<?php

add_action( 'add_meta_boxes', 'ts_event_add_custom_box' );
add_action( 'save_post', 'ts_event_save_post' );

function ts_event_add_custom_box()
{
    add_meta_box(
        'event',
        esc_html__('Settings event','videofly'),
        'ts_event_options_custom_box',
        'event'
    );
}

function ts_event_options_custom_box($post)
{
    // Use nonce for verification
    wp_nonce_field( plugin_basename( __FILE__ ), 'ts_event_nonce' );
    $event = get_post_meta($post->ID, 'event', TRUE);
    $day = get_post_meta($post->ID, 'day', TRUE);

    if( !$day ){
        $day = '';
    }else{
        if( !empty($day) ){
            $day = date('Y-m-d', $day);
        }
    }

    if ( !$event ) {
        $event = array();
        $event['start-time'] = '';
        $event['end-time'] = '';
        $event['event-days'] = '';
        $event['event-repeat'] = '';
        $event['event-enable-repeat'] = 'n';
        $event['forever'] = 'n';
        $event['event-end'] = '';
        $event['theme'] = '';
        $event['person'] = '';
        $event['map'] = '';
        $event['free-paid'] = '';
        $event['ticket-url'] = '';
        $event['price'] = '';
        $event['venue'] = '';
    }

    echo '<table>
            <tr valign="top">
                <td>' . esc_html__('Start day','videofly') . '</td>
                <td>
                    <input size="60" type="text" value="'. esc_attr($day) .'" name="day" />
                </td>
            </tr>
            <tr>
                <td>' . esc_html__('End day','videofly') . '</td>
                <td>
                    <input size="60" type="text" value="'. esc_attr($event['event-end']) .'" name="event[event-end]" />
                </td>
            </tr>
            <tr>
                <td>' . esc_html__('Start time','videofly') . '</td>
                <td>
                    <input size="60" type="text" value="'. esc_attr($event['start-time']) .'" name="event[start-time]" />
                </td>
            </tr>
            <tr>
                <td>' . esc_html__('End time','videofly') . '</td>
                <td>
                    <input size="60" type="text" value="'. esc_attr($event['end-time']) .'" name="event[end-time]" />
                </td>
            </tr>
            <tr>
                <td>' . esc_html__('Repeat event','videofly') . '</td>
                <td>
                    <select name="event[event-enable-repeat]">
                        <option ' . selected($event['event-enable-repeat'], 'y', false) . ' value="y">' . esc_html__('Yes','videofly') . '</option>
                        <option ' . selected($event['event-enable-repeat'], 'n', false) . ' value="n">' . esc_html__('No','videofly') . '</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>' . esc_html__('Change event repeat','videofly') . '</td>
                <td>
                    <select name="event[event-repeat]">
                        <option ' . selected($event['event-repeat'], '1', false) . ' value="1">' . esc_html__('Weekly','videofly') . '</option>
                        <option ' . selected($event['event-repeat'], '2', false) . ' value="2">' . esc_html__('Monthly','videofly') . '</option>
                        <option ' . selected($event['event-repeat'], '3', false) . ' value="3">' . esc_html__('Yearly','videofly') . '</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>' . esc_html__('Add theme here','videofly') . '</td>
                <td>
                    <input size="60" type="text" value="'. esc_attr($event['theme']) .'" name="event[theme]" />
                </td>
            </tr>
            <tr>
                <td>' . esc_html__('Person','videofly') . '</td>
                <td>
                    <input size="60" type="text" value="'. esc_attr($event['person']) .'" name="event[person]" />
                </td>
            </tr>
            <tr>
                <td>' . esc_html__('Map','videofly') . '</td>
                <td>
                    <textarea name="event[map]" cols="60" rows="5">' . $event['map'] . '</textarea>
                </td>
            </tr>
            <tr>
                <td>' . esc_html__('Free or paid','videofly') . '</td>
                <td>
                    <select class="ts-free-paid" name="event[free-paid]">
                        <option ' . selected($event['free-paid'], 'paid', false) . ' value="paid">' . esc_html__('Paid','videofly') . '</option>
                        <option ' . selected($event['free-paid'], 'free', false) . ' value="free">' . esc_html__('Free','videofly') . '</option>
                    </select>
                </td>
            </tr>
            <tr class="ts-event-price-url">
                <td>' . esc_html__('Price','videofly') . '</td>
                <td>
                    <input size="60" type="text" value="'. esc_attr($event['price']) .'" name="event[price]" />
                </td>
            </tr>
            <tr class="ts-event-price-url">
                <td>' . esc_html__('Ticket buy URL','videofly') . '</td>
                <td>
                    <input size="60" type="text" value="'. esc_attr($event['ticket-url']) .'" name="event[ticket-url]" />
                </td>
            </tr>
            <tr>
                <td>' . esc_html__('Venue','videofly') . '</td>
                <td>
                    <input size="60" type="text" value="'. esc_attr($event['venue']) .'" name="event[venue]" />
                </td>
            </tr>
        </table>
        <script>
            jQuery(document).ready(function(){
                jQuery(".ts-free-paid").change(function(){
                    if( jQuery(this).val() == "free" ){
                        jQuery(".ts-event-price-url").css("display", "none");
                    }else{
                        jQuery(".ts-event-price-url").css("display", "");
                    }
                });

                if( jQuery(".ts-free-paid").val() == "free" ){
                    jQuery(".ts-event-price-url").css("display", "none");
                }else{
                    jQuery(".ts-event-price-url").css("display", "");
                }
            });
        </script>
        ';

}

function ts_event_save_post($post_id)
{
    global $post;

    if ( isset($post->post_type) && @$post->post_type != 'event' ) {
        return;
    }

    if (!isset( $_POST['ts_event_nonce'] ) ||
        !wp_verify_nonce( $_POST['ts_event_nonce'], plugin_basename( __FILE__ ) )
    ) return;

    if( !current_user_can( 'edit_post', $post_id ) ) return;

    // array containing filtred slides
    $event = array();

    if( isset( $_POST['day'] ) ){
        $day = $_POST['day'];
    }else{
        $day = '';
    }

    if ( isset( $_POST['event'] ) && is_array( $_POST['event'] ) && !empty( $_POST['event'] )  ) {
        $t = $_POST['event'];
        $event['day'] = isset($day) ? esc_attr($day) : '';
        $event['start-time'] = isset($t['start-time']) ? esc_attr($t['start-time']) : '';
        $event['end-time'] = isset($t['end-time']) ? esc_attr($t['end-time']) : '';
        $event['event-enable-repeat'] = (isset($t['event-enable-repeat']) && ($t['event-enable-repeat'] == 'y' || $t['event-enable-repeat'] == 'n')) ? $t['event-enable-repeat'] : 'n';
        $event['event-end'] = isset($t['event-end']) ? $t['event-end'] : '';
        $event['event-repeat'] = (isset($t['event-repeat']) && ($t['event-repeat'] == '1' || $t['event-repeat'] == '2' || $t['event-repeat'] == '3')) ? $t['event-repeat'] : '';
        $event['theme'] = isset($t['theme']) ? esc_attr($t['theme']) : '';
        $event['person'] = isset($t['person']) ? esc_attr($t['person']) : '';
        $event['map'] = isset($t['map']) ? $t['map'] : '';
        $event['free-paid'] = (isset($t['free-paid']) && ($t['free-paid'] == 'free' || $t['free-paid'] == 'paid')) ? $t['free-paid'] : '';
        $event['ticket-url'] = isset($t['ticket-url']) ? esc_attr($t['ticket-url']) : '';
        $event['price'] = isset($t['price']) ? esc_attr($t['price']) : '';
        $event['venue'] = isset($t['venue']) ? esc_attr($t['venue']) : '';

    } else {
        $event['day'] = '';
        $event['start-time'] = '';
        $event['end-time'] = '';
        $event['event-days'] = '';
        $event['event-repeat'] = '';
        $event['event-enable-repeat'] = 'n';
        $event['forever'] = 'n';
        $event['event-end'] = 'n';
        $event['theme'] = '';
        $event['person'] = '';
        $event['map'] = '';
        $event['price'] = '';
        $event['ticket-url'] = '';
        $event['free-paid'] = '';
        $event['venue'] = '';

    }

    update_post_meta($post_id, 'event', $event);
    update_post_meta($post_id, 'day', strtotime($day));
}

add_action( 'add_meta_boxes', 'ts_slider_add_custom_box' );
add_action( 'save_post', 'ts_slider_save_postdata' );

function ts_slider_add_custom_box()
{
    add_meta_box(
        'ts_slider_options',
        'Slider Options',
        'ts_slider_options_custom_box',
        'ts_slider'
    );

    add_meta_box(
        'ts_slides',
        'Slides',
        'ts_slider_custom_box',
        'ts_slider'
    );
}

function ts_slider_options_custom_box($post) {

    $slider_type = get_post_meta($post->ID, 'slider_type', TRUE);
    $slider_source = get_post_meta($post->ID, 'slider-source', TRUE);
    $slider_size = get_post_meta($post->ID, 'slider-size', TRUE);
    $sliderNrOfPosts = get_post_meta($post->ID, 'slider-nr-of-posts', TRUE);

    if( !$sliderNrOfPosts ){
        $sliderNrOfPosts = 5;
    }

    if( $slider_size ){
          $slider_width = $slider_size['width'];
          $slider_height = $slider_size['height'];
    }else{
        $slider_size = get_option('videofly_image_sizes');
        $slider_width = $slider_size['slider']['width'];
        $slider_height = $slider_size['slider']['height'];
    }

    if( $slider_source ){
        $slider_source = ($slider_source == 'latest-posts' || $slider_source == 'latest-galleries' || $slider_source == 'latest-videos' || $slider_source == 'custom-slides' || $slider_source == 'latest-featured-posts' || $slider_source == 'latest-featured-videos' || $slider_source == 'latest-featured-galleries') ? $slider_source : 'custom-slides';
    }else{
        $slider_source = 'custom-slides';
    }

    if ($slider_type) {
        $is_flexslider = ( $slider_type == 'flexslider' ) ? 'selected="selected"' : '';
        $is_slicebox = ( $slider_type == 'slicebox' ) ? 'selected="selected"' : '';
        $is_bxslider = ( $slider_type == 'bxslider' ) ? 'selected="selected"' : '';
        $is_paraslider = ( $slider_type == 'parallax' ) ? 'selected="selected"' : '';
        $is_streamslider = ( $slider_type == 'stream' ) ? 'selected="selected"' : '';
        $is_corena = ( $slider_type == 'corena' ) ? 'selected="selected"' : '';
    } else {
        $is_flexslider = 'flexslider';
        $is_slicebox = '';
        $is_bxslider = '';
        $is_paraslider = '';
        $is_streamslider = '';
        $is_corena= '';
    }

    echo '
    <table>
        <tr>
            <td>' . esc_html__('Slider source','videofly') . '</td>
            <td>
                <select name="slider-source" id="ts-slider-source">
                    <option ' . selected($slider_source, 'latest-posts', false) . ' value="latest-posts">' . esc_html__('Latest posts','videofly') . '</option>
                    <option ' . selected($slider_source, 'latest-videos', false) . ' value="latest-videos">' . esc_html__('Latest videos','videofly') . '</option>
                    <option ' . selected($slider_source, 'latest-galleries', false) . ' value="latest-galleries">' . esc_html__('Latest galleries','videofly') . '</option>
                    <option ' . selected($slider_source, 'custom-slides', false) . ' value="custom-slides">' . esc_html__('Custom slides','videofly') . '</option>
                    <option ' . selected($slider_source, 'latest-featured-posts', false) . ' value="latest-featured-posts">' . esc_html__('Latest featured posts','videofly') . '</option>
                    <option ' . selected($slider_source, 'latest-featured-galleries', false) . ' value="latest-featured-galleries">' . esc_html__('Latest featured galleries','videofly') . '</option>
                    <option ' . selected($slider_source, 'latest-featured-videos', false) . ' value="latest-featured-videos">' . esc_html__('Latest featured videos','videofly') . '</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>' . esc_html__('Slider type','videofly') . '</td>
            <td>
                <select name="slider_type">
                    <option value="flexslider" ' . $is_flexslider . '>' . esc_html__('Flex Slider','videofly') . '</option>
                    <option value="slicebox" ' . $is_slicebox . '>' . esc_html__('Slicebox','videofly') . '</option>
                    <option value="bxslider" ' . $is_bxslider . '>' . esc_html__('bxSlider','videofly') . '</option>
                    <option value="parallax" ' . $is_paraslider . '>' . esc_html__('Parallax slider','videofly') . '</option>
                    <option value="stream" ' . $is_streamslider . '>' . esc_html__('Stream slider','videofly') . '</option>
                    <option value="corena" ' . $is_corena . '>' . esc_html__('Corena slider','videofly') . '</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>' . esc_html__('Slider image width','videofly') . '</td>
            <td>
                <input type="text" name="slider-size[width]" value="' . absint($slider_width) . '" />px
            </td>
        </tr>
        <tr>
            <td>' . esc_html__('Slider image height','videofly') . '</td>
            <td>
                <input type="text" name="slider-size[height]" value="' . absint($slider_height) . '" />px
            </td>
        </tr>
        <tr id="ts-slider-nr-of-posts">
            <td>' . esc_html__('How many posts to extract','videofly') . '</td>
            <td>
                <input type="text" name="slider-nr-of-posts" value="' . absint($sliderNrOfPosts) . '" />
            </td>
        </tr>
    </table>';
}

