<?php get_header(); ?>
<?php if( get_post_type() !== 'video' ): ?>
<section id="main">
<?php
	$generalSingle = get_option('videofly_single_post');
	if( $generalSingle['breadcrumbs'] === 'y' ) :
?>
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
<?php endif; ?>
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
	$topics = wp_get_post_terms( get_the_ID() , 'event_categories' );

	$terms = array();
	if( !empty( $topics ) ){
	    foreach ( $topics as $topic ) {
	        $term = get_category( $topic->slug );
	        array_push( $terms, $topic->slug );
	    }
	}
	$article_categories = '';
	foreach ($terms as $key => $term) {
		$article_categories .= '<li>' . '<a href="' . esc_attr(get_term_link($term, 'event_categories')) . '" title="' . esc_html__('View all articles from: ', 'videofly') . $term . '" ' . '>' . $term.'</a></li>';;
	}

	$vdf_event_meta = get_post_meta($post->ID, 'event', true);
	$vdf_event_day = get_post_meta($post->ID, 'day', true);
	$vdf_event_day = (isset($vdf_event_day) && abs($vdf_event_day) !== 0) ? abs($vdf_event_day) : NULL;
	// Get the start day
	if ( isset($vdf_event_day) ) {
		$vdf_event_start_day = date('d M Y', $vdf_event_day);
	} else{
		$vdf_event_start_day = esc_html__('Date not set', 'videofly');
	}
	// Get the event end day
	if ( isset($vdf_event_meta['event-end']) ) {
		$vdf_event_end_day = date('d M Y', strtotime($vdf_event_meta['event-end']));
	} else{
		$vdf_event_end_day = esc_html__('End day not set', 'videofly');
	}
	// Get the start time
	if ( isset($vdf_event_meta['start-time']) ) {
		$vdf_event_start_time = esc_attr($vdf_event_meta['start-time']);
	} else{
		$vdf_event_start_time = esc_html__('Time not set', 'videofly');
	}
	// Get the end time
	if ( isset($vdf_event_meta['end-time']) ) {
		$vdf_event_end_time = esc_attr($vdf_event_meta['end-time']);
	} else{
		$vdf_event_end_time = esc_html__('Time not set', 'videofly');
	}
	// Get the event days
	if ( isset($vdf_event_meta['event-days']) ) {
		$vdf_event_end_days = esc_attr($vdf_event_meta['event-days']);
	} else{
		$vdf_event_end_days = esc_html__('Days not set not set', 'videofly');
	}
	// Get the event repeat
	if ( isset($vdf_event_meta['event-enable-repeat']) ) {
		$vdf_event_repeat = esc_attr($vdf_event_meta['event-enable-repeat']);
	} else{
		$vdf_event_repeat = esc_html__('Repeat not set', 'videofly');
	}
	// Get the event tematic
	if ( isset($vdf_event_meta['theme']) ) {
		$vdf_event_tematic = esc_attr($vdf_event_meta['theme']);
	} else{
		$vdf_event_tematic = esc_html__('Tematic not set', 'videofly');
	}
	// Get the event person
	if ( isset($vdf_event_meta['person']) ) {
		$vdf_event_person = esc_attr($vdf_event_meta['person']);
	} else{
		$vdf_event_person = esc_html__('Person not set', 'videofly');
	}
	// Get the event map
	if ( isset($vdf_event_meta['map']) ) {
		$vdf_event_map = $vdf_event_meta['map'];
	} else{
		$vdf_event_map = esc_html__('Person not set', 'videofly');
	}
	// Get the event venue
	if ( isset($vdf_event_meta['venue']) ) {
		$vdf_event_venue = esc_attr($vdf_event_meta['venue']);
	} else{
		$vdf_event_venue = esc_html__('Venue not set', 'videofly');
	}

