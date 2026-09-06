/**
 * Desktop nav dropdown hover — syncs with Kadence submenu toggles.
 */
(function () {
	'use strict';

	var DESKTOP_QUERY = '(min-width: 1024px)';

	function isDesktopNav() {
		return window.matchMedia( DESKTOP_QUERY ).matches;
	}

	function openSubmenu( li ) {
		if ( window.kadence && typeof window.kadence.toggleSubMenu === 'function' ) {
			if ( ! li.classList.contains( 'menu-item--toggled-on' ) ) {
				window.kadence.toggleSubMenu( li, true );
			}
			return;
		}

		li.classList.add( 'menu-item--toggled-on' );
		var sub = li.querySelector( '.sub-menu' );
		if ( sub ) {
			sub.classList.add( 'opened', 'toggle-show' );
		}
	}

	function closeSubmenu( li ) {
		if ( window.kadence && typeof window.kadence.toggleSubMenu === 'function' ) {
			if ( li.classList.contains( 'menu-item--toggled-on' ) ) {
				window.kadence.toggleSubMenu( li, false );
			}
			return;
		}

		li.classList.remove( 'menu-item--toggled-on' );
		var sub = li.querySelector( '.sub-menu' );
		if ( sub ) {
			sub.classList.remove( 'opened', 'toggle-show' );
		}
	}

	function closeAllExcept( except ) {
		document.querySelectorAll( '#site-navigation .menu-item-has-children.menu-item--toggled-on' ).forEach( function ( li ) {
			if ( li !== except ) {
				closeSubmenu( li );
			}
		} );
	}

	function initDesktopDropdowns() {
		var nav = document.querySelector( '#site-navigation' );
		if ( ! nav || nav.dataset.superbDropdownInit ) {
			return;
		}

		nav.dataset.superbDropdownInit = '1';

		nav.querySelectorAll( '.menu-item-has-children' ).forEach( function ( li ) {
			li.addEventListener( 'mouseenter', function () {
				if ( ! isDesktopNav() ) {
					return;
				}
				closeAllExcept( li );
				openSubmenu( li );
			} );

			li.addEventListener( 'mouseleave', function ( event ) {
				if ( ! isDesktopNav() ) {
					return;
				}
				if ( event.relatedTarget && li.contains( event.relatedTarget ) ) {
					return;
				}
				closeSubmenu( li );
			} );

			li.addEventListener( 'focusin', function () {
				if ( ! isDesktopNav() ) {
					return;
				}
				closeAllExcept( li );
				openSubmenu( li );
			} );

			li.addEventListener( 'focusout', function ( event ) {
				if ( ! isDesktopNav() ) {
					return;
				}
				if ( event.relatedTarget && li.contains( event.relatedTarget ) ) {
					return;
				}
				closeSubmenu( li );
			} );
		} );
	}

	document.addEventListener( 'DOMContentLoaded', initDesktopDropdowns );

	window.addEventListener( 'resize', function () {
		if ( isDesktopNav() ) {
			return;
		}
		document.querySelectorAll( '#site-navigation .menu-item-has-children.menu-item--toggled-on' ).forEach( closeSubmenu );
	} );
} )();
