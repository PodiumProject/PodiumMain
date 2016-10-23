<?php
/**
 * Handles permissions for post content, post excerpts, and post comments.  This is based on whether a user 
 * has permission to view a post according to the settings provided by the plugin.
 *
 * @package Feup_Members
 * @subpackage Functions
 */

/* Enable the content permissions features. */
add_action( 'after_setup_theme', 'feup_members_enable_content_permissions', 1 );

/**
 * Adds required filters for the content permissions feature if it is active.
 *
 * @since 0.2.0
 */
function feup_members_enable_content_permissions() {

	/* Only add filters if the content permissions feature is enabled and we're not in the admin. */
	if ( feup_members_get_setting( 'content_permissions' ) && !is_admin() ) {

		/* Filter the content and exerpts. */
		add_filter( 'the_content', 'feup_members_content_permissions_protect' );
		add_filter( 'get_the_excerpt', 'feup_members_content_permissions_protect' );
		add_filter( 'the_excerpt', 'feup_members_content_permissions_protect' );
		add_filter( 'the_content_feed', 'feup_members_content_permissions_protect' );
		add_filter( 'comment_text_rss', 'feup_members_content_permissions_protect' );

		/* Filter the comments template to make sure comments aren't shown to users without access. */
		add_filter( 'comments_template', 'feup_members_content_permissions_comments' );

		/* Use WP formatting filters on the post error message. */
		add_filter( 'feup_members_post_error_message', 'wptexturize' );
		add_filter( 'feup_members_post_error_message', 'convert_smilies' );
		add_filter( 'feup_members_post_error_message', 'convert_chars' );
		add_filter( 'feup_members_post_error_message', 'wpfeutop' );
		add_filter( 'feup_members_post_error_message', 'shortcode_unautop' );
		add_filter( 'feup_members_post_error_message', 'do_shortcode' );
	}
}

/**
 * Denies/Allows access to view post content depending on whether a user has permission to view the content.
 *
 * @since 0.1.0
 * @param string $content The content of a post.
 * @param string $content The content of a post or an error message.
 */
function feup_members_content_permissions_protect( $content ) {

	/* If the current user can view the post, return the post content. */
	if ( feup_members_can_current_user_view_post( get_the_ID() ) )
		return $content;

	/* Return an error message at this point. */
	return feup_members_get_post_error_message( get_the_ID() );
}

/**
 * Disables the comments template if a user doesn't have permission to view the post the comments are 
 * associated with.
 *
 * @since 0.1.0
 * @param string $template The Comments template.
 * @return string $template
 */
function feup_members_content_permissions_comments( $template ) {

	/* Check if the current user has permission to view the comments' post. */
	if ( !feup_members_can_current_user_view_post( get_queried_object_id() ) ) {

		/* Look for a 'comments-no-access.php' template in the parent and child theme. */
		$has_template = locate_template( array( 'comments-no-access.php' ) );

		/* If the template was found, use it.  Otherwise, fall back to the Feup_Members comments.php template. */
		$template = ( !empty( $has_template ) ? $has_template : FEUP_MEMBERS_INCLUDES . 'comments.php' );

		/* Allow devs to overwrite the comments template. */
		$template = apply_filters( 'feup_members_comments_template', $template );
	}

	/* Return the comments template filename. */
	return $template;
}

/**
 * Gets the error message to display for users who do not have access to view the given post.  The function first 
 * checks to see if a custom error message has been written for the specific post.  If not, it loads the error 
 * message set on the plugins settings page.
 *
 * @since 0.2.0
 * @param int $post_id The ID of the post to get the error message for.
 * @return string $return The error message.
 */
function feup_members_get_post_error_message( $post_id ) {

	/* Get the error message for the specific post. */
	$error_message = get_post_meta( $post_id, '_feup_members_access_error', true );

	/* If an error message is found, return it. */
	if ( !empty( $error_message ) )
		$return = $error_message;

	/* If no error message is found, return the default message. */
	else
		$return = feup_members_get_setting( 'content_permissions_error' );

	/* Return the error message. */
	return apply_filters( 'feup_members_post_error_message', $return );
}

/**
 * Converts the meta values of the old '_role' post meta key to the newer '_feup_members_access_role' meta 
 * key.  The reason for this change is to avoid any potential conflicts with other plugins/themes.  We're 
 * now using a meta key that is extremely specific to the Feup_Members plugin.
 *
 * @since 0.2.0
 * @param int $post_id The ID of the post to convert the post meta for.
 * @return array|bool $old_roles|false Returns the array of old roles or false for everything else.
 */
function feup_members_convert_old_post_meta( $post_id ) {

	/* Check if there are any meta values for the '_role' meta key. */
	$old_roles = get_post_meta( $post_id, '_role', false );

	/* If roles were found, let's convert them. */
	if ( !empty( $old_roles ) ) {

		/* Delete the old '_role' post meta. */
		delete_post_meta( $post_id, '_role' );

		/* Check if there are any roles for the '_feup_members_access_role' meta key. */
		$new_roles = get_post_meta( $post_id, '_feup_members_access_role', false );

		/* If new roles were found, don't do any conversion. */
		if ( empty( $new_roles ) ) {

			/* Loop through the old meta values for '_role' and add them to the new '_feup_members_access_role' meta key. */
			foreach ( $old_roles as $role )
				add_post_meta( $post_id, '_feup_members_access_role', $role, false );

			/* Return the array of roles. */
			return $old_roles;
		}
	}

	/* Return false if we get to this point. */
	return false;
}

?>