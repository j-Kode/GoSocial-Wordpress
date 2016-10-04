<?php

/**
 * Job Board "Company" metabox template.
 * Define field for "Company" post type metabox
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
		'name' => 'company_description',
		'label' => __( 'Company Description', 'jobboard' ),
		'description' => __( 'Write something about your company.', 'jobboard' ),
	),
	array(	
		'type' => 'textbox',
		'name' => 'company_web_address',
		'label' => __( 'Website', 'jobboard' ),
		'validation' => 'url',
	),
	array(
		'type' => 'textbox',
		'name' => 'company_social_facebook',
		'label' => __( 'Facebook', 'jobboard' ),
		'validation' => 'url',
	),
	array(
		'type' => 'textbox',
		'name' => 'company_social_twitter',
		'label' => __( 'Twitter', 'jobboard' ),
		'validation' => 'url',
	),
	array(
		'type' => 'textbox',
		'name' => 'company_social_googleplus',
		'label' => __( 'Google Plus', 'jobboard' ),
		'validation' => 'url',
	),
	
);