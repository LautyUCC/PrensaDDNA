<?php
/** Acotado a ddna_final_statements: dry-run, aplicación idempotente y rollback con control de drift. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }
$local = 'local' === wp_get_environment_type() && in_array( wp_parse_url( home_url(), PHP_URL_HOST ), array( 'localhost', '127.0.0.1', '::1' ), true );
if ( ! $local && ( 'APROBADO PARA PUBLICAR COMUNICADOS' !== getenv( 'DDNA_COMUNICADOS_APPROVAL' ) || untrailingslashit( home_url() ) !== getenv( 'DDNA_COMUNICADOS_TARGET_ORIGIN' ) ) ) {
 WP_CLI::error( 'Fuera de local requiere aprobación explícita de este lote y origen exacto.' );
}
$mode = getenv( 'DDNA_COMUNICADOS_MODE' ) ?: 'dry-run';
if ( ! in_array( $mode, array( 'dry-run', 'apply', 'rollback' ), true ) ) { WP_CLI::error( 'Modo inválido.' ); }
$key = 'ddna_final_statements';
$receipt_key = 'ddna_comunicados_octubre_2026_receipt';
$before = get_option( $key, array() );
if ( ! is_array( $before ) ) { WP_CLI::error( 'La opción actual no es una lista; no se escribe.' ); }
$receipt = get_option( $receipt_key, array() );
if ( 'rollback' === $mode ) {
 if ( empty( $receipt ) || 'applied' !== ( $receipt['status'] ?? '' ) ) { WP_CLI::error( 'No hay aplicación activa de este lote para revertir.' ); }
 if ( $before !== $receipt['after'] ) { WP_CLI::error( 'Hay ediciones posteriores: rollback automático bloqueado para conservarlas.' ); }
 update_option( $key, $receipt['before'], false );
 $receipt['status'] = 'rolled-back';
 update_option( $receipt_key, $receipt, false );
 WP_CLI::success( 'Solo comunicados restaurados; páginas, otros datos y media intactos.' );
 return;
}
$manifest = json_decode( file_get_contents( dirname( __DIR__ ) . '/content/comunicados-octubre-2026.json' ), true );
if ( 'comunicados-octubre-2026-v1' !== ( $manifest['version'] ?? '' ) || 8 !== count( $manifest['statements'] ?? array() ) ) { WP_CLI::error( 'Manifiesto inválido.' ); }
$normalize = static fn( $title ) => preg_replace( '/[^a-z0-9]+/', '', strtolower( remove_accents( $title ) ) );
$file_id = static function ( $url ) { return preg_match( '~/file/d/([^/]+)~', $url, $matches ) ? $matches[1] : ''; };
$identities = array(); $urls = array();
foreach ( $manifest['statements'] as $item ) {
 if ( ! isset( $item['title'], $item['year'], $item['url'] ) || ! in_array( $item['year'], array( 2025, 2024, 2023, 2022 ), true ) || ! preg_match( '~^https://drive\.google\.com/file/d/[A-Za-z0-9_-]+/view\?usp=(drive_link|sharing)$~', $item['url'] ) ) { WP_CLI::error( 'Entrada inválida; sin escrituras.' ); }
 $identities[] = $normalize( $item['title'] );
 $urls[] = $file_id( $item['url'] );
}
if ( count( array_unique( $identities ) ) !== 8 || count( array_unique( $urls ) ) !== 8 ) { WP_CLI::error( 'Manifiesto duplicado.' ); }
$after = array();
foreach ( $before as $item ) {
 if ( ! is_array( $item ) || ! isset( $item['title'], $item['year'] ) ) { WP_CLI::error( 'Entrada previa inválida; no se altera.' ); }
 if ( in_array( $normalize( $item['title'] ), $identities, true ) || in_array( $file_id( $item['url'] ?? '' ), $urls, true ) ) { continue; }
 $after[] = $item; // Conservar todos los demás comunicados.
}
$after = array_merge( $after, $manifest['statements'] );
usort( $after, static fn( $a, $b ) => (int) $b['year'] <=> (int) $a['year'] );
if ( $after === $before ) { WP_CLI::success( 'El lote ya está aplicado: cero escrituras, cero duplicados.' ); return; }
WP_CLI::log( count( $before ) . ' entradas → ' . count( $after ) . '; solo ocho entradas canónicas, otros comunicados conservados.' );
if ( 'dry-run' === $mode ) { WP_CLI::success( 'Dry-run: ninguna escritura.' ); return; }
if ( 'applied' === ( $receipt['status'] ?? '' ) && $before !== $receipt['after'] ) { WP_CLI::error( 'Ediciones posteriores detectadas: revisar antes de reaplicar.' ); }
$receipt = array( 'version' => $manifest['version'], 'status' => 'applied', 'date' => gmdate( 'c' ), 'before' => $before, 'after' => $after );
update_option( $receipt_key, $receipt, false ); // Respaldo anterior a la escritura de contenido.
update_option( $key, $after, false );
if ( get_option( $key ) !== $after ) { WP_CLI::error( 'No se pudo verificar la escritura; respaldo conservado.' ); }
WP_CLI::success( 'Comunicados actualizados. No se modificaron posts, enlaces editoriales, adjuntos ni uploads.' );
