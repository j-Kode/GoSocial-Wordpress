<?php
/**
 * Job Board submit action form
 * Use to proccess submitted form from frontend submission like, "Post Resume", "Post Job" etc.
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 */
?><?php
if(isset($_POST['submit'])){
   echo wp_redirect('http://www.google.com');
}
if( isset( $_POST['form_type'] ) ){
	$form_type = $_POST['form_type'];
	switch($form_type){

		case 'post_resume':
			jobboard_post_resume($_POST, $_FILES);
			break;
		
		case 'edit_post_resume';
			jobboard_post_resume( $_POST, $_FILES, true );
			break;
			
		case 'post_job';
// wp_redirect('http://google.com');
		    // $data = $_POST;
			// $job_args = array(
			// 'post_content'		=> $data['job_description'],
			// 'post_title'		=> $data['job_title'],
			// 'post_status'		=> 'publish',
			// 'post_type'			=> 'job',
			// 'post_author'		=> $data['user_id'],
		// );
		
		// $message = '1';
		
		// $job_id = '';
		// if( $update ){
			// $job_args['ID'] = $data['post_id'];
			// $job_args['post_status'] = get_post_status( $data['post_id'] );
			// $message = '2';
		// }
		
		// $job_id = wp_insert_post( $job_args );
		
		// if($job_id){
			// if( isset( $data['job_region'] ) ){
				// wp_set_object_terms( $job_id, $data['job_region'], 'job_region' );
			// }
			// if( isset( $data['job_type'] ) ){
				// wp_set_object_terms( $job_id, $data['job_type'], 'job_type' );
			// }
			// if( isset( $data['job_category'] ) ){
				// wp_set_object_terms( $job_id, $data['job_category'], 'job_category' );
			// }
			
			
			// // Job Company Metabox
			// update_post_meta( $job_id, '_jboard_job_company', $data['job_company'] );
			
			// // Job Experience Metabox
			// update_post_meta( $job_id, '_jboard_job_experiences', $data['job_experience'] );
			
			// // Job Sallary Metabox
			// update_post_meta( $job_id, '_jboard_job_sallary', $data['job_sallary'] );
			
			// // Job Summary Metabox
			// update_post_meta( $job_id, '_jboard_job_summary', $data['job_summary'] );
			
			// // Job Overview Metabox
			// update_post_meta( $job_id, '_jboard_job_overview', $data['job_overview'] );
			
			// // Job metabox data set
			// $job_meta = array(
				// '_jboard_job_company',
				// '_jboard_job_experiences',
				// '_jboard_job_sallary',
				// '_jboard_job_summary',
				// '_jboard_job_overview',
			// );
			// update_post_meta( $job_id, 'jobboard_job_mb_fields', $job_meta );
			
			// //wp_redirect('http://trainersforathletes.com/beta/'.add_query_arg( array( 'action' => 'edit', 'jid' => $job_id, 'message' => $message ) ) );
                        
		// }
		wp_redirect('http://google.com');
			break;
		
		case 'edit_post_job';
			jobboard_post_job( $_POST, true );
			break;
			
		case 'post_company';
			jobboard_post_company( $_POST, $_FILES );
			break;
			
		case 'edit_post_company';
			jobboard_post_company( $_POST, $_FILES, true );
			break;
			
	}//endswitch;
}


	function jobboard_post_job2( $data = array(), $update = false ){
		
		$job_args = array(
			'post_content'		=> $data['job_description'],
			'post_title'		=> $data['job_title'],
			'post_status'		=> 'publish',
			'post_type'			=> 'job',
			'post_author'		=> $data['user_id'],
		);
		
		$message = '1';
		
		$job_id = '';
		if( $update ){
			$job_args['ID'] = $data['post_id'];
			$job_args['post_status'] = get_post_status( $data['post_id'] );
			$message = '2';
		}
		
		$job_id = wp_insert_post( $job_args );
		
		if($job_id){
			if( isset( $data['job_region'] ) ){
				wp_set_object_terms( $job_id, $data['job_region'], 'job_region' );
			}
			if( isset( $data['job_type'] ) ){
				wp_set_object_terms( $job_id, $data['job_type'], 'job_type' );
			}
			if( isset( $data['job_category'] ) ){
				wp_set_object_terms( $job_id, $data['job_category'], 'job_category' );
			}
			
			
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
				'_jboard_job_company',
				'_jboard_job_experiences',
				'_jboard_job_sallary',
				'_jboard_job_summary',
				'_jboard_job_overview',
			);
			update_post_meta( $job_id, 'jobboard_job_mb_fields', $job_meta );
			
			//wp_redirect('http://trainersforathletes.com/beta/'.add_query_arg( array( 'action' => 'edit', 'jid' => $job_id, 'message' => $message ) ) );
                        
			exit;
		}

	}
	
