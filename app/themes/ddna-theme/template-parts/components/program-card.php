<?php
/** Dynamic program card. @package DDNA_Theme */
$program_url  = ddna_theme_card_destination( get_the_ID() );
$link_tag = $program_url ? 'a' : 'div';
$heading_tag  = isset( $args['heading_level'] ) && 4 === (int) $args['heading_level'] ? 'h4' : 'h3';
?>
<article <?php post_class( 'program-card carousel__item' ); ?>>
	<<?php echo esc_html( $link_tag ); ?> class="program-card__link"<?php if ( $program_url ) : ?> href="<?php echo esc_url( $program_url ); ?>"<?php if ( wp_parse_url( $program_url, PHP_URL_HOST ) !== wp_parse_url( home_url(), PHP_URL_HOST ) ) : ?> target="_blank" rel="noopener noreferrer"<?php endif; ?><?php else : ?> aria-disabled="true"<?php endif; ?>>
		<?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'ddna-program-card', array( 'class' => 'program-card__image', 'sizes' => '(min-width: 1200px) 30vw, (min-width: 768px) 48vw, 88vw', 'loading' => 'lazy', 'decoding' => 'async' ) ); } ?>
		<div class="program-card__content"><?php printf( '<%1$s class="program-card__title">%2$s</%1$s>', esc_attr( $heading_tag ), esc_html( get_the_title() ) ); ?><div class="program-card__excerpt"><?php echo wp_kses_post( get_the_excerpt() ); ?></div></div>
		<?php if ( ! $program_url ) : ?><span class="screen-reader-text">Dossier pendiente de publicación</span><?php endif; ?>
	</<?php echo esc_html( $link_tag ); ?>>
</article>
