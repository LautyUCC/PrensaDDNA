<?php /** News and press panel. @package DDNA_Theme */ ?>
<section class="home-panel" id="panel-actualidad" data-home-panel="actualidad" aria-label="<?php esc_attr_e( 'Actualidad', 'ddna-theme' ); ?>" hidden>
	<div class="container container--content">
		<div class="home-panel__content home-panel__content--collections">
			<?php get_template_part( 'template-parts/sections/latest-news', null, array( 'nested' => true ) ); ?>
		</div>
	</div>
</section>
