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

		if( @$meta_value[$name] == '' || !isset( $meta_value[$name] ) ){
			$element_value = $defaultValue;
		}else{
			$element_value = $meta_value[$name];
		}
		?>
		<div class="meta-box-option meta_title_<?php echo $name;?>">
			<h4 class="meta-box-option-title"><?php echo $title; ?></h4>
			<div class="meta-box-option-input">
				<label for="<?php echo $parentName; ?>['<?php echo $name; ?>']"></label>
				<input type="radio" name="<?php echo $parentName; ?>[<?php echo $name; ?>]" value="yes" <?php checked( $element_value, 'yes', true ); ?> id="<?php echo $parentName.'-'.$name.'-yes'; ?>" /> <label class="ts-logic-label" for="<?php echo $parentName.'-'.$name.'-yes'; ?>">Yes</label>
				<input type="radio" name="<?php echo $parentName; ?>[<?php echo $name; ?>]" value="no" <?php checked( $element_value, 'no', true ); ?> id="<?php echo $parentName.'-'.$name.'-no'; ?>" /> <label class="ts-logic-label" for="<?php echo $parentName.'-'.$name.'-no'; ?>">No</label>
			</div>
			<div class="meta-box-option-desc">
				<?php echo $description; ?>
			</div>
		</div>
		<?php
	}

	// Function for creating an upload input for meta box option

	public static function uploaderMeta($parentName, $name, $defaultValue = '', $title, $description = ''){

		$meta_value = get_post_meta(get_the_ID(), $parentName, true);
		$element_value = @$meta_value[$name];
		?>
		<div class="meta-box-option meta_title_<?php echo $name;?>">
			<h4 class="meta-box-option-title"><?php echo $title; ?></h4>
			<div class="meta-box-option-input">
				<label for="<?php echo $parentName; ?>['<?php echo $name; ?>']"></label>
				<input type="text" name="<?php echo $parentName; ?>[<?php echo $name; ?>][url]" id="<?php echo $parentName; ?>-<?php echo $name; ?>-input-field" class="<?php echo $parentName; ?>[<?php echo $name; ?>]" value="<?php echo @$element_value['url'];?>"/>
				<input type="hidden" name="<?php echo $parentName; ?>[<?php echo $name; ?>][media_id]" id="<?php echo $parentName; ?>-<?php echo $name; ?>-media-id" value="<?php echo @$element_value['media_id'];?>"/>
				<input type="button" data-element-id="<?php echo $parentName; ?>-<?php echo $name; ?>" name="<?php echo $parentName; ?>[<?php echo $name; ?>]-submit" id="<?php echo $parentName; ?>[<?php echo $name; ?>]-upload" class="button-primary uploader-meta-field <?php echo $parentName; ?>[<?php echo $name; ?>]-uploade-button" value="<?php _e( 'Upload', 'touchsize' ) ?>" />
			</div>
			<div class="meta-box-option-desc">
				<?php echo $description; ?>
			</div>
		</div>
		<?php
	}


	// Function for creating an radio images inputs

	public static function radioImageMeta($parentName, $name, $values, $perRow, $defaultValue = '', $title, $description = ''){

		$meta_value = get_post_meta(get_the_ID(), $parentName, true);

		if( @$meta_value[$name] == '' ){
			$the_meta_value = $parentName . '[' . $name . ']';
			add_post_meta(get_the_ID(), $the_meta_value, $defaultValue);

			$element_value = $defaultValue;

		}else{
			$element_value = $meta_value[$name];
		}
		?>
		<div class="meta-box-option meta_title_<?php echo $name;?>">
			<h4 class="meta-box-option-title"><?php echo $title; ?></h4>
			<div class="meta-box-option-input">
				<ul class="imageRadioMetaUl perRow-<?php echo $perRow; ?>">
					<?php foreach ($values as $key => $value): ?>
						<li>
							<img src="<?php echo $value; ?>" alt="<?php echo $key; ?>" class="image-radio-input <?php if($element_value == $key ){ echo ' selected' ;} ?>" data-value="<?php echo $key; ?>" />
							<input type="radio" data-value="<?php echo $key; ?>" class="hidden-input" name="<?php echo $parentName; ?>[<?php echo $name; ?>]" value="<?php echo $key; ?>" <?php checked( $element_value, $key, true ); ?> />
						</li>
					<?php endforeach ?>
				</ul>
			</div>
			<div class="meta-box-option-desc">
				<?php echo $description; ?>
			</div>
		</div>
		<?php
	}
	// Function for creating selects

	public static function selectMeta($parentName, $name, $values, $defaultValue = '', $title, $description = ''){

		$meta_value = get_post_meta(get_the_ID(), $parentName, true);

		if( @$meta_value[$name] == '' ){
			$the_meta_value = $parentName . '[' . $name . ']';

			add_post_meta(get_the_ID(), $the_meta_value, $defaultValue);
			$element_value = $defaultValue;
		}else{
			$element_value = $meta_value[$name];
		}
		?>
		<div class="meta-box-option meta_title_<?php echo $name;?>">
			<h4 class="meta-box-option-title"><?php echo $title; ?></h4>
			<div class="meta-box-option-input">
				<select name="<?php echo $parentName; ?>[<?php echo $name; ?>]" id="">
					<?php foreach ($values as $key => $value): ?>
						<option value="<?php echo $value; ?>" <?php selected( $element_value, $value, true ); ?>><?php echo $key; ?></option>
					<?php endforeach ?>
				</select>
			</div>
			<div class="meta-box-option-desc">
				<?php echo $description; ?>
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