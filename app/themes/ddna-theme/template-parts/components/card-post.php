<?php
/**
 * Tarjeta reutilizable de publicación.
 *
 * @package DDNA_Theme
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php the_post_thumbnail( 'medium_large' ); ?>
		</a>
	<?php endif; ?>
	<div class="card__body">
		<p class="card__meta"><?php echo esc_html( get_the_date() ); ?></p>
		<?php the_title( '<h2 class="card__title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>
		<div class="card__excerpt"><?php the_excerpt(); ?></div>
	</div>
</article>
