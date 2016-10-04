<?php
/**
 * Job Board pre-defined functions
 * All functions is pluggable
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 */
?><?php
 $titleErr = $regionErr = $experienceErr = $typeErr = $rateErr = $contactErr = $overviewErr = $postErr = $humanErr = "";
if( !function_exists( 'jobboard_seo_url' ) ){
	function jobboard_seo_url($string){
		//Lower case everything
		$string = strtolower($string);
		//Make alphanumeric (removes all other characters)
		$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
		//Clean up multiple dashes or whitespaces
		$string = preg_replace("/[\s-]+/", " ", $string);
		//Convert whitespaces and underscore to dash
		$string = preg_replace("/[\s_]/", "-", $string);
		return $string;
	}
}//endif;

if( !function_exists( 'jobboard_not_found' ) ){
	function jobboard_not_found( $type ){
		
		$default_type = 'post';
		
		switch( $type ){
			
			case 'job_search':
				echo '
					<div class="alert alert-info">
						<strong>'.__( 'Sorry,', 'jobboard' ).'</strong> '.__( 'your search did not match any facility posting.', 'jobboard' ).'
					</div>
				';
			break;
		}
		
	}
}//endif;

if( !function_exists( 'jobboard_read_more_link' ) ){
	add_filter( 'the_content_more_link', 'jobboard_read_more_link' );
	function jobboard_read_more_link() {
		return '<div class="jobboard-more-link"><a href="' . get_permalink() . '">'.__( 'Read More', 'jobboard' ).' <i class="fa fa-chevron-right"></i></a></div>';
	}
}//endif;

if( !function_exists( 'jobboard_search_form' ) ){
	function jobboard_search_form( $form ) {
		$form = '<form role="search" method="get" id="searchform" class="jobboard-searchform" action="' . home_url( '/' ) . '" >
		<div class="form-group">
			<div class="input-group">
				<div class="input-group-addon">
				<a href="#"><i class="fa fa-search"></i></a>
				</div>
				<input class="form-control" type="text" id="s" name="s" />
				<input type="hidden" id="searchsubmit" value="'. __( 'Search' ) .'" />
			</div>
		</div>
		</form>';

		return $form;
	}
	add_filter( 'get_search_form', 'jobboard_search_form' );
}//endif;

/*------------------------------------------------------------------*/
/*	Custom comment form callback	*/
/*------------------------------------------------------------------*/
if( !function_exists( 'jobboard_remove_comment_url_field' ) ){

	function jobboard_remove_comment_url_field($fields){
		unset($fields['url']);
    	return $fields;
	}
	//add_filter( 'comment_form_default_fields', 'jobboard_remove_comment_url_field');
}

/*------------------------------------------------------------------*/
/*	Custom post thumbnail function	*/
/*------------------------------------------------------------------*/
if( !function_exists( 'jobboard_get_the_post_thumbnail' ) ){
	function jobboard_get_the_post_thumbnail( $id, $size ){
		
		if( has_post_thumbnail() ){
			return get_the_post_thumbnail( $id, $size );
		}
		
	}
}

/*------------------------------------------------------------------*/
/*	Custom CSS function	*/
/*------------------------------------------------------------------*/
if( !function_exists( 'jobboard_custom_css_box' ) ){
	
	add_action( 'wp_head', 'jobboard_custom_css_box', 99 );
	function jobboard_custom_css_box(){
		$custom_css = jobboard_option( 'custom_css_box' );
		if ( !empty( $custom_css ) ) {
			echo '<style type="text/css" id="custom_css">';
				echo $custom_css;
			echo '</style>';
		} 
	}
	
}//endif;

/*------------------------------------------------------------------*/
/*	Relative date function	*/
/*------------------------------------------------------------------*/
if( !function_exists( 'jobboard_time_ago' ) ){
	function jobboard_time_ago($from){
		$to = current_time( 'timestamp' );
		$from = strtotime($from);
		
		return human_time_diff( $from, $to );
	}
}

/*------------------------------------------------------------------*/
/*	Form action path url	*/
/*------------------------------------------------------------------*/
if( !function_exists( 'jobboard_form_action' ) ){
	function jobboard_form_action(){
		return get_template_directory_uri().'/includes/frontend-submission/form-submit.php';
	}
}//endif;

/*------------------------------------------------------------------*/
/*	Comment Walker Class	*/
/*------------------------------------------------------------------*/
class Jobboard_Walker_Comment extends Walker_Comment {
	
	// init classwide variables
	var $tree_type = 'comment';
	var $db_fields = array( 'parent' => 'comment_parent', 'id' => 'comment_ID' );

	/** CONSTRUCTOR
	 * You'll have to use this if you plan to get to the top of the comments list, as
	 * start_lvl() only goes as high as 1 deep nested comments */
	function __construct() { ?>
		<ul id="comment-list" class="comment-list">
		
	<?php }
	
	/** START_LVL 
	 * Starts the list before the CHILD elements are added. Unlike most of the walkers,
	 * the start_lvl function means the start of a nested comment. It applies to the first
	 * new level under the comments that are not replies. Also, it appear that, by default,
	 * WordPress just echos the walk instead of passing it to &$output properly. Go figure.  */
	function start_lvl( &$output, $depth = 0, $args = array() ) {		
		$GLOBALS['comment_depth'] = $depth + 1; ?>

				<ul class="children">
	<?php }

	/** END_LVL 
	 * Ends the children list of after the elements are added. */
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$GLOBALS['comment_depth'] = $depth + 1; ?>

