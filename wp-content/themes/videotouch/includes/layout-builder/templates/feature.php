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
	
	foreach($arr as $ts_option){
		
		// Get the number of features per row

		$columns_class = LayoutCompilator::get_column_class($elements_per_row);
		if ( $style == 'style2' ) {
			$column_options = "background-color: " . str_replace('--quote--', '"', $ts_option->background). "; color:" . str_replace('--quote--', '"', $ts_option->font) . "";
		}else{
			$column_options = "";
		}

		// Add article specific classes
	?>
		<div class="<?php echo $columns_class; ?>">
			<article style="<?php echo $column_options; ?>">
				<header>
					<div class="article-header-content">
						<div class="image-container" <?php if ( $style == 'style1' ) { echo 'style="background-color:'. str_replace('--quote--', '"', $ts_option->background). "; color:" . str_replace('--quote--', '"', $ts_option->font) .'"'; } ?>>
							<i class="<?php echo str_replace('--quote--', '"', $ts_option->icon); ?>"></i>
						</div>
						<div class="article-title">
							<h4 class="title"><?php echo str_replace('--quote--', '"', esc_attr($ts_option->title));  ?></h4>
						</div>
					</div>
				</header>
				<section>
					<div class="article-excerpt">
						<div class="feature-text">
							<?php echo apply_filters('the_content', str_replace('--quote--', '"', $ts_option->text)); ?>
						</div>
					</div>
					<?php if( !$ts_option->url == '' && $style == 'style1' ) : ?>
						<div class="readmore">
							<a href="<?php echo str_replace('--quote--', '"', $ts_option->url); ?>">
								<span><?php _e('Read more', 'touchsize'); ?></span>
								<i class="icon-right"></i>
							</a>
						</div>
					<?php endif; ?>
				</section>
				<footer>
					<?php if( !$ts_option->url == '' && $style == 'style2' ) : ?>
						<div class="readmore">
							<a href="<?php echo str_replace('--quote--', '"', $ts_option->url); ?>">
							<i class="icon-tick"></i>
								<span><?php _e('Read more', 'touchsize'); ?></span>
							</a>
						</div>
					<?php endif; ?>
				</footer>
			</article>
		</div>
<?php
	} 
}
?>