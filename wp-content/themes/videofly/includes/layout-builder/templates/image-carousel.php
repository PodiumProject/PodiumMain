<?php

/* Blockquote template below */
###########

// Get the options

global $article_options;

$images = $article_options['images'];
$images = explode(',', $images);

$slides = '';

if( ! isset($article_options["carousel-height"]) ){
	$article_options["carousel-height"] = 400;
}

if( (int)$article_options["carousel-height"] == 0 ){
	$article_options["carousel-height"] = 400;
}

foreach ($images as $k => $image) {
	$image_id = explode(':', $image, 2);

	if( isset($image_id[1] ) ){
		$img_url = vdf_resize('carousel', $image_id[1]);
		if( $image_id[0] !== '' ){
			$slides .= '<li><img alt="Carousel Image" src="'. $img_url .'" /></li>';
		}
	}
}
$slider_id = 'image-carousel-id-' . rand(321,32321321);

?>

<?php if( !isset($article_options['shortcode-div-large']) ) echo '<div class="col-lg-12">'; ?>
	<div class="slyframe image-carousel" data-animation="slide" id="<?php echo vdf_var_sanitize($slider_id, 'esc_attr');?>" data-height="<?php echo (int)$article_options["carousel-height"]; ?>px" style="height: <?php echo (int)$article_options["carousel-height"]; ?>px">
		<ul class="slidee">
			<?php echo vdf_var_sanitize($slides); ?>
		</ul>
	</div>
	<div class="slyscrollbar">
	    <div class="handle"></div>
	</div>
<?php if( !isset($article_options['shortcode-div-large']) ) echo '</div>'; ?>