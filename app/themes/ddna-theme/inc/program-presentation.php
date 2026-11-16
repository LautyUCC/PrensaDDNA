<?php
/** Frontend-only presentation of the two reviewed program pages; no DB writes. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'template_redirect', static function () {
	if ( ! is_page( 'programas-participacion-nnya' ) ) { return; }
	$program = get_page_by_path( 'programas-participacion-nnya', OBJECT, 'programa' );
	if ( $program && 'publish' === $program->post_status ) {
		wp_safe_redirect( get_permalink( $program ), 301 );
		exit;
	}
} );

function ddna_theme_reviewed_program() {
	if ( is_singular( 'programa' ) ) { return (string) get_post_field( 'post_name', get_queried_object_id() ); }
	if ( is_page( 'programas-participacion-nnya' ) ) { return 'participation'; }
	if ( is_page( 'cooperacion-internacional-interinstitucional' ) ) { return 'cooperation'; }
	if ( is_page( 'acompanamiento-formacion-adultos' ) ) { return 'acompanamiento-formacion-adultos'; }
	return '';
}

add_filter( 'body_class', static function ( $classes ) {
	$program = ddna_theme_reviewed_program();
	if ( $program ) { $classes[] = 'program-detail'; $classes[] = 'program-detail--' . $program; }
	return $classes;
} );

add_action( 'wp_enqueue_scripts', static function () {
	if ( ! ddna_theme_reviewed_program() ) { return; }
	$file = '/assets/css/components/program-detail.css';
	wp_enqueue_style( 'ddna-theme-program-detail', get_template_directory_uri() . $file, array( 'ddna-theme-editorial-feedback' ), (string) filemtime( get_template_directory() . $file ) );
}, 20 );

/** Preserve content nodes, applying only approved controls to rendered HTML. */
function ddna_theme_program_presentation( $content ) {
	$program = ddna_theme_reviewed_program();
	if ( ! $program || ! in_the_loop() || get_the_ID() !== get_queried_object_id() || ! class_exists( 'DOMDocument' ) ) { return $content; }
	$doc = new DOMDocument( '1.0', 'UTF-8' );
	$previous = libxml_use_internal_errors( true );
	$loaded = $doc->loadHTML( '<?xml encoding="utf-8" ?><div id="ddna-reviewed-program">' . $content . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
	libxml_clear_errors();
	libxml_use_internal_errors( $previous );
	if ( ! $loaded ) { return $content; }
	$xpath = new DOMXPath( $doc );
	$root = $xpath->query( '//*[@id="ddna-reviewed-program"]' )->item( 0 );
	if ( ! $root ) { return $content; }
	$label = static fn( $node ) => trim( preg_replace( '/\s+/u', ' ', $node->textContent ) );
	$add_class = static function ( $node, $class ) {
		$classes = preg_split( '/\s+/', trim( $node->getAttribute( 'class' ) ) );
		$node->setAttribute( 'class', trim( implode( ' ', array_unique( array_merge( $classes, array( $class ) ) ) ) ) );
	};
	$make_rows = static function ( $items ) use ( $doc, $root, $label ) {
		$headings = iterator_to_array( $root->getElementsByTagName( 'h2' ) );
		$headings = array_merge( $headings, iterator_to_array( $root->getElementsByTagName( 'h3' ) ) );
		foreach ( $headings as $heading ) {
			$title = $label( $heading );
			if ( ! isset( $items[ $title ] ) || $heading->parentNode !== $root ) { continue; }
			$section = $doc->createElement( 'section' );
			$section->setAttribute( 'class', 'program-visual-row' );
			$figure = $doc->createElement( 'figure' );
			$figure->setAttribute( 'class', 'program-visual-row__figure' );
			$img = $doc->createElement( 'img' );
			$img->setAttribute( 'src', get_template_directory_uri() . '/assets/images/program-details/' . $items[ $title ]['image'] );
			$img->setAttribute( 'alt', $title );
			$img->setAttribute( 'loading', 'lazy' );
			$img->setAttribute( 'decoding', 'async' );
			$figure->appendChild( $img );
			$body = $doc->createElement( 'div' );
			$body->setAttribute( 'class', 'program-visual-row__body' );
			$root->insertBefore( $section, $heading );
			$section->appendChild( $figure );
			$section->appendChild( $body );
			for ( $node = $heading; $node && ( $node === $heading || ! ( $node instanceof DOMElement && in_array( $node->tagName, array( 'h2', 'h3' ), true ) ) ); $node = $next ) {
				$next = $node->nextSibling;
				$body->appendChild( $node );
			}
			if ( ! empty( $items[ $title ]['video'] ) ) {
				$actions = $doc->createElement( 'p' );
				$link = $doc->createElement( 'a', '▶ VER CHARLA' );
				$link->setAttribute( 'class', 'program-resource-button program-video-button' );
				$link->setAttribute( 'href', $items[ $title ]['video'] );
				$link->setAttribute( 'target', '_blank' );
				$link->setAttribute( 'rel', 'noopener noreferrer' );
				$actions->appendChild( $link );
				$body->appendChild( $actions );
			}
		}
	};

	if ( in_array( $program, array( 'participation', 'programas-participacion-nnya' ), true ) ) {
		foreach ( $xpath->query( './/h2 | .//h3', $root ) as $node ) {
			if ( 'Articulación, asesoramiento y acompañamiento a territorios' !== $label( $node ) ) { continue; }
			$add_class( $node, 'program-subprogram-heading' );
			for ( $next = $node->nextSibling; $next; $next = $next->nextSibling ) {
				if ( $next instanceof DOMElement && 'ul' === $next->tagName ) { $add_class( $next, 'program-subprogram-list' ); break; }
				if ( $next instanceof DOMElement && in_array( $next->tagName, array( 'h2', 'h3' ), true ) ) { break; }
			}
			break;
		}
		$heading = null;
		foreach ( $xpath->query( './/h2 | .//h3', $root ) as $node ) {
			if ( 'Detrás del Humo' === $label( $node ) ) { $heading = $node; break; }
		}
		if ( ! $heading ) { $heading = $doc->createElement( 'h2', 'Detrás del Humo' ); $root->appendChild( $heading ); }
		$url = 'https://drive.google.com/file/d/1jF07LSJaQlx6s5WHTJDKvTMXZaLlrVf2/view?usp=sharing';
		$candidates = array();
		foreach ( $xpath->query( './/a', $root ) as $anchor ) {
			if ( str_contains( $anchor->getAttribute( 'href' ), '1jF07LSJaQlx6s5WHTJDKvTMXZaLlrVf2' ) ) { $candidates[] = $anchor; }
		}
		// An equivalent dossier link may still point to an older destination.
		$section_end = null;
		for ( $node = $heading->nextSibling; $node; $node = $node->nextSibling ) {
			if ( $node instanceof DOMElement && in_array( $node->tagName, array( 'h2', 'h3' ), true ) ) { $section_end = $node; break; }
			$anchors = $node instanceof DOMElement ? $xpath->query( './/a | self::a', $node ) : array();
			foreach ( $anchors as $anchor ) {
				if ( preg_match( '/^(Ver Dossier|Detrás del Humo)(?:\s*\(.*\))?$/iu', $label( $anchor ) ) && ! in_array( $anchor, $candidates, true ) ) { $candidates[] = $anchor; }
			}
		}
		$cta = array_shift( $candidates );
		if ( ! $cta ) { $p = $doc->createElement( 'p' ); $cta = $doc->createElement( 'a' ); $p->appendChild( $cta ); $heading->parentNode->insertBefore( $p, $section_end ); }
		foreach ( $candidates as $duplicate ) { $duplicate->parentNode->removeChild( $duplicate ); }
		$cta->textContent = 'Dossier Detrás del Humo';
		$cta->setAttribute( 'href', $url );
		$cta->setAttribute( 'target', '_blank' );
		$cta->setAttribute( 'rel', 'noopener noreferrer' );
		$cta->setAttribute( 'aria-label', 'Dossier Detrás del Humo (abre en otra pestaña)' );
		$add_class( $cta, 'program-resource-button' );
		if ( $cta->parentNode instanceof DOMElement && 'p' === $cta->parentNode->tagName ) { $add_class( $cta->parentNode, 'program-dossier-actions' ); }
		foreach ( $xpath->query( './/p/a', $root ) as $anchor ) {
			if ( str_starts_with( $anchor->getAttribute( 'href' ), 'https://drive.google.com/' ) ) { $add_class( $anchor, 'program-resource-button' ); }
			$icon = str_contains( $anchor->getAttribute( 'href' ), 'instagram.com/detrasdelhumoo_' ) ? 'social-40.png' : ( str_contains( $anchor->getAttribute( 'href' ), 'youtube.com/@detrasdelhumoo' ) ? 'social-42.png' : '' );
			if ( $icon ) {
				$add_class( $anchor, 'program-resource-button' ); $add_class( $anchor, 'program-social-button' );
				if ( 'social-42.png' === $icon ) {
					$anchor->textContent = '@detrasdelhumoo';
					$anchor->setAttribute( 'aria-label', 'YouTube de Detrás del Humo' );
				}
				if ( $anchor->getElementsByTagName( 'img' )->length ) { continue; }
				$image = $doc->createElement( 'img' );
				$image->setAttribute( 'src', get_template_directory_uri() . '/assets/icons/social/' . $icon );
				$image->setAttribute( 'alt', '' );
				$image->setAttribute( 'width', '28' );
				$image->setAttribute( 'height', '28' );
				$anchor->insertBefore( $image, $anchor->firstChild );
			}
		}
		foreach ( $xpath->query( './/p', $root ) as $paragraph ) {
			$links = $xpath->query( './/a', $paragraph );
			$has_instagram = false;
			$has_youtube = false;
			foreach ( $links as $link ) {
				$has_instagram = $has_instagram || str_contains( $link->getAttribute( 'href' ), 'instagram.com/detrasdelhumoo_' );
				$has_youtube = $has_youtube || str_contains( $link->getAttribute( 'href' ), 'youtube.com/@detrasdelhumoo' );
			}
			if ( ! $has_instagram || ! $has_youtube ) { continue; }
			$add_class( $paragraph, 'program-social-actions' );
			foreach ( iterator_to_array( $paragraph->childNodes ) as $child ) {
				if ( XML_TEXT_NODE === $child->nodeType ) { $paragraph->removeChild( $child ); }
			}
			if ( $cta->parentNode !== $paragraph ) {
				$old_parent = $cta->parentNode;
				$paragraph->appendChild( $cta );
				if ( $old_parent instanceof DOMElement && 'p' === $old_parent->tagName && ! trim( $old_parent->textContent ) ) {
					$old_parent->parentNode->removeChild( $old_parent );
				}
			}
			break;
		}
		$make_rows( array(
			'Jóvenes Protagonistas' => array( 'image' => 'protagonistas-23.png' ),
			'El juego de los derechos' => array( 'image' => 'protagonistas-24.png' ),
			'Laboratorio Literario Adolescente' => array( 'image' => 'protagonistas-25.png' ),
			'Detrás del Humo' => array( 'image' => 'protagonistas-26.png' ),
		) );
	} elseif ( 'acompanamiento-formacion-adultos' === $program ) {
		$make_rows( array(
			'“La escucha activa de niñas, niños y adolescentes”' => array( 'image' => 'adultos-30.png' ),
			'Mes de las Infancias: “La mirada que sostiene”' => array( 'image' => 'adultos-31.png', 'video' => 'https://www.youtube.com/live/YFaXuhFU2Us?si=6VNaHd1gaDH4vVaq' ),
			'Mes del Adolescente: “Lo que no dicen las palabras”' => array( 'image' => 'adultos-32.png', 'video' => 'https://www.youtube.com/live/BFurEJ4k3fQ?si=0jhluRKDtq9e_y2a' ),
		) );
	} elseif ( 'mesas-interinstitucionales' === $program ) {
		$make_rows( array(
			'Copreti' => array( 'image' => 'mesas-copreti.png' ),
			'RediPaz' => array( 'image' => 'mesas-redipaz.png' ),
		) );
	} elseif ( 'cooperation' === $program ) {
		$headings = iterator_to_array( $xpath->query( './/h2 | .//h3', $root ) );
		foreach ( $headings as $heading ) {
			if ( 'El programa promueve:' !== $label( $heading ) && ! preg_match( '/^Cohorte [1-4]\b/u', $label( $heading ) ) ) { continue; }
			$inside_details = false;
			for ( $parent = $heading->parentNode; $parent && $parent !== $root; $parent = $parent->parentNode ) {
				if ( $parent instanceof DOMElement && 'details' === $parent->tagName ) { $inside_details = true; break; }
			}
			if ( $inside_details ) { continue; }
			$list = $heading->nextSibling;
			while ( $list && XML_TEXT_NODE === $list->nodeType && '' === trim( $list->textContent ) ) { $list = $list->nextSibling; }
			if ( ! $list instanceof DOMElement || 'ul' !== $list->tagName ) { continue; }
			$details = $doc->createElement( 'details' );
			$details->setAttribute( 'class', 'panel-accordion program-accordion' );
			$summary = $doc->createElement( 'summary' );
			$panel = $doc->createElement( 'div' );
			$panel->setAttribute( 'class', 'panel-accordion__content' );
			$heading->parentNode->insertBefore( $details, $heading );
			$summary->appendChild( $heading ); $panel->appendChild( $list );
			$details->appendChild( $summary ); $details->appendChild( $panel );
		}
	} elseif ( 'programa-va-con-vos' === $program ) {
		$url = ddna_theme_editorial_url( 'va_con_vos_dossier' );
		if ( $url ) {
			$cta = $doc->createElement( 'p' );
			$link = $doc->createElement( 'a', 'Dossier Va Con Vos' );
			$link->setAttribute( 'href', $url );
			$link->setAttribute( 'target', '_blank' );
			$link->setAttribute( 'rel', 'noopener noreferrer' );
			$add_class( $link, 'program-resource-button' );
			$cta->appendChild( $link );
			$root->appendChild( $cta );
		}
	}
	$output = '';
	foreach ( $root->childNodes as $node ) { $output .= $doc->saveHTML( $node ); }
	return $output;
}
add_filter( 'the_content', 'ddna_theme_program_presentation', 20 );
