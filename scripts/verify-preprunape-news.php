<?php
/** Read-only checks for the one PrePRUNAPE news post. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }
$manifest = json_decode( file_get_contents( dirname( __DIR__ ) . '/content/novedad-preprunape.json' ), true );
if ( ! is_array( $manifest ) ) { WP_CLI::error( 'Falta el manifiesto.' ); }
$check = static function ( $condition, $message ) {
	if ( ! $condition ) { WP_CLI::error( $message ); }
	WP_CLI::log( 'OK: ' . $message );
};
$ids = get_posts( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 2, 'fields' => 'ids', 'meta_key' => '_ddna_news_source_id', 'meta_value' => $manifest['source_id'] ) );
$check( 1 === count( $ids ), 'Una sola publicación con la identidad editorial aprobada' );
$id = $ids[0];
$post = get_post( $id );
$check( $post->post_title === $manifest['title'] && $post->post_name === $manifest['slug'], 'Título y slug exactos' );
$check( str_starts_with( $post->post_date, $manifest['publication_date'] ), 'Fecha editorial exacta' );
$check( $post->post_content === wp_kses_post( $manifest['content'] ), 'Cinco párrafos y negritas preservados' );
$check( has_term( 'novedad', 'post_tag', $id ) && has_term( 'novedades', 'category', $id ), 'Etiqueta y categoría de Novedades' );
$check( (bool) get_post_meta( $id, '_ddna_news_enabled', true ) && get_post_meta( $id, '_ddna_preprunape_import_complete', true ), 'Entrada administrable e importación completa' );
$featured = get_post_thumbnail_id( $id );
$check( $featured && get_post_meta( $featured, '_ddna_news_asset_sha256', true ) === $manifest['featured']['sha256'], 'Portada oficial correcta' );
$gallery = get_post_meta( $id, '_ddna_news_gallery', true );
$check( (bool) get_post_meta( $id, '_ddna_news_carousel', true ) && is_array( $gallery ) && count( $gallery ) === 10, 'Carrusel de diez fotos habilitado' );
foreach ( $gallery as $index => $image_id ) {
	$check( get_post_meta( $image_id, '_ddna_news_asset_sha256', true ) === $manifest['gallery'][ $index ]['sha256'], 'Foto ' . ( $index + 1 ) . ' en orden' );
}
WP_CLI::success( 'PrePRUNAPE verificada. ID ' . $id . '; URL ' . get_permalink( $id ) );
