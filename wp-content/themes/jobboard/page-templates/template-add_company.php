<?php
/**
 * Template Name: Add Company
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 *
 */
?><?php
if( !is_user_logged_in() ){
	$login_redirect = urlencode( get_permalink( get_the_id() ) );
	$redirect_args = add_query_arg( 'redirect', $login_redirect, jobboard_get_permalink( 'login' ) );
	wp_redirect( $redirect_args );
	exit;
}//endif;

if( isset( $_GET['action'] ) && $_GET['action'] == 'edit' ){
	if( !isset( $_GET['jid'] ) || $_GET['jid'] == '' ){
		wp_redirect( get_permalink( get_the_id() ) ); exit;
	}
}//endif;

require_once( get_template_directory().'/includes/frontend-submission/form-submit.php' ); //Include Frontend Submission functions
get_header(); 

?>
<div id="page-title-wrapper">
	<div class="container">
		<?php
			$page_title = __( 'ADD COMPANY', 'jobboard' );
			$default = array(
				'post_id'				=> '',
				'company_name'			=> '',
				'company_description' 	=> '',
				'company_website'		=> '',
				'company_facebook'		=> '',
				'company_twitter'		=> '',
				'company_google_plus'	=> '',
				'company_image'			=> '',
				
			);
			
			
			
			if( isset( $_GET['action'] ) && $_GET['action'] == 'edit' ){
				$page_title = __( 'EDIT COMPANY', 'jobboard' );
				$edit = get_post( $_GET['jid'] );
				
				$company = array(
					'post_id'				=> $_GET['jid']?$_GET['jid']:'',
					'company_name'			=> $edit->post_title,
					'company_description' 	=> get_post_meta( $edit->ID, '_jboard_company_description', true ),
					'company_website'		=> get_post_meta( $edit->ID, '_jboard_company_web_address', true ),
					'company_facebook'		=> get_post_meta( $edit->ID, '_jboard_company_social_facebook', true ),
					'company_twitter'		=> get_post_meta( $edit->ID, '_jboard_company_social_twitter', true ),
					'company_google_plus'	=> get_post_meta( $edit->ID, '_jboard_company_social_googleplus', true ),
					'company_image'			=> get_the_post_thumbnail( $edit->ID ),
				);
				
				$default = wp_parse_args( $company, $default );
			}
		?>
		<h1 class="page-title"><?php echo esc_attr( $page_title ); ?></h1>
	</div><!-- /.container -->
</div><!-- /#page-title -->

<div id="content">
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<form id="post-company-form" class="frontend-form" action="" method="post" enctype="multipart/form-data" role="form">
					<?php
						if( isset( $_GET['message'] ) ){
							jobboard_set_post_message( $_GET['message'] );
						}
					?>
					
					<div class="form-group">
						<label for="company_name"><?php _e( 'Company Name', 'jobboard' ); ?></label>
						<input type="text" id="company_name" name="company_name" class="form-control" value="<?php echo esc_attr( $default['company_name'] ); ?>" required="required" />
					</div><!-- /.form-group -->
					
					<div class="form-group">
						<label for="company_description"><?php _e( 'Company Description', 'jobboard' ); ?></label>
						<span class="form-desc"><?php _e( 'Write something about you company.', 'jobboard' ); ?></span>
						<textarea name="company_description" id="company_description" class="form-control" rows="7" required="required"><?php echo esc_attr( $default['company_description'] ); ?></textarea>
					</div><!-- /.form-group -->
					
					<div class="form-group">
						<label for="company_image"><?php _e( 'Company Image', 'jobboard' ); ?></label>
						<?php
							echo $default['company_image'];
						?>
						<input class="" type="file" name="company_image" id="company_image" accept="image/*" />
					</div><!-- /.form-group -->
					
					<div class="form-group">
						<label for="company_website"><?php _e( 'Website URL', 'jobboard' ); ?></label>
						<input type="text" id="company_website" name="company_website" class="form-control" value="<?php echo esc_attr( $default['company_website'] ); ?>" />
					</div><!-- /.form-group -->
					
					<div class="form-group">
						<label for="company_facebook"><?php _e( 'Facebook', 'jobboard' ); ?></label>
						<input type="text" id="company_facebook" name="company_facebook" class="form-control" value="<?php echo esc_attr( $default['company_facebook'] ); ?>" />
					</div><!-- /.form-group -->
					
					<div class="form-group">
						<label for="company_twitter"><?php _e( 'Twitter', 'jobboard' ); ?></label>
						<input type="text" id="company_twitter" name="company_twitter" class="form-control" value="<?php echo esc_attr( $default['company_twitter'] ); ?>" />
					</div><!-- /.form-group -->
					
					<div class="form-group">
						<label for="company_google_plus"><?php _e( 'Google Plus', 'jobboard' ); ?></label>
						<input type="text" id="company_google_plus" name="company_google_plus" class="form-control" value="<?php echo esc_attr( $default['company_google_plus'] ); ?>" />
					</div><!-- /.form-group -->
					
					
					
					<?php
						if( isset( $_GET['action'] ) && $_GET['action'] == 'edit' ){
							$button_text = __( 'Update Company', 'jobboard' );
					?>
						<input type="hidden" name="form_type" id="form_type" value="edit_post_company" />
						<input type="hidden" name="post_id" id="post_id" value="<?php echo $default['post_id']; ?>" />
					<?php
						}else{
							$button_text = __( 'Add Company', 'jobboard' );
					?>
						<input type="hidden" name="form_type" id="form_type" value="post_company" />
					<?php
						}
					?>
					<input type="hidden" name="user_id" id="user_id" value="<?php echo esc_attr( get_current_user_id() ); ?>" />
					<button type="submit" name="submit" class="btn btn-post-resume" value="1"><?php echo esc_attr( $button_text ); ?></button>
					
				</form>
			</div><!-- /.col-md-8 -->
			
			<?php get_sidebar(); ?>
			
		</div><!-- /.row -->
	</div><!-- /.container -->
</div><!-- /#content -->

<?php
get_footer();
 