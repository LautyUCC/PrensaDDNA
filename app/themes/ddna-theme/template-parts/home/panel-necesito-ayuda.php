<?php
/** Assistance panel based on the approved institutional reference. @package DDNA_Theme */
$details = function_exists( 'ddna_core_get_institutional_settings' ) ? ddna_core_get_institutional_settings() : array();
$topics  = array(
	'Orientación en torno a procedimientos para el acceso a derechos.',
	'Violencia y maltrato hacia las infancias.',
	'Presunción de abuso.',
	'Grooming y vulneraciones en redes sociales.',
	'Conflictos entre pares. Bullying/Cyberbullying.',
	'Derecho de toda niña, niño y adolescente a ser escuchado.',
	'Conflictos familiares que afectan a niñas y niños.',
	'Actualización o renovación del DNI.',
	'Falta de atención o insumos en organismos públicos y privados.',
	'Consumo de sustancias.',
	'Incumplimiento de las obras sociales.',
	'Dificultad de acceso al sistema educativo o sanitario.',
);
?>
<section class="home-panel ddna-folder" id="panel-necesito-ayuda" data-home-panel="necesito-ayuda" aria-labelledby="panel-necesito-ayuda-title" hidden>
	<div class="container container--content">
		<?php get_template_part( 'template-parts/components/panel-header', null, array( 'id' => 'necesito-ayuda', 'title' => 'Necesito ayuda', 'subtitle' => 'Asesoramiento y Consultas' ) ); ?>
		<div class="ddna-folder__content">
			<h3 class="panel-lead-title"><?php esc_html_e( 'Asistencia, Orientación y Supervisión Institucional', 'ddna-theme' ); ?></h3>
			<p><?php esc_html_e( 'Somos un organismo de garantía y control del cumplimiento de los derechos de niñas, niños y adolescentes. Recibimos consultas, orientamos y realizamos supervisión institucional cuando corresponde.', 'ddna-theme' ); ?></p>
			<details class="panel-accordion"><summary><?php esc_html_e( '¿Cómo te ayudamos?', 'ddna-theme' ); ?></summary><div class="panel-accordion__content"><ul class="panel-bullets"><li><strong><?php esc_html_e( 'Orientación y asesoramiento:', 'ddna-theme' ); ?></strong> <?php esc_html_e( 'guiamos sobre temas relacionados con NNyA, resolvemos dudas e inquietudes y orientamos hacia dónde concurrir según el caso.', 'ddna-theme' ); ?></li><li><strong><?php esc_html_e( 'Supervisión institucional:', 'ddna-theme' ); ?></strong> <?php esc_html_e( 'se realizan actuaciones ante entidades públicas o privadas dedicadas a la atención y albergue de niñas, niños y adolescentes.', 'ddna-theme' ); ?></li><li><strong><?php esc_html_e( 'Vías de recepción:', 'ddna-theme' ); ?></strong> <?php esc_html_e( 'telefónica, personal, nota, agenda institucional, correo, formulario web, aplicación y redes sociales.', 'ddna-theme' ); ?></li></ul></div></details>
			<details class="panel-accordion"><summary><?php esc_html_e( '¿Qué temas se pueden consultar?', 'ddna-theme' ); ?></summary><div class="panel-accordion__content"><ul class="assistance-topics" role="list"><?php foreach ( $topics as $topic ) : ?><li><?php echo esc_html( $topic ); ?></li><?php endforeach; ?></ul></div></details>
			<details class="panel-accordion"><summary><?php esc_html_e( '¿Quién asesora?', 'ddna-theme' ); ?></summary><div class="panel-accordion__content"><p><?php esc_html_e( 'Un equipo interdisciplinario de profesionales de Abogacía, Trabajo Social y Psicología brinda información y escucha activa.', 'ddna-theme' ); ?></p></div></details>
			<details class="panel-accordion"><summary><?php esc_html_e( '¿Cómo hacer uso del servicio?', 'ddna-theme' ); ?></summary><div class="panel-accordion__content"><p><?php esc_html_e( 'El servicio es completamente gratuito durante todo el año.', 'ddna-theme' ); ?></p></div></details>
			<details class="panel-accordion"><summary><?php esc_html_e( 'Contacto', 'ddna-theme' ); ?></summary><div class="panel-accordion__content contact-chips">
				<?php if ( ! empty( $details['phone'] ) ) : ?><a href="<?php echo esc_url( ddna_theme_phone_uri( $details['phone'] ) ); ?>"><strong><?php esc_html_e( 'Línea fija', 'ddna-theme' ); ?></strong><span><?php echo esc_html( $details['phone'] ); ?></span></a><?php endif; ?>
				<?php if ( ! empty( $details['assistance_phone'] ) ) : ?><a href="<?php echo esc_url( ddna_theme_phone_uri( $details['assistance_phone'] ) ); ?>"><strong><?php echo esc_html( $details['assistance_label'] ); ?></strong><span><?php echo esc_html( $details['assistance_phone'] ); ?></span></a><?php endif; ?>
				<?php if ( ! empty( $details['adolescence_phone'] ) ) : ?><a href="<?php echo esc_url( ddna_theme_phone_uri( $details['adolescence_phone'] ) ); ?>"><strong><?php echo esc_html( $details['adolescence_label'] ); ?></strong><span><?php echo esc_html( $details['adolescence_phone'] ); ?></span></a><?php endif; ?>
				<?php if ( ! empty( $details['email'] ) ) : ?><a href="mailto:<?php echo esc_attr( $details['email'] ); ?>"><strong><?php esc_html_e( 'Correo', 'ddna-theme' ); ?></strong><span><?php echo esc_html( $details['email'] ); ?></span></a><?php endif; ?>
				<?php if ( ! empty( $details['address'] ) ) : ?><span><strong><?php esc_html_e( 'Dirección', 'ddna-theme' ); ?></strong><span><?php echo esc_html( $details['address'] ); ?></span></span><?php endif; ?>
			</div></details>
		</div>
	</div>
</section>
