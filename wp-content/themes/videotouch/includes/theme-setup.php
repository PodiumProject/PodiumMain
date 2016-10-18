<?php
define('VIDEOTOUCH_VERSION', time());

function videotouch_activation_settings($theme)
{

    $first_activation = get_option('videotouch_first_activation', 'yes');
    
    if ( $first_activation === 'yes' ) {

        $header = '[{"settings":{"rowName":"","bgColor":"transparent","textColor":"inherit","rowMaskColor":"inherit","rowMask":"no","rowShadow":"no","textOpacity":"","bgImage":"","bgVideoMp":"","bgVideoWebm":"","bgPosition":"left","bgAttachement":"fixed","bgRepeat":"repeat","bgSize":"auto","rowMarginTop":"0","rowMarginBottom":"40","rowPaddingTop":"40","rowPaddingBottom":"40","expandRow":"no","specialEffects":"none","rowTextAlign":"auto","fullscreenRow":"no","rowVerticalAlign":"top","rowOpacity":0},"columns":[{"size":4,"elements":[{"type":"logo","logo-align":"text-left"}]},{"size":8,"elements":[{"type":"menu","element-style":"style1","menu-custom":"no","menu-bg-color":"#DDDDDD","menu-text-color":"#FFFFFF","menu-bg-color-hover":"#DDDDDD","menu-text-color-hover":"#FFFFFF","submenu-bg-color":"#DDDDDD","submenu-text-color":"#FFFFFF","submenu-bg-color-hover":"#DDDDDD","submenu-text-color-hover":"#FFFFFF","menu-text-align":"menu-text-align-left","admin-label":"","uppercase":"menu-no-uppercase","gradient":"n","gradientColor":"transparent","gradientMode":"radial"}]}]}]';

        $header = json_decode($header, true);

        $header_templates = array(
            'default' => array(
                'name' => __('Default template', 'touchsize'),
                'elements' => $header
            )
        );

        update_option( 'videotouch_header', $header );
        update_option( 'videotouch_header_template_id', 'default' );
        update_option( 'videotouch_header_templates', $header_templates );

        $footer = '[{"settings":{"rowName":"","bgColor":"#f6f6f6","textColor":"inherit","rowMaskColor":"inherit","rowMask":"no","rowShadow":"no","textOpacity":"","bgImage":"","bgVideoMp":"","bgVideoWebm":"","bgPosition":"left","bgAttachement":"fixed","bgRepeat":"repeat","bgSize":"auto","rowMarginTop":"40","rowMarginBottom":"0","rowPaddingTop":"40","rowPaddingBottom":"40","expandRow":"no","specialEffects":"none","rowTextAlign":"auto","fullscreenRow":"no","rowVerticalAlign":"top","rowOpacity":0},"columns":[{"size":12,"elements":[{"type":"text","text":"Copyright TouchSize. All rights reserved. www.touchsize.com<\/p>","admin-label":"","gradient":"n","gradientColor":"transparent","gradientMode":"radial"}]}]}]';
        $footer = json_decode($footer, true);
        $footer_templates = array(
            'default' => array(
                'name' => __('Default template', 'touchsize'),
                'elements' => $footer
            )
        );

        update_option( 'videotouch_footer', $footer );
        update_option( 'videotouch_footer_template_id', 'default' );
        update_option( 'videotouch_footer_templates', $footer_templates );

        update_option('videotouch_first_activation', 'no');
    }
}


add_action('after_switch_theme', 'videotouch_activation_settings');

if ( function_exists( 'add_theme_support' ) ) {

	/*
	 * Makes VideoTouch available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 */
	load_theme_textdomain( 'touchsize', get_template_directory() . '/languages' );

	// Enables the navigation menu ability
	add_theme_support('menus');

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );
	
	// Enables post-thumbnail support
	add_theme_support( 'post-thumbnails', array( 'video', 'post', 'page', 'ts_teams', 'feature-blocks', 'portfolio', 'ts_testimonials', 'product' ) );

	add_theme_support( 'post-formats', array( 'video', 'gallery', 'image', 'audio' ) );

	set_post_thumbnail_size( 400, 400, TRUE );

	// additional sizes
    add_image_size( 'grid', 450, 300, TRUE );
	add_image_size( 'list', 680, 300, TRUE );
	add_image_size( 'thumb', 180, 110, TRUE );
    // add_image_size( 'square', 250, 250, TRUE );
	// add_image_size( 'team', 450, 450, TRUE );
}

