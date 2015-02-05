<?php
include_once('views/html.php');
include_once('views/form.php');
class Banners_ebay extends WP_Widget {

    public function __construct() {

    parent::__construct(
    'ebay_banner', // идентификатор виджета
    'Banner', // название виджета
    array( 'description' => 'Banner eBay' ) // Опции
    );
    }
    public function widget( $args, $instance )
    {
    echo viewHTML($instance);
    }
    function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    //Strip tags from title and name to remove HTML
    $instance['title'] = strip_tags( $new_instance['title'] );

    return $instance;
    }
    public function form( $instance )
    {
        $defaults = array(
        'title' => __('', 'Banner')
        );

        $instance = wp_parse_args( (array) $instance, $defaults );
        $id_fieds = array(
            'width' => $this->get_field_id( 'width' ),
            'height' => $this->get_field_id( 'height' ),
            'id_cat' => $this->get_field_id( 'id_cat' ),
            'keyword' => $this->get_field_id( 'keyword' ),
            'autoscroll' => $this->get_field_id( 'autoscroll' ),
            'sort' => $this->get_field_id( 'sort' ),
            'top_seller' => $this->get_field_id( 'top_seller' )
        );

        echo viewForm($id_fieds, $instance);

    }
}

// добавление виджета
add_action( 'widgets_init', 'register_widget_banner' );

function register_widget_banner() {
    register_widget( 'Banners_ebay' );
}