function ts_slider_custom_box( $post )
{

    // Use nonce for verification
    wp_nonce_field( plugin_basename( __FILE__ ), 'ts_slider_nonce' );
    $slides = get_post_meta($post->ID, 'ts_slides', TRUE);

    echo '<input type="button" class="button" id="add-slide" value="' .esc_html__('Add New Slide','videofly'). '" /><br/><br/>';
    echo '<ul id="ts-slides">';

    $slides_editor = '';

    if ( ! empty( $slides ) ) {
        $index = 0;
        foreach ( $slides as $slide_id => $slide ) {
            $index++;
            $slide_title = ($slide["slide_title"]) ? $slide["slide_title"] : 'Slide ' . $index;
            $slides_editor .= '
            <li class="ts-slide">
            <div class="sortable-meta-element"><span class="tab-arrow icon-down"></span> <span class="slide-tab">'.$slide_title.'</span>
            </div>
            <table class="hidden">
                <tr>
                    <td>' . esc_html__( 'Slide title','videofly' ) . '</td>
                    <td>
                        <input type="text" class="slide_title" name="ts_slider['.$slide_id.'][slide_title]" value="'.$slide["slide_title"].'" style="width: 100%" />
                    </td>
                </tr>
                <tr>
                    <td valign="top">' . esc_html__( 'Slide description','videofly' ) . '</td>
                    <td>
                        <textarea class="slide_description" name="ts_slider['.$slide_id.'][slide_description]" cols="60" rows="5">'.$slide["slide_description"].'</textarea>
                    </td>
                </tr>
                <tr valign="top">
                    <td>' . esc_html__( 'Slide URL','videofly' ) . '</td>
                    <td>
                        <input type="text" class="slide_url" name="ts_slider['.$slide_id.'][slide_url]" value="'.$slide["slide_url"].'" />
                        <input type="hidden" class="slide_media_id" name="ts_slider['.$slide_id.'][slide_media_id]" value="'.$slide['slide_media_id'].'" />
                        <input type="button" id="upload-'.$slide_id.'" class="button ts-upload-slide" value="' .esc_html__( 'Upload','videofly' ). '" /> <br />
                        <div class="slide_preview"><img src="'.$slide["slide_url"].'" style="width: 400px" /></div>
                    </td>
                </tr>
                <tr>
                    <td>' . esc_html__( 'Redirect to URL','videofly' ) . '</td>
                    <td>
                        <input type="text" class="redirect_to_url" name="ts_slider['.$slide_id.'][redirect_to_url]" value="'.$slide['redirect_to_url'].'" style="width: 100%" />
                    </td>
                </tr>
                <tr>
                    <td>' . esc_html__( 'Select caption position','videofly' ) . '</td>
                    <td>
                        <select name="ts_slider['.$slide_id.'][slide_position]" class="slide_position">
                            <option value="left" ' . selected($slide['slide_position'], 'left', false) . '>Left</option>
                            <option value="center" ' . selected($slide['slide_position'], 'center', false) . '>Center</option>
                            <option value="right" ' . selected($slide['slide_position'], 'right', false) . '>Right</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td></td><td><input type="button" class="button button-primary remove-slide" value="'.esc_html__('Remove','videofly').'" /></td>
                </tr>
            </table></li>';
        }
    }
    echo vdf_var_sanitize($slides_editor);
    echo '</ul>';
    echo '<script id="ts-add-slider" type="text/template">';
    echo '<li class="ts-slide">
    <div class="sortable-meta-element"><span class="tab-arrow icon-down"></span><span class="slide-tab">Slide {{slide-number}}</span>
    </div>
    <table>
        <tr>
            <td>' . esc_html__( 'Slide title','videofly' ) . '</td>
            <td>
                <input type="text" class="slide_title" name="ts_slider[slide-{{slide-id}}][slide_title]" value="" style="width: 100%" />
            </td>
        </tr>
        <tr>
            <td valign="top">' . esc_html__( 'Slide description','videofly' ) . '</td>
            <td>
                <textarea class="slide_description" name="ts_slider[slide-{{slide-id}}][slide_description]" cols="60" rows="5"></textarea>
            </td>
        </tr>
        <tr>
            <td>' . esc_html__( 'Slide URL','videofly' ) . '</td>
            <td>
                <input type="text" class="slide_url" name="ts_slider[slide-{{slide-id}}][slide_url]" value="" />
                <input type="hidden" class="slide_media_id" name="ts_slider[slide-{{slide-id}}][slide_media_id]" value="" />
                <input type="button" id="upload-{{slide-id}}" class="button ts-upload-slide" value="' .esc_html__( 'Upload','videofly' ). '" />
                <div class="slide_preview"></div>
            </td>
        </tr>
        <tr>
            <td>' . esc_html__( 'Redirect to URL','videofly' ) . '</td>
            <td>
                <input type="text" class="redirect_to_url" name="ts_slider[slide-{{slide-id}}][redirect_to_url]" value="" style="width: 100%" />
            </td>
        </tr>
        <tr>
            <td>' . esc_html__( 'Select caption position','videofly' ) . '</td>
            <td>
                <select name="ts_slider[slide-{{slide-id}}][slide_position]" class="slide_position">
                    <option value="left">' . esc_html__('Left', 'videofly') . '</option>
                    <option value="center">' . esc_html__('Center', 'videofly') . '</option>
                    <option value="right">' . esc_html__('Right', 'videofly') . '</option>
                </select>
            </td>
        </tr>
        <tr>
            <td></td><td><input type="button" class="button button-primary remove-slide" value="'.esc_html__('Remove','videofly').'" /></td>
        </tr>
    </table></li>';
    echo '</script>';
?>
    <script>
    jQuery(document).ready(function($) {
        var slides_count = $("#ts-slides > li").length;
        // sortable portfolio items
        $("#ts-slides").sortable();

        $(document).on('change', '.slide_title', function(event) {
            event.preventDefault();
            var _this = $(this);
            _this.closest('.ts-slide').find('.slide-tab').text(_this.val());
        });

        var slides = $('#ts-slides'),
            slideTempalte = $('#ts-add-slider').html(),
            custom_uploader = {};

        if (typeof wp.media.frames.file_frame == 'undefined') {
            wp.media.frames.file_frame = {};
        }


        $(document).on('click', '#add-slide', function(event) {
            event.preventDefault();

            slides_count++;
            var id = new Date().getTime();
            var slide_id = new RegExp('{{slide-id}}', 'g');
            var slide_number = new RegExp('{{slide-number}}', 'g');

            var template = slideTempalte.replace(slide_id, id).replace(slide_number, slides_count);
            slides.append(template);
        });


        $(document).on('click', '.remove-slide', function(event) {
            event.preventDefault();
            $(this).closest('li').remove();
            slides_count--;
        });


        $(document).on('click', '.ts-upload-slide', function(e) {
            e.preventDefault();

            var _this     = $(this),
                target_id = _this.attr('id'),
                media_id  = _this.closest('li').find('.slide_media_id').val();

            //If the uploader object has already been created, reopen the dialog
            if (custom_uploader[target_id]) {
                custom_uploader[target_id].open();
                return;
            }

            //Extend the wp.media object
            custom_uploader[target_id] = wp.media.frames.file_frame[target_id] = wp.media({
                title: 'Choose Image',
                button: {
                    text: 'Choose Image'
                },
                library: {
                    type: 'image'
                },
                multiple: false,
                selection: [media_id]
            });

            //When a file is selected, grab the URL and set it as the text field's value
            custom_uploader[target_id].on('select', function() {
                var attachment = custom_uploader[target_id].state().get('selection').first().toJSON();
                var slide = _this.closest('table');
                slide.find('.slide_url').val(attachment.url);
                slide.find('.slide_media_id').val(attachment.id);
                var img = $("<img>").attr('src', attachment.url).attr('style', 'width:400px');
                slide.find('.slide_preview').html(img);
            });

            //Open the uploader dialog
            custom_uploader[target_id].open();
        });
    });
    </script>
<?php
}

// saving slider
function ts_slider_save_postdata( $post_id )
{
    global $post;

    if ( isset($post->post_type) && @$post->post_type != 'ts_slider' ) {
        return;
    }

    if (!isset( $_POST['ts_slider_nonce'] ) ||
        !wp_verify_nonce( $_POST['ts_slider_nonce'], plugin_basename( __FILE__ ) )
    ) return;

    if( !current_user_can( 'edit_post', $post_id ) ) return;

    if ( isset( $_POST['slider_type'] ) ) {
        switch ($_POST['slider_type']) {
            case 'flexslider':
                $slider_type = 'flexslider';
                break;

            case 'slicebox':
                $slider_type = 'slicebox';
                break;

            case 'bxslider':
                $slider_type = 'bxslider';
                break;

            case 'parallax':
                $slider_type = 'parallax';
                break;

            case 'stream':
                $slider_type = 'stream';
                break;

            case 'corena':
                $slider_type = 'corena';
                break;

            default:
                $slider_type = 'flexslider';
                break;
        }
    } else {
        $slider_type = 'flexslider';
    }

    update_post_meta( $post_id, 'slider_type', $slider_type );

    $sliderNrOfPosts = (isset($_POST['slider-nr-of-posts'])) ? absint($_POST['slider-nr-of-posts']) : 5;
    update_post_meta($post_id, 'slider-nr-of-posts', $sliderNrOfPosts);

    if( isset($_POST['slider-source']) ){
        $slider_source = ($_POST['slider-source'] == 'latest-posts' || $_POST['slider-source'] == 'latest-videos' || $_POST['slider-source'] == 'custom-slides' || $_POST['slider-source'] == 'latest-galleries' || $_POST['slider-source'] == 'latest-featured-galleries' || $_POST['slider-source'] == 'latest-featured-videos' || $_POST['slider-source'] == 'latest-featured-posts') ? $_POST['slider-source'] : 'custom-slides';
    }else{
        $slider_source = 'custom-slides';
    }
    update_post_meta( $post_id, 'slider-source', $slider_source );

    if( isset($_POST['slider-size']) && is_array($_POST['slider-size']) && !empty($_POST['slider-size']) ){
        if( isset($_POST['slider-size']['height']) ) $slider_size['height'] = absint($_POST['slider-size']['height']);
        if( isset($_POST['slider-size']['width']) ) $slider_size['width'] = absint($_POST['slider-size']['width']);
    }else{
        $slider_size = get_option('videofly_image_sizes');
        if( isset($slider_size['slider']['height']) ) $slider_size['height'] = absint($slider_size['slider']['height']);
        if( isset($slider_size['slider']['width']) ) $slider_size['width'] = absint($slider_size['slider']['width']);
    }
    update_post_meta( $post_id, 'slider-size', $slider_size );

    // array containing filtred slides
    $slider = array();

    if ( isset( $_POST['ts_slider'] ) ) {
        if ( is_array( $_POST['ts_slider'] ) && !empty( $_POST['ts_slider'] ) ) {
            foreach ( $_POST['ts_slider'] as $slide_id => $slide ) {
                $s['slide_id']          = $slide_id;
                $s['slide_title']       = isset($slide['slide_title']) ? esc_attr($slide['slide_title']) : '';
                $s['slide_description'] = isset($slide['slide_description']) ?
                                            esc_textarea($slide['slide_description']) : '';
                $s['slide_url']         = isset($slide['slide_url']) ? esc_url($slide['slide_url']) : '';
                $s['slide_media_id']    = isset($slide['slide_media_id']) ? esc_attr($slide['slide_media_id']) : '';
                $s['redirect_to_url']   = isset($slide['redirect_to_url']) ? esc_url($slide['redirect_to_url']) : '';
                $s['slide_position']   = isset($slide['slide_position']) ? esc_attr($slide['slide_position']) : '';
                $slider[] = $s;
            }
        }
    }

    update_post_meta( $post_id, 'ts_slides', $slider );
}

