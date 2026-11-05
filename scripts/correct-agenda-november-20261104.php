<?php
/** Selective, repeatable correction of three existing November Agenda records.
 * Usage: wp eval-file scripts/correct-agenda-november-20261104.php dry-run|apply|rollback
 */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }
$mode = $args[0] ?? 'dry-run';
if ( ! in_array( $mode, array( 'dry-run', 'apply', 'rollback' ), true ) ) { WP_CLI::error( 'Modo inválido.' ); }
$origin = untrailingslashit( home_url() );
$local = 'local' === wp_get_environment_type() && in_array( wp_parse_url( $origin, PHP_URL_HOST ), array( 'localhost', '127.0.0.1', '::1' ), true );
if ( ! $local && ( 'https://ddna.com.ar' !== $origin || 'APROBADO PARA PUBLICAR' !== getenv( 'DDNA_AGENDA_CORRECTION_APPROVAL' ) ) ) { WP_CLI::error( 'Origen o aprobación inválidos.' ); }
$manifest_path = getenv( 'DDNA_AGENDA_CORRECTION_MANIFEST' ) ?: dirname( __DIR__ ) . '/content/agenda-dossier-october-2026.json';
$manifest = json_decode( file_get_contents( $manifest_path ), true );
if ( 'agenda-dossier-october-2026' !== ( $manifest['version'] ?? '' ) || 33 !== count( $manifest['events'] ?? array() ) ) { WP_CLI::error( 'Manifiesto inesperado.' ); }
$specs = array(
	'mesa' => array( 'source' => '2026-11-05-mesa-provincial-primera-infancia', 'title' => 'Mesa Provincial de Primera Infancia junto a UNICEF Argentina.', 'before' => '2026-11-05', 'after' => null ),
	'prunape' => array( 'source' => '2026-11-12-jornada-municipios-prunape', 'title' => 'Jornada de Trabajo con Municipios Pre Prunape.', 'before' => '2026-11-12', 'after' => '2026-11-13' ),
	'conectamente' => array( 'source' => '2026-11-01-taller-cibercrianza-conectamente', 'title' => 'Taller para adultos sobre Cibercrianza en el festival infantil “Conectamente”.', 'before' => '2026-11-01', 'after' => '2026-11-29' ),
);
$source_rows = array_column( $manifest['events'], null, 'id' );
foreach ( $specs as $spec ) {
	$row = $source_rows[ $spec['source'] ] ?? null;
	if ( null === $spec['after'] ? null !== $row : ( ! $row || $row['date'] !== $spec['after'] || $row['title'] !== $spec['title'] ) ) { WP_CLI::error( 'Fuente de Agenda incoherente: ' . $spec['source'] ); }
}
$all_posts = static fn() => get_posts( array( 'post_type' => 'agenda_evento', 'post_status' => array_keys( get_post_stati() ), 'posts_per_page' => -1, 'suppress_filters' => true ) );
$decode = static fn( $value ) => html_entity_decode( (string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
$locate = static function ( $posts, $spec ) use ( $decode ) {
	return array_values( array_filter( $posts, static function ( $post ) use ( $spec, $decode ) {
		return get_post_meta( $post->ID, '_ddna_agenda_source_id', true ) === $spec['source'] || $decode( $post->post_title ) === $spec['title'];
	} ) );
};
$fingerprint_others = static function ( $posts ) use ( $specs, $locate ) {
	$target_ids = array();
	foreach ( $specs as $spec ) { foreach ( $locate( $posts, $spec ) as $post ) { $target_ids[ $post->ID ] = true; } }
	$other = array();
	foreach ( $posts as $post ) {
		if ( isset( $target_ids[ $post->ID ] ) ) { continue; }
		$date = get_post_meta( $post->ID, '_agenda_fecha', true );
		if ( $date < '2026-10-01' || $date > '2026-12-31' ) { continue; }
		$other[ $post->ID ] = array( 'post' => $post->to_array(), 'meta' => get_post_meta( $post->ID ) );
	}
	ksort( $other );
	return hash( 'sha256', wp_json_encode( $other ) );
};
$checkpoint_key = 'ddna_agenda_november_20261104_checkpoint';
if ( 'rollback' === $mode ) {
	$checkpoint = get_option( $checkpoint_key );
	if ( ! is_array( $checkpoint ) || 3 !== count( $checkpoint['records'] ?? array() ) ) { WP_CLI::error( 'Checkpoint ausente.' ); }
	foreach ( $specs as $key => $spec ) {
		$record = $checkpoint['records'][ $key ];
		$current = get_post( $record['post']['ID'] );
		if ( 'mesa' === $key ? $current : ( ! $current || get_post_meta( $current->ID, '_agenda_fecha', true ) !== $spec['after'] ) ) { WP_CLI::error( 'Estado posterior cambiado: ' . $key ); }
	}
	foreach ( $checkpoint['records'] as $key => $record ) {
		$id = (int) $record['post']['ID'];
		if ( 'mesa' === $key ) {
			$data = $record['post']; unset( $data['ID'] ); $data['import_id'] = $id;
			$restored = wp_insert_post( wp_slash( $data ), true );
			if ( is_wp_error( $restored ) || (int) $restored !== $id ) { WP_CLI::error( 'Restauración falló: mesa.' ); }
			foreach ( $record['meta'] as $name => $values ) { foreach ( $values as $value ) { add_post_meta( $id, $name, maybe_unserialize( $value ) ); } }
		} else { update_post_meta( $id, '_agenda_fecha', $record['date'] ); }
	}
	delete_option( $checkpoint_key );
	WP_CLI::success( 'Rollback selectivo aplicado.' ); return;
}
$posts = $all_posts(); $plan = array(); $records = array();
foreach ( $specs as $key => $spec ) {
	$matches = $locate( $posts, $spec );
	if ( count( $matches ) > 1 || ( 'mesa' !== $key && 1 !== count( $matches ) ) ) { WP_CLI::error( 'Identidad ausente o duplicada: ' . $key ); }
	if ( ! $matches ) { $plan[ $key ] = array( 'action' => 'none', 'date' => null ); continue; }
	$post = $matches[0]; $date = get_post_meta( $post->ID, '_agenda_fecha', true );
	if ( 'publish' !== $post->post_status || $decode( $post->post_title ) !== $spec['title'] || get_post_meta( $post->ID, '_ddna_agenda_source_id', true ) !== $spec['source'] || ! in_array( $date, array_filter( array( $spec['before'], $spec['after'] ) ), true ) ) { WP_CLI::error( 'Registro inesperado: ' . $key ); }
	$action = 'mesa' === $key ? 'delete' : ( $date === $spec['after'] ? 'none' : 'move' );
	$plan[ $key ] = array( 'action' => $action, 'id' => $post->ID, 'date' => $date, 'target' => $spec['after'] );
	$records[ $key ] = array( 'post' => $post->to_array(), 'meta' => get_post_meta( $post->ID ), 'date' => $date );
}
$affected = count( array_filter( $plan, static fn( $item ) => 'none' !== $item['action'] ) );
if ( 'apply' === $mode && $affected ) {
	if ( get_option( $checkpoint_key ) ) { WP_CLI::error( 'Checkpoint previo existente; revisar antes de aplicar.' ); }
	$before_others = $fingerprint_others( $posts );
	if ( ! add_option( $checkpoint_key, array( 'origin' => $origin, 'records' => $records, 'others_sha256' => $before_others ), '', false ) ) { WP_CLI::error( 'No se pudo guardar checkpoint.' ); }
	foreach ( $plan as $key => $item ) {
		if ( 'delete' === $item['action'] ) {
			if ( ! wp_delete_post( $item['id'], true ) ) { WP_CLI::error( 'No se pudo eliminar el evento.' ); }
		} elseif ( 'move' === $item['action'] ) {
			if ( ! update_post_meta( $item['id'], '_agenda_fecha', $item['target'], $item['date'] ) ) { WP_CLI::error( 'No se pudo mover: ' . $key ); }
		}
	}
	if ( $before_others !== $fingerprint_others( $all_posts() ) ) { WP_CLI::error( 'Otro evento cambió; revisar checkpoint.' ); }
}
echo wp_json_encode( array( 'mode' => $mode, 'affected' => $affected, 'plan' => $plan ), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) . PHP_EOL;
