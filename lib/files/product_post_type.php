<?php 
 function new_post_type_products()
    {
       $labels = array(
          'name' => 'Product',
          'singular_name' => 'Product',
          'add_new' => 'Add new',
          'add_new_item' => 'Add new',
          'edit_item' => 'Edit product',
          'new_item' => 'New product',
          'all_items' => 'All products',
          'view_item' => 'View product',
          'search_items' => 'Find product',
          'not_found' =>  'Products not found',
          'not_found_in_trash' => 'Products not found',
          'menu_name' => 'Products' 
          );
       $args = array(
          'labels' => $labels,
          'public' => true,
          'show_ui' => true, 
          'has_archive' => true,
          'menu_icon' => 'dashicons-cart',
          'menu_position' => 6,
          'supports' => array( 'title', 'editor', 'custom-fields','thumbnail'),
          'capability_type' => 'post'
          );
       register_post_type('maep_products', $args);
    }
    add_action('init', 'new_post_type_products');

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
    add_action( 'init', 'new_tax_products', 0 );
 ?>