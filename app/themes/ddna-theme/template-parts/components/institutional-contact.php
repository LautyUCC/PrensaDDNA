<?php
/** Contacto central reutilizado por Asistencia, Contacto y footer. */
$details = function_exists( 'ddna_core_get_institutional_settings' ) ? ddna_core_get_institutional_settings() : array();
$icon_uri = get_template_directory_uri() . '/assets/icons/contact/';
$channels = array(
	array( 'label' => $details['assistance_label'] ?? '', 'value' => $details['assistance_phone'] ?? '', 'type' => 'phone', 'description' => 'Para consultas, orientación y asesoramiento sobre los derechos de niñas, niños y adolescentes.' ),
	array( 'label' => $details['adolescence_label'] ?? '', 'value' => $details['adolescence_phone'] ?? '', 'type' => 'phone', 'description' => 'Un canal de comunicación especialmente destinado a adolescentes.' ),
	array( 'label' => 'Línea fija', 'value' => $details['phone'] ?? '', 'type' => 'phone' ),
	array( 'label' => 'Correo electrónico', 'value' => $details['email'] ?? '', 'type' => 'mail' ),
	array( 'label' => 'Correo electrónico', 'value' => $details['case_email'] ?? '', 'type' => 'mail' ),
	array( 'label' => 'Atención presencial', 'value' => $details['address'] ?? '', 'type' => 'location' ),
);
?>
<div class="contact-cards">
	<?php foreach ( $channels as $channel ) : ?>
		<?php if ( ! $channel['value'] ) { continue; } $url = 'phone' === $channel['type'] ? ddna_theme_phone_uri( $channel['value'] ) : ( 'mail' === $channel['type'] ? 'mailto:' . $channel['value'] : '' ); ?>
		<div class="contact-card">
			<img class="contact-card__icon" src="<?php echo esc_url( $icon_uri . 'contact-' . $channel['type'] . '.png' ); ?>" alt="" width="382" height="321" decoding="async" loading="lazy">
			<div class="contact-card__content"><span class="contact-card__label"><?php echo esc_html( $channel['label'] ); ?></span>
			<?php if ( $url ) : ?><a class="contact-card__value" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $channel['value'] ); ?></a><?php else : ?><span class="contact-card__value"><?php echo esc_html( $channel['value'] ); ?></span><?php endif; ?>
			<?php if ( ! empty( $channel['description'] ) ) : ?><span class="contact-card__description"><?php echo esc_html( $channel['description'] ); ?></span><?php endif; ?>
			</div>
		</div>
	<?php endforeach; ?>
</div>
