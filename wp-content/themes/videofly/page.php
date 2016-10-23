<?php
get_header();
global $wp_query;

$breadcrumbs = get_post_meta($post->ID, 'ts_header_and_footer', true);

if( isset($breadcrumbs['breadcrumbs']) && $breadcrumbs['breadcrumbs'] === 0 && !is_front_page() ) :
?>	
<div class="ts-breadcrumbs breadcrumbs-single-post container">
	<div class="row">
		<div class="col-lg-12">
			<?php
				echo vdf_breadcrumbs();
			?>
		</div>
	</div>
</div>	
<?php endif; ?>
<?php
if ( have_posts() ) :
	if ( LayoutCompilator::builder_is_enabled() ):
		LayoutCompilator::run();
	else:
		$page_options = get_option('videofly_page');
		
		if ( LayoutCompilator::sidebar_exists() ) {
			
			$options = LayoutCompilator::get_sidebar_options();
			extract(LayoutCompilator::build_sidebar($options));

		} else {
			$content_class = 'col-lg-12';
		}
?>

<section id="main" class="default-page">
<div class="container no-pad">
	<?php
		if ( LayoutCompilator::is_left_sidebar() && !LayoutCompilator::builder_is_enabled() ) {
			echo vdf_var_sanitize($sidebar_content);
		}
	?>
	<div id="" class="<?php echo vdf_var_sanitize($content_class) ?>">
		<div id="content" role="main">	
			<div class="row">
				<div class="col-lg-12">
					<?php while ( have_posts() ) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<header class="entry-header">
							<div class="row">
								<div class="col-lg-12">
									<?php if ( !fields::logic($post->ID, 'page_settings', 'hide_title') ): ?>
										<h1 class="page-title"><?php the_title(); ?></h1>
									<?php endif; ?>
									<?php if ( vdf_single_display_meta() && !fields::logic($post->ID, 'page_settings', 'hide_meta') ): ?>
										<ul class="post-meta">
											<li><?php esc_html_e( 'by', 'videofly' ); ?> <a href="#"><?php the_author(); ?></a></li>
											<li>
												<?php the_date(); ?>
											</li>
											<li>
											<?php
												if (fields::get_options_value('videofly_general', 'comment_system') === 'facebook' ) {
												    echo vdf_get_comment_count(get_the_ID()) . esc_html__(' comments', 'videofly');
												}else{
													comments_number( '0 ' . esc_html__('comments', 'videofly'), '1 ' . esc_html__('comment', 'videofly'), '% ' . esc_html__('comments', 'videofly') );
												} 
											?>
											</li>
											<?php if ( !fields::logic($post->ID, 'page_settings', 'hide_social_sharing') && vdf_page_social_sharing() ): ?>
												<li class="post-meta-share">
													<?php get_template_part('social-sharing'); ?>
												</li>
											<?php endif; ?>
											<li><?php edit_post_link( esc_html__( 'Edit', 'videofly' ), '<span class="edit-link">', '</span>' ); ?></li>
										</ul>
									<?php endif ?>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12">
									<?php if ( !fields::logic($post->ID, 'page_settings', 'hide_featimg') ): ?>
									<div class="featured-image">
										<?php
											$post_thumbnail = get_post_thumbnail_id( get_the_ID() );
											$src = wp_get_attachment_url( $post_thumbnail ,'full'); //get img URL
											$img_url = aq_resize( $src, '1140', '9999', false, true); //crop img
											
											if ($img_url && has_post_thumbnail( get_the_ID() ) ): ?>
											<img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" >

											<?php if ( vdf_lightbox_enabled() ) {
												echo '<a class="zoom-in-icon" href="' . esc_url($src) . '" rel="fancybox[' . get_the_ID() . ']"><i class="icon-search"></i></a>';
											} ?>

											<?php if ( vdf_overlay_effect_is_enabled() ): ?>
												<div class="<?php echo vdf_overlay_effect_type() ?>"></div>
											<?php endif; endif; ?>
									</div>
									<?php endif; ?>
								</div>
							</div>
						</header><!-- .entry-header -->

						<div class="post-content">
							<?php the_content(); ?>
							<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . esc_html__( 'Pages:', 'videofly' ) . '</span>', 'after' => '</div>' ) ); ?>
						</div><!-- .entry-content -->
						<?php if (!fields::logic($post->ID, 'page_settings', 'hide_author_box')): ?>
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
						<?php endif ?>
					</article><!-- #post-<?php the_ID(); ?> -->
					<div class="row content-block">
						<div class="col-lg-12">
							<?php comments_template( '', true ); ?>
						</div>
					</div>
					<?php endwhile; // end of the loop. ?>
				</div>
			</div>
		</div>
	</div>
<?php
endif;
endif;

if ( LayoutCompilator::sidebar_exists() && !LayoutCompilator::builder_is_enabled() ) {
	if (LayoutCompilator::is_right_sidebar()) {
		if(isset($sidebar_content)) echo vdf_var_sanitize($sidebar_content);
	}
}
if ( !LayoutCompilator::builder_is_enabled() ):
?>
</div>
</section>

<?php endif;

get_footer(); ?>
