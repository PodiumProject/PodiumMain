<?php

/* List view template below */
###########

// Get the options

global $article_options;

// Get the split columns
if( isset($article_options['image-split']) ){
	$splits = LayoutCompilator::get_splits(@$article_options['image-split']);
	$image_split   = $splits['split1'];
	$content_split = $splits['split2'];
}

// Get the featured image
$src = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );

//get the params for modal
$modal = (isset($article_options['modal']) && !empty($article_options['modal']) && ($article_options['modal'] == 'y' || $article_options['modal'] == 'n')) ? $article_options['modal'] : '';
$enter_modal = ($modal == 'y') ? ' data-toggle="modal" data-target="#modal_video"' : '';
$meta = (isset($article_options['show-meta']) && ($article_options['show-meta'] === 'y' || $article_options['show-meta'] === 'n')) ? $article_options['show-meta'] : 'n';
$social_sharing = get_option('videotouch_styles', array('sharing_overlay' => 'N'));
$post_type = get_post_type(get_the_ID());

if( $modal === 'y' && $post_type === 'video' ){
	$video_url = get_post_meta(get_the_ID(), 'video', TRUE);
	if( isset($video_url['extern_url']) && !empty($video_url['extern_url']) ){
		$video = apply_filters('the_content', esc_url($video_url['extern_url']));
	}
}

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


// Get the tags of the article

$article_tags = get_the_tag_list('<li>', '</li>');

// Get related posts

if ( isset($article_options['related-posts']) && $article_options['related-posts'] === 'y' ) {
	$related_posts = LayoutCompilator::get_related_posts( get_the_ID(), get_the_tags());
} else {
	$related_posts = '';
}

// Add article specific classes

$article_classes = '';
if ( isset($article_options['image-split']) ) {
	$article_classes .= ' article-split-'.$article_options['image-split'] . ' ';
}
if ( $meta === 'y' ) {
	$article_classes .= ' article-meta-shown ';
} else{
	$article_classes .= ' article-meta-hidden ';
}
if ( isset($article_options['display-title']) ) {
	$article_classes .= ' ' . $article_options['display-title'] . ' ';
}

?>
	<article <?php echo post_class( $article_classes ); if( is_sticky(get_the_ID()) ) echo ' ts-sticky-post'; ?>>
		<div class="featimg">
			<div class="image-holder share-effect">
				<a href="<?php the_permalink(); ?>">
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
							<span class="entry-overlay-span"><a <?php if( $modal == 'y' ) echo 'data-id="' . $post->ID . '"' . $enter_modal . ' data-href="' . get_permalink(get_the_ID()) . '"'; ?> href="<?php if( $modal === 'n' || $modal === '' ) the_permalink(); ?>"><?php if( $post_type === 'video' ) _e('play video','touchsize'); else _e('read more', 'touchsize'); ?></a></span>
							<ul class="share-options">
								<li>
									<a class="icon-facebook" target="_blank" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo get_permalink($post->ID); ?>"></a>
								</li>
								<li>
									<a class="icon-twitter" target="_blank" href="http://twitter.com/home?status=<?php echo urlencode($post->post_title); ?>+<?php echo get_permalink($post->ID); ?>"></a>
								</li>
								<li>
									<a class="icon-pinterest" target="_blank" href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&amp;media=<?php if(function_exists('the_post_thumbnail')) echo wp_get_attachment_url(get_post_thumbnail_id($post->ID)); ?>&amp;description=<?php echo urlencode(get_the_title()); ?>" ></a>
								</li>
							</ul>
						</div>
					</div>	
				<?php endif; ?>
			</div>
		</div>
		<div class="row">
			<header class="<?php if( isset($image_split) ) echo $image_split; ?>">
				<div class="entry-title">
					<a href="<?php the_permalink(); ?>"><h3 class="title"><?php the_title(); ?></h3></a>
				</div>
				<?php if( $meta === 'y' ) : ?>
					<div class="entry-author">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12">
								<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" class="author-avatar"><?php echo ts_display_gravatar(50); ?></a>
								<ul>
									<li class="author-name"><?php _e('by','touchsize') ?> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a></li>
									<li class="author-published"><?php _e('Published','touchsize') ?> <span><?php echo $article_date; ?></span></li>
									<?php touchsize_likes($post->ID, '<li class="entry-likes">', '</li> '); ?>
								</ul>	
							</div>
						</div>
					</div>
					<?php if( has_tag() ) : ?>
						<div class="entry-tags">
							<div class="row">
								<div class="col-lg-12 col-md-12">
										<div class="ts-tags-container">
											<span><?php _e('Tagged In','touchsize') ?></span>
											<?php the_tags('',''); ?>
										</div>
								</div>
							</div>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			</header>
			<div class="<?php if( isset($content_split) ) echo $content_split; ?>">
				<div class="entry-excerpt">
					<?php
					    $ln = fields::get_options_value('videotouch_general', 'list_excerpt');
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
				<a href="<?php the_permalink(); ?>" class="btn"><?php _e('Details','touchsize') ?></a>
			</div>
		</div>
		<?php if( isset($video) ) : ?>
			<div data-video-id="<?php echo $post->ID; ?>" class="hidden">
				<div class="post-video">
                    <?php echo $video; ?>
                </div>
			</div>
		<?php endif; ?>
		<?php 
		$edit_post_simple_user = (isset($article_options['edit']) && $article_options['edit'] === true) ? true : NULL;
		if( isset($edit_post_simple_user) && $edit_post_simple_user === true && current_user_can('simple_user') && !current_user_can('manage_options') ) : ?>
				<a class="edit-post-link" href="<?php echo site_url('/edit-post'); ?>?id=<?php echo the_ID(); ?>"><?php _e('Edit', 'touchsize'); ?></a>
		<?php endif; ?>
	</article>	