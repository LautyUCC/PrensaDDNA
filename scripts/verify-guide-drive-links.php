<?php
/** Verify the rendered thirteen guide links and the eleven preserved original PDFs. Read only. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }
$root = dirname( __DIR__ );
$drive = json_decode( file_get_contents( $root . '/content/guide-drive-links.json' ), true );
if ( 'ddna-guide-drive-links-v1' !== ( $drive['version'] ?? '' ) || 11 !== count( $drive['guides'] ?? array() ) ) { WP_CLI::error( 'Manifiesto Drive inválido.' ); }
$urls = array_column( $drive['guides'], 'drive_url', 'slug' );
$guides = array_merge(
	json_decode( file_get_contents( $root . '/content/prevention-guides.json' ), true )['guides'],
	json_decode( file_get_contents( $root . '/content/feedback-prensa-octubre.json' ), true )['guides']
);
ob_start(); get_template_part( 'template-parts/home/panel-quiero-saber' ); $html = ob_get_clean();
$seen = array(); $mapping = array();
foreach ( $guides as $guide ) {
	$post = get_page_by_path( $guide['slug'], OBJECT, 'documento' );
	$url = $urls[ $guide['slug'] ] ?? $guide['source_url'];
	if ( ! $post || 'publish' !== $post->post_status || get_post_meta( $post->ID, '_ddna_external_url', true ) !== $url || in_array( $url, $seen, true ) ) { WP_CLI::error( 'Destino inválido: ' . $guide['slug'] ); }
	if ( ! str_contains( $html, 'href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer"' ) ) { WP_CLI::error( 'El panel no renderiza Drive accesible: ' . $guide['slug'] ); }
	$seen[] = $url;
	$id = absint( get_post_meta( $post->ID, '_ddna_file_id', true ) );
	if ( isset( $guide['file'] ) ) {
		$file = $id ? get_attached_file( $id ) : '';
		if ( ! $file || ! is_file( $file ) || hash_file( 'sha256', $file ) !== $guide['sha256'] || filesize( $file ) !== $guide['size'] ) { WP_CLI::error( 'Original perdido o diferente: ' . $guide['slug'] ); }
	}
	$mapping[] = array( 'guide' => $guide['label'] ?? $guide['title'], 'preserved_media' => $id ? wp_get_attachment_url( $id ) : '', 'final' => $url );
}
if ( 13 !== count( $seen ) ) { WP_CLI::error( 'Deben existir trece destinos únicos.' ); }
WP_CLI::log( wp_json_encode( $mapping, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) );
WP_CLI::success( '13 guías enlazadas a Drive; 11 PDFs originales íntegros; nueva pestaña segura.' );
