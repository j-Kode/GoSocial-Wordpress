<?php

/**
 * Job Board admin function.
 * Register Theme Options and Metabox
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 */

/*--------------------------------------------------------*/
/*	Retrieve Options Value	*/
/*--------------------------------------------------------*/
function jobboard_option( $name ){
	if( function_exists( 'vp_option' ) ){
		return vp_option( "jobboard_option." . $name );
	}//endif;
}

/*--------------------------------------------------------*/
/*	Include Data Source	*/
/*--------------------------------------------------------*/
require_once( get_template_directory().'/admin/data-sources.php' );

/*--------------------------------------------------------*/
/*	Register Theme Options Panel	*/
/*--------------------------------------------------------*/
if( !function_exists( 'jobboard_init_options' ) ){

function jobboard_init_options(){
    // Built path to options template array file
    $tmpl_opt  = get_template_directory() . '/admin/theme-options/theme-options.php';
    // Initialize the Option's object
    $theme_options = new VP_Option(array(
        'is_dev_mode'           => false,
        'option_key'            => 'jobboard_option',
        'page_slug'             => 'jobboard_option',
        'template'              => $tmpl_opt,
        'menu_page'				=> array(
        								'icon_url' => get_template_directory_uri() . '/assets/images/theme-options-icon.png',
        								'position' => 57,
        							),
        'use_auto_group_naming' => true,
        'use_exim_menu'         => true,
        'minimum_role'          => 'edit_theme_options',
        'layout'                => 'fixed',
        'page_title'            => __( 'Theme Options', 'jobboard' ),
        'menu_label'            => __( 'Theme Options', 'jobboard' ),
    ));
}
// the safest hook to use, since Vafpress Framework may exists in Theme or Plugin
add_action( 'after_setup_theme', 'jobboard_init_options' );

}

/*--------------------------------------------------------*/
/*	Register "Job" Metabox	*/
/*--------------------------------------------------------*/
if( !function_exists( 'jobboard_job_metaboxes' ) ){

function jobboard_job_metaboxes(){
    // Built path to metabox template array file
    $mb_path  = get_template_directory() . '/admin/metaboxes/metabox-job.php';
    // Initialize the Metabox's object
    // We can use array or file path to the array specification file.
    $mb = new VP_Metabox(array(
        'id'          => 'jobboard_job_mb',
        'types'       => array('job'),
        'title'       => __('Job Details', 'jobboard'),
        'priority'    => 'high',
        'is_dev_mode' => false,
        'template'    => $mb_path,
        'prefix'	  => '_jboard_',
        'mode' => WPALCHEMY_MODE_EXTRACT
    ));
}
// the safest hook to use, since Vafpress Framework may exists in Theme or Plugin
add_action( 'after_setup_theme', 'jobboard_job_metaboxes' );

}

/*--------------------------------------------------------*/
/*	Register "Application" Metabox	*/
/*--------------------------------------------------------*/
if( !function_exists( 'jobboard_application_metaboxes' ) ){
	
function jobboard_application_metaboxes(){
    // Built path to metabox template array file
    $mb_path  = get_template_directory() . '/admin/metaboxes/metabox-application.php';
    // Initialize the Metabox's object
    // We can use array or file path to the array specification file.
    $mb = new VP_Metabox(array(
        'id'          => 'jobboard_application_mb',
        'types'       => array('application'),
        'title'       => __('Applications', 'jobboard'),
        'priority'    => 'high',
        'is_dev_mode' => false,
        'template'    => $mb_path,
        'prefix'	  => '_jboard_',
        'mode' => WPALCHEMY_MODE_EXTRACT
    ));
}
// the safest hook to use, since Vafpress Framework may exists in Theme or Plugin
add_action( 'after_setup_theme', 'jobboard_application_metaboxes' );

}

/*--------------------------------------------------------*/
/*	Register Resume Metabox	*/
/*--------------------------------------------------------*/

if( !function_exists( 'jobboard_resume_metabox' ) ){

	function jobboard_resume_metabox(){
    // Built path to metabox template array file
    $mb_path  = get_template_directory() . '/admin/metaboxes/metabox-resume.php';
    // Initialize the Metabox's object
    // We can use array or file path to the array specification file.
    $mb = new VP_Metabox(array(
        'id'          => 'jobboard_resume_mb',
        'types'       => array('resume'),
        'title'       => __('Resume Details', 'jobboard'),
        'priority'    => 'high',
        'is_dev_mode' => false,
        'template'    => $mb_path,
        'prefix'	  => '_jboard_',
        
    ));
}
// the safest hook to use, since Vafpress Framework may exists in Theme or Plugin
add_action( 'after_setup_theme', 'jobboard_resume_metabox' );

}//endif;

