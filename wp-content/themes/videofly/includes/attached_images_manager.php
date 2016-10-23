<?php
	// attched images management
	
function red_meta_boxes() {
	global $post;

	// add meta box that will hold attached images
	$post_types = array('post', 'ts-gallery');
	foreach($post_types as $post_type){
		if( $post_type == 'ts-gallery' ){
			add_meta_box( 'ts-gallery-images-gallery', esc_html__( 'Attached Images', 'videofly' ), 'red_product_images_box', $post_type, 'normal' );
		}else{
			add_meta_box( 'ts-gallery-images', esc_html__( 'Attached Images', 'videofly' ), 'red_product_images_box', $post_type, 'normal' );
		}
	}
}

add_action( 'add_meta_boxes', 'red_meta_boxes' );	

	/**
 * Product Images
 *
 * Function for displaying the product images meta box.
 *
 */


/**
 * Display the product images meta box.
 *
 * @access public
 * @return void
 */
function red_product_images_box() {
	global $post;
	?>
	<div id="post_images_container">
		<ul class="product_images">
			<?php
			
				if ( metadata_exists( 'post', $post->ID, '_post_image_gallery' ) ) {

					$product_image_gallery = get_post_meta( $post->ID, '_post_image_gallery', true );

					$attachments = array_filter( explode( ',', $product_image_gallery ) );
				}

				if ( isset( $attachments ) ){
					foreach ( $attachments as $attachment_id ) {
						echo '
						<li class="image" data-attachment_id="' . $attachment_id . '">
							' . wp_get_attachment_image( $attachment_id, 'thumbnail' ) . '
							<ul class="actions">
								<li><a href="#" class="icon-close" title="' . esc_html__( 'Delete image', 'videofly' ) . '"></a></li>
								<li class="ts-edit-image"><a href="#" class="icon-brush" title="' . esc_html__( 'Edit image', 'videofly' ) . '"></a></li>
							</ul>
						</li>';
					}
				}
					
				if(!isset($product_image_gallery)){
					$product_image_gallery = '';
				}
			?>
		</ul>

		<input type="hidden" id="product_image_gallery" name="product_image_gallery" value="<?php echo esc_attr( $product_image_gallery ); ?>" />

	</div>
	<p class="add_product_images hide-if-no-js">
		<a href="#"><?php esc_html_e( 'Add gallery images', 'videofly' ); ?></a>
	</p>
	<script type="text/javascript">
		jQuery(document).ready(function($){

			function ts_edit_image(){

				var product_gallery_frame;
				var $image_gallery_ids = $('#product_image_gallery');
				var $product_images = $('#post_images_container ul.product_images');

				jQuery(document).on("click", ".ts-edit-image", function(event) {

					var $el = $(this);
					var attachment_ids = $image_gallery_ids.val();
					event.preventDefault();
					var id_attachment = $el.closest('.image').attr('data-attachment_id');
					
					// Create the media frame.
					product_gallery_frame = wp.media.frames.downloadable_file = wp.media({
						// Set the title of the modal.
						title: '<?php esc_html_e( 'Edit images', 'videofly' ); ?>',
						button: {
							text: '<?php esc_html_e( 'Save changes', 'videofly' ); ?>',
						},
						multiple: false
					});

					product_gallery_frame.on('open', function(){
						var selection = product_gallery_frame.state().get('selection');
					    attachment = wp.media.attachment(id_attachment);
					    attachment.fetch();
					    selection.add(attachment);
				    });

					// When an image is selected, run a callback.
					product_gallery_frame.on('select', function(){
						
						var selection = product_gallery_frame.state().get('selection');

						selection.map( function(attachment){

							attachment = attachment.toJSON();
							if ( attachment.id ) {
								if( attachment_ids ){
									var array_ids = attachment_ids.split(',');
									for(var key in array_ids){
										if( array_ids[key] == id_attachment ){
											array_ids[key] = attachment.id;
										}
									}
									attachment_ids = array_ids.join(',');
								}

								$el.closest('.image').attr('data-attachment_id', attachment.id).html('\
									<img src="' + attachment.url + '" />\
									<ul class="actions">\
										<li><a href="#" class="icon-close" title="<?php esc_html_e( 'Delete image', 'videofly' ); ?>"><?php //esc_html_e( 'Delete', 'videofly' ); ?></a></li>\
										<li class="ts-edit-image"><a href="#" class="icon-brush" title="<?php esc_html_e( 'Edit image', 'videofly' ); ?>"></a></li>\
									</ul>'
								);
							}
						} );

						$image_gallery_ids.val(attachment_ids);
					});

					// Finally, open the modal.
					product_gallery_frame.open();
				});
			}
			ts_edit_image();

			// Uploading files
			var product_gallery_frame;
			var $image_gallery_ids = $('#product_image_gallery');
			var $product_images = $('#post_images_container ul.product_images');

			jQuery('.add_product_images').click(function( event ) {

				var $el = $(this);
				var attachment_ids = $image_gallery_ids.val();

				event.preventDefault();

				// If the media frame already exists, reopen it.
				if (product_gallery_frame) {
					product_gallery_frame.open();
					return;
				}

				// Create the media frame.
				product_gallery_frame = wp.media.frames.downloadable_file = wp.media({
					// Set the title of the modal.
					title: '<?php esc_html_e( 'Add Images to Gallery', 'videofly' ); ?>',
					button: {
						text: '<?php esc_html_e( 'Add to gallery', 'videofly' ); ?>',
					},
					multiple: true
				});

				// When an image is selected, run a callback.
				product_gallery_frame.on( 'select', function() {
					
					var selection = product_gallery_frame.state().get('selection');

					selection.map( function( attachment ) {

						attachment = attachment.toJSON();

						if ( attachment.id ) {
							attachment_ids = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;

							$product_images.append('\
								<li class="image" data-attachment_id="' + attachment.id + '">\
									<img src="' + attachment.url + '" />\
									<ul class="actions">\
										<li><a href="#" class="icon-close" title="<?php esc_html_e( 'Delete image', 'videofly' ); ?>"><?php //esc_html_e( 'Delete', 'videofly' ); ?></a></li>\
										<li class="ts-edit-image"><a href="#" class="icon-brush" title="<?php esc_html_e( 'Edit image', 'videofly' ); ?>"></a></li>\
									</ul>\
								</li>'
							);
						}

					} );

					$image_gallery_ids.val( attachment_ids );
				});

				// Finally, open the modal.
				product_gallery_frame.open();
			});

			// Image ordering
			$product_images.sortable({
				items: 'li.image',
				cursor: 'move',
				scrollSensitivity:40,
				forcePlaceholderSize: true,
				forceHelperSize: false,
				helper: 'clone',
				opacity: 0.65,
				placeholder: 'wc-metabox-sortable-placeholder',
				start:function(event,ui){
					ui.item.css('background-color','#f6f6f6');
				},
				stop:function(event,ui){
					ui.item.removeAttr('style');
				},
				update: function(event, ui) {
					var attachment_ids = '';

					$('#post_images_container ul li.image').css('cursor','default').each(function() {
						var attachment_id = jQuery(this).attr( 'data-attachment_id' );
						attachment_ids = attachment_ids + attachment_id + ',';
					});

					$image_gallery_ids.val( attachment_ids );
				}
			});

			// Remove images
			$('#post_images_container').on( 'click', 'a.icon-close', function() {

				$(this).closest('li.image').remove();

				var attachment_ids = '';

				$('#post_images_container ul li.image').css('cursor','default').each(function() {
					var attachment_id = jQuery(this).attr( 'data-attachment_id' );
					attachment_ids = attachment_ids + attachment_id + ',';
				});

				$image_gallery_ids.val(attachment_ids);

				return false;
			} );

		});
	</script>
	<?php
}

add_action('save_post', 'ts_save_attached_images');

function ts_save_attached_images( $post_id ) {
	if( isset( $_POST['product_image_gallery'] ) )
	{
		$attachment_ids = array_filter( explode( ',', ts_clean( $_POST['product_image_gallery'] ) ) );
		update_post_meta( $post_id, '_post_image_gallery', implode( ',', $attachment_ids ) );
	}
	
}

function ts_attached_images_style() {
	wp_register_style('AttachedImagesStyle', get_template_directory_uri().'/admin/css/attached_images.css');
	wp_enqueue_style('AttachedImagesStyle');
}

add_action('admin_init', 'ts_attached_images_style');

/**
 * Clean variables
 */
function ts_clean( $var ) {
	return sanitize_text_field( $var );
}

?>
