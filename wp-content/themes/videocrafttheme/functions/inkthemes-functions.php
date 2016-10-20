<?php
/* ----------------------------------------------------------------------------------- */
/* Post Thumbnail Support
/*----------------------------------------------------------------------------------- */
add_theme_support('post-thumbnails');
if (function_exists('add_image_size'))
add_theme_support('post-thumbnails');
if (function_exists('add_image_size')) {
add_image_size('post_thumbnail', 595, 224, true);
}
/* ----------------------------------------------------------------------------------- */
/* Auto Feed Links Support
/*----------------------------------------------------------------------------------- */
if (function_exists('add_theme_support')) {
add_theme_support('automatic-feed-links');
}
/* ----------------------------------------------------------------------------------- */
/* Custom Menus Function
/*----------------------------------------------------------------------------------- */
// Add CLASS attributes to the first <ul> occurence in wp_page_menu
function inkthemes_add_menuclass($ulclass) {
return preg_replace('/<ul>/', '<ul class="ddsmoothmenu">', $ulclass, 1);
}
add_filter('wp_page_menu', 'inkthemes_add_menuclass');
add_action('init', 'inkthemes_register_custom_menu');
add_action('init', 'inkthemes_register_custom_menu1');
function inkthemes_register_custom_menu() {
register_nav_menu('custom_menu', __('Main Menu', 'videocraft'));
}
function inkthemes_register_custom_menu1() {
register_nav_menu('custom_menu_footer', __('Footer Menu', 'inkthemes_blogtrend'));
}
function inkthemes_nav() {
if (function_exists('wp_nav_menu')) {
echo '<div id="menu">';
echo '<a href="#" class="mobile_nav closed">' . PAGE_NAVIGATION . '<span></span></a>';
wp_nav_menu(array('theme_location' => 'custom_menu', 'menu_class' => 'ddsmoothmenu', 'fallback_cb' => 'inkthemes_nav_fallback'));
echo "</div>";
} else {
inkthemes_nav_fallback();
}
}

