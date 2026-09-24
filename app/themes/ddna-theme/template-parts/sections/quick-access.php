<?php
/** Main controls for the interactive Home landing. @package DDNA_Theme */
$accesses = array(
	array( 'id' => 'necesito-ayuda', 'title' => 'Necesito ayuda', 'description' => 'Asistencia, orientación y consultas', 'icon' => 15 ),
	array( 'id' => 'quiero-saber', 'title' => 'Quiero saber', 'description' => 'Información sobre derechos, recursos y herramientas', 'icon' => 17 ),
	array( 'id' => 'quiero-conocer', 'title' => 'Quiero conocer', 'description' => 'Programas, talleres y acciones de la Defensoría', 'icon' => 19 ),
	array( 'id' => 'observatorio', 'title' => 'Observatorio de Niñez, Adolescencia, Familia y Comunidad', 'description' => 'Datos, informes e investigaciones sobre la situación de NNyA', 'icon' => 23 ),
	array( 'id' => 'territorio', 'title' => 'Territorio', 'description' => 'Nuestras subsedes y municipios MUNA', 'icon' => 21 ),
	array( 'id' => 'actualidad', 'title' => 'Actualidad', 'description' => 'Novedades, agenda y prensa', 'icon' => 25 ),
);
$locations = get_theme_mod( 'nav_menu_locations', array() );
$menu_items = ! empty( $locations['quick_access'] ) ? wp_get_nav_menu_items( $locations['quick_access'] ) : array();
foreach ( $accesses as &$access ) {
	foreach ( $menu_items ?: array() as $item ) {
		if ( $access['id'] === get_post_meta( $item->ID, '_ddna_home_panel_id', true ) ) {
			$access['title'] = $item->title;
			$access['description'] = $item->description;
			break;
		}
	}
}
unset( $access );

foreach ( $accesses as &$access ) {
	if ( 'observatorio' === $access['id'] ) {
		$access['title'] = 'OBSERVATORIO';
		break;
	}
}
unset( $access );
$icons_uri = get_template_directory_uri() . '/assets/icons/home/';
?>
<nav class="quick-access" aria-labelledby="quick-access-title">
	<div class="container container--content">
		<h2 class="screen-reader-text" id="quick-access-title"><?php esc_html_e( 'Explorar contenidos de la Defensoría', 'ddna-theme' ); ?></h2>
		<ul class="quick-access__grid" role="list">
			<?php foreach ( $accesses as $access ) : ?>
				<?php $is_observatory = 'observatorio' === $access['id']; $observatory_url = $is_observatory ? ddna_theme_observatory_url() : ''; ?>
				<li><?php if ( $is_observatory && $observatory_url ) : ?><a class="quick-access-card" href="<?php echo esc_url( $observatory_url ); ?>" aria-label="<?php esc_attr_e( 'Abrir el Dashboard del Observatorio', 'ddna-theme' ); ?>"><?php else : ?><button class="quick-access-card" type="button" data-home-panel-trigger="<?php echo esc_attr( $access['id'] ); ?>" aria-expanded="false" aria-controls="panel-<?php echo esc_attr( $access['id'] ); ?>"><?php endif; ?>
					<span class="quick-access-card__icons" aria-hidden="true">
						<img class="quick-access-card__icon quick-access-card__icon--closed" src="<?php echo esc_url( $icons_uri . 'home-' . $access['icon'] . '.png' ); ?>" alt="" width="350" height="321">
						<img class="quick-access-card__icon quick-access-card__icon--open" src="<?php echo esc_url( $icons_uri . 'home-' . ( $access['icon'] + 1 ) . '.png' ); ?>" alt="" width="350" height="321">
					</span>
					<strong class="quick-access-card__title"><?php echo esc_html( $access['title'] ); ?></strong>
					<span class="quick-access-card__description"><?php echo esc_html( $access['description'] ); ?></span>
					<span class="quick-access-card__indicator" aria-hidden="true"></span>
				<?php echo $is_observatory && $observatory_url ? '</a>' : '</button>'; ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</nav>
