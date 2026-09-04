<?php
/** Training options shown in the Quiero conocer panel. @package DDNA_Theme */

$training_query = new WP_Query(
	array(
		'post_type'              => 'capacitacion',
		'post_status'            => 'publish',
		'posts_per_page'         => -1,
		'orderby'                => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
		'no_found_rows'          => true,
		'update_post_meta_cache' => true,
		'update_post_term_cache' => false,
	)
);

$training_urls = array();
foreach ( $training_query->posts as $training_post ) {
	$file_id = absint( get_post_meta( $training_post->ID, '_ddna_file_id', true ) );
	$url     = $file_id ? wp_get_attachment_url( $file_id ) : get_post_meta( $training_post->ID, '_ddna_external_url', true );
	$training_urls[ sanitize_title( $training_post->post_title ) ] = $url ? $url : get_permalink( $training_post );
}
wp_reset_postdata();

$training_option = static function ( $label, array $aliases = array() ) use ( $training_urls ) {
	$url = '';
	foreach ( array_merge( array( $label ), $aliases ) as $alias ) {
		$key = sanitize_title( remove_accents( $alias ) );
		if ( isset( $training_urls[ $key ] ) ) {
			$url = $training_urls[ $key ];
			break;
		}
	}
	?>
	<li class="home-trainings__item">
		<?php if ( $url ) : ?>
			<a class="home-trainings__option" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $label ); ?></a>
		<?php else : ?>
			<span class="home-trainings__option" aria-disabled="true"><?php echo esc_html( $label ); ?></span>
		<?php endif; ?>
	</li>
	<?php
};
?>
<section class="home-panel__content home-panel__content--conocer home-trainings" aria-labelledby="capacitaciones-title">
	<h3 class="home-panel__section-title" id="capacitaciones-title">Capacitaciones</h3>
	<details class="panel-accordion home-trainings__accordion" open>
		<summary>Diplomaturas</summary>
		<div class="panel-accordion__content">
			<ul class="home-trainings__options" role="list">
				<?php $training_option( 'Diplomatura Abogado del Niño', array( 'Diplomatura del Abogado del Niño' ) ); ?>
				<?php $training_option( 'Diplomatura NNyA en el mundo digital', array( 'NNyA en el mundo digital' ) ); ?>
			</ul>
		</div>
	</details>
	<details class="panel-accordion home-trainings__accordion" open>
		<summary>Seminarios</summary>
		<div class="panel-accordion__content">
			<ul class="home-trainings__options" role="list">
				<?php $training_option( 'Abordaje de las violencias hacia NNyA', array( 'Abordaje de violencias hacia NNyA' ) ); ?>
			</ul>
		</div>
	</details>
</section>
