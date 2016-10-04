<?php
/**
 * Template Part Name : Homepage Job Listing
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 *
 */
if(isset($_GET['message']))
{
	$message = $_GET['message'];
}

// readyToChkInv();
if(is_user_logged_in())
{
	$loggedUser_info = get_userdata(get_current_user_id());
}
?>
<script>
    jQuery(document).ready(function () {
        jQuery('#postingDisplay').delay(5000).fadeOut(3500);
        jQuery("button[id^='btnPosting']").click(function () {
            var id = jQuery(this).attr('id');
            jQuery('#contactModal').attr('name', id);
        })
    });

    function contactBrand(userid) {

        var modalName = jQuery('#contactModal').attr('name');
        var startSubIndex = modalName.indexOf("-") + 1;
        var lastCharacter = modalName.length;
        var postingID = modalName.substring(startSubIndex, lastCharacter);
        var message = jQuery('#contactMessage').val();
        submitContactMessage(postingID, userid, message);
    }
    function submitContactMessage(postingID, userid, message) {
        jQuery.ajax({
            type: "POST",
            url: "/post.php",
            data: { postID: postingID, uID: userid, message: message },
            success: function (msg) {
                if (msg.indexOf("error") > 0) {
                    alert("There was an error!, please contact info@gosocialmedia.");
                    location.reload();
                }
                else
                    alert("Your message has been sent!");
                location.reload();
            }
        });
        return false;
    }
