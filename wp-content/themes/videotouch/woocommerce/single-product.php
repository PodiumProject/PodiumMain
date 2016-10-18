<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
get_header();
/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action('woocommerce_before_main_content');
?>
	<div class="container">
		<?php do_action('woocommerce_main_breadcrumb'); ?>
		<div class="row">
			<?php

				if (LayoutCompilator::sidebar_exists()) {
				
					$options = LayoutCompilator::get_sidebar_options();

					extract(LayoutCompilator::build_sidebar($options));

					if (LayoutCompilator::is_left_sidebar()) {
						echo $sidebar_content;
					}
				} else {
					$content_class = 'col-lg-12';
				}
			?>
			<div id="primary" class="<?php echo $content_class ?>">
				<?php while ( have_posts() ) : the_post(); ?>

					<?php woocommerce_get_template_part( 'content', 'single-product' ); ?>

				<?php endwhile; // end of the loop. ?>
			</div>
			<?php
				
				if (LayoutCompilator::sidebar_exists()) {
					if (LayoutCompilator::is_right_sidebar('single')) {
						echo $sidebar_content;
					}
				}
				do_action('woocommerce_after_main_content');

			
			?>
		</div>
	</div>
</section>
<?php ts_get_pagination_next_previous(); ?>
<?php get_footer(); ?>