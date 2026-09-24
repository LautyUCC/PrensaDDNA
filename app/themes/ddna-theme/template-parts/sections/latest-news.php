<?php
/** Latest native WordPress posts. @package DDNA_Theme */

$news_category = get_category_by_slug( 'novedades' );
$query_args    = array(
		'post_type'              => 'post',
		'post_status'            => 'publish',
		'posts_per_page'         => 4,
		'orderby'                => 'date',
		'order'                  => 'DESC',
		'ignore_sticky_posts'    => true,
		'no_found_rows'          => true,
		'update_post_meta_cache' => true,
		'update_post_term_cache' => false,
	);

if ( $news_category ) {
	$query_args['cat'] = $news_category->term_id;
}

$latest_news = new WP_Query( $query_args );
$nested = ! empty( $args['nested'] );

if ( ! $latest_news->have_posts() ) {
	return;
}
?>
<section class="home-section home-news<?php echo $nested ? ' home-section--nested' : ''; ?>" aria-labelledby="news-title" data-carousel>
	<?php if ( ! $nested ) : ?><div class="container container--wide section-frame"><?php endif; ?>
	<header class="<?php echo $nested ? 'home-news__header' : 'section-frame__header'; ?>"><h3 class="<?php echo $nested ? 'home-panel__section-title' : 'section-frame__title'; ?>" id="news-title">Novedades</h3></header>
	<?php get_template_part( 'template-parts/components/carousel-controls', null, array( 'carousel_id' => 'news-carousel', 'label' => __( 'novedades', 'ddna-theme' ) ) ); ?>
	<div class="carousel carousel--news<?php echo $latest_news->post_count <= 4 ? ' carousel--sparse' : ''; ?>" id="news-carousel" data-carousel-track tabindex="0" role="group" aria-roledescription="<?php esc_attr_e( 'carrusel', 'ddna-theme' ); ?>" aria-label="<?php esc_attr_e( 'Últimas novedades', 'ddna-theme' ); ?>">
		<?php while ( $latest_news->have_posts() ) : $latest_news->the_post(); get_template_part( 'template-parts/components/news-card', null, array( 'heading_level' => $nested ? 4 : 3 ) ); endwhile; ?>
	</div>
	<?php $news_archive_page = get_page_by_path( 'novedades' ); ?>
	<p class="home-news__archive-link"><a class="button" href="<?php echo esc_url( $news_archive_page ? get_permalink( $news_archive_page ) : home_url( '/novedades/' ) ); ?>"><?php esc_html_e( 'Ver más', 'ddna-theme' ); ?></a></p>
	<?php if ( ! $nested ) : ?></div><?php endif; ?>
</section>
<?php wp_reset_postdata(); ?>
