<?php
/** Reusable heading for a main expandable Home panel. @package DDNA_Theme */
$panel_id       = isset( $args['id'] ) ? sanitize_html_class( $args['id'] ) : '';
$panel_title    = isset( $args['title'] ) ? $args['title'] : '';
$panel_subtitle = isset( $args['subtitle'] ) ? $args['subtitle'] : '';
?>
<header class="ddna-folder__tab">
	<div>
		<h2 class="ddna-folder__title" id="panel-<?php echo esc_attr( $panel_id ); ?>-title"><?php echo esc_html( $panel_title ); ?></h2>
		<?php if ( $panel_subtitle ) : ?><p class="ddna-folder__subtitle"><?php echo esc_html( $panel_subtitle ); ?></p><?php endif; ?>
	</div>
	<button class="ddna-folder__close" type="button" data-home-panel-close aria-label="<?php echo esc_attr( sprintf( __( 'Cerrar %s', 'ddna-theme' ), $panel_title ) ); ?>"><span aria-hidden="true"></span></button>
</header>
