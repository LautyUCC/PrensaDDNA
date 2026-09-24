<?php
/** Contenido reutilizable de las campañas Hay Otra Forma. @package DDNA_Theme */
$campaign  = isset( $args['campaign'] ) ? $args['campaign'] : 'maltrato';
$asset_uri = get_template_directory_uri() . '/assets/images/hay-otra-forma/';
$logo_uri  = $asset_uri . 'shared/logo-hay-otra-forma-01.png';
$maltrato_url = home_url( '/hay-otra-forma-prevencion-maltrato/' );
$bullying_url = home_url( '/hay-otra-forma-prevencion-bullying/' );
$is_bullying  = 'bullying' === $campaign;

$data = $is_bullying ? array(
	'eyebrow' => 'Campaña contra el acoso entre pares',
	'title'   => 'Bullying y ciberbullying',
	'lead'    => 'El bullying es una forma de violencia, generalmente prolongada, donde una o más personas ejercen abuso de poder sobre otras. Puede incluir discriminar, despreciar, intimidar o agredir.',
	'hero'    => 'bullying/img-que-es-4.png',
	'hero_alt'=> 'Ilustración sobre bullying',
	'video'   => '54Nbxrgv-qE',
	'videos'  => array(
		array( '1w26RYDXSBE', '¿Qué hacemos como sociedad?', 'que-hacemos.jpg' ),
		array( 'uNfxRDjjGhE', '¿Qué hacemos los adultos?', 'adultos.jpg' ),
		array( 'jz5x2e0nPyU', 'Consecuencias del bullying', 'consecuencias.jpg' ),
		array( 'WcuOuIV3Wyk', 'Factores que influyen', 'factores.jpg' ),
	),
) : array(
	'eyebrow' => 'Campaña de crianza respetuosa',
	'title'   => 'Prevención del maltrato hacia NNyA',
	'lead'    => 'Hay Otra Forma pone en debate las formas violentas de crianza que siguen naturalizadas y acerca herramientas para cuidar, escuchar y acompañar a niñas, niños y adolescentes.',
	'hero'    => 'maltrato/flia-03.png',
	'hero_alt'=> 'Ilustración de una familia',
	'video'   => 'GpSYFadZLHs',
	'videos'  => array(
		array( 'XWVt3DyCUtU', '¿Hay otra forma?', 'hay-otra-forma.jpg' ),
		array( '5vPohdwDvC0', 'Cómo cuidar y prevenir', 'como-prevenir.jpg' ),
		array( '90NUoKIkgSU', 'Una infancia sumergida en la violencia', 'una-infancia.jpg' ),
		array( 'iFAyoOl52ao', 'Cómo detectar situaciones de violencia', 'como-detectamos.jpg' ),
	),
);
?>
<main class="site-main hof-campaign hof-campaign--<?php echo esc_attr( $campaign ); ?>" id="main-content">
	<section class="hof-campaign__hero">
		<div class="hof-campaign__container hof-campaign__hero-grid">
			<div class="hof-campaign__hero-copy">
				<img class="hof-campaign__logo" src="<?php echo esc_url( $logo_uri ); ?>" alt="Hay Otra Forma" width="400" height="250" decoding="async">
				<p class="hof-campaign__eyebrow"><?php echo esc_html( $data['eyebrow'] ); ?></p>
				<h1><?php echo esc_html( $data['title'] ); ?></h1>
				<p class="hof-campaign__lead"><?php echo esc_html( $data['lead'] ); ?></p>
				<nav class="hof-campaign__nav" aria-label="Secciones de la campaña">
					<a href="#videos">Videos</a>
					<?php if ( $is_bullying ) : ?><a href="#guia">Guía</a><a href="#roles">Roles</a><a href="#difundir">Difundir</a><a href="<?php echo esc_url( $maltrato_url ); ?>">Maltrato</a><?php else : ?><a href="#frases">Frases</a><a href="#recomendaciones">Recomendaciones</a><a href="<?php echo esc_url( $bullying_url ); ?>">Bullying</a><?php endif; ?>
				</nav>
			</div>
			<div class="hof-campaign__hero-art"><img src="<?php echo esc_url( $asset_uri . $data['hero'] ); ?>" alt="<?php echo esc_attr( $data['hero_alt'] ); ?>" width="650" height="650" decoding="async"></div>
		</div>
	</section>

	<section class="hof-campaign__section hof-campaign__section--white" id="videos">
		<div class="hof-campaign__container">
			<header class="hof-campaign__section-heading"><h2>Videos de la campaña</h2><p><?php echo esc_html( $is_bullying ? 'Conocé testimonios, miradas profesionales y herramientas para prevenir e intervenir frente al acoso entre pares.' : 'Material audiovisual para reconocer formas de maltrato, revisar prácticas naturalizadas y promover una crianza respetuosa.' ); ?></p></header>
			<div class="hof-campaign__videos">
				<div class="hof-campaign__video-frame"><iframe src="https://www.youtube.com/embed/<?php echo esc_attr( $data['video'] ); ?>" title="<?php echo esc_attr( 'Video: ' . $data['title'] ); ?>" loading="lazy" allowfullscreen></iframe></div>
				<div class="hof-campaign__video-list">
					<?php foreach ( $data['videos'] as $video ) : ?><a class="hof-campaign__video-link" href="https://www.youtube.com/watch?v=<?php echo esc_attr( $video[0] ); ?>" target="_blank" rel="noopener noreferrer"><img src="<?php echo esc_url( $asset_uri . ( $is_bullying ? 'bullying/' : 'maltrato/' ) . $video[2] ); ?>" alt="" width="300" height="169" loading="lazy" decoding="async"><span><strong><?php echo esc_html( $video[1] ); ?></strong><small>Mirar en YouTube</small></span></a><?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>

	<?php if ( ! $is_bullying ) : ?>
		<section class="hof-campaign__section hof-campaign__section--sand" id="frases"><div class="hof-campaign__container"><header class="hof-campaign__section-heading"><h2>¿Conocés estas frases?</h2><p>Ideas que se repiten en la crianza y que invitan a revisar prácticas cotidianas.</p></header><div class="hof-campaign__quote-grid"><?php foreach ( array( array( '“Un chirlo a tiempo…”', 'La violencia no educa. El límite puede construirse con palabra, presencia y cuidado.' ), array( '“Dejá de llorar”', 'Escuchar lo que sienten niñas y niños también es una forma de protección.' ), array( '“Toda la vida fue así”', 'Que algo se haya repetido no significa que sea justo ni saludable.' ), array( '“Si te grito es por tu bien”', 'Gritar es una forma de violencia. Hay otra forma de acompañar y poner límites.' ) ) as $quote ) : ?><article><h3><?php echo esc_html( $quote[0] ); ?></h3><p><?php echo esc_html( $quote[1] ); ?></p></article><?php endforeach; ?></div></div></section>
		<section class="hof-campaign__section hof-campaign__section--paper" id="recomendaciones"><div class="hof-campaign__container"><header class="hof-campaign__section-heading"><h2>Recomendaciones para una mejor crianza</h2><p>Pequeñas acciones cotidianas que ayudan a construir vínculos más respetuosos y protectores.</p></header><div class="hof-campaign__tip-grid"><?php foreach ( array( array( 'tip-01.png', 'Escuchar', 'Dar lugar a la palabra de niñas, niños y adolescentes.' ), array( 'tip-02.png', 'Anticipar', 'Explicar acuerdos, límites y consecuencias sin recurrir al miedo.' ), array( 'tip-05.png', 'Acompañar', 'Estar presentes en los conflictos y validar emociones.' ), array( 'tip-06.png', 'Pedir ayuda', 'Consultar cuando una situación excede a la familia o la escuela.' ) ) as $tip ) : ?><article><img src="<?php echo esc_url( $asset_uri . 'maltrato/' . $tip[0] ); ?>" alt="" width="60" height="60" loading="lazy" decoding="async"><h3><?php echo esc_html( $tip[1] ); ?></h3><p><?php echo esc_html( $tip[2] ); ?></p></article><?php endforeach; ?></div></div></section>
	<?php else : ?>
		<section class="hof-campaign__section hof-campaign__section--paper" id="guia"><div class="hof-campaign__container hof-campaign__split"><div><img class="hof-campaign__guide" src="<?php echo esc_url( $asset_uri . 'bullying/guia-bullying.png' ); ?>" alt="Guía sobre bullying" width="420" height="220" loading="lazy" decoding="async"><h2>¿Qué es el bullying?</h2><p>El acoso entre pares afecta el bienestar, el desarrollo y el libre ejercicio de derechos. Reconocerlo temprano permite intervenir sin minimizar el daño ni responsabilizar a quien lo padece.</p><p>Hay bullying cuando la agresión se repite, existe desequilibrio de poder y la víctima queda en una posición de vulnerabilidad.</p></div><div class="hof-campaign__guide-art"><img src="<?php echo esc_url( $asset_uri . 'bullying/hay-bullying-cuando.png' ); ?>" alt="Hay bullying cuando" width="500" height="245" loading="lazy" decoding="async"><img src="<?php echo esc_url( $asset_uri . 'bullying/1-2-3-4.png' ); ?>" alt="Indicadores de bullying" width="500" height="245" loading="lazy" decoding="async"></div></div></section>
		<section class="hof-campaign__section hof-campaign__section--sand"><div class="hof-campaign__container"><header class="hof-campaign__section-heading"><img class="hof-campaign__number-logo" src="<?php echo esc_url( $asset_uri . 'bullying/bullying-en-numeros.png' ); ?>" alt="Bullying en números" width="430" height="130" loading="lazy" decoding="async"><h2>Datos para dimensionar el problema</h2></header><div class="hof-campaign__stat-grid"><?php foreach ( array( array( '29%', 'de estudiantes cordobeses admitieron que insultan, amenazan o agreden siempre o muchas veces.', 'Estudio Aprender, 2017' ), array( '46%', 'de chicas y chicos de 13 a 15 años sufrió acoso o tuvo una pelea física en el contexto escolar.', 'UNICEF Argentina, 2018' ), array( '63%', 'de una muestra de 3.500 estudiantes de secundario fue víctima de bullying.', 'Informe Uniciencia, 2015' ) ) as $stat ) : ?><article><strong><?php echo esc_html( $stat[0] ); ?></strong><p><?php echo esc_html( $stat[1] ); ?></p><small><?php echo esc_html( $stat[2] ); ?></small></article><?php endforeach; ?></div></div></section>
		<section class="hof-campaign__section hof-campaign__section--white" id="roles"><div class="hof-campaign__container"><header class="hof-campaign__section-heading"><img class="hof-campaign__roles-logo" src="<?php echo esc_url( $asset_uri . 'bullying/roles.png' ); ?>" alt="Roles" width="420" height="140" loading="lazy" decoding="async"><h2>Los roles importan</h2><p>Comprender qué lugar ocupa cada persona permite intervenir mejor y cortar la cadena del acoso.</p></header><div class="hof-campaign__role-grid"><?php foreach ( array( array( 'img-acosado.png', 'Acosado', 'Necesita acompañamiento, escucha y una intervención adulta que no lo exponga más.' ), array( 'agresor.png', 'Agresor', 'Debe asumir responsabilidad, reparar el daño y recibir límites claros.' ), array( 'img-espectador.png', 'Espectador', 'Puede cortar la cadena del acoso pidiendo ayuda y no amplificando la violencia.' ) ) as $role ) : ?><article><img src="<?php echo esc_url( $asset_uri . 'bullying/' . $role[0] ); ?>" alt="" width="120" height="120" loading="lazy" decoding="async"><h3><?php echo esc_html( $role[1] ); ?></h3><p><?php echo esc_html( $role[2] ); ?></p></article><?php endforeach; ?></div></div></section>
		<section class="hof-campaign__section hof-campaign__section--paper" id="difundir"><div class="hof-campaign__container"><header class="hof-campaign__section-heading"><h2>Difundí</h2><p>Piezas originales de la campaña para compartir.</p></header><div class="hof-campaign__download-grid"><?php foreach ( array( 'H1.png', 'H2.png', 'H3.png' ) as $piece ) : ?><a href="<?php echo esc_url( $asset_uri . 'bullying/' . $piece ); ?>" download><img src="<?php echo esc_url( $asset_uri . 'bullying/' . $piece ); ?>" alt="Pieza gráfica de la campaña Hay Otra Forma" width="1080" height="1800" loading="lazy" decoding="async"><span>Descargar imagen</span></a><?php endforeach; ?></div></div></section>
	<?php endif; ?>
</main>
