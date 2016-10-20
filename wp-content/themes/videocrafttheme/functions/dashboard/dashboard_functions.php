<?php
function user_listing($pid) {
    global $current_user, $wpdb, $expiry_tbl_name;
    $post_type = 'video_listing';
    $sql = "SELECT " . $wpdb->posts . ".*  FROM " . $wpdb->posts . " WHERE " . $wpdb->posts . ".post_author = $current_user->ID AND " . $wpdb->posts . ".post_type = '$post_type' AND (" . $wpdb->posts . ".post_status = 'publish' OR " . $wpdb->posts . ".post_status = 'pending') ORDER BY  " . $wpdb->posts . ".`ID` DESC";
    $query = $wpdb->query($sql); // Get total of Num rows from the database query
    if (isset($_GET['pn'])) { // Get pn from URL vars if it is present
        $pn = preg_replace('#[^0-9]#i', '', $_GET['pn']); // filter everything but numbers for security(new)
    } else { // If the pn URL variable is not present force it to be value of page number 1
        $pn = 1;
    }
    $itemsPerPage = 20;
    $lastPage = ceil($query / $itemsPerPage);
    if ($pn < 1) { // If it is less than 1
        $pn = 1; // force if to be 1
    } else if ($pn > $lastPage) { // if it is greater than $lastpage
        $pn = $lastPage; // force it to be $lastpage's value
    }
    $centerPages = "";
    $sub1 = $pn - 1;
    $sub2 = $pn - 2;
    $add1 = $pn + 1;
    $add2 = $pn + 2;
    if ($pn == 1) {
        $centerPages .= '&nbsp; <span class="active">' . $pn . '</span> &nbsp;';
        $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$pid&pn=$add1") . '">' . $add1 . '</a> &nbsp;';
    } else if ($pn == $lastPage) {
        $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$pid&pn=$sub1") . '">' . $sub1 . '</a> &nbsp;';
        $centerPages .= '&nbsp; <span class="active">' . $pn . '</span> &nbsp;';
    } else if ($pn > 2 && $pn < ($lastPage - 1)) {
        $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$pid&pn=$sub2") . '">' . $sub2 . '</a> &nbsp;';
        $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$pid&pn=$sub1") . '">' . $sub1 . '</a> &nbsp;';
        $centerPages .= '&nbsp; <span class="active">' . $pn . '</span> &nbsp;';
        $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$pid&pn=$add2") . '">' . $add1 . '</a> &nbsp;';
        $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$pid&pn=$add2") . '">' . $add2 . '</a> &nbsp;';
    } else if ($pn > 1 && $pn < $lastPage) {
        $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$pid&pn=$sub1") . '">' . $sub1 . '</a> &nbsp;';
        $centerPages .= '&nbsp; <span class="active">' . $pn . '</span> &nbsp;';
        $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$pid&pn=$add1") . '">' . $add1 . '</a> &nbsp;';
    }
    $limit = 'LIMIT ' . ($pn - 1) * $itemsPerPage . ',' . $itemsPerPage;
    $listings = $wpdb->get_results("SELECT " . $wpdb->posts . ".*  FROM " . $wpdb->posts . " WHERE " . $wpdb->posts . ".post_author = $current_user->ID AND " . $wpdb->posts . ".post_type = '$post_type' AND (" . $wpdb->posts . ".post_status = 'publish' OR " . $wpdb->posts . ".post_status = 'pending') ORDER BY  " . $wpdb->posts . ".`ID` DESC $limit");
    $paginationDisplay = ""; // Initialize the pagination output variable
    if ($lastPage != "1") {
        $paginationDisplay .= 'Page <strong>' . $pn . '</strong> of ' . $lastPage . '&nbsp;  &nbsp;  &nbsp; ';

        if ($pn != 1) {
            $previous = $pn - 1;
            $paginationDisplay .= '&nbsp;  <a href="' . home_url("/?page_id=$pid&pn=$previous") . '"> Back</a> ';
        }
        $paginationDisplay .= '<span class="paginationNumbers">' . $centerPages . '</span>';
        if ($pn != $lastPage) {
            $nextPage = $pn + 1;
            $paginationDisplay .= '&nbsp;  <a href="' . home_url("/?page_id=$pid&pn=$nextPage") . '"> Next</a> ';
        }
    }
    ?>  
    <table id="tblspacer" class="widefat fixed">
        <thead>
            <tr>
                <th scope="col">Listings</th>
    <!--                <th scope="col">Address</th>-->
                <th scope="col">Categories</th>
    <!--                <th scope="col">Tags</th>-->
                <th scope="col">Date</th>
    <!--                <th scope="col">Status</th>-->
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($listings as $listing):
                $address = get_post_meta($listing->ID, 'geocraft_meta_address', true);
                $categories = get_the_term_list($listing->ID, 'video_cat', '', ' ', '');
                $tags = get_the_term_list($listing->ID, 'video_tag', '', ' ', '');
                ?>
                <tr>
                    <td><a href="<?php echo $listing->guid; ?>"><?php echo $listing->post_title; ?></a><br/>
                        <span class="modify"><a href="<?php echo home_url("/?page_id=$pid&action=edit&pid=" . $listing->ID); ?>"><?php echo EDIT; ?></a>&nbsp;|&nbsp;
                            <a href="<?php echo home_url("/?page_id=$pid&action=delete&pid=" . $listing->ID); ?>"><?php echo DELETE; ?></a>&nbsp;|&nbsp;<a target="new" href="<?php echo $listing->guid; ?>"><?php echo VIEW; ?></a></span></td>
        <!--                    <td><?php echo $address; ?></td>-->
                    <td><?php echo $categories; ?></td>
        <!--                    <td><?php echo $tags; ?></td>-->
                    <td><?php echo date("n/j/Y", strtotime($listing->post_date)); ?>
                        <br/><?php echo ucwords($listing->post_status); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paging"><span style="float:left;"><?php echo ITEMS; ?>: <?php echo $query; ?></span>&nbsp;<span style="float:right;"><?php echo $paginationDisplay; ?></span></div>
    <?php
}
function dashboard_style() {
    ?>
    <style type="text/css">
        .dashboard a{
            color: #0c5b7f;
        }
        .dashboard a:hover{
            color:#309ed1;
        }
        #tblspacer{
           width: 100%;
			-webkit-border-radius: 3px;
		border-radius: 3px;
		border-width: 1px;
		border: solid 1px #dfdfdf;
		display: table;
		border-collapse: separate;
		}
        #tblspacer th{
            background-image: -webkit-linear-gradient(top,#F9F9F9,#ECECEC);
            background-image: linear-gradient(top,#F9F9F9,#ECECEC);
            padding: 7px 7px 8px;
            text-align: left;
            line-height: 1.3em;
            font-size: 14px;
        }
        #tblspacer td{
            padding: 7px 7px 8px;
            border: 1px solid;
            border-left: none;
            border-right: none;
            border-top-color: white;
            border-bottom-color: #DFDFDF;
            background-color: #f9f9f9;
        }
        #tblspacer tr{
            display: table-row;
            border: 1px solid #dfdfdf;
        }
        #tblspacer span.modify{
            font-size: 11px;
            visibility: hidden;
        }
        #tblspacer tr:hover span.modify{
            visibility: visible;
        }
        #tblspacer tr span.modify a:hover{
            color:brown;
        }
        #tblspacer td .author_email,
        #tblspacer td .comment_date{
            display: block;
            font-size: 12px;
        }
        #tblspacer td .comment_count{
            display: block;
            background: url('<?php echo TEMPLATEURL . '/images/comment-icon.png'; ?>') no-repeat 0 3px;;
            text-align:center;
            width:24px;
            height:24px;
            float:right;
            margin-left:8px;
            color:#7b7b7b;
            font-size:10px;
        }
        #author-info ul.navigation{
            list-style-type:none !important;
        }
       
        #author-info h6{
            margin-bottom:0;
        }
        .sidebar #author-info ul{
            margin-bottom:0;
        }
        #author-info #author-description{
            margin-bottom:20px;
        }
        .sidebar #author-info ul li{
            border-color:#EBEBEB;
        }
        .sidebar #author-info ul li:last-child{
            border:none;
            padding:0;
            margin:0;
        }
    </style>
    <?php
}
function edit_listing() {
    if (isset($_POST['update'])):
    $post_id = $_REQUEST['pid'];
        $fields = array(
            'postid',
            'place_title',
			'description',
			'_video_url',
			'_meta_image',
            '_meta_video',
			'place_tag'
        );
        //Fecth form values
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
			$posted[$field] = stripcslashes(trim($_POST[$field]));
            }
        }
        $posted['category'] = $_POST['category'];
        $posted['tag'] = explode(',', $_POST['place_tag']);
        $listing_data = array();
        $listing_meta = array();
		$listing_meta = array(		
            '_video_url' => $_POST['videourl'], 
            '_meta_video' => $_POST['place_image1'],
			'_meta_image' => $_POST['place_image2']
        );	
        $listing_data = array(
            "ID" => $post_id,
            "post_type" => 'video_listing',
            "post_title" => $posted['place_title'],
            "post_status" => $posted['post_status'],
            "post_content" => $posted['description'],
            "post_category" => $posted['category'],
            "tags_input" => $posted['tag'],
        );
        $last_postid = wp_update_post($listing_data);
        wp_set_object_terms($last_postid, $posted['category'], $taxonomy = 'video_cat');
        wp_set_object_terms($last_postid, $posted['tag'], $taxonomy = 'video_tag');
        $post_meta = $listing_meta; 
        if ($post_meta) {
            foreach ($post_meta as $mkey => $mval) {
                update_post_meta($last_postid, $mkey, $mval);
            }
        }
    endif;
    ?>
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.validate.min.js"></script>
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri() . '/css/geo_module_style.css'; ?>" />
    <script type="text/javascript" src="<?php echo get_template_directory_uri() . '/js/ajaxupload.js'; ?>"></script> 
    <?php
    require_once(TEMPLATEPATH . '/js/image_upload.php');
    ?>
