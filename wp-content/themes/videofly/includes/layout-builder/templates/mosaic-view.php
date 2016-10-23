<?php
global $article_options;

$effect_scroll = (isset($article_options['effects-scroll']) && $article_options['effects-scroll'] !== '' && $article_options['effects-scroll'] == 'default') ? '' : 'fade-effect';
$image_height = '300px';
$layout_mosaic = (isset($article_options['layout'])) ? $article_options['layout'] : '';
$gutter = (isset($article_options['gutter']) && $article_options['gutter'] == 'n') ? 'no-gutter' : '';
$scroll = (isset($article_options['scroll']) && $article_options['scroll'] !== '') ? $article_options['scroll'] : 'n';
$img_rows = (isset($article_options['rows']) && $article_options['rows'] !== '' && (int)$article_options['rows'] !== 0) ? (int)$article_options['rows'] : '3';


$taxonomies = get_object_taxonomies($post->post_type);
$exclude_taxonomies = array('post_tag', 'post_format');
$topics = array();
$article_categories = '';

$select_by_category = (isset($article_options['behavior']) && $article_options['behavior'] == 'tabbed') ? 'tabbed' : '';
$attribute_by_category = 'style="display:none;" data-category="';

$open_video = $post->post_type == 'video' && $article_options['play'] == 'modal' ? ' data-postid="' . $post->ID . '" data-video="modal"' : '';

// Check post is sticky
$post_is_sticky = '';
$post_is_sticky_div = '';
if( is_sticky(get_the_ID()) ){
	$post_is_sticky = ' data-sticky="is-sticky" ';
	$post_is_sticky_div = '<div class="is-sticky-div">'.esc_html__('is sticky','videofly').'</div>';
}

