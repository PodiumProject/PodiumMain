<?php
/**
 * 
 * Video Listing Submit feature 
 * 
 */
 
if (isset($_POST['submit'])) {
    global $user_ID, $post, $posted, $wpdb;

      if (isset($_POST['submit'])) {
            global $user_ID, $post, $posted, $wpdb;
            //Start Post 5
            $image_meta = array();
            $post_meta = array();
            $post_meta = array(
                'geocraft_contact_name' => 'Setu Billa',
                'geocraft_meta_address' => 'American Restaurant, Jaipur, Rajasthan',
                'geocraft_latitude' => '26.912416'
            );
            $img4 = $temp_url . 'thumb3.jpg';
            $image_meta[] = $temp_url . "temp/7.jpg";
            $post_meta = array(
                '_video_url' => esc_attr($_POST['videourl']),
                '_meta_video' => esc_attr($_POST['place_image1']),
                '_meta_image' => esc_attr($_POST['place_image2'])
            );
            $post_data[] = array(
                "post_title" => $_POST['place_title'],
                "post_content" => $wpdb->escape($_POST['description']),
                "post_image" => $image_meta,
                "post_category" => $_POST['category'],
                "post_tags" => $_POST['place_tag']
            );

//End Post 5
            insert_listing_data($post_data);
        }

    
    
}

/**
 * 
 * videocast_submit_video() 
 * call from tpl_video_upload
 */
