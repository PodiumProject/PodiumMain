<?php
function videofly_admin_enqueue_scripts($hook) {

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
	if (function_exists('vdf_update_redarea') && vdf_update_redarea() === true) {
		wp_enqueue_script(
			'red-area',
			get_template_directory_uri() . '/admin/js/red.js',
			array('jquery'),
			VIDEOFLY_VERSION,
			true
		);

		$data = array('token' => wp_create_nonce("vdf_save_touchsize_news"));
		wp_localize_script( 'red-area', 'RedArea', $data );
	}

	wp_enqueue_script(
		'googlemap_api-js',
		'https://maps.googleapis.com/maps/api/js?key=AIzaSyBigTQD4E05c8Tk7XgGvJkyP8L9qnzN3ro',
		array('jquery'),
		VIDEOFLY_VERSION,
		false
	);

	// JS for theme settings
	$data = array(
		'LikeGenerate' => wp_create_nonce('like-generate'),
		'Nonce'        => wp_create_nonce('extern_request_die')
	);

	//if(!isset($_GET['mode']) && $_GET['mode'] === 'list'){
		wp_enqueue_script(
			'videofly-custom',
			get_template_directory_uri() . '/admin/js/touchsize.js',
			array('jquery', 'farbtastic'),
			VIDEOFLY_VERSION,
			true
		);
		wp_localize_script( 'videofly-custom', 'VideoflyAdmin', $data );

		wp_enqueue_media();
	//}


	if (@$page_get == 'videofly' || @$page_get == 'templates') {

		// color picker
		wp_enqueue_style( 'farbtastic' );
	}

	if ( (@$page_get === 'videofly' && ( @$page_tab === 'typography' || @$page_tab === 'styles' )) || get_post_type() == 'page' || $page_get == 'videofly_header' || $page_get == 'videofly_footer' ) {

		wp_enqueue_script(
			'videofly-google-fonts',
			get_template_directory_uri() . '/admin/js/google-fonts.js',
			array(),
			VIDEOFLY_VERSION,
			false
		);

		$t = get_option('videofly_typography');

		$data = array(
			'google_fonts_key' => @$t['google_fonts_key']
		);

		wp_localize_script( 'videofly-google-fonts', 'Videofly', $data );
	}

	if ( get_post_type() == 'video' ) {
		wp_enqueue_script(
			'bootrastrap-func',
			get_template_directory_uri() . '/js/bootstrap.js',
			array('jquery'),
			VIDEOFLY_VERSION,
			false
		);
	}

	wp_enqueue_script(
		'bootrastrap-js',
		get_template_directory_uri() . '/admin/js/modal.js',
		array('jquery'),
		VIDEOFLY_VERSION,
		false
	);

	wp_enqueue_style(
		'bootstrap-css',
		get_template_directory_uri() . '/admin/css/modal.css',
		array(),
		VIDEOFLY_VERSION
	);

	wp_enqueue_script(
		'select2-js',
		get_template_directory_uri() . '/admin/js/select2.min.js',
		array('jquery'),
		VIDEOFLY_VERSION,
		false
	);

	wp_enqueue_script(
		'ui-js',
		get_template_directory_uri() . '/admin/js/jquery-ui.min.js',
		array('jquery'),
		VIDEOFLY_VERSION,
		false
	);

	wp_enqueue_style(
		'select2-css',
		get_template_directory_uri() . '/admin/css/select2.css',
		array(),
		VIDEOFLY_VERSION
	);

	wp_enqueue_style(
		'pips-css',
		get_template_directory_uri() . '/admin/css/jquery-ui.min.css',
		array(),
		VIDEOFLY_VERSION
	);


	// Theme settings
	wp_enqueue_style(
		'videofly-admin-css',
		get_template_directory_uri().  '/admin/css/touchsize-admin.css'
	);

	// Tickbox
	wp_enqueue_script( 'thickbox' );
	wp_enqueue_style( 'thickbox' );

	// Layout builder
	if (@$page_get === 'videofly_header' ||
		@$page_post === 'videofly_header' ||
		@$page_get === 'videofly_footer' ||
		@$page_post === 'videofly_footer' || get_post_type() == 'page' ) {

		// Layout builder styles
		wp_enqueue_style(
			'jquery-ui-custom',
			get_template_directory_uri() . '/admin/css/layout-builder.css',
			array(),
			VIDEOFLY_VERSION
		);

		// Layout builder
		wp_enqueue_script(
			'handlebars',
			get_template_directory_uri() . '/admin/js/handlebars.js',
			array('jquery','jquery-ui-core', 'jquery-ui-sortable'),
			VIDEOFLY_VERSION,
			true
		);
		// Layout builder
		wp_enqueue_script(
			'layout-builder',
			get_template_directory_uri() . '/admin/js/layout-builder.js',
			array('handlebars', 'builder-elements'),
			VIDEOFLY_VERSION,
			true
		);

		wp_enqueue_script(
			'builder-elements',
			get_template_directory_uri() . '/admin/js/builder-elements.js',
			array('handlebars'),
			VIDEOFLY_VERSION,
			true
		);

		// Noty
		wp_enqueue_script(
			'noty',
			get_template_directory_uri() . '/admin/js/noty/jquery.noty.js',
			array('jquery'),
			VIDEOFLY_VERSION,
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
			VIDEOFLY_VERSION,
			true
		);

		// Noty theme
		wp_enqueue_script(
			'noty-theme',
			get_template_directory_uri() . '/admin/js/noty/themes/default.js',
			array('jquery', 'noty', 'noty-top'),
			VIDEOFLY_VERSION,
			true
		);
	}
}

