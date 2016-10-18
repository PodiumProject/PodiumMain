<?php

get_header();

$options = get_option('videotouch_layout');
$title = '<b>' . single_cat_title('', false) . '&nbsp;<span>(' . count($posts) . '&nbsp;'. __('posts found', 'touchsize').')</span>' . '</b>';

$sidebar_options = $options['category_layout']['sidebar'];
$view_type = $options['category_layout']['display-mode'];
$view_options = $options['category_layout'][$view_type];
$view_options['display-mode'] = $view_type;

extract(layoutCompilator::build_sidebar( $sidebar_options ));

$content = layoutCompilator::last_posts_element($view_options, $wp_query);

$classContent = (isset($sidebar_options['size']) && $sidebar_options['size'] == '1-3') ? 'col-lg-8 col-md-8 col-sm-12' : 'col-lg-9
 col-md-9 col-sm-12';

if ( @$sidebar_options['position'] === 'left' ) {
	$content = $sidebar_content .'<div class="' . $classContent . '">'. $content .'</div>';
} else if ( @$sidebar_options['position'] === 'right' ) {
	$content = '<div class="' . $classContent . '">'. $content .'</div>'. $sidebar_content;
}
?>
<section id="main" class="row">
	<div class="container">
		<?php 
			$breadcrumbs = get_option('videotouch_single_post', array('breadcrumbs' => 'y')); 
			if( $breadcrumbs['breadcrumbs'] === 'y' ) echo ts_breadcrumbs();
		?>
		<h3 class="archive-title">
			<?php echo $title; ?>
		</h3>
		<div class="archive-desc">
			<p>
				<?php echo category_description(get_cat_ID(single_cat_title('', false))); ?> 
			</p>
		</div>
		<div class="row">
			<?php echo $content; ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>