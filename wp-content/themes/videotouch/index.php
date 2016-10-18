<?php
get_header();

$options = get_option('videotouch_layout');
$title = '';

$sidebar_options = $options['blog_layout']['sidebar'];
$view_type = $options['blog_layout']['display-mode'];
$view_options = $options['blog_layout'][$view_type];
$view_options['display-mode'] = $view_type;


extract(layoutCompilator::build_sidebar( $sidebar_options ));

$content = '<div class="'. $content_class .'">'. layoutCompilator::last_posts_element($view_options, $wp_query) .'</div>';

if ( @$sidebar_options['position'] === 'left' ) {
	$content = $sidebar_content . $content;
} else if ( @$sidebar_options['position'] === 'right' ) {
	$content = $content . $sidebar_content;
}

?>
<section id="main" class="row">
	<div class="container">
		<div class="row">
			<?php echo $content; ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>