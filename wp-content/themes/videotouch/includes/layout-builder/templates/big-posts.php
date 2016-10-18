<?php

/* Big posts template below */
###########

// Get the options

global $article_options;

// Get the split columns
$splits = LayoutCompilator::get_splits(@$article_options['image-split']);

$image_split   = $splits['split1'];
$content_split = $splits['split2'];

$meta = (isset($article_options['show-meta']) && ($article_options['show-meta'] === 'y' || $article_options['show-meta'] === 'n')) ? $article_options['show-meta'] : 'n';
$social_sharing = get_option('videotouch_styles', array('sharing_overlay' => 'N'));
$modal = (isset($article_options['modal']) && !empty($article_options['modal']) && ($article_options['modal'] === 'y' || $article_options['modal'] === 'n')) ? $article_options['modal'] : '';
$enter_modal = ($modal === 'y') ? ' data-toggle="modal" data-target="#modal_video"' : ''; 
$post_type = get_post_type(get_the_ID());
$related = (isset($article_options['related-posts']) && ($article_options['related-posts'] === 'y' || $article_options['related-posts'] === 'n')) ? $article_options['related-posts'] : 'n';
$image_position = (isset($article_options['image-position']) && ($article_options['image-position'] === 'left' || $article_options['image-position'] === 'right' || $article_options['image-position'] === 'mosaic')) ? $article_options['image-position'] : 'left';

if( $modal === 'y' && $post_type === 'video' ){
	$video_url = get_post_meta(get_the_ID(), 'video', TRUE);
	if( isset($video_url['extern_url']) && !empty($video_url['extern_url']) ){
		$video = apply_filters('the_content', esc_url($video_url['extern_url']));
	}
}
// Get the featured image
$src = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );

$img_url = ts_resize('bigpost', $src);
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
if ( $post_type == 'portfolio' ) {
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

if ( $related === 'y' ) {
	$related_posts = LayoutCompilator::get_related_posts( get_the_ID(), get_the_tags());
} else {
	$related_posts = '';
}

// Add article specific classes

$article_classes = '';
if ( $article_options['image-split'] ) {
	$article_classes .= ' article-split-'.$article_options['image-split'] . ' ';
}
if ( $meta === 'y' ) {
	$article_classes .= ' article-meta-shown ';
} else{
	$article_classes .= ' article-meta-hidden ';
}
if ( $article_options['display-title'] ) {
	$article_classes .= ' ' . $article_options['display-title'] . ' ';
}

?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<article <?php echo post_class( $article_classes ); if( is_sticky(get_the_ID()) ) echo ' ts-sticky-post'; ?>>
		<div class="row">
			<?php if ( @$article_options['display-title'] == 'title-above-image' ): ?>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="entry-title">
						<a href="<?php the_permalink(); ?>" title="<?php the_title() ?>">
							<h3 class="title"><?php the_title() ?></h3>
						</a>
					</div>
					<?php if ( $meta === 'y' ): ?>
						<div class="entry-author">
							<ul>
								<li class="author-name"><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" class="author-avatar"><?php echo ts_display_gravatar(50); ?></a> <?php _e('by','touchsize') ?> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a></li>
								<li class="author-published"><?php _e('added','touchsize') ?> <span><?php echo $article_date; ?></span></li>
							</ul>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<?php if( $image_position === 'left' || ($article_options['i'] % 2 !== 0 && $image_position === 'mosaic') ) : ?>
				<header class="<?php echo $image_split; ?>">
					<div class="image-holder default-effect">
						<a href="<?php the_permalink(); ?>">
							<?php echo $featimage; ?>
						</a>
						<?php
							if ( ts_overlay_effect_is_enabled() ) {
								echo '<div class="' . ts_overlay_effect_type() . '"></div>';
							}
						?>
						<?php if ( $social_sharing['sharing_overlay'] === 'Y' ) : ?>
							<div class="overlay-effect">
								<a <?php if( $modal === 'y' ) echo 'data-id="' . $post->ID . '"' . $enter_modal . ' data-href="' . get_permalink(get_the_ID()) . '"'; ?> href="<?php if( $modal === 'n' || $modal === '' ) the_permalink(); ?>" class="view-more">
									<i class="icon-search"></i>
									<span><?php _e('view','touchsize'); ?></span>
								</a>
							</div>
						<?php endif ?>
					</div>
				</header>
			<?php endif; ?>
			<div class="<?php echo $content_split; ?>">
				<section>
					<?php if ( @$article_options['display-title'] == 'title-above-excerpt' ): ?>
						<div class="entry-title">
							<a href="<?php the_permalink(); ?>" title="<?php the_title() ?>">
								<h3 class="title"><?php the_title() ?></h3>
							</a>
						</div>
						<?php if ( $meta === 'y' ): ?>
							<div class="entry-author">
								<ul>
									<li class="author-name"><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" class="author-avatar"><?php echo ts_display_gravatar(50); ?></a> <?php _e('by','touchsize') ?> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a></li>
									<li class="author-published"><?php _e('added','touchsize') ?> <span><?php echo $article_date; ?></span></li>
								</ul>
							</div>
						<?php endif; ?>
					<?php endif; ?>
					<div class="entry-excerpt excerpt">
						<?php
						    $ln = fields::get_options_value('videotouch_general', 'bigpost_excerpt');
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
					<a class="btn small" href="<?php the_permalink(); ?>" title="<?php the_title() ?>"><?php _e('view','touchsize');?> <i class="icon-right"></i></a>
				</section>
			</div>
			<?php if( $image_position === 'right' || ($article_options['i'] % 2 === 0 && $image_position === 'mosaic') ) : ?>
				<div class="<?php echo $image_split; ?>">
					<div class="image-holder default-effect">
						<a href="<?php the_permalink(); ?>">
							<?php echo $featimage; ?>
						</a>
						<?php
							if ( ts_overlay_effect_is_enabled() ) {
								echo '<div class="' . ts_overlay_effect_type() . '"></div>';
							}
						?>
						<?php if ( $social_sharing['sharing_overlay'] === 'Y' ) : ?>
							<div class="overlay-effect">
								<a <?php if( $modal === 'y' ) echo 'data-id="' . $post->ID . '"' . $enter_modal . ' data-href="' . get_permalink(get_the_ID()) . '"'; ?> href="<?php if( $modal === 'n' || $modal === '' ) the_permalink(); ?>" class="view-more">
									<i class="icon-search"></i>
									<span><?php _e('view','touchsize'); ?></span>
								</a>
							</div>
						<?php endif ?>
					</div>
				</div>
			<?php endif; ?>
			<?php echo $related_posts; ?>
		</div>
		<?php 
		$edit_post_simple_user = (isset($article_options['edit']) && $article_options['edit'] === true) ? true : NULL;
		if( isset($edit_post_simple_user) && $edit_post_simple_user === true && current_user_can('simple_user') && !current_user_can('manage_options') ) : ?>
				<a class="edit-post-link" href="<?php echo site_url('/edit-post'); ?>?id=<?php echo the_ID(); ?>"><?php _e('Edit', 'touchsize'); ?></a>
		<?php endif; ?>
	</article>
	<?php if( isset($video) ) : ?>
		<div data-video-id="<?php echo $post->ID; ?>" class="hidden">
			<div class="post-video">
	            <?php echo $video; ?>
	        </div>
		</div>
	<?php endif; $article_options['i']++; ?>
</div>