<?php
/*
Template Name: Frontend - Edit post
*/
if( !is_user_logged_in() ){
	wp_redirect(home_url());
	exit;
}
$post_id = (isset($_GET, $_GET['id']) && (int)$_GET['id'] !== 0 && (int)$_GET['id'] > 0) ? (int)$_GET['id'] : NULL;

if( !isset($post_id) ) wp_redirect(home_url());

get_header();
$user = wp_get_current_user();

$insert_post_type = get_option('videotouch_general');
$statusPost = (isset($insert_post_type['post_publish_user']) && ($insert_post_type['post_publish_user'] == 'pending' || $insert_post_type['post_publish_user'] == 'publish')) ? $insert_post_type['post_publish_user'] : 'pending';

	if( isset($post_id) ){
		$post = get_post($post_id, OBJECT);
		
		if( isset($post) && !empty($post) && is_object($post) ){
			$title   = (isset($post->post_title) && !empty($post->post_title) && is_string($post->post_title)) ? esc_attr($post->post_title) : _e('No title', 'touchsize');
			$content = (isset($post->post_content) && !empty($post->post_content) && is_string($post->post_content)) ? $post->post_content : _e('No content', 'touchsize');
			$post_type = (isset($post->post_type) && !empty($post->post_type) && is_string($post->post_type) && $post->post_type === 'video') ? 'v' : 'p';
			$tags_base = get_the_tags($post_id);
			$tags = '';
			
			if( isset($tags_base) && !empty($tags_base) && is_array($tags_base) ){
				foreach($tags_base as $tag){
					$tags .= $tag->name . ', ';
				}
			}

			if( $post_type === 'v' ){
				$categories_extract = wp_get_post_terms($post_id, 'videos_categories');
				$categories_base = array();

				foreach($categories_extract as $terms_id){
					$categories_base[] = $terms_id->term_id;
				}
			}else{
				$categories_base = wp_get_post_categories($post_id); ;
			}
			
			if( $post_type === 'v' ){
				$video = get_post_meta($post_id, 'video', TRUE);
				if( isset($video, $video['extern_url'], $video['your_url']) ){
					if( !empty($video['extern_url']) ) $video_extern_url = $video['extern_url'];
					if( !empty($video['your_url']) ) $video_your_url = $video['your_url'];
				}
			}
		?>
<section id="main" class="user-edit-post-page">
	<div class="row">
		<div class="container">
			<div class="row">
				<div class="col-sm-6 col-md-8">
					<form method="post" enctype="multipart/form-data">
					  	<div class="form-group">
					        <label for="ts-title"><?php _e('Your Title', 'touchsize'); ?></label>
					        <input class="form-control" type="text" name="ts-title-post" value="<?php echo $title; ?>"/>
					    </div>
					    <div class="form-group">
					        <label for="ts-editor"><?php _e('Your Content', 'touchsize'); ?></label>
					        <?php wp_editor($content, 'ts-post-editor', array('textarea_name' => 'ts-post-content', 'quicktags' => 0, 'media_buttons' => 0, 'teeny' => true, 'tinymce' => true)); ?>
					    </div>
				    	<?php
				    	$settings = array();
				    	if( $post_type == 'v' ){
				    		$settings = array('hide_empty' => 0, 'taxonomy' => 'videos_categories');
				    	}elseif ($post_type === 'p') {
				    		$settings = array('hide_empty' => 0, 'show_option_all' => '');
				    	}else{
				    		$settings = array();
				    	}
				    	
				    	if( !empty($settings) ) :
				    		$categories = get_categories($settings);
				    		if ( isset($categories) && is_array($categories) && !empty($categories) ) : ?>
				    		    <div class="form-group">
				    		    	<label for="ts-post-type"><?php _e('Change category', 'touchsize'); ?></label>
				    			    <div class="ts-category-video">
				    			    	<select name="ts-category-<?php if( $post_type === 'v' ) echo 'video'; if( $post_type === 'p' ) echo 'post'; ?>">
				                            <?php  foreach ($categories as $category) : $selected = ''; ?>
				                                <?php if( is_object($category) ) :  ?>
				                                	<?php if( is_array($categories_base) && !empty($categories_base) ) :
				                                		foreach($categories_base as $category_base){
				                                			if( $category->term_id == $category_base ){
				                                				$selected = ' selected="selected"';
				                                			}
				                                		}
				                                	endif; ?>
				                                    <option<?php echo $selected; ?> value="<?php echo $category->term_id; ?>"><?php echo $category->cat_name; ?></option>
				                                <?php endif; ?>
				                            <?php endforeach ?>                                    
				    	                </select>
				    			    </div>
				    		    </div>
				    		<?php endif; ?>
				    	<?php endif; ?>
					    <div class="form-group">
					        <label for="ts-tags"><?php _e('Tags', 'touchsize'); ?></label>
					        <input class="form-control" type="text" name="ts-tags" id="ts-tags" value="<?php echo $tags; ?>"/>
					    </div>
					    <div class="form-group well">
					        <label for="ts-upload-post"><?php _e('Image', 'touchsize'); ?></label>
					        <input type="file" name="ts-upload-post" id="ts-upload-post" />
					    </div>
					    <?php if( $post_type === 'v' ) : ?>
					    	<div class="form-group well" id="ts-upload-video">
					    	    <ul class="nav nav-tabs" role="tablist" id="myTab">
					    		    <li class="active"><a href="#y_video" data-video-type="upload" role="tab" data-toggle="tab"><?php _e('Your video', 'touchsize'); ?></a></li>
					    		    <li><a href="#e_video" data-video-type="url" role="tab" data-toggle="tab"><?php _e('Link url', 'touchsize'); ?></a></li>
					    	    </ul>

					    	    <div class="tab-content">
					    	      	<div class="tab-pane active" id="y_video">
					    	      		<label for="ts-upload-video-input"><?php _e('Video', 'touchsize'); ?></label>
					    	        	<input type="file" name="ts-upload-video" id="ts-upload-video-input" />
					    	      	</div>
					    	       	<div class="tab-pane" id="e_video">
					    	       		<div class="form-group" id="ts-url-video">
					    	       		    <label for="ts-url-video"><?php _e('Url video', 'touchsize'); ?></label>
					    	       		    <input type="text" class="form-control" name="ts-url-video" id="ts-url-video" value="<?php if(isset($video_extern_url)) echo $video_extern_url; ?>"/>
					    	       		</div>
					    	       	</div>
					    	       	<input type="hidden" name="ts-video-type" value="upload" id="ts-video-type"/>
					    	    </div>
					    	</div>
						<?php endif; ?>
					    <?php wp_nonce_field('ts_save_post', 'ts_save_post'); ?>
					    <input type="hidden" value="<?php echo $post_id; ?>" name="ts-post-id">
					    <input type="hidden" value="<?php echo $post_type; ?>" name="ts-post-type">
					    <input type="submit" name="save-posts" class="btn btn-primary active medium" value="<?php _e('Save post', 'touchsize') ?>" />
					</form>
				</div>
				<?php if( $statusPost == 'pending' ) : ?>
					<div style="height:60px"></div>
					<div class="post-pending">
						<?php _e('The post is in pending until an Administrator will approve it.', 'touchsize'); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<?php   }
	}

get_footer();
?>
