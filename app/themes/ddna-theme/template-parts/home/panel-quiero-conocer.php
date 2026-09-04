<?php /** Programs, campaigns and training panel. @package DDNA_Theme */ ?>
<section class="home-panel" id="panel-quiero-conocer" data-home-panel="quiero-conocer" aria-label="<?php esc_attr_e( 'Quiero conocer', 'ddna-theme' ); ?>" hidden>
	<div class="container container--content home-panel__collections">
		<div class="home-panel__content home-panel__content--conocer">
			<?php get_template_part( 'template-parts/sections/programs', null, array( 'nested' => true ) ); ?>
		</div>
		<div class="home-panel__content home-panel__content--conocer">
			<?php get_template_part( 'template-parts/sections/campaigns', null, array( 'nested' => true ) ); ?>
		</div>
		<?php get_template_part( 'template-parts/sections/trainings' ); ?>
	</div>
</section>
