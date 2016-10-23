<?php
// search posts only by title
function ts_search_by_title_only( $search, &$wp_query )
{
    global $wpdb;
    if ( empty( $search ) ) {
        return $search;
    }

    $q = $wp_query->query_vars;
    $n = ! empty( $q['exact'] ) ? '' : '%';
    $search = '';
    $searchand = '';
    foreach ( (array) $q['search_terms'] as $term ) {
        $term = esc_sql( $wpdb->esc_like($term) );
        $search .= "{$searchand}($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
        $searchand = ' AND ';
    }
    if ( ! empty( $search ) ) {
        $search = " AND ({$search}) ";
        if ( ! is_user_logged_in() )
            $search .= " AND ($wpdb->posts.post_password = '') ";
    }
    return $search;
}

if ( ! function_exists( 'touchsize_comment' ) ) :

function touchsize_comment( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    switch ( $comment->comment_type ) :
        case 'pingback' :
        case 'trackback' :
    ?>
    <li class="post pingback">
        <p><?php _e( 'Pingback:', 'touchsize' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'touchsize' ), '<span class="edit-link">', '</span>' ); ?></p>
    <?php
            break;
        default :
    ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
        <article id="comment-<?php comment_ID(); ?>" class="comment">
            <footer class="comment-meta">
                <div class="comment-author vcard">
                    <?php
                        $avatar_size = 68;
                        if ( '0' != $comment->comment_parent )
                            $avatar_size = 39;

                        echo get_avatar( $comment, $avatar_size );

                        /* translators: 1: comment author, 2: date and time */
                        printf( __( '%1$s on %2$s <span class="says">said:</span>', 'touchsize' ),
                            sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
                            sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
                                esc_url( get_comment_link( $comment->comment_ID ) ),
                                get_comment_time( 'c' ),
                                /* translators: 1: date, 2: time */
                                sprintf( __( '%1$s at %2$s', 'touchsize' ), get_comment_date(), get_comment_time() )
                            )
                        );
                    ?>

                    <?php edit_comment_link( __( 'Edit', 'touchsize' ), '<span class="edit-link">', '</span>' ); ?>
                </div><!-- .comment-author .vcard -->

                <?php if ( $comment->comment_approved == '0' ) : ?>
                    <em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'touchsize' ); ?></em>
                    <br />
                <?php endif; ?>

            </footer>

            <div class="comment-content"><?php comment_text(); ?></div>

            <div class="reply">
                <?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply <span>&darr;</span>', 'touchsize' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
            </div><!-- .reply -->
        </article><!-- #comment-## -->

    <?php
            break;
    endswitch;
}
endif; // ends check for touchsize_comment()


if ( ! function_exists( 'touchsize_posted_on' ) ) :

