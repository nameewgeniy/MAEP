<?php 

/*
Plugin Name: NP eBay Store
Plugin URI: http://ebayaffiliatepartner.com
Description:  NP eBay Store is an affiliate script by eBay
Version: 2.0.2
Text Domain: gen
Requires at least: WP 4.0
Author: Evgeniy Nakapyuk
Author URI: nakapyuk.com
*/
if ( !defined('GAP_VERSION') ) define( 'GAP_VERSION', '2.0.2' );
define( 'AKISMET__MINIMUM_WP_VERSION', '3.1' );
define( 'AKISMET__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'AKISMET__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'AKISMET_DELETE_LIMIT', 100000 );

require_once('lib/setup.php');

register_deactivation_hook(__FILE__, 'deactivation_maep_plugin');
function deactivation_maep_plugin() {
    wp_clear_scheduled_hook('update_plugin_product');
}
var_dump($ser);

register_activation_hook(__FILE__, 'update_prod');
?>