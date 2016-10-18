<?php

function ts_register_buttons( $buttons ){
    array_push( $buttons, 'separator', 'ts_pushortcodes' );
    return $buttons;
}

function ts_shortcode_button(){
    if( current_user_can('edit_posts') &&  current_user_can('edit_pages') )
    {
        add_filter( 'mce_external_plugins', 'ts_add_buttons');
        add_filter( 'mce_buttons', 'ts_register_buttons');
    }
}
add_action('admin_init', 'ts_shortcode_button');

function ts_add_buttons( $plugin_array ){
    $plugin_array['ts_pushortcodes'] = get_template_directory_uri() . '/includes/ts-shortcode/ts-shortcode-tinymce-button.js';

    return $plugin_array;
}


function ts_get_modal(){
    echo    '<div id="ts-shortcode-elements-modal-preloader"></div>
            <div class="modal ts-modal fade" id="ts-shortcode-elements-modal" tabindex="-1" role="dialog" aria-labelledby="ts-shortcode-elements-modal-label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="ts-shortcode-elements-modal-label">' . __('shortcode elements', 'touchsize') . '</h4>
                        </div>
                        <div class="modal-body">
                        </div>
                    </div>
                </div>
            </div>';
}
add_action('admin_footer', 'ts_get_modal');

function get_all_icons(){
    $icons_array = get_option('videotouch_typography',array());
    $icons_li = '';
    $icons_options = '';
    $ts_icons = array();
    $icons_array['icons'] = explode(',',$icons_array['icons']);

    if( isset($icons_array['icons']) && $icons_array['icons'] != '' ){
        foreach ($icons_array['icons'] as $value) {
            $icons_li .= '<li><i class="'. $value .' clickable-element" data-option="'. $value .'"></i></li>';
            $icons_options .= '<option value="'. $value .'"></option>';
        }

        $ts_icons['icons_li'] = $icons_li;
        $ts_icons['icons_options'] = $icons_options;

        return $ts_icons;
        
    }else{
        $ts_icons['icons_li'] = '';
        $ts_icons['icons_options'] = '';

        return $ts_icons;
    }
    
}

