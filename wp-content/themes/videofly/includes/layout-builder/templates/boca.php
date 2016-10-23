<?php
global $post, $article_options;

$taxonomy = $post->post_type == 'post' ? 'category' : ($post->post_type == 'video' ? 'videos_categories' : 'gallery_categories');

if ( has_post_thumbnail($post->ID) ) {

	$src = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
	$featimage = aq_resize($src, 435, 500, true);

} else {
	$featimage = get_template_directory_uri() . '/images/noimage.jpg';
}

$categories = wp_get_post_terms($post->ID, $taxonomy);
?>

<article class="row">
	<header class="col-lg-5 col-md-5 col-sm-12">
		<div>
			<a href="<?php the_permalink(); ?>">
				<img src="<?php echo esc_url($featimage); ?>" alt="<?php the_title(); ?>" />
			</a>
			<?php if ( $article_options['custom-post'] == 'video' ) : ?>
				<a href="<?php the_permalink(); ?>" class="entry-play-btn icon-play"></a>
			<?php else : ?>
				<?php esc_html_e('Read more', 'videofly'); ?>
			<?php endif; ?>
			<?php if ( !empty($categories) ) : ?>
				<ul class="entry-meta-category">
					<?php foreach ( $categories as $category ) : ?>
						<li>
							<a href="<?php echo get_term_link($category->slug, $taxonomy); ?>">
								<?php echo esc_attr($category->name) ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</header>
	<section class="col-lg-7 col-md-7 col-sm-12">
		<div class="entry-content-slider">
			<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<span class="entry-meta-date"><?php echo human_time_diff(time(), strtotime($post->post_date)) .' '. esc_html__('ago', 'videofly'); ?></span>
			<div class="entry-excerpt">
				<?php vdf_excerpt(200, $post->ID, 'show-subtitle'); ?>
			</div>
			<div class="slider-footer">
				<a href="<?php the_permalink(); ?>" class="ts-btn-slider"><?php esc_html_e('READ MORE', 'videofly'); ?></a>
				<ul class="customNavigation">
					<li><span class="ar-left slick-arrow"><i class="icon-left"></i></span></li>
					<li><span class="ar-right slick-arrow"><i class="icon-right"></i></span></li>
				</ul>
			</div>
		</div>
	</section>
</article>