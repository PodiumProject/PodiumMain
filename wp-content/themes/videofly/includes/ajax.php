<?php

function vdf_edit_template_element()
{
	require_once get_template_directory() . '/includes/layout-builder/views/elements/elements-editor.php';
	die();
}

add_action('wp_ajax_vdf_edit_template_element', 'vdf_edit_template_element');

function vdf_edit_template_row()
{

	require_once get_template_directory() . '/includes/layout-builder/views/elements/edit-template-row.php';
	die();
}

add_action('wp_ajax_vdf_edit_template_row', 'vdf_edit_template_row');

function vdf_edit_template_column()
{

	require_once get_template_directory() . '/includes/layout-builder/views/elements/edit-template-column.php';
	die();
}
add_action('wp_ajax_vdf_edit_template_column', 'vdf_edit_template_column');

function vdf_save_touchsize_news()
{
	check_ajax_referer( 'vdf_save_touchsize_news', 'token' );

	header('Content-Type: application/json');

	$last_update = time();
	$options = get_option('videofly_red_area', array());

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
			$parsed_alerts['id'] = (int)$alerts['id'];
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

	update_option('videofly_red_area', $options);

	$data = array(
		'status'  => 'ok',
		'message' => esc_html__( 'Saved', 'videofly')
	);

	echo json_encode($data);
	die();
}

add_action('wp_ajax_vdf_save_touchsize_news', 'vdf_save_touchsize_news');

function vdf_hide_touchsize_alert()
{
	check_ajax_referer( 'remove-videofly-alert', 'token' );

	header('Content-Type: application/json');

	$options = get_option('videofly_red_area', array(
		'news' => '',
		'alert' => array(
			'id' => 0,
			'message' => ''
		),
		'hidden_alerts' => array(),
		'time' => time()
	));

	$alets_id = (int)@$_POST['alertID'];

	if ( ! in_array($alets_id, $options['hidden_alerts'])) {
		array_push($options['hidden_alerts'], $alets_id);
	}

	update_option('videofly_red_area', $options);

	$data = array(
		'status'  => 'ok',
		'message' => esc_html__( 'Saved', 'videofly')
	);

	echo json_encode($data);
	die();
}

add_action('wp_ajax_vdf_hide_touchsize_alert', 'vdf_hide_touchsize_alert');

