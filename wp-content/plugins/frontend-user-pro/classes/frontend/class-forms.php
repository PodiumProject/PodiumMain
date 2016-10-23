<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class USER_Forms {
    static $meta_key = 'user-form';
    static $separator = '| ';
    static $config_id = '_user_form_id';
    function __construct() {
        // new shortcodes added in 2.2
        add_action( 'admin_enqueue_scripts', array($this, 'enqueue_script') );
        add_action( 'wp_enqueue_scripts', array($this, 'enqueue_script') );
        add_action( 'admin_init', array( $this, 'submit_registration_form' ) );
        add_shortcode( 'wpfeup-register', array( $this, 'render_registration_form' ) );
        add_shortcode( 'wpfeup-login', array( $this, 'render_login_form' ) );
        add_shortcode( 'wpfeup-add-form', array($this, 'add_post_shortcode') );
        add_shortcode( 'wpfeup-edit-form', array($this, 'edit_post_shortcode') );
        add_shortcode( 'wpfeup-dashboard', array($this, 'dashboard_shortcode') );
        add_shortcode( 'wpfeup-profile-form', array( $this, 'render_registration_form' ) );
        add_action( 'wp_ajax_user_submit_post', array( $this, 'submit_post' ) );
        add_action( 'wp_ajax_nopriv_user_submit_post', array( $this, 'submit_post' ) );
        
        add_action( 'wp_ajax_user_submit_registration', array( $this, 'submit_registration_form' ) );
        add_action( 'wp_ajax_nopriv_user_submit_registration', array( $this, 'submit_registration_form' ) );
        add_action( 'wp_ajax_user_submit_login', array( $this, 'submit_login_form' ) );
        add_action( 'wp_ajax_nopriv_user_submit_login', array( $this, 'submit_login_form' ) );
         // draft
        add_action( 'wp_ajax_user_draft_post', array($this, 'draft_post') ); 
        add_action( 'wp_ajax_user_delete_avatar', array($this, 'delete_avatar_ajax') );
        add_action( 'wp_ajax_user_delete_image', array($this, 'delete_image_ajax') );
        add_action( 'wp_ajax_user_delete_featured', array($this, 'delete_featured_ajax') );
        add_action( 'wp_ajax_user_delete_file', array($this, 'delete_file_ajax') );
        add_action( 'wp_ajax_register_form_func', array( $this, 'register_form_func' ) );
        add_action( 'wp_ajax_nopriv_register_form_func', array( $this, 'register_form_func' ) );
        
    }
    function register_form_func() {
        $form_id = $_POST['form_id'];
        $form_setting = get_post_meta($form_id , 'user_form_settings' ,true);
        $page_id = $form_setting['register_page'];
        if($page_id){
            $page_url = get_permalink( $page_id);
            echo "<a href='".$page_url."'>Register</a>";
        }
        exit;
    }
    function enqueue_script() {
        global $pagenow;
        if ( !in_array( $pagenow, array('profile.php', 'post-new.php', 'post.php') ) ) {
            return;
        }
        $path = plugins_url( '', dirname( __FILE__ ) );
        $scheme = is_ssl() ? 'https' : 'http'; 
    }
    function delete_avatar_ajax(){
        $user_id = get_current_user_id();
        $avatar = get_user_meta( $user_id, 'user_avatar', true );
        if ( $avatar ) {
            $upload_dir = wp_upload_dir();
            $full_url = str_replace( $upload_dir['baseurl'],  $upload_dir['basedir'], $avatar );
            if ( file_exists( $full_url ) ) {
                unlink( $full_url );
                delete_user_meta( $user_id, 'user_avatar' );
            }
        }
        die();
    }
    function delete_file_ajax(){
        $id = $_POST['id'];
        $id = explode("_", $id);
        // echo $id[1]."****<br>";
        if ($id[1] == 'user') {
            $user_id = get_current_user_id();
            $avatar = get_user_meta( $user_id, 'file_upload', true );
            $image = unserialize($avatar);
            $i_no =  $id[2];
            unset($image[$i_no]);
            $imp = implode(',', $image);
            $im = explode(",", $imp);
            $im = serialize($im);
            update_user_meta($user_id , 'file_upload' ,$im);
            // echo "delet user image";
        }elseif($id[1] == 'post')
        {
            $post_id = $_POST['get'];
            $gallery = get_post_meta( $post_id, 'file_upload', true );
            $image = unserialize($gallery);
            $i_no =  $id[2];
            unset($image[$i_no]);
            $imp = implode(',', $image);
            $im = explode(",", $imp);
            $im = serialize($im);
            update_post_meta($post_id , 'image_upload' ,$im);
        }
        
       // delete_user_meta( $user_id, 'image_upload' );
        die();
    }
    function delete_image_ajax(){
        $id = $_POST['id'];
        $id = explode("_", $id);
        // echo $id[1]."****<br>";
        if ($id[1] == 'user') {
            $user_id = get_current_user_id();
            $avatar = get_user_meta( $user_id, 'image_upload', true );
            $image = unserialize($avatar);
            $i_no =  $id[2];
            unset($image[$i_no]);
            $imp = implode(',', $image);
            $im = explode(",", $imp);
            $im = serialize($im);
            update_user_meta($user_id , 'image_upload' ,$im);
            // echo "delet user image";
        }elseif($id[1] == 'post')
        {
            $post_id = $_POST['get'];
            $gallery = get_post_meta( $post_id, 'image_upload', true );
            $image = unserialize($gallery);
            $i_no =  $id[2];
            unset($image[$i_no]);
            $imp = implode(',', $image);
            $im = explode(",", $imp);
            $im = serialize($im);
            update_post_meta($post_id , 'image_upload' ,$im);
        }
        
       // delete_user_meta( $user_id, 'image_upload' );
        die();
    }
    function delete_featured_ajax(){
        $id = $_POST['id'];
        delete_post_thumbnail($id);
        die();
    }
    function dashboard_shortcode($atts) 
    {
        extract( shortcode_atts( array('post_type' => 'post'), $atts ) );
        ob_start();
        if ( is_user_logged_in() ) 
        {
            $this->post_listing( $post_type );
        } else {
            // $message = user_get_option( 'un_auth_msg', 'user_dashboard' );
            // if ( empty( $message ) ) {
                $msg = sprintf( __( "This page is restricted. Please %s to view this page.", 'user' ), wp_loginout( '', false ) );
                echo apply_filters( 'user_dashboard_unauth', $msg, $post_type );
            // } else {
            //     echo $message;
            // }
        }
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
    function post_listing( $post_type ) {
        global $wpdb, $userdata, $post;
        $userdata = get_userdata( $userdata->ID );
        $pagenum = isset( $_GET['pagenum'] ) ? intval( $_GET['pagenum'] ) : 1;
        //delete post
        if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == "del" ) {
            $this->delete_post();
        }
        //show delete success message
        if ( isset( $_GET['msg'] ) && $_GET['msg'] == 'deleted' ) {
            echo '<div class="success">' . __( 'Post Deleted', 'frontend_user_pro' ) . '</div>';
        }
        
        $per_page = FRONTEND_USER()->helper->get_option( 'user-post-per-page', false );
        if (!$per_page) {
            $per_page = 10;
        }
        $access = FRONTEND_USER()->helper->get_option( 'user-post-access', false );
        if ($access == 1) {
             $author = get_current_user_id();
         }else{
            $author = '';
        }
        $args = array(
            'author' => $author,
            'post_status' => array('draft', 'future', 'pending', 'publish', 'private' ),
            'post_type' => $post_type,
            'posts_per_page' => $per_page,
            'paged' => $pagenum
        );
        $original_post = $post;
        $dashboard_query = new WP_Query( apply_filters( 'user_dashboard_query', $args ) );
        $post_type_obj = get_post_type_object( $post_type );
        ?>
        <h2 class="page-head">
            <span class="colour"></span>
        </h2>
        <?php do_action( 'user_dashboard_top', $userdata->ID, $post_type_obj ); ?>
        <?php if ( $dashboard_query->have_posts() ) { 
            $featured_img = FRONTEND_USER()->helper->get_option( 'user-featured-image', false );
            $featured_img_size = 'medium';
            ?>
            <table class="user-table <?php echo $post_type; ?>" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <?php
                        if ( true == $featured_img ) {
                            echo '<th>' .$post_type. __( ' Image', 'frontend_user_pro' ) . '</th>';
                        }
                        ?>
                        <th><?php echo $post_type; ?></th>
                        <th><?php _e( 'Status', 'frontend_user_pro' ); ?></th>
                        <?php do_action( 'user_dashboard_head_col', $args ); ?>
                        <th><?php _e( 'Options', 'frontend_user_pro' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($dashboard_query->have_posts()) {
                        $dashboard_query->the_post();
                        $show_link = !in_array( $post->post_status, array('draft', 'future', 'pending') );
                        ?>
                        <tr>
                            <?php if ( $featured_img == true ) { ?>
                                <td>
                                    <?php
                                    echo $show_link ? '<a href="' . get_permalink( $post->ID ) . '">' : '';
                                    
                                    if ( has_post_thumbnail() ) 
                                    {
                                        the_post_thumbnail( $featured_img_size );
                                    } else 
                                    {
                                        printf( '<img src="%1$s" class="attachment-thumbnail wp-post-image" alt="%2$s" title="%2$s" />', apply_filters( 'user_no_image', plugins_url( '/images/no-image.png', dirname( __FILE__ ) ) ), __( 'No Image', 'frontend_user_pro' ) );
                                    }
                                    
                                    echo $show_link ? '</a>' : '';
                                    ?>
                                </td>
                            <?php } ?>
                            <td>
                                <?php if ( !$show_link ) 
                                { 
                                    the_title(); 
                                } else 
                                { ?>
                                    <a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'frontend_user_pro' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
                                <?php 
                                } ?>
                            </td>
                            <td>
                                <?php $this->user_show_post_status( $post->post_status ); ?>
                            </td>
                            <?php do_action( 'user_dashboard_row_col', $args, $post ); ?>
                            <td>
                                <?php
                                $edit_post = FRONTEND_USER()->helper->get_option( 'user-user-edit', false );
                                if ( $edit_post == true ) 
                                {
                                    $disable_pending_edit = FRONTEND_USER()->helper->get_option( 'user-pending-post-edit', false );
                                    $edit_page = FRONTEND_USER()->helper->get_option( 'user-edit-page', false );
                                    $url = add_query_arg( array('pid' => $post->ID), get_permalink( $edit_page ) );
                                    ?>
                                    <a href="<?php echo wp_nonce_url( $url, 'user_edit' ); ?>"><?php _e( 'Edit', 'frontend_user_pro' ); ?></a>
                                    <?php
                                }
  
                                $delete_post = FRONTEND_USER()->helper->get_option( 'user-user-delete', false );
                                if ( $delete_post == true ) 
                                {
                                    $del_url = add_query_arg( array('action' => 'del', 'pid' => $post->ID) );
                                    ?>
                                    <a href="<?php echo wp_nonce_url( $del_url, 'user_del' ) ?>" onclick="return confirm('Are you sure to delete?');"><span style="color: red;"><?php _e( 'Delete', 'frontend_user_pro' ); ?></span></a>
                                    <?php 
                                } ?>
                            </td>
                        </tr>
                    <?php
                    }
                    wp_reset_postdata();
                    ?>
                </tbody>
            </table>
            <div class="user-pagination">
                <?php
                $pagination = paginate_links( array(
                    'base' => add_query_arg( 'pagenum', '%#%' ),
                    'format' => '',
                    'prev_text' => __( '&laquo;', 'frontend_user_pro' ),
                    'next_text' => __( '&raquo;', 'frontend_user_pro' ),
                    'total' => $dashboard_query->max_num_pages,
                    'current' => $pagenum
                ) );
                if ( $pagination ) 
                {
                    echo $pagination;
                }
                ?>
            </div>
            <?php
        } else 
        {
            printf( __( 'No %s found', 'frontend_user_pro' ), $post_type_obj->label );
            do_action( 'user_dashboard_nopost', $userdata->ID, $post_type_obj );
        }
        do_action( 'user_dashboard_bottom', $userdata->ID, $post_type_obj );
    }
    function edit_post_shortcode( $atts ) {
    	global $userdata;
        
        extract( shortcode_atts( array('post_id' => 0), $atts ) );
        ob_start();
        
        if ( !is_user_logged_in() ) {
            return '<div class="user-info">' . __( 'You are not logged in', 'frontend_user_pro' ) . '</div>';
        }
        if ( !$post_id ) {
            $post_id = isset( $_GET['pid'] ) ? intval( $_GET['pid'] ) : 0;
        }
        
        //is editing enabled?
        
        $user_edit = FRONTEND_USER()->helper->get_option( 'user-user-edit', false );
        // echo $user_edit."****";
        if ( $user_edit != 1 ) {
            return '<div class="user-info">' . __( 'Post Editing is disabled', 'frontend_user_pro' ) . '</div>';
        }
        
        $curpost = get_post( $post_id );
        // echo "<pre>";
        // print_r($curpost);
        // echo "</pre>";
        if ( !$curpost ) {
            return '<div class="user-info">' . __( 'Invalid post', 'frontend_user_pro' );
        }
 
        //has permission?
        if ( !current_user_can( 'delete_others_posts' ) && ( $userdata->ID != $curpost->post_author ) ) {
            return '<div class="user-info">' . __( 'You are not allowed to edit', 'frontend_user_pro' ) . '</div>';
        }
        $form_id = get_post_meta( $post_id, self::$config_id, true );
        $form_settings = get_post_meta( $form_id, 'user_form_settings', true );
        
        // fallback to default form
        if ( !$form_id ) {
            // $form_id = user_get_option( 'default_post_form', 'user_general' );
            return '<div class="user-info">' . __( 'You are not Using any Form to edit', 'frontend_user_pro' ) . '</div>';
        }
        if ( !$form_id ) {
            return '<div class="user-info">' . __( "I don't know how to edit this post, I don't have the form ID", 'frontend_user_pro' ) . '</div>';
        }
        
        $disable_pending_edit = $user_edit = FRONTEND_USER()->helper->get_option( 'user-user-edit', false );
        
        if ( $curpost->post_status == 'pending' && $disable_pending_edit == 0 ) {
            return '<div class="user-info">' . __( 'You can\'t edit a post while in pending mode.', 'frontend_user_pro' );
        }
        if ( isset( $_GET['msg'] ) && $_GET['msg'] == 'post_updated' ) {
            echo '<div class="user-success">';
            echo $form_settings['update_message'];
            echo '</div>';
        }
        $this->render_form1( $form_id, $post_id );
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
    function user_show_post_status( $status ) {
        if ( $status == 'publish' ) 
        {
            $title = __( 'Live', 'frontend_user_pro' );
            $fontcolor = '#33CC33';
        } else if ( $status == 'draft' ) 
        {
            $title = __( 'Offline', 'frontend_user_pro' );
            $fontcolor = '#bbbbbb';
        } else if ( $status == 'pending' ) 
        {
            $title = __( 'Awaiting Approval', 'frontend_user_pro' );
            $fontcolor = '#C00202';
        } else if ( $status == 'future' ) 
        {
            $title = __( 'Scheduled', 'frontend_user_pro' );
            $fontcolor = '#bbbbbb';
        } else if ( $status == 'private' ) 
        {
            $title = __( 'Private', 'frontend_user_pro' );
            $fontcolor = '#bbbbbb';
        }
        $show_status = '<span style="color:' . $fontcolor . ';">' . $title . '</span>';
        echo apply_filters( 'user_show_post_status', $show_status, $status );
    }
    function submit_post() {   
        
        check_ajax_referer( 'user_form_add' );
        @header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
        $form_id = isset( $_POST['form_id'] ) ? intval( $_POST['form_id'] ) : 0;
        $form_vars = $this->get_input_fields( $form_id );
        $form_settings = get_post_meta( $form_id, 'user_form_settings', true );
        list( $post_vars, $taxonomy_vars, $meta_vars ) = $form_vars;
        // don't check captcha on post edit
        if ( !isset( $_POST['post_id'] ) ) {
            // search if rs captcha is there
            if ( $this->search_array( $post_vars, 'input_type', 'really_simple_captcha' ) ) 
            {
                $this->validate_rs_captcha();
            }
            // check recaptcha
            if ( $this->search_array( $post_vars, 'input_type', 'recaptcha' ) ) 
            {
                $this->validate_re_captcha();
            }
        }
        $is_update = false;
        $post_author = null;
        $default_post_author ='admin';
        // Guest Stuffs: check for guest post
        if ( !is_user_logged_in() ) 
        {
            if ( $form_settings['guest_post'] == 'true' ) 
            {
                if ($form_settings['guest_details'] == 'false')
                {
                    $post_author = $form_settings['assign_role'];
                }else
                {
                    $guest_name = trim( $_POST['guest_name'] );
                    $guest_email = trim( $_POST['guest_email'] );
                // is valid email?
                    if ( !is_email( $guest_email ) ) 
                    {
                        $this->signal_error( __( 'Invalid email address.', 'frontend_user_pro' ) );
                    }
                // check if the user email already exists
                    $user = get_user_by( 'email', $guest_email );
                    if ( $user ) 
                    {
                        $post_author = $user->ID;
                    } else 
                    {
                    // user not found, lets register him
                    // username from email address
                        $username = $this->guess_username( $guest_email );
                        $user_pass = wp_generate_password( 12, false );
                        $user_id = wp_create_user( $username, $user_pass, $guest_email );
                    // if its a success and no errors found
                        if ( $user_id && !is_wp_error( $user_id ) ) 
                        {
                        update_user_option( $user_id, 'default_password_nag', true, true ); //Set up the Password change nag.
                        wp_new_user_notification( $user_id, $deprecated = null, $user_pass );
                        // update display name to full name
                        wp_update_user( array('ID' => $user_id, 'display_name' => $guest_name) );
                        $post_author = $user_id;
                    } else 
                    {
                        //something went wrong creating the user, set post author to the default author
                        $post_author = $default_post_author;
                    }
                }
            }
                
                // guest post is enabled and details are off
            } 
            // the user must be logged in already
        } else 
        {
            $post_author = get_current_user_id();
        }
        $postarr = array(
            'post_type' => $form_settings['post_type'],
            'post_status' => $form_settings['post_status'],
            'post_author' => $post_author,
            'post_title' => isset( $_POST['post_title'] ) ? trim( $_POST['post_title'] ) : '',
            'post_content' => isset( $_POST['post_content'] ) ? trim( $_POST['post_content'] ) : '',
            'post_excerpt' => isset( $_POST['post_excerpt'] ) ? trim( $_POST['post_excerpt'] ) : '',
        );
        // if ( isset( $_POST['category'] ) ) 
        // {
        //     $category = $_POST['category'];
        //     $postarr['post_category'] = is_array( $category ) ? $category : array($category);
        // }
        
        if ( isset( $_POST['tags'] ) ) 
        {
            $postarr['tags_input'] = explode( ',', $_POST['tags'] );
        }
        // if post_id is passed, we update the post
        if ( isset( $_POST['post_id'] ) ) 
        {
            $is_update = true;
            $postarr['ID'] = $_POST['post_id'];
            $postarr['post_date'] = $_POST['post_date'];
            $postarr['comment_status'] = $_POST['comment_status'];
            $postarr['post_author'] = $_POST['post_author'];
            
            if ( $form_settings['edit_post_status'] == '_nochange') {
                $postarr['post_status'] = get_post_field( 'post_status', $_POST['post_id'] );
            } else {
                $postarr['post_status'] = $form_settings['edit_post_status'];
            }
            
        } else 
        {
            if ( isset( $form_settings['comment_status'] ) ) {
                $postarr['comment_status'] = $form_settings['comment_status'];
            }
        }
        // check the form status, it might be already a draft
        // in that case, it already has the post_id field
        // so, user's add post action/filters won't work for new posts
        if ( isset( $_POST['user_form_status'] ) && $_POST['user_form_status'] == 'new' ) 
        {
            $is_update = false;
        }
        
        // set default post category if it's not been set yet and if post type supports
        // if ( !isset( $postarr['post_category'] ) && isset( $form_settings['default_cat'] ) && is_object_in_taxonomy( $form_settings['post_type'], 'category' ) ) 
        // {
        //     $postarr['post_category'] = array( $form_settings['default_cat'] );
        // }
        // validation filter
        if ( $is_update ) 
        {
            $error = apply_filters( 'user_update_post_validate', '' );
        } else 
        {
            $error = apply_filters( 'user_add_post_validate', '' );
        }
        if ( !empty( $error ) ) 
        {
            $this->signal_error( $error );
        }
        // ############ It's Time to Save the World ###############
        if ( $is_update ) 
        {
            $postarr = apply_filters( 'user_update_post_args', $postarr, $form_id, $form_settings, $form_vars );
        } else 
        {
            $postarr = apply_filters( 'user_add_post_args', $postarr, $form_id, $form_settings, $form_vars );
        }
        $post_id = wp_insert_post( $postarr );
        // wp_set_post_categories( $post_id, $postarr['post_category'], true );
        // add_post_meta($post_id , 'form_id' , $form_id );
        if ( $post_id ) 
        {   
            // echo "<pre>";
            // print_r($post_id);
            // echo "</pre>";
            if(array_key_exists('featured_image', $_POST)) {
                $featured_img = $_POST['featured_image'][0];
                // echo $featured_img;
                // add_post_meta($post_id, '_thumbnail_id', $featured_img);
                // update_post_meta($post_id, '_thumbnail_id', $featured_img);
                set_post_thumbnail( $post_id, $featured_img );
            }
            $this->post_create_user_notication($post_id , $form_id);
            $post = get_post($post_id);
            $current_user = wp_get_current_user();
            $to = $current_user->user_email;
            $name = $current_user->display_name;
            $content = $post->post_content;
            $content = apply_filters('the_content', $content);
            $content = str_replace(']]>', ']]&gt;', $content);
            $mail_body = "Hi ".$name." ,\n\n";
            $mail_body .= "A new post has been created in your site <a href='".site_url()."' >".site_url()."</a>\n\n";
            $mail_body .= "Here is the details:\n\n\n\n";
            $mail_body .= "Post Title: ".$post->post_title."\n\n";
            $mail_body .= "Content: ".$content."\n\n";
            $mail_body .= "Author: ".$current_user->user_login."\n\n";
            wp_mail( $to, $form_settings['notification']['edit_subject'], $mail_body );
            self::update_post_meta($meta_vars, $post_id);
            // set the post form_id for later usage
            update_post_meta( $post_id, self::$config_id, $form_id );
            // save post formats if have any
            if ( isset( $form_settings['post_format'] ) && $form_settings['post_format'] != '0' ) 
            {
                if ( post_type_supports( $form_settings['post_type'], 'post-formats' ) ) 
                {
                    set_post_format( $post_id, $form_settings['post_format'] );
                }
            }
            // find our if any images in post content and associate them
            if ( !empty( $postarr['post_content'] ) ) 
            {
                $dom = new DOMDocument();
                $dom->loadHTML( $postarr['post_content'] );
                $images = $dom->getElementsByTagName( 'img' );
                if ( $images->length ) 
                {
                    foreach ($images as $img) 
                    {
                        $url = $img->getAttribute( 'src' );
                        $url = str_replace(array('"', "'", "\\"), '', $url);
                        $attachment_id = self::user_get_attachment_id_from_url( $url );
                        if ( $attachment_id ) 
                        {
                            self::user_associate_attachment( $attachment_id, $post_id );
                        }
                    }
                }
            }
            // save any custom taxonomies
            $woo_attr = array();
            foreach ($taxonomy_vars as $taxonomy) 
            {
                if ( isset( $_POST[$taxonomy['name']] ) ) 
                {
                    if ( is_object_in_taxonomy( $form_settings['post_type'], $taxonomy['name'] ) ) 
                    {
                        $tax = $_POST[$taxonomy['name']];
                        // if it's not an array, make it one
                        if ( !is_array( $tax ) ) 
                        {
                            $tax = array($tax);
                        }
                        if ( $taxonomy['type'] == 'text' ) 
                        {
                            $hierarchical = array_map( 'trim', array_map( 'strip_tags', explode( ',', $_POST[$taxonomy['name']] ) ) );
                            wp_set_object_terms( $post_id, $hierarchical, $taxonomy['name'] );
                            // woocommerce check
                            if ( isset( $taxonomy['woo_attr']) && $taxonomy['woo_attr'] == 'yes' ) {
                                $woo_attr[sanitize_title( $taxonomy['name'] )] = $this->woo_attribute( $taxonomy );
                            }
                        } else 
                        {
                            if ( is_taxonomy_hierarchical( $taxonomy['name'] ) ) 
                            {
                                wp_set_object_terms( $post_id, $_POST[$taxonomy['name']], $taxonomy['name'] );
                                
                                // woocommerce check
                                if ( isset( $taxonomy['woo_attr']) && $taxonomy['woo_attr'] == 'yes' ) 
                                {
                                    $woo_attr[sanitize_title( $taxonomy['name'] )] = $this->woo_attribute( $taxonomy );
                                }
                            } else 
                            {
                                if ( $tax ) 
                                {
                                    $non_hierarchical = array();
                                    foreach ($tax as $value) 
                                    {
                                        $term = get_term_by( 'name', $value, $taxonomy['name'] );
                                        if ( $term && !is_wp_error( $term ) ) 
                                        {
                                            $non_hierarchical[] = $term->name;
                                        }
                                    }
                                    wp_set_object_terms( $post_id, $non_hierarchical, $taxonomy['name'] );
                                }
                            } // hierarchical
                        } // is text
                    } // is object tax
                } // isset tax
            }
            // if a woocommerce attribute
            if ( $woo_attr ) 
            {
                update_post_meta($post_id, '_product_attributes', $woo_attr);
            }
            if ( $is_update ) 
            {
                // plugin API to extend the functionality
                do_action( 'user_edit_post_after_update', $post_id, $form_id, $form_settings, $form_vars );
                //send mail notification
                if ( $form_settings['notification']['edit'] == 'on' ) {
                    $mail_body = $this->prepare_mail_body( $form_settings['notification']['edit_body'], $post_author, $post_id );
                    wp_mail( $form_settings['notification']['edit_to'], $form_settings['notification']['edit_subject'], $mail_body );
                }
            } else 
            {
                // plugin API to extend the functionality
                do_action( 'user_add_post_after_insert', $post_id, $form_id, $form_settings, $form_vars );
                // send mail notification
                if ( $form_settings['notification']['new'] == 'on' ) 
                {
                    $mail_body = $this->prepare_mail_body( $form_settings['notification']['new_body'], $post_author, $post_id );
                    wp_mail( $form_settings['notification']['new_to'], $form_settings['notification']['new_subject'], $mail_body );
                }
            }
            //redirect URL
            $show_message = false;
            $redirect_to = false;
            if ( $is_update ) 
            {
                if ( $form_settings['edit_redirect_to'] == 'page' ) 
                {
                    $redirect_to = get_permalink( $form_settings['edit_page_id'] );
                } elseif ( $form_settings['edit_redirect_to'] == 'url' ) 
                {
                    $redirect_to = $form_settings['edit_url'];
                } elseif ( $form_settings['edit_redirect_to'] == 'same' ) 
                {
                    $redirect_to = add_query_arg( array(
                        'pid' => $post_id,
                        '_wpnonce' => wp_create_nonce('user_edit'),
                        'msg' => 'post_updated'
                         ), get_permalink( $_POST['page_id'] )
                    );
                } else 
                {
                    $redirect_to = get_permalink( $post_id );
                }
            } else 
            {
                if ( $form_settings['redirect_to'] == 'page' ) 
                {
                    $redirect_to = get_permalink( $form_settings['page_id'] );
                } elseif ( $form_settings['redirect_to'] == 'url' ) 
                {
                    $redirect_to = $form_settings['url'];
                } elseif ( $form_settings['redirect_to'] == 'same' )
                {
                    $show_message = true;
                } else 
                {
                    $redirect_to = get_permalink( $post_id );
                }
            }
            // send the response
            $response = array(
                'success' => true,
                'redirect_to' => $redirect_to,
                'show_message' => $show_message,
                'message' => $form_settings['message']
            );
            if ( $is_update ) 
            {
                $response = apply_filters( 'user_edit_post_redirect', $response, $post_id, $form_id, $form_settings );
            } else 
            {
                $response = apply_filters( 'user_add_post_redirect', $response, $post_id, $form_id, $form_settings );
            }
            echo json_encode( $response );
            exit;
        }
        $this->signal_error( __( 'Something went wrong', 'frontend_user_pro' ) );
    }
    function draft_post() {
        check_ajax_referer( 'user_form_add' );
        @header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
        $form_id = isset( $_POST['form_id'] ) ? intval( $_POST['form_id'] ) : 0;
        $form_vars = $this->get_input_fields( $form_id );
        $form_settings = get_post_meta( $form_id, 'user_form_settings', true );
        list( $post_vars, $taxonomy_vars, $meta_vars ) = $form_vars;
        $postarr = array(
            'post_type' => $form_settings['post_type'],
            'post_status' => 'draft',
            'post_author' => get_current_user_id(),
            'post_title' => isset( $_POST['post_title'] ) ? trim( $_POST['post_title'] ) : '',
            'post_content' => isset( $_POST['post_content'] ) ? trim( $_POST['post_content'] ) : '',
            'post_excerpt' => isset( $_POST['post_excerpt'] ) ? trim( $_POST['post_excerpt'] ) : '',
        );
        if ( isset( $_POST['category'] ) ) {
            $category = $_POST['category'];
            $postarr['post_category'] = is_array( $category ) ? $category : array($category);
        }
        if ( isset( $_POST['tags'] ) ) {
            $postarr['tags_input'] = explode( ',', $_POST['tags'] );
        }
        // if post_id is passed, we update the post
        if ( isset( $_POST['post_id'] ) ) {
            $is_update = true;
            $postarr['ID'] = $_POST['post_id'];
            $postarr['comment_status'] = 'open';
        }
        $post_id = wp_insert_post( $postarr );
        if ( $post_id ) {
            $this->update_post_meta($meta_vars, $post_id);
            // set the post form_id for later usage
            update_post_meta( $post_id, self::$config_id, $form_id );
            // save post formats if have any
            if ( isset( $form_settings['post_format'] ) && $form_settings['post_format'] != '0' ) {
                if ( post_type_supports( $form_settings['post_type'], 'post-formats' ) ) {
                    set_post_format( $post_id, $form_settings['post_format'] );
                }
            }
            // save any custom taxonomies
            foreach ($taxonomy_vars as $taxonomy) {
                if ( isset( $_POST[$taxonomy['name']] ) ) {
                    if ( is_object_in_taxonomy( $form_settings['post_type'], $taxonomy['name'] ) ) {
                        $tax = $_POST[$taxonomy['name']];
                        // if it's not an array, make it one
                        if ( !is_array( $tax ) ) {
                            $tax = array($tax);
                        }
                        wp_set_post_terms( $post_id, $_POST[$taxonomy['name']], $taxonomy['name'] );
                    }
                }
            }
        }
        echo json_encode( array(
            'post_id' => $post_id,
            'action' => $_POST['action'],
            'date' => current_time( 'mysql' ),
            'post_author' => get_current_user_id(),
            'comment_status' => get_option('default_comment_status'),
            'url' => add_query_arg( 'preview', 'true', get_permalink( $post_id)  )
        ) );
        exit;
    }
    function post_create_user_notication($post_id , $form_id) 
    {
        $form_setting = get_post_meta($form_id , 'user_form_settings' , true);
        if ($form_setting) 
        {
            if ($form_setting['notification']['create'] == 'on') 
            {
                $post = get_post($post_id);
                $user_id=$post->post_author;
                $user = get_userdata( $user_id );
                $title   = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
                $message = $this->prepare_mail_body( $form_setting['notification']['create_body'], $user_id, $post_id );;
                $from_email = get_option( 'admin_email' ); 
                $headers = 'From: '.$from_email;
                $to = $user->user_email;
                $subject = $form_setting['notification']['create_subject'];
                mail( $to, $subject, $message, $headers);
            }
        }
    }
    function prepare_mail_body( $content, $user_id, $post_id ) {
        $user = get_user_by( 'id', $user_id );
        $post = get_post( $post_id );
        $post_field_search = array( '%post_title%', '%post_content%', '%post_excerpt%', '%tags%', '%category%',
            '%author%', '%sitename%', '%siteurl%', '%permalink%', '%editlink%' );
        $post_field_replace = array(
            $post->post_title,
            $post->post_content,
            $post->post_excerpt,
            get_the_term_list( $post_id, 'post_tag', '', ', '),
            get_the_term_list( $post_id, 'category', '', ', '),
            $user->display_name,
            get_bloginfo( 'name' ),
            home_url(),
            get_permalink( $post_id ),
            admin_url( 'post.php?action=edit&post=' . $post_id )
            );
        $content = str_replace( $post_field_search, $post_field_replace, $content );
        // custom fields
        preg_match_all( '/%custom_([\w-]*)\b%/', $content, $matches);
        list( $search, $replace ) = $matches;
        if ( $replace ) {
            foreach ($replace as $index => $meta_key ) {
                $value = get_post_meta( $post_id, $meta_key );
                $content = str_replace( $search[$index], implode( '; ', $value ), $content );
            }
        }
        return $content;
    }
    function render_form( $type = 'post', $id = false, $read_only = false, $args = array() ) 
    {
        FRONTEND_USER()->setup->enqueue_form_assets();
        switch ( $type ) {
        case 'registration':
            do_action( 'user_render_form_above_registration_form', $id, $read_only, $args );
            echo $this->render_registration_form( $id, $read_only, $args );
            do_action( 'user_render_form_below_registration_form', $id, $read_only, $args );
            break;
        case 'profile':
            do_action( 'user_render_form_above_profile_form', $id, $read_only, $args );
            echo $this->render_profile_form( $id, $read_only, $args );
            do_action( 'user_render_form_below_profile_form', $id, $read_only, $args );
            break;
        case 'login':
            do_action( 'user_render_form_above_login_form', $id, $read_only, $args );
            echo $this->render_login_form( $args );
            do_action( 'user_render_form_below_login_form', $id, $read_only, $args );
            break;  
        }
        // $this->scripts_styles();
    }
    function render_profile_form( $atts ) 
    {
        extract( shortcode_atts( array('id' => -2, 'type' => 'registration', 'read_only' => false, 'args' => array() ), $atts ) );
        FRONTEND_USER()->setup->enqueue_form_assets();
        ob_start();
        if ( $id === -2 || empty( $id ) ) {
            $id = get_current_user_id();
        }
        $form_id = $id;
        if ( !$form_id ){
            _e( 'Profile Form not set in the FES settings', 'frontend_user_pro' );
            return;
        }
        $form_vars = get_post_meta( $form_id, 'user-form', true );
        echo '<h1 class="user-headers" id="user-profile-page-title">'. __( 'Profile', 'frontend_user_pro') . '</h1>';
        if ( ! $form_vars ) {
            _e( 'Profile form has no fields!', 'frontend_user_pro' );
            return;
        }
        if ( isset( $_GET[ 'msg' ] ) && $_GET[ 'msg' ] == 'profile_update' ) {
            echo '<div class="user-success">';
            echo __( 'Updated Successfully', 'frontend_user_pro' );
            echo '</div>';
        }
        $user_id = get_current_user_id();
        // $is_user = FRONTEND_USER()->users->user_is_user( $user_id );
        // $is_admin = FRONTEND_USER()->users->user_is_admin( $user_id );
        // if they are not a user, admin, or in the backend or user != the user being looked at
        // if ( !$is_admin && !is_admin() && !$is_user || ( $user_id !== $id && !is_admin() && !$is_admin ) ) {
        //     _e( 'Access Denied', 'frontend_user_pro' );
        //     return;
        // }
        // is it read only? If so don't make it a form
        if ( !$read_only ) { ?>
        <form class="user-profile-form" action="" method="post">
            <?php }
            ?>
            <div class="user-form">
                <?php
                do_action( 'user_profile_form_top', $form_id, $id, $args );
                $this->render_items( $form_vars, $id, $type = 'profile' , $form_id, $read_only, $args );
                do_action( 'user_profile_form_bottom', $form_id, $id, $args );
                if ( !$read_only ) {
                    $this->submit_button( $form_id, $type = 'profile', $id, $args );
                }
                ?>
            </div>
            <?php
        // is it read only? If so don't make it a form
            if ( !$read_only ) { ?>
        </form>
        <?php }
        $form = ob_get_contents();
        ob_end_clean();
        return $form;
    }
    function add_post_shortcode( $atts ) {
        extract( shortcode_atts( array('id' => 0), $atts ) );
        ob_start();
        $form_settings = get_post_meta( $id, 'user_form_settings', true );
        $info = apply_filters( 'user_addpost_notice', '' );
        $user_can_post = apply_filters( 'user_can_post', 'yes', $id, $form_settings );
        if ( $user_can_post == 'yes' ) {
            $this->render_form1( $id );
        } else {
            echo '<div class="info">' . $info . '</div>';
        }
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
    function submit_button1( $form_id, $form_settings, $post_id ) 
    { ?>
        <fieldset class="user-submit">
            <div class="user-label">
                &nbsp;
            </div>
            <?php wp_nonce_field( 'user_form_add' ); ?>
            <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
            <input type="hidden" name="action" value="user_submit_post">
            <input type="hidden" name="page_id" value="<?php echo get_post() ? get_the_ID() : '0'; ?>">
            <?php
            if ( $post_id ) {
                $cur_post = get_post( $post_id );
                ?>
                <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                <input type="hidden" name="post_date" value="<?php echo esc_attr( $cur_post->post_date ); ?>">
                <input type="hidden" name="comment_status" value="<?php echo esc_attr( $cur_post->comment_status ); ?>">
                <input type="hidden" name="post_author" value="<?php echo esc_attr( $cur_post->post_author ); ?>">
                <input type="submit" name="submit" value="<?php echo $form_settings['update_text']; ?>" />
                <?php } else { ?>
                <input type="submit" name="submit" value="<?php echo $form_settings['submit_text']; ?>" />
                <input type="hidden" name="user_form_status" value="new">
                <?php } ?>
                <?php if ( isset( $form_settings['draft_post'] ) && $form_settings['draft_post'] == 'true' ) { ?>
                <a href="#" class="btn" id="user-post-draft"><?php _e( 'Save Draft', 'frontend_user_pro' ); ?></a>
                <?php } ?>
            </fieldset>
            <?php
    }
    function render_form1( $form_id, $post_id = NULL, $preview = false ) 
    {
        $form_vars = get_post_meta( $form_id, self::$meta_key, true );
        $form_settings = get_post_meta( $form_id, 'user_form_settings', true );
        $clss = '';
        if (is_array($form_settings) && array_key_exists('feup_f_col', $form_settings ) ) {
            $clss = $form_settings['feup_f_col'];
        }
        if ( !is_user_logged_in() && $form_settings['guest_post'] != 'true' ) {
            echo $form_settings['message_restrict'];
            return;
        }
        if ( $form_vars ) 
        { 
            if ( !$preview ) 
            {
            do_action('render_custom_style',$form_id); ?>
                <form class="user-form-add <?php do_action("add_custom_class_toform",$form_id); ?>" action="" method="post">
                <?php } ?>
                <ul class="user-form <?php echo $clss; ?>">
                    <?php
                    if ( !$post_id ) 
                    {
                        do_action( 'user_add_post_form_top', $form_id, $form_settings );
                    } else {
                        do_action( 'user_edit_post_form_top', $form_id, $post_id, $form_settings );
                    }
                    if ( !is_user_logged_in() && $form_settings['guest_post'] == 'true' && $form_settings['guest_details'] == 'true') {
                        $this->guest_fields( $form_settings );
                    }
                    $this->render_items( $form_vars, $post_id, 'post', $form_id, $form_settings );
                    $this->submit_button1( $form_id, $form_settings, $post_id );
                    if ( !$post_id ) {
                        do_action( 'user_add_post_form_bottom', $form_id, $form_settings );
                    } else {
                        do_action( 'user_edit_post_form_bottom', $form_id, $post_id, $form_settings );
                    }
                    ?>
                </ul>
                <?php if ( !$preview ) { ?>
                </form>
            <?php } ?>
            <?php
        } //endif
    }
    function guest_fields( $form_settings ) 
    { ?>
        <fieldset class="user-el name_label">         
            <div class="user-label">
                <label for="user-name_label"><?php echo $form_settings['name_label']; ?>
                    <span class="frontend-required-indicator">*</span>
                </label>
                <br>
            </div>
            <div class="user-fields">
                <input type="text" required="required" data-required="yes" data-type="text" name="guest_name" value="" size="40">
            </div>
        </fieldset>
        <fieldset class="user-el email_label">         
            <div class="user-label">
                <label for="user-email_label"><?php echo $form_settings['email_label']; ?>
                    <span class="frontend-required-indicator">*</span>
                </label>
                <br>
            </div>
            <div class="user-fields">
                <input type="email" required="required" data-required="yes" data-type="email" name="guest_email" value="" size="40">
            </div>
        </fieldset>
        <?php
    }
    function render_login_form( $atts ) 
    {
        extract( shortcode_atts( array('id' => 0, $read_only = false, $args = array()), $atts ) );
        ob_start();
        FRONTEND_USER()->setup->enqueue_form_assets();
        ob_start();
        if ( !is_user_logged_in() ) 
        {
            $form_id = $id;
            if ( !$form_id )
            {
                _e( 'Login Form not set in the USER settings', 'frontend_user_pro' );
                return;
            }
            $form_vars = get_post_meta( $form_id, 'user-form', true );
            $form_settings = get_post_meta( $form_id, 'user_form_settings', true );
            $lost = $form_settings['lostpassword_message'];
            if ( ! $form_vars ) 
            {
                _e( 'Login form has no fields!', 'frontend_user_pro' );
                return;
            }
            do_action( 'user_login_form_before_form', $form_id, $read_only, $args );
            
            do_action('render_custom_style',$form_id);
            ?>
            <form class="user-form-login-form displayform_class1 <?php do_action('add_custom_class_toform',$form_id); ?>"  action="" method="post" autocomplete="off">
            <div class="user-form">
            <p class="user-form-error user-error" style="display: none;"></p>
            <?php
            do_action( 'user_login_form_above_render_items', $form_id, $read_only, $args );
            $this->render_items( $form_vars, $id, $type = 'login', $form_id, $read_only, $args );
            do_action( 'user_login_form_below_render_items', $form_id, $args );
            $this->submit_button( $form_id, $type = 'login', $args );
            do_action( 'user_login_form_below_submit_buttons', $form_id, $read_only, $args );
            ?>
            </div>
            </form>
            <br>
            <?php 
            FRONTEND_USER()->login->user_load_template( 'lost-pass-form.php', $args ); 
            ?>
            <script type="text/javascript">
                (function($){
                    $(document).ready(function(){
                        var form_id = "<?php echo $form_id ?>";
                        $('#user-login-form').css('display','none');
                        $('#user_lost_password_link').on('click',function(e){
                            e.preventDefault();
                            $('#user-login-form').css('display','block');
                            $('#user-login-form').append("<input type='hidden' value='"+form_id+"' name='form_id_field' id='register_form_id'>");
                            $.ajax({
                                type: "post",
                                url: '<?php echo admin_url("/admin-ajax.php"); ?>',
                                data: { 
                                    action: 'register_form_func',
                                    form_id : form_id
                                }, 
                                success: function(data) {
                                    console.log(data);
                                    $('#user-login-form a').after(" | "+data);
                                },
                                error:function(res) {
                                    console.log(res);
                                }
                            }); 
                        });
                    });
                }(jQuery));
            </script>
            <style type="text/css">
            #lostpasswordform:before {
                content: '<?php echo $lost; ?>';
            }
            </style>
            <?php
            do_action( 'user_login_form_after_form', $form_id, $read_only, $args );
        }
        $form = ob_get_contents();
        ob_end_clean();
        return $form;
    }
    function form_id($args) {
        return $args;
    }
    function render_registration_form( $atts ) 
    {   
        extract( shortcode_atts( array('id' => 0, 'type' => 'registration'), $atts ) );
        ob_start();
        $form_vars = get_post_meta( $id, 'user-form', true );
        $form_settings = get_post_meta( $id, 'user_form_settings', true );
        if ( !$form_vars ) {
            return;
        }
        if ( $type == 'profile' ) 
        {
            if ( is_user_logged_in() ) {
                if ( isset( $_GET['msg'] ) && $_GET['msg'] == 'profile_update' ) {
                    echo '<div class="user-success">';
                    echo $form_settings['update_message'];
                    echo '</div>';
                }
                $this->regprofile_edit( $id, $form_vars, $form_settings );
            } else {
                echo '<div class="user-info">' . __( 'Please login to update your profile!', 'frontend_user_pro' ) . '</div>';
            }
        } elseif ( $type == 'registration' ) 
        {
            if ( is_user_logged_in() ) 
            {
                echo '<div class="user-info">' . __( 'You are already logged in!', 'frontend_user_pro' ) . '</div>';
            } else 
            {
                if ( get_option( 'users_can_register' ) != '1' ) {
                    echo '<div class="user-info">';
                    _e( 'User registration is currently not allowed.' );
                    echo '</div>';
                    return;
                }
                    
                $this->profile_edit( $id, $form_vars, $form_settings );
            }
        }
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
    function profile_edit( $form_id, $form_vars, $form_settings ) {
        $id = false;
        $args = array();        
        $ds_form =  get_post_meta($form_id , 'user_form_settings', true);
        do_action('render_custom_style',$form_id);
        ?>
        <form class="user-form-add reg_form_class  <?php do_action("add_custom_class_toform",$form_id); ?>" action="" method="post">
        <?php
        $clss = '';
        if (is_array($ds_form) && array_key_exists('feup_f_col', $ds_form ) ) {
            $clss = $ds_form['feup_f_col'];
        }
        echo '<ul class="user-form '.$clss.'">';
        $this->render_items( $form_vars, get_current_user_id(), 'user', $form_id, $form_settings );
        $this->submit_button( $form_id, 'registration', $id, $args  );
        echo '</ul>';
        echo '</form>';
    }
    function regprofile_edit( $form_id, $form_vars, $form_settings ) {
        $id = false;
        $args = array(); 
        $ds_form1 =  get_post_meta($form_id , 'user_form_settings', true);
        define('__DFROOT__', plugins_url("frontend-user-pro/css") );
        echo '<style type="text/css">' ;
        require_once(__DFROOT__.'/update_registration.css');  
        echo '</style>';       
        do_action('render_custom_style',$form_id);
        ?>
        <form class="user-form-add profile_edit_form <?php do_action("add_custom_class_toform",$form_id); ?>" action="" method="post">
        <?php
        echo '<div class="profile_head">';
        echo get_avatar( get_current_user_id(), 120 );
        echo '</div>';
        $clss = '';
        if (is_array($ds_form1) && array_key_exists('feup_f_col', $ds_form1 ) ) {
            $clss = $ds_form1['feup_f_col'];
        }
        echo '<ul class="user-form profile_content '.$clss.'">';
        $this->render_items( $form_vars, get_current_user_id(), 'user', $form_id, $form_settings );
        $this->submit_button( $form_id, 'registration', $id, $args  );
        echo '</ul>';
        echo '</form>';
    }
    // submit form
    function submit_form( $type = 'post', $id = false, $values = array(), $args = array() ) {
        switch ( $type ) {
        case 'login':
            $this->submit_login_form( $id, $values, $args );
            break;
        case 'registration':
            $this->submit_registration_form( $id, $values, $args );
            break;
        }
    }
    function submit_login_form( $args = array() ) 
    {
        check_ajax_referer( 'user-form-login' );
        @header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
        $url = home_url();
        $form_id       = isset( $_POST[ 'form_id' ] ) ? intval( $_POST[ 'form_id' ] ) : 12;
        $form_vars     = $this->get_input_fields( $form_id );
        $form_settings = get_post_meta( $form_id, 'user_form_settings', true );
        $userform = get_post_meta( $form_id, 'user-form', true );
        list( $user_vars, $taxonomy_vars, $meta_vars ) = $form_vars;
        $user_id  = get_current_user_id();
        $userdata = array(
            'ID' => $user_id
        );
        if ( FRONTEND_USER()->helper->get_option( 'user-login-captcha', false ) )
        {
            if ( $this->search_array( $form_vars, 'input_type', 'recaptcha' ) ) 
            {
                $this->validate_re_captcha();
            }
        }
        if ( $this->search_array( $form_vars, 'input_type', 'really_simple_captcha' ) ) 
        {
            $this->validate_rs_captcha();
        }
        if (array_key_exists('remember_me', $_POST)) {
            if ($_POST['remember_me'] == 1) 
            {
                $month = time() + 2592000;
                setcookie('Username', $_POST['user_login'], $month ,'/');
                setcookie('Password', $_POST['pass1'], $month ,'/');
            }
        }
        
        // user_vars contains the radio toggle
        // meta_vars contains the user_login and password fields
        $userdata[ 'username' ] = sanitize_user( $_REQUEST[ 'user_login' ] );
        $userdata[ 'password' ] = sanitize_text_field( $_REQUEST[ 'pass1' ] );
        if ( !isset( $userdata['username'] ) || !isset( $userdata[ 'password' ] ) ) {
            $this->signal_error( __( 'Please fill out the login form!', 'frontend_user_pro' ) );
        }
        $user = get_user_by( 'login', $userdata[ 'username' ] );
        if(!$user)
        {
            $user = get_user_by( 'email', $userdata[ 'username' ] );
            if ( $user ) 
            {
                $password = wp_check_password( $userdata[ 'password' ] , $user->data->user_pass, $user->ID );
                if ( $password ) 
                {
                    $approve = get_option('feu-approve-user');
                    $approve_user = get_user_meta($user->ID , 'feu-approve-user' , true);
                    $verify_user = get_user_meta($user->ID , 'confirmation_r' , true);
                    if($verify_user !='')
                    {
                        $message = "<strong>ERROR:</strong> Please Check Your Mail id for verification.";
                        $redirect_to = wp_login_url();
                        $approve_usr = 'unapproved';
                        $url = $redirect_to;
                    }elseif($approve)
                    {
                        if (!$approve_user)
                        {
                            $message = "<strong>ERROR:</strong> Your account has to be confirmed by an administrator before you can login.";
                            $url = wp_login_url();
                            $approve_usr = 'unapproved';
                        }elseif (!array_key_exists('wpfeu-send-approve-email', $approve) && $approve['wpfeu-send-approve-email'] != 1 && $verify_user == '') 
                        {
                            // log the new user in
                            wp_set_auth_cookie( $user->ID, true );
                            wp_set_current_user( $user->ID, $userdata[ 'username' ] );
                            do_action( 'wp_login', $userdata[ 'username' ] );
                            do_action( 'user_login_form' );
                            do_action( 'user_user_contact_form_success', $userdata );
                            if ($form_settings) 
                            {
                                if (array_key_exists('redirect_to', $form_settings)) 
                                {
                                    if ( $form_settings['redirect_to'] == 'page') 
                                    { 
                                        $id = $form_settings['page_id'];
                                        $url = get_post_permalink($id);
                                    } else if ( $form_settings['redirect_to'] == 'url' )
                                    { 
                                        $url = $form_settings['url'];
                                    }elseif ($form_settings['redirect_to'] == 'same' ) 
                                    {
                                        $message = true;
                                    }                    
                                }
                                $message = '';
                            }else
                            {
                                $url = home_url();
                            }
                            $approve_usr = '';
                        }elseif ($approve_user == 1 && $verify_user == '') 
                        {
                            // log the new user in
                            wp_set_auth_cookie( $user->ID, true );
                            wp_set_current_user( $user->ID, $userdata[ 'username' ] );
                            do_action( 'wp_login', $userdata[ 'username' ] );
                            do_action( 'user_login_form' );
                            do_action( 'user_user_contact_form_success', $userdata );
                            if ($form_settings) 
                            {
                                if (array_key_exists('redirect_to', $form_settings)) 
                                {
                                    if ( $form_settings['redirect_to'] == 'page') 
                                    { 
                                        $id = $form_settings['page_id'];
                                        $url = get_post_permalink($id);
                                    } else if ( $form_settings['redirect_to'] == 'url' )
                                    { 
                                        $url = $form_settings['url'];
                                    }elseif ($form_settings['redirect_to'] == 'same' ) 
                                    {
                                        $message = true;
                                    }                    
                                }
                                $message = '';
                            }
                            else
                                {
                                    $url = home_url();
                                }
                            $approve_usr = '';
                        }else
                        {
                            $message = "<strong>ERROR:</strong> Your account has to be confirmed by an administrator before you can login.";
                            $url = wp_login_url();
                            $approve_usr = 'unapproved';
                        }
                    }
                    else
                    {
                        // log the new user in
                        wp_set_auth_cookie( $user->ID, true );
                        wp_set_current_user( $user->ID, $userdata[ 'username' ] );
                        do_action( 'wp_login', $userdata[ 'username' ] );
                        do_action( 'user_login_form' );
                        do_action( 'user_user_contact_form_success', $userdata );
                        if ($form_settings) 
                        {
                            if (array_key_exists('redirect_to', $form_settings)) 
                            {
                                if ( $form_settings['redirect_to'] == 'page') 
                                { 
                                    $id = $form_settings['page_id'];
                                    $url = get_post_permalink($id);
                                } else if ( $form_settings['redirect_to'] == 'url' )
                                { 
                                    $url = $form_settings['url'];
                                }elseif ($form_settings['redirect_to'] == 'same' ) 
                                {
                                    $message = true;
                                }                    
                            }
                            $message = '';
                        }
                        else
                        {
                             $url = home_url();
                        }
                        $approve_usr = '';
                    }
                    // echo $message.'-----------------';
                    $response = array(
                        'success' => true,
                        'redirect_to' => $url,
                        'message' => $message,
                        'is_post' => true,
                        'approve' => $approve_usr,
                        
                    );
                    $response = apply_filters( 'user_login_form_success_redirect', $response, $userdata );
                    echo json_encode( $response );
                    exit;
                }
                else 
                {
                    foreach ($userform as $key => $value) 
                    {
                        if($value['name'] == 'password') 
                        {
                            if (isset($value['error_msg'])) {
                                $err = $value['error_msg'];
                            }else{
                                $err = "Enter Password Is worng";
                            }
                            $e_error = $err;
                            //$e_error = $value['error_msg'];
                        }
                    }
                    $this->signal_error( __( $e_error, 'frontend_user_pro' ) );
                }
            }
            else 
            {
                foreach ($userform as $key => $value) 
                {
                    if($value['name'] == 'user_login') 
                    {
                        if (isset($value['error_msg'])) {
                                $err = $value['error_msg'];
                            }else{
                                $err = "Enter User Name Is worng";
                            }
                            $e_error = $err;
                        //$e_error = $value['error_msg'];
                    }
            	}
                $this->signal_error( __( $e_error, 'frontend_user_pro' ) );
            }
        }
        else 
        {
        	if ( $user ) 
       		{
	            $password = wp_check_password( $userdata[ 'password' ] , $user->data->user_pass, $user->ID );
	           	if ( $password ) 
	            {
	                $approve = get_option('feu-approve-user');
	                $approve_user = get_user_meta($user->ID , 'feu-approve-user' , true);
	                $verify_user = get_user_meta($user->ID , 'confirmation_r' , true);
	                if($verify_user !='')
	                {
	                    $message = "<strong>ERROR:</strong> Please Check Your Mail id for verification.";
	                    $redirect_to = wp_login_url();
	                    $approve_usr = 'unapproved';
                        $url = $redirect_to;
	                }elseif($approve)
	                {
	                    if (!$approve_user)
	                    {
	                        $message = "<strong>ERROR:</strong> Your account has to be confirmed by an administrator before you can login.";
	                        $url = wp_login_url();
	                        $approve_usr = 'unapproved';
	                    }elseif (!array_key_exists('wpfeu-send-approve-email', $approve) && $approve['wpfeu-send-approve-email'] != 1 && $verify_user == '') 
	                    {
	                        // log the new user in
	                        wp_set_auth_cookie( $user->ID, true );
	                        wp_set_current_user( $user->ID, $userdata[ 'username' ] );
	                        do_action( 'wp_login', $userdata[ 'username' ] );
	                        do_action( 'user_login_form' );
	                        do_action( 'user_user_contact_form_success', $userdata );
	                        if ($form_settings) 
	                        {
	                            if (array_key_exists('redirect_to', $form_settings)) 
	                            {
	                                if ( $form_settings['redirect_to'] == 'page') 
	                                { 
	                                    $id = $form_settings['page_id'];
	                                    $url = get_post_permalink($id);
	                                } else if ( $form_settings['redirect_to'] == 'url' )
	                                { 
	                                    $url = $form_settings['url'];
	                                }elseif ($form_settings['redirect_to'] == 'same' ) 
	                                {
	                                    $message = true;
	                                }                    
	                            }
	                            $message = '';
	                        }
	                        else
                            {
                                $url = home_url();
                            }
	                        $approve_usr = '';
	                    }elseif ($approve_user == 1 && $verify_user == '') 
                        {
                            // log the new user in
                            wp_set_auth_cookie( $user->ID, true );
                            wp_set_current_user( $user->ID, $userdata[ 'username' ] );
                            do_action( 'wp_login', $userdata[ 'username' ] );
                            do_action( 'user_login_form' );
                            do_action( 'user_user_contact_form_success', $userdata );
                            if ($form_settings) 
                            {
                                if (array_key_exists('redirect_to', $form_settings)) 
                                {
                                    if ( $form_settings['redirect_to'] == 'page') 
                                    { 
                                        $id = $form_settings['page_id'];
                                        $url = get_post_permalink($id);
                                    } else if ( $form_settings['redirect_to'] == 'url' )
                                    { 
                                        $url = $form_settings['url'];
                                    }elseif ($form_settings['redirect_to'] == 'same' ) 
                                    {
                                        $message = true;
                                    }                    
                                }
                                $message = '';
                            }
                            else
                                {
                                    $url = home_url();
                                }
                            $approve_usr = '';
                        }else
	                    {
	                        $message = "<strong>ERROR:</strong> Your account has to be confirmed by an administrator before you can login.";
	                        $url = wp_login_url();
	                        $approve_usr = 'unapproved';
	                    }
	                }else
	                {
                    	// log the new user in
                        wp_set_auth_cookie( $user->ID, true );
                        wp_set_current_user( $user->ID, $userdata[ 'username' ] );
                        do_action( 'wp_login', $userdata[ 'username' ] );
                        do_action( 'user_login_form' );
                        do_action( 'user_user_contact_form_success', $userdata );
                        if ($form_settings) 
                        {
                            if (array_key_exists('redirect_to', $form_settings)) 
                            {
                                if ( $form_settings['redirect_to'] == 'page') 
                                { 
                                    $id = $form_settings['page_id'];
                                    $url = get_post_permalink($id);
                                } else if ( $form_settings['redirect_to'] == 'url' )
                                { 
                                    $url = $form_settings['url'];
                                }elseif ($form_settings['redirect_to'] == 'same' ) 
                                {
                                    $message = true;
                                }                    
                            }
                            $message = '';
                        }
                        else
                        {
                            $url = home_url();
                        }
                        $approve_usr = '';
                	}
                    // echo $message.'-----------------';
	                $response = array(
	                    'success' => true,
	                    'redirect_to' => $url,
	                    'message' => $message,
	                    'is_post' => true,
	                    'approve' => $approve_usr,
	                    
	                );
	                $response = apply_filters( 'user_login_form_success_redirect', $response, $userdata );
	                echo json_encode( $response );
	                exit;
            	}
            	else 
            	{
                	foreach ($userform as $key => $value) 
                	{
                		if($value['name'] == 'password') {
                            if (isset($value['error_msg'])) {
                                $err = $value['error_msg'];
                            }else{
                                $err = "Enter Password Is worng";
                            }
                			$e_error = $err;
                		}
                	}
                	$this->signal_error( __( $e_error, 'frontend_user_pro' ) );
            	}
        	}else
        	{
        		 foreach ($userform as $key => $value) 
                    {
                        if($value['name'] == 'user_email') 
                        {
                            if (isset($value['error_msg'])) {
                                $err = $value['error_msg'];
                            }else{
                                $err = "Enter Email Is worng";
                            }
                            $e_error = $err;
                        }
                    }
        	}
    	}
    }
    function submit_registration_form( $args = array() ) 
    {
        global $frontend_options;
        if ( is_admin() && ( !isset( $_REQUEST[ '_wpnonce' ] ) || !wp_verify_nonce( $_REQUEST[ '_wpnonce' ], 'user-form-registration' ) ) ) {
            return;
        }
        check_ajax_referer( 'user-form-registration' );
        @header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
        $form_id       = isset( $_POST[ 'form_id' ] ) ? intval( $_POST[ 'form_id' ] ) : 0;
        $db_form_id    = FRONTEND_USER()->helper->get_option( 'user-registration-form', false );
        $form_vars     = $this->get_input_fields( $form_id );
        list( $user_vars, $taxonomy_vars, $meta_vars ) = $form_vars;
        $merged_user_meta = array_merge( $user_vars, $meta_vars );
        $form_setting = get_post_meta($form_id,'user_form_settings',true);
        $redirect_to = $_POST['_wp_http_referer'];
        do_action( 'user_pre_process_registration_form', $this, $form_id, $form_vars );
        // if form id is equal to db_form_id
        if ( $form_id != $db_form_id && !is_admin() ) 
        {
            if ($form_setting['redirect_to'] == 'url') 
            {
                $url= $form_setting['url'];
            }elseif($form_setting['redirect_to'] == 'page')
            {
                $ii = $form_setting['page_id'];
                $url = get_post_permalink($ii);
            }elseif($form_setting['redirect_to'] == 'same')
            {
                $message_123 = true;
            }else
            {
               $redirect_to= home_url();
            }
            $response    = array(
                'success' => false,
                'redirect_to' => $url,
                'message' => __( 'Access Denied: '.$form_id.' != '.$db_form_id , 'frontend_user_pro' ),
                'is_post' => true
            );
            echo json_encode( $response );
            exit;
        }
        // if admin side lets get them out of the way
        if ( is_admin() && ! empty( $_REQUEST['is_admin'] ) && '1' == $_REQUEST['is_admin'] ) 
        {
            $user = get_userdata( absint( $_REQUEST[ 'user_id' ] ) );
            if ( ! current_user_can( 'edit_users' ) ) 
            {
                $response    = array(
                    'success' => false,
                    'redirect_to' => admin_url( 'admin.php?page=user-users&user='.$user->ID.'&result=denied&action=edit' ),
                    'message' => __( 'Access denied!' , 'frontend_user_pro' ),
                    'is_post' => true
                );
                $response    = apply_filters( 'user_registration_form_denied_admin_redirect', $response, $user->ID, $form_id );
                do_action('user_registration_form_denied_admin', $user->ID );
                echo json_encode( $response );
                exit;
            }
            $userdata = array();
            if ( $this->search_array( $merged_user_meta, 'name', 'first_name' ) ) 
            {
                $userdata[ 'first_name' ] = sanitize_text_field( $_POST[ 'first_name' ] );
            }
            if ( $this->search_array( $merged_user_meta, 'name', 'last_name' ) ) 
            {
                $userdata[ 'last_name' ] = sanitize_text_field( $_POST[ 'last_name' ] );
            }
            if ( $this->search_array( $merged_user_meta, 'name', 'user_email' ) ) 
            {
                if ( ! empty( $_POST[ 'user_email' ] ) && ! is_email( $_POST[ 'user_email' ] ) )
                {
                    $this->signal_error( __( 'Please enter a valid email!', 'frontend_user_pro' ) );
                }
                elseif( ! empty( $_POST[ 'user_email' ] ) ) 
                {
                    $userdata[ 'user_email' ] = sanitize_email( $_POST[ 'user_email' ] );
                }
            }
            if ( $this->search_array( $merged_user_meta, 'name', 'display_name' ) ) 
            {
                $userdata[ 'display_name' ] = sanitize_text_field( $_POST[ 'display_name' ] );
            }
            if ( $this->search_array( $merged_user_meta, 'name', 'user_url' ) ) 
            {
                if ( isset( $_POST[ 'user_url' ] ) )
                {
                    $userdata[ 'user_url' ] = sanitize_text_field( $_POST[ 'user_url' ] );
                }
            }
            $userdata['ID'] = $user->ID;
            wp_update_user( $userdata );
            // save app data to user
            $counter = 0;
            foreach ( $meta_vars as $meta ) 
            {
                if ( $meta['name'] == 'password' ) 
                {
                    unset( $meta_vars[$counter] );
                }
                $counter++;
            }
            $this->update_user_meta( $meta_vars, $user->ID );
            if ($form_setting['redirect_to'] == 'url') 
            {
                $redirect_to= $form_setting['url'];
            }elseif($form_setting['redirect_to'] == 'page')
            {
                $ii = $form_setting['page_id'];
                $redirect_to = get_post_permalink($ii);
            }elseif($form_setting['redirect_to'] == 'same')
            {
                $message_123 = true;
            }else
            {
               $redirect_to= home_url();
            }
            if($form_setting['email_verify'] == 'yes')
            {
                $message_123 = 'Check User Email id for verification';
            }elseif ($form_setting['update_message']) 
            {
               $message_123 = $form_setting['update_message'];
            }
            else
            {
                $message_123 = "Registration successful";
            }
            // redirect to dashboard
            $response    = array(
                'success' => true,
                'redirect_to' => $redirect_to,
                'message' => $message_123,
                'is_post' => true
            ); 
            $response    = apply_filters( 'user_registration_form_admin_redirect', $response, $user->ID, $form_id );
            do_action('user_registration_form_admin_success', $user->ID );
            echo json_encode( $response );
            exit;
        } // End is_admin()
        // check recaptcha
        if ( $this->search_array( $form_vars, 'input_type', 'recaptcha' ) ) {
            $this->validate_re_captcha();
        }
        // check really_simple_captcha
        if ( $this->search_array( $form_vars, 'input_type', 'really_simple_captcha' ) ) {
            $this->validate_rs_captcha();
        }
        // if user logged in skip verification & creation of new user
        if ( is_user_logged_in() ) 
        {
            $user = new WP_User( get_current_user_id() );
            $userdata = array();
            $userdata[ 'user_email' ] = $user->user_email;
            if ( $this->search_array( $merged_user_meta, 'name', 'first_name' ) ) 
            {
                if ( !isset($_POST[ 'first_name' ]) || $_POST[ 'first_name' ] === '' )
                {
                    $this->signal_error( __( 'Please fill out the registration form!', 'frontend_user_pro' ) );
                }
                else
                {
                    $userdata[ 'first_name' ] = sanitize_text_field( $_POST[ 'first_name' ] );
                }
            }
            if ( $this->search_array( $merged_user_meta, 'name', 'last_name' ) ) 
            {
                if ( !isset($_POST[ 'last_name' ]) || $_POST[ 'last_name' ] === '' )
                {
                    $this->signal_error( __( 'Please fill out the registration form!', 'frontend_user_pro' ) );
                } 
                else
                { 
                    $userdata[ 'last_name' ] = sanitize_text_field( $_POST[ 'last_name' ] );
                } 
            }
            if ( $this->search_array( $merged_user_meta, 'name', 'display_name' ) ) 
            {
                if ( !isset($_POST[ 'display_name' ]) || $_POST[ 'display_name' ] === '' )
                {
                    $this->signal_error( __( 'Please fill out the registration form!', 'frontend_user_pro' ) );
                }
                else
                {
                    $userdata[ 'display_name' ] = sanitize_text_field( $_POST[ 'display_name' ] );
                }
            }
            if ( $this->search_array( $merged_user_meta, 'name', 'user_url' ) ) 
            {
                if ( isset( $_POST[ 'user_url' ] ) )
                {
                    $userdata[ 'user_url' ] = sanitize_text_field( $_POST[ 'user_url' ] );
                }
            }
            $userdata['ID'] = $user->ID;
            wp_update_user( $userdata );
        }
        // else if username + password field is valid user, login and continue
        else if ( $this->is_valid_user( $merged_user_meta ) ) 
        {
            $userdata = array();
            if ( $this->search_array( $merged_user_meta, 'name', 'first_name' ) ) 
            {
                if ( !isset($_POST[ 'first_name' ]) || $_POST[ 'first_name' ] === '' )
                {
                    $this->signal_error( __( 'Please fill out the registration form!', 'frontend_user_pro' ) );
                }
                else
                {
                    $userdata[ 'first_name' ] = sanitize_text_field( $_POST[ 'first_name' ] );
                }
            }
            if ( $this->search_array( $merged_user_meta, 'name', 'last_name' ) ) 
            {
                if ( !isset($_POST[ 'last_name' ]) || $_POST[ 'last_name' ] === '' )
                {
                    $this->signal_error( __( 'Please fill out the registration form!', 'frontend_user_pro' ) );
                }
                else
                {
                    $userdata[ 'last_name' ] = sanitize_text_field( $_POST[ 'last_name' ] );
                }
            }
            if ( $this->search_array( $merged_user_meta, 'name', 'user_email' ) ) 
            {
                if ( !isset($_POST[ 'user_email' ]) || $_POST[ 'user_email' ] === '' )
                {
                    $this->signal_error( __( 'Please fill out the registration form!', 'frontend_user_pro' ) );
                }
                if ( !is_email( $_POST[ 'user_email' ] ) ){
                    $this->signal_error( __( 'Please enter a valid email!', 'frontend_user_pro' ) );
                }
                else{
                    $userdata[ 'user_email' ] = sanitize_email( $_POST[ 'user_email' ] );
                }
            }
            if ( $this->search_array( $merged_user_meta, 'name', 'display_name' ) ) {
                if ( !isset($_POST[ 'display_name' ]) || $_POST[ 'display_name' ] === '' ){
                    $this->signal_error( __( 'Please fill out the registration form!', 'frontend_user_pro' ) );
                }
                else{
                    $userdata[ 'display_name' ] = sanitize_text_field( $_POST[ 'display_name' ] );
                }
            }
            if ( $this->search_array( $merged_user_meta, 'name', 'user_url' ) ) {
                if ( isset( $_POST[ 'user_url' ] ) ){
                    $userdata[ 'user_url' ] = sanitize_text_field( $_POST[ 'user_url' ] );
                }
            }
            if ( $this->search_array( $merged_user_meta, 'name', 'description' ) ) {
                if ( isset( $_POST[ 'description' ] ) ){
                    $userdata[ 'description' ] = wp_kses( $_POST[ 'description' ], user_allowed_html_tags() );
                }
            }
            $user = get_user_by( 'login', $_REQUEST[ 'user_login' ] );
            if( $user ) {
                $userdata['ID'] = $user->ID;
                wp_update_user( $userdata );
                wp_set_auth_cookie( $user->ID, true );
                wp_set_current_user( $user->ID, $_REQUEST[ 'user_login' ] );
                do_action( 'wp_login', $_REQUEST[ 'user_login' ] );
            } else {
                $this->signal_error( __( 'Sorry! Registration is currently disabled at this time!', 'frontend_user_pro' ) );
            }
        }
        // registration is disabled
        else if ( !(bool)FRONTEND_USER()->helper->get_option( 'user-allow-applications', true ) ) 
        {
            $this->signal_error( __( 'Sorry! Registration is currently disabled at this time!', 'frontend_user_pro' ) );
        } else 
        {
            $userdata = array();
            if ( $this->search_array( $merged_user_meta, 'name', 'first_name' ) ) 
            {
                if ( !isset($_POST[ 'first_name' ]) || $_POST[ 'first_name' ] === '' )
                {
                    $this->signal_error( __( 'Please fill out the registration form!', 'frontend_user_pro' ) );
                }
                else
                {
                    $userdata[ 'first_name' ] = sanitize_text_field( $_POST[ 'first_name' ] );
                }
            }
            if ( $this->search_array( $merged_user_meta, 'name', 'last_name' ) ) 
            {
                if ( !isset($_POST[ 'last_name' ]) || $_POST[ 'last_name' ] === '' )
                {
                    $this->signal_error( __( 'Please fill out the registration form!', 'frontend_user_pro' ) );
                }
                else
                {
                    $userdata[ 'last_name' ] = sanitize_text_field( $_POST[ 'last_name' ] );
                }
            }
            if ( $this->search_array( $merged_user_meta, 'name', 'user_email' ) ) 
            {
                if ( !isset($_POST[ 'user_email' ]) || $_POST[ 'user_email' ] === '' )
                {
                    $this->signal_error( __( 'Please fill out the registration form!', 'frontend_user_pro' ) );
                }
                if ( !is_email( $_POST[ 'user_email' ] ) )
                {
                    $this->signal_error( __( 'Please enter a valid email!', 'frontend_user_pro' ) );
                }
                else
                {
                    $userdata[ 'user_email' ] = sanitize_email( $_POST[ 'user_email' ] );
                }
            }
            if ( $this->search_array( $merged_user_meta, 'name', 'user_login' ) ) 
            {
                if ( !isset($_POST[ 'user_login' ]) || $_POST[ 'user_login' ] === '' )
                {
                    $this->signal_error( __( 'Please fill out the registration form!', 'frontend_user_pro' ) );
                }
                else
                {
                    $userdata[ 'user_login' ] = sanitize_user( $_POST[ 'user_login' ] );
                }
            }
            else
            {
                $userdata[ 'user_login' ] = sanitize_email( $_POST[ 'user_email' ] );
            }
            if ( $this->search_array( $merged_user_meta, 'name', 'display_name' ) ) 
            {
                if ( !isset($_POST[ 'display_name' ]) || $_POST[ 'display_name' ] === '' )
                {
                    $this->signal_error( __( 'Please fill out the registration form!', 'frontend_user_pro' ) );
                }
                else
                {
                    $userdata[ 'display_name' ] = sanitize_text_field( $_POST[ 'display_name' ] );
                }
            }
            if ( $this->search_array( $merged_user_meta, 'name', 'user_url' ) ) 
            {
                if ( isset( $_POST[ 'user_url' ] ) )
                {
                    $userdata[ 'user_url' ] = sanitize_text_field( $_POST[ 'user_url' ] );
                }
            }
            if ( $this->search_array( $merged_user_meta, 'name', 'description' ) ) 
            {
                if ( isset( $_POST[ 'description' ] ) )
                {
                    $userdata[ 'description' ] = wp_kses( $_POST[ 'description' ], user_allowed_html_tags() );
                }
            }
            // verify password
            if ( $pass_element = $this->search_array( $merged_user_meta, 'name', 'password' ) ) 
            {
                $pass_element    = current( $pass_element );
                $password        = ( isset( $_POST[ 'pass1' ] ) ? sanitize_text_field( $_POST[ 'pass1' ] ) : '' );
                $password_repeat = ( isset( $_POST[ 'pass2' ] ) ? sanitize_text_field( $_POST[ 'pass2' ] ) : '' );
                // check only if it's filled
                if ( $pass_length = strlen( $password ) ) 
                {
                    // min length check
                    if ( $pass_length < intval( $pass_element[ 'min_length' ] ) ) {
                        $this->signal_error( sprintf( __( 'Password must be %s character long', 'frontend_user_pro' ), $pass_element[ 'min_length' ] ) );
                    }
                    // repeat password check
                    if ( isset( $_POST[ 'pass2' ] ) && ( $password != $password_repeat ) ) {
                        $this->signal_error( __( 'Password didn\'t match', 'frontend_user_pro' ) );
                    }
                    // password is good
                    $userdata[ 'user_pass' ] = $password;
                }
            }
            // see if an account? If so log in
            $user = get_user_by( 'login', $userdata[ 'user_login' ] );
            if ( $user ) 
            {
                $password = wp_check_password( $userdata[ 'user_pass' ] , $user->data->user_pass, $user->ID );
                // if username + password is account log them in
                if ( $password ) 
                {
                    wp_set_auth_cookie( $user->ID, true );
                    wp_set_current_user( $user->ID, $userdata[ 'user_login' ] );
                    do_action( 'wp_login', $userdata[ 'user_login' ] );
                }
                // else show username is in user & password incorrect
                else 
                {
                    $this->signal_error( __( 'Username already in use and password incorrect!', 'frontend_user_pro' ) );
                }
            }
            // good to go, create an subscriber account and log them in
            else 
            {
                if ($form_setting['role']) 
                {
                   $role = $form_setting['role']; 
                }else
                {
                    $role = get_option('default_role');
                }
                if ( $this->search_array( $merged_user_meta, 'name', 'user_login' ) ) 
                {
                    if ( !isset($_POST[ 'user_login' ]) || $_POST[ 'user_login' ] === '' )
                    {
                        $this->signal_error( __( 'Please fill out the registration form!', 'frontend_user_pro' ) );
                    }
                    else
                    {
                        $userdata[ 'user_login' ] = sanitize_user( $_POST[ 'user_login' ] );
                    }
                }
                else
                {
                    $userdata[ 'user_login' ] = sanitize_email( $_POST[ 'user_email' ] );
                }
                $userdata[ 'role' ] = $role;
                $userdata[ 'user_registered' ] = date( 'Y-m-d H:i:s' );
                /**********Inser user************/
                $user_id = wp_insert_user( $userdata );
                if ( is_wp_error( $user_id ) ) 
                {
                    $this->signal_error( $user_id->get_error_message() );
                }
                wp_new_user_notification( $user_id, $deprecated = null );
                $this->user_notication($user_id , $form_id);
                if ( ! empty( $_POST[ 'avatar_id' ] ) ) 
                {
                    $attachment_id = absint( $_POST[ 'avatar_id' ] );
                    add_user_meta($user_id, 'user_avatar' ,$attachment_id );
                    user_update_avatar( $user_id, $attachment_id  );
                } else 
                {
                    delete_user_meta( $user_id, 'user_avatar' );
                }
 
                if($form_setting['email_verify'] == 'yes')
                {
                    $this->user_verify($user_id, $form_id);
                }
                // if (array_key_exists('custom_mail_user', $_POST)) {
                    // $this->custom_email($user_id , $form_id);
                // }
                $user_login = $userdata[ 'user_login'];
                // log the new user in
                $approve = get_option('feu-approve-user');
                if($form_setting['email_verify'] == 'yes')
                {
                    $message_123 = "<strong>ERROR:</strong> Please Check Your Mail id for verification.";
                    $redirect_to = wp_login_url();
                    $approve_usr = 'unapproved';
                }elseif ($approve) 
                {
                    $approve_user = get_user_meta($user_id , 'feu-approve-user' , true);
                    if (array_key_exists('wpfeu-send-approve-email', $approve) && $approve['wpfeu-send-approve-email'] == "" || $approve_user == 1) 
                    {
                        // log the new user in
                        wp_set_auth_cookie( $user_id, true );
                        wp_set_current_user( $user_id, $user_login );
                        do_action( 'wp_login', $user_login );
   
                        if ($form_setting['redirect_to'] == 'url') 
                        {
                            $redirect_to= $form_setting['url'];
                        }elseif($form_setting['redirect_to'] == 'page')
                        {
                            $ii = $form_setting['page_id'];
                            $redirect_to = get_post_permalink($ii);
                        }elseif($form_setting['redirect_to'] == 'same')
                        {
                            $message_123 = true;
                        }else
                        {
                           $redirect_to= home_url();
                        }
                        if($form_setting['email_verify'] == 'yes')
                        {
                            $message_123 = 'Check User Email id for verification';
                        }elseif ($form_setting['update_message']) 
                        {
                            $message_123 = $form_setting['update_message'];
                        }else
                        {
                            $message_123 = "Registration successful";
                        }
                        $approve_usr = '';
                    }else
                    {
                        $message_123 = "<strong>ERROR:</strong> Your account has to be confirmed by an administrator before you can login.";
                        $redirect_to = wp_login_url();
                        $approve_usr = 'unapproved';
                    }
                   
                }else
                {
                    // log the new user in
                    wp_set_auth_cookie( $user_id, true );
                    wp_set_current_user( $user_id, $user_login );
                    do_action( 'wp_login', $user_login );
                    if ($form_setting['redirect_to'] == 'url') 
                    {
                        $redirect_to= $form_setting['url'];
                    }elseif($form_setting['redirect_to'] == 'page')
                    {
                        $ii = $form_setting['page_id'];
                        $redirect_to = get_post_permalink($ii);
                    }elseif($form_setting['redirect_to'] == 'same')
                    {
                        $message_123 = true;
                    }else
                    {
                        $redirect_to= home_url();
                    }
                    if($form_setting['email_verify'] == 'yes')
                    {
                        $message_123 = 'Check User Email id for verification';
                    }elseif ($form_setting['update_message']) 
                    {
                        $message_123 = $form_setting['update_message'];
                    }else
                    {
                        $message_123 = "Registration successful";
                    }
                    $approve_usr = '';
                }
                  $this->custom_email($user_id , $form_id);
            }
        }
        // at this point should have user_id
        $user_id = get_current_user_id();
        // if auto approved
        // save app data to user
      
        $counter = 0;
        foreach ( $meta_vars as $meta ) {
            if ( $meta['name'] == 'password' ) {
                unset( $meta_vars[$counter] );
            }
            $counter++;
        }
        $this->update_user_meta( $meta_vars, $user_id );
        // redirect to dashboard
        $response = array(
            'success' => true,
            'redirect_to' => $redirect_to,
            'message' => $message_123,
            'is_post' => true,
            'approve' => $approve_usr,
        );
        do_action('user_registration_form_frontend_user', $user_id, $userdata);
        $response = apply_filters( 'user_register_form_frontend_user', $response, $user_id, $form_id, $_REQUEST );
        echo json_encode( $response );
        exit;
    }
    function custom_email($user_id , $form_id)
    {
        global $wp_roles;
        $user = new WP_User($user_id);
        $user_name = $user->user_nicename;
        $user_role = implode(',', $user->roles);
        $title   = get_option( 'blogname' );
        $form_setting = get_post_meta($form_id , 'user_form_settings' , true);
        
        $from = $form_setting['notification']['new_to'];
        $subject = $form_setting['notification']['user_email_subject'];
        $message = $form_setting['notification']['user_email'];
        $link = wp_login_url();
        $message = str_replace( 'BLOG_TITLE', $title, $message );
        $message = str_replace( 'BLOG_URL',   home_url(), $message );
        $message = str_replace( 'USERNAME',  $user_name, $message );
        $message = str_replace( 'LOGINLINK',  $link, $message );
        $message = str_replace( 'ROLE',   $user_role, $message );
        if ( is_multisite() ) 
        {
            $message = str_replace( 'SITE_NAME', $GLOBALS['current_site']->site_name, $message );
        }
    $header  = "From: $from\r\n";
    $header .= "Content-type: text/html\r\n";
        $to = $user->user_email;
        wp_mail( $to, $subject, $message, $header);
    }
    function user_verify($user_id, $form_id){
        global $wp_roles; 
        $u = new WP_User($user_id );
        $new_role = 'rpr_unverified';
        wp_update_user( array ('ID' => $u->ID, 'role' => $new_role ) ) ;
        $current_user = get_userdata( $user_id );
        $user_name = $current_user->user_nicename;
        $tm = time();
        $to = $current_user->user_email;
        $title   = get_option( 'blogname' );
        $form_setting = get_post_meta($form_id , 'user_form_settings' , true);
        $message = $form_setting['email_verify_content'];
        $from = $form_setting['email_verify_content_to'];
        $subject = $form_setting['email_verify_content_sub'];
        
        $link = wp_login_url().'?confirm='.$tm; 
        $lg_link = '<a href="'.$link.'" >click here</a>';
        $message = str_replace( 'BLOG_TITLE', $title, $message );
        $message = str_replace( 'BLOG_URL', home_url(), $message );
        $message = str_replace( 'LOGINLINK', $lg_link, $message );
        $message = str_replace( 'USERNAME', $user_name, $message );
        $message = str_replace( 'ROLE', $new_role, $message );
        $headers  = "From: $from\r\n";
    $headers .= "Content-type: text/html\r\n";
        wp_mail( $to, $subject, $message, $headers); 
        add_user_meta( $user_id, 'confirmation_r', $tm , true ); 
    } 
    function user_notication($user_id , $form_id)
    {
        $form_setting = get_post_meta($form_id , 'user_form_settings' , true);
        if ($form_setting) 
        {
            if ($form_setting['notification']['new'] == 'on') 
            {
                global $wp_roles;
                $user = new WP_User($user_id);
                $user_role = implode(',', $user->roles);
                $title   = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
                $message = $form_setting['notification']['new_body'];
                $message = str_replace( 'BLOG_TITLE', $title, $message );
                $message = str_replace( 'BLOG_URL',   home_url(), $message );
                $message = str_replace( 'LOGINLINK',  wp_login_url(), $message );
                $message = str_replace( 'USERNAME',   $user->user_nicename, $message );
                $message = str_replace( 'ROLE',   $user_role, $message );
                if ( is_multisite() ) 
                {
                    $message = str_replace( 'SITE_NAME', $GLOBALS['current_site']->site_name, $message );
                }
                $headers = 'From: '.$user->user_nicename.' <'.$user->user_email.'>';
                $to = $form_setting['notification']['new_to'];
                $subject = $form_setting['notification']['new_subject'];
                wp_mail( $to, $subject, $message, $headers);
            }
        }
    }
    // retrieve fields
    public static function get_input_fields( $form_id ) 
    {
         $form_vars = get_post_meta( $form_id, 'user-form', true );
         if (empty($form_vars)) 
         {
            $form_vars = get_post_meta( $form_id, 'user_form', true );
         }
        $ignore_lists = array( 'section_break', 'html' ,'custom_css1','js');
        $post_vars = $meta_vars = $taxonomy_vars = array();
        if ( $form_vars == null ) {
            return array( array(), array(), array() );
        }
        foreach ( $form_vars as $key => $value ) 
        {
            // ignore section break and HTML input type
            if ( in_array( $value['input_type'], $ignore_lists ) ) 
            {
                continue;
            }
            //separate the post and custom fields
            if ( isset( $value['is_meta'] ) && $value['is_meta'] == 'yes' ) 
            {
                $meta_vars[] = $value;
                continue;
            }
            if ( $value['input_type'] == 'taxonomy' ) 
            {
                // if ( $value['name'] == 'category' )
                // {
                //     continue;
                // }
                $taxonomy_vars[] = $value;
            } else 
            {
                $post_vars[] = $value;
            }
        }
        return array( $post_vars, $taxonomy_vars, $meta_vars );
    }
    public function is_valid_user( $user_vars ) {
        $userdata = array();
        // verify password
        if ( $pass_element = $this->search_array( $user_vars, 'name', 'password' ) ) 
        {
            $pass_element    = current( $pass_element );
            $password        = ( isset( $_POST[ 'pass1' ] ) ? sanitize_text_field( $_POST[ 'pass1' ] ) : '' );
            $password_repeat = ( isset( $_POST[ 'pass2' ] ) ? sanitize_text_field( $_POST[ 'pass2' ] ) : false );
            // check only if it's filled
            if ( $pass_length = strlen( $password ) ) 
            {
                // min length check
                if ( $pass_length < intval( $pass_element[ 'min_length' ] ) ) 
                {
                    return false;
                }
                // repeat password check
                if ( $password_repeat && ( $password !== $password_repeat ) ) 
                {
                    return false;
                }
                // password is good
                $userdata[ 'password' ] = $password;
            }
        }
        if ( $this->search_array( $user_vars, 'name', 'user_login' ) ) 
        {
            $userdata[ 'username' ] = $_REQUEST[ 'user_login' ];
        }
        else 
        {
            return false;
        }
        // see if an account? If so log in
        $user = get_user_by( 'login', $userdata[ 'username' ] );
        if ( $user ) 
        {
            $password = wp_check_password( $userdata[ 'password' ] , $user->data->user_pass, $user->ID );
            if ( $password ) {
                return true;
            }
            else {
                return false;
            }
        }
        return false;
    }
    public static function prepare_meta_fields( $meta_vars ) 
    {
        // loop through custom fields
        // skip files, put in a key => value paired array for later executation
        // process repeatable fields separately
        // if the input is array type, implode with separator in a field
        $files = array();
        $meta_key_value = array();
        $multi_repeated = array(); //multi repeated fields will in sotre duplicated meta key
    //  echo json_encode( $meta_vars ); exit;
        foreach ( $meta_vars as $key => $value ) {
            // put files in a separate array, we'll process it later
            if ( ( $value['input_type'] == 'file_upload' ) || ( $value['input_type'] == 'image_upload' ) ) {
                $files[] = array(
                    'type' => $value['input_type'],
                    'name' => $value['name'],
                    'value' => isset( $_POST['user_files'][$value['name']] ) ? $_POST['user_files'][$value['name']] : array()
                );
                // process repeatable fiels
            } elseif ( $value['input_type'] == 'repeat' ) {
                // if it is a multi column repeat field
                if ( isset( $value['multiple'] ) ) {
                    // if there's any items in the array, process it
                    if ( $_POST[$value['name']] ) {
                        $ref_arr = array();
                        $cols = count( $value['columns'] );
                        $first = array_shift( array_values( $_POST[$value['name']] ) ); //first element
                        $rows = count( $first );
                        // loop through columns
                        for ( $i = 0; $i < $rows; $i++ ) {
                            // loop through the rows and store in a temp array
                            $temp = array();
                            for ( $j = 0; $j < $cols; $j++ ) {
                                $temp[] = $_POST[$value['name']][$j][$i];
                            }
                            // store all fields in a row with '| ' separated
                            $ref_arr[] = implode( '| ', $temp );
                        }
                        // now, if we found anything in $ref_arr, store to $multi_repeated
                        if ( $ref_arr ) {
                            $multi_repeated[$value['name']] = array_slice( $ref_arr, 0, $rows );
                        }
                    }
                } else 
                {
                    $meta_key_value[$value['name']] = implode( '| ', $_POST[$value['name']] );
                }
                // process other fields
            } else {
                // if it's an array, implode with this->separator
                if ( ! empty( $_POST[$value['name']] ) && is_array( $_POST[$value['name']] ) ) {
                    $meta_key_value[$value['name']] = implode( '| ', $_POST[$value['name']] );
                } else {
                    if( ! empty( $_POST[ $value['name'] ] ) ) {
                        $meta_key_value[$value['name']] = trim( $_POST[$value['name']] );
                    } else {
                        $meta_key_value[$value['name']] = '';
                    }
                }
            }
        } //end foreach
        return array( $meta_key_value, $multi_repeated, $files );
    }
    // make fields
    /**
     * Render form items
     *
     * @param array   $form_vars
     * @param int|null post or user id
     * @param string  $type      type of the form. post, profile, application, login, register, user_contact
     */
    function render_items( $form_vars = array(), $id = 0, $type = 'post', $form_id = 0, $read_only = false, $args = array() ) 
    {
        $hidden_fields = array();
        $edit_ignore = array('recaptcha', 'really_simple_captcha');
        if ( $type == 'post' ) 
        {
            $edit_ignore = array( 'recaptcha' );
        }
        if ( $type == 'registration' && is_user_logged_in() ) 
        {
            $edit_ignore = array( 'user_login', 'password', 'user_email' );
        }
        if ( !$form_vars ) 
        {
            return _e( 'Form has no fields!', 'frontend_user_pro' );
        }
        $edit_ignore = apply_filters( 'user_forms_edit_ignore', $edit_ignore, $form_vars, $id, $type, $form_id, $read_only, $args );
        $hidden_fields = apply_filters( 'user_forms_hidden_fields_before', $hidden_fields, $form_vars, $id, $type, $form_id, $read_only, $args );
        foreach ( $form_vars as $key => $form_field ) 
        {
            $cls_w = '';
            if(array_key_exists('full_width', $form_field)) {
                $cls_w = $form_field['full_width'];
            }
            $l_pos = '';
            if (array_key_exists('label_position', $form_field)) {
                $l_pos = $form_field['label_position'];
            }
            // Don't show the email, username or password fields to already logged in users.
            if ( $type == 'registration' && isset( $form_field['name'] ) && in_array( $form_field['name'], $edit_ignore ) ) 
            {
                continue;
            }
            
            if ( $type == 'login' && !FRONTEND_USER()->helper->get_option( 'user-allow-customer-login', true ) && isset( $form_field['input_type'] ) && $form_field['input_type'] == 'radio' ) 
            {
                continue;
            }
            
            if ( $type == 'login' && !FRONTEND_USER()->helper->get_option( 'user-login-captcha', false ) && isset( $form_field['input_type'] ) && $form_field['input_type'] == 'recaptcha'  ) 
            {
                continue;
            }
            
            if ( $form_field['input_type'] == 'toc'  )
            {
                $value = $id ? $this->get_meta( $id, 'user_accept_toc', $type, true ) : 0;
                if ( $value ){
                    $hidden_fields[] = $form_field;
                    continue; // don't reshow toc once they've agreed to it
                }
            }
            
            if ( $form_field['input_type'] == 'remember'  )
            {
                $value = $id ? $this->get_meta( $id, 'user_remember', $type, true ) : 0;
                if ( $value ){
                    $hidden_fields[] = $form_field;
                    continue; // don't reshow toc once they've agreed to it
                }
            }
            
                        // ignore the hidden fields
            if ( $form_field['input_type'] == 'hidden' ) 
            {
                $hidden_fields[] = $form_field;
                continue;
            }
            
            if ( $read_only ) 
            {
                if ( $form_field['input_type'] == 'hidden'  ) 
                {
                    $hidden_fields[] = $form_field;
                    continue;
                }
            }
            
            $label_exclude = array( 'section_break', 'html', 'action_hook', 'toc' ,'custom_css1','js');
            $el_name       = ! empty( $form_field['name'] ) ? $form_field['name'] : '';
            $class_name    = ! empty( $form_field['css'] ) ? ' ' . $form_field['css'] : '';
            
            do_action('user_before_fieldset_output', $form_vars, $id, $type, $form_id, $read_only, $args );
            if (array_key_exists('required', $form_field)) {
                $req = $form_field['required'];
            }
            
            if (array_key_exists('enable_login', $form_field)) 
            {
                $enable = $form_field['enable_login'];
                if ($enable == 'yes') 
                {
                    if ($form_field['enable_login_label'] == 'show') 
                    { 
                        if ($form_field['required'] == 'yes') {
                            $form_field['required'] = 'no';
                        }
                        $vg = $form_field['array_title'][0];
                        ?>
                        <style type="text/css">
                        .<?php echo $vg."_css"; ?>{
                            display: none;
                        }
                        </style>
                        <?php
                    }
                    $cout = count($form_field['login_title']);
                    if ($cout) 
                    {
                        $n = '';
                        for($i = 0 ; $i < $cout ; $i++)
                        {
                            $nk = $form_field['login_title'][$i];
                            $bk = explode('-', $nk);
                            $title_opt = $bk[0];
                            ?>
               
                            <?php
                        }
                    }
                }
            }
            if ( isset( $form_field['input_type'] ) && !in_array( $form_field['input_type'], $label_exclude ) && $l_pos != 'feup_bottom' ) 
            {
                if (array_key_exists('array_title', $form_field)) 
                {
                    $f_css =  $form_field['array_title'][0]."_css";
                    printf( '<fieldset class="user-el %s %s%s %s %s">',$cls_w, $el_name, $class_name ,$f_css, $l_pos);
                }else{
                    printf( '<fieldset class="user-el %s %s%s %s">',$cls_w, $el_name, $class_name, $l_pos);
                }
            do_action('user_after_fieldset_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                do_action('user_before_label_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                $label = $this->label( $form_field, $id );
                echo apply_filters('user_forms_label_wrap', $label, $form_vars, $id, $type, $form_id, $read_only, $args );
                do_action('user_after_label_output', $form_vars, $id, $type, $form_id, $read_only, $args );
            }
            do_action('user_after_label_output', $form_vars, $id, $type, $form_id, $read_only, $args );
            
            $path = plugins_url( '', dirname( __FILE__ ) );
 
            wp_enqueue_style( 'jquery-ui', $path . '/../assets/css/jquery-ui-1.9.1.custom.css' );
            // var_dump($form_field['input_type']);
            switch ( $form_field['input_type'] ) 
            {
                case 'text':
                do_action('user_before_text_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                $field = $this->text( $form_field, $id, $type );
                echo apply_filters('user_forms_text_wrap', $field, $form_vars, $id, $type, $form_id, $read_only, $args );
                do_action('user_after_text_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                break;
                case 'textarea':
                do_action('user_before_textarea_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                $field = $this->textarea( $form_field, $id, $type );
                echo apply_filters('user_forms_textarea_wrap', $field, $form_vars, $id, $type, $form_id, $read_only, $args );
                do_action('user_after_textarea_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                break;
         
                case 'image_upload':
                do_action('user_before_image_upload_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                if ( function_exists( 'wp_enqueue_media' ) ) {
                    wp_enqueue_media();
                } else {
                    wp_enqueue_script( 'media-upload' );
                }
                wp_enqueue_script( 'jquery-ui-datepicker' );
                wp_enqueue_script( 'jquery-ui-slider' );
                wp_enqueue_script( 'jquery-ui-timepicker', $path . '/../assets/js/jquery-ui-timepicker-addon.js', array('jquery-ui-datepicker') );
                wp_enqueue_script( 'user-upload', $path . '/../assets/js/upload.js', array('jquery', 'plupload-handlers') );
                wp_localize_script( 'user-upload', 'user_frontend_upload', array(
                    'confirmMsg' => __( 'Are you sure?', 'user' ),
                    'ajaxurl' => admin_url( 'admin-ajax.php' ),
                    'nonce' => wp_create_nonce( 'user_nonce' ),
                    'plupload' => array(
                        'url' => admin_url( 'admin-ajax.php' ) . '?nonce=' . wp_create_nonce( 'user_featured_img' ),
                        'flash_swf_url' => includes_url( 'js/plupload/plupload.flash.swf' ),
                        'filters' => array(array('title' => __( 'Allowed Files' ), 'extensions' => '*')),
                        'multipart' => true,
                        'urlstream_upload' => true,
                        )
                    ) );
                $field = $this->image_upload( $form_field, $id, $type );
                echo apply_filters('user_forms_image_upload_wrap', $field, $form_vars, $id, $type, $form_id, $read_only, $args );
                do_action('user_after_image_upload_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                break;
                case 'select':
                do_action('user_before_select_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                $field = $this->select( $form_field, false, $id, $type );
                echo apply_filters('user_forms_select_wrap', $field, $form_vars, $id, $type, $form_id, $read_only, $args );
                do_action('user_after_select_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                break;
                case 'map':
                do_action('user_before_map_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                $field = $this->map( $form_field, $id, $type );
                echo apply_filters('user_forms_map_wrap', $field, $form_vars, $id, $type, $form_id, $read_only, $args );
                do_action('user_after_map_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                break;
                case 'multiselect':
                do_action('user_before_multiselect_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                $field = $this->select( $form_field, true, $id, $type );
                echo apply_filters('user_forms_multiselect_wrap', $field, $form_vars, $id, $type, $form_id, $read_only, $args );
                do_action('user_after_multiselect_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                break;
                case 'radio':
                do_action('user_before_radio_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                $field = $this->radio( $form_field, $id, $type );
                echo apply_filters('user_forms_radio_wrap', $field, $form_vars, $id, $type, $form_id, $read_only, $args );
                do_action('user_after_radio_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                break;
                case 'checkbox':
                do_action('user_before_checkbox_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                $field = $this->checkbox( $form_field, $id, $type );
                echo apply_filters('user_forms_checkbox_wrap', $field, $form_vars, $id, $type, $form_id, $read_only, $args );
                do_action('user_after_checkbox_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                break;
                case 'file_upload':
                if(!is_user_logged_in())
                {
                    if ( function_exists( 'wp_enqueue_media' ) ) {
                        wp_enqueue_media();
                    } else {
                        wp_enqueue_script( 'media-upload' );
                    }
                    wp_enqueue_script( 'jquery-ui-datepicker' );
                    wp_enqueue_script( 'jquery-ui-slider' );
                    wp_enqueue_script( 'jquery-ui-timepicker', $path . '/../assets/js/jquery-ui-timepicker-addon.js', array('jquery-ui-datepicker') );
                    wp_enqueue_script( 'user-upload', $path . '/../assets/js/upload.js', array('jquery', 'plupload-handlers') );
                    wp_localize_script( 'user-upload', 'user_frontend_upload', array(
                        'confirmMsg' => __( 'Are you sure?', 'user' ),
                        'ajaxurl' => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce( 'user_nonce' ),
                        'plupload' => array(
                            'url' => admin_url( 'admin-ajax.php' ) . '?nonce=' . wp_create_nonce( 'user_file_upload' ),
                            'flash_swf_url' => includes_url( 'js/plupload/plupload.flash.swf' ),
                            'filters' => array(array('title' => __( 'Allowed Files' ), 'extensions' => '*')),
                            'multipart' => true,
                            'urlstream_upload' => true,
                            )
                        ) );
                }
                do_action('user_before_file_upload_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                $field = $this->file_upload( $form_field, $id, $type );
                echo apply_filters('user_forms_file_upload_wrap', $field, $form_vars, $id, $type, $form_id, $read_only, $args );
                do_action('user_after_file_upload_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                break;
                case 'url':
                do_action('user_before_url_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                $field = $this->url( $form_field, $id, $type );
                echo apply_filters('user_forms_url_wrap', $field, $form_vars, $id, $type, $form_id, $read_only, $args );
                do_action('user_after_url_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                break;
                case 'email':
                do_action('user_before_email_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                $field = $this->email( $form_field, $id, $type );
                echo apply_filters('user_forms_email_wrap', $field, $form_vars, $id, $type, $form_id, $read_only, $args );
                do_action('user_after_email_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                break;
                case 'password':
                do_action('user_before_password_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                $field = $this->password( $form_field, $id, $type );
                echo apply_filters('user_forms_password_wrap', $field, $form_vars, $id, $type, $form_id, $read_only, $args );
                do_action('user_after_password_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                break;
                case 'repeat':
                do_action('user_before_repeat_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                $field = $this->repeat( $form_field, $id, $type );
                echo apply_filters('user_forms_repeat_wrap', $field, $form_vars, $id, $type, $form_id, $read_only, $args );
                do_action('user_after_repeat_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                break;
                case 'taxonomy':
                do_action('user_before_taxonomy_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                $field = $this->taxonomy( $form_field, $id, $type );
                echo apply_filters('user_forms_taxonomy_wrap', $field, $form_vars, $id, $type, $form_id, $read_only, $args );
                do_action('user_after_taxonomy_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                break;
                case 'section_break':
                do_action('user_before_section_break_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                $field = $this->section_break( $form_field, $id );
                echo apply_filters('user_forms_section_break_wrap', $field, $form_vars, $id, $type, $form_id, $read_only, $args );
                do_action('user_after_section_break_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                break;
                case 'html':
                do_action('user_before_html_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                $field = $this->html( $form_field );
                echo apply_filters('user_forms_html_wrap', $field, $form_vars, $id, $type, $form_id, $read_only, $args );
                do_action('user_after_html_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                break;
                case 'custom_css1':
                do_action('user_before_custom_css1_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                $field = $this->custom_css1( $form_field );
                echo apply_filters('user_forms_custom_css1_wrap', $field, $form_vars, $id, $type, $form_id, $read_only, $args );
                do_action('user_after_custom_css1_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                break;
                case 'js':
                do_action('user_before_js_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                $field = $this->js( $form_field );
                echo apply_filters('user_forms_js_wrap', $field, $form_vars, $id, $type, $form_id, $read_only, $args );
                do_action('user_after_js_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                break;
                case 'recaptcha':
                do_action('user_before_recaptcha_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                $field = $this->recaptcha( $form_field, $id, $type );
                echo apply_filters('user_forms_recaptcha_wrap', $field, $form_vars, $id, $type, $form_id, $read_only, $args );
                do_action('user_after_recaptcha_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                break;
                case 'really_simple_captcha':
                do_action('user_before_really_simple_captcha_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                $field = $this->really_simple_captcha( $form_field, $id, $type );
                echo apply_filters('user_forms_really_simple_captcha_wrap', $field, $form_vars, $id, $type, $form_id, $read_only, $args );
                do_action('user_after_really_simple_captcha_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                break;
                case 'action_hook':
                do_action('user_before_action_hook_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                $field = $this->action_hook( $form_field, $form_id, $id, $type );
                echo apply_filters('user_forms_action_hook_wrap', $field, $form_vars, $id, $type, $form_id, $read_only, $args );
                do_action('user_after_action_hook_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                break;
                case 'date':
                do_action('user_before_date_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                $field = $this->date( $form_field, $id, $type );
                echo apply_filters('user_forms_date_wrap', $field, $form_vars, $id, $type, $form_id, $read_only, $args );
                do_action('user_after_date_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                break;
                case 'remember':
                do_action('user_before_remember_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                $field = $this->remember( $form_field, $id, $type );
                echo apply_filters('user_forms_remember_wrap', $field, $form_vars, $id, $type, $form_id, $read_only, $args );
                do_action('user_after_remember_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                break;    
                case 'toc':
                do_action('user_before_toc_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                $field = $this->toc( $form_field, $id, $type );
                echo apply_filters('user_forms_toc_wrap', $field, $form_vars, $id, $type, $form_id, $read_only, $args );
                do_action('user_after_toc_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                break;
                case 'remember':
                do_action('user_before_remember_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                $field = $this->remember( $form_field, $id, $type );
                echo apply_filters('user_forms_remember_wrap', $field, $form_vars, $id, $type, $form_id, $read_only, $args );
                do_action('user_after_remember_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                break;    
                case 'multiple_pricing':
                do_action('user_before_multiple_pricing_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                $field = $this->multiple_pricing( $form_field, $id );
                echo apply_filters('user_forms_multiple_pricing_wrap', $field, $form_vars, $id, $type, $form_id, $read_only, $args );
                do_action('user_after_multiple_pricing_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                break;
                case 'custom_mail_user':
                do_action('user_before_custom_mail_user_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                $field = $this->custom_mail_user( $form_field, $id );
                echo apply_filters('user_forms_custom_mail_user_wrap', $field, $form_vars, $id, $type, $form_id, $read_only, $args );
                do_action('user_after_custom_mail_user_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                break;
                case 'number':
                do_action('user_before_email_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                $field = $this->number( $form_field, $id, $type );
                echo apply_filters('user_forms_number_wrap', $field, $form_vars, $id, $type, $form_id, $read_only, $args );
                do_action('user_after_number_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                break;
                default:
                do_action('user_before_'.$form_field['name'].'_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                do_action( 'user_render_field_'.$form_field['name'], $form_field, $id, $type );
                do_action('user_after_'.$form_field['name'].'_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                break;
            }
            // echo "<pre>";
            // print_r($label_exclude);
            // echo "</pre>";
            if ( isset( $form_field['input_type'] ) && !in_array( $form_field['input_type'], $label_exclude ) && $l_pos == 'feup_bottom' ) 
            {
                do_action('user_before_label_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                $label = $this->label( $form_field, $id );
                echo apply_filters('user_forms_label_wrap', $label, $form_vars, $id, $type, $form_id, $read_only, $args );
                do_action('user_after_label_output', $form_vars, $id, $type, $form_id, $read_only, $args );
            }
            do_action('user_before_fieldset_output_ends', $form_vars, $id, $type, $form_id, $read_only, $args );
            echo '</fieldset>';
            do_action('user_after_fieldset_output_ends', $form_vars, $id, $type, $form_id, $read_only, $args );
        }
        $hidden_fields = apply_filters( 'user_forms_hidden_fields_after', $hidden_fields, $form_vars, $id, $type, $form_id, $read_only, $args );
        if ( $hidden_fields ) 
        {
            foreach ( $hidden_fields as $field ) 
            {
                do_action('user_before_hidden_field_output', $form_vars, $id, $type, $form_id, $read_only, $args );
                $name = isset( $field['name']  ) ? $field['name'] : "";
                $meta_value = isset( $field['meta_value']  ) ? $field['meta_value'] : "";
                printf( '<input type="hidden" name="%s" value="%s">', esc_attr( $name ), esc_attr( $meta_value ) );
                echo "\r\n";
                do_action('user_after_hidden_field_output', $form_vars, $id, $type, $form_id, $read_only, $args );
            }
        }
    }
    // load fields
    /**
     * Prints a text field
     *
     * @param array   $attr
     * @param int|null $id Post or User ID
     */
    function text( $attr, $id, $type = 'post' ) {
        // checking for user profile username
        $username = false;
        $taxonomy = false;
        $value = '';
        if ( $id ) {
            if ( $this->is_meta( $attr ) ) {
                // $user_id = get_current_user_id();
                $value = $this->get_meta( $id, $attr['name'], $type );
                if ( $type !== 'registration' && $type !== 'login' && isset( $attr['template'] ) && $attr['template'] == 'user_login' ) {
                    $bb = get_userdata($id);
                    $value = $bb->user_login;
                    $username = true;
                }
            } else {
                // applicable for post tags
                if ( $type == 'post' && $attr['name'] == 'tags' ) {
                    $post_tags = wp_get_post_tags( $id );
                    $tagsarray = array();
                    foreach ( $post_tags as $tag ) {
                        $tagsarray[] = $tag->name;
                    }
                    $value = implode( ', ', $tagsarray );
                    $taxonomy = true;
                } elseif ( $type == 'post' ) {
                    $value = get_post_field( $attr['name'], $id );
                } elseif ( $type == 'user' || $type == 'registration'  || $type == 'profile' ) {
                    $value = get_user_by( 'id', $id )->$attr['name'];
                    if ( $type !== 'registration' && $type !== 'login' && isset( $attr['template'] ) && $attr['template'] == 'user_login' ) {
                        $username = true;
                    }
                }
            }
        } else {
            $value = ! empty( $attr['default'] ) ? $attr['default'] : '';
            if ( $type == 'post' && $attr['name'] == 'tags' ) {
                $taxonomy = true;
            }
        }
        if( is_user_logged_in() && $type == 'registration' ) {
            if( is_admin() && ! empty( $id ) ) {
                $user_data = get_userdata( $id );
            } else {
                $user_data = get_userdata( get_current_user_id() );
            }
            if ( $attr['name'] == 'first_name' ) {
                $value = $user_data->first_name;
            }
            if ( $attr['name'] == 'last_name' ) {
                $value = $user_data->last_name;
            }
            if ( $attr['name'] == 'email_address' ) {
                $value = $user_data->user_email;
            }
            if ( $attr['name'] == 'display_name' ) {
                $value = $user_data->display_name;
            }
            if ( $attr['name'] == 'number' ) {
                $value = $user_data->number;
            }
        }
        if( empty( $attr['placeholder'] ) ) {
            $attr['placeholder'] = '';
        }
        ob_start(); ?>
        <div class="user-fields">
            <input class="textfield<?php echo $this->required_class( $attr ); ?>" 
            id="<?php echo $attr['name'] ;?>" 
            type="text" 
            data-required="<?php echo $attr['required'] ?>" 
            data-type="text"<?php $this->required_html5( $attr ); ?> 
            name="<?php echo esc_attr( $attr['name'] ); ?>" 
            placeholder="<?php echo esc_attr( $attr['placeholder'] ); ?>" 
            value="<?php echo esc_attr( $value); ?>" 
            size="<?php echo esc_attr( $attr['size'] ) ?>" 
            <?php echo $username ? 'disabled' : ''; ?> />
            <?php if ( $taxonomy ) { ?>
            <script type="text/javascript">
                jQuery(function($) {
                    $('fieldset.tags input[name=tags]').suggest( user_form.ajaxurl + '?action=ajax-tag-search_array&tax=post_tag', { delay: 500, minchars: 2, multiple: true, multipleSep: ', ' } );
                });
            </script>
            <?php } ?>
        </div>
        <?php
        return ob_get_clean();
    }
    /**
     * Prints a textarea field
     *
     * @param array   $attr
     * @param int|null $id Post or User ID
     */
    function textarea( $attr, $id, $type = 'post' ) {
        $req_class = ( isset( $attr['required'] ) && $attr['required'] == 'yes' ) ? 'required' : 'rich-editor';
        //$id = get_current_user_id();
        if ( $id ) {
            if ( $this->is_meta( $attr ) ) {
                $value = $this->get_meta( $id, $attr['name'], $type, true );
            } else {
                if ( $type == 'post' ) {
                    $value = get_post_field( $attr['name'], $id );
                } else {
                    // $user_id = get_current_user_id();
                    $value = $this->get_user_data( $id, $attr['name'] );
                }
            }
        } else {
            $value = $attr['default'];
        }
        if ( !isset( $attr['cols'] ) ){
            $attr['cols'] = 50;
        }
        if ( !isset( $attr['rows'] ) ){
            $attr['rows'] = 8;
        }
        ob_start();
?>
        <div class="user-fields">
        <?php
        $rich = isset( $attr['rich'] ) ? $attr['rich'] : '';
        if ( $rich == 'yes' ) {
            $options = array( 'editor_height' => $attr['rows'], 'quicktags' => false, 'editor_class' => $req_class );
            if (isset($attr['insert_image']) && $attr['insert_image']){
                $options['media_buttons'] = true;
            }
            printf( '<span class="user-rich-validation" data-required="%s" data-type="rich" data-id="%s"></span>', $attr['required'], $attr['name'] );
            wp_editor( $value, $attr['name'], $options );
        } elseif ( $rich == 'teeny' ) {
            $options = array( 'editor_height' => $attr['rows'], 'quicktags' => false, 'teeny' => true, 'editor_class' => $req_class);
            if ($attr['insert_image']){
                $options['media_buttons'] = true;
            }
            printf( '<span class="user-rich-validation" data-required="%s" data-type="rich" data-id="%s"></span>', $attr['required'], $attr['name'] );
            wp_editor( $value, $attr['name'], $options );
        } else {
?>
                <textarea class="textareafield<?php echo $this->required_class( $attr ); ?>" id="<?php echo $attr['name']; ?>" name="<?php echo $attr['name']; ?>" data-required="<?php echo $attr['required'] ?>" data-type="textarea"<?php $this->required_html5( $attr ); ?> placeholder="<?php echo esc_attr( $attr['placeholder'] ); ?>" rows="<?php echo esc_attr( $attr['rows'] ); ?>" cols="<?php echo esc_attr( $attr['cols'] ); ?>"><?php echo esc_textarea( $value ) ?></textarea>
            <?php } ?>
        </div>
        <?php
        return ob_get_clean();
    }
    /**
     * Prints a multiple pricing field
     *
     * @param array   $attr
     * @param int|null $post_id
     */
    function multiple_pricing( $attr, $post_id = 0 ) {
        // this system of letters should just be replaced with booleans. It would make this whole thing way simpler.
        $attr['names'] = $attr && isset($attr['names']) ? strtolower( $attr['names'] ) : '';
        $names_disabled = $attr && isset($attr['names']) && $attr['names'] !== 'no' ? false : true;
        $attr['prices'] = $attr && isset($attr['prices']) ? strtolower( $attr['prices'] ) : '';
        $prices_disabled = $attr && isset($attr['prices']) && $attr['prices'] !== 'no' ? false : true;
        $attr['files'] = $attr && isset($attr['files']) ? strtolower( $attr['files'] ) : '';
        $files_disabled = $attr && isset($attr['files']) && $attr['files'] !== 'no' ? false : true;
        $predefined_on = $attr && isset($attr['multiple']) && $attr['multiple'] !== 'false' ? true : false;
        $predefined_options = $attr && isset($attr['files']) ? esc_attr( $attr['files'] ) : false;
        if ( $post_id ) {
            $files = get_post_meta( $post_id, 'frontend_download_files', true );
            $prices = get_post_meta( $post_id, 'frontend_variable_prices', true );
            $is_variable = (bool) get_post_meta( $post_id, '_variable_pricing', true );
            $combos = array();
            if ( $is_variable ) {
                $counter = 0;
                foreach ( $prices as $key => $option ) {
                    $file  = ( isset( $files[$counter] ) && isset( $files[$counter]['file'] )? $files[$counter]['file'] : '' );
                    $price = ( isset( $option['amount'] )? $option['amount'] : '' );
                    $desc  = ( isset( $option['name'] )? $option['name'] : '' );
                    $combos[$key] = array( 'description' => $desc, 'price' => $price, 'files' => $file );
                    $counter++;
                }
            } else {
                $file = ( isset( $files[0]['file'] )? $files[0]['file'] : '' );
                $desc = ( isset( $prices[0]['name'] )? $prices[0]['name'] : '' );
                $price = get_post_meta( $post_id, 'frontend_price', true );
                $combos = array ( 0 => array( 'description' => $desc, 'price' => $price, 'files' => $file ) );
            }
        } else {
            if ( $predefined_on && isset( $attr['columns'] ) && $attr['columns'] > 0 ){
                $keys = count( $attr['columns'] );
                $new_values = array();
                $key = 0;
                foreach ( $attr['columns'] as $old_key => $value ){
                    if ( $old_key === 0 || $old_key % 2 == 0 ){
                        $new_values[$key]['description'] = $value['name'];
                        $new_values[$key]['files'] = '';
                    }
                    else{
                         $new_values[$key]['price'] = $value['price'];
                         $key++;
                    }
                    unset( $attr[$old_key] );
                }
                $combos = $new_values;
            }
            else{
                $combos = array( 0 => array( 'description' => '', 'price' => '', 'files' => '' ) );
            }
        }
        $files = $combos;
        ob_start();
        ?>
        <div class="user-fields">
            <table class="<?php echo sanitize_key($attr['name']); ?>">
                <thead>
                    <tr>
                        <?php if ( $attr[ 'single' ] !== 'yes' && (!$names_disabled || $predefined_on)  ) { ?>
                            <td class="user-name-column"><?php _e( 'Name of Price Option', 'frontend_user_pro' ); ?></td>
                        <?php } ?>
                        <?php if ( !$prices_disabled || $predefined_on ) { ?>
                            <td class="user-price-column"><?php printf( __( 'Amount (%s)', 'frontend_user_pro' ), frontend_currency_filter( '' ) ); ?></td>
                        <?php } ?>
                        <?php if ( !$files_disabled ) { ?>
                            <td class="user-file-column" colspan="2"><?php _e( 'File URL', 'frontend_user_pro' ); ?></td>
                        <?php } ?>
                        <?php do_action("user-add-multiple-pricing-column"); ?>
                        <?php if ( $attr[ 'single' ] === 'yes' || $predefined_on ) { ?>
                            <td class="user-remove-column">&nbsp;</td>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody  class="user-variations-list-<?php echo sanitize_key($attr['name']); ?>">
                <?php
                foreach ( $files as $index => $file ){
                    if ( ! is_array( $file ) ) {
                        $file = array(
                            'file' => '',
                            'description' => '',
                            'price' => ''
                        );
                        $file = apply_filters('user_default_new_multiple_price_row_values', $file );
                    }
                    $price = isset( $file['price'] ) && $file['price'] != '' ? $file['price'] : '';
                    $description = isset( $file['description'] ) && $file['description'] != '' ? $file['description'] : '';
                    $download = isset( $file['files'] ) && $file['files'] != '' ? $file['files'] : '';
                    $price = apply_filters('user_multiple_price_row_price_value', $price, $file );
                    $description = apply_filters('user_multiple_price_row_description_value', $description, $file );
                    $download = apply_filters('user_multiple_price_row_download_value', $download, $file );
                    $required = ! empty( $attr['required'] ) && 'yes' == $attr['required'] ? 'data-required="yes" data-type="multiple"' : '';
                    ?>
                    <tr class="user-single-variation" id="user-multiple-validation-pointer">
                        <?php if ( $attr[ 'single' ] !== 'yes' && (!$names_disabled || $predefined_on) ) { ?>
                        <td class="user-name-row">
                            <?php if( $names_disabled ) : ?>
                                <span class="user-name-value"><?php echo esc_attr( $description ); ?></span>
                                <input type="hidden" class="user-name-value" name="option[<?php echo esc_attr( $index ); ?>][description]" id="options[<?php echo esc_attr( $index ); ?>][description]" rows="3" placeholder="<?php esc_attr_e( 'Option Name', 'frontend_user_pro' ); ?>" value="<?php echo esc_attr( $description ); ?>" <?php echo $required; ?>/>
                            <?php else : ?>
                                <input type="text" class="user-name-value" name="option[<?php echo esc_attr( $index ); ?>][description]" id="options[<?php echo esc_attr( $index ); ?>][description]" rows="3" placeholder="<?php esc_attr_e( 'Option Name', 'frontend_user_pro' ); ?>" value="<?php echo esc_attr( $description ); ?>" <?php echo $required; ?>/>
                            <?php endif; ?>
                            <input type="hidden" id="user-name-row-js" name="user-name-row-js" value="1" />
                        </td>
                        <?php }
                        if ( !$prices_disabled || $predefined_on ) { ?>
                        <td class="user-price-row">
                            <?php if( $prices_disabled ) : ?>
                                <span class="user-price-value"><?php echo esc_attr( $price ); ?></span>
                                <input type="hidden" class="user-price-value" placeholder="<?php echo frontend_currency_filter( '0.00' ); ?>" name="option[<?php echo esc_attr( $index ); ?>][price]" id="options[<?php echo esc_attr( $index ); ?>][price]" placeholder="20" value="<?php echo esc_attr( $price ); ?>" <?php echo $required; ?>/>
                            <?php else : ?>
                                <input type="text" class="user-price-value" placeholder="<?php echo frontend_currency_filter( '0.00' ); ?>" name="option[<?php echo esc_attr( $index ); ?>][price]" id="options[<?php echo esc_attr( $index ); ?>][price]" placeholder="20" value="<?php echo esc_attr( $price ); ?>" <?php echo $required; ?>/>
                            <?php endif; ?>
                            <input type="hidden" id="user-price-row-js" name="user-price-row-js" value="1"/>
                        </td>
                        <?php }
                        if ( !$files_disabled ) { ?>
                        <td class="user-url-row">
                            <input type="text" class="user-file-value" placeholder="<?php _e( "http://", 'frontend_user_pro' ); ?>" name="files[<?php echo esc_attr( $index ); ?>]" value="<?php echo esc_attr( $download ); ?>" <?php echo $required; ?>/>
                            <input type="hidden" id="user-file-row-js" name="user-file-row-js" value="1" />
                        </td>
                        <td class="user-url-choose-row">
                            <a href="#" class="btn btn-sm btn-default upload_file_button" data-choose="<?php _e( 'Choose file', 'frontend_user_pro' ); ?>" data-update="<?php _e( 'Insert file URL', 'frontend_user_pro' ); ?>">
                            <?php echo str_replace( ' ', '&nbsp;', __( 'Upload', 'frontend_user_pro' ) ); ?></a>
                        </td>
                        <?php }
                        do_action("user-add-multiple-pricing-row-value", $file); ?>
                        <?php if ( $attr[ 'single' ] !== 'yes' && !$predefined_on ) { ?>
                        <td class="user-delete-row">
                            <a href="#" class="btn btn-sm btn-danger delete">
                            <?php _e( 'x', 'frontend_user_pro' ); ?></a>
                        </td>
                        <?php } ?>
                    </tr>
                    <?php } ?>
                    <tr class="add_new" style="display:none !important;" id="<?php echo sanitize_key($attr['name']); ?>"></tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5">
                            <?php if ( $attr[ 'single' ] !== 'yes' && !$predefined_on ) { ?>
                            <a href="#" class="insert-file-row" id="<?php echo sanitize_key($attr['name']); ?>"><?php _e( 'Add File', 'frontend_user_pro' ); ?></a>
                            <?php } ?>
                        </th>
                    </tr>
                </tfoot>
            </table>
    </div>
    <?php
    return ob_get_clean();
    }
    function file_upload( $attr, $post_id, $type ) {
        $single = false;
        if ( $type == 'post' ) {
            $single = true;
        }
        $uploaded_items = $post_id ? $this->get_meta( $post_id, $attr['name'], $type, $single ) : '';
        $uploaded_items1 = '' ;
        if (is_array($uploaded_items)) {
            $uploaded_items1 = unserialize($uploaded_items[0]);
        }
         
  
 
        if (!empty($uploaded_items) && empty($uploaded_items1) ) {
            $uploaded_items1 = $uploaded_items;
        }
        if ( ! is_array( $uploaded_items1 ) &&  ! is_array( $uploaded_items ) ) {
            $uploaded_items1 = array( 0 => '' );
        }
        if ( !isset($attr['single'])){
            $attr['single'] = false;
        }
        $max_files = 0;
        if ( isset( $attr['count'] ) && $attr['count'] > 0 ){
            $max_files = $attr['count'];
        }
        ob_start();
        if(!is_user_logged_in()) 
        {
            // echo "<pre>";
            // print_r($uploaded_items1);
            // echo "</pre>";
        ?>
        <!-- cxfvbxcv -->
            <div class="user-fields">
                <div id="user-<?php echo $attr['name']; ?>-upload-container">
                    <div class="user-attachment-upload-filelist">
                    <?php 
                        if (true) 
                        { ?>
                        <a id="user-<?php echo $attr['name']; ?>-pickfiles" class="button1 file-selector1" data-type="file" href="#"><?php _e( 'Select File', 'user' ); ?></a>
                        <?php
                        }
                        printf( '<span class="user-file-validation" data-required="%s" data-type="file"></span>', $attr['required'] ); ?>
                        <ul class="user-attachment-list thumbnails">
                            <?php
                            if ( $uploaded_items1 && $uploaded_items1[0] != false) {
                                foreach ($uploaded_items1 as $key => $value) {
                                    echo "<li>"; ?>
                                    <!-- <input type="text" class="user-file-value" placeholder="<?php// _e( "http://", 'frontend_user_pro' ); ?>" name="<?php //echo $attr['name']; ?>[<?php //echo esc_attr( $index ); ?>]" value="<?php echo esc_attr( $value ); ?>" /> -->
                                    <?php
                                    printf( '<br><a href="#" id="user-button_file_'.$key.'" data-confirm="%s" class="user-button button user-remove-upload-file">%s</a>', __( 'Are you sure?', 'user' ), __( 'Delete', 'user' ) );
                                    echo "</li>";
                                }
                            }
                            if ( !$uploaded_items1 ) {
                                foreach ($uploaded_items1 as $attach_id) {
                                    echo USER_Upload::attach_html( $attach_id, $attr['template'], $attr['name'] );
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div><!-- .container -->
                <span class="user-help"><?php echo $attr['help']; ?></span>
            </div> <!-- .user-fields -->
            <script type="text/javascript">
                jQuery(function($) {
                    new USER_Uploader('user-<?php echo $attr['name']; ?>-pickfiles', 'user-<?php echo $attr['name']; ?>-upload-container', <?php echo $attr['count']; ?>, '<?php echo $attr['name']; ?>', 'zip', 1024);
                });
            </script>
      <!-- gfhgh -->
      <?php } else { ?> 
        <div class="user-fields">
            <table class="<?php echo sanitize_key($attr['name']); ?>">
                <thead>
                    <tr>
                    <td class="user-file-column" colspan="2"><?php _e( 'File URL', 'frontend_user_pro' ); ?></td>
                        <?php if ( is_admin() ) { ?>
                        <td class="user-download-file">
                        <?php _e( 'Download File', 'frontend_user_pro' ); ?>
                        </td>
                        <?php } ?>
                        <?php if ( $attr[ 'single' ] !== 'yes' ) { ?>
                        <td class="user-remove-column">&nbsp;</td>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody class="user-variations-list-<?php echo sanitize_key($attr['name']); ?>">
                <input type="hidden" id="user-upload-max-files-<?php echo sanitize_key($attr['name']); ?>" value="<?php echo $max_files; ?>" />
                    <?php
                    foreach ( $uploaded_items1 as $index => $attach_id ) {
                        $download =$attach_id;
                        ?>
                        <tr class="user-single-variation">
                        <td class="user-url-row">
                        <?php printf( '<span class="user-file-validation" data-required="%s" data-type="file"></span>', $attr['required'] ); ?>
                        <input type="text" class="user-file-value" placeholder="<?php _e( "http://", 'frontend_user_pro' ); ?>" name="<?php echo $attr['name']; ?>[<?php echo esc_attr( $index ); ?>]" value="<?php echo esc_attr( $download ); ?>" />
                            </td>
                            <td class="user-url-choose-row" width="1%">
                            <a href="#" class="btn btn-sm btn-default upload_file_button" data-choose="<?php _e( 'Choose file', 'frontend_user_pro' ); ?>" data-update="<?php _e( 'Insert file URL', 'frontend_user_pro' ); ?>">
                            <?php echo str_replace( ' ', '&nbsp;', __( 'Choose file', 'frontend_user_pro' ) ); ?></a>
                                </td>
                                <?php if ( is_admin() ) { ?>
                                <td class="user-download-file">
                                <?php printf( '<a href="%s">%s</a>', wp_get_attachment_url( $attach_id ), __( 'Download File', 'frontend_user_pro' ) ); ?>
                                </td>
                                <?php } ?>
                                <?php if ( $attr[ 'single' ] !== 'yes' ) { ?>
                                <td width="1%" class="user-delete-row">
                                    <a href="#" class="btn btn-sm btn-danger delete">
                                    <?php _e( 'x', 'frontend_user_pro' ); ?></a>
                                    </td>
                                    <?php } ?>
                                </tr>
                                <?php
                            }
                            ?>
                            <tr class="add_new" style="display:none !important;" id="<?php echo sanitize_key($attr['name']); ?>"></tr>
                        </tbody>
                        <?php if( ! empty( $attr['count'] ) && $attr['count'] > 1 ) : ?>
                            <tfoot>
                                <tr>
                                    <th colspan="5">
                                    <a href="#" class="insert-file-row" id="<?php echo sanitize_key($attr['name']); ?>"><?php _e( 'Add File', 'frontend_user_pro' ); ?></a>
                                    </th>
                                </tr>
                            </tfoot>
                        <?php endif; ?>
                    </table>
                    </div> <!-- .user-fields -->
                <?php 
            }
                return ob_get_clean();
            }
    
    /**
     * Prints a image upload field
     *
     * @param array   $attr
     * @param int|null $post_id
     */
   function image_upload( $attr, $post_id, $type ) 
   {
    $has_images = false;
    $has_avatar = false;
    $featured_image = false;
    $avatar_image = false;
    $gallery = false;
    $image_upload = false;
    $avatar = false;
    $url = '';
    $id = 0;
        if ( $post_id ) 
        {
            if ($type == 'post') 
            {
                if ($attr['name'] == 'featured_image') 
                {
                    $featured_image = true;
                    $id = get_post_thumbnail_id( $post_id );
                    $url = wp_get_attachment_url( $id );
                }
                if ($attr['template'] == 'image_upload') 
                {
                    $gallery = true;
                    $has_images = true;
                   
                }
            }else 
            {
                if ($attr['name'] == 'avatar') 
                {
                    $has_avatar = true;
                    $avatar_image = get_avatar( $post_id );
                }
                if ($attr['template'] == 'image_upload') 
                {
                    $image_upload = true;
                    $has_images= true;
                    $images = $this->get_meta( $post_id, $attr['name'], $type, false );
                }
            }
        }
        if ( $attr['name'] == 'featured_image') 
        {
            $featured_image = true;
        }if ($attr['template'] == 'image_upload') {
             if ( $this->is_meta( $attr ) ) {
                
                $image_upload = true;
                
            }
        } else 
        {
        	$avatar = true;
            $has_images= true;
        }
        ob_start();
        ?>
        <style> .user-hide { display: none } </style>
        
        <?php
        if ($featured_image) 
        {  ?>
            <div class="user-fields">
                <div id="user-<?php echo $attr['name']; ?>-upload-container">
                    <div class="user-attachment-upload-filelist">
                    <?php 
                        $featured_image = get_user_meta( $post_id, $attr['name'], true );
                        if (!$featured_image) 
                        { ?>
                        <a id="user-<?php echo $attr['name']; ?>-pickfiles" class="button file-selector" href="#"><?php _e( 'Select Image', 'user' ); ?></a>
                        <?php
                        }
                        printf( '<span class="user-file-validation" data-required="%s" data-type="file"></span>', $attr['required'] ); ?>
                        <ul class="user-attachment-list thumbnails">
                            <?php
                            if ( $url ) {
                                echo "<li>";
                                echo "<img height='96' width='96' class='featured_image' alt='' src='".$url."'>";
                                printf( '<br><a href="#" id="user-button_user_" data-confirm="%s" class="user-button button user-remove-featured-image">%s</a>', __( 'Are you sure?', 'user' ), __( 'Delete', 'user' ) );
                                echo "</li>";
                            }
                            ?>
                        </ul>
                    </div>
                </div><!-- .container -->
                <span class="user-help"><?php echo $attr['help']; ?></span>
            </div> <!-- .user-fields -->
            <script type="text/javascript">
                jQuery(function($) {
                    new USER_Uploader('user-<?php echo $attr['name']; ?>-pickfiles', 'user-<?php echo $attr['name']; ?>-upload-container', <?php echo $attr['count']; ?>, '<?php echo $attr['name']; ?>', 'jpg,jpeg,gif,png,bmp', 1024);
                });
            </script>
            <?php 
        }else if ($gallery)
        { ?>
        
            <div class="user-fields">
                <div id="user-<?php echo $attr['name']; ?>-upload-container">
                    <div class="user-attachment-upload-filelist">
                    <?php 
                        $image_upload = get_post_meta( $post_id, $attr['name'], true );
                        $image_val = unserialize($image_upload);
                        if (!$image_upload || $image_val[0] == false) 
                        { ?>
                        <a id="user-<?php echo $attr['name']; ?>-pickfiles" class="button file-selector" href="#"><?php _e( 'Select Image', 'user' ); ?></a>
                        <?php
                        }
                        printf( '<span class="user-file-validation" data-required="%s" data-type="file"></span>', $attr['required'] ); ?>
                        <input type='hidden' class='post_id_field' value='<?php echo $_GET['pid']; ?>'>
                        <ul class="user-attachment-list thumbnails">
                            <?php
                            
                            if ( $image_upload && $image_val[0] != false) {
                                foreach ($image_val as $key => $value) {
                                    echo "<li>";
                                    echo "<img height='96' width='96' class='image_upload' alt='' src='".$value."'>";
                                    printf( '<br><a href="#" id="user-button_post_'.$key.'" data-confirm="%s" class="user-button button user-remove-upload-image">%s</a>', __( 'Are you sure?', 'user' ), __( 'Delete', 'user' ) );
                                    echo "</li>";
                                }
                            }
                            if ( $has_images ) {
                                foreach ($image_val as $attach_id) {
                                    echo USER_Upload::attach_html( $attach_id, $attr['template'],$attr['name'] );
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div><!-- .container -->
                <span class="user-help"><?php echo $attr['help']; ?></span>
            </div> <!-- .user-fields -->
            <script type="text/javascript">
                jQuery(function($) {
                    new USER_Uploader('user-<?php echo $attr['name']; ?>-pickfiles', 'user-<?php echo $attr['name']; ?>-upload-container', <?php echo $attr['count']; ?>, '<?php echo $attr['name']; ?>', 'jpg,jpeg,gif,png,bmp', <?php echo $attr['max_size'] ?>);
                });
            </script>
            <?php
        }elseif ($image_upload)
        {
        ?>
        
            <div class="user-fields">
                <div id="user-<?php echo $attr['name']; ?>-upload-container">
                    <div class="user-attachment-upload-filelist">
                    <?php 
                        $image_upload = get_user_meta( $post_id, $attr['name'], true );
                        $image_val = unserialize($image_upload);
                        if (!$image_upload) 
                        { ?>
                        <a id="user-<?php echo $attr['name']; ?>-pickfiles" class="button file-selector" href="#"><?php _e( 'Select Image', 'user' ); ?></a>
                        <?php
                        }
                        printf( '<span class="user-file-validation" data-required="%s" data-type="file"></span>', $attr['required'] ); ?>
                        <ul class="user-attachment-list thumbnails">
                            <?php
                            if ( $image_upload || $image_val[0] != false) {
                                foreach ($image_val as $key => $value) {
                                    echo "<li>";
                                    echo "<img height='96' width='96' class='image_upload' alt='' src='".$value."'>";
                                    printf( '<br><a href="#" id="user-button_user_'.$key.'" data-confirm="%s" class="user-button button user-remove-upload-image">%s</a>', __( 'Are you sure?', 'user' ), __( 'Delete', 'user' ) );
                                    echo "</li>";
                                }
                            }
                            if ( $has_images ) {
                                foreach ($images as $attach_id) {
                                	echo USER_Upload::attach_html( $attach_id, $attr['template'], $attr['name'] );
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div><!-- .container -->
                <span class="user-help"><?php echo $attr['help']; ?></span>
            </div> <!-- .user-fields -->
            <script type="text/javascript">
                jQuery(function($) {
                    new USER_Uploader('user-<?php echo $attr['name']; ?>-pickfiles', 'user-<?php echo $attr['name']; ?>-upload-container', <?php echo $attr['count']; ?>, '<?php echo $attr['name']; ?>', 'jpg,jpeg,gif,png,bmp', <?php echo $attr['max_size'] ?>);
                });
            </script>
            <?php
        }
        else
        {
        ?>
        
            <div class="user-fields">
                <div id="user-<?php echo $attr['name']; ?>-upload-container">
                    <div class="user-attachment-upload-filelist">
                    <?php 
                        $avatar = get_user_meta( $post_id, 'user_avatar', true );
                        if (!$avatar) 
                        { ?>
                    
                        <a id="user-<?php echo $attr['name']; ?>-pickfiles" class="button file-selector" href="#"><?php _e( 'Select Image', 'user' ); ?></a>
                        <?php
                        }
                        printf( '<span class="user-file-validation" data-required="%s" data-type="file"></span>', $attr['required'] ); ?>
                        <ul class="user-attachment-list thumbnails">
                            <?php
                            if ( $has_avatar ) {
                                $avatar = get_user_meta( $post_id, 'user_avatar', true );
                                if ( $avatar ) {
                                    echo $avatar_image;
                                    printf( '<br><a href="#" data-confirm="%s" class="user-button button user-remove-avatar-image">%s</a>', __( 'Are you sure?', 'user' ), __( 'Delete', 'user' ) );
                                }
                            }
                            if ( $has_avatar) {
                               // foreach ($images as $attach_id) {
                                    echo USER_Upload::attach_html( $attach_id, $attr['name'] );
                               // }
                            }
                            ?>
                        </ul>
                    </div>
                </div><!-- .container -->
                <span class="user-help"><?php echo $attr['help']; ?></span>
            </div> <!-- .user-fields -->
            <script type="text/javascript">
                jQuery(function($) {
                    new USER_Uploader('user-<?php echo $attr['name']; ?>-pickfiles', 'user-<?php echo $attr['name']; ?>-upload-container', <?php echo $attr['count']; ?>, '<?php echo $attr['name']; ?>', 'jpg,jpeg,gif,png,bmp', <?php echo $attr['max_size'] ?>);
                });
            </script>
            <?php
        }
        return ob_get_clean();
    }
    /**
     * Prints a select or multiselect field
     *
     * @param array   $attr
     * @param bool    $multiselect
     * @param int|null $post_id
     */
    function select( $attr, $multiselect = false, $post_id, $type ) {
        if ( $post_id ) {
            $selected = $this->get_meta( $post_id, $attr['name'], $type );
            $selected = $multiselect ? explode( '| ', $selected ) : $selected;
        } else {
            $selected = isset( $attr['selected'] ) ? $attr['selected'] : '';
            $selected = $multiselect ? ( is_array( $selected ) ? $selected : array() ) : $selected;
        }
        $multi = $multiselect ? ' multiple="multiple"' : '';
        $data_type = $multiselect ? 'multiselect' : 'select';
        $css = $multiselect ? ' class="multiselect"' : '';
        ob_start(); ?>
        <div class="user-fields">
            <select<?php echo $css; ?> name="<?php echo $attr['name'] ?>[]"<?php echo $multi; ?> data-required="<?php echo $attr['required'] ?>" data-type="<?php echo $data_type; ?>"<?php $this->required_html5( $attr ); ?>>
                <?php if ( !empty( $attr['first'] ) ) { ?>
                    <option value=""><?php echo $attr['first']; ?></option>
                <?php } ?>
                <?php
                if ( $attr['options'] && count( $attr['options'] ) > 0 ) 
                {
                    foreach ( $attr['options'] as $option ) 
                    {
                        $current_select = $multiselect ? selected( in_array( $option, $selected ), true, false ) : selected( $selected, $option, false );
                        ?>
                        <option value="<?php echo esc_attr( $option ); ?>"<?php echo $current_select; ?>><?php echo $option; ?></option>
                        <?php
                    }
                }
                ?>
            </select>
        </div>
        <?php
        return ob_get_clean();
    }
    /**
     * Prints a radio field
     *
     * @param array   $attr
     * @param int|null $post_id
     */
    function radio( $attr, $post_id, $type ) {
        $selected = isset( $attr['selected'] ) ? $attr['selected'] : '';
        if ( $post_id ) {
            $selected = $this->get_meta( $post_id, $attr['name'], $type, true );
        }
        ob_start(); ?>
        <div class="user-fields">
            <div data-type="radio" >
                <!-- <span data-required="<?php //echo $attr['required'] ?>" data-type="radio"></span> -->
                <?php
            if ( $attr['options'] && count( $attr['options'] ) > 0 ) {
                foreach ( $attr['options'] as $option ) {
                if ( isset( $attr['name'] ) && $attr['name'] == 'user_login_radio_button' && $option == 'User' && FRONTEND_USER()->users->get_user_constant_name( $plural = false, $uppercase = true ) !== 'User' ){
                    $option = FRONTEND_USER()->users->get_user_constant_name( $plural = false, $uppercase = true );
                } ?>
                        <label>
                            <input name="<?php echo $attr['name']; ?>"  type="radio" value="<?php echo esc_attr( $option ); ?>"<?php checked( $selected, $option ); ?> />
                            <?php echo $option; ?>
                        </label>
                        <?php
                }
            } ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    /**
     * Prints a checkbox field
     *
     * @param array   $attr
     * @param int|null $post_id
     */
    function checkbox( $attr, $post_id, $type ) {
        $selected = isset( $attr['selected'] ) ? $attr['selected'] : array();
        if ( $post_id ) {
            $selected = explode( '| ', $this->get_meta( $post_id, $attr['name'], $type, true ) );
        }
        ob_start(); ?>
        <div class="user-fields">
            <div data-type="radio">
                <!-- <span data-required="<?php// echo $attr['required'] ?>" data-type="radio"></span> -->
                <?php
            if ( $attr['options'] && count( $attr['options'] ) > 0 ) {
                foreach ( $attr['options'] as $option ) { ?>
                        <label>
                            <input type="checkbox" name="<?php echo $attr['name']; ?>[]" value="<?php echo esc_attr( $option ); ?>"<?php echo in_array( $option, $selected ) ? ' checked="checked"' : ''; ?> />
                            <?php echo $option; ?>
                        </label>
                        <?php
                }
            } ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    /**
     * Prints a url field
     *
     * @param array   $attr
     * @param int|null $post_id
     */
    function url( $attr, $post_id, $type ) {
        if ( $post_id ) {
            if ( $this->is_meta( $attr ) ) {
                $value = $this->get_meta( $post_id, $attr['name'], $type, true );
            } else {
                //must be user profile url
                $id = get_current_user_id();
                $value = $this->get_user_data( $id, $attr['name'] );
            }
        } else {
            $value = $attr['default'];
        }
        ob_start(); ?>
        <div class="user-fields">
            <input id="user-<?php echo $attr['name']; ?>" type="url" class="url" data-required="<?php echo $attr['required'] ?>" data-type="text"<?php $this->required_html5( $attr ); ?> name="<?php echo esc_attr( $attr['name'] ); ?>" placeholder="<?php echo esc_attr( $attr['placeholder'] ); ?>" value="<?php echo esc_attr( $value ) ?>" size="<?php echo esc_attr( $attr['size'] ) ?>" />
        </div>
        <?php
        return ob_get_clean();
    }
    /**
     * Prints a email field
     *
     * @param array   $attr
     * @param int|null $post_id
     */
    function email( $attr, $post_id, $type = 'post' ) {
        if ( $post_id ) {
            if ( $this->is_meta( $attr ) ) {
                $value = $this->get_meta( $post_id, $attr['name'], $type, true );
            } else {
                $value = $this->get_user_data( $post_id, $attr['name'] );
            }
        } else {
            $value = ! empty( $attr['default'] ) ? $attr['default'] : '';
        }
        $attr['placeholder'] = ! empty( $attr['placeholder'] ) ? $attr['placeholder'] : '';
        ob_start(); ?>
        <div class="user-fields">
            <input id="user-<?php echo $attr['name']; ?>" type="email" class="email" data-required="<?php echo $attr['required'] ?>" data-type="text"<?php $this->required_html5( $attr ); ?> name="<?php echo esc_attr( $attr['name'] ); ?>" placeholder="<?php echo esc_attr( $attr['placeholder'] ); ?>" value="<?php echo esc_attr( $value ) ?>" size="<?php echo esc_attr( $attr['size'] ) ?>" />
        </div>
        <?php
        return ob_get_clean();
    }
    function number( $attr, $post_id, $type = 'post' ) {
        if ( $post_id ) {
            if ( $this->is_meta( $attr ) ) {
                $value = $this->get_meta( $post_id, $attr['name'], $type, true );
            } else {
                $value = $this->get_user_data( $post_id, $attr['name'] );
            }
        } else {
            $value = ! empty( $attr['default'] ) ? $attr['default'] : '';
        }
        $attr['placeholder'] = ! empty( $attr['placeholder'] ) ? $attr['placeholder'] : '';
        ob_start(); ?>
        <div class="user-fields">
            <input id="user-<?php echo $attr['name']; ?>" type="number" class="number" data-required="<?php echo $attr['required'] ?>" data-type="text"<?php $this->required_html5( $attr ); ?> name="<?php echo esc_attr( $attr['name'] ); ?>" placeholder="<?php echo esc_attr( $attr['placeholder'] ); ?>" value="<?php echo esc_attr( $value ) ?>" size="<?php echo esc_attr( $attr['size'] ) ?>" />
        </div>
        <?php
        return ob_get_clean();
    }
    function custom_mail_user($attr, $post_id, $type = 'post'){
        ?>
        <input type="hidden" value="custom_mail" name="custom_mail_user">
        <?php
    }
    /**
     * Prints a password field
     *
     * @param array   $attr
     */
    function password( $attr, $post_id, $type ) {
        if ( $post_id && $type != 'registration' ) {
            $attr['required'] = 'no';
        }
        if ( !isset( $attr['placeholder'] ) ){
            $attr['placeholder'] = '';
        }
        ob_start();
        wp_enqueue_script('data-4' ,plugins_url( '../../assets/js/script.js', __FILE__ ) ); ?>
    
        <div class="user-fields">
            <?php
            if (array_key_exists('show_pass', $attr)) {
                if ($attr['show_pass'] == 'yes') {
                    ?>
                    <input id="pass1" type="password" class="password" data-required="<?php echo $attr['required'] ?>" data-type="text"<?php $this->required_html5( $attr ); ?> name="pass1" placeholder="<?php echo esc_attr( $attr['placeholder'] ); ?>" value="" size="<?php echo $attr['size']; ?>"/>
                    <link rel="stylesheet" href="<?php echo plugins_url( '../../css/font-awesome.min.css', __FILE__ );?>">
                    <i class="fa fa-eye"></i>
                    <?php
                }
            }else{
                ?>
                <input id="pass1" type="password" class="password" data-required="<?php echo $attr['required'] ?>" data-type="text"<?php $this->required_html5( $attr ); ?> name="pass1" placeholder="<?php echo esc_attr( $attr['placeholder'] ); ?>" value="" size="<?php echo $attr['size']; ?>"  />
                <?php
            }
            if (array_key_exists('pass_strength', $attr)) {
                if ($attr['pass_strength'] == 'yes') {
                    echo "<span id='result'></span>";
                }
            }
            
            ?>
            
        </div>
        <script type="text/javascript">
            (function($){
                $(".fa.fa-eye").mousedown(function(){
                    $('#pass1').prop('type', 'text');
                });
                $(".fa.fa-eye").mouseup(function(){
                    $('#pass1').prop('type', 'password');
                });
            }(jQuery));
        </script>
        <?php
        if ( $attr['repeat_pass'] == 'yes' ) {
            echo $this->label( array( 'name' => 'pass2', 'label' => $attr['re_pass_label'], 'required' => $post_id ? 'no' : 'yes' ) ); ?>
            <div class="user-fields">
                <input id="pass2" type="password" class="password" data-required="<?php echo $attr['required'] ?>" data-type="text"<?php $this->required_html5( $attr ); ?> name="pass2" value="" size="<?php echo esc_attr( $attr['size'] ) ?>" />
            </div>
            <?php
        }
        return ob_get_clean();
    }
    /**
     * Prints a repeatable field
     *
     * @param array   $attr
     * @param int|null $post_id
     */
    function repeat( $attr, $post_id, $type ) 
    {
        $add = user_assets_url .'img/add.png';
        $remove = user_assets_url. 'img/remove.png';
        ob_start(); ?>
        <div class="user-fields">
            <?php 
            if ( isset( $attr['multiple'] ) ) 
            { ?>
                <table>
                    <thead>
                        <tr>
                            <?php
                            $num_columns = count( $attr['columns'] );
                            foreach ( $attr['columns'] as $column ) 
                            { ?>
                                <th>
                                    <?php echo $column; ?>
                                </th>
                            <?php 
                            } ?>
                            <th style="visibility: hidden;">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $items = $post_id ? $this->get_meta( $post_id, $attr['name'], $type, false ) : array();
                    if ( $items ) 
                    {
                        foreach ( $items as $item_val ) 
                        {   
                            if( !is_array($item_val) ) {
                                $column_vals = explode( '| ', $item_val ); ?>
                                <tr>
                                    <?php for ( $count = 0; $count < $num_columns; $count++ ) 
                                    { ?>
                                        <td class="user-repeat-field">
                                            <input type="text" name="<?php echo $attr['name'] . '[' . $count . ']'; ?>[]" value="<?php echo esc_attr( $column_vals[$count] ); ?>" size="<?php echo esc_attr( $attr['size'] ) ?>" data-required="<?php echo $attr['required'] ?>" data-type="text"<?php $this->required_html5( $attr ); ?> />
                                        </td>
                                    <?php 
                                    } ?>
                                    <td class="user-repeat-field">
                                        <img class="user-clone-field" alt="<?php esc_attr_e( 'Add another', 'frontend_user_pro' ); ?>" title="<?php esc_attr_e( 'Add another', 'frontend_user_pro' ); ?>" src="<?php echo $add; ?>">
                                        <img class="user-remove-field" alt="<?php esc_attr_e( 'Remove this choice', 'frontend_user_pro' ); ?>" title="<?php esc_attr_e( 'Remove this choice', 'frontend_user_pro' ); ?>" src="<?php echo $remove; ?>">
                                    </td>
                                </tr>
                                <?php 
                            } else {
                                foreach ($item_val as $v) {
                                    $column_vals = explode( '| ', $v ); ?>
                                    <tr>
                                        <?php for ( $count = 0; $count < $num_columns; $count++ ) 
                                        { ?>
                                            <td class="user-repeat-field">
                                                <input type="text" name="<?php echo $attr['name'] . '[' . $count . ']'; ?>[]" value="<?php echo esc_attr( $column_vals[$count] ); ?>" size="<?php echo esc_attr( $attr['size'] ) ?>" data-required="<?php echo $attr['required'] ?>" data-type="text"<?php $this->required_html5( $attr ); ?> />
                                            </td>
                                        <?php 
                                        } ?>
                                        <td class="user-repeat-field">
                                            <img class="user-clone-field" alt="<?php esc_attr_e( 'Add another', 'frontend_user_pro' ); ?>" title="<?php esc_attr_e( 'Add another', 'frontend_user_pro' ); ?>" src="<?php echo $add; ?>">
                                            <img class="user-remove-field" alt="<?php esc_attr_e( 'Remove this choice', 'frontend_user_pro' ); ?>" title="<?php esc_attr_e( 'Remove this choice', 'frontend_user_pro' ); ?>" src="<?php echo $remove; ?>">
                                        </td>
                                    </tr>
                                    <?php 
                                }
                            }
                        }  
                    } else 
                    { ?>
                        <tr>
                            <?php for ( $count = 0; $count < $num_columns; $count++ ) { ?>
                                <td class="user-repeat-field">
                                    <input type="text" name="<?php echo $attr['name'] . '[' . $count . ']'; ?>[]" size="<?php echo esc_attr( $attr['size'] ) ?>" data-required="<?php echo $attr['required'] ?>" data-type="text"<?php $this->required_html5( $attr ); ?> />
                                </td>
                            <?php } ?>
                            <td class="user-repeat-field">
                                <img class="user-clone-field" alt="<?php esc_attr_e( 'Add another', 'frontend_user_pro' ); ?>" title="<?php esc_attr_e( 'Add another', 'frontend_user_pro' ); ?>" src="<?php echo $add; ?>">
                                <img class="user-remove-field" alt="<?php esc_attr_e( 'Remove this choice', 'frontend_user_pro' ); ?>" title="<?php esc_attr_e( 'Remove this choice', 'frontend_user_pro' ); ?>" src="<?php echo $remove; ?>">
                            </td>
                        </tr>
                        <?php 
                    } ?>
                    </tbody>
                </table>
                <?php 
            } else 
            { ?>
                <table> <?php
                $items = $post_id ? explode( '| ', $this->get_meta( $post_id, $attr['name'], $type, true ) ) : array();
                if ( $items ) 
                {
                    foreach ( $items as $item ) 
                    { ?>
                        <tr>
                            <td class="user-repeat-field">
                                <input id="user-<?php echo $attr['name']; ?>" type="text" data-required="<?php echo $attr['required'] ?>" data-type="text"<?php $this->required_html5( $attr ); ?> name="<?php echo esc_attr( $attr['name'] ); ?>[]" placeholder="<?php echo esc_attr( $attr['placeholder'] ); ?>" value="<?php echo esc_attr( $item ) ?>" size="<?php echo esc_attr( $attr['size'] ) ?>" />
                            </td>
                            <td class="user-repeat-field">
                                <img style="cursor:pointer; margin:0 3px;" alt="add another choice" title="add another choice" class="user-clone-field" src="<?php echo $add; ?>">
                                <img style="cursor:pointer;" class="user-remove-field" alt="remove this choice" title="remove this choice" src="<?php echo $remove; ?>">
                            </td>
                        </tr>
                        <?php
                    } //endforeach
                } else 
                { ?>
                    <tr>
                        <td class="user-repeat-field">
                            <input id="user-<?php echo $attr['name']; ?>" type="text" data-required="<?php echo $attr['required'] ?>" data-type="text"<?php $this->required_html5( $attr ); ?> name="<?php echo esc_attr( $attr['name'] ); ?>[]" placeholder="<?php echo esc_attr( $attr['placeholder'] ); ?>" value="<?php echo esc_attr( $attr['default'] ) ?>" size="<?php echo esc_attr( $attr['size'] ) ?>" />
                        </td>
                        <td class="user-repeat-field">
                            <img style="cursor:pointer; margin:0 3px;" alt="add another choice" title="add another choice" class="user-clone-field" src="<?php echo $add; ?>">
                            <img style="cursor:pointer;" class="user-remove-field" alt="remove this choice" title="remove this choice" src="<?php echo $remove; ?>">
                        </td>
                    </tr>
                    <?php
                } ?>
                </table>
                <?php
            }
            ?>
        </div>
        <?php
        return ob_get_clean();
    }
    /**
     * Prints a taxonomy field
     *
     * @param array   $attr
     * @param int|null $post_id
     */
    function taxonomy( $attr, $post_id ) {
        $exclude_type = isset( $attr['exclude_type'] ) ? $attr['exclude_type'] : 'exclude';
        $exclude = $attr['exclude'];
        $taxonomy = $attr['name'];
        $terms = array();
        if ( $post_id && $attr['type'] == 'text' ) 
        {
            $terms = wp_get_post_terms( $post_id, $taxonomy, array( 'fields' => 'names' ) );
        } elseif ( $post_id ) 
        {
            $terms = wp_get_post_terms( $post_id, $taxonomy, array( 'fields' => 'ids' ) );
        }
        ob_start(); ?>
        <div class="user-fields">
            <?php
        switch ( $attr['type'] ) 
        {
            case 'select':
                $required = isset( $attr['required'] ) ? $attr['required'] : '';
                $required = sprintf( 'data-required="%s" data-type="select"', $required );
                if ($attr['input_type'] == 'taxonomy') 
                {
                    $args = array(
                        'orderby'           => 'name', 
                        'order'             => 'ASC',
                        ); 
                    $terms = get_terms( $attr['name'], 'orderby=count&hide_empty=0' );
                    echo '<select class="'.$taxonomy.'" id="'.$taxonomy.'" name="'.$taxonomy.'[]" data-type="select" '.$required.'>';
                    echo "<option value='' >--Select--</option>";
                    foreach ($terms as $k => $v) 
                    { ?>
                        <option class="level-<?php echo $k; ?>" value="<?php echo $v->name; ?>" ><?php echo $v->name; ?></option>
                    <?php
                    }
                    echo "</select>";
                }else
                {
                    $selected = $terms ? $terms[0] : '';
                    $select = wp_dropdown_categories( array(
                        'show_option_none' => __( '-- Select --', 'frontend_user_pro' ),
                        'hierarchical' => 1,
                        'hide_empty' => 0,
                        'orderby' => isset( $attr['orderby'] ) ? $attr['orderby'] : 'name',
                        'order' => isset( $attr['order'] ) ? $attr['order'] : 'ASC',
                        'name' => $taxonomy . '[]',
                        'id' => $taxonomy,
                        'taxonomy' => $taxonomy,
                        'echo' => 0,
                        'title_li' => '',
                        'class' => $taxonomy,
                        $exclude_type => $exclude,
                        'selected' => $selected,
                        ) );
                    echo str_replace( '<select', '<select ' . $required, $select );
                }
            break;
            case 'multiselect':
                $selected_multiple = $terms ? $terms : array();
                $selected = is_array( $selected_multiple ) && !empty( $selected_multiple ) ? $selected_multiple[0] : '';
                $required = sprintf( 'data-required="%s" data-type="multiselect"', $attr['required'] );
                $walker = new USER_Walker_Category_Multi();
                $select = wp_dropdown_categories( array(
                        'show_option_none' => __( '-- Select --', 'frontend_user_pro' ),
                        'hierarchical' => 1,
                        'hide_empty' => 0,
                        'orderby' => isset( $attr['orderby'] ) ? $attr['orderby'] : 'name',
                        'order' => isset( $attr['order'] ) ? $attr['order'] : 'ASC',
                        'name' => $taxonomy . '[]',
                        'id' => $taxonomy,
                        'taxonomy' => $taxonomy,
                        'echo' => 0,
                        'title_li' => '',
                        'class' => $taxonomy . ' multiselect',
                        $exclude_type => $exclude,
                        'selected' => $selected,
                        'selected_multiple' => $selected_multiple,
                        'walker' => $walker
                    ) );
                echo str_replace( '<select', '<select multiple="multiple" ' . $required, $select );
                break;
            case 'checkbox':
                printf( '<span data-required="%s" data-type="tax-checkbox" />', $attr['required'] );
                user_category_checklist( $post_id, false, $attr );
                break;
            case 'text': ?>
                <input class="textfield<?php echo $this->required_class( $attr ); ?>" id="<?php echo $attr['name']; ?>" type="text" data-required="<?php echo $attr['required'] ?>" data-type="text"<?php $this->required_html5( $attr ); ?> name="<?php echo esc_attr( $attr['name'] ); ?>" value="<?php echo esc_attr( implode( ', ', $terms ) ); ?>" size="40" />
                <script type="text/javascript">
                    jQuery(function($) {
                        $('#<?php echo $attr['name']; ?>').suggest( user_form.ajaxurl + '?action=ajax-tag-search_array&tax=<?php echo $attr['name']; ?>', { delay: 500, minchars: 2, multiple: true, multipleSep: ', ' } );
                    });
                </script> <?php
                break;
            default:
                // code...
                break;
        } ?>
        </div>
        <?php
        return ob_get_clean();
    }
    /**
     * Prints a HTML field
     *
     * @param array   $attr
     */
    function html( $attr ) {
        ob_start(); ?>
        <div class="user-fields">
            <?php echo do_shortcode( $attr['html'] ); ?>
        </div>
        <?php
        return ob_get_clean();
    }
    function custom_css1( $attr ) {
        ob_start(); ?>
        <div class="user-fields">
            <style type="text/css">
            <?php echo do_shortcode( $attr['custom_css1'] ); ?>
            </style>
        </div>
        <?php
        return ob_get_clean();
    }
    function js( $attr ) 
    {
        ob_start();
        ?>
        <div class="user-fields">
            <script type="text/javascript">
            <?php echo do_shortcode( $attr['js'] ); ?>
            </script>
        </div>
        <?php
        return ob_get_clean();
    }
    /**
     * Prints a toc field
     *
     * @param array   $attr
     */
    function toc( $attr, $post_id ) {
        ob_start(); ?>
        <div class="user-label">
            &nbsp;
        </div>
        <div class="user-fields">
            <span data-required="yes" data-type="radio"></span>
            <label>
                <?php $id = $attr['select']; ?>
                <input type="checkbox" name="user_accept_toc" required="required" /><a href="<?php echo get_permalink($id); ?>"><?php echo $attr['message']; ?> </a> 
            </label>
        </div>
        <?php 
        
       return ob_get_clean();
    }
    /**
     * Prints recaptcha field
     *
     * @param array   $attr
     */
    function recaptcha( $attr, $post_id, $type ) 
    {
        if( $type != 'registration' && !empty($post_id)) 
        {
            return;
        }
        ob_start();
        ?>
        <div class="user-fields">
            <script type="text/javascript"> var RecaptchaOptions = { theme : 'clean' };</script>
            <?php echo recaptcha_get_html( FRONTEND_USER()->helper->get_option( 'user-recaptcha-public-key', '', is_ssl() ) ); ?>
        </div>
        <?php
        return ob_get_clean();
    }
    /**
     * Prints a section break
     *
     * @param array   $attr
     * @param int|null $post_id
     */
    function section_break( $attr ) {
        ob_start(); ?>
        <div class="user-section-wrap">
            <h2 class="user-section-title"><?php echo $attr['label']; ?></h2>
            <div class="user-section-details"><?php echo $attr['description']; ?></div>
        </div>
        <?php
        return ob_get_clean();
    }
    /**
     * Prints a action hook
     *
     * @param array   $attr
     * @param int     $form_id
     * @param int|null $post_id
     * @param array   $form_settings
     */
    function action_hook( $attr, $form_id, $post_id, $form_settings ) 
    {
        if ( !empty( $attr['label'] ) ) {
            do_action( $attr['label'], $form_id, $post_id, $form_settings );
        }
    }
    /**
     * Prints a date field
     *
     * @param array   $attr
     * @param int|null $post_id
     */
    function date( $attr, $post_id, $type ) 
    {
        $value = $post_id ? $this->get_meta( $post_id, $attr['name'], $type, true ) : '';
        ob_start(); ?>
        <div class="user-fields">
            <input id="user-date-<?php echo $attr['name']; ?>" type="text" class="datepicker" data-required="<?php echo $attr['required'] ?>" data-type="text"<?php $this->required_html5( $attr ); ?> name="<?php echo esc_attr( $attr['name'] ); ?>" value="<?php echo esc_attr( $value ) ?>" size="30" />
        </div>
        <script type="text/javascript">
            jQuery(function($) 
            {
                <?php 
                if ( $attr['time'] == 'yes' ) 
                { ?>
                    $("#user-date-<?php echo $attr['name']; ?>").datetimepicker({ dateFormat: '<?php echo $attr["format"]; ?>' });
                    <?php 
                } else 
                { ?>
                    $("#user-date-<?php echo $attr['name']; ?>").datepicker({ dateFormat: '<?php echo $attr["format"]; ?>' });
                    <?php 
                } ?>
            });
        </script>
        <?php
        return ob_get_clean();
    }
    function really_simple_captcha( $attr, $post_id,$type) 
    {
        if ( $post_id ) {
            return;
        }
        if ( !class_exists( 'ReallySimpleCaptcha' ) ) {
            _e( 'Error: Really Simple Captcha plugin not found!', 'frontend_user_pro' );
            return;
        }
        $captcha_instance = new ReallySimpleCaptcha();
        $word = $captcha_instance->generate_random_word();
        $prefix = mt_rand();
        $image_num = $captcha_instance->generate_image( $prefix, $word );
        ?>
        <div class="user-fields">
            <img src="<?php echo plugins_url( 'really-simple-captcha/tmp/' . $image_num ); ?>" alt="Captcha" />
            <input type="text" name="rs_captcha" value="" />
            <input type="hidden" name="rs_captcha_val" value="<?php echo $prefix; ?>" />
        </div>
        <?php
    }
    function remember( $attr, $post_id, $type ) 
    {
        $value = $post_id ? $this->get_meta( $post_id, $attr['name'], $type, true ) : '';
        ob_start(); ?>
        <div class="user-fields">
            <span data-required="<?php echo $attr['required'] ?>" data-type="radio"></span>
            <label>
                <input type="checkbox" name="<?php echo $attr['name']; ?>" value="1"<?php //echo  ? ' checked="checked"' : ''; ?> />
            </label>
        </div>
        <?php
        return ob_get_clean();
    }
    function map( $attr, $post_id, $type ) {
        $value = $post_id ? $this->get_meta( $post_id, $attr['name'], $type, true ) : '';
        $type = $attr['show_lat'] == 'yes' ? 'text' : 'hidden';
        if ( $post_id && $value ) {
            list( $def_lat, $def_long ) = explode( ',', $value );
        } else {
            list( $def_lat, $def_long ) = explode( ',', $attr['default_pos'] );
        }
        
        ?>
        <div class="user-fields">
            <input id="user-map-lat-<?php echo $attr['name']; ?>" type="<?php echo $type; ?>" data-required="<?php echo $attr['required'] ?>" data-type="text"<?php $this->required_html5( $attr ); ?> name="<?php echo esc_attr( $attr['name'] ); ?>" value="<?php echo esc_attr( $value ) ?>" size="30" />
            <?php if ( $attr['address'] == 'yes' ) { ?>
                <input id="user-map-add-<?php echo $attr['name']; ?>" type="text" value="" name="find-address" placeholder="<?php _e( 'Type an address to find', 'frontend_user_pro' ); ?>" size="30" />
                <button class="user-button button" id="user-map-btn-<?php echo $attr['name']; ?>"><?php _e( 'Find Address', 'frontend_user_pro' ); ?></button>
            <?php } ?>
            <div class="google-map" style="margin: 10px 0; height: 250px; width: 450px;" id="user-map-<?php echo $attr['name']; ?>"></div>
            <span class="user-help"><?php echo $attr['help']; ?></span>
        </div>
        <script type="text/javascript">
            (function($) {
                $(function() {
                    var def_zoomval = <?php echo $attr['zoom']; ?>;
                    var def_longval = <?php echo $def_long ? $def_long : 0; ?>;
                    var def_latval = <?php echo $def_lat ? $def_lat : 0; ?>;
                    var curpoint = new google.maps.LatLng(def_latval, def_longval),
                        geocoder   = new window.google.maps.Geocoder(),
                        $map_area = $('#user-map-<?php echo $attr['name']; ?>'),
                        $input_area = $( '#user-map-lat-<?php echo $attr['name']; ?>' ),
                        $input_add = $( '#user-map-add-<?php echo $attr['name']; ?>' ),
                        $find_btn = $( '#user-map-btn-<?php echo $attr['name']; ?>' );
                        
                    autoCompleteAddress();
                    $find_btn.on('click', function(e) {
                        e.preventDefault();
                        geocodeAddress( $input_add.val() );
                    });
                    var gmap = new google.maps.Map( $map_area[0], {
                        center: curpoint,
                        zoom: def_zoomval,
                        mapTypeId: window.google.maps.MapTypeId.ROADMAP
                    });
                    var marker = new window.google.maps.Marker({
                        position: curpoint,
                        map: gmap,
                        draggable: true
                    });
                    window.google.maps.event.addListener( gmap, 'click', function ( event ) {
                        marker.setPosition( event.latLng );
                        updatePositionInput( event.latLng );
                    } );
                    window.google.maps.event.addListener( marker, 'drag', function ( event ) {
                        updatePositionInput(event.latLng );
                    } );
                    function updatePositionInput( latLng ) {
                        $input_area.val( latLng.lat() + ',' + latLng.lng() );
                    }
                    function updatePositionMarker() {
                        var coord = $input_area.val(),
                            pos, zoom;
                        if ( coord ) {
                            pos = coord.split( ',' );
                            marker.setPosition( new window.google.maps.LatLng( pos[0], pos[1] ) );
                            zoom = pos.length > 2 ? parseInt( pos[2], 10 ) : 12;
                            gmap.setCenter( marker.position );
                            gmap.setZoom( zoom );
                        }
                    }
                    function geocodeAddress( address ) {
                        geocoder.geocode( {'address': address}, function ( results, status ) {
                            if ( status == window.google.maps.GeocoderStatus.OK ) {
                                updatePositionInput( results[0].geometry.location );
                                marker.setPosition( results[0].geometry.location );
                                gmap.setCenter( marker.position );
                                gmap.setZoom( 15 );
                            }
                        } );
                    }
                    
                    function autoCompleteAddress(){
                        if (!$input_add) return null;
                        $input_add.autocomplete({
                            source: function(request, response) {
                                // TODO: add 'region' option, to help bias geocoder.
                                geocoder.geocode( {'address': request.term }, function(results, status) {
                                    response(jQuery.map(results, function(item) {
                                        return {
                                            label     : item.formatted_address,
                                            value     : item.formatted_address,
                                            latitude  : item.geometry.location.lat(),
                                            longitude : item.geometry.location.lng()
                                        };
                                    }));
                                });
                            },
                            select: function(event, ui) {
                                $input_area.val(ui.item.latitude + ',' + ui.item.longitude );       
                                var location = new window.google.maps.LatLng(ui.item.latitude, ui.item.longitude);
                                gmap.setCenter(location);
                                // Drop the Marker
                                setTimeout( function(){
                                    marker.setValues({
                                        position    : location,
                                        animation   : window.google.maps.Animation.DROP
                                    });
                                }, 1500);
                            }
                        });
                    }
                });
            })(jQuery);
        </script>
        <?php
    }
    // submit button
    function submit_button( $form_id = false, $type = 'post', $id = false, $args = array() ) {
        if ( !$form_id ) {
            return __( 'Invalid USER Form ID', 'frontend_user_pro' );
        }
        global $frontend_options;
        $color = isset( $frontend_options[ 'checkout_color' ] ) ? $frontend_options[ 'checkout_color' ] : 'blue';
        $color = ( $color == 'inherit' ) ? '' : $color;
        $style = isset( $frontend_options[ 'button_style' ] ) ? $frontend_options[ 'button_style' ] : 'button';
        switch ( $type ) {
            case 'post': ?>
                <fieldset class="user-submit">
                    <div class="user-label">
                        &nbsp;
                    </div>
                    <?php wp_nonce_field( 'user-form-post-form' ); ?>
                    <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
                    <input type="hidden" name="user_id" value="<?php echo get_current_user_id(); ?>">
                    <input type="hidden" name="page_id" value="<?php echo get_post() ? get_the_ID() : '0'; ?>">
                    <input type="hidden" name="action" value="user_submit_post">
                    <?php
                    if ( $id ) 
                    {
                        $cur_post = get_post( $id );
                        ?>
                        <input type="hidden" name="post_id" value="<?php echo $id; ?>">
                        <input type="hidden" name="post_author" value="<?php echo esc_attr( $cur_post->post_author ); ?>">
                        <input type="hidden" name="post_status" value="edit">
                        <input type="submit" class="frontend-submit <?php echo $color; ?> <?php echo $style; ?>" name="submit" value="<?php echo __( 'Update', 'frontend_user_pro' ); ?>" />
                        <?php 
                    } else 
                    { ?>
                        <input type="hidden" name="post_status" value="new">
                        <input type="submit" class="frontend-submit <?php echo $color; ?> <?php echo $style; ?>" name="submit" value="<?php echo __( 'Submit', 'frontend_user_pro' ); ?>" />
                        <?php 
                    }
                    break;
            case 'login': ?>
                <fieldset class="user-submit">
                    <div class="user-label">
                        &nbsp;
                    </div>
                    <?php wp_nonce_field( 'user-form-login' ); ?>
                    <input type="hidden" name="action" value="user_submit_login">
                    <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
                    <input type="submit" class="frontend-submit <?php echo $color; ?> <?php echo $style; ?>" name="submit" value="<?php echo __( 'Login', 'frontend_user_pro' ); ?>" />
                    <a href="#" id="user_lost_password_link" title="<?php _e( 'Lost Password?', 'frontend_user_pro' ); ?>"><?php _e( 'Lost Password?', 'frontend_user_pro' ); ?></a>
                </fieldset>
                <?php
                break;
            case 'registration': 
                $form_settings = get_post_meta( $form_id, 'user_form_settings', true );
                if ( is_user_logged_in() ) 
                { 
                    if ($form_settings) 
                    {
                        if ($form_settings['update_text']) 
                        {
                            $up = $form_settings['update_text'];
                        }else
                        {
                            $up = 'Update';
                        }
                            $wording = $up;
                    }else
                    {
                        $wording = 'Update';
                    }
                } else 
                { 
                    if ($form_settings) 
                    {
                        if ($form_settings['submit_text']) 
                        {
                            $sub = $form_settings['submit_text'];
                        }else
                        {
                            $sub = 'Update';
                        }
                        $wording = $sub;
                    }else
                    {
                        $wording = 'Update';
                    }
                } 
                $atts = shortcode_atts( array(
                    'redirect_to' => home_url()
                    ), $id );
                extract($atts); ?>
                <fieldset class="user-submit">
                    <div class="user-label">
                        &nbsp;
                    </div>
                    <?php wp_nonce_field( 'user-form-registration', '_wpnonce', false ); ?>
                    <input type="hidden" value="<?php echo $redirect_to; ?>" name="_wp_http_referer">
                    <input type="hidden" name="action" value="user_submit_registration">
                    <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
                    <?php if( $id ) : ?>
                        <input type="hidden" name="user_id" value="<?php echo $id; ?>">
                    <?php endif; ?>
                    <?php if( is_admin() ) : ?>
                        <input type="hidden" name="is_admin" value="1">
                    <?php endif; ?>
                    <input type="submit" class="frontend-submit <?php echo $color; ?> <?php echo $style; ?>" name="submit" value="<?php echo $wording; ?>" />
                </fieldset>
                <?php
                break;
            default:
                break;
        } // endswitch
    }
    // validate fields
    function validate_re_captcha() {
        $recap_challenge = isset( $_POST['recaptcha_challenge_field'] ) ? $_POST['recaptcha_challenge_field'] : '';
        $recap_response = isset( $_POST['recaptcha_response_field'] ) ? $_POST['recaptcha_response_field'] : '';
        $private_key = FRONTEND_USER()->helper->get_option( 'user-recaptcha-private-key', '' );
        $resp = recaptcha_check_answer( $private_key, $_SERVER["REMOTE_ADDR"], $recap_challenge, $recap_response );
        if ( !$resp->is_valid ) {
            $this->signal_error( __( 'reCAPTCHA validation failed', 'frontend_user_pro' ) );
        }
    }
    function validate_rs_captcha() {
        $rs_captcha_input = isset( $_POST['rs_captcha'] ) ? $_POST['rs_captcha'] : '';
        $rs_captcha_file = isset( $_POST['rs_captcha_val'] ) ? $_POST['rs_captcha_val'] : '';
         if ( class_exists( 'ReallySimpleCaptcha' ) ) {
            $captcha_instance = new ReallySimpleCaptcha();
            if ( !$captcha_instance->check( $rs_captcha_file, $rs_captcha_input ) ) {
                $this->signal_error( __( 'Really Simple Captcha validation failed', 'frontend_user_pro' ) );
            } 
            else {
                // validation success, remove the files
                $captcha_instance->remove( $rs_captcha_file );
            }
         }
    }
    // signal error when using AJAX
    function signal_error( $error ) {
        echo json_encode( array( 'success' => false, 'error' => $error ) );
        die();
    }
    // search_array nD matrix
    function search_array( $array, $key, $value ) {
        $results = array();
        if ( is_array( $array ) ) {
            if ( isset( $array[$key] ) && $array[$key] == $value )
                $results[] = $array;
            foreach ( $array as $subarray )
                $results = array_merge( $results, $this->search_array( $subarray, $key, $value ) );
        }
        return $results;
    }
   public static function update_user_meta( $meta_vars, $user_id ) {
        // prepare meta fields
        list( $meta_key_value, $multi_repeated, $files ) = self::prepare_meta_fields( $meta_vars );
        // set featured image if there's any
        if ( ! empty( $_POST[ 'avatar_id' ] ) ) {
            $attachment_id = absint( $_POST[ 'avatar_id' ] );
            user_update_avatar( $user_id, $attachment_id ,'avatar');
        } else {
            delete_user_meta( $user_id, 'user_avatar' );
        }
        
        // save all custom fields
        foreach ( $meta_key_value as $meta_key => $meta_value ) {
            update_user_meta( $user_id, $meta_key, $meta_value );
        }
        // save any multicolumn repeatable fields
        foreach ( $multi_repeated as $repeat_key => $repeat_value ) {
            // first, delete any previous repeatable fields
            delete_user_meta( $user_id, $repeat_key );
            // now add them
            update_user_meta( $user_id, $repeat_key, $repeat_value ); // save array as a serialized
            
        } //foreach
        // save any files attached
        foreach ( $files as $file_input ) {
    
            if ( !isset( $_POST[ $file_input[ 'name' ] ] ) ){
                continue;
            }
            
            delete_user_meta( $user_id, $file_input[ 'name' ] );
            $relative_url = array();  
            if ($file_input[ 'name' ] == 'image_upload') 
            {
                foreach ($_POST[ $file_input[ 'name' ] ] as $file => $id) {
                    $attachment_id = $id;
                    $relative_url[] = wp_get_attachment_url( $attachment_id );
                }
                 //$relative = serialize($relative_url);
                update_user_meta( $user_id, 'image_upload', $relative_url );
            }else
            {
           
                foreach ($_POST[ $file_input[ 'name' ] ] as $file => $id) 
                {
                    if (is_numeric($id)) 
                    {
                        $relative_url[] = wp_get_attachment_url( $id ) ;
                    }else{
                        //$relative_url[] = self::user_get_attachment_id_from_url( $id );
                        $attachment_id = self::user_get_attachment_id_from_url( $id );
                        // self::user_associate_attachment( $attachment_id, $post_id );
                        $relative_url[] = wp_get_attachment_url( $attachment_id );
                    }
                }
               // $relative = serialize($relative_url);
                update_user_meta( $user_id, $file_input[ 'name' ], $relative_url );
            }
            
        }
    }
    public static  function user_get_attachment_id_from_url( $attachment_url = '' ) {
    global $wpdb;
    $attachment_id = false;
    // If there is no url, return.
    if ( '' == $attachment_url )
        return;
    // Get the upload directory paths
    $upload_dir_paths = wp_upload_dir();
    // Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
    if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {
        // If this is the URL of an auto-generated thumbnail, get the URL of the original image
        $attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );
        // Remove the upload path base directory from the attachment URL
        $attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );
        // Finally, run a custom database query to get the attachment ID from the modified attachment URL
        $attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );
    }
    return $attachment_id;
}
    function user_update_avatar( $user_id, $attachment_id) {
        $upload_dir = wp_upload_dir();
        $relative_url = wp_get_attachment_url( $attachment_id );
      
        if ( function_exists( 'wp_get_image_editor' ) ) {
        // try to crop the photo if it's big
            $file_path = str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $relative_url );
        // as the image upload process generated a bunch of images
        // try delete the intermediate sizes.
            $ext = strrchr( $file_path, '.' );
            $file_path_w_ext = str_replace( $ext, '', $file_path );
            $small_url = $file_path_w_ext . '-avatar' . $ext;
         
            
            $relative_url = str_replace( $upload_dir['basedir'], $upload_dir['baseurl'], $small_url );
            $editor = wp_get_image_editor( $file_path );
            if ( !is_wp_error( $editor ) ) {
                $editor->resize( 100, 100, true );
                $editor->save( $small_url );
            // if the file creation successfull, delete the original attachment
                if ( file_exists( $small_url ) ) {
                    wp_delete_attachment( $attachment_id, true );
                }
            }
        }
        $prev_avatar = get_user_meta( $user_id, 'user_avatar', true );
        if ( !empty( $prev_avatar ) ) {
            $prev_avatar_path = str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $prev_avatar );
            if ( file_exists( $prev_avatar_path ) ) {
                unlink( $prev_avatar_path );
            }
        }
            // now update new user avatar
        update_user_meta( $user_id, 'user_avatar', $relative_url );
             // delete any previous avatar
    }
 
    public static function update_post_meta( $meta_vars, $post_id ) {
        // prepare the meta vars
        list( $meta_key_value, $multi_repeated, $files ) = self::prepare_meta_fields( $meta_vars );
        // set featured image if there's any
        // if not in admin or if in admin (but doing an ajax call)
        if ( ( is_admin() && defined( 'DOING_AJAX' ) && DOING_AJAX ) || !is_admin() ){
            if ( isset( $_POST[ 'feat-image-id' ] ) && $_POST[ 'feat-image-id' ] != 0 ) {
                $attachment_id = $_POST[ 'feat-image-id' ];
                self::user_associate_attachment( $attachment_id, $post_id );
                set_post_thumbnail( $post_id, $attachment_id );
            }
            if ( !isset( $_POST[ 'feat-image-id' ] ) || $_POST[ 'feat-image-id' ] == 0 ) {
               // delete_post_thumbnail( $post_id );
            }
        }
        // save all custom fields
        foreach ( $meta_key_value as $meta_key => $meta_value ) {
            update_post_meta( $post_id, $meta_key, $meta_value );
        }
        
        // save any multicolumn repeatable fields
        foreach ( $multi_repeated as $repeat_key => $repeat_value ) {
            // first, delete any previous repeatable fields
            delete_post_meta( $post_id, $repeat_key );
            // now add them
            foreach ( $repeat_value as $repeat_field ) {
                update_post_meta( $post_id, $repeat_key, $repeat_field );
            }
        }
        foreach ( $files as $file_input ) {
            if ( !isset( $_POST[ $file_input[ 'name' ] ] ) ){
                continue;
            }
            $relative_url = array();
            // delete_post_meta( $post_id, $file_input[ 'name' ] );
            if ($file_input[ 'type' ] == 'image_upload') 
            {
                foreach ( $_POST[ $file_input[ 'name' ] ] as $file => $url )
                {
                    if ( empty ($url) )
                    {
                        continue;
                    }
                    $author_id = 0;
                    if( ! current_user_can( 'manage_shop_settings' ) ) {
                        $author_id = get_post_field( 'post_author', $post_id );
                    }
                    $attachment_id = $url;
                    $relative_url[] = wp_get_attachment_url( $attachment_id );
                    $relative = serialize($relative_url);
                    
                }
                update_post_meta( $post_id, $file_input[ 'name' ], $relative );
            }else
            {
                if (!is_user_logged_in() ) {
                    foreach ( $_POST[ $file_input[ 'name' ] ] as $file => $url )
                    {
                        if ( empty ($url) )
                        {
                            continue;
                        }
                        $author_id = 0;
                        if( ! current_user_can( 'manage_shop_settings' ) ) {
                            $author_id = get_post_field( 'post_author', $post_id );
                        }
                        $relative_url[] = wp_get_attachment_url($url);
                        // $relative_url[] = $url;
                        //$relative = serialize($relative_url);
                    }
                    update_post_meta( $post_id, $file_input[ 'name' ], $relative_url );
                }else
                {
                    foreach ( $_POST[ $file_input[ 'name' ] ] as $file => $url )
                    {
                        if ( empty ($url) )
                        {
                            continue;
                        }
                        $author_id = 1;
                        if( ! current_user_can( 'manage_shop_settings' ) ) {
                            $author_id = get_post_field( 'post_author', $post_id );
                        }
                        $attachment_id = self::user_get_attachment_id_from_url( $url );
                        self::user_associate_attachment( $attachment_id, $post_id );
                        $relative_url[] = wp_get_attachment_url( $attachment_id );
                    
                    }
                    update_post_meta( $post_id, $file_input[ 'name' ], $relative_url );
                }
            }
        }
    }
    public static function user_associate_attachment( $attachment_id, $post_id ) {
        global $wpdb;
        $tt = $wpdb->prefix."posts";
           $wpdb->update(
            $tt,
            array(
                'post_parent' => $post_id,
                ),
            array(
                'ID' => $attachment_id,
                ),
            array(
                '%d'
                ),
            array(
                '%d'
                )
            );
    }
    function required_mark( $attr ) {
        if ( isset( $attr['required'] ) && $attr['required'] == 'yes' ) {
            return apply_filters( 'user_required_mark', ' <span class="frontend-required-indicator">*</span>', $attr );
        }
    }
    function required_html5( $attr ) {
        if ( isset( $attr['required'] ) && $attr['required'] == 'yes' ) {
            echo apply_filters( 'user_required_html5', ' required="required"', $attr );
        }
    }
    function required_class( $attr ) {
        if ( isset( $attr['required'] ) && $attr['required'] == 'yes' ) {
            echo apply_filters( 'user_required_class', ' frontend-required-indicator', $attr );
        }
    }
    function label( $attr, $post_id = 0 ) {
        // echo "<pre>";
        // print_r($attr);
        // echo "</pre>";
        if ( $post_id && $attr['input_type'] == 'password' ) {
            $attr['required'] = 'no';
        }
        ob_start(); 
        if (array_key_exists('label', $attr)) {
         ?>
         <div class="user-label">
            <label for="user-<?php echo isset( $attr['name'] ) ? $attr['name'] : 'cls'; ?>"><?php echo $attr['label'] . $this->required_mark( $attr ); ?></label>
            <br />
            <?php if ( ! empty( $attr['help'] ) ) : ?>
                <span class="user-help"><?php echo $attr['help']; ?></span>
            <?php endif; ?>
        </div>
        <?php
    }
    
        return ob_get_clean();
    }
    function is_meta( $attr ) {
        // echo "<pre>";
        // print_r($attr);
        // echo "</pre>";
        if ( isset( $attr['is_meta'] ) && $attr['is_meta'] == 'yes' ) {
            return true;
        }
        return false;
    }
    function get_meta( $object_id, $meta_key, $type = 'post', $single = true ) {
        if ( !$object_id ) {
            return '';
        }
        if ( $type == 'post' ) {
            return get_post_meta( $object_id, $meta_key, $single );
        }
        
        return get_user_meta( $object_id, $meta_key, $single );
    }
    function get_user_data( $user_id, $field ) {
        return get_user_by( 'id', $user_id )->$field;
    }
    function guess_username( $email ) {
        // username from email address
        $username = sanitize_user( substr( $email, 0, strpos( $email, '@' ) ) );
        if ( !username_exists( $username ) ) {
            return $username;
        }
        // try to add some random number in username
        // and may be we got our username
        $username .= rand( 1, 199 );
        if ( !username_exists( $username ) ) {
            return $username;
        }
    }
}
