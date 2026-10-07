<?php
/** Verificación no mutante del lote editorial y archivos de Media Library. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }
$manifest = json_decode( file_get_contents( dirname( __DIR__ ) . '/content/feedback-prensa-octubre.json' ), true );
$errors = array();
$check = static function ( $condition, $message ) use ( &$errors ) { if ( ! $condition ) { $errors[] = $message; } };
$check( 9 === count( get_option( 'ddna_care_guides', array() ) ), 'Deben existir nueve guías.' );
$pdfs = array_merge( $manifest['guides'], array_filter( $manifest['normativa']['documents'], static fn( $item ) => ! empty( $item['file'] ) ) );
foreach ( $pdfs as $guide ) {
	$post = get_page_by_path( $guide['slug'], OBJECT, 'documento' );
	$id = $post ? absint( get_post_meta( $post->ID, '_ddna_file_id', true ) ) : 0;
	$file = $id ? get_attached_file( $id ) : '';
	$check( $file && is_file( $file ) && filesize( $file ) === $guide['size'] && hash_file( 'sha256', $file ) === $guide['sha256'], 'Archivo/Media Library: ' . $guide['slug'] );
	$check( $id && 'application/pdf' === get_post_mime_type( $id ), 'MIME: ' . $guide['slug'] );
	$check( $post && ! get_post_meta( $post->ID, '_ddna_external_url', true ), 'La guía debe usar media propia.' );
}
$help = get_page_by_path( 'asistencia' );
$html = apply_filters( 'the_content', $help->post_content );
$check( 4 === substr_count( $html, '<details ' ), 'Asistencia: cuatro desplegables.' );
$first_end = strpos( $html, '</details>' );
$check( false !== strpos( substr( $html, 0, $first_end ), 'Derecho a ser escuchado' ), 'Derecho a ser escuchado dentro del primer desplegable.' );
$check( strpos( $html, 'Línea fija' ) < strpos( $html, 'Línea de Asistencia' ), 'Línea fija primero.' );
foreach ( array( 'consulta.defensoria@cba.gov.ar', 'casosasistencia@gmail.com', '<strong>La atención es gratuita.</strong>' ) as $text ) { $check( str_contains( $html, $text ), 'Contenido asistencia: ' . $text ); }
$institution = get_page_by_path( 'defensoria' );
$check( 5 === substr_count( $institution->post_content, '<details ' ) && ! str_contains( $institution->post_content, 'Informes de gestión' ), 'Defensoría: acordeones sin bloque de informes.' );
$conventions = do_shortcode( '[ddna_conventions]' );
$check( 13 === substr_count( $conventions, '<img ' ) && ! preg_match( '/<(?:a|article|h2|p)\b/', $conventions ), 'Convenios: trece imágenes sin cards, textos ni enlaces.' );
$campaigns = get_posts( array( 'post_type' => 'campana', 'posts_per_page' => -1, 'meta_key' => '_ddna_featured', 'meta_value' => '1', 'orderby' => 'menu_order', 'order' => 'ASC' ) );
$check( 5 === count( $campaigns ), 'Campañas: cinco.' );
foreach ( $manifest['campaigns'] as $item ) {
	$post = get_page_by_path( $item['slug'], OBJECT, 'campana' );
	$check( $post && $post->post_excerpt === $item['excerpt'] && (int) $post->menu_order === $item['order'] && get_post_meta( $post->ID, '_ddna_external_url', true ) === $item['url'], 'Campaña: ' . $item['slug'] );
}
$check( get_option( 'ddna_editorial_links' )['graphic_materials'] === $manifest['graphic_materials'], 'Materiales gráficos.' );
$check( (bool) get_page_by_path( 'normativa' ), 'Ruta Normativa.' );
$normativa = do_shortcode( '[ddna_normativa]' );
$check( 4 === substr_count( $normativa, 'class="normativa-section"' ) && 5 === substr_count( $normativa, '<a ' ), 'Normativa: cuatro secciones, cinco PDFs.' );
$check( ! str_contains( $normativa, 'ddna.cba.gov.ar' ) && str_contains( $normativa, 'Código Civil y Comercial' ), 'Normativa: media propia y Código Civil conservado sin inventar enlace.' );
$check( (bool) get_option( 'ddna_backup_before_feedback_octubre' ), 'Respaldo editorial.' );
$baseline = json_decode( file_get_contents( dirname( __DIR__ ) . '/content/final-sept-2026.json' ), true );
foreach ( $baseline['records'] as $item ) {
	if ( 'campana' !== $item['type'] ) { continue; }
	$post = get_page_by_path( $item['slug'], OBJECT, 'campana' );
	$check( $post && $post->post_title === $item['title'] && $post->post_excerpt === ( $item['excerpt'] ?? '' ), 'Campaña previa conservada: ' . $item['slug'] );
}
if ( $errors ) { WP_CLI::error( implode( "\n", $errors ) ); }
WP_CLI::success( '14 PDFs/Media Library/checksums (9 guías + 5 normativas), cuatro categorías, asistencia, Defensoría, 13 convenios, 5 campañas y respaldo verificados.' );
