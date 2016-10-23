<?php
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
$bpActive = is_plugin_active('buddypress/bp-loader.php');

global $wpdb;

if( !is_user_logged_in() ){

	if( $bpActive ){

		$urlRegister = get_option('bp-pages');
		$urlRegister = isset($urlRegister['register']) && is_numeric($urlRegister['register']) ? get_page_link($urlRegister['register']) : '';

	}else{

		$addPostId = $wpdb->get_results($wpdb->prepare("SELECT post_id, meta_value FROM ". esc_sql($wpdb->postmeta) ." WHERE meta_value = %s LIMIT 1", 'user-settings.php'), ARRAY_A);

		$urlRegister = isset($addPostId[0]['post_id']) ? get_page_link($addPostId[0]['post_id']) : esc_url( home_url( '/' ) );
	}

}
$currentUser = wp_get_current_user();
?>
<div class="col-lg-12 col-md-12 col-sm-12">
	<div class="ts-btn">
		<?php if( !is_user_logged_in() ): ?>
			<a class="ts-autentification" href="#"><i class="icon-login"></i> <?php esc_html_e('Login', 'videofly'); ?></a>
		<?php else: ?>
			<a class="ts-username" href="#"><i class="icon-user"></i> <?php echo esc_attr($currentUser->display_name) ?> <span class="icon-down"></span></a>
		<?php endif; ?>
	</div>
	<div class="ts-slidein-block">
		<div class="ts-toggle-icon"><i class="icon-close"></i></div>
		<?php if( !is_user_logged_in() ): ?>
			<div class="ts-register-form">
				<div class="row">
					<div class="col-lg-12">
						<form action="#" method="POST">
							<div class="ts-login-icon">
								<span class="ts-mini-avatar"><i class="icon-user"></i></span>
								<span class="user-title"><?php esc_html_e('User Login', 'videofly'); ?></span>
							</div>
							<div class="ts-login-name">
							<div class="inner-login-username">
								<span class="icon-user"></span>
								<input type="text" value="" placeholder="<?php esc_html_e('Username', 'videofly'); ?>" name="login">
							</div>
							<div class="inner-login-password">
								<span class="icon-lock"></span>
								<input type="password" value="" placeholder="<?php esc_html_e('Password', 'videofly'); ?>" name="password">
							</div>
								<?php esc_html_e('Remember me', ''); ?>
								<input type="checkbox" value="" name="rememberme">
							</div>
							<div class="ts-login-submit">
								<div class="row">
									<div class="ts-login-btn">
										<div class="col-lg-6">
											<?php wp_nonce_field('user-login', 'user-nonce', false) ?>
											<button type="submit" class="ts-send-log"><i class="icon-login"></i> <?php esc_html_e('LOGIN', 'videofly'); ?></button>
											<div class="ts-login-error"></div>
										</div>
									</div>
									<div class="ts-login-register">
										<div class="col-lg-6">
											<span><?php esc_html_e('or', 'videofly'); ?></span>
											<a href="<?php echo esc_url($urlRegister) ?>"><?php esc_html_e('Register', 'videofly'); ?></a>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		<?php else:
			$uploadUrl = '';
			$linksMenu = '';

			if( $bpActive ){

				global $tsLogin;

				if( $tsLogin == 'logged' ) do_action('bp_init');

				global $bp;

				if( is_object($bp) ){

					$linkToProfile = bp_loggedin_user_domain($currentUser->ID);
					$addPostId = $wpdb->get_results($wpdb->prepare("SELECT post_id, meta_value FROM ". esc_sql($wpdb->postmeta) ." WHERE meta_value = %s LIMIT 1", 'user-add-post.php'), ARRAY_A);

					$pageAddPost = isset($addPostId[0]['post_id']) ? get_page_link($addPostId[0]['post_id']) : esc_url( home_url( '/' ) );
					foreach( $bp->bp_nav as $page ){
						if( $page['screen_function'] == 'bp_activity_screen_my_activity' ) $linksMenu .= '<li class="ts-add-video"><a href="' . esc_url($pageAddPost) . '">' . esc_html__('Add video', 'videofly') . '</a></li>';						
						$linksMenu .= '<li class="'.$page['slug'].'"><a href="' . esc_url($page['link']) . '">' . $page['name'] . '</a></li>';
			
					}

				}

				/*if( is_plugin_active('buddypress-followers/loader.php') ){
			    	$tableName = esc_sql($bp->follow->global_tables['table_name']);
			        $users = $wpdb->get_results($wpdb->prepare("SELECT leader_id, COUNT(follower_id) AS nr_followers FROM $tableName GROUP BY $tableName.leader_id ORDER BY nr_followers DESC LIMIT %d", $nrOfUsers));
			    }*/

			} else {

				$pageIds = $wpdb->get_results($wpdb->prepare("SELECT post_id, meta_value FROM ". esc_sql($wpdb->postmeta) ." WHERE meta_value = %s OR meta_value = %s OR meta_value = %s", 'user-add-post.php', 'user-profile.php', 'user-settings.php'), ARRAY_A);

				$linkToProfile = get_author_posts_url($currentUser->ID);
				$pages = array();
				foreach( $pageIds as $page ){
					$linkTitle = $page['meta_value'] == 'user-add-post.php' ? esc_html__( 'Add video', 'videofly' ) :
								($page['meta_value'] == 'user-profile.php' ? esc_html__( 'Profile', 'videofly' ) :
								($page['meta_value'] == 'user-settings.php' ? esc_html__( 'Settings', 'videofly') : ''));
					$linkClass = ( $page['meta_value'] == 'user-add-post.php' ) ? 'ts-add-video' :'';

					$linksMenu .= '<li class="'.$linkClass.'"><a href="'. get_page_link($page['post_id']) .'">'. $linkTitle .'</a></li>';
					if( $page['meta_value'] == 'user-profile.php' ) {
						$linkToProfile = get_page_link($page['post_id']);
					}
					if( $page['meta_value'] == 'user-add-post.php' ) {
						$pageAddPost = get_page_link($page['post_id']);
					}
				}
			}

			$vdfPosts = new WP_Query(array('post_type' => 'video', 'author' => $currentUser->ID, 'posts_per_page' => 4, 'post_status' => 'publish'));

		?>
		<div class="ts-user-options">
			<div class="ts-user-menu style-left">
				<div class="row">
					<div class="col-lg-12">
						<a href="<?php echo vdf_var_sanitize($linkToProfile); ?>" class="ts-login-avatar"><?php echo get_avatar($currentUser->ID); ?>
							<div class="ts-dropdown username"><?php echo esc_attr($currentUser->display_name) ?></div>
							<a href="<?php echo vdf_var_sanitize($pageAddPost); ?>" class="ts-add-video icon-upload" title="<?php esc_html_e('Add new video', 'videofly'); ?>"></a>
						</a>
						<ul class="ts-dropdown-menu">
							<?php echo vdf_var_sanitize($linksMenu); ?>
							<li class="ts-logout"><a href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>"><?php esc_html_e('Logout', 'videofly'); ?></a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="ts-user-posts">
				<div class="ts-latest-fallowing">
					<div class="row">
						<div class="ts-section-name">
							<div class="col-lg-12">
								<h2><?php esc_html_e('My latest videos', 'videofly'); ?></h2>
							</div>
						</div>
						<div class="ts-userposts-gutter">
							<?php if( $vdfPosts->have_posts() ): ?>
								<?php while( $vdfPosts->have_posts() ): $vdfPosts->the_post(); ?>
									<div class="col-lg-6 col-md-6 col-sm-12">
										<article>
											<header>
												<a href="<?php the_permalink(); ?>">
													<div class="featured-image">
														<?php the_post_thumbnail(); ?>
													</div>
												</a>
											</header>
											<section>
												<h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
											</section>
										</article>
									</div>
								<?php endwhile; wp_reset_postdata(); ?>
							<?php else: ?>
								<?php esc_html_e('No recent posts.', 'videofly'); ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<div class="ts-create-playlist">
			    <a class="ts-form-toggle"> <span class="icon-sidebar"></span> <?php esc_html_e('New playlist', 'videofly'); ?></a>
			    <div class="ts-new-playlist">
			        <input type="text" name="vdf-name-playlist" value="">
			        <button class="vdf-save-playlist icon-tick" title="<?php esc_html_e('Save playlist', 'videofly'); ?>"></button>
			        <div class="vdf-response"></div>
			    </div>
			</div>
		</div>
		<?php endif; ?>
	</div>
</div>