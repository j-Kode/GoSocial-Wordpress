<?php

/**
 * Job Board "Summary" metabox template.
 * Define field for "Resume" post type metabox
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
		'type' 	=> 'textbox',
		'name'	=> 'resume_professional_title',
		'label'	=> __( 'Professional Title', 'jobboard' ),
		'description'	=> __( 'ex: "Graphic Designer"', 'jobboard' ),
	),
	array(
		'type'	=> 'textbox',
		'name'	=> 'resume_location',
		'label'	=> __( 'Location', 'jobboard' ),
		'description'	=> __( 'ex: "London - UK"', 'jobboard' ),
	),
	array(
		'type'		=> 'group',
		'repeating'	=> false,
		'length'	=> 1,
		'name'		=> 'skills_group',
		'title'		=> __( 'Skills', 'jobboard' ),
		'fields'	=> array(
			array(
				'type'			=> 'notebox',
				'name'			=> 'skills_desc',
				'description'	=> __( 'Briefly list other skills such as computer knowledge, soft skills.<br /><small><em>'.__( 'Use comma to separate.', 'jobboard' ).'</em></small>', 'jobboard' ),
				'status'		=> 'info',
			),
			array(
				'type'			=> 'textarea',
				'name'			=> 'resume_skills',
				'label'			=> __( 'Skills', 'jobboard' ),
			),
		),
	),
	
	array(
		'type'		=> 'group',
		'repeating'	=> false,
		'length'	=> 1,
		'name'		=> 'education_group_container',
		'title'		=> __( 'Education History', 'jobboard' ),
		'fields'	=> array(
			array(
				'type'			=> 'notebox',
				'name'			=> 'education_info',
				'description'	=> __( 'Please provide details of educational institutions, dates attended and qualifications attained: <br /><small><em>Fields marked with asterisk (*) are required</em></small>', 'jobboard' ),
				'status'		=> 'info',
			),
			array(
				'type'      => 'group',
				'repeating' => true,
				'length'    => 1,
				'name'      => 'education_group',
				'title'     => __('Education', 'jobboard'),
				'fields'    => array(
					array(
						'type'			=> 'textbox',
						'name'			=> 'education_period',
						'label'			=> __( 'Education Period<sup>*</sup>', 'jobboard' ),
						'description'	=> __( 'Enter your education period.<br /> ex: "1998 - 2005"', 'jobboard' ),
						'validation'	=> 'required'
					),
					array(
						'type'		=> 'textbox',
						'name'		=> 'institution_name',
						'label'		=> __( 'Institution Name<sup>*</sup>', 'jobboard' ),
						'validation'=> 'required',
					),
					array(
						'type'		=> 'textbox',
						'name'		=> 'qualification',
						'label'		=> __( 'Qualification(s)<sup>*</sup>', 'jobboard' ),
						'validation'=> 'required',
					),
					array(
						'type'		=> 'textbox',
						'name'		=> 'study_field',
						'label'		=> __( 'Field of Study', 'jobboard' ),
					),
					array(
						'type'		=> 'textbox',
						'name'		=> 'grade',
						'label'		=> __( 'Grade/GPA', 'jobboard' ),
					),
				),
			),
		),
    ),
   array(
		'type'		=> 'group',
		'repeating'	=> false,
		'length'	=> 1,
		'name'		=> 'experience_group_container',
		'title'		=> __( 'Work Experience', 'jobboard' ),
		'fields'	=> array(
			array(
				'type'			=> 'notebox',
				'name'			=> 'experience_info',
				'description'	=> __( 'Please provide the latest working experience, with most recent at the top.<br /><small><em>Fields marked with asterisk (*) are required</em></small>', 'jobboard' ),
				'status'		=> 'info',
			),
			array(
				'type'		=> 'group',
				'repeating'	=> true,
				'length'	=> '1',
				'name'		=> 'experience_group',
				'title'		=> __( 'Experience', 'jobboard' ),
				'fields'	=> array(
					array(
						'type'			=> 'textbox',
						'name'			=> 'employment_period',
						'label'			=> __( 'Employment Period', 'jobboard' ),
						'description'	=> __( 'ex: "2010 - Present"', 'jobboard' ),
					),
					array(
						'type'	=> 'textbox',
						'name'	=> 'company_name',
						'label'	=> __( 'Company', 'jobboard' ),
					),
					array(
						'type'	=> 'textbox',
						'name'	=> 'position',
						'label'	=> __( 'Position', 'jobboard' ),
					),
					array(
						'type'	=> 'textbox',
						'name'	=> 'sallary',
						'label'	=> __( 'Yearly Sallary', 'jobboard' ),
					),
					array(
						'type'	=> 'textarea',
						'name'	=> 'job_duties',
						'label'	=> __( 'Job Duties', 'jobboard' ),
						'use_external_plugins' => '0'
					),
				),
			),
		),
    ),
    array(
		'type'		=> 'group',
		'repeating'	=> false,
		'length'	=> 1,
		'name'		=> 'url_group_container',
		'title'		=> __( 'URL(S)', 'jobboard' ),
		'fields'	=> array(
			array(
				'type'			=> 'notebox',
				'name'			=> 'url_info',
				'description'	=> __( 'You can provide your blog, website or personal album.', 'jobboard'),
				'status'		=> 'info',
			),
			array(
				'type'		=> 'group',
				'repeating'	=> true,
				'length'	=> '1',
				'name'		=> 'url_group',
				'title'		=> __( 'URL', 'jobboard' ),
				'fields'	=> array(
					array(
						'type'	=> 'textbox',
						'name'	=> 'url_name',
						'label'	=> __( 'Name', 'jobboard' ),
					),
					array(
						'type'			=> 'textbox',
						'name'			=> 'url_address',
						'label'			=> __( 'URL', 'jobboard' ),
						'validation'	=> 'url',
					),
				),
			),
		),
	),
);