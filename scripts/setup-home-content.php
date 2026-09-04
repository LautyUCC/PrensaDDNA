<?php
/** Seed idempotente del contenido demostrativo de la Home. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }

function ddna_seed_media( $relative_path, $title, $alt ) {
	$existing = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'posts_per_page' => 1, 'meta_key' => '_ddna_seed_source', 'meta_value' => $relative_path, 'fields' => 'ids' ) );
	if ( $existing ) { return (int) $existing[0]; }
	$source = get_template_directory() . '/' . $relative_path;
	$upload = wp_upload_bits( basename( $source ), null, file_get_contents( $source ) );
	if ( ! empty( $upload['error'] ) ) { WP_CLI::error( $upload['error'] ); }
	$attachment_id = wp_insert_attachment( array( 'post_mime_type' => wp_check_filetype( $upload['file'] )['type'], 'post_title' => $title, 'post_status' => 'inherit' ), $upload['file'] );
	require_once ABSPATH . 'wp-admin/includes/image.php';
	wp_update_attachment_metadata( $attachment_id, wp_generate_attachment_metadata( $attachment_id, $upload['file'] ) );
	update_post_meta( $attachment_id, '_wp_attachment_image_alt', $alt );
	update_post_meta( $attachment_id, '_ddna_seed_source', $relative_path );
	return (int) $attachment_id;
}

function ddna_seed_post( $type, $title, $slug, $excerpt, $thumbnail_id = 0, $category_id = 0 ) {
	$existing = get_page_by_path( $slug, OBJECT, $type );
	if ( $existing ) { return (int) $existing->ID; }
	$data = array( 'post_type' => $type, 'post_status' => 'publish', 'post_title' => $title, 'post_name' => $slug, 'post_excerpt' => $excerpt, 'post_content' => $excerpt );
	if ( $category_id ) { $data['post_category'] = array( $category_id ); }
	$post_id = wp_insert_post( $data );
	if ( $thumbnail_id ) { set_post_thumbnail( $post_id, $thumbnail_id ); }
	if ( in_array( $type, array( 'programa', 'campana' ), true ) ) { update_post_meta( $post_id, '_ddna_featured', 1 ); update_post_meta( $post_id, '_ddna_status', 'active' ); }
	return (int) $post_id;
}

$base = 'assets/images/content-samples/';
$logo_id = ddna_seed_media( 'assets/images/logos/ddna-horizontal.png', 'DDNA Córdoba', 'Defensoría de los Derechos de Niñas, Niños y Adolescentes' );
set_theme_mod( 'custom_logo', $logo_id );

$program_media = array(
	'entre-pantallas' => ddna_seed_media( $base . 'programa-entre-pantallas.webp', 'Entre Pantallas', 'Estudiantes participando de un taller en un aula' ),
	'detras-del-humo' => ddna_seed_media( $base . 'programa-detras-del-humo.webp', 'Detrás del Humo', 'Adolescentes participando de una producción audiovisual' ),
	'va-con-vos'      => ddna_seed_media( $base . 'programa-va-con-vos.webp', 'Va con Vos', 'Taller participativo de escucha y acompañamiento' ),
);

$program_ids = array(
	ddna_seed_post( 'programa', 'Entre Pantallas', 'programa-entre-pantallas', 'Programa de Protección Digital', $program_media['entre-pantallas'] ),
	ddna_seed_post( 'programa', 'Detrás del Humo', 'programa-detras-del-humo', 'Streaming de adolescentes para adolescentes', $program_media['detras-del-humo'] ),
	ddna_seed_post( 'programa', 'Va con Vos', 'programa-va-con-vos', 'Espacio de escucha y talleres interactivos', $program_media['va-con-vos'] ),
);
foreach ( $program_ids as $index => $post_id ) { wp_update_post( array( 'ID' => $post_id, 'menu_order' => $index + 1 ) ); }

$campaign_ids = array(
	ddna_seed_post( 'campana', 'Hay Otra Forma', 'hay-otra-forma', 'Contra el maltrato hacia NNyA, el bullying y el acoso entre pares.' ),
	ddna_seed_post( 'campana', 'Guías para una Crianza Cuidada', 'guias-crianza-cuidada', 'Recursos sobre juegos, crianza y entornos seguros.' ),
	ddna_seed_post( 'campana', 'Guías para la Prevención', 'guias-para-la-prevencion', 'Navegación segura, juegos en línea, abuso sexual y bullying.' ),
	ddna_seed_post( 'campana', 'La vida es un viaje único', 'vida-viaje-unico', 'Acompañalos a vivir sin adicciones.' ),
);
foreach ( $campaign_ids as $index => $post_id ) { wp_update_post( array( 'ID' => $post_id, 'menu_order' => $index + 1 ) ); }

$category = get_category_by_slug( 'novedades' );
$category_id = $category ? (int) $category->term_id : (int) wp_create_category( 'Novedades' );
$news = array(
	array( 'muna', 'MUNA: Municipio Unido por la Niñez y la Adolescencia', 'La DDNA acompaña a municipios cordobeses comprometidos con la niñez y la adolescencia.', 'novedad-muna.webp' ),
	array( 'pronunciamiento-conjunto', 'Consideraciones sobre derechos de niñas, niños y adolescentes', 'Pronunciamiento conjunto para fortalecer la protección integral de derechos.', 'novedad-pronunciamiento.webp' ),
	array( 'unicef-ddna-municipios', 'UNICEF y la DDNA buscan que más municipios se comprometan', 'Una iniciativa para poner a la niñez y la adolescencia en el centro de las políticas locales.', 'novedad-unicef.webp' ),
	array( 'seminario-violencias', 'Seminario: abordajes de las violencias hacia NNyA', 'Reflexiones y aportes frente a la complejidad de las violencias.', 'novedad-seminario.webp' ),
);
foreach ( $news as $item ) {
	$media_id = ddna_seed_media( $base . $item[3], $item[1], $item[1] );
	ddna_seed_post( 'post', $item[1], $item[0], $item[2], $media_id, $category_id );
}

$map_page = get_page_by_path( 'mapeo-instituciones' );
if ( ! $map_page ) { $map_id = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => 'Mapeo de Instituciones', 'post_name' => 'mapeo-instituciones' ) ); } else { $map_id = $map_page->ID; }
$menu_name = 'Accesos rápidos';
$menu = wp_get_nav_menu_object( $menu_name );
$menu_id = $menu ? (int) $menu->term_id : wp_create_nav_menu( $menu_name );
$current_items = wp_get_nav_menu_items( $menu_id );
if ( 6 !== count( $current_items ) ) {
	foreach ( $current_items as $current_item ) { wp_delete_post( $current_item->ID, true ); }
	$links = array(
		array( 'Asesoramiento y Consultas', home_url( '/asistencia/' ), 'icon-consultas' ),
		array( 'Talleres Interactivos', get_post_type_archive_link( 'capacitacion' ), 'icon-talleres' ),
		array( 'Datos sobre la Niñez y la Adolescencia', get_post_type_archive_link( 'documento' ), 'icon-datos' ),
		array( 'Recursos Didácticos', get_post_type_archive_link( 'recurso' ), 'icon-recursos' ),
		array( 'Subsedes', get_post_type_archive_link( 'subsede' ), 'icon-subsede' ),
		array( 'Mapeo de Instituciones que trabajan con NNyA', get_permalink( $map_id ), 'icon-mapeo' ),
	);
	foreach ( $links as $link ) { wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => $link[0], 'menu-item-url' => $link[1], 'menu-item-classes' => $link[2], 'menu-item-status' => 'publish', 'menu-item-type' => 'custom' ) ); }
}
$locations = get_theme_mod( 'nav_menu_locations', array() );
$locations['quick_access'] = (int) $menu_id;
set_theme_mod( 'nav_menu_locations', $locations );
WP_CLI::success( 'Contenido demostrativo de la Home configurado.' );
