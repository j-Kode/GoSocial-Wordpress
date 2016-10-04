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

<div class="col-md-4">
	<aside id="sidebar-default" class="sidebar">
	<?php
		if( is_active_sidebar( 'default_sidebar' ) ){
			dynamic_sidebar( 'default_sidebar' );
		}//endif;
	?>
	</aside><!-- /#sidebar-default -->
</div><!-- /.col-md-4 -->