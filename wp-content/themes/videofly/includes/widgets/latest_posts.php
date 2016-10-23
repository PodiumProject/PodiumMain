<?php
class widget_latest_posts extends WP_Widget {

    function widget_latest_posts() {
        $widget_ops = array( 'classname' => 'widget_latest_posts' , 'description' => esc_html__( " Get latest posts posts." , 'videofly' ) );
        parent::__construct( 'widget_touchsize_latest_posts' , esc_html__( 'Latest posts' , 'videofly' ) , $widget_ops );
    }

    function widget( $args , $instance ){
        /* prints the widget*/
		extract($args, EXTR_SKIP);

    	$title = isset($instance['title']) ? $instance['title'] : '';
        $nr_posts = isset( $instance['nr_posts'] ) && is_numeric($instance['nr_posts']) ? $instance['nr_posts'] : 5;
		$customPost = isset($instance['customPost']) ? $instance['customPost'] : '';
		$taxonomy = isset($instance['taxonomy']) ? $instance['taxonomy'] : '';
		$taxonomies	= isset($instance['taxonomies']) ? $instance['taxonomies'] : array();
		$useTab = isset($instance['tab']) ? $instance['tab'] : 'n';
	
		$args = array('number' => 10, 'orderby' => 'count', 'order' => 'DESC', 'post_type' => $customPost, 'posts_per_page' => $nr_posts, 'post_status' => 'publish');

		$storagePosts = array();
		
		if( $useTab == 'y' ){
			if( !empty($taxonomies) ){

				foreach($taxonomies as $term ){
					$args['tax_query'] = array(
						array(
							'taxonomy' => $taxonomy,
							'field'    => 'slug',
							'terms'    => $term
						)
					);

					$termInfo = get_term_by('slug', $term, $taxonomy);
					$storagePosts[$termInfo->name] = get_posts($args);
				}
			}
		}else{
			if( !empty($taxonomies) ){
				$args['tax_query'] = array(
					array(
						'taxonomy' => $taxonomy,
						'field'    => 'slug',
						'terms'    => $taxonomies
					)
				);
			}
		}

		echo vdf_var_sanitize($before_widget);

        echo (!empty($title) ? vdf_var_sanitize($before_title . $title . $after_title) : '');
        
        if( $useTab == 'y' ): $i = 0;
	        ob_start(); ob_clean();
		        foreach( $storagePosts as $titleTab => $arrayPosts ): ?>
		    		<div class="tab-pane<?php echo ($i == 0 ? ' active' : '') ?>" id="<?php echo 'vdf-'. sanitize_html_class($titleTab) .'-'. $this->id ?>">
			        	<?php $this->getHtmlPosts($arrayPosts, $instance) ?>
			        </div>
			        <?php $i++ ?>
			    <?php endforeach; ?>
			<?php $content = ob_get_clean() ?>
		    <div class="ts-tab-container" data-display="horizontal">
			    <ul class="nav nav-tabs" role="tablist">
			    	<?php $k = 0 ?>
			    	<?php foreach( $storagePosts as $titleTab => $arrayPosts ): ?>
					    <li<?php echo ($k == 0 ? ' class="active"' : '') ?>>
		        			<a href="#vdf-<?php echo sanitize_html_class($titleTab) ?>-<?php echo vdf_var_sanitize($this->id) ?>" role="tab" ><?php echo vdf_var_sanitize($titleTab) ?></a>
		        		</li>
		        		<?php $k++ ?>
			    	<?php endforeach ?>
			    </ul>
			    <div class="tab-content">
			    	<?php echo vdf_var_sanitize($content) ?>
			   	</div>
			</div>
		<?php else: 
			$queryPosts = get_posts($args);
			$this->getHtmlPosts($queryPosts, $instance);
		 endif ?>
        <?php echo vdf_var_sanitize($after_widget);
	}


