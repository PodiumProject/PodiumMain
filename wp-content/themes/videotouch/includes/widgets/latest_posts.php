<?php
class widget_latest_posts extends WP_Widget {

	function widget_latest_posts() {
	/*Constructor*/
		$widget_ops = array('classname' => 'widget_latest_posts ', 'description' => __( 'Latest Posts' , 'touchsize' ) );
		parent::__construct('widget_touchsize_latestPosts', __( 'Latest Posts' , 'touchsize' ), $widget_ops);
	}
	
	function widget($args, $instance) {
        /* prints the widget*/
		extract($args, EXTR_SKIP);
        
		echo $before_widget;

		$title = empty($instance['title']) ? __('Latest Posts','touchsize') : apply_filters('widget_title', $instance['title']);
		$number = empty($instance['number']) ? 3 : apply_filters('widget_number', $instance['number']);

        if( strlen( $title) > 0 ){
            echo $before_title . $title . $after_title;
        }
?>
		
        <?php

            $recent = get_posts(array('orderby' => 'created', 'numberposts' =>$number ));  /*NOTE use settings*/
            if( is_array( $recent ) && !empty( $recent ) ){
                ?><ul class="widget-items"><?php
                foreach( $recent as $post )  {
					if( get_post_thumbnail_id( $post -> ID ) ){
								$post_img = wp_get_attachment_image( get_post_thumbnail_id( $post -> ID ) , 'grid' , '' );
								$cnt_a1 = ' href="' . get_permalink($post -> ID) . '"';
								$cnt_a2 = ' href="' . get_permalink($post -> ID) . '#comments"';
								$cnt_a3 = ' class="entry-img" href="' . get_permalink($post -> ID) . '"';
								
							}else{
								$post_img = '<img src="' . get_template_directory_uri() . '/images/no.image.50x50.png" />';
								$cnt_a1 = ' href="' . get_permalink($post -> ID) . '"';
								$cnt_a2 = ' href="' . get_permalink($post -> ID) . '#comments"';
								$cnt_a3 = ' class="entry-img" href="' . get_permalink($post -> ID) . '"';
							}
					?>
                    <li>
                        
						<article class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <a <?php echo $cnt_a3; ?>><?php echo $post_img; ?></a>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h4>
                                	<a <?php echo $cnt_a1; ?>>
									<?php
										echo $post->post_title;
									?>
									</a>
								</h4>
								<div class="widget-meta">
										<ul>
											<li class="red-comments">
		                                        <?php
		                                            if ( $post -> comment_status == 'open' ) {
		                                        ?>
		                                                <a <?php echo $cnt_a2; ?>>
		                                                	<i class="icon-comments"></i>
		                                                	<span class="comments-count">
		                                                    <?php
	                                                            echo $post->comment_count . ' ';
		                                                    ?>
		                                                	</span>
		                                                </a>
		                                        <?php
		                                            }
		                                        ?>
											</li>
										</ul>
									</div>
                            </div>
						</article>
                    </li>
        <?php

                }
                ?></ul><?php
            }
            
            wp_reset_query();

            echo $after_widget;
	}
	
	function update($new_instance, $old_instance) {

	/*save the widget*/
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = strip_tags($new_instance['number']);
		
		return $instance;
	}
	
	function form($instance) {
	/*widgetform in backend*/

		$instance = wp_parse_args( (array) $instance, array('title' => '',  'number' => '') );
		$title = strip_tags($instance['title']);
		$number = strip_tags($instance['number']);
?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title','touchsize') ?>: 
			    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			</label>
		</p>
		<p>
		    <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts','touchsize') ?>:
		        <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo esc_attr($number); ?>" />
		    </label>
		</p>
<?php 		
		
		$title = strip_tags( $instance['title'] );
		$number = strip_tags( $instance['number'] );
	}	
}
function register_latest_posts_widget() {
    register_widget( 'widget_latest_posts' );
}
add_action( 'widgets_init', 'register_latest_posts_widget' );
?>