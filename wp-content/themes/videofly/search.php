<?php

get_header();

global $wp_query;
$options = get_option('videofly_layout');

$title = esc_html__(' results found for: ','videofly') . '<b>' . get_search_query() . '</b>';
$sidebar_options = $options['search_layout']['sidebar'];
$view_type = $options['search_layout']['display-mode'];
$view_options = $options['search_layout'][$view_type];
$view_options['display-mode'] = $view_type;


extract(layoutCompilator::build_sidebar( $sidebar_options ));

$content = layoutCompilator::last_posts_element($view_options, $wp_query);
$search_input = layoutCompilator::searchbox_element(array('align' => 'left', 'design' => ''));

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
		<?php if( isset($wp_query->found_posts) && $wp_query->found_posts == 0 ) : ?>
			<h3 class="searchpage"><?php echo esc_html__('Strange. We have nothing on this.','videofly'); ?></h3>
			<span class="subsearch"><?php echo esc_html__('Please do another search, and try to provide more details on what you are looking for.','videofly'); ?></span>
		<?php endif; ?>
		<div class="row">
			<?php echo vdf_var_sanitize($search_input); ?>
		</div>
		<h3 class="archive-title searchcount">
			<?php echo '<span>'.$wp_query->found_posts.'</span>' . $title; ?>
		</h3>
		<div class="row">
			<?php echo vdf_var_sanitize($content); ?>
		</div>
	</div>
</section>
<?php get_footer(); ?>