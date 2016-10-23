<?php
if ( ! function_exists( 'vdf_touchsize_comment' ) ) :

    function vdf_touchsize_comment( $comment, $args, $depth ) {
        $GLOBALS['comment'] = $comment;
        switch ( $comment->comment_type ) :
        case 'pingback' :
        case 'trackback' :
        ?>
        <li class="post pingback">
            <p><?php esc_html_e( 'Pingback:', 'videofly' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( esc_html__( 'Edit', 'videofly' ), '<span class="edit-link">', '</span>' ); ?></p>
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
                            printf( esc_html__( '%1$s on %2$s ', 'videofly' ) . '<span class="says">' . esc_html__( 'said:', 'videofly' ) . '</span>',
                                sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
                                sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
                                    esc_url( get_comment_link( $comment->comment_ID ) ),
                                    get_comment_time( 'c' ),
                                    /* translators: 1: date, 2: time */
                                    sprintf( esc_html__( '%1$s at %2$s', 'videofly' ), get_comment_date(), get_comment_time() )
                                    )
                                );
                                ?>

                                <?php edit_comment_link( esc_html__( 'Edit', 'videofly' ), '<span class="edit-link">', '</span>' ); ?>
                            </div><!-- .comment-author .vcard -->

                            <?php if ( $comment->comment_approved == '0' ) : ?>
                                <em class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'videofly' ); ?></em>
                                <br />
                            <?php endif; ?>

                        </footer>

                        <div class="comment-content"><?php comment_text(); ?></div>

                        <div class="reply">
                            <?php comment_reply_link( array_merge( $args, array( 'reply_text' => esc_html__( 'Reply', 'videofly' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
                        </div><!-- .reply -->
                    </article><!-- #comment-## -->

                    <?php
                    break;
                    endswitch;
                }
endif; // ends check for vdf_touchsize_comment()

function vdf_layout_wrapper($elements)
{

    echo '<script id="dragable-row-tpl" type="text/x-handlebars-template">
    <ul class="layout_builder_row">
        <li class="row-editor">
            <ul class="row-editor-options">
                <li>
                    <a href="#" class="add-column">' . esc_html__( '+', 'videofly' ) . '</a>
                    <a href="#" class="predefined-columns"><img src="'.get_stylesheet_directory_uri().'/images/options/columns_layout.png" alt=""></a>
                    <ul class="add-column-settings">
                        <li>
                         <a href="#" data-add-columns="#dragable-column-tpl"><img src="'.get_stylesheet_directory_uri().'/images/options/columns_layout_column.png" alt=""></a>
                     </li>
                     <li>
                         <a href="#" data-add-columns="#dragable-column-tpl-half"><img src="'.get_stylesheet_directory_uri().'/images/options/columns_layout_halfs.png" alt=""></a>
                     </li>
                     <li>
                         <a href="#" data-add-columns="#dragable-column-tpl-thirds"><img src="'.get_stylesheet_directory_uri().'/images/options/columns_layout_thirds.png" alt=""></a>
                     </li>
                     <li>
                         <a href="#" data-add-columns="#dragable-column-tpl-four-halfs"><img src="'.get_stylesheet_directory_uri().'/images/options/columns_layout_one_four.png" alt=""></a>
                     </li>
                     <li>
                         <a href="#" data-add-columns="#dragable-column-tpl-one_three"><img src="'.get_stylesheet_directory_uri().'/images/options/columns_layout_one_three.png" alt=""></a>
                     </li>
                     <li>
                         <a href="#" data-add-columns="#dragable-column-tpl-four-half-four"><img src="'.get_stylesheet_directory_uri().'/images/options/columns_layout_four_half_four.png" alt=""></a>
                     </li>
                 </ul>
             </li>
             <li><a href="#" class="remove-row">' . esc_html__( 'delete', 'videofly' ) . '</a></li>
             <li><a href="#" class="move">' . esc_html__( 'move', 'videofly' ) . '</a></li>
         </ul>
     </li>
     <li class="edit-row-settings" >
        <a href="#" class="edit-row" id="{{row-id}}">'.esc_html__('Edit', 'videofly').'</a>
    </li>
</ul>
</script>';

echo '<script id="dragable-element-tpl" type="text/x-handlebars-template">
<li data-element-type="empty">
    <i class="element-icon icon-empty"></i>
    <span class="element-name">'.esc_html__('Empty', 'videofly').'</span>
    <span class="edit icon-edit" data-tooltip="'.esc_html__('Edit this element', 'videofly').'">'.esc_html__('Edit', 'videofly').'</span>
    <span class="delete icon-delete" data-tooltip="'.esc_html__('Remove this element', 'videofly').'"></span>
    <span class="clone icon-beaker" data-tooltip="'.esc_html__('Clone this element', 'videofly').'"></span>
</li>
</script>';
    // Template for adding a 12 column
echo '<script id="dragable-column-tpl" type="text/x-handlebars-template">
<li data-columns="12" data-type="column" class="columns12">
    <div class="column-header">
        <span class="minus icon-left" data-tooltip="'.esc_html__('Reduce column size', 'videofly').'"></span>
        <span class="column-size" data-tooltip="'.esc_html__('The size of the column within container', 'videofly').'">12/12</span>
        <span class="plus icon-right" data-tooltip="' . esc_html__('Add column size', 'videofly') . '"></span>
        <span class="delete-column icon-delete" data-tooltip="'.esc_html__('Remove this column', 'videofly').'"></span>
        <span class="drag-column icon-drag" data-tooltip="'.esc_html__('Drag this column', 'videofly').'"></span>
        <span class="edit-column icon-edit" data-tooltip="'.esc_html__('Edit this column', 'videofly').'"></span>
        <span class="clone icon-beaker ts-clone-column" data-tooltip="'.esc_html__('Clone this column', 'videofly').'"></span>
    </div>

    <ul class="elements">
    </ul>
    <span class="add-element">'.esc_html__('Add element', 'videofly').'</span>
</li>
</script>';
    // Template for splitting the content in 2 halfs
echo '<script id="dragable-column-tpl-half" type="text/x-handlebars-template">
<li data-columns="6" data-type="column" class="columns6">
    <div class="column-header">
        <span class="minus icon-left" data-tooltip="'.esc_html__('Reduce column size', 'videofly').'"></span>
        <span class="column-size" data-tooltip="'.esc_html__('The size of the column within container', 'videofly').'">1/2</span>
        <span class="plus icon-right" data-tooltip="' . esc_html__('Add column size', 'videofly') . '"></span>
        <span class="delete-column icon-delete" data-tooltip="'.esc_html__('Remove this column', 'videofly').'"></span>
        <span class="drag-column icon-drag" data-tooltip="'.esc_html__('Drag this column', 'videofly').'"></span>
        <span class="edit-column icon-edit" data-tooltip="'.esc_html__('Edit this column', 'videofly').'"></span>
        <span class="clone icon-beaker ts-clone-column" data-tooltip="'.esc_html__('Clone this column', 'videofly').'"></span>
    </div>

    <ul class="elements">
    </ul>
    <span class="add-element">'.esc_html__('Add element', 'videofly').'</span>
</li>
<li data-columns="6" data-type="column" class="columns6">
    <div class="column-header">
        <span class="minus icon-left" data-tooltip="'.esc_html__('Reduce column size', 'videofly').'"></span>
        <span class="column-size" data-tooltip="'.esc_html__('The size of the column within container', 'videofly').'">1/2</span>
        <span class="plus icon-right" data-tooltip="' . esc_html__('Add column size', 'videofly') . '"></span>
        <span class="delete-column icon-delete" data-tooltip="'.esc_html__('Remove this column', 'videofly').'"></span>
        <span class="drag-column icon-drag" data-tooltip="'.esc_html__('Drag this column', 'videofly').'"></span>
        <span class="edit-column icon-edit" data-tooltip="'.esc_html__('Edit this column', 'videofly').'"></span>
        <span class="clone icon-beaker ts-clone-column" data-tooltip="'.esc_html__('Clone this column', 'videofly').'"></span>
    </div>

    <ul class="elements">
    </ul>
    <span class="add-element">'.esc_html__('Add element', 'videofly').'</span>
</li>
</script>';
    // Template for splitting the content in 3 halfs
echo '<script id="dragable-column-tpl-thirds" type="text/x-handlebars-template">
<li data-columns="4" data-type="column" class="columns4">
    <div class="column-header">
        <span class="minus icon-left" data-tooltip="'.esc_html__('Reduce column size', 'videofly').'"></span>
        <span class="column-size" data-tooltip="'.esc_html__('The size of the column within container', 'videofly').'">1/4</span>
        <span class="plus icon-right" data-tooltip="' . esc_html__('Add column size', 'videofly') . '"></span>
        <span class="delete-column icon-delete" data-tooltip="'.esc_html__('Remove this column', 'videofly').'"></span>
        <span class="drag-column icon-drag" data-tooltip="'.esc_html__('Drag this column', 'videofly').'"></span>
        <span class="edit-column icon-edit" data-tooltip="'.esc_html__('Edit this column', 'videofly').'"></span>
        <span class="clone icon-beaker ts-clone-column" data-tooltip="'.esc_html__('Clone this column', 'videofly').'"></span>
    </div>

    <ul class="elements">
    </ul>
    <span class="add-element">'.esc_html__('Add element', 'videofly').'</span>
</li>
<li data-columns="4" data-type="column" class="columns4">
    <div class="column-header">
        <span class="minus icon-left" data-tooltip="'.esc_html__('Reduce column size', 'videofly').'"></span>
        <span class="column-size" data-tooltip="'.esc_html__('The size of the column within container', 'videofly').'">1/4</span>
        <span class="plus icon-right" data-tooltip="' . esc_html__('Add column size', 'videofly') . '"></span>
        <span class="delete-column icon-delete" data-tooltip="'.esc_html__('Remove this column', 'videofly').'"></span>
        <span class="drag-column icon-drag" data-tooltip="'.esc_html__('Drag this column', 'videofly').'"></span>
        <span class="edit-column icon-edit" data-tooltip="'.esc_html__('Edit this column', 'videofly').'"></span>
        <span class="clone icon-beaker ts-clone-column" data-tooltip="'.esc_html__('Clone this column', 'videofly').'"></span>
    </div>

    <ul class="elements">
    </ul>
    <span class="add-element">'.esc_html__('Add element', 'videofly').'</span>
</li>
<li data-columns="4" data-type="column" class="columns4">
    <div class="column-header">
        <span class="minus icon-left" data-tooltip="'.esc_html__('Reduce column size', 'videofly').'"></span>
        <span class="column-size" data-tooltip="'.esc_html__('The size of the column within container', 'videofly').'">1/4</span>
        <span class="plus icon-right" data-tooltip="' . esc_html__('Add column size', 'videofly') . '"></span>
        <span class="delete-column icon-delete" data-tooltip="'.esc_html__('Remove this column', 'videofly').'"></span>
        <span class="drag-column icon-drag" data-tooltip="'.esc_html__('Drag this column', 'videofly').'"></span>
        <span class="edit-column icon-edit" data-tooltip="'.esc_html__('Edit this column', 'videofly').'"></span>
        <span class="clone icon-beaker ts-clone-column" data-tooltip="'.esc_html__('Clone this column', 'videofly').'"></span>
    </div>

    <ul class="elements">
    </ul>
    <span class="add-element">'.esc_html__('Add element', 'videofly').'</span>
</li>
</script>';
    // Template for splitting the content in one to three
echo '<script id="dragable-column-tpl-one_three" type="text/x-handlebars-template">
<li data-columns="4" data-type="column" class="columns4">
    <div class="column-header">
        <span class="minus icon-left" data-tooltip="'.esc_html__('Reduce column size', 'videofly').'"></span>
        <span class="column-size" data-tooltip="'.esc_html__('The size of the column within container', 'videofly').'">1/3</span>
        <span class="plus icon-right" data-tooltip="' . esc_html__('Add column size', 'videofly') . '"></span>
        <span class="delete-column icon-delete" data-tooltip="'.esc_html__('Remove this column', 'videofly').'"></span>
        <span class="drag-column icon-drag" data-tooltip="'.esc_html__('Drag this column', 'videofly').'"></span>
        <span class="edit-column icon-edit" data-tooltip="'.esc_html__('Edit this column', 'videofly').'"></span>
        <span class="clone icon-beaker ts-clone-column" data-tooltip="'.esc_html__('Clone this column', 'videofly').'"></span>
    </div>

    <ul class="elements">
    </ul>
    <span class="add-element">'.esc_html__('Add element', 'videofly').'</span>
</li>
<li data-columns="8" data-type="column" class="columns8">
    <div class="column-header">
        <span class="minus icon-left" data-tooltip="'.esc_html__('Reduce column size', 'videofly').'"></span>
        <span class="column-size" data-tooltip="'.esc_html__('The size of the column within container', 'videofly').'">2/3</span>
        <span class="plus icon-right" data-tooltip="' . esc_html__('Add column size', 'videofly') . '"></span>
        <span class="delete-column icon-delete" data-tooltip="'.esc_html__('Remove this column', 'videofly').'"></span>
        <span class="drag-column icon-drag" data-tooltip="'.esc_html__('Drag this column', 'videofly').'"></span>
        <span class="edit-column icon-edit" data-tooltip="'.esc_html__('Edit this column', 'videofly').'"></span>
        <span class="clone icon-beaker ts-clone-column" data-tooltip="'.esc_html__('Clone this column', 'videofly').'"></span>
    </div>

    <ul class="elements">
    </ul>
    <span class="add-element">'.esc_html__('Add element', 'videofly').'</span>
</li>
</script>';
    // Template for splitting the content in one fourth to one half and one fourth
echo '<script id="dragable-column-tpl-four-half-four" type="text/x-handlebars-template">
<li data-columns="3" data-type="column" class="columns3">
    <div class="column-header">
        <span class="minus icon-left" data-tooltip="'.esc_html__('Reduce column size', 'videofly').'"></span>
        <span class="column-size" data-tooltip="'.esc_html__('The size of the column within container', 'videofly').'">1/4</span>
        <span class="plus icon-right" data-tooltip="' . esc_html__('Add column size', 'videofly') . '"></span>
        <span class="delete-column icon-delete" data-tooltip="'.esc_html__('Remove this column', 'videofly').'"></span>
        <span class="drag-column icon-drag" data-tooltip="'.esc_html__('Drag this column', 'videofly').'"></span>
        <span class="edit-column icon-edit" data-tooltip="'.esc_html__('Edit this column', 'videofly').'"></span>
        <span class="clone icon-beaker ts-clone-column" data-tooltip="'.esc_html__('Clone this column', 'videofly').'"></span>
    </div>

    <ul class="elements">
    </ul>
    <span class="add-element">'.esc_html__('Add element', 'videofly').'</span>
</li>
<li data-columns="6" data-type="column" class="columns6">
    <div class="column-header">
        <span class="minus icon-left" data-tooltip="'.esc_html__('Reduce column size', 'videofly').'"></span>
        <span class="column-size" data-tooltip="'.esc_html__('The size of the column within container', 'videofly').'">1/2</span>
        <span class="plus icon-right" data-tooltip="' . esc_html__('Add column size', 'videofly') . '"></span>
        <span class="delete-column icon-delete" data-tooltip="'.esc_html__('Remove this column', 'videofly').'"></span>
        <span class="drag-column icon-drag" data-tooltip="'.esc_html__('Drag this column', 'videofly').'"></span>
        <span class="edit-column icon-edit" data-tooltip="'.esc_html__('Edit this column', 'videofly').'"></span>
        <span class="clone icon-beaker ts-clone-column" data-tooltip="'.esc_html__('Clone this column', 'videofly').'"></span>
    </div>

    <ul class="elements">
    </ul>
    <span class="add-element">'.esc_html__('Add element', 'videofly').'</span>
</li>
<li data-columns="3" data-type="column" class="columns3">
    <div class="column-header">
        <span class="minus icon-left" data-tooltip="'.esc_html__('Reduce column size', 'videofly').'"></span>
        <span class="column-size" data-tooltip="'.esc_html__('The size of the column within container', 'videofly').'">1/4</span>
        <span class="plus icon-right" data-tooltip="' . esc_html__('Add column size', 'videofly') . '"></span>
        <span class="delete-column icon-delete" data-tooltip="'.esc_html__('Remove this column', 'videofly').'"></span>
        <span class="drag-column icon-drag" data-tooltip="'.esc_html__('Drag this column', 'videofly').'"></span>
        <span class="edit-column icon-edit" data-tooltip="'.esc_html__('Edit this column', 'videofly').'"></span>
        <span class="clone icon-beaker ts-clone-column" data-tooltip="'.esc_html__('Clone this column', 'videofly').'"></span>
    </div>

    <ul class="elements">
    </ul>
    <span class="add-element">'.esc_html__('Add element', 'videofly').'</span>
</li>
</script>';
    // Template for splitting the content in four columns
echo '<script id="dragable-column-tpl-four-halfs" type="text/x-handlebars-template">
<li data-columns="3" data-type="column" class="columns3">
    <div class="column-header">
        <span class="minus icon-left" data-tooltip="'.esc_html__('Reduce column size', 'videofly').'"></span>
        <span class="column-size" data-tooltip="'.esc_html__('The size of the column within container', 'videofly').'">1/3</span>
        <span class="plus icon-right" data-tooltip="' . esc_html__('Add column size', 'videofly') . '"></span>
        <span class="delete-column icon-delete" data-tooltip="'.esc_html__('Remove this column', 'videofly').'"></span>
        <span class="drag-column icon-drag" data-tooltip="'.esc_html__('Drag this column', 'videofly').'"></span>
        <span class="edit-column icon-edit" data-tooltip="'.esc_html__('Edit this column', 'videofly').'"></span>
        <span class="clone icon-beaker ts-clone-column" data-tooltip="'.esc_html__('Clone this column', 'videofly').'"></span>
    </div>

    <ul class="elements">
    </ul>
    <span class="add-element">'.esc_html__('Add element', 'videofly').'</span>
</li>
<li data-columns="3" data-type="column" class="columns3">
    <div class="column-header">
        <span class="minus icon-left" data-tooltip="'.esc_html__('Reduce column size', 'videofly').'"></span>
        <span class="column-size" data-tooltip="'.esc_html__('The size of the column within container', 'videofly').'">1/3</span>
        <span class="plus icon-right" data-tooltip="' . esc_html__('Add column size', 'videofly') . '"></span>
        <span class="delete-column icon-delete" data-tooltip="'.esc_html__('Remove this column', 'videofly').'"></span>
        <span class="drag-column icon-drag" data-tooltip="'.esc_html__('Drag this column', 'videofly').'"></span>
        <span class="edit-column icon-edit" data-tooltip="'.esc_html__('Edit this column', 'videofly').'"></span>
        <span class="clone icon-beaker ts-clone-column" data-tooltip="'.esc_html__('Clone this column', 'videofly').'"></span>
    </div>

    <ul class="elements">
    </ul>
    <span class="add-element">'.esc_html__('Add element', 'videofly').'</span>
</li>
<li data-columns="3" data-type="column" class="columns3">
    <div class="column-header">
        <span class="minus icon-left" data-tooltip="'.esc_html__('Reduce column size', 'videofly').'"></span>
        <span class="column-size" data-tooltip="'.esc_html__('The size of the column within container', 'videofly').'">1/3</span>
        <span class="plus icon-right" data-tooltip="' . esc_html__('Add column size', 'videofly') . '"></span>
        <span class="delete-column icon-delete" data-tooltip="'.esc_html__('Remove this column', 'videofly').'"></span>
        <span class="drag-column icon-drag" data-tooltip="'.esc_html__('Drag this column', 'videofly').'"></span>
        <span class="edit-column icon-edit" data-tooltip="'.esc_html__('Edit this column', 'videofly').'"></span>
        <span class="clone icon-beaker ts-clone-column" data-tooltip="'.esc_html__('Clone this column', 'videofly').'"></span>
    </div>

    <ul class="elements">
    </ul>
    <span class="add-element">'.esc_html__('Add element', 'videofly').'</span>
</li>
<li data-columns="3" data-type="column" class="columns3">
    <div class="column-header">
        <span class="minus icon-left" data-tooltip="'.esc_html__('Reduce column size', 'videofly').'"></span>
        <span class="column-size" data-tooltip="'.esc_html__('The size of the column within container', 'videofly').'">1/3</span>
        <span class="plus icon-right" data-tooltip="' . esc_html__('Add column size', 'videofly') . '"></span>
        <span class="delete-column icon-delete" data-tooltip="'.esc_html__('Remove this column', 'videofly').'"></span>
        <span class="drag-column icon-drag" data-tooltip="'.esc_html__('Drag this column', 'videofly').'"></span>
        <span class="edit-column icon-edit" data-tooltip="'.esc_html__('Edit this column', 'videofly').'"></span>
        <span class="clone icon-beaker ts-clone-column" data-tooltip="'.esc_html__('Clone this column', 'videofly').'"></span>
    </div>

    <ul class="elements">
    </ul>
    <span class="add-element">'.esc_html__('Add element', 'videofly').'</span>
</li>
</script>';


echo '<div class="builder-section-container">
<!-- Edit Content Strucutre -->
<div style="clear: both"></div>
<a href="#" data-location="content" class="app red-ui-button add-top-row">' . esc_html__( 'Add row to the top', 'videofly' ) . '</a><br/><br/>
<div class="layout_builder" id="section-content">';

    echo vdf_var_sanitize($elements);

    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

    $is_plugin_active = is_plugin_active('ts-custom-posts/ts-custom-posts.php');

    if( is_plugin_active( 'woocommerce/woocommerce.php' ) ){
        $list_products_ul = '<li data-ts-tab-element="ts-post-tab" class="icon-basket" data-value="list-products"><span>' . esc_html__('List products', 'videofly') . '</span></li>';
        $list_products_select = '<option value="list-products">' . esc_html__( 'List products','videofly' ) . '</option>';
        $cart_li = '<li data-ts-tab-element="ts-content-tab" class="icon-basket" data-value="cart"><span>' . esc_html__('Shopping cart', 'videofly') . '</span></li>';
        $cart_select = '<option value="cart">' . esc_html__( 'Shopping cart','videofly' ) . '</option>';
    }else{
        $list_products_ul = '';
        $list_products_select = '';
        $cart_li = '';
        $cart_select = '';
    }

    echo '
</div>
<div style="clear: both"></div>
<br>
<a href="#" data-location="content" class="app red-ui-button add-bottom-row">'. esc_html__( 'Add row to the bottom', 'videofly' ) . '</a>
<a href="#" data-location="content" class="app red-ui-button publish-changes" style="float: right;font">'. esc_html__( 'Save Layout Settings', 'videofly' ) . '</a>
<div style="clear: both"></div>
</div>
<div class="ts-is-hidden ts-all-elements">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title" id="ts-builder-elements-modal-label" data-elements-title="Builder elements" data-element-title="Builder element">Builder elements</h4>
    </div>
    <div class="builder">
        <table cellpadding="10">
            <tr>
                <td>
                    <label for="ts-element-type">'. esc_html__('Select the element type', 'videofly') .'</label>
                </td>
            </tr>
            <tr>
                <td>

                    <ul class="ts-tab-elements">
                        <li data-ts-tab="ts-all-tab">
                            '. esc_html__('All', 'videofly') .'
                        </li>
                        <li data-ts-tab="ts-post-tab">
                            '. esc_html__('Post listing', 'videofly') .'
                        </li>
                        <li data-ts-tab="ts-content-tab">
                            '. esc_html__('Content elements', 'videofly') .'
                        </li>
                        <li data-ts-tab="ts-media-tab">
                            '. esc_html__('Media elements', 'videofly') .'
                        </li>
                    </ul>
                    <div class="builder-element-array">
                        <ul>
                            <li data-ts-tab-element="ts-content-tab" class="icon-logo" data-value="logo">
                                <span>'. esc_html__( 'Logo', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-social" data-value="social-buttons">
                                <span>'. esc_html__( 'Social buttons', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-search" data-value="searchbox">
                                <span>'. esc_html__( 'Search box', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-menu" data-value="menu">
                                <span>'. esc_html__( 'Menu', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-login" data-value="user">
                                <span>'. esc_html__( 'User login', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-sidebar" data-value="sidebar">
                                <span>'. esc_html__( 'Sidebar', 'videofly' ) .'</span>
                            </li>

                            <!-- Post listing -->
                            <li data-ts-tab-element="ts-post-tab" class="icon-window" data-value="latest-custom-posts">
                                <span>'. esc_html__( 'Latest custom posts', 'videofly' ) .' </span>
                            </li>
                            <li data-ts-tab-element="ts-post-tab" class="icon-filter" data-value="filters">
                                <span>'. esc_html__( 'Filters', 'videofly' ) .' </span>
                            </li>
                            <li data-ts-tab-element="ts-post-tab" class="icon-clipboard" data-value="accordion">
                                <span>'. esc_html__( 'Article accordion', 'videofly' ) .' </span>
                            </li>
                            <!-- end Post listing -->'.
                            ( $is_plugin_active ? '
                                <li data-ts-tab-element="ts-post-tab" class="icon-view-mode" data-value="last-posts">
                                    <span>'. esc_html__( 'List posts', 'videofly' ) .'</span>
                                </li>
                                <li data-ts-tab-element="ts-post-tab" class="icon-movie" data-value="list-videos">
                                    <span>'. esc_html__( 'List Videos', 'videofly' ) .'</span>
                                </li>
                                <li data-ts-tab-element="ts-post-tab" class="icon-gallery" data-value="list-galleries">
                                    <span>'. esc_html__( 'List galleries', 'videofly' ) .'</span>
                                </li>
                                <li data-ts-tab-element="ts-post-tab" class="icon-briefcase" data-value="list-portfolios">
                                    <span>'. esc_html__( 'List portfolios', 'videofly' ) .'</span>
                                </li>
                                <li data-ts-tab-element="ts-post-tab" class="icon-text" data-value="events">
                                    <span>'. esc_html__( 'List Events', 'videofly' ) .'</span>
                                </li>
                                <li data-ts-tab-element="ts-content-tab" class="icon-desktop" data-value="slider">
                                    <span>'. esc_html__( 'Slider', 'videofly' ) .'</span>
                                </li>
                                <li data-ts-tab-element="ts-post-tab" class="icon-dollar" data-value="pricing-tables">
                                    <span>'. esc_html__( 'Pricing tables', 'videofly' ) .'</span>
                                </li>
                                <li data-ts-tab-element="ts-content-tab" class="icon-team" data-value="teams">
                                    <span>'. esc_html__( 'Teams', 'videofly' ) .'</span>
                                </li>' : ''
                                ) .'

                            <li data-ts-tab-element="ts-media-tab" class="icon-gallery" data-value="gallery">
                                <span>'. esc_html__( 'Gallery', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-comments" data-value="testimonials">
                                <span>'. esc_html__( 'Testimonials', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-direction" data-value="callaction">
                                <span>'. esc_html__( 'Call to action', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-money" data-value="advertising">
                                <span>'. esc_html__( 'Advertising', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-all-tab" class="icon-empty" data-value="empty">
                                <span>'. esc_html__( 'Empty', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-delimiter" data-value="delimiter">
                                <span>'. esc_html__( 'Delimiter', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-font" data-value="title">
                                <span>'. esc_html__( 'Title', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-media-tab" class="icon-video" data-value="video">
                                <span>'. esc_html__( 'Video', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-media-tab" class="icon-image" data-value="image">
                                <span>'. esc_html__( 'Image', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-resize-vertical" data-value="spacer">
                                <span>'. esc_html__( 'Spacer', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-button" data-value="buttons">
                                <span>'. esc_html__( 'Button', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-mail" data-value="contact-form">
                                <span>'. esc_html__( 'Contact form', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-featured-area" data-value="featured-area">
                                <span>'. esc_html__( 'Featured area', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-code" data-value="shortcodes">
                                <span>'. esc_html__( 'Shortcodes', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-text" data-value="text" id="icon-text">
                                <span>'. esc_html__( 'Text', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-media-tab" class="icon-coverflow" data-value="image-carousel">
                                <span>'. esc_html__( 'Image carousel', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-flag" data-value="icon">
                                <span>'. esc_html__( 'Icon', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-list" data-value="listed-features">
                                <span>'. esc_html__( 'Listed features', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-tick" data-value="features-block">
                                <span>'. esc_html__( 'Icon box', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-time" data-value="counters"><span>'. esc_html__( 'Counter', 'videofly' ) .' </span></li>
                            '. $list_products_ul .'
                            <li data-ts-tab-element="ts-content-tab" class="icon-clients" data-value="clients">
                                <span>'. esc_html__( 'Clients', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-facebook" data-value="facebook-block">
                                <span>'. esc_html__( 'Facebook Like Box', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-pin" data-value="map">
                                <span>'. esc_html__( 'Map', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-link-ext" data-value="banner">
                                <span>'. esc_html__( 'Banner', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-resize-full" data-value="toggle">
                                <span>'. esc_html__( 'Toggle', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-tabs" data-value="tab">
                                <span>'. esc_html__( 'Tabs', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-code" data-value="breadcrumbs">
                                <span>'. esc_html__( 'Breadcrumbs', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-ribbon" data-value="ribbon">
                                <span>'. esc_html__( 'Ribbon banner', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-parallel" data-value="timeline">
                                <span>'. esc_html__( 'Timeline features', 'videofly' ) .'</span>
                            </li>
                            '. $cart_li .'
                            <li data-ts-tab-element="ts-media-tab" class="icon-coverflow" data-value="video-carousel">
                                <span>'. esc_html__( 'Post slide carousel', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-megaphone" data-value="count-down">
                                <span>'. esc_html__( 'Counter down', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-ticket" data-value="powerlink">
                                <span>'. esc_html__( 'Powerlink', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-calendar" data-value="calendar">
                                <span>'. esc_html__( 'Calendar', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-attention" data-value="alert">
                                <span>'. esc_html__( 'Alert', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-pencil-alt" data-value="skills">
                                <span>'. esc_html__( 'Skills', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-chart" data-value="chart">
                                <span>'. esc_html__( 'Chart', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-post-tab" class="icon-featured-article" data-value="featured-article">
                                <span>'. esc_html__( 'Featured article', 'videofly' ) .'</span>
                            </li>
                             <li data-ts-tab-element="ts-post-tab" class="icon-empty" data-value="boca">
                                <span>'. esc_html__( 'Boca slider posts', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-post-tab" class="icon-empty" data-value="nona">
                                <span>'. esc_html__( 'Nona slider posts', 'videofly' ) .'</span>
                            </li>
                            <li data-ts-tab-element="ts-content-tab" class="icon-analysis" data-value="instance">
                                <span>'. esc_html__( 'Instance', 'videofly' ) .'</span>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>';
}

/**
 * Extrat information from page options
 */

//=============== Style options  ===========================
//=============== General Tab ==============================

if ( ! function_exists('vdf_preloader')) {

    function vdf_preloader()
    {
        $option = get_option('videofly_general', array('enable_preloader' => 'N'));

        if ( $option['enable_preloader'] === 'Y' || $option['enable_preloader'] === 'FP' && is_front_page() ) {
            return true;
        } else {
            return false;
        }

    }
}


if ( ! function_exists('vdf_display_featured_image')) {

    function vdf_display_featured_image()
    {
        global $post;
        $option = get_option('videofly_general', array('featured_image_in_post' => 'Y'));

        if ( !is_page() && $option['featured_image_in_post'] === 'Y' && !fields::logic($post->ID, 'post_settings', 'hide_featimg') || is_page() && !fields::logic($post->ID, 'page_settings', 'hide_featimg')) {
            return true;
        } else {
            return false;
        }
    }
}

if ( ! function_exists('vdf_lightbox_enabled')) {

    function vdf_lightbox_enabled()
    {
        $option = get_option('videofly_general', array('enable_lightbox' => 'Y'));

        if ($option['enable_lightbox'] === 'Y') {
            return true;
        } else {
            return false;
        }
    }
}

if ( ! function_exists('vdf_comment_system')) {

    function vdf_comment_system()
    {
        $option = get_option('videofly_general', array('comment_system' => 'default'));

        if ( in_array($option['comment_system'], array('default', 'facebook')) ) {
            return $option['comment_system'];
        } else {
            return 'default';
        }
    }
}

if ( ! function_exists('vdf_facebook_app_ID')) {

    function vdf_facebook_app_ID()
    {
        $option = get_option('videofly_general', array('facebook_id' => ''));

        return $option['facebook_id'];
    }
}

// Enable or disable WP Admin Bar
$option = get_option('videofly_general', array('show_wp_admin_bar' => 'Y'));

if ($option['show_wp_admin_bar'] === 'N') {
    add_filter('show_admin_bar', '__return_false', 10);
}

if ( ! function_exists('vdf_enable_sticky_menu')) {

    function vdf_enable_sticky_menu()
    {
        $option = get_option('videofly_general', array('enable_sticky_menu' => 'Y'));

        if ( $option['enable_sticky_menu'] === "Y" ) {
            return true;
        } else {
            return false;
        }
    }
}

if ( ! function_exists('vdf_get_sticky_menu_style')) {

    function vdf_get_sticky_menu_style()
    {
        $option = get_option('videofly_general', array('sticky_menu_bg_color' => '', 'sticky_menu_text_color' => ''));
        $description = (isset($option['show_description']) && ($option['show_description'] == 'y' || $option['show_description'] == 'n')) ? $option['show_description'] : 'y';
        $icons = (isset($option['show_icons']) && ($option['show_icons'] == 'y' || $option['show_icons'] == 'n')) ? $option['show_icons'] : 'y';
        $style_description = ($description == 'n') ? '.ts-sticky-menu .mega-menu-item-description{display: none;}': '';
        $style_icons = ($icons == 'n') ? '.ts-sticky-menu ul li a i{display: none;}' : '';

        if ( $option['sticky_menu_text_color'] !== "#FFFFFF" || $option['sticky_menu_bg_color'] !== "#444444" ) {
            $content = '
            .ts-sticky-menu{
                background-color: ' . $option['sticky_menu_bg_color'] . ';
            }
            .ts-sticky-menu .main-menu li ul{
                background-color: ' . $option['sticky_menu_bg_color'] . ';
            }
            .ts-sticky-menu .sub-menu:not(.ts_is_mega){
                background: '.$option['sticky_menu_bg_color'].';
            }
            .ts-sticky-menu .main-menu > .menu-item-has-children ul li > a:before,
            .ts-sticky-menu .main-menu > .menu-item-has-children ul li > a:after{
                background: rgba(60,60,60,0.07);
            }
            .ts-sticky-menu .container .main-menu li a, .ts-sticky-menu .container .main-menu li, .ts-sticky-menu .main-menu{
                color:'. $option['sticky_menu_text_color'] . ';' .
                '}
                .ts-sticky-menu .container .main-menu li.current-menu-item > a{
                    color: '. vdf_get_color('primary_color') .';
                }';
                return $content . $style_description . $style_icons;
            } else {
                return '';
            }
        }
    }

    if ( ! function_exists('ts_custom_javascript_code')) {

        function ts_custom_javascript_code()
        {
            $option = get_option('videofly_general', array('custom_javascript' => ''));

            return $option['custom_javascript'];
        }
    }

//=============== Styles Tab ==============================

    if ( ! function_exists('vdf_get_color')) {

        function vdf_get_color($val)
        {
            $option = get_option('videofly_colors', array($val => '#EB593C'));

            return $option[$val];
        }
    }

    if ( ! function_exists('vdf_custom_background')) {

        function vdf_custom_background()
        {
            $bg = get_option('videofly_styles');

            if ($bg) {
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

                if ($css !== '') {
                    return "body {\n" . $css . "\n}";
                } else {
                    return '';
                }
            } else {
                return '';
            }
        }
    }

    if ( ! function_exists('vdf_custom_favicon')) {

        function vdf_custom_favicon()
        {
            $option = get_option('videofly_styles', array('favicon' => ''));

            if ( $option['favicon'] == '' ) {
                return '<link rel="shortcut icon" href="'. get_template_directory_uri() . '/favicon.png" />';
            } else {
                return '<link rel="shortcut icon" href="'.esc_url($option['favicon']).'" />';
            }
        }
    }

    if ( ! function_exists('vdf_overlay_effect_is_enabled')) {

        function vdf_overlay_effect_is_enabled()
        {
            $option = get_option('videofly_styles', array('overlay_effect_for_images' => 'N'));

            if ($option['overlay_effect_for_images'] === 'Y') {
                return true;
            } else {
                return false;
            }
        }
    }

    if ( ! function_exists('vdf_overlay_effect_type')) {

        function vdf_overlay_effect_type()
        {
            $option = get_option('videofly_styles', array('overlay_effect_type' => 'dots'));

            if ($option['overlay_effect_type'] === 'dots') {
                return 'dotted';
            } else {
                return 'stripes';
            }
        }
    }


    if ( ! function_exists('vdf_get_logo')) {

        function vdf_get_logo()
        {
            $option = get_option('videofly_styles', array('logo' => array(
                'type' => 'image',
                'retina_logo' =>'N',
                'retina_width' =>'0'
                )
            )
            );

            $retina_style = '';

            if ( isset($option['logo']['type']) && $option['logo']['type'] === 'image' ){

                if ( trim($option['logo']['image_url']) != '' ) {

                    if ( $option['logo']['retina_logo'] === 'Y' ) {

                        $option['logo']['retina_width'] = (int)$option['logo']['retina_width'];

                        if( $option['logo']['retina_width'] > 0 ) {
                            $option['logo']['retina_width'] = ceil($option['logo']['retina_width'] / 2);
                            $retina_style = 'style="width: ' . $option['logo']['retina_width'] . 'px;"';
                        } else {
                            $retina_style = '';
                        }
                    }

                    return '<img src="'.esc_url($option['logo']['image_url']).'" alt="'.get_bloginfo('name').'" ' . $retina_style . '/>';
                } else {

                    if ($option['logo']['retina_logo'] === 'Y' ) {
                        $retina_style = 'style="width: ' . $option['logo']['retina_width'] / 2 . 'px; height: auto;"';
                    } else {
                        $retina_style = '';
                    }

                    return '<img src="' . get_template_directory_uri() . '/images/logo.png" alt="' . get_bloginfo('name') . '" ' . $retina_style . '/>';
                }
            } else if( isset($option['logo']['logo_type']) && $option['logo']['logo_type'] === 'google'){
                $textLogo = (isset($option['logo']['logo_text']) && !empty($option['logo']['logo_text'])) ? esc_attr($option['logo']['logo_text']) : get_bloginfo('name');
                return $textLogo;
            } else {
                return get_bloginfo('name');
            }
        }
    }

    if ( ! function_exists('vdf_get_custom_fonts_css')) {
        add_filter( 'wp_image_editors', 'vdf_change_graphic_lib' );

        function vdf_change_graphic_lib($array) {
          return array( 'WP_Image_Editor_GD', 'WP_Image_Editor_Imagick' );
      }

      function vdf_get_custom_fonts_css()
      {

        $options = get_option('videofly_typography');
        $optionsStyleLogo = get_option('videofly_styles');
        $optionsStyleLogo = (isset($optionsStyleLogo['logo'])) ? $optionsStyleLogo['logo'] : '';
        $options['logo'] = $optionsStyleLogo;
        $returnStyle = array('style' => '', 'links' => '');
        $arrayFonts = array();
        $protocol = is_ssl() ? 'https' : 'http';

        if( isset($options) && !empty($options) && is_array($options) ){
            foreach($options as $elementName => $option){

                $elementName = ($elementName == 'menu') ? '.ts-behold-menu, .ts-behold-menu .main-menu > .menu-item-has-children > a, .main-menu li' : $elementName;
                $elementName = ($elementName == 'logo') ? '.logo' : $elementName;

                $fontSize = (isset($option['font_size']) && is_numeric($option['font_size'])) ? 'font-size: ' . $option['font_size'] . 'px;' : '';
                $fontWeight = (isset($option['font_weight']) && ($option['font_weight'] == '400' || $option['font_weight'] == '700')) ? 'font-weight: ' . $option['font_weight'] . ';' : '';
                $fontStyle = (isset($option['font_style']) && ($option['font_style'] == 'normal' || $option['font_style'] == 'italic')) ? 'font-style: ' . $option['font_style'] . ';' : '';
                $fontName = (isset($option['font_name']) && !empty($option['font_name'])) ? $option['font_name'] : '';

                if( isset($option['type']) && $option['type'] == 'google' ){
                    if( isset($option['font_name']) && $option['font_name'] !== '0' ){
                        $font_family_name = str_replace('+', ' ', $fontName);
                        $returnStyle['style'] .= $elementName . '{' .
                        $fontSize .
                        "font-family: '" . $font_family_name . "';" .
                        $fontWeight .
                        $fontStyle .
                        '}';

                        if( !in_array($fontName, $arrayFonts) ){

                            $subsets = (isset($option['font_subsets']) && !empty($option['font_subsets'])) ? '&amp;subset=' . implode(',', $option['font_subsets']) : '&amp;subset=latin';
                            $fontName = str_replace(' ', '+', $fontName);
                            $returnStyle['links'] .= '<link rel="stylesheet" href="' . $protocol . '://fonts.googleapis.com/css?family='. $fontName . ':400,400italic,700' . $subsets . '" type="text/css" media="all" />' . "\n";

                            $arrayFonts[] = $fontName;
                        }

                    }
                }else if( isset($option['type']) && $option['type'] == 'custom_font' ){

                    $fontFamily = (isset($option['font_family'])) ? "font-family: '" . $option['font_family'] . "';" : '';
                    $fileEot = (isset($option['font_eot'])) ? $option['font_eot'] : '';
                    $fileWoff = (isset($option['font_woff']) && !empty($option['font_woff'])) ? "url('" . $option['font_woff'] . "') format('woff')," : '';
                    $fileTtf = (isset($option['font_ttf']) && !empty($option['font_ttf'])) ? "url('" . $option['font_ttf'] . "') format('truetype')" : '';
                    $fileSvg = (isset($option['font_svg']) && !empty($option['font_svg'])) ? "url('" . $option['font_svg'] . "#" . $option['font_family'] . "') format('svg');" : ';';
                    $fileTtf = ($fileSvg == ';') ? $fileTtf : $fileTtf . ',';

                    if( !in_array($option['font_family'], $arrayFonts) ){
                        $returnStyle['style'] .= "@font-face{
                            " . $fontFamily . "
                            src: url('" . $fileEot . "');
                            src: url('" . $fileEot . "?#iefix') format('embedded-opentype'),
                            " . $fileWoff . "
                            " . $fileTtf . "
                            " . $fileSvg . "
                        }" . $elementName . "{
                            " . $fontFamily . "
                            " . $fontWeight . "
                            " . $fontStyle . "
                            " . $fontSize . "
                        }";
                        $arrayFonts[] = $fontName;
                    }else{
                        $returnStyle['style'] .= $elementName . "{
                         " . $fontFamily . "
                         " . $fontWeight . "
                         " . $fontStyle . "
                         " . $fontSize . "
                     }";
                 }

             }else if( $elementName == 'custom-icon' ){

                if( !empty($option) ){

                    foreach($option as $value){
                       $returnStyle['style'] .=    "@font-face {
                        font-family: '". $value['font-family'] ."';
                        src: url('". wp_get_attachment_url($value['ids']['eot']) ."');
                        src: url('". wp_get_attachment_url($value['ids']['eot']) ."#iefix') format('embedded-opentype'),
                        url('". wp_get_attachment_url($value['ids']['woff']) ."') format('woff'),
                        url('". wp_get_attachment_url($value['ids']['ttf']) ."') format('truetype'),
                        url('". wp_get_attachment_url($value['ids']['svg']) ."#fontello') format('svg');
                        font-weight: ". $value['font-weight'] ."
                        font-style: ". $value['font-style'] ."
                    }". $value['css'];
                }


            }


        }else if( isset($option['type']) && $option['type'] == 'image' ){

        }else{
            $fontName = (isset($option['standard_font'])) ? $option['standard_font'] : 'Open+Sans';

            if( !in_array($fontName, $arrayFonts) ){
                $returnStyle['links'] .= '<link rel="stylesheet" href="' . $protocol . '://fonts.googleapis.com/css?family='. $fontName . ':400,400italic,700&amp;subset=latin" type="text/css" media="all" />' . "\n";
                $arrayFonts[] = $fontName;
            }
            $font_family_name = str_replace('+', ' ', $fontName);
            $returnStyle['style'] .= $elementName . '{' .
            $fontSize .
            "font-family: '" . $font_family_name . "';" .
            $fontWeight .
            $fontStyle .
            '}';
        }
    }
}

return $returnStyle;
}
}

function vdfAddTypographyElement($specificId = '', $arrayValues, $elementName, $options = array()){


    $values = get_option($arrayValues);

    $values = (isset($values[$elementName]) && !empty($values[$elementName])) ? $values[$elementName] : '';

    $defaultOptions = array(
        'type' => array(
            'std'          => esc_html__('Standard fonts', 'videofly'),
            'google'       => esc_html__('Google fonts', 'videofly'),
            'custom_font' => esc_html__('Custom font', 'videofly')
            ),
        'font_weight'  => '',
        'font_size'    => '',
        'font_style'   => '',
        'text'         => '',
        'font_subsets' => ''
        );

    if( empty($options) ) $options = $defaultOptions;

    $type = (isset($values['type'])) ? $values['type'] : '';
    $fontName = (isset($values['font_name'])) ? esc_attr($values['font_name']) : '';
    $fontWeight = (isset($values["font_weight"])) ? $values["font_weight"] : '400';
    $fontStyle = (isset($values["font_style"])) ? $values["font_style"] : 'normal';
    $demoText = (isset($values['text']) && !empty($values['text'])) ? sanitize_text_field($values['text']) : 'videofly';
    $fontFamily = (isset($values['font_family'])) ? $values['font_family'] : '';
    $fontSize = (isset($values['font_size'])) ? $values['font_size'] : '';
    $fontSubsets = (isset($values['font_subsets']) && is_array($values['font_subsets'])) ? $values['font_subsets'] : array('latin');
    $fontDefault = (isset($values['standard_font'])) ? $values['standard_font'] : 'Open+Sans';
    $imageUrl = (isset($values['image_url'])) ? esc_url($values['image_url']) : '';
    $retinaLogo = (isset($values['retina_logo']) && ($values['retina_logo'] == 'Y' || $values['retina_logo'] == 'N')) ? $values['retina_logo'] : 'Y';
    $retinaWidth = (isset($values['retina_width'])) ? esc_attr($values['retina_width']) : '390';

    $subsetFormating = array();
    foreach ($fontSubsets as $subset) {
        $subsetFormating[] = "'". esc_attr($subset)."'";
    }
    $fontSubsets = implode(', ', $subsetFormating);

    ?>
    <?php if( $elementName !== 'logo' ) : ?>
        <a style="display: none;" href="#" class="ts-hide-options button-primary"><?php esc_html_e('Hide options', 'videofly'); ?></a>
        <a href="#" class="ts-show-options button-primary"><?php esc_html_e('Show options', 'videofly'); ?></a>
    <?php endif; ?>
    <div style="<?php if( $elementName !== 'logo' ) echo 'display: none;' ?>" class="ts-typography-toggle" id="ts-typography-<?php echo vdf_var_sanitize($elementName ); ?>">
        <p><?php esc_html_e('Select type font', 'videofly'); ?></p>
        <select name="<?php echo vdf_var_sanitize($arrayValues); ?>[<?php echo vdf_var_sanitize($elementName ); ?>][type]" class="ts-type-font">
            <?php foreach($options['type'] as $optionValue => $textUser) : ?>
                <option <?php selected($type, $optionValue, true); ?> value="<?php echo vdf_var_sanitize($optionValue); ?>"><?php echo vdf_var_sanitize($textUser); ?></option>
            <?php endforeach; ?>
        </select>
        <?php if( isset($options['type']['google']) ) : ?>
            <div class="ts-google-fonts">
                <?php esc_html_e( 'Select font', 'videofly' ); ?>
                <script>
                    jQuery(document).ready(function($) {
                        ts_google_fonts(jQuery, {
                            font_name: '<?php echo esc_attr($fontName); ?>',
                            selected_subsets: [<?php echo vdf_var_sanitize($fontSubsets); ?>],
                            allfonts: $("#fontchanger-<?php echo vdf_var_sanitize($elementName ); ?>"),
                            prefix: '<?php echo vdf_var_sanitize($elementName ); ?>',
                            subsetsTypes: $('.<?php echo vdf_var_sanitize($elementName ); ?>-subset-types'),
                            section: '<?php echo vdf_var_sanitize($arrayValues); ?>'
                        });
                    });
                </script>
                <select name="<?php echo vdf_var_sanitize($arrayValues); ?>[<?php echo vdf_var_sanitize($elementName ); ?>][font_name]" id="fontchanger-<?php echo vdf_var_sanitize($elementName ); ?>" class="ts-font-name">
                    <option value="0"><?php esc_html_e( 'No font selected', 'videofly' ); ?></option>
                </select>

                <?php if( isset($options['font_subsets']) ) : ?>
                    <p><?php esc_html_e( 'Available subsets:', 'videofly' ); ?></p>
                    <div class="<?php echo vdf_var_sanitize($elementName ); ?>-subset-types"></div>
                <?php endif; ?>

                <?php if( isset($options['font_style']) ) : ?>
                    <p><?php  esc_html_e('Font style', 'videofly'); ?></p>
                    <select name="<?php echo vdf_var_sanitize($arrayValues); ?>[<?php echo vdf_var_sanitize($elementName); ?>][font_style]" id="ts-<?php echo vdf_var_sanitize($elementName ); ?>-font-style">
                        <option value="normal" <?php selected($fontStyle, 'normal', true); ?>><?php esc_html_e('Normal', 'videofly'); ?></option>
                        <option value="italic" <?php selected($fontStyle, 'italic', true) ?>><?php esc_html_e('Italic', 'videofly'); ?></option>
                    </select>
                <?php endif; ?>

                <?php if( isset($options['text']) ) : ?>
                    <p><?php esc_html_e('Logo text', 'videofly'); ?></p>
                    <textarea name="<?php echo vdf_var_sanitize($arrayValues); ?>[<?php echo vdf_var_sanitize($elementName); ?>][text]" id="<?php echo vdf_var_sanitize($elementName); ?>-demo" >
                        <?php echo vdf_var_sanitize($demoText ); ?>
                    </textarea>
                    <input type="button" name="ts-<?php echo vdf_var_sanitize($elementName); ?>-preview" id="ts-<?php echo vdf_var_sanitize($elementName); ?>-preview" class="button-primary" value="Preview"/><br />
                    <div class="<?php echo vdf_var_sanitize($elementName); ?>-output"></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="ts-font-options-parent" style="display: none">
            <div class="ts-font-options">

                <?php if( isset($options['font_weight']) ) : ?>
                    <div class="ts-font-weight">
                        <p><?php  esc_html_e('Font weight', 'videofly'); ?></p>
                        <select name="<?php echo vdf_var_sanitize($arrayValues); ?>[<?php echo vdf_var_sanitize($elementName); ?>][font_weight]" id="ts-<?php echo vdf_var_sanitize($elementName ); ?>-font-weight">
                            <option value="400" <?php selected($fontWeight, '400', true); ?>><?php esc_html_e('Normal', 'videofly'); ?></option>
                            <option value="700" <?php selected($fontWeight, '700', true) ?>><?php esc_html_e('Bold', 'videofly'); ?></option>
                        </select>
                    </div>
                <?php endif; ?>

                <?php if( isset($options['font_size']) ) : ?>
                    <p><?php esc_html_e('Font size', 'videofly'); ?>:</p>
                    <input type="text" name="<?php echo vdf_var_sanitize($arrayValues); ?>[<?php echo vdf_var_sanitize($elementName); ?>][font_size]" value="<?php echo vdf_var_sanitize($fontSize); ?>" id="ts-<?php echo vdf_var_sanitize($elementName); ?>-font-size" />
                    <div class="ts-option-description">
                        <?php esc_html_e('This will affect the most of the website. Write your number in PIXELS.', 'videofly'); ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <?php if( isset($options['type']['custom_font']) ) : $uploadCustomFonts = ''; ?>
            <div class="ts-custom-font">
                <?php foreach(array('eot', 'svg', 'ttf', 'woff') as $format) : ?>
                    <p><?php echo esc_html__('Upload file ', 'videofly') . '"' . $format . '"'; ?></p>
                    <input type="text" name="<?php echo vdf_var_sanitize($arrayValues); ?>[<?php echo vdf_var_sanitize($elementName ); ?>][font_<?php echo vdf_var_sanitize($format ); ?>]" value="<?php echo vdf_var_sanitize($values['font_' . $format]); ?>" id="atachment-<?php echo vdf_var_sanitize($elementName ); ?>-<?php echo vdf_var_sanitize($format ); ?>"/>
                    <input type="hidden" value="" id="file-<?php echo vdf_var_sanitize($elementName ); ?>-<?php echo vdf_var_sanitize($format); ?>"/>
                    <input type="button" class="button-primary ts-file-icon" id="upload-<?php echo vdf_var_sanitize($elementName ); ?>-<?php echo vdf_var_sanitize($format ); ?>" value="<?php esc_html_e('Upload', 'videofly') ?>">
                    <?php $uploadCustomFonts .= 'ts_upload_files("#upload-' . $elementName . '-' . $format . '", "#file-' . $elementName . '-' . $format . '", "#atachment-' . $elementName . '-' . $format . '", "Upload file ' . $format . '");'; ?>
                <?php endforeach; ?>
                <p><?php esc_html_e('Enter font-family(stylesheet.css):', 'videofly'); ?></p>
                <input type="text" name="<?php echo vdf_var_sanitize($arrayValues); ?>[<?php echo vdf_var_sanitize($elementName ); ?>][font_family]" value="<?php echo vdf_var_sanitize($fontFamily); ?>" />
            </div>
        <?php endif; ?>

        <?php if( isset($options['type']['image']) ) : ?>
            <div class="ts-logo-image">
                <p><?php esc_html_e( 'Please select your logo', 'videofly' ); ?></p>
                <input type="text" name="<?php echo vdf_var_sanitize($arrayValues); ?>[<?php echo vdf_var_sanitize($elementName ); ?>][image_url]" value="<?php echo vdf_var_sanitize($imageUrl); ?>" id="atachment-<?php echo vdf_var_sanitize($elementName ); ?>" class="image_url"/>
                <input type="hidden" value="" class="image_media_id" id="file-<?php echo vdf_var_sanitize($elementName ); ?>" />
                <input type="button" class="button-primary videofly_select_image" id="upload-<?php echo vdf_var_sanitize($elementName ); ?>" value="<?php esc_html_e('Upload image', 'videofly') ?>">

                <p><?php esc_html_e('Enable retina logo', 'videofly') ?></p>
                <select name="<?php echo vdf_var_sanitize($arrayValues); ?>[<?php echo vdf_var_sanitize($elementName); ?>][retina_logo]">
                    <option value="Y" <?php selected($retinaLogo, 'Y', true) ?>><?php esc_html_e('Yes', 'videofly'); ?></option>
                    <option value="N" <?php selected($retinaLogo, 'N', true) ?>><?php esc_html_e('No', 'videofly'); ?></option>
                </select>
                <input type="hidden" id="videofly_logo_retina_width" name="<?php echo vdf_var_sanitize($arrayValues); ?>[<?php echo vdf_var_sanitize($elementName); ?>][retina_width]" value="<?php echo vdf_var_sanitize($retinaWidth ); ?>"/>

            </div>
        <?php endif; ?>

        <?php if( isset($options['type']['std']) ) : ?>
            <input type="hidden" value="<?php echo vdf_var_sanitize($fontDefault); ?>" name="<?php echo vdf_var_sanitize($arrayValues); ?>[<?php echo vdf_var_sanitize($elementName ); ?>][standard_font]">
        <?php endif; ?>

        <script>
            jQuery(document).ready(function(){

                <?php if( isset($options['type']['custom_font']) ) : ?>
                <?php echo vdf_var_sanitize($uploadCustomFonts); ?>
            <?php endif; ?>

        });
        </script>

        <?php if( $elementName == 'body' ) : ?>
            <?php $icons = get_option('videofly_typography') ?>
            <div class="hidden">
                <input name="videofly_typography[icons]" type="hidden" value="<?php echo (isset($icons['icons']) ? $icons['icons'] : '') ?>">
            </div>
        <?php endif; ?>
    </div>
    <?php
}


//================== Styles Tab =======================================================

if ( ! function_exists('vdf_resize')) {

    function vdf_resize( $post_type, $image, $masonry = false ) {

        $image_sizes = get_option('videofly_image_sizes');
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

            case 'product':
            $options =  $image_sizes['product'];
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

        $crop = $options['mode'] == 'crop' ? true : false;

        if ( $masonry === true ) {

            $crop = false;

            $options['height'] = 9999;

        }

        $img_url = aq_resize( $image, $options['width'], $options['height'], $crop, true, true );

        return $img_url;
    }
}

//================== Single post Tab ==================================================

if ( ! function_exists('vdf_single_social_sharing')) {

    function vdf_single_social_sharing() {
        $single = get_option('videofly_single_post', array('social_sharing' => 'Y'));

        if ( $single['social_sharing'] === 'Y' ) {
            return true;
        } else {
            return false;
        }
    }
}

if ( ! function_exists('vdf_single_display_meta')) {

    function vdf_single_display_meta() {
        $single = get_option('videofly_single_post', array('post_meta' => 'Y'));

        if ($single['post_meta'] === 'Y') {
            return true;
        } else {
            return false;
        }
    }
}

if ( ! function_exists('vdf_single_display_tags')) {

    function vdf_single_display_tags() {
        $single = get_option('videofly_single_post', array('post_tags' => 'Y'));

        if ($single['post_tags'] === 'Y') {
            return true;
        } else {
            return false;
        }
    }
}

if( ! function_exists('vdf_single_diisplay_related') ) {
    function vdf_single_display_related() {
        $single = get_option('videofly_single_post', array('related_posts' => 'Y'));

        if ($single['related_posts'] === 'Y') {
            return true;
        } else {
            return false;
        }

    }
}


//================== Page Tab ==================================================

if ( ! function_exists('vdf_page_social_sharing')) {

    function vdf_page_social_sharing() {
        $single = get_option('videofly_page', array('social_sharing' => 'Y'));

        if ($single['social_sharing'] === 'Y') {
            return true;
        } else {
            return false;
        }
    }
}

if ( ! function_exists('vdf_hex2rgb')) {

    function vdf_hex2rgb($hex, $p) {
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

if ( ! function_exists('vdf_get_alert')) {
    function vdf_get_alert() {

        $options = get_option('videofly_red_area', array());

        if ( isset($options['alert']['id']) && isset($options['alert']['message']) ) {
            if ( $options['alert']['id'] !== 0 && ! empty($options['alert']['message']) ) {
                if ( is_array( $options['hidden_alerts'] ) ) {
                    if ( ! in_array( $options['alert']['id'], $options['hidden_alerts'] ) ) {
                        echo '<div class="updated">
                        <p>'.$options['alert']['message'].' | <a href="#" class="ts-remove-alert" data-token="'.wp_create_nonce("remove-videofly-alert").'" data-alets-id="'.$options['alert']['id'].'">'.esc_html__('Hide', 'videofly').'</a></p>
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
                    <h3>' . esc_html__('Attention', 'videofly') . '!</h3>
                    <p>' . esc_html__( '<b>You are using an old version of the theme. To ensure maximum compatibility and bugs fixed please keep the theme up to date.</b> <br>Do not forget that changes done directly in the theme files will be lost, use only Custom CSS areas and child themes if you wish to make changes.', 'videofly' ) .
                        '<br><br><a href="' . esc_url( network_admin_url( 'update-core.php' ) ) . '" class="button button-primary">' . esc_html__( 'Update now', 'videofly' ) . '</a>
                    </p>
                </div><br><br>';
        }
    }
}

add_action('admin_notices', 'vdf_get_alert');

if ( ! function_exists('vdf_update_redarea')) {
    function vdf_update_redarea() {
        $option = get_option('videofly_red_area', array());

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

if ( ! function_exists('vdf_theme_styles_rewrite')) {
    function vdf_theme_styles_rewrite() {
        // Get thene background color
        $theme = wp_get_theme();
        $nameTheme = (isset($theme) && is_object($theme)) ? $theme->name : '';
        $versionTheme = (isset($theme) && is_object($theme)) ? $theme->version : '';

        $optionsStyles = get_option('videofly_styles');
        $arrayWidth = array('1380', '1240', '1170', '960');
        $siteWidth = (isset($optionsStyles['site_width']) && in_array($optionsStyles['site_width'], $arrayWidth)) ? $optionsStyles['site_width'] : '1380';
        ?>
        <style type="text/css">
            /*************** Theme:  <?php echo vdf_var_sanitize($nameTheme); ?> *************/
            /*************** Theme Version:  <?php echo vdf_var_sanitize($versionTheme); ?> ************/
            /*
            --------------------------------------------------------------------------------
                1. GENERAL COLOR
            --------------------------------------------------------------------------------
            */
            @media (min-width: 1170px) {
              .container, .ts-mega-menu .ts_is_mega_div{
                max-width: <?php echo vdf_var_sanitize($siteWidth); ?>px;
            }
        }
        body{
            color: <?php echo vdf_get_color('general_text_color'); ?>;
        }
        .event-list-cal-excerpt{
            color: <?php echo vdf_get_color('general_text_color'); ?>;
        }
        #event-list-cal a{
            color: <?php echo vdf_get_color('general_text_color'); ?>;
        }
        .woocommerce #content div.product form.cart .variations label,
        .woocommerce div.product form.cart .variations label,
        .woocommerce-page #content div.product form.cart .variations label,
        .woocommerce-page div.product form.cart .variations label{
            color: <?php echo vdf_get_color('general_text_color'); ?>;
        }
        #searchform input[type="submit"]{
            color: <?php echo vdf_get_color('general_text_color'); ?>;
        }

            /*
            --------------------------------------------------------------------------------
                2. LINK COLOR
            --------------------------------------------------------------------------------
            */
            a{
                color: <?php echo vdf_get_color('link_color'); ?>;
            }
            a:hover, a:focus{
                color: <?php echo vdf_get_color('link_color_hover'); ?>;
            }
            .post-navigator ul li a:hover div{
                color: <?php echo vdf_get_color('link_color_hover'); ?>;
            }
            .post-navigator ul li a div{
                color: <?php echo vdf_get_color('link_color'); ?>;
            }
            .post-navigator ul li a:hover div{
                color: <?php echo vdf_get_color('link_color_hover'); ?>;
            }

            /*
            --------------------------------------------------------------------------------
                3. PRIMARY COLOR
            --------------------------------------------------------------------------------
            */
            .joyslider .entry-category a,
            .single-ts-gallery .single_gallery4 .inner-gallery-container .bx-wrapper .bx-controls-direction a:before,
            .ts-vertical-gallery .inner-gallery-container .bx-wrapper .bx-controls-direction a:before{
                background: <?php echo vdf_get_color('primary_color'); ?>;
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            .trigger-caption .button-trigger-cap{
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            .pixdot-effect > a{
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            .pixdot-effect > a:hover{
                background: <?php echo vdf_get_color('primary_color_hover'); ?>;
                color: <?php echo vdf_get_color('primary_text_color_hover'); ?>;
            }
            .pixdot-effect > a span{
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            .pixdot-effect > a:hover span{
                color: <?php echo vdf_get_color('primary_text_color_hover'); ?>;
            }
            .post-content blockquote:before,
            .post-content blockquote:after{
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .joyslider .entry-category a:hover{
                background: <?php echo vdf_get_color('primary_color_hover'); ?>;
                color: <?php echo vdf_get_color('primary_text_color_hover'); ?>;
            }
            .ts-post-nav > a:hover .inner-content{
                border-color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .view-video-play{
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            .ts-grid-view .entry-category li a,
            .ts-grid-view article a.article-view-more{
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .ts-grid-view .entry-category li a:hover{
                color: <?php echo vdf_get_color('primary_color_hover'); ?>;
            }
            .ts-grid-view article a.article-view-more:hover{
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
                background: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .menu-open .trigger-menu.close-menu{
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            .menu-open .trigger-menu.close-menu:hover{
                background-color: <?php echo vdf_get_color('primary_color_hover'); ?>;
                color: <?php echo vdf_get_color('primary_text_color_hover'); ?>;
            }
            .joyslider .slide-preview:hover{
                border-bottom-color: <?php echo vdf_get_color('primary_color'); ?>;;
            }
            .carousel-wrapper ul.carousel-nav > li,
            .carousel-wrapper ul.carousel-nav > li .hidden_btn{
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            .carousel-wrapper ul.carousel-nav > li:hover,
            .carousel-wrapper ul.carousel-nav > li:hover .hidden_btn{
                background-color: <?php echo vdf_get_color('primary_color_hover'); ?>;
                color: <?php echo vdf_get_color('primary_text_color_hover'); ?>;
            }
            .gallery-pagination-dot-selected{
                background: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .ts-video-fancybox span:first-child{
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            body:not(.ts-bordered-widgets) .widget-title:before,
            body:not(.ts-bordered-widgets) .widget-title:after{
                background: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .ts-video-fancybox:hover span:first-child{
                color: <?php echo vdf_get_color('primary_text_color_hover'); ?>;
                background-color: <?php echo vdf_get_color('primary_color_hover'); ?>;
            }
            .ts-pricing-view article.featured .featured_emblem{
                background: <?php echo vdf_get_color('primary_color'); ?>;
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            .ts-pricing-view article > header .entry-title .title,
            .ts-pricing-view article > footer > a{
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .ts-pricing-view article > footer > a:hover{
                color: <?php echo vdf_get_color('primary_color_hover'); ?>;
            }
            .woocommerce span.onsale-after,
            .woocommerce-page span.onsale-after{
                border-bottom: 10px solid <?php echo vdf_get_color('primary_color_hover'); ?>;
            }
            .single-ts-gallery .entry-meta .entry-category > li > a:hover{
                color: <?php echo vdf_get_color('primary_color_hover'); ?>;
            }
            .ts-big-countdown li i {
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .search #main .archive-title{
                border-color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .single-event .event-meta > li.delimiter,
            .single-event .event-meta > li.repeat{
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .event-list-cal-single{
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            .ts-powerlink header .content .title:before, .ts-powerlink header .content .title:after{
                background: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .mosaic-view article .ts-sep-wrap,
            .ts-thumbnail-view .ts-sep-wrap{
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .flickr_badge_image:hover a img{
                border-color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .post-slider .post-slider-list .entry-title h4 i{
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            .bx-wrapper .slider-caption .title a{
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            .bx-wrapper .slider-caption .title a:hover{
                color: <?php echo vdf_get_color('primary_text_color_hover'); ?>;
                background-color: <?php echo vdf_get_color('primary_color_hover'); ?>;
            }
            body.videofly .wp-playlist-light .wp-playlist-playing,
            body.videofly .mejs-controls .mejs-time-rail .mejs-time-current{
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            .post-meta ul li i{
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .woocommerce #content div.product p.price,
            .woocommerce #content div.product span.price,
            .woocommerce div.product p.price,
            .woocommerce div.product span.price,
            .woocommerce-page #content div.product p.price,
            .woocommerce-page #content div.product span.price,
            .woocommerce-page div.product p.price,
            .woocommerce-page div.product span.price,
            .woocommerce .woocommerce-message,
            .woocommerce-page .woocommerce-message {
                color: <?php echo vdf_get_color('primary_color') ?>;
            }
            .woocommerce span.onsale,
            .woocommerce-page span.onsale,
            .woocommerce #content div.product .woocommerce-tabs ul.tabs li{
                background: <?php echo vdf_get_color('primary_color') ?>;
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            .woocommerce #content div.product .woocommerce-tabs ul.tabs li.active a:after,
            .woocommerce div.product .woocommerce-tabs ul.tabs li.active a:after,
            .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active a:after,
            .woocommerce-page div.product .woocommerce-tabs ul.tabs li.active a:after{
                border-color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .woocommerce #content .woocommerce-result-count{
                color: <?php echo vdf_get_color('primary_color'); ?>;
                border-color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .woocommerce .widget_price_filter .ui-slider .ui-slider-range,
            .woocommerce-page .widget_price_filter .ui-slider .ui-slider-range,
            .woocommerce .widget_price_filter .ui-slider .ui-slider-handle,
            .woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle{
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .woocommerce .widget_layered_nav_filters ul li a,
            .woocommerce-page .widget_layered_nav_filters ul li a{
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
                border-color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .woocommerce #content .quantity .minus:hover,
            .woocommerce .quantity .minus:hover,
            .woocommerce-page #content .quantity .minus:hover,
            .woocommerce-page .quantity .minus:hover,
            .woocommerce #content .quantity .plus:hover,
            .woocommerce .quantity .plus:hover,
            .woocommerce-page #content .quantity .plus:hover,
            .woocommerce-page .quantity .plus:hover{
                background-color: <?php echo vdf_get_color('primary_color_hover'); ?>;
                color: <?php echo vdf_get_color('primary_text_color_hover'); ?>;
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
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
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
                background: <?php echo vdf_get_color('primary_color_hover'); ?> !important;
                color: <?php echo vdf_get_color('primary_text_color_hover'); ?> !important;
            }
            .woocommerce .woocommerce-info,
            .woocommerce-page .woocommerce-info,
            .woocommerce .woocommerce-message,
            .woocommerce-page .woocommerce-message{
                border-color: <?php echo vdf_get_color('primary_color'); ?>;
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
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .product-view .overlay-effect .entry-overlay > a:not(.entry-view-more){
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
                background-color: <?php echo vdf_get_color('primary_color') ?>
            }
            .product-view .overlay-effect .entry-overlay > a:not(.entry-view-more):hover{
                color: <?php echo vdf_get_color('primary_text_color_hover'); ?> !important;
                background-color: <?php echo vdf_get_color('primary_color_hover'); ?> !important;
            }
            .block-title-lineariconcenter .block-title-container i[class^="icon"]{
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .ts-clients-view div[data-tooltip]:hover:before {
                background-color: <?php echo vdf_hex2rgb(vdf_get_color('primary_color'), '0.8'); ?>;
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            .ts-clients-view div[data-tooltip]:hover:after {
                border-top-color: <?php echo vdf_hex2rgb(vdf_get_color('primary_color'), '0.8'); ?>;
            }
            .ts-mega-menu .main-menu .ts_is_mega_div .title:after,
            .ts-mobile-menu .main-menu .ts_is_mega_div .title:after {
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .ts-header-menu .main-menu .current-menu-item > a,
            .ts-header-menu .main-menu .current-menu-parent > a,
            .ts-header-menu .main-menu .current-menu-ancestor > a,
            .ts-mobile-menu .main-menu .current-menu-parent > a,
            .ts-mobile-menu .main-menu .current-menu-ancestor  > a,
            .ts-sticky-menu .main-menu .current-menu-item > a,
            .ts-sticky-menu .main-menu .current-menu-parent > a,
            .ts-sticky-menu .main-menu .current-menu-ancestor  > a{
                color: <?php echo vdf_get_color('primary_color'); ?> !important;
            }
            .ts-header-menu .menu-item-has-children .sub-menu li .ts_taxonomy_views a.view-more{
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .ts-header-menu .menu-item-has-children .sub-menu li .ts_taxonomy_views a.view-more:hover{
                color: <?php echo vdf_get_color('primary_color_hover'); ?>;
            }
            .ts-big-posts .ts-sep-wrap:after{
                background-color: <?php echo vdf_hex2rgb(vdf_get_color('primary_color'),0.5); ?>;
            }
            .tags-container a.tag, .tags-container a[rel="tag"]{
                border-color: <?php echo vdf_get_color('meta_color'); ?>;
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .tags-container a.tag:after, .tags-container a[rel="tag"]:after{
                background-color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .tags-container a.tag:hover, .tags-container a[rel="tag"]:hover{
                border-color: <?php echo vdf_get_color('primary_color'); ?>;
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .tags-container a.tag:hover:after, .tags-container a[rel="tag"]:hover:after, .list-view-tags a[rel="tag"]:hover:after{
                background: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .ts-thumbnail-view .thumb-post-categories a,
            .ts-grid-view .grid-post-categories a,
            .ts-big-posts .big-post-categories a{
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .ts-thumbnail-view .thumb-post-categories a:hover,
            .ts-grid-view .grid-post-categories a:hover,
            .ts-big-posts .big-post-categories a:hover{
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .ts-super-posts .article-view-more{
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            .ts-super-posts .article-view-more:hover{
                background-color: <?php echo vdf_get_color('primary_color_hover'); ?>;
                color: <?php echo vdf_get_color('primary_text_color_hover'); ?>;
            }
            .search-type-icon #searchbox input[type="text"]:focus{
                border-bottom-color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            #searchbox input.searchbutton:hover + i.icon-search{
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .search-no-results .searchpage,
            .search .attention{
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .ts-search-bar input[type="text"]:focus{
                border-color: <?php echo vdf_hex2rgb(vdf_get_color('primary_color'), '0.5'); ?>;
                border-bottom-color: <?php echo vdf_hex2rgb(vdf_get_color('primary_color'), '0.5'); ?>;
            }
            .ts-search-bar input[type="submit"]{
                background-color:  <?php echo vdf_get_color('primary_color'); ?>;
                color:  <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            .ts-search-bar input[type="submit"]:hover{
                background-color:  <?php echo vdf_get_color('primary_color_hover'); ?>;
                color:  <?php echo vdf_get_color('primary_text_color_hover'); ?>;
            }
            #commentform .form-submit input[type="submit"]{
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            .ts-pagination-more > i{
                background: <?php echo vdf_get_color('primary_color'); ?>;
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            .ts-pagination-more:hover > i{
                background: <?php echo vdf_get_color('primary_color_hover'); ?>;
                color: <?php echo vdf_get_color('primary_text_color_hover'); ?>;
            }
            .callactionr a.continue,
            .commentlist > li .comment .comment-reply-link{
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            .block-title-lineafter .block-title-container .the-title:after{
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .ts-filters li a {
                border-color: <?php echo vdf_get_color('primary_color'); ?>;
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }

            .ts-filters li.active a{
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            .ts-filters li a:not(.active):hover{
                color: <?php echo vdf_get_color('primary_text_color_hover'); ?>;
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .post-navigator ul li a{
                border-top-color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .post-navigator ul li a:hover{
                border-top-color: <?php echo vdf_get_color('secondary_color'); ?>;
            }
            #commentform .form-submit input[type="submit"]{
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .listed-two-view .item-hover, .ts-big-posts .item-hover{
                background-color: <?php echo vdf_hex2rgb(vdf_get_color('primary_color'), '0.8') ?>;
            }
            .block-title-linerect .block-title-container:before{
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .teams article:hover .image-holder img{
                border-color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .delimiter.iconed:before{
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .block-title-leftrect .block-title-container:before{
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            a.tag:hover, a[rel="tag"]:hover{
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            input.contact-form-submit,
            #nprogress .bar {
                background: <?php echo vdf_get_color('primary_color'); ?>;
            }
            #nprogress .spinner-icon {
                border-top-color: <?php echo vdf_get_color('primary_color'); ?>;
                border-left-color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .post-slider .post-slider-list .entry-category ul li a, .post-slider .main-entry .entry-category a{
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .post-slider .main-entry .entry-content .entry-title:hover{
                border-right-color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .ts-pagination ul .page-numbers{
                background: #f7f7f7;
                color: #343434;
            }
            .ts-pagination ul .page-numbers.current{
                background: <?php echo vdf_get_color('primary_color'); ?>;
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            .slyscrollbar .handle{
                background: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .ts-grid-view article .entry-footer .btn-play-video:hover > i{
                background: <?php echo vdf_get_color('primary_color'); ?>;
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            .btn.active{
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }

            .mCS-dark.mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar{
                background: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .mCS-dark.mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar:hover,
            .mCS-dark.mCSB_scrollTools .mCSB_dragger:hover .mCSB_dragger_bar{
                background: <?php echo vdf_get_color('primary_color_hover'); ?>;
            }
            .ts-powerlink header .content .title{
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            .nav-tabs .tab-item.active > a:before,
            .nav-tabs .tab-item.active > a:hover:before,
            .nav-tabs .tab-item.active > a:focus:before{
                border-color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .ts-tags-container > a:after,
            .ts-tags-container a.tag:hover,
            article .default-effect .overlay-effect .view-more > span:before,
            article .default-effect .overlay-effect .view-more > span:after{
                background: <?php echo vdf_get_color('primary_color'); ?>;
            }
            article[data-sticky="is-sticky"] .is-sticky-div{
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
            }

            .ts-video-carousel .nav-arrow .nav-icon {
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }

            .post-content .event-meta-details li i{
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .post-author-box + .delimiter i{
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .ts-get-calendar.ts-next:hover, .ts-get-calendar.ts-prev:hover {
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            .ts-event-title a{
                background: <?php echo vdf_get_color('primary_color'); ?>;
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            .ts-small-countdown .time-remaining li > span{
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            .main-menu > .current-menu-ancestor:after,
            .main-menu > .current_page_item:after{
                border-color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .ts-grid-view article a,
            .ts-thumbnail-view article a,
            .ts-big-posts article a,
            .ts-list-view article a,
            .ts-super-posts article a,
            .product-view article a,
            .ts-timeline a,
            .ts-article-accordion .inner-content a{
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .ts-grid-view article a:hover,
            .ts-big-posts article a:hover,
            .ts-big-posts article .title a:hover,
            .ts-list-view article a:hover,
            .ts-super-posts article a:hover,
            .product-view article a:hover,
            .ts-timeline a:hover,
            .ts-article-accordion .inner-content a:hover {
                color: <?php echo vdf_get_color('primary_color_hover'); ?>;
            }
            .product-view article .grid-shop-button .button.add_to_cart_button{
                border-color: <?php echo vdf_get_color('primary_color'); ?>;
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .product-view article .grid-shop-button .button.add_to_cart_button:hover{
                background-color: <?php echo vdf_get_color('primary_color_hover'); ?>;
                color: <?php echo vdf_get_color('primary_text_color_hover'); ?>;
                border-color: <?php echo vdf_get_color('primary_color_hover'); ?>;
            }
            .ts-super-posts .entry-meta .entry-meta-likes{
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            .ts-timeline section .entry-meta:before{
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
                border-color: #fff;
            }
            .ts-lima-effect .lima-details .more-details{
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            .ts-powerlink header .content .button{
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            .ts-iconbox-bordered figure figcaption .btn,
            .ts-iconbox-background figure figcaption .btn{
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            .ts-iconbox-bordered figure figcaption .btn:hover,
            .ts-iconbox-background figure figcaption .btn:hover{
                background-color: <?php echo vdf_get_color('primary_color_hover'); ?>;
                color: <?php echo vdf_get_color('primary_text_color_hover'); ?>;
            }
            .ts-article-accordion .panel-heading .entry-icon{
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .teams article .image-holder .team-box-square:before,
            .teams article .image-holder .team-box-square:after,
            .teams article .image-holder .team-box-square2:before,
            .teams article .image-holder .team-box-square2:after{
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .teams article h4 a:hover{
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .single .post-rating .rating-items li .rating-title:before{
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
            }

            .ts-pagination-more{
                border-color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .ts-pagination-more:before, .ts-pagination-more:after, .ts-pagination-more span:before, .ts-pagination-more span:after{
                background: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .testimonials .entry-section .inner-section .author-name a:hover{
                color: <?php echo vdf_get_color('primary_color_hover'); ?>;
            }
            .nav-fillslide a.prev .icon-wrap, .nav-fillslide a.next .icon-wrap{
                border-color: <?php echo vdf_get_color('primary_color'); ?>;
                color: #555;
            }
            .nav-fillslide h3 {
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            #searchbox .hidden-form-search i.icon-search:hover{
                color: <?php echo vdf_get_color('primary_color_hover'); ?>;
            }
            #searchbox .hidden-form-search .search-close{
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            #searchbox .hidden-form-search .search-close:hover{
                color: <?php echo vdf_get_color('primary_color_hover'); ?>;
            }
            .ts-select-by-category li.active a{
                border-top: 2px solid <?php echo vdf_get_color('primary_color'); ?>;
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .mc4wp-form input[type="email"]:active,
            .mc4wp-form input[type="text"]:active,
            .mc4wp-form input[type="email"]:focus,
            .mc4wp-form input[type="text"]:focus,
            .ts_widget.buddypress input[type="text"]:focus,
            .ts_widget.buddypress input[type="email"]:focus,
            .ts_widget.buddypress input[type="password"]:focus,
            .ts_widget.buddypress input[type="text"]:active,
            .ts_widget.buddypress input[type="email"]:active,
            .ts_widget.buddypress input[type="password"]:active,            
            #mc4wp_email:active,
            #mc4wp_email:focus{
                border-color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .mc4wp-form input[type="submit"],
            .ts_widget.buddypress input[type="submit"]{
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .mc4wp-form input[type="submit"]:hover{
                background-color: <?php echo vdf_get_color('primary_color_hover'); ?>;
                color: <?php echo vdf_get_color('primary_text_color_hover'); ?>;
            }
            .post-tags .tags-container a[rel="tag"]{
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .post-tags .tags-container a[rel="tag"]:hover{
                color: <?php echo vdf_get_color('primary_color_hover'); ?>;
            }

            .flickity-page-dots .dot.is-selected{
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .fotorama__thumb-border{
                border-color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .ts-post-nav .post-nav-content .content-wrapper >  span{
                /*color: <?php echo vdf_get_color('primary_color'); ?>;*/
            }
            .comment-form input[type="text"]:focus,
            .comment-form input[type="email"]:focus,
            .comment-form textarea:focus{
                border-color: <?php echo vdf_hex2rgb(vdf_get_color('primary_color'), '0.7'); ?>;
            }
            .ts-single-two article .ts-section-title .ts-favorites a span:before,
            .ts-single-two article .ts-section-title .ts-favorites a i,
            .ts-single-two article .ts-section-title .ts-favorites a:hover {
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            .ts-slidein-block .ts-add-video{
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            .ts-slidein-block .ts-register-form form .ts-login-btn button ,
            .ts-slidein-block .ts-add-video a,
            .ts-slidein-block .ts-add-video a:hover,
            .ts-slidein-block  a.ts-add-video,
            .ts-slidein-block  a.ts-add-video:hover,
            #buddypress a.follow,
            .ts-upload-page form .ts-top .dropdown-menu > li > a:hover{
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            .ts-slidein-block .ts-register-form form .ts-login-register,
            .ts-upload-page form span.ts-inf-title,
            .ts-upload-page form .ts-middle .box:hover i{
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }



            /*
            --------------------------------------------------------------------------------
                4. SECONDARY COLOR
            --------------------------------------------------------------------------------
            */
            .post-edit-link{
                color: <?php echo vdf_get_color('secondary_color'); ?>;
            }
            .post-edit-link:hover{
                color: <?php echo vdf_get_color('secondary_color_hover'); ?>;
                border-color: <?php echo vdf_get_color('secondary_color_hover'); ?>;
            }
            .ts-big-countdown .time-remaining > li > div{
                color: <?php echo vdf_get_color('secondary_color'); ?>;
            }
            .single-event .event-time{
                color: <?php echo vdf_get_color('secondary_color'); ?>;
            }
            .ts-featured-article .featured-signal{
                color: <?php echo vdf_get_color('secondary_text_color'); ?>;
                background-color: <?php echo vdf_get_color('secondary_color'); ?>;
            }
            .event-list-cal th {
                background-color: <?php echo vdf_get_color('secondary_color'); ?>;
                color: <?php echo vdf_get_color('secondary_text_color'); ?>;
                border-color: <?php echo vdf_get_color('secondary_color_hover'); ?>;
                text-shadow: 1px 1px 0 <?php echo vdf_get_color('secondary_color_hover'); ?>;
            }
            .event-list-cal td.today .event-list-cal-day{
                background-color: <?php echo vdf_get_color('secondary_color'); ?>;
                color: <?php echo vdf_get_color('secondary_text_color'); ?>;
                text-shadow: 1px 1px 0px <?php echo vdf_get_color('secondary_color_hover'); ?>;
            }
            .widget_list_events .widget-meta .date-event .day{
                color: <?php echo vdf_get_color('secondary_color'); ?>;
            }
            .ts-thumbnail-view article:hover .ts-sep-wrap{
                color: <?php echo vdf_get_color('secondary_color'); ?>;
            }
            .bx-wrapper .slider-caption .sub{
                background-color: <?php echo vdf_get_color('secondary_color'); ?>;
                color: <?php echo vdf_get_color('secondary_text_color'); ?>;
            }
            .ts-bxslider .controls-direction span a{
                background-color: <?php echo vdf_get_color('secondary_color'); ?>;
                color: <?php echo vdf_get_color('secondary_text_color'); ?>;
            }
            .bx-wrapper .bx-pager.bx-default-pager a.active{
                background-color: <?php echo vdf_get_color('secondary_color'); ?>;
            }
            .teams article .article-title{
                color: <?php echo vdf_get_color('secondary_color'); ?>;
            }
            .ts-team-single .member-content .member-name .category > li{
                background-color: <?php echo vdf_get_color('secondary_color'); ?>;
                color: <?php echo vdf_get_color('secondary_text_color'); ?>;
            }
            .single-portfolio .page-title{
                color: <?php echo vdf_get_color('secondary_color'); ?>;
            }
            .woocommerce #content .quantity .minus,
            .woocommerce .quantity .minus,
            .woocommerce-page #content .quantity .minus,
            .woocommerce-page .quantity .minus,
            .woocommerce #content .quantity .plus,
            .woocommerce .quantity .plus,
            .woocommerce-page #content .quantity .plus,
            .woocommerce-page .quantity .plus{
                background-color: <?php echo vdf_get_color('secondary_color'); ?>;
                color: <?php echo vdf_get_color('secondary_text_color'); ?>;
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
                color: <?php echo vdf_get_color('secondary_color'); ?>;
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
                color: <?php echo vdf_get_color('secondary_color_hover'); ?>;
            }
            .woocommerce .product-view a.button:after,
            .woocommerce .product-view button.button:after,
            .woocommerce .product-view input.button:after{
                background-color: <?php echo vdf_get_color('secondary_color'); ?>
            }
            .woocommerce .product-view a.button:hover:after,
            .woocommerce .product-view button.button:hover:after,
            .woocommerce .product-view input.button:hover:after{
                background-color: <?php echo vdf_get_color('secondary_color_hover'); ?>
            }
            .product-view .overlay-effect .entry-overlay > a{
                color: <?php echo vdf_get_color('secondary_text_color'); ?>;
                background-color: <?php echo vdf_get_color('secondary_color'); ?>;
            }
            .product-view .overlay-effect .entry-overlay > a:hover{
                color: <?php echo vdf_get_color('secondary_text_color_hover'); ?>;
                background-color: <?php echo vdf_get_color('secondary_color_hover'); ?>;
            }
            .callactionr a.continue:hover{
                background-color: <?php echo vdf_get_color('secondary_color'); ?>;
                color: <?php echo vdf_get_color('secondary_text_color'); ?>;
            }
            .ts-grid-view .item-hover{
                background-color: <?php echo vdf_hex2rgb(vdf_get_color('secondary_color'), '0.8') ?>;
            }
            .teams article:hover .article-title{
                border-color: <?php echo vdf_get_color('secondary_color'); ?>;
            }
            .ts-pagination ul .page-numbers:hover{
                background: <?php echo vdf_get_color('secondary_color'); ?>;
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
            .purchase-btn{
                color: <?php echo vdf_get_color('secondary_color'); ?>;
            }
            .purchase-btn:hover{
                background: <?php echo vdf_get_color('secondary_color'); ?>;
            }
            .ts-powerlink header .content .button:hover{
                background-color: <?php echo vdf_get_color('secondary_color_hover'); ?>;
                color: <?php echo vdf_get_color('secondary_text_color_hover'); ?>;
            }
            .ts-small-countdown .time-remaining li > i{
                color: <?php echo vdf_get_color('secondary_color'); ?>;
            }
            .ts-events-calendar tr td.calendar-day-head{
                background: <?php echo vdf_get_color('secondary_color'); ?>;
                color: <?php echo vdf_get_color('secondary_text_color'); ?>;
            }

            /*
            --------------------------------------------------------------------------------
                5. META COLOR
            --------------------------------------------------------------------------------
            */
            .product-view article .entry-categories a{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .ts-pricing-view article > header .entry-box .pricing-price > .period,
            .ts-pricing-view article > header .entry-box .pricing-price > .currency{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .mega-menu-item-description{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .single-ts-gallery .post-date .entry-meta-date > li.meta-month{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .ts-big-posts article .entry-meta,
            .ts-big-posts article .entry-meta a span,
            .ts-big-posts article .entry-meta a,
            .ts-featured-article span.entry-meta-author .author-name{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .archive-title span,
            .archive-desc p,
            footer .related .related-list .related-content .ts-view-entry-meta-date,
            .ts-timeline-view .entry-meta .post-date-add,
            .ts-grid-view article .ts-view-entry-meta-date,
            .ts-bigpost-view article .ts-view-entry-meta-date,
            .ts-list-view article .ts-view-entry-meta-date,
            .ts-list-view .entry-meta-likes a{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .ts-big-countdown .time-remaining > li > span,
            article .entry-meta-category,
            article .entry-category > li > a,
            .ts-timeline .article-view-more,
            .ts-list-view .article-view-more {
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }
            article .entry-category > li > a:hover{
                color: <?php echo vdf_get_color('primary_color_hover'); ?>;
            }
            .single-event .event-meta > li span.meta{
                color: <?php echo vdf_get_color('meta_color'); ?>;
                font-size: 13px;
            }
            .widget_list_events .widget-meta .date-event .month{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .post-title-meta,
            .post-title-meta .post-categories a,
            .post-title-meta .post-title-meta-categories > i{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .teams article .article-position{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .ts-team-single .member-content .position{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .testimonials .inner-header .header-icon,
            .testimonials .inner-footer .footer-icon,
            .testimonials .entry-section .inner-section .author-position{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .post-title-meta, .ts-big-posts .big-post-meta > ul > li, .ts-grid-view .entry-meta > li, .views-delimiter{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .single .page-subtitle{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .single .single-meta-sidebar .inner-aside .entry-post-comments a{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .search-results .searchcount{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .ts-cool-share label > span,
            .ts-cool-share ul li .how-many{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .time-remaining li span{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .ts-grid-view article .entry-category,
            .ts-big-posts article .entry-category,
            .ts-thumbnail-view article[data-title-position="below-image"] .entry-category,
            .ts-thumbnail-view article[data-title-position="below-image"] .entry-date,
            .ts-big-posts .entry-meta ul li{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .entry-meta{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .ts-timeline section .entry-author,
            .ts-timeline section .entry-meta ul .meta-month,
            .ts-list-view .entry-meta-date{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .ts-featured-area .featured-area-content .entry-content .entry-meta,
            .ts-featured-area.posts-right-of-main-image .featured-area-content .entry-content .entry-meta-likes,
            .ts-featured-area .featured-area-tabs .entry-meta-date,
            .ts_taxonomy_views .ts-date{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .ts-article-accordion .entry-meta-date{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .teams article .article-excerpt{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .single .entry-meta .post-meta,
            .single .post-meta-categories > a{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .nav-fillslide div span {
                color: <?php echo vdf_get_color('meta_color'); ?>;
                border-color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .nav-fillslide p{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .widget .count-item{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .ts-video-carousel .slides .carousel-meta li{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .single-ts-gallery .inner-gallery-container .overlay-effect .entry-overlay .entry-controls > li > a,
            .ts-gallery-element .overlay-effect .entry-overlay .entry-controls > li > a{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .single-ts-gallery .single_gallery1 .entry-controls > li > a,
            .ts-gallery-element .entry-controls > li > a{
                border-color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .single-ts-gallery .entry-category > li > a{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .protected-post-form .lead{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .single-ts-gallery .inner-gallery-container .overlay-effect .entry-overlay .social-sharing > li > a,
            .ts-gallery-element .overlay-effect .entry-overlay .social-sharing > li > a{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .ts-article-accordion .entry-meta a{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .entry-meta,
            .entry-secondary-meta,
            .entry-meta li,
            .entry-secondary-meta li,
            article .entry-meta-author a,
            article .entry-meta-author a:hover{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }

            /*
            --------------------------------------------------------------------------------
                6. VIEWS COLOR
            --------------------------------------------------------------------------------
            */
            .ts-big-posts article section h3 a:hover,
            .ts-super-posts article section h3 a:hover {
                color: <?php  echo vdf_get_color('view_link_color_hover');?>;
            }
            .ts-list-view article section h3 a {
                color: <?php echo vdf_get_color('view_link_color'); ?>
            }
            .ts-grid-view article .entry-title a,
            .ts-thumbnail-view article .entry-title a,
            .ts-thumbnails-bellow article section h3 a,
            .ts-big-posts article .entry-title a,
            .ts-list-view article .entry-title a,
            .product-view article .entry-title a,
            .ts-timeline-view  header h3 a,
            .ts-big-posts article section h3 a {
                color: <?php echo vdf_get_color('view_link_color'); ?>;
            }
            .ts-list-view .entry-meta ul li.meta-date
            {
                color: <?php echo vdf_get_color('view_link_color'); ?>;
            }
            .ts-list-view article section .entry-meta-date,
            .ts-timeline-view article header .entry-meta ul li.entry-meta-date{
                color: <?php echo vdf_get_color('meta_color'); ?>;
            }
            .post-tags .tags-container a[rel="tag"]:hover{
                background-color: transparent;
            }
            .ts-grid-view article .entry-title a:hover,
            .ts-big-posts article .entry-title a:hover,
            .ts-thumbnail-view article[data-title-position="below-image"] .entry-title a:hover,
            .ts-thumbnails-bellow article section h3 a:hover,
            .ts-list-view article .entry-title a:hover,
            .product-view article .entry-title a:hover,
            .ts-timeline-view header h3 a:hover{
                color: <?php echo vdf_get_color('view_link_color_hover'); ?>;
            }
            .entry-excerpt{
                color: <?php echo vdf_get_color('excerpt_color'); ?>;
            }
            .ts-timeline-view article header .entry-meta ul li.entry-meta-time span {
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }

            /*
            --------------------------------------------------------------------------------
                7. MENU COLOR
            --------------------------------------------------------------------------------
            */
            .ts-header-menu .main-menu li a:hover,
            .ts-sticky-menu .main-menu li a:hover,
            .ts-mobile-menu .main-menu li a:hover,
            .ts-behold-menu > li > a:hover {
                color: <?php echo vdf_get_color('submenu_text_color_hover'); ?>;
            }
            .ts-header-menu .main-menu > .menu-item-has-children ul li > a:before,
            .ts-sticky-menu .main-menu > .menu-item-has-children ul li > a:before,
            .ts-mega-menu .menu-item-has-children .ts_is_mega_div .ts_is_mega > li > a:before,
            .ts-mobile-menu .main-menu > .menu-item-has-children ul li > a:before,
            .ts-mobile-menu .menu-item-type-taxonomy.menu-item-has-children .ts_is_mega_div > .sub-menu li a:before{
                background-color: <?php echo vdf_hex2rgb(vdf_get_color('submenu_bg_color_hover'),'0.3'); ?>;
            }
            .ts-header-menu .main-menu > .menu-item-has-children ul li > a:after,
            .ts-sticky-menu .main-menu > .menu-item-has-children ul li > a:after,
            .ts-mega-menu .menu-item-has-children .ts_is_mega_div .ts_is_mega > li > a:after,
            .ts-mobile-menu .menu-item-type-taxonomy.menu-item-has-children .ts_is_mega_div > .sub-menu li a:after{
                background-color: <?php echo vdf_get_color('submenu_bg_color_hover'); ?>;
            }
            .ts-header-menu .main-menu li > a,
            .ts-sticky-menu .main-menu li > a,
            .ts-mobile-menu .main-menu li > a,
            .ts-behold-menu li > a,
            .ts-mobile-menu .menu-item-type-taxonomy.menu-item-has-children .ts_is_mega_div > .sub-menu li a,
            .ts-standard-menu .main-menu li ul .menu-item-has-children:after,
            .ts-sticky-menu .main-menu li ul .menu-item-has-children:after {
                color: <?php echo vdf_get_color('submenu_text_color'); ?>;
            }
            .ts-header-menu .sub-menu:not(.ts_is_mega),
            .ts-sticky-menu .sub-menu:not(.ts_is_mega),
            .ts-mega-menu .menu-item-type-taxonomy .sub-menu,
            .ts-mobile-menu .sub-menu,
            .ts-mega-menu .is_mega .ts_is_mega_div:after,
            .is-sidebar-menu + .ts-mobile-menu .mobile_menu {
                background-color: <?php echo vdf_get_color('submenu_bg_color'); ?>;
            }
            .ts-mega-menu .main-menu .ts_is_mega_div .title,
            .ts-mobile-menu .title,
            .ts-mobile-menu .main-menu .ts_is_mega_div .title{
                color: <?php echo vdf_get_color('submenu_text_color'); ?>;
            }

            .sub-menu li a:hover{
                color: <?php echo vdf_get_color('submenu_text_color_hover'); ?>;
            }

            /*Category colors*/
            .ts-timeline-view article footer ul.entry-meta li.entry-secondary-meta li.ts-read-btn a{
                color: <?php echo vdf_get_color('primary_color'); ?>;
            }
             .ts-grid-view article footer ul.entry-secondary-meta li.ts-read-btn a:hover,
            .ts-timeline-view article footer ul.entry-meta li.entry-secondary-meta li.ts-read-btn a:hover{
                color: <?php echo vdf_get_color('primary_color_hover'); ?>;
            }
            .ts-featured-article article .entry-featured-title{
                color: <?php echo vdf_get_color('primary_text_color'); ?>;
            }
           .ts-super-posts article section a.ts-read-btn,
           .ts-thumbnails-over article section ul.entry-meta-category li a,
           .ts-thumbnails-bellow article section .entry-meta-category a,
           .ts-featured-article article section ul.entry-meta li.entry-meta-category a,
           .mosaic-view article section ul.entry-meta li.entry-meta-category a,
           .ts-timeline-view article footer ul.entry-meta li.entry-meta-category a,
           .ts-big-posts article section ul.entry-meta li.entry-meta-category a,
           .ts-list-view article  ul.entry-meta li.entry-meta-category a,
           .ts-grid-view article section ul.entry-meta li.entry-meta-category a{
                background-color: <?php echo vdf_get_color('primary_color'); ?>;
                color: <?php echo vdf_get_color('primary_text_color'); ?>
            }
            .ts-super-posts article section a.ts-read-btn:hover,.entry-meta-category a:hover{
                background-color: <?php echo vdf_get_color('primary_color_hover'); ?>;
                color: <?php echo vdf_get_color('primary_text_color_hover'); ?>
            }

        /*likes / dislikes*/
            .entry-likes a,
            .entry-likes a span,
            .entry-likes a span:before{
                color: <?php echo vdf_get_color('meta_color') ?>;
            }

        .ts-single-video .single-category li a{
            color: <?php echo vdf_get_color('primary_text_color') ?>;

        }
        .ts-single-video .single-category li a{
         background-color: <?php echo vdf_get_color('primary_color'); ?>;
        }
        .ts-single-video .single-category li a:hover{
           background-color: <?php echo vdf_get_color('primary_color_hover'); ?>;
        }
        .ts-single-two article section .ts-user-section ul.entry-meta li[class*="ts-"] span,
        .ts-single-video .single-tags li a{
            color: <?php echo vdf_get_color('meta_color'); ?>;
        }
        .ts-single-video .vdf-add-playlist li:hover,
        .ts-single-video .vdf-add-playlist li:before{
            color: <?php echo vdf_get_color('primary_color'); ?>;
        }

        /* featured area arrows*/
        .ts-slider-cyncing .ts-cyncing-nav ul.ts-flex-navigation li a{
            background-color: <?php echo vdf_get_color('primary_color'); ?>;
        }
        .ts-slider-cyncing .ts-cyncing-nav ul.slides li:after,
        .ts-slider-cyncing .ts-cyncing-nav ul.slides li.flex-active-slide:after{
            border: 3px solid <?php echo vdf_get_color('primary_color'); ?>;
        }

        .ts-slider-cyncing .ts-cyncing-nav ul.ts-flex-navigation li a:hover {
            background-color: <?php echo vdf_get_color('primary_color_hover'); ?>;
        }

        /*social icons*/
        .social-icons.big-background ul li a{
            background-color: <?php echo vdf_get_color('primary_color'); ?>;
            color: <?php echo vdf_get_color('primary_text_color'); ?>;
        }
        /*username/login*/
        .ts-btn .ts-username,
        .ts-btn .ts-autentification{
            color: <?php echo vdf_get_color('meta_color'); ?>;
        }
        .ts-btn a i{
            border: 1px solid <?php echo vdf_get_color('primary_color'); ?>;
            color: <?php echo vdf_get_color('primary_color'); ?>;
        }
        .ts-btn .ts-username:hover,
        .ts-btn .ts-autentification:hover{
            color: <?php echo vdf_get_color('primary_color'); ?>;
        }
        .ts-btn .ts-username:hover i,
        .ts-btn .ts-autentification:hover i{
            background-color: <?php echo vdf_get_color('primary_color'); ?>;
            color: <?php echo vdf_get_color('primary_text_color'); ?>;
        }
        .ts-slider-cyncing .ts-cyncing-for article section h3 i{
            background-color: <?php echo vdf_get_color('primary_color') ?>;
            color: <?php echo vdf_get_color('primary_text_color') ?>;
        }

        .ts-slider-cyncing .ts-cyncing-for article section h3:hover i{
            background-color: <?php  echo vdf_get_color('primary_color_hover'); ?>;
        }
        .ts-single-two article section .ts-user-section .entry-user span.ts-follow-btn,
        .ts-single .ts-video-footer ul.entry-meta .ts-follow-btn{
            background-color: <?php echo vdf_get_color('primary_color') ?>;
            color: <?php echo vdf_get_color('primary_text_color') ?>;
        }
        .ts-single-two article section .ts-user-section .entry-user span.ts-follow-btn:hover,
        .ts-single .ts-video-footer ul.entry-meta .ts-follow-btn:hover{
            background-color: <?php echo vdf_get_color('primary_color_hover') ?>;
        }

        /*Playlist colors*/

        #ts_playlist .ts_title{
            color: <?php echo vdf_get_color('primary_text_color'); ?>
        }
        .ts_bottomArrow:hover > div:before,
        .ts_topArrow:hover > div:before{
            color: <?php echo vdf_get_color('primary_color'); ?>;
        }

        /*Boca arrpows*/
        .post-slides .customNavigation li:hover i{
            color: <?php echo vdf_get_color('primary_color');?>;
        }

        /*Add to playlist*/
        .ts-slidein-block .ts-new-playlist input[type="text"]:active,
        .ts-slidein-block .ts-new-playlist input[type="text"]:focus{
            border-color: <?php echo vdf_get_color('primary_color'); ?>;
        }
        #buddypress .standard-form div.submit input,
        .ts-slidein-block .ts-new-playlist button{
            color: <?php echo vdf_get_color('primary_text_color'); ?>;
            background-color: <?php echo vdf_get_color('primary_color'); ?>;
        }
        .ts-slidein-block .ts-new-playlist button:hover{
            background-color: <?php echo vdf_get_color('primary_color_hover'); ?>;
            color: <?php echo vdf_get_color('primary_text_color_hover'); ?>;
        }


        .user-profile-page .user-tabs li{
            background: <?php echo vdf_get_color('primary_text_color'); ?>;
            border:1px solid <?php echo vdf_get_color('primary_color'); ?>;
        }
        .user-profile-page .user-tabs li a{
            color: <?php echo vdf_get_color('primary_color') ?>;
        }

        .user-profile-page .user-tabs li.active a,
        .user-tabs.nav-tabs > li a:hover,
        .user-tabs.nav-tabs > li a:focus{
            background-color: <?php echo vdf_get_color('primary_color') ?>;
            color: <?php echo vdf_get_color('primary_text_color') ?>;
        }

        .user-profile-page .edit-post-link,
        .user-profile-page .vdf-remove-fromplaylist,
        .user-profile-page .vdf-playlist-item header > button,
        .user-profile-page .ts-create-playlist button,
        .user-profile-page #buddypress button,
        .ts-create-playlist .ts-form-toggle span{
            border-color: <?php echo vdf_get_color('primary_color') ?>;
            background-color: <?php echo vdf_get_color('primary_color'); ?>;
            color: <?php echo vdf_get_color('primary_text_color'); ?>;
        }        


        .ts-follow-count{
            color: <?php echo vdf_get_color('meta_color'); ?>;
        }
        .ts-download.dark a:hover,
        .ts-download.dark a{
            color: <?php echo vdf_get_color('meta_color'); ?>;
        }
        .btnOverScreen:hover,
        .ts_volumeTrackProgressScrubber,
        .ts_volumeTrackProgress,
        .ts_videoTrackProgressScrubber,
        .ts_videoTrackProgress{
            background-color: <?php echo vdf_get_color('primary_color'); ?>;
        }

        /*Instance element*/
        .ts-instance .instance-read-more span{
            background-color: <?php echo vdf_get_color('primary_color'); ?>;
            color: <?php echo vdf_get_color('primary_text_color'); ?>;
        }
        .ts-instance .instance-read-more:hover span{
            background-color: <?php echo vdf_get_color('primary_color_hover'); ?>;
            color: <?php echo vdf_get_color('primary_text_color_hover'); ?>;
        }

            <?php $typography = vdf_get_custom_fonts_css(); echo vdf_var_sanitize($typography["style"]); ?>
            <?php echo vdf_custom_background(); ?>
            <?php echo vdf_get_sticky_menu_style() ?>

            /* --- Custom CSS Below ----  */
            <?php echo ts_inline_style() ?>
        </style>
        <?php
    }
}

if ( ! function_exists('ts_inline_style')) {
    function ts_inline_style() {
        $option = get_option('inline_style', array('css' => ''));
        return $option['css'];
    }
}

/* register sidebars */
if ( function_exists('register_sidebar') ) {
    register_sidebar(array(
        'name' => esc_html__( 'Main Sidebar', 'videofly' ),
        'id' => 'main',
        'before_widget' => '<aside id="%1$s" class="widget ts_widget %2$s"><div class="widget-content">',
        'after_widget'  => '</div></aside>',
        'before_title'  => '<h6 class="widget-title ts_sidebar_title">',
        'after_title'   => '</h6><div class="widget-delimiter"></div>'
        ));

}

function vdf_imagesloaded( $bool, $img_url )
{
    if( $bool == 'Y' ){
        $src = 'class="lazy" data-layzr="' . esc_url( $img_url ) . '"';
    }else{
        $src = 'src=" ' . esc_url( $img_url ) . ' "';
    }

    return $src;
}

function vdf_menuCallback(){
    wp_page_menu(array(
        'menu_class'  => 'ts-behold-menu main-menu ',
        'include'     => '',
        'exclude'     => '',
        'echo'        => true,
        'link_before' => '',
        'link_after'  => ''
        ));
}

// Check if mega menu option is enabled and add mega menu support
$vdf_is_mega_menu_option = get_option('videofly_general');
if( !isset($vdf_is_mega_menu_option['enable_mega_menu'] ) ){
    $vdf_is_mega_menu_option['enable_mega_menu'] = 'N';
}

if( $vdf_is_mega_menu_option['enable_mega_menu'] == 'Y' ) {

    add_theme_support( 'ts_is_mega_menu' );

}

$update_options = get_option('videofly_theme_update');
if( isset($update_options['update_options']) ){

    load_template( trailingslashit( get_template_directory() ) . 'includes/envato-wp-theme-updater.php' );
    new Envato_WP_Theme_Updater( $update_options['update_options']['user_name'], $update_options['update_options']['key_api'], 'upcode' );

}

function vdf_set_post_views($postID) {

    $count_key = 'ts_article_views';
    $count = get_post_meta($postID, $count_key, true);

    if ($count=='') {

        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');

    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

function vdf_track_post_views ($post_id) {

    if ( !is_single() ) return;

    if ( empty($post_id) ) {

        global $post;
        $post_id = $post->ID;

    }
    vdf_set_post_views($post_id);
}
add_action( 'wp_head', 'vdf_track_post_views');

function vdf_get_views($postID, $before, $after, $show = true){

    $options = get_option('videofly_single_post');
    $views = isset($options['views']) ? $options['views'] : 'N';

    if ( $views == 'N' ) return;

    $count = get_post_meta($postID, 'ts_article_views', true);
    if( $count == '' ){
        vdf_set_post_views($postID);
        $count = 0;
    }
    if( $show == true ){
        echo vdf_var_sanitize($before . $count . $after);
    }else{
        return $before . $count . $after;
    }
}

// ADD NEW COLUMN ts_pricing_table
function vdf_add_custom_true($columns) {

    $postType = get_post_type(get_the_ID());

    if( $postType == 'video' || $postType == 'ts-gallery' || $postType == 'post' || $postType == 'event' ){
        $columns['featured_article'] = 'Featured';
    }

    return $columns;
}
add_filter('manage_posts_columns', 'vdf_add_custom_true', 10, 1);

// show the colums featured
function vdf_columns_content_featured($columnName, $post_ID) {

    $postType = get_post_type($post_ID);

    if( $postType == 'video' || $postType == 'ts-gallery' || $postType == 'post' || $postType == 'event' && $columnName == 'featured_article' ){

        $meta_values = get_post_meta($post_ID, 'featured', true);
        $selected = $meta_values == 'yes' ? 'checked' : '';

        echo '<input type="checkbox"'. $selected .' name="featured_article" class="featured" value="'. $post_ID .'">';
        echo '<input type="hidden" value="updateFeatures" />';

    }
}
add_action('manage_posts_custom_column', 'vdf_columns_content_featured', 10, 2);

//get the pagination in single item
function vdf_get_pagination_next_previous(){

    $enable_pagination = get_option('videofly_single_post', array('post_pagination' => 'Y'));

    if( isset($enable_pagination['post_pagination']) && $enable_pagination['post_pagination'] == 'Y' ){

        $next_post = get_next_post();

        $prev_post = get_previous_post();
        ?><div class="container post-navigation">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <nav class="ts-post-nav prev">
                        <?php if( !empty($prev_post) ) :  ?>
                            <a class="prev" href="<?php echo get_permalink($prev_post->ID, false); ?>">
                                <div class="image-holder">
                                   <?php
                                        $src = wp_get_attachment_url( get_post_thumbnail_id( $prev_post->ID ) );
                                        $img_url = aq_resize($src, 350, 95, true, true);
                                    ?>
                                    <img src="<?php echo esc_url( $img_url ); ?>" alt="" />
                                </div>
                                <div class="inner-content">
                                    <div class="post-nav-content">
                                        <div class="content-wrapper">
                                            <span><?php esc_html_e('Previous post', 'videofly'); ?></span>
                                            <h4 class="title"><i class="icon-left-arrow"></i><?php echo vdf_var_sanitize($prev_post->post_title); ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php endif; ?>
                        </nav>
                    </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <nav class="ts-post-nav next">
                        <?php if( !empty($next_post) ) : ?>
                            <a class="next" href="<?php echo get_permalink($next_post->ID, false); ?>">
                                <div class="image-holder">
                                   <?php
                                        $src = wp_get_attachment_url( get_post_thumbnail_id( $next_post->ID ) );
                                        $img_url = aq_resize($src, 350, 95, true, true);
                                    ?>
                                    <img src="<?php echo esc_url( $img_url ); ?>" alt="" />
                                </div>
                                <div class="inner-content">
                                    <div class="post-nav-content">
                                        <div class="content-wrapper">
                                            <span><?php esc_html_e('Next Post','videofly') ?></span>
                                            <h4 class="title"><?php echo vdf_var_sanitize($next_post->post_title); ?><i class="icon-right-arrow"></i></h4>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php endif; ?>
                    </nav>
                </div>
            </div>
            </div>
        <?php
    }//end if(verify enable_pagination )
}//end function vdf_get_pagination_next_previous()

function vdf_breadcrumbs(){

    global $post;

    if ( is_front_page() ) {
        return;
    }

    if ( is_category() || is_single() || is_page() ) {

        $breadcrumbs =  '<div class="ts-breadcrumbs-content">
        <a href="' . esc_url( home_url('/') ) . '">' . esc_html__('Home', 'videofly') . '</a>';

        if (is_category() || is_single() ) {

            $breadcrumbs .= ' <i class="icon-right-arrow"></i> ';

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
                $breadcrumbs .= ' <i class="icon-right-arrow"></i> ';
                break;
            }
            if (is_single()) {
                $breadcrumbs .= sanitize_text_field( get_the_title($post->ID) );
            }
            $breadcrumbs .= '</div>';

            return $breadcrumbs;

        } elseif (is_page()) {

            if($post->post_parent){
                $anc = get_post_ancestors($post->ID);
                $anc_link = get_page_link($post->post_parent);

                foreach ($anc as $ancestor) {
                    $breadcrumbs .= " <i class='icon-right-arrow'></i> <a href=" . $anc_link . ">" . get_the_title($ancestor) . "</a> <i class='icon-right-arrow'></i> ";
                }

                $breadcrumbs .= sanitize_text_field( get_the_title($post->ID) );
                $breadcrumbs .= '</div>';
                return $breadcrumbs;

            } else {
                $breadcrumbs .= ' <i class="icon-right-arrow"></i> ' . sanitize_text_field ( get_the_title($post->ID) );
                $breadcrumbs .= '</div>';
                return $breadcrumbs;
            }
        }
    }elseif( is_tag() ){
        $breadcrumbs = '<div class="ts-breadcrumbs"><a href="' . esc_url( home_url('/') ) . '">' . esc_html__('Home', 'videofly') . '</a> > ' . sanitize_text_field( single_tag_title('', false) ) . '</div>';
        return $breadcrumbs;
    }elseif( is_day() ){
        $breadcrumbs = '<div class="ts-breadcrumbs"><a href="' . esc_url( home_url('/') ) . '">' . esc_html__('Home', 'videofly') . '</a> > ' . esc_html__('Archive: ', 'videofly') . get_the_date('F jS, Y') . '</div>';
        return $breadcrumbs;
    }elseif( is_month() ){
        $breadcrumbs = '<div class="ts-breadcrumbs"><a href="' . esc_url( home_url('/') ) . '">' . esc_html__('Home', 'videofly') . '</a> > ' . esc_html__('Archive: ', 'videofly') . get_the_date('F, Y') . '</div>';
        return $breadcrumbs;
    }elseif( is_year() ){
        $breadcrumbs = '<div class="ts-breadcrumbs"><a href="' . esc_url( home_url('/') ) . '">' . esc_html__('Home', 'videofly') . '</a> > ' . esc_html__('Archive: ', 'videofly') . get_the_date('Y') . '</div>';
        return $breadcrumbs;
    }elseif( is_author() ){
        $breadcrumbs = '<div class="ts-breadcrumbs"><a href="' . esc_url( home_url('/') ) . '">' . esc_html__('Home', 'videofly') . '</a> > ' . esc_html__('Author\'s archive: ', 'videofly') . get_the_author() . '</div>';
        return $breadcrumbs;
    }elseif( isset($_GET['paged']) && !empty($_GET['paged']) ){
        $breadcrumbs = '<div class="ts-breadcrumbs"><a href="' . esc_url( home_url('/') ) . '">' . esc_html__('Home', 'videofly') . '</a> > ' . esc_html__('Blogarchive: ', 'videofly') . '</div>';
        return $breadcrumbs;
    }elseif( is_search() ){
        $breadcrumbs = '<div class="ts-breadcrumbs"><a href="' . esc_url( home_url('/') ) . '">' . esc_html__('Home', 'videofly') . '</a> > ' . esc_html__('Search results: ', 'videofly') . '</div>';
        return $breadcrumbs;
    }

}

function vdf_get_comment_count($post_id) {
    if (fields::get_options_value('videofly_general', 'comment_system') == 'facebook' ) {
        return '<fb:comments-count href="' . get_permalink($post_id) .'"></fb:comments-count>';
    } else{
        return get_comments_number($post_id);
    }
}

function vdf_draw_calendar_callback($month_layout = NULL, $year_layout = NULL, $size_layout = NULL, $nonce_layout = NULL){

    if( isset($_POST['nonce']) && !wp_verify_nonce($_POST['nonce'], 'security') ){
        return;
    }

    if( isset($nonce_layout) && !wp_verify_nonce($nonce_layout, 'security') ){
        return;
    }

    if(isset($_POST['tsYear'], $_POST['tsMonth'])){
        $month = (int)$_POST['tsMonth'];
        if( strlen($month) == 1 ) $month = '0' . $month;
        $year = (int)$_POST['tsYear'];
        $class_size = (isset($_POST['size']) && ($_POST['size'] == 'ts-big-calendar' || $_POST['size'] == 'ts-small-calendar')) ? $_POST['size'] : 'ts-big-calendar';
    }

    if( isset($month_layout, $year_layout, $size_layout) ){
        $month = $month_layout;
        $year = $year_layout;
        $class_size = $size_layout;
    }

    $month_prev = ($month == 1) ? 12 : $month - 1;
    $month_next = ($month == 12) ? 1 : $month + 1;
    $year_next = ($month == 12) ? $year + 1 : $year;
    $year_prev = ($month == 1) ? $year - 1 : $year;
    if( strlen($month_prev) == 1 ) $month_prev = '0' . $month_prev;
    if( strlen($month_next) == 1 ) $month_next = '0' . $month_next;
    $days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));

    $calendar = '<h3 class="ts-calendar-title">'. date('F', mktime(0, 0, 0, $month, 10)) . '<span> ' . $year . '</span>' . '</h3>';
    $calendar .= '<a class="ts-get-calendar ts-prev" data-month="' . $month_prev . '" data-year="' . $year_prev . '" href="#">' . esc_html__('Prev month', 'videofly') . '</a><a class="ts-get-calendar ts-next" data-month="' . $month_next . '" data-year="' . $year_next . '" href="#">' . esc_html__('Next month', 'videofly') . '</a>';
    $calendar .= '<table cellpadding="0" cellspacing="0" class="ts-events-calendar ' . $class_size . '">';

    $headings = array(esc_html__('Sunday', 'videofly'), esc_html__('Monday', 'videofly'), esc_html__('Tuesday', 'videofly'), esc_html__('Wednesday', 'videofly'), esc_html__('Thursday', 'videofly'), esc_html__('Friday', 'videofly'), esc_html__('Saturday', 'videofly'));

    $calendar .= '<tr class="calendar-row"><td class="calendar-day-head">' . implode('</td><td class="calendar-day-head">', $headings) . '</td></tr>';

    $events = array();
    $args = array(
        'post_type'      => 'event',
        'posts_per_page' => -1,
        );

    $query = new WP_Query( $args );

    if( $query->have_posts() ){
        while ( $query->have_posts() ) { $query->the_post();

            $day = get_post_meta(get_the_ID(), 'day', true);
            $day = (isset($day) && (int)$day !== 0) ? date('Y-m-d', (int)$day) : NULL;

            if( isset($day) ){

                $permalink = get_permalink(get_the_ID());
                $title     = get_the_title(get_the_ID());
                $excerpt   = get_the_excerpt();

                $post_meta = get_post_meta(get_the_ID(), 'event', true);
                $start_end = (isset($post_meta['start-time'], $post_meta['end-time'])) ? $post_meta['start-time'] . ' - ' . $post_meta['end-time'] : '';
                $repeat    = (isset($post_meta['event-enable-repeat']) && ($post_meta['event-enable-repeat'] == 'y' || $post_meta['event-enable-repeat'] == 'n')) ? $post_meta['event-enable-repeat'] : '';
                $repeat_in = (isset($post_meta['event-repeat']) && ($post_meta['event-repeat'] == '1' || $post_meta['event-repeat'] == '2' || $post_meta['event-repeat'] == '3') ) ? $post_meta['event-repeat'] : '';
                $end_day   = (isset($post_meta['event-end']) &&  (int)strtotime($post_meta['event-end']) !== 0) ? $post_meta['event-end'] : '';

                $date_start = date_create($day);
                $date_end   = date_create($end_day);
                $event_days = date_diff($date_start, $date_end);
                $event_days = $event_days->days + 1;
                $days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));

                if ( $post_meta['event-enable-repeat'] == 'n' ) {
                    for ($i = 0; $i < 3; $i++) {
                        for ($k = 0; $k < $event_days; $k++){
                            if( isset($events[date('Y-m-d', strtotime($day) + ($k * 86400))]) ){
                                if( !in_array( array('title' => $title, 'permalink' => $permalink, 'excerpt' => $excerpt, 'start-end' => $start_end, 'repeat' => $repeat, 'repeat_in' => $repeat_in, 'event-end' => $end_day), $events[date('Y-m-d', strtotime($day) + ($k*86400))] ) ){

                                    array_push($events[date('Y-m-d', strtotime($day) + ($k*86400))], array(
                                        'title'     => $title,
                                        'permalink' => $permalink,
                                        'excerpt'   => $excerpt,
                                        'start-end' => $start_end,
                                        'repeat'    => $repeat,
                                        'repeat_in' => $repeat_in,
                                        'event-end' => $end_day
                                        ));
                                }
                            }else{
                                $events[date('Y-m-d', strtotime($day) + ($k*86400) )] = array(array(
                                    'title'     => $title,
                                    'permalink' => $permalink,
                                    'excerpt'   => $excerpt,
                                    'start-end' => $start_end,
                                    'repeat'    => $repeat,
                                    'repeat_in' => $repeat_in,
                                    'event-end' => $end_day
                                    ));
                            }
                        }
                    }
                }
                if ( $post_meta['event-enable-repeat'] == 'y' && $post_meta['event-repeat'] == '1' ) {
                    for ($i=0; $i < 500; $i++) {
                        if( isset($events[date('Y-m-d', strtotime($day) + 86400 * 7 * $i)]) ){
                            if( !in_array(array('title' => $title, 'permalink' => $permalink, 'excerpt' => $excerpt, 'start-end' => $start_end, 'repeat' => $repeat, 'repeat_in' => $repeat_in, 'event-end' => $end_day), $events[date('Y-m-d', strtotime($day) + 86400 * 7 * $i)]) ){
                                array_push($events[date('Y-m-d', strtotime($day) + 86400 * 7 * $i)], array(
                                    'title'     => $title,
                                    'permalink' => $permalink,
                                    'excerpt'   => $excerpt,
                                    'start-end' => $start_end,
                                    'repeat'    => $repeat,
                                    'repeat_in' => $repeat_in,
                                    'event-end' => $end_day
                                    ));
                            }
                        }else{
                            $events[date('Y-m-d', strtotime($day) + 86400 * 7 * $i)] = array(array(
                                'title'     => $title,
                                'permalink' => $permalink,
                                'excerpt'   => $excerpt,
                                'start-end' => $start_end,
                                'repeat'    => $repeat,
                                'repeat_in' => $repeat_in,
                                'event-end' => $end_day
                                ));
                        }
                        for ($k=1; $k < $event_days; $k++) {
                            if( isset($events[date('Y-m-d', strtotime($day) + (86400 * 7)* $i + ($k*86400) )]) ){
                                if( !in_array( array('title' => $title, 'permalink' => $permalink, 'excerpt' => $excerpt, 'start-end' => $start_end, 'repeat' => $repeat, 'repeat_in' => $repeat_in, 'event-end' => $end_day), $events[date('Y-m-d', strtotime($day) + (86400 * 7)* $i + ($k*86400) )]) ){
                                    array_push($events[date('Y-m-d', strtotime($day) + (86400 * 7)* $i + ($k*86400) )], array(
                                        'title'     => $title,
                                        'permalink' => $permalink,
                                        'excerpt'   => $excerpt,
                                        'start-end' => $start_end,
                                        'repeat'    => $repeat,
                                        'repeat_in' => $repeat_in,
                                        'event-end' => $end_day
                                        ));
                                }
                            }else{
                                $events[date('Y-m-d', strtotime($day) + (86400 * 7)* $i + ($k*86400) )] = array(array(
                                    'title'     => $title,
                                    'permalink' => $permalink,
                                    'excerpt'   => $excerpt,
                                    'start-end' => $start_end,
                                    'repeat'    => $repeat,
                                    'repeat_in' => $repeat_in,
                                    'event-end' => $end_day
                                    ));
                            }
                        }
                    }
                }
                if ( $post_meta['event-enable-repeat'] == 'y' && $post_meta['event-repeat'] == '2' ) {
                    for ($i=0; $i < 500; $i++) {
                        if( isset($events[date('Y-m-d', strtotime("+".$i." month",strtotime($day)))]) ){
                            if( !in_array( array('title' => $title, 'permalink' => $permalink, 'excerpt' => $excerpt, 'start-end' => $start_end, 'repeat' => $repeat, 'repeat_in' => $repeat_in, 'event-end' => $end_day), $events[date('Y-m-d', strtotime("+".$i." month",strtotime($day)))]) ){
                                array_push($events[date('Y-m-d', strtotime("+".$i." month",strtotime($day)))], array(
                                    'title'     => $title,
                                    'permalink' => $permalink,
                                    'excerpt'   => $excerpt,
                                    'start-end' => $start_end,
                                    'repeat'    => $repeat,
                                    'repeat_in' => $repeat_in,
                                    'event-end' => $end_day
                                    ));
                            }
                        }else{
                            $events[date('Y-m-d', strtotime("+".$i." month",strtotime($day)))] = array(array(
                                'title'     => $title,
                                'permalink' => $permalink,
                                'excerpt'   => $excerpt,
                                'start-end' => $start_end,
                                'repeat'    => $repeat,
                                'repeat_in' => $repeat_in,
                                'event-end' => $end_day
                                ));
                        }
                        for ($k=1; $k < $event_days; $k++) {
                            $current_date = date('Y-m-d', strtotime("+".$i." month",strtotime($day)));
                            if( isset($events[date('Y-m-d', strtotime("+".$k." days",strtotime($current_date )))]) ){
                                if( !in_array( array('title' => $title, 'permalink' => $permalink, 'excerpt' => $excerpt, 'start-end' => $start_end, 'repeat' => $repeat, 'repeat_in' => $repeat_in, 'event-end' => $end_day), $events[date('Y-m-d', strtotime("+".$k." days",strtotime($current_date )))]) ){
                                    array_push($events[date('Y-m-d', strtotime("+".$k." days",strtotime($current_date )))], array(
                                        'title'     => $title,
                                        'permalink' => $permalink,
                                        'excerpt'   => $excerpt,
                                        'start-end' => $start_end,
                                        'repeat'    => $repeat,
                                        'repeat_in' => $repeat_in,
                                        'event-end' => $end_day
                                        ));
                                }
                            }else{
                                $events[date('Y-m-d', strtotime("+".$k." days",strtotime($current_date )))] = array(array(
                                    'title'     => $title,
                                    'permalink' => $permalink,
                                    'excerpt'   => $excerpt,
                                    'start-end' => $start_end,
                                    'repeat'    => $repeat,
                                    'repeat_in' => $repeat_in,
                                    'event-end' => $end_day
                                    ));
                            }
                        }
                    }
                }
                if ( $post_meta['event-enable-repeat'] == 'y' && $post_meta['event-repeat'] == '3' ) {
                    for ($i=0; $i < 500; $i++) {
                        if( isset($events[date('Y-m-d', strtotime("+".$i." year",strtotime($day)))]) ){
                            if( !in_array( array('title' => $title, 'permalink' => $permalink, 'excerpt' => $excerpt, 'start-end' => $start_end, 'repeat' => $repeat, 'repeat_in' => $repeat_in, 'event-end' => $end_day), $events[date('Y-m-d', strtotime("+".$i." year",strtotime($day)))]) ){
                                array_push($events[date('Y-m-d', strtotime("+".$i." year",strtotime($day)))], array(
                                    'title'     => $title,
                                    'permalink' => $permalink,
                                    'excerpt'   => $excerpt,
                                    'start-end' => $start_end,
                                    'repeat'    => $repeat,
                                    'repeat_in' => $repeat_in,
                                    'event-end' => $end_day
                                    ));
                            }
                        }else{
                            $events[date('Y-m-d', strtotime("+".$i." year",strtotime($day)))] = array(array(
                                'title'     => $title,
                                'permalink' => $permalink,
                                'excerpt'   => $excerpt,
                                'start-end' => $start_end,
                                'repeat'    => $repeat,
                                'repeat_in' => $repeat_in,
                                'event-end' => $end_day
                                ));
                        }
                        for ($k=1; $k < $event_days; $k++) {
                            $current_date = date('Y-m-d', strtotime("+".$i." year",strtotime($day)));
                            if( isset($events[date('Y-m-d', strtotime("+".$k." days",strtotime($current_date )))]) ){
                                if( !in_array( array('title' => $title, 'permalink' => $permalink, 'excerpt' => $excerpt, 'start-end' => $start_end, 'repeat' => $repeat, 'repeat_in' => $repeat_in, 'event-end' => $end_day), $events[date('Y-m-d', strtotime("+".$k." days",strtotime($current_date )))]) ){
                                    array_push($events[date('Y-m-d', strtotime("+".$k." days",strtotime($current_date )))], array(
                                        'title'     => $title,
                                        'permalink' => $permalink,
                                        'excerpt'   => $excerpt,
                                        'start-end' => $start_end,
                                        'repeat'    => $repeat,
                                        'repeat_in' => $repeat_in,
                                        'event-end' => $end_day
                                        ));
                                }
                            }else{
                                $events[date('Y-m-d', strtotime("+".$k." days",strtotime($current_date )))] = array(array(
                                    'title'     => $title,
                                    'permalink' => $permalink,
                                    'excerpt'   => $excerpt,
                                    'start-end' => $start_end,
                                    'repeat'    => $repeat,
                                    'repeat_in' => $repeat_in,
                                    'event-end' => $end_day
                                    ));
                            }
                        }
                    }
                }

                if( $repeat == 'y' && $repeat_in == '1' ){
                    $day_next = strtotime($day);

                    for($i = 0; $i < $event_days; $i++){
                        $start_day = date('Y-m-d', $day_next + (86400 * $i));

                        if( isset($events[$start_day]) ){
                            if( in_array(array('title' => $title, 'permalink' => $permalink, 'excerpt' => $excerpt, 'start-end' => $start_end, 'repeat' => $repeat, 'repeat_in' => $repeat_in, 'event-end' => $end_day), $events[$start_day]) ){

                                array_push($events[$start_day], array(
                                    'title'     => $title,
                                    'permalink' => $permalink,
                                    'excerpt'   => $excerpt,
                                    'start-end' => $start_end,
                                    'repeat'    => $repeat,
                                    'repeat_in' => $repeat_in,
                                    'event-end' => $end_day
                                    )
                                );
                            }

                        }else{
                            $events[$start_day] = array(array(
                                'title'     => $title,
                                'permalink' => $permalink,
                                'excerpt'   => $excerpt,
                                'start-end' => $start_end,
                                'repeat'    => $repeat,
                                'repeat_in' => $repeat_in,
                                'event-end' => $end_day
                                )
                            );
                        }
                    }
                }
            }
        }
    }

    $running_day = date('w', mktime(0, 0, 0, $month, 1, $year));
    $days_in_this_week = 1;
    $day_counter = 0;
    $dates_array = array();

    $calendar .= '<tr class="ts-calendar-row">';

    for($x = 0; $x < $running_day; $x++):
        $calendar .= '<td class="ts-calendar-day-np">&nbsp;</td>';
    $days_in_this_week++;
    endfor;

    for($list_day = 1; $list_day <= $days_in_month; $list_day++) :
        $calendar .= '<td class="ts-calendar-day">';

    $calendar .= '<div class="ts-day-number">' . $list_day . '</div>';
    if ( strlen($list_day) == 1 ) $list_day = '0'.$list_day;
    $event_day = $year . '-' . $month . '-' . $list_day;

    if(isset($events[$event_day])) {
        foreach($events[$event_day] as $event) {
            $calendar .= '<div class="ts-event-title"><a href="'.$event['permalink'].'">' . $event['title'] . '</a>';
            $calendar .= '<div class="ts-event-details-hover"><div class="ts-event-time">'.$event['start-end'].'</div>';
            $calendar .= '<div class="ts-event-excerpt">'.$event['excerpt'].'</div></div></div>';
            if ( $event['repeat'] == 'y' && $event['repeat_in'] == '1' ) {
                $events[date('Y-m-d', strtotime($event_day) + 86400 * 7)] = array($event);
            }
            if ( $event['repeat'] == 'y' && $event['repeat_in'] == '2' ) {
                $events[date('Y-m-d', strtotime($event_day) + 86400 * $days_in_month)] = array($event);
            }
            if ( $event['repeat'] == 'y' && $event['repeat_in'] == '3' ) {
                $events[date('Y-m-d', strtotime($event_day) + 86400 * date("z", mktime(0,0,0,12,31,$year)) + 1)] = array($event);
            }
        }
    }
    else {
        $calendar .= str_repeat('', 2);
    }
    $calendar.= '</td>';
    if($running_day == 6):
        $calendar.= '</tr>';
    if(($day_counter + 1) != $days_in_month):
        $calendar.= '<tr class="calendar-row">';
    endif;
    $running_day = -1;
    $days_in_this_week = 0;
    endif;
    $days_in_this_week++; $running_day++; $day_counter++;
    endfor;

    if($days_in_this_week < 8):
        for($x = 1; $x <= (8 - $days_in_this_week); $x++):
            $calendar.= '<td class="calendar-day-np">&nbsp;</td>';
        endfor;
        endif;

        $calendar.= '</tr>';

        $calendar.= '</table>';

        $calendar = str_replace('</td>','</td>'."\n",$calendar);
        $calendar = str_replace('</tr>','</tr>'."\n",$calendar);

        if(isset($_POST['tsYear'], $_POST['tsMonth'])){
            echo vdf_var_sanitize($calendar);
        }else{
            return $calendar;
        }

        die();
    }
    add_action( 'wp_ajax_ts_draw_calendar', 'vdf_draw_calendar_callback' );
    add_action( 'wp_ajax_nopriv_ts_draw_calendar', 'vdf_draw_calendar_callback' );

    function ts_attachment_field_url( $form_fields, $post ) {

        $form_fields['ts-image-url'] = array(
            'label' => 'Image url',
            'input' => 'text',
            'value' => get_post_meta($post->ID, 'ts-image-url', true),
            'helps' => '',
            );

        return $form_fields;
    }
    add_filter( 'attachment_fields_to_edit', 'ts_attachment_field_url', 10, 2 );

    function ts_attachment_field_url_save( $post, $attachment ) {
        if( isset( $attachment['ts-image-url'] ) )
            update_post_meta($post['ID'], 'ts-image-url', $attachment['ts-image-url']);
        return $post;
    }

    add_filter('attachment_fields_to_save', 'ts_attachment_field_url_save', 10, 2);

    add_filter( 'wp_title', 'vdf_filter_wp_title' );
/**
 * Filters the page title appropriately depending on the current page
 *
 * This function is attached to the 'wp_title' fiilter hook.
 *
 * @uses    get_bloginfo()
 * @uses    is_home()
 * @uses    is_front_page()
 */
function vdf_filter_wp_title( $title ) {
    global $page, $paged;

    if ( is_feed() )
        return $title;

    $site_description = get_bloginfo( 'description' );

    $filtered_title = (is_singular() && !is_front_page()) ? $title . ' | ' . get_bloginfo( 'name' ) : '';
    $filtered_title .= (!empty($site_description) && (is_home() || is_front_page())) ? get_bloginfo( 'name' ) . ' | ' . $site_description : ' ';
    $filtered_title .= ( 2 <= $paged || 2 <= $page ) ? ' | ' . sprintf( esc_html__( 'Page %s', 'videofly' ), max( $paged, $page ) ) : '';

    return $filtered_title;
}

function vdf_get_rating($post_id){

    if( is_numeric($post_id) ){
        $rating_items = get_post_meta($post_id, 'ts_post_rating', TRUE);
        if( isset($rating_items) && is_array($rating_items) && !empty($rating_items) ){
            $total = '';
            foreach($rating_items as $rating){
                $total += $rating['rating_score'];
            }
            if( $total > 0 ){
                $round = intval($total) / count($rating_items);
                $result = round($round, 1);

                if( is_int($round) ){
                    if( $round == 10 ) return $result;
                    else return $result . '.0';
                }else{
                    return $result;
                }
            }else{
                return;
            }
        }
    }else{
        return;
    }

}

function vdf_var_sanitize($content, $method = 'true'){

    switch ($method) {

        case 'sanitize_title':
        return sanitize_title($content);
        break;

        case 'sanitize_text':
        return sanitize_text_field($content);
        break;

        case 'sanitize_html_class':
        return sanitize_html_class($content);
        break;

        case 'balanceTags':
        return balanceTags($content, true);
        break;

        case 'esc_attr':
        return esc_attr($content, true);
        break;

        case 'esc_url':
        return esc_url($content);
        break;

        case 'esc_js':
        return esc_js($content);
        break;

        case 'true':
        return $content;
        break;

        case 'esc_url_raw':
        return esc_url_raw($content);
        break;

        case 'absint':
        return absint($content);
        break;

        case 'esc_textarea':
        return esc_url_raw($content);
        break;
    }

}

function vdf_excerpt($optionLength, $postId, $showSubtitle = 'show-subtitle'){

    if( is_string($optionLength) && !is_numeric($optionLength) ){
        $ln = fields::get_options_value('videofly_general', $optionLength);
    }else{
        $ln = absint($optionLength);
    }

    $subtitle = get_post_meta($postId, 'post_settings', true);
    $subtitle = (isset($subtitle['subtitle']) && $subtitle['subtitle'] !== '' && is_string($subtitle['subtitle'])) ? esc_attr($subtitle['subtitle']) : '';

    if( $showSubtitle == 'show-subtitle' && isset($subtitle) && !empty($subtitle) ){

        if( !empty($subtitle) && strlen(strip_tags(strip_shortcodes($subtitle))) > intval($ln) ){
            echo mb_substr(strip_tags(strip_shortcodes($subtitle)), 0, intval($ln)) . '...';
        }else{
            echo strip_tags(strip_shortcodes($subtitle));
        }

    }else{
        $postExcerpt = get_post_field('post_excerpt', $postId);
        $postContent = get_post_field('post_content', $postId);

        if (!empty($postExcerpt)) {
            if (strlen(strip_tags(strip_shortcodes($postExcerpt))) > intval($ln)) {
                echo mb_substr(strip_tags(strip_shortcodes($postExcerpt)), 0, intval($ln)) . '...';
            } else {
                echo strip_tags(strip_shortcodes($postExcerpt));
            }
        } else {
            if (strlen(strip_tags(strip_shortcodes($postContent))) > intval($ln)) {
                echo mb_substr(strip_tags(strip_shortcodes($postContent)), 0, intval($ln)) . '...';
            } else {
                echo strip_tags(strip_shortcodes($postContent));
            }
        }
    }
}

function vdfHoverStyle($postId, $postType = 'post'){
    $styles = get_option('videofly_styles');
    $hoverEffect = (isset($styles['style_hover']) && ($styles['style_hover'] == 'style1' || $styles['style_hover'] == 'style2')) ? $styles['style_hover'] : 'style1';
    $sharingOverlay = (isset($styles['sharing_overlay']) && ($styles['sharing_overlay'] == 'Y' || $styles['sharing_overlay'] == 'N')) ? $styles['sharing_overlay'] : 'Y';

    if( $hoverEffect == 'style2' ) : ?>
    <div class="pixdot-effect">
        <?php if ( $postType == 'video' ) : ?>
            <div class="view-video-play">
                <i class="icon-play"></i>
            </div>
        <?php else : ?>
            <a href="<?php echo esc_url(get_permalink($postId)); ?>">
                <span><?php esc_html_e('Read more', 'videofly'); ?></span>
            </a>
        <?php endif; ?>
        <?php if ( $sharingOverlay === 'Y' ) : ?>
            <ul class="sharing-options">
                <li data-social="facebook" data-post-id="<?php echo vdf_var_sanitize($postId); ?>">
                    <a class="icon-facebook" target="_blank" data-tooltip="<?php esc_html_e('share on facebook', 'videofly'); ?>" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url(get_permalink($postId)); ?>"></a>
                </li>
                <li data-social="twitter" data-post-id="<?php echo vdf_var_sanitize($postId); ?>">
                    <a class="icon-twitter" target="_blank" data-tooltip="<?php esc_html_e('share on twitter','videofly'); ?>" href="http://twitter.com/home?status=<?php echo urlencode(esc_attr(get_the_title($postId))); ?>+<?php echo esc_url(get_permalink($postId)); ?>"></a>
                </li>
                <li data-social="gplus" data-post-id="<?php echo vdf_var_sanitize($postId); ?>">
                    <a class="icon-gplus" target="_blank" data-tooltip="<?php esc_html_e('share on g-plus','videofly'); ?>" href="https://plus.google.com/share?url=<?php echo esc_url(get_permalink($postId)); ?>"></a>
                </li>
                <li data-social="pinterest" data-post-id="<?php echo vdf_var_sanitize($postId); ?>">
                    <a class="icon-pinterest" target="_blank" data-tooltip="<?php esc_html_e('share on pinterest','videofly'); ?>" href="http://pinterest.com/pin/create/button/?url=<?php echo esc_url(get_permalink($postId)); ?>&amp;media=<?php if(function_exists('the_post_thumbnail')) echo wp_get_attachment_url(get_post_thumbnail_id($postId)); ?>&amp;description=<?php echo urlencode(esc_attr(get_the_title($postId))); ?>" ></a>
                </li>
            </ul>
        <?php endif; ?>
    </div>
<?php endif; // style 1 ?>
<?php if( $hoverEffect == 'style1' ) : ?>
    <div class="rocky-effect">
        <a href="<?php echo esc_url(get_permalink($postId)); ?>"></a>
        <div class="entry-overlay">
            <a href="<?php echo esc_url(get_permalink($postId)); ?>"></a>
            <?php if ( $postType == 'video' ) : ?>
                <div class="read-more">
                    <div class="view-video-play">
                         <a href="<?php echo esc_url(get_permalink($postId)); ?>"><i class="icon-play"></i></a>
                    </div>
                </div>
            <?php else : ?>
                <div class="read-more">
                    <div class="view-video-play">
                         <a href="<?php echo esc_url(get_permalink($postId)); ?>"><i class="icon-zoom-in"></i></a>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ( $sharingOverlay === 'Y' ) : ?>
                <div class="overlay-sharing">
                    <ul class="sharing-options">
                        <li data-social="facebook" data-post-id="<?php echo vdf_var_sanitize($postId); ?>">
                            <a class="icon-facebook" target="_blank" data-tooltip="<?php esc_html_e('share on facebook', 'videofly'); ?>" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url(get_permalink($postId)); ?>"></a>
                        </li>
                        <li data-social="twitter" data-post-id="<?php echo vdf_var_sanitize($postId); ?>">
                            <a class="icon-twitter" target="_blank" data-tooltip="<?php esc_html_e('share on twitter','videofly'); ?>" href="http://twitter.com/home?status=<?php echo urlencode(esc_attr(get_the_title($postId))); ?>+<?php echo esc_url(get_permalink($postId)); ?>"></a>
                        </li>
                        <li data-social="gplus" data-post-id="<?php echo vdf_var_sanitize($postId); ?>">
                            <a class="icon-gplus" target="_blank" data-tooltip="<?php esc_html_e('share on g-plus','videofly'); ?>" href="https://plus.google.com/share?url=<?php echo esc_url(get_permalink($postId)); ?>"></a>
                        </li>
                        <li data-social="pinterest" data-post-id="<?php echo vdf_var_sanitize($postId); ?>">
                            <a class="icon-pinterest" target="_blank" data-tooltip="<?php esc_html_e('share on pinterest','videofly'); ?>" href="http://pinterest.com/pin/create/button/?url=<?php echo esc_url(get_permalink($postId)); ?>&amp;media=<?php if(function_exists('the_post_thumbnail')) echo wp_get_attachment_url(get_post_thumbnail_id($postId)); ?>&amp;description=<?php echo urlencode(esc_attr(get_the_title($postId))); ?>" ></a>
                        </li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
<?php
} // end function vdfHoverStyle

function vdfPasswordPost() {
    global $post;
    $formPassword = '<div class="row">
    <div class="col-xs-12 col-md-6 col-md-offset-3">
        <form class="protected-post-form text-center" action="' . esc_url(site_url('wp-login.php?action=postpass', 'login_post')) . '" method="post">
            <p class="lead protected-message">' . esc_html__('Enter the password below, to view this protected post', 'videofly') . '</p>
            <div class="form-group">
                <input type="password" name="post_password" class="form-control" id="ts-password-post" placeholder="' . esc_html__( 'Enter password', 'videofly' ) . '">
            </div>
            <input class="btn medium" type="submit" name="Submit" value="' . esc_html__('Submit', 'videofly') . '" />
        </form>
    </div>
</div>';

return $formPassword;
}
add_filter('the_password_form', 'vdfPasswordPost');


add_action( 'tgmpa_register', 'vdf_register_required_plugins' );
function vdf_register_required_plugins() {

    $plugins = array(

        // Include a required plugin.
        array(
            'name'               => 'Touchsize Custom Posts',// The plugin name.
            'slug'               => 'ts-custom-posts',// The plugin slug (typically the folder name).
            'source'             => get_template_directory_uri() . '/required-plugins/ts-custom-posts.zip', // The plugin source.
            'required'           => true, // If false, the plugin is only 'recommended' instead of required.
            'version'            => '1.0.5.', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
            'force_activation'   => true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            'external_url'       => '', // If set, overrides default API URL and points to an external URL.
        ),

        array(
            'name'               => 'BuddyPress Follow',// The plugin name.
            'slug'               => 'buddypress-followers',// The plugin slug (typically the folder name).
            'source'             => get_template_directory_uri() . '/required-plugins/buddypress-followers.zip', // The plugin source.
            'required'           => false, // If false, the plugin is only 'recommended' instead of required.
            'version'            => '1.3', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
            'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            'external_url'       => '', // If set, overrides default API URL and points to an external URL.
        ),

        array(
            'name'      => 'WooCommerce',
            'slug'      => 'woocommerce',
            'required'  => false,
        ),

        array(
            'name'      => 'BuddyPress',
            'slug'      => 'buddypress',
            'required'  => false,
        )
    );

    /*
     * Array of configuration settings. Amend each line as needed.
     *
     * TGMPA will start providing localized text strings soon. If you already have translations of our standard
     * strings available, please help us make TGMPA even better by giving us access to these translations or by
     * sending in a pull-request with .po file(s) with the translations.
     *
     * Only uncomment the strings in the config array if you want to customize the strings.
     *
     * Some of the strings are wrapped in a sprintf(), so see the comments at the
     * end of each line for what each argument will be.
     */
    $config = array(
        'id'           => 'videofly',                  // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                           // Default absolute path to pre-packaged plugins.
        'menu'         => 'videofly-install-plugins',  // Menu slug.
        'parent_slug'  => 'themes.php',                 // Parent menu slug.
        'capability'   => 'edit_theme_options',         // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
        'has_notices'  => true,                         // Show admin notices or not.
        'dismissable'  => true,                         // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                           // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => true,                        // Automatically activate plugins after installation or not.
        'message'      => '',                           // Message to output right before the plugins table.
        'strings'      => array(
            'page_title'                      => esc_html__( 'Install Required Plugins', 'videofly' ),
            'menu_title'                      => esc_html__( 'Install Plugins', 'videofly' ),
            'installing'                      => esc_html__( 'Installing Plugin: %s', 'videofly' ), // %s = plugin name.
            'oops'                            => esc_html__( 'Something went wrong with the plugin API.', 'videofly' ),
            'notice_can_install_required'     => _n_noop(
                'This theme requires the following plugin: %1$s.',
                'This theme requires the following plugins: %1$s.',
                'videofly'
            ), // %1$s = plugin name(s).
            'notice_can_install_recommended'  => _n_noop(
                'This theme recommends the following plugin: %1$s.',
                'This theme recommends the following plugins: %1$s.',
                'videofly'
            ), // %1$s = plugin name(s).
            'notice_cannot_install'           => _n_noop(
                'Sorry, but you do not have the correct permissions to install the %1$s plugin.',
                'Sorry, but you do not have the correct permissions to install the %1$s plugins.',
                'videofly'
            ), // %1$s = plugin name(s).
            'notice_ask_to_update'            => _n_noop(
                'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
                'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
                'videofly'
            ), // %1$s = plugin name(s).
            'notice_ask_to_update_maybe'      => _n_noop(
                'There is an update available for: %1$s.',
                'There are updates available for the following plugins: %1$s.',
                'videofly'
            ), // %1$s = plugin name(s).
            'notice_cannot_update'            => _n_noop(
                'Sorry, but you do not have the correct permissions to update the %1$s plugin.',
                'Sorry, but you do not have the correct permissions to update the %1$s plugins.',
                'videofly'
            ), // %1$s = plugin name(s).
            'notice_can_activate_required'    => _n_noop(
                'The following required plugin is currently inactive: %1$s.',
                'The following required plugins are currently inactive: %1$s.',
                'videofly'
            ), // %1$s = plugin name(s).
            'notice_can_activate_recommended' => _n_noop(
                'The following recommended plugin is currently inactive: %1$s.',
                'The following recommended plugins are currently inactive: %1$s.',
                'videofly'
            ), // %1$s = plugin name(s).
            'notice_cannot_activate'          => _n_noop(
                'Sorry, but you do not have the correct permissions to activate the %1$s plugin.',
                'Sorry, but you do not have the correct permissions to activate the %1$s plugins.',
                'videofly'
            ), // %1$s = plugin name(s).
            'install_link'                    => _n_noop(
                'Begin installing plugin',
                'Begin installing plugins',
                'videofly'
                ),
            'update_link'                     => _n_noop(
                'Begin updating plugin',
                'Begin updating plugins',
                'videofly'
                ),
            'activate_link'                   => _n_noop(
                'Begin activating plugin',
                'Begin activating plugins',
                'videofly'
                ),
            'return'                          => esc_html__( 'Return to Required Plugins Installer', 'videofly' ),
            'plugin_activated'                => esc_html__( 'Plugin activated successfully.', 'videofly' ),
            'activated_successfully'          => esc_html__( 'The following plugin was activated successfully:', 'videofly' ),
            'plugin_already_active'           => esc_html__( 'No action taken. Plugin %1$s was already active.', 'videofly' ),  // %1$s = plugin name(s).
            'plugin_needs_higher_version'     => esc_html__( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'videofly' ),  // %1$s = plugin name(s).
            'complete'                        => esc_html__( 'All plugins installed and activated successfully. %1$s', 'videofly' ), // %s = dashboard link.
            'contact_admin'                   => esc_html__( 'Please contact the administrator of this site for help.', 'videofly' ),

            'nag_type'                        => 'updated', // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
            )
);

tgmpa( $plugins, $config );
}

add_action('after_switch_theme', 'vdf_theme_custom_posts');
function vdf_theme_custom_posts(){
    $custom_posts = array('gallery', 'event', 'slider', 'video', 'teams', 'portfolio', 'pricing_table');
    update_option('theme-custom-posts', $custom_posts);
}

function vdfAllAnimations($idSelect, $output = 'effect', $default = 'none', $dataAttr = ''){

    if( $output == 'effect' ){

        $arrOutput = array(
           esc_html__('None', 'videofly') => 'none',
           esc_html__('Bounce In', 'videofly') => 'bounceIn',
           esc_html__('Bounce In Down', 'videofly') => 'bounceInDown',
           esc_html__('Bounce In Right', 'videofly') => 'bounceInRight',
           esc_html__('Bounce In Up', 'videofly') => 'bounceInUp',
           esc_html__('Bounce In Left', 'videofly') => 'bounceInLeft',
           esc_html__('Fade In Down Short', 'videofly') => 'fadeInDownShort',
           esc_html__('Fade In Up Short', 'videofly') => 'fadeInUpShort',
           esc_html__('Fade In Left Short', 'videofly') => 'fadeInLeftShort',
           esc_html__('Fade In Right Short', 'videofly') => 'fadeInRightShort',
           esc_html__('Fade In Down', 'videofly') => 'fadeInDown',
           esc_html__('Fade In Up', 'videofly') => 'fadeInUp',
           esc_html__('Fade In Left', 'videofly') => 'fadeInLeft',
           esc_html__('Fade In Right', 'videofly') => 'fadeInRight',
           esc_html__('Fade In', 'videofly') => 'fadeIn',
           esc_html__('Grow In', 'videofly') => 'growIn',
           esc_html__('Shake', 'videofly') => 'shake',
           esc_html__('Shake Up', 'videofly') => 'shakeUp',
           esc_html__('Rotate In', 'videofly') => 'rotateIn',
           esc_html__('Rotate In Up Left', 'videofly') => 'rotateInUpLeft',
           esc_html__('Rotate In Down Left', 'videofly') => 'rotateInDownLeft',
           esc_html__('Rotate In Up Right', 'videofly') => 'rotateInUpRight',
           esc_html__('Rotate In Down Right', 'videofly') => 'rotateInDownRight',
           esc_html__('Roll In', 'videofly') => 'rollIn',
           esc_html__('Wiggle', 'videofly') => 'wiggle',
           esc_html__('Swing', 'videofly') => 'swing',
           esc_html__('Tada', 'videofly') => 'tada',
           esc_html__('Wobble', 'videofly') => 'wobble',
           esc_html__('Pulse', 'videofly') => 'pulse',
           esc_html__('Light Speed In Right', 'videofly') => 'lightSpeedInRight',
           esc_html__('Light Speed In Left', 'videofly') => 'lightSpeedInLeft',
           esc_html__('Flip', 'videofly') => 'flip',
           esc_html__('Flip In X', 'videofly') => 'flipInX',
           esc_html__('Flip In Y', 'videofly') => 'flipInY'
           );

}else{

    $arrOutput = array(
        '0s'    => 'none',
        '0.25s' => 'delay-250',
        '0.5s'  => 'delay-500',
        '0.75s' => 'delay-750',
        '1.0s'  => 'delay-1000',
        '1.25s' => 'delay-1250',
        '1.5s'  => 'delay-1500',
        '1.75s' => 'delay-1750',
        '2s'    => 'delay-2000',
        '2.5s'  => 'delay-2500',
        '3s'    => 'delay-3000',
        '3.5s'  => 'delay-3500'
        );

}

$attr = $dataAttr == 'showattr' ? ' data-builder-name="'. $output .'"' : '';

$factorySelect = '<select'. $attr .' name="'. $idSelect .'" id="'. $idSelect .'">';
foreach($arrOutput as $text => $value){
    $selected = $value == $default ? ' selected="selected"' : '';
    $factorySelect .= '<option'. $selected .' value="'. $value .'">'. $text .'</option>';
}
$factorySelect .= '</select>';

return $factorySelect;
}

function vdf_import_icon(){

    if( !isset($_POST['ts-svg']) || empty($_POST['ts-svg']) || empty($_POST['ts-eot']) || empty($_POST['ts-ttf']) || empty($_POST['ts-woff']) || empty($_POST['ts-css']) ) return;

    if( $contentCss = wp_remote_get(wp_get_attachment_url($_POST['ts-css'])) ){

        $contentCss = $contentCss['body'];

        $indentificator = uniqid();

        $fontWeight = preg_match("/font-weight:\s*[\'\"A-z0-9]+;/", $contentCss, $matches);
        $fontWeight = isset($matches[0]) ? explode(':', $matches[0]) : '';
        $fontWeight = is_array($fontWeight) ? trim($fontWeight[1]) : 'normal;';

        $fontStyle = preg_match("/font-style:\s*[\'\"A-z0-9]+;/", $contentCss, $matches);
        $fontStyle = isset($matches[0]) ? explode(':', $matches[0]) : '';
        $fontStyle = is_array($fontStyle) ? trim($fontStyle[1]) : 'normal;';

        $contentCss = preg_replace("/\@font-face\s*\{[\n\s\S]*?\}/", '', $contentCss);// remove all font-face

        $contentCss = preg_replace("/font-family:\s*[\'\"A-z0-9]+;/i", 'font-family: ts-family-'. $indentificator .';', $contentCss);

        $contentCss = preg_replace("/icon/", 'ts-icon-'. $indentificator, $contentCss);

        preg_match_all('/((?=\.)*ts-icon-'. $indentificator .'-)\w+.*(?=:before)/', $contentCss, $classes);

        $classes = implode(',', $classes[0]);
        update_option('test-icon', $contentCss);
        $customIcon = array(
            'ids' => array(
                'svg'  => $_POST['ts-svg'],
                'eot'  => $_POST['ts-eot'],
                'css'  => $_POST['ts-css'],
                'woff' => $_POST['ts-woff'],
                'ttf'  => $_POST['ts-ttf']
                ),
            'classes'     => $classes,
            'css'         => str_replace('\\', '\\\\', $contentCss),
            'font-weight' => $fontWeight,
            'font-style'  => $fontStyle,
            'font-family' => 'ts-family-'. $indentificator,
            );

        $options = get_option('videofly_typography');
        array_push($options['custom-icon'], $customIcon);

        $_POST['videofly_typography']['icons'] .= ','. $classes;
        $_POST['videofly_typography']['custom-icon'] = $options['custom-icon'];
    }

}
add_action( 'admin_init', 'vdf_import_icon');

function vdf_get_style_custom_icon_admin(){
    $options = get_option('videofly_typography');
    $icons = isset($options['custom-icon']) && !empty($options['custom-icon']) ? $options['custom-icon'] : '';

    if( !empty($icons) ){

        echo '<style>';
        foreach($icons as $value){

            echo    "@font-face {
                font-family: '". $value['font-family'] ."';
                src: url('". wp_get_attachment_url($value['ids']['eot']) ."');
                src: url('". wp_get_attachment_url($value['ids']['eot']) ."#iefix') format('embedded-opentype'),
                url('". wp_get_attachment_url($value['ids']['woff']) ."') format('woff'),
                url('". wp_get_attachment_url($value['ids']['ttf']) ."') format('truetype'),
                url('". wp_get_attachment_url($value['ids']['svg']) ."#fontello') format('svg');
                font-weight: ". $value['font-weight'] ."
                font-style: ". $value['font-style'] ."
            }". $value['css'];
        }
        echo '</style>';
    }

}
add_action('admin_head', 'vdf_get_style_custom_icon_admin');

function vdf_extended_upload_mimes ( $mime_types =array() ) {
    $mime_types['svg'] = 'image/svg+xml';
    $mime_types['woff'] = 'image/x-woff';
    $mime_types['ttf'] = 'image/x-font-ttf';
    $mime_types['eot'] = 'image/vnd.ms-fontobject';
    $mime_types['css'] = 'text/css';
    $mime_types['mp4'] = 'webm/mp4';
    $mime_types['webm'] = 'webm/webm';

    unset( $mime_types['exe'] );

    return $mime_types;
}
add_filter('upload_mimes', 'vdf_extended_upload_mimes');

function vdf_ajax_parse_embed() {
    global $wp_embed;

    if( isset($_POST['post_ID']) && (int)$_POST['post_ID'] == 0 ){

        $shortcode = wp_unslash( $_POST['shortcode'] );
        $url = str_replace( '[embed]', '', str_replace( '[/embed]', '', $shortcode ) );

        $parsed = false;
            //setup_postdata( $post );

        $wp_embed->return_false_on_fail = true;

        if ( is_ssl() && preg_match( '%^\\[embed[^\\]]*\\]http://%i', $shortcode ) ) {
                // Admin is ssl and the user pasted non-ssl URL.
                // Check if the provider supports ssl embeds and use that for the preview.
            $ssl_shortcode = preg_replace( '%^(\\[embed[^\\]]*\\])http://%i', '$1https://', $shortcode );
            $parsed = $wp_embed->run_shortcode( $ssl_shortcode );

            if ( ! $parsed ) {
                $no_ssl_support = true;
            }
        }

        if ( ! $parsed ) {
            $parsed = do_shortcode(str_replace('embed', 'ts_embed', $shortcode));
        }

        if ( ! $parsed ) {
            wp_send_json_error( array(
                'type' => 'not-embeddable',
                'message' => sprintf( esc_html__( '%s failed to embed.', 'videofly' ), '<code>' . esc_html( $url ) . '</code>' ),
                ) );
        }

        if ( has_shortcode( $parsed, 'audio' ) || has_shortcode( $parsed, 'video' ) ) {
            $styles = '';
            $mce_styles = wpview_media_sandbox_styles();
            foreach ( $mce_styles as $style ) {
                $styles .= sprintf( '<link rel="stylesheet" href="%s"/>', $style );
            }

            $html = do_shortcode( $parsed );

            global $wp_scripts;
            if ( ! empty( $wp_scripts ) ) {
                $wp_scripts->done = array();
            }
            ob_start();
            wp_print_scripts( 'wp-mediaelement' );
            $scripts = ob_get_clean();

            $parsed = $styles . $html . $scripts;
        }


        if ( ! empty( $no_ssl_support ) || ( is_ssl() && ( preg_match( '%<(iframe|script|embed) [^>]*src="http://%', $parsed ) ||
            preg_match( '%<link [^>]*href="http://%', $parsed ) ) ) ) {
                // Admin is ssl and the embed is not. Iframes, scripts, and other "active content" will be blocked.
            wp_send_json_error( array(
                'type' => 'not-ssl',
                'message' => esc_html__( 'This preview is unavailable in the editor.', 'videofly' ),
                ) );
        }

    }else{

        global $post, $wp_embed;

        if ( ! $post = get_post( (int) $_POST['post_ID'] ) ) {
            wp_send_json_error();
        }

        if ( empty( $_POST['shortcode'] ) || ! current_user_can( 'edit_post', $post->ID ) ) {
            wp_send_json_error();
        }

        $shortcode = wp_unslash( $_POST['shortcode'] );
        $url = str_replace( '[embed]', '', str_replace( '[/embed]', '', $shortcode ) );

        $parsed = false;
        setup_postdata( $post );

        $wp_embed->return_false_on_fail = true;

        if ( is_ssl() && preg_match( '%^\\[embed[^\\]]*\\]http://%i', $shortcode ) ) {
                // Admin is ssl and the user pasted non-ssl URL.
                // Check if the provider supports ssl embeds and use that for the preview.
            $ssl_shortcode = preg_replace( '%^(\\[embed[^\\]]*\\])http://%i', '$1https://', $shortcode );
            $parsed = $wp_embed->run_shortcode( $ssl_shortcode );

            if ( ! $parsed ) {
                $no_ssl_support = true;
            }
        }

        if ( ! $parsed ) {
            $parsed = $wp_embed->run_shortcode( $shortcode );
        }

        if ( ! $parsed ) {
            wp_send_json_error( array(
                'type' => 'not-embeddable',
                'message' => sprintf( esc_html__( '%s failed to embed.', 'videofly' ), '<code>' . esc_html( $url ) . '</code>' ),
                ) );
        }

        if ( has_shortcode( $parsed, 'audio' ) || has_shortcode( $parsed, 'video' ) ) {
            $styles = '';
            $mce_styles = wpview_media_sandbox_styles();
            foreach ( $mce_styles as $style ) {
                $styles .= sprintf( '<link rel="stylesheet" href="%s"/>', $style );
            }

            $html = do_shortcode( $parsed );

            global $wp_scripts;
            if ( ! empty( $wp_scripts ) ) {
                $wp_scripts->done = array();
            }
            ob_start();
            wp_print_scripts( 'wp-mediaelement' );
            $scripts = ob_get_clean();

            $parsed = $styles . $html . $scripts;
        }


        if ( ! empty( $no_ssl_support ) || ( is_ssl() && ( preg_match( '%<(iframe|script|embed) [^>]*src="http://%', $parsed ) ||
            preg_match( '%<link [^>]*href="http://%', $parsed ) ) ) ) {
                // Admin is ssl and the embed is not. Iframes, scripts, and other "active content" will be blocked.
            wp_send_json_error( array(
                'type' => 'not-ssl',
                'message' => esc_html__( 'This preview is unavailable in the editor.', 'videofly'),
                ) );
        }

    }

    wp_send_json_success( array(
        'body' => $parsed
        ) );
}
add_action( 'wp_ajax_parse-embed', 'vdf_ajax_parse_embed', 0);


function vdf_embed_callback( $attr, $url = '' ) {
    $url = strip_tags($url);
    if ( empty( $url ) && ! empty( $attr['src'] ) ) {
        $url = $attr['src'];
    }

    if ( empty( $url ) ) return '';

    $rawattr = $attr;
    $attr = wp_parse_args( $attr, wp_embed_defaults($url) );

    $url = str_replace( '&amp;', '&', $url );

    $handlers = array(
        'youtube_embed_url' => array(
            'regex' => '#https?://(www.)?youtube\.com/embed/([^/]+)#i',
            'callback' => 'wp_embed_handler_youtube'
            ),
        'googlevideo' => array(
            'regex' => '#http://video\.google\.([A-Za-z.]{2,5})/videoplay\?docid=([\d-]+)(.*?)#i',
            'callback' => 'wp_embed_handler_googlevideo'
            ),
        'audio' => array(
            'regex' => '#^https?://.+?\.(mp3|ogg|wma|m4a|wav)$#i',
            'callback' => 'wp_embed_handler_audio'
            ),
        'video' => array(
            'regex' => '#^https?://.+?\.(mp4|m4v|webm|ogv|wmv|flv)$#i',
            'callback' => 'wp_embed_handler_video'
            )
        );

    foreach($handlers as $handler){

        if ( preg_match( $handler['regex'], $url, $matches ) && is_callable( $handler['callback'] ) ) {

            if( false !== $return = call_user_func( $handler['callback'], $matches, $attr, $url, $rawattr ) ){
                return apply_filters( 'embed_handler_html', $return, $url, $attr );
            }

        }

    }

    return wp_oembed_get($url, $attr);

}
add_shortcode('ts_embed', 'vdf_embed_callback');

function vdf_the_content() {

    if ( !has_filter( 'ts_the_content', 'wptexturize' ) ) {

        add_filter( 'ts_the_content', 'wptexturize'        );
        add_filter( 'ts_the_content', 'convert_smilies'    );
        add_filter( 'ts_the_content', 'convert_chars'      );
        add_filter( 'ts_the_content', 'wpautop'            );
        add_filter( 'ts_the_content', 'shortcode_unautop'  );
        add_filter( 'ts_the_content', 'prepend_attachment' );
        add_filter( 'ts_the_content', 'vdf_autoembed_callback', 10, 1);

        add_filter( 'ts_the_content', 'do_shortcode', 12);
    }

}
add_action('init', 'vdf_the_content', 1);

function vdf_autoembed_callback($content){
    $content = wp_replace_in_html_tags($content, array( "\n" => '<!-- wp-line-break -->' ));
    $content = preg_replace('/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/im', '[ts_embed]$0[/ts_embed]', $content);

    return $content;
}

function vdf_build_list_icons(){
    $typography = get_option('videofly_typography', array());

    $typography['icons'] = explode(',', $typography['icons']);
    $return = array('li' => '', 'option' => '');

    foreach ($typography['icons'] as $value){

        $return['li'] .=    '<li class="filter-li" data-filter="'. str_replace('icon-', '', trim($value)) .'">
        <i class="'. trim($value) .' clickable-element" data-option="'. trim($value) .'"></i>
    </li>';

    $return['option'] .= '<option value="'. trim($value) .'"></option>';

}

return $return;
}

function ts_get_rating($post_id){
    if( is_numeric($post_id) ){
        $rating_items = get_post_meta($post_id, 'ts_post_rating', TRUE);
        if( isset($rating_items) && is_array($rating_items) && !empty($rating_items) ){
            $total = '';
            foreach($rating_items as $rating){
                $total += $rating['rating_score'];
            }
            if( $total > 0 ){
                $round = intval($total) / count($rating_items);
                $result = round($round, 1);

                if( is_int($round) ){
                    if( $round == 10 ) return $result;
                    else return $result . '.0';
                }else{
                    return $result;
                }
            }else{
                return;
            }
        }
    }else{
        return;
    }
}

function vdf_captcha($return = 'all'){

    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

    if( is_plugin_active('really-simple-captcha/really-simple-captcha.php') ){
        $captcha_instance = new ReallySimpleCaptcha();
        $word = $captcha_instance->generate_random_word();
        $prefix = mt_rand();
        echo '<div class="ts-captcha-container">';
        if( $return == 'img' ){
            echo '<img class="ts-img-captcha" data-prefix="'. $prefix .'" src="'. esc_url( plugins_url( 'really-simple-captcha/tmp/'. $captcha_instance->generate_image( $prefix, $word ) ) ) .'">';
        }else{
            echo
                '<span class="ts-container-img-captcha">
                    <img class="ts-img-captcha" data-prefix="'. $prefix .'" src="'. esc_url( plugins_url( 'really-simple-captcha/tmp/'. $captcha_instance->generate_image( $prefix, $word ) ) ) .'">
                </span>
                <span class="ts-regenerate-captcha icon-restart"></span>
                <input type="text" name="vdf-captcha" value="" class="ts-captcha">';
        }
        echo '</div>';

    }else{
        return;
    }
}

function ts_disable_redirect_canonical( $redirect_url )
{
    if ( is_paged() && is_singular() ) $redirect_url = false; return $redirect_url;
}
add_filter('redirect_canonical','ts_disable_redirect_canonical');

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


function ts_embed_generate(){

    if( is_admin() ) return;

    $current_url = esc_url( home_url( add_query_arg( NULL, NULL ) ) );

    if ( preg_match( "#^http.*\/embed\/\d{1,}#i", $current_url ) ) {

        $array_id = explode( '/', $current_url );
        $post_id = end( $array_id );

        if( ! is_numeric( $post_id ) ) return;

        $args = array(
            'post_type'      => 'video',
            'p'              => $post_id,
            'posts_per_page' => 1
        );

        $embed = get_posts( $args );

        if ( ! isset( $embed[0]->ID ) ) return;

        $meta = get_post_meta( $embed[0]->ID, 'ts-video', true );

        $poster = wp_get_attachment_url( get_post_thumbnail_id( $embed[0]->ID, 'full' ) );

        $video = isset( $meta['video'] ) ? $meta['video'] : '';

        if ( $meta['type'] != 'embed' && ! empty( $video ) ) {

            echo
                '<div id="videoframe" class="video-frame"></div>
                <script src="https://content.jwplatform.com/libraries/4r6XfcLg.js"></script><script>

                var playerInstance = jwplayer("videoframe");

                playerInstance.setup({
                    file: "' . $video . '",
                    image: "' . $poster . '",
                    width: 640,
                    height: 380,
                    title: "' . $embed[0]->post_title . '"
                });
            </script>';

        } else if ( $meta['type'] == 'embed' && ! empty( $video ) ) {

            echo vdf_var_sanitize( $video );

        } else {
            esc_html_e( 'No video', 'videofly' );
        }

        exit;
    }
}

add_action('init', 'ts_embed_generate');

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
?>