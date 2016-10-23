<?php
    class widget_tags extends WP_Widget {

        function widget_tags() {
            $widget_ops = array( 'classname' => 'widget_tabber' , 'description' => esc_html__( 'Tags' , 'videofly' ) );
            parent::__construct( 'widget_touchsize_tags' ,  esc_html__( 'Tags' , 'videofly' ) , $widget_ops );
        }

        function widget( $args , $instance ) {

            /* prints the widget*/
            extract($args, EXTR_SKIP);

            if( isset( $instance['title'] ) ){
                $title = $instance['title'];
            }else{
                $title = '';
            }

			if( isset( $instance['nr_tags'] ) ){
                $nr_tags = $instance['nr_tags'];
            }else{
                $nr_tags = 0;
            }
            echo vdf_var_sanitize($before_widget);

            if( !empty( $title ) ){
                echo vdf_var_sanitize($before_title . $title . $after_title);
            }

        ?>
            <!-- panel tags -->
            <div class="tab_menu_content tags-container">
                <?php
					if($nr_tags != 0){
						$args = array('number' => $nr_tags, 'orderby' => 'count', 'order' => 'DESC');
						$tags = get_tags($args);
					}else{
						$tags = get_tags();
					}	  
                    if( !empty( $tags ) && is_array( $tags ) ){
                        foreach( $tags as $tag ){
                            $tag_link = get_tag_link( $tag->term_id );
                            ?><a class="tag" href="<?php echo esc_url($tag_link) ?>"> <?php echo vdf_var_sanitize($tag->name, 'esc_attr'); ?></a><?php
                        }
                    }else{
                        echo '<p>' . esc_html__( 'There are no tags.' , 'videofly' ) . '</p>';
                    }
                ?>
            </div>
        <?php
            echo vdf_var_sanitize($after_widget);
        }

        function update( $new_instance, $old_instance) {

            /*save the widget*/
            $instance = $old_instance;
            $instance['title']              = strip_tags( $new_instance['title'] );
			$instance['nr_tags']        	= strip_tags( $new_instance['nr_tags'] );

            return $instance;
        }

        function form($instance) {

            /* widget form in backend */
            $instance       = wp_parse_args( (array) $instance, array( 'title' => '' , 'nr_tags' => '') );
            $title          = strip_tags( $instance['title'] );
			$nr_tags    	= strip_tags( $instance['nr_tags'] );
    ?>

            <p>
                <label for="<?php echo vdf_var_sanitize($this->get_field_id('title')); ?>"><?php esc_html_e('Title','videofly') ?>:
                    <input class="widefat" id="<?php echo vdf_var_sanitize($this->get_field_id('title')); ?>" name="<?php echo vdf_var_sanitize($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
                </label>
            </p>
			<p>
                <label for="<?php echo vdf_var_sanitize($this->get_field_id('nr_tags')); ?>"><?php esc_html_e( 'Number of tags' , 'videofly' ); ?>:
                    <input class="widefat digit" id="<?php echo vdf_var_sanitize($this->get_field_id('nr_tags')); ?>" name="<?php echo vdf_var_sanitize($this->get_field_name('nr_tags')); ?>" type="text" value="<?php echo esc_attr( $nr_tags ); ?>" />
					<span class="hint"><?php esc_html_e('Leave blank to show all tags','videofly' ) ?></span>
                </label>
            </p>
    <?php
        }
    }
    function register_tags_widget() {
        register_widget( 'widget_tags' );
    }
    add_action( 'widgets_init', 'register_tags_widget' );
?>