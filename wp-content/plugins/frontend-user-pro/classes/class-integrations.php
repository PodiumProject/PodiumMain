<?php
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class USER_Integrations {
	function __construct() {
		add_filter( 'frontend_download_supports', array(
			 $this,
			'enable_reviews' 
		) );
	}
	public static function is_commissions_active() {
		if ( !defined( 'FRONTENDC_PLUGIN_DIR' ) ) {
			return false;
		} else {
			return true;
		}
	}

	public function enable_reviews( $supports ) {
		return array_merge( $supports, array(
			 'reviews' 
		) );
	}
}