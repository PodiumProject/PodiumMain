<?php get_header();
$vdfStickySidebars = fields::get_options_value('videofly_single_post','sticky_sidebars');
$vdfArticleProgress = fields::get_options_value('videofly_single_post','article_progress');
$vdfAdditionalClass = '';
if ( $vdfStickySidebars == 'Y') {
	$vdfAdditionalClass = ' sticky-sidebars-enabled ';
}if ( $vdfArticleProgress == 'Y') {
	$vdfAdditionalClass .= ' article-progress-enabled ';
}
?>
<section id="main" class="<?php echo vdf_var_sanitize($vdfAdditionalClass); ?>">
	<div id="article-progress-bar"></div>
	<?php
	$singleOptions = get_post_meta($post->ID, 'post_settings', true);
	$generalSingle = get_option('videofly_single_post');

	if( $generalSingle['breadcrumbs'] === 'y' ) :?>
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="ts-breadcrumbs breadcrumbs-single-post container">
						<?php
						echo vdf_breadcrumbs();
						?>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php

	global $wp_query;

	if ( have_posts() ) :

		while ( have_posts() ) : the_post();

			$singlePostOptions = get_post_meta($post->ID, 'post_settings', true);
			$subtitle = (isset($singlePostOptions['subtitle']) && $singlePostOptions['subtitle'] !== '') ? esc_attr($singlePostOptions['subtitle']) : NULL;
			$hideAuthorBox = !fields::logic($post->ID, 'post_settings', 'hide_author_box') && $generalSingle['display_author_box'] == 'n' ? 'y' : 'n';
			$vdf_sidebar_option = ' without-sidebar ';

			if( LayoutCompilator::sidebar_exists() ){
				
				$vdf_sidebar_option = ' with-sidebar ';
				$options = LayoutCompilator::get_sidebar_options();

				extract(LayoutCompilator::build_sidebar($options));

				if ( LayoutCompilator::is_left_sidebar() ) {

					$vdf_sidebar_option .= ' with-sidebar-left ';

				} else{

					$vdf_sidebar_option .= ' with-sidebar-right ';

				}

			} else {

				$content_class = 'col-lg-12';

			}

			$general = get_option('videofly_general');
			$like = isset($general['like']) ? $general['like'] : 'y';
			$display_featured_img = ( vdf_display_featured_image() && has_post_thumbnail( get_the_ID() ) ) ? 'has-featured-img':'hidden-featured-img';
			tsIncludeScripts(array('fancybox.pack','fancybox-thumbs'));
			$hideAuthorBox = !fields::logic($post->ID, 'post_settings', 'hide_author_box') && $generalSingle['display_author_box'] == 'n' ? 'y' : 'n';
			$rating_items = get_post_meta($post->ID, 'ts_post_rating', TRUE);
			$rating_final = ts_get_rating($post->ID);
			?>
			<div class="<?php echo vdf_var_sanitize($display_featured_img); ?>">
				<article <?php post_class('ts-single-post single_style1');?> >
					<div class="header container">
						<?php if ( vdf_display_featured_image() && has_post_thumbnail( get_the_ID() ) ) : ?>
							<div class="post-header-image-holder featured-image">
								<?php
								if ( get_post_format( get_the_ID() ) === false ) {
									if ( vdf_display_featured_image() && has_post_thumbnail( get_the_ID() ) ) {

										$src = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
										$img_url = vdf_resize('single', $src);

										echo '<img itemprop="image" itemprop="thumbnailUrl" src="' . esc_url($img_url) . '" alt="' . esc_attr(get_the_title()) . '" >';

										if ( vdf_overlay_effect_is_enabled() ) {
											echo '<div class="' . vdf_overlay_effect_type() . '"></div>';
										}
									}

								} elseif( get_post_format( get_the_ID() ) === 'gallery' ) {

									echo red_get_post_img_slideshow( get_the_ID() );

								} elseif ( get_post_format( get_the_ID() ) === 'video' ) {

									echo '<div class="embedded_videos">';
									echo apply_filters('the_content', get_post_meta(get_the_ID(), 'video_embed', TRUE));
									echo '</div>';

								} elseif ( get_post_format(get_the_ID()) === 'audio' ) {

									$src = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
									$img_url = vdf_resize('single', $src);
									echo '<div class="relative ">';
									echo '<img itemprop="image" itemprop="thumbnailUrl" src="' . esc_url($img_url) . '" alt="' . esc_attr(get_the_title()) . '" >';

									echo '<a class="zoom-in-icon" href="' . esc_url($src) . '" rel="fancybox[' . get_the_ID() . ']"><i class="icon-search"></i></a>';

									if ( vdf_overlay_effect_is_enabled() ) {
										echo '<div class="' . vdf_overlay_effect_type() . '"></div>';
									}
									echo '</div>';
									echo '<div class="embedded_audio">';
									echo apply_filters('the_content', get_post_meta(get_the_ID(), 'audio_embed', TRUE));
									echo '</div>';

								}
								?>
							</div>
						<?php endif; ?>
					</div>
					<section class="single-content">
						<div class="container singular-container">
							<div id="content" role="main">
								<?php if ( !fields::logic($post->ID, 'post_settings', 'hide_title') ): ?>	
									<h1 class="post-title"><?php the_title(); ?></h1>
								<?php endif; ?>
								<?php if( isset($subtitle) ) : ?>
									<div class="post-subtitle">
										<?php echo vdf_var_sanitize($subtitle); ?>
									</div>
								<?php endif; ?>								
								<?php if ( vdf_single_display_meta() && !fields::logic($post->ID, 'post_settings', 'hide_meta') ): ?>	

									<ul class="post-meta">
										<?php touchsize_likes($post->ID, '<li class="post-meta-likes">', '</li>'); ?>
										<li class="post-meta-date">
											<i class="icon-time"></i>
											<span><?php echo the_date(); ?></span>
										</li>
										<?php vdf_get_views($post->ID, '<li class="post-meta-views"><i class="icon-views"></i><span>', '</span></li>'); ?>
										<li class="post-meta-comments">
											<i class="icon-comments"></i>
											<span><?php echo get_comments_number( get_the_ID() ); ?></span>
										</li>
									</ul>
								<?php endif; ?>
								<div class="content-splitter <?php echo esc_attr($vdf_sidebar_option) ?>">
									<div class="row">
										<?php
											if (LayoutCompilator::sidebar_exists()) {
												if (LayoutCompilator::is_left_sidebar('single')) {
													echo vdf_var_sanitize($sidebar_content);
												}
											}
										?>
										<div class="<?php echo esc_attr($content_class) ?>">
											<div class="post-content">
												<?php the_content(); ?>
												<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . esc_html__( 'Pages:', 'videofly' ) . '</span>', 'after' => '</div>' ) ); ?>
											</div>
											<?php if( isset($rating_items) && is_array($rating_items) && !empty($rating_items) ) : ?>
												<div class="post-rating" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
													<ul class="rating-items">
													<?php 
													$final_score = ''; $i = 0;
													foreach($rating_items as $rating) : $final_score += $rating['rating_score']; $i++; ?>
														<li itemprop="itemReviewed" itemscope>
															<h4 class="rating-title" itemprop="name"><?php echo sanitize_text_field($rating['rating_title']); ?></h4>
															<span class="rating-score"><i class="note" itemprop="ratingValue"><?php echo absint($rating['rating_score']) ?></i>&frasl;<i class="limit">10</i></span>
															<div class="rating-bar">
																<span class="bar-progress" data-bar-size="<?php echo absint($rating['rating_score']) * 10 ?>"></span>
															</div>
														</li>
													<?php endforeach; ?>
													</ul>
													<div class="text-right">
														<div class="counted-score">
															<span><?php esc_html_e('Final Score','videofly'); ?></span><strong class="score" itemprop="ratingValue"><?php echo round($final_score / $i, 1); ?>/10</strong>
														</div>
													</div>
												</div>
											<?php endif; ?>
											<?php if( !fields::logic($post->ID, 'post_settings', 'hide_social_sharing') && vdf_single_social_sharing() ): ?>
												<div class="bottom-article">
													<?php get_template_part('social-sharing'); ?>
												</div>
											<?php endif; ?>
											<?php if ( !fields::logic($post->ID, 'post_settings', 'hide_meta') && vdf_single_display_tags()): ?>
												<h5><?php esc_html_e('Tagged in', 'videofly'); ?>:</h5>
												<?php the_tags('<ul itemprop="keywords" class="tags-container single-post-tags"><li>','</li><li>','</li></ul>'); ?>
											<?php endif ?>
											<?php if ( $hideAuthorBox == 'y' ) : ?>
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
										</div>
										<?php
											if (LayoutCompilator::sidebar_exists()) {
												if (LayoutCompilator::is_right_sidebar('single')) {
													echo vdf_var_sanitize($sidebar_content);
												}
											}
										?>
									</div>
								</div>
							</div>
						</div>
					</section>
				</article>
				<?php vdf_get_pagination_next_previous(); ?>
				<div class="post-related">
					<div class="container">
						<?php if (!fields::logic($post->ID, 'post_settings', 'hide_related')): ?>
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
		<?php endwhile;

	endif;
	?>
</section>
<?php get_footer(); ?>