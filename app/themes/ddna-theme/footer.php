<?php
/**
 * Pie global.
 *
 * @package DDNA_Theme
 */
?>
	<?php get_template_part( 'template-parts/footer/site-footer' ); ?>
</div>
<?php if ( is_front_page() ) : ?>
	<button class="back-to-top" type="button" aria-label="<?php esc_attr_e( 'Volver arriba', 'ddna-theme' ); ?>" title="<?php esc_attr_e( 'Volver arriba', 'ddna-theme' ); ?>" hidden><span aria-hidden="true">↑</span></button>
<?php endif; ?>
<?php wp_footer(); ?>
</body>
</html>
