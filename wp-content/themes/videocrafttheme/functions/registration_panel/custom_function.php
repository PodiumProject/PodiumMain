<?php
/**
 * Custom functions for front end 
 * Work performance 
 */
function videocast_show_login() {
get_header();
?>
	<div class="page_container">
<div class="container_24">
<div class="grid_24">
<div class="page-content">
	<div class="grid_18 alpha">
		<div class="content-bar">
		<div class="upload-page">
			<?php
    global $posted;
    if (isset($_POST['register']) && $_POST['register']) {
        $result = videocast_reg_proceed_form();
        $errors = $result['errors'];
        $posted = $result['posted'];
    } elseif (isset($_POST['login']) && $_POST['login']) {
        $errors = videocast_login_proceed_form();
    }
    // Clear errors if loggedout is set.
    if (!empty($_GET['loggedout']))
        $errors = new WP_Error();
    // If cookies are disabled we can't log in even with a valid user+pass
    if (isset($_POST['testcookie']) && empty($_COOKIE[TEST_COOKIE]))
        $errors->add('test_cookie', TEST_COOKIE);
    if (isset($_GET['loggedout']) && TRUE == $_GET['loggedout'])
        $notify = 'You are now logged out.';
    elseif (isset($_GET['registration']) && 'disabled' == $_GET['registration'])
        $errors->add('registerdisabled', USR_REG_NT);
    elseif (isset($_GET['checkemail']) && 'confirm' == $_GET['checkemail'])
        $notify = CHK_EMAIL_CNF;
    elseif (isset($_GET['checkemail']) && 'newpass' == $_GET['checkemail'])
        $notify = CHK_EMAIL_PW;
    elseif (isset($_GET['checkemail']) && 'registered' == $_GET['checkemail'])
        $notify = REG_CPL_EMAIL;
    if(is_user_logged_in()){
        wp_redirect(site_url());
    }
    if ( is_user_logged_in() ) {
        global $wpdb, $current_user;
	$userRole = ($current_user->data->wp_capabilities);
	$role = key($userRole);
	unset($userRole);
	$edit_anchr = '';
	switch($role) {
		case ('administrator'||'editor'||'contributor'||'author'):			
		break;
		default:
		break;
	}
}
if (isset($notify) && !empty($notify)) {
	echo '<p class="success">' . $notify . '</p>';
}
?>
<?php
if (isset($errors) && sizeof($errors) > 0 && $errors->get_error_code()) :
	echo '<ul class="errors">';
	foreach ($errors->errors as $error) {
		echo '<li>' . $error[0] . '</li>';
	}
	echo '</ul>';
	endif;
	?>
	<?php
                    //call login form
                    videocast_login_form();
                    //call registration form
                    videocast_register_form(get_permalink($submitID));
					?>
							</div>
		</div>
	</div>
	<div class="grid_6 omega">
<?php get_sidebar(); ?>
</div>
</div>
</div>
<div class="clear"></div>
</div>
</div>				
<?php
get_footer();
}
function videocast_lost_pw() {
    ?>
    <div id="fotget_pw">
        <div class="line" style="margin-top: 15px; margin-bottom: 15px;"></div>
        <h3><?php echo FORGOT; ?></h3>        
        <form method="post" action="<?php echo site_url('wp-login.php?action=lostpassword', 'login_post') ?>" class="wp-user-form">
            <div class="row">
                <label for="user_login" class="hide"><?php echo NEW_EMAIL; ?></label><br/>               
                <input type="text" name="user_login" value="" size="20" id="user_login" />
            </div>
            <div class="row">
                <?php do_action('login_form', 'resetpass'); ?>
                <input type="submit" name="user-submit" value="get new password" class="user-submit" />
                <?php
                $reset = $_GET['reset'];
                if ($reset == true) {
                    echo '<p>' . A_MSG_ST_EMAIL . '</p>';
                }
                ?>
                <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>?reset=true" />
                <input type="hidden" name="user-cookie" value="1" />
            </div>
        </form>
    </div>
    <?php
}
global $pagenow;

// check to prevent php "notice: undefined index" msg
if (isset($_GET['action']))
    $theaction = $_GET['action']; else
    $theaction = '';

// if the user is on the login page, then let the games begin
if ($pagenow == 'wp-login.php' && $theaction != 'logout' && !isset($_GET['key'])) :
    add_action('init', 'videocast_login_init', 98);
endif;

/**
 * Function Name:  videocast_login_init
 * Description: This function gets the 
 * Page request and routes to 
 * Particular page
 */
function videocast_login_init() {
    nocache_headers(); //cache clear

    if (isset($_REQUEST['action'])) :
        $action = $_REQUEST['action'];
    else :
        $action = 'login';
    endif;
    switch ($action) :
        case 'add_place' :
            videocast_submit_place();
            break;
        case 'lostpassword' :
        case 'retrievepassword' :
            videocast_show_password();
            break;
        case 'register':
        case 'login':
        default:
            videocast_show_login();
            break;
    endswitch;
    exit;
}
/**
 * Function Name:  videocast_show_password
 * Description: This function creates
 * The forgot password page
 */
function videocast_show_password() {
    $errors = new WP_Error();
    if (isset($_POST['user_login']) && $_POST['user_login']) {
        $errors = retrieve_password();

        if (!is_wp_error($errors)) {
            wp_redirect('wp-login.php?checkemail=confirm');
            exit();
        }
    }

    if (isset($_GET['error']) && 'invalidkey' == $_GET['error'])
        $errors->add('invalidkey', SRY_KEY_VALID);

    do_action('lost_password');
    do_action('lostpassword_post');
//Call header.php
    get_header();
    ;
    ?>
    <!--Start Content Wrapper-->
    <div class="page_container">
  <div class="container_24">
    <div class="grid_24">
      <div class="page-content">
        <div class="grid_16 alpha">
            <div class="content">
                <h1>PassWord Recovery</h1>
                <?php
                if (isset($notify) && !empty($notify)) {
                    echo '<p class="success">' . $notify . '</p>';
                }
                ?>
                <?php
                if ($errors && sizeof($errors) > 0 && $errors->get_error_code()) :
                    echo '<ul class="errors">';
                    foreach ($errors->errors as $error) {
                        echo '<li>' . $error[0] . '</li>';
                    }
                    echo '</ul>';
                endif;
                ?>
            <?php videocast_lost_pw(); ?>  
            </div>
        </div>
       <div class="grid_6 omega">
          <!--Start Sidebar-->
          <?php get_sidebar(); ?>
           <!--End Sidebar-->
        </div>
    </div>
	</div>
	</div>
	</div>
    <!--End Content Wrapper-->
    <?php
//Call footer.php
    get_footer();
}
   /**
     * Show admin bar only for admins 
     */
    if (!current_user_can('manage_options')) {
        add_filter('show_admin_bar', '__return_false');
    }

?>
