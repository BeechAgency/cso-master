/**
 * File navigation.js.
 *
 * Handles the big slidy boy of the nav. Use inconjunction with some tasty CSS.
 */
//console.log('nav v5');

// Handle the mega nav
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


	const body = document.querySelector('body');

	function menuButtonToggle( ) {
		const siteNavigation = document.getElementById( 'megaMenuWrapper' );

		if ( siteNavigation.getAttribute( 'aria-expanded' ) === 'true' ) {
			// Close the nav
			siteNavigation.setAttribute( 'aria-expanded', 'false' );
			body.setAttribute( 'aria-menu-expanded', 'false' );

			siteNavigation.classList.add( 'closing' );

			setTimeout( function() { 
				siteNavigation.classList.toggle( 'open' );
				siteNavigation.classList.remove( 'closing' );
			}, 500 );

		} else {
			// Open the nav
			siteNavigation.setAttribute( 'aria-expanded', 'true' );
			body.setAttribute( 'aria-menu-expanded', 'true' );

			siteNavigation.classList.add( 'opening' );

			setTimeout( function() { 
				siteNavigation.classList.toggle( 'open' );
				siteNavigation.classList.remove( 'opening' );
			}, 1 );
		}
	}


	// Toggle the .toggled class and the aria-expanded value each time the button is clicked.
	button.addEventListener( 'click', menuButtonToggle );


	// Toggle the .toggled class and the aria-expanded value each time the button is clicked.
	closeButton.addEventListener( 'click', menuButtonToggle );



	// Handle the searchy boi
	const searchWrapper = document.getElementById( 'siteSearch' );
	const closeSearchButton = document.querySelector( '#siteSearch .btn-close' );
	const searchButtons = document.querySelectorAll('.btn-search');

	if ( ! body || ! searchWrapper || 'undefined' === typeof closeButton || !searchButtons ) { return; }

	//console.log('Doing search mode v2');

	//console.log(searchWrapper, searchButtons, closeButton);

	body.setAttribute( 'aria-menu-expanded', 'false' );


	searchButtons.forEach( btn => {
		btn.addEventListener('click', function() {

			searchWrapper.classList.add( 'opening' );
	
			setTimeout( function() { 
				searchWrapper.classList.toggle( 'closed' );
				searchWrapper.classList.remove( 'opening' );
			}, 150 );
	
			body.setAttribute( 'aria-menu-expanded', 'true' );
		});
	})

	closeSearchButton.addEventListener('click', function() {
		searchWrapper.classList.add( 'closing' );

		setTimeout( function() { 
			searchWrapper.classList.toggle( 'closed' );
			searchWrapper.classList.remove( 'closing' );
		}, 500 );
		

		if(siteNavigation.getAttribute('aria-expanded') === 'true') {
			//console.log("Togglin!");
			menuButtonToggle();
		} else {
			body.setAttribute( 'aria-menu-expanded', 'false' );
		}
		
	});


    
} () );


