<?php

/**
 * Single resume template file
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 *
 */
 
get_header();
	while( have_posts() ){
		the_post();
		
		get_template_part( 'template-parts/modal', 'contact_resume' );
		$authorid = get_the_author_meta( 'ID' );
?>
<div id="page-title-wrapper">
	<div class="container">
		<div class="row">
			<div class="col-sm-4">
				<h1 class="frontend-title"><?php _e( 'Candidate Profile', 'jobboard' ); ?></h1>
			</div><!-- /.col-md-6 -->
			<div class="col-sm-8">
				<div class="candidate-button">
				<?php
					if( $authorid == get_current_user_id() ){
				?>
					<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'edit', 'jid' => get_the_id() ) ), esc_url( jobboard_get_permalink( 'post_resume' ) ) ); ?>" class="btn btn-bookmark"><i class="fa fa-pencil-square-o"></i>&nbsp;<?php _e( 'Edit', 'jobboard' ); ?></a>	
				<?php
					}else{	
				?>
					<form id="bookmark-resume" method="post" action="<?php echo esc_url( admin_url('admin-ajax.php') ); ?>">
						<input type="hidden" name="user_id" value="<?php echo esc_attr( get_current_user_id() ); ?>" />
						<input type="hidden" name="action" value="jobboard_bookmark_the_resume" />
						<input type="hidden" name="resume_id" value="<?php echo esc_attr( get_the_id() ); ?>" />
						<?php
							$disable = '';
							$tooltip = '';
							$btn_text = __( 'Bookmark', 'jobboard' );
															
							if( !is_user_logged_in() ){
								$disable = 'disabled';
								$tooltip = 'data-toggle="popover" data-trigger="hover" data-placement="left" data-container="body" data-content="'.__( 'Login to bookmark this resume', 'jobboard' ).'"';
							}
							
							$old_bookmark = get_post_meta( get_the_id(), 'jobboard_resume_bookmarker' );
		
							// Explode data into array
							$bookmark_array = $old_bookmark;
							

							// Check if current user not bookmarked this resume yet
							if( in_array( get_current_user_id(), $bookmark_array ) ){
								$disable = ' disabled';
								$btn_text = __( 'Bookmarked', 'jobboard' );
							}//endif;
							
							
						?>
						<button data-on-success="<i class='fa fa-star'></i>&nbsp;<?php _e( 'Bookmarked', 'jobboard' ); ?>" data-loading-text="<?php _e( 'Proccessing...', 'jobboard' ); ?>" id="bookmark-button" type="submit" name="submit" value="submit" class="btn btn-bookmark<?php echo esc_attr($disable);?>" <?php echo $tooltip; ?> ><i class="fa fa-star"></i>&nbsp;<?php echo esc_attr( $btn_text ); ?></button>
						
						<a download target="_blank" href="<?php echo esc_url( get_post_meta( get_the_id(), 'jobboard_resume_file', true ) ); ?>" class="btn btn-resume"><?php _e( 'Download Resume', 'jobboard' ); ?></a>
						<a href="#" class="btn btn-contact" data-toggle="modal" data-target="#contact-resume-modal"><?php _e( 'Contact', 'jobboard' ); ?></a>
					</form>
				<?php
					}//endif;
				?>
				</div><!-- /.candidate-button -->
			</div><!-- /.col-md-6 -->
		</div><!-- /.row -->
		<div class="candidate-profile">
		<?php
			echo jobboard_get_the_post_thumbnail( get_the_id(), 'jobboard-resume-photo' );
		?>
			<h3 class="candidate-name"><?php the_title(); ?></h3>
			<div class="candidate-details">
				<span><?php echo vp_metabox('jobboard_resume_mb.resume_professional_title'); ?></span>
				<span><i class="fa fa-map-marker"></i>&nbsp;<?php echo vp_metabox('jobboard_resume_mb.resume_location'); ?></span>
				<span>
				<?php
					
					$user_date = get_userdata($authorid)->user_registered;
					echo __( 'Member from ', 'jobboard' ).jobboard_time_ago( $user_date );
				?>
				</span>
				<span><a href="<?php echo esc_url( get_the_author_meta( 'twitter', $authorid ) ); ?>"><i class="fa fa-twitter"></i></a></span>
				<span><a href="<?php echo esc_url( get_the_author_meta( 'linkedin', $authorid ) ); ?>"><i class="fa fa-linkedin"></i></a></span>
				<span><a href="<?php echo esc_url( get_the_author_meta( 'facebook', $authorid ) ); ?>"><i class="fa fa-facebook"></i></a></span>
				<span><a href="<?php echo esc_url( get_the_author_meta( 'url', $authorid ) ); ?>"><i class="fa fa-link"></i></a></span>
			</div><!-- /.candidate-details -->
			
		</div><!-- /.candidate-profile -->
	</div><!-- /.container -->
