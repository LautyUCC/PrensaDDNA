<?php
/**
 * Contenido de página.
 *
 * @package DDNA_Theme
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--page' . ( is_page( 'comunicados' ) ? ' entry--statements' : '' ) . ( get_post_meta( get_the_ID(), '_ddna_content_version', true ) ? ' home-panel__content' : '' ) ); ?>>
	<?php if ( ddna_theme_is_program_page() ) {
		get_template_part( 'template-parts/components/return-home-menu', null, array( 'url' => home_url( '/' ), 'label' => __( 'Volver al inicio', 'ddna-theme' ) ) );
	} elseif ( is_page( array( 'normativa', 'normativas' ) ) ) {
		get_template_part( 'template-parts/components/return-home-menu', null, array( 'url' => home_url( '/' ), 'label' => __( 'Volver al inicio', 'ddna-theme' ) ) );
	} elseif ( is_page( 'comunicados' ) ) {
		get_template_part( 'template-parts/components/return-home-menu', null, array( 'url' => home_url( '/' ), 'label' => __( 'Volver al inicio', 'ddna-theme' ) ) );
	} elseif ( is_page( array( 'defensoria', 'informes-anuales', 'convenios', 'contacto' ) ) ) {
		get_template_part( 'template-parts/components/return-home-menu' );
	} ?>
	<header class="entry-header">
		<?php if ( is_page( 'comunicados' ) ) : ?>
			<h1 class="entry-title"><?php esc_html_e( 'Comunicados y Pronunciamientos', 'ddna-theme' ); ?></h1>
		<?php else : ?>
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<?php endif; ?>
	</header>
	<?php if ( has_post_thumbnail() ) : ?>
		<figure class="entry-media"><?php the_post_thumbnail( 'large' ); ?></figure>
	<?php endif; ?>
	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages(); ?>
	</div>
</article>