function inkthemes_nav_fallback() {
?>
<div id="menu">
<ul class="ddsmoothmenu">
<?php
$upload_pid = get_option('upload_video');
$dashboard_pid = get_option('Dashboard');
wp_list_pages("title_li=&show_home=1&sort_column=menu_order&exclude=$upload_pid,$dashboard_pid");
?>
</ul>
</div>
<?php
}
function inkthemes_nav_menu_items($items) {
if (is_home()) {
$homelink = '<li class="current_page_item">' . '<a href="' . home_url('/') . '">' . __('Home', 'videocraft') . '</a></li>';
} else {
$homelink = '<li>' . '<a href="' . home_url('/') . '">' . __('Home', 'videocraft') . '</a></li>';
}
$items = $homelink . $items;
return $items;
}
function inkthemes_cat_nav() {
if (function_exists('wp_nav_menu'))
wp_nav_menu(array('theme_location' => 'custom_menu_footer', 'container_id' => 'menu', 'menu_class' => 'footer_topmenu', 'fallback_cb' => 'inkthemes_cat_nav_fallback'));
else
inkthemes_cat_nav_fallback();
}
function inkthemes_cat_nav_fallback() {
?>
<div class="footer_top">
<ul class="footer_topmenu">
<?php
wp_list_pages('title_li=&show_home=1&sort_column=menu_order');
?>
</ul>
</div>
<?php
}
add_filter('wp_list_pages', 'inkthemes_nav_menu_items');
/* ----------------------------------------------------------------------------------- */
/* Breadcrumbs Plugin
/*----------------------------------------------------------------------------------- */
function inkthemes_breadcrumbs() {
$delimiter = '&raquo;';
$home = 'Home'; // text for the 'Home' link
$before = '<span class="current">'; // tag before the current crumb
$after = '</span>'; // tag after the current crumb
echo '<div id="crumbs">';
global $post;
$homeLink = home_url();
echo '<a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';
if (is_category()) {
global $wp_query;
$cat_obj = $wp_query->get_queried_object();
$thisCat = $cat_obj->term_id;
$thisCat = get_category($thisCat);
$parentCat = get_category($thisCat->parent);
if ($thisCat->parent != 0)
echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
echo $before . 'Archive by category "' . single_cat_title('', false) . '"' . $after;
}
elseif (is_day()) {
echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
echo '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
echo $before . get_the_time('d') . $after;
} elseif (is_month()) {
echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
echo $before . get_the_time('F') . $after;
} elseif (is_year()) {
echo $before . get_the_time('Y') . $after;
} elseif (is_single() && !is_attachment()) {
if (get_post_type() != 'post') {
$post_type = get_post_type_object(get_post_type());
$slug = $post_type->rewrite;
echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
echo $before . get_the_title() . $after;
} else {
$cat = get_the_category();
$cat = $cat[0];
echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
echo $before . get_the_title() . $after;
}
} elseif (!is_single() && !is_page() && get_post_type() != 'post') {
$post_type = get_post_type_object(get_post_type());
echo $before . $post_type->labels->singular_name . $after;
} elseif (is_attachment()) {
$parent = get_post($post->post_parent);
$cat = get_the_category($parent->ID);
$cat = $cat[0];
echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
echo $before . get_the_title() . $after;
} elseif (is_page() && !$post->post_parent) {
echo $before . get_the_title() . $after;
} elseif (is_page() && $post->post_parent) {
$parent_id = $post->post_parent;
$breadcrumbs = array();
while ($parent_id) {
$page = get_page($parent_id);
$breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
$parent_id = $page->post_parent;
}
$breadcrumbs = array_reverse($breadcrumbs);
foreach ($breadcrumbs as $crumb)
echo $crumb . ' ' . $delimiter . ' ';
echo $before . get_the_title() . $after;
} elseif (is_search()) {
echo $before . 'Search results for "' . get_search_query() . '"' . $after;
} elseif (is_tag()) {
echo $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after;
} elseif (is_author()) {
global $author;
$userdata = get_userdata($author);
echo $before . 'Articles posted by ' . $userdata->display_name . $after;
} elseif (is_404()) {
echo $before . 'Error 404' . $after;
}
if (get_query_var('paged')) {
if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author())
echo ' (';
echo __('Page', 'videocraft') . ' ' . get_query_var('paged');
if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author())
echo ')';
}
echo '</div>';
}
//* ----------------------------------------------------------------------------------- */
/* Function to call first uploaded image in functions file
/*----------------------------------------------------------------------------------- */
/**
* This function thumbnail id and
* returns thumbnail image
* @param type $iw
* @param type $ih 
*/
function inkthemes_get_thumbnail($iw, $ih) {
$permalink = get_permalink($id);
$thumb = get_post_thumbnail_id();
$image = inkthemes_thumbnail_resize($thumb, '', $iw, $ih, true, 90);
if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) {
print "<a href='$permalink'><img class='postimg' src='$image[url]' width='$image[width]' height='$image[height]' /></a>";
}
}
// Define what post types to search
function searchAll( $query ) {
if ( $query->is_search ) {
$query->set( 'post_type', array( 'post', 'page', 'feed', 'video_listing', 'custom_post_type2', 'custom_post_type3', 'custom_post_type4' ));
}
return $query;
}
// The hook needed to search ALL content
add_filter( 'the_search_query', 'searchAll' );
/**
* This function gets image width and height and
* Prints attached images from the post        
*/
function inkthemes_get_image($width, $height) {
$w = $width;
$h = $height;
global $post, $posts;
//This is required to set to Null
$img_source = '';
$permalink = get_permalink($id);
ob_start();
ob_end_clean();
$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
if (isset($matches [1] [0])) {
$img_source = $matches [1] [0];
}
$img_path = inkthemes_image_resize($img_source, $w, $h);
if (!empty($img_path[url])) {
print "<a href='$permalink'><img src='$img_path[url]' class='postimg' alt='Post Image'/></a>";
}
}

/* ----------------------------------------------------------------------------------- */
/* Attachment Page Design
/*----------------------------------------------------------------------------------- */

