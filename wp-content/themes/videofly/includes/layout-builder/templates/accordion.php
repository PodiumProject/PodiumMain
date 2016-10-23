<?php
global $article_options;

$src = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
$img_url = vdf_resize('grid', $src, false);

$noimg_url = get_template_directory_uri() . '/images/noimage.jpg';
$bool = fields::get_options_value('videofly_general', 'enable_imagesloaded');

if ( $src ) {
	$featimage = '<img ' . vdf_imagesloaded($bool, $img_url) . ' alt="' . esc_attr(get_the_title()) . '" />';
} else {
	$featimage = '<img ' . vdf_imagesloaded($bool, $noimg_url). ' alt="' . esc_attr(get_the_title()) . '" />';
}

$article_date = get_the_date();

$collapse_id = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);
$social_sharing = get_option('videofly_styles', array('sharing_overlay' => 'N'));
$i = $article_options['i'];
$accordion_id = $article_options['accordion_id'];

$subtitle = get_post_meta(get_the_ID(), 'post_settings', true);
$subtitle = (isset($subtitle['subtitle']) && $subtitle['subtitle'] !== '' && is_string($subtitle['subtitle'])) ? esc_attr($subtitle['subtitle']) : NULL;

// Get post rating
$rating_final = vdf_get_rating($post->ID);

?>
<div class="panel panel-default" role="tab">
	<div class="panel-heading<?php if( $i == 1 ) echo ' hidden'; ?>" role="tab" id="ts-<?php echo vdf_var_sanitize($collapse_id); ?>">
		<h4 class="panel-title">
			<a data-toggle="collapse" data-parent="#<?php echo vdf_var_sanitize($accordion_id); ?>" href="#<?php echo vdf_var_sanitize($collapse_id); ?>" aria-expanded="true" aria-controls="<?php echo vdf_var_sanitize($collapse_id); ?>"><?php the_title(); ?></a>
		</h4>


		<ul class="entry-meta">
			<li class="entry-meta-author">
				<a class="author-avatar" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
					<?php echo get_avatar(get_the_author_meta( 'ID' ), 140); ?>
				</a>
				<a class="author-name" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
					<?php echo get_the_author(); ?>
				</a>
			</li>
			<?php touchsize_likes($post->ID, '<li class="entry-meta-likes">', '</li>'); ?>
		</ul>
		<div class="icon-down">
			
		</div>

	</div>
	<div id="<?php echo vdf_var_sanitize($collapse_id); ?>" class="panel-collapse collapse<?php if( $i == 1 ) echo ' in' ?>" role="tabpanel" aria-labelledby="ts-<?php echo vdf_var_sanitize($collapse_id); ?>">
		<div class="inner-content">
			<article>
				<header>
					<div class="image-holder">
						<?php if( isset($rating_final) ) : ?>
							<div class="post-rating-circular">
								<div class="circular-content">
									<div class="counted-score"><?php echo vdf_var_sanitize($rating_final); ?>/10</div>
								</div>
							</div>
						<?php endif; ?>
						<?php echo vdf_var_sanitize($featimage); ?>
						<?php
						if ( vdf_overlay_effect_is_enabled() ) {
							echo '<div class="' . vdf_overlay_effect_type() . '"></div>';
						}
						?>
						<?php vdfHoverStyle(get_the_ID()); ?>
					</div>
				</header>
				<section class="entry-content">
					<h3 class="entry-title">
						<a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>">
							<?php the_title(); ?>
						</a>
					</h3>
					<ul class="entry-meta">
						<li class="entry-meta-author">
							<a class="author-avatar" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
								<?php echo get_avatar(get_the_author_meta( 'ID' ), 140); ?>
							</a>
							<a class="author-name" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
								<?php echo get_the_author(); ?>
							</a>
						</li>
						<?php touchsize_likes($post->ID, '<li class="entry-meta-likes">', '</li>'); ?>
					</ul>
					<div class="entry-excerpt">
						<?php vdf_excerpt(170, get_the_ID(), 'show-subtitle'); ?>
					</div>
				</section>
			</article>
		</div>
	</div>
</div>