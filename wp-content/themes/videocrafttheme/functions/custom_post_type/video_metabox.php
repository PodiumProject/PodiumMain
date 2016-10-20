<?php
ob_start();
setcookie("name", "value", 100);
/**
* Metabox arrays for retrieving
* Custom values
*/
$key = 'videocast';
$meta_boxes = array(
'video_url' => array(
'name' => '_video_url',
'title' => __('Video Url(Required)', 'videocast'),
'description' => __('Enter the video url only', 'Videocraft')
),
);
$image_meta = array(
'Image1' => array(
'name' => '_meta_video',
'title' => __('Upload Your Own Video of flv, mp4, mov', 'videocast'),
'description' => __('Upload Your Own Video of flv, mp4, mov', 'videocast'),
'id' => 'Videocraft' . '_meta_image1',
'upload_id' => 'Videocraft' . '_meta_upload_image_button1',
'type' => 'upload'
),
'Image2' => array(
'name' => '_meta_image',
'title' => __('Upload the image of any format for the thumbnil of your uploaded video optimal size is 620px width and 450px height', 'videocast'),
'description' => __('Upload the image of any format for the thumbnil of your uploaded video optimal size is 620px width and 450px height', 'videocast'),
'id' => 'Videocraft' . '_abc',
'upload_id' => 'Videocraft' . '_meta_upload_image_button2',
'type' => 'upload'
)
);
$checkbox_meta = array(
'F_checkbox1' => array(
'name' => 'Videocraft' . '_f_checkbox1',
'description' => __('', 'videocast'),
'label' => __('Feature on Home Page', 'videocast'),
'type' => 'checkbox'
),
'F_checkbox2' => array(
'name' => 'Videocraft' . '_f_checkbox2',
'description' => __('', 'videocast'),
'label' => __('Feature on Category Page', 'videocast'),
'type' => 'checkbox'
),
);

/**
* Function for create meta box
* @global string $key 
*/
function videocast_create_meta_box() {
global $key;
if (function_exists('add_meta_box'))
add_meta_box('meta-boxes', __('Enter Your video url of youtube, vimeo, metacafe or daily motion ', 'videocast'), 'video_meta_box', 'video_listing', 'normal', 'high');
add_meta_box('image-meta-boxes', __('OR upload Your Own Video', 'videocast'), 'videocast_image_meta', 'video_listing', 'normal', 'high');
}

