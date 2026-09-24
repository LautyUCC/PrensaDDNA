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
	<?php
	$uses_home_header    = is_front_page() || ddna_theme_is_institutional_page();
	?>
	<header class="site-header site-header--branded<?php echo $uses_home_header ? ' site-header--home' : ''; ?>">
		<div class="container container--wide site-header__inner">
			<a class="site-header__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logos/ddna-header-2026.png' ); ?>" alt="<?php esc_attr_e( 'Defensoría de los Derechos de Niñas, Niños y Adolescentes', 'ddna-theme' ); ?>" width="4559" height="1498">
			</a>
			<?php get_template_part( 'template-parts/navigation/primary' ); ?>
		</div>
	</header>
