<?php
if (!defined('ABSPATH')) {
  exit;
}

add_filter('post_updated_messages', 'user_forms_form_updated_message');
add_action('add_meta_boxes_user-forms', 'user_forms_add_meta_boxes');
add_action('add_meta_boxes_user_logins', 'user_logins_add_meta_boxes');
add_action('add_meta_boxes_user_registrations', 'user_registrations_add_meta_boxes');
add_action('wp_ajax_user-form_add_el', 'user_forms_ajax_post_add_field');
add_action( 'wp_ajax_nopriv_user-form_add_el', array( $this, 'user_forms_ajax_post_add_field' ) );
add_action('save_post', 'user_forms_save_form', 1, 2);

function user_forms_form_updated_message($messages) {
  $message = array(
    0 => '',
    1 => __('Form updated.', 'frontend_user_pro') ,
    2 => __('Custom field updated.', 'frontend_user_pro') ,
    3 => __('Custom field deleted.', 'frontend_user_pro') ,
    4 => __('Form updated.', 'frontend_user_pro') ,
    5 => isset($_GET['revision']) ? sprintf(__('Form restored to revision from %s', 'frontend_user_pro') , wp_post_revision_title((int)$_GET['revision'], false)) : false,
    6 => __('Form published.', 'frontend_user_pro') ,
    7 => __('Form saved.', 'frontend_user_pro') ,
    8 => __('Form submitted.', 'frontend_user_pro') ,
    9 => '',
    10 => __('Form draft updated.', 'frontend_user_pro') ,
  );
  $messages['user-forms'] = $message;
  $messages['user_logins'] = $message;
  $messages['user_registrations'] = $message;
  return $messages;
}

function user_forms_add_meta_boxes() {
  global $post;
    add_meta_box('user-metabox-editor', __('Mife Form Editor', 'frontend_user_pro') , 'user_forms_metabox', 'user-forms', 'normal', 'high');
    add_meta_box('user-metabox-save', __('Save', 'frontend_user_pro') , 'user_forms_form_elements_save', 'user-forms', 'side', 'core');
    add_meta_box('user-metabox-fields', __('Add a Field', 'frontend_user_pro') , 'user_forms_elements_callback', 'user-forms', 'side', 'core');
add_meta_box( 
        'my-meta-box',
        __('Shortcode', 'frontend_user_pro'),
        'user_forms_form_elements_sc',
        'user-forms',
        'side',
        'core'
    );
  do_action('user_add_custom_meta_boxes', get_the_ID());
  remove_meta_box('submitdiv', 'user-forms', 'side');
  remove_meta_box('slugdiv', 'user-forms', 'normal');
}

function user_logins_add_meta_boxes() {
  global $post;

  add_meta_box('user-metabox-editor', __('Login Form Editor', 'frontend_user_pro') , 'user_forms_metabox', 'user_logins', 'normal', 'high');
  add_meta_box('user-metabox-save', __('Save', 'frontend_user_pro') , 'user_forms_form_elements_save', 'user_logins', 'side', 'core');
  add_meta_box('user-metabox-fields', __('Add a Field', 'frontend_user_pro') , 'user_forms_elements_callback', 'user_logins', 'side', 'core');
add_meta_box( 
        'my-meta-box',
        __('Shortcode', 'frontend_user_pro'),
        'user_forms_form_elements_sc',
        'user_logins',
        'side',
        'core'
    );
  do_action('user_add_custom_meta_boxes', get_the_ID());
  remove_meta_box('submitdiv', 'user_logins', 'side');
  remove_meta_box('slugdiv', 'user_logins', 'normal');
}

function user_registrations_add_meta_boxes() {
  global $post;

  add_meta_box('user-metabox-editor', __('Registration Form Editor', 'frontend_user_pro') , 'user_forms_metabox', 'user_registrations', 'normal', 'high');
  //add_meta_box('user-metabox-fields1', __('Shortcode', 'frontend_user_pro') , 'user_forms_form_elements_sc', 'user_registrations', 'side', 'high');
  add_meta_box('user-metabox-save', __('Save', 'frontend_user_pro') , 'user_forms_form_elements_save', 'user_registrations', 'side', 'core');
  add_meta_box('user-metabox-fields', __('Add a Field', 'frontend_user_pro') , 'user_forms_form_elements_registration', 'user_registrations', 'side', 'core');
add_meta_box( 
        'my-meta-box',
        __('Shortcode', 'frontend_user_pro'),
        'user_forms_form_elements_sc',
        'user_registrations',
        'side',
        'core'
    );
  do_action('user_add_custom_meta_boxes', get_the_ID());
  remove_meta_box('submitdiv', 'user_registrations', 'side');
  remove_meta_box('slugdiv', 'user_registrations', 'normal');
}

function user_forms_publish_button() {
  global $post, $pagenow;
  ?> 
    <div id="minor-publishing-actions">
      <center>
      <?php if( !array_key_exists('post', $_GET)) { ?>
      <input type="hidden" value="Publish" id="original_publish" name="original_publish">      
      <input type="submit" value="Publish" class="button button-primary button-large" id="publish" name="publish">
      <?php } else { ?>
      <input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e('Save') ?>" />
      <input name="save" type="submit" class="button button-primary button-large" id="publish" accesskey="p" value="<?php esc_attr_e('Save') ?>" />
      <?php } ?> 
      <span class="spinner"></span>
      </center>
    </div>
    <div class="user-clear"></div>
  <?php
}

