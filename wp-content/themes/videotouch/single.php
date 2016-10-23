<?php get_header(); ?>
<?php if( get_post_type() !== 'video' ): ?>
<section id="main">
<?php
	$generalSingle = get_option('videotouch_single_post');
	$hideAuthorBox = !fields::logic($post->ID, 'post_settings', 'hide_author_box') && $generalSingle['display_author_box'] == 'n' ? 'y' : 'n';
	if( $generalSingle['breadcrumbs'] === 'y' ) :
?>
	<div class="ts-breadcrumbs breadcrumbs-single-post container">
		<?php
			echo ts_breadcrumbs();
		?>
	</div>
<?php endif; ?>
<div class="container singular-container">
<?php endif ?>
<?php
global $wp_query;
tsIncludeScripts(array('prettyphoto'));
if ( have_posts() ) :
	
	while ( have_posts() ) : the_post();

	if (LayoutCompilator::sidebar_exists()) {
		
		$options = LayoutCompilator::get_sidebar_options();

		extract(LayoutCompilator::build_sidebar($options));

		if (LayoutCompilator::is_left_sidebar()) {
			echo $sidebar_content;
		}
	} else {
		$content_class = 'col-lg-12';
	}
?>
		<div id="primary" class="<?php echo $content_class ?>">
			<div id="content" role="main">		
				<div class="row">
					<div class="col-lg-12">
						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<header class="post-header">
								<?php if ( fields::get_value($post->ID, 'post_settings', 'title_position', true) == 'above'): ?>
									<div class="row">
										<?php if ( !fields::logic($post->ID, 'post_settings', 'hide_title') ): ?>
											<div class="col-lg-12">
												<h1 class="page-title"><?php esc_attr(the_title()); ?> <?php touchsize_likes($post->ID); ?></h1>
											</div>
										<?php endif ?>
										<?php if ( ts_single_display_meta() && !fields::logic($post->ID, 'post_settings', 'hide_meta') ): ?>
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<ul class="post-title-meta">
													<li class="post-title-meta-categories">
														<?php echo get_the_category_list(); ?>
													</li>
													<li class="post-title-meta-date">
															<?php if (ts_human_type_date_format()): ?>
																<i class="icon-time"></i> <?php echo human_time_diff(strtotime(get_the_time('Y-m-d H:i:s'))) . ' ' . __('ago', 'touchsize');?>
															<?php else: ?>
																<i class="icon-time"></i> <?php the_date(); ?>
															<?php endif ?>
													</li>
													<li class="post-title-meta-author"><i class="icon-user"></i> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php echo get_the_author(); ?></a></li>
													<?php edit_post_link( __( 'Edit', 'touchsize' ), '<li class="post-title-meta-edit"><span class="edit-link">', '</span></li>' ); ?>
												</ul>
											</div>
										<?php endif ?>
									</div>
								<?php endif; ?>
								<div class="row">
									<div class="col-lg-12">
										<div class="featured-image">
											<?php
												if ( get_post_format( get_the_ID() ) === false || get_post_format( get_the_ID() ) == 'image' ) {
													
													if ( ts_display_featured_image() && has_post_thumbnail( get_the_ID() ) ) {
														
														$src = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
														$img_url = ts_resize('single', $src);

														echo '<img src="' . esc_url($img_url) . '" alt="' . esc_attr(get_the_title()) . '" >';

														if (ts_lightbox_enabled()) {
															echo '<a class="zoom-in-icon" href="' . esc_url($src) . '" data-rel="prettyPhoto[' . get_the_ID() . ']"><i class="icon-search"></i></a>';
														}

														if ( ts_overlay_effect_is_enabled() ) {
															echo '<div class="' . ts_overlay_effect_type() . '"></div>';
														}
													}

						                        } elseif( get_post_format( get_the_ID() ) === 'gallery' ) {

						            				echo red_get_post_img_slideshow( get_the_ID() );

						                        } elseif ( get_post_format( get_the_ID() ) === 'video' ) {

						                        	echo '<div class="embedded_videos">';
							                        	echo '<div class="video-container">';
							                        		echo apply_filters('the_content', get_post_meta(get_the_ID(), 'video_embed', TRUE));
							                        	echo '</div>';
						                        	echo '</div>';

						                        } elseif ( get_post_format(get_the_ID()) === 'audio' ) {

						                        	$src = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
						                        	$option = get_option('videotouch_general', array('featured_image_in_post' => 'Y'));

						                        	echo '<div class="relative ">';
						                        	if( isset($option['featured_image_in_post']) && $option['featured_image_in_post'] === 'y' ){
						                        		$img_url = ts_resize('single', $src);
						                        		echo '<img src="' . esc_url($img_url) . '" alt="' . esc_attr(get_the_title()) . '" >';
						                        	}
						                        	
						                        	echo '<a class="zoom-in-icon" href="' . esc_url($src) . '" data-rel="prettyPhoto[' . get_the_ID() . ']"><i class="icon-search"></i></a>';
						                        	
						                        	if ( ts_overlay_effect_is_enabled() ) {
						                        		echo '<div class="' . ts_overlay_effect_type() . '"></div>';
						                        	}
						                        	echo '</div>';
						                        	echo '<div class="embedded_videos">';
						                        	echo apply_filters('the_content', get_post_meta(get_the_ID(), 'audio_embed', TRUE));
						                        	echo '</div>';

						                        }
											?>
										</div>
									</div>
								</div>
								<?php if ( fields::get_value($post->ID, 'post_settings', 'title_position', true) == 'below'): ?>
									<div class="row">
										<?php if ( !fields::logic($post->ID, 'post_settings', 'hide_title') ): ?>
											<div class="col-lg-12">
												<h1 class="page-title"><?php echo esc_attr(get_the_title()); ?> <?php touchsize_likes($post->ID); ?></h1>
											</div>
										<?php endif ?>
										<?php if ( ts_single_display_meta() && !fields::logic($post->ID, 'post_settings', 'hide_meta') ): ?>
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<ul class="post-title-meta">
													<li class="post-title-meta-categories">
														<?php echo get_the_category_list(); ?>
													</li>
													<li class="post-title-meta-date">
															<?php if (ts_human_type_date_format()): ?>
																<i class="icon-time"></i> <?php echo human_time_diff(strtotime(get_the_time('Y-m-d H:i:s'))) . ' ' . __('ago', 'touchsize'); ?>
															<?php else: ?>
																<i class="icon-time"></i> <?php the_date(); ?>
															<?php endif ?>
													</li>
													<li class="post-title-meta-author"><i class="icon-user"></i> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php echo get_the_author(); ?></a></li>
													<?php edit_post_link( __( 'Edit', 'touchsize' ), '<li class="post-title-meta-edit"><span class="edit-link">', '</span></li>' ); ?>
												</ul>
											</div>
										<?php endif ?>
									</div>
								<?php endif; ?>
							</header><!-- .post-header -->
							
							<div class="post-content">
								<?php the_content(); ?>
								<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'touchsize' ) . '</span>', 'after' => '</div>' ) ); ?>
							</div><!-- .post-content -->

							<footer class="post-footer">
								<?php if ( $hideAuthorBox == 'y' ) : ?>
									<div class="post-author-box">
							            <a href="<?php echo get_author_posts_url($post->post_author) ?>"><?php echo ts_display_gravatar(120); ?></a>
							            <h5 class="author-title" itemprop="reviewer"><?php the_author_link(); ?></h5>
							            <div class="author-box-info"><?php the_author_meta('description'); ?>
							                <?php
							                 if(strlen(get_the_author_meta('user_url'))!=''){?>
							                    <span><?php _e('Website:', 'touchsize'); ?> <a href="<?php the_author_meta('user_url');?>"><?php the_author_meta('user_url');?></a></span>
							                <?php } ?>
							            </div>                                 
							        </div>
								<?php endif ?>
								<?php $tags_columns = (has_tag()) ? 'col-lg-6' : 'col-lg-12'; ?>
								<div class="row">
									<?php if ( ts_single_display_meta() && !fields::logic($post->ID, 'post_settings', 'hide_meta') ): ?>
										<?php if( has_tag() ) : ?>
											<div class="<?php echo $tags_columns; ?>">
												<h6 class="post-details-title"><?php _e('Tagged in','touchsize'); ?></h6>
												<div class="post-tags">
													<?php if (ts_single_display_tags()): ?>
														<?php the_tags('<ul class="tags-container"><li>','</li><li>','</li></ul>'); ?>
													<?php endif ?>
												</div>
											</div>
										<?php endif; ?>
									<?php endif; ?>
									<div class="<?php echo $tags_columns; ?>">
										<?php if( ts_single_social_sharing() && !fields::logic($post->ID, 'post_settings', 'hide_social_sharing')): ?>
										<h6 class="post-details-title"><?php _e('Share','touchsize'); ?></h6>
											
										<?php	get_template_part('social-sharing');
										endif; ?>
									</div>
								</div>
								<?php if (!fields::logic($post->ID, 'post_settings', 'hide_related')): ?>
									<div class="row">
										<div class="col-lg-12">
											<h4 class="related-title"><?php _e('Related posts', 'touchsize'); ?></h4>
										</div>
										<?php echo LayoutCompilator::get_single_related_posts(get_the_ID()); ?>
									</div>
								<?php endif; ?>
							</footer>
						</article><!-- #post-<?php the_ID(); ?> -->
						
						<!-- Ad area 2 -->
						<?php if( fields::get_options_value('videotouch_theme_advertising','ad_area_2') != '' ): ?>
						<div class="container text-center ts-advertising-container">
							<?php echo fields::get_options_value('videotouch_theme_advertising','ad_area_2'); ?>
						</div>
						<?php endif; ?>
						<!-- // End of Ad Area 2 -->

						<div class="row content-block">
							<div class="col-lg-12">
								<?php comments_template( '', true ); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

<?php

if (LayoutCompilator::sidebar_exists()) {
	if (LayoutCompilator::is_right_sidebar('single')) {
		echo $sidebar_content;
	}
} ?>

</div>
</section>
<?php endwhile; ?>
<?php endif; ?>
<?php ts_get_pagination_next_previous(); ?>
<?php get_footer(); ?>