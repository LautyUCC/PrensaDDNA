<?php
/**
 * Reusable collection for structured institutional content.
 *
 * @package DDNA_Theme
 */
$collection_title = isset( $args['title'] ) ? $args['title'] : '';
$collection_id    = isset( $args['id'] ) ? sanitize_html_class( $args['id'] ) : '';
$collection_empty = isset( $args['empty'] ) ? $args['empty'] : __( 'La estructura está preparada. El contenido se publicará desde WordPress cuando esté aprobado.', 'ddna-theme' );
$query_args       = isset( $args['query'] ) && is_array( $args['query'] ) ? $args['query'] : array();
$query_args       = wp_parse_args( $query_args, array( 'post_status' => 'publish', 'posts_per_page' => 12, 'no_found_rows' => true, 'update_post_term_cache' => false ) );
$collection       = new WP_Query( $query_args );
?>
<section class="panel-section home-panel__section" aria-labelledby="<?php echo esc_attr( $collection_id ); ?>-title">
	<h3 class="home-panel__section-title" id="<?php echo esc_attr( $collection_id ); ?>-title"><?php echo esc_html( $collection_title ); ?></h3>
	<div class="home-panel__section-content">
		<?php if ( $collection->have_posts() ) : ?>
			<div class="content-collection">
				<?php while ( $collection->have_posts() ) : $collection->the_post(); ?>
					<?php
					$post_type    = get_post_type();
					$external_url = get_post_meta( get_the_ID(), '_ddna_external_url', true );
					$file_id      = absint( get_post_meta( get_the_ID(), '_ddna_file_id', true ) );
					$item_url     = $file_id ? wp_get_attachment_url( $file_id ) : ( $external_url ? $external_url : get_permalink() );
					?>
					<article <?php post_class( 'content-tile' ); ?>>
						<?php if ( has_post_thumbnail() ) : ?><a class="content-tile__media" href="<?php echo esc_url( $item_url ); ?>" tabindex="-1" aria-hidden="true"><?php the_post_thumbnail( 'medium', array( 'loading' => 'lazy', 'decoding' => 'async' ) ); ?></a><?php endif; ?>
						<div class="content-tile__body">
							<h4 class="content-tile__title"><a href="<?php echo esc_url( $item_url ); ?>"><?php echo esc_html( get_the_title() ); ?></a></h4>
							<?php if ( 'capacitacion' === $post_type && get_post_meta( get_the_ID(), '_ddna_start_date', true ) ) : ?><p class="content-tile__meta"><?php echo esc_html( get_post_meta( get_the_ID(), '_ddna_start_date', true ) ); ?></p><?php endif; ?>
							<?php if ( 'subsede' === $post_type && get_post_meta( get_the_ID(), '_ddna_locality', true ) ) : ?><p class="content-tile__meta"><?php echo esc_html( get_post_meta( get_the_ID(), '_ddna_locality', true ) ); ?></p><?php endif; ?>
							<?php if ( has_excerpt() ) : ?><div class="content-tile__excerpt"><?php echo wp_kses_post( get_the_excerpt() ); ?></div><?php endif; ?>
							<a class="content-tile__link" href="<?php echo esc_url( $item_url ); ?>"><?php echo esc_html( $file_id ? __( 'Descargar', 'ddna-theme' ) : __( 'Ver más', 'ddna-theme' ) ); ?></a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
		<?php else : ?>
			<p class="panel-empty-state"><?php echo esc_html( $collection_empty ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php wp_reset_postdata(); ?>
