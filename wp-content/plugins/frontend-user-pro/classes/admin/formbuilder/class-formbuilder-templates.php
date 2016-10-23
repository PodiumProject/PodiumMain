<?php
//error_reporting(0);
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * USER Form builder template
 */
class USER_Formbuilder_Templates {
    static $input_name = 'user_input';
    /**
     * Legend of a form item
     *
     * @param string $title
     * @param array $values
     */
    public static function legend( $title = 'Field Name', $values = array(), $removeable = true ) {
        $field_label = $values && isset( $values['label'] ) ? ': <strong>' . $values['label'] . '</strong>' : '';
        $c_r = '';
        if(array_key_exists('full_width', $values)) {
            if($values['full_width'] == 'full_width_r') {
                $c_r = 'checked';
            }
        }
        ?>
        <div class="user-legend" title="<?php _e( 'Click and Drag to rearrange', 'frontend_user_pro'); ?>">
            <div class="user-label"><?php echo $title . $field_label; ?></div>
            <div class="user-actions">
                <label>Full width</label><input type="checkbox" value="" name="make_field_fullwidth" <?php echo $c_r; ?>> 
                <?php if ($removeable){ ?>
                <a href="#" class="user-remove"><?php _e( 'Remove', 'frontend_user_pro' ); ?></a>
                <?php } ?>
                <a href="#" class="user-toggle"><?php _e( 'Toggle', 'frontend_user_pro' ); ?></a>
            </div>
        </div> <!-- .user-legend -->
        <?php
    }

