/**
 * Navegacion principal y submenus accesibles.
 */
( function () {
	'use strict';

	const navigation = document.querySelector( '.primary-navigation' );
	if ( ! navigation ) {
		return;
	}

	const menuButton = navigation.querySelector( '.menu-toggle' );
	const menu = navigation.querySelector( '#primary-menu' );
	const submenuButtons = navigation.querySelectorAll( '.submenu-toggle' );
	const mobileQuery = window.matchMedia( '(max-width: 74.9375rem)' );
	const openLabel = menuButton ? menuButton.dataset.openLabel : '';
	const closeLabel = menuButton ? menuButton.dataset.closeLabel : '';

	if ( ! menuButton || ! menu ) {
		return;
	}

	function closeSubmenus( exceptButton ) {
		submenuButtons.forEach( function ( button ) {
			if ( button !== exceptButton ) {
				button.setAttribute( 'aria-expanded', 'false' );
				button.closest( '.menu-item' ).classList.remove( 'submenu-open' );
			}
		} );
	}

	function closeMenu( restoreFocus ) {
		navigation.classList.remove( 'is-open' );
		menuButton.setAttribute( 'aria-expanded', 'false' );
		menuButton.setAttribute( 'aria-label', openLabel );
		closeSubmenus();
		document.body.classList.remove( 'menu-is-open' );

		if ( restoreFocus ) {
			menuButton.focus();
		}
	}

	menuButton.addEventListener( 'click', function () {
		const isOpen = navigation.classList.toggle( 'is-open' );
		menuButton.setAttribute( 'aria-expanded', String( isOpen ) );
		menuButton.setAttribute( 'aria-label', isOpen ? closeLabel : openLabel );
		document.body.classList.toggle( 'menu-is-open', isOpen );
	} );

	submenuButtons.forEach( function ( button ) {
		const submenu = button.parentElement.querySelector( ':scope > .sub-menu' );
		if ( submenu ) {
			submenu.id = submenu.id || `submenu-${ button.closest( '.menu-item' ).id }`;
			button.setAttribute( 'aria-controls', submenu.id );
		}

		button.addEventListener( 'click', function () {
			const item = button.closest( '.menu-item' );
			const willOpen = button.getAttribute( 'aria-expanded' ) !== 'true';

			closeSubmenus( button );
			button.setAttribute( 'aria-expanded', String( willOpen ) );
			item.classList.toggle( 'submenu-open', willOpen );
		} );
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( 'Escape' !== event.key ) {
			return;
		}

		const openSubmenu = navigation.querySelector( '.submenu-toggle[aria-expanded="true"]' );
		if ( openSubmenu ) {
			closeSubmenus();
			openSubmenu.focus();
			return;
		}

		if ( navigation.classList.contains( 'is-open' ) ) {
			closeMenu( true );
		}
	} );

	document.addEventListener( 'click', function ( event ) {
		if ( mobileQuery.matches && navigation.classList.contains( 'is-open' ) && ! navigation.contains( event.target ) ) {
			closeMenu( false );
		}
	} );

	mobileQuery.addEventListener( 'change', function ( event ) {
		if ( ! event.matches ) {
			closeMenu( false );
		}
	} );
}() );
