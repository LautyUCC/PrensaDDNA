<?php
/** Retira únicamente las cuatro noticias seed y los eventos demo identificados. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }
$local = 'local' === wp_get_environment_type() && in_array( wp_parse_url( home_url(), PHP_URL_HOST ), array( 'localhost', '127.0.0.1', '::1' ), true );
if ( ! $local && ( 'APROBADO PARA PUBLICAR' !== getenv( 'DDNA_NEWS_APPROVAL' ) || untrailingslashit( home_url() ) !== getenv( 'DDNA_NEWS_TARGET_ORIGIN' ) ) ) { WP_CLI::error( 'Requiere aprobación y origen exactos.' ); }
$mode = $args[0] ?? 'dry-run';
if ( ! in_array( $mode, array( 'dry-run', 'apply' ), true ) ) { WP_CLI::error( 'Modo inválido.' ); }
$news = array(
 array( 'muna', 'MUNA: Municipio Unido por la Niñez y la Adolescencia', 'La DDNA acompaña a municipios cordobeses comprometidos con la niñez y la adolescencia.' ),
 array( 'pronunciamiento-conjunto', 'Consideraciones sobre derechos de niñas, niños y adolescentes', 'Pronunciamiento conjunto para fortalecer la protección integral de derechos.' ),
 array( 'unicef-ddna-municipios', 'UNICEF y la DDNA buscan que más municipios se comprometan', 'Una iniciativa para poner a la niñez y la adolescencia en el centro de las políticas locales.' ),
 array( 'seminario-violencias', 'Seminario: abordajes de las violencias hacia NNyA', 'Reflexiones y aportes frente a la complejidad de las violencias.' ),
);
$targets = array();
foreach ( $news as $row ) {
 $post = get_page_by_path( $row[0], OBJECT, 'post' );
 if ( ! $post || 'trash' === $post->post_status ) { continue; }
 if ( $row[1] !== $post->post_title || $row[2] !== trim( wp_strip_all_tags( $post->post_content ) ) || get_post_meta( $post->ID, '_ddna_news_source_id', true ) ) { WP_CLI::error( 'La noticia seed fue editada; detener: ' . $row[0] ); }
 $targets[] = $post;
}
foreach ( get_posts( array( 'post_type' => 'agenda_evento', 'post_status' => 'any', 'posts_per_page' => -1 ) ) as $post ) {
 if ( ! get_post_meta( $post->ID, '_ddna_agenda_demo', true ) || ! str_starts_with( $post->post_name, 'demo-agenda-' ) ) { WP_CLI::error( 'Evento sin identificación de prueba; detener: ' . $post->ID ); }
 $targets[] = $post;
}
WP_CLI::log( 'Preflight: ' . count( $targets ) . ' contenidos de prueba identificados; medios conservados.' );
if ( 'dry-run' === $mode ) { WP_CLI::success( 'Sin escrituras.' ); return; }
$receipt = get_option( 'ddna_demo_retirement_receipt', array() );
foreach ( $targets as $post ) {
 $receipt[ $post->ID ] = array( 'id' => $post->ID, 'slug' => $post->post_name, 'title' => $post->post_title, 'type' => $post->post_type, 'previous_status' => $post->post_status, 'retired_at' => current_time( 'mysql' ) );
 update_option( 'ddna_demo_retirement_receipt', $receipt, false );
 if ( ! wp_trash_post( $post->ID ) ) { WP_CLI::error( 'No se pudo retirar: ' . $post->ID ); }
 WP_CLI::log( 'Retirada: ' . $post->ID . ' — ' . $post->post_title );
}
WP_CLI::success( count( $targets ) . ' contenidos retirados a papelera. Recibo: ddna_demo_retirement_receipt.' );
