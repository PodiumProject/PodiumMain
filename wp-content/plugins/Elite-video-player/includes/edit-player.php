<div class='wrap'>
	<div id='elite_player_admin'>
		<a href="admin.php?page=elite_player_admin" class="back-to-list-link">&larr; <?php _e('Back to players list', 'player'); ?></a>
		<h2 id="edit-player-text">Edit player
		<?php
			if (isset($_GET['playerId']) && $_GET['playerId'] > -1) {
				echo ' ' . $_GET['playerId'];
			}
		?>
		</h2>
		<form method="post" enctype="multipart/form-data" action="admin.php?page=elite_player_admin&action=save_settings&playerId=<?php _e($current_id)?>">
			<p class="submit"><input type="submit" name="submit" id="submit" class="button save-button button-primary" value="Save Changes"></p>
			<div class="metabox-holder">
				<!--<div class="meta-box-sortables ui-sortable">-->
				<div class="meta-box-sortables">
				
					<div class="btns">
						<div class="btn1">
							<h3 class="hndle">
								<span id="btn1-title">Player settings</span>
							</h3>
						</div>
						<div class="btn2">
							<h3 class="hndle">
								<span id="btn2-title">Videos</span>
							</h3>
						</div>
					</div>
				
					<div class="column-left">
					
						<div class="options_general" style="display:none">
							<div class="postbox closed">
								<div class="handlediv" title="Click to toggle"></div>
								<h3 class="hndle">
									<span id="sortable-title">General settings</span>
								</h3>
								<div class="inside">
									<table class="form-table" id="player-options-general">
										<tbody/>
									</table>
								</div>
							</div>
							
							<div class="postbox closed">
								<div class="handlediv" title="Click to toggle"></div>
								<h3 class="hndle">
									<span id="sortable-title">Functionality</span>
								</h3>
								<div class="inside">
									<table class="form-table" id="player-options-behavior">
										<tbody/>
									</table>
								</div>
							</div>
							
							<div class="postbox closed">
								<div class="handlediv" title="Click to toggle"></div>
								<h3 class="hndle">
									<span id="sortable-title">Playlist settings</span>
								</h3>
								<div class="inside">
									<table class="form-table" id="player-options-playlist">
										<tbody/>
									</table>
								</div>
							</div>
							
							<div class="postbox closed">
								<div class="handlediv" title="Click to toggle"></div>
								<h3 class="hndle">
									<span id="sortable-title">HTML5 player</span>
								</h3>
								<div class="inside">
									<table class="form-table" id="player-options-HTML5">
										<tbody/>
									</table>
								</div>
							</div>
							
							<div class="postbox closed">
								<div class="handlediv" title="Click to toggle"></div>
								<h3 class="hndle">
									<span id="sortable-title">YouTube player</span>
								</h3>
								<div class="inside">
									<table class="form-table" id="player-options-youtube">
										<tbody/>
									</table>
								</div>
							</div>
							
							<div class="postbox closed">
								<div class="handlediv" title="Click to toggle"></div>
								<h3 class="hndle">
									<span id="sortable-title">Vimeo player</span>
								</h3>
								<div class="inside">
									<table class="form-table" id="player-options-vimeo">
										<tbody/>
									</table>
								</div>
							</div>
						</div>
						
						<div class="options_videos" style="display:none">
							<div class="postbox closed">
								<div class="handlediv" title="Click to toggle"></div>
								<h3 class="hndle">
									<span id="sortable-title">Videos</span>
								</h3>
								<div class="inside">
									<div class="ui-sortable videos-body">
										<div id="videos-container" class="ui-sortable videos-container">
											<table class="form-table" id="player-options-table-right">
												<tbody>
														
												</tbody>
											</table>
										</div>
										<div><a id="add-new-video-button" class="alignleft button-primary " href='#'>Add New Video</a></div>
									</div>
								</div>
							</div>
						</div>

					</div>
					
					
							
					<div class="column-right">
					
						<!--<div class="postbox closed">
							<div class="handlediv" title="Click to toggle"></div>
							<h3 class="hndle">
								<span id="sortable-title">Videos</span>
							</h3>
							<div class="inside">
								<div>
									<div class="ui-sortable sortable-videos-body">
										<div id="videos-container" class="ui-sortable sortable-videos-container">
												<table class="form-table" id="player-options-table-right">
													<tbody/>
												</table>
										</div>
										<div><a id="add-new-video-button" class="alignleft button-primary " href='#'>Add New Video</a></div>
									</div>
								</div>
							</div>
						</div>-->
						
					</div>
					
				</div>
			</div>
			<p class="submit"><input type="submit" name="submit" id="submit" class="button save-button button-primary" value="Save Changes"></p>
		</form>
	</div>
</div>
<?php 
wp_enqueue_media();
wp_enqueue_script("readvideo_player_admin", plugins_url()."/Elite-video-player/js/plugin_admin.min.js", array('jquery','jquery-ui-sortable','jquery-ui-resizable','jquery-ui-selectable','jquery-ui-tabs' ),ELITE_PLAYER_VERSION);
wp_enqueue_style( 'readvideo_player_admin_css', plugins_url()."/Elite-video-player/css/player-admin.css",array(), ELITE_PLAYER_VERSION );
wp_enqueue_style( 'jquery-ui-style', plugins_url()."/Elite-video-player/css/jquery-ui.css",array(), ELITE_PLAYER_VERSION );
//pass $players to javascript
wp_localize_script( 'readvideo_player_admin', 'options', json_encode($elite_players[$current_id]) );

// echo json_encode($elite_players[$current_id]); 