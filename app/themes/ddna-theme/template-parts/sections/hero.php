<?php
/**
 * Hero institucional de la portada.
 *
 * @package DDNA_Theme
 */

$front_page_id  = get_queried_object_id();
$hero_image_id  = $front_page_id ? get_post_thumbnail_id( $front_page_id ) : 0;
$fallback_image = get_template_directory_uri() . '/assets/images/hero-home-official.jpg';
$official_logo  = get_template_directory_uri() . '/assets/images/logos/ddna-horizontal.png';
?>
<section class="home-hero" aria-labelledby="home-hero-title">
	<div class="home-hero__media">
		<?php if ( $hero_image_id ) : ?>
			<?php
			echo wp_get_attachment_image(
				$hero_image_id,
				'full',
				false,
				array(
					'class'         => 'home-hero__image',
					'loading'       => 'eager',
					'fetchpriority' => 'high',
					'sizes'         => '100vw',
				)
			);
			?>
		<?php else : ?>
			<img class="home-hero__image" src="<?php echo esc_url( $fallback_image ); ?>" alt="<?php esc_attr_e( 'Adolescentes participando de una actividad grupal en un aula', 'ddna-theme' ); ?>" width="4012" height="1988" loading="eager" fetchpriority="high">
		<?php endif; ?>
	</div>
	<div class="home-hero__overlay" aria-hidden="true"></div>
	<div class="home-hero__content">
		<h1 class="screen-reader-text" id="home-hero-title"><?php esc_html_e( 'Defensoría de los Derechos de Niñas, Niños y Adolescentes de la Provincia de Córdoba', 'ddna-theme' ); ?></h1>
		<a class="home-hero__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Ir al inicio', 'ddna-theme' ); ?>">
			<img class="home-hero__logo home-hero__logo--original" src="<?php echo esc_url( $official_logo ); ?>" alt="" width="3202" height="794" decoding="async">
			<img class="home-hero__logo home-hero__logo--contrast" src="<?php echo esc_url( $official_logo ); ?>" alt="" width="3202" height="794" aria-hidden="true" decoding="async">
		</a>
	</div>
</section>