function videofly_enqueue_scripts()
{
	global $wp_version;

	wp_enqueue_script('jquery');

	global $post;

	if ( is_a($post, 'WP_Post') ) {
		if( has_shortcode($post->post_content, 'toggle') || is_page_template( 'user-add-post.php' ) ){
			wp_enqueue_script(
				'bootstrap',
				get_template_directory_uri() . '/js/bootstrap.js',
				array('jquery','scripting'),
				VIDEOFLY_VERSION,
				true
			);

			if ( is_page_template( 'user-add-post.php' ) ) {
				wp_enqueue_script(
					'bootstrap-select',
					get_template_directory_uri() . '/js/bootstrap-select.js',
					array('jquery','scripting'),
					VIDEOFLY_VERSION,
					true
				);

				wp_enqueue_style(
					'bootstrap-select',
					get_template_directory_uri() . '/css/bootstrap-select.css',
					array( 'videofly.bootstrap' ),
					VIDEOFLY_VERSION
				);
			}
		}

		if ( $post->post_type == 'video' ) {
			wp_enqueue_script(
				'vdf-videoplayer',
				get_template_directory_uri() . '/js/videoplayer.js',
				array('jquery', 'scripting'),
				VIDEOFLY_VERSION,
				true
			);

			wp_enqueue_style(
				'vdf-videoplayer',
				get_template_directory_uri() . '/css/videoplayer.css',
				array(),
				VIDEOFLY_VERSION
			);
		}
	}

	if ( get_post_type() == 'video' ) {
		wp_enqueue_script(
			'bootrastrap-func',
			get_template_directory_uri() . '/js/bootstrap.js',
			array('jquery'),
			VIDEOFLY_VERSION,
			false
		);
	}

	$optionsGeneral = get_option('videofly_general');
	$lazyload = isset($optionsGeneral['enable_imagesloaded']) ? $optionsGeneral['enable_imagesloaded'] : 'N';
	$onePageWebsite = (isset($optionsGeneral['onepage_website']) && ($optionsGeneral['onepage_website'] == 'Y' || $optionsGeneral['onepage_website'] == 'N')) ? $optionsGeneral['onepage_website'] : 'N';
	$enablePreloader = (isset($optionsGeneral['enable_preloader']) && ($optionsGeneral['enable_preloader'] == 'Y' || $optionsGeneral['enable_preloader'] == 'N')) ? $optionsGeneral['enable_preloader'] : 'N';

	wp_enqueue_script(
		'jquery.html5',
		get_template_directory_uri() . '/js/html5.js',
		array('jquery'),
		VIDEOFLY_VERSION,
		true
	);

	if( $lazyload == 'Y' ){
		wp_enqueue_script(
			'lazyload',
			get_template_directory_uri() . '/js/layzr.min.js',
			false,
			VIDEOFLY_VERSION,
			true
		);
	}

	wp_enqueue_script(
		'jquery.cookie',
		get_template_directory_uri() . '/js/jquery.cookie.js',
		false,
		VIDEOFLY_VERSION,
		true
	);

    if ( $onePageWebsite == 'Y' ) {
    	wp_enqueue_script(
	        'jquery.scrollTo',
	        get_template_directory_uri() . '/js/jquery.scrollTo-min.js',
	        false,
	        VIDEOFLY_VERSION,
	        true
	    );
    }

    if( $enablePreloader == 'Y' ){
		wp_enqueue_script(
			'nprogress',
			get_template_directory_uri() . '/js/nprogress.js',
			false,
			VIDEOFLY_VERSION,
			true
		);
	}

	wp_enqueue_script(
		'scripting',
		get_template_directory_uri() . '/js/scripting.js',
		false,
		VIDEOFLY_VERSION,
		true
	);

	// Javascript localization
	$contact_form_gen_token = wp_create_nonce("submit-contact-form");
	$tsStylesOptions = get_option('videofly_styles');
	$tsLogoStyle = (isset($tsStylesOptions['logo'])) ? $tsStylesOptions['logo'] : '';
	$rightClick = (isset($optionsGeneral['right_click']) && ($optionsGeneral['right_click'] == 'y' || $optionsGeneral['right_click'] == 'n')) ? $optionsGeneral['right_click'] : 'n';

	if( isset($tsLogoStyle['type']) && $tsLogoStyle['type'] == 'image' ){
		if ( $tsLogoStyle['image_url'] != '' ) {
			$vdf_logo_content = esc_url($tsLogoStyle['image_url']);
		} else{
			$vdf_logo_content = get_template_directory_uri() . '/images/logo.png';
		}
		$vdf_logo_content_styles = '';
		if ( $tsLogoStyle['retina_logo'] == 'Y' ) {
			$vdf_logo_content_width = $tsLogoStyle['retina_width'] / 2;
			$vdf_logo_content_styles = 'style="width: ' . $vdf_logo_content_width . 'px;height: auto;"';
		}
		$vdf_logo_content = '<a href="' . esc_url( home_url('/') ) . '"><img src="' . $vdf_logo_content . '" ' . $vdf_logo_content_styles . ' alt="Logo" /></a>';
	}else{
		$vdf_logo_content = 	'<a href="' . esc_url( home_url('/') ) . '" class="logo">
								' . vdf_get_logo() . '
							</a>';
	}

	$menuAnimationIn = (isset($tsStylesOptions['effect_in_general']) && !empty($tsStylesOptions['effect_in_general'])) ? esc_js($tsStylesOptions['effect_in_general']) : 'none';
	$menuAnimationOut = (isset($tsStylesOptions['effect_out_general']) && !empty($tsStylesOptions['effect_out_general'])) ? esc_js($tsStylesOptions['effect_out_general']) : 'none';

	if ( fields::get_options_value('videofly_general', 'onepage_website') == 'Y' ) {
		$vdf_onepage_layout = 'yes';
	} else{
		$vdf_onepage_layout = 'no';
	}

	$data = array(
		'contact_form_token' => $contact_form_gen_token,
		'contact_form_success' => esc_html__('Sent successfully', 'videofly'),
		'contact_form_error' => esc_html__('Error!' , 'videofly'),
		'ajaxurl' => esc_url( admin_url('admin-ajax.php') ),
		'main_color' => fields::get_options_value('videofly_colors', 'primary_color'),
		'ts_enable_imagesloaded' => fields::get_options_value('videofly_general', 'enable_imagesloaded'),
		'ts_logo_content' => $vdf_logo_content,
		'ts_onepage_layout' => $vdf_onepage_layout,
		'video_nonce' => wp_create_nonce("video_nonce"),
		'ts_security' => wp_create_nonce( 'security' ),
		'rightClick' => $rightClick,
		'animsitionIn' => $menuAnimationIn,
		'animsitionOut' => $menuAnimationOut
	);

	wp_localize_script( 'scripting', 'Videofly', $data );

    if ( $menuAnimationIn !== 'none' || $menuAnimationOut !== 'none' ) {
    	wp_enqueue_script(
	        'animsition',
	        get_template_directory_uri() . '/js/animsition.js',
	        false,
	        VIDEOFLY_VERSION,
	        true
	    );

	    wp_enqueue_style(
	    	'animsition-css',
	    	get_template_directory_uri() . '/css/animsition.css',
	    	array(),
	    	VIDEOFLY_VERSION
	    );
    }

    if ( is_singular() && get_post_type( get_the_ID() ) == 'portfolio' ) {
    	wp_enqueue_script(
			'animation-js',
			get_template_directory_uri() . '/js/css3-animations.js',
			array('jquery'),
			VIDEOFLY_VERSION,
			true
		);
    }



	// Enqueue styles:

	wp_enqueue_style(
		'videofly.webfont',
		get_template_directory_uri() . '/css/redfont.css',
		array(),
		VIDEOFLY_VERSION
	);

	wp_enqueue_style(
		'videofly.widgets',
		get_template_directory_uri() . '/css/widgets.css',
		array(),
		VIDEOFLY_VERSION
	);

	wp_enqueue_style(
		'videofly.bootstrap',
		get_template_directory_uri() . '/css/bootstrap.css',
		array(),
		VIDEOFLY_VERSION
	);

	wp_enqueue_style(
		'videofly.style',
		get_template_directory_uri() . '/css/style.css',
		array( 'videofly.bootstrap' ),
		VIDEOFLY_VERSION
	);
	if ( is_singular() && get_post_type( get_the_ID() ) == 'portfolio' ) {
	    wp_enqueue_style(
			'animation-css',
			get_template_directory_uri() . '/css/css3-animations.css',
			array(),
			VIDEOFLY_VERSION
		);
	}

}

