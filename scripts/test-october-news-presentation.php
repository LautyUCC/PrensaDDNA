<?php
/** Read-only regression checks against existing posts; no fixture writes. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }
$fail = static function ( $ok, $message ) { if ( ! $ok ) { WP_CLI::error( $message ); } };
$sources = ddna_october_news_sources();
$ids = array();
foreach ( $sources as $source => $rank ) {
	$matches = get_posts( array( 'post_type' => 'post', 'post_status' => array_keys( get_post_stati() ), 'posts_per_page' => -1, 'fields' => 'ids', 'meta_key' => '_ddna_news_source_id', 'meta_value' => $source ) );
	$fail( count( $matches ) === 1, 'Identidad duplicada o ausente.' );
	$ids[ $matches[0] ] = $rank;
	$fail( false === strpos( get_post( $matches[0] )->post_content, '<p></strong>' ), 'Artefacto de importación.' );
}
$select = static function ( $args ) {
	$q = new WP_Query( array_merge( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => -1, 'fields' => 'ids', 'ignore_sticky_posts' => true, 'orderby' => 'date', 'order' => 'DESC' ), $args ) );
	return array_map( 'intval', $q->posts );
};
$cases = array( 'archive_home' => array( 'tag' => 'novedad' ), 'news_search' => array( 'tag' => 'novedad', 's' => 'agosto' ), 'global_search' => array( 's' => 'agosto', 'post_type' => 'any', 'orderby' => '' ), 'unrelated_listing' => array( 'post_type' => 'page' ), 'ascending' => array( 'tag' => 'novedad', 'order' => 'ASC' ) );
$results = array();
foreach ( $cases as $label => $args ) {
	remove_filter( 'posts_orderby', 'ddna_october_news_orderby', 20 );
	$before = $select( $args );
	add_filter( 'posts_orderby', 'ddna_october_news_orderby', 20, 2 );
	$after = $select( $args );
	$historical = static fn( $posts ) => array_values( array_filter( $posts, static fn( $id ) => ! isset( $ids[ $id ] ) ) );
	$fail( $historical( $before ) === $historical( $after ), 'Orden histórico alterado: ' . $label );
	$fail( count( array_diff( $before, $after ) ) === 0 && count( $before ) === count( $after ), 'Membresía alterada.' );
	if ( in_array( $label, array( 'ascending', 'unrelated_listing' ), true ) ) {
		$fail( $before === $after, 'Consulta ajena alterada.' );
	} else {
		$ranks = array_values( array_map( static fn( $id ) => $ids[ $id ], array_filter( $after, static fn( $id ) => isset( $ids[ $id ] ) ) ) );
		$sorted = $ranks; rsort( $sorted );
		$fail( $ranks === $sorted, 'Orden editorial incorrecto: ' . $label );
	}
	$results[ $label ] = array( 'count' => count( $after ), 'historical_order_preserved' => true );
}
$all = $select( array( 'tag' => 'novedad' ) );
foreach ( array( 5, 12 ) as $size ) {
	$paged = array();
	for ( $page = 1; $page <= ceil( count( $all ) / $size ); $page++ ) {
		$paged = array_merge( $paged, $select( array( 'tag' => 'novedad', 'posts_per_page' => $size, 'paged' => $page ) ) );
	}
	$fail( $paged === $all && count( $paged ) === count( array_unique( $paged ) ), 'Paginación inconsistente: ' . $size );
	$results[ 'pagination_' . $size ] = 'OK, sin omisiones ni duplicados';
}
echo wp_json_encode( $results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) . PHP_EOL;
