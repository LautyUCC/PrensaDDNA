<?php
/** Rename only the existing statements page; no changes to historical media or statements. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }
$local = 'local' === wp_get_environment_type() && in_array( wp_parse_url( home_url(), PHP_URL_HOST ), array( 'localhost', '127.0.0.1', '::1' ), true );
if ( ! $local && ( 'APROBADO PARA PUBLICAR AJUSTES ADICIONALES' !== getenv( 'DDNA_ADDITIONAL_APPROVAL' ) || untrailingslashit( home_url() ) !== getenv( 'DDNA_ADDITIONAL_TARGET_ORIGIN' ) ) ) { WP_CLI::error( 'Fuera de local requiere aprobación de este lote y origen exacto.' ); }
$mode = getenv( 'DDNA_ADDITIONAL_MODE' ) ?: 'dry-run';
if ( ! in_array( $mode, array( 'dry-run', 'apply', 'rollback' ), true ) ) { WP_CLI::error( 'Modo inválido.' ); }
$page = get_page_by_path( 'comunicados' );
if ( ! $page || 'publish' !== $page->post_status ) { WP_CLI::error( 'No existe la página Comunicados publicada; no crear duplicados.' ); }
$title = 'Comunicados y pronunciamientos';
$key = 'ddna_additional_adjustments_octubre_receipt';
$receipt = get_option( $key, array() );
if ( 'rollback' === $mode ) {
 if ( empty( $receipt ) || 'applied' !== ( $receipt['status'] ?? '' ) || $page->ID !== $receipt['id'] || $page->post_title !== $receipt['after'] ) { WP_CLI::error( 'Rollback bloqueado: falta receipt activo o hay ediciones posteriores.' ); }
 $result = wp_update_post( wp_slash( array( 'ID' => $page->ID, 'post_title' => $receipt['before'] ) ), true );
 if ( is_wp_error( $result ) ) { WP_CLI::error( $result->get_error_message() ); }
 $receipt['status'] = 'rolled-back'; update_option( $key, $receipt, false );
 WP_CLI::success( 'Solo título restaurado; slug y contenido intactos.' ); return;
}
if ( $page->post_title === $title ) { WP_CLI::success( 'Título ya aplicado; cero escrituras.' ); return; }
if ( 'Comunicados' !== $page->post_title ) { WP_CLI::error( 'Título actual distinto del auditado: revisar antes de sobrescribir.' ); }
if ( 'dry-run' === $mode ) { WP_CLI::success( 'Se renombraría /comunicados/, sin cambiar slug/contenido ni crear páginas.' ); return; }
$receipt = array( 'id' => $page->ID, 'before' => $page->post_title, 'after' => $title, 'date' => gmdate( 'c' ), 'status' => 'applied' );
$result = wp_update_post( wp_slash( array( 'ID' => $page->ID, 'post_title' => $title ) ), true );
if ( is_wp_error( $result ) ) { WP_CLI::error( $result->get_error_message() ); }
update_option( $key, $receipt, false );
WP_CLI::success( 'Solo título actualizado. Ocho comunicados, página histórica de medios y rutas conservadas.' );
