<?php
/**
* Main Class for Responsive Posts Carousel
*/
class WCP_Responsive_Posts_Carousel
{
	
	function __construct()
	{
		add_action( 'init', array($this, 'register_carousels') );
		add_action( 'add_meta_boxes', array($this, 'carousel_metaboxes' ), 10, 2 );
		add_action( 'admin_enqueue_scripts', array($this, 'admin_scripts' ) );
		add_shortcode( 'wcp-carousel', array($this, 'render_shortcode') );
		add_action( 'wp_ajax_rpc_get_posts', array($this, 'rpc_get_posts') );
		add_action( 'wp_ajax_rpc_get_terms', array($this, 'rpc_get_terms') );
		add_action( 'save_post', array($this, 'save_carousel' ) );
		add_filter( 'post_updated_messages', array($this, 'carousel_messages' ) );

		add_action( 'rpc_carousel_thumbnail', array($this, 'render_carousel_thumbnail' ), 10, 4 );
		add_action( 'rpc_carousel_title', array($this, 'render_carousel_title' ), 10, 2 );
		add_action( 'rpc_carousel_desc', array($this, 'render_carousel_desc' ), 10, 2 );
		add_action( 'rpc_read_more_btn', array($this, 'render_read_more_btn' ), 10, 2 );
		add_action( 'rpc_social_share_icons', array($this, 'render_social_share' ), 10, 2 );

        add_filter('manage_wcp_carousel_posts_columns', array($this, 'wcp_carousel_column_head'));
        add_action('manage_wcp_carousel_posts_custom_column', array($this, 'wcp_carousel_column_content'), 10, 2);		

        add_action( 'plugins_loaded', array($this, 'wcp_load_plugin_textdomain' ) );

        add_action( 'wp_ajax_rpc_get_template_preview', array($this, 'get_template_preview') );

        // add_action( 'enqueue_block_editor_assets', array($this, 'block_editor_assets') );

	}

	/**
	 * Register a carousels post type.
	 * since 1.0
	 */
	function register_carousels() {
		include RPC_PATH.'/inc/register-carousel.php';
        if (function_exists('register_block_type')) {
        	$settings = array();
        	$settings['render_callback'] = array($this, 'render_block_ss');
        	$settings['attributes'] = array('id' => array('type' => 'string'));
            register_block_type( 'responsive-posts-carousel/carousel', $settings );
        }
	}

	function render_block_ss($attributes){
		return $this->render_shortcode($attributes);
	}

	/**
	 * Returns carousel settings fields.
	 * since 1.0
	 */
	function rpc_settings_fields() {
		$fields = array();
		include RPC_PATH.'/inc/settings-fields.php';
		return $fields;
	}

    function wcp_load_plugin_textdomain(){
        load_plugin_textdomain( 'responsive-posts-carousel', FALSE, basename( RPC_PATH ) . '/languages/' );
    }

	/**
	 * Returns carousel settings tabs.
	 * since 1.0 modified 12.0
	 */
	function rpc_settings_tabs() {
		$tabs = array(
			array(
				'label' => esc_html__( 'Contents', 'responsive-posts-carousel' ),
				'name' => 'contents',
				'icon' => 'dashicons-media-text',
			),
			array(
				'label' => esc_html__( 'Slider', 'responsive-posts-carousel' ),
				'name' => 'slider',
				'icon' => 'dashicons-slides',
			),
			array(
				'label' => esc_html__( 'Appearance', 'responsive-posts-carousel' ),
				'name' => 'colors',
				'icon' => 'dashicons-admin-appearance',
			),
			array(
				'label' => esc_html__( 'Arrows', 'responsive-posts-carousel' ),
				'name' => 'arrows',
				'icon' => 'dashicons-leftright',
			),
			array(
				'label' => esc_html__( 'Advanced', 'responsive-posts-carousel' ),
				'name' => 'advanced',
				'icon' => 'dashicons-admin-generic',
			),
		);
		
		return apply_filters( 'rpc_admin_setting_tabs', $tabs );
	}

	function carousel_metaboxes( $post_type, $post ) {
	    add_meta_box( 'rpc-settings', 'Carousel Settings', array($this, 'render_carousel_settings'), 'wcp_carousel', 'normal');
	    add_meta_box( 'rpc-template', 'Template Settings', array($this, 'render_template_settings'), 'wcp_carousel', 'normal');
	    add_meta_box( 'wcp-shortcode', 'Shortcode', array($this, 'render_sc_box'), 'wcp_carousel', 'side');
	    add_meta_box( 'wcp-help', 'Quick Links', array($this, 'render_help_box'), 'wcp_carousel', 'side');
	}

	/**
	 * Renders Carousel Settings.
	 * since 1.0
	 */
	function render_carousel_settings() {
		global $post;
		$carousel_id = '';
		if (isset($post->ID)) {
			$carousel_id = $post->ID;
		}
		$carousel_meta = get_post_meta( $carousel_id, 'carousel_meta', true );
		$fields = $this->rpc_settings_fields();
		$tabs = $this->rpc_settings_tabs();
		wp_nonce_field( plugin_basename( __FILE__ ), 'rpc_carousel_nonce' );
		include RPC_PATH.'/inc/render-settings.php';
	}

