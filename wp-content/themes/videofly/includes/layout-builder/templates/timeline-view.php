<?php
global $article_options;

// Get article columns by elements per row
$columns_class = (isset($article_options['elements-per-row']) && $article_options['elements-per-row'] !== '' && (int)$article_options['elements-per-row'] !== 0) ? LayoutCompilator::get_column_class((int)$article_options['elements-per-row']) : '';

// Get the featured image
$src = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );

$img_url = vdf_resize('timeline', $src);

$noimg_url = get_template_directory_uri() . '/images/noimage.jpg';
$bool = fields::get_options_value('videofly_general', 'enable_imagesloaded');
$social_sharing = get_option('videofly_styles', array('sharing_overlay' => 'N'));

if ( $src ) {
	$featimage = '<img '. vdf_imagesloaded($bool, $img_url) .' alt="' . esc_attr(get_the_title()) . '" />';
} else {
	$featimage = '<img '. vdf_imagesloaded($bool, $noimg_url) .' alt="' . esc_attr(get_the_title()) . '" />';
}

$style_hover = get_option('videofly_styles');
$hover_effect = (isset(	$style_hover['style_hover'] ) && ($style_hover['style_hover'] == 'style1' || $style_hover['style_hover'] == 'style2')) ? $style_hover['style_hover'] : 'style1';

// Get the categories of the article
$taxonomies = get_object_taxonomies(get_post_type(get_the_ID()));
$exclude_taxonomies = array('post_tag', 'post_format');
$topics = array();
$article_categories = '';

$select_by_category = (isset($article_options['behavior']) && $article_options['behavior'] == 'tabbed') ? 'tabbed' : '';
$attribute_by_category = 'style="display:none;" data-category="';

foreach($taxonomies as $taxonomy){
	if( isset($taxonomy) && !in_array($taxonomy, $exclude_taxonomies) ){
		$topics = wp_get_post_terms(get_the_ID() , $taxonomy);
		$i_terms = 1;

		foreach ($topics as $term) {
			if( $i_terms == count($topics) ) {
				$comma = '';
				$dividing_category = '';
			}else{
				$comma = '<li> </li>';
				$dividing_category = '\\';
			}
			$article_categories .= '<li>' . '<a href="' . get_term_link($term->slug, $taxonomy) . '" title="' . esc_html__('View all articles from: ', 'videofly') . $term->name . '" ' . '>' . $term->name . '</a></li>'.$comma.'';

			if( $select_by_category == 'tabbed' ){
				$attribute_by_category .= $term->term_id . $dividing_category;
			}

			$i_terms++;
		}
	}
}
$attribute_by_category .= '"';
// Get post rating
$rating_final = vdf_get_rating($post->ID);

$open_video = $post->post_type == 'video' && $article_options['play'] == 'modal' ? ' data-postid="' . $post->ID . '" data-video="modal"' : '';

?>
<div class="ts-inner buffer-left">
	<article>
		<header>
			<h3<?php echo vdf_var_sanitize( $open_video ); ?>><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<?php if( isset($article_options['image']) && $article_options['image'] == 'y' ) : ?>
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
			<?php endif; ?>
			<div class="entry-meta">
				<ul>
					<li class="entry-meta-date"><span><?php echo date('d M', strtotime($post->post_date)) ?></span></li>
					<li class="entry-meta-time"><span><?php echo date('h:i', strtotime($post->post_date)) ?></span></li>
				</ul>
			</div>
		</header>
		<section>
			<div class="entry-excerpt">
				<?php if( !empty($post->subtitle) ): ?>
					<span class="entry-preamble"><?php vdf_excerpt('timeline_excerpt', get_the_ID(), 'show-subtitle'); ?></span>
				<?php endif; ?>
				<?php vdf_excerpt('timeline_excerpt', get_the_ID(), 'show-content'); ?>
			</div>
		</section>
		<footer class="row">
			<?php if ( $article_options['show-meta'] == 'y' ) : ?>
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
							<?php if ( $article_options['show-meta'] === 'y' ) : ?>
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