<?php

/*

Utils Lite for CMShowcase Framework
Author: Carlos Moreira
Version: 0.1
Latest Edit: Jan 30 2014

*/


//runs only when plugin is activated to flush permalinks
register_activation_hook(__FILE__, 'cmshowcase_flush_rules');

if (!function_exists('cmshowcase_flush_rules')) {
    function cmshowcase_flush_rules(){
        //and flush the rules.
        flush_rewrite_rules();
    }
}


// Function to get options from the database/settings page

if (!function_exists('cmshowcase_get_option')) {

	function cmshowcase_get_option( $option, $section, $default = '' ) {

        $options = get_option( $section );

        if ( isset( $options[$option] ) ) {
            return maybe_serialize($options[$option]);
        }

        return $default;
    }
}

/*

The functions below are the callbacks to build form fields

*/


if (!function_exists('cmshowcase_build_field_text')) {

    function cmshowcase_build_field_text( $args ) {

        $id = isset( $args['id'] ) && !is_null( $args['id'] ) ? $args['id'] : '';
		$name = isset( $args['name'] ) && !is_null( $args['name'] ) ? $args['name'] : $id;
		$default = isset( $args['default'] ) ? $args['default'] : '';
		$value = esc_attr ( isset( $args['value'] ) ? $args['value'] : $default );
		$size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
		$description = isset( $args['description'] ) ? $args['description'] : '';
        $onchange = isset( $args['onchange'] ) ? 'onchange="'.$args['onchange'].'"' : '';

        $html = sprintf( '<input type="text" class="%1$s-text" id="%2$s" name="%3$s" value="%4$s" %5$s />', $size, $id, $name, $value, $onchange );
        $html .= sprintf( '<span class="description"> %s</span>', $description );

        echo $html;
    }
}

if (!function_exists('cmshowcase_build_field_hidden')) {

    function cmshowcase_build_field_hidden( $args ) {

        $id = isset( $args['id'] ) && !is_null( $args['id'] ) ? $args['id'] : '';
        $name = isset( $args['name'] ) && !is_null( $args['name'] ) ? $args['name'] : $id;
        $default = isset( $args['default'] ) ? $args['default'] : '';
        $value = esc_attr ( isset( $args['value'] ) ? $args['value'] : $default );
        

        $html = sprintf( '<input type="hidden" id="%1$s" name="%2$s" value="%3$s"/>', $id, $name, $value );

        echo $html;
    }
}

if (!function_exists('cmshowcase_build_field_hidden_html')) {

    function cmshowcase_build_field_hidden_html( $args ) {

        $id = isset( $args['id'] ) && !is_null( $args['id'] ) ? $args['id'] : '';
        $name = isset( $args['name'] ) && !is_null( $args['name'] ) ? $args['name'] : $id;
        $default = isset( $args['default'] ) ? $args['default'] : '';
        $value = esc_attr ( isset( $args['value'] ) ? $args['value'] : $default );
        

        $html = sprintf( '<span id="%1$s" style="display:none">%2$s</span>', $id, $value );

        echo $html;
    }
}


if (!function_exists('cmshowcase_build_field_checkbox')) {

    function cmshowcase_build_field_checkbox( $args ) {

    	$id = isset( $args['id'] ) && !is_null( $args['id'] ) ? $args['id'] : '';
		$name = isset( $args['name'] ) && !is_null( $args['name'] ) ? $args['name'] : $id;
		$default = isset( $args['default'] ) ? $args['default'] : '';
		$value = isset( $args['value'] ) ? $args['value'] : $default;
		$size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
		$description = isset( $args['description'] ) ? $args['description'] : '';
        $onchange = isset( $args['onchange'] ) ? 'onchange="'.$args['onchange'].'"' : '';

        $html = sprintf( '<input type="hidden" name="%1$s" value="off" />', $name );
        $html .= sprintf( '<input type="checkbox" class="checkbox" id="%1$s" name="%1$s" value="on"%4$s %5$s />', $id, $name, $value, checked( $value, 'on', false ), $onchange );
        $html .= sprintf( '<label for="%1$s"> %2$s</label>', $id, $description );

        echo $html;
    }

}

    

