<?php
/** Institutional navigation and its in-page panels. @package DDNA_Theme */

$contacto          = get_page_by_path( 'contacto' );
$icon_uri          = get_template_directory_uri() . '/assets/icons/institutional/';
$document_icon_uri = get_template_directory_uri() . '/assets/icons/knowledge/documents/';
$convenios         = array(
	array( 'Rotary International (Distrito 4851)', '' ),
	array( 'Fundación Arcor', '' ),
	array( 'Asociación de Mujeres Jueces de Argentina (AMJA)', '' ),
	array( 'Facultad de Ciencias Sociales de la UNC', 'Convenio marco de Pasantías' ),
	array( 'Fundación Tecnológica con Propósito (TWP)', '' ),
	array( 'Colegio Universitario Politécnico (CUP)', 'Convenio marco de Pasantías' ),
	array( 'Universidad Empresarial Siglo 21', 'Convenio marco de Pasantías' ),
	array( 'Universidad Católica de Córdoba (UCC)', '' ),
	array( 'Facultad de Derecho UNC', 'Convenio marco de Pasantías' ),
	array( 'Universidad Nacional de Villa María', '' ),
	array( 'Escuela Dante Alighieri', 'Convenio marco de Pasantías' ),
	array( 'UNICEF', 'Convenio marco de colaboración para el desarrollo de planes de trabajo específicos' ),
);

