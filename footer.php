<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package csomaster
 */
?>

	<?php get_template_part( 'template-parts/navs/nav', 'footer' ); ?>
</main><!-- #page -->
<!--
<div id="scrollDebug">
	<div id="scrollPosition">0</div>
	<div id="sectionActive">0</div>
	<div id="sectionTop">0</div>
	<div id="sectionEnd">0</div>
</div>
<div id='scrollDebugStart' class="debug-line"></div>
<div id='scrollDebugEnd' class="debug-line"></div>
-->


<?php wp_footer(); 

/*
	$THEME_COLORS = $GLOBALS['THEME_COLORS'];

	var_dump($THEME_COLORS);

	$custom_colours = '';

	foreach($THEME_COLORS as $name => $hex) {
		$custom_colours .= "'$hex',' $name',";
	}

	var_dump($custom_colours);
	*/
?>

<div class="xy-indicator">
	<span class="xl">XL</span>
	<span class="lg">LG</span>
	<span class="md">MD</span>
	<span class="sm">SM</span>
</div>
<!-- START: Grid overlay code - remove when moving to production -->
<!-- Toggle the grid -->
<!--
<style id="grid-overlay-styles" type="text/css">
	:root {
		/* Colors */
		--celeste: #bdfffdff;
		--celeste-2: #9ffff5ff;
		--aquamarine: #7cffc4ff;
		--green-sheen: #6abea7ff;
		--black-coral: #5e6973ff;
	}

	section.grid-overlay.hidden {
		max-height: 0;
	}
	section.grid-overlay {
		background-color: var(--black);
	}
	section.grid-overlay.hidden .row {
		max-height: 0vh;
		background-color: var(--celeste-2);
	}

	section.grid-overlay.hidden .row .col {
		background-color: var(--black-coral);
	}

	section.grid-overlay {
		transition: all 150ms ease;

		max-height: 100vh;
		padding: 0;

		position: fixed;
		height: 100%;
		width: 100%;
		top: 0;
		left: 0;
		opacity: 0.5;
	}

	.grid-overlay .row {
		max-height: 100vh;
		height: 100%;
		background-color: var(--black-coral);
		transition: all 250ms ease;
	}
	.grid-overlay .row .col {
		background-color: var(--celeste);
		transition: all 550ms ease;
	}

	.button.overlay-toggle {
		position: fixed;
		top: 10rem;
		right: -3rem;
		z-index: 500;

		transition: all 150ms ease;
		border-radius: 100% 0 0 100%;
		padding: 2.5rem 1rem;
	}
	.button.overlay-toggle {
		border-color: var(--green-sheen);
		color: var(--white);
		background-color: var(--green-sheen);
	}
	.button.overlay-toggle.active {
		background-color: var(--celeste-2);
		border-color: var(--celeste-2);
		color: black;

		box-shadow: 5px 5px 3px 0px rgba(0,0,0,0.4);
		cursor: pointer;
	}

	.button.overlay-toggle.active:hover {
		box-shadow: 0px 0px 15px 5px rgba(0,0,0,0.4);
	}
	.button.overlay-toggle:hover {
		box-shadow: 0px 0px 15px 5px rgba(255,255,255,0.75);
		background-color: white;
		color: var(--green-sheen);
		border-color: white;
		right: 0rem;
	}
	.show-sections section:not(.grid-overlay), .show-sections .section {
		border-bottom: 7px dashed var(--green-sheen);
	}

	.show-sections section:not(.grid-overlay), .show-sections .section {
		position: relative;
	}
	.show-sections section:not(.grid-overlay)::before, .show-sections .section::before {
		content: attr(data-classes);
		position: absolute;
		top: 1rem;
		left: 1rem;
		border: 1px solid black;
		background-color: black;
		color: white;
		z-index: 1000;
	}
</style>

<section id="show-grid" class="grid-overlay hidden"><div class="container row"><div class="col"></div><div class="col"></div><div class="col"></div><div class="col"></div><div class="col"></div><div class="col"></div><div class="col"></div><div class="col"></div><div class="col"></div><div class="col"></div><div class="col"></div><div class="col"></div></div></section>
<button class="button overlay-toggle hidden" id="toggle" onclick="toggleOverlay()" >Grid</button>

<script type="text/javascript">
	const sections = document.querySelectorAll('section, .section');

	sections.forEach(section => {
		const classes = section.classList.toString();
		section.setAttribute('data-classes', classes);
	});

	const toggleOverlay = () => {
		const overlay = document.querySelector('#show-grid');
		const btn = document.querySelector('#toggle');
		const body = document.querySelector('body');

		let display = overlay.style.display;

		if(overlay.classList.contains('hidden')) {
			btn.classList.add('active');
			overlay.classList.remove('hidden');
			body.classList.add('show-sections');

		} else {
			btn.classList.remove('active');
			overlay.classList.add('hidden');
			body.classList.remove('show-sections');
		}
	}

	const toggleCSSVariable = (item) => {
		const targetVar = item.getAttribute('for');

		const valueEl = document.querySelector('input[name="'+targetVar+'"]');
		const value = valueEl.value;

		document.documentElement.style.setProperty(targetVar, value);
	}
</script>
-->
<!-- END: Grid overlay code - remove when moving to production -->
</body>
</html>
