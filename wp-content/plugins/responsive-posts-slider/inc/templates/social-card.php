<figure class="social-card rpc-box">
  <div class="image">
  	<?php do_action( 'rpc_carousel_thumbnail', $post_id, $carousel_settings ); ?>
    <div class="icons">
      <?php do_action( 'rpc_social_share_icons', $post_id, $carousel_settings ); ?>	
    </div>
  </div>
  <figcaption class="rpc-bg">
    <h3 class="rpc-title rpc-title-bg"><?php do_action( 'rpc_carousel_title', $post_id,  $carousel_settings ); ?></h3>
    <p class="rpc-content rpc-bg"><?php do_action( 'rpc_carousel_desc', $post_id, $carousel_settings); ?></p>
  </figcaption>
  <a href="<?php echo get_permalink($post_id); ?>" target="<?php echo esc_attr($carousel_settings['read_more_target']); ?>"></a>
</figure>