<?php
/**
 * Misc Functions
 *
 * @package     FRONTEND
 * @subpackage  Functions
 * @copyright   Copyright (c) 2015, Pippin Williamson
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Is Test Mode
 *
 * @since 1.0
 * @global $frontend_options
 * @return bool $ret True if return mode is enabled, false otherwise
 */
function frontend_is_test_mode() {
	global $frontend_options;

	$ret = ! empty( $frontend_options['test_mode'] );

	return (bool) apply_filters( 'frontend_is_test_mode', $ret );
}

/**
 * Checks if Guest checkout is enabled
 *
 * @since 1.0
 * @global $frontend_options
 * @return bool $ret True if guest checkout is enabled, false otherwise
 */
function frontend_no_guest_checkout() {
	global $frontend_options;

	$ret = ! empty ( $frontend_options['logged_in_only'] );

	return (bool) apply_filters( 'frontend_no_guest_checkout', $ret );
}

/**
 * Checks if users can only purchase downloads when logged in
 *
 * @since 1.0
 * @global $frontend_options
 * @return bool $ret Whether or not the logged_in_only setting is set
 */
function frontend_logged_in_only() {
	global $frontend_options;

	$ret = ! empty( $frontend_options['logged_in_only'] );

	return (bool) apply_filters( 'frontend_logged_in_only', $ret );
}

/**
 * Redirect to checkout immediately after adding items to the cart?
 *
 * @since 1.4.2
 * @return bool $ret True is redirect is enabled, false otherwise
 */
function frontend_straight_to_checkout() {
	global $frontend_options;
	$ret = isset( $frontend_options['redirect_on_add'] );
	return (bool) apply_filters( 'frontend_straight_to_checkout', $ret );
}

/**
 * Disable Redownload
 *
 * @access public
 * @since 1.0.8.2
 * @global $frontend_options
 * @return bool True if redownloading of files is disabled, false otherwise
 */
function frontend_no_redownload() {
	global $frontend_options;

	$ret = isset( $frontend_options['disable_redownload'] );

	return (bool) apply_filters( 'frontend_no_redownload', $ret );
}

/**
 * Verify credit card numbers live?
 *
 * @since 1.4
 * @global $frontend_options
 * @return bool $ret True is verify credit cards is live
 */
function frontend_is_cc_verify_enabled() {
	global $frontend_options;

	$ret = true;

	/*
	 * Enable if use a single gateway other than PayPal or Manual. We have to assume it accepts credit cards
	 * Enable if using more than one gateway if they aren't both PayPal and manual, again assuming credit card usage
	 */

	$gateways = frontend_get_enabled_payment_gateways();

	if ( count( $gateways ) == 1 && ! isset( $gateways['paypal'] ) && ! isset( $gateways['manual'] ) ) {
		$ret = true;
	} else if ( count( $gateways ) == 1 ) {
		$ret = false;
	} else if ( count( $gateways ) == 2 && isset( $gateways['paypal'] ) && isset( $gateways['manual'] ) ) {
		$ret = false;
	}

	return (bool) apply_filters( 'frontend_verify_credit_cards', $ret );
}

/**
 * Is Odd
 *
 * Checks whether an integer is odd.
 *
 * @since 1.0
 * @param int     $int The integer to check
 * @return bool Is the integer odd?
 */
function frontend_is_odd( $int ) {
	return (bool) ( $int & 1 );
}

/**
 * Get File Extension
 *
 * Returns the file extension of a filename.
 *
 * @since 1.0
 *
 * @param unknown $str File name
 *
 * @return mixed File extension
 */
function frontend_get_file_extension( $str ) {
	$parts = explode( '.', $str );
	return end( $parts );
}

/**
 * Checks if the string (filename) provided is an image URL
 *
 * @since 1.0
 * @param string  $str Filename
 * @return bool Whether or not the filename is an image
 */
function frontend_string_is_image_url( $str ) {
	$ext = frontend_get_file_extension( $str );

	switch ( strtolower( $ext ) ) {
		case 'jpg';
			$return = true;
			break;
		case 'png';
			$return = true;
			break;
		case 'gif';
			$return = true;
			break;
		default:
			$return = false;
			break;
	}

	return (bool) apply_filters( 'frontend_string_is_image', $return, $str );
}

/**
 * Get User IP
 *
 * Returns the IP address of the current visitor
 *
 * @since 1.0.8.2
 * @return string $ip User's IP address
 */
function frontend_get_ip() {

	$ip = '127.0.0.1';

	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		//check ip from share internet
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		//to check ip is pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} elseif( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return apply_filters( 'frontend_get_ip', $ip );
}