//For Attachment Page
/**
* Prints HTML with meta information for the current post (category, tags and permalink).
*
*/
function inkthemes_posted_in() {
// Retrieves tag list of current post, separated by commas.
$tag_list = get_the_tag_list('', ', ');
if ($tag_list) {
$posted_in = __('This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'videocraft');
} elseif (is_object_in_taxonomy(get_post_type(), 'category')) {
$posted_in = __('This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'videocraft');
} else {
$posted_in = __('Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'videocraft');
}
// Prints the string, replacing the placeholders.
printf(
$posted_in, get_the_category_list(', '), $tag_list, get_permalink(), the_title_attribute('echo=0')
);
}
// function to display number of posts.
function getPostViews($postID){
$count_key = 'post_views_count';
$count = get_post_meta($postID, $count_key, true);
if($count==''){
delete_post_meta($postID, $count_key);
add_post_meta($postID, $count_key, '0');
return "0 View";
}
return $count.' Views';
}
// function to count views.
function setPostViews($postID) {
$count_key = 'post_views_count';
$count = get_post_meta($postID, $count_key, true);
if($count==''){
$count = 0;
delete_post_meta($postID, $count_key);
add_post_meta($postID, $count_key, '0');
}else{
$count++;
update_post_meta($postID, $count_key, $count);
}
}
// Add it to a column in WP-Admin
add_filter('manage_posts_columns', 'posts_column_views');
add_action('manage_posts_custom_column', 'posts_custom_column_views',5,2);
function posts_column_views($defaults){
$defaults['post_views'] = __('Views');
return $defaults;
}
function posts_custom_column_views($column_name, $id){
if($column_name === 'post_views'){
echo getPostViews(get_the_ID());
}
}
?>
<?php
/**
* Set the content width based on the theme's design and stylesheet.
*
* Used to set the width of images and content. Should be equal to the width the theme
* is designed for, generally via the style.css stylesheet.
*/
if (!isset($content_width))
$content_width = 590;
?>
<?php

/**
* Register widgetized areas, including two sidebars and four widget-ready columns in the footer.
*
* To override twentyten_widgets_init() in a child theme, remove the action hook and add your own
* function tied to the init hook.
*
* @uses register_sidebar
*/
function inkthemes_widgets_init() {
// Area 1, located at the top of the sidebar.
register_sidebar(array(
'name' => __('Primary Widget Area', 'videocraft'),
'id' => 'primary-widget-area',
'description' => __('The primary widget area', 'videocraft'),
'before_widget' => '',
'after_widget' => '',
'before_title' => '<h3 class="title"><span class="arrow">',
'after_title' => '</span></h3>',
));
// Area 2, located at the Home page sidebar.
register_sidebar(array(
'name' => __('Home Page Widget Area', videocraft),
'id' => 'home-widget-area',
'description' => __('The home widget area', 'videocraft'),
'before_widget' => '<div class="widget">',
'after_widget' => '</div>',
'before_title' => '<h3 class="title"><span class="arrow">',
'after_title' => '</span></h3>',
));
// Area 3, located at the contact page sidebar.
register_sidebar(array(
'name' => __('Video Listing Page Widget Area', 'videocraft'),
'id' => 'listing-widget-area',
'description' => __('The listing widget area', 'videocraft'),
'before_widget' => '<div class="widget">',
'after_widget' => '</div>',
'before_title' => '<h3 class="title"><span class="arrow">',
'after_title' => '</span></h3>',
));
// Area 4, located at the single video page sidebar.
register_sidebar(array(
'name' => __('Single video Page Widget Area', 'videocraft'),
'id' => 'single-video-widget-area',
'description' => __('The contact widget area', 'videocraft'),
'before_widget' => '<div class="widget">',
'after_widget' => '</div>',
'before_title' => '<h3 class="title"><span class="arrow">',
'after_title' => '</span></h3>',
));
// Area 5, located at the contact page sidebar.
register_sidebar(array(
'name' => __('Contact Page Widget Area', 'videocraft'),
'id' => 'contact-widget-area',
'description' => __('The contact widget area', 'videocraft'),
'before_widget' => '<div class="widget">',
'after_widget' => '</div>',
'before_title' => '<h3 class="title"><span class="arrow">',
'after_title' => '</span></h3>',
));
// Area 6, located at the Category page sidebar.
register_sidebar(array(
'name' => __('Category Page Widget Area', 'videocraft'),
'id' => 'category-widget-area',
'description' => __('The Category widget area', 'videocraft'),
'before_widget' => '<div class="widget">',
'after_widget' => '</div>',
'before_title' => '<h3 class="title"><span class="arrow">',
'after_title' => '</span></h3>',
));
// Area 7, located in the footer. Empty by default.
register_sidebar(array(
'name' => __('First Footer Widget Area', 'videocraft'),
'id' => 'first-footer-widget-area',
'description' => __('The first footer widget area', 'videocraft'),
'before_widget' => '',
'after_widget' => '',
'before_title' => '<h4>',
'after_title' => '</h4>',
));
// Area 8, located in the footer. Empty by default.
register_sidebar(array(
'name' => __('Second Footer Widget Area', 'videocraft'),
'id' => 'second-footer-widget-area',
'description' => __('The second footer widget area', 'videocraft'),
'before_widget' => '',
'after_widget' => '',
'before_title' => '<h4>',
'after_title' => '</h4>',
));
}

/** Register sidebars by running inkthemes_widgets_init() on the widgets_init hook. */
add_action('widgets_init', 'inkthemes_widgets_init');
?>
<?php
/**
* Pagination
*
*/
function inkthemes_pagination($pages = '', $range = 2) {
$showitems = ($range * 2) + 1;
global $paged;
if (empty($paged))
$paged = 1;
if ($pages == '') {
global $WP_Query;
$WP_Query = @ $GLOBALS['wp_query'];
$pages = $WP_Query->max_num_pages;
if (!$pages) {
$pages = 1;
}
}
if (1 != $pages) {
echo "<ul class='paging'>";
if ($paged > 2 && $paged > $range + 1 && $showitems < $pages)
echo "<li><a href='" . get_pagenum_link(1) . "'>&laquo;</a></li>";
if ($paged > 1 && $showitems < $pages)
echo "<li><a href='" . get_pagenum_link($paged - 1) . "'>&lsaquo;</a></li>";
for ($i = 1; $i <= $pages; $i++) {
if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems )) {
echo ($paged == $i) ? "<li><a href='" . get_pagenum_link($i) . "' class='current' >" . $i . "</a></li>" : "<li><a href='" . get_pagenum_link($i) . "' class='inactive' >" . $i . "</a></li>";
}
}
if ($paged < $pages && $showitems < $pages)
echo "<li><a href='" . get_pagenum_link($paged + 1) . "'>&rsaquo;</a></li>";
if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages)
echo "<li><a href='" . get_pagenum_link($pages) . "'>&raquo;</a></li>";
echo "</ul>\n";
}
}
?>
<?php

