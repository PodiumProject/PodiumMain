<?php
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class USER_Users {
	function __construct() {
		add_action( 'admin_init', array(
				$this,
				'user_prevent_admin_access'
			), 1000 );
		add_filter( 'show_admin_bar' , array(
				$this,
				'hide_admin_bar'
		), 99999 );
		add_filter( 'frontend_user_can_view_receipt', array( $this, 'user_can_view_receipt' ), 10, 2 );
		add_filter( 'frontend_user_can_view_receipt_item', array( $this, 'user_can_view_receipt_item' ), 10, 2 );
	}
	public function hide_admin_bar( $bool ){

		if ( is_admin() ){
			return $bool;
		}

		// This setting is reversed. ! removed means it is removed. Stupid.
		if( ! FRONTEND_USER()->helper->get_option( 'user-remove-admin-bar', false ) ) {
			if( FRONTEND_USER()->users->user_is_user( get_current_user_id(), true, true ) && ! FRONTEND_USER()->users->user_is_admin() ) {
				$bool = false;
			}
		}

		return $bool;
	}
	public function user_prevent_admin_access() {
		if ( FRONTEND_USER()->helper->get_option( 'user-allow-backend-access', false ) ) {
			return;
		}

		if (
			// Look for the presence of /wp-admin/ in the url
			stripos( $_SERVER[ 'REQUEST_URI' ], '/wp-admin/' ) !== false &&
			// Allow calls to async-upload.php
			stripos( $_SERVER[ 'REQUEST_URI' ], 'async-upload.php' ) == false &&
			// Allow calls to media-upload.php
			stripos( $_SERVER[ 'REQUEST_URI' ], 'media-upload.php' ) == false &&
			// Allow calls to admin-ajax.php
			stripos( $_SERVER[ 'REQUEST_URI' ], 'admin-ajax.php' ) == false ) {
				if ( FRONTEND_USER()->users->user_is_user() && !FRONTEND_USER()->users->user_is_admin() ) {
					wp_redirect( get_permalink( FRONTEND_USER()->helper->get_option( 'user-user-dashboard-page', false ) ) );
					exit();
				}
		}
	}

	/**
	 * Checks whether the ID provided is user capable or not
	 * This method is deprecated. Use the one in the user permissions class instead.
	 * Will be removed in 2.3.
	 *
	 * @param int     $user_id
	 * @return bool
	 */
	public static function is_user( $user_id ) {
		$bool      = user_can( 'frontend_user', $user_id ) ? true : false;
		return apply_filters( 'user_is_user', $bool, $user_id );
	}

	/**
	 * Checks whether the ID provided is admin capable or not
	 * This method is deprecated. Use the one in the user permissions class instead.
	 * Will be removed in 2.3.
	 *
	 * @param int     $user_id
	 * @return bool
	 */
	public static function is_admin( $user_id ) {
		$bool      = user_can( 'user_is_admin', $user_id ) ? true : false;
		return apply_filters( 'user_is_admin', $bool, $user_id );
	}

	/**
	 * Grabs the user ID whether a username or an int is provided
	 * and returns the user_id if it's actually a user
	 * This method is deprecated. Use the one in the user permissions class instead.
	 * Will be removed in 2.1.
	 *
	 * @param unknown $input
	 * @return unknown
	 */
	public static function get_user_id( $input ) {
		$int_user = (int) $input;
		$user     = !empty( $int_user ) ? get_userdata( $input ) : get_user_by( 'login', $input );
		if ( !$user )
			return false;
		$user_id = $user->ID;
		if ( self::is_user( $user_id ) ) {
			return $user_id;
		} else {
			return false;
		}
	}

	public static function is_pending( $user_id = -2 ) {
		if ( $user_id == -2 ){
			$user_id = get_current_user_id();
		}
		$user       = get_userdata( $user_id );
		$roles      = ! empty( $user->roles ) ? (array) $user->roles : array();
		$is_pending = in_array( 'pending_user', $roles );
		return $is_pending;
	}

	public function user_can_view_receipt( $user_can_view, $frontend_receipt_id ) {
		if ( ! $frontend_receipt_id ){
			return false;
		}

		$payment_id = ! empty( $_GET['order_id'] ) ? absint( $_GET['order_id'] ) : false;

		if( $payment_id ) {

			$cart = frontend_get_payment_meta_cart_details( $payment_id );

			foreach ( $cart as $item ) {
				$item = get_post( $item[ 'id' ] );

				if ( $item->post_author == get_current_user_id() ) {
					$user_can_view = true;

					break;
				}
			}

		}

		return $user_can_view;
	}

	public function user_can_view_receipt_item( $user_can_view, $item ) {

		if( is_user_logged_in() && ! ( current_user_can( 'manage_shop_reports' ) || $this->user_is_admin() ) && $this->is_user( get_current_user_id() ) ) {

			$download = get_post( $item[ 'id' ] );
			if ( (int) $download->post_author !== (int) get_current_user_id() ) {
				$user_can_view = false;
			}

		}

		return $user_can_view;
	}

	public function user_is_author( $post_id = false, $user_id = false ){
		if ( $post_id == false || $user_id == false ){
			return false;
		}
		else{
			$download = get_post( $post_id ) ;
			if ( (int) $download->post_author !== (int) $user_id ) {
				return false;
			}
			else{
				return true;
			}
		}
	}

	public function get_user_constant_name( $plural = false, $uppercase = true ){
		$constant = FRONTEND_USER()->helper->get_option( 'user-plugin-constants', array() );
		// Users
		if ( $plural && $uppercase ){
			$constant = ( isset( $constant[1] ) && $constant[1] != '' ) ? $constant[1] : __('Users', 'frontend_user_pro');
			$constant = apply_filters( 'user_user_constant_plural_uppercase', $constant );
			return $constant;
		}
		// users
		else if ( $plural){
			$constant = ( isset( $constant[3] ) && $constant[3] != '' ) ? $constant[3] : __('users', 'frontend_user_pro');
			$constant = apply_filters( 'user_user_constant_plural_lowercase', $constant );
			return $constant;
		}
		// User
		else if( !$plural && $uppercase ){
			$constant = ( isset( $constant[2] ) && $constant[2] != '' ) ? $constant[2] : __('User', 'frontend_user_pro');
			$constant = apply_filters( 'user_user_constant_singular_uppercase', $constant );
			return $constant;
		}
		// user
		else{
			$constant = ( isset( $constant[4] ) && $constant[4] != '' ) ? $constant[4] : __('user', 'frontend_user_pro');
			$constant = apply_filters( 'user_user_constant_singular_lowercase', $constant );
			return $constant;
		}
	}

	public function user_can_view_order( $post_id ) {
		if ( !FRONTEND_USER()->helper->get_option( 'user-allow-users-to-view-orders', false ) ){
			return false;
		}
		$user_id = get_current_user_id();
		if ( FRONTEND_USER()->users->user_is_user( $user_id ) || FRONTEND_USER()->users->user_is_admin( $user_id ) ){
			return frontend_USER()->users->user_can_view_receipt(false, $post_id );
		}
		else {
			return false;
		}
	}

	public function user_can_view_orders() {
		if ( !FRONTEND_USER()->helper->get_option( 'user-allow-users-to-view-orders', false ) ){
			return false;
		}
		$user_id = get_current_user_id();
		if ( FRONTEND_USER()->users->user_is_user( $user_id ) || FRONTEND_USER()->users->user_is_admin( $user_id ) ){
			return true;
		}
		else {
			return false;
		}
	}

	// Let's make some magic
	public function user_is_user( $user_id = -2, $pending = false, $suspended = false ) {
		if ( $user_id == -2 ) {
			$user_id = get_current_user_id();
		}
		if ( $user_id == 0 ) {
			// This is a logged out user, since get_current_user_id returns 0 for non logged in
			// since we can't do anything with them, lets get them out of here. They aren't users.
			return false;
		}
		$user = new WP_User( $user_id );
		// This allows devs to take what would normally be a user and say they aren't a user.
		$bool = false;
		$bool = apply_filters( 'user_skip_is_user', $bool, $user );
		// Note to developers: I passed in the entire user object above.
		// So expect either an object (logged in user) or false (not logged in user).
		if ( $bool ) {
			return false;
		}
		// Authentication Attempt #1: okay let's try caps
		// $user_caps = array ( 'user_is_user', 'user_is_admin');
		// $user_caps = apply_filters('user_user_caps', $user_caps);
		if ( user_can( $user_id, 'frontend_user' ) || user_can( $user_id, 'user_is_admin' ) ) {
			return true;
		}

		if ( $pending && user_can( $user_id, 'pending_user' ) ){
			return true;
		}

		if ( $suspended && user_can( $user_id, 'suspended_user' ) ){
			return true;
		}

		// Authentication Attempt #2:  maybe a developer has a reason for wanting to hook a user in?
		$bool = false;
		$bool = apply_filters( 'user_is_user_check_override', $bool, $user );
		// Note to developers: I passed in the entire user object above.
		// So expect either an object (logged in user) or false (not logged in user).
		if ( $bool ) {
			return true;
		}
		// end of the line
		return false;
	}

	public function user_is_admin( $user_id = -2 ) {
		if ( $user_id == -2 ) {
			$user_id = get_current_user_id();
		}
		if ( $user_id == 0 ) {
			// This is a logged out user, since get_current_user_id returns 0 for non logged in
			// since we can't do anything with them, lets get them out of here. They aren't users.
			return false;
		}
		$user = new WP_User( $user_id );
		// This allows devs to take what would normally be a user and say they aren't a user.
		$bool = false;
		$bool = apply_filters( 'user_skip_is_admin', $bool, $user );
		// Note to developers: I passed in the entire user object above.
		// So expect either an object (logged in user) or false (not logged in user).
		if ( $bool ) {
			return false;
		}
		// Authentication Attempt #1: okay let's try caps
		// $user_caps = array ( 'user_is_user', 'user_is_admin');
		// $user_caps = apply_filters('user_user_caps', $user_caps);
		if ( user_can( $user->ID, 'user_is_admin' ) ||  user_can( $user->ID, 'manage_shop_settings' ) ) {
			return true;
		}

		// Authentication Attempt #2:  maybe a developer has a reason for wanting to hook a user in?
		$bool = false;
		$bool = apply_filters( 'user_is_admin_check_override', $bool, $user );
		// Note to developers: I passed in the entire user object above.
		// So expect either an object (logged in user) or false (not logged in user).
		if ( $bool ) {
			return true;
		}
		// end of the line
		return false;
	}

	// User id if present/logged in
	// $ref is the url we want to bring the user back to if applicable
	public function user_not_a_user_redirect( $user_id = -2 ) {
		// lets try the grab user_id trick
		if ( $user_id == -2 ) {
			$user_id = get_current_user_id();
		}
		if ( $user_id == 0 ) {
			// This is a logged out user, since get_current_user_id returns 0 for non logged in
			// So let's log them in, and then attempt redirect to ref
			$base_url = get_permalink( FRONTEND_USER()->helper->get_option( 'user-user-dashboard-page', false ) );
			$base_url = add_query_arg( 'view', 'login-register', $base_url );
			wp_redirect( $base_url );
			exit;
		} else {
			$user = new WP_User( $user_id );
			if ( current_user_can( 'pending_user' ) ) {
				// are they a pending user: display not approved display
				$base_url = get_permalink( FRONTEND_USER()->helper->get_option( 'user-user-dashboard-page', false ) );
				$base_url = add_query_arg( 'user_id', $user_id, $base_url );
				$base_url = add_query_arg( 'view', 'pending', $base_url );
				wp_redirect( $base_url );
				exit;
			} else {
				// are they not a user yet: show registration page
				$base_url = get_permalink( FRONTEND_USER()->helper->get_option( 'user-user-dashboard-page', false ) );
				$base_url = add_query_arg( 'user_id', $user_id, $base_url );
				$base_url = add_query_arg( 'view', 'application', $base_url );
				wp_redirect( $base_url );
				exit;
			}
		}
	}

	// WARNING: FUNCTION NOT IN USE. It's for 2.3. Don't use it yet.
	public function user_not_enough_permissions( $user_id = -2, $ref = -2 ) {
		// lets try the grab user_id trick
		if ( $user_id == -2 ) {
			$user_id = get_current_user_id();
		}
		if ( $ref == -2 ) {
			$ref = wp_get_referer();
			if ( $ref == false ) {
				$ref = 'unknown page';
			}
		}
		// lets also log this
		//user_simple_log( $logname = 'User Access Denied Log', $text = "User $user_id, attempted to access $ref and was denied", $severity = 3 );
		$base_url = get_permalink( FRONTEND_USER()->helper->get_option( 'user-user-dashboard-page', false ) );
		add_query_arg( 'ref', $ref, $base_url );
		add_query_arg( 'user_id', $user_id, $base_url );
		add_query_arg( 'view', 'pending', $base_url );
		wp_redirect( $base_url );
		exit;
	}

	function array_msort( $array, $cols ) {
		$colarr = array();
		foreach ( $cols as $col => $order ) {
			$colarr[$col] = array();
			foreach ( $array as $k => $row ) { $colarr[$col]['_'.$k] = strtolower( $row[$col] ); }
		}
		$eval = 'array_multisort(';
		foreach ( $cols as $col => $order ) {
			$eval .= '$colarr[\''.$col.'\'],'.$order.',';
		}
		$eval = substr( $eval, 0, -1 ).');';
		eval( $eval );
		$ret = array();
		foreach ( $colarr as $col => $arr ) {
			foreach ( $arr as $k => $v ) {
				$k = substr( $k, 1 );
				if ( !isset( $ret[$k] ) ) $ret[$k] = $array[$k];
				$ret[$k][$col] = $array[$k][$col];
			}
		}
		return $ret;

	}

	public function get_all_orders_count( $user_id, $status ) {
		return count( $this->get_all_orders( $user_id, $status ) );
	}

	public function use_author_archives(){
		
		/*
		 * This option was deprecated in USER 2.2.10 per https://github.com/chriscct7/frontend-user/issues/504
		 */

		return false;
	}

	public function can_see_login(){
		if ( !is_user_logged_in() ){
			return true;
		}
		else{
			return false;
		}
	}

	public function can_see_registration(){
		if ( !FRONTEND_USER()->users->is_pending( ) && !FRONTEND_USER()->users->user_is_user() && ( FRONTEND_USER()->helper->get_option( 'user-allow-registrations', true ) || FRONTEND_USER()->helper->get_option( 'user-allow-applications', true ) )  ){
			return true;
		}
		else{
			return false;
		}

	}

	public function combo_form_count(){
		if ( FRONTEND_USER()->users->can_see_registration() && FRONTEND_USER()->users->can_see_login() ){
			return 2;
		}
		if ( FRONTEND_USER()->users->can_see_registration() || FRONTEND_USER()->users->can_see_login() ){
			return 1;
		}
		else{
			return 0;
		}
	}
}