add_action( 'add_meta_boxes', 'ts_videos_add_custom_box' );
add_action( 'save_post', 'ts_videos_save_post' );

function ts_videos_add_custom_box()
{
    add_meta_box(
        'video',
        esc_html__('Insert Video','videofly'),
        'ts_videos_options_custom_box',
        'video'
    );
}

function ts_videos_options_custom_box($post)
{
    // Use nonce for verification
    $videos = get_post_meta($post->ID, 'ts-video', TRUE);

    $videoCode = isset($videos['video']) ? $videos['video'] : '';
    $type = isset($videos['type']) ? $videos['type'] : 'url';
    $ajax_nonce = wp_create_nonce( "video-image" );

?>
    <ul class="ts-url nav nav-tabs" role="tablist">
        <li data-type="url"<?php echo ($type == 'url' ? ' class="active"' : ''); ?>><a href="#url" role="tab" data-toggle="tab"><?php esc_html_e('Use video URL', 'videofly'); ?></a></li>
        <li data-type="upload"<?php echo ($type == 'upload' ? ' class="active"' : ''); ?>><a href="#upload" role="tab" data-toggle="tab"><?php esc_html_e('Upload video', 'videofly'); ?></a></li>
        <li data-type="embed"<?php echo ($type == 'embed' ? ' class="active"' : ''); ?>><a href="#embed" role="tab" data-toggle="tab"><?php esc_html_e('Use EMBED code', 'videofly'); ?></a></li>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane<?php echo ($type == 'url' ? ' active' : ''); ?>" id="url">
            <textarea id="ts-video-url" class="ts-empty-click" name="ts-url-video" cols="60" rows="5"><?php echo ($type == 'url' ? $videoCode : '') ?></textarea>
            <div class="ts-option-description"><?php esc_html_e('Insert your external video URL here. All services supported by WordPress are available. You can check the list here', 'videofly'); ?>: <a href="http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F" target="_blank"><?php esc_html_e('Source List', 'videofly'); ?></a></div>
        </div>
        <div role="tabpanel" class="tab-pane<?php echo ($type == 'upload' ? ' active' : ''); ?>" id="upload">
            <input type="text" name="ts-upload-video" value="<?php echo ($type == 'upload' ? $videoCode : '') ?>"/>
                    <input type="button" data-library="webm" data-title="<?php esc_html_e('Select video mp4', 'videofly')  ?>" class="button ts-upload-advertising" value="<?php esc_html_e( 'Upload video mp4', 'videofly' ) ?>"/>
            <br><br>
              <div class="ts-option-description"><?php esc_html_e('Upload your video here. We would recommend using MP4 file format for best compatibility', 'videofly'); ?></div>
        </div>
        <div role="tabpanel" class="tab-pane<?php echo ($type == 'embed' ? ' active' : ''); ?>" id="embed">
            <textarea class="ts-empty-click" name="ts-embed-video" cols="60" rows="5"><?php echo ($type == 'embed' ? $videoCode : '') ?></textarea>
            <br><br>
            <div class="ts-option-description"><?php esc_html_e('Insert your embed code here. You can take videos from anywhere you want, embeds provided from anywhere. NOTE: Not all services could work properly (video resizing). If you tried a service and there was a problem with it, please report this on our help desk.', 'videofly'); ?></div>
        </div>
        <div>
            <input style="display: none;" class="button-secondary" id="ts-get-featured-image" type="button" value="<?php esc_html_e('Get featured image', 'videofly'); ?>" />
        </div>
        <div style="display: none;" class="ts-remove-featured">
            <a id="ts-remove-featured-image" data-post-id="<?php echo vdf_var_sanitize($post->ID); ?>" href="#"><?php esc_html_e('Remove featured image', 'videofly'); ?></a>
        </div>
        <input type="hidden" name="selected-tab" value="<?php echo esc_attr($type); ?>">
        <?php wp_nonce_field( plugin_basename( __FILE__ ), 'ts_videos_nonce' ); ?>
    </div>
    <script>
        jQuery(document).ready(function(){
            jQuery('.ts-url > li.active > a').tab('show');

            jQuery( '.ts-url > li' ).click(function(){
                setTimeout(function(){
                    jQuery('div[role="tabpanel"]').each(function(){
                        if( jQuery(this).hasClass('active') ){
                            jQuery('.ts-tab-active').val('');
                            jQuery(this).find('.ts-tab-active').val('1');
                        }
                    })

                    if( typeof(jQuery('.ts-url > li:first-child')) !== 'undefined' && jQuery('.ts-url > li:first-child').hasClass('active') ){
                        jQuery('#ts-get-featured-image').css('display', '');
                    }else{
                        jQuery('#ts-get-featured-image').css('display', 'none');
                    }

                }, 100);
                console.log(jQuery(this).data('type'));
                jQuery('[name="selected-tab"]').val(jQuery(this).data('type'));
            });

            if( typeof(jQuery('.ts-url > li:first-child')) !== 'undefined' && jQuery('.ts-url > li:first-child').hasClass('active') ){
                jQuery('#ts-get-featured-image').css('display', '');
            }else{
                jQuery('#ts-get-featured-image').css('display', 'none');
            }

            jQuery('#ts-get-featured-image').click(function(event){
                event.preventDefault();
                var link = jQuery('#ts-video-url').val();

                jQuery.post(ajaxurl, 'action=ts_video_image&link=' + link + '&post_id=<?php echo vdf_var_sanitize($post->ID); ?>&nonce=<?php echo vdf_var_sanitize($ajax_nonce); ?>', function(response){
                    response = jQuery.parseJSON(response)
                    setTimeout(function(){
                        jQuery('#postimagediv .inside .ts-image-extern').remove();
                        jQuery('#postimagediv .inside').prepend('<p class="hide-if-no-js ts-image-extern" data-attachment-id="' + response.attachment_id + '"><a href="#"><img src="' + response.url + '"/></a></p>');
                        jQuery('#postimagediv .inside a#set-post-thumbnail').hide();
                        if( jQuery('#remove-post-thumbnail').length == 0 ){
                            jQuery('#postimagediv .inside').append(jQuery('.ts-remove-featured').html());
                        }
                    }, 500);
                });
            });

            jQuery(document).on('click', '#ts-remove-featured-image', function(event){
                event.preventDefault();
                var postId = jQuery(this).attr('data-post-id');

                data = {
                    action  : 'tsRemoveSetFeaturedImageFromPost',
                    nonce   : '<?php echo vdf_var_sanitize($ajax_nonce); ?>',
                    make    : 'remove',
                    postId  : postId
                };

                jQuery.post(ajaxurl, data, function(response) {
                    if( response ) {
                        jQuery('#postimagediv .inside a#set-post-thumbnail').show();
                        jQuery('#ts-remove-featured-image').remove();
                        jQuery('.ts-image-extern').remove();
                    }
                });
            });

            var featurdedImageAll;

            jQuery(document).on("click", ".ts-image-extern", function(event) {
                event.preventDefault();

                var elementThis = jQuery(this);
                var id_attachment = jQuery(this).attr('data-attachment-id');

                // Create the media frame.
                featurdedImageAll = wp.media.frames.downloadable_file = wp.media({
                    // Set the title of the modal.
                    title: '<?php esc_html_e( 'Edit images', 'videofly' ); ?>',
                    button: {
                        text: '<?php esc_html_e( 'Save changes', 'videofly' ); ?>',
                    },
                    multiple: false
                });

                featurdedImageAll.on('open', function(){
                    var selection = featurdedImageAll.state().get('selection');
                    attachment = wp.media.attachment(id_attachment);
                    attachment.fetch();
                    selection.add(attachment);
                });

                // When an image is selected, run a callback.
                featurdedImageAll.on('select', function(){

                    var selection = featurdedImageAll.state().get('selection');
                    selection.map( function(attachment){
                        attachment = attachment.toJSON();
                        data = {
                            action       : 'tsRemoveSetFeaturedImageFromPost',
                            nonce        : '<?php echo vdf_var_sanitize($ajax_nonce); ?>',
                            make         : 'add',
                            attachmentId : attachment.id,
                            postId       : <?php echo vdf_var_sanitize($post->ID) ?>
                        };

                        jQuery.post(ajaxurl, data, function(response) {
                            if( response == '1' ) {
                                jQuery('#postimagediv .inside .ts-image-extern').remove();
                                jQuery('#postimagediv .inside').prepend('<p class="hide-if-no-js ts-image-extern" data-attachment-id="' + attachment.id + '"><a href="#"><img src="' + attachment.url + '"/></a></p>');
                            }
                        });
                    });

                });

                // Finally, open the modal.
                featurdedImageAll.open();
            });
        });
    </script>
<?php
}

function ts_videos_save_post($post_id)
{
    global $post;

    if ( (isset($post->post_type) && $post->post_type !== 'video') || !current_user_can('edit_post', $post_id) ) return;

    if ( !isset( $_POST['ts_videos_nonce'] ) || !wp_verify_nonce($_POST['ts_videos_nonce'], plugin_basename( __FILE__ )) ) return;

    $video = array();
    $types = array('upload', 'url', 'embed');

    $type = isset($_POST['selected-tab']) && in_array($_POST['selected-tab'], $types) ? $_POST['selected-tab'] : '';

    if ( empty($type) ) return;

    $video['type'] = $type;
    $video['video'] = $_POST['ts-'. $type .'-video'];


    update_post_meta($post_id, 'ts-video', $video);
}

add_action( 'add_meta_boxes', 'ts_teams_add_custom_box' );
add_action( 'save_post', 'ts_teams_save_post' );

function ts_teams_add_custom_box()
{
    add_meta_box(
        'ts_member',
        esc_html__('About Team Member','videofly'),
        'ts_teams_options_custom_box',
        'ts_teams'
    );

    add_meta_box(
        'ts_member_networks',
        esc_html__('Social Networks','videofly'),
        'ts_teams_social_networks_custom_box',
        'ts_teams'
    );
}

function ts_teams_options_custom_box($post)
{
    // Use nonce for verification
    wp_nonce_field( plugin_basename( __FILE__ ), 'ts_teams_nonce' );
    $teams = get_post_meta($post->ID, 'ts_member', TRUE);

    if (!$teams) {
        $teams = array();
        $teams['about_member'] = '';
        $teams['position'] = '';
        $teams['team-user'] = '';
    }

    $args = array(
        'blog_id'      => $GLOBALS['blog_id'],
        'role'         => '',
        'meta_key'     => '',
        'meta_value'   => '',
        'meta_compare' => '',
        'meta_query'   => array(),
        'include'      => array(),
        'exclude'      => array(),
        'orderby'      => 'login',
        'order'        => 'ASC',
        'offset'       => '',
        'search'       => '',
        'number'       => '',
        'count_total'  => false,
        'fields'       => 'all',
        'who'          => ''
    );
    $users = get_users($args);
    $html = '';

    if( isset($users) && is_array($users) && count($users) > 0 ){
        $none = ($teams['team-user'] == 'none' || $teams['team-user'] == '') ? ' selected="selected"' : '';
        $html .= '<select name="teams[team-user]">
                    <option' . $none . ' value="none">' . esc_html__('None','videofly') . '</option>';
        foreach($users as $user){
            if( is_object($user) && isset($user->ID, $user->user_login) ){
                if( $teams['team-user'] == $user->ID ) $selected = ' selected="selected"';
                else $selected = '';
                $html .= '<option' . $selected . ' value="' . $user->ID . '">' . $user->user_login . '</option>';
            }
        }
        $html .= '</select>';
    }

    echo '<table>
        <tr valign="top">
            <td>' . esc_html__('Short information','videofly') . '</td>
            <td>
                <textarea name="teams[about_member]" cols="60" rows="5">'.esc_attr($teams['about_member']).'</textarea>
            </td>
        </tr>
        <tr>
            <td>' . esc_html__('Title','videofly') . '</td>
            <td>
                <input type="text" name="teams[position]" value="'.esc_attr($teams['position']).'" />
            </td>
        </tr>
        <tr>
            <td>' . esc_html__('Link team member to a user','videofly') . '</td>
            <td>
                ' . balanceTags($html, true) . '
            </td>
        </tr>
        </table>';

}

