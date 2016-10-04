<?php

/**
 * Job Board "Job" metabox template.
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
		'type'	=> 'radiobutton',
		'name'	=> 'job_status',
		'label'	=> __( 'Job Status', 'jobboard' ),
		'validation' => 'required',
		'items'	=> array(
			array(
				'value'	=> 'open',
				'label' => __( 'Open', 'jobboard' ),
			),
			array(
				'value'	=> 'closed',
				'label'	=> __( 'Closed', 'jobboard' ),
			),
		),
		'default' => array('open'),
	),
	array(
		'type'	=> 'toggle',
		'name'	=> 'job_featured',
		'label'	=> __( 'Mark as Featured Job', 'jobboard' ),
	),
	array(
		'type' => 'select',
		'name' => 'job_company',
		'label' => __( 'Company', 'jobboard' ),
		'items' => array(
			'data' => array(
            	array(
            		'source' => 'function',
            		'value' => 'jobboard_get_companies',
            	),
        	),
		),
	),
	array(
		'type' => 'textbox',
		'name' => 'job_experiences',
		'label' => __( 'Experience (year)', 'jobboard' ),
		'description' => __( 'Enter the working experiences requirement for this job.', 'jobboard' ),
		'validation' => 'numeric',
	),
	array(
		'type' => 'textbox',
		'name' => 'job_sallary',
		'label' => __( 'Sallary', 'jobboard' ),
		'description' => __( 'Enter the a matter of job sallary per year. So your ad can show in job search page.', 'jobboard' ),
		'validation' => 'numeric',
	),
	array(	
		'type' => 'textbox',
		'name' => 'job_summary',
		'label' => __( 'Job Summary', 'jobboard' ),
		'description' => __( 'Attract relevant job seekers to read further. (Maximum 55 characters)', 'jobboard' ),
		'validation' => 'maxlength[55]',
	),
	array(
		'type' => 'textarea',
		'name' => 'job_overview',
		'label' => __( 'Job Short Overview', 'jobboard' ),
		'description' => __( 'Write something about the job.', 'jobboard' ),
	),

);