</div><!-- /#page-title -->
<div id="content">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<?php the_content(); ?>
			</div><!-- /.col-md-6 -->
			
			<div class="col-md-6">
				
				<div class="skills-container">
					<h3 class="skills-title"><?php _e( 'Skills', 'jobboard' ); ?></h3>
					<div class="the-skills">
					<?php
						$skills = explode( ',', vp_metabox( 'jobboard_resume_mb.skills_group.0.resume_skills' ) );
						foreach( $skills as $skill ){
							echo '<span class="skill-item">'.esc_attr( $skill ).'</span>';
						}
					?>
					</div><!-- /.the-skills -->
				</div><!-- /.skills-container -->
				
				<div class="education-container">
					<h3 class="educations-title"><?php _e( 'Education', 'jobboard' ); ?></h3>
					<ul class="resume-lists">
					<?php
						$educations = vp_metabox( 'jobboard_resume_mb.education_group_container.0.education_group' );
						
						foreach( $educations as $edu ){
							echo '<li>';
						?>
							<div class="education-name"><strong><?php echo esc_attr( $edu['institution_name'] ); ?></strong></div>
							<span class="education-period"><i class="fa fa-fw fa-calendar"></i>&nbsp;<?php echo esc_attr( $edu['education_period'] ); ?>&nbsp;:&nbsp;</span>
							<span class="education-study-field"><?php echo esc_attr( $edu['study_field'] ); ?></span><br />
							<span class="education-grade"><i class="fa fa-fw fa-star"></i>&nbsp;<?php _e( 'Grade / GPA', 'jobboard' ); ?>&nbsp;:&nbsp;<?php echo esc_attr( $edu['grade'] ); ?></span><br />
							<span class="education-qualification"><i class="fa fa-fw fa-check"></i>&nbsp;<?php _e( 'Qualification', 'jobboard' ); ?>&nbsp;:&nbsp;<?php echo esc_attr( $edu['qualification'] ); ?></span>
						<?php
							echo '</li>';
						}
						
					?>
					</ul>
				</div><!-- /.education-container -->
				
				<div class="experience-container">
					<h3 class="educations-title"><?php _e( 'Experience', 'jobboard' ); ?></h3>
					<ul class="resume-lists">
					<?php
						$experiences = vp_metabox( 'jobboard_resume_mb.experience_group_container.0.experience_group' );
						
						foreach( $experiences as $exp ){
							echo '<li>';
						?>
							<div class="education-name"><strong><?php echo esc_attr( $exp['company_name'] ); ?></strong></div>
							<span class="education-period"><i class="fa fa-fw fa-calendar"></i>&nbsp;<?php echo esc_attr( $exp['employment_period'] ); ?>&nbsp;:&nbsp;</span>
							<span class="education-study-field"><?php echo $exp['position'] ?></span><br />
							<span class="education-grade"><i class="fa fa-usd fa-fw"></i>&nbsp;<?php _e( 'Yearly Sallary', 'jobboard' ); ?>&nbsp;:&nbsp;<?php echo esc_attr( $exp['sallary'] ); ?></span><br />
							<span class="education-qualification"><i class="fa fa-fw fa-check"></i>&nbsp;<?php _e( 'Job Duties', 'jobboard' ); ?>&nbsp;:&nbsp;</span>
							<div class="experience-job">
							<?php echo esc_attr( $exp['job_duties'] ); ?>
							</div>
						<?php
							echo '</li>';
						}
						
					?>
					</ul>
				</div><!-- /.experience-container -->
				
				<div class="experience-container">
					<h3 class="educations-title"><?php _e( 'URL(s)', 'jobboard' ); ?></h3>
					<ul class="resume-lists">
					<?php
						$urls = vp_metabox( 'jobboard_resume_mb.url_group_container.0.url_group' );
						
						foreach( $urls as $url ){
							echo '<li>';
						?>
							<div class="education-name"><strong><?php echo esc_attr( $url['url_name'] ); ?></strong></div>
							<span class="education-period"><i class="fa fa-fw fa-link"></i>&nbsp;<a href="<?php echo esc_url( $url['url_address'] ); ?>" target="_blank"><?php echo esc_attr( $url['url_address'] ); ?></a></span>
						<?php
							echo '</li>';
						}
						
					?>
					</ul>
				</div><!-- /.experience-container -->
				
			</div><!-- /.col-md-6 -->
		</div><!-- /.row -->
	</div><!-- /.container -->
</div><!-- /#content -->

<?php
	}//endwhile;
get_footer();