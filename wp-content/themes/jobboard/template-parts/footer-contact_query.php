<?php
/**
 * Template Part Name : Footer Contact Query
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 *
 */
?>
<div id="footer-query">
	<div class="container">
		<h2><?php echo apply_filters( 'jobboard_footer_contact_title', jobboard_option( 'footer_contact_title' ) ); ?></h2>
		<p>
		<?php echo esc_attr( jobboard_option( 'footer_contact_description' ) ); ?>
		</p>
		<div class="footer-query-contact">
		<?php echo esc_attr( jobboard_option( 'footer_contact_number' ) ); ?>
		</div><!-- /.footer-query-contact -->
	</div><!-- /.container -->
</div><!-- /#footer-query -->