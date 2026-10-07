<?php
/**
 * Contenido de página.
 *
 * @package DDNA_Theme
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--page' . ( get_post_meta( get_the_ID(), '_ddna_content_version', true ) ? ' home-panel__content' : '' ) ); ?>>
	<?php if ( is_page( array( 'defensoria', 'informes-anuales', 'normativa', 'normativas', 'convenios', 'contacto' ) ) ) { get_template_part( 'template-parts/components/return-home-menu' ); } ?>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header>
	<?php if ( has_post_thumbnail() ) : ?>
		<figure class="entry-media"><?php the_post_thumbnail( 'large' ); ?></figure>
	<?php endif; ?>
	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages(); ?>
	</div>
</article>