    /**
     * Common Fields for a input field
     *
     * Contains required, label, meta_key, help text, css class name
     *
     * @param int $id field order
     * @param mixed $field_name_value
     * @param bool $custom_field if it a custom field or not
     * @param array $values saved value
     */
    public static function login_value( $field_id, $name, $values = array(),$name_funtion) 
    {

        global $post;
        
        $tpl = '%s[%d][%s]';
        if($post) {
            $form_inputs11 = get_post_meta( $post->ID, 'user_form', true );
            if (!$form_inputs11) {
                $form_inputs11 = get_post_meta( $post->ID, 'user-form', true );
            }
        }
        
        if(!isset($input_type)) {
            $input_type = '';
        }
        if(isset($_GET["action"])) {
            $r_action = $_GET["action"];
        }
        else {
            $r_action = '';
        }
        $label_name = sprintf( $tpl, 'user_input', $field_id, 'label' );
        $field_name = sprintf( $tpl, 'user_input', $field_id, 'name' );
        $label_value = $values && isset($values['label']) && ! empty($values['label']) ? esc_attr( $values['label'] ) : esc_attr( ucwords( str_replace( '_', ' ', $input_type ) ) );
        $enable_login_name = sprintf( '%s[%d][enable_login]', 'user_input', $field_id );
        $enable_login_label = sprintf( '%s[%d][enable_login_label]', 'user_input', $field_id );
        $enable_user_label = sprintf( '%s[%d][enable_user_label]', 'user_input', $field_id );
        $login_enable = sprintf( '%s[%d][login_enable]', 'user_input', $field_id );
        $login_title = sprintf( '%s[%d][login_title]', 'user_input', $field_id );
        $login_oprt = sprintf( '%s[%d][login_oprt]', 'user_input', $field_id );
        $arr = sprintf( '%s[%d][array_title]', 'user_input', $field_id );
        $enable_login_value = $values && isset($values['enable_login'])? esc_attr( $values['enable_login'] ) : 'no';
        $enable_login_label_value = $values && isset($values['enable_login_label'])? esc_attr( $values['enable_login_label'] ) : __( 'Confirm Password', 'frontend_user_pro' );
        $enable_user_label_value = $values && isset($values['enable_user_label'])? esc_attr( $values['enable_user_label'] ) : __( 'Confirm Password', 'frontend_user_pro' );
        //$options_value = $values && isset($values['options'])? esc_attr( $values['options'] ) : '';
        echo '<input type="hidden" name="field_id_input" value="'.$name_funtion.'">';
        ?>
        <script type="text/javascript">
            (function($){
             $(document).ready(function(){
                var valu = jQuery('input[name="<?php echo $label_name;?>"]').val();
                var id = jQuery('input[name="<?php echo $label_name;?>"]').attr('id');
                var clas = "optionc_<?php echo $field_id; ?>";
                var clas_ss = "ddc_<?php echo $field_id; ?>";

                var name = "<?php echo $arr; ?>[]";
                var valu_t = "<?php echo $name_funtion; ?>";

                var nn = '<option class="'+clas+'" value="'+valu_t+'" >'+valu+'</option>';

                jQuery(document).on('change','#'+id ,function(){
                    var valu1 = jQuery(this).val();
                    jQuery('.all_fields .'+clas).replaceWith('<option class="'+clas+'" value="'+valu_t+'" >'+valu1+'</option>');
                });

                var nq = "user_input[<?php echo $field_id; ?>][options][]";

                jQuery(document).on('click' , '.user-form-sub-fields div input[name="'+nq+'"]', function(){
                    jQuery(this).attr('id',jQuery(this).val());
                });

                var nq1 = "user_input[<?php echo $field_id; ?>][columns][]";

                jQuery(document).on('click' , '.user-form-sub-fields div input[name="'+nq1+'"]', function(){
                    jQuery(this).attr('id',jQuery(this).val());
                });

                if( '<?php echo $field_id; ?>' == '0' ) {
                    if('<?php echo $r_action; ?>' != 'edit') 
                    {  
                        var htm = jQuery('.user-form-editor li:nth-child(1)').find('.all_fields').html();
                        if(htm !=undefined) {
                            var gg = htm+nn;    
                            jQuery('.user-form-editor li:nth-child(1) .all_fields').append(nn);
                            jQuery('.user-form-editor li:not(:nth-child(1)) .all_fields').append(gg);
                        }
                        else {
                            var nn1 = '<option>-Select-</option>';
                            var nn2 = nn1+nn;
                            jQuery('.user-form-editor li:nth-child(<?php echo $field_id; ?>) .all_fields').append(nn2); 
                        }
                    }  
                    jQuery(document).on('change' , '.user-form-sub-fields div input[name="'+nq+'"]', function(){
                        var vvvv = jQuery(this).val();
                        var rcheck = $(this).attr('id');
                        if(rcheck=='' || rcheck==undefined) {
                            var nn = '<option class="'+clas_ss+'" value="'+vvvv+'" >'+vvvv+'</option>';
                            var htm = jQuery('.user-form-editor li:nth-child(1)').find('.all_fields2').html();
                            if(htm !=undefined) {
                                var gg = htm+nn;    
                                jQuery('.user-form-editor li:not(:nth-child(1)) .all_fields2').append(gg);
                                jQuery('.user-form-editor li:nth-child(1) .all_fields2').append(nn);
                            }
                            else {
                                var nn1 = '<option>-Select-</option>';
                                var nn2 = nn1+nn;
                                jQuery('.user-form-editor li:nth-child(<?php echo $field_id; ?>) .all_fields2').append(nn2);    
                            }
                        }
                        else {

                            var cls = jQuery('.user-form-editor li .all_fields2 option[value="'+rcheck+'"]').attr('class');
                            jQuery('.user-form-editor li .all_fields2 option[value="'+rcheck+'"]').replaceWith('<option class="'+cls+'" value="'+vvvv+'" >'+vvvv+'</option>');
                        }
                        $(this).attr('id','');
                    });
jQuery(document).on('change' , '.user-form-sub-fields div input[name="'+nq1+'"]', function(){
    var vvvv = jQuery(this).val();
    var rcheck = $(this).attr('id');
    if(rcheck=='' || rcheck==undefined) {
        var nn = '<option class="'+clas_ss+'" value="'+vvvv+'" >'+vvvv+'</option>';
        var htm = jQuery('.user-form-editor li:nth-child(1)').find('.all_fields2').html();
        if(htm !=undefined) {
            var gg = htm+nn;    
            jQuery('.user-form-editor li:not(:nth-child(1)) .all_fields2').append(gg);
            jQuery('.user-form-editor li:nth-child(1) .all_fields2').append(nn);
        }
        else {
            var nn1 = '<option>-Select-</option>';
            var nn2 = nn1+nn;
            jQuery('.user-form-editor li:nth-child(<?php echo $field_id; ?>) .all_fields2').append(nn2);    
        }
    }
    else {

        var cls = jQuery('.user-form-editor li .all_fields2 option[value="'+rcheck+'"]').attr('class');
        jQuery('.user-form-editor li .all_fields2 option[value="'+rcheck+'"]').replaceWith('<option class="'+cls+'" value="'+vvvv+'" >'+vvvv+'</option>');
    }
    $(this).attr('id','');
});
}
else {
  if('<?php echo $r_action; ?>' != 'edit') 
  {
    var htm = jQuery('.user-form-editor li:nth-child(<?php echo $field_id; ?>)').find('.all_fields').html();
    if(htm !=undefined) {
        var gg = htm+nn;   
        jQuery('.user-form-editor li:nth-child(<?php echo $field_id + 1; ?>) .all_fields').append(gg);
        jQuery('.user-form-editor li:not(:nth-child(<?php echo $field_id + 1; ?>)) .all_fields').append(nn);
    }
    else {
        var nn1 = '<option>-Select-</option>';
        var nn2 = nn1+nn;
        jQuery('.user-form-editor li:nth-child(<?php echo $field_id; ?>) .all_fields').append(nn2); 
    }
}


jQuery(document).on('change' , '.user-form-sub-fields div input[name="'+nq+'"]', function(){
    var vvvv = jQuery(this).val();
    var rcheck = $(this).attr('id');
    if(rcheck=='' || rcheck==undefined) {
        var nn = '<option class="'+clas_ss+'" value="'+vvvv+'" >'+vvvv+'</option>';
        var htm = jQuery('.user-form-editor li:nth-child(<?php echo $field_id; ?>)').find('.all_fields2').html();
        if(htm !=undefined) {
            var gg = htm+nn;    
                                // jQuery('.user-form-editor li:nth-child(<?php echo $field_id + 1; ?>) .all_fields2').append(gg);
                                jQuery('.user-form-editor li .all_fields2').append(nn);
                            }
                            else {
                                var nn1 = '<option>-Select-</option>';
                                var nn2 = nn1+nn;
                                jQuery('.user-form-editor li:nth-child(<?php echo $field_id; ?>) .all_fields2').append(nn2);    
                            }
                        }
                        else {

                            var cls = jQuery('.user-form-editor li .all_fields2 option[value="'+rcheck+'"]').attr('class')
                            jQuery('.user-form-editor li .all_fields2 option[value="'+rcheck+'"]').replaceWith('<option class="'+cls+'" value="'+vvvv+'" >'+vvvv+'</option>');
                        }
                        $(this).attr('id','');
                    });

jQuery(document).on('change' , '.user-form-sub-fields div input[name="'+nq1+'"]', function(){
    var vvvv = jQuery(this).val();
    var rcheck = $(this).attr('id');
    if(rcheck=='' || rcheck==undefined) {
        var nn = '<option class="'+clas_ss+'" value="'+vvvv+'" >'+vvvv+'</option>';
        var htm = jQuery('.user-form-editor li:nth-child(<?php echo $field_id; ?>)').prev().find('.all_fields2').html();
        if(htm !=undefined) {
            var gg = htm+nn;    
                                // jQuery('.user-form-editor li:nth-child(<?php echo $field_id; ?>) .all_fields2').append(gg);
                                jQuery('.user-form-editor li .all_fields2').append(nn);
                            }
                            else {
                                var nn1 = '<option>-Select-</option>';
                                var nn2 = nn1+nn;
                                jQuery('.user-form-editor li:nth-child(<?php echo $field_id; ?>) .all_fields2').append(nn2);    
                            }
                        }
                        else {

                            var cls = jQuery('.user-form-editor li .all_fields2 option[value="'+rcheck+'"]').attr('class')
                            jQuery('.user-form-editor li .all_fields2 option[value="'+rcheck+'"]').replaceWith('<option class="'+cls+'" value="'+vvvv+'" >'+vvvv+'</option>');
                        }
                        $(this).attr('id','');
                    });

}
var ncn = 'user_input[<?php echo $field_id; ?>][login_title][]';

jQuery(document).on('change' ,'.append_div select[name="'+ncn+'"]' , function(){ 
                        // if(jQuery(this).data('options') == undefined){
                            // console.log('rk in');
                        //     jQuery(this).data('options',jQuery('.append_div .all_fields2.bb_<?php echo $field_id; ?> option').clone());
                        // } 

                        var id = jQuery(this).val();
                        if (id) 
                        {

                            var c = jQuery(this).find('[value="'+id+'"]').attr('class');
                            var q = c.split('_');
                            var k = q['1'];
                            var nw = "ddc_"+k;
                            var options = jQuery('.' + nw ).parent().html();
                            if(options != undefined && options.length != 0) {
                                var select_area_field = "<select name='<?php echo $login_enable; ?>[]' class='all_fields2 bb_<?php echo $field_id; ?>'></select>";
                                jQuery(this).parent('.append_div').find('.add_field').empty();
                                jQuery(this).parent('.append_div').find('.add_field').append("<div class='shweta'></div>");
                                jQuery(this).parent('.append_div').find('.add_field .shweta').replaceWith(select_area_field);
                                jQuery(this).parent('.append_div').find('.add_field').show();
                                jQuery(this).parent('.append_div').find('.all_fields2').html(options);
                                jQuery(this).parent('.append_div').find('.all_fields2').val('-Select-');
                                jQuery(this).parent('.append_div').find('.all_fields2 option:not(.initial_opt)').hide();
                                jQuery(this).parent('.append_div').find('.all_fields2 option.'+nw).show();
                            }
                            else {
                                jQuery(this).parent('.append_div').find('.add_field').find("select[name='<?php echo $login_enable; ?>[]']").removeAttr('name').hide();
                                jQuery(this).parent('.append_div').find('.add_field').find("input[name='<?php echo $login_enable; ?>[]']").remove();
                                 var text_area_field = "<input type='text' name='<?php echo $login_enable; ?>[]' value='' class='smallipopInput text_box_<?php echo $field_id; ?>' title='<?php _e( 'Enter a value', 'frontend_user_pro' ); ?>'>";
                                jQuery(this).parent('.append_div').find('.add_field').show();
                                jQuery(this).parent('.append_div').find('.add_field').append("<div class='shweta'></div>");
                                jQuery(this).parent('.append_div').find('.add_field .shweta').replaceWith(text_area_field);
                            };
                        };
                    });

});
})(jQuery);
</script>          
<div class="user-form-rows enable_conditional_input_check">
    <label><?php _e( 'Enable Conditional Logic', 'frontend_user_pro' ); ?></label>

    <div class="user-form-sub-fields">
        <label>
            <input class="retype-pass conditional_logic" type="checkbox" name="<?php echo $enable_login_name ?>" value="yes"<?php checked( $enable_login_value, 'yes' ); ?> />
        </label>
    </div>
</div> <!-- .user-form-rows -->
<div class="user-form-rows<?php echo $enable_login_value != 'yes' ? ' user-hide' : ''; ?>">

    <select name="<?php echo $enable_login_label; ?>">
        <option value="show" <?php selected( $enable_login_label_value, 'show'); ?>>Show</option>
        <option value="hide" <?php selected( $enable_login_label_value, 'hide'); ?>>Hide</option>
    </select>
    this field if  
    <select name="<?php echo $enable_user_label; ?>">
        <option value="all" <?php selected( $enable_user_label_value, 'all'); ?>>All</option>
        <option value="any" <?php selected( $enable_user_label_value, 'any'); ?>>Any</option>
    </select>
    of the following match:
    <input type="hidden" name="<?php echo $arr; ?>[]" class="array_val" value="<?php echo $name_funtion; ?>">
    <?php
    if (array_key_exists('login_enable', $values) && $values && count($values['login_enable']) > 0 ) 
    {   
        $cout = count($values['login_enable']);
        

        for($i=0; $i < $cout ; $i++)
        {
            $gd = get_post_meta($post->ID , '_ddvalue' ,true); ?>

            <div class="append_div">
                <select name="<?php echo $login_title; ?>[]" class="all_fields opt<?php echo $i; ?> ddv_<?php echo $field_id; ?>">
                    <option class="initial_opt_val">-Select-</option>
                    <?php 
                    foreach ($form_inputs11 as $k1 => $v1) { ?>
                    <option class="optionc_<?php echo $k1; ?>" value="<?php echo $v1['array_title'][0]; ?>" <?php if($values['login_title'][$i] == $v1['array_title'][0]) { echo "selected"; } ?>>
                        <?php echo $v1['label']; ?>
                    </option>
                    <?php
                }
                ?>
            </select>
            <select name="<?php echo $login_oprt; ?>[]">
                <option value="is" <?php selected( $values['login_oprt'][$i], 'is'); ?>>is</option>
                <option value="isnot" <?php selected( $values['login_oprt'][$i], 'isnot'); ?>>is not</option>
                <option value=">" <?php selected( $values['login_oprt'][$i], '>'); ?>>greater than</option>
                <option value="<" <?php selected( $values['login_oprt'][$i], '<'); ?>>less than</option>
                <option value="contains" <?php selected( $values['login_oprt'][$i], 'contains'); ?>>contains</option>
                <option value="starts_with" <?php selected( $values['login_oprt'][$i], 'starts_with'); ?>>starts with</option>
                <option value="ends_with" <?php selected( $values['login_oprt'][$i], 'ends_with'); ?>>ends with</option>
            </select>
            <div class="add_field">
                <?php $tilt =  $values['login_title'][$i];

                $t = explode('-', $tilt);
                // echo "-------------->".$t[0];
                if ( $t[0] == 'dropdown_field' || $t[0] == 'multiple_select' || $t[0] == 'repeat_field' || $t[0] == 'taxonomy' || $t[0] == 'radio_field' || $t[0] == 'checkbox_field') { ?>
                <select name="<?php echo $login_enable; ?>[]" class="all_fields2 optw<?php echo $i; ?> bb_<?php echo $field_id; ?>">
                    <option class="initial_opt">-Select-</option>
                    <?php 
                    foreach ($form_inputs11 as $k1 => $v1) {
                      if($v1['options']) {
                        foreach ($v1['options'] as $k => $v) { ?>
                        <option class="ddc_<?php echo $k1; ?>" value="<?php echo $v; ?>" <?php if($values["login_enable"][$i] == $v) { echo "selected"; } ?>><?php echo $v; ?>
                            
                        </option>
                        <?php }
                    }
                    if($v1['columns']) {
                        foreach ($v1['columns'] as $k => $v) { ?>
                        <option class="ddc_<?php echo $k1; ?>" value="<?php echo $v; ?>" <?php if($values["login_enable"][$i] == $v) { echo "selected"; } ?>><?php echo $v; ?></option>
                        <?php }
                    }
                }  ?>
            </select><?php
        }
        else { ?>
        <select class="all_fields2 optw<?php echo $i; ?> bb_<?php echo $field_id; ?>" style="display:none;">
            <option class="initial_opt">-Select-</option>
            <?php 
            foreach ($form_inputs11 as $k1 => $v1) {
              if($v1['options']) {
                foreach ($v1['options'] as $k => $v) { ?>
                <option class="ddc_<?php echo $k1; ?>" value="<?php echo $v; ?>" <?php if($values["login_enable"][$i] == $v) { echo "selected"; } ?>><?php echo $v; ?></option>
                <?php }
            }
                          // if($t[0] == 'repeat_field') { 
            if($v1['columns']) {
                foreach ($v1['columns'] as $k => $v) { ?>
                <option class="ddc_<?php echo $k1; ?>" value="<?php echo $v; ?>" <?php if($values["login_enable"][$i] == $v) { echo "selected"; } ?>><?php echo $v; ?></option>
                <?php }
            }
                            // }
        }  ?>
    </select>
    <input type="text"name="<?php echo $login_enable; ?>[]" value="<?php echo $values['login_enable'][$i]; ?>" class="smallipopInput text_box_<?php echo $field_id; ?>" title="<?php _e( 'Enter a value', 'frontend_user_pro' ); ?>">
    <?php } ?>
</div>
<?php self::remove_button(); ?>
</div>
<?php
}
} else 
{  ?>
    <div class="append_div">
        <select name="<?php echo $login_title; ?>[]" class="all_fields">
            <?php 
            if($field_id == 0) {
                echo '<option class="initial_opt_val">-Select-</option>';
            } 
            ?>
        </select>
        <select name="<?php echo $login_oprt; ?>[]">
            <option value="is" >is</option>
            <option value="isnot" >is not</option>
            <option value=">">greater than</option>
            <option value="<" >less than</option>
            <option value="contains" >contains</option>
            <option value="starts_with" >starts with</option>
            <option value="ends_with" >ends with</option>
        </select>
        <div class="add_field" style="display:none;">
            <select name="<?php echo $login_enable; ?>[]" class="all_fields2 bb_<?php echo $field_id; ?>">
               <option>-Select-</option>
            </select>
            <?php 
                $t = $_POST['name'];
                $t1 = $_POST['type'];
            if($t == 'taxonomy' || $t == 'category') 
            {
                $args = array(
                    'orderby'           => 'name', 
                    'order'             => 'ASC',
                ); 

                $terms = get_terms( $t1, 'orderby=count&hide_empty=0' );
                foreach ($terms as $k => $v) { ?>

                <script type="text/javascript">
                (function($){
                   $(document).ready(function(){
                        var intp1 = "<input type='hidden' name='user_input[<?php echo $field_id; ?>][options][]' value='<?php echo $v->name; ?>' id='<?php echo $v->name; ?>'>";
                        $('.user-form-editor li:nth-child(<?php echo $field_id+1; ?>) .user-form-holder').append(intp1);
                        var nn = "<option class='ddc_<?php echo $field_id; ?>' value='<?php echo $v->name; ?>' ><?php echo $v->name; ?></option>";
                        jQuery('.user-form-editor li:nth-child(1)').find('.all_fields2').append(nn);
                   }); 
                })(jQuery);    
                </script>
                <?php
                }
            } ?>


           <!-- <input type="text" name="<?php// echo $login_enable; ?>[]" value="<?php// echo $login_enable_value; ?>" class="smallipopInput text_box_<?php echo $field_id; ?>" title="<?php _e( 'Enter a value', 'frontend_user_pro' ); ?>"> -->
       </div>
       <?php self::remove_button(); ?>
   </div>
   <?php
} ?>
</div> 
<?php
}

public static function common( $id, $field_name_value = '', $custom_field = true, $values = array(), $force_required = false, $input_type = 'text' ) {
    global $post;

    $tpl = '%s[%d][%s]';
    $required_name = sprintf( $tpl, 'user_input', $id, 'required' );
    $field_name = sprintf( $tpl, 'user_input', $id, 'name' );
    $label_name = sprintf( $tpl, 'user_input', $id, 'label' );
    $full_width = sprintf( $tpl, 'user_input', $id, 'full_width' );
    $label_position = sprintf( $tpl, 'user_input', $id, 'label_position' );
    $is_meta_name = sprintf( $tpl, 'user_input', $id, 'is_meta' );
    $help_name = sprintf( $tpl, 'user_input', $id, 'help' );
    $css_name = sprintf( $tpl, 'user_input', $id, 'css' );
    $error_message = sprintf( $tpl, 'user_input', $id, 'error_msg' );

    $required = $values && isset($values['required']) ? esc_attr( $values['required'] ) : 'yes';
    $full_width_value = $values && isset($values['full_width']) ? esc_attr( $values['full_width'] ) : '0';
    $label_position_value = $values && isset($values['label_position']) ? esc_attr( $values['label_position'] ) : 'feup_top';
    $input_type = !empty($values['input_type']) ? $values['input_type'] : $input_type;
    $label_value = $values && isset($values['label']) && ! empty($values['label']) ? esc_attr( $values['label'] ) : esc_attr( ucwords( str_replace( '_', ' ', $field_name_value ) ) );
    $help_value = $values && isset($values['help'])? esc_textarea( $values['help'] ) : '';
    $css_value = $values && isset($values['css'])? esc_attr( $values['css'] ) : '';
    $error_msg = $values && isset($values['error_msg'])? esc_attr( $values['error_msg'] ) : '';
        $meta_type = "yes"; // for post meta on custom fields

        if ($custom_field && $values) {
            $field_name_value = $values['name'];
        }

        $exclude = array( 'email_to_use_for_contact_form', 'name_of_store' );
        if ( $custom_field && in_array( $field_name_value, $exclude ) ){
            $custom_field = false;
        }

        do_action('user_add_field_to_common_form_element', $tpl, 'user_input', $id, $values);
        ?>
        <input type="hidden" class="r_full_width"  name="<?php echo $full_width; ?>" value="<?php echo $full_width_value; ?>">
        <div class="user-form-rows required-field">
            <label><?php _e( 'Required', 'frontend_user_pro' ); ?></label>
            <div class="user-form-sub-fields">
                <label><input type="radio" name="<?php echo $required_name; ?>" value="yes"<?php checked( $required, 'yes' ); ?>> <?php _e( 'Yes', 'frontend_user_pro' ); ?> </label>
                <?php if( !$force_required ){ ?>
                <label><input type="radio" name="<?php echo $required_name; ?>" value="no"<?php checked( $required, 'no' ); ?>> <?php _e( 'No', 'frontend_user_pro' ); ?> </label>
                <?php } ?>
            </div>
        </div> <!-- .user-form-rows -->

        <div class="user-form-rows">
            <label><?php _e( 'Field Label', 'frontend_user_pro' ); ?></label>
            <input type="text" data-type="label" id="id_field_<?php echo $id; ?>"  name="<?php echo $label_name; ?>" value="<?php echo $label_value; ?>" class="smallipopInput" title="<?php _e( 'Enter a title of this field', 'frontend_user_pro' ); ?>">
        </div> <!-- .user-form-rows -->
        <div class="user-form-rows">
            <label><?php _e( 'Label Position', 'frontend_user_pro' ); ?></label>
            <select name="<?php echo $label_position; ?>">
                <option value="feup_top" <?php if($label_position_value == 'feup_top') { echo "selected"; }; ?>>top</option>
                <option value="feup_left" <?php if($label_position_value == 'feup_left') { echo "selected"; }; ?>>Left</option>
                <option value="feup_bottom" <?php if($label_position_value == 'feup_bottom') { echo "selected"; }; ?>>Botton</option>
                <option value="feup_right" <?php if($label_position_value == 'feup_right') { echo "selected"; }; ?>>Right</option>
            </select>
        </div> <!-- .user-form-rows -->
        <?php if ( $custom_field ) { ?>
        <div class="user-form-rows">
            <label><?php _e( 'Meta Key', 'frontend_user_pro' ); ?></label>
            <input type="text" name="<?php echo $field_name; ?>" value="<?php echo $field_name_value; ?>" class="smallipopInput" title="<?php _e( 'Name of the meta key this field will save to', 'frontend_user_pro' ); ?>">
            <input type="hidden"  name="<?php echo $is_meta_name; ?>" value="<?php echo $meta_type; ?>">
        </div> <!-- .user-form-rows -->
        <?php } else { ?>

        <input type="hidden"  name="<?php echo $field_name; ?>" value="<?php echo $field_name_value; ?>">
        <input type="hidden" name="<?php echo $is_meta_name; ?>" value="no">

        <?php } ?>

        <div class="user-form-rows">
            <label><?php _e( 'Help text', 'frontend_user_pro' ); ?></label>
            <textarea name="<?php echo $help_name; ?>" class="smallipopInput" title="<?php _e( 'Give the user some information about this field', 'frontend_user_pro' ); ?>"><?php echo $help_value; ?></textarea>
        </div> <!-- .user-form-rows -->
        <?php if ( !isset( $values['no_css'] ) || !$values['no_css'] ){ ?>
        <div class="user-form-rows">
            <label><?php _e( 'CSS Class Name', 'frontend_user_pro' ); ?></label>
            <input type="text" name="<?php echo $css_name; ?>" value="<?php echo $css_value; ?>" class="smallipopInput" title="<?php _e( 'Add a CSS class name for this field', 'frontend_user_pro' ); ?>">
        </div> <!-- .user-form-rows -->
        <?php } ?>
        <div class="user-form-rows">
            <label><?php _e( 'Error message', 'frontend_user_pro' ); ?></label>
            <textarea name="<?php echo $error_message; ?>" class="smallipopInput" title="<?php _e( 'Show Error Message on Signup if wrong value', 'frontend_user_pro' ); ?>"><?php echo $error_msg; ?></textarea>
        </div> <!-- .user-form-rows -->
        <?php
    }

