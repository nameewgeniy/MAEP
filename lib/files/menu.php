<?php 

function AddMaepMenu()
   {
        $page[] = add_menu_page(__('eBay Affilate','maep'), __('NP eBay Store','maep'),  'manage_options', 'views/load_product.php', 'maep_load_products',  'dashicons-products');
        $page[] = add_submenu_page('views/load_product.php', __('Load Products', 'maep'),  __('Load Products', 'maep') , 'manage_options','views/load_product.php' , 'maep_load_products');
        $page[] = add_submenu_page('views/load_product.php', __('Settings', 'maep'),  __('Settings', 'maep') , 'manage_options', 'views/maep_settings.php', 'maep_settings');
        $page[] = add_submenu_page('views/load_product.php', __('Create Banners', 'maep'),  __('Create Banners', 'maep') , 'manage_options','views/maep_banners.php', 'maep_banners');

       foreach($page as $pg)
       {
           add_action('admin_print_styles-' . $pg, 'style_only_plugin');
       }
   }

   add_action('admin_menu', 'AddMaepMenu');
 ?>