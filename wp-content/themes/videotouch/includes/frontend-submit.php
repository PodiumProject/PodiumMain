<?php

add_filter('teeny_mce_buttons', 'ts_editor_buttons', 10, 2);
function ts_editor_buttons($buttons, $editor_id)
{
    return array(
        'bold',
        'italic',
        'underline',
        'aligncenter',
        'alignright',
        'alignleft');
}

add_role('simple_user', 'Simple user', array());

// get a register form in front page
function ts_get_register_form_callback()
{
    $nonce = (isset($_POST['nonce'])) ? $_POST['nonce'] : '';
    if (!wp_verify_nonce($nonce, 'video_nonce'))
        return false;
    $facebook = get_option('videotouch_general', array('login_register_by_facebook' => 'n'));
    if( $facebook['login_register_by_facebook'] == 'y' ){
        $facebook_link_register = '<a href="javascript:void(0)" onClick="FBLogin();">' . __('Register With Facebook', 'touchsize') . '</a>';
    }else{
        $facebook_link_register = '';
    }
?>
    <form enctype="multipart/form-data" class="ts-form-horizontal">
        <p class="ts-form-group">
            <label for="ts-name"><?php _e('Your Name', 'touchsize'); ?></label>
            <input type="text" name="ts-name" id="ts-name" value="" placeholder="<?php _e('Your Name',
'touchsize'); ?>"/>
        </p>

        <p class="ts-form-group">
            <label for="ts-email"><?php _e('Your Email', 'touchsize'); ?></label>
            <input type="email" name="ts-email" id="ts-email" value="" placeholder="<?php _e('Your Email',
'touchsize'); ?>"/>
        </p>

        <p class="ts-form-group">
            <label for="ts-nick"><?php _e('Your Nickname', 'touchsize'); ?></label>
            <input type="text" name="ts-nick" id="ts-nick" value="" placeholder="<?php _e('Your Nickname',
'touchsize'); ?>"/>
        </p>

        <p class="ts-form-group">
            <label for="ts-username"><?php _e('Choose Username', 'touchsize'); ?></label>
            <input type="text" name="ts-username" id="ts-username" value="" placeholder="<?php _e('Choose Username',
'touchsize'); ?>"/>
            <span class="ts-help-block"><?php _e('Please use only a-z,A-Z,0-9,dash and underscores, minimum 5 characters',
'touchsize'); ?></span>
        </p>

        <p class="ts-form-group">
            <label for="ts-pass"><?php _e('Choose Password', 'touchsize'); ?></label>
            <input type="password" name="ts-pass" id="ts-pass" value="" placeholder="<?php _e('Choose Password',
'touchsize'); ?>"/>
            <span class="ts-help-block"><?php _e('Minimum 5 characters',
'touchsize'); ?></span>
        </p>

        <p class="ts-form-group">
            <label for="ts-description"><?php _e('Add your description',
'touchsize'); ?></label>
            <textarea name="ts-description" id="ts-description" value="" placeholder="<?php _e('Choose Description',
'touchsize'); ?>"></textarea>
            <span class="ts-help-block"><?php _e('Minimum 5 characters',
'touchsize'); ?></span>
        </p>

        <p class="ts-form-group">
            <label for="ts-url"><?php _e('Add your site url', 'touchsize'); ?></label>
            <input type="text" name="ts-url" id="ts-url" value="" placeholder="<?php _e('Add your site url here',
'touchsize'); ?>"></textarea>
            <span class="ts-help-block"><?php _e('Add your site here',
'touchsize'); ?></span>
        </p>
        <?php wp_nonce_field('ts_new_user', 'ts_new_user_nonce', true, true); ?>
        <p class="login-submit">
            <input type="submit" class="btn btn-primary" id="ts-btn-new-user" value="<?php _e('Register',
