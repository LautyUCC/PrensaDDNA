<?php /** Agenda, novedades nativas y prensa institucional. */ ?>
<section class="home-panel" id="panel-actualidad" data-home-panel="actualidad" aria-label="<?php esc_attr_e( 'Actualidad', 'ddna-theme' ); ?>" hidden>
	<div class="container container--content home-panel__collections">
		<section class="home-panel__content" aria-labelledby="current-agenda-title"><h2 class="home-panel__section-title" id="current-agenda-title">Agenda</h2><?php echo ddna_theme_page_link( 'agenda', 'Ver agenda' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></section>
		<div class="home-panel__content home-panel__content--collections">
			<?php get_template_part( 'template-parts/sections/latest-news', null, array( 'nested' => true ) ); ?>
			<?php $category = get_category_by_slug( 'novedades' ); if ( $category ) : ?><p><a class="button" href="<?php echo esc_url( get_category_link( $category ) ); ?>">Ver más novedades</a></p><?php endif; ?>
		</div>
		<section class="home-panel__content" aria-labelledby="current-press-title"><h2 class="home-panel__section-title" id="current-press-title">Prensa</h2><?php echo do_shortcode( '[ddna_press_links]' ); ?></section>
	</div>
</section>
