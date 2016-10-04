<?php
/**
 * Template Name: Profile Page
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 *
 */




get_header();
session_start();
$user_id = get_current_user_id();
$user_info = get_userdata($user_id);
require 'instagram.class.php';
require 'facebooksdk/src/Facebook/autoload.php';
require 'socialmedia.config.php';
require 'twitteroauth/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

$OAUTH_CALLBACK = 'http:\\www.gosocialmedia.ca\my-profile?twitter-success=1';

$connection = new TwitterOAuth($settings['consumer_key'], $settings['consumer_secret'], $settings['oauth_access_token'], $settings['oauth_access_token_secret']);
$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => $OAUTH_CALLBACK));
$twitterurl = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));

// Login URL
$loginUrl = $instagram->getLoginUrl();

$helper = $fb->getRedirectLoginHelper();
$FacebookloginUrl = "";
if(isset($_GET['facebook-success']))
{
    try {
        $accessToken = $helper->getAccessToken('http://www.gosocialmedia.ca/my-profile?facebook-success=1');
    }
    catch(Facebook\Exceptions\FacebookResponseException $e) {
        // When Graph returns an error
        echo 'Graph returned an error: ' . $e->getMessage();
    }
    catch(Facebook\Exceptions\FacebookSDKException $e) {
        // When validation fails or other local issues
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
    }

    if (isset($accessToken)) {
        // Logged in!
        $response = $fb->get('/me?fields=id,name,gender,age_range,email,bio', $accessToken);
        $friendsResponse = $fb->get('/me/friends', $accessToken);
        $friendsCount = $friendsResponse->getGraphEdge();
        $user = $response->getGraphObject();

        $email = $user['email'];
        $fullname = $user['name'];
        $bio = $user['bio'];
        //$location = $data->location;
        $token = $accessToken;
        $followers = $friendsCount->getTotalCount();
        $id = $user['id'];


        $facebooksuccess = verifySocialMediaAccount($email, $bio, NULL, $id, $token, $fullname, $user_id, $followers, 3, NULL, $email, NULL );

        // Now you can redirect to another page and use the
        // access token from $_SESSION['facebook_access_token']
    } elseif ($helper->getError()) {
        // The user denied the request
    }
}
else
{
    $permissions = ['email', 'public_profile','user_friends'];
    $FacebookloginUrl = $helper->getLoginUrl('http://www.gosocialmedia.ca/my-profile?facebook-success=1', $permissions);
}


if(isset($_GET['twitter-success']))
{
    $data = $connection->get("account/verify_credentials");
    $screenName = $data->screen_name;
    $fullname = $data->name;
    $desc = $data->description;
    $location = $data->location;
    $token = $request_token['oauth_token'];
    $followers = $data->followers_count;
    $following = $data->friends_count;
    $id = $data->id;


    $twitterSuccess = verifySocialMediaAccount($screenName, $desc, NULL, $id, $token, $fullname, $user_id, $followers, 2, $following, NULL, $location );
}


if(isset($_GET['insta-success']))
{

    $code = $_GET['code'];

    // Check whether the user has granted access
    if (true === isset($code))
    {

        // Receive OAuth token object
        $data = $instagram->getOAuthToken($code);

        if(empty($data->user->username))
        {
            header('Location: index.php');
        }
        else
        {
            session_start();
            // Storing instagram user data into session
            $_SESSION['userdetails']=$data;

            $url = 'https://api.instagram.com/v1/users/'.$data->user->id.'?access_token='.$data->access_token;
            $api_response = file_get_contents($url);
            $record = json_decode($api_response);

            $user=$data->user->username;
            $fullname=$data->user->full_name;
            $bio=$data->user->bio;
            $website=$data->user->website;
            $id=$data->user->id;
            $token=$data->access_token;
            $followers=$record->data->counts->followed_by;
            $following=$record->data->counts->follows;

            $instaSuccess = verifySocialMediaAccount($user, $bio, $website, $id, $token, $fullname, $user_id, $followers, 1, $following, NULL, NULL );
  
        }
    }
}

