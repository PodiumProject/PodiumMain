<?php

/* Grid view template below */
###########

// Get the options

global $article_options;

// Get the featured image
$src = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );

if ( isset($article_options['behavior']) && $article_options['behavior'] == 'scroll' ) {
	$scroll = 'scroll';
} elseif ( isset($article_options['behavior']) && $article_options['behavior'] == 'masonry' ) {
	$scroll = 'masonry';
} elseif ( isset($article_options['behavior']) && $article_options['behavior'] == 'carousel' ) {
	$scroll = 'carousel';
} else{
	$scroll = 'normal';
}
$post_count = $article_options['j'];
$post_per_page = (isset($article_options['elements-per-row']) && (int)$article_options['elements-per-row'] !== 0) ? (int)$article_options['elements-per-row'] : '2';
$meta = (isset($article_options['show-meta']) && ($article_options['show-meta'] === 'y' || $article_options['show-meta'] === 'n')) ? $article_options['show-meta'] : 'n';

$social_sharing = get_option('videotouch_styles', array('sharing_overlay' => 'N'));
$modal = (isset($article_options['modal']) && !empty($article_options['modal']) && ($article_options['modal'] === 'y' || $article_options['modal'] === 'n')) ? $article_options['modal'] : '';
$enter_modal = ($modal === 'y') ? ' data-toggle="modal" data-target="#modal_video"' : ''; 
$post_type = get_post_type(get_the_ID());
$related = (isset($article_options['related-posts']) && ($article_options['related-posts'] === 'y' || $article_options['related-posts'] === 'n')) ? $article_options['related-posts'] : 'n';

if( $modal === 'y' && $post_type === 'video' ){
	$video_url = get_post_meta(get_the_ID(), 'video', TRUE);
	if( isset($video_url['extern_url']) && !empty($video_url['extern_url']) ){
		$video = apply_filters('the_content', esc_url($video_url['extern_url']));
	}
}


$i = $article_options['i'];
$posts_inside = '';

$ts_image_is_masonry = false;
if ( isset($article_options['behavior']) && $article_options['behavior'] === 'masonry' ) {
    $ts_image_is_masonry = true;
}

$img_url = ts_resize('grid', $src, $ts_image_is_masonry);

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

// Get related posts
$related_posts = '';

if ( $related === 'y' ) {
	$related_posts = LayoutCompilator::get_related_posts(get_the_ID(), get_the_tags());
} else {
	$related_posts = '';
}


	

// Get article columns by elements per row
$columns_class = LayoutCompilator::get_column_class($article_options['elements-per-row']);

// Add article specific classes

$article_classes = '';

if ( $meta === 'y' ) {
	$article_classes .= ' article-meta-shown ';
} else{
	$article_classes .= ' article-meta-hidden ';
}
if ( @$article_options['display-title'] ) {
	$article_classes .= ' ' . $article_options['display-title'] . ' ';
}
if ( isset($article_options['behavior']) && $article_options['behavior'] == 'masonry' ) {
	$article_classes .= ' masonry-element ';
}
$posts_inside = '';

if( ( $i % $post_per_page ) === 1 && $scroll === 'scroll' ){
	$posts_inside = ' posts-inside-'.$post_per_page . ' posts-total-' .$post_per_page;
}
if( ($i % $post_per_page) == 1 && ( $post_count - $i ) < $post_per_page && ( $post_count % $post_per_page ) !== 0 ){
	$class = $post_count % $post_per_page;
	$posts_inside = ' posts-inside-'.$class . ' posts-total-' .$post_per_page;
}