		</ul><!-- /.children -->
		
	<?php }
	
	/** START_EL */
	
	function start_el( &$output, $comment, $depth = 0, $args = array(), $id = 0 ) {
		$depth++;
		$GLOBALS['comment_depth'] = $depth;
		$GLOBALS['comment'] = $comment; 
		$parent_class = ( empty( $args['has_children'] ) ? '' : 'parent' ); ?>
		
		<li <?php comment_class( $parent_class ); ?> id="comment-<?php comment_ID() ?>">
			<div id="comment-body-<?php comment_ID() ?>" class="comment-body clearfix">
				<div class="comment-left-side">
					<div class="comment-author vcard author">
						<?php echo ( $args['avatar_size'] != 0 ? get_avatar( $comment, $args['avatar_size'] ) :'' ); ?>
					</div><!-- /.comment-author -->
				</div><!-- /.comment-left-side -->
				
				<div class="comment-right-side">
					<span class="comment-meta comment-meta-data">
						<cite class="fn n author-name"><?php echo get_comment_author_link( get_comment_ID() ); ?></cite>,
						<a href="<?php echo htmlspecialchars( get_comment_link( get_comment_ID() ) ) ?>"><?php comment_date(); ?> at <?php comment_time(); ?></a>
					</span><!-- /.comment-meta -->
					&nbsp;:&nbsp;
					<span id="comment-content-<?php comment_ID(); ?>" class="comment-content">
						<?php if( !$comment->comment_approved ) : ?>
						<em class="comment-awaiting-moderation">Your comment is awaiting moderation.</em>
					
						<?php else: echo esc_attr( get_comment_text() ); ?>
						<?php endif; ?>
					</span><!-- /.comment-content -->

					<div class="reply">
					<?php
						$reply_args = array(
							'depth' => $depth,
							'max_depth' => $args['max_depth'],
							'reply_text' => '<i class="fa fa-reply"></i>&nbsp;'.__( 'Reply', 'jobboard' ),						
						);
	
						comment_reply_link( array_merge( $args, $reply_args ) );  ?>
						<?php edit_comment_link( '(Edit)' ); ?>
					</div><!-- /.reply -->
				</div><!-- /.comment-right-side -->
			</div><!-- /.comment-body -->

	<?php }

	function end_el(&$output, $comment, $depth = 0, $args = array() ) { ?>
		
		</li><!-- /#comment-' . get_comment_ID() . ' -->
		
	<?php }
	
	/** DESTRUCTOR
	 * I just using this since we needed to use the constructor to reach the top 
	 * of the comments list, just seems to balance out :) */
	function __destruct() { ?>
	
	</ul><!-- /#comment-list -->

	<?php }
}

/*------------------------------------------------------------------*/
/*	Function to handle frontend Post Resume	*/
/*------------------------------------------------------------------*/
if( !function_exists( 'jobboard_post_resume' ) ){

function jobboard_post_resume($data = array(), $files = array(), $update = false ){
	

	$resume_args = array(
		'post_content'		=> $data['resume_content'],
		'post_title'		=> $data['title'],
		'post_status'		=> 'pending',
		'post_type'			=> 'resume',
		'post_author'		=> $data['user_id'],
	);
	$message = '10';
	if($update){
		$resume_args['post_status'] = get_post_status( $_POST['resume_id'] );
		$resume_args['ID'] = $_POST['resume_id'];
		$message = '11';
	}//endif;
	
	$resume_url = array();
	$i = 0;
	foreach($data['url_address'] as $url){
		if( $url != '' ){
			$resume_url[] = array(
				'url_name' => $data['url_name'][$i],
				'url_address' => $url,
			);
		}
		$i++;
	}
	
	$resume_education = array();
	$i = 0;
	foreach( $data['education_name'] as $edu ){
		if( $edu != '' ){
			$resume_education[] = array(
				'education_period' => $data['education_period'][$i],
				'institution_name' => $edu,
				'qualification' => $data['education_qualification'][$i],
				'study_field' => $data['education_study'][$i],
				'grade' => $data['education_grade'][$i],
			);
		}
		$i++;
	}
	
	$resume_experience = array();
	$i = 0;
	foreach( $data['experience_company'] as $exp ){
		if( $exp != '' ){
			$resume_experience[] = array(
				'employment_period' => $data['experience_period'][$i],
				'company_name' => $data['experience_company'][$i],
				'position' => $data['experience_position'][$i],
				'sallary' => $data['experience_sallary'][$i],
				'job_duties' => $data['experience_job'][$i],
			);
		}
		$i++;
	}
	
	$meta_input = array(
		'resume_professional_title' => $data['job_title'],
		'resume_location' => $data['location'],
		'skills_group' => array(
			array(
				'resume_skills' => $data['skills'],
			),
		),
		'url_group_container' => array(
			array(
				'url_group' => $resume_url,
			),
		),
		'education_group_container' => array(
			array(
				'education_group' => $resume_education,
			),
		),
		'experience_group_container' => array(
			array(
				'experience_group' => $resume_experience,
			),
		),
	);
	
	
	$resume_id = wp_insert_post( $resume_args );
	if( isset($resume_id) ){
		if( isset($data['category']) ){
			wp_set_object_terms( $resume_id, $data['category'], 'resume_category' );
		}
	
		$old_post_meta = get_post_meta( $resume_id, 'jobboard_resume_mb', true );
		update_post_meta( $resume_id, 'jobboard_resume_mb', $meta_input, $old_post_meta );
		
		// Upload  photo file
		if( !empty( $files['photo']['name'] ) ){
			$attach_id = jobboard_file_upload( $files['photo'], 'image', $resume_id );
			if( $attach_id ){
				set_post_thumbnail( $resume_id, $attach_id );
			}
		}
		
		
		// Upload resume file
		if( !empty( $files['resume_file']['name'] ) ){
			$resume_file = jobboard_file_upload( $files['resume_file'], 'file' );
			$old_resume_file = get_post_meta( $resume_id, 'jobboard_resume_file', true );
			if($resume_file){
				update_post_meta( $resume_id, 'jobboard_resume_file', $resume_file['url'], $old_resume_file  );
			}
		}
		
		wp_redirect( add_query_arg( array( 'action' => 'edit', 'jid' => $resume_id, 'message' => $message ) ) );
		exit;
			
	}//endif $resume_id
}

}//endif;

