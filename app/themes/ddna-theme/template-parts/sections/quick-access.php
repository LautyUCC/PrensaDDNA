<?php
/** Administrable quick links. @package DDNA_Theme */
$locations = get_nav_menu_locations();
$menu_id   = isset( $locations['quick_access'] ) ? $locations['quick_access'] : 0;
$items     = $menu_id ? wp_get_nav_menu_items( $menu_id ) : array();
if ( ! $items ) { return; }
?>
<nav class="quick-access section" aria-label="<?php esc_attr_e( 'Accesos rápidos', 'ddna-theme' ); ?>">
	<div class="container container--wide">
		<ul class="quick-access__grid" role="list">
			<?php foreach ( $items as $item ) : ?>
				<?php
				$icon = get_post_meta( $item->ID, '_ddna_quick_access_icon', true );
				if ( ! $icon ) {
					$icon = 'default';
					foreach ( (array) $item->classes as $class_name ) {
						if ( 0 === strpos( $class_name, 'icon-' ) ) {
							$icon = substr( $class_name, 5 );
						}
					}
				}
				?>
				<li><a class="quick-access-card" href="<?php echo esc_url( $item->url ); ?>"><?php get_template_part( 'template-parts/components/quick-access-icon', null, array( 'icon' => $icon ) ); ?><span><?php echo esc_html( $item->title ); ?></span></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
</nav>
