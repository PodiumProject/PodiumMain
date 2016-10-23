<?php
/**
 * Creates and adds the meta boxes to the Feup_Members Settings page in the admin.
 *
 * @package Feup_Members
 * @subpackage Admin
 */

/* Add the meta boxes for the settings page on the 'add_meta_boxes' hook. */
add_action( 'add_meta_boxes', 'feup_members_settings_page_create_meta_boxes' );

/**
 * Adds the meta boxes to the Feup_Members plugin settings page.
 *
 * @since 0.2.0
 */
function feup_members_settings_page_create_meta_boxes() {
	global $feup_members;

	/* Add the 'About' meta box. */
	add_meta_box( 'feup_members-about', _x( 'About', 'meta box', 'feup_members' ), 'feup_members_meta_box_display_about', $feup_members->settings_page, 'side', 'default' );

	/* Add the 'Donate' meta box. */
	add_meta_box( 'feup_members-donate', _x( 'Like this plugin?', 'meta box', 'feup_members' ), 'feup_members_meta_box_display_donate', $feup_members->settings_page, 'side', 'high' );

	/* Add the 'Support' meta box. */
	add_meta_box( 'feup_members-support', _x( 'Support', 'meta box', 'feup_members' ), 'feup_members_meta_box_display_support', $feup_members->settings_page, 'side', 'low' );

	/* Add the 'Role Manager' meta box. */
	add_meta_box( 'feup_members-role-manager', _x( 'Role Manager', 'meta box', 'feup_members' ), 'feup_members_meta_box_display_role_manager', $feup_members->settings_page, 'normal', 'high' );

	/* Add the 'Content Permissions' meta box. */
	add_meta_box( 'feup_members-content-permissions', _x( 'Content Permissions', 'meta box', 'feup_members' ), 'feup_members_meta_box_display_content_permissions', $feup_members->settings_page, 'normal', 'high' );

	/* Add the 'Sidebar Widgets' meta box. */
	add_meta_box( 'feup_members-widgets', _x( 'Sidebar Widgets', 'meta box', 'feup_members' ), 'feup_members_meta_box_display_widgets', $feup_members->settings_page, 'normal', 'high' );

	/* Add the 'Private Site' meta box. */
	add_meta_box( 'feup_members-private-site', _x( 'Private Site', 'meta box', 'feup_members' ), 'feup_members_meta_box_display_private_site', $feup_members->settings_page, 'normal', 'high' );
}

/**
 * Displays the about plugin meta box.
 *
 * @since 0.2.0
 */
function feup_members_meta_box_display_about( $object, $box ) {

	$plugin_data = get_plugin_data( FEUP_MEMBERS_DIR . 'feup_members.php' ); ?>

	<p>
		<strong><?php _e( 'Version:', 'feup_members' ); ?></strong> <?php echo $plugin_data['Version']; ?>
	</p>
	<p>
		<strong><?php _e( 'Description:', 'feup_members' ); ?></strong>
	</p>
	<p>
		<?php echo $plugin_data['Description']; ?>
	</p>
<?php }

/**
 * Displays the donation meta box.
 *
 * @since 0.2.0
 */
function feup_members_meta_box_display_donate( $object, $box ) { ?>

	<p><?php _e( "Here's how you can give back:", 'feup_members' ); ?></p>

	<ul>
		<li><a href="http://wordpress.org/extend/plugins/feup_members" title="<?php esc_attr_e( 'Feup_Members on the WordPress plugin repository', 'feup_members' ); ?>"><?php _e( 'Give the plugin a good rating.', 'feup_members' ); ?></a></li>
		<li><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=3687060" title="<?php esc_attr_e( 'Donate via PayPal', 'feup_members' ); ?>"><?php _e( 'Donate a few dollars.', 'feup_members' ); ?></a></li>
		<li><a href="http://amzn.com/w/31ZQROTXPR9IS" title="<?php esc_attr_e( "Justin Tadlock's Amazon Wish List", 'feup_members' ); ?>"><?php _e( 'Get me something from my wish list.', 'feup_members' ); ?></a></li>
	</ul>
<?php
}

/**
 * Displays the support meta box.
 *
 * @since 0.2.0
 */
function feup_members_meta_box_display_support( $object, $box ) { ?>
	<p>
		<?php printf( __( 'Support for this plugin is provided via the support forums at %1$s. If you need any help using it, please ask your support questions there.', 'feup_members' ), '<a href="http://themehybrid.com/support" title="' . esc_attr__( 'Theme Hybrid Support Forums', 'feup_members' ) . '">' . __( 'Theme Hybrid', 'feup_members' ) . '</a>' ); ?>
	</p>
<?php }

