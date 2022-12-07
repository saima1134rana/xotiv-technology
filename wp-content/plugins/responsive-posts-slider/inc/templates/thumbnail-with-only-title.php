<figure class="thumbnail-with-only-title rpc-box">
    <?php do_action( 'rpc_carousel_thumbnail', $post_id, $carousel_settings ); ?>
    <figcaption>
        <div class="image">
            <img src="<?php do_action( 'rpc_carousel_thumbnail', $post_id, $carousel_settings, array(), true ); ?>">
        </div>
        <h2 class="rpc-title rpc-title-bg"><?php do_action( 'rpc_carousel_title', $post_id,  $carousel_settings ); ?></h2>  
    </figcaption>
    <a href="<?php echo get_permalink($post_id); ?>" target="<?php echo esc_attr($carousel_settings['read_more_target']); ?>"></a>            
</figure>