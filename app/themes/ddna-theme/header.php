<?php
/**
 * Cabecera global.
 *
 * @package DDNA_Theme
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main-content"><?php esc_html_e( 'Saltar al contenido', 'ddna-theme' ); ?></a>
<div class="site" id="page">
	<header class="site-header">
		<div class="container container--wide site-header__inner">
			<?php get_template_part( 'template-parts/navigation/primary' ); ?>
		</div>
	</header>
