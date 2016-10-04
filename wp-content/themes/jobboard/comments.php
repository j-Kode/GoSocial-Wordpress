<?php
/**
 * The template for displaying Comments
 *
 * The area of the page that contains comments and the comment form.
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 */

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>

	<h2 class="comments-title">
		<i class="fa fa-comment"></i>
		<?php
			printf( _n( 'One comment to this post', '%1$s comments to this post', get_comments_number(), 'jobboard' ),
				number_format_i18n( get_comments_number() ) );
		?>
	</h2>

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
	<nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'jobboard' ); ?></h1>
		<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'jobboard' ) ); ?></div>
		<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'jobboard' ) ); ?></div>
	</nav><!-- #comment-nav-above -->
	<?php endif; // Check for comment navigation. ?>

		<?php
			wp_list_comments( array(
				'style'      => 'ul',
				'short_ping' => true,
				'avatar_size'=> 70,
				'walker'	 => new Jobboard_Walker_Comment(),
			) );
		?>

	<?php if ( ! comments_open() ) : ?>
	<p class="no-comments"><?php _e( 'Comments are closed.', 'jobboard' ); ?></p>
	<?php endif; ?>

	<?php endif; // have_comments() ?>

	<?php
		$commenter = wp_get_current_commenter();
		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );
		$fields = array(
			'author' => '
				<div class="form-group group-horizontal">
					<div class="col-sm-6"><input class="form-control" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ).'" size="30"' . $aria_req . ' placeholder="'.__( 'Name', 'jobboard' ).'" /></div>
			',
			'email' => '
					<div class="col-sm-6"><input class="form-control" id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ).'" size="30"' . $aria_req . ' placeholder="'.__( 'Email', 'jobboard' ).'" /></div>
				</div>
			',
		);
		$comment_args = array(
			'fields' => $fields,
			'comment_notes_before' => false,
			'comment_notes_after' => false,
			'label_submit' => __( 'Submit', 'jobboard' ),
			'comment_field' => '
				<div class="form-group">
					<div class="col-sm-12"><textarea class="form-control" id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="'.__( 'Message', 'jobboard' ).'"></textarea></div>
				</div>	
			',
		);
		ob_start();
		comment_form( $comment_args );
		echo str_replace('class="comment-form"', 'class="comment-form form-horizontal"', ob_get_clean());

	?>

</div><!-- #comments -->
