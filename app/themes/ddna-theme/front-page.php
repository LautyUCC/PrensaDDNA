<?php
/**
 * Portada del sitio.
 *
 * @package DDNA_Theme
 */

get_header();
?>
<main class="site-main" id="main-content">
	<?php get_template_part( 'template-parts/sections/hero' ); ?>
	<?php get_template_part( 'template-parts/sections/quick-access' ); ?>
	<?php get_template_part( 'template-parts/home/panels' ); ?>
	<?php get_template_part( 'template-parts/home/institutional-navigation' ); ?>
</main>
<?php
get_footer();
