<?php
/** Read-only verification of every imported article and its original media. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }
$local = 'local' === wp_get_environment_type() && in_array( wp_parse_url( home_url(), PHP_URL_HOST ), array( 'localhost', '127.0.0.1', '::1' ), true );
if ( ! $local && untrailingslashit( home_url() ) !== getenv( 'DDNA_NEWS_VERIFY_ORIGIN' ) ) { WP_CLI::error( 'Verificación remota requiere origen exacto.' ); }
$m = json_decode( file_get_contents( dirname( __DIR__ ) . '/content/novedades-manifest.json' ), true );
$receipt = get_option( 'ddna_news_import_receipt', array() );
$assets = array(); $errors = array(); $rows = array();
$check = static function ( $ok, $message ) use ( &$errors ) { if ( ! $ok ) { $errors[] = $message; } };
$normalize = static fn( $text ) => trim( preg_replace( '/\s+/u', ' ', html_entity_decode( wp_strip_all_tags( $text ), ENT_QUOTES | ENT_HTML5, 'UTF-8' ) ) );
foreach ( get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'posts_per_page' => -1, 'fields' => 'ids' ) ) as $id ) {
	$file = wp_get_original_image_path( $id ) ?: get_attached_file( $id );
	if ( $file && is_file( $file ) ) { $key = hash_file( 'sha256', $file ); if ( isset( $m['assets'][ $key ] ) ) { $assets[ $key ] = $id; } }
}
foreach ( $m['assets'] as $key => $asset ) { $check( isset( $assets[ $key ] ), 'Media/checksum original: ' . $asset['path'] ); }
foreach ( $m['entries'] as $entry ) {
	$before = count( $errors );
	$posts = get_posts( array( 'post_type' => 'post', 'post_status' => 'any', 'posts_per_page' => 2, 'meta_key' => '_ddna_news_source_id', 'meta_value' => $entry['source_id'] ) );
	$check( 1 === count( $posts ), 'Origen único: ' . $entry['origin'] );
	if ( ! $posts ) { continue; }
	$p = $posts[0]; $id = $p->ID;
	$check( $p->post_title === $entry['title'] && $p->post_name === $entry['slug'] && 'publish' === $p->post_status, 'Título/slug/estado: ' . $entry['slug'] );
	$expected_text = preg_replace( '/\{\{image:[a-f0-9]{64}\}\}/', '', $entry['content'] );
	$check( $normalize( $p->post_content ) === $normalize( $expected_text ), 'Texto exacto: ' . $entry['slug'] );
	$check( has_term( 'novedad', 'post_tag', $id ), 'Tag: ' . $entry['slug'] );
	$check( (int) get_post_thumbnail_id( $id ) === ( $entry['featured_image'] ? ( $assets[ $entry['featured_image'] ] ?? -1 ) : 0 ), 'Portada exacta: ' . $entry['slug'] );
	$check( (bool) get_post_meta( $id, '_ddna_news_carousel', true ) === $entry['carousel'], 'Carrusel: ' . $entry['slug'] );
	$check( get_post_meta( $id, '_ddna_news_gallery', true ) === array_map( static fn( $key ) => $assets[ $key ] ?? -1, $entry['gallery'] ), 'Galería/cantidad/orden: ' . $entry['slug'] );
	$expected_date = $entry['original_publication_date'] ? $entry['original_publication_date'] . ' 12:00:00' : $receipt['started_at'];
	$check( $p->post_date === $expected_date, 'Fecha consistente de importación: ' . $entry['slug'] );
	$check( ! str_contains( $p->post_content, '/Users/' ) && ! str_contains( $p->post_content, '{{' ), 'Sin paths locales/placeholders: ' . $entry['slug'] );
	foreach ( $entry['links'] as $url ) { $check( str_contains( html_entity_decode( $p->post_content ), $url ), 'Enlace conservado: ' . $entry['slug'] ); }
	foreach ( $entry['attachments'] as $key ) { $check( str_contains( $p->post_content, wp_get_attachment_url( $assets[ $key ] ?? 0 ) ?: 'ASSET_MISSING' ), 'Adjunto local: ' . $entry['slug'] ); }
	foreach ( $entry['inline_images'] as $key ) { $check( str_contains( $p->post_content, 'wp-image-' . ( $assets[ $key ] ?? -1 ) ), 'Imagen inline: ' . $entry['slug'] ); }
	$rows[] = array( 'source_id' => $entry['source_id'], 'id' => $id, 'origin' => $entry['origin'], 'original_group' => $entry['original_group'], 'title' => $p->post_title, 'original_date' => $entry['original_publication_date'], 'publication_date' => $p->post_date, 'featured_image' => $entry['featured_image'] ? $m['assets'][ $entry['featured_image'] ]['path'] : null, 'carousel' => $entry['carousel'], 'gallery_count' => count( $entry['gallery'] ), 'inline_count' => count( $entry['inline_images'] ), 'tag' => 'novedad', 'url' => get_permalink( $id ), 'pending' => $entry['pending'], 'result' => $before === count( $errors ) ? 'OK' : 'ERROR' );
}
if ( $errors ) { WP_CLI::error( implode( "\n", $errors ) ); }
if ( in_array( 'json', $args ?? array(), true ) ) { echo wp_json_encode( array( 'count' => count( $rows ), 'media' => count( $assets ), 'receipt' => $receipt, 'rows' => $rows ), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ); return; }
WP_CLI::success( count( $rows ) . ' novedades verificadas: texto, título, fecha, tag, portada, galería/orden, inline y enlaces; ' . count( $assets ) . ' archivos originales con checksum correcto.' );
