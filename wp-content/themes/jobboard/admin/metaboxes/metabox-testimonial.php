<?php

/**
 * Job Board "Testimonial" metabox template.
 * Define field for "Testimonial" post type metabox
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 */
 
 
/*-----------------------------------------------------*/
/*	Directly return the template value with array	*/
/*-----------------------------------------------------*/

return array(
	array(
		'type' => 'textarea',
		'name' => 'testimonial_content',
		'label' => __( 'Testimonial', 'jobboard' ),
		'validation' => 'required',
	),
	array(
		'type' => 'group',
		'repeating' => false,
		'name' => 'testimonial_social_media',
		'title' => __( 'Social Media URL', 'jobboard' ),
		'fields' => array(
			array(
				'type' => 'textbox',
				'name' => 'testimonial_twitter',
				'label' => __( 'Twitter URL', 'jobboard' ),
				'validation' => 'url',
			),
			array(
				'type' => 'textbox',
				'name' => 'testimonial_facebook',
				'label' => __( 'Facebook URL', 'jobboard' ),
				'validation' => 'url',
			),
			array(
				'type' => 'textbox',
				'name' => 'testimonial_linkedin',
				'label' => __( 'LinkedIn URL', 'jobboard' ),
				'validation' => 'url',
			),
			array(
				'type' => 'textbox',
				'name' => 'testimonial_google_plus',
				'label' => __( 'Google Plus URL', 'jobboard' ),
				'validation' => 'url',
			),
		),
	),
);