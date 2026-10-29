<?php
/** Idempotent, selective Agenda update for the 2026-10-29 Prensa brief. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }

$mode = getenv( 'DDNA_AGENDA_PRENSA_MODE' ) ?: 'dry-run';
$manifest_path = getenv( 'DDNA_AGENDA_PRENSA_MANIFEST' ) ?: dirname( __DIR__ ) . '/content/agenda-prensa-2026-10-29.json';
$local = 'local' === wp_get_environment_type() && in_array( wp_parse_url( home_url(), PHP_URL_HOST ), array( 'localhost', '127.0.0.1', '::1' ), true );
if ( ! $local && ( 'APROBADO PARA PUBLICAR' !== getenv( 'DDNA_AGENDA_PRENSA_APPROVAL' ) || untrailingslashit( home_url() ) !== untrailingslashit( (string) getenv( 'DDNA_AGENDA_PRENSA_TARGET_ORIGIN' ) ) ) ) { WP_CLI::error( 'Fuera de local requiere aprobación y origen exactos.' ); }
if ( ! in_array( $mode, array( 'dry-run', 'apply', 'rollback' ), true ) ) { WP_CLI::error( 'Modo inválido.' ); }
$manifest = json_decode( file_get_contents( $manifest_path ), true );
if ( ! is_array( $manifest ) || 'agenda-prensa-2026-10-29' !== ( $manifest['version'] ?? '' ) || 4 !== count( $manifest['events'] ?? array() ) ) { WP_CLI::error( 'Manifiesto inválido.' ); }
$events = $manifest['events'];
$backup_key = 'ddna_agenda_prensa_2026_10_29_backup';
$find = static function ( $event ) {
	$by_source = get_posts( array( 'post_type' => 'agenda_evento', 'post_status' => 'any', 'posts_per_page' => 2, 'meta_key' => '_ddna_agenda_source_id', 'meta_value' => $event['id'], 'suppress_filters' => true ) );
	if ( count( $by_source ) > 1 ) { WP_CLI::error( 'Duplicado por source id: ' . $event['id'] ); }
	if ( $by_source ) { return $by_source[0]; }
	$by_title = get_posts( array( 'post_type' => 'agenda_evento', 'post_status' => 'any', 'posts_per_page' => 2, 'title' => $event['title'], 'meta_query' => array( array( 'key' => '_agenda_fecha', 'value' => $event['date'] ) ), 'suppress_filters' => true ) );
	if ( count( $by_title ) > 1 ) { WP_CLI::error( 'Duplicado por fecha/título: ' . $event['id'] ); }
	if ( $by_title ) { return $by_title[0]; }
	if ( '2026-11-30' === $event['date'] ) {
		$rows = get_posts( array( 'post_type' => 'agenda_evento', 'post_status' => 'any', 'posts_per_page' => 2, 'meta_query' => array( array( 'key' => '_agenda_fecha', 'value' => $event['date'] ) ), 'suppress_filters' => true ) );
		$rows = array_values( array_filter( $rows, static fn( $post ) => 0 === strpos( wp_strip_all_tags( $post->post_title ), 'Jornada de Certificación de Municipios MUNA' ) ) );
		if ( count( $rows ) > 1 ) { WP_CLI::error( 'Más de un candidato MUNA para el 30/11.' ); }
		if ( $rows ) { return $rows[0]; }
	}
	return null;
};
$decode = static fn( $value ) => html_entity_decode( (string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
$summary = array( 'created' => array(), 'updated' => array(), 'skipped' => array() );
if ( 'rollback' === $mode ) {
	$backup = get_option( $backup_key );
	if ( ! is_array( $backup ) ) { WP_CLI::error( 'No hay backup de esta ejecución.' ); }
	foreach ( $backup['events'] as $row ) {
		if ( ! empty( $row['created'] ) ) { wp_delete_post( (int) $row['id'], true ); continue; }
		$post = $row['before']['post']; $post['ID'] = (int) $row['id']; wp_update_post( wp_slash( $post ) );
		foreach ( get_post_meta( (int) $row['id'] ) as $key => $values ) { delete_post_meta( (int) $row['id'], $key ); }
		foreach ( $row['before']['meta'] as $key => $values ) { foreach ( $values as $value ) { add_post_meta( (int) $row['id'], $key, maybe_unserialize( $value ) ); } }
	}
	delete_option( $backup_key ); WP_CLI::success( 'Rollback selectivo de Agenda aplicado.' ); return;
}
$write = 'apply' === $mode; $backup = array( 'date' => current_time( 'mysql' ), 'events' => array() );
foreach ( $events as $event ) {
	$id = (string) $event['id']; $title = $decode( $event['title'] ); $post = $find( $event );
	if ( '2026-11-30' !== $event['date'] && ! $event['location'] ) { WP_CLI::error( 'Falta ubicación: ' . $id ); }
	if ( $post ) {
		$location = get_post_meta( $post->ID, '_agenda_lugar', true );
		$same = $post->post_title === $title && 'publish' === $post->post_status && get_post_meta( $post->ID, '_agenda_fecha', true ) === $event['date'] && get_post_meta( $post->ID, '_agenda_hora', true ) === '00:00' && ( '2026-11-30' === $event['date'] || $location === $event['location'] );
		if ( $same ) { $summary['skipped'][] = $id; if ( $write ) { update_post_meta( $post->ID, '_ddna_agenda_source_id', $id ); } continue; }
		if ( ! $write ) { $summary['updated'][] = $id; continue; }
		$backup['events'][ $id ] = array( 'created' => false, 'id' => $post->ID, 'before' => array( 'post' => $post->to_array(), 'meta' => get_post_meta( $post->ID ) ) );
		$data = array( 'ID' => $post->ID, 'post_title' => $title, 'post_content' => '<p>' . esc_html( $title ) . '</p>', 'post_status' => 'publish', 'meta_input' => array( '_agenda_fecha' => $event['date'], '_agenda_hora' => '00:00', '_ddna_agenda_source_id' => $id ) );
		if ( '2026-11-30' !== $event['date'] ) { $data['meta_input']['_agenda_lugar'] = $event['location']; }
		wp_update_post( wp_slash( $data ) ); $summary['updated'][] = $id; continue;
	}
	if ( ! $write ) { $summary['created'][] = $id; continue; }
	$data = array( 'post_type' => 'agenda_evento', 'post_title' => $title, 'post_content' => '<p>' . esc_html( $title ) . '</p>', 'post_status' => 'publish', 'meta_input' => array( '_agenda_fecha' => $event['date'], '_agenda_hora' => '00:00', '_agenda_lugar' => $event['location'], '_ddna_agenda_source_id' => $id, '_ddna_agenda_batch' => 'agenda-prensa-2026-10-29' ) );
	if ( ! $event['location'] ) { WP_CLI::error( 'Falta ubicación: ' . $id ); }
	$new_id = wp_insert_post( wp_slash( $data ), true ); if ( is_wp_error( $new_id ) ) { WP_CLI::error( $new_id->get_error_message() ); }
	$backup['events'][ $id ] = array( 'created' => true, 'id' => $new_id ); $summary['created'][] = $id;
}
if ( ! $write ) { WP_CLI::success( wp_json_encode( $summary, JSON_UNESCAPED_UNICODE ) ); return; }
add_option( $backup_key, $backup, '', false ); update_option( $backup_key, $backup, false ); WP_CLI::success( wp_json_encode( $summary, JSON_UNESCAPED_UNICODE ) );
