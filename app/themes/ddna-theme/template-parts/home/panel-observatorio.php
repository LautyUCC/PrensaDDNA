<?php /** Observatory panel, prepared for approved structured content. @package DDNA_Theme */ ?>
<section class="home-panel ddna-folder" id="panel-observatorio" data-home-panel="observatorio" aria-labelledby="panel-observatorio-title" hidden>
	<div class="container container--content">
		<?php get_template_part( 'template-parts/components/panel-header', null, array( 'id' => 'observatorio', 'title' => 'Observatorio', 'subtitle' => 'Informes, estudios e indicadores sobre la situación de NNyA' ) ); ?>
		<div class="ddna-folder__content ddna-folder__content--collections">
			<?php get_template_part( 'template-parts/components/content-collection', null, array( 'id' => 'observatorio-publicaciones', 'title' => 'Informes, estudios e indicadores', 'empty' => 'El Observatorio está preparado para recibir publicaciones administradas desde WordPress. Todavía no hay contenido definitivo cargado.', 'query' => array( 'post_type' => 'documento', 'tax_query' => array( array( 'taxonomy' => 'ddna_tema', 'field' => 'slug', 'terms' => 'observatorio' ) ) ) ) ); ?>
		</div>
	</div>
</section>
