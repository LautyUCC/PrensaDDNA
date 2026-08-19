<?php
/**
 * Archivos y taxonomías.
 *
 * @package DDNA_Theme
 */

get_header();
?>
<main class="site-main" id="main-content">
	<div class="<?php echo esc_attr( ddna_theme_container_classes() ); ?>">
		<header class="page-header">
			<?php the_archive_title( '<h1>', '</h1>' ); ?>
			<?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
		</header>
		<div class="content-grid">
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : ?>
					<?php the_post(); ?>
					<?php get_template_part( 'template-parts/components/card', 'post' ); ?>
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
