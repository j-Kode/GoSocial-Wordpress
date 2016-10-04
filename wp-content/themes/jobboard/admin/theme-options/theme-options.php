<?php
/**
 * Job Board Theme Options Panels.
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 */
?><?php

return array(
	'title'	=> __('Job Board Options Panel', 'jobboard'),
    'logo'	=> get_template_directory_uri().'/assets/images/theme-options-logo.png',
    'menus'	=> array(
    	// Homepage Settings Menu
    	array(
    		'title'	=> __( 'Homepage Settings', 'jobboard' ),
    		'name'	=> 'homepage_settings',
    		'icon'	=> 'font-awesome:fa-home',
    		'menus'	=> array(
    			
    			// Homepage Slider Settings
    			array(
    				'title'		=> __( 'Image Slider', 'jobboard' ),
    				'name'		=> 'homepage_slider_settings',
    				'icon'		=> 'font-awesome:fa-picture-o',
    				'controls'	=> array(
    					array(
							'type'		=> 'section',
							'title'		=> __( 'Homepage Slider', 'jobboard' ),
							'name'		=> 'homepage_slider',
							'fields'	=> array(
								array(
									'type'		=> 'toggle',
									'name'		=> 'enable_homepage_slider',
									'label'		=> __( 'Enable Homepage Slider', 'jobboard' ),
									'default'	=> '1',
								),
							),
						),
				
						array(
							'type'		=> 'section',
							'title'		=> __( 'Slider Settings', 'jobboard' ),
							'name'		=> 'slider_settings',
							'dependency'=> array(
								'field'		=> 'enable_homepage_slider',
								'function'	=> 'vp_dep_boolean',
							),
							'fields'	=> array(
								array(
									'type'			=> 'select',
									'name'			=> 'select_slider',
									'label'			=> __( 'Select Slider', 'jobboard' ),
									'validation'	=> 'required',
									'items'			=> array(
										'data'	=> array(
											array(
												'source'	=> 'function',
												'value'		=> 'jobboard_get_sliders',
											),
										),
									),
									'default'	=> '{{first}}',
								),
						
								array(
									'type'	=> 'select',
									'name'	=> 'slider_entrance_animation',
									'label'	=> __( 'Slider Entrance Animation', 'jobboard' ),
									'items'	=> array(
										'data'	=> array(
											array(
												'source'	=> 'function',
												'value'		=> 'jobboard_get_entrance_slider_animation',
											),
										),
									),
									'default'	=> 'fadeIn',
								),
						
								array(
									'type'	=> 'select',
									'name'	=> 'slider_exit_animation',
									'label'	=> __( 'Slider Exit Animation', 'jobboard' ),
									'items'	=> array(
										'data'	=> array(
											array(
												'source'	=> 'function',
												'value'		=> 'jobboard_get_exit_slider_animation',
											),
										),
									),
									'default'	=> 'fadeOut',
								),
						
								array(
									'type'		=> 'toggle',
									'name'		=> 'slider_auto_slide',
									'label'		=> __( 'Auto Slide', 'jobboard' ),
									'default'	=> '1',
								),
						
								array(
									'type'			=> 'textbox',
									'name'			=> 'slider_delay',
									'label'			=> __( 'Slide Delay', 'jobboard' ),
									'dependency'	=> array(
										'function'	=> 'vp_dep_boolean',
										'field'		=> 'slider_auto_slide',
									),
									'validation'	=> 'numeric|required',
									'default'		=> '3000',
									'description'	=> __( 'Insert the slide delay in miliseconds.', 'jobboard' ),
								),
						
								array(
									'type'		=> 'toggle',
									'name'		=> 'enable_slider_caption',
									'label'		=> __( 'Enable Slider Caption', 'jobboard' ),
									'default'	=> '1',
								),
							),
						),
						
						array(
							'type'		=> 'section',
							'title'		=> __( 'Slider Caption', 'jobboard' ),
							'name'		=> 'homepage_slider_caption',
							'dependency'=> array(
								'field'		=> 'enable_slider_caption',
								'function'	=> 'vp_dep_boolean',
							),
							'fields'	=> array(
								array(
									'type'		=> 'textbox',
									'name'		=> 'find_job_title',
									'label'		=> __( '"Find a Job" title', 'jobboard' ),
									'default'	=> __( 'Easiest way to find your dream job', 'jobboard' ),
									'validation'=> 'required',
								),
								array(
									'type'		=> 'textarea',
									'name'		=> 'find_job_desc',
									'label'		=> __( '"Find a Job" description', 'jobboard' ),
									'default'	=> __( 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.', 'jobboard' ),
									'validation'=> 'required',
								),
								array(
									'type'		=> 'textbox',
									'name'		=> 'find_job_button',
									'label'		=> __( '"Find a Job" button', 'jobboard' ),
									'default'	=> __( 'Find a Job', 'jobboard' ),
									'validation'=> 'required',
								),
								array(
									'type'		=> 'textbox',
									'name'		=> 'find_job_button_url',
									'label'		=> __( '"Find a Job" button URL', 'jobboard' ),
									'validation'=> 'url',
								),
						
								array(
									'type'		=> 'textbox',
									'name'		=> 'post_job_title',
									'label'		=> __( '"Post a Job" title', 'jobboard' ),
									'default'	=> __( 'Hire Skilled People, best of them', 'jobboard' ),
									'validation'=> 'required',
								),
								array(
									'type'		=> 'textarea',
									'name'		=> 'post_job_desc',
									'label'		=> __( '"Post a Job" description', 'jobboard' ),
									'default'	=> __( 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.', 'jobboard' ),
									'validation'=> 'required',
								),
								array(
									'type'		=> 'textbox',
									'name'		=> 'post_job_button',
									'label'		=> __( '"Post a Job" button', 'jobboard' ),
									'default'	=> __( 'Post a Job', 'jobboard' ),
									'validation'=> 'required',
								),
								array(
									'type'		=> 'textbox',
									'name'		=> 'post_job_button_url',
									'label'		=> __( '"Post a Job" button URL', 'jobboard' ),
									'validation'=> 'url',
								),
							),
						),
					),
    					
    			),
    			
    			// Search Form Settings
    			array(
    				'title'		=> __( 'Search Form', 'jobboard' ),
    				'name'		=> 'homepage_search_form_settings',
    				'icon'		=> 'font-awesome:fa-search',
    				'controls'	=> array(
    					array(
							'type'		=> 'section',
							'title'		=> __( 'Job Search Settings', 'jobboard' ),
							'name'		=> 'job_search_settings',
							'fields'	=> array(
								array(
									'type'	=> 'select',
									'name'	=> 'search_result_page',
									'label'	=> __( 'Job Search Result Page', 'jobboard' ),
									'items'	=> array(
										'data' => array(
											array(
												'source'	=> 'function',
												'value'  	=> 'jobboard_get_search_page',
											),
										),
									),
									'default'	=> '{{first}}'
								),
								array(
									'type'			=> 'textbox',
									'name'			=> 'keyword_placeholder',
									'label'			=> __( 'Keyword Placeholder', 'jobboard' ),
									'default'		=> __( 'Keywords (IT Engineer, Shop Manager, Hr Manager...)', 'jobboard' ),
									'description'	=> __( 'Enter your placeholder text for <strong>"Keyword"</strong> field on Job Search Form.', 'jobboard' ),
								),
								array(
									'type'			=> 'radiobutton',
									'name'			=> 'location_input_type',
									'label'			=> __( 'Location Input', 'jobboard' ),
									'description'	=> __( 'Select the "Location" field input type.', 'jobboard' ),
									'default'		=> 'input_text',
									'items'			=> array(
										array(
											'value'	=> 'input_text',
											'label'	=> __( 'Text Box', 'jobboard' ),
										),
										array(
											'value'	=> 'input_select',
											'label'	=> __( 'Select Box', 'jobboard' ),
										),
									),
						
								),
								array(
									'type'			=> 'textbox',
									'name'			=> 'location_placeholder',
									'label'			=> __( 'Location Placeholder', 'jobboard' ),
									'default'		=> __( 'New York, Hong Kong, New Delhi, Berlin etc.', 'jobboard' ),
									'description'	=> __( 'Enter your placeholder text for <strong>"Location"</strong> field on Job Search Form.', 'jobboard' ),
									'dependency'	=> array(
										'field'		=> 'location_input_type',
										'function'	=> 'jobboard_location_input_type',
									),
								),
								array(
									'type'			=> 'codeeditor',
									'name'			=> 'experience_parameters',
									'label'			=> __( 'Experience Numbers', 'jobboard' ),
									'description'	=> __( 'Enter the list number of experience to show on Job Search Form.', 'jobboard' ),
									'default'		=> "1;1\n2;2\n3;3\n4;4\n5;5\n6;6\n7;7\n8;8\n9;9\n10;10",
								),
								array(
									'type'			=> 'notebox',
									'name'			=> 'job_search_info',
									'label'			=> __( 'Information', 'jobboard' ),
									'description'	=> __( 'Each list number separated by new line, each number line contained value and text separated by comma. (see the default value for example)', 'jobboard' ),
									'status'		=> 'info',
								),
								array(
									'type'			=> 'codeeditor',
									'name'			=> 'salary_parameters',
									'label'			=> __( 'Salary Numbers', 'jobboard' ),
									'description'	=> __( 'Enter the list number of salary to show on Job Search Form.', 'jobboard' ),
									'default'		=> "10000;10K\n20000;20K\n50000;50K\n75000;75K\n100000;100K\n150000;150K\n200000;200K\n250000;250K\n300000;300K\n400000;400K\n500000;500K",
								),
							),
						),
    				),
    			),
    			
    			// Job Status Settings
    			array(
    				'title'		=> __( 'Job Status', 'jobboard' ),
    				'name'		=> 'homepage_job_status_settings',
    				'icon'		=> 'font-awesome:fa-briefcase',
    				'controls'	=> array(
    					
						array(
							'type'		=> 'section',
							'name'		=> 'job_status_settings',
							'title'		=> __( 'Job Status Section Settings', 'jobboard' ),
							'fields'	=> array(
								array(
									'type'		=> 'toggle',
									'name'		=> 'enable_job_status',
									'label'		=> __( 'Enable Job Status Section', 'jobboard' ),
									'default'	=> '1',
								),
								array(
									'type'		=> 'textbox',
									'name'		=> 'job_status_title',
									'label'		=> __( 'Job Status Section Title', 'jobboard' ),
									'default'	=> __( 'Job Status Updates', 'jobboard' ),
									'validation'=> 'required',
									'dependency'=> array(
										'field'		=> 'enable_job_status',
										'function'	=> 'vp_dep_boolean',
									),
								),
								array(
									'type'			=> 'textarea',
									'name'			=> 'job_status_description',
									'label'			=> __( 'Job Status Section Description', 'jobboard' ),
									'dependency'	=> array(
										'field'		=> 'enable_job_status',
										'function'	=> 'vp_dep_boolean',
									),
								),
								
							),
						),
						
    				),
    			),
    			
    			// Job Search Steps
    			array(
    				'title'		=> __( 'How To Find Job', 'jobboard' ),
    				'name'		=> 'homepage_how_to_find',
    				'icon'		=> 'font-awesome:fa-question',
    				'controls'	=> array(
    					array(
    						'type'		=> 'section',
    						'name'		=> 'how_to_find_job',
    						'title'		=> __( 'How to Find Job Settings', 'jobboard' ),
    						'fields'	=> array(
    							array(
									'type'		=> 'toggle',
									'name'		=> 'enable_job_steps',
									'label'		=> __( 'Enable "How To Find Job" section', 'jobboard' ),
									'default'	=> '1',
								),
						
								array(
									'type'			=> 'textbox',
									'name'			=> 'job_steps_title',
									'label'			=> __( 'Section Title', 'jobboard' ),
									'default'		=> __( 'Easiest Way to Use', 'jobboard' ),
									'validation'	=> 'required',
							
									'dependency'	=> array(
										'field'		=> 'enable_job_steps',
										'function'	=> 'vp_dep_boolean',
									),
								),
						
								array(
									'type'			=> 'textarea',
									'name'			=> 'job_steps_description',
									'label'			=> __( 'Section Description', 'jobboard' ),
							
									'dependency'	=> array(
										'field'		=> 'enable_job_steps',
										'function'	=> 'vp_dep_boolean',
									),
								),
    						),
    					),
    					array(
    						'type'		=> 'section',
    						'name'		=> 'how_to_1',
    						'title'		=> __( 'Step 1', 'jobboard' ),
    						'fields'	=> array(
    							array(
    								'name'		=> 'step_1_label',
    								'type'		=> 'textbox',
    								'label'		=> __( 'Step 1 Label', 'jobboard' ),
    								'default'	=> __( 'First Step', 'jobboard' ),
    							),
    							array(
    								'name'			=> 'step_1_image',
    								'type'			=> 'upload',
    								'label'			=> __( 'Step 1 Image', 'jobboard' ),
    								'description'	=> __( 'Recommended image size are 70px of width and 90px of height.', 'jobboard' ),
    							),
    							array(
    								'name'		=> 'step_1_title',
    								'type'		=> 'textbox',
    								'label'		=> __( 'Step 1 Title', 'jobboard' ),
    								'default'	=> __( 'Register with Us', 'jobboard' ),
    							),
    						),
    						'dependency'	=> array(
								'field'		=> 'enable_job_steps',
								'function'	=> 'vp_dep_boolean',
							),
    					),
    					array(
    						'type'		=> 'section',
    						'name'		=> 'how_to_2',
    						'title'		=> __( 'Step 2', 'jobboard' ),
    						'fields'	=> array(
    							array(
    								'name'		=> 'step_2_label',
    								'type'		=> 'textbox',
    								'label'		=> __( 'Step 2 Label', 'jobboard' ),
    								'default'	=> __( 'Second Step', 'jobboard' ),
    							),
    							array(
    								'name'			=> 'step_2_image',
    								'type'			=> 'upload',
    								'label'			=> __( 'Step 2 Image', 'jobboard' ),
    								'description'	=> __( 'Recommended image size are 70px of width and 90px of height.', 'jobboard' ),
    							),
    							array(
    								'name'		=> 'step_2_title',
    								'type'		=> 'textbox',
    								'label'		=> __( 'Step 2 Title', 'jobboard' ),
    								'default'	=> __( 'Create Your Profile', 'jobboard' ),
    							),
    						),
    						'dependency'	=> array(
								'field'		=> 'enable_job_steps',
								'function'	=> 'vp_dep_boolean',
							),
    					),
    					array(
    						'type'		=> 'section',
    						'name'		=> 'how_to_3',
    						'title'		=> __( 'Step 3', 'jobboard' ),
    						'fields'	=> array(
    							array(
    								'name'		=> 'step_3_label',
    								'type'		=> 'textbox',
    								'label'		=> __( 'Step 3 Label', 'jobboard' ),
    								'default'	=> __( 'Third Step', 'jobboard' ),
    							),
    							array(
    								'name'			=> 'step_3_image',
    								'type'			=> 'upload',
    								'label'			=> __( 'Step 3 Image', 'jobboard' ),
    								'description'	=> __( 'Recommended image size are 70px of width and 90px of height.', 'jobboard' ),
    							),
    							array(
    								'name'		=> 'step_3_title',
    								'type'		=> 'textbox',
    								'label'		=> __( 'Step 3 Title', 'jobboard' ),
    								'default'	=> __( 'Upload your resume', 'jobboard' ),
    							),
    						),
    						'dependency'	=> array(
								'field'		=> 'enable_job_steps',
								'function'	=> 'vp_dep_boolean',
							),
    					),
    					array(
    						'type'		=> 'section',
    						'name'		=> 'how_to_4',
    						'title'		=> __( 'Step 4', 'jobboard' ),
    						'fields'	=> array(
    							array(
    								'name'		=> 'step_4_label',
    								'type'		=> 'textbox',
    								'label'		=> __( 'Step 4 Label', 'jobboard' ),
    								'default'	=> __( 'Now It\'s Our Turn', 'jobboard' ),
    							),
    							array(
    								'name'			=> 'step_4_image',
    								'type'			=> 'upload',
    								'label'			=> __( 'Step 4 Image', 'jobboard' ),
    								'description'	=> __( 'Recommended image size are 70px of width and 90px of height.', 'jobboard' ),
    							),
    							array(
    								'name'		=> 'step_4_title',
    								'type'		=> 'textbox',
    								'label'		=> __( 'Step 4 Title', 'jobboard' ),
    								'default'	=> __( 'Now take a rest', 'jobboard' ),
    							),
    						),
    						'dependency'	=> array(
								'field'		=> 'enable_job_steps',
								'function'	=> 'vp_dep_boolean',
							),
    					),
    				),
    			),
    			
    			// Testimonial Section
    			array(
    				'title'		=> __( 'Testimonial', 'jobboard' ),
    				'name'		=> 'homepage_testimonial',
    				'icon'		=> 'font-awesome:fa-comment',
    				'controls'	=> array(
    					array(
    						'type'		=> 'toggle',
    						'name'		=> 'enable_testimonial',
    						'label'		=> __( 'Enable Testimonial section', 'jobboard' ),
    						'default'	=> '1',
    					),
    					
    					array(
    						'type'			=> 'textbox',
    						'name'			=> 'testimonial_title',
    						'label'			=> __( 'Testimonial Section Title', 'jobboard' ),
    						'default'		=> __( 'What People Say About Us', 'jobboard' ),
    						'validation'	=> 'required',
    						
    						'dependency'	=> array(
    							'field'		=> 'enable_testimonial',
    							'function'	=> 'vp_dep_boolean',
    						),
    					),
    					
    					array(
    						'type'			=> 'textarea',
    						'name'			=> 'testimonial_description',
    						'label'			=> __( 'Testimonial Section Description', 'jobboard' ),
    						
    						'dependency'	=> array(
    							'field'		=> 'enable_testimonial',
    							'function'	=> 'vp_dep_boolean',
    						),
    					),
    				),
    			),
    			
    			// Company Carousel
    			array(
    				'title'		=> __( 'Companies', 'jobboard' ),
    				'name'		=> 'homepage_company',
    				'icon'		=> 'font-awesome:fa-group',
    				'controls'	=> array(
    					array(
    						'type'		=> 'toggle',
    						'name'		=> 'enable_company',
    						'label'		=> __( 'Enable Companies section', 'jobboard' ),
    						'default'	=> '1',
    					),
    					array(
    						'type'			=> 'select',
    						'name'			=> 'company_slider',
    						'label'			=> __( 'Select Image Slider', 'jobboard' ),
    						'description'	=> __( 'Select image slider from Slider Post Type.', 'jobboard' ),
    						'validation'	=> 'required',
							'items'			=> array(
								'data'	=> array(
									array(
										'source'	=> 'function',
										'value'		=> 'jobboard_get_sliders',
									),
								),
							),
							'default'	=> '{{first}}',
							
							'dependency'	=> array(
    							'field'		=> 'enable_company',
    							'function'	=> 'vp_dep_boolean',
    						),
    					),
    					array(
    						'type'			=> 'textbox',
    						'name'			=> 'company_title',
    						'label'			=> __( 'Companies Section Title', 'jobboard' ),
    						'default'		=> __( 'Companies who have posted jobs', 'jobboard' ),
    						'validation'	=> 'required',
    						
    						'dependency'	=> array(
    							'field'		=> 'enable_company',
    							'function'	=> 'vp_dep_boolean',
    						),
    					),
    					
    					array(
    						'type'			=> 'textarea',
    						'name'			=> 'company_description',
    						'label'			=> __( 'Company Section Description', 'jobboard' ),
    						
    						'dependency'	=> array(
    							'field'		=> 'enable_company',
    							'function'	=> 'vp_dep_boolean',
    						),
    					),
    				),
    			),
    			
    		),
    	),
    	
    	// Layout Settings Menu
    	array(
    		'title'	=> __('Layout Settings', 'jobboard'),
    		'name'	=> 'layout_settings',
    		'icon'	=> 'font-awesome:fa-columns',
    		'menus'	=> array(
    			// Header Layout Settings
    			array(
    				'title'		=> __( 'Header', 'jobboard' ),
    				'name'		=> 'layout_settings_header',
    				'icon'		=> 'font-awesome:fa-gear',
    				'controls'	=> array(
    					array(
    						'type'		=> 'section',
    						'name'		=> 'main_header_section',
    						'title'		=> __( 'Main Header', 'jobboard' ),
    						'fields'	=> array(
    							array(
    								'type'	=> 'upload',
    								'name'	=> 'custom_header_logo',
    								'label'	=> __( 'Custom Header Logo', 'jobboard' ),
    							),
    						),
    					),
    					
    					array(
    						'type'	=> 'section',
    						'name'	=> 'top_header_section',
    						'title'	=> __( 'Top Header', 'jobboard' ),
    						'fields'=> array(
    							array(
    								'type'	=> 'toggle',
    								'name'	=> 'enable_admin_menu',
    								'label'	=> __( 'Enable Dashboard Menu', 'jobboard' ),
    								'description'	=> __( 'Enable dashboard administration menu on header.', 'jobboard' ),
    							),
    							
    							array(
    								'type'	=> 'toggle',
    								'name'	=> 'enable_social_media_url',
    								'label'	=> __( 'Enable Social Media URL', 'jobboard' ),
    								'description'	=> __( 'Enable social media URL on top header.', 'jobboard' ),
    							),
    							
    							array(
    								'type'	=> 'sorter',
    								'name'	=> 'social_media_sorter',
    								'label'	=> __( 'Social Media Items', 'jobboard' ),
    								'description'	=> __( 'Choose the social media icon that you want to show.', 'jobboard' ),
    								
    								'dependency'	=> array(
										'field' 	=> 'enable_social_media_url',
										'function' 	=> 'vp_dep_boolean',
									),
									
									'validation'=> 'minselected[1]',
    								'items'	=> array(
    									'data'	=> array(
    										array(
    											'source'=> 'function',
    											'value'	=> 'jobboard_get_social_medias',
    										),
    									),
    								),
    							),
    						),
    						
    					),
    				),
    			),
    			
    			// Footer Layout Settings
    			array(
    				'title'		=> __( 'Footer', 'jobboard' ),
    				'name'		=> 'layout_settings_footer',
    				'icon'		=> 'font-awesome:fa-gear',
    				'controls'	=> array(
    					array(
    						'type'			=> 'section',
    						'title'			=> __( 'Footer Contact Banner', 'jobboard' ),
    						'name'			=> 'footer_contact_banner',
    						'fields'		=> array(
    							array(
    								'type'			=> 'toggle',
    								'name'			=> 'enable_footer_contact_banner',
    								'label'			=> __( 'Enable Footer Contact Banner', 'jobboard' ),
    							),
    							
    							array(
    								'type'		=> 'textbox',
    								'name'		=> 'footer_contact_title',
    								'label'		=> __( 'Footer Contact Title', 'jobboard' ),
    								'default'	=> __( 'Hey Friends Any Queries?', 'jobboard' ),
    								'dependency'	=> array(
										'field'		=> 'enable_footer_contact_banner',
										'function'	=> 'vp_dep_boolean',
									),
    							),
    							array(
    								'type'		=> 'textarea',
    								'name'		=> 'footer_contact_description',
    								'label'		=> __( 'Footer Contact Description', 'jobboard' ),
    								'default'	=> ' At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt',
    								'dependency'	=> array(
										'field'		=> 'enable_footer_contact_banner',
										'function'	=> 'vp_dep_boolean',
									),
    							),
    							array(
    								'type'		=> 'textbox',
    								'name'		=> 'footer_contact_number',
    								'label'		=> __( 'Footer Contact Number', 'jobboard' ),
    								'default'	=> 'Call: 1 800 000 500',
    								'dependency'	=> array(
										'field'		=> 'enable_footer_contact_banner',
										'function'	=> 'vp_dep_boolean',
									),
    							),
    						),
    					),
    					array(
    						'type'			=> 'section',
    						'title'			=> __( 'Footer Widgets', 'jobboard' ),
    						'name'			=> 'footer_widget_settings',
    						'description'	=> __( 'Footer widget settings', 'jobboard' ),
    						'fields'		=> array(
    							array(
    								'type'				=> 'radioimage',
    								'name'				=> 'footer_widget_area',
    								'label'				=> __( 'Footer Widget Areas', 'jobboard' ),
    								'description'		=> '',
    								'item_max_width'	=> '45',
    								'item_max_height'	=> '45',
    								'default'			=> '4',
    								'items'				=> array(
    									array(
    										'value'	=> '0',
											'label'	=> __('Footer Widget Off', 'jobboard'),
											'img'	=> get_template_directory_uri().'/assets/images/layout-off.png',
    									),
    									array(
    										'value'	=> '1',
											'label'	=> __('One Column', 'jobboard'),
											'img'	=> get_template_directory_uri().'/assets/images/footer-widgets-1.png',
    									),
    									array(
    										'value'	=> '2',
											'label'	=> __('Two Columns', 'jobboard'),
											'img'	=> get_template_directory_uri().'/assets/images/footer-widgets-2.png',
    									),
    									array(
    										'value'	=> '3',
											'label'	=> __('Three Columns', 'jobboard'),
											'img'	=> get_template_directory_uri().'/assets/images/footer-widgets-3.png',
    									),
    									array(
    										'value'	=> '4',
											'label'	=> __('Four Columns', 'jobboard'),
											'img'	=> get_template_directory_uri().'/assets/images/footer-widgets-4.png',
    									),
    								),
    							),
    						),
    					),
    					array(
    						'type'		=> 'section',
    						'title'		=> __( 'Footer Widget Column Width', 'jobboard' ),
    						'name'		=> 'footer_widget_column_width',
    						'dependency'=> array(
								'field' 	=> 'footer_widget_area',
								'function' 	=> 'vp_dep_boolean',
							),

    						'fields'	=> array(
    							array(
    								'type'	=> 'slider',
    								'name'	=> 'footer_column_width_1',
    								'label'	=> __( 'First Column Width', 'jobboard' ),
    								'min'	=> '2',
    								'max'	=> '10',
    								'steps'	=> '1',
    							),
    							array(
    								'type'	=> 'slider',
    								'name'	=> 'footer_column_width_2',
    								'label'	=> __( 'Second Column Width', 'jobboard' ),
    								'min'	=> '2',
    								'max'	=> '10',
    								'steps'	=> '1',
    								'dependency' => array(
    									'field'		=> 'footer_widget_area',
    									'function'	=> 'jobboard_footer_widget1',
    								),
    							),
    							array(
    								'type'	=> 'slider',
    								'name'	=> 'footer_column_width_3',
    								'label'	=> __( 'Third Column Width', 'jobboard' ),
    								'min'	=> '2',
    								'max'	=> '10',
    								'steps'	=> '1',
    								'dependency' => array(
    									'field'		=> 'footer_widget_area',
    									'function'	=> 'jobboard_footer_widget2',
    								),
    							),
    							array(
    								'type'	=> 'slider',
    								'name'	=> 'footer_column_width_4',
    								'label'	=> __( 'Fourth Column Width', 'jobboard' ),
    								'min'	=> '2',
    								'max'	=> '10',
    								'steps'	=> '1',
    								'dependency' => array(
    									'field'		=> 'footer_widget_area',
    									'function'	=> 'jobboard_footer_widget3',
    								),
    							),
    						),
    					),
    					
    					array(
    						'type'		=> 'section',
    						'title'		=> __( 'Custom Footer', 'jobboard' ),
    						'name'		=> 'custom_footer_settings',
    						'fields'	=> array(
    							array(
    								'type'			=> 'toggle',
    								'name'			=> 'enable_custom_footer',
    								'label'			=> __( 'Enable Custom Footer', 'jobboard' ),
    								'description'	=> __( 'Activate to add the custom text below to the theme footer.', 'jobboard' ),
    							),
    							array(
    								'type'			=> 'textarea',
    								'name'			=> 'custom_footer_text',
    								'label'			=> __( 'Custom Footer Text', 'jobboard' ),
    								'description'	=> __( 'Custom HTML and Text that will appear in the footer of your theme.', 'jobboard' ),
    								'dependency'	=> array(
										'field' 	=> 'enable_custom_footer',
										'function' 	=> 'vp_dep_boolean',
									),
    							),
    						),
    					),
    					
    				),	
    			),
    			
    		),
    	),
    	
    	// Job Settings Menu
    	array(
    		'title'		=> __( 'Job Settings', 'jobboard' ),
    		'name'		=> 'job_settings',
    		'icon'		=> 'font-awesome:fa-briefcase',
    		'controls'	=> array(
    			array(
    				'type'		=> 'section',
    				'title'		=> __( 'Price Format', 'jobboard' ),
    				'name'		=> 'price_format',
    				'fields'	=> array(
    					array(
    						'type'			=> 'textbox',
    						'name'			=> 'currency_sign',
    						'label'			=> __( 'Currency Sign', 'jobboard' ),
    						'description'	=> __( 'Provide currency sign. For example : "$"', 'jobboard' ),
    						'default'		=> '$',
    					),
    					array(
    						'type'			=> 'textbox',
    						'name'			=> 'decimal_point_numbers',
    						'label'			=> __( 'Number of Decimal Points', 'jobboard' ),
    						'description'	=> __( 'Provide the number of decimal points', 'jobboard' ),
    						'default'		=> '0',
    						'validation'	=> 'numeric',
    					),
    					array(
    						'type'			=> 'textbox',
    						'name'			=> 'decimal_point_separator',
    						'label'			=> __( 'Decimal Point Separator', 'jobboard' ),
    						'description'	=> __( 'Provide the decimal point separator', 'jobboard' ),
    						'default'		=> '.',
    					),
    					array(
    						'type'			=> 'textbox',
    						'name'			=> 'thousands_separator',
    						'label'			=> __( 'Thousands Separator', 'jobboard' ),
    						'description'	=> __( 'Provide the thousands separator', 'jobboard' ),
    						'default'		=> ',',
    					),
    				),
    			),
    			
    			array(
    				'type'		=> 'section',
    				'title'		=> __( 'Job Detail Page', 'jobboard' ),
    				'name'		=> 'job_detail_page_settings',
    				'fields'	=> array(
    					array(
    						'type'		=> 'toggle',
    						'name'		=> 'enable_related_job',
    						'label'		=> __( 'Enable Related Job', 'jobboard' ),
    						'description'	=> __( 'Enable related job section in Job Detail page.', 'jobboard' ),
    					),
    					array(
    						'type'		=> 'toggle',
    						'name'		=> 'enable_upload_job_button',
    						'label'		=> __( 'Enable Upload Job/Resume Button', 'jobboard' ),
    					),
    				),
    			),
    			
    			// Section 1 Upload Job/Resume
    			array(
    				'type'		=> 'section',
    				'title'		=> __( 'Section 1', 'jobboard' ),
    				'name'		=> 'section_1_upload',
    				'dependency'=> array(
    					'field'		=> 'enable_upload_job_button',
    					'function'	=> 'vp_dep_boolean',
    				),
    				'fields'	=> array(
    					array(
    						'type'		=> 'textbox',
    						'name'		=> 'post_1_title',
    						'label'		=> __( 'Section Title', 'jobboard' ),
    						'default'	=> 'Upload Your Resume',
    					),
    					array(
    						'type'		=> 'textarea',
    						'name'		=> 'post_1_description',
    						'label'		=> __( 'Section Description', 'jobboard' ),
    						'default'	=> 'At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias',
    					),
    					array(
    						'type'		=> 'textbox',
    						'name'		=> 'post_1_button_text',
    						'label'		=> __( 'Section 1 Button Text', 'jobboard' ),
    						'default'	=> 'Upload Your Resume',
    					),
    					array(
    						'type'		=> 'fontawesome',
    						'name'		=> 'post_1_button_icon',
    						'label'		=> __( 'Section Button Icon', 'jobboard' ),
    						'default'	=> 'fa-upload',
    					),
    					array(
    						'type'		=> 'color',
    						'name'		=> 'post_1_button_color',
    						'label'		=> __( 'Section Button Color', 'jobboard' ),
    						'default'	=> '#565656',
    					),
    					array(
    						'type'		=> 'color',
    						'name'		=> 'post_1_button_text_color',
    						'label'		=> __( 'Section Button Text Color', 'jobboard' ),
    						'default'	=> '#FFFFFF',
    					),
    					array(
    						'type'		=> 'textbox',
    						'name'		=> 'post_1_button_url',
    						'label'		=> __( 'Section Button URL', 'jobboard' ),
    						'validation'=> 'url',
    					),
    				),
    			),
    			
    			// Section 2 Upload Job/Resume
    			array(
    				'type'		=> 'section',
    				'title'		=> __( 'Section 2', 'jobboard' ),
    				'name'		=> 'section_2_upload',
    				'dependency'=> array(
    					'field'		=> 'enable_upload_job_button',
    					'function'	=> 'vp_dep_boolean',
    				),
    				'fields'	=> array(
    					array(
    						'type'		=> 'textbox',
    						'name'		=> 'post_2_title',
    						'label'		=> __( 'Section Title', 'jobboard' ),
    						'default'	=> 'Post Job Now',
    					),
    					array(
    						'type'		=> 'textarea',
    						'name'		=> 'post_2_description',
    						'label'		=> __( 'Section Description', 'jobboard' ),
    						'default'	=> 'At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias',
    					),
    					array(
    						'type'		=> 'textbox',
    						'name'		=> 'post_2_button_text',
    						'label'		=> __( 'Section 1 Button Text', 'jobboard' ),
    						'default'	=> 'Post A Job Now',
    					),
    					array(
    						'type'		=> 'fontawesome',
    						'name'		=> 'post_2_button_icon',
    						'label'		=> __( 'Section Button Icon', 'jobboard' ),
    					),
    					array(
    						'type'		=> 'color',
    						'name'		=> 'post_2_button_color',
    						'label'		=> __( 'Section Button Color', 'jobboard' ),
    						'default'	=> '#1abc9c',
    					),
    					array(
    						'type'		=> 'color',
    						'name'		=> 'post_2_button_text_color',
    						'label'		=> __( 'Section Button Text Color', 'jobboard' ),
    						'default'	=> '#FFFFFF',
    					),
    					array(
    						'type'		=> 'textbox',
    						'name'		=> 'post_2_button_url',
    						'label'		=> __( 'Section Button URL', 'jobboard' ),
    						'validation'=> 'url',
    					),
    				),
    			),
    		),
    	),
    	
    	// Styling Settings Menu
    	array(
    		'title'		=> __( 'Styling', 'jobboard' ),
    		'name'		=> 'styling_settings',
    		'icon'		=> 'font-awesome:fa-magic',
    		'controls'	=> array(
    			array(
    				'type'		=> 'section',
    				'title'		=> __( 'Application Status Color', 'jobboard' ),
    				'name'		=> 'application_status_color',
    				'fields'	=> jobboard_get_application_status_color(),
    			),
    			
    			array(
    				'type'		=> 'section',
    				'title'		=> __( 'Custom CSS', 'jobboard' ),
    				'name'		=> 'custom_css_settings',
    				'fields'	=> array(
    					array(
    						'name'			=> 'custom_css_box',
    						'type'			=> 'codeeditor',
    						'label'			=> __( 'Custom CSS Box', 'jobboard' ),
    						'description'	=> __( 'Insert your custom css styling here', 'jobboard' ), 
    						'mode'			=> 'css',
    					),
    				),
    			),
    		),
    	),
    	
    	// Frontend Submission Settings Menu
    	array(
    		'title'		=> __( 'Frontend Submission', 'jobboard' ),
    		'name'		=> 'frontend_submission_settings',
    		'icon'		=> 'font-awesome:fa-ticket',
    		'controls'	=> array(
    			array(
    				'type'		=> 'section',
    				'title'		=> __( 'Page Settings', 'jobboard' ),
    				'name'		=> 'page_settings',
    				'fields'	=> array(
    					
    					array(
    						'type'	=> 'select',
    						'name'	=> 'dashboard_page',
    						'label'	=> __( 'Account Dashboard Page', 'jobboard' ),
    						'items'	=> array(
    							'data' => array(
									array(
										'source'	=> 'function',
										'value'  	=> 'jobboard_get_dashboard_page',
									),
								),
    						),
    						'default'	=> '{{first}}'
    					),
    					array(
    						'type'	=> 'select',
    						'name'	=> 'post_job_page',
    						'label'	=> __( 'Post a Job Page', 'jobboard' ),
    						'items'	=> array(
    							'data' => array(
									array(
										'source'	=> 'function',
										'value'  	=> 'jobboard_get_post_job_page',
									),
								),
    						),
    						'default'	=> '{{first}}',
    					),
    					array(
    						'type'	=> 'select',
    						'name'	=> 'post_company_page',
    						'label'	=> __( 'Add Company Page', 'jobboard' ),
    						'items'	=> array(
    							'data' => array(
									array(
										'source'	=> 'function',
										'value'  	=> 'jobboard_get_company_page',
									),
								),
    						),
    						'default'	=> '{{first}}',
    					),
    					array(
    						'type'	=> 'select',
    						'name'	=> 'post_resume_page',
    						'label'	=> __( 'Post a Resume Page', 'jobboard' ),
    						'items'	=> array(
    							'data' => array(
									array(
										'source'	=> 'function',
										'value'  	=> 'jobboard_get_post_resume_page',
									),
								),
    						),
    						'default'	=> '{{first}}',
    					),
    					array(
    						'type'	=> 'select',
    						'name'	=> 'login_page',
    						'label'	=> __( 'Login Page', 'jobboard' ),
    						'items'	=> array(
    							'data' => array(
									array(
										'source'	=> 'function',
										'value'  	=> 'jobboard_get_login_page',
									),
								),
    						),
    						'default'	=> '{{first}}',
    					),
    					array(
    						'type'	=> 'select',
    						'name'	=> 'register_page',
    						'label'	=> __( 'Register Page', 'jobboard' ),
    						'items'	=> array(
    							'data' => array(
									array(
										'source'	=> 'function',
										'value'  	=> 'jobboard_get_register_page',
									),
								),
    						),
    						'default'	=> '{{first}}',
    					),
    					
    				),
    			),
    			
    			array(
    				'type'	=> 'section',
    				'title'	=> __( 'Payments', 'jobboard' ),
    				'name'	=> 'jobboard_job_payment',
    				'fields'=> array(
    					array(
    						'type'			=> 'toggle',
    						'name'			=> 'activate_payment',
    						'label'			=> __( 'Charge for Submit', 'jobboard' ),
    						'description'	=> __( 'Activate to charge user for post each job.', 'jobboard' ),
    					),
    					
    					array(
    						'type'		=> 'select',
    						'name'		=> 'payment_currency',
    						'label'		=> __( 'Payment Currency', 'jobboard' ),
    						'items'		=> array(
    							'data' => array(
									array(
										'source'	=> 'function',
										'value'  	=> 'jobboard_get_payment_currency',
									),
								),
    						),
    						'default'	=> 'USD',
    						
    						'dependency'	=> array(
   								'field'		=> 'activate_payment',
   								'function'	=> 'vp_dep_boolean',
    						),
    					),
    					
    					array(
    						'type'		=> 'textbox',
    						'name'		=> 'currency_symbol',
    						'label'		=> __( 'Currency Symbol', 'jobboard' ),
    						'default'	=> '$',
    						
    						'dependency'	=> array(
   								'field'		=> 'activate_payment',
   								'function'	=> 'vp_dep_boolean',
    						),
    					),
    					
    					array(
    						'type'		=> 'textbox',
    						'name'		=> 'cost_per_post',
    						'label'		=> __( 'Cost per Job Posted', 'jobboard' ),
    						'validation'=> 'numeric',
    						
    						'dependency'	=> array(
   								'field'		=> 'activate_payment',
   								'function'	=> 'vp_dep_boolean',
    						),
    					),
    					
    					array(
    						'type'		=> 'toggle',
    						'name'		=> 'payment_sandbox_mode',
    						'label'		=> __( 'Enable Demo/Sanbox Mode', 'jobboard' ),
    						
    						'dependency'	=> array(
   								'field'		=> 'activate_payment',
   								'function'	=> 'vp_dep_boolean',
    						),
    					),
    					
    					array(
    						'type'		=> 'textbox',
    						'name'		=> 'paypal_email',
    						'label'		=> __( 'Paypal Email', 'jobboard' ),
    						'validation'=> 'email',
    						
    						'dependency'	=> array(
   								'field'		=> 'activate_payment',
   								'function'	=> 'vp_dep_boolean',
    						),
    					),
    					
    				),
    			),
    		),
    	),
    	
    	// Contact Settings
    	array(
    		'title'		=> __( 'Contact Settings', 'jobboard' ),
    		'name'		=> 'contact_settings',
    		'icon'		=> 'font-awesome:fa-envelope',
    		'controls'	=> array(
    			
    			array(
    				'type'		=> 'section',
    				'title'		=> __( 'Contact Information', 'jobboard' ),
    				'name'		=> 'contact_info_settings',
    				'fields'	=> array(
    					array(
    						'type'	=> 'textarea',
    						'name'	=> 'contact_info_address',
    						'label'	=> __( 'Address', 'jobboard' ),
    						'default'	=> '5th Avenue Street, 103 Floor, Trump Tower Crosss Road, LA 450001'
    					),
    					array(
    						'type'	=> 'textbox',
    						'name'	=> 'contact_info_telp',
    						'label'	=> __( 'Telephone Number', 'jobboard' ),
    						'default'	=> '+1 81000 0001',
    					),
    					array(
    						'type'	=> 'textbox',
    						'name'	=> 'contact_info_email',
    						'label'	=> __( 'Email Address', 'jobboard' ),
    						'validation'	=> 'email',
    					),
    					array(
    						'type'	=> 'textbox',
    						'name'	=> 'gmap_latitude',
    						'label'	=> __( 'Google Map Latitude', 'jobboard' ),
    					),
    					array(
    						'type'	=> 'textbox',
    						'name'	=> 'gmap_longitude',
    						'label'	=> __( 'Google Map Longitude', 'jobboard' ),
    					),
    				),
    			),
    			
    			array(
    				'type'		=> 'section',
    				'title'		=> __( 'Social Media', 'jobboard' ),
    				'name'		=> 'social_media_settings',
    				'fields'	=> array(
    					array(
    						'type'	=> 'textbox',
    						'name'	=> 'social_facebook',
    						'label'	=> __( 'Facebook', 'jobboard' ),
    						'validation'	=> 'url',
    					),
    					array(
    						'type'	=> 'textbox',
    						'name'	=> 'social_twitter',
    						'label'	=> __( 'Twitter', 'jobboard' ),
    						'validation'	=> 'url',
    					),
    					array(
    						'type'	=> 'textbox',
    						'name'	=> 'social_google-plus',
    						'label'	=> __( 'Google Plus', 'jobboard' ),
    						'validation'	=> 'url',
    					),
    					array(
    						'type'	=> 'textbox',
    						'name'	=> 'social_youtube',
    						'label'	=> __( 'Youtube', 'jobboard' ),
    						'validation'	=> 'url',
    					),
    					array(
    						'type'	=> 'textbox',
    						'name'	=> 'social_linkedin',
    						'label'	=> __( 'LinkedIn', 'jobboard' ),
    						'validation'	=> 'url',
    					),
    					array(
    						'type'	=> 'textbox',
    						'name'	=> 'social_rss',
    						'label'	=> __( 'RSS', 'jobboard' ),
    						'validation'	=> 'url',
    					),
    					array(
    						'type'	=> 'textbox',
    						'name'	=> 'social_flickr',
    						'label'	=> __( 'Flickr', 'jobboard' ),
    						'validation'	=> 'url',
    					),
    					array(
    						'type'	=> 'textbox',
    						'name'	=> 'social_vimeo-square',
    						'label'	=> __( 'Vimeo', 'jobboard' ),
    						'validation'	=> 'url',
    					),
    					array(
    						'type'	=> 'textbox',
    						'name'	=> 'social_dribbble',
    						'label'	=> __( 'Dribbble', 'jobboard' ),
    						'validation'	=> 'url',
    					),
    					array(
    						'type'	=> 'textbox',
    						'name'	=> 'social_tumblr',
    						'label'	=> __( 'Tumblr', 'jobboard' ),
    						'validation'	=> 'url',
    					),
    					
    				),
    			),
    			
    		),
    	),
    ),
);
