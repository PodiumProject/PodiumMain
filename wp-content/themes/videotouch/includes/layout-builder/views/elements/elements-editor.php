<?php   include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 
        $list_products_ul = ''; 
        $list_products_select = '';
        $cart_li = '';
        $cart_select = '';
        if( is_plugin_active( 'woocommerce/woocommerce.php' ) ){
            $list_products_ul = '<li class="icon-basket" data-value="list-products"><span>List products</span></li>';
            $list_products_select = '<option value="list-products">' . __( "List products", "touchsize" ) . '</option>';
            $cart_li = '<li class="icon-basket" data-value="cart"><span>Shopping cart</span></li>';
            $cart_select = '<option value="cart">' . __( "Shopping cart", "touchsize" ) . '</option>';
        } 
        $touchsize_com = '<a href="http://touchsize.com/videotouch-doc">' . __('here','touchsize') . '</a>.';
        $icons_array = get_option('videotouch_typography',array());
        $icons_li = '';
        $icons_options = '';
        $icons_array['icons'] = explode(',',$icons_array['icons']);
        foreach ($icons_array['icons'] as $value) {
            $icons_li .= '<li><i class="'. trim($value) .' clickable-element" data-option="'. trim($value) .'"></i></li>';
            $icons_options .= '<option value="'. trim($value) .'"></option>';
        }
