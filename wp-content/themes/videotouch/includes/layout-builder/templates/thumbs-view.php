<?php

/* Thumbnail view template below */

// Get the options

global $article_options, $filter_class, $taxonomy_name;

// Get the featured image
$src = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );

$post_per_page = (isset($article_options['elements-per-row']) && (int)$article_options['elements-per-row'] !== 0) ? (int)$article_options['elements-per-row'] : '2';
$scroll = (isset($article_options['behavior']) && $article_options['behavior'] === 'scroll') ? $article_options['behavior'] : '';
$meta = (isset($article_options['meta-thumbnail']) && ($article_options['meta-thumbnail'] === 'y' || $article_options['meta-thumbnail'] === 'n')) ? $article_options['meta-thumbnail'] : 'n';

$post_count = (isset($article_options['j'])) ? $article_options['j'] : '';
$i = (isset($article_options['i'])) ? $article_options['i'] : '';

$ts_image_is_masonry = false;
if ( isset($article_options['behavior']) && $article_options['behavior'] == 'masonry' ) {
    $ts_image_is_masonry = true;
}

$img_url = ts_resize('thumbnails', $src, $ts_image_is_masonry);
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
if ( get_post_type( get_the_ID() ) === 'portfolio' ) {
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

if( @$filter_class === 'yes' ){
	$filter_categs = array();
	foreach (get_the_terms(get_the_ID(), $taxonomy_name) as $categ) {
		$filter_categs[] = 'ts-category-' . $categ->term_id;
	}
} else{
	$filter_categs = array();
}
$posts_inside = '';
if( ( $i % $post_per_page ) === 1 && $scroll === 'scroll' ){
	$posts_inside = ' posts-inside-'.$post_per_page . ' posts-total-' .$post_per_page;
}
if( ($i % $post_per_page) === 1 && ( $post_count - $i ) < $post_per_page && ( $post_count % $post_per_page ) !== 0 ){
	$class = $post_count % $post_per_page;
	$posts_inside = ' posts-inside-'.$class . ' posts-total-' .$post_per_page;
}
?>
<?php if( ( $i % $post_per_page ) === 1  && $scroll == 'scroll' ) echo '<div class="scroll-container'. $posts_inside .'">'; ?>
	<div class="item <?php echo $columns_class . ' ' . esc_attr(implode(" ", $filter_categs)); ?>">
		<article <?php echo post_class(); if( is_sticky(get_the_ID()) ) echo ' ts-sticky-post'; ?>>
			<div class="image-holder">
				<a href="<?php the_permalink(); ?>">
					<?php echo $featimage; ?>
					<?php
						if ( ts_overlay_effect_is_enabled() ) {
							echo '<div class="' . ts_overlay_effect_type() . '"></div>';
						}
					?>
				</a>
				<?php if (  $meta !== 'n' ) : ?>
					<?php touchsize_likes($post->ID, '<div class="entry-meta-likes">', '</div>'); ?>
				<?php endif; ?>
			</div>
			<div class="entry-content">
				<div class="entry-title">
					<a href="<?php the_permalink(); ?>">
						<h3 class="title"><?php esc_attr(the_title()) ?><i class="icon-right"></i></h3>
					</a>
				</div>
				<?php if (  $meta !== 'n' ) : ?>
					<div class="entry-meta-date">
						<span><?php _e('added','touchsize') ?> <?php echo $article_date; ?></span>
					</div>
				<?php endif; ?>
			</div>
			<?php 
			$edit_post_simple_user = (isset($article_options['edit']) && $article_options['edit'] == true) ? true : NULL;
			if( isset($edit_post_simple_user) && $edit_post_simple_user == true && is_user_logged_in() ) : ?>
					<a class="edit-post-link" href="<?php echo site_url('/edit-post'); ?>?id=<?php echo the_ID(); ?>"><?php _e('Edit', 'touchsize'); ?></a>
			<?php endif; ?>
		</article>
	</div>
<?php
	if( ( $i % $post_per_page ) == 0  && $scroll == 'scroll' || ( $i % $post_per_page ) !== 0  && $scroll == 'scroll' && $i === $post_count) echo '</div>';
	if( isset($article_options['i']) ) $article_options['i']++;
?>
