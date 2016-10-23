<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class USER_Menu 
{
    public $minimum_capability = 'manage_options';
    private $form_data_key = 'user-form';
    private $form_settings_key = 'user_form_settings';
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'admin_menus' ), 9 );
        add_action( 'admin_head', array( $this, 'admin_head' ) );
        add_action( 'admin_init', array( $this, 'welcome'    ) );
        add_action( 'frontend_download_user_sysinfo',  array( $this, 'frontend_generate_user_sysinfo_download' ) );
        add_action( 'admin_init', array( $this, 'user_process_form_export' ) );
        add_action( 'admin_init', array( $this, 'user_process_form_import' ) );
        add_action( 'admin_init', array( $this, 'user_process_form_reset' ) );
        add_action( 'admin_init', array( $this, 'user_process_form_tools' ) );
        add_action( 'admin_init', array( $this, 'process_bulk_action' ) );
        add_action( 'admin_init', array($this ,'readlist_settings_init') );
        add_action( 'add_meta_boxes', array($this, 'add_meta_box_form_select') );
        add_action( 'add_meta_boxes_user_forms', array($this, 'add_meta_box_post') );
        //add_action('user_register',  array($this ,'register_role'));
        add_action('init', array($this ,'confirmation_r'));
        register_deactivation_hook( __FILE__, array( 'user_remove_custom', 'user_custom_uninstall' ) );
        register_uninstall_hook( __FILE__, array( 'user_remove_custom', 'user_custom_uninstall' ) );
        add_filter( 'manage_edit-user_forms_columns', array( $this, 'admin_column' ) );
        add_filter( 'manage_edit-user_profile_columns', array( $this, 'admin_column_profile' ) );
        add_action( 'manage_user_forms_posts_custom_column', array( $this, 'admin_column_value' ), 10, 2 );
        // add_action( 'manage_user_profile_posts_custom_column', array( $this, 'admin_column_value_profile' ), 10, 2 );
        
        add_filter( 'post_updated_messages', array($this, 'form_updated_message') );
        add_action( 'admin_enqueue_scripts', array($this, 'enqueue_scripts') );
        add_action( 'admin_footer-edit.php', array($this, 'add_form_button_style') );
        add_action( 'admin_footer-post.php', array($this, 'add_form_button_style') );
        add_filter( 'post_row_actions', array( $this, 'row_action_duplicate' ), 10, 2 );
        add_filter( 'admin_action_user_duplicate', array( $this, 'duplicate_form' ) );
        add_action( 'wp_ajax_user_form_dump', array( $this, 'form_dump' ) );
        add_action( 'wp_ajax_user_form_add_el', array( $this, 'ajax_post_add_element' ) );
        add_action( 'wp_ajax_add_fields', array( $this, 'add_fields' ) );
        add_action( 'save_post', array( $this, 'save_form_meta' ), 1, 2 ); // save the custom fields
    }
    public function admin_menus() {
        global $user_settings;
        add_menu_page(__( 'FRONTEND USER', 'frontend_user_pro' ),__( 'FRONTEND USER', 'frontend_user_pro' ),'manage_options','user-about',array( $this, 'about_screen' ), '', '25.01');
        add_submenu_page( 'user-about', __( 'About', 'frontend_user_pro' ), __( 'About', 'frontend_user_pro' ), 'manage_options', 'user-about', array( $this, 'about_screen' ) );
        add_submenu_page( 'user-about','Forms','Forms', 'manage_options', 'edit.php?post_type=user_forms' );
        add_submenu_page( 'user-about','Login Forms','Login Forms', 'manage_options', 'edit.php?post_type=user_logins' );
        add_submenu_page( 'user-about','Regisration Forms','Regisration Forms', 'manage_options', 'edit.php?post_type=user_registrations' );
        add_submenu_page( 'user-about','Frontend User Form Style','Frontend User Form Style', 'manage_options', 'edit.php?post_type=feua_style' );
        add_submenu_page( 'user-about','Readlist', 'Readlist', 'manage_options', 'user-readlist', array( $this, 'user_readlist_page' ) );
        add_submenu_page( 'user-about','Entries', 'Entries', 'manage_options', 'user-form-entries', array( $this, 'user_form_entries' ) );
        add_submenu_page( 'user-about', 'Form Import/Export', 'Form Import/Export', 'manage_options', 'user-form-import-export', array( $this, 'user_form_import_page' ) );
    }
    function add_fields() {
        parse_str($_POST['data'], $params);
        $id = $_POST['id'];
        if (!empty($_POST)) 
        {
            $dat = serialize($params);
            $ggg = get_post_meta($id , '_selected_enteries_field' , true);
            if ($ggg) {
                update_post_meta($id , '_selected_enteries_field' , $dat);
            }else{
                add_post_meta($id , '_selected_enteries_field' , $dat);
            }
        }
        die();
    }
    function user_form_entries() {
        global $post , $wpdb;
        $post_form = get_posts('post_type=user_forms');
        if (isset($_GET) && is_array($_GET) && isset($_GET['id'])) 
        {
            $id = $_GET['id'];
        }elseif ($post_form && is_array($post_form)) 
        {
            $id = $post_form['0']->ID;
        }else
        {
            echo "No data";
            return;
        }
        if(isset($_GET['action'])) {
            $r_action = $_GET['action'];
        }
        else {
            $r_action = '';
        }
        if (isset($_GET) && is_array($_GET) && $r_action == 'trash') 
        {
            $ptid = $_GET['post_id'];
            $wpdb->update( $wpdb->posts, array( 'post_status' => 'trash' ), array( 'ID' => $ptid ) );
        }
        wp_enqueue_script('pop-8' ,plugins_url( '../../assets/js/jquery.fancybox.js', __FILE__ ));
        wp_enqueue_style('pop-9' ,plugins_url( '../../assets/css/jquery.fancybox.css', __FILE__ ));
        $tb1 = $wpdb->prefix."postmeta";
        $post_data = $wpdb->get_results("SELECT post_id FROM $tb1 WHERE `meta_key` = '_user_form_id' AND `meta_value` = '$id'");
        $true = array();
        $false = array();
        // echo "**********".$id;
        $form = get_post_meta($id , 'user-form',true);
        foreach($post_data as $key => $data)
        { 
            $post_form = get_post($data->post_id);
            if ($form) 
            {
                foreach ($form as $key1 => $value1) 
                {
                    if (!empty($_GET) && isset($_GET['s'])) 
                    {
                        $sstr = $_GET['s'];
                        if ($value1['is_meta'] == 'yes') 
                        {
                            $dd1 = get_post_meta($data->post_id , $value1['name'] ,true);
                            if ($value1['template'] == 'image_upload') {
                                $data1 = unserialize($dd1);
                                $dd1 = implode('<br>', $data1);
                                $pp = strpos($dd1, $sstr); 
                                if ( $pp !== false) 
                                {
                                    $true[] = $data->post_id;
                                }else
                                {
                                    $false[] = "false";
                                }
                            }elseif ($value1['template'] == 'file_upload') 
                            {
                                foreach ($dd1 as $dds) {
                                    $pid= get_post($dds);
                                    $dd1 =$pid->post_title;
                                    $pp = strpos($dd1, $sstr); 
                                    if ( $pp !== false) 
                                    {
                                        $true[] = $data->post_id;
                                    }else
                                    {
                                        $false[] = "false";
                                    }
                                }
                            }else{
                                $pp = strpos($dd1, $sstr); 
                                    if ( $pp !== false) 
                                    {
                                        $true[] = $data->post_id;
                                    }else
                                    {
                                        $false[] = "false";
                                    }
                            }
                        }elseif ($value1['is_meta'] == 'no')
                        {
                            if ($value1['name'] == 'featured_image') 
                            {
                                $yy = get_the_post_thumbnail($data->post_id, array(70,70));
                                $pp = strpos($yy, $sstr); 
                                if ( $pp !== false) 
                                {
                                    $true[] = $data->post_id;
                                }else
                                {
                                    $false[] = "false";
                                }
                            }
                            elseif($value1['name'] == 'post_content')
                            {
                                $post_form = get_post($data->post_id);
                                $pp = strpos($post_form->$value1['name'], $sstr);
                                if ( $pp !== false) 
                                {
                                    $true[] = $data->post_id;
                                }else
                                {
                                    $false[] = "false";
                                }
                            }elseif($value1['template'] == 'taxonomy')
                            {
                                $post_form = get_post($data->post_id);
                                $cats = get_the_terms( $post_form->ID, $value1['name'] );
                                foreach ($cats as $cat) 
                                {
                                    $pp = strpos($cat->name, $sstr);
                                    if ( $pp !== false) 
                                    {
                                        $true[] = $data->post_id;
                                    }else
                                    {
                                        $false[] = "false";
                                    }
                                }
                            }else
                            {
                                $post_form = get_post($data->post_id);
                                $pp = strpos($post_form->$value1['name'], $sstr);
                                if($pp !== false)
                                {
                                    $true[] = $data->post_id;
                                }else
                                {
                                    $false[] = "false";
                                }
                            }
                        }
                    }else
                    {
                        $postid = $data->post_id;
                        $true[] = $data->post_id;
                    }
                }
            }
        }
        $postunq = array_unique($true) ;
        ?>
        <div class="wrap enteies_tb">
            <h1>
                Entries
            </h1>
            <ul class="subsubsub">
                <li class="all">
                    <a class="current" href="admin.php?page=user-form-entries&id=<?php echo $id; ?>">
                        All 
                        <span class="count">
                            (<?php echo count($post_data); ?>)
                        </span>
                    </a>
                </li>
            </ul>
            <form method="get" action="http://9to5wp.com/happy/wp-admin/admin.php">
                <?php
                if (!empty($_GET) && isset($_GET['s']) ) {
                    $vv = $_GET['s'];
                }
                else {
                    $vv = '';
                }
                ?>
            <p class="search-box">
                <label>
                <span class="screen-reader-text"><?php echo _x( 'Search for:', 'Entries' ) ?></span>
                    <input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search …', 'placeholder' ) ?>" value="<?php echo $vv; ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'Entries' ) ?>" />
                </label>
                <?php
                if (!empty($_GET) && isset($_GET['id']) ) {
                    echo '<input type="hidden" name="id" value="'.$_GET['id'].'">';
                }
                ?>
                <input type="hidden" name="page" value="user-form-entries">
                <input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button' ) ?>" />
            </p>
            </form>
            <form method="get" >
                <label for="user-search-input" class="screen-reader-text">
                    Select Shortcode
                </label>
                <p class="search-box">
                    <?php
                    $post_form = get_posts('post_type=user_forms');
                    echo "<select id='select_id_enteries' name='select_id'>";
                    foreach ($post_form as $ss) { ?>
                        <option <?php if($id == $ss->ID) echo"selected"; ?> value="<?php echo $ss->ID; ?> "><?php echo $ss->post_title; ?></option>
                    <?php }
                    echo "</select>";
                    ?>
                </p>
                <h2 class="screen-reader-text">
                    <?php echo $value->post_title; ?> list
                </h2>
                <?php 
                $select_field = get_post_meta($id , '_selected_enteries_field',true);
                $select_field = unserialize($select_field);
                ?>
                <div id="all_data">
                    <table class="wp-list-table widefat fixed striped users">
                        <thead>
                            <tr>
                                <?php
                                $i = 0;
                                if ($form) 
                                {
                         
                                    foreach ($form as $key1 => $value1) {
                                        if (!empty($select_field)) 
                                        {
                                            foreach ($select_field as $key => $value) 
                                            {
                                                if ($value == $value1['label']) 
                                                {
                                                    ?>
                                                    <th class="manage-column column-username column-primary sortable desc" id="username" scope="col">
                                                        <span><?php echo $value1['label']; ?></span>
                                                    </th>
                                                    <?php
                                                }
                                            }
                                        }else
                                        { ?>
                                            <th class="manage-column column-username column-primary sortable desc" id="username" scope="col">
                                                <span><?php echo $value1['label']; ?></span>
                                            </th>
                                            <?php
                                            $i++;
                                            if ($i == 6) {
                                                break;
                                            }
                                        }
                                    }  
                                }?>
                                <th align="right" width="50" scope="col">
                                    <a class="fancybox entries_edit_icon" href="#inline_box_<?php echo $id; ?>" title="Select Columns">Edit</a>
                                </th>
                            </tr>
                        </thead>
                        <tbody data-wp-lists="list:user" id="the-list">
                            <?php 
                            if (!empty($postunq)) {
                                foreach($postunq as $data)
                                { 
                                    $postid = $data;
                                    $pp = get_post($postid);
                                    if ($pp->post_status != 'trash') 
                                    {
                                        ?>
                                        <tr id="user-<?php echo $postid; ?>">
                                            <style type="text/css">
                                                #user-<?php echo $postid; ?> td:not(:nth-child(2)) .row-actions 
                                                {
                                                    display: none;
                                                }
                                            </style>
                                            <?php
                                            $i = 0;
                                            foreach ($form as $key1 => $value1) 
                                            {
                                                if (!empty($select_field))
                                                {   $j = 0;
                                                    foreach ($select_field as $key => $value)
                                                    {
                                                        if ($value == $value1['label']) 
                                                        { ?>
                                                            <td data-colname="Role" class="role column-role">
                                                                <span>
                                                                    <?php 
                                                                    if (array_key_exists('is_meta', $value1) &&  $value1['is_meta'] == 'yes') {
                                                                        $dd1 = get_post_meta($postid , $value1['name'] ,true);
                                                                        if (array_key_exists('template', $value1) && $value1['template'] == 'image_upload') {
                                                                            $data1 = unserialize($dd1);
                                                                            $dd1 = implode('<br>', $data1);
                                                                            echo $dd1;
                                                                        }elseif ( array_key_exists('template', $value1) && $value1['template'] == 'file_upload') {
                                                                            foreach ($dd1 as $dds) {
                                                                                $pid= get_post($dds);
                                                                                echo $pid->post_title;
                                                                            }
                                                                        }elseif ( array_key_exists('template', $value1) && $value1['template'] == 'textarea_field') {
                                                                            echo $this->limit_text($dd1,10);
                                                                        }
                                                                        else{
                                                                            echo $dd1;
                                                                        }
                                                                    }else{
                                                                        if ( array_key_exists('name', $value1) && $value1['name'] == 'featured_image') {
                                                                         echo  get_the_post_thumbnail($postid,array(70,70)); 
                                                                     }elseif(array_key_exists('name', $value1) && $value1['name'] == 'post_content'){
                                                                        $post_form = get_post($postid);
                                                                        echo $this->limit_text($post_form->$value1['name'],10);
                                                                    }elseif(array_key_exists('template', $value1) && $value1['template'] == 'taxonomy')
                                                                    {
                                                                        $post_form = get_post($postid);
                                                                        global $wpdb;
                                                          
                                                                        $cat = get_the_terms( $post_form->ID, $value1['name']);
                                                                        if($cat) {
                                                                            foreach ($cat as $cats) {
                                                                                echo $cats->name ." ";
                                                                            }
                                                                        }
                                                                    }else{
                                                                        $post_form = get_post($postid);
                                                                        echo $post_form->$value1['name'];
                                                                    }
                                                                }
                                                                ?>
                                                            </span>
                                                            <?php
                                                                $perm = get_permalink($postid);
                                                            ?>
                                                            <div class="row-actions">
                                                                <span class="edit">
                                                                    <a href="<?php echo $perm; ?>" title="View this entry">
                                                                        View
                                                                    </a>
                                                                </span>
                                                                | 
                                                                <span class="trash">
                                                                    <a href="admin.php?page=user-form-entries&id=<?php echo $id;?>&post_id=<?php echo $postid; ?>&action=trash" title="Move this entry to the trash" >
                                                                        Trash
                                                                    </a>
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <?php
                                                        }
                                                    }
                                                }else
                                                { ?>
                                                    <td data-colname="Role" class="role column-role">
                                                        <span>
                                                            <?php 
                                                            if (array_key_exists('is_meta', $value1) && $value1['is_meta'] == 'yes') 
                                                            {
                                                                $dd1 = get_post_meta($postid , $value1['name'] ,true);
                                                                if ($dd1) 
                                                                {
                                                                    if (array_key_exists('template', $value1) && $value1['template'] == 'image_upload') 
                                                                    {
                                                                        $data1 = unserialize($dd1);
                                                                        $dd1 = implode('<br>', $data1);
                                                                        echo $dd1;
                                                                    }elseif (array_key_exists('template', $value1) && $value1['template'] == 'file_upload') 
                                                                    {
                                                                        foreach ($dd1 as $dds) {
                                                                            $pid= get_post($dds);
                                                                            echo $pid->post_title;
                                                                        }
                                                                    }elseif (array_key_exists('template', $value1) && $value1['template'] == 'textarea_field')
                                                                    {
                                                                        echo $this->limit_text($dd1,10);
                                                                    }else
                                                                    {
                                                                        echo $dd1;
                                                                    }
                                                                }else
                                                                {
                                                                    echo "-";
                                                                }
                                                            }else
                                                            {
                                                                if (array_key_exists('name', $value1) && $value1['name'] == 'featured_image') {
                                                                   echo  get_the_post_thumbnail($postid,array(70 , 70)); 
                                                                }elseif(array_key_exists('name', $value1) && $value1['name'] == 'post_content'){
                                                                    $post_form = get_post($postid);
                                                                    echo $this->limit_text($post_form->$value1['name'],10);
                                                                }elseif(array_key_exists('template', $value1) && $value1['template'] == 'taxonomy')
                                                                {
                                                                    $post_form = get_post($postid);
                                                                    $cat = get_the_terms( $post_form->ID, $value1['name']);
                                                                    if($cat) {
                                                                        foreach ($cat as $cats) {
                                                                            echo $cats->name ." ";
                                                                        }
                                                                    }
                                                                }else{
                                                                    $post_form = get_post($postid);
                                                                    echo $post_form->$value1['name'];
                                                                }
                                                            }
                                                            ?>
                                                        </span>
                                                        <?php
                                                            $perm = get_permalink($postid);
                                                        ?>
                                                        <div class="row-actions">
                                                            <span class="edit">
                                                                <a href="<?php echo $perm; ?>" title="View this entry">
                                                                    View
                                                                </a>
                                                            </span>
                                                            | 
                                                            <span class="trash">
                                                                <a href="admin.php?page=user-form-entries&id=<?php echo $id;?>&post_id=<?php echo $postid; ?>&action=trash" title="Move this entry to the trash" >
                                                                    Trash
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <?php
                                                    $i++;
                                                    if ($i == 6) {
                                                        break;
                                                    }
                                                } 
                                            }?>
                                        </tr>   
                                        <?php
                                    }
                                }
                            }else
                            {
                                echo "<tr><td>No Post Found<td></tr>";
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <?php
                                $i =0;
                                if ($form) 
                                {
                                    foreach ($form as $key1 => $value1) {
                                        if (!empty($select_field)) 
                                        {
                                            foreach ($select_field as $key => $value) 
                                            {
                                                if ($value == $value1['label']) 
                                                {
                                                    ?>
                                                    <th class="manage-column column-username column-primary sortable desc" id="username" scope="col">
                                                        <span><?php echo $value1['label']; ?></span>
                                                    </th>
                                                    <?php
                                                }
                                            }
                                        }else
                                        { ?>
                                            <th class="manage-column column-username column-primary sortable desc" id="username" scope="col">
                                                <span><?php echo $value1['label']; ?></span>
                                            </th>
                                            <?php
                                            $i++;
                                            
                                            if ($i == 6) {
                                                break;
                                            }
                                        }
                                    } 
                                } ?>
                                <th align="right" width="50" scope="col">
                                    <a class="fancybox entries_edit_icon" href="#inline_box_<?php echo $id; ?>" title="Select Columns">Edit</a>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </form>
        </div>
        <div id="inline_box_<?php echo $id; ?>" style="width:400px;display: none;">
            <form action="#" method="post" id="sub_form">
                <?php
                foreach ($form as $key1 => $value1) {
                    $name = "field_".$value1['name'];
                    if(isset($select_field[$name])) {
                        $bb = $select_field[$name];    
                    }
                    else {
                        $bb ='';   
                    }
                    
                    ?><input type="checkbox" name="<?php echo $name; ?>" value="<?php echo $value1['label']; ?>"  <?php if($bb == $value1['label']){ echo "checked"; } ?> ><span><?php echo $value1['label']; ?></span><br><?php
                }
                ?>
               
                <input type="submit" value="Submit" name="submit">
            </form>
        </div>
        <script type="text/javascript">
            var $ = jQuery;
            $("#select_id_enteries").change(function()
            {
                var va = $(this).val();
                var url = "admin.php?page=user-form-entries&id="+va;    
                $(location).attr('href',url);
            });
            $(document).ready(function() {
                $('.fancybox').fancybox();
                $('#sub_form').submit(function(event)
                {
                    event.preventDefault();
                    var formData = $( this ).serialize();
                    jQuery.ajax({
                        type: "post",
                        url: '<?php echo admin_url("/admin-ajax.php"); ?>',
                        data: { 
                            action: 'add_fields',
                            data        : formData, // our data object
                            id : "<?php echo $id; ?>",
                        }, 
                         success: function(data) {
                            location.reload();
                        },
                        error:function(res) {
                            console.log(res);
                        }
                    }); 
                });
            });
        </script>
        <style type="text/css">
            #all_data table td{
                overflow: hidden;
            }
            .entries_edit_icon {
                background-image: url("<?php echo plugins_url('../images/icon-edit.png',__FILE__);?>");
                background-repeat: no-repeat;
                float: right;
                font-size: 10px;
                font-weight: normal;
                height: 16px;
                line-height: 16px;
                margin: 2px 6px 0 0;
                padding: 0 0 0 18px;
                text-decoration: none;
                text-shadow: none;
            }
            #all_data table th {
                padding: 8px 10px;
            }
        </style>
        <?php
    }
    function limit_text($text, $limit) {
      if (str_word_count($text, 0) > $limit) {
          $words = str_word_count($text, 2);
          $pos = array_keys($words);
          $text = substr($text, 0, $pos[$limit]) . '...';
      }
      return $text;
    }
    function save_form_meta( $post_id, $post ) {
        if ( !isset($_POST['user_form_editor'])) {
            return $post->ID;
        }
        if ( !wp_verify_nonce( $_POST['user_form_editor'], plugin_basename( __FILE__ ) ) ) {
            return $post->ID;
        }
        // Is the user allowed to edit the post or page?
        if ( !current_user_can( 'edit_post', $post->ID ) ) {
            return $post->ID;
        }
        if (array_key_exists('user_input', $_POST) ) {
            update_post_meta( $post->ID, $this->form_data_key, $_POST['user_input'] );
        }
        update_post_meta( $post->ID, $this->form_settings_key, $_POST['user_settings'] );
    }
    function ajax_post_add_element() {
        $name = $_POST['name'];
        $type = $_POST['type'];
        $field_id = $_POST['order'];
        switch ($name) {
            case 'post_title':
            USER_Formbuilder_Templates::post_title( $field_id, 'Post Title');
            break;
            case 'post_content':
            USER_Formbuilder_Templates::post_content( $field_id, 'Post Body');
            break;
            case 'post_excerpt':
            USER_Formbuilder_Templates::post_excerpt( $field_id, 'Excerpt');
            break;
            case 'post_tag':
            USER_Formbuilder_Templates::post_tag( $field_id, 'Tags');
            break;
            case 'featured_image':
            USER_Formbuilder_Templates::featured_image( $field_id, 'Featured Image');
            break;
            case 'custom_text':
            USER_Formbuilder_Templates::text_field( $field_id, 'Custom field: Text');
            break;
            case 'custom_textarea':
            USER_Formbuilder_Templates::textarea_field( $field_id, 'Custom field: Textarea');
            break;
            case 'custom_select':
            USER_Formbuilder_Templates::dropdown_field( $field_id, 'Custom field: Select');
            break;
            case 'custom_multiselect':
            USER_Formbuilder_Templates::multiple_select( $field_id, 'Custom field: Multiselect');
            break;
            case 'custom_radio':
            USER_Formbuilder_Templates::radio_field( $field_id, 'Custom field: Radio');
            break;
            case 'custom_checkbox':
            USER_Formbuilder_Templates::checkbox_field( $field_id, 'Custom field: Checkbox');
            break;
            case 'custom_image':
            USER_Formbuilder_Templates::image_upload( $field_id, 'Custom field: Image');
            break;
            case 'custom_file':
            USER_Formbuilder_Templates::file_upload( $field_id, 'Custom field: File Upload');
            break;
            case 'custom_url':
            USER_Formbuilder_Templates::website_url( $field_id, 'Custom field: URL');
            break;
            case 'custom_email':
            USER_Formbuilder_Templates::email_address( $field_id, 'Custom field: E-Mail');
            break;
            case 'custom_repeater':
            USER_Formbuilder_Templates::repeat_field( $field_id, 'Custom field: Repeat Field');
            break;
            case 'custom_html':
            USER_Formbuilder_Templates::custom_html( $field_id, 'HTML' );
            break;
            case 'category':
            USER_Formbuilder_Templates::taxonomy( $field_id, 'Category', $type );
            break;
            case 'taxonomy':
            USER_Formbuilder_Templates::taxonomy( $field_id, 'Taxonomy: ' . $type, $type );
            break;
            case 'section_break':
            USER_Formbuilder_Templates::section_break( $field_id, 'Section Break' );
            break;
            case 'recaptcha':
            USER_Formbuilder_Templates::recaptcha( $field_id, 'reCaptcha' );
            break;
            case 'action_hook':
            USER_Formbuilder_Templates::action_hook( $field_id, 'Action Hook' );
            break;
            case 'really_simple_captcha':
            USER_Formbuilder_Templates::really_simple_captcha( $field_id, 'Really Simple Captcha' );
            break;
            case 'custom_date':
            USER_Formbuilder_Templates::date_field( $field_id, 'Custom Field: Date' );
            break;
            case 'custom_map':
            USER_Formbuilder_Templates::google_map( $field_id, 'Custom Field: Google Map' );
            break;
            break;
            case 'custom_hidden':
            USER_Formbuilder_Templates::custom_hidden_field( $field_id, 'Hidden Field' );
            break;
            case 'toc':
            USER_Formbuilder_Templates::toc( $field_id, 'TOC' );
            break;
            case 'user_login':
            USER_Formbuilder_Templates::user_login( $field_id, __( 'Username', 'frontend_user_pro' ) );
            break;
            case 'first_name':
            USER_Formbuilder_Templates::first_name( $field_id, __( 'First Name', 'frontend_user_pro' ) );
            break;
            case 'last_name':
            USER_Formbuilder_Templates::last_name( $field_id, __( 'Last Name', 'frontend_user_pro' ) );
            break;
            case 'nickname':
            USER_Formbuilder_Templates::nickname( $field_id, __( 'Nickname', 'frontend_user_pro' ) );
            break;
            case 'user_email':
            USER_Formbuilder_Templates::user_email( $field_id, __( 'E-mail', 'frontend_user_pro' ) );
            break;
            case 'user_url':
            USER_Formbuilder_Templates::user_url( $field_id, __( 'Website', 'frontend_user_pro' ) );
            break;
            case 'user_bio':
            USER_Formbuilder_Templates::description( $field_id, __( 'Biographical Info', 'frontend_user_pro' ) );
            break;
            case 'password':
            USER_Formbuilder_Templates::password( $field_id, __( 'Password', 'frontend_user_pro' ) );
            break;
            case 'user_avatar':
            USER_Formbuilder_Templates::avatar( $field_id, __( 'Avatar', 'frontend_user_pro' ) );
            break;
            default:
            do_action( 'user_admin_field_' . $name, $type, $field_id );
            break;
        }
        exit;
    }
    function form_updated_message( $messages ) {
        $message = array(
            0 => '',
            1 => __('Form updated.'),
            2 => __('Custom field updated.'),
            3 => __('Custom field deleted.'),
            4 => __('Form updated.'),
            5 => isset($_GET['revision']) ? sprintf( __('Form restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6 => __('Form published.'),
            7 => __('Form saved.'),
            8 => __('Form submitted.' ),
            9 => '',
            10 => __('Form draft updated.'),
            );
        $messages['user_forms'] = $message;
        // $messages['user_profile'] = $message;
        return $messages;
    }
    function admin_column_value_profile( $column_name, $post_id ) {
        switch ($column_name) {
            case 'shortcode':
            printf( 'Registration: [user_profile type="registration" id="%d"]<br>', $post_id );
            printf( 'Edit Profile: [user_profile type="profile" id="%d"]', $post_id );
            break;
            case 'role':
            $settings = get_post_meta( $post_id, $this->form_settings_key, true );
            echo ucfirst( $settings['role'] );
            break;
        }
    }
    public function admin_head() {
        // Badge for welcome page
        $page = get_current_screen();
        if ( isset( $page->id  ) && $page->id == 'toplevel_page_user-about' )  
        { ?>
            <style type="text/css" media="screen">
                /*<![CDATA[*/
                .user-badge {
                    padding: 5px;
                    height: 217px;
                    width: 354px;
                    color: #666;
                    font-weight: bold;
                    font-size: 14px;
                    text-align: center;
                    text-shadow: 0 1px 0 rgba(255, 255, 255, 0.8);
                    margin: 0 -5px;
                }
                .about-wrap .user-badge {
                    position: absolute;
                    top: 0;
                    right: 0;
                }
                .user-welcome-screenshots {
                    float: right;
                    margin-left: 10px!important;
                }
                /*]]>*/
            </style>
            <?php
        }
    }
    function row_action_duplicate($actions, $post) {
        if ( !current_user_can( 'activate_plugins' ) ) {
            return $actions;
        }
        if ( !in_array( $post->post_type, array( 'user_forms', 'user_profile') ) ) {
            return $actions;
        }
        $actions['duplicate'] = '<a href="' . esc_url( add_query_arg( array( 'action' => 'user_duplicate', 'id' => $post->ID, '_wpnonce' => wp_create_nonce( 'user_duplicate' ) ), admin_url( 'admin.php' ) ) ) . '" title="' . esc_attr( __( 'Duplicate form', 'frontend_user_pro' ) ) . '">' . __( 'Duplicate', 'frontend_user_pro' ) . '</a>';
        return $actions;
    }
    function duplicate_form() {
        check_admin_referer( 'user_duplicate' );
        if ( !current_user_can( 'activate_plugins' ) ) {
            return;
        }
        $post_id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;
        $post = get_post( $post_id );
        if ( !$post ) {
            return;
        }
        $new_form = array(
            'post_title' => $post->post_title,
            'post_type' => $post->post_type,
            'post_status' => 'draft'
            );
        $form_id = wp_insert_post( $new_form );
        if ( $form_id ) {
            $form_settings = get_post_meta( $post_id, $this->form_settings_key, true );
            $form_vars = get_post_meta( $post_id, $this->form_data_key, true );
            update_post_meta( $form_id, $this->form_settings_key, $form_settings );
            update_post_meta( $form_id, $this->form_data_key, $form_vars );
            $location = admin_url( 'edit.php?post_type=' . $post->post_type );
            wp_redirect( $location );
        }
    }
    function admin_column( $columns ) {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __( 'Form Name', 'frontend_user_pro' ),
            'post_type' => __( 'Post Type', 'frontend_user_pro' ),
            'post_status' => __( 'Post Status', 'frontend_user_pro' ),
            'guest_post' => __( 'Guest Post', 'frontend_user_pro' ),
            'shortcode' => __( 'Shortcode', 'frontend_user_pro' )
            );
        return $columns;
    }
    function admin_column_profile( $columns ) {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __( 'Form Name', 'frontend_user_pro' ),
            'role' => __( 'User Role', 'frontend_user_pro' ),
            'shortcode' => __( 'Shortcode', 'frontend_user_pro' )
            );
        return $columns;
    }
    function admin_column_value( $column_name, $post_id ) {
        switch ($column_name) {
            case 'shortcode':
            printf( '[wpfeup-add-form id="%d"]', $post_id );
            break;
            case 'post_type':
            $settings = get_post_meta( $post_id, $this->form_settings_key ,true );
            $post_t = ($settings) ? $settings['post_type'] : 'post' ;
            echo $post_t;
            break;
            case 'post_status':
            $settings = get_post_meta( $post_id, $this->form_settings_key, true );
            $post_s = ($settings) ? $settings['post_status'] : 'publish' ;
            echo user_admin_post_status( $post_s );
            break;
            case 'guest_post':
            $settings = get_post_meta( $post_id, $this->form_settings_key, true );
            $url = plugins_url('../../assets/img/', __FILE__);
            $image = '<img src="%s" alt="%s">';
            $post_i = ($settings) ? $settings['guest_post'] == 'false' ? sprintf( $image, $url . 'cross.png', __( 'No', '' ) ) : sprintf( $image, $url . 'on.png', __( 'Yes', 'frontend_user_pro' ) )  : sprintf( $image, $url . 'cross.png', __( 'No', '' ) ) ;
            echo $post_i;
            break;
            default:
                    # code...
            break;
        }
    }
    function add_meta_box_form_select() {
        remove_meta_box('submitdiv', 'user_forms', 'side');
        remove_meta_box('submitdiv', 'user_profile', 'side');
    }
    function edit_form_area() {
        global $post, $pagenow;
        $form_inputs = get_post_meta( $post->ID, $this->form_data_key, true );
        $feup_f_col = get_post_meta( $post->ID, 'user_form_settings', true );
        $clss = '';
        if (is_array($feup_f_col) && array_key_exists('feup_f_col', $feup_f_col ) ) {
            $clss = $feup_f_col['feup_f_col'];
        }
        ?>
        <input type="hidden" name="user_form_editor" id="user_form_editor" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
        <div style="margin-bottom: 10px; text-align: right; margin-top: 10px;ight;">
<select name="user_settings[feup_f_col]">
      <option value="feup_f_col-1" <?php if($clss == 'feup_f_col-1') { echo 'selected'; } ?>>1 column</option>
      <option value="feup_f_col-2" <?php if($clss == 'feup_f_col-2') { echo 'selected'; } ?>>2 column</option>
      <option value="feup_f_col-3" <?php if($clss == 'feup_f_col-3') { echo 'selected'; } ?>>3 column</option>
      <option value="feup_f_col-4" <?php if($clss == 'feup_f_col-4') { echo 'selected'; } ?>>4 column</option>
      <option value="feup_f_col-5" <?php if($clss == 'feup_f_col-5') { echo 'selected'; } ?>>5 column</option>
    </select>
                <script type="text/javascript">
                (function($){
                    $(document).ready(function(){
                        $('select[name="user_settings[feup_f_col]"]').on('change',function(){
                            var val = $(this).val();
                            jQuery("ul.user-form-editor").removeClass (function (index, css) {
                                return (css.match (/(^|\s)feup_f_col-\S+/g) || []).join(' ');
                            });
                            $('ul.user-form-editor').addClass(val);
                        });
                    });
                })(jQuery);
                </script>
            <button class="button user-collapse"><?php _e( 'Toggle All Fields Open/Close', 'frontend_user_pro' ); ?></button>
        </div>
        <div class="user-updated">
            <p><?php _e( 'Click on a form element to add to the editor', 'frontend_user_pro' ); ?></p>
        </div>
        <ul id="user-form-editor" class="user-form-editor unstyled <?php echo $clss; ?>">
            <?php
            if ($form_inputs) {
                $count = 0;
                foreach ($form_inputs as $order => $input_field) {
                    $name = ucwords( str_replace( '_', ' ', $input_field['template'] ) );
                    if ( $input_field['template'] == 'taxonomy') {
                        USER_Formbuilder_Templates::$input_field['template']( $count, $name, $input_field['name'], $input_field );
                    } else {
                        USER_Formbuilder_Templates::$input_field['template']( $count, $name, $input_field );
                    }
                    $count++;
                }
            }
            ?>
        </ul>
        <?php
    }
    function form_settings_posts() {
        global $post;
        $form_settings = get_post_meta( $post->ID, $this->form_settings_key, true );
        $restrict_message = __( "This page is restricted. Please Log in / Register to view this page.", 'frontend_user_pro' );
        $post_type_selected = isset( $form_settings['post_type'] ) ? $form_settings['post_type'] : 'post';
        $post_status_selected = isset( $form_settings['post_status'] ) ? $form_settings['post_status'] : 'publish';
        $post_format_selected = isset( $form_settings['post_format'] ) ? $form_settings['post_format'] : 0;
        $default_cat = isset( $form_settings['default_cat'] ) ? $form_settings['default_cat'] : -1;
        $guest_post = isset( $form_settings['guest_post'] ) ? $form_settings['guest_post'] : 'false';
        $guest_details = isset( $form_settings['guest_details'] ) ? $form_settings['guest_details'] : 'true';
        $name_label = isset( $form_settings['name_label'] ) ? $form_settings['name_label'] : __( 'Name' );
        $email_label = isset( $form_settings['email_label'] ) ? $form_settings['email_label'] : __( 'Email' );
        $message_restrict = isset( $form_settings['message_restrict'] ) ? $form_settings['message_restrict'] : $restrict_message;
        $redirect_to = isset( $form_settings['redirect_to'] ) ? $form_settings['redirect_to'] : 'post';
        $edit_redirect_to = isset( $form_settings['edit_redirect_to'] ) ? $form_settings['edit_redirect_to'] : 'post';
        $assign_role = isset( $form_settings['assign_role'] ) ? $form_settings['assign_role'] : '1';
        $message = isset( $form_settings['message'] ) ? $form_settings['message'] : __( 'Post saved', 'frontend_user_pro' );
        $update_message = isset( $form_settings['update_message'] ) ? $form_settings['update_message'] : __( 'Post updated successfully', 'frontend_user_pro' );
        $page_id = isset( $form_settings['page_id'] ) ? $form_settings['page_id'] : 0;
        $url = isset( $form_settings['url'] ) ? $form_settings['url'] : '';
        $comment_status = isset( $form_settings['comment_status'] ) ? $form_settings['comment_status'] : 'open';
        $submit_text = isset( $form_settings['submit_text'] ) ? $form_settings['submit_text'] : __( 'Submit', 'frontend_user_pro' );
        $draft_text = isset( $form_settings['draft_text'] ) ? $form_settings['draft_text'] : __( 'Save Draft', 'frontend_user_pro' );
            // $preview_text = isset( $form_settings['preview_text'] ) ? $form_settings['preview_text'] : __( 'Preview', 'frontend_user_pro' );
        $draft_post = isset( $form_settings['draft_post'] ) ? $form_settings['draft_post'] : 'false';
        ?>
        <table class="form-table">
            <tr class="user-post-type">
                <th><?php _e( 'Post Type', 'frontend_user_pro' ); ?></th>
                <td>
                    <select name="user_settings[post_type]">
                        <?php
                        $post_types = get_post_types();
                        unset($post_types['attachment']);
                        unset($post_types['revision']);
                        unset($post_types['nav_menu_item']);
                            // unset($post_types['user_forms']);
                        unset($post_types['user_profile']);
                        foreach ($post_types as $post_type) 
                        {
                            printf('<option value="%s"%s>%s</option>', $post_type, selected( $post_type_selected, $post_type, false ), $post_type );
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr class="user-post-status">
                <th><?php _e( 'Post Status', 'frontend_user_pro' ); ?></th>
                <td>
                    <select name="user_settings[post_status]">
                        <?php
                        $statuses = get_post_statuses();
                        foreach ($statuses as $status => $label) {
                            printf('<option value="%s"%s>%s</option>', $status, selected( $post_status_selected, $status, false ), $label );
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr class="user-post-fromat">
                <th><?php _e( 'Post Format', 'frontend_user_pro' ); ?></th>
                <td>
                    <select name="user_settings[post_format]">
                        <option value="0"><?php _e( '- None -', 'frontend_user_pro' ); ?></option>
                        <?php
                        $post_formats = get_theme_support( 'post-formats' );
                        if ( isset($post_formats[0]) && is_array( $post_formats[0] ) ) {
                            foreach ($post_formats[0] as $format) {
                                printf('<option value="%s"%s>%s</option>', $format, selected( $post_format_selected, $format, false ), $format );
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr class="user-default-cat">
                <th><?php _e( 'Default Post Category', 'frontend_user_pro' ); ?></th>
                <td>
                    <?php
                    wp_dropdown_categories( array( 
                        'hide_empty' => false,
                        'hierarchical' => true,
                        'selected' => $default_cat,
                        'name' => 'user_settings[default_cat]',
                        'show_option_none' => __( '- None -', 'frontend_user_pro' )
                        ) );
                        ?>
                        <div class="description"><?php echo __( 'If users are not allowed to choose any category, this category will be used instead (if post type supports)', 'frontend_user_pro' ); ?></div>
                </td>
            </tr>
            <tr>
                <th><?php _e( 'Guest Post', 'frontend_user_pro' ); ?></th>
                <td>
                    <label>
                        <input type="hidden" name="user_settings[guest_post]" value="false">
                        <input type="checkbox" name="user_settings[guest_post]" value="true"<?php checked( $guest_post, 'true' ); ?> />
                        <?php _e( 'Enable Guest Post', 'frontend_user_pro' ) ?>
                    </label>
                    <div class="description">
                        <?php _e( 'Unregistered users will be able to submit posts', 'frontend_user_pro' ); ?>
                    </div>
                </td>
            </tr>
            <tr class="show-if-guest">
                <th><?php _e( 'User Details', 'frontend_user_pro' ); ?></th>
                <td>
                    <label>
                        <input type="hidden" name="user_settings[guest_details]" value="false">
                        <input type="checkbox" name="user_settings[guest_details]" value="true"<?php checked( $guest_details, 'true' ); ?> />
                        <?php _e( 'Require Name and Email address', 'frontend_user_pro' ) ?>
                    </label>
                    <p class="description"><?php _e( 'If requires, users will be automatically registered to the site using the name and email address', 'frontend_user_pro' ); ?></p>
                </td>
            </tr>
            <tr class="show-if-details-not">
                <th><?php _e( 'Guest Post User Role', 'frontend_user_pro' ); ?></th>
                <td>
                    <select name="user_settings[assign_role]">
                        <?php
                        $user =  new WP_User_Query('ID'); 
                        $authors = $user->get_results();
                        foreach ($authors as $author) {
                            printf('<option value="%s"%s>%s</option>', $author->ID, selected( $assign_role, $author->ID, false ), $author->display_name );
                        }
                        ?>
                    </select>
         
                    <div class="description">
                        <?php _e( 'Unregistered users will be able to submit posts role Assign to that post', 'frontend_user_pro' ); ?>
                    </div>
                </td>
            </tr>
            <tr class="show-if-guest show-if-details">
                <th><?php _e( 'Name Label', 'frontend_user_pro' ); ?></th>
                <td>
                    <label>
                        <input type="text" name="user_settings[name_label]" value="<?php echo esc_attr( $name_label ); ?>" />
                    </label>
                    <p class="description"><?php _e( 'Label text for name field', 'frontend_user_pro' ); ?></p>
                </td>
            </tr>
            <tr class="show-if-guest show-if-details">
                <th><?php _e( 'E-Mail Label', 'frontend_user_pro' ); ?></th>
                <td>
                    <label>
                        <input type="text" name="user_settings[email_label]" value="<?php echo esc_attr( $email_label ); ?>" />
                    </label>
                    <p class="description"><?php _e( 'Label text for email field', 'frontend_user_pro' ); ?></p>
                </td>
            </tr>
            <script type="text/javascript">
            jQuery(document).ready(function()
            {
            // Form settings: Guest post
            jQuery('#user-metabox-settings').on('change', 'input[type=checkbox][name="user_settings[guest_post]"]', function(e){
                e.preventDefault();
                var table = jQuery(this).closest('table');
                if ( jQuery(this).is(':checked') ) {
                    table.find('tr.show-if-guest').show();
                    table.find('tr.show-if-not-guest').hide();
                    table.find('tr.show-if-details-not').hide();
                     table.find('tr.show-if-details').hide();
                    table.find('tr.show-if-details-not').hide();
                    // table.find('tr.show-if-details-not').show();
                    jQuery('input[type=checkbox][name="user_settings[guest_details]"]').trigger('change');
                } else {
                    table.find('tr.show-if-guest').hide();
                    table.find('tr.show-if-not-guest').show();
                    table.find('tr.show-if-details-not').hide();
                     table.find('tr.show-if-details').hide();
                    table.find('tr.show-if-details-not').hide();
                                    }
            });
            jQuery('input[type=checkbox][name="user_settings[guest_post]"]').trigger('change');
            // From settings: User details
            jQuery('#user-metabox-settings').on('change', 'input[type=checkbox][name="user_settings[guest_details]"]', function(e){
                e.preventDefault();
                var table = jQuery(this).closest('table');
                if ( jQuery(this).is(':checked') ) {
                    table.find('tr.show-if-details').show();
                    table.find('tr.show-if-details-not').hide();
                } else {
                    table.find('tr.show-if-details').hide();
                    table.find('tr.show-if-details-not').show();
                    // table.find('tr.show-if-details-not').show();
                }
            });
            // jQuery('input[type=checkbox][name="user_settings[guest_details]"]').trigger('change');
            });
            </script>
            <tr class="show-if-not-guest">
                <th><?php _e( 'Unauthorized Message', 'frontend_user_pro' ); ?></th>
                <td>
                    <textarea rows="3" cols="40" name="user_settings[message_restrict]">
                        <?php echo esc_textarea( $message_restrict ); ?>
                    </textarea>
                    <div class="description">
                        <?php _e( 'Not logged in users will see this message', 'frontend_user_pro' ); ?>
                    </div>
                </td>
            </tr>
            <tr class="user-redirect-to">
                <th><?php _e( 'Redirect To', 'frontend_user_pro' ); ?></th>
                <td>
                    <select name="user_settings[redirect_to]">
                        <?php
                            $redirect_options = array(
                                'post' => __( 'Newly created post', 'frontend_user_pro' ),
                                'same' => __( 'Same Page', 'frontend_user_pro' ),
                                'page' => __( 'To a page', 'frontend_user_pro' ),
                                'url' => __( 'To a custom URL', 'frontend_user_pro' )
                                );
                            foreach ($redirect_options as $to => $label) {
                                printf('<option value="%s"%s>%s</option>', $to, selected( $redirect_to, $to, false ), $label );
                            }
                            ?>
                    </select>
                    <div class="description">
                        <?php _e( 'After successfull submit, where the page will redirect to', $domain = 'default' ) ?>
                    </div>
                </td>
            </tr>
            <tr class="user-page-id">
                <th><?php _e( 'Page', 'frontend_user_pro' ); ?></th>
                <td>
                    <select name="user_settings[page_id]">
                        <?php
                        $pages = get_posts(  array( 'numberposts' => -1, 'post_type' => 'page') );
                        foreach ($pages as $page) {
                            printf('<option value="%s"%s>%s</option>', $page->ID, selected( $page_id, $page->ID, false ), esc_attr( $page->post_title ) );
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr class="user-url">
                <th><?php _e( 'Custom URL', 'frontend_user_pro' ); ?></th>
                <td>
                    <input type="url" name="user_settings[url]" value="<?php echo esc_attr( $url ); ?>">
                </td>
            </tr>
            <tr class="user-same-page">
                <th><?php _e( 'Message to show', 'frontend_user_pro' ); ?></th>
                <td>
                    <textarea rows="3" cols="40" name="user_settings[message]"><?php echo esc_textarea( $message ); ?></textarea>
                </td>
            </tr>
            <tr class="user-comment">
                <th><?php _e( 'Comment Status', 'frontend_user_pro' ); ?></th>
                <td>
                    <select name="user_settings[comment_status]">
                        <option value="open" <?php selected( $comment_status, 'open'); ?>><?php _e('Open'); ?></option>
                        <option value="closed" <?php selected( $comment_status, 'closed'); ?>><?php _e('Closed'); ?></option>
                    </select>
                </td>
            </tr>
            <tr class="user-submit-text">
                <th><?php _e( 'Submit Post Button text', 'frontend_user_pro' ); ?></th>
                <td>
                    <input type="text" name="user_settings[submit_text]" value="<?php echo esc_attr( $submit_text ); ?>">
                </td>
            </tr>
            <tr>
                <th><?php _e( 'Post Draft', 'frontend_user_pro' ); ?></th>
                <td>
                    <label>
                        <input type="hidden" name="user_settings[draft_post]" value="false">
                        <input type="checkbox" name="user_settings[draft_post]" value="true"<?php checked( $draft_post, 'true' ); ?> />
                        <?php _e( 'Enable Saving as draft', 'frontend_user_pro' ) ?>
                    </label>
                    <div class="description"><?php _e( 'It will show a button to save as draft', 'frontend_user_pro' ); ?></div>
                </td>
            </tr>
        </table>
        <script type="text/javascript">
        jQuery(document).ready(function()
        {
            var $ = jQuery;
            var red = "<?php echo $redirect_to; ?>";
            // console.log(red);
            if (red != 'page' ) 
            {
                jQuery('select[name="user_settings[page_id]"]').closest('tr').css('display','none');
            }
            if(red != 'url')
            {
                jQuery('input[name="user_settings[url]"]').closest('tr').css('display','none');
            }
            jQuery(document).on('change','select[name="user_settings[redirect_to]"]',function()
            {
                var bb = jQuery(this).val();
                if (bb == 'page') 
                {
                    $('select[name="user_settings[page_id]"]').closest('tr').css('display','table-row');
                    $('input[name="user_settings[url]"]').closest('tr').css('display','none');
                }else if(bb == 'url')
                {
                    $('input[name="user_settings[url]"]').closest('tr').css('display','table-row');
                    $('select[name="user_settings[page_id]"]').closest('tr').css('display','none');
                }else
                {
                    $('input[name="user_settings[url]"]').closest('tr').css('display','none');
                    $('select[name="user_settings[page_id]"]').closest('tr').css('display','none');
                };
            });
        });
        </script>
        <?php
    }
    function form_elements_post() {
        ?>
        <div class="user-loading hide"></div>
        <h2><?php _e( 'Post Fields', 'frontend_user_pro' ); ?></h2>
        <div class="user-form-buttons">
            <button class="user-button button" data-name="post_title" data-type="text" title="<?php _e( 'Click to add to the editor', 'frontend_user_pro' ); ?>"><?php _e( 'Post Title', 'frontend_user_pro' ); ?></button>
            <button class="user-button button" data-name="post_content" data-type="textarea" title="<?php _e( 'Click to add to the editor', 'frontend_user_pro' ); ?>"><?php _e( 'Post Body', 'frontend_user_pro' ); ?></button>
            <button class="user-button button" data-name="post_excerpt" data-type="textarea" title="<?php _e( 'Click to add to the editor', 'frontend_user_pro' ); ?>"><?php _e( 'Excerpt', 'frontend_user_pro' ); ?></button>
            <button class="user-button button" data-name="post_tag" data-type="post_tag" title="<?php _e( 'Click to add to the editor', 'frontend_user_pro' ); ?>"><?php _e( 'Tags', 'frontend_user_pro' ); ?></button>
            <button class="user-button button" data-name="category" data-type="category" title="<?php _e( 'Click to add to the editor', 'frontend_user_pro' ); ?>"><?php _e( 'Category', 'frontend_user_pro' ); ?></button>
            <button class="user-button button" data-name="featured_image" data-type="image" title="<?php _e( 'Click to add to the editor', 'frontend_user_pro' ); ?>"><?php _e( 'Featured Image', 'frontend_user_pro' ); ?></button>
            <?php do_action( 'user_form_buttons_post' ); ?>
        </div>
        <h2><?php _e( 'Custom Taxonomies', 'frontend_user_pro' ); ?></h2>
        <div class="user-form-buttons">
            <?php
            $custom_taxonomies = get_taxonomies(array('_builtin' => false ) );
            if ( $custom_taxonomies ) {
                foreach ($custom_taxonomies as $tax) {
                    ?>
                    <button class="user-button button" data-name="taxonomy" data-type="<?php echo $tax; ?>" title="<?php _e( 'Click to add to the editor', 'frontend_user_pro' ); ?>"><?php echo $tax; ?></button>
                    <?php
                }
            } else {
                _e('No custom taxonomies found', 'frontend_user_pro');
            }?>
        </div>
        <?php
        $this->form_elements_common();
        // $this->publish_button();
    }
    function publish_button() {
        global $post, $pagenow;
        $post_type = $post->post_type;
        $post_type_object = get_post_type_object($post_type);
        $can_publish = current_user_can($post_type_object->cap->publish_posts);
        ?>
        <div class="submitbox" id="submitpost">
            <div id="major-publishing-actions">
                <!--<div id="publishing-action">-->
                    <!-- <span class="spinner"></span> -->
                    <?php
                    if ( !in_array( $post->post_status, array('publish', 'future', 'private') ) || 0 == $post->ID ) 
                    {
                        if ( $can_publish ) :
                            if ( !empty( $post->post_date_gmt ) && time() < strtotime( $post->post_date_gmt . ' +0000' ) ) :
                                ?>
                                <input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e( 'Schedule' ) ?>" />
                                <?php submit_button( __( 'Schedule' ), 'primary button-large', 'publish', false, array('accesskey' => 'p') ); ?>
                            <?php else : ?>
                                <input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e( 'Publish' ) ?>" />
                                <?php submit_button( __( 'Publish' ), 'primary button-large', 'publish', false, array('accesskey' => 'p') ); ?>
                            <?php endif;
                        else :
                            ?>
                            <input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e( 'Submit for Review' ) ?>" />
                            <?php submit_button( __( 'Submit for Review' ), 'primary button-large', 'publish', false, array('accesskey' => 'p') ); ?>
                            <?php
                        endif;
                    } else 
                    {
                        ?>
                        <input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e( 'Update' ) ?>" />
                        <input name="save" type="submit" class="button button-primary button-large" id="publish" accesskey="p" value="<?php esc_attr_e( 'Update' ) ?>" />
                        <?php 
                    } ?>
                <!--</div>-->
                <div class="clear"></div>
            </div>
        </div>
        <?php
    }
    function form_elements_common() {
        $title = esc_attr( __( 'Click to add to the editor', 'frontend_user_pro' ) );
        ?>
        <h2><?php _e( 'Custom Fields', 'frontend_user_pro' ); ?></h2>
        <div class="user-form-buttons">
            <button class="user-button button" data-name="custom_text" data-type="text" title="<?php echo $title; ?>"><?php _e( 'Text', 'frontend_user_pro' ); ?></button>
            <button class="user-button button" data-name="custom_textarea" data-type="textarea" title="<?php echo $title; ?>"><?php _e( 'Textarea', 'frontend_user_pro' ); ?></button>
            <button class="user-button button" data-name="custom_select" data-type="select" title="<?php echo $title; ?>"><?php _e( 'Dropdown', 'frontend_user_pro' ); ?></button>
            <button class="user-button button" data-name="custom_date" data-type="date" title="<?php echo $title; ?>"><?php _e( 'Date', 'frontend_user_pro' ); ?></button>
            <button class="user-button button" data-name="custom_multiselect" data-type="multiselect" title="<?php echo $title; ?>"><?php _e( 'Multi Select', 'frontend_user_pro' ); ?></button>
            <button class="user-button button" data-name="custom_radio" data-type="radio" title="<?php echo $title; ?>"><?php _e( 'Radio', 'frontend_user_pro' ); ?></button>
            <button class="user-button button" data-name="custom_checkbox" data-type="checkbox" title="<?php echo $title; ?>"><?php _e( 'Checkbox', 'frontend_user_pro' ); ?></button>
            <button class="user-button button" data-name="custom_image" data-type="image" title="<?php echo $title; ?>"><?php _e( 'Image Upload', 'frontend_user_pro' ); ?></button>
            <button class="user-button button" data-name="custom_file" data-type="file" title="<?php echo $title; ?>"><?php _e( 'File Upload', 'frontend_user_pro' ); ?></button>
            <button class="user-button button" data-name="custom_url" data-type="url" title="<?php echo $title; ?>"><?php _e( 'URL', 'frontend_user_pro' ); ?></button>
            <button class="user-button button" data-name="custom_email" data-type="email" title="<?php echo $title; ?>"><?php _e( 'Email', 'frontend_user_pro' ); ?></button>
            <button class="user-button button" data-name="custom_repeater" data-type="repeat" title="<?php echo $title; ?>"><?php _e( 'Repeat Field', 'frontend_user_pro' ); ?></button>
            <button class="user-button button" data-name="custom_hidden" data-type="hidden" title="<?php echo $title; ?>"><?php _e( 'Hidden Field', 'frontend_user_pro' ); ?></button>
            <button class="user-button button" data-name="custom_map" data-type="map" title="<?php echo $title; ?>"><?php _e( 'Google Maps', 'frontend_user_pro' ); ?></button>
            <?php do_action( 'user_form_buttons_custom' ); ?>
        </div>
        <h2><?php _e( 'Others', 'frontend_user_pro' ); ?></h2>
        <div class="user-form-buttons">
            <button class="user-button button" data-name="recaptcha" data-type="captcha" title="<?php echo $title; ?>"><?php _e( 'reCaptcha', 'frontend_user_pro' ); ?></button>
            <button class="user-button button" data-name="section_break" data-type="break" title="<?php echo $title; ?>"><?php _e( 'Section Break', 'frontend_user_pro' ); ?></button>
            <button class="user-button button" data-name="custom_html" data-type="html" title="<?php echo $title; ?>"><?php _e( 'HTML', 'frontend_user_pro' ); ?></button>
            <button class="user-button button" data-name="custom_css" data-type="custom_css1" title="<?php echo $title; ?>"><?php _e('Custom Css', 'frontend_user_pro'); ?></button>
            <button class="user-button button" data-name="custom_js" data-type="js" title="<?php echo $title; ?>"><?php _e('Custom Js', 'frontend_user_pro'); ?></button>
            <button class="user-button button" data-name="action_hook" data-type="action" title="<?php echo $title; ?>"><?php _e( 'Action Hook', 'frontend_user_pro' ); ?></button>
            <button class="user-button button" data-name="toc" data-type="action" title="<?php echo $title; ?>" style="width: 233px;" ><?php _e( 'Term &amp; Conditions', 'frontend_user_pro' ); ?></button>
            <?php do_action( 'user_form_buttons_other' ); ?>
            <button class="user-button button" data-name="really_simple_captcha" data-type="rscaptcha" title="<?php echo $title; ?>" style="width: 233px;" ><?php _e( 'Really Simple Captcha', 'frontend_user_pro' ); ?></button>
        </div>
        <?php
    }
    function add_meta_box_post() {
        add_meta_box('user-metabox-editor', __('Form Editor', 'frontend_user_pro') , array($this, 'metabox_post_form'), 'user_forms', 'normal', 'high');
        add_meta_box('user-metabox-save', __('Save', 'frontend_user_pro') , array($this , 'forms_form_elements_save'), 'user_forms', 'side', 'core');
        add_meta_box('user-metabox-fields', __('Add a Field', 'frontend_user_pro') , array($this, 'form_elements_post'), 'user_forms', 'side', 'core');
        add_meta_box( 
        'my-meta-box',
        __('Shortcode', 'frontend_user_pro'),
        array($this , 'form_elements_post_sc'),
        'user_forms',
        'side',
        'core'
    );
        do_action('user_add_custom_meta_boxes', get_the_ID());
        remove_meta_box('submitdiv', 'user_forms', 'side');
        remove_meta_box('slugdiv', 'user_forms', 'normal');
    }
    function forms_form_elements_save() 
    {
        $this->publish_button(); 
    }
    function form_elements_post_sc()
    { ?>
        <div class="submitbox" id="submitpost">
            <div id="major-publishing-actions">
                <p>Use the shortcode</p>
                <input type="text" name="sc" value="<?php echo "[wpfeup-add-form id='".get_the_ID()."']" ;  ?>" style="width:100%;" readonly>
                <p>to display this form inside a post, page or text widget.</p>
                <?php // echo "[wpfeup-add-form id=".get_the_ID()."]"; ?>
            </div>
        </div>
        <?php
    }
    function metabox_post_form( $post ) {
        ?>
        <h2 class="nav-tab-wrapper">
            <a href="#user-metabox" class="nav-tab" id="user-editor-tab"><?php _e( 'Form Editor', 'frontend_user_pro' ); ?></a>
            <a href="#user-metabox-settings" class="nav-tab" id="user-post-settings-tab"><?php _e( 'Post Settings', 'frontend_user_pro' ); ?></a>
            <a href="#user-metabox-settings-update" class="nav-tab" id="user-edit-settings-tab"><?php _e( 'Edit Settings', 'frontend_user_pro' ); ?></a>
            <a href="#user-metabox-notification" class="nav-tab" id="user-notification-tab"><?php _e( 'Notification', 'frontend_user_pro' ); ?></a>
            <?php do_action( 'user_post_form_tab' ); 
            if (isset($_GET['post']) ) {
            ?>
            <a target="_blank" href="<?php echo home_url(); ?>/?form_preview=<?php echo $_GET['post']; ?>" class="button-secondary" style="float:right;">
                <span style="padding: 4px 0px;" class="dashicons dashicons-welcome-view-site" style=""></span>
                Preview this form                       
            </a>
            <?php }
            ?>
        </h2>
        <div class="tab-content">
            <div id="user-metabox" class="group">
                <?php $this->edit_form_area(); ?>
            </div>
            <div id="user-metabox-settings" class="group">
                <?php $this->form_settings_posts(); ?>
            </div>
            <div id="user-metabox-settings-update" class="group">
                <?php $this->form_settings_posts_edit(); ?>
            </div>
            <div id="user-metabox-notification" class="group">
                <?php $this->form_settings_posts_notification(); ?>
            </div>
            <?php do_action( 'user_post_form_tab_content' ); ?>
        </div>
        <?php
    }
    function form_settings_posts_edit() {
        global $post;
        $form_settings = get_post_meta( $post->ID, $this->form_settings_key, true );
        $post_status_selected = isset( $form_settings['edit_post_status'] ) ? $form_settings['edit_post_status'] : 'publish';
        $redirect_to = isset( $form_settings['edit_redirect_to'] ) ? $form_settings['edit_redirect_to'] : 'same';
        $update_message = isset( $form_settings['update_message'] ) ? $form_settings['update_message'] : __( 'Post updated successfully', 'frontend_user_pro' );
        $page_id = isset( $form_settings['edit_page_id'] ) ? $form_settings['edit_page_id'] : 0;
        $url = isset( $form_settings['edit_url'] ) ? $form_settings['edit_url'] : '';
        $update_text = isset( $form_settings['update_text'] ) ? $form_settings['update_text'] : __( 'Update', 'frontend_user_pro' );
        ?>
        <table class="form-table">
            <tr class="user-post-status">
                <th><?php _e( 'Set Post Status to', 'frontend_user_pro' ); ?></th>
                <td>
                    <select name="user_settings[edit_post_status]">
                        <?php
                        $statuses = get_post_statuses();
                        foreach ($statuses as $status => $label) {
                            printf('<option value="%s"%s>%s</option>', $status, selected( $post_status_selected, $status, false ), $label );
                        }
                        printf( '<option value="_nochange"%s>%s</option>', selected( $post_status_selected, '_nochange', false ), __( 'No Change', 'frontend_user_pro' ) );
                        ?>
                    </select>
                </td>
            </tr>
            <tr class="user-same-page">
                <th><?php _e( 'Post Update Message', 'frontend_user_pro' ); ?></th>
                <td>
                    <textarea rows="3" cols="40" name="user_settings[update_message]"><?php echo esc_textarea( $update_message ); ?></textarea>
                </td>
            </tr>
                        <tr class="user-redirect-to">
                <th><?php _e( 'Redirect To', 'frontend_user_pro' ); ?></th>
                <td>
                    <select name="user_settings[edit_redirect_to]">
                        <?php
                        $redirect_options = array(
                            'post' => __( 'Newly created post', 'frontend_user_pro' ),
                            'same' => __( 'Same Page', 'frontend_user_pro' ),
                            'page' => __( 'To a page', 'frontend_user_pro' ),
                            'url' => __( 'To a custom URL', 'frontend_user_pro' )
                            );
                        foreach ($redirect_options as $to => $label) {
                            printf('<option value="%s"%s>%s</option>', $to, selected( $redirect_to, $to, false ), $label );
                        }
                        ?>
                    </select>
                    <div class="description">
                        <?php _e( 'After successfull submit, where the page will redirect to', $domain = 'default' ) ?>
                    </div>
                </td>
            </tr>
            <tr class="user-page-id">
                <th><?php _e( 'Page', 'frontend_user_pro' ); ?></th>
                <td>
                    <select name="user_settings[edit_page_id]">
                        <?php
                        $pages = get_posts(  array( 'numberposts' => -1, 'post_type' => 'page') );
                        foreach ($pages as $page) {
                            printf('<option value="%s"%s>%s</option>', $page->ID, selected( $page_id, $page->ID, false ), esc_attr( $page->post_title ) );
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr class="user-url">
                <th><?php _e( 'Custom URL', 'frontend_user_pro' ); ?></th>
                <td>
                    <input type="url" name="user_settings[edit_url]" value="<?php echo esc_attr( $url ); ?>">
                </td>
            </tr>
            <tr class="user-update-text">
                <th><?php _e( 'Update Post Button text', 'frontend_user_pro' ); ?></th>
                <td>
                    <input type="text" name="user_settings[update_text]" value="<?php echo esc_attr( $update_text ); ?>">
                </td>
            </tr>
        </table>
        <script type="text/javascript">
        jQuery(document).ready(function()
        {
            var $ = jQuery;
            var edit_red = "<?php echo $redirect_to; ?>";
            // console.log(edit_red);
            if (edit_red != 'page' ) 
            {
                $('select[name="user_settings[edit_page_id]"]').closest('tr').css('display','none');
            }
            if(edit_red != 'url')
            {
                $('input[name="user_settings[edit_url]"]').closest('tr').css('display','none');
            }
            jQuery(document).on('change','select[name="user_settings[edit_redirect_to]"]',function()
            {
                var bb = jQuery(this).val();
                if (bb == 'page') 
                {
                    $('select[name="user_settings[edit_page_id]"]').closest('tr').css('display','table-row');
                    $('input[name="user_settings[edit_url]"]').closest('tr').css('display','none');
                }else if(bb == 'url')
                {
                    $('input[name="user_settings[edit_url]"]').closest('tr').css('display','table-row');
                    $('select[name="user_settings[edit_page_id]"]').closest('tr').css('display','none');
                }else
                {
                    $('input[name="user_settings[edit_url]"]').closest('tr').css('display','none');
                    $('select[name="user_settings[edit_page_id]"]').closest('tr').css('display','none');
                };
            });
        });
        </script>
        <?php
    }
    function form_settings_posts_notification() {
        global $post;
        $new_mail_body = "Hi Admin,\r\n";
        $new_mail_body .= "A new post has been created in your site %sitename% (%siteurl%).\r\n\r\n";
        $edit_mail_body = "Hi Admin,\r\n";
        $edit_mail_body .= "The post \"%post_title%\" has been updated.\r\n\r\n";
        $create_mail_body = "Hi ,\r\n";
        $create_mail_body .= "The post \"%post_title%\" has been updated.\r\n\r\n";
        $mail_body = "Here is the details:\r\n";
        $mail_body .= "Post Title: %post_title%\r\n";
        $mail_body .= "Content: %post_content%\r\n";
        $mail_body .= "Author: %author%\r\n";
        $mail_body .= "Post URL: %permalink%\r\n";
        $mail_body .= "Edit URL: %editlink%";
        $form_settings = get_post_meta( $post->ID, $this->form_settings_key, true );
        $new_notificaton = isset( $form_settings['notification']['new'] ) ? $form_settings['notification']['new'] : 'on';
        $new_to = isset( $form_settings['notification']['new_to'] ) ? $form_settings['notification']['new_to'] : get_option( 'admin_email' );
        $new_subject = isset( $form_settings['notification']['new_subject'] ) ? $form_settings['notification']['new_subject'] : __( 'New post created', 'frontend_user_pro' );
        $new_body = isset( $form_settings['notification']['new_body'] ) ? $form_settings['notification']['new_body'] : $new_mail_body . $mail_body;
        $edit_notificaton = isset( $form_settings['notification']['edit'] ) ? $form_settings['notification']['edit'] : 'off';
        $create_notificaton = isset( $form_settings['notification']['create'] ) ? $form_settings['notification']['create'] : 'off';
        $edit_to = isset( $form_settings['notification']['edit_to'] ) ? $form_settings['notification']['edit_to'] : get_option( 'admin_email' );
        $edit_subject = isset( $form_settings['notification']['edit_subject'] ) ? $form_settings['notification']['edit_subject'] : __( 'A post has been edited', 'frontend_user_pro' );
        $create_subject = isset( $form_settings['notification']['create_subject'] ) ? $form_settings['notification']['create_subject'] : __( 'A post has been Created', 'frontend_user_pro' );
        $edit_body = isset( $form_settings['notification']['edit_body'] ) ? $form_settings['notification']['edit_body'] : $edit_mail_body . $mail_body;
        $create_body = isset( $form_settings['notification']['create_body'] ) ? $form_settings['notification']['create_body'] : $create_mail_body . $mail_body;
        ?>
        <h3><?php _e( 'New Post Notificatoin', 'frontend_user_pro' ); ?></h3>
        <table class="form-table">
            <tr>
                <th><?php _e( 'Notification', 'frontend_user_pro' ); ?></th>
                <td>
                    <label>
                        <input type="hidden" name="user_settings[notification][new]" value="off">
                        <input type="checkbox" name="user_settings[notification][new]" value="on"<?php checked( $new_notificaton, 'on' ); ?>>
                        <?php _e( 'Enable post notification', 'frontend_user_pro' ); ?>
                    </label>
                </td>
            </tr>
            <tr>
                <th><?php _e( 'To', 'frontend_user_pro' ); ?></th>
                <td>
                    <input type="text" name="user_settings[notification][new_to]" class="regular-text" value="<?php echo esc_attr( $new_to ) ?>">
                </td>
            </tr>
            <tr>
                <th><?php _e( 'Subject', 'frontend_user_pro' ); ?></th>
                <td><input type="text" name="user_settings[notification][new_subject]" class="regular-text" value="<?php echo esc_attr( $new_subject ) ?>"></td>
            </tr>
            <tr>
                <th><?php _e( 'Message', 'frontend_user_pro' ); ?></th>
                <td>
                    <textarea rows="6" cols="60" name="user_settings[notification][new_body]"><?php echo esc_textarea( $new_body ) ?></textarea>
                </td>
            </tr>
        </table>
        <h3><?php _e( 'Update Post Notificatoin', 'frontend_user_pro' ); ?></h3>
        <table class="form-table">
            <tr>
                <th><?php _e( 'Notification', 'frontend_user_pro' ); ?></th>
                <td>
                    <label>
                        <input type="hidden" name="user_settings[notification][edit]" value="off">
                        <input type="checkbox" name="user_settings[notification][edit]" value="on"<?php checked( $edit_notificaton, 'on' ); ?>>
                        <?php _e( 'Enable post notification', 'frontend_user_pro' ); ?>
                    </label>
                </td>
            </tr>
            <tr>
                <th><?php _e( 'To', 'frontend_user_pro' ); ?></th>
                <td><input type="text" name="user_settings[notification][edit_to]" class="regular-text" value="<?php echo esc_attr( $edit_to ) ?>"></td>
            </tr>
            <tr>
                <th><?php _e( 'Subject', 'frontend_user_pro' ); ?></th>
                <td><input type="text" name="user_settings[notification][edit_subject]" class="regular-text" value="<?php echo esc_attr( $edit_subject ) ?>"></td>
            </tr>
            <tr>
                <th><?php _e( 'Message', 'frontend_user_pro' ); ?></th>
                <td>
                    <textarea rows="6" cols="60" name="user_settings[notification][edit_body]"><?php echo esc_textarea( $edit_body ) ?></textarea>
                </td>
            </tr>
        </table>
        <h3><?php _e( 'Create Post Notificatoin to User', 'frontend_user_pro' ); ?></h3>
        <table class="form-table">
            <tr>
                <th><?php _e( 'Notification', 'frontend_user_pro' ); ?></th>
                <td>
                    <label>
                        <input type="hidden" name="user_settings[notification][create]" value="off">
                        <input type="checkbox" name="user_settings[notification][create]" value="on"<?php checked( $create_notificaton, 'on' ); ?>>
                        <?php _e( 'Enable post create notification to User', 'frontend_user_pro' ); ?>
                    </label>
                </td>
            </tr>
            <tr>
                <th><?php _e( 'Subject', 'frontend_user_pro' ); ?></th>
                <td><input type="text" name="user_settings[notification][create_subject]" class="regular-text" value="<?php echo esc_attr( $create_subject ) ?>"></td>
            </tr>
            <tr>
                <th><?php _e( 'Message', 'frontend_user_pro' ); ?></th>
                <td>
                    <textarea rows="6" cols="60" name="user_settings[notification][create_body]"><?php echo esc_textarea( $create_body ) ?></textarea>
                </td>
            </tr>
        </table>
        <h3><?php _e( 'You may use in message:', 'frontend_user_pro' ); ?></h3>
        <p>
            <code>%post_title%</code>, <code>%post_content%</code>, <code>%post_excerpt%</code>, <code>%tags%</code>, <code>%category%</code>,
            <code>%author%</code>, <code>%sitename%</code>, <code>%siteurl%</code>, <code>%permalink%</code>, <code>%editlink%</code>
            <br><code>%custom_{NAME_OF_CUSTOM_FIELD}%</code> e.g: <code>%custom_website_url%</code> for <code>website_url</code> meta field
        </p>
        <?php
    }
    function enqueue_scripts() {
        global $pagenow, $post;
        if ( !in_array( $pagenow, array( 'post.php', 'post-new.php') ) ) {
            return;
        }
        wp_enqueue_script( 'jquery-ui-autocomplete' );
        if ( !in_array( $post->post_type, array( 'user_forms', 'user_profile' ) ) ) {
            return;
        }
        // scripts
        wp_enqueue_script( 'jquery-smallipop', plugins_url("../../assets/js/jquery.smallipop-0.4.0.min.js" , __FILE__), array('jquery') );
        wp_enqueue_script( 'user-formbuilder', plugins_url("../../assets/js/formbuilder.js" , __FILE__), array('jquery', 'jquery-ui-sortable') );
        // styles
        wp_enqueue_style( 'jquery-smallipop', plugins_url("../../assets/css/jquery.smallipop.css" , __FILE__));
        wp_enqueue_style( 'user-formbuilder', plugins_url("../../assets/css/formbuilder.css" , __FILE__) );
        wp_enqueue_style( 'jquery-ui-core', plugins_url("../../assets/css/jquery-ui-1.9.1.custom.css" , __FILE__) );
    }
    function add_form_button_style() {
        global $pagenow, $post_type;
        if ( !in_array( $post_type, array( 'user_forms') ) ) {
            return;
        }
        $fixed_sidebar = FRONTEND_USER()->helper->get_option( 'fixed_form_element', false );
        ?>
        <style type="text/css">
            .wrap .add-new-h2, .wrap .add-new-h2:active {
                background: #21759b;
                color: #fff;
                text-shadow: 0 1px 1px #446E81;
            }
        </style>
        <?php
    }
    public function user_row_links( $actions, $user_object ) {
        if( FRONTEND_USER()->users->user_is_user( $user_object->ID ) ) {
            $actions['to_user_page'] = "<a class='cgc_ub_edit_badges' href='" . admin_url( "admin.php?page=user-users&action=edit&user=$user_object->ID") . "'>" . __( 'User profile', 'frontend_user_pro' ) . "</a>";
        }
        return $actions;
    }
    public function about_screen() {
        list( $display_version ) = explode( '-', user_plugin_version );
        $user_version = get_option( 'user_db_version', '1.0' );
        if ( version_compare( $user_version, '1.0', '<' ) && ! isset( $_GET['frontend_upgrade'] ) ) {
            printf(
                '<div class="wrap about-wrap"><p>' . __( 'User Permissions need to be updated, click <a href="%s">here</a> to start the upgrade.', 'frontend_user_pro' ) . '</p></div>',
                esc_url( add_query_arg( array( 'frontend_action' => 'upgrade_user_permissions' ), admin_url() ) )
                );
        }
        else
        {
            ?>
            <div class="wrap about-wrap">
                <h1><?php printf( __( 'Welcome to FRONTEND USER Pro!', 'frontend_user_pro' ), $display_version ); ?></h1>
                <div class="about-text">
                    <?php printf( __( 'Thank you for Purchasing! <br />Frontend User Pro   <br /> is ready to make your website faster, safer and better!', 'frontend_user_pro' ), $display_version ); ?>
                </div>
                <div class="user-badge">
                    
                </div>
              
                <p>
                    <?php _e('Now create frontend registration, login, guest post, survey forms, event registrations easily even if you are not a programmer!
Frontend User Pro
Frontend User Pro is a WordPress plugin designed to make it easy for you to create frontend registrations, login, guest post, survey forms, event registration and even uploading woocommerce or easy digital downloads product from frontend easily. You can embed forms into any post, page or even widget.'); 
                        ?>
                    </p>
                  
                    <h1>
                        <?php _e( "What's Included:", 'frontend_user_pro' ); ?>
                    </h1>
                    <div class="changelog">
                       
                        <div class="feature-section">
                             <li><?php _e( 'New USER Forms Class.', 'frontend_user_pro' );?></li>
                            <li><?php _e( 'Create Unlimited Login forms.', 'frontend_user_pro' );?></li>
                            <li><?php _e( 'Create Unlimited Registration forms.', 'frontend_user_pro' );?></li>
                            <li><?php _e( 'Create Unlimited Forms.', 'frontend_user_pro' );?></li>
                            <li><?php _e( 'Auto Responder, Respond user as they submit post or get register on your site.', 'frontend_user_pro' );?></li>
                            <li><?php _e( 'Conditional Menu, show different menu based on role, or login status.', 'frontend_user_pro' );?></li>
                            <li><?php _e( 'Content Protector, Show content based on role or login status.', 'frontend_user_pro' );?></li>
                            <li><?php _e( 'Get notified for every post user submit on your website.', 'frontend_user_pro' );?></li>
                            <li><?php _e( 'Create custom emails for every approved user.', 'frontend_user_pro' );?></li>
                            <li><?php _e( 'Support Custom Post type.', 'frontend_user_pro' );?></li>
                            <li><?php _e( 'Supports Meta Key.', 'frontend_user_pro' );?></li>
                            <li><?php _e( 'Supports Custom Taxtonomies.', 'frontend_user_pro' );?></li>
                            <li><?php _e( 'Support different Post status.', 'frontend_user_pro' );?></li>
                            <li><?php _e( 'Support Deafult post category.', 'frontend_user_pro' );?></li>
                            <li><?php _e( 'Create different role for different registration forms.', 'frontend_user_pro' );?></li>
                        
                        </div>
                    </div>
                </div>
                <?php
            }
        }
    public function welcome() {
        global $frontend_options;
        if ( ! get_transient( '_user_activation_redirect' ) )
            return;
        delete_transient( '_user_activation_redirect' );
        if ( is_network_admin() || isset( $_GET['activate-multi'] ) )
            return;
        wp_safe_redirect( admin_url( 'index.php?page=user-about' ) ); exit;
    }
    function user_readlist_page() {
        $this->options = get_option( 'readlist_verify_settings' );
        $this->options1 = get_option( 'readlist_pages_settings' );
        wp_enqueue_script( 'bootstrap-select', plugins_url("../../assets/js/bootstrap-multiselect.js" , __FILE__), array('jquery') );
        wp_enqueue_script( 'bootstrap', plugins_url("assets/js/bootstrap.min.js" , __FILE__), array('jquery') );
        // styles
        wp_enqueue_style( 'bootstrap', plugins_url("assets/css/bootstrap.css" , __FILE__) );
        wp_enqueue_style( 'bootstrap-select', plugins_url("../../assets/css/bootstrap-multiselect.css" , __FILE__));
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'one' ); 
                do_settings_sections( 'user-readlist' );
                submit_button(); 
                ?>
            </form>
        </div>
        <style type="text/css">
            #wpcontent {
                background: #f1f1f1 none repeat scroll 0 0;
            }
        </style>
        <?php
    }
    function confirmation_r() {
        if ($_GET) 
        {
            if(array_key_exists('confirm', $_GET)) 
            {   
                if ($_GET['confirm'] == true) {
                    global $wpdb;
                    $v= $_GET['confirm'];
                    $user_query = new WP_User_Query(
                        array(
                            'meta_key'    =>    'confirmation_r',
                            'meta_value'    =>  $v,
                            )
                        );
                    $users = $user_query->get_results();
                    $user_id = $users[0]->data->ID;
                    $new_role = get_user_meta($user_id , 'user_verified_role' , true);
                    wp_update_user( array ('ID' => $user_id, 'role' => $new_role ) ) ;
                    update_user_meta( $user_id, 'confirmation_r', '' );
                    $to = $users[0]->data->user_email;
                    $subject  = 'Congratulations!.';
                    $message  = '<strong>Congratulations '.$users[0]->data->user_login.'!<br/>';
                    $message .= '<br><br>Thanks to Register.<br/><br/>';
                    $message .= '<a href="'.get_home_url().'/">'.get_home_url().'/</a></strong>';
                    $headers = 'From: Admin<'.get_option( 'admin_email' ).'>' . "\r\n";
                    'Reply-To: '.get_option( 'admin_email' ). "\r\n" .
                    'X-Mailer: PHP/' . phpversion();
                    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                    mail($to, $subject, $message, $headers);
                }
            }
        }
    }
    function user_custom_uninstall() {
        global $wp_roles;
        remove_role( 'rpr_unverified' );
    }
    function readlist_settings_init(  ) 
    { 
        add_settings_section(
            'one',
            'ReadList Settings',
            array( $this, 'print_section_info' ),
            'user-readlist'
            );  
        add_settings_field(
            'readlist_access', 
            'Readlist Button Enable', 
            array( $this, 'access_callback' ), 
            'user-readlist', 
            'one'
            );
        add_settings_field(
            'login_sc', 
            'Select Login Shortcode for Logged out Users', 
            array( $this, 'login_sc_callback' ), 
            'user-readlist', 
            'one'
            );
        add_settings_field(
            'sign_up_sc', 
            'Select page for signup', 
            array( $this, 'signup_callback' ), 
            'user-readlist', 
            'one'
            );
        add_settings_field(
            'post', // ID
            'Post', // Title 
            array( $this, 'id_number_callback' ), // Callback
            'user-readlist', // Page
            'one' // Section           
            );      
        add_settings_field(
            'page', 
            'Page', 
            array( $this, 'page_callback' ), 
            'user-readlist', 
            'one'
            );   
        add_settings_field(
            'custom_post', 
            'Custom Post', 
            array( $this, 'custom_post_callback' ), 
            'user-readlist', 
            'one'
            );  
        add_settings_field(
            'selectpage', 
            'Select Page', 
            array( $this, 'spage_callback' ), 
            'user-readlist', 
            'one'
            );
        add_settings_field(
            'style_readlist', 
            'Custom Css', 
            array( $this, 'css_callback' ), 
            'user-readlist', 
            'one'
            );
        add_settings_field(
            'title', 
            'Rename Readlist button', 
            array( $this, 'title_callback' ), 
            'user-readlist', 
            'one'
            );
        register_setting(
            'one', // Option group
            'readlist_verify_settings', // Option name
            array( $this, 'sanitize' ) // Sanitize
            ); 
        register_setting(
            'one', // Option group
            'readlist_pages_settings', // Option name
            array( $this, 'sanitize1' ) // Sanitize
            ); 
        register_setting(
            'one', // Option group
            'readlist_styling_settings', // Option name
            '' // Sanitize
            );
        register_setting(
            'one', // Option group
            'readlist_title', // Option name
            '' // Sanitize
            );
        register_setting(
            'one', // Option group
            'readlist_access_setting', // Option name
            '' // Sanitize
            );
        register_setting(
            'one', // Option group
            'readlist_login_form', // Option name
            '' // Sanitize
            );
        register_setting(
            'one', // Option group
            'readlist_signup_link', // Option name
            '' // Sanitize
            );
    }
    public function access_callback()
    {
        $acc = get_option('readlist_access_setting');
        $ac = '' ;
        if ($acc) {
            if (array_key_exists('readlist_access', $acc)) {
                $ac = $acc['readlist_access'];
            }
        }
        ?>
        <input type="checkbox" id="readlist_access" name="readlist_access_setting[readlist_access]"  <?php checked( $ac, 1 ); ?> value='1'  />
        <div class="description">Logged Out User Enable button</div>
        <?php 
    }
    public function login_sc_callback()
    {
        $acc = get_option('readlist_login_form');
        $ac = '' ;
        if ($acc) {
            if (array_key_exists('login_sc', $acc)) {
                $ac = $acc['login_sc'];
            }
        }
        global $wpdb;
        $table = $wpdb->prefix.'posts';
        $page_types = $wpdb->get_results("SELECT *FROM $table WHERE `post_type` = 'user_logins' AND `post_status` = 'publish';");
        if (!$acc) {
            update_option('readlist_login_form' ,array('login_sc' => $page_types[0]->ID));
        }
        ?>
        <select id="readlist_login_form"  name="readlist_login_form[login_sc][]" size="5" style="width: 30%;">
            <?php               
            foreach ( $page_types  as $page_type ) { ?>
            <option <?php selected( $ac[0], $page_type->ID ); ?> value="<?php echo $page_type->ID; ?>" ><?php  echo $page_type->post_title ; ?></option>
            <?php
            } ?>
        </select>
        <?php
    }
    public function signup_callback() {
        $sign = get_option('readlist_signup_link');
        if ($sign) {
            if(array_key_exists('sign_up_sc', $sign))
            {
                $id = $sign['sign_up_sc'][0];
            }
        }else{
            $id = '';
        }
        global $wpdb;
        $table = $wpdb->prefix.'posts';
        $page_types = $wpdb->get_results("SELECT *FROM $table WHERE `post_type` = 'user_registrations' AND `post_status` = 'publish';");
        ?>
        <select id="readlist_signup_link" name="readlist_signup_link[sign_up_sc][]" size="5" style="width: 30%;">
            <?php               
            foreach ( $page_types  as $page_type ) {
                ?>
                <option <?php selected( $id, $page_type->ID ); ?> value="<?php echo $page_type->ID; ?>" ><?php  echo $page_type->post_title ; ?></option>
                <?php
            } ?>
        </select>    
        <?php
    }
    public function spage_callback(){ 
        ?>
        <select id="readlist_pages_settings" name="readlist_pages_settings[selectpage][]" multiple  size="5" style="width: 30%;">
            <?php               
            global $wpdb;
            $table = $wpdb->prefix.'posts';
            $page_types = $wpdb->get_results("SELECT *FROM `wp_posts`WHERE `post_type` = 'page' AND `post_status` = 'publish';");
            foreach ( $page_types  as $page_type ) {
                if ($page_type->post_name != 'readlist' && $page_type->post_name != 'login-form') {
                    $rt=$this->options1[$page_type->post_name] == '1'? 'selected':''; ?>
                    <option <?php echo $rt; ?> value="<?php echo $page_type->post_name; ?>" ><?php  echo $page_type->post_title ; ?></option>
                    <?php
                }
            } ?>
        </select>    
        <script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery('#readlist_pages_settings').multiselect();
            });
        </script>       
        <?php   
    }
    public function title_callback() {
        $acc = get_option('readlist_title');
        $ac = '' ;
        if ($acc) {
            if (array_key_exists('title', $acc)) {
                $ac = $acc['title'];
            }
        }
        ?>
        <input type="text" id="readlist_title" name="readlist_title[title]" value="<?php echo $ac; ?>" >
        <?php
    }
    public function css_callback() { 
        $acc = get_option('readlist_styling_settings');
        $ac = '' ;
        if ($acc) {
            if (array_key_exists('style_readlist', $acc)) {
                $ac = $acc['style_readlist'];
            }
        }
        ?>
        <textarea id="style_readlist" name="readlist_styling_settings[style_readlist]" rows="10" cols="60"><?php echo $ac ;?></textarea>
        <?php
    }
    public function page_section_info() { 
    }
    public function id_number_callback() {
        if(is_array($this->options) && array_key_exists('post', $this->options)){
            $rc_post = $this->options['post'];
        }
        else {
            $rc_post = '';
        }
        ?> 
        <input type="checkbox" id="post" name="readlist_verify_settings[post]"  <?php checked( $rc_post, 1 ); ?> value='1'  />
        <?php
    }
    public function page_callback() {
        if(is_array($this->options) && array_key_exists('page', $this->options)){
            $rc_page = $this->options['page'];
        }
        else {
            $rc_page = '';
        }
        ?>
        <input type="checkbox" id="page" name="readlist_verify_settings[page]"  <?php checked( $rc_page, 1 ); ?> value='1'  />
        <?php
    }
    public function custom_post_callback() {
        ?>
        <select id="example-getting-started-1" name="readlist_verify_settings[custom_post][]" multiple  size="5" style="width: 30%;">
            <!-- <option>select<option> -->
            <?php               
            $args = array(
                    // 'public'   => true,
                    // '_builtin' => false
                );
                $output = 'names'; // names or objects, note names is the default
                $operator = 'and'; // 'and' or 'or'
                $post_types = get_post_types( $args, $output, $operator ); 
                foreach ( $post_types  as $post_type ) 
                {
                    if ($post_type != 'post' && $post_type != 'page' && $post_type != 'user_forms' && $post_type != 'user_logins' && $post_type != 'user_registrations' && $post_type != 'user-forms' && $post_type != 'revision' && $post_type != 'nav_menu_item') 
                    {
                        $rt=$this->options[$post_type] == '1'? 'selected':''; ?>
                        <option <?php echo $rt; ?> value="<?php echo $post_type; ?>" ><?php  echo $post_type ; ?></option>
                        <?php
                    }
                } ?>
            </select>
            <script type="text/javascript">
                jQuery(document).ready(function() {
                    jQuery('#example-getting-started-1').multiselect();
                });
            </script>    
        <?php   
    }
    public function sanitize( $input ) {
        $new_input = array();
        if( isset( $input['post'] ) )
            $new_input['post'] = '1'; 
        if( isset( $input['page'] ) )
            $new_input['page'] = '1'; 
        if(!empty($input['custom_post'])){
            foreach ($input['custom_post'] as $key => $value) {
                $new_input[$value]='1';
            } 
        } 
        return $new_input;
    }
    public function sanitize1( $input1 ) {
        $new_input1 = array();
        if(!empty($input1['selectpage'])){
            foreach ($input1['selectpage'] as $key => $value) {
                $new_input1[$value]='1';
            }
        }
        return $new_input1;
    }
    public function print_section_info() {
    }
    function user_system_info_page() {
        global $wpdb, $user_settings; ?>
        <div class="wrap">
            <style>#system-info-textarea { width: 800px; height: 400px; font-family: Menlo, Monaco, monospace; background: none; white-space: pre; overflow: auto; display: block; }</style>
            <h2><?php _e( 'FRONTEND Frontend User Debugging Information', 'frontend_user_pro' ); ?></h2><br/>
            <form action="<?php echo esc_url( admin_url() ); ?>" method="post" dir="ltr">
                <textarea readonly="readonly" onclick="this.focus();this.select()" id="system-info-textarea" name="user-sysinfo" title="<?php _e( 'To copy the system info, click below then press Ctrl + C (PC) or Cmd + C (Mac).', 'frontend' ); ?>">
                    ### Begin FRONTEND Frontend User Debugging Information ###
                    ## Please include this information when posting support requests regarding USER ##
                    <?php do_action( 'user_system_info_before' ); ?>
                    Dashboard URL:                 <?php echo get_permalink( FRONTEND_USER()->helper->get_option( 'user-user-dashboard-page', false ) ) . "\n"; ?>
                    USER Version:                 <?php echo user_plugin_version . "\n"; ?>
                    USER Plugin Name:                 <?php echo user_plugin_name . "\n"; ?>
                    USER File Name:                 <?php echo user_plugin_file . "\n"; ?>
                    USER Plugin Path:                 <?php echo user_plugin_dir . "\n"; ?>
                    USER Plugin Url:                 <?php echo user_plugin_url . "\n"; ?>
                    USER Assets Url:                 <?php echo user_assets_url . "\n"; ?>
                    <?php
                    print_r( array_filter( $user_settings ) );
                    $posts = get_posts( array( 'post_type' => 'user-forms', 'posts_per_page'=>- 1 ) );
                    foreach ( $posts as $post ) {
                        echo $post->id ;
                        echo get_the_title( $post->id );
                        print_r( get_post_meta( $post->id, 'user-form', false ) );
                    }
                    ?>
                    USER TEMPLATES:
                    <?php
                    $dir = get_stylesheet_directory() . '/user_templates/*';
                    if ( !empty( $dir ) ) {
                        foreach ( glob( $dir ) as $file ) {
                            echo "Filename: " . basename( $file ) . "\n";
                        }
                    }
                    else {
                        echo 'No overrides found';
                    }
                    do_action( 'user_system_info_after' );
                    ?>
                    ### End System Info ###</textarea>
                    <p class="submit">
                        <input type="hidden" name="frontend-action" value="download_user_sysinfo" />
                        <?php submit_button( 'Download System Info File', 'primary', 'frontend-download-user-sysinfo', false ); ?>
                    </p>
                </form>
            </div>
        </div>
        <?php
    }
    /**
     * Generates the System Info Download File
     *
     * @since 1.4
     * @return void
     */
    function frontend_generate_user_sysinfo_download() {
        nocache_headers();
        header( "Content-type: text/plain" );
        header( 'Content-Disposition: attachment; filename="user-system-info.txt"' );
        echo wp_strip_all_tags( $_POST['user-sysinfo'] );
        frontend_die();
    }
    function user_form_import_page() {
        $options = get_option( 'pwsix_settings' );
        wp_enqueue_style('dashboard');
        wp_enqueue_script('dashboard');
        ?>
        <div class="wrap">
            <h2><?php screen_icon(); _e( 'Import/Export/Reset USER Forms' ); ?></h2>
            <?php if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'import' && isset( $_REQUEST['form'] ) ){
                echo '<div class="updated"><p>'.__('Successfully imported the ', 'frontend_user_pro' ) . $_REQUEST['form'] . __( ' form!' , 'frontend_user_pro').'</p></div>';
            }
            else if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'reset' && isset( $_REQUEST['form'] ) ){
                echo '<div class="updated"><p>'.__('Successfully reset the ', 'frontend_user_pro' ) . $_REQUEST['form'] . __( ' form!' , 'frontend_user_pro').'</p></div>';
            }
            else if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'delete' && isset( $_REQUEST['result'] ) ){
                if ( $_REQUEST['action'] == 'success' && isset ( $_REQUEST['count'] ) ){
                    if ( $_REQUEST['count'] > 0 ){
                        echo '<div class="updated"><p>'.__('Successfully removed ', 'frontend_user_pro' ) . $_REQUEST['count'] . __( ' extra form(s)!' , 'frontend_user_pro').'</p></div>';
                    }
                    else{
                        echo '<div class="updated"><p>'.__('No extra forms to remove!' , 'frontend_user_pro').'</p></div>';
                    }
                }
                else{
                    echo '<div class="updated"><p>'.__('No extra forms to remove!' , 'frontend_user_pro').'</p></div>';
                }
            }
            ?>
            <div class="metabox-holder meta-box-sortables ui-sortable">
                <div class="postbox ">
                    <div class="handlediv" title="Click to toggle"><br></div><h3 class="hndle"><span><?php _e( 'Submission Form Settings' ); ?></span></h3>
                    <div class="inside">
                        <p><?php _e( 'Import the submission form settings from a .json file.' ); ?></p>
                        <form method="post" enctype="multipart/form-data">
                            <p>
                                <input type="file" name="import_file"/>
                            </p>
                            <p>
                            <input type="hidden" name="user_action" value="import_submission_form_settings" />
                                <?php wp_nonce_field( 'import_submission_form_settings', 'import_submission_form_settings' ); ?>
                                <?php submit_button( __( 'Import','frontend_user' ), 'secondary', 'submit', false ); ?>
                            </p>
                        </form>
                        <p><?php _e( 'Export the submission form settings for this site as a .json file', 'frontend_user' ); ?></p>
                        <form method="post">
                        <p><input type="hidden" name="user_action" value="export_submission_form_settings" /></p>
                            <p>
                                <?php wp_nonce_field( 'export_submission_form_settings', 'export_submission_form_settings' ); ?>
                                <?php submit_button( __( 'Export','frontend_user' ), 'secondary', 'submit', false ); ?>
                            </p>
                        </form>
                    </div><!-- .inside -->
                </div><!-- .postbox -->
            </div><!-- .metabox-holder -->
            <div class="metabox-holder meta-box-sortables ui-sortable">
                <div class="postbox closed">
                    <div class="handlediv" title="Click to toggle"><br></div><h3 class="hndle"><span><?php _e( 'Registration Form Settings' ); ?></span></h3>
                    <div class="inside">
                        <p><?php _e( 'Import the registration form settings from a .json file.' ); ?></p>
                        <form method="post" enctype="multipart/form-data">
                            <p>
                                <input type="file" name="import_file"/>
                            </p>
                            <p>
                                <input type="hidden" name="user_action" value="import_registration_form_settings" />
                                <?php wp_nonce_field( 'import_registration_form_settings', 'import_registration_form_settings' ); ?>
                                <?php submit_button( __( 'Import','frontend_user_pro' ), 'secondary', 'submit', false ); ?>
                            </p>
                        </form>
                        <p><?php _e( 'Export the registration form settings for this site as a .json file', 'frontend_user_pro' ); ?></p>
                        <form method="post">
                            <p><input type="hidden" name="user_action" value="export_registration_form_settings" /></p>
                            <p>
                                <?php wp_nonce_field( 'export_registration_form_settings', 'export_registration_form_settings' ); ?>
                                <?php submit_button( __( 'Export','frontend_user_pro' ), 'secondary', 'submit', false ); ?>
                            </p>
                        </form>
                    </div><!-- .inside -->
                </div><!-- .postbox -->
            </div><!-- .metabox-holder -->
                    <div class="metabox-holder meta-box-sortables ui-sortable">
            <div class="postbox closed">
                <div class="handlediv" title="Click to toggle"><br></div><h3 class="hndle"><span><?php _e( 'Login Form Settings' ); ?></span></h3>
                <div class="inside">
                    <p><?php _e( 'Import the login form settings from a .json file.' ); ?></p>
                    <form method="post" enctype="multipart/form-data">
                        <p>
                            <input type="file" name="import_file"/>
                        </p>
                        <p>
                            <input type="hidden" name="user_action" value="import_login_form_settings" />
                            <?php wp_nonce_field( 'import_login_form_settings', 'import_login_form_settings' ); ?>
                            <?php submit_button( __( 'Import','frontend_user' ), 'secondary', 'submit', false ); ?>
                        </p>
                    </form>
                    <p><?php _e( 'Export the login form settings for this site as a .json file', 'frontend_user' ); ?></p>
                    <form method="post">
                        <p><input type="hidden" name="user_action" value="export_login_form_settings" /></p>
                        <p>
                            <?php wp_nonce_field( 'export_login_form_settings', 'export_login_form_settings' ); ?>
                            <?php submit_button( __( 'Export','frontend_user' ), 'secondary', 'submit', false ); ?>
                        </p>
                    </form>
                </div><!-- .inside -->
            </div><!-- .postbox -->
        </div><!-- .metabox-holder -->
        </div><!--end .wrap-->
        <?php
    }
    function user_process_form_import() {
        if ( !isset( $_POST['user_action'] ) || empty( $_POST['user_action'] ) ) {
            return;
        }
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        $form = $_POST['user_action'];
        switch ( $form ) {
        case 'import_login_form_settings':
                if ( !wp_verify_nonce( $_POST['import_login_form_settings'], 'import_login_form_settings' ) ) {
                    return;
                }
                $extension = explode( '.', $_FILES['import_file']['name'] );
                $extension = end( $extension );
                if ( $extension != 'json' ) {
                    wp_die( __( 'Please upload a valid .json file' ) );
                }
                $import_file = $_FILES['import_file']['tmp_name'];
                if ( empty( $import_file ) ) {
                    wp_die( __( 'Please upload a file to import' ) );
                }
                    // Retrieve the settings from the file and convert the json object to an array.
                $settings =  frontend_object_to_array( json_decode( file_get_contents( $import_file ) ) );
                foreach ($settings as $key => $value) 
                {
                 $page_data = array(
                    'post_status' => 'publish',
                    'post_type' => 'user_logins',
                    'post_author' => get_current_user_id(),
                    'post_title' => $value['title']
                    );
                 $page_id   = wp_insert_post( $page_data );
                 update_post_meta( $page_id, 'user-form', $value['user-form'] );
                 update_post_meta( $page_id, 'user_form_settings', $value['setting'] );
             }
                wp_safe_redirect( admin_url( 'admin.php?page=user-form-import-export&action=import&form=login&result=success' ) ); exit;
            break;
            case 'import_registration_form_settings':
                if ( !wp_verify_nonce( $_POST['import_registration_form_settings'], 'import_registration_form_settings' ) ) {
                    return;
                }
                $extension = explode( '.', $_FILES['import_file']['name'] );
                $extension = end( $extension );
                if ( $extension != 'json' ) {
                    wp_die( __( 'Please upload a valid .json file' ) );
                }
                $import_file = $_FILES['import_file']['tmp_name'];
                if ( empty( $import_file ) ) {
                    wp_die( __( 'Please upload a file to import' ) );
                }
                    // Retrieve the settings from the file and convert the json object to an array.
                $settings =  frontend_object_to_array( json_decode( file_get_contents( $import_file ) ) );
                    // if there's no form, let's make one
                foreach ($settings as $key => $value) 
                {
                   $page_data = array(
                            'post_status' => 'publish',
                            'post_type' => 'user_registrations',
                            'post_author' => get_current_user_id(),
                            'post_title' => $value['title']
                        );
                    $page_id   = wp_insert_post( $page_data );
                    update_post_meta( $page_id, 'user-form', $value['user-form'] );
                    update_post_meta( $page_id, 'user_form_settings', $value['setting'] );
                }
                wp_safe_redirect( admin_url( 'admin.php?page=user-form-import-export&action=import&form=registration&result=success' ) ); exit;
            break;
            case 'import_submission_form_settings':
                if ( !wp_verify_nonce( $_POST['import_submission_form_settings'], 'import_submission_form_settings' ) ) {
                    return;
                }
                $extension = explode( '.', $_FILES['import_file']['name'] );
                $extension = end( $extension );
                if ( $extension != 'json' ) {
                    wp_die( __( 'Please upload a valid .json file' ) );
                }
                $import_file = $_FILES['import_file']['tmp_name'];
                if ( empty( $import_file ) ) {
                    wp_die( __( 'Please upload a file to import' ) );
                }
                // Retrieve the settings from the file and convert the json object to an array.
                $settings =  frontend_object_to_array( json_decode( file_get_contents( $import_file ) ) );
                    // if there's no form, let's make one
                foreach ($settings as $key => $value) 
                {
                   $page_data = array(
                            'post_status' => 'publish',
                            'post_type' => 'user_forms',
                            'post_author' => get_current_user_id(),
                            'post_title' => $value['title']
                        );
                    $page_id   = wp_insert_post( $page_data );
                    update_post_meta( $page_id, 'user-form', $value['user-form'] );
                    update_post_meta( $page_id, 'user_form_settings', $value['setting'] );
                }
      
                wp_safe_redirect( admin_url( 'admin.php?page=user-form-import-export&action=import&form=submission&result=success' ) ); exit;
            break;
        }
    }
    function user_process_form_reset() {
        if ( !isset( $_POST['user_action'] ) || empty( $_POST['user_action'] ) ) {
            return;
        }
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        $form = $_POST['user_action'];
        switch ( $form ) {
            case 'reset_registration_form_settings':
            if ( !wp_verify_nonce( $_POST['reset_registration_form_settings'], 'reset_registration_form_settings' ) ) {
                return;
            }
            $import_file = user_plugin_dir . 'assets/backups/registration-form.json';
            // Retrieve the settings from the file and convert the json object to an array.
            $settings =  frontend_object_to_array( json_decode( file_get_contents( $import_file ) ) );
            // if there's no form, let's make one
            if ( ! FRONTEND_USER()->helper->get_option( 'user-registration-form', false ) ) {
                $page_data = array(
                    'post_status' => 'publish',
                    'post_type' => 'user_registrations',
                    'post_author' => get_current_user_id(),
                    'post_title' => __( 'Registration Form', 'frontend_user_pro' )
                    );
                $page_id   = wp_insert_post( $page_data );
                update_post_meta( $page_id, 'user-form', $settings );
            }
            else {
                update_post_meta( FRONTEND_USER()->helper->get_option( 'user-registration-form', false ) , 'user-form', $settings );
            }
            wp_safe_redirect( admin_url( 'admin.php?page=user-form-import-export&action=reset&form=registration&result=success' ) ); exit;
            break;
        }
    }
    function user_process_form_export() {
        if ( !isset( $_POST['user_action'] ) || empty( $_POST['user_action'] ) ) {
            return;
        }
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        $form = $_POST['user_action'];
        switch ( $form ) {
            case 'export_registration_form_settings':
                if ( !wp_verify_nonce( $_POST['export_registration_form_settings'], 'export_registration_form_settings' ) ) {
                    return;
                }
                $post_reg = get_posts('post_type=user_registrations');
                $settings =array();$tt = array();
                foreach ($post_reg as $key) {
                    $settings['user-form'] = get_post_meta( $key->ID , 'user-form', true );
                    $settings['title']     = $key->post_title;
                    $settings['setting']   = get_post_meta( $key->ID , 'user_form_settings', true );
                    $tt[] = $settings;
                }
                ignore_user_abort( true );
                nocache_headers();
                header( 'Content-Type: application/json; charset=utf-8' );
                header( 'Content-Disposition: attachment; filename=user-registration-form-export-' . date( 'm-d-Y' ) . '.json' );
                header( "Expires: 0" );
                echo json_encode( $tt) ;
                exit;
            break;
            case 'export_login_form_settings':
                if ( !wp_verify_nonce( $_POST['export_login_form_settings'], 'export_login_form_settings' ) ) {
                    return;
                }
                $post_reg = get_posts('post_type=user_logins');
                $settings =array();$tt = array();
                foreach ($post_reg as $key) {
                    $settings['user-form'] = get_post_meta( $key->ID , 'user-form', true );
                    $settings['title']     = $key->post_title;
                    $settings['setting']   = get_post_meta( $key->ID , 'user_form_settings', true );
                    $tt[] = $settings;
                }
                ignore_user_abort( true );
                nocache_headers();
                header( 'Content-Type: application/json; charset=utf-8' );
                header( 'Content-Disposition: attachment; filename=user-login-form-export-' . date( 'm-d-Y' ) . '.json' );
                header( "Expires: 0" );
                echo json_encode( $tt) ;
                exit;
            break;
            case 'export_submission_form_settings':
                if ( !wp_verify_nonce( $_POST['export_submission_form_settings'], 'export_submission_form_settings' ) ) {
                    return;
                }
                $post_reg = get_posts('post_type=user_forms');
                $settings =array();$tt = array();
                foreach ($post_reg as $key) {
                    $settings['user-form'] = get_post_meta( $key->ID , 'user-form', true );
                    $settings['title']     = $key->post_title;
                    $settings['setting']   = get_post_meta( $key->ID , 'user_form_settings', true );
                    $tt[] = $settings;
                }
                ignore_user_abort( true );
                nocache_headers();
                header( 'Content-Type: application/json; charset=utf-8' );
                header( 'Content-Disposition: attachment; filename=user-submission-form-export-' . date( 'm-d-Y' ) . '.json' );
                header( "Expires: 0" );
                echo json_encode( $tt );
                exit;
            break;
        }
    }
    function user_process_form_tools() {
        if ( !isset( $_POST['user_action'] ) || empty( $_POST['user_action'] ) ) {
            return;
        }
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        $form = $_POST['user_action'];
        switch ( $form ) {
            case 'delete_extra_forms':
            if ( !wp_verify_nonce( $_POST['delete_extra_forms'], 'delete_extra_forms' ) ) {
                return;
            }
            $forms = new WP_Query( array('post_type' => 'user-forms', 'fields' => 'ids', 'posts_per_page' => -1 ) );
            $forms = $forms->posts;
            if ( !$forms ){
                wp_safe_redirect( admin_url( 'admin.php?page=user-form-import-export&action=delete&result=fail' ) ); exit;
                break;
            }
            global $user_settings;
            $count = 0;
            foreach ( $forms as $form )
            {
                if ( isset( $user_settings['user-submission-form'] ) && $user_settings['user-submission-form'] == $form && $user_settings['user-submission-form'] ){
                    continue;
                }
                else if ( isset( $user_settings['user-profile-form'] ) && $user_settings['user-profile-form'] == $form && $user_settings['user-profile-form'] ){
                    continue;
                }
                else if ( isset( $user_settings['user-registration-form'] ) && $user_settings['user-registration-form'] == $form && $user_settings['user-registration-form'] ){
                    continue;
                }
                else if ( isset( $user_settings['user-login-form'] ) && $user_settings['user-login-form'] == $form && $user_settings['user-login-form'] ){
                    continue;
                }
                else if ( isset( $user_settings['user-user-contact-form'] ) && $user_settings['user-user-contact-form'] == $form && $user_settings['user-user-contact-form'] ){
                    continue;
                }
                else {
                    wp_delete_post( $form, true ); 
                    $count++;
                }
            }
            $settings = get_post_meta( FRONTEND_USER()->helper->get_option( 'user-login-form', false ) , 'user-form', true );
            wp_safe_redirect( admin_url( 'admin.php?page=user-form-import-export&action=delete&count='.$count.'&result=success' ) ); exit;
            break;
        }
    }
    function process_bulk_action() { 
        global $frontend_options;
        $ids = isset( $_GET['user'] ) ? $_GET['user'] : false;
        if ( empty( $ids ) )
            return;
        if ( !is_array( $ids ) )
            $ids = array( $ids );
        $current_action = $_GET['action'];
        $from_name = isset( $frontend_options[ 'from_name' ] ) ? $frontend_options[ 'from_name' ] : get_bloginfo( 'name' );
        $from_email = isset( $frontend_options[ 'from_email' ] ) ? $frontend_options[ 'from_email' ] : get_option( 'admin_email' );
        foreach ( $ids as $id ) 
        {
            if ( 'approve_user' === $current_action ) 
            {
                if ( $id < 2 ) 
                {
                    break;
                }
                if (  user_can( $id , 'user_is_admin' ) ||  user_can( $id, 'frontend_user' ) ) 
                {
                    break;
                }
                if ( ! user_can( $id, 'pending_user' ) ) 
                {
                    break;
                }
                $user = new WP_User($id);
                $user->remove_role( 'pending_user' );
                $user->add_role( 'frontend_user' );
                $subject = apply_filters( 'user_application_approved_message_subj', __( 'Application Approved', 'frontend_user_pro' ), 0 );
                $message = FRONTEND_USER()->helper->get_option( 'user-user-app-approved-email', '' );
                $type = "user";
                $args['permissions'] = 'user-user-app-approved-email-toggle';
                FRONTEND_USER()->emails->send_email( $user->user_email, $from_name, $from_email, $subject, $message, $type, $id, $args );
                do_action('user_approve_user_admin', $id);
                if ( isset($_GET['redirect']) && $_GET['redirect'] == '2')
                {
                    wp_redirect(admin_url( 'admin.php?page=user-users&user='.$id.'&action=edit&approved=2' )); exit;
                }
            }
            if ( 'revoke_user' === $current_action ) 
            {
                if ( $id < 2 ) 
                {
                    break;
                }
                if ( ! ( user_can( $id , 'user_is_admin' ) ||  user_can( $id, 'frontend_user' ) ) ) 
                {
                    break;
                }
                $user = new WP_User($id);
                $user->remove_role('frontend_user');
                $user->remove_cap('user_is_admin');
                $user->add_role('subscriber');
                // remove all their posts
                $args = array('post_type' => 'download', 'author' => $id, 'posts_per_page'=> -1, 'fields' => 'ids', 'post_status' => 'any' );
                $query = new WP_Query( $args );
                foreach ( $query->posts as $id )
                {
                    wp_delete_post( $id, false );
                }
                $subject = apply_filters( 'user_application_revoked_message_subj', __( 'Application Revoked', 'frontend_user_pro' ), 0 );
                $message = FRONTEND_USER()->helper->get_option( 'user-user-app-revoked-email', '' );
                FRONTEND_USER()->emails->send_email( $user->user_email, $from_name, $from_email, $subject, $message, "user", $id, array( 'user-user-app-revoked-email-toggle' ) );
                do_action('user_revoke_user_admin', $id);
            }
            if ( 'decline_user' === $current_action ) 
            {
                if ( $id < 2 ) {
                    break;
                }
                if ( user_can( $id , 'user_is_admin' ) ||  user_can( $id, 'frontend_user' ) ) {
                    break;
                }
                if ( ! user_can( $id, 'pending_user' ) ) {
                    break;
                }
                $user = new WP_User($id);
                $user->remove_role('pending_user');
                $subject    = apply_filters( 'user_application_declined_message_subj', __( 'Application Declined', 'frontend_user_pro' ), 0 );
                $message    = FRONTEND_USER()->helper->get_option( 'user-user-app-declined-email', '' );
                FRONTEND_USER()->emails->send_email( $user->user_email, $from_name, $from_email, $subject, $message, "user", $id, array( 'user-user-app-declined-email-toggle' ) );
                do_action( 'user_decline_user_admin', $id );
            }
            if ( 'suspend_user' === $current_action ) 
            {
                if ( $id < 2 ) 
                {
                    break;
                }
                if ( user_can( $id, 'pending_user' ) ) 
                {
                    break;
                }
                if ( user_can( $id, 'suspended_user' ) ) 
                {
                    break;
                }
                $user = new WP_User($id);
                $user->remove_role('frontend_user');
                $user->add_role('suspended_user');
                // remove all their posts
                $args = array('post_type' => 'download', 'author' => $id, 'posts_per_page'=> -1, 'fields' => 'ids', 'post_status' => 'any' );
                $query = new WP_Query( $args );
                foreach ( $query->posts as $download )
                {
                    update_post_meta( $download, 'user_previous_status', get_post_status( $download ) );
                     // Make sure products are never entirely deleted when suspending a user
                    if( defined( 'EMPTY_TRASH_DAYS' ) && ! EMPTY_TRASH_DAYS ) {
                        wp_update_post( array( 'ID' => $download, 'post_status' => 'draft' ) );
                    } else {
                        wp_trash_post( $download );
                    }
                }
                $subject    = apply_filters( 'user_user_suspended_message_subj', __( 'Suspended', 'frontend_user_pro' ), 0 );
                $message    = FRONTEND_USER()->helper->get_option( 'user-user-suspended-email', '' );
                FRONTEND_USER()->emails->send_email( $user->user_email, $from_name, $from_email, $subject, $message, 'user', $id, array( 'user-user-suspended-email-toggle' ) );
                do_action('user_user_suspended_admin', $id );
                if ( isset( $_GET['redirect'] ) && $_GET['redirect'] == '2' ) 
                {
                    wp_redirect(admin_url( 'admin.php?page=user-users&user='.$id.'&action=edit&approved=2' )); exit;
                }
            } 
            if ( 'unsuspend_user' === $current_action ) 
            {
                if ( $id < 2 ) 
                {
                    break;
                }
                if ( user_can( $id, 'pending_user' ) ) 
                {
                    break;
                }
                if ( user_can( $id, 'frontend_user' ) ) 
                {
                    break;
                }
                $user = new WP_User($id);
                $user->add_role('frontend_user');
                $user->remove_role('suspended_user');
                // remove all their posts
                $args = array('post_type' => 'download', 'author' => $id, 'posts_per_page'=> -1, 'fields' => 'ids', 'post_status' => array( 'pending', 'trash' ) );
                $query = new WP_Query( $args );
                foreach ( $query->posts as $download )
                {
                    $status = get_post_meta( $download, 'user_previous_status', true );
                    if ( ! $status ) 
                    {
                        $status = 'pending';
                    }
                    wp_update_post( array( 'ID' => $download, 'post_status' => $status ) );
                    wp_untrash_post_comments( $download );
                }
                $subject = apply_filters( 'user_user_unsuspended_message_subj', __( 'Unsuspended', 'frontend_user_pro' ), 0 );
                $message = FRONTEND_USER()->helper->get_option( 'user-user-unsuspended-email', '' );
                FRONTEND_USER()->emails->send_email( $user->user_email, $from_name, $from_email, $subject, $message, "user", $id, array( 'user-user-unsuspended-email-toggle' ) );
                do_action('user_user_unsuspended_admin', $id);
                if ( isset( $_GET['redirect'] ) && $_GET['redirect'] == '2' ) 
                {
                    wp_redirect(admin_url( 'admin.php?page=user-users&user='.$id.'&action=edit&approved=2' )); exit;
                }
            }
        }
    }
}
function inner_script() {
    wp_enqueue_script('data-6' ,plugins_url( '../../assets/js/modernizr.custom.js', __FILE__ ));
    wp_enqueue_script('data-7' ,plugins_url( '../../assets/js/classie.js', __FILE__ ));
    wp_enqueue_script('data-8' ,plugins_url( '../../assets/js/uiMorphingButton_fixed.js', __FILE__ ));
    wp_enqueue_script('data-9' ,plugins_url( '../../assets/js/nicescroll.js', __FILE__ ));
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function()
        { 
            jQuery(".uk-read-markup .uk-nav").niceScroll();
        }); 
    </script>
    <?php
}
function custom_test() { 
    $user_id = wp_get_current_user(); 
    $uid = $user_id->ID;
    $set = get_option( 'readlist_verify_settings'); 
    $pages = get_option('readlist_pages_settings');
    if ($set) 
    { 
        foreach($set as $post_type => $value){ 
            if ($GLOBALS['post']->post_type == $post_type && $post_type != 'product' && $post_type != 'page') 
            {
                inner_script($uid , $post_type);                
            }
        }
    }
    if ($pages) 
    {
        foreach ($pages as $page => $value) {
            if ($GLOBALS['post']->post_type == 'page' && $GLOBALS['post']->post_name == $page) 
            {
                inner_script($uid , $post_type);
            }
        }
    }
    $post_type = $GLOBALS['post']->post_type;
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function()
        {
            jQuery('.add_readlist').on('click', function(e) 
            {   
                e.preventDefault();         
                var uid='<?php echo $uid; ?>';
                var post_type='<?php echo $post_type; ?>';
                var th = jQuery(this); 
                var pa = th.parent().next('.morph-button');
                var post_id = jQuery(this).data('id'); 
                
                jQuery.ajax(
                {
                    type: "post",
                    url: '<?php echo admin_url("/admin-ajax.php"); ?>',
                    data: { 
                        action: 'load_readlist',
                        post_id: pa.find('.uk-hidden-fields .uk-post-id').val(),
                        post_type: pa.find('.uk-hidden-fields .uk-post-type').val(),                            
                        _rand_str: '5sdf86'
                    }, 
                    success: function(data) { 
                        if(data == '1') {
                            text = 'error';
                        } else {
                            text = data;
                            pa.find('.uk-nav.uk-nav-dropdown').empty();
                            pa.find('.uk-nav.uk-nav-dropdown').append(data);
                        }
                    }, 
                    error:function(res) {
                        console.log(res);
                    } 
                }); 
                
                jQuery('.un-open-'+post_id+'.morph-button').addClass('active');
                setTimeout(function() 
                {
                    jQuery('.un-open-'+post_id+'.morph-button').addClass('open'); 
                }, 100);
            }); 
            jQuery(".readlist12").click(function(event)
            {
                alert("Already added to readlist");
                event.preventDefault();
            }); 
            jQuery('.icon-close.uk-close').click(function(event)
            {
                event.preventDefault();
                var cl = jQuery(this);
                var post_id = '';
                if( cl.parents('.morph-button').hasClass('open') ) 
                {
                    cl.parents('.morph-button').removeClass('open'); 
                    setTimeout( function() {
                        cl.parents('.morph-button').removeClass('active'); 
                    }, 100);
                    jQuery('.un-morph-content-left').removeClass('active'); 
                    jQuery('.un-morph-content-right').removeClass('active'); 
                }
            }); 
            jQuery(document).on('click', '.readlist1', function(event)
            {
                var $ = jQuery;
                var id = $(this).attr('id');
                console.log(id);
                var res = id.split("*"); 
                var post_id = res[0];
                var uid= res[1];
                var post_type= res[2];
                event.preventDefault();
                var post_id = jQuery(this).data('id'); 
                jQuery('.uk-spin').show(200);
                jQuery.ajax(
                {
                    type: "post",
                    url: '<?php echo admin_url("/admin-ajax.php"); ?>',
                    data: { 
                        action: 'user_wish',
                        post_id : this.id,
                        uid:uid
                    },
                    success: function(data) 
                    { 
                        if (data == '1') {
                            text = 'Item Already Exist in List';
                        }else if(data == '0'){
                            text = "Success";
                        };
                        jQuery('.uk-spin').hide(100);
                        jQuery('.uk-notice').show();
                        setTimeout(function() 
                        {
                            jQuery('.uk-notice').html(text);
                        }, 1500);
                    }, 
                    error:function(res) {
                        console.log(res);
                    } 
                }); 
            }); 
            jQuery('.add_active').click(function(event){
                event.preventDefault();
                jQuery(this).parents('.morph-button').find('.un-morph-content-left').addClass('active');
                jQuery(this).parents('.morph-button').find('.un-morph-content-right').addClass('active');
            }); 
            jQuery(document).on('submit', '.readlist_form', function(event)
            { 
                var th = jQuery(this);
                var postid = jQuery('.post_id_read').val();
                var uid = '<?php echo $uid; ?>';
                var posttype = jQuery('.post_type_read').val();
                event.preventDefault();
                jQuery('.uk-spin').show(200);
                if( th.find('input[name="cwish"]').val().trim() ) 
                {
                    jQuery.ajax(
                    {
                        type: "post",
                        url: '<?php echo admin_url("/admin-ajax.php"); ?>',
                        data: 
                        { 
                            action: 'add_readlist',
                            post_id: postid,
                            post_type: posttype,
                            cwish: th.find('input[name="cwish"]').val().trim(),
                            _rand_str: '5sdf86'
                        }, 
                        success: function(data)
                        { 
                            if(data == '1') 
                            {
                                text = 'Name Already Exist';
                            }else if(data == '2') 
                            {
                                text = 'Item Already Exist in List';
                            } 
                            else 
                            {
                                text = 'success';
                                window.location.reload();
                                th.parent().find('.uk-nav.uk-nav-dropdown').append(data);
                            }
                            jQuery('.uk-spin').hide(200);
                            jQuery('.uk-notice').show(250);
                            setTimeout(function() 
                            {
                                jQuery('.uk-notice').html(text).hide(200);
                            }, 1500);
                        }, 
                        error:function(res) {
                            console.log(res);
                        } 
                    }); 
                } else 
                { 
                }
            });
            jQuery(document).on('submit', '.readlist_form_2', function(event)
            { 
                var th = jQuery(this);
                var postid = jQuery('.post_id_read').val();
                var uid = '<?php echo $uid; ?>';
                var posttype = jQuery('.post_type_read').val();
                event.preventDefault();
                jQuery('.uk-spin').show(200);
                if( th.find('input[name="cwish"]').val().trim() ) 
                {
                    jQuery.ajax(
                    {
                        type: "post",
                        url: '<?php echo admin_url("/admin-ajax.php"); ?>',
                        data: 
                        { 
                            action: 'add_readlist_2',
                                            // post_id: postid,
                                            // post_type: posttype,
                                            cwish: th.find('input[name="cwish"]').val().trim(),
                                            _rand_str: '5sdf86'
                                        }, 
                                        success: function(data)
                                        { 
                                            if(data == '1') 
                                            {
                                                text = 'Name Already Exist';
                                            }else if(data == '2') 
                                            {
                                                text = 'Item Already Exist in List';
                                            } 
                                            else 
                                            {
                                                text = 'success';
                                                window.location.reload();
                                                //th.parent().find('.uk-nav.uk-nav-dropdown').append(data);
                                            }
                                            jQuery('.uk-spin').hide(200);
                                            jQuery('.uk-notice').show(250);
                                            setTimeout(function() 
                                            {
                                                jQuery('.uk-notice').html(text).hide(200);
                                            }, 1500);
                                        }, 
                                        error:function(res) {
                                            console.log(res);
                                        } 
                                    }); 
                } else 
                { 
                }
            });
        });
    </script>
    <!-- morph-button -->   
    <?php
}
add_action('wp_footer','custom_test');
function create_readlist() { 
    ob_start();
    $renam_read = get_option('readlist_title');
    if ($renam_read) {
        if (array_key_exists('title', $renam_read)) 
        {
            $renam_read = $renam_read['title'];
        }
    }else{
        $renam_read = 'Readlist';
    }
    $post_id = $GLOBALS['post']->ID;
    $post_type = $GLOBALS['post']->post_type;
    ?>
    <!-- <h1 class="entry-title">Create A New <?php //echo $renam_read; ?></h1> -->
    <form class="readlist_form" method="post" action=""> 
        <input type="hidden" value="<?php echo $post_id ;?>" class="post_id_read">
        <input type="hidden" value="<?php echo $post_type ;?>" class="post_type_read">
        <input type="text" name="cwish" placeholder="Enter <?php echo $renam_read; ?> name">
        <input type="submit" name="sub" value="Create">
    </form>
    <?php 
    return ob_get_clean();
}
add_shortcode('create_readlist', 'create_readlist');
function ajax_user_wish() {
    global $wpdb;
    $post_id=$_POST['post_id'];
    $r=explode('*',$post_id);
    $uid=$_POST['uid'];
    $pid=$r[0];
    $wnam=$r[1];
    $hh = $wpdb->prefix.'posts';
    $results = $wpdb->get_results("select * from $hh where ID='".$pid."'");
    $type=$results[0]->post_type;
    $gg = $wpdb->prefix.'user_readlist';
    $vbs=$wpdb->get_results("select * from $gg where post_id='".$pid."' and user_id='".$uid."' and readlists_name='".$wnam."' and type='".$type."'" );
    if(empty($vbs))
    {
            //echo $type;
        $gg = $wpdb->prefix.'user_readlist';
        $wpdb->query("insert into $gg (post_id,user_id,type,added,readlists_name) values ('".$pid."','".$uid."','".$type."','1','".$wnam."')");
            //echo mysql_error();
        echo "0";
    }else{
        echo "1";
    }
    die();
}
add_action( 'wp_ajax_user_wish', 'ajax_user_wish');
function add_readlist() {
    if(isset($_POST['cwish'])) 
    { 
        global $wpdb;
        extract($_POST);
        $list = $_POST['cwish'];
        $rr = $wpdb->query("select * from ".$wpdb->prefix."readlists where readlist_name ='".$list."'");
        if(empty($rr)) 
        { 
            $user = wp_get_current_user();
            $uid = $user->ID;
            $pid = $_POST['post_id'];
            $wnam = $_POST['cwish'];
            $type = $_POST['post_type'];
            $gg = $wpdb->prefix.'user_readlist';
            if ($pid && $type) {
                $vbs=$wpdb->get_results("select * from $gg where post_id='".$pid."' and user_id='".$uid."' and readlists_name='".$wnam."' and type='".$type."'" );
            }else{
                $vbs= '' ;
            }
            if(empty($vbs))
            {
                $gg = $wpdb->prefix.'user_readlist';
                $wpdb->query("insert into $gg (post_id,user_id,type,added,readlists_name) values ('".$pid."','".$uid."','".$type."','1','".$wnam."')");
                $tb1 = $wpdb->prefix."readlists";
                $wpdb->query("insert into $tb1 (readlist_name,type) values ('".$list."','public')");
                $sqls = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."readlists order by id desc limit 1");
                if ($sqls) 
                { 
                    foreach ($sqls as $sql) :
                        echo "<li class='readlist_name' id='".$sql->id."'>";
                    echo "<a id='".$post_id."*".$sql->readlist_name."*".$post_type."' class='readlist1' href='#'>".$sql->readlist_name."</a></li>";                                         
                    endforeach; 
                } 
            }else{
                echo "2";
            }
        }else 
        { 
            echo '1';
        }
    } 
    die(); 
}
add_action('wp_ajax_add_readlist', 'add_readlist');
function add_readlist_2() {
    if(isset($_POST['cwish'])) 
    { 
        global $wpdb;
        extract($_POST);
        $list = $_POST['cwish'];
        $rr = $wpdb->query("select * from ".$wpdb->prefix."readlists where readlist_name ='".$list."'");
        if(empty($rr)) 
        { 
            $user = wp_get_current_user();
            $uid = $user->ID;
                // $pid = $_POST['post_id'];
            $wnam = $_POST['cwish'];
            $tb1 = $wpdb->prefix."readlists";
            $wpdb->query("insert into $tb1 (readlist_name,type) values ('".$list."','public')");
        }else 
        { 
            echo '1';
        }
    } 
    die(); 
}
add_action('wp_ajax_add_readlist_2', 'add_readlist_2');
function load_readlist() {
    extract($_POST);
    global $wpdb;
    $tt = $wpdb->prefix."readlists";
    $sqls = $wpdb->get_results("SELECT * FROM $tt");
    if ($sqls) { 
        foreach ($sqls as $sql) :
            echo "<li class='readlist_name' id='".$sql->id."'>";
        echo "<a id='".$post_id."*".$sql->readlist_name."*".$post_type."' class='readlist1' href='#'>".$sql->readlist_name."</a></li>";                                         
        endforeach; 
    } else { 
        echo '<li>No List</li>';
    }
    die(); 
}
add_action('wp_ajax_load_readlist', 'load_readlist');
function user_admin_post_status( $status ) {
    if ( $status == 'publish' ) {
        $title = __( 'Published', 'frontend_user_pro' );
        $fontcolor = '#009200';
    } else if ( $status == 'draft' || $status == 'private' ) {
        $title = __( 'Draft', 'frontend_user_pro' );
        $fontcolor = '#bbbbbb';
    } else if ( $status == 'pending' ) {
        $title = __( 'Pending', 'frontend_user_pro' );
        $fontcolor = '#C00202';
    } else if ( $status == 'future' ) {
        $title = __( 'Scheduled', 'frontend_user_pro' );
        $fontcolor = '#bbbbbb';
    }
    echo '<span style="color:' . $fontcolor . ';">' . $title . '</span>';
}
$set=get_option( 'readlist_verify_settings');
add_shortcode('readlist_btn' , 'readlist_btn_fn');
function readlist_btn_fn() {
    $renam_read = get_option('readlist_title');
    if ($renam_read) {
        if (array_key_exists('title', $renam_read)) 
        {
            $renam_read = $renam_read['title'];
        }
    }else{
        $renam_read = 'Readlist';
    }
    $css = get_option('readlist_styling_settings');
    ?>
    <style type="text/css">
        .add_wish a 
        {
            text-decoration: none;
        }
        .caret{
            vertical-align: middle !important;
        }
        .caret {
            border-left: 4px solid rgba(0, 0, 0, 0);
            border-right: 4px solid rgba(0, 0, 0, 0);
            border-top: 4px solid #000000;
            content: "";
            display: inline-block;
            height: 0;
            vertical-align: top;
            width: 0;
        }
        <?php echo $css['style_readlist'];?>
        .iris-slider.iris-strip {
            display: none;
        }
        .wp-picker-container .iris-picker {
            border-color: #fff;
        }
    </style>
    <div class="uk-read-markup">
        <div class="uk-button-group add_wish">
            <button class="add_readlist  uk-button"  value="default" >Add to <?php echo $renam_read; ?></button>                        
        </div>
    </div>
    <?php
}
function inner_content($id , $posttype) {
    global $wpdb;
    $uid = $id;
    $post_type = $posttype;
    $post_id = $GLOBALS['post']->ID;
    $tb1 = $wpdb->prefix."user_readlist";
    $results = $wpdb->get_results("select ID from $tb1 where post_id='".$post_id."' and user_id='".$uid."'");
    $renam_read = get_option('readlist_title');
    if ($renam_read) 
    {
        if (array_key_exists('title', $renam_read)) 
        {
            $renam_read = $renam_read['title'];
        }
    }else
    {
        $renam_read = 'Readlist';
    }
    $user = wp_get_current_user();
    $approve = get_user_meta($user->ID , 'feu-approve-user' ,true);
    $acc = get_option('readlist_access_setting');
    if ($acc['readlist_access'] == 1 || (is_user_logged_in() &&$approve == 1) )
    { 
        wp_deregister_script ('jQuery');
        wp_register_script('uikit',plugins_url( '../../assets/js/uikit.min.js', __FILE__ ), array('jquery'), '1.0.0', true );
        wp_enqueue_script('uikit');
        wp_enqueue_style('data-1' ,plugins_url( 'assets/css/uikit.docs.min.css', __FILE__ ) );
        wp_enqueue_style('data-2' ,plugins_url( '../../assets/css/component.css', __FILE__ ) );
        wp_enqueue_style('data-3' ,plugins_url( '../../assets/css/font-awesome.min.css', __FILE__ ) );
        wp_enqueue_script('data-4' ,plugins_url( '../../assets/js/script.js', __FILE__ ) );
        wp_enqueue_style('data-5' ,plugins_url( '../../assets/css/style.css', __FILE__ ) );
        ?>
        
        <div class="uk-read-markup">
            <?php 
            if($results) 
            { ?>
                <div class="uk-button-group add_wish">
                    <button class="add_readlist  uk-button" id="<?php echo $post_id."*default*".$post_type; ?>" data-id="<?php echo $post_id; ?>" value="default">Add to <?php echo $renam_read; ?></button>                        
                </div> 
                <?php 
            } else 
            { ?>
                <div class="uk-button-group add_wish">
                    <button class="add_readlist  uk-button" id="<?php echo $post_id."*default*".$post_type; ?>" data-id="<?php echo $post_id; ?>" value="default">Add to <?php echo $renam_read; ?></button>                            
                </div>                  
                <?php 
            } 
            if (is_user_logged_in()) 
            { ?>
                <div class="un-open-<?php echo $post_id; ?> morph-button morph-button-modal morph-button-modal-1 morph-button-fixed">                       
                    <div class="morph-content ">
                        <div>
                            <div class="content-style-text">
                                <span class="icon icon-close uk-close" data-id="<?php echo $post_id; ?>"></span>                                    
                                <h1><?php echo $renam_read; ?></h1>
                                <div>
                                    <ul class="uk-nav uk-nav-dropdown">
                                        <?php
                                        global $wpdb;
                                        $tt = $wpdb->prefix."readlists";
                                        $sqls = $wpdb->get_results("SELECT * FROM $tt");
                                        if ($sqls) { 
                                            foreach ($sqls as $sql) :
                                                echo "<li class='readlist_name' id='".$sql->id."'>";
                                            echo "<a id='".$post_id."*".$sql->readlist_name."*".$post_type."' class='readlist1' href='#'>".$sql->readlist_name."</a></li>";     
                                            endforeach; 
                                        } else { 
                                            echo '<li>No List</li>';
                                        }
                                        ?>
                                    </ul>
                                    <br>
                                    <span class="uk-spin"></span>
                                    <p class="uk-notice"></p>
                                    <br>
                                    <div class="uk-hidden-fields"> 
                                        <input class="uk-post-type" type="hidden" value="<?php echo $post_type; ?>" />
                                        <input class="uk-post-id" type="hidden" value="<?php echo $post_id; ?>" />
                                    </div>
                                    <?php echo do_shortcode('[create_readlist]'); ?>
                                </div>
                           </div>
                        </div>
                    </div>                      
                </div><!-- morph-button --> 
                <?php
            } ?>
        </div>
        <style type="text/css">
            .uk-close{
                float: right;
            }
        </style>
        <?php
        if (!is_user_logged_in()) 
        { ?>
            <div class="un-open-<?php echo $post_id; ?> morph-button morph-button-modal morph-button-modal-1 morph-button-fixed">
                <div class="morph-content un-morph-content-left">
                    <div>
                        <div class="content-style-text">
                            <span class="icon icon-close uk-close" data-id="<?php echo $post_id; ?>"></span>                                    
                            <?php 
                            $sc = get_option('readlist_login_form');
                            $page_types1 = get_posts("post_type=user_logins&post_status=publish");
                            if (empty($sc)) {
                                update_option('readlist_login_form' ,array('login_sc' => array($page_types1[0]->ID)));
                            }
                            $sign = get_option('readlist_signup_link');
                            $page_types2 = get_posts("post_type=user_registrations&post_status=publish");
                            if (empty($sign)) {
                                update_option('readlist_signup_link' ,array('sign_up_sc' => array($page_types2[0]->ID)));
                            }
                            $sc = get_option('readlist_login_form');
                            $sc_id = $sc['login_sc'][0];
                            echo do_shortcode('[wpfeup-login id="'.$sc_id.'"]'); ?>
                            <p><a href="#" class="add_active">Sign Up</a></p>
                        </div>
                    </div>
                </div>
                <div class="morph-content un-morph-content-right">
                    <div>
                        <div class="content-style-form content-style-form-2">
                            <span class="icon icon-close uk-close" data-id="<?php echo $post_id; ?>"></span>                                    
                            <?php 
                            $sign = get_option('readlist_signup_link');
                            $si_id = $sign['sign_up_sc'][0];
                            echo do_shortcode('[wpfeup-register type="registration" id="'.$si_id.'"]'); ?>
                        </div>
                    </div>
                </div>
            </div>
            <style type="text/css">
                .user-form-login-form div.user-form{
                    height: 194px!important;
                }
            </style>
            <?php
        } ?>
        <style type="text/css">
            .uk-read-markup {
                position: relative;
            }
            .readlist12
            {
                opacity:0.27;
            }
            .morph-button.open[class*="un-open"] {
                position: unset;
                z-index: 10;
            }
        </style>
        <?php
    }
} 
function my_the_content_filter188($content) {
    global $wpdb;
    $user_id = wp_get_current_user();
    $uid= $user_id->ID;
        // $county=count($results);
    $set = get_option( 'readlist_verify_settings'); 
    $pages = get_option('readlist_pages_settings');
    $renam_read = get_option('readlist_title');
    if ($renam_read) {
        if (array_key_exists('title', $renam_read)) 
        {
            $renam_read = $renam_read['title'];
        }
    }else{
        $renam_read = 'Readlist';
    }
    if ($set) 
    { 
        foreach($set as $post_type => $value){ 
            if ($GLOBALS['post']->post_type == $post_type && $post_type != 'product' && $post_type != 'page') 
            {
                inner_content($uid , $GLOBALS['post']->post_type);              
            }
        }
    }
    if ($pages) 
    {
        foreach ($pages as $page => $value) {
            if ($GLOBALS['post']->post_type == 'page' && $GLOBALS['post']->post_name == $page) 
            {
                inner_content($uid , $GLOBALS['post']->post_type);
            }
        }
    }
    return $content;
}
add_filter( 'the_content', 'my_the_content_filter188');
function see() {
    $oo = FRONTEND_USER()->helper->get_option( 'user-default-role', false );
    if ($oo) {
        update_option( 'default_role', $oo );
    }
    $css = get_option('readlist_styling_settings');
    $renam_read = get_option('readlist_title');
    if ($renam_read) {
        if (array_key_exists('title', $renam_read)) 
        {
            $renam_read = $renam_read['title'];
        }
    }else{
        $renam_read = 'Readlist';
    } ?>
    <style type="text/css">
        .add_wish a {
            text-decoration: none;
        }
        .caret{
            vertical-align: middle !important;
        }
        .caret {
            border-left: 4px solid rgba(0, 0, 0, 0);
            border-right: 4px solid rgba(0, 0, 0, 0);
            border-top: 4px solid #000000;
            content: "";
            display: inline-block;
            height: 0;
            vertical-align: top;
            width: 0;
        }
        <?php echo $css['style_readlist'];?>
    </style>
    <?php
}
add_action('wp_head' , 'see');
function test() {   
    global $wpdb;
    $user_id = wp_get_current_user();
    $uid= $user_id->ID;
        // $county=count($results);
    $set = get_option( 'readlist_verify_settings'); 
    $pages = get_option('readlist_pages_settings');
    $renam_read = get_option('readlist_title');
    if ($renam_read) {
        if (array_key_exists('title', $renam_read)) 
        {
            $renam_read = $renam_read['title'];
        }
    }else{
        $renam_read = 'Readlist';
    }
    if ($set) { 
        foreach($set as $post_type => $value){ 
            if ($GLOBALS['post']->post_type == $post_type && $post_type == 'product' && $post_type != 'page') 
            {
                inner_content($uid , $GLOBALS['post']->post_type);              
            }
        }
    }
}
add_action( 'woocommerce_single_product_summary', 'test', 40 );
if(isset($_GET['form_preview'])) {
    
    add_action('the_content','show_form_preview_fn');
    function show_form_preview_fn($content) {
        $id = $_GET['form_preview'];
        $p_type = get_post_type($id);
        if($p_type == 'user_forms') { 
            echo do_shortcode('[wpfeup-add-form id="'.$id.'"]');
        }
        if($p_type == 'user_logins') { 
            echo 'Form Preview is not available for Login and Registration forms.';
        }
        if($p_type == 'user_registrations') { 
            echo 'Form Preview is not available for Login and Registration forms.';
        }
    }
}
///////////////FORNTEND STYLE **********************///////////////
function frontend_form_class_attr( $form_id ) {
    $form_id = $form_id;
    $template_class = '';
    $feua_style_id  = get_post_meta( $form_id, 'feua_style_id' );
    if ( isset( $feua_style_id[0] ) ) 
    {
        $feua_style_data = get_post( $feua_style_id[0], OBJECT );
        if( has_term( 'frontend-user-style', 'style_category', $feua_style_data ) ) 
        {
            $template_class = "feua-style-" . $feua_style_id[0];
        } else 
        {
            $template_class = $feua_style_data->post_name;
        }
    }   
    // Return the modified class
    return $template_class;
}
function frontend_active_styles() 
{
    $args = array( 
        'post_type'         => array('user_forms','user_logins','user_registrations'),
        'post_status'       => 'publish',
        'posts_per_page'    => -1
        );
    $frontend_active_styles = array();
    $forms = new WP_Query( $args );
    if( $forms->have_posts() ) :
        while( $forms->have_posts() ) : $forms->the_post();
    $form_title = get_the_title();
    $id         = get_the_ID();
    $style_id = get_post_meta( $id, 'feua_style_id', true );
    if ( ! empty( $style_id ) || $style_id != 0 ) {
        $frontend_active_styles[] = $style_id;
    }
    endwhile;
    wp_reset_postdata();
    endif; 
    return $frontend_active_styles;
}
function frontend_count_element_settings( $elements, $checks ){
    $inner = 0;
    $arr = array();
    foreach ( $checks as $index => $check ) {
        $inner = 0;
        foreach ( $elements as $key => $element ) {
            if ( strpos( $key, $check ) === 0 ) {
                $arr[ $index ] = $inner++;
            }
        }
    }
    return $arr;
}
function feua_style_custom_css_generator($form_id){
    global $post;
    if( empty( $form_id ) ) {
        return false;
    }
    $args = array( 
        'post_type'         => array('user_forms','user_logins','user_registrations'),
        'p'         => $form_id,
        'post_status'       => 'publish'
        );
    $style_number = 0;
    $forms = new WP_Query( $args );
    $style = '';
    $frontend_active_styles = array();
    $total_num_posts = $forms->found_posts;
    if( $forms->have_posts() ) :
        while( $forms->have_posts() ) : $forms->the_post();
    $form_title = get_the_title();
    $id         = get_the_ID();
    $feuas_id = get_post_meta( $id, 'feua_style_id', true );
        
    if ( ( ! empty( $feuas_id ) || $feuas_id !== 0 ) && ! in_array( $feuas_id, $frontend_active_styles ) ) 
    {
        $feua_style_data = get_post( $feuas_id, OBJECT );
        $postname = $feua_style_data->post_name;
        if( !is_admin() ) {
        ?>
        <link rel="stylesheet" type="text/css" href="<?php echo plugins_url('assets/css/cs-select.css', __FILE__); ?>" />
        <script src="<?php echo plugins_url('assets/js/classie.js', __FILE__); ?>"></script>
        <script src="<?php echo plugins_url('assets/js/selectFx.js', __FILE__); ?>"></script>
        <?php
        }
        if( empty( $frontend_active_styles ) && isset($postname)) 
        {
            switch ($postname) 
            {
                case 'haruki':
                    ?>
                    <script type="text/javascript">
                        (function($) {
                            $(document).ready(function(){
                                var id  = "<?php echo $feuas_id; ?>";
                                $('.feua-style-'+id+' input:not(input[type="submit"]) ,.feua-style-'+id+' input:not(input[type="checkbox"]) ,.feua-style-'+id+' input:not(input[type="radio"]), .feua-style-'+id+' textarea').on('focus',function(){
                                var v = $(this).val();
                                if( v == '' ){
                                  var p = $(this).outerHeight()+20;
                                  $(this).closest('fieldset').find('.user-label label').css('top','-20px');
                                  $(this).css({'height':p+'px','top':'-10px'});
                                }
                                });
                                $('.feua-style-'+id+' input:not(input[type="submit"]),.feua-style-'+id+' input:not(input[type="checkbox"]) ,.feua-style-'+id+' input:not(input[type="radio"]), .feua-style-'+id+' textarea').on('blur',function(){
                                var v = $(this).val();
                                if( v == '' ){  
                                  var p = $(this).outerHeight()-20;
                                  $(this).closest('fieldset').find('.user-label label').removeAttr('style');
                                  $(this).css({'height':p+'px','top':'0px'});
                                }
                                });
                            });
                        })(jQuery);
                    </script>
                    <?php
                break;
                case 'jiro':
                    ?>
                    <script type="text/javascript">
                        (function($) {
                            $(document).ready(function(){
                                var id  = "<?php echo $feuas_id; ?>";
                                console.log(id);
                                $('.feua-style-'+id+' input:not(input[type="submit"]),.feua-style-'+id+' input:not(input[type="checkbox"]) ,.feua-style-'+id+' input:not(input[type="radio"]), .feua-style-'+id+' textarea').on('focus',function(){
                                  $(this).closest('fieldset').addClass('jiro_effect');
                                  $(this).closest('fieldset').addClass('jiro_effect_o');
                                });
                                $('.feua-style-'+id+' input:not(input[type="submit"]),.feua-style-'+id+' input:not(input[type="checkbox"]) ,.feua-style-'+id+' input:not(input[type="radio"]), .feua-style-'+id+' textarea').on('blur',function(){
                                  var $this = $(this);
                                  $this.closest('fieldset').removeClass('jiro_effect_o');
                                  setTimeout(function(){
                                    $this.closest('fieldset').removeClass('jiro_effect');
                                  },600);
                                });
                            });
                        })(jQuery);
                    </script>
                    <style type="text/css">
                    body .feua-style.feua-style-<?php echo $feuas_id; ?> .jiro_effect label {
                        top: -60px;
                        transition-delay:0.3s;
                    }
                    body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.jiro_effect:after {
                        bottom: 100%;
                    }
                    body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.jiro_effect_o:before {
                        height: 100%;
                    }
                    /*body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.jiro_effect_o:after {
                        top: 0;
                        height: 100%;
                    }*/
                    </style>
                    <?php
                break;
                case 'hoshi':
                    ?>
                    <script type="text/javascript">
                        (function($) {
                            $(document).ready(function(){
                                var id  = "<?php echo $feuas_id; ?>";
                                $('.feua-style-'+id+' input:not(input[type="submit"]),.feua-style-'+id+' input:not(input[type="checkbox"]) ,.feua-style-'+id+' input:not(input[type="radio"]), .feua-style-'+id+' textarea').on('focus',function(){
                                  var v = $(this).val();
                                    if( v == '' ){  
                                      var p = $(this).outerHeight()+20;
                                      $(this).closest('fieldset').addClass('hoshi_effect');
                                    }
                                });
                                $('.feua-style-'+id+' input:not(input[type="submit"]),.feua-style-'+id+' input:not(input[type="checkbox"]) ,.feua-style-'+id+' input:not(input[type="radio"]), .feua-style-'+id+' textarea').on('blur',function(){
                                var v = $(this).val();  
                                if( v == '' ) {     
                                  var p = $(this).outerHeight()-20;
                                  $(this).closest('fieldset').removeClass('hoshi_effect');
                                }
                                });
                            });
                        })(jQuery);
                    </script>
                    <style>
                    body .feua-style.feua-style-<?php echo $feuas_id; ?> .hoshi_effect label {
                        top: 0;
                    }
                    body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.hoshi_effect:after {
                        width: 100%;
                    }
                    </style>
                    <?php
                break;
                case 'kuro':
                    ?>
                    <script type="text/javascript">
                        (function($) {
                            $(document).ready(function(){
                                var id  = "<?php echo $feuas_id; ?>";
                                $('.feua-style-'+id+' input:not(input[type="submit"]),.feua-style-'+id+' input:not(input[type="checkbox"]) ,.feua-style-'+id+' input:not(input[type="radio"]), .feua-style-'+id+' textarea').on('focus',function(){
                                  var v = $(this).val();
                                    if( v == '' ){  
                                      var p = $(this).outerHeight()+20;
                                      $(this).closest('fieldset').addClass('kuro_effect');
                                    }
                                });
                                $('.feua-style-'+id+' input:not(input[type="submit"]),.feua-style-'+id+' input:not(input[type="checkbox"]) ,.feua-style-'+id+' input:not(input[type="radio"]), .feua-style-'+id+' textarea').on('blur',function(){
                                var v = $(this).val();  
                                if( v == '' ) {     
                                  var p = $(this).outerHeight()-20;
                                  $(this).closest('fieldset').removeClass('kuro_effect');
                                }
                                });
                            });
                        })(jQuery);
                    </script>
                    <style>
                    body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset input,
                    body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset textarea {
                        z-index: 9;
                    }
                    body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.kuro_effect:after {
                        left: -15px
                    }
                    body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.kuro_effect:before {
                        right: -15px
                    }
                    body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset label {
                        text-align: center;
                    }
                    body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.kuro_effect label {
                        top: 100%;
                    }
                    </style>
                    <?php   
                break;
                case 'kyo':
                 ?>
                 <script type="text/javascript">
                  (function($) {
                   $(document).ready(function(){
                    var id  = "<?php echo $feuas_id; ?>";
                    $('.feua-style-'+id+' input:not(input[type="submit"]),.feua-style-'+id+' input:not(input[type="checkbox"]) ,.feua-style-'+id+' input:not(input[type="radio"]), .feua-style-'+id+' textarea').on('focus',function(){
                      $(this).closest('fieldset').addClass('kyo_effect');
                    });
                    $('.feua-style-'+id+' input:not(input[type="submit"]),.feua-style-'+id+' input:not(input[type="checkbox"]) ,.feua-style-'+id+' input:not(input[type="radio"]), .feua-style-'+id+' textarea').on('blur',function(){
                     $(this).closest('fieldset').removeClass('kyo_effect');
                    });
                   });
                  })(jQuery);
                 </script>
                 <style type="text/css">
                 body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.kyo_effect:after {
                    width: 100%;
                    opacity: 1;
                 }
                 body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.kyo_effect label {
                    color: #ffffff;
                    position: relative;
                    z-index: 9999;
                 }
                 </style>
                 <?php
                break;
                case 'minor':
                    ?>
                    <script type="text/javascript">
                      (function($) {
                       $(document).ready(function(){
                        var id  = "<?php echo $feuas_id; ?>";
                        $('.feua-style-'+id+' input:not(input[type="submit"]), .feua-style-'+id+' input:not(input[type="checkbox"]) ,.feua-style-'+id+' input:not(input[type="radio"]),.feua-style-'+id+' textarea').on('focus',function(){
                          $(this).closest('fieldset').addClass('minoru_effect');
                        });
                        $('.feua-style-'+id+' input:not(input[type="submit"]),.feua-style-'+id+' input:not(input[type="checkbox"]) ,.feua-style-'+id+' input:not(input[type="radio"]),  .feua-style-'+id+' textarea').on('blur',function(){
                         $(this).closest('fieldset').removeClass('minoru_effect');
                        });
                       });
                      })(jQuery);
                    </script>
                        <style type="text/css">
                            @-webkit-keyframes anim-shadow {
                                to {
                                    box-shadow: 0px 0px 100px 50px;
                                    opacity: 0;
                                }
                            }
                            @keyframes anim-shadow {
                                to {
                                    box-shadow: 0px 0px 100px 50px;
                                    opacity: 0;
                                }
                            }
                            body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.minoru_effect:after {
                                -webkit-animation: 0.3s ease 0s normal forwards 1 running anim-shadow;
                                animation: 0.3s ease 0s normal forwards 1 running anim-shadow;
                                z-index: 1;
                            }
                        </style>
                        <?php
                break;
                case 'yoko':
                        ?>
                        <script type="text/javascript">
                          (function($) {
                           $(document).ready(function(){
                            var id  = "<?php echo $feuas_id; ?>";
                            $('.feua-style-'+id+' input:not(input[type="submit"]),.feua-style-'+id+' input:not(input[type="checkbox"]) ,.feua-style-'+id+' input:not(input[type="radio"]), .feua-style-'+id+' textarea').on('focus',function(){
                              $(this).closest('fieldset').addClass('yoko_effect');
                            });
                            $('.feua-style-'+id+' input:not(input[type="submit"]), .feua-style-'+id+' input:not(input[type="checkbox"]) ,.feua-style-'+id+' input:not(input[type="radio"]), .feua-style-'+id+' textarea').on('blur',function(){
                             $(this).closest('fieldset').removeClass('yoko_effect');
                            });
                           });
                          })(jQuery);
                        </script>
                        <style type="text/css">
                            body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.yoko_effect:before {
                                transform :perspective(1000px) rotate3d(1, 0, 0, 0deg);
                            }
                            body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.yoko_effect:after {
                                transform :perspective(1000px) rotate3d(1, 0, 0, -90deg);
                            }
                        </style>
                        <?php
                break;
                case 'akira':
                        ?>
                        <script type="text/javascript">
                            (function($) {
                                $(document).ready(function(){
                                    var id  = "<?php echo $feuas_id; ?>";
                                    $('.feua-style-'+id+' input:not(input[type="submit"]), .feua-style-'+id+' input:not(input[type="checkbox"]) ,.feua-style-'+id+' input:not(input[type="radio"]), .feua-style-'+id+' textarea').on('focus',function(){
                                      var v = $(this).val();
                                        if( v == '' ){  
                                          $(this).closest('fieldset').addClass('akira_effect');
                                        }
                                    });
                                    $('.feua-style-'+id+' input:not(input[type="submit"]), .feua-style-'+id+' input:not(input[type="checkbox"]) ,.feua-style-'+id+' input:not(input[type="radio"]), .feua-style-'+id+' textarea').on('blur',function(){
                                    var v = $(this).val();  
                                    if( v == '' ) {     
                                      $(this).closest('fieldset').removeClass('akira_effect');
                                    }
                                    });
                                });
                            })(jQuery);
                        </script>
                        <style type="text/css">
                            body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.akira_effect label {
                                top: -30px
                            }
                            body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.akira_effect:after {
                                border-width: 2px;
                            }
                            
                        </style>
                        <?php
                break;
                case 'isao':
                        ?>
                        <script type="text/javascript">
                            (function($) {
                                $(document).ready(function(){
                                    var id  = "<?php echo $feuas_id; ?>";
                                    $('.feua-style-'+id+' fieldset').each(function(){ 
                                        var el = $(this).find('.user-label label').clone();
                                        $(this).find('.user-label').prepend(el);
                                    });
                                    $('.feua-style-'+id+' input:not(input[type="submit"]), .feua-style-'+id+' input:not(input[type="checkbox"]) ,.feua-style-'+id+' input:not(input[type="radio"]), .feua-style-'+id+' textarea').on('focus',function(){
                                      var v = $(this).val();
                                        if( v == '' ){  
                                          $(this).closest('fieldset').addClass('isao_effect');
                                        }
                                    });
                                    $('.feua-style-'+id+' input:not(input[type="submit"]), .feua-style-'+id+' input:not(input[type="checkbox"]) ,.feua-style-'+id+' input:not(input[type="radio"]), .feua-style-'+id+' textarea').on('blur',function(){
                                    var v = $(this).val();  
                                    if( v == '' ) {     
                                      $(this).closest('fieldset').removeClass('isao_effect');
                                    }
                                    });
                                });
                            })(jQuery);
                        </script>
                        <style type="text/css">
                        body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset label {
                            transition-delay: 0s, 0s;
                            transition-duration: 0.3s, 0.3s;
                            transition-property: opacity, transform;
                            transition-timing-function: cubic-bezier(0.2, 1, 0.3, 1);
                        }
                        body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.isao_effect:before {
                            height: 8px;
                        }
                        body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.isao_effect label {
                            transform:translate3d(0px, -50%, 0px);
                            opacity: 0;
                        }
                        body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset label + label {
                            bottom: -40px;
                            color: #da7071;
                            opacity: 0;
                        }
                        body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.isao_effect label + label {
                            opacity: 1;
                        }
                        </style>
                        <?php
                break;
                case 'ichiro':
                        ?>
                        <script type="text/javascript">
                            (function($) {
                                $(document).ready(function(){
                                    var id  = "<?php echo $feuas_id; ?>";
                                    $('.feua-style-'+id+' fieldset').on('click',function(){ 
                                        $(this).find('.user-fields > *').focus();
                                    });
                                    $('.feua-style-'+id+' input:not(input[type="submit"]), .feua-style-'+id+' input:not(input[type="checkbox"]) ,.feua-style-'+id+' input:not(input[type="radio"]), .feua-style-'+id+' textarea').on('focus',function(){
                                      var v = $(this).val();
                                        if( v == '' ){  
                                          $(this).closest('fieldset').addClass('ichiro_effect');
                                        }
                                    });
                                    $('.feua-style-'+id+' input:not(input[type="submit"]), .feua-style-'+id+' input:not(input[type="checkbox"]) ,.feua-style-'+id+' input:not(input[type="radio"]), .feua-style-'+id+' textarea').on('blur',function(){
                                    var v = $(this).val();  
                                    if( v == '' ) {     
                                      $(this).closest('fieldset').removeClass('ichiro_effect');
                                    }
                                    });
                                });
                            })(jQuery);
                        </script>
                        <style type="text/css">
                            body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset * {
                                cursor: text;
                            }
                            body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset label {
                                display: block;
                            }
                            body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.ichiro_effect label {
                                padding: 5px 10px 12px;
                                font-size: 15px;
                            }
                            body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.ichiro_effect .user-fields > * {
                                height: 70px;
                                padding: 15px;
                            }
                            
                        </style>
                        <?php
                break;
                case 'juro':
                    ?>
                    <script type="text/javascript">
                        (function($) {
                            $(document).ready(function(){
                                var id  = "<?php echo $feuas_id; ?>";
                                $('.feua-style-'+id+' fieldset').on('click',function(){ 
                                    $(this).find('.user-fields > *').focus();
                                });
                                $('.feua-style-'+id+' input:not(input[type="submit"]),.feua-style-'+id+' input:not(input[type="checkbox"]) ,.feua-style-'+id+' input:not(input[type="radio"]), .feua-style-'+id+' input:not(input[type="checkbox"]) ,.feua-style-'+id+' input:not(input[type="radio"]), .feua-style-'+id+' textarea').on('focus',function(){
                                  var v = $(this).val();
                                    if( v == '' ){  
                                      $(this).closest('fieldset').addClass('juro_effect');
                                    }
                                });
                                $('.feua-style-'+id+' input:not(input[type="submit"]), .feua-style-'+id+' input:not(input[type="checkbox"]) ,.feua-style-'+id+' input:not(input[type="radio"]), .feua-style-'+id+' textarea').on('blur',function(){
                                var v = $(this).val();  
                                if( v == '' ) {     
                                  $(this).closest('fieldset').removeClass('juro_effect');
                                }
                                });
                            });
                        })(jQuery);
                    </script>
                    <style type="text/css">
                    body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.juro_effect label {
                        top: 9px;
                        left: 10px;
                        font-size: 12px;
                        color: #ffffff;
                    }
                    body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.juro_effect {
                        padding: 32px 8px 8px 8px;
                    }
                    body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.juro_effect input:not([type="submit"]),
                    body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.juro_effect textarea {
                        padding: 10px;
                    }
                    </style>
                    <?php
                break;
                case 'manami':
                    ?>
                    <script type="text/javascript">
                        (function($) {
                            $(document).ready(function(){
                                var id  = "<?php echo $feuas_id; ?>";
                                $('.feua-style-'+id+' fieldset').on('click',function(){ 
                                    $(this).find('.user-fields > *').focus();
                                });
                                $('.feua-style-'+id+' input:not(input[type="submit"]), .feua-style-'+id+' input:not(input[type="checkbox"]) ,.feua-style-'+id+' input:not(input[type="radio"]), .feua-style-'+id+' textarea').on('focus',function(){
                                  var v = $(this).val();
                                    if( v == '' ){  
                                      $(this).closest('fieldset').addClass('manami_effect');
                                    }
                                });
                                $('.feua-style-'+id+' input:not(input[type="submit"]), .feua-style-'+id+' input:not(input[type="checkbox"]) ,.feua-style-'+id+' input:not(input[type="radio"]), .feua-style-'+id+' textarea').on('blur',function(){
                                var v = $(this).val();  
                                if( v == '' ) {     
                                  $(this).closest('fieldset').removeClass('manami_effect');
                                }
                                });
                            });
                        })(jQuery);
                    </script>
                    <style type="text/css">
                    body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.manami_effect label {
                        top: 9px;
                        left: 10px;
                        font-size: 12px;
                        color: #ffffff;
                    }
                    body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.manami_effect {
                        padding: 32px 8px 8px 8px;
                    }
                    body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.manami_effect input:not([type="submit"]),
                    body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.manami_effect textarea {
                        padding: 10px;
                    }
                    </style>
                    <?php
                break;
                case 'madoka':
                    ?>
                    <script type="text/javascript">
                        (function($) {
                            $(document).ready(function(){
                                var id  = "<?php echo $feuas_id; ?>";
                                $('.feua-style-'+id+' fieldset').each(function(){ 
                                    var el = '<label></label>';
                                    $(this).find('.user-label label').after(el);
                                });
                                $('.feua-style-'+id+' fieldset').on('click',function(){ 
                                    $(this).find('.user-fields > *').focus();
                                });
                                $('.feua-style-'+id+' input:not(input[type="submit"]), .feua-style-'+id+' input:not(input[type="checkbox"]) ,.feua-style-'+id+' input:not(input[type="radio"]), .feua-style-'+id+' textarea').on('focus',function(){
                                  var v = $(this).val();
                                  $this = $(this);
                                    if( v == '' ){  
                                      $(this).closest('fieldset').addClass('madoka_effect');
                                      setTimeout(function(){
                                        $this.closest('fieldset').addClass('madoka_effect1');
                                      },300);
                                    }
                                });
                                $('.feua-style-'+id+' input:not(input[type="submit"]), .feua-style-'+id+' input:not(input[type="checkbox"]) ,.feua-style-'+id+' input:not(input[type="radio"]), .feua-style-'+id+' textarea').on('blur',function(){
                                var v = $(this).val();  
                                var $this = $(this);
                                if( v == '' ) {     
                                  $(this).closest('fieldset').removeClass('madoka_effect');
                                  setTimeout(function(){
                                    $this.closest('fieldset').removeClass('madoka_effect1');
                                  },400);
                                }
                                });
                            });
                        })(jQuery);
                    </script>
                    <style type="text/css">
                    body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.madoka_effect label {
                        bottom: -30px;
                        font-size: 14px;
                    }
                    body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.madoka_effect:after {
                        height:100%;
                    }
                    body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.madoka_effect:before {
                        width:100%;
                    }
                    body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset label + label {
                        bottom: auto;
                        height: 100%;
                        left: 0;
                        top: 0;
                    }
                    body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.madoka_effect label + label:after {
                        height:100%;
                    }
                    body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.madoka_effect1 label + label:after {
                        -webkit-transition:all 0.1s ease 0.1s;
                        -moz-transition:all 0.1s ease 0.1s;
                    }
                    body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.madoka_effect1:before {
                        -webkit-transition:all 0.1s ease 0.2s;
                        -moz-transition:all 0.1s ease 0.2s;
                    }
                    body .feua-style.feua-style-<?php echo $feuas_id; ?> fieldset.madoka_effect1:after {
                        -webkit-transition:all 0.1s ease 0.3s;
                        -moz-transition:all 0.1s ease 0.3s;
                    }
                    </style>
                    <?php
                break;
                case 'sae':
                    ?>
                    <script type="text/javascript">
                        (function($) {
                            $(document).ready(function(){                               
                                var id  = "<?php echo $feuas_id; ?>";
                                $('.feua-style-'+id+' input:not(input[type="submit"]), .feua-style-'+id+' input:not(input[type="checkbox"]) ,.feua-style-'+id+' input:not(input[type="radio"]), .feua-style-'+id+' textarea').on('focus', function(){
                                    $(this).closest( "fieldset" ).toggleClass( "active_sae" );                                
                                });
                                $('.feua-style-'+id+' input:not(input[type="submit"]), .feua-style-'+id+' input:not(input[type="checkbox"]) ,.feua-style-'+id+' input:not(input[type="radio"]), .feua-style-'+id+' textarea').on('focusout', function(){
                                    $( "fieldset" ).removeClass( "active_sae" );                                  
                                });
                            });
                        })(jQuery);
                    </script> <?php
                break;
                case 'ruri':
                    ?>
                    <script type="text/javascript">
                        (function($) {
                            $(document).ready(function(){                               
                                var id  = "<?php echo $feuas_id; ?>";
                                $('.feua-style-'+id+' input:not(input[type="submit"]), .feua-style-'+id+' input:not(input[type="checkbox"]) ,.feua-style-'+id+' input:not(input[type="radio"]), .feua-style-'+id+' textarea').on('focus', function(){
                                    $(this).closest( "fieldset" ).toggleClass( "active_ruri" );                               
                                });
                                $('.feua-style-'+id+' input:not(input[type="submit"]), .feua-style-'+id+' input:not(input[type="checkbox"]) ,.feua-style-'+id+' input:not(input[type="radio"]), .feua-style-'+id+' textarea').on('focusout', function(){
                                    $( "fieldset" ).removeClass( "active_ruri" );                                 
                                });
                            });
                        })(jQuery);
                    </script> <?php
                break;
                default:
                break;
            }
        }   
    }
    if ( ( ! empty( $feuas_id ) || $feuas_id !== 0 ) && ! in_array( $feuas_id, $frontend_active_styles ) ) 
    {
        if( empty( $frontend_active_styles ) ) 
        {
            $style .= "\n<style class='feua-style' media='screen' type='text/css'>\n";
        }   
        array_push( $frontend_active_styles, $feuas_id );
        $feua_style_data = get_post( $feuas_id, OBJECT );   
        if( has_term( 'frontend-user-style', 'style_category', $feua_style_data ) ) 
        {
            $feuas_slug =  $feuas_id;
        } else 
        {
            $feuas_slug = sanitize_title( get_the_title( $feuas_id ) );
        }
        // echo "////".$feuas_slug."</br>";
        $custom_cat             = get_the_terms( $feuas_id, "style_category" );
        $custom_cat_name        = ( !empty( $custom_cat ) ) ? $custom_cat[ 0 ]->name : "";
        $feuas_manual_style     = get_post_meta( $feuas_id, 'feua_style_manual_style', true );
        if (  $custom_cat_name == "Frontend User Style" ) 
        {
            $feuas_custom_settings = unserialize( get_post_meta( $feuas_id, 'feua_style_custom_styles', true ) );
            $temp       = 0; 
            $temp_1     = 0;
            $temp_2     = 0; 
            $temp_3     = 0; 
            $temp_4     = 0;
            $temp_5     = 0;
            $temp_6     = 0;
            $temp_7     = 0;
            $form_set_nr = frontend_count_element_settings( $feuas_custom_settings, array( "form", "input", "label", "submit", "textarea", "dropdown", "fieldset" ) );
            foreach( $feuas_custom_settings as $setting_key => $setting ) 
            {
                $setting_key_part   = explode( "-", $setting_key );
                $second_part        = ( $setting_key_part[0] != "submit" ) ? $setting_key_part[1] : "";
                $third_part         = ( !empty( $setting_key_part[2] ) ) ? ( ( $setting_key_part[0] != "submit" ) ? "-" : "" ) . $setting_key_part[2] : "";
                $fourth_part        = ( !empty( $setting_key_part[3] ) && $setting_key_part[0] == "submit" ) ? "-" . $setting_key_part[3] : "";
                $classelem = "body .feua-style." . ( ( is_numeric( $feuas_slug ) ) ? "feua-style-".$feuas_slug : $feuas_slug );
                if( $setting_key == 'dropdown-styles' && $setting !='' ) {
                    echo '<link rel="stylesheet" type="text/css" href="'.plugins_url("assets/css/cs-skin-".strtolower($setting).".css", __FILE__).'" />';
                    echo '<script>
                        (function($) {
                            $(document).ready(function(){
                                var id  = "'.$feuas_id.'";
                                $(".feua-style-'.$feuas_id.' select").addClass("cs-select cs-skin-'.strtolower($setting).'");
                                (function() {
                                    [].slice.call( document.querySelectorAll( ".feua-style-'.$feuas_id.' select" ) ).forEach( function(el) {    
                                        new SelectFx(el);
                                    } );
                                })();
                            });
                        })(jQuery);
                    </script>';
                }
                if( $setting_key == 'submit-styles' ) {
                    echo '<link rel="stylesheet" type="text/css" href="'.plugins_url("assets/css/component.css", __FILE__).'" />';
                    echo '<script>
                        (function($) {
                            $(document).ready(function(){
                                var id  = "'.$feuas_id.'";
                                var sb = "submit";
                                $(".feua-style-'.$feuas_id.' input[type="+sb+"]").addClass("'.strtolower($setting).'");
                            });
                        })(jQuery);
                    </script>';
                }
                if( $setting_key == 'fileupload-styles' ) {
                    echo '<link rel="stylesheet" type="text/css" href="'.plugins_url("assets/css/component1.css", __FILE__).'" />'; ?>
                    <script>
                        (function($) {
                            $(document).ready(function(){
                                var id  = "<?php echo $setting; ?>";
                                var html_r = '<label for="file-6"><figure><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg></figure><span>Choose a file&hellip;</span></label>';
                                $(document).find('ul.user-form fieldset[class*="image_upload"] .user-fields .user-attachment-upload-filelist a.file-selector').addClass(id);
                                $(document).find('ul.user-form fieldset[class*="image_upload"] .user-fields .user-attachment-upload-filelist a.file-selector').after(html_r);
                                $(document).find('ul.user-form fieldset[class*="file_upload"] table tr td:nth-child(2) a').addClass(id);
                                $(document).find('ul.user-form fieldset[class*="file_upload"] table tr td:nth-child(2) a').after(html_r);
                            });
                        })(jQuery);
                    </script>
                <?php   
                }
                ////////////****************************////////////////////
                switch ( $setting_key_part[ 0 ] ) 
                {
                
                    case 'form':
                    $startelem  = $temp;
                    $allelem    = $form_set_nr[ 0 ];
                    $temp++;
                    break;
                    case 'input':
                    $startelem  = $temp_1;
                    $allelem    = $form_set_nr[ 1 ];
                    $classelem  .= " input:not([type='submit']),\n".$classelem." textarea";
                    $temp_1++;
                    break;
                    case 'fieldset':
                    $startelem  = $temp_6;
                    $allelem    = $form_set_nr[ 6 ];
                    $classelem  .= " fieldset:not(.user-submit)";
                    $temp_6++;
                    break;
                    case 'textarea':
                    $startelem  = $temp_2;
                    $allelem    = $form_set_nr[ 4 ];
                    $classelem  .= " textarea";
                    $temp_2++;
                    break;  
                    case 'dropdown':
                    $startelem  = $temp_3;
                    $allelem    = $form_set_nr[ 5 ];
                    $classelem  .= " select";
                    $temp_3++;
                    break;      
                    case 'label':
                    $startelem  = $temp_4;
                    $allelem    = $form_set_nr[ 2 ];    
                    $classelem  .= " label";
                    $temp_4++;            
                    break;
                    case 'submit':
                    $startelem  = $temp_5;
                    $allelem    = $form_set_nr[ 3 ];
                    $classelem  .= " input[type='submit']"; 
                    $temp_5++;
                    break;
                    default:
                    break;
                }
                if($second_part != 'after' && $second_part != 'before' && $second_part != 'hover' && $second_part != 'focus') {
                    $style .= ( $startelem == 0 ) ? $classelem . " {\n" : "";
                    
                    if ( $setting != "" && $setting != "Default" && ( $second_part != 'box' && $third_part != 'box'  ) && ( $second_part != 'line' || $third_part != 'line'  ) ) 
                    {
                        $style .= "\t" . $second_part . $third_part . $fourth_part . ": ";
                        if (   !is_numeric( $setting  ) && $setting !== '' ) {
                            $style .= $setting;
                        } else {
                            if( $second_part.$third_part.$fourth_part == 'z-index' ) {
                                $style .= $setting;     
                            }
                            else {
                                $style .= $setting . "px"; 
                            }
                        }
                        $style .= ";\n";
                    } 
                    if( $second_part == 'line' && $setting == "" ) {
                        $style .= "\t" . $second_part . $third_part . ": normal;\n"; 
                    }
                    if ( $third_part == "line" && $setting == "" ) {
                        $style .= "\t" . $third_part . $fourth_part . ": normal;\n"; 
                    }
                    if( ( $second_part == 'box' || $third_part == 'box' ) && $setting != "Default" ) {
                        $style .= "\t -moz-" . $second_part . $third_part . ": ". $setting . ";\n";
                        $style .= "\t -webkit-" . $second_part . $third_part . ": ". $setting . ";\n";
                        $style .= "\t" . $second_part . $third_part . ": ". $setting . ";\n"; 
                    }
                    $style .= ( $startelem == $allelem || $allelem == 1 ) ? "}\n" : "";
                }
            }
        }
        $font_family = frontend_return_font_name( $feuas_id );
        if( ! empty( $font_family ) && "none" !== $font_family ) {
            if (is_numeric($feuas_slug)) {
                $feuas_slug = "feua-style-".$feuas_slug;
            }
            $style .= 'html body .feua-style.' . $feuas_slug . ',html body .feua-style.'  . $feuas_slug . " input[type='submit'] {\n\t font-family: '" . $font_family . "',sans-serif;\n} ";
        }
        if( !empty( $feuas_manual_style ) ){
            $style.= "\n".$feuas_manual_style."\n";
        }
        $style_number++;
    }
    if( ( $style_number !== 0 ) && $style_number == count( $frontend_active_styles ) ) {
        $style .= "\n</style>\n";
    }       
    echo $style;
    $style1 = '';
    $style1 .= "\n<style class='feua-style' media='screen' type='text/css'>\n";
        array_push( $frontend_active_styles, $feuas_id );
        $feua_style_data = get_post( $feuas_id, OBJECT );   
        if( has_term( 'frontend-user-style', 'style_category', $feua_style_data ) ) 
        {
            $feuas_slug =  $feuas_id;
        } else 
        {
            $feuas_slug = sanitize_title( get_the_title( $feuas_id ) );
        }
        $custom_cat             = get_the_terms( $feuas_id, "style_category" );
        $custom_cat_name        = ( !empty( $custom_cat ) ) ? $custom_cat[ 0 ]->name : "";
        $feuas_manual_style     = get_post_meta( $feuas_id, 'feua_style_manual_style', true );
        if (  $custom_cat_name == "Frontend User Style" ) 
        {
            $feuas_custom_settings = unserialize( get_post_meta( $feuas_id, 'feua_style_custom_styles', true ) );
            $form_set_nr = frontend_count_element_settings( $feuas_custom_settings, array( "form", "input", "label", "submit", "textarea", "dropdown", "fieldset" ) );
            foreach( $feuas_custom_settings as $setting_key => $setting ) 
            {
                $setting_key_part   = explode( "-", $setting_key );
                $second_part        = ( $setting_key_part[0] != "submit" ) ? $setting_key_part[1] : "";
                $third_part         = ( !empty( $setting_key_part[2] ) ) ? ( ( $setting_key_part[0] != "submit" ) ? "-" : "" ) . $setting_key_part[2] : "";
                $fourth_part        = ( !empty( $setting_key_part[3] ) && $setting_key_part[0] == "submit" ) ? "-" . $setting_key_part[3] : "";
                $classelem1 = "body .feua-style." . ( ( is_numeric( $feuas_slug ) ) ? "feua-style-".$feuas_slug : $feuas_slug );
                if( $second_part == 'after' || $second_part == 'before' || $second_part == 'hover' || $second_part == 'focus') 
                {
                    $classelem1 .= ' '.$setting_key_part[ 0 ].':'.$second_part; 
                    $style1 .= $classelem1. " {\n";
                    $style1 .= stripslashes($setting);
                    $style1 .= "}\n";
                }
                
            }
        }
        $style1 .= "\n</style>\n";
        echo $style1;
        
    endwhile;
    wp_reset_postdata();
    endif;              
}// end of feua_style_custom_css_generator
//include_once( 'feua-style-settings.php' );
function feua_style_admin_scripts(){
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( "feua_style_codemirror_js", plugin_dir_url( __FILE__ ) . "assets/js/codemirror.js", array( 'jquery' ), false, true );
    wp_enqueue_style( "feua-style-codemirror-style", plugin_dir_url( __FILE__ ) . "assets/css/codemirror.css", false, set_feuastyleversion(), "all" );
    wp_enqueue_script( "feua-style-codemirror-mode", plugin_dir_url( __FILE__ ) . "assets/js/mode/css/css.js",  array( 'jquery' ), false, true );
    wp_enqueue_style( "feua-style-admin-style", plugin_dir_url( __FILE__ ) . "assets/css/admin.css", false, "1.0", "all");  
    wp_enqueue_script( "feua_style_admin_js", plugin_dir_url( __FILE__ ) . "assets/js/admin.js", array( 'wp-color-picker' ), false, true );
}
function feup_style_add_class( $form_id ){
    
    global $post;
    $class = " feua-style ".frontend_form_class_attr( $form_id );
    // return $class;
    // $class = 'ravi';
    echo $class;
}// end of feua_style_add_class
/**
 *  Check if Contact Form 7 is activated
 */
