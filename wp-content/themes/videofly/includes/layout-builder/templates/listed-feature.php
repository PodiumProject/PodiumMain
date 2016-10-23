<?php

/* Thumbnail view template below */
###########

// Get the options

global $article_options;

if( isset($article_options) && $article_options['features'] != '[]' && $article_options['features'] != '' ) :
	
$arr = ( json_decode(stripslashes($article_options['features'])) ) ? json_decode(stripslashes($article_options['features'])) : array(); 
$align_icons = ( isset($article_options['features-align']) ) ? esc_attr($article_options['features-align']) : 'text-left';

$color_style = (isset($article_options['color-style']) && $article_options['color-style'] === 'none') ? 'ts-listed-features-no-border' : (isset($article_options['color-style']) && $article_options['color-style'] === 'border' ? 'has-border' : (isset($article_options['color-style']) && $article_options['color-style'] === 'background' ? 'has-background' : ''));

foreach($arr as $vdf_option){
	$icon_color = ( isset($vdf_option->iconcolor) ) ? esc_attr(str_replace('--quote--', '"', $vdf_option->iconcolor)) : '';
	$border_color = ( isset($vdf_option->bordercolor) ) ? esc_attr(str_replace('--quote--', '"', $vdf_option->bordercolor)) : '';
	$bg_color = ( isset($vdf_option->backgroundcolor) ) ? esc_attr(str_replace('--quote--', '"', $vdf_option->backgroundcolor)) : '';
	$title_url = ( isset($vdf_option->url) ) ? str_replace('--quote--', '"', $vdf_option->url) : '';

	if($article_options['color-style'] == 'border'){
		$styles = ' border-color: '.$border_color.'; color: '.$icon_color;
	}elseif ($article_options['color-style'] == 'background') {
		$styles = ' background-color: '.$bg_color.'; color: '.$icon_color.'; border-color: transparent;';
	}else{
		$styles = ' color: '.$icon_color;
	}

?>
<div class="col-lg-12 col-md-12 col-sm-12">
	<article data-display="<?php echo esc_attr($article_options['color-style']); ?>" data-alignment="<?php echo vdf_var_sanitize($align_icons) ?>" <?php echo post_class(); ?> >
		<header>
			<div class="article-header-content">
				<div class="image-container" style="<?php echo vdf_var_sanitize($styles) ?>; ">
					<i class="<?php echo str_replace('--quote--', '"', esc_attr($vdf_option->icon)); ?>"></i>
				</div>
			</div>
		</header>
		<section>
			<div class="article-title">
				<?php if( !empty($title_url) ) : ?>
					<h6 class="title"><a href="<?php echo esc_url($title_url); ?>"><?php echo str_replace('--quote--', '"', esc_attr($vdf_option->title));  ?></a></h6>
				<?php else : ?>
					<h6 class="title"><?php echo str_replace('--quote--', '"', esc_attr($vdf_option->title));  ?></h6>
				<?php endif; ?>
			</div>
			<div class="article-excerpt">
				<div class="feature-text">
					<?php echo apply_filters('ts_the_content', str_replace('--quote--', '"', $vdf_option->text)); ?>
				</div>
			</div>
		</section>
	</article>
</div>
<?php } endif; ?>