function touchsize_posted_on() {
    printf( __( '<span class="sep">Posted on </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a><span class="by-author"> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'touchsize' ),
        esc_url( get_permalink() ),
        esc_attr( get_the_time() ),
        esc_attr( get_the_date( 'c' ) ),
        esc_html( get_the_date() ),
        esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
        esc_attr( sprintf( __( 'View all posts by %s', 'touchsize' ), get_the_author() ) ),
        get_the_author()
    );
}
endif; // ends check for touchsize_posted_on()


function ts_layout_wrapper($elements)
{

    echo '<script id="dragable-row-tpl" type="text/x-handlebars-template">
        <ul class="layout_builder_row">
            <li class="row-editor">
                <ul class="row-editor-options">
                    <li>
                        <a href="#" class="add-column">' . __( '+', 'touchsize' ) . '</a>
                        <a href="#" class="predefined-columns"><img src="'.get_template_directory_uri().'/images/options/columns_layout.png" alt=""></a>
                        <ul class="add-column-settings">
                            <li>
                               <a href="#" data-add-columns="#dragable-column-tpl"><img src="'.get_template_directory_uri().'/images/options/columns_layout_column.png" alt=""></a>
                           </li>
                           <li>
                               <a href="#" data-add-columns="#dragable-column-tpl-half"><img src="'.get_template_directory_uri().'/images/options/columns_layout_halfs.png" alt=""></a>
                           </li>
                           <li>
                               <a href="#" data-add-columns="#dragable-column-tpl-thirds"><img src="'.get_template_directory_uri().'/images/options/columns_layout_thirds.png" alt=""></a>
                           </li>
                           <li>
                               <a href="#" data-add-columns="#dragable-column-tpl-four-halfs"><img src="'.get_template_directory_uri().'/images/options/columns_layout_one_four.png" alt=""></a>
                           </li>
                           <li>
                               <a href="#" data-add-columns="#dragable-column-tpl-one_three"><img src="'.get_template_directory_uri().'/images/options/columns_layout_one_three.png" alt=""></a>
                           </li>
                           <li>
                               <a href="#" data-add-columns="#dragable-column-tpl-four-half-four"><img src="'.get_template_directory_uri().'/images/options/columns_layout_four_half_four.png" alt=""></a>
                           </li>
                        </ul>
                    </li>
                    <li><a href="#" class="remove-row">' . __( 'delete', 'touchsize' ) . '</a></li>
                    <li><a href="#" class="move">' . __( 'move', 'touchsize' ) . '</a></li>
                </ul>
            </li>
            <li class="edit-row-settings" >
                <a href="#" class="edit-row" id="{{row-id}}">'.__('Edit', 'touchsize').'</a>
            </li>
        </ul>
    </script>';

    echo '<script id="dragable-element-tpl" type="text/x-handlebars-template">
            <li data-element-type="empty">
                <i class="element-icon icon-empty"></i>
                <span class="element-name">'.__('Empty', 'touchsize').'</span>
                <span class="edit icon-edit" data-tooltip="'.__('Edit this element', 'touchsize').'">'.__('Edit', 'touchsize').'</span>
                <span class="delete icon-delete" data-tooltip="'.__('Remove this element', 'touchsize').'"></span>
                <span class="clone icon-beaker" data-tooltip="'.__('Clone this element', 'touchsize').'"></span>
            </li>
        </script>';
    // Template for adding a 12 column
    echo '<script id="dragable-column-tpl" type="text/x-handlebars-template">
        <li data-columns="12" data-type="column" class="columns12">
            <div class="column-header">
                <span class="minus icon-left" data-tooltip="'.__('Reduce column size', 'touchsize').'"></span>
                <span class="column-size" data-tooltip="'.__('The size of the column within container', 'touchsize').'">12/12</span>
                <span class="plus icon-right" data-tooltip="' . __('Add column size', 'touchsize') . '"></span>
                <span class="delete-column icon-delete" data-tooltip="'.__('Remove this column', 'touchsize').'"></span>
                <span class="drag-column icon-drag" data-tooltip="'.__('Drag this column', 'touchsize').'"></span>
            </div>

            <ul class="elements">
            </ul>
            <span class="add-element">'.__('Add element', 'touchsize').'</span>
        </li>
    </script>';
    // Template for splitting the content in 2 halfs
    echo '<script id="dragable-column-tpl-half" type="text/x-handlebars-template">
        <li data-columns="6" data-type="column" class="columns6">
            <div class="column-header">
                <span class="minus icon-left" data-tooltip="'.__('Reduce column size', 'touchsize').'"></span>
                <span class="column-size" data-tooltip="'.__('The size of the column within container', 'touchsize').'">1/2</span>
                <span class="plus icon-right" data-tooltip="' . __('Add column size', 'touchsize') . '"></span>
                <span class="delete-column icon-delete" data-tooltip="'.__('Remove this column', 'touchsize').'"></span>
                <span class="drag-column icon-drag" data-tooltip="'.__('Drag this column', 'touchsize').'"></span>
            </div>

            <ul class="elements">
            </ul>
            <span class="add-element">'.__('Add element', 'touchsize').'</span>
        </li>
        <li data-columns="6" data-type="column" class="columns6">
            <div class="column-header">
                <span class="minus icon-left" data-tooltip="'.__('Reduce column size', 'touchsize').'"></span>
                <span class="column-size" data-tooltip="'.__('The size of the column within container', 'touchsize').'">1/2</span>
                <span class="plus icon-right" data-tooltip="' . __('Add column size', 'touchsize') . '"></span>
                <span class="delete-column icon-delete" data-tooltip="'.__('Remove this column', 'touchsize').'"></span>
                <span class="drag-column icon-drag" data-tooltip="'.__('Drag this column', 'touchsize').'"></span>
            </div>

            <ul class="elements">
            </ul>
            <span class="add-element">'.__('Add element', 'touchsize').'</span>
        </li>
    </script>';
    // Template for splitting the content in 3 halfs
    echo '<script id="dragable-column-tpl-thirds" type="text/x-handlebars-template">
        <li data-columns="4" data-type="column" class="columns4">
            <div class="column-header">
                <span class="minus icon-left" data-tooltip="'.__('Reduce column size', 'touchsize').'"></span>
                <span class="column-size" data-tooltip="'.__('The size of the column within container', 'touchsize').'">1/4</span>
                <span class="plus icon-right" data-tooltip="' . __('Add column size', 'touchsize') . '"></span>
                <span class="delete-column icon-delete" data-tooltip="'.__('Remove this column', 'touchsize').'"></span>
                <span class="drag-column icon-drag" data-tooltip="'.__('Drag this column', 'touchsize').'"></span>
            </div>

            <ul class="elements">
            </ul>
            <span class="add-element">'.__('Add element', 'touchsize').'</span>
        </li>
        <li data-columns="4" data-type="column" class="columns4">
            <div class="column-header">
                <span class="minus icon-left" data-tooltip="'.__('Reduce column size', 'touchsize').'"></span>
                <span class="column-size" data-tooltip="'.__('The size of the column within container', 'touchsize').'">1/4</span>
                <span class="plus icon-right" data-tooltip="' . __('Add column size', 'touchsize') . '"></span>
                <span class="delete-column icon-delete" data-tooltip="'.__('Remove this column', 'touchsize').'"></span>
                <span class="drag-column icon-drag" data-tooltip="'.__('Drag this column', 'touchsize').'"></span>
            </div>

            <ul class="elements">
            </ul>
            <span class="add-element">'.__('Add element', 'touchsize').'</span>
        </li>
        <li data-columns="4" data-type="column" class="columns4">
            <div class="column-header">
                <span class="minus icon-left" data-tooltip="'.__('Reduce column size', 'touchsize').'"></span>
                <span class="column-size" data-tooltip="'.__('The size of the column within container', 'touchsize').'">1/4</span>
                <span class="plus icon-right" data-tooltip="' . __('Add column size', 'touchsize') . '"></span>
                <span class="delete-column icon-delete" data-tooltip="'.__('Remove this column', 'touchsize').'"></span>
                <span class="drag-column icon-drag" data-tooltip="'.__('Drag this column', 'touchsize').'"></span>
            </div>

            <ul class="elements">
            </ul>
            <span class="add-element">'.__('Add element', 'touchsize').'</span>
        </li>
    </script>';
    // Template for splitting the content in one to three
    echo '<script id="dragable-column-tpl-one_three" type="text/x-handlebars-template">
        <li data-columns="4" data-type="column" class="columns4">
            <div class="column-header">
                <span class="minus icon-left" data-tooltip="'.__('Reduce column size', 'touchsize').'"></span>
                <span class="column-size" data-tooltip="'.__('The size of the column within container', 'touchsize').'">1/3</span>
                <span class="plus icon-right" data-tooltip="' . __('Add column size', 'touchsize') . '"></span>
                <span class="delete-column icon-delete" data-tooltip="'.__('Remove this column', 'touchsize').'"></span>
                <span class="drag-column icon-drag" data-tooltip="'.__('Drag this column', 'touchsize').'"></span>
            </div>

            <ul class="elements">
            </ul>
            <span class="add-element">'.__('Add element', 'touchsize').'</span>
        </li>
        <li data-columns="8" data-type="column" class="columns8">
            <div class="column-header">
                <span class="minus icon-left" data-tooltip="'.__('Reduce column size', 'touchsize').'"></span>
                <span class="column-size" data-tooltip="'.__('The size of the column within container', 'touchsize').'">2/3</span>
                <span class="plus icon-right" data-tooltip="' . __('Add column size', 'touchsize') . '"></span>
                <span class="delete-column icon-delete" data-tooltip="'.__('Remove this column', 'touchsize').'"></span>
                <span class="drag-column icon-drag" data-tooltip="'.__('Drag this column', 'touchsize').'"></span>
            </div>

            <ul class="elements">
            </ul>
            <span class="add-element">'.__('Add element', 'touchsize').'</span>
        </li>
    </script>';
    // Template for splitting the content in one fourth to one half and one fourth
    echo '<script id="dragable-column-tpl-four-half-four" type="text/x-handlebars-template">
        <li data-columns="3" data-type="column" class="columns3">
            <div class="column-header">
                <span class="minus icon-left" data-tooltip="'.__('Reduce column size', 'touchsize').'"></span>
                <span class="column-size" data-tooltip="'.__('The size of the column within container', 'touchsize').'">1/4</span>
                <span class="plus icon-right" data-tooltip="' . __('Add column size', 'touchsize') . '"></span>
                <span class="delete-column icon-delete" data-tooltip="'.__('Remove this column', 'touchsize').'"></span>
                <span class="drag-column icon-drag" data-tooltip="'.__('Drag this column', 'touchsize').'"></span>
            </div>

            <ul class="elements">
            </ul>
            <span class="add-element">'.__('Add element', 'touchsize').'</span>
        </li>
        <li data-columns="6" data-type="column" class="columns6">
            <div class="column-header">
                <span class="minus icon-left" data-tooltip="'.__('Reduce column size', 'touchsize').'"></span>
                <span class="column-size" data-tooltip="'.__('The size of the column within container', 'touchsize').'">1/2</span>
                <span class="plus icon-right" data-tooltip="' . __('Add column size', 'touchsize') . '"></span>
                <span class="delete-column icon-delete" data-tooltip="'.__('Remove this column', 'touchsize').'"></span>
                <span class="drag-column icon-drag" data-tooltip="'.__('Drag this column', 'touchsize').'"></span>
            </div>

            <ul class="elements">
            </ul>
            <span class="add-element">'.__('Add element', 'touchsize').'</span>
        </li>
        <li data-columns="3" data-type="column" class="columns3">
            <div class="column-header">
                <span class="minus icon-left" data-tooltip="'.__('Reduce column size', 'touchsize').'"></span>
                <span class="column-size" data-tooltip="'.__('The size of the column within container', 'touchsize').'">1/4</span>
                <span class="plus icon-right" data-tooltip="' . __('Add column size', 'touchsize') . '"></span>
                <span class="delete-column icon-delete" data-tooltip="'.__('Remove this column', 'touchsize').'"></span>
                <span class="drag-column icon-drag" data-tooltip="'.__('Drag this column', 'touchsize').'"></span>
            </div>

            <ul class="elements">
            </ul>
            <span class="add-element">'.__('Add element', 'touchsize').'</span>
        </li>
    </script>';
    // Template for splitting the content in four columns
    echo '<script id="dragable-column-tpl-four-halfs" type="text/x-handlebars-template">
        <li data-columns="3" data-type="column" class="columns3">
            <div class="column-header">
                <span class="minus icon-left" data-tooltip="'.__('Reduce column size', 'touchsize').'"></span>
                <span class="column-size" data-tooltip="'.__('The size of the column within container', 'touchsize').'">1/3</span>
                <span class="plus icon-right" data-tooltip="' . __('Add column size', 'touchsize') . '"></span>
                <span class="delete-column icon-delete" data-tooltip="'.__('Remove this column', 'touchsize').'"></span>
                <span class="drag-column icon-drag" data-tooltip="'.__('Drag this column', 'touchsize').'"></span>
            </div>

            <ul class="elements">
            </ul>
            <span class="add-element">'.__('Add element', 'touchsize').'</span>
        </li>
        <li data-columns="3" data-type="column" class="columns3">
            <div class="column-header">
                <span class="minus icon-left" data-tooltip="'.__('Reduce column size', 'touchsize').'"></span>
                <span class="column-size" data-tooltip="'.__('The size of the column within container', 'touchsize').'">1/3</span>
                <span class="plus icon-right" data-tooltip="' . __('Add column size', 'touchsize') . '"></span>
                <span class="delete-column icon-delete" data-tooltip="'.__('Remove this column', 'touchsize').'"></span>
                <span class="drag-column icon-drag" data-tooltip="'.__('Drag this column', 'touchsize').'"></span>
            </div>

            <ul class="elements">
            </ul>
            <span class="add-element">'.__('Add element', 'touchsize').'</span>
        </li>
        <li data-columns="3" data-type="column" class="columns3">
            <div class="column-header">
                <span class="minus icon-left" data-tooltip="'.__('Reduce column size', 'touchsize').'"></span>
                <span class="column-size" data-tooltip="'.__('The size of the column within container', 'touchsize').'">1/3</span>
                <span class="plus icon-right" data-tooltip="' . __('Add column size', 'touchsize') . '"></span>
                <span class="delete-column icon-delete" data-tooltip="'.__('Remove this column', 'touchsize').'"></span>
                <span class="drag-column icon-drag" data-tooltip="'.__('Drag this column', 'touchsize').'"></span>
            </div>

            <ul class="elements">
            </ul>
            <span class="add-element">'.__('Add element', 'touchsize').'</span>
        </li>
        <li data-columns="3" data-type="column" class="columns3">
            <div class="column-header">
                <span class="minus icon-left" data-tooltip="'.__('Reduce column size', 'touchsize').'"></span>
                <span class="column-size" data-tooltip="'.__('The size of the column within container', 'touchsize').'">1/3</span>
                <span class="plus icon-right" data-tooltip="' . __('Add column size', 'touchsize') . '"></span>
                <span class="delete-column icon-delete" data-tooltip="'.__('Remove this column', 'touchsize').'"></span>
                <span class="drag-column icon-drag" data-tooltip="'.__('Drag this column', 'touchsize').'"></span>
            </div>

            <ul class="elements">
            </ul>
            <span class="add-element">'.__('Add element', 'touchsize').'</span>
        </li>
    </script>';


    echo '<div class="builder-section-container">
        <!-- Edit Content Strucutre -->
        <div style="clear: both"></div>
        <a href="#" data-location="content" class="app red-ui-button add-top-row">' . __( 'Add row to the top', 'touchsize' ) . '</a><br/><br/>
        <div class="layout_builder" id="section-content">';

    echo $elements;

    echo '</div>
        <div style="clear: both"></div>
        <br>
        <a href="#" data-location="content" class="app red-ui-button add-bottom-row">'. __( 'Add row to the bottom', 'touchsize' ) . '</a>
        <a href="#" data-location="content" class="app red-ui-button publish-changes" style="float: right;font">'. __( 'Publish', 'touchsize' ) . '</a>
        <div style="clear: both"></div>
    </div>';
}

/**
 * Extrat information from page options
 */

//=============== Style options  ===========================
//=============== General Tab ==============================

if ( ! function_exists('ts_preloader')) {

    function ts_preloader()
    {
        $option = get_option('videotouch_general', array('enable_preloader' => 'N'));

        if ( $option['enable_preloader'] === 'Y' || $option['enable_preloader'] === 'FP' && is_front_page() ) {
            return true;
        } else {
            return false;
        }

    }
}


if ( ! function_exists('ts_display_featured_image')) {

    function ts_display_featured_image()
    {
        global $post;
        $option = get_option('videotouch_general', array('featured_image_in_post' => 'Y'));

        if ( !is_page() && $option['featured_image_in_post'] === 'Y' && !fields::logic($post->ID, 'post_settings', 'hide_featimg') || is_page() && !fields::logic($post->ID, 'page_settings', 'hide_featimg')) {
            return true;
        } else {
            return false;
        }
    }
}

if ( ! function_exists('ts_lightbox_enabled')) {

    function ts_lightbox_enabled()
    {
        $option = get_option('videotouch_general', array('enable_lightbox' => 'Y'));

        if ($option['enable_lightbox'] === 'Y') {
            return true;
        } else {
            return false;
        }
    }
}

if ( ! function_exists('ts_human_type_date_format')) {

    function ts_human_type_date_format()
    {
        $option = get_option('videotouch_general', array('human_type_date_format' => 'Y'));

        if ($option['human_type_date_format'] === 'Y') {
            return true;
        } else {
            return false;
        }
    }
}

if ( ! function_exists('ts_comment_system')) {

    function ts_comment_system()
    {
        $option = get_option('videotouch_general', array('comment_system' => 'default'));

        if ( in_array($option['comment_system'], array('default', 'facebook')) ) {
            return $option['comment_system'];
        } else {
            return 'default';
        }
    }
}

if ( ! function_exists('ts_facebook_app_ID')) {

    function ts_facebook_app_ID()
    {
        $option = get_option('videotouch_general', array('facebook_id' => ''));

        return $option['facebook_id'];
    }
}

// Enable or disable WP Admin Bar
$option = get_option('videotouch_general', array('show_wp_admin_bar' => 'Y'));
$showAdminBar = (isset($option['show_wp_admin_bar']) && ($option['show_wp_admin_bar'] == 'Y' || $option['show_wp_admin_bar'] == 'N')) ? $option['show_wp_admin_bar'] : 'Y';

if ($showAdminBar == 'N') {
    add_filter('show_admin_bar', '__return_false');
}

$user = wp_get_current_user();

if ( current_user_can('simple_user') && !current_user_can('manage_options') ) {
    add_filter('show_admin_bar', '__return_false');
}

if ( ! function_exists('ts_enable_sticky_menu')) {

    function ts_enable_sticky_menu()
    {
        $option = get_option('videotouch_general', array('enable_sticky_menu' => 'Y'));

        if ( $option['enable_sticky_menu'] === "Y" ) {
            return true;
        } else {
            return false;
        }
    }
}

if ( ! function_exists('ts_get_sticky_menu_style')) {

    function ts_get_sticky_menu_style()
    {
        $option = get_option('videotouch_general', array('sticky_menu_bg_color' => '', 'sticky_menu_text_color' => ''));

        if ( $option['sticky_menu_text_color'] !== "#FFFFFF" || $option['sticky_menu_bg_color'] !== "#444444" ) {
            $content = '
            .ts-sticky-menu{
                background-color: ' . $option['sticky_menu_bg_color'] . ';
            }
            .ts-sticky-menu .sf-menu li ul{
                background-color: ' . $option['sticky_menu_bg_color'] . ';
            }
            .ts-sticky-menu .container .sf-menu li a, .ts-sticky-menu .container .sf-menu li, .ts-sticky-menu .sf-menu{
                color:'. $option['sticky_menu_text_color'] . ';' .
            '}
            .ts-sticky-menu .container .sf-menu li.current-menu-item > a{
                color: '. ts_get_color('primary_color') .';
            }';
            return $content;
        } else {
            return '';
        }
    }
}

if ( ! function_exists('ts_tracking_code')) {

    function ts_tracking_code()
    {
        $option = get_option('videotouch_general', array('tracking_code' => ''));

        return $option['tracking_code'];
    }
}

//=============== Styles Tab ==============================

if ( ! function_exists('ts_boxed_layout')) {

    function ts_boxed_layout()
    {
        $option = get_option('videotouch_styles', array('boxed_layout' => 'N'));

        if ($option['boxed_layout'] === 'N') {
            return false;
        } else {
            return true;
        }
    }
}

if ( ! function_exists('ts_get_color')) {

    function ts_get_color($val)
    {
        $option = get_option('videotouch_colors', array($val => '#EB593C'));

        return $option[$val];
    }
}

if ( ! function_exists('ts_custom_background')) {

    function ts_custom_background()
    {
        $bg = get_option('videotouch_styles');

        if ( ! isset( $bg['theme_custom_bg'] ) || $bg['theme_custom_bg'] == 'N' ) return;

        switch ($bg['theme_custom_bg']) {
            case 'N':
                $css = '';
                break;

            case 'pattern':
                $css = "background: url(" . get_template_directory_uri() . '/images/patterns/' . esc_attr($bg['theme_bg_pattern']) .");\n";
                break;

            case 'image':
                $css = "background: url(" . esc_url($bg['bg_image']) .") no-repeat top center;\n";
                break;

            case 'color':
                $css = "background-color: " . esc_attr($bg['theme_bg_color']) .";\n";
                break;

            default:
                $css = '';
                break;
        }

        return ! empty( $css ) ? "body {\n" . $css . "\n}" : '';

    }
}

if ( ! function_exists('ts_custom_favicon')) {

    function ts_custom_favicon()
    {
        $option = get_option('videotouch_styles', array('favicon' => ''));

        if ( $option['favicon'] == '' ) {
            return '<link rel="shortcut icon" href="'. get_template_directory_uri() . '/favicon.png" />';
        } else {
            return '<link rel="shortcut icon" href="'.esc_url($option['favicon']).'" />';
        }
    }
}

if ( ! function_exists('ts_overlay_effect_is_enabled')) {

    function ts_overlay_effect_is_enabled()
    {
        $option = get_option('videotouch_styles', array('overlay_effect_for_images' => 'N'));

        if ($option['overlay_effect_for_images'] === 'Y') {
            return true;
        } else {
            return false;
        }
    }
}

if ( ! function_exists('ts_overlay_effect_type')) {

    function ts_overlay_effect_type()
    {
        $option = get_option('videotouch_styles', array('overlay_effect_type' => 'dots'));

        if ($option['overlay_effect_type'] === 'dots') {
            return 'dotted';
        } else {
            return 'stripes';
        }
    }
}


if ( ! function_exists('ts_get_logo')) {

    function ts_get_logo()
    {
        $option = get_option('videotouch_styles', array(
            'logo_type' => 'image',
            'retina_logo' =>'N',
            'retina_width' =>'0',
            'retina_height' =>'0'
        ));

        $retina_style = '';

        if ( $option['logo_type'] === 'image' ) {

            if ( trim($option['logo_url']) !== '' ) {

                if ( $option['retina_logo'] === 'Y' ) {

                    $option['retina_width'] = (int)$option['retina_width'];
                    $option['retina_height'] = (int)$option['retina_height'];

                    if( $option['retina_width'] > 0 && $option['retina_height'] > 0 ) {
                        $option['retina_width'] = ceil($option['retina_width']/2);
                        $option['retina_height'] = ceil($option['retina_height']/2);

                        $retina_style = 'style="width: ' . $option['retina_width'] . 'px; ' . $option['retina_height'] . 'px;"';
                    } else {
                        $retina_style = '';
                    }
                }

                return '<img src="'.esc_url($option['logo_url']).'" alt="'.get_bloginfo('name').'" ' . $retina_style . '/>';
            } else {

                if ($option['retina_logo'] === 'Y' ) {
                    $retina_style = 'style="width: ' . $option['retina_width']/2 . 'px; height: auto;"';
                } else {
                    $retina_style = '';
                }

                return '<img src="'.get_template_directory_uri().'/images/logo.png" alt="'.get_bloginfo('name').'" ' . $retina_style . '/>';
            }
        } else {
            return get_bloginfo('name');
        }
    }
}

if ( ! function_exists('ts_get_logo_google_fonts')) {

    function ts_get_logo_google_fonts()
    {
        $option = get_option('videotouch_styles', array('logo_type' => 'image'));

        if ( $option['logo_type'] === 'image' ) {
            return '';
        } else {

            if (is_array($option['logo_font_subsets']) && !empty($option['logo_font_subsets'])) {
                $subsets = '&subset=' . implode(",", $option['logo_font_subsets']);
            } else {
                $subsets = array('latin');
                $subsets = '&subset=' . implode(",", $subsets);
            }

            if ($option['logo_font_name'] === '0') {
                return '';
            } else {
                return '<link rel="stylesheet" href="http://fonts.googleapis.com/css?family='. urlencode($option['logo_font_name']) . ':400,400italic,700'.$subsets.'" type="text/css" media="all" />' ."\n";
            }
        }
    }
}

if ( ! function_exists('ts_get_logo_css')) {

    function ts_get_logo_css()
    {
        $option = get_option('videotouch_styles', array('logo_type' => 'image'));

        if ( $option['logo_type'] === 'image' ) {
            return '';
        } else {
            if ($option['logo_font_name'] === '0') {
                return '';
            } else {

                $option['logo_font_weight']  = (in_array($option['logo_font_weight'], array('normal', '700'))) ?
                                    $option['logo_font_weight'] : 'normal';

                $option['logo_font_style']  = (in_array($option['logo_font_style'], array('normal', 'italic'))) ?
                                    $option['logo_font_style'] : 'normal';

                $option['logo_font_size'] = (int)$option['logo_font_size'];

                if ( ($option['logo_font_size'] < 1) && ($option['logo_font_size'] > 72) ) {
                    $option['logo_font_size'] = 32;
                }

                return '
                .logo {
                    font-family: "'.esc_attr($option['logo_font_name']).'";
                    font-weight: '.esc_attr($option['logo_font_weight']).';
                    font-style: '.esc_attr($option['logo_font_style']).';
                    font-size: '.$option['logo_font_size'].'px;
                }';
            }
        }
    }
}

if ( ! function_exists('ts_get_headings_fonts')) {

    function ts_get_custom_fonts($location = '')
    {

        $locations = array('headings', 'primary_text', 'secondary_text');

        if ( ! in_array($location, $locations)) {
            return '';
        }

        $option = get_option('videotouch_typography', array( $location => array('type' => 'std') ));

        if ( $option[$location]['type'] === 'std' ) {
            return '';
        } else {

            if ( isset($option[$location]['font_subsets']) && is_array(@$option[$location]['font_subsets']) && ! empty($option[$location]['font_subsets'])) {
                $subsets = '&subset=' . implode(",", $option[$location]['font_subsets']);
            } else {
                $subsets = array('latin');
                $subsets = '&subset=' . implode(",", $subsets);
            }

            if ($option[$location]['font_name'] === '0') {
                return '';
            } else {
                return '<link rel="stylesheet" href="http://fonts.googleapis.com/css?family='. urlencode($option[$location]['font_name']) . ':400,400italic,700'.$subsets.'" type="text/css" media="all" />' . "\n";
            }
        }
    }
}

if ( ! function_exists('ts_get_custom_fonts_css')) {
    add_filter( 'wp_image_editors', 'change_graphic_lib' );

    function change_graphic_lib($array) {
      return array( 'WP_Image_Editor_GD', 'WP_Image_Editor_Imagick' );
    }

    function ts_get_custom_fonts_css($location = '')
    {

        $locations = array('headings', 'primary_text', 'secondary_text');

        if ( ! in_array($location, $locations)) {
            return '';
        }

        $option = get_option('videotouch_typography', array( $location => array('type' => 'custom_font')) );

        if ( $option[$location]['type'] === 'std' ) {
            return '';
        } else if( $option[$location]['type'] === 'custom_font' ){

            $font_family = ( isset($option[$location]['font_family']) ) ? $option[$location]['font_family'] : '';
            $file_eot = ( isset($option[$location]['font_eot']) ) ? $option[$location]['font_eot'] : '';
            $file_woff = ( isset($option[$location]['font_woff']) ) ? $option[$location]['font_woff'] : '';
            $file_ttf = ( isset($option[$location]['font_ttf']) ) ? $option[$location]['font_ttf'] : '';
            $file_svg = ( isset($option[$location]['font_svg']) ) ? $option[$location]['font_svg'] : '';
            $additional_styles = '';
            $tags = 'h1, h2, h3, h4, h5, h6 ';

            $option[$location]['font_weight'] = (in_array($option[$location]['font_weight'], array('normal', '700'))) ? $option[$location]['font_weight'] : 'normal';

            $option[$location]['font_style'] = (in_array($option[$location]['font_style'], array('normal', 'italic'))) ? $option[$location]['font_style'] : 'normal';

            if ( $location === 'headings' ) {
                $additional_styles = '
                    h1{font-size: ' . (int)@$option[$location]['h1_size'] .'px}
                    h2{font-size: ' . (int)@$option[$location]['h2_size'] .'px}
                    h3{font-size: ' . (int)@$option[$location]['h3_size'] .'px}
                    h4{font-size: ' . (int)@$option[$location]['h4_size'] .'px}
                    h5{font-size: ' . (int)@$option[$location]['h5_size'] .'px}';
            }

            if ($location === 'primary_text') {
                $tags = 'body';
                $additional_styles .= 'body{
                    font-size: '. $option[$location]['font_size'] .'px;
                    font-family: '. $font_family .';
                }';
            }

            if ($location === 'secondary_text') {
                $tags = '.ts-behold-menu';
                $additional_styles .= '.ts-behold-menu, .ts-behold-menu .main-menu > .menu-item-has-children > a:after, .main-menu{
                    font-size: '. $option['secondary_text']['font_size'] .'px;
                    font-family: '. $font_family .'!important;
                }
                .ts-mega-menu .main-menu .ts_is_mega_div .title{
                    font-family: '. $font_family .'!important;
                }
                ';
            }

            return $additional_styles . '
                    @font-face{
                        font-family: "'. $font_family .'";
                        src: url("'. $file_eot .'");
                        src: url("'. $file_eot .'?#iefix") format("embedded-opentype"),
                             url("'. $file_woff .'") format("woff"),
                             url("'. $file_ttf .'") format("truetype"),
                             url("'. $file_svg .'#'. $font_family .'") format("svg");
                    }' . $tags . ' {
                            font-family: "'.$font_family.'";
                            font-weight: '.esc_attr($option[$location]['font_weight']).';
                            font-style: '.esc_attr($option[$location]['font_style']).';
                        }';

        } else {
            if ($option[$location]['font_name'] === '0') {
                return '';
            } else {

                $option[$location]['font_weight'] = (in_array($option[$location]['font_weight'], array('normal', '700'))) ? $option[$location]['font_weight'] : 'normal';

                $option[$location]['font_style'] = (in_array($option[$location]['font_style'], array('normal', 'italic'))) ? $option[$location]['font_style'] : 'normal';

                $additional_styles = '';
                if ($location === 'headings') {
                    $tags = 'h1, h2, h3, h4, h5, h6 ';
                    $additional_styles .= 'h1{font-size: ' . (int)@$option[$location]['h1_size'] .'px}
                    h2{font-size: ' . (int)@$option[$location]['h2_size'] .'px}
                    h3{font-size: ' . (int)@$option[$location]['h3_size'] .'px}
                    h4{font-size: ' . (int)@$option[$location]['h4_size'] .'px}
                    h5{font-size: ' . (int)@$option[$location]['h5_size'] .'px}';
                }

                if ($location === 'primary_text') {
                    $tags = 'body';
                    $additional_styles .= 'body{font-size: '. $option[$location]['font_size'] .'px;}';
                }

                if ($location === 'secondary_text') {
                    $tags = '.ts-behold-menu, .main-menu li';
                    $additional_styles .= '.ts-behold-menu, .ts-behold-menu .main-menu > .menu-item-has-children > a:after, .main-menu li{font-size: '. $option['secondary_text']['font_size'] .'px;}';
                }

                return $additional_styles . $tags .' {
                    font-family: "'.esc_attr($option[$location]['font_name']).'";
                    font-weight: '.esc_attr($option[$location]['font_weight']).';
                    font-style: '.esc_attr($option[$location]['font_style']).';
                }';
            }
        }
    }
}

