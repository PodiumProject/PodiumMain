<?php

/* Team view template below */
###########

// Get the options

global $article_options;

$items = get_post_meta( get_the_ID(), 'ts_pricing_table', true);
$details = get_post_meta( get_the_ID(), 'ts_pricing_table_details', true);

$featured_class = '';
if ( $details['featured'] == 'yes' ) {
	$featured_class = 'featured';
}
// Get article columns by elements per row
$columns_class = LayoutCompilator::get_column_class($article_options['elements-per-row']);


?>
<div class="<?php echo vdf_var_sanitize($columns_class); ?>">
	<article class="<?php echo vdf_var_sanitize($featured_class); ?>">
		<?php 
			if ( $details['featured'] == 'yes' ) {
				echo '<span class="featured_emblem">'.esc_html__('most popular','videofly').'</span>';
			}
		?>
		<header>
			<div class="entry-title">
				<h3 class="title"><?php the_title(); ?></h3>
			</div>
			<div class="entry-box">
				<div class="pricing-price">
					<span class="currency"><?php echo @$details['currency']; ?></span>
					<span class="price"><?php echo @$details['price']; ?></span>
					<span class="period"><?php echo @$details['period']; ?></span>
					<div class="pricing-desc">
						<span><?php echo @$details['description']; ?></span>
					</div>
				</div>
			</div>
		</header>
		<section>
			<div class="entry-content">
				<ul>
					<?php
						foreach ($items as $key => $item) {
							echo '<li><span>' . $item['item_title'] . '</span></li>';
						}
					?>
				</ul>
			</div>
		</section>
		<footer>
			<a href="<?php echo @$details['url']; ?>"><i class="icon-basket"></i><?php echo @$details['button_text']; ?></a>
		</footer>
	</article>
</div>