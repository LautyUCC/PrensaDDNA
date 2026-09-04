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
$contact_icons_uri = get_template_directory_uri() . '/assets/icons/contact/';
$case_email        = 'casosasistencia@gmail.com';
$address_lines     = ! empty( $details['address'] ) ? array_filter( array_map( 'trim', preg_split( '/\s*[·|]\s*/u', $details['address'] ) ) ) : array();
$whatsapp_url      = static function ( $phone ) {
	$digits = preg_replace( '/\D+/', '', (string) $phone );
	if ( str_starts_with( $digits, '351' ) ) {
		$digits = '54' . $digits;
	}
	return $digits ? 'https://wa.me/' . $digits : '';
};
?>
<section class="home-panel" id="panel-necesito-ayuda" data-home-panel="necesito-ayuda" aria-label="<?php esc_attr_e( 'Necesito ayuda', 'ddna-theme' ); ?>" hidden>
	<div class="container container--content">
		<div class="home-panel__content">
			<h3 class="panel-lead-title"><?php esc_html_e( 'Asistencia, Orientación y Supervisión Institucional', 'ddna-theme' ); ?></h3>
			<p><?php esc_html_e( 'Somos un organismo de garantía y control del cumplimiento de los derechos de niñas, niños y adolescentes. Recibimos consultas, orientamos y realizamos supervisión institucional cuando corresponde.', 'ddna-theme' ); ?></p>
			<details class="panel-accordion"><summary><?php esc_html_e( '¿Cómo te ayudamos?', 'ddna-theme' ); ?></summary><div class="panel-accordion__content"><ul class="panel-bullets"><li><strong><?php esc_html_e( 'Orientación y asesoramiento:', 'ddna-theme' ); ?></strong> <?php esc_html_e( 'guiamos sobre temas relacionados con NNyA, resolvemos dudas e inquietudes y orientamos hacia dónde concurrir según el caso.', 'ddna-theme' ); ?></li><li><strong><?php esc_html_e( 'Supervisión institucional:', 'ddna-theme' ); ?></strong> <?php esc_html_e( 'se realizan actuaciones ante entidades públicas o privadas dedicadas a la atención y albergue de niñas, niños y adolescentes.', 'ddna-theme' ); ?></li><li><strong><?php esc_html_e( 'Vías de recepción:', 'ddna-theme' ); ?></strong> <?php esc_html_e( 'telefónica, personal, nota, agenda institucional, correo, formulario web, aplicación y redes sociales.', 'ddna-theme' ); ?></li></ul></div></details>
			<details class="panel-accordion"><summary><?php esc_html_e( '¿Qué temas se pueden consultar?', 'ddna-theme' ); ?></summary><div class="panel-accordion__content"><ul class="assistance-topics" role="list"><?php foreach ( $topics as $topic ) : ?><li><?php echo esc_html( $topic ); ?></li><?php endforeach; ?></ul></div></details>
			<details class="panel-accordion"><summary><?php esc_html_e( '¿Quién asesora?', 'ddna-theme' ); ?></summary><div class="panel-accordion__content"><p><?php esc_html_e( 'Un equipo interdisciplinario de profesionales de Abogacía, Trabajo Social y Psicología brinda información y escucha activa.', 'ddna-theme' ); ?></p></div></details>
			<details class="panel-accordion"><summary><?php esc_html_e( '¿Cómo hacer uso del servicio?', 'ddna-theme' ); ?></summary><div class="panel-accordion__content"><p><?php esc_html_e( 'El servicio es completamente gratuito durante todo el año.', 'ddna-theme' ); ?></p></div></details>
			<details class="panel-accordion"><summary><?php esc_html_e( 'Contacto', 'ddna-theme' ); ?></summary><div class="panel-accordion__content contact-cards">
				<?php if ( ! empty( $details['phone'] ) ) : ?><a class="contact-card" href="<?php echo esc_url( ddna_theme_phone_uri( $details['phone'] ) ); ?>" aria-label="<?php esc_attr_e( 'Llamar a Línea Fija', 'ddna-theme' ); ?>"><img class="contact-card__icon" src="<?php echo esc_url( $contact_icons_uri . 'contact-phone.png' ); ?>" alt="" width="382" height="321" decoding="async"><span class="contact-card__content"><span class="contact-card__label"><?php esc_html_e( 'Línea Fija', 'ddna-theme' ); ?></span><span class="contact-card__value"><?php echo esc_html( $details['phone'] ); ?></span></span></a><?php endif; ?>
				<?php if ( ! empty( $details['assistance_phone'] ) ) : ?><a class="contact-card" href="<?php echo esc_url( $whatsapp_url( $details['assistance_phone'] ) ); ?>" aria-label="<?php esc_attr_e( 'Abrir WhatsApp de Línea de Asistencia', 'ddna-theme' ); ?>"><img class="contact-card__icon" src="<?php echo esc_url( $contact_icons_uri . 'contact-whatsapp.png' ); ?>" alt="" width="382" height="321" decoding="async"><span class="contact-card__content"><span class="contact-card__label"><?php echo esc_html( $details['assistance_label'] ); ?></span><span class="contact-card__value"><?php echo esc_html( $details['assistance_phone'] ); ?></span></span></a><?php endif; ?>
				<?php if ( ! empty( $details['adolescence_phone'] ) ) : ?><a class="contact-card" href="<?php echo esc_url( $whatsapp_url( $details['adolescence_phone'] ) ); ?>" aria-label="<?php esc_attr_e( 'Abrir WhatsApp de Línea de Adolescencia', 'ddna-theme' ); ?>"><img class="contact-card__icon" src="<?php echo esc_url( $contact_icons_uri . 'contact-whatsapp.png' ); ?>" alt="" width="382" height="321" decoding="async"><span class="contact-card__content"><span class="contact-card__label"><?php echo esc_html( $details['adolescence_label'] ); ?></span><span class="contact-card__value"><?php echo esc_html( $details['adolescence_phone'] ); ?></span></span></a><?php endif; ?>
				<?php if ( ! empty( $details['email'] ) ) : ?><a class="contact-card" href="mailto:<?php echo esc_attr( $details['email'] ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Enviar correo a %s', 'ddna-theme' ), $details['email'] ) ); ?>"><img class="contact-card__icon" src="<?php echo esc_url( $contact_icons_uri . 'contact-mail.png' ); ?>" alt="" width="382" height="321" decoding="async"><span class="contact-card__content"><span class="contact-card__value"><?php echo esc_html( $details['email'] ); ?></span></span></a><?php endif; ?>
				<a class="contact-card" href="mailto:<?php echo esc_attr( $case_email ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Enviar correo a %s', 'ddna-theme' ), $case_email ) ); ?>"><img class="contact-card__icon" src="<?php echo esc_url( $contact_icons_uri . 'contact-mail.png' ); ?>" alt="" width="382" height="321" decoding="async"><span class="contact-card__content"><span class="contact-card__value"><?php echo esc_html( $case_email ); ?></span></span></a>
				<?php if ( $address_lines ) : ?><div class="contact-card"><img class="contact-card__icon" src="<?php echo esc_url( $contact_icons_uri . 'contact-location.png' ); ?>" alt="" width="382" height="321" decoding="async"><span class="contact-card__content contact-card__content--address"><?php foreach ( $address_lines as $address_line ) : ?><span class="contact-card__value"><?php echo esc_html( $address_line ); ?></span><?php endforeach; ?></span></div><?php endif; ?>
			</div></details>
		</div>
	</div>
</section>
