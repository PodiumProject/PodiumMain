<?php
    class widget_list_events extends WP_Widget {

        function widget_list_events() {
            $widget_ops = array( 'classname' => 'widget_list_events' , 'description' => esc_html__( " List events" , 'videofly' ) );
            parent::__construct( 'widget_touchsize_list_events' , esc_html__( "List events" , 'videofly' ) , $widget_ops );
        }

        function widget( $args, $instance ) {

            /* prints the widget*/
            extract($args, EXTR_SKIP);

            $by_time 	= (isset($instance['by_time']) && ($instance['by_time'] === 't' || $instance['by_time'] === 'm' || $instance['by_time'] === 'w')) ? $instance['by_time'] : 't';
            $columns 	= (isset($instance['columns']) && ($instance['columns'] === '1' || $instance['columns'] === '2')) ? $instance['columns'] : '1';
            $title 		= (isset($instance['title'])) ? $instance['title'] : ''; 
          	$nr_posts   = (isset($instance['nr_posts']) && is_numeric($instance['nr_posts'])) ? $instance['nr_posts'] : 5;
            $taxonomy   = (isset($instance['taxonomy']) && ($instance['taxonomy'] == 'category' || $instance['taxonomy'] == 'tags')) ? $instance['taxonomy'] : 'category';
            $category   = (isset($instance['category']) && !empty($instance['category']) && $taxonomy == 'category') ? explode(',', $instance['category']) : NULL;
            $tags       = (isset($instance['tags']) && !empty($instance['tags']) && $taxonomy == 'tags') ? explode(',', $instance['tags']) : NULL;
            $image      = (isset($instance['image']) && ($instance['image'] == 'n' || $instance['image'] == 'y' )) ? $instance['image'] : 'y';

            echo vdf_var_sanitize($before_widget);
		?>
			
			
		<?php
            if( !empty($title) ){
				echo vdf_var_sanitize($before_title . $title . $after_title);
			}	
		
            $args = array(
				'post_type'      => 'event',
				'posts_per_page' => $nr_posts,
                'meta_key'       => 'day',
                'orderby'        => 'meta_value_num'
			);

            if( $by_time == 'w' ) $between_time = array(date('U'), date('U') + 86400 * 7);
            if( $by_time == 'm' ) $between_time = array(date('U'), date('U') + 86400 * date('t'));
            if( $by_time == 't' ) $between_time = array();

			$args['meta_query'] = array(array(
                'key'      => 'day',
                'value'    => $between_time,
                'compare'  => 'BETWEEN'
            ));

            if( isset($category) && is_array($category) && !empty($category) ){
                $args['tax_query'] = array(
                    array( 
                        'taxonomy' => 'event_categories',
                        'field'    => 'id',
                        'terms'    => $category
                    )
                );  
            }
          
            if( isset($tags) && !empty($tags) && is_array($tags) ){
                $args['tag__and'] = $tags;
            }

			$args['order']    = 'DESC';
			
			$class_columns = ($columns === '1') ? '' : ($columns === '2') ? 'col-lg-6 col-md-6 col-sm-12' : 'col-lg-12 col-md-12 col-sm-12';

			$events = new WP_Query( $args );

            if( is_array($events->posts) && !empty($events->posts) ){
            	$post_count = $events->post_count; ?>
                <ul class="widget-items <?php echo ' widget-columns-' . $columns ?>"><?php $i = 1;
	                foreach($events->posts as $post){

                        $post_meta = get_post_meta($post->ID, 'event', true);
                        $day = '';
                        $month = '';
                        $day_meta = get_post_meta($post->ID, 'day', true);
                        if( isset($day_meta) && (int)$day_meta !== 0 ){
                            $month = date("M", $day_meta);
                            $day = date("j", $day_meta);
                        }

						if( get_post_thumbnail_id($post->ID) ){
									$post_img = wp_get_attachment_image(get_post_thumbnail_id($post->ID) , 'vdf_grid' , '');
									$cnt_a1 = ' href="' . get_permalink($post->ID) . '"';
									$cnt_a2 = ' href="' . get_permalink($post->ID) . '#comments"';
									$cnt_a3 = ' class="entry-img" href="' . get_permalink($post->ID) . '"';
									
								}else{
									$post_img = '<img src="' . get_template_directory_uri() . '/images/no-image.png" alt="" />';
									$cnt_a1 = ' href="' . get_permalink($post->ID) . '"';
									$cnt_a2 = ' href="' . get_permalink($post->ID) . '#comments"';
									$cnt_a3 = ' class="entry-img" href="' . get_permalink($post->ID) . '"';
								} ?>
	                    <li class="<?php echo vdf_var_sanitize($class_columns); ?>">
							<article <?php if( $columns == '1' ) echo 'class="row"'; ?>>
                                <?php if( $image == 'y' ) : ?>
    								<div class="image-holder">
    	                                <a <?php echo vdf_var_sanitize($cnt_a3); ?> ><?php echo vdf_var_sanitize($post_img); ?></a>
    	                            </div>
                                <?php endif; ?>
                                <div class="widget-content-box">
                                    <div class="widget-meta">
                                       <div class="date-event">
                                            <span class="day"><?php echo vdf_var_sanitize($day, 'esc_attr'); ?></span>                           
                                            <span class="month"><?php echo vdf_var_sanitize($month, 'esc_attr') ?></span>
                                       </div>
                                    </div>
    	                            <div class="widget-content">
    	                                <h4 class="title">
    	                                	<a <?php echo vdf_var_sanitize($cnt_a1); ?>>
    											<?php echo esc_attr($post->post_title); ?>
    										</a>
    									</h4>
                                        <span class="venue"><?php echo vdf_var_sanitize($post_meta['venue'], 'esc_attr'); ?></span>
                                        <span class="the-time"><?php echo esc_attr($post_meta['start-time']); ?> - <?php echo esc_attr($post_meta['end-time']); ?></span>
    	                            </div>
                                </div>
							</article>
	                    </li>
	    	  <?php } ?>
              	</ul><?php
            }
            
            wp_reset_postdata();
            echo vdf_var_sanitize($after_widget);
		}
		
		
        function update( $new_instance, $old_instance) {

            /*save the widget*/
            $instance = $old_instance;
			
            $instance['title']     = strip_tags($new_instance['title']);
			$instance['nr_posts']  = strip_tags($new_instance['nr_posts']);
			$instance['by_time']   = strip_tags($new_instance['by_time']);
            $instance['columns']   = strip_tags($new_instance['columns']);
            $instance['taxonomy']  = strip_tags($new_instance['taxonomy']);
            $instance['tags']      = strip_tags($new_instance['tags']);
            $instance['category']  = strip_tags($new_instance['category']);
			$instance['image']     = strip_tags($new_instance['image']);

			return $instance;
        }

        function form($instance) {

            /* widget form in backend */
            $instance   = wp_parse_args( (array) $instance, array('columns' => '', 'title' => '', 'by_time' => '', 'nr_posts' => '', 'taxonomy' => '', 'category' => '', 'tags' => '', 'image' => ''));
            $title      = strip_tags($instance['title']);
			$nr_posts   = strip_tags($instance['nr_posts']);
			$by_time    = strip_tags($instance['by_time']);
			$columns    = (isset($instance['columns']) && ($instance['columns'] === '1' || $instance['columns'] === '2')) ? $instance['columns'] : '1';
            $taxonomy   = (isset($instance['taxonomy']) && ($instance['taxonomy'] == 'category' || $instance['taxonomy'] == 'tags')) ? $instance['taxonomy'] : 'category';
            $category_ids = (isset($instance['category']) && !empty($instance['category']) && $taxonomy == 'category') ? explode(',', strip_tags($instance['category'])) : '';
            $tags_ids = (isset($instance['tags']) && !empty($instance['tags']) && $taxonomy == 'tags') ? explode(',', strip_tags($instance['tags'])) : '';
            $image = (isset($instance['image']) && ($instance['image'] == 'y' || $instance['image'] == 'n')) ? $instance['image'] : 'y';
	       
			$args = array('exclude_from_search' => false);
    ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title','videofly') ?>:
                    <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
                </label>
            </p>
            <p>
                <label><?php esc_html_e( 'Select event taxonomy:' , 'videofly' ); ?></label>
                <select class="ts-events-widget-taxonomy" name="<?php echo vdf_var_sanitize($this->get_field_name('taxonomy')); ?>">
                	<option value="category" <?php selected($taxonomy, 'category'); ?>><?php esc_html_e( 'Category' , 'videofly' ); ?></option>
                	<option value="tags" <?php selected($taxonomy, 'tags'); ?>><?php esc_html_e( 'Post tag' , 'videofly' ); ?></option>
                </select>
            </p>
            <p class="ts-events-category"> 
            	<label><?php esc_html_e('Select categories' , 'videofly'); ?></label>
            	<select name="ts-events-category" multiple>
            	    <?php
            	    $categories = get_categories(array('hide_empty' => 0, 'taxonomy' => 'event_categories'));

            	    if ( isset($categories) && is_array($categories) && !empty($categories) ): ?>
            	        <?php foreach ($categories as $index => $category): ?>
            	            <?php if( is_object($category) ) : ?>
            	                <option <?php if( is_array($category_ids) && $taxonomy == 'category' && in_array($category->term_id, $category_ids) ) echo 'selected="selected"' ?> value="<?php echo vdf_var_sanitize($category->term_id); ?>"><?php echo vdf_var_sanitize($category->cat_name); ?></option>
            	            <?php endif; ?>
            	        <?php endforeach ?>                                    
            	    <?php endif ?>
            	</select>
                <input value="" name="<?php echo vdf_var_sanitize($this->get_field_name('category')); ?>" type="hidden" class="ts-category-story">
            </p>
            <p class="ts-events-tags"> 
            	<label><?php esc_html_e('Select tags' , 'videofly'); ?></label>
            	<?php $tags = get_tags(); 
            	if( !empty($tags) && is_array($tags) ) : ?>
	            	<select name="ts-events-tags" multiple>
	            	   	<?php foreach($tags as $tag) : ?>
	            	   		<option <?php if( is_array($tags_ids) && $taxonomy == 'tags' && in_array($tag->term_id, $tags_ids) ) echo 'selected="selected"' ?> value="<?php echo vdf_var_sanitize($tag->term_id); ?>"><?php echo vdf_var_sanitize($tag->name); ?></option>
	            	   	<?php endforeach; ?>
	            	</select>
                    <input value="" name="<?php echo vdf_var_sanitize($this->get_field_name('tags')); ?>" type="hidden" class="ts-tags-story">
	            <?php else : ?>
	            	<?php echo esc_html__('No tags', 'videofly'); ?>
	            <?php endif; ?>
            </p>
            <script>
            	jQuery(document).ready(function(){

                    jQuery('[name="ts-events-category"]').each(function(){
                        
                        if( jQuery(this).val() ){
                            jQuery(this).parent().find('.ts-category-story').val(jQuery(this).val().join(','));
                        }

                        jQuery(this).change(function(){
                            jQuery(this).parent().find('.ts-category-story').val(jQuery(this).val().join(','));
                        });
                    }); 

                    jQuery('[name="ts-events-tags"]').each(function(){
                        if( jQuery(this).val() ){
                            jQuery(this).parent().find('.ts-tags-story').val(jQuery(this).val().join(','));
                        }

                        jQuery(this).change(function(){
                            jQuery(this).parent().find('.ts-tags-story').val(jQuery(this).val().join(','));
                        });
                    });

                    jQuery('.ts-events-widget-taxonomy').each(function(){
                		if( jQuery(this).val() == 'category' ){
                			jQuery(this).parent().parent().find('.ts-events-category').css('display', '');
                			jQuery(this).parent().parent().find('.ts-events-tags').css('display', 'none');
                		}else{
                			jQuery(this).parent().parent().find('.ts-events-category').css('display', 'none');
                			jQuery(this).parent().parent().find('.ts-events-tags').css('display', '');
                		}
                    });

            		jQuery('.ts-events-widget-taxonomy').change(function(){
            			if( jQuery(this).val() == 'category' ){
                            jQuery(this).parent().parent().find('.ts-events-category').css('display', '');
            				jQuery(this).parent().parent().find('.ts-events-tags').css('display', 'none');
            			}else{
                            jQuery(this).parent().parent().find('.ts-events-category').css('display', 'none');
                            jQuery(this).parent().parent().find('.ts-events-tags').css('display', ''); 
                        }
            		});
            	});
            </script>
			<p>
                <label for="<?php echo vdf_var_sanitize($this->get_field_id('nr_posts')); ?>"><?php esc_html_e( 'Number of posts' , 'videofly' ); ?></label>:
                <input class="widefat digit" id="<?php echo vdf_var_sanitize($this->get_field_id('nr_posts')); ?>" name="<?php echo vdf_var_sanitize($this->get_field_name('nr_posts')); ?>" type="text" value="<?php echo esc_attr( $nr_posts ); ?>" />
            </p>
            <p>
                <label for="<?php echo vdf_var_sanitize($this->get_field_name('by_time')); ?>"><?php esc_html_e( 'Period' , 'videofly' ); ?></label>:
                <select name="<?php echo vdf_var_sanitize($this->get_field_name('by_time')); ?>">
                	<option value="t" <?php selected($by_time, 't'); ?>><?php esc_html_e( 'All time' , 'videofly' ); ?></option>
                	<option value="m" <?php selected($by_time, 'm'); ?>><?php esc_html_e( 'This month' , 'videofly' ); ?></option>
                	<option value="w" <?php selected($by_time, 'w'); ?>><?php esc_html_e( 'This week' , 'videofly' ); ?></option>
                </select>
            </p>
            <p>
                <label for="<?php echo vdf_var_sanitize($this->get_field_name('columns')); ?>"><?php esc_html_e( 'Columns:' , 'videofly' ); ?></label>
                <select name="<?php echo vdf_var_sanitize($this->get_field_name('columns')); ?>">
                    <option value="1" <?php selected($columns, '1'); ?>><?php esc_html_e( '1 column' , 'videofly' ); ?></option>
                    <option value="2" <?php selected($columns, '2'); ?>><?php esc_html_e( '2 columns' , 'videofly' ); ?></option>
                </select>
            </p>
            <p>
                <label for="<?php echo vdf_var_sanitize($this->get_field_name('image')); ?>"><?php esc_html_e('With image', 'videofly' ); ?></label>
                <select name="<?php echo vdf_var_sanitize($this->get_field_name('image')); ?>">
                	<option value="y" <?php selected($image, 'y'); ?>><?php esc_html_e( 'Yes' , 'videofly' ); ?></option>
                	<option value="n" <?php selected($image, 'n'); ?>><?php esc_html_e( 'No' , 'videofly' ); ?></option>
                </select>
            </p>
    <?php
        }
		
    }
	function register_list_events() {
	    register_widget( 'widget_list_events' );
	}
	add_action('widgets_init', 'register_list_events');

?>