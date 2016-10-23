<?php
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class USER_Emails {
	function __construct() {
	}

	// New/beta in 2.2, this function replaces shortcodes for the meta names of custom text fields and subs in the values
	function custom_meta_values( $post_id, $user_id, $type = "user", $message ){
		$profile = array('textarea', 'text','url', 'date', 'email', 'frontendc_user_paypal');
		$submission = array('text','textarea','date','url','email');
		$submission_meta = array();
		$user_meta = array();

		$form_id = FRONTEND_USER()->helper->get_option( 'user-registration-form', false );
		if ( $form_id ){
			list($user_vars, $taxonomy_vars, $meta_vars) = FRONTEND_USER()->forms->get_input_fields( $form_id );
			foreach($meta_vars as $field){
				if ( in_array( $field['input_type'], $profile ) ){
					array_push($user_meta, $field['name']);
				}
			}
		}

		foreach($user_meta as $meta ){
			$message = str_replace('{'.$meta.'}', FRONTEND_USER()->helper->get_user_meta($meta, $user_id), $message );
		}

		return $message;
	}


	// This function only exists because of #blamepippin and will probably be gone after FRONTEND 2.0 is released.
	// id is int of post or user id. Guest user ids and unpublished post ids can be null or (int) -1
	// message is string message to translate
	function email_tags( $id = null, $message = " ", $type = "other" ){

		$has_tags = ( strpos( $message, '{' ) !== false );
		$bypass_tag_check = apply_filters('user_bypass_tag_check', false, $id, $message, $type);
		if ( ! $has_tags && !$bypass_tag_check ){
			return $message;
		}

		// Some sort of email to do with users. Application received. Application approved. Etc.
		if ( $type === "user" ){
			$user = new WP_User( $id );
			$firstname = '';
			$lastname  = '';
			$fullname  = '';
			$username  = '';
			if ( isset( $user->ID ) && $user->ID > 0 && isset( $user->first_name ) ) {
				$user_data = get_userdata( $user->ID );
				$firstname = $user->first_name;
				$lastname  = $user->last_name;
				$fullname  = $user->first_name . ' ' . $user->last_name;
				$username  = $user_data->user_login;
			} elseif ( isset( $user->first_name ) ) {
				$firstname = $user->first_name;
				$lastname  = $user->last_name;
				$fullname  = $user->first_name . ' ' . $user->last_name;
				$username  = $user->first_name;
			} else {
				$name      = $user->user_email;
				$firstname = $name;
				$lastname  = $name;
				$fullname  = $name;
				$username  = $name;
			}
			$message = str_replace( '{firstname}', $firstname, $message );
			$message = str_replace( '{lastname}', $lastname, $message );
			$message = str_replace( '{fullname}', $fullname, $message );
			$message = str_replace( '{username}', $username, $message );
			$message = str_replace( '{sitename}', get_bloginfo( 'name' ), $message );
			$message = FRONTEND_USER()->emails->custom_meta_values( $post_id = 0, $user->ID, $type = "user", $message );
			return apply_filters( "user_email_tags_user", $message, $id );
		}
		else {
			return apply_filters( "user_email_tags_other", $message, $id );
		}
	}

	// Devs: Please note, you should validate & sanitize all parameters before sending them in here.
	public function send_email( $to, $from_name, $from_email, $subject, $message, $type, $id, $args ){

		if ( ! FRONTEND_USER()->emails->should_send( $args ) ) {
			return false;
		}

		// start building the email
		$message_to_send = FRONTEND_USER()->emails->email_tags( $id, $message, $type );
		$message_to_send = apply_filters('user_send_mail_message', $message_to_send, $to, $from_name, $from_email, $subject, $message, $type, $id, $args );

		if( class_exists( 'FRONTEND_Emails' ) ) {

			$emails = FRONTEND()->emails;

			$emails->from_name    = $from_name;
			$emails->from_address = $from_email;
			$emails->heading      = $subject;

			$emails->send( $to, $subject, $message_to_send );

		} else {
			$headers  = "From: " . stripslashes_deep( html_entity_decode( $from_name, ENT_COMPAT, 'UTF-8' ) ) . " <$from_email>\r\n";
			$headers .= "Reply-To: " . $from_email . "\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers = apply_filters('user_send_mail_headers', $headers, $to, $from_name, $from_email, $subject, $message, $type, $id, $args );
			wp_mail( $to, $subject, $message_to_send, $headers );
		}

	}

	public function should_send( $args = array() ){

		$ret = true;

		global $user_settings;

		if( isset( $args['permissions'] ) ) {

			// See if there's a toggle for this email in the settings panel
			// If the toggle is enabled, we send
			$ret = isset( $user_settings[ $args['permissions'] ] ) && '1' == $user_settings[ $args['permissions'] ];

		}

		return (bool) apply_filters( 'user_no_email_filter', $ret, $args );
	}
}
