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

 	$search_background_image = get_field('search_background_image', 'option');
    $search_background_style = "background: linear-gradient(0deg, var(--primary-dark) 0%, rgba(0,0,0,0.4) 100%), url($search_background_image); background-position: top center;";
?>

	<?php get_template_part( 'template-parts/navs/nav', 'footer' ); ?>
	<div class="site-search-outer closed" id="siteSearch" style="<?= $search_background_style ?>">
		<div class="site-search-inner">
			<div class="site-search-nav">
				<div class="site-logo-wrap">
					<?= get_acf_image('logo_white', 'full', 'main', 'option', 'logo') ?>
				</div>
				<div class="btn-close menu-item-btn"><a title="Menu" href="#" id="closeSearch" >Close <img src="<?= get_template_directory_uri().'/images/icons/close.svg'; ?>" class="menu-icon" /></a></div>
			</div>
			<?= get_search_form(); ?>
			<!--
			<form action="/" method="get" class="search-form">
				<input type="text" name="s" id="search" value="<?php the_search_query(); ?>" placeholder="Search" />
				<input type="submit" value="" />
			</form>
			-->
		</div>
	</div>
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
