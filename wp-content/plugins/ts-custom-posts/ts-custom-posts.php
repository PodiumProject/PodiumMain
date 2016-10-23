<?php
/**
 * Plugin Name: Touchsize Custom Posts.
 * Plugin URI: http://touchsize.com/
 * Description: This plugin adds custom posts types.
 * Version: 1.0.5.
 * Author: Touchsize
 * Author URI: http://touchsize.com/
 * Text Domain:
 * Domain Path:
 * License: GPL2
 */

$theme = wp_get_theme();
define('THEMENAME', strtolower($theme->Name));

function ts_custom_posts_active() {

    $custom_posts = get_option('theme-custom-posts');

    if( isset($custom_posts) && is_array($custom_posts) && !empty($custom_posts) ){
        foreach($custom_posts as $custom_post){
            add_action( 'init', 'ts_'. $custom_post );
            if( $custom_post !== 'slider' ){
                add_action( 'init', 'ts_taxonomy_'. $custom_post, 0 );
            }
            add_filter( 'post_messages', 'ts_'. $custom_post .'_messages' );
        }
    }
}
register_activation_hook( __FILE__, 'ts_custom_posts_active' );


$custom_posts = get_option('theme-custom-posts');

if( isset($custom_posts) && is_array($custom_posts) && !empty($custom_posts) ){
    foreach($custom_posts as $custom_post){
        add_action( 'init', 'ts_'. $custom_post );
        if( $custom_post !== 'slider' ){
            add_action( 'init', 'ts_taxonomy_'. $custom_post, 0 );
        }
        add_filter( 'post_messages', 'ts_'. $custom_post .'_messages' );
    }
}

/**
 * Gallery
 */
function ts_gallery() {

    $slug = get_option(THEMENAME . '_general');
    $slug = (isset($slug['slug_gallery'])) ? $slug['slug_gallery'] : 'gallery';

    $labels = array(
        'name'               => __('Gallery','ts-custom-posts'),
        'singular_name'      => __('Gallery','ts-custom-posts'),
        'add_new'            => __('Add New','ts-custom-posts'),
        'add_new_item'       => __('Add New Gallery','ts-custom-posts'),
        'edit_item'          => __('Edit Gallery','ts-custom-posts'),
        'new_item'           => __('New Gallery','ts-custom-posts'),
        'all_items'          => __('All Galleries','ts-custom-posts'),
        'view_item'          => __('View Gallery','ts-custom-posts'),
        'search_items'       => __('Search Galleries','ts-custom-posts'),
        'not_found'          => __('No galleries found','ts-custom-posts'),
        'not_found_in_trash' => __('No galleries found in Trash','ts-custom-posts'),
        'parent_item_colon'  => '',
        'menu_name'          => __('Galleries','ts-custom-posts'),
    );

    $args = array(
        'labels'   => $labels,
        'public'   => true,
        'supports' => array('title', 'thumbnail', 'author', 'editor', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes'),
        'menu_icon' => plugins_url('ts-custom-posts/images/custom.gallery.png'),
        'taxonomies' => array('post_tag', 'gallery_categories'),
        'rewrite' => array('slug' => $slug)
    );

    register_post_type( 'ts-gallery', $args );
}

function ts_taxonomy_gallery(){
    $slug = get_option(THEMENAME .'_general');
    $slug = (isset($slug['slug_gallery_taxonomy'])) ? $slug['slug_gallery_taxonomy'] : 'gallery-category';

    $labels = array(
        'name' => __( 'Category','ts-custom-posts' ),
        'singular_name' => __( 'Gallery','ts-custom-posts' ),
        'search_items' =>  __( 'Search Galleries','ts-custom-posts' ),
        'popular_items' => __( 'Popular Galleries','ts-custom-posts' ),
        'all_items' => __( 'All Galleries','ts-custom-posts' ),
        'parent_item' => __( 'Parent Galleries','ts-custom-posts' ),
        'parent_item_colon' => __( 'Parent Galleries:','ts-custom-posts' ),
        'edit_item' => __( 'Edit Gallery','ts-custom-posts' ),
        'update_item' => __( 'Update Gallery','ts-custom-posts' ),
        'add_new_item' => __( 'Add New Galleries','ts-custom-posts' ),
        'new_item_name' => __( 'New Gallery Name','ts-custom-posts' ),
      );

      register_taxonomy('gallery_categories', array('gallery_categories'), array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => $slug ),

      ));
}

