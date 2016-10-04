<?php
/**
 * Template Part Name : Footer Widget Area
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 *
 */
?><?php

$widgets = jobboard_option('footer_widget_area');
$limit = 1;
$col_width = '';
if( $widgets != '0' ){
?>
<div class="container">
	<div id="footer-widgets">
		<div class="row">
		<?php
			while( $limit <= $widgets ){
			$col_width = jobboard_option('footer_column_width_'.$limit);
			?>
			<div class="col-md-<?php echo $col_width; ?> widget-container">
			<?php
			if( is_active_sidebar( 'footer_sidebar_'.$limit ) ){
				dynamic_sidebar( 'footer_sidebar_'.$limit );
			} //endif;
			?>
			</div><!-- /.col-md-<?php echo $col_width; ?> -->
			<?php		
			$limit++;
			} // endwhile;
		?>
		</div><!-- /.row -->
	</div><!-- /#footer-widgets -->
</div><!-- /.container -->
<?php 
} //endif; $widgets