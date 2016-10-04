<?php

/**
 * Template Name: Homepage
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 *
 */

//require_once( get_template_directory().'/includes/frontend-submission/form-submit.php' );
get_header(); 
if(is_user_logged_in())
{
	$current_user = wp_get_current_user();
}
?>

<script>

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
</script>
<?php
	if( jobboard_option('enable_homepage_slider') ){
		get_template_part( 'template-parts/homepage', 'slider' );
	}//endif;
	
?>

<?php
	get_template_part( 'template-parts/form', 'job_search' );
?>
<div id="post-a-job">
	<div class="container">
		<?php
			if(isset($_GET['required'])){
				$required = $_GET['required'];
			}
			$page_title = __( 'Post Here', 'jobboard' );
			$default = array(
				'post_id' => '',
				'job_title' => '',
				'job_country' => 109,
				'job_state' => '',
				'job_city' => '',
				'job_type' => '',
				'job_category' => '',
				'job_experience' => '',
				'job_sallary' => '',
				'job_summary' => '',
				'job_overview' => '',
				'job_description' => '',
				'company' => '',
				'posting_date' => '',
				
			);
			
			
			
			if( isset( $_GET['action'] ) && $_GET['action'] == 'edit' ){
				$page_title = __( 'EDIT JOB', 'jobboard' );
				$edit = get_post( $_GET['jid'] );
				
				// Get job region
				$job_country = wp_get_post_terms( $edit->ID, 'job_country' );
				$job_state = wp_get_post_terms( $edit->ID, 'job_state' );
				$job_city = wp_get_post_terms( $edit->ID, 'job_city' );
				$job_type = wp_get_post_terms( $edit->ID, 'job_type' );
				$job_category = wp_get_post_terms( $edit->ID, 'job_category' );
				
				$job = array(
					'post_id' => $edit->ID,
					'job_title' => $edit->post_title,
					'job_country' => $job_country[0]->country,
					'job_state' => $job_state[0]->state,
					'job_city' => $job_city[0]->city,
					'job_type' => $job_type[0]->slug,
					'job_category' => $job_category[0]->slug,
					'job_experience' => get_post_meta( $edit->ID, '_jboard_job_experiences', true ),
					'job_sallary' => get_post_meta( $edit->ID, '_jboard_job_sallary', true ),
					'job_summary' => get_post_meta( $edit->ID, '_jboard_job_summary', true ),
					'job_overview' => get_post_meta( $edit->ID, '_jboard_job_overview', true ),
					'job_description' => $edit->post_content,
					'company' => get_post_meta( $edit->ID, '_jboard_job_company', true ),
				);
				
				$default = wp_parse_args( $job, $default );
			}
		?>
<div class="col-md-12">
<!--<h1 class="page-title"><i class="fa fa-hand-o-down fa-2x" style="margin-right:8px"></i>Recent Posts</h1>-->
</div>
</div><!-- /.container -->
</div><!-- /#page-title -->
<?php
	get_template_part( 'template-parts/homepage', 'jobs_listing' );
?>


<?php
	if( jobboard_option('enable_job_status') ){
		get_template_part( 'template-parts/homepage', 'job_stats' );
	}//endif;
?>

<?php
	if( jobboard_option('enable_job_steps') ){
		get_template_part( 'template-parts/homepage', 'job_step' );
	}//endif;
?>

<?php
//	if( jobboard_option('enable_testimonial') ){
//		get_template_part( 'template-parts/homepage', 'testimonials' );
//	}//endif;
?>