if (!function_exists('cmshowcase_build_field_multicheck')) {

    function cmshowcase_build_field_multicheck( $args ) {

        $id = isset( $args['id'] ) && !is_null( $args['id'] ) ? $args['id'] : '';
		$name = isset( $args['name'] ) && !is_null( $args['name'] ) ? $args['name'] : $id;
		$default = isset( $args['default'] ) ? $args['default'] : '';
		$value = isset( $args['value'] ) ? $args['value'] : $default;
		$size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
		$description = isset( $args['description'] ) ? $args['description'] : '';
		$options = isset( $args['options'] ) ? $args['options'] : array();


        $html = '';
        foreach ( $args['options'] as $key => $label ) {
            $checked = isset( $value[$key] ) ? $value[$key] : '0';
            $html .= sprintf( '<input type="checkbox" class="checkbox" id="%1$s[%2$s]" name="%1$s[%2$s]" value="%2$s" %3$s />', $id, $key, checked( $checked, $key, false ) );
            $html .= sprintf( '<label for="%1$s[%3$s]"> %3$s</label><br>', $id, $key, $label );
        }
        $html .= sprintf( '<span class="description"> %s</span>', $description );

        echo $html;
    }

}


if (!function_exists('cmshowcase_build_field_radio')) {

    function cmshowcase_build_field_radio( $args ) {

        $id = isset( $args['id'] ) && !is_null( $args['id'] ) ? $args['id'] : '';
		$name = isset( $args['name'] ) && !is_null( $args['name'] ) ? $args['name'] : $id;
		$default = isset( $args['default'] ) ? $args['default'] : '';
		$value = isset( $args['value'] ) ? $args['value'] : $default;
		$size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
		$description = isset( $args['description'] ) ? $args['description'] : '';
		$options = isset( $args['options'] ) ? $args['options'] : array();

        $html = '';
        foreach ( $options as $key => $label ) {
            $html .= sprintf( '<input type="radio" class="radio" id="%1$s[%2$s]" name="%1$s" value="%2$s" %3$s />', $id, $key, checked( $value, $key, false ) );
            $html .= sprintf( '<label for="%1$s[%3$s]"> %2$s</label><br>', $id, $label, $key );
        }
        $html .= sprintf( '<span class="description"> %s</label>', $description );

        echo $html;
    }

}


if (!function_exists('cmshowcase_build_field_select')) {


    function cmshowcase_build_field_select( $args ) {

        $id = isset( $args['id'] ) && !is_null( $args['id'] ) ? $args['id'] : '';
		$name = isset( $args['name'] ) && !is_null( $args['name'] ) ? $args['name'] : $id;
		$default = isset( $args['default'] ) ? $args['default'] : '';
		$value = isset( $args['value'] ) ? $args['value'] : $default;
		$size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
		$description = isset( $args['description'] ) ? $args['description'] : '';
		$options = isset( $args['options'] ) ? $args['options'] : array();
        $onchange = isset( $args['onchange'] ) ? 'onchange="'.$args['onchange'].'"' : '';
        $multiple = isset($args['multiple']) ? 'multiple="multiple"' : '';

        $html = sprintf( '<select class="%1$s" name="%2$s" id="%2$s" %3$s %4$s>', $size, $id, $onchange, $multiple );
        foreach ( $options as $key => $label ) {
            $html .= sprintf( '<option value="%1$s" %2$s>%3$s</option>', $key, selected($value, $key, false), $label );
        }
        $html .= sprintf( '</select>' );
        if($description!=''){
            $html .= sprintf( '<span class="description"> %s</span>', $description );
        }

        echo $html;

    }

}



