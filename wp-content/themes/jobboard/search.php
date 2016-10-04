<?php

/**
 * Post search result template
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
			echo __( 'Search Result for', 'jobboard' ).'&nbsp;:&nbsp;'.get_search_query();
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
					wp_reset_postdata();
				} else { ?>
					<div class="alert alert-dismissible jb-alert nopost" role="alert">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<?php _e( 'No results were found for: "<strong>'.get_search_query().'"</strong>', 'jobboard' ); ?>
					</div>
					<?php get_search_form();
				} ?>	
			</div><!-- /.col-md-8 -->
		
			<?php get_sidebar(); ?>
		</div><!-- /.row -->
	</div><!-- /.container -->
</div><!-- /#content -->

<?php
get_footer();