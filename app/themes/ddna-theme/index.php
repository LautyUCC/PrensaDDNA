<?php
/**
 * Template de respaldo.
 *
 * @package DDNA_Theme
 */

get_header();
?>
<main class="site-main" id="main-content">
	<div class="<?php echo esc_attr( ddna_theme_container_classes() ); ?>">
		<header class="page-header">
			<h1><?php esc_html_e( 'Publicaciones', 'ddna-theme' ); ?></h1>
		</header>
		<div class="content-grid">
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : ?>
					<?php the_post(); ?>
					<?php get_template_part( 'template-parts/content/content', get_post_type() ); ?>
				<?php endwhile; ?>
				<?php the_posts_pagination(); ?>
			<?php else : ?>
				<?php get_template_part( 'template-parts/content/content', 'none' ); ?>
			<?php endif; ?>
		</div>
	</div>
</main>
<?php
get_footer();
