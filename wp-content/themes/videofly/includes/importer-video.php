<?php

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if( !is_plugin_active('ts-custom-posts/ts-custom-posts.php') ) return;

add_action('admin_menu', 'vdfRegisterImporterSubpage');

function vdfRegisterImporterSubpage() {
	add_theme_page( esc_html__('Import video page', 'videofly'), esc_html__('Import videos', 'videofly'), 'manage_options', 'ts-video-importer', array('TsImportVideoYoutube', 'vdfRenderSubpage'));
}

add_action( 'admin_init', array('TsImportVideoYoutube', 'vdfAdmin_init'));

class TsImportVideoYoutube
{
	static $nextPageToken, $prevPageToken;

	static function vdfRenderSubpage(){

		$settings = get_option('importer-settings');
		$key = isset($settings['key-api']) ? $settings['key-api'] : '';

		$settings['key-api'] = isset($_POST['key-api']) ? $_POST['key-api'] : $key;
		$paramsUrl = '';

		if( isset($_POST['ts-video-get']) ){
			$paramsUrl = $_POST;
		}else if( isset($_POST['ts-next']) || isset($_POST['ts-prev']) ){
			$paramsUrl = unserialize(ts_enc_string($_POST['ts-params-url'], 'decode'));
			if( isset($_POST['ts-next']) ){
				$paramsUrl['pageToken'] = $paramsUrl['nextPageToken'];
			}else{
				$paramsUrl['pageToken'] = $paramsUrl['prevPageToken'];
			}
		}

		if( isset($_POST['ts-import-videos']) ){
			$videos = unserialize(ts_enc_string($_POST['ts-get-after-import'], 'decode'));
			$imported = isset($settings['imported']) && is_array($settings['imported']) ? $settings['imported'] : array();
			$settings = self::vdfImportVideoPosts($settings, $imported);

			$paramsUrl = unserialize(ts_enc_string($_POST['ts-params-url'], 'decode'));

			self::$nextPageToken = isset($paramsUrl['nextPageToken']) ? $paramsUrl['nextPageToken'] : '';
			self::$prevPageToken = isset($paramsUrl['prevPageToken']) ? $paramsUrl['prevPageToken'] : '';
		}else{
			$videos = !empty($paramsUrl) ? self::vdf_getVideos($paramsUrl) : '';
		}

		update_option('importer-settings', $settings);

		self::vdfQueryForm($paramsUrl, $key);
		self::vdfFormListVideos($videos, $paramsUrl, (isset($settings['imported']) ? $settings['imported'] : array()));
	}

