<?php
global $article_options;

tsIncludeScripts(array( 'isotope', 'jquery.lazyload.min', 'fancybox.pack', 'fancybox-thumbs', 'mCustomScrollbar'));

$images = (isset($article_options['images']) && !empty($article_options['images']) && is_string($article_options['images'])) ? explode(',', $article_options['images']) : '';

$storagePosts = array();
if( is_array($images) && !empty($images) ){

	foreach($images as $image){

		$image = (!empty($image)) ? explode(':', $image, 2) : '';
		if( is_array($image) && count($image) == 2  ){
			$queryAttachment = get_post($image[0]);
			if( $queryAttachment !== NULL ){
				$storagePosts[] = $queryAttachment; 	
			}
		}
	}
}

$imageSizes = get_option('videofly_image_sizes');
$imgHeightDesktop = (isset($imageSizes['gallery-masonry-layout']['height-desktop']) && is_numeric($imageSizes['gallery-masonry-layout']['height-desktop'])) ? absint($imageSizes['gallery-masonry-layout']['height-desktop']) : 500;
$imgHeightMobile = (isset($imageSizes['gallery-masonry-layout']['height-mobile']) && is_numeric($imageSizes['gallery-masonry-layout']['height-mobile'])) ? absint($imageSizes['gallery-masonry-layout']['height-mobile']) : 300;
$imgHeight = (wp_is_mobile()) ? $imgHeightMobile : $imgHeightDesktop;

$styleOptions = get_option('videofly_styles');
$hover = (isset($styleOptions['hover_gallery']) && ($styleOptions['hover_gallery'] == 'open-on-click' || $styleOptions['hover_gallery'] == 'slide-from-bottom')) ? $styleOptions['hover_gallery'] : 'open-on-click';

$caption_option = ($hover == 'open-on-click') ? 'with-caption' : 'without-caption';

?>
<div class="col-md-12">
	<div class="ts-gallery-element ts-masonry-gallery">
		<div class="inner-gallery-container">
			<?php foreach($storagePosts as $image) : 
				$full_img_url = wp_get_attachment_url($image->ID);
				$img_url = aq_resize($full_img_url, 9999, $imgHeight, false, true);
				$alt_text = get_post_meta($image->ID, '_wp_attachment_image_alt', true);

				$descriptionImage = $image->post_excerpt;
				$titleImage = $image->post_title;
				$urlImage = get_post_meta($image->ID, 'ts-image-url', true);
				$urlImage = (isset($urlImage)) ? esc_url($urlImage) : '';
			?>
				<div class="item item-gallery">
					<figure>
						<img class="lazy" data-original="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($alt_text); ?>">
					</figure>
					<div class="overlay-effect" data-trigger-caption="<?php echo vdf_var_sanitize($caption_option); ?>">
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
	</div><!-- end gallery element -->
</div>