<?php

/**
* 
*/
class Fields
{
	
	function __construct()
	{
		# code...
	}

	// Function for creating a logic meta box option
	public static function logicMetaRadio($parentName, $name, $defaultValue = 'no', $title, $description = ''){

		$meta_value = get_post_meta(get_the_ID(), $parentName, true);
		$parentName = htmlspecialchars($parentName);
		$name = htmlspecialchars($name);
		$defaultValue = htmlspecialchars($defaultValue);
		$title = htmlspecialchars($title);
		$description = htmlspecialchars($description);

		if( !isset($meta_value[$name]) || $meta_value[$name] == '' ){
			$element_value = $defaultValue;
		}else{
			$element_value = $meta_value[$name];
		}
		?>
		<div class="meta-box-option meta_title_<?php echo vdf_var_sanitize($name);?>">
			<h4 class="meta-box-option-title"><?php echo vdf_var_sanitize($title); ?></h4>
			<div class="meta-box-option-input">
				<label for="<?php echo vdf_var_sanitize($parentName); ?>['<?php echo vdf_var_sanitize($name); ?>']"></label>
				<input type="radio" name="<?php echo vdf_var_sanitize($parentName); ?>[<?php echo vdf_var_sanitize($name); ?>]" value="yes" <?php checked( $element_value, 'yes', true ); ?> id="<?php echo vdf_var_sanitize($parentName.'-'.$name.'-yes'); ?>" /> <label class="ts-logic-label" for="<?php echo vdf_var_sanitize($parentName.'-'.$name.'-yes'); ?>"><?php esc_html_e('Yes', 'videofly'); ?></label>
				<input type="radio" name="<?php echo vdf_var_sanitize($parentName); ?>[<?php echo vdf_var_sanitize($name); ?>]" value="no" <?php checked( $element_value, 'no', true ); ?> id="<?php echo vdf_var_sanitize($parentName.'-'.$name.'-no'); ?>" /> <label class="ts-logic-label" for="<?php echo vdf_var_sanitize($parentName.'-'.$name.'-no'); ?>"><?php esc_html_e('No', 'videofly'); ?></label>
			</div>
			<div class="meta-box-option-desc">
				<?php echo vdf_var_sanitize($description); ?>
			</div>
		</div>
		<?php
	}

	// Function for creating an upload input for meta box option

	public static function uploaderMeta($parentName, $name, $defaultValue = '', $title, $description = ''){

		$parentName = htmlspecialchars($parentName);
		$name = htmlspecialchars($name);
		$defaultValue = htmlspecialchars($defaultValue);
		$title = htmlspecialchars($title);
		$description = htmlspecialchars($description);

		$meta_value = get_post_meta(get_the_ID(), $parentName, true);
		$element_value = (isset($meta_value[$name])) ? htmlspecialchars($meta_value[$name]) : '';
		?>
		<div class="meta-box-option meta_title_<?php echo vdf_var_sanitize($name);?>">
			<h4 class="meta-box-option-title"><?php echo vdf_var_sanitize($title); ?></h4>
			<div class="meta-box-option-input">
				<label for="<?php echo vdf_var_sanitize($parentName); ?>['<?php echo vdf_var_sanitize($name); ?>']"></label>
				<input type="text" name="<?php echo vdf_var_sanitize($parentName); ?>[<?php echo vdf_var_sanitize($name); ?>][url]" id="<?php echo vdf_var_sanitize($parentName); ?>-<?php echo vdf_var_sanitize($name); ?>-input-field" class="<?php echo vdf_var_sanitize($parentName); ?>[<?php echo vdf_var_sanitize($name); ?>]" value="<?php echo @$element_value['url'];?>"/>
				<input type="hidden" name="<?php echo vdf_var_sanitize($parentName); ?>[<?php echo vdf_var_sanitize($name); ?>][media_id]" id="<?php echo vdf_var_sanitize($parentName); ?>-<?php echo vdf_var_sanitize($name); ?>-media-id" value="<?php echo @$element_value['media_id'];?>"/>
				<input type="button" data-element-id="<?php echo vdf_var_sanitize($parentName); ?>-<?php echo vdf_var_sanitize($name); ?>" name="<?php echo vdf_var_sanitize($parentName); ?>[<?php echo vdf_var_sanitize($name); ?>]-submit" id="<?php echo vdf_var_sanitize($parentName); ?>[<?php echo vdf_var_sanitize($name); ?>]-upload" class="button-primary uploader-meta-field <?php echo vdf_var_sanitize($parentName); ?>[<?php echo vdf_var_sanitize($name); ?>]-uploade-button" value="<?php esc_html_e( 'Upload', 'videofly' ); ?>" />
			</div>
			<div class="meta-box-option-desc">
				<?php echo vdf_var_sanitize($description); ?>
			</div>
		</div>
		<?php
	}

