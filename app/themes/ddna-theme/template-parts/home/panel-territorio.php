<?php
/** Panel Territorio: mapa interactivo de subsedes. @package DDNA_Theme */
$institutional = function_exists( 'ddna_core_get_institutional_settings' ) ? ddna_core_get_institutional_settings() : array();
$venue_defaults = ddna_theme_territory_venues( $institutional );

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
$muna_logo_uri = get_template_directory_uri() . '/assets/images/territorio/muna-isologotipo-02.png';
?>
<section class="home-panel territory-panel" id="panel-territorio" data-home-panel="territorio" aria-labelledby="territory-title" hidden>
	<div class="container container--content">
		<div class="territory-card" data-territory-map>
			<header class="territory-card__header"><h2 class="territory-card__title" id="territory-title"><?php esc_html_e( 'Territorio', 'ddna-theme' ); ?></h2><p><?php esc_html_e( 'Nuestras subsedes y municipios MUNA', 'ddna-theme' ); ?></p></header>
			<div class="territory-map">
				<div class="territory-map__canvas">
				<img class="territory-map__image" src="<?php echo esc_url( $map_uri ); ?>" alt="<?php esc_attr_e( 'Mapa de la provincia de Córdoba dividido por departamentos', 'ddna-theme' ); ?>" width="1024" height="1536" decoding="async">
				<?php foreach ( $venues as $venue ) : ?>
					<button class="territory-marker" type="button" style="--marker-left: <?php echo esc_attr( $venue['left'] ); ?>; --marker-top: <?php echo esc_attr( $venue['top'] ); ?>; --marker-stack: <?php echo esc_attr( $venue['stack'] ?? 3 ); ?>;" aria-label="<?php echo esc_attr( sprintf( __( 'Abrir información de la subsede %s', 'ddna-theme' ), $venue['name'] ) ); ?>" aria-expanded="false" aria-controls="territory-popover-<?php echo esc_attr( $venue['id'] ); ?>" data-territory-marker>
						<svg viewBox="0 0 48 64" aria-hidden="true" focusable="false"><path d="M24 2C11.85 2 2 11.85 2 24c0 16.5 22 38 22 38s22-21.5 22-38C46 11.85 36.15 2 24 2Z"/><circle cx="24" cy="24" r="8"/></svg>
						<span class="territory-marker__label" aria-hidden="true"><?php echo esc_html( $venue['name'] ); ?></span>
					</button>
				<?php endforeach; ?>
				</div>
				<?php foreach ( $venues as $venue ) : ?>
					<aside class="territory-popover<?php echo ! empty( $venue['muna'] ) ? ' territory-popover--muna' : ''; ?>" style="--popover-left: <?php echo esc_attr( $venue['popover_left'] ); ?>; --popover-top: <?php echo esc_attr( $venue['popover_top'] ); ?>;" id="territory-popover-<?php echo esc_attr( $venue['id'] ); ?>" aria-labelledby="territory-popover-title-<?php echo esc_attr( $venue['id'] ); ?>" data-territory-popover hidden>
						<button class="territory-popover__close" type="button" aria-label="<?php echo esc_attr( sprintf( __( 'Cerrar información de la subsede %s', 'ddna-theme' ), $venue['name'] ) ); ?>" data-territory-close>×</button>
						<?php if ( ! empty( $venue['muna'] ) ) : ?><img class="territory-popover__muna-logo" src="<?php echo esc_url( $muna_logo_uri ); ?>" alt="<?php esc_attr_e( 'MUNA — Municipio Unido por la Niñez y la Adolescencia', 'ddna-theme' ); ?>" width="3508" height="2233" decoding="async"><?php endif; ?>
						<h3 id="territory-popover-title-<?php echo esc_attr( $venue['id'] ); ?>"><?php echo esc_html( $venue['name'] ); ?></h3>
						<dl class="territory-popover__details">
							<?php if ( $venue['department'] ) : ?><div><dt><?php esc_html_e( 'Departamento', 'ddna-theme' ); ?></dt><dd><?php echo esc_html( $venue['department'] ); ?></dd></div><?php endif; ?>
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
		<section class="home-panel__content territory-muna" aria-labelledby="territory-muna-title">
			<h2 class="home-panel__section-title" id="territory-muna-title">Municipios MUNA</h2>
			<?php echo ddna_theme_page_link( 'cooperacion-internacional-interinstitucional', 'Conocer los 20 municipios y sus cohortes' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</section>
	</div>
</section>
