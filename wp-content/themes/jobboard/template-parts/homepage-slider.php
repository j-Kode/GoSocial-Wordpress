<?php
/**
 * Template Part Name : Homepage Slider
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 *
 */
?>
<div id="homepage-banner" class="hidden-xs">
	<div id="slider-wrapper">
	<?php
		
		$slider_id = jobboard_option( 'select_slider' );
		$mb_args = array( 'type'	=> 'image' );
		$slider_item = rwmb_meta( 'jobboard_slider_images', $mb_args, $slider_id );
		
		foreach( $slider_item as $item ){
			echo '<div class="slider-item">';
			echo '<img class="homepage-slider-image" src="'.esc_url( $item['full_url'] ).'" alt="'.esc_attr( $item['alt'] ).'" title="'.esc_attr( $item['title'] ).'" />';
			echo '</div><!-- /.slider-item -->';
		}//endforeach;
				
	?>
	</div><!-- /.slider-images-container -->
	<?php if( jobboard_option('enable_slider_caption') ){ ?>
	<div class="banner-wrapper hidden-sm">
		<div class="container">
			<div class="banner-caption">
				<div class="row">
					<div class="col-md-6">
						<div class="banner-left">
							<h1><?php echo esc_attr( jobboard_option( 'find_job_title' ) ); ?></h1>
							<p><?php echo esc_attr( jobboard_option( 'find_job_desc' ) ); ?></p>
							<a href="<?php echo esc_url( jobboard_option('find_job_button_url') ); ?>" class="btn btn-default btn-find-job"><?php echo esc_attr( jobboard_option( 'find_job_button' ) ); ?></a>
						</div>
					</div>
					<div class="col-md-6">
						<div class="banner-right">
							<h1><?php echo esc_attr( jobboard_option( 'post_job_title' ) ); ?></h1>
							<p><?php echo esc_attr( jobboard_option( 'post_job_desc' ) ); ?></p>
							<a href="<?php echo esc_url( jobboard_option('post_job_button_url') ); ?>" class="btn btn-default btn-post-job"><?php echo esc_attr( jobboard_option( 'post_job_button' ) ); ?></a>
						</div>
					</div>
				</div><!-- /.row -->
			</div><!-- /.banner-caption -->
		</div><!-- /.container -->
	</div><!-- /.banner-wrapper -->
	<?php }//endif; ?>
</div><!-- /#homepage-banner -->