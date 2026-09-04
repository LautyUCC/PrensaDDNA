<?php
/** Dynamic campaign card. @package DDNA_Theme */
$external_url = get_post_meta( get_the_ID(), '_ddna_external_url', true );
$campaign_url = $external_url ? $external_url : get_permalink();
$heading_tag  = isset( $args['heading_level'] ) && 4 === (int) $args['heading_level'] ? 'h4' : 'h3';
$campaign_slug = get_post_field( 'post_name', get_the_ID() );
$campaign_icons = array(
	'hay-otra-forma'                         => 'hay-otra-forma.png',
	'prevencion-de-bullying-y-ciberbullying' => 'bullying-prevention.png',
	'guias-para-la-prevencion'               => 'bullying-prevention.png',
	'prevencion-del-abuso-sexual'             => 'abuse-prevention.png',
	'guias-para-una-crianza-cuidada'          => 'abuse-prevention.png',
	'guias-crianza-cuidada'                   => 'abuse-prevention.png',
	'la-vida-es-un-viaje-unico'               => 'substance-prevention.png',
	'vida-viaje-unico'                        => 'substance-prevention.png',
);
$campaign_icon = isset( $campaign_icons[ $campaign_slug ] ) ? $campaign_icons[ $campaign_slug ] : '';
$campaign_icon_class = 'hay-otra-forma' === $campaign_slug ? ' campaign-card__icon--native' : '';
?>
<article <?php post_class( 'campaign-card carousel__item' ); ?>>
	<a class="campaign-card__link<?php echo $campaign_icon ? '' : ' campaign-card__link--without-icon'; ?>" href="<?php echo esc_url( $campaign_url ); ?>">
		<?php if ( $campaign_icon ) : ?><img class="campaign-card__icon<?php echo esc_attr( $campaign_icon_class ); ?>" src="<?php echo esc_url( get_template_directory_uri() . '/assets/icons/campaigns/' . $campaign_icon ); ?>" alt="" width="100" height="100" decoding="async"><?php endif; ?>
		<div class="campaign-card__content"><?php printf( '<%1$s class="campaign-card__title">%2$s</%1$s>', esc_attr( $heading_tag ), esc_html( get_the_title() ) ); ?><div class="campaign-card__excerpt"><?php echo wp_kses_post( get_the_excerpt() ); ?></div></div>
	</a>
</article>
