<?php
/**
 * The template used for displaying blog loop content
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 */
?>
<div class="row">
	<div class="col-sm-3 blog-list-author hidden-xs">
		<div class="blog-list-avatar">
			<?php echo get_avatar( get_the_author_meta( 'ID' ), 100 ); ?>
		</div><!-- /.blog-list-avatar -->
		<div class="blog-list-author-name">
			<?php echo __( 'By', 'jobboard' ).'&nbsp;'?> 
				<?php if((get_the_author_meta( 'display_name' )) == 'sidhujeffery@gmail.com')
				{
					echo '<b>Athletes United</b>';
				}
				else 
				{
					echo get_the_author_meta( 'display_name' );
				} ?>
		</div><!-- /.blog-list-author-name -->
		<div class="blog-list-post-date-comment">
			<span class="post-date"><i class="fa fa-calendar"></i>&nbsp;<?php echo get_the_date( get_option('date-format') );  ?></span>
			<span class="post-comment"><i class="fa fa-comment"></i>&nbsp;<?php comments_number( '0', '1', '%' ) ?></span>
		</div><!-- /.blog-list-post-date-comment -->
	</div><!-- /.col-md-3 -->
	<div class="col-sm-9 blog-list-content">
		<article <?php post_class(); ?> >
			<h1 class="blog-list-title"><a href="<?php the_permalink() ?>"><b><?php the_title(); ?></b></a></h1>
			<?php
				 if( has_post_thumbnail() ){
				 the_post_thumbnail( 'jobboard-blog-list-thumbnail' );
				}
				
				the_content('Read more...');
			?>
		</article><!-- /#blog-<?php echo get_the_id(); ?> -->
	</div><!-- /.col-md-9 -->
</div><!-- /.row -->