    function update( $new_instance, $old_instance) {

        /*save the widget*/
        $instance = $old_instance;

        $instance['title'] = isset($new_instance['title']) ? strip_tags($new_instance['title']) : '';
		$instance['nr_posts'] = isset($new_instance['nr_posts']) ? strip_tags($new_instance['nr_posts']): '5';
		$instance['columns'] = isset($new_instance['columns']) ? strip_tags($new_instance['columns']) : 1;
		$instance['image']    = isset($new_instance['image']) && ($new_instance['image'] == 'y' || $new_instance['image'] == 'n') ? $new_instance['image'] : 'y';
		$instance['number']   = isset($new_instance['number']) && ($new_instance['number'] == 'y' || $new_instance['number'] == 'n') ? $new_instance['number'] : 'n';
		$instance['date']   = isset($new_instance['date']) && ($new_instance['date'] == 'y' || $new_instance['date'] == 'n') ? $new_instance['date'] : 'n';
		$instance['likes']   = isset($new_instance['likes']) && ($new_instance['likes'] == 'y' || $new_instance['likes'] == 'n') ? $new_instance['likes'] : 'y';
		$instance['views']   = isset($new_instance['views']) && ($new_instance['views'] == 'y' || $new_instance['views'] == 'n') ? $new_instance['views'] : 'n';
		$instance['comments']   = isset($new_instance['comments']) && ($new_instance['comments'] == 'y' || $new_instance['comments'] == 'n') ? $new_instance['comments'] : 'y';
		$instance['tab']   = $new_instance['tab'] ? $new_instance['tab'] : 'n';

		$instance['customPost'] = isset($new_instance['customPost']) ? $new_instance['customPost']: '';

		$instance['taxonomy'] = isset($new_instance['taxonomy']) ? $new_instance['taxonomy'] : '';

		$instance['taxonomies'] = isset($new_instance['taxonomies']) ? $new_instance['taxonomies'] : '';

		return $instance;
    }

