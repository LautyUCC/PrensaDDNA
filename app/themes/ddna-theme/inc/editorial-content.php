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
	return $phone ? '<a class="adolescence-line-button" href="' . esc_url( ddna_theme_phone_uri( $phone ) ) . '">' . esc_html( $phone . ' (' . ( $data['adolescence_label'] ?? '' ) . ')' ) . '</a>' : '';
} );

function ddna_theme_page_link( $slug, $label ) {
	$page = get_page_by_path( $slug );
	return $page && 'publish' === $page->post_status ? '<a class="button" href="' . esc_url( get_permalink( $page ) ) . '">' . esc_html( $label ) . '</a>' : '<span>' . esc_html( $label ) . '</span>';
}
add_shortcode( 'ddna_press_links', static function () {
	return '<div class="editorial-actions">' . ddna_theme_page_link( 'comunicados', 'Comunicados y pronunciamientos' ) . '</div>';
} );

/** Cabecera del accordion institucional compartido; IDs únicos incluso en una misma página. */
function ddna_theme_document_accordion_header( $label, $id ) {
	return '<h2><button class="institutional-accordion__trigger" type="button" id="' . esc_attr( $id . '-trigger' ) . '" aria-expanded="false" aria-controls="' . esc_attr( $id ) . '">' . esc_html( $label ) . '<span aria-hidden="true"></span></button></h2>';
}

/** Los iconos decorativos provienen de los recursos gráficos versionados del sitio. */
function ddna_theme_document_icon( $filename ) {
	return '<img class="document-collection__icon" src="' . esc_url( get_template_directory_uri() . '/assets/images/documentos/' . $filename ) . '" alt="" width="64" height="64" loading="lazy" decoding="async">';
}

add_shortcode( 'ddna_statements', static function () {
	$statements = get_option( 'ddna_final_statements', array() );
	$link_keys = array(
		'COMUNICADO CONJUNTO DE LAS DEFENSORÍAS DE NNyA DEL PAÍS ANTE EL VETO PRESIDENCIAL A LA LEY DE EMERGENCIA PEDIÁTRICA.' => 'statement_health_2025',
		'COMUNICADO SOBRE EL VETO A LA LEY DE EMERGENCIA EN DISCAPACIDAD' => 'statement_disability_2025',
		'LEY DE RESPONSABILIDAD PENAL JUVENIL: DEROGACIÓN DEL DECRETO-LEY 22.278.' => 'statement_juvenile_2022',
	);
	usort( $statements, static fn( $a, $b ) => (int) $b['year'] <=> (int) $a['year'] );
	$output = '<div class="document-collection document-collection--statements" data-institutional-accordion>'; $year = null;
	foreach ( $statements as $item ) {
		if ( $year !== (int) $item['year'] ) {
			if ( null !== $year ) { $output .= '</ul></div></section>'; }
			$year = (int) $item['year'];
			$id = wp_unique_id( 'statements-' . $year . '-' );
			$output .= '<section class="document-collection__section">' . ddna_theme_document_accordion_header( $year, $id ) . '<div class="institutional-accordion__panel" id="' . esc_attr( $id ) . '" aria-labelledby="' . esc_attr( $id . '-trigger' ) . '" hidden><ul class="statement-titles">';
		}
		$posts = get_posts( array( 'post_type' => array( 'post', 'documento' ), 'post_status' => 'publish', 'posts_per_page' => 1, 'title' => $item['title'], 'no_found_rows' => true ) );
		$post = $posts ? $posts[0] : null;
		$url = ( $item['url'] ?? '' ) ?: ddna_theme_editorial_url( $link_keys[ $item['title'] ] ?? '' );
		if ( ! $url && $post ) {
			$file = absint( get_post_meta( $post->ID, '_ddna_file_id', true ) );
			$url = $file ? wp_get_attachment_url( $file ) : get_post_meta( $post->ID, '_ddna_external_url', true );
			$url = $url ?: get_permalink( $post );
		}
		$output .= '<li>' . ( $url ? '<a class="document-collection__document" href="' . esc_url( $url ) . '">' . ddna_theme_document_icon( 'documentos-45.png' ) . '<span>' . esc_html( $item['title'] ) . '</span></a>' : '<span class="document-collection__document resource-unavailable" aria-disabled="true">' . ddna_theme_document_icon( 'documentos-45.png' ) . '<span>' . esc_html( $item['title'] ) . '<span class="screen-reader-text"> — enlace pendiente</span></span></span>' ) . '</li>';
	}
	return $output . ( null !== $year ? '</ul></div></section>' : '' ) . '</div>';
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

/** Logos informativos de convenios; no son enlaces ni controles interactivos. */
add_shortcode( 'ddna_conventions', static function () {
	$items = get_option( 'ddna_feedback_conventions', array() );
	ob_start();
	?><div class="conventions-grid"><?php foreach ( $items as $item ) : ?>
			<?php // Última institución verificada del orden actual; ocultar solo su presentación.
			if ( 'convenio-92.png' === ( $item['logo'] ?? '' ) && 'Secretaría de Fortalecimiento Vecinal, Cultura y Deportes' === ( $item['name'] ?? '' ) ) { continue; } ?>
			<?php if ( ! empty( $item['logo'] ) ) : ?><img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/convenios/' . basename( $item['logo'] ) ); ?>" alt="<?php echo esc_attr( $item['name'] ); ?>" loading="lazy" decoding="async"><?php endif; ?>
	<?php endforeach; ?></div><?php
	return ob_get_clean();
} );

/** Categorías y documentos canónicos; los enlaces resuelven media del WordPress actual. */
add_shortcode( 'ddna_normativa', static function () {
	$sections = get_option( 'ddna_normativa_sections', array() );
	ob_start();
	?><div class="document-collection document-collection--normativa" data-institutional-accordion><?php
	foreach ( $sections as $section ) :
		$id = wp_unique_id( 'normativa-' ); ?>
		<section class="normativa-section">
			<?php echo ddna_theme_document_accordion_header( $section['title'], $id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<div class="institutional-accordion__panel" id="<?php echo esc_attr( $id ); ?>" aria-labelledby="<?php echo esc_attr( $id . '-trigger' ); ?>" hidden>
			<div class="normativa-links">
			<?php foreach ( $section['documents'] as $item ) :
				$post = $item['slug'] ? get_page_by_path( $item['slug'], OBJECT, 'documento' ) : null;
				$file_id = $post ? absint( get_post_meta( $post->ID, '_ddna_file_id', true ) ) : 0;
				$url = $file_id ? wp_get_attachment_url( $file_id ) : '';
				if ( $url ) : ?>
					<a class="document-collection__document normativa-document" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo ddna_theme_document_icon( 'documentos-46.png' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( $item['title'] ); ?><span class="screen-reader-text"> (PDF, abre en otra pestaña)</span></span></a>
				<?php else : ?>
					<span class="document-collection__document normativa-document resource-unavailable" aria-disabled="true"><?php echo ddna_theme_document_icon( 'documentos-46.png' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( $item['title'] ); ?><span class="screen-reader-text"> — sin enlace en la fuente original</span></span></span>
				<?php endif;
			endforeach; ?>
			</div>
			</div>
		</section>
	<?php endforeach; ?></div><?php
	return ob_get_clean();
} );