'touchsize') ?>" />
        </p>
        <?php echo $facebook_link_register; ?>
    </form>
    <div class="indicator"><?php _e('Please wait...', 'touchsize'); ?></div>
    <div style="display: none;" class="alert ts-error-message"><?php _e('The Name, Email, Nickname, Username, Password is required', 'touchsize'); ?></div>
    <div class="alert ts-result-message"></div>
<?php
    die();
} //end function ts_get_register_form_callback()

add_action('wp_ajax_ts_get_register_form', 'ts_get_register_form_callback');
add_action('wp_ajax_nopriv_ts_get_register_form',
    'ts_get_register_form_callback');

function ts_register_user_callback()
{

    if ( !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ts_new_user') ) {
        $error_message = __('Ooops, something went wrong, please try again later.',
            'touchsize');
        die($error_message);
    }

    $register = get_option('users_can_register');

    if ( (int)$register == 1 ) {

        if( isset($_POST) && !empty($_POST) && is_array($_POST) ){
            $username = (isset($_POST['user'])) ? sanitize_text_field($_POST['user']) : '';
            $password = (isset($_POST['pass'])) ? sanitize_text_field($_POST['pass']) : '';
            $email = (isset($_POST['mail'])) ? sanitize_text_field($_POST['mail']) : '';
            $name = (isset($_POST['name'])) ? sanitize_text_field($_POST['name']) : '';
            $nick = (isset($_POST['nick'])) ? sanitize_text_field($_POST['nick']) : '';
            $url = (isset($_POST['url'])) ? esc_url($_POST['url']) : '';
            $description = (isset($_POST['description'])) ? esc_textarea($_POST['description']) : '';

            if( !empty($username) && strlen($username) >= 5 && !empty($password) && strlen($password) >= 5 && is_email($email) && !empty($name) && !empty($nick) ){
                $userdata = array(
                    'user_login' => $username,
                    'user_pass' => $password,
                    'user_email' => $email,
                    'first_name' => $name,
                    'nickname' => $nick,
                    'user_url' => $url,
                    'role' => 'simple_user',
                    'description' => $description);

                $user_id = wp_insert_user($userdata);
            }else{
                $user_id = '';
                echo __('The requeired fields is Username, Nickname, Name, Email, Password', 'touchsize');
            }

            if( !empty($user_id) ){
                if (!is_wp_error($user_id)) {
                    wp_set_auth_cookie($user_id, true);
                    echo '1';
                } else {
                    echo $user_id->get_error_message();
                }
            }
        }
    }else{
        echo __('User register is not enable', 'touchsize');
    }

    die();
}
add_action('wp_ajax_ts_register_user', 'ts_register_user_callback');
add_action('wp_ajax_nopriv_ts_register_user', 'ts_register_user_callback');

