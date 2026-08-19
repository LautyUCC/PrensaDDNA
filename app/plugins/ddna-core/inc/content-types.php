<?php
/**
 * Tipos de contenido institucionales.
 *
 * @package DDNA_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Construye las etiquetas habituales de un tipo de contenido.
 *
 * @param string $singular Nombre singular.
 * @param string $plural   Nombre plural.
 * @return array<string, string>
 */
function ddna_core_post_type_labels( $singular, $plural ) {
	return array(
		'name'                  => $plural,
		'singular_name'         => $singular,
		'menu_name'             => $plural,
		'name_admin_bar'        => $singular,
		'add_new'               => __( 'Añadir nuevo', 'ddna-core' ),
		'add_new_item'          => sprintf( __( 'Añadir %s', 'ddna-core' ), $singular ),
		'new_item'              => sprintf( __( 'Nuevo %s', 'ddna-core' ), $singular ),
		'edit_item'             => sprintf( __( 'Editar %s', 'ddna-core' ), $singular ),
		'view_item'             => sprintf( __( 'Ver %s', 'ddna-core' ), $singular ),
		'all_items'             => sprintf( __( 'Todos los %s', 'ddna-core' ), strtolower( $plural ) ),
		'search_items'          => sprintf( __( 'Buscar %s', 'ddna-core' ), strtolower( $plural ) ),
		'not_found'             => __( 'No se encontraron contenidos.', 'ddna-core' ),
		'not_found_in_trash'    => __( 'No se encontraron contenidos en la papelera.', 'ddna-core' ),
		'featured_image'        => __( 'Imagen destacada', 'ddna-core' ),
		'set_featured_image'    => __( 'Establecer imagen destacada', 'ddna-core' ),
		'remove_featured_image' => __( 'Quitar imagen destacada', 'ddna-core' ),
		'use_featured_image'    => __( 'Usar como imagen destacada', 'ddna-core' ),
		'archives'              => sprintf( __( 'Archivo de %s', 'ddna-core' ), strtolower( $plural ) ),
	);
}

/**
 * Registra los tipos de contenido administrables.
 */
function ddna_core_register_content_types() {
	$types = array(
		'programa' => array(
			'singular' => __( 'Programa', 'ddna-core' ),
			'plural'   => __( 'Programas', 'ddna-core' ),
			'slug'     => 'programas',
			'icon'     => 'dashicons-portfolio',
		),
		'campana' => array(
			'singular' => __( 'Campaña', 'ddna-core' ),
			'plural'   => __( 'Campañas', 'ddna-core' ),
			'slug'     => 'campanas',
			'icon'     => 'dashicons-megaphone',
		),
		'documento' => array(
			'singular' => __( 'Documento', 'ddna-core' ),
			'plural'   => __( 'Documentos', 'ddna-core' ),
			'slug'     => 'biblioteca',
			'icon'     => 'dashicons-media-document',
		),
		'capacitacion' => array(
			'singular' => __( 'Capacitación', 'ddna-core' ),
			'plural'   => __( 'Capacitaciones', 'ddna-core' ),
			'slug'     => 'capacitaciones',
			'icon'     => 'dashicons-welcome-learn-more',
		),
		'subsede' => array(
			'singular' => __( 'Subsede', 'ddna-core' ),
			'plural'   => __( 'Subsedes', 'ddna-core' ),
			'slug'     => 'subsedes',
			'icon'     => 'dashicons-location-alt',
		),
		'recurso' => array(
			'singular' => __( 'Recurso', 'ddna-core' ),
			'plural'   => __( 'Recursos', 'ddna-core' ),
			'slug'     => 'recursos',
			'icon'     => 'dashicons-admin-links',
		),
	);

	foreach ( $types as $post_type => $definition ) {
		register_post_type(
			$post_type,
			array(
				'labels'             => ddna_core_post_type_labels( $definition['singular'], $definition['plural'] ),
				'public'             => true,
				'show_in_rest'       => true,
				'has_archive'        => true,
				'menu_icon'          => $definition['icon'],
				'menu_position'      => 20,
				'rewrite'            => array( 'slug' => $definition['slug'], 'with_front' => false ),
				'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'author', 'custom-fields', 'page-attributes' ),
				'publicly_queryable' => true,
				'query_var'          => true,
				'capability_type'    => 'post',
				'map_meta_cap'       => true,
			)
		);
	}
}
add_action( 'init', 'ddna_core_register_content_types' );