function feua_style_create_post( $slug, $title, $default_css, $custom_css) {
    // Initialize the page ID to -1. This indicates no action has been taken.
    $post_id = -1;
    // If the page doesn't already exist, then create it
    if( null == get_page_by_title( $title, "OBJECT", "feua_style" ) ) {
    // Set the post ID so that we know the post was created successfully
        $post_id = wp_insert_post(
            array(
                'comment_status'    => 'closed',
                'ping_status'           => 'closed',
                'post_name'         => $slug,
                'post_title'            => $title,
                'post_status'           => 'publish',
                'post_type'         => 'feua_style'
                )
            );
        //if is_wp_error doesn't trigger, then we add the image
        if ( is_wp_error( $post_id ) ) {
            $errors = $post_id->get_error_messages();
            foreach ($errors as $error) {
                echo $error . '<br>'; 
            }
        } else {
            //wp_set_object_terms( $post_id, $category, 'style_category', false );
                 if ($default_css != '') {
                // update_post_meta( $post_id, 'feua_style_custom_styles', $default_css );
                    global $wpdb;
                    $tbl = $wpdb->prefix.'postmeta';
                    $wpdb->insert( 
                        $tbl, 
                        array( 
                            'post_id' => $post_id,
                            'meta_key' => 'feua_style_custom_styles',
                            'meta_value' => $default_css 
                        )
                    );
            }
            if ($custom_css != '') {
                update_post_meta( $post_id, 'feua_style_manual_style', $custom_css );
            }
            
        }
    // Otherwise, we'll stop
    } else {
    // Arbitrarily use -2 to indicate that the page with the title already exists
        $post_id = -2;
    } // end if
} // end feua_style_create_post
function feua_style_add_taxonomy_filters() {
    global $typenow;
    // an array of all the taxonomyies you want to display. Use the taxonomy name or slug
    $taxonomies = array( 'style_category' );
    // must set this to the post type you want the filter(s) displayed on
    if( $typenow == 'feua_style' ){
        foreach ( $taxonomies as $tax_slug ) {
            $tax_obj = get_taxonomy( $tax_slug );
            
            $tax_name = $tax_obj->labels->name;
            $terms = get_terms( $tax_slug );
            if( count( $terms ) > 0 ) {
                echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
                echo "<option value=''>Show All $tax_name</option>";
                foreach ( $terms as $term ) {
                    $resultA = "<option value='".$term->slug."' selected='selected'>".$term->name .' (' . $term->count .')'."</option>";
                    $resultB = "<option value='".$term->slug."'>".$term->name .' (' . $term->count .')'."</option>";
                    echo ( isset( $_GET[$tax_slug] ) ) ? ( ( $_GET[$tax_slug] == $term->slug ) ? $resultA : $resultB ) : $resultB;
                }
                echo "</select>";
            }
        }
    }
}// end feua_style_add_taxonomy_filters
function feua_style_set_style_category_on_publish(  $ID, $post ) {
    $temporizator = 0;
    foreach ( get_predefined_feua_style_template_data() as $predefined_post_titles ) {
        if( $post->post_title == $predefined_post_titles[ "title" ] ){
            $temporizator++;
        }   
    }
    if( 0 == $temporizator ) {
        wp_set_object_terms( $ID, 'Frontend User Style', 'style_category' );
    }
} // end feua_style_set_style_category_on_publish
// require_once( 'feua-style-meta-box.php' );
/***************************************frontend_meta_box*******************/
function feua_style_general_settings_array(){
    return array(
        "general_settings" => 
        array(
            array(
                "type"      => "color-selector",
                "label"         => "Form background",
                "description"   => "Choose the background color of the form"
                ),
            array(
                "type"      => "text",
                "label"         => "Form width",
                "description"   => "Form width in pixels"
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Form box sizing",
                "value"         => array( "inherit", "initial", "content-box", "border-box" ),
                "description"   => "Form box sizing in pixels"
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Form Position",
                "value"         => array( "inherit", "Static", "Absolute", "Relative", "Fixed" ),
                "description"   => "Form box Position"
                ),
            array(
                "type"      => "text",
                "label"         => "Form top",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px  top is 25px.Ex 2: 0px  top is 0px "
                ),
            array(
                "type"      => "text",
                "label"         => "Form bottom",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px  bottom is 25px.Ex 2: 0px  bottom is 0px "
                ),
            array(
                "type"      => "text",
                "label"         => "Form left",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px  left is 25px.Ex 2: 0px  left is 0px "
                ),
            array(
                "type"      => "text",
                "label"         => "Form right",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px  right is 25px.Ex 2: 0px  right is 0px "
                ),
            array(
                "type"      => "number",
                "label"         => "Form border width",
                "description"   => "Form border width in pixels"
                ),
            array(
                "type"      => "text",
                "label"         => "Form transition",
                "description"   => "Transition Effects"
                ),
            array(
                "type"      => "textarea",
                "label"         => "Form hover",
                "description"   => "Add css in label hover"
                ),
            array(
                "type"      => "textarea",
                "label"         => "Form focus",
                "description"   => "Add css in label focus"
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Form border style",
                "value"         => array( "none", "solid", "dotted","double", "groove", "ridge", "inset", "outset" ),
                "description"   => "Style of the Border of the Form"    
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Form text align",
                "value"         => array( "none", "left", "right","center"),
                "description"   => "Style of the text Alignment in the Form"    
                ),
            array(
                "type"      => "text",
                "label"         => "Form z-index",
                "description"   => "Form z-index"
                ),
            array(
                "type"      => "text",
                "label"         => "Form padding",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px 50px 75px 100px;  top padding is 25px right padding is 50px bottom padding is 75px left padding is 100px   Ex 2: 25px 50px 75px;  top padding is 25px right and left paddings are 50px bottom padding is 75px   Ex 3: 25px 50px;  top and bottom paddings are 25px right and left paddings are 50px   Ex 4: 25px;  all four paddings are 25px  "
                ),
            array(
                "type"      => "text",
                "label"         => "Form margin",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px 50px 75px 100px;  top margin is 25px right margin is 50px bottom margin is 75px left margin is 100px   Ex 2: 25px 50px 75px;  top margin is 25px right and left margin are 50px bottom margin is 75px   Ex 3: 25px 50px;  top and bottom margin are 25px right and left margin are 50px   Ex 4: 25px;  all four margin are 25px"
                ),
            array(
                "type"      => "color-selector",
                "label"         => "Form border color",
                "description"   => "Choose the form's border color"
                ),
            array(
                "type"      => "number",
                "label"         => "Form border radius",
                "description"   => "Choose the form's border radius in pixels"
                ),
            array(
                "type"      => "number",
                "label"         => "Form line height",
                "description"   => "Choose the form's line height in pixels"
                )
            ),
        "inputs_and_labels_settings" => 
        array(
            array(
                "type"      => "color-selector",
                "label"         => "Input Background",
                "description"   => "Choose the background color of the input"
                ),
            array(
                "type"      => "color-selector",
                "label"         => "Input Color",
                "description"   => "Choose the color for the input text"
                ),
            array(
                "type"      => "color-selector",
                "label"         => "Input Border Color",
                "description"   => "Choose a color for the input border"
                ),
            array(
                "type"      => "number",
                "label"         => "Input font size",
                "description"   => "Size of the input fonts in pixels"
                ),
            array(
                "type"      => "number",
                "label"         => "Input line height",
                "description"   => "Size of the input line height in pixels"
                ),
            array(
                "type"      => "text",
                "label"         => "Input transition",
                "description"   => "Transition Effects"
                ),
            array(
                "type"      => "text",
                "label"         => "Input border width",
                "description"   => "Size of the input border in pixels"
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Input border style",
                "value"         => array( "none", "solid", "dotted","double", "groove", "ridge", "inset", "outset" ),
                "description"   => "style of the input border"  
                ),
            array(
                "type"      => "number",
                "label"         => "Input border radius",
                "description"   => "Border radius in px"
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Input font style",
                "value"         => array( "normal", "italic", "oblique" ),
                "description"   => "Choose from the following font styles"  
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Input font weight",
                "value"         => array( "normal", "bold", "bolder", "lighter", "initial", "inherit" ),
                "description"   => "Choose from the following font weights" 
                ),
            array(
                "type"      => "text",
                "label"         => "Input width",
                "description"   => "Input width in pixels"
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Input box sizing",
                "value"         => array( "inherit", "initial", "content-box", "border-box" ),
                "description"   => "Input box sizing in pixels"
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Input Position",
                "value"         => array( "inherit", "Static", "Absolute", "Relative", "Fixed" ),
                "description"   => "Input box Position"
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Input text align",
                "value"         => array( "none", "left", "right","center"),
                "description"   => "Style of the text Alignment in the Form"    
                ),
            array(
                "type"      => "text",
                "label"         => "Input z-index",
                "description"   => "input z-index"
                ),
            array(
                "type"      => "text",
                "label"         => "Input top",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px  top is 25px.Ex 2: 0px  top is 0px "
                ),
            array(
                "type"      => "text",
                "label"         => "Input bottom",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px  bottom is 25px.Ex 2: 0px  bottom is 0px "
                ),
            array(
                "type"      => "text",
                "label"         => "Input left",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px  left is 25px.Ex 2: 0px  left is 0px "
                ),
            array(
                "type"      => "text",
                "label"         => "Input right",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px  right is 25px.Ex 2: 0px  right is 0px "
                ),
            array(
                "type"      => "number",
                "label"         => "Input height",
                "description"   => "Input height in pixels"
                ),
            array(
                "type"      => "textarea",
                "label"         => "Input hover",
                "description"   => "Add css in label hover"
                ),
            array(
                "type"      => "textarea",
                "label"         => "Input focus",
                "description"   => "Add css in label focus"
                ),
            array(
                "type"      => "text",
                "label"         => "Input padding",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px 50px 75px 100px;  top padding is 25px right padding is 50px bottom padding is 75px left padding is 100px   Ex 2: 25px 50px 75px;  top padding is 25px right and left paddings are 50px bottom padding is 75px   Ex 3: 25px 50px;  top and bottom paddings are 25px right and left paddings are 50px   Ex 4: 25px;  all four paddings are 25px  "
                ),
            array(
                "type"      => "text",
                "label"     => "Input margin",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px 50px 75px 100px;  top margin is 25px right margin is 50px bottom margin is 75px left margin is 100px   Ex 2: 25px 50px 75px;  top margin is 25px right and left margin are 50px bottom margin is 75px   Ex 3: 25px 50px;  top and bottom margin are 25px right and left margin are 50px   Ex 4: 25px;  all four margin are 25px"
                )
        ),
        "fileupload_button_settings" => 
        array(
            array(
                "type"      => "hidden",
                "label"         => "FileUpload Styles",
                "description"   => "FileUpload Styles"
                )   
        ),
        "textarea_settings" => 
        array(
            array(
                "type"      => "color-selector",
                "label"     => "Textarea background color",
                "description"   => "Textarea background color"
                ),
            array(
                "type"      => "number",
                "label"     => "Textarea height",
                "description"   => "Textarea height in pixels"
                ),
            array(
                "type"      => "text",
                "label"     => "Textarea width",
                "description"   => "Textarea width in pixels"
                ),
            array(
                "type"      => "text",
                "label"         => "Textarea transition",
                "description"   => "Transition Effects"
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Textarea box sizing",
                "value"         => array( "inherit", "initial", "content-box", "border-box" ),
                "description"   => "Textarea box sizing in pixels"
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Textarea Position",
                "value"         => array( "inherit", "Static", "Absolute", "Relative", "Fixed" ),
                "description"   => "Form box Position"
                ),
            array(
                "type"      => "text",
                "label"         => "Textarea top",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px  top is 25px.Ex 2: 0px  top is 0px "
                ),
            array(
                "type"      => "text",
                "label"         => "Textarea bottom",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px  bottom is 25px.Ex 2: 0px  bottom is 0px "
                ),
            array(
                "type"      => "text",
                "label"         => "Textarea left",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px  left is 25px.Ex 2: 0px  left is 0px "
                ),
            array(
                "type"      => "text",
                "label"         => "Textarea right",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px  right is 25px.Ex 2: 0px  right is 0px "
                ),
            array(
                "type"      => "textarea",
                "label"         => "Textarea hover",
                "description"   => "Add css in label hover"
                ),
            array(
                "type"      => "textarea",
                "label"         => "Textarea focus",
                "description"   => "Add css in label focus"
                ),
            array(
                "type"      => "number",
                "label"     => "Textarea border-size",
                "description"   => "Textarea border width pixels"
                ),
            array(
                "type"      => "color-selector",
                "label"     => "Textarea border color",
                "description"   => "Textarea border color"
                ),
            array(
                "type"      => "selectbox",
                "label"     => "Textarea border style",
                "value"     => array( "none", "solid", "dotted","double", "groove", "ridge", "inset", "outset" ),
                "description"   => "Textarea border style"
                ),
            array(
                "type"      => "number",
                "label"     => "Textarea border radius",
                "description"   => "Textarea border radius"
                ),
            array(
                "type"      => "number",
                "label"     => "Textarea font-size",
                "description"   => "Textarea font size"
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Textarea text align",
                "value"         => array( "none", "left", "right","center"),
                "description"   => "Style of the text Alignment in the Textarea"    
                ),
            array(
                "type"      => "text",
                "label"         => "Textarea z-index",
                "description"   => "Textarea z-index"
                ),
            array(
                "type"      => "number",
                "label"     => "Textarea line height",
                "description"   => "Textarea line height in pixels"
                ),
            array(
                "type"      => "selectbox",
                "label"     => "Textarea font-style",
                "value"     => array( "normal", "bold", "bolder", "lighter", "initial", "inherit" ),
                "description"   => "Textarea font style"
                )
        ),
        "dropdown_settings" => 
        array(
            
            array(
                "type"      => "selectbox",
                "label"         => "Dropdown Styles",
                "value"         => array( "Border", "Underline", "Elastic", "Slide", "Rotate"),
                "description"   => "Dropdown Styles"
                ),
            array(
                "type"      => "color-selector",
                "label"         => "Dropdown Background",
                "description"   => "Choose the background color of the dropdown"
                ),
            array(
                "type"      => "color-selector",
                "label"         => "Dropdown Color",
                "description"   => "Choose the color for the dropdown text"
                ),
            array(
                "type"      => "text",
                "label"         => "Dropdown transition",
                "description"   => "Transition Effects"
                ),
            array(
                "type"      => "color-selector",
                "label"         => "Dropdown Border Color",
                "description"   => "Choose a color for the dropdown border"
                ),
            array(
                "type"      => "number",
                "label"         => "Dropdown font size",
                "description"   => "Size of the dropdown fonts in pixels"
                ),
            array(
                "type"      => "number",
                "label"         => "Dropdown line height",
                "description"   => "Size of the dropdown line height in pixels"
                ),
            array(
                "type"      => "number",
                "label"         => "Dropdown border width",
                "description"   => "Size of the dropdown border in pixels"
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Dropdown border style",
                "value"         => array( "none", "solid", "dotted","double", "groove", "ridge", "inset", "outset" ),
                "description"   => "style of the dropdown border"   
                ),
            array(
                "type"      => "number",
                "label"         => "Dropdown border radius",
                "description"   => "Border radius in px"
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Dropdown font style",
                "value"         => array( "normal", "italic", "oblique" ),
                "description"   => "Choose from the following font styles"  
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Dropdown font weight",
                "value"         => array( "normal", "bold", "bolder", "lighter", "initial", "inherit" ),
                "description"   => "Choose from the following font weights" 
                ),
            array(
                "type"      => "text",
                "label"         => "Dropdown width",
                "description"   => "Dropdown width in pixels"
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Dropdown box sizing",
                "value"         => array( "inherit", "initial", "content-box", "border-box" ),
                "description"   => "Dropdown box sizing in pixels"
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Dropdown Position",
                "value"         => array( "inherit", "Static", "Absolute", "Relative", "Fixed" ),
                "description"   => "Form box Position"
                ),
            array(
                "type"      => "text",
                "label"         => "Dropdown top",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px  top is 25px.Ex 2: 0px  top is 0px "
                ),
            array(
                "type"      => "text",
                "label"         => "Dropdown bottom",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px  bottom is 25px.Ex 2: 0px  bottom is 0px "
                ),
            array(
                "type"      => "text",
                "label"         => "Dropdown left",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px  left is 25px.Ex 2: 0px  left is 0px "
                ),
            array(
                "type"      => "text",
                "label"         => "Dropdown right",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px  right is 25px.Ex 2: 0px  right is 0px "
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Dropdown text align",
                "value"         => array( "none", "left", "right","center"),
                "description"   => "Style of the text Alignment in the Dropdown"    
                ),
            array(
                "type"      => "text",
                "label"         => "Dropdown z-index",
                "description"   => "Dropdown z-index"
                ),
            array(
                "type"      => "number",
                "label"         => "Dropdown height",
                "description"   => "Dropdown height in pixels"
                ),
            array(
                "type"      => "text",
                "label"         => "Dropdown padding",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px 50px 75px 100px;  top padding is 25px right padding is 50px bottom padding is 75px left padding is 100px   Ex 2: 25px 50px 75px;  top padding is 25px right and left paddings are 50px bottom padding is 75px   Ex 3: 25px 50px;  top and bottom paddings are 25px right and left paddings are 50px   Ex 4: 25px;  all four paddings are 25px  "
                ),
            array(
                "type"      => "text",
                "label"     => "Dropdown margin",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px 50px 75px 100px;  top margin is 25px right margin is 50px bottom margin is 75px left margin is 100px   Ex 2: 25px 50px 75px;  top margin is 25px right and left margin are 50px bottom margin is 75px   Ex 3: 25px 50px;  top and bottom margin are 25px right and left margin are 50px   Ex 4: 25px;  all four margin are 25px"
                )
        ),
        "label_settings" => 
        array(
            array(
                "type"      => "selectbox",
                "label"     => "Label font style",
                "value"     => array( "normal", "italic", "oblique" ),
                "description"   => "Choose from the following font styles"  
                ),
            array(
                "type"      => "selectbox",
                "label"     => "Label font weight",
                "value"     => array( "normal", "bold", "bolder", "lighter", "initial", "inherit" ),
                "description"   => "Choose from the following label font weights"   
                ),
            array(
                "type"      => "number",
                "label"         => "Label font size",
                "description"   => "Size of the label fonts in pixels"
                ),
            array(
                "type"      => "number",
                "label"         => "Label line height",
                "description"   => "Size of the label line height in pixels"
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Label Position",
                "value"         => array( "inherit", "Static", "Absolute", "Relative", "Fixed" ),
                "description"   => "Form box Position"
                ),
            array(
                "type"      => "text",
                "label"     => "Label width",
                "description"   => "Label width in pixels"
                ),
            array(
                "type"      => "number",
                "label"     => "Label height",
                "description"   => "Label width in pixels"
                ),
            array(
                "type"      => "text",
                "label"         => "Label top",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px  top is 25px.Ex 2: 0px  top is 0px "
                ),
            array(
                "type"      => "text",
                "label"         => "Label bottom",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px  bottom is 25px.Ex 2: 0px  bottom is 0px "
                ),
            array(
                "type"      => "text",
                "label"         => "Label left",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px  left is 25px.Ex 2: 0px  left is 0px "
                ),
            array(
                "type"      => "number",
                "label"         => "Label border width",
                "description"   => "Label border width in pixels"
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Label border style",
                "value"         => array( "none", "solid", "dotted","double", "groove", "ridge", "inset", "outset" ),
                "description"   => "Style of the Border of the Label"   
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Label text align",
                "value"         => array( "none", "left", "right","center"),
                "description"   => "Style of the text Alignment in the Label"   
                ),
            array(
                "type"      => "text",
                "label"         => "Label z-index",
                "description"   => "Label z-index"
                ),
            array(
                "type"      => "color-selector",
                "label"         => "Label border color",
                "description"   => "Choose the form's border color"
                ),
            array(
                "type"      => "number",
                "label"         => "Label border radius",
                "description"   => "Choose the form's border radius in pixels"
                ),
            array(
                "type"      => "textarea",
                "label"         => "Label after",
                "description"   => "Add css in label after"
                ),
            array(
                "type"      => "textarea",
                "label"         => "Label before",
                "description"   => "Add css in label before"
                ),
            array(
                "type"      => "textarea",
                "label"         => "Label hover",
                "description"   => "Add css in label hover"
                ),
            array(
                "type"      => "textarea",
                "label"         => "Label focus",
                "description"   => "Add css in label focus"
                ),
            array(
                "type"      => "text",
                "label"         => "Label right",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px  right is 25px.Ex 2: 0px  right is 0px "
                ),
            array(
                "type"      => "text",
                "label"         => "Label transition",
                "description"   => "Transition Effects"
            ),
            array(
                "type"      => "text",
                "label"         => "Label padding",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px 50px 75px 100px;  top padding is 25px right padding is 50px bottom padding is 75px left padding is 100px   Ex 2: 25px 50px 75px;  top padding is 25px right and left paddings are 50px bottom padding is 75px   Ex 3: 25px 50px;  top and bottom paddings are 25px right and left paddings are 50px   Ex 4: 25px;  all four paddings are 25px  "
                ),
            array(
                "type"      => "color-selector",
                "label"         => "Label Color",
                "description"   => "Choose the color for the label text"
                )
        ),
        "submit_button_settings" => 
        array(
            array(
                "type"      => "hidden",
                "label"         => "Submit Styles",
                "description"   => "Submit Styles"
                ),
            array(
                "type"      => "number",
                "label"         => "Submit button width",
                "description"   => "Submit button width in px"
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Submit button box sizing",
                "value"         => array( "inherit", "initial", "content-box", "border-box" ),
                "description"   => "Submit button box sizing in pixels"
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Submit text align",
                "value"         => array( "none", "left", "right","center"),
                "description"   => "Style of the text Alignment in the Submit"  
                ),
            array(
                "type"      => "text",
                "label"         => "Submit z-index",
                "description"   => "Submit z-index"
                ),
            array(
                "type"      => "number",
                "label"         => "Submit button height",
                "description"   => "Submit button height in px"
                ),
            array(
                "type"      => "number",
                "label"         => "Submit button border radius",
                "description"   => "Border radius in px"
                ),
            array(
                "type"      => "number",
                "label"         => "Submit button font size",
                "description"   => "Font size in px"
                ),
            array(
                "type"      => "number",
                "label"         => "Submit button line height",
                "description"   => "Line height in px"
                ),
            array(
                "type"      => "textarea",
                "label"         => "Submit hover",
                "description"   => "Add css in label hover"
                ),
            array(
                "type"      => "textarea",
                "label"         => "Submit focus",
                "description"   => "Add css in label focus"
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Submit Position",
                "value"         => array( "inherit", "Static", "Absolute", "Relative", "Fixed" ),
                "description"   => "Form box Position"
                ),
            array(
                "type"      => "text",
                "label"         => "Submit top",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px  top is 25px.Ex 2: 0px  top is 0px "
                ),
            array(
                "type"      => "text",
                "label"         => "Submit bottom",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px  bottom is 25px.Ex 2: 0px  bottom is 0px "
                ),
            array(
                "type"      => "text",
                "label"         => "Submit left",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px  left is 25px.Ex 2: 0px  left is 0px "
                ),
            array(
                "type"      => "text",
                "label"         => "Submit right",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px  right is 25px.Ex 2: 0px  right is 0px "
                ),
            array(
                "type"      => "text",
                "label"         => "Submit transition",
                "description"   => "Transition Effects"
                ),
            array(
                "type"      => "number",
                "label"         => "Submit button border width",
                "description"   => "Size of the submit button border in pixels"
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Submit button border style",
                "value"         => array( "none", "solid", "dotted","double", "groove", "ridge", "inset", "outset" ),
                "description"   => "Type of the submit button border"   
                ),
            array(
                "type"      => "color-selector",
                "label"         => "Submit button border color",
                "description"   => "Choose a color for the submit border"
                ),
            array(
                "type"      => "color-selector",
                "label"         => "Submit button color",
                "description"   => "Choose a color for the submit button text"
                ),
            array(
                "type"      => "color-selector",
                "label"         => "Submit button background",
                "description"   => "Choose a color for the submit background"
                ),
        ),
    "fieldset_settings" => 
        array(
            array(
                "type"      => "color-selector",
                "label"         => "Fieldset Background",
                "description"   => "Choose the background color of the input"
                ),
            array(
                "type"      => "text",
                "label"         => "Fieldset width",
                "description"   => "Input width in pixels"
                ),
            array(
                "type"      => "textarea",
                "label"         => "Fieldset after",
                "description"   => "Add css in fieldset after"
                ),
            array(
                "type"      => "textarea",
                "label"         => "Fieldset before",
                "description"   => "Add css in fieldset before"
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Fieldset display",
                "value"         => array( "none", "inline", "block", "flex", "inline-block", "inline-flex", "inline-table", "list-item", "table", "initial", "inherit" ),
                "description"   => "Select Form Display style"  
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Fieldset vertical-align",
                "value"         => array( "top", "middle", "bottom","text-top","text-bottom","super,sub","calc()","baseline","unset"),
                "description"   => "style of the input border"  
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Fieldset text align",
                "value"         => array( "none", "left", "right","center"),
                "description"   => "Style of the text Alignment in the Fieldset"    
                ),
            array(
                "type"      => "text",
                "label"         => "Fieldset z-index",
                "description"   => "Fieldset z-index"
                ),
            array(
                "type"      => "color-selector",
                "label"         => "Fieldset Color",
                "description"   => "Choose the color for the input text"
                ),
            array(
                "type"      => "color-selector",
                "label"         => "Fieldset Border Color",
                "description"   => "Choose a color for the input border"
                ),
            array(
                "type"      => "number",
                "label"         => "Fieldset font size",
                "description"   => "Size of the input fonts in pixels"
                ),
            array(
                "type"      => "number",
                "label"         => "Fieldset line height",
                "description"   => "Size of the input line height in pixels"
                ),
            array(
                "type"      => "text",
                "label"         => "Fieldset transition",
                "description"   => "Transition Effects"
                ),
            array(
                "type"      => "text",
                "label"         => "Fieldset border width",
                "description"   => "Size of the input border in pixels"
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Fieldset border style",
                "value"         => array( "none", "solid", "dotted","double", "groove", "ridge", "inset", "outset" ),
                "description"   => "style of the input border"  
                ),
            array(
                "type"      => "number",
                "label"         => "Fieldset border radius",
                "description"   => "Border radius in px"
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Fieldset font style",
                "value"         => array( "normal", "italic", "oblique" ),
                "description"   => "Choose from the following font styles"  
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Fieldset font weight",
                "value"         => array( "normal", "bold", "bolder", "lighter", "initial", "inherit" ),
                "description"   => "Choose from the following font weights" 
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Fieldset box sizing",
                "value"         => array( "inherit", "initial", "content-box", "border-box" ),
                "description"   => "Fieldset box sizing in pixels"
                ),
            array(
                "type"      => "selectbox",
                "label"         => "Fieldset Position",
                "value"         => array( "inherit", "Static", "Absolute", "Relative", "Fixed" ),
                "description"   => "Form box Position"
                ),
            array(
                "type"      => "text",
                "label"         => "Fieldset top",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px  top is 25px.Ex 2: 0px  top is 0px "
                ),
            array(
                "type"      => "text",
                "label"         => "Fieldset bottom",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px  bottom is 25px.Ex 2: 0px  bottom is 0px "
                ),
            array(
                "type"      => "text",
                "label"         => "Fieldset left",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px  left is 25px.Ex 2: 0px  left is 0px "
                ),
            array(
                "type"      => "text",
                "label"         => "Fieldset right",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px  right is 25px.Ex 2: 0px  right is 0px "
                ),
            array(
                "type"      => "number",
                "label"         => "Fieldset height",
                "description"   => "Fieldset height in pixels"
                ),
            array(
                "type"      => "text",
                "label"         => "Fieldset padding",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px 50px 75px 100px;  top padding is 25px right padding is 50px bottom padding is 75px left padding is 100px   Ex 2: 25px 50px 75px;  top padding is 25px right and left paddings are 50px bottom padding is 75px   Ex 3: 25px 50px;  top and bottom paddings are 25px right and left paddings are 50px   Ex 4: 25px;  all four paddings are 25px  "
                ),
            array(
                "type"      => "text",
                "label"     => "Fieldset margin",
                "description"   => "hover here for example",
                "title"     => "Ex 1: 25px 50px 75px 100px;  top margin is 25px right margin is 50px bottom margin is 75px left margin is 100px   Ex 2: 25px 50px 75px;  top margin is 25px right and left margin are 50px bottom margin is 75px   Ex 3: 25px 50px;  top and bottom margin are 25px right and left margin are 50px   Ex 4: 25px;  all four margin are 25px"
                )
            )
    );
}
/**
* renders the custom meta box's inputs
*/
function feua_style_render_settings( $type, $label, $options, $value, $description, $title ){
    $feuas_id = strtolower( str_replace( " ", "-", $label ) );
    $r_check = explode(' ', $label);
    $hd = '';
    if($r_check[0] == 'Dropdown' && $feuas_id != 'dropdown-styles') {
        $hd = 'style="display:none"';   
    }
    if($r_check[0] == 'Submit' && $feuas_id != 'submit-styles') {
        $hd = 'style="display:none"';   
    }
    if($r_check[0] == 'FileUpload' && $feuas_id != 'fileupload-styles') {
        $hd = 'style="display:none"';   
    }
    ?>
    <li class="custom-field" <?php echo $hd; ?>>
        <div title="Click and Drag to rearrange" class="user-legend">
            <div class="user-label"><?php  echo __( $label, "feuastyle_text_domain"); ?>: <strong><?php  echo __( $label, "feuastyle_text_domain"); ?></strong></div>
            <div class="user-actions">
                <a class="user-toggle" href="#">Toggle</a>
            </div>
        </div> 
        <div class="user-form-holder" style="display: block;">
            <?php $class  = ( "color-selector" == $type ) ? "feua-style-color-field" : "";
            if( "selectbox" == $type )
            { ?>
                <select id="feuas-<?php  echo $feuas_id; ?>" name="feuastylecustom[<?php  echo $feuas_id; ?>]">
                    <option>Default</option>
                    <?php foreach( $options as $option ) {?>
                    <option value="<?php echo $option; ?>"<?php if($option == $value) echo " selected='selected'";?>><?php echo $option; ?></option>
                    <?php }//foreach end 
                    ?>  
                </select>   
                <?php 
                if( $feuas_id == 'dropdown-styles' ) 
                { ?>
                    <link rel="stylesheet" type="text/css" href="<?php echo plugins_url('assets/css/cs-select.css', __FILE__); ?>" />
                    <link rel="stylesheet" type="text/css" href="<?php echo plugins_url('assets/css/cs-skin-border.css', __FILE__); ?>" />
                    <link rel="stylesheet" type="text/css" href="<?php echo plugins_url('assets/css/cs-skin-underline.css', __FILE__); ?>" />
                    <link rel="stylesheet" type="text/css" href="<?php echo plugins_url('assets/css/cs-skin-elastic.css', __FILE__); ?>" />
                    <link rel="stylesheet" type="text/css" href="<?php echo plugins_url('assets/css/cs-skin-rotate.css', __FILE__); ?>" />
                    <link rel="stylesheet" type="text/css" href="<?php echo plugins_url('assets/css/cs-skin-slide.css', __FILE__); ?>" />
                    <script src="<?php echo plugins_url('assets/js/classie.js', __FILE__); ?>"></script>
                    <script src="<?php echo plugins_url('assets/js/selectFx.js', __FILE__); ?>"></script>
                    <script>
                        (function($) {
                            $(document).ready(function(){
                                (function() {
                                    [].slice.call( document.querySelectorAll( 'select.cs-select_f' ) ).forEach( function(el) {  
                                        new SelectFx(el);
                                    } );
                                })();
                            $('#feuas-<?php  echo $feuas_id; ?>').on('change',function(){
                                var cls = 'cs-skin-'+$(this).val().toLowerCase();
                                $('.drop_down_st > .cs-select').hide();
                                $('div.'+cls).fadeIn();
                            });
                            });
                        })(jQuery);
                    </script>
                    <style type="text/css">
                    .drop_down_st {
                        max-width: 225px;
                        display: inline-block;
                        width: 100%;
                        margin-left: 49px;
                    }
                    #feuas-dropdown-styles {
                        vertical-align: top;
                    }
                    .drop_down_st > .cs-select:not(.cs-skin-<?php echo strtolower($value); ?>) {
                        display: none;
                    }
                    </style>
                    <div class="drop_down_st">
                        <select class="cs-select cs-select_f cs-skin-border">
                            <option value="item1">Item 1 </option>
                            <option value="item2">Item 2 </option>
                            <option value="item3">Item 3 </option>
                            <option value="item4">Item 4 </option>
                        </select>
                        <select class="cs-select cs-select_f cs-skin-underline">
                            <option value="item1">Item 1 </option>
                            <option value="item2">Item 2 </option>
                            <option value="item3">Item 3 </option>
                            <option value="item4">Item 4 </option>
                        </select>
                        <select class="cs-select cs-select_f cs-skin-elastic">
                            <option value="item1">Item 1 </option>
                            <option value="item2">Item 2 </option>
                            <option value="item3">Item 3 </option>
                            <option value="item4">Item 4 </option>
                        </select>
                        <select class="cs-select cs-select_f cs-skin-rotate">
                            <option value="item1">Item 1 </option>
                            <option value="item2">Item 2 </option>
                            <option value="item3">Item 3 </option>
                            <option value="item4">Item 4 </option>
                        </select>
                        <select class="cs-select cs-select_f cs-skin-slide">
                            <option value="item1">Item 1 </option>
                            <option value="item2">Item 2 </option>
                            <option value="item3">Item 3 </option>
                            <option value="item4">Item 4 </option>
                        </select>
                    </div>
                    <?php
                }
            } elseif ( 'textarea' == $type) 
            {
                ?>
                <textarea id="feuas-<?php  echo $feuas_id; ?>" name="feuastylecustom[<?php  echo $feuas_id; ?>]"  <?php if( $class != "" ) echo 'class="'.$class.'"';?>><?php  echo stripslashes($value); ?></textarea>
                <?php
            } else 
            { ?>
                <input type="<?php  echo ( $type == 'color-selector' ) ? 'text' : $type; ?>" id="feuas-<?php  echo $feuas_id; ?>" name="feuastylecustom[<?php  echo $feuas_id; ?>]" value="<?php  echo $value; ?>" <?php if( $class != "" ) echo 'class="'.$class.'"';?>/>
                <?php 
            }
            if(  $feuas_id == 'submit-styles' ) 
            { ?>
                <link rel="stylesheet" type="text/css" href="<?php echo plugins_url('assets/css/component.css', __FILE__); ?>" />
                <div class="all_button_wrap">   
                    <section class="color-1">
                        <p class="text note-touch">Note that on mobile devices the effects might not all work as intended.</p>
                        <p>
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-1 btn-1a">Button</button>
                        </p>
                        <p>
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-1 btn-1b">Button</button>
                        </p>
                        <p> 
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-1 btn-1c">Button</button>
                        </p>
                        <p>
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-1 btn-1d">Button</button>
                        </p>
                        <p>     
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-1 btn-1e">Button</button>
                        </p>
                        <p>     
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-1 btn-1f">Button</button>
                        </p>
                    </section>
                    <section class="color-2">
                        <p>
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-2 btn-2a">Button</button>
                        </p>
                        <p> 
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-2 btn-2b">Button</button>
                        </p>
                        <p>
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-2 btn-2c">Button</button>
                        </p>
                        <p> 
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-2 btn-2d">Button</button>
                        </p>
                        <p>
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-2 btn-2e">Button</button>
                        </p>
                        <p> 
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-2 btn-2f">Button</button>
                        </p>
                        <p>
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-2 btn-2g">Button</button>
                        </p>
                        <p> 
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-2 btn-2h">Button</button>
                        </p>
                        <p>
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-2 btn-2i">Yes</button>
                        </p>
                        <p> 
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-2 btn-2j">No</button>
                        </p>
                    </section>
                    <section class="color-3">
                        <p>
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-3 btn-3a">Add to cart</button>
                        </p>
                        <p> 
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-3 btn-3b">Bookmark</button>
                        </p>
                        <p> 
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-3 btn-3e">Send data</button>
                        </p>
                    </section>
                    <section class="color-4">
                        <p>
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-4 btn-4a">Submit</button>
                        </p>
                    </section>
                    <section class="color-5">
                        <p>
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-5 btn-5a">Submit</button>
                        </p>
                    </section>
                    <section class="color-6">
                        <p>
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-6 btn-6a">Button</button>
                        </p>
                        <p> 
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-6 btn-6b">Button</button>
                        </p>
                        <p> 
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-6 btn-6c">Button</button>
                        </p>
                        <p>
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-6 btn-6d">Button</button>
                        </p>
                        <p> 
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-6 btn-6e">Button</button>
                        </p>
                        <p> 
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-6 btn-6f">Button</button>
                        </p>
                        <p>
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-6 btn-6g">Button</button>
                        </p>
                        <p> 
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-6 btn-6h">Button</button>
                        </p>
                        <p> 
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-6 btn-6i">Button</button>
                        </p>
                        <p>
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-6 btn-6j">Button</button>
                        </p>
                        <p> 
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-6 btn-6k">Button</button>
                        </p>
                        <p> 
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-6 btn-6l">Button</button>
                        </p>
                    </section>
                    <section class="color-8">
                        <p class="perspective">
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-8 btn-8a">3D Button</button>
                        </p>
                        <p class="perspective">
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-8 btn-8b">3D Button</button>
                        </p>
                        <br />
                        <p class="perspective">
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-8 btn-8c">3D Button</button>
                        </p>
                        <p class="perspective">
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-8 btn-8d">3D Button</button>
                        </p>
                        <p class="text">Click the following buttons to see the effect:</p>
                        <p class="perspective">
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-8 btn-8e">3D Button</button>
                        </p>
                        <p class="perspective">
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-8 btn-8f">3D Button</button>
                        </p>
                        <br />
                        <p class="perspective">
                            <input type="checkbox" class="select_btn_type" value="">
                            <button class="btn btn-8 btn-8g">3D Button</button>
                        </p>
                    </section>
                </div>
                <script type="text/javascript">
                    (function($){
                        $(document).ready(function(){
                            $('.select_btn_type').on('change',function(){
                                $('.select_btn_type').attr('checked',false);
                            $(this).attr('checked',true);
                            if ($(this).attr("checked")) {
                                var cls = $(this).next().attr('class');
                                $('#feuas-submit-styles').val(cls);
                                $(document).find('ul.user-form fieldset input[type="submit"]').removeClass();
                                $(document).find('ul.user-form fieldset input[type="submit"]').addClass(cls);
                            }
                        });
                        $('.select_btn_type + button').on('click',function(e){
                            e.preventDefault();
                        });
                      });   
                    })(jQuery);
                </script>
                <?php
            } 
            if ($feuas_id == 'fileupload-styles') 
            {
                ?>
                <link rel="stylesheet" type="text/css" href="<?php echo plugins_url('assets/css/component1.css', __FILE__); ?>" />
                <div class="box">
                        <input type="checkbox" class="select_upload_type" value="">
                        <input type="file" name="file-1[]" id="file-1" class="inputfile inputfile-1" data-multiple-caption="{count} files selected" multiple />
                        <label for="file-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17">
                                <path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/>
                            </svg> 
                            <span>Choose a file&hellip;</span>
                        </label>
                </div>
                <div class="box">
                    <input type="checkbox" class="select_upload_type" value="">
                    <input type="file" name="file-2[]" id="file-2" class="inputfile inputfile-2" data-multiple-caption="{count} files selected" multiple />
                    <label for="file-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17">
                            <path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/>
                        </svg> 
                        <span>Choose a file&hellip;</span>
                    </label>
                </div>
                <div class="box">
                    <input type="checkbox" class="select_upload_type" value="">
                    <input type="file" name="file-3[]" id="file-3" class="inputfile inputfile-3" data-multiple-caption="{count} files selected" multiple />
                    <label for="file-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17">
                            <path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> 
                        <span>Choose a file&hellip;</span>
                    </label>
                </div>
                <div class="box">
                    <input type="checkbox" class="select_upload_type" value="">
                    <input type="file" name="file-4[]" id="file-4" class="inputfile inputfile-3" data-multiple-caption="{count} files selected" multiple />
                    <label for="file-4"><span>Choose a file&hellip;</span></label>
                </div>
                <div class="box">
                    <input type="checkbox" class="select_upload_type" value="">
                    <input type="file" name="file-5[]" id="file-5" class="inputfile inputfile-4" data-multiple-caption="{count} files selected" multiple />
                    <label for="file-5">
                        <figure>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17">
                                <path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg>
                        </figure> 
                        <span>Choose a file&hellip;</span>
                    </label>
                </div>
                <div class="box">
                    <input type="checkbox" class="select_upload_type" value="">
                    <input type="file" name="file-6[]" id="file-6" class="inputfile inputfile-5" data-multiple-caption="{count} files selected" multiple />
                    <label for="file-6">
                        <figure>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17">
                                <path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/>
                            </svg>
                        </figure> 
                        <span>Choose a file&hellip;</span>
                    </label>
                </div>
                <script type="text/javascript">
                    (function($){
                        $(document).ready(function(){
                            $('.select_upload_type').on('change',function(){
                                $('.select_upload_type').attr('checked',false);
                            $(this).attr('checked',true);
                            if ($(this).attr("checked")) {
                                var pre_cls = $('#feuas-fileupload-styles').val();
                                var cls = $(this).next().attr('class');
                                $('#feuas-fileupload-styles').val(cls);
                                var html_r = '<label for="file-6"><figure><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg></figure><span>Choose a file&hellip;</span></label>';
                                $(document).find('ul.user-form fieldset[class*="image_upload"] .user-fields .user-attachment-upload-filelist a.file-selector').removeClass(pre_cls);
                                $(document).find('ul.user-form fieldset[class*="image_upload"] .user-fields .user-attachment-upload-filelist a.file-selector').addClass(cls);
                                $(document).find('ul.user-form fieldset[class*="image_upload"] .user-fields .user-attachment-upload-filelist a.file-selector + label').remove();
                                $(document).find('ul.user-form fieldset[class*="image_upload"] .user-fields .user-attachment-upload-filelist a.file-selector').after(html_r);
                                $(document).find('ul.user-form fieldset[class*="file_upload"] table tr td:nth-child(2) a').removeClass(pre_cls);
                                $(document).find('ul.user-form fieldset[class*="file_upload"] table tr td:nth-child(2) a').addClass(cls);
                                $(document).find('ul.user-form fieldset[class*="file_upload"] table tr td:nth-child(2) a + label').remove();
                                $(document).find('ul.user-form fieldset[class*="file_upload"] table tr td:nth-child(2) a').after(html_r);
                            }
                        });
                      });   
                    })(jQuery);
                </script>
                <?php
            }
            ?>
            <small <?php echo ( isset( $title ) ? "title='".$title."'" : "" ); ?>><?php  echo __( $description, "feuastyle_text_domain"); ?></small>
        </div>
    </li>
    <?php
}
/**
 * Calls the class the meta box. Used for selecting forms for each style.
 */