	static function vdfQueryForm($paramsUrl, $key){
		$defaults = array(
			'feed' => 'query',
			'key-api' => '',
			'count' => '25',
			'userID' => '',
			'playlistID' => '',
			'duration' => 'any',
			'query' => '',
			'order' => 'viewCount'
		);
		$paramsUrl = wp_parse_args($paramsUrl, $defaults);
		?>	
		<form method="post" class="ts-video-import">
			<div class="theme-name">
				<h3>TouchSize</h3>
				<h3>Video Import</h3>
			</div>			
			<div>
				<label for="ts-keyapi"><?php esc_html_e('Insert your api key', 'videofly') ?>:</label>
				<input type="text" name="key-api" value="<?php echo vdf_var_sanitize($key) ?>" id="ts-keyapi">
				<span>
					<?php esc_html_e('To get your YouTube API key, visit this address: https://code.google.com/apis/console. After signing in, visit Services and enable YouTube Data API. To get your API key, visit API Access and copy an API key from the screen and enter it above.', 'videofly'); ?>
				</span>
			</div>
			<div>
				<label for="ts-feed"><?php esc_html_e('Select the type of feed you want to load.', 'videofly') ?>: </label>
				<select name="feed" id="ts-feed">
					<option<?php selected($paramsUrl['feed'], 'user'); ?> value="user"><?php esc_html_e('User feed', 'videofly'); ?></option>
					<option<?php selected($paramsUrl['feed'], 'playlist'); ?> value="playlist"><?php esc_html_e('Playlist feed', 'videofly'); ?></option>
					<option<?php selected($paramsUrl['feed'], 'query'); ?> value="query"><?php esc_html_e('Search query feed', 'videofly'); ?></option>
				</select>
			</div>
			<div>
				<label for="ts-count"><?php esc_html_e('Number of videos to retrieve', 'videofly') ?>:</label>
				<input type="number" name="count" value="<?php echo vdf_var_sanitize($paramsUrl['count']) ?>" id="ts-count">
				<span><?php esc_html_e('This parameter specifies the maximum number of items that should be returned in the result set. Acceptable values are 0 to 50, inclusive', 'videofly') ?></span>
			</div>
			<div>
				<label for="ts-user-id"><?php esc_html_e('YouTube user ID', 'videofly') ?>:</label>
				<input type="text" name="userID" value="<?php echo vdf_var_sanitize($paramsUrl['userID']) ?>" id="ts-user-id">
			</div>
			<div>
				<label for="ts-playlist-id"><?php esc_html_e('YouTube playlist ID', 'videofly') ?>:</label>
				<input type="text" name="playlistID" value="<?php echo vdf_var_sanitize($paramsUrl['playlistID']) ?>" id="ts-playlist-id">
			</div>
			<div>
				<label for="ts-duration"><?php esc_html_e('Video duration', 'videofly') ?>:</label>
				<select name="duration" id="ts-duration">
					<option<?php selected($paramsUrl['duration'], 'any'); ?> value="any"><?php esc_html_e('Any', 'videofly') ?></option>
					<option<?php selected($paramsUrl['duration'], 'short'); ?> value="short"><?php esc_html_e('Short (under 4min.)', 'videofly') ?></option>
					<option<?php selected($paramsUrl['duration'], 'medium'); ?> value="medium"><?php esc_html_e('Medium (between 4 and 20min.)', 'videofly') ?></option>
					<option<?php selected($paramsUrl['duration'], 'long'); ?> value="long"><?php esc_html_e('Long (over 20min.)', 'videofly'); ?></option>
				</select>
			</div>
			<div>
				<label for="ts-query"><?php esc_html_e('Search query', 'videofly') ?>:</label>
				<input type="text" name="query" value="<?php echo vdf_var_sanitize($paramsUrl['query']) ?>" id="ts-query">
				<span><?php esc_html_e('Enter playlist ID, user ID or search query according to Feed Type selection.', 'videofly'); ?></span>
			</div>
			<div>
				<label for="ts-order"><?php esc_html_e('Order by', 'videofly') ?>:</label>
				<select name="order" id="ts-order">
					<option<?php selected($paramsUrl['order'], 'date'); ?> value="date"><?php esc_html_e('Date', 'videofly') ?></option>
					<option<?php selected($paramsUrl['order'], 'rating'); ?> value="rating"><?php esc_html_e('Rating', 'videofly') ?></option>
					<option<?php selected($paramsUrl['order'], 'relevance'); ?> value="relevance"><?php esc_html_e('Search relevance', 'videofly') ?></option>
					<option<?php selected($paramsUrl['order'], 'title'); ?> value="title"><?php esc_html_e('Video title', 'videofly') ?></option>
					<option<?php selected($paramsUrl['order'], 'viewCount'); ?> value="viewCount"><?php esc_html_e('Number of views', 'videofly') ?></option>
				</select>
			</div>
			<input type="submit" name="ts-video-get" value="<?php esc_html_e('Load', 'videofly') ?>">
		</form>
		<?php
	}

