<?php
$all_social = array('facebook', 'twitter', 'gplus', 'linkedin', 'tumblr', 'pinterest');
$facebook_count = 0;
$twitter_count = 0;
$gplus_count = 0;
$linkedin_count = 0;
$tumblr_count = 0;
$pinterest_count = 0;

foreach($all_social as $social){
	$count_social = get_post_meta(get_the_ID(), 'ts-social-' . $social, true);
	if(isset($count_social) && (int)$count_social !== 0 && $social == 'facebook') $facebook_count = (int)$count_social;
	if(isset($count_social) && (int)$count_social !== 0 && $social == 'twitter') $twitter_count = (int)$count_social;
	if(isset($count_social) && (int)$count_social !== 0 && $social == 'gplus') $gplus_count = (int)$count_social;
	if(isset($count_social) && (int)$count_social !== 0 && $social == 'linkedin') $linkedin_count = (int)$count_social;
	if(isset($count_social) && (int)$count_social !== 0 && $social == 'tumblr') $tumblr_count = (int)$count_social;
	if(isset($count_social) && (int)$count_social !== 0 && $social == 'pinterest') $pinterest_count = (int)$count_social;
}
$count_total = $facebook_count + $twitter_count + $gplus_count + $linkedin_count + $tumblr_count + $pinterest_count;

?>
<div class="ts-post-sharing">
	<!-- <label for="share-options count"><i class="icon-share"></i><span class="ts-label-text"><?php esc_html_e('Shares', 'videofly'); ?></span><span class="counted"><?php echo absint($count_total); ?></span></label> -->
	<ul class="entry-meta-share">
	    <li class="share-menu-item" data-social="facebook" data-post-id="<?php echo get_the_ID(); ?>">
	        <a target="_blank" data-tooltip="<?php esc_html_e('share on facebook','videofly'); ?>" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo get_permalink(get_the_ID()); ?>"><span><?php echo absint($facebook_count); ?></span><i class="icon-facebook"></i></a>
	    </li>
	    <li class="share-menu-item" data-social="twitter" data-post-id="<?php echo get_the_ID(); ?>">
	        <a target="_blank" data-tooltip="<?php esc_html_e('share on twitter','videofly'); ?>" href="http://twitter.com/home?status=<?php echo urlencode(sanitize_text_field(get_the_title())); ?>+<?php echo get_permalink(get_the_ID()); ?>"><span><?php echo absint($twitter_count); ?></span><i class="icon-twitter"></i></a>
	    </li>
	    <li class="share-menu-item" data-social="gplus" data-post-id="<?php echo get_the_ID(); ?>">
	        <a target="_blank" data-tooltip="<?php esc_html_e('share on google+','videofly'); ?>" href="https://plus.google.com/share?url=<?php echo get_permalink(get_the_ID()); ?>"><span><?php echo absint($gplus_count); ?></span><i class="icon-gplus"></i></a>
	    </li>
	    <li class="share-menu-item secondary ts-collapsed" data-social="linkedin" data-post-id="<?php echo get_the_ID(); ?>">
	        <a target="_blank" data-tooltip="<?php esc_html_e('share on linkedin','videofly'); ?>" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo get_permalink(get_the_ID()); ?>&title=<?php echo urlencode(sanitize_text_field(get_the_title())); ?>"><span class="how-many"><?php echo absint($linkedin_count); ?></span> <i class="icon-linkedin" ></i></a>
	    </li>
	    <li class="share-menu-item secondary ts-collapsed" data-social="tumblr" data-post-id="<?php echo get_the_ID(); ?>">
	        <a target="_blank" data-tooltip="<?php esc_html_e('share on tumblr','videofly'); ?>" href="http://www.tumblr.com/share/link?url=<?php echo urlencode(get_permalink(get_the_ID())); ?>&name=<?php echo sanitize_text_field($post->post_title); ?>&description=<?php echo sanitize_text_field($post->post_excerpt); ?>"><span><?php echo absint($tumblr_count); ?></span><i class="icon-tumblr"></i></a>
	    </li>
	    <li class="share-menu-item secondary ts-collapsed" data-social="pinterest" data-post-id="<?php echo get_the_ID(); ?>">
	        <a target="_blank" data-tooltip="<?php esc_html_e('share on pinterest','videofly'); ?>" href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&amp;media=<?php if(function_exists('the_post_thumbnail')) echo wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())); ?>&amp;description=<?php echo urlencode(sanitize_text_field(get_the_title())); ?>" ><span><?php echo absint($pinterest_count); ?></span><i class="icon-pinterest"></i></a>
	    </li>
	    <li class="show-more" data-social="more" data-post-id="<?php echo get_the_ID(); ?>">
	        <a  class="closed"  data-tooltip="<?php esc_html_e('Show more','videofly'); ?>"><i class="icon-threedots"></i></a>
	    </li>	    
	</ul>
</div>