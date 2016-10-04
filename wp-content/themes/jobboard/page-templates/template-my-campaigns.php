<?php


/**
 * Template Name: My Campaigns
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 *



 */


?>


<?php get_header();
$userInfo = get_userdata(get_current_user_id());
?>
    <script>
        $(document).ready(function () {
            var dashboardID = <?php echo($_GET["myp"]) ?>;
            if (dashboardID == 1) {
                $('.influencerTab').show();
                $("label[for='influencerViewOption']").addClass("activeView");
                $("#myProposals").addClass("dashboard-tabs-active");
                $('#myProposalsTable').fadeIn(100);
            }
            else {
                $('.employerTab').show();
                $("label[for='employerViewOption']").addClass("activeView");
                $("#activeCampaigns").addClass("dashboard-tabs-active");
                $('#activeCampaignsTable').fadeIn(100);
            }
            $("label[for='employerViewOption']").click(function () {
                $(this).addClass("activeView");
                $("label[for='influencerViewOption']").removeClass("activeView");
                $('.employerTab').show();
                $('.influencerTab').hide();
                if ($("#activeCampaignsTable").css('display') == 'none') {
                    hideTables(function () {
                        $('#activeCampaignsTable').fadeIn(100);
                        $("#activeCampaigns").addClass("dashboard-tabs-active")
                    });
                }
            });

            $("label[for='influencerViewOption']").click(function () {
                $(this).addClass("activeView");
                $("label[for='employerViewOption']").removeClass("activeView");
                $('.employerTab').hide();
                $('.influencerTab').show();
                if ($("#myProposalsTable").css('display') == 'none') {
                    hideTables(function () {
                        $('#myProposalsTable').fadeIn(100);
                        $("#myProposals").addClass("dashboard-tabs-active")
                    });
                }
            });

            function hideTables(callback) {
                $('.dashboard-tabs').children().removeClass("dashboard-tabs-active");
                $('#campaignPanel').children(':visible').fadeOut(100, function () {
                    callback(true);
                });
            }

            $("#progressCampaigns").click(function () {
                if ($("#progressCampaignsTable").css('display') == 'none') {
                    hideTables(function () {
                        $('#progressCampaignsTable').fadeIn(100);
                        $("#progressCampaigns").addClass("dashboard-tabs-active")
                    });
                }

            });
            $("#activeCampaigns").click(function () {
                if ($("#activeCampaignsTable").css('display') == 'none') {
                    hideTables(function () {
                        $('#activeCampaignsTable').fadeIn(100);
                        $("#activeCampaigns").addClass("dashboard-tabs-active")
                    });
                }
            });
            $("#currentWork").click(function () {
                if ($("#currentWorkTable").css('display') == 'none') {
                    hideTables(function () {
                        $('#currentWorkTable').fadeIn(100);
                        $("#currentWork").addClass("dashboard-tabs-active")
                    });
                }
            });
            $("#myProposals").click(function () {
                if ($("#myProposalsTable").css('display') == 'none') {
                    hideTables(function () {
                        $('#myProposalsTable').fadeIn(100);
                        $("#myProposals").addClass("dashboard-tabs-active")
                    });
                }
            });
            $("#completedCampaigns").click(function () {
                if ($("#completedCampaignsTable").css('display') == 'none') {
                    hideTables(function () {
                        $('#completedCampaignsTable').fadeIn(100);
                        $("#completedCampaigns").addClass("dashboard-tabs-active")
                    });
                }
            });
            $("#completedWork").click(function () {
                if ($("#completedWorkTable").css('display') == 'none') {
                    hideTables(function () {
                        $('#completedWorkTable').fadeIn(100);
                        $("#completedWork").addClass("dashboard-tabs-active")
                    });
                }
            });
        });

        function deletePosting(postingID)
        {
            if (confirm("Are you sure you want to delete this campaign?")) {
                jQuery.ajax({
                    type: "POST",
                    url: "<?php echo get_site_url(); ?>/post.php",
                    data: {DeleteID: postingID, DeleteGuid: "<?= $userInfo->user_guid ?>"},
                    success: function (data) {
                        if (data) {
                            $("#ac" + postingID).fadeOut('slow');
                        }
                    }
                });
            }
            return false;
        }
        function AcceptCampaign(postingID)
        {
                jQuery.ajax({
                    type: "POST",
                    url: "<?php echo get_site_url(); ?>/post.php",
                    data: {acceptID: postingID, acceptGuid: "<?= $userInfo->user_guid ?>"},
                    success: function (data) {
                        if (data) {
                            $("#notAccepted" + postingID).fadeOut('slow', function(){
                                $("#accepted" + postingID).fadeIn('slow');
                            });
                        }
                    }
                });
        }
        function retractProposal(postingID) {
            if (confirm("Are you sure you want to retract this proposal?")) {
                jQuery.ajax({
                    type: "POST",
                    url: "<?php echo get_site_url(); ?>/post.php",
                    data: {retractID: postingID, retractGuid: "<?= $userInfo->user_guid ?>"},
                    success: function (data) {
                        if (data) {
                            $("#mp" + postingID).fadeOut('slow');
                        }
                    }
                });
            }
            return false;
        }

        function completeCampaign(postingID){
            if(confirm("Are you sure you want to submit this campaign for approval?")){
                jQuery.ajax({
                    type: "POST",
                    url: "<?php echo get_site_url(); ?>/post.php",
                    data: {completeID: postingID, completeGuid: "<?= $userInfo->user_guid ?>"},
                    success: function (data) {
                        if (data) {
                            $("#completed" + postingID).fadeOut('slow', function(){
                                $("#cl" + postingID).fadeIn('slow');
                            });
                        }
                    }
                });
            }
        }

    </script>

    <div id="page-title-wrapper" style="border-bottom: 1px solid #C5C5C5;">


        <div class="container">
            <div class="col-md-12">
                <h1 class="page-title" style="font-size: 28px; padding-top: 44px">My Campaigns</h1>
            </div>
        </div>
        <div class="container" style="color:#fff;">
            <div class="col-md-12">
                <?php custom_breadcrumbs(); ?>
                <div class="ViewSelector">
                    <label for="employerViewOption">Employer</label>
                    <input id="gsmViewOption" type="radio" checked="checked"/>
                    <label for="influencerViewOption">Influencer</label>
                    <input id="gsmViewOption" type="radio"/>
                </div>
            </div>
        </div>
    </div>

    <!-- /#page-title -->


    <div id="content" style="background-color: #f0f0f0">

        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">

                    <div class="dashboard-tabs">
                        <div id="activeCampaigns" class="employerTab hvr-underline-reveal">Active Campaigns</div>
                        <div id="progressCampaigns" class="employerTab hvr-underline-reveal">Campaigns in Progress</div>
                        <div id="completedCampaigns" class="employerTab hvr-underline-reveal">Completed Campaigns</div>
                        <div id="myProposals" class="influencerTab hvr-underline-reveal">My Proposals</div>
                        <div id="currentWork" class="influencerTab hvr-underline-reveal">Current Work</div>
                        <div id="completedWork" class="influencerTab hvr-underline-reveal">Completed Work</div>
                        <div style="clear:both;float:none"></div>
                    </div>

                    <div id="campaignPanel" class="panel panel-default">

                        <table id="activeCampaignsTable" style="display:none" class="table myCampaigns">

                            <tr>

                                <td class="col-sm-4">NAME</td>

                                <td class="col-sm-2">MESSAGES</td>

                                <td class="col-sm-2">AVG FOLLOWERS</td>

                                <td class="col-sm-2">POSTING DATE</td>

                                <td class="col-sm-2">ACTION</td>

                            </tr>

                            <?php

                            $postings = getUserPostings(get_current_user_id());


                            foreach ($postings as $posting)

                            {
                            $messages = getPostingMessagesCount($posting->posting_id);
                            $avgFollowers = getAverageFollower($posting->posting_id); ?>

                            <tr id="ac<?php echo $posting->posting_id ?>">

                                <td>
                                    <a href="<?php echo get_site_url() . "/editcampaign?ID=" . $posting->posting_id?>"><?php echo $posting->postingTitle ?></a></td>

					<td><?php echo $messages; ?></td>

					<td><?php echo(count($avgFollowers) > 0 ? floor($avgFollowers) : "0"); ?></td>

					<td><?php echo $posting->postingDate ?></td>

					<td>
					<div  class="btn-group" style="min-width:150px">
                                    <button type="button" id="<?php echo $posting->posting_id ?>" onclick="deletePosting(this.id)" class="btn btn-danger">Delete</button>
                                    <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                        <span class="caret"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="#">Update</a></li>
                                        <li><a href="#">View</a></li>
                                    </ul>
                    </div>
                    </td>


                    </tr>

                    <?php } ?>

                    </table>
                    <table id="progressCampaignsTable" style="display:none" class="table myCampaigns">

                        <tr>

                            <td class="col-sm-3">CAMPAIGN NAME</td>

                            <td class="col-sm-2">INFLUENCER</td>

                            <td class="col-sm-1">FOLLOWERS</td>

                            <td class="col-sm-2">DEADLINE</td>

                            <td class="col-sm-2">AMOUNT</td>

                            <td class="col-sm-2">ACTION</td>

                        </tr>

                        <?php

                        $postings = getCampaignsInProgress(get_current_user_id());


                        foreach ($postings as $posting)

                        {
                        ?>

                        <tr>

                            <td>
                                <a href="<?php echo get_site_url() . "/editcampaign?ID=" . $posting->posting_id?>"><?php echo $posting->postingTitle ?></a></td>
					
					<td><?php echo get_user_meta($posting->ID, 'displayName', true); ?></td>

					<td><?php echo $posting->followers ?></td>

					<td><?php echo $posting->deadLineDate ?></td>
					
					<td><?php echo $posting->paymentAmount ?></td>

					<td>
                        <div class="btn-group" style="min-width:150px">
                            <button type="button" id="<?php echo $posting->posting_id ?>" onclick="revokeCampaign(this.id)" class="btn btn-info">Revoke</button>
                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="#">View Details</a></li>
                            </ul>
                        </div>
                    </td>

				</tr>

				<?php } ?>

			</table>
			<table id="myProposalsTable" style="display:none" class="table myCampaigns">

                        <tr>

                            <td class="col-sm-4">CAMPAIGN NAME</td>

                            <td class="col-sm-2">EMPLOYER</td>

                            <td class="col-sm-2">PRICE</td>

                            <td class="col-sm-2">DATE</td>

                            <td class="col-sm-2">ACTION</td>

                        </tr>

                        <?php

                        $postings = getContactsSent(get_current_user_id());


                        foreach ($postings as $posting) {
                            ?>

                            <tr  id="mp<?php echo $posting->posting_id ?>">

                                <td><?php echo $posting->postingTitle ?></td>

                                <td><?php echo get_user_meta($posting->user_id, 'company', true); ?></td>

                                <td><?php echo $posting->paymentAmount ?></td>

                                <td><?php echo $posting->datetime ?></td>

                                <td>
                                    <div class="btn-group" style="min-width:150px">
                                        <button type="button" onclick="retractProposal(<?php echo $posting->posting_id ?>)" class="btn btn-warning">Retract</button>
                                        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="#">View Details</a></li>
                                        </ul>
                                    </div>
                                </td>

                            </tr>

                        <?php } ?>

                    </table>
                    <table id="currentWorkTable" style="display:none" class="table myCampaigns">

                        <tr>

                            <td class="col-sm-3">CAMPAIGN NAME</td>

                            <td class="col-sm-2">EMPLOYER</td>

                            <td class="col-sm-1">PRICE</td>

                            <td class="col-sm-2">DEADLINE</td>

                            <td class="col-sm-2">STATUS</td>

                            <td class="col-sm-2">ACTION</td>

                        </tr>

                        <?php

                        $postings = getCurrentWork(get_current_user_id());

                        if (isset($postings) AND !empty($postings)) {

                        foreach ($postings as $posting) {
                        ?>

                        <tr>

                            <td><?php echo $posting->postingTitle ?></td>

                            <td><?php echo get_user_meta($posting->user_id, 'company', true); ?></td>

                            <td><?php echo $posting->paymentAmount ?></td>

                            <td><?php echo $posting->deadLineDate ?></td>

                            <td>
                            <?php if ($posting->isAccepted == 0 and $posting->isCancelled == 0)
                                echo "Not Accepted";
                            else if ($posting->isAccepted == 1 and $posting->isCompleted == 0)
                                echo "Accepted";
                            else if ($posting->isCompleted == 1 and $posting->isApprovedCompletion == 0)
                                echo "Awaiting Approval";
                            else
                                echo "Cancelled";

                            ?>
                            <td>


                                <?php if ($posting->isAccepted == 0 and $posting->isCancelled == 0) { ?>
                                <div id="notAccepted<?php echo $posting->posting_id ?>" class="btn-group"
                                     style="min-width:150px">
                                    <button type="button" onclick="AcceptCampaign(<?php echo $posting->posting_id ?>)"
                                            class="btn btn-success">Accept
                                    </button>
                                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                        <span class="caret"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="#">Decline</a></li>
                                        <li><a href="#">View Details</a></li>
                                    </ul>
                                </div>
                                <div id="accepted<?php echo $posting->posting_id ?>" class="btn-group" style="min-width:150px; display:none">
                                <button type="button" onclick="completeCampaign(<?php echo $posting->posting_id ?>)"
                                        class="btn btn-success">Complete
                                </button>
                                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="#">View Details</a></li>
                                </ul>
                </div>
                <?php } else if ($posting->isAccepted = 1 and $posting->isCompleted == 1) { ?>
                    <div id="completed<?php echo $posting->posting_id ?>" class="btn-group" style="min-width:150px">
                        <button type="button" onclick="completeCampaign(<?php echo $posting->posting_id ?>)"
                                class="btn btn-success">Complete
                        </button>
                        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="#">View Details</a></li>
                        </ul>
                        div>
                    </
                    <span id="cl<?php echo $posting->posting_id ?>" style="display:none"
                          class='notAccepted'>Completed</span>div>
                <?php } else { ?>
                    <span class='notAccepted'>Completed</span>
                    <?php ?>
                    </td>

                    </tr>
                <?php }
                }
                }
                else {
                            ?>
                            <tr>
                                <td colspan="6" class="text-center">
                                    <a class="col-xs-12" href="/" style="border: 5px dotted; padding:100px 10px;">Su     a Proposal</a>
                                </td>
                            </tr>
                        <?php } ?>

                    </table>
                    <table id="completedWorkTable" style="display:none" class="table myCampaigns">

                        <tr>

                            <td class="col-sm-4">CAMPAIGN NAME</td>

                            <td class="col-sm-3">EMPLOYER</td>

                            <td class="col-sm-1">PRICE</td>

                            <td class="col-sm-2">COMPLETED</td>

                            <td class="col-sm-2">ACTION</td>

                        </tr>

                        <?php

                        $postings = getCompletedWork(get_current_user_id());


                        foreach ($postings as $posting) {
                            ?>

                            <tr>

                                <td><?php echo $posting->postingTitle ?></td>

                                <td><?php echo get_user_meta($posting->user_id, 'company', true); ?></td>

                                <td><?php echo $posting->paymentAmount ?></td>

                                <td><?php echo $posting->completedDate ?></td>

                                <td>Actions Here</td>

                            </tr>

                        <?php } ?>

                    </table>
                    <table id="completedCampaignsTable" style="display:none" class="table myCampaigns">

                        <tr>

                            <td class="col-sm-4">CAMPAIGN NAME</td>

                            <td class="col-sm-2">INFLUENCER</td>

                            <td class="col-sm-2">COMPLETED</td>

                            <td class="col-sm-2">AMOUNT</td>

                            <td class="col-sm-2">ACTION</td>

                        </tr>

                        <?php

                        $postings = getCompletedCampaigns(get_current_user_id());


                        foreach ($postings as $posting)

                        {
                        ?>

                        <tr>

                            <td>
                                <a href="<?php echo get_site_url()."/editcampaign?D" . $posting->posting_id?>"><?php echo $posting->postingTitle ?></a>
                                </td>
					<td><?php echo get_user_meta($posting->ID, 'displayName', true); ?></td>

					<td><?php echo $posting->completedDate ?></td>
					
					<td><?php echo $posting->paymentAmount ?></td>

					<td>Actions Here</td>

				</tr>

				<?php } ?>

			</table>

		</div>  

        </div>

	</div>
</div>

<!-- /#content -->

<?php
jobboard_create_gmaps('jobboard-gmaps');
?>

<?php get_footer(); ?>