//================== Styles Tab =======================================================

if ( ! function_exists('ts_resize')) {

    function ts_resize($post_type, $image, $masonry = false) {

        $image_sizes = get_option('videotouch_image_sizes');
        $options = array();

        switch ($post_type) {

            case 'grid':
                $options =  $image_sizes['grid'];
                break;

            case 'thumbnails':
                $options =  $image_sizes['thumbnails'];
                break;

            case 'bigpost':
                $options =  $image_sizes['bigpost'];
                break;

            case 'superpost':
                $options =  $image_sizes['superpost'];
                break;

            case 'single':
                $options =  $image_sizes['single'];
                break;

            case 'portfolio':
                $options =  $image_sizes['portfolio'];
                break;

            case 'featarea':
                $options =  $image_sizes['featarea'];
                break;

            case 'slider':
                $options =  $image_sizes['slider'];
                break;

            case 'carousel':
                $options =  $image_sizes['carousel'];
                break;

            case 'timeline':
                $options =  $image_sizes['timeline'];
                break;

            case 'features':
                $options =  array(
                    'width' => '100',
                    'height' => '100',
                    'mode' => 'crop'
                );

                break;

            default:
                return $image;
                break;
        }
        if( $masonry === false ){
            $crop = ($options['mode'] === 'crop') ? true : false;
            $options['height'] == 9999;
        } else{
            $crop = false;
        }
        $img_url = aq_resize( $image, $options['width'], $options['height'], $crop, true);

        return $img_url;
    }
}

