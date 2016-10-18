<?php

/* Thumbnail view template below */
###########

// Get the options

global $article_options;

if( isset($article_options) && $article_options['features'] != '[]' && $article_options['features'] != '' ) :
	
$arr = ( json_decode(stripslashes($article_options['features'])) ) ? json_decode(stripslashes($article_options['features'])) : array(); 
$align_icons = ( isset($article_options['features-align']) ) ? strip_tags($article_options['features-align']) : 'text-left';

$color_style = (isset($article_options['color-style']) && $article_options['color-style'] === 'none') ? 'ts-listed-features-no-border' : (isset($article_options['color-style']) && $article_options['color-style'] === 'border' ? 'has-border' : (isset($article_options['color-style']) && $article_options['color-style'] === 'background' ? 'has-background' : ''));

foreach($arr as $ts_option){
	$icon_color = ( isset($ts_option->iconcolor) ) ? strip_tags(str_replace('--quote--', '"', $ts_option->iconcolor)) : '';
	$border_color = ( isset($ts_option->bordercolor) ) ? strip_tags(str_replace('--quote--', '"', $ts_option->bordercolor)) : '';
	$bg_color = ( isset($ts_option->backgroundcolor) ) ? strip_tags(str_replace('--quote--', '"', $ts_option->backgroundcolor)) : '';
	if($article_options['color-style'] == 'border'){
		$styles = ' border-color: '.$border_color.'; color: '.$icon_color;
	}elseif ($article_options['color-style'] == 'background') {
		$styles = ' background-color: '.$bg_color.'; color: '.$icon_color.'; border-color: transparent;';
	}else{
		$styles = ' color: '.$icon_color;
	}

?>
<div class="col-lg-12 col-md-12 col-sm-12">
	<article data-display="<?php echo $article_options['color-style']; ?>" data-alignment="<?php echo $align_icons ?>" <?php echo post_class(); ?> >
		<header>
			<div class="article-header-content">
				<div class="image-container" style="<?php echo $styles ?>; ">
					<i class="<?php echo str_replace('--quote--', '"', $ts_option->icon); ?>"></i>
				</div>
			</div>
		</header>
		<section>
			<div class="article-title">
				<h6 class="title"><?php echo str_replace('--quote--', '"', esc_attr($ts_option->title));  ?></h6>
			</div>
			<div class="article-excerpt">
				<div class="feature-text">
					<?php echo apply_filters('the_content', str_replace('--quote--', '"', $ts_option->text)); ?>
				</div>
			</div>
		</section>
	</article>
</div>
<?php } endif; ?>