function ts_teams_social_networks_custom_box($post)
{
    $teams = get_post_meta($post->ID, 'ts_member', TRUE);
    $arraySocials = array('facebook', 'twitter', 'linkedin', 'gplus', 'email', 'skype', 'github', 'dribble', 'lastfm', 'linkedin', 'tumblr', 'vimeo', 'wordpress', 'yahoo', 'youtube', 'flickr', 'pinterest', 'instagram');
    $teams  = (isset($teams) && !empty($teams)) ? $teams : array();
    $optionsSocial = get_option( 'videofly_social' );
    $customSocial = (isset($optionsSocial['social_new']) && is_array($optionsSocial['social_new']) && !empty($optionsSocial['social_new'])) ? $optionsSocial['social_new'] : '';

    echo '<table class="socials-admin">';
        foreach($arraySocials as $social){
            if( !isset($teams[$social]) ){
                $teams[$social] = '';
            }

            if( $social == 'email' ){
                $icon = 'mail';
            }elseif( $social == 'dribble' ){
                $icon = 'dribbble';
            }elseif( $social == 'youtube' ){
                $icon = 'video';
            }else{
                $icon = NULL;
            }

            echo    '<tr>
                        <td>
                            <i alt="'. $social .'" class="icon-'. (isset($icon) ? $icon : $social) .'"></i>
                        </td>
                        <td>
                            <input type="text" name="teams['. $social .']" value="'. $teams[$social] .'" />
                        </td>
                    </tr>';
        }

        if( !empty($customSocial) ){
            foreach($customSocial as $key => $social){
                $socialNew = (isset($teams[$key])) ? $teams[$key] : '';

                echo    '<tr>
                            <td>
                                <img src="'. esc_url($social['image']) .'" style="width: 22px;"/>
                            </td>
                            <td>
                                <input type="text" name="teams['. $key .']" value="'. $socialNew .'" />
                            </td>
                        </tr>';
            }
        }

    echo '</table>';

}

function ts_teams_save_post($post_id)
{
    global $post;

    if ( isset($post->post_type) && @$post->post_type != 'ts_teams' ) {
        return;
    }

    if (!isset( $_POST['ts_teams_nonce'] ) ||
        !wp_verify_nonce( $_POST['ts_teams_nonce'], plugin_basename( __FILE__ ) )
    ) return;

    if( !current_user_can( 'edit_post', $post_id ) ) return;

    // array containing filtred slides
    $teams = array();
    $optionsSocial = get_option( 'videofly_social' );
    $customSocial = (isset($optionsSocial['social_new']) && is_array($optionsSocial['social_new']) && !empty($optionsSocial['social_new'])) ? $optionsSocial['social_new'] : '';

    $arraySocials = array('facebook', 'twitter', 'linkedin', 'gplus', 'email', 'skype', 'github', 'dribble', 'lastfm', 'linkedin', 'tumblr', 'vimeo', 'wordpress', 'yahoo', 'youtube', 'flickr', 'pinterest', 'instagram');

    if( !empty($customSocial) ){
        $arraySocials = array_merge($arraySocials, array_keys($customSocial));
    }

    if ( isset( $_POST['teams'] ) && is_array( $_POST['teams'] ) && !empty( $_POST['teams'] )  ) {
        $t = $_POST['teams'];
        $teams['about_member'] = isset($t['about_member']) ? wp_kses_post($t['about_member']) : '';
        $teams['position']     = isset($t['position']) ? sanitize_text_field($t['position']) : '';
        $teams['team-user']    = isset($t['team-user']) ? sanitize_text_field($t['team-user']) : '';

        foreach($t as $key => $value){

            if( in_array($key, $arraySocials) ){
                $teams[$key] = esc_url_raw($value);
            }
        }

    } else {
        $teams['about_member'] = '';
        $teams['position']     = '';
        $teams['team-user']    = '';
        foreach($arraySocials as $social){
            $teams[$social] = '';
        }
    }

    update_post_meta( $post_id, 'ts_member', $teams );
}

add_action( 'add_meta_boxes', 'ts_portfolio_add_custom_box' );
add_action( 'save_post', 'ts_portfolio_save_postdata' );

function ts_portfolio_add_custom_box()
{
    add_meta_box(
        'ts_portfolio',
        'Portfolio',
        'ts_portfolio_custom_box',
        'portfolio'
    );
}

function ts_portfolio_custom_box( $post )
{
    // Use nonce for verification
    wp_nonce_field( plugin_basename( __FILE__ ), 'ts_portfolio_nonce' );
    $portfolio_items = get_post_meta($post->ID, 'ts_portfolio', TRUE);
    $portfolio_details = get_post_meta($post->ID, 'ts_portfolio_details', TRUE);

    echo '
    <h4>' . esc_html__( 'Portfolio details','videofly' ) . '</h4>
    <table width="450"><tr class="portfolio-client">
                <td>' . esc_html__( 'Client','videofly' ) . '</td>
                <td>
                    <input type="text" class="client" name="portfolio_details[client]" value="'.@$portfolio_details['client'].'" style="width: 100%" />
                </td>
            </tr>
            <tr class="portfolio-services">
                <td>' . esc_html__( 'Services','videofly' ) . '</td>
                <td>
                    <input type="text" class="services" name="portfolio_details[services]" value="'.@$portfolio_details['services'].'" style="width: 100%" />
                </td>
            </tr>
            <tr class="portfolio-client-url">
                <td>' . esc_html__( 'Project URL','videofly' ) . '</td>
                <td>
                    <input type="text" class="client_url" name="portfolio_details[project_url]" value="'.@$portfolio_details['project_url'].'" style="width: 100%" />
                </td>
            </tr></table><br><br>';
    echo '<input type="button" class="button" id="add-item" value="' .esc_html__('Add New Portfolio Item','videofly'). '" /><br/>';
    echo '<ul id="portfolio-items">';

    $portfolio_editor = '';

    if (!empty($portfolio_items)) {
        $index = 0;
        foreach ($portfolio_items as $portfolio_item_id => $portfolio_item) {
            $index++;
            $is_image = ($portfolio_item['item_type'] == 'i') ? 'checked="checked"' : '';
            $is_video = ($portfolio_item['item_type'] == 'v') ? 'checked="checked"' : '';

            $portfolio_editor .= '
            <li class="portfolio-item">
            <div class="sortable-meta-element"><span class="tab-arrow icon-up"></span> <span class="portfolio-item-tab">'.($portfolio_item['slide_title'] ? $portfolio_item['slide_title'] : 'Slide ' . $index).'</span></div>
            <table class="hidden">
            <tr>
                <td>' . esc_html__( 'Item type','videofly' ) . '</td>
                <td>
                    <label for="item-type-image-'.$portfolio_item_id.'">
                        <input type="radio" class="item-type-image" name="portfolio['.$portfolio_item_id.'][item_type]" value="i" checked="checked" id="item-type-image-'.$portfolio_item_id.'" '.$is_image.'/> Image
                    </label>
                    <label for="item-type-video-'.$portfolio_item_id.'">
                        <input type="radio" class="item-type-video" name="portfolio['.$portfolio_item_id.'][item_type]" value="v" id="item-type-video-'.$portfolio_item_id.'" '.$is_video.'/> Video
                    </label>
                </td>
            </tr>
            <tr>
                <td>' . esc_html__( 'Title','videofly' ) . '</td>
                <td>
                    <input type="text" class="slide_title" name="portfolio['.$portfolio_item_id.'][slide_title]" value="'.$portfolio_item['slide_title'].'" style="width: 100%" />
                </td>
            </tr>
            <tr class="portfolio-embed '.( $is_image ? 'hidden' : '' ).'">
                <td valign="top">' . esc_html__( 'Embed/Video URL<br/>(<a href="http://codex.wordpress.org/Embeds#Can_I_Use_Any_URL_With_This.3F" target="_blank">supported sites</a>)','videofly' ) . '</td>
                <td>
                    <textarea name="portfolio['.$portfolio_item_id.'][embed]" cols="60" rows="5">'.$portfolio_item['embed'].'</textarea>
                </td>
            </tr>
            <tr class="portfolio-description '.( $is_video ? 'hidden' : '' ).'">
                <td valign="top">' . esc_html__( 'Description','videofly' ) . '</td>
                <td>
                    <textarea class="slide_description" name="portfolio['.$portfolio_item_id.'][description]" cols="60" rows="5">'.$portfolio_item['description'].'</textarea>
                </td>
            </tr>
            <tr class="portfolio-image-url '.( $is_video ? 'hidden' : '' ).'">
                <td>' . esc_html__( 'Image URL','videofly' ) . '</td>
                <td>
                    <input type="text" class="slide_url" name="portfolio['.$portfolio_item_id.'][item_url]" value="'.$portfolio_item['item_url'].'" />
                    <input type="hidden" class="slide_media_id" name="portfolio['.$portfolio_item_id.'][media_id]" value="'.$portfolio_item['media_id'].'" />
                    <input type="button" id="upload-'.$portfolio_item_id.'" class="button ts-upload-slide" value="' .esc_html__( 'Upload','videofly' ). '" />
                </td>
            </tr>
            <tr class="portfolio-redirect-url '.( $is_video ? 'hidden' : '' ).'">
                <td>' . esc_html__( 'Redirect to URL','videofly' ) . '</td>
                <td>
                    <input type="text" class="redirect_to_url" name="portfolio['.$portfolio_item_id.'][redirect_to_url]" value="'.$portfolio_item['redirect_to_url'].'" style="width: 100%" />
                </td>
            </tr>
            <tr>
                <td></td><td><input type="button" class="button button-primary remove-item" value="'.esc_html__('Remove','videofly').'" /></td>
            </tr>
            </table>

            </li>';
        }
    }

    echo vdf_var_sanitize($portfolio_editor);

    echo '</ul>';

    echo '<script id="portfolio-items-template" type="text/template">';
    echo '<li class="portfolio-item">
    <div class="sortable-meta-element"><span class="tab-arrow icon-up"></span> <span class="portfolio-item-tab">Slide {{slide-number}}</span></div>
    <table>
        <tr>
            <td>' . esc_html__( 'Item type','videofly' ) . '</td>
            <td>
                <label for="item-type-image-{{item-id}}">
                    <input type="radio" class="item-type-image" name="portfolio[{{item-id}}][item_type]" value="i" checked="checked" id="item-type-image-{{item-id}}"/> Image
                </label>
                <label for="item-type-video-{{item-id}}">
                    <input type="radio" class="item-type-video" name="portfolio[{{item-id}}][item_type]" value="v" id="item-type-video-{{item-id}}" /> Video
                </label>
            </td>
        </tr>
        <tr>
            <td>' . esc_html__( 'Title','videofly' ) . '</td>
            <td>
                <input type="text" class="slide_title" name="portfolio[{{item-id}}][slide_title]" value="" style="width: 100%" />
            </td>
        </tr>
        <tr class="portfolio-embed hidden">
            <td valign="top">' . esc_html__( 'Embed/Video URL<br/>(<a href="http://codex.wordpress.org/Embeds#Can_I_Use_Any_URL_With_This.3F" target="_blank">supported sites</a>)','videofly' ) . '</td>
            <td>
                <textarea name="portfolio[{{item-id}}][embed]" cols="60" rows="5"></textarea>
            </td>
        </tr>
        <tr class="portfolio-description">
            <td valign="top">' . esc_html__( 'Description','videofly' ) . '</td>
            <td>
                <textarea class="slide_description" name="portfolio[{{item-id}}][description]" cols="60" rows="5"></textarea>
            </td>
        </tr>
        <tr class="portfolio-image-url">
            <td>' . esc_html__( 'Image URL','videofly' ) . '</td>
            <td>
                <input type="text" class="slide_url" name="portfolio[{{item-id}}][item_url]" value="" />
                <input type="hidden" class="slide_media_id" name="portfolio[{{item-id}}][media_id]" value="" />
                <input type="button" id="upload-{{item-id}}" class="button ts-upload-slide" value="' .esc_html__( 'Upload','videofly' ). '" />
            </td>
        </tr>
        <tr class="portfolio-redirect-url">
            <td>' . esc_html__( 'Redirect to URL','videofly' ) . '</td>
            <td>
                <input type="text" class="redirect_to_url" name="portfolio[{{item-id}}][redirect_to_url]" value="" style="width: 100%" />
            </td>
        </tr>
        <tr>
            <td></td><td><input type="button" class="button button-primary remove-item" value="'.esc_html__('Remove','videofly').'" /></td>
        </tr>
    </table></li>';

    echo '</script>';
?>
    <script>
    jQuery(document).ready(function($) {
        var portfolio_items = $("#portfolio-items > li").length;

        // sortable portfolio items
        $("#portfolio-items").sortable();
        //$("#portfolio-items").disableSelection();

        $(document).on('change', '.slide_title', function(event) {
            event.preventDefault();
            var _this = $(this);
            _this.closest('.portfolio-item').find('.portfolio-item-tab').text(_this.val());
        });

        // Content type switcher
        $(document).on('click', '.item-type-image', function(event) {
            var _this = $(this);
            _this.closest('table').find('.portfolio-embed').hide();
            _this.closest('table').find('.portfolio-description').show();
            _this.closest('table').find('.portfolio-image-url').show();
            _this.closest('table').find('.portfolio-redirect-url').show();
        });

        $(document).on('click', '.item-type-video', function(event) {
            var _this = $(this);
            _this.closest('table').find('.portfolio-embed').show();
            _this.closest('table').find('.portfolio-description').hide();
            _this.closest('table').find('.portfolio-image-url').hide();
            _this.closest('table').find('.portfolio-redirect-url').hide();
        });

        // Media uploader
        var items = $('#portfolio-items'),
            slideTempalte = $('#portfolio-items-template').html(),
            custom_uploader = {};

        if (typeof wp.media.frames.file_frame == 'undefined') {
            wp.media.frames.file_frame = {};
        }

        $(document).on('click', '#add-item', function(event) {
            event.preventDefault();
            portfolio_items++;
            var sufix = new Date().getTime();
            var item_id = new RegExp('{{item-id}}', 'g');
            var item_number = new RegExp('{{slide-number}}', 'g');

            var template = slideTempalte.replace(item_id, sufix).replace(item_number, portfolio_items);
            items.append(template);
        });

        $(document).on('click', '.remove-item', function(event) {
            event.preventDefault();
            $(this).closest('li').remove();
            portfolio_items--;
        });


        $(document).on('click', '.ts-upload-slide', function(e) {
            e.preventDefault();

            var _this     = $(this),
                target_id = _this.attr('id'),
                media_id  = _this.closest('li').find('.slide_media_id').val();

            //If the uploader object has already been created, reopen the dialog
            if (custom_uploader[target_id]) {
                custom_uploader[target_id].open();
                return;
            }

            //Extend the wp.media object
            custom_uploader[target_id] = wp.media.frames.file_frame[target_id] = wp.media({
                title: 'Choose Image',
                button: {
                    text: 'Choose Image'
                },
                library: {
                    type: 'image'
                },
                multiple: false,
                selection: [media_id]
            });

            //When a file is selected, grab the URL and set it as the text field's value
            custom_uploader[target_id].on('select', function() {
                var attachment = custom_uploader[target_id].state().get('selection').first().toJSON();
                var item = _this.closest('table');

                item.find('.slide_url').val(attachment.url);
                item.find('.slide_media_id').val(attachment.id);
            });

            //Open the uploader dialog
            custom_uploader[target_id].open();
        });
    });
    </script>
<?php
}

