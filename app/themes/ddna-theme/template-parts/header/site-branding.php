<?php
/**
 * Identidad del sitio.
 *
 * @package DDNA_Theme
 */
?>
<div class="site-branding">
	<?php if ( has_custom_logo() ) : ?>
		<?php the_custom_logo(); ?>
	<?php endif; ?>
	<div class="site-branding__text">
		<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
		<?php if ( get_bloginfo( 'description', 'display' ) ) : ?>
			<p class="site-description"><?php bloginfo( 'description' ); ?></p>
		<?php endif; ?>
	</div>
</div>
