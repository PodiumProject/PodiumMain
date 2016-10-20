<?php
/**
 * @ViralPress 
 * @Wordpress Plugin
 * @author InspiredDev <iamrock68@gmail.com>
 * @copyright 2016
*/
defined( 'ABSPATH' ) || exit;
?>
<?php echo '<h2>'.$attributes['title'].'</h2><br/>';?>
<?php echo text_entry_form()?>
<?php if( !empty( $attributes['edit_post_id'] ) ) $edit_post_id = $attributes['edit_post_id'] ;?>

<div class="vp_container_test"></div>
<div class="alert alert-success submit_errors" style="display:none"></div>

<div class="text-center editor_loader">
	<img src="<?php echo $attributes['vp_instance']->settings['IMG_URL']?>/spinner-2x.gif"/>
</div>

<form style="display:none" action="" id="add_new_post_form" method="POST">
	<?php wp_nonce_field( 'vp-add-post-'.get_current_user_id(), 'vp_add_post_nonce' ); ?>
	<input type="hidden" name="vp_post_id" value="<?php if(!empty($edit_post_id) && empty($attributes['is_copying']))echo $edit_post_id;?>" />
    <input type="hidden" name="vp_post_type" id="vp_post_type" value="<?php echo $attributes['post_type']?>" />
    <input type="hidden" name="vp_add_new_post" id="add_new_post" value="true" />
    <input type="hidden" name="action" value="vp_add_post" />
	<div class="row vp-glow">
    	<div class="col-lg-9">
            <fieldset>
            	<legend class="more_items_glow"><?php _e( 'Basic', 'viralpress' )?></legend>
                <div class="row more_items_x">
                    <div class="col-lg-5">
                        <div class="vp-uploader vp-uploader-sum text-center" data-target="post_thumbnail">
                            <button class="thumbnail_uploader sum_img btn btn-sm btn-danger">
                                <i class="glyphicon glyphicon-picture"></i>&nbsp;&nbsp;
                                <span class="default_text">
                                <?php _e( 'Upload a thumbnail' , 'viralpress' )?>
                                </span><span class="dload_text" style="display:none"><?php _e( 'Downloading...', 'viralpress' )?></span>
                            </button>
                            <h4 class="big_or"><?php _e( 'OR' , 'viralpress' )?></h4>
                            <span class="thumbnail_uploader sum_img thumbnail_uploader_url vp-pointer" style="font-size:14px">
                            	<i class="glyphicon glyphicon-link"></i>&nbsp;<small><?php _e( 'Upload from link' , 'viralpress' )?></small>
                            </span>
                        </div>
                        <div class="vp-uploader-nopad vp-uploader-nopad-sum">
                        	<div class="vp-uploader-image vp-uploader-image-sum"></div>
                            <button class="thumbnail_uploader sum_img btn btn-sm btn-info change-item-btn">
                                <i class="glyphicon glyphicon-picture"></i>&nbsp;&nbsp;
                                <span class="default_text">
                                <?php _e( 'Change photo', 'viralpress' )?>
                                </span><span class="dload_text" style="display:none"><?php _e( 'Downloading...', 'viralpress' )?></span>
                            </button>&nbsp;
                            <button class="thumbnail_remove sum_img btn btn-sm btn-danger">
                                <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;
                                <?php _e( 'Remove' , 'viralpress' )?>
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-7">	
                        <label for="post_title"><?php _e('Title', 'viralpress') ?></label>
                        <input type="text" name="post_title" id="post_title" class="vp-form-control required" value="" 
                        placeholder="<?php _e( 'Type a title', 'viralpress' );echo ' '; _e( '(Required)', 'viralpress' )?>"/>
                        <br/><br/>
                         <label for="post_summary"><?php _e('Subtitle', 'viralpress') ?></label>
                        <textarea name="post_summary" id="post_summary" rows="4" class="vp-form-control" 
                        placeholder="<?php _e( 'Type a subtitle', 'viralpress' ); echo ' '; _e( '(Optional)', 'viralpress' )?>"></textarea>
                    </div>
                </div>
            </fieldset>
            <br/>
            <fieldset class="editor_preface" style="display:none">
            	<legend class="more_items_glow"><?php _e( 'Post Preface', 'viralpress' )?></legend>
                <div class="vp-pull-right vp-pointer">
					<i class="glyphicon glyphicon-trash remove_preface" title="<?php _e( 'Remove preface', 'viralpress' )?>"></i>
				</div>
                <div class="row more_items_x">
                    <div class="col-lg-5">
                        <div class="vp-uploader vp-uploader-pref vp-uploader-lesspad text-center" data-target="preface_image">
                            <button class="thumbnail_uploader pref_img btn btn-sm btn-danger">
                                <i class="glyphicon glyphicon-picture"></i>&nbsp;&nbsp;
                                <span class="default_text">
                                <?php _e( 'Upload a thumbnail' , 'viralpress' )?>
                                </span><span class="dload_text" style="display:none"><?php _e( 'Downloading...', 'viralpress' )?></span>
                            </button>
                            <h4><?php _e( 'OR' , 'viralpress' )?></h4>
                            <span class="thumbnail_uploader pref_img thumbnail_uploader_url vp-pointer" style="font-size:14px">
                            	<i class="glyphicon glyphicon-link"></i>&nbsp;<small><?php _e( 'Upload from link' , 'viralpress' )?></small>
                            </span>
                        </div>
                        <div class="vp-uploader-nopad vp-uploader-nopad-pref">
                        	<div class="vp-uploader-image vp-uploader-image-pref"></div>
                            <button class="thumbnail_uploader pref_img btn btn-sm btn-info change-item-btn">
                                <i class="glyphicon glyphicon-picture"></i>&nbsp;&nbsp;
                                <span class="default_text">
                                <?php _e( 'Change photo', 'viralpress' )?>
                                </span><span class="dload_text" style="display:none"><?php _e( 'Downloading...', 'viralpress' )?></span>
                            </button>&nbsp;
                            <button class="thumbnail_remove pref_img btn btn-sm btn-danger">
                                <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;
                                <?php _e( 'Remove' , 'viralpress' )?>
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-7">	
                        <label for="preface_title"><?php _e('Title', 'viralpress') ?></label>
                        <input type="text" name="preface_title" id="preface_title" class="vp-form-control required" value="" 
                        placeholder="<?php _e( 'Type a title', 'viralpress' );echo ' '; _e( '(Optional)', 'viralpress' )?>"/>
                        <br/><br/>
                        <label for="preface_desc"><?php _e('Summary', 'viralpress') ?></label>
                        <div class="wp-editor-tabs">
							<i class="pull-right glyphicon glyphicon-eye-open toggle_tinymce vp-pointer"></i>
						</div>
                        <textarea name="preface_desc" id="preface_desc" rows="4" class="vp-form-control tinymce" 
                        placeholder="<?php _e( 'Type a summary', 'viralpress' ); echo ' '; _e( '(Optional)', 'viralpress' )?>"></textarea>
                    </div>
                </div>
            </fieldset>
            <h3 class="post_entries">
				<?php _e( 'Post entires', 'viralpress' )?> 
                <div class="pull-right">
                	<button class="btn btn-sm btn-warning editor_add_preface"><i class="glyphicon glyphicon-plus"></i>&nbsp;<?php _e( 'Add preface', 'viralpress' )?></button>
                </div>
            </h3> 
			<div class="more_items_holder faqs">
                <div class="more_items more_items_numbered">
                </div>
            </div>
            <?php if( $attributes['post_type'] == 'quiz' ): ?>
            <br/>
            <fieldset>
            	<?php if( in_array( $attributes['post_type'], array('quiz') ) ) :?>
                	<button class="btn btn-success add_quiz_item <?php  echo $attributes['post_type'] == 'quiz' ? '' : 'no_quiz'?>">
                    	<i class="glyphicon glyphicon-plus"></i>&nbsp;<?php _e('Add More Question', 'viralpress') ?>
                    </button>
                    <?php if( $attributes['post_type'] == 'quiz' ): ?>
                    <button class="btn btn-info add_res_item"><i class="glyphicon glyphicon-plus"></i>&nbsp;<?php _e('Add More Results', 'viralpress') ?></button>
                    <?php endif;?>
                <?php endif;?>
				
            </fieldset>
            <h3><?php _e( 'Results', 'Viralpress' )?></h3>
            <div class="more_results_holder faqs">
                <div class="more_items_x">
                </div>
            </div>
            <?php endif;?>
            <br/>
            <fieldset>
            	<?php if( in_array( $attributes['post_type'], array('news', 'lists', 'list', 'audio', 'videos', 'video', 'pin', 'pins', 'polls', 'poll', 'gallery', 'playlist') ) ) :?>
                    <button class="btn btn-success add_text_item"><i class="glyphicon glyphicon-plus"></i>&nbsp;<?php _e('Text', 'viralpress') ?></button>
                    <button class="btn btn-info add_list_item"><i class="glyphicon glyphicon-plus"></i>&nbsp;<?php _e('Image', 'viralpress') ?></button>
                    <?php if($vp_instance->settings['self_video']):?>
                    <button class="btn btn-danger add_self_video_item"><i class="glyphicon glyphicon-plus"></i>&nbsp;<?php _e('Video', 'viralpress') ?></button>
                    <?php endif;?>
                    <?php if($vp_instance->settings['self_audio']):?>
                    <button class="btn btn-success add_self_audio_item"><i class="glyphicon glyphicon-plus"></i>&nbsp;<?php _e('Audio', 'viralpress') ?></button>
                    <?php endif;?>
                    <?php if($vp_instance->settings['self_audio'] || $vp_instance->settings['self_video']):?>
                    <button class="btn btn-info add_playlist_item"><i class="glyphicon glyphicon-plus"></i>&nbsp;<?php _e('Playlist', 'viralpress') ?></button>
                    <?php endif;?>
                    <button class="btn btn-primary add_pin_item"><i class="glyphicon glyphicon-plus"></i>&nbsp;<?php _e('Embed', 'viralpress') ?></button>
                    <button class="btn btn-danger add_gallery_item"><i class="glyphicon glyphicon-plus"></i>&nbsp;<?php _e('Gallery', 'viralpress') ?></button>
                    <button class="btn btn-info add_quiz_item no_quiz"><i class="glyphicon glyphicon-plus"></i>&nbsp;<?php _e('Poll', 'viralpress') ?></button>
                <?php else:?>
                	<button class="btn btn-success add_quiz_item <?php  echo $attributes['post_type'] == 'quiz' ? '' : 'no_quiz'?>">
                    	<i class="glyphicon glyphicon-plus"></i>&nbsp;<?php _e('Add More Question', 'viralpress') ?>
                    </button>
                    <?php if( $attributes['post_type'] == 'quiz' ): ?>
                    <button class="btn btn-info add_res_item"><i class="glyphicon glyphicon-plus"></i>&nbsp;<?php _e('Add More Results', 'viralpress') ?></button>
                    <?php endif;?>
                <?php endif;?>
				
            </fieldset>
 		</div>
        <div class="col-lg-3 more_item_x">
        	<fieldset>
            	<legend class="more_items_glow"><?php _e( 'Settings', 'viralpress' )?></legend>
                <div class="vp-clearfix"></div>
                <div class="vp-clearfix"></div>
                <div class="vp-clearfix"></div>
                <div class="vp-clearfix"></div>
                <?php if( $attributes['post_type'] == 'quiz' ) :?>
                <label><?php _e( 'Select the quiz type', 'viralpress' )?></label>
                
                <!--
                <input type="checkbox" name="fixed_answer" id="fixed_answer" checked="checked"/>&nbsp;&nbsp;
				<label for="fixed_answer"><?php _e( 'Fixed answer type', 'viralpress' )?></label>
                -->
                
                <select name="quiz_type" id="quiz_type">
                	<option value="trivia"><?php _e( 'Trivia Quiz', 'viralpress' )?></option>
                    <option value="mcq"><?php _e( 'MCQ Quiz', 'viralpress' )?></option>
                    <option value="person1"><?php _e( 'Personality Quiz (Type - I)', 'viralpress' )?></option>
                    <option value="person2"><?php _e( 'Personality Quiz (Type - II)', 'viralpress' )?></option>
                </select>
                <br/><br/>
				<?php endif;?>
   
            	<label><?php _e( 'Category', 'viralpress' )?></label><br/>
				<?php 
					
					$select_cats = wp_dropdown_categories( 
						array( 
							'orderby' => 'NAME', 
							'hide_empty' => 0, 
							'class' => 'vp_post_select vp-form-control' ,
							'echo' => 0
							) 
						);
    				echo $select_cats = str_replace( "name='cat' id=", "name='cat[]' multiple='multiple' id=", $select_cats );
				?>
                
                <br/><br/>
                <label><?php _e( 'Post tags', 'viralpress' )?></label><br/>
                <input type="text" name="post_tag" id="post_tags" value="" class="vp-form-control"/>
                <br/>
                
                <label><?php _e( 'Publication status', 'viralpress' )?></label><br/>
                <select name="publication" id="publication" class="vp_post_select vp-form-control">
                	<option value="draft"><?php _e( 'Save as draft', 'viralpress' )?></option>
                    <option value="publish"><?php _e( 'Publish now', 'viralpress' )?></option>
                    <?php if(!empty($edit_post_id)):?>
                    <option value="delete"><?php _e( 'Delete', 'viralpress' )?></option>
                    <?php endif;?>
                </select>
                
                <br/><br/>
                
                <label><?php _e( 'Items per page', 'viralpress' )?></label><br/>
                <select name="items_per_page" id="items_per_page" class="vp_post_select vp-form-control" 
					<?php if( in_array( $attributes['post_type'], array( 'quiz', 'poll', 'polls' ) ) )echo 'disabled';?>>
                	<option value="0"><?php _e( 'All', 'viralpress' )?></option>
                    <option value="1"><?php _e( '1', 'viralpress' )?></option>
                    <option value="2"><?php _e( '2', 'viralpress' )?></option>
                    <option value="3"><?php _e( '3', 'viralpress' )?></option>
                    <option value="4"><?php _e( '4', 'viralpress' )?></option>
                    <option value="5"><?php _e( '5', 'viralpress' )?></option>
                    <option value="7"><?php _e( '7', 'viralpress' )?></option>
                    <option value="10"><?php _e( '10', 'viralpress' )?></option>
                    <option value="15"><?php _e( '15', 'viralpress' )?></option>
                    <option value="20"><?php _e( '20', 'viralpress' )?></option>
                </select>
                
                <br/><br/>
                <label><?php _e( 'List sort order', 'viralpress' )?></label>
                <select name="sort_order" id="sort_order" class="vp_post_select vp-form-control">
                	<option value="asc"><?php _e( 'Show in ascending order', 'viralpress' )?></option>
                    <option value="desc"><?php _e( 'Show in descending order', 'viralpress' )?></option>
                </select>
                
                <br/><br/>
                <label><?php _e( 'List style', 'viralpress' )?></label>
                 <select name="list_style" id="list_style" class="vp_post_select vp-form-control">
                	<option value="legend"><?php _e( 'Legend', 'viralpress' )?></option>
                    <option value="boxed"><?php _e( 'Boxed', 'viralpress' )?></option>
                    <option value="inline"><?php _e( 'Inline', 'viralpress' )?></option>
                </select>
                
                <br/><br/>
                <label><?php _e( 'List disaply', 'viralpress' )?></label>
                <select name="list_display" id="list_display" class="vp_post_select vp-form-control">
                	<option value="one"><?php _e( 'One column', 'viralpress' )?></option>
                    <option value="two_l"><?php _e( 'Two column, picture on left', 'viralpress' )?></option>
                    <option value="two_r"><?php _e( 'Two column, picture on right', 'viralpress' )?></option>
                    <option value="two_alt"><?php _e( 'Two column, alternating', 'viralpress' )?></option>
                </select>
                
                <br/><br/>
                <input type="checkbox" name="show_numbers" id="show_numbers" checked="checked"/>&nbsp;&nbsp;
				<label for="show_numbers"><?php _e( 'Show numbering', 'viralpress' )?></label>
                
                <?php if($vp_instance->settings['allow_copy']):?>
                <br/><br/>
                <input type="checkbox" name="allow_copy" id="allow_copy" value="1"/>&nbsp;&nbsp;
                <label for="allow_copy"><?php _e( 'Allow others to copy this list', 'viralpress' )?></label>
                <?php endif;?>
                 <?php if( $attributes['post_type'] != 'quiz' && $attributes['post_type'] != 'poll' ) :?>
                <br/><br/>
                <input type="checkbox" name="open_list" id="open_list" value="1"/>&nbsp;&nbsp;
                <label for="open_list"><?php _e( 'Make this list open', 'viralpress' )?></label>
                <?php endif;?>
                <span class="voting_till_span" style="display:none">
                	<br/><br/>
                    <label for="voting_till"><?php _e( 'Voting Open Till', 'viralpress' )?></label><br/>
                    <input type="text" name="voting_till" id="voting_till" class="vp-form-control" />
                </span>                
                <br/><br/>
                <?php if( $vp_instance->settings['load_recap'] && $vp_instance->settings['recap_post'] ) :?>
                <div class="erecap">
                	<?php echo recaptcha_get_html($vp_instance->settings['recap_key'], '');?>
                </div>
                <br/>
                <?php endif;?>
                <button class="btn btn-info vp_submit_post">
					<i class="glyphicon glyphicon-thumbs-up"></i>&nbsp;&nbsp;
					<?php _e( 'Submit post', 'viralpress' )?>
                </button>
                <span class="vp_submit_post_loader" style="display:none"><img src="<?php echo $vp_instance->settings['IMG_URL']?>/spinner.gif"/></span>
            	<br/><br/>
                <small><?php _e( 'Drag-drop entries to rearrange', 'viralpress' )?></small>
            </fieldset>
            
            <br/>
            <fieldset>
                <h3 class="vp-pointer" onclick="jQuery('.editor_tips').slideToggle()">
                    <?php _e( 'Notes & Tips', 'viralpress' )?> &nbsp;&nbsp;
                    <i class="glyphicon glyphicon-chevron-down" style="font-size:12px !important"></i>
                </h3>
                <div id="vp-carousel" class="editor_tips">
                    <div id="vp-buttons">
                        <button class="btn btn-info btn-xs" id="vp-slides-prev">
                            <i class="glyphicon glyphicon-menu-left"></i>&nbsp;&nbsp;
                            <?php _e( 'Prev', 'viralpress' ) ?>&nbsp;
                        </button>&nbsp;&nbsp;
                        <button class="btn btn-danger btn-xs" id="vp-slides-next">
                            <i class="glyphicon glyphicon-menu-right"></i>&nbsp;&nbsp;
                            <?php _e( 'Next', 'viralpress' ) ?>&nbsp;
                        </button>
                    </div>
                    <br/>
                    <div id="vp-slides"> 
                        <ul>
                            <li><?php _e( 'Drag & drop entries to reposition them.', 'viralpress' )?></li>
                            <li><?php _e( 'Uncheck or check show numbering checkbox to remove or add numbering from list.', 'viralpress' )?></li>
                            <li><?php _e( 'Each Text entry must have description. Title is optional', 'viralpress' )?></li>
                            <li><?php _e( 'Each Image entry must have an image upload. Title and description are optional.', 'viralpress' )?></li>
                            <li><?php _e( 'Video, audio & pins must have and a url embedded. Title and description are optional.', 'viralpress' )?></li>
                            <li><?php _e( 'When you add embed url for video, audio or pin, fields to add title and description will be shown.', 'viralpress' )?></li>
                            <li><?php _e( 'If you do not add any thumbnail to post, one of the images added to the post will be set as thumbnail.', 'viralpress' )?></li>
                            <li><?php _e( 'When you create quiz or poll, each answer & question must have either a textual or visual presentation or both.', 'viralpress' )?></li>
                            <li><?php _e( 'If you want to put caption into an image, use the caption text box  for an image in media gallery.', 'viralpress' )?></li>
                            <li><?php _e( 'Hit SHIFT+ENTER to put a small line break in rich text editor.', 'viralpress' )?></li>
                        </ul>
                    </div>
                </div>
            </fieldset>
            
            <?php if( $attributes['post_type'] == 'quiz' ): ?>
            <br/>
            <fieldset>
            	<h3 class="vp-pointer" onclick="jQuery('.quiz_tips').slideToggle()">
                    <?php _e( 'Quiz Rules', 'viralpress' )?> &nbsp;&nbsp;
                    <i class="glyphicon glyphicon-chevron-down" style="font-size:12px !important"></i>
                </h3>
                <div class="quiz_tips" style="display:none">
                    <h4><?php _e( 'Scoring rules (MCQ/Trivia)', 'viralpress' )?></h4>
                    <ul>
                        <li><?php _e( 'Suitable when one question has only one correct answer', 'viralpress' )?></li>
                        <li><?php _e( 'One point for correct answer', 'viralpress' )?></li>
                        <li><?php _e( 'Zero point for wrong answer', 'viralpress' )?></li>
                        <li><?php _e( 'Use explanation to explain asnwers if needed. MCQ quizzes will show explanation at the end of quiz and trivia will show instantly after answering one question.', 'viralpress' )?></li>
                    </ul>
                    
                    <h4><?php _e( 'Scoring rules (Personality Type 1)', 'viralpress' )?></h4>
                    <ul>
                        <li><?php _e( 'Suitable when you want to analyze users habit, behavior or choice based on their answers i.e analytical type quiz', 'viralpress' )?></li>
                        <li><?php _e( 'Choose results for each answer', 'viralpress' )?></li>
                        <li><?php _e( 'The final results will be made on the count of results the user selected most', 'viralpress' )?></li>
                    </ul>
                    
                    <h4><?php _e( 'Scoring rules (Personality Type 2)', 'viralpress' )?></h4>
                    <ul>
                        <li><?php _e( 'Suitable when you want to analyze users habit, behavior or choice based on their answers i.e analytical type quiz', 'viralpress' )?></li>
                        <li><?php _e( 'When user chooses answer 1, they get one point', 'viralpress' )?></li>
                        <li><?php _e( 'When user chooses answer 2, they get two points', 'viralpress' )?></li>
                        <li><?php _e( 'Similarly when user chooses answer y, they get y points', 'viralpress' )?></li>
                    </ul>
                    
                    <h4><?php _e( 'Score sheet', 'viralpress' )?></h4>
                    <ul>
                        <li><?php _e( 'You can set results based on the score user gets', 'viralpress' )?></li>
                        <li><?php _e( 'For different range of scoring, set different results', 'viralpress' )?></li>
                        <li><?php _e( 'If you do not specify results for a specific range, the last score sheet will be used for that range while scoring', 'viralpress' )?></li>
                        <li><?php _e( 'Points above are not applicable for personality quiz type 1', 'viralpress' )?></li>
                    </ul>
                </div>
            </fieldset>
            <?php endif;?>
        </div>
    </div>
</form>

<?php echo get_template_html('modals')?>
<?php echo get_template_html('sdks')?>
<script>
	jQuery('.entry-title').eq(0).hide();
	normalize_editor_page();
	<?php if( empty( $edit_post_id ) && empty( $_POST['vp_add_new_post'] ) ) {?>
	jQuery('.editor_loader').hide();
	jQuery('#add_new_post_form').show();
	<?php }?>
</script>