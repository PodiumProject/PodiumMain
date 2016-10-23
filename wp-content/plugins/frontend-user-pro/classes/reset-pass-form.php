<?php
/*
If you would like to edit this file, copy it to your current theme's directory and edit it there.
WPUF will always look in your theme's directory first, before using this default template.
*/
?>
<div class="login" id="user-reset-form">

    <?php FRONTEND_USER()->login->show_errors(); ?>


	<form name="resetpasswordform" id="resetpasswordform" action="<?php echo site_url('login-form/?action=resetpass', 'login_post') ?>" class="displayform_class" method="post">
	    <?php FRONTEND_USER()->login->show_messages(); ?>
		<p>
			<!-- <label for="user-pass1"></label> -->
			<input autocomplete="off" name="pass1" id="user-pass1" class="input" size="20" value="" type="password" autocomplete="off" placeholder="<?php _e( 'New password' ); ?>" />
		</p>

		<p>
			<!-- <label for="user-pass2"><?php// _e( 'Confirm new password' ); ?></label> -->
			<input autocomplete="off" name="pass2" id="user-pass2" class="input" size="20" value="" type="password" autocomplete="off" placeholder="<?php _e( 'Confirm new password' ); ?>"/>
		</p>

		<?php do_action( 'resetpassword_form' ); ?>

		<p class="submit">
			<input type="submit" name="wp-submit" id="wp-submit" value="<?php esc_attr_e( 'Reset Password' ); ?>" />
			<input type="hidden" name="key" value="<?php echo FRONTEND_USER()->login->get_posted_value( 'key' ); ?>" />
			<input type="hidden" name="login" id="user_login" value="<?php echo FRONTEND_USER()->login->get_posted_value( 'login' ); ?>" />
			<input type="hidden" name="user_reset_password" value="true" />
		</p>

		<?php wp_nonce_field( 'user_reset_pass' ); ?>
	</form>
</div>
