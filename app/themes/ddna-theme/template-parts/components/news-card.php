<?php
/** Dynamic news card. @package DDNA_Theme */
$heading_tag = isset( $args['heading_level'] ) && 4 === (int) $args['heading_level'] ? 'h4' : 'h3';
?>
<article <?php post_class( 'news-card carousel__item' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?><a class="news-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"><?php the_post_thumbnail( 'ddna-news-card', array( 'class' => 'news-card__image', 'sizes' => '(min-width: 1600px) 23vw, (min-width: 1200px) 30vw, (min-width: 768px) 48vw, 90vw', 'loading' => 'lazy', 'decoding' => 'async' ) ); ?></a><?php endif; ?>
	<div class="news-card__body">
		<?php printf( '<%1$s class="news-card__title"><a href="%2$s">%3$s</a></%1$s>', esc_attr( $heading_tag ), esc_url( get_permalink() ), esc_html( get_the_title() ) ); ?>
		<p class="news-card__date"><time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></p>
		<div class="news-card__excerpt"><?php echo wp_kses_post( get_the_excerpt() ); ?></div>
		<a class="button news-card__button" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Leer más sobre %s', 'ddna-theme' ), get_the_title() ) ); ?>"><?php esc_html_e( 'Leer más', 'ddna-theme' ); ?></a>
	</div>
</article>
