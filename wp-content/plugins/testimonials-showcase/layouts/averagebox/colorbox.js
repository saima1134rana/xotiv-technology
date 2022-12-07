jQuery(document).ready(function() {

    if (jQuery('.tt_average_rating_box .tt_rating-star').length){

        jQuery('.tt_average_rating_box .tt_rating-star').each(function(){

			jQuery(this).attr('href','#')

			.one('click',function(){

				jQuery('#tt_modal_content').attr('data-value',jQuery(this).attr('data-value'));

				var valclicked = jQuery(this).attr('data-value');

				jQuery(this).colorbox({
					inline:true,
					href: jQuery('#tt_modal_content'),
					innerWidth:'90%',
					maxWidth:640,
					close:ttparam.close,
					transition: 'fade',
					onComplete: function(){

						jQuery('#cboxLoadedContent input[value='+valclicked+']').prop("checked", true).click();

					},
			   });


			   //clean up
			   jQuery(this).closest('.tt_trigger_modal').removeClass('tt_trigger_modal');
				jQuery('.tt_average_rating_box .tt_rating-star').each(function(){

					jQuery(this).unbind('click');

					if( parseInt ( jQuery(this).attr('data-value') ) <= parseInt (valclicked) ) {
						jQuery(this).find('.fa').attr('class', 'fa fa-star');
					} else {
						jQuery(this).find('.fa').attr('class', 'fa fa-star-o');
					}


				});

			});

        })
    }
});