// saving slider
function ts_portfolio_save_postdata( $post_id )
{
    global $post;

    if ( isset($post->post_type) && @$post->post_type != 'portfolio' ) {
        return;
    }

    if ( ! isset( $_POST['ts_portfolio_nonce'] ) ||
         ! wp_verify_nonce( $_POST['ts_portfolio_nonce'], plugin_basename( __FILE__ ) )
    ) return;

    if( !current_user_can( 'edit_post', $post_id ) ) return;

    // array containing filtred items
    $portfolio_items = array();

    if ( isset( $_POST['portfolio'] ) ) {
        if ( is_array( $_POST['portfolio'] ) && !empty( $_POST['portfolio'] ) ) {
            foreach ( $_POST['portfolio'] as $item_id => $portfolio_item ) {

                $p = array();
                $p['item_id']   = $item_id;

                $p['item_type'] = isset($portfolio_item['item_type']) ?
                                esc_attr($portfolio_item['item_type']) : '';

                $p['item_type'] = isset($portfolio_item['item_type']) &&
                                ( $portfolio_item['item_type'] === 'i' || $portfolio_item['item_type'] === 'v' ) ?
                                $portfolio_item['item_type'] : 'i';

                $p['slide_title'] = isset($portfolio_item['slide_title']) ?
                                esc_textarea($portfolio_item['slide_title']) : '';

                $p['embed'] = isset($portfolio_item['embed']) ?
                            esc_textarea($portfolio_item['embed']) : '';

                $p['description'] = isset($portfolio_item['description']) ?
                                esc_textarea($portfolio_item['description']) : '';

                $p['item_url'] = isset($portfolio_item['item_url']) ?
                                esc_url($portfolio_item['item_url']) : '';

                $p['media_id'] = isset($portfolio_item['media_id']) ?
                                esc_attr($portfolio_item['media_id']) : '';

                $p['redirect_to_url'] = isset($portfolio_item['redirect_to_url']) ?
                                    esc_url($portfolio_item['redirect_to_url']) : '';

                $portfolio_items[] = $p;
            }
        }
    }
    if(isset($_POST['portfolio_details'])){
        $portfolio_details = $_POST['portfolio_details'];
    }

    update_post_meta( $post_id, 'ts_portfolio', $portfolio_items );
    update_post_meta( $post_id, 'ts_portfolio_details', $portfolio_details );
}

add_action( 'add_meta_boxes', 'ts_pricing_table_add_custom_box' );
add_action( 'save_post', 'ts_pricing_table_save_postdata' );

function ts_pricing_table_add_custom_box()
{
    add_meta_box(
        'ts_pricing_table',
        'Pricing table',
        'ts_pricing_table_custom_box',
        'ts_pricing_table'
    );
}

function ts_pricing_table_custom_box( $post )
{
    // Use nonce for verification
    wp_nonce_field( plugin_basename( __FILE__ ), 'ts_pricing_table_nonce' );
    $vdf_pricing_table_items = get_post_meta($post->ID, 'ts_pricing_table', TRUE);
    $vdf_pricing_table_details = get_post_meta($post->ID, 'ts_pricing_table_details', TRUE);

    echo '
    <h4>' . esc_html__( 'Pricing table details','videofly' ) . '</h4>
    <table width="450"><tr class="ts_pricing_table-price">
                <td>' . esc_html__( 'Price','videofly' ) . '</td>
                <td>
                    <input type="text" class="price" name="ts_pricing_table_details[price]" value="'.@$vdf_pricing_table_details['price'].'" style="width: 100%" />
                </td>
            </tr>
            <tr class="ts_pricing_table-details">
                <td>' . esc_html__( 'Description','videofly' ) . '</td>
                <td>
                    <input type="text" class="description" name="ts_pricing_table_details[description]" value="'.@$vdf_pricing_table_details['description'].'" style="width: 100%" />
                </td>
            </tr>
            <tr class="ts_pricing_table-currency">
                <td>' . esc_html__( 'Currency','videofly' ) . '</td>
                <td>
                    <input type="text" class="currency" name="ts_pricing_table_details[currency]" value="'.@$vdf_pricing_table_details['currency'].'" style="width: 100%" />
                </td>
            </tr>
            <tr class="ts_pricing_table-period">
                <td>' . esc_html__( 'Period','videofly' ) . '</td>
                <td>
                    <input type="text" class="pricing-period" name="ts_pricing_table_details[period]" value="'.@$vdf_pricing_table_details['period'].'" style="width: 100%" />
                </td>
            </tr>
            <tr class="ts_pricing_table-url">
                <td>' . esc_html__( 'Button URL','videofly' ) . '</td>
                <td>
                    <input type="text" class="pricing-url" name="ts_pricing_table_details[url]" value="'.@$vdf_pricing_table_details['url'].'" style="width: 100%" />
                </td>
            </tr>
            <tr class="ts_pricing_table-button-text">
                <td>' . esc_html__( 'Button Text','videofly' ) . '</td>
                <td>
                    <input type="text" class="pricing-text" name="ts_pricing_table_details[button_text]" value="'.@$vdf_pricing_table_details['button_text'].'" style="width: 100%" />
                </td>
            </tr>
            <tr class="ts_pricing_table-button-text">
                <td>' . esc_html__( 'Set as featured','videofly' ) . '</td>
                <td>
                    <input type="radio" class="pricing-featured" name="ts_pricing_table_details[featured]" value="yes"'.  checked(@$vdf_pricing_table_details['featured'], 'yes', false). ' /> Yes
                    <input type="radio" class="pricing-featured" name="ts_pricing_table_details[featured]" value="no" '. checked(@$vdf_pricing_table_details['featured'], 'no', false) . checked(@$vdf_pricing_table_details['featured'], '', false) . ' /> No
                </td>
            </tr></table><br><br>';
    echo '<input type="button" class="button" id="add-item" value="' .esc_html__('Add New Pricing table Item','videofly'). '" /><br/>';
    echo '<ul id="ts_pricing_table-items">';

    $vdf_pricing_table_editor = '';

    if (!empty($vdf_pricing_table_items)) {
        $index = 0;
        foreach ($vdf_pricing_table_items as $vdf_pricing_table_item_id => $vdf_pricing_table_item) {
            $index++;

            $vdf_pricing_table_editor .= '
            <li class="ts_pricing_table-item">
            <div class="sortable-meta-element"><span class="tab-arrow icon-down"></span> <span class="ts_pricing_table-item-tab ts-multiple-item-tab">'.($vdf_pricing_table_item['item_title'] ? $vdf_pricing_table_item['item_title'] : 'Item ' . $index).'</span></div>
            <table class="hidden">
            <tr>
                <td>' . esc_html__( 'Title','videofly' ) . '</td>
                <td>
                    <input type="text" class="item_title" name="ts_pricing_table['.$vdf_pricing_table_item_id.'][item_title]" value="'.$vdf_pricing_table_item['item_title'].'" style="width: 100%" />
                </td>
            </tr>
            <tr>
                <td></td><td><input type="button" class="button button-primary remove-item" value="'.esc_html__('Remove','videofly').'" /></td>
            </tr>
            </table>

            </li>';
        }
    }

    echo vdf_var_sanitize($vdf_pricing_table_editor);

    echo '</ul>';

    echo '<script id="ts_pricing_table-items-template" type="text/template">';
    echo '<li class="ts_pricing_table-item">
    <div class="sortable-meta-element"><span class="tab-arrow icon-down"></span> <span class="ts_pricing_table-item-tab ts-multiple-item-tab">Pricing table {{slide-number}}</span></div>
    <table>
        <tr>
            <td>' . esc_html__( 'Title','videofly' ) . '</td>
            <td>
                <input type="text" class="item_title" name="ts_pricing_table[{{item-id}}][item_title]" value="" style="width: 100%" />
            </td>
        </tr>
        <tr>
            <td></td><td><input type="button" class="button button-primary remove-item" value="'.esc_html__('Remove','videofly').'" /></td>
        </tr>
    </table></li>';

    echo '</script>';
?>
    <script>
    jQuery(document).ready(function($) {
        var ts_pricing_table_items = $("#ts_pricing_table-items > li").length;

        // sortable ts_pricing_table items
        $("#ts_pricing_table-items").sortable();
        //$("#ts_pricing_table-items").disableSelection();

        $(document).on('change', '.item_title', function(event) {
            event.preventDefault();
            var _this = $(this);
            _this.closest('.ts_pricing_table-item').find('.ts_pricing_table-item-tab').text(_this.val());
        });

        // Media uploader
        var items = $('#ts_pricing_table-items');
            slideTempalte = $('#ts_pricing_table-items-template').html();

        // Remove item
        $(document).on('click', '.remove-item', function(event) {
            event.preventDefault();
            $(this).closest('li').remove();
            ts_pricing_table_items--;
        });

        $(document).on('click', '#add-item', function(event) {
            event.preventDefault();
            ts_pricing_table_items++;
            var sufix = new Date().getTime();
            var item_id = new RegExp('{{item-id}}', 'g');
            var item_number = new RegExp('{{slide-number}}', 'g');

            var template = slideTempalte.replace(item_id, sufix).replace(item_number, ts_pricing_table_items);
            items.append(template);
        });
    });
    </script>
<?php
}