//action for icons shortcode
function button_callback() {
?>
    <div class="shorcode-button" data-name-element="button">
        <h3 class="element-title"><?php _e( 'Button element', 'touchsize' ); ?></h3>

        <p><?php _e("Choose your icon from the library below:", "touchsize"); ?></p>
        <div class="builder-element-icon-toggle">
            <a href="#" class="red-ui-button builder-element-icon-trigger-btn" data-toggle="#builder-element-icon-selector"><?php _e("Show icons","touchsize") ?></a>
        </div>    
        <ul id="builder-element-icon-selector" data-selector="#builder-element-icon" class="builder-icon-list ts-custom-selector">
            <?php $ts_icons = get_all_icons(); echo $ts_icons['icons_li']; ?>
        </ul>
        <select name="builder-element-icon" id="builder-element-icon" class="hidden">
           <?php echo $ts_icons['icons_options']; ?>
        </select>
        
        <h3><?php _e( 'Buttons option', 'touchsize' ); ?></h3>               
        <table cellpadding="10">
            <tr>
                <td>
                    <?php _e('Text', 'touchsize') ?>
                </td>
                <td>
                   <input type="text" id="shortcode-button-text" name="shortcode-button-text" />
                </td>
            </tr>
            <tr>
                <td>
                    <?php _e('URL', 'touchsize') ?>:
                </td>
                <td>
                   <input type="text" id="shortcode-button-url" name="shortcode-button-url" value=""/>
                </td>
            </tr>
            <tr>
                <td>
                    <?php _e('Target', 'touchsize') ?>:
                </td>
                <td>
                    <select name="shortcode-button-target" id="shortcode-button-target">
                        <option value="_blank">_blank</option>
                        <option value="_self">_self</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <?php _e('Size:', 'touchsize') ?>
                </td>
                <td>
                   <select name="shortcode-button-size" id="shortcode-button-size">
                       <option value="big"><?php _e('Big', 'touchsize') ?></option>
                       <option value="medium" selected="selected"><?php _e('Medium', 'touchsize') ?></option>
                       <option value="small"><?php _e('Small', 'touchsize') ?></option>
                       <option value="xsmall"><?php _e('xSmall', 'touchsize') ?></option>
                   </select>
                </td>
            </tr>
            <tr>
                <td>
                    <?php _e('Text color:', 'touchsize') ?>
                </td>
                <td>
                   <input class="colors-section-picker" type="text" id="shortcode-button-text_color" name="shortcode-button-text-color" value="#FFFFFF"/>
                   <div class="colors-section-picker-div"></div>
                </td>
            </tr>
             <tr>
                <td>
                    <?php _e('Choose your mode to display:', 'touchsize') ?>
                </td>
                <td>
                   <select name="shortcode-button-mode-dispaly" id="shortcode-button-mode_display">
                       <option value="border-button"><?php _e('Border button', 'touchsize') ?></option>
                       <option value="background-button"><?php _e('Background button', 'touchsize') ?></option>
                   </select>
                </td>
            </tr>
            <tr class="shortcode-button-background-color">
                <td>
                    <?php _e('Background color', 'touchsize') ?>:
                </td>
                <td>
                   <input class="colors-section-picker" type="text" id="shortcode-button-background_color" name="shortcode-button-background-color" value="#FFFFFF"/>
                   <div class="colors-section-picker-div"></div>
                </td>
            </tr>
            <tr class="shortcode-button-border-color">
                <td>
                    <?php _e('Border color', 'touchsize') ?>:
                </td>
                <td>
                   <input class="colors-section-picker" type="text" id="shortcode-button-border_color" name="shortcode-button-border-color" value="#FFFFFF"/>
                   <div class="colors-section-picker-div"></div>
                </td>
            </tr>
        </table>
    </div>
    <input type="button" class="button-primary save-element" value="<?php _e( 'Save changes', 'touchsize' ) ?>" id="shortcode-save"/>
    <script>
        jQuery(document).ready(function(){
            jQuery('#shortcode-button-mode_display').change(function(){
                if( jQuery(this).val() === 'background-button' ){
                    jQuery('.shortcode-button-border-color').css('display', 'none');
                    jQuery('.shortcode-button-background-color').css('display', '');
                }else{
                    jQuery('.shortcode-button-background-color').css('display', 'none');
                    jQuery('.shortcode-button-border-color').css('display', '');
                }
            });

            if( jQuery('#shortcode-button-mode_display').val() === 'background-button' ){
                jQuery('.shortcode-button-border-color').css('display', 'none');
                jQuery('.shortcode-button-background-color').css('display', '');
            }else{
                jQuery('.shortcode-button-background-color').css('display', 'none');
                jQuery('.shortcode-button-border-color').css('display', '');
            }

            var pickers = jQuery('.colors-section-picker-div');
            jQuery.each(pickers, function(index, value){
                jQuery(this).farbtastic(jQuery(this).prev());
            });
        });
    </script>
<?php
    die();
}// end function icon_callback
add_action('wp_ajax_button', 'button_callback');

function ts_button_shortcode($atts) {
    extract( shortcode_atts( array(
        'size'         => '',
        'icon'         => '',
        'mode_display' => '',
        'border_color' => '',
        'button_align' => '',
        'url'          => '',
        'bg_color'     => '',
        'text_color'   => '',
        'target'       => '',
        'text'         => ''
    ), $atts) );
    
    $ts_button_options = array('button-icon' => $icon, 'size' => $size, 'mode-display' => $mode_display, 'text-color' => $text_color, 'bg-color' => $bg_color, 'url' => $url, 'button-align' =>'', 'border-color' => $border_color, 'text' => $text, 'target' => $target);
    return LayoutCompilator::buttons_element($ts_button_options);
}
add_shortcode( 'button', 'ts_button_shortcode' );

