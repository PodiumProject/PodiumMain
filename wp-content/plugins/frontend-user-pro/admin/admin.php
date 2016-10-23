<?php
/**
 * Handles the admin setup and functions for the plugin.
 *
 * @package Feup_Members
 * @subpackage Admin
 */

/* Set up the administration functionality. */
add_action( 'admin_menu', 'feup_members_admin_setup' );

/**
 * Sets up any functionality needed in the admin.
 *
 * @since 0.2.0
 */
function feup_members_admin_setup() {
	global $feup_members;

	/* If the role manager feature is active, add its admin pages. */
	if ( feup_members_get_setting( 'role_manager' ) ) {

		/**
		 * The "Roles" page should be shown for anyone that has the 'list_roles', 'edit_roles', or 
		 * 'delete_roles' caps, so we're checking against all three.
		 */

		/* If the current user can 'edit_roles'. */
		if ( current_user_can( 'edit_roles' ) )
			$edit_roles_cap = 'edit_roles';

		/* If the current user can 'delete_roles'. */
		elseif ( current_user_can( 'delete_roles' ) )
			$edit_roles_cap = 'delete_roles';

		/* Else, set the cap to the default, 'list_roles'. */
		else
			$edit_roles_cap = 'list_roles';

		/* Create the Manage Roles page. */
		$feup_members->edit_roles_page = 
		//add_submenu_page( 'user-about', 'Email Verification', 'Email Verification', 'manage_shop_settings', 'user-email-verify', array( $this, 'user_email_verify' ) );
		//add_submenu_page( 'user-about', esc_attr__( 'Roles', 'feup_members' ), esc_attr__( 'Roles', 'feup_members' ),'manage_shop_settings', 'roles', 'feup_members_edit_roles_page' );
		add_submenu_page( 'users.php', esc_attr__( 'Roles', 'feup_members' ), esc_attr__( 'Roles', 'feup_members' ), $edit_roles_cap, 'roles', 'feup_members_edit_roles_page' );

		/* Create the New Role page. */
		$feup_members->new_roles_page = 
		// add_submenu_page( 'user-about', esc_attr__( 'Roles', 'feup_members' ), esc_attr__( 'Roles', 'feup_members' ),'manage_shop_settings', $edit_roles_cap, 'feup_members_edit_roles_page' );
		//add_submenu_page( 'user-about', esc_attr__( 'Add New Role', 'feup_members' ), esc_attr__( 'Add New Role', 'feup_members' ),'manage_shop_settings', 'create_roles', 'feup_members_new_role_page' );
		add_submenu_page( 'users.php', esc_attr__( 'Add New Role', 'feup_members' ), esc_attr__( 'Add New Role', 'feup_members' ), 'create_roles', 'role-new', 'feup_members_new_role_page' );
	}

	/* Load post meta boxes on the post editing screen. */
	add_action( 'load-post.php', 'feup_members_admin_load_post_meta_boxes' );
	add_action( 'load-post-new.php', 'feup_members_admin_load_post_meta_boxes' );

	/* Load stylesheets and scripts for our custom admin pages. */
	add_action( 'admin_enqueue_scripts', 'feup_members_admin_enqueue_style' );
	add_action( 'admin_enqueue_scripts', 'feup_members_admin_enqueue_scripts' );
}

/**
 * Loads the admin stylesheet for the required pages based off the $hook_suffix parameter.
 *
 * @since 0.2.0
 * @param string $hook_suffix The hook for the current page in the admin.
 */
function feup_members_admin_enqueue_style( $hook_suffix ) {

	$pages = array(
		'users_page_roles',
		'users_page_role-new',
		'settings_page_feup_members-settings'
	);

	if ( in_array( $hook_suffix, $pages ) )
		wp_enqueue_style( 'feup_members-admin', trailingslashit( FEUP_MEMBERS_URI ) . 'assets/css/admin.css', false, '20110525', 'screen' );
}

/**
 * Loads the admin JavaScript for the required pages based off the $hook_suffix parameter.
 *
 * @since 0.2.0
 * @param string $hook_suffix The hook for the current page in the admin.
 */
