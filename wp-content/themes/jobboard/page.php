<?php
/**
 * Default template to show single page.
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 */

get_header(); ?>
<div id="page-title-wrapper">
	<div class="container">
		<h1 class="page-title"><?php the_title(); ?></h1>
	</div><!-- /.container -->
</div><!-- /#page-title -->

<div id="content" <?php post_class(); ?>>
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<article>
				<?php
					while( have_posts() ){
						the_post();

						// Include the page content template.
						the_content();

					}//endwhile;
				?>
				</article>
			</div><!-- /.col-md-8 -->
			<?php get_sidebar(); ?>
		</div><!-- /.row -->
	</div><!-- /.container -->
</div><!-- /#content -->
<?php
get_footer();
