<?php
/**
 * Template Part Name : Top Job Opening
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 *
 */
?>

<div id="featured-job">
	<div class="container">
		<div class="jobs-listing-title">
			<h3><?php echo apply_filters( 'jobboard_featured_job_title', __( 'TOP JOB OPENING', 'jobboard' ) ); ?></h3>
		</div>
		
		<div class="clearfix featured-job-wrapper">
		<?php
			$args = array(
				'post_type' => 'job',
				'posts_per_page' => -1,
				'meta_query' => array(
					array(
						'value'	=> 1,
						'key' => '_jboard_job_featured',
					),
				),
			);
			
			$jobs = get_posts( $args );
			foreach( $jobs as $job ){
		?>
				<div class="featured-job-item">
					<div class="featured-job-thumbnail">
					<?php
						$comp_id = get_post_meta( $job->ID, '_jboard_job_company', true );
						if( has_post_thumbnail( $comp_id ) ){
							echo get_the_post_thumbnail( $comp_id, 'jobboard-featured-job-thumbnail' );
						}
					?>
					</div><!-- /.featured-job-thumbnail -->
					<div class="featured-job-detail">
						<div class="featured-job-title"><?php echo esc_attr( $job->post_title ); ?></div>
						<div class="featured-job-desc"><?php echo get_post_meta( $job->ID, '_jboard_job_summary', true ); ?></div>
						<a href="<?php echo esc_url( get_permalink( $job->ID) ); ?>" class="btn btn-view-featured-job"><?php _e( 'View Job', 'jobboard' ); ?></a>
					</div><!-- /.featured-job-detail -->
					<div class="featured-job-type clearfix">
							<div class="featured-job-location">
								<i class="fa fa-map-marker"></i>
								<?php
									$job_taxs = get_the_terms( $job->ID, 'job_region' );
									if($job_taxs){
										foreach( $job_taxs as $job_tax ){
											echo esc_attr( $job_tax->name );
										}
									}
								?>
							</div><!-- featured-job-location -->
							<div class="featured-job-contract">
								<i class="fa fa-fw fa-user"></i>
								<?php
									$job_taxs = get_the_terms( $job->ID, 'job_type' );
									if($job_taxs){
										foreach( $job_taxs as $job_tax ){
											echo esc_attr( $job_tax->name );
										}
									}
								?>
							</div><!-- /.featured-job-contract -->
						</div><!-- /.featured-job-type -->
				</div><!-- /.featured-job-item -->
		<?php
			}//endforeach;
		?>
		</div><!-- /.row -->
	</div><!-- /.container -->
</div><!-- /#featured-job -->
