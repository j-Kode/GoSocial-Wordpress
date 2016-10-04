<?php
/**
 * Job Board admin data source.
 * Used for populate data for options in metabox and theme options
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 */
 
if( !function_exists( 'jobboard_get_companies' ) ){

function jobboard_get_companies(){
	$result = array();
	$args = array(
		'post_type' => 'company',
		'posts_per_page' => -1,
	);
	$posts = get_posts($args);
	foreach( $posts as $post ){
		$result[] = array(
			'value' => $post->ID,
			'label' => $post->post_title,
		);
	}
	return $result;
}
VP_Security::instance()->whitelist_function('jobboard_get_companies');

}//endif;

if( !function_exists( 'jobboard_footer_widget1' ) ){

function jobboard_footer_widget1($value){
	$args   = func_get_args();
	$result = true;
	foreach ($args as $val)
    {
        $result = ($val > 1);
    }
    return $result;
}
VP_Security::instance()->whitelist_function('jobboard_footer_widget1');

}//endif;

if( !function_exists( 'jobboard_footer_widget2' ) ){

function jobboard_footer_widget2($value){
	$args   = func_get_args();
	$result = true;
	foreach ($args as $val)
    {
        $result = ($val > 2);
    }
    return $result;
}
VP_Security::instance()->whitelist_function('jobboard_footer_widget2');

}//endif;

if( !function_exists( 'jobboard_footer_widget3' ) ){

function jobboard_footer_widget3($value){
	$args   = func_get_args();
	$result = true;
	foreach ($args as $val)
    {
        $result = ($val > 3);
    }
    return $result;
}
VP_Security::instance()->whitelist_function('jobboard_footer_widget3');

}//endif

if( !function_exists( 'jobboard_get_job' ) ){
	
	function jobboard_get_job(){
		$job_args = array(
			'post_type' => 'job',
			'posts_per_page' => -1,
		);
		
		$job_query = get_posts( $job_args );
		
		$job_lists = array();
		foreach( $job_query as $job ){
			$comp_id = get_post_meta( $job->ID, '_jboard_job_company', true );
			$job_lists[] = array(
				'value' => $job->ID,
				'label' => $job->post_title.'&nbsp;-&nbsp;'.get_the_title($comp_id),
			);
		}
		
		return $job_lists;
	}
	
	VP_Security::instance()->whitelist_function('jobboard_get_job');
}//endif;

if( !function_exists( 'jobboard_get_application_status' ) ){
	
	function jobboard_get_application_status(){
		$stats_args = array(
			'hide_empty' => false,
		);
		$terms = get_terms( 'application_status', $stats_args );
		$stats_list = array();
		foreach( $terms as $term ){
			$stats_list[] = array(
				'value' => $term->term_id,
				'label' => $term->name,
			);
		}
		
		return $stats_list;
	}
	
	VP_Security::instance()->whitelist_function('jobboard_get_application_status');
}//endif;

if( !function_exists( 'jobboard_get_resume' ) ){
	
	function jobboard_get_resume( $author ){
		$resume_list = array();
		$resumes = get_posts( array( 'post_type' => 'resume', 'posts_per_page' => -1, 'author' => $author) );
		foreach( $resumes as $resume ){
			$resume_list[] = array(
				'value'	=> $resume->ID,
				'label'	=> $resume->post_title,
			);
		}
		return $resume_list;
	}
	
	VP_Security::instance()->whitelist_function('jobboard_get_resume');
}//endif;

if( !function_exists( 'jobboard_get_post_job_page' ) ){
	
	function jobboard_get_post_job_page(){
		$page_list = array();
		$pages = get_posts( array(
			'post_type'		=> 'page',
			'meta_query'	=> array(
				array(
					'key'	=> '_wp_page_template',
					'value'	=> 'page-templates/template-post_job.php',
				),
			),
		) );
		
		foreach( $pages as $page ){
			$page_list[] = array(
				'value'	=> $page->ID,
				'label'	=> $page->post_title,
			);
		}//endforeach;
		
		return $page_list;
	}
	VP_Security::instance()->whitelist_function('jobboard_get_post_job_page');
	
}//endif;

if( !function_exists( 'jobboard_get_post_resume_page' ) ){
	
	function jobboard_get_post_resume_page(){
		$page_list = array();
		$pages = get_posts( array(
			'post_type'		=> 'page',
			'meta_query'	=> array(
				array(
					'key'	=> '_wp_page_template',
					'value'	=> 'page-templates/template-post_resume.php',
				),
			),
		) );
		
		foreach( $pages as $page ){
			$page_list[] = array(
				'value'	=> $page->ID,
				'label'	=> $page->post_title,
			);
		}//endforeach;
		
		return $page_list;
	}
	VP_Security::instance()->whitelist_function('jobboard_get_post_resume_page');
	
}//endif;

