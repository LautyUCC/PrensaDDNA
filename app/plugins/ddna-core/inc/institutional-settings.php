<?php
/** Central institutional contact settings. @package DDNA_Core */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function ddna_core_get_institutional_defaults() {
	return array(
		'assistance_label' => 'Línea Asistencia', 'assistance_phone' => '',
		'adolescence_label' => 'Línea Adolescencia', 'adolescence_phone' => '',
		'address' => '', 'phone' => '', 'email' => '', 'case_email' => '',
		'facebook_url' => '', 'instagram_url' => '', 'x_url' => '', 'youtube_url' => '', 'google_play_url' => '',
	);
}

function ddna_core_get_institutional_settings() {
	return wp_parse_args( get_option( 'ddna_institutional', array() ), ddna_core_get_institutional_defaults() );
}

function ddna_core_sanitize_institutional_settings( $input ) {
	$defaults = ddna_core_get_institutional_defaults();
	$output = array();
	$input = is_array( $input ) ? $input : array();
	foreach ( $defaults as $key => $default ) {
		$value = isset( $input[ $key ] ) ? $input[ $key ] : $default;
		$value = is_scalar( $value ) ? (string) $value : '';
		if ( in_array( $key, array( 'email', 'case_email' ), true ) ) { $output[ $key ] = sanitize_email( $value ); }
		elseif ( str_ends_with( $key, '_url' ) ) { $output[ $key ] = esc_url_raw( $value ); }
		else { $output[ $key ] = sanitize_text_field( $value ); }
	}
	return $output;
}

function ddna_core_register_institutional_settings() {
	register_setting( 'ddna_institutional_group', 'ddna_institutional', array( 'type' => 'array', 'sanitize_callback' => 'ddna_core_sanitize_institutional_settings', 'default' => ddna_core_get_institutional_defaults() ) );
}
add_action( 'admin_init', 'ddna_core_register_institutional_settings' );

function ddna_core_add_institutional_settings_page() {
	add_options_page( __( 'Datos institucionales DDNA', 'ddna-core' ), __( 'Datos institucionales', 'ddna-core' ), 'manage_options', 'ddna-institutional', 'ddna_core_render_institutional_settings_page' );
}
add_action( 'admin_menu', 'ddna_core_add_institutional_settings_page' );

function ddna_core_render_institutional_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) { return; }
	$values = ddna_core_get_institutional_settings();
	$fields = array(
		'assistance_label' => array( 'Etiqueta de la línea de asistencia', 'text' ), 'assistance_phone' => array( 'Teléfono de asistencia', 'text' ),
		'adolescence_label' => array( 'Etiqueta de la línea de adolescencia', 'text' ), 'adolescence_phone' => array( 'Teléfono de adolescencia', 'text' ),
		'address' => array( 'Dirección', 'text' ), 'phone' => array( 'Teléfono general', 'text' ), 'email' => array( 'Correo institucional', 'email' ), 'case_email' => array( 'Correo de asistencia', 'email' ),
		'facebook_url' => array( 'Facebook', 'url' ), 'instagram_url' => array( 'Instagram', 'url' ), 'x_url' => array( 'X', 'url' ),
		'youtube_url' => array( 'YouTube', 'url' ), 'google_play_url' => array( 'Google Play', 'url' ),
	);
	?>
	<div class="wrap"><h1><?php esc_html_e( 'Datos institucionales DDNA', 'ddna-core' ); ?></h1><p><?php esc_html_e( 'Fuente única para el footer y futuros componentes institucionales.', 'ddna-core' ); ?></p>
	<form action="options.php" method="post"><?php settings_fields( 'ddna_institutional_group' ); ?><table class="form-table" role="presentation"><tbody>
	<?php foreach ( $fields as $key => $field ) : ?><tr><th scope="row"><label for="ddna-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field[0] ); ?></label></th><td><input class="regular-text" id="ddna-<?php echo esc_attr( $key ); ?>" type="<?php echo esc_attr( $field[1] ); ?>" name="ddna_institutional[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $values[ $key ] ); ?>"></td></tr><?php endforeach; ?>
	</tbody></table><?php submit_button(); ?></form></div>
	<?php
}