//================== Single post Tab ==================================================

if ( ! function_exists('ts_single_related_posts')) {

    function ts_single_related_posts() {
        $single = get_option('videotouch_single_post', array('related_posts' => 'Y'));

        if ($single['related_posts'] === 'Y') {
            return true;
        } else {
            return false;
        }
    }
}

if ( ! function_exists('ts_single_social_sharing')) {

    function ts_single_social_sharing() {
        $single = get_option('videotouch_single_post', array('social_sharing' => 'Y'));
        if ($single['social_sharing'] === 'Y') {
            return true;
        } else {
            return false;
        }
    }
}

if ( ! function_exists('ts_single_display_meta')) {

    function ts_single_display_meta() {
        $single = get_option('videotouch_single_post', array('post_meta' => 'Y'));

        if ($single['post_meta'] === 'Y') {
            return true;
        } else {
            return false;
        }
    }
}

if ( ! function_exists('ts_single_display_tags')) {

    function ts_single_display_tags() {
        $single = get_option('videotouch_single_post', array('post_tags' => 'Y'));

        if ($single['post_tags'] === 'Y') {
            return true;
        } else {
            return false;
        }
    }
}

//================== Page Tab ==================================================

if ( ! function_exists('ts_page_social_sharing')) {

    function ts_page_social_sharing() {
        $single = get_option('videotouch_page', array('social_sharing' => 'Y'));

        if ($single['social_sharing'] === 'Y') {
            return true;
        } else {
            return false;
        }
    }
}

if ( ! function_exists('ts_page_display_meta')) {

    function ts_page_display_meta() {
        $single = get_option('videotouch_page', array('post_meta' => 'Y'));

        if ($single['post_meta'] === 'Y') {
            return true;
        } else {
            return false;
        }
    }
}

if ( ! function_exists('hex2rgb')) {

    function hex2rgb($hex, $p) {
       $hex = str_replace("#", "", $hex);

       if(strlen($hex) == 3) {
          $r = hexdec(substr($hex,0,1).substr($hex,0,1));
          $g = hexdec(substr($hex,1,1).substr($hex,1,1));
          $b = hexdec(substr($hex,2,1).substr($hex,2,1));
       } else {
          $r = hexdec(substr($hex,0,2));
          $g = hexdec(substr($hex,2,2));
          $b = hexdec(substr($hex,4,2));
       }
       //$rgb = array($r, $g, $b);
       $rgb = 'rgba(' . $r.','. $g.','. $b.', '.$p . ')';

       //return implode(",", $rgb); // returns the rgb values separated by commas
       return $rgb; // returns an array with the rgb values
    }
}

if ( ! function_exists( 'tsz_get_alert' ) ) {

    function tsz_get_alert()
    {
        $red_area = get_option( 'videotouch_red_area', array() );

        if ( isset( $red_area['alert']['id'], $red_area['alert']['message'] ) ) {

            if ( $red_area['alert']['id'] !== 0 && ! empty( $red_area['alert']['message'] ) ) {

                if ( is_array( $red_area['hidden_alerts'] ) ) {

                    if ( ! in_array( $red_area['alert']['id'], $red_area['hidden_alerts'] ) ) {

                        echo
                            '<div class="updated">
                                <p>' .
                                    $red_area['alert']['message'] .
                                    ' | <a href="#" class="ts-remove-alert" data-token="' . wp_create_nonce( 'remove-videotouch-alert' ) . '" data-alets-id="' . $red_area['alert']['id'] . '">' . esc_html__('Hide', 'touchsize') . '</a>
                                </p>
                            </div><br/>';
                    }
                }
            }
        }

        // Get alert if theme has updates.
        $updates = get_site_transient( 'update_themes' );
        $current = wp_get_theme();

        if ( isset( $updates->response[ strtolower( $current->Name ) ] ) && version_compare( $current->Version, $updates->response[ strtolower( $current->Name ) ]['new_version'], '<' ) ) {

            echo
                '<div class="updated">
                    <h3>' . __('Attention', 'touchsize') . '!</h3>
                    <p>' . __( '<b>You are using an old version of the theme. To ensure maximum compatibility and bugs fixed please keep the theme up to date.</b> <br>Do not forget that changes done directly in the theme files will be lost, use only Custom CSS areas and child themes if you wish to make changes.', 'touchsize' ) .
                        '<br><br><a href="' . network_admin_url( 'update-core.php' ) . '" class="button button-primary">' . __( 'Update now', 'touchsize' ) . '</a>
                    </p>
                </div><br><br>';
        }

        $update_options = get_option( 'videotouch_theme_update' );

        if  (
                ( empty( $update_options['update_options']['user_name'] ) || empty( $update_options['update_options']['key_api'] ) ) &&
                ( ! isset( $red_area['hidden_alerts'] ) || ! is_array( $red_area['hidden_alerts'] ) || ! in_array( 'empty-envato-info', $red_area['hidden_alerts'], true ) )
            )
        {
            echo
                '<div class="updated">
                    <p>' .
                        esc_html__( 'To make sure you receive update notifications and to be able to update directly from the Dashboard please add your username and API key.', 'touchsize' ) .
                        '<br><a class="button button-primary" href="' . admin_url( 'themes.php?page=' . strtolower( wp_get_theme()->get( 'Name' ) ) . '&tab=theme_update' ) . '">' .
                            esc_html__( 'Set user and API Key', 'touchsize' ) .
                        '</a> ' .
                        '<a href="#" class="ts-remove-alert button-secondary" data-token="' . wp_create_nonce( 'remove-videotouch-alert' ) . '" data-alets-id="empty-envato-info">' .
                            esc_html__( 'Hide notice', 'touchsize' ) .
                        '</a>
                    </p>
                </div><br/>';
        }
    }
}
add_action( 'admin_notices', 'tsz_get_alert' );

$update_options = get_option( 'videotouch_theme_update' );

if ( isset( $update_options['update_options'] ) ) {

    load_template( trailingslashit( get_template_directory() ) . 'includes/envato-wp-theme-updater.php' );

    new Envato_WP_Theme_Updater( $update_options['update_options']['user_name'], $update_options['update_options']['key_api'], 'upcode' );
}

