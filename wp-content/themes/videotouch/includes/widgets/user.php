<?php

class User_Widget extends WP_Widget {

    /**
     * Sets up the widgets name etc
     */
    public function __construct() {

        $widget_ops = array(
            'class_name' => 'user_widget',
            'description' => esc_html__( 'Login form' , 'videofly' ),
        );

        parent::__construct( 'user_touchsize_widget', esc_html__( 'Login form' , 'videofly' ), $widget_ops );
    }

    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance )
    {
        echo $args['before_widget'];
        tsIncludeScripts(array('bootstrap'));
        echo ( isset( $instance['title'] ) && ! empty( $instance['title'] ) ? $args['before_title'] . $instance['title'] . $args['after_title'] : '' );

        echo LayoutCompilator::user_element( array( 'widget' => true ) );

        echo $args['after_widget'];
    }

    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     */
    public function form( $instance )
    {
        /* widgetform in backend */
        $instance = wp_parse_args( (array)$instance, array( 'title' => '' ) );
        $title = sanitize_text_field( $instance['title'] );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">
                <?php echo esc_html__( 'Title', 'empire' ); ?>:
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>"/>
            </label>
        </p>
        <?php
    }

    /**
     * Processing widget options on save
     *
     * @param array $new_instance The new options
     * @param array $old_instance The previous options
     */
    function update( $new_instance, $old_instance )
    {
        $old_instance['title']  = sanitize_text_field( $new_instance['title'] );

        return $old_instance;
    }
}


function register_user_widget()
{
    register_widget( 'User_Widget' );
}

add_action( 'widgets_init', 'register_user_widget' );
?>