add_action( 'admin_enqueue_scripts', 'videofly_admin_enqueue_scripts' );
add_action( 'wp_enqueue_scripts', 'videofly_enqueue_scripts' );

function tsIncludeScripts($tsScripts = array()){

	if( empty($tsScripts) ) return;
	global $wp_scripts;

	foreach($tsScripts as $registerScript){

		if( $registerScript == 'image-carousel' ) $registerScript = 'sly';
		if( $registerScript == 'accordion' ) $registerScript = 'bootstrap';
		if( $registerScript == 'toggle' ) $registerScript = 'bootstrap';
		if( $registerScript == 'easyPieChart' ) $registerScript = 'pie';

		if( $registerScript == 'map' ){
			wp_enqueue_script(
				'map',
				'https://maps.googleapis.com/maps/api/js?key=AIzaSyBigTQD4E05c8Tk7XgGvJkyP8L9qnzN3ro&sensor=false&amp;libraries=places',
				false,
				VIDEOFLY_VERSION,
				true
			);
			continue;
		}

		if( $registerScript == 'css3-animations' ){
			wp_enqueue_style(
				$registerScript,
				get_template_directory_uri() . '/css/css3-animations.css',
				array(),
				VIDEOFLY_VERSION
			);

			wp_enqueue_script(
				$registerScript,
				get_template_directory_uri() . '/js/css3-animations.js',
				array('jquery'),
				VIDEOFLY_VERSION,
				true
			);
			continue;
		}

		if( isset($wp_scripts->in_footer) && !in_array($registerScript, $wp_scripts->in_footer) ){
			wp_enqueue_script(
				$registerScript,
				get_template_directory_uri() . '/js/'. $registerScript .'.js',
				false,
				VIDEOFLY_VERSION,
				true
			);
		}

	}
}