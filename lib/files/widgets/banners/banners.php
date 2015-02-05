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
        extract( $args );
        echo viewHTML($instance);
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        //Strip tags from title and name to remove HTML
        $instance['width'] = strip_tags( $new_instance['width'] );
        $instance['height'] = strip_tags( $new_instance['height'] );
        $instance['id_cat'] = strip_tags( $new_instance['id_cat'] );
        $instance['keyword'] = strip_tags( $new_instance['keyword'] );
        $instance['autoscroll'] = strip_tags( $new_instance['autoscroll'] );
        $instance['sort'] = strip_tags( $new_instance['sort'] );
        $instance['top_seller'] = strip_tags( $new_instance['top_seller'] );
        var_dump($instance);
        exit;

        return $instance;
    }
    public function form( $instance )
    {
        $instance = array();
        $defaults = array(
            'width' => __('150', 'banner'),
            'height' => __('150', 'banner'),
            'id_cat' => __('-1', 'banner'),
            'keyword' => __('', 'banner'),
            'autoscroll' => __('false', 'banner'),
            'sort' => __('1', 'banner'),
            'top_seller' => __('false', 'banner'),
        );

        $instance = wp_parse_args($instance, $defaults );

        $id_fieds = array(
            'width' => $this->get_field_id( 'width' ),
            'height' => $this->get_field_id( 'height' ),
            'id_cat' => $this->get_field_id( 'id_cat' ),
            'keyword' => $this->get_field_id( 'keyword' ),
            'autoscroll' => $this->get_field_id( 'autoscroll' ),
            'sort' => $this->get_field_id( 'sort' ),
            'top_seller' => $this->get_field_id( 'top_seller' )
        );
        $field_name = array(
            'width' => $this->get_field_name( 'width' ),
            'height' => $this->get_field_name( 'height' ),
            'id_cat' => $this->get_field_name( 'id_cat' ),
            'keyword' => $this->get_field_name( 'keyword' ),
            'autoscroll' => $this->get_field_name( 'autoscroll' ),
            'sort' => $this->get_field_name( 'sort' ),
            'top_seller' => $this->get_field_name( 'top_seller' )
        );

        echo viewForm($id_fieds,$field_name, $instance);

    }
}

// добавление виджета
add_action( 'widgets_init', 'register_widget_banner' );

function register_widget_banner() {
    register_widget( 'Banners_ebay' );
}



