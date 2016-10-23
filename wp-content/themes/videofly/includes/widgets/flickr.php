<?php
class widget_flickr extends WP_Widget {
    function widget_flickr() {

        /* Constructor */
        $widget_ops = array('classname' => 'widget_flickr_photos', 'description' => esc_html__( 'Flickr Photos' , 'videofly' ) );
        parent::__construct('widget_touchsize_flickrwidget', esc_html__( 'Flickr Photos' , 'videofly' ), $widget_ops);
    }

    function widget($args, $instance) {

        /* prints the widget */
        extract($args, EXTR_SKIP);
        
        $id = empty($instance['id']) ? '&nbsp;' : apply_filters('widget_id', $instance['id']);
        $title = empty($instance['title']) ? esc_html__('Photo Gallery','videofly') : apply_filters('widget_title', $instance['title']);
        $number = empty($instance['number']) ? 9 : apply_filters('widget_number', $instance['number']);     
        $showing = empty($instance['showing']) ? '&nbsp;' : apply_filters('widget_showing', $instance['showing']);

        echo vdf_var_sanitize($before_widget);
        if( strlen( $title ) > 0 ){
            echo vdf_var_sanitize($before_title . $title . $after_title);
        }
?>
        <div class="flickr clearfix">
            <script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=<?php echo vdf_var_sanitize($number); ?>&amp;display=<?php echo vdf_var_sanitize($showing); ?>&amp;size=s&amp;layout=x&amp;source=user&amp;user=<?php echo vdf_var_sanitize($id); ?>"></script>
        <div class="clear"></div>
        </div>
<?php
        echo vdf_var_sanitize($after_widget);
    }

    function update($new_instance, $old_instance) {

        /* save the widget */
        $instance = $old_instance;
        $instance['id'] = strip_tags($new_instance['id']);
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = strip_tags($new_instance['number']);
        $instance['showing'] = strip_tags($new_instance['showing']);

        return $instance;
    }

    function form($instance) {
        
        /* widgetform in backend */
        $instance = wp_parse_args( (array) $instance, array('title' => '',  'id' => '', 'number' => '' , 'showing' => '') );
        $id = strip_tags($instance['id']);
        $title = strip_tags($instance['title']);
        $number = strip_tags($instance['number']);
        $showing = strip_tags($instance['showing']);
?>
        <p>
            <label for="<?php echo vdf_var_sanitize($this->get_field_id('title')); ?>"><?php esc_html_e('Title','videofly') ?>:
                <input class="widefat" id="<?php echo vdf_var_sanitize($this->get_field_id('title')); ?>" name="<?php echo vdf_var_sanitize($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </label>
        </p>
        <p>
            <label for="<?php echo vdf_var_sanitize($this->get_field_id('id')); ?>">Flickr ID (<a target='_blank' href="http://www.idgettr.com">idGettr</a>):
                <input class="widefat" id="<?php echo vdf_var_sanitize($this->get_field_id('id')); ?>" name="<?php echo vdf_var_sanitize($this->get_field_name('id')); ?>" type="text" value="<?php echo esc_attr($id); ?>" />
            </label>
        </p>

        <p>
            <label for="<?php echo vdf_var_sanitize($this->get_field_id('number')); ?>"><?php esc_html_e('Number of photos','videofly') ?>:
                <input class="widefat" id="<?php echo vdf_var_sanitize($this->get_field_id('number')); ?>" name="<?php echo vdf_var_sanitize($this->get_field_name('number')); ?>" type="text" value="<?php echo esc_attr($number); ?>" />
            </label>
        </p>

        <p>
            <label for="<?php echo vdf_var_sanitize($this->get_field_id('showing')); ?>"><?php esc_html_e('Showing Method','videofly') ?>:
                <select size="1" name="<?php echo vdf_var_sanitize($this->get_field_name('showing')); ?>">
                    <option value="random"<?php if(esc_attr($showing) =='random'){echo 'selected';}?>><?php esc_html_e('Random Photo','videofly'); ?></option>
                    <option value="latest"<?php if(esc_attr($showing) =='latest'){echo 'selected';}?>><?php esc_html_e('Latest Photo','videofly') ?></option>
                </select>
            </label>
        </p>
<?php
    }
}
function register_flickr_widget() {
    register_widget( 'widget_flickr' );
}
add_action( 'widgets_init', 'register_flickr_widget' );
?>