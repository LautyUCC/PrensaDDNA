<?php
/**
 * Plugin Name: DDNA Core
 * Description: Modelo de contenido y lógica institucional del sitio DDNA Córdoba.
 * Version: 0.1.0
 * Requires at least: 6.6
 * Requires PHP: 8.1
 * Author: Equipo DDNA
 * Text Domain: ddna-core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DDNA_CORE_VERSION', '0.1.0' );
define( 'DDNA_CORE_FILE', __FILE__ );
define( 'DDNA_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'DDNA_CORE_URL', plugin_dir_url( __FILE__ ) );

require_once DDNA_CORE_PATH . 'inc/content-types.php';
require_once DDNA_CORE_PATH . 'inc/taxonomies.php';
require_once DDNA_CORE_PATH . 'inc/meta-fields.php';
require_once DDNA_CORE_PATH . 'inc/admin.php';
require_once DDNA_CORE_PATH . 'inc/institutional-settings.php';
require_once DDNA_CORE_PATH . 'inc/home-management.php';

/**
 * Prepara reglas y términos básicos al activar el plugin.
 */
function ddna_core_activate() {
	ddna_core_register_content_types();
	ddna_core_register_taxonomies();
	ddna_core_seed_terms();

	if ( ! term_exists( 'Novedades', 'category' ) ) {
		wp_insert_term( 'Novedades', 'category', array( 'slug' => 'novedades' ) );
	}

	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'ddna_core_activate' );

/**
 * Crea vocabularios editoriales iniciales sin modificar términos existentes.
 */
function ddna_core_seed_terms() {
	$terms = array(
		'tipo_documento' => array( 'Informe anual', 'Normativa', 'Guía', 'Comunicado', 'Pronunciamiento', 'Convenio', 'Folleto' ),
		'tipo_capacitacion' => array( 'Diplomatura', 'Seminario', 'Taller', 'Jornada', 'Curso', 'Conversatorio' ),
		'ddna_publico' => array( 'Niñas, niños y adolescentes', 'Familias y personas cuidadoras', 'Docentes e instituciones educativas', 'Profesionales', 'Medios de comunicación', 'Municipios y comunas', 'Público general' ),
		'ddna_tema' => array( 'Acceso a derechos', 'Participación', 'Mundo digital', 'Crianza', 'Violencias', 'Educación', 'Salud', 'Comunicación' ),
	);

	foreach ( $terms as $taxonomy => $names ) {
		foreach ( $names as $name ) {
			if ( ! term_exists( $name, $taxonomy ) ) {
				wp_insert_term( $name, $taxonomy );
			}
		}
	}
}

/**
 * Limpia las reglas de enlaces permanentes al desactivar.
 */
function ddna_core_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'ddna_core_deactivate' );
