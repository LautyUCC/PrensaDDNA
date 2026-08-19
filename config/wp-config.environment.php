<?php
/**
 * Configuración portable para staging/production.
 *
 * Incluir desde wp-config.php antes de `require_once ABSPATH . 'wp-settings.php';`.
 * Los secretos deben inyectarse como variables de entorno; este archivo no contiene valores reales.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Lee una variable de entorno sin confundir el string "0" con ausencia.
 *
 * @param string $name    Nombre.
 * @param mixed  $default Fallback.
 * @return mixed
 */
function ddna_config_env( $name, $default = '' ) {
	$value = getenv( $name );

	return false === $value ? $default : $value;
}

/**
 * Convierte una variable de entorno a booleano.
 *
 * @param string $name    Nombre.
 * @param bool   $default Fallback.
 * @return bool
 */
function ddna_config_env_bool( $name, $default = false ) {
	$value = ddna_config_env( $name, $default ? '1' : '0' );

	return filter_var( $value, FILTER_VALIDATE_BOOLEAN );
}

$ddna_environment = strtolower( preg_replace( '/[^a-z0-9_-]/i', '', (string) ddna_config_env( 'WP_ENVIRONMENT_TYPE', 'production' ) ) );
if ( ! in_array( $ddna_environment, array( 'local', 'development', 'staging', 'production' ), true ) ) {
	$ddna_environment = 'production';
}

defined( 'WP_ENVIRONMENT_TYPE' ) || define( 'WP_ENVIRONMENT_TYPE', $ddna_environment );

$ddna_site_url = rtrim( filter_var( (string) ddna_config_env( 'WP_URL', '' ), FILTER_VALIDATE_URL ) ?: '', '/' );
if ( $ddna_site_url ) {
	defined( 'WP_HOME' ) || define( 'WP_HOME', $ddna_site_url );
	defined( 'WP_SITEURL' ) || define( 'WP_SITEURL', $ddna_site_url );
}

defined( 'DB_NAME' ) || define( 'DB_NAME', (string) ddna_config_env( 'DDNA_DB_NAME' ) );
defined( 'DB_USER' ) || define( 'DB_USER', (string) ddna_config_env( 'DDNA_DB_USER' ) );
defined( 'DB_PASSWORD' ) || define( 'DB_PASSWORD', (string) ddna_config_env( 'DDNA_DB_PASSWORD' ) );
defined( 'DB_HOST' ) || define( 'DB_HOST', (string) ddna_config_env( 'DDNA_DB_HOST', 'localhost' ) );
defined( 'DB_CHARSET' ) || define( 'DB_CHARSET', 'utf8mb4' );
defined( 'DB_COLLATE' ) || define( 'DB_COLLATE', '' );

if ( empty( $table_prefix ) ) {
	$ddna_table_prefix = preg_replace( '/[^A-Za-z0-9_]/', '', (string) ddna_config_env( 'WP_TABLE_PREFIX', 'wp_' ) );
	$table_prefix      = $ddna_table_prefix ? $ddna_table_prefix : 'wp_';
}

$ddna_debug = ddna_config_env_bool( 'WP_DEBUG', false );
defined( 'WP_DEBUG' ) || define( 'WP_DEBUG', $ddna_debug );
defined( 'WP_DEBUG_DISPLAY' ) || define( 'WP_DEBUG_DISPLAY', false );
defined( 'WP_DEBUG_LOG' ) || define( 'WP_DEBUG_LOG', ddna_config_env_bool( 'WP_DEBUG_LOG', false ) );
@ini_set( 'display_errors', '0' );

defined( 'FORCE_SSL_ADMIN' ) || define( 'FORCE_SSL_ADMIN', ddna_config_env_bool( 'WP_FORCE_HTTPS', true ) );
defined( 'DISABLE_WP_CRON' ) || define( 'DISABLE_WP_CRON', ddna_config_env_bool( 'WP_DISABLE_CRON', true ) );
defined( 'DISALLOW_FILE_EDIT' ) || define( 'DISALLOW_FILE_EDIT', true );
defined( 'WP_AUTO_UPDATE_CORE' ) || define( 'WP_AUTO_UPDATE_CORE', 'minor' );

if ( ddna_config_env_bool( 'WP_FORCE_HTTPS', true ) && isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && 'https' === strtolower( (string) $_SERVER['HTTP_X_FORWARDED_PROTO'] ) ) {
	$_SERVER['HTTPS'] = 'on';
}

$ddna_key_map = array(
	'AUTH_KEY'         => 'DDNA_AUTH_KEY',
	'SECURE_AUTH_KEY'  => 'DDNA_SECURE_AUTH_KEY',
	'LOGGED_IN_KEY'    => 'DDNA_LOGGED_IN_KEY',
	'NONCE_KEY'        => 'DDNA_NONCE_KEY',
	'AUTH_SALT'        => 'DDNA_AUTH_SALT',
	'SECURE_AUTH_SALT' => 'DDNA_SECURE_AUTH_SALT',
	'LOGGED_IN_SALT'   => 'DDNA_LOGGED_IN_SALT',
	'NONCE_SALT'       => 'DDNA_NONCE_SALT',
);

foreach ( $ddna_key_map as $ddna_constant => $ddna_variable ) {
	$ddna_secret = (string) ddna_config_env( $ddna_variable, '' );
	if ( ! defined( $ddna_constant ) && $ddna_secret ) {
		define( $ddna_constant, $ddna_secret );
	}
}

unset( $ddna_environment, $ddna_site_url, $ddna_debug, $ddna_table_prefix, $ddna_key_map, $ddna_constant, $ddna_variable, $ddna_secret );
