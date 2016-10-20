<?php
/**
 * Function Name: videocast_login_proceed_form
 * Description: This function validates login field and
 * Redirect to admin page
 * @global type $posted
 * @return type 
 */
function videocast_login_proceed_form() {

	global $posted;	
	if ( isset( $_REQUEST['redirect_to'] ) )
		$redirect_to = $_REQUEST['redirect_to'];
	else
		$redirect_to = admin_url();	
	if ( is_ssl() && force_ssl_login() && !force_ssl_admin() && ( 0 !== strpos($redirect_to, 'https') ) && ( 0 === strpos($redirect_to, 'http') ) )
		$secure_cookie = false;
	else
		$secure_cookie = '';

	$user = wp_signon('', $secure_cookie);
	$redirect_to = apply_filters('login_redirect', $redirect_to, isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '', $user);
	if ( !is_wp_error($user) ) {	
		if (user_can($user, 'manage_options')) :
			$redirect_to = admin_url();
		endif;			
		wp_safe_redirect($redirect_to);
		exit;
	}
	$errors = $user;
	return $errors;
}
/**
 * Function Name:  videocast_login_form
 * Description: This function creates user login form
 */
function videocast_login_form($class=null) {
    ?>
    <div id="loginform" class="<?php echo $class; ?> signin">
	<h3><?php echo SIGN; ?></h3>
        <form name="loginform" id="login_form" class="signinForm" action="<?php bloginfo('url') ?>/wp-login.php" method="post">
            <div class="row">
                <label for="username"><?php echo U_NAME; ?><span class="required">*</span></label>
                <input type="text" name="log" id="username" value="<?php echo esc_attr(stripslashes($user_login)); ?>"/>                
            </div>
            <div class="row password">
                <label for="password"><?php echo PASS; ?><span class="required">*</span></label>
                <input type="password" name="pwd" id="password" value=""/> 
            </div>            
             <?php ?>
			 </br>
            <input class="submit" type="submit" name="login" value="Log In"/>
			<p><?php echo LOST_D; ?></p> 
            <a href="<?php echo site_url('wp-login.php?action=lostpassword'); ?>" class="forgot_password" ><?php echo LOST; ?></a>
<!--            <a href="javascript:void(0);videocast_forgetpw();" class="forgot_password" >Lost your password?</a>-->
            <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
            <input type="hidden" name="user-cookie" value="1" />
        </form>
		<div class="remember">
            </div>
        <script  type="text/javascript" >
    function videocast_forgetpw()
    {
	if(document.getElementById('fotget_pw').style.display=='none')
	{
		document.getElementById('fotget_pw').style.display = 'block';
	}else
	{
		document.getElementById('fotget_pw').style.display = 'none';
	}	
    }
    </script> 
    </div>
	<?php
	}
	?>