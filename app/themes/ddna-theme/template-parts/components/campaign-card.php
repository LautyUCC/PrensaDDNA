<?php
/** Campaign artwork with its existing destination. @package DDNA_Theme */
$campaign_url  = ddna_theme_card_destination( get_the_ID() );
$link_tag      = $campaign_url ? 'a' : 'div';
$campaign_slug = get_post_field( 'post_name', get_the_ID() );
$campaign_images = array(
	'hay-otra-forma'          => 'HOF-02.png',
	'hay-otra-forma-bullying' => 'HOF-01.png',
	'consumo-problematico'    => 'consumo.png',
	'cuidar-la-crianza'       => 'crianza.png',
	'grooming'               => 'grooming.png',
);
$campaign_image = isset( $campaign_images[ $campaign_slug ] ) ? $campaign_images[ $campaign_slug ] : '';
$campaign_alt   = get_the_title();
$campaign_description = trim( wp_strip_all_tags( get_the_excerpt() ) );
if ( $campaign_description ) {
	$campaign_alt .= ' — ' . $campaign_description;
}
?>
<article <?php post_class( 'campaign-card carousel__item' ); ?>>
	<<?php echo esc_html( $link_tag ); ?> class="campaign-card__link"<?php if ( $campaign_url ) : ?> href="<?php echo esc_url( $campaign_url ); ?>"<?php if ( wp_parse_url( $campaign_url, PHP_URL_HOST ) !== wp_parse_url( home_url(), PHP_URL_HOST ) ) : ?> target="_blank" rel="noopener noreferrer"<?php endif; ?><?php else : ?> aria-disabled="true"<?php endif; ?>>
		<?php if ( $campaign_image ) : ?>
			<img class="campaign-card__image" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/campaigns/' . $campaign_image ); ?>" alt="<?php echo esc_attr( $campaign_alt ); ?>" width="1673" height="848" loading="lazy" decoding="async">
		<?php else : ?>
			<?php echo esc_html( $campaign_alt ); ?>
		<?php endif; ?>
		<?php if ( ! $campaign_url ) : ?><span class="screen-reader-text">Enlace pendiente de publicación</span><?php endif; ?>
	</<?php echo esc_html( $link_tag ); ?>>
</article>
