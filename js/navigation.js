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

	const allMenuItems = megaMenu.querySelectorAll('.menu-item');
	const parentIds = [];

	// Get all the parent Ids
	allMenuItems.forEach( item => {
		if(!item.dataset.parentId) return false;

		parentIds.push(item.dataset.parentId);
	});

	// Loop through menu items and add a class to parents with no children to hide the arrow
	allMenuItems.forEach( item => {
		const groupId = item.dataset.groupId;
		if(parentIds.includes(groupId)) return false;

		item.classList.add('no-children');
	})

	console.log(parentIds);


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
