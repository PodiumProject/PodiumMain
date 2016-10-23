<?php 

global $article_options;
$title = $article_options['title'];
$img_src = $article_options['image'];
$align = $article_options['align'];
$button_url = $article_options['button-url'];
$button_target = $article_options['button-target'];
$button_text = $article_options['button-text'];
$content = $article_options['content'];
$column_class = ( $align === 'left' || $align === 'right' ) ? 'col-lg-6 col-md-6' : 'col-lg-12 col-md-12';
 ?>

<div class="ts-instance-container <?php echo vdf_var_sanitize($align); ?>">
	<?php if( $align != 'center' ): ?>
		<header class="left <?php echo vdf_var_sanitize($column_class); ?>">
			<div class="image-holder">
			<?php if( !empty($img_src) ): ?>
				<img src="<?php echo vdf_var_sanitize($img_src); ?>" alt="<?php echo vdf_var_sanitize($title); ?>"/>
			<?php endif; ?>
			</div>
		</header> 
	<?php endif; ?>
	<div class="container">
	<?php if( $align === 'center' ): ?>
			<header class="left <?php echo vdf_var_sanitize($column_class); ?>">
				<div class="image-holder">
				<?php if( !empty($img_src) ): ?>
					<img src="<?php echo vdf_var_sanitize($img_src); ?>" alt="<?php echo vdf_var_sanitize($title); ?>"/>
				<?php endif; ?>
				</div>
			</header> 
	<?php endif; ?>
			<article class="ts-instance row <?php echo vdf_var_sanitize($align); ?>" >
				<?php if( $align === 'left' ): ?>
					<div class="col-lg-6"></div>
				<?php endif; ?>
				<section class="<?php echo vdf_var_sanitize($column_class); ?>">
				<div>
					<?php if( !empty($title) ): ?>
						<h3 class="entry-title"><?php echo vdf_var_sanitize($title); ?></h3>
					<?php endif; ?>
					<?php  if( !empty($content) ): ?>
						<div class="entry-content">
							<?php echo vdf_var_sanitize($content); ?>
						</div>
					<?php endif; ?>
					<?php if( !empty($button_url) ): ?>
						<a href="<?php echo vdf_var_sanitize($button_url); ?>" class="instance-read-more" target="<?php echo vdf_var_sanitize($button_target); ?>">
							<span class="icon-play"></span>
							<?php echo vdf_var_sanitize($button_text); ?>
						</a>
					<?php endif; ?>
					</div>
				</section>
			</article>
	</div>
</div>