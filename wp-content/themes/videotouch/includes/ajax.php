<?php

function edit_template_element()
{
	require_once get_template_directory() . '/includes/layout-builder/views/elements/elements-editor.php';
	die();
}

add_action('wp_ajax_edit_template_element', 'edit_template_element');

function edit_template_row()
{

	require_once get_template_directory() . '/includes/layout-builder/views/elements/edit-template-row.php';
	die();
}

add_action('wp_ajax_edit_template_row', 'edit_template_row');

/**
 * Searchin posts and pages from Layout Builder
 * @return string Response in JSON format
 */
function ts_search_content()
{
	header('Content-Type: application/json');
	$result = array();

	if (isset($_POST['post_type'])) {

		$args = array();

		if (isset($_POST['search'])) {
			$args['s'] = $_POST['search'];
		} else {
			$args['s'] = '';
		}

		if ($_POST['post_type'] === 'post') {
			$args['post_type'] = 'post';
		} else if ($_POST['post_type'] === 'page') {
			$args['post_type'] = 'page';
		} else {
			echo json_encode(array());
			die();
		}

		if (isset($_POST['order_by'])) {
			switch ($_POST['order_by']) {
				case 'id':
					$args['orderby'] = 'ID';
					break;

				case 'date':
					$args['orderby'] = 'date';
					break;
			}
		}

		if (isset($_POST['direction'])) {
			switch ($_POST['direction']) {
				case 'asc':
					$args['order'] = 'ASC';
					break;

				case 'desc':
					$args['order'] = 'DESC';
					break;
			}
		}

		if (isset($_POST['criteria'])) {
			switch ($_POST['criteria']) {
				case 'title':
					add_filter( 'posts_search', 'ts_search_by_title_only', 500, 2 );
				break;
			}
		}

		$the_query = new WP_Query( $args );
		while ( $the_query->have_posts() ) :
			$the_query->the_post();
			$result[] = array( 'id' => get_the_ID(), 'title' => get_the_title() );
		endwhile;

		wp_reset_postdata();
	}

	echo json_encode($result);
	die();
}

add_action('wp_ajax_ts_search_content', 'ts_search_content');


function save_touchsize_news()
{
	check_ajax_referer( 'save_touchsize_news', 'token' );

	header('Content-Type: application/json');

	$last_update = time();
	$options = get_option('videotouch_red_area', array());

	$news = @$_POST['news'];
	$parsed_news = array();
	$allowed_html = array('a', 'br', 'em', 'strong', 'img');

	if ( is_array($news) && ! empty($news) ) {
		foreach ($news as $news_id => $n) {
			$parsed_news[] = '<li><a href="' . esc_url($n['url']) . '" target="_blank">' . wp_kses($n['title'], $allowed_html) . '</a><em>' .   $n['date'] . '</em>
				<img src="' . esc_url($n['image']) . '" /><p>' . wp_kses($n['excerpt'], $allowed_html) . '</p>
			</li>';
		}
	}

	if ( ! empty( $parsed_news ) ) {
		$parsed_news = '<ul>' . implode( "\n", $parsed_news ) . '</ul>';
	}

	$alerts = @$_POST['alerts'];

	if ( is_array( $alerts ) && ! empty( $alerts ) ) {
		if ( isset($alerts['id']) && isset($alerts['message']) ) {
			$parsed_alerts['id'] = $alerts['id'];
			$parsed_alerts['message'] = stripslashes($alerts['message']);
		} else {
			$parsed_alerts['id'] = 0;
			$parsed_alerts['message'] = '';
		}
	}

	$options['news']  = $parsed_news;
	$options['alert'] = $parsed_alerts;
	$options['time']  = time();

	if ( ! isset($options['hidden_alerts']) ) {
		$options['hidden_alerts'] = array();
	}

	update_option('videotouch_red_area', $options);

	$data = array(
		'status'  => 'ok',
		'message' => __( 'Saved', 'touchsize')
	);

	echo json_encode($data);
	die();
}

add_action('wp_ajax_save_touchsize_news', 'save_touchsize_news');

