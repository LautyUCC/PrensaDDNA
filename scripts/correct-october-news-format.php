<?php
/** Update-only cleanup of the eleven orphan </strong> tags in source entries 8/9.
 * Usage: wp eval-file scripts/correct-october-news-format.php dry-run|apply
 * No import, media operations, new posts, taxonomy changes or date changes.
 */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }
$mode = $args[0] ?? 'dry-run';
if ( ! in_array( $mode, array( 'dry-run', 'apply' ), true ) ) { WP_CLI::error( 'Usar dry-run o apply.' ); }
$local = 'local' === wp_get_environment_type() && in_array( wp_parse_url( home_url(), PHP_URL_HOST ), array( 'localhost', '127.0.0.1', '::1' ), true );
if ( ! $local && ( getenv( 'DDNA_NEWS_APPROVAL' ) !== 'APROBADO PARA PUBLICAR' || untrailingslashit( home_url() ) !== getenv( 'DDNA_NEWS_TARGET_ORIGIN' ) ) ) {
	WP_CLI::error( 'Destino no local bloqueado: requiere aprobación y origen exacto.' );
}
$manifest = json_decode( file_get_contents( dirname( __DIR__ ) . '/content/novedades-octubre-2026.json' ), true );
if ( 'ddna-news-october-2026' !== ( $manifest['batch_id'] ?? '' ) || count( $manifest['entries'] ) !== 8 ) { WP_CLI::error( 'Lote incorrecto.' ); }
$plan = array();
$seen = array();
foreach ( $manifest['entries'] as $e ) {
	$number = (int) basename( $e['origin'] );
	if ( $number < 4 || $number > 11 || isset( $seen[ $number ] ) || $e['source_id'] !== 'ddna-news-' . substr( hash( 'sha256', $e['origin'] ), 0, 20 ) ) { WP_CLI::error( 'Identidad fuente inválida.' ); }
	$seen[ $number ] = true;
	$ids = get_posts( array( 'post_type' => 'post', 'post_status' => array_keys( get_post_stati() ), 'posts_per_page' => -1, 'fields' => 'ids', 'meta_key' => '_ddna_news_source_id', 'meta_value' => $e['source_id'] ) );
	if ( count( $ids ) !== 1 ) { WP_CLI::error( 'Post ausente/duplicado: ' . $number ); }
	$p = get_post( $ids[0] );
	if ( $p->post_title !== $e['title'] || $p->post_name !== $e['slug'] || $p->post_status !== 'publish' || substr( $p->post_date, 0, 10 ) !== $e['original_publication_date'] || get_post_meta( $p->ID, '_ddna_news_origin', true ) !== $e['origin'] ) { WP_CLI::error( 'Mapping modificado: ' . $number ); }
	if ( ! in_array( $number, array( 8, 9 ), true ) ) { continue; }
	$clean = preg_replace( '~(<h2>[^<]*</h2>\s*<p>)</strong>~', '$1', $p->post_content, -1, $count );
	if ( $count && $count !== ( 8 === $number ? 7 : 4 ) ) { WP_CLI::error( 'Formato inesperado: ' . $number ); }
	$inline_index = 0;
	$canonical = preg_replace_callback( '~<!-- wp:image\b.*?<!-- /wp:image -->~s', static function ( $match ) use ( &$inline_index, $e ) {
		$key = $e['inline_images'][ $inline_index++ ] ?? null;
		if ( ! $key ) { WP_CLI::error( 'Imagen inesperada.' ); }
		return '{{image:' . $key . '}}';
	}, $clean );
	if ( $canonical !== $e['content'] || $inline_index !== count( $e['inline_images'] ) ) { WP_CLI::error( 'Contenido diferente a la corrección editorial: ' . $number ); }
	$old_entry = $e;
	$old_entry['content'] = preg_replace( '~(</h2>\s*<p>)~', '$1</strong>', $e['content'] );
	$expected_hash = hash( 'sha256', wp_json_encode( $count ? $old_entry : $e ) );
	if ( get_post_meta( $p->ID, '_ddna_news_import_entry_sha256', true ) !== $expected_hash ) { WP_CLI::error( 'La entrada fue editada después de la importación: ' . $number ); }
	if ( $count ) { $plan[] = array( 'number' => $number, 'id' => $p->ID, 'tags_removed' => $count, 'before_sha256' => hash( 'sha256', $p->post_content ), 'after_sha256' => hash( 'sha256', $clean ), 'content' => $clean, 'entry_sha256' => hash( 'sha256', wp_json_encode( $e ) ) ); }
}
if ( 'apply' === $mode ) {
	// Require a durable selective checkpoint before the first write, including meta.
	$path = getenv( 'DDNA_NEWS_FORMAT_CHECKPOINT' );
	if ( ! $path || file_exists( $path ) || ! is_writable( dirname( $path ) ) ) { WP_CLI::error( 'Indicar un checkpoint nuevo y escribible.' ); }
	$checkpoint = array();
	foreach ( $plan as $row ) { $checkpoint[] = array( 'post' => get_post( $row['id'], ARRAY_A ), 'meta' => get_post_meta( $row['id'] ) ); }
	if ( false === file_put_contents( $path, wp_json_encode( $checkpoint, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ), LOCK_EX ) ) { WP_CLI::error( 'No se pudo guardar el checkpoint.' ); }
	chmod( $path, 0600 );
	// A selective checkpoint supplies rollback without creating any revision posts.
	$target_ids = array_column( $plan, 'id' );
	$no_revisions = static function ( $keep, $post ) use ( $target_ids ) { return in_array( $post->ID, $target_ids, true ) ? 0 : $keep; };
	add_filter( 'wp_revisions_to_keep', $no_revisions, 10, 2 );
	global $wpdb;
	try {
		foreach ( $plan as $row ) {
			$current = $wpdb->get_var( $wpdb->prepare( "SELECT post_content FROM {$wpdb->posts} WHERE ID = %d", $row['id'] ) );
			if ( ! is_string( $current ) || hash( 'sha256', $current ) !== $row['before_sha256'] ) { WP_CLI::error( 'Edición concurrente; detener y revisar checkpoint.' ); }
			$id = wp_update_post( wp_slash( array( 'ID' => $row['id'], 'post_content' => $row['content'] ) ), true );
			if ( is_wp_error( $id ) ) { WP_CLI::error( $id->get_error_message() ); }
			update_post_meta( $id, '_ddna_news_import_entry_sha256', $row['entry_sha256'] );
		}
	} finally { remove_filter( 'wp_revisions_to_keep', $no_revisions, 10 ); }
}
foreach ( $plan as &$row ) { unset( $row['content'] ); }
unset( $row );
echo wp_json_encode( array( 'mode' => $mode, 'updates' => $plan, 'created_posts' => 0 ), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) . PHP_EOL;
