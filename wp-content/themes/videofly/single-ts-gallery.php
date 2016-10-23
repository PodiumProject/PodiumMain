<?php get_header(); ?>
<section id="main">
<?php 
global $wp_query;

if ( have_posts() ) :
	while ( have_posts() ) : the_post();
	if (LayoutCompilator::sidebar_exists()) {
		
		$options = LayoutCompilator::get_sidebar_options();

		extract(LayoutCompilator::build_sidebar($options));

	} else {
		$content_class = 'col-lg-12';
	}

	if ( metadata_exists('post', get_the_ID(), '_post_image_gallery') ){
		tsIncludeScripts(array( 'isotope', 'jquery.lazyload.min', 'fancybox.pack', 'fancybox-thumbs', 'mCustomScrollbar'));
	    $product_image_gallery = get_post_meta(get_the_ID(), '_post_image_gallery', true);

	    $img_id_array = array_filter(explode( ',', $product_image_gallery ));

	    foreach ($img_id_array as $value) {
	        $attachments[$value] = $value;                
	    }
	}

	$gallery_type_class = '';
	$galleryOptions = get_post_meta(get_the_ID(), 'post_settings', true);
	$generalOptions = get_option('videofly_general');
	$styleOptions = get_option('videofly_styles');

	$hover = (isset($styleOptions['hover_gallery']) && ($styleOptions['hover_gallery'] == 'open-on-click' || $styleOptions['hover_gallery'] == 'slide-from-bottom')) ? $styleOptions['hover_gallery'] : 'open-on-click';

	if (post_password_required()) {
		$gallery_type_class = '';
	}

	$imageSizes = get_option('videofly_image_sizes');

	$heightDesktop = (isset($imageSizes['gallery-masonry-layout']['height-desktop']) && is_numeric($imageSizes['gallery-masonry-layout']['height-desktop'])) ? absint($imageSizes['gallery-masonry-layout']['height-desktop']) : 500;
	$heightMobile = (isset($imageSizes['gallery-masonry-layout']['height-mobile']) && is_numeric($imageSizes['gallery-masonry-layout']['height-mobile'])) ? absint($imageSizes['gallery-masonry-layout']['height-mobile']) : 300;
	$image_size_gallery = (wp_is_mobile()) ? $heightMobile : $heightDesktop;

	$subtitle = get_post_meta($post->ID, 'post_settings', true);
	$subtitle = (isset($subtitle['subtitle']) && $subtitle['subtitle'] !== '') ? $subtitle['subtitle'] : NULL;

	$featuredImageGeneralOptions = (isset($generalOptions['featured_image_in_post']) && ($generalOptions['featured_image_in_post'] == 'Y' || $generalOptions['featured_image_in_post'] == 'N')) ? $generalOptions['featured_image_in_post'] : 'Y'; 
	$display_featured_img = ($featuredImageGeneralOptions == 'Y' && has_post_thumbnail(get_the_ID())) ? 'has-featured-img' : 'hidden-featured-img';

	$caption_option = ($hover == 'open-on-click') ? 'with-caption' : 'without-caption';

	$generalSingle = get_option('videofly_single_post');
	$hideAuthorBox = !fields::logic($post->ID, 'post_settings', 'hide_author_box') && $generalSingle['display_author_box'] == 'n' ? 'y' : 'n';
	$show_meta = (!fields::logic($post->ID, 'post_settings', 'hide_meta') && vdf_single_display_meta() );
