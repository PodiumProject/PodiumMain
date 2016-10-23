<?php
/*
Template Name: Front-end - Add new post
*/

if( !is_user_logged_in() ){
	wp_redirect( esc_url( home_url( '/' ) ) );
	exit;
}
$checked = ts_save_post_user();

get_header();

$general = get_option('videofly_general');
$statusPost = isset($general['post_publish_user']) ? $general['post_publish_user'] : 'pending';

$postId = isset($_GET['id']) && (int)$_GET['id'] !== 0 ? (int)$_GET['id'] : NULL;
$content = isset($checked['ts-post-content']) ? $checked['ts-post-content'] : '';
$title = isset($checked['ts-title-post']) ? $checked['ts-title-post'] : '';
$tags = isset($checked['ts-tags']) ? $checked['ts-tags'] : '';
$category_id = isset($checked['ts-category-video']) ? $checked['ts-category-video'] : '';
$tab = isset($checked['selected-tab']) ? $checked['selected-tab'] : '';
$duration = isset($checked['ts-duration']) ? $checked['ts-duration'] : '';
$videoCode = $tab == 'url' ? $checked['ts-url-video'] : ($tab == 'embed' ? $checked['ts-embed-video'] : '');

if( isset($postId) ){
	$post = get_post($postId, OBJECT);
	if( isset($post) ){

		$userLogged = get_current_user_id();
		$userAuthor = $post->post_author;

		if( (int)$userLogged !== (int)$userAuthor ) wp_redirect( esc_url( home_url( '/' ) ) );

		$title = isset($post->post_title) ? esc_attr($post->post_title) : esc_html_e('No title', 'videofly');
		$content = isset($post->post_content) && is_string($post->post_content) ? $post->post_content : esc_html_e('No content', 'videofly');
		$tags_base = get_the_tags($postId);
		$tags = '';

		if( !empty($tags_base) && is_array($tags_base) ){
			foreach( $tags_base as $tag ){
				$tags .= $tag->name . ', ';
			}
		}

		$category = get_the_terms($postId, 'videos_categories');
		$category_id = isset($category[0]->term_id) ? $category[0]->term_id : '';

		$video = get_post_meta($postId, 'ts-video', true);

		$videoCode = isset($video['video']) ? $video['video'] : '';
		$duration = isset($video['duration']) ? $video['duration'] : '';
		$tab = isset($video['type']) ? $video['type'] : '';

	}else{
		wp_redirect( esc_url( home_url( '/' ) ) );
	}
}

$args = array(
	'show_option_none' => '',
	'show_count'       => 0,
	'orderby'          => 'name',
	'echo'             => 0,
	'hide_empty'       => 0,
	'hierarchical'     => 1,
	'class'            => 'selectpicker',
	'name'             => 'ts-category-video',
	'taxonomy'         => 'videos_categories',
	'selected'         => $category_id
);

if ( LayoutCompilator::sidebar_exists() ) {
	$options = LayoutCompilator::get_sidebar_options();
	extract(LayoutCompilator::build_sidebar($options));
} else {
	$content_class = 'col-lg-12';
}

