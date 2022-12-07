<figure class="rpc-style-1 rpc-wrapper">
  <?php do_action( 'rpc_carousel_thumbnail', $post_id, $carousel_settings ); ?>
  <div class="date rpc_date">
  	<span class="day"><?php echo get_the_date( 'd' ); ?></span>
  	<span class="month"><?php echo get_the_date( 'M' ); ?></span>
  </div>
  <i class="fa fa-link"></i>
  <figcaption class="rpc_bg">
    <h3 class="rpc_title"><?php do_action( 'rpc_carousel_title', $post_id,  $carousel_settings ); ?></h3>
    <p class="rpc_desc">
		<?php do_action( 'rpc_carousel_desc', $post_id, $carousel_settings); ?>
    </p>
      <?php  if (isset($carousel_settings['read_more_txt']) && $carousel_settings['read_more_txt'] != '') { ?>
        <button class="<?php echo esc_attr($carousel_settings['read_more_classes']); ?>"><?php echo esc_attr($carousel_settings['read_more_txt']); ?></button>
      <?php } ?>
  </figcaption>
  <a target="<?php echo esc_attr($carousel_settings['read_more_target']); ?>" href="<?php the_permalink(); ?>"></a>
</figure>