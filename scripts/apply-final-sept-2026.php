<?php
/** Actualización editorial explícita, idempotente y exclusiva del entorno LOCAL. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }
if ( 'local' !== wp_get_environment_type() || ! in_array( wp_parse_url( home_url(), PHP_URL_HOST ), array( 'localhost', '127.0.0.1', '::1' ), true ) ) {
	WP_CLI::error( 'Esta actualización solo se permite en WordPress LOCAL con URL loopback.' );
}
if ( 'final-sept-2026' === get_option( 'ddna_content_version' ) ) {
	WP_CLI::success( 'Actualización ya aplicada: se conservan las ediciones posteriores del administrador.' );
	return;
}
$manifest_path = dirname( __DIR__ ) . '/content/final-sept-2026.json';
$manifest = json_decode( file_get_contents( $manifest_path ), true );
if ( ! is_array( $manifest ) || 'final-sept-2026' !== ( $manifest['version'] ?? '' ) ) { WP_CLI::error( 'Manifiesto no válido.' ); }

// Respaldo previo de todo registro local que puede resultar afectado, incluyendo menús.
$backup = array( 'date' => current_time( 'mysql' ), 'posts' => array(), 'options' => array() );
foreach ( get_posts( array( 'post_type' => array( 'page', 'programa', 'campana', 'capacitacion', 'subsede', 'nav_menu_item' ), 'post_status' => 'any', 'posts_per_page' => -1 ) ) as $post ) {
	$backup['posts'][ $post->ID ] = array( 'post' => $post->to_array(), 'meta' => get_post_meta( $post->ID ) );
}
foreach ( array( 'ddna_institutional', 'ddna_home_settings', 'ddna_content_version', 'ddna_final_statements' ) as $key ) { $backup['options'][ $key ] = get_option( $key, null ); }
add_option( 'ddna_backup_before_final_sept_2026', $backup, '', false );

$page_ids = array();
foreach ( $manifest['pages'] as $item ) {
	$existing = get_page_by_path( $item['slug'] );
	$data = array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => $item['title'], 'post_content' => wp_slash( wp_kses_post( $item['content'] ) ) );
	if ( $existing ) { $data['ID'] = $existing->ID; } else { $data['post_name'] = $item['slug']; }
	$id = wp_insert_post( $data, true );
	if ( is_wp_error( $id ) ) { WP_CLI::error( $id->get_error_message() ); }
	$page_ids[ $item['slug'] ] = $id;
	update_post_meta( $id, '_ddna_content_version', $manifest['version'] );
	WP_CLI::log( ( $existing ? 'Actualizada: ' : 'Creada: ' ) . get_permalink( $id ) );
}

foreach ( get_posts( array( 'post_type' => array( 'programa', 'campana' ), 'posts_per_page' => -1, 'post_status' => 'any' ) ) as $old ) { update_post_meta( $old->ID, '_ddna_featured', 0 ); }
foreach ( $manifest['records'] as $item ) {
	$existing = get_page_by_path( $item['slug'], OBJECT, $item['type'] );
	$data = array( 'post_type' => $item['type'], 'post_status' => 'publish', 'post_title' => $item['title'], 'post_excerpt' => $item['excerpt'] ?? '', 'post_content' => '', 'menu_order' => $item['order'] );
	if ( $existing ) { $data['ID'] = $existing->ID; } else { $data['post_name'] = $item['slug']; }
	$id = wp_insert_post( wp_slash( $data ), true );
	if ( is_wp_error( $id ) ) { WP_CLI::error( $id->get_error_message() ); }
	update_post_meta( $id, '_ddna_featured', 1 );
	update_post_meta( $id, '_ddna_home_order', $item['order'] );
	update_post_meta( $id, '_ddna_content_version', $manifest['version'] );
	update_post_meta( $id, '_ddna_destination_pending', ! empty( $item['pending'] ) );
	update_post_meta( $id, '_ddna_external_url', isset( $item['page'] ) ? get_permalink( $page_ids[ $item['page'] ] ) : '' );
}
$institutional = ddna_core_get_institutional_settings();
$institutional = array_merge( $institutional, array(
	'assistance_label' => 'Línea de Asistencia', 'assistance_phone' => '351 402 0503',
	'adolescence_label' => 'Línea Adolescencia', 'adolescence_phone' => '351 239 8953',
	'phone' => '351 428 8881', 'email' => 'consulta.defensoria@cba.gov.ar', 'case_email' => 'casosasistencia@gmail.com',
	'address' => 'Dámaso Larrañaga 94, Nueva Córdoba, Córdoba Capital.',
) );
update_option( 'ddna_institutional', ddna_core_sanitize_institutional_settings( $institutional ) );
$cosquin = get_page_by_path( 'cosquin', OBJECT, 'subsede' );
if ( $cosquin ) { update_post_meta( $cosquin->ID, '_ddna_address', 'Catamarca 554 esq. Santa Fé' ); }

// Menú principal: actualizar destinos existentes sin eliminar ítems ni submenús.
$locations = get_theme_mod( 'nav_menu_locations', array() );
$primary = ! empty( $locations['primary'] ) ? wp_get_nav_menu_items( $locations['primary'] ) : array();
$destinations = array( 'Quiénes somos' => get_permalink( $page_ids['defensoria'] ), 'Normativas' => get_permalink( $page_ids['normativas'] ), 'Convenios' => get_permalink( $page_ids['convenios'] ), 'Comunicados' => get_permalink( $page_ids['comunicados'] ), 'Programas' => home_url( '/#quiero-conocer' ), 'Capacitaciones' => home_url( '/#quiero-conocer' ), 'Subsedes' => home_url( '/#territorio' ) );
foreach ( $primary ?: array() as $nav_item ) {
	if ( isset( $destinations[ $nav_item->title ] ) ) { update_post_meta( $nav_item->ID, '_menu_item_url', $destinations[ $nav_item->title ] ); }
}
if ( ! empty( $locations['quick_access'] ) ) {
	$quick_items = wp_get_nav_menu_items( $locations['quick_access'] );
	foreach ( $manifest['accesses'] as $index => $access ) {
		$nav_id = isset( $quick_items[ $index ] ) ? $quick_items[ $index ]->ID : 0;
		$nav_id = wp_update_nav_menu_item( $locations['quick_access'], $nav_id, array( 'menu-item-title' => $access['title'], 'menu-item-description' => $access['description'], 'menu-item-url' => home_url( '/#' . $access['id'] ), 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-position' => $index + 1 ) );
		if ( is_wp_error( $nav_id ) ) { WP_CLI::error( $nav_id->get_error_message() ); }
		update_post_meta( $nav_id, '_ddna_home_panel_id', $access['id'] );
	}
}
update_option( 'ddna_final_statements', $manifest['statements'], false );
update_option( 'ddna_content_version', $manifest['version'] );
WP_CLI::success( 'Contenidos finales septiembre 2026 aplicados. No se eliminó ningún contenido histórico.' );
