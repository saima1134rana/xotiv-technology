<figure class="rpc-style-2 rpc-wrapper">
  <?php do_action( 'rpc_carousel_thumbnail', $post_id, $carousel_settings ); ?>
  <div class="date rpc_date">
  	<span class="day"><?php echo get_the_date( 'd' ); ?></span>
  	<span class="month"><?php echo get_the_date( 'M' ); ?></span>
  </div>
  <figcaption class="rpc_bg">
    <h3 class="rpc_title">
      <?php do_action( 'rpc_carousel_title', $post_id,  $carousel_settings ); ?>
    </h3>
    <p class="rpc_desc">
      <?php do_action( 'rpc_carousel_desc', $post_id, $carousel_settings); ?>
    </p>
  </figcaption>
  <div class="hover"><i class="fa fa-link"></i></i></div>
  <a target="<?php echo esc_attr($carousel_settings['read_more_target']); ?>" href="<?php the_permalink(); ?>"></a>
</figure>