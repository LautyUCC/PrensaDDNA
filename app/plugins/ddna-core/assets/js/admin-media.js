/**
 * Selector nativo de archivos para metaboxes de DDNA Core.
 */
( function () {
	'use strict';

	document.addEventListener( 'click', function ( event ) {
		const selectButton = event.target.closest( '.ddna-select-media' );
		const removeButton = event.target.closest( '.ddna-remove-media' );

		if ( selectButton ) {
			event.preventDefault();
			const target = document.getElementById( selectButton.dataset.target );
			const wrapper = selectButton.closest( '.ddna-media-field' );
			const frame = wp.media( {
				title: 'Seleccionar archivo institucional',
				button: { text: 'Usar este archivo' },
				multiple: false,
			} );

			frame.on( 'select', function () {
				const attachment = frame.state().get( 'selection' ).first().toJSON();
				target.value = attachment.id;
				wrapper.querySelector( '.ddna-media-field__name' ).textContent = attachment.filename;
			} );
			frame.open();
		}

		if ( removeButton ) {
			event.preventDefault();
			const target = document.getElementById( removeButton.dataset.target );
			target.value = '';
			removeButton.closest( '.ddna-media-field' ).querySelector( '.ddna-media-field__name' ).textContent = 'Ningún archivo seleccionado';
		}
	} );
}() );
