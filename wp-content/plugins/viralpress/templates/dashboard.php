<?php
/**
 * @ViralPress 
 * @Wordpress Plugin
 * @author InspiredDev <iamrock68@gmail.com>
 * @copyright 2016
*/
defined( 'ABSPATH' ) || exit;

$vp_instance = $attributes['vp_instance'];
$uid = get_current_user_ID();
$udata = get_userdata( $uid );
$loader = '<img src="'.$vp_instance->settings['IMG_URL'].'/spinner.gif"/>';
$profile = home_url( '/profile' );//get_author_posts_url( $uid );
add_filter( 'get_comment_text', 'vp_format_dash_comments', 10, 3 );
list( $fb_url, $tw_url, $gp_url ) = get_user_social_profiles( $uid );

if( !empty( $_REQUEST['logout'] ) && $vp_instance->settings['disable_login'] ) {
	$uu = wp_logout_url( home_url( '/' ) );
	echo '<script>window.location.href="'.html_entity_decode($uu).'"</script>';
	exit;
}

?>
<div class="row">
	<div class="col-lg-12">
    	<h3 class="text-center"><?php _e( 'Welcome', 'viralpress' )?> <?php echo $udata->first_name.' '.$udata->last_name?></h3>
        <div class="vp-clearfix"></div>
    </div>
</div>

<div class="row">
	<div class="col-lg-6">
    	<form id="udata_form" class="vp-glow">
        	<?php wp_nonce_field( 'vp-ajax-action-'.get_current_user_id(), '_nonce' ); ?>
        	<fieldset style="padding:15px">
            	<h4><?php _e( 'Personal Information' , 'viralpress' ); ?></h4>
                <input type="hidden" name="action" value="vp_update_user">
                <label><?php _e( 'First name', 'viralpress' )?></label>
                <input type="text" class="vp-form-control" name="fname" value="<?php echo $udata->first_name?>">
                <label><?php _e( 'Last name', 'viralpress' )?></label>
                <input type="text" class="vp-form-control" name="lname" value="<?php echo $udata->last_name?>">
                <label><?php _e( 'Email', 'viralpress' )?></label>
                <input type="text" class="vp-form-control" name="email" value="<?php echo $udata->user_email?>">
                <!--
                <label><?php _e( 'Display name', 'viralpress' )?></label>
                <input type="text" class="vp-form-control" name="dname" value="<?php echo $udata->display_name?>">
                -->
                <br/><br/>
                <button class="btn btn-danger udata_form_btn"><?php _e( 'Save', 'viralpress' )?></button>
                <span class="vp_save_loader" style="display:none"><?php echo $loader?></span>
                <span class="vp_save_ok" style="display:none">&nbsp;&nbsp;<i class="glyphicon glyphicon-ok"></i></span>
            </fieldset>
        </form>
        <div class="vp-clearfix-lg"></div>
    	<form id="udata_s_form" class="vp-glow">
        	<?php wp_nonce_field( 'vp-ajax-action-'.get_current_user_id(), '_nonce' ); ?>
        	<fieldset style="padding:15px">
            	<h4><?php _e( 'Account Security' , 'viralpress' ); ?></h4>
                <input type="hidden" name="action" value="vp_s_update_user">
                <label><?php _e( 'New password', 'viralpress' )?></label>
                <input type="password" class="vp-form-control" name="pwd">
                <label><?php _e( 'Repeat password', 'viralpress' )?></label>
                <input type="password" class="vp-form-control" name="pwd2"><br/><br/>
                <button class="btn btn-danger udata_s_form_btn"><?php _e( 'Save', 'viralpress' )?></button>
                <span class="vp_s_save_loader" style="display:none"><?php echo $loader?></span>
                <span class="vp_s_save_ok" style="display:none">&nbsp;&nbsp;<i class="glyphicon glyphicon-ok"></i></span>
            </fieldset>
        </form>
        
    </div>
    
    <div class="col-lg-6">
    	<form id="udata_c_form" class="vp-glow">
        	<?php wp_nonce_field( 'vp-ajax-action-'.get_current_user_id(), '_nonce' ); ?>
        	<fieldset style="padding:15px">
            	<h4><?php _e( 'Social Ids' , 'viralpress' ); ?></h4>
                <input type="hidden" name="action" value="vp_c_update_user">
                <label><?php _e( 'Facebook profile', 'viralpress' )?></label>
                <input type="text" class="vp-form-control" name="fb_url" value="<?php echo $fb_url?>">
                <label><?php _e( 'Twitter Profile', 'viralpress' )?></label>
                <input type="text" class="vp-form-control" name="tw_url" value="<?php echo $tw_url?>">
                <label><?php _e( 'Google Profile', 'viralpress' )?></label>
                <input type="text" class="vp-form-control" name="gp_url" value="<?php echo $gp_url?>"><br/><br/>
                <button class="btn btn-danger udata_c_form_btn"><?php _e( 'Save', 'viralpress' )?></button>
                <span class="vp_c_save_loader" style="display:none"><?php echo $loader?></span>
                <span class="vp_c_save_ok" style="display:none">&nbsp;&nbsp;<i class="glyphicon glyphicon-ok"></i></span>
            </fieldset>
        </form>
   		<div class="vp-clearfix-lg"></div>
        <div class="vp-glow">
            <fieldset style="padding:15px">
                <div class="row">
                    <div class="col-lg-6">
                        <h4><?php _e( 'Account Information' , 'viralpress' ); ?></h4>
                        <label><?php _e( 'Username', 'viralpress' );echo '<br/><b>'.$udata->user_login.'</b>'?></label><br/>
                        <label><?php _e( 'Display name', 'viralpress' );echo '<br/><b>'.$udata->display_name.'</b>'?></label><br/>
                        <label><?php _e( 'Total post', 'viralpress' );?> <br/><span class="label label-info"><?php echo count_user_posts($uid)?></span></label><br/><br/>
                    </div>
                    <div class="col-lg-6">
                        <h4><?php _e( 'Manage Data' , 'viralpress' ); ?></h4><br/>
                        <button class="btn btn-danger vp-dash-media"><i class="glyphicon glyphicon-picture"></i>&nbsp;&nbsp;<?php _e( 'My photos', 'viralpress' )?></button>
                        <div class="vp-clearfix"></div>
                        <a class="btn btn-success" style="color:white" href="<?php echo $profile?>"><i class="glyphicon glyphicon-edit"></i>&nbsp;&nbsp;<?php _e( 'My posts', 'viralpress' )?></a>
                        <div class="vp-clearfix"></div>
                        <a class="btn btn-primary" style="color:white" href="<?php echo $profile?>"><i class="glyphicon glyphicon-edit"></i>&nbsp;&nbsp;<?php _e( 'My profile', 'viralpress' )?></a>
                    </div>
                 </div>
             </fieldset>
         </div>
    </div>
</div>
<div class="vp-clearfix-lg"></div>
<hr/>
<div class="row vp_dash_comments">
	<div class="col-lg-6">
    	<a href="<?php echo home_url( '/post-comments' )?>"><?php _e( 'View all', 'viralpress' )?></a>
        <?php
		vp_print_comments( $uid, 'on_post', 0 );
		?>
    </div>
    <div class="col-lg-6">
    	<a href="<?php echo home_url( '/my-comments' )?>"><?php _e( 'View all', 'viralpress' )?></a>
        <?php
		vp_print_comments( $uid, 'by_me', 0 );
		?>
    </div>
</div>

<script>
	jQuery('.entry-title').eq(0).hide();
</script>