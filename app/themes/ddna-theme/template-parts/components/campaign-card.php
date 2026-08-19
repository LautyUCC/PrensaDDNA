<?php
/** Dynamic campaign card. @package DDNA_Theme */
$external_url = get_post_meta( get_the_ID(), '_ddna_external_url', true );
$campaign_url = $external_url ? $external_url : get_permalink();
?>
<article <?php post_class( 'campaign-card carousel__item' ); ?>>
	<a class="campaign-card__link" href="<?php echo esc_url( $campaign_url ); ?>">
		<div class="campaign-card__mark" aria-hidden="true"><span></span><span></span><span></span></div>
		<div class="campaign-card__content"><h3 class="campaign-card__title"><?php echo esc_html( get_the_title() ); ?></h3><div class="campaign-card__excerpt"><?php echo wp_kses_post( get_the_excerpt() ); ?></div></div>
	</a>
</article>
