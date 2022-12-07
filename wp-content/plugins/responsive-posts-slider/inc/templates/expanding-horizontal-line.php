<figure class="rpc-box expanding-horizonal-line rpc-bg">
    <?php do_action( 'rpc_carousel_thumbnail', $post_id, $carousel_settings ); ?>
    <figcaption>
        <div><h2 class="rpc-title"><?php do_action( 'rpc_carousel_title', $post_id,  $carousel_settings ); ?></h2></div>
        <div><p class="rpc-content"><?php do_action( 'rpc_carousel_desc', $post_id, $carousel_settings); ?></p></div>
        <a href="<?php echo get_permalink($post_id); ?>" target="<?php echo esc_attr($carousel_settings['read_more_target']); ?>"></a>
    </figcaption>           
</figure>