	static function vdf_getVideos($settings){

		$feed = isset($settings['feed']) ? $settings['feed'] : 'query';
		$keyApi = trim($settings['key-api']);
		$duration = isset($settings['duration']) ? $settings['duration'] : 'any';

		switch( $feed ){
			case 'query':
				$url = 'https://www.googleapis.com/youtube/v3/search?part=snippet&type=video&q='. urlencode(sanitize_text_field($settings['query'])) .'&order='. $settings['order'] .'&videoDuration='. $duration;
			break;
			case 'user':
				$url = 'https://www.googleapis.com/youtube/v3/channels?part=contentDetails&forUsername='. sanitize_text_field($settings['userID']);
			break;
			default:
				$url = 'https://www.googleapis.com/youtube/v3/playlistItems?part=contentDetails&playlistId='. sanitize_text_field($settings['playlistID']);
			break;
		}

		$pageToken = isset($settings['pageToken']) && !empty($settings['pageToken']) ? '&pageToken='. $settings['pageToken'] : '';
		$count = isset($settings['count']) && is_numeric($settings['count']) ? intval($settings['count']) : 25;

		$url .= '&maxResults='. $count .'&key='. $keyApi . $pageToken;

		$response = self::vdfGetCleanResponse($url);

		if( empty($response) ) return;

		self::$nextPageToken = isset($response['nextPageToken']) ? $response['nextPageToken'] : '';
		self::$prevPageToken = isset($response['prevPageToken']) ? $response['prevPageToken'] : '';

		if( $feed == 'query' || $feed == 'playlist' ){

			$response = self::vdfGetVideoByIds($response, $keyApi);

			$response['categories'] = self::vdfGetCategories($response, $keyApi);

		}else{

			$channelId = isset($response['items'][0]['contentDetails']['relatedPlaylists']['uploads']) ? $response['items'][0]['contentDetails']['relatedPlaylists']['uploads'] : '';

			$url = 'https://www.googleapis.com/youtube/v3/playlistItems?part=contentDetails&maxResults='. $count .'&playlistId='. $channelId .'&key='. $keyApi . $pageToken;

			$response = self::vdfGetCleanResponse($url);

			if( empty($response) ) return;

			self::$nextPageToken = isset($response['nextPageToken']) ? $response['nextPageToken'] : '';
			self::$prevPageToken = isset($response['prevPageToken']) ? $response['prevPageToken'] : '';

			$response = self::vdfGetVideoByIds($response, $keyApi);

			$response['categories'] = self::vdfGetCategories($response, $keyApi);
		}

		return $response;
	}

	static function imp_covtime($youtube_time){
	    $start = new DateTime('@0');
	    $start->add(new DateInterval($youtube_time));
	    return $start->format('H:i:s');
	}

	static function vdf_setPostImage($imgUrl, $postID, $imgName, $title){

		global $wp_filesystem;

	    if( empty($wp_filesystem) ) {
	    	require_once( ABSPATH .'/wp-admin/includes/file.php' );
	    	WP_Filesystem();
	    }

		// Add Featured Image to Post
		$upload_dir = wp_upload_dir(); // Set upload folder
		$image_data = $wp_filesystem->get_contents($imgUrl); // Get image data

		$filename   = wp_basename($imgUrl); // Create image file name
		// Check image file type
		$wp_filetype = wp_check_filetype($filename, null);

		// Check folder permission and define file location
		if( wp_mkdir_p( $upload_dir['path'] ) ) {
		    $file = $upload_dir['path'] .'/'. sanitize_file_name($imgName) .'.'. $wp_filetype['ext'];
		} else {
		    $file = $upload_dir['basedir'] .'/'. sanitize_file_name($imgName) .'.'. $wp_filetype['ext'];
		}

		// Create the image  file on the server
		$wp_filesystem->put_contents($file, $image_data);

		// Set attachment data
		$attachment = array(
		    'post_mime_type' => $wp_filetype['type'],
		    'post_title'     => sanitize_file_name($title),
		    'post_content'   => '',
		    'post_status'    => 'inherit'
		);

		// Create the attachment
		$attach_id = wp_insert_attachment($attachment, $file);

		// Include image.php
		require_once(ABSPATH . 'wp-admin/includes/image.php');

		// Define attachment metadata
		$attach_data = wp_generate_attachment_metadata( $attach_id, $file );

		// Assign metadata to attachment
		wp_update_attachment_metadata( $attach_id, $attach_data );

		// And finally assign featured image to post
		set_post_thumbnail( $postID, $attach_id );
	}

