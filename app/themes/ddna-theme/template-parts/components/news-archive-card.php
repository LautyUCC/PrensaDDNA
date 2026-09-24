<?php
/** Card for the native WordPress news archive. @package DDNA_Theme */

$fallback_image = get_template_directory_uri() . '/assets/images/ddna-lockup-horizontal.png';
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'news-archive-card' ); ?>>
	<a class="news-archive-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'ddna-news-card', array( 'class' => 'news-archive-card__image', 'sizes' => '(min-width: 768px) 45vw, 92vw', 'loading' => 'lazy', 'decoding' => 'async' ) ); ?>
		<?php else : ?>
			<img class="news-archive-card__fallback" src="<?php echo esc_url( $fallback_image ); ?>" alt="" width="3202" height="794" loading="lazy" decoding="async">
		<?php endif; ?>
	</a>
	<div class="news-archive-card__body">
		<p class="news-archive-card__date"><time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></p>
		<?php the_title( '<h2 class="news-archive-card__title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>
		<div class="news-archive-card__excerpt"><?php echo wp_kses_post( get_the_excerpt() ); ?></div>
		<a class="button news-archive-card__button" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Leer más sobre %s', 'ddna-theme' ), get_the_title() ) ); ?>"><?php esc_html_e( 'Leer más', 'ddna-theme' ); ?></a>
	</div>
</article>
