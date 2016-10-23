<?php
get_header();

/* Team single page */

$teams = get_post_meta($post->ID, 'ts_member', TRUE);
$teams  = (isset($teams) && !empty($teams)) ? $teams : array();

$optionsSocial = get_option( 'videofly_social' );
$customSocial = (isset($optionsSocial['social_new']) && is_array($optionsSocial['social_new']) && !empty($optionsSocial['social_new'])) ? $optionsSocial['social_new'] : '';

$description = (isset($teams['about_member'])) ? apply_filters('ts_the_content', $teams['about_member']) : '';
$title       = (isset($teams['title'])) ? esc_attr($teams['title']) : '';
$arraySocials = array('facebook', 'linkedin', 'gplus', 'email', 'skype', 'github', 'dribble', 'lastfm', 'linkedin', 'tumblr', 'twitter', 'vimeo', 'wordpress', 'yahoo', 'youtube', 'flickr', 'pinterest', 'instagram');

$categories  = get_the_terms($post->ID, 'teams');
$term_ids    = wp_get_post_terms($post->ID, 'teams', array('fields' => 'ids'));

if( is_array($term_ids) && !empty($term_ids) ){
	$args = array(
		'post_type'    => 'ts_teams',
		'post__not_in' => array($post->ID),
		'tax_query'    => array(
	        array( 
	            'taxonomy' => 'teams',
	            'field'    => 'id',
	            'terms'    => $term_ids
	        )
	    ),
		'posts_per_page' => 3
	);
	$query = new WP_Query($args);
	$options = array();
	$options['elements-per-row'] = 3;
	if( $query->have_posts() ){
		$related_teams = LayoutCompilator::teams_element($options, $query);
	}
}

$author_id = (isset($teams['team-user']) && absint($teams['team-user']) > 0) ? absint($teams['team-user']) : '';

if( $author_id !== '' ){
	$args = array(
		'author' => $author_id,
	);
	$query_author = new WP_Query($args);

	$options_author = array();
	$options_author['display-title'] = 'title-below';
	$options_author['display-mode'] = 'thumbnails';
	$options_author['elements-per-row'] = 4;
	$options_author['meta-thumbnail'] = 'y';

	if( $query_author->have_posts() ){
		$related_teams_author = LayoutCompilator::last_posts_element($options_author, $query_author);
	}
}

?>
<div id="primary" class="ts-team-single">
	<div id="content" role="main">		
		<div class="container team-general">
			<div class="row">
				<div class="col-sm-3 col-md-3">
					<div class="member-thumb">
						<?php the_post_thumbnail(); ?>
					</div>
				</div>
				<div class="col-sm-9 col-md-9">
					<div class="member-content">
						<div class="member-name">
							<h3 class="title"><?php the_title(); ?></h3>
							<ul class="category">
							<?php if( is_array($categories) ) : ?>
								<?php $i = 1; foreach($categories as $key=>$category) : ?>
									<?php
										// Get the URL of this category
										$category_link = get_term_link( $category->term_id, 'teams');
									?>
									<?php if( $i < count($categories) ) : ?>
										<li><?php echo esc_attr($category->name); ?></li>
									<?php else : ?>
										<li><?php echo esc_attr($category->name); ?></li>
									<?php endif; ?>
								<?php $i++; endforeach; ?>
							<?php endif; ?>
							</ul>
						</div>
						<span class="position"><?php echo esc_attr($teams['position']); ?></span>
						<p class="author-short-description"><?php echo vdf_var_sanitize($description); ?></p>
						<hr>
						<div class="social-icons">
							<ul>
								<?php if( !empty($teams) ) : ?>
									<?php foreach($teams as $key => $social) : ?>
										<?php if( in_array($key, $arraySocials) && !empty($social) ) : ?>
											<?php
												if( $key == 'email' ){
												    $icon = 'mail';
												}elseif( $key == 'dribble' ){
												    $icon = 'dribbble';
												}elseif( $key == 'youtube' ){
												    $icon = 'video';
												}else{
												    $icon = NULL;
												}
											?>
											<li><a class="icon-<?php echo (isset($icon) ? $icon : $key) ?>" href="<?php echo esc_url($social); ?>"></a></li>
										<?php endif; ?>
									<?php endforeach; ?>
								<?php endif; ?>
								<?php if( !empty($customSocial) ) : ?>
									<?php foreach ($customSocial as $key => $value) : 
											if( !isset($teams[$key]) || empty($teams[$key]) ) continue;
											$socialNew = (isset($teams[$key])) ? $teams[$key] : ''; 
											echo '<style>#ts-'. $key .':hover{background-color:'. $value['color'] .'}</style>'; ?>
										<li><a id="ts-<?php echo esc_attr($key); ?>" href="<?php echo (!empty($socialNew) ? esc_url($socialNew) : $value['url']); ?>"><img src="<?php echo esc_url($value['image']); ?>" alt=""></a></li>
									<?php endforeach; ?>
								<?php endif; ?>
							</ul>
						</div>
						<br><br>
						<div class="post-content">
							<?php the_content(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container-fluid related-members">
			<div class="container">
				<div class="row">
					<?php if( isset($related_teams_author) ): ?>
						<div class="col-lg-12">
							<h3><?php esc_html_e('Author articles', 'videofly'); ?></h3>
						</div>
						<?php echo balanceTags($related_teams_author); ?>
					<?php endif; ?>
					<div class="col-lg-12">
						<h3><?php esc_html_e('Related members', 'videofly'); ?></h3>
					</div>
					<?php if( isset($related_teams) ) echo balanceTags($related_teams); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>