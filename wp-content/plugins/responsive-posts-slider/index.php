<?php
/**
 * Plugin Name: Responsive Posts Slider
 * Plugin URI: https://sejix.com
 * Description: The best Posts Slider Plugin for WordPress you will ever need.
 * Version: 1.6
 * Author: WordpressCoder
 * Author URI: https://sejix.com/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: responsive-posts-slider
 */

/*

  Copyright (C) 2019  WordpressCoder  support@sejix.com
*/

define('RPC_PATH', untrailingslashit(plugin_dir_path( __FILE__ )) );
define('RPC_URL', untrailingslashit(plugin_dir_url( __FILE__ )) );
define('RPC_VERSION', '1.6' );

require_once('slider.class.php');

if( class_exists('WCP_Responsive_Posts_Carousel')){
	
	$carousel_ob = new WCP_Responsive_Posts_Carousel;
}
?>