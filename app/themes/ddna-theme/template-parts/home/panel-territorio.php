<?php
/** Panel Territorio: mapa interactivo de subsedes. @package DDNA_Theme */
$institutional = function_exists( 'ddna_core_get_institutional_settings' ) ? ddna_core_get_institutional_settings() : array();
$venue_defaults = array(
	array(
		'id' => 'cordoba-capital', 'name' => 'Córdoba Capital',
		'address' => $institutional['address'] ?? '', 'phone' => $institutional['phone'] ?? '', 'email' => $institutional['email'] ?? '',
		'hours' => '', 'map_url' => '', 'left' => '38.5%', 'top' => '35.2%', 'stack' => 5, 'popover_left' => '48%', 'popover_top' => '25%',
	),
	array(
		'id' => 'colonia-caroya', 'name' => 'Colonia Caroya',
		'address' => 'José Alfredo Nanini 4195', 'phone' => '3525 461180 / 351 2399757', 'email' => 'defensoria.caroya@gmail.com',
		'hours' => 'Lunes a viernes de 7:30 a 13:30 hs.', 'map_url' => 'https://maps.app.goo.gl/2R8wnViTg8zMsXXE8',
		'left' => '41%', 'top' => '29.8%', 'popover_left' => '49%', 'popover_top' => '16%',
	),
	array(
		'id' => 'cosquin', 'name' => 'Cosquín',
		'address' => 'Catamarca esq. Santa Fe', 'phone' => '3512398546', 'email' => 'subsededefensoriacosquin@gmail.com',
		'hours' => 'Lunes a viernes de 8:00 a 14:00 hs.', 'map_url' => 'https://maps.app.goo.gl/6npxL6ySjViyZu7z7',
		'left' => '32%', 'top' => '32.8%', 'popover_left' => '42%', 'popover_top' => '22%',
	),
	array(
		'id' => 'cruz-del-eje', 'name' => 'Cruz del Eje',
		'address' => 'General Paz 168', 'phone' => '3512473851', 'email' => 'defensoriacde@gmail.com',
		'hours' => "Lunes y jueves de 8:00 a 12:00 hs.\nMartes, miércoles y viernes de 15:00 a 18:00 hs.", 'map_url' => 'https://maps.app.goo.gl/E9wVDLZa2dyqLDvn6',
		'left' => '26.5%', 'top' => '23.5%', 'popover_left' => '37%', 'popover_top' => '14%',
	),
	array(
		'id' => 'bell-ville', 'name' => 'Bell Ville',
		'address' => '', 'phone' => '', 'email' => 'subsedebvddnn@gmail.com',
		'hours' => "Lunes a viernes de 8 a 14 hs.\nSolo atención online", 'map_url' => '',
		'left' => '70.5%', 'top' => '58%', 'popover_left' => '25%', 'popover_top' => '52%',
	),
	array(
		'id' => 'justiniano-posse', 'name' => 'Justiniano Posse',
		'address' => '9 de Julio y Belgrano', 'phone' => '3518006748', 'email' => 'defensoria.jposse@gmail.com',
		'hours' => 'Lunes a viernes de 8:00 a 14:00 hs.', 'map_url' => 'https://maps.app.goo.gl/o78SrNxFpaE1Cw9v8',
		'left' => '69.5%', 'top' => '51.5%', 'popover_left' => '35%', 'popover_top' => '43%',
	),
);

