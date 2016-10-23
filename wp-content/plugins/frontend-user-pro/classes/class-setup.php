<?php
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class USER_Setup {
	public function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'switch_theme', 'flush_rewrite_rules', 15 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array(
				$this,
				'enqueue_styles'
			) );
		add_action( 'admin_enqueue_scripts', array(
				$this,
				'admin_enqueue_scripts'
			) );
		add_action( 'admin_enqueue_scripts', array(
				$this,
				'admin_enqueue_styles'
			) );
		add_action( 'wp_head', array(
				$this,
				'user_version'
			) );
		add_action( 'admin_head', array(
				$this,
				'admin_head'
			) );
		add_filter( 'media_upload_tabs', array(
				$this,
				'remove_media_library_tab'
			) );
		add_action( 'wp_footer', array(
				$this,
				'frontend_lockup_uploaded'
			) );
		add_post_type_support( 'download', 'author' );
		add_post_type_support( 'download', 'comments' );
		add_action( 'frontend_system_info_after', array(
				$this,
				'user_add_below_system_info'
			) );

		add_filter( 'parse_query', array( $this, 'restrict_media' ) );

		// custom columns
		add_filter( 'manage_edit-user-forms_columns', array( $this, 'user_forms_admin_column' ) );
		add_filter( 'manage_edit-user_logins_columns', array( $this, 'user_forms_admin_column' ) );
        add_filter( 'manage_edit-user_registrations_columns', array( $this, 'user_forms_admin_column' ) );

        add_action( 'manage_user-forms_posts_custom_column', array( $this, 'user_frontend_form_admin_column_value' ), 10, 2 );
        add_action( 'manage_user_logins_posts_custom_column', array( $this, 'user_login_admin_column_value' ), 10, 2 );
        add_action( 'manage_user_registrations_posts_custom_column', array( $this, 'user_registrations_admin_column_value' ), 10, 2 );
   	}

	public function enqueue_form_assets(){
		if ( !is_page( FRONTEND_USER()->helper->get_option( 'user-user-dashboard-page', false ) ) ) {
			FRONTEND_USER()->setup->enqueue_styles( true );
			FRONTEND_USER()->setup->enqueue_scripts( true );
		}
	}
	public function user_forms_admin_column () {
		$columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __( 'Form Name', 'frontend_user_pro' ),            
            'shortcode' => __( 'Shortcode', 'frontend_user_pro' )
        );

        return $columns;
	}	
	public function enqueue_scripts( $override = false ) {
		if ( is_admin() ) {
			return;
		}
		global $post;
		if ( is_page( FRONTEND_USER()->helper->get_option( 'user-user-dashboard-page', false ) ) || $override ) {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'underscore' );
			// USER outputs minified scripts by default on the frontend. To load full versions, hook into this and return empty string.
			$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
			$minify = apply_filters('user_output_minified_versions', $suffix );
			wp_enqueue_script( 'user_form', user_plugin_url . 'assets/js/frontend-form' . $minify . '.js', array(
					'jquery'
				), user_plugin_version );
			wp_localize_script( 'user_form', 'user_form', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'error_message' => __( 'Please fix the errors to proceed', 'frontend_user_pro' ),
				'nonce' => wp_create_nonce( 'user_nonce' ),
				'avatar_title' =>  __( 'Choose an avatar', 'frontend_user_pro' ),
				'avatar_button' =>  __( 'Select as avatar', 'frontend_user_pro' ),
				'file_title' =>  __( 'Choose a file', 'frontend_user_pro' ),
				'file_button' =>  __( 'Insert file URL', 'frontend_user_pro' ),
				'feat_title' =>  __( 'Choose a featured image', 'frontend_user_pro' ),
				'feat_button' =>  __( 'Select as featured image', 'frontend_user_pro' ),
				'one_option' => __( 'You must have at least one option', 'frontend_user_pro' ),
				'too_many_files_pt_1' => __( 'You may not add more than ', 'frontend_user_pro' ),
				'too_many_files_pt_2' => __( ' files!', 'frontend_user_pro' )
			) );

			wp_enqueue_media();

			$scheme = is_ssl() ? 'https' : 'http';
			wp_enqueue_script( 'google-maps', $scheme . '://maps.google.com/maps/api/js?sensor=true' );
			wp_enqueue_script( 'comment-reply' );
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'jquery-ui-autocomplete' );
			wp_enqueue_script( 'suggest' );
			wp_enqueue_script( 'jquery-ui-slider' );
			wp_enqueue_script( 'jquery-ui-timepicker', user_plugin_url . 'assets/js/jquery-ui-timepicker-addon.js', array(
					'jquery-ui-datepicker'
				) );
			

		}
	}

	function user_registrations_admin_column_value ( $column_name, $post_id ) {

		switch ($column_name) {
			case 'shortcode':
			printf( 'Registration: [wpfeup-register type="registration" id="%d"]<br>', $post_id );
			printf( 'Edit Profile: [wpfeup-register type="profile" id="%d"]', $post_id );
			break;            
		}
	}

	public function enqueue_styles( $override = false ) {
		if ( is_admin() ) {
			return;
		}
		global $post;
		if ( is_page( FRONTEND_USER()->helper->get_option( 'user-user-dashboard-page', false ) ) || $override ) {
			// USER outputs minified scripts by default on the frontend. To load full versions, hook into this and return empty string.
			$minify = apply_filters('user_output_minified_versions', '.min' );
			if ( FRONTEND_USER()->helper->get_option( 'user-use-css', true ) ){
				wp_enqueue_style( 'user-css', user_plugin_url . 'assets/css/frontend' . $minify . '.css' );
			}
			// wp_enqueue_style( 'jquery-ui', user_plugin_url . 'assets/css/jquery-ui-1.9.1.custom.css' );
		}
	}

	public function admin_enqueue_scripts() {
		if ( !is_admin() ) {
			return;
		}
		global $pagenow, $post;
		$current_screen = get_current_screen(); 

		$in_array = array( 'user-forms', 'user_logins', 'user_registrations' ); 

		if ( in_array( $current_screen->post_type, $in_array ) || $current_screen->base === 'frontend-user_page_user-users'){
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui-autocomplete' );
			wp_enqueue_script( 'jquery-smallipop', user_plugin_url . 'assets/js/jquery.smallipop-0.4.0.min.js', array(
					'jquery'
				) );

			wp_enqueue_script( 'user-formbuilder', user_plugin_url . 'assets/js/formbuilder.js', array(
					'jquery',
					'jquery-ui-sortable'
				) );

			wp_register_script( 'jquery-tiptip', user_plugin_url . 'assets/js/jquery-tiptip/jquery.tipTip.min.js', array(
					'jquery'
				), '2.0', true );
		}
		if ( $current_screen->post_type === 'download' || $pagenow == 'profile.php' || $current_screen->base === 'frontend-user_page_user-users' ) {
			wp_enqueue_script( 'jquery' );
			wp_register_script( 'jquery-tiptip', user_plugin_url . 'assets/js/jquery-tiptip/jquery.tipTip.min.js', array(
					'jquery'
				), '2.0', true );
			wp_enqueue_script( 'frontend-user-admin-js', user_plugin_url . 'assets/js/admin.js', array(
					'jquery',
					'jquery-tiptip'
				), '2.0', true );
			wp_enqueue_script( 'jquery-ui-autocomplete' );
			wp_enqueue_script( 'underscore' );
			wp_enqueue_script( 'user-form', user_plugin_url . 'assets/js/frontend-form.js', array(
					'jquery'
				) );
			wp_localize_script( 'user-form', 'user_form', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'error_message' => __( 'Please fix the errors to proceed', 'frontend_user_pro' ),
				'nonce' => wp_create_nonce( 'user_nonce' ),
				'avatar_title' =>  __( 'Choose an avatar', 'frontend_user_pro' ),
				'avatar_button' =>  __( 'Select as avatar', 'frontend_user_pro' ),
				'file_title' =>  __( 'Choose a file', 'frontend_user_pro' ),
				'file_button' =>  __( 'Insert file URL', 'frontend_user_pro' ),
				'feat_title' =>  __( 'Choose a featured image', 'frontend_user_pro' ),
				'feat_button' =>  __( 'Select as featured image', 'frontend_user_pro' ),
				'one_option' => __( 'You must have at least one option', 'frontend_user_pro' ),
				'too_many_files_pt_1' => __( 'You may not add more than ', 'frontend_user_pro' ),
				'too_many_files_pt_2' => __( ' files!', 'frontend_user_pro' )
			) );
			wp_enqueue_script( 'comment-reply' );
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'jquery-ui-autocomplete' );
			wp_enqueue_script( 'suggest' );
			wp_enqueue_script( 'jquery-ui-slider' );
			wp_enqueue_script( 'jquery-ui-timepicker', user_plugin_url . 'assets/js/jquery-ui-timepicker-addon.js', array(
					'jquery-ui-datepicker'
				) );
		}
	}

	public function admin_enqueue_styles() {
		if ( !is_admin() ) {
			return;
		}

		$current_screen = get_current_screen(); 
		$in_array = array( 'user-forms', 'user_logins', 'user_registrations' ); 

		if ( in_array( $current_screen->post_type, $in_array ) || $current_screen->post_type === 'download' || $current_screen->base === 'frontend-user_page_user-users' ) {
			if ( in_array( $current_screen->post_type, $in_array ) ) {
				wp_enqueue_style( 'user-formbuilder', user_plugin_url . 'assets/css/formbuilder.css' );
			}

			if ( $current_screen->post_type === 'download' ) {
				$minify = apply_filters('user_output_minified_versions','.min' );
				wp_enqueue_style( 'user-css', user_plugin_url . 'assets/css/frontend' . $minify . '.css' );
			}
			if ( $current_screen->base === 'frontend-user_page_user-users' ) {
				wp_enqueue_style( 'user-css', user_plugin_url . 'assets/css/frontend.css' );
			}
			wp_enqueue_style( 'user-admin-css', user_plugin_url . 'assets/css/admin.css' );
			wp_enqueue_style( 'jquery-smallipop', user_plugin_url . 'assets/css/jquery.smallipop.css' );
			wp_enqueue_style( 'jquery-ui-core', user_plugin_url . 'assets/css/jquery-ui-1.9.1.custom.css' );
		}
	}

	public function user_add_below_system_info() {
		echo "\n\n".__('Notice: USER is installed. Consider including USER\'s debug information from USER -> System Info if this is an USER bug ticket.','frontend_user_pro')."\n\n";
	}

	public function user_version() {
		// Newline on both sides to avoid being in a blob
		echo '<meta name="generator" content="FRONTEND USER v' . user_plugin_version . '" />' . "\n";
	}

	public function admin_head() {
	?>
	<style>
	@charset "UTF-8";

	[data-icon]:before {
		font-family: "user" !important;
		content: attr(data-icon);
		font-style: normal !important;
		font-weight: normal !important;
		font-variant: normal !important;
		text-transform: none !important;
		speak: none;
		line-height: 1;
		-webkit-font-smoothing: antialiased;
		-moz-osx-font-smoothing: grayscale;
	}

	[class^="icon-"]:before,
	[class*=" icon-"]:before {
		font-family: "user" !important;
		font-style: normal !important;
		font-weight: normal !important;
		font-variant: normal !important;
		text-transform: none !important;
		speak: none;
		line-height: 1;
		-webkit-font-smoothing: antialiased;
		-moz-osx-font-smoothing: grayscale;
	}
	#adminmenu #toplevel_page_user-about .menu-icon-generic div.wp-menu-image:before {
		content: "\f307";
	}
	</style>
	<?php
	}

	public function frontend_lockup_uploaded() {
		if ( is_admin() ) {
			return;
		}
		?>
	    <script type="text/javascript">
	    jQuery(document).on("DOMNodeInserted", function(){
	        // Lock uploads to "Uploaded to this post"
	        jQuery('select.attachment-filters [value="uploaded"]').attr( 'selected', true ).parent().trigger('change');
	    });
		</script>
		<?php
	}

	// removes URL tab in image upload for post
	public function remove_media_library_tab( $tabs ) {
		if ( is_admin() ) {
			return $tabs;
		}
		if ( !current_user_can( 'user_is_admin' ) ) {
				unset( $tabs[ 'library' ] );
				unset( $tabs[ 'gallery' ] );
				unset( $tabs[ 'type' ] );
				unset( $tabs[ 'type_url' ] );
				return $tabs;
		} else {
			return $tabs;
		}
	}

	// Prevents users from seeing media files that aren't theirs
	public function restrict_media( $wp_query ) {
		if ( is_admin() ) {
			if ( ! current_user_can( 'user_is_admin' ) && $wp_query->get( 'post_type' ) == 'attachment' ) {
				$wp_query->set( 'author', get_current_user_id() );
			}
		}
	}

	public function register_post_type() {
		$capability = 'manage_options';
		register_post_type( 'user_forms', array(
			'label' => __( 'Forms', 'frontend_user_pro' ),
			'public' => false,
			'show_ui' => true,
			'show_in_menu' => 'wpuf-admin-opt',
			'capability_type' => 'post',
			'capabilities' => array(
				'publish_posts' => $capability,
				'edit_posts' => $capability,
				'edit_others_posts' => $capability,
				'delete_posts' => $capability,
				'delete_others_posts' => $capability,
				'read_private_posts' => $capability,
				'edit_post' => $capability,
				'delete_post' => $capability,
				'read_post' => $capability,
				),
			'hierarchical' => false,
			'query_var' => false,
			'supports' => array('title'),
			'labels' => array(
				'name' => __( 'Forms', 'frontend_user_pro' ),
				'singular_name' => __( 'Form', 'frontend_user_pro' ),
				'menu_name' => __( 'Forms', 'frontend_user_pro' ),
				'add_new' => __( 'Add Form', 'frontend_user_pro' ),
				'add_new_item' => __( 'Add New Form', 'frontend_user_pro' ),
				'edit' => __( 'Edit', 'frontend_user_pro' ),
				'edit_item' => __( 'Edit Form', 'frontend_user_pro' ),
				'new_item' => __( 'New Form', 'frontend_user_pro' ),
				'view' => __( 'View Form', 'frontend_user_pro' ),
				'view_item' => __( 'View Form', 'frontend_user_pro' ),
				'search_items' => __( 'Search Form', 'frontend_user_pro' ),
				'not_found' => __( 'No Form Found', 'frontend_user_pro' ),
				'not_found_in_trash' => __( 'No Form Found in Trash', 'frontend_user_pro' ),
				'parent' => __( 'Parent Form', 'frontend_user_pro' ),
				),

			) );
		register_post_type( 'user_logins', array(
			'label' => __( 'Login Forms', 'frontend_user_pro' ),
			'public' => false,
			'rewrites' => false,
			'show_ui' => true,
			'show_in_menu' => 'wpuf-admin-opt',
			'capability_type' => 'post',
			'capabilities' => array(
				'publish_posts' => $capability,
				'edit_posts' => $capability,
				'edit_others_posts' => $capability,
				'delete_posts' => $capability,
				'delete_others_posts' => $capability,
				'read_private_posts' => $capability,
				'edit_post' => $capability,
				'delete_post' => $capability,
				'read_post' => $capability,
				),
			'hierarchical' => false,
			'query_var' => false,
			'supports' => array('title'),
			'can_export'		=> true,
			'labels' => array(
				'name' => __( 'Login Forms', 'frontend_user_pro' ),
				'singular_name' => __( 'Login Form', 'frontend_user_pro' ),
				'menu_name' => __( 'Login Forms', 'frontend_user_pro' ),
				'add_new' => __( 'Add Login Form', 'frontend_user_pro' ),
				'add_new_item' => __( 'Add New Login Form', 'frontend_user_pro' ),
				'edit' => __( 'Edit', 'frontend_user_pro' ),
				'edit_item' => '',
				'new_item' => __( 'New USER Login Form', 'frontend_user_pro' ),
				'view' => __( 'View USER Login Form', 'frontend_user_pro' ),
				'view_item' => __( 'View USER Login Form', 'frontend_user_pro' ),
				'search_items' => __( 'Search USER Login Forms', 'frontend_user_pro' ),
				'not_found' => __( 'No USER Login Forms Found', 'frontend_user_pro' ),
				'not_found_in_trash' => __( 'No USER Login Forms Found in Trash', 'frontend_user_pro' ),
				'parent' => __( 'Parent USER Login Form', 'frontend_user_pro' )
				)
			) );

		register_post_type( 'user_registrations', array(
			'label' => __( 'Registraton  Forms', 'frontend_user_pro' ),
			'public' => false,
			'rewrites' => false,
			'show_ui' => true,
			'show_in_menu' => 'wpuf-admin-opt',
			'capability_type' => 'post',
			'capabilities' => array(
				'publish_posts' => $capability,
				'edit_posts' => $capability,
				'edit_others_posts' => $capability,
				'delete_posts' => $capability,
				'delete_others_posts' => $capability,
				'read_private_posts' => $capability,
				'edit_post' => $capability,
				'delete_post' => $capability,
				'read_post' => $capability,
				),
			'hierarchical' => false,
			'query_var' => false,
			'supports' => array('title'),
			'labels' => array(
				'name' => __( 'Registraton Forms', 'frontend_user_pro' ),
				'singular_name' => __( 'Registraton Form', 'frontend_user_pro' ),
				'menu_name' => __( 'Registration Forms', 'frontend_user_pro' ),
				'add_new' => __( 'Add Registraton Form', 'frontend_user_pro' ),
				'add_new_item' => __( 'Add New Registraton Form', 'frontend_user_pro' ),
				'edit' => __( 'Edit', 'frontend_user_pro' ),
				'edit_item' => __( 'Edit Registraton Form', 'frontend_user_pro' ),
				'new_item' => __( 'New Registraton Form', 'frontend_user_pro' ),
				'view' => __( 'View Registraton Form', 'frontend_user_pro' ),
				'view_item' => __( 'View Registraton Form', 'frontend_user_pro' ),
				'search_items' => __( 'Search Registraton Form', 'frontend_user_pro' ),
				'not_found' => __( 'No Registraton Form Found', 'frontend_user_pro' ),
				'not_found_in_trash' => __( 'No Registraton Form Found in Trash', 'frontend_user_pro' ),
				'parent' => __( 'Parent Registraton Form', 'frontend_user_pro' ),
				),
			) );
	}

	function user_frontend_form_admin_column_value ( $column_name, $post_id ) {

		switch ($column_name) {
			case 'shortcode':
			printf( 'Form: [wpfeup-add-form id="%d"]<br>', $post_id );                
			break;            
		}
	}

	function user_login_admin_column_value ( $column_name, $post_id ) {

        switch ($column_name) {
            case 'shortcode':
                printf( 'Login: [wpfeup-login id="%d"]<br>', $post_id );
                //printf( 'Edit Profile: [user_registration_form type="profile" id="%d"]', $post_id );
                break;            
        }
    } 

}
