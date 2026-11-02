<?php
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }
$program = get_page_by_path( 'acompanamiento-formacion-adultos', OBJECT, 'programa' );
$page = get_page_by_path( 'acompanamiento-formacion-adultos', OBJECT, 'page' );
if ( ! $program || ! $page ) { WP_CLI::error( 'Programa o página no encontrados.' ); }
if ( ! get_option( 'ddna_adult_page_backup_v1' ) ) { add_option( 'ddna_adult_page_backup_v1', array( 'post_id' => $page->ID, 'content' => $page->post_content, 'date' => current_time( 'mysql' ) ), '', false ); }
$id = wp_update_post( wp_slash( array( 'ID' => $page->ID, 'post_title' => 'ACOMPAÑAMIENTO Y FORMACIÓN PARA ADULTOS', 'post_content' => $program->post_content, 'post_status' => 'publish' ) ), true );
if ( is_wp_error( $id ) ) { WP_CLI::error( $id->get_error_message() ); }
WP_CLI::success( 'Página raíz sincronizada con el contenido editorial del Programa.' );
