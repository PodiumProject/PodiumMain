<?php

	/*
	Plugin Name: Elite Video Player | Shared by Themes24x7.com
	Plugin URI: http://codecanyon.net/item/elite-video-player-wordpress-plugin/10496434
	Description: Elite Video Player
	Version: 2.0.6
	Author: _zac_
	Author URI: http://codecanyon.net/user/_zac_
	*/

	//define( 'WP_DEBUG', true );
	define('ELITE_PLAYER_DIR', plugin_dir_url( __FILE__ ));
	define('ELITE_PLAYER_VERSION', '2.0.6');
	
	function elite_vp_trace($var){
		echo("<pre style='background:#fcc;color:#000;font-size:12px;font-weight:bold'>");
		print_r($var);
		echo("</pre>");
	}

	if(!is_admin()) {
		include("includes/plugin-frontend.php");
	}
	else {
		include("includes/plugin-admin.php");
		register_deactivation_hook( __FILE__, "deactivate_elite_player");
		add_filter("plugin_action_links_" . plugin_basename(__FILE__), "elite_player_admin_link");
	}
	
	
	function elite_player_scripts() {
		wp_enqueue_script("elite_embed", plugins_url()."/Elite-video-player/js/embed.js", array('jquery'),ELITE_PLAYER_VERSION);
		wp_enqueue_script("elite_jquery.mCustomScrollbar", plugins_url()."/Elite-video-player/js/jquery.mCustomScrollbar.min.js", array('jquery'),ELITE_PLAYER_VERSION);
		wp_enqueue_script("elite_Froogaloop2", plugins_url()."/Elite-video-player/js/froogaloop.min.js", array('jquery'),ELITE_PLAYER_VERSION);
		wp_enqueue_script("elite_THREEx.FullScreen", plugins_url()."/Elite-video-player/js/THREEx.FullScreen.min.js", array('jquery'),ELITE_PLAYER_VERSION);
		wp_enqueue_script("elite_playlist", plugins_url()."/Elite-video-player/js/Playlist.min.js", array('jquery'),ELITE_PLAYER_VERSION);
		wp_enqueue_script("elite_video_player", plugins_url()."/Elite-video-player/js/videoPlayer.min.js", array(),ELITE_PLAYER_VERSION);
		wp_enqueue_script("elite_ZeroClipboard", plugins_url()."/Elite-video-player/js/ZeroClipboard.min.js", array(),ELITE_PLAYER_VERSION);
		
		wp_enqueue_style( 'elite_player_style', plugins_url()."/Elite-video-player/css/elite.css" , array(),ELITE_PLAYER_VERSION);
		wp_enqueue_style( 'elite_player_icons', plugins_url()."/Elite-video-player/css/elite-font-awesome.min.css" , array(),ELITE_PLAYER_VERSION);
		wp_enqueue_style( 'elite_player_scrollbar', plugins_url()."/Elite-video-player/css/jquery.mCustomScrollbar.min.css" , array(),ELITE_PLAYER_VERSION);
	}
	add_action( 'wp_enqueue_scripts', 'elite_player_scripts' );
	
	function elite_player_admin_scripts() {
		// wp_enqueue_media();
		// wp_enqueue_script("video_player_admin", plugins_url()."/Elite-video-player/js/plugin_admin.js", array('jquery','jquery-ui-sortable','jquery-ui-resizable','jquery-ui-selectable','jquery-ui-tabs' ),ELITE_PLAYER_VERSION);
		// wp_enqueue_style( 'video_player_admin_css', plugins_url()."/Elite-video-player/css/player-admin.css",array(), ELITE_PLAYER_VERSION );
		// wp_enqueue_style( 'jquery-ui-style', plugins_url()."/Elite-video-player/css/jquery-ui.css",array(), ELITE_PLAYER_VERSION );
		// pass $players to javascript
		// wp_localize_script( 'video_player_admin', 'options', json_encode($players[$current_id]) );
	}
	add_action( 'wp_enqueue_scripts', 'elite_player_admin_scripts' );
	
	function elite_player_admin_link($links) {
		array_unshift($links, '<a href="' . get_admin_url() . 'options-general.php?page=elite_player_admin">Admin</a>');
		return $links;
	}
	
	function deactivate_elite_player() {
		//delete_option("elite_players");
	}