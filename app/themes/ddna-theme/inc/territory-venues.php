<?php
/**
 * Datos públicos del mapa de subsedes.
 *
 * Las coordenadas se anclan al lienzo 1024 × 1536 de mapa-cordoba.png. Se
 * conservaron los seis puntos ya calibrados y los nuevos se ubicaron tomando
 * esas referencias y la posición geográfica relativa de cada localidad.
 *
 * @package DDNA_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Devuelve la única fuente de datos usada por marcadores y fichas del mapa.
 *
 * Las notas de participación MUNA se guardan para control editorial y no se
 * presentan como una afirmación pública sobre participación activa.
 *
 * @param array<string, mixed> $institutional Datos institucionales existentes.
 * @return array<int, array<string, mixed>>
 */
function ddna_theme_territory_venues( array $institutional = array() ) {
	return array(
		array(
			'id' => 'cordoba-capital', 'name' => 'Córdoba Capital', 'department' => '',
			'address' => $institutional['address'] ?? '', 'phone' => $institutional['phone'] ?? '', 'email' => $institutional['email'] ?? '',
			'hours' => '', 'map_url' => '', 'left' => '38.5%', 'top' => '35.2%', 'stack' => 5, 'popover_left' => '48%', 'popover_top' => '25%',
			'muna' => false, 'muna_participation_note' => '',
		),
		array(
			'id' => 'colonia-caroya', 'name' => 'Colonia Caroya', 'department' => 'Colón',
			'address' => 'José Alfredo Nanini 4195', 'phone' => '3525 461180 / 351 2399757', 'email' => 'defensoria.caroya@gmail.com',
			'hours' => 'Lunes a viernes, 7:30 a 13:30 hs.', 'map_url' => 'https://maps.app.goo.gl/2R8wnViTg8zMsXXE8',
			'left' => '41%', 'top' => '29.8%', 'stack' => 4, 'popover_left' => '49%', 'popover_top' => '16%',
			'muna' => true, 'muna_participation_note' => '',
		),
		array(
			'id' => 'sierras-chicas-salsipuedes', 'name' => 'Sierras Chicas — Salsipuedes', 'department' => 'Colón — alcance: corredor Sierras Chicas',
			'address' => 'Sarmiento esquina 25 de Mayo, Bº El Pueblito, Salsipuedes', 'phone' => '3518006322', 'email' => 'ddnaregionalsierraschicas@gmail.com',
			'hours' => 'Lunes a viernes, 8:00 a 14:00 hs., presencial.', 'map_url' => '',
			'left' => '36.5%', 'top' => '31.6%', 'stack' => 4, 'popover_left' => '47%', 'popover_top' => '19%',
			'muna' => true, 'muna_participation_note' => 'sin participación',
		),
		array(
			'id' => 'cosquin', 'name' => 'Cosquín', 'department' => 'Punilla',
			'address' => 'Catamarca 554 esq. Santa Fé', 'phone' => '3512398546', 'email' => 'subsededefensoriacosquin@gmail.com',
			'hours' => 'Lunes a viernes, 8:00 a 14:00 hs.', 'map_url' => 'https://maps.app.goo.gl/6npxL6ySjViyZu7z7',
			'left' => '32%', 'top' => '32.8%', 'stack' => 3, 'popover_left' => '42%', 'popover_top' => '22%',
			'muna' => true, 'muna_participation_note' => 'sin participación',
		),
		array(
			'id' => 'capilla-del-monte', 'name' => 'Capilla del Monte', 'department' => '',
			'address' => '', 'phone' => '', 'email' => '', 'hours' => 'Información institucional pendiente de actualización.', 'map_url' => '',
			'left' => '31.2%', 'top' => '27.7%', 'stack' => 3, 'popover_left' => '41%', 'popover_top' => '12%',
			'muna' => false, 'muna_participation_note' => '',
		),
		array(
			'id' => 'cruz-del-eje', 'name' => 'Cruz del Eje', 'department' => 'Cruz del Eje',
			'address' => 'General Paz 168', 'phone' => '3512473851', 'email' => 'defensoriacde@gmail.com',
			'hours' => "Lunes y jueves, 8:00 a 12:00 hs.\nMartes, miércoles y viernes, 15:00 a 18:00 hs.", 'map_url' => 'https://maps.app.goo.gl/E9wVDLZa2dyqLDvn6',
			'left' => '26.5%', 'top' => '23.5%', 'stack' => 3, 'popover_left' => '37%', 'popover_top' => '14%',
			'muna' => true, 'muna_participation_note' => '',
		),
		array(
			'id' => 'rio-tercero', 'name' => 'Río Tercero', 'department' => 'Tercero Arriba',
			'address' => 'Hilario Cuadros 433', 'phone' => '3571570950 / 3571649583', 'email' => 'ddnariotercero@gmail.com',
			'hours' => 'Lunes a viernes, 8:00 a 13:00 hs.', 'map_url' => '',
			'left' => '43.2%', 'top' => '43.3%', 'stack' => 3, 'popover_left' => '51%', 'popover_top' => '36%',
			'muna' => true, 'muna_participation_note' => '',
		),
		array(
			'id' => 'laguna-larga', 'name' => 'Laguna Larga', 'department' => 'Río Segundo',
			'address' => 'Hipólito Yrigoyen esquina Julio A. Roca', 'phone' => '351 2021792', 'email' => 'defensorialagunalarga@gmail.com',
			'hours' => 'Lunes a viernes, 9:00 a 13:00 hs.', 'map_url' => '',
			'left' => '49.6%', 'top' => '40%', 'stack' => 3, 'popover_left' => '56%', 'popover_top' => '31%',
			'muna' => true, 'muna_participation_note' => '',
		),
		array(
			'id' => 'las-varillas', 'name' => 'Las Varillas', 'department' => 'San Justo',
			'address' => 'España Nº 51', 'phone' => '3533/434410', 'email' => 'ddnalasvarillas@gmail.com',
			'hours' => 'Lunes a viernes, 8:00 a 14:00 hs.', 'map_url' => '',
			'left' => '71.1%', 'top' => '41.1%', 'stack' => 3, 'popover_left' => '48%', 'popover_top' => '36%',
			'muna' => true, 'muna_participation_note' => '',
		),
		array(
			'id' => 'san-francisco', 'name' => 'San Francisco', 'department' => 'San Justo',
			'address' => 'Av. Garibaldi esq. Suipacha', 'phone' => '', 'email' => 'ddna.sanfrancisco@gmail.com',
			'hours' => 'Lunes a viernes, 7:30 a 13:00 hs.', 'map_url' => '',
			'left' => '85.2%', 'top' => '35.5%', 'stack' => 3, 'popover_left' => '48%', 'popover_top' => '28%',
			'muna' => true, 'muna_participation_note' => '',
		),
		array(
			'id' => 'villa-cura-brochero', 'name' => 'Villa Cura Brochero', 'department' => 'San Alberto',
			'address' => 'Av. Cura Gaucho 52', 'phone' => '3544 614290', 'email' => 'defensoriacurabrochero@gmail.com',
			'hours' => 'Presencial: martes, miércoles y jueves, 9:00 a 14:00 hs. Online: lunes y viernes, 9:00 a 14:00 hs.', 'map_url' => '',
			'left' => '21.5%', 'top' => '45.5%', 'stack' => 3, 'popover_left' => '34%', 'popover_top' => '38%',
			'muna' => false, 'muna_participation_note' => '',
		),
		array(
			'id' => 'rio-cuarto', 'name' => 'Río Cuarto', 'department' => 'Río Cuarto',
			'address' => 'C. Caseros 1020 (Centro Cívico)', 'phone' => '3584021171', 'email' => 'ddnario4@gmail.com',
			'hours' => 'Presencial: lunes a viernes, 8:00 a 14:00 hs. Por la tarde, vía telefónica.', 'map_url' => '',
			'left' => '34.5%', 'top' => '56.5%', 'stack' => 3, 'popover_left' => '44%', 'popover_top' => '50%',
			'muna' => true, 'muna_participation_note' => '',
		),
		array(
			'id' => 'bell-ville', 'name' => 'Bell Ville', 'department' => 'Unión',
			'address' => '', 'phone' => '', 'email' => 'subsedebvddnn@gmail.com',
			'hours' => 'Lunes a viernes, 8:00 a 14:00 hs.', 'map_url' => '',
			'left' => '70.5%', 'top' => '58%', 'stack' => 3, 'popover_left' => '25%', 'popover_top' => '52%',
			'muna' => false, 'muna_participation_note' => '',
		),
		array(
			'id' => 'justiniano-posse', 'name' => 'Justiniano Posse', 'department' => 'Unión',
			'address' => '9 de Julio y Belgrano', 'phone' => '3518006748', 'email' => 'defensoria.jposse@gmail.com',
			'hours' => 'Lunes a viernes, 8:00 a 14:00 hs.', 'map_url' => 'https://maps.app.goo.gl/o78SrNxFpaE1Cw9v8',
			'left' => '69.5%', 'top' => '51.5%', 'stack' => 3, 'popover_left' => '35%', 'popover_top' => '43%',
			'muna' => true, 'muna_participation_note' => 'sin participación',
		),
		array(
			'id' => 'laboulaye', 'name' => 'Laboulaye', 'department' => 'Presidente Roque Sáenz Peña',
			'address' => 'España 186', 'phone' => '351 239-8252', 'email' => 'ddnalaboulaye@gmail.com',
			'hours' => 'Lunes a viernes, 8:00 a 14:00 hs.', 'map_url' => '',
			'left' => '60.2%', 'top' => '82.4%', 'stack' => 3, 'popover_left' => '35%', 'popover_top' => '66%',
			'muna' => false, 'muna_participation_note' => '',
		),
	);
}
