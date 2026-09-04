<?php /** News and press panel. @package DDNA_Theme */ ?>
<section class="home-panel ddna-folder" id="panel-actualidad" data-home-panel="actualidad" aria-labelledby="panel-actualidad-title" hidden>
	<div class="container container--content">
		<?php get_template_part( 'template-parts/components/panel-header', null, array( 'id' => 'actualidad', 'title' => 'Actualidad', 'subtitle' => 'Novedades, agenda y prensa' ) ); ?>
		<div class="ddna-folder__content ddna-folder__content--collections">
			<?php get_template_part( 'template-parts/sections/latest-news', null, array( 'nested' => true ) ); ?>
		</div>
	</div>
</section>
