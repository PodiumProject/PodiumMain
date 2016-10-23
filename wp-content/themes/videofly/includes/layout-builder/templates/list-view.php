<?php

/* List view template below */
###########

// Get the options

global $article_options;


	$image_split = 'col-lg-12 col-md-12';
	$content_split = 'col-lg-12 col-md-12';

// Get the featured image
$src = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );

$meta = isset($article_options['show-meta']) ? $article_options['show-meta'] : 'n';
$social_sharing = get_option('videofly_styles', array('sharing_overlay' => 'N'));
$post_type = get_post_type(get_the_ID());

$show_image = (isset($article_options['show-image']) && ($article_options['show-image'] == 'y' || $article_options['show-image'] == 'n')) ? $article_options['show-image'] : 'y';
if( $show_image == 'y' && has_post_thumbnail(get_the_ID()) ){
	$src = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
	$img_url = vdf_resize('bigpost', $src);

	$bool = fields::get_options_value('videofly_general', 'enable_imagesloaded');

	if ( $src ) {
		$featimage = '<img ' . vdf_imagesloaded($bool, $img_url) . ' alt="' . esc_attr(get_the_title()) . '" />';
	}
}

$style_hover = get_option( 'videofly_styles' );
$hover_effect = (isset(	$style_hover['style_hover'] ) && ($style_hover['style_hover'] == 'style1' || $style_hover['style_hover'] == 'style2')) ? $style_hover['style_hover'] : 'style1';

$article_date =  get_the_date();

// Get the tags of the article

$article_tags = get_the_tag_list('<li>', '</li>');

// Get related posts

if ( isset($article_options['related-posts']) && $article_options['related-posts'] === 'y' ) {
	$related_posts = LayoutCompilator::get_related_posts( get_the_ID(), get_the_tags());
} else {
	$related_posts = '';
}

// Add article specific classes

if ( $meta === 'y' ) {
	$article_classes = ' article-meta-shown ';
} else{
	$article_classes = ' article-meta-hidden ';
}

if ( isset($article_options['display-title']) ) {
	$article_classes .= ' ' . $article_options['display-title'] . ' ';
}

// Get post rating
$rating_final = vdf_get_rating($post->ID);

// Check post is sticky
$post_is_sticky = '';
$post_is_sticky_div = '';
if( is_sticky(get_the_ID()) ){
	$post_is_sticky = ' data-sticky="is-sticky" ';
	$post_is_sticky_div = '<div class="is-sticky-div">'.esc_html__('is sticky','videofly').'</div>';
}


$article_categories = '';
$taxonomies = get_object_taxonomies($post->post_type);
$exclude_taxonomies = array('post_tag', 'post_format');

foreach($taxonomies as $taxonomy){
	if( isset($taxonomy) && !in_array($taxonomy, $exclude_taxonomies) ){
		$topics = wp_get_post_terms( get_the_ID() , $taxonomy );
		$i_terms = 1;

		foreach ($topics as $term) {
			if( $i_terms == count($topics) ) {
				$dividing_category = '';
			}else{
				$dividing_category = '\\';
			}
			$article_categories .= '<li>' . '<a href="' . get_term_link($term->slug, $taxonomy) . '" title="' . esc_html__('View all articles from: ', 'videofly') . $term->name . '" ' . '>' . $term->name . '</a></li>';

			$i_terms++;
		}
	}
}

$open_video = $post->post_type == 'video' && $article_options['play'] == 'modal' ? ' data-postid="' . $post->ID . '" data-video="modal"' : '';
?>
<div class="col-lg-12">
	<article <?php echo vdf_var_sanitize($post_is_sticky); ?> data-featured-image="<?php if( !isset($featimage)) echo 'no-image'; ?>" <?php echo post_class( $article_classes ); echo ' entry'; ?> >
		<?php if( isset($featimage) ) : ?>
			<header>
				<div class="image-holder">
					<?php if( isset($rating_final) ) : ?>
						<div class="post-rating-circular">
							<div class="circular-content">
								<div class="counted-score"><?php echo vdf_var_sanitize($rating_final); ?>/10</div>
							</div>
						</div>
					<?php endif; ?>
					<?php
						echo vdf_var_sanitize($featimage);
						if ( vdf_overlay_effect_is_enabled() ) echo '<div class="' . vdf_overlay_effect_type() . '"></div>';
					 	vdfHoverStyle($post->ID, $post->post_type);
					?>
				</div>
			</header>
		<?php endif; ?>
		<section>
			<?php echo vdf_var_sanitize($post_is_sticky_div); ?>
			<h3 class="entry-title" <?php echo vdf_var_sanitize( $open_video ); ?>><a href="<?php the_permalink(); ?>" title="<?php the_title() ?>"><?php the_title() ?></a></h3>
			<?php if ( $meta === 'y' ) : ?>
				<span class="entry-meta-date"><?php echo vdf_var_sanitize($article_date); ?></span>
			<?php endif; ?>
			<div class="entry-excerpt">
				<?php vdf_excerpt('list_excerpt', get_the_ID(), 'show-excerpt'); ?>
			</div>
		</section>
		<footer class="row">
			<?php if ( $meta == 'y' ) : ?>
				<div class="col-lg-6 col-md-6 col-sm-12">
					<ul class="entry-meta">
						<li class="entry-meta-category">
							<ul>
								<?php echo vdf_var_sanitize($article_categories); ?>
							</ul>
						</li>
						<li class="entry-meta-author">
							<a href="<?php echo get_author_posts_url($post->post_author); ?>"><i class="icon-user"></i><?php the_author(); ?></a>
						</li>
					</ul>
				</div>
			<?php endif; ?>
			<div class="col-lg-6 col-md-6 col-sm-12">
				<ul class="entry-meta ts-right-or-left">
					<li class="entry-secondary-meta">
						<ul>
							<?php if ( $meta == 'y' ) : ?>
								<?php vdf_get_views($post->ID, '<li class="entry-views"><i class="icon-views"></i> ', '</li>'); ?>
								<?php touchsize_likes($post->ID, '<li class="entry-likes">', '</li>'); ?>
							<?php endif; ?>
							<li class="ts-read-btn">
								<a href="<?php the_permalink(); ?>"<?php echo vdf_var_sanitize( $open_video ); ?>>
									<?php echo ($post->post_type == 'video' ? '<i class="icon-play"></i> '. esc_html__('PLAY', 'videofly') : esc_html__('Read more', 'videofly')); ?>
								</a>
							</li>
						</ul>
					</li>
				</ul>
			</div>
		</footer>
	</article>
</div>