//action for icons shortcode
function icon_callback() {
?>
    <div class="shorcode-icon" data-name-element="icon">
        <h3 class="element-title"><?php _e( 'Icon element', 'touchsize' ) ?></h3>
        <p><?php _e("Choose your icon from the library below:", "touchsize"); ?></p>
        <div class="builder-element-icon-toggle">
            <a href="#" class="red-ui-button builder-element-icon-trigger-btn" data-toggle="#builder-element-icon-selector"><?php _e("Show icons","touchsize") ?></a>
        </div>    
        <ul id="builder-element-icon-selector" data-selector="#builder-element-icon" class="builder-icon-list ts-custom-selector">
            <?php $ts_icons = get_all_icons(); echo $ts_icons['icons_li']; ?>
        </ul>
        <select name="builder-element-icon" id="builder-element-icon" class="hidden">
           <?php echo $ts_icons['icons_options']; ?>
        </select>
        
        <h3><?php _e( 'Icon options', 'touchsize' ) ?></h3>               
        <table>
            <tr>
                <td>
                    <label for="shortcode-icon-size"><?php _e( 'Select your icon size', 'touchsize' ) ?></label>
                </td>
                <td>
                    <input type="text" id="shortcode-icon-size" name="shortcode-icon-size" value="<?php $default_size = fields::get_options_value('videotouch_typography','primary_text', true); if( isset($default_size['font_size']) ) echo $default_size['font_size']; ?>" />px
                </td>
            </tr>
        </table>
        <input id="shortcode-icon-display" type="hidden" value="true" name="shortcode-icon-display">
    </div>
    <input type="button" class="button-primary save-element" value="<?php _e( 'Save changes', 'touchsize' ) ?>" id="shortcode-save"/>
<?php
    die();
}// end function icon_callback
add_action('wp_ajax_icon', 'icon_callback');

function ts_icon_shortcode($atts) {
    extract( shortcode_atts( array(
        'size'  => '',
        'icon'  => '',
        'display' => true
    ), $atts) );
    $ts_icons_options = array('ts_shortcode' => true, 'icon' => $icon, 'icon-size' => $size, 'display' => $display);
    return LayoutCompilator::icon_element($ts_icons_options);
}
add_shortcode( 'icon', 'ts_icon_shortcode' );

//shortcode for item menu colums
function ts_one_half_shortcode($atts, $content = null){
    return '<div class="col-lg-6 col-md-6 col-sm-12">'. do_shortcode($content) .'</div>';
}
add_shortcode( 'ts_one_half', 'ts_one_half_shortcode' );

function ts_one_third_shortcode($atts, $content = null){
    return '<div class="col-lg-4 col-md-4 col-sm-12">'. do_shortcode($content) .'</div>';
}
add_shortcode( 'ts_one_third', 'ts_one_third_shortcode' );

function ts_two_third_shortcode($atts, $content = null){
    return '<div class="col-lg-8 col-md-8 col-sm-12">'. do_shortcode($content) .'</div>';
}
add_shortcode( 'ts_two_third', 'ts_two_third_shortcode' );

function ts_one_fourth_shortcode($atts, $content = null){
    return '<div class="col-lg-3 col-md-3 col-sm-12">'. do_shortcode($content) .'</div>';
}
add_shortcode( 'ts_one_fourth', 'ts_one_fourth_shortcode' );

function ts_row_shortcode($atts, $content = null){
    return '<div class="row">'. do_shortcode($content).'</div>';
}
add_shortcode( 'ts_row', 'ts_row_shortcode' );