function videotouch_hide_touchsize_alert()
{
	check_ajax_referer( 'remove-videotouch-alert', 'token' );

	header('Content-Type: application/json');

	$options = get_option('videotouch_red_area', array(
		'news' => '',
		'alert' => array(
			'id' => 0,
			'message' => ''
		),
		'hidden_alerts' => array(),
		'time' => time()
	));

	$alert_id = sanitize_text_field( $_POST['alertID'] );

	if ( ! in_array( $alert_id, $options['hidden_alerts'], true ) ) {

		array_push( $options['hidden_alerts'], $alert_id );
	}

	update_option('videotouch_red_area', $options);

	$data = array(
		'status'  => 'ok',
		'message' => __( 'Saved', 'touchsize')
	);

	echo json_encode($data);
	die();
}

add_action('wp_ajax_videotouch_hide_touchsize_alert', 'videotouch_hide_touchsize_alert');

function videotouch_contact_me()
{

	check_ajax_referer( 'submit-contact-form', 'token' );

	header('Content-Type: application/json');

	$data = array(
		'status'  => 'ok',
		'message' => ''
	);

	$options = get_option( 'videotouch_social', array('email' => ''));

	$from    	  = @$_POST['from'];
	$subject 	  = @$_POST['subject'];
	$message 	  = @$_POST['message'];
	$name    	  = @$_POST['name'];
	$custom_field = (isset($_POST['custom_field']) && is_array($_POST['custom_field']) && !empty($_POST['custom_field'])) ? $_POST['custom_field'] : NULL;

	if ( $subject === '' ) {
		$subject = bloginfo('name') . __('Message from ', 'touchsize') . wp_kses( $name, array());
	}

	if ( is_email($options['email']) && is_email($from) ) {

		if( isset($custom_field) ){
			foreach($custom_field as $value){
				$message .= $value['title'] . ':' . $value['value'] . "\r\n";
				if( $value['require'] == 'y' && $value['value'] == '' ){
					$error_require = 'Mail not sent. This field "' . $value['title'] . '" is require';
					$data = array(
						'status'  => 'error',
						'message' => __($error_require, 'touchsize'),
						'token' => wp_create_nonce("submit-contact-form")
					);
					echo json_encode($data);
					die();
				}
			}
		}

		$headers = 'From: '.esc_attr($name) . ' <'.$from.'>' . "\r\n";
		$sent = wp_mail($options['email'], $subject, wp_kses($message, array()) ,$headers);

		if ( $sent ) {
			$data = array(
				'status'  => 'ok',
				'message' => __('Mail sent.', 'touchsize'),
				'token' => wp_create_nonce("submit-contact-form")
			);
		} else {
			$data = array(
				'status'  => 'error',
				'message' => __('Error. Mail not sent.', 'touchsize'),
				'token' => wp_create_nonce("submit-contact-form")
			);
		}
	} else {
		$data = array(
			'status'  => 'error',
			'message' => __('Invalid email adress', 'touchsize'),
			'token' => wp_create_nonce("submit-contact-form")
		);
	}
	echo json_encode($data);
	die();
}

add_action('wp_ajax_videotouch_contact_me', 'videotouch_contact_me');
add_action( 'wp_ajax_nopriv_videotouch_contact_me', 'videotouch_contact_me' );

//========================================================================
// Save/Edit templates ===================================================
// =======================================================================

// Load template
function videotouch_load_template()
{
	header('Content-Type: application/json');
	// check_ajax_referer( 'remove-videotouch-alert', 'token' );

	$template_id     = @$_GET['template_id'];
	$location        = @$_GET['location'];

	$result = Template::load_template($location, $template_id);
	echo json_encode($result);
	die();
}

add_action('wp_ajax_videotouch_load_template', 'videotouch_load_template');

// Save blank template
function videotouch_save_layout()
{
	// if not administrator, kill WordPress execution and provide a message
	if ( ! is_admin() ) {
		return false;
	}

	header('Content-Type: application/json');
	// check_ajax_referer( 'remove-videotouch-alert', 'token' );

	$location    = @$_POST['location'];
	$mode		 = @$_POST['mode'];

	$data = array('status' => 'ok', 'message' => '');
	$response = Template::save($mode, $location);

	if ( ! $response ) {
		$data['status'] = 'error';
		$data['message'] = __("Cannot save this template", 'touchsize');
	}

	echo json_encode($data);
	die();
}

add_action('wp_ajax_videotouch_save_layout', 'videotouch_save_layout');

