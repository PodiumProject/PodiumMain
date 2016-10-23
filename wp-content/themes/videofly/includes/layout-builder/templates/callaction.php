<?php

/* Call to action template below */
###########

// Get the options

global $article_options;

$callaction_text = (trim($article_options['callaction-text']) !== '') ? $article_options['callaction-text'] : false;
$callaction_button_text = (trim($article_options['callaction-button-text']) !== '') ?
						$article_options['callaction-button-text'] : esc_html__( 'Read more', 'videofly' );
$callaction_link = (trim($article_options['callaction-link']) !== '') ? $article_options['callaction-link'] : false;

if ( ! $callaction_text ) {
	return '';
} else {
	$callaction_button = '';

	if ($callaction_link) {
		$callaction_button = '<div class="col-lg-3">
			<a href="'.esc_url($callaction_link).'" class="continue">'.stripslashes($callaction_button_text).'</a>
		</div>';
	}
}

?>
<div class="col-lg-12 col-md-12 col-sm-12">
	<div class="callactionr">
	<div class="row">
			<div class="col-lg-9">
				<div class="the-quote"><?php echo stripslashes($callaction_text); ?></div>
			</div>
			<?php echo stripslashes($callaction_button); ?>
		</div>
	</div>
</div>