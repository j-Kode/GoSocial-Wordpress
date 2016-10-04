<?php

/**
 * Template Name: Login Page
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 *
 */

$error = false;
if( isset( $_GET['action'] ) && $_GET['action'] == 'logout' ){
	wp_logout();
	wp_redirect( home_url() ); exit;
}//endif;

$login_class='no-animated';
if( isset( $_POST['user_submit'] ) ){
	$cred = array(
		'user_login' => $_POST['user_login'],
		'user_password' => $_POST['user_password'],
		'remember' => false,
	);
	
	$user = wp_signon( $cred, false );
	if( isset($user->errors) ){
		$wp_error = $user;
		$error = true;
		$login_class = 'animated shake';
	}else{
		wp_redirect( '/my-profile' ); exit;
	}
}//endif;


if( is_user_logged_in() ){
	wp_redirect( '/my-profile' ); exit;
}


get_header(); ?>

<div id="page-title-wrapper" class="login-page-wrapper">
	<div class="container">
		<h1 class="job-search-title">Login to Athletes United</h1>
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<?php
				if ( $error ) {
					echo '<div id="login-error-box" class="alert alert-danger">';
					$errors = '';
					$messages = '';
					foreach ( $wp_error->get_error_codes() as $code ) {
						$error_str = '<strong>'.__( 'ERROR', 'jobboard' ).'</strong>: ';
						$lost_password_url = get_permalink( jobboard_option( 'lost_password' ) );
						$lost_password_str = '<a href="'.esc_url( $lost_password_url ).'" title="'.__( 'Password Lost and Found', 'jobboard' ).'"> '.__( 'Lost your password', 'jobboard' ).'</a>?<br />';
						
						if( $code == 'empty_password'){
							echo apply_filters( 'jobboard_empty_password_msg', $error_str.__( 'The password field is empty.', 'jobboard' ) );
						}elseif( $code == 'invalid_username' ){
							echo apply_filters( 'jobboard_invalid_username_msg', $error_str.__( 'Invalid username.', 'jobboard' ).$lost_password_str );
						}elseif( $code == 'incorrect_password' ){
							echo apply_filters( 'jobboard_incorrect_password_msg', $error_str.__( 'The password you entered for the username <strong>admin</strong> is incorrect.', 'jobboard' ).$lost_password_str );
						}
						
					}
					echo '</div><!-- /#login-error-box -->';
				}
				?>
					<form id="login" action="#" method="post">
						<div class="input-group input-group-lg">
							<span class="input-group-addon labelmods" id="sizing-addon2">Email</span>
							<input id="user_login" name="user_login" type="text" class="form-control inputmods" placeholder="Email" onClick="inputFocus(this)" aria-describedby="sizing-addon1" required>
						</div>
						<div class="input-group input-group-lg">
							<span class="input-group-addon labelmods" id="sizing-addon2">Password</span>
							<input name="user_password" type="password" data-minlength="8" onClick="inputFocus(this)" class="form-control inputmods" id="user_password" placeholder="Password" required>
						</div>
						<input type="hidden" name="action" value="jobboard_proccess_login_form" />
						<button type="submit" name="user_submit" id="user_submit" value="1" class="btn sc-button medium athletered col-md-4 col-md-offset-4"><?php _e( 'Log In', 'jobboard' ); ?></button>
					</form>
			</div><!-- /.col-md-5 -->
			<div class="col-md-4 col-md-offset-4">
				<div <?php post_class(); ?>>
					<article style="text-align:center" id="page-<?php the_ID(); ?>">
					<?php
						while( have_posts() ){
							the_post();

							the_content();

						}//endwhile;
					?>
					</article>
				</div><!-- /#content -->
			</div><!-- /.col-md-7 -->
		</div><!-- /.row -->
	</div><!-- /.container -->
</div><!-- /#page-title -->

<?php get_footer(); ?>