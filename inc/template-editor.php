<?php

/**
 * Registers an editor stylesheet for the theme.
 */
function wpdocs_theme_add_editor_styles() {
    add_editor_style( 'editor-style.css' );
}
add_action( 'admin_init', 'wpdocs_theme_add_editor_styles' );

// Callback function to insert 'styleselect' into the $buttons array
function csomaster_mce_buttons_2( $buttons ) {
	array_unshift( $buttons, 'styleselect' );
	return $buttons;
}
// Register our callback to the appropriate filter
add_filter( 'mce_buttons_2', 'csomaster_mce_buttons_2' );

// Callback function to filter the MCE settings
function csomaster_mce_before_init_insert_formats( $init_array ) {  
	// Define the style_formats array
	$style_formats = array(  
		// Each array child is a format with it's own settings
		array(  
			'title' => 'Paragraph - Standard',  
			'block' => 'p',  
			'classes' => '',
			'wrapper' => false,
			
		),  
		array(  
			'title' => 'Paragraph - Large',  
			'block' => 'p',  
			'classes' => 'lg',
			'wrapper' => false,
			
		),  
		array(  
			'title' => 'Paragraph - Small',  
			'block' => 'p',  
			'classes' => 'sm',
			'wrapper' => false,
		),
		array(  
			'title' => 'Button',  
			'block' => 'a',  
			'classes' => 'btn btn-primary',
			'wrapper' => true,
			'attributes' => array('target' => '_blank')
		),
		array(  
			'title' => 'Button - Secondary',  
			'block' => 'a',  
			'classes' => 'btn btn-secondary',
			'wrapper' => true,
		)
	);  
	// Insert the array, JSON ENCODED, into 'style_formats'
	$init_array['style_formats'] = wp_json_encode( $style_formats );  
	
	return $init_array;  
  
} 
// Attach callback to 'tiny_mce_before_init' 
add_filter( 'tiny_mce_before_init', 'csomaster_mce_before_init_insert_formats' );  

/**
 *  Add some goodstuff to the Tiny MCE
 */ 
function csomaster_change_mce_block_formats( $init ) {
	$block_formats = array(
		'Paragraph=p',
		'Paragraph Large=p-p2',
		/*'Heading 1=h1',*/
		'Heading 2=h2',
		'Heading 3=h3',
		'Heading 4=h4',
		'Heading 5=h5',
		'Heading 6=h6',
		'Preformatted=pre',
		'Code=code',
	);
	$init['block_formats'] = implode( ';', $block_formats );
	
	//var_dump($init['block_formats']);
 
	return $init;
}

add_filter( 'tiny_mce_before_init', 'csomaster_change_mce_block_formats' );

function csomaster_theme_after_wp_tiny_mce() {
?>
    <script type="text/javascript">
		console.log('tinymce init')
        jQuery( document ).on( 'tinymce-editor-init', function( event, editor ) {
            tinymce.activeEditor.formatter.register( 'p-p2', {
                block : 'p',
                classes : 'p2',
				wrapper : true
            } );
        } );
    </script>
<?php
}
add_action( 'after_wp_tiny_mce', 'csomaster_theme_after_wp_tiny_mce' );
/* read this and fix it up here https://wordpress.org/support/topic/wysiwyg-custom-buttons-not-showing/ */


/* Add color picker to the ACF WYSIWYG editor  */
add_filter( 'acf/fields/wysiwyg/toolbars' , 'csomaster_add_acf_color'  );
function csomaster_add_acf_color( $toolbars ) {
    array_unshift( $toolbars['Basic' ][1], 'forecolor' );
    return $toolbars;
}

if ( ! function_exists ( 'csomaster_mce4_options' ) ) {
/* Default brand colors for MCE color picker */

	function csomaster_mce4_options($init) {

		// Loop through THEME_COLORS and add them to the MCE color picker
		$THEME_COLORS = $GLOBALS['THEME_COLORS'];

		$custom_colours = "";

		foreach($THEME_COLORS as $name => $hex) {
			$custom_colours .= "'$hex',' $name',";
		}
		
		/*
		$custom_colours = '
			"333f4c", "Off Black",
			"fff046", "Yellow",
			"f0f0e6", "Tan",
			"0144CB", "Blue",
			"6ce6ff", "Light Blue",
			"058c1d", "Green",
			"91e83a", "Light Green",
			"f21905", "Red",
			"ff7f00", "Orange",
			"fdb034", "Light Orange",
			"d297fd", "Purple",
			"ffa3ff", "Pink",
			"6ce7ff99", "Light Blue Tint",
			"d297fd99", "Purple Tint",
			"333f4c33", "Off Black Tint",
			"000000", "Black",
			"ffffff", "White"
		'; */

		// build colour grid default+custom colors
		$init['textcolor_map'] = '['.$custom_colours.']';

		// change the number of rows in the grid if the number of colors changes
		// 8 swatches per row
		$init['textcolor_rows'] = 1;

		return $init;
	}

	add_filter('tiny_mce_before_init', 'csomaster_mce4_options');

}