	static function vdfGetCategories($response, $keyApi){

		$categoryIds = '';
		$count = count($response['items']);

		if( $count < 0 ) return;

		foreach( $response['items'] as $key => $video ){
			$categoryIds .= $video['snippet']['categoryId'] . ($key + 1 == $count ? '' : ',');
		}

		$url = 'https://www.googleapis.com/youtube/v3/videoCategories?part=snippet&id='. $categoryIds .'&key='. $keyApi;

		$catResponse = self::vdfGetCleanResponse($url);

		if( empty($catResponse) ) return;

		$count = count($catResponse['items']);
		$categories = array();

		foreach( $catResponse['items'] as $key => $category ){
			$categories[$category['id']]['title'] = $category['snippet']['title'];
			$categories[$category['id']]['channelId'] = $category['snippet']['channelId'];
		}

		return $categories;
	}

	static function vdfGetVideoByIds($response, $keyApi){

		$count = count($response['items']);
		$videoIds = '';

		foreach( $response['items'] as $key => $video ){
			$videoIds .= (isset($video['contentDetails']['videoId']) ?  $video['contentDetails']['videoId'] : $video['id']['videoId']) . ($key + 1 == $count ? '' : ',');
		}

		$url = 'https://www.googleapis.com/youtube/v3/videos?part=snippet,statistics,contentDetails&key='. $keyApi .'&id='. $videoIds;

		return self::vdfGetCleanResponse($url);
	}

	static function vdfGetCleanResponse($url){

		$response = wp_remote_get($url);

		if( is_wp_error($response) ) return;

		$status = wp_remote_retrieve_response_code($response);

		if( $status !== 200 ) return;

		$response = json_decode(wp_remote_retrieve_body($response), true);

		return $response;
	}

	static function vdfImportVideoPosts($settings, $imported){

		$videos = isset($_POST['videos']) && !empty($_POST['videos']) ? $_POST['videos'] : array();
		$categories = unserialize(ts_enc_string($_POST['categories'], 'decode'));
		$decodedVideos = array();

		foreach( $videos as $key => $video ){
			$decoded = unserialize(ts_enc_string($video, 'decode'));
			if( !in_array($decoded['id'], $imported) ){
				$imported[] = $decoded['id'];
				$decodedVideos[] = $decoded;
			}else{
				unset($videos[$key]);
			}
		}

		$settings['imported'] = $imported;

		foreach( $decodedVideos as $video ){

			$insert = array(
				'post_title'   => (isset($video['snippet']['title']) ? $video['snippet']['title'] : 'No title'),
				'post_content' => (isset($video['snippet']['description']) ? $video['snippet']['title'] : ''),
				'post_status'  => 'publish',
				'post_type'    => 'video',
				'tags_input'   => (isset($video['snippet']['tags']) ? $video['snippet']['tags'] : ''),
			);

			$postID = wp_insert_post($insert, true);

			if( is_wp_error($postID) ){
				echo vdf_var_sanitize($postID->get_error_message());
				continue;
			};

			$postMeta = array(
				'duration'   => self::imp_covtime($video['contentDetails']['duration']),
				'video'      => 'https://www.youtube.com/watch?v='. $video['id'],
				'type'       => 'url'
			);

			add_post_meta($postID, 'ts-video', $postMeta);

			$tubeCategoryID = $video['snippet']['categoryId'];
			$tubeCategoryName = isset($categories[$tubeCategoryID]['title']) ? $categories[$tubeCategoryID]['title'] : 'Uncategorised';

			$existTerm = term_exists($tubeCategoryName, 'videos_categories');

			$termID = isset($existTerm['term_id']) ? $existTerm['term_id'] : '';

			if( empty($termID) ){
				$termID = wp_insert_term($tubeCategoryName, 'videos_categories');
				$termID = $termID['term_id'];
			}

			wp_set_post_terms($postID, $termID, 'videos_categories');

			$url = end($video['snippet']['thumbnails']);

			self::vdf_setPostImage($url['url'], $postID, $video['id'], $video['snippet']['title']);
		}

		return $settings;
	}

