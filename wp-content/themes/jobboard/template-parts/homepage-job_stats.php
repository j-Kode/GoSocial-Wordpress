<?php
/**
 * Template Part Name : Job Stats
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 *
 */
?>

<div id="job-stats">
	<div class="container">
		<h1 class="job-stats-title">Athlete Posting Updates</h1>
		<p class="job-stats-desc">
			<?php echo esc_attr( jobboard_option('job_status_description') ); ?>
		</p>
		<div class="job-stats-wrapper row">
			<div class="col-lg-2 col-lg-offset-2 col-sm-3">
				<div class="count-box">
				<?php
					$job['jobs'] = wp_count_posts( 'job' );
					echo $job['jobs']->publish;
				?>
				</div><!-- /.count-box -->
				<div class="count-text">
				<?php
					echo apply_filters( 'jobboard_job_posted_text', _n( 'Job Posted', 'Athletes Posted', $job['jobs']->publish, 'jobboard' ) );
				?>
				</div><!-- /.count-text -->
			</div> <!-- /.col-lg-2 col-lg-offset-2 col-md-3" -->
			
			<div class="col-lg-2 col-sm-3">
				<div class="count-box">
				<?php
					$job['comp'] = wp_count_posts( 'company' );
					echo esc_attr( $job['comp']->publish );
				?>
				</div><!-- /.count-box -->
				<div class="count-text">
				<?php
					echo apply_filters( 'jobboard_job_company_text', _n( 'Company', 'Companies', $job['comp']->publish, 'jobboard' ) );
				?>
				</div><!-- /.count-text -->
			</div><!-- /.col-lg-2 col-sm-3 -->
			<div class="col-lg-2 col-sm-3">
				<div class="count-box">
				<?php
					$job_user = count_users();
					echo esc_attr( $job_user['total_users'] );
					
				?>
				</div><!-- /.count-box -->
				<div class="count-text">
				<?php
					echo apply_filters( 'jobboard_job_member_text', _n( 'Member', 'Official Trainers', $job_user['total_users'], 'jobboard' ) );
				?>
				</div><!-- /.count-text -->
			</div><!-- /.col-lg-2 col-sm-3 -->
		</div><!-- /.job-stats-wrapper -->
	</div><!-- /.container -->
</div><!-- /#job-stats -->