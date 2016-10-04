<?php
/**
 * Template Name: Thank You
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 *
 */




require 'stripe/init.php';
$user_id = get_current_user_id();
$user_info = get_userdata($user_id);


get_header();

Stripe\Stripe::setApiKey("sk_test_HaJwgnBYlkYQNJyKQWgv6Ixe");

// Get the credit card details submitted by the form
$token = $_POST['stripeToken'];
$amount = $_POST['amount'] * 100;
$ahguid = $_POST['ahguid'];

// Create the charge on Stripe's servers - this will charge the user's card
try {
    $charge = \Stripe\Charge::create(array(
      "amount" => $amount, // amount in cents, again
      "currency" => "cad",
      "source" => $token,
      "description" => "Example charge",
      ));
    if ($charge['status'] == 'succeeded'){
        $_SESSION['charged'] = true;
		addFunds($user_id, ($charge['amount']/100), $charge['id'], "Deposit", "Stripe");
    }
}
catch(\Stripe\Error\Card $e) {
    $carddeclinedError = true;
}
catch (\Stripe\Error\InvalidRequest $a) {
        // Since it's a decline, Stripe_CardError will be caught
        $invalidRequestError = true;
    }

?>
<!-- /#page-title -->
<?php if($cardeclinedError){ ?>
<div id="content" style="background-color: #f0f0f0">
    <div class="container">
        <div class="col-md-12 text-center" style="min-height: 450px; padding: 50px 0;"> 
		<div><h1><i style="color: #1fa67a" class="fa fa-times-circle-o fa-5x"></i></h1>
        <h1>There was an error processing your payment</h1>
		
		<h3>Please check that your payment details have been entered correctly</h3>
		<h5>If the the problem persists, please contact us at <a href="/contact-us"/>gosocialmedia.ca support</a></h5>
		</div>
    </div>
</div>
<?php } elseif($invalidRequestError){ ?>
<div id="content" style="background-color: #f0f0f0">
    <div class="container">
        <div class="col-md-12 text-center" style="min-height: 450px; padding: 50px 0;"> 
		<div><h1><i style="color: #D24A4A" class="fa fa-times-circle-o fa-5x"></i></h1>
        <h1>There was an error processing your payment</h1>
		
		<h3>Please check that your payment details have been entered correctly</h3>
		<h5>If the the problem persists, please contact us at <a href="/contact-us"/>gosocialmedia.ca support</a></h5>
		</div>
    </div>
</div>
	
	
<?php } else { ?>
<div id="content" style="background-color: #f0f0f0">
    <div class="container">
        <div class="col-md-12 text-center" style="min-height: 450px; padding: 50px 0;"> 
		<div><h1><i style="color: #1fa67a" class="fa fa-check-circle-o fa-5x"></i></h1>
        <h1><?php echo '$'.($amount / 100) ; ?> have been deposited</h1>
		
		<h3>Your funds have been added to your account</h3>
		<h5>You will recieve a confirmation email shortly summarizing the charges</h5>
		</div>
    </div>
</div>




<?php  } get_footer(); ?>