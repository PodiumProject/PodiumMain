<?php // Define the custom post types
add_action( 'init', 'video_post_type', 0 );
function video_post_type() {

// get the slug value for the job listing custom post type
if(get_option('jr_job_permalink')) $job_base_url = get_option('jr_job_permalink'); else $job_base_url = 'video_listing';

// create the custom post type and category taxonomy for job listings
register_post_type( 'video_listing',
array(
'labels' => array(
 'name' => __( 'Video', 'Videocraft' ),
  'singular_name' => __( 'Video', 'videocraft' ),
  'add_new' => __( 'Add New', 'Videocraft' ),
  'add_new_item' => __( 'Add New Video', 'videocraft' ),
  'edit' => __( 'Edit', 'videocraft' ),
  'edit_item' => __( 'Edit Video', 'Videocraft' ),
  'new_item' => __( 'New Video', 'Videocraft' ),
'view' => __( 'View Video', 'Videocraft' ),
'view_item' => __( 'View Video', 'Videocraft' ),
'search_items' => __( 'Search Video', 'Videocraft' ),
'not_found' => __( 'No Video found', 'Videocraft' ),
'not_found_in_trash' => __( 'No Video found in trash', 'Videocraft' ),
'parent' => __( 'Parent Video', 'Videocraft' ),
),
'description' => __( 'This is where you can create new Video listings on your site.', 'videocraft' ),
'public' => true,
'show_ui' => true,
'has_archive' => true,
'can_export' => true,
'capability_type' => 'post',
'publicly_queryable' => true,
'exclude_from_search' => false,
'_edit_link' => 'post.php?post=%d',
'_builtin' => false, // It's a custom post
'menu_position' => 8,
'menu_icon' => get_stylesheet_directory_uri() . '/images/video.png',
'hierarchical' => false,
'rewrite' => array( 'slug' => __($job_base_url), 'with_front' => false ), /* Slug set so that permalinks work when just showing post name */
'query_var' => 'video_listing',
'supports' => array( 'title', 'editor', 'custom-fields', 'thumbnail', 'comments', 'revisions', 'author' ),
'taxonomies' => array('video_cat', 'video_tag')
)
);

// register the new video category taxonomy
register_taxonomy('video_cat',
array('video_listing'),
array(
'hierarchical' => true,
'update_count_callback' => '_update_post_term_count',
'labels' => array(
		'name' => __( 'video Categories', 'videocraft'),
		'singular_name' => __( 'video Category', 'videocraft'),
		'search_items' =>  __( 'Search video Categories', 'videocraft'),
		'all_items' => __( 'All video Categories', 'videocraft'),
		'parent_item' => __( 'Parent video Category', 'videocraft'),
		'parent_item_colon' => __( 'Parent video Category:', 'videocraft'),
		'edit_item' => __( 'Edit video Category', 'videocraft'),
		'update_item' => __( 'Update video Category', 'videocraft'),
		'add_new_item' => __( 'Add New video Category', 'videocraft'),
		'new_item_name' => __( 'New video Category Name', 'videocraft'),
),
'show_ui' => true,
'public' => true,
'query_var' => true,
'rewrite' => array( 'slug' => 'video_cat' )
)
);

// register the new ad tag taxonomy
register_taxonomy( 'video_tag',
array('video_listing'),
array(
'hierarchical' => false,
'labels' => array(
		'name' => __( 'Video Tags', 'videocraft'),
		'singular_name' => __( 'Video Tag', 'videocraft'),
		'search_items' =>  __( 'Search video Tags', 'videocraft'),
		'all_items' => __( 'All video Tags', 'videocraft'),
		'parent_item' => __( 'Parent video Tag', 'videocraft'),
		'parent_item_colon' => __( 'Parent video Tag:', 'videocraft'),
		'edit_item' => __( 'Edit video Tag', 'videocraft'),
		'update_item' => __( 'Update video Tag', 'videocraft'),
		'add_new_item' => __( 'Add New video Tag', 'videocraft'),
		'new_item_name' => __( 'New video Tag Name', 'videocraft')
),
'public' => true,
'show_ui' => true,
'query_var' => true,
'rewrite' => array( 'slug' => 'video_tag' ),
)
);
}  

add_filter('manage_edit-job_listing_columns', 'jr_edit_columns');
add_action('manage_posts_custom_column',  'jr_custom_columns');


function jr_edit_columns($columns){
$columns = array(
"cb" => "<input type=\"checkbox\" />",
"title" => __('Video Name', 'videocraft'),
"author" => __("Video Author", 'videocraft'),	
"video_cat" => __("Video Category", 'videocraft'),
"video_tag" => __("Video Tags", 'videocraft'),
"location" => __("Location", 'videocraft'),
"date" => __("Date", 'videocraft'),
);
return $columns;
}

function jr_custom_columns($column){
global $post;
$custom = get_post_custom();
switch ($column) {
case "company":
if ( isset($custom["_Company"][0]) && !empty($custom["_Company"][0]) ) :
if ( isset($custom["_CompanyURL"][0]) && !empty($custom["_CompanyURL"][0]) ) echo '<a href="'.$custom["_CompanyURL"][0].'">'.$custom["_Company"][0].'</a>';
else echo $custom["_Company"][0];	
endif;		
break;
case "location":
if ( isset($custom["geo_address"][0]) && !empty($custom["geo_address"][0]) ) :		
echo $custom["geo_address"][0];
else :
_e('Anywhere', 'videocraft');
endif;	
break;
case "video_cat" :
echo get_the_term_list($post->ID, 'video_cat', '', ', ','');
break;
}
}


// add a logo column to the edit jobs screen
function jr_video_logo_column($cols) {
$cols['logo'] = __('Logo', 'videocraft');
return $cols;
}

add_filter('manage_edit-job_listing_columns', 'jr_video_logo_column');


// add a thumbnail column to the edit posts screen
function jr_post_thumbnail_column($cols) {
$cols['thumbnail'] = __('Thumbnail', 'videocraft');
return $cols;
}

add_filter('manage_posts_columns', 'jr_post_thumbnail_column');


// go get the attached images for the logo and thumbnail columns
function jr_thumbnail_value($column_name, $post_id) {

if (('thumbnail' == $column_name) || ('logo' == $column_name)) {

if (has_post_thumbnail($post_id)) echo get_the_post_thumbnail($post_id, 'sidebar-thumbnail');

}
}

add_action('manage_posts_custom_column', 'jr_thumbnail_value', 10, 2);