//function ajax for shortcode image carousel
function image_carousel_callback(){
?>
<div class="shortcode-image_carousel" data-name-element="image_carousel">
    <h3 class="element-title"><?php _e( 'Image carousel element', 'touchsize' ) ?></h3>
    <table cellpadding="10">
        <tr>
            <td valign="top"><label for="image_url"><?php _e( 'Add your images', 'touchsize' ) ?>:</label></td>
            <td>

                <div id="shortcode-image_carousel">
                    <ul class="carousel_images">
                        
                    </ul>
                    <script>
                        jQuery(document).ready(function($){
                            setTimeout(function(){
                                //Show the added images
                                var image_gallery_ids = jQuery('#carousel_image_gallery').val();
                                var carousel_images = jQuery('#shortcode-image_carousel ul.carousel_images');

                                // Split each image
                                image_gallery_ids = image_gallery_ids.split(',');

                                jQuery(image_gallery_ids).each(function(index, value){
                                    image_url = value.split(/:(.*)/);
                                    if( image_url != '' ){
                                        image_url_path = image_url[1].split('.');
                                        carousel_images.append('\
                                            <li class="image" data-attachment_id="' + image_url[0] + '" data-attachment_url="' + image_url_path[0] + '.' +image_url_path[1] + '">\
                                                <img src="' + image_url_path[0] + '-<?php echo get_option( "thumbnail_size_w" ); ?>x<?php echo get_option( "thumbnail_size_h" ); ?>.' + image_url_path[1] + '" />\
                                                <ul class="actions">\
                                                    <li><a href="#" class=" icon-close" title="<?php _e( 'Delete image', 'touchsize' ); ?>"><?php //_e( 'Delete', 'touchsize' ); ?></a></li>\
                                                </ul>\
                                            </li>');
                                    }
                                });

                            },200);
                        });
                    </script>
                    <input type="hidden" id="carousel_image_gallery" name="carousel_image_gallery" value="" />
                </div>
                <p class="add_carousel_images hide-if-no-js">
                    <a href="#"><?php _e( 'Add gallery images', 'touchsize' ); ?></a>
                </p>
                <script type="text/javascript">
                    jQuery(document).ready(function($){

                        // Uploading files
                        var image_frame;
                        var $image_gallery_ids = $('#carousel_image_gallery');
                        var $carousel_images = $('#shortcode-image_carousel ul.carousel_images');

                        jQuery('.add_carousel_images').on( 'click', 'a', function( event ) {

                            var $el = $(this);
                            var attachment_ids = $image_gallery_ids.val();

                            event.preventDefault();

                            // If the media frame already exists, reopen it.
                            if ( image_frame ) {
                                image_frame.open();
                                return;
                            }

                            // Create the media frame.
                            image_frame = wp.media.frames.downloadable_file = wp.media({
                                // Set the title of the modal.
                                title: '<?php _e( 'Add Images to Gallery', 'touchsize' ); ?>',
                                button: {
                                    text: '<?php _e( 'Add to gallery', 'touchsize' ); ?>',
                                },
                                multiple: true
                            });

                            // When an image is selected, run a callback.
                            image_frame.on( 'select', function() {
                                
                                var selection = image_frame.state().get('selection');

                                selection.map( function( attachment ) {

                                    attachment = attachment.toJSON();
                                    if ( attachment.id ) {
                                        attachment_ids = attachment_ids + attachment.id + ':' + attachment.url + ',';

                                        $carousel_images.append('\
                                            <li class="image" data-attachment_id="' + attachment.id + '" data-attachment_url="' + attachment.url + '">\
                                                <img src="' + attachment.url + '" />\
                                                <ul class="actions">\
                                                    <li><a href="#" class=" icon-close" title="<?php _e( 'Delete image', 'touchsize' ); ?>"><?php //_e( 'Delete', 'touchsize' ); ?></a></li>\
                                                </ul>\
                                            </li>');
                                    }

                                } );

                                $image_gallery_ids.val( attachment_ids );
                            });

                            // Finally, open the modal.
                            image_frame.open();
                        });

                        // Image ordering
                        $carousel_images.sortable({
                            items: 'li.image',
                            cursor: 'move',
                            scrollSensitivity:40,
                            forcePlaceholderSize: true,
                            forceHelperSize: false,
                            helper: 'clone',
                            opacity: 0.65,
                            placeholder: 'wc-metabox-sortable-placeholder',
                            start:function(event,ui){
                                ui.item.css('background-color','#f6f6f6');
                            },
                            stop:function(event,ui){
                                ui.item.removeAttr('style');
                            },
                            update: function(event, ui) {
                                var attachment_ids = '';

                                $('#shortcode-image_carousel ul li.image').css('cursor','default').each(function() {
                                    var attachment_id = jQuery(this).attr( 'data-attachment_id' );
                                    attachment_url = jQuery(this).attr( 'data-attachment_url' );
                                    attachment_ids = attachment_ids + attachment_id + ':' + attachment_url + ',';
                                });

                                $image_gallery_ids.val( attachment_ids );
                            }
                        });

                        // Remove images
                        $('#shortcode-image_carousel').on( 'click', 'a.icon-close', function() {

                            $(this).closest('li.image').remove();

                            var attachment_ids = '';

                            $('#shortcode-image_carousel ul li.image').css('cursor','default').each(function() {
                                var attachment_id = jQuery(this).attr( 'data-attachment_id' );
                                var attachment_url = jQuery(this).attr( 'data-attachment_url' );
                                attachment_ids = attachment_ids + attachment_id + ':' + attachment_url + ',';
                            });

                            $image_gallery_ids.val( attachment_ids );

                            return false;
                        } );
                    });
                </script>
            </td>
        </tr>
    </table>
</div>
<input type="button" class="button-primary save-element" value="<?php _e( 'Save changes', 'touchsize' ) ?>" id="shortcode-save"/>
<?php 
die();
}// end function image_carousel_callback
add_action('wp_ajax_image_carousel', 'image_carousel_callback');
 
