<?php
/**
 * The Stats page displays all of the site's roles and each role's stats.
 *
 * To view this page, a user must have a role with the capability of 'view_stats'.
 *
 * @package Feup_Members
 * @subpackage Components
 */


/* Get the current action performed by the user. */
//$view_role = isset( $_REQUEST['role'] ) ? $_REQUEST['role'] : false;
//$view_stats = isset( $_REQUEST['view'] ) ? $_REQUEST['view'] : false;

if ( isset( $_REQUEST['role'] ) && isset( $_REQUEST['view'] ) ) {

		/* Verify the referer. */
		check_admin_referer( feup_members_get_nonce( 'view-stats' ) );

		/* Set some default variables. */
		$role = $_REQUEST['role'];
		$view = $_REQUEST['view'];

		/* Load the edit role form. */
		require_once( 'view-stats.php' );
}

else {


/* Get the global $feup_members variable. */
global $feup_members, $wp_roles;

/* Current user in the admin. */
$user = new WP_User( $feup_members->current_user->ID );

/* Set the available roles array.*/
$avail_roles = array();

/* Get all the users of the current blog. */
$users_of_blog = get_users_of_blog();

/* Loop through each user. */
foreach ( (array) $users_of_blog as $blog_user ) {

	$meta_values = unserialize( $blog_user->meta_value );

	foreach ( ( array) $meta_values as $role => $value ) {

		if ( !isset( $avail_roles[$role] ) )
			$avail_roles[$role] = 0;

		++$avail_roles[$role];
	}
}

/* Destroy the $users_of_blog variable. */
unset( $users_of_blog );

/* Sort out the roles, active roles, and inactive roles. */
$all_roles = $active_roles = $inactive_roles = 0;

/* Loop through all of the roles, adding each role to its respective category (active, inactive). */
foreach ( $wp_roles->role_names as $role => $name ) {
	$all_roles++;
	if ( isset($avail_roles[$role]) ) {
		$active_roles++;
		$active_roles_arr[$role] = $name;
	}
	else {
		$inactive_roles++;
		$inactive_roles_arr[$role] = $name;
	}
}

$roles_loop_array = $wp_roles->role_names;

/* Sort the roles array into alphabetical order. */
ksort( $roles_loop_array ); ?>

<div class="wrap">

	<h2><?php _e( 'Statistics', 'feup_members' ); ?></h2>

	<?php do_action( 'feup_members_pre_stats_form' ); // Available action hook for displaying messages. ?>

	<div id="poststuff">

		<form id="roles" action="<?php echo admin_url( 'users.php?page=stats' ); ?>" method="post">

			<?php wp_nonce_field( feup_members_get_nonce( 'view-stats' ) ); ?>

			<table class="widefat fixed" cellspacing="0">
				<thead>
					<tr>
						<th class='name-column'><?php _e( 'Role', 'feup_members' ); ?></th>
						<th><?php _e( 'Users', 'feup_members' ); ?></th>
						<th><?php _e( 'Stats', 'feup_members' ); ?></th>
					</tr>
				</thead>

				<tfoot>
					<tr>
						<th class='name-column'><?php _e( 'Role', 'feup_members' ); ?></th>
						<th><?php _e( 'Users', 'feup_members' ); ?></th>
						<th><?php _e( 'Stats', 'feup_members' ); ?></th>
					</tr>
				</tfoot>

				<tbody id="users" class="list:user user-list plugins">

				<?php foreach ( $roles_loop_array as $role => $name ) { ?>

					<?php $name = str_replace( '|User role', '', $name ); ?>

					<tr valign="top" class="<?php if ( isset($avail_roles[$role]) ) echo 'active'; else echo 'inactive'; ?>">

						<td class='plugin-title'>
							<?php $view_link = admin_url( wp_nonce_url( "users.php?page=stats&amp;role={$role}&amp;view=month", feup_members_get_nonce( 'view-stats' ) ) ); ?> 

							<a href="<?php echo $view_link; ?>" title="<?php printf( __( 'View stats for the %1$s role', 'feup_members' ), $name ); ?>"><strong><?php echo $name; ?></strong></a>

							<div class="row-actions">
								<a href="<?php echo $view_link; ?>" title="<?php printf( __( 'View stats for the %1$s role', 'feup_members' ), $name ); ?>"><?php _e( 'Stats', 'feup_members' ); ?></a> 

								<?php
								/* If there are users, provide a link to the users page of that role. */
								if ( isset($avail_roles[$role]) ) { ?>
									| <a href="<?php echo admin_url( esc_url( "users.php?role={$role}" ) ); ?>" title="<?php printf( __( 'View all users with the %1$s role', 'feup_members' ), $name ); ?>"><?php _e( 'View Users', 'feup_members' ); ?></a> 
								<?php } ?>

							</div><!-- .row-actions -->

						</td><!-- .plugin-title -->

						<td class='desc'>
							<p><?php /* Check if any users are assigned to the role.  If so, display a link to the role's users page. */
							if ( isset($avail_roles[$role]) && 1 < $avail_roles[$role] )
								echo '<a href="' . admin_url( esc_url( "users.php?role={$role}" ) ) . '" title="' . sprintf( __( 'View all users with the %1$s role', 'feup_members' ), $name ) . '">' . sprintf( __( '%1$s Users', 'feup_members' ), $avail_roles[$role] ) . '</a>'; 
							elseif ( isset($avail_roles[$role]) && 1 == $avail_roles[$role] )
								echo '<a href="' . admin_url( esc_url( "users.php?role={$role}" ) ) . '" title="' . sprintf( __( 'View all users with the %1$s role', 'feup_members' ), $name ) . '">' . __( '1 User', 'feup_members' ) . '</a>'; 
							else
								echo '<em>' . __( 'No users have this role.', 'feup_members' ) . '</em>';
							?></p>
						</td><!-- .desc -->

						<td class='desc'>
							<p>
							<a href="#" title="<?php printf( __( 'View the yearly stats for the %1$s role', 'feup_members' ), $name ); ?>"><?php _e( 'Yearly', 'feup_members' ); ?></a> | 
							<a href="#" title="<?php printf( __( 'View the monthly stats for the %1$s role', 'feup_members' ), $name ); ?>"><?php _e( 'Monthly', 'feup_members' ); ?></a> | 
							<a href="#" title="<?php printf( __( 'View the daily stats for the %1$s role', 'feup_members' ), $name ); ?>"><?php _e( 'Daily', 'feup_members' ); ?></a>
							</p>
						</td><!-- .desc -->

					</tr><!-- .active .inactive -->

				<?php } // End foreach ?>

				</tbody><!-- #users .list:user .user-list .plugins -->

			</table><!-- .widefat .fixed -->

		</form><!-- #roles -->

	</div><!-- #poststuff -->

</div><!-- .wrap -->

<?php } ?>