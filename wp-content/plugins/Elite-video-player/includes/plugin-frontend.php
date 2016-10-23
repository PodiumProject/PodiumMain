<?php
	function Elite_player_shortcode($atts){
		$args = shortcode_atts( 
			array(
				'id'   => '-1'
			), 
			$atts
		);
		$elite_id = (int) $args['id'];
		$elite_players = get_option('elite_players');
		$elite_player = $elite_players[$elite_id];
		
		// wp_enqueue_script("elite_embed", plugins_url()."/Elite-video-player/js/embed.js", array('jquery'),ELITE_PLAYER_VERSION);
		// wp_enqueue_script("elite_jquery.mCustomScrollbar", plugins_url()."/Elite-video-player/js/jquery.mCustomScrollbar.min.js", array('jquery'),ELITE_PLAYER_VERSION);
		// wp_enqueue_script("elite_Froogaloop2", plugins_url()."/Elite-video-player/js/froogaloop.min.js", array('jquery'),ELITE_PLAYER_VERSION);
		// wp_enqueue_script("elite_THREEx.FullScreen", plugins_url()."/Elite-video-player/js/THREEx.FullScreen.min.js", array('jquery'),ELITE_PLAYER_VERSION);
		// wp_enqueue_script("elite_playlist", plugins_url()."/Elite-video-player/js/Playlist.min.js", array('jquery'),ELITE_PLAYER_VERSION);
		// wp_enqueue_script("elite_video_player", plugins_url()."/Elite-video-player/js/videoPlayer.min.js", array(),ELITE_PLAYER_VERSION);
		// wp_enqueue_script("elite_ZeroClipboard", plugins_url()."/Elite-video-player/js/ZeroClipboard.min.js", array(),ELITE_PLAYER_VERSION);
		
		// wp_enqueue_style( 'elite_player_style', plugins_url()."/Elite-video-player/css/elite.min.css" , array(),ELITE_PLAYER_VERSION);
		// wp_enqueue_style( 'elite_player_icons', plugins_url()."/Elite-video-player/css/font-awesome.min.css" , array(),ELITE_PLAYER_VERSION);
		// wp_enqueue_style( 'elite_player_scrollbar', plugins_url()."/Elite-video-player/css/jquery.mCustomScrollbar.min.css" , array(),ELITE_PLAYER_VERSION);
		
		/*elite_vp_trace($players[$elite_id]);  */

		/*switch( $elite_players[$elite_id]["skinPlaylist"] ) {
			case 'Default':
				wp_enqueue_style( 'elite_skin_playlist1', plugins_url()."/Elite-video-player/css/videoPlayer.theme1_Playlist.css" , array(),ELITE_PLAYER_VERSION);
				break;
			case 'Classic':
				wp_enqueue_style( 'elite_skin_playlist2', plugins_url()."/Elite-video-player/css/videoPlayer.theme2_Playlist.css" , array(),ELITE_PLAYER_VERSION);
				break;
			case 'Minimal':
				wp_enqueue_style( 'elite_skin_playlist3', plugins_url()."/Elite-video-player/css/videoPlayer.theme3_Playlist.css" , array(),ELITE_PLAYER_VERSION);
				break;
			case 'Transparent':
				wp_enqueue_style( 'elite_skin_playlist4', plugins_url()."/Elite-video-player/css/videoPlayer.theme4_Playlist.css" , array(),ELITE_PLAYER_VERSION);
				break;
			case 'Silver':
				wp_enqueue_style( 'elite_skin_playlist5', plugins_url()."/Elite-video-player/css/videoPlayer.theme5_Playlist.css" , array(),ELITE_PLAYER_VERSION);
				break;
		}*/
		
		$elite_player['rootFolder'] = plugins_url()."/Elite-video-player/";
		$output = ('<div class="Elite_video_player" id="'.$elite_id.'" ><div id="elite_options" style="display:none;">'.json_encode($elite_player).'</div></div>');
		return $output;
	}
	add_shortcode('Elite_video_player', 'Elite_player_shortcode');