?>
<?php if( ( $i % $post_per_page ) === 1  && $scroll === 'scroll' ) echo '<div class="scroll-container'. $posts_inside .'">'; ?>
<?php if($post_per_page == 1  && $scroll === 'scroll' ) echo '<div class="scroll-container'. $posts_inside .'">'; ?>
	<div class="<?php echo $columns_class; if( is_sticky(get_the_ID()) ) echo ' ts-sticky-post'; ?> item">
		<article <?php echo post_class( $article_classes ); ?>>
			<header>
				<?php if ( @$article_options['display-title'] === 'title-above-image' ): ?>
					<div class="entry-title">
						<a href="<?php the_permalink(); ?>"><h3 class="title"><?php the_title(); ?></h3></a>
					</div>
					<?php if( $meta === 'y' ) : ?>
						<div class="ts-view-entry-meta-date">
							<ul>
								<li><?php echo $article_date; ?></li>
							</ul>
						</div>
					<?php endif; ?>
				<?php endif; ?>
				<div class="image-holder default-effect">
					<a href="<?php the_permalink();?>">
						<?php echo $featimage; ?>
						<?php
							if ( ts_overlay_effect_is_enabled() ) {
								echo '<div class="' . ts_overlay_effect_type() . '"></div>';
							}
						?>
					</a>
					<?php if ( $social_sharing['sharing_overlay'] === 'Y' ) : ?>
						<div class="overlay-effect">
							<div class="entry-overlay">
								<a class="view-more" <?php if( $modal === 'y' ) echo 'data-id="' . $post->ID . '"' . $enter_modal . ' data-href="' . get_permalink(get_the_ID()) . '"'; ?> href="<?php if( $modal === 'n' || $modal == '' ) the_permalink(); ?>">
									<?php
										if( get_post_type( get_the_ID() ) == 'video' ){
											$icon_class = 'icon-play';
											$more_word = __('play', 'touchsize');
										} else{
											$icon_class = 'icon-search';
											$more_word =  __('more', 'touchsize');
										}
									?>
									<i class="<?php echo $icon_class; ?>"></i>
									<span><?php echo $more_word; ?></span>
								</a>

							</div>
						</div>	
					<?php endif; ?>
				</div>
			</header>
			<section>
				<?php if ( @$article_options['display-title'] == 'title-above-excerpt' ): ?>
					<div class="entry-title">
						<a href="<?php the_permalink(); ?>"><h3 class="title"><?php esc_attr(the_title()); ?></h3></a>
					</div>
					<?php if( $meta === 'y' ) : ?>
						<div class="ts-view-entry-meta-date">
							<ul>
								<li><?php echo $article_date; ?></li>
							</ul>
						</div>
					<?php endif; ?>
				<?php endif; ?>
				<div class="entry-excerpt">
					<?php
					    $ln = fields::get_options_value('videotouch_general', 'grid_excerpt');
					    if (!empty($post->post_excerpt)) {
					        if (strlen(strip_tags(strip_shortcodes($post->post_excerpt))) > intval($ln)) {
					            echo mb_substr(strip_tags(strip_shortcodes($post->post_excerpt)), 0, intval($ln)) . '...';
					        } else {
					            echo strip_tags(strip_shortcodes($post->post_excerpt));
					        }
					    } else {
					        if (strlen(strip_tags(strip_shortcodes($post->post_content))) > intval($ln)) {
					            echo mb_substr(strip_tags(strip_shortcodes($post->post_content)), 0, intval($ln)) . '...';
					        } else {
					            echo strip_tags(strip_shortcodes($post->post_content));
					        }
					    }    
					?>
				</div>
			</section>
			<footer>
				<div class="entry-footer">
					<div class="row">
						<div class="col-lg-8 col-md-8 col-sm-12">
						<?php if ( $meta === 'y' ) : ?>
							<div class="entry-meta">
								<?php touchsize_likes($post->ID, '<div class="entry-meta-likes">', '</div>'); ?>
								<div class="entry-meta-views">
									<i class="icon-views"></i>
									<span class="view-count"><?php ts_get_views($post->ID); ?></span>
								</div>
								<div class="entry-meta-comments">
									<a href="<?php comments_link(); ?>">
										<i class="icon-comments"></i>
										<span class="comment-count"><?php echo ts_get_comment_count($post->ID); ?></span>
									</a>
								</div>
							</div>
						<?php endif; ?>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12 text-right">
							<a href="<?php the_permalink(); ?>" class="btn btn-grid-more"><?php if( $post_type == 'video' ) _e('play','touchsize'); else _e('more','touchsize'); ?><i class="icon-right"></i></a>
						</div>
					</div>
				</div>	
			</footer>
			<?php 
			$edit_post_simple_user = (isset($article_options['edit']) && $article_options['edit'] == true) ? true : NULL;
			if( isset($edit_post_simple_user) && $edit_post_simple_user == true && is_user_logged_in() ) : ?>
					<a class="edit-post-link" href="<?php echo site_url('/edit-post'); ?>?id=<?php echo the_ID(); ?>"><?php _e('Edit', 'touchsize'); ?></a>
			<?php endif; ?>
		</article>
		<?php if( $related === 'y' ) echo $related_posts; ?>
		<?php if( isset($video) ) : ?>
			<div data-video-id="<?php echo $post->ID; ?>" class="hidden">
				<div class="post-video">
	                <?php echo $video; ?>
	            </div>
			</div>
		<?php endif; ?>
	</div>
<?php
if( ( $i % $post_per_page ) == 0  && $scroll == 'scroll' || ( $i % $post_per_page ) !== 0  && $scroll == 'scroll' && $i === $post_count) echo '</div>';

$article_options['i']++;
?>