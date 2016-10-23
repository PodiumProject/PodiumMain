<?php

/* Featured area template below */
###########

// Get the options

global $article_options, $posts_query;

$custom_post = (isset($article_options['custom-post']) && $article_options['custom-post'] !== '') ? $article_options['custom-post'] : 'post';
$scroll = (isset($article_options['scroll']) && $article_options['scroll'] !== '') ? $article_options['scroll'] : 'y';

$store_image_featured = array();
$date_posts = array();
$store_image_thumbnail = array();
$post_number = 0;
$videos = array();
$single_id = array();
$the_permalink = array();

while ( $posts_query->have_posts() ) { $posts_query->the_post();

	if( $custom_post == 'video' || $custom_post == 'video_post' ){
		$videos[$post_number] = get_post_meta($post->ID, 'video', true);
	}

	$total_posts = $posts_query->post_count;
	$src = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );

	$noimg_url = get_template_directory_uri() . '/images/noimage.jpg';
	$bool = fields::get_options_value('videotouch_general', 'enable_imagesloaded');

	if ( $src ) {
		$img_url_featured = ts_resize('featarea', $src);
		$featimage_featured = '<img ' . ts_imagesloaded($bool, $img_url_featured) . ' alt="' . esc_attr(get_the_title()) . '" />';
		$img_url_thumbnail = ts_resize('thumbnails', $src);
		$featimage_thumbnail = '<img ' . ts_imagesloaded($bool, $img_url_thumbnail) . ' alt="' . esc_attr(get_the_title()) . '" />';
	} else {
		$featimage_featured = '<img ' .  ts_imagesloaded($bool, $noimg_url). ' alt="' . esc_attr(get_the_title()) . '" />';
		$featimage_thumbnail = '<img ' .  ts_imagesloaded($bool, $noimg_url). ' alt="' . esc_attr(get_the_title()) . '" />';
	}

	// Get the date
	if (ts_human_type_date_format()) {
		$article_date = human_time_diff(strtotime(get_the_time('Y-m-d H:i:s'))).' '.__('ago', 'touchsize');
	} else {
		$article_date =  get_the_date();
	}
	$store_image_featured[$post_number] = $featimage_featured;
	$store_image_thumbnail[$post_number]['image'] = $featimage_thumbnail;
	$store_image_thumbnail[$post_number]['date'] = $article_date;
	$store_image_thumbnail[$post_number]['title'] = esc_attr($post->post_title);
	$store_image_thumbnail[$post_number]['img-url'] = $src;
	$the_permalink[$post_number] = get_permalink();
	$single_id[$post_number] = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
	$store_post_type[$post_number] = $post->post_type;
	$post_number++;
}