/**
 * Get user host
 *
 * Returns the webhost this site is using if possible
 *
 * @since 2.0
 * @return mixed string $host if detected, false otherwise
 */
function frontend_get_host() {
	$host = false;

	if( defined( 'WPE_APIKEY' ) ) {
		$host = 'WP Engine';
	} elseif( defined( 'PAGELYBIN' ) ) {
		$host = 'Pagely';
	} elseif( DB_HOST == 'localhost:/tmp/mysql5.sock' ) {
		$host = 'ICDSoft';
	} elseif( DB_HOST == 'mysqlv5' ) {
		$host = 'NetworkSolutions';
	} elseif( strpos( DB_HOST, 'ipagemysql.com' ) !== false ) {
		$host = 'iPage';
	} elseif( strpos( DB_HOST, 'ipowermysql.com' ) !== false ) {
		$host = 'IPower';
	} elseif( strpos( DB_HOST, '.gridserver.com' ) !== false ) {
		$host = 'MediaTemple Grid';
	} elseif( strpos( DB_HOST, '.pair.com' ) !== false ) {
		$host = 'pair Networks';
	} elseif( strpos( DB_HOST, '.stabletransit.com' ) !== false ) {
		$host = 'Rackspace Cloud';
	} elseif( strpos( DB_HOST, '.sysfix.eu' ) !== false ) {
		$host = 'SysFix.eu Power Hosting';
	} elseif( strpos( $_SERVER['SERVER_NAME'], 'Flywheel' ) !== false ) {
		$host = 'Flywheel';
	} else {
		// Adding a general fallback for data gathering
		$host = 'DBH: ' . DB_HOST . ', SRV: ' . $_SERVER['SERVER_NAME'];
	}

	return $host;
}


/**
 * Check site host
 *
 * @since 2.0
 * @param $host The host to check
 * @return bool true if host matches, false if not
 */
function frontend_is_host( $host = false ) {

	$return = false;

	if( $host ) {
		$host = str_replace( ' ', '', strtolower( $host ) );

		switch( $host ) {
			case 'wpengine':
				if( defined( 'WPE_APIKEY' ) )
					$return = true;
				break;
			case 'pagely':
				if( defined( 'PAGELYBIN' ) )
					$return = true;
				break;
			case 'icdsoft':
				if( DB_HOST == 'localhost:/tmp/mysql5.sock' )
					$return = true;
				break;
			case 'networksolutions':
				if( DB_HOST == 'mysqlv5' )
					$return = true;
				break;
			case 'ipage':
				if( strpos( DB_HOST, 'ipagemysql.com' ) !== false )
					$return = true;
				break;
			case 'ipower':
				if( strpos( DB_HOST, 'ipowermysql.com' ) !== false )
					$return = true;
				break;
			case 'mediatemplegrid':
				if( strpos( DB_HOST, '.gridserver.com' ) !== false )
					$return = true;
				break;
			case 'pairnetworks':
				if( strpos( DB_HOST, '.pair.com' ) !== false )
					$return = true;
				break;
			case 'rackspacecloud':
				if( strpos( DB_HOST, '.stabletransit.com' ) !== false )
					$return = true;
				break;
			case 'sysfix.eu':
			case 'sysfix.eupowerhosting':
				if( strpos( DB_HOST, '.sysfix.eu' ) !== false )
					$return = true;
				break;
			case 'flywheel':
				if( strpos( $_SERVER['SERVER_NAME'], 'Flywheel' ) !== false )
					$return = true;
				break;
			default:
				$return = false;
		}
	}

	return $return;
}


/**
 * Get Currencies
 *
 * @since 1.0
 * @return array $currencies A list of the available currencies
 */
