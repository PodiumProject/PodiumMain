<?php

global $article_options;

// Initializate variables
$post_id = $article_options['article']->ID;
$post_type = get_post_type($post_id);
$post_title = $article_options['article']->post_title;
$authorId = $article_options['article']->post_author;

$src = wp_get_attachment_url(get_post_thumbnail_id($post_id));
$img_url = vdf_resize('featured_article', $src);

$bool = fields::get_options_value('videofly_general', 'enable_imagesloaded');

if ( $src ) {
	$post_featimg = '<img '. vdf_imagesloaded($bool, $img_url) .' alt="' . esc_attr($post_title) . '" />';
}

$optionsStyle = get_option( 'videofly_styles' );
$socialSharing = (isset($optionsStyle['sharing_overlay']) && ($optionsStyle['sharing_overlay'] == 'Y' || $optionsStyle['sharing_overlay'] == 'N')) ? $optionsStyle['sharing_overlay'] : 'N';

// Get the date
$post_date = get_the_date();

// Metadates
$authorAvatar = get_avatar($authorId, 60);
$authorUrl = get_author_posts_url($authorId);
$authorName = get_the_author_meta('display_name', $authorId);
$permalink = esc_url(get_post_permalink($post_id));

$showImage = isset($article_options['showImage']) ? $article_options['showImage'] : 'y';
$showMeta = isset($article_options['showMeta']) ? $article_options['showMeta'] : 'y';

$vdf_post_subtitle = fields::get_value($post_id, 'post_settings', 'subtitle', true);



$article_categories = '';
$taxonomies = get_object_taxonomies($post_type);
$exclude_taxonomies = array('post_tag', 'post_format');

foreach($taxonomies as $taxonomy){
	if( isset($taxonomy) && !in_array($taxonomy, $exclude_taxonomies) ){
		$topics = wp_get_post_terms( $post_id , $taxonomy );
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

?>
<div class="col-sm-12">
	<div class="ts-featured-article">
		<article>
			<?php if( $showImage == 'y' ): ?>
				<header  class="image-holder featured-image">
					<a href="<?php echo esc_url($permalink); ?>">
						<?php echo vdf_var_sanitize($post_featimg); ?>
					</a>
					<?php #vdfHoverStyle(get_the_ID()); ?>
				</header>
			<?php endif; ?>
			<section>
			<h4 class="entry-featured-title">
				<?php esc_html_e('Featured', 'videofly'); ?>
			</h4>
				<h3 class="entry-title">
					<a href="<?php echo esc_url($permalink); ?>">
						<?php echo esc_attr($post_title); ?>
					</a>
				</h3>
				<?php if ( isset($vdf_post_subtitle) ) :?>
					<div class="entry-preamble">
						<?php echo vdf_var_sanitize($vdf_post_subtitle); ?>
					</div>
				<?php endif; ?>
				<?php if( $showMeta == 'y' ): ?>
					<ul class="entry-meta">
						<li class="entry-meta-category">
							<ul>
								<?php echo vdf_var_sanitize($article_categories); ?>
							</ul>
						</li>
						<li class="entry-meta-author">
							<span>
								<a class="author-name" href="<?php echo esc_url($authorUrl); ?>">
									<i class="icon-user"></i><?php echo vdf_var_sanitize($authorName); ?>
								</a>
							</span>
						</li>
					</ul>
				<?php endif ?>
				<div class="entry-excerpt">
					<?php vdf_excerpt('grid_excerpt', $post_id); ?>
				</div>
			</section>
		</article>
	</div>
</div>
