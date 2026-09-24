<?php
/** Primary navigation. @package DDNA_Theme */

if ( is_front_page() || ddna_theme_is_institutional_page() ) {
	$is_home_page = is_front_page();
	$panel_links = array(
		'necesito-ayuda' => 'Necesito ayuda',
		'quiero-saber'   => 'Quiero saber',
		'quiero-conocer' => 'Quiero conocer',
		'observatorio'   => 'OBSERVATORIO',
		'territorio'     => 'Territorio',
		'actualidad'     => 'Actualidad',
	);
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$items = ! empty( $locations['quick_access'] ) ? wp_get_nav_menu_items( $locations['quick_access'] ) : array();
	$managed_links = array();
	foreach ( $items ?: array() as $item ) {
		$id = get_post_meta( $item->ID, '_ddna_home_panel_id', true );
		if ( isset( $panel_links[ $id ] ) ) { $managed_links[ $id ] = 'observatorio' === $id ? 'OBSERVATORIO' : $item->title; }
	}
	if ( 6 === count( $managed_links ) ) { $panel_links = $managed_links; }
	?>
	<nav class="primary-navigation primary-navigation--home" aria-label="<?php esc_attr_e( 'Explorar contenidos de la Defensoría', 'ddna-theme' ); ?>">
		<button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-menu" aria-label="<?php esc_attr_e( 'Abrir menú principal', 'ddna-theme' ); ?>" data-open-label="<?php esc_attr_e( 'Abrir menú principal', 'ddna-theme' ); ?>" data-close-label="<?php esc_attr_e( 'Cerrar menú principal', 'ddna-theme' ); ?>">
			<span class="menu-toggle__icon" aria-hidden="true"><span></span><span></span><span></span></span>
			<span class="menu-toggle__label"><?php esc_html_e( 'Menú', 'ddna-theme' ); ?></span>
		</button>
		<ul class="primary-menu" id="primary-menu">
			<?php foreach ( $panel_links as $panel_id => $panel_label ) : ?>
				<li class="menu-item">
					<?php if ( 'observatorio' === $panel_id && ddna_theme_observatory_url() ) : ?>
						<a class="primary-menu__panel-link" href="<?php echo esc_url( ddna_theme_observatory_url() ); ?>"><?php echo esc_html( $panel_label ); ?></a>
					<?php elseif ( $is_home_page ) : ?>
						<button class="primary-menu__panel-link" type="button" data-home-panel-trigger="<?php echo esc_attr( $panel_id ); ?>" aria-expanded="false" aria-controls="panel-<?php echo esc_attr( $panel_id ); ?>"><?php echo esc_html( $panel_label ); ?></button>
					<?php else : ?>
						<a class="primary-menu__panel-link" href="<?php echo esc_url( ddna_theme_home_panel_url( $panel_id ) ); ?>"><?php echo esc_html( $panel_label ); ?></a>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</nav>
	<?php
	return;
}
?>
<nav class="primary-navigation<?php echo is_page( 'novedades' ) ? ' primary-navigation--compact' : ''; ?>" aria-label="<?php esc_attr_e( 'Navegación principal', 'ddna-theme' ); ?>">
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