/* Mega Menu Interactions */ 
( function() {

	const megaMenu = document.getElementById( 'megaMenu' );

	if(!megaMenu) return false;

	const megaMenuGroups = megaMenu.querySelectorAll( '.mega-menu-group' );
	const menuGroupLvl1 = megaMenu.querySelector('[data-menu-level="1"]');
	const menuGroupLvl2 = megaMenu.querySelector('[data-menu-level="2"]');

	megaMenuGroups.forEach (  menuGroup => {
		const dataset = menuGroup.dataset;

		if(!dataset) return false;
		if(dataset.menuLevel === '2') return false;

		const menuLevel = dataset.menuLevel;
		const items = menuGroup.querySelectorAll( '.menu-item' );

		// If no items return
		if(!items) return false;



		items.forEach( item => {
			const itemDataset = item.dataset;
			const groupId = itemDataset.groupId;

			const children = megaMenu.querySelectorAll( '.mega-menu-group .menu-item[data-parent-id="' + groupId + '"]' );

			item.addEventListener( 'mouseenter', function() {
				
				items.forEach( item => { item.classList.remove( 'active' ); });
				item.classList.add( 'active' );

				if(menuLevel === '0') {
					menuGroupLvl1.setAttribute( 'data-group-active', groupId );
				} else {
					menuGroupLvl2.setAttribute( 'data-group-active', groupId );
				}
				megaMenu.dispatchEvent( new CustomEvent( 'menu-item-active', { detail: { groupId, menuLevel } } ) );
			});
		});
	});

	megaMenu.addEventListener( 'menu-item-active', function( e ) {
		const detail = e.detail;
		const groupId = detail.groupId;
		const menuLevel = detail.menuLevel;

		const children = megaMenu.querySelectorAll( '.mega-menu-group[data-group-active="' + groupId + '"] .menu-item' );

		//console.log(menuLevel);

		children.forEach( child => {
			if(child.dataset.parentId === groupId) {

				child.classList.add( 'entering' );
				setTimeout( () => {
					child.classList.remove( 'entering' );
					child.classList.remove( 'hidden' );
				}, 1);

			} else {
				child.classList.add( 'hidden' );
			}
		});

		// If level 0 hide all level 2, so exit early
		if(menuLevel !== '0') return;

		//console.log('hiding level 2');

		const level2Items = megaMenu.querySelectorAll( '.mega-menu-group[data-menu-level="2"] .menu-item' );

		level2Items.forEach( item => {
			item.classList.add( 'hidden' );
		});


	});

} () );


/* Mobile Mega Menu Interactions */ 
( function() {
	const megaMenu = document.getElementById( 'megaMenuMobile' );
	if(!megaMenu) return false;

	const toggles = megaMenu.querySelectorAll('.item-toggle');

	toggles.forEach( toggle => {
		toggle.addEventListener('click', function() {
			const mode = toggle.classList.contains('open') ? 'open' : 'closed';
			const modeClass = mode === 'open' ? 'closing' : 'opening';
			const subMenu = toggle.nextElementSibling;


			toggle.classList.toggle('open');
			toggle.parentElement.classList.toggle('open');


			if(mode === 'closed') {
				subMenu.classList.toggle('opening');

				setTimeout( () => {
					// add a style attribute to subMenu
					//subMenu.style.height = subMenu.scrollHeight + 'px';
					subMenu.classList.toggle('opening');
					subMenu.classList.toggle('open');
				}, 10);
			} else {
				subMenu.classList.toggle('closing');

				setTimeout( () => {
					// add a style attribute to subMenu
					//subMenu.style.height = subMenu.scrollHeight + 'px';
					subMenu.classList.toggle('closing');
					subMenu.classList.toggle('open');
				}, 320);

			}
		});
	});

} () );



/*
 ( function() {
	const siteNavigation = document.getElementById( 'nav' );
	const button = document.querySelector( 'nav button' );
	const page = document.getElementById('page');
	const body = document.querySelector('body');

	// Return early if the navigation don't exist.
	if ( ! siteNavigation ) { return; }

	// Return early if the buttons don't exist.
	if ( 'undefined' === typeof button || button === null ) { return; }

	const toggleMenu = ( direction = null ) => {
		const span = button.querySelector('span');

		if(direction === 'close') {
			siteNavigation.classList.add('closed');
			page.setAttribute( 'aria-expanded', 'false' );
			span.innerText = 'menu';

			return;
		}

		if(direction === 'open') {
			siteNavigation.classList.remove('closed');
			page.setAttribute( 'aria-expanded', 'true' ); 
			body.setAttribute( 'aria-expanded', 'true' );
			span.innerText = 'chevron_left';

			return;
		}

		// Else just toggle it.
		siteNavigation.classList.toggle('closed');
		
		span.innerText === 'menu' ? page.setAttribute( 'aria-expanded', 'true' ) : page.setAttribute( 'aria-expanded', 'false' );
		span.innerText === 'menu' ? body.setAttribute( 'aria-expanded', 'true' ) : body.setAttribute( 'aria-expanded', 'false' );
		span.innerText = span.innerText === 'menu' ? 'chevron_left' : 'menu';

		return;
	}

	// Toggle the .toggled class and the aria-expanded value each time the button is clicked.
	button.addEventListener( 'click', function() {
		toggleMenu();

		return true;
	} );

	const menu_items = document.querySelectorAll('nav a');
	
	menu_items.forEach( item => {
		item.addEventListener('click', () => {

			body.setAttribute( 'aria-expanded', 'false' );
			toggleMenu('close');
			
		});
	});

}() );



*/

