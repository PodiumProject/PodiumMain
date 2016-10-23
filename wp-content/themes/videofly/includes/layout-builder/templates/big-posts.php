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
$social_sharing = get_option('videofly_styles', array('sharing_overlay' => 'N'));

$post_type = get_post_type(get_the_ID());
$related = (isset($article_options['related-posts']) && ($article_options['related-posts'] === 'y' || $article_options['related-posts'] === 'n')) ? $article_options['related-posts'] : 'n';

if (isset($article_options['image-position'])) $image_position = $article_options['image-position'];
else $image_position = 'left';
$show_excerpt = (isset($article_options['excerpt']) && ($article_options['excerpt'] == 'y' || $article_options['excerpt'] == 'n')) ? $article_options['excerpt'] : 'y';

// Get the featured image
$show_image = (isset($article_options['show-image']) && ($article_options['show-image'] == 'y' || $article_options['show-image'] == 'n')) ? $article_options['show-image'] : 'y';
$content_split = ( $show_image == 'n' || !has_post_thumbnail($post->ID) ) ? 'col-lg-12 col-md-12 col-sm-12 col-xs-12' : $content_split;

if( $show_image == 'y' && has_post_thumbnail(get_the_ID()) ){
	$src = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
	$img_url = vdf_resize('bigpost', $src);

	$bool = fields::get_options_value('videofly_general', 'enable_imagesloaded');

	if ( $src ) {
		$featimage = '<img ' . vdf_imagesloaded($bool, $img_url) . ' alt="' . esc_attr(get_the_title()) . '" />';
	}
}

$style_hover = get_option( 'videofly_styles' );
$hover_effect = (isset($style_hover['style_hover'] ) && ($style_hover['style_hover'] == 'style1' || $style_hover['style_hover'] == 'style2')) ? $style_hover['style_hover'] : 'style1';

// Get related posts
$related_posts = ($related === 'y') ? LayoutCompilator::get_related_posts( get_the_ID(), get_the_tags()) : '';

// Add article specific classes
$article_classes = ($meta === 'y') ? ' article-meta-shown ' : ' article-meta-hidden ';

if ( $article_options['image-split'] ) {
	$article_classes .= ' article-split-'.$article_options['image-split'] . ' ';
}
if ( $related == 'y' ) {
	$article_classes .= ' article-related-shown ';
}

if ( $show_image == 'n' || !has_post_thumbnail($post->ID) ) {
	$article_classes .= ' no-image ';
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
$taxonomies = get_object_taxonomies(get_post_type(get_the_ID()));
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
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<article <?php echo vdf_var_sanitize($post_is_sticky); ?> data-position="<?php echo strip_tags($image_position); ?>" <?php echo post_class( $article_classes );?> >
		<div class="row">
			<?php if ( $image_position == 'left' || $image_position == 'mosaic' ): ?>
				<?php if ( isset($featimage) ) : ?>
					<header class="<?php echo vdf_var_sanitize($image_split); ?>">
						<div class="image-holder">
							<?php if( isset($rating_final) ) : ?>
								<div class="post-rating-circular">
									<div class="circular-content">
										<div class="counted-score"><?php echo vdf_var_sanitize($rating_final); ?>/10</div>
									</div>
								</div>
							<?php endif; ?>
							<?php echo vdf_var_sanitize($featimage); ?>
							<?php
							if ( vdf_overlay_effect_is_enabled() ) {
								echo '<div class="' . vdf_overlay_effect_type() . '"></div>';
							}
							?>

							<?php vdfHoverStyle(get_the_ID(), $post->post_type); ?>

						</div>
					</header>
				<?php endif; ?>
				<section class="<?php echo vdf_var_sanitize($content_split); ?>">
					<?php if ($meta === 'y') :?>
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
					<?php endif; ?>
					<h3<?php echo vdf_var_sanitize( $open_video ); ?>><a href="<?php the_permalink(); ?>" title="<?php the_title() ?>"><?php the_title(); ?></a></h3>
					<?php if( $show_excerpt == 'y' ) : ?>
						<div class="entry-excerpt excerpt">
							<?php vdf_excerpt('bigpost_excerpt', get_the_ID(), 'show-subtitle'); ?>
						</div>
					<?php endif; ?>
					<a href="<?php the_permalink(); ?>" class="ts-read-btn"<?php echo vdf_var_sanitize( $open_video ); ?>>
						<?php echo ($post->post_type == 'video' ? '<i class="icon-play"></i> '. esc_html__('PLAY', 'videofly') : esc_html__('Read more', 'videofly')); ?>
					</a>
					<?php echo vdf_var_sanitize($post_is_sticky_div); ?>
				</section>
			<?php elseif ($image_position == 'right'): ?>
				<section class="<?php echo vdf_var_sanitize($content_split); ?>">
					<?php if ($meta === 'y') :?>
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
					<?php endif; ?>
					<h3 <?php echo vdf_var_sanitize( $open_video ); ?> ><a href="<?php the_permalink(); ?>" title="<?php the_title() ?>"><?php the_title(); ?></a></h3>
					<?php if( $show_excerpt == 'y' ) : ?>
						<div class="entry-excerpt excerpt">
							<?php vdf_excerpt('bigpost_excerpt', get_the_ID(), 'show-subtitle'); ?>
						</div>
					<?php endif; ?>
					<a href="<?php the_permalink(); ?>" class="ts-read-btn">
						<?php echo ($post->post_type == 'video' ? '<i class="icon-play"></i> '. esc_html__('PLAY', 'videofly') : esc_html__('Read more', 'videofly')); ?>
					</a>
					<?php echo vdf_var_sanitize($post_is_sticky_div); ?>
				</section>
				<?php if ( isset($featimage) ) : ?>
					<header class="<?php echo vdf_var_sanitize($image_split); ?>">
						<div class="image-holder">
							<?php if( isset($rating_final) ) : ?>
								<div class="post-rating-circular">
									<div class="circular-content">
										<div class="counted-score"><?php echo vdf_var_sanitize($rating_final); ?>/10</div>
									</div>
								</div>
							<?php endif; ?>
							<?php echo vdf_var_sanitize($featimage); ?>
							<?php
							if ( vdf_overlay_effect_is_enabled() ) {
								echo '<div class="' . vdf_overlay_effect_type() . '"></div>';
							}
							?>
							<?php vdfHoverStyle(get_the_ID(), $post->post_type); ?>
						</div>
					</header>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<?php echo vdf_var_sanitize($related_posts); ?>
	</article>
</div>
<?php $article_options['i']++; ?>