<?php
/**
 * Featured Job Custom Widget
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 */
?><?php

/**
 * Create Jobboard_Featured_Job.
 */
Class Jobboard_Featured_Job extends WP_Widget{
	
	/**
	 * Register widget with WordPress.
	 */
	function __construct(){
		parent::__construct(
			'jobboard_featured_job', // Base ID
			__('JobBoard - Featured Job', 'jobboard'), // Name
			array( 'description' => __( 'Show featured job.', 'jobboard' ), ) // Args
		);
	}// __construct();
	
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ){
		echo $args['before_widget'];
		
		if( !empty( $instance['title'] ) ){
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}//endif;
		
		
	?>
		<div id="featured-job" class="featured-job-widget">
		<?php
			$job_args = array(
				'post_type' => 'job',
				'posts_per_page' => -1,
				'meta_query' => array(
					array(
						'value'	=> 1,
						'key' => '_jboard_job_featured',
					),
				),
			);
			
			$jobs = get_posts( $job_args );
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
		</div>
	<?php
		echo $args['after_widget'];
	}// widget();
	
	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ){
		
		if ( isset( $instance[ 'title' ] ) ) {
			$title = esc_attr( $instance[ 'title' ] );
		}else{
			$title = __( 'Featured Job', 'jobboard' );
		}//endif;
		
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'jobboard' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
		
	}// form();
	
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ){
		
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
		
	}// update();
	
}//end Class Jobboard_Featured_Job

// register Jobboard_Featured_Job widget
if( !function_exists( 'jobboard_register_featured_job_widget' ) ){
	function jobboard_register_featured_job_widget() {
		register_widget( 'Jobboard_Featured_Job' );
	}
	add_action( 'widgets_init', 'jobboard_register_featured_job_widget' );
}//endif;


