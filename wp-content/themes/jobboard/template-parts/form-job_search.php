<?php
/**
 * Template Part Name : Job Search Form
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 *
 */
?>
<script>

 jQuery(function() {
        jQuery('#datetimepicker1').datetimepicker({

          collapse: true,
		  format: 'dd/MM/yyyy',
		  showTodayButton: true,
		  pickTime: false,
		  keepOpen: false
        });
		jQuery('#datetimepicker2').datetimepicker({

          collapse: true,
		  format: 'dd/MM/yyyy',
		  showTodayButton: true,
		  pickTime: false,
		  keepOpen: false
        });
      });

</script>
<div id="job-search">
<div class="homeSearchBanner" style="min-height:500px">
	<div class="container">
		<div class="job-search-wrapper">
				
				
								<div id="search-text-input" class="row">
			<div class="col-md-12">
			    <h2 class="job-search-title">WANT TO GROW YOUR BRAND?</h2>
			    <h5 class="job-search-subtitle">Post a campaign and find the right influencers for your brand within minutes!</h5>  
			</div>
            <div class="col-md-12 text-center" style="margin-bottom:85px">
                <button class="btn btn-lg btnbookinghighlight" onClick="parent.location='<?php echo esc_url( get_permalink( get_page_by_title( 'Hire Influencers' ) ) ); ?>'">Post a Campaign</button>
            </div>
				<form id="job-search-form" role="form" action="<?php echo esc_url( jobboard_get_permalink( 'job_search' ) ); ?>" method="post">
			<div class="col-md-9 col-md-offset-2">
			<div class="col-md-10 homeSearchContainer">
			<div class="col-md-12 homeSearchContainer">
						<div class="form-group has-feedback col-md-6 homeSearchContainer">
							<select style="height:50px !important" name="industryID" id="industryType" class="homeSeach profileInput" required="required" placeholder="Select A Category">
											<option value="0">-- Select Industry --</option>
											<?php $industryTypes= get_industry_types();
											foreach( $industryTypes as $industryType){
												$selected='';
												echo '<option value="'.$industryType->industry_id.'"'.$selected.'>'.stripslashes($industryType->industry_name).'</option>';
											}?>
							</select>
						</div>
						<div class="form-group has-feedback col-md-6 homeSearchContainer">
							<input type="text" name="location" id="locationID" style="display:none"/>
							<input style="height:50px !important" class="homeSeach profileInput" required="required" autocomplete="off" onkeyup="suggest(this.value);" onblur="hide();" type="text" id="locationName" placeholder="Where?" />
							<ul id="location-results"  class="autocomplete-results ng-scope" ng-show="isVisible" ng-style="resultsStyle" style="display:none; width: 198px; min-width: 198px;">
							</ul>
						</div>
					</div>
				
				</div>
				<div class="col-md-2 homeSearchContainer">
					<button class="btn btn-default homeSearchBtn" type="submit" name="searchSubmit" style="height:50px"><span class="fa fa-search fa-2x"></span></button>
				</div>
				</div>
				</div>
				
				<div style="display:none">
					
						<select class="init-slider" name="experience_min" id="experience_min" style="display:none">
							<?php
								$new_structure = array();
								$exp = explode( "\n", jobboard_option( 'experience_parameters' ) );
								foreach( $exp as $child ){
									$numbers = explode( ';', $child );
									echo '<option value="'.esc_attr( $numbers[0] ).'">'.esc_attr( $numbers[1] ).'</option>';
								}
								
					
							?>
						</select>
						
					
				<!-- /.experience -->
			
						<select class="init-slider" name="sallary_min" id="sallary_min" style="display:none">
							<?php
								$new_structure = array();
								$exp = explode( "\n", jobboard_option( 'salary_parameters' ) );
								foreach( $exp as $child ){
						
									$numbers = explode( ';', $child );
									echo '<option value="'.esc_attr( $numbers[0] ).'">'.esc_attr( $numbers[1] ).'</option>';
								}
					
							?>
						</select>
						
						
						</div>
				
				<div class="row">
				
				</div>
			</form><!-- /#job-search-form -->
		</div><!-- /.job-search-wrapper -->
	</div><!-- /.container -->
	</div>
</div><!-- /#job-search -->