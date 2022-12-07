jQuery(document).ready(function($){

	ttshowcase_build_sliders();

});

/*

jQuery(document).ajaxSuccess(function($) {

		ttshowcase_build_sliders();
			
	});

*/

/*
jQuery(window).on('resize orientationchange', function($) {

	clearTimeout(jQuery.data(this, 'resizeTimer'));
    jQuery.data(this, 'resizeTimer', setTimeout(function() {

    	if(cmsliders && cmsliders.length>0){

    		for (var i = 0; i < cmsliders.length; i++) {


			if (typeof cmsliders[i] != 'undefined') {


			  cmsliders[i].destroySlider();
			  cmsliders[i].unbind();
			  cmsliders[i] = undefined;
			 
			}

		}


		ttshowcase_build_sliders();
		console.log('slider renovated');

    	}

    }, 1000));

});

*/

var cmsliders = [];
function ttshowcase_build_sliders() {

	
	jQuery('.ttshowcase_slider').each(function(key,value){
		var ttslider = jQuery(this);
		var ttopts = ttslider.attr('data-slider-options');

		//var ttopts = jQuery('<div/>').html(ttslider.attr('data-slider-options')).text();

		console.log(ttopts);

		ttopts = JSON.parse(ttopts);

		var columns = parseInt(ttopts['columns']);	
		var wrap = ttopts['wrap_id']; 
		var smode = ttopts['mode'];
		var spause = parseInt(ttopts['pause']);
		var sauto = ttopts['auto'];
		var next_arrow = ttopts['arrow_next'];
		var prev_arrow = ttopts['arrow_prev'];
		var scontrols = false;
		var sautocontrols = false;
		var spager = false;
		var adaptiveh = ttopts['adaptive_height'];
		var touchenab = ttopts['touchEnabled'];

		next_arrow = jQuery('<textarea />').html(next_arrow).text();
		prev_arrow = jQuery('<textarea />').html(prev_arrow).text();

		/*var windowWidth = jQuery(window).width();
        if (windowWidth < 650) {
            adaptiveh = true;
        }*/

		var sprevSelector = false;
		var snextSelector = false;
		if(ttopts['controls']=='controls') {
			scontrols = true;
			snextSelector = ttslider.find('#tt-slider-next');
			sprevSelector = ttslider.find('#tt-slider-prev');
		}

		if(ttopts['controls']=='pager') {
			spager = true;
		}


		if(ttopts['controls']=='autocontrols') {
			sautocontrols = true;
		}

		if(ttopts['controls']=='sides') {
			scontrols = true;
		}

		//Build Slider
		ttslider.fadeIn('slow');

		cmsliders[key] = ttslider.find('.ttshowcase_wrap').bxSlider({
		  preloadImages: 'all',
		  mode: smode,
		  auto: sauto,
		  controls: scontrols,
		  autoControls: sautocontrols,
		  pager: spager,
		  pause: spause,
		  pagerType: 'full',
		  autoHover: true,
		  nextSelector: snextSelector,	
		  prevSelector: sprevSelector,
		  nextText: next_arrow,
		  prevText: prev_arrow,
		  speed: 500,
		  adaptiveHeight:adaptiveh,
		  touchEnabled: touchenab

		});

		//fixes
		if(typeof cmsliders[key].goToSlide === "function"){

			cmsliders[key].goToSlide(0);
			if(sauto==true) {
				cmsliders[key].startAuto();
			}

			if(sauto==true) {
				
				ttslider.find('.bx-next, .bx-prev, .bx-pager a').click(function(){
				    // time to wait (in ms)
				    var wait = spause;
				    cmsliders[key].stopAuto();
				    setTimeout(function(){
				        cmsliders[key].startAuto();
				    }, wait);
				});

			}

		}
		

	});

}