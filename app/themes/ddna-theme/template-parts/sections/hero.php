<?php
/**
 * Hero institucional de la portada.
 *
 * @package DDNA_Theme
 */

$front_page_id  = get_queried_object_id();
$hero_image_id  = $front_page_id ? get_post_thumbnail_id( $front_page_id ) : 0;
$custom_logo_id = (int) get_theme_mod( 'custom_logo' );
$fallback_image = get_template_directory_uri() . '/assets/images/hero-classroom.webp';
$fallback_logo  = get_template_directory_uri() . '/assets/images/ddna-lockup-horizontal.png';
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
			<img class="home-hero__image" src="<?php echo esc_url( $fallback_image ); ?>" alt="<?php esc_attr_e( 'Adolescentes participando de una actividad grupal en un aula', 'ddna-theme' ); ?>" width="2560" height="1707" loading="eager" fetchpriority="high">
		<?php endif; ?>
	</div>
	<div class="home-hero__overlay" aria-hidden="true"></div>
	<div class="home-hero__content">
		<h1 class="screen-reader-text" id="home-hero-title"><?php esc_html_e( 'Defensoría de los Derechos de Niñas, Niños y Adolescentes de la Provincia de Córdoba', 'ddna-theme' ); ?></h1>
		<a class="home-hero__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Ir al inicio', 'ddna-theme' ); ?>">
			<?php if ( $custom_logo_id ) : ?>
				<?php echo wp_get_attachment_image( $custom_logo_id, 'full', false, array( 'class' => 'home-hero__logo', 'alt' => '' ) ); ?>
			<?php else : ?>
				<img class="home-hero__logo" src="<?php echo esc_url( $fallback_logo ); ?>" alt="" width="1040" height="254">
			<?php endif; ?>
		</a>
	</div>
</section>
