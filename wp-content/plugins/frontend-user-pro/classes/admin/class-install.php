<?php
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class USER_Install {

	public $toSet = array();
	public $user_settings = array();

	public function init() {

		$this->user_settings = get_option( 'user_settings' );

		$default = get_option( 'frontend_user_pro_options', false );
		if ( $default ){
			$default = isset( $default['db_version'] ) ? $default['db_version'] : '1.0';
		}
		$version = get_option( 'user_current_version', $default );

		if ( version_compare( $version, '1.0', '>=' )  ) {
			return;
		}

		$this->toSet = $this->user_settings;

		FRONTEND_USER()->setup->register_post_type();
		$this->install_or_update_user();
		update_option( 'user_settings', $this->toSet );

	}

	public function create_page( $slug, $page_title = '', $page_content = '', $post_parent = 0 ) {
		global $wpdb, $wp_version;
		$page_id = $this->toSet[ 'user-' . $slug . '-page'];
		if ( $page_id > 0 && get_post( $page_id ) ) {
			return;
		}
		$page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM " . $wpdb->posts . " WHERE post_name = %s LIMIT 1;", $slug ) );
		if ( $page_found ) {
			if ( !$page_id ) {
				$this->toSet[ 'user-' . $slug . '-page'] = $page_found;
				return;
			}
			return;
		}
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
		$page_id   = wp_insert_post( $page_data );
		$this->toSet[ 'user-' . $slug . '-page'] = $page_id;
		return;
	}

	public function create_post( $slug, $page_title = '', $post_type = 'user-forms' ) {
		global $wpdb, $wp_version, $user_settings;
		$page_id = $this->toSet[$slug];
		if ( $page_id > 0 && get_post( $page_id ) ) {
			return;
		}
		$page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM " . $wpdb->posts . " WHERE post_name = %s LIMIT 1;", $slug ) );
		if ( $page_found ) {
			if ( !$page_id ) {
				$this->toSet[$slug] = $page_found;
				return;
			}
			return;
		}

		$page_data = array(
			'post_status' => 'publish',
			'post_type' => $post_type,
			'post_author' => get_current_user_id(),
			'post_title' => $page_title
		);
		$page_id   = wp_insert_post( $page_data );
		$this->save_default_values( $slug, $page_id );
		$this->toSet[$slug] = $page_id;
		return;
	}

	private function save_default_values( $slug, $page_id ) {
		$admin_email = get_option( 'admin_email' );
		switch ( $slug ) {
		case 'user-login-form':
			$login = array(
				1 => array(
					'input_type' => 'text',
					'template' => 'user_login',
					'required' => 'yes',
					'label' => __( 'Username', 'frontend_user_pro' ),
					'name' => 'user_login',
					'is_meta' => 'yes',
					'size' => 40,
					'help' => '',
					'css' => '',
					'placeholder' => '',
					'default' => '',
				),
				2 => array(
					'input_type' => 'password',
					'template' => 'password',
					'required' => 'yes',
					'label' => __( 'Password', 'frontend_user_pro' ),
					'name' => 'password',
					'is_meta' => 'yes',
					'size' => '40',
					'min_length' => '6',
					'repeat_pass' => 'no',
					'help' => '',
					'css' => '',
					'placeholder' => ''
				),
			);
			update_post_meta( $page_id , 'user-form', $login );
			$setting = array(
					'redirect_to' => 'same',
					'display_form' => 'default1',
    				'page_id' => '2',
			       	'message' => 'Registration successful',
			    	'update_message' => 'Form updated successfully',
				    'lostpassword_message' => 'Form updated successfully',
				    'submit_text' => 'Submit',
				    'update_text' => 'Update',
				    'email_verify' => 'no',
				    'register_page' => '2'
				);
			update_post_meta( $page_id , 'user_form_settings' , $setting);
			break;
		case 'user-forms-form':
			$login = array(
			0 => array(
				'input_type' => 'text',
				'template' => 'post_title',
				'required' => 'yes',
				'label' => 'Post Title',
				'name' => 'post_title',
				'is_meta' => 'no',
				'help' => '',
				'css' => '',
				'error_msg' => '',
				'placeholder' => '',
				'default' => '',
				'size' => '40',
				'enable_login_label' => 'show',
				'enable_user_label' => 'all',
				'array_title' => array(
						'0' => 'post_title-0',
					),
					'login_title' => array(
						'0' => '-Select-',
					),
					'login_oprt' => array(
						'0' => 'is',
					),
					'login_enable' => array(
						'0' => '-Select-',
					)
				),
			1 => array(
				'input_type' => 'textarea',
				'template' => 'post_content',
				'required' => 'yes',
				'label' => 'Post Content',
				'name' => 'post_content',
				'is_meta' => 'no',
				'help' => '',
				'css' => '',
				'error_msg' => '',
				'rows' => '5',
				'cols' => '25',
				'placeholder' => '',
				'default' => '',
				'rich' => 'no',
				'insert_image' => 'yes',
				'enable_login_label' => 'show',
				'enable_user_label' => 'all',
				'array_title' => array(
						'0' => 'post_content-1',
					),
					'login_title' => array(
						'0' => '-Select-',
					),
					'login_oprt' => array(
						'0' => 'is',
					),
					'login_enable' => array(
						'0' => '-Select-',
					)
			),
			2 => array(
				'input_type' => 'image_upload',
				'template' => 'featured_image',
				'count' => '1',
				'required' => 'yes',
				'label' => 'Featured Image',
				'name' => 'featured_image',
				'is_meta' => 'no',
				'help' => '',
				'css' => '',
				'error_msg' => '',
				'enable_login_label' => 'show',
				'enable_user_label' => 'all',
				'array_title' => array(
						'0' => 'featured_image-2',
					),
					'login_title' => array(
						'0' => '-Select-',
					),
					'login_oprt' => array(
						'0' => 'is',
					),
					'login_enable' => array(
						'0' => '-Select-',
					)
				)
			);
			$setting = array(
				'post_type' => 'post',
				'post_status' => 'publish',
				'post_format' => '0',
				'default_cat' => '-1',
				'guest_post' => 'false',
				'message_restrict' =>  'This page is restricted. Please Log in / Register to view this page.',                 
				'redirect_to' => 'post',
				'message' => 'Post saved',
				'page_id' => '14',
				'url' => '',
				'comment_status' => 'open',
				'submit_text' => 'Submit',
				'draft_post' => 'false',
				'edit_post_status' => 'publish',
				'edit_redirect_to' => 'same',
				'update_message' => 'Post updated successfully',
				'edit_page_id' => '14',
				'edit_url' => '',
				'update_text' => 'Update',
				'notification' => array
				(
					'new' => 'on',
					'new_to' => $admin_email,
					'new_subject' => 'New post created',
					'new_body' => 'Hi Admin,
					A new post has been created in your site %sitename% (%siteurl%).

					Here is the details:
					Post Title: %post_title%
					Content: %post_content%
					Author: %author%
					Post URL: %permalink%
					Edit URL: %editlink%',
					'edit' => 'off',
					'edit_to' => $admin_email,
					'edit_subject' => 'A post has been edited',
					'edit_body' => 'Hi Admin,
					The post "%post_title%" has been updated.

					Here is the details:
					Post Title: %post_title%
					Content: %post_content%
					Author: %author%
					Post URL: %permalink%
					Edit URL: %editlink%'
					)
				);
			update_post_meta( $page_id , 'user_form_settings', $setting );
			update_post_meta( $page_id , 'user-form', $login );
			break;
		case 'user-registration-form':
			$register = array(
				1 =>
				array (
					'input_type' => 'text',
					'template' => 'first_name',
					'required' => 'yes',
					'label' => 'First Name',
					'name' => 'first_name',
					'is_meta' => 'yes',
					'help' => '',
					'css' => '',
					'placeholder' => '',
					'default' => '',
					'size' => '40',
				),
				2 =>
				array (
					'input_type' => 'text',
					'template' => 'last_name',
					'required' => 'yes',
					'label' => 'Last Name',
					'name' => 'last_name',
					'is_meta' => 'yes',
					'help' => '',
					'css' => '',
					'placeholder' => '',
					'default' => '',
					'size' => '40',
				),
				3 =>
				array (
					'input_type' => 'email',
					'template' => 'user_email',
					'required' => 'yes',
					'label' => 'Email',
					'name' => 'user_email',
					'is_meta' => 'yes',
					'help' => '',
					'css' => '',
					'placeholder' => '',
					'default' => '',
					'size' => '40',
				),
				4 =>
				array (
					'input_type' => 'text',
					'template' => 'user_login',
					'required' => 'yes',
					'label' => 'Username',
					'name' => 'user_login',
					'is_meta' => 'yes',
					'help' => '',
					'css' => '',
					'placeholder' => '',
					'default' => '',
					'size' => '40',
				),
				5 =>
				array (
					'input_type' => 'password',
					'template' => 'password',
					'required' => 'yes',
					'label' => 'Password',
					'name' => 'password',
					'is_meta' => 'yes',
					'help' => '',
					'css' => '',
					'placeholder' => '',
					'default' => '',
					'size' => '40',
					'min_length' => '6',
					'repeat_pass' => 'no'
				),
				6 =>
				array (
					'input_type' => 'text',
					'template' => 'display_name',
					'required' => 'yes',
					'label' => 'Display Name',
					'name' => 'display_name',
					'is_meta' => 'yes',
					'help' => '',
					'css' => '',
					'placeholder' => '',
					'default' => '',
					'size' => '40',
				),
			);
			$setting = array(
				'role' => 'subscriber',
				'redirect_to' => 'same',
				'display_form' => 'default1',
				'page_id' => '2',
				'url' => '',
				'message' => 'Registration successful',
				'update_message' => 'Form updated successfully',
				'lostpassword_message' => 'Form updated successfully',
				'submit_text' => 'Submit',
				'update_text' => 'Update',
				'email_verify' => 'no',
				'register_page' => '2',
				'notification' => array(
					'new' => 'on',
					'new_to' => $admin_email,
					'new_subject' => 'New post created',
					'new_body' => 'Hi Admin,
					A new registration has been created in your site BLOG_TITLE (BLOG_URL).

					Here is the details:
					User Name: USERNAME
					Role: ROLE'

					)
				);
			update_post_meta( $page_id , 'user-form', $register );
			update_post_meta( $page_id , 'user_form_settings', $setting );
			break;
		default:
			break;
		}
	}

	public function install_or_update_user() {
		global $wpdb, $wp_version, $wp_rewrite;
		$default = get_option( 'frontend_user_pro_options', false );
		if ( $default ){
			$default = isset( $default['db_version'] ) ? $default['db_version'] : '2.0';
		}
		$version = get_option( 'user_current_version', $default );

		// if new install
		if ( !$version ){
			$this->user_new_install();
			update_option( 'user_db_version', '2.2' );
			update_option( 'user_current_version', '2.2' );
			set_transient( '_user_activation_redirect', true, 30 );
		}
		// if 2.2 or newever
		else if ( version_compare( $version, '2.2', '>=' )  ){
			update_option( 'user_db_version', user_plugin_version );
			update_option( 'user_current_version', user_plugin_version );
			set_transient( '_user_activation_redirect', true, 30 );
		}
		// if < 2.2
		else{
			update_option( 'user_db_version', '2.1' );
			while ( version_compare( $version, '2.2', '<' ) ) {
				// version is 2.0 - 2.1
				if ( version_compare( $version, '2.1', '<' ) ){
					$this->user_v21_upgrades();
					$version = '2.1';
				}
				// version is 2.1 to 2.2
				else{
					$this->user_v22_upgrades();
					$version = '2.2';
				}
			}
			update_option( 'user_current_version', '2.2' );
			set_transient( '_user_activation_redirect', true, 30 );
		}
	}

	public function user_new_install() {
		// $this->add_new_roles();
		// $this->add_new_roles();
		$this->create_page( 'logout', __( 'LogOut ', 'frontend_user_pro' ), '' );
		$this->create_page( 'login-form', __( 'Login ', 'frontend_user_pro' ), '' );
		$this->create_post( 'user-registration-form', __( 'Registration Form', 'frontend_user_pro' ) ,'user_registrations' );
		$this->create_post( 'user-login-form', __( 'Login Form', 'frontend_user_pro' ) ,'user_logins' );
		$this->create_post( 'user-forms-form', __( 'Post Form', 'frontend_user_pro' ) ,'user_forms' );

		global $wpdb;
		$tble = $wpdb->prefix."posts";
		$sql = $wpdb->get_results("SELECT `ID` FROM $tble WHERE `post_type` = 'user_logins' ORDER BY `ID` LIMIT 1");
		foreach ($sql as $key) {
			$pos_id = $key->ID;
		}
		$con = '[wpfeup-login id="'.$pos_id.'"]';

		$ss2 = $wpdb->get_results("SELECT `ID` FROM $tble WHERE `post_name` = 'login-form' AND `post_type` = 'page' ORDER BY `ID` LIMIT 1");
		foreach ($ss2 as $key1) {
			$post_id = $key1->ID;
		}

		$my_post = array(
	      'ID'           => $post_id,
	      'post_content' => $con,
	  	);

		// Update the post into the database
		wp_update_post( $my_post );
	}

	public function user_v21_upgrades() {
		//$this->create_post( 'user-application-form', __( 'Application Form Editor', 'frontend_user_pro' ) );
	}

	public function user_v22_upgrades() {
		// convert settings panel
		$this->user_v22_settings_update();
		$this->create_post( 'user-registration-form', __( 'Registration Form', 'frontend_user_pro' ) ,'user_registrations' );
		$this->create_post( 'user-login-form', __( 'Login Form', 'frontend_user_pro' ) ,'user_logins' );
		$this->create_post( 'user-forms-form', __( 'Post Form', 'frontend_user_pro' ) ,'user_forms' );

		
		// if application form
		if ( isset( $this->toSet['user-application-form'] ) && $this->toSet['user-application-form'] != '' )
		{
			// move fields to registration form
			$old_fields = get_post_meta( $this->toSet['user-application-form'], 'user-form', true);
			$new_fields = get_post_meta( $this->toSet['user-registration-form'], 'user-form', true);

			if ( is_array( $old_fields ) && is_array( $new_fields ) ){
				$counter = 0;
				foreach( $old_fields as $field ){
					$key = 7 + $counter;
					if ( isset( $field['input_type'] ) && $field['input_type'] == 'image_upload' && isset( $field['is_meta'] ) && $field['is_meta'] == 'no' ){
						$field['input_type'] = 'file_upload';
					}
					if ( isset( $field['template'] ) && $field['template'] == 'image_upload' && isset( $field['is_meta'] ) && $field['is_meta'] == 'no' ){
						$field['template'] = 'file_upload';
					}
					// skip these fields as they are already in the new form
					$to_skip = array( 'last_name', 'first_name', 'user_email', 'username', 'password', 'display_name' );
					if ( isset( $field['template'] ) && !in_array( $field['template'] , $to_skip ) ) {
						$new_fields[$key] = $field;
						$counter++;
					}
				}
				update_post_meta( $this->toSet['user-registration-form'], 'user-form', $new_fields );
			}
		}
		// if profile form
		if ( isset( $this->toSet['user-profile-form'] ) && $this->toSet['user-profile-form'] != '' )
		{
			// add fields to profile form
			$old_fields = get_post_meta( $this->toSet['user-profile-form'], 'user-form', true);
			$nextindex = 1;
			if ( !is_array( $old_fields ) )
			{
				$old_fields = array();
			}
			else
			{
				// replace image uploaders with file ones
				foreach( $old_fields as $field ){
					if ( isset( $field['input_type'] ) && $field['input_type'] == 'image_upload' ){
						$field['input_type'] = 'file_upload';
					}
					if ( isset( $field['template'] ) && $field['template'] == 'image_upload' ){
						$field['template'] = 'file_upload';
					}
				}

				end($old_fields); 
				$last = key($old_fields);
				$nextindex = $last + 1;
			}

			$old_fields[$nextindex] = array(
				'input_type' => 'text',
				'template' => 'text_field',
				'required' => 'yes',
				'label' => 'Name of Store',
				'name' => 'name_of_store',
				'is_meta' => 'yes',
				'help' => 'What would you like your store to be called?',
				'css' => '',
				'placeholder' => '',
				'default' => '',
				'size' => '40'
				);
			$nextindex++;
			$old_fields[$nextindex] = array(
				'input_type' => 'email',
				'template' => 'email_address',
				'required' => 'yes',
				'label' => 'Email to use for Contact Form',
				'name' => 'email_to_use_for_contact_form',
				'is_meta' => 'yes',
				'help' => 'This email, if filled in will be used for the user contact forms. if it is not filled in, the one from your user profile will be used.',
				'css' => '',
				'placeholder' => '',
				'default' => '',
				'size' => '40'
				);
			update_post_meta( $this->toSet['user-profile-form'], 'user-form', $old_fields );

		// Profile form
			if ( isset( $this->toSet['user-profile-form'] ) && $this->toSet['user-profile-form'] != '' )
			{
				$id = $this->toSet['user-profile-form'];
				$update = array(
					'ID'           => $id,
					'post_title'   => __( 'Profile Form', 'frontend_user_pro' ),
					'post_name'    =>'user-profile-form'
					);
				wp_update_post( $update );
			}

		}
	}

	// for future
	public function user_v23_upgrades(){
		// delete old settings option from 2.1
		// delete all old applications
		// delete application form
	}

	public function user_v22_settings_update(){
		$old_settings = get_option( 'frontend_user_pro_options', false );
		if ( !$old_settings ){
			return;
		}

		// Submission form
		// if ( isset( $old_settings['user-submission-form'] ) && $old_settings['user-submission-form'] != '' ){
		// 	$this->toSet['user-submission-form'] = $old_settings['user-submission-form'];
		// }
		
		// Profile form
		if ( isset( $old_settings['user-profile-form'] ) && $old_settings['user-profile-form'] != '' ){
			$this->toSet['user-profile-form'] = $old_settings['user-profile-form'];
		}

		// Application form
		if ( isset( $old_settings['user-application-form'] ) && $old_settings['user-application-form'] != '' ){
			$this->toSet['user-application-form'] = $old_settings['user-application-form'];
		}		

		// User form
		if ( isset( $old_settings['user-page'] ) && $old_settings['user-page'] != '' ){
			$this->toSet['user-user-page'] = $old_settings['user-page'];
		}

		// User Dashboard form
		if ( isset( $old_settings['user-dashboard-page'] ) && $old_settings['user-dashboard-page'] != '' ){
			$this->toSet['user-user-dashboard-page'] = $old_settings['user-dashboard-page'];
		}

		// User Dashboard notification
		if ( isset( $old_settings['dashboard-page-template'] ) && $old_settings['dashboard-page-template'] !== '' ){
			$this->toSet['user-dashboard-notification'] = $old_settings['dashboard-page-template'];
		}

		// Allow Users Backend Access
		if ( isset( $old_settings['users_bea'] ) && $old_settings['users_bea'] !== '' ){
			$this->toSet['user-allow-backend-access'] = $old_settings['users_bea'];
		}		

		// Show User Registration
		if ( isset( $old_settings['show_user_registration'] ) && $old_settings['show_user_registration'] !== '' ){
			$this->toSet['show_user_registration'] = $old_settings['show_user_registration'];
		}

		// Auto Approve Users
		if ( isset( $old_settings['frontend_user_pro_auto_approve_users'] ) && $old_settings['frontend_user_pro_auto_approve_users'] !== '' ){
			$this->toSet['user-auto-approve-users'] = $old_settings['frontend_user_pro_auto_approve_users'];
		}

		// Allow Users to Edit Products
		if ( isset( $old_settings['frontend_user_pro_user_permissions_edit_product'] ) && $old_settings['frontend_user_pro_user_permissions_edit_product'] !== '' ){
			$this->toSet['user-allow-users-to-edit-products'] = $old_settings['frontend_user_pro_user_permissions_edit_product'];
		}

		// Allow Users to Delete Products
		if ( isset( $old_settings['frontend_user_pro_user_permissions_delete_product'] ) && $old_settings['frontend_user_pro_user_permissions_delete_product'] !== '' ){
			$this->toSet['user-allow-users-to-delete-products'] = $old_settings['frontend_user_pro_user_permissions_delete_product'];
		}

		// Use FRONTEND's CSS
		if ( isset( $old_settings['frontend_user_pro_use_css'] ) && $old_settings['frontend_user_pro_use_css'] !== '' ){
			$this->toSet['user-use-css'] = $old_settings['frontend_user_pro_use_css'];
		}

		// Admin notification on new user application
		if ( isset( $old_settings['frontend_user_pro_notify_admin_new_app_toggle'] ) && $old_settings['frontend_user_pro_notify_admin_new_app_toggle'] !== '' ){
			$this->toSet['user-admin-new-app-email-toggle'] = $old_settings['frontend_user_pro_notify_admin_new_app_toggle'];
		}

		// Admin message on new user application
		if ( isset( $old_settings['frontend_user_pro_notify_admin_new_app_message'] ) && $old_settings['frontend_user_pro_notify_admin_new_app_message'] != '' ){
			$this->toSet['user-admin-new-app-email'] = $old_settings['frontend_user_pro_notify_admin_new_app_message'];
		}

		// User message on new user application
		if ( isset( $old_settings['frontend_user_pro_notify_user_new_app_message'] ) && $old_settings['frontend_user_pro_notify_user_new_app_message'] != '' ){
			$this->toSet['user-user-new-app-email'] = $old_settings['frontend_user_pro_notify_user_new_app_message'];
		}

		// Admin message on new user submission
		if ( isset( $old_settings['new_frontend_user_pro_submission_admin_message'] ) && $old_settings['new_frontend_user_pro_submission_admin_message'] != '' ){
			$this->toSet['user-admin-new-submission-email'] = $old_settings['new_frontend_user_pro_submission_admin_message'];
		}

		// User message on user application accepted
		if ( isset( $old_settings['frontend_user_pro_notify_user_app_accepted_message'] ) && $old_settings['frontend_user_pro_notify_user_app_accepted_message'] != '' ){
			$this->toSet['user-user-app-approved-email'] = $old_settings['frontend_user_pro_notify_user_app_accepted_message'];
		}

		// User message on user application denied
		if ( isset( $old_settings['frontend_user_pro_notify_user_app_denied_message'] ) && $old_settings['frontend_user_pro_notify_user_app_denied_message'] != '' ){
			$this->toSet['user-user-app-declined-email'] = $old_settings['frontend_user_pro_notify_user_app_denied_message'];
		}

		// User message on new user submission
		if ( isset( $old_settings['new_frontend_user_pro_submission_user_message'] ) && $old_settings['new_frontend_user_pro_submission_user_message'] != '' ){
			$this->toSet['user-user-new-submission-email'] = $old_settings['new_frontend_user_pro_submission_user_message'];
		}

		// User message on new user submission accepted
		if ( isset( $old_settings['frontend_user_pro_submission_accepted_message'] ) && $old_settings['frontend_user_pro_submission_accepted_message'] != '' ){
			$this->toSet['user-user-submission-approved-email'] = $old_settings['frontend_user_pro_submission_accepted_message'];
		}

		// User message on new user submission declined
		if ( isset( $old_settings['frontend_user_pro_submission_declined_message'] ) && $old_settings['frontend_user_pro_submission_declined_message'] != '' ){
			$this->toSet['user-user-submission-declined-email'] = $old_settings['frontend_user_pro_submission_declined_message'];
		}

		// reCAPTCHA Public Key
		if ( isset( $old_settings['recaptcha_public'] ) && $old_settings['recaptcha_public'] != '' ){
			$this->toSet['user-recaptcha-public-key'] = $old_settings['recaptcha_public'];
		}

		// reCAPTCHA Private Key
		if ( isset( $old_settings['recaptcha_private'] ) && $old_settings['recaptcha_private'] != '' ){
			$this->toSet['user-recaptcha-private-key'] = $old_settings['recaptcha_private'];
		}
	}
}