if (!function_exists('cmshowcase_build_field_multiple_choice')) {


    function cmshowcase_build_field_multiple_choice( $args ) {

        $id = isset( $args['id'] ) && !is_null( $args['id'] ) ? $args['id'] : '';
        $name = isset( $args['name'] ) && !is_null( $args['name'] ) ? $args['name'] : $id;
        $default = isset( $args['default'] ) ? $args['default'] : '';
        $value = isset( $args['value'] ) ? $args['value'] : $default;
        $description = isset( $args['description'] ) ? $args['description'] : '';
        $options = isset( $args['options'] ) ? $args['options'] : array();
        $onchange = isset( $args['onchange'] ) ? 'onchange="'.$args['onchange'].'"' : '';

        $valuearray = explode(',',$value);
        $defaultarray = explode(',',$args['default']);
        $choice_array = array();
        
        foreach ($valuearray as $key) {
            if(in_array($key, $defaultarray)){
                array_push($choice_array, $key);
            }
        }
        foreach ($defaultarray as $key) {
            if(!in_array($key, $valuearray)){
                array_push($choice_array, $key);
            }
        }

        $remove = array("[", "]");
        $replace = array("_", "");
        $field_id = str_replace($remove, $replace, $name);

        $html = '<div class="mc_wrap_label">';
        foreach ($choice_array as $opt) {
            $checked = in_array(trim($opt), $valuearray) ? 'checked="checked"' : '';
            $lb = ucfirst(str_replace('_',' ', trim($opt)));
            $html .= sprintf('<label style="padding-right:15px;"><input  type="checkbox" onchange="'.$field_id.'_check_callback()"  name="%1$s" value="%2$s" %3$s> %4$s </label>',$field_id,trim($opt),$checked,$lb);    
        }

        if($description!=''){
            $html .= sprintf( '<div><span class="description"> %s</span></div>', $description );
        }

        $html .= '<input type="hidden" value="'.$value.'" id="'.$name.'" name="'.$name.'">';

        $html .= '</div>';

        $html .= '<script type="text/javascript">

                    function '.$field_id.'_check_callback() {

                        multiple_choice_field = jQuery("#'.str_replace(array("[", "]"), array("\\\[", "\\\]"), $name).'");
                        multiple_choice_field.val("");
                        finalval = "";
                        jQuery("input[name=\''.$field_id.'\']:checked").each(function() {
                           finalval = finalval+jQuery(this).val()+",";
                        });
                        multiple_choice_field.val(finalval);

                        '.$onchange.'

                    }

                        
                    
                    </script>';

        echo $html;

    }

}


if (!function_exists('cmshowcase_build_field_sortable')) {


    function cmshowcase_build_field_sortable( $args ) {

       

        $id = isset( $args['id'] ) && !is_null( $args['id'] ) ? $args['id'] : '';
        $name = isset( $args['name'] ) && !is_null( $args['name'] ) ? $args['name'] : $id;
        $value = isset( $args['value'] ) ? $args['value'] : $args['default'];
        $size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
        $description = isset( $args['description'] ) ? $args['description'] : '';
        $onchange = isset( $args['onchange'] ) ? 'onchange="'.$args['onchange'].'"' : '';

        //we make sure the value is consistent with options provided in the default value
        $valuearray = explode(',',$value);
        $defaultarray = explode(',',$args['default']);
        $sortarray = array();
        foreach ($valuearray as $key) {
            if(in_array($key, $defaultarray)){
                array_push($sortarray, $key);
            }
        }
        foreach ($defaultarray as $key) {
            if(!in_array($key, $valuearray)){
                array_push($sortarray, $key);
            }
        }
        
        //we remove the square brackets for the sortable id
        $remove = array("[", "]");
        $replace = array("_", "");
        $strid = str_replace($remove, $replace, $name);

        $html = '<ul id="sortable_'.$strid.'" class="cmshowcase_sortable">';
        foreach ( $sortarray as $key ) {
            $label = ucfirst(preg_replace('/(?<!\ )[A-Z]/', ' $0', $key));
            $html .= sprintf('<li id="%s">%s</li>',$key,$label);
        }
        $html .= sprintf( '</ul>' );
        if($description!=''){
            $html .= sprintf( '<span class="description"> %s</span>', $description );
        }

        $html .= '<input type="hidden" value="'.$value.'" id="'.$name.'" name="'.$name.'">';

         $html .= '<script type="text/javascript">
                    jQuery(document).ready(function($){
                       
                        $("#sortable_'.$strid.'").sortable({
                            stop : function(event, ui){


                                setorderfield = $("#'.str_replace(array("[", "]"), array("\\\[", "\\\]"), $name).'");
                                
                                setorderfield.val("");
                                var finalval = "";
                                $("#sortable_'.$strid.' li").each(function(){
                                    finalval = finalval+$(this).attr("id")+",";
                                });
                                finalval = finalval.replace(/,\s*$/, "");
                                setorderfield.val(finalval);
                            }
                        });

                        $("#sortable_'.$strid.'").disableSelection();
                    });
                    </script>';

        $html .= '<style type="text/css" scoped> 
        #sortable_ttshowcase_front_form_order { padding:0; margin:0; } 
        #sortable_ttshowcase_front_form_order li { display:inline-block; background:#F5F5F5; cursor:move; border:1px solid #ccc; padding:5px; margin:2px; }
        </style>';

        echo $html;

    }

}


 