if( !function_exists( 'jobboard_get_dashboard_page' ) ){
	
	function jobboard_get_dashboard_page(){
		$page_list = array();
		$pages = get_posts( array(
			'post_type'		=> 'page',
			'meta_query'	=> array(
				array(
					'key'	=> '_wp_page_template',
					'value'	=> 'page-templates/template-account_dashboard.php',
				),
			),
		) );
		
		foreach( $pages as $page ){
			$page_list[] = array(
				'value'	=> $page->ID,
				'label'	=> $page->post_title,
			);
		}//endforeach;
		
		return $page_list;
	}
	VP_Security::instance()->whitelist_function('jobboard_get_dashboard_page');
	
}//endif;

if( !function_exists( 'jobboard_get_login_page' ) ){
	
	function jobboard_get_login_page(){
		$page_list = array();
		$pages = get_posts( array(
			'post_type'		=> 'page',
			'meta_query'	=> array(
				array(
					'key'	=> '_wp_page_template',
					'value'	=> 'page-templates/template-login.php',
				),
			),
		) );
		
		foreach( $pages as $page ){
			$page_list[] = array(
				'value'	=> $page->ID,
				'label'	=> $page->post_title,
			);
		}//endforeach;
		
		return $page_list;
	}
	VP_Security::instance()->whitelist_function('jobboard_get_login_page');
	
}//endif;

if( !function_exists( 'jobboard_get_register_page' ) ){
	
	function jobboard_get_register_page(){
		$page_list = array();
		$pages = get_posts( array(
			'post_type'		=> 'page',
			'meta_query'	=> array(
				array(
					'key'	=> '_wp_page_template',
					'value'	=> 'page-templates/template-register.php',
				),
			),
		) );
		
		foreach( $pages as $page ){
			$page_list[] = array(
				'value'	=> $page->ID,
				'label'	=> $page->post_title,
			);
		}//endforeach;
		
		return $page_list;
	}
	VP_Security::instance()->whitelist_function('jobboard_get_register_page');
	
}//endif;

if( !function_exists( 'jobboard_get_company_page' ) ){
	
	function jobboard_get_company_page(){
		$page_list = array();
		$pages = get_posts( array(
			'post_type'		=> 'page',
			'meta_query'	=> array(
				array(
					'key'	=> '_wp_page_template',
					'value'	=> 'page-templates/template-add_company.php',
				),
			),
		) );
		
		foreach( $pages as $page ){
			$page_list[] = array(
				'value'	=> $page->ID,
				'label'	=> $page->post_title,
			);
		}//endforeach;
		
		return $page_list;
	}
	VP_Security::instance()->whitelist_function('jobboard_get_company_page');
	
}//endif;

if( !function_exists( 'jobboard_get_search_page' ) ){
	
	function jobboard_get_search_page(){
		$page_list = array();
		$pages = get_posts( array(
			'post_type'		=> 'page',
			'meta_query'	=> array(
				array(
					'key'	=> '_wp_page_template',
					'value'	=> 'page-templates/template-job_search.php',
				),
			),
		) );
		
		foreach( $pages as $page ){
			$page_list[] = array(
				'value'	=> $page->ID,
				'label'	=> $page->post_title,
			);
		}//endforeach;
		
		return $page_list;
	}
	VP_Security::instance()->whitelist_function('jobboard_get_search_page');
	
}//endif;

if( !function_exists( 'jobboard_get_application_status_color' ) ){
	
	function jobboard_get_application_status_color(){
		
		$options = array();
		$terms = get_terms( 'application_status',  array( 'hide_empty' => false ) );
		foreach( $terms as $term ){
			$options[] = array(
				'type'		=> 'color',
				'name'		=> 'application_status_'.$term->slug.'_'.$term->term_id,
				'label'		=> $term->name,
				'default'	=> '#CCCCCC',
					
			);
		}//endforeach;
		
		return $options;
		
	}
}//endif;


if( !function_exists( 'jobboard_get_social_medias' ) ){

	function jobboard_get_social_medias(){
		$socials_media_items = array(
			'facebook'		=> __( 'Facebook', 'jobboard' ),
			'twitter'		=> __( 'Twitter', 'jobboard' ),
			'google-plus'	=> __( 'Google Plus', 'jobboard' ),
			'youtube'		=> __( 'YouTube', 'jobboard' ),
			'linkedin'		=> __( 'LinkedIn', 'jobboard' ),
			'rss'			=> __( 'RSS', 'jobboard' ),
			'flickr'		=> __( 'Flickr', 'jobboard' ),
			'vimeo-square'	=> __( 'Vimeo', 'jobboard' ),
			'dribbble'		=> __( 'Dribbble', 'jobboard' ),
			'tumblr'		=> __( 'Tumblr', 'jobboard' ),			
		);
		
		$socials_media = array();
		
		foreach( $socials_media_items as $key => $item){
			$socials_media[] = array(
				'value'		=> $key,
				'label'		=> $item,
			);
		}
		
		return $socials_media;
		
	}
	VP_Security::instance()->whitelist_function('jobboard_get_social_medias');
	
}//endif;

