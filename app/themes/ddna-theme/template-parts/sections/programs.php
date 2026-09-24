<?php
/** Featured programs. @package DDNA_Theme */
$home_settings = function_exists( 'ddna_core_get_home_settings' ) ? ddna_core_get_home_settings() : array( 'programs_count' => 9 );
$programs = new WP_Query( array( 'post_type' => 'programa', 'post_status' => 'publish', 'posts_per_page' => $home_settings['programs_count'], 'meta_key' => '_ddna_featured', 'meta_value' => '1', 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'DESC' ), 'no_found_rows' => true, 'update_post_term_cache' => false ) );
$nested = ! empty( $args['nested'] );
if ( ! $programs->have_posts() ) { return; }
?>
<section class="home-section home-programs<?php echo $nested ? ' home-section--nested' : ''; ?>" aria-labelledby="programs-title" data-carousel>
	<div class="<?php echo $nested ? 'section-frame' : 'container container--wide section-frame'; ?>">
		<header class="section-frame__header"><h2 class="section-frame__title" id="programs-title">Programas</h2></header>
		<?php get_template_part( 'template-parts/components/carousel-controls', null, array( 'carousel_id' => 'programs-carousel', 'label' => __( 'programas', 'ddna-theme' ) ) ); ?>
		<div class="carousel carousel--programs<?php echo $programs->post_count <= 2 ? ' carousel--sparse' : ''; ?>" id="programs-carousel" data-carousel-track tabindex="0" role="group" aria-roledescription="<?php esc_attr_e( 'carrusel', 'ddna-theme' ); ?>" aria-label="<?php esc_attr_e( 'Programas destacados', 'ddna-theme' ); ?>">
			<?php while ( $programs->have_posts() ) : $programs->the_post(); get_template_part( 'template-parts/components/program-card', null, array( 'heading_level' => 3, 'nested' => $nested ) ); endwhile; ?>
		</div>
	</div>
</section>
<?php wp_reset_postdata(); ?>
