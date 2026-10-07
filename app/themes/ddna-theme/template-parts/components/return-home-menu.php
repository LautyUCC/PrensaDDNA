<?php
/** Return to the stable lower Home navigation, even when opened directly. */
$return_url = $args['url'] ?? home_url( '/#menu-principal-home' );
$return_label = $args['label'] ?? __( 'Volver al menú', 'ddna-theme' );
?>
<a class="return-home-menu" href="<?php echo esc_url( $return_url ); ?>">
 <svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path d="M3 10 12 3l9 7v11h-6v-7H9v7H3Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
 <span><?php echo esc_html( $return_label ); ?></span>
</a>