<script>
jQuery(document).ready(function(){
	jQuery("#uploadForm").validate();
});
</script>
    <?php
    global $wpdb;
    $post_type = 'video_listing';
    $post_id = $_REQUEST['pid'];
    $page_id = $_REQUEST['page_id'];
    $sql = "SELECT " . $wpdb->posts . ".*  FROM " . $wpdb->posts . " WHERE " . $wpdb->posts . ".post_type = '$post_type' AND " . $wpdb->posts . ".ID = $post_id";
    $listing = $wpdb->get_row($sql);
    $video_url = get_post_meta($post_id, '_video_url', true);
    $video_meta = get_post_meta($post_id, '_meta_video', true);
    $image_meta = get_post_meta($post_id, '_meta_image', true);
    $p_categories = get_the_term_list($post_id, 'video_cat', '', ' ', '');
	$p_tag = get_the_term_list($post_id, 'video_tag', '', ' ', '');
	?>
    <h3><?php echo "Edit Your Video "; ?></h3>
	<br/>
     <style type="text/css">
            .content_wrapper img{
                max-width: none;
            }
        </style>
     <form action="" id="uploadForm" name="video-form" class="uploadForm"  method="post" enctype="multipart/form-data">
                <label for="videoName">Video Title<span class="required">*</span></label>
                <input type="text" class="required requiredField" id="list_title" name="place_title" value="<?php if (isset($listing->post_title)) echo $listing->post_title; ?>"/>
                <br />
                <br />
                <label for="description">Description<span class="required">*</span></label>
                <textarea style="width:250px; height: 100px;" class="required requiredField" id="description" name="description" row="20" col="25"><?php if (isset($listing->post_content)) echo $listing->post_content; ?></textarea>
                <br />
                <br />
                <label for="tags">Tags<span class="required"></span></label>
				 <?php
                    $tags = wp_get_post_terms($post_id, 'video_tag');
                    $count = count($tags);
                    for ($i = 0; $i <= $count; $i++) {
                        $allc = explode(',', $tags[$i]->name);
                        if ($allc[0] != "") {
                            $tag .= $allc[0] . ",";
                        }
                    }
                    ?>
                <input type="text" name="place_tag" id="tags" value="<?php if (isset($tag)) echo $tag; ?>" placeholder="Tags"  />
                  <br/><br/><br/> 
                <label class="select-category" for="vcategory">Select Category<span class="required">*</span></label>
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
                 <?php
                    global $wpdb;
                    $taxonomy = 'video_cat';
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
                        if ($taxonomy == 'video_cat') {
                            ?>
                            <li><label class="select-all"><input type="checkbox" name="selectall" id="selectall" class="checkbox" onclick="displaychk_frm();" /></label><?php echo 'Select All'; ?></li>

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
                                'type' => 'video_listing',
                                'child_of' => '',
                                'parent' => $termid,
                                'orderby' => 'name',
                                'order' => 'ASC',
                                'hide_empty' => 0,
                                'hierarchical' => 1,
                                'exclude' => $termid,
                                'include' => '',
                                'number' => '',
                                'taxonomy' => 'video_cat',
                                'pad_counts' => false);
                            $acb_cat = get_categories($args);

                            if ($acb_cat) {
                                echo "<ul class=\"children\">";
                                foreach ($acb_cat as $child_of) {
                                    $term = get_term_by('id', $child_of->term_id, 'video_cat');
                                    $termid = $term->term_taxonomy_id;
                                    $term_tax_id = $term->term_id;
                                    $name = $term->name;
                                    ?>
                                    <li><label><input class="list_category" type="checkbox" name="category[]" id="<?php echo $termid; ?>" value="<?php echo $name; ?>" class="checkbox" /><?php echo $name; ?></label></li>
                                    <?php
                                    $args = array(
                                        'type' => 'video_listing',
                                        'child_of' => '',
                                        'parent' => $term_tax_id,
                                        'orderby' => 'name',
                                        'order' => 'ASC',
                                        'hide_empty' => 0,
                                        'hierarchical' => 1,
                                        'exclude' => $term_tax_id,
                                        'include' => '',
                                        'number' => '',
                                        'taxonomy' => 'video_cat',
                                        'pad_counts' => false);
                                    $acb_cat = get_categories($args);
                                    if ($acb_cat) {
                                        echo "<ul class=\"children\">";
                                        foreach ($acb_cat as $child_of) {
                                            $term = get_term_by('id', $child_of->term_id, 'video_cat');
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
				<div class="upload-section">
				<span class="upload-cheack">
                <input type="radio" name="type" value="Youtube" checked>
				Video From Youtube/Vimeo/Metacafe/Dailymotion</span>
                <span class="upload-cheack"><input type="radio" name="type" value="Self" />
                Upload your own Video </span>
				<br/>
                <br/>
                <br/>
                <div id="video_youtube">
                    <label class="video_url" for="videourl">Video Link Youtube/Vimeo/Metacafe/Dailymotion<span class="required"></span></label>
                    <input type="text" name="videourl" id="videourl" value="<?php if (isset($video_url)) echo $video_url; ?>" placeholder=""  />
                </div>	
				<div id="video_selfhosted">
				<div id="video_selfhosted2">
				  <label for="file">Upload Video File:</label>
				  <br/>
				  <label class="upload-image" for="file">Upload Image File:</label> 
				 </div>
				 <div id="video_selfhosted1">
                    <?php
					$images = array(
					 'place_image1' => $video_meta,
                        'place_image2' => $image_meta,
					);
                     foreach ($images as $key => $image):
					    ?> 
                        <div style="margin-bottom: 20px;">
                            <input class='of-input upload-input' name='<?php echo $key; ?>' id='place_image1_upload' type='text' value="<?php if (isset($image)) echo $image; ?>" />
                            <div style="display: inline;" class="upload_button_div"><input type="button" class="button image_upload_button" id="<?php echo $key; ?>" value="Upload" />
                                <div class="button image_reset_button hide" id="reset_<?php echo $image; ?>" title="<?php echo $image; ?>" value="reset"></div>
                            </div>             
                        </div>						
                        <?php
                    endforeach;
                    ?> 
					<div class="clear"></div>
				<?php
				$max_upload_size = wp_convert_bytes_to_hr(wp_max_upload_size());
					echo '<p class="server-limit">Server Upload Size limit is ' . $max_upload_size . '.</p><br/>';
						?>
                </div>		
					 </div>	
					 </div>
				<div class="clear"></div>				 
                <input class="submit" id="submit" name="update" type="submit" value="Update"/>
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
    <?php
}
function admin_pagination($pid, $sql1, $sql2 = '') {
    global $current_user, $wpdb;
    $query = $wpdb->query($sql); // Get total of Num rows from the database query
    if (isset($_GET['pn'])) { // Get pn from URL vars if it is present
        $pn = preg_replace('#[^0-9]#i', '', $_GET['pn']); // filter everything but numbers for security(new)
    } else { // If the pn URL variable is not present force it to be value of page number 1
        $pn = 1;
    }

    $itemsPerPage = 20;

    $lastPage = ceil($query / $itemsPerPage);

    if ($pn < 1) { // If it is less than 1
        $pn = 1; // force if to be 1
    } else if ($pn > $lastPage) { // if it is greater than $lastpage
        $pn = $lastPage; // force it to be $lastpage's value
    }
    $centerPages = "";
    $sub1 = $pn - 1;
    $sub2 = $pn - 2;
    $add1 = $pn + 1;
    $add2 = $pn + 2;
    if ($pn == 1) {
        $centerPages .= '&nbsp; <span class="active">' . $pn . '</span> &nbsp;';
        $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$pid&pn=$add1") . '">' . $add1 . '</a> &nbsp;';
    } else if ($pn == $lastPage) {
        $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$pid&pn=$sub1") . '">' . $sub1 . '</a> &nbsp;';
        $centerPages .= '&nbsp; <span class="active">' . $pn . '</span> &nbsp;';
    } else if ($pn > 2 && $pn < ($lastPage - 1)) {
        $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$pid&pn=$sub2") . '">' . $sub2 . '</a> &nbsp;';
        $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$pid&pn=$sub1") . '">' . $sub1 . '</a> &nbsp;';
        $centerPages .= '&nbsp; <span class="active">' . $pn . '</span> &nbsp;';
        $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$pid&pn=$add2") . '">' . $add1 . '</a> &nbsp;';
        $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$pid&pn=$add2") . '">' . $add2 . '</a> &nbsp;';
    } else if ($pn > 1 && $pn < $lastPage) {
        $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$pid&pn=$sub1") . '">' . $sub1 . '</a> &nbsp;';
        $centerPages .= '&nbsp; <span class="active">' . $pn . '</span> &nbsp;';
        $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$pid&pn=$add1") . '">' . $add1 . '</a> &nbsp;';
    }

    $limit = 'LIMIT ' . ($pn - 1) * $itemsPerPage . ',' . $itemsPerPage;
    $query2 = $sql2 . ' ' . $limit;
    $listings = $wpdb->get_results($query2);
    $paginationDisplay = ""; // Initialize the pagination output variable
    if ($lastPage != "1") {
        $paginationDisplay .= 'Page <strong>' . $pn . '</strong> of ' . $lastPage . '&nbsp;  &nbsp;  &nbsp; ';

        if ($pn != 1) {
            $previous = $pn - 1;
            $paginationDisplay .= '&nbsp;  <a href="' . home_url("/?page_id=$pid&pn=$previous") . '"> Back</a> ';
        }
        $paginationDisplay .= '<span class="paginationNumbers">' . $centerPages . '</span>';
        if ($pn != $lastPage) {
            $nextPage = $pn + 1;
            $paginationDisplay .= '&nbsp;  <a href="' . home_url("/?page_id=$pid&pn=$nextPage") . '"> Next</a> ';
        }
    }
    return array(
        'listings' => $listings,
        'pag_show' => $paginationDisplay);
}
?>