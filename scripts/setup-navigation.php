<?php
/**
 * Configuracion inicial reproducible de la portada y el menu principal.
 * Ejecutar con: wp eval-file scripts/setup-navigation.php
 */

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	return;
}
if ( get_option( 'ddna_content_version' ) ) { WP_CLI::log( 'Navegación final presente; conservar ediciones del administrador.' ); return; }

/**
 * Obtiene o crea una pagina publicada, sin reemplazar contenido existente.
 *
 * @param string $title   Titulo.
 * @param string $slug    Slug.
 * @param int    $parent  Pagina superior.
 * @return int
 */
function ddna_setup_page( $title, $slug, $parent = 0 ) {
	$path = $parent ? get_page_uri( $parent ) . '/' . $slug : $slug;
	$page = get_page_by_path( $path );

	if ( $page ) {
		if ( $title !== $page->post_title ) {
			wp_update_post(
				array(
					'ID'         => $page->ID,
					'post_title' => $title,
				)
			);
		}
		return (int) $page->ID;
	}

	return (int) wp_insert_post(
		array(
			'post_title'  => $title,
			'post_name'   => $slug,
			'post_type'   => 'page',
			'post_status' => 'publish',
			'post_parent' => $parent,
		)
	);
}

$home_id       = ddna_setup_page( 'Inicio', 'inicio' );
$defensoria_id = ddna_setup_page( 'Defensoría', 'defensoria' );
$about_id      = ddna_setup_page( 'Quiénes somos', 'quienes-somos', $defensoria_id );
$assistance_id = ddna_setup_page( 'Asistencia', 'asistencia' );
$contact_id    = ddna_setup_page( 'Contacto', 'contacto' );

update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $home_id );

$menu_name = 'Navegación principal';
$menu      = wp_get_nav_menu_object( $menu_name );

if ( ! $menu ) {
	$menu_id = wp_create_nav_menu( $menu_name );

	$add_item = static function ( $title, $url, $parent = 0 ) use ( $menu_id ) {
		return wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'     => $title,
				'menu-item-url'       => $url,
				'menu-item-parent-id' => $parent,
				'menu-item-status'    => 'publish',
				'menu-item-type'      => 'custom',
			)
		);
	};

	$add_item( 'Inicio', home_url( '/' ) );
	$defensoria_item = $add_item( 'Defensoría', get_permalink( $defensoria_id ) );
	$add_item( 'Quiénes somos', get_permalink( $about_id ), $defensoria_item );
	$add_item( 'Normativas', get_term_link( 'normativa', 'tipo_documento' ), $defensoria_item );
	$add_item( 'Convenios', get_term_link( 'convenio', 'tipo_documento' ), $defensoria_item );
	$add_item( 'Subsedes', get_post_type_archive_link( 'subsede' ), $defensoria_item );

	$documents_item = $add_item( 'Documentos', get_post_type_archive_link( 'documento' ) );
	$add_item( 'Informes anuales', get_term_link( 'informe-anual', 'tipo_documento' ), $documents_item );
	$add_item( 'Guías', get_term_link( 'guia', 'tipo_documento' ), $documents_item );
	$add_item( 'Comunicados', get_term_link( 'comunicado', 'tipo_documento' ), $documents_item );
	$add_item( 'Pronunciamientos', get_term_link( 'pronunciamiento', 'tipo_documento' ), $documents_item );
	$add_item( 'Folletería digital', get_term_link( 'folleto', 'tipo_documento' ), $documents_item );

	$add_item( 'Asistencia', get_permalink( $assistance_id ) );
	$add_item( 'Programas', get_post_type_archive_link( 'programa' ) );
	$add_item( 'Capacitaciones', get_post_type_archive_link( 'capacitacion' ) );
	$news_category = get_category_by_slug( 'novedades' );
	$add_item( 'Novedades', get_category_link( $news_category->term_id ) );
	$add_item( 'Contacto', get_permalink( $contact_id ) );
} else {
	$menu_id = (int) $menu->term_id;
}

$locations            = get_theme_mod( 'nav_menu_locations', array() );
$locations['primary'] = $menu_id;
set_theme_mod( 'nav_menu_locations', $locations );

WP_CLI::success( 'Portada y navegación principal configuradas.' );
