<?php

/* Thumbnail view template below */

// Get the options

global $article_options, $filter_class, $taxonomy_name;

// Get the featured image
$src = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );

$post_per_page = (isset($article_options['elements-per-row']) && (int)$article_options['elements-per-row'] !== 0) ? (int)$article_options['elements-per-row'] : '2';
$scroll = (isset($article_options['behavior']) && $article_options['behavior'] === 'scroll') ? $article_options['behavior'] : '';

$meta = (isset($article_options['meta-thumbnail']) && ($article_options['meta-thumbnail'] === 'y' || $article_options['meta-thumbnail'] === 'n')) ? $article_options['meta-thumbnail'] : 'n';

$edit_post_simple_user = (isset($article_options['edit']) && $article_options['edit'] === true) ? true : NULL;

$titlePosition = isset($article_options['display-title']) ? $article_options['display-title'] : 'over-image';
$titlePosition = has_post_thumbnail() ? $titlePosition : 'below-image';

$post_count = isset($article_options['j']) ? $article_options['j'] : '';
$i = isset($article_options['i']) ? $article_options['i'] : '';

$social_sharing = get_option('videofly_styles', array('sharing_overlay' => 'N'));

$masonry = isset($article_options['behavior']) && $article_options['behavior'] == 'masonry' ? true : false;

$style_hover = get_option( 'videofly_styles' );
$hover_effect = (isset(	$style_hover['style_hover'] ) && ($style_hover['style_hover'] == 'style1' || $style_hover['style_hover'] == 'style2')) ? $style_hover['style_hover'] : 'style1';

// Get the categories of the article
$taxonomies = get_object_taxonomies($post->post_type);
$exclude_taxonomies = array('post_tag', 'post_format');
$topics = array();
$article_categories = '';

$select_by_category = (isset($article_options['behavior']) && $article_options['behavior'] == 'tabbed') ? 'tabbed' : '';
$attribute_by_category = 'style="display:none;" data-category="';

$article_categories = '<ul class="entry-meta-category">';
foreach( $taxonomies as $taxonomy ){

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

			if( $select_by_category == 'tabbed' ){
				$attribute_by_category .= $term->term_id . $dividing_category;
			}

			$i_terms++;
		}
	}
}
$article_categories .= '</ul>';
$attribute_by_category .= '"';

// Get article columns by elements per row
$columns_class = LayoutCompilator::get_column_class($article_options['elements-per-row']);

if( @$filter_class === 'yes' ){
	$filter_categs = array();
	foreach (get_the_terms(get_the_ID(), $taxonomy_name) as $categ) {
		$filter_categs[] = 'ts-category-' . $categ->term_id;
	}
} else{
	$filter_categs = array();
}

$posts_inside = '';
if( ( $i % $post_per_page ) === 1 && $scroll === 'scroll' ){
	$posts_inside = ' posts-inside-'.$post_per_page . ' posts-total-' .$post_per_page;
}
if( ($i % $post_per_page) === 1 && ( $post_count - $i ) < $post_per_page && ( $post_count % $post_per_page ) !== 0 ){
	$class = $post_count % $post_per_page;
	$posts_inside = ' posts-inside-'.$class . ' posts-total-' .$post_per_page;
}

// Check post is sticky
$post_is_sticky = '';
$post_is_sticky_div = '';
if( is_sticky(get_the_ID()) ){
	$post_is_sticky = ' data-sticky="is-sticky" ';
	$post_is_sticky_div = '<div class="is-sticky-div">'. esc_html__('is sticky','videofly') .'</div>';
}

$open_video = $post->post_type == 'video' && $article_options['play'] == 'modal' ? ' data-postid="' . $post->ID . '" data-video="modal"' : '';

?>
<?php if( ( $i % $post_per_page ) === 1  && $scroll == 'scroll' ) echo '<div class="scroll-container'. $posts_inside .'">'; ?>
<div <?php if( $select_by_category == 'tabbed' ) echo vdf_var_sanitize($attribute_by_category); ?> class="item <?php if( $select_by_category == 'tabbed' ) echo 'ts-tabbed-category '; echo vdf_var_sanitize($columns_class . ' ' . esc_attr(implode(" ", $filter_categs))) . ($titlePosition == 'below-image' ? ' ts-thumbnails-bellow' : ' ts-thumbnails-over'); ?>">
	<article <?php echo vdf_var_sanitize($post_is_sticky) . post_class(); ?>>
		<header>
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="image-holder">
					<a href="<?php the_permalink(); ?>">
						<?php
							$genderalOptions = get_option('videofly_general');
							$src = wp_get_attachment_url( get_post_thumbnail_id() );

							$imgUrl = vdf_resize( 'thumbnails', $src, $masonry );

							$bool = isset( $genderalOptions['enable_imagesloaded'] ) && $genderalOptions['enable_imagesloaded'] == 'Y' ? true : false;

							echo '<img ' . vdf_imagesloaded( $bool, $imgUrl ) . ' alt="' . esc_attr( get_the_title() ) . '"/>';

							if ( vdf_overlay_effect_is_enabled() ) echo '<div class="'. vdf_overlay_effect_type() .'"></div>';
						?>
					</a>
					<?php if ( $titlePosition == 'below-image' ) vdfHoverStyle(get_the_ID(), $post->post_type); ?>
				</div>
			<?php endif; ?>
		</header>
		<section>
			<?php if( $meta === 'y' ): ?>
				<?php if( $titlePosition == 'over-image' ): ?>
					<?php echo vdf_var_sanitize($article_categories); ?>
				<?php endif;?>
			<?php endif; ?>
			<h3 class="entry-title" <?php echo vdf_var_sanitize( $open_video ); ?>>
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h3>
			<?php if ( $meta === 'y' ) : ?>
				<?php if( $titlePosition == 'below-image' ): ?>
					<?php echo vdf_var_sanitize($article_categories); ?>
				<?php endif;?>
				<span class="entry-meta-author">
					<a href="<?php echo get_author_posts_url($post->post_author); ?>"><i class="icon-user"></i><?php the_author(); ?></a>
				</span>
			<?php endif; ?>
		</section>
		<?php
		if( is_user_logged_in() && isset($article_options['edit']) ):

			global $wpdb;
			$addPostId = $wpdb->get_results($wpdb->prepare("SELECT post_id, meta_value FROM ". esc_sql($wpdb->postmeta) ." WHERE meta_value = %s LIMIT 1", 'user-add-post.php'), ARRAY_A);

			$pageAddPost = isset($addPostId[0]['post_id']) ? get_page_link($addPostId[0]['post_id']) : esc_url( home_url( '/' ) );

			$userLogged = wp_get_current_user();
			$userAuthor = $post->post_author;

			if( $userLogged->ID == $userAuthor ) : ?>
				<a class="edit-post-link" href="<?php echo esc_url($pageAddPost); ?>?id=<?php echo the_ID(); ?>">
					<?php esc_html_e('Edit', 'videofly'); ?>
				</a>
			<?php endif; ?>
		<?php endif; ?>
		<?php echo vdf_var_sanitize($post_is_sticky_div); ?>
	</article>
</div>
<?php
if( ( $i % $post_per_page ) == 0  && $scroll == 'scroll' || ( $i % $post_per_page ) !== 0  && $scroll == 'scroll' && $i === $post_count) echo '</div>';

if( isset($article_options['i']) ) $article_options['i']++;

?>