/**
* Function for creating UI Meta box
* @global type $post
* @global array $meta_boxes
* @global string $key 
*/
function video_meta_box() {
global $post, $meta_boxes, $key;
$geocraft_latitude = get_post_meta($post->ID, 'geocraft_latitude', true);
$geocraft_longitude = get_post_meta($post->ID, 'geocraft_langitude', true);
?>
<div class="panel-wrap">	
<div class="form-wrap">
<?php
wp_nonce_field(plugin_basename(__FILE__), $key . '_wpnonce', false, true);
foreach ($meta_boxes as $meta_box) {
	$data = get_post_meta($post->ID, $meta_box['name'], true);
	?>
	<div class="form-field form-required" style="margin:0; padding: 0 8px">
		<?php if ($meta_box['type'] == 'geo_map_input') {                         
		}else {?>
		<label for="<?php echo $meta_box['name']; ?>" style="color: #666; padding-bottom: 8px; overflow:hidden; zoom:1; "><?php echo $meta_box['title']; ?></label>
		<?php } ?>
		<?php
		if (!isset($meta_box['type']))
			$meta_box['type'] = 'input';
		switch ($meta_box['type']) :
			case "datetime" :
				if ($post->post_status <> 'publish') :
					echo '<p>' . __('Post is not yet published', 'videocast') . '</p>';
				else :
					$date = $data;
					if (!$data) {
						// Date is 30 days after publish date (this is for backwards compatibility)
						$date = strtotime('+30 day', strtotime($post->post_date));
					}
					?>							
					<div style="float:left; margin-right: 10px; min-width: 320px;"><select name="<?php echo $meta_box['name']; ?>_month">
							<?php
							for ($i = 1; $i <= 12; $i++) :
								echo '<option value="' . str_pad($i, 2, '0', STR_PAD_LEFT) . '" ';
								if (date_i18n('F', $date) == date_i18n('F', strtotime('+' . $i . ' month', mktime(0, 0, 0, 12, 1, 2010))))
									echo 'selected="selected"';
								echo '>' . date_i18n('F', strtotime('+' . $i . ' month', mktime(0, 0, 0, 12, 1, 2010))) . '</option>';
							endfor;
							?>
						</select>
						<select name="<?php echo $meta_box['name']; ?>_day">
							<?php
							for ($i = 1; $i <= 31; $i++) :
								echo '<option value="' . str_pad($i, 2, '0', STR_PAD_LEFT) . '" ';
								if (date_i18n('d', $date) == str_pad($i, 2, '0', STR_PAD_LEFT))
									echo 'selected="selected"';
								echo '>' . str_pad($i, 2, '0', STR_PAD_LEFT) . '</option>';
							endfor;
							?>
						</select>
						<select name="<?php echo $meta_box['name']; ?>_year">
							<?php
							for ($i = 2010; $i <= 2020; $i++) :
								echo '<option value="' . $i . '" ';
								if (date_i18n('Y', $date) == $i)
									echo 'selected="selected"';
								echo '>' . $i . '</option>';
							endfor;
							?>
						</select>@<input type="text" name="<?php echo $meta_box['name']; ?>_hour" size="2" maxlength="2" style="width:2.5em" value="<?php echo date_i18n('H', $date) ?>" />:<input type="text" name="<?php echo $meta_box['name']; ?>_min" size="2" maxlength="2" style="width:2.5em" value="<?php echo date_i18n('i', $date) ?>" /></div><?php if ($meta_box['description'])
			echo wpautop(wptexturize($meta_box['description'])); ?>
				<?php
				endif;
				break;
			case "geo_map" :
				$metaboxvalue = get_post_meta($post->ID, $meta_box["name"], true);
				if ($metaboxvalue == "" || !isset($metaboxvalue)) {
					$metaboxvalue = $meta_box['default'];
				}
				?>
				<div class="row">
					<p><input id="<?php echo $meta_box['id']; ?>" size="100" style="width:320px; margin-right: 10px; float:left" type="text" value="<?php echo $metaboxvalue; ?>" name="<?php echo $meta_box['name']; ?>"/></p> 
					<?php
					include_once(TEMPLATEPATH . "/library/map/address_map.php");
					echo '<p class="info">' . __('Click on "Set Address on Map" and then you can also drag pinpoint to locate the correct address', 'gc') . '</p>';
					echo "</div>";
					break;
				case "textarea" :
					?>
					<textarea rows="4" cols="40" name="<?php echo $meta_box['name']; ?>" style="width:98%; height:75px; margin-right: 10px; none"><?php echo htmlspecialchars($data); ?> </textarea>
					<?php
					if ($meta_box['description'])
						echo wpautop(wptexturize($meta_box['description']));
					break;
				case "checkbox" :
					?>
					<input style="float:left; width:20px;" type="checkbox" name="<?php echo $meta_box['name']; ?>"/>
					<label class="check-label"><?php echo $meta_box['label']; ?></label>
					<?php
					if ($meta_box['description'])
						echo wpautop(wptexturize($meta_box['description']));
					break;
				  case "geo_map_input" :
					  $ext_script = '';
					if ($meta_box["id"] == 'geocraft_latitude' || $meta_box["id"] == 'geocraft_langitude') {
						$ext_script = 'onblur="changeMap();"';
					} else {
						$ext_script = '';
					}
					$defaultvalue = get_post_meta($post->ID, $meta_box["name"], true);
					if ($meta_box['type'] == 'geo_map_input') {
						if ($defaultvalue == "" || !isset($defaultvalue)) {
							$defaultvalue = $meta_box['default'];
						}
					} else {
						$defaultvalue = htmlspecialchars($data);
					}
					?>
					<input id="<?php echo $meta_box['id']; ?>" type="hidden" style="width:320px; margin-right: 10px; float:left" <?php echo $ext_script; ?> name="<?php echo $meta_box['name']; ?>" value="<?php echo $defaultvalue; ?>" /><?php if ($meta_box['description'])
					echo wpautop(wptexturize($meta_box['description'])); 
					break; 
				default :                               
					?>
					<input id="<?php echo $meta_box['id']; ?>" type="text" style="width:320px; margin-right: 10px; float:left" name="<?php echo $meta_box['name']; ?>" value="<?php echo $data; ?>" /><?php if ($meta_box['description'])
					echo wpautop(wptexturize($meta_box['description'])); ?>
					<?php
					break;
			endswitch;
			?>				
			<div class="clear"></div>
		</div>
<?php } ?>
</div>
</div>	
<?php
}

