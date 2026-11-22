<?php
/** Set only the Hay Otra Forma – Maltrato card destination. Run with WP-CLI eval-file. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }

$mode = $args[0] ?? 'dry-run';
if ( ! in_array( $mode, array( 'dry-run', 'apply' ), true ) ) { WP_CLI::error( 'Usar dry-run o apply.' ); }

$origin = untrailingslashit( home_url() );
$local = in_array( wp_parse_url( $origin, PHP_URL_HOST ), array( 'localhost', '127.0.0.1' ), true );
if ( ! $local && ( 'https://ddna.com.ar' !== $origin || 'APROBADO PARA PUBLICAR' !== getenv( 'DDNA_CAMPAIGN_LINK_APPROVAL' ) ) ) {
	WP_CLI::error( 'Destino productivo no autorizado.' );
}

$page = get_page_by_path( 'hay-otra-forma-prevencion-maltrato', OBJECT, 'page' );
if ( ! $page || 'publish' !== $page->post_status ) { WP_CLI::error( 'La página interna de Maltrato no está publicada.' ); }
$target = get_permalink( $page );
if ( $target !== home_url( '/hay-otra-forma-prevencion-maltrato/' ) ) { WP_CLI::error( 'La URL canónica de Maltrato no coincide.' ); }

$campaign = get_page_by_path( 'hay-otra-forma-maltrato', OBJECT, 'campana' );
if ( ! $campaign && $local ) { $campaign = get_page_by_path( 'hay-otra-forma', OBJECT, 'campana' ); }
if ( ! $campaign || 'publish' !== $campaign->post_status ) { WP_CLI::error( 'La tarjeta de Maltrato no está publicada.' ); }
$current = get_post_meta( $campaign->ID, '_ddna_external_url', true );
if ( $current && $current !== $target ) { WP_CLI::error( 'La tarjeta ya tiene otro destino; no se sobrescribe.' ); }

$bullying = get_page_by_path( 'hay-otra-forma-bullying', OBJECT, 'campana' );
if ( ! $bullying || 'publish' !== $bullying->post_status ) { WP_CLI::error( 'Falta la tarjeta de Bullying.' ); }
$bullying_url = get_post_meta( $bullying->ID, '_ddna_external_url', true );
if ( ! $local && $bullying_url !== home_url( '/hay-otra-forma-prevencion-bullying/' ) ) { WP_CLI::error( 'El destino de Bullying no coincide con la referencia.' ); }

WP_CLI::log( 'Maltrato ID ' . $campaign->ID . ': ' . ( $current ?: '(vacío)' ) . ' → ' . $target );
if ( 'dry-run' === $mode || $current === $target ) { WP_CLI::success( $current === $target ? 'Enlace ya correcto. Cero escrituras.' : 'Dry-run correcto. Cero escrituras.' ); return; }

update_post_meta( $campaign->ID, '_ddna_external_url', esc_url_raw( $target ) );
if ( get_post_meta( $campaign->ID, '_ddna_external_url', true ) !== $target || ddna_theme_card_destination( $campaign->ID ) !== $target || get_post_meta( $bullying->ID, '_ddna_external_url', true ) !== $bullying_url ) {
	WP_CLI::error( 'No se pudo verificar la corrección puntual.' );
}
WP_CLI::success( 'Solo el destino de Maltrato fue corregido.' );
