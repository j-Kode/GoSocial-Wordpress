<?php
/**
 * The Header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 */
 
 if(!is_user_logged_in() && !is_page(181)){
	 header("Location: http://localhost:81/register");
	 die();
}
 
if(isset($_POST['submit'])){
	
}
if(isset( $_POST['add_posting'] ) ){
    $userid = get_current_user_id();
    $creds = array(
        'postingTitle'	=> $_POST['postingTitle'],
        'industryID'	=> $_POST['industryID'],
        'pricingID' 	=> $_POST['priceType'],
        'minFollowers'	=> $_POST['minFollowers'],
        'postingCity' 	=> $_POST['postingCity'],
        'postingDesc'	=> $_POST['postingDesc'],
        'paymentAmount' => $_POST['paymentAmount'],
        'mediaID'		=> $_POST['mediaID']);	
	
    $posting =  wp_add_posting($creds['postingTitle'],  $creds['industryID'], $userid, $creds['pricingID'],  $creds['postingCity'], $creds['postingDesc'], $creds['minFollowers'], $creds['paymentAmount'],$creds['mediaID']);
    
    if( isset($posting->errors) ){
		$wp_error = $user;
		$errorFacility = true;
		$login_class = 'animated shake';
    }
    else
    {
        $successPosting = true;
		
    }
    
    $new_url = add_query_arg( 'ap', 'success', '/' );
    wp_redirect( $new_url, 303 );
    exit;
}

if( isset( $_POST['form_type'] ) ){
	$form_type = $_POST['form_type'];
	switch($form_type){
		case 'post_resume':
			jobboard_post_resume($_POST, $_FILES);
			break;
		
		case 'edit_post_resume';
			jobboard_post_resume( $_POST, $_FILES, true );
			break;
			
		case 'post_job';
			jobboard_post_job($_POST);
			break;
		
		case 'edit_post_job';
			break;

			
		case 'post_company';
			jobboard_post_company( $_POST, $_FILES );
			break;
			
		case 'edit_post_company';
			jobboard_post_company( $_POST, $_FILES, true );
			break;
			
	}//endswitch;
}
$userInfo = get_userdata(get_current_user_id());

get_header('modals');
?>
<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8) ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<title><?php wp_title(''); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo get_site_url(); ?>/wp-content/themes/jobboard/assets/css/bootstrap-datetimepicker.min.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.js"></script>
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script src="<?php echo get_site_url(); ?>/wp-content/themes/jobboard/assets/js/readmore.min.js"></script>
    <script src="<?php echo get_site_url(); ?>/wp-content/themes/jobboard/assets/js/formValidation.js"></script>
    <script src="<?php echo get_site_url(); ?>/wp-content/themes/jobboard/assets/js/framework/bootstrap.js"></script>
