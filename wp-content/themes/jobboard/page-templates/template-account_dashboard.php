<?php
/**
 * Template Name: Account Dashboard
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 *
 */
?>
<?php get_header(); ?>

<?php
	if ( is_user_logged_in() ){
	
		$user_id = get_current_user_id();
		$user_type = get_user_meta( $user_id, 'jobboard_user_role', true );
	
		if( $user_type == 'job_seeker'|| current_user_can( 'edit_user' ) ){
			get_template_part( 'template-parts/dashboard', 'job_seeker' );
		}
		if( $user_type == 'job_lister' || current_user_can( 'edit_user' ) ){
			get_template_part( 'template-parts/dashboard', 'job_lister' );
		}
		
	}else{
		
		jobboard_forbidden( 'login' );
		
	}//endif;
?>

<?php get_footer(); ?>