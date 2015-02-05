<?php
include_once('views/html.php');
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

    $instance = wp_parse_args( (array) $instance, $defaults ); ?>
    <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Заголовок корзины:', 'cart'); ?></label>
        <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
    </p>

    <? }
}

// добавление виджета
add_action( 'widgets_init', 'register_widget_banner' );

function register_widget_banner() {
    register_widget( 'Banners_ebay' );
}



