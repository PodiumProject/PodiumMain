<?php

/* Super posts view template below */
###########

// Get the options

global $article_options;

// Get the featured image
$src = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );

$img_url = ts_resize('superpost', $src);
$noimg_url = get_template_directory_uri() . '/images/noimage.jpg';
$bool = fields::get_options_value('videotouch_general', 'enable_imagesloaded');

if ( $src ) {
	$featimage = '<img ' . ts_imagesloaded($bool, $img_url) . ' alt="' . esc_attr(get_the_title()) . '" />';
} else {
	$featimage = '<img ' .  ts_imagesloaded($bool, $noimg_url). ' alt="' . esc_attr(get_the_title()) . '" />';
}

// Get the date
if (ts_human_type_date_format()) {
	$article_date = human_time_diff(strtotime(get_the_time('Y-m-d H:i:s'))).' '.__('ago', 'touchsize');
} else {
	$article_date =  get_the_date();
}

// Get the categories of the article
if ( get_post_type( get_the_ID() ) == 'portfolio' ) {
	$category_tax = 'portfolio_categories';
} else{
	$category_tax = 'category';
}

$topics = wp_get_post_terms( get_the_ID() , $category_tax );

$terms = array();
if( !empty( $topics ) ){
    foreach ( $topics as $topic ) {
        $term = get_category( $topic->slug );
        array_push( $terms, $topic->slug );
    }
}
$article_categories = '';
foreach ($terms as $key => $term) {
	$article_categories .= '<li>' . '<a href="' . esc_attr(get_term_link($term, $category_tax)) . '" title="' . __('View all articles from: ', 'touchsize') . $term . '" ' . '>' . $term.'</a></li>';;
}

// Get article columns by elements per row
$columns_class = LayoutCompilator::get_column_class($article_options['elements-per-row']);

// Add article specific classes

?>
<div class="<?php echo $columns_class; ?>">
	<article <?php echo post_class(); if( is_sticky(get_the_ID()) ) echo ' ts-sticky-post'; ?>>
		<header>
			<div class="image-holder">
				<a href="<?php the_permalink(); ?>">
					<?php echo $featimage; ?>
					<?php
						if ( ts_overlay_effect_is_enabled() ) {
							echo '<div class="' . ts_overlay_effect_type() . '"></div>';
						}
					?>
				</a>
			</div>
		</header>
		<section>
			<?php touchsize_likes($post->ID, '<div class="entry-meta-likes">', '</div>'); ?>
			<div class="entry-title">
				<a href="<?php the_permalink(); ?>"><h4 class="title"><?php esc_attr(the_title()); ?></h4></a>
			</div>
			<div class="entry-meta">
				<ul>
					<li><?php echo $article_date; ?></li>
					<li><?php _e('by','touchsize') ?> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a></li>
				</ul>
			</div>
		</section>
		<?php 
		$edit_post_simple_user = (isset($article_options['edit']) && $article_options['edit'] === true) ? true : NULL;
		if( isset($edit_post_simple_user) && $edit_post_simple_user === true && current_user_can('simple_user') && !current_user_can('manage_options') ) : ?>
				<a class="edit-post-link" href="<?php echo site_url('/edit-post'); ?>?id=<?php echo the_ID(); ?>"><?php _e('Edit', 'touchsize'); ?></a>
		<?php endif; ?>
	</article>
</div>