	static function vdfGetPagination($paramsUrl){

		if( (empty(self::$prevPageToken) && empty(self::$nextPageToken)) ) return;

		$prevPage = ''; $nextPage = '';

		$return = array('html' => '', 'paramsUrl' => '');

		if( !empty(self::$prevPageToken) ){
			$paramsUrl['prevPageToken'] = !empty(self::$prevPageToken) ? self::$prevPageToken : '';
			$prevPage =
				'<li class="vdf-import-pagination">
					<form method="post">
						<input type="hidden" value="'. ts_enc_string(serialize($paramsUrl)) .'" name="ts-params-url">
						<input type="submit" name="ts-prev" value="'. esc_html__('Previous videos', 'videofly') .'">
					</form>
				</li>';
		}

		if( !empty(self::$nextPageToken) ){
			$paramsUrl['nextPageToken'] = !empty(self::$nextPageToken) ? self::$nextPageToken : '';
			$nextPage =
				'<li class="vdf-import-pagination">
					<form method="post">
						<input type="hidden" value="'. ts_enc_string(serialize($paramsUrl)) .'" name="ts-params-url">
						<input type="submit" name="ts-next" value="'. esc_html__('Next videos', 'videofly') .'">
					</form>
				</li>';
		}

		$return['html'] =
			'<ul class="vdf-import-pagination">'.
				$prevPage . $nextPage .'
			</ul>';

		$return['paramsUrl'] = $paramsUrl;

		return $return;
	}