    /**
     * Common fields for a text area
     *
     * @param int $id
     * @param array $values
     */
    public static function common_text( $id, $values = array() ) {
        $tpl = '%s[%d][%s]';
        $placeholder_name = sprintf( $tpl, 'user_input', $id, 'placeholder' );
        $default_name = sprintf( $tpl, 'user_input', $id, 'default' );
        $size_name = sprintf( $tpl, 'user_input', $id, 'size' );

        $placeholder_value = $values && isset($values['placeholder'])? esc_attr( $values['placeholder'] ) : '';
        $default_value = $values && isset($values['default']) ? esc_attr( $values['default'] ) : '';
        $size_value = $values && isset($values['size']) ? esc_attr( $values['size'] ) : '40';
        $show_placeholder =  $values && isset($values['show_placeholder']) ? $values['show_placeholder'] : true;
        $show_default_value =  $values && isset($values['default_value']) ? $values['default_value'] : true;

        if ( $show_placeholder ){ ?>
        <div class="user-form-rows">
            <label><?php _e( 'Placeholder text', 'frontend_user_pro' ); ?></label>
            <input type="text" class="smallipopInput" name="<?php echo $placeholder_name; ?>" title="<?php esc_attr_e( 'Text for HTML5 placeholder attribute', 'frontend_user_pro' ); ?>" value="<?php echo $placeholder_value; ?>" />
        </div> <!-- .user-form-rows -->
        <?php }
        if ( $show_default_value ){ ?>
        <div class="user-form-rows">
            <label><?php _e( 'Default value', 'frontend_user_pro' ); ?></label>
            <input type="text" class="smallipopInput" name="<?php echo $default_name; ?>" title="<?php esc_attr_e( 'The default value this field will have', 'frontend_user_pro' ); ?>" value="<?php echo $default_value; ?>" />
        </div> <!-- .user-form-rows -->
        <?php } ?>
        <div class="user-form-rows">
            <label><?php _e( 'Size', 'frontend_user_pro' ); ?></label>
            <input type="text" class="smallipopInput" name="<?php echo $size_name; ?>" title="<?php esc_attr_e( 'Size of this input field', 'frontend_user_pro' ); ?>" value="<?php echo $size_value; ?>" />
        </div> <!-- .user-form-rows -->
        <?php
    }

    /**
     * Common fields for a textarea
     *
     * @param int $id
     * @param array $values
     */
    public static function common_textarea( $id, $values = array() ) {
        $tpl = '%s[%d][%s]';
        $rows_name = sprintf( $tpl, 'user_input', $id, 'rows' );
        $cols_name = sprintf( $tpl, 'user_input', $id, 'cols' );
        $rich_name = sprintf( $tpl, 'user_input', $id, 'rich' );
        $placeholder_name = sprintf( $tpl, 'user_input', $id, 'placeholder' );
        $default_name = sprintf( $tpl, 'user_input', $id, 'default' );

        $rows_value = $values && isset( $values['rows'] )? esc_attr( $values['rows'] ) : '5';
        $cols_value = $values && isset( $values['cols'] )? esc_attr( $values['cols'] ) : '25';
        $rich_value = $values && isset( $values['rich'] )? esc_attr( $values['rich'] ) : 'no';
        $placeholder_value = $values && isset( $values['placeholder'] )? esc_attr( $values['placeholder'] ) : '';
        $default_value = $values && isset( $values['default'] )? esc_attr( $values['default'] ) : '';

        ?>
        <div class="user-form-rows">
            <label><?php _e( 'Rows', 'frontend_user_pro' ); ?></label>
            <input type="text" class="smallipopInput" name="<?php echo $rows_name; ?>" title="Number of rows in textarea" value="<?php echo $rows_value; ?>" />
        </div> <!-- .user-form-rows -->

        <div class="user-form-rows">
            <label><?php _e( 'Columns', 'frontend_user_pro' ); ?></label>
            <input type="text" class="smallipopInput" name="<?php echo $cols_name; ?>" title="Number of columns in textarea" value="<?php echo $cols_value; ?>" />
        </div> <!-- .user-form-rows -->

        <div class="user-form-rows">
            <label><?php _e( 'Placeholder text', 'frontend_user_pro' ); ?></label>
            <input type="text" class="smallipopInput" name="<?php echo $placeholder_name; ?>" title="text for HTML5 placeholder attribute" value="<?php echo $placeholder_value; ?>" />
        </div> <!-- .user-form-rows -->

        <div class="user-form-rows">
            <label><?php _e( 'Default value', 'frontend_user_pro' ); ?></label>
            <input type="text" class="smallipopInput" name="<?php echo $default_name; ?>" title="the default value this field will have" value="<?php echo $default_value; ?>" />
        </div> <!-- .user-form-rows -->

        <div class="user-form-rows">
            <label><?php _e( 'Textarea', 'frontend_user_pro' ); ?></label>

            <div class="user-form-sub-fields">
                <label><input type="radio" name="<?php echo $rich_name; ?>" value="no"<?php checked( $rich_value, 'no' ); ?>> <?php _e( 'Normal', 'frontend_user_pro' ); ?></label>
                <label><input type="radio" name="<?php echo $rich_name; ?>" value="yes"<?php checked( $rich_value, 'yes' ); ?>> <?php _e( 'Rich textarea', 'frontend_user_pro' ); ?></label>
                <label><input type="radio" name="<?php echo $rich_name; ?>" value="teeny"<?php checked( $rich_value, 'teeny' ); ?>> <?php _e( 'Teeny Rich textarea', 'frontend_user_pro' ); ?></label>
            </div>
        </div> <!-- .user-form-rows -->
        <?php
    }

    /**
     * Hidden field helper function
     *
     * @param string $name
     * @param string $value
     */
    public static function hidden_field( $name, $value = '' ) {
        printf( '<input type="hidden" name="%s" value="%s" />', 'user_input' . $name, $value );
    }

    /**
     * Displays a radio custom field
     *
     * @param int $field_id
     * @param string $name
     * @param array $values
     */
    public static function radio_fields( $field_id, $name, $values = array() ) {
        $selected_name = sprintf( '%s[%d][selected]', 'user_input', $field_id );
        $input_name = sprintf( '%s[%d][%s]', 'user_input', $field_id, $name );

        $selected_value = ( $values && isset( $values['selected'] ) ) ? $values['selected'] : '';

        if ( $values && $values['options'] > 0 ) {
            foreach ($values['options'] as $key => $value) {
                ?>
                <div>
                    <input type="radio" name="<?php echo $selected_name ?>" value="<?php echo $value; ?>" <?php checked( $selected_value, $value ); ?>>
                    <input type="text" name="<?php echo $input_name; ?>[]" value="<?php echo $value; ?>">

                    <?php self::remove_button(); ?>
                </div>
                <?php
            }
        } else {
            ?>
            <div>
                <input type="radio" name="<?php echo $selected_name ?>">
                <input type="text" name="<?php echo $input_name; ?>[]" value="">

                <?php self::remove_button(); ?>
            </div>
            <?php
        }
    }

    /**
     * Displays a checkbox custom field
     *
     * @param int $field_id
     * @param string $name
     * @param array $values
     */
    public static function common_checkbox( $field_id, $name, $values = array() ) {
        $selected_name = sprintf( '%s[%d][selected]', 'user_input', $field_id );
        $input_name = sprintf( '%s[%d][%s]', 'user_input', $field_id, $name );

        $selected_value = ( $values && isset( $values['selected'] ) ) ? $values['selected'] : array();

        if ( $values && $values['options'] > 0 ) {
            foreach ($values['options'] as $key => $value) {
                ?>
                <div>
                    <input type="checkbox" name="<?php echo $selected_name ?>[]" value="<?php echo $value; ?>"<?php echo in_array($value, $selected_value) ? ' checked="checked"' : ''; ?> />
                    <input type="text" name="<?php echo $input_name; ?>[]" value="<?php echo $value; ?>">

                    <?php self::remove_button(); ?>
                </div>
                <?php
            }
        } else {
            ?>
            <div>
                <input type="checkbox" name="<?php echo $selected_name ?>[]">
                <input type="text" name="<?php echo $input_name; ?>[]" value="">

                <?php self::remove_button(); ?>
            </div>
            <?php
        }
    }

    /**
     * Add/remove buttons for repeatable fields
     *
     * @return void
     */
    public static function remove_button() {
        $add = user_assets_url .'img/add.png';
        $remove = user_assets_url. 'img/remove.png';
        ?>
        <img style="cursor:pointer; margin:0 3px;" alt="add another choice" title="add another choice" class="user-clone-field" src="<?php echo $add; ?>">
        <img style="cursor:pointer;" class="user-remove-field" alt="remove this choice" title="remove this choice" src="<?php echo $remove; ?>">
        <?php
    }

    public static function get_buffered($func, $field_id, $label) {
        ob_start();

        self::$func( $field_id, $label );

        return ob_get_clean();
    }

    public static function text_field( $field_id, $label, $values = array(), $removeable = true, $force_required = false ) {
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
        ?>
        <li class="custom-field text_field <?php echo $cls; ?>">
            <?php self::legend( $label, $values, $removeable ); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'text' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'text_field' ); ?>