if( $custom_post == 'post' ){
?>
	<div class="col-lg-12 ts-tab-subnav">
		<div class="tab-content">
		<?php foreach($store_image_featured as $key=>$url_img_featured) : ?>
			<div class="tab-pane <?php if( $key == 0 ) echo 'active' ?>" id="<?php echo $single_id[$key]; ?>">
				<a href="<?php echo $the_permalink[$key]; ?>">
					<?php echo $url_img_featured; ?>
					<?php
						if ( ts_overlay_effect_is_enabled() ) {
							echo '<div class="' . ts_overlay_effect_type() . '"></div>';
						}
					?>
				</a>
			</div>
		<?php endforeach; ?>
		</div>

		<ul class="nav nav-tabs <?php if( $scroll == 'y' ) echo 'is-scrollable'; else echo 'row'; ?>" role="tablist" data-scroll="<?php if( $scroll == 'y' ) echo 'true'; else echo 'false'; ?>" >
		<?php foreach($store_image_thumbnail as $key=>$url_img_thumbnail) : ?>
			<li class="tab-item <?php if( $key == 0 ) echo 'active '; if( $scroll == 'n' ) echo 'col-lg-3 col-md-3 col-sm-3' ?>">
				<a href="#<?php echo $single_id[$key]; ?>" role="tab" data-toggle="tab">
					<?php echo $url_img_thumbnail['image']; ?>
					<?php
						if ( ts_overlay_effect_is_enabled() ) {

							echo '<div class="' . ts_overlay_effect_type() . '"></div>';
						}
					?>
					<div class="video-info">
						<h3 class="video-title"><?php echo $url_img_thumbnail['title']; ?></h3>
						<div class="video-meta-date"><?php echo $url_img_thumbnail['date']; ?></div>
					</div>
				</a>
			</li>
		<?php endforeach; ?>
		</ul>
	</div>

<?php } elseif ( $custom_post == 'video' ) { ?>

	<div class="col-lg-12 ts-tab-subnav">
		<div class="tab-content">
			<?php if( isset($videos) && is_array($videos) && !empty($videos) ) : ?>
				<?php foreach($videos as $key=>$video) : ?>
					<?php $url_video = (isset($video['extern_url']) && !empty( $video['extern_url'])) ? esc_url($video['extern_url']) : NULL; ?>
					<?php if( isset($url_video) ) : ?>
						<div class="tab-pane <?php if( $key == 0 ) echo 'active'; ?>" id="<?php echo $single_id[$key]; ?>">
							<?php echo apply_filters('the_content', $url_video); ?>
						</div>
					<?php endif; ?>
					<?php $your_url = (isset($video['your_url']) && !empty( $video['your_url'] )) ? esc_url($video['your_url']) : NULL; ?>
					<?php if( isset($your_url) ) : ?>
						<div class="tab-pane <?php if( $key == 0 ) echo 'active'; ?>" id="<?php echo $single_id[$key]; ?>">
							<div class="embedded_videos">
								<?php

									$atts = array(
										'src'      => $your_url,
										'poster'   => $store_image_thumbnail[$key]['img-url'],
										'loop'     => '',
										'autoplay' => '',
										'preload'  => 'metadata',
										'height'   => 640,
										'width'    => 1380,
									);

									echo wp_video_shortcode($atts);
								?>
							</div>
						</div>
					<?php endif; ?>
					<?php if( isset($video['embed']) && !empty($video['embed']) ) : ?>
						<div class="tab-pane <?php if( $key == 0 ) echo 'active'; ?>" id="<?php echo $single_id[$key]; ?>">
							<?php echo apply_filters('the_content', $video['embed']); ?>
						</div>
					<?php endif; ?>
					<?php if( !isset($video['your_url']) && empty( $video['your_url']) && !isset($video['extern_url']) && empty( $video['extern_url']) ) : ?>
						<div class="tab-pane <?php if( $key == 0 ) echo 'active'; ?>" id="<?php echo $single_id[$key]; ?>">
							<a href="<?php echo $the_permalink[$key]; ?>">
								<?php echo $store_image_featured[$key]; ?>
								<?php
									if ( ts_overlay_effect_is_enabled() ) {
										echo '<div class="' . ts_overlay_effect_type() . '"></div>';
									}
								?>
							</a>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
		<ul class="nav nav-tabs <?php if( $scroll == 'y' ) echo 'is-scrollable'; else echo 'row'; ?>" role="tablist">
			<?php foreach($store_image_thumbnail as $key=>$url_img_thumbnail) : ?>
				<li class="tab-item <?php if( $key == 0 ) echo 'active '; if( $scroll == 'n' ) echo 'col-lg-3 col-md-3 col-sm-3' ?>">
					<a href="#<?php echo $single_id[$key]; ?>" role="tab" data-toggle="tab">
						<?php echo $url_img_thumbnail['image']; ?>
						<?php
							if ( ts_overlay_effect_is_enabled() ) {
								echo '<div class="' . ts_overlay_effect_type() . '"></div>';
							}
						?>
						<div class="video-info">
							<h3 class="video-title"><?php echo $url_img_thumbnail['title']; ?></h3>
							<div class="video-meta-date"><?php echo $url_img_thumbnail['date']; ?></div>
						</div>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php }else if( $custom_post == 'video_post' ){ ?>
<div class="col-lg-12 ts-tab-subnav">
	<div class="tab-content">
		<?php foreach($store_post_type as $key_post_type=>$value) : ?>
			<?php if( $value == 'post' ) : ?>
				<div class="tab-pane<?php if( $key_post_type == 0 ) echo ' active' ?>" id="<?php echo $single_id[$key_post_type]; ?>">
					<a href="<?php echo $the_permalink[$key_post_type]; ?>">
						<?php echo $store_image_featured[$key_post_type]; ?>
						<?php
							if ( ts_overlay_effect_is_enabled() ) {
								echo '<div class="' . ts_overlay_effect_type() . '"></div>';
							}
						?>
					</a>
				</div>
			<?php endif;  ?>
			<?php if( $value == 'video' ) : ?>
				<?php if( isset($videos) && is_array($videos) && !empty($videos) ) : ?>
					<?php $extern_url = (isset($videos[$key_post_type]['extern_url']) && !empty( $videos[$key_post_type]['extern_url'])) ? esc_url($videos[$key_post_type]['extern_url']) : NULL; ?>
					<?php if( isset($extern_url) ) : ?>
						<div class="tab-pane<?php if( $key_post_type == 0 ) echo ' active'; ?>" id="<?php echo $single_id[$key_post_type]; ?>">
							<?php echo apply_filters('the_content',$extern_url); ?>
						</div>
					<?php endif; ?>
					<?php $your_url = (isset($videos[$key_post_type]['your_url']) && !empty( $videos[$key_post_type]['your_url'])) ? esc_url($videos[$key_post_type]['your_url']) : NULL; ?>
					<?php if( isset($your_url) ) : ?>
						<div class="tab-pane<?php if( $key_post_type == 0 ) echo ' active'; ?>" id="<?php echo $single_id[$key_post_type]; ?>">
							<div class="embedded_videos">
								<?php
								$atts = array(
									'src'      => $your_url,
									'poster'   => '',
									'loop'     => '',
									'autoplay' => '',
									'preload'  => 'metadata',
									'height'   => 640,
									'width'    => 1380,
								);
								 echo wp_video_shortcode($atts); ?>
							</div>
						</div>
					<?php endif; ?>
					<?php if( isset($videos[$key_post_type]['your_url']) && empty( $videos[$key_post_type]['your_url']) && isset($videos[$key_post_type]['extern_url']) && empty( $videos[$key_post_type]['extern_url']) ) : ?>
						<div class="tab-pane<?php if( $key_post_type == 0 ) echo ' active'; ?>" id="<?php echo $single_id[$key_post_type]; ?>">
							<a href="<?php echo $the_permalink[$key_post_type]; ?>">
								<?php echo $store_image_featured[$key_post_type]; ?>
								<?php
									if ( ts_overlay_effect_is_enabled() ) {
										echo '<div class="' . ts_overlay_effect_type() . '"></div>';
									}
								?>
							</a>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
	<ul class="nav nav-tabs <?php if( $scroll == 'y' ) echo 'is-scrollable'; else echo 'row'; ?>" role="tablist">
		<?php foreach($store_post_type as $key_post_type=>$value) : ?>
			<?php if( $value == 'post' ) : ?>
				<li class="tab-item<?php if( $key_post_type == 0 ) echo ' active'; if( $scroll == 'n' ) echo ' col-lg-3 col-md-3 col-sm-3' ?>">
					<a href="#<?php echo $single_id[$key_post_type]; ?>" role="tab" data-toggle="tab">
						<?php echo $store_image_thumbnail[$key_post_type]['image']; ?>
						<?php
							if ( ts_overlay_effect_is_enabled() ) {
								echo '<div class="' . ts_overlay_effect_type() . '"></div>';
							}
						?>
						<div class="video-info">
							<h3 class="video-title"><?php echo $store_image_thumbnail[$key_post_type]['title']; ?></h3>
							<div class="video-meta-date"><?php echo $store_image_thumbnail[$key_post_type]['date']; ?></div>
						</div>
					</a>
				</li>
			<?php endif; ?>
			<?php if( $value == 'video' ) : ?>
				<li class="tab-item<?php if( $scroll == 'n' ) echo ' col-lg-3 col-md-3 col-sm-3'; if( $key_post_type == 0 ) echo ' active'; ?>">
					<a href="#<?php echo $single_id[$key_post_type]; ?>" role="tab" data-toggle="tab">
						<?php echo $store_image_thumbnail[$key_post_type]['image']; ?>
						<?php
							if ( ts_overlay_effect_is_enabled() ) {
								echo '<div class="' . ts_overlay_effect_type() . '"></div>';
							}
						?>
						<div class="video-info">
							<h3 class="video-title"><?php echo $store_image_thumbnail[$key_post_type]['title']; ?></h3>
							<div class="video-meta-date"><?php echo $store_image_thumbnail[$key_post_type]['date']; ?></div>
						</div>
					</a>
				</li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
</div>
<?php } ?>