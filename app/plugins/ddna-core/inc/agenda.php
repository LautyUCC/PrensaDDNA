<?php
/** Institutional events: native editorial fields and a bounded public read API. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'init', static function () {
 register_post_type( 'agenda_evento', array(
  'labels' => array_merge( ddna_core_post_type_labels( 'Evento', 'Eventos' ), array( 'name' => 'Agenda', 'menu_name' => 'Agenda', 'all_items' => 'Todos los eventos', 'search_items' => 'Buscar eventos' ) ), 'show_ui' => true,
  'show_in_rest' => true, 'rest_base' => 'agenda-eventos', 'public' => false,
  'publicly_queryable' => false, 'exclude_from_search' => true, 'has_archive' => false,
  'rewrite' => false, 'menu_icon' => 'dashicons-calendar-alt', 'menu_position' => 21,
  'supports' => array( 'title', 'editor', 'author', 'revisions', 'custom-fields' ),
  'capability_type' => 'post', 'map_meta_cap' => true,
 ) );
} );

// One native form submits title, description and metabox fields together.
add_filter( 'use_block_editor_for_post_type', static fn( $use, $type ) => 'agenda_evento' === $type ? false : $use, 10, 2 );

/** Validated wall-clock fields; never convert ISO dates via UTC. */
function ddna_core_agenda_validate_fields( $fields, $required = true ) {
 foreach ( array( '_agenda_fecha' => 'date', '_agenda_hora' => 'time', '_agenda_lugar' => 'text' ) as $key => $type ) {
  $value = $fields[ $key ] ?? '';
  if ( ! is_scalar( $value ) || ( '' !== (string) $value && '' === ddna_core_sanitize_field( $value, $type ) ) || ( $required && '' === trim( (string) $value ) ) ) {
   return new WP_Error( 'agenda_invalid_fields', 'Completá una fecha válida, una hora de 00:00 a 23:59 y un lugar. El evento no se publicó.', array( 'status' => 400 ) );
  }
 }
 return true;
}

/** Classic/block metabox publishing: invalid fields retain previous values and save as draft. */
add_filter( 'wp_insert_post_data', static function ( $data, $postarr ) {
 if ( 'agenda_evento' !== $data['post_type'] || empty( $_POST['ddna_core_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ddna_core_meta_nonce'] ) ), 'ddna_core_save_meta' ) ) { return $data; }
 $id = (int) ( $postarr['ID'] ?? 0 );
 if ( ! current_user_can( $id ? 'edit_post' : 'edit_posts', $id ) ) { return $data; }
 $fields = isset( $_POST['ddna_core_fields'] ) && is_array( $_POST['ddna_core_fields'] ) ? wp_unslash( $_POST['ddna_core_fields'] ) : array();
 $valid = ddna_core_agenda_validate_fields( $fields, 'publish' === $data['post_status'] );
 if ( 'publish' === $data['post_status'] && ( '' === trim( wp_strip_all_tags( $data['post_title'] ) ) || '' === trim( wp_strip_all_tags( $data['post_content'] ) ) ) ) { $valid = new WP_Error( 'agenda_required_content', 'Completá título y descripción. El evento quedó en borrador.' ); }
 if ( is_wp_error( $valid ) ) { $data['post_status'] = 'draft'; set_transient( 'ddna_agenda_notice_' . get_current_user_id(), $valid->get_error_message(), 60 ); }
 return $data;
}, 10, 2 );
add_action( 'admin_notices', static function () {
 $key = 'ddna_agenda_notice_' . get_current_user_id(); $notice = get_transient( $key );
 if ( $notice ) { echo '<div class="notice notice-error"><p>' . esc_html( $notice ) . '</p></div>'; delete_transient( $key ); }
} );

/** Gutenberg/native REST writes validate before saving; role checks remain WordPress's. */
add_filter( 'rest_pre_insert_agenda_evento', static function ( $prepared, $request ) {
 if ( is_wp_error( $prepared ) ) { return $prepared; }
 $id = absint( $request['id'] ); $fields = array();
 foreach ( array( '_agenda_fecha', '_agenda_hora', '_agenda_lugar' ) as $key ) { $fields[ $key ] = $id ? get_post_meta( $id, $key, true ) : ''; }
 $meta = $request->get_param( 'meta' ); if ( is_array( $meta ) ) { $fields = array_merge( $fields, $meta ); }
 $status = $prepared->post_status ?? ( $id ? get_post_status( $id ) : 'draft' );
 $valid = ddna_core_agenda_validate_fields( $fields, 'publish' === $status );
 $title = $prepared->post_title ?? ( $id ? get_post_field( 'post_title', $id ) : '' );
 $description = $prepared->post_content ?? ( $id ? get_post_field( 'post_content', $id ) : '' );
 if ( 'publish' === $status && ( '' === trim( wp_strip_all_tags( $title ) ) || '' === trim( wp_strip_all_tags( $description ) ) ) ) { return new WP_Error( 'agenda_required_content', 'Completá título y descripción.', array( 'status' => 400 ) ); }
 return is_wp_error( $valid ) ? $valid : $prepared;
}, 10, 2 );

