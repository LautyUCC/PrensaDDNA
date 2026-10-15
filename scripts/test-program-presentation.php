<?php
/** Read-only local presentation regression checks; no fixture records are stored. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }
if ( 'local' !== wp_get_environment_type() ) { WP_CLI::error( 'Checks exclusivamente locales.' ); }
$check = static function ( $condition, $message ) {
	if ( ! $condition ) { WP_CLI::error( $message ); }
	WP_CLI::log( 'OK: ' . $message );
};
$text = static fn( $html ) => preg_replace( '/\s+/u', '', html_entity_decode( wp_strip_all_tags( $html ), ENT_QUOTES, 'UTF-8' ) );
foreach ( array( 'programas-participacion-nnya', 'cooperacion-internacional-interinstitucional' ) as $slug ) {
	$GLOBALS['wp_query'] = new WP_Query( array( 'pagename' => $slug ) );
	$GLOBALS['wp_the_query'] = $GLOBALS['wp_query'];
	$check( have_posts(), 'Página existente: ' . $slug );
	the_post();
	$source = get_post_field( 'post_content', get_the_ID() );
	$before = hash( 'sha256', $source );
	remove_filter( 'the_content', 'ddna_theme_program_presentation', 20 );
	$base = apply_filters( 'the_content', $source );
	add_filter( 'the_content', 'ddna_theme_program_presentation', 20 );
	$after = ddna_theme_program_presentation( $base );
	$check( ddna_theme_program_presentation( $after ) === $after, 'Presentación idempotente: ' . $slug );
	if ( 'cooperacion-internacional-interinstitucional' === $slug ) {
		$check( $text( $base ) === $text( $after ), 'Texto de Cooperación preservado íntegramente' );
		$check( 5 === substr_count( $after, 'panel-accordion program-accordion' ), 'Cinco acordeones, sin anidar ni duplicar' );
	} else {
		$check( str_contains( $text( $after ), $text( $base ) ), 'Texto previo de Participación preservado' );
		$url = 'https://drive.google.com/file/d/1jF07LSJaQlx6s5WHTJDKvTMXZaLlrVf2/view?usp=sharing';
		$fixture = '<h2>Detrás del Humo</h2><p>Descripción que debe conservarse.</p><p><a href="https://example.test/old.pdf">Ver Dossier</a></p><p><a href="' . $url . '">Detrás del Humo</a></p><h2>Otra sección</h2><p>Otro texto.</p>';
		$result = ddna_theme_program_presentation( $fixture );
		$check( 1 === substr_count( $result, '1jF07LSJaQlx6s5WHTJDKvTMXZaLlrVf2' ) && ! str_contains( $result, 'Ver Dossier' ), 'Un solo CTA equivalente, sin botón redundante' );
		$check( str_contains( $result, 'Descripción que debe conservarse.' ) && str_contains( $result, 'Otro texto.' ), 'Descripciones vecinas preservadas al reutilizar CTA' );
	}
	$check( hash( 'sha256', get_post_field( 'post_content', get_the_ID() ) ) === $before, 'Contenido DB sin escrituras' );
	wp_reset_postdata();
}
WP_CLI::success( 'Presentación y contenido verificados sin modificar la DB.' );
