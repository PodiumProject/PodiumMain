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
                            <h4 class="modal-title" id="ts-shortcode-elements-modal-label">' . esc_html__('shortcode elements', 'videofly') . '</h4>
                        </div>
                        <div class="modal-body">
                        </div>
                    </div>
                </div>
            </div>';
}
add_action('admin_footer', 'ts_get_modal');

function get_all_icons(){
    $icons_array = get_option('videofly_typography',array());
    $icons_li = '';
    $icons_options = '';
    $vdf_icons = array();
    $icons_array['icons'] = explode(',',$icons_array['icons']);

    if( isset($icons_array['icons']) && $icons_array['icons'] != '' ){
        foreach ($icons_array['icons'] as $value) {
            $icons_li .= '<li><i class="'. $value .' clickable-element" data-option="'. $value .'"></i></li>';
            $icons_options .= '<option value="'. $value .'"></option>';
        }

        $vdf_icons['icons_li'] = $icons_li;
        $vdf_icons['icons_options'] = $icons_options;

        return $vdf_icons;
        
    }else{
        $vdf_icons['icons_li'] = '';
        $vdf_icons['icons_options'] = '';

        return $vdf_icons;
    }
    
}

//action for icons shortcode
function button_callback() {
?>
    <div class="shorcode-button" data-name-element="button">
        <h3 class="element-title"><?php esc_html_e( 'Button element', 'videofly' ); ?></h3>

        <p><?php esc_html_e('Choose your icon from the library below:','videofly'); ?></p>
        <div class="builder-element-icon-toggle">
            <a href="#" class="red-ui-button builder-element-icon-trigger-btn" data-toggle="#builder-element-icon-selector"><?php esc_html_e('Show icons','videofly') ?></a>
        </div>    
        <ul id="builder-element-icon-selector" data-selector="#builder-element-icon" class="builder-icon-list ts-custom-selector">
            <?php $vdf_icons = get_all_icons(); echo vdf_var_sanitize($vdf_icons['icons_li']); ?>
        </ul>
        <select name="builder-element-icon" id="builder-element-icon" class="hidden">
           <?php echo vdf_var_sanitize($vdf_icons['icons_options']); ?>
        </select>
        
        <h3><?php esc_html_e( 'Buttons option', 'videofly' ); ?></h3>               
        <table cellpadding="10">
            <tr>
                <td>
                    <?php esc_html_e('Text', 'videofly') ?>
                </td>
                <td>
                   <input type="text" id="shortcode-button-text" name="shortcode-button-text" />
                </td>
            </tr>
            <tr>
                <td>
                    <?php esc_html_e('URL', 'videofly') ?>:
                </td>
                <td>
                   <input type="text" id="shortcode-button-url" name="shortcode-button-url" value=""/>
                </td>
            </tr>
            <tr>
                <td>
                    <?php esc_html_e('Target', 'videofly') ?>:
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
                    <?php esc_html_e('Size:', 'videofly') ?>
                </td>
                <td>
                   <select name="shortcode-button-size" id="shortcode-button-size">
                       <option value="big"><?php esc_html_e('Big', 'videofly') ?></option>
                       <option value="medium" selected="selected"><?php esc_html_e('Medium', 'videofly') ?></option>
                       <option value="small"><?php esc_html_e('Small', 'videofly') ?></option>
                       <option value="xsmall"><?php esc_html_e('xSmall', 'videofly') ?></option>
                   </select>
                </td>
            </tr>
            <tr>
                <td>
                    <?php esc_html_e('Text color:', 'videofly') ?>
                </td>
                <td>
                   <input class="colors-section-picker" type="text" id="shortcode-button-text_color" name="shortcode-button-text-color" value="#FFFFFF"/>
                   <div class="colors-section-picker-div"></div>
                </td>
            </tr>
             <tr>
                <td>
                    <?php esc_html_e('Choose your mode to display:', 'videofly') ?>
                </td>
                <td>
                   <select name="shortcode-button-mode-dispaly" id="shortcode-button-mode_display">
                       <option value="border-button"><?php esc_html_e('Border button', 'videofly') ?></option>
                       <option value="background-button"><?php esc_html_e('Background button', 'videofly') ?></option>
                   </select>
                </td>
            </tr>
            <tr class="shortcode-button-background-color">
                <td>
                    <?php esc_html_e('Background color', 'videofly') ?>:
                </td>
                <td>
                   <input class="colors-section-picker" type="text" id="shortcode-button-bg_color" name="shortcode-button-bg-color" value="#FFFFFF"/>
                   <div class="colors-section-picker-div"></div>
                </td>
            </tr>
            <tr class="shortcode-button-border-color">
                <td>
                    <?php esc_html_e('Border color', 'videofly') ?>:
                </td>
                <td>
                   <input class="colors-section-picker" type="text" id="shortcode-button-border_color" name="shortcode-button-border-color" value="#FFFFFF"/>
                   <div class="colors-section-picker-div"></div>
                </td>
            </tr>
        </table>
    </div>
    <input type="button" class="button-primary save-element" value="<?php esc_html_e( 'Save changes', 'videofly' ); ?>" id="shortcode-save"/>
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
    
    $vdf_button_options = array('button-icon' => $icon, 'size' => $size, 'mode-display' => $mode_display, 'text-color' => $text_color, 'bg-color' => $bg_color, 'url' => $url, 'button-align' =>'', 'border-color' => $border_color, 'text' => $text, 'target' => $target, 'short' => true);
    return LayoutCompilator::buttons_element($vdf_button_options);
}
add_shortcode( 'button', 'ts_button_shortcode' );