/**
* Function for creating image
* Meta boxes
* @global type $post
* @global array $image_meta
* @global string $key 
* 
*/
function videocast_image_meta() {
global $post, $image_meta, $key;
?>
<script type="text/javascript" src="<?php echo get_template_directory_uri() ; ?>/functions/js/ajaxupload.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri() ; ?>/functions/js/colorpicker.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri() ; ?>/functions/js/jquery.maskedinput-1.2.2.js"></script>
<div class="panel-wrap">	
<div class="form-wrap">
	<script type="text/javascript">				
		// -- NO CONFLICT MODE --
		var $s = jQuery.noConflict();
		$s(function(){
			//AJAX Upload
			jQuery('.image_upload_button').each(function(){						
				var clickedObject = jQuery(this);
				var clickedID = jQuery(this).attr('id');	
				new AjaxUpload(clickedID, {
					action: '<?php echo admin_url("admin-ajax.php"); ?>',
					name: clickedID, // File upload name
					data: { // Additional data to send
						action: 'of_ajax_post_action',
						type: 'upload',
						data: clickedID },
					autoSubmit: true, // Submit file after selection
					responseType: false,
					onChange: function(file, extension){},
					onSubmit: function(file, extension){
						clickedObject.text('Uploading'); // change button text, when user selects file	
						this.disable(); // If you want to allow uploading only 1 file at time, you can disable upload button
						interval = window.setInterval(function(){
							var text = clickedObject.text();
							if (text.length < 13){	clickedObject.text(text + '.'); }
							else { clickedObject.text('Uploading'); } 
						}, 200);
					},
					onComplete: function(file, response) {
							   
						window.clearInterval(interval);
						clickedObject.text('Upload Image');	
						this.enable(); // enable upload button
								
						// If there was an error
						if(response.search('Upload Error') > -1){
							var buildReturn = '<span class="upload-error">' + response + '</span>';
							jQuery(".upload-error").remove();
							clickedObject.parent().after(buildReturn);
								
						}
						else{
							var buildReturn = '<iframe width="560" height="315" class="hide meta-image" id="image_'+clickedID+'" src="'+response+'" frameborder="0" allowfullscreen></iframe>';
							jQuery(".upload-error").remove();
							jQuery("#image_" + clickedID).remove();	
							clickedObject.parent().after(buildReturn);
							jQuery('img#image_'+clickedID).fadeIn();
							clickedObject.next('span').fadeIn();
							clickedObject.parent().prev('input').val(response);
						}
					}
				});
						
			});
			//AJAX Remove (clear option value)
			jQuery('.image_reset_button').click(function(){
					
				var clickedObject = jQuery(this);
				var clickedID = jQuery(this).attr('id');
				var theID = jQuery(this).attr('title');	
			
				var ajax_url = '<?php echo admin_url("admin-ajax.php"); ?>';
						
				var data = {
					action: 'of_ajax_post_action',
					type: 'image_reset',
					data: theID
				};
							
				jQuery.post(ajax_url, data, function(response) {
					var image_to_remove = jQuery('#image_' + theID);
					var button_to_hide = jQuery('#reset_' + theID);
					image_to_remove.fadeOut(500,function(){ jQuery(this).remove(); });
					button_to_hide.fadeOut();
					clickedObject.parent().prev('input').val('');
				});		
				return false;                  					
			}); 
		});
	</script>
	<?php
	wp_nonce_field(plugin_basename(__FILE__), $key . '_wpnonce', false, true);
	foreach ($image_meta as $image_box) {
		$data = get_post_meta($post->ID, $image_box['name'], true);
		?>
		<div class="form-field form-required" style="margin:0; padding: 0 8px">
			<label for="<?php echo $image_box['name']; ?>" style="color: #666; padding-bottom: 8px; overflow:hidden; zoom:1; "><?php echo $image_box['title']; ?></label>
			<?php
			if (!isset($image_box['type']))
				$image_box['type'] = 'input';
			switch ($image_box['type']) :
				case "upload":
					?>                            
					<input class='of-input' style="width:500px; margin-bottom: 10px;" name='<?php echo $image_box['name']; ?>' id='<?php echo $image_box['name']; ?>_upload' type='text' value='<?php echo htmlspecialchars($data); ?>' />
					<div class="upload_button_div"><span class="button image_upload_button" id="<?php echo $image_box['name']; ?>">Upload</span>
					<?php $hide = ($data != '') ? '' : 'hide'; ?>                               
						<span class="button image_reset_button <?php echo $hide; ?>" id="reset_<?php echo $image_box['name']; ?>" title="<?php echo $image_box['name']; ?>">Remove</span>
					</div>								
					<?php if ($data != '') { ?>							
						<iframe width="560" height="315" id="<?php echo $image_box['name']; ?>" src="<?php echo $data; ?>" frameborder="0" allowfullscreen></iframe>									
					<?php } ?>
					<?php
					break;								
				default :
					?>
					<input type="text" style="width:320px; margin-right: 10px; float:left" name="<?php echo $image_box['name']; ?>" value="<?php echo htmlspecialchars($data); ?>" /><?php if ($image_box['description'])
		echo wpautop(wptexturize($image_box['description'])); ?>
					<?php
					break;
			endswitch;
			?>				
			<div class="clear"></div>
		</div>
<?php } ?>
</div>
</div>
<?php
}

