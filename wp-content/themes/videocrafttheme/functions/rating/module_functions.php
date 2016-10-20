<?php
/**
 * Function Name: videocast_commentslist
 * Description: Callback to commentlist
 * @global type $wpdb
 * @global type $post
 * @global type $rating_table_name
 * @param type $comment
 * @param type $args
 * @param type $depth 
 */
function videocast_commentslist($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    global $wpdb, $post, $rating_table_name;
    extract($args, EXTR_SKIP);
    if ('div' == $args['style']) {
        $tag = 'div';
        $add_below = 'comment';
    } else {
        $tag = 'li';
        $add_below = 'div-comment';
    }
    $post_type = 'video_listing1';
    ?>
    <<?php echo $tag ?> <?php comment_class(empty($args['has_children']) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
    <?php if ('div' != $args['style']) : ?>
        <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
            <?php
        endif;
        global $post;
        if ($post->post_type == $post_type) {
            ?>
          
        <?php } ?>
        <div class="comment-author vcard"><img class="cmt_frame" src="<?php echo TEMPLATEURL; ?>/images/cmt-frame.png" />
            <?php
            if ($args['avatar_size'] != 0)
                echo get_avatar($comment, $args['avatar_size']);
            ?>
            <?php printf(__('<cite class="fn">%s</cite> <span class="says">-</span>'), get_comment_author_link()) ?>
            <a class="comment-meta" href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)) ?>">
                <?php
                /* translators: 1: date, 2: time */
                printf(__('%1$s at %2$s'), get_comment_date(), get_comment_time())
                ?></a><?php edit_comment_link(__('(Edit)'), '  ', ''); ?>
        </div>
        <?php if ($comment->comment_approved == '0') : ?>
            <em class="comment-awaiting-moderation"><?php echo UR_CMT_AWAIT; ?></em>
            <br />
        <?php endif; ?>

        <?php comment_text() ?>

        <div class="reply">
            <?php comment_reply_link(array_merge($args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
        </div>
        <?php if ('div' != $args['style']) : ?>
        </div>
    <?php endif; ?>
    <?php
}

/**
 * Function Name: videocast_get_related_post
 * Description: This function shows related post
 * From custom post type 
 * @global type $post 
 */
function videocast_get_related_post() {
    global $post;
    //for in the loop, display all "content", regardless of post_type,
    //that have the same custom taxonomy (e.g. genre) terms as the current post
    $taxonomy = CUSTOM_CAT_TYPE; //  e.g. post_tag, category, custom taxonomy
    $param_type = CUSTOM_CAT_TYPE; //  e.g. tag__in, category__in, but genre__in will NOT work
    $tax_args = array('orderby' => 'date');
    $tags = wp_get_post_terms($post->ID, $taxonomy, $tax_args);
    if ($tags) {
        foreach ($tags as $tag) {

            $args = array(
                $param_type => $tag->slug,
                'post__not_in' => array($post->ID),
                'post_type' => POST_TYPE,
                'showposts' => 3,
                'caller_get_posts' => 1
            );

            $my_query = null;
            $my_query = new WP_Query($args);
            $loop_count = 0;
            $col_class = '';
            if ($my_query->have_posts()) {
                echo '<ul class="slides">';
                echo '<li>';
                while ($my_query->have_posts()) : $my_query->the_post();
                    $loop_count++;
                    if ($loop_count == 3) {
                        $col_class = 'last';
                    }
                    ?>
                    <div class="related <?php echo $col_class; ?>" >
                        <div class="r_item">
                            <?php if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) { ?>
                                <?php inkthemes_get_thumbnail(175, 150); ?>                    
                            <?php } else { ?>
                                <?php inkthemes_get_image(175, 150); ?> 
                                <?php
                            }
                            ?>
                            <h5><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h5>
                            <ul class="rating">
                                <?php echo videocast_get_post_rating_star($post->ID); ?>
                            </ul>
                        </div>
                    </div>
                    <?php
                endwhile;
                echo '</li>';
                echo '</ul>';
            }
        }
    }
    wp_reset_query(); // to use the original query again   
}