$instaExists = socialAccountExists("instagram",$user_id);
$twitterExists = socialAccountExists("twitter",$user_id);
$facebookExists = socialAccountExists("facebook",$user_id);

?>
<script>
    function enableEdit() {
        $('#location').editable({
            ajaxOptions: {
                beforeSend: function (xhr, s) {
                    s.data += "&locationID=" + $('#locationID').val();;
                }
            },
            type: 'text',
            pk: "<?php echo $user_id ?>",
            url: '/post.php',
            data: {},
            title: 'Enter thisisid',
            name: 'location',
            tpl: '<input id="locationName" style="float:right" onkeyup="suggest(this.value);" onblur="hide();" type="text"><ul id="location-results"  class="autocomplete-results ng-scope" ng-show="isVisible" ng-style="resultsStyle" style="display:none; float:right; margin-top:30px; height:auto; min-width: 198px; width:auto !important; padding-right:0px !important;"></ul>'
        });
         $('#name').editable({
            type: 'text',
            pk: "<?php echo $user_id ?>",
            url: '/post.php',
            name: 'displayName'
         });
         $('#email').editable({
            type: 'text',
            pk: "<?php echo $user_id ?>",
            url: '/post.php',
            name: 'email'
         });
         $('#company').editable({
            type: 'text',
            pk: "<?php echo $user_id ?>",
            url: '/post.php',
            name: 'company'
         });




    }
</script>
<div id="page-title-wrapper" style="border-bottom: 1px solid #C5C5C5;">

    <div class="container">
        <div class="col-md-12">

            <h1 class="page-title" style="padding-top: 44px; float:left">
                <?php echo $user_info->displayName?>

            </h1>
            <div class="page-title" style="padding-top: 74px;">
                <a style="float:right;" id="editProfile" class="btn btn-lg btnbookinghighlight" href="#">Edit Profile</a>
                <a style="float:right;display:none;" id="viewProfile" class="btn btn-lg btnbookinghighlight" href="#">View Profile</a>
            </div>

            <h5 style="margin-top: -20px;padding-bottom:15px;position: absolute; float:left;color:#fff;">
                Member since <?php
                             $datetime = new DateTime($user_info->user_registered);
                             echo $datetime->format('F')." ".$datetime->format('Y');?>

            </h5>

        </div>
    </div>
    <!-- /.container -->
