<?php
/**
 * Idempotent Agenda + Dossier Institucional publication.
 *
 * Modes: DDNA_AGENDA_DOSSIER_MODE=dry-run|apply|rollback
 * Outside local, apply requires:
 * DDNA_AGENDA_DOSSIER_APPROVAL=APROBADO PARA PUBLICAR
 * DDNA_AGENDA_DOSSIER_TARGET_ORIGIN=<exact home_url()>
 */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }

$local = 'local' === wp_get_environment_type() && in_array( wp_parse_url( home_url(), PHP_URL_HOST ), array( 'localhost', '127.0.0.1', '::1' ), true );
$mode = getenv( 'DDNA_AGENDA_DOSSIER_MODE' ) ?: 'dry-run';
$manifest_path = getenv( 'DDNA_AGENDA_DOSSIER_MANIFEST' ) ?: dirname( __DIR__ ) . '/content/agenda-dossier-october-2026.json';
if ( ! $local && ( 'APROBADO PARA PUBLICAR' !== getenv( 'DDNA_AGENDA_DOSSIER_APPROVAL' ) || untrailingslashit( home_url() ) !== untrailingslashit( (string) getenv( 'DDNA_AGENDA_DOSSIER_TARGET_ORIGIN' ) ) ) ) {
	WP_CLI::error( 'Fuera de local requiere aprobación y origen exactos.' );
}
if ( ! in_array( $mode, array( 'dry-run', 'apply', 'rollback' ), true ) ) { WP_CLI::error( 'Modo inválido.' ); }

$manifest = json_decode( file_get_contents( $manifest_path ), true );
if ( ! is_array( $manifest ) || 'agenda-dossier-october-2026' !== ( $manifest['version'] ?? '' ) ) { WP_CLI::error( 'Manifiesto inválido.' ); }
$digest = hash_file( 'sha256', $manifest_path );
$events = $manifest['events'] ?? array();
if ( 34 !== count( $events ) ) { WP_CLI::error( 'Se esperaban 34 eventos.' ); }
$ids = array();
foreach ( $events as $event ) {
	$id = (string) ( $event['id'] ?? '' );
	$title = (string) ( $event['title'] ?? '' );
	$date = (string) ( $event['date'] ?? '' );
	$location = (string) ( $event['location'] ?? '' );
	if ( ! $id || isset( $ids[ $id ] ) || ! preg_match( '/^2026-(?:0[1-9]|1[0-2])-(?:0[1-9]|[12][0-9]|3[01])$/D', $date ) || ! $title || ! $location ) { WP_CLI::error( 'Evento inválido: ' . $id ); }
	$ids[ $id ] = true;
}
$pdf_path = getenv( 'DDNA_AGENDA_DOSSIER_PDF' );
if ( ! $pdf_path ) {
	$pdf_path = dirname( $manifest_path ) . '/' . $manifest['pdf']['file'];
}
$pdf = $manifest['pdf'];
if ( ! is_file( $pdf_path ) || filesize( $pdf_path ) !== (int) $pdf['size'] || hash_file( 'sha256', $pdf_path ) !== $pdf['sha256'] || '%PDF-' !== file_get_contents( $pdf_path, false, null, 0, 5 ) ) {
	WP_CLI::error( 'Dossier inválido o checksum diferente.' );
}

$backup_key = 'ddna_agenda_dossier_october_backup';
$write = 'apply' === $mode;
$upsert = static function ( $event, &$summary, &$backup ) use ( $write ) {
	$existing = get_posts( array(
		'post_type' => 'agenda_evento',
		'post_status' => 'any',
		'posts_per_page' => 1,
		'meta_key' => '_ddna_agenda_source_id',
		'meta_value' => $event['id'],
		'suppress_filters' => true,
	) );
	if ( ! $existing ) {
		$existing = get_posts( array(
			'post_type' => 'agenda_evento',
			'post_status' => 'any',
			'posts_per_page' => 1,
			'title' => $event['title'],
			'meta_query' => array( array( 'key' => '_agenda_fecha', 'value' => $event['date'] ) ),
			'suppress_filters' => true,
		) );
	}
	$post = $existing ? $existing[0] : null;
	$content = '<p>' . esc_html( $event['title'] ) . '</p>';
	$data = array(
		'post_type' => 'agenda_evento',
		'post_title' => $event['title'],
		'post_content' => $content,
		'post_status' => 'publish',
		'meta_input' => array(
			'_agenda_fecha' => $event['date'],
			'_agenda_hora' => '00:00',
			'_agenda_lugar' => $event['location'],
			'_ddna_agenda_source_id' => $event['id'],
			'_ddna_agenda_batch' => 'agenda-dossier-october-2026',
		),
	);
	if ( ! $post ) {
		if ( ! $write ) { $summary['created'][] = $event['id']; return; }
		$id = wp_insert_post( wp_slash( $data ), true );
		if ( is_wp_error( $id ) ) { WP_CLI::error( $id->get_error_message() ); }
		$backup['events'][ $event['id'] ] = array( 'created' => true, 'id' => $id );
		$summary['created'][] = $event['id'];
		return;
	}
	$before = array( 'post' => $post->to_array(), 'meta' => get_post_meta( $post->ID ) );
	$same = $post->post_title === $event['title'] && $post->post_content === $content && 'publish' === $post->post_status
		&& get_post_meta( $post->ID, '_agenda_fecha', true ) === $event['date']
		&& get_post_meta( $post->ID, '_agenda_hora', true ) === '00:00'
		&& get_post_meta( $post->ID, '_agenda_lugar', true ) === $event['location'];
	if ( $same ) {
		if ( $write ) { update_post_meta( $post->ID, '_ddna_agenda_source_id', $event['id'] ); }
		$summary['skipped'][] = $event['id'];
		return;
	}
	if ( ! $write ) { $summary['updated'][] = $event['id']; return; }
	$data['ID'] = $post->ID;
	$id = wp_insert_post( wp_slash( $data ), true );
	if ( is_wp_error( $id ) ) { WP_CLI::error( $id->get_error_message() ); }
	$backup['events'][ $event['id'] ] = array( 'created' => false, 'id' => $post->ID, 'before' => $before );
	$summary['updated'][] = $event['id'];
};

