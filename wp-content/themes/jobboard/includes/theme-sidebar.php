<?php

/**
 * Job Board theme sidebar function.
 * Register Sidebar/Widget Area
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 */
?><?php

/*-----------------------------------------------------------*/
/*	Register default sidebar area	*/
/*-----------------------------------------------------------*/
if( ! function_exists( 'jobboard_register_default_sidebar' ) ){
	//Register Sidebar
	function jobboard_register_default_sidebar(){
		$args = array(
			'id'            => 'default_sidebar',
			'name'          => __( 'Default Sidebar', 'jobboard' ),
			'description'   => __( 'Default sidebar that appears on the right', 'jobboard' ),
			'class'         => 'sidebar-default',
			'before_title'  => '<h3 class="default-widget-title">',
			'after_title'   => '</h3>',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
		);
		register_sidebar( $args );
	}
	add_action( 'widgets_init', 'jobboard_register_default_sidebar' );
} //endif;

/*-----------------------------------------------------------*/
/*	Register homepage sidebar area	*/
/*-----------------------------------------------------------*/
if( ! function_exists( 'jobboard_register_home_sidebar' ) ){
	//Register Sidebar
	function jobboard_register_home_sidebar(){
		$args = array(
			'id'            => 'home_sidebar',
			'name'          => __( 'Home Sidebar', 'jobboard' ),
			'description'   => __( 'Home sidebar that appears on the right', 'jobboard' ),
			'class'         => 'sidebar-home',
			'before_title'  => '<h3 class="home-widget-title">',
			'after_title'   => '</h3>',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
		);
		register_sidebar( $args );
	}
	add_action( 'widgets_init', 'jobboard_register_home_sidebar' );
} //endif;

/*-----------------------------------------------------------*/
/*	Register footer sidebar area, depend on options panel	*/
/*-----------------------------------------------------------*/
if ( ! function_exists( 'jobboard_register_footer_widget_1' ) ) {
	// Register Sidebar
	function jobboard_register_footer_widget_1() {

		$args = array(
			'id'            => 'footer_sidebar_1',
			'name'          => __( 'Footer 1', 'jobboard' ),
			'description'   => __( 'Widgetized Footer', 'jobboard' ),
			'class'         => 'footer-widget',
			'before_title'  => '<h3 class="footer-widget-title">',
			'after_title'   => '</h3>',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
		);
		register_sidebar( $args );

	}
	add_action( 'widgets_init', 'jobboard_register_footer_widget_1' );
} //endif;

if ( ! function_exists( 'jobboard_register_footer_widget_2' ) ) {
	// Register Sidebar
	function jobboard_register_footer_widget_2() {

		$args = array(
			'id'            => 'footer_sidebar_2',
			'name'          => __( 'Footer 2', 'jobboard' ),
			'description'   => __( 'Widgetized Footer', 'jobboard' ),
			'class'         => 'footer-widget',
			'before_title'  => '<h3 class="footer-widget-title">',
			'after_title'   => '</h3>',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
		);
		register_sidebar( $args );

	}
	add_action( 'widgets_init', 'jobboard_register_footer_widget_2' );
} //endif;

if ( ! function_exists( 'jobboard_register_footer_widget_3' ) ) {
	// Register Sidebar
	function jobboard_register_footer_widget_3() {

		$args = array(
			'id'            => 'footer_sidebar_3',
			'name'          => __( 'Footer 3', 'jobboard' ),
			'description'   => __( 'Widgetized Footer', 'jobboard' ),
			'class'         => 'footer-widget',
			'before_title'  => '<h3 class="footer-widget-title">',
			'after_title'   => '</h3>',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
		);
		register_sidebar( $args );

	}
	add_action( 'widgets_init', 'jobboard_register_footer_widget_3' );
} //endif;

if ( ! function_exists( 'jobboard_register_footer_widget_4' ) ) {
	// Register Sidebar
	function jobboard_register_footer_widget_4() {

		$args = array(
			'id'            => 'footer_sidebar_4',
			'name'          => __( 'Footer 4', 'jobboard' ),
			'description'   => __( 'Widgetized Footer', 'jobboard' ),
			'class'         => 'footer-widget',
			'before_title'  => '<h3 class="footer-widget-title">',
			'after_title'   => '</h3>',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
		);
		register_sidebar( $args );

	} //endif;

	add_action( 'widgets_init', 'jobboard_register_footer_widget_4' );
	
}