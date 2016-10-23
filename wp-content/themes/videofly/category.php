<?php

get_header();

$options = get_option('videofly_layout');
$title = single_cat_title('', false);

$sidebar_options = $options['category_layout']['sidebar'];
$view_type = $options['category_layout']['display-mode'];
$view_options = $options['category_layout'][$view_type];
$view_options['display-mode'] = $view_type;

extract(layoutCompilator::build_sidebar( $sidebar_options ));

$content = layoutCompilator::last_posts_element($view_options, $wp_query);

$classContent = (isset($sidebar_options['size']) && $sidebar_options['size'] == '1-3') ? 'col-lg-8 col-md-8 col-sm-12' : 'col-lg-9
 col-md-9 col-sm-12';
$before_content = '<div class="row">';
$after_content = '</div>';

if ( isset($sidebar_options['position']) && $sidebar_options['position'] === 'left' ) {
	$content =  $sidebar_content  . '<div class="' . $classContent . '">'. $before_content . $content . $after_content. '</div>' ;
} else if ( isset($sidebar_options['position']) && $sidebar_options['position'] === 'right' ) {
	$content = '<div class="' . $classContent . '">' . $before_content  . $content . $after_content . '</div>' . $sidebar_content ;
}
?>
<section id="main" class="row">
	<div class="container">
		<?php 
			$breadcrumbs = get_option('videofly_single_post', array('breadcrumbs' => 'y')); 
			if( $breadcrumbs['breadcrumbs'] === 'y' ) echo vdf_breadcrumbs();
		?>
		<h3 class="archive-title">
			<?php echo strip_tags($title, '<span>'); ?>
		</h3>
		<div class="archive-desc">
			<?php echo category_description(get_cat_ID(single_cat_title('', false))); ?> 
		</div>
		<div class="row">
			<?php echo vdf_var_sanitize($content); ?>
		</div>
	</div>
</section>
<?php get_footer(); ?>