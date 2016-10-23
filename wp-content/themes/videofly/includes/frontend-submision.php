<?php

function custom_post_author_archive( &$query )
{
    if ( $query->is_author ) $query->set( 'post_type', array('post', 'video', 'ts-gallery') );
    remove_action( 'pre_get_posts', 'custom_post_author_archive' );
}
add_action( 'pre_get_posts', 'custom_post_author_archive' );

function ts_function_admin_bar(){

    if ( current_user_can('simple_user') && !current_user_can('manage_options') && !is_plugin_active('buddypress/bp-loader.php') ) {

        show_admin_bar(false);

    }

}
add_filter('after_setup_theme', 'ts_function_admin_bar', 11);

function ts_settings_user(){

	add_role('simple_user', esc_html__('Simple user', 'videofly'), array());

	$pages = array(
		'user-add-post.php' => 'Front-end - User add new post',
		'user-profile.php'  => 'Front-end - User profile',
		'user-settings.php' => 'Front-end - User settings'
	);

	foreach( $pages as $file => $title ){
		$existing = get_pages(array(
				'meta_key' => '_wp_page_template',
				'meta_value' => $file
			)
		);

		if( !isset($existing) || empty($existing) ){
			$args = array(
				'post_type'     => 'page',
				'post_title'    => $title,
				'post_status'   => 'publish',
				'page_template' => $file
			);

			wp_insert_post($args);
		}
	}
}
add_action('after_switch_theme', 'ts_settings_user');

function ts_save_post_user()
{
	if( !is_user_logged_in() || empty($_POST) || !isset($_POST['nonce-user-post']) || !wp_verify_nonce($_POST['nonce-user-post'], 'verify-save-post') ) return;

	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	if( !isset($_POST['agree']) && isset($_POST['ts-add-post']) ) return esc_html__('Check Terms agreement', 'videofly');

	if ( is_plugin_active('buddypress/bp-loader.php') ) {
		global $bp, $current_user;

		$redirect = esc_url( home_url('/') ) .'/members/'. $current_user->user_login . '/posts/';

	} else {
		global $wpdb;
		$profileId = $wpdb->get_results($wpdb->prepare("SELECT post_id, meta_value FROM ". esc_sql($wpdb->postmeta) ." WHERE meta_value = %s LIMIT 1", 'user-profile.php'), ARRAY_A);

		$redirect = isset($profileId[0]['post_id']) ? get_page_link($profileId[0]['post_id']) : esc_url( home_url( '/' ) );
	}

	$currentUserId = get_current_user_id();
	$postId = isset($_POST['postId']) && is_numeric($_POST['postId']) ? intval($_POST['postId']) : '';

	if( isset($_POST['ts-update-post']) || isset($_POST['ts-add-post']) ) {

		include_once (ABSPATH . "wp-admin" . '/includes/file.php');
		include_once (ABSPATH . "wp-admin" . '/includes/image.php');
	    include_once (ABSPATH . "wp-admin" . '/includes/media.php');

		if( isset($_FILES['ts-upload-img']) ) $_POST['tmp_name_img'] = $_FILES['ts-upload-img']['tmp_name'];
		if( isset($_FILES['ts-upload-video']) ) $_POST['tmp_name_video'] = $_FILES['ts-upload-video']['tmp_name'];

		$title = isset($_POST['ts-title-post']) ? sanitize_text_field($_POST['ts-title-post']) : '';
		$content = isset($_POST['ts-post-content']) ? sanitize_text_field($_POST['ts-post-content']) : '';
		$category = isset($_POST['ts-category-video']) && intval($_POST['ts-category-video']) > 0 ? intval($_POST['ts-category-video']) : '';
		$tags = isset($_POST['ts-tags']) ? sanitize_text_field($_POST['ts-tags']) : '';

		if( empty($title) ) $_POST['ts-error'] = esc_html__('Insert video title.', 'videofly');
		if( empty($content) ) $_POST['ts-error'] = esc_html__('Insert video description.', 'videofly');
		if( empty($category) ) $_POST['ts-error'] = esc_html__('Choose video category.', 'videofly');

		if( isset($_POST['ts-error']) && !empty($_POST['ts-error']) ) return $_POST;

	    if ( isset($_POST['ts-add-post']) ) {
	    	/* Upload image */
	    	if( isset($_FILES['ts-upload-img']['name']) && !empty($_FILES['ts-upload-img']['name']) ){

	    		$imgId = media_handle_upload('ts-upload-img', 0);

	    		if ( is_wp_error($imgId) ) $_POST['ts-error'] = $imgId->get_error_message();

	    	} else {
	    		$_POST['ts-error'] = esc_html__('Choose image', 'videofly');
	    	}

	    	/* Upload video */
	    	if ( $_POST['selected-tab'] == 'upload' ) {
		    	if ( isset($_FILES['ts-upload-video']['name']) && !empty($_FILES['ts-upload-video']['name']) ) {
			    	$videoId = media_handle_upload('ts-upload-video', 0);

			    	if ( !is_wp_error($videoId) ) {
		                $videoUrl = wp_get_attachment_url($videoId);
		                $meta = array('video' => $videoUrl, 'type' => 'upload');
			    	} else {
			    		$_POST['ts-error'] = $videoId->get_error_message();
			    	}
		    	} else {
		    		$_POST['ts-error'] = esc_html__('Upload any video.', 'videofly');
		    	}

	    	} elseif ( $_POST['selected-tab'] == 'url' ) {
	    		if( !empty($_POST['ts-url-video']) ) {
	    			$meta = array('video' => esc_url_raw($_POST['ts-url-video']), 'type' => 'url');
	    		} else {
	    			$_POST['ts-error'] = esc_html__('Set any video url.', 'videofly');
	    		}

	    	} else {
	    		if( !empty($_POST['ts-url-video']) ) {
	    			$meta = array('video' => $_POST['ts-embed-video'], 'type' => 'embed');
	    		} else {
	    			$_POST['ts-error'] = esc_html__('Set video embed.', 'videofly');
	    		}
	    	}

	    	$duration = isset($_POST['ts-duration']) ? intval($_POST['ts-duration']) : '';

	    	if( empty($duration) ) $_POST['ts-error'] = esc_html__('Insert video duration.', 'videofly');

	    	if( isset($_POST['ts-error']) && !empty($_POST['ts-error']) ) return $_POST;

	    }

	    $generalOptions = get_option('videofly_general');

	    $user_post = array(
	        'post_type'     => 'video',
	        'post_title'    => $title,
	        'post_content'  => $content,
	        'post_author'   => $currentUserId,
	        'post_status'   => $generalOptions['post_status_user']
	    );

	    if ( !empty($postId) ) $user_post['ID'] = $postId;

	    $last_id = wp_insert_post($user_post);

	    if ( !empty($tags) ) {
	        wp_set_post_tags($last_id, $tags, false);
	    }

	    if( isset($_POST['ts-add-post']) ){
	    	set_post_thumbnail($last_id, $imgId);
	    	$meta['duration'] = $duration;
	    	update_post_meta($last_id, 'ts-video', $meta);
	    }else{
	    	if( isset($_FILES['ts-upload-img']['name']) && !empty($_FILES['ts-upload-img']['name']) ){

	    		$imgId = media_handle_upload('ts-upload-img', 0);

	    		if ( !is_wp_error($imgId) ) {
	    			set_post_thumbnail($last_id, $imgId);
	    		} else {
	    		    $_POST['ts-error'] = $imgId->get_error_message();
	    		}
	    	}
	    }

	    wp_set_object_terms($last_id, $category, 'videos_categories');

	} elseif ( isset($_POST['ts-delete-post']) ) {

		$userPost = get_post($postId, OBJECT);

		if( !isset($userPost) || intval($userPost->post_author) !== intval($currentUserId) ) return;

		wp_delete_post($postId, true);
	}

	if( isset($_POST['ts-error']) && !empty($_POST['ts-error']) ) return $_POST;

	$varGET = isset($_POST['ts-add-post']) ? 'ts-add-post='. $last_id : (isset($_POST['ts-update-post']) ? 'ts-update-post' : 'ts-delete-post');

	wp_redirect($redirect .'?'. $varGET);
	exit;
}

