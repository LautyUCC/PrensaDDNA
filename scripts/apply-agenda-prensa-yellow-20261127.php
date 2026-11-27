<?php
/** Idempotent import of only the three current yellow Agenda additions confirmed by Prensa. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }

$mode = $args[0] ?? 'dry-run';
if ( ! in_array( $mode, array( 'dry-run', 'apply', 'rollback' ), true ) ) { WP_CLI::error( 'Usar dry-run, apply o rollback.' ); }
$origin = untrailingslashit( home_url() );
$local = 'local' === wp_get_environment_type() && in_array( wp_parse_url( $origin, PHP_URL_HOST ), array( 'localhost', '127.0.0.1' ), true );
if ( ! $local && ( 'https://ddna.com.ar' !== $origin || 'APROBADO PARA PUBLICAR' !== getenv( 'DDNA_AGENDA_YELLOW_APPROVAL' ) ) ) { WP_CLI::error( 'Destino productivo no autorizado.' ); }

$manifest = json_decode( file_get_contents( dirname( __DIR__ ) . '/content/agenda-prensa-yellow-2026-11-27.json' ), true );
if ( ! is_array( $manifest ) || 'agenda-prensa-yellow-2026-11-27' !== ( $manifest['version'] ?? '' ) || 3 !== count( $manifest['events'] ?? array() ) || 'c770df523ffc113b5d7655f5a4f4b6e61354c8fa254e57a29872df80b36cce1d' !== ( $manifest['exported_docx_sha256'] ?? '' ) ) { WP_CLI::error( 'Manifiesto editorial inesperado.' ); }
$receipt_key = 'ddna_agenda_prensa_yellow_20261127_receipt';

if ( 'rollback' === $mode ) {
	$receipt = get_option( $receipt_key );
	if ( ! is_array( $receipt ) || $origin !== ( $receipt['origin'] ?? '' ) ) { WP_CLI::error( 'Checkpoint de Agenda ausente o de otro origen.' ); }
	foreach ( $receipt['created'] as $source_id => $post_id ) {
		$post = get_post( $post_id );
		if ( ! $post || 'agenda_evento' !== $post->post_type || get_post_meta( $post_id, '_ddna_agenda_source_id', true ) !== $source_id || 'agenda-prensa-yellow-2026-11-27' !== get_post_meta( $post_id, '_ddna_agenda_batch', true ) ) { WP_CLI::error( 'Registro de rollback alterado: ' . $source_id ); }
	}
	foreach ( $receipt['created'] as $post_id ) { if ( ! wp_trash_post( $post_id ) ) { WP_CLI::error( 'No se pudo retirar el evento ID ' . $post_id ); } }
	delete_option( $receipt_key );
	WP_CLI::success( 'Solo los eventos creados por este lote fueron enviados a la papelera.' );
	return;
}

$posts = get_posts( array( 'post_type' => 'agenda_evento', 'post_status' => array_keys( get_post_stati() ), 'posts_per_page' => -1, 'suppress_filters' => true ) );
$normalize = static fn( $title ) => sanitize_title( ddna_core_agenda_decode_text( $title ) );
$plan = array(); $ids = array();
foreach ( $manifest['events'] as $event ) {
	$id = $event['id'] ?? ''; $date = $event['date'] ?? ''; $title = $event['title'] ?? '';
	if ( ! is_string( $id ) || isset( $ids[ $id ] ) || ! is_string( $title ) || ! trim( $title ) || ! in_array( $date, array( '2026-11-25', '2026-12-02', '2026-12-10' ), true ) || 'agregar' !== ( $event['instruction'] ?? '' ) ) { WP_CLI::error( 'Actividad inesperada en el manifiesto.' ); }
	$ids[ $id ] = true;
	$fields = array( '_agenda_fecha' => $date, '_agenda_hora' => '00:00', '_agenda_lugar' => $event['location'] ?? '' );
	if ( is_wp_error( ddna_core_agenda_validate_fields( $fields ) ) ) { WP_CLI::error( 'Campos inválidos: ' . $id ); }
	$matches = array_values( array_filter( $posts, static fn( $post ) => get_post_meta( $post->ID, '_ddna_agenda_source_id', true ) === $id || $normalize( $post->post_title ) === $normalize( $title ) ) );
	if ( count( $matches ) > 1 ) { WP_CLI::error( 'Duplicado o identidad ambigua: ' . $id ); }
	if ( $matches ) {
		$post = $matches[0];
		if ( 'publish' !== $post->post_status || ddna_core_agenda_decode_text( $post->post_title ) !== $title || get_post_meta( $post->ID, '_agenda_fecha', true ) !== $date || get_post_meta( $post->ID, '_agenda_hora', true ) !== '00:00' || get_post_meta( $post->ID, '_agenda_lugar', true ) !== $event['location'] || get_post_meta( $post->ID, '_ddna_agenda_source_id', true ) !== $id ) { WP_CLI::error( 'Posible actualización o duplicado en otra fecha; revisar: ' . $id ); }
		$plan[] = array( 'id' => $id, 'date' => $date, 'title' => $title, 'action' => 'SKIP', 'post_id' => $post->ID );
	} else {
		$plan[] = array( 'id' => $id, 'date' => $date, 'title' => $title, 'action' => 'CREATE' );
	}
}
WP_CLI::log( wp_json_encode( $plan, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) );
if ( 'dry-run' === $mode || ! array_filter( $plan, static fn( $row ) => 'CREATE' === $row['action'] ) ) { WP_CLI::success( 'Plan verificado. Cero escrituras.' ); return; }

if ( get_option( $receipt_key ) ) { WP_CLI::error( 'Hay un checkpoint previo; revisar antes de crear nuevos eventos.' ); }
$receipt = array( 'origin' => $origin, 'created' => array(), 'started_at' => current_time( 'mysql' ) );
if ( ! add_option( $receipt_key, $receipt, '', false ) ) { WP_CLI::error( 'No se pudo guardar el checkpoint.' ); }
foreach ( $manifest['events'] as $event ) {
	$post_id = wp_insert_post( wp_slash( array(
		'post_type' => 'agenda_evento', 'post_status' => 'publish', 'post_title' => $event['title'], 'post_content' => '<p>' . esc_html( $event['title'] ) . '</p>',
		'meta_input' => array( '_agenda_fecha' => $event['date'], '_agenda_hora' => '00:00', '_agenda_lugar' => $event['location'], '_ddna_agenda_source_id' => $event['id'], '_ddna_agenda_batch' => 'agenda-prensa-yellow-2026-11-27' ),
	) ), true );
	if ( is_wp_error( $post_id ) ) { WP_CLI::error( $post_id->get_error_message() ); }
	$receipt['created'][ $event['id'] ] = $post_id;
	update_option( $receipt_key, $receipt, false );
}
WP_CLI::success( 'Se crearon ' . count( $receipt['created'] ) . ' eventos de Agenda; checkpoint conservado.' );
