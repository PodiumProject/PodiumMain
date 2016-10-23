<?php   include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        $elementType = isset($_GET['elementType']) ? $_GET['elementType'] : '';

        $is_plugin_active = is_plugin_active('ts-custom-posts/ts-custom-posts.php');
        $touchsize_com = '<a href="http://touchsize.com/videofly-doc">' . esc_html__('here','videofly') . '</a>.';


?>
<div id="builder-elements" data-element-type="<?php echo vdf_var_sanitize($elementType) ?>">
<?php switch($elementType): ?><?php case 'logo': ?>
        <div class="logo builder-element">
            <h3 class="element-title"><?php esc_html_e('Logo', 'videofly'); ?></h3>
            <p><?php esc_html_e( 'You can add your logo in', 'videofly' ); ?> <strong><a target="_blank" href="<?php echo esc_url( admin_url( 'admin.php?page=videofly&tab=styles#ts-logo-type' ) ); ?>"><?php esc_html_e( 'Videofly -- Styles -- Logo type', 'videofly' ); ?></a></strong></p>
            <table cellpadding="10">
                <tr>
                    <td width="70px">
                        <?php esc_html_e('Logo align', 'videofly'); ?>
                    </td>
                    <td>
                        <select name="logo[align]" id="logo-align">
                            <option value="text-left"><?php esc_html_e('Left', 'videofly'); ?></option>
                            <option value="text-right"><?php esc_html_e('Right', 'videofly'); ?></option>
                            <option value="text-center"><?php esc_html_e('Center', 'videofly'); ?></option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
    <?php break ?>
    <?php case 'user': ?>
        <div class="user builder-element">
            <h3 class="element-title"><?php esc_html_e('User element', 'touchsize'); ?></h3>
            <p><?php esc_html_e( 'You can add login form to page', 'touchsize' ); ?></p>
            <table cellpadding="10">
                <tr>
                    <td width="70px">
                        <?php esc_html_e('Align', 'videofly'); ?>
                    </td>
                    <td>
                        <select name="user-align" id="user-align">
                            <option value="text-left"><?php esc_html_e('Left', 'videofly'); ?></option>
                            <option value="text-right"><?php esc_html_e('Right', 'videofly'); ?></option>
                            <option value="text-center"><?php esc_html_e('Center', 'videofly'); ?></option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
    <?php break ?>
    <?php case 'social-buttons': ?>
        <div class="social-buttons builder-element">
            <h3 class="element-title"><?php esc_html_e('Social icons','videofly'); ?></h3>
            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="social-buttons-admin-label"><?php esc_html_e('Admin label:','videofly'); ?></label>
                    </td>
                    <td>
                       <input type="text" id="social-buttons-admin-label" name="social-name" />
                    </td>
                </tr>
                <tr>
                    <td width="70px">
                        <?php esc_html_e('Text align', 'videofly'); ?>
                    </td>
                    <td>
                        <select name="social[align]" id="social-align">
                            <option value="text-left"><?php esc_html_e('Left', 'videofly'); ?></option>
                            <option value="text-right"><?php esc_html_e('Right', 'videofly'); ?></option>
                            <option value="text-center"><?php esc_html_e('Center', 'videofly'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="70px">
                        <?php esc_html_e('Styling', 'videofly'); ?>
                    </td>
                    <td>
                        <select name="social[style]" id="social-style">
                            <option value="big-background"><?php esc_html_e('Big background', 'videofly'); ?></option>
                            <option value="small-border"><?php esc_html_e('Small border', 'videofly'); ?></option>
                        </select>
                    </td>
                </tr>
            </table>
            <p><?php esc_html_e( 'You can edit this option in', 'videofly' ); ?> <strong><a target="_blank" href="<?php echo esc_url( admin_url( 'admin.php?page=videofly&tab=social' ) ); ?>"><?php esc_html_e( 'Videofly -- Social', 'videofly' ); ?></a></strong></p>
        </div>
    <?php break ?>
    <?php case 'featured-article': ?>
        <div class="featured-article builder-element">
            <h3 class="element-title"><?php esc_html_e('Featured article element','videofly'); ?></h3>
            <table cellpadding="10">
                <tr>
                    <td width="70px">
                        <?php esc_html_e('Show featured image', 'videofly'); ?>
                    </td>
                    <td>
                        <select name="featured-article-showImage" id="featured-article-showImage">
                            <option value="y"><?php esc_html_e('Yes', 'videofly'); ?></option>
                            <option value="n"><?php esc_html_e('No', 'videofly'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="70px">
                        <?php esc_html_e('Show post meta', 'videofly'); ?>
                    </td>
                    <td>
                        <select name="featured-article-showMeta" id="featured-article-showMeta">
                            <option value="y" selected="selected"><?php esc_html_e('Yes', 'videofly'); ?></option>
                            <option value="n"><?php esc_html_e('No', 'videofly'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="70px">
                        <?php esc_html_e('Search post by title','videofly'); ?>
                    </td>
                    <td>
                        <input type="text" name="featured-article-search-post" id="featured-article-search-post" style="width:250px"/>
                    </td>
                </tr>
                <tr>
                    <td width="70px">
                    </td>
                    <td>
                        <input type="button" name="search-post-button" id="search-type-post" class="search-posts-buttons button-primary save-element" value="<?php esc_html_e( 'Search', 'videofly' ) ?>"/>
                    </td>
                </tr>
            </table>

            <p id="search-text-results" style="display: none;"><?php esc_html_e( 'Results', 'videofly' ) ?></p>
            <table cellpadding="10" id="search-posts-results">
            </table>
        </div>
    <?php break ?>
    <?php case 'searchbox': ?>
        <div class="searchbox builder-element">
            <h3 class="element-title"><?php esc_html_e('Searchbar', 'videofly'); ?></h3>
            <table cellpadding="10">
                <tr>
                    <td width="70px">
                        <?php esc_html_e('Align', 'videofly'); ?>
                    </td>
                    <td>
                        <select name="searchbox-align" id="searchbox-align">
                            <option selected="selected" value="left"><?php esc_html_e('Left', 'videofly'); ?></option>
                            <option value="right"><?php esc_html_e('Right', 'videofly'); ?></option>
                            <option value="center"><?php esc_html_e('Center', 'videofly'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="70px">
                        <?php esc_html_e( 'Select design', 'videofly' ); ?>
                    </td>
                    <td>
                        <select name="searchbox-design" id="searchbox-design">
                            <option selected="selected" value="icon"><?php esc_html_e('Icon', 'videofly'); ?></option>
                            <option value="input"><?php esc_html_e('Search input', 'videofly'); ?></option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
    <?php break ?>
    <?php case 'clients': ?>
        <div class="clients builder-element">
            <h3 class="element-title"><?php esc_html_e('Clients block', 'videofly'); ?></h3>
            <table cellpadding="10">
                    <tr>
                        <td>
                            <label for="clients-admin-label"><?php esc_html_e('Admin label:','videofly'); ?></label>
                        </td>
                        <td>
                           <input type="text" id="clients-admin-label" name="clients-admin-label" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="clients-row"><?php esc_html_e( 'Number of elements per row', 'videofly' ); ?>:</label>
                        </td>
                        <td>
                            <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#clients-row" id="clients-row-selector">
                               <li><img class="image-radio-input clickable-element" data-option="1" src="<?php echo get_template_directory_uri().'/images/options/per_row_1.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="2" src="<?php echo get_template_directory_uri().'/images/options/per_row_2.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="3" src="<?php echo get_template_directory_uri().'/images/options/per_row_3.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="4" src="<?php echo get_template_directory_uri().'/images/options/per_row_4.png'; ?>"></li>
                               <li><img class="image-radio-input clickable-element" data-option="6" src="<?php echo get_template_directory_uri().'/images/options/per_row_6.png'; ?>"></li>
                            </ul>
                            <select name="clients-row" id="clients-row" class="hidden">
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
                            <label><?php esc_html_e( 'Enable carousel:', 'videofly' ); ?></label>
                        </td>
                        <td>
                            <input type="radio" name="clients-enable-carousel" id="clients-enable-carousel-y" value="y"/>
                            <label for="clients-enable-carousel-y"><?php esc_html_e( 'Yes', 'videofly' ); ?></label>
                            <input type="radio" name="clients-enable-carousel" id="clients-enable-carousel-n" value="n"  checked = "checked" />
                            <label for="clients-enable-carousel-n"><?php esc_html_e( 'No', 'videofly' ); ?></label>
                        </td>
                    </tr>
                </table>

                <ul id="clients_items">

                </ul>

                <input type="hidden" id="clients_content" value="" />
                <input type="button" class="button ts-multiple-add-button" data-element-name="clients" id="clients_add_item" value=" <?php esc_html_e('Add New Client', 'videofly'); ?>" />
                <?php
                    echo '<script id="clients_items_template" type="text/template">
                            <li id="list-item-id-{{item-id}}" class="clients_item ts-multiple-add-list-element">
                                <div class="sortable-meta-element"><span class="tab-arrow icon-down"></span> <span class="clients-item-tab ts-multiple-item-tab">Item: {{slide-number}}</span></div>
                                <div class="hidden">
                                    <table>
                                        <tr>
                                          <td>' . esc_html__( "Clients image",'videofly' ) . '</td>
                                          <td>
                                                <input type="text" name="clients-{{item-id}}[image]" id="clients-{{item-id}}-image" value="" data-role="media-url" />
                                                <input type="hidden" id="slide_media_id-{{item-id}}" name="clients_media_id-{{item-id}}" value=""  data-role="media-id" />
                                                <input type="button" id="uploader_{{item-id}}"  class="ts-secondary-button" value="' . esc_html__( "Upload",'videofly' ) . '" />
                                                <div id="image-preview-{{item-id}}"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="clients-{{item-id}}-title">' . esc_html__( "Enter your title here:",'videofly' ) . '</label>
                                            </td>
                                            <td>
                                               <input data-builder-name="title" type="text" id="clients-{{item-id}}-title" name="clients-[{{item-id}}][title]" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="clients-{{item-id}}-url">' . esc_html__( "Enter your url here:",'videofly' ) . '</label>
                                            </td>
                                            <td>
                                               <input data-builder-name="url" type="text" id="clients-{{item-id}}-url" name="clients-[{{item-id}}][url]" />
                                            </td>
                                        </tr>
                                    </table>

                                    <input type="hidden" data-builder-name="item_id" value="{{item-id}}" name="clients[{{item-id}}][id]" />
                                    <input type="button" class="ts-secondary-button remove-item" value="' . esc_html__('Remove', 'videofly') . '" />
                                    <a href="#" data-item="clients_item" data-increment="clients_items" class="ts-secondary-button ts-multiple-item-duplicate">'.esc_html__('Duplicate Item', 'videofly').'</a>
                                </div>
                            </li>
                        </script>';
               ?>
        </div>
    <?php break ?>
    <?php case 'features-block': ?>
        <div class="features-block builder-element">

            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="features-block-admin-label"><?php esc_html_e('Admin label','videofly');?>:</label>
                    </td>
                    <td>
                       <input type="text" id="features-block-admin-label" name="features-block-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="features-block-row"><?php esc_html_e( 'Number of elements per row', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#features-block-row" id="features-block-row-selector">
                           <li><img class="image-radio-input clickable-element" data-option="1" src="<?php echo get_template_directory_uri().'/images/options/per_row_1.png'; ?>"></li>
                           <li><img class="image-radio-input clickable-element" data-option="2" src="<?php echo get_template_directory_uri().'/images/options/per_row_2.png'; ?>"></li>
                           <li><img class="image-radio-input clickable-element" data-option="3" src="<?php echo get_template_directory_uri().'/images/options/per_row_3.png'; ?>"></li>
                           <li><img class="image-radio-input clickable-element" data-option="4" src="<?php echo get_template_directory_uri().'/images/options/per_row_4.png'; ?>"></li>
                           <li><img class="image-radio-input clickable-element" data-option="6" src="<?php echo get_template_directory_uri().'/images/options/per_row_6.png'; ?>"></li>
                        </ul>
                        <select name="features-block-row" id="features-block-row" class="hidden">
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
                        <label for="features-block-gutter"><?php esc_html_e( 'Remove gutter(space) between articles:', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <select name="features-block-gutter" id="features-block-gutter">
                            <option value="no-gutter"><?php esc_html_e('Yes', 'videofly'); ?></option>
                            <option selected="selected" value="gutter"><?php esc_html_e('No', 'videofly'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="features-block-style"><?php esc_html_e( 'Style', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <select name="features-block-style" id="features-block-style">
                            <option value="style1"><?php esc_html_e('Iconbox with border', 'videofly') ?></option>
                            <option value="style2"><?php esc_html_e('Feature with view background', 'videofly') ?></option>
                        </select>
                    </td>
                </tr>
            </table>

            <h3 class="element-title"><?php esc_html_e('Feature blocks', 'videofly'); ?></h3>
            <p><?php esc_html_e('Please select your icon, add your title and content below.', 'videofly'); ?></p>
            <ul id="features-block_items">

            </ul>
            <?php
                $icons = vdf_build_list_icons();
                $li = $icons['li'];
                $option = $icons['option'];
            ?>
            <input type="hidden" id="features-block_content" value="" />
            <input type="button" class="button ts-multiple-add-button" data-element-name="features-block" id="features-block_add_item" value=" <?php esc_html_e('Add New Item', 'videofly'); ?>" />
            <?php
                echo '<script id="features-block_items_template" type="text/template">';
                echo '<li id="list-item-id-{{item-id}}" class="features-block-item ts-multiple-add-list-element">
                        <div class="sortable-meta-element"><span class="tab-arrow icon-down"></span> <span class="features-block-item-tab ts-multiple-item-tab">Item: {{slide-number}}</span></div>
                        <div class="hidden">
                   <label for="features-block-icon">Choose your icon:</label>
                    <div class="builder-element-icon-toggle">
                        <a href="#" class="red-ui-button builder-element-icon-trigger-btn" data-toggle="#features-block-icon-id-{{item-id}}-selector">'.esc_html__('Show icons', 'videofly').'</a>
                    </div>
                    <div class="ts-icons-container">
                        <label>
                            '. esc_html__('Search icon', 'videofly') .'
                            <input type="search" name="search-icon" value="" />
                        </label>
                        <ul id="features-block-icon-id-{{item-id}}-selector" data-selector="#features-block-{{item-id}}-icon" class="builder-icon-list ts-custom-selector">'
                            . $li .
                        '</ul>
                        <select data-builder-name="icon" name="features-block[{{item-id}}][icon]" id="features-block-{{item-id}}-icon" class="hidden">'
                           . $option .
                        '</select>
                    </div>
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
                              <label for="features-block-border">Border/Background color:</label>
                          </td>
                          <td>
                             <input data-builder-name="background" type="text" value="#777" id="features-block-{{item-id}}-background" class="colors-section-picker" name="features-block[{{item-id}}][background]" />
                             <div class="colors-section-picker-div" id="features-block-{{item-id}}-background-picker"></div>
                          </td>
                        </tr>
                       <tr>
                          <td>
                              <label for="features-block-font">Icon color:</label>
                          </td>
                          <td>
                             <input data-builder-name="font" type="text" value="#777" id="features-block-{{item-id}}-font" class="colors-section-picker" name="features-block[{{item-id}}][font]" />
                             <div class="colors-section-picker-div" id="features-block-{{item-id}}-font-picker"></div>
                          </td>
                      </tr>
                      <tr class="ts-select-animation">
                          <td>'
                              . esc_html__( 'Special effect', 'videofly' ) .
                          '</td>
                          <td>'.
                              vdfAllAnimations('features-block-{{item-id}}-effect', 'effect', 'none', 'showattr') .'
                          </td>
                      </tr>
                      <tr class="ts-select-delay">
                          <td>'
                              . esc_html__( 'Delay', 'videofly' ) .
                          '</td>
                          <td>'.
                              vdfAllAnimations('features-block-{{item-id}}-delay', 'delay', 'none', 'showattr') .'
                          </td>
                      </tr>
                   </table>
                   <input type="hidden" data-builder-name="item_id" value="{{item-id}}" name="features-block[{{item-id}}][id]" />
                   <input type="button" class="ts-secondary-button remove-item" value="'.esc_html__('Remove', 'videofly').'" />
                   <a href="#" data-item="features-block_item" data-increment="features-block_items" class="ts-secondary-button ts-multiple-item-duplicate">'.esc_html__('Duplicate Item', 'videofly').'</a>
                   </div>
                </li>
               ';
                echo '</script>';
            ?>
        </div>
    <?php break ?>
    <?php case 'listed-features': ?>
        <div class="listed-features builder-element">
            <h3 class="element-title"><?php esc_html_e('Listed features', 'videofly'); ?></h3>
            <table>
                <tr>
                    <td>
                        <label for="listed-features-admin-label"><?php esc_html_e('Admin label','videofly');?>:</label>
                    </td>
                    <td>
                       <input type="text" id="listed-features-admin-label" name="listed-features-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="listed-features-align"><?php esc_html_e( "Icon alignment:",'videofly') ?></label>
                    </td>
                    <td>
                        <select name="listed-features-align" id="listed-features-align">
                            <option value="text-left"><?php esc_html_e( "Left",'videofly') ?></option>
                            <option value="text-right"><?php esc_html_e( "Right",'videofly') ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="listed-features-color-style"><?php esc_html_e("Add color for:",'videofly') ?></label>
                    </td>
                    <td>
                        <select name="listed-features-color-style" id="listed-features-color-style">
                            <option value="border"><?php esc_html_e( "Border",'videofly') ?></option>
                            <option value="background"><?php esc_html_e( "Background",'videofly') ?></option>
                            <option value="none"><?php esc_html_e( "None",'videofly') ?></option>
                        </select>
                    </td>
                </tr>
            </table>
           <p><?php esc_html_e('Please select your icon, add your title and content below.', 'videofly'); ?></p>
           <ul id="listed-features_items">

           </ul>
           <?php
               $icons = vdf_build_list_icons();
               $li = $icons['li'];
               $option = $icons['option'];
           ?>
           <input type="hidden" id="listed-features_content" value="" />
           <input type="button" class="button ts-multiple-add-button" data-element-name="listed-features" id="listed-features_add_item" value=" <?php esc_html_e('Add New Item', 'videofly'); ?>" />
           <?php
                echo '<script id="listed-features_items_template" type="text/template">';
                echo '<li id="list-item-id-{{item-id}}" class="listed-features-item ts-multiple-add-list-element">
                        <div class="sortable-meta-element"><span class="tab-arrow icon-down"></span> <span class="listed-features-item-tab ts-multiple-item-tab">' . esc_html__('Item:', 'videofly') . ' {{slide-number}}</span></div>
                        <div class="hidden">
                    <label for="listed-features-icon">' . esc_html__('Choose your icon:', 'videofly') . '</label>
                    <div class="builder-element-icon-toggle">
                        <a href="#" class="red-ui-button builder-element-icon-trigger-btn" data-toggle="#listed-features-icon-id-{{item-id}}-selector">' . esc_html__('Show icons', 'videofly') . '</a>
                    </div>
                     <div class="ts-icons-container">
                        <label>
                            '. esc_html__('Search icon', 'videofly') .'
                            <input type="search" name="search-icon" value="" />
                        </label>
                        <ul id="listed-features-icon-id-{{item-id}}-selector" data-selector="#listed-features-{{item-id}}-icon" class="builder-icon-list ts-custom-selector">';
                            echo vdf_var_sanitize($li);
                    echo '</ul>
                        <select data-builder-name="icon" name="listed-features[{{item-id}}][icon]" id="listed-features-{{item-id}}-icon" class="hidden">';
                            echo vdf_var_sanitize($option);
                    echo '</select>
                    </div>
                    <table>
                        <tr>
                            <td>
                                <label for="listed-features-{{item-id}}-title">' . esc_html__('Enter your title here:', 'videofly') . '</label>
                            </td>
                            <td>
                                <input data-builder-name="title" type="text" id="listed-features-{{item-id}}-title" name="listed-features[{{item-id}}][title]" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="listed-features-{{item-id}}-url">' . esc_html__('Enter title url here:', 'videofly') . '</label>
                            </td>
                            <td>
                                <input data-builder-name="url" type="text" id="listed-features-{{item-id}}-url" name="listed-features[{{item-id}}][url]" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="listed-features-text">' . esc_html__('Write your text here:', 'videofly') . '</label>
                            </td>
                            <td>
                                <textarea data-builder-name="text" name="listed-features-{{item-id}}-text" id="listed-features-{{item-id}}-text" cols="45" rows="5"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="listed-features-{{item-id}}-iconcolor">' . esc_html__('Icon color:', 'videofly') . '</label>
                            </td>
                            <td>
                                <input data-builder-name="iconcolor" type="text" value="#777" id="listed-features-{{item-id}}-iconcolor" class="colors-section-picker" name="listed-features-{{item-id}}-icon-color" />
                                <div class="colors-section-picker-div" id="listed-features-{{item-id}}-icon-color-picker"></div>
                            </td>
                        </tr>
                        <tr class="ts-border-color">
                            <td>
                                <label for="listed-features-{{item-id}}-bordercolor">' . esc_html__('Border color:', 'videofly') . '</label>
                            </td>
                            <td>
                                <input data-builder-name="bordercolor" type="text" value="#777" id="listed-features-{{item-id}}-bordercolor" class="colors-section-picker" name="listed-features-{{item-id}}-border-color" />
                                <div class="colors-section-picker-div" id="listed-features-{{item-id}}-border-color-picker"></div>
                            </td>
                        </tr>
                        <tr class="ts-background-color">
                            <td>
                                <label for="listed-features-{{item-id}}-background-color">' . esc_html__('Background color:', 'videofly') . '</label>
                            </td>
                            <td>
                                <input data-builder-name="backgroundcolor" type="text" value="#777" id="listed-features-{{item-id}}-backgroundcolor" class="colors-section-picker" name="listed-features-{{item-id}}-background-color" />
                                <div class="colors-section-picker-div" id="listed-features-{{item-id}}-background-color-picker"></div>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" data-builder-name="item_id" value="{{item-id}}" name="listed-features[{{item-id}}][id]" />
                    <input type="button" class="ts-secondary-button remove-item" value="'.esc_html__('Remove', 'videofly').'" />
                    <a href="#" data-item="listed-features-item" data-increment="listed-features_items" class="ts-secondary-button ts-multiple-item-duplicate">' . esc_html__('Duplicate Item', 'videofly') . '</a></div>
                </li>
               ';
                echo '</script>';
           ?>
        </div>
    <?php break ?>
    <?php case 'icon': ?>
        <div class="icon builder-element">
            <h3 class="element-title"><?php esc_html_e('Icon', 'videofly'); ?></h3>
            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="icon-admin-label"><?php esc_html_e('Admin label','videofly');?>:</label>
                    </td>
                    <td>
                       <input type="text" id="icon-admin-label" name="icon-admin-label" />
                    </td>
                </tr>
            </table>
            <p><?php esc_html_e('Choose your icon from the library below:', 'videofly'); ?></p>
            <div class="builder-element-icon-toggle">
                <a href="#" class="red-ui-button builder-element-icon-trigger-btn" data-toggle="#builder-element-icon-selector"><?php esc_html_e('Show icons','videofly') ?></a>
            </div>
            <div class="ts-icons-container">
                <label>
                    <?php esc_html_e('Search icon', 'videofly'); ?>
                    <input type="search" name="search-icon" value="" />
                </label>
               <?php
                    $icons = vdf_build_list_icons();
                    $li = $icons['li'];
                    $option = $icons['option'];
                ?>
                <ul id="builder-element-icon-selector" data-selector="#builder-element-icon" class="builder-icon-list ts-custom-selector">
                    <?php  echo vdf_var_sanitize($li); ?>
                </ul>
                <select name="builder-element-icon" id="builder-element-icon" class="hidden">
                    <?php echo vdf_var_sanitize($option); ?>
                </select>
            </div>
            <h3><?php esc_html_e('Icon options', 'videofly'); ?></h3>
            <table>
                <tr>
                    <td>
                        <label for="builder-element-icon-size"><?php esc_html_e('Select your icon size', 'videofly'); ?></label>
                    </td>
                    <td>
                        <input type="text" id="builder-element-icon-size" name="builder-element-icon-size" />px
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="builder-element-icon-color"><?php esc_html_e('Select your icon color', 'videofly'); ?></label>
                    </td>
                    <td>
                        <input type="text" id="builder-element-icon-color" class="colors-section-picker" value="#000" name="builder-element-icon-color" />
                        <div class="colors-section-picker-div" id=""></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="builder-element-icon-align"><?php esc_html_e('Select your icon align', 'videofly'); ?></label>
                    </td>
                    <td>
                        <select name="builder-element-icon-align" id="builder-element-icon-align">
                           <option value="left"><?php esc_html_e('Left', 'videofly'); ?></option>
                           <option value="center"><?php esc_html_e('Center', 'videofly'); ?></option>
                           <option value="right"><?php esc_html_e('Right', 'videofly'); ?></option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
    <?php break ?>
    <?php case 'menu': ?>
        <div class="menu builder-element">
            <h3 class="element-title"><?php esc_html_e('Menu', 'videofly'); ?></h3>
            <table cellpadding="10">
                 <tr>
                    <td>
                        <label for="menu-admin-label"><?php esc_html_e('Admin label:','videofly');?></label>
                    </td>
                    <td>
                       <input type="text" id="menu-admin-label" name="menu-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Choose your menu','videofly'); ?>
                    </td>
                    <td>
                        <?php
                            $menus = wp_get_nav_menus();
                            if( isset($menus) && is_array($menus) && !empty($menus) ) : ?>
                                <select name="menu-name" id="menu-name">
                                    <?php foreach($menus as $menu) : ?>
                                        <?php if( is_object($menu) ) : ?>
                                            <option value="<?php echo vdf_var_sanitize($menu->term_id); ?>"><?php echo vdf_var_sanitize($menu->name); ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            <?php else : ?>
                                <p><?php esc_html_e('No selected items', 'videofly'); ?></p>
                            <?php endif;
                        ?>
                    </td>
                </tr>
                <tr>
                    <td width="70px">
                        <?php esc_html_e('Menu style', 'videofly'); ?>
                    </td>
                    <td>
                        <select name="menu-styles" id="menu-styles">
                            <option value="style1"><?php esc_html_e('Horizontal menu', 'videofly') ?></option>
                            <?php
                                if(fields::get_options_value('videofly_general', 'enable_mega_menu') == 'N'){
                                    echo "<option value='style2'>" . esc_html__('Vertical menu', 'videofly') . "</option>";
                                }else{
                                    echo '';
                                }
                            ?>
                            <option value="style3"><?php esc_html_e('Menu with logo in the middle', 'videofly') ?></option>
                            <option value="style4"><?php esc_html_e('Sidebar menu', 'videofly') ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Select type font', 'videofly'); ?>
                    </td>
                    <td>
                        <select name="menu-font-type" id="menu-font-type">
                            <option selected="selected" value="std"><?php esc_html_e('Standard font', 'videofly') ?></option>
                            <option value="google"><?php esc_html_e('Google fonts', 'videofly') ?></option>
                        </select>
                    </td>
                </tr>
                <tr class="ts-builder-element-menu">
                    <td>
                        <?php esc_html_e( 'Select font name', 'videofly' ); ?>
                    </td>
                    <td>
                       <select name="menu-font-name" id="fontchanger-menu">
                           <option selected="selected" value="0"><?php esc_html_e( 'No font selected', 'videofly' ); ?></option>
                       </select>
                       <input type="hidden" value="0" id="ts-fontchanger-menu">
                    </td>
                </tr>
                <tr class="ts-builder-element-menu">
                    <td>
                        <?php esc_html_e( 'Available subsets:', 'videofly' ); ?>
                    </td>
                    <td>
                        <div class="menu-font-subsets"></div>
                        <input type="hidden" value="0" id="ts-menu-font-subsets">
                    </td>
                </tr>
                <tr class="ts-builder-element-menu">
                    <td>
                        <?php esc_html_e( 'Font weight', 'videofly' ); ?>
                    </td>
                    <td>
                        <select name="menu-font-weight" id="ts-menu-font-weight">
                           <option selected="selected" value="400"><?php esc_html_e( 'Normal', 'videofly' ); ?></option>
                           <option value="700"><?php esc_html_e( 'Bold', 'videofly' ); ?></option>
                       </select>
                    </td>
                </tr>
                <tr class="ts-builder-element-menu">
                    <td>
                        <?php esc_html_e( 'Font style', 'videofly' ); ?>
                    </td>
                    <td>
                        <select name="menu-font-style" id="ts-menu-font-style">
                           <option selected="selected" value="normal"><?php esc_html_e( 'Normal', 'videofly' ); ?></option>
                           <option value="italic"><?php esc_html_e( 'Italic', 'videofly' ); ?></option>
                       </select>
                    </td>
                </tr>
                <tr class="ts-builder-element-menu">
                    <td>
                        <?php esc_html_e( 'Font size', 'videofly' ); ?>
                    </td>
                    <td>
                        <input type="text" id="ts-menu-font-size" name="menu-font-size" value="15" />
                    </td>
                </tr>
                <tr class="ts-builder-element-menu">
                    <td>
                        <?php esc_html_e( 'Logo text', 'videofly' ); ?>
                    </td>
                    <td>
                       <textarea cols="52" rows="3" name="menu-demo" id="menu-demo">Videofly</textarea>
                    </td>
                </tr>
                <tr class="ts-builder-element-menu">
                    <td>
                        <?php esc_html_e( 'Preview', 'videofly' ); ?>
                    </td>
                    <td>
                       <input type="button" name="menu-font-preview" id="ts-menu-preview" class="ts-secondary-button" value="<?php esc_html_e('Preview', 'videofly'); ?>">
                       <div class="menu-output"></div>
                    </td>
                </tr>
                <tr>
                    <td width="70px">
                        <?php esc_html_e('Uppercase', 'videofly'); ?>
                    </td>
                    <td>
                        <select name="menu-uppercase" id="menu-uppercase">
                            <option value="menu-uppercase"><?php esc_html_e('Yes', 'videofly') ?></option>
                            <option selected="selected" value="menu-no-uppercase"><?php esc_html_e('No', 'videofly') ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="70px">
                        <?php esc_html_e('Menu custom colors', 'videofly'); ?>
                    </td>
                    <td>
                        <select name="menu-custom" id="menu-custom">
                            <option value="n"><?php esc_html_e('Default colors', 'videofly'); ?></option>
                            <option value="y"><?php esc_html_e('Custom colors', 'videofly'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr class="menu-custom-colors hidden">
                    <td valign="top">
                        <?php esc_html_e('Color settings', 'videofly') ?>:
                    </td>
                    <td>
                        <div class="menu-element-color-options menu-element-bg-color">
                            <div class="color-option-title"><?php esc_html_e('Menu background color', 'videofly'); ?></div>
                            <input type="text" id="menu-element-bg-color" class="colors-section-picker" name="menu-element-bg-color" value="#DDDDDD" />
                            <div class="colors-section-picker-div"></div>
                       </div>
                        <div class="menu-element-color-options menu-element-text-color">
                            <div class="color-option-title"><?php esc_html_e('Menu text color', 'videofly'); ?></div>
                            <input type="text" id="menu-element-text-color" class="colors-section-picker" name="menu-element-text-color" value="#FFFFFF" />
                            <div class="colors-section-picker-div"></div>
                       </div>
                        <div class="menu-element-color-options menu-element-bg-color-hover">
                            <div class="color-option-title"><?php esc_html_e('Menu background color on hover', 'videofly'); ?></div>
                            <input type="text" id="menu-element-bg-color-hover" class="colors-section-picker" name="menu-element-bg-color-hover" value="#DDDDDD" />
                            <div class="colors-section-picker-div"></div>
                       </div>
                        <div class="menu-element-color-options menu-element-text-color-hover">
                            <div class="color-option-title"><?php esc_html_e('Menu text color on hover', 'videofly'); ?></div>
                            <input type="text" id="menu-element-text-color-hover" class="colors-section-picker" name="menu-element-text-color-hover" value="#FFFFFF" />
                            <div class="colors-section-picker-div"></div>
                       </div>
                        <div class="menu-element-color-options menu-element-submenu-bg-color">
                            <div class="color-option-title"><?php esc_html_e('Submenu background color', 'videofly'); ?></div>
                            <input type="text" id="menu-element-submenu-bg-color" class="colors-section-picker" name="menu-element-submenu-bg-color" value="#DDDDDD" />
                            <div class="colors-section-picker-div"></div>
                       </div>
                        <div class="menu-element-color-options menu-element-submenu-text-color">
                            <div class="color-option-title"><?php esc_html_e('Submenu text color', 'videofly'); ?></div>
                            <input type="text" id="menu-element-submenu-text-color" class="colors-section-picker" name="menu-element-submenu-text-color" value="#FFFFFF" />
                            <div class="colors-section-picker-div"></div>
                       </div>
                        <div class="menu-element-color-options menu-element-submenu-bg-color-hover">
                            <div class="color-option-title"><?php esc_html_e('Submenu background color on hover', 'videofly'); ?></div>
                            <input type="text" id="menu-element-submenu-bg-color-hover" class="colors-section-picker" name="menu-element-submenu-bg-color-hover" value="#DDDDDD" />
                            <div class="colors-section-picker-div"></div>
                       </div>
                        <div class="menu-element-color-options menu-element-submenu-text-color-hover">
                            <div class="color-option-title"><?php esc_html_e('Submenu text color on hover', 'videofly'); ?></div>
                            <input type="text" id="menu-element-submenu-text-color-hover" class="colors-section-picker" name="menu-element-submenu-text-color-hover" value="#FFFFFF" />
                            <div class="colors-section-picker-div"></div>
                       </div>
                    </td>
                </tr>
                 <tr>
                    <td width="70px">
                        <?php esc_html_e('Menu text align', 'videofly'); ?>
                    </td>
                    <td>
                        <select name="menu-text-align" id="menu-text-align">
                            <option value="menu-text-align-left"><?php esc_html_e('Left', 'videofly') ?></option>
                            <option value="menu-text-align-right"><?php esc_html_e('Right', 'videofly') ?></option>
                            <option value="menu-text-align-center"><?php esc_html_e('Center', 'videofly') ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="70px">
                        <?php esc_html_e('Hide icons', 'videofly'); ?>
                    </td>
                    <td>
                        <select name="menu-icons" id="menu-icons">
                            <option value="y"><?php esc_html_e('Yes', 'videofly') ?></option>
                            <option selected="selected" value="n"><?php esc_html_e('No', 'videofly') ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="70px">
                        <?php esc_html_e('Hide description', 'videofly'); ?>
                    </td>
                    <td>
                        <select name="menu-description" id="menu-description">
                            <option value="y"><?php esc_html_e('Yes', 'videofly') ?></option>
                            <option selected="selected" value="n"><?php esc_html_e('No', 'videofly') ?></option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
    <?php break ?>
    <?php case 'sidebar': ?>
        <div class="sidebar builder-element">
            <h3 class="element-title"><?php esc_html_e('Sidebar', 'videofly'); ?></h3>
            <table cellpadding="10">
                 <tr>
                    <td>
                        <label for="sidebar-admin-label"><?php esc_html_e('Admin label','videofly');?>:</label>
                    </td>
                    <td>
                       <input type="text" id="sidebar-admin-label" name="sidebar-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td width="70px">
                        <?php esc_html_e('Sidebars', 'videofly'); ?>
                    </td>
                    <td>
                        <?php
                            echo ts_sidebars_drop_down('','available-sidebars');
                        ?>
                    </td>
                </tr>
                <tr>
                    <td width="70px">
                        <?php esc_html_e('Make sidebar sticky within container', 'videofly'); ?>
                    </td>
                    <td>
                        <select name="sidebar-sticky" id="sidebar-sticky">
                            <option value="y"><?php esc_html_e('Yes', 'videofly') ?></option>
                            <option selected="selected" value="n"><?php esc_html_e('No', 'videofly') ?></option>
                        </select>
                        <div class="ts-option-description">
                            <?php esc_html_e('This will only work properly if within new row, with only a full row columns. If you place columns above or below, there might be errors on stick.', 'videofly'); ?>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    <?php break ?>
    <?php case 'slider': ?>
        <?php if( $is_plugin_active ): ?>
        <div class="slider builder-element">
            <h3 class="element-title"><?php esc_html_e('Slider', 'videofly'); ?></h3>
            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="slider-admin-label"><?php esc_html_e('Admin label','videofly');?>:</label>
                    </td>
                    <td>
                       <input type="text" id="slider-admin-label" name="slider-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="slider-name"><?php esc_html_e( 'Slider to display', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <select name="slider-name" id="slider-name">
                        <?php
                            echo '<option value="0">-- ' . esc_html__( 'Select slider', 'videofly' ) . ' --</option>';
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
                            <p><?php esc_html_e( 'Looks like you dont have any slider. You can create one', 'videofly' ); ?> <strong><a target="_blank" href="<?php echo esc_url( admin_url( 'edit.php?post_type=ts_slider' ) ); ?>"><?php esc_html_e( 'here', 'videofly' ); ?></a></strong></p>
                        <?php endif ?>
                    </td>
                </tr>
            </table>
        </div>
        <?php endif; ?>
    <?php break ?>
    <?php case 'list-portfolios': ?>
        <?php if( $is_plugin_active ) : ?>
        <div class="list-portfolios builder-element">
            <h3 class="element-title"><?php esc_html_e('List portfolios','videofly'); ?></h3>
            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="list-portfolios-admin-label"><?php esc_html_e('Admin label:','videofly'); ?></label>
                    </td>
                    <td>
                       <input type="text" id="list-portfolios-admin-label" name="list-portfolios-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <label for="list-portfolios-category"><?php esc_html_e( 'Category:', 'videofly' ); ?></label>
                    </td>
                    <td>
                        <?php
                            $categories = get_categories(array( 'hide_empty' => 0, 'taxonomy' => 'portfolio_register_post_type', 'pad_counts' => true ));
                            if( isset($categories) && !is_wp_error($categories) && !empty($categories) ) : ?>
                                <select class="ts-custom-select-input" data-placeholder="<?php esc_html_e('Select your category', 'videofly'); ?>" name="list-portfolios-category" id="list-portfolios-category" multiple>
                                    <option value="0"><?php esc_html_e('All', 'videofly') ?></option>
                                    <?php foreach ($categories as $index => $category): ?>
                                        <option value="<?php echo vdf_var_sanitize($category->slug ); ?>"><?php echo vdf_var_sanitize($category->cat_name ); ?></option>
                                    <?php endforeach ?>
                                </select>
                            <?php else : ?>
                                <select class="ts-custom-select-input" data-placeholder="<?php esc_html_e('Select your category', 'videofly'); ?>" name="teams-category" id="teams-category" multiple>
                                </select>
                            <?php endif; ?>
                        <div class="ts-option-description">
                            <?php esc_html_e('Choose the categories that you want to showcase articles from.', 'videofly'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="list-portfolios-display-mode"><?php esc_html_e( 'How to display:', 'videofly' ); ?></label>
                    </td>
                    <td>
                        <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-portfolios-display-mode" id="list-portfolios-display-mode-selector">
                           <li><img class="image-radio-input clickable-element" data-option="grid" src="<?php echo get_template_directory_uri().'/images/options/grid_view_title_excerpt.png'; ?>"></li>
                           <li><img class="image-radio-input clickable-element" data-option="list" src="<?php echo get_template_directory_uri().'/images/options/list_view_13.png'; ?>"></li>
                           <li><img class="image-radio-input clickable-element" data-option="thumbnails" src="<?php echo get_template_directory_uri().'/images/options/thumb_view.png'; ?>"></li>
                           <li><img class="image-radio-input clickable-element" data-option="big-post" src="<?php echo get_template_directory_uri().'/images/options/big_posts_12.png'; ?>"></li>
                           <li><img class="image-radio-input clickable-element" data-option="super-post" src="<?php echo get_template_directory_uri().'/images/options/super_post_view.png'; ?>"></li>
                           <li><img class="image-radio-input clickable-element" data-option="mosaic" src="<?php echo get_template_directory_uri().'/images/options/mosaic_view.png'; ?>"></li>
                        </ul>
                        <select class="hidden" name="list-portfolios-display-mode" id="list-portfolios-display-mode">
                            <option value="grid" selected="selected"><?php esc_html_e( 'Grid', 'videofly' ); ?></option>
                            <option value="list"><?php esc_html_e( 'List', 'videofly' ); ?></option>
                            <option value="thumbnails"><?php esc_html_e( 'Thumbnails', 'videofly' ); ?></option>
                            <option value="big-post"><?php esc_html_e( 'Big post', 'videofly' ); ?></option>
                            <option value="super-post"><?php esc_html_e( 'Super Post', 'videofly' ); ?></option>
                            <option value="mosaic"><?php esc_html_e( 'Mosaic', 'videofly' ); ?></option>
                        </select>
                        <div class="ts-option-description">
                            <?php esc_html_e('Choose your article view type. Depending on what type of article showcase layout you select you will get different options. You can read more about view types in our documentation files: ', 'videofly'); echo vdf_var_sanitize($touchsize_com); ?>
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
                                <label><?php esc_html_e( 'Behavior:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-portfolios-grid-behavior" id="list-portfolios-grid-behavior-selector">
                                   <li><img class="image-radio-input clickable-element" data-option="none" src="<?php echo get_template_directory_uri().'/images/options/behavior_none.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="carousel" src="<?php echo get_template_directory_uri().'/images/options/behavior_carousel.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="masonry" src="<?php echo get_template_directory_uri().'/images/options/behavior_masonry.png'; ?>"></li>
                                </ul>
                                <select class="hidden" name="list-portfolios-grid-behavior" id="list-portfolios-grid-behavior">
                                    <option value="none"><?php esc_html_e( 'Normal', 'videofly' ); ?></option>
                                    <option value="carousel"><?php esc_html_e( 'Carousel', 'videofly' ); ?></option>
                                    <option value="masonry"><?php esc_html_e( 'Masonry', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your posts behavior - you can have them just shown, or you can activate the carousel. If carousel is selected your articles will show up in a line with arrows for navigation. If masonry bahevior is selected - your articles will be arranged in to fit it. Be aware that activating the masonry option the crop settings for image sizes tab will be overwritten.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-portfolios-grid-title"><?php esc_html_e( 'Title position', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-portfolios-grid-title" id="list-portfolios-grid-title-selector">
                                   <li><img class="image-radio-input clickable-element" data-option="title-above-image" src="<?php echo get_template_directory_uri().'/images/options/grid_view_title_image.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="title-below-image" src="<?php echo get_template_directory_uri().'/images/options/grid_view_title_excerpt.png'; ?>"></li>
                                </ul>
                                <select class="hidden" name="list-portfolios-grid-title" id="list-portfolios-grid-title">
                                    <option value="title-above-image"><?php esc_html_e( 'Above image', 'videofly' ); ?></option>
                                    <option value="title-below-image"><?php esc_html_e( 'Above excerpt', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Select your title position for you grid posts. You can either have it above the image of above the excerpt. Note that sometimes title may change the position of the meta: date, categories, author as well.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-portfolios-grid-show-meta"><?php esc_html_e( 'Show meta', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="radio" name="list-portfolios-grid-show-meta" id="list-portfolios-grid-show-meta-y" value="y"  checked = "checked"  />
                                <label for="list-portfolios-grid-show-meta-y"><?php esc_html_e( 'Yes', 'videofly' ); ?></label>
                                <input type="radio" name="list-portfolios-grid-show-meta" id="list-portfolios-grid-show-meta-n" value="n" />
                                <label for="list-portfolios-grid-show-meta-n"><?php esc_html_e( 'No', 'videofly' ); ?></label>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can choose to show or to hide your meta details for your articles. Meta values include date, author, categories and other article details.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-portfolios-grid-el-per-row"><?php esc_html_e( 'Elements per row', 'videofly' ); ?>:</label>
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
                                    <?php esc_html_e('Your articles will be shown columns. Choose how mane columns do you want to use per line. Note that for mobile devices you will get only ONE element per row for better usability.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-portfolios-grid-nr-of-posts"><?php esc_html_e( 'How many posts to extract', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="text" value="" name="list-portfolios-grid-nr-of-posts" id="list-portfolios-grid-nr-of-posts" size="4"/>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-portfolios-grid-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-portfolios-grid-order-by" id="list-portfolios-grid-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                    <option value="rand"><?php esc_html_e( 'Random', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-portfolios-grid-order-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-portfolios-grid-order-direction" id="list-portfolios-grid-order-direction">
                                    <option value="ASC"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option value="DESC" selected="selected"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-portfolios-grid-special-effects"><?php esc_html_e( 'Special effects', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-portfolios-grid-special-effects" id="list-portfolios-grid-special-effects">
                                    <option value="none"><?php esc_html_e('No effect', 'videofly') ?></option>
                                    <option value="opacited"><?php esc_html_e('Fade in', 'videofly') ?></option>
                                    <option value="rotate-in"><?php esc_html_e('Rotate in', 'videofly') ?></option>
                                    <option value="3dflip"><?php esc_html_e('3d flip', 'videofly') ?></option>
                                    <option value="scaler"><?php esc_html_e('Scale', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'videofly'); ?>
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
                                <label for="list-portfolios-list-show-meta"><?php esc_html_e( 'Show meta', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="radio" name="list-portfolios-list-show-meta" id="list-portfolios-list-show-meta-y" value="y"  checked = "checked"  />
                                <label for="list-portfolios-list-show-meta-y"><?php esc_html_e( 'Yes', 'videofly' ); ?></label>
                                <input type="radio" name="list-portfolios-list-show-meta" id="list-portfolios-list-show-meta-n" value="n" />
                                <label for="list-portfolios-list-show-meta-n"><?php esc_html_e( 'No', 'videofly' ); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-portfolios-list-nr-of-posts"><?php esc_html_e( 'How many posts to extract', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="text" value="" name="list-portfolios-list-nr-of-posts" id="list-portfolios-list-nr-of-posts" size="4"/>
                            </td>
                        </tr>
                        <?php /*
                        <tr>
                            <td>
                                <?php esc_html_e( 'Content split', 'videofly' ); ?>
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
                        */ ?>
                        <tr>
                            <td>
                                <label for="list-portfolios-list-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-portfolios-list-order-by" id="list-portfolios-list-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-portfolios-list-order-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-portfolios-list-order-direction" id="list-portfolios-list-order-direction">
                                    <option value="ASC"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option value="DESC" selected="selected"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-portfolios-list-special-effects"><?php esc_html_e( 'Special effects:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="list-portfolios-list-special-effects" id="list-portfolios-list-special-effects">
                                    <option value="none"><?php esc_html_e('No effect', 'videofly') ?></option>
                                    <option value="opacited"><?php esc_html_e('Fade in', 'videofly') ?></option>
                                    <option value="rotate-in"><?php esc_html_e('Rotate in', 'videofly') ?></option>
                                    <option value="3dflip"><?php esc_html_e('3d flip', 'videofly') ?></option>
                                    <option value="scaler"><?php esc_html_e('Scale', 'videofly') ?></option>
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
                                <label for="list-portfolios-thumbnail-title"><?php esc_html_e( 'Title position', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-portfolios-thumbnail-title" id="list-portfolios-thumbnail-title">
                                    <option value="over-image" selected="selected"><?php esc_html_e( 'Over image', 'videofly' ); ?></option>
                                    <option value="below-image"><?php esc_html_e( 'Below image', 'videofly' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label><?php esc_html_e( 'Enable carousel', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="radio" name="list-portfolios-thumbnail-enable-carousel" id="list-portfolios-thumbnail-enable-carousel-y" value="y" />
                                <label for="list-portfolios-thumbnail-enable-carousel-y"><?php esc_html_e( 'Yes', 'videofly' ); ?></label>
                                <input type="radio" name="list-portfolios-thumbnail-enable-carousel" id="list-portfolios-thumbnail-enable-carousel-n" value="n"  checked = "checked"  />
                                <label for="list-portfolios-thumbnail-enable-carousel-n"><?php esc_html_e( 'No', 'videofly' ); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-portfolios-thumbnail-posts-per-row"><?php esc_html_e( 'Number of posts per row', 'videofly' ); ?>:</label>
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
                                <label for="list-portfolios-thumbnail-limit"><?php esc_html_e( 'How many posts to extract', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="text" value="" name="list-portfolios-thumbnail-limit"  id="list-portfolios-thumbnail-limit" size="4"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-portfolios-thumbnail-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-portfolios-thumbnail-order-by" id="list-portfolios-thumbnail-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-portfolios-thumbnail-order-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-portfolios-thumbnail-order-direction" id="list-portfolios-thumbnail-order-direction">
                                    <option value="ASC"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option value="DESC" selected="selected"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-portfolios-thumbnail-special-effects"><?php esc_html_e( 'Special effects:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="list-portfolios-thumbnail-special-effects" id="list-portfolios-thumbnail-special-effects">
                                    <option value="none"><?php esc_html_e('No effect', 'videofly') ?></option>
                                    <option value="opacited"><?php esc_html_e('Fade in', 'videofly') ?></option>
                                    <option value="rotate-in"><?php esc_html_e('Rotate in', 'videofly') ?></option>
                                    <option value="3dflip"><?php esc_html_e('3d flip', 'videofly') ?></option>
                                    <option value="scaler"><?php esc_html_e('Scale' , 'videofly') ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-portfolios-thumbnail-gutter"><?php esc_html_e( 'Remove gutter(space) between articles:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="list-portfolios-thumbnail-gutter" id="list-portfolios-thumbnail-gutter">
                                    <option value="n"><?php esc_html_e('No', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Yes', 'videofly') ?></option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="list-portfolios-big-post hidden">
                    <table cellpadding="10">
                        <tr>
                            <td>
                                <label for="list-portfolios-big-post-show-meta"><?php esc_html_e( 'Show meta:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <input type="radio" name="list-portfolios-big-post-show-meta" id="list-portfolios-big-post-show-meta-y" value="y"  checked = "checked"  />
                                <label for="list-portfolios-big-post-show-meta-y"><?php esc_html_e( 'Yes', 'videofly' ); ?></label>
                                <input type="radio" name="list-portfolios-big-post-show-meta" id="list-portfolios-big-post-show-meta-n" value="n" />
                                <label for="list-portfolios-big-post-show-meta-n"><?php esc_html_e( 'No', 'videofly' ); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-portfolios-big-post-nr-of-posts"><?php esc_html_e( 'How many posts to extract:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <input type="text" value="" name="list-portfolios-big-post-nr-of-posts" id="list-portfolios-big-post-nr-of-posts" size="4"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php esc_html_e( 'Content split', 'videofly' ); ?>
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
                                <label for="list-portfolios-big-post-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-portfolios-big-post-order-by" id="list-portfolios-big-post-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-portfolios-big-post-order-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-portfolios-big-post-order-direction" id="list-portfolios-big-post-order-direction">
                                    <option value="ASC"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option selected="selected" value="DESC"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-portfolios-big-post-show-related"><?php esc_html_e( 'Show related posts', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="radio" name="list-portfolios-big-post-show-related" id="list-portfolios-big-post-show-related-y" value="y"/>
                                <label for="list-portfolios-big-post-show-related-y"><?php esc_html_e( 'Yes', 'videofly' ); ?></label>
                                <input type="radio" checked = "checked" name="list-portfolios-big-post-show-related" id="list-portfolios-big-post-show-related-n" value="n" />
                                <label for="list-portfolios-big-post-show-related-n"><?php esc_html_e( 'No', 'videofly' ); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-portfolios-big-post-special-effects"><?php esc_html_e( 'Special effects:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="list-portfolios-big-post-special-effects" id="list-portfolios-big-post-special-effects">
                                    <option value="none"><?php esc_html_e('No effect', 'videofly') ?></option>
                                    <option value="opacited"><?php esc_html_e('Fade in', 'videofly') ?></option>
                                    <option value="rotate-in"><?php esc_html_e('Rotate in', 'videofly') ?></option>
                                    <option value="3dflip"><?php esc_html_e('3d flip', 'videofly') ?></option>
                                    <option value="scaler"><?php esc_html_e('Scale', 'videofly') ?></option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="list-portfolios-super-post hidden">
                    <table cellpadding="10">
                        <tr>
                            <td>
                                <label for="list-portfolios-super-post-posts-per-row"><?php esc_html_e( 'Number of posts per row', 'videofly' ); ?>:</label>
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
                                <label for="list-portfolios-super-post-limit"><?php esc_html_e( 'How many posts to extract', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="text" value="" name="list-portfolios-super-post-limit"  id="list-portfolios-super-post-limit" size="4"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-portfolios-super-post-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-portfolios-super-post-order-by" id="list-portfolios-super-post-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                 <label for="list-portfolios-super-post-order-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-portfolios-super-post-order-direction" id="list-portfolios-super-post-order-direction">
                                    <option value="ASC"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option selected="selected" value="DESC"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-portfolios-super-post-special-effects"><?php esc_html_e( 'Special effects:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="list-portfolios-super-post-special-effects" id="list-portfolios-super-post-special-effects">
                                    <option value="none"><?php esc_html_e('No effect', 'videofly') ?></option>
                                    <option value="opacited"><?php esc_html_e('Fade in', 'videofly') ?></option>
                                    <option value="rotate-in"><?php esc_html_e('Rotate in', 'videofly') ?></option>
                                    <option value="3dflip"><?php esc_html_e('3d flip', 'videofly') ?></option>
                                    <option value="scaler"><?php esc_html_e('Scale', 'videofly') ?></option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- mosaic options -->
                <div class="list-portfolios-mosaic hidden">

                    <table cellpadding="10">
                        <tr class="list-portfolios-mosaic-layout">
                            <td>
                                <label for="list-portfolios-mosaic-layout"><?php esc_html_e( 'Choose how to show the posts', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-portfolios-mosaic-layout" id="list-portfolios-mosaic-layout" class="ts-mosaic-layout">
                                    <option value="rectangles"><?php esc_html_e( 'Rectangles', 'videofly' ); ?></option>
                                    <option value="square"><?php esc_html_e( 'Squares', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose how to show the posts', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="list-portfolios-mosaic-rows">
                            <td>
                                <label for="list-portfolios-mosaic-rows"><?php esc_html_e( 'Change number of rows', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-portfolios-mosaic-rows" id="list-portfolios-mosaic-rows" class="ts-mosaic-rows">
                                    <option value="2"><?php esc_html_e( '2', 'videofly' ); ?></option>
                                    <option value="3"><?php esc_html_e( '3', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Change number of rows', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="list-portfolios-mosaic-nr-of-posts">
                            <td>
                                <label for="list-portfolios-mosaic-post-limit"><?php esc_html_e( 'How many posts to extract', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <div class="ts-mosaic-post-limit-rows-2">
                                    <input class="ts-input-slider" type="text" name="list-portfolios-mosaic-post-limit-rows-2" id="list-portfolios-mosaic-post-limit-rows-2" value="6" disabled />
                                    <div id="list-portfolios-mosaic-post-limit-rows-2-slider"></div>
                                </div>
                                <div class="ts-mosaic-post-limit-rows-3">
                                    <input type="text" name="list-portfolios-mosaic-post-limit-rows-3" id="list-portfolios-mosaic-post-limit-rows-3" value="" disabled />
                                    <div id="list-portfolios-mosaic-post-limit-rows-3-slider"></div>
                                </div>
                                <div class="ts-mosaic-post-limit-squares">
                                    <input type="text" name="list-portfolios-mosaic-post-limit-rows-squares" id="list-portfolios-mosaic-post-limit-rows-squares" value="" disabled />
                                    <div id="list-portfolios-mosaic-post-limit-rows-squares-slider"></div>
                                </div>

                                <div class="ts-option-description">
                                    <?php esc_html_e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-portfolios-mosaic-scroll"><?php esc_html_e( 'Add/remove scroll', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-portfolios-mosaic-scroll" id="list-portfolios-mosaic-scroll">
                                    <option selected="selected" value="y"><?php esc_html_e( 'With scroll', 'videofly' ); ?></option>
                                    <option value="n"><?php esc_html_e( 'Without scroll', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Add/remove scroll', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-portfolios-mosaic-effects"><?php esc_html_e( 'Add effects to scroll', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-portfolios-mosaic-effects" id="list-portfolios-mosaic-effects">
                                    <option value="default"><?php esc_html_e( 'Default', 'videofly' ); ?></option>
                                    <option value="fade"><?php esc_html_e( 'Fade in effect', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Change number of rows', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-portfolios-mosaic-gutter"><?php esc_html_e( 'Add or Remove gutter between posts', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-portfolios-mosaic-gutter" id="list-portfolios-mosaic-gutter">
                                    <option value="y"><?php esc_html_e( 'With gutter between posts', 'videofly' ); ?></option>
                                    <option value="n"><?php esc_html_e( 'No gutter', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Add or Remove gutter between posts', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-portfolios-mosaic-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-portfolios-mosaic-order-by" id="list-portfolios-mosaic-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                    <option value="likes"><?php esc_html_e( 'Likes', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-portfolios-mosaic-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-portfolios-mosaic-order-direction" id="list-portfolios-mosaic-order-direction">
                                    <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option value="desc" selected="selected"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="class-list-portfolios-mosaic-pagination">
                            <td>
                                <label for="list-portfolios-mosaic-pagination"><?php esc_html_e( 'Enable pagination', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-portfolios-mosaic-pagination" id="list-portfolios-mosaic-pagination">
                                    <option selected="selected" value="n"><?php esc_html_e('None', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Enable pagination', 'videofly') ?></option>
                                    <option value="load-more"><?php esc_html_e('Load more', 'videofly') ?></option>
                                    <option value="infinite"><?php esc_html_e('Infinite scrolling', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can add pagination.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

            </div>
        </div>
        <?php endif; ?>
    <?php break ?>
    <?php case 'testimonials': ?>
        <div class="testimonials builder-element">
            <h3 class="element-title"><?php esc_html_e('Testimonials', 'videofly'); ?></h3>
            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="testimonials-admin-label"><?php esc_html_e('Admin label','videofly');?>:</label>
                    </td>
                    <td>
                       <input type="text" id="testimonials-admin-label" name="testimonials-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="testimonials-row"><?php esc_html_e( 'Number of testimonials per row:', 'videofly' ); ?></label>
                    </td>
                    <td>
                        <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#testimonials-row" id="testimonials-row-selector">
                           <li><img class="image-radio-input clickable-element" data-option="1" src="<?php echo get_template_directory_uri().'/images/options/per_row_1.png'; ?>"></li>
                           <li><img class="image-radio-input clickable-element" data-option="2" src="<?php echo get_template_directory_uri().'/images/options/per_row_2.png'; ?>"></li>
                           <li><img class="image-radio-input clickable-element" data-option="3" src="<?php echo get_template_directory_uri().'/images/options/per_row_3.png'; ?>"></li>
                           <li><img class="image-radio-input clickable-element" data-option="4" src="<?php echo get_template_directory_uri().'/images/options/per_row_4.png'; ?>"></li>
                           <li><img class="image-radio-input clickable-element" data-option="6" src="<?php echo get_template_directory_uri().'/images/options/per_row_6.png'; ?>"></li>
                        </ul>
                        <select name="testimonials-row" id="testimonials-row" class="hidden">
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
                        <label for="testimonials-enable-carousel"><?php esc_html_e( 'Enable carousel', 'videofly' ); ?>:</label>
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
            <input type="button" class="button ts-multiple-add-button" data-element-name="testimonials" id="testimonials_add_item" value=" <?php esc_html_e('Add New Testimonial', 'videofly'); ?>" />
            <?php
                echo '<script id="testimonials_items_template" type="text/template">';
                echo '<li id="list-item-id-{{item-id}}" class="testimonials-item ts-multiple-add-list-element">
                        <div class="sortable-meta-element"><span class="tab-arrow icon-down"></span> <span class="testimonials-item-tab ts-multiple-item-tab">Item: {{slide-number}}</span></div>
                        <div class="hidden">
                            <table>
                                <tr>
                                    <td>' . esc_html__( "Testimonial image",'videofly' ) .'</td>
                                    <td>
                                        <input type="text" name="testimonials-{{item-id}}-image" id="testimonials-{{item-id}}-image" value="" data-role="media-url" />
                                        <input type="hidden" id="slide_media_id-{{item-id}}" name="testimonials_media_id-{{item-id}}" value=""  data-role="media-id" />
                                        <input type="button" id="uploader_{{item-id}}" data-element-name="testimonials"  class="ts-secondary-button" value="' . esc_html__( "Upload",'videofly' ) . '" />
                                        <div id="image-preview-{{item-id}}"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="testimonials-{{item-id}}-text">' . esc_html__( "Write your text here:",'videofly' ) . '</label>
                                    </td>
                                    <td>
                                        <textarea data-builder-name="text" name="testimonials[{{item-id}}][text]" id="testimonials-{{item-id}}-text" cols="45" rows="5"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="testimonials-{{item-id}}-name">' . esc_html__( "Enter your person name:",'videofly' ) . '</label>
                                    </td>
                                    <td>
                                        <input data-builder-name="name" type="text" id="testimonials-{{item-id}}-name" name="testimonials[{{item-id}}][name]" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="testimonials-{{item-id}}-company">' . esc_html__( "Enter company name:",'videofly' ) . '</label>
                                    </td>
                                    <td>
                                        <input data-builder-name="company" type="text" id="testimonials-{{item-id}}-company" name="testimonials[{{item-id}}][company]" />
                                    </td>
                                </tr>
                                 <tr>
                                    <td>
                                        <label for="testimonials-{{item-id}}-url">' . esc_html__( "Enter your url here:",'videofly' ) . '</label>
                                    </td>
                                    <td>
                                        <input data-builder-name="url" type="text" id="testimonials-{{item-id}}-url" name="testimonials[{{item-id}}][url]" />
                                    </td>
                                </tr>
                            </table>
                            <input type="hidden" data-builder-name="item_id" value="{{item-id}}" name="testimonials[{{item-id}}][id]" />
                            <input type="button" class="ts-secondary-button remove-item" value="' . esc_html__('Remove', 'videofly') . '" />
                            <a href="#" data-item="testimonials-item" data-element-name="testimonials" data-increment="testimonials-items" class="ts-secondary-button ts-multiple-item-duplicate">' . esc_html__('Duplicate Item', 'videofly') . '</a>
                        </div>
                    </li>';
                echo '</script>';
           ?>

        </div>
    <?php break ?>
    <?php case 'last-posts': ?>
        <?php if( $is_plugin_active ) : ?>
        <div class="last-posts builder-element">
            <h3 class="element-title"><?php esc_html_e('List posts','videofly');?></h3>
            <!-- Select category -->
            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="last-posts-admin-label"><?php esc_html_e('Admin label','videofly');?>:</label>
                    </td>
                    <td>
                       <input type="text" id="last-posts-admin-label" name="last-posts-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <label for="last-posts-category"><?php esc_html_e( 'Category', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <select data-placeholder="<?php esc_html_e('Select your category', 'videofly'); ?>" class="ts-custom-select-input" name="last-posts-category" id="last-posts-category" multiple>
                            <?php $categories = get_categories(array( 'hide_empty' => 0, 'show_option_all' => '' )); ?>
                            <?php if ( isset($categories) && is_array($categories) && !empty($categories) ) : ?>
                                <?php $i = 1; foreach ($categories as $index => $category): ?>
                                    <?php if( is_object($category) ) : ?>
                                        <?php if( $i === 1 ) echo '<option value="0">' . esc_html__('All', 'videofly') . '</option>'; ?>
                                        <option value="<?php echo vdf_var_sanitize($category->slug ); ?>"><?php echo vdf_var_sanitize($category->cat_name ); ?></option>
                                    <?php endif; $i++; ?>
                                <?php endforeach ?>
                            <?php endif ?>
                        </select>
                        <div class="ts-option-description">
                            <?php esc_html_e('Choose the categories that you want to showcase articles from.', 'videofly'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label><?php esc_html_e( 'Show only featured', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <select name="last-posts-featured" id="last-posts-featured">
                            <option value="y"><?php esc_html_e( 'Yes', 'videofly' ); ?></option>
                            <option value="n" selected="selected"><?php esc_html_e( 'No', 'videofly' ); ?></option>
                        </select>

                        <div class="ts-option-description">
                            <?php esc_html_e('You can display only featured posts', 'videofly'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="last-posts-exclude"><?php esc_html_e( 'Exclude post IDs', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <input type="text" value="" name="last-posts-exclude" id="last-posts-exclude" size="4"/>
                        <div class="ts-option-description">
                            <?php esc_html_e('Insert the IDs of the posts you want to exclude from showing.', 'videofly'); ?> (ex: <b>100,101,102,104</b>)
                        </div>
                    </td>
                </tr>
                 <tr class="last-posts-exclude">
                    <td>
                        <label for="last-posts-exclude-first"><?php esc_html_e( 'Exclude number of first posts', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <input type="text" value="" name="last-posts-exclude-first" id="last-posts-exclude-first" size="4"/>
                        <div class="ts-option-description">
                            <?php esc_html_e('Insert the number of the posts you want to exclude from showing.', 'videofly'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="last-posts-display-mode"><?php esc_html_e( 'How to display', 'videofly' ); ?>:</label>
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
                        <select class="hidden" name="last-posts-display-mode" id="last-posts-display-mode">
                            <option value="grid" selected="selected"><?php esc_html_e( 'Grid', 'videofly' ); ?></option>
                            <option value="list"><?php esc_html_e( 'List', 'videofly' ); ?></option>
                            <option value="thumbnails"><?php esc_html_e( 'Thumbnails', 'videofly' ); ?></option>
                            <option value="big-post"><?php esc_html_e( 'Big post', 'videofly' ); ?></option>
                            <option value="super-post"><?php esc_html_e( 'Super Post', 'videofly' ); ?></option>
                            <option value="timeline"><?php esc_html_e( 'Timeline Post', 'videofly' ); ?></option>
                            <option value="mosaic"><?php esc_html_e( 'mosaic Post', 'videofly' ); ?></option>
                        </select>
                        <div class="ts-option-description">
                            <?php esc_html_e('Choose your article view type. Depending on what type of article showcase layout you select you will get different options. You can read more about view types in our documentation files: ', 'videofly'); echo vdf_var_sanitize($touchsize_com); ?>
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
                                <label><?php esc_html_e( 'Behavior', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#last-posts-grid-behavior" id="last-posts-grid-behavior-selector">
                                   <li><img class="image-radio-input clickable-element" data-option="none" src="<?php echo get_template_directory_uri().'/images/options/behavior_none.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="carousel" src="<?php echo get_template_directory_uri().'/images/options/behavior_carousel.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="masonry" src="<?php echo get_template_directory_uri().'/images/options/behavior_masonry.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="scroll" src="<?php echo get_template_directory_uri().'/images/options/behavior_scroll.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="tabbed" src="<?php echo get_template_directory_uri().'/images/options/behavior_tabs.png'; ?>"></li>
                                </ul>
                                <select class="hidden" name="last-posts-grid-behavior" id="last-posts-grid-behavior">
                                    <option value="none"><?php esc_html_e( 'Normal', 'videofly' ); ?></option>
                                    <option value="carousel"><?php esc_html_e( 'Carousel', 'videofly' ); ?></option>
                                    <option value="masonry"><?php esc_html_e( 'Masonry', 'videofly' ); ?></option>
                                    <option value="scroll"><?php esc_html_e( 'Scroll', 'videofly' ); ?></option>
                                    <option value="tabbed"><?php esc_html_e( 'Tabbed', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your posts behavior - you can have them just shown, or you can activate the carousel. If carousel is selected your articles will show up in a line with arrows for navigation. If masonry bahevior is selected - your articles will be arranged in to fit it. Be aware that activating the masonry option the crop settings for image sizes tab will be overwritten.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-grid-image"><?php esc_html_e( 'Show featured image', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-grid-image" id="last-posts-grid-image">
                                    <option selected="selected" value="y"><?php esc_html_e( 'Yes', 'videofly' ); ?></option>
                                    <option value="n"><?php esc_html_e( 'No', 'videofly' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-grid-title"><?php esc_html_e( 'Title position', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#last-posts-grid-title" id="last-posts-grid-title-selector">
                                   <li><img class="image-radio-input clickable-element" data-option="title-above-image" src="<?php echo get_template_directory_uri().'/images/options/grid_view_title_image.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="title-below-image" src="<?php echo get_template_directory_uri().'/images/options/grid_view_title_excerpt.png'; ?>"></li>
                                </ul>
                                <select class="hidden" name="last-posts-grid-title" id="last-posts-grid-title">
                                    <option value="title-above-image"><?php esc_html_e( 'Above image', 'videofly' ); ?></option>
                                    <option value="title-below-image" selected="selected"><?php esc_html_e( 'Above excerpt', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Select your title position for you grid posts. You can either have it above the image of above the excerpt. Note that sometimes title may change the position of the meta: date, categories, author as well.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-grid-show-meta"><?php esc_html_e( 'Show meta', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="radio" name="last-posts-grid-show-meta" id="last-posts-grid-show-meta-y" value="y"  checked = "checked"  />
                                <label for="last-posts-grid-show-meta-y"><?php esc_html_e( 'Yes', 'videofly' ); ?></label>
                                <input type="radio" name="last-posts-grid-show-meta" id="last-posts-grid-show-meta-n" value="n" />
                                <label for="last-posts-grid-show-meta-n"><?php esc_html_e( 'No', 'videofly' ); ?></label>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can choose to show or to hide your meta details for your articles. Meta values include date, author, categories and other article details.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-grid-el-per-row"><?php esc_html_e( 'Elements per row', 'videofly' ); ?>:</label>
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
                                    <?php esc_html_e('Your articles will be shown columns. Choose how mane columns do you want to use per line. Note that for mobile devices you will get only ONE element per row for better usability.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="last-posts-grid-nr-of-posts">
                            <td>
                                <label for="last-posts-grid-nr-of-posts"><?php esc_html_e( 'How many posts to extract', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="text" value="" name="last-posts-grid-nr-of-posts" id="last-posts-grid-nr-of-posts" size="4"/>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-grid-related"><?php esc_html_e( 'Show related posts', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-grid-related" id="last-posts-grid-related">
                                    <option value="y"><?php esc_html_e( 'Yes', 'videofly' ); ?></option>
                                    <option selected="selected" value="n"><?php esc_html_e( 'No', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can set each of your big posts to show related articles below. A list with other articles will appear below.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-grid-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-grid-order-by" id="last-posts-grid-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                    <option value="likes"><?php esc_html_e( 'Likes', 'videofly' ); ?></option>
                                    <option value="rand"><?php esc_html_e( 'Random', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-grid-order-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-grid-order-direction" id="last-posts-grid-order-direction">
                                    <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option selected="selected" value="desc" selected="selected"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-grid-special-effects"><?php esc_html_e( 'Special effects', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-grid-special-effects" id="last-posts-grid-special-effects">
                                    <option value="none"><?php esc_html_e('No effect', 'videofly') ?></option>
                                    <option value="opacited"><?php esc_html_e('Fade in', 'videofly') ?></option>
                                    <option value="rotate-in"><?php esc_html_e('Rotate in', 'videofly') ?></option>
                                    <option value="3dflip"><?php esc_html_e('3d flip', 'videofly') ?></option>
                                    <option value="scaler"><?php esc_html_e('Scale', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="last-posts-grid-pagination">
                            <td>
                                <label for="last-posts-grid-pagination"><?php esc_html_e( 'Enable pagination', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-grid-pagination" id="last-posts-grid-pagination">
                                    <option selected="selected" value="n"><?php esc_html_e('None', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Enable pagination', 'videofly') ?></option>
                                    <option value="load-more"><?php esc_html_e('Load more', 'videofly') ?></option>
                                    <option value="infinite"><?php esc_html_e('Infinite scrolling', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can add pagination.', 'videofly'); ?>
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
                                <label for="last-posts-list-title"><?php esc_html_e( 'Title:', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-list-title" id="last-posts-list-title">
                                    <option value="title-above-image"><?php esc_html_e( 'Above image', 'videofly' ); ?></option>
                                    <option value="title-below-image"><?php esc_html_e( 'Above excerpt', 'videofly' ); ?></option>
                                </select>
                            </td>
                        </tr> -->
                        <tr>
                            <td>
                                <label for="last-posts-list-image"><?php esc_html_e( 'Show featured image', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-list-image" id="last-posts-list-image">
                                    <option selected="selected" value="y"><?php esc_html_e( 'Yes', 'videofly' ); ?></option>
                                    <option value="n"><?php esc_html_e( 'No', 'videofly' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-list-show-meta"><?php esc_html_e( 'Show meta', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="radio" name="last-posts-list-show-meta" id="last-posts-list-show-meta-y" value="y"  checked = "checked"  />
                                <label for="last-posts-list-show-meta-y"><?php esc_html_e( 'Yes', 'videofly' ); ?></label>
                                <input selected="selected" type="radio" name="last-posts-list-show-meta" id="last-posts-list-show-meta-n" value="n" />
                                <label for="last-posts-list-show-meta-n"><?php esc_html_e( 'No', 'videofly' ); ?></label>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can choose to show or to hide your meta details for your articles. Meta values include date, author, categories and other article details.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="last-posts-list-nr-of-posts">
                            <td>
                                <label for="last-posts-list-nr-of-posts"><?php esc_html_e( 'How many posts to extract', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="text" value="" name="last-posts-list-nr-of-posts" id="last-posts-list-nr-of-posts" size="4"/>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <!-- <tr>
                            <td>
                                <?php //esc_html_e( 'Content split', 'videofly' ); ?>
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
                                    <?php //esc_html_e('Choose your title/content split proportions. You can have them either 1/3, 1/2, 3/4 for your title and 2/3,1/2, 1/4 accordingly. Depending on the text and titles you use, select your split type.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr> -->
                        <tr>
                            <td>
                                <label for="last-posts-list-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-list-order-by" id="last-posts-list-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                    <option value="likes"><?php esc_html_e( 'Likes', 'videofly' ); ?></option>
                                    <option value="rand"><?php esc_html_e( 'Random', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-list-order-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-list-order-direction" id="last-posts-list-order-direction">
                                    <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option value="desc" selected="selected"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-list-special-effects"><?php esc_html_e( 'Special effects:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="last-posts-list-special-effects" id="last-posts-list-special-effects">
                                    <option value="none"><?php esc_html_e('No effect', 'videofly') ?></option>
                                    <option value="opacited"><?php esc_html_e('Fade in', 'videofly') ?></option>
                                    <option value="rotate-in"><?php esc_html_e('Rotate in', 'videofly') ?></option>
                                    <option value="3dflip"><?php esc_html_e('3d flip', 'videofly') ?></option>
                                    <option value="scaler"><?php esc_html_e('Scale', 'videofly') ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-list-pagination"><?php esc_html_e( 'Enable pagination', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-list-pagination" id="last-posts-list-pagination">
                                    <option selected="selected" value="n"><?php esc_html_e('None', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Enable pagination', 'videofly') ?></option>
                                    <option value="load-more"><?php esc_html_e('Load more', 'videofly') ?></option>
                                    <option value="infinite"><?php esc_html_e('Infinite scrolling', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can add pagination.', 'videofly'); ?>
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
                                <label for="last-posts-thumbnail-title"><?php esc_html_e( 'Title position', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-thumbnail-title" id="last-posts-thumbnail-title">
                                    <option value="over-image" selected="selected"><?php esc_html_e( 'Over image', 'videofly' ); ?></option>
                                    <option value="below-image"><?php esc_html_e( 'Below image', 'videofly' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label><?php esc_html_e( 'Behavior', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#last-posts-thumbnails-behavior" id="last-posts-thumbnails-behavior-selector">
                                   <li><img class="image-radio-input clickable-element" data-option="none" src="<?php echo get_template_directory_uri().'/images/options/behavior_none.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="carousel" src="<?php echo get_template_directory_uri().'/images/options/behavior_carousel.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="masonry" src="<?php echo get_template_directory_uri().'/images/options/behavior_masonry.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="scroll" src="<?php echo get_template_directory_uri().'/images/options/behavior_scroll.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="tabbed" src="<?php echo get_template_directory_uri().'/images/options/behavior_tabs.png'; ?>"></li>
                                </ul>
                                <select name="last-posts-thumbnails-behavior" id="last-posts-thumbnails-behavior" class="hidden">
                                    <option value="none"><?php esc_html_e( 'Normal', 'videofly' ); ?></option>
                                    <option value="carousel"><?php esc_html_e('Carousel', 'videofly') ?></option>
                                    <option value="masonry"><?php esc_html_e('Masonry', 'videofly') ?></option>
                                    <option value="scroll"><?php esc_html_e('Scroll', 'videofly') ?></option>
                                    <option value="tabbed"><?php esc_html_e('Tabbed', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your posts behavior - you can have them just shown, or you can activate the carousel. If carousel is selected your articles will show up in a line with arrows for navigation. If masonry bahevior is selected - your articles will be arranged in to fit it. Be aware that activating the masonry option the crop settings for image sizes tab will be overwritten.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-thumbnail-posts-per-row"><?php esc_html_e( 'Number of posts per row', 'videofly' ); ?>:</label>
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
                                    <?php esc_html_e('Your articles will be shown columns. Choose how mane columns do you want to use per line. Note that for mobile devices you will get only ONE element per row for better usability.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="last-posts-thumbnails-nr-of-posts">
                            <td>
                                <label for="last-posts-thumbnail-limit"><?php esc_html_e( 'How many posts to extract', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="text" value="" name="last-posts-thumbnail-limit"  id="last-posts-thumbnail-limit" size="4"/>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                         <tr>
                            <td>
                                <label for="last-posts-thumbnail-show-meta"><?php esc_html_e( 'Show meta:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <input type="radio" name="last-posts-thumbnail-show-meta" id="last-posts-thumbnail-show-meta-y" value="y"  checked = "checked"  />
                                <label for="last-posts-thumbnail-show-meta-y"><?php esc_html_e( 'Yes', 'videofly' ); ?></label>
                                <input type="radio" name="last-posts-thumbnail-show-meta" id="last-posts-thumbnail-show-meta-n" value="n" />
                                <label for="last-posts-thumbnail-show-meta-n"><?php esc_html_e( 'No', 'videofly' ); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-thumbnail-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-thumbnail-order-by" id="last-posts-thumbnail-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                    <option value="likes"><?php esc_html_e( 'Likes', 'videofly' ); ?></option>
                                    <option value="rand"><?php esc_html_e( 'Random', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-thumbnail-order-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-thumbnail-order-direction" id="last-posts-thumbnails-order-direction">
                                    <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option value="desc" selected="selected"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-thumbnail-special-effects"><?php esc_html_e( 'Special effects:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="last-posts-thumbnail-special-effects" id="last-posts-thumbnail-special-effects">
                                    <option value="none"><?php esc_html_e('No effect', 'videofly') ?></option>
                                    <option value="opacited"><?php esc_html_e('Fade in', 'videofly') ?></option>
                                    <option value="rotate-in"><?php esc_html_e('Rotate in', 'videofly') ?></option>
                                    <option value="3dflip"><?php esc_html_e('3d flip', 'videofly') ?></option>
                                    <option value="scaler"><?php esc_html_e('Scale' , 'videofly') ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-thumbnail-gutter"><?php esc_html_e( 'Remove gutter(space) between articles:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="last-posts-thumbnail-gutter" id="last-posts-thumbnail-gutter">
                                    <option value="n"><?php esc_html_e('No', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Yes', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Gutter is the space between your articles. You can remove the space and have your articles sticked one to another.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="last-posts-thumbnails-pagination">
                            <td>
                                <label for="last-posts-thumbnails-pagination"><?php esc_html_e( 'Enable pagination', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-thumbnails-pagination" id="last-posts-thumbnails-pagination">
                                    <option selected="selected" value="n"><?php esc_html_e('None', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Enable pagination', 'videofly') ?></option>
                                    <option value="load-more"><?php esc_html_e('Load more', 'videofly') ?></option>
                                    <option value="infinite"><?php esc_html_e('Infinite scrolling', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can add pagination.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="last-posts-big-post hidden">
                    <table cellpadding="10">
                        <tr>
                            <td>
                                <label for="last-posts-big-post-image"><?php esc_html_e( 'Show featured image', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-big-post-image" id="last-posts-big-post-image">
                                    <option selected="selected" value="y"><?php esc_html_e( 'Yes', 'videofly' ); ?></option>
                                    <option value="n"><?php esc_html_e( 'No', 'videofly' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-big-post-carousel"><?php esc_html_e( 'Show carousel', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="last-posts-big-post-carousel" id="last-posts-big-post-carousel">
                                    <option value="y"><?php esc_html_e('Yes', 'videofly') ?></option>
                                    <option selected="selected" value="n"><?php esc_html_e('No', 'videofly') ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-big-post-excerpt"><?php esc_html_e( 'Show excerpt', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="last-posts-big-post-excerpt" id="last-posts-big-post-excerpt">
                                    <option selected="selected" value="y"><?php esc_html_e('Yes', 'videofly') ?></option>
                                    <option value="n"><?php esc_html_e('No', 'videofly') ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-big-post-image-position"><?php esc_html_e( 'Image position:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="last-posts-big-post-image-position" id="last-posts-big-post-image-position">
                                    <option value="left"><?php esc_html_e( 'Left', 'videofly' ); ?></option>
                                    <option value="right"><?php esc_html_e( 'Right', 'videofly' ); ?></option>
                                    <option value="mosaic"><?php esc_html_e( 'Mosaic', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('The way you want the big posts to be shown', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-big-post-show-meta"><?php esc_html_e( 'Show meta:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <input type="radio" name="last-posts-big-post-show-meta" id="last-posts-big-post-show-meta-y" value="y" checked="checked" />
                                <label for="last-posts-big-post-show-meta-y"><?php esc_html_e( 'Yes', 'videofly' ); ?></label>
                                <input type="radio" name="last-posts-big-post-show-meta" id="last-posts-big-post-show-meta-n" value="n"/>
                                <label for="last-posts-big-post-show-meta-n"><?php esc_html_e( 'No', 'videofly' ); ?></label>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can choose to show or to hide your meta details for your articles. Meta values include date, author, categories and other article details.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="last-posts-big-post-nr-of-posts">
                            <td>
                                <label for="last-posts-big-post-nr-of-posts"><?php esc_html_e( 'How many posts to extract:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <input type="text" value="" name="last-posts-big-post-nr-of-posts" id="last-posts-big-post-nr-of-posts" size="4"/>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php esc_html_e( 'Content split', 'videofly' ); ?>
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
                                    <?php esc_html_e('Choose your image/content split proportions. You can have them either 1/3, 1/2, 3/4 for your title and 2/3,1/2, 1/4 accordingly. Depending on the text and titles you use, select your split type.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-big-post-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-big-post-order-by" id="last-posts-big-post-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                    <option value="likes"><?php esc_html_e( 'Likes', 'videofly' ); ?></option>
                                    <option value="rand"><?php esc_html_e( 'Random', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-big-post-order-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-big-post-order-direction" id="last-posts-big-post-order-direction">
                                    <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option selected="selected" value="desc"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-big-post-related"><?php esc_html_e( 'Show related posts', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-big-post-related" id="last-posts-big-post-related">
                                    <option value="y"><?php esc_html_e( 'Yes', 'videofly' ); ?></option>
                                    <option selected="selected" value="n"><?php esc_html_e( 'No', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can set each of your big posts to show related articles below. A list with other articles will appear below.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-big-post-special-effects"><?php esc_html_e( 'Special effects', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-big-post-special-effects" id="last-posts-big-post-special-effects">
                                    <option value="none"><?php esc_html_e('No effect', 'videofly') ?></option>
                                    <option value="opacited"><?php esc_html_e('Fade in', 'videofly') ?></option>
                                    <option value="rotate-in"><?php esc_html_e('Rotate in', 'videofly') ?></option>
                                    <option value="3dflip"><?php esc_html_e('3d flip', 'videofly') ?></option>
                                    <option value="scaler"><?php esc_html_e('Scale', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="last-posts-big-post-pagination">
                            <td>
                                <label for="last-posts-big-post-pagination"><?php esc_html_e( 'Enable pagination', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-big-post-pagination" id="last-posts-big-post-pagination">
                                    <option selected="selected" value="n"><?php esc_html_e('None', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Enable pagination', 'videofly') ?></option>
                                    <option value="load-more"><?php esc_html_e('Load more', 'videofly') ?></option>
                                    <option value="infinite"><?php esc_html_e('Infinite scrolling', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can add pagination.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="last-posts-super-post hidden">
                    <table cellpadding="10">
                        <tr>
                            <td>
                                <label for="last-posts-super-post-posts-per-row"><?php esc_html_e( 'Number of posts per row', 'videofly' ); ?>:</label>
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
                                    <?php esc_html_e('Your articles will be shown columns. Choose how mane columns do you want to use per line. Note that for mobile devices you will get only ONE element per row for better usability.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="last-posts-big-post-nr-of-posts">
                            <td>
                                <label for="last-posts-super-post-limit"><?php esc_html_e( 'How many posts to extract', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="text" value="" name="last-posts-super-post-limit"  id="last-posts-super-post-limit" size="4"/>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-super-post-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-super-post-order-by" id="last-posts-super-post-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                    <option value="likes"><?php esc_html_e( 'Likes', 'videofly' ); ?></option>
                                    <option value="rand"><?php esc_html_e( 'Random', 'videofly' ); ?></option>
                                </select>
                                 <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                 <label for="last-posts-super-post-order-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-super-post-order-direction" id="last-posts-super-post-order-direction">
                                    <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option selected="selected" value="desc"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-super-post-special-effects"><?php esc_html_e( 'Special effects:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="last-posts-super-post-special-effects" id="last-posts-super-post-special-effects">
                                    <option value="none"><?php esc_html_e('No effect', 'videofly') ?></option>
                                    <option value="opacited"><?php esc_html_e('Fade in', 'videofly') ?></option>
                                    <option value="rotate-in"><?php esc_html_e('Rotate in', 'videofly') ?></option>
                                    <option value="3dflip"><?php esc_html_e('3d flip', 'videofly') ?></option>
                                    <option value="scaler"><?php esc_html_e('Scale', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-super-post-pagination"><?php esc_html_e( 'Enable pagination', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-super-post-pagination" id="last-posts-super-post-pagination">
                                    <option selected="selected" value="n"><?php esc_html_e('None', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Enable pagination', 'videofly') ?></option>
                                    <option value="load-more"><?php esc_html_e('Load more', 'videofly') ?></option>
                                    <option value="infinite"><?php esc_html_e('Infinite scrolling', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can add pagination.', 'videofly'); ?>
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
                                <label for="last-posts-timeline-show-meta"><?php esc_html_e( 'Show meta', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="radio" name="last-posts-timeline-show-meta" id="last-posts-timeline-show-meta-y" value="y"  checked = "checked"  />
                                <label for="last-posts-timeline-show-meta-y"><?php esc_html_e( 'Yes', 'videofly' ); ?></label>
                                <input type="radio" name="last-posts-timeline-show-meta" id="last-posts-timeline-show-meta-n" value="n" />
                                <label for="last-posts-timeline-show-meta-n"><?php esc_html_e( 'No', 'videofly' ); ?></label>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can choose to show or to hide your meta details for your articles. Meta values include date, author, categories and other article details.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-timeline-image"><?php esc_html_e( 'Show image', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-timeline-image" id="last-posts-timeline-image">
                                    <option value="y"><?php esc_html_e( 'Yes', 'videofly' ); ?></option>
                                    <option value="n"><?php esc_html_e( 'No', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Display image', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="last-posts-timeline-nr-of-posts">
                            <td>
                                <label for="last-posts-timeline-post-limit"><?php esc_html_e( 'How many posts to extract', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="text" value="" name="last-posts-timeline-post-limit" id="last-posts-timeline-post-limit" size="4"/>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-timeline-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-timeline-order-by" id="last-posts-timeline-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                    <option value="likes"><?php esc_html_e( 'Likes', 'videofly' ); ?></option>
                                    <option value="rand"><?php esc_html_e( 'Random', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-timeline-order-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-timeline-order-direction" id="last-posts-timeline-order-direction">
                                    <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option value="desc" selected="selected"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-timeline-pagination"><?php esc_html_e( 'Enable pagination', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-timeline-pagination" id="last-posts-timeline-pagination">
                                    <option selected="selected" value="n"><?php esc_html_e('None', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Enable pagination', 'videofly') ?></option>
                                    <option value="load-more"><?php esc_html_e('Load more', 'videofly') ?></option>
                                    <option value="infinite"><?php esc_html_e('Infinite scrolling', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can add pagination.', 'videofly'); ?>
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
                                <label for="last-posts-mosaic-layout"><?php esc_html_e( 'Choose how to show the posts', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-mosaic-layout" id="last-posts-mosaic-layout" class="ts-mosaic-layout">
                                    <option value="rectangles"><?php esc_html_e( 'Rectangles', 'videofly' ); ?></option>
                                    <option value="square"><?php esc_html_e( 'Squares', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose how to show the posts', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="last-posts-mosaic-rows">
                            <td>
                                <label for="last-posts-mosaic-rows"><?php esc_html_e( 'Change number of rows', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-mosaic-rows" id="last-posts-mosaic-rows" class="ts-mosaic-rows">
                                    <option value="2"><?php esc_html_e( '2', 'videofly' ); ?></option>
                                    <option value="3"><?php esc_html_e( '3', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Change number of rows', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="last-posts-mosaic-nr-of-posts">
                            <td>
                                <label for="last-posts-mosaic-post-limit"><?php esc_html_e( 'How many posts to extract', 'videofly' ); ?>:</label>
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
                                    <?php esc_html_e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-mosaic-scroll"><?php esc_html_e( 'Add/remove scroll', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-mosaic-scroll" id="last-posts-mosaic-scroll">
                                    <option selected="selected" value="y"><?php esc_html_e( 'With scroll', 'videofly' ); ?></option>
                                    <option value="n"><?php esc_html_e( 'Without scroll', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Add/remove scroll', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-mosaic-effects"><?php esc_html_e( 'Add effects to scroll', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-mosaic-effects" id="last-posts-mosaic-effects">
                                    <option value="default"><?php esc_html_e( 'Default', 'videofly' ); ?></option>
                                    <option value="fade"><?php esc_html_e( 'Fade in effect', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Change number of rows', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-mosaic-gutter"><?php esc_html_e( 'Add or Remove gutter between posts', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-mosaic-gutter" id="last-posts-mosaic-gutter">
                                    <option value="y"><?php esc_html_e( 'With gutter between posts', 'videofly' ); ?></option>
                                    <option value="n"><?php esc_html_e( 'No gutter', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Add or Remove gutter between posts', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-mosaic-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-mosaic-order-by" id="last-posts-mosaic-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                    <option value="likes"><?php esc_html_e( 'Likes', 'videofly' ); ?></option>
                                    <option value="rand"><?php esc_html_e( 'Random', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="last-posts-mosaic-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-mosaic-order-direction" id="last-posts-mosaic-order-direction">
                                    <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option value="desc" selected="selected"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="class-last-posts-mosaic-pagination">
                            <td>
                                <label for="last-posts-mosaic-pagination"><?php esc_html_e( 'Enable pagination', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="last-posts-mosaic-pagination" id="last-posts-mosaic-pagination">
                                    <option selected="selected" value="n"><?php esc_html_e('None', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Enable pagination', 'videofly') ?></option>
                                    <option value="load-more"><?php esc_html_e('Load more', 'videofly') ?></option>
                                    <option value="infinite"><?php esc_html_e('Infinite scrolling', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can add pagination.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

            </div>
        </div>
        <?php endif; ?>
    <?php break ?>
    <?php case 'callaction': ?>
        <div class="callaction builder-element">
            <h3 class="element-title"><?php esc_html_e('Call to action','videofly'); ?></h3>
            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="callaction-admin-label"><?php esc_html_e('Admin label','videofly');?>:</label>
                    </td>
                    <td>
                       <input type="text" id="callaction-admin-label" name="callaction-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <label for="callaction-text"><?php esc_html_e( 'Call to action Text', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <textarea name="callaction-text" id="callaction-text" style="width:400px; height: 100px" ></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="callaction-link"><?php esc_html_e( 'Button Link', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <input type="text" name="callaction-link" id="callaction-link" style="width:400px" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="callaction-button-text"><?php esc_html_e( 'Button Text', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <input type="text" name="callaction-button-text" id="callaction-button-text" style="width:400px" />
                    </td>
                </tr>
            </table>
        </div>
    <?php break ?>
    <?php case 'teams': ?>
        <?php if( $is_plugin_active ) : ?>
        <div class="teams builder-element">
            <h3 class="element-title"><?php esc_html_e('Team members', 'videofly'); ?></h3>
            <table cellpadding="10">
                        <tr>
                    <td>
                        <label for="teams-admin-label"><?php esc_html_e('Admin label:','videofly'); ?></label>
                    </td>
                    <td>
                       <input type="text" id="teams-admin-label" name="teams-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="teams-category"><?php esc_html_e( 'Category', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <?php $categories = get_categories(array( 'hide_empty' => 1, 'taxonomy' => 'teams', 'pad_counts' => true ));
                        if( isset($categories) && is_array($categories) && !empty($categories) ) : ?>
                            <select class="ts-custom-select-input" data-placeholder="<?php esc_html_e('Select your category', 'videofly'); ?>" name="teams-category" id="teams-category" multiple>
                                <option value="0"><?php esc_html_e('All', 'videofly'); ?></option>
                                <?php foreach ($categories as $index => $category): ?>
                                    <option value="<?php echo vdf_var_sanitize($category->slug ); ?>"><?php echo vdf_var_sanitize($category->cat_name ); ?><span>  (<?php echo vdf_var_sanitize($category->count); ?>)</span></option>
                                <?php endforeach ?>
                            </select>
                        <?php else : ?>
                            <select class="ts-custom-select-input" data-placeholder="<?php esc_html_e('Select your category', 'videofly'); ?>" name="teams-category" id="teams-category" multiple>
                            </select>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="teams-elements-per-row"><?php esc_html_e( 'Number of elements per row', 'videofly' ); ?>:</label>
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
                        <label for="teams-post-limit"><?php esc_html_e( 'How many posts to extract', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <input type="text" value="" name="teams-post-limit"  id="teams-post-limit" size="4"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="teams-remove-gutter"><?php esc_html_e( 'Remove gutter', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <select name="teams-remove-gutter" id="teams-remove-gutter">
                            <option value="no"><?php esc_html_e('No', 'videofly'); ?></option>
                            <option value="yes"><?php esc_html_e('Yes', 'videofly'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="teams-enable-carousel"><?php esc_html_e( 'Enable carousel', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <select name="teams-enable-carousel" id="teams-enable-carousel">
                            <option value="no"><?php esc_html_e('No', 'videofly'); ?></option>
                            <option value="yes"><?php esc_html_e('Yes', 'videofly'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr class="ts-select-animation">
                    <td>
                        <?php esc_html_e('Special effect', 'videofly') ?>:
                    </td>
                    <td>
                       <?php echo vdfAllAnimations('teams-effect', 'effect') ?>
                    </td>
                </tr>
                <tr class="ts-select-delay">
                    <td>
                        <?php esc_html_e('Delay', 'videofly') ?>:
                    </td>
                    <td>
                       <?php echo vdfAllAnimations('teams-delay', 'delay') ?>
                    </td>
                </tr>
            </table>
        </div>
        <?php endif; ?>
    <?php break ?>
    <?php case 'pricing-tables': ?>
        <?php if( $is_plugin_active ) : ?>
        <div class="pricing-tables builder-element">
            <h3 class="element-title"><?php esc_html_e('Pricing tables', 'videofly'); ?></h3>
            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="pricing-tables-admin-label"><?php esc_html_e('Admin label:', 'videofly'); ?></label>
                    </td>
                    <td>
                       <input type="text" id="pricing-tables-admin-label" name="pricing-tables-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e( 'Category:', 'videofly' ); ?>
                    </td>
                    <td>
                        <?php $categories = get_categories(array( 'hide_empty' => 0, 'taxonomy' => 'ts_pricing_table_categories', 'pad_counts' => true ));
                        if( isset($categories) && !is_wp_error($categories) && !empty($categories) ) : ?>
                            <select class="ts-custom-select-input" data-placeholder="<?php esc_html_e('Select your category', 'videofly'); ?>" name="pricing-tables-category" id="pricing-tables-category" multiple>
                                <option value="0"><?php esc_html_e('All', 'videofly') ?></option>
                                <?php foreach ($categories as $index => $category): ?>
                                    <option value="<?php echo vdf_var_sanitize($category->slug ); ?>"><?php echo vdf_var_sanitize($category->cat_name ); ?></option>
                                <?php endforeach ?>
                            </select>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="pricing-tables-elements-per-row"><?php esc_html_e( 'Number of elements per row', 'videofly' ); ?>:</label>
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
                            <option value="3" selected="selected">3</option>
                            <option value="4">4</option>
                            <option value="6">6</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="pricing-tables-post-limit"><?php esc_html_e( 'How many posts to extract', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <input type="text" value="" name="pricing-tables-post-limit"  id="pricing-tables-post-limit" size="4"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="pricing-tables-remove-gutter"><?php esc_html_e( 'Remove gutter', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <select name="pricing-tables-remove-gutter" id="pricing-tables-remove-gutter">
                            <option value="no">No</option>
                            <option value="yes">Yes</option>
                        </select>
                    </td>
                </tr>
                <tr class="ts-select-animation">
                    <td>
                        <?php esc_html_e('Special effect', 'videofly') ?>:
                    </td>
                    <td>
                       <?php echo vdfAllAnimations('pricing-tables-effect', 'effect') ?>
                    </td>
                </tr>
                <tr class="ts-select-delay">
                    <td>
                        <?php esc_html_e('Delay', 'videofly') ?>:
                    </td>
                    <td>
                       <?php echo vdfAllAnimations('pricing-tables-delay', 'delay') ?>
                    </td>
                </tr>
            </table>
        </div>
        <?php endif; ?>
    <?php break ?>
    <?php case 'advertising': ?>
        <div class="advertising builder-element">
            <h3 class="element-title"><?php esc_html_e('Advertising element', 'videofly'); ?></h3>
            <!-- Advertising -->
            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="advertising-admin-label"><?php esc_html_e('Admin label','videofly');?>:</label>
                    </td>
                    <td>
                       <input type="text" id="advertising-admin-label" name="advertising-admin-label" />
                    </td>
                </tr>
            </table>
            <p><?php esc_html_e( 'Insert here your code', 'videofly' ); ?>:</p>
            <textarea name="advertising" id="advertising" cols="60" rows="10"></textarea>
        </div>
    <?php break ?>
    <?php case 'none': ?>
        <div class="none builder-element empty">
            <h3 class="element-title"><?php esc_html_e('Empty element', 'videofly'); ?></h3>
            <!-- None -->
            <p><?php esc_html_e( 'This is an empty element.', 'videofly' ); ?></p>
        </div>
    <?php break ?>
    <?php case 'delimiter': ?>
        <div class="delimiter builder-element">
            <h3 class="element-title"><?php esc_html_e('Delimiter', 'videofly'); ?></h3>
            <!-- Delimiter -->
            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="delimiter-admin-label"><?php esc_html_e('Admin label','videofly');?>:</label>
                    </td>
                    <td>
                       <input type="text" id="delimiter-admin-label" name="delimiter-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="delimiter-type"><?php esc_html_e( 'Delimiter type', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <select name="delimiter-type" id="delimiter-type">
                            <option value="dotsslash"><?php esc_html_e( 'Dotted', 'videofly' ); ?></option>
                            <option value="doubleline"><?php esc_html_e( 'Double line', 'videofly' ); ?></option>
                            <option value="lines"><?php esc_html_e( 'Lines', 'videofly' ); ?></option>
                            <option value="squares"><?php esc_html_e( 'Squares', 'videofly' ); ?></option>
                            <option value="line"><?php esc_html_e( 'Line', 'videofly' ); ?></option>
                            <option value="gradient"><?php esc_html_e( 'Gradient', 'videofly' ); ?></option>
                            <option value="iconed icon-close"><?php esc_html_e( 'Line with cross', 'videofly' ); ?></option>
                            <option value="small-line"><?php esc_html_e( 'Small 100px line', 'videofly' ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Delimiter color', 'videofly') ?>:
                    </td>
                    <td>
                       <input type="text" id="delimiter-color" class="colors-section-picker" name="delimiter-color" value="#FFFFFF"/>
                       <div class="colors-section-picker-div" id="delimiter-color-picker"></div>
                    </td>
                </tr>
            </table>
        </div>
    <?php break ?>
    <?php case 'title': ?>
        <div class="title builder-element">
            <h3 class="element-title"><?php esc_html_e('Title', 'videofly'); ?></h3>
            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="title-admin-label"><?php esc_html_e('Admin label','videofly');?>:</label>
                    </td>
                    <td>
                       <input type="text" id="title-admin-label" name="title-admin-label" />
                    </td>
                </tr>
            </table>
            <p><?php esc_html_e('Add icon for title from the library below:', 'videofly'); ?></p>
            <div class="builder-element-icon-toggle">
                <a href="#" class="red-ui-button builder-element-icon-trigger-btn" data-toggle="#builder-element-title-icon-selector"><?php esc_html_e('Show icons','videofly') ?></a>
            </div>
            <div class="ts-icons-container">
                <label>
                    <?php esc_html_e('Search icon', 'videofly'); ?>
                    <input type="search" name="search-icon" value="" />
                </label>
                <?php
                    $icons = vdf_build_list_icons();
                    $li = $icons['li'];
                    $option = $icons['option'];
                ?>
                <ul id="builder-element-title-icon-selector" data-selector="#builder-element-title-icon" class="builder-icon-list ts-custom-selector">
                    <?php echo vdf_var_sanitize($li); ?>
                </ul>
                <select name="builder-element-title-icon" id="builder-element-title-icon" class="hidden">
                    <?php echo vdf_var_sanitize($option); ?>
                </select>
            </div>
            <table cellpadding="10">
                <tr>
                    <td><label for="title-title"><?php esc_html_e( 'Title', 'videofly' ); ?>:</label></td>
                    <td><input type="text" value="" name="title-title"  id="title-title" style="width:400px" /></td>
                </tr>
                <tr>
                    <td><?php esc_html_e( 'Letter spacing', 'videofly' ); ?>:</td>
                    <td>
                        <input class="ts-input-slider" type="text" name="title-letter-spacing" id="title-letter-spacing" value="0" disabled />
                        <div id="title-letter-spacing-slider"></div>
                    </td>
                </tr>
                <tr>
                    <td><label for="title-color"><?php esc_html_e( 'Title color', 'videofly' ); ?>:</label></td>
                    <td>
                        <input type="text" id="builder-element-title-color" class="colors-section-picker" value="<?php echo vdf_get_color('general_text_color'); ?>" name="builder-element-title-color" />
                        <div class="colors-section-picker-div" id="builder-element-title-color"></div>
                    </td>
                </tr>
                <tr>
                    <td><label for="title-subtitle"><?php esc_html_e( 'Subtitle', 'videofly' ); ?>:</label></td>
                    <td><input type="text" value="" name="title-subtitle"  id="title-subtitle" style="width:400px" /></td>
                </tr>
                <tr>
                    <td><label for="title-subtitle-color"><?php esc_html_e( 'Subtitle color', 'videofly' ); ?>:</label></td>
                    <td>
                        <input type="text" id="builder-element-title-subtitle-color" class="colors-section-picker" value="<?php echo vdf_get_color('general_text_color'); ?>" name="builder-element-title-subtitle-color" />
                        <div class="colors-section-picker-div" id="builder-element-title-subtitle-color"></div>
                    </td>
                </tr>
                <tr>
                    <td><label for="title-size"><?php esc_html_e( 'Size', 'videofly' ); ?>:</label></td>
                    <td>
                        <select name="title-size" id="title-size">
                            <option value="h1"><?php esc_html_e( 'H1', 'videofly' ); ?></option>
                            <option value="h2"><?php esc_html_e( 'H2', 'videofly' ); ?></option>
                            <option value="h3"><?php esc_html_e( 'H3', 'videofly' ); ?></option>
                            <option value="h4"><?php esc_html_e( 'H4', 'videofly' ); ?></option>
                            <option value="h5"><?php esc_html_e( 'H5', 'videofly' ); ?></option>
                            <option value="h6"><?php esc_html_e( 'H6', 'videofly' ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="title-style"><?php esc_html_e( 'Style', 'videofly' ); ?>:</label></td>
                    <td>
                        <select name="title-style" id="title-style">
                            <option value="simpleleft" selected="selected"><?php esc_html_e( 'Title aligned left', 'videofly' ); ?></option>
                            <option value="lineafter"><?php esc_html_e( 'Title aligned left with circle and line after', 'videofly' ); ?></option>
                            <option value="leftrect"><?php esc_html_e( 'Title aligned left with rectangular left', 'videofly' ); ?></option>
                            <option value="simplecenter"><?php esc_html_e( 'Title aligned center', 'videofly' ); ?></option>
                            <option value="linerect"><?php esc_html_e( 'Title aligned center with line and rectangular below', 'videofly' ); ?></option>
                            <option value="2lines"><?php esc_html_e( 'Title aligned center with 2 lines before and after', 'videofly' ); ?></option>
                            <option value="lineariconcenter"><?php esc_html_e( 'Title aligned center with linear icon after', 'videofly' ); ?></option>
                            <option value="with-subtitle-above"><?php esc_html_e( 'Title with subtitle above', 'videofly' ); ?></option>
                            <option value="align-right"><?php esc_html_e( 'Title aligned right', 'videofly' ); ?></option>
                            <option value="bottom-decoration"><?php esc_html_e( 'Title with top and bottom decoration', 'videofly' ); ?></option>
                            <option value="brackets"><?php esc_html_e( 'Title in brackets', 'videofly' ); ?></option>
                            <option value="with-subtitle-over"><?php esc_html_e( 'Title background with subtitle over', 'videofly' ); ?></option>
                            <option value="with-small-line-below"><?php esc_html_e( 'Title with small line below', 'videofly' ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="title-link"><?php esc_html_e( 'Link', 'videofly' ); ?>:</label></td>
                    <td><input type="text" value="" name="title-link"  id="title-link" style="width:400px" /></td>
                </tr>
                <tr>
                    <td><label for="title-target"><?php esc_html_e( 'Target', 'videofly' ); ?>:</label></td>
                    <td>
                        <select name="title-target" id="title-target">
                            <option value="_blank">_blank</option>
                            <option value="_self">_self</option>
                        </select>
                    </td>
                </tr>
                <tr class="ts-select-animation">
                    <td>
                        <?php esc_html_e('Special effect', 'videofly') ?>:
                    </td>
                    <td>
                       <?php echo vdfAllAnimations('title-effect', 'effect') ?>
                    </td>
                </tr>
                <tr class="ts-select-delay">
                    <td>
                        <?php esc_html_e('Delay', 'videofly') ?>:
                    </td>
                    <td>
                       <?php echo vdfAllAnimations('title-delay', 'delay') ?>
                    </td>
                </tr>
            </table>
        </div>
    <?php break ?>
    <?php case 'video': ?>
        <div class="video builder-element">
            <h3 class="element-title"><?php esc_html_e('Video', 'videofly'); ?></h3>
            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="video-admin-label"><?php esc_html_e('Admin label','videofly');?>:</label>
                    </td>
                    <td>
                       <input type="text" id="video-admin-label" name="video-admin-label" />
                       <div class="ts-option-description">
                           <?php esc_html_e('Add a name visible in the admin panel for this element', 'videofly'); ?>
                       </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="video-lightbox"><?php esc_html_e('Show as lightbox', 'videofly'); ?>:</label>
                    </td>
                    <td>
                       <select name="video-lightbox" id="video-lightbox">
                           <option value="y"><?php esc_html_e('Yes', 'videofly'); ?></option>
                           <option selected="selected" value="n"><?php esc_html_e('No', 'videofly'); ?></option>
                       </select>
                       <div class="ts-option-description">
                           <?php esc_html_e('If yes is selected, it will only show a button. When clicked, it will show the video in a modal box.', 'videofly'); ?>
                       </div>
                    </td>
                </tr>
                <tr class="ts-video-title">
                    <td>
                        <label for="video-title"><?php esc_html_e('Title', 'videofly');?>:</label>
                    </td>
                    <td>
                       <input type="text" id="video-title" name="video-title" />
                       <div class="ts-option-description">
                           <?php esc_html_e('Add a title/description for your video.', 'videofly'); ?>
                       </div>
                    </td>
                </tr>
                <tr>
                    <td valign="top" colspan="2">
                        <textarea name="embed" id="video-embed" style="width:400px" rows="10"></textarea>
                        <div class="ts-option-description">
                           <?php esc_html_e('Add your embed code/URL here.', 'videofly'); ?>
                       </div>
                    </td>
                </tr>
            </table>
        </div>
    <?php break ?>
    <?php case 'image': ?>
        <div class="image builder-element">
            <h3 class="element-title"><?php esc_html_e('Image', 'videofly'); ?></h3>
            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="image-admin-label"><?php esc_html_e('Admin label:', 'videofly'); ?></label>
                    </td>
                    <td>
                       <input type="text" id="image-admin-label" name="image-admin-label" />
                       <div class="ts-option-description">
                           <?php esc_html_e('Add a name visible in the admin panel for this element', 'videofly'); ?>
                       </div>
                    </td>
                </tr>
                <tr>
                    <td valign="top"><label for="image_url"><?php esc_html_e('Image URL:', 'videofly'); ?></label></td>
                    <td>
                        <input type="text" value="" name="image_url"  id="image_url" class="image_url" style="width:300px" />
                        <input type="button" class="ts-secondary-button" id="select_image" value="Upload" />
                        <input type="hidden" value="" id="image_media_id" />
                        <div id="image_preview"></div>
                        <div class="ts-option-description">
                           <?php esc_html_e('Upload you image in the media library and select it', 'videofly'); ?>
                       </div>
                    </td>
                </tr>
                 <tr>
                    <td>
                        <?php esc_html_e('Image align:', 'videofly'); ?>
                    </td>
                    <td>
                        <select name="image-align" id="image-align">
                           <option value="left">Left</option>
                           <option value="center">Center</option>
                           <option value="right">Right</option>
                        </select>
                        <div class="ts-option-description">
                           <?php esc_html_e('Choose the alignment of you image. If the image is smaller than the container, it will be contained to the dimensions of it. If it is smaller, it will be aligned to the selected position.', 'videofly'); ?>
                       </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Link of the image:', 'videofly'); ?>
                    </td>
                    <td>
                        <input type="text" name="forward-image-url" id="forward-image-url" style="width:250px" value=""/>
                        <div class="ts-option-description">
                           <?php esc_html_e('If you want the image to be clickable, enter an URL above. Leave black if you do not need a link for it.', 'videofly'); ?>
                       </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Target:', 'videofly'); ?>
                    </td>
                    <td>
                        <select name="image-target" id="image-target">
                            <option value="_blank"><?php esc_html_e('_blank', 'videofly'); ?></option>
                            <option value="_self"><?php esc_html_e('_self', 'videofly'); ?></option>
                        </select>
                        <div class="ts-option-description">
                           <?php esc_html_e('If you set a link for your image you can open it in a new window: _blank or in the same window: _self.', 'videofly'); ?>
                       </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Use retina image:', 'videofly'); ?>
                    </td>
                    <td>
                        <select name="image-retina" id="image-retina">
                            <option value="y"><?php esc_html_e('Yes', 'videofly'); ?></option>
                            <option value="n" selected="selected"><?php esc_html_e('No', 'videofly'); ?></option>
                        </select>
                        <div class="ts-option-description">
                           <?php esc_html_e('This option will make you image 2x smaller. Note that this option uses the get_image_size() PHP function and some server can have it deactivated. If you get a notice, contact your hosting provider.', 'videofly'); ?>
                       </div>
                    </td>
                </tr>
                <tr class="ts-select-animation">
                    <td>
                        <?php esc_html_e('Special effect', 'videofly') ?>:
                    </td>
                    <td>
                       <?php echo vdfAllAnimations('image-effect', 'effect') ?>
                        <div class="ts-option-description">
                           <?php esc_html_e('Choose an animation for this element. When scrolled to this element it will be animated with the effect you have chosen.', 'videofly'); ?>
                       </div>
                    </td>
                </tr>
                <tr class="ts-select-delay">
                    <td>
                        <?php esc_html_e('Delay', 'videofly') ?>:
                    </td>
                    <td>
                       <?php echo vdfAllAnimations('image-delay', 'delay') ?>
                       <div class="ts-option-description">
                           <?php esc_html_e('If you decide to choose an efect you want to make sequences, you can delay it with some time.', 'videofly'); ?>
                       </div>
                    </td>
                </tr>
            </table>
        </div>
    <?php break ?>
    <?php case 'list-products': ?>
        <div class="list-products builder-element">
            <h3 class="element-title">List products element</h3>
            <!-- Select category -->
            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="list-products-admin-label"><?php esc_html_e('Admin label','videofly');?>:</label>
                    </td>
                    <td>
                       <input type="text" id="list-products-admin-label" name="list-products-admin-label" />
                       <div class="ts-option-description">
                           <?php esc_html_e('Add a name visible in the admin panel for this element', 'videofly'); ?>
                       </div>
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <label for="list-products-category"><?php esc_html_e( 'Category', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <select data-placeholder="<?php esc_html_e('Select your category', 'videofly'); ?>" class="ts-custom-select-input"  name="list-products-category" id="list-products-category" multiple>
                            <?php $categories = get_categories(array( 'hide_empty' => 0, 'show_option_all' => '','taxonomy'=>'product_cat' )); ?>
                            <?php if ($categories): ?>
                                <option value="0">All</option>
                                <?php foreach ($categories as $index => $category): ?>
                                    <option value="<?php echo vdf_var_sanitize($category->term_id ); ?>"><?php echo vdf_var_sanitize($category->cat_name ); ?></option>
                                <?php endforeach ?>
                            <?php endif ?>
                        </select>
                        <div class="ts-option-description">
                            <?php esc_html_e('Choose the categories that you want to showcase products from.', 'videofly'); ?>
                        </div>
                    </td>
                </tr>
            </table>

            <div id="list-products-options">
                <div class="list-products">
                    <table cellpadding="10">
                        <tr>
                            <td>
                                <label><?php esc_html_e( 'Behavior', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-products-behavior" id="list-products-behavior-selector">
                                   <li><img class="image-radio-input clickable-element" data-option="none" src="<?php echo get_template_directory_uri().'/images/options/behavior_none.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="carousel" src="<?php echo get_template_directory_uri().'/images/options/behavior_carousel.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="masonry" src="<?php echo get_template_directory_uri().'/images/options/behavior_masonry.png'; ?>"></li>
                                </ul>
                                <select class="hidden" name="list-products-behavior" id="list-products-behavior">
                                    <option value="none"><?php esc_html_e( 'Normal', 'videofly' ); ?></option>
                                    <option value="carousel"><?php esc_html_e( 'Carousel', 'videofly' ); ?></option>
                                    <option value="masonry"><?php esc_html_e( 'Masonry', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your product articles behavior - you can have them just shown, or you can activate the carousel. If carousel is selected your articles will show up in a line with arrows for navigation. If masonry bahevior is selected - your articles will be arranged in to fit it. Be aware that activating the masonry option the crop settings for image sizes tab will be overwritten.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-products-el-per-row"><?php esc_html_e( 'Elements per row', 'videofly' ); ?>:</label>
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
                                    <?php esc_html_e('Your products will be shown columns. Choose how mane columns do you want to use per line. Note that for mobile devices you will get only ONE element per row for better usability.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-products-nr-of-posts"><?php esc_html_e( 'How many products to extract', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="text" value="" name="list-products-nr-of-posts" id="list-products-nr-of-posts" size="4"/>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-products-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-products-order-by" id="list-products-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments_count"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-products-order-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-products-order-direction" id="list-products-order-direction">
                                    <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option value="desc" selected="selected"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-products-special-effects"><?php esc_html_e( 'Special effects', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-products-special-effects" id="list-products-special-effects">
                                    <option value="none"><?php esc_html_e('No effect', 'videofly') ?></option>
                                    <option value="opacited"><?php esc_html_e('Fade in', 'videofly') ?></option>
                                    <option value="rotate-in"><?php esc_html_e('Rotate in', 'videofly') ?></option>
                                    <option value="3dflip"><?php esc_html_e('3d flip', 'videofly') ?></option>
                                    <option value="scaler"><?php esc_html_e('Scale', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    <?php break ?>
    <?php case 'filters': ?>
        <div class="filters builder-element">
            <h3 class="element-title"><?php esc_html_e('Filters', 'videofly'); ?></h3>
            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="filters-admin-label"><?php esc_html_e('Admin label','videofly');?>:</label>
                    </td>
                    <td>
                       <input type="text" id="filters-admin-label" name="filters-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td valign="top"><label for="filters-post-type"><?php esc_html_e( 'Content type', 'videofly' ); ?>:</label></td>
                    <td valign="top">
                        <select name="filters-post-type" id="filters-post-type">
                            <option value="post"><?php esc_html_e( 'Posts', 'videofly' ); ?></option>
                            <?php if( $is_plugin_active ) : ?>
                                <option value="portfolio"><?php esc_html_e( 'Portfolio', 'videofly' ); ?></option>
                                <option value="video"><?php esc_html_e( 'Video', 'videofly' ); ?></option>
                                <option value="ts-gallery"><?php esc_html_e( 'Gallery', 'videofly' ); ?></option>
                            <?php endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td valign="top"><label for="filters-posts-category"><?php esc_html_e( 'Categories', 'videofly' ); ?>:</label></td>
                    <td valign="top" class="filters-categories">
                        <div id="post-categories">
                           <select data-placeholder="<?php esc_html_e('Select your category', 'videofly'); ?>" class="ts-custom-select-input" name="filters-posts-category" id="filters-post-category" multiple>
                                <?php $categories = get_categories(array( 'hide_empty' => 1, 'show_option_all' => '' )); ?>
                                <?php if ( isset($categories) && is_array($categories) && !empty($categories) ) : ?>
                                    <?php $s = 1; foreach ($categories as $index => $category): ?>
                                        <?php if( is_object($category) ) : ?>
                                            <?php if( $s === 1 ) echo '<option value="0">' . esc_html__('All', 'videofly') . '</option>'; ?>
                                            <option value="<?php echo vdf_var_sanitize($category->slug ); ?>"><?php echo vdf_var_sanitize($category->cat_name ); ?></option>
                                        <?php endif; $s++; ?>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </select>
                        </div>
                        <?php if( $is_plugin_active ) : ?>
                        <div id="portfolio-categories">
                            <select class="ts-custom-select-input" data-placeholder="<?php esc_html_e('Select your category', 'videofly'); ?>" name="filters-portfolio-category" id="filters-portfolio-category" multiple>
                                <?php $categories = get_categories(array( 'hide_empty' => 1, 'taxonomy' => 'portfolio_register_post_type' )); ?>
                                <?php if ( isset($categories) && is_array($categories) && !empty($categories) ): ?>
                                    <?php $j = 1; foreach ($categories as $index => $category): ?>
                                        <?php if( is_object($category) ) : ?>
                                            <?php if( $j === 1 ) echo '<option value="0">All</option>'; ?>
                                            <option value="<?php echo vdf_var_sanitize($category->slug ); ?>"><?php echo vdf_var_sanitize($category->cat_name ); ?></option>
                                        <?php endif; $j++; ?>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </select>
                        </div>
                        <div id="ts-gallery-categories">
                            <select class="ts-custom-select-input" data-placeholder="<?php esc_html_e('Select your category', 'videofly'); ?>" name="filters-portfolios-category" id="filters-ts-gallery-category" multiple>
                                <?php $categories = get_categories(array( 'hide_empty' => 1, 'taxonomy' => 'gallery_categories' )); ?>
                                <?php if ( isset($categories) && is_array($categories) && !empty($categories) ): ?>
                                    <?php $j = 1; foreach ($categories as $index => $category): ?>
                                        <?php if( is_object($category) ) : ?>
                                            <?php if( $j === 1 ) echo '<option value="0">All</option>'; ?>
                                            <option value="<?php echo vdf_var_sanitize($category->slug ); ?>"><?php echo vdf_var_sanitize($category->cat_name ); ?></option>
                                        <?php endif; $j++; ?>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </select>
                        </div>
                        <div id="video-categories">
                            <select class="ts-custom-select-input" data-placeholder="<?php esc_html_e('Select your category', 'videofly'); ?>" name="filters-video-category" id="filters-video-category" multiple>
                                <?php $categories = get_categories(array( 'hide_empty' => 1, 'taxonomy' => 'videos_categories' )); ?>
                                <?php if ( isset($categories) && is_array($categories) && !empty($categories) ): ?>
                                    <?php $j = 1; foreach ($categories as $index => $category): ?>
                                        <?php if( is_object($category) ) : ?>
                                            <?php if( $j === 1 ) echo '<option value="0">All</option>'; ?>
                                            <option value="<?php echo vdf_var_sanitize($category->slug ); ?>"><?php echo vdf_var_sanitize($category->cat_name ); ?></option>
                                        <?php endif; $j++; ?>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </select>
                        </div>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="filters-posts-limit"><?php esc_html_e( 'Posts to extract', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <input type="text" value="" name="filters-posts-limit"  id="filters-posts-limit" size="4"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="filters-elements-per-row"><?php esc_html_e( 'Elements per row', 'videofly' ); ?>:</label>
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
                        <label for="filters-meta"><?php esc_html_e( 'Show meta', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <select name="filters-meta" id="filters-meta">
                            <option value="y"><?php esc_html_e( 'Yes', 'videofly' ); ?></option>
                            <option value="n"><?php esc_html_e( 'No', 'videofly' ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="filters-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <select name="filters-order-by" id="filters-order-by">
                            <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                            <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                            <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                         <label for="filters-order-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <select name="filters-order-direction" id="filters-order-direction">
                            <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                            <option selected="selected" value="desc"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                         <label for="filters-special-effects"><?php esc_html_e( 'Special effect', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <select name="filters-special-effects" id="filters-special-effects">
                            <option value="none"><?php esc_html_e('No effect', 'videofly') ?></option>
                            <option value="opacited"><?php esc_html_e('Fade in', 'videofly') ?></option>
                            <option value="rotate-in"><?php esc_html_e('Rotate in', 'videofly') ?></option>
                            <option value="3dflip"><?php esc_html_e('3d flip', 'videofly') ?></option>
                            <option value="scaler"><?php esc_html_e('Scale', 'videofly') ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                         <label for="filters-gutter"><?php esc_html_e( 'Remove gutter(space) between articles:', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <select name="filters-gutter" id="filters-gutter">
                            <option value="n"><?php esc_html_e('No', 'videofly') ?></option>
                            <option value="y"><?php esc_html_e('Yes', 'videofly') ?></option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
    <?php break ?>
    <?php case 'feature-blocks': ?>
        <div class="feature-blocks builder-element">
            <h3 class="element-title"><?php esc_html_e('Feature blocks', 'videofly'); ?></h3>
            <!-- Feature blocks -->
            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="feature-blocks-per-row"><?php esc_html_e( 'Number of elements per row', 'videofly' ); ?>:</label>
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
                        <label for="feature-blocks-style"><?php esc_html_e( 'Style', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <select name="feature-blocks-style" id="feature-blocks-style">
                            <option value="style1"><?php esc_html_e('Feature blocks with background', 'videofly') ?></option>
                            <option value="style2"><?php esc_html_e('Feature blocks with border', 'videofly') ?></option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
    <?php break ?>
    <?php case 'spacer': ?>
        <div class="spacer builder-element">
            <h3 class="element-title"><?php esc_html_e('Spacer', 'videofly'); ?></h3>
            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="spacer-admin-label"><?php esc_html_e('Admin label','videofly');?>:</label>
                    </td>
                    <td>
                       <input type="text" id="spacer-admin-label" name="spacer-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e( 'Height', 'videofly' ); ?>:
                    </td>
                    <td>
                        <input type="text" name="spacer-height" id="spacer-height" style="width:250px" value="20"/> px
                    </td>
                </tr>
            </table>
        </div>
    <?php break ?>
    <?php case 'facebook-block': ?>
        <div class="facebook-block builder-element">
            <h3 class="element-title">Facebook element</h3>
            <table cellpadding="10">
                <tr>
                    <td>
                        <?php esc_html_e( 'Link of your Facebook page: used in the URL.', 'videofly' ); ?>
                    </td>
                    <td>
                        <input type="text" name="facebook-url" id="facebook-url" style="width:250px" value=''/>
                        <div class="ts-option-description">
                            ex: http://facebook.com/touchsize
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e( 'Hide Cover Photo', 'videofly' ); ?>:
                    </td>
                    <td>
                        <select name="facebook-cover" id="facebook-cover">
                            <option value="true"><?php esc_html_e( 'Yes', 'videofly' ); ?></option>
                            <option value="false"><?php esc_html_e( 'No', 'videofly' ); ?></option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
    <?php break ?>
    <?php case 'counters': ?>
        <div class="counters builder-element">
            <h3 class="element-title"><?php esc_html_e('Counters', 'videofly'); ?></h3>
            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="counters-admin-label"><?php esc_html_e('Admin label','videofly');?>:</label>
                    </td>
                    <td>
                       <input type="text" id="counters-admin-label" name="counters-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e( 'Text', 'videofly' ); ?>:
                    </td>
                    <td>
                        <input type="text" name="counters-text" id="counters-text" style="width:250px" value=""/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e( 'Count percent', 'videofly' ); ?>:
                    </td>
                    <td>
                        <input type="text" name="counters-precents" id="counters-precents" style="width:250px" value="100"/> %
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Text color', 'videofly') ?>:
                    </td>
                    <td>
                        <input type="text" id="counters-text-color" class="colors-section-picker" value="#000" name="counters-text-color" />
                        <div class="colors-section-picker-div"></div>
                    </td>
                </tr>
                <tr class="ts-select-animation">
                    <td>
                        <?php esc_html_e('Special effect', 'videofly') ?>:
                    </td>
                    <td>
                       <?php echo vdfAllAnimations('counters-special-effect', 'effect', 'none') ?>
                    </td>
                </tr>
                <tr class="ts-select-delay">
                    <td>
                        <?php esc_html_e('Delay', 'videofly') ?>:
                    </td>
                    <td>
                       <?php echo vdfAllAnimations('counters-delay', 'delay', 'none') ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Display track bar', 'videofly') ?>:
                    </td>
                    <td>
                        <select name="counters-track-bar" id="counters-track-bar">
                            <option selected="selected" value="with-track-bar"><?php esc_html_e( 'With track bar', 'videofly' ); ?></option>
                            <option value="without-track-bar"><?php esc_html_e( 'Without track bar', 'videofly' ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr class="ts-counters-track-bar-color">
                    <td>
                        <?php esc_html_e('Track bar color', 'videofly') ?>:
                    </td>
                    <td>
                        <input type="text" id="counters-track-bar-color" class="colors-section-picker" value="#000" name="counters-track-bar-color" />
                        <div class="colors-section-picker-div"></div>
                    </td>
                </tr>
            </table>
            <div class="ts-counters-icons">
                <p><?php esc_html_e('Choose your icon from the library below:', 'videofly'); ?></p>
                <div class="builder-element-icon-toggle">
                    <a href="#" class="red-ui-button builder-element-icon-trigger-btn" data-toggle="#counters-icon-selector"><?php esc_html_e('Show icons','videofly') ?></a>
                </div>
                <?php
                    $icons = vdf_build_list_icons();
                    $li = $icons['li'];
                    $option = $icons['option'];
                ?>
                <div class="ts-icons-container">
                    <label>
                        <?php esc_html_e('Search icon', 'videofly'); ?>
                        <input type="search" name="search-icon" value="" />
                    </label>
                    <?php
                        $icons = vdf_build_list_icons();
                        $li = $icons['li'];
                        $option = $icons['option'];
                    ?>
                    <ul id="counters-icon-selector" data-selector="#counters-icon" class="builder-icon-list ts-custom-selector">
                        <?php echo vdf_var_sanitize($li); ?>
                    </ul>

                    <select name="counters-icon" id="counters-icon" class="hidden">
                        <?php echo vdf_var_sanitize($option); ?>
                    </select>
                </div>
            </div>
        </div>
    <?php break ?>
    <?php case 'page': ?>
        <div class="page builder-element">
            <h3 class="element-title"><?php esc_html_e( 'Page', 'videofly' ); ?></h3>
            <table cellpadding="10">
                <tr>
                    <td>
                        <?php esc_html_e( 'Search page', 'videofly' ); ?>:
                    </td>
                    <td>
                        <input type="text" name="search-page" id="search-page" style="width:250px"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e( 'Criteria', 'videofly' ); ?>:
                    </td>
                    <td>
                        <select name="search-page-criteria" id="search-page-criteria">
                            <option value="title"><?php esc_html_e( 'Title', 'videofly' ); ?></option>
                            <option value="title-content"><?php esc_html_e( 'Title & Content', 'videofly' ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Order by', 'videofly') ?>:
                    </td>
                    <td>
                        <select name="search-page-order-by" id="search-page-order-by">
                            <option value="id"><?php esc_html_e( 'ID', 'videofly' ); ?></option>
                            <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                            <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Direction', 'videofly') ?>:
                    </td>
                    <td>
                       <select name="search-page-direction" id="search-page-direction">
                            <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                            <option value="desc"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td>
                        <input type="button" name="search-page-button" id="search-type-page" class="search-posts-buttons ts-secondary-button save-element" value="<?php esc_html_e( 'Search', 'videofly' ); ?>"/>
                    </td>
                </tr>
            </table>

            <p><?php esc_html_e( 'Results', 'videofly' ); ?>Results</p>
            <table cellpadding="10" id="search-pages-results">
            </table>
        </div>
    <?php break ?>
    <?php case 'post': ?>
        <div class="post builder-element">
            <h3 class="element-title"><?php esc_html_e( 'Post', 'videofly' ); ?></h3>
            <table cellpadding="10">
                <tr>
                    <td>
                        <?php esc_html_e( 'Search post', 'videofly' ); ?>:
                    </td>
                    <td>
                        <input type="text" name="search-post" id="search-post" style="width:250px"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e( 'Criteria', 'videofly' ); ?>:
                    </td>
                    <td>
                        <select name="search-post-criteria" id="search-post-criteria">
                            <option value="title"><?php esc_html_e( 'Title', 'videofly' ); ?></option>
                            <option value="title-content"><?php esc_html_e( 'Title & Content', 'videofly' ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e( 'Order by', 'videofly' ); ?>:
                    </td>
                    <td>
                        <select name="search-post-order-by" id="search-post-order-by">
                            <option value="id"><?php esc_html_e( 'ID', 'videofly' ); ?></option>
                            <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                            <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e( 'Direction', 'videofly' ); ?>:
                    </td>
                    <td>
                        <select name="search-post-direction" id="search-post-direction">
                            <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                            <option value="desc"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="button" name="search-post-button" id="search-type-post" class="search-posts-buttons ts-secondary-button save-element"value="<?php esc_html_e( 'Search', 'videofly' ); ?>"/>
                    </td>
                </tr>
            </table>

            <p><?php esc_html_e( 'Results', 'videofly' ); ?></p>
            <table cellpadding="10" id="search-posts-results">
            </table>
        </div>
    <?php break ?>
    <?php case 'buttons': ?>
        <div class="buttons builder-element">
            <h3 class="element-title"><?php esc_html_e('Button', 'videofly'); ?></h3>
            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="buttons-admin-label"><?php esc_html_e('Admin label','videofly');?>:</label>
                    </td>
                    <td>
                       <input type="text" id="buttons-admin-label" name="buttons-admin-label" />
                    </td>
                </tr>
            </table>

            <p><?php esc_html_e('Choose your icon button from the library below:', 'videofly'); ?></p>
            <div class="builder-element-icon-toggle">
                <a href="#" class="red-ui-button builder-element-icon-trigger-btn" data-toggle="#builder-element-button-icon-selector"><?php esc_html_e('Show icons','videofly') ?></a>
            </div>

            <div class="ts-icons-container">
                <label>
                    <?php esc_html_e('Search icon', 'videofly'); ?>
                    <input type="search" name="search-icon" value="" />
                </label>
               <?php
                    $icons = vdf_build_list_icons();
                    $li = $icons['li'];
                    $option = $icons['option'];
                ?>
                <ul id="builder-element-button-icon-selector" data-selector="#builder-element-button-icon" class="builder-icon-list ts-custom-selector">
                    <?php echo vdf_var_sanitize($li); ?>
                </ul>

                <select name="builder-element-button-icon" id="builder-element-button-icon" class="hidden">
                    <?php echo vdf_var_sanitize($option); ?>
                </select>
            </div>

            <table cellpadding="10">
                <tr>
                    <td width="70px">
                        <?php esc_html_e('Icon align', 'videofly'); ?>
                    </td>
                    <td>
                        <select name="button-icon-align" id="button-icon-align">
                            <option selected="selected" value="left-of-text"><?php esc_html_e('Left of text', 'videofly'); ?></option>
                            <option value="above-text"><?php esc_html_e('Above Text', 'videofly'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="70px">
                        <?php esc_html_e('Button align', 'videofly'); ?>
                    </td>
                    <td>
                        <select name="button[align]" id="button-align">
                            <option value="text-left"><?php esc_html_e('Left', 'videofly'); ?></option>
                            <option value="text-right"><?php esc_html_e('Right', 'videofly'); ?></option>
                            <option value="text-center"><?php esc_html_e('Center', 'videofly'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Text', 'videofly') ?>
                    </td>
                    <td>
                       <input type="text" id="button-text" name="button-text" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('URL', 'videofly') ?>:
                    </td>
                    <td>
                       <input type="text" id="button-url" name="button-url" value=""/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Target', 'videofly') ?>:
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
                        <?php esc_html_e('Size', 'videofly') ?>:
                    </td>
                    <td>
                       <select name="button-size" id="button-size">
                           <option value="big"><?php esc_html_e('Big', 'videofly') ?></option>
                           <option value="medium" selected="selected"><?php esc_html_e('Medium', 'videofly') ?></option>
                           <option value="small"><?php esc_html_e('Small', 'videofly') ?></option>
                           <option value="xsmall"><?php esc_html_e('xSmall', 'videofly') ?></option>
                       </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Text color', 'videofly') ?>:
                    </td>
                    <td>
                       <input class="colors-section-picker" type="text" id="button-text-color" name="button-text-color" value="#000000"/>
                       <div class="colors-section-picker-div"></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Text hover color', 'videofly') ?>:
                    </td>
                    <td>
                       <input class="colors-section-picker" type="text" id="button-text-hover-color" name="button-text-hover-color" value="#FFFFFF"/>
                       <div class="colors-section-picker-div"></div>
                    </td>
                </tr>
                 <tr>
                    <td>
                        <?php esc_html_e('Choose your mode to display:', 'videofly') ?>
                    </td>
                    <td>
                       <select name="button-mode-dispaly" id="button-mode-display">
                           <option value="border-button"><?php esc_html_e('Border button', 'videofly') ?></option>
                           <option value="background-button"><?php esc_html_e('Background button', 'videofly') ?></option>
                       </select>
                    </td>
                </tr>
                <tr class="button-background-color">
                    <td>
                        <?php esc_html_e('Background color', 'videofly') ?>:
                    </td>
                    <td>
                       <input class="colors-section-picker" type="text" id="button-background-color" name="button-background-color" value="#f1f1f1"/>
                       <div class="colors-section-picker-div"></div>
                    </td>
                </tr>
                <tr class="button-border-color">
                    <td>
                        <?php esc_html_e('Border color', 'videofly') ?>:
                    </td>
                    <td>
                       <input class="colors-section-picker" type="text" id="button-border-color" name="button-border-color" value="#f1f1f1"/>
                       <div class="colors-section-picker-div"></div>
                    </td>
                </tr>
                <tr class="button-background-color">
                    <td>
                        <?php esc_html_e('Background hover color', 'videofly') ?>:
                    </td>
                    <td>
                       <input class="colors-section-picker" type="text" id="button-background-hover-color" name="button-background-hover-color" value="#858785"/>
                       <div class="colors-section-picker-div"></div>
                    </td>
                </tr>
                <tr class="button-border-color">
                    <td>
                        <?php esc_html_e('Border hover color', 'videofly') ?>:
                    </td>
                    <td>
                       <input class="colors-section-picker" type="text" id="button-border-hover-color" name="button-border-hover-color" value="#858785"/>
                       <div class="colors-section-picker-div"></div>
                    </td>
                </tr>
                <tr class="ts-select-animation">
                    <td>
                        <?php esc_html_e('Special effect', 'videofly') ?>:
                    </td>
                    <td>
                       <?php echo vdfAllAnimations('button-effect', 'effect') ?>
                    </td>
                </tr>
                <tr class="ts-select-delay">
                    <td>
                        <?php esc_html_e('Delay', 'videofly') ?>:
                    </td>
                    <td>
                       <?php echo vdfAllAnimations('button-delay', 'delay') ?>
                    </td>
                </tr>
            </table>
        </div>
    <?php break ?>
    <?php case 'contact-form': ?>
        <div class="contact-form builder-element">
            <h3 class="element-title"><?php esc_html_e('Contact form', 'videofly'); ?></h3>
            <em><?php esc_html_e('Enter yout email in Options -- Social', 'videofly') ?></em>
            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="contact-form-admin-label"><?php esc_html_e('Admin label:','videofly'); ?></label>
                    </td>
                    <td>
                       <input type="text" id="contact-form-admin-label" name="contact-form-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" name="contact-form-hide-icon" id="contact-form-hide-icon" /><label for="contact-form-hide-icon"> <?php esc_html_e('Hide email icon', 'videofly') ?></label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" name="contact-form-hide-subject" id="contact-form-hide-subject" />
                        <label for="contact-form-hide-subject"> <?php esc_html_e('Hide Subject field', 'videofly') ?></label>
                    </td>
                </tr>
            </table>
            <table cellpadding="10">
                 <ul id="contact-form_items">

                 </ul>

             <input type="hidden" id="contact-form_content" value="" />
             <input type="button" class="button ts-multiple-add-button" data-element-name="contact-form" id="contact-form_add_item" value=" <?php esc_html_e('Add New Field', 'videofly'); ?>" />
            <?php
                 echo '<script id="contact-form_items_template" type="text/template">';
                 echo '<li id="list-item-id-{{item-id}}" class="contact-form-item ts-multiple-add-list-element">
                        <div class="sortable-meta-element"><span class="tab-arrow icon-down"></span> <span class="tab-item-contact-form ts-multiple-item-tab">Item: {{slide-number}}</span></div>
                        <div class="hidden">
                            <table>
                                <tr>
                                    <td>
                                        <label for="contact-form-{{item-id}}-type">'.esc_html__('Choose your type field:', 'videofly').'</label>
                                    </td>
                                    <td>
                                        <select class="contact-form-type" data-builder-name="type" name="contact-form[{{item-id}}][type]" id="contact-form-{{item-id}}-type">
                                            <option value="select">'. esc_html__('Select', 'videofly').'</option>
                                            <option value="input">'. esc_html__('Input', 'videofly').'</option>
                                            <option value="textarea">'. esc_html__('Textarea', 'videofly').'</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="contact-form-{{item-id}}-require">'.esc_html__('Require field:', 'videofly').'</label>
                                    </td>
                                    <td>
                                        <select data-builder-name="require" name="contact-form[{{item-id}}][require]" id="contact-form-{{item-id}}-require">
                                            <option value="y">'. esc_html__('Yes', 'videofly').'</option>
                                            <option value="n">'. esc_html__('No', 'videofly').'</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                         <label for="contact-form-{{item-id}}-title">'.esc_html__('Title:', 'videofly').'</label>
                                    </td>
                                    <td>
                                         <input data-builder-name="title" type="text" id="contact-form-{{item-id}}-title" name="contact-form[{{item-id}}][title]" />
                                    </td>
                                </tr>
                                <tr class="contact-form-options">
                                    <td>
                                        <label for="contact-form-{{item-id}}-options">'. esc_html__('Write your options here in the following field(ex: option1/option2/options3/...):','videofly').'</label>
                                    </td>
                                    <td>
                                        <input data-builder-name="options" type="text" id="contact-form-{{item-id}}-options" name="contact-form[{{item-id}}][options]" />
                                    </td>
                                </tr>
                            </table>
                            <input type="hidden" data-builder-name="item_id" value="{{item-id}}" name="contact-form[{{item-id}}][id]" />
                            <input type="button" class="ts-secondary-button remove-item" value="' . esc_html__('Remove', 'videofly') . '" />
                            <a href="#" data-item="contact-form-item" data-increment="contact-form-items" class="ts-secondary-button ts-multiple-item-duplicate">'.esc_html__('Duplicate Item', 'videofly').'</a>
                        </div>
                     </li>';
                 echo '</script>';
            ?>
            </table>
        </div>
    <?php break ?>
    <?php case 'featured-area': ?>
        <div class="featured-area builder-element">
            <h3 class="element-title"><?php esc_html_e('Featured area', 'videofly'); ?></h3>
            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="featured-area-admin-label"><?php esc_html_e('Admin label','videofly');?>:</label>
                    </td>
                    <td>
                       <input type="text" id="featured-area-admin-label" name="featured-area-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><?php esc_html_e('Choose custum post type', 'videofly') ?></p>
                    </td>
                    <td>
                        <select name="featured-area-custom-post" id="featured-area-custom-post">
                            <option value="post"><?php esc_html_e('Post', 'videofly') ?></option>
                            <?php if( $is_plugin_active ) : ?>
                                <option value="video"><?php esc_html_e('Video', 'videofly') ?></option>
                                <option value="ts-gallery"><?php esc_html_e('Gallery', 'videofly') ?></option>
                            <?php endif; ?>
                        </select>
                    </td>
                </tr>
                <?php if( $is_plugin_active ) : ?>
                <tr class="featured-area-video" style="display:none">
                    <td>
                        <p><?php esc_html_e('Categories video', 'videofly') ?></p>
                    </td>
                    <td>
                        <select data-placeholder="<?php esc_html_e('Select your category', 'videofly'); ?>" class="ts-custom-select-input"  name="featured-area-categories-video" id="featured-area-categories-video" multiple>
                            <?php $categories = get_categories(array( 'hide_empty' => 0, 'taxonomy' => 'videos_categories' )); ?>
                            <?php if ( isset($categories) && is_array($categories) && $categories !== '' && !empty($categories) ): ?>
                                <?php $i = 0; foreach ($categories as $index => $category): ?>
                                    <?php if( is_object($category) ) : ?>
                                        <?php if( $i == 0 ) echo '<option value="0">All</option>'; ?>
                                        <option value="<?php if( is_object($category) ) echo vdf_var_sanitize($category->term_id); ?>"><?php if( is_object($category) ) echo vdf_var_sanitize($category->cat_name); ?></option>
                                    <?php $i++; endif; ?>
                                <?php endforeach ?>
                            <?php endif ?>
                        </select>
                    </td>
                </tr>
                <tr class="featured-area-ts-gallery" style="display:none">
                    <td>
                        <p><?php esc_html_e('Categories gallery', 'videofly') ?></p>
                    </td>
                    <td>
                        <select data-placeholder="<?php esc_html_e('Select your category', 'videofly'); ?>" class="ts-custom-select-input"  name="featured-area-categories-ts-gallery" id="featured-area-categories-ts-gallery" multiple>
                            <?php $categories = get_categories(array( 'hide_empty' => 0, 'taxonomy' => 'gallery_categories' )); ?>
                            <?php if ( isset($categories) && is_array($categories) && $categories !== '' && !empty($categories) ): ?>
                                <?php $i = 0; foreach ($categories as $index => $category): ?>
                                    <?php if( is_object($category) ) : ?>
                                        <?php if( $i == 0 ) echo '<option value="0">All</option>'; ?>
                                        <option value="<?php if( is_object($category) ) echo vdf_var_sanitize($category->term_id); ?>"><?php if( is_object($category) ) echo vdf_var_sanitize($category->cat_name); ?></option>
                                    <?php $i++; endif; ?>
                                <?php endforeach ?>
                            <?php endif ?>
                        </select>
                    </td>
                </tr>
                <?php endif; ?>
                <tr class="featured-area-post" style="display:none">
                    <td>
                        <p><?php esc_html_e('Categories posts', 'videofly') ?></p>
                    </td>
                    <td>
                        <select data-placeholder="<?php esc_html_e('Select your category', 'videofly'); ?>" class="ts-custom-select-input"  name="featured-area-categories-post" id="featured-area-categories-post" multiple>
                            <?php
                            $categories = get_categories(array( 'hide_empty' => 0 )); ?>
                            <?php if ( isset($categories) && is_array($categories) && $categories !== '' ): ?>
                                <?php $i = 0; foreach ($categories as $index => $category): ?>
                                    <?php if( is_object($category) ) : ?>
                                        <?php if( $i == 0 ) echo '<option value="0">All</option>'; ?>
                                        <option value="<?php echo vdf_var_sanitize($category->term_id); ?>"><?php echo vdf_var_sanitize($category->cat_name); ?></option>
                                    <?php $i++; endif; ?>
                                <?php endforeach ?>
                            <?php endif ?>
                        </select>
                    </td>
                </tr>
                <tr class="featured-area-video" style="display:none">
                    <td>
                        <label><?php esc_html_e( 'Redirect to', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <select name="featured-area-play" id="featured-area-play">
                            <option value="single"><?php esc_html_e( 'Single post', 'videofly' ); ?></option>
                            <option value="modal" selected="selected"><?php esc_html_e( 'Open in modal', 'videofly' ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="featured-area-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <select name="featured-area-order-by" id="featured-area-order-by">
                            <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                            <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                            <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                            <option value="likes"><?php esc_html_e( 'Likes', 'videofly' ); ?></option>
                        </select>
                        <div class="ts-option-description">
                            <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="featured-area-order-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <select name="featured-area-order-direction" id="featured-area-order-direction">
                            <option value="ASC"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                            <option value="DESC" selected="selected"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                        </select>
                        <div class="ts-option-description">
                            <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label><?php esc_html_e( 'Number of posts to extract', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <input type="text" value="" name="featured-area-posts-per-page" id="featured-area-posts-per-page" size="4"/>
                        <div class="ts-option-description">
                            <?php esc_html_e('Insert the number of the posts you want to extract.', 'videofly'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="featured-area-exclude-first"><?php esc_html_e( 'Exclude number of first posts', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <input type="text" value="" name="featured-area-exclude-first" id="featured-area-exclude-first" size="4"/>
                        <div class="ts-option-description">
                            <?php esc_html_e('Insert the number of the posts you want to exclude from showing.', 'videofly'); ?>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    <?php break ?>
    <?php case 'shortcodes': ?>
        <div class="shortcodes builder-element">
            <h3 class="element-title"><?php esc_html_e('Shortcode', 'videofly'); ?></h3>
            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="shortcodes-admin-label"><?php esc_html_e('Admin label','videofly');?>:</label>
                    </td>
                    <td>
                       <input type="text" id="shortcodes-admin-label" name="shortcodes-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Remove paddings','videofly'); ?>
                    </td>
                    <td>
                        <select name="shortcodes-paddings" id="shortcodes-paddings">
                            <option value="y"><?php esc_html_e('Yes', 'videofly'); ?></option>
                            <option selected="selected" value="n"><?php esc_html_e('No', 'videofly'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <textarea name="shortcodes" id="ts-shortcodes" cols="70" rows="10"></textarea>
                    </td>
                </tr>
            </table>
        </div>
    <?php break ?>
    <?php case 'text': ?>
        <div class="text builder-element">
            <h3 class="element-title"><?php esc_html_e('Text', 'videofly'); ?></h3>
        </div>
    <?php break ?>
    <?php case 'image-carousel': ?>
        <div class="image-carousel builder-element">
            <h3 class="element-title"><?php esc_html_e('Image carousel', 'videofly'); ?></h3>
            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="image-carousel-admin-label"><?php esc_html_e('Admin label','videofly'); ?>:</label>
                    </td>
                    <td>
                       <input type="text" id="image-carousel-admin-label" name="image-carousel-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="carousel-height"><?php esc_html_e( 'Maximum carousel height', 'videofly' ); ?>:</label>
                    </td>
                    <td><input type="text" id="carousel_height" name="carousel_height" value="400" />px</td>
                </tr>
                <tr>
                    <td valign="top"><label for="image_url"><?php esc_html_e( 'Add your images', 'videofly' ); ?>:</label></td>
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
                                            var attachmentId = image_url[0];

                                            if( image_url != '' ){
                                                image_url_path = image_url[1].split('.');
                                                var imageFormat = image_url_path[image_url_path.length - 1];
                                                var imageUrl = image_url_path.splice(0, image_url_path.length - 1).join('.');
                                                    carousel_images.append('\
                                                        <li class="image" data-attachment_id="' + attachmentId + '" data-attachment_url="' + imageUrl + '.' + imageFormat + '">\
                                                            <img src="' + imageUrl + '-<?php echo get_option( "thumbnail_size_w" ); ?>x<?php echo get_option( "thumbnail_size_h" ); ?>.' + imageFormat + '" />\
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
                        <p class="add_carousel_images hide-if-no-js">
                            <a href="#"><?php esc_html_e( 'Add gallery images', 'videofly' ); ?></a>
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
    <?php break ?>
    <?php case 'map': ?>
        <div class="map builder-element">
            <style type="text/css">
                html, body, #map-canvas {
                    width: 100%;
                    height: 360px;
                    margin: 0px;
                    padding: 0px
                }
                #panel {
                    z-index: 5;
                    background-color: #fff;
                    padding: 5px;
                }
            </style>
            <script type="text/javascript">
                jQuery(document).ready(function(){

                    // initialize();

                    // jQuery('#ts-builder-elements-modal').on('show.bs.modal', function (e) {
                    //     setTimeout(function(){
                    //         initialize();
                    //     },1500)
                    // });

                    var element = jQuery('#builder-elements > .map');
                    // Check which option is selected

                    // remove all active demo links
                    element.find('.map-style-option > a').removeClass('active');
                    setTimeout(function(){
                        var map_style = jQuery(element).find('#map-style option:selected').val();

                        if ( map_style == 'map-style-essence' ) {
                            jQuery('#map-style').next('.map-style-option').find('a[role="essence"]').addClass('active');
                        } else if( map_style == 'map-style-subtle-grayscale' ) {
                            jQuery('#map-style').next('.map-style-option').find('a[role="subtle-grayscale"]').addClass('active');
                        } else if( map_style == 'map-style-shades-of-grey' ) {
                            jQuery('#map-style').next('.map-style-option').find('a[role="shades-of-grey"]').addClass('active');
                        } else if( map_style == 'map-style-purple' ) {
                            jQuery('#map-style').next('.map-style-option').find('a[role="purple"]').addClass('active');
                        } else if( map_style == 'map-style-best-ski-pros' ) {
                            jQuery('#map-style').next('.map-style-option').find('a[role="best-ski-pros"]').addClass('active');
                        } else {
                            jQuery('#map-style').next('.map-style-option').find('a[role="essence"]').addClass('active');
                        }
                    },100);

                    // Change demo link on change
                    jQuery('#map-style').change(function(event) {
                        var map_style = jQuery(this).find('option:selected').val();

                        if( jQuery('.map-style-option').find('a.active').length > 0 )
                            jQuery('.map-style-option').find('a.active').removeClass('active');

                        if ( map_style == 'map-style-essence' ) {
                            jQuery('#map-style').next('.map-style-option').find('a[role="essence"]').addClass('active');
                        } else if( map_style == 'map-style-subtle-grayscale' ) {
                            jQuery('#map-style').next('.map-style-option').find('a[role="subtle-grayscale"]').addClass('active');
                        } else if( map_style == 'map-style-shades-of-grey' ) {
                            jQuery('#map-style').next('.map-style-option').find('a[role="shades-of-grey"]').addClass('active');
                        } else if( map_style == 'map-style-purple' ) {
                            jQuery('#map-style').next('.map-style-option').find('a[role="purple"]').addClass('active');
                        } else if( map_style == 'map-style-best-ski-pros' ) {
                            jQuery('#map-style').next('.map-style-option').find('a[role="best-ski-pros"]').addClass('active');
                        } else {
                            jQuery('#map-style').next('.map-style-option').find('a[role="essence"]').addClass('active');
                        }
                    });

                    // If label "Marker icon" option[Upload icon] is selected, show upload form
                    jQuery('#map-marker-icon').change(function(event) {
                        var map_icon = jQuery(this).find('option:selected').val();

                        if( map_icon == 'map-marker-icon-upload' ) {
                            jQuery('.map-marker-img').removeClass('hidden');
                        } else {
                            jQuery('.map-marker-img').addClass('hidden');
                        }
                    });

                    setTimeout(function(){
                        var map_icon = jQuery(element).find('#map-marker-icon option:selected').val();

                        if( map_icon == 'map-marker-icon-upload' ) {
                            jQuery('.map-marker-img').removeClass('hidden');
                        } else {
                            jQuery('.map-marker-img').addClass('hidden');
                        }
                    },100);
                });
            </script>
            <h3 class="element-title"><?php esc_html_e('Map','videofly'); ?></h3>
            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="map-admin-label"><?php esc_html_e('Admin label','videofly');?>:</label>
                    </td>
                    <td>
                       <input type="text" id="map-admin-label" name="map-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <div id="panel">
                            <input id="map-address" type="text" placeholder="<?php esc_html_e('Enter your address','videofly'); ?>">
                            <input class="ts-secondary-button" type="button" value="<?php esc_html_e('Find address','videofly') ?>" onclick="codeAddress()">
                        </div>
                    </td>
                    <td>
                        <div id="map-canvas"></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="map-width"><?php esc_html_e('Width:','videofly'); ?></label>
                    </td>
                    <td>
                        <input type="text" id="map-width" name="map-width" value="100" />%
                        <div class="ts-option-description">
                            <?php esc_html_e('Enter the width in percent for example: 100%','videofly'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="map-height"><?php esc_html_e('Height:','videofly'); ?></label>
                    </td>
                    <td>
                        <input type="text" id="map-height" name="map-height" value="300" />px
                        <div class="ts-option-description">
                            <?php esc_html_e('Enter the height in pixels (for example: 480px)','videofly'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="map-latitude"><?php esc_html_e('Latitude:','videofly'); ?></label>
                    </td>
                    <td>
                        <input type="text" id="map-latitude" name="map-latitude" />
                        <div class="ts-option-description">
                            <?php esc_html_e('Latitude automatically generated from the address entered above','videofly'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="map-longitude"><?php esc_html_e('Longitude:','videofly'); ?></label>
                    </td>
                    <td>
                        <input type="text" id="map-longitude" name="map-longitude" />
                        <div class="ts-option-description">
                            <?php esc_html_e('Longitude automatically generated from the address entered above','videofly'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="map-type"><?php esc_html_e('Map type:','videofly'); ?></label>
                    </td>
                    <td>
                        <select name="map-type" id="map-type">
                            <option selected="selected" value="ROADMAP"><?php esc_html_e('Roadmap', 'videofly') ?></option>
                            <option value="SATELLITE"><?php esc_html_e('Satellite', 'videofly') ?></option>
                            <option value="HYBRID"><?php esc_html_e('Hybrid', 'videofly') ?></option>
                            <option value="TERRAIN"><?php esc_html_e('Terrain', 'videofly') ?></option>
                        </select>
                        <div class="ts-option-description">
                            <?php esc_html_e('For details read the documentation from Google','videofly'); ?>
                            <a href="https://developers.google.com/maps/documentation/javascript/maptypes#MapTypes" target="_blank">
                                <?php esc_html_e('read documentation','videofly'); ?>
                            </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="map-style"><?php esc_html_e('Map style:','videofly'); ?></label>
                    </td>
                    <td>
                        <select name="map-style" id="map-style">
                            <option selected="selected" value="map-style-essence"><?php esc_html_e('Essence', 'videofly') ?></option>
                            <option value="map-style-subtle-grayscale"><?php esc_html_e('Subtle grayscale', 'videofly') ?></option>
                            <option value="map-style-shades-of-grey"><?php esc_html_e('Shades of grey', 'videofly') ?></option>
                            <option value="map-style-purple"><?php esc_html_e('Purple', 'videofly') ?></option>
                            <option value="map-style-best-ski-pros"><?php esc_html_e('Best ski pros', 'videofly') ?></option>
                        </select>
                        <div class="ts-option-description map-style-option">
                            <a class="active" role="essence" href="https://snazzymaps.com/style/61/blue-essence" target="_blank">
                                <?php esc_html_e('View example for Essence','videofly'); ?>
                            </a>
                            <a role="subtle-grayscale" href="https://snazzymaps.com/style/15/subtle-grayscale" target="_blank">
                                <?php esc_html_e('View example for Subtle grayscale','videofly'); ?>
                            </a>
                            <a role="shades-of-grey" href="https://snazzymaps.com/style/38/shades-of-grey" target="_blank">
                                <?php esc_html_e('View example for Shades of grey','videofly'); ?>
                            </a>
                            <a role="purple" href="https://snazzymaps.com/style/1371/purple" target="_blank">
                                <?php esc_html_e('View example for Purple','videofly'); ?>
                            </a>
                            <a role="best-ski-pros" href="https://snazzymaps.com/style/1370/best-ski-pros" target="_blank">
                                <?php esc_html_e('View example for Best ski pros','videofly'); ?>
                            </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="map-zoom"><?php esc_html_e('Map zoom:','videofly'); ?></label>
                    </td>
                    <td>
                        <input type="text" id="map-zoom" name="map-zoom" value="11" />
                    </td>
                </tr>
                <tr>
                    <td>
                        &nbsp;
                    </td>
                    <td>
                        <div>
                            <?php esc_html_e('Read more about ','videofly'); ?>
                            <a href="https://developers.google.com/maps/documentation/javascript/reference#MapTypeControlOptions" target="_blank">
                                <?php esc_html_e('Google Map Controls','videofly'); ?>
                            </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="map-type-control"><?php esc_html_e('Map type control:','videofly'); ?></label>
                    </td>
                    <td>
                        <select name="map-type-control" id="map-type-control">
                            <option value="false"><?php esc_html_e('Disable', 'videofly') ?></option>
                            <option selected="selected" value="true"><?php esc_html_e('Enable', 'videofly') ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="map-zoom-control"><?php esc_html_e('Map zoom control:','videofly'); ?></label>
                    </td>
                    <td>
                       <select name="map-zoom-control" id="map-zoom-control">
                            <option value="false"><?php esc_html_e('Disable', 'videofly') ?></option>
                            <option selected="selected" value="true"><?php esc_html_e('Enable', 'videofly') ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="map-scale-control"><?php esc_html_e('Map scale control:','videofly'); ?></label>
                    </td>
                    <td>
                        <select name="map-scale-control" id="map-scale-control">
                            <option selected="selected" value="false"><?php esc_html_e('Disable', 'videofly') ?></option>
                            <option value="true"><?php esc_html_e('Enable', 'videofly') ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="map-scroll-wheel"><?php esc_html_e('Map scroll wheel:','videofly'); ?></label>
                    </td>
                    <td>
                        <select name="map-scroll-wheel" id="map-scroll-wheel">
                            <option value="false"><?php esc_html_e('Disable', 'videofly') ?></option>
                            <option selected="selected" value="true"><?php esc_html_e('Enable', 'videofly') ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="map-draggable-direction"><?php esc_html_e('Draggable directions:','videofly'); ?></label>
                    </td>
                    <td>
                        <select name="map-draggable-direction" id="map-draggable-direction">
                            <option value="false"><?php esc_html_e('Disable', 'videofly') ?></option>
                            <option selected="selected" value="true"><?php esc_html_e('Enable', 'videofly') ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="map-marker-icon"><?php esc_html_e('Marker icon:','videofly'); ?></label>
                    </td>
                    <td>
                        <select name="map-marker-icon" id="map-marker-icon">
                            <option selected="selected" value="map-marker-icon-default"><?php esc_html_e('Use default', 'videofly') ?></option>
                            <option value="map-marker-icon-upload"><?php esc_html_e('Upload icon', 'videofly') ?></option>
                        </select>
                    </td>
                </tr>
                <tr class="hidden map-marker-img">
                    <td>
                        &nbsp;
                    </td>
                    <td>
                        <input type="text" value="" name="map-marker-image-url"  id="map-marker-attachment" style="width:300px" />
                        <input type="button" class="ts-secondary-button" id="map-marker-select-image" value="<?php esc_html_e('Upload icon', 'videofly'); ?>" />
                        <input type="hidden" value="" class="image_media_id" id="map-marker-media-id" />
                        <div id="map-marker-image-preview"></div>
                    </td>
                </tr>
            </table>
        </div>
    <?php break ?>
    <?php case 'banner': ?>
        <div class="banner builder-element">
            <h3 class="element-title"><?php esc_html_e('Banner', 'videofly'); ?></h3>
            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="banner-admin-label"><?php esc_html_e('Admin label:','videofly'); ?></label>
                    </td>
                    <td>
                       <input type="text" id="banner-admin-label" name="banner-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td valign="top"><label for="image-banner-url"><?php esc_html_e( 'Enter your image for banner box:', 'videofly' ); ?></label></td>
                    <td>
                        <input type="text" value="" name="image-banner-url"  id="image-banner-url" style="width:300px" />
                        <input type="button" class="ts-secondary-button" id="select_banner_image" value="<?php esc_html_e('Upload', 'videofly'); ?>" />
                        <input type="hidden" value="" id="banner_image_media_id" />
                        <div id="banner-image-preview"></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e( 'Enter height image', 'videofly' ); ?>:
                    </td>
                    <td>
                        <input type="text" name="banner-height" id="banner-height" value=""/>px
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e( 'Enter your title', 'videofly' ); ?>:
                    </td>
                    <td>
                        <input type="text" name="banner-title" id="banner-title" value=""/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e( 'Enter your subtitle', 'videofly' ); ?>:
                    </td>
                    <td>
                        <input type="text" name="banner-subtitle" id="banner-subtitle" value=""/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e( 'Enter title button', 'videofly' ); ?>:
                    </td>
                    <td>
                        <input type="text" name="banner-button-title" id="banner-button-title" value=""/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e( 'Enter your url for button', 'videofly' ); ?>:
                    </td>
                    <td>
                        <input type="text" name="banner-button-url" id="banner-button-url" value=""/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e( 'Select your background color for button', 'videofly' ); ?>:
                    </td>
                    <td>
                        <input type="text" id="banner-button-background" class="colors-section-picker" value="#F46964" name="banner-background-color" />
                        <div class="colors-section-picker-div" id="banner-button-background"></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e( 'Select your color for text button', 'videofly' ); ?>:
                    </td>
                    <td>
                        <input type="text" id="banner-button-text-color" class="colors-section-picker" value="#F46964" name="banner-button-text-color" />
                        <div class="colors-section-picker-div" id="banner-button-text-color"></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e( 'Select your font color', 'videofly' ); ?>:
                    </td>
                    <td>
                        <input type="text" id="banner-font-color" class="colors-section-picker" value="#f1f1f1" name="banner-font-color" />
                        <div class="colors-section-picker-div" id="banner-font-color"></div>
                    </td>
                </tr>
                <tr>
                    <td width="70px">
                        <?php esc_html_e('Text align', 'videofly'); ?>
                    </td>
                    <td>
                        <select name="banner-text-align" id="banner-text-align">
                            <option value="banner-text-align-left"><?php esc_html_e('Left', 'videofly') ?></option>
                            <option value="banner-text-align-right"><?php esc_html_e('Right', 'videofly') ?></option>
                            <option value="banner-text-align-center"><?php esc_html_e('Center', 'videofly') ?></option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
    <?php break ?>
    <?php case 'toggle': ?>
        <div class="toggle builder-element">
            <h3 class="element-title"><?php esc_html_e('Toggle', 'videofly'); ?></h3>
            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="toggle-admin-label"><?php esc_html_e('Admin label:','videofly'); ?></label>
                    </td>
                    <td>
                       <input type="text" id="toggle-admin-label" name="toggle-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e( 'Enter your title:', 'videofly' ); ?>
                    </td>
                    <td>
                        <input type="text" name="toggle-title" id="toggle-title" value=''/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e( 'Enter your description:', 'videofly' ); ?>
                    </td>
                    <td>
                        <textarea name="toggle-description" id="toggle-description" cols="45" rows="15"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e( 'State (opened/closed):', 'videofly' ); ?>
                    </td>
                    <td>
                        <select name="toggle-state" id="toggle-state">
                            <option value="open"><?php esc_html_e( 'Open', 'videofly' ); ?></option>
                            <option value="closed"><?php esc_html_e( 'Closed', 'videofly' ); ?></option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
    <?php break ?>
    <?php case 'tab': ?>
        <div class="tab builder-element">
            <h3 class="element-title"><?php esc_html_e('Tab','videofly'); ?></h3>
            <table cellpadding="10">
                <tr>
                    <td>
                        <?php esc_html_e('Admin label:','videofly'); ?>
                    </td>
                    <td>
                       <input type="text" id="tab-admin-label" name="tab-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Choose mode tab:','videofly'); ?>
                    </td>
                    <td>
                       <select id="tab-mode" name="tab-mode">
                           <option value="horizontal"><?php esc_html_e('Horizontal','videofly'); ?></option>
                           <option value="vertical"><?php esc_html_e('Vertical','videofly'); ?></option>
                       </select>
                    </td>
                </tr>
            </table>
            <ul id="tab_items">

            </ul>

            <input type="hidden" id="tab_content" value="" />
            <input type="button" class="button ts-multiple-add-button" data-element-name="tab" id="tab_add_item" value=" <?php esc_html_e('Add New Tab', 'videofly'); ?>" />
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
                            <input type="button" class="ts-secondary-button remove-item" value="' . esc_html__('Remove', 'videofly') . '" />
                            <a href="#" data-item="tab-item" data-increment="tab-items" class="ts-secondary-button ts-multiple-item-duplicate">'.esc_html__('Duplicate Item', 'videofly').'</a>
                        </div>
                    </li>';
                echo '</script>';
           ?>
        </div>
    <?php break ?>
    <?php case 'list-videos': ?>
        <?php if( $is_plugin_active ) : ?>
        <div class="list-videos builder-element">
            <h3 class="element-title"><?php esc_html_e('List videos', 'videofly'); ?></h3>
            <!-- Select category -->
            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="list-videos-admin-label"><?php esc_html_e('Admin label','videofly');?>:</label>
                    </td>
                    <td>
                       <input type="text" id="list-videos-admin-label" name="list-videos-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <label for="list-videos-category"><?php esc_html_e( 'Category', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <select data-placeholder="<?php esc_html_e('Select your category', 'videofly'); ?>" class="ts-custom-select-input" name="list-videos-category" id="list-videos-category" multiple>
                            <?php $categories = get_categories(array( 'hide_empty' => 0, 'taxonomy' => 'videos_categories' )); ?>
                            <?php if ( isset($categories) && is_array($categories) && !empty($categories) ): ?>
                                <?php $i = 0; foreach ($categories as $index => $category): ?>
                                    <?php if( is_object($category) ) : ?>
                                        <?php if( $i == 0 ) echo '<option value="0">All</option>'; ?>
                                        <option value="<?php if( is_object($category) ) echo vdf_var_sanitize($category->slug); ?>"><?php if( is_object($category) ) echo vdf_var_sanitize($category->cat_name); ?></option>
                                    <?php $i++; endif; ?>
                                <?php endforeach ?>
                            <?php endif ?>
                        </select>
                        <div class="ts-option-description">
                            <?php esc_html_e('Choose the categories that you want to showcase articles from.', 'videofly'); ?>
                        </div>

                    </td>
                </tr>
                <tr>
                    <td>
                        <label><?php esc_html_e( 'Show only featured', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <select name="list-videos-featured" id="list-videos-featured">
                            <option value="y"><?php esc_html_e( 'Yes', 'videofly' ); ?></option>
                            <option value="n" selected="selected"><?php esc_html_e( 'No', 'videofly' ); ?></option>
                        </select>

                        <div class="ts-option-description">
                            <?php esc_html_e('You can display only featured posts', 'videofly'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="list-videos-exclude"><?php esc_html_e( 'Exclude post IDs', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <input type="text" value="" name="list-videos-exclude" id="list-videos-exclude" size="4"/>
                        <div class="ts-option-description">
                            <?php esc_html_e('Insert the IDs of the posts you want to exclude from showing.', 'videofly'); ?> (ex: <b>100,101,102,104</b>)
                        </div>
                    </td>
                </tr>
                 <tr class="list-videos-exclude">
                    <td>
                        <label for="list-videos-exclude-first"><?php esc_html_e( 'Exclude number of first posts', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <input type="text" value="" name="list-videos-exclude-first" id="list-videos-exclude-first" size="4"/>
                        <div class="ts-option-description">
                            <?php esc_html_e('Insert the number of the posts you want to exclude from showing.', 'videofly'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="list-videos-display-mode"><?php esc_html_e( 'How to display', 'videofly' ); ?>:</label>
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
                        <select class="hidden" name="list-videos-display-mode" id="list-videos-display-mode">
                            <option value="grid"><?php esc_html_e( 'Grid', 'videofly' ); ?></option>
                            <option value="list"><?php esc_html_e( 'List', 'videofly' ); ?></option>
                            <option value="thumbnails"><?php esc_html_e( 'Thumbnails', 'videofly' ); ?></option>
                            <option value="big-post"><?php esc_html_e( 'Big post', 'videofly' ); ?></option>
                            <option value="super-post"><?php esc_html_e( 'Super Post', 'videofly' ); ?></option>
                            <option value="timeline"><?php esc_html_e( 'Timeline Post', 'videofly' ); ?></option>
                            <option value="mosaic"><?php esc_html_e( 'mosaic Post', 'videofly' ); ?></option>
                        </select>
                        <div class="ts-option-description">
                            <?php esc_html_e('Choose your article view type. Depending on what type of article showcase layout you select you will get different options. You can read more about view types in our documentation files: ', 'videofly'); echo vdf_var_sanitize($touchsize_com); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label><?php esc_html_e( 'Redirect to', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <select name="list-videos-play" id="list-videos-play">
                            <option value="single" selected="selected"><?php esc_html_e( 'Single post', 'videofly' ); ?></option>
                            <option value="modal"><?php esc_html_e( 'Open video in modal', 'videofly' ); ?></option>
                        </select>
                    </td>
                </tr>
            </table>

            <div id="list-videos-display-mode-options">
                <!-- Grid options -->
                <div class="list-videos-grid hidden">
                    <table cellpadding="10">
                        <tr>
                            <td>
                                <label><?php esc_html_e( 'Behavior', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-videos-grid-behavior" id="list-videos-grid-behavior-selector">
                                   <li><img class="image-radio-input clickable-element" data-option="none" src="<?php echo get_template_directory_uri().'/images/options/behavior_none.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="carousel" src="<?php echo get_template_directory_uri().'/images/options/behavior_carousel.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="masonry" src="<?php echo get_template_directory_uri().'/images/options/behavior_masonry.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="scroll" src="<?php echo get_template_directory_uri().'/images/options/behavior_scroll.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="tabbed" src="<?php echo get_template_directory_uri().'/images/options/behavior_tabs.png'; ?>"></li>
                                </ul>
                                <select class="hidden" name="list-videos-grid-behavior" id="list-videos-grid-behavior">
                                    <option value="none"><?php esc_html_e( 'Normal', 'videofly' ); ?></option>
                                    <option value="carousel"><?php esc_html_e( 'Carousel', 'videofly' ); ?></option>
                                    <option value="masonry"><?php esc_html_e( 'Masonry', 'videofly' ); ?></option>
                                    <option value="scroll"><?php esc_html_e( 'Scroll', 'videofly' ); ?></option>
                                    <option value="tabbed"><?php esc_html_e( 'Tabbed', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your posts behavior - you can have them just shown, or you can activate the carousel. If carousel is selected your articles will show up in a line with arrows for navigation. If masonry bahevior is selected - your articles will be arranged in to fit it. Be aware that activating the masonry option the crop settings for image sizes tab will be overwritten.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-grid-image"><?php esc_html_e( 'Show featured image', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-grid-image" id="list-videos-grid-image">
                                    <option selected="selected" value="y"><?php esc_html_e( 'Yes', 'videofly' ); ?></option>
                                    <option value="n"><?php esc_html_e( 'No', 'videofly' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-grid-title"><?php esc_html_e( 'Title position', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-videos-grid-title" id="list-videos-grid-title-selector">
                                   <li><img class="image-radio-input clickable-element" data-option="title-above-image" src="<?php echo get_template_directory_uri().'/images/options/grid_view_title_image.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="title-below-image" src="<?php echo get_template_directory_uri().'/images/options/grid_view_title_excerpt.png'; ?>"></li>
                                </ul>
                                <select class="hidden" name="list-videos-grid-title" id="list-videos-grid-title">
                                    <option value="title-above-image"><?php esc_html_e( 'Above image', 'videofly' ); ?></option>
                                    <option value="title-below-image" selected="selected"><?php esc_html_e( 'Above excerpt', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Select your title position for you grid posts. You can either have it above the image of above the excerpt. Note that sometimes title may change the position of the meta (date, categories, author) as well.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-grid-show-meta"><?php esc_html_e( 'Show meta', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="radio" name="list-videos-grid-show-meta" id="list-videos-grid-show-meta-y" value="y"  checked = "checked"  />
                                <label for="list-videos-grid-show-meta-y"><?php esc_html_e( 'Yes', 'videofly' ); ?></label>
                                <input type="radio" name="list-videos-grid-show-meta" id="list-videos-grid-show-meta-n" value="n" />
                                <label for="list-videos-grid-show-meta-n"><?php esc_html_e( 'No', 'videofly' ); ?></label>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can choose to show or to hide your meta details for your articles. Meta values include date, author, categories and other article details.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-grid-el-per-row"><?php esc_html_e( 'Elements per row', 'videofly' ); ?>:</label>
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
                                    <?php esc_html_e('Your articles will be shown columns. Choose how mane columns do you want to use per line. Note that for mobile devices you will get only ONE element per row for better usability.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="list-videos-grid-nr-of-posts">
                            <td>
                                <label for="list-videos-grid-nr-of-posts"><?php esc_html_e( 'How many posts to extract', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="text" value="" name="list-videos-grid-nr-of-posts" id="list-videos-grid-nr-of-posts" size="4"/>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-grid-related"><?php esc_html_e('Show related posts:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="list-videos-grid-related" id="list-videos-grid-related">
                                    <option value="y"><?php esc_html_e( 'Yes', 'videofly' ); ?></option>
                                    <option selected="selected" value="n"><?php esc_html_e( 'No', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can set each of your grid to show related articles below. A list with other articles will appear below.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-grid-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-grid-order-by" id="list-videos-grid-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                    <option value="likes"><?php esc_html_e( 'Likes', 'videofly' ); ?></option>
                                    <option value="rand"><?php esc_html_e( 'Random', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-grid-order-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-grid-order-direction" id="list-videos-grid-order-direction">
                                    <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option selected="selected" value="desc" selected="selected"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-grid-special-effects"><?php esc_html_e( 'Special effects', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-grid-special-effects" id="list-videos-grid-special-effects">
                                    <option value="none"><?php esc_html_e('No effect', 'videofly') ?></option>
                                    <option value="opacited"><?php esc_html_e('Fade in', 'videofly') ?></option>
                                    <option value="rotate-in"><?php esc_html_e('Rotate in', 'videofly') ?></option>
                                    <option value="3dflip"><?php esc_html_e('3d flip', 'videofly') ?></option>
                                    <option value="scaler"><?php esc_html_e('Scale', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="list-videos-grid-pagination">
                            <td>
                                <label for="list-videos-grid-pagination"><?php esc_html_e( 'Enable pagination', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-grid-pagination" id="list-videos-grid-pagination">
                                    <option selected="selected" value="n"><?php esc_html_e('None', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Enable pagination', 'videofly') ?></option>
                                    <option value="load-more"><?php esc_html_e('Load more', 'videofly') ?></option>
                                    <option value="infinite"><?php esc_html_e('Infinite scrolling', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can add pagination.', 'videofly'); ?>
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
                                <label for="list-videos-list-image"><?php esc_html_e( 'Show featured image', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-list-image" id="list-videos-list-image">
                                    <option selected="selected" value="y"><?php esc_html_e( 'Yes', 'videofly' ); ?></option>
                                    <option value="n"><?php esc_html_e( 'No', 'videofly' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-list-show-meta"><?php esc_html_e( 'Show meta', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="radio" name="list-videos-list-show-meta" id="list-videos-list-show-meta-y" value="y"  checked = "checked"  />
                                <label for="list-videos-list-show-meta-y"><?php esc_html_e( 'Yes', 'videofly' ); ?></label>
                                <input type="radio" name="list-videos-list-show-meta" id="list-videos-list-show-meta-n" value="n" />
                                <label for="list-videos-list-show-meta-n"><?php esc_html_e( 'No', 'videofly' ); ?></label>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can choose to show or to hide your meta details for your articles. Meta values include date, author, categories and other article details.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="list-videos-list-nr-of-posts">
                            <td>
                                <label for="list-videos-list-nr-of-posts"><?php esc_html_e( 'How many posts to extract', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="text" value="" name="list-videos-list-nr-of-posts" id="list-videos-list-nr-of-posts" size="4"/>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <?php /*
                        <tr>
                            <td>
                                <?php esc_html_e( 'Content split', 'videofly' ); ?>
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
                                    <?php esc_html_e('Choose your title/content split proportions. You can have them either 1/3, 1/2, 3/4 for your title and 2/3,1/2, 1/4 accordingly. Depending on the text and titles you use, select your split type.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                         */?>
                            <td>
                                <label for="list-videos-list-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-list-order-by" id="list-videos-list-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                    <option value="likes"><?php esc_html_e( 'Likes', 'videofly' ); ?></option>
                                    <option value="rand"><?php esc_html_e( 'Random', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-list-order-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-list-order-direction" id="list-videos-list-order-direction">
                                    <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option value="desc" selected="selected"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-list-special-effects"><?php esc_html_e( 'Special effects:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="list-videos-list-special-effects" id="list-videos-list-special-effects">
                                    <option value="none"><?php esc_html_e('No effect', 'videofly') ?></option>
                                    <option value="opacited"><?php esc_html_e('Fade in', 'videofly') ?></option>
                                    <option value="rotate-in"><?php esc_html_e('Rotate in', 'videofly') ?></option>
                                    <option value="3dflip"><?php esc_html_e('3d flip', 'videofly') ?></option>
                                    <option value="scaler"><?php esc_html_e('Scale', 'videofly') ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-list-pagination"><?php esc_html_e( 'Enable pagination', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-list-pagination" id="list-videos-list-pagination">
                                    <option selected="selected" value="n"><?php esc_html_e('None', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Enable pagination', 'videofly') ?></option>
                                    <option value="load-more"><?php esc_html_e('Load more', 'videofly') ?></option>
                                    <option value="infinite"><?php esc_html_e('Infinite scrolling', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can add pagination.', 'videofly'); ?>
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
                                <label for="list-videos-thumbnails-title"><?php esc_html_e( 'Title position', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-thumbnails-title" id="list-videos-thumbnails-title">
                                    <option value="over-image" selected="selected"><?php esc_html_e( 'Over image', 'videofly' ); ?></option>
                                    <option value="below-image"><?php esc_html_e( 'Below image', 'videofly' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label><?php esc_html_e( 'Behavior', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-videos-thumbnails-behavior" id="list-videos-thumbnails-behavior-selector">
                                   <li><img class="image-radio-input clickable-element" data-option="none" src="<?php echo get_template_directory_uri().'/images/options/behavior_none.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="carousel" src="<?php echo get_template_directory_uri().'/images/options/behavior_carousel.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="masonry" src="<?php echo get_template_directory_uri().'/images/options/behavior_masonry.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="scroll" src="<?php echo get_template_directory_uri().'/images/options/behavior_scroll.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="tabbed" src="<?php echo get_template_directory_uri().'/images/options/behavior_tabs.png'; ?>"></li>
                                </ul>
                                <select  class="hidden" name="list-videos-thumbnails-behavior" id="list-videos-thumbnails-behavior">
                                    <option value="none"><?php esc_html_e( 'Normal', 'videofly' ); ?></option>
                                    <option value="carousel"><?php esc_html_e( 'Carousel', 'videofly' ); ?></option>
                                    <option value="masonry"><?php esc_html_e( 'Masonry', 'videofly' ); ?></option>
                                    <option value="scroll"><?php esc_html_e( 'Scroll', 'videofly' ); ?></option>
                                    <option value="tabbed"><?php esc_html_e( 'Tabbed', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your posts behavior - you can have them just shown, or you can activate the carousel. If carousel is selected your articles will show up in a line with arrows for navigation. If masonry bahevior is selected - your articles will be arranged in to fit it. Be aware that activating the masonry option the crop settings for image sizes tab will be overwritten.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-thumbnail-posts-per-row"><?php esc_html_e( 'Number of posts per row', 'videofly' ); ?>:</label>
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
                                    <?php esc_html_e('Your articles will be shown columns. Choose how mane columns do you want to use per line. Note that for mobile devices you will get only ONE element per row for better usability.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="list-videos-thumbnail-nr-of-posts">
                            <td>
                                <label for="list-videos-thumbnail-limit"><?php esc_html_e( 'How many posts to extract', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="text" value="" name="list-videos-thumbnail-limit"  id="list-videos-thumbnail-limit" size="4"/>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                         <tr>
                            <td>
                                <label for="list-videos-thumbnail-show-meta"><?php esc_html_e( 'Show meta:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <input type="radio" name="list-videos-thumbnail-show-meta" id="list-videos-thumbnail-show-meta-y" value="y"  checked = "checked"  />
                                <label for="list-videos-thumbnail-show-meta-y"><?php esc_html_e( 'Yes', 'videofly' ); ?></label>
                                <input type="radio" name="list-videos-thumbnail-show-meta" id="list-videos-thumbnail-show-meta-n" value="n" />
                                <label for="list-videos-thumbnail-show-meta-n"><?php esc_html_e( 'No', 'videofly' ); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-thumbnail-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-thumbnail-order-by" id="list-videos-thumbnail-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                    <option value="likes"><?php esc_html_e( 'Likes', 'videofly' ); ?></option>
                                    <option value="rand"><?php esc_html_e( 'Random', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-thumbnail-order-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-thumbnail-order-direction" id="list-videos-thumbnails-order-direction">
                                    <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option value="desc" selected="selected"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-thumbnail-special-effects"><?php esc_html_e( 'Special effects:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="list-videos-thumbnail-special-effects" id="list-videos-thumbnail-special-effects">
                                    <option value="none"><?php esc_html_e('No effect', 'videofly') ?></option>
                                    <option value="opacited"><?php esc_html_e('Fade in', 'videofly') ?></option>
                                    <option value="rotate-in"><?php esc_html_e('Rotate in', 'videofly') ?></option>
                                    <option value="3dflip"><?php esc_html_e('3d flip', 'videofly') ?></option>
                                    <option value="scaler"><?php esc_html_e('Scale' , 'videofly') ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-thumbnail-gutter"><?php esc_html_e( 'Remove gutter(space) between articles:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="list-videos-thumbnail-gutter" id="list-videos-thumbnail-gutter">
                                    <option value="n"><?php esc_html_e('No', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Yes', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Gutter is the space between your articles. You can remove the space and have your articles sticked one to another.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="list-videos-thumbnails-pagination">
                            <td>
                                <label for="list-videos-thumbnails-pagination"><?php esc_html_e( 'Enable pagination:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="list-videos-thumbnails-pagination" id="list-videos-thumbnails-pagination">
                                    <option selected="selected" value="n"><?php esc_html_e('None', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Enable pagination', 'videofly') ?></option>
                                    <option value="load-more"><?php esc_html_e('Load more', 'videofly') ?></option>
                                    <option value="infinite"><?php esc_html_e('Infinite scrolling', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can add pagination.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="list-videos-big-post hidden">
                    <table cellpadding="10">
                        <tr>
                            <td>
                                <label for="list-videos-big-post-image"><?php esc_html_e( 'Show featured image', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-big-post-image" id="list-videos-big-post-image">
                                    <option selected="selected" value="y"><?php esc_html_e( 'Yes', 'videofly' ); ?></option>
                                    <option value="n"><?php esc_html_e( 'No', 'videofly' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-big-post-carousel"><?php esc_html_e( 'Show carousel', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="list-videos-big-post-carousel" id="list-videos-big-post-carousel">
                                    <option value="y"><?php esc_html_e('Yes', 'videofly') ?></option>
                                    <option selected="selected" value="n"><?php esc_html_e('No', 'videofly') ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-big-post-excerpt"><?php esc_html_e( 'Show excerpt', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="list-videos-big-post-excerpt" id="list-videos-big-post-excerpt">
                                    <option selected="selected" value="y"><?php esc_html_e('Yes', 'videofly') ?></option>
                                    <option value="n"><?php esc_html_e('No', 'videofly') ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-big-post-image-position"><?php esc_html_e( 'Image position:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="list-videos-big-post-image-position" id="list-videos-big-post-image-position">
                                    <option value="left"><?php esc_html_e( 'Left', 'videofly' ); ?></option>
                                    <option value="right"><?php esc_html_e( 'Right', 'videofly' ); ?></option>
                                    <option value="mosaic"><?php esc_html_e( 'Mosaic', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('The way you want to show your big post', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-big-post-show-meta"><?php esc_html_e( 'Show meta:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <input type="radio" name="list-videos-big-post-show-meta" id="list-videos-big-post-show-meta-y" value="y"  checked = "checked"  />
                                <label for="list-videos-big-post-show-meta-y"><?php esc_html_e( 'Yes', 'videofly' ); ?></label>
                                <input type="radio" name="list-videos-big-post-show-meta" id="list-videos-big-post-show-meta-n" value="n" />
                                <label for="list-videos-big-post-show-meta-n"><?php esc_html_e( 'No', 'videofly' ); ?></label>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can choose to show or to hide your meta details for your articles. Meta values include date, author, categories and other article details.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="list-videos-big-post-nr-of-posts">
                            <td>
                                <label for="list-videos-big-post-nr-of-posts"><?php esc_html_e( 'How many posts to extract:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <input type="text" value="" name="list-videos-big-post-nr-of-posts" id="list-videos-big-post-nr-of-posts" size="4"/>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php esc_html_e( 'Content split', 'videofly' ); ?>
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
                                    <?php esc_html_e('Choose your image/content split proportions. You can have them either 1/3, 1/2, 3/4 for your title and 2/3,1/2, 1/4 accordingly. Depending on the text and titles you use, select your split type.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-big-post-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-big-post-order-by" id="list-videos-big-post-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                    <option value="likes"><?php esc_html_e( 'Likes', 'videofly' ); ?></option>
                                    <option value="rand"><?php esc_html_e( 'Random', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-big-post-order-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-big-post-order-direction" id="list-videos-big-post-order-direction">
                                    <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option selected="selected" value="desc"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-big-post-related"><?php esc_html_e('Show related posts', 'videofly'); ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-big-post-related" id="list-videos-big-post-related">
                                    <option value="y"><?php esc_html_e( 'Yes', 'videofly' ); ?></option>
                                    <option selected="selected" value="n"><?php esc_html_e( 'No', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can set each of your grid to show related articles below. A list with other articles will appear below.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-big-post-special-effects"><?php esc_html_e( 'Special effects', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-big-post-special-effects" id="list-videos-big-post-special-effects">
                                    <option value="none"><?php esc_html_e('No effect', 'videofly') ?></option>
                                    <option value="opacited"><?php esc_html_e('Fade in', 'videofly') ?></option>
                                    <option value="rotate-in"><?php esc_html_e('Rotate in', 'videofly') ?></option>
                                    <option value="3dflip"><?php esc_html_e('3d flip', 'videofly') ?></option>
                                    <option value="scaler"><?php esc_html_e('Scale', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="list-videos-big-post-pagination">
                            <td>
                                <label for="list-videos-big-post-pagination"><?php esc_html_e( 'Enable pagination', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-big-post-pagination" id="list-videos-big-post-pagination">
                                    <option selected="selected" value="n"><?php esc_html_e('None', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Enable pagination', 'videofly') ?></option>
                                    <option value="load-more"><?php esc_html_e('Load more', 'videofly') ?></option>
                                    <option value="infinite"><?php esc_html_e('Infinite scrolling', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can add pagination.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="list-videos-super-post hidden">
                    <table cellpadding="10">
                        <tr>
                            <td>
                                <label for="list-videos-super-post-posts-per-row"><?php esc_html_e( 'Number of posts per row', 'videofly' ); ?>:</label>
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
                                    <?php esc_html_e('Your articles will be shown columns. Choose how mane columns do you want to use per line. Note that for mobile devices you will get only ONE element per row for better usability.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="list-videos-super-post-nr-of-posts">
                            <td>
                                <label for="list-videos-super-post-limit"><?php esc_html_e( 'How many posts to extract', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="text" value="" name="list-videos-super-post-limit"  id="list-videos-super-post-limit" size="4"/>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-super-post-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-super-post-order-by" id="list-videos-super-post-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                    <option value="likes"><?php esc_html_e( 'Likes', 'videofly' ); ?></option>
                                    <option value="rand"><?php esc_html_e( 'Random', 'videofly' ); ?></option>
                                </select>
                                 <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                 <label for="list-videos-super-post-order-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-super-post-order-direction" id="list-videos-super-post-order-direction">
                                    <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option selected="selected" value="desc"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-super-post-special-effects"><?php esc_html_e( 'Special effects:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="list-videos-super-post-special-effects" id="list-videos-super-post-special-effects">
                                    <option value="none"><?php esc_html_e('No effect', 'videofly') ?></option>
                                    <option value="opacited"><?php esc_html_e('Fade in', 'videofly') ?></option>
                                    <option value="rotate-in"><?php esc_html_e('Rotate in', 'videofly') ?></option>
                                    <option value="3dflip"><?php esc_html_e('3d flip', 'videofly') ?></option>
                                    <option value="scaler"><?php esc_html_e('Scale', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-super-post-pagination"><?php esc_html_e( 'Enable pagination', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-super-post-pagination" id="list-videos-super-post-pagination">
                                    <option selected="selected" value="n"><?php esc_html_e('None', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Enable pagination', 'videofly') ?></option>
                                    <option value="load-more"><?php esc_html_e('Load more', 'videofly') ?></option>
                                    <option value="infinite"><?php esc_html_e('Infinite scrolling', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can add pagination.', 'videofly'); ?>
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
                                <label for="list-videos-timeline-show-meta"><?php esc_html_e( 'Show meta', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="radio" name="list-videos-timeline-show-meta" id="list-videos-timeline-show-meta-y" value="y"  checked = "checked"  />
                                <label for="list-videos-timeline-show-meta-y"><?php esc_html_e( 'Yes', 'videofly' ); ?></label>
                                <input type="radio" name="list-videos-timeline-show-meta" id="list-videos-timeline-show-meta-n" value="n" />
                                <label for="list-videos-timeline-show-meta-n"><?php esc_html_e( 'No', 'videofly' ); ?></label>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can choose to show or to hide your meta details for your articles. Meta values include date, author, categories and other article details.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-timeline-image"><?php esc_html_e( 'Show image', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-timeline-image" id="list-videos-timeline-image">
                                    <option value="y"><?php esc_html_e( 'Yes', 'videofly' ); ?></option>
                                    <option value="n"><?php esc_html_e( 'No', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Display image', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="list-videos-timeline-nr-of-posts">
                            <td>
                                <label for="list-videos-timeline-post-limit"><?php esc_html_e( 'How many posts to extract', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="text" value="" name="list-videos-timeline-post-limit" id="list-videos-timeline-post-limit" size="4"/>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-timeline-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-timeline-order-by" id="list-videos-timeline-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                    <option value="likes"><?php esc_html_e( 'Likes', 'videofly' ); ?></option>
                                    <option value="rand"><?php esc_html_e( 'Random', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-timeline-order-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-timeline-order-direction" id="list-videos-timeline-order-direction">
                                    <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option value="desc" selected="selected"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-timeline-pagination"><?php esc_html_e( 'Enable pagination', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-timeline-pagination" id="list-videos-timeline-pagination">
                                    <option selected="selected" value="n"><?php esc_html_e('None', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Enable pagination', 'videofly') ?></option>
                                    <option value="load-more"><?php esc_html_e('Load more', 'videofly') ?></option>
                                    <option value="infinite"><?php esc_html_e('Infinite scrolling', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can add pagination.', 'videofly'); ?>
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
                                <label for="list-videos-mosaic-layout"><?php esc_html_e( 'Choose how to show the posts', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-mosaic-layout" id="list-videos-mosaic-layout" class="ts-mosaic-layout">
                                    <option value="rectangles"><?php esc_html_e( 'Rectangles', 'videofly' ); ?></option>
                                    <option value="square"><?php esc_html_e( 'Squares', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose how to show the posts', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="list-videos-mosaic-rows">
                            <td>
                                <label for="list-videos-mosaic-rows"><?php esc_html_e( 'Change number of rows', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-mosaic-rows" id="list-videos-mosaic-rows" class="ts-mosaic-rows">
                                    <option value="2"><?php esc_html_e( '2', 'videofly' ); ?></option>
                                    <option value="3"><?php esc_html_e( '3', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Change number of rows', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="list-videos-mosaic-nr-of-posts">
                            <td>
                                <label for="list-videos-mosaic-post-limit"><?php esc_html_e( 'How many posts to extract', 'videofly' ); ?>:</label>
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
                                    <?php esc_html_e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-mosaic-scroll"><?php esc_html_e( 'Add/remove scroll', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-mosaic-scroll" id="list-videos-mosaic-scroll">
                                    <option value="y"><?php esc_html_e( 'With scroll', 'videofly' ); ?></option>
                                    <option value="n"><?php esc_html_e( 'Without scroll', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Add/remove scroll', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-mosaic-effects"><?php esc_html_e( 'Add effects to scroll', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-mosaic-effects" id="list-videos-mosaic-effects">
                                    <option value="default"><?php esc_html_e( 'Default', 'videofly' ); ?></option>
                                    <option value="fade"><?php esc_html_e( 'Fade in effect', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Change number of rows', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-mosaic-gutter"><?php esc_html_e( 'Add or Remove gutter between posts', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-mosaic-gutter" id="list-videos-mosaic-gutter">
                                    <option value="y"><?php esc_html_e( 'With gutter between posts', 'videofly' ); ?></option>
                                    <option value="n"><?php esc_html_e( 'No gutter', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Add or Remove gutter between posts', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-mosaic-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-mosaic-order-by" id="list-videos-mosaic-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                    <option value="likes"><?php esc_html_e( 'Likes', 'videofly' ); ?></option>
                                    <option value="rand"><?php esc_html_e( 'Random', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-videos-mosaic-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-mosaic-order-direction" id="list-videos-mosaic-order-direction">
                                    <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option value="desc" selected="selected"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="class-list-videos-mosaic-pagination">
                            <td>
                                <label for="list-videos-mosaic-pagination"><?php esc_html_e( 'Enable pagination', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-videos-mosaic-pagination" id="list-videos-mosaic-pagination">
                                    <option selected="selected" value="n"><?php esc_html_e('None', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Enable pagination', 'videofly') ?></option>
                                    <option value="load-more"><?php esc_html_e('Load more', 'videofly') ?></option>
                                    <option value="infinite"><?php esc_html_e('Infinite scrolling', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can add pagination.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

            </div>
        </div>
        <?php endif; ?>
    <?php break ?>
    <?php case 'cart': ?>
        <div class="cart builder-element">
            <h3 class="element-title"><?php esc_html_e('Shopping cart', 'videofly'); ?></h3>
            <p><?php esc_html_e( 'Change the position of the shopping cart', 'videofly'); ?></p>
            <table cellpadding="10">
                <tr>
                    <td width="70px">
                        <?php esc_html_e('Cart align', 'videofly'); ?>
                    </td>
                    <td>
                        <select name="cart-align" id="cart-align">
                            <option value="left"><?php esc_html_e('Left', 'videofly'); ?></option>
                            <option value="right"><?php esc_html_e('Right', 'videofly'); ?></option>
                            <option value="center"><?php esc_html_e('Center', 'videofly'); ?></option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
    <?php break ?>
    <?php case 'breadcrumbs': ?>
        <div class="breadcrumbs builder-element">
            <h3 class="element-title"><?php esc_html_e('Breadcrumbs', 'videofly'); ?></h3>
            <p><?php esc_html_e( 'You can add breadcrumbs form to page', 'videofly' ); ?></p>
        </div>
    <?php break ?>
    <?php case 'latest-custom-posts': ?>
        <div class="latest-custom-posts builder-element">
            <h3 class="element-title"><?php esc_html_e('Latest custom post', 'videofly'); ?></h3>
            <p><?php esc_html_e( 'You can add latest custom post form to page', 'videofly' ); ?></p>
            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="latest-custom-posts-admin-label"><?php esc_html_e('Admin label','videofly'); ?>:</label>
                    </td>
                    <td>
                       <input type="text" id="latest-custom-posts-admin-label" name="latest-custom-posts-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td width="70px">
                        <label for="latest-custom-posts-type"><?php esc_html_e( 'Select post type', 'videofly' ); ?>:</label>
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
                                <select data-placeholder="<?php esc_html_e('Select your custom post type', 'videofly'); ?>" class="ts-custom-select-input" multiple name="latest-custom-posts-type" id="latest-custom-posts-type">
                                    <?php foreach($post_types as $register_name => $post_type) :
                                        if( !in_array($register_name, $post_types_default) ) : ?>
                                            <?php if( !in_array($register_name, $registred_post_type) ) : ?>
                                                <?php if( $no_custom === false ) echo '<option value="0">' . esc_html__('All', 'videofly') . '</option>'; $no_custom = true;
                                                   $registred_post_type[] = $register_name;
                                                ?>
                                            <?php endif; ?>
                                            <option value="<?php echo vdf_var_sanitize($register_name); ?>"><?php echo vdf_var_sanitize($post_type->labels->name); ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                    <?php if( $no_custom === false ) echo '<option value="no-custom-posts">' . esc_html__('No new custom posts', 'videofly') . '</option>'; ?>
                                </select>
                            <?php endif; ?>
                        <div class="ts-option-description">
                            <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
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
                                                <td>' . esc_html__('Select category by custom post type ', 'videofly') . $name_post_type . '</td>
                                                    <td>';
                                        if( isset($categories) && is_array($categories) && !empty($categories) ){

                                            echo '<select data-placeholder="' . esc_html__('Select your category', 'videofly') . '" class="ts-custom-select-input" name="latest-custom-category-' . $name_post_type . '" multiple id="latest-custom-posts-category-' . $name_post_type . '">';
                                            $i = 0;
                                                foreach($categories as $category){
                                                    if( $i == 0 ) echo '<option value="0">' . esc_html__('All', 'videofly') . '</option>';
                                                    echo '<option value="' . $category->slug . '">' . $category->name . '</option>';
                                                    $i++;
                                                }

                                            echo '</select>
                                                 <script>
                                                    jQuery(document).ready(function(){
                                                       ts_select_all_general("#latest-custom-posts-category-' . $name_post_type . '");
                                                    });
                                                 </script>';
                                        }else{
                                            echo '<select data-placeholder="' . esc_html__('Select your category', 'videofly') . '" class="ts-custom-select-input" name="latest-custom-posts-type-no-categories" multiple id="latest-custom-posts-type-no-categories">
                                                   ' . esc_html__('No categories', 'videofly') . '
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
                        <label><?php esc_html_e( 'Show only featured', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <select name="latest-custom-posts-featured" id="latest-custom-posts-featured">
                            <option value="y"><?php esc_html_e( 'Yes', 'videofly' ); ?></option>
                            <option value="n" selected="selected"><?php esc_html_e( 'No', 'videofly' ); ?></option>
                        </select>

                        <div class="ts-option-description">
                            <?php esc_html_e('You can display only featured posts', 'videofly'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="latest-custom-posts-exclude"><?php esc_html_e( 'Exclude post IDs', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <input type="text" value="" name="latest-custom-posts-exclude" id="latest-custom-posts-exclude" size="4"/>
                        <div class="ts-option-description">
                            <?php esc_html_e('Insert the IDs of the posts you want to exclude from showing.', 'videofly'); ?> (ex: <b>100,101,102,104</b>)
                        </div>
                    </td>
                </tr>
                 <tr class="latest-custom-posts-exclude">
                    <td>
                        <label for="latest-custom-posts-exclude-first"><?php esc_html_e( 'Exclude number of first posts', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <input type="text" value="" name="latest-custom-posts-exclude-first" id="latest-custom-posts-exclude-first" size="4"/>
                        <div class="ts-option-description">
                            <?php esc_html_e('Insert the number of the posts you want to exclude from showing.', 'videofly'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="latest-custom-posts-display-mode"><?php esc_html_e( 'How to display', 'videofly' ); ?>:</label>
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
                        <select class="hidden" name="latest-custom-posts-display-mode" id="latest-custom-posts-display-mode">
                            <option value="grid"><?php esc_html_e( 'Grid', 'videofly' ); ?></option>
                            <option value="list"><?php esc_html_e( 'List', 'videofly' ); ?></option>
                            <option value="thumbnails"><?php esc_html_e( 'Thumbnails', 'videofly' ); ?></option>
                            <option value="big-post"><?php esc_html_e( 'Big post', 'videofly' ); ?></option>
                            <option value="super-post"><?php esc_html_e( 'Super Post', 'videofly' ); ?></option>
                            <option value="timeline"><?php esc_html_e( 'Timeline Post', 'videofly' ); ?></option>
                            <option value="mosaic"><?php esc_html_e( 'mosaic Post', 'videofly' ); ?></option>
                        </select>
                        <div class="ts-option-description">
                            <?php esc_html_e('Choose your article view type. Depending on what type of article showcase layout you select you will get different options. You can read more about view types in our documentation files: ', 'videofly'); echo vdf_var_sanitize($touchsize_com); ?>
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
                                <label><?php esc_html_e( 'Behavior', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#latest-custom-posts-grid-behavior" id="latest-custom-posts-grid-behavior-selector">
                                   <li><img class="image-radio-input clickable-element" data-option="none" src="<?php echo get_template_directory_uri().'/images/options/behavior_none.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="carousel" src="<?php echo get_template_directory_uri().'/images/options/behavior_carousel.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="masonry" src="<?php echo get_template_directory_uri().'/images/options/behavior_masonry.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="scroll" src="<?php echo get_template_directory_uri().'/images/options/behavior_scroll.png'; ?>"></li>
                                </ul>
                                <select class="hidden" name="latest-custom-posts-grid-behavior" id="latest-custom-posts-grid-behavior">
                                    <option value="none"><?php esc_html_e( 'Normal', 'videofly' ); ?></option>
                                    <option value="carousel"><?php esc_html_e( 'Carousel', 'videofly' ); ?></option>
                                    <option value="masonry"><?php esc_html_e( 'Masonry', 'videofly' ); ?></option>
                                    <option value="scroll"><?php esc_html_e( 'Scroll', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your posts behavior - you can have them just shown, or you can activate the carousel. If carousel is selected your articles will show up in a line with arrows for navigation. If masonry bahevior is selected - your articles will be arranged in to fit it. Be aware that activating the masonry option the crop settings for image sizes tab will be overwritten.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-grid-image"><?php esc_html_e( 'Show featured image', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-grid-image" id="latest-custom-posts-grid-image">
                                    <option selected="selected" value="y"><?php esc_html_e( 'Yes', 'videofly' ); ?></option>
                                    <option value="n"><?php esc_html_e( 'No', 'videofly' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-grid-title"><?php esc_html_e( 'Title position', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#latest-custom-posts-grid-title" id="latest-custom-posts-grid-title-selector">
                                   <li><img class="image-radio-input clickable-element" data-option="title-above-image" src="<?php echo get_template_directory_uri().'/images/options/grid_view_title_image.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="title-below-image" src="<?php echo get_template_directory_uri().'/images/options/grid_view_title_excerpt.png'; ?>"></li>
                                </ul>
                                <select class="hidden" name="latest-custom-posts-grid-title" id="latest-custom-posts-grid-title">
                                    <option value="title-above-image"><?php esc_html_e( 'Above image', 'videofly' ); ?></option>
                                    <option value="title-below-image" selected="selected"><?php esc_html_e( 'Above excerpt', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Select your title position for you grid posts. You can either have it above the image of above the excerpt. Note that sometimes title may change the position of the meta (date, categories, author) as well.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-grid-show-meta"><?php esc_html_e( 'Show meta', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="radio" name="latest-custom-posts-grid-show-meta" id="latest-custom-posts-grid-show-meta-y" value="y"  checked = "checked"  />
                                <label for="latest-custom-posts-grid-show-meta-y"><?php esc_html_e( 'Yes', 'videofly' ); ?></label>
                                <input type="radio" name="latest-custom-posts-grid-show-meta" id="latest-custom-posts-grid-show-meta-n" value="n" />
                                <label for="latest-custom-posts-grid-show-meta-n"><?php esc_html_e( 'No', 'videofly' ); ?></label>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can choose to show or to hide your meta details for your articles. Meta values include date, author, categories and other article details.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-grid-el-per-row"><?php esc_html_e( 'Elements per row', 'videofly' ); ?>:</label>
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
                                    <?php esc_html_e('Your articles will be shown columns. Choose how mane columns do you want to use per line. Note that for mobile devices you will get only ONE element per row for better usability.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="latest-custom-posts-grid-nr-of-posts">
                            <td>
                                <label for="latest-custom-posts-grid-nr-of-posts"><?php esc_html_e( 'How many posts to extract', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="text" value="" name="latest-custom-posts-grid-nr-of-posts" id="latest-custom-posts-grid-nr-of-posts" size="4"/>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-grid-related"><?php esc_html_e( 'Show related posts', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-grid-related" id="latest-custom-posts-grid-related">
                                    <option value="y"><?php esc_html_e( 'Yes', 'videofly' ); ?></option>
                                    <option selected="selected" value="n"><?php esc_html_e( 'No', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can set each of your big posts to show related articles below. A list with other articles will appear below.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-grid-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-grid-order-by" id="latest-custom-posts-grid-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                    <option value="likes"><?php esc_html_e( 'Likes', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-grid-order-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-grid-order-direction" id="latest-custom-posts-grid-order-direction">
                                    <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option selected="selected" value="desc" selected="selected"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-grid-special-effects"><?php esc_html_e( 'Special effects', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-grid-special-effects" id="latest-custom-posts-grid-special-effects">
                                    <option value="none"><?php esc_html_e('No effect', 'videofly') ?></option>
                                    <option value="opacited"><?php esc_html_e('Fade in', 'videofly') ?></option>
                                    <option value="rotate-in"><?php esc_html_e('Rotate in', 'videofly') ?></option>
                                    <option value="3dflip"><?php esc_html_e('3d flip', 'videofly') ?></option>
                                    <option value="scaler"><?php esc_html_e('Scale', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="latest-custom-posts-grid-pagination">
                            <td>
                                <label for="latest-custom-posts-grid-pagination"><?php esc_html_e( 'Enable pagination', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-grid-pagination" id="latest-custom-posts-grid-pagination">
                                    <option selected="selected" value="n"><?php esc_html_e('None', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Enable pagination', 'videofly') ?></option>
                                    <option value="load-more"><?php esc_html_e('Load more', 'videofly') ?></option>
                                    <option value="infinite"><?php esc_html_e('Infinite scrolling', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can add pagination.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- List options -->
                <div class="latest-custom-posts-list hidden">

                    <table cellpadding="10">
                        <tr>
                            <td>
                                <label for="latest-custom-posts-list-image"><?php esc_html_e( 'Show featured image', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-list-image" id="latest-custom-posts-list-image">
                                    <option selected="selected" value="y"><?php esc_html_e( 'Yes', 'videofly' ); ?></option>
                                    <option value="n"><?php esc_html_e( 'No', 'videofly' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-list-show-meta"><?php esc_html_e( 'Show meta', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="radio" name="latest-custom-posts-list-show-meta" id="latest-custom-posts-list-show-meta-y" value="y"  checked = "checked"  />
                                <label for="latest-custom-posts-list-show-meta-y"><?php esc_html_e( 'Yes', 'videofly' ); ?></label>
                                <input type="radio" name="latest-custom-posts-list-show-meta" id="latest-custom-posts-list-show-meta-n" value="n" />
                                <label for="latest-custom-posts-list-show-meta-n"><?php esc_html_e( 'No', 'videofly' ); ?></label>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can choose to show or to hide your meta details for your articles. Meta values include date, author, categories and other article details.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="latest-custom-posts-list-nr-of-posts">
                            <td>
                                <label for="latest-custom-posts-list-nr-of-posts"><?php esc_html_e( 'How many posts to extract', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="text" value="" name="latest-custom-posts-list-nr-of-posts" id="latest-custom-posts-list-nr-of-posts" size="4"/>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <?php /*
                        <tr>
                            <td>
                                <?php esc_html_e( 'Content split', 'videofly' ); ?>
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
                                    <?php esc_html_e('Choose your title/content split proportions. You can have them either 1/3, 1/2, 3/4 for your title and 2/3,1/2, 1/4 accordingly. Depending on the text and titles you use, select your split type.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                         */?>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-list-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-list-order-by" id="latest-custom-posts-list-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                    <option value="likes"><?php esc_html_e( 'Likes', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-list-order-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-list-order-direction" id="latest-custom-posts-list-order-direction">
                                    <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option value="desc" selected="selected"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-list-special-effects"><?php esc_html_e( 'Special effects:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-list-special-effects" id="latest-custom-posts-list-special-effects">
                                    <option value="none"><?php esc_html_e('No effect', 'videofly') ?></option>
                                    <option value="opacited"><?php esc_html_e('Fade in', 'videofly') ?></option>
                                    <option value="rotate-in"><?php esc_html_e('Rotate in', 'videofly') ?></option>
                                    <option value="3dflip"><?php esc_html_e('3d flip', 'videofly') ?></option>
                                    <option value="scaler"><?php esc_html_e('Scale', 'videofly') ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-list-pagination"><?php esc_html_e( 'Enable pagination', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-list-pagination" id="latest-custom-posts-list-pagination">
                                    <option selected="selected" value="n"><?php esc_html_e('None', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Enable pagination', 'videofly') ?></option>
                                    <option value="load-more"><?php esc_html_e('Load more', 'videofly') ?></option>
                                    <option value="infinite"><?php esc_html_e('Infinite scrolling', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can add pagination.', 'videofly'); ?>
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
                                <label for="latest-custom-posts-thumbnail-title"><?php esc_html_e( 'Title position', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-thumbnail-title" id="latest-custom-posts-thumbnail-title">
                                    <option value="over-image" selected="selected"><?php esc_html_e( 'Over image', 'videofly' ); ?></option>
                                    <option value="below-image"><?php esc_html_e( 'Below image', 'videofly' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label><?php esc_html_e( 'Behavior', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#latest-custom-posts-thumbnails-behavior" id="latest-custom-posts-thumbnails-behavior-selector">
                                   <li><img class="image-radio-input clickable-element" data-option="none" src="<?php echo get_template_directory_uri().'/images/options/behavior_none.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="carousel" src="<?php echo get_template_directory_uri().'/images/options/behavior_carousel.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="masonry" src="<?php echo get_template_directory_uri().'/images/options/behavior_masonry.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="scroll" src="<?php echo get_template_directory_uri().'/images/options/behavior_scroll.png'; ?>"></li>
                                </ul>
                                <select name="latest-custom-posts-thumbnails-behavior" id="latest-custom-posts-thumbnails-behavior" class="hidden">
                                    <option value="none"><?php esc_html_e( 'Normal', 'videofly' ); ?></option>
                                    <option value="carousel"><?php esc_html_e( 'Carousel', 'videofly' ); ?></option>
                                    <option value="masonry"><?php esc_html_e( 'Masonry', 'videofly' ); ?></option>
                                    <option value="scroll"><?php esc_html_e( 'Scroll', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your posts behavior - you can have them just shown, or you can activate the carousel. If carousel is selected your articles will show up in a line with arrows for navigation. If masonry bahevior is selected - your articles will be arranged in to fit it. Be aware that activating the masonry option the crop settings for image sizes tab will be overwritten.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-thumbnail-posts-per-row"><?php esc_html_e( 'Number of posts per row', 'videofly' ); ?>:</label>
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
                                    <?php esc_html_e('Your articles will be shown columns. Choose how mane columns do you want to use per line. Note that for mobile devices you will get only ONE element per row for better usability.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="latest-custom-posts-thumbnails-nr-of-posts">
                            <td>
                                <label for="latest-custom-posts-thumbnail-limit"><?php esc_html_e( 'How many posts to extract', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="text" value="" name="latest-custom-posts-thumbnail-limit"  id="latest-custom-posts-thumbnail-limit" size="4"/>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                         <tr>
                            <td>
                                <label for="latest-custom-posts-thumbnail-show-meta"><?php esc_html_e( 'Show meta:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <input type="radio" name="latest-custom-posts-thumbnail-show-meta" id="latest-custom-posts-thumbnail-show-meta-y" value="y"  checked = "checked"  />
                                <label for="latest-custom-posts-thumbnail-show-meta-y"><?php esc_html_e( 'Yes', 'videofly' ); ?></label>
                                <input type="radio" name="latest-custom-posts-thumbnail-show-meta" id="latest-custom-posts-thumbnail-show-meta-n" value="n" />
                                <label for="latest-custom-posts-thumbnail-show-meta-n"><?php esc_html_e( 'No', 'videofly' ); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-thumbnail-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-thumbnail-order-by" id="latest-custom-posts-thumbnail-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                    <option value="likes"><?php esc_html_e( 'Likes', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-thumbnail-order-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-thumbnail-order-direction" id="latest-custom-posts-thumbnails-order-direction">
                                    <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option value="desc" selected="selected"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-thumbnail-special-effects"><?php esc_html_e( 'Special effects:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-thumbnail-special-effects" id="latest-custom-posts-thumbnail-special-effects">
                                    <option value="none"><?php esc_html_e('No effect', 'videofly') ?></option>
                                    <option value="opacited"><?php esc_html_e('Fade in', 'videofly') ?></option>
                                    <option value="rotate-in"><?php esc_html_e('Rotate in', 'videofly') ?></option>
                                    <option value="3dflip"><?php esc_html_e('3d flip', 'videofly') ?></option>
                                    <option value="scaler"><?php esc_html_e('Scale' , 'videofly') ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-thumbnail-gutter"><?php esc_html_e( 'Remove gutter(space) between articles:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-thumbnail-gutter" id="latest-custom-posts-thumbnail-gutter">
                                    <option value="n"><?php esc_html_e('No', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Yes', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Gutter is the space between your articles. You can remove the space and have your articles sticked one to another.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="latest-custom-posts-thumbnails-pagination">
                            <td>
                                <label for="latest-custom-posts-thumbnails-pagination"><?php esc_html_e( 'Enable pagination', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-thumbnails-pagination" id="latest-custom-posts-thumbnails-pagination">
                                    <option selected="selected" value="n"><?php esc_html_e('None', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Enable pagination', 'videofly') ?></option>
                                    <option value="load-more"><?php esc_html_e('Load more', 'videofly') ?></option>
                                    <option value="infinite"><?php esc_html_e('Infinite scrolling', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can add pagination.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="latest-custom-posts-big-post hidden">
                    <table cellpadding="10">
                        <tr>
                            <td>
                                <label for="latest-custom-posts-big-post-image"><?php esc_html_e( 'Show featured image', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-big-post-image" id="latest-custom-posts-big-post-image">
                                    <option selected="selected" value="y"><?php esc_html_e( 'Yes', 'videofly' ); ?></option>
                                    <option value="n"><?php esc_html_e( 'No', 'videofly' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-big-post-carousel"><?php esc_html_e( 'Show carousel', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-big-post-carousel" id="latest-custom-posts-big-post-carousel">
                                    <option value="y"><?php esc_html_e('Yes', 'videofly') ?></option>
                                    <option selected="selected" value="n"><?php esc_html_e('No', 'videofly') ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-big-post-excerpt"><?php esc_html_e( 'Show excerpt', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-big-post-excerpt" id="latest-custom-posts-big-post-excerpt">
                                    <option selected="selected" value="y"><?php esc_html_e('Yes', 'videofly') ?></option>
                                    <option value="n"><?php esc_html_e('No', 'videofly') ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-big-post-image-position"><?php esc_html_e( 'Image position:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-big-post-image-position" id="latest-custom-posts-big-post-image-position">
                                    <option value="left"><?php esc_html_e( 'Left', 'videofly' ); ?></option>
                                    <option value="right"><?php esc_html_e( 'Right', 'videofly' ); ?></option>
                                    <option value="mosaic"><?php esc_html_e( 'Mosaic', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('The way you want to showcase your big posts.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-big-post-show-meta"><?php esc_html_e( 'Show meta:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <input type="radio" name="latest-custom-posts-big-post-show-meta" id="latest-custom-posts-big-post-show-meta-y" value="y"  checked = "checked"  />
                                <label for="latest-custom-posts-big-post-show-meta-y"><?php esc_html_e( 'Yes', 'videofly' ); ?></label>
                                <input type="radio" name="latest-custom-posts-big-post-show-meta" id="latest-custom-posts-big-post-show-meta-n" value="n" />
                                <label for="latest-custom-posts-big-post-show-meta-n"><?php esc_html_e( 'No', 'videofly' ); ?></label>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can choose to show or to hide your meta details for your articles. Meta values include date, author, categories and other article details.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="latest-custom-posts-big-post-nr-of-posts">
                            <td>
                                <label for="latest-custom-posts-big-post-nr-of-posts"><?php esc_html_e( 'How many posts to extract:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <input type="text" value="" name="latest-custom-posts-big-post-nr-of-posts" id="latest-custom-posts-big-post-nr-of-posts" size="4"/>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php esc_html_e( 'Content split', 'videofly' ); ?>
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
                                    <?php esc_html_e('Choose your image/content split proportions. You can have them either 1/3, 1/2, 3/4 for your title and 2/3,1/2, 1/4 accordingly. Depending on the text and titles you use, select your split type.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-big-post-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-big-post-order-by" id="latest-custom-posts-big-post-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                    <option value="likes"><?php esc_html_e( 'Likes', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-big-post-order-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-big-post-order-direction" id="latest-custom-posts-big-post-order-direction">
                                    <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option selected="selected" value="desc"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-big-post-related"><?php esc_html_e( 'Show related posts', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-big-post-related" id="latest-custom-posts-big-post-related">
                                    <option value="y"><?php esc_html_e( 'Yes', 'videofly' ); ?></option>
                                    <option selected="selected" value="n"><?php esc_html_e( 'No', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can set each of your big posts to show related articles below. A list with other articles will appear below.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-big-post-special-effects"><?php esc_html_e( 'Special effects', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-big-post-special-effects" id="latest-custom-posts-big-post-special-effects">
                                    <option value="none"><?php esc_html_e('No effect', 'videofly') ?></option>
                                    <option value="opacited"><?php esc_html_e('Fade in', 'videofly') ?></option>
                                    <option value="rotate-in"><?php esc_html_e('Rotate in', 'videofly') ?></option>
                                    <option value="3dflip"><?php esc_html_e('3d flip', 'videofly') ?></option>
                                    <option value="scaler"><?php esc_html_e('Scale', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="latest-custom-posts-big-post-pagination">
                            <td>
                                <label for="latest-custom-posts-big-post-pagination"><?php esc_html_e( 'Enable pagination', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-big-post-pagination" id="latest-custom-posts-big-post-pagination">
                                    <option selected="selected" value="n"><?php esc_html_e('None', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Enable pagination', 'videofly') ?></option>
                                    <option value="load-more"><?php esc_html_e('Load more', 'videofly') ?></option>
                                    <option value="infinite"><?php esc_html_e('Infinite scrolling', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can add pagination.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="latest-custom-posts-super-post hidden">
                    <table cellpadding="10">
                        <tr>
                            <td>
                                <label for="latest-custom-posts-super-post-posts-per-row"><?php esc_html_e( 'Number of posts per row', 'videofly' ); ?>:</label>
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
                                    <?php esc_html_e('Your articles will be shown columns. Choose how mane columns do you want to use per line. Note that for mobile devices you will get only ONE element per row for better usability.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="latest-custom-posts-big-post-nr-of-posts">
                            <td>
                                <label for="latest-custom-posts-super-post-limit"><?php esc_html_e( 'How many posts to extract', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="text" value="" name="latest-custom-posts-super-post-limit"  id="latest-custom-posts-super-post-limit" size="4"/>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-super-post-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-super-post-order-by" id="latest-custom-posts-super-post-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                    <option value="likes"><?php esc_html_e( 'Likes', 'videofly' ); ?></option>
                                </select>
                                 <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                 <label for="latest-custom-posts-super-post-order-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-super-post-order-direction" id="latest-custom-posts-super-post-order-direction">
                                    <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option selected="selected" value="desc"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-super-post-special-effects"><?php esc_html_e( 'Special effects:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-super-post-special-effects" id="latest-custom-posts-super-post-special-effects">
                                    <option value="none"><?php esc_html_e('No effect', 'videofly') ?></option>
                                    <option value="opacited"><?php esc_html_e('Fade in', 'videofly') ?></option>
                                    <option value="rotate-in"><?php esc_html_e('Rotate in', 'videofly') ?></option>
                                    <option value="3dflip"><?php esc_html_e('3d flip', 'videofly') ?></option>
                                    <option value="scaler"><?php esc_html_e('Scale', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-super-post-pagination"><?php esc_html_e( 'Enable pagination', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-super-post-pagination" id="latest-custom-posts-super-post-pagination">
                                    <option selected="selected" value="n"><?php esc_html_e('None', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Enable pagination', 'videofly') ?></option>
                                    <option value="load-more"><?php esc_html_e('Load more', 'videofly') ?></option>
                                    <option value="infinite"><?php esc_html_e('Infinite scrolling', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can add pagination.', 'videofly'); ?>
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
                                <label for="latest-custom-posts-timeline-show-meta"><?php esc_html_e( 'Show meta', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="radio" name="latest-custom-posts-timeline-show-meta" id="latest-custom-posts-timeline-show-meta-y" value="y"  checked = "checked"  />
                                <label for="latest-custom-posts-timeline-show-meta-y"><?php esc_html_e( 'Yes', 'videofly' ); ?></label>
                                <input type="radio" name="latest-custom-posts-timeline-show-meta" id="latest-custom-posts-timeline-show-meta-n" value="n" />
                                <label for="latest-custom-posts-timeline-show-meta-n"><?php esc_html_e( 'No', 'videofly' ); ?></label>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can choose to show or to hide your meta details for your articles. Meta values include date, author, categories and other article details.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-timeline-image"><?php esc_html_e( 'Show image', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-timeline-image" id="latest-custom-posts-timeline-image">
                                    <option value="y"><?php esc_html_e( 'Yes', 'videofly' ); ?></option>
                                    <option value="n"><?php esc_html_e( 'No', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Display image', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="latest-custom-posts-timeline-nr-of-posts">
                            <td>
                                <label for="latest-custom-posts-timeline-post-limit"><?php esc_html_e( 'How many posts to extract', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="text" value="" name="latest-custom-posts-timeline-post-limit" id="latest-custom-posts-timeline-post-limit" size="4"/>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-timeline-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-timeline-order-by" id="latest-custom-posts-timeline-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                    <option value="likes"><?php esc_html_e( 'Likes', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-timeline-order-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-timeline-order-direction" id="latest-custom-posts-timeline-order-direction">
                                    <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option value="desc" selected="selected"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-timeline-pagination"><?php esc_html_e( 'Enable pagination', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-timeline-pagination" id="latest-custom-posts-timeline-pagination">
                                    <option selected="selected" value="n"><?php esc_html_e('None', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Enable pagination', 'videofly') ?></option>
                                    <option value="load-more"><?php esc_html_e('Load more', 'videofly') ?></option>
                                    <option value="infinite"><?php esc_html_e('Infinite scrolling', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can add pagination.', 'videofly'); ?>
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
                                <label for="latest-custom-posts-mosaic-layout"><?php esc_html_e( 'Choose how to show the posts', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-mosaic-layout" id="latest-custom-posts-mosaic-layout" class="ts-mosaic-layout">
                                    <option value="rectangles"><?php esc_html_e( 'Rectangles', 'videofly' ); ?></option>
                                    <option value="square"><?php esc_html_e( 'Squares', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose how to show the posts', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="latest-custom-posts-mosaic-rows">
                            <td>
                                <label for="latest-custom-posts-mosaic-rows"><?php esc_html_e( 'Change number of rows', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-mosaic-rows" id="latest-custom-posts-mosaic-rows" class="ts-mosaic-rows">
                                    <option value="2"><?php esc_html_e( '2', 'videofly' ); ?></option>
                                    <option value="3"><?php esc_html_e( '3', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Change number of rows', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="latest-custom-posts-mosaic-nr-of-posts">
                            <td>
                                <label for="latest-custom-posts-mosaic-post-limit"><?php esc_html_e( 'How many posts to extract', 'videofly' ); ?>:</label>
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
                                    <?php esc_html_e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-mosaic-scroll"><?php esc_html_e( 'Add/remove scroll', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-mosaic-scroll" id="latest-custom-posts-mosaic-scroll">
                                    <option value="y"><?php esc_html_e( 'With scroll', 'videofly' ); ?></option>
                                    <option value="n"><?php esc_html_e( 'Without scroll', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Add/remove scroll', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-mosaic-effects"><?php esc_html_e( 'Add effects to scroll', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-mosaic-effects" id="latest-custom-posts-mosaic-effects">
                                    <option value="default"><?php esc_html_e( 'Default', 'videofly' ); ?></option>
                                    <option value="fade"><?php esc_html_e( 'Fade in effect', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Change number of rows', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-mosaic-gutter"><?php esc_html_e( 'Add or Remove gutter between posts', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-mosaic-gutter" id="latest-custom-posts-mosaic-gutter">
                                    <option value="y"><?php esc_html_e( 'With gutter between posts', 'videofly' ); ?></option>
                                    <option value="n"><?php esc_html_e( 'No gutter', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Add or Remove gutter between posts', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-mosaic-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-mosaic-order-by" id="latest-custom-posts-mosaic-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                    <option value="likes"><?php esc_html_e( 'Likes', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="latest-custom-posts-mosaic-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-mosaic-order-direction" id="latest-custom-posts-mosaic-order-direction">
                                    <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option value="desc" selected="selected"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="class-latest-custom-posts-mosaic-pagination">
                            <td>
                                <label for="latest-custom-posts-mosaic-pagination"><?php esc_html_e( 'Enable pagination', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="latest-custom-posts-mosaic-pagination" id="latest-custom-posts-mosaic-pagination">
                                    <option selected="selected" value="n"><?php esc_html_e('None', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Enable pagination', 'videofly') ?></option>
                                    <option value="load-more"><?php esc_html_e('Load more', 'videofly') ?></option>
                                    <option value="infinite"><?php esc_html_e('Infinite scrolling', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can add pagination.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

            </div>
        </div>
    <?php break ?>
    <?php case 'timeline': ?>
        <div class="timeline builder-element">
            <h3 class="element-title"><?php esc_html_e('Timeline features', 'videofly'); ?></h3>
            <p><?php esc_html_e( 'You can add timeline features form to page', 'videofly' ); ?></p>
            <table>
                 <tr>
                    <td>
                        <?php esc_html_e('Admin label:', 'videofly'); ?>
                    </td>
                    <td>
                       <input type="text" id="timeline-admin-label" name="timeline-amin-label" />
                    </td>
                </tr>
            </table>

            <ul id="timeline_items">

            </ul>

            <input type="hidden" id="timeline_content" value="" />
            <input type="button" class="button ts-multiple-add-button" data-element-name="timeline" id="timeline_add_item" value=" <?php esc_html_e('Add New Timeline', 'videofly'); ?>"/>
            <?php
                echo '<script id="timeline_items_template" type="text/template">
                        <li id="list-item-id-{{item-id}}" class="timeline-item ts-multiple-add-list-element">
                            <div class="sortable-meta-element">
                                <span class="tab-arrow icon-down"></span> <span class="timeline-item-tab ts-multiple-item-tab">' . esc_html__('Item:', 'videofly') . ' {{slide-number}}</span>
                            </div>
                            <div class="hidden">
                                <table>
                                    <tr>
                                        <td>'
                                            . esc_html__('Title:', 'videofly') .
                                        '</td>
                                        <td>
                                           <input data-builder-name="title" type="text" id="timeline-{{item-id}}-title" name="timeline[{{item-id}}][title]" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            ' . esc_html__( "Write your text here:",'videofly' ) . '
                                        </td>
                                        <td>
                                            <textarea data-builder-name="text" name="timeline[{{item-id}}][text]" id="timeline-{{item-id}}-text" cols="51" rows="5"></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>' . esc_html__( "Add image",'videofly' ) . '</td>
                                        <td>
                                            <input type="text" name="timeline-{{item-id}}-image" id="timeline-{{item-id}}-image" value="" data-role="media-url" />
                                            <input type="hidden" id="slide_media_id-{{item-id}}" name="timeline_media_id-{{item-id}}" value=""  data-role="media-id" />
                                            <input type="button" id="uploader_{{item-id}}"  class="ts-secondary-button" value="' . esc_html__( "Upload",'videofly' ) . '" />
                                            <div id="image-preview-{{item-id}}"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>'
                                            . esc_html__( 'Align image', 'videofly' ) .
                                        '</td>
                                        <td>
                                            <select data-builder-name="align" name="timeline[{{item-id}}][align]" id="timeline-{{item-id}}-align">
                                                <option value="left">' . esc_html__('Left', 'videofly') .'</option>
                                                <option value="right">' . esc_html__('Right', 'videofly' ) . '</option>
                                            </select>
                                            <div class="ts-option-description">'
                                                . esc_html__('Align image', 'videofly') .
                                            '</div>
                                        </td>
                                    </tr>
                                    <tr class="ts-select-animation">
                                        <td>'
                                            . esc_html__( 'Special effect', 'videofly' ) .
                                        '</td>
                                        <td>'.
                                            vdfAllAnimations('timeline-{{item-id}}-effect', 'effect', 'none', 'showattr') .'
                                        </td>
                                    </tr>
                                    <tr class="ts-select-delay">
                                        <td>'
                                            . esc_html__( 'Delay', 'videofly' ) .
                                        '</td>
                                        <td>'.
                                            vdfAllAnimations('timeline-{{item-id}}-delay', 'delay', 'none', 'showattr') .'
                                        </td>
                                    </tr>
                                </table>
                                <input type="hidden" data-builder-name="item_id" value="{{item-id}}" name="timeline[{{item-id}}][id]" />
                                <input type="button" class="ts-secondary-button remove-item" value="' . esc_html__('Remove', 'videofly') . '" />
                                <a href="#" data-builder-name="timeline" data-element-name="timeline" data-item="timeline-item" data-increment="timeline-items" class="ts-secondary-button ts-multiple-item-duplicate">' . esc_html__('Duplicate Item', 'videofly') . '</a>
                            </div>
                        </li>
                    </script>';
            ?>
        </div>
    <?php break ?>
    <?php case 'ribbon': ?>
        <div class="ribbon builder-element">
            <h3 class="element-title"><?php esc_html_e('Ribbon banner', 'videofly'); ?></h3>
            <p><?php esc_html_e( 'You can add ribbon banner form to page', 'videofly' ); ?></p>
            <table cellpadding="10">
                <tr>
                    <td>
                        <?php esc_html_e('Admin label:', 'videofly'); ?>
                    </td>
                    <td>
                       <input type="text" id="ribbon-admin-label" name="ribbon-amin-label" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Title:', 'videofly'); ?>
                    </td>
                    <td>
                       <input type="text" id="ribbon-title" name="ribbon-title" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Text:', 'videofly'); ?>
                    </td>
                    <td>
                       <textarea id="ribbon-text" style="width:400px; height: 100px" name="ribbon-text"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Text color', 'videofly') ?>:
                    </td>
                    <td>
                       <input class="colors-section-picker" type="text" id="ribbon-text-color" name="ribbon-text-color" value="#FFFFFF"/>
                       <div class="colors-section-picker-div" id="ts-ribbon-text-color-picker"></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Background ribbon banner color', 'videofly') ?>:
                    </td>
                    <td>
                       <input class="colors-section-picker" type="text" id="ribbon-background-color" name="ribbon-background" value="#FFFFFF"/>
                       <div class="colors-section-picker-div" id="ts-ribbon-background-color-picker"></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Ribbon banner align', 'videofly'); ?>
                    </td>
                    <td>
                        <select name="ribbon-align" id="ribbon-align">
                            <option value="ribbon-left"><?php esc_html_e('Left', 'videofly'); ?></option>
                            <option value="ribbon-right"><?php esc_html_e('Right', 'videofly'); ?></option>
                            <option value="ribbon-center"><?php esc_html_e('Center', 'videofly'); ?></option>
                        </select>
                    </td>
                </tr>
            </table>
            <p><?php esc_html_e('Choose your icon button from the library below:', 'videofly'); ?></p>
            <div class="builder-element-icon-toggle">
                <a href="#" class="red-ui-button builder-element-icon-trigger-btn" data-toggle="#builder-element-ribbon-icon-selector"><?php esc_html_e('Show icons','videofly') ?></a>
            </div>

            <div class="ts-icons-container">
                <label>
                    <?php esc_html_e('Search icon', 'videofly'); ?>
                    <input type="search" name="search-icon" value="" />
                </label>
                <?php
                    $icons = vdf_build_list_icons();
                    $li = $icons['li'];
                    $option = $icons['option'];
                ?>
                <ul id="builder-element-ribbon-icon-selector" data-selector="#builder-element-ribbon-icon" class="builder-icon-list ts-custom-selector">
                    <?php echo vdf_var_sanitize($li); ?>
                </ul>
                <select name="builder-element-ribbon-icon" id="builder-element-ribbon-icon" class="hidden">
                    <?php echo vdf_var_sanitize($option); ?>
                </select>
            </div>

            <table cellpadding="10">
                <tr>
                    <td width="70px">
                        <?php esc_html_e('Button align', 'videofly'); ?>
                    </td>
                    <td>
                        <select name="ribbon-button-align" id="ribbon-button-align">
                            <option value="text-left"><?php esc_html_e('Left', 'videofly'); ?></option>
                            <option value="text-right"><?php esc_html_e('Right', 'videofly'); ?></option>
                            <option selected="selected" value="text-center"><?php esc_html_e('Center', 'videofly'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Text button', 'videofly') ?>
                    </td>
                    <td>
                       <input type="text" id="ribbon-button-text" name="ribbon-button-text" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('URL button', 'videofly') ?>:
                    </td>
                    <td>
                       <input type="text" id="ribbon-button-url" name="ribbon-button-url" value=""/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Target button', 'videofly') ?>:
                    </td>
                    <td>
                        <select name="ribbon-button-target" id="ribbon-button-target">
                            <option value="_blank" selected="selected"><?php esc_html_e('_blank', 'videofly'); ?></option>
                            <option value="_self"><?php esc_html_e('_self', 'videofly'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Size button', 'videofly') ?>:
                    </td>
                    <td>
                       <select name="ribbon-button-size" id="ribbon-button-size">
                           <option value="big"><?php esc_html_e('Big', 'videofly') ?></option>
                           <option value="medium" selected="selected"><?php esc_html_e('Medium', 'videofly') ?></option>
                           <option value="small"><?php esc_html_e('Small', 'videofly') ?></option>
                           <option value="xsmall"><?php esc_html_e('xSmall', 'videofly') ?></option>
                       </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Choose your mode to display:', 'videofly') ?>
                    </td>
                    <td>
                       <select name="ribbon-button-mode-dispaly" id="ribbon-button-mode-display">
                           <option value="border-button"><?php esc_html_e('Border button', 'videofly') ?></option>
                           <option value="background-button"><?php esc_html_e('Background button', 'videofly') ?></option>
                       </select>
                    </td>
                </tr>
                <tr class="ribbon-button-background-color">
                    <td>
                        <?php esc_html_e('Background button color', 'videofly') ?>:
                    </td>
                    <td>
                       <input class="colors-section-picker" type="text" id="ribbon-button-background-color" name="ribbon-button-background-color" value="#FFFFFF"/>
                       <div class="colors-section-picker-div" id="ts-ribbon-button-background-color-picker"></div>
                    </td>
                </tr>
                <tr class="ribbon-button-border-color">
                    <td>
                        <?php esc_html_e('Border color button', 'videofly') ?>:
                    </td>
                    <td>
                       <input class="colors-section-picker" type="text" id="ribbon-button-border-color" name="ribbon-button-border-color" value="#FFFFFF"/>
                       <div class="colors-section-picker-div" id="ts-ribbon-button-border-color-picker"></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Button text color', 'videofly') ?>:
                    </td>
                    <td>
                       <input class="colors-section-picker" type="text" id="ribbon-button-text-color" name="ribbon-button-text-color" value="#333333"/>
                       <div class="colors-section-picker-div" id="ts-ribbon-button-text-color-picker"></div>
                    </td>
                </tr>
                <tr>
                    <td valign="top"><?php esc_html_e('Image', 'videofly'); ?></td>
                    <td>
                        <input type="text" value="" name="ribbon-image-url"  id="ribbon-attachment" style="width:300px" />
                        <input type="button" class="ts-secondary-button" id="ribbon-select-image" value="<?php esc_html_e('Upload image', 'videofly'); ?>" />
                        <input type="hidden" value="" class="image_media_id" id="ribbon-media-id" />
                        <div id="ribbon-image-preview"></div>
                    </td>
                </tr>
            </table>
        </div>
    <?php break ?>
    <?php case 'video-carousel': ?>
        <div class="video-carousel builder-element">
            <h3 class="element-title"><?php esc_html_e('Video carousel', 'videofly'); ?></h3>
            <table cellpadding="10">
                <tr>
                    <td>
                        <?php esc_html_e('Admin label:', 'videofly'); ?>
                    </td>
                    <td>
                       <input type="text" id="video-carousel-admin-label" name="video-carousel-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td><?php esc_html_e('Slider source', 'videofly'); ?></td>
                    <td>
                        <select name="video-carousel-source" id="video-carousel-source">
                            <option value="latest-posts"><?php esc_html_e('Latest posts', 'videofly'); ?></option>
                            <option value="latest-galleries"><?php esc_html_e('Latest galleries', 'videofly'); ?></option>
                            <option value="latest-videos"><?php esc_html_e('Latest videos', 'videofly'); ?></option>
                            <option value="custom-slides"><?php esc_html_e('Custom slides', 'videofly'); ?></option>
                            <option value="latest-featured-posts"><?php esc_html_e('Latest featured posts', 'videofly'); ?></option>
                            <option value="latest-featured-galleries"><?php esc_html_e('Latest featured galleries', 'videofly'); ?></option>
                            <option value="latest-featured-videos"><?php esc_html_e('Latest featured videos', 'videofly'); ?></option>
                        </select>
                    </td>
                </tr>
            </table>
            <div class="ts-video-carousel-custom">
                <ul id="video-carousel_items">

                </ul>

                <input type="hidden" id="video-carousel_content" value="" />
                <input type="button" class="button ts-multiple-add-button" data-element-name="video-carousel" id="video-carousel_add_item" value=" <?php esc_html_e('Add New Video Carousel', 'videofly'); ?>" />
                <?php
                    echo '<script id="video-carousel_items_template" type="text/template">';
                    echo '<li id="list-item-id-{{item-id}}" class="video-carousel_item ts-multiple-add-list-element">
                            <div class="sortable-meta-element"><span class="tab-arrow icon-down"></span> <span class="video-carousel-item-tab ts-multiple-item-tab">' . esc_html__('Item:', 'videofly') . ' {{slide-number}}</span></div>
                            <div class="hidden">
                                <table>
                                    <tr>
                                        <td>
                                            ' . esc_html__('Enter your title here:', 'videofly') . '
                                        </td>
                                        <td>
                                           <input data-builder-name="title" type="text" id="video-carousel-{{item-id}}-title" name="video-carousel-[{{item-id}}][title]" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            ' . esc_html__('Enter your description here:', 'videofly') . '
                                        </td>
                                        <td>
                                            <textarea data-builder-name="text" id="video-carousel-{{item-id}}-text" name="video-carousel-[{{item-id}}][text] cols="45"></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            ' . esc_html__('Enter your video url here:', 'videofly') . '
                                        </td>
                                        <td>
                                           <input data-builder-name="embed" type="text" id="video-carousel-{{item-id}}-embed" name="video-carousel-[{{item-id}}][embed]" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            ' . esc_html__('Enter your title url here:', 'videofly') . '
                                        </td>
                                        <td>
                                           <input data-builder-name="url" type="text" id="video-carousel-{{item-id}}-url" name="video-carousel-[{{item-id}}][url]" />
                                        </td>
                                    </tr>
                                </table>
                               <input type="hidden" data-builder-name="item_id" value="{{item-id}}" name="video-carousel[{{item-id}}][id]" />
                               <input type="button" class="ts-secondary-button remove-item" value="' . esc_html__('Remove', 'videofly') . '" />
                               <a href="#" data-item="video-carousel_item" data-increment="video-carousel_items" class="ts-secondary-button ts-multiple-item-duplicate">' . esc_html__('Duplicate Item', 'videofly') . '</a>
                            </div>
                        </li>';
                    echo '</script>';
                ?>
            </div>
        </div>
    <?php break ?>
    <?php case 'count-down': ?>
        <div class="count-down builder-element">
            <h3 class="element-title"><?php esc_html_e('Counter down', 'videofly'); ?></h3>
            <p><?php esc_html_e( 'You can add counter down form to page', 'videofly' ); ?></p>
            <table>
                 <tr>
                    <td>
                        <?php esc_html_e('Admin label', 'videofly'); ?>
                    </td>
                    <td>
                       <input type="text" id="count-down-admin-label" name="count-down-amin-label" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Style', 'videofly'); ?>
                    </td>
                    <td>
                       <select name="count-down-style" id="count-down-style">
                           <option value="small" selected="selected"><?php esc_html_e('Small', 'videofly'); ?></option>
                           <option value="big"><?php esc_html_e('Big', 'videofly'); ?></option>
                       </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Title', 'videofly'); ?>
                    </td>
                    <td>
                       <input type="text" id="count-down-title" name="count-down-title" />
                    </td>
                </tr>
                <tr>
                    <td>
                        Date: (yyyy/mm/dd)
                    </td>
                    <td>
                       <input type="text" id="count-down-date" name="count-down-date" />
                    </td>
                </tr>
                <tr>
                    <td>
                        Time: (hh:mm)'
                    </td>
                    <td>
                       <input type="text" id="count-down-hours" name="count-down-hours" />
                    </td>
                </tr>
            </table>
        </div>
    <?php break ?>
    <?php case 'powerlink': ?>
        <div class="powerlink builder-element">
            <h3 class="element-title"><?php esc_html_e('Powerlink', 'videofly'); ?></h3>
            <table cellpadding="10">
                <tr>
                    <td>
                        <?php esc_html_e('Admin label:', 'videofly'); ?>
                    </td>
                    <td>
                       <input type="text" id="powerlink-admin-label" name="powerlink-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Image URL:', 'videofly'); ?>
                    </td>
                    <td>
                       <input type="text" value="" name="image-powerlink-url"  id="image-powerlink-url" style="width:300px" />
                        <input type="button" class="ts-secondary-button" id="select_powerlink_image" value="<?php esc_html_e('Upload', 'videofly'); ?>" />
                        <input type="hidden" value="" id="powerlink_image_media_id" />
                        <div id="powerlink-image-preview"></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Title', 'videofly'); ?>
                    </td>
                    <td>
                        <input type="text" name="powerlink-title" id="powerlink-title" style="width:250px" value=""/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Button text', 'videofly'); ?>
                    </td>
                    <td>
                        <input type="text" name="powerlink-button-text" id="powerlink-button-text" style="width:250px" value=""/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Button url', 'videofly'); ?>
                    </td>
                    <td>
                        <input type="text" name="powerlink-button-url" id="powerlink-button-url" style="width:250px" value=""/>
                    </td>
                </tr>
            </table>
        </div>
    <?php break ?>
    <?php case 'calendar': ?>
        <div class="calendar builder-element">
            <h3 class="element-title"><?php esc_html_e('Calendar', 'videofly'); ?></h3>
            <table cellpadding="10">
                <tr>
                    <td>
                        <?php esc_html_e('Admin label','videofly'); ?>
                    </td>
                    <td>
                       <input type="text" id="calendar-admin-label" name="calendar-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Calendar cell size','videofly'); ?>
                    </td>
                    <td>
                       <select name="calendar-size" id="calendar-size">
                           <option value="big"><?php esc_html_e('Big', 'videofly'); ?></option>
                           <option value="small"><?php esc_html_e('Small', 'videofly'); ?></option>
                       </select>
                    </td>
                </tr>
            </table>
        </div>
    <?php break ?>
    <?php case 'events': ?>
        <?php if( $is_plugin_active ) : ?>
        <div class="events builder-element">
            <h3 class="element-title"><?php esc_html_e('Events', 'videofly'); ?></h3>
            <!-- Select category -->
            <table cellpadding="10">
                <tr>
                    <td>
                        <?php esc_html_e('Admin label','videofly');?>
                    </td>
                    <td>
                       <input type="text" id="events-admin-label" name="events-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <?php esc_html_e( 'Category', 'videofly' ); ?>
                    </td>
                    <td>
                        <select data-placeholder="<?php esc_html_e('Select your category', 'videofly'); ?>" class="ts-custom-select-input" name="events-category" id="events-category" multiple>
                            <?php $categories = get_categories(array( 'hide_empty' => 0, 'taxonomy' => 'event_categories' )); ?>
                            <?php if ( isset($categories) && is_array($categories) && !empty($categories) ): ?>
                                <?php $i = 0; foreach ($categories as $index => $category): ?>
                                    <?php if( is_object($category) ) : ?>
                                        <?php if( $i == 0 ) echo '<option value="0">' . esc_html__('All', 'videofly') . '</option>'; ?>
                                        <option value="<?php if( is_object($category) ) echo vdf_var_sanitize($category->slug); ?>"><?php if( is_object($category) ) echo vdf_var_sanitize($category->cat_name); ?></option>
                                    <?php $i++; endif; ?>
                                <?php endforeach ?>
                            <?php endif ?>
                        </select>
                        <div class="ts-option-description">
                            <?php esc_html_e('Choose the categories that you want to showcase articles from.', 'videofly'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e( 'Exclude post IDs', 'videofly' ); ?>
                    </td>
                    <td>
                        <input type="text" value="" name="events-exclude" id="events-exclude" size="4"/>
                        <div class="ts-option-description">
                            <?php esc_html_e('Insert the IDs of the posts you want to exclude from showing.', 'videofly'); ?> (ex: <b>100,101,102,104</b>)
                        </div>
                    </td>
                </tr>
                <tr class="events-exclude">
                    <td>
                        <label for="events-exclude-first"><?php esc_html_e( 'Exclude number of first posts', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <input type="text" value="" name="events-exclude-first" id="events-exclude-first" size="4"/>
                        <div class="ts-option-description">
                            <?php esc_html_e('Insert the number of the posts you want to exclude from showing.', 'videofly'); ?>
                        </div>
                    </td>
                </tr>d
                <tr class="events-nr-of-posts">
                    <td>
                        <?php esc_html_e( 'How many posts to extract:', 'videofly' ); ?>
                    </td>
                    <td>
                        <input type="text" value="" name="events-nr-of-posts" id="events-nr-of-posts" size="4"/>
                        <div class="ts-option-description">
                            <?php esc_html_e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'videofly'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e( 'Order by', 'videofly' ); ?>
                    </td>
                    <td>
                        <select name="events-order-by" id="events-order-by">
                            <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                            <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                            <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                            <option value="likes"><?php esc_html_e( 'Likes', 'videofly' ); ?></option>
                            <option value="start-date"><?php esc_html_e( 'Start date', 'videofly' ); ?></option>
                        </select>
                        <div class="ts-option-description">
                            <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e( 'Order direction', 'videofly' ); ?>
                    </td>
                    <td>
                        <select name="events-order-direction" id="events-order-direction">
                            <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                            <option selected="selected" value="desc"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                        </select>
                        <div class="ts-option-description">
                            <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e( 'Special effects', 'videofly' ); ?>
                    </td>
                    <td>
                        <select name="events-special-effects" id="events-special-effects">
                            <option value="none"><?php esc_html_e('No effect', 'videofly') ?></option>
                            <option value="opacited"><?php esc_html_e('Fade in', 'videofly') ?></option>
                            <option value="rotate-in"><?php esc_html_e('Rotate in', 'videofly') ?></option>
                            <option value="3dflip"><?php esc_html_e('3d flip', 'videofly') ?></option>
                            <option value="scaler"><?php esc_html_e('Scale', 'videofly') ?></option>
                        </select>
                        <div class="ts-option-description">
                            <?php esc_html_e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'videofly'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e( 'Enable pagination', 'videofly' ); ?>
                    </td>
                    <td>
                        <select name="events-pagination" id="events-pagination">
                            <option selected="selected" value="n"><?php esc_html_e('None', 'videofly') ?></option>
                            <option value="y"><?php esc_html_e('Enable pagination', 'videofly') ?></option>
                            <option value="load-more"><?php esc_html_e('Load more', 'videofly') ?></option>
                        </select>
                        <div class="ts-option-description">
                            <?php esc_html_e('You can add pagination.', 'videofly'); ?>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <?php endif; ?>
    <?php break ?>
    <?php case 'alert': ?>
        <div class="alert builder-element">
            <h3 class="element-title"><?php esc_html_e('Alert', 'videofly'); ?></h3>

            <p><?php esc_html_e('Choose your icon from the library below:', 'videofly'); ?></p>
            <div class="builder-element-icon-toggle">
                <a href="#" class="red-ui-button builder-element-icon-trigger-btn" data-toggle="#alert-icon-selector"><?php esc_html_e('Show icons','videofly') ?></a>
            </div>

            <div class="ts-icons-container">
                <label>
                    <?php esc_html_e('Search icon', 'videofly'); ?>
                    <input type="search" name="search-icon" value="" />
                </label>
                <?php
                    $icons = vdf_build_list_icons();
                    $li = $icons['li'];
                    $option = $icons['option'];
                ?>
                <ul id="alert-icon-selector" data-selector="#alert-icon" class="builder-icon-list ts-custom-selector">
                    <?php  echo vdf_var_sanitize($li); ?>
                </ul>
                <select name="alert-icon" id="alert-icon" class="hidden">
                    <?php echo vdf_var_sanitize($option); ?>
                </select>
            </div>

            <table cellpadding="10">
                <tr>
                    <td>
                       <?php esc_html_e('Admin label','videofly');?>
                    </td>
                    <td>
                       <input type="text" id="alert-admin-label" name="alert-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Title','videofly');?>
                    </td>
                    <td>
                       <input type="text" id="alert-title" name="alert-title" />
                    </td>
                </tr>
                <tr>
                    <td>
                       <?php esc_html_e('Text', 'videofly'); ?>
                    </td>
                    <td>
                        <textarea cols="50" rows="10" name="alert-text" id="alert-text"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Background color', 'videofly') ?>:
                    </td>
                    <td>
                        <input type="text" id="alert-background-color" class="colors-section-picker" value="#000" name="alert-background-color" />
                        <div class="colors-section-picker-div"></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Text color', 'videofly') ?>:
                    </td>
                    <td>
                        <input type="text" id="alert-text-color" class="colors-section-picker" value="#000" name="alert-text-color" />
                        <div class="colors-section-picker-div"></div>
                    </td>
                </tr>
            </table>
        </div>
    <?php break ?>
    <?php case 'skills': ?>
        <div class="skills builder-element">
            <h3 class="element-title"><?php esc_html_e('Skills', 'videofly'); ?></h3>
            <table cellpadding="10">
                <tr>
                    <td>
                       <?php esc_html_e('Admin label','videofly');?>
                    </td>
                    <td>
                       <input type="text" id="skills-admin-label" name="skills-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td>
                       <?php esc_html_e('Show:','videofly');?>
                    </td>
                    <td>
                        <select name="skills-display-mode" id="skills-display-mode">
                            <option value="horizontal"><?php esc_html_e('Horizontal skills', 'videofly'); ?></option>
                            <option value="vertical"><?php esc_html_e('Vertical skills', 'videofly'); ?></option>
                        </select>
                    </td>
                </tr>
            </table>
            <ul id="skills_items">

            </ul>

            <input type="hidden" id="skills_content" value="" />
            <input type="button" class="button ts-multiple-add-button" data-element-name="skills" id="skills_add_item" value=" <?php esc_html_e('Add New Horizontal skills', 'videofly'); ?>" />
            <?php
                 echo '<script id="skills_items_template" type="text/template">';
                 echo '<li id="list-item-id-{{item-id}}" class="tab-item ts-multiple-add-list-element">
                        <div class="sortable-meta-element"><span class="tab-arrow icon-down"></span> <span class="tab-item-tab ts-multiple-item-tab">' . esc_html__('Horizontal skills', 'videofly') . ': {{slide-number}}</span></div>
                        <div class="hidden">
                             <table>
                                <tr>
                                    <td>
                                        ' . esc_html__('Title', 'videofly') . '
                                    </td>
                                     <td>
                                        <input data-builder-name="title" type="text" id="skills-{{item-id}}-title" name="skills[{{item-id}}][title]" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="skills-{{item-id}}-percentage">' . esc_html__('Percentage', 'videofly') . '</label>
                                    </td>
                                    <td>
                                        <input data-builder-name="percentage" type="text" id="skills-{{item-id}}-percentage" name="skills[{{item-id}}][percentage]"  />
                                        <div id="skills-{{item-id}}-percentage-slider"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        ' . esc_html__('Color', 'videofly') . '
                                    </td>
                                    <td>
                                        <input data-builder-name="color" type="text" value="#777" id="skills-{{item-id}}-color" class="colors-section-picker" name="skills[{{item-id}}][color]" />
                                        <div class="colors-section-picker-div"></div>
                                    </td>
                                </tr>
                            </table>
                            <input type="hidden" data-builder-name="item_id" value="{{item-id}}" name="skills[{{item-id}}][id]" />
                            <input type="button" class="ts-secondary-button remove-item" value="' . esc_html__('Remove', 'videofly') . '" />
                            <a href="#" data-item="skills-item" data-increment="skills-items" class="ts-secondary-button ts-multiple-item-duplicate">'.esc_html__('Duplicate horizontal skill', 'videofly').'</a>
                        </div>
                     </li>';
                 echo '</script>';
            ?>
        </div>
    <?php break ?>
    <?php case 'accordion': ?>
        <div class="accordion builder-element">
            <h3 class="element-title"><?php esc_html_e('Article accordion', 'videofly'); ?></h3>

            <table cellpadding="10">
                <tr>
                    <td>
                        <?php esc_html_e('Admin label:','videofly'); ?>
                    </td>
                    <td>
                       <input type="text" id="accordion-admin-label" name="accordion-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Select post type','videofly'); ?>
                    </td>
                    <td>
                        <select name="accordion-posts-type" id="accordion-posts-type">
                            <option value="event"><?php esc_html_e('Events', 'videofly'); ?></option>
                            <option value="video"><?php esc_html_e('Video', 'videofly'); ?></option>
                            <option value="post"><?php esc_html_e('Posts', 'videofly'); ?></option>
                            <option value="ts-gallery"><?php esc_html_e('Gallery', 'videofly'); ?></option>
                        </select>
                    </td>
                </tr>
                <?php
                $accordion_post_types = array('event', 'video', 'post', 'ts-gallery');
                $acc_exclude_taxonomies = array('post_tag', 'post_format');

                $args=array(
                    'public'   => true,
                    '_builtin' => false
                );
                $post_types = get_post_types($args, 'objects', 'or');

                foreach($accordion_post_types as $acc_post_type) :
                    if( $acc_post_type !== 'post' ){
                        $acc_taxonomies = get_object_taxonomies($acc_post_type);
                        foreach($acc_taxonomies as $acc_taxonomy){
                            if( !in_array($acc_taxonomy, $acc_exclude_taxonomies) ){
                                $taxonomy = $acc_taxonomy;
                            }
                        }
                    }?>

                    <tr class="ts-accordion-category-<?php echo vdf_var_sanitize($acc_post_type ); ?> ts-accordion-category">
                        <td valign="top">
                            <?php echo  esc_html_e('Category', 'videofly' ) . ' ' . $post_types[$acc_post_type]->labels->name; ?>
                        </td>
                        <td>
                            <select data-placeholder="<?php esc_html_e('Select your category', 'videofly'); ?>" class="ts-custom-select-input" name="accordion-<?php echo vdf_var_sanitize($acc_post_type ); ?>-category" id="accordion-<?php echo vdf_var_sanitize($acc_post_type ); ?>-category" multiple>
                                <?php
                                    if( $acc_post_type !== 'post' ) $categories = get_categories(array( 'hide_empty' => 0, 'taxonomy' => $taxonomy ));
                                    else $categories = get_categories(array( 'hide_empty' => 0, 'taxonomy' => 'category', 'type' => 'post'));
                                ?>
                                <?php if ( isset($categories) && is_array($categories) && !empty($categories) ): ?>
                                    <?php $i = 0; foreach ($categories as $index => $category): ?>
                                        <?php if( is_object($category) ) : ?>
                                            <?php if( $i == 0 ) echo '<option value="0">' . esc_html__('All', 'videofly') . '</option>'; ?>
                                            <option value="<?php if( is_object($category) ) echo vdf_var_sanitize($category->slug); ?>"><?php if( is_object($category) ) echo vdf_var_sanitize($category->cat_name); ?></option>
                                        <?php $i++; endif; ?>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </select>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr class="ts-accordion-featured">
                    <td>
                        <label><?php esc_html_e( 'Show only featured', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <select name="accordion-featured" id="accordion-featured">
                            <option value="y"><?php esc_html_e( 'Yes', 'videofly' ); ?></option>
                            <option value="n" selected="selected"><?php esc_html_e( 'No', 'videofly' ); ?></option>
                        </select>

                        <div class="ts-option-description">
                            <?php esc_html_e('You can display only featured posts', 'videofly'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('How many posts to extract','videofly'); ?>
                    </td>
                    <td>
                       <input type="text" id="accordion-nr-of-posts" name="accordion-nr-of-posts" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e( 'Order by', 'videofly' ); ?>
                    </td>
                    <td>
                        <select name="accordion-order-by" id="accordion-order-by">
                            <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                            <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                            <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                        </select>
                        <div class="ts-option-description">
                            <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e( 'Order direction', 'videofly' ); ?>
                    </td>
                    <td>
                        <select name="accordion-order-direction" id="accordion-order-direction">
                            <option value="ASC"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                            <option value="DESC" selected="selected"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                        </select>
                        <div class="ts-option-description">
                            <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    <?php break ?>
    <?php case 'chart': ?>
        <div class="chart builder-element">
            <h3 class="element-title"><?php esc_html_e('Chart', 'videofly'); ?></h3>
            <table cellpadding="10">
                <tr>
                    <td>
                        <?php esc_html_e('Admin label:','videofly'); ?>
                    </td>
                    <td>
                       <input type="text" id="chart-admin-label" name="chart-admin-label" />
                       <div class="ts-option-description">
                           <?php esc_html_e('Add a name visible in the admin panel for this element', 'videofly'); ?>
                       </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Select mode to display chart','videofly'); ?>
                    </td>
                    <td>
                       <select name="chart-mode" id="chart-mode">
                           <option value="line"><?php esc_html_e('Line chart', 'videofly'); ?></option>
                           <option value="pie"><?php esc_html_e('Pie chart', 'videofly'); ?></option>
                       </select>
                       <div class="ts-option-description">
                           <?php esc_html_e('We have 2 types of chart elements available. You can create a line chart (with multiple lines) or a pie chart.', 'videofly'); ?>
                       </div>
                    </td>
                </tr>
            </table>
            <!-- Line chart options -->
            <div class="chart-line-options">
                <table cellpadding="10">
                    <tr>
                        <td>
                            <?php esc_html_e('Labels in format :"label1,label2,label3,..."', 'videofly') ?>
                        </td>
                         <td>
                            <input type="text" id="chart-label" name="chart-label" />
                            <div class="ts-option-description">
                           <?php esc_html_e('Write the labels you will use for your chart. These are the labels standing at the bottom of the chart.', 'videofly'); ?>
                       </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php esc_html_e('Title', 'videofly'); ?>
                        </td>
                         <td>
                            <input value="" type="text" id="chart-title" name="chart-title" />
                            <div class="ts-option-description">
                                <?php esc_html_e('Add a description for your chart.', 'videofly'); ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php esc_html_e('Show lines across the chart', 'videofly'); ?>
                        </td>
                        <td>
                            <select id="chart-scaleShowGridLines" name="chart-scaleShowGridLines">
                                <option selected="selected" value="true"><?php esc_html_e('Yes', 'videofly'); ?></option>
                                <option value="false"><?php esc_html_e('No', 'videofly'); ?></option>
                            </select>
                            <div class="ts-option-description">
                                <?php esc_html_e('Do you want the chart to have lines in the background? It is easier for the eye to look up for information.', 'videofly'); ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php esc_html_e('Colour of the grid lines', 'videofly'); ?>
                        </td>
                        <td>
                            <input type="text" value="#f7f7f7" id="chart-scaleGridLineColor" class="colors-section-picker" name="chart-scaleGridLineColor" />
                            <div class="colors-section-picker-div"></div>
                            <div class="ts-option-description">
                                <?php esc_html_e('Change the color of the grid lines.', 'videofly'); ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php esc_html_e('Width of the grid lines (INTEGER)', 'videofly'); ?>
                        </td>
                        <td>
                            <input type="text" id="chart-scaleGridLineWidth" value="1" name="chart-scaleGridLineWidth" />
                            <div class="ts-option-description">
                                <?php esc_html_e('The lines in the background can be very thin or thick. You decide the way you need them.', 'videofly'); ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php esc_html_e('Show horizontal lines (except X axis)', 'videofly'); ?>
                        </td>
                        <td>
                            <select id="chart-scaleShowHorizontalLines" name="chart-scaleShowHorizontalLines">
                                <option selected="selected" value="true"><?php esc_html_e('Yes', 'videofly'); ?></option>
                                <option value="false"><?php esc_html_e('No', 'videofly'); ?></option>
                            </select>
                            <div class="ts-option-description">
                                <?php esc_html_e('Choose wether you want horizontal lines.', 'videofly'); ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php esc_html_e('Show vertical lines (except Y axis)', 'videofly'); ?>
                        </td>
                        <td>
                            <select id="chart-scaleShowVerticalLines" name="chart-scaleShowVerticalLines">
                                <option selected="selected" value="true"><?php esc_html_e('Yes', 'videofly'); ?></option>
                                <option value="false"><?php esc_html_e('No', 'videofly'); ?></option>
                            </select>
                            <div class="ts-option-description">
                                <?php esc_html_e('Choose wether you want vertical lines.', 'videofly'); ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php esc_html_e('The line is curved between points', 'videofly'); ?>
                        </td>
                        <td>
                            <select id="chart-bezierCurve" name="chart-bezierCurve">
                                <option selected="selected" value="true"><?php esc_html_e('Yes', 'videofly'); ?></option>
                                <option value="false"><?php esc_html_e('No', 'videofly'); ?></option>
                            </select>
                            <div class="ts-option-description">
                                <?php esc_html_e('Do you want the lines (you will add the values below) to be curved between the value points or just straight lines', 'videofly'); ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php esc_html_e('Tension of the bezier curve between points (0.1 - 1)', 'videofly'); ?>
                        </td>
                        <td>
                            <input type="text" value="0.4" id="chart-bezierCurveTension" name="chart-bezierCurveTension" />
                            <div class="ts-option-description">
                                <?php esc_html_e('How curved do you want the lines to be bended', 'videofly'); ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php esc_html_e('Show a dot for each point', 'videofly'); ?>
                        </td>
                        <td>
                            <select id="chart-pointDot" name="chart-pointDot">
                                <option selected="selected" value="true"><?php esc_html_e('Yes', 'videofly'); ?></option>
                                <option value="false"><?php esc_html_e('No', 'videofly'); ?></option>
                            </select>
                            <div class="ts-option-description">
                                <?php esc_html_e('You can add dots for the values in your chart', 'videofly'); ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php esc_html_e('Radius of each point dot in pixels (INTEGER)', 'videofly'); ?>
                        </td>
                        <td>
                            <input type="text" id="chart-pointDotRadius" value="4" name="chart-pointDotRadius" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php esc_html_e('Pixel width of point dot stroke (INTEGER)', 'videofly'); ?>
                        </td>
                        <td>
                            <input type="text" id="chart-pointDotStrokeWidth" value="1" name="chart-pointDotStrokeWidth" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php esc_html_e('Amount extra to add to the radius to cater for hit detection outside the drawn point (INTEGER)', 'videofly'); ?>
                        </td>
                        <td>
                            <input type="text" id="chart-pointHitDetectionRadius" value="20" name="chart-pointHitDetectionRadius" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php esc_html_e('Show a stroke for datasets', 'videofly'); ?>
                        </td>
                        <td>
                            <select id="chart-datasetStroke" name="chart-datasetStroke">
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
                            <input type="text" id="chart-datasetStrokeWidth" value="2" name="chart-datasetStrokeWidth" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php esc_html_e('Fill the dataset with a colour', 'videofly'); ?>
                        </td>
                        <td>
                            <select id="chart-datasetFill" name="chart-datasetFill">
                                <option selected="selected" value="true"><?php esc_html_e('Yes', 'videofly'); ?></option>
                                <option value="false"><?php esc_html_e('No', 'videofly'); ?></option>
                            </select>
                        </td>
                    </tr>
                </table>
                <ul id="chart_line_items">

                </ul>

                <input type="hidden" id="chart_line_content" value="" />
                <input type="button" class="button ts-multiple-add-button" data-element-name="chart_line" id="chart_line_add_item" value=" <?php esc_html_e('Add New Line', 'videofly'); ?>" />
                <?php
                    echo '<script id="chart_line_items_template" type="text/template">';
                    echo '<li id="list-item-id-{{item-id}}" class="tab-item ts-multiple-add-list-element">
                            <div class="sortable-meta-element"><span class="tab-arrow icon-down"></span> <span class="tab-item-tab ts-multiple-item-tab">' . esc_html__('Line', 'videofly') . ': {{slide-number}}</span></div>
                            <div class="hidden">
                                <table>
                                    <tr>
                                        <td>
                                            ' . esc_html__('Title', 'videofly') . '
                                        </td>
                                         <td>
                                            <input data-option-name="title" value="" type="text" id="chart_line-{{item-id}}-title" name="chart_line[{{item-id}}][title]" />
                                            <div class="ts-option-description">
                                                '. esc_html__('A title for your line values', 'videofly') .'
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            ' . esc_html__('Fill color', 'videofly') . '
                                        </td>
                                        <td>
                                            <input data-option-name="fillColor" type="text" value="#ffffff" id="chart_line-{{item-id}}-fillColor" name="chart_line[{{item-id}}][fillColor]" class="colors-section-picker" />
                                            '. esc_html__('Choose a color to fill the value dataset with', 'videofly') .'
                                            <div class="colors-section-picker-div"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            ' . esc_html__('Stroke color', 'videofly') . '
                                        </td>
                                        <td>
                                            <input data-option-name="strokeColor" type="text" value="#ffffff" id="chart_line-{{item-id}}-strokeColor" name="chart_line[{{item-id}}][strokeColor]" class="colors-section-picker" />
                                            '. esc_html__('Choose a stroke color', 'videofly') .'
                                            <div class="colors-section-picker-div"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            ' . esc_html__('Point color', 'videofly') . '
                                        </td>
                                        <td>
                                            <input data-option-name="pointColor" type="text" value="#ffffff" id="chart_line-{{item-id}}-pointColor" name="chart_line[{{item-id}}][pointColor]" class="colors-section-picker" />
                                            '. esc_html__('Choose a color for the points of the values on the chart', 'videofly') .'
                                            <div class="colors-section-picker-div"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            ' . esc_html__('Point stroke color', 'videofly') . '
                                        </td>
                                        <td>
                                            <input data-option-name="pointStrokeColor" type="text" value="#ffffff" id="chart_line-{{item-id}}-pointStrokeColor" name="chart_line[{{item-id}}][pointStrokeColor]" class="colors-section-picker" />
                                            '. esc_html__('Choose a stroke (border) color', 'videofly') .'
                                            <div class="colors-section-picker-div"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            ' . esc_html__('Point highlight fill', 'videofly') . '
                                        </td>
                                        <td>
                                            <input data-option-name="pointHighlightFill" type="text" value="#777" id="chart_line-{{item-id}}-pointHighlightFill" name="chart_line[{{item-id}}][pointHighlightFill]" class="colors-section-picker" />
                                            '. esc_html__('The fill color of the point on the chart', 'videofly') .'
                                            <div class="colors-section-picker-div"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            ' . esc_html__('Point highlight stroke', 'videofly') . '
                                        </td>
                                        <td>
                                            <input data-option-name="pointHighlightStroke" type="text" value="#ffffff" id="chart_line-{{item-id}}-pointHighlightStroke" name="chart_line[{{item-id}}][pointHighlightStroke]" class="colors-section-picker" />
                                            <div class="colors-section-picker-div"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            ' . esc_html__('Data in format 25,35,45,...', 'videofly') . '
                                        </td>
                                        <td>
                                            <input data-option-name="data" type="text" value="" id="chart_line-{{item-id}}-data" name="chart_line[{{item-id}}][data]" class="colors-section-picker" />
                                            '. esc_html__('Set the values for this line. Make sure you add the values in the exact order as the labels above. For example if you have the labels: label1,label2,label3 here you set the values for them in the same order: valueForLabelOne,valueForLabelTwo,valueForLabelThree', 'videofly') .'
                                        </td>
                                    </tr>
                                </table>
                                <input type="hidden" data-option-name="item_id" value="{{item-id}}" name="chart_line[{{item-id}}][id]" />
                                <input type="button" class="ts-secondary-button remove-item" value="' . esc_html__('Remove', 'videofly') . '" />
                                <a href="#" data-item="chart_line-item" data-increment="chart_line-items" class="ts-secondary-button ts-multiple-item-duplicate">'. esc_html__('Duplicate line', 'videofly') .'</a>
                            </div>
                         </li>
                    </script>';
                ?>
            </div>
            <!-- Pie chart options -->
            <div class="chart-pie-options">
                <table>
                    <tr>
                        <td>
                            <?php esc_html_e('Show a stroke on each segment', 'videofly'); ?>
                        </td>
                        <td>
                            <select id="chart-segmentShowStroke" name="chart-segmentShowStroke">
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
                            <input type="text" value="#fff" id="chart-segmentStrokeColor" class="colors-section-picker" name="chart-segmentStrokeColor" />
                            <div class="colors-section-picker-div"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php esc_html_e('The width of each segment stroke (INTEGER)', 'videofly'); ?>
                        </td>
                        <td>
                            <input type="text" value="2" id="chart-segmentStrokeWidth" name="chart-segmentStrokeWidth" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php esc_html_e('The percentage of the chart that we cut out of the middle (INTEGER)', 'videofly'); ?>
                        </td>
                        <td>
                            <input type="text" value="50" id="chart-percentageInnerCutout" name="chart-percentageInnerCutout" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php esc_html_e('Amount of animation steps (INTEGER)', 'videofly'); ?>
                        </td>
                        <td>
                            <input type="text" value="100" id="chart-animationSteps" name="chart-animationSteps" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php esc_html_e('Animate the rotation of the Doughnut', 'videofly'); ?>
                        </td>
                        <td>
                            <select id="chart-animateRotate" name="chart-animateRotate">
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
                            <select id="chart-animateScale" name="chart-animateScale">
                                <option value="true"><?php esc_html_e('Yes', 'videofly'); ?></option>
                                <option selected="selected" value="false"><?php esc_html_e('No', 'videofly'); ?></option>
                            </select>
                        </td>
                    </tr>
                </table>
                <ul id="chart_pie_items">

                </ul>

                <input type="hidden" id="chart_pie_content" value="" />
                <input type="button" class="button ts-multiple-add-button" data-element-name="chart_pie" id="chart_pie_add_item" value=" <?php esc_html_e('Add New Pie', 'videofly'); ?>" />
                <?php
                    echo '<script id="chart_pie_items_template" type="text/template">';
                    echo '<li id="list-item-id-{{item-id}}" class="tab-item ts-multiple-add-list-element">
                            <div class="sortable-meta-element"><span class="tab-arrow icon-down"></span> <span class="tab-item-tab ts-multiple-item-tab">' . esc_html__('Pie', 'videofly') . ': {{slide-number}}</span></div>
                            <div class="hidden">
                                <table>
                                    <tr>
                                        <td>
                                            ' . esc_html__('Label', 'videofly') . '
                                        </td>
                                        <td>
                                            <input data-option-name="title" type="text" value="" id="chart_pie-{{item-id}}-title" name="chart_pie[{{item-id}}][title]" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            ' . esc_html__('Value (INTEGER)', 'videofly') . '
                                        </td>
                                        <td>
                                            <input data-option-name="value" type="text" value="" id="chart_pie-{{item-id}}-value" name="chart_pie[{{item-id}}][value]" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            ' . esc_html__('Color section', 'videofly') . '
                                        </td>
                                        <td>
                                            <input data-option-name="color" type="text" value="#777" id="chart_pie-{{item-id}}-color" class="colors-section-picker" name="chart_pie[{{item-id}}][color]" />
                                            <div class="colors-section-picker-div"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            ' . esc_html__('Hover color', 'videofly') . '
                                        </td>
                                        <td>
                                            <input data-option-name="highlight" type="text" value="#777" id="chart_pie-{{item-id}}-highlight" class="colors-section-picker" name="chart_pie[{{item-id}}][highlight]" />
                                            <div class="colors-section-picker-div"></div>
                                        </td>
                                    </tr>
                                </table>
                                <input type="hidden" data-option-name="item_id" value="{{item-id}}" name="chart_pie[{{item-id}}][id]" />
                                <input type="button" class="ts-secondary-button remove-item" value="' . esc_html__('Remove', 'videofly') . '" />
                                <a href="#" data-item="chart_pie-item" data-increment="chart_pie-items" class="ts-secondary-button ts-multiple-item-duplicate">'.esc_html__('Duplicate pie', 'videofly').'</a>
                            </div>
                         </li>';
                    echo '</script>';
                ?>
            </div>
        </div>
    <?php break ?>
    <?php case 'list-galleries': ?>
        <?php if( $is_plugin_active ) : ?>
        <div class="list-galleries builder-element">
            <h3 class="element-title"><?php esc_html_e('List galleries','videofly');?></h3>
            <!-- Select category -->
            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="list-galleries-admin-label"><?php esc_html_e('Admin label','videofly');?>:</label>
                    </td>
                    <td>
                       <input type="text" id="list-galleries-admin-label" name="list-galleries-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <label for="list-galleries-category"><?php esc_html_e( 'Category', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <select data-placeholder="<?php esc_html_e('Select your category', 'videofly'); ?>" class="ts-custom-select-input" name="list-galleries-category" id="list-galleries-category" multiple>
                            <?php $categories = get_categories(array( 'hide_empty' => 0, 'show_option_all' => '', 'taxonomy' => 'gallery_categories' )); ?>
                            <?php if ( isset($categories) && is_array($categories) && !empty($categories) ) : ?>
                                <?php $i = 1; foreach ($categories as $index => $category): ?>
                                    <?php if( is_object($category) ) : ?>
                                        <?php if( $i === 1 ) echo '<option value="0">' . esc_html__('All', 'videofly') . '</option>'; ?>
                                        <option value="<?php echo vdf_var_sanitize($category->slug ); ?>"><?php echo vdf_var_sanitize($category->cat_name ); ?></option>
                                    <?php endif; $i++; ?>
                                <?php endforeach ?>
                            <?php endif ?>
                        </select>
                        <div class="ts-option-description">
                            <?php esc_html_e('Choose the categories that you want to showcase articles from.', 'videofly'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label><?php esc_html_e( 'Show only featured', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <select name="list-galleries-featured" id="list-galleries-featured">
                            <option value="y"><?php esc_html_e( 'Yes', 'videofly' ); ?></option>
                            <option value="n" selected="selected"><?php esc_html_e( 'No', 'videofly' ); ?></option>
                        </select>

                        <div class="ts-option-description">
                            <?php esc_html_e('You can display only featured posts', 'videofly'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="list-galleries-exclude"><?php esc_html_e( 'Exclude post IDs', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <input type="text" value="" name="list-galleries-exclude" id="list-galleries-exclude" size="4"/>
                        <div class="ts-option-description">
                            <?php esc_html_e('Insert the IDs of the posts you want to exclude from showing.', 'videofly'); ?> (ex: <b>100,101,102,104</b>)
                        </div>
                    </td>
                </tr>
                 <tr class="list-galleries-exclude">
                    <td>
                        <label for="list-galleries-exclude-first"><?php esc_html_e( 'Exclude number of first posts', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <input type="text" value="" name="list-galleries-exclude-first" id="list-galleries-exclude-first" size="4"/>
                        <div class="ts-option-description">
                            <?php esc_html_e('Insert the number of the posts you want to exclude from showing.', 'videofly'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="list-galleries-display-mode"><?php esc_html_e( 'How to display', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-galleries-display-mode" id="list-galleries-display-mode-selector">
                           <li><img class="image-radio-input clickable-element" data-option="grid" src="<?php echo get_template_directory_uri().'/images/options/grid_view.png'; ?>"></li>
                           <li><img class="image-radio-input clickable-element" data-option="list" src="<?php echo get_template_directory_uri().'/images/options/list_view.png'; ?>"></li>
                           <li><img class="image-radio-input clickable-element" data-option="thumbnails" src="<?php echo get_template_directory_uri().'/images/options/thumb_view.png'; ?>"></li>
                           <li><img class="image-radio-input clickable-element" data-option="big-post" src="<?php echo get_template_directory_uri().'/images/options/big_posts_view.png'; ?>"></li>
                           <li><img class="image-radio-input clickable-element" data-option="super-post" src="<?php echo get_template_directory_uri().'/images/options/super_post_view.png'; ?>"></li>
                           <li><img class="image-radio-input clickable-element" data-option="timeline" src="<?php echo get_template_directory_uri().'/images/options/timeline_view.png'; ?>"></li>
                           <li><img class="image-radio-input clickable-element" data-option="mosaic" src="<?php echo get_template_directory_uri().'/images/options/mosaic_view.png'; ?>"></li>
                        </ul>
                        <select class="hidden" name="list-galleries-display-mode" id="list-galleries-display-mode">
                            <option value="grid" selected="selected"><?php esc_html_e( 'Grid', 'videofly' ); ?></option>
                            <option value="list"><?php esc_html_e( 'List', 'videofly' ); ?></option>
                            <option value="thumbnails"><?php esc_html_e( 'Thumbnails', 'videofly' ); ?></option>
                            <option value="big-post"><?php esc_html_e( 'Big post', 'videofly' ); ?></option>
                            <option value="super-post"><?php esc_html_e( 'Super Post', 'videofly' ); ?></option>
                            <option value="timeline"><?php esc_html_e( 'Timeline Post', 'videofly' ); ?></option>
                            <option value="mosaic"><?php esc_html_e( 'mosaic Post', 'videofly' ); ?></option>
                        </select>
                        <div class="ts-option-description">
                            <?php esc_html_e('Choose your article view type. Depending on what type of article showcase layout you select you will get different options. You can read more about view types in our documentation files: ', 'videofly'); echo vdf_var_sanitize($touchsize_com); ?>
                        </div>
                    </td>
                </tr>
            </table>

            <div id="list-galleries-display-mode-options">
                <!-- Grid options -->
                <div class="list-galleries-grid hidden">
                    <table cellpadding="10">
                        <tr>
                            <td>
                                <label><?php esc_html_e( 'Behavior', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-galleries-grid-behavior" id="list-galleries-grid-behavior-selector">
                                   <li><img class="image-radio-input clickable-element" data-option="none" src="<?php echo get_template_directory_uri().'/images/options/behavior_none.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="carousel" src="<?php echo get_template_directory_uri().'/images/options/behavior_carousel.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="masonry" src="<?php echo get_template_directory_uri().'/images/options/behavior_masonry.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="scroll" src="<?php echo get_template_directory_uri().'/images/options/behavior_scroll.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="tabbed" src="<?php echo get_template_directory_uri().'/images/options/behavior_tabs.png'; ?>"></li>
                                </ul>
                                <select class="hidden" name="list-galleries-grid-behavior" id="list-galleries-grid-behavior">
                                    <option value="none"><?php esc_html_e( 'Normal', 'videofly' ); ?></option>
                                    <option value="carousel"><?php esc_html_e( 'Carousel', 'videofly' ); ?></option>
                                    <option value="masonry"><?php esc_html_e( 'Masonry', 'videofly' ); ?></option>
                                    <option value="scroll"><?php esc_html_e( 'Scroll', 'videofly' ); ?></option>
                                    <option value="tabbed"><?php esc_html_e( 'Tabbed', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your posts behavior - you can have them just shown, or you can activate the carousel. If carousel is selected your articles will show up in a line with arrows for navigation. If masonry bahevior is selected - your articles will be arranged in to fit it. Be aware that activating the masonry option the crop settings for image sizes tab will be overwritten.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-grid-image"><?php esc_html_e( 'Show featured image', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-grid-image" id="list-galleries-grid-image">
                                    <option selected="selected" value="y"><?php esc_html_e( 'Yes', 'videofly' ); ?></option>
                                    <option value="n"><?php esc_html_e( 'No', 'videofly' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-grid-title"><?php esc_html_e( 'Title position', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-galleries-grid-title" id="list-galleries-grid-title-selector">
                                   <li><img class="image-radio-input clickable-element" data-option="title-above-image" src="<?php echo get_template_directory_uri().'/images/options/grid_view_title_image.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="title-below-image" src="<?php echo get_template_directory_uri().'/images/options/grid_view_title_excerpt.png'; ?>"></li>
                                </ul>
                                <select class="hidden" name="list-galleries-grid-title" id="list-galleries-grid-title">
                                    <option value="title-above-image"><?php esc_html_e( 'Above image', 'videofly' ); ?></option>
                                    <option value="title-below-image" selected="selected"><?php esc_html_e( 'Above excerpt', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Select your title position for you grid posts. You can either have it above the image of above the excerpt. Note that sometimes title may change the position of the meta (date, categories, author) as well.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-grid-show-meta"><?php esc_html_e( 'Show meta', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="radio" name="list-galleries-grid-show-meta" id="list-galleries-grid-show-meta-y" value="y" />
                                <label for="list-galleries-grid-show-meta-y"><?php esc_html_e( 'Yes', 'videofly' ); ?></label>
                                <input type="radio" checked = "checked" name="list-galleries-grid-show-meta" id="list-galleries-grid-show-meta-n" value="n" />
                                <label for="list-galleries-grid-show-meta-n"><?php esc_html_e( 'No', 'videofly' ); ?></label>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can choose to show or to hide your meta details for your articles. Meta values include date, author, categories and other article details.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-grid-el-per-row"><?php esc_html_e( 'Elements per row', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-galleries-grid-el-per-row" id="list-galleries-grid-el-per-row-selector">
                                   <li><img class="image-radio-input clickable-element" data-option="1" src="<?php echo get_template_directory_uri().'/images/options/per_row_1.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="2" src="<?php echo get_template_directory_uri().'/images/options/per_row_2.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="3" src="<?php echo get_template_directory_uri().'/images/options/per_row_3.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="4" src="<?php echo get_template_directory_uri().'/images/options/per_row_4.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="6" src="<?php echo get_template_directory_uri().'/images/options/per_row_6.png'; ?>"></li>
                                </ul>
                                <select class="hidden" name="list-galleries-grid-el-per-row" id="list-galleries-grid-el-per-row">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3" selected="selected">3</option>
                                    <option value="4">4</option>
                                    <option value="6">6</option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Your articles will be shown columns. Choose how mane columns do you want to use per line. Note that for mobile devices you will get only ONE element per row for better usability.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="list-galleries-grid-nr-of-posts">
                            <td>
                                <label for="list-galleries-grid-nr-of-posts"><?php esc_html_e( 'How many posts to extract', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="text" value="" name="list-galleries-grid-nr-of-posts" id="list-galleries-grid-nr-of-posts" size="4"/>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-grid-related"><?php esc_html_e( 'Show related posts', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-grid-related" id="list-galleries-grid-related">
                                    <option value="y"><?php esc_html_e( 'Yes', 'videofly' ); ?></option>
                                    <option selected="selected" value="n"><?php esc_html_e( 'No', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can set each of your big posts to show related articles below. A list with other articles will appear below.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-grid-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-grid-order-by" id="list-galleries-grid-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                    <option value="likes"><?php esc_html_e( 'Likes', 'videofly' ); ?></option>
                                    <option value="rand"><?php esc_html_e( 'Random', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-grid-order-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-grid-order-direction" id="list-galleries-grid-order-direction">
                                    <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option selected="selected" value="desc" selected="selected"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-grid-special-effects"><?php esc_html_e( 'Special effects', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-grid-special-effects" id="list-galleries-grid-special-effects">
                                    <option value="none"><?php esc_html_e('No effect', 'videofly') ?></option>
                                    <option value="opacited"><?php esc_html_e('Fade in', 'videofly') ?></option>
                                    <option value="rotate-in"><?php esc_html_e('Rotate in', 'videofly') ?></option>
                                    <option value="3dflip"><?php esc_html_e('3d flip', 'videofly') ?></option>
                                    <option value="scaler"><?php esc_html_e('Scale', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="list-galleries-grid-pagination">
                            <td>
                                <label for="list-galleries-grid-pagination"><?php esc_html_e( 'Enable pagination', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-grid-pagination" id="list-galleries-grid-pagination">
                                    <option selected="selected" value="n"><?php esc_html_e('None', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Enable pagination', 'videofly') ?></option>
                                    <option value="load-more"><?php esc_html_e('Load more', 'videofly') ?></option>
                                    <option value="infinite"><?php esc_html_e('Infinite scrolling', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can add pagination.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- List options -->
                <div class="list-galleries-list hidden">

                    <table cellpadding="10">
                        <tr>
                            <td>
                                <label for="list-galleries-list-image"><?php esc_html_e( 'Show featured image', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-list-image" id="list-galleries-list-image">
                                    <option selected="selected" value="y"><?php esc_html_e( 'Yes', 'videofly' ); ?></option>
                                    <option value="n"><?php esc_html_e( 'No', 'videofly' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-list-show-meta"><?php esc_html_e( 'Show meta', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="radio" name="list-galleries-list-show-meta" id="list-galleries-list-show-meta-y" value="y"  checked = "checked"  />
                                <label for="list-galleries-list-show-meta-y"><?php esc_html_e( 'Yes', 'videofly' ); ?></label>
                                <input type="radio" name="list-galleries-list-show-meta" id="list-galleries-list-show-meta-n" value="n" />
                                <label for="list-galleries-list-show-meta-n"><?php esc_html_e( 'No', 'videofly' ); ?></label>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can choose to show or to hide your meta details for your articles. Meta values include date, author, categories and other article details.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="list-galleries-list-nr-of-posts">
                            <td>
                                <label for="list-galleries-list-nr-of-posts"><?php esc_html_e( 'How many posts to extract', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="text" value="" name="list-galleries-list-nr-of-posts" id="list-galleries-list-nr-of-posts" size="4"/>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <?php /*
                        <tr>
                            <td>
                                <?php esc_html_e( 'Content split', 'videofly' ); ?>
                            </td>
                            <td>
                                <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-galleries-list-image-split" id="list-galleries-list-image-split-selector">
                                   <li><img class="image-radio-input clickable-element" data-option="1-3" src="<?php echo get_template_directory_uri().'/images/options/list_view_13.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="1-2" src="<?php echo get_template_directory_uri().'/images/options/list_view_12.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="3-4" src="<?php echo get_template_directory_uri().'/images/options/list_view_34.png'; ?>"></li>
                                </ul>
                                <select class="hidden" name="list-galleries-list-image-split" id="list-galleries-list-image-split">
                                    <option value="1-3">1/3</option>
                                    <option value="1-2">1/2</option>
                                    <option value="3-4">3/4</option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your title/content split proportions. You can have them either 1/3, 1/2, 3/4 for your title and 2/3,1/2, 1/4 accordingly. Depending on the text and titles you use, select your split type.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        */ ?>
                        <tr>
                            <td>
                                <label for="list-galleries-list-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-list-order-by" id="list-galleries-list-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                    <option value="likes"><?php esc_html_e( 'Likes', 'videofly' ); ?></option>
                                    <option value="rand"><?php esc_html_e( 'Random', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-list-order-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-list-order-direction" id="list-galleries-list-order-direction">
                                    <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option value="desc" selected="selected"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-list-special-effects"><?php esc_html_e( 'Special effects:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="list-galleries-list-special-effects" id="list-galleries-list-special-effects">
                                    <option value="none"><?php esc_html_e('No effect', 'videofly') ?></option>
                                    <option value="opacited"><?php esc_html_e('Fade in', 'videofly') ?></option>
                                    <option value="rotate-in"><?php esc_html_e('Rotate in', 'videofly') ?></option>
                                    <option value="3dflip"><?php esc_html_e('3d flip', 'videofly') ?></option>
                                    <option value="scaler"><?php esc_html_e('Scale', 'videofly') ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-list-pagination"><?php esc_html_e( 'Enable pagination', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-list-pagination" id="list-galleries-list-pagination">
                                    <option selected="selected" value="n"><?php esc_html_e('None', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Enable pagination', 'videofly') ?></option>
                                    <option value="load-more"><?php esc_html_e('Load more', 'videofly') ?></option>
                                    <option value="infinite"><?php esc_html_e('Infinite scrolling', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can add pagination.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Thumbnail options -->
                <div class="list-galleries-thumbnails hidden">
                    <table cellpadding="10">
                        <tr>
                            <td>
                                <label for="list-galleries-thumbnail-title"><?php esc_html_e( 'Title position', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-thumbnail-title" id="list-galleries-thumbnail-title">
                                    <option value="over-image" selected="selected"><?php esc_html_e( 'Over image', 'videofly' ); ?></option>
                                    <option value="below-image"><?php esc_html_e( 'Below image', 'videofly' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label><?php esc_html_e( 'Behavior', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-galleries-thumbnails-behavior" id="list-galleries-thumbnails-behavior-selector">
                                   <li><img class="image-radio-input clickable-element" data-option="none" src="<?php echo get_template_directory_uri().'/images/options/behavior_none.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="carousel" src="<?php echo get_template_directory_uri().'/images/options/behavior_carousel.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="masonry" src="<?php echo get_template_directory_uri().'/images/options/behavior_masonry.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="scroll" src="<?php echo get_template_directory_uri().'/images/options/behavior_scroll.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="tabbed" src="<?php echo get_template_directory_uri().'/images/options/behavior_tabs.png'; ?>"></li>
                                </ul>
                                <select name="list-galleries-thumbnails-behavior" id="list-galleries-thumbnails-behavior" class="hidden">
                                    <option value="none"><?php esc_html_e( 'Normal', 'videofly' ); ?></option>
                                    <option value="carousel"><?php esc_html_e('Carousel', 'videofly') ?></option>
                                    <option value="masonry"><?php esc_html_e('Masonry', 'videofly') ?></option>
                                    <option value="scroll"><?php esc_html_e('Scroll', 'videofly') ?></option>
                                    <option value="tabbed"><?php esc_html_e( 'Tabbed', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your posts behavior - you can have them just shown, or you can activate the carousel. If carousel is selected your articles will show up in a line with arrows for navigation. If masonry bahevior is selected - your articles will be arranged in to fit it. Be aware that activating the masonry option the crop settings for image sizes tab will be overwritten.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-thumbnail-posts-per-row"><?php esc_html_e( 'Number of posts per row', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-galleries-thumbnail-posts-per-row" id="list-galleries-thumbnail-posts-per-row-selector">
                                   <li><img class="image-radio-input clickable-element" data-option="1" src="<?php echo get_template_directory_uri().'/images/options/per_row_1.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="2" src="<?php echo get_template_directory_uri().'/images/options/per_row_2.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="3" src="<?php echo get_template_directory_uri().'/images/options/per_row_3.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="4" src="<?php echo get_template_directory_uri().'/images/options/per_row_4.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="6" src="<?php echo get_template_directory_uri().'/images/options/per_row_6.png'; ?>"></li>
                                </ul>
                                <select class="hidden" name="list-galleries-thumbnail-posts-per-row" id="list-galleries-thumbnail-posts-per-row">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3" selected="selected">3</option>
                                    <option value="4">4</option>
                                    <option value="6">6</option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Your articles will be shown columns. Choose how mane columns do you want to use per line. Note that for mobile devices you will get only ONE element per row for better usability.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="list-galleries-thumbnails-nr-of-posts">
                            <td>
                                <label for="list-galleries-thumbnail-limit"><?php esc_html_e( 'How many posts to extract', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="text" value="" name="list-galleries-thumbnail-limit"  id="list-galleries-thumbnail-limit" size="4"/>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                         <tr>
                            <td>
                                <label for="list-galleries-thumbnail-show-meta"><?php esc_html_e( 'Show meta:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <input type="radio" name="list-galleries-thumbnail-show-meta" id="list-galleries-thumbnail-show-meta-y" value="y"  checked = "checked"  />
                                <label for="list-galleries-thumbnail-show-meta-y"><?php esc_html_e( 'Yes', 'videofly' ); ?></label>
                                <input type="radio" name="list-galleries-thumbnail-show-meta" id="list-galleries-thumbnail-show-meta-n" value="n" />
                                <label for="list-galleries-thumbnail-show-meta-n"><?php esc_html_e( 'No', 'videofly' ); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-thumbnail-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-thumbnail-order-by" id="list-galleries-thumbnail-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                    <option value="likes"><?php esc_html_e( 'Likes', 'videofly' ); ?></option>
                                    <option value="rand"><?php esc_html_e( 'Random', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-thumbnail-order-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-thumbnail-order-direction" id="list-galleries-thumbnails-order-direction">
                                    <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option value="desc" selected="selected"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-thumbnail-special-effects"><?php esc_html_e( 'Special effects:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="list-galleries-thumbnail-special-effects" id="list-galleries-thumbnail-special-effects">
                                    <option value="none"><?php esc_html_e('No effect', 'videofly') ?></option>
                                    <option value="opacited"><?php esc_html_e('Fade in', 'videofly') ?></option>
                                    <option value="rotate-in"><?php esc_html_e('Rotate in', 'videofly') ?></option>
                                    <option value="3dflip"><?php esc_html_e('3d flip', 'videofly') ?></option>
                                    <option value="scaler"><?php esc_html_e('Scale' , 'videofly') ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-thumbnail-gutter"><?php esc_html_e( 'Remove gutter(space) between articles:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="list-galleries-thumbnail-gutter" id="list-galleries-thumbnail-gutter">
                                    <option value="n"><?php esc_html_e('No', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Yes', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Gutter is the space between your articles. You can remove the space and have your articles sticked one to another.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="list-galleries-thumbnails-pagination">
                            <td>
                                <label for="list-galleries-thumbnails-pagination"><?php esc_html_e( 'Enable pagination', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-thumbnails-pagination" id="list-galleries-thumbnails-pagination">
                                    <option selected="selected" value="n"><?php esc_html_e('None', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Enable pagination', 'videofly') ?></option>
                                    <option value="load-more"><?php esc_html_e('Load more', 'videofly') ?></option>
                                    <option value="infinite"><?php esc_html_e('Infinite scrolling', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can add pagination.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="list-galleries-big-post hidden">
                    <table cellpadding="10">
                        <tr>
                            <td>
                                <label for="list-galleries-big-post-image"><?php esc_html_e( 'Show featured image', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-big-post-image" id="list-galleries-big-post-image">
                                    <option selected="selected" value="y"><?php esc_html_e( 'Yes', 'videofly' ); ?></option>
                                    <option value="n"><?php esc_html_e( 'No', 'videofly' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-big-post-carousel"><?php esc_html_e( 'Show carousel', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="list-galleries-big-post-carousel" id="list-galleries-big-post-carousel">
                                    <option value="y"><?php esc_html_e('Yes', 'videofly') ?></option>
                                    <option selected="selected" value="n"><?php esc_html_e('No', 'videofly') ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-big-post-excerpt"><?php esc_html_e( 'Show excerpt', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="list-galleries-big-post-excerpt" id="list-galleries-big-post-excerpt">
                                    <option selected="selected" value="y"><?php esc_html_e('Yes', 'videofly') ?></option>
                                    <option value="n"><?php esc_html_e('No', 'videofly') ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-big-post-image-position"><?php esc_html_e( 'Image position:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="list-galleries-big-post-image-position" id="list-galleries-big-post-image-position">
                                    <option value="left"><?php esc_html_e( 'Left', 'videofly' ); ?></option>
                                    <option value="right"><?php esc_html_e( 'Right', 'videofly' ); ?></option>
                                    <option value="mosaic"><?php esc_html_e( 'Mosaic', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('The way you want the big posts to be shown', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-big-post-show-meta"><?php esc_html_e( 'Show meta:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <input type="radio" name="list-galleries-big-post-show-meta" id="list-galleries-big-post-show-meta-y" value="y" checked = "checked" />
                                <label for="list-galleries-big-post-show-meta-y"><?php esc_html_e( 'Yes', 'videofly' ); ?></label>
                                <input type="radio" name="list-galleries-big-post-show-meta" id="list-galleries-big-post-show-meta-n" value="n"/>
                                <label for="list-galleries-big-post-show-meta-n"><?php esc_html_e( 'No', 'videofly' ); ?></label>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can choose to show or to hide your meta details for your articles. Meta values include date, author, categories and other article details.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="list-galleries-big-post-nr-of-posts">
                            <td>
                                <label for="list-galleries-big-post-nr-of-posts"><?php esc_html_e( 'How many posts to extract:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <input type="text" value="" name="list-galleries-big-post-nr-of-posts" id="list-galleries-big-post-nr-of-posts" size="4"/>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php esc_html_e( 'Content split', 'videofly' ); ?>
                            </td>
                            <td>
                                <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-galleries-big-post-image-split" id="list-galleries-big-post-image-split-selector">
                                   <li><img class="image-radio-input clickable-element" data-option="1-3" src="<?php echo get_template_directory_uri().'/images/options/big_posts_13.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="1-2" src="<?php echo get_template_directory_uri().'/images/options/big_posts_12.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="3-4" src="<?php echo get_template_directory_uri().'/images/options/big_posts_34.png'; ?>"></li>
                                </ul>
                                <select class="hidden" name="list-galleries-big-post-image-split" id="list-galleries-big-post-image-split">
                                    <option value="1-3">1/3</option>
                                    <option value="1-2">1/2</option>
                                    <option value="3-4">3/4</option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your image/content split proportions. You can have them either 1/3, 1/2, 3/4 for your title and 2/3,1/2, 1/4 accordingly. Depending on the text and titles you use, select your split type.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-big-post-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-big-post-order-by" id="list-galleries-big-post-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                    <option value="likes"><?php esc_html_e( 'Likes', 'videofly' ); ?></option>
                                    <option value="rand"><?php esc_html_e( 'Random', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-big-post-order-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-big-post-order-direction" id="list-galleries-big-post-order-direction">
                                    <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option selected="selected" value="desc"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-big-post-related"><?php esc_html_e( 'Show related posts', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-big-post-related" id="list-galleries-big-post-related">
                                    <option value="y"><?php esc_html_e( 'Yes', 'videofly' ); ?></option>
                                    <option selected="selected" value="n"><?php esc_html_e( 'No', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can set each of your big posts to show related articles below. A list with other articles will appear below.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-big-post-special-effects"><?php esc_html_e( 'Special effects', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-big-post-special-effects" id="list-galleries-big-post-special-effects">
                                    <option value="none"><?php esc_html_e('No effect', 'videofly') ?></option>
                                    <option value="opacited"><?php esc_html_e('Fade in', 'videofly') ?></option>
                                    <option value="rotate-in"><?php esc_html_e('Rotate in', 'videofly') ?></option>
                                    <option value="3dflip"><?php esc_html_e('3d flip', 'videofly') ?></option>
                                    <option value="scaler"><?php esc_html_e('Scale', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="list-galleries-big-post-pagination">
                            <td>
                                <label for="list-galleries-big-post-pagination"><?php esc_html_e( 'Enable pagination', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-big-post-pagination" id="list-galleries-big-post-pagination">
                                    <option selected="selected" value="n"><?php esc_html_e('None', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Enable pagination', 'videofly') ?></option>
                                    <option value="load-more"><?php esc_html_e('Load more', 'videofly') ?></option>
                                    <option value="infinite"><?php esc_html_e('Infinite scrolling', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can add pagination.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="list-galleries-super-post hidden">
                    <table cellpadding="10">
                        <tr>
                            <td>
                                <label for="list-galleries-super-post-posts-per-row"><?php esc_html_e( 'Number of posts per row', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <ul class="imageRadioMetaUl perRow-3 ts-custom-selector" data-selector="#list-galleries-super-post-posts-per-row" id="list-galleries-super-post-posts-per-row-selector">
                                   <li><img class="image-radio-input clickable-element" data-option="1" src="<?php echo get_template_directory_uri().'/images/options/per_row_1.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="2" src="<?php echo get_template_directory_uri().'/images/options/per_row_2.png'; ?>"></li>
                                   <li><img class="image-radio-input clickable-element" data-option="3" src="<?php echo get_template_directory_uri().'/images/options/per_row_3.png'; ?>"></li>
                                </ul>
                                <select class="hidden" name="list-galleries-super-post-posts-per-row" id="list-galleries-super-post-posts-per-row">
                                    <option value="1" selected="selected">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Your articles will be shown columns. Choose how mane columns do you want to use per line. Note that for mobile devices you will get only ONE element per row for better usability.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="list-galleries-big-post-nr-of-posts">
                            <td>
                                <label for="list-galleries-super-post-limit"><?php esc_html_e( 'How many posts to extract', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="text" value="" name="list-galleries-super-post-limit"  id="list-galleries-super-post-limit" size="4"/>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-super-post-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-super-post-order-by" id="list-galleries-super-post-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                    <option value="likes"><?php esc_html_e( 'Likes', 'videofly' ); ?></option>
                                    <option value="rand"><?php esc_html_e( 'Random', 'videofly' ); ?></option>
                                </select>
                                 <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                 <label for="list-galleries-super-post-order-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-super-post-order-direction" id="list-galleries-super-post-order-direction">
                                    <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option selected="selected" value="desc"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-super-post-special-effects"><?php esc_html_e( 'Special effects:', 'videofly' ); ?></label>
                            </td>
                            <td>
                                <select name="list-galleries-super-post-special-effects" id="list-galleries-super-post-special-effects">
                                    <option value="none"><?php esc_html_e('No effect', 'videofly') ?></option>
                                    <option value="opacited"><?php esc_html_e('Fade in', 'videofly') ?></option>
                                    <option value="rotate-in"><?php esc_html_e('Rotate in', 'videofly') ?></option>
                                    <option value="3dflip"><?php esc_html_e('3d flip', 'videofly') ?></option>
                                    <option value="scaler"><?php esc_html_e('Scale', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('Your articles can have effects. Effects usually appear when you scroll down the page and they get into your viewport. You can check more details on how they work in our documentation files.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-super-post-pagination"><?php esc_html_e( 'Enable pagination', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-super-post-pagination" id="list-galleries-super-post-pagination">
                                    <option selected="selected" value="n"><?php esc_html_e('None', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Enable pagination', 'videofly') ?></option>
                                    <option value="load-more"><?php esc_html_e('Load more', 'videofly') ?></option>
                                    <option value="infinite"><?php esc_html_e('Infinite scrolling', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can add pagination.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- Timeline options -->
                <div class="list-galleries-timeline hidden">

                    <table cellpadding="10">
                        <tr>
                            <td>
                                <label for="list-galleries-timeline-show-meta"><?php esc_html_e( 'Show meta', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="radio" name="list-galleries-timeline-show-meta" id="list-galleries-timeline-show-meta-y" value="y"  checked = "checked"  />
                                <label for="list-galleries-timeline-show-meta-y"><?php esc_html_e( 'Yes', 'videofly' ); ?></label>
                                <input type="radio" name="list-galleries-timeline-show-meta" id="list-galleries-timeline-show-meta-n" value="n" />
                                <label for="list-galleries-timeline-show-meta-n"><?php esc_html_e( 'No', 'videofly' ); ?></label>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can choose to show or to hide your meta details for your articles. Meta values include date, author, categories and other article details.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-timeline-image"><?php esc_html_e( 'Show image', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-timeline-image" id="list-galleries-timeline-image">
                                    <option value="y"><?php esc_html_e( 'Yes', 'videofly' ); ?></option>
                                    <option value="n"><?php esc_html_e( 'No', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Display image', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="list-galleries-timeline-nr-of-posts">
                            <td>
                                <label for="list-galleries-timeline-post-limit"><?php esc_html_e( 'How many posts to extract', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <input type="text" value="" name="list-galleries-timeline-post-limit" id="list-galleries-timeline-post-limit" size="4"/>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-timeline-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-timeline-order-by" id="list-galleries-timeline-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                    <option value="likes"><?php esc_html_e( 'Likes', 'videofly' ); ?></option>
                                    <option value="rand"><?php esc_html_e( 'Random', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-timeline-order-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-timeline-order-direction" id="list-galleries-timeline-order-direction">
                                    <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option value="desc" selected="selected"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-timeline-pagination"><?php esc_html_e( 'Enable pagination', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-timeline-pagination" id="list-galleries-timeline-pagination">
                                    <option selected="selected" value="n"><?php esc_html_e('None', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Enable pagination', 'videofly') ?></option>
                                    <option value="load-more"><?php esc_html_e('Load more', 'videofly') ?></option>
                                    <option value="infinite"><?php esc_html_e('Infinite scrolling', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can add pagination.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- mosaic options -->
                <div class="list-galleries-mosaic hidden">

                    <table cellpadding="10">
                        <tr class="list-galleries-mosaic-layout">
                            <td>
                                <label for="list-galleries-mosaic-layout"><?php esc_html_e( 'Choose how to show the posts', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-mosaic-layout" id="list-galleries-mosaic-layout" class="ts-mosaic-layout">
                                    <option value="rectangles"><?php esc_html_e( 'Rectangles', 'videofly' ); ?></option>
                                    <option value="square"><?php esc_html_e( 'Squares', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose how to show the posts', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="list-galleries-mosaic-rows">
                            <td>
                                <label for="list-galleries-mosaic-rows"><?php esc_html_e( 'Change number of rows', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-mosaic-rows" id="list-galleries-mosaic-rows" class="ts-mosaic-rows">
                                    <option value="2"><?php esc_html_e( '2', 'videofly' ); ?></option>
                                    <option value="3"><?php esc_html_e( '3', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Change number of rows', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="list-galleries-mosaic-nr-of-posts">
                            <td>
                                <label for="list-galleries-mosaic-post-limit"><?php esc_html_e( 'How many posts to extract', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <div class="ts-mosaic-post-limit-rows-2">
                                    <input class="ts-input-slider" type="text" name="list-galleries-mosaic-post-limit-rows-2" id="list-galleries-mosaic-post-limit-rows-2" value="6" disabled />
                                    <div id="list-galleries-mosaic-post-limit-rows-2-slider"></div>
                                </div>
                                <div class="ts-mosaic-post-limit-rows-3">
                                    <input type="text" name="list-galleries-mosaic-post-limit-rows-3" id="list-galleries-mosaic-post-limit-rows-3" value="" disabled />
                                    <div id="list-galleries-mosaic-post-limit-rows-3-slider"></div>
                                </div>
                                <div class="ts-mosaic-post-limit-squares">
                                    <input type="text" name="list-galleries-mosaic-post-limit-rows-squares" id="list-galleries-mosaic-post-limit-rows-squares" value="" disabled />
                                    <div id="list-galleries-mosaic-post-limit-rows-squares-slider"></div>
                                </div>

                                <div class="ts-option-description">
                                    <?php esc_html_e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-mosaic-scroll"><?php esc_html_e( 'Add/remove scroll', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-mosaic-scroll" id="list-galleries-mosaic-scroll">
                                    <option value="y"><?php esc_html_e( 'With scroll', 'videofly' ); ?></option>
                                    <option value="n"><?php esc_html_e( 'Without scroll', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Add/remove scroll', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-mosaic-effects"><?php esc_html_e( 'Add effects to scroll', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-mosaic-effects" id="list-galleries-mosaic-effects">
                                    <option value="default"><?php esc_html_e( 'Default', 'videofly' ); ?></option>
                                    <option value="fade"><?php esc_html_e( 'Fade in effect', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Change number of rows', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-mosaic-gutter"><?php esc_html_e( 'Add or Remove gutter between posts', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-mosaic-gutter" id="list-galleries-mosaic-gutter">
                                    <option value="y"><?php esc_html_e( 'With gutter between posts', 'videofly' ); ?></option>
                                    <option value="n"><?php esc_html_e( 'No gutter', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Add or Remove gutter between posts', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-mosaic-order-by"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-mosaic-order-by" id="list-galleries-mosaic-order-by">
                                    <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                                    <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                                    <option value="likes"><?php esc_html_e( 'Likes', 'videofly' ); ?></option>
                                    <option value="rand"><?php esc_html_e( 'Random', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="list-galleries-mosaic-direction"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-mosaic-order-direction" id="list-galleries-mosaic-order-direction">
                                    <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                                    <option value="desc" selected="selected"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                                </select>

                                <div class="ts-option-description">
                                    <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="class-list-galleries-mosaic-pagination">
                            <td>
                                <label for="list-galleries-mosaic-pagination"><?php esc_html_e( 'Enable pagination', 'videofly' ); ?>:</label>
                            </td>
                            <td>
                                <select name="list-galleries-mosaic-pagination" id="list-galleries-mosaic-pagination">
                                    <option selected="selected" value="n"><?php esc_html_e('None', 'videofly') ?></option>
                                    <option value="y"><?php esc_html_e('Enable pagination', 'videofly') ?></option>
                                    <option value="load-more"><?php esc_html_e('Load more', 'videofly') ?></option>
                                    <option value="infinite"><?php esc_html_e('Infinite scrolling', 'videofly') ?></option>
                                </select>
                                <div class="ts-option-description">
                                    <?php esc_html_e('You can add pagination.', 'videofly'); ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

            </div>
        </div>
        <?php endif; ?>
    <?php break ?>
    <?php case 'gallery': ?>
        <div class="gallery builder-element">
            <h3 class="element-title"><?php esc_html_e('Gallery', 'videofly'); ?></h3>
            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="gallery-admin-label"><?php esc_html_e('Admin label','videofly'); ?>:</label>
                    </td>
                    <td>
                       <input type="text" id="gallery-admin-label" name="gallery-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <label for="image_url"><?php esc_html_e( 'Add your images', 'videofly' ); ?>:</label></td>
                    </td>
                    <td>

                        <div class="inside">
                            <div id="post_images_container">
                                <ul class="product_images gallery_images">

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
                                                    var attachmentId = image_url[0];

                                                    var imageFormat = image_url_path[image_url_path.length - 1];
                                                    var imageUrl = image_url_path.splice(0, image_url_path.length - 1).join('.');
                                                    gallery_images.append('\
                                                        <li class="image" data-attachment_id="' + attachmentId + '" data-attachment_url="' + imageUrl + '.' + imageFormat + '">\
                                                            <img src="' + imageUrl + '-<?php echo get_option( "thumbnail_size_w" ); ?>x<?php echo get_option( "thumbnail_size_h" ); ?>.' + imageFormat + '" />\
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
    <?php break ?>
    <?php
        case 'nona':
        case 'boca':
    ?>
        <div class="<?php echo esc_attr($elementType) ?> builder-element">
            <h3 class="element-title"><?php echo ($elementType == 'nona' ? esc_html__('Post Nona Slider', 'videofly') : esc_html__('Post Boca Slider', 'videofly')); ?></h3>
            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="<?php echo esc_attr($elementType) ?>-admin-label"><?php esc_html_e('Admin label','videofly'); ?>:</label>
                    </td>
                    <td>
                        <input type="text" id="<?php echo esc_attr($elementType) ?>-admin-label" name="<?php echo esc_attr($elementType) ?>-admin-label" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><?php esc_html_e('Choose custum post type', 'videofly') ?></p>
                    </td>
                    <td>
                        <select name="<?php echo esc_attr($elementType) ?>-custom-post" id="<?php echo esc_attr($elementType) ?>-custom-post">
                            <option value="post"><?php esc_html_e('Post', 'videofly') ?></option>
                            <?php if( $is_plugin_active ) : ?>
                                <option value="video"><?php esc_html_e('Video', 'videofly') ?></option>
                                <option value="ts-gallery"><?php esc_html_e('Gallery', 'videofly') ?></option>
                            <?php endif; ?>
                        </select>
                    </td>
                </tr>
               <?php if( $is_plugin_active ) : ?>
                <tr class="<?php echo esc_attr($elementType) ?>-video" style="display:none">
                    <td>
                        <p><?php esc_html_e('Categories video', 'videofly') ?></p>
                    </td>
                    <td>
                        <select data-placeholder="<?php esc_html_e('Select your category', 'videofly'); ?>" class="ts-custom-select-input"  name="<?php echo esc_attr($elementType) ?>-categories-video" id="<?php echo esc_attr($elementType) ?>-categories-video" multiple>
                            <?php $categories = get_categories(array( 'hide_empty' => 0, 'taxonomy' => 'videos_categories' )); ?>
                            <?php if ( isset($categories) && is_array($categories) && $categories !== '' && !empty($categories) ): ?>
                                <?php $i = 0; foreach ($categories as $index => $category): ?>
                                    <?php if( is_object($category) ) : ?>
                                        <?php if( $i == 0 ) echo '<option value="0">All</option>'; ?>
                                        <option value="<?php if( is_object($category) ) echo vdf_var_sanitize($category->slug); ?>"><?php if( is_object($category) ) echo vdf_var_sanitize($category->cat_name); ?></option>
                                    <?php $i++; endif; ?>
                                <?php endforeach ?>
                            <?php endif ?>
                        </select>
                    </td>
                </tr>
                <tr class="<?php echo esc_attr($elementType) ?>-ts-gallery" style="display:none">
                    <td>
                        <p><?php esc_html_e('Categories gallery', 'videofly') ?></p>
                    </td>
                    <td>
                        <select data-placeholder="<?php esc_html_e('Select your category', 'videofly'); ?>" class="ts-custom-select-input"  name="<?php echo esc_attr($elementType) ?>-categories-ts-gallery" id="<?php echo esc_attr($elementType) ?>-categories-ts-gallery" multiple>
                            <?php $categories = get_categories(array( 'hide_empty' => 0, 'taxonomy' => 'gallery_categories' )); ?>
                            <?php if ( isset($categories) && is_array($categories) && $categories !== '' && !empty($categories) ): ?>
                                <?php $i = 0; foreach ($categories as $index => $category): ?>
                                    <?php if( is_object($category) ) : ?>
                                        <?php if( $i == 0 ) echo '<option value="0">All</option>'; ?>
                                        <option value="<?php if( is_object($category) ) echo vdf_var_sanitize($category->slug); ?>"><?php if( is_object($category) ) echo vdf_var_sanitize($category->cat_name); ?></option>
                                    <?php $i++; endif; ?>
                                <?php endforeach ?>
                            <?php endif ?>
                        </select>
                    </td>
                </tr>
                <?php endif; ?>
                <tr class="<?php echo esc_attr($elementType) ?>-post" style="display:none">
                    <td>
                        <p><?php esc_html_e('Categories posts', 'videofly') ?></p>
                    </td>
                    <td>
                        <select data-placeholder="<?php esc_html_e('Select your category', 'videofly'); ?>" class="ts-custom-select-input"  name="<?php echo esc_attr($elementType) ?>-categories-post" id="<?php echo esc_attr($elementType) ?>-categories-post" multiple>
                            <?php
                            $categories = get_categories(array( 'hide_empty' => 0 )); ?>
                            <?php if ( isset($categories) && is_array($categories) && $categories !== '' ): ?>
                                <?php $i = 0; foreach ($categories as $index => $category): ?>
                                    <?php if( is_object($category) ) : ?>
                                        <?php if( $i == 0 ) echo '<option value="0">All</option>'; ?>
                                        <option value="<?php echo vdf_var_sanitize($category->slug); ?>"><?php echo vdf_var_sanitize($category->cat_name); ?></option>
                                    <?php $i++; endif; ?>
                                <?php endforeach ?>
                            <?php endif ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label><?php esc_html_e( 'Show only featured', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <select name="<?php echo esc_attr($elementType) ?>-featured" id="<?php echo esc_attr($elementType) ?>-featured">
                            <option value="y"><?php esc_html_e( 'Yes', 'videofly' ); ?></option>
                            <option value="n" selected="selected"><?php esc_html_e( 'No', 'videofly' ); ?></option>
                        </select>
                        <div class="ts-option-description">
                            <?php esc_html_e('You can display only featured posts', 'videofly'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="<?php echo esc_attr($elementType) ?>-orderby"><?php esc_html_e( 'Order by', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <select name="<?php echo esc_attr($elementType) ?>-orderby" id="<?php echo esc_attr($elementType) ?>-orderby">
                            <option value="date"><?php esc_html_e( 'Date', 'videofly' ); ?></option>
                            <option value="comments"><?php esc_html_e( 'Comments', 'videofly' ); ?></option>
                            <option value="views"><?php esc_html_e( 'Views', 'videofly' ); ?></option>
                            <option value="likes"><?php esc_html_e( 'Likes', 'videofly' ); ?></option>
                        </select>
                        <div class="ts-option-description">
                            <?php esc_html_e('Choose your order criteria. You can sort your articles either by date by the number or comments.', 'videofly'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="<?php echo esc_attr($elementType) ?>-order"><?php esc_html_e( 'Order direction', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <select name="<?php echo esc_attr($elementType) ?>-order" id="<?php echo esc_attr($elementType) ?>-order">
                            <option value="asc"><?php esc_html_e( 'ASC', 'videofly' ); ?></option>
                            <option selected="selected" value="desc" selected="selected"><?php esc_html_e( 'DESC', 'videofly' ); ?></option>
                        </select>
                        <div class="ts-option-description">
                            <?php esc_html_e('Choose your order direction. You can sort your articles in an ascending or a descending way.', 'videofly'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="<?php echo esc_attr($elementType) ?>-posts_per_page"><?php esc_html_e( 'How many posts to extract', 'videofly' ); ?>:</label>
                    </td>
                    <td>
                        <input type="text" value="" name="<?php echo esc_attr($elementType) ?>-posts_per_page" id="<?php echo esc_attr($elementType) ?>-posts_per_page" size="4"/>
                        <div class="ts-option-description">
                            <?php esc_html_e('You can limit the number of posts that you want to show. You can set any number, and the system will automatically extract number of posts that you set here.', 'videofly'); ?>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    <?php break; ?>
    <?php case 'instance' : ?>
        <div class="instance builder-element">
            <h3 class="element-title"><?php esc_html_e( 'Instance', 'videofly' ); ?></h3>
            <table cellpadding="10">
                <tr>
                    <td>
                        <label for="instance-admin-label"><?php esc_html_e('Admin label:', 'videofly'); ?></label>
                    </td>
                    <td>
                       <input type="text" id="instance-admin-label" name="instance-admin-label" />
                       <div class="ts-option-description">
                           <?php esc_html_e('Add a name visible in the admin panel for this element', 'videofly'); ?>
                       </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e( 'Title instance', 'videofly' ); ?>
                    </td>
                    <td>
                        <input type="text" name="instance-title" id="instance-title" style="width:250px" value=""/>
                    </td>
                </tr>
                <tr>
                    <td valign="top"><label for="image_url"><?php esc_html_e('Image URL:', 'videofly'); ?></label></td>
                    <td>
                        <input type="text" value="" name="instance-image-url"  id="instance-image-url" class="image_url" style="width:300px" />
                        <input type="button" class="ts-secondary-button" id="select_image" value="Upload" />
                        <input type="hidden" value="" id="image_media_id" />
                        <div id="image_preview"></div>
                        <div class="ts-option-description">
                           <?php esc_html_e('Upload you image in the media library and select it', 'videofly'); ?>
                       </div>
                    </td>
                </tr>
                 <tr>
                    <td>
                        <?php esc_html_e('Image align:', 'videofly'); ?>
                    </td>
                    <td>
                        <select name="instance-align" id="instance-align">
                           <option value="left">Left</option>
                           <option value="center">Center</option>
                           <option value="right">Right</option>
                        </select>
                        <div class="ts-option-description">
                           <?php esc_html_e('Choose the alignment of you image. If the image is smaller than the container, it will be contained to the dimensions of it. If it is smaller, it will be aligned to the selected position.', 'videofly'); ?>
                       </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Button text', 'videofly'); ?>
                    </td>
                    <td>
                        <input type="text" name="instance-button-text" id="instance-button-text" style="width:250px" value=""/>
                    </td>
                </tr>
                 <tr>
                    <td>
                        <?php esc_html_e('Button url', 'videofly'); ?>
                    </td>
                    <td>
                        <input type="text" name="instance-button-url" id="instance-button-url" style="width:250px" value=""/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Target button ', 'videofly'); ?>
                    </td>
                    <td>
                        <select name="instance-button-target" id="instance-button-target">
                            <option value="_blank"><?php esc_html_e('_blank', 'videofly'); ?></option>
                            <option value="_self"><?php esc_html_e('_self', 'videofly'); ?></option>
                        </select>
                        <div class="ts-option-description">
                           <?php esc_html_e('If you set a link for your button you can open it in a new window: _blank or in the same window: _self.', 'videofly'); ?>
                       </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php esc_html_e('Add content', 'videofly'); ?>
                    </td>
                    <td>
                        <textarea id="instance-content" name="instance-content"></textarea>
                    </td>
                </tr>

            </table>
        </div>
    <?php break; ?>
<?php endswitch ?>
    <input type="button" class="button-primary save-element" value="<?php esc_html_e('Save changes', 'videofly'); ?>" id="builder-save"/>
</div>