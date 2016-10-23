<div class="modal fade ts-send-tofriend" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content container">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php esc_html_e('Send to friends', 'videofly'); ?></h4>
            </div>
            <div class="modal-body row">
            <div class="col-lg-6">
                <label><?php esc_html_e('Name', 'videofly'); ?></label>
                <input name="ts-name" type="text" value="" placeholder="<?php esc_html_e('Name', 'videofly') ?>">
            </div>
            <div class="col-lg-6">
                <label><?php esc_html_e('Email', 'videofly'); ?></label>
                <input name="ts-email" type="text" value="" placeholder="<?php esc_html_e('Email', 'videofly') ?>">
            </div>
            <div class="col-lg-12">
                <label><?php esc_html_e('Message', 'videofly'); ?></label>
                <textarea name="ts-message"><?php the_permalink(); ?></textarea>
                <div class="vdf-response"></div>
            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php esc_html_e('Close', 'videofly'); ?></button>
                <button type="button" class="btn btn-primary ts-send-embed"><?php esc_html_e('Send', 'videofly'); ?></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade ts-embed-code" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php esc_html_e('Embed code', 'videofly'); ?></h4>
            </div>
            <div class="modal-body">
                <textarea><?php echo esc_url( get_home_url() . '/embed/' . get_the_ID() ); ?></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php esc_html_e('Close', 'videofly'); ?></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade ts-link-code" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php esc_html_e('Video link', 'videofly'); ?></h4>
            </div>
            <div class="modal-body">
                <textarea><?php the_permalink(); ?></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php esc_html_e('Close', 'videofly'); ?></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade ts-modal-playlists" tabindex="-1" role="dialog">
  	<div class="modal-dialog" role="document">
   	 	<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
        		<h4 class="modal-title"><?php esc_html_e('Add to playlist', 'videofly'); ?></h4>
      		</div>
      		<div class="modal-body">
				<?php
                    $videoMeta = get_post_meta($post->ID, 'ts-video', true);
                    echo vdf_getPlaylist($videoMeta);
                ?>
      		</div>
	      	<div class="modal-footer">
	        	<button type="button" class="btn btn-default" data-dismiss="modal"><?php esc_html_e('Close', 'videofly'); ?></button>
	      	</div>
    	</div>
  	</div>
</div>