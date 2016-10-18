<?php
/**
 * Jetpack Compatibility File
 * See: https://jetpack.me/
 *
 * @package CPMMagz
 */

/**
 * Add theme support for Infinite Scroll.
 * See: https://jetpack.me/support/infinite-scroll/
 */
function cpmmagz_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'render'    => 'cpmmagz_infinite_scroll_render',
		'footer'    => 'page',
	) );
} // end function cpmmagz_jetpack_setup
add_action( 'after_setup_theme', 'cpmmagz_jetpack_setup' );

/**
 * Custom render function for Infinite Scroll.
 */
function cpmmagz_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		get_template_part( 'template-parts/content', get_post_format() );
	}
} // end function cpmmagz_infinite_scroll_render
?>