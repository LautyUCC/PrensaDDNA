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
	<?php get_template_part( 'template-parts/sections/programs' ); ?>
	<?php get_template_part( 'template-parts/sections/campaigns' ); ?>
	<?php get_template_part( 'template-parts/sections/latest-news' ); ?>
</main>
<?php
get_footer();
