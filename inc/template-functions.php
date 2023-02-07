<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package csomaster
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function csomaster_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'csomaster_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function csomaster_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'csomaster_pingback_header' );

// register acf options page
if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'Theme General Settings',
		'menu_title'	=> 'School Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'icon_url' 		=> 'dashicons-location',
		'redirect'		=> false
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Theme Header Settings',
		'menu_title'	=> 'Header',
		'parent_slug'	=> 'theme-general-settings',
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Theme Footer Settings',
		'menu_title'	=> 'Footer',
		'parent_slug'	=> 'theme-general-settings',
	));
	
}
function get_school_details() {
	$school_details = array();

    $school_details['school_address'] = get_field('school_address', 'option');
    $school_details['school_phone'] = get_field('school_phone', 'option');
    $school_details['school_email'] = get_field('school_email', 'option');
    $school_details['school_social'] = get_field('school_social', 'option');

	return $school_details;
}

function get_term_dates( $args = array() ) {
	$classes = !empty($args['classes']) ? $args['classes'] : 'term-dates';

	$output = '';

	$dates = get_field('school_term_dates', 'option');

	if( have_rows('school_term_dates', 'option') ):
		$output .= '<ul class="'.$classes.'">';
		while ( have_rows('school_term_dates', 'option') ) : the_row();
			$term = get_sub_field('term') === 'false' ? '' : '<span>'.get_sub_field('term').' </span>  ';

			$output .= '<li>'.$term.get_sub_field('date_text').'</li>';
		endwhile;
		$output .= '</ul>';
	endif;

	return $output;
}

function the_term_dates( $args = array() ) {
	$classes = !empty($args['classes']) ? $args['classes'] : 'term-dates';

	$output = array();

	$dates = get_field('school_term_dates', 'option');

	if( have_rows('school_term_dates', 'option') ):
		while ( have_rows('school_term_dates', 'option') ) : the_row();
			$term = get_sub_field('term') === 'false' ? '' : get_sub_field('term') ;
			$output[] = array('term' => $term, 'text' => get_sub_field('date_text') );
		endwhile;
	endif;

	return $output;
}

// register and then enqueue all the navigation menus for the site
function csomaster_register_nav_menu() {
	register_nav_menu('header-primary', 'Header Menu');
	register_nav_menu('header-utility', 'Header Utility Menu');
	register_nav_menu('header-auxiliary', 'Header Auxiliary');
	register_nav_menu('mega', 'Mega Menu');
	register_nav_menu('footer', 'Footer Menu');
	register_nav_menu('footer-auxiliary', 'Footer Auxiliary Menu');
}

add_action( 'init', 'csomaster_register_nav_menu' );

function csomaster_nav_location($location, $args = array()) {

	$defaultArgs =  array( 
		'theme_location' => $location, 
		'container_class' => "nav-$location" );

	wp_nav_menu( array_merge($defaultArgs, $args) ); 
}


add_filter( 'wp_nav_menu_items', 'csomaster_utility_menu_item', 10, 2 );

function csomaster_utility_menu_item ( $items, $args ) {
    if ( $args->theme_location == 'header-utility') {
        $items .= '<li class="btn-search menu-item-btn"><a title="Search" href="#" >Search <img src="'.get_template_directory_uri().'/images/icons/search.svg" class="menu-icon" /></a></li>';
        $items .= '<li class="btn-menu menu-item-btn"><a title="Menu" href="#" >Menu <img src="'.get_template_directory_uri().'/images/icons/menu.svg" class="menu-icon" /></a></li>';
		$items .= '<li class="btn-close menu-item-btn"><a title="Menu" href="#" >Close <img src="'.get_template_directory_uri().'/images/icons/close.svg" class="menu-icon" /></a></li>';
    }
    return $items;
}

function csomaster_excerpt_length($length){ return 25; } 
add_filter('excerpt_length', 'csomaster_excerpt_length');


