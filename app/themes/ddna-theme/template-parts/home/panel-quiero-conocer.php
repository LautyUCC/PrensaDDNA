<?php /** Programs, campaigns and training panel. @package DDNA_Theme */ ?>
<section class="home-panel ddna-folder" id="panel-quiero-conocer" data-home-panel="quiero-conocer" aria-labelledby="panel-quiero-conocer-title" hidden>
	<div class="container container--content">
		<?php get_template_part( 'template-parts/components/panel-header', null, array( 'id' => 'quiero-conocer', 'title' => 'Quiero conocer', 'subtitle' => 'Programas y acciones que ofrece la Defensoría' ) ); ?>
		<div class="ddna-folder__content ddna-folder__content--collections">
			<?php get_template_part( 'template-parts/sections/programs', null, array( 'nested' => true ) ); ?>
			<?php get_template_part( 'template-parts/sections/campaigns', null, array( 'nested' => true ) ); ?>
			<?php get_template_part( 'template-parts/components/content-collection', null, array( 'id' => 'capacitaciones', 'title' => 'Capacitaciones', 'query' => array( 'post_type' => 'capacitacion', 'orderby' => array( 'meta_value' => 'ASC', 'date' => 'DESC' ), 'meta_key' => '_ddna_start_date' ) ) ); ?>
		</div>
	</div>
</section>
