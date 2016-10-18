<?php
/**
 * Product Loop Start
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */
global $woocommerce_loop;

if( !isset($woocommerce_loop['columns']) || $woocommerce_loop['columns'] == '' ){
	$woocommerce_loop['columns'] = '3';
}

?>
<div class="products row product-view cols-by-<?php echo $woocommerce_loop['columns']; ?>">