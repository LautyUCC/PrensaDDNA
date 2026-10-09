<?php
/** Review fixtures only. Never run on any remote/non-local WordPress. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }
if ( 'local' !== wp_get_environment_type() || ! in_array( wp_parse_url( home_url(), PHP_URL_HOST ), array( 'localhost', '127.0.0.1', '::1' ), true ) ) { WP_CLI::error( 'Fixtures exclusivamente locales.' ); }
$year = current_datetime()->format( 'Y' );
$rows = array(
 array( 'visita', 'Visita institucional — ejemplo local', '-10-05', '09:00', 'Centro Vecinal Alberdi' ),
 array( 'reunion', 'Reunión de equipo — ejemplo local', '-10-05', '15:30', 'Sede de la Defensoría' ),
 array( 'capacitacion', 'Capacitación — ejemplo local', '-10-17', '10:00', 'Auditorio institucional' ),
 array( 'jornada', 'Jornada territorial — ejemplo local', '-11-28', '11:00', 'Centro comunitario' ),
 array( 'enero', 'Actividad de enero — ejemplo local', '-01-15', '09:00', 'Sede institucional' ),
 array( 'diciembre', 'Actividad de diciembre — ejemplo local', '-12-15', '09:00', 'Sede institucional' ),
);
foreach ( $rows as $row ) {
 $slug = 'demo-agenda-' . $row[0]; $existing = get_page_by_path( $slug, OBJECT, 'agenda_evento' );
 if ( $existing ) { continue; }
 $id = wp_insert_post( array( 'post_type' => 'agenda_evento', 'post_name' => $slug, 'post_title' => $row[1], 'post_content' => '<p>Evento ficticio para revisar la Agenda local. No representa una actividad confirmada.</p>', 'post_status' => 'publish', 'meta_input' => array( '_agenda_fecha' => $year . $row[2], '_agenda_hora' => $row[3], '_agenda_lugar' => $row[4], '_ddna_agenda_demo' => 1 ) ), true );
 if ( is_wp_error( $id ) ) { WP_CLI::error( $id->get_error_message() ); }
}
WP_CLI::success( 'Seis ejemplos locales preparados; nunca deben publicarse en VPS.' );