            <div class="user-form-holder">
                <?php self::common( $field_id, 'text_field', true, $values, $force_required, 'text' ); ?>
                <?php self::common_text( $field_id, $values ); ?>
                <?php self::login_value( $field_id, 'login_enable', $values,'text_field-'.$field_id); ?>
            </div> <!-- .user-form-holder -->
        </li>
        <?php
    }

    public static function textarea_field( $field_id, $label, $values = array(), $removeable = true, $force_required = false  ) {
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
        ?>
        <li class="custom-field textarea_field <?php echo $cls; ?>">
            <?php self::legend( $label, $values, $removeable ); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'textarea' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'textarea_field' ); ?>

            <div class="user-form-holder">
                <?php self::common( $field_id, 'textarea_field', true, $values, $force_required, 'textarea' ); ?>
                <?php self::common_textarea( $field_id, $values ); ?>
                <?php self::login_value( $field_id, 'login_enable', $values,'textarea_field-'.$field_id); ?>
            </div> <!-- .user-form-holder -->
        </li>
        <?php
    }

    public static function radio_field( $field_id, $label, $values = array(), $removeable = true, $force_required = false  ) {
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
        ?>
        <li class="custom-field radio_field <?php echo $cls; ?>">
            <?php self::legend( $label, $values, $removeable ); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'radio' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'radio_field' ); ?>

            <div class="user-form-holder">
                <?php self::common( $field_id, 'radio_field', true, $values, $force_required, 'radio' ); ?>

                <div class="user-form-rows">
                    <label><?php _e( 'Options', 'frontend_user_pro' ); ?></label>

                    <div class="user-form-sub-fields">
                        <?php self::radio_fields( $field_id, 'options', $values ); ?>
                    </div> <!-- .user-form-sub-fields -->
                </div> <!-- .user-form-rows -->
                <?php self::login_value( $field_id, 'login_enable', $values,'radio_field-'.$field_id); ?>
            </div> <!-- .user-form-holder -->
        </li>
        <?php
    }

    public static function checkbox_field( $field_id, $label, $values = array(), $removeable = true, $force_required = false  ) {
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
        ?>
        <li class="custom-field checkbox_field <?php echo $cls; ?>">
            <?php self::legend( $label, $values, $removeable ); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'checkbox' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'checkbox_field' ); ?>

            <div class="user-form-holder">
                <?php self::common( $field_id, 'checkbox_field', true, $values, $force_required, 'checkbox' ); ?>

                <div class="user-form-rows">
                    <label><?php _e( 'Options', 'frontend_user_pro' ); ?></label>

                    <div class="user-form-sub-fields">
                        <?php self::common_checkbox( $field_id, 'options', $values ); ?>
                    </div> <!-- .user-form-sub-fields -->
                </div> <!-- .user-form-rows -->
                <?php self::login_value( $field_id, 'login_enable', $values,'checkbox_field-'.$field_id); ?>
            </div> <!-- .user-form-holder -->
        </li>
        <?php
    }

    public static function dropdown_field( $field_id, $label, $values = array(), $removeable = true, $force_required = false  ) {
        $first_name = sprintf('%s[%d][first]', 'user_input', $field_id);
        $first_value = $values && isset( $values['first'] ) ? $values['first'] : ' - select -';
        $help = esc_attr( __( 'First element of the select dropdown. Leave this empty if you don\'t want to show this field', 'frontend_user_pro' ) );
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
        ?>
        <li class="custom-field dropdown_field <?php echo $cls; ?>">
            <?php self::legend( 'Dropdown Field', $values, $removeable ); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'select' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'dropdown_field' ); ?>

            <div class="user-form-holder">
                <?php self::common( $field_id, 'dropdown_field', true, $values, $force_required, 'select' ); ?>

                <div class="user-form-rows">
                    <label><?php _e( 'Select Text', 'frontend_user_pro' ); ?></label>
                    <input type="text" class="smallipopInput" name="<?php echo $first_name; ?>" value="<?php echo $first_value; ?>" title="<?php echo $help; ?>">
                </div> <!-- .user-form-rows -->

                <div class="user-form-rows">
                    <label><?php _e( 'Options', 'frontend_user_pro' ); ?></label>

                    <div class="user-form-sub-fields">
                        <?php self::radio_fields( $field_id, 'options', $values ); ?>
                    </div> <!-- .user-form-sub-fields -->
                </div> <!-- .user-form-rows -->
                <?php self::login_value( $field_id, 'login_enable', $values,'dropdown_field-'.$field_id); ?>
            </div> <!-- .user-form-holder -->
        </li>
        <?php
    }


    public static function post_content( $field_id, $label, $values = array(), $force_required = false) {
        if(!isset($values['label']) ||  $values['label'] == ''){
            $values['label'] = $label;
        }
        $values['required'] = $values && isset($values['required']) ? $values['required']  : 'yes';
        $values['label'] = $values && isset($values['label']) ? $values['label']  : '';
        $values['help'] = $values && isset($values['help'])? $values['help']  : '';
        $values['css'] = $values && isset($values['css'])?  $values['css']  : '';


        $image_insert_name = sprintf( '%s[%d][insert_image]', 'user_input', $field_id );
        $image_insert_value = isset( $values['insert_image'] ) ? $values['insert_image'] : 'yes';
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
        ?>
        <li class="post_content <?php echo $cls; ?>">
            <?php self::legend( $label, $values); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'textarea' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'post_content' ); ?>

            <div class="user-form-holder">
                <?php self::common( $field_id, 'post_content', false, $values,$force_required, 'post_content' ); ?>

                <?php self::common_textarea( $field_id, $values ); ?>

                <div class="user-form-rows">
                    <label><?php _e( 'Enable Image Insertion', 'frontend_user_pro' ); ?></label>

                    <div class="user-form-sub-fields">
                        <label>
                            <?php self::hidden_field( "[$field_id][insert_image]", 'no' ); ?>
                            <input type="checkbox" name="<?php echo $image_insert_name ?>" value="yes"<?php checked( $image_insert_value, 'yes' ); ?> />
                            <?php _e( 'Enable image upload in post area', 'frontend_user_pro' ); ?>
                        </label>
                    </div>
                </div> <!-- .user-form-rows -->
                <?php self::login_value( $field_id, 'login_enable', $values,'post_content-'.$field_id); ?>
            </div> <!-- .user-form-holder -->
        </li>
        <?php
    }


    public static function post_excerpt( $field_id, $label, $values = array() ) {
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
        ?>
        <li class="custom-field post_excerpt <?php echo $cls; ?>">
            <?php self::legend( $label, $values ); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'textarea' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'post_excerpt' ); ?>

            <div class="user-form-holder">
                <?php self::common( $field_id, 'post_excerpt', false, $values ); ?>
                <?php self::common_text( $field_id, $values ); ?>
                <?php self::common_textarea( $field_id, $values ); ?>
                <?php self::login_value( $field_id, 'login_enable', $values,'post_excerpt-'.$field_id); ?>
            </div> <!-- .user-form-holder -->
        </li>
        <?php
    }
    
    public static function post_tags( $field_id, $label, $values = array() ) {
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
        ?>
        <li class="custom-field post_tags <?php echo $cls; ?>">
            <?php self::legend( $label, $values ); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'text' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'post_tags' ); ?>

            <div class="user-form-holder">
                <?php self::common( $field_id, 'tags', false, $values ); ?>
                <?php self::common_text( $field_id, $values ); ?>
                <?php self::login_value( $field_id, 'login_enable', $values,'post_tags-'.$field_id); ?>
            </div> <!-- .user-form-holder -->
        </li>
        <?php
    }


    public static function post_category( $field_id, $label, $values = array() ) {
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
                ?>
        <li class="custom-field post_category <?php echo $cls; ?>">
            <?php self::legend( $label, $values ); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'taxonomy' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'post_category' ); ?>

            <div class="user-form-holder">
                <?php self::common( $field_id, 'category', false, $values ); ?>
                <?php self::login_value( $field_id, 'login_enable', $values,'post_category-'.$field_id); ?>
            </div> <!-- .user-form-holder -->
        </li>
        <?php
    }
    public static function multiple_select( $field_id, $label, $values = array(), $removeable = true, $force_required = false  ) {
        $first_name = sprintf('%s[%d][first]', 'user_input', $field_id);
        $first_value = $values && isset( $values['first'] ) ? $values['first'] : ' - select -';
        $help = esc_attr( __( 'First element of the select dropdown. Leave this empty if you don\'t want to show this field', 'frontend_user_pro' ) );
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }        
        ?>
        <li class="custom-field multiple_select <?php echo $cls; ?>">
            <?php self::legend( $label, $values, $removeable ); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'multiselect' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'multiple_select' ); ?>

            <div class="user-form-holder">
                <?php self::common( $field_id, 'multiple_select', true, $values, $force_required, 'multiselect' ); ?>

                <div class="user-form-rows">
                    <label><?php _e( 'Select Text', 'frontend_user_pro' ); ?></label>
                    <input type="text" class="smallipopInput" name="<?php echo $first_name; ?>" value="<?php echo $first_value; ?>" title="<?php echo $help; ?>">
                </div> <!-- .user-form-rows -->

                <div class="user-form-rows">
                    <label><?php _e( 'Options', 'frontend_user_pro' ); ?></label>

                    <div class="user-form-sub-fields">
                        <?php self::radio_fields( $field_id, 'options', $values ); ?>
                    </div> <!-- .user-form-sub-fields -->
                </div> <!-- .user-form-rows -->
                <?php self::login_value( $field_id, 'login_enable', $values,'multiple_select-'.$field_id); ?>
            </div> <!-- .user-form-holder -->
        </li>
        <?php
    }

    public static function country( $field_id, $label, $values = array(), $removeable = true, $force_required = false  ) {
        $first_name = sprintf('%s[%d][first]', 'user_input', $field_id);
        $first_value = $values && isset( $values['first'] ) ? $values['first'] : ' - select -';
        $values['options'] = empty( $values['options'] ) ? frontend_get_country_list() : $values['options'];
        $values['label']   = empty( $label ) || empty( $values['label'] ) ? __( 'User Country', 'frontend_user_pro' ) : $values['label'];
        $values['name']    = empty( $values['name'] ) ? 'user_country' : $values['name'];
        $help = esc_attr( __( 'First element of the select dropdown. Leave this empty if you don\'t want to show this field', 'frontend_user_pro' ) );
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }        
        ?>
        <li class="custom-field country <?php echo $cls; ?>">
            <?php self::legend( $label, $values, $removeable ); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'select' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'country' ); ?>

            <div class="user-form-holder">
                <?php self::common( $field_id, 'country', true, $values, $force_required, 'select' ); ?>

                <div class="user-form-rows">
                    <label><?php _e( 'Select Text', 'frontend_user_pro' ); ?></label>
                    <input type="text" class="smallipopInput" name="<?php echo $first_name; ?>" value="<?php echo $first_value; ?>" title="<?php echo $help; ?>">
                </div> <!-- .user-form-rows -->

                <div class="user-form-rows">
                    <label><?php _e( 'Countries', 'frontend_user_pro' ); ?></label>

                    <div class="user-form-sub-fields">
                        <?php self::radio_fields( $field_id, 'options', $values ); ?>
                    </div> <!-- .user-form-sub-fields -->
                </div> <!-- .user-form-rows -->
                <?php self::login_value( $field_id, 'login_enable', $values,'country-'.$field_id); ?>
            </div> <!-- .user-form-holder -->
        </li>
        <?php
    }

    public static function prices_and_files( $id, $values = array() ) {
        $tpl = '%s[%d][%s]';
        $single_name = sprintf( $tpl, 'user_input', $id, 'single' );
        $names_name = sprintf( $tpl, 'user_input', $id, 'names' );
        $prices_name = sprintf( $tpl, 'user_input', $id, 'prices' );
        $files_name = sprintf( $tpl, 'user_input', $id, 'files' );
        $single = $values && isset($values['single']) ? esc_attr( $values['single'] ) : 'no';
        $names = $values && isset($values['names']) ? esc_attr( $values['names'] ) : 'yes';
        $prices = $values && isset($values['prices']) ? esc_attr( $values['prices'] ) : 'yes';
        $files = $values && isset($values['files']) ? esc_attr( $values['files'] ) : 'yes';
        ?>

        <div class="user-form-rows required-field">
            <label><?php _e( 'Single Price/Upload', 'frontend_user_pro' ); ?></label>

            <?php //self::hidden_field($order_name, ''); ?>
            <div class="user-form-sub-fields">
                <label><input type="radio" name="<?php echo $single_name; ?>" value="yes"<?php checked( $single, 'yes' ); ?>> <?php _e( 'Yes', 'frontend_user_pro' ); ?> </label>
                <label><input type="radio" name="<?php echo $single_name; ?>" value="no"<?php checked( $single, 'no' ); ?>> <?php _e( 'No', 'frontend_user_pro' ); ?> </label>
            </div>
        </div> <!-- .user-form-rows -->

        <div class="user-form-rows required-field">
            <label><?php printf( __( 'Allow %s to Set Names of Options', 'frontend_user_pro' ), FRONTEND_USER()->users->get_user_constant_name( $plural = true, $uppercase = true ) ); ?></label>

            <?php //self::hidden_field($order_name, ''); ?>
            <div class="user-form-sub-fields">
                <label><input type="radio" name="<?php echo $names_name; ?>" value="yes"<?php checked( $names, 'yes' ); ?>> <?php _e( 'Yes', 'frontend_user_pro' ); ?> </label>
                <label><input type="radio" name="<?php echo $names_name; ?>" value="no"<?php checked( $names, 'no' ); ?>> <?php _e( 'No', 'frontend_user_pro' ); ?> </label>
            </div>
        </div> <!-- .user-form-rows -->

        <div class="user-form-rows required-field">
            <label><?php printf( __( 'Allow %s to Set Prices of Options', 'frontend_user_pro' ), FRONTEND_USER()->users->get_user_constant_name( $plural = true, $uppercase = true ) ); ?></label>

            <?php //self::hidden_field($order_name, ''); ?>
            <div class="user-form-sub-fields">
                <label><input type="radio" name="<?php echo $prices_name; ?>" value="yes"<?php checked( $prices, 'yes' ); ?>> <?php _e( 'Yes', 'frontend_user_pro' ); ?> </label>
                <label><input type="radio" name="<?php echo $prices_name; ?>" value="no"<?php checked( $prices, 'no' ); ?>> <?php _e( 'No', 'frontend_user_pro' ); ?> </label>
            </div>
        </div> <!-- .user-form-rows -->

        <div class="user-form-rows required-field">
            <label><?php printf( __( 'Allow %s to Upload Files', 'frontend_user_pro' ), FRONTEND_USER()->users->get_user_constant_name( $plural = true, $uppercase = true ) ); ?></label>

            <?php //self::hidden_field($order_name, ''); ?>
            <div class="user-form-sub-fields">
                <label><input type="radio" name="<?php echo $files_name; ?>" value="yes"<?php checked( $files, 'yes' ); ?>> <?php _e( 'Yes', 'frontend_user_pro' ); ?> </label>
                <label><input type="radio" name="<?php echo $files_name; ?>" value="no"<?php checked( $files, 'no' ); ?>> <?php _e( 'No', 'frontend_user_pro' ); ?> </label>
            </div>
        </div> <!-- .user-form-rows -->
        <?php self::login_value( $field_id, 'login_enable', $values,'price_upload-'.$field_id); ?>
        <?php
    }

    public static function file_upload( $field_id, $label, $values = array(), $removeable = true, $force_required = false  ) {
        $max_files_name = sprintf('%s[%d][count]', 'user_input', $field_id);
        $max_files_value = $values && isset( $values['count'] ) ? $values['count'] : '1';
        $count = esc_attr( __( 'Number of files which can be uploaded', 'frontend_user_pro' ) );
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }        
        ?>
        <li class="custom-field custom_image <?php echo $cls; ?>">
            <?php self::legend( $label, $values, $removeable ); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'file_upload' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'file_upload' ); ?>

            <div class="user-form-holder">
                <?php self::common( $field_id, 'file_upload', true, $values, $force_required, 'file_upload' ); ?>

                <div class="user-form-rows">
                    <label><?php _e( 'Max. files', 'frontend_user_pro' ); ?></label>
                    <input type="text" class="smallipopInput" name="<?php echo $max_files_name; ?>" value="<?php echo $max_files_value; ?>" title="<?php echo $count; ?>">
                </div> <!-- .user-form-rows -->
                
            </div> <!-- .user-form-holder -->
        </li>
        <?php
    }

    public static function image_upload( $field_id, $label, $values = array(),$force_required = false ) {
        $max_size_name = sprintf('%s[%d][max_size]', 'user_input', $field_id);
        $max_files_name = sprintf('%s[%d][count]', 'user_input', $field_id);

        $max_size_value = $values ? $values['max_size'] : '1024';
        $max_files_value = $values ? $values['count'] : '1';

        $help = esc_attr( __( 'Enter maximum upload size limit in KB', 'frontend_user_pro' ) );
        $count = esc_attr( __( 'Number of images can be uploaded', 'frontend_user_pro' ) );
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
        ?>
        <li class="custom-field image_upload <?php echo $cls; ?>">
            <?php self::legend( $label, $values ); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'image_upload' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'image_upload' ); ?>

            <div class="user-form-holder">
                <?php self::common( $field_id, 'image_upload', true, $values, $force_required, 'image_upload',$force_required ); ?>

                <div class="user-form-rows">
                    <label><?php _e( 'Max. file size', 'frontend_user_pro' ); ?></label>
                    <input type="text" class="smallipopInput" name="<?php echo $max_size_name; ?>" value="<?php echo $max_size_value; ?>" title="<?php echo $help; ?>">
                </div> <!-- .user-form-rows -->

                <div class="user-form-rows">
                    <label><?php _e( 'Max. files', 'frontend_user_pro' ); ?></label>
                    <input type="text" class="smallipopInput" name="<?php echo $max_files_name; ?>" value="<?php echo $max_files_value; ?>" title="<?php echo $count; ?>">
                </div> <!-- .user-form-rows -->
                 <?php //self::login_value( $field_id, 'login_enable', $values,'image_upload-'.$field_id); ?>
            </div> <!-- .user-form-holder -->
        </li>
        <?php
    }

    public static function website_url( $field_id, $label, $values = array(), $removeable = true, $force_required = false  ) {
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
        ?>
        <li class="custom-field website_url <?php echo $cls; ?>">
            <?php self::legend( $label, $values, $removeable ); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'url' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'website_url' ); ?>

            <div class="user-form-holder">
                <?php self::common( $field_id, 'url', true, $values, $force_required, 'url',$force_required ); ?>
                <?php self::common_text( $field_id,'Url',true, $values ); ?>
                <?php self::login_value( $field_id, 'login_enable', $values,'website_url-'.$field_id); ?>
            </div> <!-- .user-form-holder -->
        </li>
        <?php
    }



    public static function email_address( $field_id, $label, $values = array(), $removeable = true, $force_required = false  ) {
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
        ?>
        <li class="custom-field eamil_address <?php echo $cls; ?>">
            <?php self::legend( $label, $values, $removeable ); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'email' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'email_address' ); ?>

            <div class="user-form-holder">
                <?php self::common( $field_id, 'email_address', true, $values, $force_required, 'email' ); ?>
                <?php self::common_text( $field_id, $values ); ?>
                <?php self::login_value( $field_id, 'login_enable', $values,'email_address-'.$field_id); ?>
            </div> <!-- .user-form-holder -->
        </li>
        <?php
    }

    public static function repeat_field( $field_id, $label, $values = array(), $removeable = true, $force_required = false  ) {
        $tpl = '%s[%d][%s]';

        $enable_column_name = sprintf( '%s[%d][multiple]', 'user_input', $field_id );
        $column_names = sprintf( '%s[%d][columns]', 'user_input', $field_id );
        $has_column = ( $values && isset( $values['multiple'] ) ) ? true : false;

        $placeholder_name = sprintf( $tpl, 'user_input', $field_id, 'placeholder' );
        $default_name = sprintf( $tpl, 'user_input', $field_id, 'default' );
        $size_name = sprintf( $tpl, 'user_input', $field_id, 'size' );

        $placeholder_value = $values && isset( $values['placeholder'] ) ? esc_attr( $values['placeholder'] ) : '';
        $default_value = $values && isset( $values['default'] ) ? esc_attr( $values['default'] ) : '';
        $size_value = $values && isset( $values['size'] ) ? esc_attr( $values['size'] ) : '40';
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
        ?>
        <li class="custom-field custom_repeater <?php echo $cls; ?>">
            <?php self::legend( $label, $values, $removeable ); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'repeat' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'repeat_field' ); ?>

            <div class="user-form-holder">
                <?php self::common( $field_id, 'repeat_field', true, $values, $force_required, 'repeat' ); ?>

                <div class="user-form-rows">
                    <label><?php _e( 'Multiple Column', 'frontend_user_pro' ); ?></label>

                    <div class="user-form-sub-fields">
                        <label><input type="checkbox" class="multicolumn" name="<?php echo $enable_column_name ?>"<?php echo $has_column ? ' checked="checked"' : ''; ?> value="true"> Enable Multi Column</label>
                    </div>
                </div>

                <div class="user-form-rows<?php echo $has_column ? ' user-hide' : ''; ?>">
                    <label><?php _e( 'Placeholder text', 'frontend_user_pro' ); ?></label>
                    <input type="text" class="smallipopInput" name="<?php echo $placeholder_name; ?>" title="text for HTML5 placeholder attribute" value="<?php echo $placeholder_value; ?>" />
                </div> <!-- .user-form-rows -->

                <div class="user-form-rows<?php echo $has_column ? ' user-hide' : ''; ?>">
                    <label><?php _e( 'Default value', 'frontend_user_pro' ); ?></label>
                    <input type="text" class="smallipopInput" name="<?php echo $default_name; ?>" title="the default value this field will have" value="<?php echo $default_value; ?>" />
                </div> <!-- .user-form-rows -->

                <div class="user-form-rows">
                    <label><?php _e( 'Size', 'frontend_user_pro' ); ?></label>
                    <input type="text" class="smallipopInput" name="<?php echo $size_name; ?>" title="Size of this input field" value="<?php echo $size_value; ?>" />
                </div> <!-- .user-form-rows -->

                <div class="user-form-rows column-names<?php echo $has_column ? '' : ' user-hide'; ?>">
                    <label><?php _e( 'Columns', 'frontend_user_pro' ); ?></label>

                    <div class="user-form-sub-fields">
                        <?php

                        if ( $values && $values['columns'] > 0 ) {
                            foreach ($values['columns'] as $key => $value) {
                                ?>
                                <div>
                                    <input type="text" name="<?php echo $column_names; ?>[]" value="<?php echo $value; ?>">

                                    <?php self::remove_button(); ?>
                                </div>
                                <?php
                            }
                        } else {
                            ?>
                            <div>
                                <input type="text" name="<?php echo $column_names; ?>[]" value="">

                                <?php self::remove_button(); ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div> <!-- .user-form-rows -->
                <?php self::login_value( $field_id, 'login_enable', $values,'repeat_field-'.$field_id); ?>
            </div> <!-- .user-form-holder -->
        </li>
        <?php
    }

    public static function custom_html( $field_id, $label, $values = array(), $removeable = true, $force_required = false  ) {
        $title_name = sprintf( '%s[%d][label]', 'user_input', $field_id );
        $html_name = sprintf( '%s[%d][html]', 'user_input', $field_id );

        $title_value = $values && isset( $values['label'] ) ? esc_attr( $values['label'] ) : 'Custom html';
        $html_value = $values && isset( $values['html'] ) ? esc_attr( $values['html'] ) : '';
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
        ?>
        <li class="custom-field custom_html <?php echo $cls; ?>">
            <?php self::legend( $label, $values, $removeable ); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'html' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'custom_html' ); ?>

            <div class="user-form-holder">
                <div class="user-form-rows">
                    <label><?php _e( 'Title', 'frontend_user_pro' ); ?></label>
                    <input type="text" class="smallipopInput" title="Title of the section" name="<?php echo $title_name; ?>" value="<?php echo esc_attr( $title_value ); ?>" />
                </div> <!-- .user-form-rows -->

                <div class="user-form-rows">
                    <label><?php _e( 'HTML Codes', 'frontend_user_pro' ); ?></label>
                    <textarea class="smallipopInput" title="Paste your HTML codes, WordPress shortcodes will also work here" name="<?php echo $html_name; ?>" rows="10"><?php echo esc_html( $html_value ); ?></textarea>
                </div>
                <?php self::login_value( $field_id, 'login_enable', $values,'custom_html-'.$field_id); ?>
            </div> <!-- .user-form-holder -->
        </li>
        <?php
    }

    public static function custom_css( $field_id, $label, $values = array(), $removeable = true, $force_required = false  ) {
        $title_name = sprintf( '%s[%d][label]', 'user_input', $field_id );
        $css_name = sprintf( '%s[%d][custom_css1]', 'user_input', $field_id );

        $title_value = $values && isset( $values['label'] ) ? esc_attr( $values['label'] ) : 'Custom css';
        $css_value = $values && isset( $values['custom_css1'] ) ? esc_attr( $values['custom_css1'] ) : '';
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
        ?>
        <li class="custom-field custom_css <?php echo $cls; ?>">
            <?php self::legend( $label, $values, $removeable ); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'custom_css1' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'custom_css' ); ?>

            <div class="user-form-holder">
                <div class="user-form-rows">
                    <label><?php _e( 'Title', 'frontend_user_pro' ); ?></label>
                    <input type="text" class="smallipopInput" title="Title of the section" name="<?php echo $title_name; ?>" value="<?php echo esc_attr( $title_value ); ?>" />
                </div> <!-- .user-form-rows -->

                <div class="user-form-rows">
                    <label><?php _e( 'Css', 'frontend_user_pro' ); ?></label>
                    <textarea class="smallipopInput" title="Paste your css here without style tag" name="<?php echo $css_name; ?>" rows="10"><?php echo esc_html( $css_value ); ?></textarea>
                </div>
                <?php self::login_value( $field_id, 'login_enable', $values,'custom_css'.$field_id); ?>
            </div> <!-- .user-form-holder -->
        </li>
        <?php
    }

    public static function custom_js( $field_id, $label, $values = array(), $removeable = true, $force_required = false  ) {
        $title_name = sprintf( '%s[%d][label]', 'user_input', $field_id );
        $js_name = sprintf( '%s[%d][js]', 'user_input', $field_id );

        $title_value = $values && isset( $values['label'] ) ? esc_attr( $values['label'] ) : 'Custom js';
        $js_value = $values && isset( $values['js'] ) ? esc_attr( $values['js'] ) : '';
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
        ?>
        <li class="custom-field custom_js <?php echo $cls; ?>">
            <?php self::legend( $label, $values, $removeable ); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'js' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'custom_js' ); ?>

            <div class="user-form-holder">
                <div class="user-form-rows">
                    <label><?php _e( 'Title', 'frontend_user_pro' ); ?></label>
                    <input type="text" class="smallipopInput" title="Title of the section" name="<?php echo $title_name; ?>" value="<?php echo esc_attr( $title_value ); ?>" />
                </div> <!-- .user-form-rows -->

                <div class="user-form-rows">
                    <label><?php _e( 'Js', 'frontend_user_pro' ); ?></label>
                    <textarea class="smallipopInput" title="Paste your js here without script tag" name="<?php echo $js_name; ?>" rows="10"><?php echo esc_html( $js_value ); ?></textarea>
                </div>
                <?php self::login_value( $field_id, 'login_enable', $values,'custom_js-'.$field_id); ?>
            </div> <!-- .user-form-holder -->
        </li>
        <?php
    }

    public static function really_simple_captcha( $field_id, $label, $values = array() , $removeable = true, $force_required = false ) {
        $title_name = sprintf( '%s[%d][label]', 'user_input', $field_id );
        // $html_name = sprintf( '%s[%d][html]', 'user_input', $field_id );

        $title_value = $values ? esc_attr( $values['label'] ) : 'Really simple captcha';
        // $html_value = $values ? esc_attr( $values['html'] ) : '';
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
        ?>
        <li class="custom-field custom_html <?php echo $cls; ?>">
            <?php self::legend( $label, $values ); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'really_simple_captcha' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'really_simple_captcha' ); ?>

            <div class="user-form-holder">
                <div class="user-form-rows">
                    <label><?php _e( 'Title', 'frontend_user_pro' ); ?></label>

                    <div class="user-form-sub-fields">
                        <input type="text" class="smallipopInput" title="Title of the section" name="<?php echo $title_name; ?>" value="<?php echo esc_attr( $title_value ); ?>" />

                        <div class="description" style="margin-top: 8px;">
                            <?php printf( __( "Depends on <a href='http://wordpress.org/extend/plugins/really-simple-captcha/' target='_blank'>Really Simple Captcha</a> Plugin. Install it first." )  ); ?>
                        </div>
                    </div> <!-- .user-form-rows -->
                </div>
                <?php self::login_value( $field_id, 'login_enable', $values,'really_simple_captcha-'.$field_id); ?>
            </div> <!-- .user-form-holder -->
        </li>
        <?php
    }
    public static function post_title( $field_id, $label, $values = array() ,$force_required = false ) {
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
        ?>
        <li class="custom-field post_title <?php echo $cls; ?>">
            <?php self::legend( $label, $values ); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'text' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'post_title' ); ?>

            <div class="user-form-holder">
                <?php self::common( $field_id, 'post_title', false, $values,$force_required, 'post_title' ); ?>
                <?php self::common_text( $field_id, $values ); ?>
                <?php self::login_value( $field_id, 'login_enable', $values,'post_title-'.$field_id); ?>
            </div> <!-- .user-form-holder -->
        </li>
        <?php
    }
    public static function text( $field_id, $label, $values = array(), $removeable = true, $force_required = false  ) {
        $title_name = sprintf( '%s[%d][label]', 'user_input', $field_id );
        $html_name = sprintf( '%s[%d][html]', 'user_input', $field_id );

        $title_value = $values && isset( $values['label'] ) ? esc_attr( $values['label'] ) : '';
        $html_value = $values && isset( $values['html'] ) ? esc_attr( $values['html'] ) : '';
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
        ?>
        <li class="custom-field text <?php echo $cls; ?>">
            <?php self::legend( $label, $values, $removeable ); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'html' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'text' ); ?>


            <div class="user-form-holder">
                <div class="user-form-rows">
                    <label><?php _e( 'Title', 'frontend_user_pro' ); ?></label>
                    <input type="text" class="smallipopInput" title="Title of the section" name="<?php echo $title_name; ?>" value="<?php echo esc_attr( $title_value ); ?>" />
                </div> <!-- .user-form-rows -->

                <div class="user-form-rows">
                    <label><?php _e( 'Display Text', 'frontend_user_pro' ); ?></label>
                    <textarea class="smallipopInput" title="Write display Text , WordPress shortcodes will also work here" name="<?php echo $html_name; ?>" rows="10"><?php echo esc_html( $html_value ); ?></textarea>
                </div>
                <?php self::login_value( $field_id, 'login_enable', $values,'text-'.$field_id); ?>
            </div> <!-- .user-form-holder -->
        </li>
        <?php
    }

    public static function custom_hidden_field( $field_id, $label, $values = array(), $removeable = true, $force_required = false  ) {
        $meta_name = sprintf( '%s[%d][name]', 'user_input', $field_id );
        $value_name = sprintf( '%s[%d][meta_value]', 'user_input', $field_id );
        $is_meta_name = sprintf( '%s[%d][is_meta]', 'user_input', $field_id );
        $label_name = sprintf( '%s[%d][label]', 'user_input', $field_id );

        $meta_value = $values && isset( $values['name'] ) ? esc_attr( $values['name'] ) : 'custom_hidden_field';
        $value_value = $values && isset( $values['meta_value'] ) ? esc_attr( $values['meta_value'] ) : 'custom_hidden_field';
        $title_value = $values && isset( $values['label'] ) ? esc_attr( $values['label'] ) : 'Custom Field';
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
        ?>
        <li class="custom-field custom_hidden_field <?php echo $cls; ?>">
            <?php self::legend( $label, $values, $removeable ); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'hidden' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'custom_hidden_field' ); ?>

            <div class="user-form-holder">
                <div class="user-form-rows">
                    <label><?php _e( 'Meta Key', 'frontend_user_pro' ); ?></label>
                    <input type="text" name="<?php echo $meta_name; ?>" value="<?php echo $meta_value; ?>" class="smallipopInput" title="<?php _e( 'Name of the meta key this field will save to', 'frontend_user_pro' ); ?>">
                    <input type="hidden" name="<?php echo $is_meta_name; ?>" value="yes">
                    <input type="hidden" name="<?php echo $label_name; ?>" value="<?php echo $title_value; ?>">
                </div> <!-- .user-form-rows -->

                <div class="user-form-rows">
                    <label><?php _e( 'Meta Value', 'frontend_user_pro' ); ?></label>
                    <input type="text" class="smallipopInput" title="<?php esc_attr_e( 'Enter the meta value', 'frontend_user_pro' ); ?>" name="<?php echo $value_name; ?>" value="<?php echo $value_value; ?>">
                </div>
                <?php self::login_value( $field_id, 'login_enable', $values,'custom_hidden_field-'.$field_id); ?>
            </div> <!-- .user-form-holder -->
        </li>
        <?php
    }

    public static function section_break( $field_id, $label, $values = array(), $removeable = true, $force_required = false  ) {
        $title_name = sprintf( '%s[%d][label]', 'user_input', $field_id );
        $description_name = sprintf( '%s[%d][description]', 'user_input', $field_id );
        $css_name = sprintf( '%s[%d][css]', 'user_input', $field_id );

        $title_value = $values && isset( $values['label'] ) ? esc_attr( $values['label'] ) : 'Section break';
        $description_value = $values && isset( $values['description'] ) ? esc_attr( $values['description'] ) : '';
        $css_value = $values && isset( $values['css'] ) ? esc_attr( $values['css'] ) : '';
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
        ?>
        <li class="custom-field custom_html <?php echo $cls; ?>">
            <?php self::legend( $label, $values, $removeable ); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'section_break' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'section_break' ); ?>

            <div class="user-form-holder">
                <div class="user-form-rows">
                    <label><?php _e( 'Title', 'frontend_user_pro' ); ?></label>
                    <input type="text" class="smallipopInput" title="Title of the section" name="<?php echo $title_name; ?>" value="<?php echo esc_attr( $title_value ); ?>" />
                </div> <!-- .user-form-rows -->

                <div class="user-form-rows">
                    <label><?php _e( 'Description', 'frontend_user_pro' ); ?></label>
                    <textarea class="smallipopInput" title="Some details text about the section" name="<?php echo $description_name; ?>" rows="3"><?php echo esc_html( $description_value ); ?></textarea>
                </div> <!-- .user-form-rows -->

                <div class="user-form-rows">
                    <label><?php _e( 'CSS Class Name', 'frontend_user_pro' ); ?></label>
                    <input type="text" name="<?php echo $css_name; ?>" value="<?php echo $css_value; ?>" class="smallipopInput" title="<?php _e( 'Add a CSS class name for this field', 'frontend_user_pro' ); ?>">
                </div> <!-- .user-form-rows -->
                <?php self::login_value( $field_id, 'login_enable', $values,'section_break-'.$field_id); ?>
            </div> <!-- .user-form-holder -->
        </li>
        <?php
    }


    public static function recaptcha( $field_id, $label, $values = array(), $removeable = true, $force_required = false  ) {
        $title_name = sprintf( '%s[%d][label]', 'user_input', $field_id );
        $html_name = sprintf( '%s[%d][html]', 'user_input', $field_id );

        $title_value = $values && isset( $values['label'] ) ? esc_attr( $values['label'] ) : 'Recaptcha';
        $html_value = $values && isset( $values['html'] ) ? esc_attr( $values['html'] ) : '';
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
        ?>
        <li class="custom-field custom_html <?php echo $cls; ?>">
            <?php self::legend( $label, $values, $removeable ); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'recaptcha' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'recaptcha' ); ?>
            <div class="user-form-holder">
                <div class="user-form-rows">
                    <label><?php _e( 'Title', 'frontend_user_pro' ); ?></label>
                    <div class="user-form-sub-fields">
                        <input type="text" class="smallipopInput" title="Title of the section" name="<?php echo $title_name; ?>" value="<?php echo esc_attr( $title_value ); ?>" />

                        <div class="description" style="margin-top: 8px;">
                            <?php __( "Insert your public key and private key in plugin settings. <a href='https://www.google.com/recaptcha/' target='_blank'>Register</a> first if you don't have any keys.", 'frontend_user_pro' ); ?>
                        </div>
                    </div> <!-- .user-form-rows -->
                </div>
                <?php self::login_value( $field_id, 'login_enable', $values,'recaptcha-'.$field_id); ?>
            </div> <!-- .user-form-holder -->
        </li>
        <?php
    }


    public static function action_hook( $field_id, $label, $values = array(), $removeable = true, $force_required = false  ) {
        $title_name = sprintf( '%s[%d][label]', 'user_input', $field_id );
        $title_value = $values && isset( $values['label'] ) ? esc_attr( $values['label'] ) : 'action_hook';
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
        ?>
        <li class="custom-field custom_html <?php echo $cls; ?>">
            <?php self::legend( $label, $values, $removeable ); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'action_hook' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'action_hook' ); ?>

            <div class="user-form-holder">
                <div class="user-form-rows">
                    <label><?php _e( 'Hook Name', 'frontend_user_pro' ); ?></label>

                    <div class="user-form-sub-fields">
                        <input type="text" class="smallipopInput" title="<?php _e( 'Name of the hook', 'frontend_user_pro' ); ?>" name="<?php echo $title_name; ?>" value="<?php echo esc_attr( $title_value ); ?>" />

                        <div class="description" style="margin-top: 8px;">
                            <?php _e( "This is for developers to add dynamic elements as they want. It provides the chance to add whatever input type you want to add in this form.", 'frontend_user_pro' ); ?>
                            <?php _e( 'You can bind your own functions to render the form to this action hook. You\'ll be given 3 parameters to play with: $form_id, $post_id, $form_settings.', 'frontend_user_pro' ); ?>
                               <p> add_action('HOOK_NAME', 'your_function_name', 10, 3 );
                                function your_function_name( $form_id, $post_id, $form_settings ) {
                                // do what ever you want
                            }
                        </p>
                    </div>
                </div> <!-- .user-form-rows -->
            </div>
        </div> <!-- .user-form-holder -->
    </li>
    <?php
}