// saving slider
function ts_pricing_table_save_postdata( $post_id )
{
    global $post;

    if ( isset($post->post_type) && @$post->post_type != 'ts_pricing_table' ) {
        return;
    }

    if ( ! isset( $_POST['ts_pricing_table_nonce'] ) ||
         ! wp_verify_nonce( $_POST['ts_pricing_table_nonce'], plugin_basename( __FILE__ ) )
    ) return;

    if( !current_user_can( 'edit_post', $post_id ) ) return;

    // array containing filtred items
    $vdf_pricing_table_items = array();

    if ( isset( $_POST['ts_pricing_table'] ) ) {
        if ( is_array( $_POST['ts_pricing_table'] ) && !empty( $_POST['ts_pricing_table'] ) ) {
            foreach ( $_POST['ts_pricing_table'] as $item_id => $vdf_pricing_table_item ) {

                $p = array();
                $p['item_id']   = $item_id;


                $p['item_title'] = isset($vdf_pricing_table_item['item_title']) ?
                                esc_textarea($vdf_pricing_table_item['item_title']) : '';

                $vdf_pricing_table_items[] = $p;
            }
        }
    }
    if(isset($_POST['ts_pricing_table_details'])){
        $vdf_pricing_table_details = $_POST['ts_pricing_table_details'];
    }

    update_post_meta( $post_id, 'ts_pricing_table', $vdf_pricing_table_items );
    update_post_meta( $post_id, 'ts_pricing_table_details', $vdf_pricing_table_details );
}

/**
// Create boxes for video post format
*/
add_action( 'add_meta_boxes', 'ts_video_post_add_custom_box' );
function ts_video_post_add_custom_box()
{
	add_meta_box(
        'video_embed',
        esc_html__('Video embed', 'videofly'),
        'ts_video_post_options_custom_box',
        'post'
    );
}

function ts_video_post_options_custom_box($post)
{
	// Use nonce for verification
	wp_nonce_field( plugin_basename( __FILE__ ), 'ts_video_nonce' );
	$video_post = get_post_meta($post->ID, 'video_embed' , TRUE);

	if (!$video_post) {
		$video_post = '';
	}

	echo '<table>
		<tr valign="top">
			<td>' . esc_html__('Video embed code', 'videofly') . '</td>
			<td>
				<textarea name="video_embed" cols="60" rows="5">'.esc_attr(@$video_post).'</textarea>
			</td>
		</tr>
		</table>';

}
// saving video embed data
function ts_video_post_postdata( $post_id )
{
	global $post;

    if ( isset($post->post_type) && @$post->post_type != 'post' ) {
        return;
    }

	if (!isset( $_POST['ts_video_nonce'] ) ||
		!wp_verify_nonce( $_POST['ts_video_nonce'], plugin_basename( __FILE__ ) )
	) return;


	// array containing filtred slides

    $video_embed_code = $_POST['video_embed'];
    update_post_meta( $post_id, 'video_embed', $video_embed_code );
}
add_action( 'save_post', 'ts_video_post_postdata' );


// Create boxes for audio post format

add_action( 'add_meta_boxes', 'ts_audio_post_add_custom_box' );
function ts_audio_post_add_custom_box()
{
	add_meta_box(
        'audio_embed',
        esc_html__('Audio embed', 'videofly'),
        'ts_audio_post_options_custom_box',
        'post'
    );
}

function ts_audio_post_options_custom_box($post)
{
	// Use nonce for verification
	wp_nonce_field( plugin_basename( __FILE__ ), 'ts_audio_nonce' );
	$audio_post = get_post_meta($post->ID, 'audio_embed' , TRUE);

	if (!$audio_post) {
		$audio_post = '';
	}

	echo '<table>
		<tr valign="top">
			<td>' . esc_html__('Audio embed code', 'videofly') . '</td>
			<td>
				<textarea name="audio_embed" cols="60" rows="5">'.esc_attr(@$audio_post).'</textarea>
			</td>
		</tr>
		</table>';

}
// saving audio post embed data
function ts_audio_post_postdata( $post_id )
{
	global $post;

    if ( isset($post->post_type) && @$post->post_type != 'post' ) {
        return;
    }

	if (!isset( $_POST['ts_audio_nonce'] ) ||
		!wp_verify_nonce( $_POST['ts_audio_nonce'], plugin_basename( __FILE__ ) )
	) return;


	// array containing filtred slides

    $audio_embed_code = $_POST['audio_embed'];
    update_post_meta( $post_id, 'audio_embed', $audio_embed_code );
}
add_action( 'save_post', 'ts_audio_post_postdata' );


/**************************************************************************
 ************** Select layouts for posts and pages ************************
 *************************************************************************/

add_action( 'add_meta_boxes', 'ts_layout_custom_boxes' );
add_action( 'save_post', 'ts_layout_save_postdata' );

/* Adds a box to the main column on the Post and Page edit screens */
function ts_layout_custom_boxes() {
    $screens = array( 'page' );
    foreach ($screens as $screen) {
        add_meta_box(
            'ts_layout_id',
            esc_html__( 'Custom Layout', 'videofly' ),
            'ts_layout_selector_custom_box',
            $screen
        );
    }
    // Add the header and footer meta box
    add_meta_box(
        'ts_header_and_footer',
        esc_html__( 'Header & Footer', 'videofly' ),
        'ts_header_and_footer_custom_box',
        'page',
        'normal',
        'high'
    );
    // Add the page options meta box
    add_meta_box(
        'ts_page_options',
        esc_html__( 'Page options', 'videofly' ),
        'ts_page_options_custom_box',
        'page',
        'normal',
        'high'
    );
    // Add the post options meta box
    add_meta_box(
        'ts_post_options',
        esc_html__( 'Post options', 'videofly' ),
        'ts_post_options_custom_box',
        'post',
        'normal',
        'high'
    );

     // Add the vide post options meta box
    add_meta_box(
        'ts_post_options',
        esc_html__( 'Post options', 'videofly' ),
        'ts_post_options_custom_box',
        'video',
        'normal',
        'high'
    );
    // Add the post type gallery options meta box
    add_meta_box(
        'ts_post_options',
        esc_html__( 'Post options', 'videofly' ),
        'ts_post_options_custom_box',
       'ts-gallery',
        'normal',
        'high'
    );

    $sidebar_screens = array( 'page', 'post', 'portfolio', 'product', 'video', 'event', 'ts-gallery' );

    foreach ($sidebar_screens as $screen) {
        add_meta_box(
            'ts_sidebar',
            esc_html__( 'Layout', 'videofly' ),
            'ts_sidebar_custom_box',
            $screen,
            'side',
            'low'
        );
    }

    if (is_admin()) {

        wp_enqueue_style( 'farbtastic' );
        wp_enqueue_script( 'farbtastic' );

        // Layout builder styles
        wp_enqueue_style(
            'jquery-ui-custom',
            get_template_directory_uri() . '/admin/css/layout-builder.css',
            array(),
            VIDEOFLY_VERSION
        );

        // Layout builder
        wp_enqueue_script(
            'handlebars',
            get_template_directory_uri() . '/admin/js/handlebars.js',
            array('jquery','jquery-ui-core', 'jquery-ui-sortable'),
            VIDEOFLY_VERSION,
            true
        );
        // Layout builder
        wp_enqueue_script(
            'layout-builder',
            get_template_directory_uri() . '/admin/js/layout-builder.js',
            array('handlebars'),
            VIDEOFLY_VERSION,
            true
        );

        // Noty
        wp_enqueue_script(
            'noty',
            get_template_directory_uri() . '/admin/js/noty/jquery.noty.js',
            array('jquery'),
            VIDEOFLY_VERSION,
            true
        );

        // Noty layouts
        wp_enqueue_script(
            'noty-top',
            get_template_directory_uri() . '/admin/js/noty/layouts/bottomCenter.js',
            array('jquery', 'noty'),
            VIDEOFLY_VERSION,
            true
        );

        // Noty theme
        wp_enqueue_script(
            'noty-theme',
            get_template_directory_uri() . '/admin/js/noty/themes/default.js',
            array('jquery', 'noty', 'noty-top'),
            VIDEOFLY_VERSION,
            true
        );
    }
}

/* Prints the box content */
function ts_layout_selector_custom_box( $post ) {

    $template_id = Template::get_template_info('page', 'id');
    $template_name = Template::get_template_info('page', 'name');

    echo videofly_template_modals( 'page', $template_id, $template_name );
    vdf_layout_wrapper(Template::edit($post->ID));
}

function ts_page_options_custom_box( $post )
{
  fields::logicMetaRadio('page_settings', 'hide_title', fields::get_value($post->ID, 'page_settings', 'hide_title', true), 'Hide title for this post', esc_html__('If set to yes, this option will hide the title of the post on this specific post','videofly') );
  fields::logicMetaRadio('page_settings', 'hide_meta', fields::get_value($post->ID, 'page_settings', 'hide_meta', true), 'Hide meta for this post', esc_html__('If set to yes, this option will hide the meta of the post on this specific post','videofly') );
  fields::logicMetaRadio('page_settings', 'hide_social_sharing', fields::get_value($post->ID, 'page_settings', 'hide_social_sharing', true), 'Hide social sharing for this post', esc_html__('If set to yes, this option will hide the social sharing buttons of the post on this specific post','videofly') );
  fields::logicMetaRadio('page_settings', 'hide_featimg', fields::get_value($post->ID, 'page_settings', 'hide_featimg', true), 'Hide featured image for this post', esc_html__('If set to yes, this option will hide the featured image of the post on this specific post','videofly') );
  fields::logicMetaRadio('page_settings', 'hide_author_box', fields::get_value($post->ID, 'page_settings', 'hide_author_box', true), 'Hide author box for this post', esc_html__('If set to yes, this option will hide the author box of the post on this specific post','videofly') );

}