function ts_gallery_messages( $messages ) {
  global $post, $post_ID;

  $messages['ts_gallery'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Gallery updated. <a href="%s">View gallery</a>','ts-custom-posts'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.','ts-custom-posts'),
    3 => __('Custom field deleted.','ts-custom-posts'),
    4 => __('Gallery updated.','ts-custom-posts'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('Gallery restored to revision from %s','ts-custom-posts'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Gallery published. <a href="%s">View gallery</a>','ts-custom-posts'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Gallery saved.','ts-custom-posts'),
    8 => sprintf( __('Gallery submitted. <a target="_blank" href="%s">Preview gallery</a>','ts-custom-posts'), esc_url( add_query_arg( 'preview', 'true', esc_url(get_permalink($post_ID)) ) ) ),
    9 => sprintf( __('Gallery scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview gallery</a>', 'Gallery'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i','ts-custom-posts' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Gallery draft updated. <a target="_blank" href="%s">Preview gallery</a>','ts-custom-posts'), esc_url( add_query_arg( 'preview', 'true', esc_url(get_permalink($post_ID)) ) ) ),
  );

  return $messages;
}

/**
 * User_image
 */
function ts_user_image() {

    $slug = get_option(THEMENAME . '_general');
    $slug = (isset($slug['slug_user_image'])) ? $slug['slug_user_image'] : 'user-image';

    $labels = array(
        'name'               => __('User Image','ts-custom-posts'),
        'singular_name'      => __('User Image','ts-custom-posts'),
        'add_new'            => __('Add New','ts-custom-posts'),
        'add_new_item'       => __('Add New User Image','ts-custom-posts'),
        'edit_item'          => __('Edit User Image','ts-custom-posts'),
        'new_item'           => __('New User Image','ts-custom-posts'),
        'all_items'          => __('All User Image','ts-custom-posts'),
        'view_item'          => __('View User Image','ts-custom-posts'),
        'search_items'       => __('Search User Image','ts-custom-posts'),
        'not_found'          => __('No User Image found','ts-custom-posts'),
        'not_found_in_trash' => __('No User Image found in Trash','ts-custom-posts'),
        'parent_item_colon'  => '',
        'menu_name'          => __('User Image','ts-custom-posts'),
    );

    $args = array(
        'labels'   => $labels,
        'public'   => true,
        'supports' => array('title', 'thumbnail', 'author', 'editor'),
        'menu_icon' => plugins_url('ts-custom-posts/images/user-image.png'),
        'taxonomies' => array('post_tag', 'user_image_categories'),
        'rewrite' => array('slug' => $slug)
    );

    register_post_type( 'user_image', $args );
}

function ts_taxonomy_user_image(){
    $slug = get_option(THEMENAME .'_general');
    $slug = (isset($slug['user_image_taxonomy'])) ? $slug['user_image_taxonomy'] : 'user-image-category';

    $labels = array(
        'name' => __( 'Category','ts-custom-posts' ),
        'singular_name' => __( 'User Image','ts-custom-posts' ),
        'search_items' =>  __( 'Search User Image','ts-custom-posts' ),
        'popular_items' => __( 'Popular User Image','ts-custom-posts' ),
        'all_items' => __( 'All User Image','ts-custom-posts' ),
        'parent_item' => __( 'Parent User Image','ts-custom-posts' ),
        'parent_item_colon' => __( 'Parent User Image:','ts-custom-posts' ),
        'edit_item' => __( 'Edit User Image','ts-custom-posts' ),
        'update_item' => __( 'Update User Image','ts-custom-posts' ),
        'add_new_item' => __( 'Add New User Image','ts-custom-posts' ),
        'new_item_name' => __( 'New User Image Name','ts-custom-posts' ),
      );
      register_taxonomy('user_image_categories', array('user_image_categories'), array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => $slug ),

      ));
}