$venues = array_map(
	static function ( $defaults ) {
		$post = get_page_by_path( $defaults['id'], OBJECT, 'subsede' );
		if ( ! $post ) { return $defaults; }
		$meta_map = array( 'address' => '_ddna_address', 'phone' => '_ddna_phone', 'email' => '_ddna_email', 'hours' => '_ddna_hours', 'map_url' => '_ddna_map_url' );
		$defaults['name'] = get_the_title( $post );
		foreach ( $meta_map as $key => $meta_key ) {
			$value = get_post_meta( $post->ID, $meta_key, true );
			if ( $value ) { $defaults[ $key ] = $value; }
		}
		return $defaults;
	},
	$venue_defaults
);
$territory_phone_uri = static function ( $phone ) {
	$digits = preg_replace( '/\D+/', '', (string) $phone );
	if ( $digits && str_starts_with( $digits, '35' ) ) { $digits = '54' . $digits; }
	return $digits ? 'tel:+' . $digits : '';
};
$map_uri = get_template_directory_uri() . '/assets/images/territorio/mapa-cordoba.png';
?>
<section class="home-panel territory-panel" id="panel-territorio" data-home-panel="territorio" aria-labelledby="territory-title" hidden>
	<div class="container container--content">
		<div class="territory-card" data-territory-map>
			<header class="territory-card__header"><h2 class="territory-card__title" id="territory-title"><?php esc_html_e( 'Territorio', 'ddna-theme' ); ?></h2><p><?php esc_html_e( 'Nuestras subsedes y municipios MUNA', 'ddna-theme' ); ?></p></header>
			<div class="territory-map">
				<img class="territory-map__image" src="<?php echo esc_url( $map_uri ); ?>" alt="<?php esc_attr_e( 'Mapa de la provincia de Córdoba dividido por departamentos', 'ddna-theme' ); ?>" width="1024" height="1536" decoding="async">
				<?php foreach ( $venues as $venue ) : ?>
					<button class="territory-marker" type="button" style="--marker-left: <?php echo esc_attr( $venue['left'] ); ?>; --marker-top: <?php echo esc_attr( $venue['top'] ); ?>; --marker-stack: <?php echo esc_attr( $venue['stack'] ?? 3 ); ?>;" aria-label="<?php echo esc_attr( sprintf( __( 'Abrir información de la subsede %s', 'ddna-theme' ), $venue['name'] ) ); ?>" aria-expanded="false" aria-controls="territory-popover-<?php echo esc_attr( $venue['id'] ); ?>" data-territory-marker>
						<svg viewBox="0 0 48 64" aria-hidden="true" focusable="false"><path d="M24 2C11.85 2 2 11.85 2 24c0 16.5 22 38 22 38s22-21.5 22-38C46 11.85 36.15 2 24 2Z"/><circle cx="24" cy="24" r="8"/></svg>
						<span class="territory-marker__label" aria-hidden="true"><?php echo esc_html( $venue['name'] ); ?></span>
					</button>
					<aside class="territory-popover" style="--popover-left: <?php echo esc_attr( $venue['popover_left'] ); ?>; --popover-top: <?php echo esc_attr( $venue['popover_top'] ); ?>;" id="territory-popover-<?php echo esc_attr( $venue['id'] ); ?>" aria-labelledby="territory-popover-title-<?php echo esc_attr( $venue['id'] ); ?>" data-territory-popover hidden>
						<button class="territory-popover__close" type="button" aria-label="<?php echo esc_attr( sprintf( __( 'Cerrar información de la subsede %s', 'ddna-theme' ), $venue['name'] ) ); ?>" data-territory-close>×</button>
						<h3 id="territory-popover-title-<?php echo esc_attr( $venue['id'] ); ?>"><?php echo esc_html( $venue['name'] ); ?></h3>
						<dl class="territory-popover__details">
							<?php if ( $venue['address'] ) : ?><div><dt><?php esc_html_e( 'Dirección', 'ddna-theme' ); ?></dt><dd><?php echo esc_html( $venue['address'] ); ?></dd></div><?php endif; ?>
							<?php if ( $venue['phone'] ) : ?><div><dt><?php esc_html_e( 'Teléfono', 'ddna-theme' ); ?></dt><dd><?php $phones = preg_split( '/\s*\/\s*/', $venue['phone'] ); foreach ( $phones as $index => $phone ) : ?><?php if ( $index ) : ?> / <?php endif; ?><a href="<?php echo esc_url( $territory_phone_uri( $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a><?php endforeach; ?></dd></div><?php endif; ?>
							<?php if ( $venue['email'] ) : ?><div><dt><?php esc_html_e( 'Correo', 'ddna-theme' ); ?></dt><dd><a href="mailto:<?php echo esc_attr( $venue['email'] ); ?>"><?php echo esc_html( $venue['email'] ); ?></a></dd></div><?php endif; ?>
							<?php if ( $venue['hours'] ) : ?><div><dt><?php esc_html_e( 'Atención', 'ddna-theme' ); ?></dt><dd><?php echo nl2br( esc_html( $venue['hours'] ) ); ?></dd></div><?php endif; ?>
						</dl>
						<?php if ( $venue['map_url'] ) : ?><a class="territory-popover__map-link" href="<?php echo esc_url( $venue['map_url'] ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Abrir mapa', 'ddna-theme' ); ?><span class="screen-reader-text"> <?php esc_html_e( '(se abre en una pestaña nueva)', 'ddna-theme' ); ?></span></a><?php endif; ?>
					</aside>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
