/**
 * Custom JQuery for Load theme javascript plugins
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 */
 
jQuery(document).ready(function($){
    // Inside of this function, $() will work as an alias for jQuery()
    // and other libraries also using $ will not be accessible under this shortcut
    
    "use strict";
    
    /*-------------------------------------------*/
	/*	Homepage Slider	*/
	/*-------------------------------------------*/
	$(function(){
		var init_slider = slider.home_init;
		if(init_slider){
			$('#slider-wrapper').owlCarousel({
				items : 1,
				margin:0,
				dots:false,
				loop:true,
				lazyLoad:true,
				autoplay:home_slider.auto_play,
				autoplayTimeout:home_slider.auto_play_timeout,
				animateIn:home_slider.animate_in,
				animateOut:home_slider.animate_out,
			
			
			});
		}//endif;
	});
	
    $(function(){
    	var initSlider = slider.init;
    	if(initSlider){
			$('select#experience_min, select#experience_max').selectToUISlider({
				labels:10,
				labelSrc:'text',
				tooltip:true,
			});
			$('select#sallary_min, select#sallary_max').selectToUISlider({
				labels:11,
				labelSrc:'text',
				tooltip:true,
			});
		}
    });
    
    // Keep the images fit the container in smaller screen
    $(function(){
    	$('#slider-wrapper .owl-item').imgLiquid();    	
    });
    
    // Jobs Listings Tabs
    $(function(){
    	$('#job-listing-tabs').tabs({ hide: { effect: "fade", duration: 'fast' }, show: { effect: "fade", duration: 'fast' } });
    });
    
    // Testimonial Carousel
    $(function(){
    	var sync1 = $('#testimonials-wrapper'),
    		sync2 = $('#testimonials-caption');
    	
    	sync1.owlCarousel({
			items : 10,
			margin:5,
			center:true,
			loop:true,
			dots:false,
			nav:false,
			autoplay:true,
			autoplayTimeout:4000,
			autoWidth:true,
			mouseDrag:false,
			touchDrag:false,
			pullDrag:false,
			freeDrag:false,
		});
		
		function checkPosition(e){
			alert(e.item.index);
		}
    	sync2.owlCarousel({
    		mouseDrag:false,
			touchDrag:false,
			pullDrag:false,
			freeDrag:false,
			items : 1,
			dots:false,
			loop:true,
			nav:false,
			animateOut: 'fadeOutDown',
			animateIn: 'fadeInDown',
			
		});
		
		sync1.on('change.owl.carousel', function(event){
			sync2.trigger('next.owl.carousel');
		});
		
    });
    
    // Companies Listing Carousel
    $(function(){
    	$('.companies-listing-wrapper').owlCarousel({
			loop:true,
    		dots:false,
			nav:false,
			autoplay:true,
			autoplayTimeout:4000,
			autoWidth:true,
			mouseDrag:false,
			touchDrag:false,
			pullDrag:false,
			freeDrag:false,
			items:6,
    	});
    });
    
    // Featured Job Carousel
    $( function(){
    	$('.featured-job-wrapper').owlCarousel({
    		loop:true,
    		dots:false,
			nav:true,
			navText : ['&nbsp;','&nbsp;'],
			autoplay:true,
			autoplayTimeout:4000,
			autoWidth:true,
			items:3,
    	});
    });
    
    // Featured Job Widget
    $( function(){
    	$('.featured-job-widget').owlCarousel({
    		loop:true,
    		dots:false,
			nav:true,
			navText : ['&nbsp;','&nbsp;'],
			autoplay:true,
			autoplayTimeout:4000,
			autoWidth:true,
			items:1,
    	});
    });
    
    // Post resume repeated Form submission
    $(function(){
    	var i=1;
    	$('.btn-add-url').on( 'click', function(){
    		var form_id = $(this).attr("data-form-id");
    		var form_html = $(form_id).html();
    		var limit = $(this).attr("data-limit");
			var insert = '<div class="repeated-form">'+form_html+'</div>';
			$(insert).insertBefore(form_id).slideDown('fast');
			if(i==limit && limit != ''){
				$(this).prop( 'disabled', true );
			}
			i++;
    	});
    	

    	$(document).on('click', '.close-form', function(){
			var btn_id = $(this).attr("data-button-name");
			var btn_limit = $(this).attr("data-button-limit");
			
			$(this).parent().slideUp('fast', function(){
				$(this).remove();
			});
			if(i==btn_limit & btn_limit!=''){
				$(btn_id).prop('disabled', false);
			}
			i--;
		});

    });
    
    /*-------------------------------------------*/
	/*	AJAX Contact Form Function	*/
	/*-------------------------------------------*/
	$(function(){
		function success_status(){
			$('.contact-form-status').slideDown('fast');
			$('.btn-send-contact-form').button('reset');
			//$('#contact-resume-modal').delay(300).modal('hide');
			setTimeout(function() { $('#contact-resume-modal').modal('hide'); }, 2000);
		}
		
		function before_submit(){
			$('.btn-send-contact-form').button('loading');
		}
		
		var options = {
			resetForm:true,
			success:success_status,
			beforeSubmit:before_submit
		};
		$('#contact-form').ajaxForm(options);
		$('#contact-job-seeker').ajaxForm(options);
	});
	
	/*-------------------------------------------*/
	/*	AJAX delete post item function	*/
	/*-------------------------------------------*/
	$(function(){
		function before_submit(formData, jqForm, options){
			var queryString = $.param(formData);
			
			var form_id = jqForm.attr( 'data-post-id' );
			
			var bool = confirm('Are you sure?');
			if( bool == false ){
				return false;
			}
		}
		
		function on_success(responseText, statusText, xhr, $form){
			var response = responseText.getElementsByTagName("response_data")[0].childNodes[0].nodeValue;
			$('#list-item-'+response).fadeOut('normal', function(){
				$(this).remove();
			});
		}
		
		var options = {
			success:on_success,
			beforeSubmit:before_submit,
			dataType:'xml'
		};
		
		$('.jobboard_delete_item').ajaxForm(options);
		
	});
	
	/*-------------------------------------------*/
	/*	AJAX featured post item function	*/
	/*-------------------------------------------*/
	
	$(function(){
		
		function on_success(responseText, statusText, xhr, $form){
			var form = '<i class="fa fa-check"></i> Featured';
			$form.html(form);
		}
		
		var options = {
			success:on_success,
		}
		
		$('.jobboard_featured_item').ajaxForm(options);
		
	});
	
	/*-------------------------------------------*/
	/*	AJAX post status function	*/
	/*-------------------------------------------*/
	$(function(){
		
		function on_success(responseText, statusText, xhr, $form){
			$form.children('.application_icons').html('<i class="fa fa-check"></i>').delay('3000').fadeOut();
		}
		
		function before_submit(arr, $form, options){
			$form.append('<span class="application_icons"><i class="fa fa-refresh fa-spin"></i></span>');
		}
		
		var options = {
			success:on_success,
			beforeSubmit:before_submit
		}
		
		$('.application_status').on( 'change', function(){
			$(this).parent('.application_status_form').ajaxSubmit(options);
		});
		
	});
	
	/*-------------------------------------------*/
	/*	Cross Browser Function	*/
	/*-------------------------------------------*/
	$(function(){
		$('.disable').disable=true;
		$('#bookmark-button').popover();
	});
	
	/*-------------------------------------------*/
	/*	AJAX Bookmark	*/
	/*-------------------------------------------*/
	$(function(){
		
		function on_success(responseText, statusText, xhr, $form){
			var btn_ed = $('#bookmark-button').attr('data-on-success');
			//$('#bookmark-button').button('reset');
			$('#bookmark-button').html(btn_ed);
			
		}
		
		function before_submit(){
			$('#bookmark-button').button('loading');
		}
		
		var options = {
			success:on_success,
			beforeSubmit:before_submit
		}
		
		$('#bookmark-resume').ajaxForm(options);
		
	});
	
	
	/*-------------------------------------------*/
	/*	Javascript Dropdown Menu	*/
	/*-------------------------------------------*/
	$(function(){
		
		$('li.menu-item-has-children > a').on('click', function(e){
			e.preventDefault();
			$(this).next('.sub-menu').slideToggle('fast');	
		});
		
	});
	
});// JQuery Wrapper, Don't delete this line !!!
 