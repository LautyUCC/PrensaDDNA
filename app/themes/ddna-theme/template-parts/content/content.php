<?php
/**
 * Resumen genérico de contenido.
 *
 * @package DDNA_Theme
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-summary' ); ?>>
	<header class="entry-header">
		<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>
		<div class="entry-meta"><?php ddna_theme_post_meta(); ?></div>
	</header>
	<div class="entry-content"><?php the_excerpt(); ?></div>
</article>