function ts_save_post_user()
{

    $user = wp_get_current_user();
    if( !is_user_logged_in() ) return;
    
    if (isset($_POST['ts_save_post']) && wp_verify_nonce($_POST['ts_save_post'],
        'ts_save_post') && isset($_POST['save-posts'])) {
        $single = get_option('videotouch_single_post', array('user_profile' => ''));
        $link_to_profile = (isset($single['user_profile']) && !empty($single['user_profile'])) ?
            esc_url($single['user_profile']) : home_url();
        $post_status = get_option('videotouch_general', array('post_publish_user' =>
                'pending'));

        include_once (ABSPATH . "wp-admin" . '/includes/image.php');
        include_once (ABSPATH . "wp-admin" . '/includes/file.php');
        include_once (ABSPATH . "wp-admin" . '/includes/media.php');

        $title = (isset($_POST['ts-title-post'])) ? sanitize_text_field($_POST['ts-title-post']) :
            '';
        $content = (isset($_POST['ts-post-content'])) ? apply_filters('the_content', $_POST['ts-post-content']) :
            '';
        $category = (isset($_POST['ts-category-post']) && (int)$_POST['ts-category-post']
            !== 0) ? (int)$_POST['ts-category-post'] : '';
        $post_type = (isset($_POST['ts-post-type']) && ($_POST['ts-post-type'] === 'p' ||
            $_POST['ts-post-type'] === 'v')) ? $_POST['ts-post-type'] : 'p';
        $taxonomy = (isset($_POST['ts-category-video']) && (int)$_POST['ts-category-video']
            !== 0) ? (int)$_POST['ts-category-video'] : '';
        $video_url = (isset($_POST['ts-url-video']) && !empty($_POST['ts-url-video']) &&
            $post_type === 'v') ? esc_url($_POST['ts-url-video']) : null;
        $post_id = (isset($_POST['ts-post-id']) && !empty($_POST['ts-post-id']) && $_POST['ts-post-id'] >
            0) ? (int)$_POST['ts-post-id'] : null;
        $tags = (isset($_POST['ts-tags']) && !empty($_POST['ts-tags']) && is_string($_POST['ts-tags'])) ?
            explode(',', esc_attr($_POST['ts-tags'])) : null;
        $type_url = (isset($_POST['ts-video-type']) && ($_POST['ts-video-type'] ===
            'upload' || $_POST['ts-video-type'] === 'url')) ? $_POST['ts-video-type'] : null;

        $user_post = array(
            'post_title' => $title,
            'post_content' => $content,
            'post_author' => $user->ID,
            'post_status' => $post_status['post_publish_user']);
        if ($post_type === 'p') {
            $user_post['post_type'] = 'post';
            $user_post['post_category'] = (array )$category;
        } else {
            $user_post['post_type'] = 'video';
        }

        if (isset($post_id))
            $user_post['ID'] = $post_id;

        $last_id = wp_insert_post($user_post);

        if (isset($tags)) {
            wp_set_post_tags($last_id, $tags, false);
        }
        if ($post_type === 'v') {
            wp_set_post_terms($last_id, $taxonomy, 'videos_categories');
        }

        $allowed_img = array(
            'jpeg',
            'gif',
            'png');
        $allowed_video = array('mp4', 'webm');

        if (isset($_FILES['ts-upload-video']['type'])) {
            $array_type_video = explode("/", $_FILES['ts-upload-video']['type']);
            $extension_video = end($array_type_video);
        }

        if (isset($_FILES['ts-upload-post']['type'])) {
            $array_type_img = explode("/", $_FILES['ts-upload-post']['type']);
            $extension_img = end($array_type_img);
        }

        if ($post_type === 'v') {

            if (isset($_FILES['ts-upload-video']) && !empty($_FILES['ts-upload-video']['name']) &&
                $_FILES['ts-upload-video']['size'] > 100000 && in_array($extension_video, $allowed_video) &&
                $type_url === 'upload') {
                $attach_video_id = media_handle_upload('ts-upload-video', $last_id);
                $attachment_video_url = wp_get_attachment_url($attach_video_id);
                $your_url = array('your_url' => $attachment_video_url, 'extern_url' => '');
                update_post_meta($last_id, 'video', $your_url);
            }

            if (isset($_FILES['ts-upload-video']) && empty($_FILES['ts-upload-video']['name']) &&
                empty($_FILES['ts-upload-video']['type']) && $_FILES['ts-upload-video']['size']
                === 0 && isset($video_url) && $type_url === 'url') {
                $extern_url = array('extern_url' => $video_url, 'your_url' => '');
                update_post_meta($last_id, 'video', $extern_url);
            }
        }

        if (isset($_FILES['ts-upload-post'], $_FILES['ts-upload-post']['name']) && !
            empty($_FILES['ts-upload-post']['name']) && in_array($extension_img, $allowed_img) &&
            $_FILES['ts-upload-post']['size'] > 30000) {
            $attach_id_image = media_handle_upload('ts-upload-post', $last_id);
            if (is_int($attach_id_image) && (int)$attach_id_image > 0) {
                set_post_thumbnail($last_id, $attach_id_image);
            }
        }

        wp_redirect($link_to_profile);
        exit;
    }
}
add_action('init', 'ts_save_post_user');

