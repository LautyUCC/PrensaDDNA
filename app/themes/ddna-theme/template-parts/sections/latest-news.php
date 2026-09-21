<?php
/** Latest native WordPress posts. @package DDNA_Theme */
$news_category = get_category_by_slug( 'novedades' );
$home_settings = function_exists( 'ddna_core_get_home_settings' ) ? ddna_core_get_home_settings() : array( 'news_count' => 12 );
$query_args = array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => $home_settings['news_count'], 'ignore_sticky_posts' => true, 'no_found_rows' => true, 'update_post_term_cache' => false );
if ( $news_category ) { $query_args['cat'] = $news_category->term_id; }
$latest_news = new WP_Query( $query_args );
$nested = ! empty( $args['nested'] );
if ( ! $latest_news->have_posts() ) { return; }
?>
<section class="home-section home-news<?php echo $nested ? ' home-section--nested' : ''; ?>" aria-labelledby="news-title" data-carousel>
	<div class="<?php echo $nested ? 'section-frame' : 'container container--wide section-frame'; ?>">
		<header class="section-frame__header"><h2 class="section-frame__title" id="news-title">Novedades</h2></header>
		<?php get_template_part( 'template-parts/components/carousel-controls', null, array( 'carousel_id' => 'news-carousel', 'label' => __( 'novedades', 'ddna-theme' ) ) ); ?>
		<div class="carousel carousel--news<?php echo $latest_news->post_count <= 3 ? ' carousel--sparse' : ''; ?>" id="news-carousel" data-carousel-track tabindex="0" role="group" aria-roledescription="<?php esc_attr_e( 'carrusel', 'ddna-theme' ); ?>" aria-label="<?php esc_attr_e( 'Últimas novedades', 'ddna-theme' ); ?>">
			<?php while ( $latest_news->have_posts() ) : $latest_news->the_post(); get_template_part( 'template-parts/components/news-card', null, array( 'heading_level' => 3 ) ); endwhile; ?>
		</div>
	</div>
</section>
<?php wp_reset_postdata(); ?>