/////////Theme Options
/* ----------------------------------------------------------------------------------- */
/* Add Favicon
/*----------------------------------------------------------------------------------- */
function inkthemes_childtheme_favicon() {
if (inkthemes_get_option('inkthemes_favicon') != '') {
echo '<link rel="shortcut icon" href="' . inkthemes_get_option('inkthemes_favicon') . '"/>' . "\n";
} else {
?>
<link rel="shortcut icon" href="<?php echo get_template_directory_uri() ?>/images/favicon.ico" />
<?php
}
}

add_action('wp_head', 'inkthemes_childtheme_favicon');
?>
<?php
/* ----------------------------------------------------------------------------------- */
/* Show analytics code in footer */
/* ----------------------------------------------------------------------------------- */

function inkthemes_childtheme_analytics() {
$output = inkthemes_get_option('inkthemes_analytics');
if ($output <> "")
echo stripslashes($output) ;
}

add_action('wp_head', 'inkthemes_childtheme_analytics');
/* ----------------------------------------------------------------------------------- */
/* Custom CSS Styles */
/* ----------------------------------------------------------------------------------- */

function inkthemes_of_head_css() {
$output = '';
$custom_css = inkthemes_get_option('inkthemes_customcss');
if ($custom_css <> '') {
$output .= $custom_css . "\n";
}
// Output styles
if ($output <> '') {
$output = "<!-- Custom Styling -->\n<style type=\"text/css\">\n" . $output . "</style>\n";
echo $output;
}
}

add_action('wp_head', 'inkthemes_of_head_css');

//Load languages file
load_theme_textdomain('videocraft', get_template_directory() . '/languages');
$locale = get_locale();
$locale_file = TEMPLATEPATH . "/languages/$locale.php";
if (is_readable($locale_file))
require_once($locale_file);
// This theme styles the visual editor with editor-style.css to match the theme style.
add_editor_style();
// activate support for thumbnails
if (function_exists('add_theme_support')) { // added in 2.9
add_theme_support('menus');
add_theme_support('post-thumbnails', array('post', 'game_listing'));
set_post_thumbnail_size(250, 250, false);
add_image_size('blog-thumbnail', 150, 150, true); // blog post thumbnail size, box resize mode
add_image_size('sidebar-thumbnail', 48, 48, true); // sidebar blog thumbnail size, box resize mode
}

