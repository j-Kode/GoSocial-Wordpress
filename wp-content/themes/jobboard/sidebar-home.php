<?php
/**
 * Template Part Name : Default Sidebar
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 *
 */
?>

<div class="col-lg-4">
	<aside id="sidebar-home" class="sidebar">
	<?php
		if( is_active_sidebar( 'home_sidebar' ) ){
			dynamic_sidebar( 'home_sidebar' );
		}//endif;
	?>
	</aside><!-- /#sidebar-default -->
</div><!-- /.col-md-4 -->