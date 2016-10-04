<?php
/**
 * Template Name: Post a Resume
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 *
 */

ob_start();
if( !is_user_logged_in() ){
	$login_redirect = urlencode( get_permalink( get_the_id() ) );
	$redirect_args = add_query_arg( 'redirect', $login_redirect, jobboard_get_permalink( 'login' ) );
	wp_redirect( $redirect_args );
	exit;
}//endif;

if( isset( $_GET['action'] ) && $_GET['action'] == 'edit' ){
	if( !isset( $_GET['jid'] ) || $_GET['jid'] == '' ){
		wp_redirect( get_permalink( get_the_id() ) ); exit;
	}else{
		$post_resume = get_post($_GET['jid']);
		if( $post_resume->post_type != 'resume' ){
			wp_redirect( get_permalink( get_the_id() ) ); exit;
		}
	}//endif;
}//endif;
ob_end_clean();
require_once( get_template_directory().'/includes/frontend-submission/form-submit.php' ); //Include Frontend Submission functions
get_header(); 
?>
<div id="page-title-wrapper">
	<div class="container">
		<?php
			$page_title = __( 'POST A RESUME', 'jobboard' );
			$default = array(
				'resume_id'			=> '',
				'resume_title'		=> '',
				'resume_job_title'	=> '',
				'resume_location'	=> '',
				'resume_photo'		=> '',
				'resume_category'	=> '',
				'resume_content'	=> '',
				'resume_skills'		=> '',
				'resume_url'		=> array(),
				'resume_education'	=> array(),
				'resume_experience'	=> array(),
				'resume_file'		=> '',
			);
			
			if( isset( $_GET['action'] ) && $_GET['action'] == 'edit' ){
				$page_title = __( 'EDIT RESUME', 'jobboard' );
				$resume_id = $_GET['jid'];
				
				$resume = get_post($resume_id);
				
				$resume_category = wp_get_post_terms( $resume_id, 'resume_category' );
				
				$input_value = array(
					'resume_id'			=> $resume_id,
					'resume_title'		=> $resume->post_title,
					'resume_job_title'	=> vp_metabox( 'jobboard_resume_mb.resume_professional_title', null, $resume_id ),
					'resume_location'	=> vp_metabox( 'jobboard_resume_mb.resume_location', null, $resume_id ),
					'resume_photo'		=> get_the_post_thumbnail( $resume_id ),
					'resume_category'	=> $resume_category[0]->slug,
					'resume_content'	=> $resume->post_content,
					'resume_skills'		=> vp_metabox( 'jobboard_resume_mb.skills_group.0.resume_skills', null, $resume_id ),
				);
				
				$default = wp_parse_args( $input_value, $default );
			}//endif;
		?>
		<h1 class="page-title"><?php echo esc_attr( $page_title ); ?></h1>
	</div><!-- /.container -->
