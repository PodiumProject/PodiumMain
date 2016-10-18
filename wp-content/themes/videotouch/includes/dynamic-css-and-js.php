<?php
function videotouch_admin_enqueue_scripts($hook) {

	if ( 'upload.php' === $hook ) {
	        return;
	}
	
	global $wp_scripts;

	$page_get = '';

	if ( isset($_GET['page']) ) {
		$page_get = $_GET['page'];
	}

	$page_post = '';

	if ( isset($_POST['page']) ) {
		$page_post = $_POST['page'];
	}
	$page_tab = '';

	if ( isset($_GET['tab']) ) {
		$page_tab = $_GET['tab'];
	}

	// News from TouchSize
	if (ts_update_redarea() === true) {
		wp_enqueue_script(
			'red-area',
			get_template_directory_uri() . '/admin/js/red.js',
			array('jquery'),
			VIDEOTOUCH_VERSION,
			true
		);
			
		$data = array('token' => wp_create_nonce("save_touchsize_news"));
		wp_localize_script( 'red-area', 'RedArea', $data );
	}

	// JS for theme settings
	$data = array(
		'LikeGenerate' => wp_create_nonce('like-generate'),
		'Nonce'        => wp_create_nonce('feature_nonce')
	);

	//if(!isset($_GET['mode']) && $_GET['mode'] === 'list'){
		wp_enqueue_script(
			'videotouch-custom',
			get_template_directory_uri() . '/admin/js/touchsize.js',
			array('jquery', 'farbtastic'),
			VIDEOTOUCH_VERSION,
			true
		);
		wp_localize_script( 'videotouch-custom', 'VideoTouchAdmin', $data );

		wp_enqueue_media();
	//}

	
	if (@$page_get == 'videotouch' || @$page_get == 'templates') {
	
		// color picker
		wp_enqueue_style( 'farbtastic' );
	}
	
	if ( @$page_get === 'videotouch' && ( @$page_tab === 'typography' || @$page_tab === 'styles' ) ) {
		
		wp_enqueue_script(
			'videotouch-google-fonts',
			get_template_directory_uri() . '/admin/js/google-fonts.js',
			array(),
			VIDEOTOUCH_VERSION,
			false
		);

		$t = get_option('videotouch_typography');

		$data = array(
			'google_fonts_key' => @$t['google_fonts_key']
		);

		wp_localize_script( 'videotouch-google-fonts', 'VideoTouch', $data );
	}

	wp_enqueue_script(
		'bootrastrap-js',
		get_template_directory_uri() . '/admin/js/modal.js',
		array('jquery'),
		VIDEOTOUCH_VERSION,
		false
	);

	wp_enqueue_style(
		'bootstrap-css',
		get_template_directory_uri() . '/admin/css/modal.css',
		array(),
		VIDEOTOUCH_VERSION
	);

	wp_enqueue_script(
		'select2-js',
		get_template_directory_uri() . '/admin/js/select2.min.js',
		array('jquery'),
		VIDEOTOUCH_VERSION,
		false
	);
	
	wp_enqueue_style(
		'select2-css',
		get_template_directory_uri() . '/admin/css/select2.css',
		array(),
		VIDEOTOUCH_VERSION
	);
	
	wp_enqueue_script(
		'bootstrap-admin-js',
		get_template_directory_uri() . '/js/bootstrap.js',
		false,
		VIDEOTOUCH_VERSION,
		true
	);

	wp_enqueue_script(
		'ui-js',
		get_template_directory_uri() . '/admin/js/jquery-ui.min.js',
		array('jquery'),
		VIDEOTOUCH_VERSION,
		false
	);

	wp_enqueue_style(
		'pips-css',
		get_template_directory_uri() . '/admin/css/jquery-ui.min.css',
		array(),
		VIDEOTOUCH_VERSION
	);
	
	// Theme settings
	wp_enqueue_style(
		'videotouch-admin-css',
		get_template_directory_uri().  '/admin/css/touchsize-admin.css'
	);

	// Tickbox
	wp_enqueue_script( 'thickbox' );
	wp_enqueue_style( 'thickbox' );

	// Layout builder
	if (@$page_get === 'videotouch_header' ||
		@$page_post === 'videotouch_header' ||
		@$page_get === 'videotouch_footer' ||
		@$page_post === 'videotouch_footer' ) {

		// Layout builder styles
		wp_enqueue_style(
			'jquery-ui-custom',
			get_template_directory_uri() . '/admin/css/layout-builder.css',
			array(),
			VIDEOTOUCH_VERSION
		);

		// Layout builder
		wp_enqueue_script(
			'handlebars',
			get_template_directory_uri() . '/admin/js/handlebars.js',
			array('jquery','jquery-ui-core', 'jquery-ui-sortable'),
			VIDEOTOUCH_VERSION,
			true
		);
		// Layout builder
		wp_enqueue_script(
			'layout-builder',
			get_template_directory_uri() . '/admin/js/layout-builder.js',
			array('handlebars'),
			VIDEOTOUCH_VERSION,
			true
		);
		// Noty
		wp_enqueue_script(
			'noty',
			get_template_directory_uri() . '/admin/js/noty/jquery.noty.js',
			array('jquery'),
			VIDEOTOUCH_VERSION,
			true
		);

		wp_enqueue_script('farbtastic');
		// color picker
		wp_enqueue_style( 'farbtastic' );

		// Noty layouts
		wp_enqueue_script(
			'noty-top',
			get_template_directory_uri() . '/admin/js/noty/layouts/bottomCenter.js',
			array('jquery', 'noty'),
			VIDEOTOUCH_VERSION,
			true
		);

		// Noty theme
		wp_enqueue_script(
			'noty-theme',
			get_template_directory_uri() . '/admin/js/noty/themes/default.js',
			array('jquery', 'noty', 'noty-top'),
			VIDEOTOUCH_VERSION,
			true
		);
	}
}