function frontend_get_currencies() {
	$currencies = array(
		'USD'  => __( 'US Dollars (&#36;)', 'frontend' ),
		'EUR'  => __( 'Euros (&euro;)', 'frontend' ),
		'GBP'  => __( 'Pounds Sterling (&pound;)', 'frontend' ),
		'AUD'  => __( 'Australian Dollars (&#36;)', 'frontend' ),
		'BRL'  => __( 'Brazilian Real (R&#36;)', 'frontend' ),
		'CAD'  => __( 'Canadian Dollars (&#36;)', 'frontend' ),
		'CZK'  => __( 'Czech Koruna', 'frontend' ),
		'DKK'  => __( 'Danish Krone', 'frontend' ),
		'HKD'  => __( 'Hong Kong Dollar (&#36;)', 'frontend' ),
		'HUF'  => __( 'Hungarian Forint', 'frontend' ),
		'ILS'  => __( 'Israeli Shekel (&#8362;)', 'frontend' ),
		'JPY'  => __( 'Japanese Yen (&yen;)', 'frontend' ),
		'MYR'  => __( 'Malaysian Ringgits', 'frontend' ),
		'MXN'  => __( 'Mexican Peso (&#36;)', 'frontend' ),
		'NZD'  => __( 'New Zealand Dollar (&#36;)', 'frontend' ),
		'NOK'  => __( 'Norwegian Krone', 'frontend' ),
		'PHP'  => __( 'Philippine Pesos', 'frontend' ),
		'PLN'  => __( 'Polish Zloty', 'frontend' ),
		'SGD'  => __( 'Singapore Dollar (&#36;)', 'frontend' ),
		'SEK'  => __( 'Swedish Krona', 'frontend' ),
		'CHF'  => __( 'Swiss Franc', 'frontend' ),
		'TWD'  => __( 'Taiwan New Dollars', 'frontend' ),
		'THB'  => __( 'Thai Baht (&#3647;)', 'frontend' ),
		'INR'  => __( 'Indian Rupee (&#8377;)', 'frontend' ),
		'TRY'  => __( 'Turkish Lira (&#8378;)', 'frontend' ),
		'RIAL' => __( 'Iranian Rial (&#65020;)', 'frontend' ),
		'RUB'  => __( 'Russian Rubles', 'frontend' )
	);

	return apply_filters( 'frontend_currencies', $currencies );
}

/**
 * Get the store's set currency
 *
 * @since 1.5.2
 * @return string The currency code
 */
function frontend_get_currency() {
	global $frontend_options;
	$currency = isset( $frontend_options['currency'] ) ? $frontend_options['currency'] : 'USD';
	return apply_filters( 'frontend_currency', $currency );
}

/**
 * Given a currency determine the symbol to use. If no currency given, site default is used.
 * If no symbol is determine, the currency string is returned.
 *
 * @since  2.2
 * @param  string $currency The currency string
 * @return string           The symbol to use for the currency
 */
function frontend_currency_symbol( $currency = '' ) {
	global $frontend_options;

	if ( empty( $currency ) ) {
		$currency = frontend_get_currency();
	}

	switch ( $currency ) :
		case "GBP" :
			$symbol = '&pound;';
			break;
		case "BRL" :
			$symbol = 'R&#36;';
			break;
		case "EUR" :
			$symbol = '&euro;';
			break;
		case "USD" :
		case "AUD" :
		case "NZD" :
		case "CAD" :
		case "HKD" :
		case "MXN" :
		case "SGD" :
			$symbol = '&#36;';
			break;
		case "JPY" :
			$symbol = '&yen;';
			break;
		default :
			$symbol = $currency;
			break;
	endswitch;

	return apply_filters( 'frontend_currency_symbol', $symbol, $currency );
}

/**
 * Get the name of a currency
 *
 * @since 2.2
 * @param  string $currency The currency code
 * @return string The currency's name
 */
function frontend_get_currency_name( $code = 'USD' ) {
	$currencies = frontend_get_currencies();
	$name       = isset( $currencies[ $code ] ) ? $currencies[ $code ] : $code;
	return apply_filters( 'frontend_currency_name', $name );
}

/**
 * Month Num To Name
 *
 * Takes a month number and returns the name three letter name of it.
 *
 * @since 1.0
 *
 * @param unknown $n
 * @return string Short month name
 */
function frontend_month_num_to_name( $n ) {
	$timestamp = mktime( 0, 0, 0, $n, 1, 2005 );

	return date_i18n( "M", $timestamp );
}

/**
 * Get PHP Arg Separator Output
 *
 * @since 1.0.8.3
 * @return string Arg separator output
 */
function frontend_get_php_arg_separator_output() {
	return ini_get( 'arg_separator.output' );
}

/**
 * Get the current page URL
 *
 * @since 1.3
 * @global $post
 * @return string $page_url Current page URL
 */
function frontend_get_current_page_url() {
	global $post;

	if ( is_front_page() ) :
		$page_url = home_url();
	else :
		$page_url = 'http';

	if ( isset( $_SERVER["HTTPS"] ) && $_SERVER["HTTPS"] == "on" )
		$page_url .= "s";

	$page_url .= "://";

	if ( isset( $_SERVER["SERVER_PORT"] ) && $_SERVER["SERVER_PORT"] != "80" )
		$page_url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	else
		$page_url .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	endif;

	return apply_filters( 'frontend_get_current_page_url', esc_url( $page_url ) );
}

