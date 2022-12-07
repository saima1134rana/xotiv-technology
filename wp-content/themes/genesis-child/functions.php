<?php
/**
 * Genesis Child.
 *
 * This file adds functions to the Genesis Child Theme.
 *
 * @package Genesis Child
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://www.studiopress.com/
 */

// Starts the engine.
require_once get_template_directory() . '/lib/init.php';

// Sets up the Theme.
require_once get_stylesheet_directory() . '/lib/theme-defaults.php';

add_action( 'after_setup_theme', 'genesis_sample_localization_setup' );
/**
 * Sets localization (do not remove).
 *
 * @since 1.0.0
 */
function genesis_sample_localization_setup() {

	load_child_theme_textdomain( genesis_get_theme_handle(), get_stylesheet_directory() . '/languages' );

}

// Adds helper functions.
require_once get_stylesheet_directory() . '/lib/helper-functions.php';

// Adds image upload and color select to Customizer.
require_once get_stylesheet_directory() . '/lib/customize.php';

// Includes Customizer CSS.
require_once get_stylesheet_directory() . '/lib/output.php';

// Adds WooCommerce support.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-setup.php';

// Adds the required WooCommerce styles and Customizer CSS.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-output.php';

// Adds the Genesis Connect WooCommerce notice.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-notice.php';

add_action( 'after_setup_theme', 'genesis_child_gutenberg_support' );

//enque again css to avoid cache issue
add_action( 'genesis_meta', 'genesis_load_child_stylesheet' );

function genesis_load_child_stylesheet(){
	wp_dequeue_style(genesis_get_theme_handle());
	wp_enqueue_style(
		genesis_get_theme_handle(),
		get_stylesheet_uri(),
		false,
		'1.1.0'.time()
	);
}

// 4.11.2

/**
 * Adds Gutenberg opt-in features and styling.
 *
 * @since 2.7.0
 */
function genesis_child_gutenberg_support() { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- using same in all child themes to allow action to be unhooked.
	require_once get_stylesheet_directory() . '/lib/gutenberg/init.php';
}

// Registers the responsive menus.
if ( function_exists( 'genesis_register_responsive_menus' ) ) {
	genesis_register_responsive_menus( genesis_get_config( 'responsive-menus' ) );
}

add_action( 'wp_enqueue_scripts', 'genesis_sample_enqueue_scripts_styles' );
/**
 * Enqueues scripts and styles.
 *
 * @since 1.0.0
 */
function genesis_sample_enqueue_scripts_styles() {

	$appearance = genesis_get_config( 'appearance' );

	wp_enqueue_style( // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- see https://core.trac.wordpress.org/ticket/49742
		genesis_get_theme_handle() . '-fonts',
		$appearance['fonts-url'],
		[],
		null
	);
	wp_enqueue_style( 'custom', get_stylesheet_directory_uri() . '/css/font-awesome.css' );
	wp_enqueue_style ('bootstrap_css','https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css');
	wp_enqueue_style( 'sass', get_stylesheet_directory_uri() . '/sass/main.css' );
	wp_enqueue_style ('owl_css','https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css');
	wp_enqueue_style ('owl_theme','https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css');
	wp_enqueue_script ('owl_theme_js','https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js');
	wp_enqueue_script ('bootstrap_js','https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js');

	wp_enqueue_style( 'dashicons' );

	if ( genesis_is_amp() ) {
		wp_enqueue_style(
			genesis_get_theme_handle() . '-amp',
			get_stylesheet_directory_uri() . '/lib/amp/amp.css',
			[ genesis_get_theme_handle() ],
			genesis_get_theme_version()
		);
	}

}

add_filter( 'body_class', 'genesis_sample_body_classes' );
/**
 * Add additional classes to the body element.
 *
 * @since 3.4.1
 *
 * @param array $classes Classes array.
 * @return array $classes Updated class array.
 */
function genesis_sample_body_classes( $classes ) {

	if ( ! genesis_is_amp() ) {
		// Add 'no-js' class to the body class values.
		$classes[] = 'no-js';
	}
	return $classes;
}

add_action( 'genesis_before', 'genesis_sample_js_nojs_script', 1 );
/**
 * Echo the script that changes 'no-js' class to 'js'.
 *
 * @since 3.4.1
 */
function genesis_sample_js_nojs_script() {

	if ( genesis_is_amp() ) {
		return;
	}

	?>
	<script>
	//<![CDATA[
	(function(){
		var c = document.body.classList;
		c.remove( 'no-js' );
		c.add( 'js' );
	})();
	//]]>
	</script>
	<?php
}

add_filter( 'wp_resource_hints', 'genesis_sample_resource_hints', 10, 2 );

/**
 * Add preconnect for Google Fonts.
 *
 * @since 3.4.1
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed.
 * @return array URLs to print for resource hints.
 */
function genesis_sample_resource_hints( $urls, $relation_type ) {

	if ( wp_style_is( genesis_get_theme_handle() . '-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = [
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		];
	}

	return $urls;
}

add_action( 'after_setup_theme', 'genesis_sample_theme_support', 9 );
/**
 * Add desired theme supports.
 *
 * See config file at `config/theme-supports.php`.
 *
 * @since 3.0.0
 */
function genesis_sample_theme_support() {

	$theme_supports = genesis_get_config( 'theme-supports' );

	foreach ( $theme_supports as $feature => $args ) {
		add_theme_support( $feature, $args );
	}

}

