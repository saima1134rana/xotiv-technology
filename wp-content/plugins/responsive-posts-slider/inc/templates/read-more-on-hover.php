<figure class="carousel-style13 rpc-box">
  <?php do_action( 'rpc_carousel_thumbnail', $post_id, $carousel_settings ); ?>
  <div class="image">
  	<?php do_action( 'rpc_carousel_thumbnail', $post_id, $carousel_settings ); ?>
  </div>
  <figcaption>
    <h3 class="rpc-title">
    	<?php do_action( 'rpc_carousel_title', $post_id,  $carousel_settings ); ?>
    </h3>
    <p class="rpc-content">
		<?php do_action( 'rpc_carousel_desc', $post_id, $carousel_settings); ?>
    </p>
  </figcaption>
  <span class="read-more">   
    <?php echo ($carousel_settings['read_more_txt'] != '') ? $carousel_settings['read_more_txt'] : 'Read More' ; ?> <i class="fa fa-arrow-right"></i></span>
  <a target="<?php echo esc_attr($carousel_settings['read_more_target']); ?>" href="<?php the_permalink(); ?>"></a>
</figure>