<?php
/** Territory panel. @package DDNA_Theme */
$mapping_page = get_page_by_path( 'mapeo-instituciones' );
?>
<section class="home-panel ddna-folder" id="panel-territorio" data-home-panel="territorio" aria-labelledby="panel-territorio-title" hidden>
	<div class="container container--content">
		<?php get_template_part( 'template-parts/components/panel-header', null, array( 'id' => 'territorio', 'title' => 'Territorio', 'subtitle' => 'Nuestras subsedes' ) ); ?>
		<div class="ddna-folder__content ddna-folder__content--collections">
			<?php get_template_part( 'template-parts/components/content-collection', null, array( 'id' => 'subsedes', 'title' => 'Subsedes', 'empty' => 'La sección está preparada para mostrar subsedes administradas desde WordPress. Todavía no hay sedes definitivas publicadas.', 'query' => array( 'post_type' => 'subsede', 'meta_key' => '_ddna_locality', 'orderby' => array( 'meta_value' => 'ASC', 'title' => 'ASC' ) ) ) ); ?>
			<?php if ( $mapping_page ) : ?><p class="panel-primary-action"><a class="button" href="<?php echo esc_url( get_permalink( $mapping_page ) ); ?>"><?php esc_html_e( 'Mapeo de instituciones que trabajan con NNyA', 'ddna-theme' ); ?></a></p><?php endif; ?>
		</div>
	</div>
</section>
