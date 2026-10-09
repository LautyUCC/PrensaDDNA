<?php
/**
 * Páginas.
 *
 * @package DDNA_Theme
 */

get_header();
?>
<main class="site-main editorial-page-layout<?php echo is_page( 'defensoria' ) ? ' editorial-page-layout--defensoria' : ( is_page( 'asistencia' ) ? ' editorial-page-layout--asistencia' : '' ); ?>" id="main-content">
	<div class="<?php echo esc_attr( ddna_theme_container_classes( array( 'site-container--editorial', is_page( 'contacto' ) ? 'site-container--contact' : '' ) ) ); ?>">
		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>
			<?php get_template_part( 'template-parts/content/content', 'page' ); ?>
		<?php endwhile; ?>
	</div>
</main>
<?php
get_footer();
