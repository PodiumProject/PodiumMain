<?php

/* Super posts view template below */
###########

// Get the options

global $article_options;

// Get the featured image
$src = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );

$img_url = vdf_resize('superpost', $src);
$noimg_url = get_template_directory_uri() . '/images/noimage.jpg';
$bool = fields::get_options_value('videofly_general', 'enable_imagesloaded');

if ( $src ) {
	$featimage = '<img ' . vdf_imagesloaded($bool, $img_url) . ' alt="' . esc_attr(get_the_title()) . '" />';
} else {
	$featimage = '<img ' .  vdf_imagesloaded($bool, $noimg_url). ' alt="' . esc_attr(get_the_title()) . '" />';
}

$article_date =  get_the_date();

// Get the categories of the article
$post_type = get_post_type(get_the_ID());
$category_tax = ($post_type == 'portfolio' ? 'portfolio_categories' : ($post_type == 'video' ? 'videos_categories' : ($post_type == 'ts-gallery' ? 'gallery_categories' : ($post_type == 'event' ? 'event_categories' : 'category'))));

$topics = wp_get_post_terms( get_the_ID() , $category_tax );

$terms = array();
if( !empty( $topics ) ){
    foreach ( $topics as $topic ) {
        $term = get_category( $topic->slug );
        $terms[$topic->slug] = $topic->name;
    }
}
$article_categories = '';
$i = 1;
foreach ($terms as $key => $term) {
	if( $i === count($terms) ) $comma = '';
	else $comma = '<li>&nbsp;/&nbsp;</li>';
	$article_categories .= '<li>' . '<a href="' . get_term_link($key, $category_tax) . '" title="' . esc_html__('View all articles from: ', 'videofly') . $term . '" ' . '>' . $term.'</a></li>'.$comma.'';
	$i++;
}

// Get article columns by elements per row
$columns_class = LayoutCompilator::get_column_class($article_options['elements-per-row']);

// Check post is sticky
$post_is_sticky = '';
$post_is_sticky_div = '';
if( is_sticky(get_the_ID()) ){
	$post_is_sticky = ' data-sticky="is-sticky" ';
	$post_is_sticky_div = '<div class="is-sticky-div">'.esc_html__('is sticky','videofly').'</div>';
}

$open_video = $post->post_type == 'video' && $article_options['play'] == 'modal' ? ' data-postid="' . $post->ID . '" data-video="modal"' : '';

?>
<div class="<?php echo vdf_var_sanitize($columns_class); ?>">
	<article <?php echo vdf_var_sanitize($post_is_sticky); ?> <?php echo post_class(); ?> >
		<header>
			<div class="image-holder">
				<a href="<?php the_permalink(); ?>">
					<?php echo vdf_var_sanitize($featimage); ?>
					<?php
						if ( vdf_overlay_effect_is_enabled() ) {
							echo '<div class="' . vdf_overlay_effect_type() . '"></div>';
						}
					?>
				</a>
			</div>
		</header>
		<section>
			<div class="ts-inner-info">
				<h3 class="entry-title" <?php echo vdf_var_sanitize( $open_video ); ?>><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
				<div class="entry-excerpt">
					<?php vdf_excerpt('timeline_excerpt', get_the_ID(), 'show-subtitle'); ?>
				</div>
				<a href="<?php the_permalink(); ?>" class="ts-read-btn"<?php echo vdf_var_sanitize( $open_video ); ?>>
					<?php echo ($post->post_type == 'video' ? '<i class="icon-play"></i> '. esc_html__('PLAY', 'videofly') : esc_html__('Read more', 'videofly')); ?>
				</a>
			</div>
		</section>
		<?php echo vdf_var_sanitize($post_is_sticky_div); ?>
	</article>
</div>