?>
<div class="container">
	<div class="ts-upload-page">
		<?php if( isset($checked['ts-error']) ): ?>
			<div class="ts-alert" style="color: #339b62; background-color: #f5fcf8;margin-bottom:40px;">
                <span class="alert-icon"><i class="icon-ok-full"></i></span>
                <div class="right-side">
                    <span class="alert-title"><h3 class="title"><?php echo esc_attr($checked['ts-error']); ?></h3></span>
                 </div>
            </div>
		<?php endif; ?>
		<div class="row">
			<?php if ( LayoutCompilator::is_left_sidebar() ) echo vdf_var_sanitize($sidebar_content); ?>
			<div class="<?php echo vdf_var_sanitize($content_class) ?>">
				<h2><?php esc_html_e('Upload video', 'videofly'); ?></h2>
				<form method="POST" enctype="multipart/form-data">
					<div class="row">
						<div class="ts-top">
	    					<div class="col-lg-12 col-md-12 col-sm-12">
	    						<span class="ts-inf-title"><?php esc_html_e('General information', 'videofly'); ?><i class="icon-attention" data-placement="right" data-toggle="tooltip" title="General information"></i></span>
								<input type="text" required value="<?php echo esc_attr($title) ?>" maxlength="70" placeholder="<?php esc_html_e('add your video title here', 'videofly'); ?>" name="ts-title-post"/>
								<textarea placeholder="<?php esc_html_e('add your description text here', 'videofly'); ?>" name="ts-post-content" required><?php echo esc_textarea($content); ?></textarea>
	    					</div>
							<div class="col-lg-12 col-md-12 col-sm-12">
								<div class="row">
	            					<div class="col-lg-6 col-md-6 col-sm-12">
	            						<span class="ts-inf-title">
	            							<?php esc_html_e('Choose category', 'videofly'); ?>
	            							<i class="icon-attention" data-placement="right" data-toggle="tooltip" title="<?php esc_html_e('Choose category', 'videofly'); ?>"></i>
	            						</span>
	            						<?php echo wp_dropdown_categories($args) ?>
	            					</div>
	            					<div class="col-lg-6 col-md-6 col-sm-12">
	            						<span class="ts-inf-title">
	            							<?php esc_html_e('Add tags', 'videofly'); ?>
	            							<i class="icon-attention" data-placement="right" data-toggle="tooltip" title="Tags"></i>
	            						</span>
	    								<input type="text" value="<?php echo esc_attr($tags) ?>" placeholder="music, dance, tag" name="ts-tags"/>
	            					</div>
								</div>
							</div>
	    				</div>
	    			</div>
	    			<div class="row">
	    				<div class="ts-middle">
	    					<div class="col-lg-12 col-md-12 col-sm-12">
								<div class="box">
									<span class="ts-preview-img">
										<?php if( isset($postId) && has_post_thumbnail($postId) ) : ?>
											<?php echo get_the_post_thumbnail($postId); ?>
										<?php endif; ?>
									</span>
									<input type="file" name="ts-upload-img" class="inputfile ts-img-upload" value="<?php echo (isset($checked['tmp_name_img']) ? $checked['tmp_name_img'] : '') ?>"<?php echo (empty($postId) ? ' required' : ''); ?>/>
									<label class="ts-file">
										<i class="icon-upload"></i>
										<span><?php esc_html_e('Click to upload your image', 'videofly'); ?></span>
									</label>
								</div>
	    					</div>
	    					<?php if( empty($postId) ): ?>
		    					<div class="col-lg-12 col-md-12 col-sm-12">
		    						<div class="entry-tabs">
		        						<ul class="ts-select-tab">
		        							<?php if( isset($general['user_tab_post']['upload']) ): ?>
		        								<li data-selected="upload"><span><?php esc_html_e('Upload video', 'videofly'); ?></span></li>
		        							<?php endif; ?>
		        							<?php if( isset($general['user_tab_post']['url']) ): ?>
		        								<li data-selected="url"><span><?php esc_html_e('Use URL', 'videofly'); ?></span></li>
		        							<?php endif; ?>
		        							<?php if( isset($general['user_tab_post']['embed']) ): ?>
		        								<li data-selected="embed"><span><?php esc_html_e('Embed code', 'videofly'); ?></span></li>
		        							<?php endif; ?>
		        						</ul>
		        						<ul class="ts-tabs">
		        							<?php if( isset($general['user_tab_post']['upload']) ): ?>
		        								<li class="ts-tab-active">
													<div class="box">
														<input type="file" name="ts-upload-video" class="inputfile"/>
														<label class="ts-file">
															<i class="icon-upload"></i>
															<span><?php esc_html_e('Click to upload your video', 'videofly'); ?></span>
														</label>
													</div>
			        							</li>
		        							<?php endif; ?>
		        							<?php if( isset($general['user_tab_post']['url']) ): ?>
		        								<li>
			        								<input type="text" value="<?php echo ($tab == 'url' ? $videoCode : '') ?>" class="ts-url" placeholder="<?php esc_html_e('add your video url here', 'videofly'); ?>" name="ts-url-video" />
			        							</li>
		        							<?php endif; ?>
		        							<?php if( isset($general['user_tab_post']['embed']) ): ?>
		        								<li>
			        								<textarea placeholder="<?php esc_html_e('add here embed code', 'videofly'); ?>" name="ts-embed-video"><?php echo ($tab == 'embed' ? $videoCode : '') ?></textarea>
			        							</li>
		        							<?php endif; ?>
		        						</ul>
		        						<input type="hidden" name="selected-tab" value="<?php echo esc_attr($tab) ?>">
		    						</div>
		    					</div>
		    				<?php endif; ?>
						</div>
					</div>
					<div class="row">
						<div class="ts-footer">
	    					<div class="col-lg-12 col-md-12 col-sm-12">
	    						<?php if( empty($postId) ): ?>
			    					<div class="ts-details">
		    							<span class="ts-inf-title"><?php esc_html_e('Video details', 'videofly'); ?>
		    								<i class="icon-attention" data-placement="right" data-toggle="tooltip" title="<?php esc_html_e('Video details', 'videofly'); ?>"></i>
		    							</span>
										<input type="text" value="<?php echo esc_attr($duration) ?>" maxlength="70" placeholder="<?php esc_html_e('set video duration in seconds', 'videofly'); ?>" name="ts-duration" required/>
									</div>
		    						<div class="ts-terms">
		    							<span class="ts-inf-title"><?php esc_html_e('Terms agreement', 'videofly'); ?><i class="icon-attention" data-placement="right" data-toggle="tooltip" title="<?php esc_html_e('Terms agreement', 'videofly'); ?>"></i></span>
										<div class="checkbox">
											<input type="checkbox" id="checkbox1" name="agree" required>
											<label for="checkbox1">
												<span><?php esc_html_e('I agree to the terms that are written here.', 'videofly'); ?></span>
											</label>
										</div>
									</div>
								<?php endif; ?>
								<div class="ts-btn-submit">
								<?php if( $statusPost == 'pending' && empty($postId) ) : ?>
									<div class="post-pending">
										<?php esc_html_e('The post will be pending until an Administrator will approve it.', 'touchsize'); ?>
									</div>
									<div style="height:60px"></div>
								<?php endif; ?>
									<?php wp_nonce_field('verify-save-post', 'nonce-user-post', false); ?>
									<?php if( !empty($postId) ): ?>
										<input type="submit" name="ts-update-post" class="btn btn-primary active medium" value="<?php esc_html_e( 'Update post', 'videofly' ) ?>"/>
										<input type="submit" name="ts-delete-post" class="btn btn-primary active medium" value="<?php esc_html_e('Remove post', 'videofly'); ?>"/>
										<input type="hidden" value="<?php echo intval($postId) ?>" name="postId">
									<?php else: ?>
										<input type="submit" name="ts-add-post" class="btn btn-primary active medium" value="<?php esc_html_e( 'Add post', 'videofly' ) ?>"/>
									<?php endif; ?>
								</div>
	    					</div>
						</div>
					</div>
				</form>
			</div>
			<?php if ( LayoutCompilator::is_right_sidebar() ) echo vdf_var_sanitize($sidebar_content); ?>
		</div>
	</div>
</div>
<?php get_footer(); ?>