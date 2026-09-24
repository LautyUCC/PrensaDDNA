<?php
/**
 * Registro de estilos y scripts públicos.
 *
 * @package DDNA_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Carga los recursos del tema.
 */
function ddna_theme_enqueue_assets() {
	$theme_version = wp_get_theme()->get( 'Version' );
	$theme_path    = get_template_directory();
	$theme_uri     = get_template_directory_uri();

	$styles = array(
		'ddna-theme-tokens'        => 'settings/tokens.css',
		'ddna-theme-fonts'         => 'base/fonts.css',
		'ddna-theme-reset'         => 'base/reset.css',
		'ddna-theme-typography'    => 'base/typography.css',
		'ddna-theme-elements'      => 'base/elements.css',
		'ddna-theme-containers'    => 'layout/containers.css',
		'ddna-theme-sections'      => 'layout/sections.css',
		'ddna-theme-buttons'       => 'components/buttons.css',
		'ddna-theme-header'        => 'components/header.css',
		'ddna-theme-navigation'    => 'components/navigation.css',
		'ddna-theme-hero'          => 'components/hero.css',
		'ddna-theme-carousel'      => 'components/carousel.css',
		'ddna-theme-section-frame' => 'components/section-frame.css',
		'ddna-theme-quick-access'  => 'components/quick-access.css',
		'ddna-theme-programs'      => 'components/home-programs.css',
		'ddna-theme-campaigns'     => 'components/home-campaigns.css',
		'ddna-theme-news'          => 'components/home-news.css',
		'ddna-theme-footer'        => 'components/footer.css',
		'ddna-theme-home-panels'   => 'components/home-panels.css',
		'ddna-theme-knowledge'     => 'components/knowledge-panel.css',
		'ddna-theme-territory'     => 'components/territory.css',
		'ddna-theme-institutional' => 'components/institutional-navigation.css',
		'ddna-theme-accessibility' => 'utilities/accessibility.css',
	);

	if ( is_page( 'novedades' ) ) {
		$styles['ddna-theme-news-archive'] = 'components/news-archive.css';
	}

	if ( is_page( 'informes-anuales' ) ) {
		$styles['ddna-theme-annual-reports'] = 'components/annual-reports.css';
	}

	if ( is_page( array( 'hay-otra-forma-prevencion-maltrato', 'hay-otra-forma-prevencion-bullying' ) ) ) {
		$styles['ddna-theme-hay-otra-forma'] = 'components/hay-otra-forma.css';
	}

	if ( ! is_front_page() ) {
		$styles['ddna-theme-cards']      = 'components/cards.css';
		$styles['ddna-theme-site-shell'] = 'components/site-shell.css';
		$styles['ddna-theme-main']       = 'main.css';
	}

	foreach ( $styles as $handle => $relative_path ) {
		$file_path = $theme_path . '/assets/css/' . $relative_path;
		$version   = file_exists( $file_path ) ? (string) filemtime( $file_path ) : $theme_version;

		wp_enqueue_style(
			$handle,
			$theme_uri . '/assets/css/' . $relative_path,
			'ddna-theme-tokens' === $handle ? array() : array( 'ddna-theme-tokens' ),
			$version
		);
	}

	wp_enqueue_script(
		'ddna-theme-navigation',
		$theme_uri . '/assets/js/navigation.js',
		array(),
		$theme_version,
		true
	);
	wp_script_add_data( 'ddna-theme-navigation', 'strategy', 'defer' );

	if ( is_front_page() ) {
		wp_enqueue_script(
			'ddna-theme-hero-video',
			$theme_uri . '/assets/js/hero-video.js',
			array(),
			file_exists( $theme_path . '/assets/js/hero-video.js' ) ? (string) filemtime( $theme_path . '/assets/js/hero-video.js' ) : $theme_version,
			true
		);
		wp_script_add_data( 'ddna-theme-hero-video', 'strategy', 'defer' );

		wp_enqueue_script(
			'ddna-theme-carousel',
			$theme_uri . '/assets/js/carousel.js',
			array(),
			file_exists( $theme_path . '/assets/js/carousel.js' ) ? (string) filemtime( $theme_path . '/assets/js/carousel.js' ) : $theme_version,
			true
		);
		wp_script_add_data( 'ddna-theme-carousel', 'strategy', 'defer' );

		wp_enqueue_script(
			'ddna-theme-home-panels',
			$theme_uri . '/assets/js/home-panels.js',
			array(),
			file_exists( $theme_path . '/assets/js/home-panels.js' ) ? (string) filemtime( $theme_path . '/assets/js/home-panels.js' ) : $theme_version,
			true
		);
		wp_script_add_data( 'ddna-theme-home-panels', 'strategy', 'defer' );

		wp_enqueue_script(
			'ddna-theme-territory',
			$theme_uri . '/assets/js/territory.js',
			array(),
			file_exists( $theme_path . '/assets/js/territory.js' ) ? (string) filemtime( $theme_path . '/assets/js/territory.js' ) : $theme_version,
			true
		);
		wp_script_add_data( 'ddna-theme-territory', 'strategy', 'defer' );
	}
}
add_action( 'wp_enqueue_scripts', 'ddna_theme_enqueue_assets' );
