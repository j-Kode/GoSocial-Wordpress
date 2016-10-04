<?php
/**
 * Template Name: Register Page
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 *
 */

$error = false;
if( isset( $_POST['user_submit'] ) ){
	
	$creds = array(
		'email'		=> $_POST['register_email'],
		'password'	=> $_POST['register_password'],
		'firstname' => $_POST['register_firstname'],
		'lastname'  => $_POST['register_lastname'],
		'company'   => $_POST['register_company'],
		'city'      => $_POST['postingCity'],
		'phoneNo'   => $_POST['register_phoneNo'],
		'passwordIssue' => $_POST['passwordIssue'],
		'username' => $_POST['username'],
		'accountType' => "influencer",
        'membershipType' => "free",
        'remainingMessages' => "5",
        'maximumMessages' => "5",
	);
	
	$user = wp_create_influencer( $creds['email'], $creds['password'], $creds['firstname'], $creds['lastname'], $creds['company'], $creds['city'], $creds['phoneNo'], $creds['passwordIssue'], $creds['username'], $creds['accountType'], $creds['membershipType'], $creds['remainingMessages'],$creds['maximumMessages']);
	
	if( isset($user->errors) ){
		$wp_error = $user;
		$error = true;
		$login_class = 'animated shake';
	}else{
		update_user_meta( $user, 'jobboard_user_role', $_POST['register_role'] );
		wp_redirect( get_permalink( jobboard_option( 'login' ) ) ); exit;
	}
}

if( isset( $_POST['cp_getStarted'] ) ){
	
	$creds = array(
		'firstname'		=> $_POST['cp_firstname'],
		'lastname'	=> $_POST['cp_lastname'],
		'email' => $_POST['cp_email'],
		'company'  => $_POST['cp_company'],
		'phone'   => $_POST['cp_phone'],
		'campaign'   => $_POST['cp_campaign'],
		);
	
	$user = wp_email_campaign( $creds['firstname'], $creds['lastname'], $creds['email'], $creds['company'], $creds['phone'], $creds['campaign']);
	
	if( isset($user->errors) ){
		$wp_error = $user;
		$error = true;
		$login_class = 'animated shake';
	}
}

if( isset( $_POST['login'] ) )
{
	$login = $_POST['user'];
	$pass = $_POST['unique'];
	$creds = array();
	$creds['user_login'] = $login;
	$creds['user_password'] = $pass;
	$creds['remember'] = false;
	$user = wp_signon( $creds, false );
	if ( is_wp_error($user) ){
		$wp_error = $user;
		$error = true;
		$login_class = 'animated shake';
		}
	else
		header("Location: http://localhost:81/");
}


if( is_user_logged_in() ){
	wp_redirect( get_permalink( jobboard_option( 'dashboard_page' ) ) ); exit;
}


get_header(); ?>
<script>

function validate() {
    if((document.getElementById('inputPasswordConfirm').value) != (document.getElementById('inputPassword').value)){
		jQuery('#passwordError').fadeIn(800);
		jQuery('#passwordissue').val(1);
		}
	else{
		jQuery('#passwordError').fadeOut(800);
		jQuery('#passwordissue').val(0);
		}
	if(document.getElementById('inputPasswordConfirm').value.length < 8){
		jQuery('#passwordError2').fadeIn(800);
		jQuery('#passwordissue').val(1);
		}
	else{
		jQuery('#passwordError2').fadeOut(800);
		jQuery('#passwordissue').val(0);
		}
	}
		
function inputFocus(element){
	jQuery('.input-group-lg').removeClass('focus');
	jQuery(element).parent().addClass('focus');
	
}

function get_states(countryid)
{
	jQuery.ajax({
            type: "POST",
            url: "/post.php",
            data: { countryID: countryid}
        }).done(function( msg ) {
			jQuery('#job_state').html(unescape(msg));
			jQuery('#job_state').fadeIn(800);
		});
		
		
}

