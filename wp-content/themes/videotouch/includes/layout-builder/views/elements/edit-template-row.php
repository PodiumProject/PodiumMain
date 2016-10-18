<div class="row-settings-editor">
<table>
	<tr>
		<td valign="top"><?php _e( 'Row ID', 'touchsize' ) ?></td>
		<td>
			#ts_<input type="text" name="row-name-id" id="row-name-id" value="" />
			<div>
				<small><?php _e('If you are using the one-page layout - use this for your links. (#ts_YOUR-ID-HERE)', 'touchsize'); ?></small>
			</div>
		</td>
	</tr>
	<tr>
		<td valign="top"><?php _e( 'Background color', 'touchsize' ) ?></td>
		<td>
			<input class="colors-section-picker" type="text" name="row-background-color" id="row-background-color" value="" />
			<div  class="colors-section-picker-div"  id="row-background-color-picker"></div>
		</td>
	</tr>
	<tr>
		<td valign="top"><?php _e( 'Text color', 'touchsize' ) ?></td>
		<td>
			<input class="colors-section-picker"  type="text" name="row-text-color" id="row-text-color" value="" />
			<div class="colors-section-picker-div"  id="row-text-color-picker"></div>
		</td>
	</tr>
	<tr>
		<td><?php _e( 'Background image', 'touchsize' ) ?></td>
		<td><input type="text" name="row-bg-image" id="row-bg-image" value="" />
			<input type="hidden" id="slide_media_id_image" name="row_media_id_image" value="" />
			<input class="ts-upload-row-image button" type="button" value="<?php _e( 'Upload image', 'touchsize' ) ?>" /> 
		</td>
	</tr>
	<tr>
		<td><?php _e( 'Background video .mp4', 'touchsize' ) ?></td>
		<td><input type="text" name="row-bg-video-mp" id="row-bg-video-mp" value="" />
			<input type="hidden" id="slide_media_id_video_mp" name="row_media_id_video_mp" value="" />
			<input class="ts-upload-row-video-mp button" type="button" value="<?php _e( 'Upload video', 'touchsize' ) ?>" /> 
		</td>
	</tr>
	<tr>
		<td><?php _e( 'Background video .webm', 'touchsize' ) ?></td>
		<td><input type="text" name="row-bg-video_webm" id="row-bg-video-webm" value="" />
			<input type="hidden" id="slide_media_id_video_webm" name="row_media_id_video_webm" value="" />
			<input class="ts-upload-row-video-webm button" type="button" value="<?php _e( 'Upload video', 'touchsize' ) ?>" /> 
		</td>
	</tr>
	<tr>
		<td><?php _e( 'Enable show mask', 'touchsize' ) ?> </td>
		<td>
			<select name="row-mask" id="row-mask">
				<option value="yes"><?php _e('Yes', 'touchsize') ?></option>
				<option value="no"><?php _e('No', 'touchsize') ?></option>
			</select>
		</td>
	</tr>
	<tr id="ts_mask_opacity">
		<td valign="top"><?php _e( 'Opacity', 'touchsize' ) ?></td>
		<td>
			<input type="text" name="row-opacity" id="row-opacity" value="" />%
		</td>
	</tr>
	<tr id="ts_mask_color">
		<td valign="top">
			<?php _e( 'Color mask', 'touchsize' ) ?>
		</td>
		<td>
			<input class="colors-section-picker" type="text" name="row-mask-color" id="row-mask-color" value="" />
			<div class="colors-section-picker-div" id="row-mask-color-picker"></div>
		</td>
	</tr>
	<tr>
		<td><?php _e( 'Gradient background', 'touchsize' ) ?> </td>
		<td>
			<select name="row-gradient" id="row-gradient">
				<option value="y"><?php _e('Yes', 'touchsize'); ?></option>
				<option value="n"><?php _e('No', 'touchsize'); ?></option>
			</select>
		</td>
	</tr>
	<tr class="ts_gradient_color">
		<td valign="top">
			<?php _e( 'Choose color gradient', 'touchsize' ); ?>
		</td>
		<td>
			<input class="colors-section-picker" type="text" name="row-gradient-color" id="row-gradient-color" value="" />
			<div class="colors-section-picker-div" id="row-gradient-color-picker"></div>
		</td>
	</tr>
	<tr class="ts_mode_gradient">
		<td valign="top">
			<?php _e( 'Choose mode gradient', 'touchsize' ); ?>
		</td>
		<td>
			<select name="row-gradient-mode" id="row-gradient-mode">
				<option value="radial"><?php _e('Radial', 'touchsize'); ?></option>
				<option value="left-to-right"><?php _e('Left to right', 'touchsize'); ?></option>
				<option value="corner-top"><?php _e('Corner top left to bottom right', 'touchsize'); ?></option>
				<option value="corner-bottom"><?php _e('Corner bottom left to top right', 'touchsize'); ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td><?php _e( 'Background postition', 'touchsize' ) ?></td>
		<td>
			<select name="row-bg-position" id="row-bg-position">
				<option value="left"><?php _e( 'Left', 'touchsize' ) ?></option>
				<option value="center"><?php _e( 'Center', 'touchsize' ) ?></option>
				<option value="right"><?php _e( 'Right', 'touchsize' ) ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td><?php _e( 'Background attachment', 'touchsize' ) ?></td>
		<td>
			<select name="row-bg-attachement" id="row-bg-attachement">
				<option value="fixed"><?php _e( 'Fixed', 'touchsize' ) ?></option>
				<option value="scroll"><?php _e( 'Scroll', 'touchsize' ) ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td><?php _e( 'Background repeat', 'touchsize' ) ?> </td>
		<td>
			<select name="row-bg-repeat" id="row-bg-repeat">
				<option value="repeat"><?php _e( 'Repeat', 'touchsize' ) ?></option>
				<option value="no-repeat"><?php _e( 'No-repeat', 'touchsize' ) ?></option>
				<option value="repeat-x"><?php _e( 'Horizontaly', 'touchsize' ) ?></option>
				<option value="repeat-y"><?php _e( 'Verticaly', 'touchsize' ) ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td><?php _e( 'Background size', 'touchsize' ) ?> </td>
		<td>
			<select name="row-bg-size" id="row-bg-size">
				<option value="auto"><?php _e( 'Auto', 'touchsize' ) ?></option>
				<option value="cover"><?php _e( 'Cover', 'touchsize' ) ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td><?php _e( 'Margin top', 'touchsize' ) ?> </td>
		<td>
			<input type="text" name="margin-top" id="row-margin-top" value="0"> px
		</td>
	</tr>
	<tr>
		<td><?php _e( 'Margin bottom', 'touchsize' ) ?> </td>
		<td>
			<input type="text" name="margin-top" id="row-margin-bottom" value="30"> px
		</td>
	</tr>
	<tr>
		<td><?php _e( 'Padding top', 'touchsize' ) ?> </td>
		<td>
			<input type="text" name="padding-top" id="row-padding-top" value="0"> px
		</td>
	</tr>
	<tr>
		<td><?php _e( 'Padding bottom', 'touchsize' ) ?> </td>
		<td>
			<input type="text" name="padding-bottom" id="row-padding-bottom" value="0"> px
		</td>
	</tr>
	<tr>
		<td><?php _e( 'Special effects', 'touchsize' ) ?> </td>
		<td>
			<select name="special-effects" id="special-effects">
				<option value="no"><?php _e('None', 'touchsize') ?></option>
				<option value="slideup"><?php _e('Slide up', 'touchsize') ?></option>
				<option value="perspective-x"><?php _e('Perspective X', 'touchsize') ?></option>
				<option value="perspective-y"><?php _e('Perspective Y', 'touchsize') ?></option>
				<option value="opacited"><?php _e('Opacity', 'touchsize') ?></option>
				<option value="slideright"><?php _e('Slide right', 'touchsize') ?></option>
				<option value="slideleft"><?php _e('Slide left', 'touchsize') ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td><?php _e( 'Text align', 'touchsize' ) ?> </td>
		<td>
			<select name="text-align" id="text-align">
				<option value="auto"><?php _e('Auto', 'touchsize') ?></option>
				<option value="left"><?php _e('Left', 'touchsize') ?></option>
				<option value="center"><?php _e('Center', 'touchsize') ?></option>
				<option value="right"><?php _e('Right', 'touchsize') ?></option>
			</select>
		</td>
	</tr>
	<tr class="ts_vertical_align">
		<td><?php _e( 'Vertical align', 'touchsize' ) ?></td>
		<td>
			<select name="row-vertical-align" id="row-vertical-align">
				<option value="top"><?php _e('Top', 'touchsize') ?></option>
				<option value="middle"><?php _e('Middle', 'touchsize') ?></option>
				<option value="bottom"><?php _e('Bottom', 'touchsize') ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td><?php _e( 'Enable box shadow', 'touchsize' ) ?> </td>
		<td>
			<select name="row-shadow" id="row-shadow">
				<option value="yes"><?php _e('Yes', 'touchsize') ?></option>
				<option value="no"><?php _e('No', 'touchsize') ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td><?php _e( 'Expand row', 'touchsize' ) ?> </td>
		<td>
			<ul class="imageRadioMetaUl perRow-4 ts-custom-selector" data-selector="#expand-row" id="expand-row-selector">
               <li><img class="image-radio-input clickable-element" data-option="yes" src="<?php echo get_template_directory_uri().'/images/options/expand_row_yes.png'; ?>"></li>
               <li><img class="image-radio-input clickable-element" data-option="no" src="<?php echo get_template_directory_uri().'/images/options/expand_row_no.png'; ?>"></li>
            </ul>
			<select class="hidden" name="expand-row" id="expand-row">
				<option value="yes"><?php _e('Yes', 'touchsize') ?></option>
				<option value="no"><?php _e('No', 'touchsize') ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td><?php _e( 'Fullscreen row', 'touchsize' ) ?> </td>
		<td>
			<ul class="imageRadioMetaUl perRow-4 ts-custom-selector" data-selector="#fullscreen-row" id="fullscreen-row-selector">
               <li><img class="image-radio-input clickable-element" data-option="no" src="<?php echo get_template_directory_uri().'/images/options/fullscreen_row_no.png'; ?>"></li>
               <li><img class="image-radio-input clickable-element" data-option="yes" src="<?php echo get_template_directory_uri().'/images/options/fullscreen_row_yes.png'; ?>"></li>
            </ul>
			<select name="fullscreen-row" id="fullscreen-row">
				<option value="no"><?php _e('No', 'touchsize') ?></option>
				<option value="yes"><?php _e('Yes', 'touchsize') ?></option>
			</select>
		</td>
	</tr>