// This theme uses wp_nav_menu()
function register_videotouch_menu() {
	register_nav_menus( array(
		'primary' => __( 'Primary' , 'touchsize' )
	));
}

add_action( 'init', 'register_videotouch_menu' );

// Verify if more than one page exists
function show_posts_nav() {
	global $wp_query;
	return ( $wp_query->max_num_pages > 1 );
}

/**
* Render image gallery from attached images to post
*/
if ( ! function_exists( 'red_get_post_img_slideshow' ) ) { 
    function red_get_post_img_slideshow($post_id, $size="grid"){

        /*check the meta data where the attached image ids are stored*/

        if ( metadata_exists( 'post', $post_id, '_post_image_gallery' ) ) {

            $product_image_gallery = get_post_meta( $post_id, '_post_image_gallery', true );

            $img_id_array = array_filter( explode( ',', $product_image_gallery ) );
        }
  
        if(isset($img_id_array) && is_array($img_id_array)){
            foreach ($img_id_array as $value) {
                $attachments[$value] = $value; // create attachments array in hte format that will work for us                    
            }
        }
  
        if(isset($attachments) && count($attachments) > 0){

            
            $additional_items = ''; /*in this string we will store the images that are left after loading the number of images defined in $images_to_show_first var*/
            $counter = 0; ?>
            <div class="flexslider" data-animation="slider">
            <ul class="slides">
            <?php 
            foreach($attachments as $att_id => $attachment) {
                $full_img_url = wp_get_attachment_url($att_id);

                $thumbnail_url = aq_resize( $full_img_url, '1170', '9999', false, false ); //resize img, Return an array containing url, width, and height.
          
                $src = $thumbnail_url[0];

        ?>                                 
                <li>
                    <img src="<?php echo $src; ?>"  data-original="<?php echo $thumbnail_url[0]; ?>" alt="" data-width="<?php echo $thumbnail_url[1]; ?>" data-height="<?php echo $thumbnail_url[2]; ?>" />                               
                    <?php if ( ts_overlay_effect_is_enabled() ): ?>
                        <div class="dotted"></div>
                    <?php endif ?>
                    <a class="zoom-in-icon" href="<?php echo $thumbnail_url[0]; ?>" data-rel="prettyPhoto-[<?php echo $post_id; ?>]"><i class="icon-search"></i></a>
                </li>

         <?php } ?>
         </ul>
        </div>
        <?php }    
    }
}

add_filter('pre_get_posts', 'ts_include_post_types_in_search');
function ts_include_post_types_in_search($query) {

    if( is_admin() ) return;
    
    if(is_search() || is_category() || is_tag() && empty($query->query_vars['suppress_filters']) ) {
        $post_types = get_post_types();
        $query->set('post_type', $post_types);
    }
    return $query;
}

