<?php

/**
 * Instagrm_Feed_Widget Class
 */
class widget_instagram extends WP_Widget {
	/** constructor */
	function widget_instagram() {
       	$options = array( 'classname' => 'instagram_widget', 'description' => esc_html__('Instagram Widget' , 'videofly' ) );
       	parent::__construct( 'widget_touchsize_instagram' , esc_html__( 'Instagram Widget' , 'videofly' )  , $options );

    }
	/* WP_Widget::widget */
	function widget( $args, $instance ) {
		extract( $args );
		//get widget information to display on page
		$title = apply_filters( 'widget_title', $instance['title'] );

		if(isset($instance['user_id'])){
			$user_id = apply_filters( 'widget_title', $instance['user_id'] );
		}else{
			$user_id = '';
		}

		if(isset($instance['access_token'])){
			$access_token = apply_filters( 'widget_title', $instance['access_token'] );
		}else{
			$access_token = '';
		}

		if(isset($instance['picture_number'])){
			$picture_number = apply_filters( 'widget_title', $instance['picture_number'] );
		}else{
			$picture_number = 0;
		}

		if(isset($instance['link_images'])){
			$picture_size = apply_filters( 'widget_title', $instance['picture_size'] );
		}else{
			$picture_size = 'thumbnail';
		}

		if(isset($instance['link_images'])){
			$link_images = apply_filters( 'widget_title', $instance['link_images'] );
		}else{
			$link_images = false;
		}

		if(isset($instance['link_images'])){
			$show_likes = apply_filters( 'widget_title', $instance['show_likes'] );
		}else{
			$show_likes = false;
		}

		if(isset($instance['link_images'])){
			$show_caption = apply_filters( 'widget_title', $instance['show_caption'] );
		}else{
			$show_caption = false;
		}

		echo vdf_var_sanitize($before_widget);
		if ( $title ){
			echo vdf_var_sanitize($before_title . $title . $after_title);
		};

		$elements_per_row = isset($instance['elements_per_row']) ? (int)$instance['elements_per_row'] : 3;

		switch($elements_per_row) {
			case 4:
				$class_per_row = 'ts-four-posts';
				break;

			case 20:
				$class_per_row = 'ts-twenty-posts';
				break;

			default:
				$class_per_row = 'ts-three-posts';
				break;
		}

		$layoutClass = isset($instance['layout']) && $instance['layout'] == 'grid' ? 'ts-intagram-grid' : 'ts-intagram-mosaic';
		$results = $this->get_recent_data($user_id, $access_token);

		$i=1;
		echo '<ul id="instagram_widget" class="widget-list '. $class_per_row .' '. $layoutClass .'">';
		if(!empty($results['data'])){
			foreach($results['data'] as $item){
				if($picture_number == 0){

					echo sprintf(esc_html__('%s Please set the Number of images to show within the widget %s','videofly'),'<strong>', '</strong>');
					break;
				}


				echo "<li>";
				echo '<div class="relative">';
				if(!empty($link_images)){
					echo "<a href='".$item['link']."' target='_blank'><img src='".$item['images'][$picture_size]['url']."' alt='".$title." image'/></a>";
				}else{
					echo "<img src='".$item['images'][$picture_size]['url']."' alt=''/>";
				}
				if($show_likes){
					if(!empty($item['likes']['count'])){
						echo "<p class='instagram_likes'>".esc_html__('Likes:','videofly')." <span class='likes_count'>".$item['likes']['count']."</span></p>";
					}
				}
				if($show_caption){
					if(!empty($item['caption']['text'])){
						echo "<p class='instagram_caption'>".$item['caption']['text']."</p>";
					}
				}
				echo '</div>';
				echo "</li>";
				if($i == $picture_number){
					echo "</ul>";
					break;
				}else{
					$i++;
				}
			}
		}else{
			echo "<strong>".esc_html__('The user currently does not have any images...','videofly')."</strong>";
		}
		echo vdf_var_sanitize($after_widget);
	}

	/* WP_Widget::update */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		//update setting with information form widget form
		$instance['title'] = strip_tags($new_instance['title']);

		$instance['access_token'] = strip_tags($new_instance['access_token']);
		$instance['user_id'] = strip_tags($new_instance['user_id']);

		$instance['picture_number'] = strip_tags($new_instance['picture_number']);
		$instance['picture_size'] = strip_tags($new_instance['picture_size']);
		$instance['link_images'] = strip_tags($new_instance['link_images']);

		$instance['show_likes'] = strip_tags($new_instance['show_likes']);
		$instance['show_caption'] = strip_tags($new_instance['show_caption']);