function get_Cities(stateID)
{
	jQuery.ajax({
            type: "POST",
            url: "/post.php",
            data: { stateID: stateID}
        }).done(function( msg ) {
			jQuery('#job_city').html(unescape(msg));
			jQuery('#job_city').fadeIn(800);
		});
}
	$(document).ready(function(){
		var location;
		$('#username').focusout(function(){
			checkUsername($(this).val());
		});
		$('#locationName').focusin(function() {
			location = $('#locationName').val();
		});
		$('#locationName').focusout(function(){
			if(location != '' && location != $('#locationName').val())
				$('#locationID').val(0);
			if($('#locationID').val() == 0)
				$('#locationError').show('slow');
			else
				$('#locationError').hide('slow');
		});
		$("#location-results").focusin(function () {
			$('#locationError').hide('slow');
		});
		$('#register').submit(function(e){
			if(($('#usernameIssue').val() == 1) || $('#locationID').val() == 0)
				e.preventDefault();
		})
	});
	function checkUsername(username)
	{
		if(username !=  '') {
			jQuery.ajax({
				type: "POST",
				url: "/post.php",
				data: {existingUsername: username}
			}).done(function (msg) {
				if (msg) {
					$('#usernameIssue').val(1);
					$('#usernameError').show('slow');
					$('#usernameSuccess').hide('slow');
				}
				else {
					$('#usernameIssue').val(0);
					$('#usernameError').hide('slow');
					$('#usernameSuccess').show('slow');
				}
			});
		}
	}