/**
 * Marks a function as deprecated and informs when it has been used.
 *
 * There is a hook frontend_deprecated_function_run that will be called that can be used
 * to get the backtrace up to what file and function called the deprecated
 * function.
 *
 * The current behavior is to trigger a user error if WP_DEBUG is true.
 *
 * This function is to be used in every function that is deprecated.
 *
 * @uses do_action() Calls 'frontend_deprecated_function_run' and passes the function name, what to use instead,
 *   and the version the function was deprecated in.
 * @uses apply_filters() Calls 'frontend_deprecated_function_trigger_error' and expects boolean value of true to do
 *   trigger or false to not trigger error.
 *
 * @param string  $function    The function that was called
 * @param string  $version     The version of WordPress that deprecated the function
 * @param string  $replacement Optional. The function that should have been called
 * @param array   $backtrace   Optional. Contains stack backtrace of deprecated function
 */
function _frontend_deprecated_function( $function, $version, $replacement = null, $backtrace = null ) {
	do_action( 'frontend_deprecated_function_run', $function, $replacement, $version );

	$show_errors = current_user_can( 'manage_options' );

	// Allow plugin to filter the output error trigger
	if ( WP_DEBUG && apply_filters( 'frontend_deprecated_function_trigger_error', $show_errors ) ) {
		if ( ! is_null( $replacement ) ) {
			trigger_error( sprintf( __( '%1$s is <strong>deprecated</strong> since Frontend User version %2$s! Use %3$s instead.', 'frontend' ), $function, $version, $replacement ) );
			trigger_error(  print_r( $backtrace, 1 ) ); // Limited to previous 1028 characters, but since we only need to move back 1 in stack that should be fine.
			// Alternatively we could dump this to a file.
		}
		else {
			trigger_error( sprintf( __( '%1$s is <strong>deprecated</strong> since Frontend User version %2$s with no alternative available.', 'frontend' ), $function, $version ) );
			trigger_error( print_r( $backtrace, 1 ) );// Limited to previous 1028 characters, but since we only need to move back 1 in stack that should be fine.
			// Alternatively we could dump this to a file.
		}
	}
}


/**
 * Checks whether function is disabled.
 *
 * @since 1.3.5
 *
 * @param string  $function Name of the function.
 * @return bool Whether or not function is disabled.
 */
function frontend_is_func_disabled( $function ) {
	$disabled = explode( ',',  ini_get( 'disable_functions' ) );

	return in_array( $function, $disabled );
}

/**
 * FRONTEND Let To Num
 *
 * Does Size Conversions
 *
 * @since 1.4
 * @usedby frontend_settings()
 * @author Chris Christoff
 *
 * @param unknown $v
 * @return int|string
 */
function frontend_let_to_num( $v ) {
	$l   = substr( $v, -1 );
	$ret = substr( $v, 0, -1 );

	switch ( strtoupper( $l ) ) {
		case 'P': // fall-through
		case 'T': // fall-through
		case 'G': // fall-through
		case 'M': // fall-through
		case 'K': // fall-through
			$ret *= 1024;
			break;
		default:
			break;
	}

	return $ret;
}

/**
 * Retrieve the URL of the symlink directory
 *
 * @since 1.5
 * @return string $url URL of the symlink directory
 */
function frontend_get_symlink_url() {
	$wp_upload_dir = wp_upload_dir();
	wp_mkdir_p( $wp_upload_dir['basedir'] . '/frontend/symlinks' );
	$url = $wp_upload_dir['baseurl'] . '/frontend/symlinks';

	return apply_filters( 'frontend_get_symlink_url', $url );
}

/**
 * Retrieve the absolute path to the symlink directory
 *
 * @since  1.5
 * @return string $path Absolute path to the symlink directory
 */
function frontend_get_symlink_dir() {
	$wp_upload_dir = wp_upload_dir();
	wp_mkdir_p( $wp_upload_dir['basedir'] . '/frontend/symlinks' );
	$path = $wp_upload_dir['basedir'] . '/frontend/symlinks';

	return apply_filters( 'frontend_get_symlink_dir', $path );
}

/**
 * Retrieve the absolute path to the file upload directory without the trailing slash
 *
 * @since  1.8
 * @return string $path Absolute path to the FRONTEND upload directory
 */
function frontend_get_upload_dir() {
	$wp_upload_dir = wp_upload_dir();
	wp_mkdir_p( $wp_upload_dir['basedir'] . '/frontend' );
	$path = $wp_upload_dir['basedir'] . '/frontend';

	return apply_filters( 'frontend_get_upload_dir', $path );
}

