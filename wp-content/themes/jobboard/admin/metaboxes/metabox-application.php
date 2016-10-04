<?php

/**
 * Job Board "Job Applicants" metabox template.
 * Define field for "Job" post type metabox
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
        'type'	=> 'select',
        'name'	=> 'applicant_name',
        'label'	=> __( 'Applicant Name', 'jobboard' ),
        'items' => array(
        	'data' => array(
        		array(
        			'source' => 'function',
        			'value' => 'vp_get_users',
        		),
        	),
        	
        ),
    ),
    array(
    	'type'	=> 'select',
    	'name'	=> 'applicant_resume',
    	'label'	=> __( 'Applicant Resume', 'jobboard' ),
    	'items' => array(
    		'data' => array(
        		 array(
					'source' => 'binding',
					'field'  => 'applicant_name',
					'value'  => 'jobboard_get_resume',
				),
        	),
        	
        ),
    ),
    array(
    	'type'	=> 'select',
    	'name'	=> 'applied_job',
    	'label'	=> __( 'Applied Job', 'jobboard' ),
    	'items' => array(
        	'data' => array(
        		array(
        			'source' => 'function',
        			'value' => 'jobboard_get_job',
        		),
        	),
        	
        ),
    ),
    array(
    	'type'	=> 'select',
    	'name'	=> 'application_status',
    	'label'	=> __( 'Application Status', 'jobboard' ),
    	'items'	=> array(
    		'data' => array(
    			array(
    				'source' => 'function',
    				'value' => 'jobboard_get_application_status'
    			),
    		),
    	),
    ),
);