function vdf_contact_me()
{

	check_ajax_referer( 'submit-contact-form', 'token' );

	header('Content-Type: application/json');

	$data = array(
		'status'  => 'ok',
		'message' => ''
	);

	$options = get_option( 'videofly_social', array('email' => ''));

	$from    	  = @$_POST['from'];
	$subject 	  = @$_POST['subject'];
	$message 	  = @$_POST['message'];
	$name    	  = @$_POST['name'];
	$custom_field = (isset($_POST['custom_field']) && is_array($_POST['custom_field']) && !empty($_POST['custom_field'])) ? $_POST['custom_field'] : NULL;

	$subject = $subject === '' ? bloginfo('name') . esc_html__('Message from ', 'videofly') . wp_kses( $name, array()) : $subject;

	if( is_plugin_active('really-simple-captcha/really-simple-captcha.php') ){
		$isCapcha = true;
		$captcha_instance = new ReallySimpleCaptcha();
		$capchaCheck = $captcha_instance->check((isset($_POST['prefix']) ? $_POST['prefix'] : ''), (isset($_POST['capcha']) ? $_POST['capcha'] : ''));

		if( !$capchaCheck ){
			wp_send_json(array('status' => 'error', 'message' => esc_html__('Invalid capcha. Try again.', 'videofly')));
		}
	}

	if ( is_email($options['email']) && is_email($from) ) {

		if( isset($custom_field) ){

			foreach($custom_field as $value){
				$message .= $value['title'] . ':' . $value['value'] . "\r\n";
				if( $value['require'] == 'y' && $value['value'] == '' ){
					$error_require = 'Mail not sent. This field "' . $value['title'] . '" is required';
					$data = array(
						'status'  => 'error',
						'message' => $error_require,
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
				'message' => esc_html__('Mail sent.', 'videofly'),
				'token' => wp_create_nonce("submit-contact-form")
			);

			if( isset($isCapcha) ) $captcha_instance->remove((isset($_POST['prefix']) ? $_POST['prefix'] : ''));

		} else {
			$data = array(
				'status'  => 'error',
				'message' => esc_html__('Error. Mail not sent.', 'videofly'),
				'token' => wp_create_nonce("submit-contact-form")
			);
		}
	} else {
		$data = array(
			'status'  => 'error',
			'message' => esc_html__('Invalid email adress', 'videofly'),
			'token' => wp_create_nonce("submit-contact-form")
		);
	}
	echo json_encode($data);
	die();
}

add_action('wp_ajax_vdf_contact_me', 'vdf_contact_me');
add_action( 'wp_ajax_nopriv_vdf_contact_me', 'vdf_contact_me' );

//========================================================================
// Save/Edit templates ===================================================
// =======================================================================

// Load template
function vdf_load_template()
{
	$template_id     = @$_GET['template_id'];
	$location        = @$_GET['location'];

	$result = Template::load_template($location, $template_id);

	wp_send_json( $result );
}

add_action('wp_ajax_vdf_load_template', 'vdf_load_template');

// Save blank template
function vdf_save_layout()
{
	// if not administrator, kill WordPress execution and provide a message
	if ( ! is_admin() ) {
		return false;
	}

	$location    = @$_POST['location'];
	$mode		 = @$_POST['mode'];

	if ( isset( $_POST['post_id'] ) ) {

		update_post_meta( $_POST['post_id'], 'ts_use_template', 1 );

	}

	$data = array( 'status' => 'ok', 'message' => '' );

	$response = Template::save( $mode, $location );

	if ( ! $response ) {
		$data['status'] = 'error';
		$data['message'] = esc_html__( 'Cannot save this template', 'videofly' );
	}

	wp_send_json( $data );
}

add_action( 'wp_ajax_vdf_save_layout', 'vdf_save_layout' );

// Remove template
function vdf_remove_template()
{
	// if not administrator, kill WordPress execution and provide a message
	if ( ! current_user_can( 'manage_options' ) ) {
		return false;
	}

	header('Content-Type: application/json');
	// check_ajax_referer( 'remove-videofly-alert', 'token' );

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
			'message' => esc_html__('Cannot delete this template', 'videofly')
		);
	}

	echo json_encode($data);
	die();
}

add_action('wp_ajax_vdf_remove_template', 'vdf_remove_template');

