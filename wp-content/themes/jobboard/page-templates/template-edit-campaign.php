<?php

/**

 * Template Name: Edit Campaign

 *

 * @package WordPress

 * @subpackage Job_Board

 * @since Job Board 1.0

 *

 */

?>




<?php get_header(); ?>


<?php

$ID = NULL;
if(isset($_GET["ID"]) && $_GET["ID"] <> "")
{
    $ID = $_GET["ID"];
    $posting = getPostingDetails($ID);
    if(isset($posting) && !empty($posting) && ($posting->user_id == get_current_user_id()))
    {

?>

<script>
    function enableEdit() {
        $('#campaignLocation').editable({
            ajaxOptions: {
                beforeSend: function (xhr, s) {
                    s.data += "&locationID=" + $('#locationID').val();
                }
            },
            type: 'text',
            pk: "<?php echo $posting->posting_id.'-campaign' ?>",
            url: '/post.php',
            data: {},
            name: 'campaignLocation',
            tpl: '<input id="locationName" style="float:right" onkeyup="suggest(this.value);" onblur="hide();" type="text"><ul id="location-results"  class="autocomplete-results ng-scope" ng-show="isVisible" ng-style="resultsStyle" style="display:none; float:right; margin-top:30px; height:auto; min-width: 198px; width:auto !important; padding-right:0px !important;"></ul>'
        });
         $('#followers').editable({
            type: 'text',
            pk: "<?php echo $posting->posting_id.'-campaign' ?>",
            url: '/post.php',
            name: 'minFollowers'
         });
         $('#description').editable({
             type: 'textarea',
             rows: 3,
            pk: "<?php echo $posting->posting_id.'-campaign'?>",
            url: '/post.php',
            name: 'postingDesc'
         });
         $('#company').editable({
            type: 'text',
            pk: "<?php echo $user_id ?>",
            url: '/post.php',
            name: 'company'
         });
    }
    function allowHire(postingID, guid, muID) {
        jQuery.ajax({
            type: "POST",
            url: "/post.php",
            data: {pid: postingID, allowHire: guid, huID: muID},
            success: function (msg) {
                if (msg.indexOf("error") > 0) {
                    alert("There was an error!, please contact info@gosocialmedia.");
                    location.reload();
                }
                else {
                    SetCookie("ahguid", guid, 2);
                    window.location="http://www.gosocialmedia.ca/payments?pid="+postingID;
                }
            }
        });
    }
</script>

<div id="page-title-wrapper" style="border-bottom: 1px solid #C5C5C5;">

    <div class="container">
        <div class="col-md-12">
            <h1 class="page-title" style="font-size: 28px; padding-top: 44px; float:left">
                <?php echo $posting->postingTitle ?>
            </h1>
            <h3 style="font-size: 28px; padding-top: 44px; float:right; color:#fff;">
                $<?php echo $posting->paymentAmount; ?> CAD
            </h3>
        </div>
    </div>
    <div class="container" style="color:#fff;">
        <div class="col-md-12" style="padding-bottom:15px;">
            <?php $isHired = getHiredDetails($posting->posting_id);
            if(!isset($isHired)){
            ?>
            <a style="float:right;" id="editProfile" class="btn btn-lg btnbookinghighlight" href="#">Edit Campaign</a>
            <a style="float:right;display:none;" id="viewProfile" class="btn btn-lg btnbookinghighlight" href="#">View Campaign</a>
            <?php } ?>
        </div>
    </div>
    <div class="container" style="color:#fff;">
        <div class="col-md-12">
            <?php custom_breadcrumbs(); ?>
            <p class="socialPlatformDetails">
                <?php echo $posting->Social_name; ?>
            </p>
        </div>
    </div>

    <!-- /.container -->

</div>
<!-- /#page-title -->

<div id="content" style="background-color: #f0f0f0">
    <div class="container">
        <?php if($ID > 0) {
            if(isset($isHired)) {
                $hired_user_info = get_userdata($isHired->hiredUserID);
                $SocialMediaDetails = socialAccountExists($posting->SocialMediaType, $isHired->hiredUserID)
                ?>
            <div class="col-md-8">
                <div class="se-pre-con col-md-12"></div>
                <div id="messagePanel" class="panel panel-default">
                    <table class="table myCampaigns">
                        <tr>
                            <td colspan="3">
                                Hired Influencer
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <h4><?php echo $hired_user_info->displayName;?></h4>
                            </td>
                            <td>
                                <h5 class="text-right"><?php echo "$".$posting->paymentAmount ?></h5>
                            </td>
                        </tr>
                        <tr>
                            <td>Followers: <?php echo $SocialMediaDetails->followers ?></td>
                            <td>Following: <?php echo $SocialMediaDetails->following ?></td>
                            <td>Located in:  <?php $locations = get_post_location($hired_user_info->city);
                                foreach($locations as $area){
                                    if(($area->countryID) == 109 || ($area->countryID) == 295){
                                        echo $area->city?>, <?php echo $area->stateCode;
                                    }
                                    else {
                                        echo $area->city?>, <?php echo $area->countryCode;
                                    }
                                }
                                ?></td>
                        </tr>
                        <tr>

                        </tr>
                        <tr>
                            <td style="vertical-align: middle;">
                                <?php if($isHired->isAccepted == 1)
                                    echo "<span class='accepted'>Accepted</span>";
                                else
                                    echo "<span class='notAccepted'>Awaiting Acceptance</span>";
                                ?>
                            </td>
                            <td style="vertical-align: middle;">
                                <?php
                                date_default_timezone_set('America/Los_Angeles');
                                $deadDate = date_create($isHired->deadLineDate);
                                $currentDate = date_create(Date("Y-m-d"));

                                $diff = date_diff($deadDate, $currentDate);
                                echo "Deadline in ".$diff->format("%a days")
                                ?>
                            </td>
                            <td class="text-right">
                                <button type="button" id="<?php echo $posting->posting_id ?>" onclick="revokeCampaign(this.id)" class="btn btn-info">Revoke</button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <?php } else { ?>
                <div class="col-md-8">
                <div class="se-pre-con col-md-12"></div>
                <div id="messagePanel" class="panel panel-default">
                    <table class="table myCampaigns">
                        <tr>
                            <td>
                                Messages
                            </td>
                        </tr>
                        <tr>
                            <?php $messages = getPostingMessages($ID, $posting->SocialMediaType);
                            foreach($messages as $message)
                            {
                            $messagingUser = get_userdata($message->userID);
                            ?>
                        </tr>
                        <tr>
                            <td>
                                <div class="col-md-9">
                                    <h3>
                                        <?php echo $messagingUser->displayName?>
                                    </h3>
                                </div>
                                <div class="col-md-3">
                                    <h4>
                                        <?php echo $message->followers ?>
                                        <small>Followers</small>
                                    </h4>
                                </div>
                                <div class="col-md-9">
                                    <article>
                                        <?php echo $message->message ?>
                                    </article>
                                </div>
                                <div class="col-md-3">
                                    <ul style="list-style-type:none; padding:0;">
                                        <li>
                                            <small>
                                                <?php $locations = get_post_location($messagingUser->city);
                                                foreach($locations as $area){
                                                    if(($area->countryID) == 109 || ($area->countryID) == 295){
                                                        echo $area->city?>, <?php echo $area->stateCode;
                                                    }
                                                    else {
                                                        echo $area->city?>, <?php echo $area->countryCode;
                                                    }
                                                }
                                                ?>
                                            </small>
                                        </li>
                                            <button type="submit" class="btn btnbookinghighlight" onClick="allowHire(<?php echo $posting->posting_id.",'".wp_generate_password(25, false)."',".$message->userID ?>)">Hire</button>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
            <?php } ?>
        <div class="col-md-4">
            <div class="panel panel-default">
                <table class="table">
                    <tr>
                        <td>
                            <h5>Campaign Details</h5>
                        </td>
                    </tr>
                    <tr class="divider tableHeading">
                        <td>Description</td>
                    </tr>
                    <tr>
                        <td>
                            <a href="#" id="description" style="float:left" class="editable noEdit editing">
                                <?php echo '<h6 style="font-size:14px !important">'.$posting->postingDesc.'</h6>' ?>
                            </a>
                            <i class="edit fa fa-pencil"></i>
                        </td>
                    </tr>
                    <tr class="divider tableHeading">
                        <td>Minimum Followers</td>
                    </tr>
                    <tr>
                        <td>
                            <a href="#" id="followers" style="float:left" class="editable noEdit editing">
                                <?php echo $posting->minFollowers?>
                            </a>
                            <i class="edit fa fa-pencil"></i>
                        </td>
                    </tr>
                    <tr class="divider tableHeading">
                        <td>Location</td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="location" id="locationID" style="display:none" />
                            <a href="#" id="campaignLocation" style="float:left" class="editable noEdit editing">
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
                            </a>
                            <i class="edit fa fa-pencil"></i>
                        </td>
                    </tr>
                    <tr class="divider tableHeading">
                        <td>Industry</td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $posting->industry_name?>
                        </td>
                    </tr>
                    <tr class="divider tableHeading">
                        <td>Social Media Platform</td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $posting->Social_name?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <?php } ?>
        <div class="alert alert-danger" role="alert" style=<?php if(!is_null($ID)) echo "display:none"; ?>>
            <?php if(is_null($ID))
                      echo "No ID provided";
                  elseif($posting->user_id <> get_current_user_id())
                      echo "You cannot edit this campaign"; ?>


        </div>

    </div>
    <!-- /.container -->

</div>
<!-- /#content -->
<?php
    }
    elseif ((isset($posting) || !empty($posting)) && $posting->user_id <> get_current_user_id())
    {
?>
>
<div id="content" style="min-height:300px; background-color: #f0f0f0">
    <div class="container">
        <div class="alert alert-danger" role="alert">
            <?php  echo "You can not edit this campaign";?>
        </div>

    </div>
    <!-- /.container -->

</div>
<?php
    }
    elseif (!isset($posting) || empty($posting))
    {
?>>
<div id="content" style="min-height:300px; background-color: #f0f0f0">
    <div class="container">
        <div class="alert alert-danger" role="alert">
            <?php  echo "This campaign does not exist";?>
        </div>

    </div>
    <!-- /.container -->

</div>
<?php
    }
}
else
{?>
<div id="content" style="min-height:300px; background-color: #f0f0f0">
    <div class="container">
        <?php if($ID > 0)
                  echo $_GET["ID"];?>
        <div class="alert alert-danger" role="alert" style=<?php if(!is_null($ID)) echo "display:none"; ?>>
            <?php if(is_null($ID)) echo "No ID provided"?>
        </div>

    </div>
    <!-- /.container -->

</div>
<?php
}
jobboard_create_gmaps( 'jobboard-gmaps' );

?>

<?php get_footer(); ?>