// Remove template
function videotouch_remove_template()
{
	// if not administrator, kill WordPress execution and provide a message
	if ( ! current_user_can( 'manage_options' ) ) {
		return false;
	}

	header('Content-Type: application/json');
	// check_ajax_referer( 'remove-videotouch-alert', 'token' );

	$template_id = @$_POST['template_id'];
	$location    = @$_POST['location'];

	$result = Template::delete( $location, $template_id );

	if ( $result ) {

		$data = array(
			'status' => 'removed',
			'message' => ''
		);

	} else {

		$data = array(
			'status' => 'error',
			'message' => __("Cannot delete this template", 'touchsize')
		);
	}

	echo json_encode($data);
	die();
}

add_action('wp_ajax_videotouch_remove_template', 'videotouch_remove_template');

function videotouch_load_all_templates()
{
	$location = @$_POST['location'];
	$templates = Template::get_all_templates($location);

	$edit = '';
	if ( $templates ) {
		foreach ($templates as $template_id => $template) {

			$remove_template = '';

			if ( $template_id !== 'default' ) {
				$remove_template = '<a href="#" data-template-id="'.esc_attr($template_id) .'" data-location="'.esc_attr($location).'" class="ts-remove-template icon-delete">' . __('remove', 'touchsize') . '</a>';
			}

			$edit .= '<tr>
				<td><input type="radio" name="template_id" value="'.esc_attr($template_id).'" id="'.esc_attr($template_id).'"/></td>
				<td>
					<label for="'.$template_id . '">' . $template['name'] . '
					</label>
				</td>
				<td>
					' . $remove_template . '
				</td>
			</tr>';
		}
	}

	echo $edit;
	die();
}

add_action('wp_ajax_videotouch_load_all_templates', 'videotouch_load_all_templates');


function ts_updateFeatures(){
    $nonce = $_POST['nonce_featured'];

    if ( !wp_verify_nonce( $nonce, 'feature_nonce' ) ) return false;
    if ( !current_user_can( 'manage_options' ) ) return false;

    $id_post = (isset($_POST['value_checkbox']) && (int)$_POST['value_checkbox'] !== 0) ? (int)$_POST['value_checkbox'] : NULL;
    $value_checkbox = (isset($_POST['checked']) && $_POST['checked'] !== '' && ($_POST['checked'] == 'yes' || $_POST['checked'] == 'no')) ? $_POST['checked'] : 'no';

    if( isset($id_post) ){
       update_post_meta($id_post, 'featured', $value_checkbox);
    }

    die();
}

if( is_admin() ) {
    add_action('wp_ajax_ts_updateFeatures', 'ts_updateFeatures');
    add_action('wp_ajax_nopriv_ts_updateFeatures', 'ts_updateFeatures');
}

//get video in modal by ajax
add_action('wp_ajax_ts_get_video_modal', 'ts_get_video_modal_callback');
add_action('wp_ajax_nopriv_ts_get_video_modal', 'ts_get_video_modal_callback');

