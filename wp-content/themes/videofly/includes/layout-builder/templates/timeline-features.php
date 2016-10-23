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
		$bool = fields::get_options_value('videofly_general', 'enable_imagesloaded');

		$effect = isset($timeline_option->effect) && $timeline_option->effect !== 'none' ? $timeline_option->effect : 'none';
		$classDelay = isset($timeline_option->delay) && $timeline_option->delay !== 'none' ? ' '. $timeline_option->delay : '';

		?>
		<div class="ts-timeline<?php echo ($effect !== 'none' ? ' animatedParent animateOnce' : '') ?>">
			<article class="timeline-entry<?php echo ($effect !== 'none' ? ' animated '. $effect . $classDelay : '') ?>">
				<?php if( $align === 'left' ) : ?>
					<aside class="timeline-panel">
						<img src="<?php echo vdf_var_sanitize($src, 'esc_url'); ?>" alt="" />
					</aside>
				<?php endif; ?>
				<aside class="timeline-panel">
					<h3 class="entry-title"><?php echo vdf_var_sanitize($title, 'esc_attr'); ?></h3>
					<div class="entry-description">
						<?php echo apply_filters('ts_the_content', $text); ?>
					</div>
				</aside>
				<?php if( $align === 'right' ) : ?>
					<aside class="timeline-panel">
						<img src="<?php echo vdf_var_sanitize($src, 'esc_url'); ?>" alt="" />
					</aside>
				<?php endif; ?>
			</article>
		</div>
	<?php endforeach; ?>
<?php endif; ?>