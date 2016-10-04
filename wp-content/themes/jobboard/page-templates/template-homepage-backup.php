<?php

/**
 * Template Name: Homepage
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 *
 */

//require_once( get_template_directory().'/includes/frontend-submission/form-submit.php' );
get_header(); ?>

<script>

function get_states(countryid)
{
	jQuery.ajax({
            type: "POST",
            url: "/post.php",
            data: { countryID: countryid}
        }).done(function( msg ) {
			jQuery('#job_state').html(unescape(msg));
			jQuery('#job_state').fadeIn(800);
		});
		
		
}

function get_Cities(stateID)
{
	jQuery.ajax({
            type: "POST",
            url: "/post.php",
            data: { stateID: stateID}
        }).done(function( msg ) {
			jQuery('#job_city').html(unescape(msg));
			jQuery('#job_city').fadeIn(800);
		});
		
		
}
</script>
<?php
	if( jobboard_option('enable_homepage_slider') ){
		get_template_part( 'template-parts/homepage', 'slider' );
	}//endif;
?>

<?php
	get_template_part( 'template-parts/form', 'job_search' );
?>
<div id="post-a-job" class="collapseomatic">
	<div class="container">
		<?php
			if(isset($_GET['required'])){
				$required = $_GET['required'];
			}
			$page_title = __( 'Post Here', 'jobboard' );
			$default = array(
				'post_id' => '',
				'job_title' => '',
				'job_country' => 109,
				'job_state' => '',
				'job_city' => '',
				'job_type' => '',
				'job_category' => '',
				'job_experience' => '',
				'job_sallary' => '',
				'job_summary' => '',
				'job_overview' => '',
				'job_description' => '',
				'company' => '',
				'posting_date' => '',
				
			);
			
			
			
			if( isset( $_GET['action'] ) && $_GET['action'] == 'edit' ){
				$page_title = __( 'EDIT JOB', 'jobboard' );
				$edit = get_post( $_GET['jid'] );
				
				// Get job region
				$job_country = wp_get_post_terms( $edit->ID, 'job_country' );
				$job_state = wp_get_post_terms( $edit->ID, 'job_state' );
				$job_city = wp_get_post_terms( $edit->ID, 'job_city' );
				$job_type = wp_get_post_terms( $edit->ID, 'job_type' );
				$job_category = wp_get_post_terms( $edit->ID, 'job_category' );
				
				$job = array(
					'post_id' => $edit->ID,
					'job_title' => $edit->post_title,
					'job_country' => $job_country[0]->country,
					'job_state' => $job_state[0]->state,
					'job_city' => $job_city[0]->city,
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
<table style="width: 100%;">
<tr>
<td id="testingTour"><i class="fa fa-hand-o-down fa-4x" style="color: #ffffff; float: left; margin-top:25px; margin-right: 10px;"></i><h1 class="page-title">Recent Posts</h1>
<label style="color: red; display: <?php if($required == 1){ echo "block"; } else { echo "none"; }?> ">Please fill all required fields! (*)</label></td>
</tr>
</table>
</div><!-- /.container -->
</div><!-- /#page-title -->

<div id="target-post-a-job" class="collapseomatic_content" style="display:none; margin-left: -16px;">
<div id="content" style="background-color: #f8f8fa; border-bottom: 1px solid #e8eaf1;">
	<div class="container">
		<div class="row" style="margin-left:auto; margin-right:auto; width:80%">
			<div class="col-md-8" style="width: 100%">
				<form id="post-job-form" class="frontend-form" action=""  method="post" entype="multipart/form-data" role="form">
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
						<label for="job_title"><?php _e( 'Training Title', 'jobboard' ); ?>*</label>
						<input type="text" id="job_title" name="job_title" class="form-control" value="<?php echo esc_attr( $default['job_title'] ); ?>" required="required"/>
					</div><!-- /.form-group -->
					<div class="row">					
					<div class="form-group col-sm-6">
                         			<label for="job_country"><?php _e( 'Training Region', 'jobboard' ); ?>*</label>
						<select name="job_country" id="job_country" class="form-control" required="required" onChange="get_states(this.value)">
							<option value=""><?php echo '-- '.__( 'Select Region', 'jobboard' ).' --'; ?></option>
						<?php
							$countries= get_countries();
							foreach( $countries as $country){
								if( $default['job_country'] == $country->country){
									$selected = 'selected';
								}
								echo '<option value="'.$country->id.'" '.$selected.'>'.esc_attr($country->country).'</option>';
							}
						?>
						</select>
						<select name="job_state" id="job_state" class="form-control" style="display:none;" required="required" onChange="get_Cities(this.value)"></select>
							<select name="job_city" id="job_city" class="form-control"  style="display:none;" required="required"></select>
					</div><!-- /.form-group -->

					<div class="form-group col-sm-6">
						<label for="job_experience"><?php _e( 'Training Experience (years)', 'jobboard' ); ?>*</label>
						<!--<span class="form-desc"><?php _e( 'Enter the working experiences requirement for this job.', 'jobboard' ); ?></span>-->
						<input type="text" name="job_experience" id="job_experience" class="form-control" value="<?php echo esc_attr( $default['job_experience'] ); ?>" required="required"/>
					</div><!-- /.form-group -->
					</div>
					
					<div class="row">
						<div class="form-group col-sm-6">
							<label for="job_type"><?php _e( 'Training Type', 'jobboard' ); ?>*</label>
							<select name="job_type" id="job_type" class="form-control" required="required">
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
						<label for="job_sallary"><?php _e( 'Training Rate ($/HR)', 'jobboard' ); ?>*</label>
						<!--<span class="form-desc"><?php _e( 'Enter the a matter of job sallary per year. So your ad can show in job search page.', 'jobboard' ); ?></span>-->
						<input type="text" name="job_sallary" id="job_sallary" class="form-control" value="<?php echo esc_attr( $default['job_sallary'] ); ?>" required="required"/>
					</div><!-- /.form-group -->
						<!--<div class="form-group col-sm-6">
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
						</div>--><!-- /.col-sm-6 -->
					</div><!-- /.row -->
					
					
					
					
					<div class="form-group">
						<label for="job_summary"><?php _e( 'Contact Information', 'jobboard' ); ?>*</label>
						<span class="form-desc"><?php _e( 'Email/Phone Number. (Maximum 40 characters)', 'jobboard' ); ?></span>
						<input type="text" name="job_summary" id="job_summary" class="form-control" maxlength="40" value="" required="required"/>
					</div><!-- /.form-group -->
					
					<div class="form-group">
						<label for="job_overview"><?php _e( 'Training Overview', 'jobboard' ); ?>*</label>
						<span class="form-desc"><?php _e( 'Write something about your training needs/experiences', 'jobboard' ); ?></span>
						<textarea name="job_overview" id="job_overview" class="form-control" rows="3" value="" required="required"><?php echo esc_textarea( $default['job_overview'] ); ?></textarea>
					</div><!-- /.form-group -->
					
					<div class="form-group">
						<label for="job_description"><?php _e( 'Training Description (Optional)', 'jobboard' ); ?></label>
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
						<label for="job_company"><?php _e( 'Post Type', 'jobboard' ); ?>*</label>
						<select name="job_company" id="job_company" class="form-control" required="required">
							<option value=""><?php echo '-- '.__( 'Are You a Trainer Or Athlete?', 'joboard' ).' --' ?></option>
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
					
					<div class="form-group">
						<label>What sound does a cat make? (Rhymes with 'cow')*<?php echo $humanErr; ?></label>
						<input type="text" id="cat_sound" name="cat_sound" class="form-control" value="" required="required"/>
					</div><!-- /.form-group -->
					
					<?php
						if( isset( $_GET['action'] ) && $_GET['action'] == 'edit' ){
							$button_text = __( 'Update Job', 'jobboard' );
					?>
						<input type="hidden" name="form_type" id="form_type" value="edit_post_job" />
						<input type="hidden" name="post_id" id="post_id" value="<?php echo esc_attr( $default['post_id'] ); ?>" />
					<?php
						}else{
							$button_text = __( 'Post!', 'jobboard' );
					?>
						<input type="hidden" name="form_type" id="form_type" value="post_job" />
						<input id='btn' class="btn btn-post-resume" name="submit" type='submit' value='Submit'>
					<?php
						}
					?>
					<input type="hidden" name="user_id" id="user_id" value="<?php echo esc_attr( get_current_user_id() ); ?>" />
					<!--<button type="submit" name="submit" class="btn btn-post-resume"><?php echo esc_attr( $button_text ); ?></button>-->
					
				</form>
			</div><!-- /.col-md-8 -->
			<?php get_sidebar(); ?>
			
		</div><!-- /.row -->
		
	</div><!-- /.container -->
	
</div><!-- /#content -->
</div>
<?php
	get_template_part( 'template-parts/homepage', 'jobs_listing' );
?>

<?php
	if( jobboard_option('enable_job_status') ){
		get_template_part( 'template-parts/homepage', 'job_stats' );
	}//endif;
?>

<?php
	if( jobboard_option('enable_job_steps') ){
		get_template_part( 'template-parts/homepage', 'job_step' );
	}//endif;
?>

<?php
//	if( jobboard_option('enable_testimonial') ){
//		get_template_part( 'template-parts/homepage', 'testimonials' );
//	}//endif;
?>

<?php
//	if( jobboard_option('enable_company') ){
//		get_template_part( 'template-parts/homepage', 'company' );
//	}//endif;
?>

<?php
get_footer();