if (!function_exists('cmshowcase_build_field_textarea')) {

    function cmshowcase_build_field_textarea( $args ) {

        $id = isset( $args['id'] ) && !is_null( $args['id'] ) ? $args['id'] : '';
		$name = isset( $args['name'] ) && !is_null( $args['name'] ) ? $args['name'] : $id;
		$default = isset( $args['default'] ) ? $args['default'] : '';
		$value = isset( $args['value'] ) ? $args['value'] : $default;

        $value = str_replace('<br />', '\n', $value);

		$size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
		$description = isset( $args['description'] ) ? $args['description'] : '';
		$options = isset( $args['options'] ) ? $args['options'] : array();

        $html = sprintf( '<textarea rows="5" cols="55" class="%1$s-text" id="%2$s" name="%2$s">%3$s</textarea>', $size, $id, $value );
        $html .= sprintf( '<br><span class="description"> %s</span>', $description );

        echo $html;
    }

}


if (!function_exists('cmshowcase_build_field_html')) {

    function cmshowcase_build_field_html( $args ) {
    	$description = isset( $args['description'] ) ? $args['description'] : '';
        echo $description;
    }

}

if (!function_exists('cmshowcase_build_field_wysiwyg')) {


    function cmshowcase_build_field_wysiwyg( $args ) {

    	$id = isset( $args['id'] ) && !is_null( $args['id'] ) ? $args['id'] : '';
		$name = isset( $args['name'] ) && !is_null( $args['name'] ) ? $args['name'] : $id;
		$default = isset( $args['default'] ) ? $args['default'] : '';
		$value = stripslashes(isset( $args['value'] ) ? $args['value'] : $default);
		$size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : '500px';
		$description = isset( $args['description'] ) ? $args['description'] : '';


        echo '<div style="width: ' . $size . ';">';

        /* function might need review */
        wp_editor( $value, $id, array( 'teeny' => true, 'textarea_rows' => 10, 'tinymce' => false ) );
        

        //With full editor
        //wp_editor($value, 'textareafield', array('media_buttons' => false, 'textarea_name' => $id));

        


        echo '</div>';

        echo sprintf( '<br><span class="description"> %s</span>', $args['description'] );
    }
}

if (!function_exists('cmshowcase_build_field_fulleditor')) {


    function cmshowcase_build_field_fulleditor( $args ) {

        $id = isset( $args['id'] ) && !is_null( $args['id'] ) ? $args['id'] : '';
        $name = isset( $args['name'] ) && !is_null( $args['name'] ) ? $args['name'] : $id;
        $default = isset( $args['default'] ) ? $args['default'] : '';
        $value = stripslashes(isset( $args['value'] ) ? $args['value'] : $default);
        $size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : '500px';
        $description = isset( $args['description'] ) ? $args['description'] : '';


        echo '<div style="width: ' . $size . ';">';

        //With full editor
        wp_editor($value, 'textareafield', array('media_buttons' => true, 'textarea_name' => $id));

        


        echo '</div>';

        echo sprintf( '<br><span class="description"> %s</span>', $args['description'] );
    }
}

