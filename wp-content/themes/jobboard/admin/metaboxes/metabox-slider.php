<?php

/**
 * Job Board "Slider" metabox template.
 * Define field for "Slider" post type metabox
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
		'type'		=> 'image_advanced',
		'id'		=> 'jobboard_slider_images',
		'name'		=> __( 'Slider Images', 'jobboard' ),
		'desc'		=> __( 'Upload your slider images here.', 'jobboard' ),
		
	),
),
/*
return array(
	array(
        'type'		=> 'group',
        'repeating'	=> true,
        'name'		=> 'slider_image_group',
        'title'		=> __( 'Slider Item', 'jobboard' ),
        'sortable'	=> true,
        'fields'	=> array(
        	array(
        		'type'			=> 'upload',
        		'name'			=> 'slider_image',
        		'label'			=> __( 'Slider Image', 'jobboard' ),
        		'description'	=> __( 'Upload your image here.', 'jobboard' ),
        	),
        ),
    ),
    
);
*/