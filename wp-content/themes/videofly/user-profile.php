<?php
/*
Template Name: Frontend - My Profile
*/
if( !is_user_logged_in() ){
	wp_redirect( esc_url( home_url( '/' ) ) );
	exit;
}
get_header();
global $current_user;

$description     = get_user_meta($current_user->ID, 'description', true);

?>
<section id="main" class="user-profile-page">
	<div class="row">
		<div class="container">
			<div class="row">
				<div class="ts-user-content-header">
					<div class="col-md-12 col-lg-12">
					<div class="row">
						<div class="col-lg-2 col-md-2 user-avatar">
							<?php echo get_avatar($current_user->user_email, 280); ?>
						</div>
						<div class="user-info col-lg-10 col-md-10">
							<a href="#" title=""><h3 class="title"><?php echo esc_attr($current_user->user_nicename); ?><span class="videos-count">(<?php echo count_user_posts($current_user->ID, 'video') ?> <?php esc_html_e('Videos', 'videofly'); ?>)</span></h3></a>
							<div class="user-meta">
								<ul>
									<?php
										if ( !empty($current_user->user_url) ) {
											echo '<li><a href="'. $current_user->user_url .'" title=""><i class="icon-social"></i>'. $current_user->user_url .'</a></li>';
										}
										if ( !empty($current_user->user_registered) ) {
											echo esc_html_e('registered', 'videofly').' '. human_time_diff(time(), strtotime($current_user->user_registered)) .' '.esc_html__('ago', 'videofly') .'</li>';
										}
									?>
								</ul>
							</div>
							<div class="user-description">
								<?php echo vdf_var_sanitize($description); ?>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div><!-- /.row -->
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 col-lg-12">
					<div class="ts-tab-container">
						<ul class="nav user-tabs nav-tabs" role="tablist">
						  	<li class="active"><a href="#favorites" role="tab" data-toggle="tab"><?php esc_html_e('Favorites', 'videofly'); ?></a></li>
						  	<li><a href="#videos" role="tab" data-toggle="tab"><?php esc_html_e('Videos added', 'videofly'); ?></a></li>
						  	<li><a href="#playlists" role="tab" data-toggle="tab"><?php esc_html_e('Playlists', 'videofly'); ?></a></li>
						</ul>
						<div class="tab-content">
						  	<div class="tab-pane active" id="favorites">
						  		<div class="row">
						  		    <?php
						  		    $favoritesPostIds = get_user_meta($current_user->ID, 'favoritePosts', true);
						  		    if ( is_array($favoritesPostIds) && !empty($favoritesPostIds) ) {
						  		    	$options = array();
						  		    	$options['display-mode'] = 'thumbnails';
						  		    	$options['elements-per-row'] = 3;
						  		    	$options['show-meta'] = 'Y';
						  		    	$options['behavior'] = 'normal';
						  		    	$options['display-title'] = 'title-over-image';

						  		    	$args = array(
						  		    	    'post_type' => 'video',
						  		    	    'post__in' => $favoritesPostIds
						  		    	);

						  		    	$query = new WP_Query($args);

						  		    	echo LayoutCompilator::list_videos_element($options, $query);
						  		    } else {
						  		    	echo '<p>'. esc_html__('No found posts', 'videofly') .'</p>';
						  		    }
						  		    wp_reset_postdata();
						  		    ?>
						  		</div>
						 	</div>
							<div class="tab-pane" id="videos">
								<div class="row">
						  			<?php $ts_frontend_videos = new WP_Query(array('posts_per_page' => -1, 'author' => $current_user->ID, 'post_type' => 'video')); ?>
									<?php if ( $ts_frontend_videos->have_posts() ) : ?>
										<?php echo LayoutCompilator::list_videos_element(array('display-mode' => 'thumbnails', 'elements-per-row' => 4, 'order-direction' => 'DESC', 'order-by' => 'Date', 'posts-limit' => -1, 'pagination' => 'n', 'author' => $current_user->ID, 'edit' => true), $ts_frontend_videos); ?>
									<?php else : ?>
									<p><?php esc_html_e('No found posts', 'videofly'); ?></p>
									<?php endif; ?>
									<?php wp_reset_postdata(); ?>
								</div>
							</div>
							<div class="tab-pane" id="playlists">
						  			<?php
						  			global $userdata;

						  			$userId = $userdata->ID;
						  			$playlists = get_user_meta($userId, 'vdf-playlists', true);
						  			$postIds = array();

						  			array_walk_recursive($playlists, function($value, $key) use (&$postIds) {
						  				if ( $key == 'postId' ) {
						  					$postIds[] = $value;
						  				}
						  			});

						 			$query = get_posts(array('post__in' => $postIds, 'post_type' => 'video', 'posts_per_page' => 9999));
						 			?>
							        <div class="ts-create-playlist">
							            <a class="ts-form-toggle"> <span class="icon-sidebar"></span> <?php esc_html_e('New playlist', 'videofly'); ?></a>
							            <div class="ts-new-playlist">
							                <input type="text" name="vdf-name-playlist" value="">
							                <button class="vdf-save-playlist icon-tick" title="<?php esc_html_e('Save playlist', 'videofly'); ?>"></button>
							                <div class="vdf-response"></div>
							            </div>
							        </div>
						 			<?php 

						 			foreach ( $playlists as $playlistId => $playlist ) {
						 				echo '<div class="row">';
						 				echo '<div class="vdf-playlist-item col-lg-12">
						 						<header>
							 						<h4>'. $playlist['name'] .'<span>( '.count($playlist['videos']).' videos )</span></h4>
								 					<button class="vdf-remove-playlist icon-delete" data-action="playlist" data-playlistid="'. $playlistId .'" title="'.esc_html__('Remove playlist', 'videofly') .'"></button>
								 					<div class="vdf-response"></div>
								 				</header>';

						 				if ( empty($playlist['videos']) || empty($query) ) {
						 					echo esc_html__('Playlist is empty.', 'videofly');
						 					echo '</div></div>';
						 					continue;
						 				}
						 				echo '<section class="row ts-thumbnail-view cols-by-4">';
						 				foreach ( $playlist['videos'] as $videoPlay ) {
						 					foreach ( $query as $videoPost ) {

						 						if ( $videoPlay['postId'] !== $videoPost->ID ) continue;

						 						$link = add_query_arg('playlist', $playlistId, get_the_permalink($videoPost->ID));

						 						?>
						 						<div class="col-lg-3 col-md-3 ts-thumbnails-over">
						 						<article class="vdf-video-item">
							 						<header>
								 						<div class="image-holder">
								 							<a href="<?php echo esc_url($link); ?>">
								 								<?php echo get_the_post_thumbnail($videoPost->ID); ?>
								 							</a>
								 						</div>
								 					</header>
							 						<section>
							 							<h3 class="entry-title">
								 							<a href="<?php echo esc_url($link); ?>">
								 								<?php echo esc_attr($videoPost->post_title); ?>
								 							</a>
							 							</h3>
							 							<div class="entry-meta-author">
								 							<a href="<?php echo get_author_posts_url($post->post_author); ?>" class="author">
								 								<i class="icon-user"></i>
								 								<?php echo get_the_author_meta('display_name', $videoPost->post_author); ?>
								 							</a>
							 							</div>
						 							</section>
								 						<button class="vdf-remove-fromplaylist" data-playlistid="<?php echo vdf_var_sanitize($playlistId); ?>" data-postid="<?php echo vdf_var_sanitize($videoPost->ID); ?>" data-action="video">
								 							<?php esc_html_e('Remove', 'videofly'); ?>
								 						</button>
						 						</article><!-- ./vdf-video-item -->
						 						</div> <!-- ./col-lg-3 -->
						 						<?php
						 					}

						 				}
						 				echo '</section>'; // end vdf-playlist-item
						 				echo '</div>';//end vdf-playlis-item
						 				echo '</div>';//end row
						 			}
						  			?>
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