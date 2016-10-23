<?php

/* Contact form template below */
###########

// Get the options

global $article_options;
$contact_form_options = (isset($article_options['contact-form']) && !empty($article_options['contact-form']) && $article_options['contact-form'] != '[]') ? json_decode(stripslashes($article_options['contact-form'])) : NULL;

?>
<div class="col-lg-12">
	<form method="post" class="contact-form">
		<div class="row">
			<?php if ( $article_options['hide-icon'] != 1): ?>
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="contact-form-icon">
					<i class="icon-mail"></i>
				</div>
			</div>
			<?php endif ?>
			<div class="col-lg-6 col-md-12 col-sm-12">
				<label><?php esc_html_e( 'Name', 'videofly' ); ?></label>
				<input type="text" name="contact-form-name" class="contact-form-name">
			</div>
			<div class="col-lg-6 col-md-12 col-sm-12">
				<label><?php esc_html_e( 'Email', 'videofly' ); ?></label>
				<input type="text" name="contact-form-email" class="contact-form-email">
			</div>
			<?php if ( $article_options['hide-subject'] != 1): ?>
				<div class="col-lg-12 col-md-12 col-sm-12">
					<label><?php esc_html_e( 'Subject', 'videofly' ); ?></label>
					<input type="text" name="contact-form-subject" class="contact-form-subject">
				</div>
			<?php endif ?>
			<div class="col-lg-12 col-md-12 col-sm-12">
				<label><?php esc_html_e( 'Text', 'videofly' ); ?></label>
				<textarea name="contact-form-text" class="contact-form-text"></textarea>
			</div>
			
			<?php 	if( isset($contact_form_options) && is_array($contact_form_options) && !empty($contact_form_options) ) : 
				 		foreach( $contact_form_options as $form_option ) : 
					 		if( is_object($form_option) ) :
					 			$title = (isset($form_option->title)) ? esc_attr($form_option->title) : '';
					 			$require = (isset($form_option->require) && !empty($form_option->require)) ? $form_option->require : 'n';
						 		if( isset($form_option->type) && $form_option->type == 'select' ) :
						 			if( isset($form_option->options) && !empty($form_option->options) ) :
						 				$options_select = explode('/', $form_option->options);
						 				$html_option = '';
						 				foreach($options_select as $option){
						 					$html_option .= '<option value="'. $option .'">'. $option . '</option>'; 
						 				}?>
						 				<div class="col-lg-6 col-md-12 col-sm-12">
						 					<label><?php echo vdf_var_sanitize($title); ?></label>
						 					<select class="ts_contact_custom_field form-control <?php if( $require == 'y' ) echo 'contact-form-require' ?>" name="custom_fields[select]" style="margin-bottom:20px">
						 						<?php echo vdf_var_sanitize($html_option); ?>
						 					</select>
						 					<input value="<?php echo vdf_var_sanitize($title); ?>" type="hidden" name="custom_fields[title_select]">
						 					<input value="<?php echo vdf_var_sanitize($require); ?>" type="hidden" name="custom_fields[require]">
						 				</div>
			<?php                   endif;
								endif;
								if( isset($form_option->type) && $form_option->type == 'input' ) :?>
									<div class="col-lg-6 col-md-12 col-sm-12">
										<label><?php echo vdf_var_sanitize($title); ?></label>
										<input type="text" name="custom_fields[content]"  class="ts_contact_custom_field <?php if( $require == 'y' ) echo 'contact-form-require'; ?>">
										<input value="<?php echo vdf_var_sanitize($title); ?>" type="hidden" name="custom_fields[title_input]">
										<input value="<?php echo vdf_var_sanitize($require); ?>" type="hidden" name="custom_fields[require]">
									</div>
			<?php				endif;
								if( isset($form_option->type) && $form_option->type == 'textarea' ) : ?>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<label><?php echo vdf_var_sanitize($title); ?></label>
										<textarea name="custom_fields[<?php echo vdf_var_sanitize($title); ?>]" class="ts_contact_custom_field <?php if( $require == 'y' ) echo 'contact-form-require' ?>"></textarea>
										<input value="<?php echo vdf_var_sanitize($title); ?>" type="hidden" name="custom_fields[title_textarea]">
										<input value="<?php echo vdf_var_sanitize($require); ?>" type="hidden" name="custom_fields[require]">
									</div>
			<?php				endif;
							endif;//end if isset form_option
						endforeach;
					endif; ?>
			<div class="col-lg-12">
				<?php vdf_captcha() ?>
				<input type="button" value="<?php esc_html_e( 'Send', 'videofly' ); ?>" class="contact-form-submit">
			</div>
		</div>
		<div class="contact-form-messages hidden"></div>
	</form>
</div>