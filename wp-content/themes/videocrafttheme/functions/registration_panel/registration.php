<?php
/**
 * Function Name:  videocast_register_form
 * Description: This function creates user login form
 */
function videocast_register_form($action = '') {
    global $posted;
   	$multi_site = WP_ALLOW_MULTISITE;
	if (get_option('users_can_register') || $multi_site == true) :
        if (!$action)
            $action = site_url('wp-login.php?action=register');
		
        ?>
        <div id="registration_form">
            <div class="registration">
			<h3><?php echo FREE; ?></h3>
	             <form name="registration" id="reg_form" class="registrtaionForm" action="<?php echo $action; ?>" method="post">
                    <div class="row">
                        <label for="user_login"><?php echo U_NAME; ?><span class="required">*</span></label>
                        <input type="text" class="required requiredField" id="user_login" name="your_username" value="<?php if (isset($posted['your_username'])) echo $posted['your_username']; ?>"/>
                        <span id="user_error"></span>
                    </div>
                    <div class="row">
                        <label for="email"><?php echo EMAIL; ?><span class="required">*</span></label>
                        <input type="text" class="required requiredField email" id="email" name="your_email" value="<?php if (isset($posted['your_email'])) echo $posted['your_email']; ?>"/>
                        <span id="email_error"></span>
                    </div>
                    <div class="row">
                        <label for="rpassword"><?php echo PASS; ?><span class="required">*</span></label>
                        <input type="password" id="rpassword" name="your_password" value=""/>
                        <span id="pw_error"></span>
                    </div>
                    <div class="row">
                        <label for="password2"><?php echo AGAIN_PASS; ?><span class="required">*</span></label>
                        <input type="password" id="password2" name="your_password_2" value=""/>
                        <span id="pw_error2"></span>
                    </div>
                    
                             
    
                     <?php if(inkthemes_get_option('reg_terms') == 'on'){ ?>
                    <div class="row">
                        <input type="checkbox" id="terms" name="terms"/>
                        <label class="terms"><?php echo sprintf(__('I agree to the <a target="_new" href="%s">Terms and conditions</a>.',THEME_SLUG),  inkthemes_get_option('vc_terms')); ?></label>
                        <span style="display:block;" class="term_error"></span>
                        <inpu type="hidden" name="termcheck" id="termcheck" value="true"/>
                    </div>
                    <?php }else{ ?>
                    <inpu type="hidden" name="termcheck" id="termcheck" value=""/>
                    <?php } ?>
                    
                    
                    <div class="row">
                        <input type="submit" name="register" value="<?php echo "Register"; ?>" class="submit" tabindex="103" />

                        <input type="hidden" name="user-cookie" value="1" />
                    </div>
                </form>
            </div>
        </div> 
           
     <?php /*?>   <?php include_once(TEMPLATEPATH . '/js/registration_validation.php'); ?>  <?php */?>
     
            
    <?php endif; ?>
    
<?php }
function videocast_reg_proceed_form($success_redirect = '') {

    if (!$success_redirect)
        $success_redirect = site_url();

    	$multi_site = WP_ALLOW_MULTISITE;
if (get_option('users_can_register') || $multi_site == true) :
        global $posted;
        $posted = array();
        $errors = new WP_Error();
        if (isset($_POST['register']) && $_POST['register']) {

            require_once( ABSPATH . WPINC . '/registration.php');

            // Get (and clean) data
            $fields = array(
                'your_username',
                'your_email',
                'your_password',
                'your_password_2',
				'terms'
            );
            foreach ($fields as $field) {
                $posted[$field] = stripslashes(trim($_POST[$field]));
            }
            $user_login = sanitize_user($posted['your_username']);
            $user_email = apply_filters('user_registration_email', $posted['your_email']);
            // Check the username
            if ($posted['your_username'] == '')
                $errors->add('empty_username', __('<strong>ERROR</strong>: Please enter a username.', 'videocraft'));
            elseif (!validate_username($posted['your_username'])) {
                $errors->add('invalid_username', __('<strong>ERROR</strong>: This username is invalid.  Please enter a valid username.', 'videocraft'));
                $posted['your_username'] = '';
            } elseif (username_exists($posted['your_username']))
                $errors->add('username_exists', __('<strong>ERROR</strong>: This username is already registered, please choose another one.', 'videocraft'));
            // Check the e-mail address
            if ($posted['your_email'] == '') {
                $errors->add('empty_email', __('<strong>ERROR</strong>: Please type your e-mail address.', 'videocraft'));
            } elseif (!is_email($posted['your_email'])) {
                $errors->add('invalid_email', __('<strong>ERROR</strong>: The email address isn&#8217;t correct.', 'videocraft'));
                $posted['your_email'] = '';
            } elseif (email_exists($posted['your_email']))
                $errors->add('email_exists', __('<strong>ERROR</strong>: This email is already registered, please choose another one.', 'videocraft'));

            // Check Passwords match
            if ($posted['your_password'] == '')
                $errors->add('empty_password', __('<strong>ERROR</strong>: Please enter a password.', 'videocraft'));
            elseif ($posted['your_password_2'] == '')
                $errors->add('empty_password', __('<strong>ERROR</strong>: Please enter password twice.', 'videocraft'));
            elseif ($posted['your_password'] !== $posted['your_password_2'])
                $errors->add('wrong_password', __('<strong>ERROR</strong>: Passwords do not match.', 'videocraft'));
			
	
				
						
			 if ($posted['terms'] == '' && inkthemes_get_option('reg_terms') == 'on' )
                $errors->add('empty_terms', __('<strong>ERROR</strong>: You must agree terms and conditions.', 'videocraft'));
			
				
					
            do_action('register_post', $posted['your_username'], $posted['your_email'], $errors);
            $errors = apply_filters('registration_errors', $errors, $posted['your_username'], $posted['your_email']);
            if (!$errors->get_error_code()) {
                $user_pass = $posted['your_password'];
                $user_id = wp_create_user($posted['your_username'], $user_pass, $posted['your_email']);
                if (!$user_id) {
                    $errors->add('registerfail', sprintf(__('<strong>ERROR</strong>: Couldn&#8217;t register you... please contact the <a href="mailto:%s">webmaster</a> !', 'videocraft'), get_option('admin_email')));
                    return array('errors' => $errors, 'posted' => $posted);
                }
                // Change role
                wp_update_user(array('ID' => $user_id, 'role' => 'contributor'));
               wp_new_user_notification($user_id, $user_pass);
                $secure_cookie = is_ssl() ? true : false;
                wp_set_auth_cookie($user_id, true, $secure_cookie);
                ### Redirect
                wp_redirect($success_redirect);
                exit;
            } else {
                return array('errors' => $errors, 'posted' => $posted);
            }
        }
		
    endif;
}

?>