if (!function_exists('cmshowcase_build_field_image')) {


    function cmshowcase_build_field_image( $args ) {

        wp_enqueue_style('farbtastic');
        wp_enqueue_style('thickbox');

        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'jquery-ui-sortable' );

        wp_enqueue_script( 'media-upload' );
        wp_enqueue_script( 'thickbox' );
         wp_enqueue_script('farbtastic');



        $id = isset( $args['id'] ) && !is_null( $args['id'] ) ? $args['id'] : '';
        $name = isset( $args['name'] ) && !is_null( $args['name'] ) ? $args['name'] : $id;
        $default = isset( $args['default'] ) ? $args['default'] : '';
        $value = isset( $args['value'] ) ? $args['value'] : $default;
        $size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
        $description = isset( $args['description'] ) ? $args['description'] : '';

        $js_id = str_replace('[', '\\\\[', $id);
        $js_id = str_replace(']', '\\\\]', $js_id);
        $html = sprintf( '<input type="text" class="%1$s-text" id="%2$s" name="%3$s" value="%4$s"/>', $size, $id, $name, $value );
        $html .= '<input type="button" class="button wpsf-browse" id="'. $id .'_button" value="Browse" />';
        $html .= '<div id="'.$id.'_preview"><img src="'.$value.'" style="max-width:50px;"></div>';

        $html .= '<script type="text/javascript">
                    jQuery(document).ready(function($){
                                $("#'. $js_id .'_button").click(function() {
                                        tb_show("", "media-upload.php?post_id=0&amp;type=image&amp;TB_iframe=true");
                                        window.original_send_to_editor = window.send_to_editor;
                                window.send_to_editor = function(html) {
                                        var imgurl = $("img",html).attr("src");
                                        $("#'. $js_id .'").val(imgurl);
                                         $("#'. $js_id .'_preview > img").attr("src",imgurl);
                                        tb_remove();
                                        window.send_to_editor = window.original_send_to_editor;
                                };
                                        return false;
                                });
                    });
                    </script>';




        $html .= sprintf( '<span class="description"> %s</span>', $args['description'] );

        echo $html;
    }

}


if (!function_exists('cmshowcase_build_field_file')) {


    function cmshowcase_build_field_file( $args ) {

        wp_enqueue_style('farbtastic');
        wp_enqueue_style('thickbox');

        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'media-upload' );
        wp_enqueue_script( 'thickbox' );
         wp_enqueue_script('farbtastic');



        $id = isset( $args['id'] ) && !is_null( $args['id'] ) ? $args['id'] : '';
		$name = isset( $args['name'] ) && !is_null( $args['name'] ) ? $args['name'] : $id;
		$default = isset( $args['default'] ) ? $args['default'] : '';
		$value = isset( $args['value'] ) ? $args['value'] : '';
		$size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
		$description = isset( $args['description'] ) ? $args['description'] : '';

        $js_id = str_replace('[', '\\\\[', $id);
        $js_id = str_replace(']', '\\\\]', $js_id);
        $html = sprintf( '<input type="text" class="%1$s-text" id="%2$s" name="%3$s" value="%4$s"/>', $size, $id, $name, $value );
        $html .= '<input type="button" class="button wpsf-browse" id="'. $id .'_button" value="Browse" />';


        $html .= '<script type="text/javascript">
                    jQuery(document).ready(function($){
                                $("#'. $js_id .'_button").click(function() {
                                        tb_show("", "media-upload.php?post_id=0&amp;type=image&amp;TB_iframe=true");
                                        window.original_send_to_editor = window.send_to_editor;
                                window.send_to_editor = function(html) {
                                        var imgurl = $("img",html).attr("src");
                                        $("#'. $js_id .'").val(imgurl);
                                        tb_remove();
                                        window.send_to_editor = window.original_send_to_editor;
                                };
                                        return false;
                                });
                    });
                    </script>';




        $html .= sprintf( '<span class="description"> %s</span>', $args['description'] );

        echo $html;
    }

}

if (!function_exists('cmshowcase_build_field_password')) {


    function cmshowcase_build_field_password( $args ) {

        $id = isset( $args['id'] ) && !is_null( $args['id'] ) ? $args['id'] : '';
		$name = isset( $args['name'] ) && !is_null( $args['name'] ) ? $args['name'] : $id;
		$default = isset( $args['default'] ) ? $args['default'] : '';
		$value = isset( $args['value'] ) ? $args['value'] : '';
		$size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
		$description = isset( $args['description'] ) ? $args['description'] : '';

        $html = sprintf( '<input type="password" class="%1$s-text" id="%2$s" name="%2$s" value="%3$s"/>', $size, $id, $value );
        $html .= sprintf( '<span class="description"> %s</span>', $description);

        echo $html;
    }

}



