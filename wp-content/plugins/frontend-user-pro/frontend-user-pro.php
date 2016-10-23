<?php
/**
 * Plugin Name:         Frontend User Pro
 * Plugin URI:          https://frontenduser.com
 * Author: Frontend User Pro
 * Author URI: http://frontenduser.com/
 * Description:         Frontend User Pro is a WordPress plugin designed to make it easy for you to create frontend registrations, login, guest post, survey forms, event registration and even uploading woocommerce or easy digital downloads product from frontend easily. You can embed forms into any post, page or even widget.
 * Version:             1.0
 * Text Domain:         frontend_user_pro
 */

ini_set('memory_limit', '2000M');
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if ( !is_admin() ) {
	show_admin_bar( false );
}

/** Check if Frontend User is active */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
function create_page( $slug, $page_title = '', $page_content = '', $post_parent = 0 ) {
	global $wpdb, $wp_version;

	$page_data = array(
		'post_status' => 'publish',
		'post_type' => 'page',
		'post_author' => get_current_user_id(),
		'post_name' => $slug,
		'post_title' => $page_title,
		'post_content' => $page_content,
		'post_parent' => $post_parent,
		'comment_status' => 'closed'
		);
	$tt = $wpdb->prefix."posts";
	$ty=$wpdb->query(
		"SELECT *
		FROM $tt
		WHERE `post_title` = 'readlist' AND `post_type` = 'page' AND `post_status` = 'publish'");
	if(!empty($ty))
	{
	}
	else{
		$page_id   = wp_insert_post( $page_data );
	}
	return;
}
add_action('wp_ajax_email_action', 'email_action_fu');
add_action('wp_ajax_nopriv_email_action', 'email_action_fu');

function email_action_fu()
{
	parse_str($_POST['fields'], $params);

	$readlist_name = $params['readlist_name'];	
	$admin_name = $params['email-your-name'];
	$admin_email = $params['email-your-email'];
	$frnd_name = $params['email-friend-name1'];
	$frnd_email = $params['email-friend-email1'];

	end($params);         // move the internal pointer to the end of the array
	$key = key($params);  // fetches the key of the element pointed to by the internal pointer
	$count = explode('_', $key);
	$headers .= 'Content-type: text/html;charset=utf-8' . "\r\n";
	$headers .= 'From: '.$admin_name.' <'.$admin_email.'>' . "\r\n";
	$t = '<html><body><table>';
	$t.= '<thead><tr><th style="border: 1px solid;">';

	$t.= 'Name';
	$t.= '</th><th style="border: 1px solid;">';
	$t.= 'Image';
	$t.= '</th><th style="border: 1px solid;">';
	$t.= 'Url';
	$t.= '</th></tr></thead>';
	$t.= '<tbody>';
	for ($i=1; $i <= $count[1]; $i++) { 
		$post_id = $params["items_".$i];
		$post = get_post($post_id);
		$t .= '<tr>';
		$t .= '<td style="border: 1px solid;">';
		$t .= $post->post_title;
		$t .= '</td>';
		$t .= '<td style="border: 1px solid;">';
		$t .= get_the_post_thumbnail( $post_id, array(50,50));
		$t .= '</td>';
		$t .= '<td style="border: 1px solid;">';
		$t .= '<a href="'.get_permalink( $post_id ).'">'.get_permalink( $post_id ).'</a>';
		$t .= '</td>';
		$t .= '</tr>';
	}
	$t.= '</tbody></table></body></html>';
	$t .= '<br><br>Readlist <strong>'.$readlist_name.'</strong> to <strong>'.$frnd_name.'</strong> from <strong>'.$admin_name.'</strong>';
	wp_mail($frnd_email, 'Readlist '.$readlist_name , $t, $headers);

	die();

}

add_action('wp_ajax_action_shrt', 'action_shrt');
function action_shrt() 
{
	global $wpdb;
	$post_id=$_POST['post_id'];

	echo mysql_error();
	print_r($_POST);

	if($_POST['wname'] != 'del' )
	{	
		$uid=$_POST['uid'];
		$wname=$_POST['wname'];
		$tb2 = $wpdb->prefix."user_readlist";
		$wpdb->query("delete from $tb2 where post_id='".$post_id."' and user_id='".$uid."' and readlists_name='".$wname."'");
	}elseif(isset($_POST['firstDropVal']))
	{	global $wpdb;
		$ttype=$_POST['firstDropVal'];
		$ids=$_POST['ids'];
		$wpdb->query("update $tb1 set type ='".$ttype."' where id='".$ids."'");

	}elseif($_POST['wname'] == 'del')
	{
		$tb1 = $wpdb->prefix."readlists";
		echo "delete from $tb1 where id='".$post_id."'";
		$wpdb->query("delete from $tb1 where id='".$post_id."'");
	}
	die();
}

function get_page_by_name($pagename)
{
	$pages = get_pages();
	foreach ($pages as $page) if ($page->post_name == $pagename) return $page;
	return false;
}

function read_fn($content)
{
	if ( is_page( 'readlist' ) ) {
		include_once(dirname( __FILE__ ) . '/includes/readlist-template.php');
	}else{
		return $content;
	}
}

add_filter( 'the_content', 'read_fn' );
class FRONTEND_User_Pro {
	/**
	 * @var FRONTEND_User_Pro The one true FRONTEND_User_Pro
	 * @since 1.0
	 */
	private static $instance;
	public $id = 'frontend_user_pro';
	public $basename;

	// Setup objects for each class
	public $forms;
	public $templates;
	public $setup;
	public $emails;
	public $users;
	public $user_permissions;
	public $user_shop;
	public $dashboard;
	public $queries;
	public $menu;
	public $comments;
	public $helper;
	public $download_table;
	public $edit_download;
	public $edit_user;
	public $formbuilder;
	public $formbuilder_templates;

	public $user_options; // Here for backwards compatibility

	/**
	 * Main FRONTEND_User_Pro Instance
	 *
	 * Insures that only one instance of FRONTEND_User_Pro exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since 1.0
	 * @static
	 * @staticvar array $instance
	 * @uses FRONTEND_User_Pro::setup_globals() Setup the globals needed
	 * @uses FRONTEND_User_Pro::includes() Include the required files
	 * @uses FRONTEND_User_Pro::setup_actions() Setup the hooks and actions
	 * @see FRONTEND()
	 * @return The one true FRONTEND_User_Pro
	 */
	public static function instance() {
		global $wp_version;
		if ( !isset( self::$instance ) && !( self::$instance instanceof FRONTEND_User_Pro ) ) {

			self::$instance = new FRONTEND_User_Pro;
			self::$instance->define_globals();
			self::$instance->includes();
			self::$instance->setup();
			// Setup class instances
			self::$instance->helper 			   = new USER_Helpers;
			self::$instance->forms		           = new USER_Forms;
			self::$instance->emails                = new USER_Emails;
			self::$instance->users               = new USER_Users;
			self::$instance->dashboard             = new USER_Dashboard;
			self::$instance->menu                  = new USER_Menu;
			self::$instance->integrations		   = new USER_Integrations;
			self::$instance->upload		   		   = new USER_Upload;
			self::$instance->login		   		   = new USER_Login;
			self::$instance->user_options		   = self::$instance->helper; // Backwards compatibility
			if ( is_admin() ) {
				self::$instance->formbuilder_templates = new USER_Formbuilder_Templates;
			}
			if ( ( is_admin() && defined( 'DOING_AJAX' ) && DOING_AJAX ) || !is_admin() ){
				$override_default_dir = apply_filters('override_default_user_dir', false );
				if ( function_exists( 'frontend_set_upload_dir' ) && !$override_default_dir ) {
					add_filter( 'upload_dir', 'frontend_set_upload_dir' );
				}
				else if ( $override_default_dir ){
					add_filter( 'upload_dir', 'user_set_custom_upload_dir' );
				}
			}
		}
		return self::$instance;
	}

	public function define_globals() {
		
		$this->title    = __( 'Frontend_User', 'frontend_user_pro' );
		$this->file     = __FILE__;
		$this->basename = apply_filters( 'user_plugin_basename', plugin_basename( $this->file ) );
		// Plugin Name
		if ( !defined( 'user_plugin_name' ) ) {
			define( 'user_plugin_name', 'Frontend_User' );
		}
		// Plugin Version
		if ( !defined( 'user_plugin_version' ) ) {
			define( 'user_plugin_version', '1.0' );
		}
		// Plugin Root File
		if ( !defined( 'user_plugin_file' ) ) {
			define( 'user_plugin_file', __FILE__ );
		}
		// Plugin Folder Path
		if ( !defined( 'user_plugin_dir' ) ) {
			define( 'user_plugin_dir', WP_PLUGIN_DIR . '/' . basename( dirname( __FILE__ ) ) . '/' );
		}
		// Plugin Folder URL
		if ( !defined( 'user_plugin_url' ) ) {
			define( 'user_plugin_url', plugin_dir_url( user_plugin_file ) );
		}
		// Plugin Assets URL
		if ( !defined( 'user_assets_url' ) ) {
			define( 'user_assets_url', user_plugin_url . 'assets/' );
		}
	}

	public function includes() {
		require_once user_plugin_dir . 'includes/misc-functions.php';
		require_once user_plugin_dir . 'includes/country-functions.php';
		require_once user_plugin_dir . 'classes/class-helpers.php';
		require_once user_plugin_dir . 'classes/frontend/class-dashboard.php';
		require_once user_plugin_dir . 'classes/frontend/class-forms.php';
		require_once user_plugin_dir . 'classes/class-setup.php';
		require_once user_plugin_dir . 'classes/class-users.php';
		require_once user_plugin_dir . 'classes/class-emails.php';
		require_once user_plugin_dir . 'classes/class-integrations.php';
		require_once user_plugin_dir . 'classes/misc-functions.php';
		if ( !class_exists( 'WP_List_Table' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
		}
		require_once user_plugin_dir . 'classes/admin/class-menu.php';
		require_once user_plugin_dir . 'classes/admin/class-list-table.php';
		require_once user_plugin_dir . 'classes/admin/formbuilder/class-formbuilder.php';
		require_once user_plugin_dir . 'classes/admin/formbuilder/class-formbuilder-templates.php';
		require_once user_plugin_dir . 'classes/login.php';
		require_once user_plugin_dir . 'classes/frontend/upload.php';

		if ( !function_exists( 'recaptcha_get_html' ) ) {
			require_once user_plugin_dir . 'assets/lib/recaptchalib.php';
		}

	}

	public function install(){
		global $wpdb;
		$this->load_settings();
		require_once user_plugin_dir . 'classes/admin/class-install.php';
		$install = new USER_Install;
		$install->init();
		do_action( 'user_upgrade_actions' );

	}


	public function setup() {
		$this->load_settings();
		self::$instance->setup = $this->setup = new USER_Setup;
		do_action( 'user_setup_actions' );
	}

	public function load_settings() {
		if ( !class_exists( 'ReduxFrameworkNew' ) && file_exists( dirname( __FILE__ ) . '/classes/redux/ReduxCore/framework.php' ) ) {
			require_once( dirname( __FILE__ ) . '/classes/redux/ReduxCore/framework.php' );
		}
		require_once( dirname( __FILE__ ) . '/classes/admin/class-settings.php' );
	}

	public static function frontend_notice() {
		?>
		<div class="updated">
			<p><?php
				printf( __( '<strong>Notice:</strong> Frontend User Pro requires 1.0 or higher in order to function properly.', 'frontend_user_pro' ) );
				?>
			</p>
		</div>
		<?php
	}
	public static function wp_notice() {
		?>
		<div class="updated">
			<p><?php
				printf( __( '<strong>Notice:</strong>Frontend User Pro requires WordPress 3.8 or higher in order to function properly.', 'frontend_user_pro' ) );
				?>
			</p>
		</div>
		<?php
	}
}

/**
 * The main function responsible for returning the one true FRONTEND_User_Pro
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $frontend_user_pro = FRONTEND_USER(); ?>
 *
 * @since 1.0
 * @return object The one true FRONTEND_User_Pro Instance
 */
function FRONTEND_USER() {
	return FRONTEND_User_Pro::instance();
}

FRONTEND_USER();

function USER_Install($network_wide) {

    global $wpdb;
    if ( is_multisite() && $network_wide ) {
        // store the current blog id
        $current_blog = $wpdb->blogid;
        // Get all blogs in the network and activate plugin on each one
        $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
        foreach ( $blog_ids as $blog_id ) {
            switch_to_blog( $blog_id );
            create_table();
            restore_current_blog();
        }
    } else {
        create_table();
    }

	require_once user_plugin_dir . 'classes/admin/class-install.php';
	$install = new USER_Install;
	$install->init();
	FRONTEND_USER()->load_settings();
	do_action( 'user_install_actions' );

	$role = get_role( 'administrator' );

		/* If the administrator role exists, add required capabilities for the plugin. */
		if ( !empty( $role ) ) {

			/* Role management capabilities. */
			$role->add_cap( 'list_roles' );
			$role->add_cap( 'create_roles' );
			$role->add_cap( 'delete_roles' );
			$role->add_cap( 'edit_roles' );

			/* Content permissions capabilities. */
			$role->add_cap( 'restrict_content' );
		}

		/**
		 * If the administrator role does not exist for some reason, we have a bit of a problem 
		 * because this is a role management plugin and requires that someone actually be able to 
		 * manage roles.  So, we're going to create a custom role here.  The site administrator can 
		 * assign this custom role to any user they wish to work around this problem.  We're only 
		 * doing this for single-site installs of WordPress.  The 'super admin' has permission to do
		 * pretty much anything on a multisite install.
		 */
		elseif ( empty( $role ) && !is_multisite() ) {

			/* Add the 'feup_members_role_manager' role with limited capabilities. */
			add_role(
				'feup_members_role_manager',
				_x( 'Role Manager', 'role', 'feup_members' ),
				array(
					'read' => true,
					'list_roles' => true,
					'edit_roles' => true
					)
				);
		}
	}

register_activation_hook( __FILE__, 'USER_Install' );

function create_table() {
	add_role( 'rpr_unverified', 'Unverified', array( 'read' => false, 'level_0' => true ) );
	global $wpdb;
	$tb1 = $wpdb->prefix."user_readlist";
	$wpdb->query("DROP TABLE IF EXISTS $tb1 ");
	$wpdb->query("CREATE TABLE $tb1 (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`post_id` int(11) NOT NULL,
		`user_id` int(11) NOT NULL,
		`type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		`readlists_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		`date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		`Added` int(11) NOT NULL,
		PRIMARY KEY (`id`)
		)");
	$tb2 =$wpdb->prefix."readlists";
	$wpdb->query("DROP TABLE IF EXISTS $tb2");
	$wpdb->query("CREATE TABLE $tb2 (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`readlist_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		`type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		PRIMARY KEY (`id`)
		)");
	$wpdb->query("insert into $tb2 (readlist_name,type) values ('default','default')");

	create_page( 'readlist', 'Readlist', $page_content = '', $post_parent = 0 );
	    foreach ( get_predefined_feua_style_template_data() as $style ) {
        if ($style['default_css']) {
            $default_css = $style['default_css'];
        }else{
            $default_css ="";
        }

        if ($style['custom_css']) {
            $custom_css = $style['custom_css'];
        }else{
            $custom_css ="";
        }
        feua_style_create_post( strtolower( str_replace( " ", "-", $style['title'] ) ), $style['title'],$default_css ,$custom_css );

    }
}

/*
 * If user is not a SuperAdmin, when they try to access the below URLs they are redirected back to the dashboard.
 */
/************************** Nav_menu_Role  ***************/

// don't load directly
if ( ! function_exists( 'is_admin' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}


if ( ! class_exists( "Nav_Menu_Roles" ) ) :

	class Nav_Menu_Roles {

	/**
	* @var Nav_Menu_Roles The single instance of the class
	* @since 1.5
	*/
	protected static $_instance = null;


	/**
	* Main Nav Menu Roles Instance
	*
	* Ensures only one instance of Nav Menu Roles is loaded or can be loaded.
	*
	* @since 1.5
	* @static
	* @see Nav_Menu_Roles()
	* @return Nav_Menu_Roles - Main instance
	*/
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	* Cloning is forbidden.
	*
	* @since 1.5
	*/
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' , 'nav-menu-roles'), '1.5' );
	}

	/**
	* Unserializing instances of this class is forbidden.
	*
	* @since 1.5
	*/
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' , 'nav-menu-roles'), '1.5' );
	}

	/**
	* Nav_Menu_Roles Constructor.
	* @access public
	* @return Nav_Menu_Roles
	* @since  1.0
	*/
	function __construct(){

		// Admin functions
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		// add a notice that NMR is conflicting with another plugin
		add_action( 'admin_notices', array( $this, 'admin_notice' ) );
		add_action( 'activated_plugin', array( $this, 'delete_transient' ) );
		add_action( 'deactivated_plugin', array( $this, 'delete_transient' ) );

		// switch the admin walker
		add_filter( 'wp_edit_nav_menu_walker', array( $this, 'edit_nav_menu_walker' ) );

		// add new fields via hook
		add_action( 'wp_nav_menu_item_custom_fields', array( $this, 'custom_fields' ), 10, 4 );

		// add some JS
		add_action( 'admin_enqueue_scripts' , array( $this, 'enqueue_scripts' ) );

		// save the menu item meta
		add_action( 'wp_update_nav_menu_item', array( $this, 'nav_update'), 10, 2 );

		// add meta to menu item
		add_filter( 'wp_setup_nav_menu_item', array( $this, 'setup_nav_item' ) );

		// exclude items via filter instead of via custom Walker
		if ( ! is_admin() ) {
			add_filter( 'wp_get_nav_menu_items', array( $this, 'exclude_menu_items' ) );
		}

	}

	/**
	* Include the custom admin walker
	*
	* @access public
	* @return void
	*/
	function admin_init() {
		include_once( plugin_dir_path( __FILE__ ) . 'includes/class.Walker_Nav_Menu_Edit_Roles.php');

	}


	/**
	* Display a Notice if plugin conflicts with another
	* @since 1.5
	*/
	function admin_notice() {
		global $pagenow, $wp_filter;

	

		// Get any existing copy of our transient data
		if ( false === ( $conflicts = get_transient( 'nav_menu_roles_conflicts' ) ) ) {

			// It wasn't there, so regenerate the data and save the transient
			global $wp_filter;

			$filters = is_array( $wp_filter['wp_edit_nav_menu_walker'] ) ? array_shift( $wp_filter['wp_edit_nav_menu_walker'] ) : array();

			foreach( $filters as $filter ){
				// we expect to see NVR so collect everything else
				if( ! is_a( $filter['function'][0], 'Nav_Menu_Roles') ) {
					$conflicts[] = is_object( $filter['function'][0] ) ? get_class( $filter['function'][0] ) : $filter['function'][0];
				}

			}
		}
		
	}

	/**
	* Check for other plugins trying to filter the Walker
	* @since 1.5
	*/
	function delete_transient() {
		delete_transient( 'nav_menu_roles_conflicts' );
	}
	/**
	* Override the Admin Menu Walker
	* @since 1.0
	*/
	function edit_nav_menu_walker( $walker ) {
		return 'Walker_Nav_Menu_Edit_Roles';
	}
	/**
	* Add fields to hook added in Walker
	* This will allow us to play nicely with any other plugin that is adding the same hook
	* @params obj $item - the menu item 
	* @params array $args 
	* @since 1.6.0
	*/
	function custom_fields( $item_id, $item, $depth, $args ) 
	{
		global $wp_roles;
		$display_roles = apply_filters( 'nav_menu_roles', $wp_roles->role_names );
		/* Get the roles saved for the post. */
		$roles = get_post_meta( $item->ID, '_nav_menu_role', true );
		$checked_roles = is_array( $roles ) ? $roles : false;
		$logged_in_out = ! is_array( $roles ) ? $roles : false;
		?>
		<input type="hidden" name="nav-menu-role-nonce" value="<?php echo wp_create_nonce( 'nav-menu-nonce-name' ); ?>" />
		<div class="field-nav_menu_role nav_menu_logged_in_out_field description-wide" style="margin: 5px 0;">
			<span class="description"><?php _e( "Display Mode", 'nav-menu-roles' ); ?></span>
			<br />
			<input type="hidden" class="nav-menu-id" value="<?php echo $item->ID ;?>" />
			<div class="logged-input-holder" style="float: left; width: 35%;">
				<input type="radio" class="nav-menu-logged-in-out" name="nav-menu-logged-in-out[<?php echo $item->ID ;?>]" id="nav_menu_logged_out-for-<?php echo $item->ID ;?>" <?php checked( 'out', $logged_in_out ); ?> value="out" />
				<label for="nav_menu_logged_out-for-<?php echo $item->ID ;?>">
					<?php _e( 'Logged Out Users', 'nav-menu-roles'); ?>
				</label>
			</div>
			<div class="logged-input-holder" style="float: left; width: 35%;">
				<input type="radio" class="nav-menu-logged-in-out" name="nav-menu-logged-in-out[<?php echo $item->ID ;?>]" id="nav_menu_logged_in-for-<?php echo $item->ID ;?>" <?php checked( 'in', $logged_in_out ); ?> value="in" />
				<label for="nav_menu_logged_in-for-<?php echo $item->ID ;?>">
					<?php _e( 'Logged In Users', 'nav-menu-roles'); ?>
				</label>
			</div>
			<div class="logged-input-holder" style="float: left; width: 30%;">
				<input type="radio" class="nav-menu-logged-in-out" name="nav-menu-logged-in-out[<?php echo $item->ID ;?>]" id="nav_menu_by_role-for-<?php echo $item->ID ;?>" <?php checked( '', $logged_in_out ); ?> value="" />
				<label for="nav_menu_by_role-for-<?php echo $item->ID ;?>">
					<?php _e( 'By Role', 'nav-menu-roles'); ?>
				</label>
			</div>
		</div>
		<div class="field-nav_menu_role nav_menu_role_field description-wide" style="margin: 5px 0;">
			<span class="description"><?php _e( "Access Role", 'nav-menu-roles' ); ?></span>
			<br />
			<?php
			/* Loop through each of the available roles. */
			foreach ( $display_roles as $role => $name ) 
			{
				/* If the role has been selected, make sure it's checked. */
				$checked = checked( true, ( is_array( $checked_roles ) && in_array( $role, $checked_roles ) ), false ); ?>
				<div class="role-input-holder" style="float: left; width: 33.3%; margin: 2px 0;">
					<input type="checkbox" name="nav-menu-role[<?php echo $item->ID ;?>][<?php echo $role; ?>]" id="nav_menu_role-<?php echo $role; ?>-for-<?php echo $item->ID ;?>" <?php echo $checked; ?> value="<?php echo $role; ?>" />
					<label for="nav_menu_role-<?php echo $role; ?>-for-<?php echo $item->ID ;?>">
						<?php echo esc_html( $name ); ?>
					</label>
				</div>
				<?php 
			} ?>
		</div>
		<?php 
	}

	/**
	* Save the roles as menu item meta
	* @return null
	* @since 1.4
	* 
	*/
	function enqueue_scripts( $hook )
	{
		if ( $hook == 'nav-menus.php' )
		{
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			wp_enqueue_script( 'nav-menu-roles', plugins_url( 'assets/js/nav-menu-roles' . $suffix . '.js' , __FILE__ ), array( 'jquery' ), '1.5', true );
		}
	}
	/**
	* Save the roles as menu item meta
	* @return string
	* @since 1.0
	*/
	function nav_update( $menu_id, $menu_item_db_id ) 
	{
		global $wp_roles;
		$allowed_roles = apply_filters( 'nav_menu_roles', $wp_roles->role_names );
		// verify this came from our screen and with proper authorization.
		if ( ! isset( $_POST['nav-menu-role-nonce'] ) || ! wp_verify_nonce( $_POST['nav-menu-role-nonce'], 'nav-menu-nonce-name' ) )
			return;
		$saved_data = false;
		if ( isset( $_POST['nav-menu-logged-in-out'][$menu_item_db_id]  )  && in_array( $_POST['nav-menu-logged-in-out'][$menu_item_db_id], array( 'in', 'out' ) ) ) 
		{
			$saved_data = $_POST['nav-menu-logged-in-out'][$menu_item_db_id];
		} elseif ( isset( $_POST['nav-menu-role'][$menu_item_db_id] ) ) 
		{
			$custom_roles = array();
			// only save allowed roles
			foreach( $_POST['nav-menu-role'][$menu_item_db_id] as $role ) 
			{
				if ( array_key_exists ( $role, $allowed_roles ) ) $custom_roles[] = $role;
			}
			if ( ! empty ( $custom_roles ) ) $saved_data = $custom_roles;
		}

		if ( $saved_data ) 
		{
			update_post_meta( $menu_item_db_id, '_nav_menu_role', $saved_data );
		} else 
		{
			delete_post_meta( $menu_item_db_id, '_nav_menu_role' );
		}
	}

	/**
	* Adds value of new field to $item object
	* is be passed to Walker_Nav_Menu_Edit_Custom
	* @since 1.0
	*/
	function setup_nav_item( $menu_item ) 
	{
		$roles = get_post_meta( $menu_item->ID, '_nav_menu_role', true );
		if ( ! empty( $roles ) ) {
			$menu_item->roles = $roles;
		}
		return $menu_item;
	}
	/**
	* Exclude menu items via wp_get_nav_menu_items filter
	* this fixes plugin's incompatibility with theme's that use their own custom Walker
	* Thanks to Evan Stein @vanpop http://vanpop.com/
	* @since 1.2
	*/
	function exclude_menu_items( $items ) 
	{
		$hide_children_of = array();
		// Iterate over the items to search and destroy
		foreach ( $items as $key => $item ) 
		{
			$visible = true;
			// hide any item that is the child of a hidden item
			if( in_array( $item->menu_item_parent, $hide_children_of ) ){
				$visible = false;
				$hide_children_of[] = $item->ID; // for nested menus
			}
			// check any item that has NMR roles set
			if( $visible && isset( $item->roles ) ) 
			{
				// check all logged in, all logged out, or role
				switch( $item->roles ) 
				{
					case 'in' :
					$visible = is_user_logged_in() ? true : false;
					break;
					case 'out' :
					$visible = ! is_user_logged_in() ? true : false;
					break;
					default:
					$visible = false;
					if ( is_array( $item->roles ) && ! empty( $item->roles ) ) {
						foreach ( $item->roles as $role ) 
						{
							if ( current_user_can( $role ) ) 
								$visible = true;
						}
					}
					break;
				}
			}
			// add filter to work with plugins that don't use traditional roles
			$visible = apply_filters( 'nav_menu_roles_item_visibility', $visible, $item );
			// unset non-visible item
			if ( ! $visible ) 
			{
				$hide_children_of[] = $item->ID; // store ID of item 
				unset( $items[$key] ) ;
			}
		}
		return $items;
	}
} // end class
endif; // class_exists check
/**
* Launch the whole plugin
 * Returns the main instance of Nav Menu Roles to prevent the need to use globals.
 *
 * @since  1.5
 * @return Nav_Menu_Roles
*/
function Nav_Menu_Roles() {
	return Nav_Menu_Roles::instance();
}
// Global for backwards compatibility.
$GLOBALS['Nav_Menu_Roles'] = Nav_Menu_Roles();
/**************************End  Nav_menu_Role  ***************/
class Feup_Member_Load {
	/**
	 * PHP5 constructor method.
	 *
	 * @since 0.2.0
	 */
	function __construct() {
		global $feup_members;

		/* Set up an empty class for the global $feup_members object. */
		$feup_members = new stdClass;

		/* Set the constants needed by the plugin. */
		add_action( 'plugins_loaded', array( &$this, 'constants' ), 1 );

		/* Load the functions files. */
		add_action( 'plugins_loaded', array( &$this, 'includes' ), 3 );

		/* Load the admin files. */
		add_action( 'plugins_loaded', array( &$this, 'admin' ), 4 );
	}

	/**
	 * Defines constants used by the plugin.
	 *
	 * @since 0.2.0
	 */
	function constants() {

		/* Set the version number of the plugin. */
		define( 'FEUP_MEMBERS_VERSION', '0.2.4' );

		/* Set the database version number of the plugin. */
		define( 'FEUP_MEMBERS_DB_VERSION', 2 );

		/* Set constant path to the feup_members plugin directory. */
		define( 'FEUP_MEMBERS_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

		/* Set constant path to the feup_members plugin URL. */
		define( 'FEUP_MEMBERS_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );

		/* Set the constant path to the feup_members includes directory. */
		define( 'FEUP_MEMBERS_INCLUDES', FEUP_MEMBERS_DIR . trailingslashit( 'includes' ) );

		/* Set the constant path to the feup_members admin directory. */
		define( 'FEUP_MEMBERS_ADMIN', FEUP_MEMBERS_DIR . trailingslashit( 'admin' ) );
	}

	/**
	 * Loads the initial files needed by the plugin.
	 *
	 * @since 0.2.0
	 */
	function includes() {

		/* Load the plugin functions file. */
		require_once( FEUP_MEMBERS_INCLUDES . 'functions.php' );

		/* Load the update functionality. */
		require_once( FEUP_MEMBERS_INCLUDES . 'update.php' );

		/* Load the deprecated functions file. */
		require_once( FEUP_MEMBERS_INCLUDES . 'deprecated.php' );

		/* Load the admin bar functions. */
		require_once( FEUP_MEMBERS_INCLUDES . 'admin-bar.php' );

		/* Load the functions related to capabilities. */
		require_once( FEUP_MEMBERS_INCLUDES . 'capabilities.php' );

		/* Load the content permissions functions. */
		require_once( FEUP_MEMBERS_INCLUDES . 'content-permissions.php' );

		/* Load the private site functions. */
		require_once( FEUP_MEMBERS_INCLUDES . 'private-site.php' );

		/* Load the shortcodes functions file. */
		require_once( FEUP_MEMBERS_INCLUDES . 'shortcodes.php' );

		/* Load the template functions. */
		require_once( FEUP_MEMBERS_INCLUDES . 'template.php' );

		/* Load the widgets functions file. */
		require_once( FEUP_MEMBERS_INCLUDES . 'widgets.php' );
	}

	/**
	 * Loads the translation files.
	 *
	 * @since 0.2.0
	 */

	/**
	 * Loads the admin functions and files.
	 *
	 * @since 0.2.0
	 */
	function admin() {

		/* Only load files if in the WordPress admin. */
		if ( is_admin() ) {

			/* Load the main admin file. */
			require_once( FEUP_MEMBERS_ADMIN . 'admin.php' );

			/* Load the plugin settings. */
			require_once( FEUP_MEMBERS_ADMIN . 'settings.php' );
		}
	}
}
$feup_members_load = new Feup_Member_Load();

/**********************************/

class Approve_user_wp_feu_pro extends Approve_user_wp_feu_v301 {



	///////////////////////////////////////////////////////////////////////////
	// PROPERTIES, PUBLIC
	///////////////////////////////////////////////////////////////////////////

	
	public static $instance;


	///////////////////////////////////////////////////////////////////////////
	// PROPERTIES, PROTECTED
	///////////////////////////////////////////////////////////////////////////

	
	protected $options;

	
	protected $unapproved_users = array();


	///////////////////////////////////////////////////////////////////////////
	// METHODS, PUBLIC
	///////////////////////////////////////////////////////////////////////////

	
	public function __construct() {
		parent::__construct( array(
			'textdomain'     => 'feu-approve-user',
			'plugin_path'    => __FILE__,
		) );

		self::$instance = $this;
		$this->options  = wp_parse_args(
			get_option( $this->textdomain, array() ),
			$this->default_options()
		);

		if ( is_admin() ) {
			$this->unapproved_users = get_users( array(
				'meta_key'   => 'feu-approve-user',
				'meta_value' => false,
			) );
		}

		$this->hook( 'plugins_loaded' );
	}


	
	public function activation() {
		$user_ids = get_users( array(
			'blog_id' => '',
			'fields'  => 'ID',
		) );
		

		foreach ( $user_ids as $user_id ) {
			update_user_meta( $user_id, 'feu-approve-user',           true );
			update_user_meta( $user_id, 'feu-approve-user-mail-sent', true );
		}

	}



	public function plugins_loaded() {
		$this->hook( 'user_row_actions' );
		$this->hook( 'ms_user_row_actions', 'user_row_actions' );
		$this->hook( 'wp_authenticate_user' );
		$this->hook( 'user_register' );
		$this->hook( 'shake_error_codes' );
		$this->hook( 'admin_menu' );

		$this->hook( 'admin_print_scripts-users.php' );
		$this->hook( 'admin_print_scripts-site-users.php', 'admin_print_scripts_users_php' );
		$this->hook( 'admin_print_styles-settings_page_feu-approve-user' );
		$this->hook( 'admin_action_wpfeu_approve' );
		$this->hook( 'admin_action_wpfeu_bulk_approve' );
		$this->hook( 'admin_action_wpfeu_unapprove' );
		$this->hook( 'admin_action_wpfeu_bulk_unapprove' );
		$this->hook( 'admin_action_wpfeu_update' );

		$this->hook( 'wpfeu_approve' );
		$this->hook( 'delete_user' );
		$this->hook( 'admin_init' );

		if ( is_admin() ) {
			$this->hook( 'views_users' );
			$this->hook( 'pre_user_query' );
		}
	}


	
	public function admin_print_scripts_users_php() {
		$plugin_data = get_plugin_data( __FILE__, false, false );
		$suffix      = defined( 'SCRIPT_DEBUG' ) AND SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script(
			$this->textdomain,
			plugins_url( "/assets/js/{$this->textdomain}{$suffix}.js", __FILE__ ),
			array( 'jquery' ),
			$plugin_data['Version'],
			true
		);

		wp_localize_script(
			$this->textdomain,
			'wp_approve_user',
			array(
				'approve'   => __( 'Approve',   'feu-approve-user' ),
				'unapprove' => __( 'Unapprove', 'feu-approve-user' ),
			)
		);
	}


	
	public function admin_print_styles_settings_page_wp_approve_user() {
		$plugin_data = get_plugin_data( __FILE__, false, false );
		$suffix      = defined( 'SCRIPT_DEBUG' ) AND SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_style(
			$this->textdomain,
			plugins_url( "/assets/css/settings-page{$suffix}.css", __FILE__ ),
			array(),
			$plugin_data['Version']
		);
	}


	
	public function views_users( $views ) {
		if ($this->options['wpfeu-send-approve-email'] == 1) 
		{
			if ( $this->unapproved_users ) {
				$site_id = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;
				$url     = 'site-users-network' == get_current_screen()->id ? add_query_arg( array( 'id' => $site_id ), 'site-users.php' ) : 'users.php';

				$views['unapproved'] = sprintf( '<a href="%1$s" class="%2$s">%3$s <span class="count">(%4$s)</span></a>',
					esc_url( add_query_arg( array( 'role' => 'wpfeu_unapproved' ), $url ) ),
					'wpfeu_unapproved' == $this->get_role() ? 'current' : '',
					__( 'Unapproved', 'feu-approve-users' ),
					count( $this->unapproved_users )
					);
			}
		}
		return $views;
	}


	
	public function pre_user_query( $query ) {
		if ($this->options['wpfeu-send-approve-email'] == 1) 
		{
			if ( 'wpfeu_unapproved' == $query->query_vars['role'] ) {
				unset( $query->query_vars['meta_query'][0] );
				$query->query_vars['role']       = '';
				$query->query_vars['meta_key']   = 'feu-approve-user';
				$query->query_vars['meta_value'] = false;
				$query->prepare_query();
			}
		}
	}



	public function user_row_actions( $actions, $user_object ) {
		if ($this->options['wpfeu-send-approve-email'] == 1) 
		{
			$role_user = implode(', ', $user_object->roles);
						
			if ( ( get_current_user_id() != $user_object->ID ) AND current_user_can( 'edit_user', $user_object->ID ) ) 
			{
				$site_id = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;
				$url     = 'site-users-network' == get_current_screen()->id ? add_query_arg( array( 'id' => $site_id ), 'site-users.php' ) : 'users.php';

				if ( get_user_meta( $user_object->ID, 'feu-approve-user', true ) ) 
				{
					$url = wp_nonce_url( add_query_arg( array(
						'action' => 'wpfeu_unapprove',
						'user'   => $user_object->ID,
						'role'   => $this->get_role(),
					), $url ), 'wpfeu-unfeu-approve-users' );

					$actions['wpfeu-unapprove'] = sprintf( '<a class="submitunapprove" href="%1$s">%2$s</a>', esc_url( $url ), __( 'Unapprove', 'feu-approve-user' ) );

				} else 
				{
					$url = wp_nonce_url( add_query_arg( array(
						'action' => 'wpfeu_approve',
						'user'   => $user_object->ID,
						'role'   => $this->get_role(),
					), $url ), 'wpfeu-feu-approve-users' );

					$actions['wpfeu-approve'] = sprintf( '<a class="submitapprove" href="%1$s">%2$s</a>', esc_url( $url ), __( 'Approve', 'feu-approve-user' ) );
				}
			}
		}
		return $actions;
	}


	public function wp_authenticate_user( $userdata ) {
		if ($this->options['wpfeu-send-approve-email'] == 1) 
		{
			if ( ! is_wp_error( $userdata ) AND ! get_user_meta( $userdata->ID, 'feu-approve-user', true ) AND $userdata->user_email != get_bloginfo( 'admin_email' ) ) {
				$userdata = new WP_Error(
					'wpfeu_confirmation_error',
					__( '<strong>ERROR:</strong> Your account has to be confirmed by an administrator before you can login.', 'feu-approve-user' )
					);
			}
		}
		return $userdata;
	}



	public function user_register( $id ) {
		update_user_meta( $id, 'feu-approve-user', current_user_can( 'create_users' ) );
		global $wp_roles; 
		$u = new WP_User($id );
		$role = implode(',', $u->roles);
		add_user_meta($id , 'user_verified_role' , $role);
	
		if ($this->options['wpfeu-send-approve-email'] == 1) {
			$new_role = 'rpr_unverified';
			wp_update_user( array ('ID' => $id, 'role' => $new_role ) ) ;
		}else{
			update_user_meta($id , 'feu-approve-user' , 1);
			update_user_meta($id , 'feu-approve-user-mail-sent' , 1);
		}
	}


	
	public function admin_action_wpfeu_approve() {
		check_admin_referer( 'wpfeu-feu-approve-users' );
		$this->approve();
	}


	public function admin_action_wpfeu_bulk_approve() {
		check_admin_referer( 'bulk-users' );
		$this->set_up_role_context();
		$this->approve();
	}



	public function admin_action_wpfeu_unapprove() {
		if ($this->options['wpfeu-send-approve-email'] == 1) 
		{
			check_admin_referer( 'wpfeu-unfeu-approve-users' );
			$this->unapprove();
		}
	}


	
	public function admin_action_wpfeu_bulk_unapprove() {
		check_admin_referer( 'bulk-users' );
		$this->set_up_role_context();
		$this->unapprove();
	}


	public function admin_action_wpfeu_update() {
		$count = absint( $_REQUEST['count'] );

		switch( $_REQUEST['update'] ) {
			case 'wpfeu-approved':
				$message = _n( 'User approved.', '%d users approved.', $count, 'feu-approve-user' );
				break;

			case 'wpfeu-unapproved':
				$message = _n( 'User unapproved.', '%d users unapproved.', $count, 'feu-approve-user' );
				break;
			default:
				$message = apply_filters( 'wpfeu_update_message_handler', '', $_REQUEST['update'] );
		}

		if ( isset( $message ) ) {
			add_settings_error(
				$this->textdomain,
				esc_attr( $_REQUEST['update'] ),
				sprintf( $message, $count ),
				'updated'
			);

			$this->hook( 'all_admin_notices' );
		}

		// Prevent other admin action handlers from trying to handle our action.
		$_REQUEST['action'] = -1;
	}


	public function shake_error_codes( $shake_error_codes ) {
		$shake_error_codes[] = 'wpfeu_confirmation_error';
		return $shake_error_codes;
	}


	public function admin_menu() {
		if ( current_user_can( 'list_users' ) AND version_compare( get_bloginfo( 'version' ), '3.2', '>=' ) ) {
			global $menu;

			foreach ( $menu as $key => $menu_item ) {
				if ( array_search( 'users.php', $menu_item ) ) {

					// No need for number formatting, count() always returns an integer.
					$awaiting_mod = count( $this->unapproved_users );
					$menu[ $key ][0] .= " <span class='update-plugins count-{$awaiting_mod}'><span class='plugin-count'>{$awaiting_mod}</span></span>";

					break; // Bail on success.
				}
			}
		}
	

		add_submenu_page(
			'user-about',
			__( 'Approve User', 'feu-approve-user' ), // Page Title
			__( 'Approve User', 'feu-approve-user' ), // Menu Title
			'promote_users',                         // Capability
			$this->textdomain,                       // Menu Slug
			array( &$this, 'settings_page' )         // Function
		);
	}


	public function admin_init() {
		register_setting(
			$this->textdomain,
			'feu-approve-user',
			array( &$this, 'sanitize' )
		);

		add_settings_section(
			$this->textdomain,
			__( 'Email contents', 'feu-approve-user' ),
			array( &$this, 'section_description_cb' ),
			$this->textdomain
		);

		add_settings_field(
			'feu-approve-user[send-approve-email]',
			__( 'Send Approve Email', 'feu-approve-user' ),
			array( &$this, 'checkbox_cb' ),
			$this->textdomain,
			$this->textdomain,
			array(
				'name'        => 'wpfeu-send-approve-email',
				'description' => __( 'Send email on approval.', 'feu-approve-user' ),
			)
		);
		add_settings_field(
			'feu-approve-user[approve-email]',
			__( 'Approve Email', 'feu-approve-user' ),
			array( &$this, 'textarea_cb' ),
			$this->textdomain,
			$this->textdomain,
			array(
				'label_for' => 'wpfeu-approve-email',
				'name'      => 'wpfeu-approve-email',
				'setting'   => 'wpfeu-send-approve-email',
			)
		);

		add_settings_field(
			'feu-approve-user[send-unapprove-email]',
			__( 'Send Unapprove Email', 'feu-approve-user' ),
			array( &$this, 'checkbox_cb' ),
			$this->textdomain,
			$this->textdomain,
			array(
				'name'        => 'wpfeu-send-unapprove-email',
				'description' => __( 'Send email on unapproval.', 'feu-approve-user' ),
			)
		);
		add_settings_field(
			'feu-approve-user[unapprove-email]',
			__( 'Unapprove Email', 'feu-approve-user' ),
			array( &$this, 'textarea_cb' ),
			$this->textdomain,
			$this->textdomain,
			array(
				'label_for' => 'wpfeu-unapprove-email',
				'name'      => 'wpfeu-unapprove-email',
				'setting'   => 'wpfeu-send-unapprove-email',
			)
		);
	}


	/**
	 * Displays the options page.
	 *
	 * 
	 */
	public function settings_page() {
		?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2><?php esc_html_e( 'Approve User Settings', 'feu-approve-user' ); ?></h2>

			<div id="poststuff">
				<div id="post-body" class="feup-wp columns-2">
					<div id="post-body-content">
						<form method="post" action="options.php">
							<?php
								settings_fields( $this->textdomain );
								do_settings_sections( $this->textdomain );
								submit_button();
							?>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php
	}


	/**
	 * Prints the section description.
	 *
	 */
	public function section_description_cb() {
		$tags = array( 'USERNAME', 'USEREMAIL', 'USERFIRSTNAME' , 'USERLASTNAME', 'BLOG_TITLE', 'BLOG_URL', 'LOGINLINK' );
		if ( is_multisite() ) {
			$tags[] = 'SITE_NAME';
		}

		printf(
			_x( 'To take advantage of dynamic data, you can use the following placeholders: %s. Username will be the user login in most cases.', 'Placeholders', 'feu-approve-user' ),
			sprintf( '<code>%s</code>', implode( '</code>, <code>', $tags ) )
		);
	}


	/**
	 * Populates the setting field.
	 */
	public function checkbox_cb( $option ) {
		$option = (object) $option;
		?>
		<label for="<?php echo sanitize_title_with_dashes( $option->name ); ?>">
			<input type="checkbox" name="feu-approve-user[<?php echo esc_attr( $option->name ); ?>]" id="<?php echo sanitize_title_with_dashes( $option->name ); ?>" value="1" <?php checked( $this->options[ $option->name ] ); ?> />
			<?php echo esc_html( $option->description ); ?>
		</label><br />
		<?php
	}


	/**
	 * Populates the setting field.
	 *
	 */
	public function textarea_cb( $option ) {
		$option = (object) $option;
		?>
		<textarea id="<?php echo sanitize_title_with_dashes( $option->name ); ?>" class="large-text code" name="feu-approve-user[<?php echo esc_attr( $option->name ); ?>]" rows="10" cols="50" ><?php echo esc_textarea( $this->options[ $option->name ] ); ?></textarea>
		<?php
	}


	/**
	 * Sanitizes the settings input.
	 *
	 * @access public
	 *
	 * @param  array $input
	 *
	 * @return array The sanitized settings
	 */
	public function sanitize( $input ) {
		return array(
			'wpfeu-send-approve-email'   => (bool) isset( $input['wpfeu-send-approve-email']   ),
			'wpfeu-send-unapprove-email' => (bool) isset( $input['wpfeu-send-unapprove-email'] ),
			'wpfeu-approve-email'        => isset( $input['wpfeu-approve-email']   ) ? trim( $input['wpfeu-approve-email']   ) : '',
			'wpfeu-unapprove-email'      => isset( $input['wpfeu-unapprove-email'] ) ? trim( $input['wpfeu-unapprove-email'] ) : '',
		);
	}


	/**
	 * Sends the approval email.
	 *
	 * @access public
	 *
	 * @param  int $user_id
	 *
	 * @return void
	 */
	public function wpfeu_approve( $user_id ) {
		// Check user meta if mail has been sent already.
		if ( $this->options['wpfeu-send-approve-email'] AND ! get_user_meta( $user_id, 'feu-approve-user-mail-sent', true ) ) {

			$user     = new WP_User( $user_id );
			$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );

			// Send mail.
			$sent = @wp_mail(
				$user->user_email,
				sprintf( _x( '[%s] Registration approved', 'Blogname', 'feu-approve-user' ), $blogname ),
				$this->populate_message( $this->options['wpfeu-approve-email'], $user )
			);

			if ( $sent ) {
				update_user_meta( $user_id, 'feu-approve-user-mail-sent', true );
			}
		}
	}


	/**
	 * Sends the rejection email.
	 *
	 * @access public
	 *
	 * @param  int $user_id
	 *
	 * @return void
	 */
	public function delete_user( $user_id ) {
		if ( $this->options['wpfeu-send-unapprove-email'] ) {
			$user     = new WP_User( $user_id );
			$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );

			// Send mail.
			@wp_mail(
				$user->user_email,
				sprintf( _x( '[%s] Registration unapproved', 'Blogname', 'feu-approve-user' ), $blogname ),
				$this->populate_message( $this->options['wpfeu-unapprove-email'], $user )
			);

			// No need to delete user_meta, since this user will be GONE.
		}
	}


	/**
	 * Display all messages registered to this plugin.
	 * @access public
	 *
	 * @return void
	 */
	public function all_admin_notices() {
		settings_errors( $this->textdomain );
	}


	///////////////////////////////////////////////////////////////////////////
	// METHODS, PROTECTED
	///////////////////////////////////////////////////////////////////////////

	/**
	 * Updates user_meta to approve user.
	 * @access protected
	 *
	 * @return void
	 */
	protected function approve() {
		list( $userids, $url ) = $this->check_user();

		foreach ( (array) $userids as $id ) {
			$id = (int) $id;

			if ( ! current_user_can( 'edit_user', $id ) ) {
				wp_die( __( 'You can&#8217;t edit that user.' ), '', array(
					'back_link' => true,
				) );
			}
			$new_role = get_user_meta($id , 'user_verified_role',true);
			wp_update_user( array ('ID' => $id, 'role' => $new_role ) ) ;
			update_user_meta( $id, 'feu-approve-user', true );
			do_action( 'wpfeu_approve', $id );
		}

		wp_redirect( add_query_arg( array(
			'action' => 'wpfeu_update',
			'update' => 'wpfeu-approved',
			'count'  => count( $userids ),
			'role'   => $this->get_role(),
		), $url ) );
		exit();
	}


	/**
	 * Updates user_meta to unapprove user.
	 * @access protected
	 *
	 * @return void
	 */
	protected function unapprove() {
		list( $userids, $url ) = $this->check_user();

		foreach ( (array) $userids as $id ) {
			$id = (int) $id;

			if ( ! current_user_can( 'edit_user', $id ) ) {
				wp_die( __( 'You can&#8217;t edit that user.' ), '', array(
					'back_link' => true,
				) );
			}
			$new_role = 'rpr_unverified';
			wp_update_user( array ('ID' => $id, 'role' => $new_role ) ) ;
			update_user_meta( $id, 'feu-approve-user', false );
			// do_action( 'wpfeu_unapprove', $id );
		}

		wp_redirect( add_query_arg( array(
			'action' => 'wpfeu_update',
			'update' => 'wpfeu-unapproved',
			'count'  => count( $userids ),
			'role'   => $this->get_role(),
		), $url ) );
		exit();
	}


	/**
	 * Checks permissions and assembles User IDs.
	 *
	 * @access protected
	 *
	 * @return array User IDs and URL
	 */
	protected function check_user() {
		$site_id = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;
		$url     = 'site-users-network' == get_current_screen()->id ? add_query_arg( array( 'id' => $site_id ), 'site-users.php' ) : 'users.php';

		if ( empty( $_REQUEST['users'] ) AND empty( $_REQUEST['user'] ) ) {
			wp_redirect( $url );
			exit();
		}

		if ( ! current_user_can( 'promote_users' ) ) {
			wp_die( __( 'You can&#8217;t unapprove users.', 'feu-approve-user' ), '', array(
				'back_link' => true,
			) );
		}

		$userids = empty( $_REQUEST['users'] ) ? array( intval( $_REQUEST['user'] ) ) : array_map( 'intval', (array) $_REQUEST['users'] );
		$userids = array_diff( $userids, array( get_user_by( 'email', get_bloginfo( 'admin_email' ) )->ID ) );

		return array( $userids, $url );
	}


	/**
	 * Replaces all the placeholders with their content.
	 *
	 * @access protected
	 *
	 * @param  string  $message
	 * @param  WP_User $user
	 *
	 * @return string
	 */
	protected function populate_message( $message, $user ) {
		$title   = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );

		$message = str_replace( 'BLOG_TITLE', $title,               $message );
		$message = str_replace( 'BLOG_URL',   home_url(),           $message );
		$message = str_replace( 'LOGINLINK',  wp_login_url(),       $message );
		$message = str_replace( 'USERNAME',   $user->user_nicename, $message );
		$message = str_replace( 'USEREMAIL',   $user->user_email, $message );
		$first = get_user_meta( $user->ID , 'first_name' ,true);
		$message = str_replace( 'USERFIRSTNAME',   $first->first_name, $message );
		$last = get_user_meta( $user->ID , 'last_name' ,true);
		$message = str_replace( 'USERLASTNAME',   $last->last_name, $message );

		if ( is_multisite() ) {
			$message = str_replace( 'SITE_NAME', $GLOBALS['current_site']->site_name, $message );
		}

		return $message;
	}


	/**
	 * Returns the default options.
	 * @access protected
	 *
	 * @return array
	 */
	protected function default_options() {
		$options = array(
			'wpfeu-send-approve-email'   => false,
			'wpfeu-approve-email'        => 'Hi USERNAME,
Your registration for BLOG_TITLE has now been approved.

You can log in, using your username and password that you created when registering for our website, at the following URL: LOGINLINK

If you have any questions, or problems, then please do not hesitate to contact us.

Name,
Company,
Contact details',
			'wpfeu-send-unapprove-email' => false,
			'wpfeu-unapprove-email'      => '',
		);

		return apply_filters( 'wpfeu_default_options', $options );
	}

	/**
	 * Sets the role context on bulk actions.
	 *
	 * On bulk actions the role parameter is not passed, since we're using a form
	 * to submit information. The information is only available through the
	 * `_wp_http_referer` parameter, so we get it from there and make it available
	 * for the request.
	 * @access     protected
	 *
	 * @return     void
	 */
	protected function set_up_role_context() {
		if ( empty( $_REQUEST['role'] ) && ! empty( $_REQUEST['_wp_http_referer'] ) ) {
			$referrer = parse_url( $_REQUEST['_wp_http_referer'] );

			if ( ! empty( $referrer['query'] ) ) {
				$args = wp_parse_args( $referrer['query'] );

				if ( ! empty( $args['role'] ) ) {
					$_REQUEST['role'] = $args['role'];
				}
			}
		}
	}

	/**
	 * Returns the current role.
	 *
	 * If the user list is in the context of a specific role, this function makes
	 * sure that the requested role is valid. By returning `false` otherwise, we
	 * make sure that parameter gets removed from the activation link.
	 * @access     protected
	 *
	 * @return     string|bool The role key if set, false otherwise.
	 */
	protected function get_role() {
		$roles   = array_keys( get_editable_roles() );
		$roles[] = 'wpfeu_unapproved';
		$role    = false;

		if ( isset( $_REQUEST['role'] ) && in_array( $_REQUEST['role'], $roles ) ) {
			$role = $_REQUEST['role'];
		}

		return $role;
	}


	///////////////////////////////////////////////////////////////////////////
	// METHODS, DEPRECATED
	///////////////////////////////////////////////////////////////////////////

	/**
	 * Re-runs the activation hook when registration is activated.
	 *
	 * If the plugin is activated and user registration is disabled, the plugin
	 * activation hook never gets added, let alone fired. This a secondary
	 * measure to make sure all existing users are approved on activation.
	 *
	 * @access     public
	 *
	 * @param      string $old Old settings value.
	 * @param      int    $new New settings value.
	 *
	 * @return     void
	 */
	public function update_option_users_can_register( $old, $new ) {
		_deprecated_function( __FUNCTION__, '2.3' );
	}
} // End of class Approve_user_wp_feu_pro


new Approve_user_wp_feu_pro;


register_activation_hook( __FILE__, array(
	Approve_user_wp_feu_pro::$instance,
	'activation'
));

if ( ! get_option( 'users_can_register' ) ) {
	function wpfeu_whitelist_users( $old, $new ) {
		if ( $new ) {
			$user_ids = get_users( array(
				'blog_id' => '',
				'fields'  => 'ID',
			) );

			foreach ( $user_ids as $user_id ) {
				update_user_meta( $user_id, 'feu-approve-user',           true );
				update_user_meta( $user_id, 'feu-approve-user-mail-sent', true );
			}
		}
	}
	add_action( 'update_option_users_can_register', 'wpfeu_whitelist_users', 10, 2 );
	return;
}
/********Frontend**********/


function get_predefined_feua_style_template_data() {

    return array ( 

       
        array (

        	'title'     => "Haruki",

        	'category'  => "Frontend User Style",

        	'default_css' => 's:5654:"a:164:{s:15:"form-background";s:7:"#ffffff";s:10:"form-width";s:3:"50%";s:15:"form-box-sizing";s:7:"Default";s:13:"form-position";s:7:"Default";s:8:"form-top";s:0:"";s:11:"form-bottom";s:0:"";s:9:"form-left";s:0:"";s:10:"form-right";s:0:"";s:17:"form-border-width";s:0:"";s:15:"form-transition";s:0:"";s:10:"form-hover";s:9:"         ";s:10:"form-focus";s:9:"         ";s:17:"form-border-style";s:7:"Default";s:15:"form-text-align";s:7:"Default";s:12:"form-z-index";s:0:"";s:12:"form-padding";s:0:"";s:11:"form-margin";s:0:"";s:17:"form-border-color";s:0:"";s:18:"form-border-radius";s:0:"";s:16:"form-line-height";s:0:"";s:16:"input-background";s:13:"rgba(0,0,0,0)";s:11:"input-color";s:7:"#6a7989";s:18:"input-border-color";s:7:"#6a7989";s:15:"input-font-size";s:2:"40";s:17:"input-line-height";s:2:"51";s:16:"input-transition";s:16:"all 0.3s ease 0s";s:18:"input-border-width";s:5:"4px 0";s:18:"input-border-style";s:5:"solid";s:19:"input-border-radius";s:1:"0";s:16:"input-font-style";s:7:"Default";s:17:"input-font-weight";s:7:"Default";s:11:"input-width";s:0:"";s:16:"input-box-sizing";s:7:"Default";s:14:"input-position";s:8:"Relative";s:16:"input-text-align";s:7:"Default";s:13:"input-z-index";s:0:"";s:9:"input-top";s:1:"0";s:12:"input-bottom";s:0:"";s:10:"input-left";s:0:"";s:11:"input-right";s:0:"";s:12:"input-height";s:2:"80";s:11:"input-hover";s:9:"         ";s:11:"input-focus";s:9:"         ";s:13:"input-padding";s:9:"15px 10px";s:12:"input-margin";s:0:"";s:17:"fileupload-styles";s:0:"";s:25:"textarea-background-color";s:0:"";s:15:"textarea-height";s:2:"80";s:14:"textarea-width";s:0:"";s:19:"textarea-transition";s:16:"all 0.3s ease 0s";s:19:"textarea-box-sizing";s:7:"Default";s:17:"textarea-position";s:7:"Default";s:12:"textarea-top";s:0:"";s:15:"textarea-bottom";s:0:"";s:13:"textarea-left";s:0:"";s:14:"textarea-right";s:0:"";s:14:"textarea-hover";s:9:"         ";s:14:"textarea-focus";s:9:"         ";s:20:"textarea-border-size";s:0:"";s:21:"textarea-border-color";s:0:"";s:21:"textarea-border-style";s:7:"Default";s:22:"textarea-border-radius";s:0:"";s:18:"textarea-font-size";s:0:"";s:19:"textarea-text-align";s:7:"Default";s:16:"textarea-z-index";s:0:"";s:20:"textarea-line-height";s:0:"";s:19:"textarea-font-style";s:7:"Default";s:15:"dropdown-styles";s:5:"Slide";s:19:"dropdown-background";s:0:"";s:14:"dropdown-color";s:0:"";s:19:"dropdown-transition";s:0:"";s:21:"dropdown-border-color";s:0:"";s:18:"dropdown-font-size";s:0:"";s:20:"dropdown-line-height";s:0:"";s:21:"dropdown-border-width";s:0:"";s:21:"dropdown-border-style";s:7:"Default";s:22:"dropdown-border-radius";s:0:"";s:19:"dropdown-font-style";s:7:"Default";s:20:"dropdown-font-weight";s:7:"Default";s:14:"dropdown-width";s:0:"";s:19:"dropdown-box-sizing";s:7:"Default";s:17:"dropdown-position";s:7:"Default";s:12:"dropdown-top";s:0:"";s:15:"dropdown-bottom";s:0:"";s:13:"dropdown-left";s:0:"";s:14:"dropdown-right";s:0:"";s:19:"dropdown-text-align";s:7:"Default";s:16:"dropdown-z-index";s:0:"";s:15:"dropdown-height";s:0:"";s:16:"dropdown-padding";s:0:"";s:15:"dropdown-margin";s:0:"";s:16:"label-font-style";s:7:"Default";s:17:"label-font-weight";s:4:"bold";s:15:"label-font-size";s:2:"18";s:17:"label-line-height";s:0:"";s:14:"label-position";s:8:"Relative";s:11:"label-width";s:0:"";s:12:"label-height";s:0:"";s:9:"label-top";s:4:"52px";s:12:"label-bottom";s:0:"";s:10:"label-left";s:4:"15px";s:18:"label-border-width";s:0:"";s:18:"label-border-style";s:7:"Default";s:16:"label-text-align";s:7:"Default";s:13:"label-z-index";s:0:"";s:18:"label-border-color";s:0:"";s:19:"label-border-radius";s:0:"";s:11:"label-after";s:9:"         ";s:12:"label-before";s:9:"         ";s:11:"label-hover";s:9:"         ";s:11:"label-focus";s:9:"         ";s:11:"label-right";s:0:"";s:16:"label-transition";s:16:"all 0.3s ease 0s";s:13:"label-padding";s:0:"";s:11:"label-color";s:7:"#6a7989";s:13:"submit-styles";s:0:"";s:19:"submit-button-width";s:3:"150";s:24:"submit-button-box-sizing";s:7:"Default";s:17:"submit-text-align";s:7:"Default";s:14:"submit-z-index";s:0:"";s:20:"submit-button-height";s:2:"50";s:27:"submit-button-border-radius";s:1:"0";s:23:"submit-button-font-size";s:2:"14";s:25:"submit-button-line-height";s:0:"";s:12:"submit-hover";s:9:"         ";s:12:"submit-focus";s:9:"         ";s:15:"submit-position";s:7:"Default";s:10:"submit-top";s:0:"";s:13:"submit-bottom";s:0:"";s:11:"submit-left";s:0:"";s:12:"submit-right";s:0:"";s:17:"submit-transition";s:0:"";s:26:"submit-button-border-width";s:1:"0";s:26:"submit-button-border-style";s:7:"Default";s:26:"submit-button-border-color";s:0:"";s:19:"submit-button-color";s:7:"#ffffff";s:24:"submit-button-background";s:7:"#dd9933";s:19:"fieldset-background";s:7:"#ffffff";s:14:"fieldset-width";s:3:"225";s:14:"fieldset-after";s:5:"     ";s:15:"fieldset-before";s:5:"     ";s:16:"fieldset-display";s:12:"inline-block";s:23:"fieldset-vertical-align";s:3:"top";s:19:"fieldset-text-align";s:7:"Default";s:16:"fieldset-z-index";s:0:"";s:14:"fieldset-color";s:7:"#6a7989";s:21:"fieldset-border-color";s:0:"";s:18:"fieldset-font-size";s:2:"18";s:20:"fieldset-line-height";s:0:"";s:19:"fieldset-transition";s:0:"";s:21:"fieldset-border-width";s:1:"0";s:21:"fieldset-border-style";s:4:"none";s:22:"fieldset-border-radius";s:1:"0";s:19:"fieldset-font-style";s:7:"Default";s:20:"fieldset-font-weight";s:7:"Default";s:19:"fieldset-box-sizing";s:7:"Default";s:17:"fieldset-position";s:7:"Default";s:12:"fieldset-top";s:0:"";s:15:"fieldset-bottom";s:0:"";s:13:"fieldset-left";s:0:"";s:14:"fieldset-right";s:0:"";s:15:"fieldset-height";s:0:"";s:16:"fieldset-padding";s:3:"0px";s:15:"fieldset-margin";s:13:"0 25px 35px 0";}";'

        	),
		array(

			'title'     =>'Hoshi',

			'category'  => 'Frontend User Style',

			'default_css' => 's:5614:"a:161:{s:15:"form-background";s:0:"";s:10:"form-width";s:3:"350";s:15:"form-box-sizing";s:7:"Default";s:13:"form-position";s:7:"Default";s:8:"form-top";s:0:"";s:11:"form-bottom";s:0:"";s:9:"form-left";s:0:"";s:10:"form-right";s:0:"";s:17:"form-border-width";s:0:"";s:15:"form-transition";s:0:"";s:10:"form-hover";s:7:"       ";s:10:"form-focus";s:7:"       ";s:17:"form-border-style";s:7:"Default";s:15:"form-text-align";s:7:"Default";s:12:"form-z-index";s:0:"";s:12:"form-padding";s:0:"";s:11:"form-margin";s:0:"";s:17:"form-border-color";s:0:"";s:18:"form-border-radius";s:0:"";s:16:"form-line-height";s:0:"";s:16:"input-background";s:11:"transparent";s:11:"input-color";s:7:"#6a7989";s:18:"input-border-color";s:0:"";s:15:"input-font-size";s:2:"17";s:17:"input-line-height";s:2:"39";s:16:"input-transition";s:0:"";s:18:"input-border-width";s:1:"0";s:18:"input-border-style";s:4:"none";s:19:"input-border-radius";s:1:"0";s:16:"input-font-style";s:7:"Default";s:17:"input-font-weight";s:4:"bold";s:11:"input-width";s:0:"";s:16:"input-box-sizing";s:7:"Default";s:14:"input-position";s:8:"Relative";s:16:"input-text-align";s:7:"Default";s:13:"input-z-index";s:0:"";s:9:"input-top";s:0:"";s:12:"input-bottom";s:0:"";s:10:"input-left";s:0:"";s:11:"input-right";s:0:"";s:12:"input-height";s:0:"";s:11:"input-hover";s:7:"       ";s:11:"input-focus";s:7:"       ";s:13:"input-padding";s:6:"15px 0";s:12:"input-margin";s:0:"";s:25:"textarea-background-color";s:10:"tranparent";s:15:"textarea-height";s:2:"80";s:14:"textarea-width";s:0:"";s:19:"textarea-transition";s:0:"";s:19:"textarea-box-sizing";s:7:"Default";s:17:"textarea-position";s:8:"Relative";s:12:"textarea-top";s:0:"";s:15:"textarea-bottom";s:0:"";s:13:"textarea-left";s:0:"";s:14:"textarea-right";s:0:"";s:14:"textarea-hover";s:7:"       ";s:14:"textarea-focus";s:7:"       ";s:20:"textarea-border-size";s:1:"0";s:21:"textarea-border-color";s:0:"";s:21:"textarea-border-style";s:4:"none";s:22:"textarea-border-radius";s:1:"0";s:18:"textarea-font-size";s:2:"17";s:19:"textarea-text-align";s:7:"Default";s:16:"textarea-z-index";s:0:"";s:20:"textarea-line-height";s:0:"";s:19:"textarea-font-style";s:7:"Default";s:19:"dropdown-background";s:0:"";s:14:"dropdown-color";s:0:"";s:19:"dropdown-transition";s:0:"";s:21:"dropdown-border-color";s:0:"";s:18:"dropdown-font-size";s:0:"";s:20:"dropdown-line-height";s:0:"";s:21:"dropdown-border-width";s:0:"";s:21:"dropdown-border-style";s:7:"Default";s:22:"dropdown-border-radius";s:0:"";s:19:"dropdown-font-style";s:7:"Default";s:20:"dropdown-font-weight";s:7:"Default";s:14:"dropdown-width";s:0:"";s:19:"dropdown-box-sizing";s:7:"Default";s:17:"dropdown-position";s:7:"Default";s:12:"dropdown-top";s:0:"";s:15:"dropdown-bottom";s:0:"";s:13:"dropdown-left";s:0:"";s:14:"dropdown-right";s:0:"";s:19:"dropdown-text-align";s:7:"Default";s:16:"dropdown-z-index";s:0:"";s:15:"dropdown-height";s:0:"";s:16:"dropdown-padding";s:0:"";s:15:"dropdown-margin";s:0:"";s:16:"label-font-style";s:7:"Default";s:17:"label-font-weight";s:4:"bold";s:15:"label-font-size";s:2:"17";s:17:"label-line-height";s:0:"";s:14:"label-position";s:8:"Relative";s:11:"label-width";s:0:"";s:12:"label-height";s:0:"";s:9:"label-top";s:4:"52px";s:12:"label-bottom";s:0:"";s:10:"label-left";s:0:"";s:18:"label-border-width";s:1:"0";s:18:"label-border-style";s:4:"none";s:16:"label-text-align";s:7:"Default";s:13:"label-z-index";s:0:"";s:18:"label-border-color";s:0:"";s:19:"label-border-radius";s:1:"0";s:11:"label-after";s:7:"       ";s:12:"label-before";s:7:"       ";s:11:"label-hover";s:7:"       ";s:11:"label-focus";s:7:"       ";s:11:"label-right";s:0:"";s:16:"label-transition";s:16:"all 0.3s ease 0s";s:13:"label-padding";s:0:"";s:11:"label-color";s:7:"#6a7978";s:19:"submit-button-width";s:0:"";s:24:"submit-button-box-sizing";s:7:"Default";s:17:"submit-text-align";s:7:"Default";s:14:"submit-z-index";s:0:"";s:20:"submit-button-height";s:0:"";s:27:"submit-button-border-radius";s:0:"";s:23:"submit-button-font-size";s:0:"";s:25:"submit-button-line-height";s:0:"";s:12:"submit-hover";s:7:"       ";s:12:"submit-focus";s:7:"       ";s:15:"submit-position";s:7:"Default";s:10:"submit-top";s:0:"";s:13:"submit-bottom";s:0:"";s:11:"submit-left";s:0:"";s:12:"submit-right";s:0:"";s:17:"submit-transition";s:0:"";s:26:"submit-button-border-width";s:0:"";s:26:"submit-button-border-style";s:7:"Default";s:26:"submit-button-border-color";s:0:"";s:19:"submit-button-color";s:0:"";s:24:"submit-button-background";s:0:"";s:19:"fieldset-background";s:11:"transparent";s:14:"fieldset-width";s:0:"";s:14:"fieldset-after";s:171:"   background: #00a9ff none repeat scroll 0 0;bottom: 0;rncolor: rgba(0, 0, 0, 0);height: 4px;left: 0;position:absolute;transition: all 0.3s ease 0s;width: 0;content:\"\";";s:15:"fieldset-before";s:7:"       ";s:16:"fieldset-display";s:7:"Default";s:23:"fieldset-vertical-align";s:3:"top";s:19:"fieldset-text-align";s:7:"Default";s:16:"fieldset-z-index";s:0:"";s:14:"fieldset-color";s:0:"";s:21:"fieldset-border-color";s:7:"#d1d1d1";s:18:"fieldset-font-size";s:0:"";s:20:"fieldset-line-height";s:0:"";s:19:"fieldset-transition";s:0:"";s:21:"fieldset-border-width";s:9:"0 0 1px 0";s:21:"fieldset-border-style";s:5:"solid";s:22:"fieldset-border-radius";s:1:"0";s:19:"fieldset-font-style";s:7:"Default";s:20:"fieldset-font-weight";s:7:"Default";s:19:"fieldset-box-sizing";s:7:"Default";s:17:"fieldset-position";s:8:"Relative";s:12:"fieldset-top";s:0:"";s:15:"fieldset-bottom";s:0:"";s:13:"fieldset-left";s:0:"";s:14:"fieldset-right";s:0:"";s:15:"fieldset-height";s:0:"";s:16:"fieldset-padding";s:1:"0";s:15:"fieldset-margin";s:0:"";}";'

			),
		array (

			'title'     => "Kuro",

			'category'  => "Frontend User Style",

			'default_css' => 's:5480:"a:146:{s:15:"form-background";s:11:"transparent";s:10:"form-width";s:3:"350";s:15:"form-box-sizing";s:10:"border-box";s:13:"form-position";s:7:"Default";s:8:"form-top";s:0:"";s:11:"form-bottom";s:0:"";s:9:"form-left";s:0:"";s:10:"form-right";s:0:"";s:17:"form-border-width";s:1:"0";s:15:"form-transition";s:0:"";s:10:"form-hover";s:21:"                     ";s:10:"form-focus";s:21:"                     ";s:17:"form-border-style";s:7:"Default";s:12:"form-padding";s:0:"";s:11:"form-margin";s:0:"";s:17:"form-border-color";s:0:"";s:18:"form-border-radius";s:0:"";s:16:"form-line-height";s:0:"";s:16:"input-background";s:11:"transparent";s:11:"input-color";s:7:"#9196a1";s:18:"input-border-color";s:0:"";s:15:"input-font-size";s:2:"25";s:17:"input-line-height";s:2:"31";s:16:"input-transition";s:0:"";s:18:"input-border-width";s:1:"0";s:18:"input-border-style";s:4:"none";s:19:"input-border-radius";s:1:"0";s:16:"input-font-style";s:6:"normal";s:17:"input-font-weight";s:4:"bold";s:11:"input-width";s:0:"";s:16:"input-box-sizing";s:7:"Default";s:14:"input-position";s:8:"Relative";s:9:"input-top";s:0:"";s:12:"input-bottom";s:0:"";s:10:"input-left";s:0:"";s:11:"input-right";s:0:"";s:12:"input-height";s:0:"";s:11:"input-hover";s:21:"                     ";s:11:"input-focus";s:21:"                     ";s:13:"input-padding";s:4:"20px";s:12:"input-margin";s:0:"";s:25:"textarea-background-color";s:11:"transparent";s:15:"textarea-height";s:0:"";s:14:"textarea-width";s:0:"";s:19:"textarea-transition";s:0:"";s:19:"textarea-box-sizing";s:7:"Default";s:17:"textarea-position";s:8:"Relative";s:12:"textarea-top";s:0:"";s:15:"textarea-bottom";s:0:"";s:13:"textarea-left";s:0:"";s:14:"textarea-right";s:0:"";s:14:"textarea-hover";s:21:"                     ";s:14:"textarea-focus";s:21:"                     ";s:20:"textarea-border-size";s:1:"0";s:21:"textarea-border-color";s:0:"";s:21:"textarea-border-style";s:4:"none";s:22:"textarea-border-radius";s:1:"0";s:18:"textarea-font-size";s:2:"25";s:20:"textarea-line-height";s:0:"";s:19:"textarea-font-style";s:7:"Default";s:19:"dropdown-background";s:0:"";s:14:"dropdown-color";s:0:"";s:19:"dropdown-transition";s:0:"";s:21:"dropdown-border-color";s:0:"";s:18:"dropdown-font-size";s:0:"";s:20:"dropdown-line-height";s:0:"";s:21:"dropdown-border-width";s:0:"";s:21:"dropdown-border-style";s:7:"Default";s:22:"dropdown-border-radius";s:0:"";s:19:"dropdown-font-style";s:7:"Default";s:20:"dropdown-font-weight";s:7:"Default";s:14:"dropdown-width";s:0:"";s:19:"dropdown-box-sizing";s:7:"Default";s:17:"dropdown-position";s:7:"Default";s:12:"dropdown-top";s:0:"";s:15:"dropdown-bottom";s:0:"";s:13:"dropdown-left";s:0:"";s:14:"dropdown-right";s:0:"";s:15:"dropdown-height";s:0:"";s:16:"dropdown-padding";s:0:"";s:15:"dropdown-margin";s:0:"";s:16:"label-font-style";s:7:"Default";s:17:"label-font-weight";s:4:"bold";s:15:"label-font-size";s:2:"17";s:17:"label-line-height";s:0:"";s:14:"label-position";s:8:"Absolute";s:11:"label-width";s:0:"";s:12:"label-height";s:0:"";s:9:"label-top";s:4:"26px";s:12:"label-bottom";s:0:"";s:10:"label-left";s:1:"0";s:18:"label-border-width";s:1:"0";s:18:"label-border-style";s:4:"none";s:18:"label-border-color";s:0:"";s:19:"label-border-radius";s:0:"";s:11:"label-after";s:21:"                     ";s:12:"label-before";s:21:"                     ";s:11:"label-hover";s:21:"                     ";s:11:"label-focus";s:21:"                     ";s:11:"label-right";s:1:"0";s:16:"label-transition";s:16:"all 0.3s ease 0s";s:11:"label-color";s:7:"#df6589";s:19:"submit-button-width";s:0:"";s:24:"submit-button-box-sizing";s:7:"Default";s:20:"submit-button-height";s:0:"";s:27:"submit-button-border-radius";s:0:"";s:23:"submit-button-font-size";s:0:"";s:25:"submit-button-line-height";s:0:"";s:12:"submit-hover";s:21:"                     ";s:12:"submit-focus";s:21:"                     ";s:15:"submit-position";s:7:"Default";s:10:"submit-top";s:0:"";s:13:"submit-bottom";s:0:"";s:11:"submit-left";s:0:"";s:12:"submit-right";s:0:"";s:17:"submit-transition";s:0:"";s:26:"submit-button-border-width";s:0:"";s:26:"submit-button-border-style";s:7:"Default";s:26:"submit-button-border-color";s:0:"";s:19:"submit-button-color";s:0:"";s:24:"submit-button-background";s:0:"";s:19:"fieldset-background";s:0:"";s:14:"fieldset-width";s:0:"";s:14:"fieldset-after";s:185:"   border-color: #747981;border-style: solid;border-width: 4px 0 4px 4px;content: \"\";height: 100%;left: 0;position: absolute;top: 0;transition: all 0.3s ease 0s;width: 50%;z-index: 0;";s:15:"fieldset-before";s:185:"   border-color: #747981;border-style: solid;border-width: 4px 4px 4px 0;content:\"\";height: 100%;right: 0;position: absolute;top: 0;transition: all 0.3s ease 0s;width: 50%;z-index: 0;";s:16:"fieldset-display";s:7:"Default";s:23:"fieldset-vertical-align";s:7:"Default";s:14:"fieldset-color";s:0:"";s:21:"fieldset-border-color";s:0:"";s:18:"fieldset-font-size";s:0:"";s:20:"fieldset-line-height";s:0:"";s:19:"fieldset-transition";s:0:"";s:21:"fieldset-border-width";s:1:"0";s:21:"fieldset-border-style";s:4:"none";s:22:"fieldset-border-radius";s:0:"";s:19:"fieldset-font-style";s:7:"Default";s:20:"fieldset-font-weight";s:7:"Default";s:19:"fieldset-box-sizing";s:7:"Default";s:17:"fieldset-position";s:8:"Relative";s:12:"fieldset-top";s:0:"";s:15:"fieldset-bottom";s:0:"";s:13:"fieldset-left";s:0:"";s:14:"fieldset-right";s:0:"";s:15:"fieldset-height";s:0:"";s:16:"fieldset-padding";s:1:"0";s:15:"fieldset-margin";s:10:"0 0 35px 0";}";'

			),
		array (

			'title'     => "Kyo",

			'category'  => "Frontend User Style",

			'default_css' => 's:5865:"a:164:{s:15:"form-background";s:0:"";s:10:"form-width";s:0:"";s:15:"form-box-sizing";s:7:"Default";s:13:"form-position";s:7:"Default";s:8:"form-top";s:0:"";s:11:"form-bottom";s:0:"";s:9:"form-left";s:0:"";s:10:"form-right";s:0:"";s:17:"form-border-width";s:0:"";s:15:"form-transition";s:0:"";s:10:"form-hover";s:13:"             ";s:10:"form-focus";s:13:"             ";s:17:"form-border-style";s:7:"Default";s:15:"form-text-align";s:7:"Default";s:12:"form-z-index";s:0:"";s:12:"form-padding";s:0:"";s:11:"form-margin";s:0:"";s:17:"form-border-color";s:0:"";s:18:"form-border-radius";s:0:"";s:16:"form-line-height";s:0:"";s:16:"input-background";s:0:"";s:11:"input-color";s:0:"";s:18:"input-border-color";s:11:"transparent";s:15:"input-font-size";s:0:"";s:17:"input-line-height";s:0:"";s:16:"input-transition";s:0:"";s:18:"input-border-width";s:3:"0px";s:18:"input-border-style";s:4:"none";s:19:"input-border-radius";s:2:"20";s:16:"input-font-style";s:7:"Default";s:17:"input-font-weight";s:7:"Default";s:11:"input-width";s:3:"300";s:16:"input-box-sizing";s:7:"Default";s:14:"input-position";s:8:"Relative";s:16:"input-text-align";s:7:"Default";s:13:"input-z-index";s:10:"2147483647";s:9:"input-top";s:0:"";s:12:"input-bottom";s:0:"";s:10:"input-left";s:0:"";s:11:"input-right";s:0:"";s:12:"input-height";s:0:"";s:11:"input-hover";s:13:"             ";s:11:"input-focus";s:84:"        background: #fff none repeat scroll 0 0;outline: medium none;z-index: 10000;";s:13:"input-padding";s:0:"";s:12:"input-margin";s:0:"";s:17:"fileupload-styles";s:0:"";s:25:"textarea-background-color";s:7:"#ffffff";s:15:"textarea-height";s:0:"";s:14:"textarea-width";s:0:"";s:19:"textarea-transition";s:0:"";s:19:"textarea-box-sizing";s:7:"Default";s:17:"textarea-position";s:8:"Relative";s:12:"textarea-top";s:0:"";s:15:"textarea-bottom";s:0:"";s:13:"textarea-left";s:0:"";s:14:"textarea-right";s:0:"";s:14:"textarea-hover";s:13:"             ";s:14:"textarea-focus";s:17:"   z-index:99999;";s:20:"textarea-border-size";s:0:"";s:21:"textarea-border-color";s:0:"";s:21:"textarea-border-style";s:7:"Default";s:22:"textarea-border-radius";s:0:"";s:18:"textarea-font-size";s:0:"";s:19:"textarea-text-align";s:7:"Default";s:16:"textarea-z-index";s:10:"2147483647";s:20:"textarea-line-height";s:0:"";s:19:"textarea-font-style";s:7:"Default";s:15:"dropdown-styles";s:7:"Default";s:19:"dropdown-background";s:0:"";s:14:"dropdown-color";s:0:"";s:19:"dropdown-transition";s:0:"";s:21:"dropdown-border-color";s:0:"";s:18:"dropdown-font-size";s:0:"";s:20:"dropdown-line-height";s:0:"";s:21:"dropdown-border-width";s:0:"";s:21:"dropdown-border-style";s:7:"Default";s:22:"dropdown-border-radius";s:0:"";s:19:"dropdown-font-style";s:7:"Default";s:20:"dropdown-font-weight";s:7:"Default";s:14:"dropdown-width";s:0:"";s:19:"dropdown-box-sizing";s:7:"Default";s:17:"dropdown-position";s:7:"Default";s:12:"dropdown-top";s:0:"";s:15:"dropdown-bottom";s:0:"";s:13:"dropdown-left";s:0:"";s:14:"dropdown-right";s:0:"";s:19:"dropdown-text-align";s:7:"Default";s:16:"dropdown-z-index";s:0:"";s:15:"dropdown-height";s:0:"";s:16:"dropdown-padding";s:0:"";s:15:"dropdown-margin";s:0:"";s:16:"label-font-style";s:7:"Default";s:17:"label-font-weight";s:4:"bold";s:15:"label-font-size";s:0:"";s:17:"label-line-height";s:2:"42";s:14:"label-position";s:8:"Relative";s:11:"label-width";s:0:"";s:12:"label-height";s:0:"";s:9:"label-top";s:0:"";s:12:"label-bottom";s:0:"";s:10:"label-left";s:4:"18px";s:18:"label-border-width";s:0:"";s:18:"label-border-style";s:7:"Default";s:16:"label-text-align";s:7:"Default";s:13:"label-z-index";s:0:"";s:18:"label-border-color";s:0:"";s:19:"label-border-radius";s:0:"";s:11:"label-after";s:13:"             ";s:12:"label-before";s:13:"             ";s:11:"label-hover";s:13:"             ";s:11:"label-focus";s:13:"             ";s:11:"label-right";s:0:"";s:16:"label-transition";s:16:"all 0.2s ease 0s";s:13:"label-padding";s:0:"";s:11:"label-color";s:7:"#6a7989";s:13:"submit-styles";s:0:"";s:19:"submit-button-width";s:0:"";s:24:"submit-button-box-sizing";s:7:"Default";s:17:"submit-text-align";s:7:"Default";s:14:"submit-z-index";s:0:"";s:20:"submit-button-height";s:0:"";s:27:"submit-button-border-radius";s:0:"";s:23:"submit-button-font-size";s:0:"";s:25:"submit-button-line-height";s:0:"";s:12:"submit-hover";s:13:"             ";s:12:"submit-focus";s:13:"             ";s:15:"submit-position";s:7:"Default";s:10:"submit-top";s:0:"";s:13:"submit-bottom";s:0:"";s:11:"submit-left";s:0:"";s:12:"submit-right";s:0:"";s:17:"submit-transition";s:0:"";s:26:"submit-button-border-width";s:0:"";s:26:"submit-button-border-style";s:7:"Default";s:26:"submit-button-border-color";s:0:"";s:19:"submit-button-color";s:0:"";s:24:"submit-button-background";s:0:"";s:19:"fieldset-background";s:7:"#e8e8e8";s:14:"fieldset-width";s:3:"350";s:14:"fieldset-after";s:169:"content:\"\";position:fixed;top: 0;left: 0;z-index:1000;width: 0%;height:100%;background: rgba(11, 43, 205, 0.6);			      -webkit-transition: all 0.3s ease 0s;opacity:0;";s:15:"fieldset-before";s:13:"             ";s:16:"fieldset-display";s:7:"Default";s:23:"fieldset-vertical-align";s:7:"Default";s:19:"fieldset-text-align";s:7:"Default";s:16:"fieldset-z-index";s:0:"";s:14:"fieldset-color";s:0:"";s:21:"fieldset-border-color";s:0:"";s:18:"fieldset-font-size";s:0:"";s:20:"fieldset-line-height";s:0:"";s:19:"fieldset-transition";s:0:"";s:21:"fieldset-border-width";s:0:"";s:21:"fieldset-border-style";s:7:"Default";s:22:"fieldset-border-radius";s:0:"";s:19:"fieldset-font-style";s:7:"Default";s:20:"fieldset-font-weight";s:7:"Default";s:19:"fieldset-box-sizing";s:7:"Default";s:17:"fieldset-position";s:7:"Default";s:12:"fieldset-top";s:0:"";s:15:"fieldset-bottom";s:0:"";s:13:"fieldset-left";s:0:"";s:14:"fieldset-right";s:0:"";s:15:"fieldset-height";s:0:"";s:16:"fieldset-padding";s:0:"";s:15:"fieldset-margin";s:0:"";}";'

			),
		array (

			'title'     => "Yoko",

			'category'  => "Frontend User Style",

			'default_css' => 's:5921:"a:164:{s:15:"form-background";s:7:"#df6659";s:10:"form-width";s:3:"350";s:15:"form-box-sizing";s:7:"Default";s:13:"form-position";s:7:"Default";s:8:"form-top";s:0:"";s:11:"form-bottom";s:0:"";s:9:"form-left";s:0:"";s:10:"form-right";s:0:"";s:17:"form-border-width";s:0:"";s:15:"form-transition";s:0:"";s:10:"form-hover";s:5:"     ";s:10:"form-focus";s:5:"     ";s:17:"form-border-style";s:7:"Default";s:15:"form-text-align";s:7:"Default";s:12:"form-z-index";s:0:"";s:12:"form-padding";s:4:"20px";s:11:"form-margin";s:0:"";s:17:"form-border-color";s:0:"";s:18:"form-border-radius";s:0:"";s:16:"form-line-height";s:0:"";s:16:"input-background";s:11:"transparent";s:11:"input-color";s:7:"#f5f5f5";s:18:"input-border-color";s:0:"";s:15:"input-font-size";s:2:"25";s:17:"input-line-height";s:0:"";s:16:"input-transition";s:0:"";s:18:"input-border-width";s:1:"0";s:18:"input-border-style";s:4:"none";s:19:"input-border-radius";s:1:"0";s:16:"input-font-style";s:7:"Default";s:17:"input-font-weight";s:4:"bold";s:11:"input-width";s:0:"";s:16:"input-box-sizing";s:7:"Default";s:14:"input-position";s:8:"Relative";s:16:"input-text-align";s:7:"Default";s:13:"input-z-index";s:0:"";s:9:"input-top";s:0:"";s:12:"input-bottom";s:0:"";s:10:"input-left";s:0:"";s:11:"input-right";s:0:"";s:12:"input-height";s:0:"";s:11:"input-hover";s:5:"     ";s:11:"input-focus";s:5:"     ";s:13:"input-padding";s:0:"";s:12:"input-margin";s:0:"";s:17:"fileupload-styles";s:0:"";s:25:"textarea-background-color";s:0:"";s:15:"textarea-height";s:2:"52";s:14:"textarea-width";s:0:"";s:19:"textarea-transition";s:0:"";s:19:"textarea-box-sizing";s:7:"Default";s:17:"textarea-position";s:7:"Default";s:12:"textarea-top";s:0:"";s:15:"textarea-bottom";s:0:"";s:13:"textarea-left";s:0:"";s:14:"textarea-right";s:0:"";s:14:"textarea-hover";s:5:"     ";s:14:"textarea-focus";s:5:"     ";s:20:"textarea-border-size";s:0:"";s:21:"textarea-border-color";s:0:"";s:21:"textarea-border-style";s:7:"Default";s:22:"textarea-border-radius";s:0:"";s:18:"textarea-font-size";s:0:"";s:19:"textarea-text-align";s:7:"Default";s:16:"textarea-z-index";s:0:"";s:20:"textarea-line-height";s:0:"";s:19:"textarea-font-style";s:7:"Default";s:15:"dropdown-styles";s:7:"Default";s:19:"dropdown-background";s:0:"";s:14:"dropdown-color";s:0:"";s:19:"dropdown-transition";s:0:"";s:21:"dropdown-border-color";s:0:"";s:18:"dropdown-font-size";s:0:"";s:20:"dropdown-line-height";s:0:"";s:21:"dropdown-border-width";s:0:"";s:21:"dropdown-border-style";s:7:"Default";s:22:"dropdown-border-radius";s:0:"";s:19:"dropdown-font-style";s:7:"Default";s:20:"dropdown-font-weight";s:7:"Default";s:14:"dropdown-width";s:0:"";s:19:"dropdown-box-sizing";s:7:"Default";s:17:"dropdown-position";s:7:"Default";s:12:"dropdown-top";s:0:"";s:15:"dropdown-bottom";s:0:"";s:13:"dropdown-left";s:0:"";s:14:"dropdown-right";s:0:"";s:19:"dropdown-text-align";s:7:"Default";s:16:"dropdown-z-index";s:0:"";s:15:"dropdown-height";s:0:"";s:16:"dropdown-padding";s:0:"";s:15:"dropdown-margin";s:0:"";s:16:"label-font-style";s:7:"Default";s:17:"label-font-weight";s:4:"bold";s:15:"label-font-size";s:2:"20";s:17:"label-line-height";s:0:"";s:14:"label-position";s:8:"Absolute";s:11:"label-width";s:0:"";s:12:"label-height";s:0:"";s:9:"label-top";s:0:"";s:12:"label-bottom";s:5:"-30px";s:10:"label-left";s:0:"";s:18:"label-border-width";s:1:"0";s:18:"label-border-style";s:4:"none";s:16:"label-text-align";s:7:"Default";s:13:"label-z-index";s:0:"";s:18:"label-border-color";s:0:"";s:19:"label-border-radius";s:0:"";s:11:"label-after";s:5:"     ";s:12:"label-before";s:5:"     ";s:11:"label-hover";s:5:"     ";s:11:"label-focus";s:5:"     ";s:11:"label-right";s:0:"";s:16:"label-transition";s:0:"";s:13:"label-padding";s:0:"";s:11:"label-color";s:7:"#b04b40";s:13:"submit-styles";s:0:"";s:19:"submit-button-width";s:0:"";s:24:"submit-button-box-sizing";s:7:"Default";s:17:"submit-text-align";s:7:"Default";s:14:"submit-z-index";s:0:"";s:20:"submit-button-height";s:0:"";s:27:"submit-button-border-radius";s:0:"";s:23:"submit-button-font-size";s:0:"";s:25:"submit-button-line-height";s:0:"";s:12:"submit-hover";s:5:"     ";s:12:"submit-focus";s:5:"     ";s:15:"submit-position";s:7:"Default";s:10:"submit-top";s:0:"";s:13:"submit-bottom";s:0:"";s:11:"submit-left";s:0:"";s:12:"submit-right";s:0:"";s:17:"submit-transition";s:0:"";s:26:"submit-button-border-width";s:0:"";s:26:"submit-button-border-style";s:7:"Default";s:26:"submit-button-border-color";s:0:"";s:19:"submit-button-color";s:0:"";s:24:"submit-button-background";s:0:"";s:19:"fieldset-background";s:11:"transparent";s:14:"fieldset-width";s:0:"";s:14:"fieldset-after";s:186:"  background: #ad473c none repeat scroll 0 0;content: \"\";height: 0.25em; left: 0; position: absolute;bottom: 0;transform-origin: 50% 0 0;transition: transform 0.3s ease 0s;width: 100%;";s:15:"fieldset-before";s:252:"     background: #c5564a none repeat scroll 0 0;bottom:0;content: \"\"; height: 100%; left: 0;   position: absolute;transform: perspective(1000px) rotate3d(1, 0, 0, 90deg);   transform-origin: 50% 100% 0;transition: transform 0.3s ease 0s;width: 100%; ";s:16:"fieldset-display";s:7:"Default";s:23:"fieldset-vertical-align";s:7:"Default";s:19:"fieldset-text-align";s:7:"Default";s:16:"fieldset-z-index";s:0:"";s:14:"fieldset-color";s:0:"";s:21:"fieldset-border-color";s:0:"";s:18:"fieldset-font-size";s:0:"";s:20:"fieldset-line-height";s:0:"";s:19:"fieldset-transition";s:0:"";s:21:"fieldset-border-width";s:1:"0";s:21:"fieldset-border-style";s:4:"none";s:22:"fieldset-border-radius";s:1:"0";s:19:"fieldset-font-style";s:7:"Default";s:20:"fieldset-font-weight";s:7:"Default";s:19:"fieldset-box-sizing";s:7:"Default";s:17:"fieldset-position";s:8:"Relative";s:12:"fieldset-top";s:0:"";s:15:"fieldset-bottom";s:0:"";s:13:"fieldset-left";s:0:"";s:14:"fieldset-right";s:0:"";s:15:"fieldset-height";s:0:"";s:16:"fieldset-padding";s:1:"0";s:15:"fieldset-margin";s:10:"0 0 55px 0";}";'

			),
		array (

			'title'     => "Akira",

			'category'  => "Frontend User Style",

			'default_css' => 's:5739:"a:164:{s:15:"form-background";s:7:"#2f3238";s:10:"form-width";s:3:"350";s:15:"form-box-sizing";s:7:"Default";s:13:"form-position";s:7:"Default";s:8:"form-top";s:0:"";s:11:"form-bottom";s:0:"";s:9:"form-left";s:0:"";s:10:"form-right";s:0:"";s:17:"form-border-width";s:0:"";s:15:"form-transition";s:0:"";s:10:"form-hover";s:9:"         ";s:10:"form-focus";s:9:"         ";s:17:"form-border-style";s:7:"Default";s:15:"form-text-align";s:7:"Default";s:12:"form-z-index";s:0:"";s:12:"form-padding";s:9:"40px 20px";s:11:"form-margin";s:0:"";s:17:"form-border-color";s:0:"";s:18:"form-border-radius";s:0:"";s:16:"form-line-height";s:0:"";s:16:"input-background";s:11:"transparent";s:11:"input-color";s:7:"#aaaaaa";s:18:"input-border-color";s:0:"";s:15:"input-font-size";s:2:"25";s:17:"input-line-height";s:0:"";s:16:"input-transition";s:16:"all 0.3s ease 0s";s:18:"input-border-width";s:1:"0";s:18:"input-border-style";s:4:"none";s:19:"input-border-radius";s:1:"0";s:16:"input-font-style";s:7:"Default";s:17:"input-font-weight";s:4:"bold";s:11:"input-width";s:0:"";s:16:"input-box-sizing";s:7:"Default";s:14:"input-position";s:8:"Relative";s:16:"input-text-align";s:7:"Default";s:13:"input-z-index";s:4:"9999";s:9:"input-top";s:0:"";s:12:"input-bottom";s:0:"";s:10:"input-left";s:0:"";s:11:"input-right";s:0:"";s:12:"input-height";s:0:"";s:11:"input-hover";s:9:"         ";s:11:"input-focus";s:9:"         ";s:13:"input-padding";s:4:"20px";s:12:"input-margin";s:0:"";s:17:"fileupload-styles";s:0:"";s:25:"textarea-background-color";s:11:"transparent";s:15:"textarea-height";s:2:"75";s:14:"textarea-width";s:0:"";s:19:"textarea-transition";s:16:"all 0.3s ease 0s";s:19:"textarea-box-sizing";s:7:"Default";s:17:"textarea-position";s:8:"Relative";s:12:"textarea-top";s:0:"";s:15:"textarea-bottom";s:0:"";s:13:"textarea-left";s:0:"";s:14:"textarea-right";s:0:"";s:14:"textarea-hover";s:9:"         ";s:14:"textarea-focus";s:9:"         ";s:20:"textarea-border-size";s:1:"0";s:21:"textarea-border-color";s:0:"";s:21:"textarea-border-style";s:4:"none";s:22:"textarea-border-radius";s:1:"0";s:18:"textarea-font-size";s:2:"25";s:19:"textarea-text-align";s:7:"Default";s:16:"textarea-z-index";s:4:"9999";s:20:"textarea-line-height";s:0:"";s:19:"textarea-font-style";s:7:"Default";s:15:"dropdown-styles";s:7:"Default";s:19:"dropdown-background";s:0:"";s:14:"dropdown-color";s:0:"";s:19:"dropdown-transition";s:0:"";s:21:"dropdown-border-color";s:0:"";s:18:"dropdown-font-size";s:0:"";s:20:"dropdown-line-height";s:0:"";s:21:"dropdown-border-width";s:0:"";s:21:"dropdown-border-style";s:7:"Default";s:22:"dropdown-border-radius";s:0:"";s:19:"dropdown-font-style";s:7:"Default";s:20:"dropdown-font-weight";s:7:"Default";s:14:"dropdown-width";s:0:"";s:19:"dropdown-box-sizing";s:7:"Default";s:17:"dropdown-position";s:7:"Default";s:12:"dropdown-top";s:0:"";s:15:"dropdown-bottom";s:0:"";s:13:"dropdown-left";s:0:"";s:14:"dropdown-right";s:0:"";s:19:"dropdown-text-align";s:7:"Default";s:16:"dropdown-z-index";s:0:"";s:15:"dropdown-height";s:0:"";s:16:"dropdown-padding";s:0:"";s:15:"dropdown-margin";s:0:"";s:16:"label-font-style";s:7:"Default";s:17:"label-font-weight";s:4:"bold";s:15:"label-font-size";s:2:"18";s:17:"label-line-height";s:0:"";s:14:"label-position";s:8:"Absolute";s:11:"label-width";s:0:"";s:12:"label-height";s:0:"";s:9:"label-top";s:4:"22px";s:12:"label-bottom";s:0:"";s:10:"label-left";s:1:"0";s:18:"label-border-width";s:1:"0";s:18:"label-border-style";s:4:"none";s:16:"label-text-align";s:6:"center";s:13:"label-z-index";s:0:"";s:18:"label-border-color";s:0:"";s:19:"label-border-radius";s:0:"";s:11:"label-after";s:9:"         ";s:12:"label-before";s:9:"         ";s:11:"label-hover";s:9:"         ";s:11:"label-focus";s:9:"         ";s:11:"label-right";s:1:"0";s:16:"label-transition";s:16:"all 0.3s ease 0s";s:13:"label-padding";s:0:"";s:11:"label-color";s:7:"#cc6055";s:13:"submit-styles";s:0:"";s:19:"submit-button-width";s:0:"";s:24:"submit-button-box-sizing";s:7:"Default";s:17:"submit-text-align";s:7:"Default";s:14:"submit-z-index";s:0:"";s:20:"submit-button-height";s:0:"";s:27:"submit-button-border-radius";s:0:"";s:23:"submit-button-font-size";s:0:"";s:25:"submit-button-line-height";s:0:"";s:12:"submit-hover";s:9:"         ";s:12:"submit-focus";s:9:"         ";s:15:"submit-position";s:7:"Default";s:10:"submit-top";s:0:"";s:13:"submit-bottom";s:0:"";s:11:"submit-left";s:0:"";s:12:"submit-right";s:0:"";s:17:"submit-transition";s:0:"";s:26:"submit-button-border-width";s:0:"";s:26:"submit-button-border-style";s:7:"Default";s:26:"submit-button-border-color";s:0:"";s:19:"submit-button-color";s:0:"";s:24:"submit-button-background";s:0:"";s:19:"fieldset-background";s:0:"";s:14:"fieldset-width";s:0:"";s:14:"fieldset-after";s:132:"    border: 5px solid #696a6e;content: \"\";height: 100%;left: 0;position: absolute;top: 0;transition: all 0.3s ease 0s;width: 100%;";s:15:"fieldset-before";s:9:"         ";s:16:"fieldset-display";s:7:"Default";s:23:"fieldset-vertical-align";s:7:"Default";s:19:"fieldset-text-align";s:7:"Default";s:16:"fieldset-z-index";s:0:"";s:14:"fieldset-color";s:0:"";s:21:"fieldset-border-color";s:0:"";s:18:"fieldset-font-size";s:0:"";s:20:"fieldset-line-height";s:0:"";s:19:"fieldset-transition";s:0:"";s:21:"fieldset-border-width";s:0:"";s:21:"fieldset-border-style";s:4:"none";s:22:"fieldset-border-radius";s:1:"0";s:19:"fieldset-font-style";s:7:"Default";s:20:"fieldset-font-weight";s:7:"Default";s:19:"fieldset-box-sizing";s:7:"Default";s:17:"fieldset-position";s:8:"Relative";s:12:"fieldset-top";s:0:"";s:15:"fieldset-bottom";s:0:"";s:13:"fieldset-left";s:0:"";s:14:"fieldset-right";s:0:"";s:15:"fieldset-height";s:0:"";s:16:"fieldset-padding";s:1:"0";s:15:"fieldset-margin";s:10:"0 0 45px 0";}";'

			),
		array (

			'title'     => "Jiro",

			'category'  => "Frontend User Style",

			'default_css' => 's:5828:"a:164:{s:15:"form-background";s:7:"#d0d6d6";s:10:"form-width";s:3:"350";s:15:"form-box-sizing";s:7:"Default";s:13:"form-position";s:7:"Default";s:8:"form-top";s:0:"";s:11:"form-bottom";s:0:"";s:9:"form-left";s:0:"";s:10:"form-right";s:0:"";s:17:"form-border-width";s:0:"";s:15:"form-transition";s:0:"";s:10:"form-hover";s:8:"        ";s:10:"form-focus";s:8:"        ";s:17:"form-border-style";s:7:"Default";s:15:"form-text-align";s:7:"Default";s:12:"form-z-index";s:0:"";s:12:"form-padding";s:9:"50px 20px";s:11:"form-margin";s:0:"";s:17:"form-border-color";s:0:"";s:18:"form-border-radius";s:0:"";s:16:"form-line-height";s:0:"";s:16:"input-background";s:11:"transparent";s:11:"input-color";s:7:"#6a7989";s:18:"input-border-color";s:0:"";s:15:"input-font-size";s:2:"20";s:17:"input-line-height";s:0:"";s:16:"input-transition";s:16:"all 0.3s ease 0s";s:18:"input-border-width";s:1:"0";s:18:"input-border-style";s:4:"none";s:19:"input-border-radius";s:1:"0";s:16:"input-font-style";s:6:"normal";s:17:"input-font-weight";s:6:"bolder";s:11:"input-width";s:0:"";s:16:"input-box-sizing";s:7:"Default";s:14:"input-position";s:8:"Relative";s:16:"input-text-align";s:7:"Default";s:13:"input-z-index";s:4:"9999";s:9:"input-top";s:0:"";s:12:"input-bottom";s:0:"";s:10:"input-left";s:0:"";s:11:"input-right";s:0:"";s:12:"input-height";s:0:"";s:11:"input-hover";s:8:"        ";s:11:"input-focus";s:8:"        ";s:13:"input-padding";s:8:"19px 5px";s:12:"input-margin";s:0:"";s:17:"fileupload-styles";s:0:"";s:25:"textarea-background-color";s:11:"transparent";s:15:"textarea-height";s:0:"";s:14:"textarea-width";s:0:"";s:19:"textarea-transition";s:15:"all 0.3 ease 0s";s:19:"textarea-box-sizing";s:7:"Default";s:17:"textarea-position";s:8:"Relative";s:12:"textarea-top";s:0:"";s:15:"textarea-bottom";s:0:"";s:13:"textarea-left";s:0:"";s:14:"textarea-right";s:0:"";s:14:"textarea-hover";s:8:"        ";s:14:"textarea-focus";s:8:"        ";s:20:"textarea-border-size";s:1:"0";s:21:"textarea-border-color";s:0:"";s:21:"textarea-border-style";s:4:"none";s:22:"textarea-border-radius";s:1:"0";s:18:"textarea-font-size";s:2:"20";s:19:"textarea-text-align";s:7:"Default";s:16:"textarea-z-index";s:4:"9999";s:20:"textarea-line-height";s:0:"";s:19:"textarea-font-style";s:6:"bolder";s:15:"dropdown-styles";s:7:"Default";s:19:"dropdown-background";s:0:"";s:14:"dropdown-color";s:0:"";s:19:"dropdown-transition";s:0:"";s:21:"dropdown-border-color";s:0:"";s:18:"dropdown-font-size";s:0:"";s:20:"dropdown-line-height";s:0:"";s:21:"dropdown-border-width";s:0:"";s:21:"dropdown-border-style";s:7:"Default";s:22:"dropdown-border-radius";s:0:"";s:19:"dropdown-font-style";s:7:"Default";s:20:"dropdown-font-weight";s:7:"Default";s:14:"dropdown-width";s:0:"";s:19:"dropdown-box-sizing";s:7:"Default";s:17:"dropdown-position";s:7:"Default";s:12:"dropdown-top";s:0:"";s:15:"dropdown-bottom";s:0:"";s:13:"dropdown-left";s:0:"";s:14:"dropdown-right";s:0:"";s:19:"dropdown-text-align";s:7:"Default";s:16:"dropdown-z-index";s:0:"";s:15:"dropdown-height";s:0:"";s:16:"dropdown-padding";s:0:"";s:15:"dropdown-margin";s:0:"";s:16:"label-font-style";s:6:"normal";s:17:"label-font-weight";s:6:"bolder";s:15:"label-font-size";s:2:"20";s:17:"label-line-height";s:2:"66";s:14:"label-position";s:8:"Absolute";s:11:"label-width";s:0:"";s:12:"label-height";s:0:"";s:9:"label-top";s:1:"0";s:12:"label-bottom";s:0:"";s:10:"label-left";s:4:"20px";s:18:"label-border-width";s:0:"";s:18:"label-border-style";s:4:"none";s:16:"label-text-align";s:7:"Default";s:13:"label-z-index";s:0:"";s:18:"label-border-color";s:0:"";s:19:"label-border-radius";s:0:"";s:11:"label-after";s:8:"        ";s:12:"label-before";s:8:"        ";s:11:"label-hover";s:8:"        ";s:11:"label-focus";s:8:"        ";s:11:"label-right";s:0:"";s:16:"label-transition";s:16:"all 0.3s ease 0s";s:13:"label-padding";s:0:"";s:11:"label-color";s:7:"#6a7989";s:13:"submit-styles";s:0:"";s:19:"submit-button-width";s:0:"";s:24:"submit-button-box-sizing";s:7:"Default";s:17:"submit-text-align";s:7:"Default";s:14:"submit-z-index";s:0:"";s:20:"submit-button-height";s:0:"";s:27:"submit-button-border-radius";s:0:"";s:23:"submit-button-font-size";s:0:"";s:25:"submit-button-line-height";s:0:"";s:12:"submit-hover";s:8:"        ";s:12:"submit-focus";s:8:"        ";s:15:"submit-position";s:7:"Default";s:10:"submit-top";s:0:"";s:13:"submit-bottom";s:0:"";s:11:"submit-left";s:0:"";s:12:"submit-right";s:0:"";s:17:"submit-transition";s:0:"";s:26:"submit-button-border-width";s:0:"";s:26:"submit-button-border-style";s:7:"Default";s:26:"submit-button-border-color";s:0:"";s:19:"submit-button-color";s:0:"";s:24:"submit-button-background";s:0:"";s:19:"fieldset-background";s:0:"";s:14:"fieldset-width";s:0:"";s:14:"fieldset-after";s:119:"   background:#6a7989;transition:all 0.3s ease 0s;position:absolute;bottom:0;left:0;content:\"\";height:2px;width:100%;";s:15:"fieldset-before";s:116:"   background:#6a7989;transition:all 0.3s ease 0.3s;position:absolute;top:0;left:0;content:\"\";height:0;width:100%;";s:16:"fieldset-display";s:7:"Default";s:23:"fieldset-vertical-align";s:7:"Default";s:19:"fieldset-text-align";s:7:"Default";s:16:"fieldset-z-index";s:0:"";s:14:"fieldset-color";s:0:"";s:21:"fieldset-border-color";s:0:"";s:18:"fieldset-font-size";s:0:"";s:20:"fieldset-line-height";s:0:"";s:19:"fieldset-transition";s:0:"";s:21:"fieldset-border-width";s:0:"";s:21:"fieldset-border-style";s:7:"Default";s:22:"fieldset-border-radius";s:0:"";s:19:"fieldset-font-style";s:7:"Default";s:20:"fieldset-font-weight";s:7:"Default";s:19:"fieldset-box-sizing";s:7:"Default";s:17:"fieldset-position";s:8:"Relative";s:12:"fieldset-top";s:0:"";s:15:"fieldset-bottom";s:0:"";s:13:"fieldset-left";s:0:"";s:14:"fieldset-right";s:0:"";s:15:"fieldset-height";s:0:"";s:16:"fieldset-padding";s:1:"0";s:15:"fieldset-margin";s:10:"0 0 55px 0";}";'

			),
		array (

			'title'     => "Isao",

			'category'  => "Frontend User Style",

			'default_css' => 's:5948:"a:164:{s:15:"form-background";s:7:"#3d4444";s:10:"form-width";s:3:"350";s:15:"form-box-sizing";s:7:"Default";s:13:"form-position";s:7:"Default";s:8:"form-top";s:0:"";s:11:"form-bottom";s:0:"";s:9:"form-left";s:0:"";s:10:"form-right";s:0:"";s:17:"form-border-width";s:0:"";s:15:"form-transition";s:0:"";s:10:"form-hover";s:24:"                        ";s:10:"form-focus";s:24:"                        ";s:17:"form-border-style";s:7:"Default";s:15:"form-text-align";s:7:"Default";s:12:"form-z-index";s:0:"";s:12:"form-padding";s:4:"20px";s:11:"form-margin";s:0:"";s:17:"form-border-color";s:0:"";s:18:"form-border-radius";s:0:"";s:16:"form-line-height";s:0:"";s:16:"input-background";s:11:"transparent";s:11:"input-color";s:7:"#afb3b8";s:18:"input-border-color";s:0:"";s:15:"input-font-size";s:2:"25";s:17:"input-line-height";s:0:"";s:16:"input-transition";s:0:"";s:18:"input-border-width";s:1:"0";s:18:"input-border-style";s:4:"none";s:19:"input-border-radius";s:0:"";s:16:"input-font-style";s:7:"Default";s:17:"input-font-weight";s:4:"bold";s:11:"input-width";s:0:"";s:16:"input-box-sizing";s:7:"Default";s:14:"input-position";s:7:"Default";s:16:"input-text-align";s:7:"Default";s:13:"input-z-index";s:0:"";s:9:"input-top";s:0:"";s:12:"input-bottom";s:0:"";s:10:"input-left";s:0:"";s:11:"input-right";s:0:"";s:12:"input-height";s:0:"";s:11:"input-hover";s:24:"                        ";s:11:"input-focus";s:9:"         ";s:13:"input-padding";s:0:"";s:12:"input-margin";s:0:"";s:17:"fileupload-styles";s:0:"";s:25:"textarea-background-color";s:0:"";s:15:"textarea-height";s:0:"";s:14:"textarea-width";s:0:"";s:19:"textarea-transition";s:0:"";s:19:"textarea-box-sizing";s:7:"Default";s:17:"textarea-position";s:7:"Default";s:12:"textarea-top";s:0:"";s:15:"textarea-bottom";s:0:"";s:13:"textarea-left";s:0:"";s:14:"textarea-right";s:0:"";s:14:"textarea-hover";s:24:"                        ";s:14:"textarea-focus";s:9:"         ";s:20:"textarea-border-size";s:0:"";s:21:"textarea-border-color";s:0:"";s:21:"textarea-border-style";s:7:"Default";s:22:"textarea-border-radius";s:0:"";s:18:"textarea-font-size";s:0:"";s:19:"textarea-text-align";s:7:"Default";s:16:"textarea-z-index";s:0:"";s:20:"textarea-line-height";s:0:"";s:19:"textarea-font-style";s:7:"Default";s:15:"dropdown-styles";s:7:"Default";s:19:"dropdown-background";s:0:"";s:14:"dropdown-color";s:0:"";s:19:"dropdown-transition";s:0:"";s:21:"dropdown-border-color";s:0:"";s:18:"dropdown-font-size";s:0:"";s:20:"dropdown-line-height";s:0:"";s:21:"dropdown-border-width";s:0:"";s:21:"dropdown-border-style";s:7:"Default";s:22:"dropdown-border-radius";s:0:"";s:19:"dropdown-font-style";s:7:"Default";s:20:"dropdown-font-weight";s:7:"Default";s:14:"dropdown-width";s:0:"";s:19:"dropdown-box-sizing";s:7:"Default";s:17:"dropdown-position";s:7:"Default";s:12:"dropdown-top";s:0:"";s:15:"dropdown-bottom";s:0:"";s:13:"dropdown-left";s:0:"";s:14:"dropdown-right";s:0:"";s:19:"dropdown-text-align";s:7:"Default";s:16:"dropdown-z-index";s:0:"";s:15:"dropdown-height";s:0:"";s:16:"dropdown-padding";s:0:"";s:15:"dropdown-margin";s:0:"";s:16:"label-font-style";s:7:"Default";s:17:"label-font-weight";s:4:"bold";s:15:"label-font-size";s:2:"18";s:17:"label-line-height";s:0:"";s:14:"label-position";s:8:"Absolute";s:11:"label-width";s:0:"";s:12:"label-height";s:0:"";s:9:"label-top";s:0:"";s:12:"label-bottom";s:5:"-30px";s:10:"label-left";s:0:"";s:18:"label-border-width";s:1:"0";s:18:"label-border-style";s:4:"none";s:16:"label-text-align";s:7:"Default";s:13:"label-z-index";s:0:"";s:18:"label-border-color";s:0:"";s:19:"label-border-radius";s:0:"";s:11:"label-after";s:24:"                        ";s:12:"label-before";s:24:"                        ";s:11:"label-hover";s:24:"                        ";s:11:"label-focus";s:24:"                        ";s:11:"label-right";s:0:"";s:16:"label-transition";s:0:"";s:13:"label-padding";s:0:"";s:11:"label-color";s:7:"#dadada";s:13:"submit-styles";s:0:"";s:19:"submit-button-width";s:0:"";s:24:"submit-button-box-sizing";s:7:"Default";s:17:"submit-text-align";s:7:"Default";s:14:"submit-z-index";s:0:"";s:20:"submit-button-height";s:0:"";s:27:"submit-button-border-radius";s:0:"";s:23:"submit-button-font-size";s:0:"";s:25:"submit-button-line-height";s:0:"";s:12:"submit-hover";s:24:"                        ";s:12:"submit-focus";s:24:"                        ";s:15:"submit-position";s:7:"Default";s:10:"submit-top";s:0:"";s:13:"submit-bottom";s:0:"";s:11:"submit-left";s:0:"";s:12:"submit-right";s:0:"";s:17:"submit-transition";s:0:"";s:26:"submit-button-border-width";s:0:"";s:26:"submit-button-border-style";s:7:"Default";s:26:"submit-button-border-color";s:0:"";s:19:"submit-button-color";s:0:"";s:24:"submit-button-background";s:0:"";s:19:"fieldset-background";s:11:"transparent";s:14:"fieldset-width";s:0:"";s:14:"fieldset-after";s:127:"       content:\"\";background:#dadada; height:3px; width:100%; bottom:0;transition:all 0.2s ease 0s;position: absolute;left:0;";s:15:"fieldset-before";s:135:"       content:\"\";background:#da7071; height:0; width:100%; bottom:0;transition:all 0.2s ease 0s;position: absolute;left:0;z-index:1;";s:16:"fieldset-display";s:7:"Default";s:23:"fieldset-vertical-align";s:7:"Default";s:19:"fieldset-text-align";s:7:"Default";s:16:"fieldset-z-index";s:0:"";s:14:"fieldset-color";s:0:"";s:21:"fieldset-border-color";s:0:"";s:18:"fieldset-font-size";s:0:"";s:20:"fieldset-line-height";s:0:"";s:19:"fieldset-transition";s:0:"";s:21:"fieldset-border-width";s:1:"0";s:21:"fieldset-border-style";s:4:"none";s:22:"fieldset-border-radius";s:0:"";s:19:"fieldset-font-style";s:7:"Default";s:20:"fieldset-font-weight";s:7:"Default";s:19:"fieldset-box-sizing";s:7:"Default";s:17:"fieldset-position";s:8:"Relative";s:12:"fieldset-top";s:0:"";s:15:"fieldset-bottom";s:0:"";s:13:"fieldset-left";s:0:"";s:14:"fieldset-right";s:0:"";s:15:"fieldset-height";s:0:"";s:16:"fieldset-padding";s:1:"0";s:15:"fieldset-margin";s:10:"0 0 40px 0";}";'

			),
		array (

			'title'     => "Ichiro",

			'category'  => "Frontend User Style",

			'default_css' => 's:5707:"a:164:{s:15:"form-background";s:7:"#f9f7f6";s:10:"form-width";s:3:"350";s:15:"form-box-sizing";s:7:"Default";s:13:"form-position";s:7:"Default";s:8:"form-top";s:0:"";s:11:"form-bottom";s:0:"";s:9:"form-left";s:0:"";s:10:"form-right";s:0:"";s:17:"form-border-width";s:0:"";s:15:"form-transition";s:0:"";s:10:"form-hover";s:14:"              ";s:10:"form-focus";s:14:"              ";s:17:"form-border-style";s:7:"Default";s:15:"form-text-align";s:7:"Default";s:12:"form-z-index";s:0:"";s:12:"form-padding";s:5:"20px ";s:11:"form-margin";s:0:"";s:17:"form-border-color";s:0:"";s:18:"form-border-radius";s:0:"";s:16:"form-line-height";s:0:"";s:16:"input-background";s:7:"#f0f0f0";s:11:"input-color";s:7:"#7f8994";s:18:"input-border-color";s:0:"";s:15:"input-font-size";s:2:"25";s:17:"input-line-height";s:2:"31";s:16:"input-transition";s:16:"all 0.2s ease 0s";s:18:"input-border-width";s:1:"0";s:18:"input-border-style";s:4:"none";s:19:"input-border-radius";s:0:"";s:16:"input-font-style";s:6:"normal";s:17:"input-font-weight";s:6:"bolder";s:11:"input-width";s:0:"";s:16:"input-box-sizing";s:7:"Default";s:14:"input-position";s:8:"Relative";s:16:"input-text-align";s:7:"Default";s:13:"input-z-index";s:0:"";s:9:"input-top";s:0:"";s:12:"input-bottom";s:0:"";s:10:"input-left";s:0:"";s:11:"input-right";s:0:"";s:12:"input-height";s:1:"0";s:11:"input-hover";s:14:"              ";s:11:"input-focus";s:14:"              ";s:13:"input-padding";s:1:"0";s:12:"input-margin";s:0:"";s:17:"fileupload-styles";s:0:"";s:25:"textarea-background-color";s:7:"#f0f0f0";s:15:"textarea-height";s:1:"0";s:14:"textarea-width";s:0:"";s:19:"textarea-transition";s:16:"all 0.2s ease 0s";s:19:"textarea-box-sizing";s:7:"Default";s:17:"textarea-position";s:8:"Relative";s:12:"textarea-top";s:0:"";s:15:"textarea-bottom";s:0:"";s:13:"textarea-left";s:0:"";s:14:"textarea-right";s:0:"";s:14:"textarea-hover";s:14:"              ";s:14:"textarea-focus";s:14:"              ";s:20:"textarea-border-size";s:1:"0";s:21:"textarea-border-color";s:0:"";s:21:"textarea-border-style";s:4:"none";s:22:"textarea-border-radius";s:1:"0";s:18:"textarea-font-size";s:2:"17";s:19:"textarea-text-align";s:7:"Default";s:16:"textarea-z-index";s:0:"";s:20:"textarea-line-height";s:0:"";s:19:"textarea-font-style";s:6:"bolder";s:15:"dropdown-styles";s:7:"Default";s:19:"dropdown-background";s:0:"";s:14:"dropdown-color";s:0:"";s:19:"dropdown-transition";s:0:"";s:21:"dropdown-border-color";s:0:"";s:18:"dropdown-font-size";s:0:"";s:20:"dropdown-line-height";s:0:"";s:21:"dropdown-border-width";s:0:"";s:21:"dropdown-border-style";s:7:"Default";s:22:"dropdown-border-radius";s:0:"";s:19:"dropdown-font-style";s:7:"Default";s:20:"dropdown-font-weight";s:7:"Default";s:14:"dropdown-width";s:0:"";s:19:"dropdown-box-sizing";s:7:"Default";s:17:"dropdown-position";s:7:"Default";s:12:"dropdown-top";s:0:"";s:15:"dropdown-bottom";s:0:"";s:13:"dropdown-left";s:0:"";s:14:"dropdown-right";s:0:"";s:19:"dropdown-text-align";s:7:"Default";s:16:"dropdown-z-index";s:0:"";s:15:"dropdown-height";s:0:"";s:16:"dropdown-padding";s:0:"";s:15:"dropdown-margin";s:0:"";s:16:"label-font-style";s:6:"normal";s:17:"label-font-weight";s:6:"bolder";s:15:"label-font-size";s:2:"17";s:17:"label-line-height";s:0:"";s:14:"label-position";s:7:"Default";s:11:"label-width";s:0:"";s:12:"label-height";s:0:"";s:9:"label-top";s:0:"";s:12:"label-bottom";s:0:"";s:10:"label-left";s:4:"15px";s:18:"label-border-width";s:1:"0";s:18:"label-border-style";s:4:"none";s:16:"label-text-align";s:7:"Default";s:13:"label-z-index";s:0:"";s:18:"label-border-color";s:0:"";s:19:"label-border-radius";s:1:"0";s:11:"label-after";s:14:"              ";s:12:"label-before";s:14:"              ";s:11:"label-hover";s:14:"              ";s:11:"label-focus";s:14:"              ";s:11:"label-right";s:0:"";s:16:"label-transition";s:16:"all 0.2s ease 0s";s:13:"label-padding";s:14:"20px 10px 28px";s:11:"label-color";s:7:"#6a7989";s:13:"submit-styles";s:0:"";s:19:"submit-button-width";s:3:"170";s:24:"submit-button-box-sizing";s:7:"Default";s:17:"submit-text-align";s:7:"Default";s:14:"submit-z-index";s:0:"";s:20:"submit-button-height";s:2:"60";s:27:"submit-button-border-radius";s:0:"";s:23:"submit-button-font-size";s:0:"";s:25:"submit-button-line-height";s:0:"";s:12:"submit-hover";s:14:"              ";s:12:"submit-focus";s:14:"              ";s:15:"submit-position";s:7:"Default";s:10:"submit-top";s:0:"";s:13:"submit-bottom";s:0:"";s:11:"submit-left";s:0:"";s:12:"submit-right";s:0:"";s:17:"submit-transition";s:0:"";s:26:"submit-button-border-width";s:0:"";s:26:"submit-button-border-style";s:7:"Default";s:26:"submit-button-border-color";s:0:"";s:19:"submit-button-color";s:0:"";s:24:"submit-button-background";s:0:"";s:19:"fieldset-background";s:7:"#ffffff";s:14:"fieldset-width";s:0:"";s:14:"fieldset-after";s:14:"              ";s:15:"fieldset-before";s:14:"              ";s:16:"fieldset-display";s:7:"Default";s:23:"fieldset-vertical-align";s:7:"Default";s:19:"fieldset-text-align";s:7:"Default";s:16:"fieldset-z-index";s:0:"";s:14:"fieldset-color";s:0:"";s:21:"fieldset-border-color";s:0:"";s:18:"fieldset-font-size";s:0:"";s:20:"fieldset-line-height";s:0:"";s:19:"fieldset-transition";s:0:"";s:21:"fieldset-border-width";s:1:"0";s:21:"fieldset-border-style";s:4:"none";s:22:"fieldset-border-radius";s:1:"0";s:19:"fieldset-font-style";s:7:"Default";s:20:"fieldset-font-weight";s:7:"Default";s:19:"fieldset-box-sizing";s:7:"Default";s:17:"fieldset-position";s:8:"Relative";s:12:"fieldset-top";s:0:"";s:15:"fieldset-bottom";s:0:"";s:13:"fieldset-left";s:0:"";s:14:"fieldset-right";s:0:"";s:15:"fieldset-height";s:0:"";s:16:"fieldset-padding";s:16:"10px 4px 4px 4px";s:15:"fieldset-margin";s:0:"";}";'

			),
		array (

			'title'     => "Juro",

			'category'  => "Frontend User Style",

			'default_css' => 's:5618:"a:164:{s:15:"form-background";s:7:"#2fa8ec";s:10:"form-width";s:3:"350";s:15:"form-box-sizing";s:7:"Default";s:13:"form-position";s:7:"Default";s:8:"form-top";s:0:"";s:11:"form-bottom";s:0:"";s:9:"form-left";s:0:"";s:10:"form-right";s:0:"";s:17:"form-border-width";s:0:"";s:15:"form-transition";s:0:"";s:10:"form-hover";s:10:"          ";s:10:"form-focus";s:10:"          ";s:17:"form-border-style";s:7:"Default";s:15:"form-text-align";s:7:"Default";s:12:"form-z-index";s:0:"";s:12:"form-padding";s:4:"20px";s:11:"form-margin";s:0:"";s:17:"form-border-color";s:0:"";s:18:"form-border-radius";s:0:"";s:16:"form-line-height";s:0:"";s:16:"input-background";s:7:"#ffffff";s:11:"input-color";s:7:"#1784cd";s:18:"input-border-color";s:0:"";s:15:"input-font-size";s:2:"21";s:17:"input-line-height";s:0:"";s:16:"input-transition";s:16:"all 0.3s ease 0s";s:18:"input-border-width";s:1:"0";s:18:"input-border-style";s:4:"none";s:19:"input-border-radius";s:1:"0";s:16:"input-font-style";s:7:"Default";s:17:"input-font-weight";s:4:"bold";s:11:"input-width";s:0:"";s:16:"input-box-sizing";s:7:"Default";s:14:"input-position";s:8:"Relative";s:16:"input-text-align";s:7:"Default";s:13:"input-z-index";s:0:"";s:9:"input-top";s:0:"";s:12:"input-bottom";s:0:"";s:10:"input-left";s:0:"";s:11:"input-right";s:0:"";s:12:"input-height";s:0:"";s:11:"input-hover";s:10:"          ";s:11:"input-focus";s:10:"          ";s:13:"input-padding";s:9:"30px 15px";s:12:"input-margin";s:0:"";s:17:"fileupload-styles";s:0:"";s:25:"textarea-background-color";s:0:"";s:15:"textarea-height";s:0:"";s:14:"textarea-width";s:0:"";s:19:"textarea-transition";s:0:"";s:19:"textarea-box-sizing";s:7:"Default";s:17:"textarea-position";s:7:"Default";s:12:"textarea-top";s:0:"";s:15:"textarea-bottom";s:0:"";s:13:"textarea-left";s:0:"";s:14:"textarea-right";s:0:"";s:14:"textarea-hover";s:10:"          ";s:14:"textarea-focus";s:10:"          ";s:20:"textarea-border-size";s:0:"";s:21:"textarea-border-color";s:0:"";s:21:"textarea-border-style";s:7:"Default";s:22:"textarea-border-radius";s:0:"";s:18:"textarea-font-size";s:0:"";s:19:"textarea-text-align";s:7:"Default";s:16:"textarea-z-index";s:0:"";s:20:"textarea-line-height";s:0:"";s:19:"textarea-font-style";s:7:"Default";s:15:"dropdown-styles";s:7:"Default";s:19:"dropdown-background";s:0:"";s:14:"dropdown-color";s:0:"";s:19:"dropdown-transition";s:0:"";s:21:"dropdown-border-color";s:0:"";s:18:"dropdown-font-size";s:0:"";s:20:"dropdown-line-height";s:0:"";s:21:"dropdown-border-width";s:0:"";s:21:"dropdown-border-style";s:7:"Default";s:22:"dropdown-border-radius";s:0:"";s:19:"dropdown-font-style";s:7:"Default";s:20:"dropdown-font-weight";s:7:"Default";s:14:"dropdown-width";s:0:"";s:19:"dropdown-box-sizing";s:7:"Default";s:17:"dropdown-position";s:7:"Default";s:12:"dropdown-top";s:0:"";s:15:"dropdown-bottom";s:0:"";s:13:"dropdown-left";s:0:"";s:14:"dropdown-right";s:0:"";s:19:"dropdown-text-align";s:7:"Default";s:16:"dropdown-z-index";s:0:"";s:15:"dropdown-height";s:0:"";s:16:"dropdown-padding";s:0:"";s:15:"dropdown-margin";s:0:"";s:16:"label-font-style";s:7:"Default";s:17:"label-font-weight";s:4:"bold";s:15:"label-font-size";s:2:"17";s:17:"label-line-height";s:0:"";s:14:"label-position";s:8:"Absolute";s:11:"label-width";s:0:"";s:12:"label-height";s:0:"";s:9:"label-top";s:4:"32px";s:12:"label-bottom";s:0:"";s:10:"label-left";s:4:"15px";s:18:"label-border-width";s:1:"0";s:18:"label-border-style";s:7:"Default";s:16:"label-text-align";s:7:"Default";s:13:"label-z-index";s:1:"9";s:18:"label-border-color";s:0:"";s:19:"label-border-radius";s:1:"0";s:11:"label-after";s:10:"          ";s:12:"label-before";s:10:"          ";s:11:"label-hover";s:10:"          ";s:11:"label-focus";s:10:"          ";s:11:"label-right";s:0:"";s:16:"label-transition";s:16:"all 0.3s ease 0s";s:13:"label-padding";s:0:"";s:11:"label-color";s:7:"#6a7989";s:13:"submit-styles";s:0:"";s:19:"submit-button-width";s:0:"";s:24:"submit-button-box-sizing";s:7:"Default";s:17:"submit-text-align";s:7:"Default";s:14:"submit-z-index";s:0:"";s:20:"submit-button-height";s:0:"";s:27:"submit-button-border-radius";s:0:"";s:23:"submit-button-font-size";s:0:"";s:25:"submit-button-line-height";s:0:"";s:12:"submit-hover";s:10:"          ";s:12:"submit-focus";s:10:"          ";s:15:"submit-position";s:7:"Default";s:10:"submit-top";s:0:"";s:13:"submit-bottom";s:0:"";s:11:"submit-left";s:0:"";s:12:"submit-right";s:0:"";s:17:"submit-transition";s:0:"";s:26:"submit-button-border-width";s:0:"";s:26:"submit-button-border-style";s:7:"Default";s:26:"submit-button-border-color";s:0:"";s:19:"submit-button-color";s:0:"";s:24:"submit-button-background";s:0:"";s:19:"fieldset-background";s:7:"#1784cd";s:14:"fieldset-width";s:0:"";s:14:"fieldset-after";s:10:"          ";s:15:"fieldset-before";s:10:"          ";s:16:"fieldset-display";s:7:"Default";s:23:"fieldset-vertical-align";s:7:"Default";s:19:"fieldset-text-align";s:7:"Default";s:16:"fieldset-z-index";s:0:"";s:14:"fieldset-color";s:0:"";s:21:"fieldset-border-color";s:0:"";s:18:"fieldset-font-size";s:0:"";s:20:"fieldset-line-height";s:0:"";s:19:"fieldset-transition";s:16:"all 0.3s ease 0s";s:21:"fieldset-border-width";s:1:"0";s:21:"fieldset-border-style";s:4:"none";s:22:"fieldset-border-radius";s:1:"0";s:19:"fieldset-font-style";s:7:"Default";s:20:"fieldset-font-weight";s:7:"Default";s:19:"fieldset-box-sizing";s:7:"Default";s:17:"fieldset-position";s:8:"Relative";s:12:"fieldset-top";s:0:"";s:15:"fieldset-bottom";s:0:"";s:13:"fieldset-left";s:0:"";s:14:"fieldset-right";s:0:"";s:15:"fieldset-height";s:0:"";s:16:"fieldset-padding";s:1:"0";s:15:"fieldset-margin";s:0:"";}";'

			),
		array (

			'title'     => "Madoka",

			'category'  => "Frontend User Style",

			'default_css' => 's:6127:"a:164:{s:15:"form-background";s:7:"#2f3238";s:10:"form-width";s:3:"350";s:15:"form-box-sizing";s:7:"Default";s:13:"form-position";s:7:"Default";s:8:"form-top";s:0:"";s:11:"form-bottom";s:0:"";s:9:"form-left";s:0:"";s:10:"form-right";s:0:"";s:17:"form-border-width";s:0:"";s:15:"form-transition";s:0:"";s:10:"form-hover";s:24:"                        ";s:10:"form-focus";s:24:"                        ";s:17:"form-border-style";s:7:"Default";s:15:"form-text-align";s:7:"Default";s:12:"form-z-index";s:0:"";s:12:"form-padding";s:4:"20px";s:11:"form-margin";s:0:"";s:17:"form-border-color";s:0:"";s:18:"form-border-radius";s:0:"";s:16:"form-line-height";s:0:"";s:16:"input-background";s:11:"transparent";s:11:"input-color";s:7:"#7a7593";s:18:"input-border-color";s:7:"#7a7593";s:15:"input-font-size";s:2:"25";s:17:"input-line-height";s:0:"";s:16:"input-transition";s:17:"all 0.2s ease 0.2";s:18:"input-border-width";s:9:"0 0 2px 0";s:18:"input-border-style";s:5:"solid";s:19:"input-border-radius";s:1:"0";s:16:"input-font-style";s:7:"Default";s:17:"input-font-weight";s:4:"bold";s:11:"input-width";s:0:"";s:16:"input-box-sizing";s:7:"Default";s:14:"input-position";s:8:"Relative";s:16:"input-text-align";s:7:"Default";s:13:"input-z-index";s:0:"";s:9:"input-top";s:0:"";s:12:"input-bottom";s:0:"";s:10:"input-left";s:0:"";s:11:"input-right";s:0:"";s:12:"input-height";s:0:"";s:11:"input-hover";s:24:"                        ";s:11:"input-focus";s:5:"     ";s:13:"input-padding";s:4:"15px";s:12:"input-margin";s:1:"0";s:17:"fileupload-styles";s:0:"";s:25:"textarea-background-color";s:0:"";s:15:"textarea-height";s:0:"";s:14:"textarea-width";s:0:"";s:19:"textarea-transition";s:0:"";s:19:"textarea-box-sizing";s:7:"Default";s:17:"textarea-position";s:7:"Default";s:12:"textarea-top";s:0:"";s:15:"textarea-bottom";s:0:"";s:13:"textarea-left";s:0:"";s:14:"textarea-right";s:0:"";s:14:"textarea-hover";s:24:"                        ";s:14:"textarea-focus";s:24:"                        ";s:20:"textarea-border-size";s:0:"";s:21:"textarea-border-color";s:0:"";s:21:"textarea-border-style";s:7:"Default";s:22:"textarea-border-radius";s:0:"";s:18:"textarea-font-size";s:2:"17";s:19:"textarea-text-align";s:7:"Default";s:16:"textarea-z-index";s:0:"";s:20:"textarea-line-height";s:0:"";s:19:"textarea-font-style";s:6:"normal";s:15:"dropdown-styles";s:7:"Default";s:19:"dropdown-background";s:0:"";s:14:"dropdown-color";s:0:"";s:19:"dropdown-transition";s:0:"";s:21:"dropdown-border-color";s:0:"";s:18:"dropdown-font-size";s:0:"";s:20:"dropdown-line-height";s:0:"";s:21:"dropdown-border-width";s:0:"";s:21:"dropdown-border-style";s:7:"Default";s:22:"dropdown-border-radius";s:0:"";s:19:"dropdown-font-style";s:7:"Default";s:20:"dropdown-font-weight";s:7:"Default";s:14:"dropdown-width";s:0:"";s:19:"dropdown-box-sizing";s:7:"Default";s:17:"dropdown-position";s:7:"Default";s:12:"dropdown-top";s:0:"";s:15:"dropdown-bottom";s:0:"";s:13:"dropdown-left";s:0:"";s:14:"dropdown-right";s:0:"";s:19:"dropdown-text-align";s:7:"Default";s:16:"dropdown-z-index";s:0:"";s:15:"dropdown-height";s:0:"";s:16:"dropdown-padding";s:0:"";s:15:"dropdown-margin";s:0:"";s:16:"label-font-style";s:6:"normal";s:17:"label-font-weight";s:4:"bold";s:15:"label-font-size";s:2:"17";s:17:"label-line-height";s:0:"";s:14:"label-position";s:8:"Absolute";s:11:"label-width";s:0:"";s:12:"label-height";s:0:"";s:9:"label-top";s:0:"";s:12:"label-bottom";s:4:"22px";s:10:"label-left";s:4:"15px";s:18:"label-border-width";s:1:"0";s:18:"label-border-style";s:4:"none";s:16:"label-text-align";s:7:"Default";s:13:"label-z-index";s:0:"";s:18:"label-border-color";s:0:"";s:19:"label-border-radius";s:0:"";s:11:"label-after";s:125:"          background:#7a7593; content:\"\";position:absolute;top:0; left:0;width:2px; height:0;transition:all 0.1s ease 0.3s;";s:12:"label-before";s:24:"                        ";s:11:"label-hover";s:24:"                        ";s:11:"label-focus";s:24:"                        ";s:11:"label-right";s:0:"";s:16:"label-transition";s:16:"all 0.3s ease 0s";s:13:"label-padding";s:0:"";s:11:"label-color";s:7:"#6a7989";s:13:"submit-styles";s:0:"";s:19:"submit-button-width";s:0:"";s:24:"submit-button-box-sizing";s:7:"Default";s:17:"submit-text-align";s:7:"Default";s:14:"submit-z-index";s:0:"";s:20:"submit-button-height";s:0:"";s:27:"submit-button-border-radius";s:0:"";s:23:"submit-button-font-size";s:0:"";s:25:"submit-button-line-height";s:0:"";s:12:"submit-hover";s:24:"                        ";s:12:"submit-focus";s:24:"                        ";s:15:"submit-position";s:7:"Default";s:10:"submit-top";s:0:"";s:13:"submit-bottom";s:0:"";s:11:"submit-left";s:0:"";s:12:"submit-right";s:0:"";s:17:"submit-transition";s:0:"";s:26:"submit-button-border-width";s:0:"";s:26:"submit-button-border-style";s:7:"Default";s:26:"submit-button-border-color";s:0:"";s:19:"submit-button-color";s:0:"";s:24:"submit-button-background";s:0:"";s:19:"fieldset-background";s:0:"";s:14:"fieldset-width";s:0:"";s:14:"fieldset-after";s:151:"          background:#7a7593; content:\"\";position:absolute;right:0;bottom:0; right:0;width:2px; height:0;transition:all 0.1s ease 0.1s;              ";s:15:"fieldset-before";s:139:"          background:#7a7593; content:\"\";position:absolute;top:0;right:0;width:0; height:2px;transition:all 0.1s ease 0.2s;              ";s:16:"fieldset-display";s:7:"Default";s:23:"fieldset-vertical-align";s:7:"Default";s:19:"fieldset-text-align";s:7:"Default";s:16:"fieldset-z-index";s:0:"";s:14:"fieldset-color";s:0:"";s:21:"fieldset-border-color";s:0:"";s:18:"fieldset-font-size";s:0:"";s:20:"fieldset-line-height";s:0:"";s:19:"fieldset-transition";s:0:"";s:21:"fieldset-border-width";s:0:"";s:21:"fieldset-border-style";s:4:"none";s:22:"fieldset-border-radius";s:0:"";s:19:"fieldset-font-style";s:7:"Default";s:20:"fieldset-font-weight";s:7:"Default";s:19:"fieldset-box-sizing";s:7:"Default";s:17:"fieldset-position";s:8:"Relative";s:12:"fieldset-top";s:0:"";s:15:"fieldset-bottom";s:0:"";s:13:"fieldset-left";s:0:"";s:14:"fieldset-right";s:0:"";s:15:"fieldset-height";s:0:"";s:16:"fieldset-padding";s:1:"0";s:15:"fieldset-margin";s:0:"";}";'

			),
		array (

			'title'     => "Kaede",

			'category'  => "Frontend User Style",

			'default_css' => 's:5380:"a:161:{s:15:"form-background";s:7:"#ffffff";s:10:"form-width";s:3:"400";s:15:"form-box-sizing";s:7:"Default";s:13:"form-position";s:7:"Default";s:8:"form-top";s:0:"";s:11:"form-bottom";s:0:"";s:9:"form-left";s:0:"";s:10:"form-right";s:0:"";s:17:"form-border-width";s:0:"";s:15:"form-transition";s:0:"";s:10:"form-hover";s:7:"       ";s:10:"form-focus";s:7:"       ";s:17:"form-border-style";s:4:"none";s:15:"form-text-align";s:7:"Default";s:12:"form-z-index";s:0:"";s:12:"form-padding";s:0:"";s:11:"form-margin";s:0:"";s:17:"form-border-color";s:0:"";s:18:"form-border-radius";s:0:"";s:16:"form-line-height";s:0:"";s:16:"input-background";s:7:"#efeeee";s:11:"input-color";s:0:"";s:18:"input-border-color";s:0:"";s:15:"input-font-size";s:2:"17";s:17:"input-line-height";s:0:"";s:16:"input-transition";s:0:"";s:18:"input-border-width";s:0:"";s:18:"input-border-style";s:4:"none";s:19:"input-border-radius";s:1:"0";s:16:"input-font-style";s:7:"Default";s:17:"input-font-weight";s:7:"Default";s:11:"input-width";s:0:"";s:16:"input-box-sizing";s:7:"Default";s:14:"input-position";s:7:"Default";s:16:"input-text-align";s:7:"Default";s:13:"input-z-index";s:0:"";s:9:"input-top";s:0:"";s:12:"input-bottom";s:0:"";s:10:"input-left";s:0:"";s:11:"input-right";s:0:"";s:12:"input-height";s:0:"";s:11:"input-hover";s:7:"       ";s:11:"input-focus";s:7:"       ";s:13:"input-padding";s:0:"";s:12:"input-margin";s:0:"";s:25:"textarea-background-color";s:7:"#efeeee";s:15:"textarea-height";s:0:"";s:14:"textarea-width";s:0:"";s:19:"textarea-transition";s:0:"";s:19:"textarea-box-sizing";s:7:"Default";s:17:"textarea-position";s:7:"Default";s:12:"textarea-top";s:0:"";s:15:"textarea-bottom";s:0:"";s:13:"textarea-left";s:0:"";s:14:"textarea-right";s:0:"";s:14:"textarea-hover";s:7:"       ";s:14:"textarea-focus";s:7:"       ";s:20:"textarea-border-size";s:0:"";s:21:"textarea-border-color";s:0:"";s:21:"textarea-border-style";s:7:"Default";s:22:"textarea-border-radius";s:0:"";s:18:"textarea-font-size";s:0:"";s:19:"textarea-text-align";s:7:"Default";s:16:"textarea-z-index";s:0:"";s:20:"textarea-line-height";s:0:"";s:19:"textarea-font-style";s:7:"Default";s:19:"dropdown-background";s:0:"";s:14:"dropdown-color";s:0:"";s:19:"dropdown-transition";s:0:"";s:21:"dropdown-border-color";s:0:"";s:18:"dropdown-font-size";s:0:"";s:20:"dropdown-line-height";s:0:"";s:21:"dropdown-border-width";s:0:"";s:21:"dropdown-border-style";s:7:"Default";s:22:"dropdown-border-radius";s:0:"";s:19:"dropdown-font-style";s:7:"Default";s:20:"dropdown-font-weight";s:7:"Default";s:14:"dropdown-width";s:0:"";s:19:"dropdown-box-sizing";s:7:"Default";s:17:"dropdown-position";s:7:"Default";s:12:"dropdown-top";s:0:"";s:15:"dropdown-bottom";s:0:"";s:13:"dropdown-left";s:0:"";s:14:"dropdown-right";s:0:"";s:19:"dropdown-text-align";s:7:"Default";s:16:"dropdown-z-index";s:0:"";s:15:"dropdown-height";s:0:"";s:16:"dropdown-padding";s:0:"";s:15:"dropdown-margin";s:0:"";s:16:"label-font-style";s:7:"Default";s:17:"label-font-weight";s:7:"Default";s:15:"label-font-size";s:0:"";s:17:"label-line-height";s:0:"";s:14:"label-position";s:7:"Default";s:11:"label-width";s:0:"";s:12:"label-height";s:0:"";s:9:"label-top";s:0:"";s:12:"label-bottom";s:0:"";s:10:"label-left";s:0:"";s:18:"label-border-width";s:0:"";s:18:"label-border-style";s:7:"Default";s:16:"label-text-align";s:7:"Default";s:13:"label-z-index";s:0:"";s:18:"label-border-color";s:0:"";s:19:"label-border-radius";s:0:"";s:11:"label-after";s:7:"       ";s:12:"label-before";s:7:"       ";s:11:"label-hover";s:7:"       ";s:11:"label-focus";s:7:"       ";s:11:"label-right";s:0:"";s:16:"label-transition";s:0:"";s:13:"label-padding";s:0:"";s:11:"label-color";s:7:"#6a7989";s:19:"submit-button-width";s:0:"";s:24:"submit-button-box-sizing";s:7:"Default";s:17:"submit-text-align";s:7:"Default";s:14:"submit-z-index";s:0:"";s:20:"submit-button-height";s:0:"";s:27:"submit-button-border-radius";s:0:"";s:23:"submit-button-font-size";s:0:"";s:25:"submit-button-line-height";s:0:"";s:12:"submit-hover";s:7:"       ";s:12:"submit-focus";s:7:"       ";s:15:"submit-position";s:7:"Default";s:10:"submit-top";s:0:"";s:13:"submit-bottom";s:0:"";s:11:"submit-left";s:0:"";s:12:"submit-right";s:0:"";s:17:"submit-transition";s:0:"";s:26:"submit-button-border-width";s:0:"";s:26:"submit-button-border-style";s:7:"Default";s:26:"submit-button-border-color";s:0:"";s:19:"submit-button-color";s:0:"";s:24:"submit-button-background";s:0:"";s:19:"fieldset-background";s:0:"";s:14:"fieldset-width";s:0:"";s:14:"fieldset-after";s:7:"       ";s:15:"fieldset-before";s:7:"       ";s:16:"fieldset-display";s:7:"Default";s:23:"fieldset-vertical-align";s:7:"Default";s:19:"fieldset-text-align";s:7:"Default";s:16:"fieldset-z-index";s:0:"";s:14:"fieldset-color";s:0:"";s:21:"fieldset-border-color";s:0:"";s:18:"fieldset-font-size";s:0:"";s:20:"fieldset-line-height";s:0:"";s:19:"fieldset-transition";s:0:"";s:21:"fieldset-border-width";s:0:"";s:21:"fieldset-border-style";s:7:"Default";s:22:"fieldset-border-radius";s:0:"";s:19:"fieldset-font-style";s:7:"Default";s:20:"fieldset-font-weight";s:7:"Default";s:19:"fieldset-box-sizing";s:7:"Default";s:17:"fieldset-position";s:7:"Default";s:12:"fieldset-top";s:0:"";s:15:"fieldset-bottom";s:0:"";s:13:"fieldset-left";s:0:"";s:14:"fieldset-right";s:0:"";s:15:"fieldset-height";s:0:"";s:16:"fieldset-padding";s:0:"";s:15:"fieldset-margin";s:0:"";}";'

			),
		array (

			'title'     => "Manami",

			'category'  => "Frontend User Style",

			'default_css' => 's:5397:"a:161:{s:15:"form-background";s:7:"#f0efee";s:10:"form-width";s:0:"";s:15:"form-box-sizing";s:7:"Default";s:13:"form-position";s:7:"Default";s:8:"form-top";s:0:"";s:11:"form-bottom";s:0:"";s:9:"form-left";s:0:"";s:10:"form-right";s:0:"";s:17:"form-border-width";s:0:"";s:15:"form-transition";s:0:"";s:10:"form-hover";s:7:"       ";s:10:"form-focus";s:7:"       ";s:17:"form-border-style";s:7:"Default";s:15:"form-text-align";s:7:"Default";s:12:"form-z-index";s:0:"";s:12:"form-padding";s:0:"";s:11:"form-margin";s:0:"";s:17:"form-border-color";s:0:"";s:18:"form-border-radius";s:0:"";s:16:"form-line-height";s:0:"";s:16:"input-background";s:7:"#f0efee";s:11:"input-color";s:0:"";s:18:"input-border-color";s:0:"";s:15:"input-font-size";s:2:"17";s:17:"input-line-height";s:0:"";s:16:"input-transition";s:0:"";s:18:"input-border-width";s:0:"";s:18:"input-border-style";s:4:"none";s:19:"input-border-radius";s:0:"";s:16:"input-font-style";s:7:"Default";s:17:"input-font-weight";s:7:"Default";s:11:"input-width";s:0:"";s:16:"input-box-sizing";s:7:"Default";s:14:"input-position";s:7:"Default";s:16:"input-text-align";s:7:"Default";s:13:"input-z-index";s:0:"";s:9:"input-top";s:0:"";s:12:"input-bottom";s:0:"";s:10:"input-left";s:0:"";s:11:"input-right";s:0:"";s:12:"input-height";s:0:"";s:11:"input-hover";s:7:"       ";s:11:"input-focus";s:7:"       ";s:13:"input-padding";s:0:"";s:12:"input-margin";s:0:"";s:25:"textarea-background-color";s:7:"#f0efee";s:15:"textarea-height";s:0:"";s:14:"textarea-width";s:0:"";s:19:"textarea-transition";s:0:"";s:19:"textarea-box-sizing";s:7:"Default";s:17:"textarea-position";s:7:"Default";s:12:"textarea-top";s:0:"";s:15:"textarea-bottom";s:0:"";s:13:"textarea-left";s:0:"";s:14:"textarea-right";s:0:"";s:14:"textarea-hover";s:7:"       ";s:14:"textarea-focus";s:7:"       ";s:20:"textarea-border-size";s:0:"";s:21:"textarea-border-color";s:7:"#f0efee";s:21:"textarea-border-style";s:7:"Default";s:22:"textarea-border-radius";s:0:"";s:18:"textarea-font-size";s:2:"17";s:19:"textarea-text-align";s:7:"Default";s:16:"textarea-z-index";s:0:"";s:20:"textarea-line-height";s:0:"";s:19:"textarea-font-style";s:7:"Default";s:19:"dropdown-background";s:0:"";s:14:"dropdown-color";s:0:"";s:19:"dropdown-transition";s:0:"";s:21:"dropdown-border-color";s:0:"";s:18:"dropdown-font-size";s:0:"";s:20:"dropdown-line-height";s:0:"";s:21:"dropdown-border-width";s:0:"";s:21:"dropdown-border-style";s:7:"Default";s:22:"dropdown-border-radius";s:0:"";s:19:"dropdown-font-style";s:7:"Default";s:20:"dropdown-font-weight";s:7:"Default";s:14:"dropdown-width";s:0:"";s:19:"dropdown-box-sizing";s:7:"Default";s:17:"dropdown-position";s:7:"Default";s:12:"dropdown-top";s:0:"";s:15:"dropdown-bottom";s:0:"";s:13:"dropdown-left";s:0:"";s:14:"dropdown-right";s:0:"";s:19:"dropdown-text-align";s:7:"Default";s:16:"dropdown-z-index";s:0:"";s:15:"dropdown-height";s:0:"";s:16:"dropdown-padding";s:0:"";s:15:"dropdown-margin";s:0:"";s:16:"label-font-style";s:7:"Default";s:17:"label-font-weight";s:7:"Default";s:15:"label-font-size";s:2:"17";s:17:"label-line-height";s:0:"";s:14:"label-position";s:7:"Default";s:11:"label-width";s:0:"";s:12:"label-height";s:0:"";s:9:"label-top";s:0:"";s:12:"label-bottom";s:0:"";s:10:"label-left";s:0:"";s:18:"label-border-width";s:0:"";s:18:"label-border-style";s:7:"Default";s:16:"label-text-align";s:7:"Default";s:13:"label-z-index";s:0:"";s:18:"label-border-color";s:7:"#f0efee";s:19:"label-border-radius";s:0:"";s:11:"label-after";s:7:"       ";s:12:"label-before";s:7:"       ";s:11:"label-hover";s:7:"       ";s:11:"label-focus";s:7:"       ";s:11:"label-right";s:0:"";s:16:"label-transition";s:0:"";s:13:"label-padding";s:0:"";s:11:"label-color";s:7:"#404d5b";s:19:"submit-button-width";s:0:"";s:24:"submit-button-box-sizing";s:7:"Default";s:17:"submit-text-align";s:7:"Default";s:14:"submit-z-index";s:0:"";s:20:"submit-button-height";s:0:"";s:27:"submit-button-border-radius";s:0:"";s:23:"submit-button-font-size";s:0:"";s:25:"submit-button-line-height";s:0:"";s:12:"submit-hover";s:7:"       ";s:12:"submit-focus";s:7:"       ";s:15:"submit-position";s:7:"Default";s:10:"submit-top";s:0:"";s:13:"submit-bottom";s:0:"";s:11:"submit-left";s:0:"";s:12:"submit-right";s:0:"";s:17:"submit-transition";s:0:"";s:26:"submit-button-border-width";s:0:"";s:26:"submit-button-border-style";s:7:"Default";s:26:"submit-button-border-color";s:0:"";s:19:"submit-button-color";s:0:"";s:24:"submit-button-background";s:0:"";s:19:"fieldset-background";s:0:"";s:14:"fieldset-width";s:0:"";s:14:"fieldset-after";s:7:"       ";s:15:"fieldset-before";s:7:"       ";s:16:"fieldset-display";s:7:"Default";s:23:"fieldset-vertical-align";s:7:"Default";s:19:"fieldset-text-align";s:7:"Default";s:16:"fieldset-z-index";s:0:"";s:14:"fieldset-color";s:0:"";s:21:"fieldset-border-color";s:0:"";s:18:"fieldset-font-size";s:2:"17";s:20:"fieldset-line-height";s:0:"";s:19:"fieldset-transition";s:0:"";s:21:"fieldset-border-width";s:0:"";s:21:"fieldset-border-style";s:4:"none";s:22:"fieldset-border-radius";s:1:"0";s:19:"fieldset-font-style";s:7:"Default";s:20:"fieldset-font-weight";s:7:"Default";s:19:"fieldset-box-sizing";s:7:"Default";s:17:"fieldset-position";s:7:"Default";s:12:"fieldset-top";s:0:"";s:15:"fieldset-bottom";s:0:"";s:13:"fieldset-left";s:0:"";s:14:"fieldset-right";s:0:"";s:15:"fieldset-height";s:0:"";s:16:"fieldset-padding";s:0:"";s:15:"fieldset-margin";s:0:"";}";'

			),
		array (

			'title'     => "Sae",

			'category'  => "Frontend User Style",

			'default_css' => 's:5380:"a:161:{s:15:"form-background";s:7:"#ffffff";s:10:"form-width";s:3:"400";s:15:"form-box-sizing";s:7:"Default";s:13:"form-position";s:7:"Default";s:8:"form-top";s:0:"";s:11:"form-bottom";s:0:"";s:9:"form-left";s:0:"";s:10:"form-right";s:0:"";s:17:"form-border-width";s:0:"";s:15:"form-transition";s:0:"";s:10:"form-hover";s:7:"       ";s:10:"form-focus";s:7:"       ";s:17:"form-border-style";s:4:"none";s:15:"form-text-align";s:7:"Default";s:12:"form-z-index";s:0:"";s:12:"form-padding";s:0:"";s:11:"form-margin";s:0:"";s:17:"form-border-color";s:0:"";s:18:"form-border-radius";s:0:"";s:16:"form-line-height";s:0:"";s:16:"input-background";s:7:"#efeeee";s:11:"input-color";s:0:"";s:18:"input-border-color";s:0:"";s:15:"input-font-size";s:2:"17";s:17:"input-line-height";s:0:"";s:16:"input-transition";s:0:"";s:18:"input-border-width";s:0:"";s:18:"input-border-style";s:4:"none";s:19:"input-border-radius";s:1:"0";s:16:"input-font-style";s:7:"Default";s:17:"input-font-weight";s:7:"Default";s:11:"input-width";s:0:"";s:16:"input-box-sizing";s:7:"Default";s:14:"input-position";s:7:"Default";s:16:"input-text-align";s:7:"Default";s:13:"input-z-index";s:0:"";s:9:"input-top";s:0:"";s:12:"input-bottom";s:0:"";s:10:"input-left";s:0:"";s:11:"input-right";s:0:"";s:12:"input-height";s:0:"";s:11:"input-hover";s:7:"       ";s:11:"input-focus";s:7:"       ";s:13:"input-padding";s:0:"";s:12:"input-margin";s:0:"";s:25:"textarea-background-color";s:7:"#efeeee";s:15:"textarea-height";s:0:"";s:14:"textarea-width";s:0:"";s:19:"textarea-transition";s:0:"";s:19:"textarea-box-sizing";s:7:"Default";s:17:"textarea-position";s:7:"Default";s:12:"textarea-top";s:0:"";s:15:"textarea-bottom";s:0:"";s:13:"textarea-left";s:0:"";s:14:"textarea-right";s:0:"";s:14:"textarea-hover";s:7:"       ";s:14:"textarea-focus";s:7:"       ";s:20:"textarea-border-size";s:0:"";s:21:"textarea-border-color";s:0:"";s:21:"textarea-border-style";s:7:"Default";s:22:"textarea-border-radius";s:0:"";s:18:"textarea-font-size";s:0:"";s:19:"textarea-text-align";s:7:"Default";s:16:"textarea-z-index";s:0:"";s:20:"textarea-line-height";s:0:"";s:19:"textarea-font-style";s:7:"Default";s:19:"dropdown-background";s:0:"";s:14:"dropdown-color";s:0:"";s:19:"dropdown-transition";s:0:"";s:21:"dropdown-border-color";s:0:"";s:18:"dropdown-font-size";s:0:"";s:20:"dropdown-line-height";s:0:"";s:21:"dropdown-border-width";s:0:"";s:21:"dropdown-border-style";s:7:"Default";s:22:"dropdown-border-radius";s:0:"";s:19:"dropdown-font-style";s:7:"Default";s:20:"dropdown-font-weight";s:7:"Default";s:14:"dropdown-width";s:0:"";s:19:"dropdown-box-sizing";s:7:"Default";s:17:"dropdown-position";s:7:"Default";s:12:"dropdown-top";s:0:"";s:15:"dropdown-bottom";s:0:"";s:13:"dropdown-left";s:0:"";s:14:"dropdown-right";s:0:"";s:19:"dropdown-text-align";s:7:"Default";s:16:"dropdown-z-index";s:0:"";s:15:"dropdown-height";s:0:"";s:16:"dropdown-padding";s:0:"";s:15:"dropdown-margin";s:0:"";s:16:"label-font-style";s:7:"Default";s:17:"label-font-weight";s:7:"Default";s:15:"label-font-size";s:0:"";s:17:"label-line-height";s:0:"";s:14:"label-position";s:7:"Default";s:11:"label-width";s:0:"";s:12:"label-height";s:0:"";s:9:"label-top";s:0:"";s:12:"label-bottom";s:0:"";s:10:"label-left";s:0:"";s:18:"label-border-width";s:0:"";s:18:"label-border-style";s:7:"Default";s:16:"label-text-align";s:7:"Default";s:13:"label-z-index";s:0:"";s:18:"label-border-color";s:0:"";s:19:"label-border-radius";s:0:"";s:11:"label-after";s:7:"       ";s:12:"label-before";s:7:"       ";s:11:"label-hover";s:7:"       ";s:11:"label-focus";s:7:"       ";s:11:"label-right";s:0:"";s:16:"label-transition";s:0:"";s:13:"label-padding";s:0:"";s:11:"label-color";s:7:"#6a7989";s:19:"submit-button-width";s:0:"";s:24:"submit-button-box-sizing";s:7:"Default";s:17:"submit-text-align";s:7:"Default";s:14:"submit-z-index";s:0:"";s:20:"submit-button-height";s:0:"";s:27:"submit-button-border-radius";s:0:"";s:23:"submit-button-font-size";s:0:"";s:25:"submit-button-line-height";s:0:"";s:12:"submit-hover";s:7:"       ";s:12:"submit-focus";s:7:"       ";s:15:"submit-position";s:7:"Default";s:10:"submit-top";s:0:"";s:13:"submit-bottom";s:0:"";s:11:"submit-left";s:0:"";s:12:"submit-right";s:0:"";s:17:"submit-transition";s:0:"";s:26:"submit-button-border-width";s:0:"";s:26:"submit-button-border-style";s:7:"Default";s:26:"submit-button-border-color";s:0:"";s:19:"submit-button-color";s:0:"";s:24:"submit-button-background";s:0:"";s:19:"fieldset-background";s:0:"";s:14:"fieldset-width";s:0:"";s:14:"fieldset-after";s:7:"       ";s:15:"fieldset-before";s:7:"       ";s:16:"fieldset-display";s:7:"Default";s:23:"fieldset-vertical-align";s:7:"Default";s:19:"fieldset-text-align";s:7:"Default";s:16:"fieldset-z-index";s:0:"";s:14:"fieldset-color";s:0:"";s:21:"fieldset-border-color";s:0:"";s:18:"fieldset-font-size";s:0:"";s:20:"fieldset-line-height";s:0:"";s:19:"fieldset-transition";s:0:"";s:21:"fieldset-border-width";s:0:"";s:21:"fieldset-border-style";s:7:"Default";s:22:"fieldset-border-radius";s:0:"";s:19:"fieldset-font-style";s:7:"Default";s:20:"fieldset-font-weight";s:7:"Default";s:19:"fieldset-box-sizing";s:7:"Default";s:17:"fieldset-position";s:7:"Default";s:12:"fieldset-top";s:0:"";s:15:"fieldset-bottom";s:0:"";s:13:"fieldset-left";s:0:"";s:14:"fieldset-right";s:0:"";s:15:"fieldset-height";s:0:"";s:16:"fieldset-padding";s:0:"";s:15:"fieldset-margin";s:0:"";}";',

			'custom_css'  =>  '.user-form {
				margin: 0;
			}
			.active_sae .user-label > label {
				font-size: 14px !important;
				transform: translate(0px, -50px);
				transition: all 0.5s ease 0.2s;
			}
			.user-label > label {
				margin-left: 10px;
				margin-top: 25px;
				cursor: text;
				transition: all 0.5s ease 0.5s;
			}'
			),
		array (

			'title'     => "Ruri",

			'category'  => "Frontend User Style",

			'default_css' => 's:5446:"a:164:{s:15:"form-background";s:7:"#2f3238";s:10:"form-width";s:3:"60%";s:15:"form-box-sizing";s:7:"Default";s:13:"form-position";s:8:"Relative";s:8:"form-top";s:0:"";s:11:"form-bottom";s:0:"";s:9:"form-left";s:0:"";s:10:"form-right";s:0:"";s:17:"form-border-width";s:0:"";s:15:"form-transition";s:0:"";s:10:"form-hover";s:0:"";s:10:"form-focus";s:0:"";s:17:"form-border-style";s:7:"Default";s:15:"form-text-align";s:7:"Default";s:12:"form-z-index";s:0:"";s:12:"form-padding";s:9:"50px 40px";s:11:"form-margin";s:0:"";s:17:"form-border-color";s:0:"";s:18:"form-border-radius";s:0:"";s:16:"form-line-height";s:0:"";s:16:"input-background";s:7:"#2f3238";s:11:"input-color";s:7:"#ffffff";s:18:"input-border-color";s:7:"#696969";s:15:"input-font-size";s:2:"25";s:17:"input-line-height";s:2:"29";s:16:"input-transition";s:0:"";s:18:"input-border-width";s:9:"0 0 7px 0";s:18:"input-border-style";s:5:"solid";s:19:"input-border-radius";s:0:"";s:16:"input-font-style";s:7:"Default";s:17:"input-font-weight";s:7:"Default";s:11:"input-width";s:0:"";s:16:"input-box-sizing";s:7:"Default";s:14:"input-position";s:7:"Default";s:16:"input-text-align";s:7:"Default";s:13:"input-z-index";s:0:"";s:9:"input-top";s:0:"";s:12:"input-bottom";s:0:"";s:10:"input-left";s:0:"";s:11:"input-right";s:0:"";s:12:"input-height";s:0:"";s:11:"input-hover";s:0:"";s:11:"input-focus";s:0:"";s:13:"input-padding";s:0:"";s:12:"input-margin";s:11:"10px 0 0 0 ";s:17:"fileupload-styles";s:0:"";s:25:"textarea-background-color";s:0:"";s:15:"textarea-height";s:0:"";s:14:"textarea-width";s:0:"";s:19:"textarea-transition";s:0:"";s:19:"textarea-box-sizing";s:7:"Default";s:17:"textarea-position";s:7:"Default";s:12:"textarea-top";s:0:"";s:15:"textarea-bottom";s:0:"";s:13:"textarea-left";s:0:"";s:14:"textarea-right";s:0:"";s:14:"textarea-hover";s:0:"";s:14:"textarea-focus";s:0:"";s:20:"textarea-border-size";s:0:"";s:21:"textarea-border-color";s:0:"";s:21:"textarea-border-style";s:7:"Default";s:22:"textarea-border-radius";s:0:"";s:18:"textarea-font-size";s:0:"";s:19:"textarea-text-align";s:7:"Default";s:16:"textarea-z-index";s:0:"";s:20:"textarea-line-height";s:0:"";s:19:"textarea-font-style";s:7:"Default";s:15:"dropdown-styles";s:7:"Default";s:19:"dropdown-background";s:0:"";s:14:"dropdown-color";s:0:"";s:19:"dropdown-transition";s:0:"";s:21:"dropdown-border-color";s:0:"";s:18:"dropdown-font-size";s:0:"";s:20:"dropdown-line-height";s:0:"";s:21:"dropdown-border-width";s:0:"";s:21:"dropdown-border-style";s:7:"Default";s:22:"dropdown-border-radius";s:0:"";s:19:"dropdown-font-style";s:7:"Default";s:20:"dropdown-font-weight";s:7:"Default";s:14:"dropdown-width";s:0:"";s:19:"dropdown-box-sizing";s:7:"Default";s:17:"dropdown-position";s:7:"Default";s:12:"dropdown-top";s:0:"";s:15:"dropdown-bottom";s:0:"";s:13:"dropdown-left";s:0:"";s:14:"dropdown-right";s:0:"";s:19:"dropdown-text-align";s:7:"Default";s:16:"dropdown-z-index";s:0:"";s:15:"dropdown-height";s:0:"";s:16:"dropdown-padding";s:0:"";s:15:"dropdown-margin";s:0:"";s:16:"label-font-style";s:6:"normal";s:17:"label-font-weight";s:6:"bolder";s:15:"label-font-size";s:2:"25";s:17:"label-line-height";s:0:"";s:14:"label-position";s:8:"Absolute";s:11:"label-width";s:0:"";s:12:"label-height";s:0:"";s:9:"label-top";s:0:"";s:12:"label-bottom";s:0:"";s:10:"label-left";s:0:"";s:18:"label-border-width";s:1:"0";s:18:"label-border-style";s:4:"none";s:16:"label-text-align";s:7:"Default";s:13:"label-z-index";s:0:"";s:18:"label-border-color";s:0:"";s:19:"label-border-radius";s:0:"";s:11:"label-after";s:0:"";s:12:"label-before";s:0:"";s:11:"label-hover";s:0:"";s:11:"label-focus";s:0:"";s:11:"label-right";s:0:"";s:16:"label-transition";s:20:"all 0.5s ease 0.15s;";s:13:"label-padding";s:0:"";s:11:"label-color";s:0:"";s:13:"submit-styles";s:0:"";s:19:"submit-button-width";s:0:"";s:24:"submit-button-box-sizing";s:7:"Default";s:17:"submit-text-align";s:7:"Default";s:14:"submit-z-index";s:0:"";s:20:"submit-button-height";s:0:"";s:27:"submit-button-border-radius";s:0:"";s:23:"submit-button-font-size";s:0:"";s:25:"submit-button-line-height";s:0:"";s:12:"submit-hover";s:0:"";s:12:"submit-focus";s:0:"";s:15:"submit-position";s:7:"Default";s:10:"submit-top";s:0:"";s:13:"submit-bottom";s:0:"";s:11:"submit-left";s:0:"";s:12:"submit-right";s:0:"";s:17:"submit-transition";s:0:"";s:26:"submit-button-border-width";s:0:"";s:26:"submit-button-border-style";s:7:"Default";s:26:"submit-button-border-color";s:0:"";s:19:"submit-button-color";s:0:"";s:24:"submit-button-background";s:0:"";s:19:"fieldset-background";s:0:"";s:14:"fieldset-width";s:0:"";s:14:"fieldset-after";s:0:"";s:15:"fieldset-before";s:0:"";s:16:"fieldset-display";s:7:"Default";s:23:"fieldset-vertical-align";s:7:"Default";s:19:"fieldset-text-align";s:7:"Default";s:16:"fieldset-z-index";s:0:"";s:14:"fieldset-color";s:0:"";s:21:"fieldset-border-color";s:0:"";s:18:"fieldset-font-size";s:0:"";s:20:"fieldset-line-height";s:0:"";s:19:"fieldset-transition";s:0:"";s:21:"fieldset-border-width";s:0:"";s:21:"fieldset-border-style";s:7:"Default";s:22:"fieldset-border-radius";s:0:"";s:19:"fieldset-font-style";s:7:"Default";s:20:"fieldset-font-weight";s:7:"Default";s:19:"fieldset-box-sizing";s:7:"Default";s:17:"fieldset-position";s:7:"Default";s:12:"fieldset-top";s:0:"";s:15:"fieldset-bottom";s:0:"";s:13:"fieldset-left";s:0:"";s:14:"fieldset-right";s:0:"";s:15:"fieldset-height";s:0:"";s:16:"fieldset-padding";s:0:"";s:15:"fieldset-margin";s:10:"0 0 30px 0";}";',

			'custom_css'  => '.user-form {
				margin: 0;
			}
			.active_ruri .user-label > label {
				font-size: 16px !important;color:#a3d39c;
				transform: translate(0px, 50px);
				transition: all 0.5s ease 0.15s;
			}
			fieldset .user-label label {
				margin-top: 35px; cursor: text;
			}
			.user-label {
				position: relative;
			}
			.active_ruri .user-fields input {
				border-bottom :2px solid #a3d39c !important;
				transition:all 0.57s ease 0.17s;
			}'
			),
		array (

			'title'     => "Minor",

			'category'  => "Frontend User Style",

			'custom_css' => '.user-fields:focus + .user-fields input::after{
				-webkit-animation: anim-shadow 0.3s forwards;
				animation: anim-shadow 0.3s forwards;
			}

			@-webkit-keyframes anim-shadow {
				to {
					box-shadow: 0px 0px 100px 50px;
					opacity: 0;
				}
			}

			@keyframes anim-shadow {
				to {
					box-shadow: 0px 0px 100px 50px;
					opacity: 0;
				}
			}',

			'default_css'  => 's:5998:"a:160:{s:15:"form-background";s:7:"#f9f7f6";s:10:"form-width";s:3:"350";s:15:"form-box-sizing";s:7:"Default";s:13:"form-position";s:7:"Default";s:8:"form-top";s:0:"";s:11:"form-bottom";s:0:"";s:9:"form-left";s:0:"";s:10:"form-right";s:0:"";s:17:"form-border-width";s:0:"";s:15:"form-transition";s:0:"";s:10:"form-hover";s:26:"                          ";s:10:"form-focus";s:31:"  box-shadow:0 0 0 2px #eca29b;";s:17:"form-border-style";s:7:"Default";s:15:"form-text-align";s:7:"Default";s:12:"form-z-index";s:0:"";s:12:"form-padding";s:4:"20px";s:11:"form-margin";s:0:"";s:17:"form-border-color";s:0:"";s:18:"form-border-radius";s:0:"";s:16:"form-line-height";s:0:"";s:16:"input-background";s:7:"#ffffff";s:11:"input-color";s:7:"#eca29b";s:18:"input-border-color";s:0:"";s:15:"input-font-size";s:2:"25";s:17:"input-line-height";s:0:"";s:16:"input-transition";s:23:"box-shadow 0.3s ease 0s";s:18:"input-border-width";s:1:"0";s:18:"input-border-style";s:4:"none";s:19:"input-border-radius";s:0:"";s:16:"input-font-style";s:7:"Default";s:17:"input-font-weight";s:4:"bold";s:11:"input-width";s:0:"";s:16:"input-box-sizing";s:7:"Default";s:14:"input-position";s:7:"Default";s:16:"input-text-align";s:7:"Default";s:13:"input-z-index";s:0:"";s:9:"input-top";s:0:"";s:12:"input-bottom";s:0:"";s:10:"input-left";s:0:"";s:11:"input-right";s:0:"";s:12:"input-height";s:0:"";s:11:"input-hover";s:26:"                          ";s:11:"input-focus";s:34:"     box-shadow:0 0 0 2px #eca29b;";s:13:"input-padding";s:4:"25px";s:12:"input-margin";s:0:"";s:25:"textarea-background-color";s:0:"";s:15:"textarea-height";s:0:"";s:14:"textarea-width";s:0:"";s:19:"textarea-transition";s:0:"";s:19:"textarea-box-sizing";s:7:"Default";s:17:"textarea-position";s:7:"Default";s:12:"textarea-top";s:0:"";s:15:"textarea-bottom";s:0:"";s:13:"textarea-left";s:0:"";s:14:"textarea-right";s:0:"";s:14:"textarea-hover";s:32:"                          dfgdfg";s:14:"textarea-focus";s:93:"                           box-shadow:0 0 0 2px #eca29b;rntransition:box-shadow 0.3s ease 0s;";s:20:"textarea-border-size";s:0:"";s:21:"textarea-border-color";s:0:"";s:21:"textarea-border-style";s:7:"Default";s:22:"textarea-border-radius";s:0:"";s:18:"textarea-font-size";s:0:"";s:19:"textarea-text-align";s:7:"Default";s:16:"textarea-z-index";s:0:"";s:20:"textarea-line-height";s:0:"";s:19:"textarea-font-style";s:7:"Default";s:19:"dropdown-background";s:0:"";s:14:"dropdown-color";s:0:"";s:19:"dropdown-transition";s:0:"";s:21:"dropdown-border-color";s:0:"";s:18:"dropdown-font-size";s:0:"";s:20:"dropdown-line-height";s:0:"";s:21:"dropdown-border-width";s:0:"";s:21:"dropdown-border-style";s:7:"Default";s:22:"dropdown-border-radius";s:0:"";s:19:"dropdown-font-style";s:7:"Default";s:20:"dropdown-font-weight";s:7:"Default";s:14:"dropdown-width";s:0:"";s:19:"dropdown-box-sizing";s:7:"Default";s:17:"dropdown-position";s:7:"Default";s:12:"dropdown-top";s:0:"";s:15:"dropdown-bottom";s:0:"";s:13:"dropdown-left";s:0:"";s:14:"dropdown-right";s:0:"";s:19:"dropdown-text-align";s:7:"Default";s:16:"dropdown-z-index";s:0:"";s:15:"dropdown-height";s:0:"";s:16:"dropdown-padding";s:0:"";s:15:"dropdown-margin";s:0:"";s:16:"label-font-style";s:7:"Default";s:17:"label-font-weight";s:4:"bold";s:15:"label-font-size";s:2:"20";s:17:"label-line-height";s:0:"";s:14:"label-position";s:8:"Absolute";s:11:"label-width";s:0:"";s:12:"label-height";s:0:"";s:9:"label-top";s:0:"";s:12:"label-bottom";s:5:"-40px";s:10:"label-left";s:0:"";s:18:"label-border-width";s:0:"";s:18:"label-border-style";s:7:"Default";s:16:"label-text-align";s:7:"Default";s:13:"label-z-index";s:0:"";s:18:"label-border-color";s:0:"";s:19:"label-border-radius";s:0:"";s:11:"label-after";s:77:"                animation:0.3s ease 0s normal forwards 1 running anim-shadow;";s:12:"label-before";s:35:"                           thttyuyu";s:11:"label-hover";s:33:"                           uyiyui";s:11:"label-focus";s:33:"                           n,,m,m";s:11:"label-right";s:0:"";s:16:"label-transition";s:0:"";s:11:"label-color";s:7:"#6a7989";s:19:"submit-button-width";s:3:"200";s:24:"submit-button-box-sizing";s:7:"initial";s:17:"submit-text-align";s:7:"Default";s:14:"submit-z-index";s:0:"";s:20:"submit-button-height";s:2:"20";s:27:"submit-button-border-radius";s:2:"10";s:23:"submit-button-font-size";s:2:"20";s:25:"submit-button-line-height";s:1:"2";s:12:"submit-hover";s:26:"                          ";s:12:"submit-focus";s:26:"                          ";s:15:"submit-position";s:7:"Default";s:10:"submit-top";s:0:"";s:13:"submit-bottom";s:0:"";s:11:"submit-left";s:0:"";s:12:"submit-right";s:0:"";s:17:"submit-transition";s:0:"";s:26:"submit-button-border-width";s:0:"";s:26:"submit-button-border-style";s:5:"solid";s:26:"submit-button-border-color";s:7:"#2980b2";s:19:"submit-button-color";s:7:"#1c1c1c";s:24:"submit-button-background";s:7:"#eca29b";s:19:"fieldset-background";s:0:"";s:14:"fieldset-width";s:0:"";s:14:"fieldset-after";s:153:"    box-shadow: 0 0 0 0; color: rgba(199, 152, 157, 0.6);content: \"\"; height: 100%;left: 0;position: absolute; top: 0; width: 100%; z-index: -1;       ";s:15:"fieldset-before";s:23:"                       ";s:16:"fieldset-display";s:7:"Default";s:23:"fieldset-vertical-align";s:7:"Default";s:19:"fieldset-text-align";s:7:"Default";s:16:"fieldset-z-index";s:0:"";s:14:"fieldset-color";s:0:"";s:21:"fieldset-border-color";s:0:"";s:18:"fieldset-font-size";s:0:"";s:20:"fieldset-line-height";s:0:"";s:19:"fieldset-transition";s:0:"";s:21:"fieldset-border-width";s:3:"0px";s:21:"fieldset-border-style";s:4:"none";s:22:"fieldset-border-radius";s:0:"";s:19:"fieldset-font-style";s:7:"Default";s:20:"fieldset-font-weight";s:7:"Default";s:19:"fieldset-box-sizing";s:7:"Default";s:17:"fieldset-position";s:8:"Relative";s:12:"fieldset-top";s:0:"";s:15:"fieldset-bottom";s:0:"";s:13:"fieldset-left";s:0:"";s:14:"fieldset-right";s:0:"";s:15:"fieldset-height";s:0:"";s:16:"fieldset-padding";s:3:"0px";s:15:"fieldset-margin";s:10:"0 0 55px 0";}";'


			)

		);

}
// end of get_predefined_feua_style_template_data




function feuastyle_load_elements(){



    $labels = array(

        'name'                      => _x( 'Frontend User Form Styles', 'Post Type General Name', 'feua_style' ),

        'singular_name'         => _x( 'Frontend User Form Style', 'Post Type Singular Name', 'feua_style' ),

        'menu_name'             => __( 'Frontend User Form Style', 'feua_style' ),

        'parent_item_colon'     => __( 'Parent Style:', 'feua_style' ),

        'all_items'             => __( 'All Styles', 'feua_style' ),

        'view_item'             => __( 'View Style', 'feua_style' ),

        'add_new_item'          => __( 'Add New', 'feua_style' ),

        'add_new'               => __( 'Add New', 'feua_style' ),

        'edit_item'             => __( 'Edit Style', 'feua_style' ),

        'update_item'           => __( 'Update Style', 'feua_style' ),

        'search_items'          => __( 'Search Style', 'feua_style' ),

        'not_found'             => __( 'Not found', 'feua_style' ),

        'not_found_in_trash'    => __( 'Not found in Trash', 'feua_style' )

        );

    $args = array(

        'label'                     => __( 'feua_style', 'feua_style' ),

        'description'           => __( 'Add/remove Frontend User Form style', 'feua_style' ),

        'labels'                    => $labels,

        'supports'              => array( 'title' ),

        'hierarchical'          => false,

        'taxonomies'        => array('style_category'), 

        'public'                    => true,

        'show_ui'               => true,

        'show_in_menu'          => 'wpuf-admin-opt',

        'show_in_nav_menus'     => false,

        'show_in_admin_bar'     => false,

        'menu_icon'     => "dashicons-twitter",

        'menu_position'         => 29.555555,

        'can_export'            => true,

        'has_archive'           => false,

        'exclude_from_search'   => true,                                

        'publicly_queryable'    => false,

        'capability_type'       => 'page'

        );

    /*register custom post type FEUPA_STYLE*/

    register_post_type( 'feua_style', $args );



    $labels = array(

        'name'                              => _x( 'Categories', 'Taxonomy General Name', 'feua_style' ),

        'singular_name'                     => _x( 'Categories', 'Taxonomy Singular Name', 'feua_style' ),

        'menu_name'                         => __( 'Categories', 'feua_style' ),

        'all_items'                         => __( 'All Categories', 'feua_style' ),

        'parent_item'                       => __( 'Parent Categories', 'feua_style' ),

        'parent_item_colon'         => __( 'Parent Categories:', 'feua_style' ),

        'new_item_name'             => __( 'New Categories Name', 'feua_style' ),

        'add_new_item'                  => __( 'Add New Categories', 'feua_style' ),

        'edit_item'                         => __( 'Edit Categories', 'feua_style' ),

        'update_item'                       => __( 'Update Categories', 'feua_style' ),

        'separate_items_with_commas' => __( 'Separate Categories with commas', 'feua_style' ),

        'search_items'                      => __( 'Search Categories', 'feua_style' ),

        'add_or_remove_items'           => __( 'Add or remove Categories', 'feua_style' ),

        'choose_from_most_used'         => __( 'Choose from the most used Categories', 'feua_style' ),

        'not_found'                         => __( 'Not Found', 'feua_style' ),

        );

	$args = array(

	    'labels'                        => $labels,

	    'hierarchical'                  => true,

	    'public'                        => true,

	    'show_ui'                       => false,

	    'show_admin_column'     => true,

	    'show_in_nav_menus'     => false,

	    'show_tagcloud'              => true,

	    );

    //register tax

	register_taxonomy( 'style_category', array( 'feua_style' ), $args );



	if( get_option( 'feua_style_add_categories', 0 ) == 0 )
	{

	    $feua_style_args = array(

	        'post_type' => 'feua_style',
	        'posts_per_page' => -1

	        );

	    $feua_style_query = new WP_Query( $feua_style_args );

	    if ( $feua_style_query->have_posts() ) 
	    {

	        while ( $feua_style_query->have_posts() ) 
	        {

	            $feua_style_query->the_post();

	            $temp_title = get_the_title();

	            $temp_ID = get_the_ID();



	            foreach ( get_predefined_feua_style_template_data() as $style ) 
	            {

	                if( $temp_title == wptexturize( $style[ 'title' ] ) ) 
	                {

	                    wp_set_object_terms( $temp_ID, $style[ 'category' ], 'style_category' );

	                }

	            }

	        }

	        update_option( 'feua_style_add_categories', 1 );

	    }

	}
}

/** approve_user_feu.php
 *
 * @version 3.0.1
 */
class Approve_user_wp_feu_v301 {


	/////////////////////////////////////////////////////////////////////////////
	// PROPERTIES, PROTECTED
	/////////////////////////////////////////////////////////////////////////////

	/**
	 * The plugins' text domain.
	 *
	 * @since  1.1 - 03.04.2011
	 * @access protected
	 *
	 * @var    string
	 */
	protected $textdomain;


	/**
	 * The name of the calling plugin.
	 *
	 * @since  1.0 - 23.03.2011
	 * @access protected
	 *
	 * @var    string
	 */
	protected $plugin_name;



	/**
	 * The path to the plugin file.
	 *
	 * /path/to/wp-content/plugins/{plugin-name}/{plugin-name}.php
	 *
	 * @since  2.0.0 - 30.05.2012
	 * @access protected
	 *
	 * @var    string
	 */
	protected $plugin_path;


	/**
	 * The path to the plugin directory.
	 *
	 * /path/to/wp-content/plugins/{plugin-name}/
	 *
	 * @since  1.2 - 21.04.2011
	 * @access protected
	 *
	 * @var    string
	 */
	protected $plugin_dir_path;


	///////////////////////////////////////////////////////////////////////////
	// METHODS, PUBLIC
	///////////////////////////////////////////////////////////////////////////

	/**
	 * Constructor
	 *
	 * @since  1.0 - 23.03.2011
	 * @access public
	 *
	 * @param  string $plugin_name
	 *
	 */
	public function __construct( $args = array() ) {

		// Set class properties
		$this->textdomain      = $args['textdomain'];
		$this->plugin_path     = $args['plugin_path'];
		$this->plugin_dir_path = plugin_dir_path( $args['plugin_path'] );
		$this->plugin_name     = plugin_basename( $args['plugin_path'] );
	}



	/**
	 *
	 * @since  1.0 - 23.03.2011
	 * @access public
	 *
	 * @param  array  $plugin_meta
	 * @param  string $plugin_file
	 *
	 * @return string
	 */
	public function plugin_row_meta( $plugin_meta, $plugin_file ) {
		if ( $this->plugin_name == $plugin_file ) {
		
		}
		return $plugin_meta;
	}



	///////////////////////////////////////////////////////////////////////////
	// METHODS, PROTECTED
	///////////////////////////////////////////////////////////////////////////

	/**
	 * Hooks methods to their WordPress Actions and Filters.
	 *
	 * @example:
	 * $this->hook( 'the_title' );
	 * $this->hook( 'init', 5 );
	 * $this->hook( 'omg', 'is_really_tedious', 3 );
	 *
	 * @author Mark Jaquith
	 * @see    http://sliwww.slideshare.net/markjaquith/creating-and-maintaining-wordpress-plugins
	 * @since  1.5 - 12.02.2012
	 * @access protected
	 *
	 * @param  string $hook Action or Filter Hook name.
	 *
	 * @return boolean true
	 */
	protected function hook( $hook ) {
		$priority = 10;
		$method   = $this->sanitize_method( $hook );
		$args     = func_get_args();
		unset( $args[0] ); // Filter name

		foreach ( (array) $args as $arg ) {
			if ( is_int( $arg ) )
				$priority = $arg;
			else
				$method   = $arg;
		}

		return add_action( $hook, array( $this, $method ), $priority , 999 );
	}



	///////////////////////////////////////////////////////////////////////////
	// METHODS, PRIVATE
	///////////////////////////////////////////////////////////////////////////

	/**
	 * Sanitizes method names.
	 *
	 * @author Mark Jaquith
	 * @see    http://sliwww.slideshare.net/markjaquith/creating-and-maintaining-wordpress-plugins
	 * @since  1.5 - 12.02.2012
	 * @access private
	 *
	 * @param  string $method Method name to be sanitized.
	 *
	 * @return string Sanitized method name
	 */
	private function sanitize_method( $method ) {
		return str_replace( array( '.', '-' ), '_', $method );
	}

} // End of class 