/*------------------------------------------------------------------*/
/*	Function to handle frontend Post Job	*/
/*------------------------------------------------------------------*/
if( !function_exists( 'jobboard_post_job' ) ){

	function jobboard_post_job( $data = array(), $update = false ){
		ECHO 'test';
		$job_args = array(
			'post_content'		=> $data['job_description'],
			'post_title'		=> $data['job_title'],
			'post_status'		=> 'publish',
			'post_type'			=> 'job',
			'post_author'		=> $data['user_id'],
			'post_date'			=> $data['posting_date'],
		);
		
	if(empty($_POST["job_title"])) {
		$required = 1;
	}
	else if(empty($_POST["job_experience"])){
		$required = 1;
	}
	else if(empty($_POST["job_sallary"])){
		$required = 1;
	}
	else if(empty($_POST["job_summary"])){
		$required = 1;
	}
	else if(empty($_POST["job_overview"])){
		$required = 1;
	}
	else if(strtolower($_POST["cat_sound"]) != "meow"){
		$required = 1;
	}
	else if(strtolower($_POST["job_country"]) == "-- select region --"){
		$required = 1;
	}
	else if(strtolower($_POST["job_state"]) == "-- select state/province --"){
		$required = 1;
	}
	else if(strtolower($_POST["job_city"]) == "-- select city --"){
		$required = 1;
	}
	else if(strtolower($_POST["job_type"]) == "-- select type --"){
		$required = 1;
	}
	else if(strtolower($_POST["job_company"]) == "-- are you a trainer or athlete? --"){
		$required = 1;
	}
	else 
	{
		$required = 0;
	}
	if($required == 0)
	{
		
		$message = '1';
		
		$job_id = '';
		if( $update ){
			$job_args['ID'] = $data['post_id'];
			$job_args['post_status'] = get_post_status( $data['post_id'] );
			$message = '2';
		}
		
		$job_id = wp_insert_post( $job_args );
		
		if($job_id){
			if( isset( $data['job_country'] ) ){
				wp_set_object_terms( $job_id, $data['job_country'], 'job_country' );
			}
			if( isset( $data['job_type'] ) ){
				wp_set_object_terms( $job_id, $data['job_type'], 'job_type' );
			}
			if( isset( $data['job_category'] ) ){
				wp_set_object_terms( $job_id, $data['job_category'], 'job_category' );
			}
			
			update_post_meta($job_id, '_jboard_job_country', $data['job_country']);
			update_post_meta($job_id, '_jboard_job_state', $data['job_state']);
			update_post_meta($job_id, '_jboard_job_city', $data['job_city']);
			// Job Company Metabox
			update_post_meta( $job_id, '_jboard_job_company', $data['job_company'] );
			
			// Job Experience Metabox
			update_post_meta( $job_id, '_jboard_job_experiences', $data['job_experience'] );
			
			// Job Sallary Metabox
			update_post_meta( $job_id, '_jboard_job_sallary', $data['job_sallary'] );
			
			// Job Summary Metabox
			update_post_meta( $job_id, '_jboard_job_summary', $data['job_summary'] );
			
			// Job Overview Metabox
			update_post_meta( $job_id, '_jboard_job_overview', $data['job_overview'] );
			
			// Job metabox data set
			$job_meta = array(
				'_jboard_job_country',
				'_jboard_job_state',
				'_jboard_job_city',
				'_jboard_job_company',
				'_jboard_job_experiences',
				'_jboard_job_sallary',
				'_jboard_job_summary',
				'_jboard_job_overview',
			);
			update_post_meta( $job_id, 'jobboard_job_mb_fields', $job_meta );
			//add_query_arg( array( 'action' => 'edit', 'jid' => $job_id, 'message' => $message ) ) 
			
                        //wp_redirect('http://www.google.com');
			wp_redirect(add_query_arg( array('message' => "true", 'required' => 0 ) ) );
			exit;
		}
	}
	wp_redirect(add_query_arg( array( 'message' => "false", 'required' => $required ) ) );
	}
	
}//endif;

