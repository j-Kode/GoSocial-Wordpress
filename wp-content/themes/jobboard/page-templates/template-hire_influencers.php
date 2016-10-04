<?php

/**

 * Template Name: Hire Influencers

 *

 * @package WordPress

 * @subpackage Job_Board

 * @since Job Board 1.0

 *

 */

?>



<?php get_header(); ?>

<div id="page-title-wrapper" style="border-bottom: 1px solid #C5C5C5;">

    <div class="container">
		<div class="col-md-12">
			<h1 class="page-title" style="font-size: 28px; padding-top: 44px">Hire Influencers</h1>
		</div>

    </div>
    <div class="container" style="color:#fff;">
		<div class="col-md-12">
			<?php custom_breadcrumbs(); ?>
		</div>
	</div>
    <!-- /.container -->

</div>
<!-- /#page-title -->

<div id="content" style="background-color: #f0f0f0">

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

            $success = "<div style=\"display:block\" class=\"hire-influencer-status alert alert-success alert-dismissable\" role=\"alert\">

				<button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\"><i class=\"fa fa-times\"></i></span><span class=\"sr-only\"><?php _e( 'Close', 'jobboard' ); ?></span></button>

				<strong>Thank you!</strong> Your message was sent successfully

			</div>";

            

            if($mail_sent){

                echo "$success";

            }

        }



        ?>

        <div class="col-lg-7">
            <form id="hire-influencer" action="" method="post">
                <ol class="hireSteps">
                    <li>
                        <fieldset class="hireStepLabel">
                            <label for="contact_name">What industry is your brand in?</label>
                            <div class="form-group">


                                <select name="industryID" id="industryType" class="form-control" required="required" placeholder="Select A Category">

                                    <option value=""><?php echo __( 'Select a category for posting', 'jobboard' ); ?></option>

                                    <?php $industryTypes= get_industry_types();

                                          foreach( $industryTypes as $industryType){

                                              $selected='';

                                              echo '<option value="'.$industryType->industry_id.'"'.$selected.'>'.stripslashes($industryType->industry_name).'</option>';

                                          }?>

                                </select>

                            </div>
                        </fieldset>
                    </li>
                    <!-- /.form-group -->
                    <li>
                        <fieldset class="hireStepLabel">
                            <label>What is your post about?</label>
                            <div class="form-group">
                                <label for="contact_email">Post Name</label>

                                <input type="text" name="postingTitle" id="postingTitle" class="form-control" required="required" placeholder="Title of posting" />

                            </div>
                            <div class="form-group">
                                <label for="contact_email">Give further details about the posting.</label>
                                <textarea name="postingDesc" class="form-control custom-control" rows="3" style="resize: none" placeholder="Describe your posting..."></textarea>
                            </div>
                        </fieldset>
                    </li>
                    <li>
                        <fieldset class="hireStepLabel">
                            <label>What is your budget?</label>
                            <div class="form-group">
                                <input type="radio" name="priceType" value="2" checked="checked" /><label>Set fixed price</label>
                                <input type="radio" name="priceType" value="1"/><label>Set a pay-per-like rate</label>
                            </div>
                            <div class="form-group">
                                <label for="contact_email">Set a budget</label>
                                <input type="number" name="paymentAmount" id="paymentAmount" class="form-control" required="required" placeholder="Price ($)" />
                                <!-- /.form-group -->
                            </div>
                        </fieldset>
                    </li>
                    <li>
                        <fieldset class="hireStepLabel">
                            <label>Where is your brand located?</label>
                            <div class="form-group">
                                <label for="contact_email">Start typing your city</label>
                                <input type="text" name="postingCity" id="locationID" style="display: none" />
                                <input style="height: 50px !important" class="form-control" required="required" autocomplete="off" onkeyup="suggest(this.value);" onblur="hide();" type="search" id="locationName" placeholder="Start typing your city..." />
                                <ul id="location-results" class="autocomplete-results ng-scope" ng-show="isVisible" ng-style="resultsStyle" style="display: none; width: 198px; min-width: 198px;">
                                </ul>
                            </div>
                        </fieldset>
                    </li>
                    <li>
                        <fieldset class="hireStepLabel">
                            <label>Tell us more about your post.</label>
                            <div class="form-group">
                                <label>Which social media platform?</label>
                                <select name="mediaID" id="mediaType" class="form-control" required="required" placeholder="Select A Category">
                                    <option value=""><?php echo '-- '.__( 'Select Platform', 'jobboard' ).' --'; ?></option>
                                    <?php $mediaTypes= get_mediaPlatforms();
                                          foreach( $mediaTypes as $mediaType){
                                              $selected='';
                                              echo '<option value="'.$mediaType->ID.'"'.$selected.'>'.stripslashes($mediaType->Social_Name).'</option>';
                                          }?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="minFollowers">How many followers should the influencer have?</label>
                                <input type="number" name="minFollowers" id="minFollowers" class="form-control" required="required" placeholder="Minimum followers" />
                                <!-- /.form-group -->
                            </div>
                        </fieldset>
                    </li>
                </ol>
                <input type="hidden" name="button_pressed" value="1" />

                <button type="submit" name="add_posting" id="add_posting" value="1" class="btn btnbooking" style="font-size: 20px!important; font-weight: 600" data-loading-text="Posting....">Post Campaign Now</button>

                <div class="hire-influencer-status alert alert-success alert-dismissable" role="alert">

                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="fa fa-times"></i></span><span class="sr-only"><?php _e( 'Close', 'jobboard' ); ?></span></button>

                    <?php _e( '<strong>Thank you!</strong> Your message was sent successfully', 'jobboard' ); ?>

                </div>



                <!-- /.row -->

            </form>

        </div>

        <div class="col-md-4 postingInfo">
            <h5 class="subHeading">IT'S FREE TO POST</h5>
            <div style="padding: 0 35px;">
                <p style="text-align: left; font-size: 14px; font-weight: 600;">Try it now!</p>
                <ul class="fa-ul" style="margin: 0 35px;">
                    <li><i class="fa-li fa fa-check-square fa-lg" style="color: #7FDE7F"></i>
                        Expose your company to 1000's of people in your local area with a few clicks!
                    </li>
                    <li><i class="fa-li fa fa-check-square fa-lg" style="color: #7FDE7F"></i>
                        Pay the influencer only when your 100% satisfied.
                    </li>
                    <li><i class="fa-li fa fa-check-square fa-lg" style="color: #7FDE7F"></i>
                        Recieve messages from influencers in your local area in minutes.
                    </li>
                    <li><i class="fa-li fa fa-check-square fa-lg" style="color: #7FDE7F"></i>
                        Having only a 5% commission fee*, start your campaign with the strongest influencer today!
                    </li>
                </ul>
                <p>*fees may apply</p>
            </div>
        </div>
    </div>
    <!-- /.container -->

</div>
<!-- /#content -->



<?php

jobboard_create_gmaps( 'jobboard-gmaps' );

?>

<?php get_footer(); ?>