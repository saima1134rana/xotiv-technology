<figure class="carousel-style16 rpc-box">
  <div class="image">
    <?php do_action( 'rpc_carousel_thumbnail', $post_id, $carousel_settings ); ?>
  </div>
  <figcaption class="rpc-bg">
    <div class="date rpc-date"><span class="day"><?php echo get_the_date( 'd' ); ?></span><span class="month"><?php echo get_the_date( 'M' ); ?></span></div>
    <h3 class="rpc-title">
      <?php do_action( 'rpc_carousel_title', $post_id,  $carousel_settings ); ?>
    </h3>
    <p class="rpc-content">
      <?php do_action( 'rpc_carousel_desc', $post_id, $carousel_settings); ?>
    </p>
    <!-- <footer>
      <div class="views"><i class="fa fa-eye"></i>2,907</div>
      <div class="love"><i class="fa fa-heart"></i>623</div>
      <div class="comments"><i class="fa fa-comment"></i>23</div>
    </footer> -->
  </figcaption><a target="<?php echo esc_attr($carousel_settings['read_more_target']); ?>" href="<?php the_permalink(); ?>"></a>
</figure>