if ( 'rollback' === $mode ) {
	$backup = get_option( $backup_key );
	if ( ! is_array( $backup ) ) { WP_CLI::error( 'No hay backup de esta ejecución.' ); }
	foreach ( $backup['events'] as $row ) {
		if ( ! empty( $row['created'] ) ) { wp_delete_post( (int) $row['id'], true ); continue; }
		$before = $row['before'];
		$post = $before['post']; $post['ID'] = (int) $row['id'];
		wp_update_post( wp_slash( $post ) );
		delete_post_meta( (int) $row['id'], '_agenda_fecha' ); delete_post_meta( (int) $row['id'], '_agenda_hora' ); delete_post_meta( (int) $row['id'], '_agenda_lugar' ); delete_post_meta( (int) $row['id'], '_ddna_agenda_source_id' ); delete_post_meta( (int) $row['id'], '_ddna_agenda_batch' );
		foreach ( $before['meta'] as $key => $values ) { foreach ( $values as $value ) { add_post_meta( (int) $row['id'], $key, maybe_unserialize( $value ) ); } }
	}
	if ( isset( $backup['links'] ) ) { update_option( 'ddna_editorial_links', $backup['links'], false ); }
	if ( ! empty( $backup['attachment_created'] ) ) { wp_delete_attachment( (int) $backup['attachment_created'], true ); }
	delete_option( $backup_key );
	WP_CLI::success( 'Rollback Agenda/Dossier aplicado.' );
	return;
}

$summary = array( 'created' => array(), 'updated' => array(), 'skipped' => array(), 'dossier' => 'pending' );
if ( '1' === getenv( 'DDNA_AGENDA_DOSSIER_DRY_RUN' ) || 'dry-run' === $mode ) {
	$unused = array();
	foreach ( $events as $event ) { $upsert( $event, $summary, $unused ); }
	$summary['dossier'] = 'verified';
	WP_CLI::success( wp_json_encode( $summary, JSON_UNESCAPED_UNICODE ) );
	return;
}
if ( get_option( 'ddna_agenda_dossier_october_digest' ) === $digest ) { WP_CLI::success( 'Manifiesto ya aplicado; sin escrituras.' ); return; }
$backup = array( 'date' => current_time( 'mysql' ), 'digest' => $digest, 'events' => array(), 'links' => get_option( 'ddna_editorial_links', array() ), 'attachment_created' => 0 );
add_option( $backup_key, $backup, '', false );
foreach ( $events as $event ) { $upsert( $event, $summary, $backup ); }
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
$hash_matches = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'posts_per_page' => 1, 'meta_key' => '_ddna_source_sha256', 'meta_value' => $pdf['sha256'] ) );
$attachment = $hash_matches ? (int) $hash_matches[0]->ID : 0;
if ( ! $attachment ) {
	$tmp = wp_tempnam( basename( $pdf_path ) );
	if ( ! $tmp || ! copy( $pdf_path, $tmp ) ) { WP_CLI::error( 'No se pudo preparar el Dossier.' ); }
	$attachment = media_handle_sideload( array( 'name' => basename( $pdf_path ), 'tmp_name' => $tmp ), 0, 'Dossier Institucional' );
	if ( is_wp_error( $attachment ) ) { WP_CLI::error( $attachment->get_error_message() ); }
	update_post_meta( $attachment, '_ddna_source_sha256', $pdf['sha256'] );
	$backup['attachment_created'] = $attachment;
}
$links = get_option( 'ddna_editorial_links', array() );
$links['institutional_dossier'] = wp_get_attachment_url( $attachment );
update_option( 'ddna_editorial_links', $links, false );
$backup['links_after'] = $links;
update_option( $backup_key, $backup, false );
update_option( 'ddna_agenda_dossier_october_digest', $digest, false );
$summary['dossier'] = array( 'attachment_id' => $attachment, 'url' => wp_get_attachment_url( $attachment ) );
WP_CLI::success( wp_json_encode( $summary, JSON_UNESCAPED_UNICODE ) );