/**
 * Delete symbolic links after they have been used
 *
 * @access public
 * @since  1.5
 * @return void
 */
function frontend_cleanup_file_symlinks() {
	$path = frontend_get_symlink_dir();
	$dir = opendir( $path );

	while ( ( $file = readdir( $dir ) ) !== false ) {
		if ( $file == '.' || $file == '..' )
			continue;

		$transient = get_transient( md5( $file ) );
		if ( $transient === false )
			@unlink( $path . '/' . $file );
	}
}
add_action( 'frontend_cleanup_file_symlinks', 'frontend_cleanup_file_symlinks' );

/**
 * Checks if SKUs are enabled
 *
 * @since 1.6
 * @global $frontend_options
 * @author Daniel J Griffiths
 * @return bool $ret True if SKUs are enabled, false otherwise
 */
function frontend_use_skus() {
	global $frontend_options;

	$ret = isset( $frontend_options['enable_skus'] );

	return (bool) apply_filters( 'frontend_use_skus', $ret );
}



/**
 * Retrieve timezone
 *
 * @since 1.6
 * @return string $timezone The timezone ID
 */
function frontend_get_timezone_id() {

	// if site timezone string exists, return it
	if ( $timezone = get_option( 'timezone_string' ) )
		return $timezone;

	// get UTC offset, if it isn't set return UTC
	if ( ! ( $utc_offset = 3600 * get_option( 'gmt_offset', 0 ) ) )
		return 'UTC';

	// attempt to guess the timezone string from the UTC offset
	$timezone = timezone_name_from_abbr( '', $utc_offset );

	// last try, guess timezone string manually
	if ( $timezone === false ) {

		$is_dst = date( 'I' );

		foreach ( timezone_abbreviations_list() as $abbr ) {
			foreach ( $abbr as $city ) {
				if ( $city['dst'] == $is_dst &&  $city['offset'] == $utc_offset )
					return $city['timezone_id'];
			}
		}
	}

	// fallback
	return 'UTC';
}

/**
 * Convert an object to an associative array.
 *
 * Can handle multidimensional arrays
 *
 * @since 1.7
 *
 * @param unknown $data
 * @return array
 */
function frontend_object_to_array( $data ) {
	if ( is_array( $data ) || is_object( $data ) ) {
		$result = array();
		foreach ( $data as $key => $value ) {
			$result[ $key ] = frontend_object_to_array( $value );
		}
		return $result;
	}
	return $data;
}

/**
 * Set Upload Directory
 *
 * Sets the upload dir to frontend. This function is called from
 * frontend_change_downloads_upload_dir()
 *
 * @since 1.0
 * @return array Upload directory information
 */
function frontend_set_upload_dir( $upload ) {

	// Override the year / month being based on the post publication date, if year/month organization is enabled
	if ( get_option( 'uploads_use_yearmonth_folders' ) ) {
		// Generate the yearly and monthly dirs
		$time = current_time( 'mysql' );
		$y = substr( $time, 0, 4 );
		$m = substr( $time, 5, 2 );
		$upload['subdir'] = "/$y/$m";
	}

	$upload['subdir'] = '/frontend' . $upload['subdir'];
	$upload['path']   = $upload['basedir'] . $upload['subdir'];
	$upload['url']    = $upload['baseurl'] . $upload['subdir'];
	return $upload;
}


if ( ! function_exists( 'cal_days_in_month' ) ) {
	// Fallback in case the calendar extension is not loaded in PHP
	// Only supports Gregorian calendar
	function cal_days_in_month( $calendar, $month, $year ) {
		return date( 't', mktime( 0, 0, 0, $month, 1, $year ) );
	}
}


if ( ! function_exists( 'hash_equals' ) ) :
/**
 * Compare two strings in constant time.
 *
 * This function was added in PHP 5.6.
 * It can leak the length of a string.
 *
 * @since 2.2.1
 *
 * @param string $a Expected string.
 * @param string $b Actual string.
 * @return bool Whether strings are equal.
 */
function hash_equals( $a, $b ) {
	$a_length = strlen( $a );
	if ( $a_length !== strlen( $b ) ) {
		return false;
	}
	$result = 0;

	// Do not attempt to "optimize" this.
	for ( $i = 0; $i < $a_length; $i++ ) {
		$result |= ord( $a[ $i ] ) ^ ord( $b[ $i ] );
	}

	return $result === 0;
}
endif;