function feua_style_meta_form_init() {
    new feua_style_meta_boxes();
}
if ( is_admin() ) {
    add_action( 'load-post.php', 'feua_style_meta_form_init' );
    add_action( 'load-post-new.php', 'feua_style_meta_form_init' );
}
/** 
 * The Class for creating all of the meta boxes
 */
class feua_style_meta_boxes 
{
    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_box_style_selector' ) );
        add_action( 'save_post', array( $this, 'save_style_selector' ) );
        add_action( 'save_post', array( $this, 'save_font_id' ) );
        // add_action( 'add_meta_boxes', array( $this, 'add_meta_box_style_customizer' ), 10, 2 );
        add_action( 'save_post', array( $this, 'save_style_customizer' ) );
        add_action('add_meta_boxes', array( $this,'wpse_add_custom_meta_box_2') );
        add_action( 'save_post', array( $this, 'save_style_customizer' ) );
add_action( 'new_to_publish', array( $this, 'save_style_customizer' ) );
    }
    public function wpse_add_custom_meta_box_2() {
       add_meta_box(
           'custom_meta_box-2',       // $id
           'Custom style settings',                  // $title
           array( $this, 'render_meta_box_style_customizer' ),  // $callback
           'feua_style',                 // $page
           'advanced',                  // $context
           'high'                     // $priority
       );
    }
    public function add_meta_box_style_selector( $post_type ) {
        $post_types = array('feua_style');     //limit meta box to certain post types
        if ( in_array( $post_type, $post_types )) {
            add_meta_box(
                'feua_style_meta_box_form_selector'
                ,__( 'Select forms for current style', 'myplugin_textdomain' )
                ,
                array( $this, 'render_meta_box_selector' )
                ,$post_type
                ,'advanced'
                ,'high'
                );
        }
    }
    public function render_meta_box_style_customizer( $post ,$metabox) {
        if (is_array($_GET) && array_key_exists('post', $_GET) ) {
            $post_id = $_GET['post'];
        }else if($post->ID){
            $post_id = $post->ID;
        }
        wp_nonce_field( 'feup_a_style_style_customizer_inner_custom_box', 'feup_a_style_customizer_custom_box_nonce' );
        $bbb =  get_post_meta( $post->ID, 'feua_style_custom_styles', true );
        $setting_array = unserialize($bbb);
        $result_manual = get_post_meta( $post_id, 'feua_style_manual_style', true ); ?>
        <h2 class="nav-tab-wrapper custom_nav_tav">
            <a class="nav-tab nav-tab-active" href="#general_settings">General</a>
            <a class="nav-tab" href="#inputs_and_labels_settings">Input</a>
            <a class="nav-tab" href="#textarea_settings">Textarea</a>
            <a class="nav-tab" href="#dropdown_settings">Dropdown</a>
            <a class="nav-tab" href="#submit_button_settings">Button</a>
            <a class="nav-tab" href="#fileupload_button_settings">File Upload</a>
            <a class="nav-tab" href="#label_settings">Label</a>
            <a class="nav-tab" href="#fieldset_settings">Fieldset</a>
            <a class="nav-tab" href="#google_font_setting">Font</a>
        </h2>
        <div class="tab-content frontend_style_css_wrap">
            <?php 
            foreach( feua_style_general_settings_array() as $key=>$settings) {  ?>
            <div class="general-settings full-width" id="<?php echo $key; ?>">
                <div style="margin-bottom: 10px; text-align: right; margin-top: 10px;ight;">
                    <button class="button user-collapse">Toggle All Fields Open/Close</button>
                </div>
                <h3><?php  echo __( str_replace( "_", " ", $key ).' for this custom style.', "feuastyle_text_domain" ); ?></h3>
                <?php
                foreach( $settings as $setting ){
                                        //this will be improved with:  if ( isset( $setting_array[$setting["label"]] ) ) {  - for later reference !!!
                    $current_val = ( !empty( $setting_array ) ) ? $setting_array[ strtolower( str_replace( " ", "-", $setting["label"] ) ) ] : "";
                    $current_option = ( $setting["type"] == "selectbox" ) ? $setting["value"] : "";
                    $current_title = ( isset( $setting["title"] ) ) ? $setting["title"] : "";
                    feua_style_render_settings( $setting["type"], $setting["label"], $current_option, $current_val, $setting["description"], $current_title );
                } ?>
            </div><!-- /.general-settings -->
            <?php } ?>
            <div class="postbox-container tab_right_form_wrap" id="postbox-container-1">
                <div class="meta-box-sortables ui-sortable" id="side-sortables"><div class="postbox " id="submitdiv">
                    <button aria-expanded="true" class="handlediv button-link" type="button"><span class="screen-reader-text">Toggle panel: Publish</span><span aria-hidden="true" class="toggle-indicator"></span></button><h2 class="hndle ui-sortable-handle"><span>Update Form To see changes..</span></h2>
                    <div class="inside">
                        <div id="submitpost" class="submitbox">
                            <p class="cr_msg" style="display:none;">Please select Form.</p>
                            
                        </div>
                    </div>
                </div>
            </div>
            </div>
                
            <div class="general-settings full-width" id="google_font_setting">
                <?php
                wp_nonce_field( 'feup_a_style_font_inner_custom_box', 'feup_a_style_font_custom_box_nonce' );
                    //getting all google fonts
                $google_list = file_get_contents( 'https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyBAympIKDNKmfxhI3udY-U_9vDWSdfHrEo' );
                $font_obj = json_decode( $google_list );
                $feua_style_font = get_post_meta( $post->ID, 'feua_style_font', true );
                $selected = '';
                echo '<select name="feua_style_font_selector">';
                echo '<option value="none">None</option>';
                foreach ($font_obj->items as $font) {
                    echo '<option value="' . $font->family . '"' . ( $feua_style_font == $font->family ? 'selected="selected"' : '' ) . '>' . $font->family . '</option>';
                }
                echo '</select>'; ?>
                <div class="feua-style preview-zone">
                    <h4>Preview Selected font:</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur rhoncus ultrices neque sit amet consequat. Aenean facilisis massa convallis nisl viverra eleifend. Nam fermentum mauris eu eleifend posuere. Duis at pharetra tellus. Suspendisse viverra tempor tellus, non efficitur nibh posuere nec. Sed vitae pellentesque augue, id efficitur enim. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus eros turpis, hendrerit id condimentum vitae, fermentum ut dolor. Cras quis lobortis ante.</p>
                </div>
                <div class="clear">
                </div>
            </div>
        </div>
        <script type="text/javascript">
            (function($){
                $(document).ready(function(){
                    var current_form_id = $('#current_form_id').val();
                    if(current_form_id == '') {
                        $('.cr_msg').show();
                    }
                    $.ajax({
                       method: "POST",
                       url: "<?php echo admin_url('admin-ajax.php'); ?>",
                       data: {action: 'get_current_form', id: current_form_id}
                    }).done(function(res) {
                      $('.tab_right_form_wrap #submitpost').append(res);
                    });
                    $('.feuastyle_body_select_all td input[type="checkbox"]').on('change',function(){
                        $('.feuastyle_body_select_all td input[type="checkbox"]').attr('checked',false);
                        $(this).attr('checked',true);
                        if ($(this).attr("checked")) {
                            var id = $(this).val();
                            $.ajax({
                               method: "POST",
                               url: "<?php echo admin_url('admin-ajax.php'); ?>",
                               data: {action: 'get_current_form', id: id}
                            }).done(function(res) {
                              $('.tab_right_form_wrap #submitpost').empty();
                              $('.tab_right_form_wrap #submitpost').append(res);
                            });
                            
                        }
                    });         
                    $('.custom_nav_tav a').on('click',function(e){
                        e.preventDefault();
                        var cls = $(this).attr('href');
                            // console.log(cls);
                            $(this).siblings().removeClass('nav-tab-active');
                            $(this).addClass('nav-tab-active');
                            $('.tab-content > .general-settings').css('display','none');
                            $(cls).fadeIn();
                        });
                    $('.general-settings li .user-legend .user-actions a.user-toggle').on('click',function(e){
                        e.preventDefault();
                        $(this).closest('.user-legend').next().toggle();
                    });
                    $('.button.user-collapse').on('click',function(e){
                        e.preventDefault();
                        $(this).closest('.general-settings').find('li .user-form-holder').toggle();
                    });
                });
            })(jQuery);
        </script>
        <div class="general-settings full-width">
            <h3><?php _e( "CSS Editor", "feuastyle" ); ?></h3>
            <p>You can easily find CSS elements by using your browser inspector or Firebug, or view a quick guide <a href="http://sixrevisions.com/tools/firebug-guide-web-designers/" target="_blank" title="Firebug guide">here</a>.</p>
            <label for="manual-style">
                <textarea name="manual-style" id="manual-style" cols="30" rows="10"><?php echo $result_manual; ?></textarea>
            </label>
        </div>
        <div class="clear"></div>
        <?php
    }
    public function save_style_customizer( $post_id ) {
        global $wpdb;
        if ( ! isset( $_POST['feup_a_style_customizer_custom_box_nonce'] ) )
            return $post_id;
        $nonce = $_POST['feup_a_style_customizer_custom_box_nonce'];
            // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'feup_a_style_style_customizer_inner_custom_box' ) ) {
            return $post_id;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
        if ( 'page' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }
        $posted_data = $_POST['feuastylecustom'];
        $posted_manual_style_data   =  strip_tags($_POST[ 'manual-style' ]);
        preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $posted_manual_style_data);
        if ( is_array( $posted_data ) && isset( $posted_data ) ) {
            // $posted_data = esc_sql($posted_data);
            $serialized_result = esc_sql(serialize( $posted_data ));
            update_post_meta( $post_id, 'feua_style_custom_styles', $serialized_result, "");
        }
        if (  isset( $posted_manual_style_data ) ) {
            update_post_meta( $post_id, 'feua_style_manual_style', $posted_manual_style_data, "");
        }
    }
    public function save_style_selector( $post_id ) {
        if ( ! isset( $_POST['feup_a_style_selector_custom_box_nonce'] ) )
            return $post_id;
        $nonce = $_POST['feup_a_style_selector_custom_box_nonce'];
        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'feup_a_style_selector_inner_custom_box' ) ) {
            return $post_id;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
        if ( 'page' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }
        
        //getting all the feua forms
        $feuaformsargs = array(
            'post_type'     => array('user_forms','user_logins','user_registrations'),
            'posts_per_page'    => -1
            );
        $feuaforms = get_posts( $feuaformsargs );
        
        //for each checked form, saving the style id
        foreach ( $feuaforms as $feuaform ) {
            if ( isset( $_POST[$feuaform->post_name] ) ) {
                //if ( !empty( $_POST[$feuaform->post_name] ) ) {
                update_post_meta( $feuaform->ID, 'feua_style_id', $post_id);
                //} 
            } else {
                $getthisstyle = get_post_meta( $feuaform->ID, 'feua_style_id', $post_id );
                
                if ( !empty( $getthisstyle ) && $post_id == $getthisstyle ) {
                    update_post_meta( $feuaform->ID, 'feua_style_id', '' );
                }
                
                if ( !empty( $getthisstyle ) ) {
                    //update_post_meta( $feuaform->ID, 'feua_style_id', $getthisstyle );
                }
            }
        }
    }
    public function render_meta_box_selector( $post ) {
        wp_nonce_field( 'feup_a_style_selector_inner_custom_box', 'feup_a_style_selector_custom_box_nonce' );
        global $current_form;
        // Display the form, using the current value.
        $args = array(
            'post_type'     => array('user_forms','user_logins','user_registrations'),
            'posts_per_page'    => -1
            );
        $currentpostid = get_the_ID();
        
        //wp_die( 'aaaa' );
        $query = new WP_Query( $args );
        echo '<table class="wp-list-table fixed pages widefat">'; 
        echo '<thead>';
        echo '<tr>';
        echo '<th class="manage-column">' . __('Frontend user forms') . '</th>';
        echo '<th class="manage-column different-style"><input type="checkbox" id="select_all"/><label for="select_all">' . __('Select all') . '</label></th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody class="feuastyle_body_select_all">';
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) : $query->the_post(); 
            $feuastylehas = get_post_meta( get_the_ID(), 'feua_style_id', true ); 
            ?>
            <tr>
                <td>
                    <label for="<?php echo feua_style_the_slug();  ?>"><?php the_title(); ?></label>
                </td>
                <td>
                    <input type="checkbox" id="<?php echo feua_style_the_slug(); ?>" name="<?php echo feua_style_the_slug(); ?>" value="<?php echo get_the_ID(); ?>" <?php if ( $currentpostid == $feuastylehas ) { $current_form = get_the_ID(); echo 'checked'; } ?>  /> 
                    <?php  if ( $currentpostid != $feuastylehas && !empty( $feuastylehas ) ) {
                        echo '<p class="description">' . __('Notice: This form allready has a selected style. Checking this one will overwrite the ') . '<a href="' . get_admin_url() . 'post.php?post_type=feua_style&post=' . $feuastylehas . '&action=edit">' . __('other one.') . '</a></p>'; 
                    } ?>
                </td>
            </tr>
            <?php endwhile; wp_reset_postdata();
            //$current_form
            echo '<input type="hidden" id="current_form_id" value="'.$current_form.'" />';
            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<tr><td><p class="description">' . __( 'Please create a form. You can do it by clicking') . '<a href="' . admin_url() . 'admin.php?page=wpfeua-new" target="_blank">' . __(' here') . '</a></p></td></tr></table>';
        }
    }
    public function render_meta_box_image( $post ) {
        $image = get_post_meta( $post->ID, 'feua_style_image_preview', true );
        if ( !empty( $image ) ) {
            echo '<img src="' . plugins_url() . '/frontend-user-style' . $image . '" alt="' . $post->title . '" />';
        } else {
            //here will be the placeholder in case the image is not available
            $image = 'default_form.jpg';
            echo '<img src="' . plugins_url() . '/frontend-user-style/images/' . $image . '" alt="' . $post->title . '" />';
        }
    }
    public function add_meta_box_font_selector( $post_type ) {
        $post_types = array('feua_style');     //limit meta box to certain post types
        if ( in_array( $post_type, $post_types )) {
            add_meta_box(
                'feua_style_meta_box_font_selector'
                ,__( 'Select a Google Font', 'myplugin_textdomain' )
                ,
                array( $this, 'render_font_selector' )
                ,$post_type
                ,'advanced'
                ,'high'
                );
        }
    }
    public function render_font_selector( $post ) {
    }
    public function save_font_id( $post_id ) {
        if ( ! isset( $_POST['feup_a_style_font_custom_box_nonce'] ) )
            return $post_id;
        $nonce = $_POST['feup_a_style_font_custom_box_nonce'];
        if ( ! wp_verify_nonce( $nonce, 'feup_a_style_font_inner_custom_box' ) ) {
            return $post_id;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
        if ( 'page' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }
        if ( isset ( $_POST['feua_style_font_selector'] ) ) {
            update_post_meta( $post_id, 'feua_style_font', $_POST['feua_style_font_selector'] );
        }
    }
}
//gets the slug of a post
function feua_style_the_slug() {
    global $post; 
    $post_data = get_post($post->ID, ARRAY_A);
    $slug = $post_data['post_name'];
    return $slug; 
}
//enques the font
function frontend_enque_selected_font() {
    if ( is_page() || is_single() || is_home() ) {
        global $post;
        
        $frontend_active_styles = frontend_active_styles();
        foreach( $frontend_active_styles as $feuas_id ) {
            if ( $feuas_id ) {
                $fontid     = get_post_meta( $feuas_id, 'feua_style_font', true );
                $googlefont = preg_replace( "/ /", "+", $fontid );
                if( ! empty( $googlefont ) && "none" !== $googlefont )  {
                    wp_register_style( 'googlefont-feuastyle-' . $feuas_id, 'http://fonts.googleapis.com/css?family=' . $googlefont . ':100,200,300,400,500,600,700,800,900&subset=latin,latin-ext,cyrillic,cyrillic-ext,greek-ext,greek,vietnamese', array(), false, 'all' );
                    wp_enqueue_style( 'googlefont-feuastyle-' . $feuas_id );
                }
            }
        }
    }
}
add_action( 'wp_enqueue_scripts', 'frontend_enque_selected_font' );
/**
 * returns the name of the font on the current page/post
 */
