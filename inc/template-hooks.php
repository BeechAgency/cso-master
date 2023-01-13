<?php 
/* Hooks to make enhancing functionality to the site possible through child themes */

function csomaster_header() {
	do_action('csomaster_header');
}

function csomaster_before_header() {
	do_action('csomaster_before_header');
}

function csomaster_after_header() {
	do_action('csomaster_after_header');
}

function csomaster_before_custom_header_text_layout() {
	do_action('csomaster_before_custom_header_text_layout');
}

function csomaster_after_custom_header_text_layout() {
	do_action('csomaster_after_custom_header_text_layout');
}


add_filter('csomaster_custom_header_data', 'csomaster_set_custom_header_data');

if ( ! function_exists( 'csomaster_set_custom_header_data' ) ) :
    function csomaster_set_custom_header_data( $header_data ) {
        $header_data = get_header_data();

        return $header_data;
    }
endif;