    function form($instance) {

        $title = isset($instance['title']) ? strip_tags($instance['title']) : '';
       	$nr_posts = isset($instance['nr_posts']) ? strip_tags($instance['nr_posts']): '3';
		$customPost = isset($instance['customPost']) ? $instance['customPost'] : '';
		$taxonomy   = isset($instance['taxonomy']) ? $instance['taxonomy'] : '';
		$taxonomies = isset($instance['taxonomies']) ? $instance['taxonomies'] : array();
		$number = isset($instance['number']) && ($instance['number'] == 'y' || $instance['number'] == 'n') ? $instance['number'] : 'n';
		$image = isset($instance['image']) && ($instance['image'] == 'y' || $instance['image'] == 'n') ? $instance['image'] : 'y';
		$date = isset($instance['date']) && ($instance['date'] == 'y' || $instance['date'] == 'n') ? $instance['date'] : 'n';
		$likes = isset($instance['likes']) && ($instance['likes'] == 'y' || $instance['likes'] == 'n') ? $instance['likes'] : 'y';
		$views = isset($instance['views']) && ($instance['views'] == 'y' || $instance['views'] == 'n') ? $instance['views'] : 'n';
		$comments = isset($instance['comments']) ? $instance['comments'] : 'y';
		$columns = isset($instance['columns']) ? $instance['columns'] : '1';
		$tab = isset($instance['tab']) ? $instance['tab'] : 'n';

		$allowedPostTypes = array(
			'post' => array(
				'category' => esc_html__('Category', 'videofly'),
				'post_tag' => esc_html__('Post tag', 'videofly') ,
			),
			'video' => array(
				'videos_categories' => esc_html__('Video category','videofly'),
				'post_tag' => esc_html__('Video tag', 'videofly') ,
			),
			'ts-gallery' => array(
				'gallery_categories' => esc_html__('Gallery category','videofly'),
			 	'post_tag' => esc_html__('Gallery tag', 'videofly') ,
			),
		);
		?>
        <p>
            <label for="<?php echo vdf_var_sanitize($this->get_field_id('title')); ?>"><?php esc_html_e('Title','videofly') ?>:
                <input class="widefat" id="<?php echo vdf_var_sanitize($this->get_field_id('title')); ?>" name="<?php echo vdf_var_sanitize($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </label>
        </p>

        <p>
            <label for="<?php echo vdf_var_sanitize($this->get_field_id('image')); ?>"><?php esc_html_e('Show image','videofly') ?>:
            	<select name="<?php echo vdf_var_sanitize($this->get_field_name('image')); ?>">
            		<option <?php selected($image, 'y', true); ?> value="y"><?php esc_html_e('Yes', 'videofly'); ?></option>
            		<option <?php selected($image, 'n', true); ?> value="n"><?php esc_html_e('No', 'videofly'); ?></option>
            	</select>
            </label>
        </p>

        <p>
            <label for="<?php echo vdf_var_sanitize($this->get_field_id('number')); ?>"><?php esc_html_e('Show numbers','videofly') ?>:
            	<select name="<?php echo vdf_var_sanitize($this->get_field_name('number')); ?>">
            		<option <?php selected($number, 'y', true); ?> value="y"><?php esc_html_e('Yes', 'videofly'); ?></option>
            		<option <?php selected($number, 'n', true); ?> value="n"><?php esc_html_e('No', 'videofly'); ?></option>
            	</select>
            </label>
        </p>

        <p>
            <label for="<?php echo vdf_var_sanitize($this->get_field_id('date')); ?>"><?php esc_html_e('Show date','videofly') ?>:
            	<select name="<?php echo vdf_var_sanitize($this->get_field_name('date')); ?>">
            		<option <?php selected($date, 'y', true); ?> value="y"><?php esc_html_e('Yes', 'videofly'); ?></option>
            		<option <?php selected($date, 'n', true); ?> value="n"><?php esc_html_e('No', 'videofly'); ?></option>
            	</select>
            </label>
        </p>

        <p>
            <label for="<?php echo vdf_var_sanitize($this->get_field_id('likes')); ?>"><?php esc_html_e('Show likes','videofly') ?>:
            	<select name="<?php echo vdf_var_sanitize($this->get_field_name('likes')); ?>">
            		<option <?php selected($likes, 'y', true); ?> value="y"><?php esc_html_e('Yes', 'videofly'); ?></option>
            		<option <?php selected($likes, 'n', true); ?> value="n"><?php esc_html_e('No', 'videofly'); ?></option>
            	</select>
            </label>
        </p>

        <p>
            <label for="<?php echo vdf_var_sanitize($this->get_field_id('views')); ?>"><?php esc_html_e('Show views','videofly') ?>:
            	<select name="<?php echo vdf_var_sanitize($this->get_field_name('views')); ?>">
            		<option <?php selected($views, 'y', true); ?> value="y"><?php esc_html_e('Yes', 'videofly'); ?></option>
            		<option <?php selected($views, 'n', true); ?> value="n"><?php esc_html_e('No', 'videofly'); ?></option>
            	</select>
            </label>
        </p>

        <p>
            <label for="<?php echo vdf_var_sanitize($this->get_field_id('comments')); ?>"><?php esc_html_e('Show comments','videofly') ?>:
            	<select name="<?php echo vdf_var_sanitize($this->get_field_name('comments')); ?>">
            		<option <?php selected($comments, 'y', true); ?> value="y"><?php esc_html_e('Yes', 'videofly'); ?></option>
            		<option <?php selected($comments, 'n', true); ?> value="n"><?php esc_html_e('No', 'videofly'); ?></option>
            	</select>
            </label>
        </p>
		<p>
            <label for="<?php echo vdf_var_sanitize($this->get_field_id('nr_posts')) ?>">
            	<?php esc_html_e( 'Number of posts' , 'videofly' ); ?>
            </label>:
            <input class="widefat digit" id="<?php echo vdf_var_sanitize($this->get_field_id('nr_posts')) ?>" name="<?php echo vdf_var_sanitize($this->get_field_name('nr_posts')) ?>" type="text" value="<?php echo esc_attr($nr_posts) ?>" />
        </p>
        <p>
            <label for="<?php echo vdf_var_sanitize($this->get_field_id('tab')); ?>"><?php esc_html_e('Show tabs','videofly') ?>:
            	<select name="<?php echo vdf_var_sanitize($this->get_field_name('tab')); ?>">
            		<option<?php selected($tab, 'y', true); ?> value="y"><?php esc_html_e('Yes', 'videofly'); ?></option>
            		<option<?php selected($tab, 'n', true); ?> value="n"><?php esc_html_e('No', 'videofly'); ?></option>
            	</select>
            </label>
        </p>

		<p>
            <label for="<?php echo vdf_var_sanitize($this->get_field_name('columns')) ?>">
            	<?php esc_html_e( 'Columns','videofly' ) ?>:
            </label>
            <select name="<?php echo vdf_var_sanitize($this->get_field_name('columns')) ?>">
            	<option value="1"<?php selected($columns, '1'); ?>>
            		1 <?php esc_html_e( 'column' , 'videofly' ) ?>
            	</option>
            	<option value="2"<?php selected($columns, '2'); ?>>
            		2 <?php esc_html_e( 'columns' , 'videofly' ) ?>
            	</option>
            </select>
        </p>

		<div class="ts-content-taxonomy">
			<p>
				<label for="<?php echo vdf_var_sanitize($this->get_field_name('customPost')) ?>">
					<?php esc_html_e("Select post type",'videofly') ?>:
				</label>
				<select name="<?php echo vdf_var_sanitize($this->get_field_name("customPost")) ?>" class="ts-widget-custom-post">
					<option value=''><?php esc_html_e("Select item",'videofly') ?></option>
					<?php foreach($allowedPostTypes as $custom_post => $value): ?>
						<option value="<?php echo vdf_var_sanitize($custom_post) ?>"<?php selected($custom_post, $customPost) ?>>
							<?php echo vdf_var_sanitize($custom_post) ?>
						</option>
					<?php endforeach ?>
				</select>
			</p>
			<div class="ts-taxonomy" data-taxonomy="<?php echo vdf_var_sanitize($this->get_field_name('taxonomy')) ?>">
				<?php if( !empty($taxonomy) && !empty($customPost) ): ?>
					<p>
						<label ><?php esc_html_e('Select post taxonomy','videofly') ?>:
							<select class="ts-select-taxonomy widefat multiple-select" name="<?php echo vdf_var_sanitize($this->get_field_name('taxonomy')) ?>">
								<option value=""><?php esc_html_e('Select taxonomy','videofly') ?></option>
								<?php foreach( $allowedPostTypes[$customPost] as $taxonomyisArray => $textUser ): ?>
									<option value="<?php echo vdf_var_sanitize($taxonomyisArray) ?>" <?php selected($taxonomyisArray, $taxonomy) ?>>
										<?php echo vdf_var_sanitize($textUser) ?>
									</option>
								<?php endforeach ?>
							</select>
						</label>
					</p>
				<?php endif ?>
			</div>
			<div class="ts-taxonomies" data-taxonomies="<?php echo vdf_var_sanitize($this->get_field_name('taxonomies')) ?>">
				<?php if( !empty($taxonomy) && !empty($customPost) ): ?>
					<?php
						$terms = get_terms($taxonomy, array('hide_empty' => false)) ?>
					<?php if( !empty($terms) && is_array($terms) && !is_wp_error($terms) ): ?>
						<label><?php echo ($taxonomy == 'post_tag' ? esc_html__('Select post tag', 'videofly') :  esc_html__('Select post terms', 'videofly')) ?>:
							<select multiple name="<?php echo vdf_var_sanitize($this->get_field_name('taxonomies')) ?>[]" class="widefat multiple-select">
								<?php foreach( $terms as $term ): ?>
									<option value="<?php echo vdf_var_sanitize($term->slug) ?>"<?php echo (in_array($term->slug, $taxonomies) ? ' selected="selected"' : '') ?>>
										<?php echo vdf_var_sanitize($term->name); ?>
									</option>
								<?php endforeach ?>
							</select>
						</label>
					<?php endif ?>
				<?php endif ?>
			</div>
		</div>
		<?php
    }

