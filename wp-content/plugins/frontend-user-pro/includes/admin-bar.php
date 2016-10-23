<?php
/**
 * Functions for modifying the WordPress admin bar.
 *
 * @package Feup_Members
 * @subpackage Functions
 */

/* Hook the feup_members admin bar to 'wp_before_admin_bar_render'. */
add_action( 'wp_before_admin_bar_render', 'feup_members_admin_bar' );

/**
 * Adds new menu items to the WordPress admin bar.
 *
 * @since 0.2.0
 * @global object $wp_admin_bar
 */
function feup_members_admin_bar() {
	global $wp_admin_bar;

	/* Check if the current user can 'create_roles'. */
	if ( current_user_can( 'create_roles' ) ) {

		/* Add a 'Role' menu item as a sub-menu item of the new content menu. */
		$wp_admin_bar->add_menu(
			array(
				'id' => 'feup_members-new-role',
				'parent' => 'new-content',
				'title' => esc_attr__( 'Role', 'feup_members' ),
				'href' => admin_url( 'users.php?page=role-new' )
			)
		);
	}
}

?>