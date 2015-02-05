<?php
include_once('views/html.php');
include_once('views/form.php');

class Banners_ebay extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'Gen_Cart', // идентификатор виджета
            'eBay banner', // название виджета
            array( 'description' => 'Banner from eBay' ) // Опции
        );
    }
    public function widget( $args, $instance )
    {
        $instance['topseller'] = ($instance['topseller'] == 'true') ? 'y' : 'n';
        $instance['autoscroll'] = ($instance['autoscroll'] == 'true') ? 'y' : 'n';

        if ($instance['topseller'] == 'true')
            $instance['topseller'] = 'y';
        Banners($instance['id_camp'],$instance['id_cat'], $instance['keyword'], $instance['width'], $instance['height'], $instance['autoscroll'], $instance['sort'], $instance['topseller']);
    }
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        //Strip tags from title and name to remove HTML
        $instance['id_cat'] = strip_tags( $new_instance['id_cat'] );
        $instance['id_camp'] = strip_tags( $new_instance['id_camp'] );
        $instance['width'] = strip_tags( $new_instance['width'] );
        $instance['height'] = strip_tags( $new_instance['height'] );
        $instance['keyword'] = strip_tags( $new_instance['keyword'] );
        $instance['sort'] = strip_tags( $new_instance['sort'] );
        $instance['autoscroll'] = strip_tags( $new_instance['autoscroll'] );
        $instance['topseller'] = strip_tags( $new_instance['topseller'] );
        return $instance;
    }
    public function form( $instance )
    {
        $defaults = array(
            'id_camp' => __('5337645164', 'banner'),
            'id_cat' => __('-1 ', 'banner'),
            'width' => __('150', 'banner'),
            'height' => __('150', 'banner'),
            'keyword' => __('', 'banner'),
            'sort' => __('1', 'banner'),
            'autoscroll' => __('false', 'banner'),
            'topseller' => __('false', 'banner'), );


        $instance = wp_parse_args( (array) $instance, $defaults ); ?>
                <style>
                    .width {width: 45%; float: left;}
                    .height {width: 45%; float: right;}
                    .keyword {clear: both; margin-top: 10px}
                    label {padding-bottom: 15px}
                    .select { padding: 5px 0}
                </style>
                <p>
                    <label for="<?php echo $this->get_field_id( 'id_camp' ) ?>"><?php _e('Compaign ID:', 'banner'); ?></label>
                    <input type="text" id="<?php echo $this->get_field_id( 'id_camp' ) ?>" name="<?php echo $this->get_field_name( 'id_camp' ) ?>" value="<?php echo $instance['id_camp']; ?>" style="width:100%;" />
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'id_cat' ) ?>"><?php _e('Category ID:', 'banner'); ?></label>
                    <input type="number" id="<?php echo $this->get_field_id( 'id_cat' ) ?>" name="<?php echo $this->get_field_name( 'id_cat' ) ?>" value="<?php echo $instance['id_cat']; ?>" style="width:100%;" />
                    <a href="http://www.isoldwhat.com/getcats/fullcategorytree.php" target="_blank">View categories id</a>
                </p>
                <div class="width" >
                    <label for="<?php echo $this->get_field_id( 'width' ) ?>"><?php _e('Width(px):', 'banner'); ?></label>
                    <input id="<?php echo $this->get_field_id( 'width' ) ?>" type="number" name="<?php echo $this->get_field_name( 'width' ) ?>" value="<?php echo $instance['width']; ?>" style="width:100%;" />
                </div>
                <div class="height" >
                    <label for="<?php echo $this->get_field_id( 'height' ) ?>"><?php _e('Height(px):', 'banner'); ?></label>
                    <input id="<?php echo $this->get_field_id( 'height' ) ?>" type="number" name="<?php echo $this->get_field_name( 'height' ) ?>" value="<?php echo $instance['height']; ?>" style="width:100%;" />
                </div>
                <div style="clear: both" ></div>
                <div class="keyword">
                    <label for="<?php echo $this->get_field_id( 'keyword' ) ?>"><?php _e('Keyword:', 'banner'); ?></label>
                    <input id="<?php echo $this->get_field_id( 'keyword' ) ?>" type="text" name="<?php echo $this->get_field_name( 'keyword' ) ?>" value="<?php echo $instance['keyword']; ?>" style="width:100%;" />
                </div>
                <div class="select">
                    <label for="<?php echo $this->get_field_id( 'sort' ) ?>"><?php _e('Sort by:', 'banner'); ?></label>
                    <select name="<?php echo $this->get_field_name( 'sort' ) ?>" id="<?php echo $this->get_field_id( 'sort' ) ?>" style="width: 100%; display: block">
                        <option value="1" <?php selected($instance['sort'], '1'); ?> >Best Match</option>
                        <option value="2" <?php selected($instance['sort'], '2'); ?>>Items Ending First</option>
                        <option value="3" <?php selected($instance['sort'], '3'); ?>>Newly-Listed Items First</option>
                        <option value="4" <?php selected($instance['sort'], '4'); ?>>Lowest Prices First</option>
                        <option value="5" <?php selected($instance['sort'], '5'); ?>>Highest Prices First</option>
                    </select>
                </div>
                <div class="select">
                    <label for="<?php echo $this->get_field_id( 'autoscroll' ) ?>"><?php _e('Auto Scroll:', 'banner'); ?>
                        <input type="hidden" name="<?php echo $this->get_field_name( 'autoscroll' ) ?>" value="false"/>
                        <input id="<?php echo $this->get_field_id( 'autoscroll' ) ?>" type="checkbox" <?php checked($instance['autoscroll'], 'true'); ?> name="<?php echo $this->get_field_name( 'autoscroll' ) ?>" value="true"  />
                    </label>
                </div>
                <div class="select">
                    <label for="<?php echo $this->get_field_id( 'topseller' ) ?>"><?php _e('Only Include Items from Top Rated Sellers  :', 'banner'); ?>
                        <input type="hidden" name="<?php echo $this->get_field_name( 'topseller' ) ?>" value="false"/>
                        <input id="<?php echo $this->get_field_id( 'topseller' ) ?>" type="checkbox" name="<?php echo $this->get_field_name( 'topseller' ) ?>" <?php checked($instance['topseller'], 'true'); ?> value="true"  />
                    </label>
                </div>
    <? }
}


// добавление виджета
add_action( 'widgets_init', 'register_widget_banner' );

function register_widget_banner() {
    register_widget( 'Banners_ebay' );
}