</div><!-- /#page-title -->
<div id="content">
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<form method="post" class="frontend-form" action="" id="post-resume" role="form" enctype="multipart/form-data">
					<?php
						$status_message = '';
						
						if( isset( $_GET['message'] ) ){
							$status_message = $_GET['message'];
						}
						jobboard_set_post_message( $status_message );
					?>
					
					<div class="form-group">
						<label for="name"><?php _e( 'Resume Title', 'jobboard' ); ?></label>
						<input class="form-control" type="text" name="title" id="title" value="<?php echo esc_attr( $default['resume_title'] ); ?>" />
					</div><!-- /.form-group -->
					
					<!-- <div class="form-group">
						<label for="email"><?php _e( 'Your Email', 'jobboard' ); ?></label>
						<input class="form-control" type="email" name="email" id="email" value="<?php echo $default['resume_job_title']; ?>" />
					</div> --> <!-- /.form-group -->
					
					<div class="form-group">
						<label for="title"><?php _e( 'Professional Title', 'jobboard' ); ?></label>
						<input class="form-control" type="text" name="job_title" id="job_title" value="<?php echo esc_attr( $default['resume_job_title'] ); ?>" />
					</div><!-- /.form-group -->
					
					<div class="form-group">
						<label for="name"><?php _e( 'Location', 'jobboard' ); ?></label>
						<input class="form-control" type="text" name="location" id="location" value="<?php echo $default['resume_location']; ?>" />
					</div><!-- /.form-group -->
					
					<div class="form-group">
						<label for="photo"><?php _e( 'Photo (optional)', 'jobboard' ); ?></label>
						<?php
							if( !empty( $default['resume_photo'] ) ){
								echo $default['resume_photo'];
							}
						?>
						<input class="" type="file" name="photo" id="photo" accept="image/*" />
					</div><!-- /.form-group -->
					
					<div class="form-group">
						<label for="category"><?php _e( 'Resume Category', 'jobboard' ); ?></label>
						<select class="form-control" name="category">
							<option value=""><?php _e( 'Select Category', 'jobboard' ); ?></option>
						<?php
							$terms = get_terms( array( 'resume_category' ), array( 'hide_empty' => false ) );
							foreach( $terms as $term ){
								$selected = '';
								if( $term->slug == $default['resume_category'] ){
									$selected = 'selected="selected"';
								}//endif;
								echo '<option value="'.$term->slug.'" '.$selected.'>'.esc_attr($term->name).'</option>';
							}
						?>
						</select>
					</div><!-- /.form-group -->
					
					<div class="form-group">
						<label for="content"><?php _e( 'Resume Content', 'jobboard' ); ?></label>
						<!-- <textarea name="content" class="form-control" rows="6"></textarea> -->
						<?php
							
							$editor_id = 'resume_content';
							$editor_css = '
							<style>
								.wp-editor-container{
									border: 1px solid #e5e5e5;
									-webkit-box-shadow: 0 1px 1px rgba(0,0,0,0.04);
									box-shadow: 0 1px 1px rgba(0,0,0,0.04);
								}
								.wp-switch-editor,
								.tmce-active .switch-tmce, .html-active .switch-html{
									height:25px;
								}
							</style>';
							wp_editor( $default['resume_content'], $editor_id, array( 'editor_class' => 'form-control', 'media_buttons' => false, 'editor_css' => $editor_css,  ) );
							
						?>
					</div><!-- /.form-group -->
					
					<div class="form-group">
						<label for="skills"><?php _e( 'Skills (optional)', 'jobboard' ); ?></label>
						<input class="form-control" type="text" name="skills" id="skills" placeholder="<?php _e( 'Use comma to separate', 'jobboard' ); ?>" value="<?php echo esc_attr( $default['resume_skills'] ); ?>" />
					</div><!-- /.forn-group -->
					
					<?php
						// URL form, only executed if edit mode is active
						if( isset( $_GET['action']) && $_GET['action'] == 'edit' ){
							$resume_url = vp_metabox( 'jobboard_resume_mb.url_group_container.0', null, $resume_id );
							
							foreach( $resume_url['url_group'] as $url ){
							?>
								<div class="repeated-form" style="display:block">
									<div class="close-form" data-button-limit="6" data-button-name="#add_url_button"><i class="fa fa-times"></i></div>
									<div class="row">
										<div class="col-md-5 form-group">
											<label for="url_name"><?php _e( 'Name', 'jobboard' ); ?></label>
											<input class="form-control" type="text" name="url_name[]" value="<?php echo esc_attr( $url['url_name'] ); ?>" required="required" />
										</div>
										<div class="col-md-7 form-group">
											<label for="url_address"><?php _e( 'URL', 'jobboard' ); ?></label>
											<input class="form-control" type="text" name="url_address[]" value="<?php echo esc_attr( $url['url_address'] ); ?>" required="required" />
										</div>
									</div><!-- /.row -->
								</div><!-- /.repeated-form -->
							<?php
							}//endforeach;
						}//endif;
					?>
					
					<div id="url_form" class="repeated-form">
						<div class="close-form" data-button-limit="6" data-button-name="#add_url_button"><i class="fa fa-times"></i></div>
						<div class="row">
							<div class="col-md-5 form-group">
								<label for="url_name"><?php _e( 'Name', 'jobboard' ); ?></label>
								<input class="form-control" type="text" name="url_name[]" />
							</div>
							<div class="col-md-7 form-group">
								<label for="url_address"><?php _e( 'URL', 'jobboard' ); ?></label>
								<input class="form-control" type="text" name="url_address[]" />
							</div>
						</div><!-- /.row -->
					</div><!-- /.repeated-form -->
					
					<div class="form-group">
						<label for="url"><?php _e( 'URL(S)', 'jobboard' ); ?></label>
						<button type="button" id="add_url_button" class="btn btn-add-url" data-limit="5" data-form-id="#url_form" ><?php _e( '+ Add URL', 'jobboard' ); ?></button>
					</div><!-- /.form-group -->
					
					<?php
						// Education form looping, only show if edit mode activated
						if( isset( $_GET['action'] ) && $_GET['action'] == 'edit' ){
							
							$resume_education = vp_metabox( 'jobboard_resume_mb.education_group_container.0', null, $resume_id );
							foreach( $resume_education['education_group'] as $education ){
							?>
								<div class="repeated-form" style="display:block">
									<div class="close-form" data-button-name="add_education_button"><i class="fa fa-times"></i></div>
									<div class="row">
										<div class="form-group col-md-6">
											<label for="education_period"><?php _e( 'Education Period', 'jobboard' ); ?></label>
											<input type="text"  class="form-control" name="education_period[]" value="<?php echo esc_attr( $education['education_period'] ); ?>" />
										</div><!-- /.form-group -->
										<div class="form-group col-md-6">
											<label for="education_grade"><?php _e( 'Grade/GPA', 'jobboard' ); ?></label>
											<input type="text" class="form-control" name="education_grade[]" value="<?php echo esc_attr( $education['grade'] ); ?>"/>
										</div><!-- /.form-group -->
									</div><!-- /.row -->
						
									<div class="form-group">
										<label for="education_name"><?php _e( 'Institution Name', 'jobboard' ); ?></label>
										<input type="text" class="form-control" name="education_name[]" value="<?php echo esc_attr( $education['institution_name'] ); ?>"/>
									</div><!-- /.form-group -->
									<div class="form-group">
										<label for="education_qualification"><?php _e( 'Qualification(s)', 'jobboard' ) ?></label>
										<input type="text" class="form-control" name="education_qualification[]" value="<?php echo esc_attr( $education['qualification'] ); ?>"/>
									</div><!-- /.form-group -->
									<div class="form-group">
										<label for="education_study"><?php _e( 'Field of Study', 'jobboard' ); ?></label>
										<input type="text" class="form-control" name="education_study[]" value="<?php echo esc_attr( $education['study_field'] ); ?>" />
									</div><!-- /.form-group -->
								</div><!-- /#education_form" -->
							<?php
							}//endforeach;
							
						}//endif;
					?>
					<div id="education_form" class="repeated-form">
						<div class="close-form" data-button-name="add_education_button"><i class="fa fa-times"></i></div>
						<div class="row">
							<div class="form-group col-md-6">
								<label for="education_period"><?php _e( 'Education Period', 'jobboard' ); ?></label>
								<input type="text"  class="form-control" name="education_period[]" />
							</div><!-- /.form-group -->
							<div class="form-group col-md-6">
								<label for="education_grade"><?php _e( 'Grade/GPA', 'jobboard' ); ?></label>
								<input type="text" class="form-control" name="education_grade[]" />
							</div><!-- /.form-group -->
						</div><!-- /.row -->
						
						<div class="form-group">
							<label for="education_name"><?php _e( 'Institution Name', 'jobboard' ); ?></label>
							<input type="text" class="form-control" name="education_name[]" />
						</div><!-- /.form-group -->
						<div class="form-group">
							<label for="education_qualification"><?php _e( 'Qualification(s)', 'jobboard' ) ?></label>
							<input type="text" class="form-control" name="education_qualification[]" />
						</div><!-- /.form-group -->
						<div class="form-group">
							<label for="education_study"><?php _e( 'Field of Study', 'jobboard' ); ?></label>
							<input type="text" class="form-control" name="education_study[]" />
						</div><!-- /.form-group -->
					</div><!-- /#education_form" -->
					
					<div class="form-group">
						<label for="education"><?php _e( 'Education', 'jobboard' ); ?></label>
						<button type="button" id="add_education_button" class="btn btn-add-url" data-form-id="#education_form"><?php _e( '+ Add Education', 'jobboard' ); ?></button>
					</div><!-- /.form-group -->
					
					<?php
						// Experience form looping, only show when edit mode activated
						if( isset($_GET['action']) && $_GET['action'] == 'edit' ){
							$resume_experience = vp_metabox( 'jobboard_resume_mb.experience_group_container.0', null, $resume_id );
							foreach( $resume_experience['experience_group'] as $exp ){
						
							?>
								<div class="repeated-form" style="display:block">
									<div class="close-form" data-button-name="add_experience_button"><i class="fa fa-times"></i></div>
									<div class="row">
										<div class="form-group col-md-6">
											<label for="experience_period"><?php _e( 'Employment Period', 'jobboard' ); ?></label>
											<input type="text" class="form-control" name="experience_period[]" value="<?php echo esc_attr( $exp['employment_period'] ); ?>" />
										</div><!-- /.form-group -->
										<div class="form-group col-md-6">
											<label for="experience_sallary"><?php _e( 'Yearly Sallary', 'jobboard' ); ?></label>
											<input type="text" class="form-control" name="experience_sallary[]" value="<?php echo esc_attr( $exp['sallary'] ); ?>" />
										</div><!-- /.form-group -->
									</div><!-- /.row -->
									<div class="row">
										<div class="form-group col-md-6">
											<label for="experience_company"><?php _e( 'Company Name', 'jobboard' ); ?></label>
											<input type="text" class="form-control" name="experience_company[]" value="<?php echo esc_attr( $exp['company_name'] ); ?>" />
										</div><!-- /.form-group -->
										<div class="form-group col-md-6">
											<label for="experience_position"><?php _e( 'Position', 'jobboard' ); ?></label>
											<input type="text" class="form-control" name="experience_position[]" value="<?php echo esc_attr( $exp['position'] ); ?>" />
										</div><!-- /.form-group -->
									</div><!-- /.row -->
									<div class="form-group">
										<label for="experience_job"><?php _e( 'Job Duties', 'jobboard' ); ?></label>
										<textarea name="experience_job[]" class="form-control" rows="6"><?php echo esc_attr( $exp['job_duties'] ); ?></textarea>
									</div><!-- /.form-group -->
								</div><!-- /.experience_form -->
							<?php	
							}//endforeach;
						}//endif;
					?>
					
					<div id="experience_form" class="repeated-form">
						<div class="close-form" data-button-name="add_experience_button"><i class="fa fa-times"></i></div>
						<div class="row">
							<div class="form-group col-md-6">
								<label for="experience_period"><?php _e( 'Employment Period', 'jobboard' ); ?></label>
								<input type="text" class="form-control" name="experience_period[]" />
							</div><!-- /.form-group -->
							<div class="form-group col-md-6">
								<label for="experience_sallary"><?php _e( 'Yearly Sallary', 'jobboard' ); ?></label>
								<input type="text" class="form-control" name="experience_sallary[]" />
							</div><!-- /.form-group -->
						</div><!-- /.row -->
						<div class="row">
							<div class="form-group col-md-6">
								<label for="experience_company"><?php _e( 'Company Name', 'jobboard' ); ?></label>
								<input type="text" class="form-control" name="experience_company[]" />
							</div><!-- /.form-group -->
							<div class="form-group col-md-6">
								<label for="experience_position"><?php _e( 'Position', 'jobboard' ); ?></label>
								<input type="text" class="form-control" name="experience_position[]" />
							</div><!-- /.form-group -->
						</div><!-- /.row -->
						<div class="form-group">
							<label for="experience_job"><?php _e( 'Job Duties', 'jobboard' ); ?></label>
							<textarea name="experience_job[]" class="form-control" rows="6"></textarea>
						</div><!-- /.form-group -->
					</div><!-- /.experience_form -->
					<div class="form-group">
						<label for="experience"><?php _e( 'Experience', 'jobboard' ); ?></label>
						<button type="button" id="add_experience_button" class="btn btn-add-url" data-form-id="#experience_form"><?php _e( '+ Add Experience', 'jobboard' ); ?></button>
					</div><!-- /.form-group -->
					
					<div class="form-group">
						<label for="resume_file"><?php _e( 'Resume File (optional)', 'jobboard' ); ?></label>
						<?php
							if( isset($_GET['action']) && $_GET['action'] == 'edit' ){
								$file = get_post_meta( $resume_id, 'jobboard_resume_file', true );
								echo '
									<div class="alert alert-success"><strong>'.basename($file).'</strong></div>
								';
							}//endif;
						?>
						<input class="" type="file" name="resume_file" id="resume_file" accept="image/*,application/pdf,application/msword" />
						<span class="help-block"><?php _e( 'Optionally upload your resume for employers to view', 'jobboard' ); ?></span>
					</div><!-- /.form-group -->
					<?php
					if( isset( $_GET['action'] ) && $_GET['action'] == 'edit' ){
					?>
					<input type="hidden" name="form_type" id="form_type" value="edit_post_resume" />
					<input type="hidden" name="user_id" id="user_id" value="<?php echo esc_attr( $resume->post_author ); ?>" />
					<input type="hidden" name="resume_id" id="resume_id" value="<?php echo esc_attr( $resume_id ); ?>" />
					<button type="submit" name="submit" class="btn btn-post-resume"><?php _e( 'Update Resume', 'jobboard' ); ?></button>
					<?php
					}else{
					?>
					<input type="hidden" name="form_type" id="form_type" value="post_resume" />
					<input type="hidden" name="user_id" id="user_id" value="<?php echo esc_attr( get_current_user_id() ); ?>" />
					<button type="submit" name="submit" class="btn btn-post-resume"><?php _e( 'Post a Resume', 'jobboard' ); ?></button>
					
					<?php
					}//endif;
					?>
				</form><!-- /#post-resume -->
			</div><!-- /.col-md-8 -->
		
			<?php get_sidebar(); ?>
		</div><!-- /.row -->
	</div><!-- /.container -->
</div><!-- /#content -->

<?php
get_footer();