if (!function_exists('cmshowcase_build_field_color')) {


    function cmshowcase_build_field_color( $args ) {

        $id = isset( $args['id'] ) && !is_null( $args['id'] ) ? $args['id'] : '';
        $name = isset( $args['name'] ) && !is_null( $args['name'] ) ? $args['name'] : $id;
        $default = isset( $args['default'] ) ? $args['default'] : '';
        $value = isset( $args['value'] ) ? $args['value'] : '';
        $size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
        $description = isset( $args['description'] ) ? $args['description'] : '';
        $onchange = isset( $args['onchange'] ) ? 'onchange="'.$args['onchange'].'"' : '';
        
        $generator = isset($args['generator']) ? '#'.$args['generator'].'_wrapper ' : '';

        $function = 'function(event,ui){ 
            var clinp = jQuery("'.$generator.'#'.$id.'").val(ui.color.toString()).change();
            console.log(ui.color.toString());
            console.log("'.$generator.'#'.$id.'");
        }';

        $html = sprintf( '<input type="text" class="cm-color-field" id="%1$s" name="%2$s" value="%3$s" />', $id, $name, $value );
        $html .= sprintf( '<span class="description"> %s</span>', $description);
        //$html .= sprintf( '<input type="hidden" id="%1$s" name="%2$s" value="%3$s" %4$s />', $id, $name, $value, $onchange );

        $html .= '
        <script type="text/javascript">

        jQuery(document).ready(function($){
            var myOptions = {
                    // you can declare a default color here,
                    // or in the data-default-color attribute on the input
                    defaultColor: false,
                    // a callback to fire whenever the color changes to a valid color
                    change: '.$function.',
                    // a callback to fire when the input is emptied or an invalid color
                    clear: function() {},
                    // hide the color picker controls on load
                    hide: true,
                    // show a group of common colors beneath the square
                    // or, supply an array of colors to customize further
                    palettes: false
                };
             $("'.$generator.'.cm-color-field").wpColorPicker(myOptions);
          });

       
        </script>';

        echo $html;
    }

}

if (!function_exists('cmshowcase_build_field_taxonomy')) {

    function cmshowcase_build_field_taxonomy($options) {

        $cpt = isset($options['cpt']) ? $options['cpt'] : '';
        $none = isset($options['none_label']) ? $options['none_label'] : 'none';

        $taxonomies = get_object_taxonomies($cpt,'object');
        
        foreach ($taxonomies as $tax) {

            $args = array();
            $args['id'] = 'taxonomy'; //str_replace($cpt.'_','',$tax->query_var);
            $args['default'] = '';
            $args['description'] = isset($options['description']) ? $options['description'] : '';
            $args['size'] = 'medium';
            $args['onchange'] = 'cmshowcase_build_shortcode(this)';
            $args['default'] = isset($options['default']) ? $options['default'] : '0';
            $args['tax_args'] = isset($options['tax_args']) ? $options['tax_args'] : array(
                'orderby'       => 'name', 
                'order'         => 'ASC',
                'hide_empty'    => false
            );

            $args['extra_options'] = isset($options['extra_options']) ? $options['extra_options'] : false;

            

            $terms = get_terms( $tax->query_var , $args['tax_args']);
            $count = count($terms);
            $optarray = array();
            $optarray['0'] = $none;
            if ($terms && $count > 0) {
                foreach($terms as $term) {
                    if(isset($term->slug)) {
                        $optarray[$term->slug] = $term->name;
                    }
                    
                }
            }


            if($args['extra_options']) {

                foreach ($args['extra_options'] as $key => $value) {
                    $optarray[$key] = __($value,$cpt);
                }   
            
            }

            $args['options'] = $optarray;

            
            cmshowcase_build_field_select( $args );
           
            
        }

    } 

}



if (!function_exists('cmshowcase_add_http')) {

    function cmshowcase_add_http($url) {


        if($url!='') {

            $url = str_replace('##//', '://', $url);

            if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
            }
        }
        
        return $url;
    }

}

