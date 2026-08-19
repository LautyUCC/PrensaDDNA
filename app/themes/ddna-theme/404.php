<?php
/**
 * Página no encontrada.
 *
 * @package DDNA_Theme
 */

get_header();
?>
<main class="site-main" id="main-content">
	<div class="<?php echo esc_attr( ddna_theme_container_classes( array( 'error-404' ) ) ); ?>">
		<p class="eyebrow">404</p>
		<h1><?php esc_html_e( 'No encontramos esa página', 'ddna-theme' ); ?></h1>
		<p><?php esc_html_e( 'Puede que el contenido haya cambiado de ubicación. Probá con una búsqueda o regresá al inicio.', 'ddna-theme' ); ?></p>
		<?php get_search_form(); ?>
		<a class="button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Volver al inicio', 'ddna-theme' ); ?></a>
	</div>
</main>
<?php
get_footer();
