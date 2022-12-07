<?php
$labels = array(
	'name'               => esc_html_x( 'Carousels', 'Carousel', 'responsive-posts-carousel' ),
	'singular_name'      => esc_html_x( 'Carousel', 'Carousel', 'responsive-posts-carousel' ),
	'menu_name'          => esc_html_x( 'Carousels', 'admin menu', 'responsive-posts-carousel' ),
	'name_admin_bar'     => esc_html_x( 'Carousel', 'Carousels', 'responsive-posts-carousel' ),
	'add_new'            => esc_html_x( 'Add New', 'Carousel', 'responsive-posts-carousel' ),
	'add_new_item'       => esc_html__( 'Add New Carousel', 'responsive-posts-carousel' ),
	'new_item'           => esc_html__( 'New Carousel', 'responsive-posts-carousel' ),
	'edit_item'          => esc_html__( 'Edit Carousel', 'responsive-posts-carousel' ),
	'view_item'          => esc_html__( 'View Carousel', 'responsive-posts-carousel' ),
	'all_items'          => esc_html__( 'All Carousels', 'responsive-posts-carousel' ),
	'search_items'       => esc_html__( 'Search Carousels', 'responsive-posts-carousel' ),
	'parent_item_colon'  => esc_html__( 'Parent Carousels:', 'responsive-posts-carousel' ),
	'not_found'          => esc_html__( 'No Carousel found.', 'responsive-posts-carousel' ),
	'not_found_in_trash' => esc_html__( 'No Carousel found in Trash.', 'responsive-posts-carousel' )
);

$args = array(
	'labels'             => $labels,
    'description'        => esc_html__( 'Create Posts Carousels.', 'responsive-posts-carousel' ),
	'public'             => false,
	'publicly_queryable' => false,
	'show_ui'            => true,
	'show_in_menu'       => true,
	'menu_icon'       	 => 'dashicons-editor-insertmore',
	'query_var'          => true,
	'capability_type'    => 'post',
	'has_archive'        => false,
	'hierarchical'       => false,
	'supports'           => array( 'title' )
);

register_post_type( 'wcp_carousel', $args );
?>