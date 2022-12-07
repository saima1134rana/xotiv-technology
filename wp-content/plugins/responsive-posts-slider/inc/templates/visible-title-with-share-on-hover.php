<figure class="visible-title-with-share-on-hover rpc-box">
    <?php do_action( 'rpc_carousel_thumbnail', $post_id, $carousel_settings ); ?>
    <figcaption>
        <h2 class="rpc-title rpc-title-bg"><?php do_action( 'rpc_carousel_title', $post_id,  $carousel_settings ); ?></h2>
        <p class="rpc-content rpc-bg"><?php do_action( 'rpc_carousel_desc', $post_id, $carousel_settings); ?></p>
        <div class="icons">
            <?php do_action( 'rpc_social_share_icons', $post_id, $carousel_settings ); ?>   
        </div>
    </figcaption>           
</figure>