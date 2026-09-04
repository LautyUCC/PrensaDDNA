<?php
/** Dynamic campaign card. @package DDNA_Theme */
$external_url = get_post_meta( get_the_ID(), '_ddna_external_url', true );
$campaign_url = $external_url ? $external_url : get_permalink();
$heading_tag  = isset( $args['heading_level'] ) && 4 === (int) $args['heading_level'] ? 'h4' : 'h3';
?>
<article <?php post_class( 'campaign-card carousel__item' ); ?>>
	<a class="campaign-card__link" href="<?php echo esc_url( $campaign_url ); ?>">
		<div class="campaign-card__mark" aria-hidden="true"><span></span><span></span><span></span></div>
		<div class="campaign-card__content"><?php printf( '<%1$s class="campaign-card__title">%2$s</%1$s>', esc_attr( $heading_tag ), esc_html( get_the_title() ) ); ?><div class="campaign-card__excerpt"><?php echo wp_kses_post( get_the_excerpt() ); ?></div></div>
	</a>
</article>
