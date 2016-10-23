<?php
// File Security Check
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
    die ( 'You do not have sufficient permissions to access this page' );
}
?>
<?php

/*-----------------------------------------------------------------------------------*/
/* This theme supports WooCommerce, woo! */
/*-----------------------------------------------------------------------------------*/

// Remove the some default actions
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);

add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
	add_theme_support( 'woocommerce' );
}

add_action( 'woocommerce_after_shop_loop_item_rating', 'woocommerce_template_loop_rating', 5 );
add_action( 'woocommerce_main_breadcrumb', 'woocommerce_breadcrumb', 5 );


// Remove the default WordPress wrappers
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('woocommerce_before_main_content', 'videotouch_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'videotouch_wrapper_end', 10);

function videotouch_wrapper_start() {
?>
	<section id="main">

<?php
}

function videotouch_wrapper_end() {
?>

	</section>
<?php

}

add_filter( 'woocommerce_product_add_to_cart_text' , 'custom_woocommerce_product_add_to_cart_text' );

/**
 * custom_woocommerce_template_loop_add_to_cart
*/
function custom_woocommerce_product_add_to_cart_text() {
	global $product;

	$product_type = $product->product_type;

	switch ( $product_type ) {
		case 'external':
			return __( 'Buy product', 'woocommerce' );
		break;
		case 'grouped':
			return __( 'View', 'woocommerce' );
		break;
		case 'simple':
			return __( 'Buy', 'woocommerce' );
		break;
		case 'variable':
			return __( 'Choose', 'woocommerce' );
		break;
		default:
			return __( 'More', 'woocommerce' );
	}

}

//Redefine wooCommerce related products
function woocommerce_output_related_products() {
	woocommerce_related_products( array( 'posts_per_page' => 3, 'columns' => 3 ) ); // Display 3 products in rows of 3
}

// END.