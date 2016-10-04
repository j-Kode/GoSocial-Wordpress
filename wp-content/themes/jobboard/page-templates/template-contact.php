<?php
/**
 * Template Name: Contact Page
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 *
 */
?>

<?php get_header(); ?>

<div id="page-title-wrapper">
	<div class="container">
		<h1 class="page-title" style="font-size: 28px">Contact Us</h1>
	</div><!-- /.container -->
</div><!-- /#page-title -->
<div id="content" style="background-color:#ffffff">
	<div class="container">
	<?php
if(isset($_POST['button_pressed']))
{
    $to      = 'info@trainersforathletes.com';
    $subject = $_POST['contact_subject'];
	$message = 'FROM: '. $_POST['contact_email'] . ' ' . $_POST['contact_name'] . "\r\n" . $_POST['contact_message'];
    $headers = 'From: '. $_POST['contact_email'] . "\r\n" .
        'Reply-To: webmaster@example.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    $mail_sent = mail($to, $subject, $message, $headers);
	$success = "<div style=\"display:block\" class=\"contact-form-status alert alert-success alert-dismissable\" role=\"alert\">
				<button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\"><i class=\"fa fa-times\"></i></span><span class=\"sr-only\"><?php _e( 'Close', 'jobboard' ); ?></span></button>
				<strong>Thank you!</strong> Your message was sent successfully
			</div>";
	
    if($mail_sent){
		echo "$success";
	}
}

?>
	<div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
	<p class="headerPara">Want to get in touch with us? Fill out the form below to send us a message and we will get back to you within 24 hours!</p>
	<?php
		while( have_posts() ){
			the_post();
	?>
		<article id="contact-page-<?php echo get_the_id(); ?>">
		<?php the_content(); ?>
		</article><!-- /.#contact-page-<?php echo get_the_id(); ?> -->
	<?php
		}//endwhile;
		wp_reset_postdata();
	?>
		<form id="contact-form" action="" method="post">
			<div class="row">
					<div class="form-group">
						<label for="contact_name"><i style="margin-right:5px" class="fa fa-user fa"></i><?php _e( 'Name', 'jobboard' ); ?></label>
						<input type="text" name="contact_name" id="contact_name" class="form-control" required="required" />
					</div><!-- /.form-group -->
					<div class="form-group">
						<label for="contact_email"><i style="margin-right:5px" class="fa fa-envelope fa"></i><?php _e( 'Email', 'jobboard' ); ?></label>
						<input type="email" name="contact_email" id="contact_email" class="form-control" required="required" />
					</div><!-- /.form-group -->
			</div><!-- /.row -->
			<div class="row">
					<div class="form-group">
						<label for="contact_subject"><i style="margin-right:5px" class="fa fa-question-circle fa"></i><?php _e( 'Subject', 'jobboard' ); ?></label>
						<input type="text" name="contact_subject" id="contact_subject" class="form-control" />
					</div><!-- /.form-group -->
			<!-- /.row -->
			<div class="form-group">
				<label for="contact_message"><i style="margin-right:5px" class="fa fa-pencil fa"></i><?php _e( 'Message', 'jobboard' ); ?></label>
				<textarea name="contact_message" rows="7" class="form-control" required="required" ></textarea>
			</div><!-- /.form-group -->
			
			<input type="hidden" name="button_pressed" value="1" />
			<button type="submit" name="contact_submit" value="1" class="btn btn-send-contact-form" data-loading-text="<?php _e( 'Sending...', 'jobboard' ); ?>"><?php _e( 'Send', 'jobboard' ); ?></button>
			<div class="contact-form-status alert alert-success alert-dismissable" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="fa fa-times"></i></span><span class="sr-only"><?php _e( 'Close', 'jobboard' ); ?></span></button>
				<?php _e( '<strong>Thank you!</strong> Your message was sent successfully', 'jobboard' ); ?>
			</div>
			</div>
		</form>
		</div>
	</div><!-- /.container -->
</div><!-- /#content -->

<?php
	jobboard_create_gmaps( 'jobboard-gmaps' );
?>
<?php get_footer(); ?>