<?php
/** Read-only complete historical reconciliation audit. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }
if ( wp_get_environment_type() !== 'local' && ( getenv( 'DDNA_RECONCILIATION_ORIGIN' ) !== 'http://179.199.132.207' || untrailingslashit( home_url() ) !== getenv( 'DDNA_RECONCILIATION_ORIGIN' ) ) ) { WP_CLI::error( 'Verificación remota requiere origen exacto.' ); }
$m = json_decode( file_get_contents( dirname( __DIR__ ) . '/content/novedades-historical-manifest.json' ), true );
$policy = json_decode( file_get_contents( dirname( __DIR__ ) . '/content/novedades-reconciliation-policy.json' ), true );
$assets = array(); $errors = array(); $rows = array();
$check = static function ( $ok, $error ) use ( &$errors ) { if ( ! $ok ) { $errors[] = $error; } };
foreach ( array( 'retire', 'review' ) as $group ) {
 foreach ( $policy[ $group ] as &$r ) {
  $found = get_posts( array( 'post_type' => 'post', 'post_status' => array( 'publish', 'draft', 'pending', 'private', 'future', 'trash' ), 'posts_per_page' => 2, 'fields' => 'ids', 'meta_key' => '_ddna_news_source_id', 'meta_value' => $r['source_id'] ) );
  if ( count( $found ) !== 1 ) { WP_CLI::error( 'Origen de exclusión inexistente/duplicado.' ); }
  $r['id'] = (int) $found[0];
 }
 unset( $r );
}
foreach ( get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'posts_per_page' => -1, 'fields' => 'ids' ) ) as $id ) {
 $file = wp_get_original_image_path( $id ) ?: get_attached_file( $id );
 if ( $file && is_file( $file ) ) { $assets[ hash_file( 'sha256', $file ) ] = $id; }
}
foreach ( $m['assets'] as $key => $asset ) { $check( isset( $assets[ $key ] ), 'Asset original/checksum: ' . $asset['path'] ); }
foreach ( $m['entries'] as $e ) {
 $posts = get_posts( array( 'post_type' => 'post', 'post_status' => 'any', 'posts_per_page' => 2, 'meta_key' => '_ddna_news_historical_id', 'meta_value' => $e['historical_id'] ) );
 $check( count( $posts ) === 1, 'Origen único: ' . $e['historical_id'] );
 if ( ! $posts ) { continue; }
 $p = $posts[0]; $id = $p->ID;
 $expected = preg_replace_callback( '/\{\{media:([a-f0-9]{64})\}\}/', static fn( $v ) => esc_url( wp_get_attachment_url( $assets[ $v[1] ] ?? 0 ) ), $e['content'] );
 $check( $p->post_content === wp_kses_post( $expected ), 'Contenido exacto: ' . $id );
 $check( $p->post_title === $e['title'] && $p->post_name === $e['slug'] && $p->post_status === 'publish', 'Identidad: ' . $id );
 $check( $p->post_date === $e['post_date'] && $p->post_date_gmt === $e['post_date_gmt'], 'Fecha histórica: ' . $id );
 $check( has_term( 'novedad', 'post_tag', $id ), 'Tag: ' . $id );
 $check( (int) get_post_thumbnail_id( $id ) === ( $e['featured_image'] ? ( $assets[ $e['featured_image'] ] ?? -1 ) : 0 ), 'Portada: ' . $id );
 $check( (bool) get_post_meta( $id, '_ddna_news_carousel', true ) === $e['carousel'], 'Carrusel: ' . $id );
 $check( get_post_meta( $id, '_ddna_news_gallery', true ) === array_map( static fn( $key ) => $assets[ $key ] ?? -1, $e['gallery'] ), 'Galería y orden: ' . $id );
 $check( ! preg_match( '~<img[^>]+src=["\'][^"\']*ddna\.cba\.gov\.ar~', $p->post_content ) && ! str_contains( $p->post_content, '{{' ), 'Sin hotlink/placeholders: ' . $id );
 preg_match_all( '/\{\{media:([a-f0-9]{64})\}\}/', $e['content'], $used );
 foreach ( array_merge( $used[1], $e['gallery'], $e['featured_image'] ? array( $e['featured_image'] ) : array() ) as $key ) { $check( isset( $assets[ $key ] ), 'Original/checksum inexistente: ' . $key ); }
 $rows[] = array( 'id' => $id, 'historical_id' => $e['historical_id'], 'title' => $p->post_title, 'date' => $p->post_date, 'url' => get_permalink( $id ), 'featured' => get_post_thumbnail_id( $id ), 'carousel' => $e['carousel'], 'gallery_count' => count( $e['gallery'] ), 'pending' => $e['pending'] );
}
$published = get_posts( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => -1, 'tag' => 'novedad', 'orderby' => 'date', 'order' => 'DESC' ) );
$check( count( $published ) === 222, 'Total final debe ser 219 + 3 = 222.' );
foreach ( $policy['retire'] as $r ) { $check( get_post_status( $r['id'] ) === 'trash', 'Excluida publicada: ' . $r['id'] ); }
foreach ( $policy['review'] as $r ) { $check( get_post_status( $r['id'] ) === 'draft', 'REVIEW debe seguir en borrador: ' . $r['id'] ); }
$first = array(); $previous = '9999-99-99';
foreach ( $published as $p ) { $check( $p->post_date <= $previous, 'Orden descendente incumplido.' ); $previous = $p->post_date; if ( count( $first ) < 15 ) { $first[] = array( 'id' => $p->ID, 'title' => $p->post_title, 'date' => $p->post_date, 'url' => get_permalink( $p->ID ) ); } }
if ( $errors ) { WP_CLI::error( implode( "\n", $errors ) ); }
if ( in_array( 'json', $args ?? array(), true ) ) { echo wp_json_encode( array( 'count' => count( $published ), 'historical' => count( $rows ), 'first15' => $first, 'rows' => $rows, 'excluded' => array_merge( $policy['retire'], $policy['review'] ), 'media_originals' => count( $m['assets'] ), 'receipt' => get_option( 'ddna_news_reconciliation_receipt' ) ), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ); return; }
WP_CLI::success( '219 históricos íntegros; 222 novedades publicadas; fechas exactas, tag, medios/checksums, semántica y orden de galería, exclusiones y cronología verificados.' );
