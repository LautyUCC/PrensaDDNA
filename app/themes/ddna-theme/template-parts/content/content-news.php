<?php
/** Presentación automática de entradas con etiqueta novedad. */
$gallery = function_exists( 'ddna_core_news_image_ids' ) ? ddna_core_news_image_ids( get_post_meta( get_the_ID(), '_ddna_news_gallery', true ) ) : array();
$carousel = (bool) get_post_meta( get_the_ID(), '_ddna_news_carousel', true );
$gallery_id = 'news-gallery-' . get_the_ID();
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--single news-article' ); ?>>
	<a class="button news-article__back" href="<?php $category = get_category_by_slug( 'novedades' ); echo esc_url( $category ? get_category_link( $category ) : home_url( '/novedades/' ) ); ?>">Volver a Novedades</a>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<p class="entry-meta"><time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></p>
	</header>
	<?php if ( has_post_thumbnail() ) : ?>
		<figure class="entry-media news-article__cover"><?php the_post_thumbnail( 'large', array( 'loading' => 'eager', 'decoding' => 'async' ) ); ?></figure>
	<?php endif; ?>
	<div class="entry-content"><?php the_content(); ?><?php wp_link_pages(); ?></div>
	<?php if ( $gallery ) : ?>
		<section class="news-gallery<?php echo $carousel ? ' news-gallery--carousel' : ''; ?>" aria-label="Imágenes de la novedad"<?php if ( $carousel ) : ?> data-carousel<?php endif; ?>>
			<h2>Imágenes</h2>
			<?php if ( $carousel ) { get_template_part( 'template-parts/components/carousel-controls', null, array( 'carousel_id' => $gallery_id, 'label' => 'imágenes de la novedad' ) ); } ?>
			<div class="news-gallery__track<?php echo $carousel ? ' carousel' : ''; ?>" id="<?php echo esc_attr( $gallery_id ); ?>"<?php if ( $carousel ) : ?> data-carousel-track tabindex="0" role="group" aria-roledescription="carrusel" aria-label="Fotos de la novedad: usar flechas izquierda y derecha"<?php endif; ?>>
				<?php foreach ( $gallery as $id ) : ?><figure class="news-gallery__slide<?php echo $carousel ? ' carousel__item' : ''; ?>"><?php echo wp_get_attachment_image( $id, 'large', false, array( 'loading' => 'lazy', 'decoding' => 'async', 'sizes' => '(min-width: 1200px) 900px, 90vw' ) ); ?><?php $caption = wp_get_attachment_caption( $id ); if ( $caption ) : ?><figcaption><?php echo esc_html( $caption ); ?></figcaption><?php endif; ?></figure><?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>
</article>
