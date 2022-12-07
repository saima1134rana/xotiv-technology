<figure class="carousel-style12 rpc-box">
  <?php do_action( 'rpc_carousel_thumbnail', $post_id, $carousel_settings ); ?>
  <?php global $post; $author_id = $post->post_author; ?>
  <figcaption class="rpc-bg">
  	<?php echo get_avatar( $author_id, 80, '', '', array('class' => 'profile') ); ?>
    <h2 class="rpc-title">
    	<?php do_action( 'rpc_carousel_title', $post_id,  $carousel_settings ); ?>
    	<span><?php the_author_meta( 'display_name', $author_id ); ?></span>
    </h2>
    <p class="rpc-content">
      <?php do_action( 'rpc_carousel_desc', $post_id, $carousel_settings); ?>
    </p>
    <a target="<?php echo esc_attr($carousel_settings['read_more_target']); ?>" href="<?php echo get_author_posts_url( $author_id ); ?>" class="follow"><?php esc_html_e( 'Profile', 'responsive-posts-carousel' ); ?></a>
    <a target="<?php echo esc_attr($carousel_settings['read_more_target']); ?>" href="<?php the_permalink(); ?>" class="info"><?php esc_html_e( 'More Info', 'responsive-posts-carousel' ); ?></a>
  </figcaption>
</figure>