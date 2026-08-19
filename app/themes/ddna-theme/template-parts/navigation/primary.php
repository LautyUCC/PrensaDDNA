<?php
/**
 * Navegacion principal.
 *
 * @package DDNA_Theme
 */
?>
<nav class="primary-navigation" aria-label="<?php esc_attr_e( 'Navegación principal', 'ddna-theme' ); ?>">
	<button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-menu" aria-label="<?php esc_attr_e( 'Abrir menú principal', 'ddna-theme' ); ?>" data-open-label="<?php esc_attr_e( 'Abrir menú principal', 'ddna-theme' ); ?>" data-close-label="<?php esc_attr_e( 'Cerrar menú principal', 'ddna-theme' ); ?>">
		<span class="menu-toggle__icon" aria-hidden="true"><span></span><span></span><span></span></span>
		<span class="menu-toggle__label"><?php esc_html_e( 'Menú', 'ddna-theme' ); ?></span>
	</button>
	<?php
	wp_nav_menu(
		array(
			'theme_location' => 'primary',
			'menu_id'        => 'primary-menu',
			'menu_class'     => 'primary-menu',
			'container'      => false,
			'fallback_cb'    => 'ddna_theme_primary_menu_fallback',
			'depth'          => 3,
		)
	);
	?>
</nav>
