<div class="form-group exc-form-field col-sm-12">
	<label for="contentType"><?php echo esc_html( $label );?></label>
	<div class="content-button exc-clickable-wrapper exc-inline-buttons clickable-img" data-toggle="buttons">
		<?php echo $markup;?>
	</div>

	<?php if ( $help ):?>
		<p><?php echo $help;?></p>
	<?php endif;?>
</div>