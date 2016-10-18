<?php 
global $article_options;

$title = (isset($article_options['title']) && is_string($article_options['title'])) ? esc_attr($article_options['title']) : '';
$text = (isset($article_options['text']) && is_string($article_options['text'])) ? esc_attr($article_options['text']) : '';
$align = (isset($article_options['align']) && ($article_options['align'] === 'ribbon-center' || $article_options['align'] === 'ribbon-left') || $article_options['align'] === 'ribbon-right') ? $article_options['align'] : 'ribbon-left';
$background = (isset($article_options['background']) && is_string($article_options['background'])) ? 'background-color: ' . $article_options['background'] . ';color: ' . $article_options['background'] : '';
$text_color = (isset($article_options['text-color']) && is_string($article_options['text-color'])) ? 'color: ' . $article_options['text-color'] . ';' : '';
$image = (isset($article_options['image']) && is_string($article_options['image']) && !empty($article_options['image'])) ? '<img src="' . esc_url($article_options['image']) . '" alt="' . $title . '"/>' : NULL;

$arrow_bg_color = (isset($article_options['background']) && is_string($article_options['background'])) ? $article_options['background'] : '';

$button = array();
$button['button-align'] = $article_options['button-align'];
$button['mode-display'] = $article_options['button-mode-display'];
$button['border-color'] = esc_attr($article_options['button-border-color']);
$button['bg-color'] = esc_attr($article_options['button-background-color']);
$button['text-color'] = esc_attr($article_options['button-text-color']);
$button['button-icon'] = esc_attr($article_options['button-icon']);
$button['url'] = esc_url($article_options['button-url']);
$button['size'] = $article_options['button-size'];
$button['target'] = $article_options['button-target'];
$button['text'] = $article_options['button-text'];

?>

<div class="col-md-12 col-lg-12">
	<div class="ts-ribbon-banner">
		<div class="ribbon-image"><?php echo $image; ?></div>
		<div style="<?php echo $background; ?>" class="ts-ribbon <?php echo $align; ?>">
			<div class="rb-content" style="<?php echo $text_color; ?>">
				<div class="rb-separator">
					<span style="<?php echo $text_color; ?>"><?php echo $title; ?></span>
				</div>
				<div class="rb-description">
					<?php echo $text; ?>
				</div>
				<?php echo LayoutCompilator::buttons_element($button); ?>
			</div>
			<div class="left-arrow" style="background: -webkit-linear-gradient(to right top, transparent 50%, <?php $arrow_bg_color ?> 50%); background: linear-gradient(to right top, transparent 50%, <?php echo $arrow_bg_color ?> 50%);"></div>
			<div class="right-arrow" style="background: -webkit-linear-gradient(to left top, transparent 50%, <?php echo $arrow_bg_color ?> 50%); background: linear-gradient(to left top, transparent 50%, <?php echo $arrow_bg_color ?> 50%);"></div>
		</div>
		</div>
		
</div>