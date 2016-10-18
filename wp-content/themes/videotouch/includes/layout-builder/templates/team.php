<?php

/* Team view template below */
###########

// Get the options

global $article_options;

$meta = get_post_meta( get_the_ID(), 'ts_member', true);

$position = (trim(@$meta['position']) !== '') ? '<i class="author-position">'.$meta['position'].'</i>' : '';
					
// $position = @$meta['position'];
$about_member = @$meta['about_member'];

$arraySocials = array('facebook', 'twitter', 'linkedin', 'gplus', 'email', 'skype', 'github', 'dribble', 'lastfm', 'linkedin', 'tumblr', 'twitter', 'vimeo', 'wordpress', 'yahoo', 'youtube', 'flickr', 'pinterest', 'instagram');
$optionsSocial = get_option( 'videotouch_social' );
$customSocial = (isset($optionsSocial['social_new']) && is_array($optionsSocial['social_new']) && !empty($optionsSocial['social_new'])) ? $optionsSocial['social_new'] : array();

if( !empty($customSocial) ){
    $arraySocials = array_merge($arraySocials, array_keys($customSocial));
}

$social = '';
foreach($meta as $key => $value){
    
    if( !empty($value) && in_array($key, $arraySocials) ){

        if( $key == 'email' ){
            $icon = 'mail';
        }elseif( $key == 'dribble' ){
            $icon = 'dribbble';
        }elseif( $key == 'youtube' ){
            $icon = 'video';
        }else{
            $icon = NULL;
        }

        if( isset($customSocial[$key]) ){
        	$social .= '<li class=""><a id="ts-'. $key .'" href="'. esc_url($value) .'" class="icon-'. (isset($icon) ? $icon : $key) .'"><img src="'. $customSocial[$key]['image'] .'" alt=""></a></li>';
        	$social .= '<style>#ts-'. $key .':hover{background-color:'. $customSocial[$key]['image'] .'}</style>';
        }else{
        	$social .= '<li class=""><a href="'. esc_url($value) .'" class="icon-'. (isset($icon) ? $icon : $key) .'"> </a></li>';
        }
        

        foreach($customSocial as $id => $setting_social){
        	if( $id == $key ) $social .= '<style>#ts-'. $key .':hover{background-color:'. $setting_social['color'] .'}</style>';
        }
    }
}

// $author_img = get_the_post_thumbnail( get_the_ID(), 'team' );
// Get the featured image
$src = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );

$ts_image_is_masonry = false;
if ( isset($article_options['behavior']) && $article_options['behavior'] == 'masonry' ) {
    $ts_image_is_masonry = true;
}

$img_url = ts_resize('thumbnails', $src, $ts_image_is_masonry);

$noimg_url = get_template_directory_uri() . '/images/noimage.jpg';
$bool = fields::get_options_value('videotouch_general', 'enable_imagesloaded');

if ( $src ) {
	$featimage = '<img ' . ts_imagesloaded($bool, $img_url) . ' alt="' . esc_attr(get_the_title()) . '" />';
} else {
	$featimage = '<img ' .  ts_imagesloaded($bool, $noimg_url). ' alt="' . esc_attr(get_the_title()) . '" />';
}

// Get article columns by elements per row
$columns_class = LayoutCompilator::get_column_class($article_options['elements-per-row']);


?>
<div class="<?php echo $columns_class; ?>">
	<article>
		<header>
			<div class="image-holder">
				<?php echo $featimage; ?>
			</div>
			<div class="article-title">
				<h4> <?php esc_attr(the_title()); ?></h4>
			</div>
			<div class="article-position">
			<?php echo $position; ?>
			</div>
		</header>
		<div class="article-excerpt">
			<?php echo $about_member; ?>
		</div>
		<div class="article-social">
			<div class="social-icons">
				<ul class="">
					<?php echo $social; ?>
				</ul>
			</div>
		</div>
	</article>
</div>