public static function date_field( $field_id, $label, $values = array(), $removeable = true, $force_required = false  ) {
    $format_name = sprintf('%s[%d][format]', 'user_input', $field_id);
    $time_name = sprintf('%s[%d][time]', 'user_input', $field_id);

    $format_value = $values && isset( $values['format'] ) ? $values['format'] : 'dd/mm/yy';
    $time_value = $values && isset( $values['time'] ) ? $values['time'] : 'no';

    $help = esc_attr( __( 'The date format', 'frontend_user_pro' ) );
    $cls = '';
    if(array_key_exists('full_width', $values)) {
        $cls = $values['full_width'];
    }
    ?>
    <li class="custom-field custom_image <?php echo $cls; ?>">
        <?php self::legend( $label, $values, $removeable ); ?>
        <?php self::hidden_field( "[$field_id][input_type]", 'date' ); ?>
        <?php self::hidden_field( "[$field_id][template]", 'date_field' ); ?>

        <div class="user-form-holder">
            <?php self::common( $field_id, 'date_field', true, $values, $force_required, 'date' ); ?>

            <div class="user-form-rows">
                <label><?php _e( 'Date Format', 'frontend_user_pro' ); ?></label>
                <input type="text" class="smallipopInput" name="<?php echo $format_name; ?>" value="<?php echo $format_value; ?>" title="<?php echo $help; ?>">
            </div> <!-- .user-form-rows -->

            <div class="user-form-rows">
                <label><?php _e( 'Time', 'frontend_user_pro' ); ?></label>

                <div class="user-form-sub-fields">
                    <label>
                        <?php self::hidden_field( "[$field_id][time]", 'no' ); ?>
                        <input type="checkbox" name="<?php echo $time_name ?>" value="yes"<?php checked( $time_value, 'yes' ); ?> />
                        <?php _e( 'Enable time input', 'frontend_user_pro' ); ?>
                    </label>
                </div>
            </div> <!-- .user-form-rows -->
            <?php self::login_value( $field_id, 'login_enable', $values,'date_field-'.$field_id); ?>
        </div> <!-- .user-form-holder -->
    </li>
    <?php
}
public static function remember($field_id, $label, $values = array(), $removeable = true, $force_required = false  ) {
    $title_name = sprintf('%s[%d][label]', 'user_input', $field_id);
    $remember_name = sprintf('%s[%d][remember]', 'user_input', $field_id);

    $title_value = $values && isset( $values['label'] ) ? $values['label'] : '';
    $remember_value = $values && isset( $values['remember'] ) ? $values['remember'] : 'no';
    $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
    ?>
    <li class="custom-field custom_image <?php echo $cls; ?>">
        <?php self::legend( $label, $values, $removeable ); ?>
        <?php self::hidden_field( "[$field_id][input_type]", 'remember' ); ?>
        <?php self::hidden_field( "[$field_id][template]", 'remember' ); ?>

        <div class="user-form-holder">
            <?php self::common( $field_id, '', true, $values, $force_required, 'remember' ); ?>

            <div class="user-form-rows">
                <label><?php _e( 'Remember me', 'frontend_user_pro' ); ?></label>

                <div class="user-form-sub-fields">
                    <label>
                        <?php self::hidden_field( "[$field_id][remember]", 'no' ); ?>
                        <input type="checkbox" name="<?php echo $remember_name ?>" value="yes"<?php checked( $remember_value, 'yes' ); ?> />
                        <?php _e( 'Enable Remember me input', 'frontend_user_pro' ); ?>
                    </label>
                </div>
            </div> <!-- .user-form-rows -->
            <?php self::login_value( $field_id, 'login_enable', $values,'remember-'.$field_id); ?>
        </div> <!-- .user-form-holder -->
    </li>
    <?php
}