function user_forms_metabox($post) {  
  if( $post->post_type == 'user_registrations' ) {
    $title = __('USER Registration Form Editor', 'frontend_user_pro'); 
    $title = apply_filters('user_forms_metabox_title', $title, get_the_ID());  

  } elseif( $post->post_type == 'user-forms' ) {
    $title = __('USER Mi Fe Form Editor', 'frontend_user_pro');
    $title = apply_filters('user_forms_metabox_title', $title, get_the_ID()); 

  } elseif( $post->post_type == 'user_logins' ) {
    $title = __('USER Login Form Editor', 'frontend_user_pro');
    $title = apply_filters('user_forms_metabox_title', $title, get_the_ID());
  }    
  ?><style type="text/css">#post-body-content {display: block;}</style><?php
  ?>

  <h1><?php //echo $title; ?></h1>
  <h2 class="nav-tab-wrapper">
            <a href="#user-metabox" class="nav-tab" id="user_general-tab"><?php _e( 'Form Editor', 'frontend_user_pro' ); ?></a>
            <a href="#user-metabox-settings" class="nav-tab" id="user_dashboard-tab"><?php _e( 'Settings', 'frontend_user_pro' ); ?></a>
            <?php
             if( $post->post_type == 'user_registrations' ) { ?>
            <a href="#user-metabox-notification" class="nav-tab" id="user_notification-tab"><?php _e( 'Notification', 'frontend_user_pro' ); ?></a>
            <?php } ?>
            <?php do_action( 'user_profile_form_tab' ); ?>
        </h2> 

  <div class="tab-content">
      <div id="user-metabox" class="group">
           <?php user_forms_edit_form_area(); ?>
      </div> 

      <div id="user-metabox-settings" class="group">
        <?php user_form_settings_area(); ?>
      </div>
      <?php
      if( $post->post_type == 'user_registrations' ) 
        { ?>
      <div id="user-metabox-notification" class="group">
        <?php user_form_notification_area(); ?>
      </div>
      <?php  } ?>
  </div>
  <?php
}

function user_forms_form_elements_sc() {
  ?>
  <div class="submitbox" id="submitpost">
    <div id="minor-publishing">

      <?php 
      if (get_post_type() == 'user_logins' ) {
        ?>
        <p>Use the shortcode</p>
        <input type="text" name="sc" value="<?php echo "[wpfeup-login id='".get_the_ID()."']" ;  ?>" style="width: 100%;" readonly>
        <p>to display this form inside a post, page or text widget.</p>
        <?php
      } else if (get_post_type() == 'user_registrations' ) { ?>
      <p>Use the shortcode</p>
      <p>Registration:</p>
      <input type="text" name="sc" value="<?php echo "[wpfeup-register type='registration' id='".get_the_ID()."']" ;  ?>" style="width: 100%;" readonly>
      <p>Edit Profile:</p>
      <input type="text" name="sc" value="<?php echo "[wpfeup-register type='profile' id='".get_the_ID()."']" ;  ?>" style="width: 100%;" readonly>
      <p>to display this form inside a post, page or text widget.</p>
      <?php

       // echo "Registration:<br> [wpfeup-register type='registration' id='".get_the_ID()."']<br>Edit Profile:<br> [wpfeup-register type='profile' id='".get_the_ID()."']";
    }       ?>
   </div>
 </div>
 <?php
}

function user_forms_form_elements_save() {
  ?>
  <div class="submitbox" id="submitpost">
    <div id="minor-publishing">

      <?php user_forms_publish_button(); ?>
    </div>
  </div>
  <?php
}

