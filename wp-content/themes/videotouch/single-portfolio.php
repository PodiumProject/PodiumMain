<?php get_header(); ?>
<?php if( get_post_type() !== 'video' ): ?>
<section id="main">
<div class="container singular-container">
<?php endif ?>
<?php

global $wp_query;

if ( have_posts() ) :
	while ( have_posts() ) : the_post();
	if (LayoutCompilator::sidebar_exists()) {
		
		$options = LayoutCompilator::get_sidebar_options();

		extract(LayoutCompilator::build_sidebar($options));

		if (LayoutCompilator::is_left_sidebar()) {
			echo $sidebar_content;
		}
	} else {
		$content_class = 'col-lg-12';
	}

$portfolio_items = get_post_meta(get_the_ID(), 'ts_portfolio', TRUE);
$portfolio_details = get_post_meta(get_the_ID(), 'ts_portfolio_details', TRUE);

$breadcrumbs = get_option('videotouch_single_post', array('breadcrumbs' => 'y')); 
if( $breadcrumbs['breadcrumbs'] === 'y' ) echo ts_breadcrumbs(); 
?>
<div id="primary" class="<?php echo $content_class ?>">
	<div id="content" role="main">		
		<div class="row">
			<div class="col-lg-12">
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">
						<div class="row">
							<div class="col-lg-12">
								<h2 class="page-title"><?php esc_attr(the_title()); ?></h2>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<div class="flexslider featured-image portfolio-featured" data-animation="fade">
									<ul class="slides">
									<?php
										foreach ($portfolio_items as $item) {
											if ( $item['item_type'] === 'i' ) {
												
												$src = $item['item_url'];
												$img_url = ts_resize('single', $src);

												echo '<li><img src="'. $img_url .'" alt="' . esc_attr($item['description']) . '" />';

												if ( ts_lightbox_enabled() ) {
													echo '<a class="zoom-in-icon" href="' . esc_url($item['item_url']) . '" data-rel="prettyPhoto[' . get_the_ID() . ']"><i class="icon-search"></i></a>';
												}

												if ( ts_overlay_effect_is_enabled() ) {
													echo '<div class="' . ts_overlay_effect_type() . '"></div>';
												}

												echo '</li>';

											} elseif ( $item['item_type'] === 'v' ) {
												echo '<div class="embedded_videos">' . apply_filters('the_content', $item['embed']) . '</div>';
											}
										}
									?>
									</ul>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<div class="post-meta">
								<?php if (ts_single_display_meta()): ?>
									<ul>
										<li class="date">
											<span><?php _e('Date','touchsize') ?></span>
											<?php if (ts_human_type_date_format()): ?>
												<div><?php echo human_time_diff(strtotime(get_the_time('Y-m-d H:i:s'))) . ' ' . __('ago', 'touchsize');?></div>
											<?php else: ?>
											<div><?php the_date(); ?></div>
											<?php endif ?>
											<i class="icon-time"></i>
										</li>
										<li class="client">
											<span><?php _e('Client','touchsize') ?></span>
											<div><?php echo $portfolio_details['client']; ?></div>
											<i class="icon-user"></i>
										</li>
										<li class="category">
											<span><?php _e('Services','touchsize') ?></span>
											<div><?php echo $portfolio_details['services']; ?></div>
											<i class="icon-category"></i>
										</li>
										<li class="url">
											<span><?php _e('URL','touchsize') ?></span>
											<div><a href="<?php echo $portfolio_details['project_url']; ?>" target="_blank"><?php echo $portfolio_details['project_url']; ?></a></div>
											<i class="icon-link"></i>
										</li>
									</ul>
								<?php endif; ?>
								</div>
							</div>
						</div>
					</header><!-- .entry-header -->

					<div class="post-content">
						<?php the_content(); ?>
						<?php edit_post_link( __( 'Edit', 'touchsize' ), '<span class="edit-link">', '</span>' ); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'touchsize' ) . '</span>', 'after' => '</div>' ) ); ?>
					</div><!-- .entry-content -->

					<footer class="post-author-box">
						<div class="row">
							<div class="col-lg-12">
								<h6 class="post-details-title"><?php _e('Share','touchsize'); ?></h6>
								<?php 
								if(ts_single_social_sharing()):
									get_template_part('social-sharing');
								endif;
								?>
							</div>
						</div>

						<?php if ( get_the_author_meta( 'description' ) && ( ! function_exists( 'is_multi_author' ) || is_multi_author() ) ) : // If a user has filled out their description and this is a multi-author blog, show a bio on their entries ?>
						<div id="author-info">
							<div id="author-avatar">
								<?php echo ts_display_gravatar(50); ?>
							</div><!-- #author-avatar -->
							<div id="author-description">
								<h2><?php printf( __( 'About %s', 'touchsize' ), get_the_author() ); ?></h2>
								<?php the_author_meta( 'description' ); ?>
								<div id="author-link">
									<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
										<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'touchsize' ), get_the_author() ); ?>
									</a>
								</div><!-- #author-link	-->
							</div><!-- #author-description -->
						</div><!-- #author-info -->
						<?php endif; ?>
					</footer><!-- .entry-meta -->
				</article><!-- #post-<?php the_ID(); ?> -->
			</div>
		</div>
	</div>
</div>


<?php

if (LayoutCompilator::sidebar_exists()) {
	if (LayoutCompilator::is_right_sidebar('single')) {
		echo $sidebar_content;
	}
} ?>

</div>
</section>

<?php endwhile; ?>
<?php endif; ?>
<?php get_footer(); ?>