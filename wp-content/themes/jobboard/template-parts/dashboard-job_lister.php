<?php
/**
 * Template Part Name : Job Lister Dashboard
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
			<div class="col-sm-6 hidden-xs">
				<div class="account-setting-url">
					<a href="<?php echo esc_url( jobboard_get_permalink('profile') ); ?>">
						<i class="fa fa-gear"></i>
						<?php _e( 'Account Settings', 'jobboard' ); ?>
					</a>
				</div>
			</div><!-- /.col-sm-6 -->
		</div><!-- /.row -->
		
		<div class="row">
			<div class="col-md-6">
				<div class="account-profile-picture clearfix">
					<span>
						<?php
							$user_id = get_current_user_id();
							echo get_avatar( $user_id, 150 );
						?>
					</span>
					<div class="account-profile-info">
					<?php
				
						echo '<h3>'.get_the_author_meta( 'display_name', $user_id ).'</h3>';
						echo '<span>'.__( 'Welcome to your personal Account.', 'jobboard' ).'</span>';
					
					?>
					</div><!-- /.account-profile-info -->
				</div><!-- /.account-profile-picture -->
				
			</div><!-- /.col-md-6 -->
			<div class="col-md-6 hidden-xs">
				<div class="account-job-status">
					<div class="account-status-item" id="status-company">
						<span class="count-status-number">
							<?php
								$args = array(
									'post_type' => 'company',
									'author'	=> $user_id,
									'posts_per_page' => -1,
								);
								$companies = count(get_posts($args));
								echo esc_attr( $companies );
							?>
						</span>
						<span class="count-status-desc"><?php echo _n( 'Company', 'Companies', $companies, 'jobboard' ); ?></span>
					</div><!-- /.account-status-item -->
					
					<div class="account-status-item" id="status-posted-job">
						<span class="count-status-number">
							<?php
								$args = array(
									'post_type' => 'job',
									'author'	=> $user_id,
									'posts_per_page' => -1,
								);
								$companies = count(get_posts($args));
								echo esc_attr( $companies );
							?>
						</span>
						<span class="count-status-desc"><?php echo _n( 'Posted Job', 'Posted Jobs', $companies, 'jobboard' ); ?></span>
					</div><!-- /.account-status-item -->
					
					<div class="account-status-item" id="status-closed-job">
						<span class="count-status-number">
							<?php
								$args = array(
									'post_type' => 'job',
									'author'	=> $user_id,
									'posts_per_page' => -1,
									'meta_query' => array(
										array(
											'key' => '_jboard_job_status',
											'value'	=> 'closed',
										),
									),
								);
								$companies = count(get_posts($args));
								echo esc_attr( $companies );
							?>
						</span>
						<span class="count-status-desc"><?php echo _n( 'Closed Job', 'Closed Jobs', $companies, 'jobboard' ); ?></span>
					</div><!-- /.account-status-item -->
					
					<div class="account-status-item" id="status-applicant-job">
						<span class="count-status-number">
							<?php
								$number = '0';
								$args = array(
									'post_type' => 'job',
									'author'	=> $user_id,
									'posts_per_page' => -1,
								);
								$companies = get_posts($args);
								foreach( $companies as $item ){
									
									$app = array(
										'post_type'	=> 'application',
										'posts_per_page' => -1,
										'meta_query' => array(
											array(
												'key'	=> '_jboard_applied_job',
												'value'	=> $item->ID
											),
										),
									);
									$result = get_posts($app);
									
									$number = $number + count($result);
								}
								echo esc_attr( $number );
							?>
						</span>
						<span class="count-status-desc"><?php echo _n( 'Applicant', 'Applicants', $companies, 'jobboard' ); ?></span>
					</div><!-- /.account-status-item -->
					
				</div><!-- /.account-job-status -->
			</div><!-- /.col-md-6 -->
		</div><!-- /.row -->
	</div><!-- /.container -->
</div><!-- /#page-title -->

<div id="content">
	<div class="container">
		<div class="jobs-listing-title clearfix">
			<h3 class="pull-left"><i class="fa fa-briefcase"></i><?php _e( 'YOUR COMPANIES', 'jobboard' ); ?></h3>
			<div class="pull-right">
				<a class="add-new-items" href="<?php echo  esc_url( jobboard_get_permalink( 'post_company' ) ); ?>"><i class="fa fa-plus"></i><?php _e( 'Add Company', 'jobboard' ); ?></a>
			</div><!-- /.pull-right -->
		</div>
		<div id="company-list" class="company-listing-wrapper">
		<?php
			$company_paged = isset( $_GET['company_paged'] ) ? (int) $_GET['company_paged'] : 1;
            $job_paged = isset( $_GET['job_paged'] ) ? (int) $_GET['job_paged'] : 1;
            $app_paged = isset( $_GET['app_paged'] ) ? (int) $_GET['app_paged']:1;
            $bookmark_paged = isset( $_GET['bookmark_paged'] ) ? (int) $_GET['bookmark_paged'] : 1;
            
			$user_id = get_current_user_id();
			
			$comps_args = array(
				'post_type'			=> 'company',
				'posts_per_page'	=> 10,
				'paged'				=> $company_paged,
				'author'			=> $user_id,
			);
			
			$comps = new WP_Query( $comps_args );
			while( $comps->have_posts() ){
				$comps->the_post();
				$com_id = get_the_id();
			?>
			
			<div id="list-item-<?php echo $com_id; ?>" class="company-list-item clearfix">
				<div class="company-list-logo">
				<?php
					if( has_post_thumbnail( $com_id ) ){
						echo get_the_post_thumbnail( $com_id, 'jobboard-company-logo-thumbnail' );
					}//endif;	
				?>
				</div><!-- /.company-list-logo -->
				
				<div class="company-list-name">
				<?php echo esc_attr( get_the_title( $com_id ) ); ?>
				</div><!-- /.company-list-name -->
				
				<div class="company-list-date">
					<i class="fa fa-calendar"></i>
				<?php
					$publish_date = get_the_date( 'F j, Y', $com_id );
					echo jobboard_time_ago( $publish_date ).'&nbsp;'.__( 'ago', 'jobboard' );
				?>
				</div><!-- /.company-list-date -->
				
				<div class="company-list-edit">
					<i class="fa fa-edit hidden-sm"></i>
					<a class="hidden-sm" href="<?php echo esc_url( get_permalink().'?action=edit&jid='.$com_id ); ?>"><?php _e( 'Edit', 'jobboard' ); ?></a>
					<a class="visible-sm-inline" href="<?php echo esc_url( get_permalink().'?action=edit&jid='.$com_id ); ?>" title="<?php _e( 'Edit', 'jobboard' ); ?>"><i class="fa fa-edit"></i></a>
					
				</div><!-- /.company-list-edit -->
				
				<div class="company-list-delete">
					<form class="jobboard_delete_item" method="post" action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" data-post-id="<?php echo $com_id; ?>">
						<button class="btn btn-list-delete hidden-sm" type="submit" name="submit"><i class="fa fa-trash-o"></i> <?php _e( 'Delete', 'jobboard' ); ?></button>
						<button class="btn btn-list-delete visible-sm-inline-block" type="submit" name="submit" title="<?php _e( 'Delete', 'jobboard' ); ?>"><i class="fa fa-trash-o"></i></button>
						<input type="hidden" name="post_id" value="<?php echo esc_attr( $com_id ); ?>" />
						<input type="hidden" name="action" value="jobboard_delete_post_item" />
					</form>
				</div><!-- /.company-list-delete -->
				
				
				<div class="company-list-view">
					<i class="fa fa-eye  hidden-sm"></i>
					<a class="hidden-sm" target="_blank" href="<?php echo esc_url( get_permalink( get_the_id() ) ); ?>"><?php _e( 'View', 'jobboard' ); ?></a>
					<a class="visible-sm-inline" target="_blank" href="<?php echo esc_url( get_permalink( get_the_id() ) ); ?>" title="<?php _e( 'View', 'jobboard' ); ?>"><i class="fa fa-eye"></i></a>
				</div><!-- /.company-list-delete -->
			</div><!-- /.company-list-item -->
			
			<?php
			}//while;
			
			$pag_args1 = array(
                'format'  => '?company_paged=%#%',
                'current' => $company_paged,
                'total'   => $comps->max_num_pages,
                'add_args' => array( 'job_paged' => $job_paged, 'bookmark_paged' => $bookmark_paged, 'app_paged' => $app_paged ),
                'prev_text'    => __( 'Previous', 'jobboard' ),
				'next_text'    => __( 'Next', 'jobboard' ),
            );
            echo '<div class="dashboard-pagination">';
            echo esc_url( paginate_links( $pag_args1 ) );
            echo '</div><!-- /.dashboard-pagination -->';
            
             wp_reset_postdata();
		?>	
		</div><!-- /.company-listing-wrapper -->
		
		<div class="jobs-listing-title clearfix">
			<h3 class="pull-left"><i class="fa fa-briefcase"></i><?php _e( 'POSTED JOBS', 'jobboard' ); ?></h3>
			<div class="pull-right">
				<a class="add-new-items" href="<?php echo esc_url( jobboard_get_permalink( 'post_job' ) ); ?>"><i class="fa fa-plus"></i><?php _e( 'Post a Job', 'jobboard' ); ?></a>
			</div><!-- /.pull-right -->
		</div>
		<div id="job-list" class="company-listing-wrapper">
		<?php
			
			$jobs_args = array(
				'post_type' => 'job',
				'posts_per_page' => 10,
				'paged'		=> $job_paged,
				'author'	=> $user_id,
				'post_status' => array( 'publish', 'pending', 'draft' ),
			);
			
			$jobs = new WP_Query( $jobs_args );
			
			while( $jobs->have_posts() ){
				$jobs->the_post();
				$job_id = get_the_id();
			?>
			
			<div id="list-item-<?php echo $job_id; ?>" class="job-list-item clearfix">
				<div class="company-list-logo visible-lg">
				<?php
					$comp_id = get_post_meta( $job_id, '_jboard_job_company', true );
					if( has_post_thumbnail( $comp_id ) ){
						echo get_the_post_thumbnail( $comp_id, 'jobboard-company-logo-thumbnail' );
					}//endif;	
				?>
				</div><!-- /.company-list-logo -->
				
				<div class="job-list-title">
					<div class="job-list-title-wrapper">
						<h4><?php echo esc_attr( get_the_title( $job_id ) ); ?></h4>
						<span><?php echo get_post_meta( $job_id, '_jboard_job_summary', true ); ?></span>
					</div><!-- /.job-list-title-wrapper -->
				</div><!-- /.job-list-title -->
				
				<div class="job-list-date">
					<i class="fa fa-calendar hidden-md hidden-sm"></i>
				<?php
					$publish_date = get_the_date( 'F j, Y', $job_id );
					echo jobboard_time_ago( $publish_date ).'&nbsp;'.__( 'ago', 'jobboard' );
				?>
				</div><!-- /.job-list-date -->
				
				<div class="job-list-status">
				<?php
					$post_status = get_post_status( $job_id );
					
					$payment_stat = get_post_meta( get_the_id(), 'jobboard_job_payment_status', true );
					if( $payment_stat == 'complete' ){
						echo '<i class="fa fa-circle job-list-status-'.esc_attr( $post_status ).'"></i>';
						echo esc_attr( $post_status );
					}else{
						$action = '';
						if( '1' == jobboard_option( 'activate_payment' ) && get_post_status() != 'trash' ){
					
							if( $payment_stat == 'completed' ){
								echo esc_attr( $post_status );
							}else{
								$action = jobboard_get_payment_mode();
								$custom = json_encode( array( 'user_id' => get_current_user_id() ) );
								$listener_url = add_query_arg( 'action', 'payment_success', home_url( '/' ) );
								$return_url = add_query_arg( 'action', 'payment_success', jobboard_get_permalink('dashboard') );
								?>
								<form id="paypal_approval" name="paypal_approval" action="<?php echo esc_url($action); ?>" method="POST" class="payment-button">
									<input type="hidden" name="cmd" value="_xclick" />
									<input type="hidden" name="amount" value="<?php echo esc_attr( jobboard_option( 'cost_per_post' ) ); ?>" />
									<input type="hidden" name="business" value="<?php echo jobboard_option( 'paypal_email' );  ?>" />
									<input type="hidden" name="item_name" value="<?php echo __( 'Job Posting - ', 'jobboard' ).esc_attr( get_the_title() ); ?>" />
									<input type="hidden" name="item_number" value="<?php echo esc_attr( get_the_id() );  ?>" />
									<input type="hidden" name="no_shipping" value="1" />
									<input type="hidden" name="no_note" value="1" />
									<input type="hidden" name="currency_code" value="<?php echo jobboard_option( 'payment_currency' ); ?>" />
									<input type="hidden" name="charset" value="UTF-8" />
									<input type="hidden" name="custom" value="<?php echo esc_attr( $custom ); ?>" />
									<input type="hidden" name="rm" value="2" />
									<input type="hidden" name="cbt" value="<?php echo sprintf( __( 'Click here to complete the purchase on %s', 'jobboard' ), esc_attr( get_bloginfo( 'name' ) ) ) ?>" />
									<input type="hidden" name="return" value="<?php echo esc_url( $return_url ); ?>" />
									<input type="hidden" name="notify_url" value="<?php echo esc_url( $listener_url ); ?>" />
									<button type="submit" name="paynow" class="btn btn-paypal"><?php _e( 'Pay Now', 'jobboard' ); ?></button>
								</form>

						<?php
							}//endif;
							
						}else{
							echo '<i class="fa fa-circle job-list-status-'.esc_attr( $post_status ).'"></i>'.esc_attr( $post_status );
						}//endif;//endif;
					}//endif;
				?>
				</div><!-- /.job-list-status -->
				
				<div class="job-list-edit">
					<i class="fa fa-edit hidden-sm"></i>
					<?php
						$edit_permalink = add_query_arg( array( 'action' => 'edit', 'jid' => $job_id ), jobboard_get_permalink( 'post_job' ) );
					?>
					<a class="hidden-sm" href="<?php echo esc_url( $edit_permalink ); ?>"><?php _e( 'Edit', 'jobboard' ); ?></a>
					<a class="visible-sm-inline" href="<?php echo esc_url( $edit_permalink ); ?>" title="<?php _e( 'Edit', 'jobboard' ); ?>"><i class="fa fa-edit"></i></a>
				</div><!-- /.job-list-edit -->
				
				<div class="job-list-delete">
					<form class="jobboard_delete_item" method="post" action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" data-post-id="<?php echo esc_attr( $job_id ); ?>">
						<button class="btn btn-list-delete hidden-sm" type="submit" name="submit"><i class="fa fa-trash-o"></i> <?php _e( 'Delete', 'jobboard' ); ?></button>
						<button class="btn btn-list-delete visible-sm-inline" type="submit" name="submit" title="<?php _e( 'Delete', 'jobboard' ); ?>"><i class="fa fa-trash-o"></i></button>
						<input type="hidden" name="post_id" value="<?php echo esc_attr( $job_id ); ?>" />
						<input type="hidden" name="action" value="jobboard_delete_post_item" />
					</form>
				</div><!-- /.job-list-delete -->
				
				<div class="job-list-featured">
					<?php
						$featured = get_post_meta( $job_id, '_jboard_job_featured', true );
						if( $featured == 0 ){
					?>
					<form class="jobboard_featured_item" method="post" action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
						<button class="btn btn-list-featured" type="submit" name="submit"><i class="fa fa-star"></i> <?php _e( 'Mark As Featured', 'jobboard' ); ?></button>
						<input type="hidden" name="post_id" value="<?php echo esc_attr( $job_id ); ?>" />
						<input type="hidden" name="action" value="jobboard_add_featured_post" />
					</form>
					<?php
						}else{
					?>
					<i class="fa fa-check"></i> <?php _e( 'Featured', 'jobboard' ); ?>
					<?php
						}//endif;
					?>
				</div><!-- /.job-list-featured -->
			</div><!-- /.company-list-item -->
			
			<?php
			}//endforeach;
			
			$pag_args2 = array(
                'format'  => '?job_paged=%#%',
                'current' => $job_paged,
                'total'   => $jobs->max_num_pages,
                'add_args' => array( 'company_paged' => $company_paged, 'bookmark_paged' => $bookmark_paged, 'app_paged' => $app_paged ),
                'prev_text'    => __( 'Previous', 'jobboard' ),
				'next_text'    => __( 'Next', 'jobboard' ),
            );
           
            echo '<div class="dashboard-pagination">';
            echo esc_url( paginate_links( $pag_args2 ) );
            echo '</div><!-- /.dashboard-pagination -->';
            
            wp_reset_postdata();
		?>	
		</div><!-- /.company-listing-wrapper -->
		
		<div class="jobs-listing-title clearfix">
			<h3 class="pull-left"><i class="fa fa-briefcase"></i><?php _e( 'APPLICANTS', 'jobboard' ); ?></h3>
		</div>
		<div id="applicant-list" class="company-listing-wrapper">
		<?php
		
			$job_args = array(
				'post_type'			=> 'job',
				'author'			=> get_current_user_id(),
				'posts_per_page'	=> -1,
			);
			$jobs = new WP_Query($job_args);
			$resume_id = array();
			if($jobs->have_posts()){
				while( $jobs->have_posts() ){
					$jobs->the_post();
					
					$resume_id[] = get_the_id();
					
				}//endwhile;
				
				wp_reset_postdata();
			}//endif;
			
			$meta_query = array();
			
			foreach( $resume_id as $res_id ){
				
				$meta_query[] = array(
					'key'	=> '_jboard_applied_job',
					'value'	=> $res_id,
				);
				
			}//endforeach;
			$meta_query['relation'] = 'OR';
			$app_args = array();			
			if( $jobs->have_posts() ){
				$app_args = array(
					'post_type' 		=> 'application',
					'posts_per_page'	=> 10,
					'paged'				=> $app_paged,
					'meta_query'		=> $meta_query,
				);
			}
			
			$app = new WP_Query($app_args);
			
			if( $app->have_posts() ){
				while( $app->have_posts() ){
					$app->the_post();
					
				?>
			<div id="list-item-<?php echo get_the_id(); ?>" class="job-list-item clearfix">				
				<div class="application-list-title">
					<div class="applicant-list-title-wrapper">
						<h4>
						<?php
							$userid = get_post_meta( get_the_id(), '_jboard_applicant_name', true );
							echo esc_attr( get_userdata($userid)->display_name );
						?>
						</h4>
						<span><?php echo vp_metabox('jobboard_resume_mb.resume_professional_title', null, esc_attr( get_post_meta( get_the_id(), '_jboard_applicant_resume', true ) ) ); ?></span>
					</div><!-- /.job-list-title-wrapper -->
				</div><!-- /.resume-list-title -->
				
				<div class="application-job">
				<?php
					echo esc_attr( get_the_title( get_post_meta( get_the_id(), '_jboard_applied_job', true ) ) );
				?>
				</div><!-- /.resume-list-category -->
				
				<div class="job-list-date resume-list-date hidden-sm">
					<i class="fa fa-calendar"></i>
				<?php
					$publish_date = get_the_date( 'F j, Y', get_the_id() );
					echo jobboard_time_ago( $publish_date ).'&nbsp;'.__( 'ago', 'jobboard' );
				?>
				</div><!-- /.job-list-date -->
				
				<div class="resume-list-status application">
					<form id="application_form_<?php echo get_the_id(); ?>" class="application_status_form" method="post" action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
						<select id="application_status" name="application_status" class="application_status form-control">
					<?php
						$status_id = get_post_meta( get_the_id(), '_jboard_application_status', true );
					
						$terms = get_terms( 'application_status', array( 'hide_empty' => false ) );
						foreach( $terms as $term ){
							$selected = '';
							if( $status_id == $term->term_id ){
								$selected = 'selected';
							}
							echo '<option value="'.esc_attr( $term->term_id ).'" '.esc_attr( $selected ).'>'.esc_attr( $term->name ).'</option>';
						}//endforeach;
					?>
							<input type="hidden" name="action" value="jobboard_change_application_status" />
							<input type="hidden" name="app_id" value="<?php echo esc_attr( get_the_id() ); ?>" />
						</select>
					</form>
				</div><!-- /.resume-list-status -->
				
				<div class="resume-list-action">
					<a class="hidden-sm" href="<?php echo esc_url( get_permalink(get_post_meta( get_the_id(), '_jboard_applicant_resume', true )) ); ?>" target="_blank"><i class="fa fa-eye"></i> <?php _e( 'View Resume', 'jobboard' ); ?></a>
					<a class="hidden-lg hidden-md" href="<?php echo esc_url( get_permalink(get_post_meta( get_the_id(), '_jboard_applicant_resume', true )) ); ?>" target="_blank" title="<?php _e( 'View Resume', 'jobboard' ); ?>"><i class="fa fa-eye"></i></a>
				</div><!-- /.resume-list-action -->
				
				
			</div><!-- /.job-list-item -->
				<?php
					
				}//endwhile;
				
				wp_reset_postdata();
			}else{
			
			}//endif;
			$pag_args4 = array(
                'format'  => '?app_paged=%#%',
                'current' => $app_paged,
                'total'   => $app->max_num_pages,
                'add_args' => array( 'job_paged' => $job_paged, 'bookmark_paged' => $bookmark_paged, 'company_paged' => $company_paged ),
                'prev_text'    => __( 'Previous', 'jobboard' ),
				'next_text'    => __( 'Next', 'jobboard' ),
            );
            echo '<div class="dashboard-pagination">';
            echo esc_url( paginate_links( $pag_args4 ) );
            echo '</div><!-- /.dashboard-pagination -->';
		?>
			
		</div><!-- /.company-listing-wrapper -->
		
		
		<div class="jobs-listing-title">
			<h3><i class="fa fa-briefcase"></i><?php _e( 'RESUME BOOKMARKS', 'jobboard' ); ?>
		</div><!-- /.jobs-listing-title -->
		<div id="resume-list" class="company-listing-wrapper">
		<?php
			$args = array(
				'post_type'			=> 'resume',
				'posts_per_page'	=> 10,
				'paged'				=> $company_paged,
				'meta_query'		=> array(
					array(
						'key'		=> 'jobboard_resume_bookmarker',
						'value'		=> get_current_user_id(),
						'compare'	=> 'IN',
					),
				),
				
			);
			
			$bookmarks = new WP_Query($args);
			
			if( $bookmarks->have_posts()){
				
				while($bookmarks->have_posts()){
					$bookmarks->the_post();
					$resume_id = get_the_id();
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
				
					<div class="job-list-date resume-list-date hidden-sm hidden-xs">
						<i class="fa fa-calendar"></i>
					<?php
						$publish_date = get_the_date( 'F j, Y', $resume_id );
						echo jobboard_time_ago( $publish_date ).'&nbsp;'.__( 'ago', 'jobboard' );
					?>
					</div><!-- /.job-list-date -->
				
					<div class="resume-list-status hidden-sm hidden-xs">
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
						<a href="<?php echo esc_url( get_permalink($resume_id) ); ?>" target="_blank"><i class="fa fa-eye"></i> <?php _e( 'View', 'jobboard' ); ?></a>
					</div><!-- /.resume-list-action -->
				
				
				</div><!-- /.job-list-item -->
				<?php
					
				}//endwhile;
				
				$pag_args3 = array(
					'format'  => '?bookmark_paged=%#%',
					'current' => $bookmark_paged,
					'total'   => $bookmarks->max_num_pages,
					'add_args' => array( 'job_paged' => $job_paged, 'company_paged' => $company_paged, 'app_paged' => $app_paged ),
					'prev_text'    => __( 'Previous', 'jobboard' ),
					'next_text'    => __( 'Next', 'jobboard' ),
				);
		   
				echo '<div class="dashboard-pagination">';
				echo esc_url( paginate_links( $pag_args3 ) );
				echo '</div><!-- /.dashboard-pagination -->';
            
				wp_reset_postdata();
			}//endif;
		?>
		</div><!-- /.company-listing-wrapper -->
	</div><!-- /.container -->
</div><!-- /#content -->