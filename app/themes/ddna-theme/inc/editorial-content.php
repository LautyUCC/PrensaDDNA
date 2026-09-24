<?php
/** Presentación reutilizable de contenidos administrados por WordPress. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function ddna_theme_editorial_url( $key ) {
	$fields = function_exists( 'ddna_core_editorial_link_fields' ) ? ddna_core_editorial_link_fields() : array();
	$links = get_option( 'ddna_editorial_links', array() );
	return isset( $fields[ $key ] ) ? ( $links[ $key ] ?? '' ) : '';
}

function ddna_theme_resource_control( $key, $label ) {
	$url = ddna_theme_editorial_url( $key );
	if ( $url ) {
		return '<a class="button" href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $label ) . '<span class="screen-reader-text"> (abre en otra pestaña)</span></a>';
	}
	return '<span class="button resource-unavailable" aria-disabled="true">' . esc_html( $label ) . '<span class="screen-reader-text"> — recurso pendiente de publicación</span></span>';
}
add_shortcode( 'ddna_resource', static function ( $atts ) {
	$atts = shortcode_atts( array( 'key' => '', 'label' => '' ), $atts );
	return ddna_theme_resource_control( sanitize_key( $atts['key'] ), $atts['label'] );
} );

add_shortcode( 'ddna_contact', static function () {
	ob_start();
	get_template_part( 'template-parts/components/institutional-contact' );
	return ob_get_clean();
} );
add_shortcode( 'ddna_adolescence_line', static function () {
	$data = function_exists( 'ddna_core_get_institutional_settings' ) ? ddna_core_get_institutional_settings() : array();
	$phone = $data['adolescence_phone'] ?? '';
	return $phone ? '<a href="' . esc_url( ddna_theme_phone_uri( $phone ) ) . '">' . esc_html( $phone . ' — ' . ( $data['adolescence_label'] ?? '' ) ) . '</a>' : '';
} );

function ddna_theme_page_link( $slug, $label ) {
	$page = get_page_by_path( $slug );
	return $page && 'publish' === $page->post_status ? '<a class="button" href="' . esc_url( get_permalink( $page ) ) . '">' . esc_html( $label ) . '</a>' : '<span>' . esc_html( $label ) . '</span>';
}
add_shortcode( 'ddna_press_links', static function () {
	return '<div class="editorial-actions">' . ddna_theme_page_link( 'defensoria-en-los-medios', 'La Defensoría en los Medios' ) . ddna_theme_page_link( 'comunicados', 'Comunicados' ) . '</div>';
} );

add_shortcode( 'ddna_statements', static function () {
	$statements = get_option( 'ddna_final_statements', array() );
	$link_keys = array( 'statement_health_2025', 'statement_disability_2025', 'statement_juvenile_2022' );
	$output = ''; $year = null;
	foreach ( $statements as $index => $item ) {
		if ( $year !== (int) $item['year'] ) {
			if ( null !== $year ) { $output .= '</ul>'; }
			$year = (int) $item['year'];
			$output .= '<h2>' . esc_html( $year ) . '</h2><ul class="statement-titles">';
		}
		$posts = get_posts( array( 'post_type' => array( 'post', 'documento' ), 'post_status' => 'publish', 'posts_per_page' => 1, 'title' => $item['title'], 'no_found_rows' => true ) );
		$post = $posts ? $posts[0] : null;
		$url = ddna_theme_editorial_url( $link_keys[ $index ] ?? '' );
		if ( ! $url && $post ) {
			$file = absint( get_post_meta( $post->ID, '_ddna_file_id', true ) );
			$url = $file ? wp_get_attachment_url( $file ) : get_post_meta( $post->ID, '_ddna_external_url', true );
			$url = $url ?: get_permalink( $post );
		}
		$output .= '<li>' . ( $url ? '<a href="' . esc_url( $url ) . '">' . esc_html( $item['title'] ) . '</a>' : '<span aria-disabled="true">' . esc_html( $item['title'] ) . '<span class="screen-reader-text"> — enlace pendiente</span></span>' ) . '</li>';
	}
	return $output . ( null !== $year ? '</ul>' : '' );
} );

/** Usa el mismo contenido de página tanto dentro de la Home como en su permalink. */
function ddna_theme_render_editorial_page( $slug ) {
	$page = get_page_by_path( $slug );
	if ( $page && 'publish' === $page->post_status ) { echo apply_filters( 'the_content', $page->post_content ); } // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

function ddna_theme_program_dossier_key( $post_id ) {
	$keys = array(
		'programa-va-con-vos'                          => 'va_con_vos_dossier',
		'programa-entre-pantallas'                     => 'entre_pantallas_dossier',
		'desarrollo-integral-primeros-anos-vida'       => 'desarrollo_integral_dossier',
	);
	$slug = get_post_field( 'post_name', $post_id );
	return $keys[ $slug ] ?? '';
}

function ddna_theme_card_destination( $post_id ) {
	$external = get_post_meta( $post_id, '_ddna_external_url', true );
	$dossier_key = ddna_theme_program_dossier_key( $post_id );
	$resource = $dossier_key ? ddna_theme_editorial_url( $dossier_key ) : '';
	return $resource ?: ( $external ?: ( get_post_meta( $post_id, '_ddna_destination_pending', true ) ? '' : get_permalink( $post_id ) ) );
}
