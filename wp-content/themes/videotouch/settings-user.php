<?php
/*
Template Name: Frontend - User settings
*/
if( !is_user_logged_in()){
	wp_redirect(home_url());
	exit;
} 
get_header();

$user = wp_get_current_user();

$login           = get_the_author_meta('user_login', $user->ID);
$user_nickname   = get_the_author_meta('nickname', $user->ID);
$email           = get_the_author_meta('user_email', $user->ID);
$site_url        = get_the_author_meta('user_url', $user->ID);
$username        = get_the_author_meta('display_name', $user->ID);
$description     = get_the_author_meta('description', $user->ID); 
	
?>
<section id="main" class="user-settings-page">
	<div class="row">
		<div class="container">
			<div class="row">
				<div class="col-sm-12 col-md-6 col-lg-6">
					<form enctype="multipart/form-data" method="post">
					    <div class="form-group">
					        <label for="ts-name" class=""><?php _e('Your Login', 'touchsize'); ?></label>
					        <input disabled class="form-control" type="text" name="ts-login" id="ts-login" value="<?php echo $login; ?>" placeholder="<?php _e('Your Login', 'touchsize'); ?>"/>
					    </div>

					    <div class="form-group">
					        <label for="ts-email"><?php _e('Your Email', 'touchsize'); ?></label>
					        <input class="form-control" type="email" name="ts-email" id="ts-email" value="<?php echo $email; ?>" placeholder="<?php _e('Your Email', 'touchsize'); ?>"/>
					    </div>

					    <div class="form-group">
					        <label for="ts-nick"><?php _e('Your Nickname', 'touchsize'); ?></label>
					        <input class="form-control" type="text" name="ts-nick" id="ts-nick" value="<?php echo $user_nickname; ?>" placeholder="<?php _e('Your Nickname', 'touchsize'); ?>"/>
					    </div>

					    <div class="form-group">
					        <label for="ts-username"><?php _e('Choose Username', 'touchsize'); ?></label>
					        <input class="form-control" type="text" name="ts-username" id="ts-username" value="<?php echo $username; ?>" placeholder="<?php _e('Choose Username', 'touchsize'); ?>"/>
					        <span class="help-block"><?php _e('Please use only a-z,A-Z,0-9,dash and underscores, minimum 5 characters', 'touchsize'); ?></span>
					    </div>

					    <div class="form-group">
					        <label for="ts-pass"><?php _e('Choose Password', 'touchsize'); ?></label>
					        <input class="form-control" type="password" name="ts-pass" id="ts-pass" value="" placeholder="<?php _e('Choose Password', 'touchsize'); ?>"/>
					        <span class="help-block"><?php _e('Minimum 5 characters', 'touchsize'); ?></span>
					    </div>
					    <div id="ts-notconfirm" class="hidden">
					    	<?php _e('Your password and confirmation password do not match.', 'touchsize'); ?>
					    </div>
					    <div id="ts-confirm" class="hidden">
					    	<?php _e('Passwords Match!', 'touchsize'); ?>
					    </div>
					    <div class="form-group">
					        <label for="ts-pass"><?php _e('Confirm your password', 'touchsize'); ?></label>
					        <input class="form-control" type="password" name="ts-pass-confirm" id="ts-pass-confirme" value="" placeholder="<?php _e('Confirm your password', 'touchsize'); ?>"/>
					        <span class="help-block"><?php _e('Minimum 5 characters', 'touchsize'); ?></span>
					    </div>

					    <div class="form-group">
					        <label for="ts-description"><?php _e('Add your description', 'touchsize'); ?></label>
					        <textarea class="form-control" rows="5" name="ts-description" id="ts-description" placeholder="<?php _e('Choose Description', 'touchsize'); ?>"><?php echo $description; ?></textarea>
					        <span class="help-block"><?php _e('Minimum 5 characters', 'touchsize'); ?></span>
					    </div>
					    <div class="form-group">
					        <label for="ts-url"><?php _e('Add your site url', 'touchsize'); ?></label>
					        <input class="form-control" type="text" name="ts-url" id="ts-url" value="<?php echo $site_url; ?>" placeholder="<?php _e('Add your site url here', 'touchsize'); ?>">
					        <span class="help-block"><?php _e('Add your site here', 'touchsize'); ?></span>
					    </div>
					    <?php wp_nonce_field('ts_update_user', 'ts_update_user_nonce'); ?>
					    <input type="submit" name="update-user" class="btn btn-primary active medium" id="ts-btn-update-user" value="<?php _e('Update', 'touchsize') ?>" />
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
<?php
get_footer();
?>