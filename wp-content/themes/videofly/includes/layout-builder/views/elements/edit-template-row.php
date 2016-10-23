<div class="row-settings-editor">
<table>
	<tr>
		<td valign="top" width="30%"><?php esc_html_e( 'Row ID', 'videofly' ); ?></td>
		<td width="60%">
			#ts_<input type="text" name="row-name-id" id="row-name-id" value="" />
			<div class="ts-option-description">
			    <?php esc_html_e('If you are using the one-page layout - use this for your links.', 'videofly'); ?> (#ts_YOUR-ID-HERE) 
			</div>
		</td>
	</tr>
	<tr class="ts-background-color">
		<td valign="top"><?php esc_html_e( 'Background color', 'videofly' ); ?></td>
		<td>
			<input class="colors-section-picker" type="text" name="row-background-color" id="row-background-color" value="" />
			<div  class="colors-section-picker-div"  id="row-background-color-picker"></div>
			<div class="ts-option-description">
			    <?php esc_html_e('Choose the background color for your row. Unless you are using the boxed layout, the background will go from one edge of the screen to another.', 'videofly'); ?>.
			</div>
		</td>
	</tr>
	<tr class="ts-transparent">
		<td valign="center"><?php esc_html_e( 'Transparent background ', 'videofly' ); ?> </td>
		<td>
			<select name="row-transparent" id="row-transparent">
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
			<input class="colors-section-picker"  type="text" name="row-text-color" id="row-text-color" value="" />
			<div class="colors-section-picker-div"  id="row-text-color-picker"></div>
			<div class="ts-option-description">
			    <?php esc_html_e('Choose the text color for your row. This might not affect all elements in this row, some colors might be overwritten by some theme or builder element options', 'videofly'); ?>.
			</div>
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Background image', 'videofly' ); ?></td>
		<td><input type="text" name="row-bg-image" id="row-bg-image" value="" />
			<input type="hidden" id="slide_media_id_image" name="row_media_id_image" value="" />
			<input class="ts-upload-row-image button" type="button" value="<?php esc_html_e( 'Upload image', 'videofly' ); ?>" />

			<div class="ts-option-description">
			    <?php esc_html_e('Add a background image to your row.', 'videofly'); ?>.
			</div>
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Background video .mp4', 'videofly' ); ?></td>
		<td><input type="text" name="row-bg-video-mp" id="row-bg-video-mp" value="" />
			<input type="hidden" id="slide_media_id_video_mp" name="row_media_id_video_mp" value="" />
			<input class="ts-upload-row-video-mp button" type="button" value="<?php esc_html_e( 'Upload video', 'videofly' ); ?>" />
			<div class="ts-option-description">
			    <?php esc_html_e('Add a background video to your row. We strongly recommend to use muted videos, since there is no way to stop, pause or mute a background video. Use the MP4 format here for some browsers.', 'videofly'); ?>.
			</div>
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Background video .webm', 'videofly' ); ?></td>
		<td><input type="text" name="row-bg-video_webm" id="row-bg-video-webm" value="" />
			<input type="hidden" id="slide_media_id_video_webm" name="row_media_id_video_webm" value="" />
			<input class="ts-upload-row-video-webm button" type="button" value="<?php esc_html_e( 'Upload video', 'videofly' ); ?>" /> 
			<div class="ts-option-description">
			    <?php esc_html_e('Add a background video to your row. We strongly recommend to use muted videos, since there is no way to stop, pause or mute a background video. Use the WEBM format here for some browsers.', 'videofly'); ?>.
			</div>
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Enable parallax', 'videofly' ); ?> </td>
		<td>
			<select name="row-parallax" id="row-parallax">
				<option value="yes"><?php esc_html_e('Yes', 'videofly') ?></option>
				<option selected="selected" value="no"><?php esc_html_e('No', 'videofly') ?></option>
			</select>

			<div class="ts-option-description">
			    <?php esc_html_e('If you have a background image set, this will create a cool parallax efect for the background image of this specifc row.', 'videofly'); ?>.
			</div>
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Enable show mask', 'videofly' ); ?></td>
		<td>
			<select name="row-mask" id="row-mask">
				<option value="yes"><?php esc_html_e('Yes', 'videofly') ?></option>
				<option value="no"><?php esc_html_e('No', 'videofly') ?></option>
				<option value="gradient"><?php esc_html_e('Gradient', 'videofly') ?></option>
			</select>

			<div class="ts-option-description">
			    <?php esc_html_e('If you have a background image or a background video for this row, but some of the elements or some text cannot be read, add a row mask. This will place a mask over the background, but under the row contents.', 'videofly'); ?>.
			</div>
		</td>
	</tr>
	<tr id="ts_mask_color">
		<td valign="top">
			<?php esc_html_e( 'Color mask', 'videofly' ); ?>
		</td>
		<td>
			<input class="colors-section-picker" type="text" name="row-mask-color" id="row-mask-color" value="" />
			<div class="colors-section-picker-div" id="row-mask-color-picker"></div>
			<div class="ts-option-description">
			    <?php esc_html_e('Select the color of the mask that you want applied over the background of the row. Make sure you adapt it right with the opacity.', 'videofly'); ?>.
			</div>
		</td>
	</tr>
	<tr id="ts-row-mask-gradient-mode">
		<td>
			<?php esc_html_e( 'Choose mode gradient for mask', 'videofly' ); ?>
		</td>
		<td>
			<select name="row-mask-gradient-mode" id="row-mask-gradient-mode">
				<option value="radial"><?php esc_html_e('Radial', 'videofly'); ?></option>
				<option value="left-to-right"><?php esc_html_e('Left to right', 'videofly'); ?></option>
				<option value="corner-top"><?php esc_html_e('Corner top left to bottom right', 'videofly'); ?></option>
				<option value="corner-bottom"><?php esc_html_e('Corner bottom left to top right', 'videofly'); ?></option>
			</select>
		</td>
	</tr>
	<tr id="mask-color-gradient">
		<td>
			<?php esc_html_e( 'Choose mask gradient color', 'videofly' ) ?>
		</td>
		<td>
			<input class="colors-section-picker" type="text" name="row-mask-gradient-color" id="row-mask-gradient-color" value="" />
			<div class="colors-section-picker-div"></div>
		</td>
	</tr>
	<tr id="ts_mask_opacity">
		<label for="row-opacity"><td valign="top"><?php esc_html_e( 'Opacity', 'videofly' ); ?></td></label>
		<td>
			<input type="text" name="row-opacity" id="row-opacity" value="" readonly  />
			<div id="row-opacity-slider"></div>
			<div class="ts-option-description">
			    <?php esc_html_e('Select the opacity of the color mask. You can set anything from 1 to 100.', 'videofly'); ?>.
			</div>
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Background postition x', 'videofly' ); ?></td>
		<td>
			<select name="row-bg-position-x" id="row-bg-position-x">
				<option value="left"><?php esc_html_e( 'Left', 'videofly' ); ?></option>
				<option value="center"><?php esc_html_e( 'Center', 'videofly' ); ?></option>
				<option value="right"><?php esc_html_e( 'Right', 'videofly' ); ?></option>
			</select>
			<div class="ts-option-description">
			    <?php esc_html_e('Select the background position for background image of the row.', 'videofly'); ?>.
			</div>
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Background postition y', 'videofly' ); ?></td>
		<td>
			<select name="row-bg-position-y" id="row-bg-position-y">
				<option value="top"><?php esc_html_e( 'Top', 'videofly' ); ?></option>
				<option value="center"><?php esc_html_e( 'Center', 'videofly' ); ?></option>
				<option value="bottom"><?php esc_html_e( 'Bottom', 'videofly' ); ?></option>
			</select>
			<div class="ts-option-description">
			    <?php esc_html_e('Select the background position for background image of the row.', 'videofly'); ?>.
			</div>
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Background attachment', 'videofly' ); ?></td>
		<td>
			<select name="row-bg-attachement" id="row-bg-attachement">
				<option value="fixed"><?php esc_html_e( 'Fixed', 'videofly' ); ?></option>
				<option value="scroll"><?php esc_html_e( 'Scroll', 'videofly' ); ?></option>
			</select>
			<div class="ts-option-description">
			    <?php esc_html_e('You can make the background of this specific row scroll with the content when you scroll down or up, or make it fixed so it makes the website feel like it has a hole in it.', 'videofly'); ?>.
			</div>
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Background repeat', 'videofly' ); ?> </td>
		<td>
			<select name="row-bg-repeat" id="row-bg-repeat">
				<option value="repeat"><?php esc_html_e( 'Repeat', 'videofly' ); ?></option>
				<option value="no-repeat"><?php esc_html_e( 'No-repeat', 'videofly' ); ?></option>
				<option value="repeat-x"><?php esc_html_e( 'Horizontaly', 'videofly' ); ?></option>
				<option value="repeat-y"><?php esc_html_e( 'Verticaly', 'videofly' ); ?></option>
			</select>
			<div class="ts-option-description">
			    <?php esc_html_e('Select how do you want the background image to be repeated.', 'videofly'); ?>.
			</div>
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Background size', 'videofly' ); ?> </td>
		<td>
			<select name="row-bg-size" id="row-bg-size">
				<option value="auto"><?php esc_html_e( 'Auto', 'videofly' ); ?></option>
				<option value="cover"><?php esc_html_e( 'Cover', 'videofly' ); ?></option>
			</select>
			<div class="ts-option-description">
			    <?php esc_html_e('If you are using a background image, select the background size that you want. You can have it auto so it will keep the image size that you have uploaded or set it cover, so it makes sure that image will fill the row vertically and horizontally. Keep in mind that the second option could crop bits of your image.', 'videofly'); ?>.
			</div>
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Border top', 'videofly' ) ?></td>
		<td>
			<select name="row-border-top" id="row-border-top">
				<option value="y"><?php esc_html_e( 'Yes', 'videofly' ) ?></option>
				<option value="n"><?php esc_html_e( 'No', 'videofly' ) ?></option>
			</select>
			<div class="ts-option-description">

			</div>
		</td>
	</tr>
	<tr id="ts-row-color-border-top">
		<td><?php esc_html_e( 'Border top color', 'videofly' ) ?></td>
		<td>
			<input class="colors-section-picker"  type="text" name="row-color-border-top" id="row-color-border-top" value="" />
			<div class="colors-section-picker-div"></div>
		</td>
	</tr>
	<tr id="ts-row-width-border-top">
		<td><?php esc_html_e( 'Border top width', 'videofly' ) ?></td>
		<td>
			<input type="text" name="row-width-border-top" id="row-width-border-top" value="" disabled/>
			<div id="row-width-border-top-slider"></div>
			<div class="ts-option-description">
			    <?php esc_html_e('Select the width of the border top. You can set anything from 1 to 15.', 'videofly'); ?>.
			</div>
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Border bottom', 'videofly' ) ?></td>
		<td>
			<select name="row-border-bottom" id="row-border-bottom">
				<option value="y"><?php esc_html_e( 'Yes', 'videofly' ) ?></option>
				<option value="n"><?php esc_html_e( 'No', 'videofly' ) ?></option>
			</select>
			<div class="ts-option-description">

			</div>
		</td>
	</tr>
	<tr id="ts-row-color-border-bottom">
		<td><?php esc_html_e( 'Border bottom color', 'videofly' ) ?></td>
		<td>
			<input class="colors-section-picker"  type="text" name="row-color-border-bottom" id="row-color-border-bottom" value="" />
			<div class="colors-section-picker-div"></div>
		</td>
	</tr>
	<tr id="ts-row-width-border-bottom">
		<td><?php esc_html_e( 'Border bottom width', 'videofly' ) ?></td>
		<td>
			<input type="text" name="row-width-border-bottom" id="row-width-border-bottom" value="" disabled="" />
			<div id="row-width-border-bottom-slider"></div>
			<div class="ts-option-description">
			    <?php esc_html_e('Select the width of the border bottom. You can set anything from 1 to 15.', 'videofly'); ?>.
			</div>
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Margin top', 'videofly' ); ?> </td>
		<td>
			<input type="text" name="margin-top" id="row-margin-top" value="0"> px
			<div class="ts-option-description">
			    <?php esc_html_e('Set a margin top for your row. Margins will set a certain amout of space above this row.', 'videofly'); ?>.
			</div>
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Margin bottom', 'videofly' ); ?> </td>
		<td>
			<input type="text" name="margin-top" id="row-margin-bottom" value="30"> px
			<div class="ts-option-description">
			    <?php esc_html_e('Set a margin bottom for your row. Margins will set a certain amout of space below this row.', 'videofly'); ?>.
			</div>
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Padding top', 'videofly' ); ?> </td>
		<td>
			<input type="text" name="padding-top" id="row-padding-top" value="0"> px
			<div class="ts-option-description">
			    <?php esc_html_e('Set a padding top for your row. Padding top will set a certain amout of space inside the row, but from the top. So you can add blank space inside the row.', 'videofly'); ?>.
			</div>
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Padding bottom', 'videofly' ); ?> </td>
		<td>
			<input type="text" name="padding-bottom" id="row-padding-bottom" value="0"> px
			<div class="ts-option-description">
			    <?php esc_html_e('Set a padding bottom for your row. Padding bottom will set a certain amout of space inside the row, but from the bottom. So you can add blank space inside the row.', 'videofly'); ?>.
			</div>
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Special effects', 'videofly' ); ?> </td>
		<td>
			<select name="special-effects" id="special-effects">
				<option value="no"><?php esc_html_e('None', 'videofly') ?></option>
				<option value="slideup"><?php esc_html_e('Slide up', 'videofly') ?></option>
				<option value="perspective-x"><?php esc_html_e('Perspective X', 'videofly') ?></option>
				<option value="perspective-y"><?php esc_html_e('Perspective Y', 'videofly') ?></option>
				<option value="opacited"><?php esc_html_e('Opacity', 'videofly') ?></option>
				<option value="slideright"><?php esc_html_e('Slide right', 'videofly') ?></option>
				<option value="slideleft"><?php esc_html_e('Slide left', 'videofly') ?></option>
			</select>
			<div class="ts-option-description">
			    <?php esc_html_e('You can choose a certain animation for articles that are inside this row. Keep in mind that not all elements inside the row can be animated.', 'videofly'); ?>.
			</div>
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
			<div class="ts-option-description">
			    <?php esc_html_e('You can select the align of the text inside this row. Keep in mind that some element aligns are hardcoded and you cannot change them.', 'videofly'); ?>.
			</div>
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Enable box shadow', 'videofly' ); ?> </td>
		<td>
			<select name="row-shadow" id="row-shadow">
				<option value="yes"><?php esc_html_e('Yes', 'videofly') ?></option>
				<option value="no"><?php esc_html_e('No', 'videofly') ?></option>
			</select>

			<div class="ts-option-description">
			    <?php esc_html_e('If you enable this option, it will add a subtle box shadow inside the row edges.', 'videofly'); ?>.
			</div>
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Convert columns in carousel', 'videofly' ) ?> </td>
		<td>
			<select name="row-columns-in-carousel" id="row-columns-in-carousel">
				<option value="yes"><?php esc_html_e('Yes', 'videofly') ?></option>
				<option value="no"><?php esc_html_e('No', 'videofly') ?></option>
			</select>
		</td>
	</tr>
	<?php if( is_plugin_active('ts-custom-posts/ts-custom-posts.php') ) : ?>
		<tr>
			<td><?php esc_html_e( 'Row slider background', 'videofly' ); ?></td>
			<td>
				<select name="slider-background" id="slider-background">
				<?php 	$args = array(
                            'post_type' => 'ts_slider',
                            'posts_per_page' => -1,
                            'post_status' =>'publish'
                        );
                                
                        $sliders = new WP_Query( $args );
				?>
					<option value="no"><?php esc_html_e('None', 'videofly') ?></option>
					<?php if ($sliders->have_posts()) : ?>
						<?php while ( $sliders->have_posts() ) : $sliders->the_post(); ?>
							<option value="<?php echo get_the_ID(); ?>"><?php echo get_the_title(); ?></option>
                        <?php endwhile; ?>              
					<?php endif; ?>
				</select>
			</td>
		</tr>
	<?php endif; ?>
	<tr>
		<td><?php esc_html_e( 'Expand row', 'videofly' ); ?> </td>
		<td>
			<ul class="imageRadioMetaUl perRow-4 ts-custom-selector" data-selector="#expand-row" id="expand-row-selector">
               <li><img class="image-radio-input clickable-element" data-option="yes" src="<?php echo get_template_directory_uri().'/images/options/expand_row_yes.png'; ?>"></li>
               <li><img class="image-radio-input clickable-element" data-option="no" src="<?php echo get_template_directory_uri().'/images/options/expand_row_no.png'; ?>"></li>
            </ul>
			<select class="hidden" name="expand-row" id="expand-row">
				<option value="yes"><?php esc_html_e('Yes', 'videofly') ?></option>
				<option value="no"><?php esc_html_e('No', 'videofly') ?></option>
			</select>
			<div class="ts-option-description">
			    <?php esc_html_e('Once you decide to expand this row, all the content inside will go from one edge of the screen to another - if you are not using the boxed version. Mostly used for sliders.', 'videofly'); ?>.
			</div>
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Fullscreen row', 'videofly' ); ?> </td>
		<td>
			<ul class="imageRadioMetaUl perRow-4 ts-custom-selector" data-selector="#fullscreen-row" id="fullscreen-row-selector">
               <li><img class="image-radio-input clickable-element" data-option="no" src="<?php echo get_template_directory_uri().'/images/options/fullscreen_row_no.png'; ?>"></li>
               <li><img class="image-radio-input clickable-element" data-option="yes" src="<?php echo get_template_directory_uri().'/images/options/fullscreen_row_yes.png'; ?>"></li>
            </ul>
			<select name="fullscreen-row" id="fullscreen-row">
				<option value="no"><?php esc_html_e('No', 'videofly') ?></option>
				<option value="yes"><?php esc_html_e('Yes', 'videofly') ?></option>
			</select>

			<div class="ts-option-description">
			    <?php esc_html_e('If you want a row that will cover the whole screen, this is the option. Once you set it to fullscreen it will resize the row so that it fits the screen of the user. This will not expand your content though.', 'videofly'); ?>.
			</div>
		</td>
	</tr>
	<tr class="ts_vertical_align">
		<td><?php esc_html_e( 'Vertical align', 'videofly' ); ?></td>
		<td>
			<select name="row-vertical-align" id="row-vertical-align">
				<option value="top"><?php esc_html_e('Top', 'videofly') ?></option>
				<option value="middle"><?php esc_html_e('Middle', 'videofly') ?></option>
				<option value="bottom"><?php esc_html_e('Bottom', 'videofly') ?></option>
			</select>

			<div class="ts-option-description">
			    <?php esc_html_e('If you have the fullscreen row option selected, you can decide where should the content from this row be aligned vertically. You can center it, keep it on the top or stick it to the bottom.', 'videofly'); ?>.
			</div>
		</td>
	</tr>
	<tr class="ts_scroll_nag">
		<td><?php esc_html_e( 'Scroll down button', 'videofly' ); ?></td>
		<td>
			<select name="scroll-down-button" id="scroll-down-button">
				<option value="no"><?php esc_html_e('No', 'videofly') ?></option>
				<option value="yes"><?php esc_html_e('Yes', 'videofly') ?></option>
			</select>

			<div class="ts-option-description">
			    <?php esc_html_e('This will add a small buttom at the bottom of the row that will show the users to scroll down and once clicked, it will scroll the browser just below the row.', 'videofly'); ?>.
			</div>
		</td>
	</tr>
	<tr>
		<td><?php esc_html_e( 'Custom css', 'videofly' ); ?></td>
		<td>
			<textarea name="row-custom-css" id="row-custom-css" cols="50" rows="5"></textarea>
		</td>
	</tr>
</table>
<input type="button" class="button-primary save-element" value="<?php esc_html_e( 'Save changes', 'videofly' ); ?>" id="save-row-settings"/>
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
		bgPositionX = row.attr("data-bg-position-x") ? row.attr("data-bg-position-x") : '',
		bgPositionY = row.attr("data-bg-position-y") ? row.attr("data-bg-position-y") : '',
		bgAttachement = row.attr("data-bg-attachment") ? row.attr("data-bg-attachment") : '',
		bgRepeat = row.attr("data-bg-repeat") ? row.attr("data-bg-repeat") : '',
		bgSize = row.attr("data-bg-size") ? row.attr("data-bg-size") : '',
		rowMarginTop = row.attr("data-margin-top") ? row.attr("data-margin-top") : '0',
		rowMarginBottom = row.attr("data-margin-bottom") ? row.attr("data-margin-bottom") : '30',
		rowPaddingTop = row.attr("data-padding-top") ? row.attr("data-padding-top") : '0',
		rowPaddingBottom = row.attr("data-padding-bottom") ? row.attr("data-padding-bottom") : '0',
		expandRow = row.attr("data-expand-row") ? row.attr("data-expand-row") : 'no',
		specialEffects = row.attr("data-special-effects") ? row.attr("data-special-effects") : 'none',
		rowTextAlign = row.attr("data-text-align") ? row.attr("data-text-align") : 'auto',
		fullscreenRow = row.attr("data-fullscreen-row") ? row.attr("data-fullscreen-row") : 'no',
		rowMask = row.attr("data-mask") ? row.attr("data-mask") : 'no',
		rowShadow = row.attr("data-shadow") ? row.attr("data-shadow") : 'no',
		rowVerticalAlign = row.attr("data-vertical-align") ? row.attr("data-vertical-align") : 'top',
		scrollDownButton = row.attr("data-scroll-down-button") ? row.attr("data-scroll-down-button") : 'no',
		rowParallax = row.attr("data-parallax") ? row.attr("data-parallax") : 'no',
		customCss = row.attr("data-custom-css") ? row.attr("data-custom-css") : '',
		borderTop = row.attr("data-border-top") ? row.attr("data-border-top") : 'n',
		borderBottom = row.attr("data-border-bottom") ? row.attr("data-border-bottom") : 'n',
		borderTopColor = row.attr("data-border-top-color") ? row.attr("data-border-top-color") : '#fff',
		borderBottomColor = row.attr("data-border-bottom-color") ? row.attr("data-border-bottom-color") : '#fff',
		borderTopWidth = row.attr("data-border-top-width") ? row.attr("data-border-top-width") : '3',
		borderBottomWidth = row.attr("data-border-bottom-width") ? row.attr("data-border-bottom-width") : '3',
		rowCarousel = row.attr("data-carousel") ? row.attr("data-carousel") : 'no',
		sliderBackground = row.attr("data-slider-background") ? row.attr("data-slider-background") : 'no',
		gradientMaskMode = row.attr("data-mask-gradient-mode") ? row.attr("data-mask-gradient-mode") : 'radial',
		rowMaskGradient = row.attr("data-mask-gradient") ? row.attr("data-mask-gradient") : '#fff';
		transparent = row.attr("data-transparent") ? row.attr("data-transparent") : 'y';

	
		// repopulate row settings
		modalWindow.find('#row-name-id').val(rowName);
		modalWindow.find('#row-background-color').val(bgColor);
		modalWindow.find('#row-text-color').val(textColor);
		modalWindow.find('#row-mask-color').val(rowMaskColor);
		modalWindow.find('#row-opacity').val(rowOpacity);
		ts_slider_pips(1, 100, 1, rowOpacity, 'row-opacity-slider', 'row-opacity');
		modalWindow.find('#row-bg-image').val(bgImage);
		modalWindow.find('#row-bg-video-mp').val(bgVideoMp);
		modalWindow.find('#row-bg-video-webm').val(bgVideoWebm);
		modalWindow.find('#row-custom-css').val(customCss);
		modalWindow.find('#row-border-top').val(borderTop);
		modalWindow.find('#row-border-bottom').val(borderBottom);
		modalWindow.find('#row-color-border-top').val(borderTopColor);
		modalWindow.find('#row-color-border-bottom').val(borderBottomColor);
		ts_slider_pips(1, 15, 1, borderTopWidth, 'row-width-border-top-slider', 'row-width-border-top');
		ts_slider_pips(1, 15, 1, borderBottomWidth, 'row-width-border-bottom-slider', 'row-width-border-bottom');
		modalWindow.find('#row-columns-in-carousel').val(rowCarousel);
		modalWindow.find('#row-mask-gradient-color').val(rowMaskGradient);

		modalWindow.find('#row-transparent option').filter(function() {
			return $(this).val() == transparent;
		}).prop('selected', true);

		modalWindow.find('#row-mask-gradient-mode option').filter(function() {
			return $(this).val() == gradientMaskMode; 
		}).prop('selected', true);

		modalWindow.find('#row-bg-position-x option').filter(function() {
			return $(this).val() == bgPositionX; 
		}).prop('selected', true);

		modalWindow.find('#row-bg-position-y option').filter(function() {
			return $(this).val() == bgPositionY; 
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

		modalWindow.find('#scroll-down-button option').filter(function() {
			return $(this).val() == scrollDownButton; 
		}).prop('selected', true);

		modalWindow.find('#slider-background option').filter(function() {
			return $(this).val() == sliderBackground; 
		}).prop('selected', true);

		modalWindow.find('#row-parallax option').filter(function() {
			return $(this).val() == rowParallax; 
		}).prop('selected', true);

		function ts_show_proprety_mask(){

			$('#ts_mask_color').hide();
			$('#ts_mask_opacity').hide();
			$('#mask-color-gradient').hide();

			$('#row-mask').change(function(){
				if( $(this).val() == 'no' ){
					$('#ts_mask_color').hide();
					$('#ts_mask_opacity').hide();
					$('#mask-color-gradient').hide();
					$('#ts-row-mask-gradient-mode').hide();
				}else if( $(this).val() == 'gradient' ){
					$('#ts_mask_color').show();
					$('#ts_mask_opacity').show();
					$('#mask-color-gradient').show();
					$('#ts-row-mask-gradient-mode').show();
				}else{
					$('#ts_mask_color').show();
					$('#ts_mask_opacity').show();
					$('#mask-color-gradient').hide();
					$('#ts-row-mask-gradient-mode').hide();
				}
			});

			if( $('#row-mask').val() == 'yes' ){
				$('#ts_mask_color').show();
				$('#ts_mask_opacity').show();
				$('#mask-color-gradient').hide();
				$('#ts-row-mask-gradient-mode').hide();
			}else if( $('#row-mask').val() == 'gradient' ){
				$('#ts_mask_color').show();
				$('#ts_mask_opacity').show();
				$('#mask-color-gradient').show();
				$('#ts-row-mask-gradient-mode').show();
			}else{
				$('#ts_mask_color').hide();
				$('#ts_mask_opacity').hide();
				$('#mask-color-gradient').hide();
				$('#ts-row-mask-gradient-mode').hide();
			}

			if( jQuery('#row-gradient').val() == 'y' ){
				jQuery('#ts-gradient-color').show();
				jQuery('#ts-row-gradient-mode').show();
			}else{
				jQuery('#ts-gradient-color').hide();
				jQuery('#ts-row-gradient-mode').hide();
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

        jQuery('.ts-custom-selector').each(function(){
            var data_select = jQuery(this).attr('data-selector');
            jQuery(data_select).addClass('hidden');
        });

		ts_restart_color_picker();
		
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

		function ts_show_hide_fullscreen_row_options(){

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

			jQuery('#fullscreen-row').change(function(){
				if( jQuery(this).val() == 'no' ){
					jQuery('.ts_scroll_nag').css({'display':'none'});
				}else{
					jQuery('.ts_scroll_nag').css({'display':''});
				}
			});

			if( jQuery('#fullscreen-row').val() == 'no' ){
				jQuery('.ts_scroll_nag').css({'display':'none'});
			}else{
				jQuery('.ts_scroll_nag').css({'display':''});
			}

		}
		ts_show_hide_fullscreen_row_options();


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

		function tsShowOptionsBorders(border){
			jQuery('#row-border-' + border).change(function(){
				if( jQuery(this).val() == 'y' ){
					jQuery('#ts-row-color-border-' + border).show();
					jQuery('#ts-row-width-border-' + border).show();
				}else{
					jQuery('#ts-row-color-border-' + border).hide();
					jQuery('#ts-row-width-border-' + border).hide();
				}
			});

			if( jQuery('#row-border-' + border).val() == 'y' ){
				jQuery('#ts-row-color-border-' + border).show();
				jQuery('#ts-row-width-border-' + border).show();
			}else{
				jQuery('#ts-row-color-border-' + border).hide();
				jQuery('#ts-row-width-border-' + border).hide();
			}
		}
		tsShowOptionsBorders('top');
		tsShowOptionsBorders('bottom');

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
