/**
 * File slide-navigation.js.
 *
 * Handles the big slidy boy of the nav. Use inconjunction with some tasty CSS.
 */
console.log('asd');

 ( function() {
	const siteNavigation = document.getElementById( 'megaMenuWrapper' );

	// Return early if the navigation don't exist.
	if ( ! siteNavigation ) { return; }

	const button = document.querySelector( '.header-primary-nav .btn-menu' );

	// Return early if the button don't exist.
	if ( 'undefined' === typeof button ) { return; }

	const closeButton = document.querySelector( '#megaMenuWrapper .btn-close' );

	// Return early if the button don't exist.
	if ( 'undefined' === typeof closeButton ) { return; }


	// Toggle the .toggled class and the aria-expanded value each time the button is clicked.
	button.addEventListener( 'click', function() {
		siteNavigation.classList.toggle( 'open' );

		if ( siteNavigation.getAttribute( 'aria-expanded' ) === 'true' ) {
			siteNavigation.setAttribute( 'aria-expanded', 'false' );
		} else {
			siteNavigation.setAttribute( 'aria-expanded', 'true' );
		}
	} );
    
} () );
