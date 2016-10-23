<?php

/* Grid view template below */
###########

// Get the options

global $article_options;

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
$post_per_page = isset($article_options['elements-per-row']) && (int)$article_options['elements-per-row'] !== 0 ? (int)$article_options['elements-per-row'] : '2';
$meta = isset($article_options['show-meta']) ? $article_options['show-meta'] : 'n';

$related = (isset($article_options['related-posts']) && ($article_options['related-posts'] === 'y' || $article_options['related-posts'] === 'n')) ? $article_options['related-posts'] : 'n';

$i = $article_options['i'];
$posts_inside = '';

$vdf_image_is_masonry = false;
if ( isset($article_options['behavior']) && $article_options['behavior'] === 'masonry' ) {
	$vdf_image_is_masonry = true;
}

$show_image = (isset($article_options['show-image']) && ($article_options['show-image'] == 'y' || $article_options['show-image'] == 'n')) ? $article_options['show-image'] : 'y';

if( $show_image == 'y' && has_post_thumbnail(get_the_ID()) ){
	$src = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
	$img_url = vdf_resize('grid', $src, $vdf_image_is_masonry);

	$bool = fields::get_options_value('videofly_general', 'enable_imagesloaded');

	if ( $src ) {
		$featimage = '<img ' . vdf_imagesloaded($bool, $img_url) . ' alt="' . esc_attr(get_the_title()) . '" />';
	}
}

// Get related posts
$related_posts = ($related === 'y') ? LayoutCompilator::get_related_posts(get_the_ID(), get_the_tags()) : '';

// Get article columns by elements per row
$columns_class = LayoutCompilator::get_column_class($article_options['elements-per-row']);

// Add article specific classes

$article_classes = ($meta === 'y') ? ' article-meta-shown ' : ' article-meta-hidden ';

if ( isset($article_options['display-title']) ) {
	$article_classes .= ' ' . $article_options['display-title'] . ' ';
}
if ( isset($article_options['behavior']) && $article_options['behavior'] == 'masonry' ) {
	$article_classes .= ' masonry-element ';
}
$posts_inside = '';

if( ( $i % $post_per_page ) == 1 && $scroll === 'scroll' ){
	$posts_inside = ' posts-inside-' . $post_per_page . ' posts-total-' . $post_per_page;
}
if( ($i % $post_per_page) == 1 && ( $post_count - $i ) < $post_per_page && ( $post_count % $post_per_page ) !== 0 ){
	$class = $post_count % $post_per_page;
	$posts_inside = ' posts-inside-' . $class . ' posts-total-' . $post_per_page;
}

// Get post rating
$rating_final = vdf_get_rating($post->ID);

// Check post is sticky
$post_is_sticky = '';
$post_is_sticky_div = '';
if( is_sticky(get_the_ID()) ){
	$post_is_sticky = ' data-sticky="is-sticky" ';
	$post_is_sticky_div = '<div class="is-sticky-div">'. esc_html__('is sticky','videofly') .'</div>';
}

$select_by_category = (isset($article_options['behavior']) && $article_options['behavior'] == 'tabbed') ? 'tabbed' : '';

$attribute_by_category = 'style="display:none;" data-category="';

$taxonomies = get_object_taxonomies($post->post_type);
$exclude_taxonomies = array('post_tag', 'post_format');

$article_categories = '';

foreach ( $taxonomies as $taxonomy ) {
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

			if( $article_options['behavior'] == 'tabbed' ){
				$attribute_by_category .= $term->term_id . $dividing_category;
			}

			$i_terms++;
		}
	}
}
$attribute_by_category .= '"';

$open_video = $post->post_type == 'video' && $article_options['play'] == 'modal' ? ' data-postid="' . $post->ID . '" data-video="modal"' : '';

