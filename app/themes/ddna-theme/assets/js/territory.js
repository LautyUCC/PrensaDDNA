/** Accessible marker/popover interaction for the Territory panel. */
( function () {
	'use strict';

	const maps = Array.from( document.querySelectorAll( '[data-territory-map]' ) );
	if ( ! maps.length ) { return; }

	function closeMap( map, restoreFocus ) {
		const marker = map.querySelector( '[data-territory-marker][aria-expanded="true"]' );
		map.querySelectorAll( '[data-territory-marker]' ).forEach( function ( item ) { item.setAttribute( 'aria-expanded', 'false' ); } );
		map.querySelectorAll( '[data-territory-popover]' ).forEach( function ( item ) { item.hidden = true; } );
		if ( restoreFocus && marker ) { marker.focus(); }
	}

	maps.forEach( function ( map ) {
		const markers = Array.from( map.querySelectorAll( '[data-territory-marker]' ) );
		map.addEventListener( 'click', function ( event ) {
			let marker = event.target.closest( '[data-territory-marker]' );
			if ( ! marker || ! map.contains( marker ) ) { return; }

			// Partition overlapping hit areas by their nearest centre. Pin anchors and
			// venue coordinates remain unchanged; keyboard activation keeps its target.
			if ( event.detail !== 0 ) {
				let nearestDistance = Infinity;
				markers.forEach( function ( candidate ) {
					const rect = candidate.getBoundingClientRect();
					if ( ! rect.width || ! rect.height || event.clientX < rect.left || event.clientX > rect.right || event.clientY < rect.top || event.clientY > rect.bottom ) { return; }
					const distance = Math.hypot( event.clientX - rect.left - rect.width / 2, event.clientY - rect.top - rect.height / 2 );
					if ( distance < nearestDistance ) { nearestDistance = distance; marker = candidate; }
				} );
			}

			const popover = document.getElementById( marker.getAttribute( 'aria-controls' ) );
			if ( ! popover ) { return; }
			const opening = 'true' !== marker.getAttribute( 'aria-expanded' );
			maps.forEach( function ( item ) { closeMap( item, false ); } );
			marker.setAttribute( 'aria-expanded', String( opening ) );
			popover.hidden = ! opening;
			marker.focus( { preventScroll: true } );
		} );
		map.querySelectorAll( '[data-territory-close]' ).forEach( function ( close ) {
			close.addEventListener( 'click', function () { closeMap( map, true ); } );
		} );
	} );

	document.addEventListener( 'click', function ( event ) {
		maps.forEach( function ( map ) {
			const openPopover = map.querySelector( '[data-territory-popover]:not([hidden])' );
			const clickedMarker = event.target.closest( '[data-territory-marker]' );
			if ( openPopover && ! openPopover.contains( event.target ) && ! clickedMarker ) { closeMap( map, false ); }
		} );
	} );

	window.addEventListener( 'keydown', function ( event ) {
		if ( 'Escape' !== event.key ) { return; }
		const openMap = maps.find( function ( map ) { return map.querySelector( '[data-territory-marker][aria-expanded="true"]' ); } );
		if ( openMap ) {
			event.preventDefault();
			event.stopPropagation();
			closeMap( openMap, true );
		}
	}, true );
}() );
