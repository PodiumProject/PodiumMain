<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 3 );

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;

// Increase loop count
$woocommerce_loop['loop']++;

global $article_options;

$classes = array();
if( isset($article_options['elements-per-row']) ){

   array_push($classes, LayoutCompilator::get_column_class($article_options['elements-per-row']));
   
}else{
    // Extra post classes
    if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] )
        $classes[] = 'first';
    if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] )
        $classes[] = 'last';
    if( $woocommerce_loop['columns'] == 3 ){
        $classes[] = 'col-lg-4';
    } elseif( $woocommerce_loop['columns'] == 4 ){
        $classes[] = 'col-lg-3';
    } elseif( $woocommerce_loop['columns'] == 2 ){
        $classes[] = 'col-lg-6';
    } elseif( $woocommerce_loop['columns'] == 5 ){
        $classes[] = 'col-lg-4';
    } elseif( $woocommerce_loop['columns'] == 6 ){
        $classes[] = 'col-lg-2';
    } else{
        $classes[] = 'col-lg-3';
    }
}
$ts_image_is_masonry = false;
if ( isset($article_options['behavior']) && $article_options['behavior'] == 'masonry' ) {
    array_push($classes, 'masonry-element');
    $ts_image_is_masonry = true;
}

$size = 'product_grid';
$meta = get_option('videotouch_single_post', array('post_meta'=> 'Y'));
?>

<div <?php post_class( $classes ); ?> data-post-id="<?php echo $post->ID; ?>" >
    <article>
        <header>
            <div class="product-perspective">
                <div class="featimg">
                    <?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
                    <?php
                        if (has_post_thumbnail($post->ID)) {

                            
                            $src = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
                            
                            $img_url = ts_resize('grid', $src, $ts_image_is_masonry);
                            
                            $noimg_url = get_template_directory_uri() . '/images/noimage.jpg';
                            $bool = fields::get_options_value('videotouch_general', 'enable_imagesloaded');

                            if ( $src ) {
                                $featimage = '<img ' . ts_imagesloaded($bool, $img_url) . ' alt="' . esc_attr(get_the_title()) . '" />';
                            } else {
                                $featimage = '<img ' .  ts_imagesloaded($bool, $noimg_url). ' alt="' . esc_attr(get_the_title()) . '" />';
                            }
                        ?>
                            <a href="<?php echo get_permalink($post->ID);  ?>">
                                <?php echo $featimage; ?>
                            </a>
                        <?php    
                        }
                    ?>
                </div>
                <div class="product-share">
                    <ul class="share-options">
                        <li>
                            <a class="icon-facebook" target="_blank" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo get_permalink(get_the_ID()); ?>"></a>
                        </li>
                        <li>
                            <a class="icon-twitter" target="_blank" href="http://twitter.com/home?status=<?php echo urlencode(get_the_title()); ?>+<?php echo get_permalink(get_the_ID()); ?>"></a>
                        </li>
                        <li>
                            <a class="icon-pinterest" target="_blank" href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&amp;media=<?php if(function_exists('the_post_thumbnail')) echo wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())); ?>&amp;description=<?php echo urlencode(get_the_title()); ?>" ></a>
                        </li>
                    </ul>
                </div>
            </div>
        </header>
        <section>
            <div class="entry-section">
                <div class="entry-title">
                    <h3>
                        <a href="<?php echo get_permalink($post->ID);  ?>" title="<?php _e('Permalink to', 'touchsize'); ?> <?php echo $post->post_title; ?>" rel="bookmark"><?php echo esc_attr($post->post_title); ?></a>
                    </h3>
                </div>
                <div class="grid-shop-options">
                    <div class="price-options">
                        <?php
                            do_action( 'woocommerce_after_shop_loop_item_title' );
                        ?>
                    </div>
                </div>
                <?php do_action( 'woocommerce_after_shop_loop_item_rating' ); ?>
                <div class="entry-categories">
                    <ul>
                        <?php
                            $product_categories = wp_get_post_terms(get_the_ID(), 'product_cat' );
                            foreach ($product_categories as $category) {
                                $categ = get_category($category);
                                echo '<li>' . '<a href="' . get_category_link($category) . '">' . $category->name .  '</a>' . '</li>';    
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </section>
        <footer>
            <div class="entry-footer">
                <div class="row">
                    <div class="col-lg-8 col-md-6 col-sm-6 col-xs-6">
                    <?php if ( $meta['post_meta'] === 'Y' || !isset($meta['post_meta']) ) : ?>
                        <div class="entry-meta">
                           <?php touchsize_likes($post->ID, '<div class="entry-meta-likes">', '</div>'); ?>
                            <div class="entry-meta-views">
                                <i class="icon-views"></i>
                                <span class="view-count"><?php ts_get_views( get_the_ID() ); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 text-right">
                        <?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
                    </div>
                </div>
            </div>  
        </footer>
        
    </article>
</div>