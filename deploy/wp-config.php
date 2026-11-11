<?php
function required_env($name) {
    $value = getenv($name);
    if ($value === false || $value === '') { throw new RuntimeException('Missing configuration: ' . $name); }
    return $value;
}
define('DB_NAME', required_env('WORDPRESS_DB_NAME'));
define('DB_USER', required_env('WORDPRESS_DB_USER'));
define('DB_PASSWORD', required_env('WORDPRESS_DB_PASSWORD'));
define('DB_HOST', required_env('WORDPRESS_DB_HOST'));
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');
$table_prefix = required_env('WORDPRESS_TABLE_PREFIX');
foreach (['AUTH_KEY','SECURE_AUTH_KEY','LOGGED_IN_KEY','NONCE_KEY','AUTH_SALT','SECURE_AUTH_SALT','LOGGED_IN_SALT','NONCE_SALT'] as $key) { define($key, required_env('WORDPRESS_' . $key)); }
define('WP_HOME', required_env('WP_URL'));
define('WP_SITEURL', required_env('WP_URL'));
define('DDNA_OBSERVATORIO_URL', '/observatorio/');
define('WP_ENVIRONMENT_TYPE', 'production');
define('WP_DEBUG', false);
define('DISABLE_WP_CRON', true);
define('WP_DEBUG_DISPLAY', false);
define('DISALLOW_FILE_EDIT', true);
define('DISALLOW_FILE_MODS', true);
define('WP_AUTO_UPDATE_CORE', false);
define('FORCE_SSL_ADMIN', true);
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') { $_SERVER['HTTPS'] = 'on'; }
if (!defined('ABSPATH')) { define('ABSPATH', __DIR__ . '/'); }
require_once ABSPATH . 'wp-settings.php';
