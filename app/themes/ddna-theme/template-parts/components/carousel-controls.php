<?php
/** Controls for a reusable home carousel. @package DDNA_Theme */
$ddna_carousel_id = isset( $args['carousel_id'] ) ? $args['carousel_id'] : '';
$ddna_carousel_label = isset( $args['label'] ) ? $args['label'] : __( 'elementos', 'ddna-theme' );
?>
<div class="carousel-controls" role="group" aria-label="<?php echo esc_attr( sprintf( __( 'Controles de %s', 'ddna-theme' ), $ddna_carousel_label ) ); ?>">
	<button class="carousel-control carousel-control--previous" type="button" data-carousel-previous aria-controls="<?php echo esc_attr( $ddna_carousel_id ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Mostrar %s anteriores', 'ddna-theme' ), $ddna_carousel_label ) ); ?>" disabled><span aria-hidden="true"></span></button>
	<button class="carousel-control carousel-control--next" type="button" data-carousel-next aria-controls="<?php echo esc_attr( $ddna_carousel_id ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Mostrar %s siguientes', 'ddna-theme' ), $ddna_carousel_label ) ); ?>"><span aria-hidden="true"></span></button>
</div>