function frontend_return_font_name( $feuas_id ) {
    if ( $feuas_id ) {
        $fontname = get_post_meta( $feuas_id, 'feua_style_font', true );
        return ( $fontname );
    }
    return false;
}
/**
 * hides change permalink and view buttons on editing screen
 */
add_action('admin_head', 'frontend_hide_edit_permalinks_on_style_customizer');
function frontend_hide_edit_permalinks_on_style_customizer() {
    $currentScreen = get_current_screen();
    if ( $currentScreen->post_type == 'feua_style' ) { 
        ?>
        <style type="text/css">
        #titlediv {
            margin-bottom: 10px;
        }
        #edit-slug-box, .inline-edit-col-left, .inline-edit-col-right, .view{
            display: none;
        }
        .inline-edit-col-left.feua-quick-edit {
            display: block;
        }
        .inline-edit-feua_style {
            background: #eaeaea;
            padding: 20px 0;
        } 
        </style>
        <?php }
    }
/**
 * Quick edit
 */ 
add_action( 'quick_edit_custom_box', 'manage_wp_posts_qe_bulk_quick_edit_custom_box_frontend', 10, 2 );
function manage_wp_posts_qe_bulk_quick_edit_custom_box_frontend( $column_name, $post_type ) {
    if( $post_type == 'feua_style' && $column_name == 'preview-style' ) {
        switch ( $post_type ) {
            case 'feua_style': ?>
            <fieldset class="inline-edit-col-left feua-quick-edit" style="clear:both">
                <div class="hidden-fields"></div>
                <h4><?php _e( "Activate this template on the following forms:", "feua_style" ); ?></h4>
                <div class="inline-edit-col"> 
                    <span class="data">
                        <?php
                        $args = array( 
                            'post_type'     => array('user_forms','user_logins','user_registrations'),
                            'post_status'       => 'publish',
                            'posts_per_page'    => -1
                            );
                        $forms = new WP_Query( $args );
                        if( $forms->have_posts() ) :
                            while( $forms->have_posts() ) : $forms->the_post();
                        $form_title = get_the_title();
                        $id         = get_the_ID();
                        $form_id    = "form-" . $id;
                        $form_style = get_post_meta( get_the_ID(), 'feua_style_id', true );
                        echo "<p><span class='input-text-wrap'><input type='checkbox' name='form[{$id}]' id='form[{$id}]' data-id='{$id}' data-style='{$form_style}' /><label for='form[{$id}]' style='display:inline'>{$form_title}</label></span></p>";
                        if( ! empty( $form_style ) && $id != $form_style ) {
                            $template  = get_the_title( $form_style );
                            $permalink = admin_url() . "post.php?post={$form_style}&action=edit";
                            echo "<span class='notice'>Notice: This form allready has a selected style. Checking this one will overwrite the <a href='{$permalink}' title='{$template}'>other one</a>.</span>";
                        }
                        endwhile;
                        wp_reset_postdata();
                        endif; ?>
                    </span>
                </div>
                </fieldset><?php
                break;
            }
        }
    }