function ts_get_video_modal_callback(){
    $nonce = $_POST['nonce_video'];

    if ( !wp_verify_nonce( $nonce, 'video_nonce' ) ) return false;

    if( isset($_POST) && !empty($_POST) && isset($_POST['post_id']) && (int)$_POST['post_id'] !== 0 ){
        $post_id = (int)$_POST['post_id'];
        $post_type = get_post_type($post_id);
    }
    if( isset($post_id, $post_type) && $post_type == 'video' ){

        $video_url = get_post_meta($post_id, 'video', TRUE);
        $post = get_post($post_id);

        if( isset($video_url) && !empty($video_url) && is_array($video_url) && isset($post) && is_object($post) && !empty($post) ){

            if( isset($video_url['extern_url'], $video_url['your_url'], $video_url['embed']) ){
                $video = '';

                if( isset($video_url['extern_url'], $video_url['your_url'], $video_url['embed']) && empty($video_url['embed']) && empty($video_url['extern_url']) && !empty($video_url['your_url']) ){

                    $atts = array(
                        'src'      => esc_url($video_url['your_url']),
                        'poster'   => '',
                        'loop'     => '',
                        'autoplay' => '',
                        'preload'  => 'metadata',
                        'height'   => 560,
                        'width'    => 1380,
                    );
                    $video = wp_video_shortcode($atts);
                }
                // Get the date
                if (ts_human_type_date_format()) {
                    $article_date = human_time_diff(strtotime(get_the_time('Y-m-d H:i:s', $post->ID))).' '.__('ago', 'touchsize');
                } else {
                    $article_date =  get_the_date();
                }
                ?>

                <div class="featured-image">
                    <?php if( (isset($video_url['embed']) && !empty($video_url['embed'])) || (isset($video_url['your_url']) && !empty($video_url['your_url'])) || (isset($video_url['extern_url']) && !empty($video_url['extern_url'])) ) : ?>
                        <div class="embedded_videos">
                            <div class="video-container">
                                <div id="videoframe" class="video-frame">
                                    <?php if( !empty($video_url['your_url']) ) : ?>
                                        <div id="post-video">
                                            <?php echo $video; ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if( !empty($video_url['embed']) ) : ?>
                                    	<?php echo $video_url['embed']; ?>
                                	<?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php else : ?>
                        <?php _e('no video', 'touchsize'); ?>
                    <?php endif; ?>
                </div>
                <article>
                    <header class="post-header">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-12">
                                <h1 class="post-title video-title"><?php echo esc_attr($post->post_title); ?></h1>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="post-meta">
                                    <?php touchsize_likes($post->ID, '<div class="post-meta-likes"><i class="icon-like"></i><span class="likes-count">', '</span></div>'); ?>
                                    <div class="post-meta-views">
                                        <span class="views-count"><?php ts_get_views($post->ID); ?></span>
                                        <small><?php _e('views','touchsize') ?></small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 post-author-block">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <a href="<?php echo get_author_posts_url($post->post_author); ?>" class="author-avatar"><?php echo get_avatar($post->post_author, 50); ?></a>
                                        <ul>
                                            <li class="author-name"><?php _e('by ','touchsize') ?><a href="<?php echo get_author_posts_url($post->post_author); ?>"><?php echo get_the_author_meta('user_nicename', $post->post_author); ?></a></li>
                                            <li class="author-published"><?php _e('Published ','touchsize') ?><span><?php echo $article_date; ?></span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </header>
                </article>
    <?php   }
        }
    }

    die();
}//end function for get video modal

//function generate random likes for all posts
function ts_generate_like_callback(){

    check_ajax_referer( 'like-generate', 'nonce' );
    if ( !current_user_can( 'manage_options' ) ) return false;

    global $wpdb;
    $sql="SELECT ID FROM $wpdb->posts";

    $posts = $wpdb->get_results($sql, ARRAY_N);

    if( isset($posts) && is_array($posts) && !empty($posts) ){
        foreach($posts as $id){
        	$rand_likes = rand(50, 100);
        	$rand_view  = rand(2, 5);
            update_post_meta($id[0], '_touchsize_likes', $rand_likes);
            update_post_meta($id[0], 'ts_article_views', $rand_likes * $rand_view);
        }
        echo '1';
    }
    die();
}

add_action('wp_ajax_ts_generate_like', 'ts_generate_like_callback');

//function generate the pagination read more
function ts_pagination_callback(){

    if( isset($_POST['action'], $_POST['args'], $_POST['paginationNonce'], $_POST['loop']) ){

        check_ajax_referer('pagination-read-more', 'paginationNonce');
        $args = ts_base_64($_POST['args'], 'decode');
        $loop = (is_numeric($_POST['loop'])) ? (int)$_POST['loop'] : '';

        if( is_array($args) ){

            if(isset($args['options']) && is_array($args['options'])){
                $options = $args['options'];
                unset($args['options']);
            }

            if( isset($options) && is_array($options) ){

                $offset = (isset($args['offset'])) ? (int)$args['offset'] : 0;

                if(isset($args['posts_per_page'])){
                    if( $args['posts_per_page'] === 0 ){
                        $args['posts_per_page'] = get_option('posts_per_page');
                    }

                    if( $loop > 0 ){
                        $args['offset'] = $offset + ((int)$args['posts_per_page'] * $loop);
                    }

                    if( $loop === 0){
                        $args['offset'] = $offset + (int)$args['posts_per_page'];
                    }

                }

            	$args['post_status'] = 'publish';

                if( isset($args['post_type']) && $args['post_type'] === 'video' ){
                    $options['ajax-load-more'] = true;
                    $query = new WP_Query($args);
                    if ( $query->have_posts() ) {
                        echo LayoutCompilator::list_videos_element($options, $query);
                    }else{
                        return false;
                    }
                }

                if( isset($args['post_type']) && $args['post_type'] === 'post' || $args['post_type'] === 'portfolio' ){
                    $options['ajax-load-more'] = true;
                    $query = new WP_Query($args);
                    if ( $query->have_posts() ) {
                        echo LayoutCompilator::last_posts_element($options, $query);
                    }else{
                        return false;
                    }
                }
            }
        }
    }
    die();
}
add_action('wp_ajax_ts_pagination', 'ts_pagination_callback');
add_action('wp_ajax_nopriv_ts_pagination', 'ts_pagination_callback');

