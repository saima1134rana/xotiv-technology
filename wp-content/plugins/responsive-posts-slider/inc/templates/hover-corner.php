<figure class="carousel-style18 rpc-box">
  <div class="image">
    <?php do_action( 'rpc_carousel_thumbnail', $post_id, $carousel_settings ); ?>
    <i class="fa fa-comment"><span class="comnt-count">
          <?php
            $comments = wp_count_comments(get_the_id());
            echo esc_attr( $comments->total_comments );
          ?>
    </span></i>
    <div class="date rpc-date"><span class="day"><?php echo get_the_date( 'd' ); ?></span><span class="month"><?php echo get_the_date( 'M' ); ?></span></div>
  </div>
  <figcaption>
    <h3 class="rpc-title">
      <?php do_action( 'rpc_carousel_title', $post_id,  $carousel_settings ); ?>
    </h3>
    <p class="rpc-content">
      <?php do_action( 'rpc_carousel_desc', $post_id, $carousel_settings); ?>
    </p>
    <?php do_action( 'rpc_read_more_btn', $post_id, $carousel_settings); ?>
  </figcaption>
</figure>