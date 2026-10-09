<?php
/** Meaningful local integration tests; only marked disposable fixtures are removed. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }
if ( 'local' !== wp_get_environment_type() || ! in_array( wp_parse_url( home_url(), PHP_URL_HOST ), array( 'localhost', '127.0.0.1', '::1' ), true ) ) { WP_CLI::error( 'Solo local.' ); }
$original_user = get_current_user_id(); $original_post = $_POST; $created = array(); $term_id = 0; $error = null;
$assert = static function ( $condition, $message ) { if ( ! $condition ) { throw new RuntimeException( $message ); } };
try {
	$admins = get_users( array( 'role' => 'administrator', 'number' => 1 ) );
	$assert( (bool) $admins, 'Falta administrador local existente.' );
	wp_set_current_user( $admins[0]->ID );
	$images = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'posts_per_page' => 2, 'post_mime_type' => 'image', 'fields' => 'ids' ) );
	$assert( 2 === count( $images ), 'Se requieren dos imágenes locales existentes.' );
	$assert( ddna_core_news_image_ids( array( $images[1], $images[0], $images[1], 0, -999999 ) ) === array( $images[1], $images[0] ), 'Sanitización/order/deduplicación de imágenes.' );
	$request = new WP_REST_Request( 'POST', '/wp/v2/posts' );
	$request->set_body_params( array( 'title' => 'DDNA NEWS QA fixture descartable', 'content' => 'Prueba editorial local descartable.', 'status' => 'publish', 'featured_media' => $images[0], 'meta' => array( '_ddna_news_enabled' => true, '_ddna_news_carousel' => true, '_ddna_news_gallery' => array( $images[1], $images[0] ) ) ) );
	$response = rest_do_request( $request ); $data = $response->get_data();
	$assert( 201 === $response->get_status(), 'Creación REST editorial: ' . wp_json_encode( $data ) );
	$id = $data['id']; $created[] = $id; update_post_meta( $id, '_ddna_news_qa_fixture', true );
	$assert( has_term( 'novedad', 'post_tag', $id ) && has_category( 'novedades', $id ), 'Tag automático y categoría compatible.' );
	$assert( get_post_meta( $id, '_ddna_news_gallery', true ) === array( $images[1], $images[0] ), 'REST conserva orden de galería.' );
	$assert( get_post_thumbnail_id( $id ) === $images[0], 'Imagen destacada nativa.' );
	$other = wp_insert_term( 'DDNA NEWS QA etiqueta adicional', 'post_tag', array( 'slug' => 'ddna-news-qa-extra' ) );
	$assert( ! is_wp_error( $other ), 'Etiqueta fixture.' ); $term_id = $other['term_id'];
	wp_set_post_terms( $id, array( $term_id ), 'post_tag', true );
	$_POST = array( 'ddna_news_nonce' => 'invalid', 'ddna_news_enabled' => 1, 'ddna_news_gallery' => '' );
	do_action( 'save_post_post', $id );
	$assert( get_post_meta( $id, '_ddna_news_gallery', true ) === array( $images[1], $images[0] ), 'Nonce inválido no modifica campos.' );
	$_POST = array( 'ddna_news_nonce' => wp_create_nonce( 'ddna_news_save' ), 'ddna_news_enabled' => 1, 'ddna_news_carousel' => 1, 'ddna_news_gallery' => $images[0] . ',' . $images[1] . ',' . $images[0] . ',0' );
	do_action( 'save_post_post', $id );
	$assert( get_post_meta( $id, '_ddna_news_gallery', true ) === $images, 'Formulario nativo sanea y reordena.' );
	$assert( has_term( $term_id, 'post_tag', $id ), 'Etiquetas adicionales conservadas.' );
	wp_set_current_user( 0 ); do_action( 'save_post_post', $id );
	$assert( get_post_meta( $id, '_ddna_news_gallery', true ) === $images, 'Usuario sin permiso no modifica.' );
	wp_set_current_user( $admins[0]->ID );
	$_POST = array( 'ddna_news_nonce' => wp_create_nonce( 'ddna_news_save' ), 'ddna_news_gallery' => implode( ',', $images ) );
	do_action( 'save_post_post', $id );
	$assert( ! has_term( 'novedad', 'post_tag', $id ) && has_term( $term_id, 'post_tag', $id ), 'Desmarcar excluye solo de Novedades.' );
	$assert( ! get_post_meta( $id, '_ddna_news_carousel', true ), 'Carrusel desactivado.' );
	$_POST = array();
	$request = new WP_REST_Request( 'POST', '/wp/v2/posts/' . $id );
	$request->set_body_params( array( 'meta' => array( '_ddna_news_enabled' => true ) ) );
	$assert( 200 === rest_do_request( $request )->get_status(), 'Edición REST.' );
	$assert( has_term( 'novedad', 'post_tag', $id ) && get_post_meta( $id, '_ddna_news_gallery', true ) === $images, 'Actualización parcial no pierde galería y vuelve a etiquetar.' );
	// A tag-only post must appear in the archive even without the legacy category.
	wp_remove_object_terms( $id, 'novedades', 'category' );
	$http_origin = getenv( 'DDNA_NEWS_TEST_HTTP_ORIGIN' ) ?: home_url();
	$http_headers = array( 'headers' => array( 'Host' => wp_parse_url( home_url(), PHP_URL_HOST ) . ( wp_parse_url( home_url(), PHP_URL_PORT ) ? ':' . wp_parse_url( home_url(), PHP_URL_PORT ) : '' ) ) );
	$archive = wp_remote_get( $http_origin . '/category/novedades/?buscar=DDNA+NEWS+QA', $http_headers );
	$assert( ! is_wp_error( $archive ) && 200 === wp_remote_retrieve_response_code( $archive ) && str_contains( wp_remote_retrieve_body( $archive ), 'post-' . $id ), 'Archivo consultado por tag, no categoría.' );
	$home = wp_remote_get( $http_origin . '/', $http_headers );
	$assert( ! is_wp_error( $home ) && str_contains( wp_remote_retrieve_body( $home ), get_permalink( $id ) ), 'Novedad futura aparece dinámicamente en Home.' );
	ob_start(); ddna_core_news_meta_box( get_post( $id ) ); $box = ob_get_clean();
	$assert( str_contains( $box, 'Mostrar carrusel' ) && str_contains( $box, 'data-news-gallery-add' ) && str_contains( $box, 'ddna_news_nonce' ), 'Controles editoriales nativos.' );
} catch ( Throwable $e ) { $error = $e->getMessage(); }
finally {
	$_POST = array();
	foreach ( $created as $id ) { if ( get_post_meta( $id, '_ddna_news_qa_fixture', true ) ) { wp_delete_post( $id, true ); } }
	if ( $term_id ) { wp_delete_term( $term_id, 'post_tag' ); }
	$_POST = $original_post; wp_set_current_user( $original_user );
}
if ( $error ) { WP_CLI::error( $error ); }
WP_CLI::success( 'Flujo REST/formulario, tag automático, etiquetas adicionales, portada, galería/orden/sanitización, nonce/capabilities, Home y archivo por tag verificados; fixtures retirados.' );
