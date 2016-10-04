<?php
/**
 * Job Board shortcode generator template.
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 */
?><?php

return array(
    // menus
    __( 'Layout', 'jobboard' ) => array(
        // shortcodes collection in this menu
        'elements' => array(
            // shortcode without attribute
            'row' => array(
                'title'   => __('Row', 'jobboard'),
                'code'    => '[jb_row][/jb_row]',
            ),
            // shortcode with attribute
            'column' => array(
                'title'   => __('Column', 'jobboard'),
                'code'    => '[jb_column][/jb_column]',
                'attributes' => array(
                    array(
                        'name'    => 'grid',
                        'type'    => 'slider',
                        'label'   => __('Grid', 'jobboard'),
                        'min'     => 1,
                        'max'     => 12,
                        'default' => 12,
                    ),
                    array(
                        'name'    => 'offset',
                        'type'    => 'slider',
                        'label'   => __('Offset', 'jobboard'),
                        'min'     => 0,
                        'max'     => 11,
                        'default' => 0,
                    ),
                    // ... more attributes
                ),
            ),
            // ... more elements
            'accordion' => array(
            	'title' => __( 'Accordion', 'jobboard' ),
            	'code' => '[jb_accordion][/jb_accordion]',
            	'attributes' => array(
            		array(
            			'name' => 'title',
            			'type' => 'textbox',
            			'label' => __( 'Title', 'jobboard' ),
            		),
            		array(
            			'name' => 'open',
            			'type' => 'toggle',
            			'label' => __( 'Default Open', 'jobboard' ),
            		),
            	),
            ),
        ),
    ),
    
    __( 'Elements', 'jobboard' ) => array(
    	'elements'	=> array(
    		'title' => array(
				'title' => __( 'Title', 'jobboard' ),
				'code' => '[jb_title]',
				'attributes' => array(
					array(
						'name' => 'size',
						'type' => 'radiobutton',
						'label' => __( 'Size', 'jobboard' ),
						'default' => 'normal',
						'items'	=> array(
							array(
								'label' => __( 'Normal', 'jobboard' ),
								'value' => 'normal',
							),
							array(
								'label' => __( 'Large', 'jobboard' ),
								'value' => 'large',
							),
						),
					),
					array(
						'name' => 'content',
						'type' => 'textbox',
						'label' => __( 'Content', 'jobboard' ),
						'default' => __( 'Title Content', 'jobboard' ),
					),
				),
			),
			'blockquote' => array(
				'title' => __( 'Blockquote', 'jobboard' ),
				'code' => '[jb_blockquote][/jb_blockquote]',
			),
			'unordered_list' => array(
				'title' => __( 'Unordered List', 'jobboard' ),
				'code' => '[jb_ul]',
				'attributes' => array(
					
					array(
						'name' => 'icon',
						'type' => 'fontawesome',
						'label' => __( 'List Style Icon', 'jobboard' ),
					),
					array(
						'name' => 'color',
						'type' => 'color',
						'label' => __( 'List item color', 'jobboard' ),
						'default' => '#1abc9c',
					),
					
					array(
						'name' => 'content',
						'type' => 'textarea',
						'label' => __( 'Content', 'jobboard' ),
						'default' => __( 'list 1;list 2;list 3;list 4', 'jobboard' ),
					),
					array(
						'name' => 'note',
						'type' => 'notebox',
						'label' => __( 'Attention', 'jobboard' ),
						'description' => __( 'Enter your list items content. each item separated by semicolon ";"', 'jobboard' ),
					),
				),
			),
			'dropcap' => array(
				'title'			=> __( 'Drop Cap', 'jobboard' ),
				'code'			=> '[jb_dropcap]',
				'attributes'	=> array(
					array(
						'name'		=> 'style',
						'type'		=> 'radiobutton',
						'label'		=> __( 'Style', 'jobboard' ),
						'default'	=> 'normal',
						'items'		=> array(
							array(
								'value'	=> 'normal',
								'label'	=> __( 'Normal', 'jobboard' ),
							),
							array(
								'value'	=> 'boxed',
								'label'	=> __( 'Boxed', 'jobboard' ),
							),
						),
					),
					array(
						'name'		=> 'color',
						'type'		=> 'color',
						'label'		=> __( 'Color', 'jobboard' ),
						'default'	=> '#1abc9c',
					),
					array(
						'name'		=> 'content',
						'type'		=> 'textbox',
						'label'		=> __( 'Content', 'jobboard' ),
						'default'	=> 'S'
					),
				),
			),
			'button' => array(
				'title' => __( 'Button', 'jobboard' ),
				'code' => '[jb_button]',
				'attributes' => array(
					array(
						'type' => 'radiobutton',
						'name' => 'size',
						'label' => __( 'Size', 'jobboard' ),
						'default' => 'medium',
						'items' => array(
							array(
								'label' => __( 'Medium', 'jobboard' ),
								'value' => 'medium',
							),
							array(
								'label' => __( 'Large', 'jobboard' ),
								'value' => 'large',
							),
						),
					),
					array(
						'type' => 'select',
						'name' => 'style',
						'label' => __( 'Style', 'jobboard' ),
						'default' => 'grey',
						'items' => array(
							array(
								'value' => 'green',
								'label' => __( 'Green', 'jobboard' ),
							),
							array(
								'value' => 'purple',
								'label' => __( 'Purple', 'jobboard' ),
							),
							array(
								'value' => 'red',
								'label' => __( 'Red', 'jobboard' ),
							),
							array(
								'value' => 'green',
								'label' => __( 'Green', 'jobboard' ),
							),
							array(
								'value' => 'grey',
								'label' => __( 'Grey', 'jobboard' ),
							),
							array(
								'value' => 'darkgrey',
								'label' => __( 'Dark Grey', 'jobboard' ),
							),
						),
						
					),
					array(
						'type' => 'textbox',
						'name' => 'text',
						'label' => __( 'Text', 'jobboard' ),
						'default' => __( 'Button', 'jobboard' ),
					),
					array(
						'type' => 'textbox',
						'name' => 'url',
						'label' => __( 'URL', 'jobboard' ),
						'default' => '#',
					),
					array(
						'type' => 'radiobutton',
						'name' => 'new_tab',
						'label' => __( 'Open in a new tab?', 'jobboard' ),
						'default' => 'no',
						'items' => array(
							array(
								'value' => 'no',
								'label' => __( 'No', 'jobboard' ),
							),
							array(
								'value' => 'yes',
								'label' => __( 'Yes', 'jobboard' ),
							),
						),
					),
				),
			),
			'alert' => array(
				'title' => __( 'Alert', 'jobboard' ),
				'code' => '[jb_alert][/jb_alert]',
				'attributes' => array(
					array(
						'name' => 'style',
						'type' => 'select',
						'label' => __( 'Style', 'jobboard' ),
						'default' => 'info',
						'items' => array(
							array(
								'value' => 'info',
								'label' => __( 'Info', 'jobboard' ),
							),
							array(
								'value' => 'success',
								'label' => __( 'Success', 'jobboard' ),
							),
							array(
								'value' => 'warning',
								'label' => __( 'Warning', 'jobboard' ),
							),
							array(
								'value' => 'danger',
								'label' => __( 'Danger', 'jobboard' ),
							),
						),
					),
				),
			),
    	),
    ),
    // ... more menus
);