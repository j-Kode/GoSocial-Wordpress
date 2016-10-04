<?php
/**
 * Template Part Name : Apply job modal
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 *
 */
?>

<!-- Modal Apply Job -->
<div class="modal fade" id="apply-job-modal" tabindex="-1" role="dialog" aria-labelledby="jobboard-modal-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php _e( 'Close', 'jobboard' ); ?></span></button>
				<h4 class="modal-title" id="jobboard_apply_job"><?php echo esc_attr( get_the_title() ); ?> - <?php echo esc_attr( get_the_title( get_post_meta( get_the_id(), '_jboard_job_company', true ) ) ); ?></h4>
			</div><!-- /.modal-header -->
			
			<div class="modal-body">
			<?php
				if( is_user_logged_in() ){
					$args = array(
						'post_type'	=> 'resume',
						'author'	=> get_current_user_id(),
					);
					
					$has_resume = '';
					$resumes = new WP_Query($args);
					if( $resumes->have_posts() ){
						$has_resume = true;
					}else{
						$has_resume = false;
					}//endif;
					
				}//endif;
				
				if( is_user_logged_in() && $has_resume ){
			?>
				<form id="jobboard_apply_job" class="form-horizontal" method="post" action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" role="form">
					<div class="form-group">
						<label for="job_resume" class="control-label col-sm-4"><?php _e( 'Select your resume', 'jobboard' ) ?></label>
						<div class="col-sm-8">
							<select name="job_resume" class="form-control">
							<?php
								$args = array(
									'post_type' => 'resume',
									'author'	=> get_current_user_id(),
								);
								$resumes = get_posts( $args );
								foreach( $resumes as $resume ){
									echo '<option value="'.$resume->ID.'">'.esc_attr( $resume->post_title ).'</option>';
								}
							?>
							</select>
						</div><!-- /.col-sm-8 -->
					</div><!-- /.form-group -->
					<div class="form-group">
						
						<div class="col-sm-8 col-sm-offset-4">
							<input type="hidden" name="action" value="jobboard_apply_job" />
							<input type="hidden" name="job_id" value="<?php echo esc_attr( get_the_id() ); ?>" />
						
							<button type="button" class="btn btn-apply-cancel" data-toggle="modal" data-target="#apply-job-modal"><?php _e( 'Cancel', 'jobboard' ); ?></button>
							<button type="submit" name="submit" class="btn btn-apply-submit"><?php _e( 'Apply Now', 'jobboard' ); ?></button>
						</div><!-- /.col-sm-8 -->
					</div><!-- /.form-group -->
				</form>
			<?php
				}else{
			?>
				<div class="alert alert-warning" role="alert">
					<?php
						if( is_user_logged_in() ){
							echo __( 'You need to create resume first to apply this job. Click', 'jobboard' ).' <a href="'.esc_url( jobboard_get_permalink( 'post_resume' ) ).'">'.__( 'Here', 'jobboard' ).'</a> '.'to add new resume.';
						}else{
							echo __( 'You need to signed in to apply the job. Click', 'jobboard' ).' <a href="'.add_query_arg( 'redirect', urlencode( get_permalink( get_the_id() ) ), esc_url( jobboard_get_permalink( 'login' ) ) ).'">'.__( 'Here', 'jobboard' ).'</a> '.'to sign in.';
						}
					?>
				</div>
			<?php
				}
			?>
			</div><!-- /.modal-body -->
			
			<div class="modal-footer">
			
			</div><!-- /.modal-footer -->
		</div><!-- ./modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal fade -->
<!-- /.Modal Apply Job -->