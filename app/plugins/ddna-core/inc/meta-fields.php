<?php
/**
 * Campos estructurados del modelo institucional.
 *
 * @package DDNA_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Devuelve la definición central de campos por tipo de contenido.
 *
 * @return array<string, array<string, array<string, mixed>>>
 */
function ddna_core_get_field_groups() {
	$status_options = array(
		'active'   => __( 'Activo', 'ddna-core' ),
		'planned'  => __( 'Próximo', 'ddna-core' ),
		'paused'   => __( 'Pausado', 'ddna-core' ),
		'finished' => __( 'Finalizado / histórico', 'ddna-core' ),
	);

	return array(
		'programa' => array(
			'_ddna_home_order'   => array( 'label' => __( 'Orden en la portada', 'ddna-core' ), 'type' => 'number', 'min' => 0, 'description' => __( 'Los números menores aparecen primero.', 'ddna-core' ) ),
			'_ddna_status'       => array( 'label' => __( 'Estado', 'ddna-core' ), 'type' => 'select', 'options' => $status_options, 'default' => 'active' ),
			'_ddna_start_date'   => array( 'label' => __( 'Fecha de inicio', 'ddna-core' ), 'type' => 'date' ),
			'_ddna_end_date'     => array( 'label' => __( 'Fecha de finalización', 'ddna-core' ), 'type' => 'date' ),
			'_ddna_contact_email'=> array( 'label' => __( 'Correo de contacto', 'ddna-core' ), 'type' => 'email' ),
			'_ddna_external_url' => array( 'label' => __( 'Sitio o micrositio', 'ddna-core' ), 'type' => 'url' ),
			'_ddna_link_pending' => array( 'label' => __( 'Enlace pendiente de definición', 'ddna-core' ), 'type' => 'checkbox' ),
			'_ddna_featured'     => array( 'label' => __( 'Destacar en listados', 'ddna-core' ), 'type' => 'checkbox' ),
		),
		'campana' => array(
			'_ddna_home_order'   => array( 'label' => __( 'Orden en la portada', 'ddna-core' ), 'type' => 'number', 'min' => 0, 'description' => __( 'Los números menores aparecen primero.', 'ddna-core' ) ),
			'_ddna_status'       => array( 'label' => __( 'Estado', 'ddna-core' ), 'type' => 'select', 'options' => $status_options, 'default' => 'active' ),
			'_ddna_start_date'   => array( 'label' => __( 'Fecha de inicio', 'ddna-core' ), 'type' => 'date' ),
			'_ddna_end_date'     => array( 'label' => __( 'Fecha de finalización', 'ddna-core' ), 'type' => 'date' ),
			'_ddna_cta_label'    => array( 'label' => __( 'Texto del llamado a la acción', 'ddna-core' ), 'type' => 'text' ),
			'_ddna_external_url' => array( 'label' => __( 'URL de campaña o acción', 'ddna-core' ), 'type' => 'url' ),
			'_ddna_link_pending' => array( 'label' => __( 'Enlace pendiente de definición', 'ddna-core' ), 'type' => 'checkbox' ),
			'_ddna_featured'     => array( 'label' => __( 'Destacar en listados', 'ddna-core' ), 'type' => 'checkbox' ),
		),
		'documento' => array(
			'_ddna_file_id'          => array( 'label' => __( 'Archivo institucional', 'ddna-core' ), 'type' => 'media' ),
			'_ddna_external_url'     => array( 'label' => __( 'URL externa alternativa', 'ddna-core' ), 'type' => 'url', 'description' => __( 'Usar sólo si el archivo aún no puede alojarse en la Biblioteca de Medios.', 'ddna-core' ) ),
			'_ddna_year'             => array( 'label' => __( 'Año', 'ddna-core' ), 'type' => 'number', 'min' => 1900, 'max' => 2100 ),
			'_ddna_publication_date' => array( 'label' => __( 'Fecha de publicación institucional', 'ddna-core' ), 'type' => 'date' ),
			'_ddna_version'          => array( 'label' => __( 'Versión / edición', 'ddna-core' ), 'type' => 'text' ),
			'_ddna_issuer'           => array( 'label' => __( 'Organismo emisor', 'ddna-core' ), 'type' => 'text' ),
			'_ddna_accessible'       => array( 'label' => __( 'Documento accesible verificado', 'ddna-core' ), 'type' => 'checkbox' ),
		),
		'capacitacion' => array(
			'_ddna_status'           => array( 'label' => __( 'Estado', 'ddna-core' ), 'type' => 'select', 'options' => array( 'planned' => __( 'Próxima', 'ddna-core' ), 'registration' => __( 'Inscripción abierta', 'ddna-core' ), 'ongoing' => __( 'En curso', 'ddna-core' ), 'finished' => __( 'Finalizada', 'ddna-core' ), 'cancelled' => __( 'Cancelada', 'ddna-core' ) ), 'default' => 'planned' ),
			'_ddna_start_date'       => array( 'label' => __( 'Fecha de inicio', 'ddna-core' ), 'type' => 'date' ),
			'_ddna_end_date'         => array( 'label' => __( 'Fecha de finalización', 'ddna-core' ), 'type' => 'date' ),
			'_ddna_modality'         => array( 'label' => __( 'Modalidad', 'ddna-core' ), 'type' => 'select', 'options' => array( 'onsite' => __( 'Presencial', 'ddna-core' ), 'online' => __( 'Virtual', 'ddna-core' ), 'hybrid' => __( 'Híbrida', 'ddna-core' ) ) ),
			'_ddna_location'         => array( 'label' => __( 'Lugar o plataforma', 'ddna-core' ), 'type' => 'text' ),
			'_ddna_duration'         => array( 'label' => __( 'Duración', 'ddna-core' ), 'type' => 'text' ),
			'_ddna_registration_url' => array( 'label' => __( 'URL de inscripción', 'ddna-core' ), 'type' => 'url' ),
			'_ddna_contact_email'    => array( 'label' => __( 'Correo de contacto', 'ddna-core' ), 'type' => 'email' ),
			'_ddna_cost'             => array( 'label' => __( 'Arancel / condición', 'ddna-core' ), 'type' => 'text', 'description' => __( 'Indicar “Gratuita” cuando corresponda.', 'ddna-core' ) ),
		),
		'subsede' => array(
			'_ddna_status'      => array( 'label' => __( 'Estado', 'ddna-core' ), 'type' => 'select', 'options' => array( 'active' => __( 'Activa', 'ddna-core' ), 'temporary' => __( 'Atención temporal', 'ddna-core' ), 'closed' => __( 'Cerrada / histórica', 'ddna-core' ) ), 'default' => 'active' ),
			'_ddna_address'     => array( 'label' => __( 'Dirección', 'ddna-core' ), 'type' => 'text' ),
			'_ddna_locality'    => array( 'label' => __( 'Localidad', 'ddna-core' ), 'type' => 'text' ),
			'_ddna_department'  => array( 'label' => __( 'Departamento', 'ddna-core' ), 'type' => 'text' ),
			'_ddna_postcode'    => array( 'label' => __( 'Código postal', 'ddna-core' ), 'type' => 'text' ),
			'_ddna_phone'       => array( 'label' => __( 'Teléfono', 'ddna-core' ), 'type' => 'text' ),
			'_ddna_whatsapp'    => array( 'label' => __( 'WhatsApp', 'ddna-core' ), 'type' => 'text' ),
			'_ddna_email'       => array( 'label' => __( 'Correo', 'ddna-core' ), 'type' => 'email' ),
			'_ddna_hours'       => array( 'label' => __( 'Días y horarios', 'ddna-core' ), 'type' => 'textarea' ),
			'_ddna_map_url'     => array( 'label' => __( 'Enlace al mapa', 'ddna-core' ), 'type' => 'url' ),
			'_ddna_latitude'    => array( 'label' => __( 'Latitud', 'ddna-core' ), 'type' => 'number', 'step' => 'any' ),
			'_ddna_longitude'   => array( 'label' => __( 'Longitud', 'ddna-core' ), 'type' => 'number', 'step' => 'any' ),
			'_ddna_coverage'    => array( 'label' => __( 'Cobertura territorial', 'ddna-core' ), 'type' => 'textarea' ),
			'_ddna_responsible' => array( 'label' => __( 'Responsable / referencia', 'ddna-core' ), 'type' => 'text' ),
		),
		'recurso' => array(
			'_ddna_resource_type' => array( 'label' => __( 'Tipo de recurso', 'ddna-core' ), 'type' => 'select', 'options' => array( 'download' => __( 'Descarga', 'ddna-core' ), 'video' => __( 'Video / playlist', 'ddna-core' ), 'website' => __( 'Sitio o micrositio', 'ddna-core' ), 'app' => __( 'Aplicación', 'ddna-core' ), 'form' => __( 'Formulario', 'ddna-core' ), 'other' => __( 'Otro', 'ddna-core' ) ) ),
			'_ddna_file_id'       => array( 'label' => __( 'Archivo', 'ddna-core' ), 'type' => 'media' ),
			'_ddna_external_url'  => array( 'label' => __( 'URL externa', 'ddna-core' ), 'type' => 'url' ),
			'_ddna_cta_label'     => array( 'label' => __( 'Texto del enlace', 'ddna-core' ), 'type' => 'text' ),
			'_ddna_featured'      => array( 'label' => __( 'Destacar en listados', 'ddna-core' ), 'type' => 'checkbox' ),
		),
	);
}