/*------------------------------------------------------------------*/
/*	Function to handle frontend Post Company	*/
/*------------------------------------------------------------------*/
if( !function_exists( 'jobboard_post_company' ) ){
	
	function jobboard_post_company( $data = array(), $files = array(), $update = false ){
		
		$message = '3';
		$company_args = array(
			'post_type'		=> 'company',
			'post_title'	=> $data['company_name'],
			'author'		=> get_current_user_id(),
			'post_status'	=> 'publish',
		);
		
		if( $update ){
			$company_args['ID'] = $data['post_id'];
			$company_args['post_status'] = get_post_status( $data['post_id'] );
			$message = '4';
		}
		
		$comp_id = wp_insert_post($company_args);
		
		if($comp_id){
			
			// Company Overview Metabox
			update_post_meta( $comp_id, '_jboard_company_description', $data['company_description'] );
			
			// Company Website URL
			update_post_meta( $comp_id, '_jboard_company_web_address', $data['company_website'] );
			
			// Company Facebook URL
			update_post_meta( $comp_id, '_jboard_company_social_facebook', $data['company_facebook'] );
			
			// Company Twitter URL
			update_post_meta( $comp_id, '_jboard_company_social_twitter', $data['company_twitter'] );
			
			// Company Google Plus
			update_post_meta( $comp_id, '_jboard_company_social_googleplus', $data['company_google_plus'] );
			
			$company_meta = array(
				'_jboard_company_description',
				'_jboard_company_web_address',
				'_jboard_company_social_facebook',
				'_jboard_company_social_twitter',
				'_jboard_company_social_googleplus',	
			);
			
			update_post_meta( $comp_id, 'jobboard_company_mb_fields', $company_meta );
			
			// Upload  Company Image
			if( !empty( $files['company_image']['name'] ) ){
				$attach_id = jobboard_file_upload( $files['company_image'], 'image', $comp_id );
				if( $attach_id ){
					if( has_post_thumbnail( $comp_id ) ){
						delete_post_thumbnail($comp_id);
					}
					set_post_thumbnail( $comp_id, $attach_id );
				}
			}
			
			wp_redirect( add_query_arg( array( 'action' => 'edit', 'jid' => $comp_id, 'message' => $message ) ) );
			exit;
		}//endif;
	}
	
}//endif;

/*------------------------------------------------------------------*/
/*	Function to handle upload files	*/
/*------------------------------------------------------------------*/
if( !function_exists( 'jobboard_file_upload' ) ){

	function jobboard_file_upload( $file = array(), $type = 'image', $post_id = null ){
		
		// Phase 1 : Upload photo to WordPress upload directory
		if ( ! function_exists( 'wp_handle_upload' ) ){
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}
		
		$uploadedfile = $file;
		$movefile = wp_handle_upload( $uploadedfile, array( 'test_form' => false ) );
		
		if ( $movefile ) {
			
			if( $type == 'file' ){
				return $movefile;
			}elseif( $type == 'image' ){
				// Phase 2 : Insert Photo as attachment post
				$filename =  $movefile['file'];

				// The ID of the post this attachment is for.
				$parent_post_id = $post_id;

				// Check the type of tile. We'll use this as the 'post_mime_type'.
				$filetype = wp_check_filetype( basename( $filename ), null );

				// Get the path to the upload directory.
				$wp_upload_dir = wp_upload_dir();

				// Prepare an array of post data for the attachment.
				$attachment = array(
					'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
					'post_mime_type' => $filetype['type'],
					'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
					'post_content'   => '',
					'post_status'    => 'inherit'
				);

				// Insert the attachment.
				$attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );

				// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
				require_once( ABSPATH . 'wp-admin/includes/image.php' );

				// Generate the metadata for the attachment, and update the database record.
				$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
				wp_update_attachment_metadata( $attach_id, $attach_data );
				
				return $attach_id;
			}
			
		}else{
			return __( 'Upload failed', 'jobboard' );
		}
	
	}
		
}//endif;

/*------------------------------------------------------------------*/
/*	Google map init functions	*/
/*------------------------------------------------------------------*/
if( !function_exists( 'jobboard_create_gmaps' ) ){

	function jobboard_create_gmaps( $object = '', $args = array() ){
		$latitude = jobboard_option( 'gmap_latitude' );
		$longitude = jobboard_option( 'gmap_longitude' );
		$default_args = array(
			'target'	=> $object,
			'latitude'	=> $latitude,
			'longitude'	=> $longitude,
			'zoom'		=> 12,
			'marker'	=> true,
			'show'		=> true,
		);
		
		if( empty($args) ){
			$args = $default_args;
		}
		
		wp_enqueue_script( 'google-map', 'https://maps.googleapis.com/maps/api/js', array(), '3.0', true );
		wp_enqueue_script( 'jobboard-map', get_template_directory_uri().'/assets/js/map.js', array( 'google-map' ), '1.0', true );
		wp_localize_script( 'jobboard-map', 'gmaps', $args );		
				
	}

}//endif;


