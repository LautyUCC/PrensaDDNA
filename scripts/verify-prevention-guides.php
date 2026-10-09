<?php
/** Assert curated targets, actual PDF bytes, no placeholders or duplicate resources. Read only. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }
$m = json_decode( file_get_contents( dirname( __DIR__ ) . '/content/prevention-guides.json' ), true ); $urls = array(); $result = array();
foreach ( $m['guides'] as $g ) {
 $post = get_page_by_path( $g['slug'], OBJECT, 'documento' ); if ( ! $post || 'publish' !== $post->post_status ) { WP_CLI::error( 'Documento ausente: ' . $g['slug'] ); }
 if ( 'pdf' === $g['type'] ) {
  $id = absint( get_post_meta( $post->ID, '_ddna_file_id', true ) ); $file = get_attached_file( $id ); $url = wp_get_attachment_url( $id );
  if ( ! $id || ! is_file( $file ) || 'application/pdf' !== get_post_mime_type( $id ) || '%PDF-' !== file_get_contents( $file, false, null, 0, 5 ) || hash_file( 'sha256', $file ) !== $g['sha256'] || wp_parse_url( $url, PHP_URL_HOST ) !== wp_parse_url( home_url(), PHP_URL_HOST ) ) { WP_CLI::error( 'PDF inválido o destino antiguo: ' . $g['slug'] ); }
 } else { $url = get_post_meta( $post->ID, '_ddna_external_url', true ); if ( $url !== $g['source_url'] ) { WP_CLI::error( 'Externo no coincide con fuente.' ); } }
 if ( ! $url || '#' === $url || ! wp_http_validate_url( $url ) && ! in_array( wp_parse_url( $url, PHP_URL_HOST ), array( 'localhost', '127.0.0.1' ), true ) || in_array( $url, $urls, true ) ) { WP_CLI::error( 'URL vacía, inválida o duplicada.' ); }
 $urls[] = $url; $result[] = array( 'guide' => $g['label'], 'original' => $g['source_url'], 'action' => $g['action'], 'final' => $url );
}
WP_CLI::log( wp_json_encode( $result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) ); WP_CLI::success( '4 recursos únicos: 2 PDFs locales válidos y 2 URLs originales.' );