</div>
<!-- /#page-title -->
<div id="content" style="background-color: #f0f0f0">
    <div class="container">
        <div class="col-md-8">
            <div class="panel panel-default">
                <table class="table myCampaigns">
                    <tr>
                        <td colspan="5">
                            <h5>My Profile</h5>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <h5>
                                <a href="#" id="name" style="float:left" class="editable noEdit editing">
                                    <?php echo $user_info->displayName;?>
                                </a>
                                <i class="edit fa fa-pencil"></i>
                            </h5>
                        </td>
                        <td colspan="3">
                            <h5>
                                <input type="text" name="location" id="locationID" style="display:none" />
                                <a href="#" id="location" style="float:left" class="editable noEdit editing">
                                   
                                    <?php $locations = get_post_location($user_info->city);
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
                            </h5>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="noRowBorder">
                            <h5>
                                <a href="#" id="email" style="float:left" class="editable noEdit editing">
                                    <?php echo $user_info->email?>
                                </a>
                                <i class="edit fa fa-pencil"></i>
                            </h5>
                        </td>
                        <td colspan="3" class="noRowBorder">
                            <h5>
                                <a href="#" id="company" style="float:left" class="editable noEdit editing">
                                    <?php echo $user_info->company?>
                                </a>
                                <i class="edit fa fa-pencil"></i>
                            </h5>
                        </td>
                    </tr>
                    <tr>
                        <td class="wp-submenu-head" colspan="5">
                            <strong>Instagram</strong>
                        </td>
                    </tr>
                    <?php if(isset($instaExists)) { ?>
                    <tr>
                        <td></td>
                        <td>
                            <?php echo $instaExists->username; ?>
                        </td>
                        <td>
                            <?php echo $instaExists->followers." Followers";?>
                        </td>
                        <td>209 Following</td>
                        <td>
                            <a class="btn btnbookinghighlight" href="#">Update</a>
                        </td>
                    </tr>
                    <?php } else { ?>
                    <tr>
                        <td class="text-center" colspan="5">
                            <h6>
                                <span class="label label-warning">Please verify your Instagram account</span>
                            </h6>
                        </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td class="wp-submenu-head" colspan="5">
                            <strong>Twitter</strong>
                        </td>
                    </tr>
                    <?php if(isset($twitterExists)) { ?>
                    <tr>
                        <td></td>
                        <td>
                            <?php echo $twitterExists->username; ?>
                        </td>
                        <td>
                            <?php echo $twitterExists->followers." Followers";?>
                        </td>
                        <td>
                            <?php echo $twitterExists->following." Following";?>
                        </td>
                        <td>
                            <a class="btn btnbookinghighlight" href="#">Update</a>
                        </td>
                    </tr>
                    <?php } else { ?>
                    <tr>
                        <td class="text-center" colspan="5">
                            <h6>
                                <span class="label label-warning">Please verify your Twitter account</span>
                            </h6>
                        </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td class="wp-submenu-head" colspan="5">
                            <strong>Facebook</strong>
                        </td>
                    </tr>
                    <?php if(isset($facebookExists)) { ?>
                    <tr>
                        <td></td>
                        <td>
                            <?php echo $facebookExists->username; ?>
                        </td>
                        <td>
                            <?php echo $facebookExists->followers." Followers";?>
                        </td>
                        <td>209 Following</td>
                        <td>
                            <a class="btn btnbookinghighlight" href="#">Update</a>
                        </td>
                    </tr>
                    <?php } else { ?>
                    <tr>
                        <td class="text-center" colspan="5">
                            <h6>
                                <span class="label label-warning">Please verify your Facebook account</span>
                            </h6>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <table class="table">
                    <tr>
                        <td>
                            <h5>Verifications</h5>
                        </td>
                    </tr>
                    <tr class="divider tableHeading">
                        <td>Instagram</td>
                        <?php if(!isset($instaExists)){  ?>
                        <td class="text-right">
                            <a class="btn btnbookinghighlight" href="<?php echo $loginUrl ?>">Verify</a>
                        </td>
                        <?php } else {?>
                        <td class="text-right">
                            <label class="verified">
                                <i class="fa fa-check-square"></i>
                                Verified
                            </label>
                        </td>
                        <?php }?>
                    </tr>
                    <tr class="divider tableHeading">
                        <td>Facebook</td>
                        <?php if(!isset($facebookExists)){  ?>
                        <td class="text-right">
                            <a class="btn btnbookinghighlight" href="<?php echo $FacebookloginUrl?>">Verify</a>
                        </td>
                        <?php } else {?>
                        <td class="text-right">
                            <label class="verified">
                                <i class="fa fa-check-square"></i>
                                Verified
                            </label>
                        </td>
                        <?php }?>
                    </tr>
                    <tr class="divider tableHeading">
                        <td>Twitter</td>
                        <?php if(!isset($twitterExists)){  ?>
                        <td class="text-right">
                            <a class="btn btnbookinghighlight" href="<?php echo $twitterurl?>">Verify</a>
                        </td>
                        <?php } else {?>
                        <td class="text-right">
                            <label class="verified">
                                <i class="fa fa-check-square"></i>
                                Verified
                            </label>
                        </td>
                        <?php }?>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>




<?php get_footer(); ?>