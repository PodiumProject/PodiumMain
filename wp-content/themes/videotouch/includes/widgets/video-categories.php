<?php
class widget_video_categories extends WP_Widget {
    function widget_video_categories() {

        /* Constructor */
        $widget_ops = array('classname' => 'widget_video_categories', 'description' => __( 'Video categories' , 'touchsize' ) );
        parent::__construct('widget_touchsize_video_categories', __( 'Video categories' , 'touchsize' ), $widget_ops);
    }

    function widget($args, $instance) {

        /* prints the widget */
        extract($args, EXTR_SKIP);
        
        $title = isset($instance['title']) ? $instance['title'] : '';
        $show_count = isset($instance['show_count']) ? $instance['show_count'] : '';
        $orderby = isset($instance['order']) ? $instance['order'] : 'ASC';
        $number = isset($instance['number']) ? $instance['number'] : '';

        $args = array(
          'taxonomy'     => 'videos_categories',
          'orderby'      => 'name',
          'order'        => $orderby,
          'hide_empty'   => 0,
          'number'       => $number,
          'show_count'   => $show_count
        );

        echo $before_widget;
        if( strlen( $title ) > 0 ){
            echo $before_title . $title . $after_title;
        }
?>
        <div class="ts-widget-video-categories">
            <?php echo wp_list_categories($args); ?>
        </div>
<?php
        echo $after_widget;
    }

    function update($new_instance, $old_instance) {

        /* save the widget */
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['show_count'] = strip_tags($new_instance['show_count']);
        $instance['order'] = strip_tags($new_instance['order']);
        $instance['number'] = strip_tags($new_instance['number']);

        return $instance;
    }

    function form($instance) {
        
        /* widgetform in backend */
        $instance = wp_parse_args( (array) $instance, array('title' => '',  'order' => '', 'number' => '', 'show_count' => '') );
        $title = strip_tags($instance['title']);
        $show_count = strip_tags($instance['show_count']);
        $order = strip_tags($instance['order']);
        $number = strip_tags($instance['number']);
?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title','touchsize') ?>:
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number','touchsize') ?>:
                <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo esc_attr($number); ?>" />
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('show_count'); ?>"><?php _e('Show current count of posts in each category','touchsize') ?>:
                <select size="1" name="<?php echo $this->get_field_name('show_count'); ?>">
                    <option<?php selected($show_count, '1'); ?> value="1"><?php _e('Yes','touchsize')?></option>
                    <option<?php selected($show_count, '0'); ?> value="0"><?php _e('No','touchsize') ?></option>
                </select>
            </label>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order','touchsize') ?>:
                <select size="1" name="<?php echo $this->get_field_name('order'); ?>">
                    <option<?php selected($order, 'ASC'); ?> value="ASC"><?php _e('ASC','touchsize')?></option>
                    <option<?php selected($order, 'DESC'); ?> value="DESC"><?php _e('DESC','touchsize') ?></option>
                </select>
            </label>
        </p>
<?php
    }
}
function register_widget_video_categories() {
    register_widget( 'widget_video_categories' );
}
add_action( 'widgets_init', 'register_widget_video_categories' );
?>