//action for icons shortcode
function icon_callback() {
?>
    <div class="shorcode-icon" data-name-element="icon">
        <h3 class="element-title"><?php esc_html_e( 'Icon element', 'videofly' ); ?></h3>
        <p><?php esc_html_e('Choose your icon from the library below:','videofly'); ?></p>
        <div class="builder-element-icon-toggle">
            <a href="#" class="red-ui-button builder-element-icon-trigger-btn" data-toggle="#builder-element-icon-selector"><?php esc_html_e('Show icons','videofly') ?></a>
        </div>    
        <ul id="builder-element-icon-selector" data-selector="#builder-element-icon" class="builder-icon-list ts-custom-selector">
            <?php $vdf_icons = get_all_icons(); echo vdf_var_sanitize($vdf_icons['icons_li']); ?>
        </ul>
        <select name="builder-element-icon" id="builder-element-icon" class="hidden">
           <?php echo vdf_var_sanitize($vdf_icons['icons_options']); ?>
        </select>
        
        <h3><?php esc_html_e( 'Icon options', 'videofly' ); ?></h3>               
        <table>
            <tr>
                <td>
                    <label for="shortcode-icon-size"><?php esc_html_e( 'Select your icon size', 'videofly' ); ?></label>
                </td>
                <td>
                    <input type="text" id="shortcode-icon-size" name="shortcode-icon-size" value="<?php $default_size = fields::get_options_value('videofly_typography','primary_text', true); if( isset($default_size['font_size']) ) echo vdf_var_sanitize($default_size['font_size']); ?>" />px
                </td>
            </tr>
        </table>
        <input id="shortcode-icon-display" type="hidden" value="true" name="shortcode-icon-display">
    </div>
    <input type="button" class="button-primary save-element" value="<?php esc_html_e( 'Save changes', 'videofly' ); ?>" id="shortcode-save"/>
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
    $vdf_icons_options = array('ts_shortcode' => true, 'icon' => $icon, 'icon-size' => $size, 'display' => $display);
    return LayoutCompilator::icon_element($vdf_icons_options);
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
    <h3 class="element-title"><?php esc_html_e( 'Image carousel', 'videofly' ); ?></h3>
    <table cellpadding="10">
        <tr>
            <td valign="top"><label for="image_url"><?php esc_html_e( 'Add your images', 'videofly' ); ?>:</label></td>
            <td>
            <div class="inside">
                <div id="post_images_container">
                    <ul class="carousel_images">
                        
                    </ul>
                    <script>
                        jQuery(document).ready(function($){
                            setTimeout(function(){
                                //Show the added images
                                var image_gallery_ids = jQuery('#carousel_image_gallery').val();
                                var carousel_images = jQuery('#post_images_container ul.carousel_images');

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
                                                    <li><a href="#" class=" icon-close" title="<?php esc_html_e( 'Delete image', 'videofly' ); ?>"><?php //esc_html_e( 'Delete', 'videofly' ); ?></a></li>\
                                                </ul>\
                                            </li>');
                                    }
                                });

                            },200);
                        });
                    </script>
                    <input type="hidden" id="carousel_image_gallery" name="carousel_image_gallery" value="" />
                </div>
            </div>
                <p class="add_carousel_images hide-if-no-js">
                    <a href="#"><?php esc_html_e( 'Add carousel images', 'videofly' ); ?></a>
                </p>
                <script type="text/javascript">
                    jQuery(document).ready(function($){

                        // Uploading files
                        var image_frame;
                        var $image_gallery_ids = $('#carousel_image_gallery');
                        var $carousel_images = $('#post_images_container ul.carousel_images');

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
                                title: '<?php esc_html_e( 'Add Images to Gallery', 'videofly' ); ?>',
                                button: {
                                    text: '<?php esc_html_e( 'Add to gallery', 'videofly' ); ?>',
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
                                                    <li><a href="#" class=" icon-close" title="<?php esc_html_e( 'Delete image', 'videofly' ); ?>"><?php //esc_html_e( 'Delete', 'videofly' ); ?></a></li>\
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

                                $('#post_images_container ul li.image').css('cursor','default').each(function() {
                                    var attachment_id = jQuery(this).attr( 'data-attachment_id' );
                                    attachment_url = jQuery(this).attr( 'data-attachment_url' );
                                    attachment_ids = attachment_ids + attachment_id + ':' + attachment_url + ',';
                                });

                                $image_gallery_ids.val( attachment_ids );
                            }
                        });

                        // Remove images
                        $('#post_images_container').on( 'click', 'a.icon-close', function() {

                            $(this).closest('li.image').remove();

                            var attachment_ids = '';

                            $('#post_images_container ul li.image').css('cursor','default').each(function() {
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
<input type="button" class="button-primary save-element" value="<?php esc_html_e( 'Save changes', 'videofly' ); ?>" id="shortcode-save"/>
<?php 
die();
}// end function image_carousel_callback
add_action('wp_ajax_image_carousel', 'image_carousel_callback');
 
function ts_image_carousel_shortcode($atts, $content = null){
    $atts['shortcode-div-large'] = true;
    tsIncludeScripts(array('sly'));
    return LayoutCompilator::image_carousel_element($atts);
}
add_shortcode( 'image_carousel', 'ts_image_carousel_shortcode' );

//function for ajax shortcode toggle
function toggle_callback(){
?>
<div class="shortcode-toggle" data-name-element="toggle">
    <h3 class="element-title"><?php esc_html_e( 'Toggle element:', 'videofly' ); ?></h3>
    <table cellpadding="10">
        <tr>
            <td>
                <?php esc_html_e( 'Enter your title:', 'videofly' ); ?>
            </td>
            <td>
                <input type="text" name="shortcode-toggle-title" id="shortcode-toggle-title" value=''/>
            </td>
        </tr>
        <tr>
            <td>
                <?php esc_html_e( 'State (opened/closed)', 'videofly' ); ?>:
            </td>
            <td>
                <select name="shortcode-toggle-state" id="shortcode-toggle-state">
                    <option value="open"><?php esc_html_e( 'Open', 'videofly' ); ?></option>
                    <option value="closed"><?php esc_html_e( 'Closed', 'videofly' ); ?></option>
                </select>
            </td>
        </tr>
    </table>
</div>
<input type="button" class="button-primary save-element" value="<?php esc_html_e( 'Save changes', 'videofly' ); ?>" id="shortcode-save"/>
<?php
die();
}//end function toggle_callback
add_action('wp_ajax_toggle', 'toggle_callback');

function ts_toggle_shortcode($atts, $content = null){
    extract( shortcode_atts( array(
        'title'  => '',
        'state' => ''
    ), $atts));

    $toggle_options = array('toggle-title' => $title, 'toggle-state' => $state, 'toggle-description' => $content, 'shortcode-div-large' => true);
    tsIncludeScripts(array('bootstrap'));
    return LayoutCompilator::toggle_element($toggle_options);
}
add_shortcode( 'toggle', 'ts_toggle_shortcode' );

function tab_callback(){
?>
<div class="shortcode-tab" data-name-element="tab">
    <h3 class="element-title"><?php esc_html_e( 'Tabs', 'videofly' ); ?></h3>
    <ul id="shortcode-tab_items">
   
    </ul>
       
    <input type="hidden" id="shortcode-tab_content" value="" />
    <input type="button" class="button ts-multiple-add-button" data-element-name="shortcode-tab" id="shortcode-tab_add_item" value=" <?php esc_html_e('Add New Tab', 'videofly'); ?>" />
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
                    <input type="button" class="button button-primary remove-item" value="' . esc_html__('Remove', 'videofly') . '" />
                    <a href="#" data-item="shortcode-tab-item" data-increment="shortcode-tab-items" class="button button-primary ts-multiple-item-duplicate">'.esc_html__('Duplicate Item', 'videofly').'</a>
                </div>
            </li>';
        echo '</script>';
   ?>
</div>
<input type="button" class="button-primary save-element" value="<?php esc_html_e( 'Save changes', 'videofly' ); ?>" id="shortcode-save"/>
<?php
die();
}//end function tab_callback
add_action('wp_ajax_tab', 'tab_callback');

function ts_tabs_shortcode( $atts, $option_text = null )
{
    $ts_tab_options = '[' . str_replace( '}{', '},{', do_shortcode( $option_text ) ) . ']';

    $options['tab'] = $ts_tab_options;
    $options['short'] = true;
    
    $content = LayoutCompilator::tab_element( $options );
    
    return $content;
}
add_shortcode( 'ts_tabs', 'ts_tabs_shortcode' );

function ts_tab_shortcode($atts, $content = null){
    
    extract( shortcode_atts( array(
        'id'     => '',
        'title'  => ''
    ), $atts));

    $atts['text'] = str_replace( '"', '--quote--', $content );     
    $atts['title'] = str_replace( '"', '--quote--', $atts['title'] );  

    $json_encode = json_encode( $atts );

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


//action for alert shortcode
function alert_callback() {
?>
    <div class="alert" data-name-element="alert">
        <h3 class="element-title"><?php esc_html_e('Alert shortcode', 'videofly'); ?></h3>

        <p><?php esc_html_e("Choose your icon from the library below:", 'videofly'); ?></p>
        <div class="builder-element-icon-toggle">
            <a href="#" class="red-ui-button builder-element-icon-trigger-btn" data-toggle="#shortcode-alert-icon-selector"><?php esc_html_e('Show icons','videofly') ?></a>
        </div>    
        <ul id="shortcode-alert-icon-selector" data-selector="#builder-element-icon" class="builder-icon-list ts-custom-selector">
            <?php $vdf_icons = get_all_icons(); echo vdf_var_sanitize($vdf_icons['icons_li']); ?>
        </ul>
        <select name="shortcode-alert-icon" id="builder-element-icon" class="hidden">
            <?php echo vdf_var_sanitize($vdf_icons['icons_options']); ?> 
        </select>

        <table cellpadding="10">
            <tr>
                <td>
                    <?php esc_html_e('Title','videofly');?>
                </td>
                <td>
                   <input type="text" id="shortcode-alert-title" name="shortcode-alert-title" />
                </td>
            </tr>
            <tr>
                <td>
                    <?php esc_html_e('Background color', 'videofly') ?>:
                </td>
                <td>
                    <input type="text" id="shortcode-alert-background_color" class="colors-section-picker" value="#000" name="shortcode-alert-background-color" />
                    <div class="colors-section-picker-div"></div>
                </td>
            </tr> 
            <tr>
                <td>
                    <?php esc_html_e('Text color', 'videofly') ?>:
                </td>
                <td>
                    <input type="text" id="shortcode-alert-text_color" class="colors-section-picker" value="#000" name="shortcode-alert-text_color" />
                    <div class="colors-section-picker-div"></div>
                </td>
            </tr>  
        </table>
    </div>
    
    <input type="button" class="button-primary save-element" value="<?php esc_html_e( 'Save changes', 'videofly' ); ?>" id="shortcode-save"/>

    <script>
        jQuery(document).ready(function(){
            var pickers = jQuery('.colors-section-picker-div');
            jQuery.each(pickers, function(index, value){
                jQuery(this).farbtastic(jQuery(this).prev());
            });
        });
    </script>
<?php
    die();
}// end function icon_callback
add_action('wp_ajax_alert', 'alert_callback');

function ts_alert_shortcode($atts, $content = null) {
    extract( shortcode_atts( array(
        'icon'             => '',
        'title'            => '',
        'background_color' => '',
        'text_color'       => ''
    ), $atts) );
    
    $options = array('icon' => $icon, 'title' => $title, 'text' => $content, 'background-color' => $background_color, 'text-color' => $text_color, 'shortcode-div-large' => true);
    return LayoutCompilator::alert_element($options);
}
add_shortcode( 'alert', 'ts_alert_shortcode' );

//function ajax for shortcode image gallery
function image_gallery_callback(){
?>
<div class="shortcode-image_gallery" data-name-element="image_gallery">
    <h3 class="element-title"><?php esc_html_e( 'Image gallery element', 'videofly' ); ?></h3>
    <table cellpadding="10">
        <tr>
            <td valign="top"><label for="image_url"><?php esc_html_e( 'Add your images', 'videofly' ); ?>:</label></td>
            <td>
            <div class="inside">
                <div id="post_images_container">
                    <ul class="gallery_images">
                        
                    </ul>
                    <script>
                        jQuery(document).ready(function($){
                            setTimeout(function(){
                                //Show the added images
                                var image_gallery_ids = jQuery('#gallery_image_gallery').val();
                                var gallery_images = jQuery('#post_images_container ul.gallery_images');

                                // Split each image
                                image_gallery_ids = image_gallery_ids.split(',');

                                jQuery(image_gallery_ids).each(function(index, value){
                                    image_url = value.split(/:(.*)/);
                                    if( image_url != '' ){
                                        image_url_path = image_url[1].split('.');
                                        gallery_images.append('\
                                            <li class="image" data-attachment_id="' + image_url[0] + '" data-attachment_url="' + image_url_path[0] + '.' +image_url_path[1] + '">\
                                                <img src="' + image_url_path[0] + '-<?php echo get_option( "thumbnail_size_w" ); ?>x<?php echo get_option( "thumbnail_size_h" ); ?>.' + image_url_path[1] + '" />\
                                                <ul class="actions">\
                                                    <li><a href="#" class=" icon-close" title="<?php esc_html_e( 'Delete image', 'videofly' ); ?>"><?php //esc_html_e( 'Delete', 'videofly' ); ?></a></li>\
                                                </ul>\
                                            </li>');
                                    }
                                });

                            },200);
                        });
                    </script>
                    <input type="hidden" id="gallery_image_gallery" name="gallery_image_gallery" value="" />
                </div>
            </div>
                <p class="add_gallery_images hide-if-no-js">
                    <a href="#"><?php esc_html_e( 'Add gallery images', 'videofly' ); ?></a>
                </p>
                <script type="text/javascript">
                    jQuery(document).ready(function($){

                        // Uploading files
                        var image_frame;
                        var $image_gallery_ids = $('#gallery_image_gallery');
                        var $gallery_images = $('#post_images_container ul.gallery_images');

                        jQuery('.add_gallery_images').on( 'click', 'a', function( event ) {

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
                                title: '<?php esc_html_e( 'Add Images to Gallery', 'videofly' ); ?>',
                                button: {
                                    text: '<?php esc_html_e( 'Add to gallery', 'videofly' ); ?>',
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

                                        $gallery_images.append('\
                                            <li class="image" data-attachment_id="' + attachment.id + '" data-attachment_url="' + attachment.url + '">\
                                                <img style="max-width: 100%;" src="' + attachment.url + '" />\
                                                <ul class="actions">\
                                                    <li><a href="#" class=" icon-close" title="<?php esc_html_e( 'Delete image', 'videofly' ); ?>"><?php //esc_html_e( 'Delete', 'videofly' ); ?></a></li>\
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
                        $gallery_images.sortable({
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

                                $('#post_images_container ul li.image').css('cursor','default').each(function() {
                                    var attachment_id = jQuery(this).attr( 'data-attachment_id' );
                                    attachment_url = jQuery(this).attr( 'data-attachment_url' );
                                    attachment_ids = attachment_ids + attachment_id + ':' + attachment_url + ',';
                                });

                                $image_gallery_ids.val( attachment_ids );
                            }
                        });

                        // Remove images
                        $('#post_images_container').on( 'click', 'a.icon-close', function() {

                            $(this).closest('li.image').remove();

                            var attachment_ids = '';

                            $('#post_images_container ul li.image').css('cursor','default').each(function() {
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
<input type="button" class="button-primary save-element" value="<?php esc_html_e( 'Save changes', 'videofly' ); ?>" id="shortcode-save"/>
<?php 
die();
}// end function image_gallery_callback
add_action('wp_ajax_image_gallery', 'image_gallery_callback');
 
function ts_image_gallery_shortcode($atts, $content = null){
    tsIncludeScripts(array('fotorama'));
    $link_images = (isset($atts['images']) && !empty($atts['images'])) ? explode(',', $atts['images']) : '';
    if( $link_images !== '' && is_array($link_images) && !empty($link_images) ){
        $img = '';

        foreach($link_images as $image){
            $id_link_image = explode(':', $image, 2);
            if( is_array($id_link_image) && !empty($id_link_image) ){

                $alt = (isset($id_link_image[0]) && absint($id_link_image[0]) > 0) ? get_post_meta($id_link_image[0], '_wp_attachment_image_alt', true) : '';
                $src = (isset($id_link_image[1])) ? $id_link_image[1] : '';

                if( $src !== '' ) $img .= '<img src="' . esc_url($id_link_image[1]) . '" alt="' . esc_attr($alt) . '">';
            }
        }

        if( !empty($img) ){
            return  '<div class="fotorama" data-nav="thumbs">
                        ' . $img . '
                    </div>';
        }
    }
}
add_shortcode( 'image_gallery', 'ts_image_gallery_shortcode' );

//function ajax for shortcode chart
function chart_callback(){
 ?>
    <div class="shortcode-chart" data-name-element="chart">
        <h3 class="element-title"><?php esc_html_e('Chart shortcode', 'videofly'); ?></h3>
        
        <table cellpadding="10">
            <tr>
                <td>
                    <?php esc_html_e('Select mode to display chart', 'videofly'); ?>
                </td>
                <td>
                   <select name="shortcode-chart-mode" id="shortcode-chart-mode">
                       <option value="line"><?php esc_html_e('Line chart', 'videofly'); ?></option>
                       <option value="pie"><?php esc_html_e('Pie chart', 'videofly'); ?></option>
                   </select>
                </td>
            </tr>
        </table>
        <!-- Line chart options -->
        <div class="shortcode-chart-line-options">
            <table cellpadding="10">
                <tr>
                    <td>
                        <?php esc_html_e('Labels in format "label1,label2,label3,..."', 'videofly') ?>
                    </td>
                     <td>
                        <input type="text" id="shortcode-chart-label" name="shortcode-chart-label" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Title', 'videofly'); ?>
                    </td>
                     <td>
                        <input value="" type="text" id="shortcode-chart-title" name="shortcode-chart-title" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Show lines across the chart', 'videofly'); ?>
                    </td>
                    <td>
                        <select id="shortcode-chart-scaleShowGridLines" name="shortcode-chart-scaleShowGridLines">
                            <option selected="selected" value="true"><?php esc_html_e('Yes', 'videofly'); ?></option>
                            <option value="false"><?php esc_html_e('No', 'videofly'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Colour of the grid lines', 'videofly'); ?>
                    </td>
                    <td>
                        <input type="text" value="#f7f7f7" id="shortcode-chart-scaleGridLineColor" class="colors-section-picker" name="shortcode-chart-scaleGridLineColor" />
                        <div class="colors-section-picker-div"></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Width of the grid lines (INTEGER)', 'videofly'); ?>
                    </td>
                    <td>
                        <input type="text" id="shortcode-chart-scaleGridLineWidth" value="1" name="shortcode-chart-scaleGridLineWidth" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Show horizontal lines (except X axis)', 'videofly'); ?>
                    </td>
                    <td>
                        <select id="shortcode-chart-scaleShowHorizontalLines" name="shortcode-chart-scaleShowHorizontalLines">
                            <option selected="selected" value="true"><?php esc_html_e('Yes', 'videofly'); ?></option>
                            <option value="false"><?php esc_html_e('No', 'videofly'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Show vertical lines (except Y axis)', 'videofly'); ?>
                    </td>
                    <td>
                        <select id="shortcode-chart-scaleShowVerticalLines" name="shortcode-chart-scaleShowVerticalLines">
                            <option selected="selected" value="true"><?php esc_html_e('Yes', 'videofly'); ?></option>
                            <option value="false"><?php esc_html_e('No', 'videofly'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('The line is curved between points', 'videofly'); ?>
                    </td>
                    <td>
                        <select id="shortcode-chart-bezierCurve" name="shortcode-chart-bezierCurve">
                            <option selected="selected" value="true"><?php esc_html_e('Yes', 'videofly'); ?></option>
                            <option value="false"><?php esc_html_e('No', 'videofly'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Tension of the bezier curve between points (0.1 - 1)', 'videofly'); ?>
                    </td>
                    <td>
                        <input type="text" value="0.4" id="shortcode-chart-bezierCurveTension" name="shortcode-chart-bezierCurveTension" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Show a dot for each point', 'videofly'); ?>
                    </td>
                    <td>
                        <select id="shortcode-chart-pointDot" name="shortcode-chart-pointDot">
                            <option selected="selected" value="true"><?php esc_html_e('Yes', 'videofly'); ?></option>
                            <option value="false"><?php esc_html_e('No', 'videofly'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Radius of each point dot in pixels (INTEGER)', 'videofly'); ?>
                    </td>
                    <td>
                        <input type="text" id="shortcode-chart-pointDotRadius" value="4" name="shortcode-chart-pointDotRadius" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Pixel width of point dot stroke (INTEGER)', 'videofly'); ?>
                    </td>
                    <td>
                        <input type="text" id="shortcode-chart-pointDotStrokeWidth" value="1" name="shortcode-chart-pointDotStrokeWidth" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Amount extra to add to the radius to cater for hit detection outside the drawn point (INTEGER)', 'videofly'); ?>
                    </td>
                    <td>
                        <input type="text" id="shortcode-chart-pointHitDetectionRadius" value="20" name="shortcode-chart-pointHitDetectionRadius" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Show a stroke for datasets', 'videofly'); ?>
                    </td>
                    <td>
                        <select id="shortcode-chart-datasetStroke" name="shortcode-chart-datasetStroke">
                            <option selected="selected" value="true"><?php esc_html_e('Yes', 'videofly'); ?></option>
                            <option value="false"><?php esc_html_e('No', 'videofly'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Pixel width of dataset stroke (INTEGER)', 'videofly'); ?>
                    </td>
                    <td>
                        <input type="text" id="shortcode-chart-datasetStrokeWidth" value="2" name="shortcode-chart-datasetStrokeWidth" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Fill the dataset with a colour', 'videofly'); ?>
                    </td>
                    <td>
                        <select id="shortcode-chart-datasetFill" name="shortcode-chart-datasetFill">
                            <option selected="selected" value="true"><?php esc_html_e('Yes', 'videofly'); ?></option>
                            <option value="false"><?php esc_html_e('No', 'videofly'); ?></option>
                        </select>
                    </td>
                </tr>
            </table>
            <ul id="shortcode-chart_line_items">
            
            </ul>
                
            <input type="hidden" id="shortcode-chart_line_content" value="" />
            <input type="button" class="button ts-multiple-add-button" data-element-name="shortcode-chart_line" id="shortcode-chart_line_add_item" value=" <?php esc_html_e('Add New Line', 'videofly'); ?>" />
            <?php
                echo '<script id="shortcode-chart_line_items_template" type="text/template">';
                echo '<li id="list-item-id-{{item-id}}" class="tab-item ts-multiple-add-list-element">
                        <div class="sortable-meta-element"><span class="tab-arrow icon-down"></span> <span class="tab-item-tab ts-multiple-item-tab">' . esc_html__('Line', 'videofly') . ': {{slide-number}}</span></div>
                        <div class="hidden">
                            <table>
                                <tr>
                                    <td>
                                        ' . esc_html__('Title', 'videofly') . '
                                    </td>
                                     <td>
                                        <input data-option-name="title" value="" type="text" id="shortcode-chart_line-{{item-id}}-title" name="shortcode-chart_line[{{item-id}}][title]" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        ' . esc_html__('Fill color', 'videofly') . '
                                    </td>
                                    <td>
                                        <input data-option-name="fillColor" type="text" value="#ffffff" id="shortcode-chart_line-{{item-id}}-fillColor" name="shortcode-chart_line[{{item-id}}][fillColor]" class="colors-section-picker" />
                                        <div class="colors-section-picker-div"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        ' . esc_html__('Stroke color', 'videofly') . '
                                    </td>
                                    <td>
                                        <input data-option-name="strokeColor" type="text" value="#ffffff" id="shortcode-chart_line-{{item-id}}-strokeColor" name="shortcode-chart_line[{{item-id}}][strokeColor]" class="colors-section-picker" />
                                        <div class="colors-section-picker-div"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        ' . esc_html__('Point color', 'videofly') . '
                                    </td>
                                    <td>
                                        <input data-option-name="pointColor" type="text" value="#ffffff" id="shortcode-chart_line-{{item-id}}-pointColor" name="shortcode-chart_line[{{item-id}}][pointColor]" class="colors-section-picker" />
                                        <div class="colors-section-picker-div"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        ' . esc_html__('Point stroke color', 'videofly') . '
                                    </td>
                                    <td>
                                        <input data-option-name="pointStrokeColor" type="text" value="#ffffff" id="shortcode-chart_line-{{item-id}}-pointStrokeColor" name="shortcode-chart_line[{{item-id}}][pointStrokeColor]" class="colors-section-picker" />
                                        <div class="colors-section-picker-div"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        ' . esc_html__('Point highlight fill', 'videofly') . '
                                    </td>
                                    <td>
                                        <input data-option-name="pointHighlightFill" type="text" value="#777" id="shortcode-chart_line-{{item-id}}-pointHighlightFill" name="shortcode-chart_line[{{item-id}}][pointHighlightFill]" class="colors-section-picker" />
                                        <div class="colors-section-picker-div"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        ' . esc_html__('Point highlight stroke', 'videofly') . '
                                    </td>
                                    <td>
                                        <input data-option-name="pointHighlightStroke" type="text" value="#ffffff" id="shortcode-chart_line-{{item-id}}-pointHighlightStroke" name="shortcode-chart_line[{{item-id}}][pointHighlightStroke]" class="colors-section-picker" />
                                        <div class="colors-section-picker-div"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        ' . esc_html__('Data in format 25,35,45,...', 'videofly') . '
                                    </td>
                                    <td>
                                        <input data-option-name="data" type="text" value="" id="shortcode-chart_line-{{item-id}}-data" name="shortcode-chart_line[{{item-id}}][data]" class="colors-section-picker" />
                                    </td>
                                </tr>
                            </table>
                            <input type="hidden" data-option-name="item_id" value="{{item-id}}" name="chart_line[{{item-id}}][id]" />
                            <input type="button" class="button button-primary remove-item" value="' . esc_html__('Remove', 'videofly') . '" />
                            <a href="#" data-item="chart_line-item" data-increment="chart_line-items" class="button button-primary ts-multiple-item-duplicate">'.esc_html__('Duplicate line', 'videofly').'</a>
                        </div>
                     </li>';
                echo '</script>';
            ?>
        </div>
        <div class="shortcode-chart-pie-options">
            <table>
                <tr>
                    <td>
                        <?php esc_html_e('Show a stroke on each segment', 'videofly'); ?>
                    </td>
                    <td>
                        <select id="shortcode-chart-segmentShowStroke" name="shortcode-chart-segmentShowStroke">
                            <option selected="selected" value="true"><?php esc_html_e('Yes', 'videofly'); ?></option>
                            <option value="false"><?php esc_html_e('No', 'videofly'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('The colour of each segment stroke', 'videofly'); ?>
                    </td>
                    <td>
                        <input type="text" value="#777" id="shortcode-chart-segmentStrokeColor" class="colors-section-picker" name="shortcode-chart-segmentStrokeColor" />
                        <div class="colors-section-picker-div"></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('The width of each segment stroke (INTEGER)', 'videofly'); ?>
                    </td>
                    <td>
                        <input type="text" value="2" id="shortcode-chart-segmentStrokeWidth" name="shortcode-chart-segmentStrokeWidth" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('The percentage of the chart that we cut out of the middle (INTEGER)', 'videofly'); ?>
                    </td>
                    <td>
                        <input type="text" value="0" id="shortcode-chart-percentageInnerCutout" name="shortcode-chart-percentageInnerCutout" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Amount of animation steps (INTEGER)', 'videofly'); ?>
                    </td>
                    <td>
                        <input type="text" value="100" id="shortcode-chart-animationSteps" name="shortcode-chart-animationSteps" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Animate the rotation of the Doughnut', 'videofly'); ?>
                    </td>
                    <td>
                        <select id="shortcode-chart-animateRotate" name="shortcode-chart-animateRotate">
                            <option selected="selected" value="true"><?php esc_html_e('Yes', 'videofly'); ?></option>
                            <option value="false"><?php esc_html_e('No', 'videofly'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Animate scaling the Doughnut from the centre', 'videofly'); ?>
                    </td>
                    <td>
                        <select id="shortcode-chart-animateScale" name="shortcode-chart-animateScale">
                            <option value="true"><?php esc_html_e('Yes', 'videofly'); ?></option>
                            <option selected="selected" value="false"><?php esc_html_e('No', 'videofly'); ?></option>
                        </select>
                    </td>
                </tr>
            </table>
            <ul id="shortcode-chart_pie_items">
                            
            </ul>
                
            <input type="hidden" id="shortcode-chart_pie_content" value="" />
            <input type="button" class="button ts-multiple-add-button" data-element-name="shortcode-chart_pie" id="shortcode-shortcode-chart_pie_add_item" value=" <?php esc_html_e('Add New Pie', 'videofly'); ?>" />
            <?php
                echo '<script id="shortcode-chart_pie_items_template" type="text/template">';
                echo '<li id="list-item-id-{{item-id}}" class="tab-item ts-multiple-add-list-element">
                        <div class="sortable-meta-element"><span class="tab-arrow icon-down"></span> <span class="tab-item-tab ts-multiple-item-tab">' . esc_html__('Pie', 'videofly') . ': {{slide-number}}</span></div>
                        <div class="hidden">
                            <table>
                                <tr>
                                    <td>
                                        ' . esc_html__('Label', 'videofly') . '
                                    </td>
                                    <td>
                                        <input data-option-name="title" type="text" value="" id="shortcode-chart_pie-{{item-id}}-title" name="shortcode-chart_pie[{{item-id}}][title]" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        ' . esc_html__('Value (INTEGER)', 'videofly') . '
                                    </td>
                                    <td>
                                        <input data-option-name="value" type="text" value="" id="shortcode-chart_pie-{{item-id}}-value" name="shortcode-chart_pie[{{item-id}}][value]" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        ' . esc_html__('Color section', 'videofly') . '
                                    </td>
                                    <td>
                                        <input data-option-name="color" type="text" value="#777" id="shortcode-chart_pie-{{item-id}}-color" class="colors-section-picker" name="shortcode-chart_pie[{{item-id}}][color]" />
                                        <div class="colors-section-picker-div"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        ' . esc_html__('Hover color', 'videofly') . '
                                    </td>
                                    <td>
                                        <input data-option-name="highlight" type="text" value="#777" id="shortcode-chart_pie-{{item-id}}-highlight" class="colors-section-picker" name="shortcode-chart_pie[{{item-id}}][highlight]" />
                                        <div class="colors-section-picker-div"></div>
                                    </td>
                                </tr>
                            </table>
                            <input type="hidden" data-option-name="item_id" value="{{item-id}}" name="shortcode-chart_pie[{{item-id}}][id]" />
                            <input type="button" class="button button-primary remove-item" value="' . esc_html__('Remove', 'videofly') . '" />
                            <a href="#" data-item="shortcode-chart_pie-item" data-increment="shortcode-chart_pie-items" class="button button-primary ts-multiple-item-duplicate">'.esc_html__('Duplicate pie', 'videofly').'</a>
                        </div>
                     </li>';
                echo '</script>';
            ?>
        </div>
        <script>
        jQuery(document).ready(function(){
            jQuery('#shortcode-chart-mode').change(function(){
                if( jQuery(this).val() == 'pie' ){
                    jQuery('.shortcode-chart-line-options').css('display', 'none');
                    jQuery('.shortcode-chart-pie-options').css('display', '');
                }else{
                    jQuery('.shortcode-chart-line-options').css('display', '');
                    jQuery('.shortcode-chart-pie-options').css('display', 'none');
                }
            });
            if( jQuery('#shortcode-chart-mode').val() == 'pie' ){
                jQuery('.shortcode-chart-line-options').css('display', 'none');
                jQuery('.shortcode-chart-pie-options').css('display', '');
            }else{
                jQuery('.shortcode-chart-line-options').css('display', '');
                jQuery('.shortcode-chart-pie-options').css('display', 'none');
            }

            var pickers = jQuery('.colors-section-picker-div');
            jQuery.each(pickers, function(index, value){
                jQuery(this).farbtastic(jQuery(this).prev());
            });

        });
        </script>
    </div>
    <input type="button" class="button-primary save-element" value="<?php esc_html_e( 'Save changes', 'videofly' ); ?>" id="shortcode-save"/>  
<?php 
    die();
}// end function chart_callback
add_action('wp_ajax_chart', 'chart_callback');
 
function ts_charts_shortcode($atts, $content = null){
   $chart_options = '[' . str_replace('}{', '},{', do_shortcode($content)) . ']';

    extract( shortcode_atts( array(
        'mode' => 'pie',
        'label' => '',
        'title' => '',
        'scaleshowgridlines' => 'true',
        'scalegridlinecolor' => '',
        'scalegridlinewidth' => 1,
        'scaleshowhorizontallines' => 'true',
        'scaleshowverticallines' => 'true',
        'beziercurve' => 'true',
        'beziercurvetension' => 0.4,
        'pointdot' => 'true',
        'pointdotradius' => 4,
        'pointdotstrokewidth' => 1,
        'pointhitdetectionradius' => 20,
        'datasetstroke' => 'true',
        'datasetstrokewidth' => 2,
        'datasetfill' => 'true',
        'segmentshowstroke' => 'true',
        'segmentstrokecolor' => '#777',
        'segmentstrokewidth' => 2,
        'percentageinnercutout' => 0,
        'animaterotate' => 'true',
        'animatescale' => 'false',
        'animationsteps' => 100,
      ), $atts));

    $options['mode'] = $mode;
    $options['label'] = $label;
    $options['title'] = $title;
    $options['scaleShowGridLines'] = $scaleshowgridlines;
    $options['scaleGridLineColor'] = $scalegridlinecolor;
    $options['scaleGridLineWidth'] = $scalegridlinewidth;
    $options['scaleShowHorizontalLines'] = $scaleshowhorizontallines;
    $options['scaleShowVerticalLines'] = $scaleshowverticallines;
    $options['bezierCurve'] = $beziercurve;
    $options['bezierCurveTension'] = $beziercurvetension;
    $options['pointDot'] = $pointdot;
    $options['pointDotRadius'] = $pointdotradius;
    $options['pointDotStrokeWidth'] = $pointdotstrokewidth;
    $options['pointHitDetectionRadius'] = $pointhitdetectionradius;
    $options['datasetStroke'] = $datasetstroke;
    $options['datasetStrokeWidth'] = $datasetstrokewidth;
    $options['datasetFill'] = $datasetfill;
    $options['segmentShowStroke'] = $segmentshowstroke;
    $options['segmentStrokeColor'] = $segmentstrokecolor;
    $options['segmentStrokeWidth'] = $segmentstrokewidth;
    $options['percentageInnerCutout'] = $percentageinnercutout;
    $options['animationSteps'] = $animationsteps;
    $options['animateRotate'] = $animaterotate;
    $options['animateScale'] = $animatescale;

   if( $options['mode'] == 'line' ) $options['chart_line'] = $chart_options;
   else $options['chart_pie'] = $chart_options;
   tsIncludeScripts(array('chart'));
   return LayoutCompilator::chart_element($options);
}
add_shortcode( 'ts_charts', 'ts_charts_shortcode' );

function ts_chart_shortcode($atts, $content = null){
    
    extract( shortcode_atts( array(
        'id'     => '',
        'title'  => '',
        'fillcolor'  => '',
        'strokecolor'  => '',
        'pointcolor'  => '',
        'pointstrokecolor'  => '',
        'pointhighlightfill'  => '',
        'pointhighlightstroke'  => '',
        'data'  => '',
        'value'  => '',
        'color'  => '',
        'highlight'  => ''
    ), $atts));

    $json_encode = '{"id":"'. $id . '","title":"' . $title . '","fillColor":"' . $fillcolor . '","strokeColor":"' . $strokecolor . '","pointColor":"' . $pointcolor . '","pointStrokeColor":"' . $pointstrokecolor . '","pointHighlightFill":"' . $pointhighlightfill . '","pointHighlightStroke":"' . $pointhighlightstroke . '","data":"' . $data . '","value":"' . $value . '","color":"' . $color . '","highlight":"' . $highlight . '"}';

    return $json_encode;
}
add_shortcode( 'ts_chart', 'ts_chart_shortcode' );

?>
