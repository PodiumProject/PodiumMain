<?php

get_header();

global $wp_query;

$options = get_option('videotouch_layout');
$title = '';

if ( is_day() ) :
	$title = '<b>' . get_the_date() . '&nbsp;<span>(' . count($posts) . ')</span>' . '</b>';
elseif ( is_month() ) :
	$title = '<b>' . get_the_date( 'F Y' ) . '&nbsp;<span>(' . count($posts) . ')</span>' . '</b>';
elseif ( is_year() ) :
	$title = '<b>' . get_the_date( 'Y' ) . '&nbsp;<span>(' . count($posts) . ')</span>' . '</b>';
else :
	$post_type = $post->post_type;
	$taxonomies = get_object_taxonomies($post_type);
	$tag = $wp_query->queried_object->name;
	
	$title = '<b>' . $tag . '&nbsp;<span>(' . count($posts) . ')</span>' . '</b>';
endif;

$sidebar_options = $options['archive_layout']['sidebar'];
$view_type = $options['archive_layout']['display-mode'];
$view_options = $options['archive_layout'][$view_type];
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
		<?php 
			if ( $post_type == "video" ):
		?>
			<div class="archive-desc">
				<p>
					<?php echo category_description(get_cat_ID(single_cat_title('', false))); ?> 
				</p>
			</div>
		<?php endif ?>
		<div class="row">
			<?php echo $content; ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>

