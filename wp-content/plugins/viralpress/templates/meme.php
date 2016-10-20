<?php
/**
 * @ViralPress 
 * @Wordpress Plugin
 * @author InspiredDev <iamrock68@gmail.com>
 * @copyright 2016
*/
defined( 'ABSPATH' ) || exit;

$media_id = @(int)$_REQUEST['media_id'];

if( empty( $media_id ) ) $media_id = @(int)$attributes['in']['media_id'];

if( !current_user_can( 'edit_post', $media_id ) || empty( $media_id ) ) :
	echo '<div class="alert alert-info">'.__( 'You cannot edit this media', 'viralpress' ).'</div>';	
else:
	$img = wp_get_attachment_image_src( $media_id , 'large' );
	$img = @$img[0];
	
	if( empty( $img ) ) {
		echo '<div class="alert alert-info">'.__( 'Failed to load image', 'viralpress' ).'</div>';	
	}
	else {
		
		if( !wp_script_is( 'vp-meme-js', 'done' ) ) {
			vp_enqueue_script_meme_page();
		}
?>
<div class="row editor" id="editor" style="overflow:auto">    
    <div class="col-lg-6">
        <h5>
            <?php _e( 'Drag texts around after adding them', 'viralpress' )?>
        </h5>
        <div style="overflow:auto">
            <canvas id="mycanvas" class="editor-after-canvas" style="width:500px"></canvas>
            <canvas id="hcanvas" class="editor-after-canvas" style="display:none" width="800px"></canvas>
            <canvas id="hcanvas2" class="editor-after-canvas" style="display:none" width="800px"></canvas>
            <div class="editor-after-img" style="display:none"></div>
            <div class="editor-before-img" style="display:none"></div>
        </div> 
        <br/>
        <?php if( $vp_instance->settings['load_recap'] && $vp_instance->settings['recap_post'] ) :?>
        <form class="recap">
            <?php echo recaptcha_get_html($vp_instance->settings['recap_key'], '');?>
        </form>
        <br/>
        <?php endif;?>
        <button class="btn btn-success apply-opt import-edited-img-int"><i class="glyphicon glyphicon-save"></i>&nbsp;&nbsp;<?php _e( 'Save', 'viralpress' )?></button>&nbsp;&nbsp;
        <a class="btn btn-info apply-opt" id="download-edited-img-int"><i class="glyphicon glyphicon-download-alt"></i>&nbsp;&nbsp;<?php _e( 'Download', 'viralpress' )?></a>
        <br/><br/>
        <div class="vp_meme_feed alert alert-info" style="display:none"></div>		
    </div>
    
    <div class="col-lg-6">
    	<!--control for wm image-->
        <div class="row">                    
             <!--control for wm text-->    
            <div class="col-lg-12">
                
                <div class="row">
                    <div class="col-lg-4"><?php _e( 'Text1', 'viralpress' )?>: <input class="vp-form-control medium-input wm-text" value=""></div>
                    <div class="col-lg-2">
                        <?php _e( 'Color', 'viralpress' )?>: <br/><input class="vp-form-control small-input tint-color wm-col" value="">
                    </div>
                    <div class="col-lg-2"><?php _e( 'Opacity', 'viralpress' )?>: <input class="vp-form-control small-input wm-opacity" value="1"></div>
                    <div class="col-lg-2"><?php _e( 'Size', 'viralpress' )?>: <input class="vp-form-control small-input wm-font-size" value="36"></div>
                </div><br/>
                <div class="row">
                    <div class="col-lg-4"><?php _e( 'Font', 'viralpress' )?>: <select class="wm-font vp-form-control medium-input"></select></div>
                    <!--<div class="col-lg-2"><?php _e( 'Rotate', 'viralpress' )?>: <input class="vp-form-control small-input wm-rotate" value="0"></div>-->
                </div>
                
               
                <div class="row">
                    <br/><br/>
                    <div class="col-lg-4"><?php _e( 'Text 2', 'viralpress' )?>: <input class="vp-form-control medium-input wm-text-2" value=""></div>
                    <div class="col-lg-2">
                        <?php _e( 'Color', 'viralpress' )?>: <br/><input class="vp-form-control small-input tint-color wm-col-2" value="">
                    </div>
                    <div class="col-lg-2"><?php _e( 'Opacity', 'viralpress' )?>: <input class="vp-form-control small-input wm-opacity-2" value="1"></div>
                    <div class="col-lg-2"><?php _e( 'Size', 'viralpress' )?>: <input class="vp-form-control small-input wm-font-size-2" value="36"></div>
                </div><br/>
                <div class="row">
                    <div class="col-lg-4"><?php _e( 'Font', 'viralpress' )?>: <select class="wm-font-2 vp-form-control medium-input"></select></div>
                    <!--<div class="col-lg-2"><?php _e( 'Rotate', 'viralpress' )?>: <input class="vp-form-control small-input wm-rotate-2" value="0"></div>-->
                </div>
                
                <br/>			
                <button class="btn btn-info wmm-apply"><?php _e( 'Apply', 'viralpress' )?></button>
                <button class="btn btn-danger wmm-reset"><?php _e( 'Reset', 'viralpress' )?></button>
            </div>
            <!--/control for wm text-->
        </div>        
	</div>
</div>
<button class="import-edited-img" style="display:none"></button>
<input type="hidden" id="imw"/><input type="hidden" id="imh"/>

<script>
var preload_file = '<?php echo esc_js( $img )?>';
var media_id = '<?php echo $media_id?>';
</script>

<?php } endif;?>