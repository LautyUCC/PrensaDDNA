<?php
/** Primary navigation. @package DDNA_Theme */

if ( is_front_page() ) {
	$panel_links = array(
		'necesito-ayuda' => 'Necesito ayuda',
		'quiero-saber'   => 'Quiero saber',
		'quiero-conocer' => 'Quiero conocer',
		'observatorio'   => 'Observatorio',
		'territorio'     => 'Territorio',
		'actualidad'     => 'Actualidad',
	);
	?>
	<nav class="primary-navigation primary-navigation--home" aria-label="<?php esc_attr_e( 'Explorar contenidos de la Defensoría', 'ddna-theme' ); ?>">
		<button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-menu" aria-label="<?php esc_attr_e( 'Abrir menú principal', 'ddna-theme' ); ?>" data-open-label="<?php esc_attr_e( 'Abrir menú principal', 'ddna-theme' ); ?>" data-close-label="<?php esc_attr_e( 'Cerrar menú principal', 'ddna-theme' ); ?>">
			<span class="menu-toggle__icon" aria-hidden="true"><span></span><span></span><span></span></span>
			<span class="menu-toggle__label"><?php esc_html_e( 'Menú', 'ddna-theme' ); ?></span>
		</button>
		<ul class="primary-menu" id="primary-menu">
			<?php foreach ( $panel_links as $panel_id => $panel_label ) : ?>
				<li class="menu-item"><button class="primary-menu__panel-link" type="button" data-home-panel-trigger="<?php echo esc_attr( $panel_id ); ?>" aria-expanded="false" aria-controls="panel-<?php echo esc_attr( $panel_id ); ?>"><?php echo esc_html( $panel_label ); ?></button></li>
			<?php endforeach; ?>
		</ul>
	</nav>
	<?php
	return;
}
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
