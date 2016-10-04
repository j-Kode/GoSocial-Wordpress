<?php
/**
 * JobBoard - Custom Button Widget
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 */
?><?php

/**
 * Create Jobboard_Featured_Job.
 */
Class Jobboard_Custom_Button extends WP_Widget{
	
	/**
	 * Register widget with WordPress.
	 */
	function __construct(){
		parent::__construct(
			'jobboard_custom_button', // Base ID
			__('JobBoard - Custom Button', 'jobboard'), // Name
			array( 'description' => __( 'A custom button with title and description.', 'jobboard' ), ) // Args
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
	?>
		<div class="upload-post-job-wrapper resume">
			<h4 class="custom-button-widget-title"><?php echo esc_attr( $instance['title'] ); ?></h4>
			<p class="custom-button-widget-text"><?php echo esc_attr( $instance['text'] ); ?></p>
			<a href="<?php echo esc_url( $instance['button_url'] ); ?>" class="btn btn-custom-button-widget"><?php echo esc_attr( $instance['btn_text'] ); ?></a>
		</div><!-- /.upload-post-job-wrapper -->
		
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
		
		$title = isset( $instance['title'] )? $instance['title']:'';
		$text = isset( $instance['text'] )? $instance['text']:'';
		$btn_text = isset( $instance['btn_text'] )? $instance['btn_text']:'';
		$button_url = isset( $instance['button_url'] )? $instance['button_url']:'';
		
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'jobboard' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php _e( 'Text Description:', 'jobboard' ); ?></label>
			<textarea class="widefat" rows="6" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ) ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ) ?>"><?php echo esc_textarea( $text ) ?></textarea>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'btn_text' ) ); ?>"><?php _e( 'Button Text:', 'jobboard' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'btn_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'btn_text' ) ); ?>" type="text" value="<?php echo esc_attr( $btn_text ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'button_url') ); ?>"><?php _e( 'Button URL:', 'jobboard' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_url' ) ); ?>" type="text" value="<?php echo esc_attr( $button_url ); ?>" />
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
		$instance['text'] = ( !empty( $new_instance['text'] ) ) ? strip_tags( $new_instance['text'] ) : '';
		$instance['btn_text'] = ( !empty( $new_instance['btn_text'] ) ) ? strip_tags( $new_instance['btn_text'], '<i>' ) : '';
		$instance['button_url'] = ( !empty( $new_instance['button_url'] ) ) ? strip_tags( $new_instance['button_url'] ) : '';

		return $instance;
		
	}// update();
	
}//end Class Jobboard_Featured_Job

// register Jobboard_Featured_Job widget
if( !function_exists( 'jobboard_register_custom_button_widget' ) ){
	function jobboard_register_custom_button_widget() {
		register_widget( 'Jobboard_Custom_Button' );
	}
	add_action( 'widgets_init', 'jobboard_register_custom_button_widget' );
}//endif;


