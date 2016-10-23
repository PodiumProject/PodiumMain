<div class="column-settings-editor">
<table>
	<tr>
		<td valign="top"><?php esc_html_e( 'Column ID', 'videofly' ); ?></td>
		<td>
			#ts_<input type="text" name="column-name-id" id="column-name-id" value="" />
			<div>
				<small><?php esc_html_e('(#ts_YOUR-ID-HERE)', 'videofly'); ?></small>
			</div>
		</td>
	</tr>
	<tr class="ts-background-color">
		<td valign="top"><?php esc_html_e( 'Background color', 'videofly' ); ?></td>
		<td>
			<input class="colors-section-picker" type="text" name="column-background-color" id="column-background-color" value="#FFFFFF" />
			<div  class="colors-section-picker-div"></div>
		</td>
	</tr>
	<tr class="ts-transparent">
		<td><?php esc_html_e( 'Transparent background ', 'videofly' ); ?> </td>
		<td>
			<select name="column-transparent" id="column-transparent">
				<option selected="selected" value="n"><?php esc_html_e('No', 'videofly') ?></option>
				<option value="y"><?php esc_html_e('Yes', 'videofly') ?></option>
			</select>

			<div class="ts-option-description">
			  
			</div>
		</td>
	</tr>
	<tr>
		<td valign="top"><?php esc_html_e( 'Text color', 'videofly' ); ?></td>
		<td>
			<input class="colors-section-picker"  type="text" name="column-text-color" id="column-text-color" value="#000000" />
			<div class="colors-section-picker-div"  id="column-text-color-picker"></div>
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Background image', 'videofly' ); ?></td>
		<td><input type="text" name="column-bg-image" id="column-bg-image" value="" />
			<input type="hidden" id="slide_media_id_image" name="column_media_id_image" value="" />
			<input class="ts-upload-column-image button" type="button" value="<?php esc_html_e( 'Upload image', 'videofly' ); ?>" /> 
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Background video .mp4', 'videofly' ); ?></td>
		<td><input type="text" name="column-bg-video-mp" id="column-bg-video-mp" value="" />
			<input type="hidden" id="slide_media_id_video_mp" name="column_media_id_video_mp" value="" />
			<input class="ts-upload-column-video-mp button" type="button" value="<?php esc_html_e( 'Upload video', 'videofly' ); ?>" /> 
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Background video .webm', 'videofly' ); ?></td>
		<td><input type="text" name="column-bg-video_webm" id="column-bg-video-webm" value="" />
			<input type="hidden" id="slide_media_id_video_webm" name="column_media_id_video_webm" value="" />
			<input class="ts-upload-column-video-webm button" type="button" value="<?php esc_html_e( 'Upload video', 'videofly' ); ?>" /> 
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Enable show mask', 'videofly' ); ?> </td>
		<td>
			<select name="column-mask" id="column-mask">
				<option value="yes"><?php esc_html_e('Yes', 'videofly') ?></option>
				<option value="no"><?php esc_html_e('No', 'videofly') ?></option>
				<option value="gradient"><?php esc_html_e('Gradient', 'videofly') ?></option>
			</select>
		</td>
	</tr>
	<tr id="ts_mask_color">
		<td valign="top">
			<?php esc_html_e( 'Color mask', 'videofly' ); ?>
		</td>
		<td>
			<input class="colors-section-picker" type="text" name="column-mask-color" id="column-mask-color" value="" />
			<div class="colors-section-picker-div" id="column-mask-color-picker"></div>
		</td>
	</tr>
	<tr id="ts-column-mask-gradient-mode">
		<td valign="top">
			<?php esc_html_e( 'Choose mode gradient for mask', 'videofly' ); ?>
		</td>
		<td>
			<select name="column-mask-gradient-mode" id="column-mask-gradient-mode">
				<option value="radial"><?php esc_html_e('Radial', 'videofly'); ?></option>
				<option value="left-to-right"><?php esc_html_e('Left to right', 'videofly'); ?></option>
				<option value="corner-top"><?php esc_html_e('Corner top left to bottom right', 'videofly'); ?></option>
				<option value="corner-bottom"><?php esc_html_e('Corner bottom left to top right', 'videofly'); ?></option>
			</select>
		</td>
	</tr>
	<tr id="mask-color-gradient">
		<td valign="top">
			<?php esc_html_e( 'Choose mask gradient color', 'videofly' ) ?>
		</td>
		<td>
			<input class="colors-section-picker" type="text" name="column-mask-gradient-color" id="column-mask-gradient-color" value="#ffffff" />
			<div class="colors-section-picker-div"></div>
		</td>
	</tr>
	<tr id="ts_mask_opacity">
		<td valign="top"><?php esc_html_e( 'Opacity', 'videofly' ); ?></td>
		<td>
			<input type="text" name="column-opacity" id="column-opacity" value="" disabled />%
			<div id="column-opacity-slider"></div>
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Background postition', 'videofly' ); ?></td>
		<td>
			<select name="column-bg-position" id="column-bg-position">
				<option value="left"><?php esc_html_e( 'Left', 'videofly' ); ?></option>
				<option value="center"><?php esc_html_e( 'Center', 'videofly' ); ?></option>
				<option value="right"><?php esc_html_e( 'Right', 'videofly' ); ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Background attachment', 'videofly' ); ?></td>
		<td>
			<select name="column-bg-attachement" id="column-bg-attachement">
				<option value="fixed"><?php esc_html_e( 'Fixed', 'videofly' ); ?></option>
				<option value="scroll"><?php esc_html_e( 'Scroll', 'videofly' ); ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Background repeat', 'videofly' ); ?> </td>
		<td>
			<select name="column-bg-repeat" id="column-bg-repeat">
				<option value="repeat"><?php esc_html_e( 'Repeat', 'videofly' ); ?></option>
				<option value="no-repeat"><?php esc_html_e( 'No-repeat', 'videofly' ); ?></option>
				<option value="repeat-x"><?php esc_html_e( 'Horizontaly', 'videofly' ); ?></option>
				<option value="repeat-y"><?php esc_html_e( 'Verticaly', 'videofly' ); ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Background size', 'videofly' ); ?> </td>
		<td>
			<select name="column-bg-size" id="column-bg-size">
				<option value="auto"><?php esc_html_e( 'Auto', 'videofly' ); ?></option>
				<option value="cover"><?php esc_html_e( 'Cover', 'videofly' ); ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Padding top', 'videofly' ); ?> </td>
		<td>
			<input type="text" name="padding-top" id="column-padding-top" value="0"> px
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Padding bottom', 'videofly' ); ?> </td>
		<td>
			<input type="text" name="padding-bottom" id="column-padding-bottom" value="0"> px
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Padding left', 'videofly' ); ?> </td>
		<td>
			<input type="text" name="padding-left" id="column-padding-left" value="0"> px
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Padding right', 'videofly' ); ?> </td>
		<td>
			<input type="text" name="padding-right" id="column-padding-right" value="0"> px
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Gutter right', 'videofly' ); ?> </td>
		<td>
			<input type="text" name="column-gutter-right" id="column-gutter-right" value="" disabled /> px
			<div id="column-gutter-right-slider"></div>
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Gutter left', 'videofly' ); ?> </td>
		<td>
			<input type="text" name="column-gutter-left" id="column-gutter-left" value="" disabled /> px
			<div id="column-gutter-left-slider"></div>
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Text align', 'videofly' ); ?> </td>
		<td>
			<select name="text-align" id="text-align">
				<option value="auto"><?php esc_html_e('Auto', 'videofly') ?></option>
				<option value="left"><?php esc_html_e('Left', 'videofly') ?></option>
				<option value="center"><?php esc_html_e('Center', 'videofly') ?></option>
				<option value="right"><?php esc_html_e('Right', 'videofly') ?></option>
			</select>
		</td>
	</tr>
