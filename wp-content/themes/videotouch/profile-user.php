<?php
/*
Template Name: Frontend - My Profile
*/ 
if( !is_user_logged_in() ){
	wp_redirect(home_url());
	exit;
}
get_header();

$user = wp_get_current_user();
$login_author    = (isset($user->data->user_login) && is_string($user->data->user_login)) ? $user->data->user_login : '';
$user_nicename   = (isset($user->data->user_nicename) && is_string($user->data->user_nicename)) ? $user->data->user_nicename : '';
$email           = (isset($user->data->user_email) && is_string($user->data->user_email)) ? $user->data->user_email : '';
$site_url        = (isset($user->data->user_url) && is_string($user->data->user_url)) ? $user->data->user_url : '';
$data_registered = (isset($user->data->user_registered) && is_string($user->data->user_registered)) ? human_time_diff(strtotime(get_the_time('Y-m-d H:i:s'))).' '.__('ago', 'touchsize') : '';

$description     = get_user_meta($user->ID, 'description', true); 

?>
<section id="main" class="user-profile-page">
	<div class="row">
		<div class="container">
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
									<li><span class="text-uppercase"><?php _e('Videos', 'touchsize'); ?></span><strong><?php echo count_user_posts($user->ID, 'video') ?></strong></li>
									<li><span class="text-uppercase"><?php _e('Posts', 'touchsize'); ?></span><strong><?php echo count_user_posts($user->ID, 'post') ?></strong></li>
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
									<?php $ts_frontend_posts = new WP_Query(array('posts_per_page' => -1, 'author' => $user->ID, 'post_type' => 'post')); ?>
								  	<?php if ( $ts_frontend_posts->have_posts() ) : ?>
								  		<?php echo LayoutCompilator::last_posts_element(array('display-mode' => 'thumbnails', 'elements-per-row' => 4, 'order-direction' => 'DESC', 'order-by' => 'Date', 'posts-limit' => -1, 'pagination' => 'n', 'author' => $user->ID, 'edit' => true), $ts_frontend_posts); ?>
								  	<?php else : ?>
								  	<p><?php _e('No found posts', 'touchsize'); ?></p> 
								  	<?php endif; ?>
								  	<?php wp_reset_postdata(); ?>
								</div>
							</div>
						  	<div class="tab-pane" id="videos">
						  		<div class="row">
						  			<?php $ts_frontend_videos = new WP_Query(array('posts_per_page' => -1, 'author' => $user->ID, 'post_type' => 'video')); ?>
									<?php if ( $ts_frontend_videos->have_posts() ) : ?>
										<?php echo LayoutCompilator::list_videos_element(array('display-mode' => 'thumbnails', 'elements-per-row' => 4, 'order-direction' => 'DESC', 'order-by' => 'Date', 'posts-limit' => -1, 'pagination' => 'n', 'author' => $user->ID, 'edit' => true), $ts_frontend_videos); ?>
									<?php else : ?>
									<p><?php _e('No found posts', 'touchsize'); ?></p> 
									<?php endif; ?>
									<?php wp_reset_postdata(); ?>
						  		</div>
						 	</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>	
<?php
get_footer();
?>