function ts_video_image_callback(){
	check_ajax_referer('video-image', 'nonce');
	$post_id = (isset($_POST['post_id']) && (int)$_POST['post_id'] !== 0) ? (int)$_POST['post_id'] : NULL;
	$video_url = (isset($_POST['link'])) ? esc_url($_POST['link']) : '';

	if( isset($post_id) && $video_url !== '' ):

     	$video_id = '';
     	if( strlen(trim($video_url)) > 0 ):
           	if( strpos($video_url, 'vimeo') > 0 ) {
               	$video_id = str_replace(array('http://vimeo.com/', 'https://vimeo.com/'), '', $video_url);
               	$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$video_id.php"));
               	$video_thumbnail = $hash[0]['thumbnail_large'];

           	}elseif( strpos($video_url, 'youtube' ) > 0 ) {
               	parse_str(parse_url($video_url, PHP_URL_QUERY));
               	$video_id = $v;
               	$video_thumbnail = 'http://img.youtube.com/vi/' . $video_id . '/maxresdefault.jpg';
               	$headers = get_headers($video_thumbnail);
               	if (substr($headers[0], 9, 3) == '404') {
           	        $video_thumbnail = 'http://img.youtube.com/vi/' . $video_id . '/0.jpg';
           	    }
           	}elseif( strpos($video_url, 'dailymotion' ) > 0 ) {
           		$video_id = strtok(basename($video_url), '_');
               	$video_thumbnail =  "https://api.dailymotion.com/video/$video_id?fields=thumbnail_large_url";
               	$resp = wp_remote_get($video_thumbnail, array('sslverify' => false));
               	$response = wp_remote_retrieve_body($resp);
               	$result = json_decode($response);
				$video_thumbnail = $result->thumbnail_large_url;
           	} else {
               	return;
           	}
     	else:
           return;
     	endif;
     	delete_post_meta( $post_id, '_thumbnail_id' );
     	media_sideload_image($video_thumbnail, $post_id, get_the_title($post_id));
     	$attachments = get_posts(
           	array(
	           	'post_type'   =>'attachment',
	           	'numberposts' => 1,
	           	'order'       => 'DESC'
     		));

     	$attachment = isset($attachments[0]) ? $attachments[0] : '';

     	set_post_thumbnail($post_id, $attachment->ID);
     	echo $video_thumbnail;
	endif;

	die();
}
add_action('wp_ajax_ts_video_image', 'ts_video_image_callback');

function ts_set_video_thumbnail ($content) {
    if( has_post_format('video') && !has_post_thumbnail() ):
    	$post_id = get_the_ID();

     	if( get_post_meta($post_id, '_format_video_embed', true) ) {
         	$video_url = get_post_meta($post_id, '_format_video_embed', true);
     	} else {
         	$urls = array();
         	preg_match_all('#bhttps?://[^s()&amp;lt;&amp;gt;]+(?:([wd]+)|([^[:punct:]s]|/))#', $content, $urls);
         	$video_url = isset( $urls[0][0] ) ? $urls[0][0] : '';
     	}

     	$video_id = '';
     	if( strlen(trim($video_url)) > 0 ):
           	if( strpos($video_url, 'vimeo') > 0 ) {
               	$video_id = str_replace( 'http://vimeo.com/', '', $video_url );
               	$hash = unserialize(file_get_contents('http://vimeo.com/api/v2/video/$video_id.php'));
               	$video_thumbnail = $hash[0]['thumbnail_large'];

           	} elseif( strpos($video_url, 'youtube' ) > 0 ) {
               parse_str( parse_url( $video_url, PHP_URL_QUERY ) );
               $video_id = $v;
               $video_thumbnail = 'http://img.youtube.com/vi/' . $youtube_id . '/maxresdefault.jpg';
           	} else {
               	return;
           	}
     	else:
           return;
     	endif;
     	media_sideload_image($video_thumbnail, $post_id, get_the_title($post_id) );
     	$attachments = get_posts(
           	array(
	           	'post_type'   =>'attachment',
	           	'numberposts' => 1,
	           	'order'       => 'ASC',
	           	'post_parent' => $post_id
     		));

     	$attachment = isset($attachments[0]) ? $attachments[0] : '';
     	set_post_thumbnail($post_id, $attachment->ID);
	endif;
}
add_action('save_post', 'ts_set_video_thumbnail');

