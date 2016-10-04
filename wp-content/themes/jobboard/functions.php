<?php
/**
 * Job Board functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link http://codex.wordpress.org/Theme_Development
 * @link http://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * @link http://codex.wordpress.org/Plugin_API
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 */
 
add_action('init', 'do_output_buffer');
function do_output_buffer() {
        ob_start();
}
 
 if ( ! function_exists( 'jobboard_setup' ) ) :
/**
 * Job Board setup.
 *
 * Set up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support post thumbnails.
 *
 * @since Job Board 1.0
 */

add_action( 'after_setup_theme', 'jobboard_setup' );
function jobboard_setup() {

	/*
	 * Make Job Board available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Twenty Fourteen, use a find and
	 * replace to change 'twentyfourteen' to the name of your theme in all
	 * template files.
	 */
	load_theme_textdomain( 'jobboard', get_template_directory() . '/languages' );

	// Add RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// Enable support for Post Thumbnails, and declare two sizes.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 150, 150, true ); // Set default post thumbnail size;
	add_image_size( 'jobboard-company-logo-thumbnail', 80, 45, false ); // Company logo on job listing homepage
	add_image_size( 'jobboard-related-company-logo-thumbnail', 120, 60, false ); // Company logo on job related on single job detail page
	add_image_size( 'jobboard-testimonial-thumbnail', 228, 228, true ); // Testimonial Thumbnail
	add_image_size( 'jobboard-companies-listing', 185, 100, false ); // Company logo on company listing homepage
	add_image_size( 'jobboard-job-detail-company', 160, 60, false ); // Company logo on job detail page
	add_image_size( 'jobboard-blog-list-thumbnail', 570, 390, true ); // Blog list thumbnail 
	add_image_size( 'jobboard-resume-photo', 170, 150, true ); // Resume Photo thumbnail
	add_image_size( 'jobboard-featured-job-thumbnail', 368, 180, true ); // Featured Job Thumbnail
	add_image_size( 'jobboard-resume-thumbnail', 100, 100, true ); // Featured Resume Thumbnail

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary_menu'   => __( 'Primary Menu', 'jobboard' ),
		
	) );
	
	// Set Content Width
	if ( ! isset( $content_width ) ) $content_width = 800;

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );


	// This theme allows users to set a custom background.
	add_theme_support( 'custom-background', apply_filters( 'jobboard_custom_background_args', array(
		'default-color' => 'ffffff',
	) ) );
	
	// Customize the wp_title() function
	if( !function_exists( 'jobboard_custom_title' ) ){
		add_filter( 'wp_title', 'jobboard_custom_title' );
		function jobboard_custom_title( $title )
		{
		  if( empty( $title ) && ( is_home() || is_front_page() ) ) {
			return get_bloginfo('name') . ' | ' . get_bloginfo( 'description' );
		  }
		  return $title;
		}
	}//endif;

}
endif; // jobboard_setup

/*--------------------------------------------------------*/
/*	Include admin metabox and theme options, required plugin activation before include the file	*/
/*--------------------------------------------------------*/
add_action('after_setup_theme', 'vp_tb_load_textdomain');

function vp_tb_load_textdomain() {
	load_theme_textdomain('vp_textdomain', get_template_directory() . '/admin/framework/lang/');
}

/**
 * Include Vafpress Framework
 */
require_once ( get_template_directory().'/admin/framework/bootstrap.php' );

/**
 * Include Custom Data Sources
 */
require_once ( get_template_directory().'/admin/data-sources.php' );
require_once( get_template_directory().'/admin/theme-admin.php' ); // Include Theme Administration functions

/*-----------------------------------------------------------------------------------*/
/*	Include Meta Box for Slider
/*-----------------------------------------------------------------------------------*/
    define( 'RWMB_URL', trailingslashit( get_template_directory_uri() . '/admin/metaboxes/meta-box' ) );
    define( 'RWMB_DIR', trailingslashit( get_template_directory() . '/admin/metaboxes/meta-box' ) );
    require_once RWMB_DIR . 'meta-box.php';

/*--------------------------------------------------------*/
/*	Include all required files	*/
/*--------------------------------------------------------*/
require_once( get_template_directory().'/includes/theme-enqueue.php' ); // Enqueue all required CSS and Javascript Files
require_once( get_template_directory().'/includes/theme-cpt.php' ); // Custom post type and taxonomy registration
require_once( get_template_directory().'/includes/theme-sidebar.php' ); // Register Theme Widget/Sidebar area
require_once( get_template_directory().'/includes/widgets/widget-init.php' ); // Include Custom Widgets
require_once( get_template_directory().'/includes/theme-shortcodes.php' ); // Include Custom Theme Shortcode
require_once( get_template_directory().'/includes/theme-functions.php' ); // Include Theme custom functions

/*--------------------------------------------------------*/
/*	Include TGM Plugin Activation	*/
/*--------------------------------------------------------*/
require_once( get_template_directory().'/includes/theme-plugin-activation.php' );

// Disable admin bar
if( !current_user_can('edit_theme_options') ){
	add_filter('show_admin_bar', '__return_false');
}