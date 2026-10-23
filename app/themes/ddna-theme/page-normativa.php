<?php
/** Página existente de Normativa; estructura editorial de Prensa. @package DDNA_Theme */
get_header();
?>
<main class="site-main editorial-page-layout" id="main-content">
	<div class="<?php echo esc_attr( ddna_theme_container_classes( array( 'site-container--editorial' ) ) ); ?>">
		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--page home-panel__content' ); ?>>
				<?php get_template_part( 'template-parts/components/return-home-menu', null, array( 'label' => __( 'Volver al inicio', 'ddna-theme' ), 'url' => home_url( '/' ) ) ); ?>
				<header class="entry-header"><h1 class="entry-title"><?php esc_html_e( 'Normativas', 'ddna-theme' ); ?></h1></header>
				<div class="entry-content"><?php the_content(); ?></div>
			</article>
		<?php endwhile; ?>
	</div>
</main>
<?php get_footer(); ?>
