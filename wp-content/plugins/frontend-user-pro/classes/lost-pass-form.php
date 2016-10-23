<?php
/*
If you would like to edit this file, copy it to your current theme's directory and edit it there.
WPUF will always look in your theme's directory first, before using this default template.
*/
?>

<div class="login" id="user-login-form">
	<?php
	FRONTEND_USER()->login->show_errors();
	FRONTEND_USER()->login->show_messages();
	?>
	<form name="lostpasswordform" id="lostpasswordform" action="<?php echo site_url('login-form/?action=checkemail', 'login_post') ?>" method="post">
		<p>
			<label for="user-user_login"><?php _e( 'Username or E-mail:' ); ?></label>
			<input type="text" name="user_login" id="user-user_login" class="input" value="" size="20" />
		</p>
		<?php do_action( 'lostpassword_form' ); ?>
		<p class="submit">
			<input type="submit" name="wp-submit" id="wp-submit" value="<?php esc_attr_e( 'Get New Password' ); ?>" />
			<input type="hidden" name="redirect_to" value="<?php echo FRONTEND_USER()->login->get_posted_value( 'redirect_to' ); ?>" />
			<input type="hidden" name="user_reset_password" value="true" />
			<input type="hidden" name="action" value="lostpassword" />
			<?php wp_nonce_field( 'user_lost_pass' ); ?>
		</p>
		<div class="login_reg_link"><?php echo FRONTEND_USER()->login->user_get_action_links( array( 'lostpassword' => false,'register' => false ) ); ?></div>
	</form>
	
</div>
