<?php
/** Import only the editorially approved 05/11/2026 first-childhood news post. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }
$mode = $args[0] ?? 'dry-run';
if ( ! in_array( $mode, array( 'dry-run', 'apply' ), true ) ) { WP_CLI::error( 'Usar dry-run o apply.' ); }
$local = 'local' === wp_get_environment_type() && in_array( wp_parse_url( home_url(), PHP_URL_HOST ), array( 'localhost', '127.0.0.1' ), true );
if ( ! $local && ( untrailingslashit( home_url() ) !== 'https://ddna.com.ar' || getenv( 'DDNA_NEWS_APPROVAL' ) !== 'APROBADO PARA PUBLICAR' ) ) { WP_CLI::error( 'Destino productivo no autorizado.' ); }
if ( ! function_exists( 'ddna_core_news_assign_terms' ) ) { WP_CLI::error( 'DDNA Core editorial no está activo.' ); }
$base = dirname( __DIR__ );
$manifest = json_decode( file_get_contents( $base . '/content/novedad-primera-infancia-20261105.json' ), true );
if ( ! is_array( $manifest ) || ( $manifest['version'] ?? '' ) !== 'ddna-first-childhood-news-v1' || ( $manifest['publication_date'] ?? '' ) !== '2026-11-05' || ( $manifest['source_id'] ?? '' ) !== 'ddna-news-first-childhood-mesa-provincial-20261105' || empty( $manifest['carousel'] ) || 5 !== count( $manifest['gallery'] ?? array() ) ) { WP_CLI::error( 'Manifiesto inesperado.' ); }
$root = realpath( getenv( 'DDNA_NEWS_ASSET_ROOT' ) ?: $base );
if ( ! $root ) { WP_CLI::error( 'Falta carpeta de recursos.' ); }
$resolve = static function ( $asset ) use ( $root ) {
	$path = realpath( $root . '/' . $asset['path'] );
	if ( ! $path || ! str_starts_with( $path, $root . DIRECTORY_SEPARATOR ) || ! is_file( $path ) || filesize( $path ) !== $asset['size'] || hash_file( 'sha256', $path ) !== $asset['sha256'] ) { WP_CLI::error( 'Fuente faltante o alterada: ' . $asset['path'] ); }
	return $path;
};
foreach ( array_merge( $manifest['sources'], array( $manifest['featured'] ), $manifest['gallery'] ) as $asset ) { $resolve( $asset ); }
$post_ids = get_posts( array( 'post_type' => 'post', 'post_status' => array_keys( get_post_stati() ), 'posts_per_page' => -1, 'fields' => 'ids' ) );
$identity = 0;
foreach ( $post_ids as $id ) {
	$post = get_post( $id );
	$same_source = get_post_meta( $id, '_ddna_news_source_id', true ) === $manifest['source_id'];
	$same_title = sanitize_title( html_entity_decode( $post->post_title, ENT_QUOTES | ENT_HTML5, 'UTF-8' ) ) === sanitize_title( $manifest['title'] );
	$same_slug = preg_replace( '/__trashed$/', '', $post->post_name ) === $manifest['slug'];
	if ( $same_source || $same_title || $same_slug ) {
		if ( $identity || ! $same_source || ! $same_title || ! $same_slug || 'publish' !== $post->post_status ) { WP_CLI::error( 'Identidad existente ambigua; no se duplica ni sobrescribe. ID ' . $id ); }
		$identity = $id;
	}
}
if ( $identity ) {
	if ( ! get_post_meta( $identity, '_ddna_first_childhood_import_complete', true ) ) { WP_CLI::error( 'Importación parcial; requiere revisión manual.' ); }
	WP_CLI::success( 'Novedad ya publicada, ID ' . $identity . '. Cero escrituras.' );
	return;
}
WP_CLI::log( 'Preflight: una novedad nueva, portada, cinco imágenes ordenadas, fuentes y checksums correctos; sin colisiones.' );
if ( 'dry-run' === $mode ) { WP_CLI::success( 'Dry-run correcto. Cero escrituras.' ); return; }
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
$receipt = array( 'source_id' => $manifest['source_id'], 'created_media' => array(), 'created_post' => 0, 'started_at' => current_time( 'mysql' ) );
update_option( 'ddna_first_childhood_news_20261105_receipt', $receipt, false );
$media_id = static function ( $asset ) use ( $resolve, &$receipt ) {
	$existing = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'posts_per_page' => 2, 'meta_key' => '_ddna_news_asset_sha256', 'meta_value' => $asset['sha256'], 'fields' => 'ids' ) );
	if ( count( $existing ) > 1 ) { WP_CLI::error( 'Medio duplicado en biblioteca.' ); }
	if ( $existing ) { return $existing[0]; }
	$path = $resolve( $asset );
	$tmp = wp_tempnam( basename( $path ) );
	if ( ! $tmp || ! copy( $path, $tmp ) ) { WP_CLI::error( 'No se pudo preparar imagen.' ); }
	$id = media_handle_sideload( array( 'name' => 'ddna-news-' . substr( $asset['sha256'], 0, 24 ) . '.png', 'tmp_name' => $tmp ), 0, basename( $path ) );
	if ( is_wp_error( $id ) ) { @unlink( $tmp ); WP_CLI::error( $id->get_error_message() ); }
	update_post_meta( $id, '_ddna_news_asset_sha256', $asset['sha256'] );
	$receipt['created_media'][] = $id;
	update_option( 'ddna_first_childhood_news_20261105_receipt', $receipt, false );
	return $id;
};
$featured = $media_id( $manifest['featured'] );
$gallery = array_map( $media_id, $manifest['gallery'] );
$date = $manifest['publication_date'] . ' 12:00:00';
$id = wp_insert_post( wp_slash( array( 'post_type' => 'post', 'post_status' => 'publish', 'post_name' => $manifest['slug'], 'post_title' => $manifest['title'], 'post_content' => wp_kses_post( $manifest['content'] ), 'post_date' => $date, 'post_date_gmt' => get_gmt_from_date( $date ), 'comment_status' => 'closed', 'meta_input' => array( '_ddna_news_enabled' => true, '_ddna_news_source_id' => $manifest['source_id'] ) ) ), true );
if ( is_wp_error( $id ) ) { WP_CLI::error( $id->get_error_message() ); }
$receipt['created_post'] = $id;
update_option( 'ddna_first_childhood_news_20261105_receipt', $receipt, false );
set_post_thumbnail( $id, $featured );
update_post_meta( $id, '_ddna_news_carousel', true );
update_post_meta( $id, '_ddna_news_gallery', $gallery );
update_post_meta( $id, '_ddna_news_date_policy', $manifest['date_policy'] );
ddna_core_news_assign_terms( $id );
update_post_meta( $id, '_ddna_first_childhood_import_complete', true );
WP_CLI::success( 'Novedad publicada ID ' . $id . '; portada ' . $featured . '; carrusel de ' . count( $gallery ) . ' imágenes.' );