/**
 * Populate Quick Edit fields
 */
add_action( 'admin_print_scripts-edit.php', 'manage_wp_posts_be_qe_enqueue_admin_scripts_frontend' );
function manage_wp_posts_be_qe_enqueue_admin_scripts_frontend() {
    // if using code as plugin
    wp_enqueue_script( 'manage-wp-posts-using-bulk-quick-edit', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'assets/js/quick.edit.js', array( 'jquery', 'inline-edit-post' ), '', true );
}
/**
 * Save quick edit templates
 */
add_action( 'save_post_feua_style', 'manage_wp_posts_be_qe_save_post_frontend', 10, 2 );
function manage_wp_posts_be_qe_save_post_frontend( $post_id, $post ) {
    // pointless if $_POST is empty (this happens on bulk edit)
    if ( empty( $_POST ) )
        return $post_id;
    // verify quick edit nonce
    if ( isset( $_POST[ '_inline_edit' ] ) && ! wp_verify_nonce( $_POST[ '_inline_edit' ], 'inlineeditnonce' ) )
        return $post_id;
    // don't save for autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return $post_id;
    // dont save for revisions
    if ( isset( $post->post_type ) && $post->post_type == 'revision' )
        return $post_id;
    if( isset( $_POST['form'] ) ) {
        foreach( $_POST['form'] as $form_id => $value ) {
            update_post_meta( $form_id, 'feua_style_id', $post_id );
        }
    }
    if( isset( $_POST['remove-form'] ) ) {
        foreach( $_POST['remove-form'] as $form_id => $value ) {
            update_post_meta( $form_id, 'feua_style_id', '' );
        }               
    }
} 
add_action( 'wp_ajax_get_current_form', 'get_current_form_callback' );
add_action( 'wp_ajax_nopriv_get_current_form', 'get_current_form_callback' );
function set_feuastyleversion(){
    return "2.2.8";
}
function get_current_form_callback() {
    $id = $_POST['id'];
    $p_type = get_post_type($id);
    if($p_type == 'user_forms') { 
        echo do_shortcode('[wpfeup-add-form id="'.$id.'"]');
    }
    if($p_type == 'user_logins') { 
        echo 'Form Preview is not available for Login and Registration forms.';
    }
    if($p_type == 'user_registrations') { 
        echo 'Form Preview is not available for Login and Registration forms.';
    }
    die();
}
/*************************frontend_metabox end *****************/
function frontend_user_script_style() {
if ( !is_admin() ) {
    wp_enqueue_script('jquery');
    wp_enqueue_style( "feua-style-frontend-responsive-style", plugin_dir_url( __FILE__ ) . "assets/css/responsive.css", false, set_feuastyleversion(), "all");
    wp_enqueue_script( "feua-style-frontend-script", plugin_dir_url( __FILE__ ) . "assets/js/frontend.js", false, set_feuastyleversion());
}
wp_enqueue_style( "feua-style-frontend-style", plugin_dir_url( __FILE__ ) . "assets/css/frontend.css", false, set_feuastyleversion(), "all");
wp_enqueue_style( "font-awesome.min", plugin_dir_url( __FILE__ ) . "assets/css/font-awesome.min.css", false, set_feuastyleversion(), "all");
add_action('render_custom_style', 'feua_style_custom_css_generator');  
}
add_action('init','frontend_user_script_style');
add_action( 'admin_enqueue_scripts', 'feua_style_admin_scripts' );
add_action( 'init', 'feuastyle_load_elements' );
add_action( 'restrict_manage_posts', 'feua_style_add_taxonomy_filters' );
add_action( 'publish_feua_style',  'feua_style_set_style_category_on_publish', 10, 2 );
add_action( 'add_custom_class_toform', 'feup_style_add_class' );
// add_filter('manage_feua_style_posts_columns', 'feua_style_event_table_head');
add_action( 'manage_feua_style_posts_custom_column', 'feua_style_event_table_content', 10, 2 );
function feua_style_event_table_content( $column_name, $post_id ) {
    //    feua_style_image_preview
    if ( $column_name == 'preview-style' ) {
        $img_src = get_post_meta( $post_id, 'feua_style_image_preview', true );
        echo "<a href='".admin_url() ."post.php?post=".$post_id."&action=edit"."'><span class='thumb-preview'><img src='" . plugins_url() ."/"."frontend-user-style". ( empty( $img_src ) ? "/images/default_form.jpg" : $img_src ) . "' alt='".get_the_title( $post_id )."' title='".get_the_title( $post_id )."'/><div class='previewed-img'><img src='" . plugins_url() ."/"."frontend-user-style". ( empty( $img_src ) ? "/images/default_form.jpg" : $img_src ) . "' alt='".get_the_title( $post_id )."' title='Edit ".get_the_title( $post_id )." Style'/></div></span></a>"  ;
    }
}
/**
 * Reset the feua_style_cookie option
 */
