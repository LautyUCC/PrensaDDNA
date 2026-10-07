<?php
/** Keep the native Novedades archive query, search and pagination bounded. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
add_action( 'pre_get_posts', static function ( $query ) {
 if ( is_admin() || ! $query->is_main_query() || ! $query->is_category( 'novedades' ) ) { return; }
 $query->set( 'posts_per_page', 12 );
 $query->set( 'ignore_sticky_posts', true );
 $query->set( 'orderby', array( 'date' => 'DESC', 'ID' => 'DESC' ) );
 $query->set( 'order', 'DESC' );
 if ( isset( $_GET['buscar'] ) && is_scalar( $_GET['buscar'] ) ) {
  $query->set( 's', sanitize_text_field( wp_unslash( $_GET['buscar'] ) ) );
 }
} );
add_filter( 'get_the_archive_title', static fn( $title ) => is_category( 'novedades' ) ? __( 'Novedades', 'ddna-theme' ) : $title );
