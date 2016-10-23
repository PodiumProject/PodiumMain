<?php get_header();
$breadcrumbs = get_option('videofly_single_post', array('breadcrumbs' => 'y'));
tsIncludeScripts(array( 'fancybox.pack', 'fancybox-thumbs'));
?>

<?php if( $breadcrumbs['breadcrumbs'] === 'y' ) : ?>
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="ts-breadcrumbs breadcrumbs-single-post container">
					<?php
						echo vdf_breadcrumbs();
					?>
				</div>
			</div>
		</div>
	</div>
<?php endif ?>

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
			echo vdf_var_sanitize($sidebar_content);
		}
	} else {
		$content_class = 'col-lg-12';
	}

$portfolio_items = get_post_meta(get_the_ID(), 'ts_portfolio', TRUE);
$portfolio_details = get_post_meta(get_the_ID(), 'ts_portfolio_details', TRUE);

?>
<div class="row">
	<div id="primary" class="<?php echo vdf_var_sanitize($content_class) ?>">
		<div id="content" role="main">
			<div class="row">
				<div class="col-lg-12">
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<section>
							<div class="row">
								<div class="col-md-5 col-lg-5">
									<h1 class="page-title"><?php the_title(); ?></h1>
									<div class="post-meta">
									<?php if (vdf_single_display_meta()): ?>
										<ul>
											<li class="date">
												<span><?php esc_html_e('Date','videofly') ?></span>
												<div><?php the_date(); ?></div>
											</li>
											<li class="client">
												<span><?php esc_html_e('Client','videofly') ?></span>
												<div><?php echo esc_attr($portfolio_details['client']); ?></div>
											</li>
											<li class="category">
												<span><?php esc_html_e('Services','videofly') ?></span>
												<div><?php echo esc_attr($portfolio_details['services']); ?></div>
											</li>
											<li class="url">
												<span><?php esc_html_e('URL','videofly') ?></span>
												<div><a href="<?php echo esc_url($portfolio_details['project_url']); ?>" target="_blank"><?php echo esc_attr($portfolio_details['project_url']); ?></a></div>
											</li>
										</ul>
									<?php endif; ?>
									</div>
									<div class="post-content">
										<?php the_content(); ?>
										<?php
										if( vdf_single_social_sharing() ):
											get_template_part('social-sharing');
										endif;
										?>
										<?php edit_post_link( esc_html__( 'Edit', 'videofly' ), '<span class="edit-link">', '</span>' ); ?>
										<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . esc_html__( 'Pages:', 'videofly' ) . '</span>', 'after' => '</div>' ) ); ?>
									</div><!-- .entry-content -->
								</div>
								<div class="col-md-7 col-lg-7">

									<div class="featured-image portfolio-featured animatedParent" data-animation="fade" data-sequence="500">
										<ul>
										<?php
											$i = 0;
											foreach ($portfolio_items as $item) {
												$i++;
												if ( $item['item_type'] == 'i' ) {

													$src = $item['item_url'];
													$img_url = vdf_resize('single', $src);

													echo '<li class="animated fadeInUp" data-id="' . $i . '"><img src="'. $img_url .'" alt="' . esc_attr($item['description']) . '" />';

													if ( vdf_lightbox_enabled() ) {
														echo '<a class="zoom-in-icon" href="' . esc_url($item['item_url']) . '" rel="fancybox[' . get_the_ID() . ']"><i class="icon-search"></i></a>';
													}

													if ( vdf_overlay_effect_is_enabled() ) {
														echo '<div class="' . vdf_overlay_effect_type() . '"></div>';
													}

													echo '</li>';

												} elseif ( $item['item_type'] === 'v' ) {
													echo '<li class="animated fadeInUp" data-id="' . $i . '"><div class="embedded_videos">' . apply_filters('the_content', $item['embed']) . '</div></li>';
												}
											}
										?>
										</ul>
									</div> <!-- portfolio content -->
								</div>
							</div>
						</section>

						<div class="post-author-box">
							<a href="<?php echo get_author_posts_url($post->post_author) ?>"><?php echo get_avatar(get_the_author_meta( 'ID' ), 74); ?></a>
							<div class="author-box-content">
								<h5 class="author-title"><?php the_author_link(); ?></h5>
								<div class="author-box-info"><?php the_author_meta('description'); ?>
								    <?php
								     if(strlen(get_the_author_meta('user_url'))!=''){?>
								        <span><?php esc_html_e('Website:','videofly'); ?> <a href="<?php the_author_meta('user_url');?>"><?php the_author_meta('user_url');?></a></span>
								    <?php } ?>
								</div>
							</div>
						</div>
						<?php vdf_get_pagination_next_previous(); ?>
					</article><!-- #post-<?php the_ID(); ?> -->
				</div>
			</div>
		</div>
	</div>
</div>


<?php

if (LayoutCompilator::sidebar_exists()) {
	if (LayoutCompilator::is_right_sidebar('single')) {
		echo vdf_var_sanitize($sidebar_content);
	}
} ?>

</div>
</section>

<?php endwhile; ?>
<?php endif; ?>
<?php get_footer(); ?>