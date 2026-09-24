<?php
/** Dynamic program card. @package DDNA_Theme */
$program_url  = ddna_theme_card_destination( get_the_ID() );
$link_tag = $program_url ? 'a' : 'div';
$heading_tag  = isset( $args['heading_level'] ) && 4 === (int) $args['heading_level'] ? 'h4' : 'h3';
$program_images = array(
	'programas-participacion-nnya'                => 'participacion-nnya.png',
	'acompanamiento-formacion-adultos'             => 'formacion-adultos.png',
	'programa-va-con-vos'                          => 'va-con-vos.png',
	'programa-entre-pantallas'                     => 'entre-pantallas.png',
	'cooperacion-internacional-interinstitucional' => 'cooperacion-internacional.png',
	'desarrollo-integral-primeros-anos-vida'       => 'desarrollo-integral.png',
);
$program_slug    = get_post_field( 'post_name', get_the_ID() );
$program_image   = ! empty( $args['nested'] ) && isset( $program_images[ $program_slug ] ) ? get_template_directory_uri() . '/assets/images/programs/' . $program_images[ $program_slug ] : '';
$program_excerpt = wp_strip_all_tags( get_the_excerpt(), true );
$dossier_key     = ddna_theme_program_dossier_key( get_the_ID() );
$opens_new_tab   = $dossier_key && $program_url === ddna_theme_editorial_url( $dossier_key );
?>
<article <?php post_class( 'program-card carousel__item' ); ?>>
	<<?php echo esc_html( $link_tag ); ?> class="program-card__link"<?php if ( $program_url ) : ?> href="<?php echo esc_url( $program_url ); ?>"<?php if ( $opens_new_tab || wp_parse_url( $program_url, PHP_URL_HOST ) !== wp_parse_url( home_url(), PHP_URL_HOST ) ) : ?> target="_blank" rel="noopener noreferrer"<?php endif; ?><?php else : ?> aria-disabled="true"<?php endif; ?>>
		<?php if ( $program_image ) : ?><img class="program-card__image" src="<?php echo esc_url( $program_image ); ?>" alt="" loading="lazy" decoding="async"><?php elseif ( has_post_thumbnail() ) : ?><?php the_post_thumbnail( 'ddna-program-card', array( 'class' => 'program-card__image', 'sizes' => '(min-width: 1200px) 30vw, (min-width: 768px) 48vw, 88vw', 'loading' => 'lazy', 'decoding' => 'async' ) ); ?><?php endif; ?>
		<div class="program-card__content"><?php printf( '<%1$s class="program-card__title"><span class="program-card__text-shape">%2$s</span></%1$s>', esc_attr( $heading_tag ), esc_html( get_the_title() ) ); ?><?php if ( $program_excerpt ) : ?><p class="program-card__excerpt"><span class="program-card__text-shape"><?php echo esc_html( $program_excerpt ); ?></span></p><?php endif; ?></div>
		<?php if ( ! $program_url ) : ?><span class="screen-reader-text">Dossier pendiente de publicación</span><?php endif; ?>
	</<?php echo esc_html( $link_tag ); ?>>
</article>
