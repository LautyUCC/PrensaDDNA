<?php
/** Importador selectivo e idempotente de Programas Editoriales Septiembre 2026. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }
$mode = getenv( 'DDNA_PROGRAMS_MODE' ) ?: 'dry-run';
$origin = untrailingslashit( home_url() );
$local = 'local' === wp_get_environment_type() && in_array( wp_parse_url( $origin, PHP_URL_HOST ), array( 'localhost', '127.0.0.1', '::1' ), true );
if ( ! $local && ( 'APROBADO PARA PUBLICAR' !== getenv( 'DDNA_PROGRAMS_APPROVAL' ) || $origin !== getenv( 'DDNA_PROGRAMS_TARGET_ORIGIN' ) ) ) { WP_CLI::error( 'Origen o aprobación inválidos.' ); }
if ( ! in_array( $mode, array( 'dry-run', 'apply' ), true ) ) { WP_CLI::error( 'Modo inválido.' ); }
$manifest = json_decode( file_get_contents( dirname( __DIR__ ) . '/content/programs-editorial-sept-2026.json' ), true );
if ( ! is_array( $manifest ) || 'programs-editorial-sept-2026-v2' !== ( $manifest['version'] ?? '' ) || 7 !== count( $manifest['programs'] ?? array() ) ) { WP_CLI::error( 'Manifiesto inválido.' ); }
$ids = array(); $changes = array();
foreach ( $manifest['programs'] as $item ) {
	$post = get_page_by_path( $item['slug'], OBJECT, 'programa' );
	$data = array( 'post_type' => 'programa', 'post_status' => 'publish', 'post_title' => $item['title'], 'post_excerpt' => $item['excerpt'], 'post_content' => $item['content'], 'menu_order' => $item['order'] );
	if ( $post ) { $data['ID'] = $post->ID; } else { $data['post_name'] = $item['slug']; }
	$ids[ $item['slug'] ] = $post ? $post->ID : 0;
	$changes[] = array( 'slug' => $item['slug'], 'id' => $post ? $post->ID : 0, 'action' => $post ? 'updated' : 'created', 'title' => $item['title'] );
}
WP_CLI::log( wp_json_encode( $changes, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) );
if ( 'dry-run' === $mode ) { WP_CLI::success( 'Dry-run sin escrituras.' ); return; }
if ( ! get_option( 'ddna_programs_editorial_backup_v2' ) ) {
	$backup = array( 'origin' => $origin, 'date' => current_time( 'mysql' ), 'programs' => array() );
	foreach ( $manifest['programs'] as $item ) { $post = get_page_by_path( $item['slug'], OBJECT, 'programa' ); if ( $post ) { $backup['programs'][ $post->ID ] = array( 'post' => $post->to_array(), 'meta' => get_post_meta( $post->ID ) ); } }
	$old = get_page_by_path( 'programas-participacion-nnya', OBJECT, 'page' ); if ( $old ) { $backup['old_page'] = array( 'post' => $old->to_array(), 'meta' => get_post_meta( $old->ID ) ); }
	add_option( 'ddna_programs_editorial_backup_v2', $backup, '', false );
}
foreach ( $manifest['programs'] as $item ) {
	$post = get_page_by_path( $item['slug'], OBJECT, 'programa' );
	$data = array( 'post_type' => 'programa', 'post_status' => 'publish', 'post_title' => $item['title'], 'post_excerpt' => $item['excerpt'], 'post_content' => $item['content'], 'menu_order' => $item['order'] );
	if ( $post ) { $data['ID'] = $post->ID; } else { $data['post_name'] = $item['slug']; }
	$id = wp_insert_post( wp_slash( $data ), true ); if ( is_wp_error( $id ) ) { WP_CLI::error( $id->get_error_message() ); }
	update_post_meta( $id, '_ddna_featured', 1 ); update_post_meta( $id, '_ddna_home_order', $item['order'] ); update_post_meta( $id, '_ddna_content_version', $manifest['version'] ); update_post_meta( $id, '_ddna_destination_pending', 0 );
}
$old_page = get_page_by_path( 'programas-participacion-nnya', OBJECT, 'page' );
if ( $old_page ) { wp_update_post( array( 'ID' => $old_page->ID, 'post_content' => '', 'post_title' => 'Programas de Participación para NNyA' ) ); }
foreach ( get_posts( array( 'post_type' => array( 'programa', 'campana' ), 'posts_per_page' => -1, 'post_status' => 'publish' ) ) as $post ) { if ( ! in_array( $post->post_name, array_column( $manifest['programs'], 'slug' ), true ) ) { update_post_meta( $post->ID, '_ddna_featured', 0 ); } }
WP_CLI::success( 'Programas actualizados: siete tarjetas, contenido vigente y backup editorial creado.' );
