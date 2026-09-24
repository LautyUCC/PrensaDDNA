<?php
/** Definitive institutional footer. @package DDNA_Theme */
$details = function_exists( 'ddna_core_get_institutional_settings' ) ? ddna_core_get_institutional_settings() : array();
$footer_logo = get_template_directory_uri() . '/assets/images/logos/ddna-footer.png';
$socials = array(
	'facebook' => array( 'Facebook', $details['facebook_url'] ?? '' ),
	'instagram' => array( 'Instagram', $details['instagram_url'] ?? '' ),
	'x' => array( 'X', $details['x_url'] ?? '' ),
	'youtube' => array( 'YouTube', $details['youtube_url'] ?? '' ),
	'google-play' => array( 'Google Play', $details['google_play_url'] ?? '' ),
);
$lines = array(
	array( $details['assistance_label'] ?? '', $details['assistance_phone'] ?? '' ),
	array( $details['adolescence_label'] ?? '', $details['adolescence_phone'] ?? '' ),
);
?>
<footer class="site-footer" id="site-footer">
	<div class="container container--wide site-footer__inner">
		<div class="site-footer__identity">
			<a class="site-footer__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Ir al inicio', 'ddna-theme' ); ?>">
				<img class="site-footer__logo" src="<?php echo esc_url( $footer_logo ); ?>" alt="" width="870" height="688" loading="lazy" decoding="async">
			</a>
		</div>

		<div class="site-footer__lines" role="group" aria-label="<?php esc_attr_e( 'Líneas de atención', 'ddna-theme' ); ?>">
			<?php foreach ( $lines as $line ) : ?>
				<?php if ( $line[1] ) : ?><a class="assistance-line" href="<?php echo esc_url( ddna_theme_phone_uri( $line[1] ) ); ?>"><span class="assistance-line__icon" aria-hidden="true">◉</span><span><strong><?php echo esc_html( $line[0] ); ?></strong><small><?php echo esc_html( $line[1] ); ?></small></span></a><?php endif; ?>
			<?php endforeach; ?>
		</div>

		<div class="site-footer__contact">
			<?php if ( array_filter( array_column( $socials, 1 ) ) ) : ?>
				<p class="site-footer__follow"><?php esc_html_e( 'Seguinos en', 'ddna-theme' ); ?></p>
				<ul class="social-links" role="list">
					<?php foreach ( $socials as $network => $social ) : ?><?php if ( $social[1] ) : ?><li><a href="<?php echo esc_url( $social[1] ); ?>" target="_blank" rel="noopener noreferrer"><span class="screen-reader-text"><?php echo esc_html( $social[0] ); ?></span><?php get_template_part( 'template-parts/components/social-icon', null, array( 'network' => $network ) ); ?></a></li><?php endif; ?><?php endforeach; ?>
				</ul>
			<?php endif; ?>
			<address class="site-footer__address">
				<?php if ( ! empty( $details['address'] ) ) : ?><span><?php echo esc_html( $details['address'] ); ?></span><?php endif; ?>
				<?php if ( ! empty( $details['email'] ) ) : ?><a href="mailto:<?php echo esc_attr( $details['email'] ); ?>"><?php echo esc_html( $details['email'] ); ?></a><?php endif; ?>
				<?php if ( ! empty( $details['case_email'] ) ) : ?><a href="mailto:<?php echo esc_attr( $details['case_email'] ); ?>"><?php echo esc_html( $details['case_email'] ); ?></a><?php endif; ?>
				<?php if ( ! empty( $details['phone'] ) ) : ?><a href="<?php echo esc_url( ddna_theme_phone_uri( $details['phone'] ) ); ?>"><?php echo esc_html( $details['phone'] ); ?></a><?php endif; ?>
			</address>
		</div>
		<p class="site-footer__legal">&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>
	</div>
</footer>