/**
 * Handle the scrolly boiz
 */
/*
( function() {
	document.addEventListener('DOMContentLoaded', function () {
		setTimeout(() => {

			//console.log(page_els);

			let pre = 0; // Store position of previous element offset;

			const handleSectionPercentage = (position, element_id, top, bottom) => {
				const height = bottom - top;
				const adjusted_position = position - top;

				const percent = Math.floor(adjusted_position / height * 100);
				const menu_item = document.querySelector('nav li[data-menu-for="'+element_id+'"]');

				//console.log(element_id, menu_item);

				menu_item.dataset.sectionScrollPercentage = percent;
			}

			const scrollPercentElement = document.querySelector('#scrollPercentage');


			const debugScrolling = (position, id, top, bottom) => {
				const scrollPosition = document.querySelector('#scrollPosition');
				const sectionActive = document.querySelector('#sectionActive');
				const sectionTop = document.querySelector('#sectionTop');
				const sectionEnd = document.querySelector('#sectionEnd');

				scrollPosition.innerText = position;
				sectionActive.innerText = id;
				sectionTop.innerText = top;
				sectionEnd.innerText = bottom;

				const scrollDebugEnd = document.querySelector('#scrollDebugEnd');
				const scrollDebugStart = document.querySelector('#scrollDebugStart');

				scrollDebugStart.setAttribute('style', 'top: '+top+'px');
				scrollDebugEnd.setAttribute('style', 'top: '+bottom+'px');
				

				return;
			}

			// Check offset on scroll
			document.addEventListener('scroll', function ( event ) {
				const actualPosition = window.scrollY;
				const position = actualPosition + (63); // 3.5rem is the padding the section has from the top.

				// Loop through page_els to check offest against position
				page_els.forEach( obj => {

					const el = obj.el;
					const el_offset = obj.top;
					//const previous_el = obj.previous;
					const next_el = obj.next;
					let el_offset_bottom = obj.bot;

					// Check if the menu item is an inner guy.
					if(obj?.tag === 'DIV' ) {
						// Check if there is a next element.
						if( next_el.top !== undefined ) { 
							// If there is a next element set the top of that as the bottom of the el.
							el_offset_bottom = next_el.top; 
						}
					}
					
					const target = document.querySelector('nav li[data-menu-for="#'+el.id+'"]');
					const section = document.querySelector('#'+el.id);

					


					if( (position > el_offset) && (position < el_offset_bottom) ) {
						target.classList.add('active');
						section.classList.add('active');

						//debugScrolling(position, el.id, el_offset, el_offset_bottom);

						if(obj?.tag === 'SECTION' ) {
							//handleSectionPercentage(position, obj.id, el_offset, el_offset_bottom);
						}

						//if( obj.id === '#karen' ) { console.warn(target, position, el_offset, el_offset_bottom ); }
					} else {
						target.classList.remove('active');
						section.classList.remove('active');

						//if( obj.id === '#karen' ) { console.log(target, position, el_offset, el_offset_bottom ); }
					}
					return;
				});


				let scrollTop = window.scrollY;
				let docHeight = document.body.offsetHeight;
				let winHeight = window.innerHeight;
				let scrollPercent = scrollTop / (docHeight - winHeight);
				let scrollPercentRounded = scrollPercent * 100;

				//console.log(scrollPercentRounded);

				if(!scrollPercentElement) return;

				scrollPercentElement.style = '--scroll-percent: '+scrollPercentRounded+'%;';

			}, {
				passive: true
			});

		}, 200);
	});

}() );

*/