function vdf_load_all_templates()
{
	$location = @$_POST['location'];
	$templates = Template::get_all_templates($location);

	$edit = '';
	if ( $templates ) {
		foreach ($templates as $template_id => $template) {

			$remove_template = '';

			if ( $template_id !== 'default' ) {
				$remove_template = '<a href="#" data-template-id="'.esc_attr($template_id) .'" data-location="'.esc_attr($location).'" class="ts-remove-template icon-delete">' . esc_html__('remove', 'videofly') . '</a>';
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

	echo vdf_var_sanitize($edit);
	die();
}

add_action('wp_ajax_vdf_load_all_templates', 'vdf_load_all_templates');

function vdf_toggle_layout_builder()
{
	// if not administrator, kill WordPress execution and provide a message
	if ( ! current_user_can( 'manage_options' ) ) {
		return false;
	}

	$post_id = @$_POST['post_id'];
	$state  = @$_POST['state'];

	$valid_states = array(
		'enable' => 1,
		'disable' => 0
	);

	if ( array_key_exists($state, $valid_states) ) {
		update_post_meta((int)$post_id, 'ts_use_template', $valid_states[$state]);
	}

	die();
}

add_action('wp_ajax_vdf_toggle_layout_builder', 'vdf_toggle_layout_builder');

function vdfupdateFeatures(){
    $nonce = $_POST['nonce_featured'];

    if ( !wp_verify_nonce( $nonce, 'extern_request_die' ) ) return false;
    if ( !current_user_can( 'manage_options' ) ) return false;

    $id_post = (isset($_POST['value_checkbox']) && (int)$_POST['value_checkbox'] !== 0) ? (int)$_POST['value_checkbox'] : NULL;
    $value_checkbox = (isset($_POST['checked']) && $_POST['checked'] !== '' && ($_POST['checked'] == 'yes' || $_POST['checked'] == 'no')) ? $_POST['checked'] : 'no';

    if( isset($id_post) ){
       update_post_meta($id_post, 'featured', $value_checkbox);
    }

    die();
}

if( is_admin() ) {
    add_action('wp_ajax_vdfupdateFeatures', 'vdfupdateFeatures');
    add_action('wp_ajax_nopriv_vdfupdateFeatures', 'vdfupdateFeatures');
}

//function generate random likes for all posts
function vdf_generate_like_callback(){

    check_ajax_referer( 'like-generate', 'nonce' );
    if ( !current_user_can( 'manage_options' ) ) return false;

    global $wpdb;

    $vdf_post_type = 'videofly';
    $sql = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_type != %s", $vdf_post_type);
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

add_action('wp_ajax_ts_generate_like', 'vdf_generate_like_callback');

//function generate the pagination read more
function vdf_pagination_callback(){

    if( isset($_POST, $_POST['action'], $_POST['args'], $_POST['paginationNonce'], $_POST['loop']) ){

        check_ajax_referer('pagination-read-more', 'paginationNonce');

        if( !function_exists('ts_enc_string') ) die('You need to install plugin "Touchsize Custom Posts"');

        $args = unserialize(ts_enc_string($_POST['args'], 'decode'));

        $loop = (is_numeric($_POST['loop'])) ? (int)$_POST['loop'] : '';

        if( is_array($args) ){

            if(isset($args['options']) && is_array($args['options'])){
                $options = $args['options'];
                $options['loop'] = $loop;
                unset($args['options']);
            }

            if( isset($options) && is_array($options) ){

                $offset = (isset($args['offset'])) ? (int)$args['offset'] : 0;

                if( isset($args['posts_per_page']) ){
                    if( $args['posts_per_page'] == 0 ){
                        $args['posts_per_page'] = get_option('posts_per_page');
                    }

                    if( $loop > 0 ){
                        $args['offset'] = $offset + ((int)$args['posts_per_page'] * $loop);
                    }

                    if( $loop == 0){
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

                if( isset($args['post_type']) && $args['post_type'] === 'post' ){
                    $options['ajax-load-more'] = true;
                    $query = new WP_Query($args);
                    if ( $query->have_posts() ) {
                        echo LayoutCompilator::last_posts_element($options, $query);
                    }else{
                        return false;
                    }
                }

                if( isset($args['post_type']) && $args['post_type'] === 'event' ){
                    $options['ajax-load-more'] = true;
                    $query = new WP_Query($args);
                    if ( $query->have_posts() ) {
                        echo LayoutCompilator::events_element($options, $query);
                    }else{
                        return false;
                    }
                }

                if( isset($args['post_type']) && $args['post_type'] === 'ts-gallery' ){
                    $options['ajax-load-more'] = true;
                    $query = new WP_Query($args);
                    if ( $query->have_posts() ) {
                        echo LayoutCompilator::list_galleries_element($options, $query);
                    }else{
                        return false;
                    }
                }

                if( isset($args['post_type']) && $args['post_type'] !== 'event' && $args['post_type'] !== 'video' && $args['post_type'] !== 'post' && $args['post_type'] !== 'ts-gallery' ){
                    $options['ajax-load-more'] = true;
                    $query = new WP_Query($args);
                    if ( $query->have_posts() ) {
                        echo LayoutCompilator::latest_custom_posts_element($options, $query);
                    }else{
                        return false;
                    }
                }
            }
        }
    }
    die();
}
add_action('wp_ajax_ts_pagination', 'vdf_pagination_callback');
add_action('wp_ajax_nopriv_ts_pagination', 'vdf_pagination_callback');

function vdf_set_share_callback(){

	check_ajax_referer( 'security', 'ts_security' );

	if( isset($_POST['postId'], $_POST['social']) ){

		$post_id = ((int)$_POST['postId'] !== 0) ? (int)$_POST['postId'] : '';
		$all_social = array('facebook', 'twitter', 'gplus', 'linkedin', 'tumblr', 'pinterest');
		$social = (in_array($_POST['social'], $all_social)) ? $_POST['social'] : '';

		if( isset($_COOKIE['ts-syncope-social-' . $social . '-id-' . $post_id]) ){
			echo '-1';
		}else{
			$count_social = get_post_meta($post_id, 'ts-social-' . $social, true);
			$total = 0;

			foreach($all_social as $socialName){
				$countSocial = get_post_meta($post_id, 'ts-social-' . $socialName, true);
				if( isset($countSocial) && !empty($countSocial) ){
					$total += (int)$countSocial;
				}
			}

			if( isset($count_social) && (int)$count_social > 0 ){
				$count_total = (int)$count_social + 1;
				$total = (int)$total + 1;
				update_post_meta($post_id, 'ts-social-' . $social, $count_total);
				update_post_meta($post_id, 'ts-social-count', $total);
			}else{
				update_post_meta($post_id, 'ts-social-' . $social, 	1);
				$total = (int)$total + 1;
				update_post_meta($post_id, 'ts-social-count', $total);
			}
			setcookie('ts-syncope-social-' . $social . '-id-' . $post_id, 1, time() + 86400 * 7);
			echo (int)$count_social + 1;
		}
	}

    die();
}
add_action('wp_ajax_ts_set_share', 'vdf_set_share_callback');
add_action('wp_ajax_nopriv_ts_set_share', 'vdf_set_share_callback');

function vdf_delete_custom_icon(){

	check_ajax_referer( 'feature_nonce', 'nonce' );

	$options = get_option('videofly_typography');

	if( isset($_POST['icon']) && !empty($_POST['icon']) ){

		if( !empty($options['custom-icon']) ){

			$removeKey = '';

			foreach($options['custom-icon'] as $item => $value){

				if( !empty($value['classes']) ){

					$classes = explode(',', $value['classes']);
					$classKey = '';

					foreach($classes as $key => $class){
						if( $class == $_POST['icon'] ) $classKey = $key;
					}

					unset($classes[$classKey]);
					$options['custom-icon'][$item]['classes'] = implode(',', $classes);

					$css = preg_replace('/.'. $_POST['icon'] .':before *\{[\s\S]*?\}{1}/', '', $value['css']);
					$options['custom-icon'][$item]['css'] = $css;

					$classesGeneral = explode(',', $options['icons']);
					$classKeyGeneral = '';

					foreach($classesGeneral as $keyGeneral => $classGeneral){
						if( $classGeneral == $_POST['icon'] ) $classKeyGeneral = $keyGeneral;
					}

					unset($classesGeneral[$classKeyGeneral]);

					$options['icons'] = implode(',', $classesGeneral);

				}

				if( $options['custom-icon'][$item]['classes'] == '' ){
					foreach($value['ids'] as $idAttachment){
						wp_delete_attachment($idAttachment, true);
					}

					unset($options['custom-icon'][$item]);
				}

			}

		}
	}

	if( isset($_POST['key']) && isset($options['custom-icon'][$_POST['key']]) ){

		foreach($options['custom-icon'][$_POST['key']]['ids'] as $idAttachment){
			wp_delete_attachment($idAttachment, true);
		}

		$generalClasses = explode(',', $options['icons']);
		$removeClasses = explode(',', $options['custom-icon'][$_POST['key']]['classes']);

		$delete = array_intersect($generalClasses, $removeClasses);

		foreach($delete as $keyForDelete => $class ){
			unset($generalClasses[$keyForDelete]);
		}

		$options['icons'] = implode(',', $generalClasses);

		unset($options['custom-icon'][$_POST['key']]);

	}

	$result = update_option('videofly_typography', $options);

	if( $result ){
		echo 'ok';
	}

    die();
}
add_action('wp_ajax_vdf_delete_custom_icon', 'vdf_delete_custom_icon');

function vdf_get_terms(){

	if( !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'extern_request_die') ) return;

	$terms = get_terms($_POST['taxonomy'], array('hide_empty' => false));
	$terms = !empty($terms) && is_array($terms) && !is_wp_error($terms) ? $terms : array();
	?>
	<p>
		<label>
			<?php echo ($_POST['taxonomy'] == 'post_tag' ? esc_html__('Select post tag', 'videofly') :  esc_html__('Select post terms', 'videofly')) ?>:
			<select class="widefat multiple-select" name="<?php echo vdf_var_sanitize($_POST['name']) ?>[]" multiple>
				<?php foreach( $terms as $term ): ?>
					<option value="<?php echo vdf_var_sanitize($term->slug) ?>">
						<?php echo vdf_var_sanitize($term->name); ?>
					</option>
				<?php endforeach ?>
			</select>
		</label>
	</p>
	<?php

	die();
}



function vdf_get_taxonomies(){

	if( !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'extern_request_die') ) return;

	if( isset($_POST['postType']) && !empty($_POST['postType']) && isset($_POST['name']) && !empty($_POST['name']) ):

		$allowedPostTypes = array(
			'post' => array(
				'category' => esc_html__('Category', 'videofly'),
				'post_tag' => esc_html__('Post tag', 'videofly') ,
			),
			'video' => array(
				'videos_categories' => esc_html__('Video category','videofly'),
				'post_tag' => esc_html__('Video tag', 'videofly') ,
			),
			'ts-gallery' => array(
				'gallery_categories' => esc_html__('Gallery category','videofly'),
			 	'post_tag' => esc_html__('Gallery tag', 'videofly') ,
			),
		);

		if( isset($allowedPostTypes[$_POST['postType']]) ): ?>
			<p>
				<label ><?php esc_html_e('Select post taxonomy','videofly') ?>:
					<select class="ts-select-taxonomy widefat multiple-select" name="<?php echo vdf_var_sanitize($_POST['name']) ?>">
						<option value=""><?php esc_html_e('Select taxonomy','videofly') ?></option>
						<?php foreach( $allowedPostTypes[$_POST['postType']] as $taxonomy => $textUser ): ?>
							<option value="<?php echo vdf_var_sanitize($taxonomy) ?>">
								<?php echo vdf_var_sanitize($textUser) ?>
							</option>
						<?php endforeach ?>
					</select>
				</label>
			</p>
		<?php endif ?>
	<?php endif;

	die();
}

function ts_actions_ajax_widgets() {
    add_action('wp_ajax_ts_get_taxonomy', 'vdf_get_taxonomies');
    add_action('wp_ajax_vdf_get_terms', 'vdf_get_terms');
}
add_action( 'widgets_init', 'ts_actions_ajax_widgets' );

function vdf_search_content()
{
	header('Content-Type: application/json');
	$result = array();

	$args = array();

	$args['s'] = (isset($_POST['search'])) ? $_POST['search'] : '';
	$args['post_type'] = array('post', 'ts-gallery', 'video');
	$args['orderby'] = 'ID';
	$args['order'] = 'DESC';
	add_filter('posts_search', 'vdf_search_by_title_only', 500, 2);

	$the_query = new WP_Query($args);
	while ( $the_query->have_posts() ) :
		$the_query->the_post();
		$result[] = array( 'id' => get_the_ID(), 'title' => get_the_title() );
	endwhile;

	wp_reset_postdata();

	echo json_encode($result);
	die();
}

add_action('wp_ajax_vdf_search_content', 'vdf_search_content');

function vdf_search_by_title_only( $search, &$wp_query )
{
    global $wpdb;
    if ( empty( $search ) ) {
        return $search;
    }

    $q = $wp_query->query_vars;
    $n = ! empty( $q['exact'] ) ? '' : '%';
    $search = '';
    $searchand = '';
    foreach ( (array) $q['search_terms'] as $term ) {
        $term = esc_sql( $wpdb->esc_like($term) );
        $search .= "{$searchand}($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
        $searchand = ' AND ';
    }
    if ( ! empty( $search ) ) {
        $search = " AND ({$search}) ";
        if ( ! is_user_logged_in() )
            $search .= " AND ($wpdb->posts.post_password = '') ";
    }
    return $search;
}

function vdf_regenerateCaptcha(){

	check_ajax_referer('submit-contact-form', 'token');

	vdf_captcha('img');

	die();
}

add_action('wp_ajax_vdf_regenerateCaptcha', 'vdf_regenerateCaptcha');
add_action('wp_ajax_nopriv_vdf_regenerateCaptcha', 'vdf_regenerateCaptcha');

function vdf_getVideo(){

	check_ajax_referer( 'security', 'nonce' );

	if( !isset($_POST['postId']) ) die();

	$video = get_post_meta(intval($_POST['postId']), 'ts-video', true);

	if ( is_array($video) ) {
		if ( $video['type'] == 'url' ) {
			echo wp_oembed_get($video['video']);
		} elseif ( $video['type'] == 'embed' ) {
			echo vdf_var_sanitize($video['video']);
		}
		else {
			$attr = array(
				'src'      => $video['video'],
				'autoplay' => 'on',
			);
			echo wp_video_shortcode($attr);
		}
	} else {
		echo esc_html__('No video.', 'videofly');
	}
	die();
}

add_action('wp_ajax_vdf-get-video', 'vdf_getVideo');
add_action('wp_ajax_nopriv_vdf-get-video', 'vdf_getVideo');
?>
