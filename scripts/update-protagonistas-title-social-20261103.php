<?php
/** Change only Protagonistas' title and two social links.
 * Usage: wp eval-file update-protagonistas-title-social-20261103.php dry-run|apply
 */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }
$mode = $args[0] ?? 'dry-run';
if ( ! in_array( $mode, array( 'dry-run', 'apply' ), true ) ) { WP_CLI::error( 'Usar dry-run o apply.' ); }
if ( untrailingslashit( home_url() ) !== 'https://ddna.com.ar' ) { WP_CLI::error( 'Origen inesperado.' ); }
if ( 'apply' === $mode && getenv( 'DDNA_PROTAGONISTAS_APPROVAL' ) !== 'APROBADO PARA PUBLICAR' ) { WP_CLI::error( 'Falta aprobación.' ); }
$manifest_path = getenv( 'DDNA_PROTAGONISTAS_MANIFEST' );
$manifest = $manifest_path ? json_decode( file_get_contents( $manifest_path ), true ) : null;
if ( ! is_array( $manifest ) || 'programs-editorial-sept-2026-v2' !== ( $manifest['version'] ?? '' ) ) { WP_CLI::error( 'Manifiesto incorrecto.' ); }
$entry = null;
foreach ( $manifest['programs'] as $candidate ) {
	if ( 'programas-participacion-nnya' === $candidate['slug'] ) { $entry = $candidate; break; }
}
$expected_title = 'PROTAGONISTAS DEL PRESENTE: Programa de participación ciudadana de niñas, niños y adolescentes';
if ( ! $entry || $expected_title !== $entry['title'] || 1 !== $entry['order'] ) { WP_CLI::error( 'Entrada inesperada.' ); }
$post = get_page_by_path( 'programas-participacion-nnya', OBJECT, 'programa' );
if ( ! $post || 'publish' !== $post->post_status || 1 !== (int) $post->menu_order ) { WP_CLI::error( 'Página inesperada.' ); }
$old_line = '<p>Dónde encontrarlo: Instagram @detrasdelhumoo_ y canal de YouTube “Detrás del Humo”.</p>';
$new_line = '<p>Dónde encontrarlo: Instagram <a class="program-social-link" href="https://www.instagram.com/detrasdelhumoo_/" target="_blank" rel="noopener noreferrer">@detrasdelhumoo_</a> y <a class="program-social-link" href="https://www.youtube.com/@detrasdelhumoo" target="_blank" rel="noopener noreferrer">canal de YouTube “Detrás del Humo”</a>.</p>';
if ( 1 !== substr_count( $entry['content'], $new_line ) ) { WP_CLI::error( 'Enlaces editoriales inesperados.' ); }
$old_content = str_replace( $new_line, $old_line, $entry['content'] );
$old_title = 'PROTAGONISTAS DEL PRESENTE';
$done = $post->post_title === $expected_title && $post->post_content === $entry['content'];
if ( ! $done && ( $post->post_title !== $old_title || $post->post_content !== $old_content ) ) { WP_CLI::error( 'Título o contenido modificados fuera de este cambio.' ); }
if ( 'apply' === $mode && ! $done ) {
	$checkpoint_path = getenv( 'DDNA_PROTAGONISTAS_CHECKPOINT' );
	if ( ! $checkpoint_path || file_exists( $checkpoint_path ) || ! is_writable( dirname( $checkpoint_path ) ) ) { WP_CLI::error( 'Checkpoint nuevo requerido.' ); }
	$checkpoint = array( 'post' => get_post( $post->ID, ARRAY_A ), 'meta' => get_post_meta( $post->ID ) );
	if ( false === file_put_contents( $checkpoint_path, wp_json_encode( $checkpoint, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ), LOCK_EX ) ) { WP_CLI::error( 'Checkpoint falló.' ); }
	chmod( $checkpoint_path, 0600 );
	global $wpdb;
	$current = $wpdb->get_row( $wpdb->prepare( "SELECT post_title, post_content FROM {$wpdb->posts} WHERE ID = %d", $post->ID ) );
	if ( ! $current || $current->post_title !== $old_title || $current->post_content !== $old_content ) { WP_CLI::error( 'Edición concurrente; revisar checkpoint.' ); }
	$updated = wp_update_post( wp_slash( array( 'ID' => $post->ID, 'post_title' => $expected_title, 'post_content' => $entry['content'] ) ), true );
	if ( is_wp_error( $updated ) ) { WP_CLI::error( $updated->get_error_message() ); }
}
echo wp_json_encode( array( 'mode' => $mode, 'post_id' => $post->ID, 'change_required' => ! $done, 'title_after' => $expected_title, 'content_sha256_after' => hash( 'sha256', $entry['content'] ) ), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) . PHP_EOL;
