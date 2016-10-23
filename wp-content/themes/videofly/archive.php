<?php

get_header();

global $wp_query;

$options = get_option('videofly_layout');
$title = '';

if ( is_day() ) :
	$title = get_the_date();
elseif ( is_month() ) :
	$title = get_the_date( 'F Y' );
elseif ( is_year() ) :
	$title = get_the_date( 'Y' );
else :
	$post_type = $post->post_type;
	$taxonomies = get_object_taxonomies($post_type);
	$tag = $wp_query->queried_object->name;
	
	$title =  $tag;
endif;

$sidebar_options = $options['archive_layout']['sidebar'];
$view_type = $options['archive_layout']['display-mode'];
$view_options = $options['archive_layout'][$view_type];
$view_options['display-mode'] = $view_type;
$before_content = '<div class="row">';
$after_content = '</div>';

extract(layoutCompilator::build_sidebar( $sidebar_options ));

$content = layoutCompilator::last_posts_element($view_options, $wp_query);

$classContent = (isset($sidebar_options['size']) && $sidebar_options['size'] == '1-3') ? 'col-lg-8 col-md-8 col-sm-12' : 'col-lg-9
 col-md-9 col-sm-12';

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
			<?php echo vdf_var_sanitize($title); ?>
		</h3>
		<?php 
			if ( $post_type == "video" ):
		?>
			<div class="archive-desc">
				<?php echo category_description(get_cat_ID(single_cat_title('', false))); ?> 
			</div>
		<?php endif ?>
		<div class="row">
			<?php echo vdf_var_sanitize($content); ?>
		</div>
	</div>
</section>
<?php get_footer(); ?>