/*------------------------------------------------------------------*/
/*	Ajax Contact form function	*/
/*------------------------------------------------------------------*/
if( !function_exists( 'jobboard_contact_form' ) ){
	add_action( 'wp_ajax_nopriv_jobboard_send_contact_form', 'jobboard_contact_form' );
	add_action( 'wp_ajax_jobboard_send_contact_form', 'jobboard_contact_form' );
	
	function jobboard_contact_form(){
		
		if( isset( $_POST['contact_submit'] ) && $_POST['contact_submit'] == '1' ){
			
			$data = array();
			
			if( isset( $_POST['contact_name'] ) ){
				$data['name'] = trim( $_POST['contact_name'] );
			}
			if( isset( $_POST['contact_email'] ) ){
				$data['email'] = trim( $_POST['contact_email'] );
			}
			if( isset( $_POST['contact_telp'] ) ){
				$data['telp'] = trim( $_POST['contact_telp'] );
			}
			if( isset( $_POST['contact_website'] ) ){
				$data['website'] = trim( $_POST['contact_website'] );
			}
			if( isset( $_POST['contact_subject'] ) ){
				$data['subject'] = trim( $_POST['contact_subject'] );
			}
			if( isset( $_POST['contact_message'] ) ){
				$data['message'] = wp_kses( $_POST['contact_message'], array() );
			}
			
			$email_to = jobboard_option( 'contact_info_email' );
			$subject = __( 'Contact Form Submission From : ', 'jobboard' ).$data['name'];
			$body = "You have received message from : \n\nName : ".$data['name']." \n\nEmail : ".$data['email']." \n\nTel : ".$data['telp']." \n\nWebsite : ".$data['website']." \n\nSubject : ".$data['subject']." \n\nMessage : \n".$data['message'];
			$headers = 'From: '.get_bloginfo('name').' <'.get_bloginfo('admin_email').'>';
			
			$sent = wp_mail( $email_to, $subject, $body, $headers );
				if($sent){
					$response = array(
					   'what'=>'status',
					   'action'=>'contact_email',
					   'id'=>'1',
					   'data'=>true
					);
					$xmlResponse = new WP_Ajax_Response($response);
					$xmlResponse->send();
				}else{
					echo 'fail';
				}
			
		}//endif;
		
	}
	
}//endif;

/*------------------------------------------------------------------*/
/*	Ajax delete post item function	*/
/*------------------------------------------------------------------*/
if( !function_exists( 'jobboard_delete_post_item' ) ){
	
	add_action( 'wp_ajax_nopriv_jobboard_delete_post_item', 'jobboard_delete_post_item' );
	add_action( 'wp_ajax_jobboard_delete_post_item', 'jobboard_delete_post_item' );
	
	function jobboard_delete_post_item(){
		
		$post_id = $_POST['post_id'];
		if( $post_id != '' ){
			wp_delete_post($post_id);
			$response = array(
			   'what'=>'foobar',
			   'action'=>'update_something',
			   'id'=>'1',
			   'data'=>$post_id
			);
			$xmlResponse = new WP_Ajax_Response($response);
			$xmlResponse->send();
		}
	}
	
}//endif;

/*------------------------------------------------------------------*/
/*	Ajax Featured post item function	*/
/*------------------------------------------------------------------*/
if( !function_exists( 'jobboard_featured_post_item' ) ){
	
	add_action( 'wp_ajax_nopriv_jobboard_add_featured_post', 'jobboard_featured_post_item' );
	add_action( 'wp_ajax_jobboard_add_featured_post', 'jobboard_featured_post_item' );
	
	function jobboard_featured_post_item(){
		
		$post_id = $_POST['post_id'];
		if( $post_id != '' ){
			
			$old_post_meta = get_post_meta( $post_id, '_jboard_job_featured', true );
			update_post_meta( $post_id, '_jboard_job_featured', '1', $old_post_meta );
			
		}//endif;
		
	}
	
}//endif;

/*------------------------------------------------------------------*/
/*	Add Extra Field on user page function	*/
/*------------------------------------------------------------------*/

if( !function_exists( 'jobboard_add_user_extra_field' ) ){
	$user_id = get_current_user_id();
	if ( current_user_can( 'edit_user', $user_id ) ){
		add_action( 'show_user_profile', 'jobboard_add_user_extra_field' );
		add_action( 'edit_user_profile', 'jobboard_add_user_extra_field' );	
	}
	
	function jobboard_add_user_extra_field( $user ){
		if( !current_user_can( 'edit_theme_options' ) ){
			$disabled = 'disabled="disabled"';
		}else{
			$disabled = '';
		}
		
		$old_value = get_user_meta( $user->ID, 'jobboard_user_role', true );
	?>
	<h3><?php _e('Job Board Extra Profile Information', 'jobboard'); ?></h3>

	<table class="form-table">
		<tr>
			<th><label for="jobboard_user_role"><?php _e( 'User Role', 'jobboard' ); ?></label></th>
			<td>
				<select name="jobboard_user_role" id="jobboard_user_role" <?php echo $disabled; ?> >
					<option value=""<?php if( $old_value == '' ){ echo 'selected="selected"'; } ?>><?php _e( '-- Select Role --', 'jobboard' ); ?></option>
					<option value="job_lister" <?php if( $old_value == 'job_lister' ){ echo 'selected="selected"'; } ?>><?php _e( 'Job Lister', 'jobboard' ); ?></option>
					<option value="job_seeker" <?php if( $old_value == 'job_seeker' ){ echo 'selected="selected"'; } ?>><?php _e( 'Job Seeker', 'jobboard' ); ?></option>
				</select>
			</td>
		</tr>
	</table>
	<?php
	}
}

/*------------------------------------------------------------------*/
/*	Save Extra Field on user page function	*/
/*------------------------------------------------------------------*/
if( !function_exists( 'jobboard_save_user_extra_field' ) ){

	add_action( 'personal_options_update', 'jobboard_save_user_extra_field' );
	add_action( 'edit_user_profile_update', 'jobboard_save_user_extra_field' );

	function jobboard_save_user_extra_field( $user_id ) {

		if ( current_user_can( 'edit_theme_option', $user_id ) ){
			update_user_meta( $user_id, 'jobboard_user_role', $_POST['jobboard_user_role'] );
		}
		
	}
}//endif;

