<?php
/** Selective content correction for the existing Mesas Interinstitucionales post.
 * Usage: wp eval-file update-mesas-interinstitucionales-20261103.php dry-run|apply
 */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }
$mode = $args[0] ?? 'dry-run';
if ( ! in_array( $mode, array( 'dry-run', 'apply' ), true ) ) { WP_CLI::error( 'Usar dry-run o apply.' ); }
if ( untrailingslashit( home_url() ) !== 'https://ddna.com.ar' ) { WP_CLI::error( 'Origen inesperado.' ); }
if ( 'apply' === $mode && getenv( 'DDNA_MESAS_APPROVAL' ) !== 'APROBADO PARA PUBLICAR' ) { WP_CLI::error( 'Falta aprobación.' ); }
$manifest_path = getenv( 'DDNA_MESAS_MANIFEST' );
$manifest = $manifest_path ? json_decode( file_get_contents( $manifest_path ), true ) : null;
if ( ! is_array( $manifest ) || 'programs-editorial-sept-2026-v2' !== ( $manifest['version'] ?? '' ) ) { WP_CLI::error( 'Manifiesto incorrecto.' ); }
$entry = null;
foreach ( $manifest['programs'] as $candidate ) {
	if ( 'mesas-interinstitucionales' === $candidate['slug'] ) { $entry = $candidate; break; }
}
if ( ! $entry || 'MESAS INTERINSTITUCIONALES' !== $entry['title'] || 4 !== $entry['order'] ) { WP_CLI::error( 'Entrada inesperada.' ); }
$posts = get_posts( array( 'post_type' => 'programa', 'post_status' => 'publish', 'name' => 'mesas-interinstitucionales', 'numberposts' => 2 ) );
if ( 1 !== count( $posts ) ) { WP_CLI::error( 'Página ausente o duplicada.' ); }
$post = $posts[0];
if ( 'MESAS INTERINSTITUCIONALES' !== $post->post_title || 4 !== (int) $post->menu_order ) { WP_CLI::error( 'Identidad u orden modificados.' ); }
$old = str_replace( array( '<h3>Copreti</h3>', '<h3>RediPaz</h3>' ), array( '<h2>COPRETI</h2>', '<h2>RedyPaz</h2>' ), $entry['content'] );
if ( $post->post_content !== $old && $post->post_content !== $entry['content'] ) { WP_CLI::error( 'Contenido modificado fuera de este cambio.' ); }
$change = $post->post_content !== $entry['content'];
if ( 'apply' === $mode && $change ) {
	$checkpoint_path = getenv( 'DDNA_MESAS_CHECKPOINT' );
	if ( ! $checkpoint_path || file_exists( $checkpoint_path ) || ! is_writable( dirname( $checkpoint_path ) ) ) { WP_CLI::error( 'Checkpoint nuevo requerido.' ); }
	$checkpoint = array( 'post' => get_post( $post->ID, ARRAY_A ), 'meta' => get_post_meta( $post->ID ) );
	if ( false === file_put_contents( $checkpoint_path, wp_json_encode( $checkpoint, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ), LOCK_EX ) ) { WP_CLI::error( 'Checkpoint falló.' ); }
	chmod( $checkpoint_path, 0600 );
	global $wpdb;
	$current = $wpdb->get_var( $wpdb->prepare( "SELECT post_content FROM {$wpdb->posts} WHERE ID = %d", $post->ID ) );
	if ( $current !== $old ) { WP_CLI::error( 'Edición concurrente; revisar checkpoint.' ); }
	$updated = wp_update_post( wp_slash( array( 'ID' => $post->ID, 'post_content' => $entry['content'] ) ), true );
	if ( is_wp_error( $updated ) ) { WP_CLI::error( $updated->get_error_message() ); }
}
echo wp_json_encode( array( 'mode' => $mode, 'post_id' => $post->ID, 'change_required' => $change, 'before_sha256' => hash( 'sha256', $post->post_content ), 'after_sha256' => hash( 'sha256', $entry['content'] ) ), JSON_PRETTY_PRINT ) . PHP_EOL;