if( !function_exists( 'jobboard_location_input_type' ) ){
	
	function jobboard_location_input_type( $value ){
		$args = func_get_args();
		$result = false;
		foreach( $args as $value ){
			$result = ( $value == 'input_text' );
		}
		return $result;
	}
	VP_Security::instance()->whitelist_function('jobboard_location_input_type');
	
}//endif;

if( !function_exists( 'jobboard_get_payment_currency' ) ){

	function jobboard_get_payment_currency(){
		$currency = array(
			'AUD' => __( 'Australian Dollar', 'jobboard' ),
			'CAD' => __( 'Canadian Dollar', 'jobboard' ),
			'EUR' => __( 'Euro', 'jobboard' ),
			'GBP' => __( 'British Pound', 'jobboard' ),
			'JPY' => __( 'Japanese Yen', 'jobboard' ),
			'USD' => __( 'U.S. Dollar', 'jobboard' ),
			'NZD' => __( 'New Zealand Dollar', 'jobboard' ),
			'CHF' => __( 'Swiss Franc', 'jobboard' ),
			'HKD' => __( 'Hong Kong Dollar', 'jobboard' ),
			'SGD' => __( 'Singapore Dollar', 'jobboard' ),
			'SEK' => __( 'Swedish Krona', 'jobboard' ),
			'DKK' => __( 'Danish Krone', 'jobboard' ),
			'PLN' => __( 'Polish Zloty', 'jobboard' ),
			'NOK' => __( 'Norwegian Krone', 'jobboard' ),
			'HUF' => __( 'Hungarian Forint', 'jobboard' ),
			'CZK' => __( 'Czech Koruna', 'jobboard' ),
			'ILS' => __( 'Israeli New Shekel', 'jobboard' ),
			'MXN' => __( 'Mexican Peso', 'jobboard' ),
			'BRL' => __( 'Brazilian Real', 'jobboard' ),
			'MYR' => __( 'Malaysian Ringgit', 'jobboard' ),
			'PHP' => __( 'Philippine Peso', 'jobboard' ),
			'TWD' => __( 'New Taiwan Dollar', 'jobboard' ),
			'THB' => __( 'Thai Baht', 'jobboard' ),
			'TRY' => __( 'Turkish Lira', 'jobboard' ),
		);
		
		$return = array();
		foreach( $currency as $key => $value ){
			$return[] = array(
				'value'	=> $key,
				'label'	=> $value,
			);
		}
		
		return $return;
	}
	VP_Security::instance()->whitelist_function('jobboard_get_payment_currency');
}//endif;

if( !function_exists( 'jobboard_get_sliders' ) ){
	
	function jobboard_get_sliders(){
		
		$return = array();
		$args = array(
			'post_type'			=> 'jb_slider',
			'posts_per_page'	=> -1,
		);
		$posts = get_posts($args);
		foreach( $posts as $post ){
			$return[] = array(
				'value'	=> $post->ID,
				'label'	=> $post->post_title,
			);
		}
		
		return $return;
		
	}
	
	VP_Security::instance()->whitelist_function('jobboard_get_sliders');
}//endif;

