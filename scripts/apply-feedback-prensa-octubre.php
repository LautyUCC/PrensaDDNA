<?php
/** Importación editorial y de media reproducible; nunca se ejecuta al cargar el sitio. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }
$local = 'local' === wp_get_environment_type() && in_array( wp_parse_url( home_url(), PHP_URL_HOST ), array( 'localhost', '127.0.0.1', '::1' ), true );
if ( ! $local && ( 'APROBADO PARA PUBLICAR' !== getenv( 'DDNA_FEEDBACK_APPROVAL' ) || untrailingslashit( home_url() ) !== getenv( 'DDNA_FEEDBACK_TARGET_ORIGIN' ) ) ) {
	WP_CLI::error( 'Importación fuera de local bloqueada: requiere aprobación explícita y origen de destino exacto.' );
}
$root = dirname( __DIR__ );
$path = $root . '/content/feedback-prensa-octubre.json';
$manifest = json_decode( file_get_contents( $path ), true );
if ( ! is_array( $manifest ) || 'feedback-prensa-octubre-2026' !== ( $manifest['version'] ?? '' ) ) { WP_CLI::error( 'Manifiesto inválido.' ); }
$digest = hash_file( 'sha256', $path );
// Validar TODO antes de la primera escritura. Solo media curada dentro del bundle.
$pdfs = array_merge( $manifest['guides'], array_filter( $manifest['normativa']['documents'], static fn( $item ) => ! empty( $item['file'] ) ) );
foreach ( $pdfs as $guide ) {
	$file = realpath( $root . '/content/' . $guide['file'] );
	$media_root = realpath( $root . '/content/media/feedback-octubre' ) . DIRECTORY_SEPARATOR;
	if ( ! $file || ! str_starts_with( $file, $media_root ) || filesize( $file ) !== $guide['size'] || hash_file( 'sha256', $file ) !== $guide['sha256'] || '%PDF-' !== file_get_contents( $file, false, null, 0, 5 ) || 'application/pdf' !== ( new finfo( FILEINFO_MIME_TYPE ) )->file( $file ) ) {
		WP_CLI::error( 'PDF no válido o checksum diferente: ' . $guide['file'] );
	}
}
if ( '1' === getenv( 'DDNA_FEEDBACK_DRY_RUN' ) ) { WP_CLI::success( 'Preflight correcto: ' . count( $pdfs ) . ' PDFs verificados; sin escrituras.' ); return; }
if ( get_option( 'ddna_feedback_prensa_octubre_digest' ) === $digest ) { WP_CLI::success( 'Este manifiesto ya está aplicado; se conservan ediciones posteriores.' ); return; }
$backup = array( 'date' => current_time( 'mysql' ), 'posts' => array(), 'options' => array() );
foreach ( $manifest['pages'] as $page ) {
	$post = get_page_by_path( $page['slug'] );
	if ( $post ) { $backup['posts'][ $post->ID ] = array( 'post' => $post->to_array(), 'meta' => get_post_meta( $post->ID ) ); }
}
foreach ( array( 'ddna_editorial_links', 'ddna_feedback_conventions', 'ddna_care_guides', 'ddna_normativa_sections', 'ddna_home_settings' ) as $key ) { $backup['options'][ $key ] = get_option( $key ); }
add_option( 'ddna_backup_before_feedback_octubre', $backup, '', false );
$upsert = static function ( $type, $slug, $data ) {
	$existing = get_page_by_path( $slug, OBJECT, $type );
	$data = array_merge( array( 'post_type' => $type, 'post_name' => $slug, 'post_status' => 'publish' ), $data );
	if ( $existing ) { $data['ID'] = $existing->ID; }
	$id = wp_insert_post( wp_slash( $data ), true );
	if ( is_wp_error( $id ) ) { WP_CLI::error( $id->get_error_message() ); }
	update_post_meta( $id, '_ddna_content_version', 'feedback-prensa-octubre-2026' );
	return $id;
};
foreach ( $manifest['pages'] as $page ) {
	// No reemplazar contenido existente de Informes Anuales.
	if ( ! empty( $page['create_only'] ) && get_page_by_path( $page['slug'] ) ) { continue; }
	$upsert( 'page', $page['slug'], array( 'post_title' => $page['title'], 'post_content' => wp_kses_post( $page['content'] ) ) );
}
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
$import_pdf = static function ( $guide, $order ) use ( $upsert, $root ) {
	$id = $upsert( 'documento', $guide['slug'], array( 'post_title' => $guide['title'], 'menu_order' => $order ) );
	$attachments = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'posts_per_page' => 1, 'meta_key' => '_ddna_source_sha256', 'meta_value' => $guide['sha256'] ) );
	$attachment = $attachments ? $attachments[0]->ID : 0;
	if ( $attachment && ( ! is_file( get_attached_file( $attachment ) ) || hash_file( 'sha256', get_attached_file( $attachment ) ) !== $guide['sha256'] ) ) { $attachment = 0; }
	if ( ! $attachment ) {
		$tmp = wp_tempnam( basename( $guide['file'] ) );
		if ( ! $tmp || ! copy( $root . '/content/' . $guide['file'], $tmp ) ) { WP_CLI::error( 'No se pudo preparar la importación.' ); }
		$attachment = media_handle_sideload( array( 'name' => basename( $guide['file'] ), 'tmp_name' => $tmp ), $id, $guide['title'] );
		if ( is_wp_error( $attachment ) ) { if ( is_file( $tmp ) ) { unlink( $tmp ); } WP_CLI::error( $attachment->get_error_message() ); }
		update_post_meta( $attachment, '_ddna_source_sha256', $guide['sha256'] );
	}
	update_post_meta( $id, '_ddna_file_id', $attachment );
	update_post_meta( $id, '_ddna_external_url', '' );
	WP_CLI::log( $guide['title'] . ' → ' . wp_get_attachment_url( $attachment ) );
};
$care_guides = array();
foreach ( $manifest['guides'] as $index => $guide ) {
	$import_pdf( $guide, $index + 1 );
	$care_guides[] = array( 'slug' => $guide['slug'], 'label' => $guide['title'], 'icon' => $guide['icon'] );
}
update_option( 'ddna_care_guides', $care_guides, false );
$normativa = array();
foreach ( $manifest['normativa']['sections'] as $section ) {
	$documents = array();
	foreach ( $manifest['normativa']['documents'] as $document ) {
		if ( $section !== $document['section'] ) { continue; }
		if ( ! empty( $document['file'] ) ) { $import_pdf( $document, $document['order'] ); }
		$documents[] = array( 'title' => $document['title'], 'slug' => $document['slug'] );
	}
	$normativa[] = array( 'title' => $section, 'documents' => $documents );
}
update_option( 'ddna_normativa_sections', $normativa, false );
update_option( 'ddna_feedback_conventions', $manifest['conventions'], false );
foreach ( $manifest['campaigns'] as $campaign ) {
	$id = $upsert( 'campana', $campaign['slug'], array( 'post_title' => $campaign['title'], 'post_excerpt' => $campaign['excerpt'], 'menu_order' => $campaign['order'] ) );
	update_post_meta( $id, '_ddna_featured', 1 );
	update_post_meta( $id, '_ddna_home_order', $campaign['order'] );
	update_post_meta( $id, '_ddna_destination_pending', 0 );
	update_post_meta( $id, '_ddna_external_url', esc_url_raw( $campaign['url'] ) );
}
$settings = function_exists( 'ddna_core_get_home_settings' ) ? ddna_core_get_home_settings() : array();
$settings['campaigns_count'] = max( 5, absint( $settings['campaigns_count'] ?? 5 ) );
update_option( 'ddna_home_settings', $settings );
$links = get_option( 'ddna_editorial_links', array() );
$links['graphic_materials'] = esc_url_raw( $manifest['graphic_materials'] );
update_option( 'ddna_editorial_links', $links );
// Adaptar solo el destino de normativa de los menús existentes, sin borrarlos.
foreach ( get_posts( array( 'post_type' => 'nav_menu_item', 'post_status' => 'any', 'posts_per_page' => -1 ) ) as $item ) {
	if ( in_array( $item->post_title, array( 'Normativas', 'Normativa' ), true ) ) { update_post_meta( $item->ID, '_menu_item_url', home_url( '/normativa/' ) ); }
}
update_option( 'ddna_feedback_prensa_octubre_digest', $digest, false );
WP_CLI::success( 'Feedback aplicado: 9 guías y 5 PDFs normativos. Código Civil conserva el elemento sin enlace de la fuente.' );