/**
 * Sanitiza valores según el tipo declarado.
 *
 * @param mixed  $value Valor recibido.
 * @param string $type  Tipo de campo.
 * @return mixed
 */
function ddna_core_sanitize_field( $value, $type ) {
	switch ( $type ) {
		case 'checkbox':
			return (bool) $value;
		case 'number':
			return is_numeric( $value ) ? (float) $value : '';
		case 'media':
			return absint( $value );
		case 'email':
			return sanitize_email( $value );
		case 'url':
			return esc_url_raw( $value );
		case 'textarea':
			return sanitize_textarea_field( $value );
		case 'date':
			if ( ! is_scalar( $value ) || ! preg_match( '/^(\d{4})-(\d{2})-(\d{2})$/', (string) $value, $matches ) ) {
				return '';
			}
			return checkdate( (int) $matches[2], (int) $matches[3], (int) $matches[1] ) ? (string) $value : '';
		default:
			return sanitize_text_field( $value );
	}
}

/**
 * Comprueba permisos REST sobre metadatos.
 *
 * @param bool   $allowed   Resultado previo.
 * @param string $meta_key  Clave de metadato.
 * @param int    $object_id ID del contenido.
 * @return bool
 */
function ddna_core_meta_auth_callback( $allowed, $meta_key, $object_id ) {
	unset( $allowed, $meta_key );
	return current_user_can( 'edit_post', $object_id );
}

/**
 * Registra metadatos en WordPress y REST.
 */
function ddna_core_register_post_meta() {
	foreach ( ddna_core_get_field_groups() as $post_type => $fields ) {
		foreach ( $fields as $meta_key => $field ) {
			$type = in_array( $field['type'], array( 'media' ), true ) ? 'integer' : ( 'checkbox' === $field['type'] ? 'boolean' : ( 'number' === $field['type'] ? 'number' : 'string' ) );
			register_post_meta(
				$post_type,
				$meta_key,
				array(
					'single'            => true,
					'type'              => $type,
					'show_in_rest'      => true,
					'sanitize_callback' => function ( $value ) use ( $field ) {
						return ddna_core_sanitize_field( $value, $field['type'] );
					},
					'auth_callback'     => 'ddna_core_meta_auth_callback',
				)
			);
		}
	}
}
add_action( 'init', 'ddna_core_register_post_meta' );
