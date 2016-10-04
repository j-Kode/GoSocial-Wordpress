<?php
/**
 * Template Part Name : Job Seeker Dashboard
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 *
 */
?>
<div id="page-title-wrapper" class="company-account-setting">
	<div class="container">
		<div class="row">
			<div class="col-sm-6">
				<h1 class="page-title"><?php the_title(); ?></h1>
			</div><!-- /.col-sm-6 -->
			<div class="col-sm-6">
				<div class="account-setting-url">
					<a href="#">
						<i class="fa fa-gear"></i>
						<?php _e( 'Account Settings', 'jobboard' ); ?>
					</a>
				</div>
			</div><!-- /.col-sm-6 -->
		</div><!-- /.row -->
		
		<div class="row">
			<div class="col-md-6">
				<div class="account-profile-picture clearfix">
				<?php
					$user_id = get_current_user_id();
					echo get_avatar( $user_id, 150 );
				?>
					<div class="account-profile-info">
					<?php
				
						echo '<h3>'.get_the_author_meta( 'display_name', $user_id ).'</h3>';
						echo '<span>'.__( 'Welcome to your personal Account.', 'jobboard' ).'</span>';
					
					?>
					</div><!-- /.account-profile-info -->
				</div><!-- /.account-profile-picture -->
				
			</div><!-- /.col-md-6 -->
			<div class="col-md-6">
				<div class="account-job-status">
					<div class="account-status-item" id="status-company">
						<span class="count-status-number">
							<?php
								$args = array(
									'post_type' => 'application',
									'posts_per_page' => -1,
									'meta_query'	=> array(
										array(
											'key'	=> '_jboard_applicant_name',
											'value'	=> $user_id,
										),
									),
								);
								$applications = count(get_posts($args));
								echo esc_attr( $applications );
							?>
						</span>
						<span class="count-status-desc"><?php echo __( 'Apply', 'jobboard' ); ?></span>
					</div><!-- /.account-status-item -->
					
					<?php
						$app_args = array(
							'hide_empty'	=> false,
						);
						$app_stats = get_terms( 'application_status', $app_args );
						foreach( $app_stats as $app ){
					?>
					<div id="status-<?php echo $app->slug; ?>" class="account-status-item">
						<span class="count-status-number">
							<?php
								$args = array(
									'post_type' => 'application',
									'posts_per_page' => -1,
									'meta_query'	=> array(
										'relation'	=> 'AND',
										array(
											'key'	=> '_jboard_application_status',
											'value'	=> $app->term_id,
										),
										array(
											'key'	=> '_jboard_applicant_name',
											'value'	=> $user_id,
										),
									),
									
								);
								$applications = count(get_posts($args));
								echo $applications;
								$apps = get_posts( $args );
							?>
						</span>
						<span class="count-status-desc"><?php echo esc_attr( $app->name ); ?></span>
					</div><!-- /.account-status-item -->
					<?php
						}//endforeach;
					?>
					
					
					
				</div><!-- /.account-job-status -->
			</div><!-- /.col-md-6 -->
		</div><!-- /.row -->
	</div><!-- /.container -->
</div><!-- /#page-title -->

