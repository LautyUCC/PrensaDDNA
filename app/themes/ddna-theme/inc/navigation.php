<?php
/**
 * Mejoras de marcado para la navegacion principal.
 *
 * @package DDNA_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Anade un control independiente a los elementos que contienen submenu.
 *
 * @param string   $item_output Marcado del elemento.
 * @param WP_Post  $item        Elemento del menu.
 * @param int      $depth       Profundidad actual.
 * @param stdClass $args        Argumentos del menu.
 * @return string
 */
function ddna_theme_add_submenu_toggle( $item_output, $item, $depth, $args ) {
	if ( empty( $args->theme_location ) || 'primary' !== $args->theme_location ) {
		return $item_output;
	}

	if ( ! in_array( 'menu-item-has-children', (array) $item->classes, true ) ) {
		return $item_output;
	}

	$label = sprintf(
		/* translators: %s: menu item label. */
		__( 'Mostrar submenú de %s', 'ddna-theme' ),
		wp_strip_all_tags( $item->title )
	);

	$item_output .= sprintf(
		'<button class="submenu-toggle" type="button" aria-expanded="false"><span class="screen-reader-text">%1$s</span><span class="submenu-toggle__icon" aria-hidden="true"></span></button>',
		esc_html( $label )
	);

	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'ddna_theme_add_submenu_toggle', 10, 4 );
