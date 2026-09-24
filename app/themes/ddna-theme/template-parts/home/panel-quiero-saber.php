<?php
/** Panel Quiero Saber. @package DDNA_Theme */

$knowledge_query = new WP_Query(
	array(
		'post_type' => array( 'documento', 'recurso' ), 'post_status' => 'publish',
		'posts_per_page' => -1, 'no_found_rows' => true,
		'update_post_meta_cache' => true, 'update_post_term_cache' => true,
	)
);
$knowledge_items = array();
foreach ( $knowledge_query->posts as $knowledge_post ) {
	$keys = array( sanitize_title( remove_accents( get_the_title( $knowledge_post ) ) ) );
	$year = get_post_meta( $knowledge_post->ID, '_ddna_year', true );
	if ( $year && has_term( 'informe-anual', 'tipo_documento', $knowledge_post ) ) {
		$keys[] = 'informe-anual-' . sanitize_key( $year );
	}
	foreach ( $keys as $key ) { $knowledge_items[ $key ] = $knowledge_post; }
}
wp_reset_postdata();

$resolve_item = static function ( array $aliases ) use ( $knowledge_items ) {
	foreach ( $aliases as $alias ) {
		$key = sanitize_title( remove_accents( $alias ) );
		if ( isset( $knowledge_items[ $key ] ) ) {
			$post = $knowledge_items[ $key ];
			$file_id = absint( get_post_meta( $post->ID, '_ddna_file_id', true ) );
			$url = $file_id ? wp_get_attachment_url( $file_id ) : '';
			$url = $url ? $url : get_post_meta( $post->ID, '_ddna_external_url', true );
			return $url ? $url : get_permalink( $post );
		}
	}
	return '';
};
$theme_icon = static function ( $path ) { return get_template_directory_uri() . '/assets/icons/knowledge/' . ltrim( $path, '/' ); };
$render_item = static function ( array $item, $variant = 'document' ) use ( $resolve_item, $theme_icon ) {
	$url = $resolve_item( array_merge( array( $item['label'] ), $item['aliases'] ?? array() ) );
	$tag = $url ? 'a' : 'span';
	$attributes = $url ? ' href="' . esc_url( $url ) . '"' : ' aria-disabled="true" title="' . esc_attr__( 'Contenido pendiente de carga en WordPress', 'ddna-theme' ) . '"';
	?>
	<<?php echo esc_html( $tag ); ?> class="knowledge-item knowledge-item--<?php echo esc_attr( $variant ); ?><?php echo $url ? '' : ' is-unavailable'; ?>"<?php echo $attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<img class="knowledge-item__icon" src="<?php echo esc_url( $theme_icon( $item['icon'] ) ); ?>" alt="" width="96" height="96" decoding="async">
		<span class="knowledge-item__label"><?php echo esc_html( $item['label'] ); ?></span>
		<?php if ( ! $url ) : ?><span class="screen-reader-text"><?php esc_html_e( 'Pendiente de publicación', 'ddna-theme' ); ?></span><?php endif; ?>
	</<?php echo esc_html( $tag ); ?>>
	<?php
};

$prevention_guides = array(
	array( 'label' => 'Guía de Navegación Segura', 'icon' => 'guides/guide-47.png' ),
	array( 'label' => 'Guía de Juegos en Línea', 'icon' => 'guides/guide-48.png' ),
	array( 'label' => 'Guía sobre abuso sexual hacia NNyA', 'icon' => 'guides/guide-49.png' ),
	array( 'label' => 'Guía sobre Bullying', 'icon' => 'guides/guide-50.png' ),
);
$care_guides = array(
	array( 'label' => 'Juegos en Línea', 'icon' => 'guides/guide-47.png' ),
	array( 'label' => 'Apuestas en Línea', 'icon' => 'guides/guide-51.png' ),
	array( 'label' => 'Acompañar a NNyA en Entornos Virtuales', 'icon' => 'guides/guide-52.png' ),
	array( 'label' => 'Prevención del Abuso', 'icon' => 'guides/guide-53.png' ),
	array( 'label' => 'Prevención del Bullying', 'icon' => 'guides/guide-54.png' ),
	array( 'label' => 'Entornos Seguros y Libres de Violencia hacia NNyA', 'icon' => 'guides/guide-55.png' ),
	array( 'label' => 'Hablemos de Crianza', 'icon' => 'guides/guide-56.png' ),
	array( 'label' => '¿Cómo acompañar la prevención del consumo de sustancias?', 'icon' => 'guides/guide-57.png' ),
);
?>
<section class="home-panel" id="panel-quiero-saber" data-home-panel="quiero-saber" aria-label="<?php esc_attr_e( 'Quiero saber', 'ddna-theme' ); ?>" hidden>
	<div class="container container--content">
		<div class="knowledge-panel">
			<section class="home-panel__content knowledge-block" aria-labelledby="knowledge-prevention-title"><h2 class="home-panel__section-title" id="knowledge-prevention-title">Guías para la Prevención</h2><div class="knowledge-guide-grid knowledge-guide-grid--four"><?php foreach ( $prevention_guides as $item ) { $render_item( $item, 'guide' ); } ?></div></section>
			<section class="home-panel__content knowledge-block" aria-labelledby="knowledge-care-title"><h2 class="home-panel__section-title" id="knowledge-care-title">Guías para una Crianza Cuidada</h2><div class="knowledge-guide-grid knowledge-guide-grid--care"><?php foreach ( $care_guides as $item ) { $render_item( $item, 'guide' ); } ?></div></section>
			<section class="home-panel__content knowledge-block" aria-labelledby="knowledge-materials-title">
				<h2 class="home-panel__section-title" id="knowledge-materials-title">Materiales Gráficos Descargables</h2>
				<?php echo ddna_theme_resource_control( 'graphic_materials', 'Materiales Gráficos Descargables' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</section>
			<section class="home-panel__content knowledge-block" aria-labelledby="knowledge-resources-title">
				<h2 class="home-panel__section-title" id="knowledge-resources-title">Recursos Didácticos</h2>
				<a class="button" href="https://youtube.com/playlist?list=PLXdxSIZhcTKwT-P10dblC2tN3dKWRXXxg&amp;si=Il_0hYxrd_vQ-TTH" target="_blank" rel="noopener noreferrer">Recursos Didácticos<span class="screen-reader-text"> (abre en otra pestaña)</span></a>
			</section>
		</div>
	</div>
</section>
