<?php
/**
 * Configuración y soportes del tema.
 *
 * @package DDNA_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registra soportes, tamaños y ubicaciones de menú.
 */
function ddna_theme_setup() {
	load_theme_textdomain( 'ddna-theme', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_image_size( 'ddna-program-card', 900, 900, true );
	add_image_size( 'ddna-news-card', 768, 480, true );
	add_theme_support(
		'html5',
		array(
			'comment-list',
			'comment-form',
			'search-form',
			'gallery',
			'caption',
			'script',
			'style',
		)
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 120,
			'width'       => 320,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary'      => __( 'Menú principal', 'ddna-theme' ),
			'quick_access' => __( 'Accesos rápidos de la portada', 'ddna-theme' ),
			'footer'       => __( 'Menú del pie', 'ddna-theme' ),
		)
	);
}
add_action( 'after_setup_theme', 'ddna_theme_setup' );
