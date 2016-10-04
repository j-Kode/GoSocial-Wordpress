<?php

/**
 * Template Name: Fullwidth - No Sidebar
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

<div id="content" <?php post_class(); ?>>
	<div class="container">
		<article id="page-<?php the_ID(); ?>">
		<?php
			while( have_posts() ){
				the_post();

				the_content();

			}//endwhile;
		?>
		</article>
	</div><!-- /.container -->
</div><!-- /#content -->

<?php
get_footer();