</script>
<div id="page-title-wrapper" class="register-page-wrapper">
	<div id="reg-overlay">
	<div class="container inner-reg">
		<div class="row col-md-6 whiteFont">
			<h1 class="job-search-title"><strong>Join </strong>GoSocialMedia</h1>
			<p style="text-align:center; font-size:18px">Get<strong> paid </strong>when your creative posts are seen, liked or shared.</p>
		<p style="text-align:center"><button type="button" class="btn sc-button medium athletered col-md-4 col-md-offset-4" data-toggle="modal" data-target="#loginModal">Login</button></a></p>
		<h1 class="job-search-title">Want to grow your <strong>brand</strong>?</h1>
			<p style="text-align:center; font-size:18px">Click below and tell us a little about your brand.</p>
		<p style="text-align:center"><button type="button" class="btn sc-button medium athletered col-md-4 col-md-offset-4" data-toggle="modal" data-target="#campaignModal">Start a Campaign</button></a></p>
		</div>
		<div class="row col-md-6">
			<div class="col-md-12">
				<?php
				if ( $error ) {
					echo '<div id="login-error-box" class="alert alert-warning">';
					$errors = '';
					$messages = '';
					foreach ( $wp_error->get_error_codes() as $code ) {
						$severity = $wp_error->get_error_data($code);
						foreach ( $wp_error->get_error_messages($code) as $error ) {
							if ( 'message' == $severity )
								$messages .= '	' . $error . "<br />\n";
							else
								$errors .= '	' . $error . "<br />\n";
						}
					}
					if ( ! empty( $errors ) ) {
						/**
						 * Filter the error messages displayed above the login form.
						 *
						 * @since 2.1.0
						 *
						 * @param string $errors Login error message.
						 */
						echo '<div id="login_error">' . apply_filters( 'login_errors', $errors ) . "</div>\n";
					}
					if ( ! empty( $messages ) ) {
						/**
						 * Filter instructional messages displayed above the login form.
						 *
						 * @since 2.5.0
						 *
						 * @param string $messages Login messages.
						 */
						echo '<p class="message">' . apply_filters( 'login_messages', $messages ) . "</p>\n";
					}
					echo '</div><!-- /#login-error-box -->';
				}//endif;
				?>
				<div class="form-top">
                        		<div class="form-top-left">
                        			<h3>Influencer Sign up</h3>
                            		<p>Fill in the form below to get access:</p>
                        		</div>
                        		<div class="form-top-right">
                        			<i class="fa fa-pencil"></i>
                        		</div>
                            </div>
					<form class="form-bottom" id="register" data-toggle="validator" action="<?php echo esc_url( get_permalink( get_the_id() ) ); ?>" method="post">
						<div class="input-group input-group-lg">
							<span class="input-group-addon labelmods" id="sizing-addon2">Username</span>
							<input id="username" name="username" type="text" class="form-control inputmods" placeholder="GoSocialMedia Username" onClick="inputFocus(this)" required>
						</div>
						<input id="usernameIssue" type="hidden" value="0" />
						<div class="alert alert-danger" id="usernameError" style="display:none;margin-bottom:0px;" role="alert">Whoops, this username is already taken!</div>
						<div class="alert alert-success" id="usernameSuccess" style="display:none;margin-bottom:0px;" role="alert">Great!, this username is available.</div>
						<div class="input-group input-group-lg">
							<span class="input-group-addon labelmods" id="sizing-addon2">Email</span>
							<input name="register_email" type="text" class="form-control inputmods" placeholder="Email" onClick="inputFocus(this)" aria-describedby="sizing-addon1" required>
						</div>
						<div class="input-group input-group-lg">
							<span class="input-group-addon labelmods" id="sizing-addon2">Password</span>
							<input name="register_password" type="password" data-minlength="8" onClick="inputFocus(this)" class="form-control inputmods" id="inputPassword" placeholder="Minimum of 8 Characters" required>
						</div>
						 <div class="input-group input-group-lg">
							<span class="input-group-addon labelmods" id="sizing-addon2">Confirm Password</span>
							<input name="confirmPassword" type="password" onblur="validate()" onClick="inputFocus(this)" class="form-control inputmods" id="inputPasswordConfirm" data-match="#inputPassword" placeholder="Confirm Password" required>
						</div>
						<div class="alert alert-danger" id="passwordError" style="display:none;margin-bottom:0px;" role="alert">Whoops, passwords don't match.</div>
						<div class="alert alert-danger" id="passwordError2" style="display:none;margin-bottom:0px;" role="alert">Whoops, password must be minimum 8 characters.</div>
						<input id="passwordissue" type="hidden" name="passwordIssue" value="0" />
						<div class="input-group input-group-lg">
							<span class="input-group-addon labelmods" id="sizing-addon2">First Name</span>
							<input name="register_firstname" type="text" class="form-control inputmods" placeholder="First Name" onClick="inputFocus(this)" aria-describedby="sizing-addon1" required>
						</div><!-- /.form-group -->
						<div class="input-group input-group-lg">
							<span class="input-group-addon labelmods" id="sizing-addon2">Last Name</span>
							<input name="register_lastname" type="text" class="form-control inputmods" placeholder="Last Name" onClick="inputFocus(this)" aria-describedby="sizing-addon1" required>
						</div>
						<div class="input-group input-group-lg">
							<span class="input-group-addon labelmods" id="sizing-addon2">Company</span>
							<input name="register_company" type="text" class="form-control inputmods" placeholder="Company" onClick="inputFocus(this)" aria-describedby="sizing-addon1" required>
						</div><!-- /.form-group -->
							<div class="input-group input-group-lg">
								<span class="input-group-addon labelmods" id="sizing-addon2">Location</span>
								<input type="text" name="postingCity" id="locationID" style="display: none" value="0"/>
								<input style="height: 50px !important" class="form-control inputmods" required="required" autocomplete="off" onkeyup="suggest(this.value);" onfocusout="hide();" type="search" id="locationName" placeholder="Start typing your city..." />
								<ul id="location-results" class="autocomplete-results ng-scope" ng-show="isVisible" ng-style="resultsStyle" style="display: none; width: 300px !important; margin-top:45px;">
								</ul>
							</div>
						<div class="alert alert-danger" id="locationError" style="display:none;margin-bottom:0px;" role="alert">Whoops, you need to select a location!</div>
						<!--<div class="input-group input-group-lg">
							<span class="input-group-addon labelmods" id="sizing-addon2">Address</span>
							<input name="register_address" type="text" class="form-control inputmods" placeholder="Address" onClick="inputFocus(this)" aria-describedby="sizing-addon1" required>
						</div>
						<div class="input-group input-group-lg">
							<span class="input-group-addon labelmods" id="sizing-addon2">Apt, suite, etc</span>
							<input name="register_address2" type="text" class="form-control inputmods" placeholder="Apt, suite, etc (optional)" onClick="inputFocus(this)" aria-describedby="sizing-addon1">
						</div>
						<div class="input-group input-group-lg">
							<span class="input-group-addon labelmods" id="sizing-addon2">Postal Code</span>
							<input name="register_zip" type="text" class="form-control inputmods" placeholder="Postal Code" onClick="inputFocus(this)" aria-describedby="sizing-addon1" required>
						</div>-->
						<div class="input-group input-group-lg">
							<span class="input-group-addon labelmods" id="sizing-addon2">Phone Number</span>
							<input name="register_phoneNo" type="text" class="form-control inputmods" placeholder="Phone Number" onClick="inputFocus(this)" aria-describedby="sizing-addon1">
						</div>
						<input type="hidden" name="action" value="jobboard_proccess_login_form" />
						<button type="submit" name="user_submit" id="user_submit" value="1" class="btn sc-button medium athletered col-md-4 col-md-offset-4"><?php _e( 'Register', 'jobboard' ); ?></button>
					</form>
				
			</div><!-- /.col-md-5 -->
		</div><!-- /.row -->
	</div><!-- /.container -->
	</div>
