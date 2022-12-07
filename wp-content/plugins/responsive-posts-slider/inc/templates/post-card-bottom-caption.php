<figure class="carousel-style8 rpc-box">
  <div class="image">
    <?php do_action( 'rpc_carousel_thumbnail', $post_id, $carousel_settings ); ?>
  </div>
  <figcaption class="rpc-overlay">
    <div class="date rpc-date">
    	<span class="day"><?php echo get_the_date( 'd' ); ?></span>
    	<span class="month"><?php echo get_the_date( 'M' ); ?></span>
    </div>
    <h3 class="rpc-title">
      <?php do_action( 'rpc_carousel_title', $post_id,  $carousel_settings ); ?>
    </h3>
    <p class="rpc-content">
      <?php do_action( 'rpc_carousel_desc', $post_id, $carousel_settings); ?>
    </p>
  </figcaption>
  <a href="<?php echo get_permalink($post_id); ?>" target="<?php echo esc_attr($carousel_settings['read_more_target']); ?>"></a>
</figure>