?>
		<div itemscope itemtype="http://data-vocabulary.org/Event" id="primary" class="<?php echo vdf_var_sanitize($content_class) ?>">
			<div id="content" role="main">		
				<div class="row">
					<div class="col-lg-12">
						<div class="row">
							<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
								<header class="post-header">
									<div class="row">
										<div class="col-md-12">
											<div class="event-map">
												<?php echo vdf_var_sanitize($vdf_event_map); ?>
											</div>
										</div>
										<div class="col-lg-12">
											<?php edit_post_link( esc_html__( 'Edit', 'videofly' )); ?>
										</div>
									</div>
									<div class="row">
										<?php if ( !fields::logic($post->ID, 'post_settings', 'hide_title') ): ?>
											<div class="col-sm-12 col-md-7 col-lg-7">
												<h1 class="page-title"><?php the_title(); ?></h1>
											</div>
											<div class="col-sm-12 col-md-5 col-lg-5">
												<div class="event-time">
													<i class="icon-time"></i>
													<span  itemprop="startDate" datetime="<?php echo vdf_var_sanitize($vdf_event_start_time) ?>"><?php echo vdf_var_sanitize($vdf_event_start_time) ?></span>
													-
													<span itemprop="endDate" datetime="<?php echo vdf_var_sanitize($vdf_event_end_time) ?>"><?php echo vdf_var_sanitize($vdf_event_end_time) ?></span>
												</div>
											</div>
										<?php endif ?>
										<?php if ( vdf_single_display_meta() && !fields::logic($post->ID, 'post_settings', 'hide_meta') ): ?>
											<div class="col-md-12 col-lg-12">
												<ul class="event-meta">
													<li class="event-start-date">
														<span class="meta"><?php esc_html_e('start date','videofly'); ?></span>
														<span role="start-date"><?php echo vdf_var_sanitize($vdf_event_start_day); ?></span>
													</li>
													<li class="event-end-date">
														<span class="meta"><?php esc_html_e('end date','videofly'); ?></span>
														<span role="end-date"><?php echo vdf_var_sanitize($vdf_event_end_day); ?></span>
													</li>
													<li class="event-venue">
														<span class="meta"><?php esc_html_e('venue','videofly'); ?></span>
														<span itemprop="location" role="venue"><?php echo vdf_var_sanitize($vdf_event_venue); ?></span>
													</li>
													<?php if ( $vdf_event_repeat != 'n' ): ?>	
													<li class="repeat">
														<span role="repeat"><i class="icon-recursive"></i></span>
													</li>
													<?php endif ?>
												</ul>
											</div>
										<?php endif ?>
									</div>
								</header><!-- .post-header -->
								
								<div class="post-content">
									<?php the_content(); ?>
									<!-- Start the rest of the event meta -->
									<ul itemprop="tickets" itemscope itemtype="http://data-vocabulary.org/Offer" class="event-meta-details">
										<?php if (isset($vdf_event_meta['person']) && trim($vdf_event_meta['person']) != ''): ?>
											<li><span><?php echo esc_html__('Host:','videofly'); ?></span> <?php echo esc_attr($vdf_event_meta['person']); ?></li>
										<?php endif ?>
										<?php if (isset($vdf_event_meta['price']) && trim($vdf_event_meta['price']) != ''): ?>
											<li><span><?php echo esc_html__('Price:','videofly'); ?></span itemprop="price"> <?php echo esc_attr($vdf_event_meta['price']); ?></li>
										<?php endif ?>
										<?php if (isset($vdf_event_meta['ticket-url']) && trim($vdf_event_meta['ticket-url']) != ''): ?>
											<li><span><?php echo esc_html__('Tickets:','videofly'); ?></span> <?php echo '<a  itemprop="offerurl" href="'.esc_url($vdf_event_meta['ticket-url']).'" target="_blank">' . esc_url($vdf_event_meta['ticket-url']).'</a>'; ?></li>
										<?php endif ?>
									</ul>
									<!-- End of event meta -->
									<div class="post-meta-share">
										<?php 
											if(vdf_single_social_sharing()): get_template_part('social-sharing');
										?>
										<?php endif; ?>
									</div>

									<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . esc_html__( 'Pages:', 'videofly' ) . '</span>', 'after' => '</div>' ) ); ?>
								</div><!-- .post-content -->
								
								<footer class="post-footer">
									<?php if( $generalSingle['display_author_box'] == 'n' ): ?>
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
									<?php $tags_columns = (has_tag()) ? 'col-lg-6' : 'col-lg-12'; ?>
									<div class="row">
										<?php if ( vdf_single_display_meta() && !fields::logic($post->ID, 'post_settings', 'hide_meta') ): ?>
											<?php if( has_tag() ) : ?>
												<div class="<?php echo vdf_var_sanitize($tags_columns); ?>">
													<div class="row">
														<div class="col-sm-12 col-md-12">
															<div class="post-tagged-icon">
															</div>
															<div class="post-details">
																<h6 class="post-details-title"><?php esc_html_e('Tags','videofly'); ?></h6>
																<div class="single-post-tags">
																	<?php if (vdf_single_display_tags()): ?>
																		<?php the_tags('<ul class="tags-container"><li>','</li><li>','</li></ul>'); ?>
																	<?php endif ?>
																</div>
															</div>
														</div>
													</div>
												</div>
											<?php endif; ?>
										<?php endif; ?>
										
									</div>
									<?php if ( !fields::logic($post->ID, 'post_settings', 'hide_related') && vdf_single_display_related() ): ?>
										<div class="row">
											<div class="col-lg-12">
												<h4 class="related-title"><?php esc_html_e('Related events', 'videofly'); ?></h4>
											</div>
											<?php echo LayoutCompilator::get_single_related_posts(get_the_ID()); ?>
										</div>
									<?php endif; ?>
								</footer>
							</article><!-- #post-<?php the_ID(); ?> -->
							
							<!-- Ad area 2 -->
							<?php if( fields::get_options_value('videofly_theme_advertising','ad_area_2') != '' ): ?>
							<div class="container text-center ts-advertising-container">
								<?php echo fields::get_options_value('videofly_theme_advertising','ad_area_2'); ?>
							</div>
							<?php endif; ?>
							<!-- // End of Ad Area 2 -->

							<div class="row content-block">
								<div class="col-lg-12">
									<?php comments_template( '', true ); ?>
								</div>
							</div>
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
<?php vdf_get_pagination_next_previous(); ?>
</section>
<?php endwhile; ?>
<?php endif; ?>
<?php get_footer(); ?>