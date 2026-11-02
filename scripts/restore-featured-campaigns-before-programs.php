<?php
/** Restore the five featured campaigns displaced by the Programs importer. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }
$mode = getenv( 'DDNA_CAMPAIGNS_MODE' ) ?: 'dry-run';
$origin = untrailingslashit( home_url() );
if ( 'apply' === $mode && ( 'APROBADO PARA PUBLICAR' !== getenv( 'DDNA_CAMPAIGNS_APPROVAL' ) || $origin !== getenv( 'DDNA_CAMPAIGNS_TARGET_ORIGIN' ) ) ) {
	WP_CLI::error( 'Origen o aprobación inválidos.' );
}
if ( ! in_array( $mode, array( 'dry-run', 'apply' ), true ) ) { WP_CLI::error( 'Modo inválido.' ); }
$slugs = array( 'hay-otra-forma-maltrato', 'hay-otra-forma-bullying', 'consumo-problematico', 'cuidar-la-crianza', 'grooming' );
$posts = array();
foreach ( $slugs as $index => $slug ) {
	$post = get_page_by_path( $slug, OBJECT, 'campana' );
	if ( ! $post || 'publish' !== $post->post_status ) { WP_CLI::error( 'Campaña faltante o no publicada: ' . $slug ); }
	$posts[] = array( 'id' => (int) $post->ID, 'slug' => $slug, 'title' => $post->post_title, 'featured_before' => get_post_meta( $post->ID, '_ddna_featured', true ), 'order_before' => (int) $post->menu_order, 'order_target' => $index + 1 );
}
WP_CLI::log( wp_json_encode( $posts, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) );
if ( 'dry-run' === $mode ) { WP_CLI::success( 'Dry-run sin escrituras.' ); return; }
if ( ! get_option( 'ddna_campaigns_restore_backup_v1' ) ) {
	$backup = array( 'origin' => $origin, 'date' => current_time( 'mysql' ), 'campaigns' => array() );
	foreach ( $posts as $item ) { $post = get_post( $item['id'] ); $backup['campaigns'][ $item['id'] ] = array( 'post' => $post->to_array(), 'meta' => get_post_meta( $item['id'] ) ); }
	add_option( 'ddna_campaigns_restore_backup_v1', $backup, '', false );
}
foreach ( $posts as $item ) {
	wp_update_post( array( 'ID' => $item['id'], 'menu_order' => $item['order_target'] ) );
	update_post_meta( $item['id'], '_ddna_featured', 1 );
}
WP_CLI::success( 'Cinco campañas restauradas selectivamente; contenido, enlaces, imágenes y demás campañas preservados.' );
