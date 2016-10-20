<?php
include_once TEMPLATEPATH . '/functions/inkthemes-functions.php';
$functions_path = TEMPLATEPATH . '/functions/';
/* These files build out the options interface.  Likely won't need to edit these. */
require_once ($functions_path . 'admin-functions.php');  // Custom functions and plugins
require_once ($functions_path . 'admin-interface.php');  // Admin Interfaces (options,framework, seo)
/* These files build out the theme specific options and associated functions. */
require_once ($functions_path . 'theme-options.php');   // Options panel settings and custom settings
require_once ($functions_path . 'shortcodes.php');
require_once ($functions_path . 'dynamic-image.php');
require_once ($functions_path . 'install.php');
require_once ($functions_path . '/rating/post_rating.php');
require_once ($functions_path . '/rating/module_functions.php');
require_once ($functions_path . '/custom_post_type/post_type.php');
require_once ($functions_path . '/custom_post_type/video_metabox.php');
require_once ($functions_path . '/registration_panel/custom_function.php');  
require_once ($functions_path . '/registration_panel/login.php');  
require_once ($functions_path . '/registration_panel/registration.php'); 
require_once ($functions_path . '/widget/category_widget.php');
require_once ($functions_path . '/widget/google_map.php');
require_once ($functions_path . '/dashboard/dashboard_functions.php');
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
?>
<?php
// Theme slug define
define('THEME_SLUG','video_cast');
/* ----------------------------------------------------------------------------------- */
/* Styles Enqueue */
/* ----------------------------------------------------------------------------------- */
function inkthemes_add_stylesheet() {
	if(inkthemes_get_option('inkthemes_altstylesheet')=='black'){
	wp_enqueue_style('coloroptions', get_template_directory_uri() . "/css/color/" . inkthemes_get_option('inkthemes_altstylesheet') . ".css", '', '', 'all');
	}
	wp_enqueue_style('shortcodes', get_template_directory_uri() . "/css/shortcode.css", '', '', 'all');
	 wp_enqueue_style('Registration_CSS', get_template_directory_uri() . "/functions/css/registration.css", '', '', 'all');
}
add_action('init', 'inkthemes_add_stylesheet');
/* ----------------------------------------------------------------------------------- */
/* jQuery Enqueue */
/* ----------------------------------------------------------------------------------- */
function inkthemes_wp_enqueue_scripts() {
    if (!is_admin()) {
        wp_enqueue_script('jquery');        
        wp_enqueue_script('inkthemes-ddsmoothmenu', get_template_directory_uri() . '/js/ddsmoothmenu.js', array('jquery'));
		wp_enqueue_script('inkthemes-tabs', get_template_directory_uri() . '/js/tabs.js', array('jquery'));
        wp_enqueue_script('inkthemes-custom', get_template_directory_uri() . '/js/custom.js', array('jquery'));
		wp_enqueue_script('inkthemes-validate', get_template_directory_uri() . '/js/jquery.validate.min.js', array('jquery'));
		wp_enqueue_script('inkthemes-jwplayer', get_template_directory_uri() . '/js/jwplayer.js', array('jquery'));
		wp_enqueue_script('inkthemes-scroll', get_template_directory_uri() . '/js/scroll.js', array('jquery'));
    } elseif (is_admin()) {
    }
}
add_action('wp_enqueue_scripts', 'inkthemes_wp_enqueue_scripts');
/* ----------------------------------------------------------------------------------- */
/* Custom Jqueries Enqueue */
/* ----------------------------------------------------------------------------------- */
function inkthemes_custom_jquery(){
    wp_enqueue_script('mobile-menu', get_template_directory_uri() . "/js/mobile-menu.js", array('jquery'));
}
add_action('wp_footer','inkthemes_custom_jquery');
//Front Page Rename
$get_status = inkthemes_get_option('re_nm');
$get_file_ac = TEMPLATEPATH . '/front-page.php';
$get_file_dl = TEMPLATEPATH . '/front-page-hold.php';
//True Part
if ($get_status === 'off' && file_exists($get_file_ac)) {
    rename("$get_file_ac", "$get_file_dl");
}
//False Part
if ($get_status === 'on' && file_exists($get_file_dl)) {
    rename("$get_file_dl", "$get_file_ac");
}
/* ----------------------------------------------------------------------------------- */
/* For Custom Post Type Rss Feed */
/* ----------------------------------------------------------------------------------- */
function myfeed_request($qv) {
	if (isset($qv['feed']))
		$qv['post_type'] = get_post_types();
	return $qv;
}
add_filter('request', 'myfeed_request');
//
function inkthemes_get_option($name) {
    $options = get_option('inkthemes_options');
    if (isset($options[$name]))
        return $options[$name];
}
//
function inkthemes_update_option($name, $value) {
    $options = get_option('inkthemes_options');
    $options[$name] = $value;
    return update_option('inkthemes_options', $options);
}
//
function inkthemes_delete_option($name) {
    $options = get_option('inkthemes_options');
    unset($options[$name]);
    return update_option('inkthemes_options', $options);
}
//Enqueue comment thread js
function inkthemes_enqueue_scripts() {
    if (is_singular() and get_site_option('thread_comments')) {
        wp_print_scripts('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'inkthemes_enqueue_scripts');
?>