?>
<div class="gallery-type single_gallery5 <?php echo vdf_var_sanitize($display_featured_img); ?>">
	<?php if( !post_password_required() ) : ?>
		<?php if( isset($attachments) && count($attachments) > 0 ) : ?>
			<div class="header">
				<div class="row">
					<div class="container">
						<div class="inner-gallery-container">
							<?php foreach($attachments as $att_id => $attachment) : 
								$full_img_url = wp_get_attachment_url($att_id);
								$img_url = aq_resize($full_img_url, 9999, $image_size_gallery, false, true);
								$alt_text = get_post_meta($att_id, '_wp_attachment_image_alt', true);

								$attachmentQuery = get_post($att_id);
								$titleImage = (isset($attachmentQuery) && is_object($attachmentQuery)) ? $attachmentQuery->post_title : '';
								$descriptionImage = (isset($attachmentQuery) && is_object($attachmentQuery)) ? $attachmentQuery->post_content : '';
								$urlImage = get_post_meta($att_id, 'ts-image-url', true);
								$urlImage = (isset($urlImage)) ? esc_url($urlImage) : '';
							?>
								<div class="item item-gallery">
									<figure>
										<img class="lazy" data-layzr="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($alt_text); ?>">
									</figure>
									<div class="overlay-effect" data-trigger-caption="<?php echo esc_attr($caption_option); ?>">
										<div class="entry-overlay">
											<a href="<?php echo esc_url($urlImage); ?>" target="_blank">
												<h3 class="title"><?php echo esc_attr($titleImage); ?></h3>
											</a>
											<div class="entry-excerpt">
												<p><?php echo esc_attr($descriptionImage); ?></p>
											</div>
											<ul class="entry-controls">
												<li>
													<a href="<?php echo esc_url($full_img_url); ?>" title="<?php echo esc_attr($titleImage); ?>" rel="fancybox[]" class="zoom-in">
														<i class="icon-search"></i>
													</a>
												</li>
												<li>
													<a href="<?php echo esc_url($urlImage); ?>" title="<?php esc_html_e('External link','videofly'); ?>" target="_blank" class="link-in">
														<i class="icon-link-ext"></i>
													</a>
												</li>
												<li class="share-box">
													<a href="#" class="share-link">
														<i class="icon-share"></i>
													</a>
													<ul class="social-sharing share-menu">
														<li class="share-item">
															<a class="facebook" title="<?php esc_html_e('Share on facebook','videofly'); ?>" target="_blank" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url($full_img_url); ?>"><i class="icon-facebook"></i></a>
														</li>
														<li class="share-item">
															<a class="icon-twitter" title="<?php esc_html_e('Share on twitter','videofly'); ?>" target="_blank" href="http://twitter.com/home?status=<?php echo urlencode($titleImage); ?>+<?php echo esc_url($full_img_url); ?>"></a>
														</li>
														<li class="share-item">
															<a class="icon-pinterest" title="<?php esc_html_e('Share on pinterest','videofly'); ?>" target="_blank" href="http://pinterest.com/pin/create/button/?url=<?php esc_url($urlImage); ?>&amp;media=<?php echo esc_url($full_img_url) ?>&amp;description=<?php echo esc_attr($descriptionImage) ?>" ></a>
														</li>
													</ul>
												</li>
											</ul>
										</div>
									</div>
									<?php if ($caption_option == 'with-caption'): ?>
										<div class="trigger-caption">
											<a href="#" class="button-trigger-cap"><i class="icon-left-arrow"></i></a>
										</div>
									<?php endif ?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		<?php else : ?>
			<?php if( $featuredImageGeneralOptions == 'Y' && has_post_thumbnail(get_the_ID()) && !isset($attachments) ) : ?>
				<?php tsIncludeScripts(array( 'fancybox.pack', 'fancybox-thumbs')); ?>
				<div class="container">
					<div class="featured-image">
						<?php 
							$src = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));
							$img_url = vdf_resize('single', $src);

							echo '<img itemprop="image" itemprop="thumbnailUrl" src="' . esc_url($img_url) . '" alt="' . esc_attr(get_the_title()) . '" >';
						
							if (vdf_lightbox_enabled()) {
								echo '<a class="zoom-in-icon" href="' . esc_url($src) . '" rel="fancybox[' . get_the_ID() . ']"><i class="icon-search"></i></a>';
							}

							if ( vdf_overlay_effect_is_enabled() ) {
								echo '<div class="' . vdf_overlay_effect_type() . '"></div>';
							} 
						?>
					</div>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	<?php endif; ?>
	<div class="container singular-container">
		<div class="row">
			<?php
				if (LayoutCompilator::sidebar_exists()) {
					if (LayoutCompilator::is_left_sidebar('single')) {
						echo '<div class="left-sidebar">';
							echo vdf_var_sanitize($sidebar_content);
						echo '</div>';
					}
				}
			?>
			<div id="primary" class="<?php echo vdf_var_sanitize($content_class); ?>">
				<div id="content" role="main">
					<article>
						<div class="section">
							<div class="inner-section">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="col-lg-12">
												<div class="post-header-title">
													<?php if (post_password_required()): ?>
														<div class="entry-icon">
															<i class="icon-lock"></i>
														</div>
													<?php endif ?>
													<div class="entry-header-content">
														<?php if(!fields::logic($post->ID, 'post_settings', 'hide_title')): ?>													
															<div class="entry-title">
																<h1 class="post-title"><?php the_title(); ?></h1>														
															</div>
														<?php endif; ?>
														<?php if( $show_meta ): ?>
															<div class="post-date">
																<?php the_date(); ?>
															</div>
														<?php endif; ?>
														<?php if( isset($subtitle) && !post_password_required() ) : ?>
															<div class="entry-subtitle">
																<?php echo esc_attr($subtitle); ?>
															</div>
														<?php endif; ?>
														<?php if ( vdf_single_display_meta() && !fields::logic($post->ID, 'post_settings', 'hide_meta') ): ?>
															<div class="entry-meta post-meta gallery-meta">
																<ul>
																	<?php touchsize_likes($post->ID, '<li class="post-likes">', '</li>'); ?>
																	<?php vdf_get_views($post->ID, '<li class="post-views"><i class="icon-views"></i> ', '</li>'); ?>
																	<li class="post-collection">
																		<i class="icon-gallery"></i>
																		<?php echo (isset($attachments) ? count($attachments) : 0); ?> <?php esc_html_e('photos','videofly'); ?>
																	</li>
																	<li class="post-meta-categories">
																		<i class="icon-category"></i>
																		<?php echo get_the_term_list(get_the_ID(), 'gallery_categories', '', ' ', '') ?>
																	</li>
																	<?php edit_post_link( esc_html__( 'Edit', 'videofly' ), '<li class="post-edit"><i class="icon-edit"></i><span class="edit-link">', '</span></li>' ); ?>
																	
																</ul>
															</div>
														<?php endif ?>
														
													</div>
												</div>
											</div>
											<div class="col-lg-12">
												<div class="post-content">
													<?php the_content(); ?>
													<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . esc_html__( 'Pages:', 'videofly' ) . '</span>', 'after' => '</div>' ) ); ?>
												</div>
												<?php if( !fields::logic($post->ID, 'post_settings', 'hide_social_sharing') && vdf_single_social_sharing() ): ?>
													<?php get_template_part('social-sharing'); ?>
												<?php endif; ?>
												<div class="footer">
													<div class="inner-footer">
														<?php if( $hideAuthorBox == 'y' ) : ?>
														<div class="post-author-box">
															<a href="<?php echo get_author_posts_url($post->post_author) ?>"><?php echo get_avatar(get_the_author_meta( 'ID' ), 74); ?></a>
															<div class="author-box-content">
																<h5 class="author-title"><?php the_author_link(); ?></h5>
																<div class="author-box-info"><?php the_author_meta('description'); ?>
																    <?php
																     if(strlen(get_the_author_meta('user_url'))!=''){?>
																        <span><?php esc_html_e('Website:','videofly'); ?> <a href="<?php the_author_meta('user_url');?>"><?php the_author_meta('user_url');?></a></span>
																    <?php } ?>
																</div>
															</div>
														</div>
														<?php endif ?>

														<?php if ( vdf_single_display_meta() && !fields::logic($post->ID, 'post_settings', 'hide_meta') ): ?>
															<?php if( has_tag() ) : ?>
																<h5><?php esc_html_e('Tags','videofly'); ?></h5>
																<div class="single-post-tags">
																	<?php if (vdf_single_display_tags()): ?>
																		<?php the_tags('<ul itemprop="keywords" class="tags-container"><li>','</li><li>','</li></ul>'); ?>
																	<?php endif ?>
																</div>
															<?php endif; ?>
														<?php endif; ?>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</article>
				</div>
			</div>
			<?php
				if (LayoutCompilator::sidebar_exists()) {
					if (LayoutCompilator::is_right_sidebar('single')) {
						echo '<div class="right-sidebar">';
							echo vdf_var_sanitize($sidebar_content);
						echo '</div>';
					}
				}
			?>
		</div>
	</div>
	<div class="post-related">
		<div class="container">
			<?php if ( !fields::logic($post->ID, 'post_settings', 'hide_related') && vdf_single_display_related() ): ?>
				<div class="row">
					<div class="col-md-12 text-center">
						<h4 class="related-title"><?php esc_html_e('Related posts', 'videofly'); ?></h4>
					</div>
					<?php echo LayoutCompilator::get_single_related_posts(get_the_ID()); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<!-- Ad area 2 -->
	<?php if( fields::get_options_value('videofly_theme_advertising','ad_area_2') != '' ): ?>
		<div class="container text-center ts-advertising-container">
			<?php echo fields::get_options_value('videofly_theme_advertising','ad_area_2'); ?>
		</div>
	<?php endif; ?>
	<!-- // End of Ad Area 2 -->
	<div class="post-comments">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="post-comments">
						<?php comments_template( '', true ); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php  endwhile; 
endif; //end if have_posts ?>
</section>
<?php get_footer(); ?>