/*------------------------------------------------------------------*/
/*	Add CSS role for Custom Styling	*/
/*------------------------------------------------------------------*/
if( !function_exists( 'jobbboard_custom_style' ) ){
	
	add_action( 'wp_head', 'jobboard_custom_style', 90 );
	
	function jobboard_custom_style(){

		echo '<style type="text/css" id="application-status-color">';
		
		// Aplication Status Color
		$options = array();
		$terms = get_terms( 'application_status',  array( 'hide_empty' => false ) );
		foreach( $terms as $term ){
			
			echo '
				.application-status_'.$term->slug.'_'.$term->term_id.'{
					background-color:'.jobboard_option( 'application_status_'.$term->slug.'_'.$term->term_id ).';
				}
				
			';
			
		}//endforeach;
		
		echo '</style>';
	}
	
}//endif;

/*------------------------------------------------------------------*/
/*	Jobboard Get Permalink Function	*/
/*------------------------------------------------------------------*/
if( !function_exists( 'jobboard_get_permalink' ) ){
	
	function jobboard_get_permalink( $key ){
		
		$return = '';
		switch( $key ){
			
			case 'login':
				$return = add_query_arg( 'redirect', urlencode(get_permalink()), get_permalink( jobboard_option( 'login_page' ) ) );
				break;
				
			case 'logout':
				$return = add_query_arg( 'action', 'logout', get_permalink( jobboard_option( 'login_page' ) ) );
				break;
				
			case 'register':
				$return = get_permalink( jobboard_option( 'register_page' ) );
				break;
			
			case 'dashboard':
				$return = get_permalink( jobboard_option( 'dashboard_page' ) );
				break;
			
			case 'profile':
				$return = get_edit_user_link(get_current_user_id());
				break;
			
			case 'post_job':
				$return = get_permalink( jobboard_option( 'post_job_page' ) );
				break;
				
			case 'post_resume':
				$return = get_permalink( jobboard_option( 'post_resume_page' ) );
				break;
			
			case 'post_company':
				$return = get_permalink( jobboard_option( 'post_company_page' ) );
				break;
			
			case 'job_search':
				$return = get_permalink( jobboard_option( 'search_result_page' ) );
				break;
				
		}//endswicth;
		
		return $return;
		
	}
	
}//endif;

/*------------------------------------------------------------------*/
/*	Jobboard Forbidden Page Function	*/
/*------------------------------------------------------------------*/
if( !function_exists( 'jobboard_forbidden' ) ){
	
	function jobboard_forbidden( $arg ){
		
		switch( $arg ){
			
			case 'login':
				
			?>
				<div class="forbidden-container">
					<div class="container">
					<?php
						echo __( 'You need to be', 'jobboard' ).' <a href="'.jobboard_get_permalink( 'login' ).'">'.__( 'signed in', 'jobboard' ).'</a> '.__( 'to view this page.', 'jobboard' );
					?>
					</div><!-- /.container -->
				</div><!-- /.forbidden-container -->
			<?php
				
				break;
			
		}//endswicth;
		
	}
}//endif;

/*------------------------------------------------------------------*/
/*	Jobboard Get Social Media Lists	*/
/*------------------------------------------------------------------*/
if( !function_exists( 'jobboard_get_social_media_items' ) ){
	
	function jobboard_get_social_media_items(){
		return array(
			'facebook'		=> __( 'Facebook', 'jobboard' ),
			'twitter'		=> __( 'Twitter', 'jobboard' ),
			'google-plus'	=> __( 'Google Plus', 'jobboard' ),
			'youtube'		=> __( 'YouTube', 'jobboard' ),
			'linkedin'		=> __( 'LinkedIn', 'jobboard' ),
			'rss'			=> __( 'RSS', 'jobboard' ),
			'flickr'		=> __( 'Flickr', 'jobboard' ),
			'vimeo'			=> __( 'Vimeo', 'jobboard' ),
			'dribbble'		=> __( 'Dribbble', 'jobboard' ),
			'tumbrl'		=> __( 'Tumblr', 'jobboard' ),			
		);
	}
	
}//endif;

/*------------------------------------------------------------------*/
/*	Function to handle status message	*/
/*------------------------------------------------------------------*/
if( !function_exists( 'jobboard_set_post_message' ) ){
	
	function jobboard_set_post_message( $code = false ){
		
		if($code){
			
			//echo $code;
			
			switch($code){
				
				case '1':
					echo '<div class="alert alert-success alert-dismissable" role="alert">';
					echo '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">'.__( 'Close', 'jobboard' ).'</span></button>';
					_e( 'Job Published.', 'jobboard' );
					echo '<a href="'.esc_url( get_permalink( $_GET['jid'] ) ).'" target="_blank">'.__( 'View Job', 'jobboard' ).'</a>';
					echo '</div>';
					break;
				
				case '2':
					echo '<div class="alert alert-success alert-dismissable" role="alert">';
					echo '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">'.__( 'Close', 'jobboard' ).'</span></button>';
					_e( 'Job Updated.', 'jobboard' );
					echo '<a href="'.esc_url( get_permalink( $_GET['jid'] ) ).'" target="_blank">'.__( 'View Job', 'jobboard' ).'</a>';
					echo '</div>';
					break;
					
				case '3':
					echo '<div class="alert alert-success alert-dismissable" role="alert">';
					echo '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">'.__( 'Close', 'jobboard' ).'</span></button>';
					_e( 'Company Added.', 'jobboard' );
					//echo '<a href="'.esc_url( get_permalink( $_GET['jid'] ) ).'" target="_blank">'.__( 'View Company', 'jobboard' ).'</a>';
					echo '</div>';
					break;
				
				case '4':
					echo '<div class="alert alert-success alert-dismissable" role="alert">';
					echo '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">'.__( 'Close', 'jobboard' ).'</span></button>';
					_e( 'Company Updated.', 'jobboard' );
					//echo '<a href="'.esc_url( get_permalink( $_GET['jid'] ) ).'" target="_blank">'.__( 'View Company', 'jobboard' ).'</a>';
					echo '</div>';
					break;
				
				case '6':
					echo '<div class="alert alert-danger" role="alert">';
					echo '<strong>'.__( 'Attention!', 'jobboard' ).' </strong>'.__( 'You have to add at least one company in order to post a job. Click', 'jobboard' ).' <a href="'.esc_url( jobboard_get_permalink('post_company') ).'">'.__( 'here', 'jobboard' ).'</a> '.__( 'to add your company first.', 'jobboard' );
					echo '</div>';
					break;
					
				case '10':
					echo '<div class="alert alert-success alert-dismissable" role="alert">';
					echo '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">'.__( 'Close', 'jobboard' ).'</span></button>';
					_e( 'Resume created successfully. Your resume will be visible once approved.', 'jobboard' );
					echo '<a href="'.esc_url( get_permalink( $_GET['jid'] ) ).'" target="_blank"> '.__( 'Preview Resume', 'jobboard' ).'</a>';
					echo '</div>';
					break;
				case '11':
					echo '<div class="alert alert-success alert-dismissable" role="alert">';
					echo '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">'.__( 'Close', 'jobboard' ).'</span></button>';
					_e( 'Resume Updated.', 'jobboard' );
					echo '<a href="'.esc_url( get_permalink( $_GET['jid'] ) ).'" target="_blank"> '.__( 'View Resume', 'jobboard' ).'</a>';
					echo '</div>';
					break;
			}
			
		}//endif;
	}
}//endif;


