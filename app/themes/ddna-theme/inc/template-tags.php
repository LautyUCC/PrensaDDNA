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
