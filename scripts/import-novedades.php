<?php
/** Explicit WP-CLI import: dry-run by default, local apply, guarded future approved target. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }
$mode = $args[0] ?? 'dry-run';
if ( ! in_array( $mode, array( 'dry-run', 'apply' ), true ) ) { WP_CLI::error( 'Usar dry-run o apply.' ); }
$local = 'local' === wp_get_environment_type() && in_array( wp_parse_url( home_url(), PHP_URL_HOST ), array( 'localhost', '127.0.0.1', '::1' ), true );
if ( ! $local && ( getenv( 'DDNA_NEWS_APPROVAL' ) !== 'APROBADO PARA PUBLICAR' || untrailingslashit( home_url() ) !== getenv( 'DDNA_NEWS_TARGET_ORIGIN' ) ) ) { WP_CLI::error( 'Destino no local bloqueado: requiere aprobación explícita y origen exacto.' ); }
if ( ! function_exists( 'ddna_core_news_assign_terms' ) ) { WP_CLI::error( 'DDNA Core editorial no está activo.' ); }
$manifest_path = dirname( __DIR__ ) . '/content/novedades-manifest.json';
$manifest = json_decode( file_get_contents( $manifest_path ), true );
if ( ! is_array( $manifest ) || 'ddna-novedades-2026-v1' !== ( $manifest['version'] ?? '' ) || empty( $manifest['entries'] ) || ! isset( $manifest['assets'] ) ) { WP_CLI::error( 'Manifiesto inválido.' ); }
$root = realpath( getenv( 'DDNA_NEWS_ASSET_ROOT' ) ?: dirname( __DIR__ ) . '/' . $manifest['source_root'] );
if ( ! $root ) { WP_CLI::error( 'Falta el bundle de fuentes. Definir DDNA_NEWS_ASSET_ROOT con la ruta montada.' ); }
$resolve = static function ( $relative ) use ( $root ) {
	if ( ! is_string( $relative ) || str_contains( $relative, "\0" ) ) { WP_CLI::error( 'Ruta fuente inválida.' ); }
	$file = realpath( $root . '/' . $relative );
	if ( ! $file || ! is_file( $file ) || ! str_starts_with( $file, $root . DIRECTORY_SEPARATOR ) ) { WP_CLI::error( 'Fuente inexistente o fuera del bundle: ' . $relative ); }
	return $file;
};
// Validate every asset, source and collision BEFORE any database/media write.
foreach ( $manifest['assets'] as $key => $asset ) {
	$file = $resolve( $asset['path'] );
	if ( ! preg_match( '/^[a-f0-9]{64}$/D', $key ) || $key !== $asset['sha256'] || filesize( $file ) !== $asset['size'] || hash_file( 'sha256', $file ) !== $key ) { WP_CLI::error( 'Checksum/size inválido: ' . $asset['path'] ); }
	$mime = ( new finfo( FILEINFO_MIME_TYPE ) )->file( $file );
	if ( ! in_array( $mime, array( 'image/jpeg', 'image/png', 'image/webp', 'application/pdf' ), true ) ) { WP_CLI::error( 'Formato no permitido: ' . $asset['path'] ); }
}
$seen = array(); $existing_ids = array();
foreach ( $manifest['entries'] as $entry ) {
	$source_id = $entry['source_id'];
	if ( isset( $seen[ $source_id ] ) || empty( $entry['title'] ) || empty( $entry['slug'] ) || 'novedad' !== $entry['tag'] || 'publish' !== $entry['status'] ) { WP_CLI::error( 'Entrada inválida o repetida.' ); }
	$seen[ $source_id ] = true;
	foreach ( $entry['sources'] as $source ) { if ( hash_file( 'sha256', $resolve( $source['path'] ) ) !== $source['sha256'] ) { WP_CLI::error( 'Documento fuente cambió: ' . $source['path'] ); } }
	foreach ( array_merge( $entry['gallery'], $entry['inline_images'], $entry['attachments'], $entry['featured_image'] ? array( $entry['featured_image'] ) : array() ) as $key ) { if ( ! isset( $manifest['assets'][ $key ] ) ) { WP_CLI::error( 'Asset desconocido: ' . $source_id ); } }
	preg_match_all( '/\{\{(?:image|media):([a-f0-9]{64})\}\}/', $entry['content'], $matches );
	foreach ( $matches[1] as $key ) { if ( ! isset( $manifest['assets'][ $key ] ) ) { WP_CLI::error( 'Placeholder inválido.' ); } }
	$matches = get_posts( array( 'post_type' => 'post', 'post_status' => 'any', 'posts_per_page' => 2, 'meta_key' => '_ddna_news_source_id', 'meta_value' => $source_id, 'fields' => 'ids' ) );
	if ( count( $matches ) > 1 ) { WP_CLI::error( 'ID de origen duplicado: ' . $source_id ); }
	$existing_ids[ $source_id ] = $matches ? $matches[0] : 0;
	$collision = get_page_by_path( $entry['slug'], OBJECT, 'post' );
	if ( $collision && $collision->ID !== $existing_ids[ $source_id ] ) { WP_CLI::error( 'Slug ocupado por contenido ajeno: ' . $entry['slug'] ); }
	if ( $entry['original_publication_date'] && ( ! preg_match( '/^\d{4}-\d{2}-\d{2}$/D', $entry['original_publication_date'] ) || ! strtotime( $entry['original_publication_date'] ) ) ) { WP_CLI::error( 'Fecha inválida.' ); }
}
WP_CLI::log( 'Preflight: ' . count( $manifest['entries'] ) . ' novedades, ' . count( $manifest['assets'] ) . ' assets únicos, fuentes/checksums/rutas/colisiones verificados.' );
if ( 'dry-run' === $mode ) { WP_CLI::success( 'Dry-run correcto. Sin escrituras.' ); return; }
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
$receipt = get_option( 'ddna_news_import_receipt', array( 'started_at' => current_time( 'mysql' ), 'created_posts' => array(), 'created_media' => array(), 'legacy_posts_tagged' => array() ) );
$receipt['manifest_sha256'] = hash_file( 'sha256', $manifest_path );
$save_receipt = static function () use ( &$receipt ) { update_option( 'ddna_news_import_receipt', $receipt, false ); };
$media_index = array();
// Hash existing originals once, including media created by earlier institutional imports.
foreach ( get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'posts_per_page' => -1, 'fields' => 'ids' ) ) as $id ) {
	$file = wp_get_original_image_path( $id ) ?: get_attached_file( $id );
	if ( $file && is_file( $file ) ) { $media_index[ hash_file( 'sha256', $file ) ] = $id; }
}
$media_id = static function ( $key ) use ( &$media_index, $manifest, $resolve, &$receipt, $save_receipt ) {
	if ( isset( $media_index[ $key ] ) ) { return $media_index[ $key ]; }
	$asset = $manifest['assets'][ $key ]; $file = $resolve( $asset['path'] );
	$name = 'ddna-news-' . substr( $key, 0, 24 ) . '.' . strtolower( pathinfo( $file, PATHINFO_EXTENSION ) );
	$tmp = wp_tempnam( $name );
	if ( ! $tmp || ! copy( $file, $tmp ) ) { WP_CLI::error( 'No se pudo preparar el archivo: ' . $asset['path'] ); }
	$id = media_handle_sideload( array( 'name' => $name, 'tmp_name' => $tmp ), 0, $asset['filename'] );
	if ( is_wp_error( $id ) ) { @unlink( $tmp ); $receipt['error'] = $id->get_error_message(); $save_receipt(); WP_CLI::error( $receipt['error'] ); }
	$receipt['created_media'][] = $id; $save_receipt();
	update_post_meta( $id, '_ddna_news_asset_sha256', $key );
	update_post_meta( $id, '_ddna_news_asset_source', $asset['path'] );
	if ( wp_attachment_is_image( $id ) ) { update_post_meta( $id, '_wp_attachment_image_alt', sanitize_text_field( $asset['alt'] ) ); }
	$media_index[ $key ] = $id;
	return $id;
};
// Preserve prior news and their category URLs, adding only the required identification tag.
$category = get_category_by_slug( 'novedades' );
if ( $category ) {
	foreach ( get_posts( array( 'post_type' => 'post', 'post_status' => 'any', 'posts_per_page' => -1, 'cat' => $category->term_id, 'fields' => 'ids' ) ) as $id ) {
		if ( ! has_term( 'novedad', 'post_tag', $id ) ) {
			$receipt['legacy_posts_tagged'][ $id ] = wp_get_post_terms( $id, 'post_tag', array( 'fields' => 'ids' ) ); $save_receipt();
			ddna_core_news_assign_terms( $id );
		}
	}
}
$created = 0; $updated = 0; $skipped = 0;
foreach ( $manifest['entries'] as $entry ) {
	$id = $existing_ids[ $entry['source_id'] ];
	if ( $id && get_post_meta( $id, '_ddna_news_import_complete', true ) ) { $skipped++; WP_CLI::log( 'Conservada (sin sobrescribir ediciones): ' . $entry['slug'] ); continue; }
	$gallery = array_map( $media_id, $entry['gallery'] );
	$featured = $entry['featured_image'] ? $media_id( $entry['featured_image'] ) : 0;
	$content = preg_replace_callback( '/\{\{(image|media):([a-f0-9]{64})\}\}/', static function ( $match ) use ( $media_id ) {
		$id = $media_id( $match[2] );
		return 'media' === $match[1] ? esc_url( wp_get_attachment_url( $id ) ) : '<!-- wp:image {"id":' . $id . ',"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large">' . wp_get_attachment_image( $id, 'large', false, array( 'class' => 'wp-image-' . $id ) ) . '</figure><!-- /wp:image -->';
	}, $entry['content'] );
	$date = $entry['original_publication_date'] ? $entry['original_publication_date'] . ' 12:00:00' : $receipt['started_at'];
	$is_new = ! $id;
	$id = wp_insert_post( wp_slash( array( 'ID' => $id, 'post_type' => 'post', 'post_status' => $entry['status'], 'post_name' => $entry['slug'], 'post_title' => $entry['title'], 'post_content' => wp_kses_post( $content ), 'post_date' => $date, 'post_date_gmt' => get_gmt_from_date( $date ), 'comment_status' => 'closed', 'meta_input' => array( '_ddna_news_enabled' => true, '_ddna_news_source_id' => $entry['source_id'] ) ) ), true );
	if ( is_wp_error( $id ) ) { $receipt['error'] = $id->get_error_message(); $save_receipt(); WP_CLI::error( $receipt['error'] ); }
	if ( $is_new ) { $receipt['created_posts'][] = $id; $save_receipt(); }
	if ( $featured ) { set_post_thumbnail( $id, $featured ); }
	update_post_meta( $id, '_ddna_news_carousel', $entry['carousel'] );
	update_post_meta( $id, '_ddna_news_gallery', $gallery );
	update_post_meta( $id, '_ddna_news_pending', $entry['pending'] );
	update_post_meta( $id, '_ddna_news_original_group', $entry['original_group'] );
	update_post_meta( $id, '_ddna_news_origin', $entry['origin'] );
	update_post_meta( $id, '_ddna_news_import_date', $receipt['started_at'] );
	update_post_meta( $id, '_ddna_news_date_policy', $entry['date_policy'] );
	update_post_meta( $id, '_ddna_news_import_entry_sha256', hash( 'sha256', wp_json_encode( $entry ) ) );
	ddna_core_news_assign_terms( $id );
	update_post_meta( $id, '_ddna_news_import_complete', true );
	if ( $is_new ) { $created++; } else { $updated++; }
	WP_CLI::log( ( $is_new ? 'Creada: ' : 'Completada: ' ) . $entry['slug'] );
}
$receipt['completed_at'] = current_time( 'mysql' ); unset( $receipt['error'] ); $save_receipt();
WP_CLI::success( $created . ' creadas; ' . $updated . ' completadas; ' . $skipped . ' conservadas; ' . count( $receipt['created_media'] ) . ' media creados acumulados. Recibo: ddna_news_import_receipt.' );