?>
<?php if( ( $i % $post_per_page ) === 1  && $scroll === 'scroll' ) echo '<div class="scroll-container' . $posts_inside . '">'; ?>
<?php if($post_per_page == 1  && $scroll === 'scroll' ) echo '<div class="scroll-container' . $posts_inside .'">'; ?>
<div <?php if( $select_by_category == 'tabbed' ) echo vdf_var_sanitize($attribute_by_category); ?> class="<?php echo vdf_var_sanitize($columns_class); if( $select_by_category == 'tabbed' ) echo ' ts-tabbed-category' ?> item">
	<article <?php echo vdf_var_sanitize($post_is_sticky); ?> <?php echo post_class( $article_classes ); ?>>
		<header>
			<?php if ( $article_options['display-title'] == 'title-above-image' ) : ?>
				<h3 class="entry-title"<?php echo vdf_var_sanitize( $open_video ); ?>><a href="<?php the_permalink(); ?>" title="<?php the_title() ?>"><?php the_title(); ?></a></h3>
			<?php endif; ?>
			<?php if( isset($featimage) ) : ?>
				<div class="image-holder">
					<?php if( isset($rating_final) ) : ?>
						<div class="post-rating-circular">
							<div class="circular-content">
								<div class="counted-score"><?php echo vdf_var_sanitize($rating_final, 'esc_attr'); ?>/10</div>
							</div>
						</div>
					<?php endif;

						echo vdf_var_sanitize($featimage);

						if ( vdf_overlay_effect_is_enabled() ) echo '<div class="' . vdf_overlay_effect_type() . '"></div>';

						vdfHoverStyle($post->ID, $post->post_type);
					?>
					<?php echo vdf_var_sanitize($post_is_sticky_div); ?>
				</div>
			<?php endif; ?>
		</header>
		<section>
			<?php if ( $article_options['show-meta'] === 'y' ) : ?>
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
			<?php if ( $article_options['display-title'] == 'title-below-image' ) : ?>
				<h3 class="entry-title"<?php echo vdf_var_sanitize( $open_video ); ?>><a href="<?php the_permalink(); ?>" title="<?php the_title() ?>"><?php the_title(); ?></a></h3>
			<?php endif; ?>
			<div class="entry-excerpt">
				<?php vdf_excerpt('grid_excerpt', $post->ID, 'show-excerpt'); ?>
			</div>
		</section>
		<footer>
			<ul class="entry-secondary-meta">
				<li class="ts-read-btn">
					<a href="<?php the_permalink(); ?>"<?php echo vdf_var_sanitize( $open_video ); ?>>
						<?php echo ($post->post_type == 'video' ? '<i class="icon-play"></i> '. esc_html__('PLAY', 'videofly') : esc_html__('Read more', 'videofly')); ?>
					</a>
				</li>
				<?php if ( $article_options['show-meta'] === 'y' ) : ?>
					<?php vdf_get_views($post->ID, '<li class="entry-views"><i class="icon-views"></i> ', '</li>'); ?>
					<?php touchsize_likes($post->ID, '<li class="entry-likes">', '</li>'); ?>
				<?php endif; ?>
			</ul>
		</footer>
		<?php
		if( is_user_logged_in() && isset($article_options['edit']) ):

			global $wpdb;
			$addPostId = $wpdb->get_results($wpdb->prepare("SELECT post_id, meta_value FROM ". esc_sql($wpdb->postmeta) ." WHERE meta_value = %s LIMIT 1", 'user-add-post.php'), ARRAY_A);

			$pageAddPost = isset($addPostId[0]['post_id']) ? get_page_link($addPostId[0]['post_id']) : esc_url( home_url( '/' ) );

			$userLogged = wp_get_current_user();
			$userAuthor = $post->post_author;

			if( is_object($userLogged) && $userLogged->ID == $userAuthor ) : ?>
				<a class="edit-post-link" href="<?php echo esc_url($pageAddPost); ?>?id=<?php echo the_ID(); ?>">
					<?php esc_html_e('Edit', 'videofly'); ?>
				</a>
			<?php endif; ?>
		<?php endif; ?>

	</article>
	<?php if( $related === 'y' ) echo vdf_var_sanitize($related_posts); ?>
</div>
<?php
if( ( $i % $post_per_page ) == 0  && $scroll == 'scroll' || ( $i % $post_per_page ) !== 0  && $scroll == 'scroll' && $i === $post_count) echo '</div>';

$article_options['i']++;
?>