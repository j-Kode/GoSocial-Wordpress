<?php
/**
 * Template Name: Post a Job
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 *
 */
?><?php


//get_header(); 

?>
<?php echo get_template_directory().'/includes/frontend-submission/form-submit.php' ?>
<div id="page-title-wrapper" class="collapseomatic">

	<div class="container">
		<?php
			$page_title = __( 'POST A JOB', 'jobboard' );
			$default = array(
				'post_id' => '',
				'job_title' => '',
				'job_region' => '',
				'job_type' => '',
				'job_category' => '',
				'job_experience' => '',
				'job_sallary' => '',
				'job_summary' => '',
				'job_overview' => '',
				'job_description' => '',
				'company' => '',
				
			);
			
			
			
			if( isset( $_GET['action'] ) && $_GET['action'] == 'edit' ){
				$page_title = __( 'EDIT JOB', 'jobboard' );
				$edit = get_post( $_GET['jid'] );
				
				// Get job region
				$job_region = wp_get_post_terms( $edit->ID, 'job_region' );
				$job_type = wp_get_post_terms( $edit->ID, 'job_type' );
				$job_category = wp_get_post_terms( $edit->ID, 'job_category' );
				
				$job = array(
					'post_id' => $edit->ID,
					'job_title' => $edit->post_title,
					'job_region' => $job_region[0]->slug,
					'job_type' => $job_type[0]->slug,
					'job_category' => $job_category[0]->slug,
					'job_experience' => get_post_meta( $edit->ID, '_jboard_job_experiences', true ),
					'job_sallary' => get_post_meta( $edit->ID, '_jboard_job_sallary', true ),
					'job_summary' => get_post_meta( $edit->ID, '_jboard_job_summary', true ),
					'job_overview' => get_post_meta( $edit->ID, '_jboard_job_overview', true ),
					'job_description' => $edit->post_content,
					'company' => get_post_meta( $edit->ID, '_jboard_job_company', true ),
				);
				
				$default = wp_parse_args( $job, $default );
			}
		?>
		<h1 class="page-title"><?php echo esc_attr( $page_title ); ?></h1>
	</div><!-- /.container -->
</div><!-- /#page-title -->
</div>