public static function toc( $field_id, $label, $values = array(), $removeable = true, $force_required = false  ) {

    $title_name = sprintf( '%s[%d][label]', 'user_input', $field_id );
    $error = sprintf( '%s[%d][error]', 'user_input', $field_id );
    $css_name = sprintf( '%s[%d][css]', 'user_input', $field_id );
    $select_name = sprintf( '%s[%d][select]', 'user_input', $field_id );
    $message_name = sprintf( '%s[%d][message]', 'user_input', $field_id );

    // echo "*****".$values['select'] ;
    $title_value = $values && isset( $values['label'] ) ? esc_attr( $values['label'] ) : 'Term & Conditions';
    $error_value = $values && isset( $values['error'] ) ? esc_attr( $values['error'] ) : '';
    $css_value = $values && isset( $values['css'] ) ? esc_attr( $values['css'] ) : '';
    $select_value = $values && isset( $values['select'] ) ? esc_attr( $values['select'] ) : '';
    $message_value = $values && isset( $values['message'] ) ? esc_attr( $values['message'] ) : '';
    $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
    ?>
    <li class="custom-field custom_html <?php echo $cls; ?>">
        <?php self::legend( $label, $values, $removeable ); ?>
        <?php self::hidden_field( "[$field_id][input_type]", 'toc' ); ?>
        <?php self::hidden_field( "[$field_id][name]", 'user_accept_toc' ); ?>
        <?php self::hidden_field( "[$field_id][template]", 'toc' ); ?>

        <div class="user-form-holder">
            <div class="user-form-rows">
                <label><?php _e( 'Label', 'frontend_user_pro' ); ?></label>
                <input type="text" name="<?php echo $title_name; ?>" value="<?php echo esc_attr( $title_value ); ?>" />
            </div> <!-- .user-form-rows -->

            <!-- .user-form-rows -->
            <?php $pages = get_pages('status=publish&numberposts=-1&posts_per_page=-1'); ?>
            <div class="user-form-rows">
                <label><?php _e( 'Select your terms page ', 'frontend_user_pro' ); ?></label>
                <select name="<?php echo $select_name; ?>">
                    <?php 
                    foreach ($pages as $p) { ?>
                    <option value="<?php echo $p->ID; ?>" <?php selected( $select_value, $p->ID ); ?>><?php echo esc_attr($p->post_title); ?></option>
                    <?php } ?>
                </select>
            </div> <!-- .user-form-rows -->
            <div class="user-form-rows">
                <label><?php _e( 'Message', 'frontend_user_pro' ); ?></label>
                <input type="text" name="<?php echo $message_name; ?>" value="<?php echo $message_value; ?>" class="smallipopInput" title="<?php _e( 'This is the text that goes right after the checkbox', 'frontend_user_pro' ); ?>">
            </div> <!-- .user-form-rows -->

            <div class="user-form-rows">
                <label><?php _e( 'CSS Class Name', 'frontend_user_pro' ); ?></label>
                <input type="text" name="<?php echo $css_name; ?>" value="<?php echo $css_value; ?>" class="smallipopInput" title="<?php _e( 'Add a CSS class name for this field', 'frontend_user_pro' ); ?>">
            </div> <!-- .user-form-rows -->
            <?php self::login_value( $field_id, 'login_enable', $values,'toc-'.$field_id); ?>
        </div> <!-- .user-form-holder -->
    </li>
    <?php
}


