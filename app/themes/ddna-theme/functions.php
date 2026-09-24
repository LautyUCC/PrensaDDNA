<?php
/**
 * Bootstrap del tema DDNA.
 *
 * @package DDNA_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_template_directory() . '/inc/theme-setup.php';
require_once get_template_directory() . '/inc/enqueue.php';
require_once get_template_directory() . '/inc/template-tags.php';
require_once get_template_directory() . '/inc/editorial-content.php';
require_once get_template_directory() . '/inc/territory-venues.php';
require_once get_template_directory() . '/inc/navigation.php';
require_once get_template_directory() . '/inc/seo.php';
