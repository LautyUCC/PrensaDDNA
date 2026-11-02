<?php
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }
$post = get_page_by_path( 'acompanamiento-formacion-adultos', OBJECT, 'programa' );
if ( ! $post || 'publish' !== $post->post_status ) { WP_CLI::error( 'Programa no encontrado.' ); }
$content = '<p>Las personas adultas cumplen un rol central como garantes de derechos. Por eso ofrecemos charlas y formaciones para acompañarlas en la tarea de escuchar y cuidar.</p><h2>“La escucha activa de niñas, niños y adolescentes”</h2><p>Charla dictada en dos sedes: la Subsede Bell Ville y la Diplomatura en Derecho de Niñez y Adolescencia de la Facultad de Derecho de la UNC. Disertante: Lic. Silvana Piscitelli, de la DDNA.</p><h2>Mes de las Infancias: “La mirada que sostiene”</h2><p>Charla abierta para conversar sobre escucha activa, validación emocional y el derecho a ser mirados y oídos. Disertante: Lic. Beatriz Janin.</p><h2>Mes del Adolescente: “Lo que no dicen las palabras”</h2><p>Charla abierta sobre señales, vínculos y violencia en la adolescencia, y sobre qué podemos hacer las personas adultas. Disertante: Lic. María Zysman.</p>';
if ( ! get_option( 'ddna_adult_training_content_backup_v1' ) ) { add_option( 'ddna_adult_training_content_backup_v1', array( 'post_id' => $post->ID, 'content' => $post->post_content, 'date' => current_time( 'mysql' ) ), '', false ); }
$id = wp_update_post( wp_slash( array( 'ID' => $post->ID, 'post_content' => $content ) ), true );
if ( is_wp_error( $id ) ) { WP_CLI::error( $id->get_error_message() ); }
WP_CLI::success( 'Contenido actualizado en acompanamiento-formacion-adultos.' );