function videotouch_enqueue_scripts()
{
	global $wp_version;

	wp_enqueue_script('jquery');

	wp_enqueue_script(
		'jquery.html5',
		get_template_directory_uri() . '/js/html5.js',
		array('jquery'),
		VIDEOTOUCH_VERSION,
		true
	);
	
	wp_enqueue_script(
		'echo',
		get_template_directory_uri() . '/js/echo.js',
		false,
		VIDEOTOUCH_VERSION,
		true
	);

	wp_enqueue_script(
		'jquery.cookie',
		get_template_directory_uri() . '/js/jquery.cookie.js',
		false,
		VIDEOTOUCH_VERSION,
		true
	);

    if ( fields::get_options_value('videotouch_general','onepage_website') == 'Y' ) {
    	wp_enqueue_script(
	        'jquery.scrollTo',
	        get_template_directory_uri() . '/js/jquery.scrollTo-min.js',
	        false,
	        VIDEOTOUCH_VERSION,
	        true
	    );
    }

	wp_enqueue_script(
		'scripting',
		get_template_directory_uri() . '/js/scripting.js',
		false,
		VIDEOTOUCH_VERSION,
		true
	);

	// Javascript localization
	$contact_form_gen_token = wp_create_nonce("submit-contact-form");
	if ( fields::get_options_value('videotouch_styles', 'logo_url') != '' ) {
		$ts_logo_content = fields::get_options_value('videotouch_styles', 'logo_url');
	} else{
		$ts_logo_content = get_template_directory_uri() . '/images/logo.png';
	}
	$ts_logo_content_styles = '';
	if ( fields::get_options_value('videotouch_styles', 'retina_logo') == 'Y' ) {
		$ts_logo_content_width = fields::get_options_value('videotouch_styles', 'retina_width') / 2;
		$ts_logo_content_height = fields::get_options_value('videotouch_styles', 'retina_height') / 2;
		$ts_logo_content_styles = 'style="width: ' . $ts_logo_content_width . 'px;height: auto;"';
	}
	$ts_logo_content = '<a href="' . home_url() . '"><img src="' . $ts_logo_content . '" ' . $ts_logo_content_styles . ' alt="Logo" /></a>';

	if ( fields::get_options_value('videotouch_general','onepage_website') == 'Y' ) {
		$ts_onepage_layout = 'yes';
	} else{
		$ts_onepage_layout = 'no';
	}

	$singleOptions = get_option('videotouch_single_post');
	$enableJwplayer = (isset($singleOptions['default_videoplayer']) && ($singleOptions['default_videoplayer'] == 'y' || $singleOptions['default_videoplayer'] == 'n')) ? $singleOptions['default_videoplayer'] : 'n';

	$data = array(
		'contact_form_token' => $contact_form_gen_token,
		'contact_form_success' => __('Sent successfully', 'touchsize'),
		'contact_form_error' => __('Error!' , 'touchsize'),
		'ajaxurl' => admin_url('admin-ajax.php'),
		'main_color' => fields::get_options_value('videotouch_colors', 'primary_color'),
		'ts_enable_imagesloaded' => fields::get_options_value('videotouch_general', 'enable_imagesloaded'),
		'ts_logo_content' => $ts_logo_content,
		'ts_onepage_layout' => $ts_onepage_layout,
		'video_nonce' => wp_create_nonce("video_nonce"),
		'jwplayer'     => $enableJwplayer
	);

	wp_localize_script( 'scripting', 'VideoTouch', $data );

	// Enqueue styles:

	wp_enqueue_style(
		'videotouch.webfont',
		get_template_directory_uri() . '/css/redfont.css',
		array(),
		VIDEOTOUCH_VERSION
	);

	wp_enqueue_style(
		'videotouch.widgets',
		get_template_directory_uri() . '/css/widgets.css',
		array(),
		VIDEOTOUCH_VERSION
	);


	wp_enqueue_style(
		'videotouch.bootstrap',
		get_template_directory_uri() . '/css/bootstrap.css',
		array(),
		VIDEOTOUCH_VERSION
	);

	wp_enqueue_style(
		'lightbox',
		get_template_directory_uri() . '/css/prettyphoto.css',
		array( ),
		VIDEOTOUCH_VERSION
	);

	wp_enqueue_style(
		'videotouch.style',
		get_template_directory_uri() . '/css/style.css',
		array( 'videotouch.bootstrap' ),
		VIDEOTOUCH_VERSION
	);
	$ts_headings_font = fields::get_options_value('videotouch_typography', 'headings');
	$ts_headings_font = $ts_headings_font['type'];
	$ts_general_font = fields::get_options_value('videotouch_typography', 'primary_text');
	$ts_general_font = $ts_general_font['type'];
	$ts_menu_font = fields::get_options_value('videotouch_typography', 'secondary_text');
	$ts_menu_font = $ts_menu_font['type'];
	if ( $ts_headings_font === 'std' ) {
		wp_enqueue_style(
			'google.fonts1',
			'//fonts.googleapis.com/css?family=Lato:400,400italic,700,700italic,300,100&subset=latin,latin-ext',
			array(),
			VIDEOTOUCH_VERSION
		);
	}
	if ( $ts_general_font === 'std' ) {
		wp_enqueue_style(
			'google.fonts2',
			'//fonts.googleapis.com/css?family=Fira+Sans:400,400italic,700,700italic,300,100&subset=latin,latin-ext',
			array(),
			VIDEOTOUCH_VERSION
		);
	}
	if ( $ts_menu_font === 'std' ) {
		wp_enqueue_style(
			'google.fonts3',
			'//fonts.googleapis.com/css?family=Alegreya+Sans:400,400italic,700,700italic,300,100&subset=latin,latin-ext',
			array(),
			VIDEOTOUCH_VERSION
		);
	}
}

add_action( 'admin_enqueue_scripts', 'videotouch_admin_enqueue_scripts' );
add_action( 'wp_enqueue_scripts', 'videotouch_enqueue_scripts' );


function tsIncludeScripts($tsScripts = array()){

	if( empty($tsScripts) ) return;
	global $wp_scripts;

	foreach($tsScripts as $style => $registerScript){

		if( $registerScript == 'tab' ) $registerScript = 'bootstrap';
		if( $registerScript == 'image-carousel' ) $registerScript = 'sly';
		if( $registerScript == 'accordion' ) $registerScript = 'bootstrap';
		if( $registerScript == 'featured-area' ) $registerScript = 'mCustomScrollbar';
		if( $registerScript == 'user' ) $registerScript = 'bootstrap';
		if( $registerScript == 'toggle' ) $registerScript = 'bootstrap';

		if( isset($wp_scripts->in_footer) && !in_array($registerScript, $wp_scripts->in_footer) ){
			wp_enqueue_script(
				$registerScript,
				get_template_directory_uri() . '/js/'. $registerScript .'.js',
				false,
				VIDEOTOUCH_VERSION,
				true
			);
		}

	}
}