	public static function inputText($parentName, $name, $defaultValue = '', $title, $description = ''){

		$parentName = htmlspecialchars($parentName);
		$name = htmlspecialchars($name);
		$defaultValue = htmlspecialchars($defaultValue);
		$title = htmlspecialchars($title);
		$description = htmlspecialchars($description);

		$meta_value = get_post_meta(get_the_ID(), $parentName, true);
		$element_value = (isset($meta_value[$name])) ? htmlspecialchars($meta_value[$name]) : '';
		?>
		<div class="meta-box-option meta_title_<?php echo vdf_var_sanitize($name);?>">
			<h4 class="meta-box-option-title"><?php echo vdf_var_sanitize($title); ?></h4>
			<div class="meta-box-option-input">
				<label for="<?php echo vdf_var_sanitize($parentName); ?>['<?php echo vdf_var_sanitize($name); ?>']"></label>
				<input type="text" name="<?php echo vdf_var_sanitize($parentName); ?>[<?php echo vdf_var_sanitize($name); ?>]" id="<?php echo vdf_var_sanitize($parentName); ?>-<?php echo vdf_var_sanitize($name); ?>-input-field" class="<?php echo vdf_var_sanitize($parentName); ?>[<?php echo vdf_var_sanitize($name); ?>]" value="<?php echo @$element_value; ?>"/>
			</div>
			<div class="meta-box-option-desc">
				<?php echo vdf_var_sanitize($description); ?>
			</div>
		</div>
		<?php
	}

	public static function textareaText($parentName, $name, $defaultValue = '', $title, $description = ''){

		$parentName = htmlspecialchars($parentName);
		$name = htmlspecialchars($name);
		$defaultValue = htmlspecialchars($defaultValue);
		$title = htmlspecialchars($title);
		$description = htmlspecialchars($description);

		$meta_value = get_post_meta(get_the_ID(), $parentName, true);
		$element_value = (isset($meta_value[$name])) ? htmlspecialchars($meta_value[$name]) : '';
		?>
		<div class="meta-box-option meta_title_<?php echo vdf_var_sanitize($name);?>">
			<h4 class="meta-box-option-title"><?php echo vdf_var_sanitize($title); ?></h4>
			<div class="meta-box-option-input">
				<label for="<?php echo vdf_var_sanitize($parentName); ?>['<?php echo vdf_var_sanitize($name); ?>']"></label>
				<textarea cols="35" name="<?php echo vdf_var_sanitize($parentName); ?>[<?php echo vdf_var_sanitize($name); ?>]" id="<?php echo vdf_var_sanitize($parentName); ?>-<?php echo vdf_var_sanitize($name); ?>-input-field" class="<?php echo vdf_var_sanitize($parentName); ?>[<?php echo vdf_var_sanitize($name); ?>]"><?php echo @$element_value; ?></textarea>
			</div>
			<div class="meta-box-option-desc">
				<?php echo vdf_var_sanitize($description); ?>
			</div>
		</div>
		<?php
	}


	// Function for creating an radio images inputs

	public static function radioImageMeta($parentName, $name, $values, $perRow, $defaultValue = '', $title, $description = ''){

		$parentName = htmlspecialchars($parentName);
		$name = htmlspecialchars($name);
		$defaultValue = htmlspecialchars($defaultValue);
		$title = htmlspecialchars($title);
		$description = htmlspecialchars($description);

		$meta_value = get_post_meta(get_the_ID(), $parentName, true);

		if( isset($meta_value[$name]) && $meta_value[$name] == '' ){
			$the_meta_value = $parentName . '[' . $name . ']';
			add_post_meta(get_the_ID(), $the_meta_value, $defaultValue);

			$element_value = $defaultValue;

		}else{
			$element_value = (isset($meta_value[$name])) ? htmlspecialchars($meta_value[$name]) : $defaultValue;
		}
		?>
		<div class="meta-box-option meta_title_<?php echo vdf_var_sanitize($name);?>">
			<h4 class="meta-box-option-title"><?php echo vdf_var_sanitize($title); ?></h4>
			<div class="meta-box-option-input">
				<ul class="imageRadioMetaUl perRow-<?php echo vdf_var_sanitize($perRow); ?>">
					<?php foreach ($values as $key => $value): ?>
						<li>
							<img src="<?php echo vdf_var_sanitize($value); ?>" alt="<?php echo vdf_var_sanitize($key); ?>" class="image-radio-input <?php if($element_value == $key ){ echo ' selected' ;} ?>" data-value="<?php echo vdf_var_sanitize($key); ?>" />
							<input type="radio" data-value="<?php echo vdf_var_sanitize($key); ?>" class="hidden-input" name="<?php echo vdf_var_sanitize($parentName); ?>[<?php echo vdf_var_sanitize($name); ?>]" value="<?php echo vdf_var_sanitize($key); ?>" <?php checked( $element_value, $key, true ); ?> />
						</li>
					<?php endforeach ?>
				</ul>
			</div>
			<div class="meta-box-option-desc">
				<?php echo vdf_var_sanitize($description); ?>
			</div>
		</div>
		<?php
	}
	// Function for creating selects

