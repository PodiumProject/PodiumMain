<?php 
global $wp_query;
$single_post = get_option('videotouch_single_post');
$default_videoplayer = $single_post['default_videoplayer'] ? $single_post['default_videoplayer'] : 'n';

$src = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );

$noimg_url = get_template_directory_uri() . '/images/noimage.jpg';
$noimg_src = '<img src="'.get_template_directory_uri() . '/images/noimage.jpg'.'" alt="No video feature image">';
$bool = fields::get_options_value('videotouch_general', 'enable_imagesloaded');

if ( $src ) {
    $img = ts_resize('single', $src);
    $featimage = '<img ' . ts_imagesloaded($bool, $img) . ' alt="' . esc_attr(get_the_title()) . '" />';
} else {
    $featimage = '<img ' .  ts_imagesloaded($bool, $noimg_url). ' alt="' . esc_attr(get_the_title()) . '" />';
}
$poster_url = '';
if ( ts_display_featured_image() && has_post_thumbnail( get_the_ID() ) ) {
    
    $src = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
    $poster_url = ts_resize('single', $src);
}

$bHas_button_play = ($single_post['button_play'] == 'y') ? 'true' : 'false';
$image_url = get_the_post_thumbnail(get_the_ID(), 'full');

$video_meta = get_post_meta($post->ID, 'video', true);
$video = '';
$self_hosted_video = "";

//Check if single video is on full width or with sidebar
$single_sidebar = get_option('videotouch_single_post', array('video_sidebar' => 'n'));
$single_sidebar['video_sidebar'] == 'n' ? $video_width = 1340 : $video_width = 965;

if( isset($video_meta['extern_url']) && !empty($video_meta['extern_url']) && $default_videoplayer === 'n' ) :
    $video = apply_filters('the_content',$video_meta['extern_url']);
elseif( isset($video_meta['your_url']) && !empty($video_meta['your_url']) && $default_videoplayer === 'n' ) :

    $self_hosted_video = "self-hosted";

    $atts = array(
        'src'      => esc_url($video_meta['your_url']),
        'poster'   => $poster_url,
        'loop'     => '',
        'autoplay' => '',
        'preload'  => 'metadata',
        'height'   => 560,
        'width'    => $video_width,
    );
    $video = wp_video_shortcode($atts); 

elseif( isset($video_meta['embed']) && !empty($video_meta['embed']) && $default_videoplayer === 'n' ) :
    $video = $video_meta['embed'];
elseif( isset($video_meta['extern_url']) && !empty($video_meta['extern_url']) && $default_videoplayer === 'y' ) :
    $video = esc_url($video_meta['extern_url']);
elseif( isset($video_meta['your_url']) && !empty($video_meta['your_url']) && $default_videoplayer === 'y' ) :
    $video = esc_url($video_meta['your_url']);
else :
    $video = '';
endif;

if( empty($video_meta['embed']) && empty($video_meta['extern_url']) && empty($video_meta['your_url']) ){
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    if( is_plugin_active('ccb-youtube/main.php') ){
        $bHas_button_play = 'false';
        if( $default_videoplayer == 'n' ){
            $video = ccb_single_custom_post_filter('');
        }else{
            $video = get_post_meta($post->ID, '__cbc_video_data', true);
            if( isset($video['video_id']) && !empty($video['video_id']) ){
                $video = 'https://www.youtube.com/watch?v='. $video['video_id'];
            }
        }
    }
} 

// Extract all post data
$article_date = '';
if (ts_human_type_date_format()) {
    $article_date = human_time_diff(strtotime(get_the_time('Y-m-d H:i:s'))).' '.__('ago', 'touchsize');
} else {
    $article_date =  get_the_date();
}

$single_options = get_option('videotouch_single_post', array('resize_video' => 'big', 'show_more' => 'y'));
if ( isset($_COOKIE['ts_single_video_resize_type']) ) {
    // Rewrite from cookie if exists
    $single_options['resize_video'] = $_COOKIE['ts_single_video_resize_type'];
}
$is_smaller = (isset($single_options) && isset($single_options['resize_video']) && $single_options['resize_video'] === 'small') ? ' is-smaller' : '';

$list_post = LayoutCompilator::get_single_related_posts(get_the_ID(), true);

