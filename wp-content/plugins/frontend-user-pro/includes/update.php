<?php
/**
 * Version check and update functionality.
 *
 * @package Feup_Members
 * @subpackage Includes
 */

/* Don't run when installing. */
if ( defined( 'WP_INSTALLING' ) && WP_INSTALLING )
  return;

/* Hook our version check to 'init'. */
add_action( 'init', 'feup_members_version_check' );

/**
 * Checks the version number and runs install or update functions if needed.
 *
 * @since 0.2.0
 */
function feup_members_version_check() {

	/* Get the old database version. */
	$old_db_version = get_option( 'feup_members_db_version' );

	/* Get the theme settings. */
	$settings = get_option( 'feup_members_settings' );

	/* If there is no old database version, run the install. */
	if ( empty( $old_db_version ) && false === $settings )
		feup_members_install();

	/* Temporary check b/c version 0.1.0 didn't have an upgrade path. */
	elseif ( empty( $old_db_version ) && !empty( $settings ) )
		feup_members_update();

	/* If the old version is less than the new version, run the update. */
	elseif ( intval( $old_db_version ) < intval( FEUP_MEMBERS_DB_VERSION ) )
		feup_members_update();
}

/**
 * Adds the plugin settings on install.
 *
 * @since 0.2.0
 */
function feup_members_install() {

	/* Add the database version setting. */
	add_option( 'feup_members_db_version', FEUP_MEMBERS_DB_VERSION );

	/* Add the default plugin settings. */
	add_option( 'feup_members_settings', feup_members_get_default_settings() );
}

/**
 * Updates plugin settings if there are new settings to add.
 *
 * @since 0.2.0
 */
function feup_members_update() {

	/* Update the database version setting. */
	update_option( 'feup_members_db_version', FEUP_MEMBERS_DB_VERSION );

	/* Get the settings from the database. */
	$settings = get_option( 'feup_members_settings' );

	/* Get the default plugin settings. */
	$default_settings = feup_members_get_default_settings();

	/* Loop through each of the default plugin settings. */
	foreach ( $default_settings as $setting_key => $setting_value ) {

		/* If the setting didn't previously exist, add the default value to the $settings array. */
		if ( !isset( $settings[$setting_key] ) )
			$settings[$setting_key] = $setting_value;
	}

	/* Update the plugin settings. */
	update_option( 'feup_members_settings', $settings );
}

/**
 * Returns an array of the default plugin settings.  These are only used on initial setup.
 *
 * @since 0.2.0
 */
function feup_members_get_default_settings() {

	/* Set up the default plugin settings. */
	$settings = array(

		// Version 0.1.0
		'role_manager' => 1,
		'content_permissions' => 1,
		'private_blog' => 0,

		// Version 0.2.0
		'private_feed' => 0,
		'login_form_widget' => 0,
		'users_widget' => 0,
		'content_permissions_error' => '<p class="restricted">' . __( 'Sorry, but you do not have permission to view this content.', 'feup_members' ) . '</p>',
		'private_feed_error' => '<p class="restricted">' . __( 'You must be logged into the site to view this content.', 'feup_members' ) . '</p>',
	);

	/* Return the default settings. */
	return $settings;
}

?>