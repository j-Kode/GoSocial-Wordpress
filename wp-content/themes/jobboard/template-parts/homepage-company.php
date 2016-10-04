<?php
/**
 * Template Part Name : Companies Listing
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 *
 */
?>
<div id="companies-listing">
	<div class="container">
		<h1 class="companies-listing-title"><?php echo apply_filters( 'jobboard_companies_listings_title', jobboard_option('company_title') ); ?></h1>
		<p class="companies-listing-desc">
		<?php
			echo esc_attr( jobboard_option('company_description') );
		?>
		</p>
		<div class="companies-listing-wrapper">
		<?php
			$companies = jobboard_option('company_slider');
			$mb_args = array(
				'type'	=> 'image',
				'size'	=> 'jobboard-companies-listing',
			);
			
			$slider_items = rwmb_meta( 'jobboard_slider_images', $mb_args, $companies );
			
			if($slider_items){
				foreach( $slider_items as $slider){
				
			?>
				<div id="company-<?php echo $slider['ID']; ?>" class="company-item">
				<img src="<?php echo esc_url( $slider['url'] ); ?>" alt="<?php esc_attr( $slider['alt'] ); ?>" title="<?php echo esc_attr( $slider['title'] ); ?>" width="<?php echo esc_attr( $slider['width'] ); ?>" height="<?php echo esc_attr( $slider['height'] ); ?>" />
				</div>
			<?php
				}
				wp_reset_postdata();
			}
		
		?>
		</div><!-- /.companies-listing-wrapper -->
	</div><!-- /.container -->
</div><!-- /#companies-listing -->