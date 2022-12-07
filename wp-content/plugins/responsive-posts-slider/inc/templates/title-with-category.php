<figure class="carousel-style10 rpc-box">
  <div class="image">
    <?php do_action( 'rpc_carousel_thumbnail', $post_id, $carousel_settings ); ?>
  </div>
  <figcaption class="rpc-bg">
    <h5 class="rpc-title">
        <?php $categories = get_the_category();
          $separator = ' , ';
          $output = '';
          if ( ! empty( $categories[0] ) ) {
            echo esc_html( $categories[0]->name );
          }
        ?>
    </h5>
    <h3 class="rpc-content">
      <?php do_action( 'rpc_carousel_title', $post_id,  $carousel_settings ); ?>
    </h3>
    <footer class="rpc-date">
      <div class="date"><?php echo get_the_date(); ?></div>
      <div class="icons">
        <div class="love"><i class="fa fa-comment"></i>
          <?php
            $comments = wp_count_comments(get_the_id());
            echo esc_attr( $comments->total_comments );
          ?>
        </div>
      </div>
    </footer>
  </figcaption>
  <a href="<?php echo get_permalink($post_id); ?>" target="<?php echo esc_attr($carousel_settings['read_more_target']); ?>"></a>
</figure>