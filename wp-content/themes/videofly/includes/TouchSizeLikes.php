<?php
class TouchsizeLikes {

    function __construct() 
    {	
    	add_action('wp_ajax_touchsize-likes', array(&$this, 'ajax_callback'));
    	add_action('wp_ajax_nopriv_touchsize-likes', array(&$this, 'ajax_callback'));
	}

	function ajax_callback(){

		if( isset($_POST['post_id']) ) wp_send_json($this->like_this($_POST['post_id'], 'ajax'));
		
		exit;
	}
	
	function like_this($post_id, $action = '', $text = true){

		if( !is_numeric($post_id) ) return;
		
		$text = isset( $_POST['show_text'] ) ? $_POST['show_text'] : $text;

		$general_icon = get_option('videofly_general', array('like_icon' => 'heart', 'dislike_icon' => 'dislike'));

		if ( isset($general_icon['like']) && $general_icon['like'] == 'n' ) return;

		global $wpdb;

		$likes = get_post_meta($post_id, '_touchsize_likes', true);
		$likes = !$likes || !is_array($likes) ? array('likes' => 0, 'dislikes' => 0) : $likes;
		$textLike = $text && isset($general_icon['text_like']) && !empty($general_icon['text_like']) ? '<span class="vdf-like-text">'. esc_attr($general_icon['text_like']) .'</span>' : '';
		$textDislike = $text && isset($general_icon['text_dislike']) && !empty($general_icon['text_dislike']) ? '<span class="vdf-like-text">'. esc_attr($general_icon['text_dislike']) .'</span>' : '';


		$return = array();
		$time = time() + (7 * 24 * 60 * 60);
		
		if ( $action == 'ajax' ) {

			if ( $_POST['act'] == 'like' ) {

				if ( isset($_COOKIE['vdf_dislikes_'. $post_id]) ) {
					setcookie('vdf_dislikes_'. $post_id, '', 1, '/');
					$likes['dislikes'] = intval($likes['dislikes']) > 0 ? intval($likes['dislikes']) - 1 : 0;
				}

				$likes['likes'] = intval($likes['likes']) + 1;

				$titleLikes = esc_html__('You already like this', 'videofly');
				$titleDislikes = esc_html__('I dislike this', 'videofly');

				setcookie('vdf_likes_'. $post_id, $post_id, $time, '/');

			} else {

				if( isset($_COOKIE['vdf_likes_'. $post_id]) ){
					setcookie('vdf_likes_'. $post_id, '', 1, '/');
					$likes['likes'] = intval($likes['likes']) > 0 ? intval($likes['likes']) - 1 : 0;
				}

				$likes['dislikes'] = intval($likes['dislikes']) + 1;

				$titleLikes = esc_html__('I like this', 'videofly');
				$titleDislikes = esc_html__('You already like this', 'videofly');

				setcookie('vdf_dislikes_'. $post_id, $post_id, $time, '/');
			}

			$return['titleLikes'] = $titleLikes;
			$return['titleDislikes'] = $titleDislikes;

			update_post_meta($post_id, '_touchsize_likes', $likes);

			$media = intval($likes['likes']) - intval($likes['dislikes']);
			update_post_meta($post_id, 'likes-media', $media);
		}

		$return['htmlLikes']    = '<span class="touchsize-likes-count icon-'. $general_icon['like_icon'] .'">'. $likes['likes'] .'</span>'. $textLike;
		$return['htmlDislikes'] = '<span class="touchsize-deslikes-count icon-'. $general_icon['dislike_icon'] .'">'. $likes['dislikes'] .'</span>'. $textDislike;

		return $return;
	}
	
	function do_likes( $post_id, $before, $after, $text = true ){

		$output = $this->like_this( $post_id, '', $text );

		if ( empty( $output ) ) return;

  		$titleLikes = esc_html__('I like this', 'videofly');
  		$titleDislikes = esc_html__('I dislike this', 'videofly');
  		
		if( isset($_COOKIE['vdf_likes_'. $post_id]) ){
			$classLike = ' active';
			$titleLikes = esc_html__('You already like this', 'videofly');
		}

		if( isset($_COOKIE['vdf_dislikes_'. $post_id]) ){
			$classDislike = ' active';
			$titleDislikes = esc_html__('You already like this', 'videofly');
		}

		return 
			$before .
				'<a href="#" data-show-text="' . $text . '" class="touchsize-likes'. ( isset($classLike) ? $classLike : '' ) .'" data-id="'. $post_id .'" title="'. $titleLikes .'">'. 
					$output['htmlLikes'] .
				'</a>
				<a href="#" data-show-text="' . $text . '" class="touchsize-dislikes'. (isset($classDislike) ? $classDislike : '') .'" data-id="'. $post_id .'" title="'. $titleDislikes .'">'. 
					$output['htmlDislikes'] .
				'</a>'. 
			$after;
	}
	
}
global $touchsize_likes;
$touchsize_likes = new TouchsizeLikes();

function touchsize_likes( $post_id, $before = '', $after = '', $echo = true, $text = true ) {

	global $touchsize_likes;

	if ( $echo ) {

		echo vdf_var_sanitize( $touchsize_likes->do_likes( $post_id, $before, $after, $text ) );

	} else {
		
		return vdf_var_sanitize( $touchsize_likes->do_likes( $post_id, $before, $after, $text ) ); 

	}
    
}