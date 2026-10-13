<?php
/** Set the eleven verified guide destinations; preserve every PDF and attachment. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }
$local = 'local' === wp_get_environment_type() && in_array( wp_parse_url( home_url(), PHP_URL_HOST ), array( 'localhost', '127.0.0.1', '::1' ), true );
$mode = getenv( 'DDNA_GUIDE_DRIVE_MODE' ) ?: 'dry-run';
if ( ! in_array( $mode, array( 'dry-run', 'apply' ), true ) ) { WP_CLI::error( 'Modo inválido.' ); }
if ( ! $local && ( 'APROBADO PARA PUBLICAR' !== getenv( 'DDNA_GUIDE_DRIVE_APPROVAL' ) || ! getenv( 'DDNA_GUIDE_DRIVE_TARGET_ORIGIN' ) || untrailingslashit( home_url() ) !== getenv( 'DDNA_GUIDE_DRIVE_TARGET_ORIGIN' ) ) ) {
	WP_CLI::error( 'Fuera de localhost requiere aprobación explícita y origen exacto.' );
}
$root = dirname( __DIR__ );
$manifest = json_decode( file_get_contents( $root . '/content/guide-drive-links.json' ), true );
if ( 'ddna-guide-drive-links-v1' !== ( $manifest['version'] ?? '' ) || 11 !== count( $manifest['guides'] ?? array() ) ) { WP_CLI::error( 'Manifiesto inválido.' ); }
$sources = array_merge(
	json_decode( file_get_contents( $root . '/content/prevention-guides.json' ), true )['guides'],
	json_decode( file_get_contents( $root . '/content/feedback-prensa-octubre.json' ), true )['guides']
);
$sources = array_column( $sources, null, 'slug' );
$changes = array(); $urls = array(); $slugs = array();
foreach ( $manifest['guides'] as $guide ) {
	$source = $sources[ $guide['slug'] ] ?? array();
	if ( ( $source['sha256'] ?? '' ) !== $guide['sha256'] || ( $source['file'] ?? '' ) !== $guide['file'] || ( $source['size'] ?? 0 ) !== $guide['size'] ) { WP_CLI::error( 'Fuente distinta: ' . $guide['slug'] ); }
	if ( ! preg_match( '~^https://drive\.google\.com/file/d/[A-Za-z0-9_-]+/view$~D', $guide['drive_url'] ) || in_array( $guide['drive_url'], $urls, true ) || in_array( $guide['slug'], $slugs, true ) ) { WP_CLI::error( 'URL/slug inválido o repetido.' ); }
	$urls[] = $guide['drive_url']; $slugs[] = $guide['slug'];
	$post = get_page_by_path( $guide['slug'], OBJECT, 'documento' );
	$id = $post ? absint( get_post_meta( $post->ID, '_ddna_file_id', true ) ) : 0;
	$file = $id ? get_attached_file( $id ) : '';
	if ( ! $post || 'publish' !== $post->post_status || ! $file || ! is_file( $file ) || 'application/pdf' !== get_post_mime_type( $id ) || hash_file( 'sha256', $file ) !== $guide['sha256'] || filesize( $file ) !== $guide['size'] ) { WP_CLI::error( 'PDF original ausente o diferente: ' . $guide['slug'] ); }
	$current = get_post_meta( $post->ID, '_ddna_external_url', true );
	if ( $current && $current !== $guide['drive_url'] ) { WP_CLI::error( 'Destino editado inesperadamente: ' . $guide['slug'] ); }
	$changes[] = array( 'id' => $post->ID, 'slug' => $guide['slug'], 'file_id' => $id, 'previous_external' => $current, 'current' => $current ?: wp_get_attachment_url( $id ), 'final' => $guide['drive_url'] );
}
WP_CLI::log( wp_json_encode( $changes, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) );
if ( 'apply' !== $mode ) { WP_CLI::success( '11 destinos y PDFs comprobados; cero escrituras.' ); return; }
if ( ! get_option( 'ddna_guide_drive_backup_v1' ) ) {
	add_option( 'ddna_guide_drive_backup_v1', array( 'origin' => home_url(), 'changes' => $changes ), '', false );
}
foreach ( $changes as $change ) {
	update_post_meta( $change['id'], '_ddna_external_url', $change['final'] );
	if ( get_post_meta( $change['id'], '_ddna_external_url', true ) !== $change['final'] || (int) get_post_meta( $change['id'], '_ddna_file_id', true ) !== $change['file_id'] ) { WP_CLI::error( 'No se pudo verificar el destino: ' . $change['slug'] ); }
}
WP_CLI::success( '11 enlaces Drive aplicados; documentos y PDFs originales conservados.' );