add_action('media_buttons_context',  'videotouch_toggle_editor');
function videotouch_toggle_editor() {

    global $post;
    
    $state = 'enabled';

    if (isset($post)) {

        if ($post->post_type === 'page') {
            
            $use_template = get_post_meta($post->ID, 'ts_use_template', true);
            if( $use_template == '' ){
                add_post_meta($post->ID, 'ts_use_template', '0', true);
                $use_template = 0;
            }

            if ((int)$use_template === 1) {
                $builder_status = 'enabled';
            } else {
                $builder_status = 'disabled';
            }

            $button = '<div class="icon-blocks" id="ts-toggle-layout-builder" data-state="'.$builder_status.'">' . __('Toggle Layout Builder', 'touchsize') . '</div>';
?>
            <script>
            jQuery(document).ready(function($) {
                
                var hide_editor = function(button) {

                    $('#postcustom').find('input[value="ts_use_template"]').closest('td').siblings('td').find('textarea').val(1);
                    if( typeof($('#postcustom').find('input[value="ts_use_template"]').parent('td').parent('tr')) !== 'undefined' && $('#postcustom').find('input[value="ts_use_template"]').parent('td').parent('tr').length > 1 ){
                        var i = 1;
                        $('#postcustom').find('input[value="ts_use_template"]').parent('td').parent('tr').each(function(){
                            if( i > 1 ){
                                $(this).remove();
                            }
                            i++;
                        });
                    }

                    if( typeof($('#postcustom').find('input[value="post_settings"]').parent('td').parent('tr')) !== 'undefined' && $('#postcustom').find('input[value="post_settings"]').parent('td').parent('tr').length > 1 ){
                        var i = 1;
                        $('#postcustom').find('input[value="post_settings"]').parent('td').parent('tr').each(function(){
                            if( i > 1 ){
                                $(this).remove();
                            }
                            i++;
                        });
                    }

                    $('#content-tmce').hide();
                    $('#insert-media-button').hide();
                    $('#content-html').hide();
                    $('#wp-content-editor-container').hide();
                    $('#ts_layout_id').show();
                    $('#ts_page_options').hide();
                    $('#post-status-info').hide();
                    $('#ts-import-export').show();
                    $('#ts_sidebar').hide();
                    button.attr('data-state', 'enabled');
                };

                var show_editor = function(button) {
                    $('#postcustom').find('input[value="ts_use_template"]').closest('td').siblings('td').find('textarea').val(0);
                    if( typeof($('#postcustom').find('input[value="ts_use_template"]').parent('td').parent('tr')) !== 'undefined' && $('#postcustom').find('input[value="ts_use_template"]').parent('td').parent('tr').length > 1 ){
                        var i = 1;
                        $('#postcustom').find('input[value="ts_use_template"]').parent('td').parent('tr').each(function(){
                            if( i > 1 ){
                                $(this).remove();
                            }
                            i++;
                        });
                    }

                    if( typeof($('#postcustom').find('input[value="post_settings"]').parent('td').parent('tr')) !== 'undefined' && $('#postcustom').find('input[value="post_settings"]').parent('td').parent('tr').length > 1 ){
                        var i = 1;
                        $('#postcustom').find('input[value="post_settings"]').parent('td').parent('tr').each(function(){
                            if( i > 1 ){
                                $(this).remove();
                            }
                            i++;
                        });
                    }

                    $('#content-tmce').show();
                    $('#content-html').show();
                    $('#wp-content-editor-container').show();
                    $('#post-status-info').show();
                    $('#ts_layout_id').hide();
                    $('#ts_page_options').show();
                    $('#ts-import-export').hide();
                    $('#ts_sidebar').show();
                    $('#insert-media-button').show();
                    button.attr('data-state', 'disabled');
                };
               
                <?php if ( $builder_status === 'enabled' ): ?>

                    if( typeof($('#postcustom').find('input[value="ts_use_template"]').parent('td').parent('tr')) !== 'undefined' && $('#postcustom').find('input[value="ts_use_template"]').parent('td').parent('tr').length > 1 ){
                        var i = 1;
                        $('#postcustom').find('input[value="ts_use_template"]').parent('td').parent('tr').each(function(){
                            if( i > 1 ){
                                $(this).remove();
                            }
                            i++;
                        });
                    }

                    if( typeof($('#postcustom').find('input[value="post_settings"]').parent('td').parent('tr')) !== 'undefined' && $('#postcustom').find('input[value="post_settings"]').parent('td').parent('tr').length > 1 ){
                        var i = 1;
                        $('#postcustom').find('input[value="post_settings"]').parent('td').parent('tr').each(function(){
                            if( i > 1 ){
                                $(this).remove();
                            }
                            i++;
                        });
                    }

                    var button = $('#ts-toggle-layout-builder');
                    hide_editor(button);
                    $('#ts_layout_id').show();
                    $('#ts_page_options').hide();
                    $('#ts_sidebar').hide();
                    $('#ts-import-export').show();
                <?php else: ?>
                    var button = $('#ts-toggle-layout-builder');
                    show_editor(button);
                    $('#ts_layout_id').hide();
                    $('#ts_page_options').show();
                    $('#ts_sidebar').show();
                    $('#ts-import-export').hide();
                <?php endif; ?>

                // Toggle Layout builder
                $("#ts-toggle-layout-builder").toggle(function() {
                    var button = $(this);
                    <?php if ($builder_status === 'enabled'): ?>
                        show_editor(button);
                    <?php else: ?>
                        hide_editor(button);
                    <?php endif; ?>

                }, function() {
                    var button = $(this);
                    <?php if ($builder_status === 'enabled'): ?>
                        hide_editor(button);
                    <?php else: ?>
                        show_editor(button);
                    <?php endif; ?>
                });  
            });
            </script>

<?php       
            if( current_user_can( 'manage_options') ) return $button;
        }
    } else {
        return '';
    }
}



/* Localization */
load_theme_textdomain( 'touchsize' );
load_theme_textdomain( 'touchsize' , get_template_directory() . '/languages' );

if ( function_exists( 'load_child_theme_textdomain' ) ){
    load_child_theme_textdomain( 'touchsize' );
}

?>
