<?php
/**
 * Publicación individual.
 *
 * @package DDNA_Theme
 */

get_header();
?>
<main class="site-main<?php echo 'diplomatura-ia-derechos-digitales-nnya' === get_post_field( 'post_name', get_queried_object_id() ) ? ' diplomatura-ia' : ''; ?>" id="main-content">
	<div class="<?php echo esc_attr( ddna_theme_container_classes() ); ?>">
		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>
			<?php get_template_part( 'template-parts/content/content', function_exists( 'ddna_core_is_news' ) && ddna_core_is_news( get_the_ID() ) ? 'news' : 'single' ); ?>
			<?php if ( ! function_exists( 'ddna_core_is_news' ) || ! ddna_core_is_news( get_the_ID() ) ) { the_post_navigation(); } ?>
		<?php endwhile; ?>
	</div>
</main>
<?php
get_footer();