function get_header_data($pageId = null) {
    $header_data = array();

    $header_data['logo'] = get_field('logo', 'option');
    $header_data['logo_white'] = get_field('logo_white', 'option');

	$header_data['navigation_style'] = get_field('navigation_style', 'option');
	$header_data['primary_navigation_background_color'] = get_field('primary_navigation_background_color', 'option');

	$header_data['auxiliary_navigation_display'] = get_field('auxiliary_navigation_display', 'option');
	$header_data['auxiliary_navigation_background_color'] = get_field('auxiliary_navigation_background_color', 'option');
	$header_data['auxiliary_navigation_background_color_inverse'] = get_field('auxiliary_navigation_background_color_inverse', 'option');

	$header_data['auxiliary_navigation_text'] = get_field('auxiliary_navigation_text', 'option');
	$header_data['mega_navigation_background_color'] = get_field('mega_navigation_background_color', 'option');

    //$header_data['navigation_background_color'] = get_field('navigation_background_color', 'option');
    //$header_data['auxiliary_navigation_background_color'] = get_field('auxiliary_navigation_background_color', 'option');

    $header_data['header_title'] = get_field('header_title', $pageId);
    $header_data['header_text'] = get_field('header_text', $pageId);
    $header_data['header_text_alignment'] = get_field('header_text_alignment', $pageId);

	if(empty($header_data['header_title'])) $header_data['header_title'] = get_the_title($pageId);

    $header_data['header_text_cta'] = get_field('header_text_cta', $pageId); // Group with two properties: text and link
    $header_data['header_text_cta_secondary'] = get_field('header_text_cta_secondary', $pageId); // Group with two properties: text and link

	$header_data['header_text_cta']['classes'] = 'btn-primary';
	$header_data['header_text_cta_secondary']['classes'] = 'btn-secondary';

    $header_data['header_style'] = get_field('header_style', $pageId);
    $header_data['header_background_color'] = get_field('header_background_color', $pageId);
    $header_data['header_text_color'] = get_field('header_text_color', $pageId);

    $header_data['header_image'] = get_field('header_image', $pageId);
    $header_data['header_image_mobile'] = get_field('header_image_mobile', $pageId);
    $header_data['header_video'] = get_field('header_video', $pageId);

	$header_data['header_gradient'] = get_field('header_gradient', $pageId);

	if(is_single()) {
		$header_data['header_text_alignment'] = 'text-left';
	}

	if(is_search()) {
		$header_data['header_style'] = 'alternative';
		$header_data['header_text_alignment'] = 'text-left';
		$header_data['header_background_color'] = 'has-secondary-light-background-color';
		$header_data['header_title'] = 'Search results for: '.get_search_query();
		$header_data['header_text'] = null;
		$header_data['header_text_color'] = 'has-black-color';
		$header_data['header_text_cta'] = null;
		$header_data['header_text_cta_secondary'] = null;
	}

	if(is_404()) {
		$header_data['header_style'] = 'alternative';
		$header_data['header_text_alignment'] = 'text-left';
		$header_data['header_background_color'] = 'has-secondary-light-background-color';
		$header_data['header_title'] = 'Oops! That page can&rsquo;t be found.';
		$header_data['header_text'] = 'It looks like nothing was found at this location. Maybe try one of the links below or a search?';
	}

    return $header_data;
}

// Standard block fields, for use within the ACF content loop
function get_block_fields($pageId = null) {
	$fields = array();

	$fields['layout'] = get_row_layout();

	$fields['title'] = get_acf_value('_title', 'sub', $pageId);
	$fields['subtitle'] = get_acf_value('_subtitle', 'sub', $pageId);
	$fields['content'] = get_acf_value('_content', 'sub', $pageId);

	$fields['image'] = get_acf_image('_image', 'full', 'sub', $pageId);

	$fields['image_url'] = get_acf_value('_image', 'sub', $pageId);
	$fields['video'] = get_acf_value('_video', 'sub', $pageId);
	$fields['caption'] = get_acf_value('_caption', 'sub', $pageId);

	$fields['background_color'] = get_acf_value('_background_color', 'sub', $pageId);
	$fields['text_color'] = get_acf_value('_text_color', 'sub', $pageId);

	$fields['position'] = get_acf_value('_position', 'sub', $pageId);
	$fields['style'] = get_acf_value('_style', 'sub', $pageId);

    $fields['cta'] = get_sub_field('_cta', 'sub', $pageId); // Group with two properties: text and link
    $fields['cta_secondary'] = get_sub_field('_cta_secondary', 'sub', $pageId); // Group with two properties: text and link

	$fields['school_style'] = get_acf_value('_school_style', 'sub', $pageId);

	if( empty($fields['school_style']) ) {
		$fields['school_style'] = false;
	}

	if( empty($fields['cta']) ) {
		$fields['cta'] = array();
	}
	if( empty($fields['cta_secondary']) ) {
		$fields['cta_secondary'] = array();
	}
	$fields['cta']['classes'] = 'btn-primary';
	$fields['cta_secondary']['classes'] = 'btn-secondary';

	return $fields;
}
