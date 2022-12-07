(function($) { 
"use strict"; 

    jQuery(document).ready(function($) {

        $(".rpc-contents .rpc-tabcontent").hide().first().show();
        $(".rpc-tabs a:first").addClass("active");

        var tabs = $('.rpc-tabs');
        var items = $('.rpc-tabs').find('a').length;
        var selector = $(".rpc-tabs").find(".selector");
        var activeItem = tabs.find('.active');
        var activeWidth = activeItem.innerWidth();
        $(".selector").css({
            "left": activeItem.position.left + "px", 
            "width": activeWidth + "px"
        });

        $(".rpc-tabs").on("click","a",function(e){
            e.preventDefault();
            window.location.hash = $(this).attr("href");
            $($(this).attr('href')).slideDown().siblings('.rpc-tabcontent').slideUp();
            $('.rpc-tabs a').removeClass("active");
            $(this).addClass('active');
            var activeWidth = $(this).innerWidth();
            var itemPos = $(this).position();
            $(".selector").css({
                "left":itemPos.left + "px", 
                "width": activeWidth + "px"
            });
        });

        var hash = $.trim( window.location.hash );
        if (hash) $('.rpc-tabs a[href$="'+hash+'"]').trigger('click');

    	var b_ui_styles = { css: {
                border: 'none', 
                padding: '15px', 
                backgroundColor: '#000', 
                '-webkit-border-radius': '10px', 
                '-moz-border-radius': '10px', 
                opacity: .7, 
                color: '#fff' 
            } };

        $('#wrap_term_ select').select2().on("select2:select", function (evt) {
                var id = evt.params.data.id;
                var element = $(this).children("option[value="+id+"]");
                moveElementToEndOfParent(element);
                $(this).trigger("change");
            });

        var wrap_terms = $("#wrap_term_ select").parent().find("ul.select2-selection__rendered");
        wrap_terms.sortable({
            containment: 'parent',
            update: function() {
                orderSortedValues($(this).closest('tr').attr('id'));
            }
        });

        $('#wrap_posts_ select').select2().on("select2:select", function (evt) {
                var id = evt.params.data.id;
                var element = $(this).children("option[value="+id+"]");
                moveElementToEndOfParent(element);
                $(this).trigger("change");
            });

        var wrap_posts = $("#wrap_posts_ select").parent().find("ul.select2-selection__rendered");
        wrap_posts.sortable({
            containment: 'parent',
            update: function() {
                orderSortedValues($(this).closest('tr').attr('id'));
            }
        });

        function orderSortedValues(parentID) {
            $("#"+parentID+" select").parent().find("ul.select2-selection__rendered").children("li[title]").each(function(i, obj){
                var element = $("#"+parentID+" select").children('option').filter(function () { return $(this).html() == obj.title });
                moveElementToEndOfParent(element)
            });
        }

        function moveElementToEndOfParent(element) {
            var parent = element.parent();
            element.detach();
            parent.append(element);
        }

    	$('.td_social_networks select').select2();
    	$('.colorpicker').wpColorPicker();
    	
    	$('#wrap_post_type select').change(function(event) {
    		event.preventDefault();
            $.blockUI(b_ui_styles);
    		var data = {
    			action: 'rpc_get_posts',
    			post_type: $(this).val(),
    		}
    		$.post(ajaxurl, data, function(resp) {
    			// console.log(resp);
    			$('#wrap_posts_ select').html(resp);
    			$.unblockUI();
    		});
    	});
        $('#wrap_taxonomy select').change(function(event) {
            event.preventDefault();
            $.blockUI(b_ui_styles);
            var element = jQuery(this);
            jQuery.post(ajaxurl, {action: 'rpc_get_terms' , taxonomy: element.val()}, function(resp) {
    			element.closest('table').find('.td_term_').html(resp);
    			element.closest('table').find('.wcp-term').select2();
    			$.unblockUI();
            });
        });

        if ($('#wrap_display_by select').val() == 'taxonomy') {
        	$('#wrap_post_type').hide();
        	$('#wrap_posts_').hide();
        } else {
        	$('#wrap_taxonomy').hide();
        	$('#wrap_term_').hide();
        }

        $('#wrap_display_by select').change(function(event) {
        	if ($(this).val() == 'taxonomy') {
    	    	$('#wrap_post_type').hide();
    	    	$('#wrap_posts_').hide();
    	    	$('#wrap_taxonomy').show();
    	    	$('#wrap_term_').show();
        	} else {
    	    	$('#wrap_post_type').show();
    	    	$('#wrap_posts_').show();
    	    	$('#wrap_taxonomy').hide();
    	    	$('#wrap_term_').hide();
        	}
        });

        rpc_update_preview('first_time');
        $('.td_hover_effect').on('change', 'select', function(event) {
            event.preventDefault();            
            rpc_update_preview('change');            
        });
        function rpc_update_preview(type){
            if (type != 'first_time') {
                $.blockUI(b_ui_styles);
            }
            var preview_style = {
                action: 'rpc_get_template_preview',
                template_id: $('.td_hover_effect select').val(),
                carousel_id: $('.rpc-carousel-id').val(),
                type: type,
            }
            $.post(ajaxurl, preview_style, function(resp) {
                // console.log(resp);
                if (resp.html == 'none') {
                    $('#rpc-template-settings').html(resp.settings);
                    $('#rpc-preview').html('');
                } else {
                    $('#rpc-preview').html(resp.html);
                    $('#rpc-template-settings').html(resp.settings);
                    $('#rpc-template-settings').find('.colorpicker').wpColorPicker({
                        change: function(event, ui) {
                            var class_name = $(this).closest('td').find('.colorpicker').data('selector');
                            var property = $(this).closest('td').find('.colorpicker').data('property');
                            $('#rpc-preview').find(class_name).css( property, ui.color.toString());
                        }
                    });
                    $('#rpc-template-settings').on('keyup', '.rpctextfield', function(event) {
                        event.preventDefault();
                        var class_name = $(this).data('selector');
                        var value = $(this).val();
                        var property = $(this).data('property');
                        if (class_name != undefined) {
                            $('#rpc-preview').find(class_name).css( property, value);
                        }
                    });
                    $('#rpc-template-settings').on('change', '.rpcselectbox', function(event) {
                        event.preventDefault();
                        var class_name = $(this).data('selector');
                        var value = $(this).val();
                        var property = $(this).data('property');
                        if (class_name != undefined) {
                            $('#rpc-preview').find(class_name).css( property, value);
                        }
                    });
                    $('#rpc-template-settings .rpctextfield').trigger('keyup');
                    $('#rpc-template-settings .rpcselectbox').trigger('change');
                }
                if (type != 'first_time') {
                    $.unblockUI();
                }
            }, 'json');
        }
    });

})(jQuery);
