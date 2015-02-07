<?php
function new_post_type_reviews()
{
    $labels = array(
        'name' => 'Reviews',
        'singular_name' => 'News Reviews',
        'add_new' => 'Add new',
        'add_new_item' => 'Add new',
        'edit_item' => 'Edit Reviews',
        'new_item' => 'New Reviews',
        'all_items' => 'All Reviews',
        'view_item' => 'View Reviews',
        'search_items' => 'Find Reviews',
        'not_found' =>  'Reviews not found',
        'not_found_in_trash' => 'Reviews not found',
        'menu_name' => 'Reviews'
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_ui' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-visibility',
        'menu_position' => 7,
        'supports' => array( 'title', 'editor', 'custom-fields','thumbnail'),
        'capability_type' => 'post'
    );
    register_post_type('reviews_ebay', $args);
}
add_action('init', 'new_post_type_reviews');
/*
function new_tax_products()
{
    $labels = array(
        'name' => _x( 'Categories', 'taxonomy general name' ),
        'singular_name' => _x( 'Categories', 'taxonomy singular name' ),
        'search_items' =>  __( 'Serch Categories' ),
        'all_items' => __( 'All categories' ),
        'parent_item' => __( 'Parent Category' ),
        'parent_item_colon' => __( 'Parent Category' ),
        'edit_item' => __( 'Edit Category' ),
        'update_item' => __( 'Update Category' ),
        'add_new_item' => __( 'Add new' ),
        'new_item_name' => __( 'New name category' ),
        'menu_name' => __( 'Categories' ),
    );
    register_taxonomy('catproducts', array('maep_products'), array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'catproducts' ),
    ));
}
add_action( 'init', 'new_tax_products', 0 );*/
?>