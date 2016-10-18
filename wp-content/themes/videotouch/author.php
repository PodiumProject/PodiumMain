<?php
get_header();
global $wp_query;

$author = get_user_by( 'slug', get_query_var( 'author_name' ) );
$user 	= get_userdata($author->ID);

if( isset($user) && is_object($user) && !empty($user) ) :

	$options = get_option('videotouch_layout');
	
	$sidebar_options = $options['author_layout']['sidebar'];
	$view_type = $options['author_layout']['display-mode'];
	$options_layout = $options['author_layout'][$view_type];
	$options_layout['display-mode'] = $view_type;
	$options_layout['edit'] = true;
	$options_layout['author'] = $user->ID;
	$options_layout['pagination'] = 'n';
	
	extract(layoutCompilator::build_sidebar( $sidebar_options ));

	$sidebar_split = '';
	if( isset($sidebar_options['position'], $sidebar_options['id'], $sidebar_options['size']) && $sidebar_options['position'] !== 'none' && $sidebar_options['id'] !== '0' ){
		$sidebar_split = ($sidebar_options['size'] == '1-3') ? ' class="col-lg-8 col-md-8"' : (($sidebar_options['size'] == '1-4') ? ' class="col-lg-9 col-md-9"' : '');	
	}

	$login_author    = (isset($user->data->user_login) && is_string($user->data->user_login)) ? $user->data->user_login : '';
	$user_nicename   = (isset($user->data->user_nicename) && is_string($user->data->user_nicename)) ? $user->data->user_nicename : '';
	$email           = (isset($user->data->user_email) && is_string($user->data->user_email)) ? $user->data->user_email : '';
	$site_url        = (isset($user->data->user_url) && is_string($user->data->user_url)) ? $user->data->user_url : '';
	$data_registered = (isset($user->data->user_registered) && is_string($user->data->user_registered)) ? human_time_diff(strtotime($user->data->user_registered)).' '.__('ago', 'touchsize') : '';

	$posts           = new WP_Query(array('posts_per_page' => -1, 'author' => $user->ID, 'post_type' => 'post'));
	$count_posts     = (is_object($posts) && isset($posts->post_count) && !empty($posts->post_count)) ? $posts->post_count : 0;
	$videos          = new WP_Query(array('posts_per_page' => -1, 'author' => $user->ID, 'post_type' => 'video'));
	$count_videos    = (is_object($videos) && isset($videos->post_count) && !empty($videos->post_count)) ? $videos->post_count : 0;
	$description     = get_user_meta($user->ID, 'description', true);

?>
	<section id="main" class="user-profile-page">
		<div class="row">
			<div class="container">
				<?php if( isset($sidebar_options['position'], $sidebar_content, $sidebar_options['id']) && ($sidebar_options['position'] === 'left' || $sidebar_options['position'] === 'right') && $sidebar_options['id'] !== '0' ) echo '<div class="row">'; ?>
					<?php if( isset($sidebar_options['position'], $sidebar_content) && $sidebar_options['position'] === 'left' ) echo $sidebar_content; ?> 
					<div<?php echo $sidebar_split; ?>>
						<div class="row">
							<div class="ts-user-content-header">
								<div class="col-md-12 col-lg-12">
									<div class="user-avatar">
										<?php echo ts_display_gravatar(280); ?>
									</div>
									<div class="user-info">
										<a href="#" title=""><h3 class="title"><?php echo $user_nicename; ?></h3></a>
										<div class="user-activity">
											<ul>
												<li><span class="text-uppercase"><?php _e('Videos', 'touchsize'); ?></span><strong><?php echo $count_videos; ?></strong></li>
												<li><span class="text-uppercase"><?php _e('Posts', 'touchsize'); ?></span><strong><?php echo $count_posts; ?></strong></li>
											</ul>
										</div>
										<div class="user-meta">
											<ul>
												<?php 
													if (isset($site_url) && !empty($site_url)) {
														echo '<li><a href="'.$site_url.'" title=""><i class="icon-social"></i>'.$site_url.'</a></li>';
													}
													if (isset($data_registered) && !empty($data_registered)) {
														echo '<li><i class="icon-post"></i>'.$data_registered.'</li>';
													}
												?>
											</ul>
										</div>
										<div class="clearfix"></div>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-12">
								<div class="user-description">
									<?php echo $description; ?>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-lg-12">
								<div class="ts-tab-container">
									<ul class="nav user-tabs nav-tabs" role="tablist">
									  	<li class="active"><a href="#posts" role="tab" data-toggle="tab"><?php _e('Posts', 'touchsize'); ?></a></li>
									  	<li><a href="#videos" role="tab" data-toggle="tab"><?php _e('Videos', 'touchsize'); ?></a></li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane active" id="posts">
											<div class="row">
											  	<?php if ( $posts->have_posts() ) : ?>
											  		<?php echo LayoutCompilator::last_posts_element($options_layout, $posts); ?>
											  	<?php else : ?>
											  	<p><?php _e('No found posts', 'touchsize'); ?></p> 
											  	<?php endif; ?>
											</div>
										</div>
									  	<div class="tab-pane" id="videos">
									  		<div class="row">
												<?php if ( $videos->have_posts() ) : ?>
													<?php echo LayoutCompilator::list_videos_element($options_layout, $videos); ?>
												<?php else : ?>
												<p><?php _e('No found posts', 'touchsize'); ?></p> 
												<?php endif; ?>
									  		</div>
									 	</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php if( isset($sidebar_options['position'], $sidebar_content) && $sidebar_options['position'] === 'right' ) echo $sidebar_content; ?>
				<?php if( isset($sidebar_options['position'], $sidebar_content, $sidebar_options['id']) && ($sidebar_options['position'] === 'left' || $sidebar_options['position'] === 'right') && $sidebar_options['id'] !== '0' ) echo '</div>'; ?>
			</div>
		</div>
	</section> 	
<?php
endif;
get_footer();
?>