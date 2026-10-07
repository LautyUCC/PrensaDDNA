<?php
/** Return to the stable lower Home navigation, even when opened directly. */
?>
<a class="return-home-menu" href="<?php echo esc_url( home_url( '/#menu-principal-home' ) ); ?>">
 <svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path d="M3 10 12 3l9 7v11h-6v-7H9v7H3Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
 <span><?php esc_html_e( 'Volver al menú', 'ddna-theme' ); ?></span>
</a>
