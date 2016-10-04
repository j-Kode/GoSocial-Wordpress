<?php
/**
 * Template Part Name : Homepage Job Listing
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 *
 */
?>
<div id="resumes-listing" class="in-homepage">
	<div class="container">
		<div class="row">
			<div class="col-lg-8">
				<div class="resumes-listing-title">
					<h3><i class="fa fa-briefcase"></i><?php _e( 'Recent Resumes', 'jobboard' ); ?></h3>
				</div>
				<div class="resumes-listing-wrapper">
					<div id="resume-listing-tabs">
						<ul>
						<?php
							echo '<li><a href="#all_resumes">'.__( 'All', 'jobboard' ).'</a></li>';
							$job_types = get_terms('resume_categories');
							foreach( $job_types as $job_type ){
								echo '<li><a href="#'.$job_type->slug.'-'.$job_type->term_id.'">'.esc_attr( $job_type->name ).'</a></li>';
							}
						?>
						</ul>
						<div id="all_resumes">
						<?php
							$job_args = array( 'post_type' => 'resume' );
							$jobs = new WP_Query($job_args);
							while( $jobs-> have_posts() ){
								$jobs->the_post();
						?>
							<a class="resume-listing-permalink" href="<?php echo esc_url( get_permalink() ); ?>">
								<div class="resume-listing-row clearfix">
									<div class="resume-company-logo">
									<?php
										$comp_id = get_post_meta( get_the_id(), '_jboard_job_company', true );
										echo get_the_post_thumbnail( $comp_id , 'jobboard-company-logo-thumbnail' );
									?>	
									</div><!-- /.resume-company-logo -->
									<div class="resume-listing-name">
										<h4><?php echo esc_attr( get_the_title() ); ?></h4>	
										<p class="resume-listing-summary"><?php echo get_post_meta( get_the_id(), '_jboard_job_summary', true ); ?></p>	
									</div><!-- /.resume-listing-name -->
									<div class="resume-listing-region">
										<i class="fa fa-fw fa-map-marker"></i>
										<?php
											$job_taxs = get_the_terms( get_the_id(), 'job_region' );
											if($job_taxs){
												foreach( $job_taxs as $job_tax ){
													echo esc_attr( $job_tax->name );
												}
											}
										?>
									</div><!-- /.resume-listing-region -->
									<div class="resume-listing-type">
										<i class="fa fa-fw fa-user"></i>
										<?php
											$job_taxs = get_the_terms( get_the_id(), 'job_type' );
											if($job_taxs){
												foreach( $job_taxs as $job_tax ){
													echo esc_attr( $job_tax->name );
												}
											}
										?>
									</div><!-- /.resume-listing-type -->
								</div><!-- /#resume-listing-<?php echo get_the_id(); ?> -->
							</a>
						<?php
							}
							wp_reset_query();
						?>
						</div><!-- /#all_jobs -->
						<?php
							foreach( $job_types as $job_type ){
						?>
						<div id="<?php echo $job_type->slug.'-'.$job_type->term_id; ?>">
						<?php
							$job_args['tax_query'] = array(
								array(
									'taxonomy' => 'job_type',
									'terms' => $job_type->term_id,
								),
							);
							$jobs = new WP_Query($job_args);
							while( $jobs-> have_posts() ){
								$jobs->the_post();
						?>
							<a class="resume-listing-permalink" href="<?php echo esc_url( get_permalink() ); ?>">
								<div class="resume-listing-row clearfix">
									<div class="resume-company-logo">
									<?php
										$comp_id = get_post_meta( get_the_id(), '_jboard_job_company', true );
										echo get_the_post_thumbnail( $comp_id , 'jobboard-company-logo-thumbnail' );
									?>	
									</div><!-- /.resume-company-logo -->
									<div class="resume-listing-name">
										<h4><?php echo esc_attr( get_the_title() ); ?></h4>	
										<p class="resume-listing-summary"><?php echo get_post_meta( get_the_id(), '_jboard_job_summary', true ); ?></p>	
									</div><!-- /.resume-listing-name -->
									<div class="resume-listing-region">
										<i class="fa fa-fw fa-map-marker"></i>
										<?php
											$job_taxs = get_the_terms( get_the_id(), 'job_region' );
											if($job_taxs){
												foreach( $job_taxs as $job_tax ){
													echo esc_attr( $job_tax->name );
												}//endforeach;
											}//endif;
										?>
									</div><!-- /.resume-listing-region -->
									<div class="resume-listing-type">
										<i class="fa fa-fw fa-user"></i>
										<?php
											$job_taxs = get_the_terms( get_the_id(), 'job_type' );
											if($job_taxs){
												foreach( $job_taxs as $job_tax ){
													echo esc_attr( $job_tax->name );
												}//endforeach;
											}//endif;
										?>
									</div><!-- /.resume-listing-type -->
								</div><!-- /#resume-listing-<?php echo get_the_id(); ?> -->
							</a>
						<?php
							}
							wp_reset_query();
						?>
						</div><!-- /#<?php echo esc_attr( $job_type->slug ).'-'.esc_attr( $job_type->term_id ); ?> -->
						<?php
							}
						?>
					</div><!-- /#resume-listing-tabs -->
				</div><!-- /.resumes-listing-wrapper -->
			</div><!-- /.col-md-8 -->
			
			<?php get_sidebar('home'); ?>
			
		</div><!-- /.row -->
	</div><!-- /.container -->
</div><!-- /#resumes-listings -->