<div id="content">
	<div class="container">
		<div class="jobs-listing-title clearfix">
			<h3 class="pull-left"><i class="fa fa-briefcase"></i><?php _e( 'YOUR RESUME', 'jobboard' ); ?></h3>
			<div class="pull-right">
				<a class="add-new-items" href="<?php echo esc_url( jobboard_get_permalink( 'post_resume' ) ); ?>"><i class="fa fa-plus"></i><?php _e( 'ADD RESUME', 'jobboard' ); ?></a>
			</div>
		</div>
		<div class="company-listing-wrapper">
		<?php
			$resume_args = array(
				'post_type' 		=> 'resume',
				'posts_per_page'	=> 2,
				'paged'				=> $paged,
				'author'			=> $user_id,
				'post_status'		=> array( 'publish', 'pending' ),
			);
			
			$resume = new WP_Query($resume_args);
			
			if( $resume->have_posts() ){
				
				while( $resume->have_posts() ){
					$resume->the_post();
					
					$resume_id = get_the_ID();
					
				?>
			<div id="list-item-<?php echo $resume_id; ?>" class="job-list-item clearfix">				
				<div class="resume-list-title">
					<div class="resume-list-title-wrapper">
						<h4><?php echo esc_attr( get_the_title() ); ?></h4>
						<span><?php echo vp_metabox('jobboard_resume_mb.resume_professional_title'); ?></span>
					</div><!-- /.job-list-title-wrapper -->
				</div><!-- /.resume-list-title -->
				
				<div class="resume-list-category">
					<?php
						$resume_taxs = get_the_terms( $resume_id, 'resume_category' );
						if($resume_taxs){
							foreach( $resume_taxs as $resume_tax ){
								echo esc_attr( $resume_tax->name ); 
							}//endforeach;
						}//endif;
					?>
				</div><!-- /.resume-list-category -->
				
				<div class="job-list-date resume-list-date">
					<i class="fa fa-calendar"></i>
				<?php
					$publish_date = get_the_date( 'F j, Y', $resume_id );
					echo jobboard_time_ago( $publish_date ).'&nbsp;'.__( 'ago', 'jobboard' );
				?>
				</div><!-- /.job-list-date -->
				
				<div class="resume-list-status">
				<?php
					$post_status = get_post_status($resume_id);
					if( $post_status == 'pending' ){
						_e( 'Pending Review', 'jobboard' );
					}
					if( $post_status == 'publish' ){
						_e( 'Published', 'jobboard' );
					}
				?>
				</div><!-- /.resume-list-status -->
				
				<div class="resume-list-action">
					<form action="<?php echo esc_url( admin_url('admin-ajax.php') ); ?>" method="post" class="jobboard_delete_item">
						<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'edit', 'jid' => $resume_id ), esc_url( jobboard_get_permalink( 'post_resume' ) ) ) ); ?>"><i class="fa fa-edit"></i> <?php _e( 'Edit', 'jobboard' ); ?></a> |
						<a href="<?php echo esc_url( get_permalink(esc_attr( $resume_id )) ); ?>" target="_blank"><i class="fa fa-eye"></i> <?php _e( 'View', 'jobboard' ); ?></a> |
						<button type="submit" class="btn btn-list-delete" name="submit" value="1" /><i class="fa fa-trash-o"></i> <?php _e( 'Delete', 'jobboard' ); ?></button>
						<input type="hidden" name="action" value="jobboard_delete_post_item" />
						<input type="hidden" name="post_id" value="<?php echo esc_attr( $resume_id ); ?>" />
					</form>
				</div><!-- /.resume-list-action -->
				
				
			</div><!-- /.job-list-item -->
				<?php
					
				}//endwhile;
				
				wp_reset_postdata();
			}else{
			
			}//endif;
		?>
		</div><!-- /.company-listing-wrapper -->
		
		<div class="jobs-listing-title clearfix">
			<h3><i class="fa fa-briefcase"></i><?php _e( 'YOUR JOB APPLICATION', 'jobboard' ); ?></h3>
		</div>
		<div class="company-listing-wrapper">
		<?php
            
			$jobs_args = array(
				'post_type' => 'application',
				'posts_per_page' => 2,
				'paged'		=> $paged,
				'meta_query'	=> array(
					array(
						'key'	=> '_jboard_applicant_name',
						'value'	=> $user_id,
					),
				),
			);
			
			$jobs = new WP_Query( $jobs_args );
			
			while( $jobs->have_posts() ){
				$jobs->the_post();
				$job_id = get_post_meta( get_the_id(), '_jboard_applied_job', true );
				$company_id = get_post_meta( $job_id, '_jboard_job_company', true );
				$company = get_post($company_id);
				$app_status_id = get_post_meta( get_the_id(), '_jboard_application_status', true );
				$app_stats = get_term_by( 'id', $app_status_id, 'application_status' );
				
			?>
			
			<div id="list-item-<?php echo $job_id; ?>" class="job-list-item clearfix">
				<div class="company-list-logo">
				<?php
					if( has_post_thumbnail( $company_id ) ){
						echo get_the_post_thumbnail( $company_id, 'jobboard-company-logo-thumbnail' );
					}//endif;	
				?>
				</div><!-- /.company-list-logo -->
				
				<div class="job-list-title">
					<div class="job-list-title-wrapper">
						<h4><?php echo esc_attr( get_the_title( $job_id ) ); ?></h4>
						<span><?php echo get_post_meta( $job_id, '_jboard_job_summary', true ); ?></span>
					</div><!-- /.job-list-title-wrapper -->
				</div><!-- /.job-list-title -->
				
				<div class="job-listing-region">
					<i class="fa fa-fw fa-map-marker"></i>
					<?php
						$job_taxs = get_the_terms( $job_id, 'job_region' );
						if($job_taxs){
							foreach( $job_taxs as $job_tax ){
								echo esc_attr( $job_tax->name );
							}//endforeach;
						}//endif;
					?>
				</div><!-- /.job-listing-region -->
				<div class="job-listing-type">
					<i class="fa fa-fw fa-user"></i>
					<?php
						$job_taxs = get_the_terms( $job_id, 'job_type' );
						if($job_taxs){
							foreach( $job_taxs as $job_tax ){
								echo esc_attr( $job_tax->name );
							}//endforeach;
						}//endif;
					?>
				</div><!-- /.job-listing-type -->
				
				<div class="job-list-date">
					<i class="fa fa-calendar"></i>
				<?php
					$publish_date = get_the_date( 'F j, Y', $job_id );
					echo jobboard_time_ago( $publish_date ).'&nbsp;'.__( 'ago', 'jobboard' );
				?>
				</div><!-- /.job-list-date -->
				
				<div class="job-application-status">
				<?php
					if($app_stats){
				?>
						<span class="application-status_<?php echo $app_stats->slug.'_'.$app_stats->term_id; ?>"><?php echo esc_attr( $app_stats->name ); ?></span>
				<?php
					}else{
				?>
						<span class="application-status_waiting"><?php _e( 'Waiting', 'jobboard' );  ?></span>
				<?php
					}//endif;
				?>
				</div><!-- /.job-application-status -->
				
				
			</div><!-- /.company-list-item -->
			
			<?php
			}//endwhile;
			
			$pag_args = array(
                'total'   => $jobs->max_num_pages,
               
                'prev_text'    => __( 'Previous', 'jobboard' ),
				'next_text'    => __( 'Next', 'jobboard' ),
            );
           
            echo '<div class="dashboard-pagination">';
            echo esc_url( paginate_links( $pag_args ) );
            echo '</div><!-- /.dashboard-pagination -->';
            
            wp_reset_postdata();
		?>	
		</div><!-- /.company-listing-wrapper -->
	</div><!-- /.container -->
</div><!-- /#content -->