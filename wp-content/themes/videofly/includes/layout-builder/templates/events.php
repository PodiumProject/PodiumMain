<?php 

$src = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );

$img_url = vdf_resize('grid', $src);
$noimg_url = get_template_directory_uri() . '/images/noimage.jpg';
$bool = fields::get_options_value('videofly_general', 'enable_imagesloaded');

if ( $src ) {
	$featimage = '<img '. vdf_imagesloaded($bool, $img_url) .' alt="' . esc_attr(get_the_title()) . '" />';
} else {
	$featimage = '<img '. vdf_imagesloaded($bool, $noimg_url) .' alt="' . esc_attr(get_the_title()) . '" />';
}

$post_meta = get_post_meta(get_the_ID(), 'event', true);

$repeat = (isset($post_meta['event-enable-repeat']) && ($post_meta['event-enable-repeat'] == 'n' || $post_meta['event-enable-repeat'] == 'y')) ? $post_meta['event-enable-repeat'] : 'n';

$free_paid = '';
$price = '';

if( isset($post_meta['free-paid']) ){
	if( $post_meta['free-paid'] == 'free' ){
		$free_paid = esc_html__('Free', 'videofly');
	}else{
		if( isset($post_meta['ticket-url']) && !empty($post_meta['ticket-url']) ){
			$free_paid = '<a href="' . esc_url($post_meta['ticket-url']) . '">' . esc_html__('BUY', 'videofly') . '</a>';
		}
		$price = (isset($post_meta['price'])) ? $post_meta['price'] : '';
	}
}
$day = '';
$month = '';
$vdf_event_start_time = '';
$day_meta = get_post_meta(get_the_ID(), 'day', true);
if( isset($day_meta) && (int)$day_meta !== 0 ){
	$month = date("M", $day_meta);
	$day = date("j", $day_meta);
	$vdf_event_start_time = date("g a", $day_meta);
}

?>
<article>
	<header>
		<ul class="entry-meta-date">
			<li class="meta-date"><?php echo vdf_var_sanitize($day, 'esc_attr') ?></li>
			<li class="meta-month"><?php echo vdf_var_sanitize($month, 'esc_attr'); ?></li>
			<li class="meta-hour"><?php echo vdf_var_sanitize($vdf_event_start_time) ?></li>
		</ul>
		<div class="entry-content">
			<h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
			<span class="block-price"><?php echo vdf_var_sanitize($free_paid); ?></span>
			<div class="entry-meta">
				<div class="entry-address">
					<address><?php echo isset($post_meta['venue']) ? $post_meta['venue'] : ''; ?></address>
				</div>
			</div>
		</div>
	</header>
	<section>
		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
				<div class="image-holder">
					<?php echo vdf_var_sanitize($featimage); ?>					
					<?php vdfHoverStyle(get_the_ID()); ?>
				</div>
			</div>
			<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
				<div class="entry-excerpt">
					<?php vdf_excerpt(470, get_the_ID(), 'show-excerpt'); ?>
				</div>
			</div>
		</div>
	</section>
</article>