if( !function_exists( 'jobboard_get_entrance_slider_animation' ) ){
	
	function jobboard_get_entrance_slider_animation(){
		$data_animation = array(
			'bounceIn' 			=> __( 'Bounce-In', 'jobboard' ),
			'bounceInDown'		=> __( 'Bounce-In-Down', 'jobboard' ),
			'bounceInLeft'		=> __( 'Bounce-In-Left', 'jobboard' ),
			'bounceInRight'		=> __( 'Bounce-In-Right', 'jobboard' ),
			'bounceInUp'		=> __( 'Bounce-In-Up', 'jobboard' ),
			'fadeIn'			=> __( 'Fade-In', 'jobboard' ),
			'fadeInDown'		=> __( 'Fade-In-Down', 'jobboard' ),
			'fadeInDownBig'		=> __( 'Fade-In-Down-Big', 'jobboard' ),
			'fadeInLeft'		=> __( 'Fade-In-Left', 'jobboard' ),
			'fadeInLeftBig'		=> __( 'Fade-In-Left-Big', 'jobboard' ),
			'fadeInRight'		=> __( 'Fade-In-Right', 'jobboard' ),
			'fadeInRightBig'	=> __( 'Fade-In-Right-Big', 'jobboard' ),
			'fadeInUp'			=> __( 'Fade-In-Up', 'jobboard' ),
			'fadeInUpBig'		=> __( 'Fade-In-Up-Big', 'jobboard' ),
			'flipInX'			=> __( 'Flip-In-X', 'jobboard' ),
			'flipInY'			=> __( 'Flip-In-Y', 'jobboard' ),
			'lightSpeedIn'		=> __( 'Light-Speed-In', 'jobboard' ),
			'rotateIn'			=> __( 'Rotate-In', 'jobboard' ),
			'rotateInDownLeft'	=> __( 'Rotate-In-Down-Left', 'jobboard' ),
			'rotateInDownRight'	=> __( 'Rotate-In-Down-Right', 'jobboard' ),
			'rotateInUpLeft'	=> __( 'Rotate-In-Up-Left', 'jobboard' ),
			'rotateInUpRight'	=> __( 'Rotate-In-Up-Right', 'jobboard' ),
			'rollIn'			=> __( 'Roll-In', 'jobboard' ),
			'zoomIn'			=> __( 'Zoom-In', 'jobboard' ),
			'zoomInDown'		=> __( 'Zoom-In-Down', 'jobboard' ),
			'zoomInLeft'		=> __( 'Zoom-In-Left', 'jobboard' ),
			'zoomInRight'		=> __( 'Zoom-In-Right', 'jobboard' ),
			'zoomInUp'			=> __( 'Zoom-In-Up', 'jobboard' ),			
		);
		
		$return = array();
		foreach( $data_animation as $key => $value ){
			$return[] = array(
				'value'	=> $key,
				'label'	=> $value,
			);
		}
		return $return;
	}
	
	VP_Security::instance()->whitelist_function('jobboard_get_entrance_slider_animation');
}//endif;

if( !function_exists( 'jobboard_get_exit_slider_animation' ) ){
	
	function jobboard_get_exit_slider_animation(){
		$data_animation = array(
			'bounceOut' 		=> __( 'Bounce-Out', 'jobboard' ),
			'bounceOutDown'		=> __( 'Bounce-Out-Down', 'jobboard' ),
			'bounceOutLeft'		=> __( 'Bounce-Out-Left', 'jobboard' ),
			'bounceOutRight'	=> __( 'Bounce-Out-Right', 'jobboard' ),
			'bounceOutUp'		=> __( 'Bounce-Out-Up', 'jobboard' ),
			'fadeOut'			=> __( 'Fade-Out', 'jobboard' ),
			'fadeOutDown'		=> __( 'Fade-Out-Down', 'jobboard' ),
			'fadeOutDownBig'	=> __( 'Fade-Out-Down-Big', 'jobboard' ),
			'fadeOutLeft'		=> __( 'Fade-Out-Left', 'jobboard' ),
			'fadeOutLeftBig'	=> __( 'Fade-Out-Left-Big', 'jobboard' ),
			'fadeOutRight'		=> __( 'Fade-Out-Right', 'jobboard' ),
			'fadeOutRightBig'	=> __( 'Fade-Out-Right-Big', 'jobboard' ),
			'fadeOutUp'			=> __( 'Fade-Out-Up', 'jobboard' ),
			'fadeOutUpBig'		=> __( 'Fade-Out-Up-Big', 'jobboard' ),
			'flipOutX'			=> __( 'Flip-Out-X', 'jobboard' ),
			'flipOutY'			=> __( 'Flip-Out-Y', 'jobboard' ),
			'lightSpeedOut'		=> __( 'Light-Speed-Out', 'jobboard' ),
			'rotateOut'		=> __( 'Rotate-Out', 'jobboard' ),
			'rotateOutDownLeft'	=> __( 'Rotate-Out-Down-Left', 'jobboard' ),
			'rotateOutDownRight'=> __( 'Rotate-Out-Down-Right', 'jobboard' ),
			'rotateOutUpLeft'	=> __( 'Rotate-Out-Up-Left', 'jobboard' ),
			'rotateOutUpRight'	=> __( 'Rotate-Out-Up-Right', 'jobboard' ),
			'rollOut'			=> __( 'Roll-Out', 'jobboard' ),
			'zoomOut'			=> __( 'Zoom-Out', 'jobboard' ),
			'zoomOutDown'		=> __( 'Zoom-Out-Down', 'jobboard' ),
			'zoomOutLeft'		=> __( 'Zoom-Out-Left', 'jobboard' ),
			'zoomOutRight'		=> __( 'Zoom-Out-Right', 'jobboard' ),
			'zoomOutUp'			=> __( 'Zoom-Out-Up', 'jobboard' ),			
		);
		
		$return = array();
		foreach( $data_animation as $key => $value ){
			$return[] = array(
				'value'	=> $key,
				'label'	=> $value,
			);
		}
		return $return;
	}
	
	VP_Security::instance()->whitelist_function('jobboard_get_exit_slider_animation');
}//endif;