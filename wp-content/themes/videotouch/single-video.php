<?php

get_header();

global $wp_query;

if ( have_posts() ) {

	$single_sidebar = get_option('videotouch_single_post', array('video_sidebar' => 'n'));

	if (LayoutCompilator::sidebar_exists()) {

		$options = LayoutCompilator::get_sidebar_options();

		extract(LayoutCompilator::build_sidebar($options));

	} else {
		$content_class = 'col-lg-12';
	}
	while ( have_posts() ) : the_post();

	// Get the categories of the article
	if ( get_post_type( get_the_ID() ) == 'portfolio' ) {
		$category_tax = 'portfolio_categories';
	}elseif ( get_post_type( get_the_ID() ) == 'video' ) {
		$category_tax = 'videos_categories';
	} else{
		$category_tax = 'category';
	}

	// Get the date
	if (ts_human_type_date_format()) {
		$article_date = human_time_diff(strtotime(get_the_time('Y-m-d H:i:s'))).' '.__('ago', 'touchsize');
	} else {
		$article_date =  get_the_date();
	}

	$topics = wp_get_post_terms(get_the_ID() , $category_tax);


	$article_categories = '';
	if( isset($topics[0]) && !is_wp_error($topics) ){
		foreach($topics as $term) {
			$article_categories .= '<li>' .
										'<a href="' . esc_attr(get_term_link($term->term_id, $category_tax)) . '" title="' . __('View all articles from: ', 'touchsize') . $term->name . '" ' . '>'
											. $term->name .
										'</a>
									</li>';
		}
	}

	$single_options = get_option('videotouch_single_post', array('resize_video' => 'big', 'show_more' => 'y'));
	if ( isset($_COOKIE['ts_single_video_resize_type']) ) {
		// Rewrite from cookie if exists
		$single_options['resize_video'] = $_COOKIE['ts_single_video_resize_type'];
	}
	$show_more = (isset($single_options) && isset($single_options['show_more']) && ($single_options['show_more'] === 'y' || $single_options['show_more'] === 'n')) ? $single_options['show_more'] : 'y';

?>

<!-- Ad area 1 -->
<?php if( fields::get_options_value('videotouch_theme_advertising','ad_area_1') != '' ): ?>
<div class="container text-center ts-advertising-container">
	<?php echo fields::get_options_value('videotouch_theme_advertising','ad_area_1'); ?>
</div>
<?php endif; ?>
<!-- // End of Ad Area 1 -->

<?php

	if ( ! isset( $single_sidebar['log_video'] ) || ( isset( $single_sidebar['log_video'] ) && $single_sidebar['log_video'] == 'Y' ) || ( isset( $single_sidebar['log_video'] ) && $single_sidebar['log_video'] == 'N' && is_user_logged_in() ) ) {

		if( $single_sidebar['video_sidebar'] == 'y' ) {

			get_template_part('single-video-sidebar');

		} else {

			get_template_part('single-video-full');

		}
	}
?>

<section id="main">

	<div class="container singular-container">
		<?php
			if (LayoutCompilator::sidebar_exists()) {
				$options = LayoutCompilator::get_sidebar_options();

				extract(LayoutCompilator::build_sidebar($options));

				if (LayoutCompilator::is_left_sidebar()) {
					echo $sidebar_content;
				}
			} else {
				$content_class = 'col-lg-12';
			}
		?>
		<div id="primary" class="<?php echo $content_class; ?>">
			<?php if ( ! isset( $single_sidebar['log_video'] ) || ( isset( $single_sidebar['log_video'] ) && $single_sidebar['log_video'] == 'Y' ) || ( isset( $single_sidebar['log_video'] ) && $single_sidebar['log_video'] == 'N' && is_user_logged_in() ) ) : ?>
				<div id="content" role="main">
					<article <?php post_class(''); ?>>
						<header class="post-header">
							<div class="row">
								<?php
									$breadcrumbs = get_option('videotouch_single_post', array('breadcrumbs' => 'y'));
									if( $breadcrumbs['breadcrumbs'] === 'y' ):
								?>
									<div class="ts-breadcrumbs breadcrumbs-single-video">
										<div class="container">
											<div class="row">
												<div class="col-md-12 col-lg-12">
													<?php  echo ts_breadcrumbs(); ?>
												</div>
											</div>
										</div>
									</div>
								<?php endif; ?>
								<div class="col-lg-8 col-md-8 col-sm-12">
									<h1 class="post-title video-title"><?php esc_attr(the_title()); ?></h1>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-12 text-right">
									<?php if( fields::get_options_value('videotouch_single_post','social_sharing') != 'N' ): ?>
										<div class="post-share-box-circle action-effect">
											<label for="share-menu-circle"><div class="icon-share"></div></label>
										    <ul class="share-options share-menu">
										        <li class="share-menu-item">
										            <a class="icon-facebook" target="_blank" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo get_permalink(get_the_ID()); ?>"></a>
										        </li>
										        <li class="share-menu-item">
										            <a class="icon-twitter" target="_blank" href="http://twitter.com/home?status=<?php echo urlencode(get_the_title()); ?>+<?php echo get_permalink(get_the_ID()); ?>"></a>
										        </li>
										        <li class="share-menu-item">
										            <a class="icon-gplus" target="_blank" href="https://plus.google.com/share?url=<?php echo get_permalink(get_the_ID()); ?>"></a>
										        </li>
										        <li class="share-menu-item">
										            <a class="icon-linkedin" target="_blank" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo get_permalink(get_the_ID()); ?>&title=<?php echo urlencode(get_the_title()); ?>"></a>
										        </li>
										        <li class="share-menu-item">
										            <a class="icon-tumblr" target="_blank" href="http://www.tumblr.com/share/link?url=<?php echo get_permalink(get_the_ID()); ?>&name=<?php echo esc_attr($post->post_title); ?>&description=<?php echo $post->post_excerpt; ?>"></a>
										        </li>
										        <li class="share-menu-item">
										            <a class="icon-pinterest" target="_blank" href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&amp;media=<?php if(function_exists('the_post_thumbnail')) echo wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())); ?>&amp;description=<?php echo urlencode(esc_attr(get_the_title())); ?>" ></a>
										        </li>
										    </ul>
										</div>
									<?php endif; ?>
									<div class="post-meta">
										<?php touchsize_likes($post->ID, '<div class="post-meta-likes">', '</div>'); ?>
										<div class="post-meta-views video-post-likes">
											<span class="views-count"><?php ts_get_views($post->ID); ?></span>
											<small><?php _e('views', 'touchsize'); ?></small>
										</div>
									</div>
								</div>
								<?php if ( ts_single_display_meta() ): ?>
									<div class="col-lg-12 col-md-12 post-author-block">
										<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12">
												<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" class="author-avatar"><?php echo ts_display_gravatar(50); ?></a>
												<ul>
													<li class="author-name"><?php _e('by','touchsize') ?> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php echo get_the_author(); ?></a></li>
													<li class="author-published"><?php _e('Published','touchsize') ?> <span><?php echo $article_date; ?></span></li>
												</ul>
											</div>
										</div>
									</div>
								<?php endif; ?>
							</div>
						</header>

						<div class="post-content ">
							<?php the_content(); ?>
							<?php if( !empty($article_categories) ) : ?>
								<div>
									<i class="icon-category"></i>
									<ul class="single-video-categories">
										<?php echo $article_categories; ?>
									</ul>
								</div>
							<?php endif; ?>
							<div>
								<?php if( has_tag() ) : ?>
									<i class="icon-tags"></i>
									<?php echo get_the_tag_list('<ul class="single-video-tags"><li>','</li>, <li>','</li></ul>'); ?>
								<?php endif; ?>
							</div>
							<?php if($show_more === 'y' ) : ?>
								<div class="content-cortina"></div>
							<?php endif; ?>
						</div>

						<?php if( $show_more === 'y' ) : ?>
							<div class="video-post-open">
								<i class="icon-down"></i>
								<span><?php _e('details','touchsize'); ?></span>
							</div>
						<?php endif; ?>
					</article>
				</div>
			<?php else : ?>
				<h1 class="post-title video-title"><?php esc_attr(the_title()); ?></h1>
			<?php endif;

			endwhile;
			?>
		</div>
		<?php
			if (LayoutCompilator::sidebar_exists()) {
				if (LayoutCompilator::is_right_sidebar('single')) {
					echo $sidebar_content;
				}
			}
		?>
	</div>
	<?php if ( fields::get_options_value('videotouch_single_post', 'related_posts') === 'Y' && $single_sidebar['video_sidebar'] === 'n' ): ?>
		<div class="ts-related-video-container row">
			<div class="container">
				<div class="ts-tab-container">
					<ul class="nav nav-tabs" role="tablist">
						<li class="active">
							<a role="tab" data-toggle="tab" href="#related"><?php _e('Related posts','touchsize') ?></a>
						</li>
						<li>
							<a role="tab" data-toggle="tab" href="#related-author"><?php _e('By the author','touchsize') ?></a>
						</li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="related">
							<div class="row">
								<?php echo LayoutCompilator::get_single_related_posts(get_the_ID()); ?>
							</div>
						</div>
						<div class="tab-pane" id="related-author">
							<div class="row">
								<?php ts_get_related_posts_author($post->post_author, $post->ID); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<!-- Ad area 2 -->
	<?php if( fields::get_options_value('videotouch_theme_advertising','ad_area_2') != '' ): ?>
	<div class="container text-center ts-advertising-container">
		<?php echo fields::get_options_value('videotouch_theme_advertising','ad_area_2'); ?>
	</div>
	<?php endif; ?>
	<!-- // End of Ad Area 2 -->

	<div class="container single-video-comments">
		<div class="row content-block">
			<div class="col-lg-12">
				<?php comments_template( '', true ); ?>
			</div>
		</div>
	</div>
</section>
<?php
	ts_get_pagination_next_previous();
?>
	<?php } ?>
<?php get_footer(); ?>