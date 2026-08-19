<?php
/** Dynamic program card. @package DDNA_Theme */
$external_url = get_post_meta( get_the_ID(), '_ddna_external_url', true );
$program_url  = $external_url ? $external_url : get_permalink();
?>
<article <?php post_class( 'program-card carousel__item' ); ?>>
	<a class="program-card__link" href="<?php echo esc_url( $program_url ); ?>">
		<?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'ddna-program-card', array( 'class' => 'program-card__image', 'sizes' => '(min-width: 1200px) 30vw, (min-width: 768px) 48vw, 88vw', 'loading' => 'lazy', 'decoding' => 'async' ) ); } ?>
		<div class="program-card__content"><h3 class="program-card__title"><?php echo esc_html( get_the_title() ); ?></h3><div class="program-card__excerpt"><?php echo wp_kses_post( get_the_excerpt() ); ?></div></div>
	</a>
</article>
