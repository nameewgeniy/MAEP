<?php 
   function maep_script(){
      //wp_enqueue_style( 'maep_css_boot', plugins_url('maep/views/css/bootstrap.css'));
      wp_enqueue_style( 'select2.css', plugins_url('maep/views/css/select2.css'));
      wp_enqueue_style( 'maep_css', plugins_url('maep/views/css/style.css'));
      wp_enqueue_script( 'maep_js_boot',plugins_url('maep/views/js/bootstrap.min.js'),array('jquery'));
      wp_enqueue_script( 'maep_js',plugins_url('maep/views/js/load.js'),array('jquery'));
      wp_enqueue_script( 'select2.min.js',plugins_url('maep/views/js/select2.min.js'),array('jquery','maep_js'));
      
   }
   add_action( 'admin_enqueue_scripts', 'maep_script' );

    function style_only_plugin()
    {
        wp_enqueue_style( 'maep_css_boot', plugins_url('maep/views/css/bootstrap.css'));
    }

 ?>