function videocast_submit_video() {
    global $post, $posted;
    ?> 	
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.validate.min.js"></script>
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri() . '/css/geo_module_style.css'; ?>" />
    <script type="text/javascript" src="<?php echo get_template_directory_uri() . '/js/ajaxupload.js'; ?>"></script> 
    <?php
    require_once(TEMPLATEPATH . '/js/image_upload.php');
    ?>
    <div class="upload-page">
        <form action="" id="uploadForm" name="video-form" class="uploadForm"  method="post" enctype="multipart/form-data">
            <label for="videoName">Video Title<span class="required">*</span></label>
            <input type="text" id="list_title" name="place_title" value="<?php if (isset($posted['place_title']))
        echo $posted['place_title']; ?>"/>
            <br />
            <br />
            <label for="description">Description<span class="required">*</span></label>
            <textarea style="width:250px; height: 100px;" id="description" name="description" row="20" col="25"><?php if (isset($posted['description']))
        echo $posted['description']; ?></textarea>
            <br />
            <br />
            <label for="tags">Tags<span class="required">*</span></label>
            <input type="text" name="place_tag" id="tags" value="" placeholder="Tags" class="required requiredField website" />
            <br/>
            <br/>
            <br/>
            <label for="vcategory">Select Category<span class="required">*</span></label>
            <script type="text/javascript">
                function displaychk_frm(){
                    dom = document.forms['video-form'];
                    chk = dom.elements['category[]'];
                    len = dom.elements['category[]'].length;
        									
                    if(document.getElementById('selectall').checked == true) { 
                        for (i = 0; i < len; i++)
                            chk[i].checked = true ;
                    } else { 
                        for (i = 0; i < len; i++)
                            chk[i].checked = false ;
                    }
                }
            </script>
   <div>
                    <?php
                    global $wpdb;
                    $taxonomy = CUSTOM_CAT_TYPE;
                    $table_prefix = $wpdb->prefix;
                    $wpcat_id = NULL;
                    //Fetch category                          
                    $wpcategories = (array) $wpdb->get_results("
                            SELECT * FROM {$table_prefix}terms, {$table_prefix}term_taxonomy
                            WHERE {$table_prefix}terms.term_id = {$table_prefix}term_taxonomy.term_id
                            AND {$table_prefix}term_taxonomy.taxonomy ='" . $taxonomy . "' and  {$table_prefix}term_taxonomy.parent=0  ORDER BY {$table_prefix}terms.name ASC");

                    $wpcategories = array_values($wpcategories);
                    if ($wpcategories) {
                        echo "<ul class=\"select_cat\">";
                        if ($taxonomy == CUSTOM_CAT_TYPE) {
                            ?>
                            <li><label><input type="checkbox" name="selectall" id="selectall" class="checkbox" onclick="displaychk_frm();" /></label><?php echo 'Select All'; ?></li>

                            <?php
                        }
                        foreach ($wpcategories as $wpcat) {
                            $counter++;
                            $termid = $wpcat->term_id;
                            $name = $wpcat->name;
                            $termprice = $wpcat->term_price;
                            $tparent = $wpcat->parent;
                            ?>
                            <li><label><input class="list_category" type="checkbox" name="category[]" id="<?php echo $termid; ?>" value="<?php echo $name; ?>" class="checkbox" /><?php echo $name; ?></label></li>
                            <?php
                            $args = array(
                                'type' => POST_TYPE,
                                'child_of' => '',
                                'parent' => $termid,
                                'orderby' => 'name',
                                'order' => 'ASC',
                                'hide_empty' => 0,
                                'hierarchical' => 1,
                                'exclude' => $termid,
                                'include' => '',
                                'number' => '',
                                'taxonomy' => CUSTOM_CAT_TYPE,
                                'pad_counts' => false);
                            $acb_cat = get_categories($args);

                            if ($acb_cat) {
                                echo "<ul class=\"children\">";
                                foreach ($acb_cat as $child_of) {
                                    $term = get_term_by('id', $child_of->term_id, CUSTOM_CAT_TYPE);
                                    $termid = $term->term_taxonomy_id;
                                    $term_tax_id = $term->term_id;
                                    $name = $term->name;
                                    ?>
                                    <li><label><input class="list_category" type="checkbox" name="category[]" id="<?php echo $termid; ?>" value="<?php echo $name; ?>" class="checkbox" /><?php echo $name; ?></label></li>
                                    <?php
                                    $args = array(
                                        'type' => POST_TYPE,
                                        'child_of' => '',
                                        'parent' => $term_tax_id,
                                        'orderby' => 'name',
                                        'order' => 'ASC',
                                        'hide_empty' => 0,
                                        'hierarchical' => 1,
                                        'exclude' => $term_tax_id,
                                        'include' => '',
                                        'number' => '',
                                        'taxonomy' => CUSTOM_CAT_TYPE,
                                        'pad_counts' => false);
                                    $acb_cat = get_categories($args);
                                    if ($acb_cat) {
                                        echo "<ul class=\"children\">";
                                        foreach ($acb_cat as $child_of) {
                                            $term = get_term_by('id', $child_of->term_id, CUSTOM_CAT_TYPE);
                                            $termid = $term->term_taxonomy_id;
                                            $term_tax_id = $term->term_id;
                                            $name = $term->name;
                                            ?>
                                            <li><label><input class="list_category" type="checkbox" name="category[]" id="<?php echo $termid; ?>" value="<?php echo $name; ?>" class="checkbox" /><?php echo $name; ?></label></li>
                                            <?php
                                        }
                                        echo "</ul>";
                                    }
                                }
                                echo "</ul>";
                            }
                        }
                        echo "</ul>";
                    }
                    ?>   
                </div> 
            <br/>
            <br/>
            <input type="radio" name="type" value="Youtube" />
            Video From Youtube/Vimeo/Metacafe/Dailymotion
            <input type="radio" name="type" value="Self" />
            Video Self Uploaded <br />
            <br />
            <div id="video_youtube">
                <label for="videourl">Video Url Youtube/Vimeo/Metacafe/Dailymotion<span class="required">*</span></label>
                <input type="text" name="videourl" id="videourl" value="" placeholder="Video Url" class="required requiredField" />
            </div>
            <div id="video_selfhosted">
                <label for="file">Upload Video File:</label>
    <?php
    $images = array(
        'place_image1',
        'place_image2'
    );
    foreach ($images as $image):
        ?> 
                    <div style="margin-bottom: 20px;">
                        <input class='of-input' name='<?php echo $image; ?>' id='place_image1_upload' type='text' value="<?php echo $_POST['$image'] ?>" />
                        <div style="display: inline;" class="upload_button_div"><input type="button" class="button image_upload_button" id="<?php echo $image; ?>" value="Upload Image" />
                            <div class="button image_reset_button hide" id="reset_<?php echo $image; ?>" title="<?php echo $image; ?>" value="reset"></div>
                        </div>             
                    </div>
        <?php
        echo $image . 'syhgfhsgf uhsf' . '</br>';
        echo $posted['$image'];
        ?>
                    <?php
                endforeach;
                ?>  

                <br/>
                <br/>


                <br/>
                <br/>
            </div>
            <input class="submit" id="submit" name="submit" type="submit" value="Saved"/>
        </form>
        <script>     
            jQuery(document).ready(function(){
                jQuery("input[name$='type']").click(function(){
                    var value = jQuery(this).val();
                    if(value=='Youtube') {
                        jQuery("#video_youtube").show();
                        jQuery("#video_selfhosted").hide();
                    }
                    else if(value=='Self') {
                        jQuery("#video_selfhosted").show();
                        jQuery("#video_youtube").hide();
                    }
                });
                jQuery("#video_youtube").show();
                jQuery("#video_selfhosted").hide();
            });
        </script>
    </div>
    <?php
}
?>