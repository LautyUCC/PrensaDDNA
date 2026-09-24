<?php
/**
 * Helpers de presentación reutilizables.
 *
 * @package DDNA_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Imprime fecha y autor de una publicación.
 */
function ddna_theme_post_meta() {
	printf(
		'<span class="entry-date"><time datetime="%1$s">%2$s</time></span><span class="screen-reader-text"> %3$s </span><span class="entry-author">%4$s</span>',
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date() ),
		esc_html__( 'por', 'ddna-theme' ),
		esc_html( get_the_author() )
	);
}

/**
 * Devuelve las clases de un contenedor principal.
 *
 * @param string[] $classes Clases adicionales.
 * @return string
 */
function ddna_theme_container_classes( $classes = array() ) {
	$classes = array_merge( array( 'site-container' ), $classes );

	return implode( ' ', array_map( 'sanitize_html_class', $classes ) );
}

/**
 * Determines whether the current page belongs to the institutional area.
 *
 * Institutional child pages inherit the Home header and its panel menu,
 * without duplicating that header or the Home hero.
 *
 * @return bool
 */
function ddna_theme_is_institutional_page() {
	if ( ! is_page() ) {
		return false;
	}

	$institutional_slugs = array( 'defensoria', 'informes-anuales', 'normativas', 'convenios', 'contacto', 'hay-otra-forma-prevencion-maltrato', 'hay-otra-forma-prevencion-bullying' );
	$page_id             = get_queried_object_id();

	while ( $page_id ) {
		$page = get_post( $page_id );
		if ( ! $page ) {
			return false;
		}

		if ( in_array( $page->post_name, $institutional_slugs, true ) ) {
			return true;
		}

		$page_id = (int) $page->post_parent;
	}

	return false;
}

/** Returns the environment-configured public URL for the Observatory. */
function ddna_theme_observatory_url() {
	$url   = defined( 'DDNA_OBSERVATORIO_URL' ) ? trim( (string) DDNA_OBSERVATORIO_URL ) : '';
	$parts = $url ? wp_parse_url( $url ) : false;
	if ( ! $parts || empty( $parts['host'] ) || empty( $parts['scheme'] ) || ! in_array( $parts['scheme'], array( 'http', 'https' ), true ) ) {
		return '';
	}
	return esc_url_raw( $url );
}

/**
 * Returns a Home panel URL that works both on the Home and on inner pages.
 *
 * @param string $panel_id Home panel identifier.
 * @return string
 */
function ddna_theme_home_panel_url( $panel_id ) {
	if ( 'observatorio' === $panel_id ) {
		return ddna_theme_observatory_url();
	}
	return home_url( '/#' . rawurlencode( $panel_id ) );
}

/**
 * Muestra una navegación básica cuando todavía no existe un menú asignado.
 */
function ddna_theme_primary_menu_fallback() {
	?>
	<ul class="primary-menu" id="primary-menu">
		<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Inicio', 'ddna-theme' ); ?></a></li>
		<?php wp_list_pages( array( 'title_li' => '' ) ); ?>
	</ul>
	<?php
}

/**
 * Normaliza un teléfono visible para utilizarlo en un enlace tel:.
 * Los números locales de Córdoba se internacionalizan con +54.
 *
 * @param string $phone Teléfono visible.
 * @return string
 */
function ddna_theme_phone_uri( $phone ) {
	$phone = trim( $phone );
	$has_plus = str_starts_with( $phone, '+' );
	$digits = preg_replace( '/\D+/', '', $phone );
	if ( ! $digits ) { return ''; }
	if ( ! $has_plus && str_starts_with( $digits, '351' ) ) { $digits = '54' . $digits; }
	return 'tel:+' . $digits;
}
