<?php
/**
 * Metadatos SEO técnicos que no dependen de la migración de URLs.
 *
 * @package DDNA_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Obtiene una descripción breve desde contenido administrable.
 *
 * @return string
 */
function ddna_theme_get_meta_description() {
	$description = '';

	if ( is_front_page() ) {
		$front_page_id = get_queried_object_id();
		if ( $front_page_id ) {
			$description = get_post_field( 'post_excerpt', $front_page_id );
		}
		if ( ! $description ) {
			$description = get_bloginfo( 'description', 'display' );
		}
		if ( ! $description ) {
			$description = __( 'Defensoría de los Derechos de Niñas, Niños y Adolescentes de la Provincia de Córdoba.', 'ddna-theme' );
		}
	} elseif ( is_singular() ) {
		$description = get_the_excerpt( get_queried_object_id() );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$description = term_description();
	}

	$description = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( strip_shortcodes( (string) $description ) ) ) );

	return wp_html_excerpt( $description, 160, '…' );
}

/**
 * Devuelve la URL de imagen social disponible para la vista actual.
 *
 * @return string
 */
function ddna_theme_get_social_image_url() {
	$image_id = 0;

	if ( is_singular() ) {
		$image_id = get_post_thumbnail_id( get_queried_object_id() );
	}
	if ( ! $image_id ) {
		$image_id = (int) get_theme_mod( 'custom_logo' );
	}

	$image = $image_id ? wp_get_attachment_image_url( $image_id, 'large' ) : '';

	return $image ? $image : '';
}

/** Imprime description y metadatos Open Graph básicos. */
function ddna_theme_output_seo_meta() {
	if ( is_admin() || is_feed() || is_robots() ) {
		return;
	}

	$description = ddna_theme_get_meta_description();
	$canonical   = is_singular() ? get_permalink( get_queried_object_id() ) : ( is_front_page() ? home_url( '/' ) : '' );
	$title       = wp_get_document_title();
	$image       = ddna_theme_get_social_image_url();
	$type        = is_singular( 'post' ) ? 'article' : 'website';

	if ( $description ) {
		echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
	}
	echo '<meta property="og:locale" content="' . esc_attr( str_replace( '-', '_', get_bloginfo( 'language' ) ) ) . '">' . "\n";
	echo '<meta property="og:type" content="' . esc_attr( $type ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	if ( $description ) {
		echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
	}
	if ( $canonical ) {
		echo '<meta property="og:url" content="' . esc_url( $canonical ) . '">' . "\n";
	}
	echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
	if ( $image ) {
		echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'ddna_theme_output_seo_meta', 5 );

/** Evita indexar entornos locales o de staging aunque la opción se copie mal. */
function ddna_theme_non_production_robots( $robots ) {
	if ( 'production' !== wp_get_environment_type() ) {
		$robots['noindex']  = true;
		$robots['nofollow'] = true;
	}

	return $robots;
}
add_filter( 'wp_robots', 'ddna_theme_non_production_robots' );

/** Evita publicar archivos de autores en el sitemap por privacidad. */
function ddna_theme_filter_sitemap_provider( $provider, $name ) {
	return 'users' === $name ? false : $provider;
}
add_filter( 'wp_sitemaps_add_provider', 'ddna_theme_filter_sitemap_provider', 10, 2 );