if (!function_exists('cmshowcase_html_cut')) {

    function cmshowcase_html_cut($text, $max_length)
    {
        $tags   = array();
        $result = "";

        $is_open   = false;
        $grab_open = false;
        $is_close  = false;
        $in_double_quotes = false;
        $in_single_quotes = false;
        $tag = "";

        $i = 0;
        $stripped = 0;

        $stripped_text = strip_tags($text);

        while ($i < strlen($text) && $stripped < strlen($stripped_text) && $stripped < $max_length)
        {
            $symbol  = $text{$i};
            $result .= $symbol;

            switch ($symbol)
            {
               case '<':
                    $is_open   = true;
                    $grab_open = true;
                    break;

               case '"':
                   if ($in_double_quotes)
                       $in_double_quotes = false;
                   else
                       $in_double_quotes = true;

                break;

                case "'":
                  if ($in_single_quotes)
                      $in_single_quotes = false;
                  else
                      $in_single_quotes = true;

                break;

                case '/':
                    if ($is_open && !$in_double_quotes && !$in_single_quotes)
                    {
                        $is_close  = true;
                        $is_open   = false;
                        $grab_open = false;
                    }

                    break;

                case ' ':
                    if ($is_open)
                        $grab_open = false;
                    else
                        $stripped++;

                    break;

                case '>':
                    if ($is_open)
                    {
                        $is_open   = false;
                        $grab_open = false;
                        array_push($tags, $tag);
                        $tag = "";
                    }
                    else if ($is_close)
                    {
                        $is_close = false;
                        array_pop($tags);
                        $tag = "";
                    }

                    break;

                default:
                    if ($grab_open || $is_close)
                        $tag .= $symbol;

                    if (!$is_open && !$is_close)
                        $stripped++;
            }

            $i++;
        }

        while ($tags)
            $result .= "</".array_pop($tags).">";

        return $result;
    }

}

if (!function_exists('cmshowcase_truncate')) {
    //Inspired by similar function in CakePHP framework: http://cakephp.org/
    function cmshowcase_truncate($text, $length = 100, $ending = '...', $exact = true, $considerHtml = false) {
        if (is_array($ending)) {
            extract($ending);
        }
        if ($considerHtml) {
            if (mb_strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
                return $text;
            }
            $totalLength = mb_strlen($ending);
            $openTags = array();
            $truncate = '';
            preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);
            foreach ($tags as $tag) {
                if (!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s', $tag[2])) {
                    if (preg_match('/<[\w]+[^>]*>/s', $tag[0])) {
                        array_unshift($openTags, $tag[2]);
                    } else if (preg_match('/<\/([\w]+)[^>]*>/s', $tag[0], $closeTag)) {
                        $pos = array_search($closeTag[1], $openTags);
                        if ($pos !== false) {
                            array_splice($openTags, $pos, 1);
                        }
                    }
                }
                $truncate .= $tag[1];

                $contentLength = mb_strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $tag[3]));
                if ($contentLength + $totalLength > $length) {
                    $left = $length - $totalLength;
                    $entitiesLength = 0;
                    if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $tag[3], $entities, PREG_OFFSET_CAPTURE)) {
                        foreach ($entities[0] as $entity) {
                            if ($entity[1] + 1 - $entitiesLength <= $left) {
                                $left--;
                                $entitiesLength += mb_strlen($entity[0]);
                            } else {
                                break;
                            }
                        }
                    }

                    $truncate .= mb_substr($tag[3], 0 , $left + $entitiesLength);
                    break;
                } else {
                    $truncate .= $tag[3];
                    $totalLength += $contentLength;
                }
                if ($totalLength >= $length) {
                    break;
                }
            }

        } else {
            if (mb_strlen($text) <= $length) {
                return $text;
            } else {
                $truncate = mb_substr($text, 0, $length - strlen($ending));
            }
        }
        if (!$exact) {
            $spacepos = mb_strrpos($truncate, ' ');
            if (isset($spacepos)) {
                if ($considerHtml) {
                    $bits = mb_substr($truncate, $spacepos);
                    preg_match_all('/<\/([a-z]+)>/', $bits, $droppedTags, PREG_SET_ORDER);
                    if (!empty($droppedTags)) {
                        foreach ($droppedTags as $closingTag) {
                            if (!in_array($closingTag[1], $openTags)) {
                                array_unshift($openTags, $closingTag[1]);
                            }
                        }
                    }
                }
                $truncate = mb_substr($truncate, 0, $spacepos);
            }
        }

        $truncate .= $ending;

        if ($considerHtml) {
            foreach ($openTags as $tag) {
                $truncate .= '</'.$tag.'>';
            }
        }

        return $truncate;
    }
}

