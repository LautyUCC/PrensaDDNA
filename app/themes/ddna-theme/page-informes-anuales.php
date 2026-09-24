<?php
/**
 * Informes Anuales.
 *
 * @package DDNA_Theme
 */

get_header();

$report_slots = array(
	array( 'slug' => 'documento-informe-anual-2025', 'title' => 'Informe Anual 2025' ),
	array( 'slug' => 'documento-informe-anual-2024', 'title' => 'Informe Anual 2024' ),
	array( 'slug' => 'documento-informe-anual-2023', 'title' => 'Informe Anual 2023' ),
	array( 'slug' => 'documento-informe-anual-2022', 'title' => 'Informe Anual 2022' ),
	array( 'slug' => 'documento-informe-anual-2021', 'title' => 'Informe Anual 2021' ),
	array( 'slug' => 'documento-informe-anual-2020', 'title' => 'Informe Anual 2020' ),
	array( 'slug' => 'documento-informe-anual-2019', 'title' => 'Informe Anual 2019' ),
	array( 'slug' => 'documento-informe-anual-2018', 'title' => 'Informe Anual 2018' ),
	array( 'slug' => 'documento-informe-anual-2017', 'title' => 'Informe Anual 2017' ),
	array( 'slug' => 'documento-informe-anual-2016', 'title' => 'Informe Anual 2016' ),
);

$document_icon = get_template_directory_uri() . '/assets/icons/knowledge/documents/annual-report.png';

/**
 * Gets a document record and its assigned media file, when available.
 *
 * @param string $slug Documento slug.
 * @return array{url:string,available:bool}
 */
$get_document_file = static function ( $slug ) {
	$documents = get_posts(
		array(
			'name'        => $slug,
			'post_type'   => 'documento',
			'post_status' => 'publish',
			'numberposts' => 1,
		)
	);
	$document = $documents ? $documents[0] : null;
	$file_id  = $document ? (int) get_post_meta( $document->ID, '_ddna_file_id', true ) : 0;
	$file_url = $file_id ? wp_get_attachment_url( $file_id ) : '';

	return array(
		'url'       => $file_url ? $file_url : '',
		'available' => (bool) $file_url,
	);
};
?>
<main class="site-main" id="main-content">
	<div class="site-container site-container--annual-reports">
		<article class="annual-reports">
			<header class="annual-reports__header">
				<h1><?php esc_html_e( 'Informes Anuales', 'ddna-theme' ); ?></h1>
				<p><?php esc_html_e( 'Consultá y descargá los informes anuales de la Defensoría.', 'ddna-theme' ); ?></p>
			</header>

			<section aria-label="<?php esc_attr_e( 'Informes anuales disponibles', 'ddna-theme' ); ?>">
				<div class="annual-reports__grid">
					<?php foreach ( $report_slots as $report ) : ?>
						<?php $file = $get_document_file( $report['slug'] ); ?>
						<?php if ( $file['available'] ) : ?>
							<a class="annual-report-card" href="<?php echo esc_url( $file['url'] ); ?>" target="_blank" rel="noopener noreferrer">
								<img src="<?php echo esc_url( $document_icon ); ?>" alt="" width="350" height="321" loading="lazy" decoding="async">
								<span><?php echo esc_html( $report['title'] ); ?></span>
							</a>
						<?php else : ?>
							<div class="annual-report-card annual-report-card--pending" role="status">
								<img src="<?php echo esc_url( $document_icon ); ?>" alt="" width="350" height="321" loading="lazy" decoding="async">
								<span><?php echo esc_html( $report['title'] ); ?></span>
								<small><?php esc_html_e( 'Documento próximamente disponible', 'ddna-theme' ); ?></small>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</section>

		<?php $management_file = $get_document_file( 'documento-memoria-de-gestion-2016-2026' ); ?>
			<section class="annual-reports__management" aria-labelledby="management-report-title">
				<h2 id="management-report-title"><?php esc_html_e( 'Memoria de Gestión 2016–2026', 'ddna-theme' ); ?></h2>
				<?php if ( $management_file['available'] ) : ?>
					<a class="annual-report-card" href="<?php echo esc_url( $management_file['url'] ); ?>" target="_blank" rel="noopener noreferrer">
						<img src="<?php echo esc_url( $document_icon ); ?>" alt="" width="350" height="321" loading="lazy" decoding="async">
						<span><?php esc_html_e( 'Abrir Memoria de Gestión 2016–2026', 'ddna-theme' ); ?></span>
					</a>
				<?php else : ?>
					<p class="annual-reports__pending"><?php esc_html_e( 'Documento próximamente disponible', 'ddna-theme' ); ?></p>
				<?php endif; ?>
			</section>
		</article>
	</div>
</main>
<?php
get_footer();
