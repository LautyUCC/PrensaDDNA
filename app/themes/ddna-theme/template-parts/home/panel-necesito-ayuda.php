<?php
/** Final September 2026 assistance content. @package DDNA_Theme */

$details           = function_exists( 'ddna_core_get_institutional_settings' ) ? ddna_core_get_institutional_settings() : array();
$contact_icons_uri = get_template_directory_uri() . '/assets/icons/contact/';
$address           = $details['address'] ?? '';
$whatsapp_url      = static function ( $phone ) {
	$digits = preg_replace( '/\D+/', '', (string) $phone );
	if ( str_starts_with( $digits, '351' ) ) {
		$digits = '54' . $digits;
	}
	return $digits ? 'https://wa.me/' . $digits : '';
};
$topics = array(
	'Violencia, maltrato y abuso' => array( 'Violencia física, psicológica o verbal.', 'Maltrato hacia niñas, niños o adolescentes.', 'Situaciones de abuso sexual o sospecha de abuso.', 'Otras situaciones de vulneración de derechos.' ),
	'Convivencia y vínculos' => array( 'Bullying o acoso entre pares.', 'Cyberbullying y conflictos en entornos digitales.', 'Conflictos familiares que afecten a niñas, niños o adolescentes.', 'Situaciones vinculadas con responsabilidad parental, régimen de comunicación, tutela o delegación de la responsabilidad parental.' ),
	'Internet y redes sociales' => array( 'Grooming.', 'Acoso o violencia en redes sociales.', 'Situaciones que afecten la privacidad, la identidad o la seguridad digital.', 'Otras vulneraciones de derechos en internet y entornos digitales.' ),
	'Educación' => array( 'Dificultades para ingresar o permanecer en la escuela.', 'Problemas para acceder a recursos o apoyos educativos.', 'Situaciones que afecten el derecho a la educación.' ),
	'Salud' => array( 'Dificultades para acceder a atención médica.', 'Problemas para acceder a tratamientos, prestaciones o insumos.', 'Situaciones relacionadas con obras sociales o prepagas que afecten el acceso a prestaciones de niñas, niños o adolescentes.' ),
	'Identidad y documentación' => array( 'Dificultades relacionadas con el DNI.', 'Situaciones vinculadas con el derecho a la identidad.', 'Problemas para acceder a documentación de niñas, niños y adolescentes.' ),
	'Consumos problemáticos' => array( 'Situaciones relacionadas con el consumo de sustancias.', 'Dificultades para acceder a orientación, atención o acompañamiento.' ),
);
?>
<section class="home-panel" id="panel-necesito-ayuda" data-home-panel="necesito-ayuda" aria-labelledby="assistance-title" hidden>
	<div class="container container--content">
		<div class="home-panel__content">
			<h2 class="panel-lead-title" id="assistance-title">Asistencia, orientación y consultas</h2>
			<p>¿Tenés una duda, conoces o estás atravesando una situación que afecta a una niña, niño o adolescente o no sabés dónde recurrir?</p>
			<p>En la Defensoría podemos escucharte, asesorarte y orientarte sobre los pasos a seguir.</p>
			<p>Brindamos atención gratuita, orientación y asesoramiento ante situaciones que involucren los derechos de niñas, niños y adolescentes.</p>
			<p>No es necesario que sepas qué organismo debe intervenir. Podés comunicarte con nosotros y te orientaremos.</p>

			<details class="panel-accordion" open><summary>¿En qué podemos ayudarte?</summary><div class="panel-accordion__content"><p><strong>Podés consultar con la Defensoría ante situaciones relacionadas con:</strong></p><div class="assistance-topics" role="list"><?php foreach ( $topics as $title => $items ) : ?><section role="listitem"><h3><?php echo esc_html( $title ); ?></h3><ul class="panel-bullets"><?php foreach ( $items as $item ) : ?><li><?php echo esc_html( $item ); ?></li><?php endforeach; ?></ul></section><?php endforeach; ?></div></div></details>
			<details class="panel-accordion"><summary>Derecho a ser escuchado</summary><div class="panel-accordion__content"><p><strong>¿Sos niña, niño o adolescente y necesitás hablar con alguien?</strong></p><p>Tenés derecho a ser escuchado y a que tu opinión sea tenida en cuenta en las situaciones que te afectan.</p><p>Podés comunicarte directamente con la Defensoría al: <strong><?php echo esc_html( $details['adolescence_phone'] ?? '' ); ?> — Línea Adolescencia</strong></p></div></details>
			<details class="panel-accordion"><summary>¿Quién te asesora?</summary><div class="panel-accordion__content"><p>Las consultas son recibidas por un equipo interdisciplinario de profesionales, integrado por abogados, trabajadores sociales y psicólogos.</p><p>El equipo brinda escucha, orientación y asesoramiento, teniendo en cuenta las características y necesidades de cada situación.</p><p>Cuando corresponde, se realiza el seguimiento y la articulación con otros organismos o instituciones.</p><p>La atención es gratuita.</p></div></details>
			<details class="panel-accordion"><summary>¿Cómo podés comunicarte?</summary><div class="panel-accordion__content"><p><strong>Elegí el canal que te resulte más cómodo:</strong></p><div class="contact-cards">
				<?php if ( ! empty( $details['assistance_phone'] ) ) : ?><a class="contact-card" href="<?php echo esc_url( $whatsapp_url( $details['assistance_phone'] ) ); ?>" aria-label="<?php esc_attr_e( 'Abrir WhatsApp de Línea de Asistencia', 'ddna-theme' ); ?>"><img class="contact-card__icon" src="<?php echo esc_url( $contact_icons_uri . 'contact-whatsapp.png' ); ?>" alt="" width="382" height="321"><span class="contact-card__content"><span class="contact-card__label">Línea de Asistencia</span><span class="contact-card__value"><?php echo esc_html( $details['assistance_phone'] ); ?></span><span>Para consultas, orientación y asesoramiento sobre los derechos de niñas, niños y adolescentes.</span></span></a><?php endif; ?>
				<?php if ( ! empty( $details['adolescence_phone'] ) ) : ?><a class="contact-card" href="<?php echo esc_url( $whatsapp_url( $details['adolescence_phone'] ) ); ?>" aria-label="<?php esc_attr_e( 'Abrir WhatsApp de Línea Adolescencia', 'ddna-theme' ); ?>"><img class="contact-card__icon" src="<?php echo esc_url( $contact_icons_uri . 'contact-whatsapp.png' ); ?>" alt="" width="382" height="321"><span class="contact-card__content"><span class="contact-card__label">Línea Adolescencia</span><span class="contact-card__value"><?php echo esc_html( $details['adolescence_phone'] ); ?></span><span>Un canal de comunicación especialmente destinado a adolescentes.</span></span></a><?php endif; ?>
				<?php if ( ! empty( $details['phone'] ) ) : ?><a class="contact-card" href="<?php echo esc_url( ddna_theme_phone_uri( $details['phone'] ) ); ?>" aria-label="<?php esc_attr_e( 'Llamar a Línea fija', 'ddna-theme' ); ?>"><img class="contact-card__icon" src="<?php echo esc_url( $contact_icons_uri . 'contact-phone.png' ); ?>" alt="" width="382" height="321"><span class="contact-card__content"><span class="contact-card__label">Línea fija</span><span class="contact-card__value"><?php echo esc_html( $details['phone'] ); ?></span></span></a><?php endif; ?>
				<?php foreach ( array_filter( array( $details['email'] ?? '', $details['case_email'] ?? '' ) ) as $email ) : ?><a class="contact-card" href="mailto:<?php echo esc_attr( $email ); ?>"><img class="contact-card__icon" src="<?php echo esc_url( $contact_icons_uri . 'contact-mail.png' ); ?>" alt="" width="382" height="321"><span class="contact-card__content"><span class="contact-card__label">Correo electrónico</span><span class="contact-card__value"><?php echo esc_html( $email ); ?></span></span></a><?php endforeach; ?>
				<?php if ( $address ) : ?><div class="contact-card"><img class="contact-card__icon" src="<?php echo esc_url( $contact_icons_uri . 'contact-location.png' ); ?>" alt="" width="382" height="321"><span class="contact-card__content"><span class="contact-card__label">Atención presencial</span><span class="contact-card__value"><?php echo esc_html( $address ); ?></span><span>También contamos con subsedes en el interior de la provincia.</span></span></div><?php endif; ?>
			</div></div></details>
			<details class="panel-accordion"><summary>¿No sabés si tu consulta corresponde a la Defensoría?</summary><div class="panel-accordion__content"><p>No te preocupes. Comunicate igualmente.</p><p>Podemos escucharte, orientarte y, si corresponde, indicarte a qué organismo o institución podés recurrir.</p></div></details>
		</div>
	</div>
</section>
