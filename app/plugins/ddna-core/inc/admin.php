<?php
/**
 * Interfaz editorial nativa para los campos institucionales.
 *
 * @package DDNA_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Añade un metabox por tipo de contenido.
 */
function ddna_core_add_meta_boxes() {
	foreach ( array_keys( ddna_core_get_field_groups() ) as $post_type ) {
		add_meta_box(
			'ddna-core-details',
			__( 'Información institucional', 'ddna-core' ),
			'ddna_core_render_meta_box',
			$post_type,
			'normal',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'ddna_core_add_meta_boxes' );

/**
 * Oculta el editor de metadatos sin procesar en los CPT propios.
 */
function ddna_core_remove_raw_custom_fields_boxes() {
	foreach ( array_keys( ddna_core_get_field_groups() ) as $post_type ) {
		remove_meta_box( 'postcustom', $post_type, 'normal' );
	}
}
add_action( 'admin_menu', 'ddna_core_remove_raw_custom_fields_boxes' );

/**
 * Imprime los campos del metabox.
 *
 * @param WP_Post $post Contenido editado.
 */
function ddna_core_render_meta_box( $post ) {
	$groups = ddna_core_get_field_groups();
	$fields = isset( $groups[ $post->post_type ] ) ? $groups[ $post->post_type ] : array();

	wp_nonce_field( 'ddna_core_save_meta', 'ddna_core_meta_nonce' );

	echo '<table class="form-table" role="presentation"><tbody>';
	foreach ( $fields as $meta_key => $field ) {
		$value = get_post_meta( $post->ID, $meta_key, true );
		if ( '_ddna_home_order' === $meta_key && '' === $value ) {
			$value = $post->menu_order;
		}
		if ( '' === $value && isset( $field['default'] ) ) {
			$value = $field['default'];
		}
		$field_id = 'ddna-field-' . sanitize_html_class( ltrim( $meta_key, '_' ) );

		echo '<tr><th scope="row"><label for="' . esc_attr( $field_id ) . '">' . esc_html( $field['label'] ) . '</label></th><td>';
		ddna_core_render_field( $meta_key, $field_id, $field, $value );
		if ( ! empty( $field['description'] ) ) {
			echo '<p class="description">' . esc_html( $field['description'] ) . '</p>';
		}
		echo '</td></tr>';
	}
	echo '</tbody></table>';
}

/**
 * Renderiza un control individual.
 *
 * @param string              $meta_key Clave del campo.
 * @param string              $field_id ID HTML.
 * @param array<string,mixed> $field    Definición.
 * @param mixed               $value    Valor actual.
 */
function ddna_core_render_field( $meta_key, $field_id, $field, $value ) {
	$name = 'ddna_core_fields[' . $meta_key . ']';

	switch ( $field['type'] ) {
		case 'select':
			echo '<select class="regular-text" id="' . esc_attr( $field_id ) . '" name="' . esc_attr( $name ) . '">';
			foreach ( $field['options'] as $option_value => $option_label ) {
				echo '<option value="' . esc_attr( $option_value ) . '" ' . selected( $value, $option_value, false ) . '>' . esc_html( $option_label ) . '</option>';
			}
			echo '</select>';
			break;
		case 'textarea':
			echo '<textarea class="large-text" rows="4" id="' . esc_attr( $field_id ) . '" name="' . esc_attr( $name ) . '">' . esc_textarea( $value ) . '</textarea>';
			break;
		case 'checkbox':
			echo '<label><input type="checkbox" id="' . esc_attr( $field_id ) . '" name="' . esc_attr( $name ) . '" value="1" ' . checked( (bool) $value, true, false ) . '> ' . esc_html__( 'Sí', 'ddna-core' ) . '</label>';
			break;
		case 'media':
			$attachment_name = $value ? basename( (string) get_attached_file( (int) $value ) ) : __( 'Ningún archivo seleccionado', 'ddna-core' );
			echo '<div class="ddna-media-field">';
			echo '<input type="hidden" id="' . esc_attr( $field_id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '">';
			echo '<span class="ddna-media-field__name">' . esc_html( $attachment_name ) . '</span> ';
			echo '<button type="button" class="button ddna-select-media" data-target="' . esc_attr( $field_id ) . '">' . esc_html__( 'Seleccionar archivo', 'ddna-core' ) . '</button> ';
			echo '<button type="button" class="button-link-delete ddna-remove-media" data-target="' . esc_attr( $field_id ) . '">' . esc_html__( 'Quitar', 'ddna-core' ) . '</button>';
			echo '</div>';
			break;
		default:
			$input_type = in_array( $field['type'], array( 'date', 'email', 'url', 'number' ), true ) ? $field['type'] : 'text';
			$attributes = '';
			foreach ( array( 'min', 'max', 'step' ) as $attribute ) {
				if ( isset( $field[ $attribute ] ) ) {
					$attributes .= ' ' . $attribute . '="' . esc_attr( $field[ $attribute ] ) . '"';
				}
			}
			echo '<input class="regular-text" type="' . esc_attr( $input_type ) . '" id="' . esc_attr( $field_id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '"' . $attributes . '>';
	}
}

/**
 * Guarda y sanea campos institucionales.
 *
 * @param int $post_id ID del contenido.
 */
function ddna_core_save_meta_box( $post_id ) {
	if ( ! isset( $_POST['ddna_core_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ddna_core_meta_nonce'] ) ), 'ddna_core_save_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$post_type = get_post_type( $post_id );
	$groups    = ddna_core_get_field_groups();
	if ( ! isset( $groups[ $post_type ] ) ) {
		return;
	}

	$submitted = isset( $_POST['ddna_core_fields'] ) && is_array( $_POST['ddna_core_fields'] ) ? wp_unslash( $_POST['ddna_core_fields'] ) : array();
	foreach ( $groups[ $post_type ] as $meta_key => $field ) {
		$raw_value = isset( $submitted[ $meta_key ] ) ? $submitted[ $meta_key ] : ( 'checkbox' === $field['type'] ? false : '' );
		if ( ! is_scalar( $raw_value ) && ! is_bool( $raw_value ) ) {
			$raw_value = '';
		}
		$value     = ddna_core_sanitize_field( $raw_value, $field['type'] );

		if ( '' === $value || ( 'media' === $field['type'] && 0 === $value ) ) {
			delete_post_meta( $post_id, $meta_key );
		} else {
			update_post_meta( $post_id, $meta_key, $value );
		}
	}

	if ( in_array( $post_type, array( 'programa', 'campana' ), true ) && isset( $submitted['_ddna_home_order'] ) ) {
		remove_action( 'save_post', 'ddna_core_save_meta_box' );
		wp_update_post(
			array(
				'ID'         => $post_id,
				'menu_order' => absint( $submitted['_ddna_home_order'] ),
			)
		);
		add_action( 'save_post', 'ddna_core_save_meta_box' );
	}
}
add_action( 'save_post', 'ddna_core_save_meta_box' );

/**
 * Carga el selector de Medios sólo en los CPT administrados.
 *
 * @param string $hook_suffix Pantalla administrativa.
 */
function ddna_core_enqueue_admin_assets( $hook_suffix ) {
	if ( ! in_array( $hook_suffix, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || ! array_key_exists( $screen->post_type, ddna_core_get_field_groups() ) ) {
		return;
	}

	wp_enqueue_media();
	wp_enqueue_script(
		'ddna-core-admin-media',
		DDNA_CORE_URL . 'assets/js/admin-media.js',
		array(),
		DDNA_CORE_VERSION,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'ddna_core_enqueue_admin_assets' );
