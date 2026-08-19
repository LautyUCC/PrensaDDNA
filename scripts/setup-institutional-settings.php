<?php
/** Initial values corroborated between the approved PDF and the site audit. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }

$verified = array(
	'assistance_label' => 'Línea Asistencia',
	'assistance_phone' => '351 402-0503',
	'adolescence_label' => 'Línea Adolescencia',
	'adolescence_phone' => '351 239-8953',
	'address' => 'Dámaso Larrañaga 94 · B° Nueva Córdoba',
	'phone' => '+54 351 428-8888',
	'email' => 'consulta.defensoria@cba.gov.ar',
	'facebook_url' => 'https://www.facebook.com/DefensoriaCba/',
	'instagram_url' => 'https://www.instagram.com/defensoriacba/',
	'x_url' => 'https://x.com/DefensoriaCba',
	'youtube_url' => '',
	'google_play_url' => '',
);

if ( false === get_option( 'ddna_institutional', false ) ) {
	add_option( 'ddna_institutional', $verified );
	WP_CLI::success( 'Datos institucionales verificados cargados.' );
} else {
	WP_CLI::log( 'La configuración institucional ya existe; no fue sobrescrita.' );
}