</table>
<input type="button" class="button-primary save-element" value="<?php esc_html_e( 'Save changes', 'videofly' ); ?>" id="save-column-settings"/>
</div>

<script>
	jQuery(document).ready(function($) {

		var modalWindow = $('#ts-builder-elements-modal', document),
			column = window.currentEditedColumn,

			columnName = column.attr("data-name-id") ? column.attr("data-name-id") : '',
			bgColor = column.attr("data-bg-color") ? column.attr("data-bg-color") : '#FFFFFF',
			textColor = column.attr("data-text-color") ? column.attr("data-text-color") : '#000000',
			columnMaskColor = column.attr("data-mask-color") ? column.attr("data-mask-color") : '#fff',
			columnOpacity = column.attr("data-opacity") ? column.attr("data-opacity") : '',
			bgImage = column.attr("data-bg-image") ? column.attr("data-bg-image") : '',
			bgVideoMp = column.attr("data-bg-video-mp") ? column.attr("data-bg-video-mp") : '',
			bgVideoWebm = column.attr("data-bg-video-webm") ? column.attr("data-bg-video-webm") : '',
			bgPosition = column.attr("data-bg-position") ? column.attr("data-bg-position") : '',
			bgAttachement = column.attr("data-bg-attachment") ? column.attr("data-bg-attachment") : '',
			bgRepeat = column.attr("data-bg-repeat") ? column.attr("data-bg-repeat") : '',
			bgSize = column.attr("data-bg-size") ? column.attr("data-bg-size") : '',
			columnPaddingTop = column.attr("data-padding-top") ? column.attr("data-padding-top") : '0',
			columnPaddingBottom = column.attr("data-padding-bottom") ? column.attr("data-padding-bottom") : '0',
			columnPaddingLeft = column.attr("data-padding-left") ? column.attr("data-padding-left") : '0',
			columnPaddingRight = column.attr("data-padding-right") ? column.attr("data-padding-right") : '0',
			columnTextAlign = column.attr("data-text-align") ? column.attr("data-text-align") : 'auto';
			columnMask = column.attr("data-mask") ? column.attr("data-mask") : 'no';
			gutterLeft =  column.attr("data-gutter-left") ? column.attr("data-gutter-left") : '20';
			gutterRight =  column.attr("data-gutter-right") ? column.attr("data-gutter-right") : '20';
			gradientMaskMode = column.attr("data-mask-gradient-mode") ? column.attr("data-mask-gradient-mode") : 'radial',
			maskGradient = column.attr("data-mask-gradient") ? column.attr("data-mask-gradient") : '#fff';
			transparent = column.attr("data-transparent") ? column.attr("data-transparent") : 'y';

	
		// repopulate column settings
		modalWindow.find('#column-name-id').val(columnName);
		modalWindow.find('#column-background-color').val(bgColor);
		modalWindow.find('#column-text-color').val(textColor);
		modalWindow.find('#column-mask-color').val(columnMaskColor);
		ts_slider_pips(1, 100, 1, columnOpacity, 'column-opacity-slider', 'column-opacity');
		modalWindow.find('#column-bg-image').val(bgImage);
		modalWindow.find('#column-bg-video-mp').val(bgVideoMp);
		modalWindow.find('#column-bg-video-webm').val(bgVideoWebm);
		ts_slider_pips(0, 150, 1, gutterLeft, 'column-gutter-left-slider', 'column-gutter-left');
		ts_slider_pips(0, 150, 1, gutterRight, 'column-gutter-right-slider', 'column-gutter-right');
		modalWindow.find('#column-mask-gradient-color').val(maskGradient);

		modalWindow.find('#column-transparent option').filter(function() {
			return $(this).val() == transparent; 
		}).prop('selected', true);

		modalWindow.find('#column-mask-gradient-mode option').filter(function() {
			return $(this).val() == gradientMaskMode; 
		}).prop('selected', true);

		modalWindow.find('#column-bg-position option').filter(function() {
			return $(this).val() == bgPosition; 
		}).prop('selected', true);

		modalWindow.find('#column-bg-attachement option').filter(function() {
			return $(this).val() == bgAttachement; 
		}).prop('selected', true);

		modalWindow.find('#column-bg-repeat option').filter(function() {
			return $(this).val() == bgRepeat; 
		}).prop('selected', true)
		;
		modalWindow.find('#column-bg-size option').filter(function() {
			return $(this).val() == bgSize; 
		}).prop('selected', true)
		;

		modalWindow.find('#column-padding-top').val(columnPaddingTop);
		modalWindow.find('#column-padding-bottom').val(columnPaddingBottom);
		modalWindow.find('#column-padding-left').val(columnPaddingLeft);
		modalWindow.find('#column-padding-right').val(columnPaddingRight);

		modalWindow.find('#column-mask option').filter(function() {
			return $(this).val() == columnMask; 
		}).prop('selected', true);

		modalWindow.find('#text-align option').filter(function() {
			return $(this).val() == columnTextAlign; 
		}).prop('selected', true);

		function ts_show_proprety_mask(){

			$('#ts_mask_color').hide();
			$('#ts_mask_opacity').hide();
			$('#mask-color-gradient').hide();

			$('#column-mask').change(function(){
				if( $(this).val() == 'no' ){
					$('#ts_mask_color').hide();
					$('#ts_mask_opacity').hide();
					$('#mask-color-gradient').hide();
					$('#ts-column-mask-gradient-mode').hide();
				}else if( $(this).val() == 'gradient' ){
					$('#ts_mask_color').show();
					$('#ts_mask_opacity').show();
					$('#mask-color-gradient').show();
					$('#ts-column-mask-gradient-mode').show();
				}else{
					$('#ts_mask_color').show();
					$('#ts_mask_opacity').show();
					$('#mask-color-gradient').hide();
					$('#ts-column-mask-gradient-mode').hide();
				}
			});

			if( $('#column-mask').val() == 'yes' ){
				$('#ts_mask_color').show();
				$('#ts_mask_opacity').show();
				$('#mask-color-gradient').hide();
				$('#ts-column-mask-gradient-mode').hide();
			}else if( $('#column-mask').val() == 'gradient' ){
				$('#ts_mask_color').show();
				$('#ts_mask_opacity').show();
				$('#mask-color-gradient').show();
				$('#ts-column-mask-gradient-mode').show();
			}else{
				$('#ts_mask_color').hide();
				$('#ts_mask_opacity').hide();
				$('#mask-color-gradient').hide();
				$('#ts-column-mask-gradient-mode').hide();
			}

			if( jQuery('#column-gradient').val() == 'y' ){
				jQuery('#ts-gradient-color').show();
				jQuery('#ts-column-gradient-mode').show();
			}else{
				jQuery('#ts-gradient-color').hide();
				jQuery('#ts-column-gradient-mode').hide();
			}
			
		}
		ts_show_proprety_mask();

		function ts_upload_file(class_button,library,curent_column_id,prefix_button_id,input_hidden_id,input_attachment_id,text_button){

			var custom_uploader = {};
			if (typeof wp.media.frames.file_frame == 'undefined') {
				wp.media.frames.file_frame = {};
			}
			$(class_button).attr('id', prefix_button_id + 'button'+ curent_column_id);
			// Upload background image
			$(document).on('click', '#' + prefix_button_id + 'button'+ curent_column_id, function(e) {
				e.preventDefault();
				var _this     = $(this),
				target_id = _this.attr('id'),
				media_id  = _this.closest('td').find(input_hidden_id).val();

				//If the uploader object has already been created, reopen the dialog
				if (custom_uploader[target_id]) {
					custom_uploader[target_id].open();
					return;
				}

				//Extend the wp.media object
				custom_uploader[target_id] = wp.media.frames.file_frame[target_id] = wp.media({
					title: text_button,
					button: {
						text: text_button
					},
					library: {
						type: library
					},
					multiple: false,
					selection: [media_id]
				});

				//When a file is selected, grab the URL and set it as the text field's value
				custom_uploader[target_id].on('select', function() {
					var attachment = custom_uploader[target_id].state().get('selection').first().toJSON();
					var slide = _this.closest('table');
					slide.find(input_attachment_id).val(attachment.url);
					slide.find(input_hidden_id).val(attachment.id);
				});

				//Open the uploader dialog
				custom_uploader[target_id].open();
			});
		}
		ts_upload_file('.ts-upload-column-image','image',window.currentSetId,'ts_image','#slide_media_id_image','#column-bg-image','Upload image');
		ts_upload_file('.ts-upload-column-video-mp','webm',window.currentSetId,'ts_video_mp','#slide_media_id_video_mp','#column-bg-video-mp','Upload video mp4');
		ts_upload_file('.ts-upload-column-video-webm','webm',window.currentSetId,'ts_video_webm','#slide_media_id_video_webm','#column-bg-video-webm','Upload video webm');

		// Color pickers
		if (jQuery('.colors-section-picker-div').length) {
		    jQuery('.colors-section-picker-div').hide();

		    jQuery(".colors-section-picker").click(function(e){
		        e.stopPropagation();
		        jQuery(jQuery(this).next()).show();
		    });
		    
		    var pickers = jQuery('.colors-section-picker-div');
		    setTimeout(function(){
		        jQuery.each(pickers, function( index, value ) {
		            jQuery(this).farbtastic(jQuery(this).prev());
		        });
		    },100);
		    jQuery('body').click(function() {
		        jQuery(pickers).hide();
		    });
		}

		jQuery('.ts-transparent select').change(function(){
			if( jQuery(this).val() == 'y' ){
				jQuery('.ts-background-color').hide();
			}else{
				jQuery('.ts-background-color').show();
			}
		});

		if( jQuery('.ts-transparent select').val() == 'y' ){
			jQuery('.ts-background-color').hide();
		}else{
			jQuery('.ts-background-color').show();
		}

	});
	</script>