		$instance['elements_per_row'] = (int)$new_instance['elements_per_row'];
		$instance['layout'] = strip_tags($new_instance['layout']);

		return $instance;
	}

	/* WP_Widget::form */
	function form( $instance ) {
		if ( $instance ) {
			$title = esc_attr( $instance[ 'title' ] );

			if(isset($instance[ 'access_token' ])){
				$access_token = esc_attr( $instance[ 'access_token' ] );
			}else{
				$access_token = '';
			}

			if(isset($instance[ 'user_id' ])){
				$user_id = esc_attr( $instance[ 'user_id' ] );
			}else{
				$user_id = '';
			}

			if(isset($instance[ 'picture_number' ])){
				$picture_number = esc_attr( $instance[ 'picture_number' ] );
			}else{
				$picture_number = 4;
			}

			if(isset($instance[ 'picture_size' ])){
				$picture_size = esc_attr( $instance[ 'picture_size' ] );
			}else{
				$picture_size = 'thumbnail';
			}

			if(isset($instance[ 'show_likes' ])){
				$show_likes = esc_attr( $instance[ 'show_likes' ] );
			}else{
				$show_likes = false;
			}

			if(isset($instance[ 'show_caption' ])){
				$show_caption = esc_attr( $instance[ 'show_caption' ] );
			}else{
				$show_caption = false;
			}

			if(isset($instance[ 'link_images' ])){
				$link_images = esc_attr( $instance[ 'link_images' ] );
			}else{
				$link_images = false;
			}

			$elements_per_row = isset($instance['elements_per_row']) ? $instance['elements_per_row'] : 3;
			$layout = isset($instance['layout']) ? $instance['layout'] : 'grid';

		}
		else {
			$title = esc_html__( 'Title', 'videofly' );
			$username = esc_html__( 'Username', 'videofly' );
			$access_token = esc_html__( 'Access Token', 'videofly' );
			$user_id = esc_html__( 'User ID', 'videofly' );
			$picture_size = 'thumbnail';
			$picture_number = 0;
			$show_likes = false;
			$show_caption = false;
			$link_images = false;
			$elements_per_row = 3;
		}


		$client_id = 'd1a0f83b878c402ab90d0c7cca7d9174';
		$client_secrete = '591c04ca8eae475dbb9b342eb22f5de1';
		$redirect_uri = 'http://www.touchsize.net';
		$token_url = 'http://redcodn.com/instagram';


		$picture_sizes = array('thumbnail' => 'Thumbnail', 'low_resolution' => 'Low Resolution','standard_resolution' => 'Standard Resolution');
		?>
		<p>
		<label for="<?php echo vdf_var_sanitize($this->get_field_id('title')); ?>"><?php esc_html_e('Title:','videofly'); ?></label>
		<input class="widefat" id="<?php echo vdf_var_sanitize($this->get_field_id('title')); ?>" name="<?php echo vdf_var_sanitize($this->get_field_name('title')); ?>" type="text" value="<?php echo vdf_var_sanitize($title); ?>" />
		</p>
		<p>
		<label for="<?php echo vdf_var_sanitize($this->get_field_id('user_id')); ?>"><?php esc_html_e('User ID:','videofly'); ?></label>
		<input class="widefat" id="<?php echo vdf_var_sanitize($this->get_field_id('user_id')); ?>" name="<?php echo vdf_var_sanitize($this->get_field_name('user_id')); ?>" type="text" value="<?php echo vdf_var_sanitize($user_id); ?>" />
		</p>
		<p>
		<label for="<?php echo vdf_var_sanitize($this->get_field_id('access_token')); ?>"><?php esc_html_e('Access Token:','videofly'); ?></label>
		<input class="widefat" id="<?php echo vdf_var_sanitize($this->get_field_id('access_token')); ?>" name="<?php echo vdf_var_sanitize($this->get_field_name('access_token')); ?>" type="text" value="<?php echo vdf_var_sanitize($access_token); ?>" />
		</p>
		<p>
			<label for="<?php echo vdf_var_sanitize($this->get_field_id('picture_number')); ?>"><?php esc_html_e('Number of Images:','videofly'); ?></label>
			<input type="text" id="<?php echo vdf_var_sanitize($this->get_field_id('picture_number')); ?>" name="<?php echo vdf_var_sanitize($this->get_field_name('picture_number')); ?>" value="<?php echo vdf_var_sanitize($picture_number) ?>">
		</p>
		<p>
			<label for="<?php echo vdf_var_sanitize($this->get_field_id('elements_per_row')); ?>"><?php esc_html_e('Elements per row','videofly'); ?>:</label>
			<select id="<?php echo vdf_var_sanitize($this->get_field_id('elements_per_row')); ?>" name="<?php echo vdf_var_sanitize($this->get_field_name('elements_per_row')); ?>">
				<option value="3"<?php selected($elements_per_row, '3'); ?>>3</option>
				<option value="4"<?php selected($elements_per_row, '4'); ?>>4</option>
				<option value="20"<?php selected($elements_per_row, '20'); ?>>20</option>
			</select>
		</p>
		<p>
			<label for="<?php echo vdf_var_sanitize($this->get_field_id('layout')); ?>"><?php esc_html_e('Layout','videofly'); ?>:</label>
			<select id="<?php echo vdf_var_sanitize($this->get_field_id('layout')); ?>" name="<?php echo vdf_var_sanitize($this->get_field_name('layout')); ?>">
				<option value="grid"<?php selected($layout, 'grid'); ?>><?php esc_html_e('Grid', 'videofly'); ?></option>
				<option value="mosaic"<?php selected($layout, 'mosaic'); ?>><?php esc_html_e('Mosaic', 'videofly'); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo vdf_var_sanitize($this->get_field_id('picture_size')); ?>"><?php esc_html_e('Picture Size:','videofly'); ?></label>
			<select id="<?php echo vdf_var_sanitize($this->get_field_id('picture_size')); ?>" name="<?php echo vdf_var_sanitize($this->get_field_name('picture_size')); ?>">
					<?php foreach($picture_sizes as $item => $val):?>
						<option value="<?php echo vdf_var_sanitize($item);?>" <?php if($item == $picture_size){echo 'selected="selected"';};?>><?php echo vdf_var_sanitize($val);?></option>
					<?php endforeach;?>
			</select>
		</p>
		<p>
		<label for="<?php echo vdf_var_sanitize($this->get_field_id('link_images')); ?>"><?php esc_html_e('Link images to full image:','videofly'); ?></label>
		<input class="" id="<?php echo vdf_var_sanitize($this->get_field_id('link_images')); ?>" name="<?php echo vdf_var_sanitize($this->get_field_name('link_images')); ?>" type="checkbox" <?php echo (($link_images)? "CHECKED":''); ?> />
		</p>
		<p>
		<label for="<?php echo vdf_var_sanitize($this->get_field_id('show_likes')); ?>"><?php esc_html_e('Show Likes:','videofly'); ?></label>
		<input class="" id="<?php echo vdf_var_sanitize($this->get_field_id('show_likes')); ?>" name="<?php echo vdf_var_sanitize($this->get_field_name('show_likes')); ?>" type="checkbox" <?php echo (($show_likes)? "CHECKED":''); ?> />
		</p>
		<p>
		<label for="<?php echo vdf_var_sanitize($this->get_field_id('show_caption')); ?>"><?php esc_html_e('Show Caption:','videofly'); ?></label>
		<input class="" id="<?php echo vdf_var_sanitize($this->get_field_id('show_caption')); ?>" name="<?php echo vdf_var_sanitize($this->get_field_name('show_caption')); ?>" type="checkbox" <?php echo (($show_caption)? "CHECKED":''); ?> />
		</p>

		<?php
			$token_msg = sprintf(esc_html__('If you do not have an ID or access token, please visit %s Get Access token %s to receive a valid token','videofly'),'<a href="'.$token_url.'" target="_blank">', '</a>');
		?>
		<p><?php echo vdf_var_sanitize($token_msg); ?></p>
		<p><?php esc_html_e("If you don't know your user id:", 'videofly'); ?> <a href="http://jelled.com/instagram/lookup-user-id" target="_blank"><?php esc_html_e('Get you user id', 'videofly'); ?></a></p>
		<?php
	}

	function get_recent_data($user_id, $access_token){

		$apiurl = "https://api.instagram.com/v1/users/". $user_id ."/media/recent/?access_token=". $access_token;

		$response = wp_remote_get( $apiurl, array('sslverify' => false, 'decompress' => false) );

		if(is_wp_error($response)){
			echo vdf_var_sanitize($response->get_error_message());
			return;
		}else{
			$data = json_decode( $response['body'], true );
		}


		return $data;
	}



} // class Instagrm_Feed_Widget

// register Instagrm widget
function register_instagram_widget() {
    register_widget( 'widget_instagram' );
}
add_action( 'widgets_init', 'register_instagram_widget' );

?>