function feua_style_deactivate() {
    update_option( 'feua_style_cookie', false );
    update_option( 'feua_style_add_categories', 0 );
}
register_deactivation_hook( __FILE__, 'feua_style_deactivate' );
/*
 * Function created for deactivated Contact Form 7 Designer & Frontend User Pro Skins.
 * This is because styles of that plugin is in conflict with ours. 
 * No one should add an id in the html tag.
 */
function deactivate_frontend_user_designer_plugin() {
    //designer
    if ( is_plugin_active('frontend-user-pro-designer/feua-styles.php') ) {
        deactivate_plugins('frontend-user-pro-designer/feua-styles.php');
        add_action( 'admin_notices', 'feua_designer_deactivation_notice' );
    }
    //skins
    if ( is_plugin_active('feua-skins-beta/index.php') ) {
        deactivate_plugins('feua-skins-beta/index.php');
        add_action( 'admin_notices', 'feua_skins_deactivation_notice' );
    }
}
add_action('admin_init', 'deactivate_frontend_user_designer_plugin');
/*
 * notice for the user
 */
function feua_designer_deactivation_notice() { ?>
<div class="error">
    <p>You cannot activate FEUPA Designer while FEUPA Style is activated!</p>
</div>
<?php }
/*
 * notice for the user
 */
function feua_skins_deactivation_notice() { ?>
<div class="error">
    <p>You cannot activate FEUPA Skins while FEUPA Style is activated!</p>
</div>
<?php }
/*
 * kiwi1 helper function. using print_r with styles and pre tag. it can be used outside the plugin too.
 */
function kiwi1($var) {
    global $current_user;
    get_currentuserinfo();
    $myid = $current_user->ID;
    if (is_user_logged_in() && $myid == 1) {
        echo '<pre class="kiwi1">';
        print_r($var);
        echo '</pre>';
    }
    echo '<style type="text/css">.kiwi1{background:#9DAE5C;color:#0000;}</style>';
}