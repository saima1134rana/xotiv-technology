/*
jQuery( '#ttshowcase_form' ).submit(function( event ) {

	   event.preventDefault();

});
*/

jQuery(document).ready(function(){
	jQuery('#ttshowcase_form.tt_form_has_ajax').each( function(){
		var containerid = jQuery(this).attr('data-unique-id');
		jQuery(this).submit(function( event ) {
			event.preventDefault();
		 }).find('.tt_form_button').one('click',function(){
			 tt_ajax_form(containerid);
		 });

	});
});

function tt_ajax_form(containerid) {

 	var form = jQuery('[data-unique-id='+containerid+']');
	var wrapper = form.closest('.tt_form_container');
	
	form.fadeTo( "slow" , 0.1, function() {
		wrapper.addClass('tt_loading');
		var data = new FormData(form[0]);
	   data.append("label", "WEBUPLOAD");
	   data.append("action", "ttshowcase_ajax_form");

		 jQuery.ajax({
	            url: ajax_object.ajax_url,
	            type: "POST",
	            data: data,
	            contentType: false,      
				cache: false,            
				processData:false,  
	            success: function (response) {
					
				  var elOffset = wrapper.position().top;
				  var elHeight = wrapper.height();
				  var windowHeight = jQuery(window).height();
				  var offset;

				  if (elHeight < windowHeight) {
				    offset = elOffset - ((windowHeight / 2) - (elHeight / 2));
				  }
				  else {
				    offset = elOffset;
				  }

				  jQuery('html, body').animate({scrollTop:offset-100}, 700, function() {

				  	wrapper.html(response).removeClass('tt_loading');
				  	form.fadeTo( "slow" , 1, function() {});
					  
					  
					  wrapper.find('#ttshowcase_form.tt_form_has_ajax').each( function(){
						var newcontainerid = jQuery(this).attr('data-unique-id');
						jQuery(this).submit(function( event ) {
							event.preventDefault();
						}).find('.tt_form_button').one('click',function(){
							tt_ajax_form(newcontainerid);
						});
				
					});
				  	

				  });
				  
				 


	            },
	 
	        });
	});
    

	   
  

}