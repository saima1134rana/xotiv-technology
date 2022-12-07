<figure class="float-sharing-on-hover rpc-box">
    <?php do_action( 'rpc_carousel_thumbnail', $post_id, $carousel_settings ); ?>
    <h3 class="rpc-title"><?php do_action( 'rpc_carousel_title', $post_id,  $carousel_settings ); ?></h3>
    <div class="icons">
        <?php do_action( 'rpc_social_share_icons', $post_id, $carousel_settings ); ?>	
    </div>
    <div class="corner"><i class="fa fa-plus"></i></div>
</figure>