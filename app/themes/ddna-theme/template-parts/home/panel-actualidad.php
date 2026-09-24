<?php /** News and press panel. @package DDNA_Theme */ ?>
<section class="home-panel" id="panel-actualidad" data-home-panel="actualidad" aria-label="<?php esc_attr_e( 'Actualidad', 'ddna-theme' ); ?>" hidden>
	<div class="container container--content">
		<div class="home-panel__content home-panel__content--news">
			<h2 class="home-panel__section-title">Actualidad</h2>
			<details class="panel-accordion" open><summary>Agenda</summary><div class="panel-accordion__content"><p class="panel-empty-state">La agenda se publicará cuando estén definidos los próximos eventos.</p></div></details>
			<?php get_template_part( 'template-parts/sections/latest-news', null, array( 'nested' => true ) ); ?>
			<details class="panel-accordion"><summary>Prensa</summary><div class="panel-accordion__content"><section aria-labelledby="press-media-title"><h3 id="press-media-title">La Defensoría en los Medios</h3><p class="panel-empty-state">Los contenidos de prensa se publicarán cuando estén aprobados.</p></section><section aria-labelledby="press-statements-title"><h3 id="press-statements-title">Comunicados</h3><h4>2025</h4><ul class="panel-bullets"><li>Comunicado conjunto de las Defensorías de NNyA del país ante el veto presidencial a la Ley de Emergencia Pediátrica.</li><li>Comunicado sobre el veto a la Ley de Emergencia en Discapacidad.</li></ul><h4>2022</h4><ul class="panel-bullets"><li>Ley de Responsabilidad Penal Juvenil: derogación del Decreto-Ley 22.278.</li></ul></section></div></details>
		</div>
	</div>
</section>