/*------------------------------------------------------------------*/
/*	Function to handle Apply Job button	*/
/*------------------------------------------------------------------*/
if( !function_exists( 'jobboard_apply_job_button' ) ){
	
	function jobboard_apply_job_button( $id ){
		
		$apply_arg = array(
			'post_type'		=> 'application',
			'meta_query'	=> array(
				'relation'	=> 'AND',
				array(
					'key'	=> '_jboard_applied_job',
					'value'	=> $id,
				),
				array(
					'key'	=> '_jboard_applicant_name',
					'value'	=> get_current_user_id(),
				),
			),
		);
		
		$disable = '';
		$btn_text = __( 'Apply', 'jobboard' );
		
		$posts = new WP_Query( $apply_arg );
		if( $posts->have_posts() ){
			$disable = 'disabled';
			$btn_text = __( 'Applied', 'jobboard' );
		}
	?>
		<button class="btn btn-apply-job <?php echo $disable; ?>" type="submit" value="1" data-toggle="modal" data-target="#apply-job-modal"><?php echo esc_attr($btn_text); ?></button>
	<?php		
	}
	
}//endif;

if( !function_exists( 'jobboard_ajax_apply_job' ) ){

	add_action( 'wp_ajax_nopriv_jobboard_apply_job', 'jobboard_ajax_apply_job' );
	add_action( 'wp_ajax_jobboard_apply_job', 'jobboard_ajax_apply_job' );
	
	function jobboard_ajax_apply_job(){
		
		$job_id = $_POST['job_id'];
		$applicant = get_current_user_id();
		
		$app_args = array(
			'post_type' 	=> 'application',
			'author'		=> get_current_user_id(),
			'post_status'	=> 'publish',
			'post_title'	=> get_userdata( $applicant )->display_name.' - '.get_the_title( $job_id ),
		);
		
		$app_id = wp_insert_post( $app_args );
		$job_author = get_post($job_id)->post_author;
		
		$email = array(
			'to'	=> get_the_author_meta( 'user_email', $job_author),
			'from'	=> get_the_author_meta( 'user_email', $applicant ),
			'name'	=> get_the_author_meta( 'display_name', $applicant ),
		);
		
		$to = $email['to'];
		$subject = __( 'You\'ve got a new message from', 'jobboard' ).' : '.$email['name'];
		$header = 'From: '.get_bloginfo('name').' <'.get_bloginfo('admin_email').'>';
		$message = $email['name']." has applied for your job opening. Click ".get_permalink($_POST['job_resume'])." to view the resume.";
		
		wp_mail( $to, $subject, $message, $header );
		
		if($app_id){
			
			update_post_meta( $app_id, '_jboard_applied_job', $job_id );
			update_post_meta( $app_id, '_jboard_applicant_resume', $_POST['job_resume'] );
			update_post_meta( $app_id, '_jboard_applicant_name', get_current_user_id() );
			update_post_meta( $app_id, '_jboard_application_status', '' );
			
			$meta_args = array(
				'_jboard_applicant_name',
				'_jboard_applicant_resume',
				'_jboard_applied_job',
				'_jboard_application_status',
				
			);
			update_post_meta( $app_id, 'jobboard_application_mb_fields', $meta_args );
			
			
			
			$email = array(
				
			);
			
			wp_redirect( add_query_arg( 'message', '10', get_permalink($job_id) ) ); exit;
		}//endif;
		
	}
}//endif;


