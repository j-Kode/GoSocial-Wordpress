<?php

/**

 * Template Name: Campaign Detail

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
    if(isset($posting) && !empty($posting))
    {

?>


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
        <?php if($ID > 0) {?>
                <div class="col-md-8">
                <div class="se-pre-con col-md-12"></div>
                <div id="messagePanel" class="panel panel-default">
                    <table class="table myCampaigns">
                        <tr>
                            <td colspan="2">
                                Proposals: <?php echo getNoProposals($ID); ?>
                            </td>

                        </tr>
                            <?php $messages = getPostingMessages($ID, $posting->SocialMediaType);
                            foreach($messages as $message)
                            {
                            $messagingUser = get_userdata($message->userID);
                            ?>
                        <tr >
                            <td style="padding-left: 25px;">
                                    <h5>
                                        @<?php echo $messagingUser->username?>
                                    </h5>
                            </td>
                                <td class="text-right" style="padding-right:25px;">
                                    <h5>
                                        <?php echo $message->followers ?>
                                        <small>Followers</small>
                                    </h5>
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
                                <?php echo '<h6 style="font-size:14px !important">'.$posting->postingDesc.'</h6>' ?>
                        </td>
                    </tr>
                    <tr class="divider tableHeading">
                        <td>Minimum Followers</td>
                    </tr>
                    <tr>
                        <td>
                                <?php echo $posting->minFollowers?>
                        </td>
                    </tr>
                    <tr class="divider tableHeading">
                        <td>Location</td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="location" id="locationID" style="display:none" />
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
    </div>
    <!-- /.container -->

</div>
<!-- /#content -->
<?php } elseif (!isset($posting) || empty($posting)) {?>
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