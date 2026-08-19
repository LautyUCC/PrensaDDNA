<?php
/**
 * Configuración editorial de la portada y accesos rápidos.
 *
 * @package DDNA_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Valores predeterminados de las consultas de portada.
 *
 * @return array<string, int>
 */
function ddna_core_get_home_defaults() {
	return array(
		'programs_count'  => 9,
		'campaigns_count' => 12,
		'news_count'      => 12,
	);
}

/**
 * Obtiene la configuración saneada de la portada.
 *
 * @return array<string, int>
 */
function ddna_core_get_home_settings() {
	$settings = wp_parse_args( get_option( 'ddna_home_settings', array() ), ddna_core_get_home_defaults() );

	foreach ( $settings as $key => $value ) {
		$settings[ $key ] = min( 24, max( 1, absint( $value ) ) );
	}

	return $settings;
}

/**
 * Sanea las cantidades administrables.
 *
 * @param mixed $input Valores enviados.
 * @return array<string, int>
 */
function ddna_core_sanitize_home_settings( $input ) {
	$input   = is_array( $input ) ? $input : array();
	$output  = array();
	$defaults = ddna_core_get_home_defaults();

	foreach ( $defaults as $key => $default ) {
		$value          = isset( $input[ $key ] ) ? absint( $input[ $key ] ) : $default;
		$output[ $key ] = min( 24, max( 1, $value ) );
	}

	return $output;
}

/** Registra la opción de portada. */
function ddna_core_register_home_settings() {
	register_setting(
		'ddna_home_group',
		'ddna_home_settings',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'ddna_core_sanitize_home_settings',
			'default'           => ddna_core_get_home_defaults(),
		)
	);
}
add_action( 'admin_init', 'ddna_core_register_home_settings' );

/** Añade una pantalla breve bajo Apariencia. */
function ddna_core_add_home_settings_page() {
	add_theme_page(
		__( 'Portada DDNA', 'ddna-core' ),
		__( 'Portada DDNA', 'ddna-core' ),
		'edit_theme_options',
		'ddna-home',
		'ddna_core_render_home_settings_page'
	);
}
add_action( 'admin_menu', 'ddna_core_add_home_settings_page' );

/** Renderiza la configuración de cantidades. */
function ddna_core_render_home_settings_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$settings = ddna_core_get_home_settings();
	$fields   = array(
		'programs_count'  => __( 'Programas disponibles en el carrusel', 'ddna-core' ),
		'campaigns_count' => __( 'Campañas disponibles en el carrusel', 'ddna-core' ),
		'news_count'      => __( 'Novedades disponibles en el carrusel', 'ddna-core' ),
	);
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Portada DDNA', 'ddna-core' ); ?></h1>
		<p><?php esc_html_e( 'Define cuántos contenidos carga cada carrusel. La cantidad visible simultáneamente se adapta al ancho de pantalla.', 'ddna-core' ); ?></p>
		<form action="options.php" method="post">
			<?php settings_fields( 'ddna_home_group' ); ?>
			<table class="form-table" role="presentation"><tbody>
				<?php foreach ( $fields as $key => $label ) : ?>
					<tr><th scope="row"><label for="ddna-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label></th><td><input id="ddna-<?php echo esc_attr( $key ); ?>" name="ddna_home_settings[<?php echo esc_attr( $key ); ?>]" type="number" min="1" max="24" value="<?php echo esc_attr( $settings[ $key ] ); ?>" class="small-text"></td></tr>
				<?php endforeach; ?>
			</tbody></table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

/**
 * Añade un selector de icono a los elementos de menú.
 *
 * @param int $item_id ID del elemento.
 */
function ddna_core_render_quick_access_icon_field( $item_id ) {
	$value = get_post_meta( $item_id, '_ddna_quick_access_icon', true );
	$icons = array(
		''          => __( 'Automático / icono genérico', 'ddna-core' ),
		'consultas' => __( 'Asesoramiento y consultas', 'ddna-core' ),
		'talleres'  => __( 'Talleres interactivos', 'ddna-core' ),
		'datos'     => __( 'Datos', 'ddna-core' ),
		'recursos'  => __( 'Recursos didácticos', 'ddna-core' ),
		'subsede'   => __( 'Subsedes', 'ddna-core' ),
		'mapeo'     => __( 'Mapeo de instituciones', 'ddna-core' ),
	);
	?>
	<p class="description description-wide">
		<label for="ddna-quick-icon-<?php echo esc_attr( $item_id ); ?>"><?php esc_html_e( 'Icono de acceso rápido', 'ddna-core' ); ?><br>
		<select id="ddna-quick-icon-<?php echo esc_attr( $item_id ); ?>" name="ddna_quick_access_icon[<?php echo esc_attr( $item_id ); ?>]">
			<?php foreach ( $icons as $icon => $label ) : ?><option value="<?php echo esc_attr( $icon ); ?>" <?php selected( $value, $icon ); ?>><?php echo esc_html( $label ); ?></option><?php endforeach; ?>
		</select></label>
		<br><span><?php esc_html_e( 'Se utiliza cuando este elemento pertenece al menú de Accesos rápidos.', 'ddna-core' ); ?></span>
	</p>
	<?php
}
add_action( 'wp_nav_menu_item_custom_fields', 'ddna_core_render_quick_access_icon_field', 10, 1 );

/**
 * Guarda el icono elegido junto al elemento de menú.
 *
 * @param int $menu_id         ID del menú.
 * @param int $menu_item_db_id ID del elemento.
 */
function ddna_core_save_quick_access_icon_field( $menu_id, $menu_item_db_id ) {
	unset( $menu_id );
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$allowed = array( 'consultas', 'talleres', 'datos', 'recursos', 'subsede', 'mapeo' );
	$submitted_icons = isset( $_POST['ddna_quick_access_icon'] ) && is_array( $_POST['ddna_quick_access_icon'] ) ? wp_unslash( $_POST['ddna_quick_access_icon'] ) : array();
	$value           = isset( $submitted_icons[ $menu_item_db_id ] ) && is_scalar( $submitted_icons[ $menu_item_db_id ] ) ? sanitize_key( $submitted_icons[ $menu_item_db_id ] ) : '';

	if ( in_array( $value, $allowed, true ) ) {
		update_post_meta( $menu_item_db_id, '_ddna_quick_access_icon', $value );
	} else {
		delete_post_meta( $menu_item_db_id, '_ddna_quick_access_icon' );
	}
}
add_action( 'wp_update_nav_menu_item', 'ddna_core_save_quick_access_icon_field', 10, 2 );