</script>
<div id="jobs-listing" class="in-homepage">
    <div id="home-listings-container" class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div id="displayTitle" class="jobs-listing-title" style="padding-top: 15px;">
                    <label id="postingErrorDisplay" style="color: #D43F3F; display: none"></label>
                    <h3 style="font-size: 35px"><i class="fa fa-comments-o"></i><?php _e( 'Recent Campaign Posts', 'jobboard' ); ?> <button class="btn btn-lg btnbookinghighlight" onClick="parent.location='<?php echo esc_url( get_permalink( get_page_by_title( 'Hire Influencers' ) ) ); ?>'" style="float:right">Start a Campaign</button></h3>
                    <div>
                       
                    </div>
                </div>

                <div class="jobs-listing-wrapper">
                    <div id="job-listing-tabs">
                        <div id="all_jobs">
                            <?php
							$job_args = array( 'post_type' => 'job' );
							$jobs = new WP_Query($job_args);
							$count = 'odd';
							
							$postings = getAllPostings();

							foreach($postings as $posting)
							{
							
							$user_info = get_userdata($posting->user_id);
						?>
                            <?php 
							$comp_id = get_post_meta( get_the_id(), '_jboard_job_company', true );
							$socialMediaType = $posting->SocialMediaType;
							if($socialMediaType ==  1)
							{
								$class = 'trainercolor';
							}
							else
							{
								 $class = 'athletecolor';
							 }
							?>
                            <div class="job-listing-row clearfix" style="padding: 0 0;">
                                <table style="width: 100%">
                                    <tr class="collapseomatic noarrow <?php echo $count ?>" id="<?php echo $posting->posting_id; ?>-all"  style="min-height:120px;">
                                        
                                        <td class="col-md-7 postingTitle" >
                                            <ul class="promotionMarker">
                                                <?php if($posting->promotionType == 1)
                                                    echo "<li class=\"promotionFeatured\">Featured</li>";
                                                      ?>
                                            </ul>
                                            <h3 style="font-size: 18px; color:#227dc4;font-weight:bold;">
                                                <?php 
                                                    if($posting->SocialMediaType == 1)
                                                        echo "<i class=\"fa fa-instagram fa-lg\" style=\"color:#125688\"></i>";
                                                    else if($posting->SocialMediaType == 2)
                                                        echo "<i class=\"fa fa-twitter fa-lg\" style=\"color:#55acee\"></i>";
                                                    else if($posting->SocialMediaType == 3)
                                                        echo "<i class=\"fa fa-facebook-official fa-lg\" style=\"color:#4e69a2\"></i>";
                                                    ?>
                                                <?php 
										if(strlen($posting->postingTitle) > 30) {
										echo esc_attr( substr(stripslashes($posting->postingTitle), 0, 30) )."...";
										}
										else{
										echo stripslashes($posting->postingTitle);
										} ?>
                                            </h3>
                                            <p class="job-listing-summary">
                                                <?php 
										if(strlen($posting->postingDesc) > 50) {
										echo substr(stripslashes($posting->postingDesc),0 ,50)."...";
										}
										else{
										echo stripslashes($posting->postingDesc);
										} ?>
                                            </p>
                                            <p class="job-listing-summary" style="color: #227dc4">
                                                <?php 
										echo stripslashes($posting->industry_name);
										?>
                                            </p>
                                        </td>
                                        
                                        <td class="col-md-3 postingLocation">
                                            <i class="fa fa-fw fa-map-marker"></i>
                                            <?php $locations = get_post_location($posting->postingCity); 
												foreach($locations as $area){
													if(($area->countryID) == 109 || ($area->countryID) == 295){
														echo $area->city?>, <?php echo $area->stateCode;	
													}
													else {
														echo $area->city?>, <?php echo $area->countryCode;	
													}
												}
											?>
                                        </td>
                                        
                                        <td id="paymentAmount" class="col-md-1 postingPrice">
                                            <?php echo "$".$posting->paymentAmount; 
                                                if($posting->pricingType == 1)
                                                    echo "<small style=\"font-size:70%\">/Like</small>";
                                                ?>

                                        </td>
                                        <td  class="col-md-1 postingTime">
                                                <?php
										
	
										$date = new DateTime($posting->postingDate);
										$date->setTimezone(new DateTimeZone('america/los_angeles'));
										$ComparePostingDate = $date->format('g:ia');
										
										$today = new DateTime();
										$today->setTimezone(new DateTimeZone('america/los_angeles'));
										$CompareToday = $today->format('d/m/y @ g:ia');
										
										$diff = date_diff($date, $today);
										
										if($diff->days == 0)
											echo "Today<span><small><i class=\"fa fa-fw fa-clock-o\"></i>".$ComparePostingDate. "</small></span>";
										else if ($diff->days == 1)
											echo "Yesterday<span><small><i class=\"fa fa-fw fa-clock-o\"></i>".$ComparePostingDate. "</small></span>";
										else
											echo $diff->format("%a days")."<span><small><i class=\"fa fa-fw fa-clock-o\"></i>".$ComparePostingDate. "</small></span>";
										?>
                                        </td>
                                        
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            <div id="target-<?php echo $posting->posting_id ?>-all" style="display:none; ">
                                                <?php $job_id = get_the_id(); ?>
                                                <table id="postingDetails" style="width:100%">
                                                    <tr>
                                                        <td>
                                                            <div class="the-job-company col-md-6" style="width: 50%">
                                                                <i class="fa fa-rocket"></i>
                                                                <b><?php echo stripslashes($posting->postingTitle); ?></b>
                                                            </div>
                                                            <div class="the-job-location col-md-6" style="width: 50%">
                                                                <i class="fa fa-fw fa-envelope"></i>
                                                                <b><?php echo getNoProposals($posting->posting_id); ?> Proposals</b>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="the-job-aditional-details" style="padding-top: 10px">
														<div class="col-xs-12">
                                                            <div class="col-xs-6">
                                                                <h6><b class="col-xs-4"><?php echo __( 'Platform: ', 'jobboard' )?></b><p class="col-xs-8"><?php echo stripslashes($posting->Social_name); ?></p>
                                                                </h6>
                                                                <h6><b class="col-xs-4"><?php echo __( 'Followers: ', 'jobboard' )?></b><p class="col-xs-8"><?php echo stripslashes($posting->minFollowers); ?></p>
                                                                </h6>
																</div>
															<div class="col-xs-6">
                                                                <h6><b class="col-xs-4"><?php echo __( 'Posted: ', 'jobboard' )?></b><p class="col-xs-8"><?php echo date('F j, Y', strtotime($posting->postingDate)); date('h:i A', strtotime($posting->postingDate)); ?></p>
                                                                </h6>
                                                                <h6><b class="col-xs-4"><?php echo __( 'Description: ', 'jobboard' )?></b><p class="col-xs-8"><?php echo stripslashes($posting->postingDesc); ?></p>
                                                                </h6>
                                                            </div>
															</div>
                                                            <div class="col-xs-12 text-center" style="padding: 25px 0 15px 0;">
                                                                <button id="btnPosting-<?php echo $posting->posting_id?>" class="btn btnbooking" data-toggle="modal" data-target="#contactModal">Submit Proposal</button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <!-- /#job-detail -->
                                                </table>
                                          </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <!-- /#job-listing-<?php echo get_the_id(); ?> -->
                            <?php
							if($count == 'even')
								$count = 'odd';
							else
								$count = 'even';
							}
							wp_reset_query();
						?>
                        </div>
                        <!-- /#all_jobs -->
                    </div>
                    <!-- /#job-listing-tabs -->
                </div>
                <!-- /.jobs-listing-wrapper -->
            </div>
            <!-- /.col-md-8 -->

            <?php get_sidebar('home'); ?>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container -->
</div>
<!-- /#jobs-listings -->

