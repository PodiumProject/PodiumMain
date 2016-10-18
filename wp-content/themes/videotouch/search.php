<?php

get_header();

global $wp_query;
$options = get_option('videotouch_layout');

$title = __(' results found for: ','touchsize') . '<b>' . get_search_query() . '</b>';
$sidebar_options = $options['search_layout']['sidebar'];
$view_type = $options['search_layout']['display-mode'];
$view_options = $options['search_layout'][$view_type];
$view_options['display-mode'] = $view_type;

extract(layoutCompilator::build_sidebar( $sidebar_options ));

$content = layoutCompilator::last_posts_element($view_options, $wp_query);

$search_input = layoutCompilator::searchbox_element('');

if ( @$sidebar_options['position'] === 'left' ) {
	$content = $sidebar_content . $content;
} else if ( @$sidebar_options['position'] === 'right' ) {
	$content = $content . $sidebar_content;
}
?>

<section id="main" class="row">
	<div class="container">
		<?php if( isset($wp_query->found_posts) && $wp_query->found_posts == 0 ) : ?>
			<h3 class="searchpage"><?php echo __('Strange. We have nothing on this.','touchsize'); ?></h3>
			<span class="subsearch"><?php echo __('Please do another search, and try to provide more details on what you are looking for.','touchsize'); ?></span>
		<?php endif; ?>
		<div class="row">
			<?php echo $search_input; ?>
		</div>
		<div class="attention"><i class="icon-attention"></i></div>
		<h3 class="archive-title searchcount">
			<?php echo '<span>'.$wp_query->found_posts.'</span>' . $title; ?>
		</h3>
		<div class="row">
			<?php echo $content; ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>