/** One monthly CPT query with batched meta; password-protected/draft events never leak. */
function ddna_core_agenda_events( $year, $month ) {
 $start = sprintf( '%04d-%02d-01', $year, $month );
 $end = ( new DateTimeImmutable( $start, wp_timezone() ) )->format( 'Y-m-t' );
 $query = new WP_Query( array(
  'post_type' => 'agenda_evento', 'post_status' => 'publish', 'has_password' => false,
  'posts_per_page' => 500, 'no_found_rows' => true, 'update_post_meta_cache' => true,
  'update_post_term_cache' => false, 'meta_query' => array(
   'date_clause' => array( 'key' => '_agenda_fecha', 'value' => array( $start, $end ), 'compare' => 'BETWEEN', 'type' => 'DATE' ),
   'time_clause' => array( 'key' => '_agenda_hora', 'compare' => 'EXISTS' ),
  ), 'orderby' => array( 'date_clause' => 'ASC', 'time_clause' => 'ASC', 'title' => 'ASC', 'ID' => 'ASC' ),
 ) );
 $events = array();
 foreach ( $query->posts as $post ) {
  $fields = array( '_agenda_fecha' => get_post_meta( $post->ID, '_agenda_fecha', true ), '_agenda_hora' => get_post_meta( $post->ID, '_agenda_hora', true ), '_agenda_lugar' => get_post_meta( $post->ID, '_agenda_lugar', true ) );
  if ( is_wp_error( ddna_core_agenda_validate_fields( $fields ) ) ) { continue; }
  $events[] = array( 'id' => $post->ID, 'title' => wp_specialchars_decode( get_the_title( $post ), ENT_QUOTES ), 'date' => $fields['_agenda_fecha'], 'time' => $fields['_agenda_hora'], 'location' => sanitize_text_field( $fields['_agenda_lugar'] ), 'description' => wp_kses_post( wpautop( strip_shortcodes( $post->post_content ) ) ) );
 }
 return $events;
}
add_action( 'rest_api_init', static function () {
 register_rest_route( 'ddna/v1', '/agenda', array(
  'methods' => WP_REST_Server::READABLE, 'permission_callback' => '__return_true',
  'args' => array(
   'year' => array( 'default' => (int) current_datetime()->format( 'Y' ), 'validate_callback' => static fn( $v ) => is_scalar( $v ) && preg_match( '/^\d{4}$/D', (string) $v ) && (int) $v === (int) current_datetime()->format( 'Y' ) ),
   'month' => array( 'default' => (int) current_datetime()->format( 'n' ), 'validate_callback' => static fn( $v ) => is_scalar( $v ) && preg_match( '/^(?:[1-9]|1[0-2])$/D', (string) $v ) ),
  ),
  'callback' => static function ( $request ) { return rest_ensure_response( array( 'year' => (int) $request['year'], 'month' => (int) $request['month'], 'today' => current_datetime()->format( 'Y-m-d' ), 'timezone' => wp_timezone_string(), 'events' => ddna_core_agenda_events( (int) $request['year'], (int) $request['month'] ) ) ); },
 ) );
} );

// The native editorial API includes author/meta fields. Public visitors use only
// ddna/v1/agenda; leave native reads to users who can edit this content.
add_filter( 'rest_pre_dispatch', static function ( $result, $server, $request ) {
 if ( in_array( $request->get_method(), array( 'GET', 'HEAD' ), true ) && preg_match( '~^/wp/v2/agenda-eventos(?:/\d+)?$~D', $request->get_route() ) && ! current_user_can( 'edit_posts' ) ) {
  return new WP_Error( 'rest_forbidden', 'Sin permiso para consultar la API editorial de Agenda.', array( 'status' => rest_authorization_required_code() ) );
 }
 return $result;
}, 10, 3 );
