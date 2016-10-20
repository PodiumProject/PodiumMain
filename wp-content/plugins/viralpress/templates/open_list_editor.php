<?php
/**
 * @ViralPress 
 * @Wordpress Plugin
 * @author InspiredDev <iamrock68@gmail.com>
 * @copyright 2016
*/
defined( 'ABSPATH' ) || exit;

$lang = $attributes['lang'];
$vp_instance = $attributes['vp_instance'];
$post = $attributes['post'];
?>
<div class="vp-clearfix-lg"></div>
<ul id="vp-tabs">
    <li class="active vp-op-list"><?php _e( 'Image', 'viralpress' )?></li>
    <li class="vp-op-news"><?php _e( 'News', 'viralpress' )?></li>
    <li class="vp-op-embed"><?php _e( 'Embed', 'viralpress' )?></li>
    <li class="vp-op-video"><?php _e( 'Video', 'viralpress' )?></li>
    <li class="vp-op-audio"><?php _e( 'Audio', 'viralpress' )?></li>
    <li class="vp-op-gallery"><?php _e( 'Gallery', 'viralpress' )?></li>
    <li class="vp-op-playlist"><?php _e( 'Playlist', 'viralpress' )?></li>
</ul>

<form id="open_list_form">
<?php wp_nonce_field( 'vp-ajax-action-'.get_current_user_id(), '_nonce' ); ?>
<ul id="vp-tab">
    <li class="active vp-op-list">
        <h2><?php _e( 'Submit an image', 'viralpress' )?></h2>
        <div class="op_editor"></div>
    </li>
    <li class="vp-op-news">
        <h2><?php _e( 'Submit a news', 'viralpress' )?></h2>
        <div class="op_editor"></div>
    </li>
    <li class="vp-op-embed">
        <h2><?php _e( 'Embed from social media', 'viralpress' )?></h2>
        <div class="op_editor"></div>
    </li>
    <li class="vp-op-video">
        <h2><?php _e( 'Submit a video', 'viralpress' )?></h2>
        <div class="op_editor"></div>
    </li>
    <li class="vp-op-audio">
        <h2><?php _e( 'Submit an audio', 'viralpress' )?></h2>
        <div class="op_editor"></div>
    </li>
    <li class="vp-op-gallery">
        <h2><?php _e( 'Submit a gallery', 'viralpress' )?></h2>
        <div class="op_editor"></div>
    </li>
    <li class="vp-op-playlist">
        <h2><?php _e( 'Submit a playlist', 'viralpress' )?></h2>
        <div class="op_editor"></div>
    </li>
</ul>
<div class="vp-clearfix"></div>
<div class="alert alert-info open_list_form_feedback" style="display:none"></div>
<input type="hidden" name="post_id" value="<?php echo $post->ID?>"/>
<input type="hidden" name="action" value="vp_open_list_submit"/>
</form>

<?php echo get_template_html('modals')?>