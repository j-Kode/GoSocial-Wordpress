<?php

/**
 * Post archive template,
 * Display archive post (date, category, tag)
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 *
 */
 
get_header(); ?>
<div id="page-title-wrapper">
	<div class="container">
		<h1 class="page-title">
		<?php
			if( is_category() ){
				echo __( 'Category Archives', 'jobboard' ).'&nbsp;:&nbsp;';
				single_cat_title();
			}elseif( is_date() ){
				if( is_year() ){
					echo __( 'Yearly Archives', 'jobboard' ).'&nbsp;:&nbsp;'.get_the_time('Y');
				}elseif( is_month() ){
					echo __( 'Monthly Archives', 'jobboard' ).'&nbsp;:&nbsp;'.get_the_time('F Y');
				}elseif( is_day() ){
					echo __( 'Daily Archives', 'jobboard' ).'&nbsp;:&nbsp;'.get_the_time( get_option( 'date_format' ) );
				}
			}elseif( is_tag() ){
				echo __( 'Tag Archives', 'jobboard' ).'&nbsp;:&nbsp;';
				single_tag_title();
			}elseif( is_author() ){
				echo __( 'Author Archives', 'jobboard' ).'&nbsp;:&nbsp;'.get_the_author_meta( 'display_name' );
			}
		?>
		</h1>
	</div><!-- /.container -->
</div><!-- /#page-title -->
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