<?php      global $wpdb;      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
$table =  $products = $wpdb->prefix . "maep_products_info";
if($wpdb->get_var("show tables like '$products'") != $products)
{
    $sql_ord = "CREATE TABLE " . $products . " (
    id int(11) NOT NULL AUTO_INCREMENT,
    id_product VARCHAR(100) NOT NULL,
    id_post VARCHAR(100) NOT NULL,
    link VARCHAR(255) NOT NULL,
    list 	MEDIUMTEXT NOT NULL,
    images TEXT(255) NOT NULL,
    price VARCHAR(100) NOT NULL,
    methods VARCHAR(100) NOT NULL,
    listing_type VARCHAR(100) NOT NULL,
    seller VARCHAR(100) NOT NULL,
    status VARCHAR(100) NOT NULL,
    type_cost VARCHAR(100) NOT NULL,
    views VARCHAR(100) NOT NULL,
    clicks VARCHAR(100) NOT NULL,
    UNIQUE KEY id (id)        );";
    dbDelta($sql_ord);      };