/**
 * Function Name: videocast_excerpt
 * Description: It returns linked elipse
 * @param type $text
 * @return type 
 */
function videocast_excerpt($text) {
    return str_replace('[...]', '<a href="' . get_permalink($post->ID) . '">' . '[...] Read More' . '</a>', $text);
}

add_filter('the_excerpt', 'videocast_excerpt');

function get_wp_cat_checklist($post_taxonomy, $pid) {
    $pid = explode(',', $pid);
    global $wpdb;
    $taxonomy = $post_taxonomy;
    $table_prefix = $wpdb->prefix;
    $wpcat_id = NULL;
    //Fetch category                          
    $wpcategories = (array) $wpdb->get_results("
                            SELECT * FROM {$table_prefix}terms, {$table_prefix}term_taxonomy
                            WHERE {$table_prefix}terms.term_id = {$table_prefix}term_taxonomy.term_id
                            AND {$table_prefix}term_taxonomy.taxonomy ='" . $taxonomy . "' and  {$table_prefix}term_taxonomy.parent=0  ORDER BY {$table_prefix}terms.name");

    $wpcategories = array_values($wpcategories);
    $wpcat2 = NULL;
    if ($wpcategories) {
        echo "<ul class=\"select-cat\">";
        if ($taxonomy == CUSTOM_CATEGORY_TYPE) {
            ?>
            <li><label><input type="checkbox" name="selectall" id="selectall" class="checkbox" onclick="displaychk_frm();" /></label><?php echo "Select All"; ?></li>

            <?php
        }
        foreach ($wpcategories as $wpcat) {
            $counter++;
            $termid = $wpcat->term_id;
            $name = ucfirst($wpcat->name);
            $termprice = $wpcat->term_price;
            $tparent = $wpcat->parent;
            ?>
            <li><label><input type="checkbox" name="category[]" id="<?php echo $termid; ?>" value="<?php echo $termid; ?>" class="checkbox" <?php
            if ($pid[0]) {
                if (in_array($termid, $pid)) {
                    echo "checked=checked";
                }
            } else {
                
            }
            ?> /><?php echo $name; ?></label></li>
                <?php
                if ($taxonomy != "") {
                    $child = get_term_children($termid, $post_taxonomy);
                    foreach ($child as $child_of) {
                        $term = get_term_by('id', $child_of, $post_taxonomy);
                        $termid = $term->term_taxonomy_id;
                        $term_tax_id = $term->term_id;
                        $termprice = $term->term_price;
                        $name = $term->name;
                        $catprice = $wpdb->get_row("select * from $wpdb->term_taxonomy tt ,$wpdb->terms t where t.term_id='" . $child_of->term_id . "' and t.term_id = tt.term_id");
                        ?>
                    <li style="margin-left:15px;"><label><input type="checkbox" name="category[]" id="<?php echo $termid; ?>" value="<?php echo $termid; ?>" class="checkbox" <?php
                    if ($pid[0]) {
                        if (in_array($termid, $pid)) {
                            echo "checked=checked";
                        }
                    }
                        ?> /><?php echo $name; ?></label></li>
                        <?php
                    }
                }
            }
            echo "</ul>";
        }
    }

// Output errors
    function videocast_show_errors($errors, $id = '') {
        if ($errors && sizeof($errors) > 0 && $errors->get_error_code()) :
            echo '<ul class="errors" id="' . $id . '">';
            foreach ($errors->errors as $error) {
                echo '<li>' . $error[0] . '</li>';
            }
            echo '</ul>';
        endif;
    }

    /**
     * Function for get author info
     * @global type $wpdb
     * @param type $post_id
     * @return type 
     */
    function get_author_info($post_id) {
        global $wpdb;
        $sql = "SELECT wp_users.*
            FROM
            wp_users
            INNER JOIN wp_posts
            ON wp_users.ID = wp_posts.post_author where wp_posts.post_author=$post_id";

        $returninfo = $wpdb->get_row($sql);
        return $returninfo;
    }

    /**
     * Function Name: custom_post_author_archive
     * Description: Displaying custom post type author archives
     * @param type $query 
     */
    function custom_post_author_archive(&$query) {
        if ($query->is_author)
            $query->set('post_type', 'video_listing');
        remove_action('pre_get_posts', 'custom_post_author_archive'); // run once!
    }
    add_action('pre_get_posts', 'custom_post_author_archive');
	
/**
 * Function Name: delete_dummy_data()
 * Description: To delete the dummy data
 * 
 * @global type $wpdb 
 */
function delete_dummy_data() {
    global $wpdb;
	global $post;
    $productArray = array();
    $pids_sql = "SELECT $wpdb->postmeta.post_id , $wpdb->postmeta.meta_id , $wpdb->postmeta.meta_key FROM  $wpdb->postmeta
                WHERE
                $wpdb->postmeta.meta_key = 'geocraft_dummy_content'
                AND $wpdb->postmeta.meta_value = 1";
    $pids_info = $wpdb->get_results($pids_sql);
    foreach ($pids_info as $pids_info_obj) {
        wp_delete_post($pids_info_obj->post_id);
    }
}
	
	/**
 * Function Name: geocraft_dummydata_notify()
 * Description: To insert and delete dummy data
 * 
 * @global type $wpdb 
 */
function videocast_dummydata_notify() {
    global $wpdb;
    if (strstr($_SERVER['REQUEST_URI'], 'themes.php') && $_REQUEST['template'] == '' && $_GET['page'] == '') {
        if ($_REQUEST['dummy'] == 'delete') {
            delete_dummy_data();
            $dummy_deleted = '<p><b>' . DUMMY_DT_DLT . '</b></p>';
        }
        if ($_REQUEST['dummy_insert']) {
            include_once (get_template_directory() . '/functions/install_data.php'); //Install dummy data			
        }
        if ($_REQUEST['activated'] == 'true') {
            $theme_activate_success = '<p class="message">' . THEME_ACTIVATED . '</p>';
        }
        $post_counts = $wpdb->get_var("SELECT count($wpdb->postmeta.post_id) FROM $wpdb->postmeta
                                        WHERE
                                        $wpdb->postmeta.meta_key = 'geocraft_dummy_content'
                                        AND $wpdb->postmeta.meta_value = 1");
        if ($post_counts > 0) {
            $dummy_data_notify = '<p> <b>' . SAMPLE_DT_PAPU . '</b><p>';
            $button = '<a class="btn_delete" href="' . admin_url('/themes.php?dummy=delete') . '">' . YES_DEL . '</a>';
        } else {
            $dummy_data_notify = '<p> <b>' . LIKE_TOP_POPULATED . '</b></p>';
            $button = '<a class="btn_insert" href="' . admin_url('/themes.php?dummy_insert=1') . '">Yes Insert Sample Data</a>';
        }
        $dummy_msg = "<div class='btn'>$button</div>";
        ?>
        <style type="text/css">
            .dummy_data_notify{
                background: #f1f1f1;
                border:1px solid #089bd0;
                margin-top: 20px;
                width:700px;
                padding-left: 20px;
                color: #282829;
                height:100px;
                position:relative;
                margin-bottom:25px;
            }
            .dummy_data_notify .btn{
                background: url('<?php echo get_template_directory_uri() . '/images/dummy_msg.png'; ?>') no-repeat; 
                width:276px;
                height:58px;
                position:absolute;
                bottom:-13px;
                text-align:center;
                right:15px;
            }
            .dummy_data_notify .btn a{
                color: #000;
                display:block;
                text-decoration:none;
                margin-top:22px;
                font-size:22px;
                text-shadow:0 1px 0 #3fc6f3;
            }
        </style>
        <?php
        echo '<div class="dummy_data_notify"> ' . $theme_activate_success . $dummy_deleted . $dummy_data_notify . $dummy_msg . '</div>';
    }
}

add_action('admin_notices', 'videocast_dummydata_notify');

   