define('TCH_PostsOnProfilesVersion', '0.1');
define('TCH_PostsOnProfilesVersion_PLUGIN_URL', esc_url( plugin_dir_url( __FILE__ ) ) );

add_action( 'bp_setup_nav', 'ts_add_profileposts_tab', 100 );

function ts_add_profileposts_tab() {

    global $bp;

    bp_core_new_nav_item(
        array(
            'name' => esc_html__('Videos', 'videofly'),
            'slug' => 'posts',
            'screen_function' => 'ts_bp_postsonprofile',
            'default_subnav_slug' => 'My Posts',
            'position' => 25
        )
    );

    bp_core_new_nav_item(
        array(
            'name' => esc_html__('Favorites', 'videofly'),
            'slug' => 'favorites',
            'screen_function' => 'tsBpPostsFaforites',
            'default_subnav_slug' => 'My Favorites Posts',
            'position' => 26
        )
    );

    bp_core_new_nav_item(
        array(
            'name' => esc_html__('Playlists', 'videofly'),
            'slug' => 'playlists',
            'screen_function' => 'tsAddBpPlaylists',
            'default_subnav_slug' => 'My playlists',
            'position' => 27
        )
    );
}

function tsBpPostsFaforites(){
    add_action( 'bp_template_content', 'tsScreenPostsFavorite' );
    bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

// show feedback when 'Posts' tab is clicked
function tsAddBpPlaylists() {
    add_action( 'bp_template_content', 'tsBpPlaylists' );
    bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

function tsBpPlaylists() {

    global $userdata;

    $userId = $userdata->ID;
    $playlists = get_user_meta($userId, 'vdf-playlists', true);
    $postIds = array();

    array_walk_recursive($playlists, function($value, $key) use (&$postIds) {
        if ( $key == 'postId' ) {
            $postIds[] = $value;
        }
    });

    $query = get_posts(array('post__in' => $postIds, 'post_type' => 'video', 'posts_per_page' => -1));
    ?>
        <div class="ts-create-playlist">
            <a class="ts-form-toggle"> <span class="icon-sidebar"></span> <?php esc_html_e('New playlist', 'videofly'); ?></a>
            <div class="ts-new-playlist">
                <input type="text" name="vdf-name-playlist" value="">
                <button class="vdf-save-playlist icon-tick" title="<?php esc_html_e('Save playlist', 'videofly'); ?>"></button>
                <div class="vdf-response"></div>
            </div>
        </div>
    <?php 
    foreach ( $playlists as $playlistId => $playlist ) {

        echo '<div class="vdf-playlist-item">
                <header>
                    <h4>'. $playlist['name'] .'</h4>
                    <button class="vdf-remove-playlist icon-delete" data-action="playlist" data-playlistid="'. $playlistId .'" title="'.esc_html__('Remove playlist', 'videofly') .'"></button>
                    <div class="vdf-response"></div>
                </header>';

                if ( empty($playlist['videos']) || empty($query) ) {
                    echo esc_html__('Playlist is empty.', 'videofly');
                    continue;
                }

            echo '<section class="ts-thumbnail-view cols-by-3 row">';
                foreach ( $playlist['videos'] as $videoPlay ) {

                    foreach ( $query as $videoPost ) {

                        if ( $videoPlay['postId'] !== $videoPost->ID ) continue;

                        $link = add_query_arg('playlist', $playlistId, get_the_permalink($videoPost->ID));

                        ?>
                        <div class="col-lg-4 col-md-4 ts-thumbnails-over">
                            <article class="vdf-video-item">
                                <header>
                                    <div class="image-holder">
                                        <a href="<?php echo esc_url($link); ?>">
                                            <?php echo get_the_post_thumbnail($videoPost->ID); ?>
                                        </a>
                                    </div>
                                </header>
                                <section>
                                    <h3 class="entry-title">
                                        <a href="<?php echo esc_url($link); ?>">
                                            <?php echo esc_attr($videoPost->post_title); ?>
                                        </a>
                                    </h3>
                                    <div class="entry-meta-author">
                                        <a href="<?php echo get_author_posts_url($post->post_author); ?>" class="author">
                                            <i class="icon-user"></i>
                                            <?php echo get_the_author_meta('display_name', $videoPost->post_author); ?>
                                        </a>
                                    </div>
                                </section>
                                    <button class="vdf-remove-fromplaylist" data-playlistid="<?php echo vdf_var_sanitize($playlistId); ?>" data-postid="<?php echo vdf_var_sanitize($videoPost->ID); ?>" data-action="video">
                                        <?php esc_html_e('Remove', 'videofly'); ?>
                                    </button>
                            </article><!-- ./vdf-video-item -->
                        </div> <!-- ./col-lg-3 -->
                        <?php
                    }
                }

                echo
            '</section>
        </div>'; // end vdf-playlist-item
    }
}

function tsScreenPostsFavorite(){
    global $bp;

    $userId = isset($bp->displayed_user->id) ? $bp->displayed_user->id : '';

    if( empty($userId) ) return;

    $favoritesPostIds = get_user_meta($userId, 'favoritePosts', true);
    if( isset($favoritesPostIds) && is_array($favoritesPostIds) && !empty($favoritesPostIds) ){

        $options = array();
        $options['display-mode'] = 'thumbnails';
        $options['elements-per-row'] = 3;
        $options['show-meta'] = 'y';
        $options['behavior'] = 'normal';
        $options['display-title'] = 'title-over-image';

        $args = array(
            'post_type' => 'video',
            'post__in' => $favoritesPostIds
        );

        $query = new WP_Query($args);

        echo '<div class="row">';
        echo LayoutCompilator::list_videos_element($options, $query);
        echo '</div>';
    }

}

// show feedback when 'Posts' tab is clicked
function ts_bp_postsonprofile() {
    add_action( 'bp_template_content', 'ts_profile_screen_posts_show' );
    bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

function ts_profile_screen_posts_show(){
    $userID = bp_displayed_user_id();
    $args = array(); $options = array();
    $args['author'] = $userID;
    $args['post_type'] = 'video';

    $query = new WP_Query($args);
    $options['display-mode'] = 'grid';
    $options['elements-per-row'] = 3;
    $options['show-meta'] = 'y';
    $options['edit'] = true;
    $options['behavior'] = 'normal';
    $options['display-title'] = 'title-below-image';

    if( isset($_GET['ts-delete-post']) || isset($_GET['ts-update-post']) || isset($_GET['ts-add-post']) ){

        if( isset($_GET['ts-add-post']) ){
            $postStatus = get_post_status($_GET['ts-update-post']);
            $title = $postStatus == 'pending' ? esc_html__('Post has been successfully saved. Post is pending.', 'videofly') : esc_html__('Post has been successfully saved.', 'videofly');
        } elseif ( isset($_GET['ts-update-post']) ) {
            $title = esc_html__('Post has been successfully updated.', 'videofly');
        } else {
        	$title = esc_html__('Post has been successfully removed.', 'videofly');
        }

        echo
            '<div class="ts-alert" style="color: #339b62; background-color: #f5fcf8;margin-bottom:40px;">
                <span class="alert-icon"><i class="icon-ok-full"></i></span>
                <div class="right-side">
                    <span class="alert-title"><h3 class="title">'. $title .'</h3></span>
                 </div>
            </div>';
    }

    echo '<div class="row">';
    echo LayoutCompilator::list_videos_element($options, $query);
    echo '</div>';
}

function vdf_login(){

	if( !isset( $_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'], 'user-login' ) ) die();

	$creds = array();
	$creds['user_login']    = isset($_POST['login']) ? sanitize_user($_POST['login']) : '';
	$creds['user_password'] = isset($_POST['password']) ? sanitize_text_field($_POST['password']) : '';
	$creds['remember']      = isset($_POST['remember']) && $_POST['remember'] == 'forever' ? true : false;

	if( empty($creds['user_login']) && empty($creds['user_password']) ){
	    wp_send_json(array('error' => esc_html__('The fields are empty', 'videofly')));
	}

	$user = wp_signon($creds, false);

	if ( is_wp_error($user) ){
		wp_send_json(array('error' => $user->get_error_message()));

	} else {

		wp_set_current_user($user->ID);
    	wp_set_auth_cookie($user->ID);
    	do_action('wp_login', $user->user_login);

    	global $tsLogin;
    	$tsLogin = 'logged';

	   	get_template_part('includes/layout-builder/templates/user');

	   	die();
	}
}
add_action('wp_ajax_vdf_login', 'vdf_login');
add_action('wp_ajax_nopriv_vdf_login', 'vdf_login');

function tsAddFavorite(){

    check_ajax_referer('security', 'ts_security');

    if ( is_user_logged_in() ) {

        $postId = isset($_POST['postId']) && is_numeric($_POST['postId']) ? absint(intval($_POST['postId'])) : '';

        if ( !empty($postId) ) {
            $userId = get_current_user_id();
            $favoritesPostIds = get_user_meta($userId, 'favoritePosts', true);
            $action = isset($_POST['makeAction']) && (trim($_POST['makeAction']) == 'remove' || trim($_POST['makeAction']) == 'add') ? trim($_POST['makeAction']) : 'add';

            if ( $action == 'add' ) {
                if( isset($favoritesPostIds) && is_array($favoritesPostIds) ){
                    if ( !in_array($postId, $favoritesPostIds) ) {
                        $favoritesPostIds[] = $postId;
                    }
                } else {
                    $favoritesPostIds = array();
                    $favoritesPostIds[] = $postId;
                }
            } else {
                if ( isset($favoritesPostIds) && is_array($favoritesPostIds) && in_array($postId, $favoritesPostIds) ) {
                    $key = array_search($postId, $favoritesPostIds);
                    unset($favoritesPostIds[$key]);
                } else {
                    $favoritesPostIds = array();
                }
            }

            $succes = update_user_meta($userId, 'favoritePosts', $favoritesPostIds);

            if( $succes ){
                echo 'succes';
            }else{
                echo 'notsucces';
            }
        }
    }

    die();
}
add_action('wp_ajax_tsAddFavorite', 'tsAddFavorite');
add_action('wp_ajax_nopriv_tsAddFavorite', 'tsAddFavorite');

function vdf_updateRegisterUser()
{
    if ( !isset($_POST['ts_update_user_nonce']) || !wp_verify_nonce($_POST['ts_update_user_nonce'], 'ts_update_user') /*|| !current_user_can('simple_user')*/ ) return;
    $action = isset($_POST['user-action']) && ($_POST['user-action'] == 'update' || $_POST['user-action'] == 'register') ? $_POST['user-action'] : '';

    if( empty($action) || (is_user_logged_in() && $action == 'register') ) return;

    $password = isset($_POST['ts-pass']) && !empty($_POST['ts-pass']) ? sanitize_text_field($_POST['ts-pass']) : '';
    $passwordConfirm = isset($_POST['ts-pass-confirm']) && !empty($_POST['ts-pass-confirm']) ? sanitize_text_field($_POST['ts-pass-confirm']) : '';
    $email = isset($_POST['ts-email']) && is_email($_POST['ts-email']) ? $_POST['ts-email'] : '';
    $nickname = isset($_POST['ts-nick']) ? sanitize_text_field($_POST['ts-nick']) : '';
    $login = isset($_POST['ts-login']) ? sanitize_text_field($_POST['ts-login']) : '';
    $description = isset($_POST['ts-description']) ? sanitize_text_field($_POST['ts-description']) : '';
    $site_url = isset($_POST['ts-url']) ? esc_url_raw($_POST['ts-url']) : '';
    $displayname = isset($_POST['ts-displayname']) ? sanitize_text_field($_POST['ts-displayname']) : '';

    $userdata = array();

    if ( $action == 'register' || (!empty($passwordConfirm) && !empty($password)) ){

    	if ( strlen($password) < 6 || strlen($passwordConfirm) < 6 ) $_POST['error'] = esc_html__('Insert your password min 6 characters.', 'videofly');

    	if ( strlen($login) <= 4 ) $_POST['error'] = esc_html__('Insert your login min 4 characters.', 'videofly');

    	if ( $password == $passwordConfirm ) {
    		$userdata['user_pass'] = $password;
    	} else {
    		$_POST['error'] = esc_html__('Your password and confirmation password do not match.', 'videofly');
    	}
    }

    if ( empty($email) ) $_POST['error'] = esc_html__('Insert a valid email.', 'videofly');
    if ( strlen($displayname) <= 4 ) $_POST['error'] = esc_html__('Insert your name min 4 characters.', 'videofly');

    if ( isset($_POST['error']) && !empty($_POST['error']) ) return $_POST;

    $userdata['user_url'] = $site_url;
    $userdata['user_email'] = $email;
    $userdata['description'] = $description;
    $userdata['display_name'] = $displayname;

    if ( $action == 'update' ) {
    	$userdata['ID'] = get_current_user_id();
    	$user_id = wp_update_user($userdata);
    } else {
    	$userdata['user_login'] = $login;
    	$userdata['user_login'] = $login;
    	$userdata['role'] = 'simple_user';

    	$user_id = wp_insert_user($userdata);
    }

    if ( is_wp_error($user_id) ) {
    	$_POST['error'] = $user_id->get_error_message();
    	return $_POST;
    } else {
    	if ( $action == 'update' ) {
    		$_POST['success'] = esc_html__('Your settings has been updated successfully.', 'videofly');
    		return $_POST;
    	} else {
    		wp_set_auth_cookie($user_id, true);
    		wp_redirect( esc_url( home_url( '/' ) ) );
    		exit;
    	}
    }
}

function vdf_setDataAd(){

    check_ajax_referer('security', 'nonce');

    if ( !isset($_POST['postId']) || !is_numeric(intval($_POST['postId'])) || empty($_POST['keys']) || empty($_POST['event']) ) die();

    $ads = get_option('videofly_theme_advertising');
    $statics = get_option('ad-statistic');
    $statics = !empty($statics) ? $statics : array();
    $event = $_POST['event'];

    if( !isset($ads['adver_video']) || empty($ads['adver_video']) || ($event !== 'clicks' && $event !== 'views') ) die();

    $keys = explode('|', $_POST['keys']);

    if ( count($keys) > 3 || count($keys) == 0 ) die();

    foreach ( $ads['adver_video'] as  $key => $ad ) {

        if ( in_array($key, $keys) ) {

            if ( array_key_exists($key, $statics) ) {

                if ( $ad['countMode'] == 'clicks' && $event == 'clicks' ) {

                    $statics[$key]['clicks'] = intval($statics[$key]['clicks']) + 1;

                }

                if ( $event == 'views' ){

                    $statics[$key]['views'] = intval($statics[$key]['views']) + 1;

                }

            } else {

                $statics[$key] = array(
                    'clicks' => ($event == 'clicks' ? 1 : 0),
                    'views'  => 1
                );
            }
        }

    }

    update_option('ad-statistic', $statics);

    die();
}
add_action('wp_ajax_vdf_setDataAd', 'vdf_setDataAd');
add_action('wp_ajax_nopriv_vdf_setDataAd', 'vdf_setDataAd');

function vdf_addPlaylist(){

    check_ajax_referer('security', 'nonce');

    if ( !is_user_logged_in() ) die();

    if ( empty($_POST['name']) ) wp_send_json(array('error' => esc_html__('Write your playlist name.', 'videofly')));

    global $userdata;

    $userId = $userdata->ID;
    $playlists = get_user_meta($userId, 'vdf-playlists', true);

    $playlists = !empty($playlists) && is_array($playlists) ? $playlists : array();
    $playlistName = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 15) .'-'. $userId;

    if ( array_key_exists($playlistName, $playlists) ) wp_send_json(array('error' => esc_html__('This playlist already exists.', 'videofly')));

    $playlists[$playlistName] = array(
        'name'   => sanitize_text_field($_POST['name']),
        'videos' => array()
    );

    $update = update_user_meta($userId, 'vdf-playlists', $playlists);

    if ( $update ) {
        $send = array('success' => esc_html__('Playlist saved successfully.', 'videofly'), 'playlistId' => $playlistName, 'namePlaylist' => sanitize_text_field($_POST['name']));
    } else {
        $send = array('error' => esc_html__('An error occurred.', 'videofly'));
    }

    wp_send_json($send);
}
add_action('wp_ajax_vdf_addPlaylist', 'vdf_addPlaylist');
add_action('wp_ajax_nopriv_vdf_addPlaylist', 'vdf_addPlaylist');

function vdf_getPlaylist($videoMeta) {

    if ( !is_user_logged_in() ) return;

    if ( !isset($videoMeta['type']) || $videoMeta['type'] == 'embed' ) return;

    if (  strpos($videoMeta['video'], 'youtube') == false && strpos($videoMeta['video'], 'vimeo') == false && $videoMeta['type'] != 'upload' ) return;

    global $userdata, $post;

    $userId = $userdata->ID;
    $playlists = get_user_meta($userId, 'vdf-playlists', true);

    if ( empty($playlists) ) return '<span>'. esc_html__('No playlists.', 'videofly') .'</span>';

    $ul = '<ul class="vdf-add-playlist" data-postid="'. $post->ID .'">';

    foreach ( $playlists as $keyPlaylist => $playlist ) {
        $icon = '';
        $is_in_playlist = '';
        foreach ( $playlist['videos'] as $video ) {
            if ( intval($video['postId']) == intval($post->ID) ) {
                $icon = '<i class="icon-tick"></i>';
                $is_in_playlist = ' class="in-playlist"';
                break;
            }
        }
        $ul .= '<li data-name="'. $keyPlaylist .'" '. $is_in_playlist .'>'. $icon . $playlist['name'] .'</li>';
    }

    $ul .= '</ul><span class="vdf-response"></span>';

    return $ul;
}

function vdf_addToPlaylist()
{
    check_ajax_referer('security', 'nonce');

    if ( !is_user_logged_in() ) die();

    if ( empty($_POST['name']) || empty($_POST['postId']) ) wp_send_json(array('error' => esc_html__('Erorr.', 'videofly')));

    global $userdata;

    $userId = $userdata->ID;
    $playlistName = sanitize_text_field($_POST['name']);
    $playlists = get_user_meta($userId, 'vdf-playlists', true);
    $videoMeta = get_post_meta($_POST['postId'], 'ts-video', true);

    if ( !isset($playlists[$playlistName]) ) wp_send_json(array('error' => esc_html__('You don\'t have this playlist.', 'videofly')));

    foreach ( $playlists[$playlistName]['videos'] as $videoPlaylist ) {
        if ( in_array($videoMeta['video'], $videoPlaylist) ) wp_send_json(array('success' => esc_html__('This video already exist.', 'videofly')));
    }

    $playlists[$playlistName]['videos'][] = array(
        'video'  => $videoMeta['video'],
        'postId' => intval($_POST['postId']),
    );

    update_user_meta($userId, 'vdf-playlists', $playlists);

    wp_send_json(array('success' => esc_html__('Video added.', 'videofly')));
}
add_action('wp_ajax_vdf_addToPlaylist', 'vdf_addToPlaylist');
add_action('wp_ajax_nopriv_vdf_addToPlaylist', 'vdf_addToPlaylist');


function vdf_advertisingVideo($advertisings, $post, $videoMeta, $playlist = '')
{
    if( !isset($advertisings['adver_video'])|| empty($videoMeta) || !is_array($videoMeta) ) return;

    $i = 1;
    $tags = array();
    $categories = array();
    $videoIds = array();

    if ( !empty($playlist) ) {

        $playlistInfo = explode('-', $playlist);

        $playlistId = isset($playlistInfo[0]) ? sanitize_text_field($playlistInfo[0]) : '';
        $userId = isset($playlistInfo[1]) ? intval($playlistInfo[1]) : '';

        if ( !empty($userId) && !empty($playlistId) ) {
            $userPlaylists = get_user_meta($userId, 'vdf-playlists', true);

            if ( array_key_exists($playlist, $userPlaylists) ) {

                $postIds = array();

                array_walk_recursive($userPlaylists[$playlist], function($value, $key) use (&$postIds, &$post) {

                    if ( $key == 'postId' ) {
                        $postIds[] = $value;
                    }
                });

                $currentIndex = array_search($post->ID, $postIds);

                unset($postIds[$currentIndex]);

                $query = !empty($postIds) ? get_posts(array('post__in' => $postIds, 'post_type' => 'video', 'orderby' => 'post__in', 'posts_per_page' => -1)) : '';

                $playlistVideos = array();

                if ( !empty($query) ) {
                    foreach ( $query as $videoPost ) {
                        $imgInfo = wp_get_attachment_image_src(get_post_thumbnail_id($videoPost->ID), 'thumbnail');
                        $thumbImg = $imgInfo[0];

                        $playlistVideos[] = array(
                            'title'        => str_replace('"', '\"', stripslashes($videoPost->post_title)),
                            'description'  => get_the_date('', $videoPost->ID),
                            'link'         => add_query_arg('playlist', $playlist, get_the_permalink($videoPost->ID)),
                            'thumbImg'     => $thumbImg,
                            'index'        => array_search($videoPost->ID, $postIds)
                        );
                    }
                }
            }
        }
    }

    $adStorage = array(
        'video' => array(),
        'text'  => array(),
        'image' => array()
    );

    $hasAd = false;
    $thumbUrl = vdf_resize('single', wp_get_attachment_url(get_post_thumbnail_id($post->ID)));
    $statics = get_option('ad-statistic');
    $statics = !empty($statics) ? $statics : array();

    foreach ( $advertisings['adver_video'] as $key => $ad ) {

        $excludeIds = !empty($ad['excludeIds']) ? explode(',', $ad['excludeIds']) : array();

        if ( in_array($post->ID, $excludeIds) || $ad['active'] == 'n' ) continue;

        if ( array_key_exists($key, $statics) ) {

            if ( intval($statics[$key][$ad['countMode']]) >= intval($ad['max'. $ad['countMode']]) && intval($ad['max'. $ad['countMode']]) > 0 ) continue;

        }

        /*** Verify if this post has ad. ***/
        if ( $ad['criterion'] == 'categories' ) {

            if ( empty($categories) ) {

                $terms = wp_get_object_terms($post->ID, 'videos_categories');

                if ( !empty($terms) && !is_wp_error($terms) ) {

                    foreach ( $terms as $category ) {
                        $categories[] = $category->slug;
                    }

                } else {
                    $categories = array('no-category');
                    continue;
                }
            }

            $adCategories = !empty($ad['category']) ? explode(',', $ad['category']) : array();

            $intersect = array_intersect($categories, $adCategories);

            if ( !empty($intersect) ) $hasAd = true;

        } elseif ( $ad['criterion'] == 'tags' ) {

            if ( empty($categories) ) {

                $tagsPost = wp_get_post_tags($post->ID);

                if ( !empty($tagsPost) && !is_wp_error($tagsPost) ) {

                    foreach ( $tagsPost as $tag ) {
                        $tags[] = $tag->slug;
                    }

                } else {

                    $tags = array('no-tags');
                    continue;
                }
            }

            $adTags = !empty($ad['tags']) ? explode(',', $ad['tags']) : array();

            $intersect = array_intersect($tags, $adTags);

            if ( !empty($intersect) ) $hasAd = true;

        } elseif ( $ad['criterion'] == 'videoids' ) {

            $videoIds = !empty($ad['videoids']) ? explode(',', $ad['videoids']) : array();

            if ( in_array($post->ID, $adIds) ) $hasAd = true;

        }

        if ( $hasAd ) {

            /*** Group options in one array for retired random later. ***/
            if ( $ad['type'] == 'video' ) {

                $adStorage['video'][$i] = array(
                    'videoAdShow'     => 'yes',
                    'videoAdGotoLink' => $ad['link'],
                    'mp4AD'           => $ad['videoUrl'],
                    'skip'            => $ad['skip'] == 'y' ? true : false
                );

            } elseif ( $ad['type'] == 'image' ) {

                $adStorage['image'][$i] = array(
                    'popupAdShow'      => 'yes',
                    'popupAdGoToLink'  => $ad['link'],
                    'popupImg'         => $ad['imageUrl'],
                    'popupAdStartTime' => $ad['start'],
                    'popupAdEndTime'   => $ad['end']
                );

            } else {

                $adStorage['text'][$i] = array(
                    'textAdShow'      => 'yes',
                    'textAd'          => nl2br($ad['text']),
                    'textAdStartTime' => $ad['start'],
                    'textAdEndTime'   => $ad['end'],
                    'textAdGoToLink'  => $ad['link']
                );

            }

            $adStorage[$ad['type']][$i]['key'] = $key;
        }

        $i++;

    }

    $video = array();
    $video['title'] = sanitize_text_field($post->post_title);
    $video['thumbImg'] = $thumbUrl;
    $video['description'] = sanitize_text_field($post->post_content);

    if ( isset($currentIndex) ) {
        $video['index'] = $currentIndex;
    }

    if ( isset($playlistVideos) ) {
        $video['link'] = add_query_arg('playlist', $playlist, get_the_permalink($post->ID));
    }

    $videoType = '';

    /**** Check video type for videoplayer. This player support only video from vimeo, youtube and uploaded. ***/
    if ( $videoMeta['type'] == 'url' ) {

        if ( strpos($videoMeta['video'], 'youtube') > 0 ) {
            $videoType = 'youtube';
        } elseif ( strpos($videoMeta['video'], 'vimeo') > 0 ) {
            $videoType = 'vimeo';
        } else {
            return wp_oembed_get($videoMeta['video']);
        }

    } elseif ( $videoMeta['type'] == 'upload' ) {

        $videoType = 'HTML5';

    } else {

        return $videoMeta['video'];

    }

    $video['videoType'] = $videoType;

    /*** Set video url or id ( from youtube or vimeo ) for video player ***/
    if ( $videoType == 'youtube' ) {

        parse_str(parse_url($videoMeta['video'], PHP_URL_QUERY), $varsUrl);
        $video['youtubeID'] = $varsUrl['v'];

    } elseif ( $videoType == 'vimeo' ) {

        $result = preg_match('/(\d+)/', $videoMeta['video'], $matches);
        if ( $result ) {
            $video['vimeoID'] = $matches[0];
        }

    } else {

        $video['mp4'] = $videoMeta['video'];

    }

    $skip = false;
    $keys = array();

    if ( !empty($adStorage['video']) ) {

        shuffle($adStorage['video']);

        $video = array_merge($video, $adStorage['video'][0]);
        $skip = $adStorage['video'][0]['skip'];
        $keys[] = $adStorage['video'][0]['key'];
    }

    if ( !empty($adStorage['image']) ) {

        shuffle($adStorage['image']);

        $video = array_merge($video, $adStorage['image'][0]);
        $keys[] = $adStorage['image'][0]['key'];
    }

    if ( !empty($adStorage['text']) ) {

        shuffle($adStorage['text']);

        $video = array_merge($video, $adStorage['text'][0]);
        $keys[] = $adStorage['text'][0]['key'];
    }

    /*** Create general options videoplayer ***/

    $player = array();

    $player['logoShow'] = 'No';
    $player['logoPath'] = '';
    $player['embedShow'] = 'Yes';
    $player['allowSkip'] = $skip;
    $player['responsive'] = true;
    $player['youtubeSkin'] = 'light';
    $player['youtubeColor'] = 'red';
    $player['playlist'] = isset($playlistVideos) ? 'Right playlist' : 'Off';
    $player['onFinish'] = isset($playlistVideos) && count($playlistVideos) > 1 ? 'Play next video' : 'Stop video';
    $player['embedCodeW'] = '1000';
    $player['embedCodeH'] = '520';
    $player['posterImg'] = $thumbUrl;
    $player['autoplay'] = isset($playlistVideos) ? true : false;
    //$player['videoPlayerWidth'] = 746;
    //$player['videoPlayerHeight'] = 420;
    $player['playVideosRandomly'] = 'No';
    $player['loadRandomVideoOnStart'] = 'No';

    if ( !isset($video['popupImg']) ) $video['popupImg'] = '';

    $keys = !empty($keys) ?  ' data-keys="'. implode('|', $keys) .'" data-postid="'. $post->ID .'"' : '';

    $player['videos'] = array($video);

    if ( isset($playlistVideos) && !empty($playlistVideos) ) {
        $player['videos'] = array_merge($player['videos'], $playlistVideos);
    }

    return '<div class="vdf-video-player"'. $keys .'><span style="display:none;" class="vdf-data-json">'. json_encode($player) .'</span></div>';

}

function vdf_actionsPlaylist(){

    check_ajax_referer('security', 'nonce');

    if ( !isset($_POST['playlistId']) || empty($_POST['playlistId']) || !isset($_POST['todo']) || ($_POST['todo'] !== 'playlist' && $_POST['todo'] !== 'video') ) die();

    global $userdata;

    $userId = $userdata->ID;
    $playlists = get_user_meta($userId, 'vdf-playlists', true);
    $playlistInfo = explode('-', $_POST['playlistId']);
    $playlistUser = $playlistInfo[1];

    if ( !array_key_exists($_POST['playlistId'], $playlists) || intval($userId) !== intval($playlistUser) ) die();

    if ( $_POST['todo'] == 'playlist' ) {
        unset($playlists[$_POST['playlistId']]);
    } else {

        foreach ( $playlists[$_POST['playlistId']]['videos'] as $key => $video ) {

            if ( intval($video['postId']) == intval($_POST['postId']) ) {
                unset($playlists[$_POST['playlistId']]['videos'][$key]);
            }
        }
    }

    $update = update_user_meta($userId, 'vdf-playlists', $playlists);

    if ( $update ) {
        $send = array('success' => esc_html__('Playlist removed successfully.', 'videofly'));
    } else {
        $send = array('error' => esc_html__('An error occurred.', 'videofly'));
    }

    wp_send_json($send);
}
add_action('wp_ajax_vdf_actionsPlaylist', 'vdf_actionsPlaylist');
add_action('wp_ajax_nopriv_vdf_actionsPlaylist', 'vdf_actionsPlaylist');

function ts_video_image_callback(){
    check_ajax_referer('video-image', 'nonce');
    $post_id = (isset($_POST['post_id']) && (int)$_POST['post_id'] !== 0) ? (int)$_POST['post_id'] : NULL;
    $video_url = (isset($_POST['link'])) ? esc_url($_POST['link']) : '';

    if( isset($post_id) && $video_url !== '' ):

        $video_id = '';
        if( strlen(trim($video_url)) > 0 ):

            if( strpos($video_url, 'vimeo') > 0 ) {

                global $wp_filesystem;

                if( empty($wp_filesystem) ) {
                    require_once( ABSPATH .'/wp-admin/includes/file.php' );
                    WP_Filesystem();
                }

                $video_id = str_replace(array('http://vimeo.com/', 'https://vimeo.com/'), '', $video_url);
                $contents = $wp_filesystem->get_contents("http://vimeo.com/api/v2/video/$video_id.php");

                $hash = unserialize($contents);

                $video_thumbnail = $hash[0]['thumbnail_large'];

            }elseif( strpos($video_url, 'youtube' ) > 0 ) {
                parse_str(parse_url($video_url, PHP_URL_QUERY));
                $video_id = $v;
                $video_thumbnail = 'http://img.youtube.com/vi/' . $video_id . '/maxresdefault.jpg';
                $headers = get_headers($video_thumbnail);
                if (substr($headers[0], 9, 3) == '404') {
                    $video_thumbnail = 'http://img.youtube.com/vi/' . $video_id . '/0.jpg';
                }
            }elseif( strpos($video_url, 'dailymotion' ) > 0 ) {
                $video_id = strtok(wp_basename($video_url), '_');
                $video_thumbnail =  "https://api.dailymotion.com/video/$video_id?fields=thumbnail_large_url";
                $resp = wp_remote_get($video_thumbnail, array('sslverify' => false));
                $response = wp_remote_retrieve_body($resp);
                $result = json_decode($response);
                $video_thumbnail = $result->thumbnail_large_url;
            } else {
                return;
            }
        else:
           return;
        endif;
        delete_post_meta( $post_id, '_thumbnail_id' );
        media_sideload_image($video_thumbnail, $post_id, get_the_title($post_id));
        $attachments = get_posts(
            array(
                'post_type'   =>'attachment',
                'numberposts' => 1,
                'order'       => 'DESC'
            ));

        $attachment = isset($attachments[0]) ? $attachments[0] : '';

        set_post_thumbnail($post_id, $attachment->ID);
        $jsonEncode = array('url' => wp_get_attachment_url($attachment->ID), 'attachment_id' => $attachment->ID);
        echo json_encode($jsonEncode);

    endif;

    die();
}
add_action('wp_ajax_ts_video_image', 'ts_video_image_callback');

function vdf_sendEmbed(){

    if ( !is_user_logged_in() ) die();

    check_ajax_referer('security', 'nonce');

    $message = isset($_POST['message']) ? sanitize_text_field($_POST['message']) : '';
    $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';

    if ( empty($name) || empty($message) || !is_email($email) ) die();

    global $userdata;

    $headers = 'From: '. esc_attr($name) .' <'. $userdata->user_email .'>';
    $send = wp_mail($email, esc_html__('Message from: ', 'videofly') . $name, $message, $headers);

    $response = $send ? array('success' => esc_html__('Mail sent.', 'videofly')) : array('error' => esc_html__('An error occurred.', 'videofly'));

    wp_send_json($response);
}
add_action('wp_ajax_vdf_sendEmbed', 'vdf_sendEmbed');
add_action('wp_ajax_nopriv_vdf_sendEmbed', 'vdf_sendEmbed');
?>