<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 */
?>
		<?php
			if( !is_page_template( 'page-templates/template-homepage.php' ) && jobboard_option('enable_footer_contact_banner') ){
				$show_query = true;
			}else{
				$show_query = false;
			}
			
			if($show_query){
				get_template_part( 'template-parts/footer', 'contact_query' );
			}
		?>
		<footer id="footer">
			<?php get_sidebar( 'footer' ); ?>
			<div id="footer-text" class="container">
			<?php
				if( jobboard_option('enable_custom_footer') ){
					echo jobboard_option('custom_footer_text');
				}else{
					_e( '2015 &copy; GoSocialMedia, All Rights Reserved', 'jobboard' );
				}
			?>
			</div><!-- /#footer-text -->
		</footer><!-- /#footer -->
	</div><!-- /#wrapper -->
	<?php wp_footer(); ?>
</body>
</html>