<?php

/**
 * Template Name: Blog
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 *
 */
 
get_header(); ?>
<div id="page-title-wrapper">
	<div class="container">
		<h1 class="page-title"><?php the_title(); ?></h1>
	</div><!-- /.container -->
</div><!-- /#page-title -->
<div id="content">
	<div class="container">
		<div class="row">
			<div class="col-md-8">
			<?php
				global $more;
				
				//Protect against arbitrary paged values
				$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
				
				$post_args = array(
					'post_type' => 'post',
					'paged'		=> $paged,
				);
				
				$posts = new WP_Query($post_args);
				if( $posts->have_posts() ){
					while( $posts->have_posts() ){
						$posts->the_post();
						$more = 0;
					
						get_template_part( 'content', 'blog' );

					
					}//endwhile;
									
					$big = 999999999; // need an unlikely integer
						
					echo '<div class="dashboard-pagination">';

					echo paginate_links( array(
						'base'		=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
						'format'	=> '?paged=%#%',
						'current'	=> max( 1, get_query_var('paged') ),
						'total'		=> $posts->max_num_pages,
						'prev_text'	=> __( 'Previous', 'jobboard' ),
						'next_text' => __( 'Next', 'jobboard' ),
					) );
					
					echo '</div><!-- /.dashboard-pagination -->';
					
					wp_reset_postdata();
				}//endif;
			?>
			</div><!-- /.col-md-8 -->
		
			<?php get_sidebar(); ?>
		</div><!-- /.row -->
	</div><!-- /.container -->
</div><!-- /#content -->

<?php
get_footer();