<?php
/** Frontend-only presentation of the two reviewed program pages; no DB writes. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function ddna_theme_reviewed_program() {
	if ( is_page( 'programas-participacion-nnya' ) ) { return 'participation'; }
	if ( is_page( 'cooperacion-internacional-interinstitucional' ) ) { return 'cooperation'; }
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

	if ( 'participation' === $program ) {
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
		$cta->textContent = 'Detrás del Humo';
		$cta->setAttribute( 'href', $url );
		$cta->setAttribute( 'target', '_blank' );
		$cta->setAttribute( 'rel', 'noopener noreferrer' );
		$cta->setAttribute( 'aria-label', 'Detrás del Humo (abre en otra pestaña)' );
		$add_class( $cta, 'program-resource-button' );
		foreach ( $xpath->query( './/p/a', $root ) as $anchor ) {
			if ( str_starts_with( $anchor->getAttribute( 'href' ), 'https://drive.google.com/' ) ) { $add_class( $anchor, 'program-resource-button' ); }
		}
	} else {
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
	}
	$output = '';
	foreach ( $root->childNodes as $node ) { $output .= $doc->saveHTML( $node ); }
	return $output;
}
add_filter( 'the_content', 'ddna_theme_program_presentation', 20 );
