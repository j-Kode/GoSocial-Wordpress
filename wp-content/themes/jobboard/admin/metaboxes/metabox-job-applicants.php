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
        'type' => 'html',
        'name' => 'html_1',
        'binding' => array(
            'field'    => '',
            'function' => 'jobboard_job_applicants',
        ),
    ),
);