<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">-->
	 <link href="<?php echo get_site_url(); ?>/wp-content/themes/jobboard/assets/css/lightbox.css" rel="stylesheet" />
	 <link href="<?php echo get_site_url(); ?>/wp-content/themes/jobboard/assets/css/hover.min.css" rel="stylesheet" />
	 <link href='http://fonts.googleapis.com/css?family=Open+Sans:700,300,400' rel='stylesheet' type='text/css'>
    <link href='<?php echo get_site_url(); ?>/wp-content/themes/jobboard/assets/css/formValidation.css' rel='stylesheet' type='text/css'>
	 <script src="<?php echo get_site_url(); ?>/wp-content/themes/jobboard/assets/js/lightbox.min.js"></script>
	<script type="text/javascript" src="<?php echo get_site_url(); ?>/wp-content/themes/jobboard/assets/js/bootstrap-datetimepicker.js"></script> 
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>

    <script>
        jQuery(function($){ 
            $('article').readmore({
                speed: 750,
                collapsedHeight: 40  
            });
            $(window).load(function() {
		    // Animate loader off screen
		        $(".se-pre-con").delay(800).fadeOut("slow", function(){
                     $("#messagePanel").css({opacity: 0, visibility: "visible"}).animate({opacity: 1}, 400);
                });
               
	        });
       });
        function suggest(Text) {

            jQuery.ajax({
                type: "POST",
                url: "/post.php",
                data: { searchText: Text }
            }).done(function (msg) {
                jQuery('#location-results').html(unescape(msg));
                jQuery('#location-results').fadeIn(800);
            });

        }
		function revokeCampaign(postingID) {
			$('#sureRevoke').modal('show');
			$('#sureRevoke').find('#yes').click(function(){
			jQuery.ajax({
					type: "POST",
					url: "<?php echo get_site_url(); ?>/post.php",
					data: {revokeID: postingID, revokeGuid: "<?= $userInfo->user_guid ?>"},
					success: function (data) {
						if (data == 0) {
							$('#cantRevoke').modal('show');
							$('#sureRevoke').modal('hide');
							$('#cantRevoke').find('#ok').click(function(){
								$('#cantRevoke').modal('hide');
							});
						}
						else
							location.reload();
					}
				});
			});
			$('#sureRevoke').find('#no').click(function(){
				$('#sureRevoke').modal('hide');
			});
			return false;
		}

        function selectLocation(location) {
            var listLocationItem = document.getElementById(location);
            var locationId = document.getElementById("locationID");
            var locationInput = document.getElementById("locationName");
            locationInput.value = listLocationItem.innerHTML;
            locationId.value = location;
        }

        function hide() {
            jQuery('#location-results').fadeOut(800);
        }
        $.fn.editable.defaults.mode = 'inline';
        $(document).ready(function () {
            $('.editable').editable('destroy');
            $('.edit').hide();
            $('#editProfile').click(function (e) {
                $('.edit').show();
                $('#editProfile').hide();
                $('#viewProfile').show();
                enableEdit();
                $('.editing').removeClass("noEdit");

            });
            $('#viewProfile').click(function (e) {
                $('.edit').hide();
                $('#editProfile').show();
                $('#viewProfile').hide();
                disableEdit();
                $('.editing').addClass("noEdit");
            });
        });
        function disableEdit() {
            $('.editable').editable('destroy');
        }
		function SetCookie(c_name,value,minutes)
		{
			var exdate=new Date()
			exdate.setTime(exdate.getTime()+ (minutes * 60 * 1000))
			document.cookie=c_name+ "=" +escape(value)+
				((minutes==null) ? "" : ";path=/;expires="+exdate.toGMTString())
		}
		function getCookie(cname) {
			var name = cname + "=";
			var ca = document.cookie.split(';');
			for(var i=0; i<ca.length; i++) {
				var c = ca[i];
				while (c.charAt(0)==' ') c = c.substring(1);
				if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
			}
			return false;
		}
    </script>
</head>

<body <?php body_class(); ?>>
	<div id="wrapper">
		
		<?php
			if( jobboard_option('enable_admin_menu') || jobboard_option('enable_social_media_url') ){
				get_template_part( 'template-parts/header', 'bar' );
			}//endif;
		?>
		<header id="header" style="display:none">
			<div class="container">
				<div class="row">
					<div class="col-md-4">
					<?php
						$custom_logo = jobboard_option( 'custom_header_logo' );
						$logo = '';
						if( empty($custom_logo) ){
							$logo = 'custom-logo-inactive';
						}
					?>
						<div class="logo-wrapper <?php echo esc_attr( $logo ) ?>">
							<a href="<?php echo esc_url( home_url() ); ?>" class="header-logo" title="<?php echo esc_attr( get_bloginfo('name') ); ?>">
						<?php
							if($custom_logo){
								echo '<img src="'.esc_url( $custom_logo ).'" alt="'.esc_attr( get_bloginfo('name') ).'" /></a>';
							}else{
								echo '<h1 class="site-name">'.get_bloginfo('name').'</h1>';
								echo '<span class="site-description">'.get_bloginfo('description').'</span>';
							}
						?>
							</a>
						</div><!-- /.logo-wrapper -->
					</div><!-- /.col-md-3 -->
					<div class="col-md-8">
						<div id="menu-wrapper">
							<button class="navbar-toggle collapsed" style="margin-top:30px" type="button" data-toggle="collapse" data-target="#main-menu">
    							<span class="sr-only"><?php _e( 'Toggle navigation', 'jobboard' ); ?></span>
    							<span class="icon-bar"></span>
    							<span class="icon-bar"></span>
    							<span class="icon-bar"></span>
    						</button>
    						<nav id="main-menu" class="clearfix collapse navbar-collapse" role="navigation">
    						<?php
    							$menu_args = array(
    								'theme_location' => 'primary_menu',
    								'container' => false,
    								'menu_class' => 'nav-menu',
    								'fallback_cb' => '__return_false',
    							);
    							wp_nav_menu($menu_args);
    						?>
    						</nav><!-- /#main-menu -->
						</div><!-- /#menu-wrapper -->
					</div><!-- /.col-md-9 -->
				</div><!-- /.row -->
			</div><!-- /.container -->
		</header><!-- /#header -->