<?php
/** Local integration suite, using real CPT/save hooks/REST/roles, cleans its test records. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }
if ( 'local' !== wp_get_environment_type() || ! in_array( wp_parse_url( home_url(), PHP_URL_HOST ), array( 'localhost', '127.0.0.1', '::1' ), true ) ) { WP_CLI::error( 'Tests solo locales.' ); }
require_once ABSPATH . 'wp-admin/includes/user.php';
$ids = array(); $users = array(); $original_user = get_current_user_id(); $original_post = $_POST; $checks = 0;
$check = static function ( $condition, $label ) use ( &$checks ) { if ( ! $condition ) { throw new RuntimeException( $label ); } $checks++; WP_CLI::log( 'OK: ' . $label ); };
$year = (int) current_datetime()->format( 'Y' );
try {
 $type = get_post_type_object( 'agenda_evento' ); $check( $type && $type->show_ui && $type->show_in_rest && ! $type->has_archive, 'CPT nativo sin conflicto con /agenda' );
 $check( false === apply_filters( 'use_block_editor_for_post_type', true, 'agenda_evento' ), 'Formulario nativo único para fecha/hora/lugar' );
 foreach ( array( 'administrator', 'editor', 'subscriber' ) as $role ) {
  $uid = wp_insert_user( array( 'user_login' => 'agenda-test-' . $role . '-' . wp_generate_password( 8, false ), 'user_pass' => wp_generate_password( 30 ), 'role' => $role ) );
  if ( is_wp_error( $uid ) ) { throw new RuntimeException( $uid->get_error_message() ); } $users[] = $uid;
  wp_set_current_user( $uid ); $check( current_user_can( $type->cap->publish_posts ) === ( 'subscriber' !== $role ), 'Permiso de publicación: ' . $role );
 }
 wp_set_current_user( $users[0] );
 $valid = array( '_agenda_fecha' => $year . '-10-05', '_agenda_hora' => '15:30', '_agenda_lugar' => 'Centro <b>Vecinal</b>' );
 $id = wp_insert_post( array( 'post_type' => 'agenda_evento', 'post_title' => 'TEST agenda validación', 'post_content' => '<p>Descripción pública</p><script>alert(1)</script>', 'post_status' => 'draft' ) ); $ids[] = $id;
 $_POST = array( 'ddna_core_meta_nonce' => wp_create_nonce( 'ddna_core_save_meta' ), 'ddna_core_fields' => $valid ); ddna_core_save_meta_box( $id );
 $check( get_post_meta( $id, '_agenda_fecha', true ) === $valid['_agenda_fecha'] && get_post_meta( $id, '_agenda_hora', true ) === '15:30' && get_post_meta( $id, '_agenda_lugar', true ) === 'Centro Vecinal', 'Guardado nonce/capability/sanitización' );
 $_POST['ddna_core_meta_nonce'] = 'invalid'; $_POST['ddna_core_fields']['_agenda_hora'] = '12:00'; ddna_core_save_meta_box( $id );
 $check( get_post_meta( $id, '_agenda_hora', true ) === '15:30', 'Nonce inválido no escribe' );
 $_POST['ddna_core_meta_nonce'] = wp_create_nonce( 'ddna_core_save_meta' ); wp_set_current_user( $users[2] ); ddna_core_save_meta_box( $id );
 $check( get_post_meta( $id, '_agenda_hora', true ) === '15:30', 'Usuario sin permiso no escribe' ); wp_set_current_user( $users[0] ); $_POST = array();
 foreach ( array( '2026-02-30', '2026-13-01', '2026-2-01', array( '2026-10-01' ) ) as $date ) { $f = $valid; $f['_agenda_fecha'] = $date; $check( is_wp_error( ddna_core_agenda_validate_fields( $f ) ), 'Fecha inválida rechazada' ); }
 $check( ! is_wp_error( ddna_core_agenda_validate_fields( array_merge( $valid, array( '_agenda_fecha' => '2028-02-29' ) ) ) ), 'Fecha bisiesta válida' );
 foreach ( array( '24:00', '12:60', '9:00' ) as $time ) { $check( is_wp_error( ddna_core_agenda_validate_fields( array_merge( $valid, array( '_agenda_hora' => $time ) ) ) ), 'Hora inválida rechazada' ); }
 wp_update_post( array( 'ID' => $id, 'post_status' => 'publish' ) );
 foreach ( array( array( '09:00', 'publish', $year ), array( '07:00', 'draft', $year ), array( '08:00', 'publish', $year - 1 ) ) as $row ) {
  $new = wp_insert_post( array( 'post_type' => 'agenda_evento', 'post_title' => 'TEST agenda ' . $row[0], 'post_status' => $row[1], 'meta_input' => array( '_agenda_fecha' => $row[2] . '-10-05', '_agenda_hora' => $row[0], '_agenda_lugar' => 'Sede' ) ) ); $ids[] = $new;
 }
 $secret = wp_insert_post( array( 'post_type' => 'agenda_evento', 'post_title' => 'TEST password', 'post_status' => 'publish', 'post_password' => wp_generate_password( 16 ), 'meta_input' => $valid ) ); $ids[] = $secret;
 $events = ddna_core_agenda_events( $year, 10 ); $test_events = array_values( array_filter( $events, static fn( $e ) => in_array( $e['id'], $ids, true ) ) );
 $check( array_column( $test_events, 'time' ) === array( '09:00', '15:30' ), 'Rango de año/mes, drafts y contraseñas excluidos, orden horario' );
 $check( ! str_contains( $test_events[1]['description'], '<script' ), 'Descripción pública filtrada con wp_kses_post' );
 wp_set_current_user( 0 ); $request = new WP_REST_Request( 'GET', '/ddna/v1/agenda' ); $request->set_query_params( array( 'year' => $year, 'month' => 10 ) ); $response = rest_do_request( $request );
 $check( 200 === $response->get_status() && $response->get_data()['timezone'] === wp_timezone_string(), 'Endpoint público, zona horaria WP' );
 $check( array_keys( $response->get_data()['events'][0] ) === array( 'id', 'title', 'date', 'time', 'location', 'description' ), 'API expone solo seis campos públicos' );
 $request->set_query_params( array( 'year' => $year - 1, 'month' => 10 ) ); $check( 400 === rest_do_request( $request )->get_status(), 'UI/API no salen del año actual' );
 $request->set_query_params( array( 'year' => $year, 'month' => 13 ) ); $check( 400 === rest_do_request( $request )->get_status(), 'Mes fuera de rango rechazado' );
 $write = new WP_REST_Request( 'POST', '/wp/v2/agenda-eventos' ); $write->set_body_params( array( 'title' => 'TEST unauthorized', 'status' => 'publish', 'meta' => $valid ) ); $check( 401 === rest_do_request( $write )->get_status(), 'Anónimo no crea eventos' );
 $native_read = new WP_REST_Request( 'GET', '/wp/v2/agenda-eventos' ); $check( 401 === rest_do_request( $native_read )->get_status(), 'Anónimo no accede a los campos internos de la API editorial' );
 wp_set_current_user( $users[2] ); $check( 403 === rest_do_request( $write )->get_status(), 'Suscriptor no crea eventos' );
 $check( 403 === rest_do_request( $native_read )->get_status(), 'Suscriptor no accede a la API editorial' );
 wp_set_current_user( $users[1] ); $write->set_body_params( array( 'title' => 'TEST REST', 'content' => 'Descripción de prueba', 'status' => 'publish', 'meta' => array_merge( $valid, array( '_agenda_fecha' => $year . '-02-30' ) ) ) ); $check( 400 === rest_do_request( $write )->get_status(), 'REST rechaza fecha imposible antes de escribir' );
 $write->set_body_params( array( 'title' => 'TEST REST', 'content' => 'Descripción de prueba', 'status' => 'publish', 'meta' => $valid ) ); $created = rest_do_request( $write ); $check( 201 === $created->get_status(), 'Editor crea evento vía REST con campos válidos' ); $ids[] = $created->get_data()['id'];
 $check( 200 === rest_do_request( $native_read )->get_status(), 'Editor conserva lectura de la API editorial' );
 $write->set_body_params( array( 'title' => 'TEST incompleto', 'content' => '', 'status' => 'publish', 'meta' => $valid ) );
 $check( 400 === rest_do_request( $write )->get_status(), 'REST exige descripción antes de publicar' );
 $_POST = array( 'ddna_core_meta_nonce' => wp_create_nonce( 'ddna_core_save_meta' ), 'ddna_core_fields' => array_merge( $valid, array( '_agenda_fecha' => $year . '-02-30' ) ) );
 wp_update_post( array( 'ID' => $id, 'post_status' => 'publish' ) );
 $check( 'draft' === get_post_status( $id ) && get_post_meta( $id, '_agenda_fecha', true ) === $valid['_agenda_fecha'], 'Formulario inválido queda en borrador y conserva la fecha anterior' );
 $_POST = array( 'ddna_core_meta_nonce' => wp_create_nonce( 'ddna_core_save_meta' ), 'ddna_core_fields' => $valid );
 wp_update_post( array( 'ID' => $id, 'post_status' => 'publish', 'post_content' => '' ) );
 $check( 'draft' === get_post_status( $id ), 'Formulario exige descripción para publicar' ); $_POST = array();
 foreach ( array( 1, 12 ) as $boundary ) {
  $request->set_query_params( array( 'year' => $year, 'month' => $boundary ) );
  $check( 200 === rest_do_request( $request )->get_status(), 'Mes límite válido: ' . $boundary );
 }
 WP_CLI::success( $checks . ' verificaciones Agenda pasaron.' );
} finally {
 $_POST = array(); wp_set_current_user( $users[0] ?? $original_user ); foreach ( $ids as $id ) { wp_delete_post( $id, true ); }
 foreach ( $users as $uid ) { delete_transient( 'ddna_agenda_notice_' . $uid ); wp_delete_user( $uid ); }
 $_POST = $original_post; wp_set_current_user( $original_user );
}
