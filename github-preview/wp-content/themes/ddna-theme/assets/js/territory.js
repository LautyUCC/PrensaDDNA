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
		map.querySelectorAll( '[data-territory-marker]' ).forEach( function ( marker ) {
			const popover = document.getElementById( marker.getAttribute( 'aria-controls' ) );
			if ( ! popover ) { return; }
			marker.addEventListener( 'click', function () {
				const opening = 'true' !== marker.getAttribute( 'aria-expanded' );
				maps.forEach( function ( item ) { closeMap( item, false ); } );
				marker.setAttribute( 'aria-expanded', String( opening ) );
				popover.hidden = ! opening;
			} );
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
