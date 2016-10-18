<?php
class TouchsizeLikes {

    function __construct() 
    {	
    	add_action('wp_ajax_touchsize-likes', array(&$this, 'ajax_callback'));
    	add_action('wp_ajax_nopriv_touchsize-likes', array(&$this, 'ajax_callback'));
	}

	function ajax_callback($post_id){

		$options = get_option( 'touchsize_likes_settings' );
		if( !isset($options['add_to_posts']) ) $options['add_to_posts'] = '0';
		if( !isset($options['add_to_pages']) ) $options['add_to_pages'] = '0';
		if( !isset($options['add_to_other']) ) $options['add_to_other'] = '0';
		if( !isset($options['zero_postfix']) ) $options['zero_postfix'] = '';
		if( !isset($options['one_postfix']) ) $options['one_postfix'] = '';
		if( !isset($options['more_postfix']) ) $options['more_postfix'] = '';

		if( isset($_POST['likes_id']) ) {
		    // Click event. Get and Update Count
			$post_id = str_replace('touchsize-likes-', '', $_POST['likes_id']);
			echo $this->like_this($post_id, $options['zero_postfix'], $options['one_postfix'], $options['more_postfix'], 'update');
		} else {
		    // AJAXing data in. Get Count
			$post_id = str_replace('touchsize-likes-', '', $_POST['post_id']);
			echo $this->like_this($post_id, $options['zero_postfix'], $options['one_postfix'], $options['more_postfix'], 'get');
		}
		
		exit;
	}
	
	function like_this($post_id, $zero_postfix = false, $one_postfix = false, $more_postfix = false, $action = 'get'){
		if(!is_numeric($post_id)) return;
		$zero_postfix = strip_tags($zero_postfix);
		$one_postfix = strip_tags($one_postfix);
		$more_postfix = strip_tags($more_postfix);
		$general_icon = get_option('videotouch_general', array('like_icons' => 'heart'));

		if ( isset($general_icon['like']) && $general_icon['like'] == 'n' ) return;	
		
		switch($action) {
		
			case 'get':
				$likes = get_post_meta($post_id, '_touchsize_likes', true);
				if( !$likes ){
					$likes = 0;
					add_post_meta($post_id, '_touchsize_likes', $likes, true);
				}
				
				if( $likes == 0 ) { $postfix = $zero_postfix; }
				elseif( $likes == 1 ) { $postfix = $one_postfix; }
				else { $postfix = $more_postfix; }
				
				return '<span class="touchsize-likes-count icon-' . $general_icon['like_icons'] . '">'. $likes .'</span> <span class="touchsize-likes-postfix">'. $postfix .'</span>';
				break;
				
			case 'update':
				$likes = get_post_meta($post_id, '_touchsize_likes', true);
				if( isset($_COOKIE['touchsize_likes_'. $post_id]) ) return $likes;
				
				$likes++;
				update_post_meta($post_id, '_touchsize_likes', $likes);
				setcookie('touchsize_likes_'. $post_id, $post_id, time()*20, '/');
				
				if( $likes == 0 ) { $postfix = $zero_postfix; }
				elseif( $likes == 1 ) { $postfix = $one_postfix; }
				else { $postfix = $more_postfix; }
				
				return '<span class="touchsize-likes-count icon-' . $general_icon['like_icons'] . '">'. $likes .'</span> <span class="touchsize-likes-postfix">'. $postfix .'</span>';
				break;
		
		}
	}
	
	function do_likes($post_id, $before, $after){
		global $post;

        $options = get_option( 'touchsize_likes_settings' );

		if( !isset($options['zero_postfix']) ) $options['zero_postfix'] = '';
		if( !isset($options['one_postfix']) ) $options['one_postfix'] = '';
		if( !isset($options['more_postfix']) ) $options['more_postfix'] = '';
		
		$output = $this->like_this($post_id, $options['zero_postfix'], $options['one_postfix'], $options['more_postfix']);

		if ( empty($output) ) return;
  
  		$class = 'touchsize-likes';
  		$title = __('Like this', 'touchsizelikes');
		if( isset($_COOKIE['touchsize_likes_'. $post_id]) ){
			$class = 'touchsize-likes active';
			$title = __('You already like this', 'touchsizelikes');
		}
		
		return $before .'<a href="#" class="'. $class .'" data-id="touchsize-likes-'. $post_id .'" title="'. $title .'">'. $output .'</a>'. $after;
	}
	
}
global $touchsize_likes;
$touchsize_likes = new TouchsizeLikes();

function touchsize_likes($post_id, $before = '', $after = '', $return = true){
	global $touchsize_likes;
	if( $return ){
    	echo $touchsize_likes->do_likes($post_id, $before, $after);
    }else{
    	return $touchsize_likes->do_likes($post_id, $before, $after);
    }
}
