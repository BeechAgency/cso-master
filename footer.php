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

<?php 
	wp_footer(); 
	
	if( !empty($GLOBALS['DEVELOPMENT_MODE']) && $GLOBALS['DEVELOPMENT_MODE'] === true ) : ?>
	<div class="xy-indicator">
		<span class="xl">XL</span>
		<span class="lg">LG</span>
		<span class="md">MD</span>
		<span class="sm">SM</span>
	</div>
	<?php endif; ?>
</body>
</html>