public static function multiple_pricing( $field_id, $label, $values = array() ) {
    if(!isset($values['label']) || $values['label'] == ''){
        $values['label'] = $label;
    }
    $enable_column_name = sprintf( '%s[%d][multiple]', 'user_input', $field_id );
    $column_names = sprintf( '%s[%d][columns]', 'user_input', $field_id );
    $has_column = ( $values && isset( $values['multiple'] ) ) ? true : false;
    $values['has_column'] = $has_column;
    $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
        ?>
    <li class="multiple_pricing echo $cls; ?>">
        <?php self::legend( $label, $values); ?>
        <?php self::hidden_field( "[$field_id][input_type]", 'multiple_pricing' ); ?>
        <?php self::hidden_field( "[$field_id][template]", 'multiple_pricing' ); ?>
        <div class="user-form-holder">
            <?php self::common( $field_id, 'multiple', false, $values, false, 'multiple_pricing' ); ?>
            <?php self::prices_and_files( $field_id, $values ); ?>

            <div class="user-form-rows">
                <label><?php _e( 'Set Options', 'frontend_user_pro' ); ?></label>

                <div class="user-form-sub-fields">
                    <label><input type="checkbox" class="multicolumn" name="<?php echo $enable_column_name ?>"<?php echo $has_column ? ' checked="checked"' : ''; ?> value="true"> Set Default Names/Prices</label>
                </div>
            </div>
            <div class="user-form-rows column-names<?php echo $has_column ? '' : ' user-hide'; ?>">
                <label><?php _e( 'Predefined Names/Prices', 'frontend_user_pro' ); ?></label>

                <div class="user-form-sub-fields">
                    <?php
                    if ( $values && isset( $values['columns'] ) && $values['columns'] > 0 ) {
                        $keys = count( $values['columns'] );
                        $new_values = array();
                        $key = 0;
                        foreach ( $values['columns'] as $old_key => $value ){
                            if ( $old_key === 0 || $old_key % 2 == 0 ){
                                $new_values[$key]['name'] = $value['name'];
                            }
                            else{
                               $new_values[$key]['price'] = $value['price'];
                               $key++;
                           }
                           unset( $values[$old_key] );
                       }
                       $values['columns'] = $new_values;
                       foreach ( $values['columns'] as $key => $value ) {
                        ?>
                        <div>
                            <?php _e('Name: ', 'frontend_user_pro'); ?><input type="text" name="<?php echo $column_names; ?>[][name]" value="<?php echo $value['name']; ?>">
                            <?php _e('Price: ', 'frontend_user_pro'); ?><input type="text" name="<?php echo $column_names; ?>[][price]" value="<?php echo $value['price']; ?>">
                            <?php self::remove_button(); ?>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div>
                        <?php _e('Name: ', 'frontend_user_pro'); ?><input type="text" name="<?php echo $column_names; ?>[][name]" value="">
                        <?php _e('Price: ', 'frontend_user_pro'); ?><input type="text" name="<?php echo $column_names; ?>[][price]" value="">
                        <?php self::remove_button(); ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div> <!-- .user-form-rows -->
        <?php self::login_value( $field_id, 'login_enable', $values,'multiple_pricing-'.$field_id); ?>
    </div> <!-- .user-form-holder -->
</li>
<?php
}

public static function featured_image( $field_id, $label, $values = array() ) {
    if(!isset($values['label']) || $values['label'] == ''){
        $values['label'] = $label;
    }
    $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
    ?>
    <li class="featured_image <?php echo $cls; ?>">
        <?php self::legend( $label, $values); ?>
        <?php self::hidden_field( "[$field_id][input_type]", 'image_upload' ); ?>
        <?php self::hidden_field( "[$field_id][template]", 'featured_image' ); ?>
        <?php self::hidden_field( "[$field_id][count]", '1' ); ?>
        <div class="user-form-holder">
            <?php self::common( $field_id, 'featured_image', false, $values, false, 'image_upload' ); ?>
            <?php self::login_value( $field_id, 'login_enable', $values,'featured_image-'.$field_id); ?>
        </div> <!-- .user-form-holder -->
    </li>
    <?php
}

public static function taxonomy( $field_id, $label, $taxonomy = '', $values = array() ) {
    $type_name = sprintf( '%s[%d][type]', 'user_input', $field_id );
    $order_name = sprintf( '%s[%d][order]', 'user_input', $field_id );
    $orderby_name = sprintf( '%s[%d][orderby]', 'user_input', $field_id );
    $exclude_type_name = sprintf( '%s[%d][exclude_type]', 'user_input', $field_id );
    $exclude_name = sprintf( '%s[%d][exclude]', 'user_input', $field_id );

    $type_value = $values  && isset($values['type'])? esc_attr( $values['type'] ) : 'select';
    $order_value = $values && isset($values['order'])? esc_attr( $values['order'] ) : 'ASC';
    $orderby_value = $values && isset($values['orderby'] )? esc_attr( $values['orderby'] ) : 'name';
    $exclude_type_value = $values && isset( $values['exclude_type'] )? esc_attr( $values['exclude_type'] ) : 'exclude';
    $exclude_value = $values && isset($values['exclude'] )? esc_attr( $values['exclude'] ) : '';
    if(!isset($values['label']) || $values['label'] == ''){
        $values['label'] = $label;
    }
    $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
    ?>
    <li class="taxonomy <?php echo $taxonomy.' '.$cls; ?>">
        <?php self::legend( $label, $values ); ?>
        <?php self::hidden_field( "[$field_id][input_type]", 'taxonomy' ); ?>
        <?php self::hidden_field( "[$field_id][template]", 'taxonomy' ); ?>

        <div class="user-form-holder">
            <?php self::common( $field_id, $taxonomy, false, $values, false, 'taxonomy' ); ?>

            <div class="user-form-rows">
                <label><?php _e( 'Type', 'frontend_user_pro' ); ?></label>
                <select name="<?php echo $type_name ?>">
                    <option value="select"<?php selected( $type_value, 'select' ); ?>><?php _e( 'Dropdown', 'frontend_user_pro' ); ?></option>
                    <option value="multiselect"<?php selected( $type_value, 'multiselect' ); ?>><?php _e( 'Multi Select', 'frontend_user_pro' ); ?></option>
                    <option value="checkbox"<?php selected( $type_value, 'checkbox' ); ?>><?php _e( 'Checkbox', 'frontend_user_pro' ); ?></option>
                    <option value="text"<?php selected( $type_value, 'text' ); ?>><?php _e( 'Text Input', 'frontend_user_pro' ); ?></option>
                </select>
            </div> <!-- .user-form-rows -->

            <div class="user-form-rows">
                <label><?php _e( 'Order By', 'frontend_user_pro' ); ?></label>
                <select name="<?php echo $orderby_name ?>">
                    <option value="name"<?php selected( $orderby_value, 'name' ); ?>><?php _e( 'Name', 'frontend_user_pro' ); ?></option>
                    <option value="id"<?php selected( $orderby_value, 'id' ); ?>><?php _e( 'Term ID', 'frontend_user_pro' ); ?></option>
                    <option value="slug"<?php selected( $orderby_value, 'slug' ); ?>><?php _e( 'Slug', 'frontend_user_pro' ); ?></option>
                    <option value="count"<?php selected( $orderby_value, 'count' ); ?>><?php _e( 'Count', 'frontend_user_pro' ); ?></option>
                    <option value="term_group"<?php selected( $orderby_value, 'term_group' ); ?>><?php _e( 'Term Group', 'frontend_user_pro' ); ?></option>
                </select>
            </div> <!-- .user-form-rows -->

            <div class="user-form-rows">
                <label><?php _e( 'Order', 'frontend_user_pro' ); ?></label>
                <select name="<?php echo $order_name ?>">
                    <option value="ASC"<?php selected( $order_value, 'ASC' ); ?>><?php _e( 'ASC', 'frontend_user_pro' ); ?></option>
                    <option value="DESC"<?php selected( $order_value, 'DESC' ); ?>><?php _e( 'DESC', 'frontend_user_pro' ); ?></option>
                </select>
            </div> <!-- .user-form-rows -->

            <div class="user-form-rows">
                <label><?php _e( 'Selection Type', 'frontend_user_pro' ); ?></label>
                <select name="<?php echo $exclude_type_name ?>">
                    <option value="exclude"<?php selected( $exclude_type_value, 'exclude' ); ?>><?php _e( 'Exclude', 'frontend_user_pro' ); ?></option>
                    <option value="include"<?php selected( $exclude_type_value, 'include' ); ?>><?php _e( 'Include', 'frontend_user_pro' ); ?></option>
                    <option value="child_of"<?php selected( $exclude_type_value, 'child_of' ); ?>><?php _e( 'Child of', 'frontend_user_pro' ); ?></option>
                </select>
            </div> <!-- .user-form-rows -->

            <div class="user-form-rows">
                <label><?php _e( 'Selection terms', 'frontend_user_pro' ); ?></label>
                <input type="text" class="smallipopInput" name="<?php echo $exclude_name; ?>" title="<?php _e( 'Enter the term IDs as comma separated (without space) to exclude/include in the form.', 'frontend_user_pro' ); ?>" value="<?php echo $exclude_value; ?>" />
            </div> <!-- .user-form-rows -->
            <?php self::login_value( $field_id, 'login_enable', $values,'taxonomy-'.$field_id); ?>
        </div> <!-- .user-form-holder -->
    </li>
    <?php
}

public static function user_login( $field_id, $label, $values = array() ) {
    global $post;
    if(!isset($values['label']) || $values['label'] == ''){
        $values['label'] = $label;
    }
    $force_required = false;
    $removable = true;

    $values['show_placeholder'] = true;
    $values['default_value'] = false;
    $minus_label = $values;
    unset($minus_label['label']);
    $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
    ?>
    <li class="user_login <?php echo $cls; ?>">
        <?php self::legend( $label, $minus_label, $removable ); ?>
        <?php self::hidden_field( "[$field_id][input_type]", 'text' ); ?>
        <?php self::hidden_field( "[$field_id][template]", 'user_login' ); ?>

        <div class="user-form-holder">
            <?php self::common( $field_id, 'user_login', false, $values, $force_required, 'text' ); ?>
            <?php self::common_text( $field_id, $values ); ?>
        </div> <!-- .user-form-holder -->
    </li>
    <?php
}

public static function first_name( $field_id, $label, $values = array() ) {
    global $post;
    if(!isset($values['label']) || $values['label'] == ''){
        $values['label'] = $label;
    }
    $force_required = false;
    $removable = true;

    $values['show_placeholder'] = true;
    $values['default_value'] = false;
    $minus_label = $values;
    unset($minus_label['label']);
    $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
    ?>
    <li class="first_name <?php echo $cls; ?>">
        <?php self::legend( $label, $minus_label, $removable ); ?>
        <?php self::hidden_field( "[$field_id][input_type]", 'text' ); ?>
        <?php self::hidden_field( "[$field_id][template]", 'first_name' ); ?>

        <div class="user-form-holder">
            <?php self::common( $field_id, 'first_name', false, $values, $force_required, 'text' ); ?>
            <?php self::common_text( $field_id, $values ); ?>
        </div> <!-- .user-form-holder -->
    </li>
    <?php
}

public static function last_name( $field_id, $label, $values = array() ) {
    global $post;
    if(!isset($values['label']) || $values['label'] == ''){
        $values['label'] = $label;
    }
    $force_required = false;
    $removable = true;

    $values['show_placeholder'] = true;
    $values['default_value'] = false;
    $minus_label = $values;
    unset($minus_label['label']);
    $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
    ?>
    <li class="last_name <?php echo $cls; ?>">
        <?php self::legend( $label, $minus_label, $removable ); ?>
        <?php self::hidden_field( "[$field_id][input_type]", 'text' ); ?>
        <?php self::hidden_field( "[$field_id][template]", 'last_name' ); ?>

        <div class="user-form-holder">
            <?php self::common( $field_id, 'last_name', false, $values, $force_required, 'text' ); ?>
            <?php self::common_text( $field_id, $values ); ?>
        </div> <!-- .user-form-holder -->
    </li>
    <?php
}

public static function nickname( $field_id, $label, $values = array() ) {
    if(!isset($values['label']) || $values['label'] == ''){
        $values['label'] = $label;
    }
    $values['show_placeholder'] = true;
    $values['default_value'] = false;
    $minus_label = $values;
    unset($minus_label['label']);
    $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
    ?>
    <li class="nickname <?php echo $cls; ?>">
        <?php self::legend( $label, $minus_label ); ?>
        <?php self::hidden_field( "[$field_id][input_type]", 'text' ); ?>
        <?php self::hidden_field( "[$field_id][template]", 'nickname' ); ?>

        <div class="user-form-holder">
            <?php self::common( $field_id, 'nickname', false, $values, false, 'text' ); ?>
            <?php self::common_text( $field_id, $values ); ?>
        </div> <!-- .user-form-holder -->
    </li>
    <?php
}

public static function display_name( $field_id, $label, $values = array() ) {
    global $post;
    if(!isset($values['label']) || $values['label'] == ''){
        $values['label'] = $label;
    }
    $force_required = false;
    $removable = true;
    $values['show_placeholder'] = true;
    $values['default_value'] = false;
    $minus_label = $values;
    unset($minus_label['label']);
    $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
    ?>
    <li class="display_name <?php echo $cls; ?>">
        <?php self::legend( $label, $minus_label, $removable ); ?>
        <?php self::hidden_field( "[$field_id][input_type]", 'text' ); ?>
        <?php self::hidden_field( "[$field_id][template]", 'display_name' ); ?>

        <div class="user-form-holder">
            <?php self::common( $field_id, 'display_name', false, $values, $force_required, 'text' ); ?>
            <?php self::common_text( $field_id, $values ); ?>
        </div> <!-- .user-form-holder -->
    </li>
    <?php
}

public static function user_email( $field_id, $label, $values = array() ) {
    global $post;
    if(!isset($values['label']) || $values['label'] == ''){
        $values['label'] = $label;
    }
    $force_required = false;
    $removable = true;
    if ( is_object( $post ) && get_the_ID() == FRONTEND_USER()->helper->get_option( 'user-registration-form', false ) ) {
        $force_required = true;
        $removable = false;
    }
    $values['show_placeholder'] = true;
    $values['default_value'] = false;
    $minus_label = $values;
    unset($minus_label['label']);
    if( $force_required ){
        ?>
        <style>.user-form-editor li.user_email .required-field { display: none; }</style>
        <?php } 
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
        ?>
        <li class="user_email <?php echo $cls; ?>">
            <?php self::legend( $label, $minus_label, $removable ); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'email' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'user_email' ); ?>

            <div class="user-form-holder">
                <?php self::common( $field_id, 'user_email', false, $values, $force_required, 'email' ); ?>
                <?php self::common_text( $field_id, $values ); ?>
            </div> <!-- .user-form-holder -->
        </li>
        <?php
    }
 public static function number( $field_id, $label, $values = array(), $removeable = true, $force_required = false  ) {
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
        ?>
        <li class="custom-field number <?php echo $cls; ?>">
            <?php self::legend( $label, $values, $removeable ); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'number' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'number' ); ?>

            <div class="user-form-holder">
                <?php self::common( $field_id, 'number', true, $values, $force_required, 'number' ); ?>
                <?php self::common_text( $field_id, $values ); ?>
                <?php self::login_value( $field_id, 'login_enable', $values,'number-'.$field_id); ?>
            </div> <!-- .user-form-holder -->
        </li>
        <?php
    }
    public static function user_url( $field_id, $label, $values = array() ) {
        if(!isset($values['label']) || $values['label'] == ''){
            $values['label'] = $label;
        }
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
        ?>
        <li class="user_url <?php echo $cls; ?>">
            <?php self::legend( $label, $values ); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'url' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'user_url' ); ?>

            <div class="user-form-holder">
                <?php self::common( $field_id, 'user_url', false, $values, false, 'url' ); ?>
                <?php self::common_text( $field_id, $values ); ?>
            </div> <!-- .user-form-holder -->
        </li>
        <?php
    }

    public static function description( $field_id, $label, $values = array() ) {
        if(!isset($values['label']) || $values['label'] == ''){
            $values['label'] = $label;
        }
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
        ?>
        <li class="user_bio <?php echo $cls; ?>">
            <?php self::legend( $label, $values ); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'textarea' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'description' ); ?>

            <div class="user-form-holder">
                <?php self::common( $field_id, 'description', false, $values, false, 'textarea' ); ?>
                <?php self::common_textarea( $field_id, $values ); ?>
                <?php self::login_value( $field_id, 'login_enable', $values,'description-'.$field_id); ?>
            </div> <!-- .user-form-holder -->
        </li>
        <?php
    }

    public static function password( $field_id, $label, $values = array() ) {
        global $post;
        if(!isset($values['label']) || $values['label'] == ''){
            $values['label'] = $label;
        }

        $force_required = false;
        $removable = true;
        if ( is_object( $post ) && get_the_ID() == FRONTEND_USER()->helper->get_option( 'user-registration-form', false ) ) {
            $force_required = true;
            $removable = false;
        }
        $values['show_placeholder'] = true;
        $values['default_value'] = false;
        $minus_label = $values;
        unset($minus_label['label']);
        $min_length_name = sprintf( '%s[%d][min_length]', 'user_input', $field_id );
        $pass_repeat_name = sprintf( '%s[%d][repeat_pass]', 'user_input', $field_id );
        $re_pass_label = sprintf( '%s[%d][re_pass_label]', 'user_input', $field_id );
        $show_pass = sprintf( '%s[%d][show_pass]', 'user_input', $field_id );
        $pass_strength = sprintf( '%s[%d][pass_strength]', 'user_input', $field_id );

        $min_length_value = isset( $values['min_length'] ) ? $values['min_length'] : '6';
        $pass_repeat_value = isset( $values['repeat_pass'] ) ? $values['repeat_pass'] : 'yes';
        $re_pass_label_value = isset( $values['re_pass_label'] ) ? $values['re_pass_label'] : __( 'Confirm Password', 'frontend_user_pro' );
        $show_pass_value = isset( $values['show_pass'] ) ? $values['show_pass'] : 'no';
        $pass_strength_value = isset( $values['pass_strength'] ) ? $values['pass_strength'] : 'no';
        if( $force_required ){ ?>
        <style>.user-form-editor li.password .required-field { display: none; } </style>
        <?php }
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
        ?>
        <li class="password <?php echo $cls; ?>">
            <?php self::legend( $label, $minus_label, $removable ); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'password' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'password' ); ?>

            <div class="user-form-holder">
                <?php self::common( $field_id, 'password', false, $values, $force_required, 'password' ); ?>
                <?php self::common_text( $field_id, $values ); ?>
                <div class="user-form-rows">
                    <label><?php _e( 'Minimum password length', 'frontend_user_pro' ); ?></label>

                    <input type="text" name="<?php echo $min_length_name ?>" value="<?php echo esc_attr( $min_length_value ); ?>" />
                </div> <!-- .user-form-rows -->

                <div class="user-form-rows">
                    <label><?php _e( 'Show Password', 'frontend_user_pro' ); ?></label>

                    <div class="user-form-rows">
                        <label>
                            <?php self::hidden_field( "[$field_id][show_pass_value]", 'no' ); ?>
                            <input class="show-pass" type="checkbox" name="<?php echo $show_pass ?>" value="yes"<?php checked( $show_pass_value, 'yes' ); ?> />
                            <?php _e( 'Require Show Password', 'frontend_user_pro' ); ?>
                        </label>
                    </div>
                </div> <!-- .user-form-rows -->
                <div class="user-form-rows">
                    <label><?php _e( 'Show Password Strength', 'frontend_user_pro' ); ?></label>

                    <div class="user-form-sub-fields">
                        <label>
                            <input class="strength-pass" type="checkbox" name="<?php echo $pass_strength ?>" value="yes"<?php checked( $pass_strength_value, 'yes' ); ?> />
                            <?php _e( 'Require Password Strength Meter', 'frontend_user_pro' ); ?>
                        </label>
                    </div>
                </div> <!-- .user-form-rows -->
                <div class="user-form-rows">
                    <label><?php _e( 'Password Re-type', 'frontend_user_pro' ); ?></label>

                    <div class="user-form-sub-fields">
                        <label>
                            <?php self::hidden_field( "[$field_id][repeat_pass]", 'no' ); ?>
                            <input class="retype-pass" type="checkbox" name="<?php echo $pass_repeat_name ?>" value="yes"<?php checked( $pass_repeat_value, 'yes' ); ?> />
                            <?php _e( 'Require Password repeat', 'frontend_user_pro' ); ?>
                        </label>
                    </div>
                </div> <!-- .user-form-rows -->
         

                <div class="user-form-rows<?php echo $pass_repeat_value != 'yes' ? ' user-hide' : ''; ?>">
                    <label><?php _e( 'Re-type password label', 'frontend_user_pro' ); ?></label>

                    <input type="text" name="<?php echo $re_pass_label ?>" value="<?php echo esc_attr( $re_pass_label_value ); ?>" />
                </div>

                <!-- .user-form-rows -->
            </div> <!-- .user-form-holder -->
        </li>
        <?php
    }

    public static function frontendc_user_paypal( $field_id, $label, $values = array() ) {
        if(!isset($values['label']) || $values['label'] == ''){
            $values['label'] = $label;
        }
        $values['is_meta'] = true;
        $values['name'] = 'frontendc_user_paypal';
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
        ?>
        <li class="frontendc_user_paypal <?php echo $cls; ?>">
            <?php self::legend( $label, $values ); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'email' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'frontendc_user_paypal' ); ?>

            <div class="user-form-holder">
                <?php self::common( $field_id, 'frontendc_user_paypal', true, $values, false, 'email' ); ?>
                <?php self::common_text( $field_id, $values ); ?>
                <?php self::login_value( $field_id, 'login_enable', $values,'frontendc_user_paypal-'.$field_id); ?>
            </div> <!-- .user-form-holder -->
        </li>
        <?php
    }


    public static function avatar( $field_id, $label, $values = array() ) {
        $max_file_name = sprintf( '%s[%d][max_size]', self::$input_name, $field_id );
        $max_file_value = $values ? $values['max_size'] : '1024';
        $help = esc_attr( __( 'Enter maximum upload size limit in KB', 'user' ) );
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
        ?>
        <li class="user_avatar <?php echo $cls; ?>">
            <?php self::legend( $label, $values ); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'image_upload' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'avatar' ); ?>
            <?php self::hidden_field( "[$field_id][count]", '1' ); ?>

            <div class="user-form-holder">
                <?php self::common( $field_id, 'avatar', false, $values ); ?>

                <div class="user-form-rows">
                    <label><?php _e( 'Max. file size', 'user' ); ?></label>
                    <input type="text" class="smallipopInput" name="<?php echo $max_file_name; ?>" value="<?php echo $max_file_value; ?>" title="<?php echo $help; ?>">
                </div> <!-- .user-form-rows -->
            </div> <!-- .user-form-holder -->
        </li>
        <?php
    }




    public static function google_map( $field_id, $label, $values = array() ) {
        $zoom_name = sprintf('%s[%d][zoom]', self::$input_name, $field_id);
        $address_name = sprintf('%s[%d][address]', self::$input_name, $field_id);
        $default_pos_name = sprintf('%s[%d][default_pos]', self::$input_name, $field_id);
        $show_lat_name = sprintf('%s[%d][show_lat]', self::$input_name, $field_id);

        $zoom_value = $values ? $values['zoom'] : '12';
        $address_value = $values ? $values['address'] : 'yes';
        $show_lat_value = $values ? $values['show_lat'] : 'no';
        $default_pos_value = $values ? $values['default_pos'] : '40.7143528,-74.0059731';

        $zoom_help = esc_attr( __( 'Set the map zoom level', 'user' ) );
        $pos_help = esc_attr( __( 'Enter default latitude and longitude to center the map', 'user' ) );
        $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
        ?>
        <li class="custom-field custom_image <?php echo $cls; ?>">
            <?php self::legend( $label, $values ); ?>
            <?php self::hidden_field( "[$field_id][input_type]", 'map' ); ?>
            <?php self::hidden_field( "[$field_id][template]", 'google_map' ); ?>

            <div class="user-form-holder">
                <?php self::common( $field_id, 'google_map', true, $values ); ?>

                <div class="user-form-rows">
                    <label><?php _e( 'Zoom Level', 'user' ); ?></label>
                    <input type="text" class="smallipopInput" name="<?php echo $zoom_name; ?>" value="<?php echo $zoom_value; ?>" title="<?php echo $zoom_help; ?>">
                </div> <!-- .user-form-rows -->

                <div class="user-form-rows">
                    <label><?php _e( 'Default Co-ordinate', 'user' ); ?></label>
                    <input type="text" class="smallipopInput" name="<?php echo $default_pos_name; ?>" value="<?php echo $default_pos_value; ?>" title="<?php echo $pos_help; ?>">
                </div> <!-- .user-form-rows -->

                <div class="user-form-rows">
                    <label><?php _e( 'Address Button', 'user' ); ?></label>

                    <div class="user-form-sub-fields">
                        <label>
                            <?php self::hidden_field( "[$field_id][address]", 'no' ); ?>
                            <input type="checkbox" name="<?php echo $address_name ?>" value="yes"<?php checked( $address_value, 'yes' ); ?> />
                            <?php _e( 'Show address find button', 'user' ); ?>
                        </label>
                    </div>
                </div> <!-- .user-form-rows -->

                <div class="user-form-rows">
                    <label><?php _e( 'Show Latitude/Longitude', 'user' ); ?></label>

                    <div class="user-form-sub-fields">
                        <label>
                            <?php self::hidden_field( "[$field_id][show_lat]", 'no' ); ?>
                            <input type="checkbox" name="<?php echo $show_lat_name ?>" value="yes"<?php checked( $show_lat_value, 'yes' ); ?> />
                            <?php _e( 'Show latitude and longitude input box value', 'user' ); ?>
                        </label>
                    </div>
                </div> <!-- .user-form-rows -->
                <?php self::login_value( $field_id, 'login_enable', $values,'google_map-'.$field_id); ?>
            </div> <!-- .user-form-holder -->
        </li>
        <?php
    }

    public static function custom_mail_user( $field_id, $label, $values = array(), $removeable = true, $force_required = false  ) {
        $subject_name = sprintf( '%s[%d][subject]', self::$input_name , $field_id );
        $from_name = sprintf( '%s[%d][from]', self::$input_name, $field_id );
        $content_name = sprintf( '%s[%d][content]', self::$input_name, $field_id );
        // $css_name = sprintf( '%s[%d][css]', 'user_input', $field_id );

        $subject_value = $values && isset( $values['subject'] ) ? esc_attr( $values['subject'] ) : '';
        $from_value = $values && isset( $values['from'] ) ? esc_attr( $values['from'] ) : '';
        $content_value = $values && isset( $values['content'] ) ? esc_attr( $values['content'] ) : '';
        // $css_value = $values && isset( $values['css'] ) ? esc_attr( $values['css'] ) : '';
        $user = wp_get_current_user();
        if (!$from_value) {
          $from_value = 'USEREMAIL';
      }

      if (!$content_value) {
         $content_value = 'Hi USERNAME,
         Your registration for BLOG_TITLE .

         You can log in, using your username and password that you created when registering for our website, at the following URL: LOGINLINK

         If you have any questions, or problems, then please do not hesitate to contact us.

         Name,
         Company,
         Contact details';
     }

     if (!$subject_value) {
        $subject_value = 'BLOG_TITLE';
    }
    if ( is_multisite() ) {
        $message = str_replace( 'SITE_NAME', $GLOBALS['current_site']->site_name, $message );
    }
    $cls = '';
        if(array_key_exists('full_width', $values)) {
            $cls = $values['full_width'];
        }
    ?>
    <li class="custom-field custom_html <?php echo $cls; ?>">
        <?php self::legend( $label, $values, $removeable ); ?>
        <?php self::hidden_field( "[$field_id][input_type]", 'custom_mail_user' ); ?>
        <?php self::hidden_field( "[$field_id][name]", 'mail_user' ); ?>
        <?php self::hidden_field( "[$field_id][template]", 'custom_mail_user' ); ?>

        <div class="user-form-holder">
            <div class="user-form-rows">
                <label><?php _e( 'Subject', 'frontend_user' ); ?></label>
                <input type="text" name="<?php echo $subject_name; ?>" class="smallipopInput" value="<?php echo esc_attr( $subject_value ); ?>" title="<?php _e( ' Enter Subject for Email to User Register.', 'frontend_user'); ?>"/>
            </div> <!-- .user-form-rows -->
            <div class="user-form-rows">
                <label><?php _e( 'Email From', 'frontend_user' ); ?></label>
                <input type="text" name="<?php echo $from_name; ?>" class="smallipopInput" value="<?php echo esc_attr( $from_value ); ?>" title="<?php _e( 'Insert Email ID from which email sent User Register.', 'frontend_user'); ?>"/>
            </div> <!-- .user-form-rows -->

            <div class="user-form-rows">
                <label><?php _e( 'Content', 'frontend_user' ); ?></label>
                <textarea class="smallipopInput" title="<?php _e( 'Insert Email Content sent to User Register.', 'frontend_user'); ?>" name="<?php echo $content_name; ?>" rows="3"><?php echo esc_html( $content_value ); ?></textarea>
            </div> <!-- .user-form-rows -->
            To take advantage of dynamic data, you can use the following placeholders:
            <code>USERNAME</code>
            ,
            <code>BLOG_TITLE</code>
            ,
            <code>BLOG_URL</code>
            ,
            <code>LOGINLINK</code>
            ,
            <code>USEREMAIL</code>
            . Username will be the user login in most cases.
            <?php self::login_value( $field_id, 'login_enable', $values,'custom_mail_user-'.$field_id); ?>
        </div> <!-- .user-form-holder -->
    </li>
    <?php
}
}