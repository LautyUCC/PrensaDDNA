<?php
/** Main controls for the interactive Home landing. @package DDNA_Theme */
$accesses = array(
	array( 'id' => 'necesito-ayuda', 'title' => 'Necesito ayuda', 'description' => 'Asesoramiento y Consultas', 'icon' => 15 ),
	array( 'id' => 'quiero-saber', 'title' => 'Quiero saber', 'description' => 'Información sobre derechos, recursos y herramientas', 'icon' => 17 ),
	array( 'id' => 'quiero-conocer', 'title' => 'Quiero conocer', 'description' => 'Programas y acciones que ofrece la Defensoría', 'icon' => 19 ),
	array( 'id' => 'observatorio', 'title' => 'Observatorio', 'description' => 'Informes, estudios e indicadores sobre la situación de NNyA', 'icon' => 23 ),
	array( 'id' => 'territorio', 'title' => 'Territorio', 'description' => 'Nuestras subsedes', 'icon' => 21 ),
	array( 'id' => 'actualidad', 'title' => 'Actualidad', 'description' => 'Novedades, agenda y prensa', 'icon' => 25 ),
);
$icons_uri = get_template_directory_uri() . '/assets/icons/home/';
?>
<nav class="quick-access" aria-labelledby="quick-access-title">
	<div class="container container--content">
		<h2 class="screen-reader-text" id="quick-access-title"><?php esc_html_e( 'Explorar contenidos de la Defensoría', 'ddna-theme' ); ?></h2>
		<ul class="quick-access__grid" role="list">
			<?php foreach ( $accesses as $access ) : ?>
				<li><button class="quick-access-card" type="button" data-home-panel-trigger="<?php echo esc_attr( $access['id'] ); ?>" aria-expanded="false" aria-controls="panel-<?php echo esc_attr( $access['id'] ); ?>">
					<span class="quick-access-card__icons" aria-hidden="true">
						<img class="quick-access-card__icon quick-access-card__icon--closed" src="<?php echo esc_url( $icons_uri . 'home-' . $access['icon'] . '.png' ); ?>" alt="" width="350" height="321">
						<img class="quick-access-card__icon quick-access-card__icon--open" src="<?php echo esc_url( $icons_uri . 'home-' . ( $access['icon'] + 1 ) . '.png' ); ?>" alt="" width="350" height="321">
					</span>
					<strong class="quick-access-card__title"><?php echo esc_html( $access['title'] ); ?></strong>
					<span class="quick-access-card__description"><?php echo esc_html( $access['description'] ); ?></span>
					<span class="quick-access-card__indicator" aria-hidden="true"></span>
				</button></li>
			<?php endforeach; ?>
		</ul>
	</div>
</nav>
