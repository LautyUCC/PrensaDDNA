<?php
/** Dynamic campaign card. @package DDNA_Theme */
$campaign_url = ddna_theme_card_destination( get_the_ID() );
$link_tag = $campaign_url ? 'a' : 'div';
$heading_tag  = isset( $args['heading_level'] ) && 4 === (int) $args['heading_level'] ? 'h4' : 'h3';
$campaign_slug = get_post_field( 'post_name', get_the_ID() );
$campaign_icons = array(
	'hay-otra-forma-bullying' => 'hay-otra-forma-official.png',
	'consumo-problematico' => 'substance-prevention.png',
	'hay-otra-forma'                         => 'hay-otra-forma-official.png',
	'prevencion-de-bullying-y-ciberbullying' => 'bullying-prevention.png',
	'guias-para-la-prevencion'               => 'bullying-prevention.png',
	'prevencion-del-abuso-sexual'             => 'abuse-prevention.png',
	'guias-para-una-crianza-cuidada'          => 'abuse-prevention.png',
	'guias-crianza-cuidada'                   => 'abuse-prevention.png',
	'la-vida-es-un-viaje-unico'               => 'substance-prevention.png',
	'vida-viaje-unico'                        => 'substance-prevention.png',
);
$campaign_icon = isset( $campaign_icons[ $campaign_slug ] ) ? $campaign_icons[ $campaign_slug ] : '';
$campaign_icon_class = in_array( $campaign_slug, array( 'hay-otra-forma', 'hay-otra-forma-bullying' ), true ) ? ' campaign-card__icon--native' : '';
?>
<article <?php post_class( 'campaign-card carousel__item' ); ?>>
	<<?php echo esc_html( $link_tag ); ?> class="campaign-card__link<?php echo $campaign_icon ? '' : ' campaign-card__link--without-icon'; ?>"<?php if ( $campaign_url ) : ?> href="<?php echo esc_url( $campaign_url ); ?>"<?php if ( wp_parse_url( $campaign_url, PHP_URL_HOST ) !== wp_parse_url( home_url(), PHP_URL_HOST ) ) : ?> target="_blank" rel="noopener noreferrer"<?php endif; ?><?php else : ?> aria-disabled="true"<?php endif; ?>>
		<?php if ( $campaign_icon ) : ?><img class="campaign-card__icon<?php echo esc_attr( $campaign_icon_class ); ?>" src="<?php echo esc_url( get_template_directory_uri() . '/assets/icons/campaigns/' . $campaign_icon ); ?>" alt="" width="100" height="100" decoding="async"><?php endif; ?>
		<div class="campaign-card__content"><?php printf( '<%1$s class="campaign-card__title">%2$s</%1$s>', esc_attr( $heading_tag ), esc_html( get_the_title() ) ); ?><div class="campaign-card__excerpt"><?php echo wp_kses_post( get_the_excerpt() ); ?></div></div>
		<?php if ( ! $campaign_url ) : ?><span class="screen-reader-text">Enlace pendiente de publicación</span><?php endif; ?>
	</<?php echo esc_html( $link_tag ); ?>>
</article>