    function getHtmlPosts($arrayPosts, $instance){ 

		$number = isset($instance['number']) && ($instance['number'] == 'y' || $instance['number'] == 'n') ? $instance['number'] : 'y';
		$image = isset($instance['image']) && ($instance['image'] == 'y' || $instance['image'] == 'n') ? $instance['image'] : 'y';
		$date = isset($instance['date']) && ($instance['date'] == 'y' || $instance['date'] == 'n') ? $instance['date'] : 'n';
		$likes   = isset($instance['likes']) && ($instance['likes'] == 'y' || $instance['likes'] == 'n') ? $instance['likes'] : 'y';
		$views   = isset($instance['views']) && ($instance['views'] == 'y' || $instance['views'] == 'n') ? $instance['views'] : 'n';
		$comments = isset($instance['comments']) && ($instance['comments'] == 'y' || $instance['comments'] == 'n') ? $instance['comments'] : 'y';
		$columns = isset($instance['columns']) && ($instance['columns'] === '1' || $instance['columns'] === '2') ? $instance['columns'] : '1';
		$class_columns = ($columns === '1') ? '' : ($columns === '2') ? 'col-lg-6 col-md-6 col-sm-12' : 'col-lg-12 col-md-12 col-sm-12';

    	?>
		<?php if( !empty($arrayPosts) ) : $count = 0 ?>
			<ul class="widget-items row <?php echo ' widget-columns-' . $columns; echo($image == 'y' ? ' widget-has-image' : '') ?>">
	        	<?php foreach( $arrayPosts as $postCategory ): ?>
	            	<?php $count++;

					if( get_post_thumbnail_id($postCategory->ID) ){
						$postCategory_img = wp_get_attachment_image(get_post_thumbnail_id($postCategory->ID) , 'vdf_grid' , '');
						$cnt_a1 = ' href="' . get_permalink($postCategory->ID) . '"';
						$cnt_a2 = ' href="' . get_permalink($postCategory->ID) . '#comments"';
						$cnt_a3 = ' class="entry-img" href="' . get_permalink($postCategory->ID) . '"';

					}else{
						$postCategory_img = '<img src="' . get_template_directory_uri() . '/images/no-image.png" alt="" />';
						$cnt_a1 = ' href="' . get_permalink($postCategory->ID) . '"';
						$cnt_a2 = ' href="' . get_permalink($postCategory->ID) . '#comments"';
						$cnt_a3 = ' class="entry-img" href="' . get_permalink($postCategory->ID) . '"';
					}

					$article_date = get_the_date('', $postCategory->ID); ?>
	                <li class="<?php echo vdf_var_sanitize($class_columns); ?>">
						<article class="row<?php echo ($number == 'y' ? ' widget-has-number' : '') ?>">
							<?php if( $image == 'y' ) : ?>
								<div class="col-lg-12 col-md-12 col-sm-12">
	                                <a <?php echo vdf_var_sanitize($cnt_a3); ?>><?php echo vdf_var_sanitize($postCategory_img); ?></a>
	                            </div>
	                        <?php endif; ?>
	                        <div class="col-lg-12 col-md-12 col-sm-12">
	                        	<?php if( $number == 'y' ) : ?>
	                        		<span class="count-item"><?php echo vdf_var_sanitize($count); ?></span>
	                        	<?php endif; ?>
	                        	<div class="entry-content">
	                                <h4 class="title">
	                                	<a <?php echo vdf_var_sanitize($cnt_a1); ?>>
											<?php echo vdf_var_sanitize($postCategory->post_title); ?>
										</a>
									</h4>
									<div class="widget-meta">
										<ul class="list-inline">
											<?php if( $date == 'y' ) : ?>
												<li class="meta-date">
													<span><?php echo esc_attr($article_date) ?></span>
												</li>
											<?php endif; ?>
											<?php if( $comments == 'y' ) : ?>
												<li class="red-comments">
												    <a <?php echo vdf_var_sanitize($cnt_a2); ?>>
												        <i class="icon-comments"></i>
												        <span class="comments-count">
												            <?php echo vdf_var_sanitize($postCategory->comment_count) . ' '; ?>
												        </span>
												    </a>
												</li>
											<?php endif; ?>
											<?php if( $likes == 'y' ) : ?>
												<?php touchsize_likes($postCategory->ID, '<li class="meta-likes">', '</li>', true, false); ?>
											<?php endif; ?>
											<?php if( $views == 'y' ) : ?>
												<?php vdf_get_views($postCategory->ID, '<li class="meta-views"><i class="icon-views"></i> ', '</li>'); ?>
											<?php endif; ?>
										</ul>
									</div>
	                        	</div>
	                        </div>
						</article>
	                </li>
	            <?php endforeach; ?>
	        </ul>
        <?php else: ?>
        	<?php esc_html_e('No post found', 'videofly') ?>
        <?php endif ?>
    	<?php
    }
}
function register_latest_posts_widget() {
    register_widget('widget_latest_posts');
}
add_action( 'widgets_init', 'register_latest_posts_widget' );

?>