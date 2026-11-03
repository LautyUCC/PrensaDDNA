<?php
/**
 * Contenido de publicación individual.
 *
 * @package DDNA_Theme
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--single' ); ?>>
	<?php
	$program_slug = get_post_field( 'post_name', get_the_ID() );
	$hide_program_meta = in_array( $program_slug, array( 'programa-va-con-vos', 'mesas-interinstitucionales', 'programas-participacion-nnya' ), true );
	?>
	<?php if ( ddna_theme_is_program_page() ) {
		get_template_part( 'template-parts/components/return-home-menu', null, array( 'url' => home_url( '/' ), 'label' => __( 'Volver al inicio', 'ddna-theme' ) ) );
	} ?>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<?php if ( ! $hide_program_meta ) : ?><div class="entry-meta"><?php ddna_theme_post_meta(); ?></div><?php endif; ?>
	</header>
	<?php if ( has_post_thumbnail() && 'programa-va-con-vos' !== $program_slug ) : ?>
		<figure class="entry-media"><?php the_post_thumbnail( 'large' ); ?></figure>
	<?php endif; ?>
	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages(); ?>
	</div>
</article>