/*--------------------------------------------------------*/
/*	Register "Company" Metabox	*/
/*--------------------------------------------------------*/
if( !function_exists( 'jobboard_company_metaboxes' ) ){

function jobboard_company_metaboxes(){
    // Built path to metabox template array file
    $mb_path  = get_template_directory() . '/admin/metaboxes/metabox-company.php';
    // Initialize the Metabox's object
    // We can use array or file path to the array specification file.
    $mb = new VP_Metabox(array(
        'id'          => 'jobboard_company_mb',
        'types'       => array('company'),
        'title'       => __('Company Details', 'jobboard'),
        'priority'    => 'high',
        'is_dev_mode' => false,
        'template'    => $mb_path,
        'prefix'	  => '_jboard_',
        'mode'		  => WPALCHEMY_MODE_EXTRACT
    ));
}
// the safest hook to use, since Vafpress Framework may exists in Theme or Plugin
add_action( 'after_setup_theme', 'jobboard_company_metaboxes' );

}

/*--------------------------------------------------------*/
/*	Register "Testimonial" Metabox	*/
/*--------------------------------------------------------*/
if( !function_exists( 'jobboard_testimonial_metaboxes' ) ){

function jobboard_testimonial_metaboxes(){
	// Built path to metabox template array file
    $mb_path  = get_template_directory() . '/admin/metaboxes/metabox-testimonial.php';
    // Initialize the Metabox's object
    // We can use array or file path to the array specification file.
    $mb = new VP_Metabox(array(
        'id'          => 'jobboard_testimonial_mb',
        'types'       => array('testimonial'),
        'title'       => __('Testimonial Details', 'jobboard'),
        'priority'    => 'high',
        'is_dev_mode' => false,
        'template'    => $mb_path,
        'prefix'	  => '_jboard_',
        'mode' => WPALCHEMY_MODE_EXTRACT
    ));
}
// the safest hook to use, since Vafpress Framework may exists in Theme or Plugin
add_action( 'after_setup_theme', 'jobboard_testimonial_metaboxes' );

}//endif;

/*--------------------------------------------------------*/
/*	Register "Slider" Metabox	*/
/*--------------------------------------------------------*/
if( !function_exists( 'jobboard_slider_metaboxes' ) ){

	add_filter( 'rwmb_meta_boxes', 'jobboard_slider_metaboxes' );
	function jobboard_slider_metaboxes($meta_boxes){
		
		$meta_boxes[] = array(
			'id'		=> 'slider_metabox',
			'title'		=> __( 'Slider Options', 'jobboard' ),
			'pages'		=> array('jb_slider'),
			'context'	=> 'normal',
			'priority'	=> 'high',
			//'fields'	=> get_template_directory().'/admin/metaboxes/metabox-slider.php',
			'fields'	=> array(
				array(
					'type'		=> 'image_advanced',
					'id'		=> 'jobboard_slider_images',
					'name'		=> __( 'Slider Images', 'jobboard' ),
					'desc'		=> __( 'Upload your slider images here. You can select multiple images at once by hold on "Shift" key when selecting images.', 'jobboard' ),
		
				),
			),
		);
		
		return $meta_boxes;
	}
	
}//endif; 


/*--------------------------------------------------------*/
/*	Register Custom Shortcode	*/
/*--------------------------------------------------------*/
if( !function_exists( 'jobboard_init_shortcode_generator' ) ){

function jobboard_init_shortcode_generator(){
    // Built path to shortcode generator template array file
    $sg_path = get_template_directory() . '/admin/shortcodes/shortcode-generator.php';
    // Initialize the ShortcodeGenerator's object
    $tmpl_sg = array(
        'name'           => 'jobboard_shortcode_generator',
        'template'       => $sg_path,
        'modal_title'    => __( 'Job Board Shortcodes', 'jobboard'),
        'button_title'   => __( 'Job Board Shortcode', 'jobboard'),
        'types'          => array( 'post', 'page', 'job', 'resume' ),
        'main_image'     => get_template_directory_uri() . '/assets/images/shortcode-main.png',
        'sprite_image'   => get_template_directory_uri() . '/assets/images/shortcode-sprite.png',
        'included_pages' => array( '229' ),
    );
    $sg = new VP_ShortcodeGenerator($tmpl_sg);
}
// the safest hook to use, since Vafpress Framework may exists in Theme or Plugin
add_action( 'after_setup_theme', 'jobboard_init_shortcode_generator' );

}//endif;