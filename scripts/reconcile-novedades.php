<?php
/** Scoped reconciliation. Dry-run default; remote writes require exact approval/origin. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }
$local = 'local' === wp_get_environment_type() && in_array( wp_parse_url( home_url(), PHP_URL_HOST ), array( 'localhost', '127.0.0.1', '::1' ), true );
if ( ! $local && ( getenv( 'DDNA_RECONCILIATION_APPROVAL' ) !== 'LISTO PARA PUSH Y PUBLICAR EN VPS' || getenv( 'DDNA_RECONCILIATION_ORIGIN' ) !== 'http://179.199.132.207' || untrailingslashit( home_url() ) !== getenv( 'DDNA_RECONCILIATION_ORIGIN' ) ) ) { WP_CLI::error( 'Reconciliación remota requiere aprobación y origen exactos.' ); }
$by_source = static function ( $source ) {
 $found = get_posts( array( 'post_type' => 'post', 'post_status' => array( 'publish', 'draft', 'pending', 'private', 'future', 'trash' ), 'posts_per_page' => 2, 'fields' => 'ids', 'meta_key' => '_ddna_news_source_id', 'meta_value' => $source ) );
 if ( count( $found ) !== 1 ) { WP_CLI::error( 'Origen inexistente/duplicado: ' . $source ); }
 return (int) $found[0];
};
$mode = $args[0] ?? 'dry-run';
if ( ! in_array( $mode, array( 'apply', 'dry-run' ), true ) ) { WP_CLI::error( 'Modo inválido.' ); }
$root = dirname( __DIR__ ) . '/content';
$m = json_decode( file_get_contents( $root . '/novedades-historical-manifest.json' ), true );
$policy = json_decode( file_get_contents( $root . '/novedades-reconciliation-policy.json' ), true );
$new = json_decode( file_get_contents( $root . '/novedades-manifest.json' ), true );
if ( ! is_array( $m ) || 'ddna-historical-novedades-v1' !== ( $m['version'] ?? '' ) || count( $m['entries'] ) !== 219 || count( $new['entries'] ) !== 3 ) { WP_CLI::error( 'Manifiestos incompletos.' ); }
$resolve = static function ( $asset ) use ( $root ) {
 $file = realpath( $root . '/' . $asset['path'] );
 if ( ! $file || ! str_starts_with( $file, realpath( $root ) . '/' ) || ! is_file( $file ) || hash_file( 'sha256', $file ) !== $asset['sha256'] || filesize( $file ) !== $asset['size'] ) { WP_CLI::error( 'Asset/checksum inválido: ' . $asset['path'] ); }
 if ( ( new finfo( FILEINFO_MIME_TYPE ) )->file( $file ) !== $asset['mime'] ) { WP_CLI::error( 'MIME no coincide: ' . $asset['path'] ); }
 return $file;
};
foreach ( $m['assets'] as $asset ) { $resolve( $asset ); }
$ids = array(); $seen = array();
foreach ( $m['entries'] as $e ) {
 if ( isset( $seen[ $e['historical_id'] ] ) || ! $e['title'] || ! preg_match( '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/D', $e['post_date'] ) || ! strtotime( $e['post_date'] ) ) { WP_CLI::error( 'Entrada histórica inválida.' ); }
 $seen[ $e['historical_id'] ] = true;
 $found = get_posts( array( 'post_type' => 'post', 'post_status' => 'any', 'posts_per_page' => 2, 'fields' => 'ids', 'meta_key' => '_ddna_news_historical_id', 'meta_value' => $e['historical_id'] ) );
 if ( count( $found ) > 1 ) { WP_CLI::error( 'Origen histórico duplicado.' ); }
 $id = $found[0] ?? ( ! empty( $e['existing_local_source_id'] ) ? $by_source( $e['existing_local_source_id'] ) : 0 );
 if ( $id && ( ! get_post( $id ) || get_post_type( $id ) !== 'post' || ! get_post_meta( $id, '_ddna_news_source_id', true ) ) ) { WP_CLI::error( 'Coincidencia local no verificable.' ); }
 if ( $id && isset( $e['existing_local_source_id'] ) && get_post_meta( $id, '_ddna_news_source_id', true ) !== $e['existing_local_source_id'] ) { WP_CLI::error( 'Coincidencia local cambió de origen: ' . $e['slug'] ); }
 $collision = get_page_by_path( $e['slug'], OBJECT, 'post' );
 if ( $collision && (int) $collision->ID !== (int) $id ) { WP_CLI::error( 'Slug ocupado: ' . $e['slug'] ); }
 preg_match_all( '/\{\{media:([a-f0-9]{64})\}\}/', $e['content'], $keys );
 foreach ( array_merge( $keys[1], $e['gallery'], $e['featured_image'] ? array( $e['featured_image'] ) : array() ) as $key ) { if ( ! isset( $m['assets'][ $key ] ) ) { WP_CLI::error( 'Asset desconocido.' ); } }
 $ids[ $e['historical_id'] ] = $id;
}
foreach ( array( 'retire', 'review' ) as $group ) {
 foreach ( $policy[ $group ] as &$r ) {
  $r['id'] = $by_source( $r['source_id'] );
  if ( get_post_type( $r['id'] ) !== 'post' || get_post( $r['id'] )->post_title !== $r['title'] ) { WP_CLI::error( 'El contenido a retirar cambió de identidad.' ); }
 }
 unset( $r );
}
foreach ( $new['entries'] as $e ) {
 if ( ! preg_match( '~^FALTA PUBLICAR/([123])\)~', $e['origin'] ) || $e['original_publication_date'] !== $policy['new_local_date'] ) { WP_CLI::error( 'Política de nuevas incumplida.' ); }
 $id = $by_source( $e['source_id'] );
 if ( get_post( $id )->post_title !== $e['title'] || get_post( $id )->post_name !== $e['slug'] || get_post_status( $id ) !== 'publish' ) { WP_CLI::error( 'Nueva aprobada cambió de identidad.' ); }
}
WP_CLI::log( 'Preflight: 219 históricos, 3 nuevas; hashes, identidades, fechas y colisiones verificados.' );
if ( 'dry-run' === $mode ) { WP_CLI::success( 'Dry-run sin escrituras.' ); return; }
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
$backup = get_option( 'ddna_news_reconciliation_before', array() );
if ( ! $backup ) {
 foreach ( array_unique( array_filter( array_merge( array_values( $ids ), array_map( static fn( $e ) => $by_source( $e['source_id'] ), $new['entries'] ), array_column( $policy['retire'], 'id' ), array_column( $policy['review'], 'id' ) ) ) ) as $id ) {
  $backup[ $id ] = array( 'post' => get_post( $id, ARRAY_A ), 'meta' => get_post_meta( $id ), 'terms' => wp_get_object_terms( $id, get_object_taxonomies( 'post' ), array( 'fields' => 'all' ) ) );
 }
 update_option( 'ddna_news_reconciliation_before', $backup, false );
}
$created_media_ids = get_option( 'ddna_news_reconciliation_created_media', array() );
$media = array(); $created_media = 0;
foreach ( get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'posts_per_page' => -1, 'fields' => 'ids' ) ) as $id ) {
 $file = wp_get_original_image_path( $id ) ?: get_attached_file( $id );
 if ( $file && is_file( $file ) ) { $media[ hash_file( 'sha256', $file ) ] = $id; }
}
$media_id = static function ( $key ) use ( &$media, &$created_media, &$created_media_ids, $m, $resolve ) {
 if ( isset( $media[ $key ] ) ) { return $media[ $key ]; }
 $a = $m['assets'][ $key ]; $file = $resolve( $a ); $name = 'ddna-historical-' . substr( $key, 0, 24 ) . '.' . pathinfo( $file, PATHINFO_EXTENSION );
 $tmp = wp_tempnam( $name );
 if ( ! $tmp || ! copy( $file, $tmp ) ) { WP_CLI::error( 'No se pudo preparar media.' ); }
 $id = media_handle_sideload( array( 'name' => $name, 'tmp_name' => $tmp ), 0, $a['filename'] );
 if ( is_wp_error( $id ) ) { @unlink( $tmp ); WP_CLI::error( $id->get_error_message() ); }
 $created_media_ids[] = $id;
 update_option( 'ddna_news_reconciliation_created_media', $created_media_ids, false );
 update_post_meta( $id, '_ddna_news_asset_sha256', $key );
 update_post_meta( $id, '_ddna_news_asset_source', $a['source_url'] );
 if ( wp_attachment_is_image( $id ) ) { update_post_meta( $id, '_wp_attachment_image_alt', sanitize_text_field( $a['alt'] ) ); }
 $media[ $key ] = $id; $created_media++;
 return $id;
};
$rows = array(); $counts = array( 'CREATED' => 0, 'UPDATED' => 0, 'SKIPPED' => 0 );
foreach ( $m['entries'] as $e ) {
 $id = $ids[ $e['historical_id'] ]; $fingerprint = hash( 'sha256', wp_json_encode( $e ) );
 if ( $id && get_post_meta( $id, '_ddna_news_reconciled_sha256', true ) === $fingerprint ) { $state = 'SKIPPED'; }
 else {
  $content = preg_replace_callback( '/\{\{media:([a-f0-9]{64})\}\}/', static fn( $v ) => esc_url( wp_get_attachment_url( $media_id( $v[1] ) ) ), $e['content'] );
  $gallery = array_map( $media_id, $e['gallery'] ); $featured = $e['featured_image'] ? $media_id( $e['featured_image'] ) : 0;
  $was_new = ! $id;
  $id = wp_insert_post( wp_slash( array( 'ID' => $id, 'post_type' => 'post', 'post_status' => 'publish', 'post_title' => $e['title'], 'post_name' => $e['slug'], 'post_content' => wp_kses_post( $content ), 'post_date' => $e['post_date'], 'post_date_gmt' => $e['post_date_gmt'], 'comment_status' => 'closed' ) ), true );
  if ( is_wp_error( $id ) ) { WP_CLI::error( $id->get_error_message() ); }
  if ( $featured ) { set_post_thumbnail( $id, $featured ); } else { delete_post_thumbnail( $id ); }
  if ( ! get_post_meta( $id, '_ddna_news_source_id', true ) ) { update_post_meta( $id, '_ddna_news_source_id', $e['source_id'] ); }
  update_post_meta( $id, '_ddna_news_enabled', true );
  update_post_meta( $id, '_ddna_news_historical_id', $e['historical_id'] );
  update_post_meta( $id, '_ddna_news_origin', $e['source_url'] );
  update_post_meta( $id, '_ddna_news_date_policy', 'authoritative_historical_publication_date' );
  update_post_meta( $id, '_ddna_news_carousel', $e['carousel'] );
  update_post_meta( $id, '_ddna_news_gallery', $gallery );
  update_post_meta( $id, '_ddna_news_pending', $e['pending'] );
  ddna_core_news_assign_terms( $id );
  update_post_meta( $id, '_ddna_news_reconciled_sha256', $fingerprint );
  $state = $was_new ? 'CREATED' : 'UPDATED';
 }
 $counts[ $state ]++; $rows[] = array( 'id' => $id, 'historical_id' => $e['historical_id'], 'title' => $e['title'], 'date' => $e['post_date'], 'status' => $state, 'url' => get_permalink( $id ) );
 WP_CLI::log( $state . ' ' . $e['historical_id'] . ' ' . $e['slug'] );
 update_option( 'ddna_news_reconciliation_progress', $rows, false );
}
foreach ( $new['entries'] as $e ) {
 $found = get_posts( array( 'post_type' => 'post', 'post_status' => 'any', 'posts_per_page' => 2, 'fields' => 'ids', 'meta_key' => '_ddna_news_source_id', 'meta_value' => $e['source_id'] ) );
 if ( count( $found ) !== 1 ) { WP_CLI::error( 'Nueva local inexistente/duplicada.' ); }
 $id = $found[0]; $date = $policy['new_local_date'] . ' 12:00:00';
 $fingerprint = hash( 'sha256', wp_json_encode( $e ) );
 $changed = get_post_meta( $id, '_ddna_news_reconciled_local_sha256', true ) !== $fingerprint;
 if ( $changed ) {
  $result = wp_update_post( array( 'ID' => $id, 'post_date' => $date, 'post_date_gmt' => get_gmt_from_date( $date ) ), true );
  if ( is_wp_error( $result ) ) { WP_CLI::error( $result->get_error_message() ); }
  update_post_meta( $id, '_ddna_news_carousel', $e['carousel'] );
  update_post_meta( $id, '_ddna_news_gallery', array_map( static function ( $key ) use ( $media ) { if ( ! isset( $media[ $key ] ) ) { WP_CLI::error( 'Media de nueva local no existe.' ); } return $media[ $key ]; }, $e['gallery'] ) );
  update_post_meta( $id, '_ddna_news_date_policy', 'user_confirmed_publication_date' );
  update_post_meta( $id, '_ddna_news_pending', $e['pending'] );
  update_post_meta( $id, '_ddna_news_reconciled_local_sha256', $fingerprint );
 }
 $rows[] = array( 'id' => $id, 'title' => $e['title'], 'date' => $date, 'status' => $changed ? 'UPDATED' : 'SKIPPED', 'url' => get_permalink( $id ) );
}
foreach ( $policy['retire'] as $r ) { if ( get_post_status( $r['id'] ) !== 'trash' ) { wp_trash_post( $r['id'] ); } }
foreach ( $policy['review'] as $r ) { if ( get_post_status( $r['id'] ) !== 'draft' ) { wp_update_post( array( 'ID' => $r['id'], 'post_status' => 'draft' ) ); } update_post_meta( $r['id'], '_ddna_news_pending', array( 'REVIEW: no coincide inequívocamente con el histórico oficial; fecha sin confirmar.' ) ); }
$receipt = array( 'rows' => $rows, 'counts' => $counts, 'created_media' => $created_media, 'created_media_ids' => $created_media_ids, 'completed_at' => current_time( 'mysql' ), 'manifest_sha256' => hash_file( 'sha256', $root . '/novedades-historical-manifest.json' ) );
update_option( 'ddna_news_reconciliation_receipt', $receipt, false );
WP_CLI::success( wp_json_encode( $counts ) . '; media nuevos: ' . $created_media );