<div id="target-page-title-wrapper" class="collapseomatic_content">
<div id="content">
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<form id="post-job-form" class="frontend-form" action="" method="post" entype="multipart/form-data" role="form">
					<?php
						$status_message = '';
						
						if( isset( $_GET['message'] ) ){
							$status_message = $_GET['message'];
						}
						
						// check if user have post a company
						$check = get_posts( array( 'post_type' => 'company', 'author' => get_current_user_id() ) );
						if( $check == null ){	
							$status_message = '6';
						}//endif;
						
						jobboard_set_post_message( $status_message );
					?>
					
					<div class="form-group">
						<label for="job_title"><?php _e( 'Job Title', 'jobboard' ); ?></label>
						<input type="text" id="job_title" name="job_title" class="form-control" value="<?php echo esc_attr( $default['job_title'] ); ?>" />
					</div><!-- /.form-group -->
					
					<div class="form-group">
						<label for="job_region"><?php _e( 'Job Region', 'jobboard' ); ?></label>
						<select name="job_region" id="job_region" class="form-control">
							<option><?php echo '-- '.__( 'Select Region', 'jobboard' ).' --'; ?></option>
						<?php
							$terms = get_terms( 'job_region', array( 'hide_empty' => false, ) );
							foreach( $terms as $term ){
								$selected = '';
								if( $default['job_region'] == $term->slug ){
									$selected = 'selected';
								}
								echo '<option value="'.$term->slug.'" '.$selected.'>'.esc_attr($term->name).'</option>';
							}
						?>
						</select>
					</div><!-- /.form-group -->
					
					<div class="row">
						<div class="form-group col-sm-6">
							<label for="job_type"><?php _e( 'Job Type', 'jobboard' ); ?></label>
							<select name="job_type" id="job_type" class="form-control">
								<option value=""><?php echo '-- '.__( 'Select Type', 'jobboard' ).' --'; ?></option>
							<?php
								$terms = get_terms( 'job_type', array( 'hide_empty' => false, ) );
								foreach( $terms as $term ){
									$selected = '';
									if( $default['job_type'] == $term->slug ){
										$selected = 'selected';
									}
									echo '<option value="'.$term->slug.'" '.$selected.'>'.esc_attr($term->name).'</option>';
								}
							?>
							</select>
						</div><!-- /.col-sm-6 -->
						<div class="form-group col-sm-6">
							<label for="job_category"><?php _e( 'Job Category', 'jobboard' ); ?></label>
							<select name="job_category" id="job_category" class="form-control">
								<option value=""><?php echo '-- '.__( 'Select Category', 'jobboard' ).' --'; ?></option>
							<?php
								$terms = get_terms( 'job_category', array( 'hide_empty' => false, ) );
								foreach( $terms as $term ){
									$selected = '';
									if( $default['job_category'] == $term->slug ){
										$selected = 'selected';
									}
									echo '<option value="'.$term->slug.'" '.$selected.'>'.esc_attr($term->name).'</option>';
								}
							?>
							</select>
						</div><!-- /.col-sm-6 -->
					</div><!-- /.row -->
					
					<div class="form-group">
						<label for="job_experience"><?php _e( 'Experience (year)', 'jobboard' ); ?></label>
						<span class="form-desc"><?php _e( 'Enter the working experiences requirement for this job.', 'jobboard' ); ?></span>
						<input type="text" name="job_experience" id="job_experience" class="form-control" value="<?php echo esc_attr( $default['job_experience'] ); ?>" />
					</div><!-- /.form-group -->
					
					<div class="form-group">
						<label for="job_sallary"><?php _e( 'Sallary', 'jobboard' ); ?></label>
						<span class="form-desc"><?php _e( 'Enter the a matter of job sallary per year. So your ad can show in job search page.', 'jobboard' ); ?></span>
						<input type="text" name="job_sallary" id="job_sallary" class="form-control" value="<?php echo esc_attr( $default['job_sallary'] ); ?>" />
					</div><!-- /.form-group -->
					
					<div class="form-group">
						<label for="job_summary"><?php _e( 'Job Summary', 'jobboard' ); ?></label>
						<span class="form-desc"><?php _e( 'Attract relevant job seekers to read further. (Maximum 55 characters)', 'jobboard' ); ?></span>
						<input type="text" name="job_summary" id="job_summary" class="form-control" value="<?php echo esc_attr( $default['job_summary'] ); ?>" />
					</div><!-- /.form-group -->
					
					<div class="form-group">
						<label for="job_overview"><?php _e( 'Job Short Overview', 'jobboard' ); ?></label>
						<span class="form-desc"><?php _e( 'Write something about the job.', 'jobboard' ); ?></span>
						<textarea name="job_overview" id="job_overview" class="form-control" rows="7"><?php echo esc_textarea( $default['job_overview'] ); ?></textarea>
					</div><!-- /.form-group -->
					
					<div class="form-group">
						<label for="job_description"><?php _e( 'Job Description', 'jobboard' ); ?></label>
						<?php
							
							$editor_id = 'job_description';
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
							wp_editor( $default['job_description'], $editor_id, array( 'rows' => '4', 'editor_class' => 'form-control', 'media_buttons' => false, 'editor_css' => $editor_css,  ) );
							
						?>
					</div><!-- /.form-group -->
					
					<div class="form-group">
						<label for="job_company"><?php _e( 'Company', 'jobboard' ); ?></label>
						<select name="job_company" id="job_company" class="form-control">
							<option value=""><?php echo '-- '.__( 'Select Company', 'joboard' ).' --' ?></option>
							<?php
								$args = array(
									'post_type' => 'company',
									'author'	=> get_current_user_id(),
									'posts_per_page' => -1,
								);
								$comps = get_posts($args);
								foreach( $comps as $comp ){
									$selected = '';
									if( $default['company'] == $comp->ID ){
										$selected = 'selected';
									}
									echo '<option value="'.$comp->ID.'" '.$selected.'>'.esc_attr($comp->post_title).'</option>';
								}
								
							?>
						</select>
					</div><!-- /.form-group -->
					
					<?php
						if( isset( $_GET['action'] ) && $_GET['action'] == 'edit' ){
							$button_text = __( 'Update Job', 'jobboard' );
					?>
						<input type="hidden" name="form_type" id="form_type" value="edit_post_job" />
						<input type="hidden" name="post_id" id="post_id" value="<?php echo esc_attr( $default['post_id'] ); ?>" />
					<?php
						}else{
							$button_text = __( 'Post A Job', 'jobboard' );
					?>
						<input type="submit" name="form_type" id="form_type" value="post_job" />
					<?php
						}
					?>
					<input type="hidden" name="user_id" id="user_id" value="<?php echo esc_attr( get_current_user_id() ); ?>" />
					<button type="submit" name="submit" class="btn btn-post-resume"><?php echo esc_attr( $button_text ); ?></button>
					
				</form>
			</div><!-- /.col-md-8 -->
			
			<?php get_sidebar(); ?>
			
		</div><!-- /.row -->
	</div><!-- /.container -->
</div><!-- /#content -->
</div>

<?php require_once( get_template_directory().'/includes/frontend-submission/form-submit.php' ); //Include Frontend Submission functions ?>
 