?>
<div class="featured-image video-featured-image with-sidebar">
    <div class="container">
        <div class="row">
            <div class="<?php if( $list_post !== '' ) echo 'col-md-9 col-lg-9'; else echo 'col-md-12 col-lg-12' ?>">
                <div class="embedded_videos <?php echo $self_hosted_video ?>">
                    <?php if( !empty($video) && $default_videoplayer === 'n' ) : ?>
                        <div id="videoframe" class="video-frame" data-display-button="<?php echo $bHas_button_play; ?>">
                            <?php if( $bHas_button_play == 'true' && empty($video_meta['your_url']) ) : ?>
                                <div class="overimg">
                                        <div class="play-btn">
                                            <a class="videoPlay" href="#"><i class="icon-play"></i></a>
                                        </div>
                                    <?php if( !empty($image_url) ) : ?>    
                                        <div class="over-image">
                                            <?php echo $image_url; ?>
                                        </div>
                                    <?php elseif( empty($image_url) ) : ?>
                                        <div class="over-image">
                                            <?php echo $noimg_src; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="over-image"></div>
                                    <?php endif; ?>
                                    <div class="thumb-image"></div>
                                </div>
                            <?php endif; ?>
                            <div id="post-video">
                                <div class="video-container">
                                    <?php echo $video; ?>
                                </div>
                            </div>    
                        </div>
    				
    				<?php elseif( !empty($video) && $default_videoplayer === 'y' ) : ?>

                        <div class="video-container">
        					<div id="videoframe" class="video-frame"></div>
                        </div>
    					<script type="text/javascript">

    						var playerInstance = jwplayer("videoframe");

                            playerInstance.setup({
                                file: "<?php echo $video; ?>",
                                image: "<?php echo $poster_url; ?>",
                                width: 640,
                                height: 380,
                                title: "<?php the_title() ?>"
                            });

    					</script>

                    <?php else : ?>
                        <?php echo $featimage; ?>
                    <?php endif; ?>
                </div>
                <?php
                    if (trim($is_smaller) == 'is-smaller') {
                        $in_class = 'out';
                        $first_i_class = 'icon-left';
                        $second_i_class = 'icon-right';
                    } else{
                        $in_class = 'in';
                        $first_i_class = 'icon-right';
                        $second_i_class = 'icon-left';
                    }
                ?>
                <div class="video-single-resize <?php echo $in_class; ?>"><i class="<?php echo $first_i_class; ?>"></i><b class="icon-video"></b><span><?php _e('resize video','touchsize'); ?></span><i class="<?php echo $second_i_class; ?>"></i></div>
            </div>
            <?php if( $list_post !== '' ) :?>
                <div class="col-md-3 col-lg-3">
                    <div class="ts-video-playlist" id="watch-list-sidebar">
                        <?php 
                        	$list_post = LayoutCompilator::get_single_related_posts(get_the_ID(), true);
                        	if( $list_post !== '' && $list_post->have_posts() ){
                        		$video_posts_list = '<ol class="watch-playlist">';
                        			while($list_post->have_posts()){ 
                        				$list_post->the_post();
                        				$video_thumb = get_the_post_thumbnail(get_the_ID());
                        				$video_title = esc_attr(get_the_title(get_the_ID()));
                        				$video_url   = get_permalink(get_the_ID());

                        				if( !empty($video_thumb) ) {
        	                				$video_posts_list .= '<li><div class="video-thumb"><a href="'.$video_url.'">'.$video_thumb.'</a></div>';
                        				}else {
                        					$video_posts_list .= '<li><div class="video-thumb"><a href="'.$video_url.'">'.$noimg_src.'</a></div>';
                        				}
                        				$video_posts_list .= '<div class="video-description">
                        										<h4 class="title"><a href="'.$video_url.'">'. $video_title .'</a></h4>
                                                                <div class="video-meta">'. 
                                                                touchsize_likes(get_the_ID(), '<span class="likes">', '</span>', false) .'
                                                                <span class="views"><i class="icon-views"></i>'. ts_get_views(get_the_ID(), false) .'</span>
                                                                </div>';

                        				$video_posts_list .= '</div></li>';
                        			}
                        		$video_posts_list .= '</ol>';

                        		echo $video_posts_list;
                        		wp_reset_postdata();
                        	}
                        ?>
                    </div>
                </div>
            <?php endif; ?>   
        </div>
    </div>
</div>