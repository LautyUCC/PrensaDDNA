<?php
/**
 * Páginas.
 *
 * @package DDNA_Theme
 */

get_header();
?>
<main class="site-main" id="main-content">
	<div class="<?php echo esc_attr( ddna_theme_container_classes() ); ?>">
		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>
			<?php get_template_part( 'template-parts/content/content', 'page' ); ?>
		<?php endwhile; ?>
	</div>
</main>
<?php
get_footer();
