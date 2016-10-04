<?php
/**
 * Single post template
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 *
 */
 
get_header(); ?>
<div id="page-title-wrapper" class="single-post-template">
	<div class="container">
		<h1 class="page-title"><?php echo apply_filters( 'jobboard_single_post_title', __( 'Blog', 'jobboard' ) ); ?></h1>
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
					
						get_template_part( 'content', 'single' );
						
						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) {
							comments_template();
						}
					}//endwhile;
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