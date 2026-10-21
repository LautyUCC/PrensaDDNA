<?php
/** Presentation policy for the eight reviewed October sources; no content writes. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/** Stable source identities, independent of local/production WordPress IDs. */
function ddna_october_news_sources() {
	return array(
		'ddna-news-c4ac8c1d9bbb1d0a6222' => 4,
		'ddna-news-b944ba04a08c2be896a5' => 5,
		'ddna-news-867444ab5bd387eb767c' => 6,
		'ddna-news-77ea16e539bc2abc9ed3' => 7,
		'ddna-news-5e1ba134bba0a73077b9' => 8,
		'ddna-news-c5d76048ea58e82d8b9f' => 9,
		'ddna-news-354a7be7ecd45f7016da' => 10,
		'ddna-news-3135200c82d6b2e6d60f' => 11,
	);
}

function ddna_october_news_number( $post_id ) {
	$sources = ddna_october_news_sources();
	$source  = get_post_meta( $post_id, '_ddna_news_source_id', true );
	return 'post' === get_post_type( $post_id ) && isset( $sources[ $source ] ) ? $sources[ $source ] : 0;
}

add_filter( 'post_class', static function ( $classes, $css_class, $post_id ) {
	if ( ddna_october_news_number( $post_id ) ) {
		$classes[] = 'news-article--october-2026';
	}
	return $classes;
}, 10, 3 );

/**
 * Break publication-date ties by the supplied source number, before SQL LIMIT.
 * Dates stay authoritative. Other dates/order clauses and historical rows retain
 * their original ordering; no timestamps, menu_order or post metadata are changed.
 */
function ddna_october_news_orderby( $orderby, $query ) {
	if ( is_admin() || $query->is_singular() || 'ASC' === strtoupper( (string) $query->get( 'order' ) ) ) {
		return $orderby;
	}
	$is_news = 'novedad' === $query->get( 'tag' ) || $query->is_category( 'novedades' ) || $query->is_search();
	if ( ! $is_news ) { return $orderby; }
	global $wpdb;
	$date_order = "{$wpdb->posts}.post_date DESC";
	if ( false === strpos( $orderby, $date_order ) ) { return $orderby; }
	static $ranks = null;
	if ( null === $ranks ) {
		$sources = ddna_october_news_sources();
		$keys    = array_keys( $sources );
		$slots   = implode( ', ', array_fill( 0, count( $keys ), '%s' ) );
		$rows    = $wpdb->get_results( $wpdb->prepare(
			"SELECT post_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key = '_ddna_news_source_id' AND meta_value IN ($slots)",
			$keys
		) );
		$ranks = array();
		foreach ( $rows as $row ) {
			$ranks[ (int) $row->post_id ] = $sources[ $row->meta_value ];
		}
	}
	if ( ! $ranks ) { return $orderby; }
	$cases = '';
	foreach ( $ranks as $id => $rank ) {
		$cases .= sprintf( ' WHEN %d THEN %d', $id, $rank );
	}
	return str_replace( $date_order, $date_order . ", CASE {$wpdb->posts}.ID{$cases} ELSE 0 END DESC", $orderby );
}
add_filter( 'posts_orderby', 'ddna_october_news_orderby', 20, 2 );