	public static function selectMeta($parentName, $name, $values, $defaultValue = '', $title, $description = ''){

		$parentName = htmlspecialchars($parentName);
		$name = htmlspecialchars($name);
		$defaultValue = htmlspecialchars($defaultValue);
		$title = htmlspecialchars($title);
		$description = htmlspecialchars($description);

		$meta_value = get_post_meta(get_the_ID(), $parentName, true);
		
		if( isset($meta_value[$name]) && $meta_value[$name] == '' ){
			$the_meta_value = $parentName . '[' . $name . ']';

			add_post_meta(get_the_ID(), $the_meta_value, $defaultValue);
			$element_value = $defaultValue;
		}else{
			$element_value = (isset($meta_value[$name])) ? htmlspecialchars($meta_value[$name]) : $defaultValue;
		}
		
		?>
		<div class="meta-box-option meta_title_<?php echo vdf_var_sanitize($name);?>">
			<h4 class="meta-box-option-title"><?php echo vdf_var_sanitize($title); ?></h4>
			<div class="meta-box-option-input">
				<select name="<?php echo vdf_var_sanitize($parentName); ?>[<?php echo vdf_var_sanitize($name); ?>]" id="">
					<?php foreach ($values as $key => $value): ?>
						<option value="<?php echo vdf_var_sanitize($value); ?>" <?php selected( $element_value, $value, true ); ?>><?php echo vdf_var_sanitize($key); ?></option>
					<?php endforeach ?>
				</select>
			</div>
			<div class="meta-box-option-desc">
				<?php echo vdf_var_sanitize($description); ?>
			</div>
		</div>
		<?php
	}

	// ############### Functions to retrive data from saved meta  ############### //

	public static function logic($post_id, $field, $option){
		$meta_value = get_post_meta($post_id, $field, true);
		// Check the option and return true of false depending on the option
		if ( $meta_value == '' ) {
			$meta_value[$option] = '';
		}
		if( @$meta_value[$option] == '' ){
			$meta_defaults = get_option( $field . '_defaults' );
			$meta_value[$option] = $meta_defaults[$option];
		}
		if ( @$meta_value[$option] == 'yes' ) {
			return true;
		} else{
			return false;
		}
	}
	public static function get_value($post_id, $field, $option, $return = true){
		$meta_value = get_post_meta($post_id, $field, true);
		
		// Check the option and return true of false depending on the option
		if ( $meta_value == '' ) {
			$meta_value[$option] = '';
		}
		if( !isset($meta_value[$option]) || $meta_value[$option] == '' ){
			$meta_defaults = get_option( $field . '_defaults' );
			$meta_value[$option] = $meta_defaults[$option];
		}
		if( $return == true){
			return @$meta_value[$option];
		}else{
			echo @$meta_value[$option];
		}
	}
	public static function get_default($post_id, $field, $option, $return = true){
		$meta_value = get_post_meta($post_id, $field, true);
		// Check the option and return true of false depending on the option
		if( @$meta_value[$option] == '' ){
			$meta_defaults = get_option( $field . '_defaults' );
			$meta_value[$option] = $meta_defaults[$option];
		}
		if( $return == true){
			return @$meta_value[$option];
		}else{
			echo @$meta_value[$option];
		}
	}


	// ############# -- Retrieve functions from options -- ############

	public static function get_options_value($field, $option, $return = true){
		$value = get_option($field);

		// Check the option and return true of false depending on the option
		@$value = @$value[$option];

		if( $return == true){
			return @$value;
		}else{
			echo @$value;
		}
	}

}
?>