function ts_get_login_form()
{
    $link_to_profile = home_url();

    if (!is_user_logged_in()) {
        $args = array(
            'echo' => false,
            'redirect' => $link_to_profile,
            'form_id' => 'loginform',
            'label_username' => __('Username', 'touchsize'),
            'label_password' => __('Password', 'touchsize'),
            'label_remember' => __('Remember Me', 'touchsize'),
            'label_log_in' => __('Log In', 'touchsize'),
            'id_username' => 'ts_videotouch_username_id',
            'id_password' => 'ts_videotouch_username_pass',
            'id_remember' => 'ts_videotouch_username_remember',
            'id_submit' => 'ts_videotouch_form_submit',
            'remember' => true,
            'value_username' => null,
            'value_remember' => false,
        );

        $form = wp_login_form($args);
    
        $facebook = get_option('videotouch_general', array('login_register_by_facebook' => 'n', 'facebook_app_id' => ''));
        $appid = $facebook['facebook_app_id'];
        if( $facebook['login_register_by_facebook'] == 'y' ){
            $facebook_javascript = '
                <script>
                    window.fbAsyncInit = function() {
                        FB.init({
                        appId      : "' . $appid . '",
                        status     : true, 
                        cookie     : true, 
                        xfbml      : true  
                        });
                    };
                    
                    (function(d){
                        var js, id = "facebook-jssdk", ref = d.getElementsByTagName("script")[0];
                        if (d.getElementById(id)) {return;}
                        js = d.createElement("script"); js.id = id; js.async = true;
                        js.src = "//connect.facebook.net/en_US/all.js";
                        ref.parentNode.insertBefore(js, ref);
                    }(document));

                    function FBLogin(){
                        FB.login(function(response){
                            if(response.authResponse){
                                window.location.href = "' . site_url() . '?option=tsfblogin";
                            }
                        }, {scope: "email,user_likes"});
                    }
                </script>';

            $facebook_link_login = '<a href="javascript:void(0)" onClick="FBLogin();">' . __('Login With Facebook', 'touchsize') . '</a>';
            
        }else{
            $facebook_javascript = '';
            $facebook_link_login = '';
        }

        return '
            <div class="ts-user-header-profile align-right">
                <div class="user-mini-avatar">
                    <a href="#"><img src="' . get_template_directory_uri() . '/images/user_profile_60.png" alt="image-user"/></a>
                </div>
                <div class="user-info">
                    <div><a id="ts-show-login-modal" href="#">' . __("Login",
            "touchsize") . '</a></div>
                    <div><a id="ts-show-register-modal" href="#">' . __("or",
            "touchsize") . ' <span>' . __("register", "touchsize") . '</span></a></div>
                </div>
            </div>

            <div class="modal fade ts-user-login-modal" id="ts-login-modal" tabindex="-1" role="dialog" aria-labelledby="videoModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content text-center">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">' . __('Close', 'touchsize') . '</span></button>
                            <h4 class="modal-title" id="videoModalLabel">' . __("Login/Register",
            "touchsize") . '</h4>
                        </div>
                        <div class="modal-body">
                            
                            <div class="modal-mini-avatar"><i class="icon-user"></i></div>
                            <div class="preloader"><img src="' .
            get_template_directory_uri() . '/images/ajax-loader.gif" alt="Loader"/></div>
                            <div class="ts-login">
                            ' . $facebook_javascript . '
                                <div class="ts-form-login">
                                    ' . $form . $facebook_link_login . '
                                </div>
                                <div class="ts-form-register"></div>
                                <div class="clearfix"></div>
                            </div>
                            <div style="height: 20px"></div>
                            <div class="ts-register">
                                
                                <a href="#" class="ts-show-register-modal">
                                   ' . __("Register", "touchsize") . '
                                </a>
                                <a href="#" class="ts-show-login-modal hidden">
                                   ' . __("Login", "touchsize") . '
                                </a>
                            </div>
                        </div>
                        <div style="height: 50px"></div>
                    </div>
                </div>
            </div>';
    }

} //end function ts_get_login_form()

