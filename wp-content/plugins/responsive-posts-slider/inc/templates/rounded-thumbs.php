<figure class="carousel-style9 rpc-box rpc-bg">
  <figcaption>
  	<?php do_action( 'rpc_carousel_thumbnail', $post_id, $carousel_settings ); ?>
    <h3 class="rpc-title">
    	<?php do_action( 'rpc_carousel_title', $post_id,  $carousel_settings ); ?>
    </h3>
    <p class="rpc-content">
    	<?php do_action( 'rpc_carousel_desc', $post_id, $carousel_settings); ?>
    </p>
	<?php do_action( 'rpc_read_more_btn', $post_id, $carousel_settings); ?>
    
  </figcaption>
</figure>