</div><!-- /#page-title -->




<?php get_footer(); ?>
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
	<form action="#" method="post" enctype="multipart/form-data">
      <div class="form-top"  style="height:100px">
	  
       <div class="form-top-left">
	   <h3>Login</h3>
                        		</div>
                        		<div class="form-top-right" style="font-size:35px">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        			<i class="fa fa-key"></i>
                        		</div>
      </div>
      <div class="form-bottom" style="padding-bottom: 0">
				<div class="input-group input-group-lg">
							<input name="user" type="text" class="form-control" placeholder="Email" onClick="inputFocus(this)" aria-describedby="sizing-addon1" required>
						</div>
						<div class="input-group input-group-lg">
							<input name="unique" type="password" data-minlength="8" onClick="inputFocus(this)" class="form-control" id="inputPassword" placeholder="Password" required>
						</div>
						
		<div class="modal-footer">
        <button type="Submit" class="btn sc-button medium athletered"  type="submit" name="login" id="login" value="1" value="1">Login</button>
      </div>
	  </form>
    </div>
  </div>
</div>
</div>

<div class="modal fade" id="campaignModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<form action="#" method="post" id="startCampaign enctype="multipart/form-data">
				<div class="form-top"  style="height:100px">
					<div class="form-top-left">
						<h3>Start your campaign</h3>
					</div>
                    <div class="form-top-right" style="font-size:35px">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    	<i class="fa fa-key"></i>
                    </div>
				</div>
				<div class="form-bottom">
				<div class="input-group input-group-lg">
						<span class="input-group-addon labelmods" id="sizing-addon2">First Name</span>
						<input name="cp_firstname" type="text" class="form-control inputmods" placeholder="First Name" onClick="inputFocus(this)" aria-describedby="sizing-addon1" required>
					</div>
					<div class="input-group input-group-lg">
						<span class="input-group-addon labelmods" id="sizing-addon2">Last Name</span>
						<input name="cp_lastname" type="text" class="form-control inputmods" placeholder="Last Name" onClick="inputFocus(this)" aria-describedby="sizing-addon1" required>
					</div>
					<div class="input-group input-group-lg">
						<span class="input-group-addon labelmods" id="sizing-addon2">Email</span>
						<input name="cp_email" type="text" class="form-control inputmods" placeholder="Email" onClick="inputFocus(this)" aria-describedby="sizing-addon1" required>
					</div>
					<div class="input-group input-group-lg">
						<span class="input-group-addon labelmods" id="sizing-addon2">Company</span>
						<input name="cp_company" type="text" class="form-control inputmods" placeholder="Company" onClick="inputFocus(this)" aria-describedby="sizing-addon1">
					</div>
					<div class="input-group input-group-lg">
						<span class="input-group-addon labelmods" id="sizing-addon2">Phone</span>
						<input name="cp_phone" type="text" class="form-control inputmods" placeholder="Phone" onClick="inputFocus(this)" aria-describedby="sizing-addon1" required>
					</div>
					<div class="input-group input-group-lg">
						<span class="input-group-addon labelmods" id="sizing-addon2">Campaign</span>
						<textarea name="cp_campaign"  rows="5"  class="form-control inputmods" placeholder="About your campaign"  required></textarea>
					</div>
				</div>
			<div class="modal-footer">
				<button type="Submit" class="btn sc-button medium athletered"  type="submit" name="cp_getStarted" id="cp_getStarted" value="1" value="1">Get Started</button>
			</div>
			</form>
		</div>
	</div>
</div>