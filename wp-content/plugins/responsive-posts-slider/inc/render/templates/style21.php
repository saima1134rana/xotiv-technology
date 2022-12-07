<figure class="rpc-style-21 rpc-wrapper">
  <figcaption class="rpc_bg">
    <h2 class="rpc_title">
      <?php do_action( 'rpc_carousel_title', $post_id,  $carousel_settings ); ?>
    </h2>
    <p class="rpc_desc">
      <?php do_action( 'rpc_carousel_desc', $post_id, $carousel_settings); ?>
    </p>
    <div class="icons">
      <?php do_action( 'rpc_social_share_icons', $post_id, $carousel_settings ); ?> 
    </div>
  </figcaption>
  <?php do_action( 'rpc_carousel_thumbnail', $post_id, $carousel_settings ); ?>
  <div class="position rpc_date">
    <?php printf( _x( '%s ago', '%s = human-readable time difference', 'responsive-posts-carousel' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?>
  </div>
</figure>