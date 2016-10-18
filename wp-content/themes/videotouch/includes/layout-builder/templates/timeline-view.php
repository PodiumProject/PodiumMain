<?php 
global $article_options;

// Get article columns by elements per row
$columns_class = (isset($article_options['elements-per-row']) && $article_options['elements-per-row'] !== '' && (int)$article_options['elements-per-row'] !== 0) ? LayoutCompilator::get_column_class((int)$article_options['elements-per-row']) : '';

// Get the featured image
$src = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
$meta = (isset($article_options['show-meta']) && ($article_options['show-meta'] == 'y' || $article_options['show-meta'] == 'n')) ? $article_options['show-meta'] : 'y';

$img_url = ts_resize('timeline', $src);

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

 ?>
<div class="col-lg-12 col-md-12">
	<article <?php post_class(); if( is_sticky(get_the_ID()) ) echo ' ts-sticky-post'; ?>>
		<div class="post-icon"><?php ts_get_icon_format($post->ID); ?></div>
		<div class="entry-meta">
			<?php if( $meta == 'y' ) : ?>
				<div class="post-date-add">
					<i class="icon-time"></i>&nbsp; <?php echo $article_date; ?></div>
				</div>
			<?php endif; ?>
		<?php if( isset($article_options['image']) && $article_options['image'] == 'y' ) : ?>
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
		<?php endif; ?>
		<div class="article-section">
			<a href="<?php the_permalink(); ?>">
				<h3 class="title"><?php esc_attr(the_title()); ?></h3>
			</a>
			<div class="entry-excerpt">
				<?php
				    $ln = fields::get_options_value('videotouch_general', 'timeline_excerpt');
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
		</div>
		<footer>
			<a href="<?php the_permalink(); ?>" class="btn"><?php _e('Read more','touchsize') ?></a>
		</footer>
	</article>
</div>