if (!function_exists('cmshowcase_check_email')) {

    function cmshowcase_check_email($email) {
        // we check that there's one @ symbol, and that the lengths are right
        if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
            // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
            return false;
        }
        // Split it into sections 
        $email_array = explode("@", $email);
        $local_array = explode(".", $email_array[0]);
        for ($i = 0; $i < sizeof($local_array); $i++) {
            if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
                return false;
            }
        }
        if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
            $domain_array = explode(".", $email_array[1]);
            if (sizeof($domain_array) < 2) {
                return false; // Not enough parts to domain
            }
            for ($i = 0; $i < sizeof($domain_array); $i++) {
                if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
                    return false;
                }
            }
        }

        return true;
    }

}

if (!function_exists('cmshowcase_get_boolean')) {

    function cmshowcase_get_boolean($string) {

        if($string == '1' || $string == 'on' || $string == 'true') {
            return true;
        }
        else {
            return false;
        }

    }
}

if (!function_exists('tts__')) {

    function tts__($string,$domain) {

        if(function_exists('pll__')) {
          $string = pll__($string);
          return $string;
        } 
        else if (function_exists('icl_object_id') ) {
            //$string = icl_t('Events Showcase','Settings Field: '.$string, $string);  
            $string = apply_filters( 'wpml_translate_single_string', $string, $domain, 'Settings Field: '.$string );  
            return $string;
        } else {
            return __($string,$domain);
        }

    }
}

if (!function_exists('tts_register_for_translation')) {

    function tts_register_for_translation($title,$value,$domain){
        //Polylang plugin
        /*if( class_exists( 'Polylang' ) && function_exists('pll_register_string') ) {
            pll_register_string( $title, $value , $domain );
        }*/
        //WPML & WPML
        if ( function_exists('icl_object_id') ) {
            do_action( 'wpml_register_single_string', $domain, 'Settings Field: '.$value, $value );
            //icl_register_string('Events Showcase','Settings Field', $value );
        }
    }
    
}


function tts_get_scale_array(){

    //5, 5.5, 10
    $section = 'ttshowcase_front_form';
    $tt_star_label_singular = cmshowcase_get_option('star_singular',$section,'Star');
    $tt_star_label_plural = cmshowcase_get_option('star_plural',$section,'Stars');

    $optscale = cmshowcase_get_option('rating_scale','ttshowcase_basic_settings','5');
    $scale = array();

    if($optscale=='5'){
        $scale = array(
                '0' => __('Ignore','ttshowcase'),
                '1' => '1 '.$tt_star_label_singular,
                '2' => '2 '.$tt_star_label_plural,
                '3' => '3 '.$tt_star_label_plural,
                '4' => '4 '.$tt_star_label_plural,
                '5' => '5 '.$tt_star_label_plural,
                );
    }   

   
   if($optscale=='10'){
        $scale = array(
                '0' => __('Ignore','ttshowcase'),
                '1' => __('1 Star','ttshowcase'),
                '2' => __('2 Stars','ttshowcase'),
                '3' => __('3 Stars','ttshowcase'),
                '4' => __('4 Stars','ttshowcase'),
                '5' => __('5 Stars','ttshowcase'),
                '6' => __('6 Stars','ttshowcase'),
                '7' => __('7 Stars','ttshowcase'),
                '8' => __('8 Stars','ttshowcase'),
                '9' => __('9 Stars','ttshowcase'),
                '10' => __('10 Stars','ttshowcase'),
                );
    }  

    return $scale; 


}


?>