add_action( 'after_setup_theme', 'genesis_sample_post_type_support', 9 );
/**
 * Add desired post type supports.
 *
 * See config file at `config/post-type-supports.php`.
 *
 * @since 3.0.0
 */
function genesis_sample_post_type_support() {

	$post_type_supports = genesis_get_config( 'post-type-supports' );

	foreach ( $post_type_supports as $post_type => $args ) {
		add_post_type_support( $post_type, $args );
	}

}

// Adds image sizes.
add_image_size( 'sidebar-featured', 75, 75, true );
add_image_size( 'genesis-singular-images', 702, 526, true );

// Add header right widget area.
register_sidebar( 'header-right' );

// Removes secondary sidebar.
unregister_sidebar( 'sidebar-alt' );

// Removes site layouts.
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

// Repositions primary navigation menu Old
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_header', 'genesis_do_nav');

// Reposition the primary navigation menu New

 //remove_action( 'genesis_after_header', 'genesis_do_nav' );
 //add_action( 'genesis_before_header', 'genesis_do_nav' );

// Repositions the secondary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_footer', 'genesis_do_subnav', 10 );

//add Primary Social icons

genesis_register_sidebar( array(
	'id'          => 'header-social-icons',
	'name'        => __( 'Header Social Icons', 'genesis-child-theme' ),
	'description' => __( 'This is the social menu section for header.', 'genesis-child-theme' ),
) );

add_filter( 'genesis_nav_items', 'header_social_icons', 10, 2 );
add_filter( 'wp_nav_menu_items', 'header_social_icons', 10, 2 );

function header_social_icons($menu, $args) {
	$args = (array)$args;
	if ( 'primary' !== $args['theme_location'] )
		return $menu;
	ob_start();
	genesis_widget_area('header-social-icons');
	$social = ob_get_clean();
	$social=$social;
	return $menu . $social;
}

////Mobile View Contact Details
genesis_register_sidebar( array(
    'id'              		=> 'after-header-contact-mobile',
    'name'         	 	=> __( 'Mobile View After Header Contact', 'wpsites' ),
    'description'  	=> __( 'Header responsive widget area', 'wpsites' ),
) );

add_action ('genesis_after_header','genesis_child_after_header_contact', 7);

function genesis_child_after_header_contact ()  {	
	
	echo '<div class="after-header-contact">';
	genesis_widget_area( 'after-header-contact-mobile', array(
			'before' => '',
			'after' => '',
		) );
	echo '</div>';

}

add_filter( 'wp_nav_menu_args', 'genesis_sample_secondary_menu_args' );

//Add Banner for inner Pages

genesis_register_sidebar( array(
    'id'              		=> 'inner-page-hero-banner',
    'name'         	 	=> __( 'Inner Page Hero Banner', 'wpsites' ),
    'description'  	=> __( 'Inner Page Hero Banner', 'wpsites' ),
) );

add_action ('genesis_after_header','genesis_child_after_inner_banner', 7);

function genesis_child_after_inner_banner ()  {	
    if ( !is_front_page() && !is_home() ) {
        echo '<div class="hero-banner inner-page">';
        genesis_widget_area( 'inner-page-hero-banner', array(
                'before' => '',
                'after' => '',
            ) );
        echo '</div>';
    }

}

/**
 * Reduces secondary navigation menu to one level depth.
 *
 * @since 2.2.3
 *
 * @param array $args Original menu options.
 * @return array Menu options with depth set to 1.
 */
function genesis_sample_secondary_menu_args( $args ) {

	if ( 'secondary' === $args['theme_location'] ) {
		$args['depth'] = 1;
	}

	return $args;

}

add_filter( 'genesis_author_box_gravatar_size', 'genesis_sample_author_box_gravatar' );
/**
 * Modifies size of the Gravatar in the author box.
 *
 * @since 2.2.3
 *
 * @param int $size Original icon size.
 * @return int Modified icon size.
 */
function genesis_sample_author_box_gravatar( $size ) {

	return 90;

}

add_filter( 'genesis_comment_list_args', 'genesis_sample_comments_gravatar' );

/**
 * Modifies size of the Gravatar in the entry comments.
 *
 * @since 2.2.3
 *
 * @param array $args Gravatar settings.
 * @return array Gravatar settings with modified size.
 */
function genesis_sample_comments_gravatar( $args ) {

	$args['avatar_size'] = 60;
	return $args;

}
add_filter('next_posts_link_attributes', 'posts_link_next_attributes');
add_filter('previous_posts_link_attributes', 'posts_link_prev_attributes');

function posts_link_prev_attributes() {
  return 'class="pg-item pg-nav-item pg-prev btn btn-primary float-left"';
}
function posts_link_next_attributes() {
  return 'class="pg-item pg-nav-item pg-nextbtn btn btn-primary float-right"';
}
add_action( 'get_header', 'remove_page_title' );
function remove_page_title() {
    if ( is_page() ) {
        remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
    }else {
        remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
        add_action( 'genesis_before_content_sidebar_wrap', 'genesis_do_post_title', 2 );
    }
}
add_filter( 'genesis_sidebar_title_output', 'genesis_sidebar_title_output_new',1,2);
function genesis_sidebar_title_output_new($heading,$id) {
    global $wp_registered_sidebars;
    $name = $id;
    if ( array_key_exists( $id, $wp_registered_sidebars ) ) {
        $name = $wp_registered_sidebars[ $id ]['name'];
    }
    $heading = '<p class="genesis-sidebar-title screen-reader-text">' . $name . '</p>';
    return $heading;
}