/**
 * Displays the role manager meta box.
 *
 * @since 0.2.0
 */
function feup_members_meta_box_display_role_manager( $object, $box ) { ?>

	<p>
		<input type="checkbox" name="feup_members_settings[role_manager]" id="feup_members_settings-role_manager" value="1" <?php checked( 1, feup_members_get_setting( 'role_manager' ) ); ?> /> 
		<label for="feup_members_settings-role_manager"><?php _e( 'Enable the role manager.', 'feup_members' ); ?></label>
	</p>
	<p>
		<span class="howto"><?php _e( 'Your roles and capabilities will not revert back to their previous settings after deactivating or uninstalling this plugin, so use this feature wisely.', 'feup_members' ); ?></span>
	</p>

<?php }

/**
 * Displays the content permissions meta box.
 *
 * @since 0.2.0
 */
function feup_members_meta_box_display_content_permissions( $object, $box ) { ?>

	<p>
		<input type="checkbox" name="feup_members_settings[content_permissions]" id="feup_members_settings-content_permissions" value="1" <?php checked( 1, feup_members_get_setting( 'content_permissions' ) ); ?> /> 
		<label for="feup_members_settings-content_permissions"><?php _e( 'Enable the content permissions feature.', 'feup_members' ); ?></label>
	</p>

	<p>
		<label for="feup_members_settings-content_permissions_error"><?Php _e( 'Default post error message:', 'feup_members' ); ?></label>
		<textarea name="feup_members_settings[content_permissions_error]" id="feup_members_settings-content_permissions_error"><?php echo esc_textarea( feup_members_get_setting( 'content_permissions_error' ) ); ?></textarea>
		<label for="feup_members_settings-content_permissions_error"><?php _e( 'You can use <abbr title="Hypertext Markup Language">HTML</abbr> and/or shortcodes to create a custom error message for users that don\'t have permission to view posts.', 'feup_members' ); ?></label>
	</p>

<?php }

/**
 * Displays the widgets meta box.
 *
 * @since 0.2.0
 */
function feup_members_meta_box_display_widgets( $object, $box ) { ?>

	<p>
		<input type="checkbox" name="feup_members_settings[login_form_widget]" id="feup_members_settings-login_form_widget" value="1" <?php checked( 1, feup_members_get_setting( 'login_form_widget' ) ); ?> /> 
		<label for="feup_members_settings-login_form_widget"><?php _e( 'Enable the login form widget.', 'feup_members' ); ?></label>
	</p>

	<p>
		<input type="checkbox" name="feup_members_settings[users_widget]" id="feup_members_settings-users_widget" value="1" <?php checked( 1, feup_members_get_setting( 'users_widget' ) ); ?> /> 
		<label for="feup_members_settings-users_widget"><?php _e( 'Enable the users widget.', 'feup_members' ); ?></label>
	</p>

<?php }

/**
 * Displays the private site meta box.
 *
 * @since 0.2.0
 */
function feup_members_meta_box_display_private_site( $object, $box ) { ?>

	<p>
		<input type="checkbox" name="feup_members_settings[private_blog]" id="feup_members_settings-private_blog" value="1" <?php checked( 1, feup_members_get_setting( 'private_blog' ) ); ?> /> 
		<label for="feup_members_settings-private_blog"><?php _e( 'Redirect all logged-out users to the login page before allowing them to view the site.', 'feup_members' ); ?></label>
	</p>

	<p>
		<input type="checkbox" name="feup_members_settings[private_feed]" id="feup_members_settings-private_feed" value="1" <?php checked( 1, feup_members_get_setting( 'private_feed' ) ); ?> /> 
		<label for="feup_members_settings-private_feed"><?php _e( 'Show error message for feed items.', 'feup_members' ); ?></label>
	</p>

	<p>
		<label for="feup_members_settings-private_feed_error"><?php _e( 'Feed error message:', 'feup_members' ); ?></label>
		<textarea name="feup_members_settings[private_feed_error]" id="feup_members_settings-private_feed_error"><?php echo esc_textarea( feup_members_get_setting( 'private_feed_error' ) ); ?></textarea>
		<br />
		<label for="feup_members_settings-private_feed_error"><?php _e( 'You can use <abbr title="Hypertext Markup Language">HTML</abbr> and/or shortcodes to create a custom error message to display instead of feed item content.', 'feup_members' ); ?></label>
	</p>

<?php }

?>