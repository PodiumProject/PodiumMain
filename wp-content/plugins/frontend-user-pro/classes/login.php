<?php
class USER_Login {
	private $messages = array();
	private $login_errors = array();
	function __construct() {
		add_action( 'user_login_form_after_form', array($this, 'user_process_lostpass') );
		add_action( 'init', array($this, 'user_process_logout') );
		add_action( 'init', array($this, 'user_process_reset_password') );

		add_filter( 'lostpassword_url', array($this, 'user_filter_lostpassword_url'), 10, 2 );

		add_filter( 'login_url', array($this, 'user_filter_login_url'), 10, 2 );
		add_filter( 'logout_url', array($this, 'user_filter_logout_url'), 10, 2 );
		add_filter( 'wp_head', array($this, 'user_process_reset_pass'), 10, 2 );
	}
	function user_process_reset_pass() {
		if ($_GET) 
		{
			if (array_key_exists('action', $_GET) && $_GET['action'] == 'rp' || array_key_exists('reset', $_GET)) 
			{
				if ( isset( $_GET['reset'] ) && $_GET['reset'] == 'true' ) {
					echo "<div class='reset_section md-show' style='z-index: 99999;'>";
					echo "<div id='login_reset'>";
					printf( '<div class="user-message">' . __( 'Your password has been reset. %s', 'user' ) . '</div>', sprintf( '<a href="%s">%s</a>', $this->user_get_action_url( 'login' ), __( 'Log In', 'user' ) ) );
					
					echo "</div></div>";
					echo "<div class='md-overlay' style='z-index: 9999;'></div>";
					// return;
				} else {
					
					echo "<div class='reset_section md-show' style='z-index: 99999;'>";
					$this->messages[] = __( '<h2>Enter your new password below..</h2>', 'user' );

					$this->user_load_template( 'reset-pass-form.php', $args );
					echo "</div>";
					echo "<div class='md-overlay' style='z-index: 9999;'></div>";
				}
			}
		}
		?>
		<style type="text/css">
			
			#user-reset-form , #login_reset{
				background: rgba(0,0,0,0) none repeat scroll 0 0;
				opacity: 1;
				padding: 3%;
				position: relative;
				text-align: center;
				transform: scale(1);
				transition: all 0.3s ease 0s;
			}
			.md-overlay {
				background: rgba(0, 0, 0, 0.8) none repeat scroll 0 0;
				height: 100%;
				left: 0;
				opacity: 0;
				position: fixed;
				top: 0;
				transition: all 0.3s ease 0s;
				visibility: hidden;
				width: 100%;
				z-index: 1000;
			}
			.md-show ~ .md-overlay {
				opacity: 1;
				visibility: visible;
			}
			.reset_section {
				backface-visibility: hidden;
				height: auto;
				left: 50%;
				margin: 0 auto;
				max-width: 630px;
				position: fixed;
				top: 50%;
				transform: translateX(-50%) translateY(-50%);
				width: 50%;
				z-index:999;
			}
		</style>
		<?php
	}
	function user_load_template( $file, $args = array() ) {
		if ( $args && is_array( $args ) ) {
			extract( $args );
		}

		$child_theme_dir = get_stylesheet_directory() . '/user/';
		$parent_theme_dir = get_template_directory() . '/user/';
		$user_dir = dirname( __FILE__ )  . '/';

		if ( file_exists( $child_theme_dir . $file ) ) {
			include $child_theme_dir . $file;

		} else if ( file_exists( $parent_theme_dir . $file ) ) {
			include $parent_theme_dir . $file;

		} else {

			include $user_dir . $file;
		}
	}

	function user_process_lostpass() {
		$login_page = $this->user_get_login_url();

		if ( false === $login_page ) {
			return;
		}

		ob_start();

		if ( is_user_logged_in() ) {

			$this->user_load_template( 'logged-in.php', array(
				'user' => wp_get_current_user()
				) );
		} else {

			$action = isset( $_GET['action'] ) ? $_GET['action'] : 'login';

			$args = array(
				'action_url' => $login_page,
				);
			if(isset($_GET['action'])) {
				$r_action = $_GET['action'];
			}
			else {
				$r_action = '';	
			}
			if ($r_action == 'rp' || isset( $_GET['reset'] )) {
				if ( isset( $_GET['reset'] ) && $_GET['reset'] == 'true' ) {

					printf( '<div class="user-message">' . __( 'Your password has been reset. %s', 'user' ) . '</div>', sprintf( '<a href="%s">%s</a>', $this->user_get_action_url( 'login' ), __( 'Log In', 'user' ) ) );
					return;
				} else {

					$this->messages[] = __( 'Enter your new password below..', 'user' );

					$this->user_load_template( 'reset-pass-form.php', $args );
				}
				exit();
			}elseif ($r_action == 'lostpassword') {
				$this->messages[] = __( 'Please enter your username or email address. You will receive a link to create a new password via email.', 'user' );

				$this->user_load_template( 'lost-pass-form.php', $args );
				exit();
			}else{
				if ( isset( $_GET['checkemail'] ) && $_GET['checkemail'] == 'confirm' ) {
					$this->messages[] = __( 'Check your e-mail for the confirmation link.', 'user' );
				}

				if ( isset( $_GET['loggedout'] ) && $_GET['loggedout'] == 'true' ) {
					$this->messages[] = __( 'You are now logged out.', 'user' );
				}

				$this->user_load_template( 'login-form.php', $args );
			}
			
		
		}
		return ob_get_clean();
	}
	
	function user_process_logout() {
		if ( isset( $_GET['action'] ) && $_GET['action'] == 'logout' ) {
			check_admin_referer('log-out');
			wp_logout();

			$page_id = FRONTEND_USER()->helper->get_option( 'user-logout-redirect', false );
			if ($page_id) {
				$url = get_permalink( $page_id );
			}else{
				$url = home_url();
			}
			$redirect_to = !empty( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : add_query_arg( array( 'loggedout' => 'true' ), $url ) ;
			wp_safe_redirect( $redirect_to );
			exit();
		}
	}

	function user_process_reset_password() {
		if ( ! isset( $_POST['user_reset_password'] ) ) {
			return;
		}

        // process lost password form
		if ( isset( $_POST['user_login'] ) && isset( $_POST['_wpnonce'] ) ) {
			wp_verify_nonce( $_POST['_wpnonce'], 'user_lost_pass' );

			if ( $this->user_retrieve_password() ) {
				$url = add_query_arg( array( 'checkemail' => 'confirm' ), $this->user_get_login_url() );
				wp_redirect( $url );
				exit;
			}
		}

        // process reset password form
		if ( isset( $_POST['pass1'] ) && isset( $_POST['pass2'] ) && isset( $_POST['key'] ) && isset( $_POST['login'] ) && isset( $_POST['_wpnonce'] ) ) {

            // verify reset key again
			$user = $this->user_check_password_reset_key( $_POST['key'], $_POST['login'] );

			if ( is_object( $user ) ) {

                // save these values into the form again in case of errors
				$args['key']   = $_POST['key'];
				$args['login'] = $_POST['login'];

				wp_verify_nonce( $_POST['_wpnonce'], 'user_reset_pass' );

				if ( empty( $_POST['pass1'] ) || empty( $_POST['pass2'] ) ) {
					$this->login_errors[] = __( 'Please enter your password.', 'user' );
					return;
				}

				if ( $_POST[ 'pass1' ] !== $_POST[ 'pass2' ] ) {
					$this->login_errors[] = __( 'Passwords do not match.', 'user' );
					return;
				}

				$errors = new WP_Error();

				do_action( 'validate_password_reset', $errors, $user );

				if ( $errors->get_error_messages() ) {
					foreach ( $errors->get_error_messages() as $error ) {
						$this->login_errors[] = $error;
					}

					return;
				}

				if ( ! $this->login_errors ) {

					$this->user_reset_password( $user, $_POST['pass1'] );

					do_action( 'user_customer_reset_password', $user );

					wp_redirect( add_query_arg( 'reset', 'true', remove_query_arg( array( 'key', 'login' ) ) ) );
					exit;
				}
			}

		}
	}

	function user_filter_lostpassword_url( $url, $redirect ) {
		return $this->user_get_action_url( 'lostpassword', $redirect );
	}

	function user_filter_login_url( $url, $redirect ) {
		return $this->user_get_action_url( 'login', $redirect );
	}

	function user_filter_logout_url( $url, $redirect ) {
		return $this->user_get_action_url( 'logout', $redirect );
	}

	function login_form() {

		$login_page = $this->user_get_login_url();

		if ( false === $login_page ) {
			return;
		}

		ob_start();

		if ( is_user_logged_in() ) {

			$this->user_load_template( 'logged-in.php', array(
				'user' => wp_get_current_user()
				) ); 
		} else {

			$action = isset( $_GET['action'] ) ? $_GET['action'] : 'login';

			$args = array(
				'action_url' => $login_page,
				);

			switch ($action) {
				case 'lostpassword':

				$this->messages[] = __( 'Please enter your username or email address. You will receive a link to create a new password via email.', 'user' );

				$this->user_load_template( 'lost-pass-form.php', $args );
				break;

				case 'rp':
				case 'resetpass':

				if ( isset( $_GET['reset'] ) && $_GET['reset'] == 'true' ) {

					printf( '<div class="user-message">' . __( 'Your password has been reset. %s', 'user' ) . '</div>', sprintf( '<a href="%s">%s</a>', $this->get_action_url( 'login' ), __( 'Log In', 'user' ) ) );
					return;
				} else {

					$this->messages[] = __( 'Enter your new password below..', 'user' );

					$this->user_load_template( 'reset-pass-form.php', $args );
				}

				break;

				default:

				if ( isset( $_GET['checkemail'] ) && $_GET['checkemail'] == 'confirm' ) {
					$this->messages[] = __( 'Check your e-mail for the confirmation link.', 'user' );
				}

				if ( isset( $_GET['loggedout'] ) && $_GET['loggedout'] == 'true' ) {
					$this->messages[] = __( 'You are now logged out.', 'user' );
				}

				$this->user_load_template( 'login-form.php', $args );
				break;
			}
		}
		return ob_get_clean();
	}
	function user_get_login_url() {
		$page_id = FRONTEND_USER()->helper->get_option( 'user-login-url', false );

		if ( !$page_id ) {
			return site_url()."/wp-login.php";
			//return false;
		}

		$url = get_permalink( $page_id );

		return apply_filters( 'user_login_url', $url, $page_id );
	}
	
	// function user_get_login_url() {
	// 	$page_id = FRONTEND_USER()->helper->get_option( 'user-login-form-page', false ); 
	// 	$page_id = FRONTEND_USER()->helper->get_option( 'user-login-redirect', false );

	// 	if ( !$page_id ) {
	// 		return false;
	// 	}

	// 	$url = get_permalink( $page_id );

	// 	return apply_filters( 'user_login_url', $url, $page_id );
	// }

	function user_get_action_url( $action = 'login', $redirect_to = '' ) {
		$root_url = $this->user_get_login_url();
		switch ($action) {
			case 'resetpass':
			return add_query_arg( array('action' => 'resetpass'), $root_url );
			break;

			case 'lostpassword':
           // echo add_query_arg( array('action' => 'lostpassword'), $root_url );
			return add_query_arg( array('action' => 'lostpassword'), $root_url );
			break;

            // case 'register':
            //     return $this->get_registration_url();
            //     break;

			case 'logout':
			return wp_nonce_url( add_query_arg( array('action' => 'logout'), $root_url ), 'log-out' );
			break;

			default:
			if ( empty( $redirect_to ) ) {
				return $root_url;
			}

			return add_query_arg( array('redirect_to' => urlencode( $redirect_to )), $root_url );
			break;
		}
	}

	function user_reset_password( $user, $new_pass ) {
		do_action( 'password_reset', $user, $new_pass );

		wp_set_password( $new_pass, $user->ID );

		wp_password_change_notification( $user );
	}

	function user_check_password_reset_key( $key, $login ) {
		global $wpdb;
		// $key = preg_replace( '/[^a-z0-9]/i', '', $key );
		if ( empty( $key ) || ! is_string( $key ) ) {
			$this->login_errors[] = __( 'Invalid key', 'user' );
			return false;
		}

		if ( empty( $login ) || ! is_string( $login ) ) {
			$this->login_errors[] = __( 'Invalid key', 'user' );
			return false;
		}

		$user = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $key, $login ) );

		if ( empty( $user ) ) {
			$this->login_errors[] = __( 'Invalid key', 'user' );
			return false;
		}

		return $user;
	}

	function show_errors() {
		if (  $this->login_errors ) {
			foreach ( $this->login_errors as $error) {
				printf( '<div class="user-error">%s</div>', $error );
			}
		}
	}

	function show_messages() {
		if ( $this->messages ) {
			foreach ($this->messages as $message) {
				printf( '<div class="user-message">%s</div>', $message );
			}
		}
	}
	function user_retrieve_password() {
		global $wpdb;

		if ( empty( $_POST['user_login'] ) ) {

			$this->login_errors[] = __( 'Enter a username or e-mail address.', 'user' );
			return;

		} elseif ( strpos( $_POST['user_login'], '@' ) && apply_filters( 'user_get_username_from_email', true ) ) {

			$user_data = get_user_by( 'email', trim( $_POST['user_login'] ) );

			if ( empty( $user_data ) ) {
				$this->login_errors[] = __( 'There is no user registered with that email address.', 'user' );
				return;
			}

		} else {

			$login = trim( $_POST['user_login'] );

			$user_data = get_user_by( 'login', $login );
		}

		do_action('lostpassword_post');

		if ( $this->login_errors ) {
			return false;
		}

		if ( ! $user_data ) {
			$this->login_errors[] = __( 'Invalid username or e-mail.', 'user' );
			return false;
		}

        // redefining user_login ensures we return the right case in the email
		$user_login = $user_data->user_login;
		$user_email = $user_data->user_email;

		do_action('retrieve_password', $user_login);

		$allow = apply_filters('allow_password_reset', true, $user_data->ID);

		if ( ! $allow ) {

			$this->login_errors[] = __( 'Password reset is not allowed for this user', 'user' );
			return false;

		} elseif ( is_wp_error( $allow ) ) {

			$this->login_errors[] = $allow->get_error_message();
			return false;
		}

		$key = $wpdb->get_var( $wpdb->prepare( "SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login ) );

		if ( empty( $key ) ) {

            // Generate something random for a key...
			$key = wp_generate_password( 20, false );

			do_action('retrieve_password_key', $user_login, $user_email, $key);

            // Now insert the new md5 key into the db
			$wpdb->update( $wpdb->users, array( 'user_activation_key' => $key ), array( 'user_login' => $user_login ) );
		}

        // Send email notification
		$this->email_reset_pass( $user_login, $user_email, $key );

		return true;
	}

	function email_reset_pass( $user_login, $user_email, $key ) {
		$reset_url = add_query_arg( array( 'action' => 'rp', 'key' => $key, 'login' => $user_login ), site_url().'/' );

		$message = __('Someone requested that the password be reset for the following account:') . "\r\n\r\n";
		$message .= network_home_url( '/' ) . "\r\n\r\n";
		$message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
		$message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
		$message .= __('To reset your password, visit the following address:') . "\r\n\r\n";
		$message .= '<' . $reset_url . ">\r\n";

		if ( is_multisite() ) {
			$blogname = $GLOBALS['current_site']->site_name;
		} else {
			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
		}

		$title = sprintf( __('[%s] Password Reset'), $blogname );

		$title = apply_filters( 'retrieve_password_title', $title );
		$message = apply_filters( 'retrieve_password_message', $message, $key );

		if ( $message && !wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) ) {
			wp_die( __('The e-mail could not be sent.') . "<br />\n" . __('Possible reason: your host may have disabled the mail() function.') );
		}
	}

	function user_get_action_links( $args = array() ) {
		$defaults = array(
			'login' => true,
			'register' => true,
			'lostpassword' => true
			);

		$args = wp_parse_args( $args, $defaults );
		$links = array();

		if ( $args['login'] ) {
			$links[] = sprintf( '<a href="%s">%s</a>', $this->user_get_action_url( 'login' ), __( 'Log In', 'user' ) );
		}

		if ( $args['register'] ) {
			$links[] = sprintf( '<a href="%s">%s</a>', $this->user_get_action_url( 'register' ), __( 'Register', 'user' ) );
		}

		if ( $args['lostpassword'] ) {
			$links[] = sprintf( '<a href="%s">%s</a>', $this->user_get_action_url( 'lostpassword' ), __( 'Lost Password', 'user' ) );
		}

		return implode( ' | ', $links );
	}
	public static function get_posted_value( $key ) {
		if ( isset( $_REQUEST[$key] ) ) {
			return esc_attr( $_REQUEST[$key] );
		}

		return '';
	}
}