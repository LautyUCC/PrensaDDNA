<?php
/** Media histórica ya versionada, solo para revisar el lote en una DB local nueva. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }
if ( 'local' !== wp_get_environment_type() || ! in_array( wp_parse_url( home_url(), PHP_URL_HOST ), array( 'localhost', '127.0.0.1', '::1' ), true ) ) { WP_CLI::error( 'Fixtures permitidos únicamente en local.' ); }
$root = getenv( 'DDNA_REVIEW_RESOURCE_ROOT' );
if ( ! $root || ! is_dir( $root ) ) { WP_CLI::error( 'Falta DDNA_REVIEW_RESOURCE_ROOT: montar los recursos históricos como solo lectura.' ); }
$items = array();
foreach ( range( 2016, 2025 ) as $year ) {
	$items[] = array( 'file' => 'Informes anuales/Informe-Anual-' . $year . '.pdf', 'title' => 'Informe Anual ' . $year, 'slug' => 'documento-informe-anual-' . $year );
}
$items[] = array( 'file' => 'Informes anuales/Memoria Gestión 2016-2026.pdf', 'title' => 'Memoria de Gestión 2016–2026', 'slug' => 'documento-memoria-de-gestion-2016-2026' );
$items[] = array( 'file' => 'Dossiers/Programa Va con Vos.pdf', 'title' => 'Va con Vos', 'key' => 'va_con_vos_dossier' );
$items[] = array( 'file' => 'Dossiers/Dossier Entre Pantallas DDNA.pdf', 'title' => 'Entre Pantallas', 'key' => 'entre_pantallas_dossier' );
$items[] = array( 'file' => 'Dossiers/Desarrollo integral en los primeros años.pdf', 'title' => 'Desarrollo Integral en los Primeros Años de Vida', 'key' => 'desarrollo_integral_dossier' );
foreach ( $items as $item ) {
	$file = $root . '/' . $item['file'];
	if ( ! is_file( $file ) || '%PDF-' !== file_get_contents( $file, false, null, 0, 5 ) ) { WP_CLI::error( 'Fixture inválido: ' . $item['file'] ); }
}
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
$links = get_option( 'ddna_editorial_links', array() );
foreach ( $items as $item ) {
	$post = ! empty( $item['slug'] ) ? get_page_by_path( $item['slug'], OBJECT, 'documento' ) : null;
	if ( $post && get_post_meta( $post->ID, '_ddna_file_id', true ) ) { continue; }
	if ( ! empty( $item['key'] ) && ! empty( $links[ $item['key'] ] ) ) { continue; }
	$hash = hash_file( 'sha256', $root . '/' . $item['file'] );
	$media = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'meta_key' => '_ddna_source_sha256', 'meta_value' => $hash, 'posts_per_page' => 1 ) );
	$id = $media ? $media[0]->ID : 0;
	if ( ! $id ) {
		$tmp = wp_tempnam( basename( $item['file'] ) );
		if ( ! $tmp || ! copy( $root . '/' . $item['file'], $tmp ) ) { WP_CLI::error( 'No se pudo copiar fixture.' ); }
		$id = media_handle_sideload( array( 'name' => basename( $item['file'] ), 'tmp_name' => $tmp ), 0, $item['title'] );
		if ( is_wp_error( $id ) ) { WP_CLI::error( $id->get_error_message() ); }
		update_post_meta( $id, '_ddna_source_sha256', $hash );
		update_post_meta( $id, '_ddna_review_fixture', 1 );
	}
	if ( ! empty( $item['slug'] ) ) {
		$post_id = $post ? $post->ID : wp_insert_post( array( 'post_type' => 'documento', 'post_status' => 'publish', 'post_name' => $item['slug'], 'post_title' => $item['title'] ), true );
		if ( is_wp_error( $post_id ) ) { WP_CLI::error( $post_id->get_error_message() ); }
		update_post_meta( $post_id, '_ddna_file_id', $id );
		update_post_meta( $post_id, '_ddna_review_fixture', 1 );
	} else { $links[ $item['key'] ] = wp_get_attachment_url( $id ); }
}
update_option( 'ddna_editorial_links', $links );
WP_CLI::success( 'Media histórica cargada para revisión local. Este script no admite producción.' );
