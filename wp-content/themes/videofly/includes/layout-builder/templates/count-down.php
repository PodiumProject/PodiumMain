<?php
global $article_options;

$title = (isset($article_options['title'])) ? esc_attr($article_options['title']) : '';
$date = (isset($article_options['date'])) ? esc_attr($article_options['date']) : NULL;
$time = (isset($article_options['hours'])) ? esc_attr($article_options['hours']) : NULL;
$style = (isset($article_options['style'])) ? esc_attr($article_options['style']) : '';

// Set the class for the style
if ( $style == 'big' ) {
	$vdf_countdown_class = 'ts-big-countdown';
} else{
	$vdf_countdown_class = 'ts-small-countdown';
}

if ( $date !== NULL && $time !== NULL) {
    ?>
    <div class="col-md-12 col-lg-12">
	    <div class="ts-countdown <?php echo vdf_var_sanitize($vdf_countdown_class); ?>">
			<h4 class="countdown-title"><?php if( isset($title) ) echo vdf_var_sanitize($title); ?></h4>
			<div class="countdown-icon-container">- <i class="icon-clock"></i> -</div>
			<ul class="time-remaining" data-date="<?php echo vdf_var_sanitize($date) ?>" data-time="<?php echo vdf_var_sanitize($time, 'esc_attr') ?>">
				<li>
					<div class="ts-days time">0</div>
					<span><?php esc_html_e('day', 'videofly'); ?></span>
				</li>

				<li>
					<div class="ts-hours time">0</div>
					<span><?php esc_html_e('hou', 'videofly'); ?></span>
				</li>
				<li>
					<div class="ts-minutes time">0</div>
					<span><?php esc_html_e('min', 'videofly'); ?></span>
				</li>
				<li>
					<div class="ts-seconds time">0</div>
					<span><?php esc_html_e('sec', 'videofly'); ?></span>
				</li>
			</ul>
		</div>
    </div>
	<?php
}
?>
