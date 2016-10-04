<?php
/**
 * Template Part Name : Job Upload/Post
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 *
 */
?>

<div id="upload-post-job">
	<div class="container">
		<div class="row">
			<div class="col-sm-6">
				<div class="upload-post-job-wrapper resume">
					<h4><?php echo esc_attr( jobboard_option( 'post_1_title' ) ); ?></h4>
					<p><?php echo esc_attr( jobboard_option( 'post_1_description' ) ); ?></p>
					<a style="<?php echo 'background:'.esc_attr( jobboard_option('post_1_button_color') ).'; color:'.esc_attr( jobboard_option('post_1_button_text_color') ).';' ?>" href="<?php echo esc_url( jobboard_option( 'post_1_button_url' ) )? esc_url( jobboard_option( 'post_1_button_url' ) ): '#'; ?>" class="btn btn-upload-post resume">
					<?php
						echo esc_attr( jobboard_option('post_1_button_text') );
						if( jobboard_option('post_1_button_icon') ){
							echo '<i class="fa '.jobboard_option('post_1_button_icon').'"></i>';
						}//endif;
					?>
					</a>
				</div><!-- /.upload-post-job-wrapper -->
			</div><!-- /.col-sm-6 -->
			<div class="col-sm-6">
				<div class="upload-post-job-wrapper job">
					<h4><?php echo esc_attr( jobboard_option( 'post_2_title' ) ); ?></h4>
					<p><?php echo esc_attr( jobboard_option( 'post_2_description' ) ); ?></p>
					<a style="<?php echo 'background:'.esc_attr( jobboard_option('post_2_button_color') ).'; color:'.esc_attr( jobboard_option('post_2_button_text_color') ).';' ?>" href="<?php echo esc_url( jobboard_option( 'post_2_button_url' ) )? esc_url( jobboard_option( 'post_2_button_url' ) ): '#'; ?>" class="btn btn-upload-post resume">
					<?php
						echo esc_attr( jobboard_option('post_2_button_text') );
						if( jobboard_option('post_2_button_icon') ){
							echo '<i class="fa '.esc_attr( jobboard_option('post_2_button_icon') ).'"></i>';
						}//endif;
					?>
					</a>
				</div><!-- /.upload-post-job-wrapper -->
			</div><!-- /.col-sm-6 -->
		</div><!-- /.row -->
	</div><!-- /.container -->
</div><!-- /#upload-post-job -->