function user_forms_elements_callback() {
  $title = esc_attr(__('Click to add to the editor', 'frontend_user_pro'));
  ?>
    <style type="text/css">
    .custom-field .enable_conditional_input_check, 
    .user_bio .enable_conditional_input_check {
    display: none;
}
  </style>
  <div class="user-loading hide"></div>
  <div class="user-form-buttons">
  <button class="user-button button" data-name="first_name" data-type="textarea" title="<?php echo $title; ?>"><?php _e('First Name', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="last_name" data-type="textarea" title="<?php echo $title; ?>"><?php _e('Last Name', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="user_login" data-type="text" title="<?php echo $title; ?>"><?php _e('Username', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="password" data-type="password" title="<?php echo $title; ?>"><?php _e('Password', 'frontend_user_pro'); ?></button>
    <button class="user-button button" data-name="user_email" data-type="email" title="<?php echo $title; ?>"><?php _e('User Email', 'frontend_user_pro'); ?></button>

  <button class="user-button button" data-name="nickname" data-type="text" title="<?php echo $title; ?>"><?php _e('Nickname', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="display_name" data-type="text" title="<?php echo $title; ?>"><?php _e('Display Name', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="user_bio" data-type="textarea" title="<?php echo $title; ?>"><?php _e('Biography', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="user_url" data-type="text" title="<?php echo $title; ?>"><?php _e('Website', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_url" data-type="url" title="<?php echo $title; ?>"><?php _e('URL', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_text" data-type="text" title="<?php echo $title; ?>"><?php _e('Text', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_textarea" data-type="textarea" title="<?php echo $title; ?>"><?php _e('Textarea', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_select" data-type="select" title="<?php echo $title; ?>"><?php _e('Dropdown', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_multiselect" data-type="multiselect" title="<?php echo $title; ?>"><?php _e('Multi Select', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_date" data-type="date" title="<?php echo $title; ?>"><?php _e('Date', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_radio" data-type="radio" title="<?php echo $title; ?>"><?php _e('Radio', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_checkbox" data-type="checkbox" title="<?php echo $title; ?>"><?php _e('Checkbox', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_repeater" data-type="repeat" title="<?php echo $title; ?>"><?php _e('Repeat Field', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_file" data-type="file" title="<?php echo $title; ?>"><?php _e('File Upload', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_image" data-type="image" title="<?php echo $title; ?>"><?php _e( 'Image Upload', 'frontend_user_pro' ); ?></button>
  <button class="user-button button" data-name="custom_number" data-type="number" title="<?php echo $title; ?>"><?php _e( 'Number', 'frontend_user_pro' ); ?></button>

  <button class="user-button button" data-name="custom_email" data-type="email" title="<?php echo $title; ?>"><?php _e('Email', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_hidden" data-type="hidden" title="<?php echo $title; ?>"><?php _e('Hidden Field', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="recaptcha" data-type="captcha" title="<?php echo $title; ?>"><?php _e('Captcha', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="text" data-type="html" title="<?php echo $title; ?>"><?php _e('Display Text', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="section_break" data-type="break" title="<?php echo $title; ?>"><?php _e('Section Break', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_html" data-type="html" title="<?php echo $title; ?>"><?php _e('HTML', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_css" data-type="custom_css1" title="<?php echo $title; ?>"><?php _e('Custom Css', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_js" data-type="js" title="<?php echo $title; ?>"><?php _e('Custom Js', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="action_hook" data-type="action" title="<?php echo $title; ?>"><?php _e('Do Action', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_country" data-type="country" title="<?php echo $title; ?>"><?php _e('Country', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="featured_image" data-type="featured_image" title="<?php echo $title; ?>"><?php _e('Image Upload', 'frontend_user_pro'); ?></button>
  <button class="user-button button" title="<?php echo $title; ?>" data-type="map" data-name="custom_map"><?php _e('Google Maps', 'frontend_user_pro'); ?></button>

  <?php
  if (FRONTEND_USER()->integrations->is_commissions_active()) { ?>
    <button class="user-button button" data-name="frontendc_user_paypal" data-type="frontendc_user_paypal"><?php _e('PayPal Email', 'frontend_user_pro'); ?></button>
    <button class="user-button button" data-name="toc" data-type="action" title="<?php echo $title; ?>" style="width: 233px;" ><?php _e('Accept Terms', 'frontend_user_pro'); ?></button>
  <?php
  } else { ?>
      <button class="user-button button" data-name="toc" data-type="action" title="<?php echo $title; ?>" style="width: 233px;" ><?php _e('Accept Terms', 'frontend_user_pro'); ?></button>
      <?php
  }
  do_action('user_custom_registration_button'); ?>
  <button class="user-button button" data-name="really_simple_captcha" data-type="rscaptcha" title="<?php echo $title; ?>" style="width: 233px;" ><?php _e( 'Really Simple Captcha', 'frontend_user_pro' ); ?></button>

  </div>
  <?php
}

function user_forms_form_elements_post() {
  $title = esc_attr(__('Click to add to the editor', 'frontend_user_pro'));
  ?>
  <div class="user-form-buttons">
  <button class="user-button button" data-name="post_title" data-type="post_title" title="<?php echo $title; ?>"><?php _e('Title', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="post_content" data-type="post_content" title="<?php echo $title; ?>"><?php _e('Description', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="featured_image" data-type="featured_image" title="<?php echo $title; ?>"><?php _e('Featured Image', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="post_category" data-type="post_category" title="<?php echo $title; ?>"><?php _e('Categories', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="post_tag" data-type="post_tag" title="<?php echo $title; ?>"><?php _e('Tags', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="multiple_pricing" data-type="multiple_pricing" title="<?php echo $title; ?>"><?php _e('Prices and Files', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="post_excerpt" data-type="post_excerpt" title="<?php echo $title; ?>"><?php _e('Excerpt', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_text" data-type="text" title="<?php echo $title; ?>"><?php _e('Text', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_textarea" data-type="textarea" title="<?php echo $title; ?>"><?php _e('Textarea', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_select" data-type="select" title="<?php echo $title; ?>"><?php _e('Dropdown', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_date" data-type="date" title="<?php echo $title; ?>"><?php _e('Date', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_multiselect" data-type="multiselect" title="<?php echo $title; ?>"><?php _e('Multi Select', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_radio" data-type="radio" title="<?php echo $title; ?>"><?php _e('Radio', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_checkbox" data-type="checkbox" title="<?php echo $title; ?>"><?php _e('Checkbox', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_image" data-type="image" title="<?php echo $title; ?>"><?php _e( 'Image Upload', 'frontend_user_pro' ); ?></button>
  <button class="user-button button" data-name="custom_number" data-type="number" title="<?php echo $title; ?>"><?php _e( 'Number', 'frontend_user_pro' ); ?></button>

  <button class="user-button button" data-name="custom_file" data-type="file" title="<?php echo $title; ?>"><?php _e('File Upload', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_url" data-type="url" title="<?php echo $title; ?>"><?php _e('URL', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_email" data-type="email" title="<?php echo $title; ?>"><?php _e('Email', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_repeater" data-type="repeat" title="<?php echo $title; ?>"><?php _e('Repeat Field', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_hidden" data-type="hidden" title="<?php echo $title; ?>"><?php _e('Hidden Field', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="recaptcha" data-type="captcha" title="<?php echo $title; ?>"><?php _e('Captcha', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="section_break" data-type="break" title="<?php echo $title; ?>"><?php _e('Section Break', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_html" data-type="html" title="<?php echo $title; ?>"><?php _e('HTML', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_css" data-type="custom_css1" title="<?php echo $title; ?>"><?php _e('Custom Css', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_js" data-type="js" title="<?php echo $title; ?>"><?php _e('Custom Js', 'frontend_user_pro'); ?></button>
  <button class="user-button button" title="<?php echo $title; ?>" data-type="map" data-name="custom_map"><?php _e('Google Maps', 'frontend_user_pro'); ?></button>

  <button class="user-button button" data-name="action_hook" data-type="action" title="<?php echo $title; ?>"><?php _e('Do Action', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="toc" data-type="action" title="<?php echo $title; ?>" style="width: 233px;" ><?php _e('Accept Terms', 'frontend_user_pro'); ?></button>

  <?php
  do_action('user_custom_post_button', $title); ?>
  </div>
  <?php
}

function user_forms_form_elements_registration() {
  $title = esc_attr(__('Click to add to the editor', 'frontend_user_pro'));
  ?>
  <style type="text/css">
    .custom-field .enable_conditional_input_check {
    display: none;
    }
  </style>
  <div class="user-loading hide"></div>
  <div class="user-form-buttons">
  <button class="user-button button" data-name="first_name" data-type="textarea" title="<?php echo $title; ?>"><?php _e('First Name', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="last_name" data-type="textarea" title="<?php echo $title; ?>"><?php _e('Last Name', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="user_login" data-type="text" title="<?php echo $title; ?>"><?php _e('Username', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="user_email" data-type="email" title="<?php echo $title; ?>"><?php _e('User Email', 'frontend_user_pro'); ?></button>
  
  <button class="user-button button" data-name="user_avatar" data-type="avatar" title="<?php echo $title; ?>"><?php _e('Avatar', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="nickname" data-type="text" title="<?php echo $title; ?>"><?php _e('Nickname', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="display_name" data-type="text" title="<?php echo $title; ?>"><?php _e('Display Name', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="password" data-type="password" title="<?php echo $title; ?>"><?php _e('Password', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="user_bio" data-type="textarea" title="<?php echo $title; ?>"><?php _e('Biography', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="user_url" data-type="text" title="<?php echo $title; ?>"><?php _e('Website', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_image" data-type="image" title="<?php echo $title; ?>"><?php _e( 'Image Upload', 'frontend_user_pro' ); ?></button>
  <button class="user-button button" data-name="custom_number" data-type="number" title="<?php echo $title; ?>"><?php _e( 'Number', 'frontend_user_pro' ); ?></button>

  <button class="user-button button" data-name="custom_file" data-type="file" title="<?php echo $title; ?>"><?php _e('File Upload', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_url" data-type="url" title="<?php echo $title; ?>"><?php _e('URL', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_text" data-type="text" title="<?php echo $title; ?>"><?php _e('Text', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_textarea" data-type="textarea" title="<?php echo $title; ?>"><?php _e('Textarea', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_select" data-type="select" title="<?php echo $title; ?>"><?php _e('Dropdown', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_multiselect" data-type="multiselect" title="<?php echo $title; ?>"><?php _e('Multi Select', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_date" data-type="date" title="<?php echo $title; ?>"><?php _e('Date', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_radio" data-type="radio" title="<?php echo $title; ?>"><?php _e('Radio', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_checkbox" data-type="checkbox" title="<?php echo $title; ?>"><?php _e('Checkbox', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_repeater" data-type="repeat" title="<?php echo $title; ?>"><?php _e('Repeat Field', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_email" data-type="email" title="<?php echo $title; ?>"><?php _e('Email', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_hidden" data-type="hidden" title="<?php echo $title; ?>"><?php _e('Hidden Field', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="recaptcha" data-type="captcha" title="<?php echo $title; ?>"><?php _e('Captcha', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="text" data-type="html" title="<?php echo $title; ?>"><?php _e('Display Text', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="section_break" data-type="break" title="<?php echo $title; ?>"><?php _e('Section Break', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_html" data-type="html" title="<?php echo $title; ?>"><?php _e('HTML', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="action_hook" data-type="action" title="<?php echo $title; ?>"><?php _e('Do Action', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_country" data-type="country" title="<?php echo $title; ?>"><?php _e('Country', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_css" data-type="custom_css1" title="<?php echo $title; ?>"><?php _e('Custom Css', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_js" data-type="js" title="<?php echo $title; ?>"><?php _e('Custom Js', 'frontend_user_pro'); ?></button>
  <button class="user-button button" title="<?php echo $title; ?>" data-type="map" data-name="custom_map"><?php _e('Google Maps', 'frontend_user_pro'); ?></button>
  <button class="user-button button" data-name="custom_mail_user" data-type="custom_mail" title="<?php echo $title; ?>"><?php _e('Register Email', 'frontend_user_pro'); ?></button>

  <?php
  if (FRONTEND_USER()->integrations->is_commissions_active()) 
  { ?>
    <button class="user-button button" data-name="frontendc_user_paypal" data-type="frontendc_user_paypal"><?php _e('PayPal Email', 'frontend_user_pro'); ?></button>
    <button class="user-button button" data-name="toc" data-type="action" title="<?php echo $title; ?>"><?php _e('Accept Terms', 'frontend_user_pro'); ?></button>
    <?php
  }
  ?>
    <button class="user-button button" data-name="toc" data-type="action" title="<?php echo $title; ?>" style="width: 233px;" ><?php _e('Accept Terms', 'frontend_user_pro'); ?></button>

    <?php do_action('user_custom_registration_button'); ?>
    <button class="user-button button" data-name="really_simple_captcha" data-type="rscaptcha" title="<?php echo $title; ?>" style="width: 233px;" ><?php _e( 'Really Simple Captcha', 'frontend_user_pro' ); ?></button>

  </div>
  <?php
}

function user_forms_save_form($post_id, $post) {
  if (!isset($_POST['user-form-editor'])) {
    return $post->ID;
  }
  
  if (!wp_verify_nonce($_POST['user-form-editor'], 'user-form-editor')) {
    return $post->ID;
  }
  // Is the user allowed to edit the post or page?
  if (!current_user_can('edit_post', $post->ID)) {
    return $post->ID;
  }
  
  update_post_meta($post->ID, 'user-form', $_POST['user_input']);
  update_post_meta($post->ID, 'user_form_settings', $_POST['user_settings']);
}

function user_forms_edit_form_area() {
  global $post, $pagenow;

  if ($post->post_type == 'user_logins' ) {
    $form = __('Your Login form has no fields', 'frontend_user_pro');
  } else if ($post->post_type == 'user_registrations' ) {
    $form = __('Your registration form has no fields', 'frontend_user_pro');
  } else {
    $form = __('Your submission form has no fields', 'frontend_user_pro');
  }
  $form = apply_filters('user_edit_form_area_no_fields', $form, get_the_ID());
  $form_inputs = get_post_meta($post->ID, 'user-form', true);

  $feup_f_col = get_post_meta( $post->ID, 'user_form_settings', true );

        $clss = '';
        if ( is_array($feup_f_col) && array_key_exists('feup_f_col', $feup_f_col ) ) {
            $clss = $feup_f_col['feup_f_col'];
        }
  ?>

  <input type="hidden" name="user-form-editor" id="user-form-editor" value="<?php echo wp_create_nonce("user-form-editor"); ?>" />
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
    <button class="button user-collapse"><?php _e('Toggle All Fields Open/Close', 'frontend_user_pro'); ?></button>
  </div>
  
  <?php
  if (empty($form_inputs)) { ?>
    <div class="user-updated">
      <p><?php echo $form; ?></p>
    </div>
    <?php
  } ?>
  
  <ul id="user-form-editor" class="user-form-editor unstyled <?php echo $clss; ?>">

  <?php
  if ($form_inputs) {
    $count = 0;
    foreach ($form_inputs as $order => $input_field) 
    {
      $name = ucwords(str_replace('_', ' ', $input_field['template']));

      $is_custom = apply_filters( 'user_formbuilder_custom_field', false, $input_field );
      if ( $is_custom ){
         $name = $input_field['input_type'];
         do_action('user_admin_field_' . $name, $count, $name, $input_field );
         $count++;
       }else if($form_inputs) 
       {
       // $count = 0;
        // foreach ($form_inputs as $order => $input_field) {
          $name = ucwords( str_replace( '_', ' ', $input_field['template'] ) );
          if ( $input_field['template'] == 'taxonomy') {
            USER_Formbuilder_Templates::$input_field['template']( $count, $name, $input_field['name'], $input_field );
          } else {
            USER_Formbuilder_Templates::$input_field['template']( $count, $name, $input_field );
          }
          $count++;
        // }
      }
    }
  } 
  ?>
  </ul>
  <?php
}

function user_form_notification_area() {
  global $post;
  $user  = wp_get_current_user();


    $new_mail_body = "Hi Admin,\r\n";
    $new_mail_body .= "A new registration has been created in your site BLOG_TITLE (BLOG_URL).\r\n\r\n";

    $mail_body = "Here is the details:\r\n";
    $mail_body .= "User Name: USERNAME\r\n";
    $mail_body .= "Role: ROLE\r\n";


    $user_mail_body1 = "Hi USERNAME,
         Your registration for BLOG_TITLE .

         You can log in, using your username and password that you created when registering for our website, at the following URL: LOGINLINK

         If you have any questions, or problems, then please do not hesitate to contact us.

         Name,
         Company,
         Contact details";

    $form_settings = get_post_meta( $post->ID, 'user_form_settings', true );

    $new_notificaton = isset( $form_settings['notification']['new'] ) ? $form_settings['notification']['new'] : 'on';
    $new_to = isset( $form_settings['notification']['new_to'] ) ? $form_settings['notification']['new_to'] : get_option( 'admin_email' );
    $new_subject = isset( $form_settings['notification']['new_subject'] ) ? $form_settings['notification']['new_subject'] : __( 'New post created', 'frontend_user_pro' );
    $new_body = isset( $form_settings['notification']['new_body'] ) ? $form_settings['notification']['new_body'] : $new_mail_body . $mail_body;
    $user_mail_body = isset( $form_settings['notification']['user_email'] ) ? $form_settings['notification']['user_email'] : $user_mail_body1;
    $user_email_subject = isset( $form_settings['notification']['user_email_subject'] ) ? $form_settings['notification']['user_email_subject'] : 'Your account has been created';

    $edit_notificaton = isset( $form_settings['notification']['edit'] ) ? $form_settings['notification']['edit'] : 'off';
    $edit_to = isset( $form_settings['notification']['edit_to'] ) ? $form_settings['notification']['edit_to'] : get_option( 'admin_email' );
    $edit_subject = isset( $form_settings['notification']['edit_subject'] ) ? $form_settings['notification']['edit_subject'] : __( 'A post has been edited', 'frontend_user_pro' );
    $edit_body = isset( $form_settings['notification']['edit_body'] ) ? $form_settings['notification']['edit_body'] : $new_mail_body . $mail_body;
    // echo "<pre>";
    // print_r($form_settings);
    // echo "</pre>";
    ?>

    <h3><?php _e( 'New Registration Notificatoin', 'frontend_user_pro' ); ?></h3>

    <table class="form-table">
      <tr>
        <th><?php _e( 'Notification', 'frontend_user_pro' ); ?></th>
        <td>
          <label>
            <input type="hidden" name="user_settings[notification][new]" value="off">
            <input type="checkbox" name="user_settings[notification][new]" value="on"<?php checked( $new_notificaton, 'on' ); ?>>
            <?php _e( 'Enable registration notification', 'frontend_user_pro' ); ?>
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
<tr>
<td colspan="2">
<h3><?php _e( 'User Registration Notificatoin', 'frontend_user_pro' ); ?></h3>
</td>
</tr>
       <tr>
        <th><?php _e( 'Subject', 'frontend_user_pro' ); ?></th>
        <td>
          <input type="text" name="user_settings[notification][user_email_subject]" class="regular-text" value="<?php echo esc_attr( $user_email_subject ); ?>">
        </td>
      </tr>

      <tr>
        <th><?php _e( 'Message', 'frontend_user_pro' ); ?></th>
        <td>
	<textarea rows="6" cols="60" name="user_settings[notification][user_email]"><?php echo esc_textarea( $user_mail_body ) ?></textarea>
	<br>
<strong>You may use in message:</strong>
	</td>
      </tr> 
     
    </table>
    <?php
}

function user_form_settings_area() {
  global $post;

        $form_settings = get_post_meta( $post->ID, 'user_form_settings', true );

        $admin_email = get_option('admin_email');
        $email_body1 = 'Hi USERNAME,
        Congratulations! You are almost Register on BLOG_TITLE
        Please verify your account by clicking the button below.
        LOGINLINK';

        $role_selected = isset( $form_settings['role'] ) ? $form_settings['role'] : 'subscriber';
        $redirect_to = isset( $form_settings['redirect_to'] ) ? $form_settings['redirect_to'] : 'post';
        $display_form = isset( $form_settings['display_form'] ) ? $form_settings['display_form'] : 'post';
        $message = isset( $form_settings['message'] ) ? $form_settings['message'] : __( 'Registration successful', 'frontend_user_pro');
        $update_message = isset( $form_settings['update_message'] ) ? $form_settings['update_message'] : __( 'Form updated successfully', 'frontend_user_pro');
        $lostpassword_message = isset( $form_settings['lostpassword_message'] ) ? $form_settings['lostpassword_message'] : __( 'Please Enter User Email Id', 'frontend_user_pro');
        $page_id = isset( $form_settings['page_id'] ) ? $form_settings['page_id'] : 0;
        $url = isset( $form_settings['url'] ) ? $form_settings['url'] : '';
        $submit_text = isset( $form_settings['submit_text'] ) ? $form_settings['submit_text'] : __( 'Submit', 'frontend_user_pro');
        $update_text = isset( $form_settings['update_text'] ) ? $form_settings['update_text'] : __( 'Update', 'frontend_user_pro');
        $email = isset( $form_settings['email_verify'] ) ? $form_settings['email_verify'] : __( 'Email Verification', 'frontend_user_pro');
        $register_page_url = isset( $form_settings['register_page'] ) ? $form_settings['register_page'] : __( 'Registration Url', 'frontend_user_pro');
        $email_verify_content = isset( $form_settings['email_verify_content'] ) ? $form_settings['email_verify_content'] : $email_body1;
        $email_verify_content_sub = isset( $form_settings['email_verify_content_sub'] ) ? $form_settings['email_verify_content_sub'] : 'Confirmation Email';
        $email_verify_content_to = isset( $form_settings['email_verify_content_to'] ) ? $form_settings['email_verify_content_to'] : $admin_email;
        
        ?>
        <!-- <input type="hidden" name="user_settings[feup_f_col]" value="" /> -->
        <table class="form-table">
            <?php if( $post->post_type == 'user_registrations' ) { ?>
            <tr class="user-post-type">
                <th><?php _e( 'New User Role', 'frontend_user_pro'); ?></th>
                <td>
                    <select name="user_settings[role]">
                        <?php
                        $user_roles = user_get_user_roles();
                        foreach ($user_roles as $role => $label) {
                            printf('<option value="%s"%s>%s</option>', $role, selected( $role_selected, $role, false ), $label );
                        }
                        ?>
                    </select>
                    <div class="description">
                        To Add new role<a href="<?php echo admin_url(); ?>users.php?page=role-new">Click here</a>
                    </div>
                </td>
            </tr>
            <?php } ?>

            <tr class="user-redirect-to">
                <th><?php _e( 'Redirect To', 'frontend_user_pro'); ?></th>
                <td>
                    <select name="user_settings[redirect_to]">
                        <?php
                        $redirect_options = array(
                            'same' => __( 'Same Page', 'wpuf' ),
                            'page' => __( 'To a page', 'wpuf' ),
                            'url' => __( 'To a custom URL', 'wpuf' )
                        );

                        foreach ($redirect_options as $to => $label) {
                            printf('<option value="%s"%s>%s</option>', $to, selected( $redirect_to, $to, false ), $label );
                        }
                        ?>
                    </select>
                    <div class="description" style="font-style:italic;">
                        <?php _e( 'After successful submit, where the page will redirect to', 'wpuf' ) ?>
                    </div>
                </td>
            </tr>

 
            <tr class="user-page-id">
                <th><?php _e( 'Page', 'frontend_user_pro'); ?></th>
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
                <th><?php _e( 'Custom URL', 'frontend_user_pro'); ?></th>
                <td>
                    <input type="url" name="user_settings[url]" value="<?php echo esc_attr( $url ); ?>">
                </td>
            </tr>

            <tr class="user-same-page">
                <?php if( $post->post_type == 'user_registrations' ) { ?>
                <th><?php _e( 'Registration success message', 'frontend_user_pro'); ?></th>
                <?php } elseif( $post->post_type == 'user_logins' ) { ?>                
                <th><?php _e( 'Login success message', 'frontend_user_pro'); ?></th>
                <?php } else { ?>
                <th><?php _e( 'success message', 'frontend_user_pro'); ?></th>
                <?php } ?>

                <td>
                    <textarea rows="3" cols="40" name="user_settings[message]"><?php echo esc_textarea( $message ); ?></textarea>
                </td>
            </tr>
            <tr class="user-same-page">
                <th><?php _e( 'Update profile message', 'frontend_user_pro' ); ?></th>
                <td>
                    <textarea rows="3" cols="40" name="user_settings[update_message]"><?php echo esc_textarea( $update_message ); ?></textarea>
                </td>
            </tr>
            <tr class="user-same-page">
                <th><?php _e( 'Lost Password message', 'frontend_user_pro' ); ?></th>
                <td>
                    <textarea rows="3" cols="40" name="user_settings[lostpassword_message]"><?php echo esc_textarea( $lostpassword_message ); ?></textarea>
                </td>
            </tr>
             <tr class="user-submit-text">
                <th><?php _e( 'Submit Button text', 'frontend_user_pro'); ?></th>
                <td>
                    <input type="text" name="user_settings[submit_text]" value="<?php echo esc_attr( $submit_text ); ?>">
                </td>
            </tr>
            <tr class="user-submit-text">
                <th><?php _e( 'Update Button text', 'frontend_user_pro'); ?></th>
                <td>
                    <input type="text" name="user_settings[update_text]" value="<?php echo esc_attr( $update_text ); ?>">
                </td>
            </tr>
            <tr class="user-verification-text">
                <th><?php _e( 'Email Verification', 'frontend_user_pro'); ?></th>
                <td>
                    <select name="user_settings[email_verify]">
                      <option value="no" <?php echo selected( $email, 'no', false ); ?> >NO</option>
                      <option value="yes" <?php echo selected( $email, 'yes', false ) ?> >Yes</option>
                    </select>
                    <div class="description">
                        Send verification mail after Registration
            <?php if($email != 'yes') {
                echo '<div class="email_verify_content" style="display:none;">';
            }
            else {
                echo '<div class="email_verify_content">';
            } ?>
                <input type="text" name="user_settings[email_verify_content_to]" value="<?php echo esc_attr( $email_verify_content_to ); ?>">
                <br><br>
                <input type="text" name="user_settings[email_verify_content_sub]" value="<?php echo esc_attr( $email_verify_content_sub ); ?>">
                <br><br>
              <textarea rows="3" cols="40" name="user_settings[email_verify_content]"><?php echo esc_textarea( $email_verify_content ); ?></textarea> 
            </div>
                    </div>
                </td>
            </tr>           
            <tr class="user-page-url">
                <th><?php _e( 'Registration Url', 'frontend_user_pro'); ?></th>
                <td>
                    <select name="user_settings[register_page]">
                        <?php
                        $pages = get_posts(  array( 'numberposts' => -1, 'post_type' => 'page') );

                        foreach ($pages as $page) {
                            printf('<option value="%s"%s>%s</option>', $page->ID, selected( $register_page_url, $page->ID, false ), esc_attr( $page->post_title ) );
                        }
                        ?>
                    </select>
                    <div class="description" style="font-style:italic;">
                        Select Registration page for this particular login form
                    </div>
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
            jQuery('select[name="user_settings[email_verify]"]').on('change',function(){
                var ch = jQuery(this).val();
                if(ch == 'yes') {
                  jQuery('.email_verify_content').show();
                }
                else {
                  jQuery('.email_verify_content').hide();
                }
            })
        });
        </script>

        <?php
}
function user_get_user_roles() {
    global $wp_roles;

    if ( !isset( $wp_roles ) )
        $wp_roles = new WP_Roles();

    return $wp_roles->get_names();
}

function user_forms_ajax_post_add_field() {
  
  $name = $_POST['name'];
  $type = $_POST['type'];
  $field_id = $_POST['order'];
  
  switch ($name) {
     case 'post_tag':
      USER_Formbuilder_Templates::post_tags($field_id, __('Tags', 'frontend_user_pro'));
     break;

     // case 'category':
     //  USER_Formbuilder_Templates::post_category($field_id, __('Category', 'frontend_user_pro'));
     // break;

     case 'post_title':
      USER_Formbuilder_Templates::post_title($field_id, __('Post Title', 'frontend_user_pro'));
     break;

    case 'post_content':
      USER_Formbuilder_Templates::post_content($field_id, __('Post Content', 'frontend_user_pro'));
      break;

    case 'post_excerpt':
      USER_Formbuilder_Templates::post_excerpt($field_id, __('Excerpt', 'frontend_user_pro'));
      break;

    case 'featured_image':
      USER_Formbuilder_Templates::featured_image($field_id, __('Featured Image', 'frontend_user_pro'));
      break;
      
    case 'multiple_pricing':
      USER_Formbuilder_Templates::multiple_pricing($field_id, __('Prices and Files', 'frontend_user_pro'));
      break;

    case 'user_email':
      USER_Formbuilder_Templates::user_email($field_id, __('User E-Mail', 'frontend_user_pro'));
      break;

    case 'custom_text':
      USER_Formbuilder_Templates::text_field($field_id, __('Custom field: Text', 'frontend_user_pro'));
      break;

    case 'custom_textarea':
      USER_Formbuilder_Templates::textarea_field($field_id, __('Custom field: Textarea', 'frontend_user_pro'));
      break;

    case 'custom_select':
      USER_Formbuilder_Templates::dropdown_field($field_id, __('Custom field: Select', 'frontend_user_pro'));
      break;

    case 'custom_image':
      USER_Formbuilder_Templates::image_upload($field_id, __('Custom field: Image Upload', 'frontend_user_pro'));
      break; 

    case 'custom_number':
      USER_Formbuilder_Templates::number($field_id, __('Custom field: Number', 'frontend_user_pro'));
    break; 
    
    case 'custom_multiselect':
      USER_Formbuilder_Templates::multiple_select($field_id, __('Custom field: Multiselect', 'frontend_user_pro'));
      break;

    case 'custom_radio':
      USER_Formbuilder_Templates::radio_field($field_id, __('Custom field: Radio', 'frontend_user_pro'));
      break;

    case 'custom_checkbox':
      USER_Formbuilder_Templates::checkbox_field($field_id, __('Custom field: Checkbox', 'frontend_user_pro'));
      break;

    case 'custom_file':
      USER_Formbuilder_Templates::file_upload($field_id, __('Custom field: File Upload', 'frontend_user_pro'));
      break;

    case 'custom_url':
      USER_Formbuilder_Templates::website_url($field_id, __('Custom field: URL', 'frontend_user_pro'));
      break;

    case 'custom_email':
      USER_Formbuilder_Templates::email_address($field_id, __('Custom field: E-Mail', 'frontend_user_pro'));
      break;

    case 'custom_repeater':
      USER_Formbuilder_Templates::repeat_field($field_id, __('Custom field: Repeat Field', 'frontend_user_pro'));
      break;

    case 'custom_html':
      USER_Formbuilder_Templates::custom_html($field_id, __('HTML', 'frontend_user_pro'));
      break;

    case 'text':
    USER_Formbuilder_Templates::text($field_id, __('Display Text', 'frontend_user_pro'));
    break;

    case 'section_break':
      USER_Formbuilder_Templates::section_break($field_id, __('Section Break', 'frontend_user_pro'));
      break;

    case 'action_hook':
      USER_Formbuilder_Templates::action_hook($field_id, __('Action Hook', 'frontend_user_pro'));
      break;

    case 'user_avatar':
      USER_Formbuilder_Templates::avatar($field_id, __('Avatar', 'frontend_user_pro'));
      break;

    case 'recaptcha':
      USER_Formbuilder_Templates::recaptcha($field_id, __('reCaptcha', 'frontend_user_pro'));
      break;

    case 'custom_date':
      USER_Formbuilder_Templates::date_field($field_id, __('Custom Field: Date', 'frontend_user_pro'));
      break;

    case 'custom_hidden':
      USER_Formbuilder_Templates::custom_hidden_field($field_id, __('Hidden Field', 'frontend_user_pro'));
      break;

    case 'custom_country':
      USER_Formbuilder_Templates::country($field_id, __('Country', 'frontend_user_pro'));
      break;

    case 'toc':
      USER_Formbuilder_Templates::toc($field_id,  __('TOC', 'frontend_user_pro'));
      break;

    case 'user_login':
      USER_Formbuilder_Templates::user_login($field_id, __('Username', 'frontend_user_pro'));
      break;

    case 'first_name':
      USER_Formbuilder_Templates::first_name($field_id, __('First Name', 'frontend_user_pro'));
      break;

    case 'custom_remember':
      USER_Formbuilder_Templates::remember($field_id, __('Remember me', 'frontend_user_pro'));
      break;      

    case 'last_name':
      USER_Formbuilder_Templates::last_name($field_id, __('Last Name', 'frontend_user_pro'));
      break;

    case 'nickname':
      USER_Formbuilder_Templates::nickname($field_id, __('Nickname', 'frontend_user_pro'));
      break;

    case 'display_name':
      USER_Formbuilder_Templates::display_name($field_id, __('Display Name', 'frontend_user_pro'));
      break;

    case 'user_email':
      USER_Formbuilder_Templates::user_email($field_id, __('E-mail', 'frontend_user_pro'));
      break;

    case 'user_url':
      USER_Formbuilder_Templates::user_url($field_id, __('Website', 'frontend_user_pro'));
      break;

    case 'user_bio':
      USER_Formbuilder_Templates::description($field_id, __('Biographical Info', 'frontend_user_pro'));
      break;

    case 'password':
      USER_Formbuilder_Templates::password($field_id, __('Password', 'frontend_user_pro'));
      break;

    case 'frontendc_user_paypal':
      USER_Formbuilder_Templates::frontendc_user_paypal($field_id, __('PayPal Email', 'frontend_user_pro'));
      break;

    case 'really_simple_captcha':
      USER_Formbuilder_Templates::really_simple_captcha( $field_id, __('Really Simple Captcha','frontend_user_pro'));
      break;

    case 'custom_map':
      USER_Formbuilder_Templates::google_map($field_id, __('Custom Field: Google Map', 'frontend_user_pro'));
      break;

    case 'custom_mail_user':
      USER_Formbuilder_Templates::custom_mail_user($field_id, __('Register Email', 'frontend_user_pro'));
      break;

    case 'custom_css':
      USER_Formbuilder_Templates::custom_css($field_id, __('Custom Css', 'frontend_user_pro'));
      break;

    case 'custom_js':
      USER_Formbuilder_Templates::custom_js($field_id, __('Custom Js', 'frontend_user_pro'));
      break;

    case 'category':
      USER_Formbuilder_Templates::taxonomy( $field_id, 'Category', $type );
      break;

    case 'taxonomy':
      USER_Formbuilder_Templates::taxonomy( $field_id, 'Taxonomy: ' . $type, $type );
      break;

    default:
      do_action('user_admin_field_' . $name, $field_id);
      break;
  }
  
  exit;
}
