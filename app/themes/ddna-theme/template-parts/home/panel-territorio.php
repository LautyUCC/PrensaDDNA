<?php
/** Territory panel. @package DDNA_Theme */
$mapping_page = get_page_by_path( 'mapeo-instituciones' );
?>
<section class="home-panel" id="panel-territorio" data-home-panel="territorio" aria-label="<?php esc_attr_e( 'Territorio', 'ddna-theme' ); ?>" hidden>
	<div class="container container--content">
		<div class="home-panel__content home-panel__content--collections">
			<?php get_template_part( 'template-parts/components/content-collection', null, array( 'id' => 'subsedes', 'title' => 'Subsedes', 'empty' => 'La sección está preparada para mostrar subsedes administradas desde WordPress. Todavía no hay sedes definitivas publicadas.', 'query' => array( 'post_type' => 'subsede', 'meta_key' => '_ddna_locality', 'orderby' => array( 'meta_value' => 'ASC', 'title' => 'ASC' ) ) ) ); ?>
			<?php if ( $mapping_page ) : ?><p class="panel-primary-action"><a class="button" href="<?php echo esc_url( get_permalink( $mapping_page ) ); ?>"><?php esc_html_e( 'Mapeo de instituciones que trabajan con NNyA', 'ddna-theme' ); ?></a></p><?php endif; ?>
		</div>
	</div>
</section>
