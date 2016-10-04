<?php

/**
 * Job Board custom post type and taxonomy function.
 * Register and enqueue theme CSS and JS files.
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 */


/*------------------------------------------------------------*/
/*	Register "Job" Post Type	*/
/*------------------------------------------------------------*/
if ( ! function_exists('jobboard_register_job_post_type') ) {

// Register Job Post Type
function jobboard_register_job_post_type() {

	$labels = array(
		'name'                => _x( 'Jobs', 'Post Type General Name', 'jobboard' ),
		'singular_name'       => _x( 'Job', 'Post Type Singular Name', 'jobboard' ),
		'menu_name'           => __( 'Jobs', 'jobboard' ),
		'parent_item_colon'   => __( 'Parent Job:', 'jobboard' ),
		'all_items'           => __( 'All Jobs', 'jobboard' ),
		'view_item'           => __( 'View Job', 'jobboard' ),
		'add_new_item'        => __( 'Add New Job', 'jobboard' ),
		'add_new'             => __( 'Add New', 'jobboard' ),
		'edit_item'           => __( 'Edit Job', 'jobboard' ),
		'update_item'         => __( 'Update Job', 'jobboard' ),
		'search_items'        => __( 'Search Job', 'jobboard' ),
		'not_found'           => __( 'Not found', 'jobboard' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'jobboard' ),
	);
	$args = array(
		'label'               => __( 'job', 'jobboard' ),
		'description'         => __( 'Post Type Description', 'jobboard' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'author' ),
		'taxonomies'          => array( 'job_category', 'job_type' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => true,
		'menu_position'       => 57.1,
		'menu_icon'           => 'dashicons-portfolio',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
	);
	register_post_type( 'job', $args );

}

// Hook into the 'init' action
add_action( 'init', 'jobboard_register_job_post_type', 0 );

}


/*------------------------------------------------------------*/
/*	Register "Job Category" Taxonomy	*/
/*------------------------------------------------------------*/
if ( ! function_exists( 'jobboard_register_job_category' ) ) {

// Register Job Category Taxonomy
function jobboard_register_job_category() {

	$labels = array(
		'name'                       => _x( 'Job Categories', 'Taxonomy General Name', 'jobboard' ),
		'singular_name'              => _x( 'Job Category', 'Taxonomy Singular Name', 'jobboard' ),
		'menu_name'                  => __( 'Job Categories', 'jobboard' ),
		'all_items'                  => __( 'All Job Categories', 'jobboard' ),
		'parent_item'                => __( 'Parent Job Category', 'jobboard' ),
		'parent_item_colon'          => __( 'Parent Job Category:', 'jobboard' ),
		'new_item_name'              => __( 'New Job Category Name', 'jobboard' ),
		'add_new_item'               => __( 'Add New Job Category', 'jobboard' ),
		'edit_item'                  => __( 'Edit Job Category', 'jobboard' ),
		'update_item'                => __( 'Update Job Category', 'jobboard' ),
		'separate_items_with_commas' => __( 'Separate job categories with commas', 'jobboard' ),
		'search_items'               => __( 'Search Job Categories', 'jobboard' ),
		'add_or_remove_items'        => __( 'Add or remove job categories', 'jobboard' ),
		'choose_from_most_used'      => __( 'Choose from the most used job categories', 'jobboard' ),
		'not_found'                  => __( 'Not Found', 'jobboard' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'job_category', array( 'job' ), $args );

}

// Hook into the 'init' action
add_action( 'init', 'jobboard_register_job_category', 0 );

}


/*------------------------------------------------------------*/
/*	Register "Job Type" Taxonomy	*/
/*------------------------------------------------------------*/
if ( ! function_exists( 'jobboard_register_job_type' ) ) {

// Register Job Type Taxonomy
function jobboard_register_job_type() {

	$labels = array(
		'name'                       => _x( 'Job Types', 'Taxonomy General Name', 'jobboard' ),
		'singular_name'              => _x( 'Job Type', 'Taxonomy Singular Name', 'jobboard' ),
		'menu_name'                  => __( 'Job Types', 'jobboard' ),
		'all_items'                  => __( 'All Job Types', 'jobboard' ),
		'parent_item'                => __( 'Parent Job Type', 'jobboard' ),
		'parent_item_colon'          => __( 'Parent Job Type:', 'jobboard' ),
		'new_item_name'              => __( 'New Job Type Name', 'jobboard' ),
		'add_new_item'               => __( 'Add New Job Type', 'jobboard' ),
		'edit_item'                  => __( 'Edit Job Type', 'jobboard' ),
		'update_item'                => __( 'Update Job Type', 'jobboard' ),
		'separate_items_with_commas' => __( 'Separate job types with commas', 'jobboard' ),
		'search_items'               => __( 'Search Job Types', 'jobboard' ),
		'add_or_remove_items'        => __( 'Add or remove job types', 'jobboard' ),
		'choose_from_most_used'      => __( 'Choose from the most used job types', 'jobboard' ),
		'not_found'                  => __( 'Not Found', 'jobboard' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'job_type', array( 'job' ), $args );

}

// Hook into the 'init' action
add_action( 'init', 'jobboard_register_job_type', 0 );

}


/*------------------------------------------------------------*/
/*	Register "Job Region" Taxonomy	*/
/*------------------------------------------------------------*/
if ( ! function_exists( 'jobboard_register_job_region' ) ) {

// Register Job Region Taxonomy
function jobboard_register_job_region() {

	$labels = array(
		'name'                       => _x( 'Job Regions', 'Taxonomy General Name', 'jobboard' ),
		'singular_name'              => _x( 'Job Region', 'Taxonomy Singular Name', 'jobboard' ),
		'menu_name'                  => __( 'Job Regions', 'jobboard' ),
		'all_items'                  => __( 'All Job Regions', 'jobboard' ),
		'parent_item'                => __( 'Parent Job Region', 'jobboard' ),
		'parent_item_colon'          => __( 'Parent Job Region:', 'jobboard' ),
		'new_item_name'              => __( 'New Job Region Name', 'jobboard' ),
		'add_new_item'               => __( 'Add New Job Region', 'jobboard' ),
		'edit_item'                  => __( 'Edit Job Region', 'jobboard' ),
		'update_item'                => __( 'Update Job Region', 'jobboard' ),
		'separate_items_with_commas' => __( 'Separate job regions with commas', 'jobboard' ),
		'search_items'               => __( 'Search Job Regions', 'jobboard' ),
		'add_or_remove_items'        => __( 'Add or remove job regions', 'jobboard' ),
		'choose_from_most_used'      => __( 'Choose from the most used job regions', 'jobboard' ),
		'not_found'                  => __( 'Not Found', 'jobboard' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'job_region', array( 'job' ), $args );

}

// Hook into the 'init' action
add_action( 'init', 'jobboard_register_job_region', 0 );

}

/*------------------------------------------------------------*/
/*	Register "Application" Post Type	*/
/*------------------------------------------------------------*/
if ( ! function_exists('jobboard_register_application_post_type') ) {

// Register Custom Post Type
function jobboard_register_application_post_type() {

	$labels = array(
		'name'                => _x( 'Applications', 'Post Type General Name', 'jobboard' ),
		'singular_name'       => _x( 'Application', 'Post Type Singular Name', 'jobboard' ),
		'menu_name'           => __( 'Applications', 'jobboard' ),
		'parent_item_colon'   => __( 'Parent Application:', 'jobboard' ),
		'all_items'           => __( 'All Applications', 'jobboard' ),
		'view_item'           => __( 'View Application', 'jobboard' ),
		'add_new_item'        => __( 'Add New Application', 'jobboard' ),
		'add_new'             => __( 'Add New', 'jobboard' ),
		'edit_item'           => __( 'Edit Application', 'jobboard' ),
		'update_item'         => __( 'Update Application', 'jobboard' ),
		'search_items'        => __( 'Search Application', 'jobboard' ),
		'not_found'           => __( 'Not found', 'jobboard' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'jobboard' ),
	);
	$args = array(
		'label'               => __( 'application', 'jobboard' ),
		'description'         => __( 'Application post type', 'jobboard' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'author' ),
		'taxonomies'          => array( 'application_status' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => false,
		'menu_position'       => 57.2,
		'menu_icon'           => 'dashicons-clipboard',
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
	);
	register_post_type( 'application', $args );

}

// Hook into the 'init' action
add_action( 'init', 'jobboard_register_application_post_type', 0 );

}


/*------------------------------------------------------------*/
/*	Register "Appplication Status" Taxonomy	*/
/*------------------------------------------------------------*/
if ( ! function_exists( 'jobboard_register_application_status' ) ) {

// Register Custom Taxonomy
function jobboard_register_application_status() {

	$labels = array(
		'name'                       => _x( 'Application Statuses', 'Taxonomy General Name', 'jobboard' ),
		'singular_name'              => _x( 'Application Status', 'Taxonomy Singular Name', 'jobboard' ),
		'menu_name'                  => __( 'Application Status', 'jobboard' ),
		'all_items'                  => __( 'All Statuses', 'jobboard' ),
		'parent_item'                => __( 'Parent Status', 'jobboard' ),
		'parent_item_colon'          => __( 'Parent Status:', 'jobboard' ),
		'new_item_name'              => __( 'New Status Name', 'jobboard' ),
		'add_new_item'               => __( 'Add New Status', 'jobboard' ),
		'edit_item'                  => __( 'Edit Status', 'jobboard' ),
		'update_item'                => __( 'Update Status', 'jobboard' ),
		'separate_items_with_commas' => __( 'Separate statuses with commas', 'jobboard' ),
		'search_items'               => __( 'Search Statuses', 'jobboard' ),
		'add_or_remove_items'        => __( 'Add or remove statuses', 'jobboard' ),
		'choose_from_most_used'      => __( 'Choose from the most used statuses', 'jobboard' ),
		'not_found'                  => __( 'Not Found', 'jobboard' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => false,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false,
	);
	register_taxonomy( 'application_status', array( 'application' ), $args );

}


// Hook into the 'init' action
add_action( 'init', 'jobboard_register_application_status', 0 );

add_filter( 'manage_edit-application_columns', 'jobboard_set_custom_edit_application_columns' );
add_action( 'manage_application_posts_custom_column' , 'jobboard_custom_application_column', 10, 2 );

function jobboard_set_custom_edit_application_columns($columns){
	
	unset( $columns['author'] );
	unset( $columns['taxonomy-application_status'] );
	unset( $columns['date'] );
	
	$columns['applicant_name'] = __( 'Applicant Name', 'jobboard' );
	$columns['job'] = __( 'Applied Job', 'jobboard' );
	$columns['application_status'] = __( 'Status', 'jobboard' );
	$columns['action'] = __( 'Action', 'jobboard' );
	
	return $columns;
}

function jobboard_custom_application_column( $column, $post_id ){
    switch ( $column ){
    	
    	case 'applicant_name':
    		echo get_the_author_meta( 'display_name', get_post_meta( $post_id, '_jboard_applicant_name', true ) );
    	break;
    	
    	case 'action':
    		$resume_id = get_post_meta( $post_id, '_jboard_applicant_resume', true );
    		echo '<a target="_" href="'.esc_url( get_permalink( $resume_id ) ).'">'.__( 'View Resume', 'jobboard' ).'</a> | <a href="'.esc_url( get_edit_post_link( $post_id ) ).'">'.__( 'Edit', 'jobboard' ).'</a>';
    	break;
    	
    	case 'job':
    		$job_id = get_post_meta( $post_id, '_jboard_applied_job', true );
    		echo '<a target="_blank" href="'.esc_url( get_permalink($job_id) ).'">'.esc_attr( get_the_title($job_id) ).'</a> ';
    	break;
    	
    	case 'application_status':
    		$apps = get_post_meta( $post_id, '_jboard_application_status', true);
    		$terms = get_term_by( 'id', $apps, 'application_status' );
    		if($terms){
    			echo esc_attr( $terms->name );
    		}
    	break;

    }
}

function jobboard_remove_application_meta() {
	//remove_meta_box( 'tagsdiv-application_status', 'application', 'side' );
}
add_action( 'admin_menu' , 'jobboard_remove_application_meta' );

}//endif;


/*------------------------------------------------------------*/
/*	Register "Resume" Post Type	*/
/*------------------------------------------------------------*/
if ( ! function_exists('jobboard_register_resume_post_type') ) {

// Register Custom Post Type
function jobboard_register_resume_post_type() {

	$labels = array(
		'name'                => _x( 'Resumes', 'Post Type General Name', 'jobboard' ),
		'singular_name'       => _x( 'Resume', 'Post Type Singular Name', 'jobboard' ),
		'menu_name'           => __( 'Resumes', 'jobboard' ),
		'parent_item_colon'   => __( 'Parent Resume:', 'jobboard' ),
		'all_items'           => __( 'All Resumes', 'jobboard' ),
		'view_item'           => __( 'View Resume', 'jobboard' ),
		'add_new_item'        => __( 'Add New Resume', 'jobboard' ),
		'add_new'             => __( 'Add New', 'jobboard' ),
		'edit_item'           => __( 'Edit Resume', 'jobboard' ),
		'update_item'         => __( 'Update Resume', 'jobboard' ),
		'search_items'        => __( 'Search Resume', 'jobboard' ),
		'not_found'           => __( 'Not found', 'jobboard' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'jobboard' ),
	);
	$args = array(
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'author', 'thumbnail' ),
		'hierarchical'        => false,
		'taxonomies'		  => array( 'resume_category' ),
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => false,
		'menu_position'       => 57.3,
		'menu_icon'           => 'dashicons-id-alt',
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
	);
	register_post_type( 'resume', $args );

}

// Hook into the 'init' action
add_action( 'init', 'jobboard_register_resume_post_type', 0 );

}

/*------------------------------------------------------------*/
/*	Register "Resume Category" Taxonomy	*/
/*------------------------------------------------------------*/
if ( ! function_exists( 'jobboard_register_resume_category' ) ) {

// Register Custom Taxonomy
function jobboard_register_resume_category() {

	$labels = array(
		'name'                       => _x( 'Resume Categories', 'Taxonomy General Name', 'jobboard' ),
		'singular_name'              => _x( 'Resume Category', 'Taxonomy Singular Name', 'jobboard' ),
		'menu_name'                  => __( 'Resume Categories', 'jobboard' ),
		'all_items'                  => __( 'All Categories', 'jobboard' ),
		'parent_item'                => __( 'Parent Category', 'jobboard' ),
		'parent_item_colon'          => __( 'Parent Category:', 'jobboard' ),
		'new_item_name'              => __( 'New Category Name', 'jobboard' ),
		'add_new_item'               => __( 'Add New Category', 'jobboard' ),
		'edit_item'                  => __( 'Edit Category', 'jobboard' ),
		'update_item'                => __( 'Update Category', 'jobboard' ),
		'separate_items_with_commas' => __( 'Separate categories with commas', 'jobboard' ),
		'search_items'               => __( 'Search Categories', 'jobboard' ),
		'add_or_remove_items'        => __( 'Add or remove categories', 'jobboard' ),
		'choose_from_most_used'      => __( 'Choose from the most used categories', 'jobboard' ),
		'not_found'                  => __( 'Not Found', 'jobboard' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false,
	);
	register_taxonomy( 'resume_category', array( 'resume' ), $args );

}

// Hook into the 'init' action
add_action( 'init', 'jobboard_register_resume_category', 0 );

}


/*------------------------------------------------------------*/
/*	Register "Company" Post Type	*/
/*------------------------------------------------------------*/

if ( ! function_exists('jobboard_register_company_posttype') ) {

// Register Custom Post Type
function jobboard_register_company_posttype() {

	$labels = array(
		'name'                => _x( 'Companies', 'Post Type General Name', 'jobboard' ),
		'singular_name'       => _x( 'Company', 'Post Type Singular Name', 'jobboard' ),
		'menu_name'           => __( 'Companies', 'jobboard' ),
		'parent_item_colon'   => __( 'Parent Company:', 'jobboard' ),
		'all_items'           => __( 'All Companies', 'jobboard' ),
		'view_item'           => __( 'View Company', 'jobboard' ),
		'add_new_item'        => __( 'Add New Company', 'jobboard' ),
		'add_new'             => __( 'Add New', 'jobboard' ),
		'edit_item'           => __( 'Edit Company', 'jobboard' ),
		'update_item'         => __( 'Update Company', 'jobboard' ),
		'search_items'        => __( 'Search Company', 'jobboard' ),
		'not_found'           => __( 'Not found', 'jobboard' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'jobboard' ),
	);
	$args = array(
		'label'               => __( 'company', 'jobboard' ),
		'description'         => __( 'Post Type Description', 'jobboard' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'author', 'thumbnail', ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => false,
		'menu_position'       => 57.4,
		'menu_icon'           => 'dashicons-groups',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
	);
	register_post_type( 'company', $args );

}

// Hook into the 'init' action
add_action( 'init', 'jobboard_register_company_posttype', 0 );

}


/*------------------------------------------------------------*/
/*	Register "Testimonial" Post Type	*/
/*------------------------------------------------------------*/
if ( ! function_exists('jobboard_register_testimonial_post_type') ) {

// Register Custom Post Type
function jobboard_register_testimonial_post_type() {

	$labels = array(
		'name'                => _x( 'Testimonials', 'Post Type General Name', 'jobboard' ),
		'singular_name'       => _x( 'Testimonial', 'Post Type Singular Name', 'jobboard' ),
		'menu_name'           => __( 'Testimonials', 'jobboard' ),
		'parent_item_colon'   => __( 'Parent Testimonial:', 'jobboard' ),
		'all_items'           => __( 'All Testimonials', 'jobboard' ),
		'view_item'           => __( 'View Testimonial', 'jobboard' ),
		'add_new_item'        => __( 'Add New Testimonial', 'jobboard' ),
		'add_new'             => __( 'Add New', 'jobboard' ),
		'edit_item'           => __( 'Edit Testimonial', 'jobboard' ),
		'update_item'         => __( 'Update Testimonial', 'jobboard' ),
		'search_items'        => __( 'Search Testimonial', 'jobboard' ),
		'not_found'           => __( 'Not found', 'jobboard' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'jobboard' ),
	);
	$args = array(
		'label'               => __( 'testimonial', 'jobboard' ),
		'description'         => __( 'Post Type Description', 'jobboard' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'thumbnail', ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => false,
		'menu_position'       => 57.5,
		'menu_icon'           => 'dashicons-smiley',
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
	);
	register_post_type( 'testimonial', $args );

}

// Hook into the 'init' action
add_action( 'init', 'jobboard_register_testimonial_post_type', 0 );

}

if( !function_exists('jobboard_register_slider_post_type') ){

// Register Custom Post Type
function jobboard_register_slider_post_type(){

	$labels = array(
		'name'                => _x( 'Sliders', 'Post Type General Name', 'jobboard' ),
		'singular_name'       => _x( 'Slider', 'Post Type Singular Name', 'jobboard' ),
		'menu_name'           => __( 'Sliders', 'jobboard' ),
		'parent_item_colon'   => __( 'Parent Slider:', 'jobboard' ),
		'all_items'           => __( 'All Sliders', 'jobboard' ),
		'view_item'           => __( 'View Slider', 'jobboard' ),
		'add_new_item'        => __( 'Add New Slider', 'jobboard' ),
		'add_new'             => __( 'Add New', 'jobboard' ),
		'edit_item'           => __( 'Edit Slider', 'jobboard' ),
		'update_item'         => __( 'Update Slider', 'jobboard' ),
		'search_items'        => __( 'Search Slider', 'jobboard' ),
		'not_found'           => __( 'Not found', 'jobboard' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'jobboard' ),
	);
	$args = array(
		'label'               => __( 'jb_slider', 'jobboard' ),
		'description'         => __( 'Slider', 'jobboard' ),
		'labels'              => $labels,
		'supports'            => array( 'title', ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => false,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-slides',
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
	);
	register_post_type( 'jb_slider', $args );

}

// Hook into the 'init' action
add_action( 'init', 'jobboard_register_slider_post_type', 0 );

}//endif;