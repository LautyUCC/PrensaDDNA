<?php
/** Destinos institucionales pendientes, independientes de la presentación. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function ddna_core_editorial_link_fields() {
	return array(
		'institutional_dossier' => 'Dossier Institucional',
		'va_con_vos_dossier' => 'Dossier Va con Vos',
		'entre_pantallas_dossier' => 'Dossier Entre Pantallas',
		'desarrollo_integral_dossier' => 'Dossier Desarrollo Integral',
		'graphic_materials' => 'Carpeta Materiales Gráficos Descargables',
		'statement_health_2025' => 'Comunicado 2025 — emergencia pediátrica',
		'statement_disability_2025' => 'Comunicado 2025 — emergencia en discapacidad',
		'statement_juvenile_2022' => 'Comunicado 2022 — responsabilidad penal juvenil',
	);
}

function ddna_core_sanitize_editorial_links( $input ) {
	$input = is_array( $input ) ? $input : array();
	$result = array();
	foreach ( ddna_core_editorial_link_fields() as $key => $label ) {
		$result[ $key ] = isset( $input[ $key ] ) && is_scalar( $input[ $key ] ) ? esc_url_raw( $input[ $key ], array( 'https', 'http' ) ) : '';
	}
	return $result;
}

add_action( 'admin_init', static function () {
	register_setting( 'ddna_editorial_links_group', 'ddna_editorial_links', array( 'type' => 'array', 'sanitize_callback' => 'ddna_core_sanitize_editorial_links', 'default' => array() ) );
} );
add_action( 'admin_menu', static function () {
	add_options_page( 'Recursos institucionales DDNA', 'Recursos institucionales', 'manage_options', 'ddna-editorial-links', 'ddna_core_render_editorial_links' );
} );

function ddna_core_render_editorial_links() {
	if ( ! current_user_can( 'manage_options' ) ) { return; }
	$values = get_option( 'ddna_editorial_links', array() );
	?>
	<div class="wrap"><h1>Recursos institucionales DDNA</h1><p>Completar únicamente con destinos aprobados. Vacío significa que el recurso todavía no está disponible.</p>
	<form action="options.php" method="post"><?php settings_fields( 'ddna_editorial_links_group' ); ?><table class="form-table"><tbody>
	<?php foreach ( ddna_core_editorial_link_fields() as $key => $label ) : ?>
	<tr><th><label for="ddna-link-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label></th><td><input class="regular-text" type="url" id="ddna-link-<?php echo esc_attr( $key ); ?>" name="ddna_editorial_links[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $values[ $key ] ?? '' ); ?>"></td></tr>
	<?php endforeach; ?></tbody></table><?php submit_button(); ?></form></div>
	<?php
}
