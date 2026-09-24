<?php
/** Verificación no mutante de PHP, contenido, preservación y configuración. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }
$errors = array(); $count = 0;
foreach ( array( get_template_directory(), DDNA_CORE_PATH, __DIR__ ) as $dir ) {
	foreach ( new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $dir, FilesystemIterator::SKIP_DOTS ) ) as $file ) {
		if ( ! $file->isFile() || 'php' !== $file->getExtension() ) { continue; }
		$lines = array(); $status = 0;
		exec( 'php -l ' . escapeshellarg( $file->getPathname() ), $lines, $status );
		$count++;
		if ( $status ) { $errors[] = implode( "\n", $lines ); }
	}
}
$manifest = json_decode( file_get_contents( dirname( __DIR__ ) . '/content/final-sept-2026.json' ), true );
foreach ( $manifest['pages'] as $item ) {
	$page = get_page_by_path( $item['slug'] );
	if ( ! $page || 'publish' !== $page->post_status || $page->post_title !== $item['title'] ) { $errors[] = 'Página: ' . $item['slug']; }
}
foreach ( array( 'programa' => 6, 'campana' => 3 ) as $type => $expected ) {
	$posts = get_posts( array( 'post_type' => $type, 'posts_per_page' => -1, 'meta_key' => '_ddna_featured', 'meta_value' => '1' ) );
	if ( count( $posts ) !== $expected ) { $errors[] = 'Selección: ' . $type; }
}
$backup = get_option( 'ddna_backup_before_final_sept_2026', array() );
if ( ! $backup ) { $errors[] = 'No hay respaldo editorial.'; }
foreach ( $backup['posts'] ?? array() as $id => $record ) { if ( ! get_post( $id ) ) { $errors[] = 'Registro previo perdido: ' . $id; } }
if ( '351 428 8881' !== ddna_core_get_institutional_settings()['phone'] ) { $errors[] = 'Teléfono fijo'; }
if ( 5 !== (int) wp_count_posts( 'post' )->publish ) { $errors[] = 'Posts previos modificados'; }
if ( ! shortcode_exists( 'ddna_contact' ) || ! shortcode_exists( 'ddna_statements' ) ) { $errors[] = 'Shortcodes'; }
$input = ddna_core_sanitize_institutional_settings( array( 'case_email' => 'invalid', 'phone' => '<script>x</script>351' ) );
if ( '' !== $input['case_email'] || str_contains( $input['phone'], '<' ) ) { $errors[] = 'Sanitización contacto'; }
if ( ! str_contains( do_shortcode( '[ddna_contact]' ), 'tel:+543514288881' ) ) { $errors[] = 'Contacto no utiliza tel correcto'; }
WP_CLI::log( 'Archivos PHP comprobados: ' . $count );
if ( $errors ) { WP_CLI::error( implode( "\n", $errors ) ); }
WP_CLI::success( 'PHP, páginas, selección 6/3, contacto, shortcodes y preservación de registros verificados.' );
