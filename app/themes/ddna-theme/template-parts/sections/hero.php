<?php
/**
 * Hero institucional de la portada.
 *
 * @package DDNA_Theme
 */

$hero_video = get_template_directory_uri() . '/assets/video/ddna-home-2026.mp4';
?>
<section class="home-hero" aria-labelledby="home-hero-title">
	<h1 class="screen-reader-text" id="home-hero-title"><?php esc_html_e( 'Defensoría de los Derechos de Niñas, Niños y Adolescentes de la Provincia de Córdoba', 'ddna-theme' ); ?></h1>
	<video class="home-hero__video" autoplay muted playsinline preload="metadata" aria-hidden="true" tabindex="-1" width="1904" height="518">
		<source src="<?php echo esc_url( $hero_video ); ?>" type="video/mp4">
	</video>
</section>