<?php
//	if( jobboard_option('enable_company') ){
//		get_template_part( 'template-parts/homepage', 'company' );
//	}//endif;
?>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
	<form action="#" method="post" enctype="multipart/form-data">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">New Posting</h4>
      </div>
      <div class="modal-body">
		<div class="col-md-6">
	      <label class="control-label col-sm-6">Title</label>
           <div class="input-group col-md-6">
			<!--<input type="text" style="display:none" name="userid" value="<?php echo $current_user->ID;?>">-->
			  <input type="text" class="form-control" name="postingTitle" required placeholder="Title" aria-describedby="Tile">
		   </div>
		  <label class="control-label col-sm-6">Industry:</label>
           <div class="input-group col-md-6">
			  <select name="industryID" id="industryType" class="profileInput" required="required" placeholder="Select A Category">
			  <option value=""><?php echo '-- '.__( 'Select Industry', 'jobboard' ).' --'; ?></option>
											<?php $industryTypes= get_industry_types();
											foreach( $industryTypes as $industryType){
												$selected='';
												echo '<option value="'.$industryType->industry_id.'"'.$selected.'>'.stripslashes($industryType->industry_name).'</option>';
											}?>
				</select>
		   </div>
           <label class="control-label col-sm-6">Pricing:</label>
           <div class="input-group col-md-6">
			  <select name="pricingID" id="pricingType" class="profileInput" required="required" placeholder="Select A Category">
			  <option value=""><?php echo '-- '.__( 'Select Pricing', 'jobboard' ).' --'; ?></option>
											<?php $paymentTypes= get_paymentTypes();
											foreach( $paymentTypes as $paymentType){
												$selected='';
												echo '<option value="'.$paymentType->ID.'"'.$selected.'>'.stripslashes($paymentType->Pricing_Name).'</option>';
											}?>
				</select>
		   </div>
			<label class="control-label col-sm-6">Country</label>
			<div class="input-group col-md-6">
						<select name="country" id="country" class="profileInput" required="required" onChange="get_states(this.value)">
							<option value=""><?php echo '-- '.__( 'Select Region', 'jobboard' ).' --'; ?></option>
						<?php
							$countries= get_countries();
							foreach( $countries as $country){
								if( $user_info->country == $country->id){
									$selected = 'selected';
								}
								else
									$selected = '';
								echo '<option value="'.$country->id.'" '.$selected.'>'.esc_attr($country->country).'</option>';
							}
						?>
						</select>
						</div>
		   <label class="control-label col-sm-6">State/Province:</label>
           <div class="input-group col-md-6">
						<select name="state" id="job_state" class="profileInput" required="required" onChange="get_Cities(this.value)">
						</select>
		   </div>
		   <label class="control-label col-sm-6">City:</label>
           <div class="input-group col-md-6">
			  <select name="postingCity" id="job_city" class="profileInput" required="required">
						</select>
		   </div>
		</div>
		<div class="col-md-6">
		    <label class="control-label col-sm-6">Minimum Followers:</label>
			<div class="input-group col-md-6">
				<input type="text" class="form-control" name="minFollowers" required placeholder="Followers" aria-describedby="experience">
			</div>
			  <label class="control-label col-sm-6">Media Platform:</label>
           <div class="input-group col-md-6">
			  <select name="mediaID" id="mediaType" class="profileInput" required="required" placeholder="Select A Category">
			  <option value=""><?php echo '-- '.__( 'Select Platform', 'jobboard' ).' --'; ?></option>
											<?php $mediaTypes= get_mediaPlatforms();
											foreach( $mediaTypes as $mediaType){
												$selected='';
												echo '<option value="'.$mediaType->ID.'"'.$selected.'>'.stripslashes($mediaType->Social_Name).'</option>';
											}?>
				</select>
          </div>          
		 <label class="control-label col-sm-6">Payment Amount:</label>
			<div class="input-group col-md-6">
				<input type="text" class="form-control" name="paymentAmount" required placeholder="Posting Payment" aria-describedby="experience">
			</div>

		<label class="control-label col-sm-6">Description:</label>
           <div class="input-group col-md-6">
			<textarea name="postingDesc" class="form-control custom-control" rows="3" style="resize:none"></textarea> 
		   </div>
      </div>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btnbookingClose" data-dismiss="modal">Close</button>
        <button type="Submit" class="btn btn-primary btnbooking"  name="add_posting" id="add_posting" value="1">Add Posting</button>
      </div>
	  </form>
    </div>
  </div>
</div>
<!-- CONTACT FORM -->
<div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
	<form action="#" method="post" enctype="multipart/form-data">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title" id="myModalLabel">Contact Brand</h3>
      </div>
      <div class="modal-body">
          <div class="col-md-12">
          <label class="control-label col-sm-3">Message:</label>
           <div class="input-group col-md-9">
			<textarea id="contactMessage" class="form-control custom-control" rows="5" style="resize:none"></textarea> 
		   </div>
           </div>
           <div class="col-md-12">
         <h2>
             <?php echo get_user_meta($current_user->ID, 'remainingMessages', true)."/".get_user_meta($current_user->ID, 'maximumMessages', true);?>
             </h2>Messages Left.
             </div>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btnbookingClose" data-dismiss="modal">Close</button>
        <?php 
            $remainingMessages = get_user_meta($current_user->ID, 'remainingMessages', true);
        if($remainingMessages > 0) {
            echo "<button type=\"Submit\" class=\"btn btn-primary btnbooking\" onclick=\"contactBrand('".$current_user->ID."')\">Send Message</button>";
        } 
        else{
	       echo "<button type=\"Submit\" class=\"btn btn-primary btnbooking\" disabled>Contact Now</button>"; 
        }?>
      </div>
	  </form>
    </div>
  </div>
</div>
<?php
get_footer();