/*------------------------------------------------------------------*/
/*	Function to handle Contact Form Job Seeker	*/
/*------------------------------------------------------------------*/
if( !function_exists( 'jobboard_contact_form_job_seeker' ) ){
	
	add_action( 'wp_ajax_nopriv_jobboard_send_contact_job_seeker', 'jobboard_contact_form_job_seeker' );
	add_action( 'wp_ajax_jobboard_send_contact_job_seeker', 'jobboard_contact_form_job_seeker' );
	
	function jobboard_contact_form_job_seeker(){
		
		$email = array(
			'to'		=> get_userdata( intval($_POST['job_seeker_id']) )->user_email,
			'from'		=> $_POST['contact_email'],
			'name'		=> $_POST['contact_name'],
			'message'	=> $_POST['contact_message'],
		);
		
		$to = $email['to'] ;
		$subject = __( 'You\'ve got a new message from', 'jobboard' ).' : '.$email['name'];
		$headers = 'From: '.get_bloginfo('name').' <'.get_bloginfo('admin_email').'>';
		$message = $email['message'];
		
		wp_mail( $to, $subject, $message, $headers );

	}
	
}//endif;

/*------------------------------------------------------------------*/
/*	Ajax Bookmark Function	*/
/*------------------------------------------------------------------*/
if( !function_exists( 'jobboard_bookmark_resume' ) ){
	
	add_action( 'wp_ajax_nopriv_jobboard_bookmark_the_resume', 'jobboard_bookmark_resume' );
	add_action( 'wp_ajax_jobboard_bookmark_the_resume', 'jobboard_bookmark_resume' );
	function jobboard_bookmark_resume(){
		
		$resume_id = $_POST['resume_id'];
		$user_id = $_POST['user_id'];
		
		// Get the recent bookmarkers ID
		$old_bookmark = get_post_meta( $resume_id, 'jobboard_resume_bookmarker' );
		
		// Explode data into array
		if( !empty( $old_bookmark ) ){
			$bookmark_array = $old_bookmark;
		
			// Check if current user not bookmarked this resume yet
			if( !in_array( $user_id, $bookmark_array ) ){
			
				add_post_meta( $resume_id, 'jobboard_resume_bookmarker', $user_id );
			
			}//endif;
		}else{
			add_post_meta( $resume_id, 'jobboard_resume_bookmarker', $user_id );
		}//endif;
		
	}
}//endif;


/*------------------------------------------------------------------*/
/*	Ajax Change Application Status	*/
/*------------------------------------------------------------------*/
if( !function_exists( 'jobboard_ajax_application_status' ) ){
	
	add_action( 'wp_ajax_nopriv_jobboard_change_application_status', 'jobboard_ajax_application_status' );
	add_action( 'wp_ajax_jobboard_change_application_status', 'jobboard_ajax_application_status' );
	function jobboard_ajax_application_status(){
		
		$app_id = $_POST['app_id'];
		$app_status = $_POST['application_status'];
		$old_app_id = get_post_meta( $app_id, '_jboard_application_status', true );
		if($app_id){
			$check = update_post_meta( $app_id, '_jboard_application_status', $app_status, $old_app_id );
		}//endif;
		
	}
	
}//endif;


/*------------------------------------------------------------------*/
/*	Paypal Payment Integration Mode	*/
/*------------------------------------------------------------------*/
if( !function_exists( 'jobboard_insert_payment' ) ){
	add_action( 'init', 'jobboard_insert_payment' );
	function jobboard_insert_payment(){
		if ( isset( $_GET['action'] ) && $_GET['action'] == 'payment_success' ) {
            $postdata = $_POST;
			$item_number = $postdata['item_number'];
			$amount = $postdata['mc_gross'];
			$payment_status = strtolower( $postdata['payment_status'] );

			//verify payment
			//$verified = validate_ipn();
			$verified = true;
			$custom = json_decode( stripcslashes( $postdata['custom'] ) );
			
			$post_id = $item_number;
			if ( $verified ) {
				$data = array(
					//'user_id' => (int) $custom->user_id,
					'status' => 'completed',
					'cost' => $postdata['mc_gross'],
					'post_id' => $post_id,
					//'pack_id' => $pack_id,
					'payer_first_name' => $postdata['first_name'],
					'payer_last_name' => $postdata['last_name'],
					'payer_email' => $postdata['payer_email'],
					'payment_type' => 'Paypal',
					'payer_address' => $postdata['residence_country'],
					'transaction_id' => $postdata['txn_id'],
					'created' => current_time( 'mysql' )
				);
				add_post_meta( $data['post_id'], 'jobboard_job_payment_status', $data['status'] );
			}
		}
	}
}//endif;

if( !function_exists( 'jobboard_get_payment_mode' ) ){
	function jobboard_get_payment_mode(){
		$sanboxmode = jobboard_option( 'payment_sandbox_mode' )?jobboard_option( 'payment_sandbox_mode' ):'';
		if( $sanboxmode == '1' ){
			$action = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		}else{
			$action = 'https://www.paypal.com/cgi-bin/webscr';
		}
		return $action;
	}
}//endif;

if( !function_exists( 'jobboard_add_extra_social_links' ) ){
	function jobboard_add_extra_social_links( $contactmethods ) {
		// Add Twitter
		if ( !isset( $contactmethods['twitter'] ) ){
			$contactmethods['twitter'] = __( 'Twitter', 'jobboard' );
		}
		
		// Add Facebook
		if ( !isset( $contactmethods['facebook'] ) ){
			$contactmethods['facebook'] = __( 'Facebook', 'jobboard' );
		}
		
		// Add Linked In
		if ( !isset( $contactmethods['linkedin'] ) ){
			$contactmethods['linkedin'] = __( 'Linked In', 'jobboard' );
		}
		
		return $contactmethods;
	}
	add_filter( 'user_contactmethods', 'jobboard_add_extra_social_links', 10, 1 );
}//endif;