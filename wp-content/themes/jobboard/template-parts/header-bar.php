<?php
/**
 * Template Part Name : Top Header Bar
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 *
 */
 if(is_user_logged_in()){

?><script>
jQuery(document).ready(function(){
	getNotifications();
	setInterval(getNotifications(), 3000);
});
 function getNotifications() {
        jQuery.ajax({
            type: "POST",
            url: "<?php echo get_site_url(); ?>/notification.php",
            data: {info: <?=get_current_user_id()?> },
            success: function (data) {
                if(data){
                    jQuery('#notificationBell').addClass("highlightNotify");
			        jQuery('#notificationList').html(unescape(data));
                }
                else{
                    jQuery('#notificationBell').removeClass("highlightNotify");
                    jQuery('#notificationList').html("");
                }
                    
            }
        })
    }
    </script>
<nav class="navbar navbar-default navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">GoSocialMedia</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <?php
    							$menu_args = array(
    								'theme_location' => 'primary_menu',
    								'container' => false,
    								'menu_class' => 'nav navbar-nav',
    								'fallback_cb' => '__return_false',
    							);
    							wp_nav_menu($menu_args);
    						?>
          <ul class="nav navbar-nav navbar-right">
		   <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
			  My Dashboard
			  <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li class="dropdown-header"><h5>Dashboards</h5><div style="clear:both"></div></li>
                <li role="separator" class="divider" style="margin:0"></li>
                <ul class="notificationList" style="padding:0px">
				 <a href="/my-dashboard?myp=2"><li>My Campaigns</li></a>
				 <a href="/my-dashboard?myp=1"><li>My Proposals</li></a>
                </ul>
              </ul>
            </li>
		  <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
			  <?php 
			  $currentBalance = getBalance(get_current_user_id()); 
			  echo "CAD $".substr($currentBalance->currentBalance,0, strrpos($currentBalance->currentBalance, "."))."<sup class=\"cents\">".substr($currentBalance->currentBalance, (strlen($currentBalance->currentBalance) - 2))."</sup>";
			  
			  ?>
			  
			  <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li class="dropdown-header"><h5>Balances<div style="float:right"><?php echo "$ ".$currentBalance->currentBalance?></div></h5><div style="clear:both"></div></li>
                <li role="separator" class="divider" style="margin:0"></li>
                <ul id="FundsOptions" class="notificationList" style="padding:0px">
				 <a href="/payments"><li>Deposit Funds</li></a>
                </ul>
              </ul>
            </li>
             <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i id="notificationBell" class="fa fa-bell-o fa-lg"></i><span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li class="dropdown-header"><h5>NOTIFICATIONS</h5></li>
                <li role="separator" class="divider" style="margin:0"></li>
                <ul id="notificationList" class="notificationList" style="padding:0px">
                </ul>
              </ul>
            </li>
            <li>
            <div class="user_menu dropdown">
            <?php
				echo get_avatar( get_current_user_id(), 35 ).'<span> '.__( 'Hi, ', 'jobboard' ).esc_attr( get_userdata( get_current_user_id() )->first_name ).' '.esc_attr( get_userdata( get_current_user_id() )->last_name ).'</span>';
				
			?>
            </div>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    
<?php
}
?>