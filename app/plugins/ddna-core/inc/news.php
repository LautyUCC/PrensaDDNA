<?php
/** Editorial de Novedades sobre entradas, etiquetas y medios nativos. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function ddna_core_is_news( $post_id ) {
	return 'post' === get_post_type( $post_id ) && has_term( 'novedad', 'post_tag', $post_id );
}

/** Solo imágenes válidas; conserva el orden elegido y elimina duplicados. */
function ddna_core_news_image_ids( $value ) {
	if ( is_string( $value ) ) { $value = explode( ',', $value ); }
	if ( ! is_array( $value ) ) { return array(); }
	return array_values( array_filter( array_unique( array_map( 'absint', $value ) ), 'wp_attachment_is_image' ) );
}

add_action( 'init', static function () {
	foreach ( array( '_ddna_news_enabled', '_ddna_news_carousel' ) as $key ) {
		register_post_meta( 'post', $key, array( 'single' => true, 'type' => 'boolean', 'show_in_rest' => true, 'sanitize_callback' => 'rest_sanitize_boolean', 'auth_callback' => 'ddna_core_meta_auth_callback' ) );
	}
	register_post_meta( 'post', '_ddna_news_gallery', array( 'single' => true, 'type' => 'array', 'show_in_rest' => array( 'schema' => array( 'type' => 'array', 'items' => array( 'type' => 'integer' ) ) ), 'sanitize_callback' => 'ddna_core_news_image_ids', 'auth_callback' => 'ddna_core_meta_auth_callback' ) );
} );

add_action( 'admin_menu', static function () {
	add_menu_page( 'Novedades', 'Novedades', 'edit_posts', 'edit.php?tag=novedad', '', 'dashicons-megaphone', 6 );
	add_submenu_page( 'edit.php?tag=novedad', 'Todas las novedades', 'Todas las novedades', 'edit_posts', 'edit.php?tag=novedad' );
	add_submenu_page( 'edit.php?tag=novedad', 'Añadir novedad', 'Añadir novedad', 'edit_posts', 'post-new.php?ddna_news=1' );
} );

add_action( 'add_meta_boxes_post', static function () {
	add_meta_box( 'ddna-news-editorial', 'Publicación en Novedades', 'ddna_core_news_meta_box', 'post', 'normal', 'high', array( '__block_editor_compatible_meta_box' => true ) );
} );

function ddna_core_news_meta_box( $post ) {
	$enabled = ddna_core_is_news( $post->ID ) || has_category( 'novedades', $post->ID ) || get_post_meta( $post->ID, '_ddna_news_enabled', true ) || ( 'auto-draft' === $post->post_status && isset( $_GET['ddna_news'] ) );
	$ids = ddna_core_news_image_ids( get_post_meta( $post->ID, '_ddna_news_gallery', true ) );
	wp_nonce_field( 'ddna_news_save', 'ddna_news_nonce' );
	?>
	<p><label><input type="checkbox" name="ddna_news_enabled" value="1" <?php checked( $enabled ); ?>> <strong>Mostrar esta entrada en Novedades</strong></label></p>
	<p>Al guardar se asigna automáticamente la etiqueta <strong>novedad</strong>, sin quitar otras etiquetas. Usá el título y el editor de contenido de WordPress. La <strong>Imagen destacada</strong> es la imagen principal; la fecha se administra en <strong>Publicación</strong>.</p>
	<p><label><input type="checkbox" name="ddna_news_carousel" value="1" <?php checked( (bool) get_post_meta( $post->ID, '_ddna_news_carousel', true ) ); ?>> <strong>Mostrar carrusel</strong></label></p>
	<div class="ddna-news-gallery">
		<input type="hidden" name="ddna_news_gallery" value="<?php echo esc_attr( implode( ',', $ids ) ); ?>" data-news-gallery-value>
		<p><button type="button" class="button" data-news-gallery-add>Agregar imágenes del carrusel</button></p>
		<p>Seleccioná imágenes de la Biblioteca de Medios. Usá Subir/Bajar para ordenar y Quitar para retirar una imagen del carrusel. La imagen principal se administra por separado. Si el carrusel está desactivado, las fotos adicionales se muestran como galería estática.</p>
		<ol data-news-gallery-list>
		<?php foreach ( $ids as $id ) : ?>
			<li data-image-id="<?php echo esc_attr( $id ); ?>">
				<?php echo wp_get_attachment_image( $id, 'thumbnail', false, array( 'style' => 'width:80px;height:60px;object-fit:contain;vertical-align:middle;' ) ); ?>
				<span><?php echo esc_html( get_the_title( $id ) ); ?></span>
				<button type="button" class="button" data-news-gallery-up>Subir</button>
				<button type="button" class="button" data-news-gallery-down>Bajar</button>
				<button type="button" class="button" data-news-gallery-remove>Quitar</button>
			</li>
		<?php endforeach; ?>
		</ol>
		<p class="screen-reader-text" aria-live="polite" data-news-gallery-status></p>
	</div>
	<?php
	$pending = get_post_meta( $post->ID, '_ddna_news_pending', true );
	if ( is_array( $pending ) && $pending ) { echo '<p><strong>Notas de la carga inicial:</strong></p><ul>'; foreach ( $pending as $note ) { echo '<li>' . esc_html( $note ) . '</li>'; } echo '</ul>'; }
}

