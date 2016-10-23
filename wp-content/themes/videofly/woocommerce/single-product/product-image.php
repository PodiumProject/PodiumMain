<?php
/**
 * Single Product Image
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.14
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $woocommerce, $product;

?>
<div class="images">	
    <?php if( !empty( $product->get_gallery_attachment_ids()) ) : ?>
		<div id="product-slider" class="flexslider with-thumbs" data-animation="slide">
	      
		    <ul class="slides" >

		<?php

			$attachment_ids = $product->get_gallery_attachment_ids();
			tsIncludeScripts(array('flexslider', 'fancybox.pack'));

			if ( sizeof($attachment_ids) ){

				foreach ( $attachment_ids as $attachment_id ) {

					$classes = array( 'zoom' );

					$image_link = wp_get_attachment_url( $attachment_id );


					if ( ! $image_link )
						continue;
					$red_img_src = wp_get_attachment_url( $attachment_id   ,'full'); //get img URL
                    $red_img_url = vdf_resize('single', $red_img_src);
                    $red_thumb_url = aq_resize( $red_img_src, '180', '140', true, true); //resize img


					$image_class = esc_attr( implode( ' ', $classes ) );
					$image_title = esc_attr( get_the_title( $attachment_id ) );
					$image =  '<li data-thumb="'.$red_thumb_url.'"><img src="'.$red_img_url.'" alt="Product image" />';

					echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<a href="%s" itemprop="image" class=" woocommerce-main-image zoom" title="%s"  rel="fancybox">%s</a>', $image_link, $image_title, $image ), $post->ID );
					
				}
				
			} else {

				echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<li><img src="%s" alt="Placeholder" /></li>', woocommerce_placeholder_img_src() ), $post->ID );
			}
		?>
		  	</ul>

	  	</div>
	<?php else : ?>
		<?php the_post_thumbnail(); ?>
	<?php endif; ?>

</div>
