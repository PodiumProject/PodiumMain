<?php 
global $article_options;

$title = (isset($article_options['title']) && is_string($article_options['title'])) ? esc_attr($article_options['title']) : '';
$text = (isset($article_options['text']) && is_string($article_options['text'])) ? esc_attr($article_options['text']) : '';
$align = (isset($article_options['align']) && ($article_options['align'] === 'ribbon-center' || $article_options['align'] === 'ribbon-left') || $article_options['align'] === 'ribbon-right') ? $article_options['align'] : 'ribbon-left';
$background = (isset($article_options['background'])) ? 'background-color: ' . esc_attr($article_options['background']) . ';color: ' . esc_attr($article_options['background']) : '';
$text_color = (isset($article_options['text-color'])) ? 'color: ' . esc_attr($article_options['text-color']) . ';' : '';
$image = (isset($article_options['image']) && is_string($article_options['image']) && !empty($article_options['image'])) ? '<img src="' . esc_url($article_options['image']) . '" alt="' . $title . '"/>' : NULL;

$button = array();
$button['button-align'] = esc_attr($article_options['button-align']);
$button['mode-display'] = esc_attr($article_options['button-mode-display']);
$button['border-color'] = esc_attr($article_options['button-border-color']);
$button['bg-color'] = esc_attr($article_options['button-background-color']);
$button['text-color'] = esc_attr($article_options['button-text-color']);
$button['button-icon'] = esc_attr($article_options['button-icon']);
$button['url'] = esc_url($article_options['button-url']);
$button['size'] = esc_attr($article_options['button-size']);
$button['target'] = esc_attr($article_options['button-target']);
$button['text'] = esc_attr($article_options['button-text']);

?>

<div class="col-md-12 col-lg-12">
	<div class="ts-ribbon-banner">
		<div class="ribbon-image"><?php echo vdf_var_sanitize($image); ?></div>
		<div style="<?php echo vdf_var_sanitize($background); ?>" class="ts-ribbon <?php echo vdf_var_sanitize($align); ?>">
			<div class="rb-content" style="<?php echo vdf_var_sanitize($text_color, 'esc_attr'); ?>">
				<div class="rb-separator">
					<span style="<?php echo vdf_var_sanitize($text_color, 'esc_attr'); ?>"><?php echo vdf_var_sanitize($title, 'esc_attr'); ?></span>
				</div>
				<div class="rb-description">
					<?php echo vdf_var_sanitize($text); ?>
				</div>
				<?php echo LayoutCompilator::buttons_element($button); ?>
			</div>
		</div>
	</div>
</div>