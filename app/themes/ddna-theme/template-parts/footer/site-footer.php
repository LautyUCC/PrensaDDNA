<?php
/**
 * Global institutional footer.
 *
 * @package DDNA_Theme
 */

$details          = function_exists( 'ddna_core_get_institutional_settings' ) ? ddna_core_get_institutional_settings() : array();
$footer_logo      = get_template_directory_uri() . '/assets/images/logos/ddna-footer-2026.png';
$whatsapp_icon    = get_template_directory_uri() . '/assets/icons/contact/contact-whatsapp.png';
$assistance_phone = $details['assistance_phone'] ?? '';
$whatsapp_number  = preg_replace( '/\D+/', '', $assistance_phone );

if ( $whatsapp_number && ! str_starts_with( $whatsapp_number, '54' ) ) {
	$whatsapp_number = '54' . $whatsapp_number;
}

$socials = array(
	'instagram' => array(
		'label' => 'Instagram',
		'url'   => $details['instagram_url'] ?? '',
	),
	'facebook'  => array(
		'label' => 'Facebook',
		'url'   => $details['facebook_url'] ?? '',
	),
	'whatsapp'  => array(
		'label' => 'WhatsApp',
		'url'   => $whatsapp_number ? 'https://wa.me/' . $whatsapp_number : '',
	),
);

$lines = array(
	array( 'Línea Asistencia', $assistance_phone ),
	array( 'Línea Adolescencia', $details['adolescence_phone'] ?? '' ),
);

$address_parts = array_values(
	array_filter(
		array_map( 'trim', explode( ',', $details['address'] ?? '' ) )
	)
);

if ( isset( $address_parts[1] ) && 'Nueva Córdoba' === $address_parts[1] ) {
	$address_parts[1] = 'B° Nueva Córdoba';
}
?>
<footer class="site-footer" id="site-footer">
	<div class="container container--wide site-footer__inner">
		<div class="site-footer__identity">
			<a class="site-footer__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<img class="site-footer__logo" src="<?php echo esc_url( $footer_logo ); ?>" alt="<?php esc_attr_e( 'Defensoría de los Derechos de Niñas, Niños y Adolescentes', 'ddna-theme' ); ?>" width="2197" height="1498" loading="lazy" decoding="async">
			</a>
		</div>

		<div class="site-footer__lines" role="group" aria-label="<?php esc_attr_e( 'Líneas de atención', 'ddna-theme' ); ?>">
			<?php foreach ( $lines as $line ) : ?>
				<?php if ( $line[1] ) : ?>
					<a class="assistance-line" href="<?php echo esc_url( ddna_theme_phone_uri( $line[1] ) ); ?>">
						<img class="assistance-line__icon" src="<?php echo esc_url( $whatsapp_icon ); ?>" alt="" width="382" height="321" loading="lazy" decoding="async">
						<span class="assistance-line__content">
							<strong><?php echo esc_html( $line[0] ); ?></strong>
							<small><?php echo esc_html( $line[1] ); ?></small>
						</span>
					</a>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>

		<div class="site-footer__contact">
			<p class="site-footer__follow"><?php esc_html_e( 'Seguinos en', 'ddna-theme' ); ?></p>
			<ul class="social-links" role="list">
				<?php foreach ( $socials as $network => $social ) : ?>
					<li>
						<?php if ( $social['url'] ) : ?>
							<a href="<?php echo esc_url( $social['url'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $social['label'] ); ?>">
								<?php if ( 'whatsapp' === $network ) : ?>
									<img class="social-icon social-icon--whatsapp" src="<?php echo esc_url( $whatsapp_icon ); ?>" alt="" width="382" height="321" loading="lazy" decoding="async">
								<?php else : ?>
									<?php get_template_part( 'template-parts/components/social-icon', null, array( 'network' => $network ) ); ?>
								<?php endif; ?>
							</a>
						<?php else : ?>
							<span class="social-links__unavailable" role="img" aria-label="<?php echo esc_attr( $social['label'] ); ?>">
								<?php if ( 'whatsapp' === $network ) : ?>
									<img class="social-icon social-icon--whatsapp" src="<?php echo esc_url( $whatsapp_icon ); ?>" alt="" width="382" height="321" loading="lazy" decoding="async">
								<?php else : ?>
									<?php get_template_part( 'template-parts/components/social-icon', null, array( 'network' => $network ) ); ?>
								<?php endif; ?>
							</span>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>

			<address class="site-footer__address">
				<?php foreach ( $address_parts as $address_line ) : ?>
					<span><?php echo esc_html( rtrim( $address_line, '. ' ) ); ?></span>
				<?php endforeach; ?>
				<?php if ( ! empty( $details['email'] ) ) : ?>
					<a href="mailto:<?php echo esc_attr( $details['email'] ); ?>"><?php echo esc_html( $details['email'] ); ?></a>
				<?php endif; ?>
				<?php if ( ! empty( $details['phone'] ) ) : ?>
					<a href="<?php echo esc_url( ddna_theme_phone_uri( $details['phone'] ) ); ?>">+54 <?php echo esc_html( $details['phone'] ); ?></a>
				<?php endif; ?>
			</address>
		</div>

		<p class="site-footer__legal">&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php esc_html_e( 'Defensoría de los Derechos de Niñas, Niños y Adolescentes - Provincia de Córdoba', 'ddna-theme' ); ?></p>
	</div>
</footer>
