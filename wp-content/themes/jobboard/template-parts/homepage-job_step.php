<?php
/**
 * Template Part Name : Job Step
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 *
 */
?>

<div id="job-step">
	<div class="container">
		<h1 class="job-step-title">
		<?php echo apply_filters( 'jobboard_job_step_title', jobboard_option('job_steps_title') ); ?>
		</h1>
		<p class="job-step-desc">
			<?php echo esc_attr( jobboard_option('job_steps_description') ); ?>
		</p>
	</div><!-- /.container -->
</div><!-- /#job-step -->