function ts_image_carousel_shortcode($atts, $content = null){

    return LayoutCompilator::image_carousel_element($atts);
}
add_shortcode( 'image_carousel', 'ts_image_carousel_shortcode' );

//function for ajax shortcode toggle
function toggle_callback(){
?>
<div class="shortcode-toggle" data-name-element="toggle">
    <h3 class="element-title"><?php _e( 'Toggle element:', 'touchsize' ) ?></h3>
    <table cellpadding="10">
        <tr>
            <td>
                <?php _e( 'Enter your title:', 'touchsize' ) ?>
            </td>
            <td>
                <input type="text" name="shortcode-toggle-title" id="shortcode-toggle-title" value=''/>
            </td>
        </tr>
        <tr>
            <td>
                <?php _e( 'State (opened/closed)', 'touchsize' ) ?>:
            </td>
            <td>
                <select name="shortcode-toggle-state" id="shortcode-toggle-state">
                    <option value="open"><?php _e( 'Open', 'touchsize' ) ?></option>
                    <option value="closed"><?php _e( 'Closed', 'touchsize' ) ?></option>
                </select>
            </td>
        </tr>
    </table>
</div>
<input type="button" class="button-primary save-element" value="<?php _e( 'Save changes', 'touchsize' ) ?>" id="shortcode-save"/>
<?php
die();
}//end function toggle_callback
add_action('wp_ajax_toggle', 'toggle_callback');

function ts_toggle_shortcode($atts, $content = null){
    extract( shortcode_atts( array(
        'title'  => '',
        'state' => ''
    ), $atts));
    $ts_toggle_options = array('toggle-title' => $title, 'toggle-state' => $state, 'toggle-description' => $content);
    return LayoutCompilator::toggle_element($ts_toggle_options);
}
add_shortcode( 'toggle', 'ts_toggle_shortcode' );