function ts_fb_login_validate(){
    
    if(isset($_REQUEST['option']) and $_REQUEST['option'] == "tsfblogin"){
        include_once get_template_directory() . '/includes/ts-facebook/facebook.php';
        $app = get_option('videotouch_general', array('login_register_by_facebook' => 'n', 'facebook_app_id' => '', 'facebook_app_secret' => ''));

        if( $app['login_register_by_facebook'] == 'y' && $app['facebook_app_secret'] !== '' && $app['facebook_app_id'] !== '' ){
            $app_id = $app['facebook_app_id'];
            $app_secret = $app['facebook_app_secret'];
        }
        $appid      = $app_id;
        $appsecret  = $app_secret;

        $facebook   = new Facebook(array(
            'appId' => $appid,
            'secret' => $appsecret,
            'cookie' => TRUE,
        ));

        $fbuser = $facebook->getUser();

        if ($fbuser) {
            try {
                $user_profile = $facebook->api('/me');
            }
            catch (Exception $e) {
                echo $e->getMessage();
                exit();
            }
            $user_fbid  = $fbuser;
            $user_email = $user_profile["email"];
            $user_fnmae = $user_profile["first_name"];
            $last_name  = $user_profile['last_name'];

            if( email_exists($user_email ) ) {
                $user = get_user_by('email', $user_email);
                $user_id = $user->ID;
                wp_set_auth_cookie($user_id, true);
            }else { // this user is a guest
                $random_password = wp_generate_password(10, false);
                $userdata = array(
                    'user_login' => $user_fnmae,
                    'first_name' => $user_fnmae,
                    'nickname'   => $user_fnmae,
                    'last_name'  => $last_name,
                    'user_email' => $user_email,
                    'user_pass'  => $random_password,
                    'role'       => 'simple_user'
                );

                if( username_exists($user_fnmae) ){
                   $userdata['user_login'] = $user_famae . '_' . rand(1, 9999);
                }

                $user_id = wp_insert_user($userdata);
                wp_set_auth_cookie($user_id, true);
            }
           
            wp_redirect(site_url());
            exit;
        }       
    }
}

add_action( 'init', 'ts_fb_login_validate' );

function ts_update_user()
{

    $user = wp_get_current_user();

    if (isset($_POST['ts_update_user_nonce'], $user, $user->roles[0]) &&
        wp_verify_nonce($_POST['ts_update_user_nonce'], 'ts_update_user') &&
        current_user_can('simple_user')) {

        $password = (isset($_POST['ts-pass']) && !empty($_POST['ts-pass'])) ? $_POST['ts-pass'] : null;
        $passwordConfirm = (isset($_POST['ts-pass-confirm']) && !empty($_POST['ts-pass-confirm'])) ? $_POST['ts-pass-confirm'] : null;
        $email = (isset($_POST['ts-email'])) ? $_POST['ts-email'] : '';
        $nickname = (isset($_POST['ts-nick'])) ? $_POST['ts-nick'] : '';
        $username = (isset($_POST['ts-username'])) ? $_POST['ts-username'] : '';
        $description = (isset($_POST['ts-description'])) ? $_POST['ts-description'] : '';
        $site_url = (isset($_POST['ts-url'])) ? $_POST['ts-url'] : '';

        $userdata = array(
            'ID' => $user->ID,
            'nickname' => $nickname,
            'user_url' => $site_url,
            'user_email' => $email,
            'display_name' => $username,
            'description' => $description,
        );

        if (isset($password, $passwordConfirm) && $password == $passwordConfirm ) {
            $userdata['user_pass'] = $password;
        }

        $user_id = wp_update_user($userdata);
      
        if (is_wp_error($user_id)) {
            echo '<div class="ts-error">' . __('Error update data', 'touchsize') . '</div>';
        } else {
            //echo '<div class="ts-success">' . __('success', 'touchsize') . '</div>';
        }

    }

}
add_action('init', 'ts_update_user');
?>