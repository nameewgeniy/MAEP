<?php
function new_post_type_news()
{
    $labels = array(
        'name' => 'News eBay',
        'singular_name' => 'News eBay',
        'add_new' => 'Add new',
        'add_new_item' => 'Add new',
        'edit_item' => 'Edit news',
        'new_item' => 'New news',
        'all_items' => 'All news',
        'view_item' => 'View news',
        'search_items' => 'Find news',
        'not_found' =>  'News not found',
        'not_found_in_trash' => 'News not found',
        'menu_name' => 'News eBay'
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_ui' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-analytics',
        'menu_position' => 7,
        'supports' => array( 'title', 'editor', 'custom-fields','thumbnail'),
        'capability_type' => 'post'
    );
    register_post_type('news_ebay', $args);
}
add_action('init', 'new_post_type_news');
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