if ( ! function_exists('ts_update_redarea')) {
    function ts_update_redarea() {
        $option = get_option('videotouch_red_area', array());

        if (isset($option['time'])) {

            $current_time = time();

            if ( ($current_time - (int)$option['time']) >= 3600 ) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }
}

if ( ! function_exists('theme_styles_rewrite')) {
    function theme_styles_rewrite() {
        // Get thene background color
         $theme = wp_get_theme();
         $nameTheme = (isset($theme) && is_object($theme)) ? $theme->name : '';
         $versionTheme = (isset($theme) && is_object($theme)) ? $theme->version : '';
        ?>
        <style type="text/css">
            /*************** Theme:  <?php echo $nameTheme; ?> *************/
            /*************** Theme Version:  <?php echo $versionTheme; ?> ************/
            a{
                color: <?php echo ts_get_color('link_color'); ?>;
            }
            a:hover, a:focus{
                color: <?php echo ts_get_color('link_color_hover'); ?>;
            }
            .post-navigator ul li a:hover div{
                color: <?php echo ts_get_color('link_color_hover'); ?>;
            }
            .ts-grid-view article a, .ts-thumbnail-view article a, .ts-big-posts article a, .ts-list-view article a, .ts-super-posts article a, .product-view article a{
                color: <?php echo ts_get_color('view_link_color'); ?>;
            }
            .product-view article .entry-categories a{
                color: <?php echo ts_get_color('meta_color'); ?>;
            }
            .archive-title span,
            .archive-desc p,
            footer .related .related-list .related-content .ts-view-entry-meta-date,
            .ts-timeline-view .entry-meta .post-date-add,
            .ts-grid-view article .ts-view-entry-meta-date,
            .ts-bigpost-view article .ts-view-entry-meta-date,
            .ts-list-view article .ts-view-entry-meta-date{
                color: <?php echo ts_get_color('meta_color'); ?>;
            }
            article .overlay-effect,
            article .overlay-effect a,
            .ts-grid-view article .overlay-effect a:hover, .ts-thumbnail-view article .overlay-effect a:hover, .ts-big-posts article .overlay-effect a:hover, .ts-list-view article .overlay-effect a:hover, .ts-super-posts article .overlay-effect a:hover, .product-view article .overlay-effect a:hover{
                color: <?php echo ts_get_color('primary_text_color'); ?>;
            }
            article .overlay-effect{
                background-color: <?php echo hex2rgb(ts_get_color('primary_color'), 0.8); ?>;
            }
            .ts-grid-view article a:hover, .ts-thumbnail-view article a:hover, .ts-big-posts article a:hover, .ts-list-view article a:hover, .ts-super-posts article a:hover, .product-view article a:hover{
                color: <?php echo ts_get_color('view_link_color_hover'); ?>;
            }
            body{
                color: <?php echo ts_get_color('general_text_color'); ?>;
            }
            .ts-user-profile-dw .user-info a,
            .ts-user-header-profile .ts-show-login-modal{
                color: inherit;
            }
            .ts-user-profile-dw .user-info .dropdown > .dropdown-menu a:hover{
                color: <?php echo ts_get_color('general_text_color'); ?>;
            }
            .ts-user-profile-dw .user-info .user-role,
            .ts-user-header-profile .ts-show-register-modal{
                color: inherit;
                opacity: 0.7;
            }
            .flickr_badge_image:hover a img{
                border-color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .teams article .article-title, .post-slider .post-slider-list .entry-title h4 i{
                background-color: <?php echo ts_get_color('primary_color'); ?>;
                color: <?php echo ts_get_color('primary_text_color'); ?>;
            }
            body.videotouch .wp-playlist-light .wp-playlist-playing, body.videotouch .mejs-controls .mejs-time-rail .mejs-time-current{
                background-color: <?php echo ts_get_color('primary_color'); ?>;
                color: <?php echo ts_get_color('primary_text_color'); ?>;
            }
            .woocommerce #content div.product p.price, .woocommerce #content div.product span.price, .woocommerce div.product p.price, .woocommerce div.product span.price, .woocommerce-page #content div.product p.price, .woocommerce-page #content div.product span.price, .woocommerce-page div.product p.price, .woocommerce-page div.product span.price,
            .woocommerce .woocommerce-message, .woocommerce-page .woocommerce-message {
                color: <?php echo ts_get_color('primary_color') ?>;
            }
            .woocommerce span.onsale, .woocommerce-page span.onsale,
            .woocommerce #content div.product .woocommerce-tabs ul.tabs li, .woocommerce div.product .woocommerce-tabs ul.tabs li{
                background: <?php echo ts_get_color('primary_color') ?>;
                color: <?php echo ts_get_color('primary_text_color'); ?>;
            }
            .woocommerce #content div.product .woocommerce-tabs ul.tabs li.active{
                background-color: #f7f7f7;
                color: #343434 !important;
            }
            .woocommerce #content .woocommerce-result-count{
                color: <?php echo ts_get_color('primary_color'); ?>;
                border-color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .woocommerce .widget_price_filter .ui-slider .ui-slider-range,
            .woocommerce-page .widget_price_filter .ui-slider .ui-slider-range,
            .woocommerce .widget_price_filter .ui-slider .ui-slider-handle,
            .woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle{
                background-color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .woocommerce .widget_layered_nav_filters ul li a,
            .woocommerce-page .widget_layered_nav_filters ul li a{
                background-color: <?php echo ts_get_color('primary_color'); ?>;
                border-color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .woocommerce #content div.product form.cart .variations label,
            .woocommerce div.product form.cart .variations label,
            .woocommerce-page #content div.product form.cart .variations label,
            .woocommerce-page div.product form.cart .variations label{
                color: <?php echo ts_get_color('general_text_color'); ?>;
            }
            .woocommerce #content div.product .woocommerce-tabs ul.tabs li.active,
            .woocommerce div.product .woocommerce-tabs ul.tabs li.active,
            .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active,
            .woocommerce-page div.product .woocommerce-tabs ul.tabs li.active{
                background-color: #f7f7f7;
                color: #343434;
            }
            .woocommerce #content .quantity .minus,
            .woocommerce .quantity .minus,
            .woocommerce-page #content .quantity .minus,
            .woocommerce-page .quantity .minus,
            .woocommerce #content .quantity .plus,
            .woocommerce .quantity .plus,
            .woocommerce-page #content .quantity .plus,
            .woocommerce-page .quantity .plus{
                background-color: <?php echo ts_get_color('secondary_color'); ?>;
                color: <?php echo ts_get_color('secondary_text_color'); ?>;
            }
            .woocommerce #content .quantity .minus:hover,
            .woocommerce .quantity .minus:hover,
            .woocommerce-page #content .quantity .minus:hover,
            .woocommerce-page .quantity .minus:hover,
            .woocommerce #content .quantity .plus:hover,
            .woocommerce .quantity .plus:hover,
            .woocommerce-page #content .quantity .plus:hover,
            .woocommerce-page .quantity .plus:hover{
                background-color: <?php echo ts_get_color('primary_color_hover'); ?>;
                color: <?php echo ts_get_color('primary_text_color_hover'); ?>;
            }
            .woocommerce #content input.button,
            .woocommerce #respond input#submit,
            .woocommerce a.button,
            .woocommerce button.button,
            .woocommerce input.button,
            .woocommerce-page #content input.button,
            .woocommerce-page #respond input#submit,
            .woocommerce-page a.button,
            .woocommerce-page button.button,
            .woocommerce-page input.button,
            .woocommerce .woocommerce-error .button,
            .woocommerce .woocommerce-info .button,
            .woocommerce .woocommerce-message .button,
            .woocommerce-page .woocommerce-error .button,
            .woocommerce-page .woocommerce-info .button,
            .woocommerce-page .woocommerce-message .button{
                background: transparent;
                color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .woocommerce #content input.button:hover,
            .woocommerce #respond input#submit:hover,
            .woocommerce a.button:hover,
            .woocommerce button.button:hover,
            .woocommerce input.button:hover,
            .woocommerce-page #content input.button:hover,
            .woocommerce-page #respond input#submit:hover,
            .woocommerce-page a.button:hover,
            .woocommerce-page button.button:hover,
            .woocommerce-page input.button:hover{
                background: transparent;
                color: <?php echo ts_get_color('primary_color_hover'); ?> !important;
            }
            .woocommerce #content input.button.alt,
            .woocommerce #respond input#submit.alt,
            .woocommerce a.button.alt,
            .woocommerce button.button.alt,
            .woocommerce input.button.alt,
            .woocommerce-page #content input.button.alt,
            .woocommerce-page #respond input#submit.alt,
            .woocommerce-page a.button.alt,
            .woocommerce-page button.button.alt,
            .woocommerce-page input.button.alt{
                background-color: <?php echo ts_get_color('primary_color'); ?>;
                color: <?php echo ts_get_color('primary_text_color'); ?>;
            }
            .woocommerce #content input.button.alt:hover,
            .woocommerce #respond input#submit.alt:hover,
            .woocommerce a.button.alt:hover,
            .woocommerce button.button.alt:hover,
            .woocommerce input.button.alt:hover,
            .woocommerce-page #content input.button.alt:hover,
            .woocommerce-page #respond input#submit.alt:hover,
            .woocommerce-page a.button.alt:hover,
            .woocommerce-page button.button.alt:hover,
            .woocommerce-page input.button.alt:hover{
                background: <?php echo ts_get_color('primary_color_hover'); ?> !important;
                color: <?php echo ts_get_color('primary_text_color_hover'); ?> !important;
            }
            .woocommerce .woocommerce-info,
            .woocommerce-page .woocommerce-info,
            .woocommerce .woocommerce-message,
            .woocommerce-page .woocommerce-message{
                border-color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .woocommerce .woocommerce-error,
            .woocommerce-page .woocommerce-error{
                border-color: #a80023;
            }
            .woocommerce .woocommerce-error:before,
            .woocommerce-page .woocommerce-error:before{
                color: #a80023;
            }
            .woocommerce .woocommerce-info:before,
            .woocommerce-page .woocommerce-info:before,
            .woocommerce .woocommerce-message:before,
            .woocommerce-page .woocommerce-message:before{
                color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .woocommerce #content div.product .woocommerce-tabs .panel,
            .woocommerce div.product .woocommerce-tabs .panel{
                background-color: #f7f7f7;
            }
            .product-view .overlay-effect .entry-overlay > a{
                color: <?php echo ts_get_color('secondary_text_color'); ?>;
                background-color: <?php echo ts_get_color('secondary_color'); ?>;
            }
            .product-view .overlay-effect .entry-overlay > a:hover{
                color: <?php echo ts_get_color('secondary_text_color_hover'); ?>;
                background-color: <?php echo ts_get_color('secondary_color_hover'); ?>;
            }
            .product-view .overlay-effect .entry-overlay > a:not(.entry-view-more){
                color: <?php echo ts_get_color('primary_text_color'); ?>;
                background-color: <?php echo ts_get_color('primary_color') ?>
            }
            .product-view .overlay-effect .entry-overlay > a:not(.entry-view-more):hover{
                color: <?php echo ts_get_color('primary_text_color_hover'); ?> !important;
                background-color: <?php echo ts_get_color('primary_color_hover'); ?> !important;
            }
            .ts-features-default section .readmore a:after,
            .ts-features-fullbg footer .readmore{
                background-color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .ts-features-fullbg footer .readmore a{
                color: <?php echo ts_get_color('primary_text_color'); ?>;
            }
            .ts-features-default header .article-header-content .image-container{
                color: <?php echo ts_get_color('primary_text_color'); ?>;
            }
            .ts-features-fullbg article:hover header .article-header-content .image-container{
                color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .block-title-lineariconcenter .block-title-container i[class^="icon"]{
                color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .ts-features-default section .readmore a:hover span{
                color: <?php echo ts_get_color('primary_text_color'); ?>;
            }
            .ts-features-fullbg header .article-header-content .image-container{
                box-shadow: inset 0 0 0 10px <?php echo ts_get_color('primary_text_color'); ?>;
            }
            .ts-features-fullbg article:hover header .article-header-content .image-container{
                box-shadow: inset 0 0 0 3px <?php echo ts_get_color('primary_text_color'); ?>;
            }
            .ts-features-fullbg header .article-header-content .image-container:after{
                background-color: <?php echo ts_get_color('primary_text_color'); ?>;
            }
            .carousel-wrapper ul.carousel-nav > li:hover {
                background-color: <?php echo ts_get_color('primary_color_hover'); ?>;
                color: <?php echo ts_get_color('primary_text_color_hover'); ?>;
            }
            .ts-clients-view .carousel-wrapper ul.carousel-nav > li:hover {
                background-color: <?php echo ts_get_color('primary_color_hover'); ?>;
                color: <?php echo ts_get_color('primary_text_color_hover'); ?>;
            }
            .ts-clients-view div[data-tooltip]:hover:before {
                background-color: <?php echo hex2rgb(ts_get_color('primary_color'), '0.8'); ?>;
                color: <?php echo ts_get_color('primary_text_color'); ?>;
            }
            .ts-clients-view div[data-tooltip]:hover:after {
                border-top-color: <?php echo hex2rgb(ts_get_color('primary_color'), '0.8'); ?>;
            }
            .ts-header-menu .main-menu li a:hover,
            .ts-sticky-menu .main-menu li a:hover,
            .ts-mobile-menu .main-menu li a:hover {
                color: <?php echo ts_get_color('submenu_text_color_hover'); ?>;
            }
            .ts-header-menu .main-menu > .menu-item-has-children ul li > a:before,
            .ts-sticky-menu .main-menu > .menu-item-has-children ul li > a:before,
            .ts-mobile-menu .main-menu > .menu-item-has-children ul li > a:before{
                background-color: <?php echo ts_get_color('submenu_bg_color_hover'); ?>;
            }
            .ts-header-menu .main-menu li > a,
            .ts-sticky-menu .main-menu li > a,
            .ts-mobile-menu .main-menu li > a {
                color: <?php echo ts_get_color('submenu_text_color'); ?>;
            }
            .ts-header-menu .sub-menu,
            .ts-sticky-menu .sub-menu,
            .ts-mobile-menu .sub-menu {
                background-color: <?php echo ts_get_color('submenu_bg_color'); ?>;
            }
            .ts-mega-menu .main-menu .ts_is_mega_div .title{
                color: <?php echo ts_get_color('submenu_text_color'); ?>;
            }
            .ts-mega-menu .main-menu .ts_is_mega_div .title:after,
            .ts-mobile-menu .main-menu .ts_is_mega_div .title:after{
                background-color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .rsDefault .rsArrowIcn,
            .rsDefault .rsThumbsArrowIcn {
                background-color: <?php echo hex2rgb(ts_get_color('primary_color'), '0.7'); ?>;
            }
            .rsDefault .rsArrowIcn:hover,
            .rsDefault .rsThumbsArrowIcn:hover {
                background-color: <?php echo ts_get_color('primary_color_hover'); ?>;
            }
            .rsDefault .rsBullet span {
                background-color: <?php echo hex2rgb(ts_get_color('primary_color'), '0.3'); ?>;
            }
            .rsDefault .rsBullet.rsNavSelected span {
                background-color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .ts-header-menu .main-menu .current-menu-item > a,
            .ts-header-menu .main-menu .current-menu-parent > a,
            .ts-header-menu .main-menu .current-menu-ancestor > a,
            .ts-mobile-menu .main-menu .current-menu-item > a,
            .ts-mobile-menu .main-menu .current-menu-parent > a,
            .ts-mobile-menu .main-menu .current-menu-ancestor  > a,
            .ts-sticky-menu .main-menu .current-menu-item > a,
            .ts-sticky-menu .main-menu .current-menu-parent > a,
            .ts-sticky-menu .main-menu .current-menu-ancestor  > a{
                color: <?php echo ts_get_color('primary_color'); ?> !important;
            }
            .sub-menu li a:hover{
                color: <?php echo ts_get_color('submenu_text_color_hover'); ?>;
            }
            .testimonial-item .author-position{
                color: <?php echo ts_get_color('meta_color'); ?>;
            }
            .sf-default li:after{
                background: <?php echo ts_get_color('primary_color'); ?>;
            }
            .post-title-meta, .ts-big-posts .big-post-meta > ul > li, .ts-grid-view .entry-meta > ul > li, .views-delimiter{
                color: <?php echo ts_get_color('meta_color'); ?>;
            }
            .tags-container a.tag, .tags-container a[rel="tag"], .ts-list-view-tags a[rel="tag"]{
                background-color: <?php echo ts_get_color('primary_color'); ?>;
                color: <?php echo ts_get_color('primary_text_color'); ?>;
            }
            .tags-container a.tag:hover, .tags-container a[rel="tag"]:hover, .ts-list-view-tags a[rel="tag"]:hover{
                background-color: <?php echo ts_get_color('secondary_color'); ?>;
                color: <?php echo ts_get_color('secondary_text_color_hover'); ?>;
            }
            .ts-thumbnail-view .thumb-post-categories a, .ts-grid-view .grid-post-categories a, .ts-big-posts .big-post-categories a, .post-title-meta .post-categories a, .ts-super-posts .ts-super-posts-categories a{
                color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .ts-thumbnail-view .thumb-post-categories a:hover, .ts-grid-view .grid-post-categories a:hover, .ts-big-posts .big-post-categories a:hover, .post-title-meta .post-categories a:hover, .ts-super-posts .ts-super-posts-categories a:hover{
                color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .user-profile-page .edit-post-link{
                color: <?php echo ts_get_color('primary_text_color'); ?>;
                background-color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .user-profile-page .edit-post-link:hover{
                color: <?php echo ts_get_color('primary_text_color_hover'); ?>;
                background-color: <?php echo ts_get_color('primary_color_hover'); ?>;
            }
            #searchbox input[type="text"]:focus{
                border-bottom-color: <?php echo ts_get_color('primary_color'); ?>;
            }
            #searchbox input.searchbutton:hover + i.icon-search{
                color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .search-no-results .searchpage,
            .search .attention{
                color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .video-single-resize:hover b{
                color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .search-results .searchcount{
                color: <?php echo ts_get_color('meta_color'); ?>;
            }
            #commentform .form-submit input[type="submit"]{
                color: <?php echo ts_get_color('primary_text_color'); ?>;
            }
            .post-navigator ul li a div{
                color: <?php echo ts_get_color('link_color'); ?>;
            }
            .widget-title:after {
                background: <?php echo ts_get_color('primary_color'); ?>;
                display: block;
                content: '';
                width: 30px;
                height: 2px;
                margin-top: 10px;
            }

            .post-navigator ul li a:hover div{
                color: <?php echo ts_get_color('link_color_hover'); ?>;
            }
            .callactionr a.continue, .commentlist > li .comment .comment-reply-link{
                background-color: <?php echo ts_get_color('primary_color'); ?>;
                color: <?php echo ts_get_color('primary_text_color'); ?>;
            }
            .callactionr a.continue:hover{
                background-color: <?php echo ts_get_color('secondary_color'); ?>;
                color: <?php echo ts_get_color('secondary_text_color'); ?>;
            }
            .block-title-lineafter .block-title-container .the-title:after{
                background-color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .ts-grid-view .entry-meta a, .ts-big-posts .big-post-meta a, .post-author-box > .author-title{
                color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .ts-list-view .readmore{
                background-color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .ts-super-posts .title-holder{
                background-color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .ts-thumbnail-view .item-hover{
                /*background-color: <?php echo hex2rgb(ts_get_color('primary_color'), '0.8') ?>;*/
            }
            .ts-thumbnail-view .item-hover span, .ts-grid-view .item-hover span{
                color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .ts-grid-view .item-hover{
                background-color: <?php echo hex2rgb(ts_get_color('secondary_color'), '0.8') ?>;
            }
            .ts-grid-view .readmore:hover{
                color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .ts-filters li a.active{
                background-color: <?php echo ts_get_color('primary_color'); ?>;
                color: <?php echo ts_get_color('primary_text_color'); ?>;
            }
            .ts-filters li a.active:after{
                border-top-color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .ts-filters li a:not(.active):hover{
                color: <?php echo ts_get_color('secondary_color'); ?>;
            }
            .post-navigator ul li a{
                border-top-color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .post-navigator ul li a:hover{
                border-top-color: <?php echo ts_get_color('secondary_color'); ?>;
            }
            #commentform .form-submit input[type="submit"]{
                background-color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .listed-two-view .item-hover, .ts-big-posts .item-hover{
                background-color: <?php echo hex2rgb(ts_get_color('primary_color'), '0.8') ?>;
            }
            .block-title-linerect .block-title-container:before{
                background-color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .teams article:hover .image-holder img{
                border-color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .teams article:hover .article-title{
                border-color: <?php echo ts_get_color('secondary_color'); ?>;
            }
            .delimiter.iconed:before{
                color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .block-title-leftrect .block-title-container:before{
                background-color: <?php echo ts_get_color('primary_color'); ?>;
            }
            a.tag:hover, a[rel="tag"]:hover{
                color: <?php echo ts_get_color('primary_color'); ?>;
            }
            input.contact-form-submit{
                background: <?php echo ts_get_color('primary_color'); ?>;
            }
            .dl-menuwrapper button:hover,
            .dl-menuwrapper button.dl-active,
            .dl-menuwrapper ul {
                background: <?php echo ts_get_color('primary_color'); ?>;
            }
            .dl-menuwrapper button{
                background: <?php echo ts_get_color('secondary_color'); ?>;
            }
            .post-slider .post-slider-list .entry-category ul li a, .post-slider .main-entry .entry-category a{
                color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .post-slider .main-entry .entry-content .entry-title:hover{
                border-right-color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .tweet-entry .icon-twitter{
                color: <?php echo ts_get_color('primary_color'); ?>;
            }
            /* Set the background and text color of the view articles */

            .ts-pagination ul .page-numbers{
                background: #f7f7f7;
                color: #343434;
            }
            .ts-pagination ul .page-numbers.current{
                background: <?php echo ts_get_color('primary_color'); ?>;
                color: <?php echo ts_get_color('primary_text_color'); ?>;
            }
            .ts-pagination ul .page-numbers:hover{
                background: <?php echo ts_get_color('secondary_color'); ?>;
            }
            .views-read-more{
                background: <?php echo ts_get_color('primary_color'); ?>;
                color: <?php echo ts_get_color('primary_text_color'); ?> !important;
            }
            #searchform input[type="submit"]{
                color: <?php echo ts_get_color('general_text_color'); ?>;
            }
            .ts-pricing-view article > header:after{
                border-top-color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .ts-pricing-view article > header, .ts-pricing-view article > footer a.btn{
                background: <?php echo ts_get_color('primary_color'); ?>;
                color: <?php echo ts_get_color('primary_text_color'); ?>;
            }
            .slyscrollbar .handle{
                background: <?php echo ts_get_color('primary_color'); ?>;
            }
            .touchsize-likes .touchsize-likes-count:before, .post-meta .post-meta-likes span.touchsize-likes-count:before{
                color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .touchsize-likes.active .touchsize-likes-count:before, .post-meta .post-meta-likes .touchsize-likes.active span.touchsize-likes-count:before{
                color: <?php echo ts_get_color('primary_color_hover'); ?>;
            }
            .ts-grid-view article .entry-footer .btn-play-video:hover > i{
                background: <?php echo ts_get_color('primary_color'); ?>;
                color: <?php echo ts_get_color('primary_text_color'); ?>;
            }
            .btn:hover,
            .btn:active,
            .btn:focus{
                border-color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .btn.active{
                background-color: <?php echo ts_get_color('primary_color'); ?>;
                color: <?php echo ts_get_color('primary_text_color'); ?>;
            }
            .purchase-btn{
                color: <?php echo ts_get_color('secondary_color'); ?>;
            }
            .purchase-btn:hover{
                background: <?php echo ts_get_color('secondary_color'); ?>;
            }
            .mCS-dark.mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar{
                background: <?php echo ts_get_color('primary_color'); ?>;
            }
            .mCS-dark.mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar:hover, .mCS-dark.mCSB_scrollTools .mCSB_dragger:hover .mCSB_dragger_bar{
                background: <?php echo ts_get_color('primary_color_hover'); ?>;
            }
            .mosaic-view article section{
                /*background: <?php echo hex2rgb(ts_get_color('primary_color'), 0.7) ?>;*/
            }
            .nav-tabs .tab-item.active > a:before,
            .nav-tabs .tab-item.active > a:hover:before,
            .nav-tabs .tab-item.active > a:focus:before{
                border-color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .ts-tags-container > a:after,
            a.tag:hover, a[rel="tag"]:hover,
            article .default-effect .overlay-effect .view-more > span:before,
            article .default-effect .overlay-effect .view-more > span:after{
                background: <?php echo ts_get_color('primary_color'); ?>;
            }
            article.type-post .page-title .touchsize-likes .touchsize-likes-count{
                color: <?php echo ts_get_color('meta_color'); ?>;
            }
            .ts-thumbnail-view article h3.title:after{
                background: <?php echo ts_get_color('primary_color'); ?>;
            }
            .ts-user-login-modal .modal-title{
                color: <?php echo ts_get_color('general_text_color'); ?>;
            }
            #ts-timeline .timeline-entry:before, .ts-grid-view article .entry-footer .btn-grid-more:hover > i{
                background-color: <?php echo ts_get_color('primary_color'); ?>;
            }
            .ts-video-carousel .nav-arrow .nav-icon{
                color: <?php echo ts_get_color('primary_color'); ?>;
            }
            body.single-video article.type-video .post-content .content-cortina{
                <?php
                    $bg = get_option('videotouch_styles');
                    $site_background_color = $bg['theme_bg_color'];
                ?>
                background: -moz-linear-gradient(top, <?php echo hex2rgb($site_background_color, 0) ?> 0%, <?php echo hex2rgb($site_background_color, 0.7) ?> 41%, <?php echo hex2rgb($site_background_color, 1) ?> 83%); /* FF3.6+ */
                background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,<?php echo hex2rgb($site_background_color, 0) ?>), color-stop(41%,<?php echo hex2rgb($site_background_color, 0.7) ?>), color-stop(83%,<?php echo hex2rgb($site_background_color, 1) ?>)); /* Chrome,Safari4+ */
                background: -webkit-linear-gradient(top,  <?php echo hex2rgb($site_background_color, 0) ?> 0%,<?php echo hex2rgb($site_background_color, 0.7) ?> 41%,<?php echo hex2rgb($site_background_color, 1) ?> 83%); /* Chrome10+,Safari5.1+ */
                background: -o-linear-gradient(top,  <?php echo hex2rgb($site_background_color, 0) ?> 0%,<?php echo hex2rgb($site_background_color, 0.7) ?> 41%,<?php echo hex2rgb($site_background_color, 1) ?> 83%); /* Opera 11.10+ */
                background: -ms-linear-gradient(top,  <?php echo hex2rgb($site_background_color, 0) ?> 0%,<?php echo hex2rgb($site_background_color, 0.7) ?> 41%,<?php echo hex2rgb($site_background_color, 1) ?> 83%); /* IE10+ */
                background: linear-gradient(to bottom,  <?php echo hex2rgb($site_background_color, 0) ?> 0%,<?php echo hex2rgb($site_background_color, 0.7) ?> 41%,<?php echo hex2rgb($site_background_color, 1) ?> 83%); /* W3C */
                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00ffffff', endColorstr='#ffffff',GradientType=0 ); /* IE6-9 */
            }
            <?php echo ts_custom_background(); ?>
            <?php echo ts_get_logo_css(); ?>
            <?php echo ts_get_custom_fonts_css('headings') ?>
            <?php echo ts_get_custom_fonts_css('primary_text') ?>
            <?php echo ts_get_custom_fonts_css('secondary_text') ?>
            <?php echo ts_get_sticky_menu_style() ?>
            /* --- Custom CSS Below ----  */
            <?php echo ts_get_custom_css() ?>
        </style>
        <?php
    }
}

if ( ! function_exists('ts_get_custom_css')) {
    function ts_get_custom_css() {
        $option = get_option('videotouch_css', array('css' => ''));
        return $option['css'];
    }
}

/* register sidebars */
if ( function_exists('register_sidebar') ) {
    register_sidebar(array(
        'name' => __( 'Main Sidebar', 'touchsize' ),
        'id' => 'main',
        'before_widget' => '<aside id="%1$s" class="widget ts_widget %2$s"><div class="widget-content">',
        'after_widget'  => '</div></aside>',
        'before_title'  => '<h4 class="widget-title ts_sidebar_title">',
        'after_title'   => '</h4><div class="widget-delimiter"></div>'
    ));

}

function ts_imagesloaded($bool, $img_url){
    if( $bool == 'Y' ){
        $src = 'src="'. get_template_directory_uri() .'/images/loader.gif'.'" data-echo="'. esc_url($img_url) .'"';
    }else{
        $src = 'src="'. esc_url($img_url) .'"';
    }

    return $src;
}

function menuCallback(){
    wp_page_menu(array(
        'menu_class'  => 'ts-behold-menu main-menu ',
        'include'     => '',
        'exclude'     => '',
        'echo'        => true,
        'link_before' => '',
        'link_after'  => ''
    ));
}

function extended_upload_mimes ( $mime_types =array() ) {
    $mime_types['svg'] = 'image/svg+xml';
    $mime_types['woff'] = 'image/x-woff';
    $mime_types['ttf'] = 'image/x-font-ttf';
    $mime_types['eot'] = 'image/vnd.ms-fontobject';
    $mime_types['mp4'] = 'webm/mp4';
    $mime_types['webm'] = 'webm/webm';

    unset( $mime_types['exe'] );

    return $mime_types;
}
add_filter('upload_mimes', 'extended_upload_mimes');


// Check if mega menu option is enabled and add mega menu support
$ts_is_mega_menu_option = get_option('videotouch_general');
if( !isset($ts_is_mega_menu_option['enable_mega_menu'] ) ){
    $ts_is_mega_menu_option['enable_mega_menu'] = 'N';
}

if( $ts_is_mega_menu_option['enable_mega_menu'] == 'Y' ) {

    add_theme_support( 'ts_is_mega_menu' );

}

function ts_set_post_views($postID) {
    $count_key = 'ts_article_views';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

function ts_track_post_views ($post_id) {
    if ( !is_single() ) return;
    if ( empty($post_id) ) {
        global $post;
        $post_id = $post->ID;
    }
    ts_set_post_views($post_id);
}
add_action( 'wp_head', 'ts_track_post_views');

function ts_get_views($postID, $show = true){

    $count = get_post_meta($postID, 'ts_article_views', true);
    if( $count == '' ){
        ts_set_post_views($postID);
        $count = 0;
    }
    if( $show == true ){
        echo $count;
    }else{
        return $count;
    }
}

//get icon format by post format
function ts_get_icon_format($post_id){

    $post_format = get_post_format( $post_id );

    if( isset($post_format) ){

        if( $post_format == 'video' ){
            echo '<i class="icon-video"></i>';
        }else if( $post_format == 'gallery' ){
            echo '<i class="icon-gallery"></i>';
        }else{
            echo '<i class="icon-page"></i>';
        }

    }else{
        echo '<i class="icon-page"></i>';
     }
}

// ADD NEW COLUMN ts_pricing_table
function ts_add_custom_true($columns) {

    $postType = get_post_type(get_the_ID());

    if( $postType == 'video' || $postType == 'post' ){
        $columns['featured_article'] = 'Featured';
    }

    return $columns;
}
add_filter('manage_posts_columns', 'ts_add_custom_true', 10, 1);

// show the colums featured
function ts_columns_content_featured($columnName, $post_ID) {

    $postType = get_post_type($post_ID);

    if( $postType == 'video' || $postType == 'post' && $columnName == 'featured_article' ){

        $meta_values = get_post_meta($post_ID, 'featured', true);
        $selected = $meta_values == 'yes' ? 'checked' : '';

        echo '<input type="checkbox"'. $selected .' name="featured_article" class="featured" value="'. $post_ID .'">';
        echo '<input type="hidden" value="updateFeatures" />';

    }
}
add_action('manage_posts_custom_column', 'ts_columns_content_featured', 10, 2);
//get the pagination in single item
function ts_get_pagination_next_previous(){

    $enable_pagination = get_option('videotouch_single_post', array('post_pagination' => 'Y'));
    if( isset($enable_pagination) && is_array($enable_pagination) && !empty($enable_pagination) && isset($enable_pagination['post_pagination']) && $enable_pagination['post_pagination'] == 'Y' ){

        $next_post = get_next_post();
        $prev_post = get_previous_post();

        ?>
        <nav class="ts-post-nav nav-growpop">
            <?php if( !empty($prev_post) ) :  ?>
                <a class="prev" href="<?php echo get_permalink( $prev_post->ID, false ); ?>">
                    <span class="icon-wrap"><i class="icon-left"></i></span>
                    <?php
                        $src = wp_get_attachment_url( get_post_thumbnail_id( $prev_post->ID ) );
                        $class_no_image = '';
                        if( !$src ){
                            $class_no_image = 'no-image';
                        }
                     ?>
                    <div class="<?php echo $class_no_image ?>">
                        <span><?php _e('Previous Post','touchsize') ?></span>
                        <h3><?php echo esc_attr($prev_post->post_title); ?></h3>
                        <p><?php _e('by ','touchsize'); the_author_meta( 'display_name', $prev_post->post_author ); ?></p>
                        <?php
                            if ( $src ) {
                                $featimage = '<img src="'.aq_resize( $src, 100, 90, true, true).'" alt="' . esc_attr(get_the_title()) . '" />';
                            } else {
                                $featimage = '';
                            }
                            echo $featimage;
                        ?>
                    </div>
                </a>
            <?php endif ?>
            <?php if( !empty($next_post) ) : ?>
                <a class="next" href="<?php echo get_permalink( $next_post->ID, false ); ?>">
                    <span class="icon-wrap"><i class="icon-right"></i></span>
                    <?php
                        $src = wp_get_attachment_url( get_post_thumbnail_id( $next_post->ID ) );
                        $class_no_image = '';
                        if( !$src ){
                            $class_no_image = 'no-image';
                        }
                     ?>
                    <div class="<?php echo $class_no_image ?>">
                        <span><?php _e('Next Post','touchsize') ?></span>
                        <h3><?php echo esc_attr($next_post->post_title); ?></h3>
                        <p><?php _e('by ','touchsize'); the_author_meta( 'display_name', $next_post->post_author ); ?></p>
                        <?php
                            if ( $src ) {
                                $featimage = '<img src="'.aq_resize( $src, 100, 90, true, true).'" alt="' . esc_attr(get_the_title()) . '" />';
                            } else {
                                $featimage = '';
                            }
                            echo $featimage;
                        ?>
                    </div>
                </a>
            <?php endif; ?>
        </nav>
<?php
    }//end if(verify enable_pagination )
}//end function ts_get_pagination_next_previous()

// get related post by author
function ts_get_related_posts_author($author_id, $post_id){

    $related_posts = '';
    $single_options = get_option('videotouch_single_post');

    if( isset($single_options) && is_array($single_options) && isset($single_options['related_posts']) && $single_options['related_posts'] == 'Y' ){
        if( isset($post_id) && (int)$post_id !== 0 ){
            $post_type = get_post_type($post_id);
        }
        if( isset($post_type) ){

            $options = array();

            $options['special-effects'] = '';
            $options['display-mode'] = $single_options['related_posts_type'];
            $options['elements-per-row'] = $single_options['related_posts_nr_of_columns'];
            $options['order-direction'] = 'desc';
            $options['order-by'] = 'date';

            if ( $options['display-mode'] === 'grid' ) {
                $options['display-title'] = 'title-above-excerpt';
                $options['show-meta'] = 'y';
                $options['enable-carousel'] = 'n';
            }
            if ( $options['display-mode'] === 'thumbnails' ) {
                $options['meta-thumbnail'] = 'y';
            }

            $args['author'] = $author_id;
            $args['posts_per_page'] = (int)$single_options['number_of_related_posts'];
            $args['orderby '] = 'date';
            $args['order '] = 'DESC';
            $args['post_type'] = $post_type;

            $query = new WP_Query( $args );

            if ( $query->have_posts() ) {
                echo $related_posts . LayoutCompilator::last_posts_element( $options, $query );
            } else {
                echo '';
            }

        }
    }else{
        return FALSE;
    }
}

function ts_breadcrumbs(){

    global $post;

    if ( is_front_page() ) {
        return;
    }

    if ( is_category() || is_single() || is_page() ) {

        $breadcrumbs =  '<div class="ts-breadcrumbs-content">
                            <a href="' . home_url() . '">' . __('Home', 'touchsize') . '</a>';

        if (is_category() || is_single() ) {

            $breadcrumbs .= " / ";

            $post_type = $post->post_type;

            if( $post_type === 'post' ){
                $categoryies = get_the_category($post->ID);
            }else{
                $taxonomies = get_object_taxonomies($post_type);
                $categoryies = wp_get_post_terms($post->ID, $taxonomies);

                $taxonomy_link = '';
            }

            foreach ($categoryies as $category){

                if( isset($category->taxonomy) && $category->taxonomy === 'post_tag' ) break;

                if( is_category() ){
                    $breadcrumbs .= $category->name;
                    break;
                }
                if( $post_type !== 'post' ){
                    foreach($taxonomies as $name_taxonomy){
                        $error_string = get_term_link($category->term_id, $name_taxonomy);
                        if( !is_wp_error($error_string) ){
                            $taxonomy_link = get_term_link($category->term_id, $name_taxonomy);
                        }
                    }
                }

                if( $post_type === 'post' && is_single() ){
                    $breadcrumbs .= '<a href="' . get_category_link($category->term_id) . '">' . $category->name . '</a>';
                }else{
                    if( !empty($taxonomy_link) ) $breadcrumbs .= '<a href="' . $taxonomy_link . '">' . $category->name . '</a>';
                    else $breadcrumbs .= $category->name;
                }
                $breadcrumbs .= " / ";
                break;
            }
            if (is_single()) {
                $breadcrumbs .= esc_attr(get_the_title($post->ID));
            }
            $breadcrumbs .= '</div>';

            return $breadcrumbs;

        } elseif (is_page()) {

            if($post->post_parent){
                $anc = get_post_ancestors($post->ID);
                $anc_link = get_page_link($post->post_parent);

                foreach ($anc as $ancestor) {
                    $breadcrumbs .= " > <a href=" . $anc_link . ">" . esc_attr(get_the_title($ancestor)) . "</a> > ";
                }

                $breadcrumbs .= esc_attr(get_the_title($post->ID));
                $breadcrumbs .= '</div>';
                return $breadcrumbs;

            } else {
                $breadcrumbs .= ' > ' . esc_attr(get_the_title($post->ID));
                $breadcrumbs .= '</div>';
                return $breadcrumbs;
            }
        }
    }elseif( is_tag() ){
        $breadcrumbs = '<div class="ts-breadcrumbs"><a href="' . home_url() . '">' . __('Home', 'touchsize') . '</a> > ' . single_tag_title('', false) . '</div>';
        return $breadcrumbs;
    }elseif( is_day() ){
        $breadcrumbs = '<div class="ts-breadcrumbs"><a href="' . home_url() . '">' . __('Home', 'touchsize') . '</a> > ' . __('Archive: ', 'touchsize') . get_the_date('F jS, Y') . '</div>';
        return $breadcrumbs;
    }elseif( is_month() ){
        $breadcrumbs = '<div class="ts-breadcrumbs"><a href="' . home_url() . '">' . __('Home', 'touchsize') . '</a> > ' . __('Archive: ', 'touchsize') . get_the_date('F, Y') . '</div>';
        return $breadcrumbs;
    }elseif( is_year() ){
        $breadcrumbs = '<div class="ts-breadcrumbs"><a href="' . home_url() . '">' . __('Home', 'touchsize') . '</a> > ' . __('Archive: ', 'touchsize') . get_the_date('Y') . '</div>';
        return $breadcrumbs;
    }elseif( is_author() ){
        $breadcrumbs = '<div class="ts-breadcrumbs"><a href="' . home_url() . '">' . __('Home', 'touchsize') . '</a> > ' . __("Author's archive: ", 'touchsize') . get_the_author() . '</div>';
        return $breadcrumbs;
    }elseif( isset($_GET['paged']) && !empty($_GET['paged']) ){
        $breadcrumbs = '<div class="ts-breadcrumbs"><a href="' . home_url() . '">' . __('Home', 'touchsize') . '</a> > ' . __("Blogarchive: ", 'touchsize') . '</div>';
        return $breadcrumbs;
    }elseif( is_search() ){
        $breadcrumbs = '<div class="ts-breadcrumbs"><a href="' . home_url() . '">' . __('Home', 'touchsize') . '</a> > ' . __("Search results: ", 'touchsize') . '</div>';
        return $breadcrumbs;
    }

}

function ts_base_64($string, $encode_decode){
    if($encode_decode === 'encode' && isset($string) && !empty($string)){
        return base64_encode(serialize($string));
    }else if( $encode_decode === 'decode' && isset($string) && !empty($string) ){
        return @unserialize(base64_decode($string));
    }else return '';
}

function ts_validate_gravatar($email) {
    $hash = md5(strtolower(trim($email)));
    $uri = 'http://www.gravatar.com/avatar/' . $hash . '?d=404';
    $headers = @get_headers($uri);
    if (!preg_match("|200|", $headers[0])) {
        $has_valid_avatar = FALSE;
    } else {
        $has_valid_avatar = TRUE;
    }
    return $has_valid_avatar;
}

function ts_display_gravatar($size){
    global $current_user;
    get_currentuserinfo();
    $get_user_email = $current_user->user_email;

    $user_gravatar = 'http://www.gravatar.com/avatar/' . md5($get_user_email) . '?s=' . $size;
    $avatar = get_avatar(get_the_author_meta( 'ID' ), $size);
    $validate_email_gravatar = ts_validate_gravatar($get_user_email);

    if( $validate_email_gravatar ){
        return '<img src="' . $user_gravatar . '" class="wpb_gravatar" />';
    }else{
        return $avatar;
    }
}

function ts_get_comment_count($post_id) {
    if (fields::get_options_value('videotouch_general', 'comment_system') == 'facebook' ) {
        return '<fb:comments-count href="' . get_permalink($post_id) .'"></fb:comments-count>';
    } else{
        return get_comments_number($post_id);
    }
}

function tsGetPreRoll($options){
    if( !is_array($options) || empty($options) ) return;

    foreach($options as $preRollId => $option){
        if( array_key_exists('active', $option) && $option['active'] == 'n' ){
            unset($options[$preRollId]);
        }
    }

    if( !empty($options) ){

        $randPreRoll = rand(0, count($options) - 1);
        $i = 0;
        $factoryHtml = '';

        foreach($options as  $preRollId => $option){
            if( $i == $randPreRoll ){
                $video = (isset($option['video']) && !empty($option['video'])) ? esc_url($option['video']) : '';
                $link = (isset($option['url']) && !empty($option['url'])) ? '<a class="ts-preroll-linkto" target="_blank" href="'. esc_url($option['url']) .'"></a>' : '';
                $time = (isset($option['time']) && is_int((int)$option['time'])) ? (int)($option['time']) : '';
                $views = (isset($option['views']) && (int)$option['views'] > 0) ? $option['views'] : 0;
                $clicks = (isset($option['clicks']) && (int)$option['clicks'] > 0) ? $option['clicks'] : 0;
                $limit = (isset($option['limit']) && (int)$option['limit'] > 0) ? $option['limit'] : $views + 10;
                $timer = '<div id="pre-roll-counter"><span></span> ' . __('seconds remaining', 'touchsize') . '</div>';
                $image_url = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));

                if( !empty($video) && $limit > $views ){
                    $atts = array(
                        'src'      => $video,
                        'poster'   => $image_url,
                        'loop'     => '',
                        'autoplay' => '',
                        'preload'  => 'metadata',
                        'height'   => 560,
                        'width'    => 1340,
                    );

                    $video = wp_video_shortcode($atts);
                    $factoryHtml .= '<div class="ts-preroll" data-time="'. $time .'" data-views="'. $views .'" data-clicks="'. $clicks .'" data-id="'. $preRollId .'">
                                        '. $video . $link . $timer .'
                                    </div>';
                }
            }
            $i++;
        }

        return $factoryHtml;
    }

}

remove_filter('the_content', 'ccb_single_custom_post_filter', 100);

add_filter('redirect_canonical','ts_disable_redirect_canonical');

function ts_disable_redirect_canonical( $redirect_url )
{
    if ( is_paged() && is_singular() ) $redirect_url = false; return $redirect_url;
}

// Add button socials to single product.
function ts_add_social_single_product()
{ ?>
    <div class="product-sharing-options">
        <span class="post-meta-share">
            <?php get_template_part('social-sharing'); ?>
        </span>
    </div>
    <?php
}

add_action( 'woocommerce_single_product_summary', 'ts_add_social_single_product', 100 );

function ts_woocommerce_breadcrumbs() {
    return array(
            'delimiter'   => ' &#47; ',
            'wrap_before' => '<div class="container"><nav class="woocommerce-breadcrumb" itemprop="breadcrumb">',
            'wrap_after'  => '</div></nav>',
            'before'      => '',
            'after'       => '',
            'home'        => _x( 'Home', 'breadcrumb', 'woocommerce' ),
        );
}

add_filter( 'woocommerce_breadcrumb_defaults', 'ts_woocommerce_breadcrumbs' );

function tsz_custom_post_archive( $query )
{
    if ( is_author() || is_category() || is_tag() ) {

        $args = array(
            'public'   => true,
            '_builtin' => false
        );

        $post_types = get_post_types( $args, 'names' );
        $post_types[] = 'post';

        $query->set( 'post_type', array_values( $post_types ) );
    }

    remove_action( 'pre_get_posts', 'tsz_custom_post_archive' );
}

add_action( 'pre_get_posts', 'tsz_custom_post_archive' );

?>