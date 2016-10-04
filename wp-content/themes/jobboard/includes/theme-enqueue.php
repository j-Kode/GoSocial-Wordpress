<?php
/**
 * Job Board script enqueue function.
 * Register and enqueue theme CSS and JS files.
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 */
 
if( !function_exists( 'jobboard_enqueue_scripts' ) ):

add_action( 'wp_enqueue_scripts', 'jobboard_enqueue_scripts' );
function jobboard_enqueue_scripts(){

	// Default theme fonts
	wp_enqueue_style( 'nunito-font', 'http://fonts.googleapis.com/css?family=Nunito:400,300,700');
	
	// Animate CSS
	wp_enqueue_style( 'animate', get_template_directory_uri().'/assets/css/animate.css', array(), '3.2.0', 'screen' );
	
	// Add Boostrap framework scripts
	wp_enqueue_style( 'bootstrap', get_template_directory_uri().'/assets/css/bootstrap.min.css', array(), '3.2.0', 'all' );
	wp_enqueue_script( 'bootstrap-js', get_template_directory_uri().'/assets/js/bootstrap.min.js', array('jquery'), '3.2.0', true );
	
	// Add Font Awesome script
	wp_enqueue_style( 'font-awesome', get_template_directory_uri().'/assets/css/font-awesome.min.css', array(), '4.1.0', 'all' );
	
	
	
	// Test Load Jquery UI slider
	wp_enqueue_script( 'jquery-select-to', get_template_directory_uri().'/assets/js/jquery.select-to-ui-slider.js', array( 'jquery', 'jquery-ui-slider' ), '', true );
	wp_enqueue_style('select-to', get_template_directory_uri().'/assets/css/jquery-ui-1.7.1.custom.css');
	wp_enqueue_style('select-to-extras', get_template_directory_uri().'/assets/css/ui.slider.extras.css');
	
	// Load owl carousel
	wp_enqueue_script( 'owl-carousel-js', get_template_directory_uri().'/assets/js/owl.carousel.min.js', array('jquery'), '2.0', true );
	wp_enqueue_style( 'owl-carousel', get_template_directory_uri().'/assets/css/owl.carousel.css', array(), '2.0', 'screen' );
	wp_enqueue_style( 'owl-carousel-theme', get_template_directory_uri().'/assets/css/owl.theme.default.min.css', array(), '2.0', 'screen' );
	
	// Javascript Image Liquid
	wp_enqueue_script( 'image-liquid-js', get_template_directory_uri().'/assets/js/image-liquid.min.js', array(), '0.9.944', true );
	
	// Load theme custom shortcodes stylesheet
	wp_enqueue_style( 'shortcode', get_template_directory_uri().'/assets/css/shortcodes.css', array(), '1.0', 'all' );
	
	// Load default theme stylesheet
	wp_enqueue_style( 'default', get_stylesheet_uri(), array(), '1.0', 'all' );
	wp_enqueue_style( 'default-responsive', get_stylesheet_directory_uri().'/style-responsive.css', '1.0', 'all' );
	// Load default theme javascript
	wp_enqueue_script( 'theme-js', get_template_directory_uri().'/assets/js/theme-script.js', array( 'jquery', 'jquery-ui-tabs', 'jquery-effects-core', 'jquery-effects-fade' ), '1.0', true  );
	
	// Check the homepage active or not, then execute the javascript
	if( is_page_template( 'page-templates/template-homepage.php' ) || is_page_template( 'page-templates/template-job_listing.php' ) || is_page_template( 'page-templates/template-job_search.php' ) ){
		$slider_init = true;
		
		// Homepage Image Slider
		$slider_settings = array(
			'auto_play'			=> jobboard_option('slider_auto_slide'),
			'auto_play_timeout'	=> jobboard_option('slider_delay'),
			'animate_in'		=> jobboard_option('slider_entrance_animation'),
			'animate_out'		=> jobboard_option('slider_exit_animation'),
		);
		
		wp_localize_script( 'theme-js', 'home_slider', $slider_settings );
		
	}else{
		$slider_init = false;
	}
	
	// Check if comments are open then enqueue 'comment-reply.js'
	if ( is_singular() && comments_open() && get_option('thread_comments') ){
		wp_enqueue_script( 'comment-reply' );
	}
	
	wp_localize_script( 'theme-js', 'slider', array( 'init' => $slider_init, 'home_init' => $slider_init ) );
	
	
	wp_enqueue_script( 'jquery-form' );
	
}

endif; // jobboard_enqueue_scripts
 
