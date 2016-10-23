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
        $align = isset( $instance['align'] ) ? $instance['align'] : 'text-left';

        echo ( isset( $instance['title'] ) && ! empty( $instance['title'] ) ? $args['before_title'] . $instance['title'] . $args['after_title'] : '' );

        echo LayoutCompilator::user_element( array( 'align' => $align ) );
    }

    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     */
    public function form( $instance )
    {
        /* widgetform in backend */
        $instance = wp_parse_args( (array)$instance, array( 'title' => '',  'align' => 'text-left' ) );
        $title = sanitize_text_field( $instance['title'] );
        $align = sanitize_text_field( $instance['align'] );
        ?>
        <p>
            <label for="<?php echo vdf_var_sanitize( $this->get_field_id( 'title' ) ); ?>">
                <?php echo esc_html__( 'Title', 'empire' ); ?>:
                <input class="widefat" id="<?php echo vdf_var_sanitize( $this->get_field_id( 'title' ) ); ?>" name="<?php echo vdf_var_sanitize( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>"/>
            </label>
        </p>
        <p>
            <label for="<?php echo vdf_var_sanitize( $this->get_field_id( 'id' ) ); ?>">
                <?php esc_html_e( 'Align', 'videofly' ); ?>
                <select id="<?php echo vdf_var_sanitize( $this->get_field_id( 'align' ) ); ?>" name="<?php echo vdf_var_sanitize( $this->get_field_name( 'align' ) ); ?>">
                    <option<?php selected( $align, 'text-left' ); ?> value="text-left"><?php esc_html_e( 'Left', 'empire' ); ?></option>
                    <option<?php selected( $align, 'text-center' ); ?> value="text-center"><?php esc_html_e( 'Center', 'empire' ); ?></option>
                    <option<?php selected( $align, 'text-right' ); ?> value="text-right"><?php esc_html_e( 'Right', 'empire' ); ?></option>
                </select>
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
        $old_instance['align'] = sanitize_text_field( $new_instance['align'] );

        return $old_instance;
    }
}


function register_user_widget()
{
    register_widget( 'User_Widget' );
}

add_action( 'widgets_init', 'register_user_widget' );
?>