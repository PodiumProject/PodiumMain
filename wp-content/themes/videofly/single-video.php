<?php

get_header();

global $wp_query;

$singleOptions = get_post_meta($post->ID, 'post_settings', true);

if( isset($singleOptions['single_layout_video']) && $singleOptions['single_layout_video'] == 'single_style2' ){
	get_template_part('single-video-2');
	return;
}

if ( have_posts() ): the_post();

	if (LayoutCompilator::sidebar_exists()) {

		$options = LayoutCompilator::get_sidebar_options();

		extract(LayoutCompilator::build_sidebar($options));

	} else {

		$content_class = 'col-lg-12';

	}

	$advertisings = get_option('videofly_theme_advertising');

	$generalSingle = get_option('videofly_single_post');
	$hideAuthorBox = !fields::logic($post->ID, 'post_settings', 'hide_author_box') && $generalSingle['display_author_box'] == 'n' ? 'y' : 'n';

	$videoMeta = get_post_meta($post->ID, 'ts-video', true);
	$nrShares = get_post_meta($post->ID, 'ts-social-count', true);

	$video = '';

	if ( isset($videoMeta['type']) && ($videoMeta['type'] == 'url' || $videoMeta['type'] == 'upload') ) {

		$playlist = isset($_GET['playlist']) ? sanitize_text_field($_GET['playlist']) : '';
		$video = vdf_advertisingVideo($advertisings, $post, $videoMeta, $playlist);

	} else if ( isset($videoMeta['type']) && $videoMeta['type'] == 'embed' ) {

		$video = $videoMeta['video'];

	} else {

		$video = esc_html__( 'No video.', 'videofly' );

	}

	?>

	<!-- Ad area 1 -->
	<?php if ( !empty($advertisings['ad_area_1']) ) : ?>
	<div class="container text-center ts-advertising-container">
		<?php echo vdf_var_sanitize($advertisings['ad_area_1']); ?>
	</div>
	<?php endif; ?>
	<!-- // End of Ad Area 1 -->

	<section id="main">
		<?php
		$breadcrumbs = get_option('videofly_single_post', array('breadcrumbs' => 'y'));
		if( $breadcrumbs['breadcrumbs'] === 'y' ): ?>
			<div class="ts-breadcrumbs breadcrumbs-single-video">
				<div class="container">
					<div class="row">
						<div class="col-md-12 col-lg-12">
							<?php  echo vdf_breadcrumbs(); ?>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>	
		<div class="container singular-container">
			<div class="row">
				<?php
					if (LayoutCompilator::sidebar_exists()) {
						$options = LayoutCompilator::get_sidebar_options();

						extract(LayoutCompilator::build_sidebar($options));

						if (LayoutCompilator::is_left_sidebar()) {
							echo vdf_var_sanitize($sidebar_content);
						}
					} else {
						$content_class = 'col-lg-12';
					}
				?>
				<?php if ( is_user_logged_in() || $generalSingle['log_video'] == 'Y' ) :
						$content_class .= isset($generalSingle['video_scroll']) && $generalSingle['video_scroll'] == 'Y' ? ' ts-video-small' : ''; ?>
					<div id="primary" >
						<div id="content" role="main" class="<?php echo vdf_var_sanitize($content_class); ?>">
							<article <?php post_class('ts-single-video'); ?>>
								<header class="post-header">
									<div class="ts-single">
											<div class="row">
												<div class="col-lg-12 col-md-12 col-sm-12">
													<div class="ts-video-before"></div>
													<div class="ts-video">
														<div id="videoframe" class="video-frame">
															<div class="video-container">
																<?php echo vdf_var_sanitize($video) ?>
															</div>
														</div>
													</div>
													<div class="ts-video-footer">
														<ul class="row">
															<li class="col-lg-9 col-md-9 col-sm-12">
																<ul class="entry-meta">
																	<li class="entry-user">
																		<?php if( $hideAuthorBox == 'y' ): ?>
																			<a href="<?php echo get_author_posts_url($post->post_author) ?>">
																				<span class="ts-avatar">
																					<?php echo get_avatar($post->post_author, 25); ?>
																				</span>
																				<span class="ts-name"><?php the_author(); ?></span>
																			</a>
																		<?php endif;

																		if( is_user_logged_in() ) :

																			if( function_exists('bp_follow_add_follow_button') && function_exists('bp_loggedin_user_id') ){
																				if( (int)get_the_author_meta('ID') !== (int)bp_loggedin_user_id() ){

																					bp_follow_add_follow_button(array('leader_id' => $post->post_author, 'follower_id' => bp_loggedin_user_id()));
																				}
																			}

																			if( function_exists('bp_follow_total_follow_counts') ) : ?>
																				<span class="ts-follow-count">
																					<?php $authorFollow = bp_follow_total_follow_counts(array('user_id' => get_the_author_meta('ID') ) );
																						if( isset($authorFollow['followers']) ) echo vdf_var_sanitize($authorFollow['followers']);
																					?>
																					<?php esc_html_e('followers','slimvideo'); ?>
																				</span>
																			<?php endif;
																		else :

																			if ( is_plugin_active('buddypress/bp-loader.php') ) {

																				$page_ids = bp_get_option( 'bp-pages' );
																				$register = isset($page_ids['register']) ? get_page_link($page_ids['register']) : '';

																				echo '<a href="'. $register .'"><span class="ts-follow-btn">'. esc_html__('Follow', 'videofly') .'</span></a>';
																			}

																		endif; ?>
																	</li>
																<?php if( !fields::logic($post->ID, 'post_settings', 'hide_meta') && vdf_single_display_meta() ): ?>
																	<?php touchsize_likes($post->ID, '<li class="entry-likes">', '</li>'); ?>
																	<li class="ts-share"><span class="icon-share"><?php echo (empty($nrShares) ? 0 : $nrShares); ?></span></li>
																	<?php vdf_get_views($post->ID, '<li class="ts-views"><span class="icon-views">', '</span></li>'); ?>
																<?php endif; ?>
																	<li class="ts-light"><a href="#" class="light-off"><span class="icon-lamp"><?php esc_html_e('Light Off', 'videofly'); ?></span></a></li>

																	<?php if ( isset($generalSingle['download']) && $generalSingle['download'] == 'Y' && $videoMeta['type'] == 'upload' ) : ?>
																		<li class="ts-download light">
																			<a href="<?php echo esc_url($videoMeta['video']) ?>" target="_blank">
																				<?php esc_html_e('Download video', 'videofly'); ?>
																			</a>
																		</li>
																	<?php endif; ?>
																</ul>
															</li>
															<li class="col-lg-3 col-md-3 col-sm-12">
																<div class="ts-favorites">
																	<?php if( is_user_logged_in() ) : ?>
																		<?php $favoriteIds = get_user_meta(get_current_user_id(), 'favoritePosts', true); ?>
																		<input type="hidden" value="<?php echo get_the_ID(); ?>" name="ts-post-video-id" id="ts-post-video-id">
																		<?php
																		if ( is_array($favoriteIds) && !in_array($post->ID, $favoriteIds) ) {
																			$addToFavorite = '';
																			$removeFromFavorite = ' hidden';
																		} else {
																			$addToFavorite = ' hidden';
																			$removeFromFavorite = '';
																		} ?>
																		<a href="#" data-action="add" class="ts-display-favorite ts-add-favorite<?php echo vdf_var_sanitize($addToFavorite); ?>">
																			<span class="icon-star">
																			<?php esc_html_e('Favorite', 'videofly'); ?>
																			</span>
																		</a>
																		<a href="#" data-action="remove" class="ts-remove-favorite ts-add-favorite<?php echo vdf_var_sanitize($removeFromFavorite); ?>">
																			<span class="icon-star">
																			<?php esc_html_e('Remove from favorite', 'videofly'); ?>
																			</span>
																		</a>
																	<?php else : ?>
																		<a href="#" class="ts-notlogin-favorite" data-alert="<?php esc_html_e( 'You should be logged', 'videofly' ); ?>">
																			<span class="icon-star">
																				<?php esc_html_e('Add to favorite', 'videofly'); ?>
																			</span>
																		</a>
																	<?php endif; ?>
																</div>
															</li>
														</ul>
													</div>
												</div>
											</div>
									</div>
								</header>
								<section>
									<div class="row">
									<?php if( !fields::logic($post->ID, 'post_settings', 'hide_meta') && vdf_single_display_meta() ): ?>
										<div class="col-lg-8 col-md-8">
											<?php echo get_the_term_list($post->ID, 'videos_categories', '<div><ul class="single-category"><li>', '</li><li>', '</li></ul></div>'); ?>
										</div>
										<div class="col-lg-4 col-md-4">
											<span class="entry-meta-day"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) .' '. esc_html__('ago', 'videofly'); ?></span>
										</div>
										<?php endif; ?>
										<div class="col-lg-12 col-md-12">
										<?php if ( !fields::logic($post->ID, 'post_settings', 'hide_title') ): ?>
											<h1 class="post-title"><?php echo esc_attr($post->post_title); ?></h1>
										<?php endif; ?>
										</div>
									</div>
									<div class="entry-excerpt hidden-excerpt">
										<?php the_content(); ?>
										<?php if( !fields::logic($post->ID, 'post_settings', 'hide_meta') && vdf_single_display_tags()) : ?>
										<?php echo get_the_tag_list('<div><i class="icon-tags"></i><ul class="single-tags"><li>', '</li><li>', '</li></ul></div>'); ?>
										<?php endif; ?>
									</div>
									<span class="ts-show-btn"><a href="#"><?php esc_html_e('SHOW MORE', 'videofly'); ?></a></span>
								</section>
								<footer>
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-12">
											<?php if ( !fields::logic($post->ID, 'post_settings', 'hide_social_sharing')  && vdf_single_social_sharing() ) : ?>
												<?php get_template_part('social-sharing'); ?>
											<?php endif; ?>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12">
											<ul class="entry-embed-links">
												<?php if ( is_user_logged_in() ) : ?>
													<li data-modal="ts-send-tofriend">
														<a href="#" class="icon-mail"></a>
													</li>
												<?php endif; ?>
												<li data-modal="ts-embed-code">
													<a href="#" class="icon-code"></a>
												</li>
												<li data-modal="ts-link-code">
													<a href="#" class="icon-link"></a>
												</li>
												<?php if( is_user_logged_in() ): ?>
													<li data-modal="ts-modal-playlists">
														<a href="#" class="icon-sidebar"></a>
													</li>
												<?php endif; ?>
											</ul>
											<?php get_template_part('single-modals'); ?>
										</div>
									</div>
								</footer>
							</article>
						</div>
					</div>
				<?php else : ?>
					<div class="<?php echo esc_html( $content_class ); ?>">
						<?php echo LayoutCompilator::user_element( array( 'align' => 'center' ) ); ?>
					</div>
				<?php endif; ?>
				<?php
					if (LayoutCompilator::sidebar_exists()) {
						if (LayoutCompilator::is_right_sidebar('single')) {
							echo vdf_var_sanitize($sidebar_content);
						}
					}
				?>
			</div>
		</div>
		<?php
			// Show the next and previous links
			vdf_get_pagination_next_previous();
		?>

		<!-- Ad area 2 -->
		<?php if ( !empty($advertisings['ad_area_2']) ) : ?>
		<div class="container text-center ts-advertising-container">
			<?php echo vdf_var_sanitize($advertisings['ad_area_2']); ?>
		</div>
		<?php endif; ?>
		<!-- // End of Ad Area 2 -->

		<div class="container single-video-comments">
			<div class="row content-block">
				<div class="col-lg-12">
					<?php comments_template( '', true ); ?>
				</div>
			</div>
		</div>

		<?php if (!fields::logic($post->ID, 'post_settings', 'hide_related') && vdf_single_display_related() ): ?>
			<div class="ts-related-video-container">
				<div class="container">
					<div class="row">
						<div class="col-lg-12">
							<h4 class="related-title"><?php esc_html_e('Related posts', 'videofly'); ?></h4>
						</div>
						<?php echo LayoutCompilator::get_single_related_posts(get_the_ID()); ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</section>
<?php endif; ?>
<?php get_footer(); ?>