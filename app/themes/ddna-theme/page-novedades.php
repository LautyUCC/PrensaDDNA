<?php
/**
 * Archivo de novedades nativas.
 *
 * @package DDNA_Theme
 */

get_header();

$search_term   = isset( $_GET['buscar'] ) ? sanitize_text_field( wp_unslash( $_GET['buscar'] ) ) : '';
$current_page  = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );
$news_category = get_category_by_slug( 'novedades' );
$query_args    = array(
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'posts_per_page'      => 12,
	'paged'               => $current_page,
	'orderby'             => 'date',
	'order'               => 'DESC',
	'ignore_sticky_posts' => true,
);

if ( $news_category ) {
	$query_args['cat'] = $news_category->term_id;
}

if ( '' !== $search_term ) {
	$query_args['s'] = $search_term;
}

$news_query = new WP_Query( $query_args );
?>
<main class="site-main news-archive" id="main-content">
	<div class="container container--content">
		<section class="news-archive__panel" aria-labelledby="news-archive-title">
			<header class="news-archive__header">
				<h1 id="news-archive-title"><?php esc_html_e( 'Novedades', 'ddna-theme' ); ?></h1>
			</header>

			<form class="news-archive__search" method="get" action="<?php echo esc_url( get_permalink() ); ?>" role="search">
				<label class="screen-reader-text" for="news-search"><?php esc_html_e( 'Buscar novedades', 'ddna-theme' ); ?></label>
				<input id="news-search" name="buscar" type="search" value="<?php echo esc_attr( $search_term ); ?>" placeholder="<?php esc_attr_e( 'Buscar novedades...', 'ddna-theme' ); ?>">
				<button type="submit"><?php esc_html_e( 'Buscar', 'ddna-theme' ); ?></button>
			</form>

			<?php if ( $news_query->have_posts() ) : ?>
				<div class="news-archive__grid">
					<?php while ( $news_query->have_posts() ) : $news_query->the_post(); ?>
						<?php get_template_part( 'template-parts/components/news-archive-card' ); ?>
					<?php endwhile; ?>
				</div>

				<?php if ( $news_query->max_num_pages > 1 ) : ?>
					<nav class="news-archive__pagination" aria-label="<?php esc_attr_e( 'Paginación de novedades', 'ddna-theme' ); ?>">
						<?php
						echo wp_kses_post(
							paginate_links(
								array(
									'base'      => add_query_arg( 'paged', '%#%', get_permalink() ),
									'format'    => '',
									'current'   => $current_page,
									'total'     => $news_query->max_num_pages,
									'type'      => 'list',
									'prev_text' => __( 'Anterior', 'ddna-theme' ),
									'next_text' => __( 'Siguiente', 'ddna-theme' ),
									'add_args'  => '' !== $search_term ? array( 'buscar' => $search_term ) : false,
								)
							)
						);
						?>
					</nav>
				<?php endif; ?>
			<?php elseif ( '' !== $search_term ) : ?>
				<p class="news-archive__empty"><?php esc_html_e( 'No se encontraron novedades para esta búsqueda.', 'ddna-theme' ); ?></p>
			<?php else : ?>
				<p class="news-archive__empty"><?php esc_html_e( 'Todavía no hay novedades publicadas.', 'ddna-theme' ); ?></p>
			<?php endif; ?>
		</section>
	</div>
</main>
<?php
wp_reset_postdata();
get_footer();
