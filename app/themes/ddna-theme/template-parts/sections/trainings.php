<?php
/** Diplomatura aprobada y estructura de Seminarios, sin oferta inventada. */
$training = get_page_by_path( 'diplomatura-ia-derechos-digitales-nnya', OBJECT, 'capacitacion' );
$label = $training ? get_the_title( $training ) : 'Diplomatura Inteligencia Artificial y Derechos Digitales de NNyA';
$url = $training ? get_post_meta( $training->ID, '_ddna_registration_url', true ) : '';
if ( ! $url && $training && trim( $training->post_content ) ) { $url = get_permalink( $training ); }
?>
<section class="home-panel__content home-panel__content--conocer home-trainings" aria-labelledby="capacitaciones-title">
	<h2 class="home-panel__section-title" id="capacitaciones-title">Capacitaciones</h2>
	<details class="panel-accordion home-trainings__accordion" open>
		<summary>Diplomaturas</summary>
		<div class="panel-accordion__content">
			<ul class="home-trainings__options" role="list"><li class="home-trainings__item">
				<?php if ( $url ) : ?><a class="home-trainings__option" href="<?php echo esc_url( $url ); ?>"<?php if ( wp_parse_url( $url, PHP_URL_HOST ) !== wp_parse_url( home_url(), PHP_URL_HOST ) ) : ?> target="_blank" rel="noopener noreferrer"<?php endif; ?>><?php echo esc_html( $label ); ?></a>
				<?php else : ?><span class="home-trainings__option" aria-disabled="true"><?php echo esc_html( $label ); ?><span class="screen-reader-text"> — información adicional pendiente</span></span><?php endif; ?>
			</li></ul>
		</div>
	</details>
	<details class="panel-accordion home-trainings__accordion">
		<summary>Seminarios</summary>
		<div class="panel-accordion__content"></div>
	</details>
</section>