foreach($taxonomies as $taxonomy){
	if( isset($taxonomy) && !in_array($taxonomy, $exclude_taxonomies) ){
		$topics = wp_get_post_terms($post->ID, $taxonomy);
		$i_terms = 1;

		foreach ($topics as $term) {
			if( $i_terms == count($topics) ) {
				$comma = '';
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
$attribute_by_category .= '"';

$i = $article_options['i'];
$j = $article_options['j'];
$k = $article_options['k'];

$article_date =  get_the_date();

if( $layout_mosaic === 'rectangles' ) :

	$noimg_url = get_template_directory_uri() . '/images/noimage.jpg';
	$src = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
	$class_random = '';
	if( $k === 1 ){
		// Check if gutter is on, add the paddings to the image
		if ( $article_options['gutter'] == 'y' ) {
			$image_width = $image_height * 2 + 4;
		} else{
			$image_width = $image_height * 2;
		}
		$class_random = 'col-lg-6 col-md-6 col-sm-6 col-xs-12';
		$img = aq_resize( $src, $image_width, $image_height, true, true);
	}
	if( $k === 2 ){
		$class_random = 'col-lg-3 col-md-3 col-sm-3 col-xs-12';
		$img = aq_resize( $src, $image_height, $image_height, true, true);
	}
	if( $k === 3 ){
		$class_random = 'col-lg-3 col-md-3 col-sm-3 col-xs-12';
		$img = aq_resize( $src, $image_height, $image_height, true, true);
	}
	if( $k === 4 ){
		$class_random = 'col-lg-3 col-md-3 col-sm-3 col-xs-12';
		$img = aq_resize( $src, $image_height, $image_height, true, true);
	}
	if( $k === 5 ){
		// Check if gutter is on, add the paddings to the image
		if ( $article_options['gutter'] == 'y' ) {
			$image_width = $image_height * 2 + 5;
		} else{
			$image_width = $image_height * 2;
		}
		$class_random = 'col-lg-6 col-md-6 col-sm-6 col-xs-12';
		$img = aq_resize( $src, $image_width, $image_height, true, true);
	}
	if( $k === 6 ){
		$class_random = 'col-lg-3 col-md-3 col-sm-3 col-xs-12';
		$img = aq_resize( $src, $image_height, $image_height, true, true);
	}
	if( $k === 7 ){
		$class_random = 'col-lg-3 col-md-3 col-sm-3 col-xs-12';
		$img = aq_resize( $src, $image_height, $image_height, true, true);
	}
	if( $k === 8 ){
		$class_random = 'col-lg-3 col-md-3 col-sm-3 col-xs-12';
		$img = aq_resize( $src, $image_height, $image_height, true, true);
	}
	if( $k === 9 ){
		// Check if gutter is on, add the paddings to the image
		if ( $article_options['gutter'] == 'y' ) {
			$image_width = $image_height * 2 + 5;
		} else{
			$image_width = $image_height * 2;
		}
		$class_random = 'col-lg-6 col-md-6 col-sm-6 col-xs-12';
		$img = aq_resize( $src, $image_width, $image_height, true, true);
	}

	$bool = fields::get_options_value('videofly_general', 'enable_imagesloaded');
	if ( $src ) {
		$featimage = '<img ' . vdf_imagesloaded($bool, $img) . ' alt="' . esc_attr(get_the_title()) . '" />';
	} else {
		$featimage = '<img ' .  vdf_imagesloaded($bool, $noimg_url). ' alt="' . esc_attr(get_the_title()) . '" />';
	}

 ?>

<?php if( ($scroll === 'y' && $i % 6 === 1 && $img_rows === 2) || ($scroll === 'y' && $i % 9 === 1 && $img_rows === 3) ) echo '<div class="scroll-container">'; ?>
	<div class="<?php echo vdf_var_sanitize($class_random); ?>">
		<article <?php echo vdf_var_sanitize($post_is_sticky); ?> >
			<header>
				<div class="entry-meta">
					<ul class="row">
						<li class="col-lg-7 col-md-7 col-xs-7">
							<div class="entry-meta-author">
								<a href="<?php echo get_author_posts_url($post->post_author) ?>">
									<?php echo get_avatar($post->post_author, 32); ?>
									<span> <?php echo get_the_author(); ?> </span>
								</a>
							</div>
						</li>
						<li class="col-lg-5 col-md-5 col-xs-5 text-right">
							<div class="entry-meta-date">
								<span><?php echo human_time_diff(time(), strtotime($post->post_date)) .' '. esc_html__('ago', 'videofly'); ?></span>
							</div>
						</li>
					</ul>
				</div>
				<div class="image-holder">
					<a href="<?php the_permalink(); ?>">
						<?php echo vdf_var_sanitize($featimage); ?>
						<?php
							if ( vdf_overlay_effect_is_enabled() ) {
								echo '<div class="' . vdf_overlay_effect_type() . '"></div>';
							}
						?>
					</a>
				</div>
			</header>
			<section>
 				<div class="entry-shadow">
					<ul class="entry-meta">
						<li class="entry-meta-category">
							<ul>
								<?php echo vdf_var_sanitize($article_categories); ?>
							</ul>
						</li>
						<?php touchsize_likes($post->ID, '<li class="entry-likes">', '</li>', true, false); ?>
					</ul>
					<h3<?php echo vdf_var_sanitize( $open_video ); ?>><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				</div>
			</section>
			<?php echo vdf_var_sanitize($post_is_sticky_div); ?>
		</article>
	</div>

<?php if( ($k % 9 === 0 && $img_rows === 3 && $scroll === 'y') || ($k % 6 === 0 && $img_rows === 2 && $scroll === 'y') || ($i === $j && $k % 6 !== 0 && $img_rows === 2) || ($i === $j && $k % 9 !== 0 && $img_rows === 3) ){ echo '</div>'; } ?>
<?php endif; ?>

<?php if( $layout_mosaic === 'square' ) :

	$noimg_url = get_template_directory_uri() . '/images/noimage.jpg';
	$src = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );


	$class_random = '';

	if(($i % 2) == 0 && $scroll == 'n'){
		if( $k == 1 ){
			$class_random = 'col-lg-3 col-md-3 col-sm-3 col-xs-12';
			$img = aq_resize( $src, $image_height, $image_height, true, true);
		}
		if( $k == 2 ){
			$class_random = 'col-lg-3 col-md-3 col-sm-3 col-xs-12';
			$img = aq_resize( $src, $image_height, $image_height, true, true);
		}
		if( $k == 3 ){
			$class_random = 'col-lg-6 col-md-6 col-sm-6 col-xs-12';
		}
		if( $k == 3 ){
			$class_random = 'col-lg-6 col-md-6 col-sm-6 col-xs-12 pull-right';
			$img = aq_resize( $src, $image_height * 2, $image_height * 2, true, true);
		}
		if( $k == 4 ){
			$class_random = 'col-lg-3 col-md-3 col-sm-3 col-xs-12';
			$img = aq_resize( $src, $image_height, $image_height, true, true);
		}
		if( $k == 5 ){
			$class_random = 'col-lg-3 col-md-3 col-sm-3 col-xs-12';
			$img = aq_resize( $src, $image_height, $image_height, true, true);
		}
	}else{
		if( $k == 1 ){
			$class_random = 'col-lg-6 col-md-6 col-sm-6 col-xs-12';
			$img = aq_resize( $src, $image_height * 2, $image_height * 2, true, true);
		}
		if( $k == 2 ){
			$class_random = 'col-lg-3 col-md-3 col-sm-3 col-xs-12';
			$img = aq_resize( $src, $image_height, $image_height, true, true);
		}
		if( $k == 3 ){
			$class_random = 'col-lg-3 col-md-3 col-sm-3 col-xs-12';
			$img = aq_resize( $src, $image_height, $image_height, true, true);
		}
		if( $k == 4 ){
			$class_random = 'col-lg-3 col-md-3 col-sm-3 col-xs-12';
			$img = aq_resize( $src, $image_height, $image_height, true, true);
		}
		if( $k == 5 ){
			$class_random = 'col-lg-3 col-md-3 col-sm-3 col-xs-12';
			$img = aq_resize( $src, $image_height, $image_height, true, true);
		}
	}

	$bool = fields::get_options_value('videofly_general', 'enable_imagesloaded');
	if ( $src ) {
		$featimage = '<img '. vdf_imagesloaded($bool, $img) .' alt="' . esc_attr(get_the_title()) . '" />';
	} else {
		$featimage = '<img '.  vdf_imagesloaded($bool, $noimg_url) .' alt="' . esc_attr(get_the_title()) . '" />';
	}
 ?>
<?php if( $k == 1  && $scroll == 'y' ) echo '<div class="scroll-container">'; ?>
	<div class="<?php echo vdf_var_sanitize($class_random); ?>">
		<article <?php echo vdf_var_sanitize($post_is_sticky); ?> >
			<header>
				<div class="entry-meta">
					<ul class="row">
						<li class="col-lg-6 col-md-6 col-xs-6">
							<div class="entry-meta-author">
								<a class="author-avatar" href="<?php the_permalink(); ?>">
									<?php echo get_avatar($post->post_author, 32); ?>
									<span> <?php echo get_the_author(); ?> </span>
								</a>
							</div>
						</li>
						<li class="col-lg-6 col-md-6 col-xs-6 text-right">
							<div class="entry-meta-date">
								<span><?php echo human_time_diff(time(), strtotime($post->post_date)) .' '. esc_html__('ago', 'videofly'); ?></span>
							</div>
						</li>
					</ul>
				</div>
				<div class="image-holder">
					<a href="<?php the_permalink(); ?>">
						<?php echo vdf_var_sanitize($featimage); ?>
						<?php
							if ( vdf_overlay_effect_is_enabled() ) {
								echo '<div class="' . vdf_overlay_effect_type() . '"></div>';
							}
						?>
					</a>
				</div>
			</header>
			<section>
 				<div class="entry-shadow">
					<ul class="entry-meta">
						<li class="entry-meta-category">
							<ul>
								<?php echo vdf_var_sanitize($article_categories); ?>
							</ul>
						</li>
						<?php touchsize_likes($post->ID, '<li class="entry-likes">', '</li>', true, false); ?>
					</ul>
					<h3<?php echo vdf_var_sanitize( $open_video ); ?>><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				</div>
			</section>
			<?php echo vdf_var_sanitize($post_is_sticky_div); ?>
		</article>
	</div>
<?php
	if( ($k % 5 === 0 && $scroll === 'y') || ($k % 5 !== 0 && $scroll === 'y' && $i === $j) ){ echo '</div>'; }
	endif;
?>
