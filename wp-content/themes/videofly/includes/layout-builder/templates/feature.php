<?php

/* Thumbnail view template below */
###########

// Get the options

global $article_options;

// Get style for the feature blocks

if( isset($article_options['style']) ){
	$style = $article_options['style'];
}

if( isset($article_options['elements-per-row']) ){
	$elements_per_row = $article_options['elements-per-row'];
}

if( isset($article_options['features-block']) && $article_options['features-block'] != '[]' && $article_options['features-block'] != '' ){
	$arr = json_decode(stripslashes($article_options['features-block']));
	
	foreach($arr as $vdf_option){
		
		// Get the number of features per row

		$columns_class = LayoutCompilator::get_column_class($elements_per_row);

		if ( $style == 'style1' ) {
			$style1_color = ' color:'.str_replace('--quote--', '"', $vdf_option->font).'; ';
			$style1_border = ' border-color: '.str_replace('--quote--', '"', $vdf_option->background).'; ';
			$style2_bgcolor = '';
			$style2_color = '';
		}elseif ( $style == 'style2' ) {
			$style2_bgcolor = ' background-color:'. str_replace('--quote--', '"', $vdf_option->background). '; ';
			$style2_color = ' color:'. str_replace('--quote--', '"', $vdf_option->font) . '; ';
			$style1_color = "";
			$style1_border = "";
		}else{
			$style1_color = "";
			$style1_border = "";
			$style2_color = "";
			$style2_bgcolor = "";
		}

		$effect = isset($vdf_option->effect) && $vdf_option->effect !== 'none' ? $vdf_option->effect : 'none';
		$classDelay = isset($vdf_option->delay) && $vdf_option->delay !== 'none' ? ' '. $vdf_option->delay : '';

		// Add article specific classes
	?>
		<div class="<?php echo vdf_var_sanitize($columns_class); ?><?php echo ($effect !== 'none' ? ' animatedParent animateOnce' : '') ?>">
			<figure style="<?php echo vdf_var_sanitize($style1_border.$style2_bgcolor); ?>"<?php echo ($effect !== 'none' ? ' class="animated '. $effect . $classDelay .'"' : '') ?>>
				<header>
					<div class="image-container" style="<?php echo vdf_var_sanitize($style1_color.$style2_color); ?>">
						<i class="<?php echo str_replace('--quote--', '"', $vdf_option->icon); ?>"></i>
					</div>
					<?php if( $style == 'style2' ): ?>
						<div class="entry-title">
							<h4 class="title"><?php echo str_replace('--quote--', '"', $vdf_option->title);  ?></h4>
						</div>
					<?php endif; ?>
				</header>
				<figcaption>
					<?php if( $style == 'style1' ): ?>
						<div class="entry-title">
							<h4 class="title"><?php echo str_replace('--quote--', '"', $vdf_option->title);  ?></h4>
						</div>
					<?php endif; ?>
					<div class="entry-excerpt">
						<?php echo apply_filters('ts_the_content', str_replace('--quote--', '"', $vdf_option->text)); ?>
					</div>
					<?php if( !empty($vdf_option->url) ) : ?>
					<div class="readmore">
						<a class="btn btn-sm" href="<?php echo str_replace('--quote--', '"', $vdf_option->url); ?>" >
							<span><?php esc_html_e('details', 'videofly'); ?></span>
						</a>
					</div>
					<?php endif; ?>
				</figcaption>
			</figure>
		</div>
<?php
	} 
}
?>