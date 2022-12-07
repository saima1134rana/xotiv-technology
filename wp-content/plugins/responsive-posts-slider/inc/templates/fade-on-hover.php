<figure class="carousel-style7 rpc-box">
  <?php do_action( 'rpc_carousel_thumbnail', $post_id, $carousel_settings ); ?>
  <div class="date rpc-date">
  	<span class="day"><?php echo get_the_date( 'd' ); ?></span>
  	<span class="month"><?php echo get_the_date( 'M' ); ?></span>
  </div>
  <figcaption>
    <h3 class="rpc-title">
		  <?php do_action( 'rpc_carousel_title', $post_id,  $carousel_settings ); ?>
    </h3>
    <p class="rpc-content">
      <?php do_action( 'rpc_carousel_desc', $post_id, $carousel_settings); ?>
    </p>
  </figcaption>
  <div class="hover rpc-overlay"><i class="fa fa-link"></i></div>
  <a href="<?php echo get_permalink($post_id); ?>" target="<?php echo esc_attr($carousel_settings['read_more_target']); ?>"></a>
</figure>