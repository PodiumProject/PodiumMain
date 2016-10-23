<?php
/*
Template Name: Frontend - User settings && register
*/
$checked = vdf_updateRegisterUser();

get_header();

global $current_user;

$description = isset($checked['ts-description']) ? $checked['ts-description'] : (isset($current_user->ID) ? get_the_author_meta('description', $current_user->ID) : '');
$register = !is_user_logged_in() ? get_option('users_can_register') : 1;
$login = isset($checked['ts-login']) ? $checked['ts-login'] : (isset($current_user->user_login) ? $current_user->user_login : '');
$email = isset($checked['ts-email']) ? $checked['ts-email'] : (isset($current_user->user_email) ? $current_user->user_email : '');
$userName = isset($checked['ts-displayname']) ? $checked['ts-displayname'] : (isset($current_user->display_name) ? $current_user->display_name : '');
$userSite = isset($checked['ts-url']) ? $checked['ts-url'] : (isset($current_user->user_url) ? $current_user->user_url : '');

?>
<section id="main" class="user-settings-page">
	<div class="row">
		<div class="container">
			<?php if( isset($checked['error']) || isset($checked['success']) ): ?>
				<div class="ts-alert" style="color: <?php echo (isset($checked['error']) ? '#FF0000' : '#339b62') ?>;background-color: <?php echo (isset($checked['error']) ? 'E8CFCF' : '#f5fcf8') ?>;margin-bottom: 40px;">
	                <span class="alert-icon"><i class="icon-ok-full"></i></span>
	                <div class="right-side">
	                    <span class="alert-title"><h3 class="title"><?php echo (isset($checked['success']) ? $checked['success'] : $checked['error']); ?></h3></span>
	                 </div>
	            </div>
			<?php endif; ?>
			<div class="row">
				<div class="col-sm-12 col-md-6 col-lg-6">
					<?php if( intval($register) == 1 ): ?>
						<form enctype="multipart/form-data" method="post">
						    <div class="form-group">
						        <label for="ts-login" class=""><?php esc_html_e('Your Login', 'videofly'); ?></label>
						        <input<?php echo (is_user_logged_in() ? ' disabled' : ''); ?> class="form-control" type="text" name="ts-login" id="ts-login" value="<?php echo esc_attr($login); ?>" placeholder="<?php esc_html_e('Your Login', 'videofly'); ?>"/>
						    </div>

						    <div class="form-group">
						        <label for="ts-email"><?php esc_html_e('Your Email', 'videofly'); ?></label>
						        <input class="form-control" type="email" name="ts-email" id="ts-email" value="<?php echo esc_attr($email); ?>" required/>
						    </div>

						    <div class="form-group">
						        <label for="ts-username"><?php esc_html_e('Choose Username', 'videofly'); ?></label>
						        <input class="form-control" type="text" name="ts-displayname" id="ts-username" value="<?php echo esc_attr($userName); ?>" placeholder="<?php esc_html_e('Choose Username', 'videofly'); ?>" required/>
						        <span class="help-block"><?php esc_html_e('Please use only a-z,A-Z,0-9,dash and underscores, minimum 5 characters', 'videofly'); ?></span>
						    </div>

						    <div class="form-group">
						        <label for="ts-pass"><?php esc_html_e('Choose Password', 'videofly'); ?></label>
						        <input class="form-control" type="password" name="ts-pass" id="ts-pass" value="" placeholder="<?php esc_html_e('Choose Password', 'videofly'); ?>"/>
						        <span class="help-block"><?php esc_html_e('Minimum 5 characters', 'videofly'); ?></span>
						    </div>
						    <div id="ts-notconfirm" class="hidden">
						    	<?php esc_html_e('Your password and confirmation password do not match.', 'videofly'); ?>
						    </div>
						    <div id="ts-confirm" class="hidden">
						    	<?php esc_html_e('Passwords Match!', 'videofly'); ?>
						    </div>
						    <div class="form-group">
						        <label for="ts-pass"><?php esc_html_e('Confirm your password', 'videofly'); ?></label>
						        <input class="form-control" type="password" name="ts-pass-confirm" id="ts-pass-confirme" value="" placeholder="<?php esc_html_e('Confirm your password', 'videofly'); ?>"/>
						        <span class="help-block"><?php esc_html_e('Minimum 5 characters', 'videofly'); ?></span>
						    </div>

						    <div class="form-group">
						        <label for="ts-description"><?php esc_html_e('Add your description', 'videofly'); ?></label>
						        <textarea class="form-control" rows="5" name="ts-description" id="ts-description" placeholder="<?php esc_html_e('Choose Description', 'videofly'); ?>"><?php echo vdf_var_sanitize($description); ?></textarea>
						        <span class="help-block"><?php esc_html_e('Minimum 5 characters', 'videofly'); ?></span>
						    </div>
						    <div class="form-group">
						        <label for="ts-url"><?php esc_html_e('Add your site url', 'videofly'); ?></label>
						        <input class="form-control" type="text" name="ts-url" id="ts-url" value="<?php echo esc_url($userSite); ?>" placeholder="<?php esc_html_e('Add your site url here', 'videofly'); ?>">
						        <span class="help-block"><?php esc_html_e('Add your site here', 'videofly'); ?></span>
						    </div>
						    <div class="form-group">
						    	<?php vdf_captcha() ?>
						    </div>
						    <?php wp_nonce_field('ts_update_user', 'ts_update_user_nonce'); ?>
						    <input type="hidden" name="user-action" value="<?php echo (is_user_logged_in() ? 'update' : 'register'); ?>">
						    <input type="submit" name="update-user" class="btn btn-primary active medium" id="ts-btn-update-user" value="<?php echo (is_user_logged_in() ? esc_html__('Update', 'videofly') : esc_html__('Register', 'videofly')) ?>" />
						</form>
				<?php else: ?>
					<?php esc_html_e('Register in this site is disabled.', 'videofly'); ?>
				<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>
<?php
get_footer();
?>