	static function vdfFormListVideos($videos, $paramsUrl, $imported){
		echo self::vdfGetPagination($paramsUrl)['html']; ?>
		<form method="post">
			<?php if( isset($videos['items']) ): ?>
				<input type="submit" value="Import videos" name="ts-import-videos">
			<?php endif; ?>
			<table class="wp-list-table widefat fixed striped videos">
				<tbody>
					<?php self::vdfGetHeadsTable($paramsUrl); ?>
					<?php if( isset($videos['items']) ): ?>
						<?php foreach( $videos['items'] as $key => $item ): ?>
							<tr>
								<td>
									<input type="checkbox" name="videos[<?php echo intval($key) ?>]" value="<?php echo ts_enc_string(serialize($item)) ?>">
								</td>
								<td>
									<a href="https://www.youtube.com/watch?v=<?php echo esc_attr($item['id']) ?>" target="_blank">
										<img src="<?php echo esc_url($item['snippet']['thumbnails']['default']['url']) ?>">
										<span class="ts-import-video-name"><?php echo esc_attr($item['snippet']['title']) ?></span>
									</a>
								</td>
								<td>
									<?php
										echo date('Y-m-d H:i:s', strtotime($item['snippet']['publishedAt']));
										if( in_array($item['id'], $imported) ){
											echo '<span class="ts-is-imported">'. esc_html__('Imported', 'videofly') .'</span>';
										}
									?>
								</td>
								<td>
									<?php echo self::imp_covtime($item['contentDetails']['duration']) ?>
								</td>
								<td>
									<ul>
										<li>
											<?php esc_html_e('Category', 'touchsize'); ?>:
											<a href="https://www.youtube.com/channel/<?php echo esc_attr($videos['categories'][$item['snippet']['categoryId']]['channelId']) ?>" target="_blank">
												<?php echo esc_attr($videos['categories'][$item['snippet']['categoryId']]['title']) ?>
											</a>
										</li>
										<li>
											<?php esc_html_e('Views', 'videofly'); ?>: <?php echo vdf_var_sanitize($item['statistics']['viewCount']) ?>
										</li>
										<li>
											<?php esc_html_e('Likes', 'videofly'); ?>: <?php echo vdf_var_sanitize($item['statistics']['likeCount']) ?>
										</li>
										<li>
											<?php esc_html_e('Dislikes', 'videofly'); ?>: <?php echo vdf_var_sanitize($item['statistics']['dislikeCount']) ?>
										</li>
										<li>
											<?php esc_html_e('Comments', 'videofly'); ?>: <?php echo vdf_var_sanitize($item['statistics']['commentCount']) ?>
										</li>
										<li>
											<?php esc_html_e('Channel', 'videofly'); ?>: <a href="https://www.youtube.com/channel/<?php echo vdf_var_sanitize($item['snippet']['channelId']) ?>"><?php echo vdf_var_sanitize($item['snippet']['channelTitle']) ?>
										</li>
									</ul>
								</td>
							</tr>
						<?php endforeach ?>
						<input type="hidden" name="categories" value="<?php echo ts_enc_string(serialize($videos['categories'])) ?>">
						<input type="hidden" name="ts-get-after-import" value="<?php echo ts_enc_string(serialize($videos)) ?>">
						<input type="hidden" value="<?php echo ts_enc_string(serialize(self::vdfGetPagination($paramsUrl)['paramsUrl'])) ?>" name="ts-params-url">

					<?php else: ?>
						<tr class="no-items">
							<td class="colspanchange" colspan="9">
								<strong><?php esc_html_e('Not found any videos', 'videofly'); ?></strong>
							</td>
						</tr>
					<?php endif ?>
					<?php echo self::vdfGetHeadsTable($paramsUrl); ?>
				</tbody>
			</table>
			<?php if( isset($videos['items']) ): ?>
				<input type="submit" value="Import videos" name="ts-import-videos">
			<?php endif; ?>
		</form>
		<?php
		echo self::vdfGetPagination($paramsUrl)['html'];
	}

	static function vdfGetHeadsTable(){
		$titles = array(esc_html__('Select', 'videofly'), esc_html__('Title', 'videofly'), esc_html__('Published', 'videofly'),  esc_html__('Duration', 'videofly'), esc_html__('Details', 'videofly'));

		$html = '<tr>';
		$count = count($titles) - 1;

		foreach( $titles as $key => $title ){
			if ( $key == 0 ) {
				$html .= '<th scope="col" class="manage-column"><input type="checkbox" class="ts-select-all-videos">'. $title .'</th>';
			} else {
				$html .= '<th scope="col" class="manage-column">'. $title .'</th>';
			}
		}
		$html .= '</tr>';

		echo vdf_var_sanitize($html);
	}

	static function vdfAdmin_init(){
		add_action('before_delete_post', array(__CLASS__, 'vdfRemovePost'), 10, 1);
	}

	static function vdfRemovePost($id){

		if( get_post_type($id) !== 'video' ) return;

		$settings = get_option('importer-settings');
		$postMeta = get_post_meta($id, 'ts-youtube', true);

		$importedVideos = isset($settings['imported']) && is_array($settings['imported']) ? $settings['imported'] : array();
		$youtubeId = isset($postMeta['youtube-id']) ? $postMeta['youtube-id'] : '';

		if( ($key = array_search($youtubeId, $importedVideos)) !== false ) {
		    unset($importedVideos[$key]);

		    $settings['imported'] = $importedVideos;

		    update_option('importer-settings', $settings);
		}
	}
}
?>