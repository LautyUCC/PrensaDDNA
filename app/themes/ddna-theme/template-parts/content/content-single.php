<?php
/**
 * Contenido de publicación individual.
 *
 * @package DDNA_Theme
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--single' ); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<div class="entry-meta"><?php ddna_theme_post_meta(); ?></div>
	</header>
	<?php if ( has_post_thumbnail() ) : ?>
		<figure class="entry-media"><?php the_post_thumbnail( 'large' ); ?></figure>
	<?php endif; ?>
	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages(); ?>
	</div>
</article>