	/**
	 * Renders Carousel Settings.
	 * since 1.0
	 */
	function render_template_settings() {
		global $post;
		$carousel_id = '';
		if (isset($post->ID)) {
			$carousel_id = $post->ID;
		}
		$template_settings = get_post_meta( $carousel_id, 'rpc_template_settings', true );
		include RPC_PATH.'/inc/render-template-settings.php';
	}

	function get_carousel_styles(){
		$styles = array();
		include RPC_PATH.'/inc/carousel-styles.php';
		return apply_filters( 'rpc_carousel_styles', $styles );
	}	

	/**
	 * Renders Input Field for settings.
	 * since 1.0
	 */
	function render_input_field($field, $carousel_meta) {

		$input_name = 'carousel_data';
		$value = '';

		if (is_array($field['key'])) {
			foreach ($field['key'] as $key) {
				$input_name .= '['.esc_attr( $key ).']';
			}
			$value = (isset($carousel_meta[$field['key'][0]][$field['key'][1]])) ? $carousel_meta[$field['key'][0]][$field['key'][1]] : '' ;
		} else {
			$input_name .= '['.esc_attr( $field['key'] ).']';
			$value = (isset($carousel_meta[$field['key']])) ? $carousel_meta[$field['key']] : '' ;
		}
		if(isset($field['multiple']) && $field['multiple'] == true) {
			$input_name .= '[]';
		}
		if (is_array($field['key']) && $field['key'][1] == '') {
			$value = (isset($carousel_meta[$field['key'][0]])) ? $carousel_meta[$field['key'][0]] : '' ;
		}
		switch ($field['type']) {
			case 'text': ?>
				<input type="text" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $value ); ?>" class="widefat">
				<?php break;

			case 'color': ?>
				<input type="text" data-alpha="true" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $value ); ?>" class="colorpicker">
				<?php break;
				
