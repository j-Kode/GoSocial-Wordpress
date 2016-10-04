<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme and one
 * of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query,
 * e.g., it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 */

get_header(); 

?>

<div id="content">
	<div class="container">
		<div class="row">
			<div class="col-md-8">
			<?php
				if( have_posts() ){
					while( have_posts() ){
						the_post();
					
						get_template_part( 'content', 'blog' );
					
					}//endwhile;
										
					global $wp_query;

					$big = 999999999; // need an unlikely integer
					echo '<div class="dashboard-pagination">';
					echo paginate_links( array(
						'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
						'format' => '?paged=%#%',
						'current' => max( 1, get_query_var('paged') ),
						'total' => $wp_query->max_num_pages
					) );
					echo '</div><!-- /.dashboard-pagination -->';
          
				}//endif;
			?>	
			</div><!-- /.col-md-8 -->
		
			<?php get_sidebar(); ?>
		</div><!-- /.row -->
	</div><!-- /.container -->
</div><!-- /#content -->

<?php
get_footer();