function feup_members_admin_enqueue_scripts( $hook_suffix ) {

	$pages = array(
		'users_page_roles',
		'users_page_role-new'
	);

	if ( in_array( $hook_suffix, $pages ) )
		wp_enqueue_script( 'feup_members-admin', trailingslashit( FEUP_MEMBERS_URI ) . 'assets/js/admin.js', array( 'jquery' ), '20110525', true );
}

/**
 * Loads meta boxes for the post editing screen.
 *
 * @since 0.2.0
 */
function feup_members_admin_load_post_meta_boxes() {

	/* If the content permissions component is active, load its post meta box. */
	if ( feup_members_get_setting( 'content_permissions' ) )
		require_once( FEUP_MEMBERS_ADMIN . 'meta-box-post-content-permissions.php' );
}

/**
 * Loads the role manager main page (Roles).
 *
 * @since 0.1.0
 */
function feup_members_edit_roles_page() {
	require_once( FEUP_MEMBERS_ADMIN . 'roles.php' );
}

/**
 * Loads the New Role page.
 *
 * @since 0.1.0
 */
function feup_members_new_role_page() {
	require_once( FEUP_MEMBERS_ADMIN . 'role-new.php' );
}

/**
 * Message to show when a single role has been deleted.
 *
 * @since 0.1.0
 */
function feup_members_message_role_deleted() {
	feup_members_admin_message( '', __( 'Role deleted.', 'feup_members' ) );
}

/**
 * Message to show when multiple roles have been deleted (bulk delete).
 *
 * @since 0.1.0
 */
function feup_members_message_roles_deleted() {
	feup_members_admin_message( '', __( 'Selected roles deleted.', 'feup_members' ) );
}

/**
 * A function for displaying messages in the admin.  It will wrap the message in the appropriate <div> with the 
 * custom class entered.  The updated class will be added if no $class is given.
 *
 * @since 0.1.0
 * @param $class string Class the <div> should have.
 * @param $message string The text that should be displayed.
 */
function feup_members_admin_message( $class = 'updated', $message = '' ) {

	echo '<div class="' . ( !empty( $class ) ? esc_attr( $class ) : 'updated' ) . '"><p><strong>' . $message . '</strong></p></div>';
}

/**
 * Feup_Members plugin nonce function.  This is to help with securely making sure forms have been processed 
 * from the correct place.
 *
 * @since 0.1.0
 * @param $action string Additional action to add to the nonce.
 */
function feup_members_get_nonce( $action = '' ) {
	if ( $action )
		return "feup_members-component-action_{$action}";
	else
		return "feup_members-plugin";
}

/**
 * Function for safely deleting a role and transferring the deleted role's users to the default role.  Note that 
 * this function can be extremely intensive.  Whenever a role is deleted, it's best for the site admin to assign 
 * the user's of the role to a different role beforehand.
 *
 * @since 0.2.0
 * @param string $role The name of the role to delete.
 */
function feup_members_delete_role( $role ) {

	/* Get the default role. */
	$default_role = get_option( 'default_role' );

	/* Don't delete the default role. Site admins should change the default before attempting to delete the role. */
	if ( $role == $default_role )
		return;

	/* Get all users with the role to be deleted. */
	$users = get_users( array( 'role' => $role ) );

	/* Check if there are any users with the role we're deleting. */
	if ( is_array( $users ) ) {

		/* If users are found, loop through them. */
		foreach ( $users as $user ) {

			/* Create a new user object. */
			$new_user = new WP_User( $user->ID );

			/* If the user has the role, remove it and set the default. Do we need this check? */
			if ( $new_user->has_cap( $role ) ) {
				$new_user->remove_role( $role );
				$new_user->set_role( $default_role );
			}
		}
	}

	/* Remove the role. */
	remove_role( $role );
}

/**
 * Returns an array of all the user meta keys in the $wpdb->usermeta table.
 *
 * @since 0.2.0
 * @return array $keys The user meta keys.
 */
function feup_members_get_user_meta_keys() {
	global $wpdb;

	$keys = $wpdb->get_col( "SELECT meta_key FROM $wpdb->usermeta GROUP BY meta_key ORDER BY meta_key" );

	return $keys;
}

?>