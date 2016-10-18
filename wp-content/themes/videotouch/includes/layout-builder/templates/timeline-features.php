<?php 
global $article_options;

$timeline_options = (isset($article_options['timeline']) && $article_options['timeline'] !== '[]' && !empty($article_options['timeline']) && is_string($article_options['timeline'])) ? json_decode(stripslashes($article_options['timeline'])) : NULL;

if( is_array($timeline_options) && !empty($timeline_options) ) :
	foreach($timeline_options as $timeline_option) :
		$title = (isset($timeline_option->title) && is_string($timeline_option->title)) ? esc_attr($timeline_option->title) : '';
		$text = (isset($timeline_option->text) && is_string($timeline_option->text)) ? apply_filters('the_content', $timeline_option->text) : '';
		$align = (isset($timeline_option->align) && ($timeline_option->align === 'left' || $timeline_option->align === 'right')) ? $timeline_option->align : 'left';
		$src = (isset($timeline_option->image) && is_string($timeline_option->image)) ? esc_url($timeline_option->image) : NULL;
		$noimg_url = get_template_directory_uri() . '/images/noimage.jpg';
		$bool = fields::get_options_value('videotouch_general', 'enable_imagesloaded');

		$featimage = (isset($src)) ? '<img ' . ts_imagesloaded($bool, ts_resize('bigpost', $src)) . ' alt="' . esc_attr(get_the_title()) . '" />' : '<img ' .  ts_imagesloaded($bool, $noimg_url). ' alt="' . esc_attr(get_the_title()) . '" />';

		 ?>
		<div id="ts-timeline">
			<article class="timeline-entry">
				<?php if( $align === 'left' ) : ?>
					<aside class="timeline-panel">
						<?php echo $featimage; ?>
					</aside>
				<?php endif; ?>
				<aside class="timeline-panel">
					<h3 class="entry-title"><?php echo $title; ?></h3>
					<div class="entry-description">
						<?php echo apply_filters('the_content', $text); ?>
					</div>
				</aside>
				<?php if( $align === 'right' ) : ?>
					<aside class="timeline-panel">
						<?php echo $featimage; ?>
					</aside>
				<?php endif; ?>
			</article>
		</div>
	<?php endforeach; ?>
<?php endif; ?>