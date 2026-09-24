<?php
/** Institutional navigation before the footer. @package DDNA_Theme */
$defensoria = get_page_by_path( 'defensoria' );
$informes   = get_page_by_path( 'informes-anuales' );
$contacto   = get_page_by_path( 'contacto' );
$normativas = get_page_by_path( 'normativas' );
$convenios = get_page_by_path( 'convenios' );
$icon_uri   = get_template_directory_uri() . '/assets/icons/institutional/';
$items      = array(
	array( 'label' => 'La Defensoría', 'url' => $defensoria ? get_permalink( $defensoria ) : home_url( '/defensoria/' ), 'icon' => $icon_uri . 'institutional-27.png' ),
	array( 'label' => 'Informes Anuales', 'url' => $informes ? get_permalink( $informes ) : home_url( '/informes-anuales/' ), 'icon' => get_template_directory_uri() . '/assets/icons/knowledge/documents/annual-report.png', 'icon_class' => 'institutional-navigation__icon--black' ),
	array( 'label' => 'Normativas', 'url' => $normativas ? get_permalink( $normativas ) : home_url( '/normativas/' ), 'icon' => $icon_uri . 'institutional-29.png' ),
	array( 'label' => 'Convenios', 'url' => $convenios ? get_permalink( $convenios ) : home_url( '/convenios/' ), 'icon' => $icon_uri . 'institutional-31.png' ),
	array( 'label' => 'Contacto', 'url' => $contacto ? get_permalink( $contacto ) : home_url( '/contacto/' ), 'icon' => $icon_uri . 'institutional-33.png' ),
);
?>
<nav class="institutional-navigation" aria-label="<?php esc_attr_e( 'Navegación institucional', 'ddna-theme' ); ?>">
	<ul class="institutional-navigation__list" role="list">
		<?php foreach ( $items as $item ) : ?><li><a href="<?php echo esc_url( $item['url'] ); ?>"><img class="<?php echo esc_attr( $item['icon_class'] ?? '' ); ?>" src="<?php echo esc_url( $item['icon'] ); ?>" alt="" width="350" height="321" loading="lazy" decoding="async"><span><?php echo esc_html( $item['label'] ); ?></span></a></li><?php endforeach; ?>
	</ul>
</nav>
