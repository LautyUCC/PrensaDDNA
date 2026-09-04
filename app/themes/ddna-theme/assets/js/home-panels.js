/** Accessible single-open-panel behavior for the Home landing. */
( function () {
	'use strict';

	const root = document.querySelector( '[data-home-panels]' );
	const triggers = Array.from( document.querySelectorAll( '[data-home-panel-trigger]' ) );
	const panels = Array.from( document.querySelectorAll( '[data-home-panel]' ) );
	const reducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' );
	const closeTimers = new WeakMap();
	let activeId = '';

	if ( ! root || ! triggers.length || ! panels.length ) {
		return;
	}

	function getPanel( id ) {
		return panels.find( function ( panel ) { return panel.dataset.homePanel === id; } );
	}

	function getTrigger( id ) {
		return triggers.find( function ( trigger ) { return trigger.dataset.homePanelTrigger === id; } );
	}

	function closePanel( panel, immediate ) {
		const timer = closeTimers.get( panel );
		if ( timer ) {
			window.clearTimeout( timer );
		}
		panel.classList.remove( 'is-open' );
		const finish = function () { panel.hidden = true; };
		if ( reducedMotion.matches || immediate ) {
			finish();
		} else {
			closeTimers.set( panel, window.setTimeout( finish, 240 ) );
		}
	}

	function openPanel( panel ) {
		const timer = closeTimers.get( panel );
		if ( timer ) {
			window.clearTimeout( timer );
		}
		panel.hidden = false;
		window.requestAnimationFrame( function () {
			window.requestAnimationFrame( function () { panel.classList.add( 'is-open' ); } );
		} );
	}

	function updateUrl( id, replace ) {
		const url = new URL( window.location.href );
		url.hash = id ? `#${ id }` : '';
		window.history[ replace ? 'replaceState' : 'pushState' ]( { ddnaPanel: id }, '', url );
	}

	function scrollToPanel( panel ) {
		window.requestAnimationFrame( function () {
			const header = document.querySelector( '.site-header' );
			const offset = header && 'sticky' === window.getComputedStyle( header ).position ? header.offsetHeight + 16 : 16;
			const top = panel.getBoundingClientRect().top + window.scrollY - offset;
			window.scrollTo( { top: Math.max( 0, top ), behavior: reducedMotion.matches ? 'auto' : 'smooth' } );
		} );
	}

	function activate( id, options ) {
		const settings = Object.assign( { history: false, replace: false, scroll: false, restoreFocus: false }, options );
		const nextPanel = getPanel( id );
		const previousId = activeId;

		panels.forEach( function ( panel ) {
			if ( panel !== nextPanel && ! panel.hidden ) {
				closePanel( panel, Boolean( nextPanel ) );
			}
		} );

		triggers.forEach( function ( trigger ) {
			trigger.setAttribute( 'aria-expanded', String( trigger.dataset.homePanelTrigger === id && Boolean( nextPanel ) ) );
		} );

		activeId = nextPanel ? id : '';
		if ( nextPanel ) {
			openPanel( nextPanel );
		}

		if ( settings.history ) {
			updateUrl( activeId, settings.replace );
		}

		if ( settings.scroll && nextPanel ) {
			scrollToPanel( nextPanel );
		}

		if ( settings.restoreFocus && previousId ) {
			const previousTrigger = getTrigger( previousId );
			if ( previousTrigger ) { previousTrigger.focus(); }
		}
	}

	triggers.forEach( function ( trigger ) {
		trigger.addEventListener( 'click', function () {
			const id = trigger.dataset.homePanelTrigger;
			if ( activeId === id ) {
				activate( '', { history: true } );
				return;
			}
			activate( id, { history: true, scroll: true } );
		} );
	} );

	root.querySelectorAll( '[data-inner-accordion]' ).forEach( function ( accordion ) {
		accordion.querySelectorAll( '.knowledge-accordion__trigger' ).forEach( function ( trigger ) {
			trigger.addEventListener( 'click', function () {
				const panel = document.getElementById( trigger.getAttribute( 'aria-controls' ) );
				if ( ! panel ) { return; }
				const expanded = 'true' === trigger.getAttribute( 'aria-expanded' );
				trigger.setAttribute( 'aria-expanded', String( ! expanded ) );
				panel.hidden = expanded;
			} );
		} );
	} );

	root.querySelectorAll( '[data-home-panel-close]' ).forEach( function ( button ) {
		button.addEventListener( 'click', function () {
			activate( '', { history: true, restoreFocus: true } );
		} );
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( 'Escape' === event.key && activeId ) {
			event.preventDefault();
			activate( '', { history: true, restoreFocus: true } );
		}
	} );

	window.addEventListener( 'popstate', function () {
		activate( window.location.hash.slice( 1 ), { scroll: Boolean( window.location.hash ) } );
	} );

	const initialId = window.location.hash.slice( 1 );
	if ( getPanel( initialId ) ) {
		activate( initialId, { scroll: true } );
	} else {
		activate( '' );
	}
}() );