?>
        <div class="builder">
            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="ts-element-type"><?php _e('Select the element type', 'touchsize'); ?>:</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="builder-element-array">
                            <ul>
                                <li class="icon-logo" data-value="logo"><span><?php _e( 'Logo', 'touchsize' ) ?></span></li>
                                <li class="icon-social" data-value="social-buttons"><span><?php _e( 'Social buttons', 'touchsize' ) ?></span></li>
                                <li class="icon-search" data-value="searchbox"><span><?php _e( 'Search box', 'touchsize' ) ?></span></li>
                                <li class="icon-menu" data-value="menu"><span><?php _e( 'Menu', 'touchsize' ) ?></span></li>
                                <li class="icon-sidebar" data-value="sidebar"><span><?php _e( 'Sidebar', 'touchsize' ) ?></span></li>
                                <li class="icon-desktop" data-value="slider"><span><?php _e( 'Slider', 'touchsize' ) ?></span></li>
                                <li class="icon-briefcase" data-value="list-portfolios"><span><?php _e( 'List portfolios', 'touchsize' ) ?></span></li>
                                <li class="icon-comments" data-value="testimonials"><span><?php _e( 'Testimonials', 'touchsize' ) ?></span></li>
                                <li class="icon-page" data-value="last-posts"><span><?php _e( 'List posts', 'touchsize' ) ?></span></li>
                                <li class="icon-direction" data-value="callaction"><span><?php _e( 'Call to action', 'touchsize' ) ?></span></li>
                                <li class="icon-team" data-value="teams"><span><?php _e( 'Teams', 'touchsize' ) ?></span></li>
                                <li class="icon-money" data-value="advertising"><span><?php _e( 'Advertising', 'touchsize' ) ?></span></li>
                                <li class="icon-empty" data-value="empty"><span><?php _e( 'Empty', 'touchsize' ) ?></span></li>
                                <li class="icon-delimiter" data-value="delimiter"><span><?php _e( 'Delimiter', 'touchsize' ) ?></span></li>
                                <li class="icon-font" data-value="title"><span><?php _e( 'Title', 'touchsize' ) ?></span></li>
                                <li class="icon-video" data-value="video"><span><?php _e( 'Video', 'touchsize' ) ?></span></li>
                                <li class="icon-image" data-value="image"><span><?php _e( 'Image', 'touchsize' ) ?></span></li>
                                <li class="icon-filter" data-value="filters"><span><?php _e( 'Filters', 'touchsize' ) ?></span></li>
                                <li class="icon-resize-vertical" data-value="spacer"><span><?php _e( 'Spacer', 'touchsize' ) ?></span></li>
                                <li class="icon-button" data-value="buttons"><span><?php _e( 'Button', 'touchsize' ) ?></span></li>
                                <li class="icon-mail" data-value="contact-form"><span><?php _e( 'Contact form', 'touchsize' ) ?></span></li>
                                <li class="icon-featured-area" data-value="featured-area"><span><?php _e( 'Featured area', 'touchsize' ) ?></span></li>
                                <li class="icon-code" data-value="shortcodes"><span><?php _e( 'Shortcodes', 'touchsize' ) ?></span></li>
                                <li class="icon-text" data-value="text" id="icon-text"><span><?php _e( 'Text', 'touchsize' ) ?></span></li>
                                <li class="icon-coverflow" data-value="image-carousel"><span><?php _e( 'Image carousel', 'touchsize' ) ?></span></li>
                                <li class="icon-flag" data-value="icon"><span><?php _e( 'Icon', 'touchsize' ) ?></span></li>
                                <li class="icon-dollar" data-value="pricing-tables"><span><?php _e( 'Pricing tables', 'touchsize' ) ?></span></li>
                                <li class="icon-list" data-value="listed-features"><span><?php _e( 'Listed features', 'touchsize' ) ?></span></li>
                                <li class="icon-tick" data-value="features-block"><span><?php _e( 'Icon box', 'touchsize' ) ?></span></li>
                                <li class="icon-time" data-value="counters"><span><?php _e( 'Counter', 'touchsize' ) ?></span></li>
                                <?php echo $list_products_ul; ?>
                                <li class="icon-clients" data-value="clients"><span><?php _e( 'Clients', 'touchsize' ) ?></span></li>
                                <li class="icon-facebook" data-value="facebook-block"><span><?php _e( 'Facebook Like Box', 'touchsize' ) ?></span></li>
                                <li class="icon-pin" data-value="map"><span><?php _e( 'Map', 'touchsize' ) ?>
                                <li class="icon-link-ext" data-value="banner"><span><?php _e( 'Banner', 'touchsize' ) ?>
                                <li class="icon-resize-full" data-value="toggle"><span><?php _e( 'Toggle', 'touchsize' ) ?>
                                <li class="icon-tabs" data-value="tab"><span><?php _e( 'Tabs', 'touchsize' ) ?>
                                <li class="icon-movie" data-value="list-videos"><span><?php _e( 'List Videos', 'touchsize' ) ?>
                                <li class="icon-login" data-value="user"><span><?php _e( 'User login', 'touchsize' ) ?>
                                <li class="icon-code" data-value="breadcrumbs"><span><?php _e( 'Breadcrumbs', 'touchsize' ) ?>
                                <li class="icon-window" data-value="latest-custom-posts"><span><?php _e( 'Latest custom posts', 'touchsize' ) ?>
                                <li class="icon-ribbon" data-value="ribbon"><span><?php _e( 'Ribbon banner', 'touchsize' ) ?>
                                <li class="icon-parallel" data-value="timeline"><span><?php _e( 'Timeline features', 'touchsize' ) ?>
                                <?php echo $cart_li; ?>
                                <li class="icon-coverflow" data-value="video-carousel"><span><?php _e( 'Video carousel', 'touchsize' ) ?>
                                <li class="icon-quote" data-value="quote"><span><?php _e( 'Quote', 'touchsize' ) ?>
                            </ul>
                        </div>
                        <select name="ts-element-type" id="ts-element-type" class="hidden">
                            <option value="logo"><?php _e( 'Logo', 'touchsize' ) ?></option>
                            <option value="social-buttons"><?php _e( 'Social buttons', 'touchsize' ) ?></option>
                            <option value="searchbox"><?php _e( 'Search box', 'touchsize' ) ?></option>
                            <option value="menu"><?php _e( 'Menu', 'touchsize' ) ?></option>
                            <option value="sidebar"><?php _e( 'Sidebar', 'touchsize' ) ?></option>
                            <option value="slider"><?php _e( 'Slider', 'touchsize' ) ?></option>
                            <option value="list-portfolios"><?php _e( 'List Portfolios', 'touchsize' ) ?></option>
                            <option value="testimonials"><?php _e( 'Testimonials', 'touchsize' ) ?></option>
                            <option value="last-posts"><?php _e( 'List Posts', 'touchsize' ) ?></option>
                            <option value="callaction"><?php _e( 'Call to action', 'touchsize' ) ?></option>
                            <option value="teams"><?php _e( 'Teams', 'touchsize' ) ?></option>
                            <option value="advertising"><?php _e( 'Advertising', 'touchsize' ) ?></option>
                            <option value="empty"><?php _e( 'Empty', 'touchsize' ) ?></option>
                            <option value="delimiter"><?php _e( 'Delimiter', 'touchsize' ) ?></option>
                            <option value="title"><?php _e( 'Title', 'touchsize' ) ?></option>
                            <option value="video"><?php _e( 'Video', 'touchsize' ) ?></option>
                            <option value="image"><?php _e( 'Image', 'touchsize' ) ?></option>
                            <option value="filters"><?php _e( 'Filters', 'touchsize' ) ?></option>
                            <option value="spacer"><?php _e( 'Spacer', 'touchsize' ) ?></option>
                            <option value="buttons"><?php _e( 'Button', 'touchsize' ) ?></option>
                            <option value="contact-form"><?php _e( 'Contact form', 'touchsize' ) ?></option>
                            <option value="featured-area"><?php _e( 'Featured area', 'touchsize' ) ?></option>
                            <option value="shortcodes"><?php _e( 'Shortcodes', 'touchsize' ) ?></option>
                            <option value="text"><?php _e( 'Text', 'touchsize' ) ?></option>
                            <option value="image-carousel"><?php _e( 'Image carousel', 'touchsize' ) ?></option>
                            <option value="icon"><?php _e( 'Icon', 'touchsize' ) ?></option>
                            <option value="pricing-tables"><?php _e( 'Pricing tables', 'touchsize' ) ?></option>
                            <option value="listed-features"><?php _e( 'Listed features', 'touchsize' ) ?></option>
                            <option value="features-block"><?php _e( 'Icon box', 'touchsize' ) ?></option>
                            <option value="counters"><?php _e( 'Counters', 'touchsize' ) ?></option>
                            <option value="clients"><?php _e( 'Clients', 'touchsize' ) ?></option>
                            <option value="facebook-block"><?php _e( 'Facebook Like Box', 'touchsize' ) ?></option>
                            <option value="map"><?php _e( 'Map', 'touchsize' ) ?></option>
                            <option value="banner"><?php _e( 'Banner', 'touchsize' ) ?></option>
                            <option value="toggle"><?php _e( 'Toggle', 'touchsize' ) ?></option>
                            <?php echo $list_products_select; ?>
                            <option value="tab"><?php _e( 'Tabs', 'touchsize' ) ?></option>
                            <option value="list-videos"><?php _e( 'List Videos', 'touchsize' ) ?></option>
                            <option value="user"><?php _e( 'User login', 'touchsize' ) ?></option>
                            <option value="breadcrumbs"><?php _e( 'Breadcrumbs', 'touchsize' ) ?></option>
                            <option value="latest-custom-posts"><?php _e( 'Latest custom posts', 'touchsize' ) ?></option>
                            <option value="ribbon"><?php _e( 'Ribbon banner', 'touchsize' ) ?></option>
                            <option value="timeline"><?php _e( 'Timeline features', 'touchsize' ) ?></option>
                            <?php echo $cart_select; ?>
                            <option value="video-carousel"><?php _e( 'Video carousel', 'touchsize' ) ?></option>                            
                            <option value="quote"><?php _e( 'Quote', 'touchsize' ) ?></option>                            
                        </select>
                    </td>
                </tr>
            </table>
        </div>

        <div id="builder-elements">
            <div class="logo builder-element hidden">
                <h3 class="element-title"><?php _e('Logo element', 'touchsize'); ?></h3>
                <p><?php _e( 'You can add your logo in', 'touchsize' ) ?> <strong><a target="_blank" href="<?php echo admin_url( 'admin.php?page=videotouch&tab=styles#ts-logo-type' ) ?>"><?php _e( 'Untack &rarr; Styles &rarr; Logo type', 'touchsize' ) ?></a></strong></p>
                <table cellpadding="10">
                    <tr>
                        <td width="70px">
                            <?php _e('Logo align', 'touchsize'); ?>
                        </td>
                        <td>
                            <select name="logo[align]" id="logo-align">
                                <option value="text-left"><?php _e('Left', 'touchsize'); ?></option>
                                <option value="text-right"><?php _e('Right', 'touchsize'); ?></option>
                                <option value="text-center"><?php _e('Center', 'touchsize'); ?></option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="social-buttons builder-element hidden">
                <h3 class="element-title"><?php _e('Social icons element','touchsize'); ?></h3>
                <table cellpadding="10">
                    <tr>
                        <td>
                            <label for="social-buttons-admin-label"><?php _e('Admin label:','touchsize'); ?></label>
                        </td>
                        <td>
                           <input type="text" id="social-buttons-admin-label" name="social-name" />
                        </td>
                    </tr>
                    <tr>
                        <td width="70px">
                            <?php _e('Text align', 'touchsize'); ?>
                        </td>
                        <td>
                            <select name="social[align]" id="social-align">
                                <option value="text-left"><?php _e('Left', 'touchsize'); ?></option>
                                <option value="text-right"><?php _e('Right', 'touchsize'); ?></option>
                                <option value="text-center"><?php _e('Center', 'touchsize'); ?></option>
                            </select>
                        </td>
                    </tr>
                </table>
                <p><?php _e( 'You can edit this option in', 'touchsize' ) ?> <strong><a target="_blank" href="<?php echo admin_url( 'admin.php?page=videotouch&tab=social' ) ?>"><?php _e( 'Untack &rarr; Social', 'touchsize' ) ?></a></strong></p>
            </div>

            <div class="searchbox builder-element hidden">
                <h3 class="element-title"><?php _e('Searchbar element', 'touchsize'); ?></h3>
               <p><?php _e("This element doesn't have options.", 'touchsize'); ?></p>
            </div>
         
                     
        <div class="clients builder-element hidden">
            <h3 class="element-title"><?php _e('Clients block', 'touchsize'); ?></h3>
            <table cellpadding="10">
                    <tr>
                        <td>
                            <label for="clients-admin-label"><?php _e('Admin label:','touchsize'); ?></label>
                        </td>
                        <td>
                           <input type="text" id="clients-admin-label" name="clients-admin-label" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="clients-row"><?php _e( 'Number of elements per row', 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#clients-row" id="clients-row-selector">
                               <li><img class="image-radio-input clickable-element" data-option="1" src="<?php echo get_template_directory_uri().'/images/options/per_row_1.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="2" src="<?php echo get_template_directory_uri().'/images/options/per_row_2.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="3" src="<?php echo get_template_directory_uri().'/images/options/per_row_3.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="4" src="<?php echo get_template_directory_uri().'/images/options/per_row_4.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="6" src="<?php echo get_template_directory_uri().'/images/options/per_row_6.png'; ?>"></li>
                            </ul>
                            <select name="clients-row" id="clients-row">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="6">6</option>
                            </select>   
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label><?php _e( 'Enable carousel:', 'touchsize' ); ?></label>
                        </td>
                        <td>
                            <input type="radio" name="clients-enable-carousel" id="clients-enable-carousel-y" value="y"/> 
                            <label for="clients-enable-carousel-y"><?php _e( 'Yes', 'touchsize' ); ?></label>
                            <input type="radio" name="clients-enable-carousel" id="clients-enable-carousel-n" value="n"  checked = "checked" />
                            <label for="clients-enable-carousel-n"><?php _e( 'No', 'touchsize' ); ?></label>
                        </td>
                    </tr>
                </table>
                
                <ul id="clients_items">
                   
                </ul>
                   
                <input type="hidden" id="clients_content" value="" />
                <input type="button" class="button ts-multiple-add-button" data-element-name="clients" id="clients_add_item" value=" <?php _e('Add New Client', 'touchsize'); ?>" />
                <?php
                    echo '<script id="clients_items_template" type="text/template">
                            <li id="list-item-id-{{item-id}}" class="clients_item ts-multiple-add-list-element">
                                <div class="sortable-meta-element"><span class="tab-arrow icon-down"></span> <span class="clients-item-tab ts-multiple-item-tab">Item: {{slide-number}}</span></div>
                                <div class="hidden">
                                    <table>
                                        <tr>
                                          <td>' . __( "Clients image", "touchsize" ) . '</td>
                                          <td>
                                                <input type="text" name="clients-{{item-id}}[image]" id="clients-{{item-id}}-image" value="" data-role="media-url" />
                                                <input type="hidden" id="slide_media_id-{{item-id}}" name="clients_media_id-{{item-id}}" value=""  data-role="media-id" />
                                                <input type="button" id="uploader_{{item-id}}"  class="button button-primary" value="' . __( "Upload", "touchsize" ) . '" />
                                                <div id="image-preview-{{item-id}}"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="clients-{{item-id}}-title">' . __( "Enter your title here:", "touchsize" ) . '</label>
                                            </td>
                                            <td>
                                               <input data-builder-name="title" type="text" id="clients-{{item-id}}-title" name="clients-[{{item-id}}][title]" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="clients-{{item-id}}-url">' . __( "Enter your url here:", "touchsize" ) . '</label>
                                            </td>
                                            <td>
                                               <input data-builder-name="url" type="text" id="clients-{{item-id}}-url" name="clients-[{{item-id}}][url]" />
                                            </td>
                                        </tr>
                                    </table>

                                    <input type="hidden" data-builder-name="item_id" value="{{item-id}}" name="clients[{{item-id}}][id]" />
                                    <input type="button" class="button button-primary remove-item" value="' . __('Remove', 'touchsize') . '" />
                                    <a href="#" data-item="clients_item" data-increment="clients_items" class="button button-primary ts-multiple-item-duplicate">'.__('Duplicate Item', 'touchsize').'</a>
                                </div>
                            </li>
                        </script>';
               ?>
        </div> 


            <div class="features-block builder-element hidden">
    
                <table cellpadding="10">
                    <tr>
                        <td>
                            <label for="features-block-admin-label"><?php _e('Admin label','touchsize');?>:</label>
                        </td>
                        <td>
                           <input type="text" id="features-block-admin-label" name="features-block-admin-label" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="features-block-row"><?php _e( 'Number of elements per row', 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#features-block-row" id="features-block-row-selector">
                               <li><img class="image-radio-input clickable-element" data-option="1" src="<?php echo get_template_directory_uri().'/images/options/per_row_1.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="2" src="<?php echo get_template_directory_uri().'/images/options/per_row_2.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="3" src="<?php echo get_template_directory_uri().'/images/options/per_row_3.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="4" src="<?php echo get_template_directory_uri().'/images/options/per_row_4.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="6" src="<?php echo get_template_directory_uri().'/images/options/per_row_6.png'; ?>"></li>
                            </ul>
                            <select name="features-block-row" id="features-block-row">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="6">6</option>
                            </select>   
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="features-block-gutter"><?php _e( 'Remove gutter(space) between articles:', 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <select name="features-block-gutter" id="features-block-gutter">
                                <option value="gutter">Yes</option>
                                <option value="no-gutter">No</option>
                            </select>   
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="features-block-style"><?php _e( 'Style', 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <select name="features-block-style" id="features-block-style">
                                <option value="style1"><?php _e('Feature blocks with icon top edge', 'touchsize') ?></option>
                                <option value="style2"><?php _e('Feature blocks with icon inner', 'touchsize') ?></option>
                            </select>   
                        </td>
                    </tr>
                </table>

                <h3 class="element-title">Feature blocks</h3>
                <p><?php _e("Please select your icon, add your title and content below.", 'touchsize'); ?></p>
                <ul id="features-block_items">
                   
                </ul>
                   
                <input type="hidden" id="features-block_content" value="" />
                <input type="button" class="button ts-multiple-add-button" data-element-name="features-block" id="features-block_add_item" value=" <?php _e('Add New Item', 'touchsize'); ?>" />
                <?php
                    echo '<script id="features-block_items_template" type="text/template">';
                    echo '<li id="list-item-id-{{item-id}}" class="features-block-item ts-multiple-add-list-element">
                            <div class="sortable-meta-element"><span class="tab-arrow icon-down"></span> <span class="features-block-item-tab ts-multiple-item-tab">Item: {{slide-number}}</span></div>
                            <div class="hidden">
                       <label for="features-block-icon">Choose your icon:</label>
                        <div class="builder-element-icon-toggle">
                            <a href="#" class="red-ui-button builder-element-icon-trigger-btn" data-toggle="#features-block-icon-id-{{item-id}}-selector">'.__('Show icons', 'touchsize').'</a>
                        </div> 
                        <ul id="features-block-icon-id-{{item-id}}-selector" data-selector="#features-block-{{item-id}}-icon" class="builder-icon-list ts-custom-selector">'
                            . $icons_li .
                        '</ul>
                        <select data-builder-name="icon" name="features-block[{{item-id}}][icon]" id="features-block-{{item-id}}-icon" class="hidden">'
                           . $icons_options .
                        '</select>  
                       <table>
                            <tr>
                               <td>
                                   <label for="features-block-title">Enter your title here:</label>
                               </td>
                               <td>
                                   <input data-builder-name="title" type="text" id="features-block-{{item-id}}-title" name="features-block[{{item-id}}][title]" />
                               </td>
                            </tr>
                            <tr>
                               <td>
                                   <label for="features-block-text">Write your text here:</label>
                               </td>
                               <td>
                                   <textarea data-builder-name="text" name="features-block-{{item-id}}[text]" id="features-block-{{item-id}}-text" cols="45" rows="5"></textarea>
                               </td>
                           </tr>
                           <tr>
                               <td>
                                   <label for="features-block-url">Enter your url here:</label>
                               </td>
                               <td>
                                   <input data-builder-name="url" type="text" id="features-block-{{item-id}}-url" name="features-block[{{item-id}}][url]" />
                               </td>
                           </tr>
                            <tr>
                              <td>
                                  <label for="features-block-border">Background color:</label>
                              </td>
                              <td>
                                 <input data-builder-name="background" type="text" value="#777" id="features-block-{{item-id}}-background" class="colors-section-picker" name="features-block[{{item-id}}][background]" />
                                 <div class="colors-section-picker-div" id="features-block-{{item-id}}-background-picker"></div>
                              </td>
                            </tr> 
                           <tr>
                              <td>
                                  <label for="features-block-font">Font color:</label>
                              </td>
                              <td>
                                 <input data-builder-name="font" type="text" value="#777" id="features-block-{{item-id}}-font" class="colors-section-picker" name="features-block[{{item-id}}][font]" />
                                 <div class="colors-section-picker-div" id="features-block-{{item-id}}-font-picker"></div>
                              </td>
                          </tr>
                       </table>
                       <input type="hidden" data-builder-name="item_id" value="{{item-id}}" name="features-block[{{item-id}}][id]" />
                       <input type="button" class="button button-primary remove-item" value="'.__('Remove', 'touchsize').'" />
                       <a href="#" data-item="features-block_item" data-increment="features-block_items" class="button button-primary ts-multiple-item-duplicate">'.__('Duplicate Item', 'touchsize').'</a>
                       </div>
                    </li>
                   ';
                    echo '</script>';
                ?>
            </div>        

            <div class="listed-features builder-element hidden">
                <h3 class="element-title">Listed features</h3>
                <table>
                    <tr>
                        <td>
                            <label for="listed-features-admin-label"><?php _e('Admin label','touchsize');?>:</label>
                        </td>
                        <td>
                           <input type="text" id="listed-features-admin-label" name="listed-features-admin-label" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="listed-features-align"><?php _e( "Icon alignment:", "touchsize") ?></label>
                        </td>
                        <td>
                            <select name="listed-features-align" id="listed-features-align">
                                <option value="text-left"><?php _e( "Left", "touchsize") ?></option>
                                <option value="text-right"><?php _e( "Right", "touchsize") ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="listed-features-color-style"><?php _e("Add color for:", "touchsize") ?></label>
                        </td>
                        <td>
                            <select name="listed-features-color-style" id="listed-features-color-style">
                                <option value="border"><?php _e( "Border", "touchsize") ?></option>
                                <option value="background"><?php _e( "Background", "touchsize") ?></option>
                                <option value="none"><?php _e( "None", "touchsize") ?></option>
                            </select>
                        </td>
                    </tr>
                </table>
               <p><?php _e("Please select your icon, add your title and content below.", 'touchsize'); ?></p>
               <ul id="listed-features_items">
                   
               </ul>
               <input type="hidden" id="listed-features_content" value="" />
               <input type="button" class="button ts-multiple-add-button" data-element-name="listed-features" id="listed-features_add_item" value=" <?php _e('Add New Item', 'touchsize'); ?>" />
               <?php
                    echo '<script id="listed-features_items_template" type="text/template">';
                    echo '<li id="list-item-id-{{item-id}}" class="listed-features-item ts-multiple-add-list-element">
                            <div class="sortable-meta-element"><span class="tab-arrow icon-down"></span> <span class="listed-features-item-tab ts-multiple-item-tab">' . __('Item:', 'touchsize') . ' {{slide-number}}</span></div>
                            <div class="hidden">
                       <label for="listed-features-icon">' . __('Choose your icon:', 'touchsize') . '</label>
                        <div class="builder-element-icon-toggle">
                            <a href="#" class="red-ui-button builder-element-icon-trigger-btn" data-toggle="#listed-features-icon-id-{{item-id}}-selector">' . __('Show icons', 'touchsize') . '</a>
                        </div> 
                        <ul id="listed-features-icon-id-{{item-id}}-selector" data-selector="#listed-features-{{item-id}}-icon" class="builder-icon-list ts-custom-selector">';
                            echo $icons_li;
                    echo '</ul>
                        <select data-builder-name="icon" name="listed-features[{{item-id}}][icon]" id="listed-features-{{item-id}}-icon" class="hidden">'; 
                            echo $icons_options;
                    echo '</select>
                        <table>
                            <tr>
                                <td>
                                    <label for="listed-features-{{item-id}}-title">' . __('Enter your title here:', 'touchsize') . '</label>
                                </td>
                                <td>
                                    <input data-builder-name="title" type="text" id="listed-features-{{item-id}}-title" name="listed-features[{{item-id}}][title]" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="listed-features-text">' . __('Write your text here:', 'touchsize') . '</label>
                                </td>
                                <td>
                                    <textarea data-builder-name="text" name="listed-features-{{item-id}}-text" id="listed-features-{{item-id}}-text" cols="45" rows="5"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="listed-features-{{item-id}}-iconcolor">' . __('Icon color:', 'touchsize') . '</label>
                                </td>
                                <td>
                                    <input data-builder-name="iconcolor" type="text" value="#777" id="listed-features-{{item-id}}-iconcolor" class="colors-section-picker" name="listed-features-{{item-id}}-icon-color" />
                                    <div class="colors-section-picker-div" id="listed-features-{{item-id}}-icon-color-picker"></div>
                                </td>
                            </tr>
                            <tr class="ts-border-color">
                                <td>
                                    <label for="listed-features-{{item-id}}-bordercolor">' . __('Border color:', 'touchsize') . '</label>
                                </td>
                                <td>
                                    <input data-builder-name="bordercolor" type="text" value="#777" id="listed-features-{{item-id}}-bordercolor" class="colors-section-picker" name="listed-features-{{item-id}}-border-color" />
                                    <div class="colors-section-picker-div" id="listed-features-{{item-id}}-border-color-picker"></div>
                                </td>
                            </tr>  
                            <tr class="ts-background-color">
                                <td>
                                    <label for="listed-features-{{item-id}}-background-color">' . __('Background color:', 'touchsize') . '</label>
                                </td>
                                <td>
                                    <input data-builder-name="backgroundcolor" type="text" value="#777" id="listed-features-{{item-id}}-backgroundcolor" class="colors-section-picker" name="listed-features-{{item-id}}-background-color" />
                                    <div class="colors-section-picker-div" id="listed-features-{{item-id}}-background-color-picker"></div>
                                </td>
                            </tr>  
                        </table>
                        <input type="hidden" data-builder-name="item_id" value="{{item-id}}" name="listed-features[{{item-id}}][id]" />
                        <input type="button" class="button button-primary remove-item" value="'.__('Remove', 'touchsize').'" />
                        <a href="#" data-item="listed-features-item" data-increment="listed-features_items" class="button button-primary ts-multiple-item-duplicate">' . __('Duplicate Item', 'touchsize') . '</a></div>
                    </li>
                   ';
                    echo '</script>';
               ?>
            </div>
            
            <div class="icon builder-element hidden">
                <h3 class="element-title"><?php _e('Icon element', 'touchsize'); ?></h3>
                <table cellpadding="10">
                    <tr>
                        <td>
                            <label for="icon-admin-label"><?php _e('Admin label','touchsize');?>:</label>
                        </td>
                        <td>
                           <input type="text" id="icon-admin-label" name="icon-admin-label" />
                        </td>
                    </tr>
                </table>
                <p><?php _e("Choose your icon from the library below:", 'touchsize'); ?></p>
                <div class="builder-element-icon-toggle">
                    <a href="#" class="red-ui-button builder-element-icon-trigger-btn" data-toggle="#builder-element-icon-selector"><?php _e('Show icons','touchsize') ?></a>
                </div>    
                <ul id="builder-element-icon-selector" data-selector="#builder-element-icon" class="builder-icon-list ts-custom-selector">
                    <?php  echo $icons_li; ?>
                </ul>
                <select name="builder-element-icon" id="builder-element-icon" class="hidden">
                    <?php echo $icons_options; ?> 
                </select>
                
                <h3>Icon options</h3>               
                <table>
                    <tr>
                        <td>
                            <label for="builder-element-icon-size"><?php _e('Select your icon size', 'touchsize'); ?></label>
                        </td>
                        <td>
                            <input type="text" id="builder-element-icon-size" name="builder-element-icon-size" />px
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="builder-element-icon-color"><?php _e('Select your icon color', 'touchsize'); ?></label>
                        </td>
                        <td>
                            <input type="text" id="builder-element-icon-color" class="colors-section-picker" value="#000" name="builder-element-icon-color" />
                            <div class="colors-section-picker-div" id=""></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="builder-element-icon-align"><?php _e('Select your icon align', 'touchsize'); ?></label>
                        </td>
                        <td>
                            <select name="builder-element-icon-align" id="builder-element-icon-align">
                               <option value="left"><?php _e('Left', 'touchsize'); ?></option>
                               <option value="center"><?php _e('Center', 'touchsize'); ?></option>
                               <option value="right"><?php _e('Right', 'touchsize'); ?></option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="menu builder-element hidden">
                <h3 class="element-title"><?php _e('Menu element', 'touchsize'); ?></h3>
                <table cellpadding="10">
                     <tr>
                        <td>
                            <label for="menu-admin-label"><?php _e('Admin label:','touchsize');?></label>
                        </td>
                        <td>
                           <input type="text" id="menu-admin-label" name="menu-admin-label" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e('Choose your menu','touchsize'); ?>
                        </td>
                        <td>
                            <?php 
                                $menus = wp_get_nav_menus();
                                if( isset($menus) && is_array($menus) && !empty($menus) ) : ?>
                                    <select name="menu-name" id="menu-name">
                                        <?php foreach($menus as $menu) : ?>
                                            <?php if( is_object($menu) ) : ?>
                                                <option value="<?php echo $menu->term_id; ?>"><?php echo $menu->name; ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="70px">
                            <?php _e('Menu style', 'touchsize'); ?>
                        </td>
                        <td>
                            <select name="menu-styles" id="menu-styles">
                                <option value="style1"><?php _e('Horizontal menu', 'touchsize') ?></option>
                                <?php
                                    if(fields::get_options_value('videotouch_general', 'enable_mega_menu') == 'N'){
                                        echo "<option value='style2'>" . __('Vertical menu', 'touchsize') . "</option>";
                                    }else{
                                        echo '';
                                    }
                                ?>
                                <option value="style3"><?php _e('Menu with logo in the middle', 'touchsize') ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="70px">
                            <?php _e('Uppercase', 'touchsize'); ?>
                        </td>
                        <td>
                            <select name="menu-uppercase" id="menu-uppercase">
                                <option value="menu-uppercase"><?php _e('Yes', 'touchsize') ?></option>
                                <option value="menu-no-uppercase"><?php _e('No', 'touchsize') ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="70px">
                            <?php _e('Menu custom colors', 'touchsize'); ?>
                        </td>
                        <td>
                            <input type="radio" value="no" name="menu-custom" id="menu-custom" /> Default colors
                            <input type="radio" value="yes" name="menu-custom" id="menu-custom" /> Custom colors
                        </td>
                    </tr>
                    <tr class="menu-custom-colors hidden">
                        <td valign="top">
                            <?php _e('Color settings', 'touchsize') ?>:
                        </td>
                        <td>
                            <div class="menu-element-color-options menu-element-bg-color">
                                <div class="color-option-title">Menu background color</div>
                                <input type="text" id="menu-element-bg-color" class="colors-section-picker" name="menu-element-bg-color" value="#DDDDDD" />
                                <div class="colors-section-picker-div" id="menu-element-bg-color-picker"></div>
                           </div>
                            <div class="menu-element-color-options menu-element-text-color">
                                <div class="color-option-title">Menu text color</div>
                                <input type="text" id="menu-element-text-color" class="colors-section-picker" name="menu-element-text-color" value="#FFFFFF" />
                                <div class="colors-section-picker-div" id="menu-element-text-color-picker"></div>
                           </div>
                            <div class="menu-element-color-options menu-element-bg-color-hover">
                                <div class="color-option-title">Menu background color on hover</div>
                                <input type="text" id="menu-element-bg-color-hover" class="colors-section-picker" name="menu-element-bg-color-hover" value="#DDDDDD" />
                                <div class="colors-section-picker-div" id="menu-element-bg-color-hover-picker"></div>
                           </div>
                            <div class="menu-element-color-options menu-element-text-color-hover">
                                <div class="color-option-title">Menu text color on hover</div>
                                <input type="text" id="menu-element-text-color-hover" class="colors-section-picker" name="menu-element-text-color-hover" value="#FFFFFF" />
                                <div class="colors-section-picker-div" id="menu-element-text-color-hover-picker"></div>
                           </div>
                            <div class="menu-element-color-options menu-element-submenu-bg-color">
                                <div class="color-option-title">Submenu background color</div>
                                <input type="text" id="menu-element-submenu-bg-color" class="colors-section-picker" name="menu-element-submenu-bg-color" value="#DDDDDD" />
                                <div class="colors-section-picker-div" id="menu-element-submenu-bg-color-picker"></div>
                           </div>
                            <div class="menu-element-color-options menu-element-submenu-text-color">
                                <div class="color-option-title">Submenu text color</div>
                                <input type="text" id="menu-element-submenu-text-color" class="colors-section-picker" name="menu-element-submenu-text-color" value="#FFFFFF" />
                                <div class="colors-section-picker-div" id="menu-element-submenu-text-color-picker"></div>
                           </div>
                            <div class="menu-element-color-options menu-element-submenu-bg-color-hover">
                                <div class="color-option-title">Submenu background color on hover</div>
                                <input type="text" id="menu-element-submenu-bg-color-hover" class="colors-section-picker" name="menu-element-submenu-bg-color-hover" value="#DDDDDD" />
                                <div class="colors-section-picker-div" id="menu-element-submenu-bg-color-hover-picker"></div>
                           </div>
                            <div class="menu-element-color-options menu-element-submenu-text-color-hover">
                                <div class="color-option-title">Submenu text color on hover</div>
                                <input type="text" id="menu-element-submenu-text-color-hover" class="colors-section-picker" name="menu-element-submenu-text-color-hover" value="#FFFFFF" />
                                <div class="colors-section-picker-div" id="menu-element-submenu-text-color-hover-picker"></div>
                           </div>
                        </td>
                    </tr>
                     <tr>
                        <td width="70px">
                            <?php _e('Menu text align', 'touchsize'); ?>
                        </td>
                        <td>
                            <select name="menu-text-align" id="menu-text-align">
                                <option value="menu-text-align-left"><?php _e('Left', 'touchsize') ?></option>
                                <option value="menu-text-align-right"><?php _e('Right', 'touchsize') ?></option>
                                <option value="menu-text-align-center"><?php _e('Center', 'touchsize') ?></option>
                            </select>
                        </td>
                    </tr>
                </table>
                <script>
                    jQuery(document).ready(function(){
                        if (jQuery('.colors-section-picker-div').length) {
                            jQuery('.colors-section-picker-div').hide();

                            // jQuery('.colors-section-picker-div').farbtastic(".colors-section-picker");

                            jQuery(".colors-section-picker").click(function(e){
                                e.stopPropagation();
                                jQuery(jQuery(this).next()).show();
                            });
                            
                            // jQuery('body').click(function() {
                            //  jQuery('.colors-section-picker-div').hide();
                            // });
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
                    jQuery('input[name="menu-custom"]').change(function(){
                        if ( jQuery(this).val() == 'yes' ) {
                            jQuery('.menu-custom-colors').removeClass('hidden');
                        } else{
                            jQuery('.menu-custom-colors').addClass('hidden');
                        }
                    });
                    jQuery(document).ready(function(){
                        menu_colors_value = jQuery('#menu-custom').val();
                        jQuery('input[value="'+menu_colors_value+'"]').attr('checked','checked')
                    });
                </script>
            </div>
            
            <div class="sidebar builder-element hidden">
                <h3 class="element-title">Sidebar element</h3>
                <table cellpadding="10">
                     <tr>
                        <td>
                            <label for="sidebar-admin-label"><?php _e('Admin label','touchsize');?>:</label>
                        </td>
                        <td>
                           <input type="text" id="sidebar-admin-label" name="sidebar-admin-label" />
                        </td>
                    </tr>
                    <tr>
                        <td width="70px">
                            <?php _e('Sidebars', 'touchsize'); ?>
                        </td>
                        <td>
                            <?php
                                echo ts_sidebars_drop_down('','available-sidebars');
                            ?>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="slider builder-element hidden">
                <h3 class="element-title">Slider element</h3>
                <table cellpadding="10">
                    <tr>
                        <td>
                            <label for="slider-admin-label"><?php _e('Admin label','touchsize');?>:</label>
                        </td>
                        <td>
                           <input type="text" id="slider-admin-label" name="slider-admin-label" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="slider-name"><?php _e( 'Slider to display', 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <select name="slider-name" id="slider-name">
                            <?php 
                                echo '<option value="0">-- ' . __( 'Select slider', 'touchsize' ) . ' --</option>';
                                $show_where_user_can_add_sliders = false;
                                $args = array(
                                    'post_type' => 'ts_slider',
                                    'posts_per_page' => -1,
                                    'post_status' =>'publish'
                                );
                                
                                $sliders = new WP_Query( $args );

                                if ($sliders->have_posts()) {
                                    while ( $sliders->have_posts() ) :
                                        $sliders->the_post();
                                        echo '<option value="' . get_the_ID() .'">' . get_the_title() . '</option>';
                                    endwhile;
                                } else {
                                    $show_where_user_can_add_sliders = true;
                                }

                                wp_reset_postdata();
                            ?>
                            </select>

                            <?php if ($show_where_user_can_add_sliders): ?>
                                <p><?php _e( "Looks like you don't have any slider. You can create one", 'touchsize' ) ?> <strong><a target="_blank" href="<?php echo admin_url( 'edit.php?post_type=ts_slider' ) ?>"><?php _e( 'here', 'touchsize' ) ?></a></strong></p>
                            <?php endif ?>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="list-portfolios builder-element hidden">
                <h3 class="element-title"><?php _e('List portfolios element','touchsize'); ?></h3>
                <table cellpadding="10">
                    <tr>
                        <td>
                            <label for="list-portfolios-admin-label"><?php _e('Admin label:','touchsize'); ?></label>
                        </td>
                        <td>
                           <input type="text" id="list-portfolios-admin-label" name="list-portfolios-admin-label" />
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <label for="list-portfolios-category"><?php _e( 'Category:', 'touchsize' ) ?></label>
                        </td>
                        <td>
                            <?php 
                                $categories = get_categories(array( 'hide_empty' => 0, 'taxonomy' => 'portfolio_register_post_type', 'pad_counts' => true ));
                                if( isset($categories) && !is_wp_error($categories) && !empty($categories) ) : ?>
                                    <select class="ts-custom-select-input" data-placeholder="<?php _e('Select your category', 'touchsize'); ?>" name="list-portfolios-category" id="list-portfolios-category" multiple>   
                                        <option value="0"><?php _e('All', 'touchsize') ?></option>
                                        <?php foreach ($categories as $index => $category): ?>
                                            <option value="<?php echo $category->term_id ?>"><?php echo $category->cat_name ?></option>
                                        <?php endforeach ?>                                    
                                    </select>
                                <?php endif; ?>
                            <div class="ts-option-description">
                                <?php _e('Choose the categories that you want to showcase articles from.', 'touchsize'); ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="list-portfolios-display-mode"><?php _e( 'How to display:', 'touchsize' ) ?></label>
                        </td>
                        <td>
                            <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-portfolios-display-mode" id="list-portfolios-display-mode-selector">
                               <li><img class="image-radio-input clickable-element" data-option="grid" src="<?php echo get_template_directory_uri().'/images/options/grid_view_title_excerpt.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="list" src="<?php echo get_template_directory_uri().'/images/options/list_view_13.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="thumbnails" src="<?php echo get_template_directory_uri().'/images/options/thumb_view.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="big-post" src="<?php echo get_template_directory_uri().'/images/options/big_posts_12.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="super-post" src="<?php echo get_template_directory_uri().'/images/options/super_post_view.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="timeline" src="<?php echo get_template_directory_uri().'/images/options/timeline_view.png'; ?>"></li>
                            </ul>
                            <select class="hidden" name="list-portfolios-display-mode" id="list-portfolios-display-mode">
                                <option value="grid"><?php _e( 'Grid', 'touchsize' ) ?></option>
                                <option value="list"><?php _e( 'List', 'touchsize' ) ?></option>
                                <option value="thumbnails"><?php _e( 'Thumbnails', 'touchsize' ) ?></option>
                                <option value="big-post"><?php _e( 'Big post', 'touchsize' ) ?></option>
                                <option value="super-post"><?php _e( 'Super Post', 'touchsize' ) ?></option>
                                <option value="timeline"><?php _e( 'Timeline', 'touchsize' ) ?></option>
                            </select>
                            <div class="ts-option-description">
                                <?php _e('Choose your article view type. Depending on what type of article showcase layout you select you will get different options. You can read more about view types in our documentation files: ', 'touchsize'); echo $touchsize_com; ?>
                            </div>
                        </td>
                    </tr>
                </table>

                <div id="list-portfolios-display-mode-options">
                    <!-- Grid options -->
                    <div class="list-portfolios-grid hidden">
                        <table cellpadding="10">
                            <tr>
                                <td>
                                    <label><?php _e( 'Behavior:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-portfolios-grid-behavior" id="list-portfolios-grid-behavior-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="none" src="<?php echo get_template_directory_uri().'/images/options/behavior_none.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="carousel" src="<?php echo get_template_directory_uri().'/images/options/behavior_carousel.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="masonry" src="<?php echo get_template_directory_uri().'/images/options/behavior_masonry.png'; ?>"></li>
                                    </ul>
                                    <select class="hidden" name="list-portfolios-grid-behavior" id="list-portfolios-grid-behavior">
                                        <option value="none"><?php _e( 'Normal', 'touchsize' ) ?></option>
                                        <option value="carousel"><?php _e( 'Carousel', 'touchsize' ) ?></option>
                                        <option value="masonry"><?php _e( 'Masonry', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Choose your posts behavior - you can have them just shown, or you can activate the carousel. If carousel is selected your articles will show up in a line with arrows for navigation. If masonry bahevior is selected - your articles will be arranged in to fit it. Be aware that activating the masonry option the crop settings for image sizes tab will be overwritten.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-portfolios-grid-title"><?php _e( 'Title position', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-portfolios-grid-title" id="list-portfolios-grid-title-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="title-above-image" src="<?php echo get_template_directory_uri().'/images/options/grid_view_title_image.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="title-above-excerpt" src="<?php echo get_template_directory_uri().'/images/options/grid_view_title_excerpt.png'; ?>"></li>
                                    </ul>
                                    <select class="hidden" name="list-portfolios-grid-title" id="list-portfolios-grid-title">
                                        <option value="title-above-image"><?php _e( 'Above image', 'touchsize' ) ?></option>
                                        <option value="title-above-excerpt"><?php _e( 'Above excerpt', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Select your title position for you grid posts. You can either have it above the image of above the excerpt. Note that sometimes title may change the position of the meta (date, categories, author) as well.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-portfolios-grid-show-meta"><?php _e( 'Show meta', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="radio" name="list-portfolios-grid-show-meta" id="list-portfolios-grid-show-meta-y" value="y"  checked = "checked"  /> 
                                    <label for="list-portfolios-grid-show-meta-y"><?php _e( 'Yes', 'touchsize' ) ?></label>
                                    <input type="radio" name="list-portfolios-grid-show-meta" id="list-portfolios-grid-show-meta-n" value="n" />
                                    <label for="list-portfolios-grid-show-meta-n"><?php _e( 'No', 'touchsize' ) ?></label>
                                    <div class="ts-option-description">
                                        <?php _e('You can choose to show or to hide your meta details for your articles. Meta values include date, author, categories and other article details.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-portfolios-grid-el-per-row"><?php _e( 'Elements per row', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-portfolios-grid-el-per-row" id="list-portfolios-grid-el-per-row-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="1" src="<?php echo get_template_directory_uri().'/images/options/per_row_1.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="2" src="<?php echo get_template_directory_uri().'/images/options/per_row_2.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="3" src="<?php echo get_template_directory_uri().'/images/options/per_row_3.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="4" src="<?php echo get_template_directory_uri().'/images/options/per_row_4.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="6" src="<?php echo get_template_directory_uri().'/images/options/per_row_6.png'; ?>"></li>
                                    </ul>
                                    <select class="hidden" name="list-portfolios-grid-el-per-row" id="list-portfolios-grid-el-per-row">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3" selected="selected">3</option>
                                        <option value="4">4</option>
                                        <option value="6">6</option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Your articles will be shown columns. Choose how mane columns do you want to use per line. Note that for mobile devices you will get only ONE element per row for better usability.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-portfolios-grid-nr-of-posts"><?php _e( 'How many posts to extract', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="text" value="" name="list-portfolios-grid-nr-of-posts" id="list-portfolios-grid-nr-of-posts" size="4"/>
                                    <div class="ts-option-description">
                                        <?php _e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-portfolios-grid-order-by"><?php _e( 'Order by', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-portfolios-grid-order-by" id="list-portfolios-grid-order-by">
                                        <option value="date"><?php _e( 'Date', 'touchsize' ) ?></option>
                                        <option value="comments"><?php _e( 'Comments', 'touchsize' ) ?></option>
                                        <option value="views"><?php _e( 'Views', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-portfolios-grid-order-direction"><?php _e( 'Order direction', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-portfolios-grid-order-direction" id="list-portfolios-grid-order-direction">
                                        <option value="asc"><?php _e( 'ASC', 'touchsize' ) ?></option>
                                        <option value="desc" selected="selected"><?php _e( 'DESC', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-portfolios-grid-special-effects"><?php _e( 'Special effects', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-portfolios-grid-special-effects" id="list-portfolios-grid-special-effects">
                                        <option value="none"><?php _e('No effect', 'touchsize') ?></option>
                                        <option value="opacited"><?php _e('Fade in', 'touchsize') ?></option>
                                        <option value="rotate-in"><?php _e('Rotate in', 'touchsize') ?></option>
                                        <option value="3dflip"><?php _e('3d flip', 'touchsize') ?></option>
                                        <option value="scaler"><?php _e('Scale', 'touchsize') ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- List options -->
                    <div class="list-portfolios-list hidden">

                        <table cellpadding="10">
                            <tr>
                                <td>
                                    <label for="list-portfolios-list-show-meta"><?php _e( 'Show meta', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="radio" name="list-portfolios-list-show-meta" id="list-portfolios-list-show-meta-y" value="y"  checked = "checked"  /> 
                                    <label for="list-portfolios-list-show-meta-y"><?php _e( 'Yes', 'touchsize' ) ?></label>
                                    <input type="radio" name="list-portfolios-list-show-meta" id="list-portfolios-list-show-meta-n" value="n" />
                                    <label for="list-portfolios-list-show-meta-n"><?php _e( 'No', 'touchsize' ) ?></label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-portfolios-list-nr-of-posts"><?php _e( 'How many posts to extract', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="text" value="" name="list-portfolios-list-nr-of-posts" id="list-portfolios-list-nr-of-posts" size="4"/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php _e( 'Content split', 'touchsize' ) ?>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-portfolios-list-image-split" id="list-portfolios-list-image-split-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="1-3" src="<?php echo get_template_directory_uri().'/images/options/list_view_13.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="1-2" src="<?php echo get_template_directory_uri().'/images/options/list_view_12.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="3-4" src="<?php echo get_template_directory_uri().'/images/options/list_view_34.png'; ?>"></li>
                                    </ul>
                                    <select class="hidden" name="list-portfolios-list-image-split" id="list-portfolios-list-image-split">
                                        <option value="1-3">1/3</option>
                                        <option value="1-2">1/2</option>
                                        <option value="3-4">3/4</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-portfolios-list-order-by"><?php _e( 'Order by', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-portfolios-list-order-by" id="list-portfolios-list-order-by">
                                        <option value="date"><?php _e( 'Date', 'touchsize' ) ?></option>
                                        <option value="comments"><?php _e( 'Comments', 'touchsize' ) ?></option>
                                        <option value="views"><?php _e( 'Views', 'touchsize' ) ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-portfolios-list-order-direction"><?php _e( 'Order direction', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-portfolios-list-order-direction" id="list-portfolios-list-order-direction">
                                        <option value="asc"><?php _e( 'ASC', 'touchsize' ) ?></option>
                                        <option value="desc" selected="selected"><?php _e( 'DESC', 'touchsize' ) ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-portfolios-list-special-effects"><?php _e( 'Special effects:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <select name="list-portfolios-list-special-effects" id="list-portfolios-list-special-effects">
                                        <option value="none"><?php _e('No effect', 'touchsize') ?></option>
                                        <option value="opacited"><?php _e('Fade in', 'touchsize') ?></option>
                                        <option value="rotate-in"><?php _e('Rotate in', 'touchsize') ?></option>
                                        <option value="3dflip"><?php _e('3d flip', 'touchsize') ?></option>
                                        <option value="scaler"><?php _e('Scale', 'touchsize') ?></option>
                                    </select>
                                </td>
                            </tr>

                        </table>
                    </div>

                    <!-- Thumbnail options -->
                    <div class="list-portfolios-thumbnails hidden">
                        <table cellpadding="10">
                            <tr>
                                <td>
                                    <label><?php _e( 'Enable carousel', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="radio" name="list-portfolios-thumbnail-enable-carousel" id="list-portfolios-thumbnail-enable-carousel-y" value="y" /> 
                                    <label for="list-portfolios-thumbnail-enable-carousel-y"><?php _e( 'Yes', 'touchsize' ) ?></label>
                                    <input type="radio" name="list-portfolios-thumbnail-enable-carousel" id="list-portfolios-thumbnail-enable-carousel-n" value="n"  checked = "checked"  />
                                    <label for="list-portfolios-thumbnail-enable-carousel-n"><?php _e( 'No', 'touchsize' ) ?></label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-portfolios-thumbnail-posts-per-row"><?php _e( 'Number of posts per row', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-portfolios-thumbnail-posts-per-row" id="list-portfolios-thumbnail-posts-per-row-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="1" src="<?php echo get_template_directory_uri().'/images/options/per_row_1.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="2" src="<?php echo get_template_directory_uri().'/images/options/per_row_2.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="3" src="<?php echo get_template_directory_uri().'/images/options/per_row_3.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="4" src="<?php echo get_template_directory_uri().'/images/options/per_row_4.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="6" src="<?php echo get_template_directory_uri().'/images/options/per_row_6.png'; ?>"></li>
                                    </ul>
                                    <select class="hidden" name="list-portfolios-thumbnail-posts-per-row" id="list-portfolios-thumbnail-posts-per-row">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="6">6</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-portfolios-thumbnail-limit"><?php _e( 'How many posts to extract', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="text" value="" name="list-portfolios-thumbnail-limit"  id="list-portfolios-thumbnail-limit" size="4"/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-portfolios-thumbnail-order-by"><?php _e( 'Order by', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-portfolios-thumbnail-order-by" id="list-portfolios-thumbnail-order-by">
                                        <option value="date"><?php _e( 'Date', 'touchsize' ) ?></option>
                                        <option value="comments"><?php _e( 'Comments', 'touchsize' ) ?></option>
                                        <option value="views"><?php _e( 'Views', 'touchsize' ) ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-portfolios-thumbnail-order-direction"><?php _e( 'Order direction', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-portfolios-thumbnail-order-direction" id="list-portfolios-thumbnail-order-direction">
                                        <option value="asc"><?php _e( 'ASC', 'touchsize' ) ?></option>
                                        <option value="desc" selected="selected"><?php _e( 'DESC', 'touchsize' ) ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-portfolios-thumbnail-special-effects"><?php _e( 'Special effects:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <select name="list-portfolios-thumbnail-special-effects" id="list-portfolios-thumbnail-special-effects">
                                        <option value="none"><?php _e('No effect', 'touchsize') ?></option>
                                        <option value="opacited"><?php _e('Fade in', 'touchsize') ?></option>
                                        <option value="rotate-in"><?php _e('Rotate in', 'touchsize') ?></option>
                                        <option value="3dflip"><?php _e('3d flip', 'touchsize') ?></option>
                                        <option value="scaler"><?php _e('Scale' , 'touchsize') ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-portfolios-thumbnail-gutter"><?php _e( 'Remove gutter(space) between articles:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <select name="list-portfolios-thumbnail-gutter" id="list-portfolios-thumbnail-gutter">
                                        <option value="n"><?php _e('No', 'touchsize') ?></option>
                                        <option value="y"><?php _e('Yes', 'touchsize') ?></option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="list-portfolios-big-post hidden">
                        <table cellpadding="10">
                            <tr>
                                <td>
                                    <label for="list-portfolios-big-post-title"><?php _e( 'Title position:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-portfolios-big-post-title" id="list-portfolios-big-post-title-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="title-above-image" src="<?php echo get_template_directory_uri().'/images/options/grid_view_title_image.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="title-above-excerpt" src="<?php echo get_template_directory_uri().'/images/options/grid_view_title_excerpt.png'; ?>"></li>
                                    </ul>
                                    <select class="hidden" name="list-portfolios-big-post-title" id="list-portfolios-big-post-title">
                                        <option value="title-above-image"><?php _e( 'Above image', 'touchsize' ) ?></option>
                                        <option value="title-above-excerpt"><?php _e( 'Above excerpt', 'touchsize' ) ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-portfolios-big-post-show-meta"><?php _e( 'Show meta:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <input type="radio" name="list-portfolios-big-post-show-meta" id="list-portfolios-big-post-show-meta-y" value="y"  checked = "checked"  /> 
                                    <label for="list-portfolios-big-post-show-meta-y"><?php _e( 'Yes', 'touchsize' ) ?></label>
                                    <input type="radio" name="list-portfolios-big-post-show-meta" id="list-portfolios-big-post-show-meta-n" value="n" />
                                    <label for="list-portfolios-big-post-show-meta-n"><?php _e( 'No', 'touchsize' ) ?></label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-portfolios-big-post-nr-of-posts"><?php _e( 'How many posts to extract:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <input type="text" value="" name="list-portfolios-big-post-nr-of-posts" id="list-portfolios-big-post-nr-of-posts" size="4"/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php _e( 'Content split', 'touchsize' ) ?>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-portfolios-big-post-image-split" id="list-portfolios-big-post-image-split-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="1-3" src="<?php echo get_template_directory_uri().'/images/options/list_view_13.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="1-2" src="<?php echo get_template_directory_uri().'/images/options/list_view_12.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="3-4" src="<?php echo get_template_directory_uri().'/images/options/list_view_34.png'; ?>"></li>
                                    </ul>
                                    <select class="hidden" name="list-portfolios-big-post-image-split" id="list-portfolios-big-post-image-split">
                                        <option value="1-3">1/3</option>
                                        <option value="1-2">1/2</option>
                                        <option value="3-4">3/4</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-portfolios-big-post-order-by"><?php _e( 'Order by', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-portfolios-big-post-order-by" id="list-portfolios-big-post-order-by">
                                        <option value="date"><?php _e( 'Date', 'touchsize' ) ?></option>
                                        <option value="comments"><?php _e( 'Comments', 'touchsize' ) ?></option>
                                        <option value="views"><?php _e( 'Views', 'touchsize' ) ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-portfolios-big-post-order-direction"><?php _e( 'Order direction', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-portfolios-big-post-order-direction" id="list-portfolios-big-post-order-direction">
                                        <option value="asc"><?php _e( 'ASC', 'touchsize' ) ?></option>
                                        <option selected="selected" value="desc"><?php _e( 'DESC', 'touchsize' ) ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-portfolios-big-post-show-related"><?php _e( 'Show related posts', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="radio" name="list-portfolios-big-post-show-related" id="list-portfolios-big-post-show-related-y" value="y"  checked = "checked"  /> 
                                    <label for="list-portfolios-big-post-show-related-y"><?php _e( 'Yes', 'touchsize' ) ?></label>
                                    <input type="radio" name="list-portfolios-big-post-show-related" id="list-portfolios-big-post-show-related-n" value="n" />
                                    <label for="list-portfolios-big-post-show-related-n"><?php _e( 'No', 'touchsize' ) ?></label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-portfolios-big-post-special-effects"><?php _e( 'Special effects', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-portfolios-big-post-special-effects" id="list-portfolios-big-post-special-effects">
                                        <option value="none"><?php _e('No effect', 'touchsize') ?></option>
                                        <option value="opacited"><?php _e('Fade in', 'touchsize') ?></option>
                                        <option value="rotate-in"><?php _e('Rotate in', 'touchsize') ?></option>
                                        <option value="3dflip"><?php _e('3d flip', 'touchsize') ?></option>
                                        <option value="scaler"><?php _e('Scale', 'touchsize') ?></option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="list-portfolios-super-post hidden">
                        <table cellpadding="10">
                            <tr>
                                <td>
                                    <label for="list-portfolios-super-post-posts-per-row"><?php _e( 'Number of posts per row', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-portfolios-super-post-posts-per-row" id="list-portfolios-super-post-posts-per-row-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="1" src="<?php echo get_template_directory_uri().'/images/options/per_row_1.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="2" src="<?php echo get_template_directory_uri().'/images/options/per_row_2.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="3" src="<?php echo get_template_directory_uri().'/images/options/per_row_3.png'; ?>"></li>
                                    </ul>
                                    <select class="hidden" name="list-portfolios-super-post-posts-per-row" id="list-portfolios-super-post-posts-per-row">
                                        <option value="1" selected="selected">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-portfolios-super-post-limit"><?php _e( 'How many posts to extract', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="text" value="" name="list-portfolios-super-post-limit"  id="list-portfolios-super-post-limit" size="4"/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-portfolios-super-post-order-by"><?php _e( 'Order by', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-portfolios-super-post-order-by" id="list-portfolios-super-post-order-by">
                                        <option value="date"><?php _e( 'Date', 'touchsize' ) ?></option>
                                        <option value="comments"><?php _e( 'Comments', 'touchsize' ) ?></option>
                                        <option value="views"><?php _e( 'Views', 'touchsize' ) ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                     <label for="list-portfolios-super-post-order-direction"><?php _e( 'Order direction', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-portfolios-super-post-order-direction" id="list-portfolios-super-post-order-direction">
                                        <option value="asc"><?php _e( 'ASC', 'touchsize' ) ?></option>
                                        <option selected="selected" value="desc"><?php _e( 'DESC', 'touchsize' ) ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-portfolios-super-post-special-effects"><?php _e( 'Special effects:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <select name="list-portfolios-super-post-special-effects" id="list-portfolios-super-post-special-effects">
                                        <option value="none"><?php _e('No effect', 'touchsize') ?></option>
                                        <option value="opacited"><?php _e('Fade in', 'touchsize') ?></option>
                                        <option value="rotate-in"><?php _e('Rotate in', 'touchsize') ?></option>
                                        <option value="3dflip"><?php _e('3d flip', 'touchsize') ?></option>
                                        <option value="scaler"><?php _e('Scale', 'touchsize') ?></option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="list-portfolios-timeline hidden">

                        <table cellpadding="10">
                            <tr>
                                <td>
                                    <label for="list-portfolios-timeline-show-meta"><?php _e( 'Show meta', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="radio" name="list-portfolios-timeline-show-meta" id="list-portfolios-timeline-show-meta-y" value="y"  checked = "checked"  /> 
                                    <label for="list-portfolios-timeline-show-meta-y"><?php _e( 'Yes', 'touchsize' ) ?></label>
                                    <input type="radio" name="list-portfolios-timeline-show-meta" id="list-portfolios-timeline-show-meta-n" value="n" />
                                    <label for="list-portfolios-timeline-show-meta-n"><?php _e( 'No', 'touchsize' ) ?></label>
                                    <div class="ts-option-description">
                                        <?php _e('You can choose to show or to hide your meta details for your articles. Meta values include date, author, categories and other article details.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-portfolios-timeline-image"><?php _e( 'Show image', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-portfolios-timeline-image" id="list-portfolios-timeline-image">
                                        <option value="y"><?php _e( 'Yes', 'touchsize' ) ?></option>
                                        <option value="n"><?php _e( 'No', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Display image', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr class="list-portfolios-timeline-nr-of-posts">
                                <td>
                                    <label for="list-portfolios-timeline-post-limit"><?php _e( 'How many posts to extract', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="text" value="" name="list-portfolios-timeline-post-limit" id="list-portfolios-timeline-post-limit" size="4"/>
                                    <div class="ts-option-description">
                                        <?php _e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-portfolios-timeline-order-by"><?php _e( 'Order by', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-portfolios-timeline-order-by" id="list-portfolios-timeline-order-by">
                                        <option value="date"><?php _e( 'Date', 'touchsize' ) ?></option>
                                        <option value="comments"><?php _e( 'Comments', 'touchsize' ) ?></option>
                                        <option value="views"><?php _e( 'Views', 'touchsize' ) ?></option>
                                        <option value="likes"><?php _e( 'Likes', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-portfolios-timeline-order-direction"><?php _e( 'Order direction', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-portfolios-timeline-order-direction" id="list-portfolios-timeline-order-direction">
                                        <option value="asc"><?php _e( 'ASC', 'touchsize' ) ?></option>
                                        <option value="desc" selected="selected"><?php _e( 'DESC', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-portfolios-timeline-pagination"><?php _e( 'Enable pagination', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-portfolios-timeline-pagination" id="list-portfolios-timeline-pagination">
                                        <option selected="selected" value="n"><?php _e('None', 'touchsize') ?></option>
                                        <option value="y"><?php _e('Enable pagination', 'touchsize') ?></option>
                                        <option value="load-more"><?php _e('Load more', 'touchsize') ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('You can add pagination.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                
                </div>
            </div>

            <div class="testimonials builder-element hidden">
                <h3 class="element-title"><?php _e('Testimonials element', 'touchsize'); ?></h3>
                <table cellpadding="10">
                    <tr>
                        <td>
                            <label for="testimonials-admin-label"><?php _e('Admin label','touchsize');?>:</label>
                        </td>
                        <td>
                           <input type="text" id="testimonials-admin-label" name="testimonials-admin-label" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="testimonials-row"><?php _e( 'Number of testimonials per row:', 'touchsize' ); ?></label>
                        </td>
                        <td>
                            <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#testimonials-row" id="testimonials-row-selector">
                               <li><img class="image-radio-input clickable-element" data-option="1" src="<?php echo get_template_directory_uri().'/images/options/per_row_1.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="2" src="<?php echo get_template_directory_uri().'/images/options/per_row_2.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="3" src="<?php echo get_template_directory_uri().'/images/options/per_row_3.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="4" src="<?php echo get_template_directory_uri().'/images/options/per_row_4.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="6" src="<?php echo get_template_directory_uri().'/images/options/per_row_6.png'; ?>"></li>
                            </ul>
                            <select name="testimonials-row" id="testimonials-row">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="6">6</option>
                            </select>   
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="testimonials-enable-carousel"><?php _e( 'Enable carousel', 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <select name="testimonials-enable-carousel" id="testimonials-enable-carousel">
                                <option value="N">No</option>
                                <option value="Y">Yes</option>
                            </select>   
                        </td>
                    </tr>
                </table>
                <ul id="testimonials_items">
                   
                </ul>
                   
                <input type="hidden" id="testimonials_content" value="" />
                <input type="button" class="button ts-multiple-add-button" data-element-name="testimonials" id="testimonials_add_item" value=" <?php _e('Add New Testimonial', 'touchsize'); ?>" />
                <?php
                    echo '<script id="testimonials_items_template" type="text/template">';
                    echo '<li id="list-item-id-{{item-id}}" class="testimonials-item ts-multiple-add-list-element">
                            <div class="sortable-meta-element"><span class="tab-arrow icon-down"></span> <span class="testimonials-item-tab ts-multiple-item-tab">Item: {{slide-number}}</span></div>
                            <div class="hidden">
                                <table>
                                    <tr>
                                        <td>' . __( "Testimonial image", "touchsize" ) .'</td>
                                        <td>
                                            <input type="text" name="testimonials-{{item-id}}-image" id="testimonials-{{item-id}}-image" value="" data-role="media-url" />
                                            <input type="hidden" id="slide_media_id-{{item-id}}" name="testimonials_media_id-{{item-id}}" value=""  data-role="media-id" />
                                            <input type="button" id="uploader_{{item-id}}" data-element-name="testimonials"  class="button button-primary" value="' . __( "Upload", "touchsize" ) . '" />
                                            <div id="image-preview-{{item-id}}"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="testimonials-{{item-id}}-text">' . __( "Write your text here:", "touchsize" ) . '</label>
                                        </td>
                                        <td>
                                            <textarea data-builder-name="text" name="testimonials[{{item-id}}][text]" id="testimonials-{{item-id}}-text" cols="45" rows="5"></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="testimonials-{{item-id}}-name">' . __( "Enter your person name:", "touchsize" ) . '</label>
                                        </td>
                                        <td>
                                            <input data-builder-name="name" type="text" id="testimonials-{{item-id}}-name" name="testimonials[{{item-id}}][name]" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="testimonials-{{item-id}}-company">' . __( "Enter company name:", "touchsize" ) . '</label>
                                        </td>
                                        <td>
                                            <input data-builder-name="company" type="text" id="testimonials-{{item-id}}-company" name="testimonials[{{item-id}}][company]" />
                                        </td>
                                    </tr>
                                     <tr>
                                        <td>
                                            <label for="testimonials-{{item-id}}-url">' . __( "Enter your url here:", "touchsize" ) . '</label>
                                        </td>
                                        <td>
                                            <input data-builder-name="url" type="text" id="testimonials-{{item-id}}-url" name="testimonials[{{item-id}}][url]" />
                                        </td>
                                    </tr>
                                </table>
                                <input type="hidden" data-builder-name="item_id" value="{{item-id}}" name="testimonials[{{item-id}}][id]" />
                                <input type="button" class="button button-primary remove-item" value="' . __('Remove', 'touchsize') . '" />
                                <a href="#" data-item="testimonials-item" data-element-name="testimonials" data-increment="testimonials-items" class="button button-primary ts-multiple-item-duplicate">' . __('Duplicate Item', 'touchsize') . '</a>
                            </div>
                        </li>';
                    echo '</script>';
               ?>
              
            </div>

            <div class="last-posts builder-element hidden">
                <h3 class="element-title"><?php _e('List posts element','touchsize');?></h3>
                <!-- Select category -->
                <table cellpadding="10">
                    <tr>
                        <td>
                            <label for="last-posts-admin-label"><?php _e('Admin label','touchsize');?>:</label>
                        </td>
                        <td>
                           <input type="text" id="last-posts-admin-label" name="last-posts-admin-label" />
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <label for="last-posts-category"><?php _e( 'Category', 'touchsize' ) ?>:</label>
                        </td>
                        <td>                           
                            <select data-placeholder="<?php _e('Select your category', 'touchsize'); ?>" class="ts-custom-select-input" name="last-posts-category" id="last-posts-category" multiple>
                                <?php $categories = get_categories(array( 'hide_empty' => 0, 'show_option_all' => '' )); ?>
                                <?php if ( isset($categories) && is_array($categories) && !empty($categories) ) : ?>
                                    <?php $i = 1; foreach ($categories as $index => $category): ?>
                                        <?php if( is_object($category) ) : ?>
                                            <?php if( $i === 1 ) echo '<option value="0">' . __('All', 'touchsize') . '</option>'; ?>
                                            <option value="<?php echo $category->cat_ID ?>"><?php echo $category->cat_name ?></option>
                                        <?php endif; $i++; ?>
                                    <?php endforeach ?>                                    
                                <?php endif ?>
                            </select>
                            <div class="ts-option-description">
                                <?php _e('Choose the categories that you want to showcase articles from.', 'touchsize'); ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label><?php _e( 'Show only featured', 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <select name="last-posts-featured" id="last-posts-featured">
                                <option value="y"><?php _e( 'Yes', 'touchsize' ) ?></option>
                                <option value="n" selected="selected"><?php _e( 'No', 'touchsize' ) ?></option>
                            </select>

                            <div class="ts-option-description">
                                <?php _e('You can display only featured posts', 'touchsize'); ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="last-posts-exclude"><?php _e( "Exclude post ID's", 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <input type="text" value="" name="last-posts-exclude" id="last-posts-exclude" size="4"/>
                            <div class="ts-option-description">
                                <?php _e('Insert the IDs of the posts you want to exclude from showing.', 'touchsize'); ?> (ex: <b>100,101,102,104</b>)
                            </div>
                        </td>
                    </tr>
                     <tr class="last-posts-exclude">
                        <td>
                            <label for="last-posts-exclude-first"><?php _e( "Exclude number of first posts", 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <input type="text" value="" name="last-posts-exclude-first" id="last-posts-exclude-first" size="4"/>
                            <div class="ts-option-description">
                                <?php _e('Insert the number of the posts you want to exclude from showing.', 'touchsize'); ?> 
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="last-posts-display-mode"><?php _e( 'How to display', 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#last-posts-display-mode" id="last-posts-display-mode-selector">
                               <li><img class="image-radio-input clickable-element" data-option="grid" src="<?php echo get_template_directory_uri().'/images/options/grid_view.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="list" src="<?php echo get_template_directory_uri().'/images/options/list_view.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="thumbnails" src="<?php echo get_template_directory_uri().'/images/options/thumb_view.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="big-post" src="<?php echo get_template_directory_uri().'/images/options/big_posts_view.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="super-post" src="<?php echo get_template_directory_uri().'/images/options/super_post_view.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="timeline" src="<?php echo get_template_directory_uri().'/images/options/timeline_view.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="mosaic" src="<?php echo get_template_directory_uri().'/images/options/mosaic_view.png'; ?>"></li>
                            </ul>
                            <select class="select_2" class="hidden" name="last-posts-display-mode" id="last-posts-display-mode">
                                <option value="grid"><?php _e( 'Grid', 'touchsize' ) ?></option>
                                <option value="list"><?php _e( 'List', 'touchsize' ) ?></option>
                                <option value="thumbnails"><?php _e( 'Thumbnails', 'touchsize' ) ?></option>
                                <option value="big-post"><?php _e( 'Big post', 'touchsize' ) ?></option>
                                <option value="super-post"><?php _e( 'Super Post', 'touchsize' ) ?></option>
                                <option value="timeline"><?php _e( 'Timeline Post', 'touchsize' ) ?></option>
                                <option value="mosaic"><?php _e( 'mosaic Post', 'touchsize' ) ?></option>
                            </select>
                            <div class="ts-option-description">
                                <?php _e('Choose your article view type. Depending on what type of article showcase layout you select you will get different options. You can read more about view types in our documentation files: ', 'touchsize'); echo $touchsize_com; ?>
                            </div>
                        </td>
                    </tr>
                </table>

                <div id="last-posts-display-mode-options">
                    <!-- Grid options -->
                    <div class="last-posts-grid hidden">
                        <table cellpadding="10">
                            <tr>
                                <td>
                                    <label><?php _e( 'Behavior', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#last-posts-grid-behavior" id="last-posts-grid-behavior-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="none" src="<?php echo get_template_directory_uri().'/images/options/behavior_none.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="carousel" src="<?php echo get_template_directory_uri().'/images/options/behavior_carousel.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="masonry" src="<?php echo get_template_directory_uri().'/images/options/behavior_masonry.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="scroll" src="<?php echo get_template_directory_uri().'/images/options/behavior_scroll.png'; ?>"></li>
                                    </ul>
                                    <select class="hidden" name="last-posts-grid-behavior" id="last-posts-grid-behavior">
                                        <option value="none"><?php _e( 'Normal', 'touchsize' ) ?></option>
                                        <option value="carousel"><?php _e( 'Carousel', 'touchsize' ) ?></option>
                                        <option value="masonry"><?php _e( 'Masonry', 'touchsize' ) ?></option>
                                        <option value="scroll"><?php _e( 'Scroll', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Choose your posts behavior - you can have them just shown, or you can activate the carousel. If carousel is selected your articles will show up in a line with arrows for navigation. If masonry bahevior is selected - your articles will be arranged in to fit it. Be aware that activating the masonry option the crop settings for image sizes tab will be overwritten.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-grid-title"><?php _e( 'Title position', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#last-posts-grid-title" id="last-posts-grid-title-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="title-above-image" src="<?php echo get_template_directory_uri().'/images/options/grid_view_title_image.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="title-above-excerpt" src="<?php echo get_template_directory_uri().'/images/options/grid_view_title_excerpt.png'; ?>"></li>
                                    </ul>
                                    <select class="hidden" name="last-posts-grid-title" id="last-posts-grid-title">
                                        <option value="title-above-image"><?php _e( 'Above image', 'touchsize' ) ?></option>
                                        <option value="title-above-excerpt" selected="selected"><?php _e( 'Above excerpt', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Select your title position for you grid posts. You can either have it above the image of above the excerpt. Note that sometimes title may change the position of the meta (date, categories, author) as well.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-grid-show-meta"><?php _e( 'Show meta', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="radio" name="last-posts-grid-show-meta" id="last-posts-grid-show-meta-y" value="y"  checked = "checked"  /> 
                                    <label for="last-posts-grid-show-meta-y"><?php _e( 'Yes', 'touchsize' ) ?></label>
                                    <input type="radio" name="last-posts-grid-show-meta" id="last-posts-grid-show-meta-n" value="n" />
                                    <label for="last-posts-grid-show-meta-n"><?php _e( 'No', 'touchsize' ) ?></label>
                                    <div class="ts-option-description">
                                        <?php _e('You can choose to show or to hide your meta details for your articles. Meta values include date, author, categories and other article details.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-grid-el-per-row"><?php _e( 'Elements per row', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#last-posts-grid-el-per-row" id="last-posts-grid-el-per-row-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="1" src="<?php echo get_template_directory_uri().'/images/options/per_row_1.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="2" src="<?php echo get_template_directory_uri().'/images/options/per_row_2.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="3" src="<?php echo get_template_directory_uri().'/images/options/per_row_3.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="4" src="<?php echo get_template_directory_uri().'/images/options/per_row_4.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="6" src="<?php echo get_template_directory_uri().'/images/options/per_row_6.png'; ?>"></li>
                                    </ul>
                                    <select class="hidden" name="last-posts-grid-el-per-row" id="last-posts-grid-el-per-row">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3" selected="selected">3</option>
                                        <option value="4">4</option>
                                        <option value="6">6</option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Your articles will be shown columns. Choose how mane columns do you want to use per line. Note that for mobile devices you will get only ONE element per row for better usability.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr class="last-posts-grid-nr-of-posts">
                                <td>
                                    <label for="last-posts-grid-nr-of-posts"><?php _e( 'How many posts to extract', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="text" value="" name="last-posts-grid-nr-of-posts" id="last-posts-grid-nr-of-posts" size="4"/>
                                    <div class="ts-option-description">
                                        <?php _e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-grid-related"><?php _e( 'Show related posts', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="last-posts-grid-related" id="last-posts-grid-related">
                                        <option value="y"><?php _e( 'Yes', 'touchsize' ); ?></option>
                                        <option selected="selected" value="n"><?php _e( 'No', 'touchsize' ); ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('You can set each of your big posts to show related articles below. A list with other articles will appear below.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-grid-order-by"><?php _e( 'Order by', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="last-posts-grid-order-by" id="last-posts-grid-order-by">
                                        <option value="date"><?php _e( 'Date', 'touchsize' ) ?></option>
                                        <option value="comments"><?php _e( 'Comments', 'touchsize' ) ?></option>
                                        <option value="views"><?php _e( 'Views', 'touchsize' ) ?></option>
                                        <option value="likes"><?php _e( 'Likes', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-grid-order-direction"><?php _e( 'Order direction', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="last-posts-grid-order-direction" id="last-posts-grid-order-direction">
                                        <option value="asc"><?php _e( 'ASC', 'touchsize' ) ?></option>
                                        <option selected="selected" value="desc" selected="selected"><?php _e( 'DESC', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-grid-special-effects"><?php _e( 'Special effects', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="last-posts-grid-special-effects" id="last-posts-grid-special-effects">
                                        <option value="none"><?php _e('No effect', 'touchsize') ?></option>
                                        <option value="opacited"><?php _e('Fade in', 'touchsize') ?></option>
                                        <option value="rotate-in"><?php _e('Rotate in', 'touchsize') ?></option>
                                        <option value="3dflip"><?php _e('3d flip', 'touchsize') ?></option>
                                        <option value="scaler"><?php _e('Scale', 'touchsize') ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr class="last-posts-grid-pagination">
                                <td>
                                    <label for="last-posts-grid-pagination"><?php _e( 'Enable pagination', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="last-posts-grid-pagination" id="last-posts-grid-pagination">
                                        <option selected="selected" value="n"><?php _e('None', 'touchsize') ?></option>
                                        <option value="y"><?php _e('Enable pagination', 'touchsize') ?></option>
                                        <option value="load-more"><?php _e('Load more', 'touchsize') ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('You can add pagination.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- List options -->
                    <div class="last-posts-list hidden">

                        <table cellpadding="10">
                           <!--  <tr>
                                <td>
                                    <label for="last-posts-list-title"><?php _e( 'Title:', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="last-posts-list-title" id="last-posts-list-title">
                                        <option value="title-above-image"><?php _e( 'Above image', 'touchsize' ) ?></option>
                                        <option value="title-above-excerpt"><?php _e( 'Above excerpt', 'touchsize' ) ?></option>
                                    </select>
                                </td>
                            </tr> -->
                            <tr>
                                <td>
                                    <label for="last-posts-list-show-meta"><?php _e( 'Show meta', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="radio" name="last-posts-list-show-meta" id="last-posts-list-show-meta-y" value="y"  checked = "checked"  /> 
                                    <label for="last-posts-list-show-meta-y"><?php _e( 'Yes', 'touchsize' ) ?></label>
                                    <input type="radio" name="last-posts-list-show-meta" id="last-posts-list-show-meta-n" value="n" />
                                    <label for="last-posts-list-show-meta-n"><?php _e( 'No', 'touchsize' ) ?></label>
                                    <div class="ts-option-description">
                                        <?php _e('You can choose to show or to hide your meta details for your articles. Meta values include date, author, categories and other article details.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr class="last-posts-list-nr-of-posts">
                                <td>
                                    <label for="last-posts-list-nr-of-posts"><?php _e( 'How many posts to extract', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="text" value="" name="last-posts-list-nr-of-posts" id="last-posts-list-nr-of-posts" size="4"/>
                                    <div class="ts-option-description">
                                        <?php _e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php _e( 'Content split', 'touchsize' ) ?>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#last-posts-list-image-split" id="last-posts-list-image-split-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="1-3" src="<?php echo get_template_directory_uri().'/images/options/list_view_13.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="1-2" src="<?php echo get_template_directory_uri().'/images/options/list_view_12.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="3-4" src="<?php echo get_template_directory_uri().'/images/options/list_view_34.png'; ?>"></li>
                                    </ul>
                                    <select class="hidden" name="last-posts-list-image-split" id="last-posts-list-image-split">
                                        <option value="1-3">1/3</option>
                                        <option value="1-2">1/2</option>
                                        <option value="3-4">3/4</option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Choose your title/content split proportions. You can have them either 1/3, 1/2, 3/4 for your title and 2/3,1/2, 1/4 accordingly. Depending on the text and titles you use, select your split type.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-list-order-by"><?php _e( 'Order by', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="last-posts-list-order-by" id="last-posts-list-order-by">
                                        <option value="date"><?php _e( 'Date', 'touchsize' ) ?></option>
                                        <option value="comments"><?php _e( 'Comments', 'touchsize' ) ?></option>
                                        <option value="views"><?php _e( 'Views', 'touchsize' ) ?></option>
                                        <option value="likes"><?php _e( 'Likes', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-list-order-direction"><?php _e( 'Order direction', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="last-posts-list-order-direction" id="last-posts-list-order-direction">
                                        <option value="asc"><?php _e( 'ASC', 'touchsize' ) ?></option>
                                        <option value="desc" selected="selected"><?php _e( 'DESC', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-list-special-effects"><?php _e( 'Special effects:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <select name="last-posts-list-special-effects" id="last-posts-list-special-effects">
                                        <option value="none"><?php _e('No effect', 'touchsize') ?></option>
                                        <option value="opacited"><?php _e('Fade in', 'touchsize') ?></option>
                                        <option value="rotate-in"><?php _e('Rotate in', 'touchsize') ?></option>
                                        <option value="3dflip"><?php _e('3d flip', 'touchsize') ?></option>
                                        <option value="scaler"><?php _e('Scale', 'touchsize') ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-list-pagination"><?php _e( 'Enable pagination', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="last-posts-list-pagination" id="last-posts-list-pagination">
                                        <option selected="selected" value="n"><?php _e('None', 'touchsize') ?></option>
                                        <option value="y"><?php _e('Enable pagination', 'touchsize') ?></option>
                                        <option value="load-more"><?php _e('Load more', 'touchsize') ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('You can add pagination.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Thumbnail options -->
                    <div class="last-posts-thumbnails hidden">
                        <table cellpadding="10">
                            <tr>
                                <td>
                                    <label><?php _e( 'Behavior', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#last-posts-thumbnail-behavior" id="last-posts-thumbnail-behavior-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="none" src="<?php echo get_template_directory_uri().'/images/options/behavior_none.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="carousel" src="<?php echo get_template_directory_uri().'/images/options/behavior_carousel.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="masonry" src="<?php echo get_template_directory_uri().'/images/options/behavior_masonry.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="scroll" src="<?php echo get_template_directory_uri().'/images/options/behavior_scroll.png'; ?>"></li>
                                    </ul>
                                    <select name="last-posts-thumbnail-behavior" id="last-posts-thumbnail-behavior">
                                        <option value="none"><?php _e( 'Normal', 'touchsize' ) ?></option>
                                        <option value="carousel"><?php _e( 'Carousel', 'touchsize' ) ?></option>
                                        <option value="masonry"><?php _e( 'Masonry', 'touchsize' ) ?></option>
                                        <option value="scroll"><?php _e( 'Scroll', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Choose your posts behavior - you can have them just shown, or you can activate the carousel. If carousel is selected your articles will show up in a line with arrows for navigation. If masonry bahevior is selected - your articles will be arranged in to fit it. Be aware that activating the masonry option the crop settings for image sizes tab will be overwritten.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-thumbnail-posts-per-row"><?php _e( 'Number of posts per row', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#last-posts-thumbnail-posts-per-row" id="last-posts-thumbnail-posts-per-row-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="1" src="<?php echo get_template_directory_uri().'/images/options/per_row_1.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="2" src="<?php echo get_template_directory_uri().'/images/options/per_row_2.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="3" src="<?php echo get_template_directory_uri().'/images/options/per_row_3.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="4" src="<?php echo get_template_directory_uri().'/images/options/per_row_4.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="6" src="<?php echo get_template_directory_uri().'/images/options/per_row_6.png'; ?>"></li>
                                    </ul>
                                    <select class="hidden" name="last-posts-thumbnail-posts-per-row" id="last-posts-thumbnail-posts-per-row">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3" selected="selected">3</option>
                                        <option value="4">4</option>
                                        <option value="6">6</option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Your articles will be shown columns. Choose how mane columns do you want to use per line. Note that for mobile devices you will get only ONE element per row for better usability.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr class="last-posts-thumbnails-nr-of-posts">
                                <td>
                                    <label for="last-posts-thumbnail-limit"><?php _e( 'How many posts to extract', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="text" value="" name="last-posts-thumbnail-limit"  id="last-posts-thumbnail-limit" size="4"/>
                                    <div class="ts-option-description">
                                        <?php _e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                             <tr>
                                <td>
                                    <label for="last-posts-thumbnail-show-meta"><?php _e( 'Show meta:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <input type="radio" name="last-posts-thumbnail-show-meta" id="last-posts-thumbnail-show-meta-y" value="y"  checked = "checked"  /> 
                                    <label for="last-posts-thumbnail-show-meta-y"><?php _e( 'Yes', 'touchsize' ) ?></label>
                                    <input type="radio" name="last-posts-thumbnail-show-meta" id="last-posts-thumbnail-show-meta-n" value="n" />
                                    <label for="last-posts-thumbnail-show-meta-n"><?php _e( 'No', 'touchsize' ) ?></label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-thumbnail-order-by"><?php _e( 'Order by', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="last-posts-thumbnail-order-by" id="last-posts-thumbnail-order-by">
                                        <option value="date"><?php _e( 'Date', 'touchsize' ) ?></option>
                                        <option value="comments"><?php _e( 'Comments', 'touchsize' ) ?></option>
                                        <option value="views"><?php _e( 'Views', 'touchsize' ) ?></option>
                                        <option value="likes"><?php _e( 'Likes', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-thumbnail-order-direction"><?php _e( 'Order direction', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="last-posts-thumbnail-order-direction" id="last-posts-thumbnails-order-direction">
                                        <option value="asc"><?php _e( 'ASC', 'touchsize' ) ?></option>
                                        <option value="desc" selected="selected"><?php _e( 'DESC', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-thumbnail-special-effects"><?php _e( 'Special effects:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <select name="last-posts-thumbnail-special-effects" id="last-posts-thumbnail-special-effects">
                                        <option value="none"><?php _e('No effect', 'touchsize') ?></option>
                                        <option value="opacited"><?php _e('Fade in', 'touchsize') ?></option>
                                        <option value="rotate-in"><?php _e('Rotate in', 'touchsize') ?></option>
                                        <option value="3dflip"><?php _e('3d flip', 'touchsize') ?></option>
                                        <option value="scaler"><?php _e('Scale' , 'touchsize') ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-thumbnail-gutter"><?php _e( 'Remove gutter(space) between articles:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <select name="last-posts-thumbnail-gutter" id="last-posts-thumbnail-gutter">
                                        <option value="n"><?php _e('No', 'touchsize') ?></option>
                                        <option value="y"><?php _e('Yes', 'touchsize') ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Gutter is the space between your articles. You can remove the space and have your articles sticked one to another.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr class="last-posts-thumbnails-pagination">
                                <td>
                                    <label for="last-posts-thumbnails-pagination"><?php _e( 'Enable pagination', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="last-posts-thumbnails-pagination" id="last-posts-thumbnails-pagination">
                                        <option selected="selected" value="n"><?php _e('None', 'touchsize') ?></option>
                                        <option value="y"><?php _e('Enable pagination', 'touchsize') ?></option>
                                        <option value="load-more"><?php _e('Load more', 'touchsize') ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('You can add pagination.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="last-posts-big-post hidden">
                        <table cellpadding="10">
                            <tr>
                                <td>
                                    <label for="last-posts-big-post-title"><?php _e( 'Title position:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#last-posts-big-post-title" id="last-posts-big-post-title-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="title-above-image" src="<?php echo get_template_directory_uri().'/images/options/grid_view_title_image.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="title-above-excerpt" src="<?php echo get_template_directory_uri().'/images/options/grid_view_title_excerpt.png'; ?>"></li>
                                    </ul>
                                    <select name="last-posts-big-post-title" id="last-posts-big-post-title">
                                        <option value="title-above-image"><?php _e( 'Above image', 'touchsize' ) ?></option>
                                        <option value="title-above-excerpt"><?php _e( 'Above excerpt', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Select your title position for you grid posts. You can either have it above the image of above the excerpt. Note that sometimes title may change the position of the meta (date, categories, author) as well.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-big-post-image-position"><?php _e( 'Image position:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <select name="last-posts-big-post-image-position" id="last-posts-big-post-image-position">
                                        <option value="left"><?php _e( 'Left', 'touchsize' ) ?></option>
                                        <option value="right"><?php _e( 'Right', 'touchsize' ) ?></option>
                                        <option value="mosaic"><?php _e( 'Mosaic', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('The way you want the big posts to be shown', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-big-post-show-meta"><?php _e( 'Show meta:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <input type="radio" name="last-posts-big-post-show-meta" id="last-posts-big-post-show-meta-y" value="y"  checked = "checked"  /> 
                                    <label for="last-posts-big-post-show-meta-y"><?php _e( 'Yes', 'touchsize' ) ?></label>
                                    <input type="radio" name="last-posts-big-post-show-meta" id="last-posts-big-post-show-meta-n" value="n" />
                                    <label for="last-posts-big-post-show-meta-n"><?php _e( 'No', 'touchsize' ) ?></label>
                                    <div class="ts-option-description">
                                        <?php _e('You can choose to show or to hide your meta details for your articles. Meta values include date, author, categories and other article details.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr class="last-posts-big-post-nr-of-posts">
                                <td>
                                    <label for="last-posts-big-post-nr-of-posts"><?php _e( 'How many posts to extract:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <input type="text" value="" name="last-posts-big-post-nr-of-posts" id="last-posts-big-post-nr-of-posts" size="4"/>
                                    <div class="ts-option-description">
                                        <?php _e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php _e( 'Content split', 'touchsize' ) ?>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#last-posts-big-post-image-split" id="last-posts-big-post-image-split-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="1-3" src="<?php echo get_template_directory_uri().'/images/options/big_posts_13.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="1-2" src="<?php echo get_template_directory_uri().'/images/options/big_posts_12.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="3-4" src="<?php echo get_template_directory_uri().'/images/options/big_posts_34.png'; ?>"></li>
                                    </ul>
                                    <select class="hidden" name="last-posts-big-post-image-split" id="last-posts-big-post-image-split">
                                        <option value="1-3">1/3</option>
                                        <option value="1-2">1/2</option>
                                        <option value="3-4">3/4</option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Choose your image/content split proportions. You can have them either 1/3, 1/2, 3/4 for your title and 2/3,1/2, 1/4 accordingly. Depending on the text and titles you use, select your split type.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-big-post-order-by"><?php _e( 'Order by', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="last-posts-big-post-order-by" id="last-posts-big-post-order-by">
                                        <option value="date"><?php _e( 'Date', 'touchsize' ) ?></option>
                                        <option value="comments"><?php _e( 'Comments', 'touchsize' ) ?></option>
                                        <option value="views"><?php _e( 'Views', 'touchsize' ) ?></option>
                                        <option value="likes"><?php _e( 'Likes', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-big-post-order-direction"><?php _e( 'Order direction', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="last-posts-big-post-order-direction" id="last-posts-big-post-order-direction">
                                        <option value="asc"><?php _e( 'ASC', 'touchsize' ) ?></option>
                                        <option selected="selected" value="desc"><?php _e( 'DESC', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-big-post-related"><?php _e( 'Show related posts', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="last-posts-big-post-related" id="last-posts-big-post-related">
                                        <option value="y"><?php _e( 'Yes', 'touchsize' ); ?></option>
                                        <option selected="selected" value="n"><?php _e( 'No', 'touchsize' ); ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('You can set each of your big posts to show related articles below. A list with other articles will appear below.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-big-post-special-effects"><?php _e( 'Special effects', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="last-posts-big-post-special-effects" id="last-posts-big-post-special-effects">
                                        <option value="none"><?php _e('No effect', 'touchsize') ?></option>
                                        <option value="opacited"><?php _e('Fade in', 'touchsize') ?></option>
                                        <option value="rotate-in"><?php _e('Rotate in', 'touchsize') ?></option>
                                        <option value="3dflip"><?php _e('3d flip', 'touchsize') ?></option>
                                        <option value="scaler"><?php _e('Scale', 'touchsize') ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-big-post-pagination"><?php _e( 'Enable pagination', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="last-posts-big-post-pagination" id="last-posts-big-post-pagination">
                                        <option selected="selected" value="n"><?php _e('None', 'touchsize') ?></option>
                                        <option value="y"><?php _e('Enable pagination', 'touchsize') ?></option>
                                        <option value="load-more"><?php _e('Load more', 'touchsize') ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('You can add pagination.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="last-posts-super-post hidden">
                        <table cellpadding="10">
                            <tr>
                                <td>
                                    <label for="last-posts-super-post-posts-per-row"><?php _e( 'Number of posts per row', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#last-posts-super-post-posts-per-row" id="last-posts-super-post-posts-per-row-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="1" src="<?php echo get_template_directory_uri().'/images/options/per_row_1.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="2" src="<?php echo get_template_directory_uri().'/images/options/per_row_2.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="3" src="<?php echo get_template_directory_uri().'/images/options/per_row_3.png'; ?>"></li>
                                    </ul>
                                    <select class="hidden" name="last-posts-super-post-posts-per-row" id="last-posts-super-post-posts-per-row">
                                        <option value="1" selected="selected">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Your articles will be shown columns. Choose how mane columns do you want to use per line. Note that for mobile devices you will get only ONE element per row for better usability.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr class="last-posts-big-post-nr-of-posts">
                                <td>
                                    <label for="last-posts-super-post-limit"><?php _e( 'How many posts to extract', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="text" value="" name="last-posts-super-post-limit"  id="last-posts-super-post-limit" size="4"/>
                                    <div class="ts-option-description">
                                        <?php _e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-super-post-order-by"><?php _e( 'Order by', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="last-posts-super-post-order-by" id="last-posts-super-post-order-by">
                                        <option value="date"><?php _e( 'Date', 'touchsize' ) ?></option>
                                        <option value="comments"><?php _e( 'Comments', 'touchsize' ) ?></option>
                                        <option value="views"><?php _e( 'Views', 'touchsize' ) ?></option>
                                        <option value="likes"><?php _e( 'Likes', 'touchsize' ) ?></option>
                                    </select>
                                     <div class="ts-option-description">
                                        <?php _e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                     <label for="last-posts-super-post-order-direction"><?php _e( 'Order direction', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="last-posts-super-post-order-direction" id="last-posts-super-post-order-direction">
                                        <option value="asc"><?php _e( 'ASC', 'touchsize' ) ?></option>
                                        <option selected="selected" value="desc"><?php _e( 'DESC', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-super-post-special-effects"><?php _e( 'Special effects:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <select name="last-posts-super-post-special-effects" id="last-posts-super-post-special-effects">
                                        <option value="none"><?php _e('No effect', 'touchsize') ?></option>
                                        <option value="opacited"><?php _e('Fade in', 'touchsize') ?></option>
                                        <option value="rotate-in"><?php _e('Rotate in', 'touchsize') ?></option>
                                        <option value="3dflip"><?php _e('3d flip', 'touchsize') ?></option>
                                        <option value="scaler"><?php _e('Scale', 'touchsize') ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-super-post-pagination"><?php _e( 'Enable pagination', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="last-posts-super-post-pagination" id="last-posts-super-post-pagination">
                                        <option selected="selected" value="n"><?php _e('None', 'touchsize') ?></option>
                                        <option value="y"><?php _e('Enable pagination', 'touchsize') ?></option>
                                        <option value="load-more"><?php _e('Load more', 'touchsize') ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('You can add pagination.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!-- Timeline options -->
                    <div class="last-posts-timeline hidden">

                        <table cellpadding="10">
                            <tr>
                                <td>
                                    <label for="last-posts-timeline-show-meta"><?php _e( 'Show meta', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="radio" name="last-posts-timeline-show-meta" id="last-posts-timeline-show-meta-y" value="y"  checked = "checked"  /> 
                                    <label for="last-posts-timeline-show-meta-y"><?php _e( 'Yes', 'touchsize' ) ?></label>
                                    <input type="radio" name="last-posts-timeline-show-meta" id="last-posts-timeline-show-meta-n" value="n" />
                                    <label for="last-posts-timeline-show-meta-n"><?php _e( 'No', 'touchsize' ) ?></label>
                                    <div class="ts-option-description">
                                        <?php _e('You can choose to show or to hide your meta details for your articles. Meta values include date, author, categories and other article details.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-timeline-image"><?php _e( 'Show image', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="last-posts-timeline-image" id="last-posts-timeline-image">
                                        <option value="y"><?php _e( 'Yes', 'touchsize' ) ?></option>
                                        <option value="n"><?php _e( 'No', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Display image', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr class="last-posts-timeline-nr-of-posts">
                                <td>
                                    <label for="last-posts-timeline-post-limit"><?php _e( 'How many posts to extract', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="text" value="" name="last-posts-timeline-post-limit" id="last-posts-timeline-post-limit" size="4"/>
                                    <div class="ts-option-description">
                                        <?php _e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-timeline-order-by"><?php _e( 'Order by', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="last-posts-timeline-order-by" id="last-posts-timeline-order-by">
                                        <option value="date"><?php _e( 'Date', 'touchsize' ) ?></option>
                                        <option value="comments"><?php _e( 'Comments', 'touchsize' ) ?></option>
                                        <option value="views"><?php _e( 'Views', 'touchsize' ) ?></option>
                                        <option value="likes"><?php _e( 'Likes', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-timeline-order-direction"><?php _e( 'Order direction', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="last-posts-timeline-order-direction" id="last-posts-timeline-order-direction">
                                        <option value="asc"><?php _e( 'ASC', 'touchsize' ) ?></option>
                                        <option value="desc" selected="selected"><?php _e( 'DESC', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-timeline-pagination"><?php _e( 'Enable pagination', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="last-posts-timeline-pagination" id="last-posts-timeline-pagination">
                                        <option selected="selected" value="n"><?php _e('None', 'touchsize') ?></option>
                                        <option value="y"><?php _e('Enable pagination', 'touchsize') ?></option>
                                        <option value="load-more"><?php _e('Load more', 'touchsize') ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('You can add pagination.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!-- mosaic options -->
                    <div class="last-posts-mosaic hidden">

                        <table cellpadding="10">
                            <tr class="last-posts-mosaic-layout">
                                <td>
                                    <label for="last-posts-mosaic-layout"><?php _e( 'Choose how to show the posts', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="last-posts-mosaic-layout" id="last-posts-mosaic-layout" class="ts-mosaic-layout">
                                        <option value="rectangles"><?php _e( 'Rectangles', 'touchsize' ) ?></option>
                                        <option value="square"><?php _e( 'Squares', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Choose how to show the posts', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr class="last-posts-mosaic-rows">
                                <td>
                                    <label for="last-posts-mosaic-rows"><?php _e( 'Change number of rows', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="last-posts-mosaic-rows" id="last-posts-mosaic-rows" class="ts-mosaic-rows">
                                        <option value="2"><?php _e( '2', 'touchsize' ) ?></option>
                                        <option value="3"><?php _e( '3', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Change number of rows', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr class="last-posts-mosaic-nr-of-posts">
                                <td>
                                    <label for="last-posts-mosaic-post-limit"><?php _e( 'How many posts to extract', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <div class="ts-mosaic-post-limit-rows-2">
                                        <input class="ts-input-slider" type="text" name="last-posts-mosaic-post-limit-rows-2" id="last-posts-mosaic-post-limit-rows-2" value="6" disabled />
                                        <div id="last-posts-mosaic-post-limit-rows-2-slider"></div>
                                    </div>
                                    <div class="ts-mosaic-post-limit-rows-3">
                                        <input type="text" name="last-posts-mosaic-post-limit-rows-3" id="last-posts-mosaic-post-limit-rows-3" value="" disabled />
                                        <div id="last-posts-mosaic-post-limit-rows-3-slider"></div>
                                    </div>
                                    <div class="ts-mosaic-post-limit-squares">
                                        <input type="text" name="last-posts-mosaic-post-limit-rows-squares" id="last-posts-mosaic-post-limit-rows-squares" value="" disabled />
                                        <div id="last-posts-mosaic-post-limit-rows-squares-slider"></div>
                                    </div>

                                    <div class="ts-option-description">
                                        <?php _e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-mosaic-scroll"><?php _e( 'Add/remove scroll', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="last-posts-mosaic-scroll" id="last-posts-mosaic-scroll">
                                        <option value="y"><?php _e( 'With scroll', 'touchsize' ) ?></option>
                                        <option value="n"><?php _e( 'Without scroll', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Add/remove scroll', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-mosaic-effects"><?php _e( 'Add effects to scroll', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="last-posts-mosaic-effects" id="last-posts-mosaic-effects">
                                        <option value="default"><?php _e( 'Default', 'touchsize' ) ?></option>
                                        <option value="fade"><?php _e( 'Fade in effect', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Change number of rows', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-mosaic-gutter"><?php _e( 'Add or Remove gutter between posts', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="last-posts-mosaic-gutter" id="last-posts-mosaic-gutter">
                                        <option value="y"><?php _e( 'With gutter between posts', 'touchsize' ) ?></option>
                                        <option value="n"><?php _e( 'No gutter', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Add or Remove gutter between posts', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-mosaic-order-by"><?php _e( 'Order by', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="last-posts-mosaic-order-by" id="last-posts-mosaic-order-by">
                                        <option value="date"><?php _e( 'Date', 'touchsize' ) ?></option>
                                        <option value="comments"><?php _e( 'Comments', 'touchsize' ) ?></option>
                                        <option value="views"><?php _e( 'Views', 'touchsize' ) ?></option>
                                        <option value="likes"><?php _e( 'Likes', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="last-posts-mosaic-direction"><?php _e( 'Order direction', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="last-posts-mosaic-order-direction" id="last-posts-mosaic-order-direction">
                                        <option value="asc"><?php _e( 'ASC', 'touchsize' ) ?></option>
                                        <option value="desc" selected="selected"><?php _e( 'DESC', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                
                </div>
            </div>
      
            <div class="callaction builder-element hidden">
                <h3 class="element-title"><?php _e('Call to action element','touchsize'); ?></h3>
                <table cellpadding="10">
                    <tr>
                        <td>
                            <label for="callaction-admin-label"><?php _e('Admin label','touchsize');?>:</label>
                        </td>
                        <td>
                           <input type="text" id="callaction-admin-label" name="callaction-admin-label" />
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <label for="callaction-text"><?php _e( 'Call to action Text', 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <textarea name="callaction-text" id="callaction-text" style="width:400px; height: 100px" ></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="callaction-link"><?php _e( 'Button Link', 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <input type="text" name="callaction-link" id="callaction-link" style="width:400px" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="callaction-button-text"><?php _e( 'Button Text', 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <input type="text" name="callaction-button-text" id="callaction-button-text" style="width:400px" />
                        </td>
                    </tr>
                </table>
            </div>

            <div class="teams builder-element hidden">
                <h3 class="element-title"><?php _e('Team members element', 'touchsize'); ?></h3>
                <table cellpadding="10">
                            <tr>
                        <td>
                            <label for="teams-admin-label"><?php _e('Admin label:','touchsize'); ?></label>
                        </td>
                        <td>
                           <input type="text" id="teams-admin-label" name="teams-admin-label" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="teams-category"><?php _e( 'Category', 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <?php $categories = get_categories(array( 'hide_empty' => 1, 'taxonomy' => 'teams', 'pad_counts' => true ));
                            if( isset($categories) && is_array($categories) && !empty($categories) ) : ?>
                                <select class="ts-custom-select-input" data-placeholder="<?php _e('Select your category', 'touchsize'); ?>" name="teams-category" id="teams-category" multiple>
                                    <option value="0"><?php _e('All', 'touchsize'); ?></option>
                                    <?php foreach ($categories as $index => $category): ?>
                                        <option value="<?php echo $category->term_id ?>"><?php echo $category->cat_name ?><span>  (<?php echo $category->count; ?>)</span></option>
                                    <?php endforeach ?>                                    
                                </select>
                            <?php endif ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="teams-elements-per-row"><?php _e( 'Number of elements per row', 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#teams-elements-per-row" id="teams-elements-per-row-selector">
                               <li><img class="image-radio-input clickable-element" data-option="1" src="<?php echo get_template_directory_uri().'/images/options/per_row_1.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="2" src="<?php echo get_template_directory_uri().'/images/options/per_row_2.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="3" src="<?php echo get_template_directory_uri().'/images/options/per_row_3.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="4" src="<?php echo get_template_directory_uri().'/images/options/per_row_4.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="6" src="<?php echo get_template_directory_uri().'/images/options/per_row_6.png'; ?>"></li>
                            </ul>
                            <select class="hidden" name="teams-elements-per-row" id="teams-elements-per-row">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="6">6</option>
                            </select>   
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="teams-post-limit"><?php _e( 'How many posts to extract', 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <input type="text" value="" name="teams-post-limit"  id="teams-post-limit" size="4"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="teams-remove-gutter"><?php _e( 'Remove gutter (space between articles)', 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <select name="teams-remove-gutter" id="teams-remove-gutter">
                                <option value="no"><?php _e('No', 'touchsize'); ?></option>
                                <option value="yes"><?php _e('Yes', 'touchsize'); ?></option>
                            </select>   
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="teams-enable-carousel"><?php _e( 'Enable carousel', 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <select name="teams-enable-carousel" id="teams-enable-carousel">
                                <option value="no"><?php _e('No', 'touchsize'); ?></option>
                                <option value="yes"><?php _e('Yes', 'touchsize'); ?></option>
                            </select>   
                        </td>
                    </tr>
                </table>
            </div>
            <div class="pricing-tables builder-element hidden">
                <h3 class="element-title"><?php _e('Pricing tables element', 'touchsize'); ?></h3>
                <table cellpadding="10">
                    <tr>
                        <td>
                            <label for="pricing-tables-admin-label"><?php _e('Admin label:', 'touchsize'); ?></label>
                        </td>
                        <td>
                           <input type="text" id="pricing-tables-admin-label" name="pricing-tables-admin-label" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e( 'Category:', 'touchsize' ); ?>
                        </td>
                        <td>
                            <?php $categories = get_categories(array( 'hide_empty' => 0, 'taxonomy' => 'ts_pricing_table_categories', 'pad_counts' => true ));
                            if( isset($categories) && !is_wp_error($categories) && !empty($categories) ) : ?>
                                <select class="ts-custom-select-input" data-placeholder="<?php _e('Select your category', 'touchsize'); ?>" name="pricing-tables-category" id="pricing-tables-category" multiple>   
                                    <option value="0"><?php _e('All', 'touchsize') ?></option>
                                    <?php foreach ($categories as $index => $category): ?>
                                        <option value="<?php echo $category->term_id ?>"><?php echo $category->cat_name ?></option>
                                    <?php endforeach ?>                                    
                                </select>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="pricing-tables-elements-per-row"><?php _e( 'Number of elements per row', 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#pricing-tables-elements-per-row" id="teams-elements-per-row-selector">
                               <li><img class="image-radio-input clickable-element" data-option="1" src="<?php echo get_template_directory_uri().'/images/options/per_row_1.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="2" src="<?php echo get_template_directory_uri().'/images/options/per_row_2.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="3" src="<?php echo get_template_directory_uri().'/images/options/per_row_3.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="4" src="<?php echo get_template_directory_uri().'/images/options/per_row_4.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="6" src="<?php echo get_template_directory_uri().'/images/options/per_row_6.png'; ?>"></li>
                            </ul>
                            <select class="hidden" name="pricing-tables-elements-per-row" id="pricing-tables-elements-per-row">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="6">6</option>
                            </select>   
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="pricing-tables-post-limit"><?php _e( 'How many posts to extract', 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <input type="text" value="" name="pricing-tables-post-limit"  id="pricing-tables-post-limit" size="4"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="pricing-tables-remove-gutter"><?php _e( 'Remove gutter (space between articles)', 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <select name="pricing-tables-remove-gutter" id="pricing-tables-remove-gutter">
                                <option value="no">No</option>
                                <option value="yes">Yes</option>
                            </select>   
                        </td>
                    </tr>
                </table>
            </div>

            <div class="advertising builder-element hidden">
                <h3 class="element-title">Advertising element</h3>
                <!-- Advertising -->
                <table cellpadding="10">
                    <tr>
                        <td>
                            <label for="advertising-admin-label"><?php _e('Admin label','touchsize');?>:</label>
                        </td>
                        <td>
                           <input type="text" id="advertising-admin-label" name="advertising-admin-label" />
                        </td>
                    </tr>
                </table>
                <p><?php _e( 'Insert here your code', 'touchsize' ) ?>:</p>
                <textarea name="advertising" id="advertising" cols="60" rows="10"></textarea>
            </div>

            <div class="none builder-element hidden empty">
                <h3 class="element-title">Empty element</h3>
                <!-- None -->
                <p><?php _e( 'This is an empty element.', 'touchsize' ) ?></p>
            </div>

            <div class="delimiter builder-element hidden">
                <h3 class="element-title">Delimiter element</h3>
                <!-- Delimiter -->
                <table cellpadding="10">
                    <tr>
                        <td>
                            <label for="delimiter-admin-label"><?php _e('Admin label','touchsize');?>:</label>
                        </td>
                        <td>
                           <input type="text" id="delimiter-admin-label" name="delimiter-admin-label" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="delimiter-type"><?php _e( 'Delimiter type', 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <select name="delimiter-type" id="delimiter-type">
                                <option value="dotsslash"><?php _e( 'Dotted', 'touchsize' ) ?></option>
                                <option value="doubleline"><?php _e( 'Double line', 'touchsize' ) ?></option>
                                <option value="lines"><?php _e( 'Lines', 'touchsize' ) ?></option>
                                <option value="squares"><?php _e( 'Squares', 'touchsize' ) ?></option>
                                <option value="line"><?php _e( 'Line', 'touchsize' ) ?></option>
                                <option value="gradient"><?php _e( 'Gradient', 'touchsize' ) ?></option>
                                <option value="iconed icon-close"><?php _e( 'Line with cross', 'touchsize' ) ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e('Delimiter color', 'touchsize') ?>:
                        </td>
                        <td>
                           <input type="text" id="delimiter-color" class="colors-section-picker" name="delimiter-color" value="#FFFFFF"/>
                           <div class="colors-section-picker-div" id="delimiter-color-picker"></div>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Title element -->
            <div class="title builder-element hidden">
                <h3 class="element-title">Title element</h3>
                <table cellpadding="10">
                    <tr>
                        <td>
                            <label for="title-admin-label"><?php _e('Admin label','touchsize');?>:</label>
                        </td>
                        <td>
                           <input type="text" id="title-admin-label" name="title-admin-label" />
                        </td>
                    </tr>
                </table>
                <p><?php _e("Add icon for title from the library below:", 'touchsize'); ?></p>
                <div class="builder-element-icon-toggle">
                    <a href="#" class="red-ui-button builder-element-icon-trigger-btn" data-toggle="#builder-element-title-icon-selector"><?php _e('Show icons','touchsize') ?></a>
                </div> 
                <ul id="builder-element-title-icon-selector" data-selector="#builder-element-title-icon" class="builder-icon-list ts-custom-selector">
                    <?php echo $icons_li; ?>
                </ul>
                <select name="builder-element-title-icon" id="builder-element-title-icon" class="hidden">
                    <?php echo $icons_options; ?>
                </select>
                <table cellpadding="10">
                    <tr>
                        <td><label for="title-title"><?php _e( 'Title', 'touchsize' ) ?>:</label></td>
                        <td><input type="text" value="" name="title-title"  id="title-title" style="width:400px" /></td>
                    </tr>
                    <tr>
                        <td><label for="title-color"><?php _e( 'Title color', 'touchsize' ) ?>:</label></td>
                        <td>
                            <input type="text" id="builder-element-title-color" class="colors-section-picker" value="<?php echo ts_get_color('general_text_color'); ?>" name="builder-element-title-color" />
                            <div class="colors-section-picker-div" id="builder-element-title-color"></div>
                        </td>
                    </tr> 
                    <tr>
                        <td><label for="title-subtitle"><?php _e( 'Subtitle', 'touchsize' ) ?>:</label></td>
                        <td><input type="text" value="" name="title-subtitle"  id="title-subtitle" style="width:400px" /></td>
                    </tr>
                    <tr>
                        <td><label for="title-subtitle-color"><?php _e( 'Subtitle color', 'touchsize' ) ?>:</label></td>
                        <td>
                            <input type="text" id="builder-element-title-subtitle-color" class="colors-section-picker" value="<?php echo ts_get_color('general_text_color'); ?>" name="builder-element-title-subtitle-color" />
                            <div class="colors-section-picker-div" id="builder-element-title-subtitle-color"></div>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="title-size"><?php _e( 'Size', 'touchsize' ) ?>:</label></td>
                        <td>
                            <select name="title-size" id="title-size">
                                <option value="h1"><?php _e( 'H1', 'touchsize' ) ?></option>
                                <option value="h2"><?php _e( 'H2', 'touchsize' ) ?></option>
                                <option value="h3"><?php _e( 'H3', 'touchsize' ) ?></option>
                                <option value="h4"><?php _e( 'H4', 'touchsize' ) ?></option>
                                <option value="h5"><?php _e( 'H5', 'touchsize' ) ?></option>
                                <option value="h6"><?php _e( 'H6', 'touchsize' ) ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="title-style"><?php _e( 'Style', 'touchsize' ) ?>:</label></td>
                        <td>
                            <select name="title-style" id="title-style">
                                <option value="simpleleft"><?php _e( 'Title aligned left', 'touchsize' ) ?></option>
                                <option value="lineafter"><?php _e( 'Title aligned left with circle and line after', 'touchsize' ) ?></option>
                                <option value="leftrect"><?php _e( 'Title aligned left with rectangular left', 'touchsize' ) ?></option>
                                <option value="simplecenter"><?php _e( 'Title aligned center', 'touchsize' ) ?></option>
                                <option value="linerect"><?php _e( 'Title aligned center with line and rectangular below', 'touchsize' ) ?></option>
                                <option value="2lines"><?php _e( 'Title aligned center with 2 lines before and after', 'touchsize' ) ?></option>
                                <option value="lineariconcenter"><?php _e( 'Title aligned center with linear icon after', 'touchsize' ) ?></option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Video element -->
            <div class="video builder-element hidden">
                <h3 class="element-title"><?php _e('Video element', 'touchsize'); ?></h3>
                <table cellpadding="10">
                    <tr>
                        <td>
                            <label for="video-admin-label"><?php _e('Admin label','touchsize'); ?>:</label>
                        </td>
                        <td>
                           <input type="text" id="video-admin-label" name="video-admin-label" />
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <textarea name="embed" id="video-embed" style="width:400px" rows="10"></textarea>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Image element -->
            <div class="image builder-element hidden">
                <h3 class="element-title"><?php _e('Image element', 'touchsize'); ?></h3>
                <table cellpadding="10">
                    <tr>
                        <td>
                            <label for="image-admin-label"><?php _e('Admin label:', 'touchsize'); ?></label>
                        </td>
                        <td>
                           <input type="text" id="image-admin-label" name="image-admin-label" />
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"><label for="image_url"><?php _e('Image URL:', 'touchsize'); ?></label></td>
                        <td>
                            <input type="text" value="" name="image_url"  id="image_url" class="image_url" style="width:300px" />
                            <input type="button" class="button-primary" id="select_image" value="Upload" />
                            <input type="hidden" value="" class="image_media_id" />
                            <div id="image_preview"></div>
                        </td>
                    </tr>
                     <tr>
                        <td>
                            <?php _e('Image align:', 'touchsize'); ?>
                        </td>
                        <td>
                            <select name="image-align" id="image-align">
                               <option value="left">Left</option>
                               <option value="center">Center</option>
                               <option value="right">Right</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e('Forward image to url:', 'touchsize'); ?>
                        </td>
                        <td>
                            <input type="text" name="forward-image-url" id="forward-image-url" style="width:250px" value=""/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e('Target:', 'touchsize'); ?>
                        </td>
                        <td>
                            <select name="image-target" id="image-target">
                                <option value="_blank"><?php _e('_blank', 'touchsize'); ?></option>
                                <option value="_self"><?php _e('_self', 'touchsize'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e('Use retina image:', 'touchsize'); ?>
                        </td>
                        <td>
                            <select name="image-retina" id="image-retina">
                                <option value="y"><?php _e('Yes', 'touchsize'); ?></option>
                                <option value="_self"><?php _e('No', 'touchsize'); ?></option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- List products element --> 

             <div class="list-products builder-element hidden">
                <h3 class="element-title">List products element</h3>
                <!-- Select category -->
                <table cellpadding="10">
                    <tr>
                        <td>
                            <label for="list-products-admin-label"><?php _e('Admin label','touchsize');?>:</label>
                        </td>
                        <td>
                           <input type="text" id="list-products-admin-label" name="list-products-admin-label" />
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <label for="list-products-category"><?php _e( 'Category', 'touchsize' ) ?>:</label>
                        </td>
                        <td>                            
                            <select data-placeholder="<?php _e('Select your category', 'touchsize'); ?>" class="ts-custom-select-input"  name="list-products-category" id="list-products-category" multiple>
                                <?php $categories = get_categories(array( 'hide_empty' => 0, 'show_option_all' => '','taxonomy'=>'product_cat' )); ?>
                                <?php if ($categories): ?>
                                    <option value="0">All</option>
                                    <?php foreach ($categories as $index => $category): ?>
                                        <option value="<?php echo $category->term_id ?>"><?php echo $category->cat_name ?></option>
                                    <?php endforeach ?>                                    
                                <?php endif ?>
                            </select>
                            <div class="ts-option-description">
                                <?php _e('Choose the categories that you want to showcase products from.', 'touchsize'); ?>
                            </div>
                        </td>
                    </tr>
                </table>

                <div id="list-products-options">
                    <div class="list-products">
                        <table cellpadding="10">
                            <tr>
                                <td>
                                    <label><?php _e( 'Behavior', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-products-behavior" id="list-products-behavior-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="none" src="<?php echo get_template_directory_uri().'/images/options/behavior_none.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="carousel" src="<?php echo get_template_directory_uri().'/images/options/behavior_carousel.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="masonry" src="<?php echo get_template_directory_uri().'/images/options/behavior_masonry.png'; ?>"></li>
                                    </ul>
                                    <select name="list-products-behavior" id="list-products-behavior">
                                        <option value="none"><?php _e( 'Normal', 'touchsize' ) ?></option>
                                        <option value="carousel"><?php _e( 'Carousel', 'touchsize' ) ?></option>
                                        <option value="masonry"><?php _e( 'Masonry', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Choose your product articles behavior - you can have them just shown, or you can activate the carousel. If carousel is selected your articles will show up in a line with arrows for navigation. If masonry bahevior is selected - your articles will be arranged in to fit it. Be aware that activating the masonry option the crop settings for image sizes tab will be overwritten.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-products-el-per-row"><?php _e( 'Elements per row', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-products-el-per-row" id="list-products-el-per-row-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="1" src="<?php echo get_template_directory_uri().'/images/options/per_row_1.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="2" src="<?php echo get_template_directory_uri().'/images/options/per_row_2.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="3" src="<?php echo get_template_directory_uri().'/images/options/per_row_3.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="4" src="<?php echo get_template_directory_uri().'/images/options/per_row_4.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="6" src="<?php echo get_template_directory_uri().'/images/options/per_row_6.png'; ?>"></li>
                                    </ul>
                                    <select class="hidden" name="list-products-el-per-row" id="list-products-el-per-row">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3" selected="selected">3</option>
                                        <option value="4">4</option>
                                        <option value="6">6</option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Your products will be shown columns. Choose how mane columns do you want to use per line. Note that for mobile devices you will get only ONE element per row for better usability.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-products-nr-of-posts"><?php _e( 'How many products to extract', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="text" value="" name="list-products-nr-of-posts" id="list-products-nr-of-posts" size="4"/>
                                    <div class="ts-option-description">
                                        <?php _e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-products-order-by"><?php _e( 'Order by', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-products-order-by" id="list-products-order-by">
                                        <option value="date"><?php _e( 'Date', 'touchsize' ) ?></option>
                                        <option value="comments_count"><?php _e( 'Comments', 'touchsize' ) ?></option>
                                        <option value="views"><?php _e( 'Views', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-products-order-direction"><?php _e( 'Order direction', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-products-order-direction" id="list-products-order-direction">
                                        <option value="asc"><?php _e( 'ASC', 'touchsize' ) ?></option>
                                        <option value="desc" selected="selected"><?php _e( 'DESC', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-products-special-effects"><?php _e( 'Special effects', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-products-special-effects" id="list-products-special-effects">
                                        <option value="none"><?php _e('No effect', 'touchsize') ?></option>
                                        <option value="opacited"><?php _e('Fade in', 'touchsize') ?></option>
                                        <option value="rotate-in"><?php _e('Rotate in', 'touchsize') ?></option>
                                        <option value="3dflip"><?php _e('3d flip', 'touchsize') ?></option>
                                        <option value="scaler"><?php _e('Scale', 'touchsize') ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>        

            <!-- Filters element -->
            <div class="filters builder-element hidden">
                <h3 class="element-title"><?php _e('Filters element', 'touchsize'); ?></h3>
                <table cellpadding="10">
                    <tr>
                        <td>
                            <label for="filters-admin-label"><?php _e('Admin label','touchsize');?>:</label>
                        </td>
                        <td>
                           <input type="text" id="filters-admin-label" name="filters-admin-label" />
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"><label for="filters-post-type"><?php _e( 'Content type', 'touchsize' ) ?>:</label></td>
                        <td valign="top">
                            <select name="filters-post-type" id="filters-post-type">
                                <option value="posts"><?php _e( 'Posts', 'touchsize' ) ?></option>
                                <option value="portfolio"><?php _e( 'Portfolio', 'touchsize' ) ?></option>
                                <option value="video"><?php _e( 'Video', 'touchsize' ) ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"><label for="filters-posts-category"><?php _e( 'Categories', 'touchsize' ) ?>:</label></td>
                        <td valign="top" class="filters-categories">
                            <div id="wp-post-categories" class="hidden wp-post-categories">
                               <select data-placeholder="<?php _e('Select your category', 'touchsize'); ?>" class="ts-custom-select-input" name="filters-posts-category" id="filters-posts-category" multiple>
                                    <?php $categories = get_categories(array( 'hide_empty' => 1, 'show_option_all' => '' )); ?>
                                    <?php if ( isset($categories) && is_array($categories) && !empty($categories) ) : ?>
                                        <?php $s = 1; foreach ($categories as $index => $category): ?>
                                            <?php if( is_object($category) ) : ?>
                                                <?php if( $s === 1 ) echo '<option value="0">All</option>'; ?>
                                                <option value="<?php echo $category->term_id ?>"><?php echo $category->cat_name ?></option>
                                            <?php endif; $s++; ?>
                                        <?php endforeach ?>                                 
                                    <?php endif ?>
                                </select>
                            </div>
                            <div id="porfolio-categories" class="hidden porfolio-categories">
                                <select class="ts-custom-select-input" data-placeholder="<?php _e('Select your category', 'touchsize'); ?>" name="filters-portfolios-category" id="filters-portfolios-category" multiple>
                                    <?php $categories = get_categories(array( 'hide_empty' => 1, 'taxonomy' => 'portfolio_register_post_type' )); ?>
                                    <?php if ( isset($categories) && is_array($categories) && !empty($categories) ): ?>
                                        <?php $j = 1; foreach ($categories as $index => $category): ?>
                                            <?php if( is_object($category) ) : ?>
                                                <?php if( $j === 1 ) echo '<option value="0">All</option>'; ?>
                                                <option value="<?php echo $category->term_id ?>"><?php echo $category->cat_name ?></option>
                                            <?php endif; $j++; ?>
                                        <?php endforeach ?>                                    
                                    <?php endif ?>
                                </select>
                            </div> 
                            <div id="video-categories" class="hidden video-categories">
                                <select class="ts-custom-select-input" data-placeholder="<?php _e('Select your category', 'touchsize'); ?>" name="filters-video-category" id="filters-video-category" multiple>
                                    <?php $categories = get_categories(array( 'hide_empty' => 1, 'taxonomy' => 'videos_categories' )); ?>
                                    <?php if ( isset($categories) && is_array($categories) && !empty($categories) ): ?>
                                        <?php $j = 1; foreach ($categories as $index => $category): ?>
                                            <?php if( is_object($category) ) : ?>
                                                <?php if( $j === 1 ) echo '<option value="0">All</option>'; ?>
                                                <option value="<?php echo $category->term_id ?>"><?php echo $category->cat_name ?></option>
                                            <?php endif; $j++; ?>
                                        <?php endforeach ?>                                    
                                    <?php endif ?>
                                </select>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="filters-posts-limit"><?php _e( 'Nr. of posts from each category', 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <input type="text" value="" name="filters-posts-limit"  id="filters-posts-limit" size="4"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="filters-elements-per-row"><?php _e( 'Elements per row', 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#filters-elements-per-row" id="filters-elements-per-row-selector">
                               <li><img class="image-radio-input clickable-element" data-option="1" src="<?php echo get_template_directory_uri().'/images/options/per_row_1.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="2" src="<?php echo get_template_directory_uri().'/images/options/per_row_2.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="3" src="<?php echo get_template_directory_uri().'/images/options/per_row_3.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="4" src="<?php echo get_template_directory_uri().'/images/options/per_row_4.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="6" src="<?php echo get_template_directory_uri().'/images/options/per_row_6.png'; ?>"></li>
                            </ul>
                            <select class="hidden" name="filters-elements-per-row" id="filters-elements-per-row">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3" selected="selected">3</option>
                                <option value="4">4</option>
                                <option value="6">6</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="filters-order-by"><?php _e( 'Order by', 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <select name="filters-order-by" id="filters-order-by">
                                <option value="date"><?php _e( 'Date', 'touchsize' ) ?></option>
                                <option value="comments"><?php _e( 'Comments', 'touchsize' ) ?></option>
                                <option value="views"><?php _e( 'Views', 'touchsize' ) ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                             <label for="filters-order-direction"><?php _e( 'Order direction', 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <select name="filters-order-direction" id="filters-order-direction">
                                <option value="asc"><?php _e( 'ASC', 'touchsize' ) ?></option>
                                <option selected="selected" value="desc"><?php _e( 'DESC', 'touchsize' ) ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                             <label for="filters-special-effects"><?php _e( 'Special effect', 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <select name="filters-special-effects" id="filters-special-effects">
                                <option value="none"><?php _e('No effect', 'touchsize') ?></option>
                                <option value="opacited"><?php _e('Fade in', 'touchsize') ?></option>
                                <option value="rotate-in"><?php _e('Rotate in', 'touchsize') ?></option>
                                <option value="3dflip"><?php _e('3d flip', 'touchsize') ?></option>
                                <option value="scaler"><?php _e('Scale', 'touchsize') ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                             <label for="filters-gutter"><?php _e( 'Remove gutter(space) between articles:', 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <select name="filters-gutter" id="filters-gutter">
                                <option value="n"><?php _e('No', 'touchsize') ?></option>
                                <option value="y"><?php _e('Yes', 'touchsize') ?></option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="feature-blocks builder-element hidden">
                <h3 class="element-title">Feature blocks element</h3>
                <!-- Feature blocks -->
                <table cellpadding="10">
                    <tr>
                        <td>
                            <label for="feature-blocks-per-row"><?php _e( 'Number of elements per row', 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#feature-blocks-per-row" id="feature-blocks-per-row-selector">
                               <li><img class="image-radio-input clickable-element" data-option="1" src="<?php echo get_template_directory_uri().'/images/options/per_row_1.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="2" src="<?php echo get_template_directory_uri().'/images/options/per_row_2.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="3" src="<?php echo get_template_directory_uri().'/images/options/per_row_3.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="4" src="<?php echo get_template_directory_uri().'/images/options/per_row_4.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="6" src="<?php echo get_template_directory_uri().'/images/options/per_row_6.png'; ?>"></li>
                            </ul>
                            <select class="hidden" name="feature-blocks-per-row" id="feature-blocks-per-row">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="6">6</option>
                            </select>   
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="feature-blocks-style"><?php _e( 'Style', 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <select name="feature-blocks-style" id="feature-blocks-style">
                                <option value="style1"><?php _e('Feature blocks with background', 'touchsize') ?></option>
                                <option value="style2"><?php _e('Feature blocks with border', 'touchsize') ?></option>
                            </select>   
                        </td>
                    </tr>
                </table>
            </div>
                
            <div class="spacer builder-element hidden">
                <h3 class="element-title"><?php _e('Spacer element', 'touchsize'); ?></h3>
                <table cellpadding="10">           
                    <tr>
                        <td>
                            <label for="spacer-admin-label"><?php _e('Admin label','touchsize');?>:</label>
                        </td>
                        <td>
                           <input type="text" id="spacer-admin-label" name="spacer-admin-label" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e( 'Height', 'touchsize' ) ?>:
                        </td>
                        <td>
                            <input type="text" name="spacer-height" id="spacer-height" style="width:250px" value="20"/> px
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e( 'Hide on mobile', 'touchsize' ) ?>:
                        </td>
                        <td>
                            <select name="spacer-mobile" id="spacer-mobile">
                                <option value="y"><?php _e( 'Yes', 'touchsize' ); ?></option>
                                <option selected="selected" value="n"><?php _e( 'No', 'touchsize' ); ?></option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="facebook-block builder-element hidden">
                <h3 class="element-title"><?php _e('Facebook element', 'touchsize'); ?></h3>
                <table cellpadding="10">
                    <tr>
                        <td>
                            <?php _e( 'Link of your Facebook page (used in the URL).', 'touchsize' ) ?>
                        </td>
                        <td>
                            <input type="text" name="facebook-url" id="facebook-url" style="width:250px" value=''/>
                            <div class="ts-option-description">
                                <?php _e('ex: ( http://facebook.com/touchsize )', 'touchsize'); ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e( 'Color scheme', 'touchsize' ) ?>:
                        </td>
                        <td>
                            <select name="facebook-background" id="facebook-background">
                                <option value="dark"><?php _e( 'Dark', 'touchsize' ) ?></option>
                                <option value="light"><?php _e( 'Light', 'touchsize' ) ?></option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="counters builder-element hidden">
                <h3 class="element-title"><?php _e('Counters element', 'touchsize'); ?></h3>
                <table cellpadding="10">
                    <tr>
                        <td>
                            <label for="counters-admin-label"><?php _e('Admin label','touchsize');?>:</label>
                        </td>
                        <td>
                           <input type="text" id="counters-admin-label" name="counters-admin-label" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e( 'Text', 'touchsize' ) ?>:
                        </td>
                        <td>
                            <input type="text" name="counters-text" id="counters-text" style="width:250px" value=""/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e( 'Count percent', 'touchsize' ) ?>:
                        </td>
                        <td>
                            <input type="text" name="counters-precents" id="counters-precents" style="width:250px" value="100"/> %
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e('Text color', 'touchsize') ?>:
                        </td>
                        <td>
                            <input type="text" id="counters-text-color" class="colors-section-picker" value="#000" name="counters-text-color" />
                            <div class="colors-section-picker-div" id="counters-text-color"></div>
                        </td>
                    </tr>                    
                </table>
            </div>

            <div class="page builder-element hidden">
                <h3 class="element-title"><?php _e( 'Page element', 'touchsize' ) ?></h3>
                <table cellpadding="10">
                    <tr>
                        <td>
                            <?php _e( 'Search page', 'touchsize' ) ?>:
                        </td>
                        <td>
                            <input type="text" name="search-page" id="search-page" style="width:250px"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e( 'Criteria', 'touchsize' ) ?>:
                        </td>
                        <td>
                            <select name="search-page-criteria" id="search-page-criteria">
                                <option value="title"><?php _e( 'Title', 'touchsize' ) ?></option>
                                <option value="title-content"><?php _e( 'Title & Content', 'touchsize' ) ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e('Order by', 'touchsize') ?>:
                        </td>
                        <td>
                            <select name="search-page-order-by" id="search-page-order-by">
                                <option value="id"><?php _e( 'ID', 'touchsize' ) ?></option>
                                <option value="date"><?php _e( 'Date', 'touchsize' ) ?></option>
                                <option value="views"><?php _e( 'Views', 'touchsize' ) ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e('Direction', 'touchsize') ?>:
                        </td>
                        <td>
                           <select name="search-page-direction" id="search-page-direction">
                                <option value="asc"><?php _e( 'ASC', 'touchsize' ) ?></option>
                                <option value="desc"><?php _e( 'DESC', 'touchsize' ) ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                            <input type="button" name="search-page-button" id="search-type-page" class="search-posts-buttons button-primary save-element" value="<?php _e( 'Search', 'touchsize' ) ?>"/>
                        </td>
                    </tr>
                </table>

                <p><?php _e( 'Results', 'touchsize' ); ?>Results</p>
                <table cellpadding="10" id="search-pages-results">
                </table>
            </div>

            <div class="post builder-element hidden">
                <h3 class="element-title"><?php _e( 'Post element', 'touchsize' ); ?></h3>
                <table cellpadding="10">
                    <tr>
                        <td>
                            <?php _e( 'Search post', 'touchsize' ) ?>:
                        </td>
                        <td>
                            <input type="text" name="search-post" id="search-post" style="width:250px"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e( 'Criteria', 'touchsize' ) ?>:
                        </td>
                        <td>
                            <select name="search-post-criteria" id="search-post-criteria">
                                <option value="title"><?php _e( 'Title', 'touchsize' ) ?></option>
                                <option value="title-content"><?php _e( 'Title & Content', 'touchsize' ) ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e( 'Order by', 'touchsize' ) ?>:
                        </td>
                        <td>
                            <select name="search-post-order-by" id="search-post-order-by">
                                <option value="id"><?php _e( 'ID', 'touchsize' ) ?></option>
                                <option value="date"><?php _e( 'Date', 'touchsize' ) ?></option>
                                <option value="views"><?php _e( 'Views', 'touchsize' ) ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e( 'Direction', 'touchsize' ) ?>:
                        </td>
                        <td>
                            <select name="search-post-direction" id="search-post-direction">
                                <option value="asc"><?php _e( 'ASC', 'touchsize' ) ?></option>
                                <option value="desc"><?php _e( 'DESC', 'touchsize' ) ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type="button" name="search-post-button" id="search-type-post" class="search-posts-buttons button-primary save-element"value="<?php _e( 'Search', 'touchsize' ) ?>"/>
                        </td>
                    </tr>
                </table>

                <p><?php _e( 'Results', 'touchsize' ) ?></p>
                <table cellpadding="10" id="search-posts-results">
                </table>
            </div>

            <div class="buttons builder-element hidden">
                <h3 class="element-title"><?php _e('Button element', 'touchsize'); ?></h3>
                <table cellpadding="10">
                    <tr>
                        <td>
                            <label for="buttons-admin-label"><?php _e('Admin label','touchsize');?>:</label>
                        </td>
                        <td>
                           <input type="text" id="buttons-admin-label" name="buttons-admin-label" />
                        </td>
                    </tr>
                </table>
                <p><?php _e("Choose your icon button from the library below:", 'touchsize'); ?></p>
                <div class="builder-element-icon-toggle">
                    <a href="#" class="red-ui-button builder-element-icon-trigger-btn" data-toggle="#builder-element-button-icon-selector"><?php _e('Show icons','touchsize') ?></a>
                </div>
                <ul id="builder-element-button-icon-selector" data-selector="#builder-element-button-icon" class="builder-icon-list ts-custom-selector">
                    <?php echo $icons_li; ?>
                </ul>
                <select name="builder-element-button-icon" id="builder-element-button-icon" class="hidden">
                    <?php echo $icons_options; ?>
                </select>
                <table cellpadding="10">
                    <tr>
                        <td width="70px">
                            <?php _e('Button align', 'touchsize'); ?>
                        </td>
                        <td>
                            <select name="button[align]" id="button-align">
                                <option value="text-left"><?php _e('Left', 'touchsize'); ?></option>
                                <option value="text-right"><?php _e('Right', 'touchsize'); ?></option>
                                <option value="text-center"><?php _e('Center', 'touchsize'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e('Text', 'touchsize') ?>
                        </td>
                        <td>
                           <input type="text" id="button-text" name="button-text" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e('URL', 'touchsize') ?>:
                        </td>
                        <td>
                           <input type="text" id="button-url" name="button-url" value=""/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e('Target', 'touchsize') ?>:
                        </td>
                        <td>
                            <select name="button-target" id="button-target">
                                <option value="_blank">_blank</option>
                                <option value="_self">_self</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e('Size', 'touchsize') ?>:
                        </td>
                        <td>
                           <select name="button-size" id="button-size">
                               <option value="big"><?php _e('Big', 'touchsize') ?></option>
                               <option value="medium" selected="selected"><?php _e('Medium', 'touchsize') ?></option>
                               <option value="small"><?php _e('Small', 'touchsize') ?></option>
                               <option value="xsmall"><?php _e('xSmall', 'touchsize') ?></option>
                           </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e('Text color', 'touchsize') ?>:
                        </td>
                        <td>
                           <input class="colors-section-picker" type="text" id="button-text-color" name="button-text-color" value="#FFFFFF"/>
                           <div class="colors-section-picker" id="ts-button-text-color-picker"></div>
                        </td>
                    </tr>
                     <tr>
                        <td>
                            <?php _e('Choose your mode to display:', 'touchsize') ?>
                        </td>
                        <td>
                           <select name="button-mode-dispaly" id="button-mode-display">
                               <option value="border-button"><?php _e('Border button', 'touchsize') ?></option>
                               <option value="background-button"><?php _e('Background button', 'touchsize') ?></option>
                           </select>
                        </td>
                    </tr>
                    <tr class="button-background-color">
                        <td>
                            <?php _e('Background color', 'touchsize') ?>:
                        </td>
                        <td>
                           <input class="colors-section-picker" type="text" id="button-background-color" name="button-background-color" value="#FFFFFF"/>
                           <div class="colors-section-picker-div" id="ts-button-background-color-picker"></div>
                        </td>
                    </tr>
                    <tr class="button-border-color">
                        <td>
                            <?php _e('Border color', 'touchsize') ?>:
                        </td>
                        <td>
                           <input class="colors-section-picker" type="text" id="button-border-color" name="button-border-color" value="#FFFFFF"/>
                           <div class="colors-section-picker-div" id="ts-button-border-color-picker"></div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="contact-form builder-element hidden">
                <h3 class="element-title"><?php _e('Contact form element', 'touchsize'); ?></h3>
                <em><?php _e("Enter yout email in Options &rarr; Social", 'touchsize') ?></em>
                <table cellpadding="10">
                    <tr>
                        <td>
                            <label for="contact-form-admin-label"><?php _e('Admin label:','touchsize'); ?></label>
                        </td>
                        <td>
                           <input type="text" id="contact-form-admin-label" name="contact-form-admin-label" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" name="contact-form-hide-icon" id="contact-form-hide-icon" /><label for="contact-form-hide-icon"> <?php _e('Hide email icon', 'touchsize') ?></label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" name="contact-form-hide-subject" id="contact-form-hide-subject" />
                            <label for="contact-form-hide-subject"> <?php _e('Hide Subject field', 'touchsize') ?></label>
                        </td>
                    </tr>
                </table>
                <table cellpadding="10">
                     <ul id="contact-form_items">
                    
                     </ul>
                    
                 <input type="hidden" id="contact-form_content" value="" />
                 <input type="button" class="button ts-multiple-add-button" data-element-name="contact-form" id="contact-form_add_item" value=" <?php _e('Add New Field', 'touchsize'); ?>" />
                <?php
                     echo '<script id="contact-form_items_template" type="text/template">';
                     echo '<li id="list-item-id-{{item-id}}" class="contact-form-item ts-multiple-add-list-element">
                            <div class="sortable-meta-element"><span class="tab-arrow icon-down"></span> <span class="tab-item-contact-form ts-multiple-item-tab">Item: {{slide-number}}</span></div>
                            <div class="hidden">
                                <table>
                                    <tr>
                                        <td>
                                            <label for="contact-form-{{item-id}}-type">'.__('Choose your type field:', 'touchsize').'</label>
                                        </td>
                                        <td>
                                            <select class="contact-form-type" data-builder-name="type" name="contact-form[{{item-id}}][type]" id="contact-form-{{item-id}}-type">
                                                <option value="select">'. __('Select', 'touchsize').'</option>
                                                <option value="input">'. __('Input', 'touchsize').'</option>
                                                <option value="textarea">'. __('Textarea', 'touchsize').'</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="contact-form-{{item-id}}-require">'.__('Require field:', 'touchsize').'</label>
                                        </td>
                                        <td>
                                            <select data-builder-name="require" name="contact-form[{{item-id}}][require]" id="contact-form-{{item-id}}-require">
                                                <option value="y">'. __('Yes', 'touchsize').'</option>
                                                <option value="n">'. __('No', 'touchsize').'</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                             <label for="contact-form-{{item-id}}-title">'.__('Title:', 'touchsize').'</label>
                                        </td>
                                        <td>
                                             <input data-builder-name="title" type="text" id="contact-form-{{item-id}}-title" name="contact-form[{{item-id}}][title]" />
                                        </td>
                                    </tr>
                                    <tr class="contact-form-options">
                                        <td>
                                            <label for="contact-form-{{item-id}}-options">'. __('Write your options here in the following field(ex: option1/option2/options3/...):','touchsize').'</label>
                                        </td>
                                        <td>
                                            <input data-builder-name="options" type="text" id="contact-form-{{item-id}}-options" name="contact-form[{{item-id}}][options]" />
                                        </td>
                                    </tr>
                                </table>
                                <input type="hidden" data-builder-name="item_id" value="{{item-id}}" name="contact-form[{{item-id}}][id]" />
                                <input type="button" class="button button-primary remove-item" value="' . __('Remove', 'touchsize') . '" />
                                <a href="#" data-item="contact-form-item" data-increment="contact-form-items" class="button button-primary ts-multiple-item-duplicate">'.__('Duplicate Item', 'touchsize').'</a>
                            </div>
                         </li>';
                     echo '</script>';
                ?>
                </table>
            </div>

            <!-- featured area element -->
            <div class="featured-area builder-element hidden">
                <h3 class="element-title">Featured area element</h3>
                <table cellpadding="10">
                    <tr>
                        <td>
                            <label for="featured-area-admin-label"><?php _e('Admin label','touchsize');?>:</label>
                        </td>
                        <td>
                           <input type="text" id="featured-area-admin-label" name="featured-area-admin-label" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p><?php _e('Add/Remove scroll', 'touchsize') ?></p>
                        </td>
                        <td>
                            <select name="featured-area-scroll" id="featured-area-scroll">
                                <option value="y"><?php _e('Yes', 'touchsize') ?></option>
                                <option value="n"><?php _e('No', 'touchsize') ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p><?php _e('Choose custum post type', 'touchsize') ?></p>
                        </td>
                        <td>
                            <select name="featured-area-custom-post" id="featured-area-custom-post">
                                <option value="post"><?php _e('Post', 'touchsize') ?></option>
                                <option value="video"><?php _e('Video', 'touchsize') ?></option>
                                <option value="video_post"><?php _e('Video & Post', 'touchsize') ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr class="featured-area-video" style="display:none">
                        <td>
                            <p><?php _e('Categories video', 'touchsize') ?></p>
                        </td>
                        <td>
                            <select data-placeholder="<?php _e('Select your category', 'touchsize'); ?>" class="ts-custom-select-input"  name="featured-area-categories-video" id="featured-area-categories-video" multiple>
                                <?php $categories = get_categories(array( 'hide_empty' => 0, 'taxonomy' => 'videos_categories' )); ?>
                                <?php if ( isset($categories) && is_array($categories) && $categories !== '' && !empty($categories) ): ?>
                                    <?php $i = 0; foreach ($categories as $index => $category): ?>
                                        <?php if( is_object($category) ) : ?>
                                            <?php if( $i == 0 ) echo '<option value="0">All</option>'; ?>
                                            <option value="<?php if( is_object($category) ) echo $category->term_id; ?>"><?php if( is_object($category) ) echo $category->cat_name; ?></option>
                                        <?php $i++; endif; ?>
                                    <?php endforeach ?>                                    
                                <?php endif ?>
                            </select>
                        </td>
                    </tr>
                    <tr class="featured-area-posts" style="display:none">
                        <td>
                            <p><?php _e('Categories posts', 'touchsize') ?></p>
                        </td>
                        <td>
                            <select data-placeholder="<?php _e('Select your category', 'touchsize'); ?>" class="ts-custom-select-input"  name="featured-area-categories-video" id="featured-area-categories-posts" multiple>
                                <?php 
                                $categories = get_categories(array( 'hide_empty' => 0 )); ?>
                                <?php if ( isset($categories) && is_array($categories) && $categories !== '' ): ?>
                                    <?php $i = 0; foreach ($categories as $index => $category): ?>
                                        <?php if( is_object($category) ) : ?>
                                            <?php if( $i == 0 ) echo '<option value="0">All</option>'; ?>
                                            <option value="<?php echo $category->term_id; ?>"><?php echo $category->cat_name; ?></option>
                                        <?php $i++; endif; ?>
                                    <?php endforeach ?>                                    
                                <?php endif ?>
                            </select>
                        </td>
                    </tr>
                    <tr class="featured-area-posts_video" style="display:none">
                        <td>
                            <p><?php _e('Categories posts & video', 'touchsize') ?></p>
                        </td>
                        <td>
                            <select data-placeholder="<?php _e('Select your category', 'touchsize'); ?>" class="ts-custom-select-input"  name="featured-area-categories-video_posts" id="featured-area-categories-posts_video" multiple>
                                <?php $categories = get_categories(array( 'hide_empty' => 0, 'taxonomy' => array('category','videos_categories') ));?>
                                    <?php if ( isset($categories) && is_array($categories) && $categories !== '' && !empty($categories) ) : ?>
                                        <?php $i = 0; foreach ($categories as $index => $category): ?>
                                            <?php if( is_object($category) ) : ?>
                                                <?php if( $i == 0 ) echo '<option value="0">All</option>';  ?>
                                                <option value="<?php echo $category->term_id; ?>"><?php echo $category->cat_name; ?></option>
                                            <?php $i++; endif; ?>
                                        <?php endforeach; ?>                                    
                                    <?php endif; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p><?php _e('Choose number of posts', 'touchsize') ?></p>
                        </td>
                        <td>
                            <select name="featured-area-number-posts" id="featured-area-number-posts">
                                <option value="4"><?php _e('4', 'touchsize') ?></option>
                                <option value="5"><?php _e('5', 'touchsize') ?></option>
                                <option value="6"><?php _e('6', 'touchsize') ?></option>
                                <option value="7"><?php _e('7', 'touchsize') ?></option>
                                <option value="8"><?php _e('8', 'touchsize') ?></option>
                                <option value="9"><?php _e('9', 'touchsize') ?></option>
                                <option value="10"><?php _e('10', 'touchsize') ?></option>
                                <option value="11"><?php _e('11', 'touchsize') ?></option>
                                <option value="12"><?php _e('12', 'touchsize') ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr class="featured-area-exclude">
                        <td>
                            <label for="featured-area-exclude-first"><?php _e( "Exclude number of first posts", 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <input type="text" value="" name="featured-area-exclude-first" id="featured-area-exclude-first" size="4"/>
                            <div class="ts-option-description">
                                <?php _e('Insert the number of the posts you want to exclude from showing.', 'touchsize'); ?> 
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="shortcodes builder-element hidden">
                <h3 class="element-title">Shortcode element</h3>
                <table cellpadding="10">
                    <tr>
                        <td>
                            <label for="shortcodes-admin-label"><?php _e('Admin label','touchsize');?>:</label>
                        </td>
                        <td>
                           <input type="text" id="shortcodes-admin-label" name="shortcodes-admin-label" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <textarea name="shortcodes" id="ts-shortcodes" cols="70" rows="10"></textarea>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="text builder-element hidden">
                <h3 class="element-title">Text element</h3>
            </div>

            <!-- Image carousel element -->
            <div class="image-carousel builder-element hidden">
                <h3 class="element-title">Image carousel element</h3>
                <table cellpadding="10">
                    <tr>
                        <td>
                            <label for="image-carousel-admin-label"><?php _e('Admin label','touchsize');?>:</label>
                        </td>
                        <td>
                           <input type="text" id="image-carousel-admin-label" name="image-carousel-admin-label" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="carousel-height"><?php _e( 'Maximum carousel height', 'touchsize' ) ?>:</label>
                        </td>
                        <td><input type="text" id="carousel_height" name="carousel_height" value="400" />px</td>
                    </tr>
                    <tr>
                        <td valign="top"><label for="image_url"><?php _e( 'Add your images', 'touchsize' ) ?>:</label></td>
                        <td>

                            <div id="image-carousel">
                                <ul class="carousel_images">
                                    
                                </ul>
                                <script>
                                    jQuery(document).ready(function($){
                                        setTimeout(function(){
                                            //Show the added images
                                            var image_gallery_ids = jQuery('#carousel_image_gallery').val();
                                            var carousel_images = jQuery('#image-carousel ul.carousel_images');

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
                                    var $carousel_images = $('#image-carousel ul.carousel_images');

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

                                            $('#image-carousel ul li.image').css('cursor','default').each(function() {
                                                var attachment_id = jQuery(this).attr( 'data-attachment_id' );
                                                attachment_url = jQuery(this).attr( 'data-attachment_url' );
                                                attachment_ids = attachment_ids + attachment_id + ':' + attachment_url + ',';
                                            });

                                            $image_gallery_ids.val( attachment_ids );
                                        }
                                    });

                                    // Remove images
                                    $('#image-carousel').on( 'click', 'a.icon-close', function() {

                                        $(this).closest('li.image').remove();

                                        var attachment_ids = '';

                                        $('#image-carousel ul li.image').css('cursor','default').each(function() {
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

            <div class="map builder-element hidden">
                <h3 class="element-title"><?php _e('Map element','touchsize'); ?></h3>
                <table cellpadding="10">
                    <tr>
                        <td>
                            <label for="map-admin-label"><?php _e('Admin label','touchsize');?>:</label>
                        </td>
                        <td>
                           <input type="text" id="map-admin-label" name="map-admin-label" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e( 'Code google map', 'touchsize' ) ?>:
                        </td>
                        <td>
                            <input type="text" name="map-code" id="map-code" value=""/>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="banner builder-element hidden">
                <h3 class="element-title"><?php _e('Banner element', 'touchsize'); ?></h3>
                <table cellpadding="10">
                    <tr>
                        <td>
                            <label for="banner-admin-label"><?php _e('Admin label:','touchsize'); ?></label>
                        </td>
                        <td>
                           <input type="text" id="banner-admin-label" name="banner-admin-label" />
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"><label for="image-banner-url"><?php _e( 'Enter your image for banner box:', 'touchsize' ); ?></label></td>
                        <td>
                            <input type="text" value="" name="image-banner-url"  id="image-banner-url" style="width:300px" />
                            <input type="button" class="button button-primary" id="select_banner_image" value="<?php _e('Upload', 'touchsize'); ?>" />
                            <input type="hidden" value="" id="banner_image_media_id" />
                            <div id="banner-image-preview"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e( 'Enter height image', 'touchsize' ) ?>:
                        </td>
                        <td>
                            <input type="text" name="banner-height" id="banner-height" value=""/>px
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e( 'Enter your title', 'touchsize' ) ?>:
                        </td>
                        <td>
                            <input type="text" name="banner-title" id="banner-title" value=""/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e( 'Enter your subtitle', 'touchsize' ) ?>:
                        </td>
                        <td>
                            <input type="text" name="banner-subtitle" id="banner-subtitle" value=""/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e( 'Enter title button', 'touchsize' ) ?>:
                        </td>
                        <td>
                            <input type="text" name="banner-button-title" id="banner-button-title" value=""/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e( 'Enter your url for button', 'touchsize' ) ?>:
                        </td>
                        <td>
                            <input type="text" name="banner-button-url" id="banner-button-url" value=""/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e( 'Select your background color for button', 'touchsize' ) ?>:
                        </td>
                        <td>
                            <input type="text" id="banner-button-background" class="colors-section-picker" value="#F46964" name="banner-background-color" />
                            <div class="colors-section-picker-div" id="banner-button-background"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e( 'Select your font color', 'touchsize' ) ?>:
                        </td>
                        <td>
                            <input type="text" id="banner-font-color" class="colors-section-picker" value="#f1f1f1" name="banner-font-color" />
                            <div class="colors-section-picker-div" id="banner-font-color"></div>
                        </td>
                    </tr>
                    <tr>
                    <tr>
                        <td width="70px">
                            <?php _e('Text align', 'touchsize'); ?>
                        </td>
                        <td>
                            <select name="banner-text-align" id="banner-text-align">
                                <option value="banner-text-align-left"><?php _e('Left', 'touchsize') ?></option>
                                <option value="banner-text-align-right"><?php _e('Right', 'touchsize') ?></option>
                                <option value="banner-text-align-center"><?php _e('Center', 'touchsize') ?></option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="toggle builder-element hidden">
                <h3 class="element-title"><?php _e('Toggle element', 'touchsize'); ?></h3>
                <table cellpadding="10">
                    <tr>
                        <td>
                            <label for="toggle-admin-label"><?php _e('Admin label:','touchsize'); ?></label>
                        </td>
                        <td>
                           <input type="text" id="toggle-admin-label" name="toggle-admin-label" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e( 'Enter your title:', 'touchsize' ); ?>
                        </td>
                        <td>
                            <input type="text" name="toggle-title" id="toggle-title" value=''/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e( 'Enter your description:', 'touchsize' ); ?>
                        </td>
                        <td>
                            <textarea name="toggle-description" id="toggle-description" cols="45" rows="15"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e( 'State (opened/closed):', 'touchsize' ); ?>
                        </td>
                        <td>
                            <select name="toggle-state" id="toggle-state">
                                <option value="open"><?php _e( 'Open', 'touchsize' ); ?></option>
                                <option value="closed"><?php _e( 'Closed', 'touchsize' ); ?></option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>

        <!-- tab element -->
            <div class="tab builder-element hidden">
                <h3 class="element-title"><?php _e('Tab element','touchsize'); ?></h3>
                <table cellpadding="10">
                    <tr>
                        <td>
                            <?php _e('Admin label:','touchsize'); ?>
                        </td>
                        <td>
                           <input type="text" id="tab-admin-label" name="tab-admin-label" />
                        </td>
                    </tr> 
                    <tr>
                        <td>
                            <?php _e('Choose mode tab:','touchsize'); ?>
                        </td>
                        <td>
                           <select id="tab-mode" name="tab-mode">
                               <option value="horizontal"><?php _e('Horizontal','touchsize'); ?></option>
                               <option value="vertical"><?php _e('Vertical','touchsize'); ?></option>
                           </select>
                        </td>
                    </tr>
                </table>
                <ul id="tab_items">
               
                </ul>
                   
                <input type="hidden" id="tab_content" value="" />
                <input type="button" class="button ts-multiple-add-button" data-element-name="tab" id="tab_add_item" value=" <?php _e('Add New Tab', 'touchsize'); ?>" />
                  <?php
                    echo '<script id="tab_items_template" type="text/template">';
                    echo '<li id="list-item-id-{{item-id}}" class="tab-item ts-multiple-add-list-element">
                            <div class="sortable-meta-element"><span class="tab-arrow icon-down"></span> <span class="tab-item-tab ts-multiple-item-tab">Item: {{slide-number}}</span></div>
                            <div class="hidden">
                                <table>
                                    <tr>
                                        <td>
                                            <label for="tab-{{item-id}}-title">Title:</label>
                                        </td>
                                        <td>
                                            <input data-builder-name="title" type="text" id="tab-{{item-id}}-title" name="tab[{{item-id}}][title]" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="tab-{{item-id}}-text">Write your text here:</label>
                                        </td>
                                        <td>
                                            <textarea data-builder-name="text" name="tab[{{item-id}}][text]" id="tab-{{item-id}}-text" cols="45" rows="5"></textarea>
                                        </td>
                                    </tr>
                                </table>
                                <input type="hidden" data-builder-name="item_id" value="{{item-id}}" name="testimonials[{{item-id}}][id]" />
                                <input type="button" class="button button-primary remove-item" value="' . __('Remove', 'touchsize') . '" />
                                <a href="#" data-item="tab-item" data-increment="tab-items" class="button button-primary ts-multiple-item-duplicate">'.__('Duplicate Item', 'touchsize').'</a>
                            </div>
                        </li>';
                    echo '</script>';
               ?></table>
            </div>
          
            <!-- List videos element -->

            <div class="list-videos builder-element hidden">
                <h3 class="element-title"><?php _e('List videos element', 'touchsize'); ?></h3>
                <!-- Select category -->
                <table cellpadding="10">
                    <tr>
                        <td>
                            <label for="list-videos-admin-label"><?php _e('Admin label','touchsize');?>:</label>
                        </td>
                        <td>
                           <input type="text" id="list-videos-admin-label" name="list-videos-admin-label" />
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <label for="list-videos-category"><?php _e( 'Category', 'touchsize' ) ?>:</label>
                        </td>
                        <td>                           
                            <select data-placeholder="<?php _e('Select your category', 'touchsize'); ?>" class="ts-custom-select-input" name="list-videos-category" id="list-videos-category" multiple>
                                <?php $categories = get_categories(array( 'hide_empty' => 0, 'taxonomy' => 'videos_categories' )); ?>
                                <?php if ( isset($categories) && is_array($categories) && !empty($categories) ): ?>
                                    <?php $i = 0; foreach ($categories as $index => $category): ?>
                                        <?php if( is_object($category) ) : ?>
                                            <?php if( $i == 0 ) echo '<option value="0">All</option>'; ?>
                                            <option value="<?php if( is_object($category) ) echo $category->term_id; ?>"><?php if( is_object($category) ) echo $category->cat_name; ?></option>
                                        <?php $i++; endif; ?>
                                    <?php endforeach ?>                                    
                                <?php endif ?>
                            </select>
                            <div class="ts-option-description">
                                <?php _e('Choose the categories that you want to showcase articles from.', 'touchsize'); ?>
                            </div>

                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label><?php _e( 'Show only featured', 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <select name="list-videos-featured" id="list-videos-featured">
                                <option value="y"><?php _e( 'Yes', 'touchsize' ) ?></option>
                                <option value="n" selected="selected"><?php _e( 'No', 'touchsize' ) ?></option>
                            </select>

                            <div class="ts-option-description">
                                <?php _e('You can display only featured posts', 'touchsize'); ?>
                            </div>
                        </td>
                    </tr>
                    <?php $social_sharing = get_option('videotouch_styles', array('sharing_overlay' => 'N'));  if( $social_sharing['sharing_overlay'] === 'Y' ) : ?>
                        <tr class="list-videos-modal" style="display:none;">
                            <td>
                                <label><?php _e( 'Play in modal', 'touchsize' ) ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-modal" id="list-videos-modal">
                                    <option value="y"><?php _e( 'Yes', 'touchsize' ) ?></option>
                                    <option selected="selected" value="n"><?php _e( 'No', 'touchsize' ) ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php _e('You can display video in modal', 'touchsize'); ?>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td>
                            <label for="list-videos-exclude"><?php _e( "Exclude post ID's", 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <input type="text" value="" name="list-videos-exclude" id="list-videos-exclude" size="4"/>
                            <div class="ts-option-description">
                                <?php _e('Insert the IDs of the posts you want to exclude from showing.', 'touchsize'); ?> (ex: <b>100,101,102,104</b>)
                            </div>
                        </td>
                    </tr>
                     <tr class="list-videos-exclude">
                        <td>
                            <label for="list-videos-exclude-first"><?php _e( "Exclude number of first posts", 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <input type="text" value="" name="list-videos-exclude-first" id="list-videos-exclude-first" size="4"/>
                            <div class="ts-option-description">
                                <?php _e('Insert the number of the posts you want to exclude from showing.', 'touchsize'); ?> 
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="list-videos-display-mode"><?php _e( 'How to display', 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-videos-display-mode" id="list-videos-display-mode-selector">
                               <li><img class="image-radio-input clickable-element" data-option="grid" src="<?php echo get_template_directory_uri().'/images/options/grid_view.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="list" src="<?php echo get_template_directory_uri().'/images/options/list_view.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="thumbnails" src="<?php echo get_template_directory_uri().'/images/options/thumb_view.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="big-post" src="<?php echo get_template_directory_uri().'/images/options/big_posts_view.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="super-post" src="<?php echo get_template_directory_uri().'/images/options/super_post_view.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="timeline" src="<?php echo get_template_directory_uri().'/images/options/timeline_view.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="mosaic" src="<?php echo get_template_directory_uri().'/images/options/mosaic_view.png'; ?>"></li>
                            </ul>
                            <select class="select_2" class="hidden" name="list-videos-display-mode" id="list-videos-display-mode">
                                <option value="grid"><?php _e( 'Grid', 'touchsize' ) ?></option>
                                <option value="list"><?php _e( 'List', 'touchsize' ) ?></option>
                                <option value="thumbnails"><?php _e( 'Thumbnails', 'touchsize' ) ?></option>
                                <option value="big-post"><?php _e( 'Big post', 'touchsize' ) ?></option>
                                <option value="super-post"><?php _e( 'Super Post', 'touchsize' ) ?></option>
                                <option value="timeline"><?php _e( 'Timeline Post', 'touchsize' ) ?></option>
                                <option value="mosaic"><?php _e( 'mosaic Post', 'touchsize' ) ?></option>
                            </select>
                            <div class="ts-option-description">
                                <?php _e('Choose your article view type. Depending on what type of article showcase layout you select you will get different options. You can read more about view types in our documentation files: ', 'touchsize'); echo $touchsize_com; ?>
                            </div>
                        </td>
                    </tr>
                </table>

                <div id="list-videos-display-mode-options">
                    <!-- Grid options -->
                    <div class="list-videos-grid hidden">
                        <table cellpadding="10">
                            <tr>
                                <td>
                                    <label><?php _e( 'Behavior', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-videos-grid-behavior" id="list-videos-grid-behavior-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="none" src="<?php echo get_template_directory_uri().'/images/options/behavior_none.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="carousel" src="<?php echo get_template_directory_uri().'/images/options/behavior_carousel.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="masonry" src="<?php echo get_template_directory_uri().'/images/options/behavior_masonry.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="scroll" src="<?php echo get_template_directory_uri().'/images/options/behavior_scroll.png'; ?>"></li>
                                    </ul>
                                    <select class="hidden" name="list-videos-grid-behavior" id="list-videos-grid-behavior">
                                        <option value="none"><?php _e( 'Normal', 'touchsize' ) ?></option>
                                        <option value="carousel"><?php _e( 'Carousel', 'touchsize' ) ?></option>
                                        <option value="masonry"><?php _e( 'Masonry', 'touchsize' ) ?></option>
                                        <option value="scroll"><?php _e( 'Scroll', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Choose your posts behavior - you can have them just shown, or you can activate the carousel. If carousel is selected your articles will show up in a line with arrows for navigation. If masonry bahevior is selected - your articles will be arranged in to fit it. Be aware that activating the masonry option the crop settings for image sizes tab will be overwritten.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-grid-title"><?php _e( 'Title position', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-videos-grid-title" id="list-videos-grid-title-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="title-above-image" src="<?php echo get_template_directory_uri().'/images/options/grid_view_title_image.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="title-above-excerpt" src="<?php echo get_template_directory_uri().'/images/options/grid_view_title_excerpt.png'; ?>"></li>
                                    </ul>
                                    <select class="hidden" name="list-videos-grid-title" id="list-videos-grid-title">
                                        <option value="title-above-image"><?php _e( 'Above image', 'touchsize' ) ?></option>
                                        <option value="title-above-excerpt" selected="selected"><?php _e( 'Above excerpt', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Select your title position for you grid posts. You can either have it above the image of above the excerpt. Note that sometimes title may change the position of the meta (date, categories, author) as well.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-grid-show-meta"><?php _e( 'Show meta', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="radio" name="list-videos-grid-show-meta" id="list-videos-grid-show-meta-y" value="y"  checked = "checked"  /> 
                                    <label for="list-videos-grid-show-meta-y"><?php _e( 'Yes', 'touchsize' ) ?></label>
                                    <input type="radio" name="list-videos-grid-show-meta" id="list-videos-grid-show-meta-n" value="n" />
                                    <label for="list-videos-grid-show-meta-n"><?php _e( 'No', 'touchsize' ) ?></label>
                                    <div class="ts-option-description">
                                        <?php _e('You can choose to show or to hide your meta details for your articles. Meta values include date, author, categories and other article details.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-grid-el-per-row"><?php _e( 'Elements per row', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-videos-grid-el-per-row" id="list-videos-grid-el-per-row-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="1" src="<?php echo get_template_directory_uri().'/images/options/per_row_1.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="2" src="<?php echo get_template_directory_uri().'/images/options/per_row_2.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="3" src="<?php echo get_template_directory_uri().'/images/options/per_row_3.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="4" src="<?php echo get_template_directory_uri().'/images/options/per_row_4.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="6" src="<?php echo get_template_directory_uri().'/images/options/per_row_6.png'; ?>"></li>
                                    </ul>
                                    <select class="hidden" name="list-videos-grid-el-per-row" id="list-videos-grid-el-per-row">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3" selected="selected">3</option>
                                        <option value="4">4</option>
                                        <option value="6">6</option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Your articles will be shown columns. Choose how mane columns do you want to use per line. Note that for mobile devices you will get only ONE element per row for better usability.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr class="list-videos-grid-nr-of-posts">
                                <td>
                                    <label for="list-videos-grid-nr-of-posts"><?php _e( 'How many posts to extract', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="text" value="" name="list-videos-grid-nr-of-posts" id="list-videos-grid-nr-of-posts" size="4"/>
                                    <div class="ts-option-description">
                                        <?php _e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-grid-related"><?php _e('Show related posts:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <select name="list-videos-grid-related" id="list-videos-grid-related">
                                        <option value="y"><?php _e( 'Yes', 'touchsize' ); ?></option>
                                        <option selected="selected" value="n"><?php _e( 'No', 'touchsize' ); ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('You can set each of your grid to show related articles below. A list with other articles will appear below.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-grid-order-by"><?php _e( 'Order by', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-videos-grid-order-by" id="list-videos-grid-order-by">
                                        <option value="date"><?php _e( 'Date', 'touchsize' ) ?></option>
                                        <option value="comments"><?php _e( 'Comments', 'touchsize' ) ?></option>
                                        <option value="views"><?php _e( 'Views', 'touchsize' ) ?></option>
                                        <option value="likes"><?php _e( 'Likes', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-grid-order-direction"><?php _e( 'Order direction', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-videos-grid-order-direction" id="list-videos-grid-order-direction">
                                        <option value="asc"><?php _e( 'ASC', 'touchsize' ) ?></option>
                                        <option selected="selected" value="desc" selected="selected"><?php _e( 'DESC', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-grid-special-effects"><?php _e( 'Special effects', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-videos-grid-special-effects" id="list-videos-grid-special-effects">
                                        <option value="none"><?php _e('No effect', 'touchsize') ?></option>
                                        <option value="opacited"><?php _e('Fade in', 'touchsize') ?></option>
                                        <option value="rotate-in"><?php _e('Rotate in', 'touchsize') ?></option>
                                        <option value="3dflip"><?php _e('3d flip', 'touchsize') ?></option>
                                        <option value="scaler"><?php _e('Scale', 'touchsize') ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr class="list-videos-grid-pagination">
                                <td>
                                    <label for="list-videos-grid-pagination"><?php _e( 'Enable pagination', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-videos-grid-pagination" id="list-videos-grid-pagination">
                                        <option selected="selected" value="n"><?php _e('None', 'touchsize') ?></option>
                                        <option value="y"><?php _e('Enable pagination', 'touchsize') ?></option>
                                        <option value="load-more"><?php _e('Load more', 'touchsize') ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('You can add pagination.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- List options -->
                    <div class="list-videos-list hidden">

                        <table cellpadding="10">
                            <tr>
                                <td>
                                    <label for="list-videos-list-show-meta"><?php _e( 'Show meta', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="radio" name="list-videos-list-show-meta" id="list-videos-list-show-meta-y" value="y"  checked = "checked"  /> 
                                    <label for="list-videos-list-show-meta-y"><?php _e( 'Yes', 'touchsize' ) ?></label>
                                    <input type="radio" name="list-videos-list-show-meta" id="list-videos-list-show-meta-n" value="n" />
                                    <label for="list-videos-list-show-meta-n"><?php _e( 'No', 'touchsize' ) ?></label>
                                    <div class="ts-option-description">
                                        <?php _e('You can choose to show or to hide your meta details for your articles. Meta values include date, author, categories and other article details.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr class="list-videos-list-nr-of-posts">
                                <td>
                                    <label for="list-videos-list-nr-of-posts"><?php _e( 'How many posts to extract', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="text" value="" name="list-videos-list-nr-of-posts" id="list-videos-list-nr-of-posts" size="4"/>
                                    <div class="ts-option-description">
                                        <?php _e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php _e( 'Content split', 'touchsize' ) ?>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-videos-list-image-split" id="list-videos-list-image-split-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="1-3" src="<?php echo get_template_directory_uri().'/images/options/list_view_13.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="1-2" src="<?php echo get_template_directory_uri().'/images/options/list_view_12.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="3-4" src="<?php echo get_template_directory_uri().'/images/options/list_view_34.png'; ?>"></li>
                                    </ul>
                                    <select class="hidden" name="list-videos-list-image-split" id="list-videos-list-image-split">
                                        <option value="1-3">1/3</option>
                                        <option value="1-2">1/2</option>
                                        <option value="3-4">3/4</option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Choose your title/content split proportions. You can have them either 1/3, 1/2, 3/4 for your title and 2/3,1/2, 1/4 accordingly. Depending on the text and titles you use, select your split type.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-list-order-by"><?php _e( 'Order by', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-videos-list-order-by" id="list-videos-list-order-by">
                                        <option value="date"><?php _e( 'Date', 'touchsize' ) ?></option>
                                        <option value="comments"><?php _e( 'Comments', 'touchsize' ) ?></option>
                                        <option value="views"><?php _e( 'Views', 'touchsize' ) ?></option>
                                        <option value="likes"><?php _e( 'Likes', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-list-order-direction"><?php _e( 'Order direction', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-videos-list-order-direction" id="list-videos-list-order-direction">
                                        <option value="asc"><?php _e( 'ASC', 'touchsize' ) ?></option>
                                        <option value="desc" selected="selected"><?php _e( 'DESC', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-list-special-effects"><?php _e( 'Special effects:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <select name="list-videos-list-special-effects" id="list-videos-list-special-effects">
                                        <option value="none"><?php _e('No effect', 'touchsize') ?></option>
                                        <option value="opacited"><?php _e('Fade in', 'touchsize') ?></option>
                                        <option value="rotate-in"><?php _e('Rotate in', 'touchsize') ?></option>
                                        <option value="3dflip"><?php _e('3d flip', 'touchsize') ?></option>
                                        <option value="scaler"><?php _e('Scale', 'touchsize') ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-list-pagination"><?php _e( 'Enable pagination', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-videos-list-pagination" id="list-videos-list-pagination">
                                        <option selected="selected" value="n"><?php _e('None', 'touchsize') ?></option>
                                        <option value="y"><?php _e('Enable pagination', 'touchsize') ?></option>
                                        <option value="load-more"><?php _e('Load more', 'touchsize') ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('You can add pagination.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Thumbnail options -->
                    <div class="list-videos-thumbnails hidden">
                        <table cellpadding="10">
                            <tr>
                                <td>
                                    <label><?php _e( 'Behavior', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-videos-thumbnail-behavior" id="list-videos-thumbnail-behavior-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="none" src="<?php echo get_template_directory_uri().'/images/options/behavior_none.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="carousel" src="<?php echo get_template_directory_uri().'/images/options/behavior_carousel.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="masonry" src="<?php echo get_template_directory_uri().'/images/options/behavior_masonry.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="scroll" src="<?php echo get_template_directory_uri().'/images/options/behavior_scroll.png'; ?>"></li>
                                    </ul>
                                    <select name="list-videos-thumbnail-behavior" id="list-videos-thumbnail-behavior">
                                        <option value="none"><?php _e( 'Normal', 'touchsize' ) ?></option>
                                        <option value="carousel"><?php _e( 'Carousel', 'touchsize' ) ?></option>
                                        <option value="masonry"><?php _e( 'Masonry', 'touchsize' ) ?></option>
                                        <option value="scroll"><?php _e( 'Scroll', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Choose your posts behavior - you can have them just shown, or you can activate the carousel. If carousel is selected your articles will show up in a line with arrows for navigation. If masonry bahevior is selected - your articles will be arranged in to fit it. Be aware that activating the masonry option the crop settings for image sizes tab will be overwritten.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-thumbnail-posts-per-row"><?php _e( 'Number of posts per row', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-videos-thumbnail-posts-per-row" id="list-videos-thumbnail-posts-per-row-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="1" src="<?php echo get_template_directory_uri().'/images/options/per_row_1.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="2" src="<?php echo get_template_directory_uri().'/images/options/per_row_2.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="3" src="<?php echo get_template_directory_uri().'/images/options/per_row_3.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="4" src="<?php echo get_template_directory_uri().'/images/options/per_row_4.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="6" src="<?php echo get_template_directory_uri().'/images/options/per_row_6.png'; ?>"></li>
                                    </ul>
                                    <select class="hidden" name="list-videos-thumbnail-posts-per-row" id="list-videos-thumbnail-posts-per-row">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3" selected="selected">3</option>
                                        <option value="4">4</option>
                                        <option value="6">6</option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Your articles will be shown columns. Choose how mane columns do you want to use per line. Note that for mobile devices you will get only ONE element per row for better usability.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr class="list-videos-thumbnail-nr-of-posts">
                                <td>
                                    <label for="list-videos-thumbnail-limit"><?php _e( 'How many posts to extract', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="text" value="" name="list-videos-thumbnail-limit"  id="list-videos-thumbnail-limit" size="4"/>
                                    <div class="ts-option-description">
                                        <?php _e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                             <tr>
                                <td>
                                    <label for="list-videos-thumbnail-show-meta"><?php _e( 'Show meta:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <input type="radio" name="list-videos-thumbnail-show-meta" id="list-videos-thumbnail-show-meta-y" value="y"  checked = "checked"  /> 
                                    <label for="list-videos-thumbnail-show-meta-y"><?php _e( 'Yes', 'touchsize' ) ?></label>
                                    <input type="radio" name="list-videos-thumbnail-show-meta" id="list-videos-thumbnail-show-meta-n" value="n" />
                                    <label for="list-videos-thumbnail-show-meta-n"><?php _e( 'No', 'touchsize' ) ?></label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-thumbnail-order-by"><?php _e( 'Order by', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-videos-thumbnail-order-by" id="list-videos-thumbnail-order-by">
                                        <option value="date"><?php _e( 'Date', 'touchsize' ) ?></option>
                                        <option value="comments"><?php _e( 'Comments', 'touchsize' ) ?></option>
                                        <option value="views"><?php _e( 'Views', 'touchsize' ) ?></option>
                                        <option value="likes"><?php _e( 'Likes', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-thumbnail-order-direction"><?php _e( 'Order direction', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-videos-thumbnail-order-direction" id="list-videos-thumbnails-order-direction">
                                        <option value="asc"><?php _e( 'ASC', 'touchsize' ) ?></option>
                                        <option value="desc" selected="selected"><?php _e( 'DESC', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-thumbnail-special-effects"><?php _e( 'Special effects:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <select name="list-videos-thumbnail-special-effects" id="list-videos-thumbnail-special-effects">
                                        <option value="none"><?php _e('No effect', 'touchsize') ?></option>
                                        <option value="opacited"><?php _e('Fade in', 'touchsize') ?></option>
                                        <option value="rotate-in"><?php _e('Rotate in', 'touchsize') ?></option>
                                        <option value="3dflip"><?php _e('3d flip', 'touchsize') ?></option>
                                        <option value="scaler"><?php _e('Scale' , 'touchsize') ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-thumbnail-gutter"><?php _e( 'Remove gutter(space) between articles:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <select name="list-videos-thumbnail-gutter" id="list-videos-thumbnail-gutter">
                                        <option value="n"><?php _e('No', 'touchsize') ?></option>
                                        <option value="y"><?php _e('Yes', 'touchsize') ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Gutter is the space between your articles. You can remove the space and have your articles sticked one to another.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr class="list-videos-thumbnails-pagination">
                                <td>
                                    <label for="list-videos-thumbnails-pagination"><?php _e( 'Enable pagination:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <select name="list-videos-thumbnails-pagination" id="list-videos-thumbnails-pagination">
                                        <option selected="selected" value="n"><?php _e('None', 'touchsize') ?></option>
                                        <option value="y"><?php _e('Enable pagination', 'touchsize') ?></option>
                                        <option value="load-more"><?php _e('Load more', 'touchsize') ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('You can add pagination.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="list-videos-big-post hidden">
                        <table cellpadding="10">
                            <tr>
                                <td>
                                    <label for="list-videos-big-post-title"><?php _e( 'Title position:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-videos-big-post-title" id="list-videos-big-post-title-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="title-above-image" src="<?php echo get_template_directory_uri().'/images/options/grid_view_title_image.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="title-above-excerpt" src="<?php echo get_template_directory_uri().'/images/options/grid_view_title_excerpt.png'; ?>"></li>
                                    </ul>
                                    <select name="list-videos-big-post-title" id="list-videos-big-post-title">
                                        <option value="title-above-image"><?php _e( 'Above image', 'touchsize' ) ?></option>
                                        <option value="title-above-excerpt"><?php _e( 'Above excerpt', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Select your title position for you grid posts. You can either have it above the image of above the excerpt. Note that sometimes title may change the position of the meta (date, categories, author) as well.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-big-post-image-position"><?php _e( 'Image position:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <select name="list-videos-big-post-image-position" id="list-videos-big-post-image-position">
                                        <option value="left"><?php _e( 'Left', 'touchsize' ) ?></option>
                                        <option value="right"><?php _e( 'Right', 'touchsize' ) ?></option>
                                        <option value="mosaic"><?php _e( 'Mosaic', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('The way you want to show your big post', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-big-post-show-meta"><?php _e( 'Show meta:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <input type="radio" name="list-videos-big-post-show-meta" id="list-videos-big-post-show-meta-y" value="y"  checked = "checked"  /> 
                                    <label for="list-videos-big-post-show-meta-y"><?php _e( 'Yes', 'touchsize' ) ?></label>
                                    <input type="radio" name="list-videos-big-post-show-meta" id="list-videos-big-post-show-meta-n" value="n" />
                                    <label for="list-videos-big-post-show-meta-n"><?php _e( 'No', 'touchsize' ) ?></label>
                                    <div class="ts-option-description">
                                        <?php _e('You can choose to show or to hide your meta details for your articles. Meta values include date, author, categories and other article details.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr class="list-videos-big-post-nr-of-posts">
                                <td>
                                    <label for="list-videos-big-post-nr-of-posts"><?php _e( 'How many posts to extract:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <input type="text" value="" name="list-videos-big-post-nr-of-posts" id="list-videos-big-post-nr-of-posts" size="4"/>
                                    <div class="ts-option-description">
                                        <?php _e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php _e( 'Content split', 'touchsize' ) ?>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-videos-big-post-image-split" id="list-videos-big-post-image-split-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="1-3" src="<?php echo get_template_directory_uri().'/images/options/big_posts_13.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="1-2" src="<?php echo get_template_directory_uri().'/images/options/big_posts_12.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="3-4" src="<?php echo get_template_directory_uri().'/images/options/big_posts_34.png'; ?>"></li>
                                    </ul>
                                    <select class="hidden" name="list-videos-big-post-image-split" id="list-videos-big-post-image-split">
                                        <option value="1-3">1/3</option>
                                        <option value="1-2">1/2</option>
                                        <option value="3-4">3/4</option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Choose your image/content split proportions. You can have them either 1/3, 1/2, 3/4 for your title and 2/3,1/2, 1/4 accordingly. Depending on the text and titles you use, select your split type.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-big-post-order-by"><?php _e( 'Order by', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-videos-big-post-order-by" id="list-videos-big-post-order-by">
                                        <option value="date"><?php _e( 'Date', 'touchsize' ) ?></option>
                                        <option value="comments"><?php _e( 'Comments', 'touchsize' ) ?></option>
                                        <option value="views"><?php _e( 'Views', 'touchsize' ) ?></option>
                                        <option value="likes"><?php _e( 'Likes', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-big-post-order-direction"><?php _e( 'Order direction', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-videos-big-post-order-direction" id="list-videos-big-post-order-direction">
                                        <option value="asc"><?php _e( 'ASC', 'touchsize' ) ?></option>
                                        <option selected="selected" value="desc"><?php _e( 'DESC', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-big-post-related"><?php _e('Show related posts', 'touchsize'); ?>:</label>
                                </td>
                                <td>
                                    <select name="list-videos-big-post-related" id="list-videos-big-post-related">
                                        <option value="y"><?php _e( 'Yes', 'touchsize' ); ?></option>
                                        <option selected="selected" value="n"><?php _e( 'No', 'touchsize' ); ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('You can set each of your grid to show related articles below. A list with other articles will appear below.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-big-post-special-effects"><?php _e( 'Special effects', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-videos-big-post-special-effects" id="list-videos-big-post-special-effects">
                                        <option value="none"><?php _e('No effect', 'touchsize') ?></option>
                                        <option value="opacited"><?php _e('Fade in', 'touchsize') ?></option>
                                        <option value="rotate-in"><?php _e('Rotate in', 'touchsize') ?></option>
                                        <option value="3dflip"><?php _e('3d flip', 'touchsize') ?></option>
                                        <option value="scaler"><?php _e('Scale', 'touchsize') ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-big-post-pagination"><?php _e( 'Enable pagination', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-videos-big-post-pagination" id="list-videos-big-post-pagination">
                                        <option selected="selected" value="n"><?php _e('None', 'touchsize') ?></option>
                                        <option value="y"><?php _e('Enable pagination', 'touchsize') ?></option>
                                        <option value="load-more"><?php _e('Load more', 'touchsize') ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('You can add pagination.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="list-videos-super-post hidden">
                        <table cellpadding="10">
                            <tr>
                                <td>
                                    <label for="list-videos-super-post-posts-per-row"><?php _e( 'Number of posts per row', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-videos-super-post-posts-per-row" id="list-videos-super-post-posts-per-row-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="1" src="<?php echo get_template_directory_uri().'/images/options/per_row_1.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="2" src="<?php echo get_template_directory_uri().'/images/options/per_row_2.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="3" src="<?php echo get_template_directory_uri().'/images/options/per_row_3.png'; ?>"></li>
                                    </ul>
                                    <select class="hidden" name="list-videos-super-post-posts-per-row" id="list-videos-super-post-posts-per-row">
                                        <option value="1" selected="selected">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Your articles will be shown columns. Choose how mane columns do you want to use per line. Note that for mobile devices you will get only ONE element per row for better usability.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr class="list-videos-super-post-nr-of-posts">
                                <td>
                                    <label for="list-videos-super-post-limit"><?php _e( 'How many posts to extract', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="text" value="" name="list-videos-super-post-limit"  id="list-videos-super-post-limit" size="4"/>
                                    <div class="ts-option-description">
                                        <?php _e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-super-post-order-by"><?php _e( 'Order by', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-videos-super-post-order-by" id="list-videos-super-post-order-by">
                                        <option value="date"><?php _e( 'Date', 'touchsize' ) ?></option>
                                        <option value="comments"><?php _e( 'Comments', 'touchsize' ) ?></option>
                                        <option value="views"><?php _e( 'Views', 'touchsize' ) ?></option>
                                        <option value="likes"><?php _e( 'Likes', 'touchsize' ) ?></option>
                                    </select>
                                     <div class="ts-option-description">
                                        <?php _e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                     <label for="list-videos-super-post-order-direction"><?php _e( 'Order direction', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-videos-super-post-order-direction" id="list-videos-super-post-order-direction">
                                        <option value="asc"><?php _e( 'ASC', 'touchsize' ) ?></option>
                                        <option selected="selected" value="desc"><?php _e( 'DESC', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-super-post-special-effects"><?php _e( 'Special effects:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <select name="list-videos-super-post-special-effects" id="list-videos-super-post-special-effects">
                                        <option value="none"><?php _e('No effect', 'touchsize') ?></option>
                                        <option value="opacited"><?php _e('Fade in', 'touchsize') ?></option>
                                        <option value="rotate-in"><?php _e('Rotate in', 'touchsize') ?></option>
                                        <option value="3dflip"><?php _e('3d flip', 'touchsize') ?></option>
                                        <option value="scaler"><?php _e('Scale', 'touchsize') ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-super-post-pagination"><?php _e( 'Enable pagination', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-videos-super-post-pagination" id="list-videos-super-post-pagination">
                                        <option selected="selected" value="n"><?php _e('None', 'touchsize') ?></option>
                                        <option value="y"><?php _e('Enable pagination', 'touchsize') ?></option>
                                        <option value="load-more"><?php _e('Load more', 'touchsize') ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('You can add pagination.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!-- Timeline options -->
                    <div class="list-videos-timeline hidden">

                        <table cellpadding="10">
                            <tr>
                                <td>
                                    <label for="list-videos-timeline-show-meta"><?php _e( 'Show meta', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="radio" name="list-videos-timeline-show-meta" id="list-videos-timeline-show-meta-y" value="y"  checked = "checked"  /> 
                                    <label for="list-videos-timeline-show-meta-y"><?php _e( 'Yes', 'touchsize' ) ?></label>
                                    <input type="radio" name="list-videos-timeline-show-meta" id="list-videos-timeline-show-meta-n" value="n" />
                                    <label for="list-videos-timeline-show-meta-n"><?php _e( 'No', 'touchsize' ) ?></label>
                                    <div class="ts-option-description">
                                        <?php _e('You can choose to show or to hide your meta details for your articles. Meta values include date, author, categories and other article details.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-timeline-image"><?php _e( 'Show image', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-videos-timeline-image" id="list-videos-timeline-image">
                                        <option value="y"><?php _e( 'Yes', 'touchsize' ) ?></option>
                                        <option value="n"><?php _e( 'No', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Display image', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr class="list-videos-timeline-nr-of-posts">
                                <td>
                                    <label for="list-videos-timeline-post-limit"><?php _e( 'How many posts to extract', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="text" value="" name="list-videos-timeline-post-limit" id="list-videos-timeline-post-limit" size="4"/>
                                    <div class="ts-option-description">
                                        <?php _e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-timeline-order-by"><?php _e( 'Order by', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-videos-timeline-order-by" id="list-videos-timeline-order-by">
                                        <option value="date"><?php _e( 'Date', 'touchsize' ) ?></option>
                                        <option value="comments"><?php _e( 'Comments', 'touchsize' ) ?></option>
                                        <option value="views"><?php _e( 'Views', 'touchsize' ) ?></option>
                                        <option value="likes"><?php _e( 'Likes', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-timeline-order-direction"><?php _e( 'Order direction', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-videos-timeline-order-direction" id="list-videos-timeline-order-direction">
                                        <option value="asc"><?php _e( 'ASC', 'touchsize' ) ?></option>
                                        <option value="desc" selected="selected"><?php _e( 'DESC', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-timeline-pagination"><?php _e( 'Enable pagination', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-videos-timeline-pagination" id="list-videos-timeline-pagination">
                                        <option selected="selected" value="n"><?php _e('None', 'touchsize') ?></option>
                                        <option value="y"><?php _e('Enable pagination', 'touchsize') ?></option>
                                        <option value="load-more"><?php _e('Load more', 'touchsize') ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('You can add pagination.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!-- mosaic options -->
                    <div class="list-videos-mosaic hidden">

                        <table cellpadding="10">
                            <tr class="list-videos-mosaic-layout">
                                <td>
                                    <label for="list-videos-mosaic-layout"><?php _e( 'Choose how to show the posts', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-videos-mosaic-layout" id="list-videos-mosaic-layout" class="ts-mosaic-layout">
                                        <option value="rectangles"><?php _e( 'Rectangles', 'touchsize' ) ?></option>
                                        <option value="square"><?php _e( 'Squares', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Choose how to show the posts', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr class="list-videos-mosaic-rows">
                                <td>
                                    <label for="list-videos-mosaic-rows"><?php _e( 'Change number of rows', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-videos-mosaic-rows" id="list-videos-mosaic-rows" class="ts-mosaic-rows">
                                        <option value="2"><?php _e( '2', 'touchsize' ) ?></option>
                                        <option value="3"><?php _e( '3', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Change number of rows', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr class="list-videos-mosaic-nr-of-posts">
                                <td>
                                    <label for="list-videos-mosaic-post-limit"><?php _e( 'How many posts to extract', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <div class="ts-mosaic-post-limit-rows-2">
                                        <input class="ts-input-slider" type="text" name="list-videos-mosaic-post-limit-rows-2" id="list-videos-mosaic-post-limit-rows-2" value="6" disabled />
                                        <div id="list-videos-mosaic-post-limit-rows-2-slider"></div>
                                    </div>
                                    <div class="ts-mosaic-post-limit-rows-3">
                                        <input type="text" name="list-videos-mosaic-post-limit-rows-3" id="list-videos-mosaic-post-limit-rows-3" value="" disabled />
                                        <div id="list-videos-mosaic-post-limit-rows-3-slider"></div>
                                    </div>
                                    <div class="ts-mosaic-post-limit-squares">
                                        <input type="text" name="list-videos-mosaic-post-limit-rows-squares" id="list-videos-mosaic-post-limit-rows-squares" value="" disabled />
                                        <div id="list-videos-mosaic-post-limit-rows-squares-slider"></div>
                                    </div>

                                    <div class="ts-option-description">
                                        <?php _e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-mosaic-scroll"><?php _e( 'Add/remove scroll', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-videos-mosaic-scroll" id="list-videos-mosaic-scroll">
                                        <option value="y"><?php _e( 'With scroll', 'touchsize' ) ?></option>
                                        <option value="n"><?php _e( 'Without scroll', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Add/remove scroll', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-mosaic-effects"><?php _e( 'Add effects to scroll', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-videos-mosaic-effects" id="list-videos-mosaic-effects">
                                        <option value="default"><?php _e( 'Default', 'touchsize' ) ?></option>
                                        <option value="fade"><?php _e( 'Fade in effect', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Change number of rows', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-mosaic-gutter"><?php _e( 'Add or Remove gutter between posts', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-videos-mosaic-gutter" id="list-videos-mosaic-gutter">
                                        <option value="y"><?php _e( 'With gutter between posts', 'touchsize' ) ?></option>
                                        <option value="n"><?php _e( 'No gutter', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Add or Remove gutter between posts', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-mosaic-order-by"><?php _e( 'Order by', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-videos-mosaic-order-by" id="list-videos-mosaic-order-by">
                                        <option value="date"><?php _e( 'Date', 'touchsize' ) ?></option>
                                        <option value="comments"><?php _e( 'Comments', 'touchsize' ) ?></option>
                                        <option value="views"><?php _e( 'Views', 'touchsize' ) ?></option>
                                        <option value="likes"><?php _e( 'Likes', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="list-videos-mosaic-direction"><?php _e( 'Order direction', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="list-videos-mosaic-order-direction" id="list-videos-mosaic-order-direction">
                                        <option value="asc"><?php _e( 'ASC', 'touchsize' ) ?></option>
                                        <option value="desc" selected="selected"><?php _e( 'DESC', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                
                </div>
            </div>

            <div class="user builder-element hidden">
                <h3 class="element-title"><?php _e('User element', 'touchsize'); ?></h3>
                <p><?php _e( 'You can add login form to page', 'touchsize' ); ?></p>
            </div>

            <div class="cart builder-element hidden">
                <h3 class="element-title"><?php _e('Shopping cart', 'touchsize'); ?></h3>
                <p><?php _e( 'Change the position of the shopping cart', 'touchsize', 'touchsize'); ?></p>
                <table cellpadding="10">
                    <tr>
                        <td width="70px">
                            <?php _e('Cart align', 'touchsize'); ?>
                        </td>
                        <td>
                            <select name="cart-align" id="cart-align">
                                <option value="left"><?php _e('Left', 'touchsize'); ?></option>
                                <option value="right"><?php _e('Right', 'touchsize'); ?></option>
                                <option value="center"><?php _e('Center', 'touchsize'); ?></option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="breadcrumbs builder-element hidden">
                <h3 class="element-title"><?php _e('Breadcrumbs element', 'touchsize'); ?></h3>
                <p><?php _e( 'You can add breadcrumbs form to page', 'touchsize' ) ?></p>
            </div>

        <!-- Latest custom post -->
           <div class="latest-custom-posts builder-element hidden">
                <h3 class="element-title"><?php _e('Latest custom post', 'touchsize'); ?></h3>
                <p><?php _e( 'You can add latest custom post form to page', 'touchsize' ) ?></p>
                <table cellpadding="10">
                    <tr>
                        <td>
                            <label for="latest-custom-posts-admin-label"><?php _e('Admin label','touchsize');?>:</label>
                        </td>
                        <td>
                           <input type="text" id="latest-custom-posts-admin-label" name="latest-custom-posts-admin-label" />
                        </td>
                    </tr>
                    <tr>
                        <td width="70px">
                            <label for="latest-custom-posts-type"><?php _e( 'Select post type', 'touchsize' ); ?>:</label>
                        </td>
                        <td>
                            <?php 
                                $args=array(
                                  'public'   => true,
                                  '_builtin' => false
                                );
                                $post_types = get_post_types($args, 'objects', 'or');
                                
                                $post_types_default = array('post', 'page', 'video', 'ts_teams', 'ts_slider', 'portfolio', 'ts_pricing_table', 'attachment', 'product', 'product_variation', 'shop_order', 'shop_order_refund', 'shop_coupon', 'shop_webhook');
                                $no_custom = false;
                                $registred_post_type = array();
                               
                                if( isset($post_types['post']) && !empty($post_types) ) : ?>
                                    <select data-placeholder="<?php _e('Select your custom post type', 'touchsize'); ?>" class="ts-custom-select-input" multiple name="latest-custom-posts-type" id="latest-custom-posts-type">
                                        <?php foreach($post_types as $register_name=>$post_type) : 
                                            if( !in_array($register_name, $post_types_default) ) : ?>
                                                <?php if( !in_array($register_name, $registred_post_type) ) : ?>
                                                    <?php if( $no_custom === false ) echo '<option value="0">' . __('All', 'touchsize') . '</option>'; $no_custom = true; 
                                                       $registred_post_type[] = $register_name; 
                                                    ?>
                                                <?php endif; ?>
                                                <option value="<?php echo $register_name; ?>"><?php echo $post_type->labels->name; ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                        <?php if( $no_custom === false ) echo '<option value="no-custom-posts">' . __('No new custom posts', 'touchsize') . '</option>'; ?>
                                    </select>  
                                <?php endif; ?>
                            <div class="ts-option-description">
                                <?php _e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'touchsize'); ?>
                            </div>
                        </td>
                    </tr>
                    <?php   if( is_array($registred_post_type) && count($registred_post_type) > 0 ){
                                $exclude_taxonomies = array('post_tag', 'post_format');
                                foreach($registred_post_type as $name_post_type){
                                    $taxonomies = get_object_taxonomies($name_post_type);
                                    foreach($taxonomies as $taxonomy){
                                        if( !in_array($taxonomy, $exclude_taxonomies) ){
                                            $categories = get_categories(array( 'hide_empty' => 0, 'taxonomy' => $taxonomy ));
                                            echo '<tr id="ts-block-category-' . $name_post_type . '">
                                                    <td>' . __('Select category by custom post type ', 'touchsize') . $name_post_type . '</td>
                                                        <td>';
                                            if( isset($categories) && is_array($categories) && !empty($categories) ){
                                                
                                                echo '<select data-placeholder="' . __('Select your category', 'touchsize') . '" class="ts-custom-select-input" name="latest-custom-category-' . $name_post_type . '" multiple id="latest-custom-posts-category-' . $name_post_type . '">';
                                                $i = 0;
                                                    foreach($categories as $category){
                                                        if( $i == 0 ) echo '<option value="0">' . __('All', 'touchsize') . '</option>';
                                                        echo '<option value="' . $category->term_id . '">' . $category->name . '</option>';
                                                        $i++;
                                                    }
                                            }else{
                                                echo '<select data-placeholder="' . __('Select your category', 'touchsize') . '" class="ts-custom-select-input" name="latest-custom-posts-type-no-categories" multiple id="latest-custom-posts-type-no-categories">
                                                       <option value="no-custom-posts">' . __('No categories', 'touchsize') . '</option> 
                                                    </select>';
                                            }
                                            echo '</td></tr>';
                                        }
                                    } 
                                }
                            }   
                    ?>
                    <tr>
                        <td>
                            <label><?php _e( 'Show only featured', 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <select name="latest-custom-posts-featured" id="latest-custom-posts-featured">
                                <option value="y"><?php _e( 'Yes', 'touchsize' ) ?></option>
                                <option value="n" selected="selected"><?php _e( 'No', 'touchsize' ) ?></option>
                            </select>

                            <div class="ts-option-description">
                                <?php _e('You can display only featured posts', 'touchsize'); ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="latest-custom-posts-exclude"><?php _e( "Exclude post ID's", 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <input type="text" value="" name="latest-custom-posts-exclude" id="latest-custom-posts-exclude" size="4"/>
                            <div class="ts-option-description">
                                <?php _e('Insert the IDs of the posts you want to exclude from showing.', 'touchsize'); ?> (ex: <b>100,101,102,104</b>)
                            </div>
                        </td>
                    </tr>
                     <tr class="latest-custom-posts-exclude">
                        <td>
                            <label for="latest-custom-posts-exclude-first"><?php _e( "Exclude number of first posts", 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <input type="text" value="" name="latest-custom-posts-exclude-first" id="latest-custom-posts-exclude-first" size="4"/>
                            <div class="ts-option-description">
                                <?php _e('Insert the number of the posts you want to exclude from showing.', 'touchsize'); ?> 
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="latest-custom-posts-display-mode"><?php _e( 'How to display', 'touchsize' ) ?>:</label>
                        </td>
                        <td>
                            <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#latest-custom-posts-display-mode" id="latest-custom-posts-display-mode-selector">
                               <li><img class="image-radio-input clickable-element" data-option="grid" src="<?php echo get_template_directory_uri().'/images/options/grid_view.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="list" src="<?php echo get_template_directory_uri().'/images/options/list_view.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="thumbnails" src="<?php echo get_template_directory_uri().'/images/options/thumb_view.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="big-post" src="<?php echo get_template_directory_uri().'/images/options/big_posts_view.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="super-post" src="<?php echo get_template_directory_uri().'/images/options/super_post_view.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="timeline" src="<?php echo get_template_directory_uri().'/images/options/timeline_view.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="mosaic" src="<?php echo get_template_directory_uri().'/images/options/mosaic_view.png'; ?>"></li>
                            </ul>
                            <select class="select_2" class="hidden" name="latest-custom-posts-display-mode" id="latest-custom-posts-display-mode">
                                <option value="grid"><?php _e( 'Grid', 'touchsize' ) ?></option>
                                <option value="list"><?php _e( 'List', 'touchsize' ) ?></option>
                                <option value="thumbnails"><?php _e( 'Thumbnails', 'touchsize' ) ?></option>
                                <option value="big-post"><?php _e( 'Big post', 'touchsize' ) ?></option>
                                <option value="super-post"><?php _e( 'Super Post', 'touchsize' ) ?></option>
                                <option value="timeline"><?php _e( 'Timeline Post', 'touchsize' ) ?></option>
                                <option value="mosaic"><?php _e( 'mosaic Post', 'touchsize' ) ?></option>
                            </select>
                            <div class="ts-option-description">
                                <?php _e('Choose your article view type. Depending on what type of article showcase layout you select you will get different options. You can read more about view types in our documentation files: ', 'touchsize'); echo $touchsize_com; ?>
                            </div>
                        </td>
                    </tr>
                </table>

                <div id="latest-custom-posts-display-mode-options">
                    <!-- Grid options -->
                    <div class="latest-custom-posts-grid hidden">
                        <table cellpadding="10">
                            <tr>
                                <td>
                                    <label><?php _e( 'Behavior', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#latest-custom-posts-grid-behavior" id="latest-custom-posts-grid-behavior-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="none" src="<?php echo get_template_directory_uri().'/images/options/behavior_none.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="carousel" src="<?php echo get_template_directory_uri().'/images/options/behavior_carousel.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="masonry" src="<?php echo get_template_directory_uri().'/images/options/behavior_masonry.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="scroll" src="<?php echo get_template_directory_uri().'/images/options/behavior_scroll.png'; ?>"></li>
                                    </ul>
                                    <select class="hidden" name="latest-custom-posts-grid-behavior" id="latest-custom-posts-grid-behavior">
                                        <option value="none"><?php _e( 'Normal', 'touchsize' ) ?></option>
                                        <option value="carousel"><?php _e( 'Carousel', 'touchsize' ) ?></option>
                                        <option value="masonry"><?php _e( 'Masonry', 'touchsize' ) ?></option>
                                        <option value="scroll"><?php _e( 'Scroll', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Choose your posts behavior - you can have them just shown, or you can activate the carousel. If carousel is selected your articles will show up in a line with arrows for navigation. If masonry bahevior is selected - your articles will be arranged in to fit it. Be aware that activating the masonry option the crop settings for image sizes tab will be overwritten.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-grid-title"><?php _e( 'Title position', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#latest-custom-posts-grid-title" id="latest-custom-posts-grid-title-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="title-above-image" src="<?php echo get_template_directory_uri().'/images/options/grid_view_title_image.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="title-above-excerpt" src="<?php echo get_template_directory_uri().'/images/options/grid_view_title_excerpt.png'; ?>"></li>
                                    </ul>
                                    <select class="hidden" name="latest-custom-posts-grid-title" id="latest-custom-posts-grid-title">
                                        <option value="title-above-image"><?php _e( 'Above image', 'touchsize' ) ?></option>
                                        <option value="title-above-excerpt" selected="selected"><?php _e( 'Above excerpt', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Select your title position for you grid posts. You can either have it above the image of above the excerpt. Note that sometimes title may change the position of the meta (date, categories, author) as well.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-grid-show-meta"><?php _e( 'Show meta', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="radio" name="latest-custom-posts-grid-show-meta" id="latest-custom-posts-grid-show-meta-y" value="y"  checked = "checked"  /> 
                                    <label for="latest-custom-posts-grid-show-meta-y"><?php _e( 'Yes', 'touchsize' ) ?></label>
                                    <input type="radio" name="latest-custom-posts-grid-show-meta" id="latest-custom-posts-grid-show-meta-n" value="n" />
                                    <label for="latest-custom-posts-grid-show-meta-n"><?php _e( 'No', 'touchsize' ) ?></label>
                                    <div class="ts-option-description">
                                        <?php _e('You can choose to show or to hide your meta details for your articles. Meta values include date, author, categories and other article details.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-grid-el-per-row"><?php _e( 'Elements per row', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#latest-custom-posts-grid-el-per-row" id="latest-custom-posts-grid-el-per-row-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="1" src="<?php echo get_template_directory_uri().'/images/options/per_row_1.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="2" src="<?php echo get_template_directory_uri().'/images/options/per_row_2.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="3" src="<?php echo get_template_directory_uri().'/images/options/per_row_3.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="4" src="<?php echo get_template_directory_uri().'/images/options/per_row_4.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="6" src="<?php echo get_template_directory_uri().'/images/options/per_row_6.png'; ?>"></li>
                                    </ul>
                                    <select class="hidden" name="latest-custom-posts-grid-el-per-row" id="latest-custom-posts-grid-el-per-row">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3" selected="selected">3</option>
                                        <option value="4">4</option>
                                        <option value="6">6</option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Your articles will be shown columns. Choose how mane columns do you want to use per line. Note that for mobile devices you will get only ONE element per row for better usability.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr class="latest-custom-posts-grid-nr-of-posts">
                                <td>
                                    <label for="latest-custom-posts-grid-nr-of-posts"><?php _e( 'How many posts to extract', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="text" value="" name="latest-custom-posts-grid-nr-of-posts" id="latest-custom-posts-grid-nr-of-posts" size="4"/>
                                    <div class="ts-option-description">
                                        <?php _e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-grid-related"><?php _e( 'Show related posts', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-grid-related" id="latest-custom-posts-grid-related">
                                        <option value="y"><?php _e( 'Yes', 'touchsize' ); ?></option>
                                        <option selected="selected" value="n"><?php _e( 'No', 'touchsize' ); ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('You can set each of your big posts to show related articles below. A list with other articles will appear below.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-grid-order-by"><?php _e( 'Order by', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-grid-order-by" id="latest-custom-posts-grid-order-by">
                                        <option value="date"><?php _e( 'Date', 'touchsize' ) ?></option>
                                        <option value="comments"><?php _e( 'Comments', 'touchsize' ) ?></option>
                                        <option value="views"><?php _e( 'Views', 'touchsize' ) ?></option>
                                        <option value="likes"><?php _e( 'Likes', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-grid-order-direction"><?php _e( 'Order direction', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-grid-order-direction" id="latest-custom-posts-grid-order-direction">
                                        <option value="asc"><?php _e( 'ASC', 'touchsize' ) ?></option>
                                        <option selected="selected" value="desc" selected="selected"><?php _e( 'DESC', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-grid-special-effects"><?php _e( 'Special effects', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-grid-special-effects" id="latest-custom-posts-grid-special-effects">
                                        <option value="none"><?php _e('No effect', 'touchsize') ?></option>
                                        <option value="opacited"><?php _e('Fade in', 'touchsize') ?></option>
                                        <option value="rotate-in"><?php _e('Rotate in', 'touchsize') ?></option>
                                        <option value="3dflip"><?php _e('3d flip', 'touchsize') ?></option>
                                        <option value="scaler"><?php _e('Scale', 'touchsize') ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr class="latest-custom-posts-grid-pagination">
                                <td>
                                    <label for="latest-custom-posts-grid-pagination"><?php _e( 'Enable pagination', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-grid-pagination" id="latest-custom-posts-grid-pagination">
                                        <option selected="selected" value="n"><?php _e('None', 'touchsize') ?></option>
                                        <option value="y"><?php _e('Enable pagination', 'touchsize') ?></option>
                                        <option value="load-more"><?php _e('Load more', 'touchsize') ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('You can add pagination.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- List options -->
                    <div class="latest-custom-posts-list hidden">

                        <table cellpadding="10">
                           <!--  <tr>
                                <td>
                                    <label for="latest-custom-posts-list-title"><?php _e( 'Title:', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-list-title" id="latest-custom-posts-list-title">
                                        <option value="title-above-image"><?php _e( 'Above image', 'touchsize' ) ?></option>
                                        <option value="title-above-excerpt"><?php _e( 'Above excerpt', 'touchsize' ) ?></option>
                                    </select>
                                </td>
                            </tr> -->
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-list-show-meta"><?php _e( 'Show meta', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="radio" name="latest-custom-posts-list-show-meta" id="latest-custom-posts-list-show-meta-y" value="y"  checked = "checked"  /> 
                                    <label for="latest-custom-posts-list-show-meta-y"><?php _e( 'Yes', 'touchsize' ) ?></label>
                                    <input type="radio" name="latest-custom-posts-list-show-meta" id="latest-custom-posts-list-show-meta-n" value="n" />
                                    <label for="latest-custom-posts-list-show-meta-n"><?php _e( 'No', 'touchsize' ) ?></label>
                                    <div class="ts-option-description">
                                        <?php _e('You can choose to show or to hide your meta details for your articles. Meta values include date, author, categories and other article details.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr class="latest-custom-posts-list-nr-of-posts">
                                <td>
                                    <label for="latest-custom-posts-list-nr-of-posts"><?php _e( 'How many posts to extract', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="text" value="" name="latest-custom-posts-list-nr-of-posts" id="latest-custom-posts-list-nr-of-posts" size="4"/>
                                    <div class="ts-option-description">
                                        <?php _e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php _e( 'Content split', 'touchsize' ) ?>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#latest-custom-posts-list-image-split" id="latest-custom-posts-list-image-split-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="1-3" src="<?php echo get_template_directory_uri().'/images/options/list_view_13.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="1-2" src="<?php echo get_template_directory_uri().'/images/options/list_view_12.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="3-4" src="<?php echo get_template_directory_uri().'/images/options/list_view_34.png'; ?>"></li>
                                    </ul>
                                    <select class="hidden" name="latest-custom-posts-list-image-split" id="latest-custom-posts-list-image-split">
                                        <option value="1-3">1/3</option>
                                        <option value="1-2">1/2</option>
                                        <option value="3-4">3/4</option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Choose your title/content split proportions. You can have them either 1/3, 1/2, 3/4 for your title and 2/3,1/2, 1/4 accordingly. Depending on the text and titles you use, select your split type.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-list-order-by"><?php _e( 'Order by', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-list-order-by" id="latest-custom-posts-list-order-by">
                                        <option value="date"><?php _e( 'Date', 'touchsize' ) ?></option>
                                        <option value="comments"><?php _e( 'Comments', 'touchsize' ) ?></option>
                                        <option value="views"><?php _e( 'Views', 'touchsize' ) ?></option>
                                        <option value="likes"><?php _e( 'Likes', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-list-order-direction"><?php _e( 'Order direction', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-list-order-direction" id="latest-custom-posts-list-order-direction">
                                        <option value="asc"><?php _e( 'ASC', 'touchsize' ) ?></option>
                                        <option value="desc" selected="selected"><?php _e( 'DESC', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-list-special-effects"><?php _e( 'Special effects:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-list-special-effects" id="latest-custom-posts-list-special-effects">
                                        <option value="none"><?php _e('No effect', 'touchsize') ?></option>
                                        <option value="opacited"><?php _e('Fade in', 'touchsize') ?></option>
                                        <option value="rotate-in"><?php _e('Rotate in', 'touchsize') ?></option>
                                        <option value="3dflip"><?php _e('3d flip', 'touchsize') ?></option>
                                        <option value="scaler"><?php _e('Scale', 'touchsize') ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-list-pagination"><?php _e( 'Enable pagination', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-list-pagination" id="latest-custom-posts-list-pagination">
                                        <option selected="selected" value="n"><?php _e('None', 'touchsize') ?></option>
                                        <option value="y"><?php _e('Enable pagination', 'touchsize') ?></option>
                                        <option value="load-more"><?php _e('Load more', 'touchsize') ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('You can add pagination.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Thumbnail options -->
                    <div class="latest-custom-posts-thumbnails hidden">
                        <table cellpadding="10">
                            <tr>
                                <td>
                                    <label><?php _e( 'Behavior', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#latest-custom-posts-thumbnail-behavior" id="latest-custom-posts-thumbnail-behavior-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="none" src="<?php echo get_template_directory_uri().'/images/options/behavior_none.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="carousel" src="<?php echo get_template_directory_uri().'/images/options/behavior_carousel.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="masonry" src="<?php echo get_template_directory_uri().'/images/options/behavior_masonry.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="scroll" src="<?php echo get_template_directory_uri().'/images/options/behavior_scroll.png'; ?>"></li>
                                    </ul>
                                    <select name="latest-custom-posts-thumbnail-behavior" id="latest-custom-posts-thumbnail-behavior">
                                        <option value="none"><?php _e( 'Normal', 'touchsize' ) ?></option>
                                        <option value="carousel"><?php _e( 'Carousel', 'touchsize' ) ?></option>
                                        <option value="masonry"><?php _e( 'Masonry', 'touchsize' ) ?></option>
                                        <option value="scroll"><?php _e( 'Scroll', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Choose your posts behavior - you can have them just shown, or you can activate the carousel. If carousel is selected your articles will show up in a line with arrows for navigation. If masonry bahevior is selected - your articles will be arranged in to fit it. Be aware that activating the masonry option the crop settings for image sizes tab will be overwritten.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-thumbnail-posts-per-row"><?php _e( 'Number of posts per row', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#latest-custom-posts-thumbnail-posts-per-row" id="latest-custom-posts-thumbnail-posts-per-row-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="1" src="<?php echo get_template_directory_uri().'/images/options/per_row_1.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="2" src="<?php echo get_template_directory_uri().'/images/options/per_row_2.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="3" src="<?php echo get_template_directory_uri().'/images/options/per_row_3.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="4" src="<?php echo get_template_directory_uri().'/images/options/per_row_4.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="6" src="<?php echo get_template_directory_uri().'/images/options/per_row_6.png'; ?>"></li>
                                    </ul>
                                    <select class="hidden" name="latest-custom-posts-thumbnail-posts-per-row" id="latest-custom-posts-thumbnail-posts-per-row">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3" selected="selected">3</option>
                                        <option value="4">4</option>
                                        <option value="6">6</option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Your articles will be shown columns. Choose how mane columns do you want to use per line. Note that for mobile devices you will get only ONE element per row for better usability.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr class="latest-custom-posts-thumbnails-nr-of-posts">
                                <td>
                                    <label for="latest-custom-posts-thumbnail-limit"><?php _e( 'How many posts to extract', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="text" value="" name="latest-custom-posts-thumbnail-limit"  id="latest-custom-posts-thumbnail-limit" size="4"/>
                                    <div class="ts-option-description">
                                        <?php _e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                             <tr>
                                <td>
                                    <label for="latest-custom-posts-thumbnail-show-meta"><?php _e( 'Show meta:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <input type="radio" name="latest-custom-posts-thumbnail-show-meta" id="latest-custom-posts-thumbnail-show-meta-y" value="y"  checked = "checked"  /> 
                                    <label for="latest-custom-posts-thumbnail-show-meta-y"><?php _e( 'Yes', 'touchsize' ) ?></label>
                                    <input type="radio" name="latest-custom-posts-thumbnail-show-meta" id="latest-custom-posts-thumbnail-show-meta-n" value="n" />
                                    <label for="latest-custom-posts-thumbnail-show-meta-n"><?php _e( 'No', 'touchsize' ) ?></label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-thumbnail-order-by"><?php _e( 'Order by', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-thumbnail-order-by" id="latest-custom-posts-thumbnail-order-by">
                                        <option value="date"><?php _e( 'Date', 'touchsize' ) ?></option>
                                        <option value="comments"><?php _e( 'Comments', 'touchsize' ) ?></option>
                                        <option value="views"><?php _e( 'Views', 'touchsize' ) ?></option>
                                        <option value="likes"><?php _e( 'Likes', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-thumbnail-order-direction"><?php _e( 'Order direction', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-thumbnail-order-direction" id="latest-custom-posts-thumbnails-order-direction">
                                        <option value="asc"><?php _e( 'ASC', 'touchsize' ) ?></option>
                                        <option value="desc" selected="selected"><?php _e( 'DESC', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-thumbnail-special-effects"><?php _e( 'Special effects:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-thumbnail-special-effects" id="latest-custom-posts-thumbnail-special-effects">
                                        <option value="none"><?php _e('No effect', 'touchsize') ?></option>
                                        <option value="opacited"><?php _e('Fade in', 'touchsize') ?></option>
                                        <option value="rotate-in"><?php _e('Rotate in', 'touchsize') ?></option>
                                        <option value="3dflip"><?php _e('3d flip', 'touchsize') ?></option>
                                        <option value="scaler"><?php _e('Scale' , 'touchsize') ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-thumbnail-gutter"><?php _e( 'Remove gutter(space) between articles:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-thumbnail-gutter" id="latest-custom-posts-thumbnail-gutter">
                                        <option value="n"><?php _e('No', 'touchsize') ?></option>
                                        <option value="y"><?php _e('Yes', 'touchsize') ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Gutter is the space between your articles. You can remove the space and have your articles sticked one to another.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr class="latest-custom-posts-thumbnails-pagination">
                                <td>
                                    <label for="latest-custom-posts-thumbnails-pagination"><?php _e( 'Enable pagination', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-thumbnails-pagination" id="latest-custom-posts-thumbnails-pagination">
                                        <option selected="selected" value="n"><?php _e('None', 'touchsize') ?></option>
                                        <option value="y"><?php _e('Enable pagination', 'touchsize') ?></option>
                                        <option value="load-more"><?php _e('Load more', 'touchsize') ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('You can add pagination.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="latest-custom-posts-big-post hidden">
                        <table cellpadding="10">
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-big-post-title"><?php _e( 'Title position:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#latest-custom-posts-big-post-title" id="latest-custom-posts-big-post-title-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="title-above-image" src="<?php echo get_template_directory_uri().'/images/options/grid_view_title_image.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="title-above-excerpt" src="<?php echo get_template_directory_uri().'/images/options/grid_view_title_excerpt.png'; ?>"></li>
                                    </ul>
                                    <select name="latest-custom-posts-big-post-title" id="latest-custom-posts-big-post-title">
                                        <option value="title-above-image"><?php _e( 'Above image', 'touchsize' ) ?></option>
                                        <option value="title-above-excerpt"><?php _e( 'Above excerpt', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Select your title position for you grid posts. You can either have it above the image of above the excerpt. Note that sometimes title may change the position of the meta (date, categories, author) as well.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-big-post-image-position"><?php _e( 'Image position:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-big-post-image-position" id="latest-custom-posts-big-post-image-position">
                                        <option value="left"><?php _e( 'Left', 'touchsize' ) ?></option>
                                        <option value="right"><?php _e( 'Right', 'touchsize' ) ?></option>
                                        <option value="mosaic"><?php _e( 'Mosaic', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('The way you want to showcase your big posts.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-big-post-show-meta"><?php _e( 'Show meta:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <input type="radio" name="latest-custom-posts-big-post-show-meta" id="latest-custom-posts-big-post-show-meta-y" value="y"  checked = "checked"  /> 
                                    <label for="latest-custom-posts-big-post-show-meta-y"><?php _e( 'Yes', 'touchsize' ) ?></label>
                                    <input type="radio" name="latest-custom-posts-big-post-show-meta" id="latest-custom-posts-big-post-show-meta-n" value="n" />
                                    <label for="latest-custom-posts-big-post-show-meta-n"><?php _e( 'No', 'touchsize' ) ?></label>
                                    <div class="ts-option-description">
                                        <?php _e('You can choose to show or to hide your meta details for your articles. Meta values include date, author, categories and other article details.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr class="latest-custom-posts-big-post-nr-of-posts">
                                <td>
                                    <label for="latest-custom-posts-big-post-nr-of-posts"><?php _e( 'How many posts to extract:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <input type="text" value="" name="latest-custom-posts-big-post-nr-of-posts" id="latest-custom-posts-big-post-nr-of-posts" size="4"/>
                                    <div class="ts-option-description">
                                        <?php _e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php _e( 'Content split', 'touchsize' ) ?>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#latest-custom-posts-big-post-image-split" id="latest-custom-posts-big-post-image-split-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="1-3" src="<?php echo get_template_directory_uri().'/images/options/big_posts_13.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="1-2" src="<?php echo get_template_directory_uri().'/images/options/big_posts_12.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="3-4" src="<?php echo get_template_directory_uri().'/images/options/big_posts_34.png'; ?>"></li>
                                    </ul>
                                    <select class="hidden" name="latest-custom-posts-big-post-image-split" id="latest-custom-posts-big-post-image-split">
                                        <option value="1-3">1/3</option>
                                        <option value="1-2">1/2</option>
                                        <option value="3-4">3/4</option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Choose your image/content split proportions. You can have them either 1/3, 1/2, 3/4 for your title and 2/3,1/2, 1/4 accordingly. Depending on the text and titles you use, select your split type.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-big-post-order-by"><?php _e( 'Order by', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-big-post-order-by" id="latest-custom-posts-big-post-order-by">
                                        <option value="date"><?php _e( 'Date', 'touchsize' ) ?></option>
                                        <option value="comments"><?php _e( 'Comments', 'touchsize' ) ?></option>
                                        <option value="views"><?php _e( 'Views', 'touchsize' ) ?></option>
                                        <option value="likes"><?php _e( 'Likes', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-big-post-order-direction"><?php _e( 'Order direction', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-big-post-order-direction" id="latest-custom-posts-big-post-order-direction">
                                        <option value="asc"><?php _e( 'ASC', 'touchsize' ) ?></option>
                                        <option selected="selected" value="desc"><?php _e( 'DESC', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-big-post-related"><?php _e( 'Show related posts', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-big-post-related" id="latest-custom-posts-big-post-related">
                                        <option value="y"><?php _e( 'Yes', 'touchsize' ); ?></option>
                                        <option selected="selected" value="n"><?php _e( 'No', 'touchsize' ); ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('You can set each of your big posts to show related articles below. A list with other articles will appear below.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-big-post-special-effects"><?php _e( 'Special effects', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-big-post-special-effects" id="latest-custom-posts-big-post-special-effects">
                                        <option value="none"><?php _e('No effect', 'touchsize') ?></option>
                                        <option value="opacited"><?php _e('Fade in', 'touchsize') ?></option>
                                        <option value="rotate-in"><?php _e('Rotate in', 'touchsize') ?></option>
                                        <option value="3dflip"><?php _e('3d flip', 'touchsize') ?></option>
                                        <option value="scaler"><?php _e('Scale', 'touchsize') ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-big-post-pagination"><?php _e( 'Enable pagination', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-big-post-pagination" id="latest-custom-posts-big-post-pagination">
                                        <option selected="selected" value="n"><?php _e('None', 'touchsize') ?></option>
                                        <option value="y"><?php _e('Enable pagination', 'touchsize') ?></option>
                                        <option value="load-more"><?php _e('Load more', 'touchsize') ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('You can add pagination.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="latest-custom-posts-super-post hidden">
                        <table cellpadding="10">
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-super-post-posts-per-row"><?php _e( 'Number of posts per row', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#latest-custom-posts-super-post-posts-per-row" id="latest-custom-posts-super-post-posts-per-row-selector">
                                       <li><img class="image-radio-input clickable-element" data-option="1" src="<?php echo get_template_directory_uri().'/images/options/per_row_1.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="2" src="<?php echo get_template_directory_uri().'/images/options/per_row_2.png'; ?>"></li>
                                       <li><img class="image-radio-input clickable-element" data-option="3" src="<?php echo get_template_directory_uri().'/images/options/per_row_3.png'; ?>"></li>
                                    </ul>
                                    <select class="hidden" name="latest-custom-posts-super-post-posts-per-row" id="latest-custom-posts-super-post-posts-per-row">
                                        <option value="1" selected="selected">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Your articles will be shown columns. Choose how mane columns do you want to use per line. Note that for mobile devices you will get only ONE element per row for better usability.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr class="latest-custom-posts-big-post-nr-of-posts">
                                <td>
                                    <label for="latest-custom-posts-super-post-limit"><?php _e( 'How many posts to extract', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="text" value="" name="latest-custom-posts-super-post-limit"  id="latest-custom-posts-super-post-limit" size="4"/>
                                    <div class="ts-option-description">
                                        <?php _e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-super-post-order-by"><?php _e( 'Order by', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-super-post-order-by" id="latest-custom-posts-super-post-order-by">
                                        <option value="date"><?php _e( 'Date', 'touchsize' ) ?></option>
                                        <option value="comments"><?php _e( 'Comments', 'touchsize' ) ?></option>
                                        <option value="views"><?php _e( 'Views', 'touchsize' ) ?></option>
                                        <option value="likes"><?php _e( 'Likes', 'touchsize' ) ?></option>
                                    </select>
                                     <div class="ts-option-description">
                                        <?php _e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                     <label for="latest-custom-posts-super-post-order-direction"><?php _e( 'Order direction', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-super-post-order-direction" id="latest-custom-posts-super-post-order-direction">
                                        <option value="asc"><?php _e( 'ASC', 'touchsize' ) ?></option>
                                        <option selected="selected" value="desc"><?php _e( 'DESC', 'touchsize' ) ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-super-post-special-effects"><?php _e( 'Special effects:', 'touchsize' ) ?></label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-super-post-special-effects" id="latest-custom-posts-super-post-special-effects">
                                        <option value="none"><?php _e('No effect', 'touchsize') ?></option>
                                        <option value="opacited"><?php _e('Fade in', 'touchsize') ?></option>
                                        <option value="rotate-in"><?php _e('Rotate in', 'touchsize') ?></option>
                                        <option value="3dflip"><?php _e('3d flip', 'touchsize') ?></option>
                                        <option value="scaler"><?php _e('Scale', 'touchsize') ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-super-post-pagination"><?php _e( 'Enable pagination', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-super-post-pagination" id="latest-custom-posts-super-post-pagination">
                                        <option selected="selected" value="n"><?php _e('None', 'touchsize') ?></option>
                                        <option value="y"><?php _e('Enable pagination', 'touchsize') ?></option>
                                        <option value="load-more"><?php _e('Load more', 'touchsize') ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('You can add pagination.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!-- Timeline options -->
                    <div class="latest-custom-posts-timeline hidden">

                        <table cellpadding="10">
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-timeline-show-meta"><?php _e( 'Show meta', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="radio" name="latest-custom-posts-timeline-show-meta" id="latest-custom-posts-timeline-show-meta-y" value="y"  checked = "checked"  /> 
                                    <label for="latest-custom-posts-timeline-show-meta-y"><?php _e( 'Yes', 'touchsize' ) ?></label>
                                    <input type="radio" name="latest-custom-posts-timeline-show-meta" id="latest-custom-posts-timeline-show-meta-n" value="n" />
                                    <label for="latest-custom-posts-timeline-show-meta-n"><?php _e( 'No', 'touchsize' ) ?></label>
                                    <div class="ts-option-description">
                                        <?php _e('You can choose to show or to hide your meta details for your articles. Meta values include date, author, categories and other article details.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-timeline-image"><?php _e( 'Show image', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-timeline-image" id="latest-custom-posts-timeline-image">
                                        <option value="y"><?php _e( 'Yes', 'touchsize' ) ?></option>
                                        <option value="n"><?php _e( 'No', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Display image', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr class="latest-custom-posts-timeline-nr-of-posts">
                                <td>
                                    <label for="latest-custom-posts-timeline-post-limit"><?php _e( 'How many posts to extract', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <input type="text" value="" name="latest-custom-posts-timeline-post-limit" id="latest-custom-posts-timeline-post-limit" size="4"/>
                                    <div class="ts-option-description">
                                        <?php _e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-timeline-order-by"><?php _e( 'Order by', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-timeline-order-by" id="latest-custom-posts-timeline-order-by">
                                        <option value="date"><?php _e( 'Date', 'touchsize' ) ?></option>
                                        <option value="comments"><?php _e( 'Comments', 'touchsize' ) ?></option>
                                        <option value="views"><?php _e( 'Views', 'touchsize' ) ?></option>
                                        <option value="likes"><?php _e( 'Likes', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-timeline-order-direction"><?php _e( 'Order direction', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-timeline-order-direction" id="latest-custom-posts-timeline-order-direction">
                                        <option value="asc"><?php _e( 'ASC', 'touchsize' ) ?></option>
                                        <option value="desc" selected="selected"><?php _e( 'DESC', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-timeline-pagination"><?php _e( 'Enable pagination', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-timeline-pagination" id="latest-custom-posts-timeline-pagination">
                                        <option selected="selected" value="n"><?php _e('None', 'touchsize') ?></option>
                                        <option value="y"><?php _e('Enable pagination', 'touchsize') ?></option>
                                        <option value="load-more"><?php _e('Load more', 'touchsize') ?></option>
                                    </select>
                                    <div class="ts-option-description">
                                        <?php _e('You can add pagination.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!-- mosaic options -->
                    <div class="latest-custom-posts-mosaic hidden">

                        <table cellpadding="10">
                            <tr class="latest-custom-posts-mosaic-layout">
                                <td>
                                    <label for="latest-custom-posts-mosaic-layout"><?php _e( 'Choose how to show the posts', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-mosaic-layout" id="latest-custom-posts-mosaic-layout" class="ts-mosaic-layout">
                                        <option value="rectangles"><?php _e( 'Rectangles', 'touchsize' ) ?></option>
                                        <option value="square"><?php _e( 'Squares', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Choose how to show the posts', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr class="latest-custom-posts-mosaic-rows">
                                <td>
                                    <label for="latest-custom-posts-mosaic-rows"><?php _e( 'Change number of rows', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-mosaic-rows" id="latest-custom-posts-mosaic-rows" class="ts-mosaic-rows">
                                        <option value="2"><?php _e( '2', 'touchsize' ) ?></option>
                                        <option value="3"><?php _e( '3', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Change number of rows', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr class="latest-custom-posts-mosaic-nr-of-posts">
                                <td>
                                    <label for="latest-custom-posts-mosaic-post-limit"><?php _e( 'How many posts to extract', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <div class="ts-mosaic-post-limit-rows-2">
                                        <input class="ts-input-slider" type="text" name="latest-custom-posts-mosaic-post-limit-rows-2" id="latest-custom-posts-mosaic-post-limit-rows-2" value="6" disabled />
                                        <div id="latest-custom-posts-mosaic-post-limit-rows-2-slider"></div>
                                    </div>
                                    <div class="ts-mosaic-post-limit-rows-3">
                                        <input type="text" name="latest-custom-posts-mosaic-post-limit-rows-3" id="latest-custom-posts-mosaic-post-limit-rows-3" value="" disabled />
                                        <div id="latest-custom-posts-mosaic-post-limit-rows-3-slider"></div>
                                    </div>
                                    <div class="ts-mosaic-post-limit-squares">
                                        <input type="text" name="latest-custom-posts-mosaic-post-limit-rows-squares" id="latest-custom-posts-mosaic-post-limit-rows-squares" value="" disabled />
                                        <div id="latest-custom-posts-mosaic-post-limit-rows-squares-slider"></div>
                                    </div>

                                    <div class="ts-option-description">
                                        <?php _e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-mosaic-scroll"><?php _e( 'Add/remove scroll', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-mosaic-scroll" id="latest-custom-posts-mosaic-scroll">
                                        <option value="y"><?php _e( 'With scroll', 'touchsize' ) ?></option>
                                        <option value="n"><?php _e( 'Without scroll', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Add/remove scroll', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-mosaic-effects"><?php _e( 'Add effects to scroll', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-mosaic-effects" id="latest-custom-posts-mosaic-effects">
                                        <option value="default"><?php _e( 'Default', 'touchsize' ) ?></option>
                                        <option value="fade"><?php _e( 'Fade in effect', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Change number of rows', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-mosaic-gutter"><?php _e( 'Add or Remove gutter between posts', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-mosaic-gutter" id="latest-custom-posts-mosaic-gutter">
                                        <option value="y"><?php _e( 'With gutter between posts', 'touchsize' ) ?></option>
                                        <option value="n"><?php _e( 'No gutter', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Add or Remove gutter between posts', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-mosaic-order-by"><?php _e( 'Order by', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-mosaic-order-by" id="latest-custom-posts-mosaic-order-by">
                                        <option value="date"><?php _e( 'Date', 'touchsize' ) ?></option>
                                        <option value="comments"><?php _e( 'Comments', 'touchsize' ) ?></option>
                                        <option value="views"><?php _e( 'Views', 'touchsize' ) ?></option>
                                        <option value="likes"><?php _e( 'Likes', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="latest-custom-posts-mosaic-direction"><?php _e( 'Order direction', 'touchsize' ) ?>:</label>
                                </td>
                                <td>
                                    <select name="latest-custom-posts-mosaic-order-direction" id="latest-custom-posts-mosaic-order-direction">
                                        <option value="asc"><?php _e( 'ASC', 'touchsize' ) ?></option>
                                        <option value="desc" selected="selected"><?php _e( 'DESC', 'touchsize' ) ?></option>
                                    </select>

                                    <div class="ts-option-description">
                                        <?php _e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'touchsize'); ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                
                </div>
            </div>
   
            <div class="timeline builder-element hidden">
                <h3 class="element-title"><?php _e('Timeline features', 'touchsize'); ?></h3>
                <p><?php _e( 'You can add timeline features form to page', 'touchsize' ) ?></p>
                <table>
                     <tr>
                        <td>
                            <?php _e('Admin label:', 'touchsize'); ?>
                        </td>
                        <td>
                           <input type="text" id="timeline-admin-label" name="timeline-amin-label" />
                        </td>
                    </tr>
                </table>

                <ul id="timeline_items">
                    
                </ul>
                    
                <input type="hidden" id="timeline_content" value="" />
                <input type="button" class="button ts-multiple-add-button" data-element-name="timeline" id="timeline_add_item" value=" <?php _e('Add New Timeline', 'touchsize'); ?>"/>
                <?php
                    echo '<script id="timeline_items_template" type="text/template">
                            <li id="list-item-id-{{item-id}}" class="timeline-item ts-multiple-add-list-element">
                                <div class="sortable-meta-element">
                                    <span class="tab-arrow icon-down"></span> <span class="timeline-item-tab ts-multiple-item-tab">' . __('Item:', 'touchsize') . ' {{slide-number}}</span>
                                </div>
                                <div class="hidden">
                                    <table>
                                        <tr>
                                            <td>'
                                                . __('Title:', 'touchsize') .
                                            '</td>
                                            <td>
                                               <input data-builder-name="title" type="text" id="timeline-{{item-id}}-title" name="timeline[{{item-id}}][title]" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                ' . __( "Write your text here:", "touchsize" ) . '
                                            </td>
                                            <td>
                                                <textarea data-builder-name="text" name="timeline[{{item-id}}][text]" id="timeline-{{item-id}}-text" cols="51" rows="5"></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>' . __( "Add image", "touchsize" ) . '</td>
                                            <td>
                                                <input type="text" name="timeline-{{item-id}}-image" id="timeline-{{item-id}}-image" value="" data-role="media-url" />
                                                <input type="hidden" id="slide_media_id-{{item-id}}" name="timeline_media_id-{{item-id}}" value=""  data-role="media-id" />
                                                <input type="button" id="uploader_{{item-id}}"  class="button button-primary" value="' . __( "Upload", "touchsize" ) . '" />
                                                <div id="image-preview-{{item-id}}"></div> 
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>'
                                                . __( 'Align image', 'touchsize' ) .
                                            '</td>
                                            <td>
                                                <select data-builder-name="align" name="timeline[{{item-id}}][align]" id="timeline-{{item-id}}-align">
                                                    <option value="left">' . __('Left', 'touchsize') .'</option>
                                                    <option value="right">' . __('Right', 'touchsize' ) . '</option>
                                                </select>
                                                <div class="ts-option-description">'
                                                    . __('Align image', 'touchsize') .
                                                '</div>
                                            </td>
                                        </tr>
                                    </table>
                                    <input type="hidden" data-builder-name="item_id" value="{{item-id}}" name="timeline[{{item-id}}][id]" />
                                    <input type="button" class="button button-primary remove-item" value="' . __('Remove', 'touchsize') . '" />
                                    <a href="#" data-builder-name="timeline" data-element-name="timeline" data-item="timeline-item" data-increment="timeline-items" class="button button-primary ts-multiple-item-duplicate">' . __('Duplicate Item', 'touchsize') . '</a>
                                </div>
                            </li>
                        </script>';
                ?>
            </div>

            <div class="ribbon builder-element hidden">
                <h3 class="element-title"><?php _e('Ribbon banner', 'touchsize'); ?></h3>
                <p><?php _e( 'You can add ribbon banner form to page', 'touchsize' ) ?></p>
                <table cellpadding="10">
                    <tr>
                        <td>
                            <?php _e('Admin label:', 'touchsize'); ?>
                        </td>
                        <td>
                           <input type="text" id="ribbon-admin-label" name="ribbon-amin-label" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e('Title:', 'touchsize'); ?>
                        </td>
                        <td>
                           <input type="text" id="ribbon-title" name="ribbon-title" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e('Text:', 'touchsize'); ?>
                        </td>
                        <td>
                           <textarea id="ribbon-text" style="width:400px; height: 100px" name="ribbon-text"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e('Text color', 'touchsize') ?>:
                        </td>
                        <td>
                           <input class="colors-section-picker" type="text" id="ribbon-text-color" name="ribbon-text-color" value="#FFFFFF"/>
                           <div class="colors-section-picker-div" id="ts-ribbon-text-color-picker"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e('Background ribbon banner color', 'touchsize') ?>:
                        </td>
                        <td>
                           <input class="colors-section-picker" type="text" id="ribbon-background-color" name="ribbon-background" value="#FFFFFF"/>
                           <div class="colors-section-picker-div" id="ts-ribbon-background-color-picker"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e('Ribbon banner align', 'touchsize'); ?>
                        </td>
                        <td>
                            <select name="ribbon-align" id="ribbon-align">
                                <option value="ribbon-left"><?php _e('Left', 'touchsize'); ?></option>
                                <option value="ribbon-right"><?php _e('Right', 'touchsize'); ?></option>
                                <option value="ribbon-center"><?php _e('Center', 'touchsize'); ?></option>
                            </select>
                        </td>
                    </tr>
                </table>
                <p><?php _e("Choose your icon button from the library below:", 'touchsize'); ?></p>
                <div class="builder-element-icon-toggle">
                    <a href="#" class="red-ui-button builder-element-icon-trigger-btn" data-toggle="#builder-element-ribbon-icon-selector"><?php _e('Show icons','touchsize') ?></a>
                </div>
                <ul id="builder-element-ribbon-icon-selector" data-selector="#builder-element-ribbon-icon" class="builder-icon-list ts-custom-selector">
                    <?php echo $icons_li; ?>
                </ul>
                <select name="builder-element-ribbon-icon" id="builder-element-ribbon-icon" class="hidden">
                    <?php echo $icons_options; ?>
                </select>
                <table cellpadding="10">
                    <tr>
                        <td width="70px">
                            <?php _e('Button align', 'touchsize'); ?>
                        </td>
                        <td>
                            <select name="ribbon-button-align" id="ribbon-button-align">
                                <option value="text-left"><?php _e('Left', 'touchsize'); ?></option>
                                <option value="text-right"><?php _e('Right', 'touchsize'); ?></option>
                                <option selected="selected" value="text-center"><?php _e('Center', 'touchsize'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e('Text button', 'touchsize') ?>
                        </td>
                        <td>
                           <input type="text" id="ribbon-button-text" name="ribbon-button-text" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e('URL button', 'touchsize') ?>:
                        </td>
                        <td>
                           <input type="text" id="ribbon-button-url" name="ribbon-button-url" value=""/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e('Target button', 'touchsize') ?>:
                        </td>
                        <td>
                            <select name="ribbon-button-target" id="ribbon-button-target">
                                <option value="_blank" selected="selected"><?php _e('_blank', 'touchsize'); ?></option>
                                <option value="_self"><?php _e('_self', 'touchsize'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e('Size button', 'touchsize') ?>:
                        </td>
                        <td>
                           <select name="ribbon-button-size" id="ribbon-button-size">
                               <option value="big"><?php _e('Big', 'touchsize') ?></option>
                               <option value="medium" selected="selected"><?php _e('Medium', 'touchsize') ?></option>
                               <option value="small"><?php _e('Small', 'touchsize') ?></option>
                               <option value="xsmall"><?php _e('xSmall', 'touchsize') ?></option>
                           </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e('Choose your mode to display:', 'touchsize') ?>
                        </td>
                        <td>
                           <select name="ribbon-button-mode-dispaly" id="ribbon-button-mode-display">
                               <option value="border-button"><?php _e('Border button', 'touchsize') ?></option>
                               <option value="background-button"><?php _e('Background button', 'touchsize') ?></option>
                           </select>
                        </td>
                    </tr>
                    <tr class="ribbon-button-background-color">
                        <td>
                            <?php _e('Background button color', 'touchsize') ?>:
                        </td>
                        <td>
                           <input class="colors-section-picker" type="text" id="ribbon-button-background-color" name="ribbon-button-background-color" value="#FFFFFF"/>
                           <div class="colors-section-picker-div" id="ts-ribbon-button-background-color-picker"></div>
                        </td>
                    </tr>
                    <tr class="ribbon-button-border-color">
                        <td>
                            <?php _e('Border color button', 'touchsize') ?>:
                        </td>
                        <td>
                           <input class="colors-section-picker" type="text" id="ribbon-button-border-color" name="ribbon-button-border-color" value="#FFFFFF"/>
                           <div class="colors-section-picker-div" id="ts-ribbon-button-border-color-picker"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e('Button text color', 'touchsize') ?>:
                        </td>
                        <td>
                           <input class="colors-section-picker" type="text" id="ribbon-button-text-color" name="ribbon-button-text-color" value="#333333"/>
                           <div class="colors-section-picker-div" id="ts-ribbon-button-text-color-picker"></div>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"><?php _e('Image', 'touchsize'); ?></td>
                        <td>
                            <input type="text" value="" name="ribbon-image-url"  id="ribbon-attachment" style="width:300px" />
                            <input type="button" class="button-primary" id="ribbon-select-image" value="<?php _e('Upload image', 'touchsize'); ?>" />
                            <input type="hidden" value="" class="image_media_id" id="ribbon-media-id" />
                            <div id="ribbon-image-preview"></div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="video-carousel builder-element hidden">
                <h3 class="element-title"><?php _e('Video carousel element', 'touchsize'); ?></h3>
                <table cellpadding="10">
                    <tr>
                        <td>
                            <?php _e('Admin label:', 'touchsize'); ?>
                        </td>
                        <td>
                           <input type="text" id="video-carousel-admin-label" name="video-carousel-admin-label" />
                        </td>
                    </tr>
                    <tr>
                        <td><?php _e('Source','videotouch'); ?></td>
                        <td>
                            <select name="video-carousel-source" id="video-carousel-source">
                                <option value="latest-posts"><?php _e('Latest posts','videotouch'); ?></option>
                                <option value="latest-videos"><?php _e('Latest videos','videotouch'); ?></option>
                                <option value="custom-slides"><?php _e('Custom slides','videotouch'); ?></option>
                                <option value="latest-featured-posts"><?php _e('Latest featured posts','videotouch'); ?></option>
                                <option value="latest-featured-videos"><?php _e('Latest featured videos','videotouch'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr class="video-carousel-video" style="display:none">
                        <td>
                            <p><?php _e('Categories video', 'touchsize') ?></p>
                        </td>
                        <td>
                            <select data-placeholder="<?php _e('Select your category', 'touchsize'); ?>" class="ts-custom-select-input"  name="video-carousel-categories-video" id="video-carousel-categories-video" multiple>
                                <?php $categories = get_categories(array( 'hide_empty' => 0, 'taxonomy' => 'videos_categories' )); ?>
                                <?php if ( isset($categories) && is_array($categories) && $categories !== '' && !empty($categories) ): ?>
                                    <?php $i = 0; foreach ($categories as $index => $category): ?>
                                        <?php if( is_object($category) ) : ?>
                                            <?php if( $i == 0 ) echo '<option value="0">All</option>'; ?>
                                            <option value="<?php if( is_object($category) ) echo $category->term_id; ?>"><?php if( is_object($category) ) echo $category->cat_name; ?></option>
                                        <?php $i++; endif; ?>
                                    <?php endforeach ?>                                    
                                <?php endif ?>
                            </select>
                        </td>
                    </tr>
                    <tr class="video-carousel-posts" style="display:none">
                        <td>
                            <p><?php _e('Categories posts', 'touchsize') ?></p>
                        </td>
                        <td>
                            <select data-placeholder="<?php _e('Select your category', 'touchsize'); ?>" class="ts-custom-select-input"  name="video-carousel-categories-video" id="video-carousel-categories-posts" multiple>
                                <?php 
                                $categories = get_categories(array( 'hide_empty' => 0 )); ?>
                                <?php if ( isset($categories) && is_array($categories) && $categories !== '' ): ?>
                                    <?php $i = 0; foreach ($categories as $index => $category): ?>
                                        <?php if( is_object($category) ) : ?>
                                            <?php if( $i == 0 ) echo '<option value="0">All</option>'; ?>
                                            <option value="<?php echo $category->term_id; ?>"><?php echo $category->cat_name; ?></option>
                                        <?php $i++; endif; ?>
                                    <?php endforeach ?>                                    
                                <?php endif ?>
                            </select>
                        </td>
                    </tr>
                    <tr class="video-carousel-nr-of-posts">
                        <td>
                            <?php _e('How many to extract','slimvideo'); ?>
                        </td>
                        <td>
                           <input type="text" id="video-carousel-nr-of-posts" name="video-carousel-nr-of-posts" />
                        </td>
                    </tr>
                </table>
                <div class="ts-video-carousel-custom">
                    <ul id="video-carousel_items">

                    </ul>
                    <input type="hidden" id="video-carousel_content" value="" />
                    <input type="button" class="button ts-multiple-add-button" data-element-name="video-carousel" id="video-carousel_add_item" value=" <?php _e('Add New Video Carousel', 'touchsize'); ?>" />
                    <?php
                        echo '<script id="video-carousel_items_template" type="text/template">';
                        echo '<li id="list-item-id-{{item-id}}" class="video-carousel_item ts-multiple-add-list-element">
                                <div class="sortable-meta-element"><span class="tab-arrow icon-down"></span> <span class="video-carousel-item-tab ts-multiple-item-tab">' . __('Item:', 'touchsize') . ' {{slide-number}}</span></div>
                                <div class="hidden">
                                    <table>
                                        <tr>
                                            <td>
                                                ' . __('Enter your title here:', 'touchsize') . '
                                            </td>
                                            <td>
                                               <input data-builder-name="title" type="text" id="video-carousel-{{item-id}}-title" name="video-carousel-[{{item-id}}][title]" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                ' . __('Enter your description here:', 'touchsize') . '
                                            </td>
                                            <td>
                                                <textarea data-builder-name="text" id="video-carousel-{{item-id}}-text" name="video-carousel-[{{item-id}}][text] cols="45"></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                ' . __('Enter your video url here:', 'touchsize') . '
                                            </td>
                                            <td>
                                               <input data-builder-name="embed" type="text" id="video-carousel-{{item-id}}-embed" name="video-carousel-[{{item-id}}][embed]" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                ' . __('Enter your title url here:', 'touchsize') . '
                                            </td>
                                            <td>
                                               <input data-builder-name="url" type="text" id="video-carousel-{{item-id}}-url" name="video-carousel-[{{item-id}}][url]" />
                                            </td>
                                        </tr>
                                    </table>
                                   <input type="hidden" data-builder-name="item_id" value="{{item-id}}" name="video-carousel[{{item-id}}][id]" />
                                   <input type="button" class="button button-primary remove-item" value="' . __('Remove', 'touchsize') . '" />
                                   <a href="#" data-item="video-carousel_item" data-increment="video-carousel_items" class="button button-primary ts-multiple-item-duplicate">' . __('Duplicate Item', 'touchsize') . '</a>
                                </div>
                            </li>';
                        echo '</script>';
                    ?>
                </div>
            </div>

            <div class="quote builder-element hidden">
                <h3 class="element-title"><?php _e('Quote element', 'touchsize'); ?></h3>
                <table cellpadding="10">
                    <tr>
                        <td>
                            <?php _e('Admin label:','touchsize'); ?>
                        </td>
                        <td>
                           <input type="text" id="quote-admin-label" name="quote-admin-label" />
                        </td>
                    </tr>
                </table>
                <p><?php _e("Choose your icon from the library below:", 'touchsize'); ?></p>
                <div class="builder-element-icon-toggle">
                    <a href="#" class="red-ui-button builder-element-icon-trigger-btn" data-toggle="#quote-selector"><?php _e('Show icons','touchsize') ?></a>
                </div>    
                <ul id="quote-selector" data-selector="#quote-icon" class="builder-icon-list ts-custom-selector">
                    <?php  echo $icons_li; ?>
                </ul>
                <select name="quote-icon" id="quote-icon" class="hidden">
                    <?php echo $icons_options; ?> 
                </select>
                
                <h3>Icon options</h3>               
                <table>
                    <tr>
                        <td>
                            <?php _e('Text quote', 'touchsize'); ?>
                        </td>
                        <td>
                            <textarea cols="50" rows="5" name="quote-text" id="quote-text"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php _e('Author', 'touchsize'); ?>
                        </td>
                        <td>
                            <input type="text" id="quote-author" value="" name="quote-author" />
                        </td>
                    </tr>
                </table>
            </div> 

            <input type="button" class="button-primary save-element" value="<?php _e( 'Save changes', 'touchsize' ) ?>" id="builder-save"/>

        </div>
        <script src="<?php echo get_template_directory_uri() . '/admin/js/builder-elements.js?ver='.time(); ?>"></script>
<script>
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
    
    function custom_selectors_toggle_run(){
        jQuery('.builder-element-icon-toggle').each(function(){
            var selectedOptionVal = jQuery(this).next().next().find('option:selected').attr('value');
            if( jQuery(this).find('i.' + selectedOptionVal).length == 0 ){
                jQuery(this).find('.builder-element-icon-trigger-btn').append("<i class='" + selectedOptionVal + "'></i>"); //add to button the selected icon
            }
        });
    }

    jQuery(document).ready(function(){
        custom_selectors_toggle_run();
    });

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

    jQuery('.ts-custom-select-input').each(function(){
        var select_placeholder = jQuery(this).attr('data-placeholder');
        jQuery(this).css({'width':'380px'}).select2({
            placeholder : select_placeholder,
            allowClear: true
        });
    });

    jQuery('select.ts-custom-select-input').on('click',function(){

        if(jQuery(this).val() == 0){

            jQuery(this).find('option[value="0"]').remove();
            jQuery(this).find('option').attr("selected","selected");
            jQuery(this).trigger("change");
            jQuery(this).append('<option value="0">All</option>');
           
        }else{
            if( jQuery(this).val() != null && jQuery(this).val().indexOf('0') >= 0){
                jQuery(this).find('option[value="0"]').remove();
                jQuery(this).find('option').attr("selected","selected");
                jQuery(this).trigger("change");
                jQuery(this).append('<option value="0">All</option>');
            }
        }
    });

    //show/hide the inputs "Border color" and "background color" in element listed features depending of option "style"
    function ts_listed_features_style_color(){

        jQuery('#listed-features-color-style').change(function(){

            if( jQuery(this).val() === 'background' ){
                jQuery('.ts-border-color').css({'display':'none'});
                jQuery('.ts-background-color').css({'display':''});
            }else if( jQuery(this).val() === 'border' ){
                jQuery('.ts-border-color').css({'display':''});
                jQuery('.ts-background-color').css({'display':'none'});
            }else{
                jQuery('.ts-border-color').css({'display':'none'});
                jQuery('.ts-background-color').css({'display':'none'});
            }
        });

        if( jQuery('#listed-features-color-style').val() == 'background' ){
            jQuery('.ts-border-color').css({'display':'none'});
            jQuery('.ts-background-color').css({'display':''});
         }else if( jQuery('#listed-features-color-style').val() == 'border' ){
            jQuery('.ts-border-color').css({'display':''});
            jQuery('.ts-background-color').css({'display':'none'});
        }else{
            jQuery('.ts-border-color').css({'display':'none'});
            jQuery('.ts-background-color').css({'display':'none'});
        }
    }
    ts_listed_features_style_color();

    //function for display or hidden the categories in builder element featured area       
    function ts_display_categories(){

        var costum_post = jQuery('#featured-area-custom-post').val();
        
        jQuery('#featured-area-custom-post').change(function(){
        
            if( jQuery(this).val() == 'video' ){
                jQuery('.select2-choices li:not(.select2-search-field)').remove();
                jQuery('.featured-area-posts_video').css('display','none').find('option').removeAttr('selected');
                jQuery('.featured-area-posts').css('display','none').find('option').removeAttr('selected');
                jQuery('.featured-area-video').css('display','');
            }else if( jQuery(this).val() == 'video_post' ){
                jQuery('.select2-choices li:not(.select2-search-field)').remove();
                jQuery('.featured-area-posts').css('display','none').find('option').removeAttr('selected');
                jQuery('.featured-area-video').css('display','none').find('option').removeAttr('selected');
                jQuery('.featured-area-posts_video').css('display','');
            }else if( jQuery(this).val() == 'post' ){
                jQuery('.select2-choices li:not(.select2-search-field)').remove();
                jQuery('.featured-area-posts').css('display','');
                jQuery('.featured-area-video').css('display','none').find('option').removeAttr('selected');
                jQuery('.featured-area-posts_video').css('display','none').find('option').removeAttr('selected');
            }
        });

        if( costum_post == 'video' ){
            jQuery('.featured-area-video').css('display','');
        }else if( costum_post == 'video_post' ){
            jQuery('.featured-area-posts_video').css('display','');
        }else if( costum_post == 'post' ){
            jQuery('.featured-area-posts').css('display','');
        }
    }
    ts_display_categories();

    // show-display option number rows depending of layout option(square/hide number rows - rectangles/show number rows)
    function ts_last_post_mosaic_rows_layout(name_element){

        var value_select = jQuery('#' + name_element + '-mosaic-layout').val();
        if( value_select == 'square' ){
            jQuery('.' + name_element + '-mosaic-rows').css('display', 'none');
        }else{
            jQuery('.' + name_element + '-mosaic-rows').css('display', '');
        }

        jQuery('#' + name_element + '-mosaic-layout').change(function(){
            if( jQuery(this).val() == 'square' ){
                jQuery('.' + name_element + '-mosaic-rows').css('display', 'none');
            }else{
                jQuery('.' + name_element + '-mosaic-rows').css('display', '');
            }
        });
    }
    ts_last_post_mosaic_rows_layout('last-posts');
    ts_last_post_mosaic_rows_layout('list-videos');
    ts_last_post_mosaic_rows_layout('latest-custom-posts');

    function ts_repopulate_element(element_name, items_array){

        list_items = jQuery.parseJSON(jQuery('#' + element_name + '_content').val());

        if (list_items != '') {
            name_blocks_template = jQuery('#' + element_name + '_items_template').html();

            var json_to_array = '';
            
            jQuery(list_items).each(function(index, value){
    
                li_content = '';
                var li_appended = false;

                if ( value != '' ) {

                    var elemId = '';
                    for(var i in value){

                        if( i == 'id' ){
                            var elemId = value[i];
                            if (li_appended == false) {
                                li_content = name_blocks_template.replace(/{{item-id}}/g, elemId);
                                jQuery('#' + element_name + '_items').append(li_content);
                            }
                            li_appended = true;
                        }

                        if( i == 'icon' ){
                            var elemIcon = value[i]; 
                        }

                        if( i == 'title' ){

                            var title = value[i].replace(/--quote--/g, '"');
                            jQuery('#' + element_name + '_items li:last-child').find('.ts-multiple-item-tab').html('Item: ' + title);
                           
                            if( elemIcon ){
                                jQuery('#' + element_name + '-' + elemId + '-icon').find('option[value="' + elemIcon + '"]').attr('selected','selected');
                            }
                        }

                        if( i == 'name' ){
                            var name = value[i];
                            jQuery('#' + element_name + '_items li:last').find('.ts-multiple-item-tab').html('Item: ' + name);
                        }

                        if( i == 'image' ){
                            var img = jQuery("<img>").attr('src', value[i]).attr('style', 'width:400px');
                            jQuery('#image-preview-' + elemId).html(img);

                        }

                        jQuery('#' + element_name + '-' + elemId + '-' + i).val(value[i].replace(/--quote--/g, '"').replace(/<br \/>/g, '\n'));

                        if( elemIcon ){

                            custom_selectors_toggle_run();
                            jQuery('div.' + element_name + '.builder-element .builder-icon-list').each(function(){

                                this_id = '#' + jQuery(this).attr('id') + ' li i';

                                jQuery('div.' + element_name + '.builder-element .builder-icon-list li i').click(function(){
                                    targetselect = jQuery(this).parent().parent().attr('data-selector');
                                    custom_selectors(jQuery(this), targetselect);
                                });

                            });
                        }

                    }

                    ts_upload_files('#uploader_' + elemId, '#slide_media_id-' + elemId, '#' + element_name + '-' + elemId + '-image', 'Insert image', '#image-preview-' + elemId, 'image');

                    if(element_name == 'listed-features'){
                        ts_listed_features_style_color();
                    }
                    
                    ts_contact_form_type_options();

                };
                restartColorPickers();
            });
        };

        var name_block_items = jQuery('#' + element_name + '_items > li').length;

        jQuery('#' + element_name + '_items').sortable();
    }
    ts_repopulate_element('features-block', new Array('id','icon','title','text','url','background','font'));
    ts_repopulate_element('listed-features', new Array('id','icon','title','text','iconcolor','bordercolor','backgroundcolor'));
    ts_repopulate_element('clients', new Array('id','image','title','url'));
    ts_repopulate_element('testimonials', new Array('id','image','name','text','company','url'));
    ts_repopulate_element('tab', new Array('id','title','text'));
    ts_repopulate_element('contact-form', new Array('id','title','type','require','options'));
    ts_repopulate_element('timeline', new Array('id','title','text','align','image'));
    ts_repopulate_element('video-carousel', new Array('id','title','text','url','embed'));

    //show/hide the select 'play in modal' in element 'list videos' depending of option 'display mode'
    function ts_list_videos_play_in_modal(){
        var option_modal = jQuery('.list-videos-modal');
        var display_val = jQuery('#list-videos-display-mode').val();
        
        if( display_val === 'mosaic' || display_val === 'timeline' || display_val === 'super-post' || display_val === 'thumbnails' ){
            jQuery(option_modal).css('display', 'none');
        }else{
           jQuery(option_modal).css('display', ''); 
        }
        jQuery('#list-videos-display-mode').change(function(){

            if( jQuery(this).val() === 'mosaic' || jQuery(this).val() === 'timeline' || jQuery(this).val() === 'super-post' || jQuery(this).val() === 'thumbnails' ){
                jQuery(option_modal).css('display', 'none');
            }else{
                jQuery(option_modal).css('display', '');
            }
        });
      
    }
    ts_list_videos_play_in_modal();

    //show/hide the option 'Background color' end 'Border color' in element 'Button'&'ribbon banner' depending of option 'Choose your mode to display:'
    function ts_buttons_display_mode(element_name){
        var option_display_mode = jQuery('#' + element_name + '-mode-display');
        jQuery(option_display_mode).change(function(){
            if( jQuery(this).val() === 'background-button' ){
                jQuery('.' + element_name + '-border-color').css('display', 'none');
                jQuery('.' + element_name + '-background-color').css('display', '');
            }else{
                jQuery('.' + element_name + '-background-color').css('display', 'none');
                jQuery('.' + element_name + '-border-color').css('display', '');
            }
        });

        if( jQuery(option_display_mode).val() === 'background-button' ){
            jQuery('.' + element_name + '-border-color').css('display', 'none');
            jQuery('.' + element_name + '-background-color').css('display', '');
        }else{
            jQuery('.' + element_name + '-background-color').css('display', 'none');
            jQuery('.' + element_name + '-border-color').css('display', '');
        }
    }
    ts_buttons_display_mode('button');
    ts_buttons_display_mode('ribbon-button');

    ts_contact_form_type_options();

    ts_upload_files('#select_banner_image', '#banner_image_media_id', '#image-banner-url', 'Insert banner', '#banner-image-preview', 'image');
    ts_upload_files('#select_image', '#image_media_id', '#image_url', 'Insert image', '#image_preview', 'image');
    ts_upload_files('#ribbon-select-image', '#ribbon-media-id', '#ribbon-attachment', 'Choose image', '#ribbon-image-preview','image');
    //show select category by custom post type in element latest custom posts
    function ts_latest_custom_post_category(){
        jQuery('#latest-custom-posts-type').change(function(){
            var arrayValues = jQuery(this).val();
            jQuery(this).find('option').each(function(){
                jQuery('#ts-block-category-' + jQuery(this).attr('value')).css('display', 'none');
            });
            for(key in arrayValues){
                jQuery('#ts-block-category-' + arrayValues[key]).css('display', '');
            }
        });

        jQuery('#latest-custom-posts-type option').each(function(){
            jQuery('#ts-block-category-' + jQuery(this).attr('value')).css('display', 'none');
        });

        var arrayValues = jQuery('#latest-custom-posts-type').val();
        for(key in arrayValues){
            jQuery('#ts-block-category-' + arrayValues[key]).css('display', '');
        }
    }
    ts_latest_custom_post_category();


    function ts_mosaic_post_limit(){
        jQuery('.ts-mosaic-layout').change(function(){
            if( jQuery(this).val() == 'rectangles' ){

                jQuery(this).closest('table').find('.ts-mosaic-post-limit-squares').css('display', 'none');

                if( jQuery(this).closest('table').find('.ts-mosaic-rows').val() == '2' ){
                    jQuery(this).closest('table').find('.ts-mosaic-post-limit-rows-2').css('display', '');
                    jQuery(this).closest('table').find('.ts-mosaic-post-limit-rows-3').css('display', 'none');
                }else{
                    jQuery(this).closest('table').find('.ts-mosaic-post-limit-rows-3').css('display', '');
                    jQuery(this).closest('table').find('.ts-mosaic-post-limit-rows-2').css('display', 'none');
                }
            }else{
                jQuery(this).closest('table').find('.ts-mosaic-post-limit-squares').css('display', '');
                jQuery(this).closest('table').find('.ts-mosaic-post-limit-rows-3').css('display', 'none');
                jQuery(this).closest('table').find('.ts-mosaic-post-limit-rows-2').css('display', 'none');
            }
        });
        
        jQuery('.ts-mosaic-layout').each(function(){
            if( jQuery(this).val() == 'rectangles' ){

                jQuery(this).closest('table').find('.ts-mosaic-post-limit-squares').css('display', 'none');

                if( jQuery(this).closest('table').find('.ts-mosaic-rows').val() == '2' ){
                    jQuery(this).closest('table').find('.ts-mosaic-post-limit-rows-2').css('display', '');
                    jQuery(this).closest('table').find('.ts-mosaic-post-limit-rows-3').css('display', 'none');
                }else{
                    jQuery(this).closest('table').find('.ts-mosaic-post-limit-rows-3').css('display', '');
                    jQuery(this).closest('table').find('.ts-mosaic-post-limit-rows-2').css('display', 'none');
                }
            }else{
                jQuery(this).closest('table').find('.ts-mosaic-post-limit-squares').css('display', '');
                jQuery(this).closest('table').find('.ts-mosaic-post-limit-rows-3').css('display', 'none');
                jQuery(this).closest('table').find('.ts-mosaic-post-limit-rows-2').css('display', 'none');
            }
        });

        jQuery('.ts-mosaic-rows').change(function(){
            jQuery(this).closest('table').find('.ts-mosaic-post-limit-squares').css('display', 'none');

            if( jQuery(this).val() == '2' ){
                jQuery(this).closest('table').find('.ts-mosaic-post-limit-rows-2').css('display', '');
                jQuery(this).closest('table').find('.ts-mosaic-post-limit-rows-3').css('display', 'none');
            }else{
                jQuery(this).closest('table').find('.ts-mosaic-post-limit-rows-3').css('display', '');
                jQuery(this).closest('table').find('.ts-mosaic-post-limit-rows-2').css('display', 'none');
            }
        });
        
    }
    ts_mosaic_post_limit();

    ts_slider_pips(6, 102, 6, jQuery('#last-posts-mosaic-post-limit-rows-2').val(), 'last-posts-mosaic-post-limit-rows-2-slider', 'last-posts-mosaic-post-limit-rows-2');
    ts_slider_pips(9, 99, 9, jQuery('#last-posts-mosaic-post-limit-rows-3').val(), 'last-posts-mosaic-post-limit-rows-3-slider', 'last-posts-mosaic-post-limit-rows-3');
    ts_slider_pips(5, 100, 5, jQuery('#last-posts-mosaic-post-limit-rows-squares').val(), 'last-posts-mosaic-post-limit-rows-squares-slider', 'last-posts-mosaic-post-limit-rows-squares');

    ts_slider_pips(6, 102, 6, jQuery('#list-videos-mosaic-post-limit-rows-2').val(), 'list-videos-mosaic-post-limit-rows-2-slider', 'list-videos-mosaic-post-limit-rows-2');
    ts_slider_pips(9, 99, 9, jQuery('#list-videos-mosaic-post-limit-rows-3').val(), 'list-videos-mosaic-post-limit-rows-3-slider', 'list-videos-mosaic-post-limit-rows-3');
    ts_slider_pips(5, 100, 5, jQuery('#list-videos-mosaic-post-limit-rows-squares').val(), 'list-videos-mosaic-post-limit-rows-squares-slider', 'list-videos-mosaic-post-limit-rows-squares');

    ts_slider_pips(6, 102, 6, jQuery('#latest-custom-posts-mosaic-post-limit-rows-2').val(), 'latest-custom-posts-mosaic-post-limit-rows-2-slider', 'latest-custom-posts-mosaic-post-limit-rows-2');
    ts_slider_pips(9, 99, 9, jQuery('#latest-custom-posts-mosaic-post-limit-rows-3').val(), 'latest-custom-posts-mosaic-post-limit-rows-3-slider', 'latest-custom-posts-mosaic-post-limit-rows-3');
    ts_slider_pips(5, 100, 5, jQuery('#latest-custom-posts-mosaic-post-limit-rows-squares').val(), 'latest-custom-posts-mosaic-post-limit-rows-squares-slider', 'latest-custom-posts-mosaic-post-limit-rows-squares');

    jQuery('#video-carousel-source').change(function(){

        if ( jQuery(this).val() == 'custom-slides' ) {

            jQuery('.video-carousel-nr-of-posts, .video-carousel-video, .video-carousel-post').css('display', 'none');
            jQuery(".ts-video-carousel-custom").css("display", "");

        } else {

            var value = jQuery(this).val();

            if ( value == 'latest-posts' || value == 'latest-featured-posts' ) {

                jQuery('.video-carousel-video').css('display', 'none');

                jQuery('.video-carousel-posts').css('display', '');

            } else {

                jQuery('.video-carousel-posts').css('display', 'none');

                jQuery('.video-carousel-video').css('display', '');
            }
            
            jQuery('.video-carousel-nr-of-posts').css('display', '');

            jQuery(".ts-video-carousel-custom").css("display", "none");
        }

    });
    
    jQuery('#video-carousel-source').trigger('change');

</script>