<?php
    global $post;
    $current_post_id = '';
    if (isset($post->ID)) {
        $current_post_id = $post->ID;
    }
    $data_attr = '';
    if(is_array($carousel_settings['slider'])){
        foreach ($carousel_settings['slider'] as $p_name => $p_val) {
            if ($p_val != '') {
                $data_attr .= ' data-'.esc_attr( $p_name ).' = '.esc_attr( $p_val );
            }
        }
    }
    
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

    $args = apply_filters( 'rpc_query_args', $args );
            
    $car_query = new WP_Query( $args );


    // The Loop
    if ( $car_query->have_posts() ) { ?>
        <div class="wcp-carousel-main-wrap <?php echo (isset($carousel_settings['arrow']['position'])) ? $carousel_settings['arrow']['position'] : '' ; ?>">
        <section class="wcp-slick" <?php echo esc_attr( $data_attr ); ?> id="carousel-<?php echo esc_attr( $attrs['id'] ); ?>">

            <?php while ( $car_query->have_posts() ) {
                $car_query->the_post(); ?>
                <div class="slick-slide carousel-item-<?php echo get_the_id(); ?>">
                    <?php $this->load_post_template($carousel_settings['hover_effect'], get_the_id(), $carousel_settings); ?>
                </div>    

            <?php } ?>
    
        </section>
        </div>
        <?php wp_reset_postdata();
    } else {
        echo esc_html__( 'Carousel contents not found!', 'responsive-posts-carousel' );
    }

    // Init Vars
    $carousel_id = esc_attr( $attrs['id'] );
    $arrow_color = esc_html( $carousel_settings['arrow_color'] );
    $title_color = esc_html( $carousel_settings['title_color'] );
    $desc_color = esc_html( $carousel_settings['desc_color'] );
    $date_bg_color = esc_html( $carousel_settings['date_bg_color'] );
    $date_text_color = esc_html( $carousel_settings['date_text_color'] );
    $overlay_bg_color = esc_html( $carousel_settings['overlay_bg_color'] );
    $shadow = esc_html( $carousel_settings['shadow'] );
    $desc_bg = esc_html( $carousel_settings['desc_bg'] );
    $title_bg = esc_html( $carousel_settings['title_bg'] );
    $carousel_background = (isset($carousel_settings['carousel_background'])) ? esc_html($carousel_settings['carousel_background'] ) : '' ;
    $border_width = esc_html( $carousel_settings['border_width'] );
    $border_type = esc_html( $carousel_settings['border_type'] );
    $border_color = esc_html( $carousel_settings['border_color'] );
    $margin = esc_attr( $carousel_settings['slider']['margin'] );
    $images_height = (isset($carousel_settings['images_height'])) ? esc_attr( $carousel_settings['images_height'] ) : '';

    // Styles and Colors

    echo "<style>";

    echo "#carousel-{$carousel_id} { background-color: $carousel_background; } ";
    echo "#carousel-{$carousel_id} .slick-prev:before, #carousel-{$carousel_id} .slick-next:before { color: $arrow_color !important;}";
    echo "#carousel-{$carousel_id} .slick-slide { margin: 2px {$margin}; } ";
    echo "#carousel-{$carousel_id} .rpc-title { color: $title_color; } ";
    echo "#carousel-{$carousel_id} .rpc-content { color: $desc_color; } ";
    echo "#carousel-{$carousel_id} .rpc-date { background-color: $date_bg_color; } ";
    echo "#carousel-{$carousel_id} .rpc-date { color: $date_text_color; } ";
    echo "#carousel-{$carousel_id} .rpc-overlay { background-color: $overlay_bg_color; } ";
    echo "#carousel-{$carousel_id} figure.social-card:before, figure.social-card:after { background-color: $overlay_bg_color; } ";
    echo "#carousel-{$carousel_id} .float-sharing-on-hover i, .float-sharing-on-hover .corner { background-color: $overlay_bg_color; color: $desc_color; } ";
    echo "#carousel-{$carousel_id} .carousel-style9 { border-top-color: $overlay_bg_color; } ";
    echo "#carousel-{$carousel_id} .carousel-style9 .button:before { background-color: $overlay_bg_color; } ";
    echo "#carousel-{$carousel_id} .rpc-box { box-shadow: $shadow; } ";
    echo "#carousel-{$carousel_id} .rpc-bg { background-color: $desc_bg; } ";
    echo "#carousel-{$carousel_id} .rpc-title-bg { background-color: $title_bg; } ";
    echo "#carousel-{$carousel_id} figure.visible-title-with-share-on-hover:after { background-color: $title_bg; } ";
    if ($border_width != '') {
        echo "#carousel-{$carousel_id} .rpc-box { border: $border_width $border_type $border_color; } ";
    }
    echo "#carousel-{$carousel_id} .fixed-height-image { height: $images_height; } ";
    if ($border_width != '' || $shadow != '') {
        echo "#carousel-{$carousel_id} .style4 { padding: 10px; } ";
        echo "#carousel-{$carousel_id} .style6 { padding: 10px; } ";
    }
    if ($overlay_bg_color != '') {
        echo "#carousel-{$carousel_id} .carousel-style11:after { border-color: transparent transparent transparent $overlay_bg_color; } ";
        echo "#carousel-{$carousel_id} figure.carousel-style18:after { background-color: $overlay_bg_color; } ";
    }
    if (isset($carousel_settings['arrow']['type']) && $carousel_settings['arrow']['type'] != '') {
        $arrow_codes = $this->get_arrow_codes($carousel_settings['arrow']['type']);
        echo "#carousel-{$carousel_id} .slick-next:before { content: '\ ".esc_html( $arrow_codes[0] )." '; } ";
        echo "#carousel-{$carousel_id} .slick-prev:before { content: '\ ".esc_html( $arrow_codes[1] )."'; }";
    }
    if (isset($carousel_settings['arrow']['style']) && $carousel_settings['arrow']['style'] == 'circle') {
        echo "#carousel-{$carousel_id} .slick-next:before, #carousel-{$carousel_id} .slick-prev:before { background-color: ".esc_html( $carousel_settings['arrow']['bgcolor'] )."; padding: 2px 5px; border-radius: 50%; } ";
    }
    if (isset($carousel_settings['arrow']['style']) && $carousel_settings['arrow']['style'] == 'square') {
        echo "#carousel-{$carousel_id} .slick-next:before, #carousel-{$carousel_id} .slick-prev:before { background-color: ".esc_html( $carousel_settings['arrow']['bgcolor'] )."; padding: 5px; } ";
    }
    if ($desc_bg != '') {
        echo "#carousel-{$carousel_id} .carousel-style12 figcaption:before { border-color: transparent transparent transparent $desc_bg; } ";
        echo "#carousel-{$carousel_id} .carousel-style14 figcaption:before { background-image: linear-gradient(to bottom, transparent 0%, $desc_bg 100%); } ";
        echo "#carousel-{$carousel_id} .carousel-style15 figcaption:before { border-color: transparent transparent transparent $desc_bg; } ";
    }
    if ($date_bg_color != '') {
        echo "#carousel-{$carousel_id} .carousel-style15 .date:before { border-color: transparent $date_bg_color transparent transparent; } ";
        echo "#carousel-{$carousel_id} figure.carousel-style18 figcaption:before { background-color: $date_bg_color; } ";
        echo "#carousel-{$carousel_id} figure.carousel-style18:before { background-color: $date_bg_color; border: none; } ";
    }
    if (isset($carousel_settings['typo']['title_font_family'])) {
        echo "#carousel-{$carousel_id} .rpc-title { font-family: {$carousel_settings['typo']['title_font_family']}; } ";
        echo "#carousel-{$carousel_id} .rpc-title { font-size: {$carousel_settings['typo']['title_font_size']}; } ";
        echo "#carousel-{$carousel_id} .rpc-content { font-family: {$carousel_settings['typo']['desc_font_family']}; } ";
        echo "#carousel-{$carousel_id} .rpc-content { font-size: {$carousel_settings['typo']['desc_font_size']}; } ";
    }
    if (isset($hidemeta)) {
        echo '.wcp-disable-post-meta { display: none !important; }';
    }    

    echo (isset($carousel_settings['custom_css'])) ? stripcslashes($carousel_settings['custom_css']) : '' ;
    echo "</style>";

?>