function ts_post_options_custom_box( $post )
{
    if( $post->post_type == 'post' ) fields::textareaText('post_settings', 'subtitle', fields::get_options_value($post->ID, 'post_settings', 'subtitle', true), 'Add subtitle', esc_html__('Add subtitle to post','videofly') );
    fields::logicMetaRadio('post_settings', 'hide_title', fields::get_value($post->ID, 'post_settings', 'hide_title', true), 'Hide title for this post', esc_html__('If set to yes, this option will hide the title of the post on this specific post','videofly') );
    fields::logicMetaRadio('post_settings', 'hide_related', fields::get_value($post->ID, 'post_settings', 'hide_related', true), 'Hide related articles for this post', esc_html__('If set to yes, this option will hide the related articles of the post on this specific post','videofly') );
    fields::logicMetaRadio('post_settings', 'hide_meta', fields::get_value($post->ID, 'post_settings', 'hide_meta', true), 'Hide meta for this post', esc_html__('If set to yes, this option will hide the meta of the post on this specific post','videofly') );
    fields::logicMetaRadio('post_settings', 'hide_social_sharing', fields::get_value($post->ID, 'post_settings', 'hide_social_sharing', true), 'Hide social sharing for this post', esc_html__('If set to yes, this option will hide the social sharing buttons of the post on this specific post','videofly') );

    if ( $post->post_type == 'video' ) {
        $singls = array(
            'single_style1' => get_template_directory_uri() . '/images/options/single_style1.png',
            'single_style2' => get_template_directory_uri() . '/images/options/single_style2.png'
        );

        $options = get_option('videofly_single_post');
        $singleLayoutStyle = isset($options['single_layout_video']) ? $options['single_layout_video'] : 'single_style1';

        fields::radioImageMeta('post_settings', 'single_layout_video', $singls , 2, $singleLayoutStyle, 'Single layout style', esc_html__('Select the single layout you want to use.','videofly') );
    }

    if( $post->post_type == 'post' ){
        fields::logicMetaRadio('post_settings', 'hide_featimg', fields::get_value($post->ID, 'post_settings', 'hide_featimg', true), 'Hide featured image for this post', esc_html__('If set to yes, this option will hide the featured image of the post on this specific post','videofly') );
    }

    fields::logicMetaRadio('post_settings', 'hide_author_box', fields::get_value($post->ID, 'post_settings', 'hide_author_box', true), 'Hide author box for this post', esc_html__('If set to yes, this option will hide the author box of the post on this specific post','videofly') );
}

function ts_header_and_footer_custom_box( $post )
{
    $header_footer = get_post_meta( $post->ID, 'ts_header_and_footer', true );
    $breadcrumbs = get_option('videofly_single_post', array('breadcrumbs' => 'y'));
    $breadcrumbs_clean = (isset($breadcrumbs['breadcrumbs']) && $breadcrumbs['breadcrumbs'] === 'y' ) ? 0 : 1;

    if( isset($header_footer['breadcrumbs']) ){
    	$disable_breadcrumbs = ( $header_footer['breadcrumbs'] === 1 ) ? 'checked="checked"' : '';
    }else{
        $disable_breadcrumbs = ($breadcrumbs_clean === 1) ? 'checked="checked"' : '';
    }

    if ( $header_footer ) {
        $disable_header = ( $header_footer['disable_header'] === 1 ) ? 'checked="checked"' : '';
        $disable_footer = ( $header_footer['disable_footer'] === 1 ) ? 'checked="checked"' : '';

    } else {
        $disable_header = '';
        $disable_footer = '';
    }

    echo '<p>
            <label class="switch" for="ts-disable-header">
              <input id="ts-disable-header" class="switch-input" name="ts_header_footer[disable_header]" type="checkbox" value="1" '.$disable_header.'>
              <span class="switch-label" data-on="'. esc_html__('Yes','videofly') . '" data-off="' . esc_html__('No','videofly') . '"></span>
              <span class="switch-handle"></span>
            </label>
            '.esc_html__('Disable header', 'videofly').'
            <div class="ts-option-description">
				'.esc_html__('This options will disable the default global header. You can use it if you want to create a custom header for this page using the layout builder. Global (default) header options are in a tab in the theme options panel. (in the menu on the left, last icon).', 'videofly').'
            </div>
        </p>
        <p>
            <label class="switch" for="ts-disable-footer">
              <input id="ts-disable-footer" class="switch-input" type="checkbox" name="ts_header_footer[disable_footer]" value="1" '.$disable_footer.'>
              <span class="switch-label" data-on="'. esc_html__('Yes','videofly') . '" data-off="' . esc_html__('No','videofly') . '"></span>
              <span class="switch-handle"></span>
            </label>
            '.esc_html__('Disable footer', 'videofly').'
            <div class="ts-option-description">
				'.esc_html__('This options will disable the default global footer. You can use it if you want to create a custom footer for this page using the layout builder. Global (default) footer options are in a tab in the theme options panel. (in the menu on the left, last icon).', 'videofly').'
            </div>
        </p>
        <p>
            <label class="switch" for="ts-disable-breadcrumbs">
              <input id="ts-disable-breadcrumbs" class="switch-input" type="checkbox" name="ts_header_footer[breadcrumbs]" value="1" '.$disable_breadcrumbs.'>
              <span class="switch-label" data-on="'. esc_html__('Yes','videofly') . '" data-off="' . esc_html__('No','videofly') . '"></span>
              <span class="switch-handle"></span>
            </label>
            '.esc_html__('Disable breadcrumbs', 'videofly').'
            <div class="ts-option-description">
				'.esc_html__('Hide the breadcrumbs in this page', 'videofly').'
            </div>
        </p>';


}

/* When the post is saved, saves our custom data */
function ts_layout_save_postdata( $post_id ) {

	$post_types = array( 'page', 'post', 'portfolio', 'product', 'video', 'event', 'ts-gallery' );

	// First we need to check if the current user is authorised to do this action.
	if ( in_array(get_post_type($post_id), $post_types) ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return $post_id;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		// Secondly we need to check if the user intended to change this value.
		if ( ! isset( $_POST['ts_layout_nonce_filed'] ) || ! wp_verify_nonce( @$_POST['ts_layout_nonce_filed'], 'ts_layout_nonce' ) ) return $post_id;

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;

		// Thirdly we can save the value to the database
		$post_ID = @$_POST['post_ID'];
		$sidebar = @$_POST['ts_sidebar'];

		$new_sidebar_options = array(
			'position' => '',
			'size' => ''
		);

		if ( is_array( $sidebar ) &&
			 isset( $sidebar['position'] ) &&
			 isset( $sidebar['size'] ) &&
             isset( $sidebar['id'] )
			) {

			$valid_positions = array( 'none', 'left', 'right' );
			$valid_sizes = array( '1-3', '1-4' );

			if ( in_array( $sidebar['position'], $valid_positions ) ) {
				$new_sidebar_options['position'] = $sidebar['position'];
			} else {
				$new_sidebar_options['position'] = 'none';
			}

			if ( in_array( $sidebar['size'], $valid_sizes ) ) {
				$new_sidebar_options['size'] = $sidebar['size'];
			} else {
				$new_sidebar_options['size'] = '1-4';
			}

            $sidebars = ts_get_sidebars();

            if ( array_key_exists( $sidebar['id'], $sidebars ) || $sidebar['id'] == 'main' ) {
                $new_sidebar_options['id'] = $sidebar['id'];
            } else {
                $new_sidebar_options['id'] = 0;
            }

			update_post_meta( $post_ID, 'ts_sidebar', $new_sidebar_options );
		}

		// Get and save header meta box options
        $header_footer = @$_POST['ts_header_footer'];

        $header_footer_options = array(
            'disable_header' => 0,
            'disable_footer' => 0,
            'breadcrumbs' => 0
        );

        if ( isset($header_footer['disable_header']) ) {
            $header_footer_options['disable_header'] = 1;
        }

        if ( isset($header_footer['disable_footer']) ) {
            $header_footer_options['disable_footer'] = 1;
        }

        if ( isset($header_footer['breadcrumbs']) ) {
            $header_footer_options['breadcrumbs'] = 1;
        }

        update_post_meta( $post_ID, 'ts_header_and_footer', $header_footer_options );


		// Get and save page options meta box options
        $page_settings = @$_POST['page_settings'];

        update_post_meta( $post_ID, 'page_settings', $page_settings );

        // Get and save page options meta box options
        $post_settings = @$_POST['post_settings'];

        update_post_meta( $post_ID, 'post_settings', $post_settings );
	}
}

function get_layout_type( $postID = 0 )
{
	$layout_type = get_post_meta( $postID, 'ts_layout_id', true );
}

function ts_sidebar_custom_box( $post ) {

	$sidebar = get_post_meta( $post->ID, 'ts_sidebar', true );

	// IF there are not settings for this specific post, get those from layout settings.
	if ( !isset( $sidebar ) || $sidebar == '' ) {
		if ( get_post_type($post->ID) == 'page' ) {
			$sidebar = fields::get_options_value('videofly_layout', 'page_layout');
		} elseif( get_post_type($post->ID) != 'page' && get_post_type($post->ID) == 'product' ){
			$sidebar = fields::get_options_value('videofly_layout', 'product_layout');
		} else{
			$sidebar = fields::get_options_value('videofly_layout', 'single_layout');
		}
		$sidebar = $sidebar['sidebar'];
	}

	$positions = array(
		'none'  => esc_html__( 'None', 'videofly' ),
		'left'  => esc_html__( 'Left', 'videofly' ),
		'right' => esc_html__( 'Right', 'videofly' )
	);

	$positions_options = '';

	if ( array_key_exists(@$sidebar['position'], $positions) ) {
		foreach ($positions as $option_id => $option) {
			if ( $option_id === $sidebar['position'] ) {
				$positions_options .= '<option value="' . $option_id . '" selected="selected">' . $option.'</option>';
			} else {
				$positions_options .= '<option value="'.$option_id .'">'.$option.'</option>';
			}
		}
	} else {
		foreach ($positions as $option_id => $option) {
			$positions_options .= '<option value="'.$option_id .'">'.$option.'</option>';
		}
	}

	$sizes = array(
		'1-3'  => '1/3',
		'1-4'  => '1/4'
	);

	$size_options = '';

	if ( array_key_exists(@$sidebar['size'], $sizes) ) {
		foreach ($sizes as $size_id => $size) {
			if ( $size_id === $sidebar['size'] ) {
				$size_options .= '<option value="'.$size_id .'" selected="selected">'.$size.'</option>';
			} else {
				$size_options .= '<option value="'.$size_id .'">'.$size.'</option>';
			}
		}
	} else {
		foreach ($sizes as $size_id => $size) {
			$size_options .= '<option value="'.$size_id .'">'.$size.'</option>';
		}
	}

    wp_nonce_field('ts_layout_nonce', 'ts_layout_nonce_filed');

    if ( isset( $sidebar['id'] ) ) {
        $sidebar_id = $sidebar['id'];
    } else {
        $sidebar_id = 0;
    }

    echo '<div id="ts_sidebar_position"><p><strong>'.esc_html__( 'Sidebar position', 'videofly' ).'</strong></p>
		    <ul id="page-sidebar-position-selector" data-selector="#page-sidebar-position" class="imageRadioMetaUl perRow-3 ts-custom-selector">
		       <li><img src="'.get_template_directory_uri() . '/images/options/none.png'.'" data-option="none" class="image-radio-input"></li>
		       <li><img src="'.get_template_directory_uri() . '/images/options/left_sidebar.png'.'" data-option="left" class="image-radio-input"></li>
		       <li><img src="'.get_template_directory_uri() . '/images/options/right_sidebar.png'.'" data-option="right" class="image-radio-input"></li>
		    </ul>
			<select name="ts_sidebar[position]" id="page-sidebar-position" class="hidden">
			' . $positions_options . '
			</select></div>
			<div id="ts_sidebar_size">
			<p><strong>'.esc_html__( 'Sidebar size', 'videofly' ).'</strong></p>
			<select id="ts_sidebar_size" name="ts_sidebar[size]">
			' . $size_options . '
			</select>
			</div><div id="ts_sidebar_sidebars">
            <p><strong>'.esc_html__('Sidebar name', 'videofly').'</strong></p>
            '.ts_sidebars_drop_down($sidebar_id, '', 'ts_sidebar[id]') . '</div>';

}//end function ts_sidebar_custom_box


// Custom boxes defaults
$videofly_single_post = get_option('videofly_single_post');
$global_hide_author_box = (isset($videofly_single_post['display_author_box']) && $videofly_single_post['display_author_box'] === 'y') ? 'yes' : 'no';
$videoLayout = isset($videofly_single_post['single_layout_video']) ? $videofly_single_post['single_layout_video'] : 'single_style1';

$post_custom_box_defaults = array(
		'hide_title' => 'no',
		'hide_meta' => 'no',
		'hide_related' => 'no',
		'hide_social_sharing' => 'no',
		'hide_featimg' => 'no',
		'hide_author_box' => $global_hide_author_box,
		'background_img' => '',
        'background_position' => 'left',
		'single_layout_video' => $videoLayout,
		'subtitle' => ''
	);