$render_accordion = static function ( $id, $label, $content ) {
	?>
	<section class="institutional-accordion" data-institutional-accordion>
		<h3><button class="institutional-accordion__trigger" type="button" id="<?php echo esc_attr( $id ); ?>-trigger" aria-expanded="true" aria-controls="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?><span aria-hidden="true"></span></button></h3>
		<div class="institutional-accordion__panel" id="<?php echo esc_attr( $id ); ?>" role="region" aria-labelledby="<?php echo esc_attr( $id ); ?>-trigger"><?php echo wp_kses_post( $content ); ?></div>
	</section>
	<?php
};
?>
<section class="institutional-home" aria-label="<?php esc_attr_e( 'Información institucional', 'ddna-theme' ); ?>">
	<nav class="institutional-navigation" aria-label="<?php esc_attr_e( 'Navegación institucional', 'ddna-theme' ); ?>">
		<ul class="institutional-navigation__list" role="list">
			<li><button type="button" data-institutional-panel-trigger="la-defensoria" aria-expanded="false" aria-controls="institutional-panel-la-defensoria"><img src="<?php echo esc_url( $icon_uri . 'institutional-27.png' ); ?>" alt="" width="350" height="321" loading="lazy" decoding="async"><span><?php esc_html_e( 'La Defensoría', 'ddna-theme' ); ?></span></button></li>
			<li><button type="button" data-institutional-panel-trigger="normativas" aria-expanded="false" aria-controls="institutional-panel-normativas"><img src="<?php echo esc_url( $icon_uri . 'institutional-29.png' ); ?>" alt="" width="350" height="321" loading="lazy" decoding="async"><span><?php esc_html_e( 'Normativas', 'ddna-theme' ); ?></span></button></li>
			<li><button type="button" data-institutional-panel-trigger="convenios" aria-expanded="false" aria-controls="institutional-panel-convenios"><img src="<?php echo esc_url( $icon_uri . 'institutional-31.png' ); ?>" alt="" width="350" height="321" loading="lazy" decoding="async"><span><?php esc_html_e( 'Convenios', 'ddna-theme' ); ?></span></button></li>
			<li><a href="<?php echo esc_url( $contacto ? get_permalink( $contacto ) : home_url( '/contacto/' ) ); ?>"><img src="<?php echo esc_url( $icon_uri . 'institutional-33.png' ); ?>" alt="" width="350" height="321" loading="lazy" decoding="async"><span><?php esc_html_e( 'Contacto', 'ddna-theme' ); ?></span></a></li>
		</ul>
	</nav>

	<div class="institutional-panels" data-institutional-panels>
		<section class="institutional-panel" id="institutional-panel-la-defensoria" data-institutional-panel="la-defensoria" aria-labelledby="institutional-title-la-defensoria" hidden>
			<div class="container container--content"><div class="institutional-panel__content">
				<header class="institutional-panel__header"><h2 id="institutional-title-la-defensoria">¿Quiénes somos?</h2><p>Somos un organismo estatal autónomo dedicado a promover, proteger y defender los derechos de las niñas, niños y adolescentes, trabajando para que sean escuchados y sus derechos se respeten plenamente.</p></header>
				<?php
				$render_accordion( 'institutional-what-we-do', '¿Qué hacemos?', '<ul class="institutional-bullets"><li>Brindamos orientación y atención personalizada a niñas, niños, adolescentes y sus familias.</li><li>Recibimos consultas y acompañamos en la búsqueda de soluciones.</li><li>Ofrecemos asistencia jurídica gratuita a niñas, niños y adolescentes con discapacidad.</li><li>Promovemos acciones de sensibilización, capacitación y trabajo conjunto con organismos e instituciones de toda la provincia.</li></ul>' );
				$render_accordion( 'institutional-mission', 'Nuestra misión', '<p>Promover el pleno ejercicio de los derechos de niñas, niños y adolescentes, impulsando una cultura basada en el respeto, la participación y la escucha activa, reconociéndolos como sujetos de derecho.</p>' );
				$render_accordion( 'institutional-vision', 'Visión', '<p>Construir una sociedad donde niñas, niños y adolescentes ejerzan plenamente sus derechos y participen activamente como protagonistas de su presente y su futuro.</p>' );
				$render_accordion( 'institutional-objectives', 'Objetivos', '<ul class="institutional-bullets"><li>Garantizar la defensa de los derechos de las niñas, niños y adolescentes ante las instituciones públicas y privadas.</li><li>Desarrollar acciones de promoción, investigación y formación en derechos de la niñez y adolescencia.</li><li>Supervisar el funcionamiento del sistema de protección integral de derechos.</li><li>Impulsar políticas públicas que fortalezcan la participación de niñas, niños y adolescentes.</li><li>Promover la adecuación de la normativa provincial a la Convención sobre los Derechos del Niño y a la legislación vigente.</li></ul>' );
				?>
				<div class="institutional-dossier" aria-label="<?php esc_attr_e( 'Dossier institucional sin enlace disponible', 'ddna-theme' ); ?>"><img src="<?php echo esc_url( $document_icon_uri . 'annual-report.png' ); ?>" alt="" width="350" height="321" loading="lazy" decoding="async"><span><?php esc_html_e( 'Ver dossier institucional', 'ddna-theme' ); ?></span></div>
			</div></div>
		</section>

		<section class="institutional-panel" id="institutional-panel-normativas" data-institutional-panel="normativas" aria-labelledby="institutional-title-normativas" hidden>
			<div class="container container--content"><div class="institutional-panel__content">
				<header class="institutional-panel__header"><h2 id="institutional-title-normativas">Normativas</h2></header>
				<p class="panel-empty-state">La selección definitiva de normativas y sus enlaces se publicará cuando esté aprobada.</p>
			</div></div>
		</section>

		<section class="institutional-panel" id="institutional-panel-convenios" data-institutional-panel="convenios" aria-labelledby="institutional-title-convenios" hidden>
			<div class="container container--content"><div class="institutional-panel__content">
				<header class="institutional-panel__header"><h2 id="institutional-title-convenios">Convenios</h2></header>
				<div class="institutional-table-wrap" tabindex="0"><table class="institutional-table"><thead><tr><th scope="col">Entidad</th><th scope="col">Tipo de Convenio</th></tr></thead><tbody><?php foreach ( $convenios as $convenio ) : ?><tr><td><?php echo esc_html( $convenio[0] ); ?></td><td><?php echo esc_html( $convenio[1] ); ?></td></tr><?php endforeach; ?></tbody></table></div>
			</div></div>
		</section>
	</div>
</section>