function ts_user_image_messages( $messages ) {
  global $post, $post_ID;

  $messages['user_image'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('User Image updated. <a href="%s">View User Image</a>','ts-custom-posts'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.','ts-custom-posts'),
    3 => __('Custom field deleted.','ts-custom-posts'),
    4 => __('User Image updated.','ts-custom-posts'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('User Image restored to revision from %s','ts-custom-posts'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('User Image published. <a href="%s">View User Image</a>','ts-custom-posts'), esc_url( get_permalink($post_ID) ) ),
    7 => __('User Image saved.','ts-custom-posts'),
    8 => sprintf( __('User Image submitted. <a target="_blank" href="%s">Preview User Image</a>','ts-custom-posts'), esc_url( add_query_arg( 'preview', 'true', esc_url(get_permalink($post_ID)) ) ) ),
    9 => sprintf( __('User Image scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview User Image</a>', 'Gallery'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i','ts-custom-posts' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('User Image draft updated. <a target="_blank" href="%s">Preview User Image</a>','ts-custom-posts'), esc_url( add_query_arg( 'preview', 'true', esc_url(get_permalink($post_ID)) ) ) ),
  );

  return $messages;
}

/**
 * Event
 */
function ts_event() {
    $slug = get_option(THEMENAME .'_general');
    $slug = (isset($slug['slug_event'])) ? $slug['slug_event'] : 'event';

    $labels = array(
        'name'               => __('Events','ts-custom-posts'),
        'singular_name'      => __('Event','ts-custom-posts'),
        'add_new'            => __('Add New','ts-custom-posts'),
        'add_new_item'       => __('Add New Event','ts-custom-posts'),
        'edit_item'          => __('Edit Event','ts-custom-posts'),
        'new_item'           => __('New Event','ts-custom-posts'),
        'all_items'          => __('All Events','ts-custom-posts'),
        'view_item'          => __('View Event','ts-custom-posts'),
        'search_items'       => __('Search Events','ts-custom-posts'),
        'not_found'          => __('No events found','ts-custom-posts'),
        'not_found_in_trash' => __('No events found in Trash','ts-custom-posts'),
        'parent_item_colon'  => '',
        'menu_name'          => __('Events','ts-custom-posts'),
    );

    $args = array(
        'labels'   => $labels,
        'public'   => true,
        'supports' => array('title', 'thumbnail', 'author', 'editor', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes'),
        'menu_icon' => plugins_url('ts-custom-posts/images/custom.event.png'),
        'taxonomies' => array('post_tag', 'event_categories'),
        'rewrite' => array('slug' => $slug)
    );

    register_post_type( 'event', $args );
}

function ts_taxonomy_event(){
    $slug = get_option(THEMENAME .'_general');
    $slug = (isset($slug['slug_event_taxonomy'])) ? $slug['slug_event_taxonomy'] : 'event-taxonomy';

    $labels = array(
        'name' => __( 'Category','ts-custom-posts' ),
        'singular_name' => __( 'Event','ts-custom-posts' ),
        'search_items' =>  __( 'Search Events','ts-custom-posts' ),
        'popular_items' => __( 'Popular Events','ts-custom-posts' ),
        'all_items' => __( 'All Events','ts-custom-posts' ),
        'parent_item' => __( 'Parent Events','ts-custom-posts' ),
        'parent_item_colon' => __( 'Parent Events:','ts-custom-posts' ),
        'edit_item' => __( 'Edit Event','ts-custom-posts' ),
        'update_item' => __( 'Update Event','ts-custom-posts' ),
        'add_new_item' => __( 'Add New Events','ts-custom-posts' ),
        'new_item_name' => __( 'New Event Name','ts-custom-posts' ),
      );
      register_taxonomy('event_categories', array('event_categories'), array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => $slug ),

      ));
}

function ts_event_messages( $messages ) {
  global $post, $post_ID;

  $messages['ts_event'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Event updated. <a href="%s">View event</a>','ts-custom-posts'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.','ts-custom-posts'),
    3 => __('Custom field deleted.','ts-custom-posts'),
    4 => __('Event updated.','ts-custom-posts'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('Event restored to revision from %s','ts-custom-posts'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Event published. <a href="%s">View event</a>','ts-custom-posts'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Event saved.','ts-custom-posts'),
    8 => sprintf( __('Event submitted. <a target="_blank" href="%s">Preview event</a>','ts-custom-posts'), esc_url( add_query_arg( 'preview', 'true', esc_url(get_permalink($post_ID)) ) ) ),
    9 => sprintf( __('Event scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview event</a>', 'event'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i','ts-custom-posts' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Event draft updated. <a target="_blank" href="%s">Preview event</a>','ts-custom-posts'), esc_url( add_query_arg( 'preview', 'true', esc_url(get_permalink($post_ID)) ) ) ),
  );

  return $messages;
}

/**
 * Sliders
 */
function ts_slider() {
	$labels = array(
		'name'               => __('Sliders','ts-custom-posts'),
		'singular_name'      => __('Slider','ts-custom-posts'),
		'add_new'            => __('Add New','ts-custom-posts'),
		'add_new_item'       => __('Add New Slider','ts-custom-posts'),
		'edit_item'          => __('Edit Slider','ts-custom-posts'),
		'new_item'           => __('New Slider','ts-custom-posts'),
		'all_items'          => __('All Sliders','ts-custom-posts'),
		'view_item'          => __('View Slider','ts-custom-posts'),
		'search_items'       => __('Search Sliders','ts-custom-posts'),
		'not_found'          => __('No sliders found','ts-custom-posts'),
		'not_found_in_trash' => __('No sliders found in Trash','ts-custom-posts'),
		'parent_item_colon'  => '',
		'menu_name'          => __('Sliders','ts-custom-posts'),
	);

	$args = array(
		'labels'       => $labels,
		'public'       => true,
		'supports'     => array('title'),
		'menu_icon'    => plugins_url('ts-custom-posts/images/custom.slideshow.png')
	);

	register_post_type( 'ts_slider', $args );
}

function ts_slider_messages( $messages ) {
  global $post, $post_ID;

  $messages['ts_slider'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Slider updated. <a href="%s">View slider</a>','ts-custom-posts'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.','ts-custom-posts'),
    3 => __('Custom field deleted.','ts-custom-posts'),
    4 => __('Slider updated.','ts-custom-posts'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('Slider restored to revision from %s','ts-custom-posts'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Slider published. <a href="%s">View slider</a>','ts-custom-posts'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Slider saved.','ts-custom-posts'),
    8 => sprintf( __('Slider submitted. <a target="_blank" href="%s">Preview slider</a>','ts-custom-posts'), esc_url( add_query_arg( 'preview', 'true', esc_url(get_permalink($post_ID)) ) ) ),
    9 => sprintf( __('Slider scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview slider</a>', 'slider'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i','ts-custom-posts' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Slider draft updated. <a target="_blank" href="%s">Preview slider</a>','ts-custom-posts'), esc_url( add_query_arg( 'preview', 'true', esc_url(get_permalink($post_ID)) ) ) ),
  );

  return $messages;
}

/**
 * Video
 */
function ts_taxonomy_video(){
    $slug = get_option(THEMENAME .'_general');
    $slug = (isset($slug['slug_video_taxonomy'])) ? $slug['slug_video_taxonomy'] : 'videos_categories';

	$labels = array(
	    'name' => __( 'Category','ts-custom-posts' ),
	    'singular_name' => __( 'Video','ts-custom-posts' ),
	    'search_items' =>  __( 'Search Videos','ts-custom-posts' ),
	    'popular_items' => __( 'Popular Videos','ts-custom-posts' ),
	    'all_items' => __( 'All Videos','ts-custom-posts' ),
	    'parent_item' => __( 'Parent Videos','ts-custom-posts' ),
	    'parent_item_colon' => __( 'Parent Videos:','ts-custom-posts' ),
	    'edit_item' => __( 'Edit Videos','ts-custom-posts' ),
	    'update_item' => __( 'Update Videos','ts-custom-posts' ),
	    'add_new_item' => __( 'Add New Videos','ts-custom-posts' ),
	    'new_item_name' => __( 'New Videos Name','ts-custom-posts' ),
	  );
	  register_taxonomy('videos_categories', array('videos_categories'), array(
	    'hierarchical' => true,
	    'labels' => $labels,
	    'show_ui' => true,
	    'query_var' => true,
	    'rewrite' => array( 'slug' => $slug ),
	  ));
}

function ts_video()
{
    $slug = get_option(THEMENAME .'_general');
    $slug = (isset($slug['slug_video'])) ? $slug['slug_video'] : 'video';

	$labels = array(
		'name'               => __('Videos','ts-custom-posts'),
		'singular_name'      => __('Video','ts-custom-posts'),
		'add_new'            => __('New Video','ts-custom-posts'),
		'add_new_item'       => __('Add New Video','ts-custom-posts'),
		'edit_item'          => __('Edit Video','ts-custom-posts'),
		'new_item'           => __('New Video','ts-custom-posts'),
		'all_items'          => __('All Videos','ts-custom-posts'),
		'view_item'          => __('View Video','ts-custom-posts'),
		'search_items'       => __('Search Videos','ts-custom-posts'),
		'not_found'          => __('No video found','ts-custom-posts'),
		'not_found_in_trash' => __('No video found in Trash','ts-custom-posts'),
		'parent_item_colon'  => '',
		'menu_name'          => __('Videos','ts-custom-posts')
	);

	$args = array(
		'labels'   => $labels,
		'map_meta_cap' => true,
		'public'   => true,
		'supports' => array('title', 'thumbnail', 'author', 'editor', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes'),
		'menu_icon' => plugins_url('ts-custom-posts/images/custom.video.png'),
		'taxonomies' => array('videos_categories', 'post_tag'),
        'rewrite' => array( 'slug' => $slug ),
	);

	register_post_type( 'video', $args );
}

function ts_video_messages( $messages )
{
  global $post, $post_ID;

  $messages['video'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Information about video updated. <a href="%s">View video</a>','ts-custom-posts'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.','ts-custom-posts'),
    3 => __('Custom field deleted.','ts-custom-posts'),
    4 => __('Video updated.','ts-custom-posts'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('Videos restored to revision from %s','ts-custom-posts'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Video published. <a href="%s">View video</a>','ts-custom-posts'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Video saved.','ts-custom-posts'),
    8 => sprintf( __('Video submitted. <a target="_blank" href="%s">Preview Video</a>','ts-custom-posts'), esc_url( add_query_arg( 'preview', 'true', esc_url(get_permalink($post_ID)) ) ) ),
    9 => sprintf( __('Video scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview video</a>','ts-custom-posts'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i','ts-custom-posts'), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Video draft updated. <a target="_blank" href="%s">Preview video</a>','ts-custom-posts'), esc_url( add_query_arg( 'preview', 'true', esc_url(get_permalink($post_ID)) ) ) ),
  );

  return $messages;
}

/**
 * Teams
 */
function ts_teams()
{
    $slug = get_option(THEMENAME .'_general');
    $slug = (isset($slug['slug_teams']) && !empty($slug['slug_teams'])) ? $slug['slug_teams'] : 'ts-teams';

	$labels = array(
		'name'               => __('Teams','ts-custom-posts'),
		'singular_name'      => __('Teams','ts-custom-posts'),
		'add_new'            => __('New Member','ts-custom-posts'),
		'add_new_item'       => __('Add New Member','ts-custom-posts'),
		'edit_item'          => __('Edit Member','ts-custom-posts'),
		'new_item'           => __('New Member','ts-custom-posts'),
		'all_items'          => __('All Members','ts-custom-posts'),
		'view_item'          => __('View Member','ts-custom-posts'),
		'search_items'       => __('Search Members','ts-custom-posts'),
		'not_found'          =>  __('No members found','ts-custom-posts'),
		'not_found_in_trash' => __('No members found in Trash','ts-custom-posts'),
		'parent_item_colon'  => '',
		'menu_name'          => __('Teams','ts-custom-posts')
	);

	$args = array(
		'labels'   => $labels,
		'public'   => true,
		'supports' => array('title', 'thumbnail', 'editor', 'thumbnail'),
		'menu_icon' => plugins_url('ts-custom-posts/images/custom.team.png'),
        'rewrite' => array('slug' => $slug)
	);

	register_post_type( 'ts_teams', $args );
}

function ts_teams_messages( $messages )
{
  global $post, $post_ID;

  $messages['ts_teams'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Information about team member updated. <a href="%s">View member</a>','ts-custom-posts'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.','ts-custom-posts'),
    3 => __('Custom field deleted.','ts-custom-posts'),
    4 => __('Member updated.','ts-custom-posts'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('Member restored to revision from %s','ts-custom-posts'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Member published. <a href="%s">View member</a>','ts-custom-posts'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Member saved.','ts-custom-posts'),
    8 => sprintf( __('Member submitted. <a target="_blank" href="%s">Preview member</a>','ts-custom-posts'), esc_url( add_query_arg( 'preview', 'true', esc_url(get_permalink($post_ID)) ) ) ),
    9 => sprintf( __('Member scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview member</a>','ts-custom-posts'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i','ts-custom-posts' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Member draft updated. <a target="_blank" href="%s">Preview member</a>','ts-custom-posts'), esc_url( add_query_arg( 'preview', 'true', esc_url(get_permalink($post_ID)) ) ) ),
  );

  return $messages;
}

function ts_taxonomy_teams()
{
    $slug = get_option(THEMENAME .'_general');
    $slug = (isset($slug['slug_teams_taxonomy']) && !empty($slug['slug_teams_taxonomy'])) ? $slug['slug_teams_taxonomy'] : 'teams-category';
    register_taxonomy(
        'teams',
        'ts_teams',
        array(
            'label' => __( 'Teams','ts-custom-posts' ),
            'rewrite' => array( 'slug' => $slug ),
            'hierarchical' => true
        )
    );
}

/**
 * Portfolio
 */
function ts_portfolio() {
    $slug = get_option(THEMENAME .'_general');
    $slug = (isset($slug['slug_portfolio'])) ? $slug['slug_portfolio'] : 'portfolio';

	$labels = array(
		'name'               => __('Portfolio','ts-custom-posts'),
		'singular_name'      => __('Portfolio','ts-custom-posts'),
		'add_new'            => __('Add New Item','ts-custom-posts'),
		'add_new_item'       => __('Add New Item','ts-custom-posts'),
		'edit_item'          => __('Edit Item','ts-custom-posts'),
		'new_item'           => __('New Item','ts-custom-posts'),
		'all_items'          => __('All Items','ts-custom-posts'),
		'view_item'          => __('View Item','ts-custom-posts'),
		'search_items'       => __('Search items','ts-custom-posts'),
		'not_found'          =>  __('No items found','ts-custom-posts'),
		'not_found_in_trash' => __('No items found in Trash','ts-custom-posts'),
		'parent_item_colon'  => '',
		'menu_name'          => __('Portfolio','ts-custom-posts')
	);

	$args = array(
		'labels'   => $labels,
		'public'   => true,
		'supports' => array('title', 'editor', 'thumbnail'),
		'menu_icon' => plugins_url('ts-custom-posts/images/custom.portfolio.png'),
		'menu_position' => 4,
        'rewrite' => array('slug' => $slug)
	);

	register_post_type( 'portfolio', $args );
}

function ts_portfolio_messages( $messages ) {
  global $post, $post_ID;

  $messages['portfolio'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Portfolio Item updated. <a href="%s">View Portfolio Item</a>','ts-custom-posts'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.','ts-custom-posts'),
    3 => __('Custom field deleted.','ts-custom-posts'),
    4 => __('Portfolio Item updated.','ts-custom-posts'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('Slider restored to revision from %s','ts-custom-posts'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Portfolio Item published. <a href="%s">View Portfolio Item</a>','ts-custom-posts'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Portfolio Item saved.','ts-custom-posts'),
    8 => sprintf( __('Portfolio Item submitted. <a target="_blank" href="%s">Preview Portfolio Item</a>','ts-custom-posts'), esc_url( add_query_arg( 'preview', 'true', esc_url(get_permalink($post_ID)) ) ) ),
    9 => sprintf( __('Portfolio Item scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Portfolio Item</a>', 'slider'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i','ts-custom-posts' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Portfolio Item draft updated. <a target="_blank" href="%s">Preview Portfolio Item</a>','ts-custom-posts'), esc_url( add_query_arg( 'preview', 'true', esc_url(get_permalink($post_ID)) ) ) ),
  );

  return $messages;
}

function ts_taxonomy_portfolio()
{
    $slug = get_option(THEMENAME .'_general');
    $slug = (isset($slug['slug_portfolio_taxonomy'])) ? $slug['slug_portfolio_taxonomy'] : 'portfolio-categories';

    register_taxonomy(
        'portfolio_register_post_type',
        'portfolio',
        array(
            'label' => __( 'Categories','ts-custom-posts' ),
            'rewrite' => array( 'slug' => $slug ),
            'hierarchical' => true
        )
    );
}

/**
 * Pricing table
 */
function ts_pricing_table() {
    $slug = get_option(THEMENAME .'_general');
    $slug = (isset($slug['slug_pricing_table'])) ? $slug['slug_pricing_table'] : 'pricing-table';
	$labels = array(
		'name'               => __('Pricing table','ts-custom-posts'),
		'singular_name'      => __('Pricing table','ts-custom-posts'),
		'add_new'            => __('Add New Item','ts-custom-posts'),
		'add_new_item'       => __('Add New Item','ts-custom-posts'),
		'edit_item'          => __('Edit Item','ts-custom-posts'),
		'new_item'           => __('New Item','ts-custom-posts'),
		'all_items'          => __('All Items','ts-custom-posts'),
		'view_item'          => __('View Item','ts-custom-posts'),
		'search_items'       => __('Search items','ts-custom-posts'),
		'not_found'          =>  __('No items found','ts-custom-posts'),
		'not_found_in_trash' => __('No items found in Trash','ts-custom-posts'),
		'parent_item_colon'  => '',
		'menu_name'          => __('Pricing table','ts-custom-posts')
	);

	$args = array(
		'labels'   => $labels,
		'public'   => true,
		'supports' => array('title'),
		'menu_icon' => plugins_url('ts-custom-posts/images/custom.pricing.png'),
		'menu_position' => 25,
        'rewrite' => array('slug' => $slug)
	);

	register_post_type( 'ts_pricing_table', $args );
}

function ts_enc_string($string, $action = 'on'){
    if( $action == 'on' ){
        return base64_encode($string);
    }else{
        return base64_decode($string);
    }
}

function ts_pricing_table_messages( $messages ) {
  global $post, $post_ID;

  $messages['ts_pricing_table'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Pricing table Item updated. <a href="%s">View Pricing table Item</a>','ts-custom-posts'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.','ts-custom-posts'),
    3 => __('Custom field deleted.','ts-custom-posts'),
    4 => __('Pricing table Item updated.','ts-custom-posts'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('Slider restored to revision from %s','ts-custom-posts'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Pricing table Item published. <a href="%s">View Pricing table Item</a>','ts-custom-posts'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Pricing table Item saved.','ts-custom-posts'),
    8 => sprintf( __('Pricing table Item submitted. <a target="_blank" href="%s">Preview Pricing table Item</a>','ts-custom-posts'), esc_url( add_query_arg( 'preview', 'true', esc_url(get_permalink($post_ID)) ) ) ),
    9 => sprintf( __('Pricing table Item scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Pricing table Item</a>', 'slider'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i','ts-custom-posts' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Pricing table Item draft updated. <a target="_blank" href="%s">Preview Pricing table Item</a>','ts-custom-posts'), esc_url( add_query_arg( 'preview', 'true', esc_url(get_permalink($post_ID)) ) ) ),
  );

  return $messages;
}

function ts_taxonomy_pricing_table()
{
    $slug = get_option(THEMENAME .'_general');
    $slug = (isset($slug['slug_pricing_table_taxonomy'])) ? $slug['slug_pricing_table_taxonomy'] : 'pricing-table-categories';

    register_taxonomy(
        'ts_pricing_table_categories',
        'ts_pricing_table',
        array(
            'label' => __( 'Categories', 'ts-custom-posts' ),
            'rewrite' => array( 'slug' => $slug ),
            'hierarchical' => true
        )
    );
}

function ts_admin_init(){

    $themes = array('codmark', 'videotouch', 'hopeful', 'esquise', 'shootback', 'slimvideo', 'hologram', 'syncope', 'aspact');

    if ( defined('TS_THEMENAME') && !in_array(TS_THEMENAME, $themes) ) {

        if ( false === get_option( 'inline_style' ) ) {
           $data = array(
               'css' => ''
           );

           update_option('inline_style', $data);
       }

       // Register a section
       add_settings_section(
           'css_section',
           __( 'Custom CSS', 'melodrama' ),
           'inline_style_callback',
           'inline_style'
       );

       register_setting( 'inline_style', 'inline_style');
    }
}
add_action( 'admin_init', 'ts_admin_init' );

function inline_style_callback()
{
    echo '<p>'.__( 'Insert here your custom CSS', 'melodrama' ).'</p>';

    $options = get_option('inline_style');
    $inlineStyle = isset($options['css']) ? $options['css'] : '';

    $html = '<textarea name="inline_style[css]" cols="80" rows="30">'. $inlineStyle .'</textarea>';
    echo $html;

} // END inline_style_callback()

function ts_custom_post_author_archive( $query )
{
    if ( $query->is_author ) {

        $args = array(
            'public'   => true,
            '_builtin' => false
        );

        $post_types = get_post_types( $args, 'names' );

        $query->set( 'post_type', array_values( $post_types ) );
    }

    remove_action( 'pre_get_posts', 'ts_custom_post_author_archive' );
}

add_action( 'pre_get_posts', 'ts_custom_post_author_archive' );

?>