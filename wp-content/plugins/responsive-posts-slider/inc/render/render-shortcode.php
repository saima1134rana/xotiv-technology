<?php

    global $post;
    $current_post_id = '';
    if (isset($post->ID)) {
        $current_post_id = $post->ID;
    }

    // Carousel Data Attributes
    $data_attr = '';
    if(is_array($carousel_settings['slider'])){
        foreach ($carousel_settings['slider'] as $p_name => $p_val) {
            if ($p_val != '') {
                $data_attr .= ' data-'.esc_attr( $p_name ).' = '.esc_html( $p_val );
            }
        }
    }
    $data_attr = apply_filters( 'rpc_slick_args', $data_attr, $attrs['id'] );
    // Query Arguments
    $args = array(
        'posts_per_page'   => (isset($attrs['count'])) ? $attrs['count'] : -1,
        'ignore_sticky_posts' => true,
    );

    if (isset($carousel_settings['offset']) && $carousel_settings['offset'] != '') {
         $args['offset'] =  intval($carousel_settings['offset']);
    }

    if (isset($carousel_settings['posts_per_page']) && $carousel_settings['posts_per_page'] != '') {
         $args['posts_per_page'] =  intval($carousel_settings['posts_per_page']);
    }

    if (isset($carousel_settings['display_by']) && $carousel_settings['display_by'] == 'taxonomy') {

        if (is_array($carousel_settings['term'])) {
            $terms_included = $carousel_settings['term'];
        } else {
            $terms_included = array( $carousel_settings['term'] );
        }
        $args['tax_query'] = array(
            array(
                'taxonomy'         => $carousel_settings['taxonomy'],
                'terms'            => $terms_included,
                'include_children' => true,
            ),
        );
    } else {
        $args['post_type'] = $carousel_settings['post_type'];
        if ($carousel_settings['post_type'] == 'attachment') {
            $args['post_status'] = 'inherit';
        }
        if (is_array($carousel_settings['posts']) && $carousel_settings['posts'][0] != 'all') {
            $args['post__in'] = $carousel_settings['posts'];
        }
    }

    $exclude_ids_arr = explode(",",$carousel_settings['exclude_ids']);
    if (isset($carousel_settings['disable_current_post']) && $carousel_settings['disable_current_post'] == 'on') {
        $exclude_ids_arr[] = $current_post_id;
    }
    $args['post__not_in'] = $exclude_ids_arr;

    // Setting query params dynamically  
    if (is_array($attrs)) {
        foreach ($attrs as $key => $value) {
            if ($key != 'id' && $key != 'count') {
                $args[$key] = $value;
            }
        }
    }

    $args = apply_filters( 'rpc_query_args', $args, $attrs['id'] );
            
    $car_query = new WP_Query( $args );

    // Making its width as container
    $remove_side_space = '';
    if (isset($carousel_settings['fullwidth']) && isset($carousel_settings['slider']['margin']) && $carousel_settings['slider']['margin'] != '') {
        $remove_side_space = 'style="margin: 0 -'.$carousel_settings['slider']['margin'].'"';
    }
    
    // The Loop
    if ( $car_query->have_posts() && !isset($attrs['multisite']) ) { ?>
        <div class="wcp-carousel-main-wrap <?php echo 'rpc-crsl-'.$carousel_settings['hover_effect']; ?> <?php echo (isset($carousel_settings['arrow']['position'])) ? $carousel_settings['arrow']['position'] : '' ; ?>">
        <section class="wcp-slick <?php echo (isset($carousel_settings['sameheightcols'])) ? 'matchheightenable' : '' ; ?>" <?php echo $remove_side_space; ?> <?php echo esc_html( $data_attr ); ?> id="carousel-<?php echo esc_attr( $attrs['id'] ); ?>">

            <?php while ( $car_query->have_posts() ) {
                $car_query->the_post(); ?>
                <div class="slick-slide carousel-item-<?php echo get_the_id(); ?>">
                    <?php $this->render_post_box($carousel_settings['hover_effect'], get_the_id(), $carousel_settings); ?>
                </div>    

            <?php } ?>
    
        </section>
        </div>
        <?php wp_reset_postdata();
    } elseif (isset($attrs['multisite'])) { ?>
        <div class="wcp-carousel-main-wrap <?php echo 'rpc-crsl-'.$carousel_settings['hover_effect']; ?> <?php echo (isset($carousel_settings['arrow']['position'])) ? $carousel_settings['arrow']['position'] : '' ; ?>">
        <section class="wcp-slick" <?php echo $remove_side_space; ?> <?php echo esc_html( $data_attr ); ?> id="carousel-<?php echo esc_attr( $attrs['id'] ); ?>">    
        <?php $blog_ids = explode(",", $attrs['multisite']);
        foreach( $blog_ids as $id ) {

            switch_to_blog( $id );

            $query = new WP_Query( $args );
         
            while( $query->have_posts()) : $query->the_post(); ?>
                <div class="slick-slide carousel-item-<?php echo get_the_id(); ?>">
                    <?php $this->render_post_box($carousel_settings['hover_effect'], get_the_id(), $carousel_settings); ?>
                </div>

            <?php endwhile;
            wp_reset_postdata();

            restore_current_blog();
        } ?>
        </section>
        </div>
    <?php } else {
        echo esc_html__( 'Carousel contents not found!', 'responsive-posts-carousel' );
    }

    // Init Vars
    $carousel_id = esc_attr( $attrs['id'] );
    $arrow_color = esc_html( $carousel_settings['arrow_color'] );
    $carousel_background = (isset($carousel_settings['carousel_background'])) ? esc_html( $carousel_settings['carousel_background'] ) : '' ;
    $margin = esc_html( $carousel_settings['slider']['margin'] );
    $images_height = (isset($carousel_settings['images_height'])) ? esc_html( $carousel_settings['images_height'] ) : '';

    // Styles and Colors

    echo "<style>";
    echo "#carousel-{$carousel_id} { background-color: $carousel_background; } ";
    echo "#carousel-{$carousel_id} .slick-slide { margin: 2px {$margin}; } ";
    echo "#carousel-{$carousel_id}.slick-vertical .slick-slide { margin: {$margin} 2px; } ";
    echo "#carousel-{$carousel_id} .fixed-height-image { height: $images_height; } ";

    $all_styles = $this->get_carousel_styles();
    foreach ($all_styles as $carousel_data) {
        if($carousel_data['id'] == $carousel_settings['hover_effect']){
            $settings = $carousel_data['settings'];
        }
    }

    foreach ($settings as $field) {
        if (isset($carousel_styles[$field['id']])) {
            $selector = esc_html( $field['selector'] );
            $property = esc_attr( $field['property'] );
            $value = esc_html( $carousel_styles[$field['id']] );
            if ($property == 'content') {
                echo "#carousel-{$carousel_id} .slick-slide {$selector} { {$property}: '".$value."' !important; } ";                
            } else {
                echo "#carousel-{$carousel_id} .slick-slide {$selector} { {$property}: {$value} !important; } ";
                if ($carousel_settings['hover_effect'] == '51' && $field['id'] == 'rpc_price_bg') {
                    echo "#carousel-{$carousel_id} .slick-slide .rpc_price:before { border-color: transparent {$value} transparent !important; } ";
                }
                if ($carousel_settings['hover_effect'] == '51' && $field['id'] == 'rpc_title_bg') {
                    echo "#carousel-{$carousel_id} .slick-slide .rpc_title:before { border-color: transparent transparent transparent {$value} !important; } ";
                }
                if ($carousel_settings['hover_effect'] == '51' && $field['id'] == 'rpc_btn_bg') {
                    echo "#carousel-{$carousel_id} .slick-slide .rpc-style-51 a:before { border-color: transparent transparent {$value} !important; } ";
                }
            }
        }
    }

    // Arrow
    
    echo ".topright #carousel-{$carousel_id} .slick-next { right: {$margin}; }";
    echo ".topright #carousel-{$carousel_id} .slick-prev { right: ".(intval($margin)+29)."px; left: inherit; } ";

    echo ".topleft #carousel-{$carousel_id} .slick-prev { left: {$margin}; }";
    echo ".topleft #carousel-{$carousel_id} .slick-next { left: ".(intval($margin)+29)."px; right: inherit; } ";

    echo ".aboveposts #carousel-{$carousel_id} .slick-prev { left: {$margin}; }";
    echo ".aboveposts #carousel-{$carousel_id} .slick-next { right: {$margin}; left: inherit; } ";

    // Dots Color
    if (isset($carousel_settings['dots_color'])) {
        echo "#carousel-{$carousel_id} .slick-dots li button:before { color: {$carousel_settings['dots_color']} ; }";
    }

    echo "#carousel-{$carousel_id} .slick-prev:before, #carousel-{$carousel_id} .slick-next:before { color: $arrow_color !important;}";
    if (isset($carousel_settings['arrow']['type']) && $carousel_settings['arrow']['type'] != '') {
        $arrow_codes = $this->get_arrow_codes($carousel_settings['arrow']['type']);
        echo "#carousel-{$carousel_id} .slick-next:before { content: '\ ".esc_html( $arrow_codes[0] )." '; } ";
        echo "#carousel-{$carousel_id} .slick-prev:before { content: '\ ".esc_html( $arrow_codes[1] )."'; }";
    }
    if (isset($carousel_settings['arrow']['style']) && $carousel_settings['arrow']['style'] == 'circle') {
        echo "#carousel-{$carousel_id} .slick-next:before, #carousel-{$carousel_id} .slick-prev:before { background-color: ".esc_html( $carousel_settings['arrow']['bgcolor'] )."; padding: 2px 5px; border-radius: 50%; } ";
    }
    if (isset($carousel_settings['arrow']['size']) && $carousel_settings['arrow']['size'] != '') {
        echo "#carousel-{$carousel_id} .slick-next:before, #carousel-{$carousel_id} .slick-prev:before { font-size: ".esc_html( $carousel_settings['arrow']['size'] )."; }";
        echo "#carousel-{$carousel_id} .slick-next, #carousel-{$carousel_id} .slick-prev { width: ".esc_html( $carousel_settings['arrow']['size'] )."; height: ".esc_html( $carousel_settings['arrow']['size'] )."; }";
    }
    if (isset($carousel_settings['arrow']['style']) && $carousel_settings['arrow']['style'] == 'square') {
        echo "#carousel-{$carousel_id} .slick-next:before, #carousel-{$carousel_id} .slick-prev:before { background-color: ".esc_html( $carousel_settings['arrow']['bgcolor'] )."; padding: 5px; } ";
    }   

    echo (isset($carousel_settings['custom_css'])) ? stripcslashes($carousel_settings['custom_css']) : '' ;
    echo "</style>";

?>