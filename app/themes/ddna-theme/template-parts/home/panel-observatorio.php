<?php /** Observatory panel, prepared for approved structured content. @package DDNA_Theme */ ?>
<section class="home-panel" id="panel-observatorio" data-home-panel="observatorio" aria-label="<?php esc_attr_e( 'Observatorio', 'ddna-theme' ); ?>" hidden>
	<div class="container container--content">
		<div class="home-panel__content home-panel__content--collections">
			<?php get_template_part( 'template-parts/components/content-collection', null, array( 'id' => 'observatorio-publicaciones', 'title' => 'Informes, estudios e indicadores', 'empty' => 'El Observatorio está preparado para recibir publicaciones administradas desde WordPress. Todavía no hay contenido definitivo cargado.', 'query' => array( 'post_type' => 'documento', 'tax_query' => array( array( 'taxonomy' => 'ddna_tema', 'field' => 'slug', 'terms' => 'observatorio' ) ) ) ) ); ?>
		</div>
	</div>
</section>
