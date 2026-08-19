<?php
/**
 * Taxonomías institucionales.
 *
 * @package DDNA_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Genera etiquetas de taxonomía.
 *
 * @param string $singular Etiqueta singular.
 * @param string $plural   Etiqueta plural.
 * @return array<string, string>
 */
function ddna_core_taxonomy_labels( $singular, $plural ) {
	return array(
		'name'              => $plural,
		'singular_name'     => $singular,
		'search_items'      => sprintf( __( 'Buscar %s', 'ddna-core' ), strtolower( $plural ) ),
		'all_items'         => sprintf( __( 'Todos los %s', 'ddna-core' ), strtolower( $plural ) ),
		'parent_item'       => sprintf( __( '%s superior', 'ddna-core' ), $singular ),
		'parent_item_colon' => sprintf( __( '%s superior:', 'ddna-core' ), $singular ),
		'edit_item'         => sprintf( __( 'Editar %s', 'ddna-core' ), $singular ),
		'update_item'       => sprintf( __( 'Actualizar %s', 'ddna-core' ), $singular ),
		'add_new_item'      => sprintf( __( 'Añadir %s', 'ddna-core' ), $singular ),
		'new_item_name'     => sprintf( __( 'Nombre del nuevo %s', 'ddna-core' ), strtolower( $singular ) ),
		'menu_name'         => $plural,
	);
}

/**
 * Registra taxonomías compartidas y específicas.
 */
function ddna_core_register_taxonomies() {
	$shared_types = array( 'post', 'programa', 'campana', 'documento', 'capacitacion', 'recurso' );

	register_taxonomy(
		'ddna_tema',
		$shared_types,
		array(
			'labels'            => ddna_core_taxonomy_labels( __( 'Tema', 'ddna-core' ), __( 'Temas', 'ddna-core' ) ),
			'public'            => true,
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'tema', 'with_front' => false ),
		)
	);

	register_taxonomy(
		'ddna_publico',
		array( 'programa', 'campana', 'documento', 'capacitacion', 'recurso' ),
		array(
			'labels'            => ddna_core_taxonomy_labels( __( 'Público destinatario', 'ddna-core' ), __( 'Públicos destinatarios', 'ddna-core' ) ),
			'public'            => true,
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'publico', 'with_front' => false ),
		)
	);

	register_taxonomy(
		'tipo_documento',
		array( 'documento' ),
		array(
			'labels'            => ddna_core_taxonomy_labels( __( 'Tipo de documento', 'ddna-core' ), __( 'Tipos de documento', 'ddna-core' ) ),
			'public'            => true,
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'tipo-documento', 'with_front' => false ),
		)
	);

	register_taxonomy(
		'tipo_capacitacion',
		array( 'capacitacion' ),
		array(
			'labels'            => ddna_core_taxonomy_labels( __( 'Tipo de capacitación', 'ddna-core' ), __( 'Tipos de capacitación', 'ddna-core' ) ),
			'public'            => true,
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'tipo-capacitacion', 'with_front' => false ),
		)
	);
}
add_action( 'init', 'ddna_core_register_taxonomies' );