/*add_action( 'wp_ajax_ts_import', 'ts_import_callback' );
function ts_import_callback()
{
    global $wpdb;

    if ( !defined('WP_LOAD_IMPORTERS') ) define('WP_LOAD_IMPORTERS', true);

    // Load Importer API
    require_once ABSPATH . 'wp-admin/includes/import.php';

    if ( ! class_exists( 'WP_Importer' ) ) {
        $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
        if ( file_exists( $class_wp_importer ) )
        {
            require $class_wp_importer;
        }
    }

    if ( ! class_exists( 'WP_Import' ) ) {
        $class_wp_importer = get_template_directory() ."/import-data/wordpress-importer.php";
        if ( file_exists( $class_wp_importer ) )
            require $class_wp_importer;
    }


    if ( class_exists( 'WP_Import' ) )
    {
        $import_filepath = get_template_directory() ."/import-data/demo.xml" ;

        $wp_import = new WP_Import();
        $wp_import->fetch_attachments = true;
        $wp_import->import($import_filepath);

    }
        die();
}*/

function tsSetViewClickPreroll(){
    check_ajax_referer('video_nonce', 'ts_security');
    $prerollId = (isset($_POST['prerollId']) && (int)$_POST['prerollId'] > 0) ? $_POST['prerollId'] : '';
    $updateField = (isset($_POST['field']) && ($_POST['field'] == 'view' || $_POST['field'] == 'click')) ? $_POST['field'] : '';

    if( !empty($prerollId) && !empty($updateField) ){
        $options = get_option('videotouch_theme_advertising');
        $prerolls = (isset($options['pre_roll']) && is_array($options['pre_roll']) && !empty($options['pre_roll'])) ? $options['pre_roll'] : '';

        if( !empty($prerolls) ){
            foreach($prerolls as $id => $option){
                if( $id == $prerollId ){
                    if( $updateField == 'view' ){
                        $options['pre_roll'][$id]['views'] = (int)$option['views'] + 1;
                    }else{
                        $options['pre_roll'][$id]['clicks'] = (int)$option['clicks'] + 1;
                    }
                    break;
                }
            }
            update_option('videotouch_theme_advertising', $options);
        }
    }

    die();
}
add_action('wp_ajax_tsSetViewClickPreroll', 'tsSetViewClickPreroll');
add_action('wp_ajax_nopriv_tsSetViewClickPreroll', 'tsSetViewClickPreroll');

function ts_login()
{
	check_ajax_referer('video_nonce', 'nonce');

	if ( ! isset( $_POST['username'] ) || empty( $_POST['username'] ) || ! isset( $_POST['password'] ) || empty( $_POST['password'] ) ) {
		echo __( 'One of the fields is empty', 'videotouch' );
		die();
	}

	$creds = array(
		'user_login'    => isset( $_POST['username'] ) ? $_POST['username'] : ' ',
		'user_password' => isset( $_POST['password'] ) ? $_POST['password'] : ' ',
		'remember'      => isset( $_POST['password'] ) && $_POST['password'] == 'forever' ? true : false
	);

	$user = wp_signon( $creds );

	if ( is_wp_error( $user ) ) {

		echo $user->get_error_message();

	} else {

		wp_send_json( array( 'url' => home_url() ) );

	}

	die();
}

add_action('wp_ajax_ts_login', 'ts_login');
add_action( 'wp_ajax_nopriv_ts_login', 'ts_login' );
?>
