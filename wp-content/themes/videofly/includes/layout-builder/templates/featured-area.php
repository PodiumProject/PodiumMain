<?php
global $element, $featQuery;

$taxonomy = $element['custom-post'] == 'post' ? 'category' : ($element['custom-post'] == 'video' ? 'videos_categories' : ($element['custom-post'] == 'ts-gallery' ? 'gallery_categories' : ''));

$postsThumb = '';
?>

<div class="ts-cyncing-for featured-sync">
	<div id="slider" class="ts-slides">
		<ul class="slides">
			<?php while ( $featQuery->have_posts() ): $featQuery->the_post(); ?>
				<li>
					<div class="container">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12">
								<article>
									<header>
										<div class="image-holder">
											<a href="<?php the_permalink(); ?>">
												<?php 
													$src = has_post_thumbnail($post->ID) ? wp_get_attachment_url(get_post_thumbnail_id($post->ID)) : get_template_directory_uri() .'/images/noimage.jpg';
													
													echo LayoutCompilator::tsGetPostImg( $post->ID, $post->post_title, 'featarea', $src );

													$postsThumb .= 
														'<li>	
															<div class="image-holder">
																<img src="'. aq_resize($src, 200, 100, true, true) .'" alt="'. get_the_title() .'" />
															</div>			
														</li>';

													$attrs = $element['custom-post'] == 'video' && $element['play'] == 'modal' ? ' data-postid="'. $post->ID .'" data-video="modal"' : '';
												?>
											</a>
										</div>
									</header>
									<section>
										<h3>
											<a href="<?php the_permalink(); ?>"<?php echo vdf_var_sanitize($attrs); ?>>
												<?php echo ($element['custom-post'] == 'video' ? '<i class="icon-play"></i>' : '' ) . esc_attr($post->post_title) ?>
											</a>
										</h3>
										<ul class="entry-meta">
											<li class="entry-meta-category">
												<i class="icon-category"></i>
												<?php echo get_the_term_list($post->ID, $taxonomy, '<ul><li>', '</li><li>', '</li></ul>'); ?>
											</li>
											<li class="entry-meta-author">
												<a href="<?php echo get_author_posts_url($post->post_author); ?>"><i class="icon-user"></i><?php the_author(); ?></a>
											</li>
										</ul>
									</section>
								</article>
							</div>
						</div>
					</div>
				</li>
			<?php endwhile; ?>				
		</ul>
	</div>
</div>
<div class="ts-cyncing-nav">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div id="carousel" class="slide-nav">
					<ul class="slides">
						<?php echo vdf_var_sanitize($postsThumb); ?>
					</ul>
					<ul class="ts-flex-navigation">
						<li class="ts-left-arrow"><a href="#" class="prev icon-left-arrow-thin"></a></li>
						<li class="ts-right-arrow"><a href="#" class="next icon-right-arrow-thin"></a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