function get_category_id($cat_name) {
$term = get_term_by('name', $cat_name, 'category');
return $term->term_id;
}
//Trm excerpt
function inkthemes_custom_trim_excerpt($length) {
global $post;
$explicit_excerpt = $post->post_excerpt;
if ( '' == $explicit_excerpt ) {
$text = get_the_content('');
$text = apply_filters('the_content', $text);
$text = str_replace(']]>', ']]>', $text);
}
else {
$text = apply_filters('the_content', $explicit_excerpt);
}
$text = strip_shortcodes( $text ); // optional
$text = strip_tags($text);
$excerpt_length = $length;
$words = explode(' ', $text, $excerpt_length + 1);
if (count($words)> $excerpt_length) {
array_pop($words);
array_push($words, '[&hellip;]');
$text = implode(' ', $words);
$text = apply_filters('the_excerpt',$text);
}
return $text;
}
/**
* Function Name: geocraft_auth_menu
* Description: Displays user menu
* @global type $current_user 
*/
function videocast_auth_menu() {
global $current_user;
global $wpdb;
$dashboard_pid = get_option('Dashboard');	 
$ink=home_url("/?page_id=$dashboard_pid");	
$geo_val=$wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID =$dashboard_pid ",ARRAY_N);
if(!$geo_val[0]){
$wpdb->insert($wpdb->posts,array('ID' =>$dashboard_pid,'post_author' => 1,'post_date_gmt' => $dat,				'post_title' => 'Dashboard','post_status' =>'publish','comment_status' => 'closed','ping_status' => 'closed',	'post_name' => 'Dashboard','guid' => $ink,'post_type' => 'page'));

$mylink = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE post_id =$dashboard_pid ",ARRAY_N);
$wpdb->insert($wpdb->postmeta,array('meta_id' =>$mylink[0],'post_id' =>$dashboard_pid,'meta_key' => '_wp_page_template','meta_value' => 'tpl_dashboard.php'));		
}
if($geo_val[7]=='trash'){
$wpdb->query("UPDATE $wpdb->posts SET post_status = 'publish' WHERE ID =$dashboard_pid");
}

$vid_list=get_option('upload_video');
$geo_url=site_url('/?page_id=' . get_option('upload_video'));
$geo_val_list=$wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID =$vid_list ",ARRAY_N);
if(!$geo_val_list[0]){
$wpdb->insert($wpdb->posts,array('ID' =>$vid_list,'post_author' => 1,'post_date_gmt' => $dat,'post_title' => 'Add New Listing',	'post_status' =>'publish','comment_status' => 'closed','ping_status' => 'closed',		'post_name' => 'submit-listing','guid' => $geo_url,'post_type' => 'page','post_excerpt' => ''));		
$listlink = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE post_id =$vid_list ",ARRAY_N);
$wpdb->insert($wpdb->postmeta,array('meta_id' =>$listlink[0],'post_id' =>$vid_list, 'meta_key' => '_wp_page_template','meta_value' => 'tpl_video_upload.php',));				
}
if($geo_val_list[7]=='trash'){
$wpdb->query("UPDATE $wpdb->posts SET post_status = 'publish' WHERE ID =$vid_list");
}
?>
<ul class="associative_link">
<?php
if (is_user_logged_in()) {
?> 
<li><a href="<?php echo home_url("/?page_id=$dashboard_pid"); ?>"><?php echo "Dashboard"; ?></a></li>
<li><a href="<?php echo wp_logout_url(home_url()); ?>"><?php echo LOG_OUT; ?></a></li> 
<?php
} else {
?>  
<li><a href="<?php echo site_url("/?page_id=$vid_list"); ?>"><?php echo SIGN; ?></a></li> 
<?php } ?>
<li class="listing-btn"><a href="<?php echo site_url("/?page_id=$vid_list"); ?>"><?php echo ADD_LISTING; ?></a></li> 
</ul>
<?php
}
add_action('videocast_auth_menu', 'videocast_auth_menu');
/* ----------------------------------------------------------------------------------- */
/* Show analytics code in footer */
/* ----------------------------------------------------------------------------------- */  
function insert_listing_data($post_data) {
global $wpdb, $current_user;
for ($i = 0; $i < count($post_data); $i++) {
$post_title = $post_data[$i]['post_title'];
$post_count = $wpdb->get_var("SELECT count(ID) FROM $wpdb->posts where post_title like \"$post_title\" and post_type='" . 'video_listing' . "' and post_status in ('publish','draft')");
if (!$post_count) {
$post_data_array = array();
$catids_arr = array();
$my_post = array();
$post_data_array = $post_data[$i];
$my_post['post_title'] = $post_data_array['post_title'];
$my_post['post_content'] = $post_data_array['post_content'];
$my_post['post_type'] = 'video_listing';
if ($post_data_array['post_author']) {
$my_post['post_author'] = $post_data_array['post_author'];
} else {
$my_post['post_author'] = 1;
}
$my_post['post_status'] = inkthemes_get_option('video_post_mode');
$my_post['post_category'] = $post_data_array['post_category'];
$my_post['tags_input'] = $post_data_array['post_tags'];
$last_postid = wp_insert_post($my_post);
wp_set_object_terms($last_postid, $post_data_array['post_category'], $taxonomy = 'video_cat');
wp_set_object_terms($last_postid, $post_data_array['post_tags'], $taxonomy = 'video_tag');
$post_meta = $post_data_array['post_meta'];
if ($post_meta) {
foreach ($post_meta as $mkey => $mval) {
update_post_meta($last_postid, $mkey, $mval);
}
}
$post_image = $post_data_array['post_image'];
if ($post_image) {
for ($m = 0; $m < count($post_image); $m++) {
$menu_order = $m + 1;
$image_array = explode('/', $post_image[$m]);
$img_name = $image_array[count($image_array) - 1];
$img_name_arr = explode('.', $img_name);
$post_img = array();
$post_img['post_title'] = $img_name_arr[0];
$post_img['post_status'] = 'attachment';
$post_img['post_parent'] = $last_postid;
$post_img['post_type'] = 'attachment';
$post_img['post_mime_type'] = 'image/jpeg';
$post_img['menu_order'] = $menu_order;
$last_postimage_id = wp_insert_post($post_img);
update_post_meta($last_postimage_id, '_wp_attached_file', $post_image[$m]);
$post_attach_arr = array(
	"width" => 580,
	"height" => 480,
	"hwstring_small" => "height='100' width='100'",
	"file" => $post_image[$m],
);
wp_update_attachment_metadata($last_postimage_id, $post_attach_arr);
}
}
}
}
} 
/* ----------------------------------------------------------------------------------- */
/* Show analytics code in footer */
/* ----------------------------------------------------------------------------------- */
function videocast_submit_video() {
if (isset($_POST['submit'])) {
global $user_ID, $post, $posted, $wpdb;
//Start Post 5
$image_meta = array();
$post_meta = array();           
$img4 = $temp_url . 'thumb3.jpg';
$image_meta[] = $temp_url . "temp/7.jpg";
$post_meta = array(
'_video_url' => esc_attr($_POST['videourl']),
'_meta_video' => esc_attr($_POST['place_image1']),
'_meta_image' => esc_attr($_POST['place_image2'])
);
$post_data[] = array(
"post_title" => $_POST['place_title'],
"post_content" => $wpdb->escape($_POST['description']),
"post_image" => $image_meta,
"post_category" => $_POST['category'],
"post_tags" => $_POST['place_tag'],
"post_meta" => $post_meta,
"post_author" =>$user_ID
);
//End Post 5
insert_listing_data($post_data);
}
?>
<style>
.server-limit{
font-size:14px;
color:#3d638d;
text-align:right;
margin-right:20px;
}
</style>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.validate.min.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri() . '/css/geo_module_style.css'; ?>" />
<script type="text/javascript" src="<?php echo get_template_directory_uri() . '/js/ajaxupload.js'; ?>"></script> 
<?php
require_once(TEMPLATEPATH . '/js/image_upload.php');
?>
<script>
jQuery(document).ready(function(){
jQuery("#uploadForm").validate();
});
</script>
<?php 
if (isset($_POST['submit'])) {
echo "<div class='sucess-send'><h2>Your Video has been successfully submitted And Awaiting For Publish.				
</h2>  </div>
"; ?>
<br/>
<?php $upload_page_id = get_option('upload_video'); ?>
<h2 class="upload-heading">Upload New Video</h2>
<br/>
<div class="uploadbtn2"> <a href="<?php echo site_url("?page_id=$upload_page_id"); ?>" class="upload1">Upload</a>
</div>	
<?php    }
?>
<?php if(!(isset($_POST['submit']))){?>
<form action="" id="uploadForm" name="video-form" class="uploadForm"  method="post" enctype="multipart/form-data">
<label for="videoName">Video Title<span class="required">*</span></label>
<input type="text" class="required requiredField" id="list_title" name="place_title" value="<?php if (isset($posted['place_title']))
echo $posted['place_title']; ?>"/>
<br />
<br />
<label for="description">Description<span class="required">*</span></label>
<textarea style="width:250px; height: 100px;" class="required requiredField" id="description" name="description" row="20" col="25"><?php if (isset($posted['description']))
echo $posted['description']; ?></textarea>
<br />
<br />
<label for="tags">Tags<span class="required"></span></label>
<input type="text" name="place_tag" id="tags" value="" placeholder="Tags"  />
<br/><br/><br/> 
<label class="select-category" for="vcategory">Select Category<span class="required">*</span></label>
            <script type="text/javascript">
                function displaychk_frm(){
                    dom = document.forms['video-form'];
                    chk = dom.elements['category[]'];
                    len = dom.elements['category[]'].length;
        									
                    if(document.getElementById('selectall').checked == true) { 
                        for (i = 0; i < len; i++)
                            chk[i].checked = true ;
                    } else { 
                        for (i = 0; i < len; i++)
                            chk[i].checked = false ;
                    }
                }
            </script>
   <div>
                    <?php
                    global $wpdb;
                    $taxonomy = 'video_cat';
                    $table_prefix = $wpdb->prefix;
                    $wpcat_id = NULL;
                    //Fetch category                          
                    $wpcategories = (array) $wpdb->get_results("
                            SELECT * FROM {$table_prefix}terms, {$table_prefix}term_taxonomy
                            WHERE {$table_prefix}terms.term_id = {$table_prefix}term_taxonomy.term_id
                            AND {$table_prefix}term_taxonomy.taxonomy ='" . $taxonomy . "' and  {$table_prefix}term_taxonomy.parent=0  ORDER BY {$table_prefix}terms.name ASC");

                    $wpcategories = array_values($wpcategories);
                    if ($wpcategories) {
                        echo "<ul class=\"select_cat\">";
                        if ($taxonomy == 'video_cat') {
                            ?>
                            <li><label class="select-all"><input type="checkbox" name="selectall" id="selectall" class="checkbox" onclick="displaychk_frm();" /></label><?php echo 'Select All'; ?></li>

                            <?php
                        }
                        foreach ($wpcategories as $wpcat) {
                            $counter++;
                            $termid = $wpcat->term_id;
                            $name = $wpcat->name;
                            $termprice = $wpcat->term_price;
                            $tparent = $wpcat->parent;
                            ?>
                            <li><label><input class="list_category" type="checkbox" name="category[]" id="<?php echo $termid; ?>" value="<?php echo $name; ?>" class="checkbox" /><?php echo $name; ?></label></li>
                            <?php
                            $args = array(
                                'type' => 'video_listing',
                                'child_of' => '',
                                'parent' => $termid,
                                'orderby' => 'name',
                                'order' => 'ASC',
                                'hide_empty' => 0,
                                'hierarchical' => 1,
                                'exclude' => $termid,
                                'include' => '',
                                'number' => '',
                                'taxonomy' => 'video_cat',
                                'pad_counts' => false);
                            $acb_cat = get_categories($args);

                            if ($acb_cat) {
                                echo "<ul class=\"children\">";
                                foreach ($acb_cat as $child_of) {
                                    $term = get_term_by('id', $child_of->term_id, 'video_cat');
                                    $termid = $term->term_taxonomy_id;
                                    $term_tax_id = $term->term_id;
                                    $name = $term->name;
                                    ?>
                                    <li><label><input class="list_category" type="checkbox" name="category[]" id="<?php echo $termid; ?>" value="<?php echo $name; ?>" class="checkbox" /><?php echo $name; ?></label></li>
                                    <?php
                                    $args = array(
                                        'type' => 'video_listing',
                                        'child_of' => '',
                                        'parent' => $term_tax_id,
                                        'orderby' => 'name',
                                        'order' => 'ASC',
                                        'hide_empty' => 0,
                                        'hierarchical' => 1,
                                        'exclude' => $term_tax_id,
                                        'include' => '',
                                        'number' => '',
                                        'taxonomy' => 'video_cat',
                                        'pad_counts' => false);
                                    $acb_cat = get_categories($args);
                                    if ($acb_cat) {
                                        echo "<ul class=\"children\">";
                                        foreach ($acb_cat as $child_of) {
                                            $term = get_term_by('id', $child_of->term_id, 'video_cat');
                                            $termid = $term->term_taxonomy_id;
                                            $term_tax_id = $term->term_id;
                                            $name = $term->name;
                                            ?>
                                            <li><label><input class="list_category" type="checkbox" name="category[]" id="<?php echo $termid; ?>" value="<?php echo $name; ?>" class="checkbox" /><?php echo $name; ?></label></li>
                                            <?php
                                        }
                                        echo "</ul>";
                                    }
                                }
                                echo "</ul>";
                            }
                        }
                        echo "</ul>";
                    }
                    ?>   
                </div>             
<div class="upload-section">
<span class="upload-cheack">
<input type="radio" name="type" value="Youtube" checked>
Video From Youtube/Vimeo/Metacafe/Dailymotion</span>
<span class="upload-cheack"><input type="radio" name="type" value="Self" />
Upload your own Video </span>
<br/>
<br/>
<br />
<div id="video_youtube">
<label class="video_url" for="videourl">Video Link Youtube/Vimeo/Metacafe/Dailymotion<span class="required"></span></label>
<input type="text" name="videourl" id="videourl" value="" placeholder="Example: http://www.youtube.com/watch?v=bdt3Xp6Ma48"  />
</div>	
<div id="video_selfhosted">
<div id="video_selfhosted2">
<label for="file">Upload Video File:</label>
<br/>
<label class="upload-image" for="file">Upload Image File:</label> 
</div>
<div id="video_selfhosted1">
<?php									
$images = array(
	'place_image1',
	'place_image2'
);
foreach ($images as $image):
	?> 
	<div style="margin-bottom: 20px;">
		<input class='of-input upload-input' name='<?php echo $image; ?>' id='place_image1_upload' type='text' value="<?php echo $_POST['$image'] ?>" />
		<div style="display: inline;" class="upload_button_div"><input type="button" class="button image_upload_button" id="<?php echo $image; ?>" value="Upload" />
			<div class="button image_reset_button hide" id="reset_<?php echo $image; ?>" title="<?php echo $image; ?>" value="reset"></div>
		</div>             
	</div>						
	<?php
endforeach;
?> 
<div class="clear"></div>
<?php
$max_upload_size = wp_convert_bytes_to_hr(wp_max_upload_size());
echo '<p class="server-limit">Server Upload Size limit is ' . $max_upload_size . '.</p><br/>';
	?>
</div>		
 </div>	
 </div>
<div class="clear"></div>				 
<input class="submit" id="submit" name="submit" type="submit" value="Save"/>
</form>			
<script>     
jQuery(document).ready(function(){
jQuery("input[name$='type']").click(function(){
	var value = jQuery(this).val();
	if(value=='Youtube') {
		jQuery("#video_youtube").show();
		jQuery("#video_selfhosted").hide();
	}
	else if(value=='Self') {
		jQuery("#video_selfhosted").show();
		jQuery("#video_youtube").hide();
	}
});
jQuery("#video_youtube").show();
jQuery("#video_selfhosted").hide();
});
</script>
<?php }
}
//VidioCast Constant Variables
define('LOG_OUT',__('Log out','videocraft'));
define('ADD_LISTING',__('Add Video','videocraft'));
define('REG',__('Register','videocraft'));
define('LOG_REG',__('Login/Register','videocraft'));
define('SIGN',__('Sign In','videocraft'));
define('A_MSG_ST_EMAIL',__('A message will be sent to your email address.','videocraft'));
define('UR_CMT_AWAIT',__('Your comment is awaiting moderation.','videocraft'));
define('EDIT_PROFILE',__('Edit profile','videocraft'));
define('LOG_OUT',__('Log out','videocraft'));
define('REG',__('Register','videocraft'));
define('SIGN',__('Sign In','videocraft'));
define('LOGOUT',__('Logout','videocraft'));
define('FREE',__('Create a free account','videocraft'));
define('U_NAME',__('User Name:','videocraft'));
define('EMAIL',__('Email:','videocraft'));
define('PASS',__('Enter a Password:','videocraft'));
define('AGAIN_PASS',__('Enter Password again:','videocraft'));
define('LOST_D',__('if you lost your password then click on the link shown below','videocraft'));
define('LOST',__('Lost your password?','videocraft'));
define('FORGOT',__('Forget Your PassWord?','videocraft'));
define('NEW_EMAIL',__('Enter your new email or username:','videocraft'));
define('SLT_ALL',__('Select All','videocraft'));
define('ACTION',__('Action','videocraft'));
define('DUMMY_DT_DLT',__('All Dummy data has been removed from your database successfully!','videocraft'));
define('THEME_ACTIVATED',__('Theme activated successfully.','videocraft'));
define('SAMPLE_DT_PAPU',__('Sample data has been populated on your site. Wish to delete sample data?','videocraft'));
define('YES_DEL',__('Yes Delete Please!','videocraft'));
define('LIKE_TOP_POPULATED',__('Would you like to populate sample data on your site?','videocraft'));
define('MEMBER_SINCE',__('Member Since','videocraft'));
define('ADD_N_LISTING',__('Add New Video','videocraft'));
define('V_LISTING',__('View All','videocraft'));
define('PAGE_NAVIGATION',__('Pages Navigation Menu','videocraft'));

?>