/** Solo procesa el formulario autenticado; no altera autosaves, revisiones ni REST parciales. */
add_action( 'save_post_post', static function ( $post_id ) {
	if ( ! isset( $_POST['ddna_news_nonce'] ) || ! is_scalar( $_POST['ddna_news_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ddna_news_nonce'] ) ), 'ddna_news_save' ) || ! current_user_can( 'edit_post', $post_id ) || wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) { return; }
	$enabled = ! empty( $_POST['ddna_news_enabled'] );
	update_post_meta( $post_id, '_ddna_news_enabled', $enabled );
	update_post_meta( $post_id, '_ddna_news_carousel', ! empty( $_POST['ddna_news_carousel'] ) );
	$ids = isset( $_POST['ddna_news_gallery'] ) && is_scalar( $_POST['ddna_news_gallery'] ) ? ddna_core_news_image_ids( wp_unslash( $_POST['ddna_news_gallery'] ) ) : array();
	update_post_meta( $post_id, '_ddna_news_gallery', $ids );
	if ( $enabled ) {
		ddna_core_news_assign_terms( $post_id );
	} else {
		wp_remove_object_terms( $post_id, 'novedad', 'post_tag' );
		wp_remove_object_terms( $post_id, 'novedades', 'category' );
	}
} );

/** Mantiene la categoría histórica para URLs/menú; el tag es el criterio del frontend. */
function ddna_core_news_assign_terms( $post_id ) {
	if ( ! term_exists( 'novedad', 'post_tag' ) ) { wp_insert_term( 'Novedad', 'post_tag', array( 'slug' => 'novedad' ) ); }
	if ( ! term_exists( 'novedades', 'category' ) ) { wp_insert_term( 'Novedades', 'category', array( 'slug' => 'novedades' ) ); }
	wp_set_post_terms( $post_id, array( 'novedad' ), 'post_tag', true );
	$category = get_category_by_slug( 'novedades' );
	if ( $category ) { wp_set_post_categories( $post_id, array( $category->term_id ), true ); }
}

/** REST guarda términos después del post; este hook evita perder la clasificación automática. */
add_action( 'rest_after_insert_post', static function ( $post ) {
	if ( get_post_meta( $post->ID, '_ddna_news_enabled', true ) ) { ddna_core_news_assign_terms( $post->ID ); }
}, 20 );
add_action( 'wp_after_insert_post', static function ( $post_id, $post ) {
	if ( 'post' === $post->post_type && ! wp_is_post_revision( $post_id ) && get_post_meta( $post_id, '_ddna_news_enabled', true ) ) { ddna_core_news_assign_terms( $post_id ); }
}, 20, 2 );

add_action( 'admin_enqueue_scripts', static function ( $hook ) {
	$screen = get_current_screen();
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) || ! $screen || 'post' !== $screen->post_type ) { return; }
	wp_enqueue_media();
	wp_enqueue_script( 'ddna-news-editor', DDNA_CORE_URL . 'assets/js/news-editor.js', array( 'media-views' ), (string) filemtime( DDNA_CORE_PATH . 'assets/js/news-editor.js' ), true );
} );
