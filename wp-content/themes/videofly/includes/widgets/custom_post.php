<?php
    class widget_custom_post extends WP_Widget{

        function widget_custom_post() {
            $widget_ops = array( 'classname' => 'widget_custom_post' , 'description' => esc_html__( " Latest custom posts" , 'videofly' ) );
            parent::__construct( 'widget_touchsize_custom_post' , esc_html__( " Latest custom posts" , 'videofly' ) , $widget_ops );
        }

        function widget( $args , $instance ) {

            /* prints the widget*/
            extract($args, EXTR_SKIP);
            $title      = (isset($instance['title'])) ? $instance['title'] : '';
            $nr_posts   = (isset($instance['nr_posts']) && is_numeric($instance['nr_posts'])) ? absint($instance['nr_posts']) : '';
			$custompost = isset($instance['customPost']) ? $instance['customPost'] : '';			
			$tags       = (isset($instance['tags']) && is_array($instance['tags'])) ? $instance['tags'] : array();
			$terms      = (isset($instance['terms']) && is_array($instance['terms'])) ? $instance['terms'] : array();
			$taxonomy   = (isset($instance['taxonomy']) && ($instance['taxonomy'] == 'post_tag' || $instance['taxonomy'] == 'category')) ? $instance['taxonomy'] : '__';
			
            echo vdf_var_sanitize($before_widget);

            if( !empty( $title ) ){
				echo vdf_var_sanitize($before_title . $title . $after_title);
			}	
			
            $args = array(
				'post_type' => $custompost,
				'posts_per_page' => $nr_posts
			);

            if( $taxonomy == 'category' && !empty($terms) ){
            	$exclude_taxonomies = array('post_tag', 'post_format');
            	$obj_taxonomies = get_object_taxonomies($custompost);
            	$args['tax_query'] = array();
	            foreach($obj_taxonomies as $name_taxonomy){
	            	if( !in_array($name_taxonomy, $exclude_taxonomies) ){
	            		array_push($args['tax_query'], array(
								'taxonomy' => $name_taxonomy,
								'field'    => 'slug',
								'terms'    => $terms
							)
						);
	            	}
	            }
			}

			if( $taxonomy == 'post_tag' && !empty($tags) ){
				$args['tag__in'] = $tags;
			}
			
			$recent = new WP_Query( $args );

            
            if( is_array( $recent -> posts ) && !empty( $recent -> posts ) ){
                ?><ul class="widget-items"><?php
                foreach( $recent->posts as $post )  {
					if( get_post_thumbnail_id( $post->ID ) ){
								$post_img = wp_get_attachment_image( get_post_thumbnail_id( $post->ID ) , 'grid' , '' );
								$cnt_a1 = ' href="' . get_permalink($post->ID) . '"';
								$cnt_a2 = ' href="' . get_permalink($post->ID) . '#comments"';
								$cnt_a3 = ' class="entry-img" href="' . get_permalink($post -> ID) . '"';
								
							}else{
								$post_img = '<img src="' . get_template_directory_uri() . '/images/no-image.png" alt="" />';
								$cnt_a1 = ' href="' . get_permalink($post->ID) . '"';
								$cnt_a2 = ' href="' . get_permalink($post->ID) . '#comments"';
								$cnt_a3 = ' class="entry-img" href="' . get_permalink($post->ID) . '"';
							}

					?>
                    <li>
                        
						<article class="row">
							<div class="col-lg-4 col-sm-4">
                                <a <?php echo vdf_var_sanitize($cnt_a3); ?>><?php echo vdf_var_sanitize($post_img); ?></a>
                            </div>
                            <div class="col-lg-8 col-sm-8">
                                <h4 class="">
                                	<a <?php echo vdf_var_sanitize($cnt_a1); ?>>
									<?php
										echo vdf_var_sanitize($post->post_title, 'esc_attr');
									?>
									</a>
								</h4>
								<div class="widget-meta">
										<ul>
											<?php
	                                            if ( $post->comment_status == 'open' ) {
	                                        ?>
												<li class="red-comments">
		                                        
	                                                <a <?php echo vdf_var_sanitize($cnt_a2); ?>>
	                                                	<i class="icon-comments"></i>
	                                                	<span class="comments-count">
	                                                    <?php
	                                                            echo vdf_var_sanitize($post->comment_count) . ' ';
	                                                        
	                                                    ?>
	                                                	</span>
	                                                </a>
		                                        
												</li>
											<?php
	                                            }
	                                        touchsize_likes( $post->ID, '<li>', '</li>', true, false ); ?>
										</ul>
									</div>
                            </div>
						</article>
                    </li>
        <?php

                }
                ?></ul><?php
            }
            
            wp_reset_postdata();
            echo vdf_var_sanitize($after_widget);
		}
		
		
        function update( $new_instance, $old_instance) {

            /*save the widget*/
            $instance = $old_instance;
            
            $instance['title'] = (isset($new_instance['title'])) ? $new_instance['title'] : '';
			$instance['nr_posts'] = (isset($new_instance['nr_posts'])) ? absint($new_instance['nr_posts']) : '';
			
			$instance['customPost'] = isset($new_instance['customPost']) ? $new_instance['customPost'] : '';
			
			$instance['taxonomy'] = (isset($new_instance['taxonomy']) && ($new_instance['taxonomy'] == 'post_tag' || $new_instance['taxonomy'] == 'category')) ? $new_instance['taxonomy'] : '__';

			$instance['terms'] = array();
			foreach($new_instance['terms'] as $terms){
				if($terms !== ''){
					$instance['terms'][] = $terms;
				}else{
					$instance['terms'][] = '';
				}
			}

			$instance['tags'] = array();
			foreach($new_instance['tags'] as $tags){
				if($tags !== ''){
					$instance['tags'][] = $tags;
				}else{
					$instance['tags'][] = '';
				}
			}

			return $instance;
        }

        function form($instance) {

            $instance       = wp_parse_args( (array)$instance, array( 'title' => '' , 'nr_posts' => '',  'customPost' => '','taxonomy' => '', 'taxonomies' => array(), 'terms' => array() ) );
            
            $title          = (isset($instance['title'])) ? esc_attr($instance['title']) : '';
			$nr_posts    	= (isset($instance['nr_posts'])) ? esc_attr($instance['nr_posts']) : '';
			
			$custom_post = (isset($instance['customPost'])) ? $instance['customPost'] : '';
			$taxonomy = (isset($instance['taxonomy']) && ($instance['taxonomy'] == 'post_tag' || $instance['taxonomy'] == 'category' )) ? $instance['taxonomy'] : '__'; 
			
			$tags = (isset($instance['tags']) && is_array($instance['tags'])) ? $instance['tags'] : array();
			$terms__ = (isset($instance['terms']) && is_array($instance['terms'])) ? $instance['terms'] : array();
			
			$exclude_post_types = array('page', 'attachment', 'post', 'event', 'ts_slider');
			$args = array('exclude_from_search' => false);
			$post_types = get_post_types($args);
			$select_options = '';
			$select_terms = '';
			$all_tags = get_tags();
			
    ?>

            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title','videofly') ?>:
                    <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
                </label>
            </p>
            
            <?php foreach($post_types as $post_type) : ?>
            	<?php if( !in_array($post_type, $exclude_post_types) ) : ?>
            		<?php 
            			$object_post_type = get_post_type_object($post_type);

            			$exclude_taxonomies = array('post_tag', 'post_format');
            			$obj_taxonomies = get_object_taxonomies($post_type);

            			$select_options .= 	'<option ' . selected($custom_post, $post_type, false) . 'value="' . $post_type . '">
            									' . $object_post_type->labels->name .
            								'</option>';

            			if( isset($obj_taxonomies) && is_array($obj_taxonomies) && !empty($obj_taxonomies) ){
            				$option_terms = '';
            				foreach($obj_taxonomies as $tax){
            					if( !in_array($tax, $exclude_taxonomies) ){
		            				$terms = get_categories(array('hide_empty' => true, 'taxonomy' => $tax));
		            				if( isset($terms) && is_array($terms) && !empty($terms) ){
		            					foreach($terms as $term){
		            						if( is_object($term) ){
		            							$selected = (in_array($term->slug, $terms__)) ? 'selected="selected"' : '';
		            							$option_terms .= '<option ' . $selected . ' value="' . $term->slug . '">' . esc_attr($term->name) . '</option>';
		            						}
		            					}
		            				}
		            			}
	            			}

	            			if( isset($option_terms) && !empty($option_terms) ){
	            				$select_terms .='<select style="display:;" multiple class="widefat multiple-select ts-terms-' . $post_type . '" name="' . $this->get_field_name('terms') . '[]">
	            									' . $option_terms . '
	            								</select>';
	            			}
            			}
            		?>
				<?php endif; ?>
			<?php endforeach; ?>
			
           	<p>
				<label>
					<?php esc_html_e('Select post type: ', 'videofly'); ?>
			            <select class="ts-custom-posts" name="<?php echo vdf_var_sanitize($this->get_field_name('customPost')); ?>">
			            	<?php 
			            		if(isset($select_options) && !empty($select_options)) echo vdf_var_sanitize($select_options);
			            		else echo '<option>' . esc_html__('No custom post type', 'videofly') . '</option>'
			            	?>
						</select>
				</label>
			</p>
			<p>
            	<label>
            		<?php esc_html_e('Select taxonomy: ', 'videofly'); ?>
		            <select class="ts-by-taxonomy" name="<?php echo vdf_var_sanitize($this->get_field_name('taxonomy')); ?>">
		            	<option <?php selected($taxonomy, '__', true); ?> value="__"><?php esc_html_e('Select taxonomy', 'videofly'); ?></option>
			            <option <?php selected($taxonomy, 'post_tag', true); ?> value="post_tag"><?php esc_html_e('Post tag', 'videofly'); ?></option>
						<option <?php selected($taxonomy, 'category', true); ?> value="category"><?php esc_html_e('Category', 'videofly'); ?></option>
					</select>
				</label>	
            </p>

            <p class="ts-all-tags" style="display: none;">
            	<label>
            		<?php esc_html_e('Select tags: ', 'videofly'); ?>
		            <select name="<?php echo vdf_var_sanitize($this->get_field_name('tags')); ?>[]" multiple>
		            	<?php if( isset($all_tags) && is_array($tags) && !empty($all_tags) ) : ?>
		            		<?php foreach($all_tags as $tag) : ?>
		            			<?php if( is_object($tag) ) : ?>
		            				<option <?php if( in_array($tag->slug, $tags) ) echo 'selected="selected"' ?> value="<?php echo vdf_var_sanitize($tag->slug); ?>"><?php echo vdf_var_sanitize($tag->name ); ?></option>
		            			<?php endif; ?>
		            		<?php endforeach; ?>
		            	<?php else : ?>
		            		<option value=""><?php esc_html_e('No tags', 'videofly'); ?></option>
		            	<?php endif; ?>
					</select>
				</label>	
            </p>

            <p class="ts-all-category" style="display: none;">
            	<label>
            		<?php esc_html_e('Select category: ', 'videofly'); ?>
		            <?php if( isset($select_terms) && !empty($select_terms) ) : ?>
		            	<?php echo balanceTags($select_terms); ?>
		            <?php endif; ?>
				</label>	
            </p>

			<p>
                <label for="<?php echo vdf_var_sanitize($this->get_field_id('nr_posts')); ?>"><?php esc_html_e( 'Number of posts' , 'videofly' ); ?>:
                    <input class="widefat digit" id="<?php echo vdf_var_sanitize($this->get_field_id('nr_posts')); ?>" name="<?php echo vdf_var_sanitize($this->get_field_name('nr_posts')); ?>" type="text" value="<?php echo esc_attr( $nr_posts ); ?>" />
				</label>
            </p>
            <script>
            	jQuery(document).ready(function(){
            		jQuery('.ts-by-taxonomy').change(function(){
            			if( jQuery(this).val() == 'post_tag' ){
            				jQuery(this).closest('div').find('.ts-all-tags').css('display', '');
            				jQuery(this).closest('div').find('.ts-all-category').css('display', 'none');
            			}else if( jQuery(this).val() == 'category' ){
            				jQuery(this).closest('div').find('.ts-all-category').css('display', '');
            				jQuery(this).closest('div').find('.ts-all-tags').css('display', 'none');

            				jQuery(this).closest('div').find('.ts-all-category select').each(function(){
            					jQuery(this).css('display', 'none');
            				});

            				var custom_post = jQuery(this).closest('div').find('.ts-custom-posts').val();
            				jQuery('.ts-terms-' + custom_post).css('display', '');

            			}else{
            				jQuery(this).closest('div').find('.ts-all-category').css('display', 'none');
            				jQuery(this).closest('div').find('.ts-all-tags').css('display', 'none');
            			}
            		});

            		jQuery('.ts-by-taxonomy').each(function(){
            			if( jQuery(this).val() == 'post_tag' ){
            				jQuery(this).closest('div').find('.ts-all-tags').css('display', '');
            				jQuery(this).closest('div').find('.ts-all-category').css('display', 'none');
            			}else if( jQuery(this).val() == 'category' ){
            				jQuery(this).closest('div').find('.ts-all-category').css('display', '');
            				jQuery(this).closest('div').find('.ts-all-tags').css('display', 'none');

            				jQuery(this).closest('div').find('.ts-all-category select').each(function(){
            					jQuery(this).css('display', 'none');
            				});

            				var custom_post = jQuery(this).closest('div').find('.ts-custom-posts').val();
            				jQuery('.ts-terms-' + custom_post).css('display', '');

            			}else{
            				jQuery(this).closest('div').find('.ts-all-category').css('display', 'none');
            				jQuery(this).closest('div').find('.ts-all-tags').css('display', 'none');
            			}
            		});

					jQuery('.ts-custom-posts').change(function(){
						var taxonomy = jQuery(this).closest('div').find('.ts-by-taxonomy').val();
						if( taxonomy == 'category' ){

							jQuery(this).closest('div').find('.ts-all-category select').each(function(){
								jQuery(this).css('display', 'none');
							});

            				jQuery('.ts-terms-' + jQuery(this).val()).css('display', '');
						}
					});

            	});
            </script>
			
    <?php
        }
    }

	function register_custom_posts_widget() {
	    register_widget( 'widget_custom_post' );
	}
	add_action( 'widgets_init', 'register_custom_posts_widget' );

?>