function tab_callback(){
?>
<div class="shortcode-tab" data-name-element="tab">
    <h3 class="element-title"><?php _e( 'Tabs', 'touchsize' ) ?></h3>
        <ul id="shortcode-tab_items">
       
        </ul>
       
    <input type="hidden" id="shortcode-tab_content" value="" />
    <input type="button" class="button ts-multiple-add-button" data-element-name="shortcode-tab" id="shortcode-tab_add_item" value=" <?php _e('Add New Tab', 'touchsize'); ?>" />
      <?php
        echo '<script id="shortcode-tab_items_template" type="text/template">';
        echo '<li id="list-item-id-{{item-id}}" class="shortcode-tab-item ts-multiple-add-list-element">
                <div class="sortable-meta-element"><span class="shortcode-tab-arrow icon-down"></span> <span class="shortcode-tab-item-shortcode-tab ts-multiple-item-shortcode-tab">Item: {{slide-number}}</span></div>
                <div class="hidden">
                    <table>
                        <tr>
                            <td>
                                <label for="shortcode-tab-{{item-id}}-title">Title:</label>
                            </td>
                            <td>
                                <input data-builder-name="title" type="text" id="shortcode-tab-{{item-id}}-title" name="shortcode-tab[{{item-id}}][title]" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="shortcode-tab-{{item-id}}-text">Write your text here:</label>
                            </td>
                            <td>
                                <textarea data-builder-name="text" name="shortcode-tab[{{item-id}}][text]" id="shortcode-tab-{{item-id}}-text" cols="45" rows="5"></textarea>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" data-builder-name="item_id" value="{{item-id}}" name="shortcode[{{item-id}}][id]" />
                    <input type="button" class="button button-primary remove-item" value="' . __('Remove', 'touchsize') . '" />
                    <a href="#" data-item="shortcode-tab-item" data-increment="shortcode-tab-items" class="button button-primary ts-multiple-item-duplicate">'.__('Duplicate Item', 'touchsize').'</a>
                </div>
            </li>';
        echo '</script>';
   ?>
</div>
<input type="button" class="button-primary save-element" value="<?php _e( 'Save changes', 'touchsize' ) ?>" id="shortcode-save"/>
<?php
die();
}//end function tab_callback
add_action('wp_ajax_tab', 'tab_callback');

function ts_tabs_shortcode($atts, $option_text = null){
    $ts_tab_options = '[' . str_replace('}{', '},{', do_shortcode( $option_text )) . ']';
    $masiv['tab'] = $ts_tab_options;
    return LayoutCompilator::tab_element($masiv);
}
add_shortcode( 'ts_tabs', 'ts_tabs_shortcode' );

function ts_tab_shortcode($atts, $content = null){
    extract( shortcode_atts( array(
        'id'     => '',
        'title'  => ''
    ), $atts));

    $json_encode = '{"id":"'. $id . '","title":"' . str_replace('"', '--quote--', $title) . '","text":"' . str_replace('"', '--quote--', $content). '"}';

    return $json_encode;
}
add_shortcode( 'ts_tab', 'ts_tab_shortcode' );

//add shortcode for list
function ts_list_star_shortcode($atts, $content = null){
    return '<div class="ts-shortcode-list ts-star">'. $content .'</div>';
}
add_shortcode( 'star', 'ts_list_star_shortcode' );

function ts_list_arrow_shortcode($atts, $content = null){
    return '<div class="ts-shortcode-list ts-arrow">'. $content .'</div>';
}
add_shortcode( 'arrow', 'ts_list_arrow_shortcode' );

function ts_list_thumb_shortcode($atts, $content = null){
    return '<div class="ts-shortcode-list ts-thumb">'. $content .'</div>';
}
add_shortcode( 'thumb', 'ts_list_thumb_shortcode' );

function ts_list_question_shortcode($atts, $content = null){
    return '<div class="ts-shortcode-list ts-question">'. $content .'</div>';
}
add_shortcode( 'question', 'ts_list_question_shortcode' );

function ts_list_direction_shortcode($atts, $content = null){
    return '<div class="ts-shortcode-list ts-direction">'. $content .'</div>';
}
add_shortcode( 'direction', 'ts_list_direction_shortcode' );

function ts_list_tick_shortcode($atts, $content = null){
    return '<div class="ts-shortcode-list ts-tick">'. $content .'</div>';
}
add_shortcode( 'tick', 'ts_list_tick_shortcode' );
?>
