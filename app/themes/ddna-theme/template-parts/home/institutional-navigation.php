<?php
/** Institutional navigation before the footer. @package DDNA_Theme */
$defensoria = get_page_by_path( 'defensoria' );
$contacto   = get_page_by_path( 'contacto' );
$items      = array(
	array( 'label' => 'La Defensoría', 'url' => $defensoria ? get_permalink( $defensoria ) : home_url( '/defensoria/' ), 'icon' => 27 ),
	array( 'label' => 'Normativas', 'url' => add_query_arg( 'tipo_documento', 'normativa', get_post_type_archive_link( 'documento' ) ?: home_url( '/biblioteca/' ) ), 'icon' => 29 ),
	array( 'label' => 'Convenios', 'url' => add_query_arg( 'tipo_documento', 'convenio', get_post_type_archive_link( 'documento' ) ?: home_url( '/biblioteca/' ) ), 'icon' => 31 ),
	array( 'label' => 'Contacto', 'url' => $contacto ? get_permalink( $contacto ) : home_url( '/contacto/' ), 'icon' => 33 ),
);
$icon_uri = get_template_directory_uri() . '/assets/icons/institutional/';
?>
<nav class="institutional-navigation" aria-label="<?php esc_attr_e( 'Navegación institucional', 'ddna-theme' ); ?>">
	<ul class="institutional-navigation__list" role="list">
		<?php foreach ( $items as $item ) : ?><li><a href="<?php echo esc_url( $item['url'] ); ?>"><img src="<?php echo esc_url( $icon_uri . 'institutional-' . $item['icon'] . '.png' ); ?>" alt="" width="350" height="321" loading="lazy" decoding="async"><span><?php echo esc_html( $item['label'] ); ?></span></a></li><?php endforeach; ?>
	</ul>
</nav>
