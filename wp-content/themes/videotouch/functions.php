<?php
/**
 * Functions
 */
require( get_template_directory() . '/includes/functions.php' );
/**
 * Define custom constants, image sizes, nav menus...
 */
require( get_template_directory() . '/includes/theme-setup.php' );

/**
 * Dynamic included CSS and JavaScript 
 */
require( get_template_directory() . '/includes/dynamic-css-and-js.php' );

/**
 * Include to search taxonome and tags
 */
require( get_template_directory() . '/includes/include-to-search.php' );

require( get_template_directory() . '/includes/frontend-submit.php' );
/**
 * Include the Widgets Functions File
 */
require( get_template_directory() . '/includes/widgets.php' );

/**
 * Include the Most liked Functions File
 */
require( get_template_directory() . '/includes/widgets/most-liked.php' );

require( get_template_directory() . '/includes/widgets/video-categories.php' );

/**
 * Include the Most liked Functions File
 */
require( get_template_directory() . '/includes/widgets/most-viewed.php' );

/**
 * Dynamic sidebars
 */
require( get_template_directory() . '/includes/dynamic-sidebars.php' );

/**
 * Likes system
 */
require( get_template_directory() . '/includes/TouchSizeLikes.php' );

/**
 * Layout builder elements
 */
require( get_template_directory() . '/includes/layout-builder/classes/element.php' );

/**
 * Layout builder templates
 */
require( get_template_directory() . '/includes/layout-builder/classes/template.php' );

/**
 * Options megamenu
 */
require( get_template_directory() . '/includes/megamenu/ts-megamenu.php' );
require( get_template_directory() . '/includes/megamenu/class-megamenu.php' );


/**
 * Include Theme options
 */
require( get_template_directory() . '/includes/options.php' );

/**
 * Ajax
 */
require( get_template_directory() . '/includes/ajax.php' );

/**
 * Custom posts and Metadata
 */
require( get_template_directory() . '/includes/custom-posts.php' );

/**
 * Layout Compilator
 */
require( get_template_directory() . '/includes/layout-compilator.php' );

/**
 * Aqua resizer
 */
require( get_template_directory() . '/includes/aq_resizer.php' );

/**
 * Fields Class
 */
require( get_template_directory() . '/includes/fields.class.php' );

/**
 * Attached images manager
 */
require( get_template_directory() . '/includes/attached_images_manager.php' );

/**
 * Attached images manager
 */
require( get_template_directory() . '/includes/ts-shortcode/TsShortcode.php' );

/**
 * Include for the widgets
 */

require( get_template_directory() . '/includes/widgets/tweets.php' );
require( get_template_directory() . '/includes/widgets/flickr.php' );
require( get_template_directory() . '/includes/widgets/instagram.php' );
require( get_template_directory() . '/includes/widgets/tags.php' );
require( get_template_directory() . '/includes/widgets/custom_post.php' );
require( get_template_directory() . '/includes/widgets/comments.php' );
require( get_template_directory() . '/includes/widgets/latest_posts.php' );

// require( get_template_directory() . 'theme-picker.php' );


// Add ID and CLASS attributes to the first <ul> occurence in wp_page_menu
function add_menuclass( $ulclass ) {
	return preg_replace('/<div class="(.*)"><ul/im', '<div><ul class="$1"', $ulclass);
}
add_filter( 'wp_page_menu', 'add_menuclass' );

if ( ! isset( $content_width ) ) $content_width = 1340;

// Add WooCommerce Support for the theme
require( get_template_directory() . '/woocommerce/theme-woocommerce.php' );

if(current_theme_supports( 'ts_is_mega_menu' ) ) { new ts_is_megamenu(); }

?>