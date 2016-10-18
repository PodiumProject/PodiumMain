<?php 
/*
Template Name: Frontend - Add post
*/
if( !is_user_logged_in()){
	wp_redirect(home_url());
	exit;
} 

get_header();
$insert_post_type = get_option('videotouch_general');
$statusPost = (isset($insert_post_type['post_publish_user']) && ($insert_post_type['post_publish_user'] == 'pending' || $insert_post_type['post_publish_user'] == 'publish')) ? $insert_post_type['post_publish_user'] : 'pending';
$insert_post_type = $insert_post_type['insert_post_user'];

?>
<section id="main" class="user-add-post-page">
	<div class="row">
		<div class="container">
			<div class="row">
				<div class="col-sm-6 col-md-8">
					<form method="post" enctype="multipart/form-data">
					  	<div class="form-group">
					        <label for="ts-title"><?php _e('Add your title', 'touchsize'); ?></label>
					        <input type="text" class="form-control" name="ts-title-post" placeholder="<?php _e('Write your title here', 'touchsize'); ?>" value=""/>
					    </div>
					    <div class="form-group">
					        <label for="ts-editor"><?php _e('Add content text', 'touchsize'); ?></label>
					        <?php wp_editor('', 'ts-post-editor', array('textarea_name' => 'ts-post-content', 'quicktags' => 0, 'media_buttons' => 0, 'teeny' => true, 'tinymce' => true)); ?>
					    </div>
					    <div class="form-group">
					    	<label for="ts-post-type"><?php _e('Chose post type', 'touchsize'); ?></label>
						    <select name="ts-post-type" style="display:block">
						    	<?php if( $insert_post_type == 'post' || $insert_post_type == 'post-video' ) : ?>
							  		<option value="p"><?php _e('Posts', 'touchsize'); ?></option>
							  	<?php endif; ?>
							  	<?php if( $insert_post_type == 'video' || $insert_post_type == 'post-video' ) : ?>
							  		<option value="v"><?php _e('Videos', 'touchsize'); ?></option>
							  	<?php endif; ?>
							</select>
						</div>
					    <div class="form-group">
				    		<label for="ts-post-type"><?php _e('Chose your category', 'touchsize'); ?></label>
				    		<?php


				    		$args = array(
		    					'show_option_none' => __('Select category', 'videotouch'),
		    					'show_count'       => 0,
		    					'orderby'          => 'name',
		    					'echo'             => 0,
		    					'hide_empty'       => 0,
		    					'hierarchical'     => 1,
		    					'name'             => 'ts-category-post'
		    				);
				    		?>
				    		<?php if( $insert_post_type == 'post' || $insert_post_type == 'post-video' ) : ?>
						    	<div class="ts-category-post">
							    	<?php echo wp_dropdown_categories($args) ?>
								</div>
							<?php endif; ?>
							<?php if( $insert_post_type == 'video' || $insert_post_type == 'post-video' ) : ?>
							    <div class="ts-category-video">
							    	<?php
							    	$args['taxonomy'] = 'videos_categories';
							    	$args['name'] = 'ts-category-video';

							    	echo wp_dropdown_categories($args);
							    	?>
							    </div>
							<?php endif; ?>
					    </div>
						<div class="form-group">
					        <label for="ts-tags"><?php _e('Add tags', 'touchsize'); ?></label>
					        <input type="text" class="form-control" id="ts-tags" name="ts-tags" placeholder="<?php _e('Add tags separated by commas ex(tag1, tag2, tagN)', 'touchsize'); ?>" value="" />
					    </div>
					    <div class="form-group well" id="ts-upload-image">
					        <label for="ts-upload-post"><?php _e('Set as featured image', 'touchsize'); ?></label>
					        <input type="file" name="ts-upload-post" id="ts-upload-post" />
					        <span class="help-block"><?php _e('Please use .jpg, .png files for your feature image', 'touchsize'); ?></span>
					    </div>
					    <?php if( $insert_post_type == 'video' || $insert_post_type == 'post-video' ) : ?>
							<div class="form-group well" id="<?php if( $insert_post_type == 'post-video' ) echo 'ts-upload-video'; ?>">
							    <ul class="nav nav-tabs" role="tablist" id="myTab">
								    <li class="active"><a href="#y_video" data-video-type="upload" role="tab" data-toggle="tab"><?php _e('Your video', 'touchsize'); ?></a></li>
								    <li><a href="#e_video" data-video-type="url" role="tab" data-toggle="tab"><?php _e('Link url', 'touchsize'); ?></a></li>
							    </ul>
							    <div class="tab-content">
							      	<div class="tab-pane active" id="y_video">
							      		<label for="ts-upload-video-input"><?php _e('Chose file to upload', 'touchsize'); ?></label>
							        	<input type="file" name="ts-upload-video" id="ts-upload-video-input" />
							        	<span class="help-block"><?php _e('Please use .mp4 files for your video', 'touchsize'); ?></span>
							      	</div>
							       	<div class="tab-pane" id="e_video">
							       		<div class="form-group" id="ts-url-video">
							       		    <label for="ts-url-video"><?php _e('Url video', 'touchsize'); ?></label>
							       		    <input type="text" class="form-control" name="ts-url-video" id="ts-url-video" value=""/>
							       		</div>
							       	</div>
							       	<input type="hidden" name="ts-video-type" value="upload" id="ts-video-type"/>
							    </div>
							</div>
						<?php endif; ?>
					    <?php wp_nonce_field('ts_save_post', 'ts_save_post'); ?>
					    <input type="submit" name="save-posts" class="btn btn-primary active medium" value="<?php _e('Add new post', 'touchsize') ?>" />
					</form>
				</div>
				
				<div class="col-sm-5 col-sm-offset-1 col-md-4 col-md-offset-0">
					<?php if( $statusPost == 'pending' ) : ?>
						<div class="post-pending">
							<?php _e('The post will be pending until an Administrator will approve it.', 'touchsize'); ?>
						</div>
						<div style="height:60px"></div>
					<?php endif; ?>
					<div class="alert alert-warning">
						<?php 
							$text = get_option('videotouch_single_post', array('text-user' => ''));
							if(isset($text['text-user'])) echo apply_filters('the_content', $text['text-user']);	
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php get_footer(); ?>