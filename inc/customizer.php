<?php
/**
 * csomaster Theme Customizer
 *
 * @package csomaster
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function csomaster_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'csomaster_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'csomaster_customize_partial_blogdescription',
			)
		);
	}
}
add_action( 'customize_register', 'csomaster_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function csomaster_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function csomaster_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function csomaster_customize_preview_js() {
	wp_enqueue_script( 'csomaster-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), _S_VERSION, true );
}
add_action( 'customize_preview_init', 'csomaster_customize_preview_js' );



// Remove WP features 99.99% of people don't need
/*
function mr_customise_wp() {
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	add_filter( 'emoji_svg_url', '__return_false' );
	add_filter( 'show_recent_comments_widget_style', '__return_false', 99 );
	add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
	add_filter( 'rest_enabled', '_return_false' );
	add_filter( 'rest_jsonp_enabled', '_return_false' );
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
}
add_action( 'init', 'mr_customise_wp' );
*/

/* 
	Remove all the extra styles and junk WP adds
*/
remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );
