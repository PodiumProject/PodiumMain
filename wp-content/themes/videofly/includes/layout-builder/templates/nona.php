<?php
global $post, $article_options;

$taxonomy = $post->post_type == 'post' ? 'category' : ($post->post_type == 'video' ? 'videos_categories' : 'gallery_categories');


if ( has_post_thumbnail($post->ID) ) {

	$src = wp_get_attachment_url(get_post_thumbnail_id($post->ID));

	$featimage = vdf_resize('slider', $src, false);

} else {
	$featimage = get_template_directory_uri() . '/images/noimage.jpg';
}

$categories = wp_get_post_terms($post->ID, $taxonomy);
ob_start();
ob_clean();
?>
	<div>
		<div class="featimg">
			<a href="<?php the_permalink(); ?>">
				<img src="<?php echo esc_url($featimage); ?>" alt="<?php the_title(); ?>" />
			</a>
		</div>
		<div class="ts-info-slider">
			<section class="container">
				<a href="<?php the_permalink(); ?>">
					<h3 class="entry-title"><?php the_title(); ?></h3>
				</a>

				<span class="entry-excerpt">
					<?php vdf_excerpt(400, $post->ID, 'show-subtitle'); ?>
				</span>
				<a href="<?php the_permalink(); ?>" class="ts-btn-slider"><?php esc_html_e('Read more', 'videofly'); ?></a>
			</section>
		</div>
	</div>
<?php
	$article_options['nona-info'] .= ob_get_clean();

	ob_start();
	ob_clean();
?>
	<div class="nona-nav">
		<div class="featured-image">
			<img src="<?php echo (has_post_thumbnail($post->ID) ? esc_url(aq_resize($featimage, 500, 200, true)) : $featimage); ?>" alt="<?php the_title(); ?>" />
		</div>
		<section>
			<h5 class="entry-title"><?php the_title(); ?></h5>
			<span class="nav-descript">
				<?php vdf_excerpt(90, $post->ID, 'show-subtitle'); ?>
			</span>
		</section>
	</div>
<?php $article_options['nona-nav'] .= ob_get_clean(); ?>