$page_custom_box_defaults = array(
		'hide_title' => 'no',
		'hide_meta' => 'yes',
		'hide_social_sharing' => 'no',
		'hide_featimg' => 'no',
		'hide_author_box' => $global_hide_author_box,
		'background_img' => '',
		'background_position' => 'left'
	);

if( false === get_option( 'post_settings_defaults' ) && false === get_option( 'page_settings_defaults' ) ) {
    //delete_option('post_settings_defaults');
	add_option( 'post_settings_defaults', $post_custom_box_defaults);
	add_option( 'page_settings_defaults', $page_custom_box_defaults);

} // end custom boxes default
// Function for setting defaults for existing posts
function setMetaForExistingPosts($post){
	$videofly_single_post = get_option('videofly_single_post');
	$global_hide_author_box = (isset($videofly_single_post['display_author_box']) && $videofly_single_post['display_author_box'] === 'y') ? 'yes' : 'no';

	$post_custom_box_defaults = array(
			'hide_title' => 'no',
			'hide_meta' => 'no',
			'hide_related' => 'no',
			'hide_social_sharing' => 'no',
			'hide_featimg' => 'no',
			'hide_author_box' => $global_hide_author_box,
			'background_img' => '',
			'background_position' => 'left',
            'title_position' => 'below',
			'subtitle' => ''
		);
	$page_custom_box_defaults = array(
			'hide_title' => 'no',
			'hide_meta' => 'yes',
			'hide_social_sharing' => 'no',
			'hide_featimg' => 'yes',
			'hide_author_box' => $global_hide_author_box,
			'background_img' => '',
			'background_position' => 'left'
		);

	if( is_object($post) ) {
		$the_ID = get_the_ID();
		$post_type = get_post_type( get_the_ID() );
		$meta_settings = get_post_meta( $the_ID, $post_type . '_settings' );

		if ( $post_type == 'page' ) {
			$meta_to_add = $page_custom_box_defaults;
		}else {
            $meta_to_add = $post_custom_box_defaults;
            $post_type = 'post';
        }

		if ( empty($meta_settings) ) {
			update_post_meta( get_the_ID() , $post_type . '_settings', $meta_to_add);
		}
	}

}
add_action('pre_post_update', 'setMetaForExistingPosts');

//Add the box import/export to page

function ts_custom_box_import_export() {

	add_meta_box( 'ts-import-export', 'Import/Export options', 'ts_html_custom_box_import_export', 'page' );

}
add_action('add_meta_boxes', 'ts_custom_box_import_export');

/***********/

function ts_html_custom_box_import_export($post) {

	if( !function_exists('ts_enc_string') ) {
        echo ('The plugin "Touchsize Custom Posts" is required.');
        return;
    }

	$settings = get_post_meta( $post->ID, 'ts_template', true );
	$settings = ts_enc_string(serialize($settings));


	echo '<table>
			<tr>
				<td><h4>' . esc_html__('Export options', 'videofly') . '</h4>
					<div class="ts-option-description">
						' . esc_html__('This is the export data. Copy this into another page import field and you should get the same builder elements and arrangement.', 'videofly') . '
					</div>

					<textarea name="export_options" cols="60" rows="5">' . $settings . '</textarea>
				</td>
			</tr>
			<tr>
				<td><h4>' . esc_html__('Import options', 'videofly') . '</h4>
					<div class="ts-option-description">
						' . sprintf( esc_html__('This is the import data field. %s BE CAUTIONS, changing anythig here will result in breaking all your page elements and arrangement. Please save your previous data before proceding. %s', 'videofly'), '<b style="color: #Ff0000;">', '</b>' ) . '
					</div>
					<textarea name="import_options" cols="60" rows="5"></textarea>
				</td>
			</tr>
		</table>';

}

function ts_import_export_save_postdata( $post_id ) {

	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
		return $post_id;

    if( !function_exists('ts_enc_string') ) return $post_id;

	if ( 'page' == get_post_type($post_id) && ! current_user_can( 'edit_page', $post_id ) ) {
		  return $post_id;
	} elseif( ! current_user_can( 'edit_post', $post_id ) ) {
		return $post_id;
	}

	if( isset($_POST['import_options']) && $_POST['import_options'] != '' ){

        $import_export = unserialize(ts_enc_string($_POST['import_options'], 'decode'));

		update_post_meta($post_id, 'ts_template', $import_export);
	}

}
add_action( 'save_post', 'ts_import_export_save_postdata' );

// Custom mega menu saving
if(!function_exists('ts_ajax_switch_menu_walker'))
{
	function ts_ajax_switch_menu_walker()
	{
		if ( ! current_user_can('edit_theme_options') )
		die('-1');

		check_ajax_referer('add-menu_item', 'menu-settings-column-nonce');

		require_once ABSPATH . 'wp-admin/includes/nav-menu.php';

		$item_ids = wp_save_nav_menu_items(0, $_POST['menu-item']);
		if ( is_wp_error($item_ids) )
			die('-1');

		foreach ( (array)$item_ids as $menu_item_id ) {

			$menu_obj = get_post($menu_item_id);

			if ( !empty($menu_obj->ID) ) {
				$menu_obj = wp_setup_nav_menu_item($menu_obj);
				$menu_obj->label = $menu_obj->title; // don't show "(pending)" in ajax-added items
				$menu_items[] = $menu_obj;
			}
		}

		if ( !empty($menu_items) ) {
			$args = array(
				'after' => '',
				'before' => '',
				'link_after' => '',
				'link_before' => '',
				'walker' => new ts_backend_walker
			);
			echo walk_nav_menu_tree($menu_items, 0, (object)$args);
		}

		die('end');
	}

	//hook into wordpress admin.php
	add_action('wp_ajax_ts_ajax_switch_menu_walker', 'ts_ajax_switch_menu_walker');
}

// Adding the post rating box here
add_action( 'add_meta_boxes', 'ts_post_rating_add_custom_box' );
add_action( 'save_post', 'ts_post_rating_save_postdata' );

function ts_post_rating_add_custom_box()
{
    add_meta_box(
        'ts_post_rating',
        'Post rating',
        'ts_post_rating_custom_box',
        'post'
    );
}

function ts_post_rating_custom_box( $post )
{
    // Use nonce for verification
    wp_nonce_field( plugin_basename( __FILE__ ), 'ts_post_rating_nonce' );
    $rating_items = get_post_meta($post->ID, 'ts_post_rating', TRUE);

    echo '<br/><input type="button" class="button button-primary" id="add-item" value="' .esc_html__('Add New rating Item', 'videofly'). '" /><br/><br/>';
    echo '<ul id="rating-items">';

    $rating_editor = '';

    if (!empty($rating_items)) {
        $index = 0;
        foreach ($rating_items as $rating_item_id => $rating_item) {
            $index++;

            $rating_editor .= '
            <li class="rating-item">
            <div class="sortable-meta-element"><span class="tab-arrow icon-down"></span><span class="rating-item-tab ts-multiple-item-tab">'.($rating_item['rating_title'] ? $rating_item['rating_title'] : 'Rating ' . $index).'</span></div>
                <table class="hidden">
                    <tr>
                        <td>
                            Rating name<br>
                            <input type="text" class="rating_title" name="rating['.$rating_item_id.'][rating_title]" value="'.$rating_item['rating_title'].'" style="width: 100%" />
                        </td>
                        <td>
                            Rating score<br>
                            <select name="rating['.$rating_item_id.'][rating_score] " id="rating_score">
                                <option value="1" ' . selected( $rating_item['rating_score'] , 1 , false) . ' >1</option>
                                <option value="2" ' . selected( $rating_item['rating_score'] , 2  , false) . '>2</option>
                                <option value="3" ' . selected( $rating_item['rating_score'] , 3  , false) . '>3</option>
                                <option value="4" ' . selected( $rating_item['rating_score'] , 4  , false) . '>4</option>
                                <option value="5" ' . selected( $rating_item['rating_score'] , 5  , false) . '>5</option>
                                <option value="6" ' . selected( $rating_item['rating_score'] , 6  , false) . '>6</option>
                                <option value="7" ' . selected( $rating_item['rating_score'] , 7  , false) . '>7</option>
                                <option value="8" ' . selected( $rating_item['rating_score'] , 8  , false) . '>8</option>
                                <option value="9" ' . selected( $rating_item['rating_score'] , 9  , false) . '>9</option>
                                <option value="10" ' . selected( $rating_item['rating_score'] , 10 , false) . '>10</option>
                            </select>
                        </td>
                        <td>&nbsp;<br><input type="button" class="button button-primary remove-item" value="'.esc_html__('Remove', 'videofly').'" /></td>
                    </tr>
                </table>
            </li>';
        }
    } else{
        echo esc_html__('Sorry, no rating items were found. Please add some.', 'videofly');
    }

    echo vdf_var_sanitize($rating_editor);

    echo '</ul>';
    echo '<br/><input type="button" class="button button-primary" id="add-item" value="' .esc_html__('Add New rating Item', 'videofly'). '" /><br/><br/>';
    echo '<script id="rating-items-template" type="text/template">';
    echo '<li class="rating-item ts-multiple-add-list-element">
    <div class="sortable-meta-element"><span class="tab-arrow icon-up"></span><span class="rating-item-tab ts-multiple-item-tab">' . esc_html__('Rating', 'videofly') . ' {{slide-number}}</span></div>
        <table>
            <tr>
                <td>
                    ' . esc_html__('Rating name', 'videofly') . '<br>
                    <input type="text" class="rating_title" name="rating[{{item-id}}][rating_title]" value="" style="width: 100%" />
                </td>
                <td>
                    ' . esc_html__('Rating score', 'videofly') . '<br>
                    <select name="rating[{{item-id}}][rating_score]" id="rating_score">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
                </td>
                <td>&nbsp;<br><input type="button" class="button button-primary remove-item" value="'.esc_html__('Remove', 'videofly').'" /></td>
            </tr>
        </table>
    </li>';
    echo '</script>';
?>
    <script>
    jQuery(document).ready(function($) {
        var rating_items = $("#rating-items > li").length;

        // sortable rating items
        $("#rating-items").sortable();
        //$("#rating-items").disableSelection();

        $(document).on('change', '.slide_title', function(event) {
            event.preventDefault();
            var _this = $(this);
            _this.closest('.rating-item').find('.rating-item-tab').text(_this.val());
        });

        // Media uploader
        var items = $('#rating-items'),
            slideTempalte = $('#rating-items-template').html();

        $(document).on('click', '#add-item', function(event) {
            event.preventDefault();
            rating_items++;
            var sufix = new Date().getTime();
            var item_id = new RegExp('{{item-id}}', 'g');
            var item_number = new RegExp('{{slide-number}}', 'g');

            var template = slideTempalte.replace(item_id, sufix).replace(item_number, rating_items);
            items.append(template);
        });

        $(document).on('click', '.remove-item', function(event) {
            event.preventDefault();
            $(this).closest('li').remove();
            rating_items--;
        });

    });
    </script>
<?php
}

// saving slider
function ts_post_rating_save_postdata( $post_id )
{
    global $post;

    if ( is_object($post) && @$post->post_type != 'post' ) {
        return;
    }

    if ( ! isset( $_POST['ts_post_rating_nonce'] ) ||
         ! wp_verify_nonce( $_POST['ts_post_rating_nonce'], plugin_basename( __FILE__ ) )
    ) return;

    if( !current_user_can( 'edit_post', $post_id ) ) return;

    // array containing filtred items
    $rating_items = array();

    if ( isset( $_POST['rating'] ) ) {
        if ( is_array( $_POST['rating'] ) && !empty( $_POST['rating'] ) ) {
            foreach ( $_POST['rating'] as $item_id => $rating_item ) {

                $p = array();
                $p['item_id']   = $item_id;


                $p['rating_title'] = isset($rating_item['rating_title']) ?
                                esc_textarea($rating_item['rating_title']) : '';

                $p['rating_score'] = isset($rating_item['rating_score']) ?
                            esc_textarea($rating_item['rating_score']) : '';

                $rating_items[] = $p;
            }
        }
    }

    update_post_meta( $post_id, 'ts_post_rating', $rating_items );
}
?>
