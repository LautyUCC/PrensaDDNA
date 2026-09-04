<?php
/** Administrable campaigns. @package DDNA_Theme */
$home_settings = function_exists( 'ddna_core_get_home_settings' ) ? ddna_core_get_home_settings() : array( 'campaigns_count' => 12 );
$campaigns = new WP_Query( array( 'post_type' => 'campana', 'post_status' => 'publish', 'posts_per_page' => $home_settings['campaigns_count'], 'meta_key' => '_ddna_featured', 'meta_value' => '1', 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'DESC' ), 'no_found_rows' => true, 'update_post_term_cache' => false ) );
$nested = ! empty( $args['nested'] );
if ( ! $campaigns->have_posts() ) { return; }
?>
<section class="home-section home-campaigns<?php echo $nested ? ' home-section--nested' : ''; ?>" aria-labelledby="campaigns-title" data-carousel>
	<div class="<?php echo $nested ? 'section-frame' : 'container container--wide section-frame'; ?>">
		<header class="section-frame__header"><h3 class="section-frame__title" id="campaigns-title">Campañas</h3></header>
		<?php get_template_part( 'template-parts/components/carousel-controls', null, array( 'carousel_id' => 'campaigns-carousel', 'label' => __( 'campañas', 'ddna-theme' ) ) ); ?>
		<div class="carousel carousel--campaigns<?php echo $campaigns->post_count <= 2 ? ' carousel--sparse' : ''; ?>" id="campaigns-carousel" data-carousel-track tabindex="0" role="group" aria-roledescription="<?php esc_attr_e( 'carrusel', 'ddna-theme' ); ?>" aria-label="<?php esc_attr_e( 'Campañas destacadas', 'ddna-theme' ); ?>">
			<?php while ( $campaigns->have_posts() ) : $campaigns->the_post(); get_template_part( 'template-parts/components/campaign-card', null, array( 'heading_level' => $nested ? 4 : 3 ) ); endwhile; ?>
		</div>
	</div>
</section>
<?php wp_reset_postdata(); ?>