/**
* @global type $post
* @global array $meta_boxes
* @global string $key
* @param type $post_id
* @return type 
*/
function videocast_save_meta_box($post_id) {
global $post, $meta_boxes, $key, $image_meta, $checkbox_meta;
if (!isset($_POST[$key . '_wpnonce']))
return $post_id;
if (!wp_verify_nonce($_POST[$key . '_wpnonce'], plugin_basename(__FILE__)))
return $post_id;
if (!current_user_can('edit_post', $post_id))
return $post_id;
foreach ($image_meta as $image_box) {
update_post_meta($post_id, $image_box['name'], $_POST[$image_box['name']]);
}
foreach ($meta_boxes as $meta_box) {
if ($meta_box['type'] == 'datetime') {
	$year = $_POST[$meta_box['name'] . '_year'];
	$month = $_POST[$meta_box['name'] . '_month'];
	$day = $_POST[$meta_box['name'] . '_day'];
	$hour = $_POST[$meta_box['name'] . '_hour'];
	$min = $_POST[$meta_box['name'] . '_min'];
	if (!$hour)
		$hour = '00';
	if (!$min)
		$min = '00';
	if (checkdate($month, $day, $year)) :
		$date = $year . $month . $day . ' ' . $hour . ':' . $min;
		update_post_meta($post_id, $meta_box['name'], strtotime($date));
	endif;
} else {
	update_post_meta($post_id, $meta_box['name'], $_POST[$meta_box['name']]);
}
}
}
add_action('admin_menu', 'videocast_create_meta_box');
add_action('save_post', 'videocast_save_meta_box');
?>