			case 'number': ?>
				<input type="number" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $value ); ?>" class="widefat">
				<?php break;
				
			case 'textarea': ?>
				<textarea name="<?php echo esc_attr( $input_name ); ?>" class="widefat"><?php echo esc_attr( $value ); ?></textarea>
				<?php break;

			case 'select':
				$multiple = (isset($field['multiple']) && $field['multiple'] == true) ? 'multiple' : '' ;
				?>
				<select name="<?php echo esc_attr( $input_name ); ?>"
				<?php echo esc_attr( $multiple ); ?> class="widefat">
					<?php foreach ($field['options'] as $key => $title) {
						if (is_array($value)) {
							$selected = (in_array($key, $value)) ? 'selected' : '' ;
						} else {
							$selected = ($value == $key) ? 'selected' : '' ;
						}
						echo '<option value="'.esc_attr( $key ).'" '.esc_attr( $selected ).'>'.esc_html( $title ).'</option>';
					} ?>
				</select>
				<?php break;

			case 'image_size': ?>
				<select name="<?php echo esc_attr( $input_name ); ?>" class="widefat">
					<option value="" <?php echo ($value == '') ? 'selected' : '' ; ?>>
						<?php esc_html_e( 'Default', 'responsive-posts-carousel' ); ?>
					</option>
					<?php
						$image_sizes = get_intermediate_image_sizes();
						foreach ($image_sizes as $img) {
							$selected = ($value == $img) ? 'selected' : '';
							echo '<option '.esc_attr( $selected ).' value="'.esc_attr( $img ).'">'.esc_html( $img ).'</option>';
						}
					?>
				</select>
				<?php break;

			case 'taxonomy': ?>
				<select class="widefat" name="<?php echo esc_attr( $input_name ); ?>"> 
					<option value=""><?php echo esc_html( $field['title'] ); ?></option> 
					<?php 
						$taxonomies = get_taxonomies(array('public'   => true));
						foreach ($taxonomies as $tax) { 
							echo '<option value="'.esc_attr( $tax ).'" '.selected( $value, $tax ).'>'.esc_attr( $tax ).'</option>';
						}
					?>
				</select>
				<?php break;

			case 'term': ?>
			<?php
				if (isset($carousel_meta['taxonomy']) && $carousel_meta['taxonomy'] != '') {
					$terms = get_terms( $carousel_meta['taxonomy'] );
					if (empty($terms)) {
						echo esc_html__( 'Sorry! this Taxonomy has no Terms.', 'responsive-posts-carousel' );
					} else {
						echo '<select class="widefat" multiple name="'.esc_attr( $input_name ).'">';
						// already saved
						if (isset($carousel_meta['term']) && is_array($carousel_meta['term'])) {
							foreach ($carousel_meta['term'] as $saved_term_id) {
								foreach ($terms as $term_data) {
									if ($saved_term_id == $term_data->term_id) {
										echo '<option value="'.esc_attr( $term_data->term_id ).'" selected>'.esc_attr( $term_data->name ).'('.esc_attr( $term_data->count ).')</option>';
									}
								}
							}
						}

						foreach ($terms as $key => $term_data) {
							if (!in_array($term_data->term_id, $value)) {
								echo '<option value="'.esc_attr( $term_data->term_id ).'">'.esc_attr( $term_data->name ).'('.esc_attr( $term_data->count ).')</option>';
							}
						}
						echo '</select>';			
					}					
				} else { ?>
					<p class="description"><?php esc_html_e( 'Please select any taxonomy first', 'responsive-posts-carousel' ); ?>.</p>
				<?php }
			?>
				<?php break;

			case 'post_type': ?>
				<select name="<?php echo esc_attr( $input_name ); ?>" class="widefat">
					<option value=""><?php echo esc_html( $field['title'] ); ?></option>
						<?php $post_types = get_post_types( array( 'public' => true, ) );
							foreach ($post_types as $name => $label) {
								$selected = (isset($carousel_meta['post_type']) && $carousel_meta['post_type'] == $name) ? 'selected' : '' ;
								echo '<option value="'.esc_attr( $name ).'" '.esc_attr( $selected ).'>'.esc_html( $label ).'</option>';
							}
						?>
				</select>
				<?php break;

			case 'posts': ?>
				<select name="<?php echo esc_attr( $input_name ); ?>" class="widefat" multiple>
					<?php

						if (isset($carousel_meta['post_type']) && $carousel_meta['post_type'] != '') {
							$all_posts = get_posts( array('post_type' => $carousel_meta['post_type'], 'posts_per_page' => 500 ) );
							$selc = (is_array($value) && in_array('all', $value)) ? 'selected' : '' ;
							echo '<option value="all" '.esc_attr( $selc ).'> All '.esc_attr( $carousel_meta['post_type'] ).'s</option>';

							if (isset($value) && is_array($value)) {
								foreach ($value as $saved_post_id) {
									foreach ($all_posts as $key => $post_obj) {
										if ($saved_post_id == $post_obj->ID) {
											echo '<option value="'.esc_attr( $post_obj->ID ).'" selected>'.esc_attr( $post_obj->post_title ).'</option>';
										}
									}
								}
							}

							foreach ($all_posts as $key => $post_obj) {
								if (!in_array($post_obj->ID, $value)) {
									echo '<option value="'.esc_attr( $post_obj->ID ).'">'.esc_attr( $post_obj->post_title ).'</option>';
								}
							}
						}
					?>				
				</select>
				<?php break;

			case 'carousel_styles': ?>
				<select name="<?php echo esc_attr( $input_name ); ?>" class="widefat">
					<?php
					$styles = $this->get_carousel_styles();
					foreach ($styles as $style) {
					    $selected = ($value == $style['id']) ? 'selected' : '' ; ?>
					    <option value="<?php echo esc_attr( $style['id'] ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_html( $style['name'] ); ?></option>
					<?php }
					?>
				</select>
				<?php break;

			case 'checkbox': ?>
				<label><input <?php checked( $value, 'on', true ); ?> type="checkbox" name="<?php echo esc_attr( $input_name ); ?>"><?php esc_html_e( 'Enable', 'responsive-posts-carousel' ); ?></label>
				<?php break;
			
			default:
				# code...
				break;
		}
	}

	function render_sc_box($carousel){
		if (isset($carousel->ID)) { ?>
			<p class="text-center">
				<b><?php esc_html_e( 'Default Shortcode', 'responsive-posts-carousel' ); ?></b><br>
				[wcp-carousel id="<?php echo esc_attr( $carousel->ID ); ?>"]
			</p>
			<hr>
			<p class="text-center">
				<b><?php esc_html_e( 'Display 10 Latest Posted', 'responsive-posts-carousel' ); ?></b><br>
				[wcp-carousel id="<?php echo esc_attr( $carousel->ID ); ?>" order="DESC" orderby="date" count="10"] <br>
			</p>
			<hr>
			<p class="text-center">
				<b><?php esc_html_e( 'Order by Ascending Titles', 'responsive-posts-carousel' ); ?></b><br>
				[wcp-carousel id="<?php echo esc_attr( $carousel->ID ); ?>" order="ASC" orderby="title"] <br>
			</p>
		<?php }
	}

	function render_help_box(){
		?>
			<ol style="list-style-type: square;">
				<li><a target="_blank" href="<?php echo esc_url( 'http://kb.webcodingplace.com/docs/responsive-posts-carousel/' ); ?>"><?php _e( 'Documentation', 'responsive-posts-carousel' ); ?></a></li>
				<li><a target="_blank" href="<?php echo esc_url( 'http://kb.webcodingplace.com/docs/responsive-posts-carousel/faqs/load-template-from-theme/' ); ?>"><?php _e( 'Load Template from Theme', 'responsive-posts-carousel' ); ?></a></li>
				<li><a target="_blank" href="<?php echo esc_url( 'http://kb.webcodingplace.com/docs/responsive-posts-carousel/faqs/order-or-sorting/' ); ?>"><?php _e( 'Order and Sorting', 'responsive-posts-carousel' ); ?></a></li>
				<li><a target="_blank" href="<?php echo esc_url( 'http://clients.webcodingplace.com/support/' ); ?>"><?php _e( 'Support Forum', 'responsive-posts-carousel' ); ?></a></li>
			</ol>
		<?php
	}

    function carousel_messages( $messages ) {
        $post             = get_post();
        $post_type        = get_post_type( $post );
        $post_type_object = get_post_type_object( $post_type );

        $messages['wcp_carousel'] = array(
            0  => '', // Unused. Messages start at index 1.
            1  => esc_html__( 'Carousel updated.', 'responsive-posts-carousel' ),
            2  => esc_html__( 'Custom field updated.', 'responsive-posts-carousel' ),
            3  => esc_html__( 'Custom field deleted.', 'responsive-posts-carousel' ),
            4  => esc_html__( 'Carousel updated.', 'responsive-posts-carousel' ),
            /* translators: %s: date and time of the revision */
            5  => isset( $_GET['revision'] ) ? sprintf( esc_html__( 'Carousel restored to revision', 'responsive-posts-carousel' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6  => esc_html__( 'Carousel published.', 'responsive-posts-carousel' ),
            7  => esc_html__( 'Carousel saved.', 'responsive-posts-carousel' ),
            8  => esc_html__( 'Carousel submitted.', 'responsive-posts-carousel' ),
            9  => sprintf(
                esc_html__( 'Carousel scheduled.', 'responsive-posts-carousel' ),
                // translators: Publish box date format, see http://php.net/date
                date_i18n( esc_html__( 'M j, Y @ G:i', 'responsive-posts-carousel' ), strtotime( $post->post_date ) )
            ),
            10 => esc_html__( 'Carousel draft updated.', 'responsive-posts-carousel' )
        );

        if ( $post_type_object->publicly_queryable && 'wcp_carousel' === $post_type ) {
            $permalink = get_permalink( $post->ID );

            $view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), esc_html__( 'View Carousel', 'responsive-posts-carousel' ) );
            $messages[ $post_type ][1] .= $view_link;
            $messages[ $post_type ][6] .= $view_link;
            $messages[ $post_type ][9] .= $view_link;

            $preview_permalink = add_query_arg( 'preview', 'true', $permalink );
            $preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), esc_html__( 'Preview Carousel', 'responsive-posts-carousel' ) );
            $messages[ $post_type ][8]  .= $preview_link;
            $messages[ $post_type ][10] .= $preview_link;
        }

        return $messages;
    }	

	function render_shortcode($attrs){
		if (isset($attrs['id']) && $attrs['id'] != '') {

			$carousel_settings = get_post_meta( $attrs['id'], 'carousel_meta', true );
			$carousel_styles = get_post_meta( $attrs['id'], 'rpc_template_settings', true );

			if ($carousel_styles != '' && is_array($carousel_styles)) {
				wp_enqueue_style( 'rpc-font-awesome', RPC_URL.'/assets/front/css/font-awesome.min.css' );
				wp_enqueue_style( 'rpc-styles', RPC_URL.'/assets/front/css/rpc-styles.css' );
				wp_enqueue_style( 'rpc-slick-theme-css', RPC_URL.'/assets/front/css/slick-theme.css' );
				wp_enqueue_script( 'rpc-slick-js', RPC_URL.'/assets/front/js/slick.min.js', array('jquery') );

				if (isset($carousel_settings['equal_height_mode'])) {
					wp_enqueue_script( 'images-loaded', RPC_URL.'/assets/front/js/imagesloaded.js', array('jquery') );
					wp_enqueue_script( 'images-fill', RPC_URL.'/assets/front/js/jquery-imagefill.js', array('jquery') );
				}

				if (isset($carousel_settings['sameheightcols'])) {
					wp_enqueue_script( 'rpc-match-height', RPC_URL.'/assets/front/js/jquery.matchHeight.js', array('jquery') );
				}

				$in_theme = get_stylesheet_directory().'/rpc/custom.js';
				if (file_exists($in_theme)) {
					wp_enqueue_script( 'custom-crsl-js', get_stylesheet_directory_uri().'/rpc/custom.js', array('jquery') );
				} else {
					wp_enqueue_script( 'custom-crsl-js', RPC_URL.'/assets/front/js/custom.js', array('jquery') );
				}				

				ob_start();
				include RPC_PATH.'/inc/render/render-shortcode.php';
				return ob_get_clean();
				
			} else {
				wp_enqueue_style( 'rpc-font-awesome', RPC_URL.'/assets/front/css/font-awesome.min.css' );
				wp_enqueue_style( 'slick-css', RPC_URL.'/assets/front/css/slick.css' );
				wp_enqueue_style( 'slick-theme-css', RPC_URL.'/assets/front/css/slick-theme.css' );
				wp_enqueue_style( 'ihover-css', RPC_URL.'/assets/front/css/ihover.min.css' );
				wp_enqueue_script( 'slick-js', RPC_URL.'/assets/front/js/slick.min.js', array('jquery') );
				

				if ($carousel_settings['hover_effect'] == '3d hover box') {
					wp_enqueue_style( 'vue-css', RPC_URL.'/hover-box/hoverbox.css' );
					wp_enqueue_script( 'vue-js', RPC_URL.'/hover-box/vue.js' );
					wp_enqueue_script( 'trigger-vue-js', RPC_URL.'/hover-box/hoverbox.js' );
				} elseif (isset($carousel_settings['equal_height_mode'])) {
					wp_enqueue_script( 'images-loaded', RPC_URL.'/assets/front/js/imagesloaded.js', array('jquery') );
					wp_enqueue_script( 'images-fill', RPC_URL.'/assets/front/js/jquery-imagefill.js', array('jquery') );
				}

				$in_theme = get_stylesheet_directory().'/rpc/custom.js';
				if (file_exists($in_theme)) {
					wp_enqueue_script( 'custom-crsl-js', get_stylesheet_directory_uri().'/rpc/custom.js', array('jquery') );
				} else {
					wp_enqueue_script( 'custom-crsl-js', RPC_URL.'/assets/front/js/custom.js', array('jquery') );
				}				

				ob_start();

					include RPC_PATH.'/inc/render.php';

				return ob_get_clean();
			}
		} else {
			ob_start();
				echo esc_html__( 'Sorry, it seems you did not selected any carousel.', 'responsive-posts-carousel' );
			return ob_get_clean();			
		}
	}

	function admin_scripts($slug){
		global $post;
        if ( $slug == 'post-new.php' || $slug == 'post.php') {
            if (isset($post->post_type) && 'wcp_carousel' === $post->post_type) {
            	wp_enqueue_style( 'wp-color-picker' );
            	wp_enqueue_style( 'rpc-font-awesome', RPC_URL.'/assets/front/css/font-awesome.min.css' );
            	wp_enqueue_style( 'rpc-styles', RPC_URL.'/assets/front/css/rpc-styles.css' );
				wp_enqueue_script( 'ui-block', RPC_URL.'/assets/admin/js/jquery.blockUI.js', array('jquery') );
				wp_enqueue_script( 'colorpicker-alpha', RPC_URL.'/assets/admin/js/wp-color-picker-alpha.min.js', array('jquery') );
				wp_enqueue_style( 'select2-css', RPC_URL.'/assets/admin/css/select2.min.css' );
				wp_enqueue_style( 'wcp-admin-css', RPC_URL.'/assets/admin/css/admin.css' );
				wp_enqueue_script( 'select2-js', RPC_URL.'/assets/admin/js/select2.min.js', array('jquery') );
				wp_enqueue_script( 'carousel-admin', RPC_URL.'/assets/admin/js/admin.js', array('jquery', 'wp-color-picker') );
            }
        }		
	}

	function rpc_get_posts(){
		$all_posts = get_posts( array('post_type' => $_REQUEST['post_type'], 'posts_per_page' => 500 ) );
		echo '<option value="all">'.esc_html__( 'All', 'responsive-posts-carousel' ).' '.esc_attr( $_REQUEST['post_type'] ).'s</option>';
		foreach ($all_posts as $key => $post_obj) {
			echo '<option value="'.esc_attr( $post_obj->ID ).'">'.esc_attr( $post_obj->post_title ).'</option>';
		}
		die(0);
	}

	function rpc_get_terms(){
		$terms = get_terms( $_REQUEST['taxonomy'] );
		if (empty($terms) || $_REQUEST['taxonomy'] == '') {
			echo esc_html__( 'Sorry! this Taxonomy has no Terms.', 'wcp-carousel' );
		} else {
			echo '<select class="wcp-term widefat" multiple name="carousel_data[term][]">';
			foreach ($terms as $key => $value) {
				echo '<option value="'.esc_attr( $value->term_id ).'">'.esc_attr( $value->name ).'('.esc_attr( $value->count ).')</option>';
			}
			echo '</select>';			
		}
		die(0);
	}	

	function save_carousel($post_id){
        // verify if this is an auto save routine. 
        // If it is our form has not been submitted, so we dont want to do anything
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
            return;

        // verify this came from the our screen and with proper authorization,
        // because save_post can be triggered at other times
        if ( !isset( $_POST['rpc_carousel_nonce'] ) )
            return;

        if ( !wp_verify_nonce( $_POST['rpc_carousel_nonce'], plugin_basename( __FILE__ ) ) )
            return;

        // OK, we're authenticated: we need to find and save the data
        if (isset($_POST['carousel_data']) && $_POST['carousel_data'] != '') {
            update_post_meta( $post_id, 'carousel_meta', $_POST['carousel_data'] );
        }

        if (isset($_POST['rpc_template_settings']) && $_POST['rpc_template_settings'] != '') {
            update_post_meta( $post_id, 'rpc_template_settings', $_POST['rpc_template_settings'] );
        }
	}

	function render_carousel_title($post_id, $carousel_settings){
		$title_key = ($carousel_settings['title'] != '') ? $carousel_settings['title'] : 'post_title' ;
		$words = (isset($carousel_settings['titlewords']) && $carousel_settings['titlewords'] != '') ? $carousel_settings['titlewords'] : '' ;
		echo $this->get_display_data($post_id, $title_key, $words, $carousel_settings);
	}

	function render_carousel_desc($post_id, $carousel_settings){
		$title_key = ($carousel_settings['desc'] != '') ? $carousel_settings['desc'] : 'post_date' ;
		$words = ($carousel_settings['words'] != '') ? $carousel_settings['words'] : '' ;
		echo $this->get_display_data($post_id, $title_key, $words, $carousel_settings);
	}	

	function get_display_data($post_id, $key, $length, $carousel_settings){
		$more = (isset($carousel_settings['appendmore'])) ? $carousel_settings['appendmore'] : '...' ;
		ob_start();
		if (strpos($key, ',')) {
			$exc_arr = explode(',', $key);
			$more = (isset($exc_arr[2])) ? $exc_arr[2] : '...' ;
	        $esc_text = get_the_excerpt();
	        echo wp_trim_words( $esc_text, $exc_arr[1], $more );
		} else {
	        switch ($key) {
	            case 'post_date':
	                echo get_the_date();
	                break;
	            case 'post_title':
		            if ($length != '') {
		            	echo wp_trim_words(get_the_title( $post_id) , $length, $more );
		            } else {
	                	echo get_the_title($post_id);
		            }
	                break;
	            case 'woo_price':
	            	$price = get_post_meta( $post_id, '_regular_price', true);
	                echo wc_price($price);
	                break;	                
	            case 'content':
	            	if (isset($carousel_settings['enableshortcodes'])) {
						$m_content = do_shortcode( get_the_content() );
						echo wp_trim_words($m_content , $length );	            		
	            	} else {
		            	if ($length != '') {
							echo wp_trim_words( get_the_content(), $length, $more );
		            	} else {
		            		the_content();
		            	}
	            	}
	                break;
                case 'post_author':
                    echo get_the_author();
                    break;
	            case 'excerpt':
	            	if ($length != '') {
	            		echo wp_trim_words( get_the_excerpt(), $length, $more );
	            	} else {
	            		the_excerpt();
	            	}
	                break;
	            case 'none':
	                break;
	            
	            default:
	            	if ($words != '') {
	            		$meta = get_post_meta( $post_id, $key, true );
	            		echo wp_trim_words( $meta, $words, $more );
	            	} else {
	            		echo get_post_meta( $post_id, $key, true );
	            	}	            
	                
	                break;
	        }
		}

		$display = ob_get_clean();
		return apply_filters( 'rpc_display_meta_data', $display, $post_id, $key, $length, $carousel_settings );		
	}

	function render_read_more_btn($post_id, $carousel_settings){
        if (isset($carousel_settings['read_more_txt']) && $carousel_settings['read_more_txt'] != '') { ?>
                <br>
                <a href="<?php echo get_permalink($post_id); ?>" target="<?php echo esc_attr( $carousel_settings['read_more_target'] ); ?>" class="<?php echo esc_attr( $carousel_settings['read_more_classes'] ); ?>"><?php echo esc_attr( $carousel_settings['read_more_txt'] ); ?></a>
        <?php }
	}

	function render_social_share($post_id, $carousel_settings){
        if (isset($carousel_settings['social_networks']) && $carousel_settings['social_networks'] != '') {
        	$networks = (is_array($carousel_settings['social_networks'])) ? $carousel_settings['social_networks'] : explode(",", $carousel_settings['social_networks']) ;
        } else {
        	$networks = array('post', 'facebook', 'twitter');
        }
        foreach ($networks as $network) {
        	$network = trim($network);
        	$networkdata = $this->get_share_url($network, $post_id);
        	echo '<a target="'.esc_attr( $carousel_settings['read_more_target'] ).'" href="'.esc_url( $networkdata['url'] ).'"><i class="'.esc_attr( $networkdata['icon_class'] ).'"></i></a> ';
        }
	}

	function render_carousel_thumbnail($post_id, $carousel_settings, $img_args = array(), $only_url = false){
		$images_size = (isset($carousel_settings['images_size'])) ? $carousel_settings['images_size'] : 'full' ;
		if ($only_url) {
			echo get_the_post_thumbnail_url($post_id, $images_size);
		} else {
			if (isset($carousel_settings['equal_height_mode'])) {
				echo '<div class="fixed-height-image">';
			}
			if (has_post_thumbnail( $post_id )) {
				if (isset($carousel_settings['lazy_loading']) && $carousel_settings['lazy_loading'] == 'on') {
					echo '<img data-lazy="'.get_the_post_thumbnail_url($post_id, $images_size).'">';
				} else {
					echo get_the_post_thumbnail( $post_id, $images_size, $img_args );
				}
			} else {
				if (wp_attachment_is_image( $post_id )) {
					echo '<img src="'.wp_get_attachment_url( $post_id ).'">';
				} elseif (isset($carousel_settings['placeholder_image']) && $carousel_settings['placeholder_image'] != '') {
					echo '<img src="'.esc_url( $carousel_settings['placeholder_image'] ).'">';
				}
			}
			if (isset($carousel_settings['equal_height_mode'])) {
				echo '</div>';
			}
		}
	}

    function wcp_carousel_column_head($defaults){
        $defaults['rpc_sc'] = esc_html__( 'Shortcode', 'responsive-posts-carousel' );
        $defaults['rpc_template'] = esc_html__( 'Template', 'responsive-posts-carousel' );
        return $defaults;       
    }

    function wcp_carousel_column_content($column_name, $carousel_id){
        if ($column_name == 'rpc_sc') {
            echo '[wcp-carousel id="'.esc_attr( $carousel_id ).'"]';
        }
        if ($column_name == 'rpc_template') {
        	$carousel_settings = get_post_meta( $carousel_id, 'carousel_meta', true );
            echo esc_attr( $carousel_settings['hover_effect'] );
        }
    }

    function get_arrow_codes($fa){
    	switch ($fa) {
    		case 'circle':
    			$resp = array('\f0a9', '\f0a8');
    			break;
    		case 'circleinverted':
    			$resp = array('\f18e', '\f190');
    			break;
    		case 'simple':
    			$resp = array('\f061', '\f060');
    			break;
    		case 'long':
    			$resp = array('\f178', '\f177');
    			break;
    		case 'angle':
    			$resp = array('\f105', '\f104');
    			break;
    		case 'doubleangle':
    			$resp = array('\f101', '\f100');
    			break;
    		case 'caret':
    			$resp = array('\f0da', '\f0d9');
    			break;
    		case 'caretsquare':
    			$resp = array('\f152', '\f191');
    			break;
    		case 'hand':
    			$resp = array('\f0a4', '\f0a5');
    			break;
    		case 'chevron':
    			$resp = array('\f054', '\f053');
    			break;
    		
    		default:
    			$resp = array('\f0a9', '\f0a8');
    			break;
    	}

    	return $resp;
    }

    function load_post_template($style, $post_id, $carousel_settings){
		if (strpos($style, 'square') !== false) {
			$filename = 'squares';   
		} else {
			$filename = str_replace(" ", "-", $style);
		}
		$in_theme = get_stylesheet_directory().'/rpc/'.esc_attr( $filename ).'.php';
		$in_plugin = RPC_PATH.'/inc/templates/'.esc_attr( $filename ).'.php';
		if (file_exists($in_theme)) {
			include $in_theme;
		} elseif (file_exists($in_plugin)) {
			include $in_plugin;
		} else {
			echo esc_html__( 'Template not Found', 'responsive-posts-carousel' ).' '.esc_url( $in_plugin );
		}
    }

    function render_post_box($style, $post_id, $carousel_settings){
    	$filename = 'style'.esc_attr( $style );
		$in_theme = get_stylesheet_directory().'/rpc/'.esc_attr( $filename ).'.php';
		$in_plugin = RPC_PATH.'/inc/render/templates/'.esc_attr( $filename ).'.php';
		if (file_exists($in_theme)) {
			include $in_theme;
		} elseif (file_exists($in_plugin)) {
			include $in_plugin;
		} else {
			echo esc_html__( 'Template not Found', 'responsive-posts-carousel' ).' '.esc_url( $in_plugin );
		}
    }

	function get_share_url($network, $post_id){
		$post_url = esc_url( get_permalink( $post_id ) );
		$post_title = esc_html( get_the_title( $post_id ) );
		$url = '';
		$icon_class = '';
		$protocol = (is_ssl()) ? 'https://' : 'http://' ;
		switch ($network) {
			case 'post':
				$url = $post_url;
				$icon_class = 'fa fa-external-link';
				break;
			case 'facebook':
				$url = $protocol.'facebook.com/sharer/sharer.php?u='.$post_url;
				$icon_class = 'fa fa-facebook';
				break;
			case 'twitter':
				$url = $protocol.'twitter.com/intent/tweet/?text='.$post_title.'&amp;url='.$post_url;
				$icon_class = 'fa fa-twitter';
				break;
			case 'googleplus':
				$url = $protocol.'plus.google.com/share?url='.$post_url;
				$icon_class = 'fa fa-google-plus';
				break;
			case 'tumblr':
				$url = $protocol.'www.tumblr.com/widgets/share/tool?posttype=link&amp;title='.$post_title.'&amp;caption='.$post_title.'&amp;content='.$post_url.'&amp;canonicalUrl='.$post_url.'&amp;shareSource=tumblr_share_button';
				$icon_class = 'fa fa-tumblr';
				break;
			case 'email':
				$url = 'mailto:?subject='.$post_title.'&amp;body='.$post_url;
				$icon_class = 'fa fa-envelope';
				break;
			case 'pinterest':
				$url = $protocol.'pinterest.com/pin/create/button/?url='.$post_url.'&amp;media='.$post_url.'&amp;description='.$post_title;
				$icon_class = 'fa fa-pinterest';
				break;
			case 'linkedin':
				$url = $protocol.'www.linkedin.com/shareArticle?mini=true&amp;url='.$post_url.'&amp;title='.$post_title.'&amp;summary='.$post_title.'&amp;source='.$post_title;
				$icon_class = 'fa fa-linkedin';
				break;
			case 'reddit':
				$url = $protocol.'reddit.com/submit/?url='.$post_url;
				$icon_class = 'fa fa-reddit';
				break;
			case 'xing':
				$url = $protocol.'www.xing.com/app/user?op=share;url='.$post_url.';title='.$post_title;
				$icon_class = 'fa fa-xing';
				break;
			case 'whatsapp':
				$url = $protocol.'api.whatsapp.com/send?text='.$post_title.'%20'.$post_url;
				$icon_class = 'fa fa-whatsapp';
				break;
			case 'hackernews':
				$url = $protocol.'news.ycombinator.com/submitlink?u='.$post_url.'&amp;t='.$post_title;
				$icon_class = 'fa fa-hacker-news';
				break;
			case 'vk':
				$url = $protocol.'vk.com/share.php?title='.$post_title.'&amp;url='.$post_url;
				$icon_class = 'fa fa-vk';
				break;
			case 'telegram':
				$url = $protocol.'telegram.me/share/url?text='.$post_title.'&amp;url='.$post_url;
				$icon_class = 'fa fa-telegram';
				break;
			
			default:
				# code...
				break;
		}

		$resp = array('url' => $url, 'icon_class' => $icon_class);

		return apply_filters( 'rpc_social_sharing', $resp, $post_id );
	}

	function get_template_settings_markup($id = '', $carousel_id = '', $type){
		$all_styles = $this->get_carousel_styles();
		foreach ($all_styles as $carousel_data) {
			if($carousel_data['id'] == $id){
				$settings = $carousel_data['settings'];
			}
		}
		ob_start();
		echo '<table class="rpc-list-table widefat fixed">';
		foreach ($settings as $field) {
			$this->render_template_setting_field($field, $carousel_id, $type);
		}
		echo '</table>';
		return ob_get_clean();
		die(0);
	}

	function get_template_preview(){
		$settings = '';
		$style = $_REQUEST['template_id'];
		$carousel_id = $_REQUEST['carousel_id'];
		$type = $_REQUEST['type'];
		if (file_exists(RPC_PATH .'/inc/previews/style'.esc_attr( $style ).'.php')) {
			ob_start();
				include RPC_PATH.'/inc/previews/style'.esc_attr( $style ).'.php';
			$template_html = ob_get_clean();

			$data =  array(
				'html' => $template_html,
				'settings' => $this->get_template_settings_markup($style, $carousel_id, $type),
			);
		} else {
			$data =  array(
				'html' => 'none',
				'settings' => esc_html__( 'Please choose some other template.', 'responsive-posts-carousel' ),
			);
		}

		echo json_encode($data);
		die(0);
	}

	function render_template_setting_field($field, $carousel_id, $type){
		$saved_styles = get_post_meta( $carousel_id, 'rpc_template_settings', true );
		if ($saved_styles != '' && is_array($saved_styles) && $type == 'first_time') {
			$value = (isset($saved_styles[$field['id']])) ? $saved_styles[$field['id']] : '' ;
		} else {
			$value = $field['default'];
		}
		echo '<tr>';
			if ($field['type'] == 'color') { ?>
				<td><label><?php echo esc_html( $field['label'] ); ?></label></td>
				<td><input type="text" data-alpha="true"
				value="<?php echo esc_attr( $value ); ?>"
				data-property="<?php echo esc_attr( $field['property'] ); ?>"
				data-selector="<?php echo esc_attr( $field['selector'] ); ?>"
				class="colorpicker" name="rpc_template_settings[<?php echo esc_attr( $field['id'] ); ?>]">
				</td>
			<?php } elseif ($field['type'] == 'border') { ?>
				<td><label><?php echo esc_html( $field['label'] ); ?></label></td>
				<td>
					<select class="widefat rpcselectbox" name="rpc_template_settings[<?php echo esc_attr( $field['id'] ); ?>]"
					data-property="<?php echo esc_attr( $field['property'] ); ?>"
					data-selector="<?php echo esc_attr( $field['selector'] ); ?>"					>
						<option <?php selected( $value, 'none', true ); ?> value="none"><?php esc_html_e( 'None', 'responsive-posts-carousel' ); ?></option>
						<option <?php selected( $value, 'solid', true ); ?> value="solid"><?php esc_html_e( 'Solid', 'responsive-posts-carousel' ); ?></option>
						<option <?php selected( $value, 'dotted', true ); ?> value="dotted"><?php esc_html_e( 'Dotted', 'responsive-posts-carousel' ); ?></option>
						<option <?php selected( $value, 'dashed', true ); ?> value="dashed"><?php esc_html_e( 'Dashed', 'responsive-posts-carousel' ); ?></option>
						<option <?php selected( $value, 'double', true ); ?> value="double"><?php esc_html_e( 'Double', 'responsive-posts-carousel' ); ?></option>
						<option <?php selected( $value, 'groove', true ); ?> value="groove"><?php esc_html_e( 'Groove', 'responsive-posts-carousel' ); ?></option>
						<option <?php selected( $value, 'ridge', true ); ?> value="ridge"><?php esc_html_e( 'Ridge', 'responsive-posts-carousel' ); ?></option>
						<option <?php selected( $value, 'inset', true ); ?> value="inset"><?php esc_html_e( 'Inset', 'responsive-posts-carousel' ); ?></option>
						<option <?php selected( $value, 'outset', true ); ?> value="outset"><?php esc_html_e( 'Outset', 'responsive-posts-carousel' ); ?></option>
					</select>
				</td>
			<?php } else { ?>
				<td><label><?php echo esc_html( $field['label'] ); ?></label></td>
				<td><input
				type="<?php echo esc_attr( $field['type'] ); ?>"
				class="widefat rpctextfield"
				value="<?php echo esc_attr( $value ); ?>"
				data-property="<?php echo esc_attr( $field['property'] ); ?>"
				data-selector="<?php echo esc_attr( $field['selector'] ); ?>"
				name="rpc_template_settings[<?php echo esc_attr( $field['id'] ); ?>]"></td>
			<?php }
		echo '</tr>';
	}

    function block_editor_assets(){
		$args = array( 'posts_per_page' => 500, 'post_type'=> 'wcp_carousel' );
		$list_carousels = array();
		$carousels = get_posts( $args );
		foreach ( $carousels as $post ) :
			$list_carousels[] = array(
				'label' => $post->post_title,
				'value' => $post->ID,
			);
		endforeach; 
		wp_reset_postdata();
		if (!empty($list_carousels)) {
	        wp_enqueue_script( 'rpc-block', RPC_URL.'/assets/admin/js/block.js', array('wp-blocks', 'wp-editor' , 'wp-i18n', 'wp-element'), filemtime( RPC_PATH . '/assets/admin/js/block.js' ) );
	        wp_localize_script( 'rpc-block', 'rpc_carousel_list', $list_carousels );
		}
    }
}
?>