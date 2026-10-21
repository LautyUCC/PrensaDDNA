<?php
/** Read-only verification of the explicit eight-entry editorial batch. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }
$manifest = json_decode( file_get_contents( dirname( __DIR__ ) . '/content/novedades-octubre-2026.json' ), true );
$rows = array();
foreach ( $manifest['entries'] as $entry ) {
	$ids = get_posts( array( 'post_type' => 'post', 'post_status' => array_keys( get_post_stati() ), 'posts_per_page' => -1, 'fields' => 'ids', 'meta_key' => '_ddna_news_source_id', 'meta_value' => $entry['source_id'] ) );
	if ( count( $ids ) !== 1 ) { WP_CLI::error( 'Identidad ausente o repetida: ' . $entry['source_id'] ); }
	$id = $ids[0]; $post = get_post( $id );
	if ( 'publish' !== $post->post_status || $post->post_title !== $entry['title'] || substr( $post->post_date, 0, 10 ) !== $entry['original_publication_date'] || ! has_term( 'novedad', 'post_tag', $id ) ) { WP_CLI::error( 'Título/fecha/estado/tag incorrecto: ' . $id ); }
	$image_hash = static function ( $media_id ) {
		$file = wp_get_original_image_path( $media_id ) ?: get_attached_file( $media_id );
		if ( ! $file || ! is_file( $file ) ) { WP_CLI::error( 'Media inexistente: ' . $media_id ); }
		return hash_file( 'sha256', $file );
	};
	if ( $image_hash( get_post_thumbnail_id( $id ) ) !== $entry['featured_image'] ) { WP_CLI::error( 'Portada incorrecta: ' . $id ); }
	$gallery = ddna_core_news_image_ids( get_post_meta( $id, '_ddna_news_gallery', true ) );
	if ( array_map( $image_hash, $gallery ) !== $entry['gallery'] || (bool) get_post_meta( $id, '_ddna_news_carousel', true ) !== $entry['carousel'] ) { WP_CLI::error( 'Galería/orden incorrecto: ' . $id ); }
	preg_match_all( '/wp-image-(\d+)/', $post->post_content, $inline );
	if ( array_map( $image_hash, array_map( 'intval', $inline[1] ) ) !== $entry['inline_images'] ) { WP_CLI::error( 'Imágenes inline incorrectas: ' . $id ); }
	if ( str_contains( $post->post_content, '/Users/' ) || str_contains( $post->post_content, '{{image:' ) ) { WP_CLI::error( 'Ruta/placeholder sin resolver: ' . $id ); }
	foreach ( $entry['links'] as $link ) { if ( ! str_contains( $post->post_content, esc_url( $link ) ) && ! str_contains( $post->post_content, $link ) ) { WP_CLI::error( 'Link faltante: ' . $id ); } }
	$rows[] = array( 'id' => $id, 'title' => $post->post_title, 'date' => $post->post_date, 'url' => get_permalink( $id ), 'featured_id' => get_post_thumbnail_id( $id ), 'featured_file' => $manifest['assets'][ $entry['featured_image'] ]['path'], 'carousel' => $entry['carousel'], 'gallery_count' => count( $gallery ), 'inline_count' => count( $inline[1] ), 'total_images' => 1 + count( $gallery ) + count( $inline[1] ) );
}
$receipt = get_option( 'ddna_news_october_2026_receipt' );
echo wp_json_encode( array( 'entries' => $rows, 'created_posts' => $receipt['created_posts'], 'updated_posts' => array_keys( $receipt['updated_before'] ), 'created_media' => $receipt['created_media'], 'archive_url' => get_category_link( get_category_by_slug( 'novedades' ) ), 'home_url' => home_url( '/' ) ), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . PHP_EOL;
