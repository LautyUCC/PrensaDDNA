/** Lightweight, progressively enhanced home carousels. */
( function () {
	'use strict';

	document.querySelectorAll( '[data-carousel]' ).forEach( function ( carousel ) {
		const track = carousel.querySelector( '[data-carousel-track]' );
		const previous = carousel.querySelector( '[data-carousel-previous]' );
		const next = carousel.querySelector( '[data-carousel-next]' );
		const reducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' );

		if ( ! track || ! previous || ! next ) {
			return;
		}

		function updateControls() {
			const maxScroll = Math.max( 0, track.scrollWidth - track.clientWidth );
			previous.disabled = track.scrollLeft <= 2;
			next.disabled = track.scrollLeft >= maxScroll - 2;
			carousel.classList.toggle( 'carousel-has-overflow', maxScroll > 2 );
		}

		function move( direction ) {
			track.scrollBy( { left: direction * track.clientWidth, behavior: reducedMotion.matches ? 'auto' : 'smooth' } );
		}

		previous.addEventListener( 'click', function () { move( -1 ); } );
		next.addEventListener( 'click', function () { move( 1 ); } );
		track.addEventListener( 'scroll', updateControls, { passive: true } );
		track.addEventListener( 'keydown', function ( event ) {
			if ( 'ArrowLeft' === event.key || 'ArrowRight' === event.key ) {
				event.preventDefault();
				move( 'ArrowLeft' === event.key ? -1 : 1 );
			}
		} );

		if ( 'ResizeObserver' in window ) {
			new ResizeObserver( updateControls ).observe( track );
		} else {
			window.addEventListener( 'resize', updateControls );
		}

		updateControls();
	} );
}() );
