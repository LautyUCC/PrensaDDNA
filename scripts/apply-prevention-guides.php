<?php
/** Curated four-card migration. Default dry-run; never imports any Drive content. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }
$local = 'local' === wp_get_environment_type() && in_array( wp_parse_url( home_url(), PHP_URL_HOST ), array( 'localhost', '127.0.0.1', '::1' ), true );
if ( ! $local && ( 'APROBADO PARA PUSHEAR Y PUBLICAR EN LA VPS' !== getenv( 'DDNA_PREVENTION_APPROVAL' ) || 'http://179.199.132.207' !== getenv( 'DDNA_PREVENTION_TARGET_ORIGIN' ) || untrailingslashit( home_url() ) !== getenv( 'DDNA_PREVENTION_TARGET_ORIGIN' ) ) ) { WP_CLI::error( 'Publicación requiere aprobación del lote y origen VPS exacto.' ); }
$mode = getenv( 'DDNA_PREVENTION_MODE' ) ?: 'dry-run';
if ( ! in_array( $mode, array( 'dry-run', 'apply', 'rollback' ), true ) ) { WP_CLI::error( 'Modo inválido.' ); }
$apply = 'apply' === $mode;
// The private release journal only tracks newly created records; never overwrite existing VPS documents.
$journal_path = getenv( 'DDNA_PREVENTION_JOURNAL' );
$journal = array( 'origin' => untrailingslashit( home_url() ), 'created' => array() );
$fingerprint = static function( $id ) { return hash( 'sha256', serialize( array( get_post( $id, ARRAY_A ), get_post_meta( $id ) ) ) ); };
$save_journal = static function() use ( &$journal, $journal_path, $fingerprint, $local ) {
 if ( $local ) { return; }
 foreach ( $journal['created'] as &$entry ) { $entry['fingerprint'] = $fingerprint( $entry['id'] ); } unset( $entry );
 if ( false === file_put_contents( $journal_path, wp_json_encode( $journal ), LOCK_EX ) ) { WP_CLI::error( 'No se pudo guardar journal privado.' ); }
 chmod( $journal_path, 0600 );
};
if ( ! $local ) {
 if ( ! $journal_path || ! str_starts_with( $journal_path, '/opt/ddna-agenda/' ) || ! is_dir( dirname( $journal_path ) ) ) { WP_CLI::error( 'Falta ruta privada del journal de release.' ); }
 if ( 'rollback' === $mode ) {
  $journal = json_decode( file_get_contents( $journal_path ), true );
  if ( ! is_array( $journal ) || $journal['origin'] !== untrailingslashit( home_url() ) ) { WP_CLI::error( 'Journal inválido.' ); }
  foreach ( $journal['created'] as $entry ) {
   if ( $fingerprint( $entry['id'] ) !== $entry['fingerprint'] || ( isset( $entry['file'] ) && ( ! is_file( $entry['file'] ) || hash_file( 'sha256', $entry['file'] ) !== $entry['sha256'] ) ) ) { WP_CLI::error( 'Rollback bloqueado por ediciones posteriores.' ); }
  }
  foreach ( array_reverse( $journal['created'] ) as $entry ) { if ( isset( $entry['file'] ) ) { wp_delete_attachment( $entry['id'], true ); } else { wp_delete_post( $entry['id'], true ); } }
  WP_CLI::success( 'Solo registros y PDFs nuevos de este release revertidos.' ); return;
 }
 if ( $apply && file_exists( $journal_path ) ) { WP_CLI::error( 'Journal existente: verificar estado antes de repetir escrituras.' ); }
} elseif ( 'rollback' === $mode ) { WP_CLI::error( 'Rollback de release solo para VPS con journal.' ); }
$root = dirname( __DIR__ ); $manifest = json_decode( file_get_contents( $root . '/content/prevention-guides.json' ), true );
if ( 'prevention-guides-2026-v1' !== ( $manifest['version'] ?? '' ) || 4 !== count( $manifest['guides'] ?? array() ) ) { WP_CLI::error( 'Manifiesto inválido.' ); }
foreach ( $manifest['guides'] as $guide ) {
 if ( 'pdf' === $guide['type'] ) {
  $file = realpath( $root . '/content/' . $guide['file'] ); $media = realpath( $root . '/content/media/prevention' ) . DIRECTORY_SEPARATOR;
  if ( ! $file || ! str_starts_with( $file, $media ) || hash_file( 'sha256', $file ) !== $guide['sha256'] || filesize( $file ) !== $guide['size'] || '%PDF-' !== file_get_contents( $file, false, null, 0, 5 ) || 'application/pdf' !== ( new finfo( FILEINFO_MIME_TYPE ) )->file( $file ) ) { WP_CLI::error( 'PDF no válido: ' . $guide['slug'] ); }
 } elseif ( ! preg_match( '~^https://drive\.google\.com/file/d/[A-Za-z0-9_-]+/view$~D', $guide['source_url'] ) ) { WP_CLI::error( 'URL externa distinta del inventario.' ); }
}
if ( ! $local ) { foreach ( $manifest['guides'] as $guide ) { if ( get_page_by_path( $guide['slug'], OBJECT, 'documento' ) ) { WP_CLI::error( 'Documento VPS existente: no sobrescribir ' . $guide['slug'] ); } } }
if ( ! $apply ) { WP_CLI::success( 'Inventario validado: 2 PDFs y 2 enlaces Drive; cero escrituras.' ); return; }
require_once ABSPATH . 'wp-admin/includes/file.php'; require_once ABSPATH . 'wp-admin/includes/media.php'; require_once ABSPATH . 'wp-admin/includes/image.php';
$result = array();
// Inspect existing attachments once; compare actual hashes, not only filenames/metadata.
$by_hash = array();
foreach ( get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'post_mime_type' => 'application/pdf', 'posts_per_page' => -1, 'fields' => 'ids' ) ) as $id ) {
 $path = get_attached_file( $id ); if ( is_file( $path ) ) { $by_hash[ hash_file( 'sha256', $path ) ] = $id; }
}
foreach ( $manifest['guides'] as $guide ) {
 $post = get_page_by_path( $guide['slug'], OBJECT, 'documento' );
 $id = $post ? $post->ID : wp_insert_post( array( 'post_type' => 'documento', 'post_status' => 'publish', 'post_title' => $guide['label'], 'post_name' => $guide['slug'] ), true );
 if ( is_wp_error( $id ) ) { WP_CLI::error( $id->get_error_message() ); }
 if ( ! $local && ! $post ) { $journal['created'][] = array( 'id' => $id ); $save_journal(); }
 if ( 'pdf' === $guide['type'] ) {
  $attachment = $by_hash[ $guide['sha256'] ] ?? 0;
  if ( ! $attachment ) {
   $file = $root . '/content/' . $guide['file']; $tmp = wp_tempnam( basename( $file ) );
   if ( ! $tmp || ! copy( $file, $tmp ) ) { WP_CLI::error( 'No se pudo preparar PDF.' ); }
   $attachment = media_handle_sideload( array( 'name' => basename( $file ), 'tmp_name' => $tmp ), $id, $guide['label'] );
   if ( is_wp_error( $attachment ) ) { if ( is_file( $tmp ) ) { unlink( $tmp ); } WP_CLI::error( $attachment->get_error_message() ); }
   $by_hash[ $guide['sha256'] ] = $attachment;
   if ( ! $local ) { $journal['created'][] = array( 'id' => $attachment, 'file' => get_attached_file( $attachment ), 'sha256' => $guide['sha256'] ); $save_journal(); }
   update_post_meta( $attachment, '_ddna_source_sha256', $guide['sha256'] );
  }
  update_post_meta( $id, '_ddna_file_id', $attachment ); delete_post_meta( $id, '_ddna_external_url' ); $url = wp_get_attachment_url( $attachment );
 } else { delete_post_meta( $id, '_ddna_file_id' ); update_post_meta( $id, '_ddna_external_url', esc_url_raw( $guide['source_url'] ) ); $url = $guide['source_url']; }
 $save_journal();
 $result[] = array( 'slug' => $guide['slug'], 'original' => $guide['source_url'], 'final' => $url, 'type' => $guide['type'] );
}
WP_CLI::log( wp_json_encode( $result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) ); WP_CLI::success( 'Cuatro destinos configurados, sin duplicar PDFs ni descargar Drive.' );