</table>
<input type="button" class="button-primary save-element" value="<?php _e( 'Save changes', 'touchsize' ) ?>" id="save-row-settings"/>
</div>
	<script>
	jQuery(document).ready(function($) {

		var modalWindow = $('#ts-builder-elements-modal', document),
		row = window.currentEditedRow,

		rowName = row.attr("data-name-id") ? row.attr("data-name-id") : '',
		bgColor = row.attr("data-bg-color") ? row.attr("data-bg-color") : '',
		textColor = row.attr("data-text-color") ? row.attr("data-text-color") : '',
		rowMaskColor = row.attr("data-mask-color") ? row.attr("data-mask-color") : '',
		rowOpacity = row.attr("data-opacity") ? row.attr("data-opacity") : '',
		bgImage = row.attr("data-bg-image") ? row.attr("data-bg-image") : '',
		bgVideoMp = row.attr("data-bg-video-mp") ? row.attr("data-bg-video-mp") : '',
		bgVideoWebm = row.attr("data-bg-video-webm") ? row.attr("data-bg-video-webm") : '',
		bgPosition = row.attr("data-bg-position") ? row.attr("data-bg-position") : '',
		bgAttachement = row.attr("data-bg-attachment") ? row.attr("data-bg-attachment") : '',
		bgRepeat = row.attr("data-bg-repeat") ? row.attr("data-bg-repeat") : '',
		bgSize = row.attr("data-bg-size") ? row.attr("data-bg-size") : '',
		rowMarginTop = row.attr("data-margin-top") ? row.attr("data-margin-top") : '0',
		rowMarginBottom = row.attr("data-margin-bottom") ? row.attr("data-margin-bottom") : '30',
		rowPaddingTop = row.attr("data-padding-top") ? row.attr("data-padding-top") : '0',
		rowPaddingBottom = row.attr("data-padding-bottom") ? row.attr("data-padding-bottom") : '0',
		expandRow = row.attr("data-expand-row") ? row.attr("data-expand-row") : 'no',
		specialEffects = row.attr("data-special-effects") ? row.attr("data-special-effects") : 'none';
		rowTextAlign = row.attr("data-text-align") ? row.attr("data-text-align") : 'auto';
		fullscreenRow = row.attr("data-fullscreen-row") ? row.attr("data-fullscreen-row") : 'no';
		rowMask = row.attr("data-mask") ? row.attr("data-mask") : 'no';
		rowShadow = row.attr("data-shadow") ? row.attr("data-shadow") : 'no';
		rowVerticalAlign = row.attr("data-vertical-align") ? row.attr("data-vertical-align") : 'top';
		gradientMode = row.attr("data-gradient-mode") ? row.attr("data-gradient-mode") : 'radial',
		gradient = row.attr("data-gradient") ? row.attr("data-gradient") : 'n',
		gradientColor = row.attr("data-gradient-color") ? row.attr("data-gradient-color") : 'transparent',
	
		// repopulate row settings
		modalWindow.find('#row-name-id').val(rowName);
		modalWindow.find('#row-background-color').val(bgColor);
		modalWindow.find('#row-text-color').val(textColor);
		modalWindow.find('#row-mask-color').val(rowMaskColor);
		modalWindow.find('#row-opacity').val(rowOpacity);
		modalWindow.find('#row-bg-image').val(bgImage);
		modalWindow.find('#row-bg-video-mp').val(bgVideoMp);
		modalWindow.find('#row-bg-video-webm').val(bgVideoWebm);
		modalWindow.find('#row-gradient-color').val(gradientColor);

		modalWindow.find('#row-bg-position option').filter(function() {
			return $(this).val() == bgPosition; 
		}).prop('selected', true);

		modalWindow.find('#row-gradient-mode option').filter(function() {
			return $(this).val() == gradientMode; 
		}).prop('selected', true);

		modalWindow.find('#row-gradient option').filter(function() {
			return $(this).val() == gradient; 
		}).prop('selected', true);

		modalWindow.find('#row-bg-attachement option').filter(function() {
			return $(this).val() == bgAttachement; 
		}).prop('selected', true);

		modalWindow.find('#row-bg-repeat option').filter(function() {
			return $(this).val() == bgRepeat; 
		}).prop('selected', true)
		;
		modalWindow.find('#row-bg-size option').filter(function() {
			return $(this).val() == bgSize; 
		}).prop('selected', true)
		;

		modalWindow.find('#row-margin-top').val(rowMarginTop);
		modalWindow.find('#row-margin-bottom').val(rowMarginBottom);
		modalWindow.find('#row-padding-top').val(rowPaddingTop);
		modalWindow.find('#row-padding-bottom').val(rowPaddingBottom);

		modalWindow.find('#expand-row option').filter(function() {
			return $(this).val() == expandRow; 
		}).prop('selected', true);

		modalWindow.find('#special-effects option').filter(function() {
			return $(this).val() == specialEffects; 
		}).prop('selected', true);

		modalWindow.find('#row-mask option').filter(function() {
			return $(this).val() == rowMask; 
		}).prop('selected', true);

		modalWindow.find('#row-shadow option').filter(function() {
			return $(this).val() == rowShadow; 
		}).prop('selected', true);

		modalWindow.find('#fullscreen-row option').filter(function() {
			return $(this).val() == fullscreenRow; 
		}).prop('selected', true);

		modalWindow.find('#text-align option').filter(function() {
			return $(this).val() == rowTextAlign; 
		}).prop('selected', true);

		modalWindow.find('#row-vertical-align option').filter(function() {
			return $(this).val() == rowVerticalAlign; 
		}).prop('selected', true);

		function ts_show_proprety_mask(){

			$('#ts_mask_color').hide();
			$('#ts_mask_opacity').hide();
			$('#row-mask').change(function(){
				if( $(this).val() == 'no' ){
					$('#ts_mask_color').hide();
					$('#ts_mask_opacity').hide();
				}else{
					$('#ts_mask_color').show();
					$('#ts_mask_opacity').show();
				}
			});

			if( $('#row-mask').val() == 'yes' ){
				$('#ts_mask_color').show();
				$('#ts_mask_opacity').show();
			}

			$('.ts_gradient_color').hide();
			$('.ts_mode_gradient').hide();
			$('#row-gradient').change(function(){
				if( $(this).val() == 'n' ){
					$('.ts_gradient_color').hide();
					$('.ts_mode_gradient').hide();
				}else{
					$('.ts_gradient_color').show();
					$('.ts_mode_gradient').show();
				}
			});

			if( $('#row-gradient').val() == 'y' ){
				$('.ts_gradient_color').show();
				$('.ts_mode_gradient').show();
			}
		}
		ts_show_proprety_mask();

		function ts_upload_file(class_button,library,curent_row_id,prefix_button_id,input_hidden_id,input_attachment_id,text_button){

			var custom_uploader = {};
			if (typeof wp.media.frames.file_frame == 'undefined') {
				wp.media.frames.file_frame = {};
			}
			$(class_button).attr('id', prefix_button_id + 'button'+ curent_row_id);
			// Upload background image
			$(document).on('click', '#' + prefix_button_id + 'button'+ curent_row_id, function(e) {
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
		ts_upload_file('.ts-upload-row-image','image',window.currentSetId,'ts_image','#slide_media_id_image','#row-bg-image','Upload image');
		ts_upload_file('.ts-upload-row-video-mp','webm',window.currentSetId,'ts_video_mp','#slide_media_id_video_mp','#row-bg-video-mp','Upload video mp4');
		ts_upload_file('.ts-upload-row-video-webm','webm',window.currentSetId,'ts_video_webm','#slide_media_id_video_webm','#row-bg-video-webm','Upload video webm');

		function custom_selectors(selector, targetselect){
            selector_option = jQuery(selector).attr('data-option');
            jQuery(selector).parent().parent().find('.selected').removeClass('selected');
            jQuery(targetselect).find('option[selected="selected"]').removeAttr('selected');
            jQuery(targetselect).find('option[value="' + selector_option + '"]').attr('selected','selected');
            jQuery(selector).parent().addClass('selected');
        }

        function custom_selectors_run(){
            jQuery('.ts-custom-selector').each(function(){
                the_selector = jQuery(this).attr('data-selector');
                the_selector_value = jQuery(the_selector + ' option[selected="selected"]').attr('value');
                selected_class = jQuery(this).find('[data-option="' + the_selector_value + '"]').attr('data-option');
                jQuery(this).find('[data-option="' + selected_class + '"]').parent().addClass('selected');

                var firstchild = jQuery(this).children('li:first'),
                    bool = false;

				if(jQuery(this).children().hasClass('selected') == false){
					firstchild.addClass('selected');
					bool = true;
					if(bool == true){
					    jQuery(the_selector + ' li .clickable-element:first-child').trigger('click');
					}
                }else{
                	return true;
                }
            });
        }

        jQuery('.ts-custom-selector').each(function(){
            var data_select = jQuery(this).attr('data-selector');
            jQuery(data_select).addClass('hidden');
        });

        function restartColorPickers(){
          var pickers = jQuery('.colors-section-picker-div');
          jQuery.each(pickers, function( index, value ) {
            jQuery(this).farbtastic(jQuery(this).prev());
          });
        }
        setTimeout(function(){
            custom_selectors_run();
        },200);

        jQuery('.clickable-element').click(function(){
            data_selector = jQuery(this).parent().parent().attr('data-selector');
            custom_selectors(jQuery(this), data_selector);
            jQuery(data_selector).trigger('change');
        });

        if( expandRow ){
        	jQuery('#expand-row option').each(function(){
        		jQuery(this).val() == expandRow ? jQuery(this).attr('selected','selected') : '';
        	})
        }
        if( fullscreenRow ){
        	jQuery('#fullscreen-row option').each(function(){
        		jQuery(this).val() == fullscreenRow ? jQuery(this).attr('selected','selected') : '';
        	})
        }

		function ts_show_hide_text_align(){

			jQuery('#fullscreen-row').change(function(){
				if( jQuery(this).val() == 'no' ){
					jQuery('.ts_vertical_align').css({'display':'none'});
				}else{
					jQuery('.ts_vertical_align').css({'display':''});
				}
			});

			if( jQuery('#fullscreen-row').val() == 'no' ){
				jQuery('.ts_vertical_align').css({'display':'none'});
			}else{
				jQuery('.ts_vertical_align').css({'display':''});
			}
		}
		ts_show_hide_text_align();


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
		
	});
	</script>
