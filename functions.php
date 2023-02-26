<?php
/**
 * csomaster functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package csomaster
 */

$THEME_VERSION = wp_get_theme()->get('Version');

$GLOBALS['THEME_COLORS'] = array(
	'white' => 'f21905',
	'black' => '000000',
	'primary-dark' => '313131',
	'primary-light' => 'e6e6e6',
	'secondary-dark' => '917a4a',
	'secondary-light' => 'f1eadd',
	'warning' => 'c92d2d',
	'success' => '2dc98d'
);
$GLOBALS['DEVELOPMENT_MODE'] = empty( get_option( 'csomaster_development_mode' ) ) ? false : true;

if(!$GLOBALS['DEVELOPMENT_MODE']) {
	add_filter('acf/settings/show_admin', '__return_false');
}


if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', $THEME_VERSION );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function csomaster_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on csomaster, use a find and replace
		* to change 'csomaster' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'csomaster', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'csomaster' ),
			'footer-menu' => esc_html__( 'Footer', 'csomaster' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'csomaster_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'csomaster_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function csomaster_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'csomaster_content_width', 640 );
}
add_action( 'after_setup_theme', 'csomaster_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function csomaster_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'csomaster' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'csomaster' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'csomaster_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function csomaster_scripts() {
	wp_enqueue_style( 'csomaster-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_enqueue_style( 'flickity', get_template_directory_uri().'/css/vendor/flickity.css', array(), _S_VERSION );

	wp_style_add_data( 'csomaster-style', 'rtl', 'replace' );

	wp_enqueue_script( 'csomaster-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'csomaster-blocks', get_template_directory_uri() . '/js/blocks.js', array(), _S_VERSION, true );

	wp_enqueue_script( 'flickity', get_template_directory_uri() . '/js/vendor/flickity.pkgd.min.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'csomaster_scripts' );


//Remove JQuery migrate
 
function remove_jquery_migrate( $scripts ) {
	if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
		 $script = $scripts->registered['jquery'];
	if ( $script->deps ) { 
 // Check whether the script has any dependencies
 
		 $script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
  }
  }
  }
 add_action( 'wp_default_scripts', 'remove_jquery_migrate' );


/**
 * Add goodness to deal with defering scripts
 */
add_filter( 'script_loader_tag', 'csomaster_defer_scripts', 10, 3 );
function csomaster_defer_scripts( $tag, $handle, $src ) {

	// The handles of the enqueued scripts we want to defer
	$defer_scripts = array( 
		'blocks'
	);

    if ( in_array( $handle, $defer_scripts ) ) {
        return '<script src="' . $src . '" defer="defer" type="text/javascript"></script>' . "\n";
    }
    
    return $tag;
} 
/**
 * Get post types
 */
require get_template_directory() . '/inc/post-types.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Hooks which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-hooks.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';


/**
 * Theme editor functions.
 */
require get_template_directory() . '/inc/template-editor.php';

/**
 * Theme utility functions.
 */
require get_template_directory() . '/inc/template-utilities.php';


/**
 * Get theme updater.
 */
require get_template_directory() . '/inc/template-updater.php';


add_action('admin_init', 'csomaster_setup_theme_settings' );

function csomaster_setup_theme_settings() {
	register_setting( 'general', 'csomaster_development_mode' );
	add_settings_field('csomaster_development_mode', 'Enable Development Mode', 'csomaster_development_mode_settings_cb', 'general', 'default' );
	
	function csomaster_development_mode_settings_cb($args)
	{ 
		$name = 'Enable Development Mode';
		$id = 'csomaster_development_mode';
		$checked = '';
		$options = get_option('csomaster_development_mode');
		//var_dump($options);
		$value   = ( !isset( $options[0] ) ) 
					? null : $options[0];
		
		if( $value || $value === 'on' ) { $checked = ' checked="checked" '; }

		// Could use ob_start.
		$html  = '';
		$html .= '<input id="' . esc_attr( $id ) . '" 
		name="' . esc_attr( 'csomaster_development_mode' ) .'" 
		type="checkbox" ' . $checked . '/>';
		$html .= '<span class="wndspan">' . esc_html( 'Display visual helper' ) .'</span>';
		$html .= '<b class="wntip" data-title="'. esc_attr( 'Display visual helpers' ) .'"></b>';

		echo $html;
	}
	
}
