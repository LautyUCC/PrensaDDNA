<?php
/** Official decorative social network icon. @package DDNA_Theme */
$network = isset( $args['network'] ) ? $args['network'] : '';
$icons   = array(
	'facebook'    => 39,
	'instagram'   => 40,
	'x'           => 41,
	'youtube'     => 42,
	'google-play' => 43,
);
if ( isset( $icons[ $network ] ) ) :
	$icon_url = get_template_directory_uri() . '/assets/icons/social/social-' . $icons[ $network ] . '.png';
	?>
	<img class="social-icon" src="<?php echo esc_url( $icon_url ); ?>" alt="" width="350" height="321" loading="lazy" decoding="async">
<?php endif; ?>
