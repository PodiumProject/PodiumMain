<?php
/**
* This class is used for build a layout created in admin panel
*/
class LayoutCompilator
{
	public static $columns = array(
		1 => 'col-lg-1 col-md-1',
		2 => 'col-lg-2 col-md-2',
		3 => 'col-lg-3 col-md-3',
		4 => 'col-lg-4 col-md-4',
		5 => 'col-lg-5 col-md-5',
		6 => 'col-lg-6 col-md-6',
		7 => 'col-lg-7 col-md-7',
		8 => 'col-lg-8 col-md-8',
		9 => 'col-lg-9 col-md-9',
		10 => 'col-lg-10 col-md-10',
		11 => 'col-lg-11 col-md-11',
		12 => 'col-lg-12 col-md-12',
		);

	public static $tsScripts = array();

	public static function order_direction($order_direction = 'asc')
	{
		return ($order_direction === 'asc') ? 'ASC' : 'DESC';
	}

	public static function order_by($order_by = 'date', $args = array(), $featured = 'n')
	{

		$order_variants = array('date', 'comments', 'views', 'likes', 'start-date', 'rand');

		$order_by = (in_array($order_by, $order_variants)) ? $order_by : 'date' ;

		if( $featured === 'y' ){

			$args['meta_query'] = array(
				array(
					'key' => 'featured',
					'value' => 'yes',
					'compare' => '=',
					),
				);

		}

		if( $order_by === 'comments' ){
			$args['orderby'] = 'comment_count';
		}

		if( $order_by === 'views' ){
			$args['meta_key'] = 'ts_article_views';
			$args['orderby']  = 'meta_value_num';
		}

		if( $order_by === 'likes' ){
			$args['meta_key'] = 'likes-media';
			$args['orderby']  = 'meta_value_num';
		}

		if( $order_by === 'date' ){
			$args['orderby'] = 'date';
		}

		if($order_by == 'start-date' ){
			$args['meta_key'] = 'day';
			$args['orderby']  = 'meta_value_num';
		}

		if( $order_by == 'rand' ){
			$args['orderby']  = 'rand';
		}

		return $args;
	}

	public static function get_related_posts($post_id = 0, $tags = array())
	{
		$single_options = get_option('videofly_single_post');
		$criteria = $single_options['related_posts_selection_criteria'];

		if ( $criteria === 'by_tags' ) {

			$post_type = get_post_type($post_id);

			$args_related['posts_per_page'] = (int)$single_options['number_of_related_posts'];
			$args_related['post_type'] = $post_type;

			if ( $tags ) {
				$tag_id = array();
				foreach($tags as $tag) {
					$tag_id[] = $tag->term_id;
				}

				$args_related = array(
					'tag__in'        => $tag_id,
					'post__not_in'   => array( $post_id ),
					'post_type'      => 'post',
					'posts_per_page' => 3,
					);

				$query_related = get_posts($args_related);

				$related = '<footer>
				<div class="related">
					<div class="row">
						<p class="title">' . esc_html__('Related articles', 'videofly') . '</p>
					</div>
					<ul class="related-list row">
						{{articles}}
					</ul>
				</div>
			</footer>';

			$related_posts = array();

			if ( isset($query_related) && !empty($query_related) && is_array($query_related) ){

				foreach($query_related as $post_related){

					$article_date =  get_the_date('', $post_related->ID);

					$related_posts[] = '
					<li class="col-lg-4">
						<div class="row">
							<div class="related-thumb col-md-5 col-sm-5 col-xs-5">
								<a href="' . get_permalink($post_related->ID) . '">'
									. get_the_post_thumbnail($post_related->ID,  'vdf_thumb') .
									'</a>
								</div>
								<div class="related-content col-md-7 col-sm-7 col-xs-7">
									<h3>
										<a href="' . get_permalink($post_related->ID) . '">'
											. get_the_title($post_related->ID) .
											'</a>
									</h3>
										<div class="ts-view-entry-meta-date">
											<ul>
												<li>' . $article_date . '</li>
											</ul>
										</div>
									</div>
						</div>
					</li>';
					}


					return str_replace('{{articles}}', implode("\n", $related_posts), $related);

				} else {
					return '';
				}

			} else {
				return '';
			}
		} else if ( $criteria === 'by_categs' ) {

			$category_id = array();
			$categories = wp_get_post_categories($post_id);
			$post_type = get_post_type($post_id);

			if( isset($post_type) && $post_type == 'video' ){
				$term_list = wp_get_post_terms($post_id, 'videos_categories', array("fields" => "ids"));
				if ( isset($term_list) && is_array($term_list) && !empty($term_list) ) {

					$args_related['tax_query'] = array('relation' => 'AND',
						array(
							'taxonomy' => 'videos_categories',
							'field'    => 'id',
							'terms'    => $term_list,
							'operator' => 'IN'
							)
						);
					$args_related['post_type']      = 'video';
					$args_related['posts_per_page'] = (int)$single_options['number_of_related_posts'];
					$args_related['post__not_in']  = array($post_id);

					$query_related = get_posts($args_related);

					$related =  '<footer>
					<div class="related">
						<div class="row">
							<p class="title">' . esc_html__('Related articles', 'videofly') . '</p>
						</div>
						<ul class="related-list row">
							{{articles}}
						</ul>
					</div>
				</footer>';

				$related_posts = array();

				if ( isset($query_related) && !empty($query_related) && is_array($query_related) ) {
					foreach($query_related as $post_related){

						$article_date =  get_the_date('', $post_related->ID);

						$related_posts[] = '
						<li class="col-lg-4">
							<div class="row">
								<div class="related-thumb col-md-5 col-sm-5 col-xs-5">
									<a href="' . get_permalink($post_related->ID) . '">'
										. get_the_post_thumbnail($post_related->ID,  'vdf_thumb') .
										'</a>
									</div>
									<div class="related-content col-md-7 col-sm-7 col-xs-7">
											<h3>
												<a href="' . get_permalink($post_related->ID) . '">'
													. get_the_title($post_related->ID) .
													'</a>
											</h3>
											<div class="ts-view-entry-meta-date">
												<ul>
													<li>' . $article_date . '</li>
												</ul>
											</div>
										</div>
							</div>
						</li>';
						}

						return str_replace('{{articles}}', implode("\n", $related_posts), $related);
					} else {
						return '';
					}

				}
			}

			if ( $categories ) {

				$args_related['category__in']   = $categories;
				$args_related['posts_per_page'] = (int)$single_options['number_of_related_posts'];
				$args_related['post_type']      = $post_type;
				$args_related['post__not_in']  = array($post_id);

				$query_related = get_posts( $args_related );

				$related =  '<footer>
				<div class="related">
					<div class="row">
						<p class="title">' . esc_html__('Related articles', 'videofly') . '</p>
					</div>
					<ul class="related-list row">
						{{articles}}
					</ul>
				</div>
			</footer>';

			$related_posts = array();

			if ( isset($query_related) && !empty($query_related) && is_array($query_related) ){

				foreach($query_related as $post_related){

					$article_date =  get_the_date('', $post_related->ID);

					$related_posts[] = '
					<li class="col-lg-4">
						<div class="row">
							<div class="related-thumb col-md-5 col-sm-5 col-xs-5">
								<a href="' . get_permalink($post_related->ID) . '">'
									. get_the_post_thumbnail($post_related->ID,  'vdf_thumb') .
									'</a>
								</div>
								<div class="related-content col-md-7 col-sm-7 col-xs-7">
									<h3>
										<a href="' . get_permalink($post_related->ID) . '">'
											. get_the_title($post_related->ID) .
											'</a>
									</h3>
										<div class="ts-view-entry-meta-date">
											<ul>
												<li>' . $article_date . '</li>
											</ul>
										</div>
									</div>
						</div>
					</li>';
					}

					return str_replace('{{articles}}', implode("\n", $related_posts), $related);
				} else {
					return '';
				}

			} else {
				return '';
			}

		} else {
			return '';
		}

	}

	public static function get_single_related_posts($post_id = 0) {

		$related_posts = '';
		$single_options = get_option('videofly_single_post');

		$args = array(
			'post__not_in' => array( $post_id ),
			'post_type' => 'post',
			);

		$options = array();

		$options['special-effects'] = '';
		$options['display-mode'] = $single_options['related_posts_type'];
		$options['elements-per-row'] = $single_options['related_posts_nr_of_columns'];
		$options['order-direction'] = 'desc';
		$options['order-by'] = 'date';

		if ( $options['display-mode'] === 'grid' ) {
			$options['display-title'] = 'title-below-image';
			$options['show-meta'] = 'y';
			$options['enable-carousel'] = 'n';
		}
		if ( $options['display-mode'] === 'thumbnails' ) {
			$options['meta-thumbnail'] = 'y';
			$options['display-title'] = 'below-image';
		}

		$criteria = $single_options['related_posts_selection_criteria'];

		if ( $criteria === 'by_tags' ) {

			$tags = wp_get_post_tags( $post_id );
			$post_type = get_post_type($post_id);

			$tag_id = array();

			if ( $tags ) {
				foreach($tags  as $tag) {
					$tag_id[] = $tag->term_id;
				}
			} else {
				return '';
			}

			$args['tag__in'] = $tag_id;
			$args['posts_per_page'] = (int)$single_options['number_of_related_posts'];
			$args['post_type'] = $post_type;

			$query = new WP_Query( $args );

			if ( $query->have_posts() ) {
				return $related_posts . self::last_posts_element( $options, $query );
			} else {
				return '';
			}

		} else if ( $criteria === 'by_categs' ) {

			$category_id = array();
			$categories = wp_get_post_categories( $post_id );

			$post_type = get_post_type($post_id);
			if( isset($post_type) && ($post_type == 'video' || $post_type == 'ts-gallery' || $post_type == 'event') ){
				$taxonomy = ($post_type == 'video' ? 'videos_categories' : ($post_type == 'ts-gallery' ? 'gallery_categories':($post_type == 'event' ? 'event_categories' : '')));
				$term_list = wp_get_post_terms($post_id, $taxonomy, array("fields" => "ids"));
				if ( isset($term_list) && is_array($term_list) && !empty($term_list) ) {

					$args['tax_query'] = array('relation' => 'AND',
						array(
							'taxonomy' => $taxonomy,
							'field'    => 'id',
							'terms'    => $term_list,
							'operator' => 'IN'
							)
						);
					$args['post_type'] = $post_type;
					$args['posts_per_page'] = (int)$single_options['number_of_related_posts'];
					$args['post__not_in']  = array($post_id);

					$query = new WP_Query( $args );
					if ( $query->have_posts() ) {
						return $related_posts . self::last_posts_element( $options, $query);
					} else {
						return '';
					}
				}
			}

			if ( $categories ) {

				$args['category__in'] = $categories;
				$args['posts_per_page'] = (int)$single_options['number_of_related_posts'];

				$query = new WP_Query( $args );

				if ( $query->have_posts() ) {
					return $related_posts . self::last_posts_element( $options, $query );
				} else {
					return '';
				}

			} else {
				return '';
			}

		} else {
			return '';
		}
	}

	public static function list_products_element($options = array(), $post_id = 0, $tags = array()){

		if( $options['type'] == 'list-products' ){
			$categories = (isset($options['category']) && is_string($options['category'])) ? esc_attr($options['category']) : '';
			$args = array(
				'post_type' => 'product',
				'tax_query' => array(
					array(
						'taxonomy' => 'product_cat',
						'field' => 'id',
						'terms' => explode(',', $categories)
						)
					),
				'posts_per_page' => (int)$options['posts-limit'],
				'orderby' => $options['order-by'],
				'order' => $options['order-direction'],
				);

			$query = new WP_Query( $args );

			if ( $query->have_posts() ) {
				ob_start();
				ob_clean();
				global $article_options;
				$article_options = $options;
				while ( $query->have_posts() ) { $query->the_post();
					get_template_part('woocommerce/content-product');
				}
				$elements = ob_get_clean();

				wp_reset_postdata();
			}

		// Check if an effect is selected
			$display_effect = 'no-effect';

			if( isset($options['special-effects'] ) ){
				if( $options['special-effects'] == 'opacited' ){
					$display_effect = 'animated opacited';
				} elseif( $options['special-effects'] == 'rotate-in' ){
					$display_effect = 'animated rotate-in';
				} elseif( $options['special-effects'] == '3dflip' ){
					$display_effect = 'animated flip';
				} elseif( $options['special-effects'] == 'scaler' ){
					$display_effect = 'animated scaler';
				}
			}

		// If masonry is enabled

			$vdf_masonry_class = '';
			if( @$options['behavior'] === 'masonry' ){
				$vdf_masonry_class = ' ts-filters-container ';
			}

		// If carousel is enabled

			if( $options['behavior'] === 'carousel' ){
				$carousel_wrapper_start = '<div class="carousel-wrapper">';
				$carousel_wrapper_end = '</div>';

				$carousel_container_start = '<div class="carousel-overview"><div class="carousel-container">';
				$carousel_container_end = '</div></div>';

				$carousel_navigation = '<ul class="carousel-nav">
				<li class="carousel-nav-left icon-left">
					<span class="hidden_btn">'.esc_html__('prev','videofly').'</span>
				</li>
				<li class="carousel-nav-right icon-right">
					<span class="hidden_btn">'.esc_html__('next','videofly').'</span>
				</li>
			</ul>';
		} else{
			$carousel_wrapper_start = '';
			$carousel_wrapper_end = '';
			$carousel_container_start = '';
			$carousel_container_end = '';
			$carousel_navigation = '';
		}

		$elements = (isset($elements)) ? $elements : '';

		return '<div class="woocommerce"><section class="product-view cols-by-' . $options['elements-per-row'] . ' ' . $display_effect . $vdf_masonry_class . '">'. $carousel_wrapper_start . $carousel_navigation . $carousel_container_start . $elements . $carousel_container_end . $carousel_wrapper_end .'</section></div>';
	}


}

public static function get_splits($split1 = '1-3')
{

	$split_variants = array(
		'1-3' => 'col-lg-4 col-md-4 col-sm-12',
		'1-2' => 'col-lg-6 col-md-6 col-sm-12',
		'3-4' => 'col-lg-8 col-md-8 col-sm-12'
		);

	$split1 = (array_key_exists($split1, $split_variants)) ?
	$split_variants[$split1] : 'col-lg-4 col-md-4 col-sm-12';

		// content split
	switch ($split1) {
		case 'col-lg-4 col-md-4 col-sm-12':
		$split2 = 'col-lg-8 col-md-8 col-sm-12';
		break;

		case 'col-lg-6 col-md-6 col-sm-12':
		$split2 = 'col-lg-6 col-md-6 col-sm-12';
		break;

		case 'col-lg-8 col-md-8 col-sm-12':
		$split2 = 'col-lg-4 col-md-4 col-sm-12';
		break;

		default:
		$split2 = 'col-lg-8 col-md-8 col-sm-12';
		break;
	}

	return array(
		'split1' => $split1,
		'split2' => $split2
		);
}

public static function get_column_class($elements_per_row = 1)
{

	switch ($elements_per_row) {
		case '1':
		return 'col-lg-12 col-md-12 col-sm-12';
		break;

		case '2':
		return 'col-lg-6 col-md-6 col-sm-12';
		break;

		case '3':
		return 'col-lg-4 col-md-4 col-sm-12';
		break;

		case '4':
		return 'col-lg-3 col-md-3 col-sm-12';
		break;

		case '6':
		return 'col-lg-2 col-md-2 col-sm-12';
		break;

		default:
		return 'col-lg-2 col-md-2 col-sm-12';
		break;
	}
}

public static function get_clear_class($elements_per_row = 1)
{

	switch ($elements_per_row) {
		case '1':
		return 'cols-by-1';
		break;

		case '2':
		return 'cols-by-2';
		break;

		case '3':
		return 'cols-by-3';
		break;

		case '4':
		return 'cols-by-4';
		break;

		case '6':
		return 'cols-by-5';
		break;

		default:
		return 'cols-by-1';
		break;
	}
}


	/**
	 * Layout compilation starts from tist method
	 * @return string
	 */
	public static function run()
	{
		global $post;

		if ( post_password_required() ){
			echo apply_filters('ts_the_content', get_the_content());
			return;
		}

		$template        = get_post_meta( $post->ID, 'ts_template', true);
		$sidebar_options = get_post_meta( $post->ID, 'ts_sidebar', true);

		extract(self::build_sidebar( $sidebar_options ));

		$content = self::build_content($template);
		$content = '<div id="primary" class="'.$content_class.'"><div id="content" role="main">'.$content.'</div></div>';

		// if ( @$sidebar_options['position'] == 'left' ) {
		// 	$content = $sidebar_content . $content;
		// } else if ( @$sidebar_options['position'] == 'right' ) {
		// 	$content = $content . $sidebar_content;
		// }

		// Check if sidebar is set we apply the container part
		if( (isset($sidebar_options['position']) && $sidebar_options['position'] == 'left' && !LayoutCompilator::builder_is_enabled()) ||
			(isset($sidebar_options['position']) && $sidebar_options['position'] == 'right' && !LayoutCompilator::builder_is_enabled()) ){

			$theme_styles = get_option('videofly_styles');
			$use_padding = '';
			if($theme_styles['boxed_layout'] == 'N'){
				$use_padding = 'no-pad';
			}
			$content_wrapper_start = '<div class="container '. $use_padding .'">';
			$content_wrapper_end = '</div>';

		} else{
			$content_wrapper_start = '';
			$content_wrapper_end = '';
		}

	echo '<section id="main" class="row">' . $content_wrapper_start . $content . $content_wrapper_end . '</section>';
}

public static function builder_is_enabled()
{
	global $post;

	if ( is_object($post) && get_post_meta( @$post->ID, 'ts_use_template', true ) === '1') {
		return true;
	} else {
		return false;
	}
}

	/**
	 * Building sidebars
	 * @param  string $sidebar_id
	 * @return string
	 */
	public static function build_sidebar( $options = array() )
	{
		if ( ! isset( $options['id'] ) || ! is_active_sidebar( (string)$options['id'] ) ){
			return array(
				'sidebar_content' => '',
				'sidebar_class' => '',
				'content_class' => ''
				);
		}

		ob_start();
			dynamic_sidebar( (string)$options['id']);
		$sidebar = ob_get_contents();
		ob_end_clean();

		if ( isset($options['position']) && $options['position'] !== 'none' ) {

			if ($options['size'] === '1-3') {

				$sidebar_class = self::$columns[4];
				$content_class = self::$columns[8];

			} else if ($options['size'] === '1-4') {
				$sidebar_class = self::$columns[3];
				$content_class = self::$columns[9];

			} else {

				$sidebar_class = self::$columns[3];
				$content_class = self::$columns[9];
			}

			$sidebar_content = '<div id="secondary" class="secondary '.$sidebar_class.'">' . $sidebar . '</div>';

		} else {
			$sidebar_content = '';
			$sidebar_class = '';
			$content_class = self::$columns[12];
		}

		if( is_page() && self::builder_is_enabled() ){
			$content_class = 'ts-page-with-layout-builder col-lg-12 col-md-12 col-sm-12 col-xs-12';
		}
		global $post;

		return array(
			'sidebar_content' => $sidebar_content,
			'sidebar_class' => $sidebar_class,
			'content_class' => $content_class
			);
	}

	public static function sidebar_exists()
	{
		global $post;

		if( is_singular() && is_page() ){
			$page_type = 'page';
		}elseif( is_singular() && !is_page() && get_post_type($post->ID) !== 'product' ){
			$page_type = 'single';
		}elseif( is_singular() && !is_page() && get_post_type($post->ID) == 'product' ){
			$page_type = 'product';
		} else{
			$page_type = 'archive';
		}

		$sidebar_option = fields::get_options_value('videofly_layout', $page_type. '_layout');
		$sidebar_post = get_post_meta($post->ID, 'ts_sidebar', true);

		$sidebar_is_on = false;
		if ( $sidebar_option['sidebar']['position'] ) {
			if ( $sidebar_option['sidebar']['position'] === 'none' ) {
				$sidebar_is_on = false;
			} else {
				$sidebar_is_on = true;
			}
		}

		if( isset($sidebar_post['position']) ){
			if ( @$sidebar_post['position'] ) {
				if ( @$sidebar_post['position'] === 'none'  ) {
					$sidebar_is_on = false;
				} else if( @$sidebar_post['position'] != 'none' || !isset($sidebar_post['position']) && @$sidebar_option['position'] != 'none' ) {
					$sidebar_is_on = true;
				}
			}
		}

		return $sidebar_is_on;
	}

	public static function get_sidebar_options()
	{
		global $post;
		if( is_singular() && is_page() ){
			$page_type = 'page';
		}elseif( is_singular() && !is_page() ){
			$page_type = 'single';
		}elseif( is_singular() && !is_page() && get_post_type($post->ID) == 'product' ){
			$page_type = 'product';
		} else{
			$page_type = 'archive';
		}

		$sidebar = get_post_meta($post->ID, 'ts_sidebar', true);
		if( $sidebar == '' ){
			$sidebar = fields::get_options_value('videofly_layout', $page_type. '_layout');
			$sidebar = $sidebar['sidebar'];
		}
		return $sidebar;
	}

	public static function is_left_sidebar()
	{
		global $post;

		if( is_singular() && is_page() ){
			$page_type = 'page';
		}elseif( is_singular() && !is_page() ){
			$page_type = 'single';
		}elseif( is_singular() && !is_page() && get_post_type($post->ID) == 'product' ){
			$page_type = 'product';
		} else{
			$page_type = 'archive';
		}

		$sidebar_option = fields::get_options_value('videofly_layout', $page_type. '_layout');

		$sidebar_post = get_post_meta($post->ID, 'ts_sidebar', true);

		$sidebar_is_on = false;
		if ( $sidebar_option['sidebar']['position'] ) {
			if ( $sidebar_option['sidebar']['position'] === 'left' ) {
				$sidebar_is_on = true;
			} else {
				$sidebar_is_on = false;
			}
		}

		if ( isset($sidebar_post['position']) ) {
			if ( @$sidebar_post['position'] ) {
				if ( @$sidebar_post['position'] === 'left'  ) {
					$sidebar_is_on = true;
				} else {
					$sidebar_is_on = false;
				}
			}
		}
		return $sidebar_is_on;
	}

	public static function is_right_sidebar()
	{
		global $post;

		if( is_singular() && is_page() ){
			$page_type = 'page';
		}elseif( is_singular() && !is_page() ){
			$page_type = 'single';
		}elseif( is_singular() && !is_page() && get_post_type($post->ID) == 'product' ){
			$page_type = 'product';
		} else{
			$page_type = 'archive';
		}

		$sidebar_option = fields::get_options_value('videofly_layout', $page_type. '_layout');

		$sidebar_post = get_post_meta($post->ID, 'ts_sidebar', true);

		$sidebar_is_on = false;
		if ( isset($sidebar_option['sidebar']['position']) ) {
			if ( $sidebar_option['sidebar']['position'] === 'right' ) {
				$sidebar_is_on = true;
			} else {
				$sidebar_is_on = false;
			}
		}


		if ( isset($sidebar_post['position']) ) {
			if ( @$sidebar_post['position'] === 'right'  ) {
				$sidebar_is_on = true;
			} else {
				$sidebar_is_on = false;
			}
		}


		return $sidebar_is_on;
	}

	/**
	 * Parsing layout elements
	 * @param  array $rows
	 * @return string
	 */
	public static function build_content($rows = array())
	{

		$compiled_rows = array();

		if (is_array($rows) && ! empty($rows)) {
			$tsScripts = array();
			$elementsWithScripts = array('image-carousel', 'map', 'accordion', 'toggle', 'counters', 'chart');
			foreach ($rows as $row_index => $row) {

				// Add additional row classes if needed
				$additional_row_class = '';
				if ( self::is_fullscreen_row( $row['settings'] ) ) {
					$additional_row_class = ' ts-fullscreen-row ';
				}

				$opacity = (isset($row['settings']['rowOpacity']) && $row['settings']['rowOpacity'] !== '' ) ? (int)$row['settings']['rowOpacity']/100 : '';
				if ( isset($row['settings']['rowShadow']) && $row['settings']['rowShadow'] === 'yes' ){
					$additional_row_class .= ' ts-section-with-box-shadow ';
				}
				$vertical_align = ( isset($row['settings']['rowVerticalAlign']) ) ? strip_tags($row['settings']['rowVerticalAlign']) : '';
				$scroll_down_btn = ( isset($row['settings']['scrollDownButton']) ) ? strip_tags($row['settings']['scrollDownButton']) : '';
				$columnCarousel = (isset($row['settings']['rowCarousel'])) ? $row['settings']['rowCarousel'] : 'no';
				$ulCarouselStart = ($columnCarousel == 'yes') ? '<ul class="slidee">' : '';
				$ulCarouselEnd = ($columnCarousel == 'yes') ? '</ul>' : '';
				// end if;

				$rowID = (isset($row['settings']['rowName'])) ? trim(@$row['settings']['rowName'] ) : '';
				if ( !isset( $rowID ) || $rowID == '' ) {
					$row_name_id = '';
				} else{
					$row_name_id = ' id="ts_' . $row['settings']['rowName'].'" ';
				}

				if( ( isset($row['settings']['bgVideoMp']) && !empty($row['settings']['bgVideoMp']) ) || ( isset($row['settings']['bgVideoWebm']) && !empty($row['settings']['bgVideoWebm']) ) ){
					$additional_row_class .= " has-video-bg ";
					$row_video_bg = "<video class='video-background' autoplay loop poster='". $row['settings']['bgImage'] ."' id='bgvid'>
					<source src='". $row['settings']['bgVideoWebm'] ."' type='video/webm'>
						<source src='". $row['settings']['bgVideoMp'] ."' type='video/mp4'>
						</video>";
					}else{
						$row_video_bg = '';
					}

					if( isset($row['settings']['scrollDownButton']) && !empty($row['settings']['scrollDownButton']) && $row['settings']['scrollDownButton'] === 'yes' ){

						$additional_row_class .= " has-scroll-btn ";
						$row_scroll_btn = "<div class='ts-scroll-down-btn'>
						<a href='#' data-target='site-section' data-action='scroll'>
							<i class='icon-mouse-scroll'></i>
						</a>
					</div>";
				}else{
					$row_scroll_btn = '';
				}

				$div_mask = '';

				$sliderBackground = (isset($row['settings']['sliderBackground']) && (int)$row['settings']['sliderBackground'] > 0) ? $row['settings']['sliderBackground'] : 'no';

				// Add custom class if row has slider
				if( isset($row['settings']['sliderBackground']) && (int)$row['settings']['sliderBackground'] > 0 ){
					$additional_row_class .= " ts-has-bg-slider ";
				}

				if( $sliderBackground !== 'no' ){
					include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
					if( is_plugin_active('ts-custom-posts/ts-custom-posts.php') ){
						$div_mask .= self::slider_element(array('slider-id' => $sliderBackground), false);
					}
				}

				if( isset($row['settings']['rowMask']) && $row['settings']['rowMask'] == 'yes' ){
					if ( $opacity !== '' ) {
						$additional_row_class .= ' has-row-mask ';
						$row_mask_color = (isset($row['settings']['rowMaskColor'])) ? $row['settings']['rowMaskColor'] : '';
						$div_mask .= "<div class='row-mask' style='background-color:". $row_mask_color .";opacity:". $opacity ."'></div>";
					}
				}

				if( isset($row['settings']['rowMask']) && $row['settings']['rowMask'] == 'gradient' ){
					$gradient_color = (isset($row['settings']['rowMaskGradient']) && is_string($row['settings']['rowMaskGradient'])) ? $row['settings']['rowMaskGradient'] : '';
					$gradient_mode = (isset($row['settings']['gradientMaskMode']) && is_string($row['settings']['gradientMaskMode'])) ? $row['settings']['gradientMaskMode'] : '';
					$cssGradientMask = '';

					if( $gradient_mode == 'radial' ) {
						$cssGradientMask .= '
						background: '.$row['settings']['rowMaskColor'].';
						background: -moz-radial-gradient(center, ellipse cover,  '.$row['settings']['rowMaskColor'].' 0%,  '.$gradient_color.' 0%,  '.$row['settings']['rowMaskColor'].' 100%, '.$row['settings']['rowMaskColor'].' 100%);
						background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%,'.$row['settings']['rowMaskColor'].'), color-stop(0%, '.$gradient_color.'), color-stop(100%, '.$row['settings']['rowMaskColor'].'), color-stop(100%,'.$row['settings']['rowMaskColor'].'));
						background: -webkit-radial-gradient(center, ellipse cover,  '.$row['settings']['rowMaskColor'].' 0%, '.$gradient_color.' 0%, '.$row['settings']['rowMaskColor'].' 100%,'.$row['settings']['rowMaskColor'].' 100%);
						background: -o-radial-gradient(center, ellipse cover,  '.$row['settings']['rowMaskColor'].' 0%, '.$gradient_color.' 0%, '.$row['settings']['rowMaskColor'].' 100%,'.$row['settings']['rowMaskColor'].' 100%);
						background: -ms-radial-gradient(center, ellipse cover,  '.$row['settings']['rowMaskColor'].' 0%, '.$gradient_color.' 0%, '.$row['settings']['rowMaskColor'].' 100%,'.$row['settings']['rowMaskColor'].' 100%);
						background: radial-gradient(ellipse at center,  '.$row['settings']['rowMaskColor'].' 0%, '.$gradient_color.' 0%, '.$row['settings']['rowMaskColor'].' 100%,'.$row['settings']['rowMaskColor'].' 100%);
						';
					}elseif ( $gradient_mode == 'left-to-right' ) {
						$cssGradientMask .= '
						background: '.$row['settings']['rowMaskColor'].';
						background: -moz-linear-gradient(left, '.$row['settings']['rowMaskColor'].' 0%,  '.$gradient_color.' 0%, '.$row['settings']['rowMaskColor'].' 100%, '.$gradient_color.' 100%);
						background: -webkit-gradient(linear, left top, right top, color-stop(0%,'.$row['settings']['rowMaskColor'].'), color-stop(0%, '.$gradient_color.'), color-stop(100%,'.$row['settings']['rowMaskColor'].'), color-stop(100%,'.$gradient_color.'));
						background: -webkit-linear-gradient(left, '.$row['settings']['rowMaskColor'].' 0%, '.$gradient_color.' 0%,'.$row['settings']['rowMaskColor'].' 100%,'.$gradient_color.' 100%);
						background: -o-linear-gradient(left, '.$row['settings']['rowMaskColor'].' 0%, '.$gradient_color.' 0%,'.$row['settings']['rowMaskColor'].' 100%,'.$gradient_color.' 100%);
						background: -ms-linear-gradient(left, '.$row['settings']['rowMaskColor'].' 0%, '.$gradient_color.' 0%,'.$row['settings']['rowMaskColor'].' 100%,'.$gradient_color.' 100%);
						background: linear-gradient(to right, '.$row['settings']['rowMaskColor'].' 0%, '.$gradient_color.' 0%,'.$row['settings']['rowMaskColor'].' 100%,'.$gradient_color.' 100%);
						';
					}elseif ( $gradient_mode == 'corner-top' ) {
						$cssGradientMask .= '
						background: '.$row['settings']['rowMaskColor'].';
						background: -moz-linear-gradient(-45deg, '.$row['settings']['rowMaskColor'].' 0%,  '.vdf_hex2rgb($row['settings']['rowMaskColor'], 1).' 30%,  '.$gradient_color.' 100%);
						background: -webkit-gradient(linear, left top, right bottom, color-stop(0%,'.$row['settings']['rowMaskColor'].'), color-stop(30%, '.vdf_hex2rgb($row['settings']['rowMaskColor'], 1).'), color-stop(100%, '.$gradient_color.'));
						background: -webkit-linear-gradient(-45deg, '.$row['settings']['rowMaskColor'].' 0%, '.vdf_hex2rgb($row['settings']['rowMaskColor'], 1).' 30%, '.$gradient_color.' 100%);
						background: -o-linear-gradient(-45deg, '.$row['settings']['rowMaskColor'].' 0%, '.vdf_hex2rgb($row['settings']['rowMaskColor'], 1).' 30%, '.$gradient_color.' 100%);
						background: -ms-linear-gradient(-45deg, '.$row['settings']['rowMaskColor'].' 0%, '.vdf_hex2rgb($row['settings']['rowMaskColor'], 1).' 30%, '.$gradient_color.' 100%);
						background: linear-gradient(135deg, '.$row['settings']['rowMaskColor'].' 0%, '.vdf_hex2rgb($row['settings']['rowMaskColor'], 1).' 30%, '.$gradient_color.' 100%);
						';
					}elseif ( $gradient_mode == 'corner-bottom' ) {
						$cssGradientMask .= '
						background: '.$row['settings']['rowMaskColor'].';
						background: -moz-linear-gradient(45deg, '.$row['settings']['rowMaskColor'].' 0%,  '.vdf_hex2rgb($row['settings']['rowMaskColor'], 1).' 30%,  '.$gradient_color.' 100%);
						background: -webkit-gradient(linear, left bottom, right top, color-stop(0%,'.$row['settings']['rowMaskColor'].'), color-stop(30%, '.vdf_hex2rgb($row['settings']['rowMaskColor'], 1).'), color-stop(100%, '.$gradient_color.'));
						background: -webkit-linear-gradient(45deg, '.$row['settings']['rowMaskColor'].' 0%, '.vdf_hex2rgb($row['settings']['rowMaskColor'], 1).' 30%, '.$gradient_color.' 100%);
						background: -o-linear-gradient(45deg, '.$row['settings']['rowMaskColor'].' 0%, '.vdf_hex2rgb($row['settings']['rowMaskColor'], 1).' 30%, '.$gradient_color.' 100%);
						background: -ms-linear-gradient(45deg, '.$row['settings']['rowMaskColor'].' 0%, '.vdf_hex2rgb($row['settings']['rowMaskColor'], 1).' 30%, '.$gradient_color.' 100%);
						background: linear-gradient(45deg, '.$row['settings']['rowMaskColor'].' 0%, '.vdf_hex2rgb($row['settings']['rowMaskColor'], 1).' 30%, '.$gradient_color.' 100%);
						';
					}

					if( $opacity !== '' ){
						$cssGradientMask .= 'opacity:'. $opacity;
					}

					$additional_row_class .= ' has-row-mask ';
					$div_mask .= '<div class="row-mask" style="'. $cssGradientMask .'"></div>';
				}

				if( $vertical_align == 'bottom' ){
					$vertical_align_div_start = '<div class="row-align-bottom">';
					$vertical_align_div_end = '</div>';
				} else{
					$vertical_align_div_start = '';
					$vertical_align_div_end = '';
				}


				if ( self::is_expanded_row($row['settings']) ) {
					$row_container_start = '';
					$row_container_end = '';
					$additional_row_class .= ' ts-expanded-row ';
				} else {
					$row_container_start = $vertical_align_div_start . '<div class="container">';
					$row_container_end = $vertical_align_div_end . '</div>';
				}
				// Check if parallax is enabled
				if( isset($row['settings']['rowParallax']) && $row['settings']['rowParallax'] == 'yes' ){
					$row_parallax = 'yes';
					if( !in_array('parallax.image', $tsScripts) ){
						$tsScripts[] = 'parallax.image';
					}
				} else{
					$row_parallax = 'no';
				}

				$row_wrapper_start = '<div data-parallax="' . $row_parallax . '" data-scroll-btn="'. $scroll_down_btn .'" data-alignment="'. $vertical_align . '" ' . $row_name_id . ' class="site-section ' . $additional_row_class . '" '.self::row_settings($row['settings']).'>'.$div_mask;
				$row_wrapper_end = '</div>';

				$row_start = '<div class="row">';
				$row_end   = '</div>';

				if ( is_array( $row['columns'] ) && ! empty( $row['columns'] ) ) {
					$columns = array();

					foreach ( $row['columns'] as $column_index => $column ) {
						$elements = '';

						if ( is_array( $column['elements'] ) && ! empty( $column['elements'] ) ) {
							foreach ( $column['elements'] as $element_id => $element ) {
								$elements .=  self::compile_element($element, $row['settings']['specialEffects']);

								if( $element['type'] == 'menu' && isset($element['font-type']) && $element['font-type'] == 'google' ){

									$protocol = ( is_ssl() ) ? 'https' : 'http';
									$subsets = (isset($element['font-subsets']) && !empty($element['font-subsets'])) ? '&amp;subset=' . $element['font-subsets'] : '&amp;subset=latin';
									$fontName = (isset($element['font-name']) && !empty($element['font-name'])) ? $element['font-name'] : '';
									$fontName = str_replace(' ', '+', $fontName);

									wp_enqueue_style(
										$fontName,
										$protocol . '://fonts.googleapis.com/css?family='. $fontName . ':400,400italic,700' . $subsets,
										array(),
										VIDEOFLY_VERSION
										);
								}

								if( (isset($element['behavior']) && $element['behavior'] == 'masonry' && !in_array('isotope', $tsScripts)) || $element['type'] == 'filters' ){
									$tsScripts[] = 'isotope';
								}

								if( $element['type'] == 'featured-area' ){
									if( !in_array('flexslider', $tsScripts) )$tsScripts[] = 'flexslider';
									if( $element['custom-post'] == 'video' && $element['play'] == 'modal' && !in_array('fancybox.pack', $tsScripts) ) $tsScripts[] = 'fancybox.pack';
								}

								if( isset($element['effect']) && $element['effect'] !== 'none' && !in_array('css3-animations', $tsScripts) ){
									$tsScripts['css3-animations'] = 'css3-animations';
								}

								if ( isset($element['play']) && $element['play'] == 'modal' && !in_array( 'fancybox.pack', $tsScripts ) ) {
									$tsScripts[] = 'fancybox.pack';
								}

								if( isset($element['timeline']) && !in_array('css3-animations', $tsScripts) && preg_match('/"effect":"[^n][a-z]+"/i', $element['timeline']) ){
									$tsScripts['css3-animations'] = 'css3-animations';
								}

								if( isset($element['features-block']) && !in_array('css3-animations', $tsScripts) && preg_match('/"effect":"[^n][a-z]+"/i', $element['features-block']) ){
									$tsScripts['css3-animations'] = 'css3-animations';
								}

								if( isset($element['behavior']) && $element['behavior'] == 'scroll' && !in_array('mCustomScrollbar', $tsScripts) ){
									$tsScripts[] = 'mCustomScrollbar';
								}

								if( isset($element['display-mode']) && $element['display-mode'] == 'mosaic' && $element['scroll'] == 'y' && !in_array('mCustomScrollbar', $tsScripts)  ){
									$tsScripts[] = 'mCustomScrollbar';
								}

								if( !in_array($element['type'], $tsScripts) && in_array($element['type'], $elementsWithScripts) ){
									$tsScripts[] = $element['type'];
								}

								if( $element['type'] == 'video' ){
									if( !in_array('fancybox.pack', $tsScripts) ){
										$tsScripts[] = 'fancybox.pack';
									}
								}

								if( $element['type'] == 'boca' || $element['type'] == 'nona' ){
									if( !in_array('slick', $tsScripts) ){
										$tsScripts[] = 'slick';
									}
								}

							}
						}

						$idColumn = (isset($column['settingsColumn']['columnName']) && !empty($column['settingsColumn']['columnName'])) ? ' id="ts_' . esc_attr($column['settingsColumn']['columnName']) . '"' : '';
						$classSize = self::$columns[$column['size']];
						$columnOptions = (isset($column['settingsColumn'])) ? $column['settingsColumn'] : array();

						$columns[] = self::addStyleColumn($idColumn, $classSize, $columnOptions, $elements);

					}
				}

				$columns = isset($columns) ? $columns : array();

				if( $columnCarousel == 'yes' ){
					$carousel_wrapper_start = '<div class="carousel-wrapper">';
					$carousel_wrapper_end = '</div>';

					$carousel_container_start = '<div class="carousel-overview"><div class="carousel-container">';
					$carousel_container_end = '</div></div>';

					$carousel_navigation = '<ul class="carousel-nav">
					<li class="carousel-nav-left icon-left">
						<span class="hidden_btn">'.esc_html__('prev','videofly').'</span>
					</li>
					<li class="carousel-nav-right icon-right">
						<span class="hidden_btn">'.esc_html__('next','videofly').'</span>
					</li>
				</ul>';

				$compiled_rows[] = 	$row_wrapper_start . $row_video_bg . $row_scroll_btn .
				$row_container_start .
				$row_start .
				$carousel_wrapper_start . $carousel_navigation .
				$carousel_container_start .
				implode("\n", $columns) .
				$carousel_container_end .
				$carousel_wrapper_end .
				$row_end .
				$row_container_end .
				$row_wrapper_end;

			}else{
				$compiled_rows[] = $row_wrapper_start . $row_video_bg . $row_scroll_btn . $row_container_start . $row_start . implode("\n", $columns) . $row_end . $row_container_end . $row_wrapper_end;
			}
		}

		tsIncludeScripts($tsScripts);

		return implode("\n", $compiled_rows);
	}
}

public static function addStyleColumn($idColumn, $classSize, $arraySettings = array(), $elements){

	$additionalColumnClass = '';

	$transparent = (isset($arraySettings['transparent']) && ($arraySettings['transparent'] == 'y' || $arraySettings['transparent'] == 'n')) ? $arraySettings['transparent'] : 'n';
	$bgColor = (isset($arraySettings['bgColor']) && $transparent == 'n') ? 'background-color: ' . esc_attr($arraySettings['bgColor']) . ';' : 'background-color: transparent;';

	$textColor = (isset($arraySettings['textColor']) && !empty($arraySettings['textColor']) && is_string($arraySettings['textColor'])) ? 'color: ' . esc_attr($arraySettings['textColor']) . ';' : 'color: inherit;';

	$enableMask = (isset($arraySettings['columnMask']) && ($arraySettings['columnMask'] == 'yes' || $arraySettings['columnMask'] == 'no')) ? $arraySettings['columnMask'] : 'no';
	$maskColor = (isset($arraySettings['columnMaskColor']) && !empty($arraySettings['columnMaskColor']) && is_string($arraySettings['columnMaskColor']) && $enableMask == 'yes') ? esc_attr($arraySettings['columnMaskColor']) : 'inherit';
	$columnOpacity = (isset($arraySettings['columnOpacity']) && absint($arraySettings['columnOpacity']) > 0) ? absint($arraySettings['columnOpacity']) / 100 : 0;

	$divMask = '';
	if( $enableMask == 'yes' && $columnOpacity !== '' ){
		$additionalColumnClass .= ' has-column-mask ';
		$divMask = "<div class='column-mask' style='background-color:" . $maskColor . "; opacity:" . $columnOpacity . "'></div>";
	}

	$bgImage = (isset($arraySettings['bgImage']) && !empty($arraySettings['bgImage']) && is_string($arraySettings['bgImage'])) ? 'background-image: url(' . esc_url($arraySettings['bgImage']) . ');' : '';

	$bgVideoMp = (isset($arraySettings['bgVideoMp']) && !empty($arraySettings['bgVideoMp']) && is_string($arraySettings['bgVideoMp'])) ? "<source src='" . esc_url($arraySettings['bgVideoMp']) . "' type='video/mp4'>" : '';
	$bgVideoWebm = (isset($arraySettings['bgVideoWebm']) && !empty($arraySettings['bgVideoWebm']) && is_string($arraySettings['bgVideoWebm'])) ? "<source src='" . esc_url($arraySettings['bgVideoWebm']) . "' type='video/webm'>" : '';
	$columnVideoBg = '';

	if( !empty($bgVideoWebm) || !empty($bgVideoMp) ){
			$columnVideoBg = "<video class='video-background' autoplay loop poster='" . $arraySettings['bgImage'] . "' id='bgvid'>
			" . $bgVideoWebm . "
			" . $bgVideoMp . "
		</video>";
	}

$bgPosition = (isset($arraySettings['bgPosition']) && ($arraySettings['bgPosition'] == 'left' || $arraySettings['bgPosition'] == 'right' || $arraySettings['bgPosition'] == 'center')) ? 'background-position: ' . $arraySettings['bgPosition'] . ';' : 'background-position: left;';
$bgAttachement = (isset($arraySettings['bgAttachement']) && ($arraySettings['bgAttachement'] == 'scroll' || $arraySettings['bgAttachement'] == 'fixed')) ? 'background-attachment: ' . $arraySettings['bgAttachement'] . ';' : 'background-attachment: scroll;';
$bgRepeat = (isset($arraySettings['bgRepeat']) && ($arraySettings['bgRepeat'] == 'repeat' || $arraySettings['bgRepeat'] == 'no-repeat' || $arraySettings['bgRepeat'] == 'repeat-x' || $arraySettings['bgRepeat'] == 'repeat-y')) ? 'background-repeat: ' . $arraySettings['bgRepeat'] . ';' : 'background-repeat: repeat;';
$bgSize = (isset($arraySettings['bgSize']) && ($arraySettings['bgSize'] == 'auto' || $arraySettings['bgSize'] == 'cover')) ? 'background-size: ' . $arraySettings['bgSize'] . ';' : 'background-size: auto;';
$paddingTop = (isset($arraySettings['columnPaddingTop']) && absint($arraySettings['columnPaddingTop']) > 0) ? 'padding-top: ' . absint($arraySettings['columnPaddingTop']) . 'px;' : 'padding-top: 0px;';
$paddingRight = (isset($arraySettings['columnPaddingRight']) && is_numeric($arraySettings['columnPaddingRight'])) ? 'padding-right: ' . absint($arraySettings['columnPaddingRight']) . 'px;' : 'padding-right: 0;';
$paddingLeft = (isset($arraySettings['columnPaddingLeft']) && absint($arraySettings['columnPaddingLeft']) > 0) ? 'padding-left: ' . absint($arraySettings['columnPaddingLeft']) . 'px;' : 'padding-left: 0;';
$textAlign = (isset($arraySettings['columnTextAlign']) && ($arraySettings['columnTextAlign'] == 'left' || $arraySettings['columnTextAlign'] == 'center' || $arraySettings['columnTextAlign'] == 'right' || $arraySettings['columnTextAlign'] == 'auto')) ? 'text-align: ' . $arraySettings['columnTextAlign'] . ';' : 'text-align: auto;';
$paddingBottom = (isset($arraySettings['columnPaddingBottom']) && absint($arraySettings['columnPaddingBottom']) > 0) ? 'padding-bottom: ' . absint($arraySettings['columnPaddingBottom']) . 'px;' : 'padding-bottom: 0px;';
$gutterLeft = (isset($arraySettings['gutterLeft']) && is_numeric($arraySettings['gutterLeft'])) ? 'padding-left: ' . absint($arraySettings['gutterLeft']) . 'px;' : 'padding-left: 20px;';
$gutterRight = (isset($arraySettings['gutterRight']) && is_numeric($arraySettings['gutterRight'])) ? 'padding-right: ' . absint($arraySettings['gutterRight']) . 'px;' : 'padding-right: 20px;';


if( isset($arraySettings['columnMask']) && $arraySettings['columnMask'] == 'gradient' ){

	$gradient_color = (isset($arraySettings['maskGradient']) && is_string($arraySettings['maskGradient'])) ? $arraySettings['maskGradient'] : '';
	$gradient_mode = (isset($arraySettings['gradientMaskMode']) && is_string($arraySettings['gradientMaskMode'])) ? $arraySettings['gradientMaskMode'] : '';
	$cssGradientMask = '';

	if( $gradient_mode == 'radial' ) {
		$cssGradientMask .= '
		background: '.$arraySettings['columnMaskColor'].';
		background: -moz-radial-gradient(center, ellipse cover,  '.$arraySettings['columnMaskColor'].' 0%,  '.$gradient_color.' 0%,  '.$arraySettings['columnMaskColor'].' 100%, '.$arraySettings['columnMaskColor'].' 100%);
		background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%,'.$arraySettings['columnMaskColor'].'), color-stop(0%, '.$gradient_color.'), color-stop(100%, '.$arraySettings['columnMaskColor'].'), color-stop(100%,'.$arraySettings['columnMaskColor'].'));
		background: -webkit-radial-gradient(center, ellipse cover,  '.$arraySettings['columnMaskColor'].' 0%, '.$gradient_color.' 0%, '.$arraySettings['columnMaskColor'].' 100%,'.$arraySettings['columnMaskColor'].' 100%);
		background: -o-radial-gradient(center, ellipse cover,  '.$arraySettings['columnMaskColor'].' 0%, '.$gradient_color.' 0%, '.$arraySettings['columnMaskColor'].' 100%,'.$arraySettings['columnMaskColor'].' 100%);
		background: -ms-radial-gradient(center, ellipse cover,  '.$arraySettings['columnMaskColor'].' 0%, '.$gradient_color.' 0%, '.$arraySettings['columnMaskColor'].' 100%,'.$arraySettings['columnMaskColor'].' 100%);
		background: radial-gradient(ellipse at center,  '.$arraySettings['columnMaskColor'].' 0%, '.$gradient_color.' 0%, '.$arraySettings['columnMaskColor'].' 100%,'.$arraySettings['columnMaskColor'].' 100%);
		';
	}elseif ( $gradient_mode == 'left-to-right' ) {
		$cssGradientMask .= '
		background: '.$arraySettings['columnMaskColor'].';
		background: -moz-linear-gradient(left, '.$arraySettings['columnMaskColor'].' 0%,  '.$gradient_color.' 0%, '.$arraySettings['columnMaskColor'].' 100%, '.$gradient_color.' 100%);
		background: -webkit-gradient(linear, left top, right top, color-stop(0%,'.$arraySettings['columnMaskColor'].'), color-stop(0%, '.$gradient_color.'), color-stop(100%,'.$arraySettings['columnMaskColor'].'), color-stop(100%,'.$gradient_color.'));
		background: -webkit-linear-gradient(left, '.$arraySettings['columnMaskColor'].' 0%, '.$gradient_color.' 0%,'.$arraySettings['columnMaskColor'].' 100%,'.$gradient_color.' 100%);
		background: -o-linear-gradient(left, '.$arraySettings['columnMaskColor'].' 0%, '.$gradient_color.' 0%,'.$arraySettings['columnMaskColor'].' 100%,'.$gradient_color.' 100%);
		background: -ms-linear-gradient(left, '.$arraySettings['columnMaskColor'].' 0%, '.$gradient_color.' 0%,'.$arraySettings['columnMaskColor'].' 100%,'.$gradient_color.' 100%);
		background: linear-gradient(to right, '.$arraySettings['columnMaskColor'].' 0%, '.$gradient_color.' 0%,'.$arraySettings['columnMaskColor'].' 100%,'.$gradient_color.' 100%);
		';
	}elseif ( $gradient_mode == 'corner-top' ) {
		$cssGradientMask .= '
		background: '.$arraySettings['columnMaskColor'].';
		background: -moz-linear-gradient(-45deg, '.$arraySettings['columnMaskColor'].' 0%,  '.vdf_hex2rgb($arraySettings['columnMaskColor'], 1).' 30%,  '.$gradient_color.' 100%);
		background: -webkit-gradient(linear, left top, right bottom, color-stop(0%,'.$arraySettings['columnMaskColor'].'), color-stop(30%, '.vdf_hex2rgb($arraySettings['columnMaskColor'], 1).'), color-stop(100%, '.$gradient_color.'));
		background: -webkit-linear-gradient(-45deg, '.$arraySettings['columnMaskColor'].' 0%, '.vdf_hex2rgb($arraySettings['columnMaskColor'], 1).' 30%, '.$gradient_color.' 100%);
		background: -o-linear-gradient(-45deg, '.$arraySettings['columnMaskColor'].' 0%, '.vdf_hex2rgb($arraySettings['columnMaskColor'], 1).' 30%, '.$gradient_color.' 100%);
		background: -ms-linear-gradient(-45deg, '.$arraySettings['columnMaskColor'].' 0%, '.vdf_hex2rgb($arraySettings['columnMaskColor'], 1).' 30%, '.$gradient_color.' 100%);
		background: linear-gradient(135deg, '.$arraySettings['columnMaskColor'].' 0%, '.vdf_hex2rgb($arraySettings['columnMaskColor'], 1).' 30%, '.$gradient_color.' 100%);
		';
	}elseif ( $gradient_mode == 'corner-bottom' ) {
		$cssGradientMask .= '
		background: '.$arraySettings['columnMaskColor'].';
		background: -moz-linear-gradient(45deg, '.$arraySettings['columnMaskColor'].' 0%,  '.vdf_hex2rgb($arraySettings['columnMaskColor'], 1).' 30%,  '.$gradient_color.' 100%);
		background: -webkit-gradient(linear, left bottom, right top, color-stop(0%,'.$arraySettings['columnMaskColor'].'), color-stop(30%, '.vdf_hex2rgb($arraySettings['columnMaskColor'], 1).'), color-stop(100%, '.$gradient_color.'));
		background: -webkit-linear-gradient(45deg, '.$arraySettings['columnMaskColor'].' 0%, '.vdf_hex2rgb($arraySettings['columnMaskColor'], 1).' 30%, '.$gradient_color.' 100%);
		background: -o-linear-gradient(45deg, '.$arraySettings['columnMaskColor'].' 0%, '.vdf_hex2rgb($arraySettings['columnMaskColor'], 1).' 30%, '.$gradient_color.' 100%);
		background: -ms-linear-gradient(45deg, '.$arraySettings['columnMaskColor'].' 0%, '.vdf_hex2rgb($arraySettings['columnMaskColor'], 1).' 30%, '.$gradient_color.' 100%);
		background: linear-gradient(45deg, '.$arraySettings['columnMaskColor'].' 0%, '.vdf_hex2rgb($arraySettings['columnMaskColor'], 1).' 30%, '.$gradient_color.' 100%);
		';
	}

	if( $columnOpacity !== '' ){
		$cssGradientMask .= 'opacity:'. $columnOpacity;
	}

	$additionalColumnClass .= ' has-row-mask ';
	$divMask = '<div class="row-mask" style="'. $cssGradientMask .'"></div>';
}

$styleColumn = 'style="'. $gutterRight .' '. $gutterLeft .'"';

$style = 	'style="' .
$bgColor .
$textColor .
$bgImage .
$bgPosition .
$bgAttachement .
$bgRepeat .
$bgSize .
$paddingTop .
$paddingRight .
$paddingLeft .
$paddingBottom .
$textAlign . '"';

return $columnVideoBg . '<div '. $styleColumn . $idColumn . ' class="' . $classSize . $additionalColumnClass . '">
<div '. $style .'>
	' . $divMask . '
	' . $elements . '
</div>
</div>';

}

public static function compile_element($element = array(), $effect = 'none')
{

	switch ($element['type']) {
		case 'logo':
		$e = self::logo_element($element);
		break;

		case 'cart':
		$e = self::cart_element($element);
		break;

		case 'breadcrumbs':
		$e = self::breadcrumbs_element($element);
		break;

		case 'featured-article':
		$e = self::featured_article_element($element);
		break;

		case 'menu':
		$e = self::menu_element($element);
		break;

		case 'delimiter':
		$e = self::delimiter_element($element);
		break;

		case 'title':
		$e = self::title_element($element);
		break;

		case 'sidebar':
		$e = self::sidebar_element($element);
		break;

		case 'social-buttons':
		$e = self::social_buttons_element($element);
		break;

		case 'list-portfolios':
		$e = self::list_portfolios_element($element);
		break;

		case 'searchbox':
		$e = self::searchbox_element($element);
		break;

		case 'teams':
		$e = self::teams_element($element);
		break;

		case 'pricing-tables':
		$e = self::pricing_tables_element($element);
		break;

		case 'list-products':
		$e = self::list_products_element($element);
		break;

		case 'testimonials':
		$e = self::testimonials_element($element);
		break;

		case 'slider':
		$e = self::slider_element($element);
		break;

		case 'last-posts':
		$e = self::last_posts_element($element);
		break;

		case 'list-galleries':
		$e = self::list_galleries_element($element);
		break;

		case 'latest-custom-posts':
		$e = self::latest_custom_posts_element($element);
		break;

		case 'callaction':
		$e = self::callaction_element($element);
		break;

		case 'advertising':
		$e = self::advertising_element($element);
		break;

		case 'empty':
		$e = self::empty_element($element);
		break;

		case 'video':
		$e = self::video_element($element);
		break;

		case 'counters':
		$e = self::counter_element($element);
		break;

		case 'image':
		$e = self::image_element($element);
		break;

		case 'facebook-block':
		$e = self::facebook_block_element($element);
		break;

		case 'filters':
		$e = self::filters_element($element);
		break;

		case 'features-block':
		$e = self::feature_blocks_element($element);
		break;

		case 'listed-features':
		$e = self::listed_feature_element($element);
		break;

		case 'clients':
		$e = self::clients_element($element);
		break;

		case 'spacer':
		$e = self::spacer_element($element);
		break;

		case 'icon':
		$e = self::icon_element($element);
		break;

		case 'post':
		$e = self::post_element($element);
		break;

		case 'page':
		$e = self::page_element($element);
		break;

		case 'buttons':
		$e = self::buttons_element($element);
		break;

		case 'contact-form':
		$e = self::contact_form_element($element);
		break;

		case 'featured-area':
		$e = self::featured_area_element($element);
		break;

		case 'image-carousel':
		$e = self::image_carousel_element($element);
		break;

		case 'shortcodes':
		$e = self::shortcodes_element($element);
		break;

		case 'text':
		$e = self::text_element($element);
		break;

		case 'map':
		$e = self::map_element($element);
		break;

		case 'banner':
		$e = self::banner_element($element);
		break;

		case 'tab':
		$e = self::tab_element($element);
		break;

		case 'toggle':
		$e = self::toggle_element($element);
		break;

		case 'timeline':
		$e = self::timeline_element($element);
		break;

		case 'ribbon':
		$e = self::ribbon_element($element);
		break;

		case 'list-videos':
		$e = self::list_videos_element($element);
		break;

		case 'video-carousel':
		$e = self::video_carousel_element($element);
		break;

		case 'count-down':
		$e = self::count_down_element($element);
		break;

		case 'powerlink':
		$e = self::powerlink_element($element);
		break;

		case 'calendar':
		$e = self::calendar_element($element);
		break;

		case 'events':
		$e = self::events_element($element);
		break;

		case 'alert':
		$e = self::alert_element($element);
		break;

		case 'skills':
		$e = self::skills_element($element);
		break;

		case 'accordion':
		$e = self::accordion_element($element);
		break;

		case 'chart':
		$e = self::chart_element($element);
		break;

		case 'gallery':
		$e = self::gallery_element($element);
		break;

		case 'user':
		$e = self::user_element($element);
		break;

		case 'instance':
		$e = self::instance_element($element);
		break;

		case 'boca':
		case 'nona':
		$e = self::boca_nona_element($element);
		break;

		default:
		$e = '';
		break;
	}

	return '<div class="row content-block '.self::special_effect($effect).'">' . $e . '</div>';
}

	/**
	 * This function return the class used for animation effect
	 * @param  array $effect
	 * @return string
	 */
	public static function special_effect($effect)
	{
		if ($effect === 'none') {
			return '';
		} else {
			return ' animated ' . $effect;
		}
	}

	public static function build_header()
	{
		global $post;

		$lang = defined( 'ICL_LANGUAGE_CODE' ) ? '_' . ICL_LANGUAGE_CODE : '';

		$header = get_option( 'videofly_header' . $lang, array() );

		$header = defined( 'ICL_LANGUAGE_CODE' ) && empty( $header ) ? get_option( 'videofly_header', array() ) : $header;

		if ( isset( $post->post_type ) && $post->post_type === 'page' ) {

			$h = get_post_meta( $post->ID, 'ts_header_and_footer', true );

			if ( $h && $h['disable_header'] == 1 ) return;

		}

		$elements = self::build_content( $header );

		return $elements;
	}

	public static function build_footer()
	{
		global $post;

		$lang = defined( 'ICL_LANGUAGE_CODE' ) ? '_' . ICL_LANGUAGE_CODE : '';

		$footer = get_option( 'videofly_footer' . $lang, array() );

		$footer = defined( 'ICL_LANGUAGE_CODE' ) && empty( $footer ) ? get_option( 'videofly_footer', array() ) : $footer;

		if ( isset( $post->post_type ) && $post->post_type === 'page' ) {

			$f = get_post_meta( $post->ID, 'ts_header_and_footer', true );

			if ( $f && $f['disable_footer'] == 1 ) return;
		}

		$elements = self::build_content( $footer );

		return $elements;
	}


	public static function is_expanded_row($settings) {

		if (isset($settings['expandRow'])) {
			if ($settings['expandRow'] === 'no') {
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	}

	public static function is_fullscreen_row($settings) {

		if (isset($settings['fullscreenRow'])) {
			if ($settings['fullscreenRow'] === 'no') {
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	}

	/**
	 * Rendering style attribute for the row
	 * @param  array  $settings Row settings
	 * @return string
	 */
	public static function row_settings($settings = array())
	{
		$open_attr = ' style="';
		$css = '';
		$close_attr = '" ';

		$transparent = (isset($settings['transparent']) && ($settings['transparent'] == 'y' || $settings['transparent'] == 'n')) ? $settings['transparent'] : 'n';
		$bgColor = (isset($settings['bgColor']) && $transparent == 'n') ? 'background-color: ' . esc_attr($settings['bgColor']) . ';' : 'background-color: transparent;';

		$css .= $bgColor;
		$css .= ' color: ' . $settings['textColor'] . '; ';

		/* style row border */
		$borderTop = (isset($settings['borderTop'])) ? $settings['borderTop'] : 'n';
		$borderBottom = (isset($settings['borderBottom'])) ? $settings['borderBottom'] : 'n';

		if( $borderTop == 'y' ){
			$borderTopColor = (isset($settings['borderTopColor']) && !empty($settings['borderTopColor'])) ? $settings['borderTopColor'] : '#fff';
			$borderTopWidth = (isset($settings['borderTopWidth']) && !empty($settings['borderTopWidth'])) ? $settings['borderTopWidth'] : 3;
			$css .= 'border-top: '. $borderTopWidth . 'px solid '. $borderTopColor . ';';
		}
		if( $borderBottom == 'y' ){
			$borderBottomColor = (isset($settings['borderBottomColor']) && !empty($settings['borderBottomColor'])) ? $settings['borderBottomColor'] : '#fff';
			$borderBottomWidth = (isset($settings['borderBottomWidth']) && !empty($settings['borderBottomWidth'])) ? $settings['borderBottomWidth'] : 3;
			$css .= 'border-bottom: '. $borderBottomWidth . 'px solid '. $borderBottomColor . ';';
		}
		/* end style row border */

		if ($settings['bgImage'] !== '') {

			$css .= " background-image:url('" . $settings['bgImage'] . "') " .  '; ';

			if ( isset($settings['bgPositionX']) && isset($settings['bgPositionY']) ) {
				$css .= " background-position: ". $settings['bgPositionX'] ." ". $settings['bgPositionY'] ."; ";
			}

			if ($settings['bgAttachement'] !== '') {
				$css .= " background-attachment: ". $settings['bgAttachement'] ."; ";
			}

			if ($settings['bgRepeat'] !== '') {
				$css .= " background-repeat: ".$settings['bgRepeat']."; ";
			}
		}
		$css .= " margin-top: " . $settings['rowMarginTop'] . "px; ";
		$css .= " margin-bottom: " . $settings['rowMarginBottom'] . "px; ";
		$css .= " padding-top: " . $settings['rowPaddingTop'] . "px; ";
		$css .= " padding-bottom: " . $settings['rowPaddingBottom'] . "px; ";

		if ( isset($settings['rowTextAlign']) ) {
			if( $settings['rowTextAlign'] !== 'auto' ){
				$css .= " text-align: " . $settings['rowTextAlign'] . "; ";
			}
		} else {
			$settings['rowTextAlign'] = 'auto';
		}

		if ( isset($settings['bgSize']) ) {
			if( $settings['bgSize'] !== 'auto' ) {
				$css .= " background-size: " . $settings['bgSize'] . "; ";
			}
		} else {
			$settings['bgSize'] = 'auto';
		}

		$css .= (isset($settings['customCss'])) ? strip_tags($settings['customCss']) : '';

		$css = (trim($css) === '') ? '' : $open_attr . $css . $close_attr;

		return $css;
	}

	public static function logo_element($options = array())
	{
		$align_logo = ( isset($options['logo-align']) ) ? strip_tags($options['logo-align']) : '';
		return '<div class="col-lg-12 '. $align_logo .'"><a href="'. esc_url( home_url('/') ) . '" class="logo">
		' . vdf_get_logo() . '
	</a></div>';
}

public static function cart_element($options = array())
{
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
		global $woocommerce;
		$cart_code = '<div class="col-lg-12 col-md-12 col-sm-12">
		<div class="woocommerce gbtr_dynamic_shopping_bag">
			<div class="gbtr_little_shopping_bag_wrapper">
				<div class="gbtr_little_shopping_bag">
					<div class="overview">
						<span class="minicart_items ';
						if($woocommerce->cart->cart_contents_count == 0){ $cart_code .= 'no-items'; };
						$cart_code .= '"><i class="icon-basket"></i>';
						$cart_code .= sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'videofly'), $woocommerce->cart->cart_contents_count).'</span>
						<span class="minicart_total">'. $woocommerce->cart->get_cart_total() .'</span>
					</div>
				</div>
				<div class="gbtr_minicart_wrapper">
					<h4>'. esc_html__('My shopping basket', 'videofly') . '</h4>
					<div class="gbtr_minicart">
						<ul class="cart_list">';
							if ( sizeof($woocommerce->cart->cart_contents) > 0 ) :
								foreach ($woocommerce->cart->cart_contents as $cart_item_key => $cart_item) :
									$_product = $cart_item['data'];
								if ($_product->exists() && $cart_item['quantity']>0) :
									$cart_code .='<li class="cart_list_product">
								<a class="cart_list_product_img" href="'.get_permalink($cart_item['product_id']).'"> ' . $_product->get_image().'</a>
								<div class="cart_list_product_title">';
									$gbtr_product_title = $_product->get_title();
									$gbtr_short_product_title = (strlen($gbtr_product_title) > 28) ? substr($gbtr_product_title, 0, 25) . '...' : $gbtr_product_title;
									$cart_code .= apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="remove" title="%s">&times;</a>', esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ), esc_html__('Remove this item', 'videofly') ), $cart_item_key ) . '<a href="'.get_permalink($cart_item['product_id']). '" class="cart-item-title">' . apply_filters('woocommerce_cart_widget_product_title', $gbtr_short_product_title, $_product) . '</a><span class="cart_list_product_quantity"> ('.$cart_item['quantity'].')</span>' . '<span class="cart_list_product_price">'.woocommerce_price($_product->get_price()).'</span>
								</div>
								<div class="clr"></div>
							</li>';
							endif;
							endforeach;
							$cart_code .= ' <li class="minicart_total_checkout">
							<div>'. esc_html__('Cart subtotal:', 'videofly') .' </div>
							<span>'. $woocommerce->cart->get_cart_total() .'</span>
						</li>
						<li class="clr">
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6">
									<a href="'. esc_url( $woocommerce->cart->get_cart_url() ) .'" class="button gbtr_minicart_cart_but">'. esc_html__('View Cart', 'videofly') .'</a>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6">
									<a href="'. esc_url( $woocommerce->cart->get_checkout_url() ) .'" class="button gbtr_minicart_checkout_but">'. esc_html__('Checkout', 'videofly') .'</a>
								</div>
							</div>
						</li>';
						else:
							$cart_code .= '<li class="empty">' .esc_html__('No products in the cart.','videofly').'</li>';
						endif;
						$cart_code .= '</ul>
					</div>
				</div>
			</div>
		</div>
	</div>';
	return $cart_code;
}
}

public static function menu_element($options = array())
{
	$menu = '';
	$mobile_menu = '';
	global $article_options;
	$article_options = $options;

	$mega_menu = get_option('videofly_general');
	$enable_mega_menu = (isset($mega_menu['enable_mega_menu']) && $mega_menu['enable_mega_menu'] == 'Y') ? new ts_responsive_mega_menu : '';
	$uppercase = (isset($options['uppercase']) && !empty($options['uppercase']) && ($options['uppercase'] === 'menu-uppercase' || $options['uppercase'] === 'menu-no-uppercase') && $options['uppercase'] === 'menu-uppercase') ? 'text-uppercase' : '';
	$menu_by_id = (isset($options['name']) && (int)$options['name'] !== 0) ? $options['name'] : NULL;
	$icons = (isset($options['icons']) && ($options['icons'] == 'y' || $options['icons'] == 'n')) ? $options['icons'] : 'n';
	$description = (isset($options['description']) && ($options['description'] == 'y' || $options['description'] == 'n')) ? $options['description'] : 'n';
	$font_type = (isset($options['font-type']) && ($options['font-type'] == 'std' || $options['font-type'] == 'google')) ? $options['font-type'] : 'std';

	$menu_styles = array(
		'style1' => 'ts-standard-menu',
		'style2' => 'ts-vertical-menu',
		'style4' => 'ts-vertical-menu',
		);

	$element_style = array_key_exists($options['element-style'], $menu_styles) ? $menu_styles[$options['element-style']] : 'sf-simplemenu';
	$menu_style = '';

	$menu_id = 'menu-element-' . rand(321,32132213);
	$mobile_menu_id = 'mobile-menu-element-'.rand(11,392132213);
	$menu_text_align = '';
	$menu_text_align = (isset($options['menu-text-align'])) ? @$options['menu-text-align'] : '';
	$menu_with_logo = ($options['element-style'] == 'style3') ? 'menu-with-logo' : '';

	if( @$options['menu-custom'] == 'y' ){
		$custom_menu_class = 'ts-custom-menu-colors';
	} else{
		$custom_menu_class = '';
	}

	if(fields::get_options_value('videofly_general', 'enable_mega_menu') == 'Y'){
		$menu_style .= 'ts-mega-menu';
		if($options['element-style'] == 'style4'){
			$menu_style .= ' ts-sidebar-menu ';
		}
	}elseif (isset($options['element-style']) && !empty($options['element-style'])) {
		if($options['element-style'] == 'style1'){
			$menu_style .= 'ts-standard-menu';
		}elseif($options['element-style'] == 'style2'){
			$menu_style .= 'ts-vertical-menu';
		}elseif($options['element-style'] == 'style4'){
			$menu_style .= ' menu-trigger ts-sidebar-menu';
		}else{
			$menu_style .= 'ts-standard-menu';
		}
	}

	if ( isset($menu_by_id) || has_nav_menu('primary') ) {

		$locations = get_theme_mod('nav_menu_locations');
		$menu_by_id = (isset($menu_by_id)) ? $menu_by_id : $locations['primary'];

		ob_start();
		wp_nav_menu(array(
			'theme_location'  => 'primary',
			'menu'            => $menu_by_id,
			'container'       => 'nav',
			'container_class' => 'ts-header-menu '.$menu_style.' '.$menu_text_align.' '.$menu_with_logo.' '.$uppercase.' '.$menu_id . ' '.$custom_menu_class,
			'container_id'    => 'nav',
			'menu_class'	  => 'main-menu ',
			'menu_id'         => 'menu-main-header',
			'echo'            => true,
			'fallback_cb'     => 'vdf_menuCallback',
			'before'          => '',
			'after'           => '',
			'link_before'     => '',
			'link_after'      => '',
			'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
			'depth'           => 0,
			'walker'          => $enable_mega_menu
			));

		$menu = ob_get_contents();
		ob_end_clean();

		ob_start();
		wp_nav_menu(array(
			'theme_location'  => 'primary',
			'menu'            => $menu_by_id,
			'container'       => 'div',
			'container_class' => 'mobile_menu ',
			'container_id'    => '',
			'menu_class'	  => 'main-menu ',
			'menu_id'         => 'menu-main-header-mobile',
			'echo'            => true,
			'fallback_cb'     => 'vdf_menuCallback',
			'before'          => '',
			'after'           => '',
			'link_before'     => '',
			'link_after'      => '',
			'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
			'depth'           => 0,
			'walker'          => ''
			));

		$mobile_menu = ob_get_contents();
		ob_end_clean();

	} else {

		ob_start();
		vdf_menuCallback();

		$menu = '<nav id="nav" data-alignment="'. $menu_text_align .'" class=" '. $menu_text_align .'">' . ob_get_contents() . '</nav>';
		ob_end_clean();

		ob_start();
		wp_page_menu(array(
			'container'       => '',
			'container_class' => '',
			'container_id'    => $mobile_menu_id,
			'menu_class' => 'slicknav_nav_main',
			'menu_id' => ''
			));

		$mobile_menu  = '<div class="slicknav_nav_mobile">' . ob_get_contents() . '</div>';
		ob_end_clean();
	}

		// Check if sidebar menu
	if ( $article_options['element-style'] == 'style4' ) {
		$menu .= 	'<a href="#" class="trigger-menu open-menu ' . $menu_text_align . '">
		<span class="icon-menu"></span>
	</a>';

			$styleSidebarMenu = '
				#nav.ts-sidebar-menu,
			.ts-sidebar-menu.ts-custom-menu-colors .sub-menu{
				background-color: '. $options['menu-bg-color'] .';
			}
			.ts-sidebar-menu .menu-item-has-children a:before{
				background-color: '. @$options["submenu-bg-color-hover"].';
				opacity: 0.3;
			}
			.ts-sidebar-menu .menu-item-has-children a:after{
				background-color: '. @$options["submenu-bg-color-hover"].';
			}
			.ts-sidebar-menu ul.main-menu li.menu-item a,
			.ts-sidebar-menu [class*="ts_is_mega_menu_columns"] h4,
			.ts-sidebar-menu .main-menu li.menu-item-has-description .mega-menu-item-description,
			.ts-sidebar-menu ul.main-menu > li > .sub-menu .sub-menu--back,
			.ts-sidebar-menu .main-menu li > i,
			.ts-sidebar-menu .main-menu li > i:hover {
				color: '. @$options["menu-text-color"] .';
			}
			.ts-sidebar-menu [class*="ts_is_mega_menu_columns"] h4:before {
				background: '. @$options["menu-text-color"] .';
			}
			.ts-sidebar-menu ul.main-menu li.menu-item a:hover,
			.ts-sidebar-menu.ts-header-menu .main-menu > .menu-item-has-children ul li > a:hover {
				color: '. @$options["menu-text-color-hover"] .';
			}
			';

		}

		$style_hide_description_icons = '';

		if( $description == 'y' ){
			$style_hide_description_icons = '.' . $menu_id . ' .mega-menu-item-description{display: none;}';
		}

		if( $icons == 'y' ){
			$style_hide_description_icons .= '.' . $menu_id . ' li a i{display: none;}';
		}
		$style_to_hide_description_icon = '<style>' . $style_hide_description_icons . '</style>';

		if ( $icons != 'y' && $description == 'n' ) {
			$style_to_hide_description_icon = '';
		}

		$style_font_menu = '';
		if( $font_type == 'google' ){
			$style_font_menu = 	'<style>
			.'. $menu_id .'{
				font-weight: '.  $options['font-weight'] .';
				font-size: '. $options['font-size'] .'px;
				font-family: '. str_replace('+', ' ', $options['font-name']) .';
				font-style: '. $options['font-style'] .';
			}
		</style>';
	}

	$menu_styles = '
	<style>
		.ts-custom-menu-colors.'.$menu_id.' .main-menu > .menu-item-has-children ul li > a:hover{
			color: '. @$options["submenu-text-color-hover"] .';
		}
		.ts-custom-menu-colors.'.$menu_id.' .main-menu > .menu-item-has-children ul li > a:before {
			background-color: '. @$options["submenu-bg-color-hover"].';
			opacity: 0.3;
		}
		.ts-custom-menu-colors.'.$menu_id.'{
			background-color: '. @$options["menu-bg-color"] .';
		}
		.ts-custom-menu-colors.'.$menu_id.' .main-menu > li > a {
			color: '. @$options["menu-text-color"] .';
		}
		.ts-custom-menu-colors.'.$menu_id.' .main-menu > li > a:hover{
			background-color: '. @$options["menu-bg-color-hover"] .';
			color: '.@$options["menu-text-color-hover"].';
		}
		.ts-custom-menu-colors.'.$menu_id.' li li a,
		.ts-mega-menu .main-menu .ts_is_mega_div .title{
			color: '. @$options["submenu-text-color"] .';
		}
		.ts-custom-menu-colors.'.$menu_id.' li ul li a:not(.view-more):hover{
			color: '. @$options["submenu-text-color-hover"] .';
		}
		.ts-custom-menu-colors.'.$menu_id.' .sub-menu{
			background-color: '. @$options["submenu-bg-color"].';
		}
		.ts-header-menu .main-menu > .menu-item-has-children ul li > a:after,
		.ts-sticky-menu .main-menu > .menu-item-has-children ul li > a:after,
		.ts-mega-menu .menu-item-has-children .ts_is_mega_div .ts_is_mega > li > a:after,
		.ts-mobile-menu .menu-item-type-taxonomy.menu-item-has-children .ts_is_mega_div > .sub-menu li a:after{
			background-color: '. @$options["submenu-bg-color"].';
		}
		.ts-custom-menu-colors .title {
			color: '. @$options['menu-text-color'] .';
		}
		.ts-custom-menu-colors.'.$mobile_menu_id.' .main-menu > .menu-item-has-children ul li > a:hover{
			color: '. @$options["submenu-text-color-hover"] .';
		}
		.ts-custom-menu-colors.'.$mobile_menu_id.' .main-menu > .menu-item-has-children ul li > a:before{
			background-color: '. @$options["submenu-bg-color-hover"].';
		}
		.ts-mobile-menu.'.$mobile_menu_id.'{
			background-color: '. @$options["menu-bg-color"] .';
		}
		.ts-mobile-menu.'.$mobile_menu_id.' .main-menu > li > a{
			color: '. @$options["menu-text-color"] .';
		}
		.ts-mobile-menu.'.$mobile_menu_id.' .main-menu > li > a:hover{
			background-color: '. @$options["menu-bg-color-hover"] .';
			color: '.@$options["menu-text-color-hover"].';
		}
		.ts-mobile-menu.'.$mobile_menu_id.' li li a{
			color: '. @$options["submenu-text-color"] .';
		}
		.ts-mobile-menu.'.$mobile_menu_id.' li ul li a:hover{
			color: '. @$options["submenu-text-color-hover"] .';
		}
		.ts-mobile-menu.'.$mobile_menu_id.' .sub-menu{
			background-color: '. @$options["submenu-bg-color"].';
		}'.
		(isset ($styleSidebarMenu) ? $styleSidebarMenu : '') .'
	</style>
	';

	if( isset($options['menu-custom']) && $options['menu-custom'] == 'n' ){
		$menu_styles = '';
	}

	return '<div class="col-lg-12 col-md-12 col-sm-12">' .
	$menu
	.'<div id="ts-mobile-menu" class="ts-mobile-menu '.$mobile_menu_id.' '. $custom_menu_class.' ">
	<div class="mobile_header nav-header">
		<a href="#" data-toggle="mobile_menu" class="trigger">
			<span class="icon-menu">' . esc_html__('Menu', 'videofly') . '</span>
		</a>
	</div>'.
	$mobile_menu .
	'</div>
</div>'. $menu_styles . $style_to_hide_description_icon . $style_font_menu;
}

public static function delimiter_element($options = array())
{
	$delimiters = array(
		'dotsslash',
		'doubleline',
		'lines',
		'squares',
		'gradient',
		'line',
		'iconed icon-close',
		'small-line'
		);
	$delimiter_style = (in_array($options['delimiter-type'], $delimiters))? $options['delimiter-type'] : 'line';
	$delimiter_color = (isset($options['delimiter-color']) && is_string($options['delimiter-color'])) ?	$options['delimiter-color'] : '';

		// Set styles for each delimiter type

	if ( $delimiter_style == 'dotsslash' || $delimiter_style == 'doubleline' || $delimiter_style == 'line' || $delimiter_style == 'iconed icon-close' ) {
		$delimiter_css_styles = 'style="color: '.$delimiter_color.'; border-color:'.$delimiter_color.'"';
	} elseif ( $delimiter_style == 'lines' ) {
		$delimiter_css_styles = 'style="background: repeating-linear-gradient(to right,'.$delimiter_color.','.$delimiter_color.' 1px,#fff 1px,#fff 2px);"';
	} elseif( $delimiter_style == 'squares' ) {
		$delimiter_css_styles = 'style="background: repeating-linear-gradient(to right,'.$delimiter_color.','.$delimiter_color.' 4px,#fff 4px,#fff 8px);"';
	} elseif( $delimiter_style == 'gradient' ) {
		$delimiter_css_styles = 'style="
		background: -moz-linear-gradient(left,  rgba(0, 0, 0, 0) 0%,  '.$delimiter_color.' 50%, rgba(0, 0, 0, 0) 100%);
		background: -webkit-gradient(linear, left top, right top, color-stop(0%,rgba(0, 0, 0, 0)), color-stop(50%, '.$delimiter_color.'), color-stop(100%,rgba(0, 0, 0, 0)));
		background: -webkit-linear-gradient(left,  rgba(0, 0, 0, 0) 0%, '.$delimiter_color.' 50%,rgba(0, 0, 0, 0) 100%);
		background: -o-linear-gradient(left,  rgba(0, 0, 0, 0) 0%, '.$delimiter_color.' 50%,rgba(0, 0, 0, 0) 100%);
		background: -ms-linear-gradient(left,  rgba(0, 0, 0, 0) 0%, '.$delimiter_color.' 50%,rgba(0, 0, 0, 0) 100%);
		background: linear-gradient(to right,  rgba(0, 0, 0, 0) 0%, '.$delimiter_color.' 50%,rgba(0, 0, 0, 0) 100%);"';

	} elseif($delimiter_style == 'small-line'){
		$delimiter_css_styles = 'style="background:'.$delimiter_color.'"';
	}else{
		$delimiter_css_styles =  'style="'.$delimiter_color.'"';
	}

	return '<div class="col-lg-12"><div class="delimiter ' . $delimiter_style . '" '.$delimiter_css_styles.'></div></div>';
}

public static function title_element($options = array())
{

	$styles = array(
		'lineariconcenter',
		'2lines',
		'simpleleft',
		'lineafter',
		'linerect',
		'leftrect',
		'simplecenter',
		'with-subtitle-above',
		'align-right',
		'bottom-decoration',
		'brackets',
		'with-subtitle-over',
		'with-small-line-below'
		);

	$options['style'] = (in_array($options['style'], $styles)) ? $options['style'] : 'simpleleft';

	$sizes = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );

	$options['size'] = (in_array($options['size'], $sizes))? $options['size'] : 'h1';
	$link = (isset($options['link']) && !empty($options['link'])) ? esc_url($options['link']) : '';
	$titleTarget = (isset($options['target']) && ($options['target'] == '_blank' || $options['target'] == '_self')) ? $options['target'] : '_blank';

	$letterSpacer = isset($options['letter-spacer']) && (int)$options['letter-spacer'] !== 0 ? ' letter-spacing: '. (int)$options['letter-spacer'] .'px;' : '';

	if( $options['title'] !== '' && $options['style'] !== 'lineariconcenter' ){
		if( !empty($link) ){
			$title = '<' . $options['size'] . ' class="the-title" style="color: ' . $options['title-color'] . ';' . $letterSpacer .'"><i class="' . @$options['title-icon'] . '"></i><a target="'. $titleTarget .'" href="'.
			$link . '">' . stripslashes($options['title']) . '</a></' . $options['size'] . '>';
		}else{
			$title = '<' . $options['size'] . ' class="the-title" style="color: ' . $options['title-color'] . ';' . $letterSpacer .'"><i class="' . @$options['title-icon'] . '"></i>' . stripslashes($options['title']) . '</' . $options['size'] . '>';
		}
	}else{
		$title = '';
	}
	$description = '';
	if( $options['subtitle'] !== '' && $options['style'] !== 'lineariconcenter' ){
		$description = '<span class="block-title-description" style="color: ' . $options['subtitle-color'] . '">' . stripslashes($options['subtitle']) . '</span>';
	}else{
		$description = '';
	}

	if( $options['title'] !== '' && $options['style'] == 'lineariconcenter' ){
		$desc = '<span class="block-title-description" style="color: ' . $options['subtitle-color'] . '">' . stripslashes($options['subtitle']) . '</span>';
		$additional = '<'.$options['size'] . ' class="the-title" style="color: '. $options['title-color'] . ';' . $letterSpacer .'">'
		. stripslashes($options['title']) .
		'</' . $options['size'].'>' . $desc . '<i class="' . @$options['title-icon'] . '"></i>';
	}else{
		$additional = '';
	}

	$title_styles = '';
	if( $options['style'] != 'with-subtitle-above' ){
		$title_styles = $title . $description . $additional;
	}
	$title_with_desc_above = '';
	if( $options['style'] == 'with-subtitle-above' ){
		$title_with_desc_above = $description . $title . $additional;
	}

	$effect = isset($options['effect']) && $options['effect'] !== 'none' ? $options['effect'] : 'none';
	$classDelay = isset($options['delay']) && $options['delay'] !== 'none' ? ' '. $options['delay'] : '';

	return	'<div class="col-lg-12'. ($effect !== 'none' ? ' animatedParent animateOnce' : '') .'">
	<div class="block-title block-title-'. $options['style'] . ($effect !== 'none' ? ' animated '. $effect . $classDelay .'"' : '"') .'>
		<div class="block-title-container">
			'. $title_styles .'
			'. $title_with_desc_above .'
		</div>
	</div>
</div>';
}

public static function social_buttons_element($options = array())
{
	$elements = array();
	$social_options = get_option( 'videofly_social' );
	$social_align_text = ( isset($options['social-align']) ) ? $options['social-align'] : '';
	$social_style = ( isset($options['social-style']) ) ? $options['social-style'] : 'big-background';

	if( isset($social_options['social_new']) && is_array($social_options['social_new']) && !empty($social_options['social_new']) ){
		foreach ($social_options['social_new'] as $key => $setting_social) {

			$elements[] = '<style>#ts-'. $key .':hover{background-color:'. $setting_social['color'] .'}</style>';

			$elements[] = '<li><a id="ts-'. $key .'" href="'. $setting_social['url'] .'"><img src="'. $setting_social['image'] .'" alt=""></a></li>';
		}
	}

	if ( isset($social_options) ) {

		if ( trim($social_options['skype']) !== '' ) {
			$elements[] = '<li><a href="'.esc_url($social_options['skype']).'" class="icon-skype" target="_blank"></a></li>';
		}

		if ( trim($social_options['github']) !== '' ) {
			$elements[] = '<li><a href="'.esc_url($social_options['github']).'" class="icon-github" target="_blank"></a></li>';
		}

		if ( trim($social_options['gplus']) !== '' ) {
			$elements[] = '<li><a href="'.esc_url($social_options['gplus']).'" class="icon-gplus" target="_blank"></a></li>';
		}

		if ( trim($social_options['dribble']) !== '' ) {
			$elements[] = '<li><a href="'.esc_url($social_options['dribble']).'" class="icon-dribbble" target="_blank"></a></li>';
		}

		if ( trim($social_options['lastfm']) !== '' ) {
			$elements[] = '<li><a href="'.esc_url($social_options['lastfm']).'" class="icon-lastfm" target="_blank"></a></li>';
		}

		if ( trim($social_options['linkedin']) !== '' ) {
			$elements[] = '<li><a href="'.esc_url($social_options['linkedin']).'" class="icon-linkedin" target="_blank"></a></li>';
		}

		if ( trim($social_options['tumblr']) !== '' ) {
			$elements[] = '<li><a href="'.esc_url($social_options['tumblr']).'" class="icon-tumblr" target="_blank"></a></li>';
		}

		if ( trim($social_options['twitter']) !== '' ) {
			$elements[] = '<li><a href="'.esc_url($social_options['twitter']).'" class="icon-twitter" target="_blank"></a></li>';
		}

		if ( trim($social_options['vimeo']) !== '' ) {
			$elements[] = '<li><a href="'.esc_url($social_options['vimeo']).'" class="icon-vimeo" target="_blank"></a></li>';
		}

		if ( trim($social_options['wordpress']) !== '' ) {
			$elements[] = '<li><a href="'.esc_url($social_options['wordpress']).'" class="icon-wordpress" target="_blank"></a></li>';
		}

		if ( trim($social_options['yahoo']) !== '' ) {
			$elements[] = '<li><a href="'.esc_url($social_options['yahoo']).'" class="icon-yahoo" target="_blank"></a></li>';
		}

		if ( trim($social_options['youtube']) !== '' ) {

			$elements[] = '<li><a href="'.esc_url($social_options['youtube']).'" class="icon-video" target="_blank"></a></li>';
		}

		if ( trim($social_options['facebook']) !== '' ) {
			$elements[] = '<li><a href="'.esc_url($social_options['facebook']).'" class="icon-facebook" target="_blank"></a></li>';
		}

		if ( trim($social_options['flickr']) !== '' ) {
			$elements[] = '<li><a href="'.esc_url($social_options['flickr']).'" class="icon-flickr" target="_blank"></a></li>';
		}

		if ( trim($social_options['pinterest']) !== '' ) {
			$elements[] = '<li><a href="'.esc_url($social_options['pinterest']).'" class="icon-pinterest" target="_blank"></a></li>';
		}

		if ( trim($social_options['instagram']) !== '' ) {
			$elements[] = '<li><a href="'.esc_url($social_options['instagram']).'" class="icon-instagram" target="_blank"></a></li>';
		}
	}

	$elements = trim(implode("\n", $elements));

	if ($elements) {
		return '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="social-icons ' . $social_style . '">
			<ul class="'. $social_align_text .'">
				'.$elements.'
				<li class="">
					<a href="'. get_bloginfo('rss2_url') .'" class="icon-rss"> </a>
				</li>
			</ul>
		</div>
	</div>';
} else {
	return '';
}
}

public static function post_navigation() {
	if( get_previous_posts_link() != '' && get_next_posts_link() !='' ){
		return '
		<div class="col-lg-12">
			<div class="post-navigator">
				<ul class="row">
					<li class="col-lg-6">'.get_previous_posts_link().'
					</li>
					<li class="col-lg-6">'.get_next_posts_link().'
					</li>
				</ul>
			</div>
		</div>
		';
	}
}

public static function archive_navigation( $args = array() ) {
	global $wp_rewrite, $wp_query;
	/* If there's not more than one page, return nothing. */
	if ( 1 >= $wp_query->max_num_pages )
		return;
	/* Get the current page. */
	$current = ( get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1 );
	/* Get the max number of pages. */
	$max_num_pages = intval( $wp_query->max_num_pages );
	/* Get the pagination base. */
	$pagination_base = $wp_rewrite->pagination_base;
	/* Set up some default arguments for the paginate_links() function. */
	$defaults = array(
		'base'         => add_query_arg( 'paged', '%#%' ),
		'format'       => '',
		'total'        => $max_num_pages,
		'current'      => $current,
		'prev_next'    => true,
		'prev_text'    => esc_html__( '&larr; Previous', 'videofly' ),
		'next_text'    => esc_html__( 'Next &rarr;', 'videofly' ),
		'show_all'     => false,
		'end_size'     => 1,
		'mid_size'     => 4,
		'add_fragment' => '',
		'type'         => 'list'
		);
	/* Add the $base argument to the array if the user is using permalinks. */
	if ( $wp_rewrite->using_permalinks() && !is_search() ) {
		$big = 999999999;
		$defaults['base'] = str_replace( $big, '%#%', get_pagenum_link( $big ) );
	}
	/* Allow developers to overwrite the arguments with a filter. */
	$args = apply_filters( 'loop_pagination_args', $args );
	/* Merge the arguments input with the defaults. */
	$args = wp_parse_args( $args, $defaults );
	/* Don't allow the user to set this to an array. */
	if ( 'array' == $args['type'] )
		$args['type'] = 'plain';
	/* Get the paginated links. */
	$page_links = paginate_links( $args );
	/* Remove 'page/1' from the entire output since it's not needed. */
	$page_links = preg_replace(
		array(
				"#(href=['\"].*?){$pagination_base}/1(['\"])#",  // 'page/1'
				"#(href=['\"].*?){$pagination_base}/1/(['\"])#", // 'page/1/'
				"#(href=['\"].*?)\?paged=1(['\"])#",             // '?paged=1'
				"#(href=['\"].*?)&\#038;paged=1(['\"])#"         // '&#038;paged=1'
				),
		'$1$2',
		$page_links
		);

	/* Allow devs to completely overwrite the output. */
	$page_links = apply_filters( 'loop_pagination', $page_links );
	/* Return the paginated links for use in themes. */
	if($page_links){
		return '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="ts-pagination">' . $page_links . '</div></div>';
	}

}

public static function list_portfolios_element($options = array())
{
	$categories = (isset($options['category']) && !empty($options['category']) && is_string($options['category'])) ? explode(',', strip_tags($options['category'])) : '';
	$pagination     = (isset($options['pagination']) && ($options['pagination'] == 'n' || $options['pagination'] == 'y' || $options['pagination'] == 'load-more' || $options['pagination'] == 'infinite')) ? $options['pagination'] : 'n';

	if ( get_query_var('paged') ) {
		$current = get_query_var('paged');
	} elseif ( get_query_var('page') ) {
		$current = get_query_var('page');
	} else {
		$current = 1;
	}

	$args = array(
		'post_type' => 'portfolio',
		'posts_per_page' => (int)$options['posts-limit'],
		'orderby' => $options['order-by'],
		'order' => $options['order-direction'],
		'paged' => $current
		);

	if ( is_array($categories) && count($categories) > 0 ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'portfolio_register_post_type',
				'field'    => 'slug',
				'terms'    => $categories
				)
			);
	} else {
		$args['category__in'] = array(0);
	}

	if( $options['order-by'] === 'comments' ){
		$args['orderby'] = 'comment_count';
	}

	if( $options['order-by'] === 'views' ){
		$args['meta_key'] = 'ts_article_views';
		$args['orderby']  = 'meta_value_num';
	}
	$args = self::order_by($options['order-by'], $args);

	$query = new WP_Query($args);
	$options['args'] = $args;

	return self::last_posts_element($options, $query);
}

public static function searchbox_element($options = array())
{
	$align = (isset($options['align']) && ($options['align'] == 'left' || $options['align'] == 'right' || $options['align'] == 'center')) ? $options['align'] : 'center';
	if( isset($options['design']) ){
		if ( $options['design'] == 'icon' ) {
			$type = 'icon';
			$class = 'hidden-form-search';
			$icon = '<a href="#" class="search-trigger"><i class="icon-search"></i><span>' . esc_html__('click to search', 'videofly') . '</span></a>';
			$icon_close = '<a href="#" class="search-close"><i class="icon-close"></i></a>';
			$icon_search = '<a href="#" class="search"><i class="icon-search"></i></a>';
		} else {
			$type = 'search-type-input';
			$class = 'ts-search-bar';
			$icon = '';
			$icon_close = '';
			$icon_search = '';
		}
	}
	return '<div class="col-lg-12 col-md-12 col-sm-12 '.$type.'">
	<div id="searchbox" class="text-' . $align . '">' .
		$icon .
		'<div class="' . $class . '">
			<form role="search" method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">
				<fieldset>
					<input class="input" name="s" type="text" id="keywords" value="'.esc_html__( 'type anything and press enter', 'videofly' ).'" onfocus="if (this.value == \''.esc_html__( 'type anything and press enter', 'videofly' ).'\') {this.value = \'\';}" onblur="if (this.value == \'\') {this.value = \''.esc_html__( 'type anything and press enter', 'videofly' ).'\';}" />
					<input type="submit" class="searchbutton" name="search" value="'.esc_html__( 'Search', 'videofly' ).'" />' .
					$icon_search . '
				</fieldset>
			</form>' .
			$icon_close . '
		</div>
	</div>
</div>';
}

public static function teams_element($options = array(), $query = NULL)
{

	$teams = array();
	$elements_per_row = (isset($options['elements-per-row'])) ? (int)$options['elements-per-row'] : '';
	$posts_limit = (isset($options['posts-limit'])) ? (int)$options['posts-limit'] : '';
	$remove_gutter = (isset($options['remove-gutter'])) ? strip_tags($options['remove-gutter']) : '';
	$categories = (isset($options['category']) && !empty($options['category']) && is_string($options['category'])) ? explode(',', strip_tags($options['category'])) : '';

	$args = array(
		'post_type' => 'ts_teams',
		'posts_per_page' => $posts_limit,
		'orderby' => 'DATE',
		'order' => 'DESC'
		);

	if ( is_array($categories) && count($categories) > 0 ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'teams',
				'field'    => 'slug',
				'terms'    => $categories
				)
			);
	} else {
		$args['category__in'] = array(0);
	}

	if( !isset($query) ){
		$query = new WP_Query($args);
	}

	if ( $query->have_posts() ) {
		ob_start();
		ob_clean();
		global $article_options;
		$article_options = $options;
		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part('includes/layout-builder/templates/team');
		}
		$elements = ob_get_clean();

		wp_reset_postdata();

	} else {
		return esc_html__('No Results', 'videofly');
	}

	/* Restore original Post Data */
	wp_reset_postdata();
	if( $remove_gutter == 'yes' ){
		$gutter_class = ' no-gutter ';
	} else{
		$gutter_class = '';
	}

		// If carousel is enabled
	if( isset($options['enable-carousel']) && $options['enable-carousel'] === 'yes' ){
		$carousel_wrapper_start = '<div class="carousel-wrapper">';
		$carousel_wrapper_end = '</div>';

		$carousel_container_start = '<div class="carousel-overview"><div class="carousel-container">';
		$carousel_container_end = '</div></div>';

		$carousel_navigation = '<ul class="carousel-nav">
		<li class="carousel-nav-left icon-left">
			<span class="hidden_btn">'.esc_html__('prev','videofly').'</span>
		</li>
		<li class="carousel-nav-right icon-right">
			<span class="hidden_btn">'.esc_html__('next','videofly').'</span>
		</li>
	</ul>';
} else{
	$carousel_wrapper_start = '';
	$carousel_wrapper_end = '';
	$carousel_container_start = '';
	$carousel_container_end = '';
	$carousel_navigation = '';
}

$effect = isset($options['effect']) && $options['effect'] !== 'none' ? $options['effect'] : 'none';
$classDelay = isset($options['delay']) && $options['delay'] !== 'none' ? ' '. $options['delay'] : '';

return
'<section class="teams ' . $gutter_class . ' cols-by-' . $elements_per_row . ($effect !== 'none' ? ' animatedParent animateOnce' : '') .'">'.
($effect !== 'none' ? '<div class="animated '. $effect . $classDelay .'">' : '') .
$carousel_wrapper_start .
$carousel_navigation .
$carousel_container_start .
$elements .
$carousel_container_end .
$carousel_wrapper_end .
($effect !== 'none' ? '</div>' : '') .
'</section>';
}

public static function pricing_tables_element($options = array())
{
	$teams = array();
	$elements_per_row = (int)$options['elements-per-row'];
	$posts_limit = (int)$options['posts-limit'];
	$remove_gutter = $options['remove-gutter'];
	$categories = (isset($options['category']) && !empty($options['category']) && is_string($options['category'])) ? explode(',', $options['category']) : '';

	$args = array(
		'post_type' => 'ts_pricing_table',
		'posts_per_page' => $posts_limit,
		'orderby' => 'DATE',
		'order' => 'asc'
		);

	if ( is_array($categories) && !empty($categories) ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'ts_pricing_table_categories',
				'field'    => 'slug',
				'terms'    => $categories
				)
			);
	} else {
		$args['category__in'] = array(0);
	}


	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
		ob_start();
		ob_clean();
		global $article_options;
		$article_options = $options;
		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part('includes/layout-builder/templates/pricing-tables');
		}
		$elements = ob_get_clean();

		wp_reset_postdata();

	} else {
		return esc_html__('No Results', 'videofly');
	}

	/* Restore original Post Data */
	wp_reset_postdata();
	if( $remove_gutter == 'yes' ){
		$gutter_class = ' no-gutter ';
	} else{
		$gutter_class = '';
	}

	$effect = isset($options['effect']) && $options['effect'] !== 'none' ? $options['effect'] : 'none';
	$classDelay = isset($options['delay']) && $options['delay'] !== 'none' ? ' '. $options['delay'] : '';

	return
	'<section class="ts-pricing-view ' . $gutter_class . ' cols-by-' . $elements_per_row . ($effect !== 'none' ? ' animatedParent animateOnce' : '') .'">'.
	($effect !== 'none' ? '<div class="animated '. $effect . $classDelay .'">' : '') .
	$elements .
	($effect !== 'none' ? '</div>' : '') .
	'</section>';
}

public static function testimonials_element($options = array())
{

	$elementsperrow = (int)$options['elements-per-row'];
	$elements_per_row = self::get_column_class($elementsperrow);
	$enable_carousel = $options['enable-carousel'];


	if( isset($options['testimonials']) && $options['testimonials'] != '' ){

		$arr = $options['testimonials'];
		$arr = json_decode(stripslashes($arr));

		foreach($arr as $option_element){

			if ( $option_element->image != '' ) {
				$img_url = esc_url(str_replace('--quote--', '"', vdf_resize('features', $option_element->image)));
				$author_img = '<img class="author-img" src="' . $img_url . '" alt="' . esc_attr(str_replace('--quote--', '"', $option_element->name)) . '" />';
			} else {
				$url_image = get_template_directory_uri() . '/images/noimage.jpg';
				$img_url = esc_url(vdf_resize('features', $url_image));
				$author_img = '<img class="author-img" src="' . $img_url . '" alt="' . esc_attr(str_replace('--quote--', '"', $option_element->name)) . '" />';
			}

			$testimonials[] = 	'<div class="testimonial-item '. $elements_per_row .'">
			<article class="text-center">
				<div class="entry-header">
					<div class="inner-header">
						<div class="header-icon">
							<i class="icon-quote"></i>
						</div>
					</div>
				</div>
				<div class="entry-section text-center">
					<div class="inner-section">
						<div class="author-text">'. apply_filters('ts_the_content', str_replace('--quote--', '"', $option_element->text)) .'
						</div>
						<div class="testimonial-image">
							'. $author_img .'
						</div>
						<div class="author-position">'. str_replace('--quote--', '"', $option_element->company). '</div>
						<div class="author-name"><a target="_blank" href="'. $option_element->url .'">'. str_replace('--quote--', '"', $option_element->name) .'</a></div>
					</div>
				</div>
				<div class="entry-footer">
					<div class="inner-footer">
						<div class="footer-icon">
							<i class="icon-quote"></i>
						</div>
					</div>
				</div>
			</article>
		</div>';
	}
}

if( $enable_carousel == 'Y' ){

	$carousel_wrapper_start   = '<div class="carousel-wrapper">';
	$carousel_wrapper_end     = '</div>';

	$carousel_container_start = '<div class="carousel-overview"><div class="carousel-container">';
	$carousel_container_end = '</div></div>';

	$carousel_navigation      = '<ul class="carousel-nav">
	<li class="carousel-nav-left icon-left-arrow">
		<span>'.esc_html__('prev','videofly').'</span>
	</li>
	<li class="carousel-nav-right icon-right-arrow">
		<span>'.esc_html__('next','videofly').'</span>
	</li>
</ul>';
}else{
	$carousel_wrapper_start   = '';
	$carousel_wrapper_end 	  = '';

	$carousel_container_start = '';
	$carousel_container_end   = '';

	$carousel_navigation      = '';
}

if( isset($testimonials) ){
	$testimonials = implode("\n", $testimonials);
	return '<section class="testimonials cols-by-' . $elementsperrow . '"> ' . $carousel_wrapper_start . $carousel_container_start . $testimonials . $carousel_container_end . $carousel_navigation . $carousel_wrapper_end . ' </section>';
}

}

public static function slider_element($options = array(), $add_columns_container = true )
{
	if( isset($options['slider-id']) && (int)$options['slider-id'] !== 0 ){
		$slider_source = get_post_meta($options['slider-id'], 'slider-source', TRUE);
		$slider_size = get_post_meta($options['slider-id'], 'slider-size', TRUE);
		$slider_nr_of_posts = get_post_meta($options['slider-id'], 'slider-nr-of-posts', TRUE);
			// Check if slider size is null - set defaults
		if ( !isset($slider_size['width']) ) {
			$slider_size = fields::get_options_value('videofly_image_sizes', 'slider', true);
		}
	}else{
		return;
	}

	$slider_source = (isset($slider_source) && $slider_source == 'latest-posts' || $slider_source == 'latest-videos' || $slider_source == 'latest-galleries' || $slider_source == 'custom-slides' || $slider_source == 'latest-featured-posts' || $slider_source == 'latest-featured-galleries' || $slider_source == 'latest-featured-videos') ? $slider_source : 'custom-slides';

	$post_type = 'post';

	if( $slider_source == 'latest-posts' || $slider_source == 'latest-featured-posts' ) $post_type = 'post';
	if( $slider_source == 'latest-videos' || $slider_source == 'latest-featured-videos' ) $post_type = 'video';
	if( $slider_source == 'latest-galleries' || $slider_source == 'latest-featured-galleries' ) $post_type = 'ts-gallery';
	if( $slider_source == 'custom-slides' ) $post_type = 'ts_slider';

	$p_query = ($slider_source == 'custom-slides') ? $options['slider-id'] : '';
	$posts_per_page = ($slider_source == 'latest-posts' || $slider_source == 'latest-videos' || $slider_source == 'latest-galleries' || $slider_source == 'latest-featured-posts' || $slider_source == 'latest-featured-galleries' || $slider_source == 'latest-featured-videos') ? $slider_nr_of_posts : 1;
	$icon = fields::get_options_value('videofly_general','like_icons');

	$slider_content = '';

	$meta = ($slider_source == 'custom-slides') ? get_post_meta($options['slider-id'], 'ts_slides', true) : array();
	$meta = ($slider_source == 'custom-slides' && isset($meta) && is_array($meta) && !empty($meta)) ? $meta : array();
	$slider_type = get_post_meta($options['slider-id'], 'slider_type', true);
	$slider_type = (isset($slider_type)) ? esc_attr($slider_type) : 'flexslider';

	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => $posts_per_page,
		'p' => $p_query
		);

	if( $slider_source == 'latest-featured-posts' || $slider_source == 'latest-featured-videos' || $slider_source == 'latest-featured-galleries' ){
		$args['meta_query'] = array(
			array(
				'key' => 'featured',
				'value' => 'yes',
				'compare' => '=',
				),
			);
	}

	$query = new WP_Query( $args );
	global $post;

	$i = 0;

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) { $query->the_post(); setup_postdata($post);
			if( $slider_source !== 'custom-slides' ){

				if (!empty($post->post_excerpt)) {
					if (strlen(strip_tags(strip_shortcodes($post->post_excerpt))) > 180) {
						$description = mb_substr(strip_tags(strip_shortcodes($post->post_excerpt)), 0, 180) . '...';
					} else {
						$description = strip_tags(strip_shortcodes($post->post_excerpt));
					}
				} else {
					if ( strlen(strip_tags(strip_shortcodes($post->post_content))) > 180 ) {
						$description = mb_substr(strip_tags(strip_shortcodes($post->post_content)), 0, 180) . '...';
					} else {
						$description = strip_tags(strip_shortcodes($post->post_content));
					}
				}

				$meta[$i]['category'] = '';

				$post_type = get_post_type($post);

				if( isset($post_type) && ($post_type == 'video' || $post_type == 'post' || $post_type == 'ts-gallery') ){

					if( $post_type == 'video' ) $categories = wp_get_post_terms($post->ID, 'videos_categories');
					if( $post_type == 'ts-gallery' ) $categories = wp_get_post_terms($post->ID, 'gallery_categories');
					if( $post_type == 'post' ) $categories = get_the_category($post->ID);

					if ( isset($categories) && is_array($categories) && !empty($categories) ){
						$meta[$i]['category'] .= '<ul class="entry-category">';
						foreach ($categories as $index => $category){
							$link = ($post_type !== 'post') ? get_category_link($category->term_id) : get_term_link($category);
							if( is_object($category) ){
								$meta[$i]['category'] .= '<li><a title="' . esc_attr($category->name) . '" href="' . esc_url($link) . '">' . esc_attr($category->name) . '</a></li>';
							}
						}
						$meta[$i]['category'] .= '</ul>';
					}
				}

				$meta[$i]['meta-data'] = 	'<ul class="entry-meta">
				<li class="meta-month">' . get_the_date() . '</li>
			</ul>';

			$meta[$i]['date']              = get_the_time('F d, Y');
			$meta[$i]['slide_title']       = get_the_title(get_the_ID());
			$meta[$i]['slide_description'] = $description;
			$meta[$i]['slide_url']         = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
			$meta[$i]['slide_media_id']    = $post->ID;
			$meta[$i]['redirect_to_url']   = get_permalink();
			$meta[$i]['slide_position']    = 'left';
			$meta[$i]['slide_post_id']     = get_the_ID();
			$meta_likes = get_post_meta(get_the_ID(), '_touchsize_likes', true);
			if (!$meta_likes) {
				$meta_likes = 0;
			}

			if (fields::get_options_value('videofly_general', 'like') == 'n') {
				$meta_likes = 'nolikes';
			}

			$meta[$i]['slide_post_likes']   = $meta_likes;
			$meta[$i]['slide_post_likes_icon'] = $icon;

			$i++;

				}// end if

			} // end while
			if( $slider_type == 'slicebox' ){
				tsIncludeScripts(array('modernizr.custom2', $slider_type));
			}else{
				tsIncludeScripts(array($slider_type));
			}

			if ( $slider_type === 'flexslider' ) {

				$slider_content = '
				<div class="red-slider flexslider" data-animation="slide">
					<ul class="slides">';
						$slides_container = '';

						foreach ($meta as $slide) {
							$img_url = aq_resize($slide['slide_url'], $slider_size['width'], $slider_size['height'], true);
							$slides_container .= '<li>';
							$slides_container .= '<img src="'. esc_url($img_url) .'" alt="' . esc_attr($slide['slide_title']) . '" />';
							$align_caption = (isset($slide['slide_position']) && ($slide['slide_position'] == 'left' || $slide['slide_position'] == 'right' || $slide['slide_position'] == 'center')) ? $slide['slide_position'] : 'left';

							if ( $slide['slide_title'] !== '' && $slide['slide_description'] !== '' ) {
								$slides_container .= '<div class="slider-caption-container container ' . $align_caption . '"><div class="slider-caption">';
								$slides_container .= '<h3><a href="' . esc_url($slide['redirect_to_url']) . '">' . $slide['slide_title'] . '</a></h3>';
								$slides_container .= apply_filters('ts_the_content', $slide['slide_description']);
								$slides_container .= '</div></div>';
							}

							$slides_container .= '</li>';
						}

						$slider_content .= $slides_container;
						$slider_content .= '
					</ul>
				</div>';

			} elseif ( $slider_type === 'slicebox' ) {

				$slider_content = '
				<div class="red-slider slicebox">
					<ul class="sb-slider">';

						$slides_container = '';

						foreach ($meta as $slide) {

							$img_url = aq_resize($slide['slide_url'], $slider_size['width'], $slider_size['height'], true);
							$slides_container .= '<li>';
							$slides_container .= '<img src="'. esc_url($img_url) .'" alt="' . esc_attr($slide['slide_title']) . '" />';
							$align_caption = (isset($slide['slide_position']) && ($slide['slide_position'] == 'left' || $slide['slide_position'] == 'right' || $slide['slide_position'] == 'center')) ? $slide['slide_position'] : 'left';

							if ( $slide['slide_title'] !== '' && $slide['slide_description'] !== '' ) {

								$slides_container .= '<div class="slider-caption ' . $align_caption . '">';
								$slides_container .= '<h3><a href="' . esc_url($slide['redirect_to_url']) . '">' . $slide['slide_title'] . '</a></h3>';
								$slides_container .= $slide['slide_description'];
								$slides_container .= '</div>';
							}

							$slides_container .= '</li>';
						}

						$slider_content .= $slides_container;
						$slider_content .= '
					</ul>';
					$slider_content.= '
					<div id="nav-arrows" class="nav-arrows">
						<a href="#" class="icon-right sb-next"></a>
						<a href="#" class="icon-left sb-prev"></a>
					</div>
				</div>';

			} elseif ( $slider_type === 'parallax' ) {

				$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 7);

				$slider_content = '
				<div class="ts-sf-slider">
					<ul id="'.$randomString.'" class="sf-slides">';

						$slides_container = '';

						foreach ($meta as $slide) {

							$img_url = aq_resize($slide['slide_url'], $slider_size['width'], $slider_size['height'], true);

							$slides_container .= '<li>';
							$slides_container .= '<img src="'. esc_url($img_url) .'" alt="' . esc_attr($slide['slide_title']) . '" />';
							$align_caption = (isset($slide['slide_position']) && ($slide['slide_position'] == 'left' || $slide['slide_position'] == 'right' || $slide['slide_position'] == 'center')) ? $slide['slide_position'] : 'left';

							if ( $slide['slide_title'] !== '' && $slide['slide_description'] !== '' ) {

								$slides_container .= '<div class="slider-caption text-' . $align_caption . '"><div class="container">';
								$slides_container .= '<h3 class="title"><a href="' . esc_url($slide['redirect_to_url']) . '">' . $slide['slide_title'] . '</a></h3>';
								$slides_container .= '<span class="sub">'.$slide['slide_description'].'</span>';
								$slides_container .= '</div></div>';
							}

							$slides_container .= '</li>';
						}

						$slider_content .= $slides_container;
						$slider_content .= '
					</ul>';
					$slider_content .= '<ul class="sf-controls">
					<li class="previous"><a href="#"><i class="icon-left"></i></a></li>
					<li class="next"><a href="#"><i class="icon-right"></i></a></li>
				</ul>
			</div>';
		} elseif ( $slider_type === 'bxslider' ) {

			$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 7);

			$slider_content = '
			<div class="red-slider ts-bxslider">
				<ul id="'.$randomString.'" class="bxslider">';

					$slides_container = '';

					foreach ($meta as $slide) {

						$img_url = aq_resize($slide['slide_url'], $slider_size['width'], $slider_size['height'], true);
						$slides_container .= '<li>';
						$slides_container .= '<img src="'. esc_url($img_url) .'" alt="' . esc_attr($slide['slide_title']) . '" />';
						$align_caption = (isset($slide['slide_position']) && ($slide['slide_position'] == 'left' || $slide['slide_position'] == 'right' || $slide['slide_position'] == 'center')) ? $slide['slide_position'] : 'left';

						if ( $slide['slide_title'] !== '' && $slide['slide_description'] !== '' ) {

							$slides_container .= '<div class="slider-caption ' . $align_caption. '">';
							$slides_container .= '<h3 class="title"><a href="' . esc_url($slide['redirect_to_url']) . '">' . $slide['slide_title'] . '</a></h3>';
							$slides_container .= '<span class="sub">'.$slide['slide_description'].'</span>';
							$slides_container .= '</div>';
						}

						$slides_container .= '</li>';
					}

					$slider_content .= $slides_container;
					$slider_content .= '
				</ul>';
				$slider_content .= '<div class="controls-direction">
				<span id="slider-next"></span>
				<span id="slider-prev"></span>
			</div>
		</div>';
	} elseif ( $slider_type === 'stream' ) {

		$slider_content = '
		<div class="joyslider">
			<div class="slider-container">
				<ul class="slides-container">';

					$slides_container = '';
					$fonts = get_option('videofly_typography');
					$custom_font = (isset($fonts['headings']['type']) && $fonts['headings']['type'] == 'google' ) ? $fonts['headings']['h1']['font_name'] : 'inherit';
					$i = 0;

					foreach ($meta as $slide) {

						$img_url = aq_resize($slide['slide_url'], $slider_size['width'], $slider_size['height'], true);
						$date = (isset($slider['date'])) ? esc_attr($slider['date']) : '';
						$data_slide_meta_date = ($slider_source !== 'custom-slides') ? 'data-slide-meta-date="' . $slide['date'] . '"' : '';
						$like = ($slider_source !== 'custom-slides') ? ' data-slide-meta-likes="'. $slide['slide_post_likes'] .'" ' : '';
						$iconData = ($slider_source !== 'custom-slides') ? ' data-slide-meta-likes-icon="'. $like .'" ' : '';


						$slides_container .= '<li class="slide" data-custom-font="'. $custom_font .'" data-slide-title="' . $slide['slide_title'] . '" '. $data_slide_meta_date . ' data-slide-meta-id="'. $i .'"'. $like . $iconData .'>';

						$slides_container .= '<img src="'. esc_url($img_url) .'" alt="' . sanitize_text_field($slide['slide_title']) . '" />';
						if ( vdf_overlay_effect_is_enabled() ) {
							$slides_container .= '<div class="' . vdf_overlay_effect_type() . '"></div>';
						}

						$content_meta_data = (isset($slide['meta-data'])) ? $slide['meta-data'] : '';

						$description = ($slider_source == 'custom-slides') ? '<div class="entry-description">' . apply_filters('ts_the_content', $slide['slide_description']) . '</div>' : $slide['slide_description'];
						$align_caption = (isset($slide['slide_position']) && ($slide['slide_position'] == 'left' || $slide['slide_position'] == 'right' || $slide['slide_position'] == 'center')) ? 'text-'. $slide['slide_position'] : 'text-left';

						$slides_container .= '<div class="slide-content ' . $align_caption . ' ">
						<div class="container">
							' . $content_meta_data . '
							<h2 class="entry-title">
								<a title="' . sanitize_text_field($slide['slide_title']) . '" href="' . esc_url($slide['redirect_to_url']) . '">' . $slide['slide_title'] . '</a>
							</h2>
							<div class="entry-description">' . $description . '</div>
						</div>
					</div> ';

					$slides_container .= '</li>';
					$i++;
				}

				$slider_content .= $slides_container;
				$slider_content .= '
			</ul>';
			$slider_content .= '
			</div>
			</div>';
			$slider_content .= 	'<script>
			jQuery(document).ready(function(){
				jQuery(".joyslider").JoySlider();
			});
		</script>';
		}
	 elseif ( $slider_type === 'corena' ) {

			$slider_content = '
			<div class="corena-slider">
				<div class="slider-container">
					<ul class="slides-container">';

						$slides_container = '';
						$fonts = get_option('videofly_typography');
						$custom_font = (isset($fonts['headings']['type']) && $fonts['headings']['type'] == 'google' ) ? $fonts['headings']['h1']['font_name'] : 'inherit';
						$i = 0;

						foreach ($meta as $slide) {

							$img_url = aq_resize($slide['slide_url'], $slider_size['width'], $slider_size['height'], true);
							$date = (isset($slider['date'])) ? esc_attr($slider['date']) : '';
							$data_slide_meta_date = ($slider_source !== 'custom-slides') ? 'data-slide-meta-date="' . $slide['date'] . '"' : '';
							$like = ($slider_source !== 'custom-slides') ? ' data-slide-meta-likes="'. $slide['slide_post_likes'] .'" ' : '';
							$iconData = ($slider_source !== 'custom-slides') ? ' data-slide-meta-likes-icon="'. $like .'" ' : '';


							$slides_container .= '<li class="slide" data-custom-font="'. $custom_font .'" data-slide-title="' . $slide['slide_title'] . '" '. $data_slide_meta_date . ' data-slide-meta-id="'. $i .'"'. $like . $iconData .'>';

							$slides_container .= '<img src="'. esc_url($img_url) .'" alt="' . sanitize_text_field($slide['slide_title']) . '" />';
							if ( vdf_overlay_effect_is_enabled() ) {
								$slides_container .= '<div class="' . vdf_overlay_effect_type() . '"></div>';
							}

							$content_meta_data = (isset($slide['meta-data'])) ? $slide['meta-data'] : '';

							$description = ($slider_source == 'custom-slides') ? '<div class="entry-description">' . apply_filters('ts_the_content', $slide['slide_description']) . '</div>' : $slide['slide_description'];
							$align_caption = (isset($slide['slide_position']) && ($slide['slide_position'] == 'left' || $slide['slide_position'] == 'right' || $slide['slide_position'] == 'center')) ? $slide['slide_position'] : 'left';

							$vdf_slider_likes =

							$slides_container .= '<div class="slide-content">
							<div class="container">
								<div class="caption-container">
									' . $content_meta_data . '
									<h2 class="entry-title">
										<a title="' . sanitize_text_field($slide['slide_title']) . '" href="' . esc_url($slide['redirect_to_url']) . '">' .$slide['slide_title'] . '</a>
									</h2>
									<div class="entry-description">' . $description . '</div>
								</div>
							</div>
						</div> ';

						$slides_container .= '</li>';
						$i++;
					}

					$slider_content .= $slides_container;
					$slider_content .= '
				</ul>';
				$slider_content .= '
				</div>
				</div>';
				$slider_content .= 	'<script>
				jQuery(document).ready(function(){
					jQuery(".corena-slider").Corena();
				});
			</script>';
			}
		}//end if

		/* Restore original Post Data */
		wp_reset_postdata();

		if ( $add_columns_container == true ) {
			$start_columns = '<div class="col-lg-12 col-md-12 col-sm-12">';
			$end_columns = '</div>';
		} else{
			$start_columns = '<div class="slider-is-row-bg">';
			$end_columns = '</div>';
		}

		return $start_columns . balanceTags($slider_content, true) . $end_columns;
	}

	public static function last_posts_element($options = array(), &$original_query = null)
	{
		$exclude_posts  = ( isset($options['id-exclude']) && $options['id-exclude'] != '' ) ? explode(',',@$options['id-exclude']) : NULL;
		$exclude_first  = ( isset($options['exclude-first']) ) ? (int)$options['exclude-first'] : '';
		$exclude_id     = array();
		$featured       = (isset($options['featured']) && $options['featured'] !== '' && ($options['featured'] == 'y' || $options['featured'] == 'n')) ? $options['featured'] : 'n';
		$ajax_load_more = (isset($options['ajax-load-more']) && $options['ajax-load-more'] === true) ? true : false;
		$pagination_content = '';
		$pagination     = (isset($options['pagination']) && ($options['pagination'] == 'n' || $options['pagination'] == 'y' || $options['pagination'] == 'load-more' || $options['pagination'] == 'infinite')) ? $options['pagination'] : 'n';
		$categories     = (isset($options['category']) && !empty($options['category']) && is_string($options['category'])) ? explode(',', $options['category']) : '';

		$display_load_more = ($pagination == 'infinite') ? 'style="display: none;"' : '';
		$infinite_scroll = ($pagination == 'infinite') ? ' ts-infinite-scroll' : '';

		if( isset($exclude_posts) ){
			foreach($exclude_posts as $transform_to_integer){
				if( $transform_to_integer != 0 && $transform_to_integer != '' ) $exclude_id[] = (int)$transform_to_integer;
			}
		}

		$display_effect = 'no-effect';
		if( isset($options['special-effects']) ){

			if( $options['special-effects'] === 'opacited' ){
				$display_effect = 'animated opacited';
			} elseif( $options['special-effects'] === 'rotate-in' ){
				$display_effect = 'animated rotate-in';
			} elseif( $options['special-effects'] === '3dflip' ){
				$display_effect = 'animated flip';
			} elseif( $options['special-effects'] === 'scaler' ){
				$display_effect = 'animated scaler';
			}
		}

		if (isset($options['taxonomy'])) {
			$taxonomy = $options['taxonomy'];
		} else {
			$taxonomy = 'category';
		}

		// Display elements for grid mode
		if ($options['display-mode'] === 'grid') {

			$vdf_masonry_class = (isset($options['behavior']) && $options['behavior'] == 'masonry') ? ' ts-filters-container ' : '';

			$grid_view_start = '<section class="ts-grid-view ' . $display_effect . $vdf_masonry_class . ' ' . self::get_clear_class($options['elements-per-row']) . ' ">';

			$grid_view_end = '</section>';

			$carousel_wrapper_start = '<div class="carousel-wrapper">';
			$carousel_wrapper_end = '</div>';

			$carousel_container_start = '<div class="carousel-overview"><div class="carousel-container">';
			$carousel_container_end = '</div></div>';

			$carousel_navigation = '<ul class="carousel-nav">
			<li class="carousel-nav-left icon-left">
				<span class="hidden_btn">'.esc_html__('prev','videofly').'</span>
			</li>
			<li class="carousel-nav-right icon-right">
				<span class="hidden_btn">'.esc_html__('next','videofly').'</span>
			</li>
		</ul>';
		$tab_category_html = '';
		if ( $original_query === null ) {

			$order    = self::order_direction($options['order-direction']);

			if ( get_query_var('paged') ) {
				$current = get_query_var('paged');
			} elseif ( get_query_var('page') ) {
				$current = get_query_var('page');
			} else {
				$current = 1;
			}

			$args = array(
				'post_type' => 'post',
				'order' => $order,
				'post__not_in' => $exclude_id,
				'paged' => $current,
				'posts_per_page' => (int)$options['posts-limit'],
				'ignore_sticky_posts' => 1,
				'post__in'  => get_option('sticky_posts')
				);

			if( $pagination === 'n' || $pagination === 'load-more' || $pagination == 'infinite' ){
				$args['offset'] = $exclude_first;
			}

			$select_by_category = (isset($options['behavior']) && $options['behavior'] == 'tabbed') ? 'tabbed' : '';

			if ( is_array($categories) && !empty($categories) ) {

				if( $select_by_category == 'tabbed' ){
					$tab_category_html = '<div class="col-lg-12">
					<ul class="ts-select-by-category">';
						$category_i = 1;
						$tab_div_category = '';

						foreach($categories as $key=>$slug_category){
							$active_item = $key == 0 ? ' class="active"' : '';
							$category = get_category_by_slug($slug_category);
							if( is_object($category) ){
								$tab_category_html .= '<li' . $active_item . ' data-category-li="' . $category->term_id . '"><a href="#">' . $category->name . '</a></li>';
								if( $category_i == count($categories) ) $tab_category_html .= '</ul>';
								$tab_div_category .= '<div data-category-div="' . $category->term_id . '"><div class="row ts-cat-row"></div></div>';
							}
							$category_i++;
						}
						$tab_category_html .= $tab_div_category . '</div>';
					}

					$args['tax_query'] = array(
						array(
							'taxonomy' => 'category',
							'field'    => 'slug',
							'terms'    => $categories
							)
						);
				} else {
					$args['category__in'] = array(0);
				}

				$args = self::order_by($options['order-by'], $args, $featured);

				$query = new WP_Query( $args );

			} else {
				$query = &$original_query;
			}

			$row = array();

			if ( $query->have_posts() ) {
				ob_start();
				ob_clean();
				global $article_options;
				$article_options = $options;
				$article_options['j'] = $query->post_count;
				$article_options['i'] = 1;
				$article_options['k'] = 1;

				while ( $query->have_posts() ) {

					$query->the_post();
					get_template_part('includes/layout-builder/templates/grid-view');
				}
				$elements = ob_get_clean();

				if ( $pagination == 'y' ) {
					ob_start();
					ob_clean();
					global $vdf_list_query;
					$vdf_list_query = $query;
					get_template_part('template-pagination');
					$pagination_content = ob_get_clean();
				}
				wp_reset_postdata();

			} else {
				return $grid_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . esc_html__( 'Nothing Found', 'videofly' ) . '</div>' . $grid_view_end;
			}

			$args['options'] = $options;

			$load_more = (($pagination === 'load-more' || $pagination == 'infinite') && $ajax_load_more === false) ? '<div ' . $display_load_more . ' class="ts-pagination-more' . $infinite_scroll . '" data-loop="1" data-args="' . ts_enc_string(serialize($args)) . '"><i class="icon-restart"></i><span>' .esc_html__('Load More', 'videofly') . '</span>' . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

			if ( $original_query === null ) {
				$next_prev_links = '';
			} else {
				$next_prev_links = self::archive_navigation();
			}

			if (isset($options['behavior']) && $options['behavior'] === 'carousel') {
				return $grid_view_start . $carousel_wrapper_start . $carousel_navigation . $carousel_container_start . $elements . $carousel_container_end . $carousel_wrapper_end . $grid_view_end;
			}else if( isset($options['behavior']) && $options['behavior'] === 'scroll' ) {
				return  $grid_view_start . '<div class="scroll-view"><div class="row">' . $elements .'</div></div>'. $grid_view_end . $next_prev_links;
			}else if($ajax_load_more === true){
				return  $elements;
			}else{
				return  $grid_view_start . balanceTags($tab_category_html) . $elements . $grid_view_end . $load_more . $next_prev_links . $pagination_content;
			}

		} else if ($options['display-mode'] === 'list') {

			$list_view_start = '<section class="ts-list-view ' . $display_effect . ' ">';
			$list_view_end = '</section>';

			if ( $original_query === null ) {

				$order    = self::order_direction($options['order-direction']);

				if ( get_query_var('paged') ) {
					$current = get_query_var('paged');
				} elseif ( get_query_var('page') ) {
					$current = get_query_var('page');
				} else {
					$current = 1;
				}

				$args = array(
					'post_type'      => 'post',
					'order'          => $order,
					'paged'          => $current,
					'post__not_in'   => $exclude_id,
					'posts_per_page' => (int)$options['posts-limit']
					);

				if( $pagination === 'n' || $pagination === 'load-more' || $pagination == 'infinite' ){
					$args['offset'] = $exclude_first;
				}

				if ( is_array($categories) && !empty($categories) ) {
					$args['tax_query'] = array(
						array(
							'taxonomy' => 'category',
							'field'    => 'slug',
							'terms'    => $categories
							)
						);
				} else {
					$args['category__in'] = array(0);
				}

				$args = self::order_by($options['order-by'], $args, $featured);

				$query = new WP_Query( $args );

			} else {
				$query = &$original_query;
			}

			$articles = array();
			if ( $query->have_posts() ) {
				ob_start();
				ob_clean();
				global $article_options;
				$article_options = $options;
				while ( $query->have_posts() ) {
					$query->the_post();
					get_template_part('includes/layout-builder/templates/list-view');
				}
				$elements = ob_get_clean();

				if ( isset($options['pagination']) && $options['pagination'] == 'y' ) {
					ob_start();
					ob_clean();
					global $vdf_list_query;
					$vdf_list_query = $query;
					get_template_part('template-pagination');
					$pagination_content = ob_get_clean();
				}

				wp_reset_postdata();

			} else {
				return $list_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . esc_html__( 'Nothing Found', 'videofly' ) . '</div>' . $list_view_end;
			}

			$args['options'] = $options;

			$load_more = (($pagination === 'load-more' || $pagination == 'infinite') && $ajax_load_more === false) ? '<div ' . $display_load_more . ' class="ts-pagination-more' . $infinite_scroll . '" data-loop="1" data-args="' . ts_enc_string(serialize($args)) . '"><i class="icon-restart"></i><span>' .esc_html__('Load More', 'videofly') . '</span>' . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

			if ( $original_query === null ) {
				$next_prev_links = '';
			} else {
				$next_prev_links = self::archive_navigation();
			}
			if( $ajax_load_more === true ){
				return $elements;
			}else{
				return $list_view_start . $elements . $list_view_end . $load_more . $next_prev_links . $pagination_content;
			}

		} else if ($options['display-mode'] === 'thumbnails') {

			$use_gutter = '';
			$author = (isset($options['author'])) ? $options['author'] : '';

			if( isset($options['gutter']) ){
				if( $options['gutter'] === 'y' ){
					$use_gutter = ' no-gutter';
				}
			}

			$vdf_masonry_class = '';
			if ( isset($options['behavior']) && @$options['behavior'] == 'masonry' ) {
				$vdf_masonry_class = ' ts-filters-container ';
			}

			$thumbnails_view_start = '<section class="ts-thumbnail-view ' . $display_effect . $use_gutter . $vdf_masonry_class . ' ' . self::get_clear_class($options['elements-per-row']) . ' ">';
			$thumbnails_view_end = '</section>';

			$carousel_navigation = '<ul class="carousel-nav">
			<li class="carousel-nav-left icon-left">
				<span class="hidden_btn">'.esc_html__('prev','videofly').'</span>
			</li>
			<li class="carousel-nav-right icon-right">
				<span class="hidden_btn">'.esc_html__('next','videofly').'</span>
			</li>
		</ul>';

		$carousel_wrapper_start = '<div class="carousel-wrapper">';
		$carousel_wrapper_end = '</div>';

		$carousel_container_start = '<div class="carousel-overview"><div class="carousel-container">';
		$carousel_container_end = '</div></div>';

		$valid_columns = array(1, 2, 3, 4, 6);

		if ( ! in_array($options['elements-per-row'], $valid_columns)) {
			$options['elements-per-row'] = 3;
		}

		$tab_category_html = '';

		if ( $original_query === null ) {

			$order    = self::order_direction($options['order-direction']);

			if ( get_query_var('paged') ) {
				$current = get_query_var('paged');
			} elseif ( get_query_var('page') ) {
				$current = get_query_var('page');
			} else {
				$current = 1;
			}

			$args = array(
				'post_type' => 'post',
				'order' => $order,
				'paged' => $current,
				'post__not_in' => $exclude_id,
				'posts_per_page' => (int)$options['posts-limit']
				);

			if( $author !== '' ){
				$args['author'] = $author;
			}

			if( $pagination === 'n' || $pagination === 'load-more' || $pagination == 'infinite' ){
				$args['offset'] = $exclude_first;
			}

			$select_by_category = (isset($options['behavior']) && $options['behavior'] == 'tabbed') ? 'tabbed' : '';

			if ( is_array($categories) && !empty($categories) ) {

				if( $select_by_category == 'tabbed' ){
					$tab_category_html = '<div class="col-lg-12">
					<ul class="ts-select-by-category">';
						$category_i = 1;
						$tab_div_category = '';

						foreach($categories as $key=>$slug_category){
							$active_item = $key == 0 ? ' class="active"' : '';
							$category = get_category_by_slug($slug_category);
							if( is_object($category) ){
								$tab_category_html .= '<li' . $active_item . ' data-category-li="' . $category->term_id . '"><a href="#">' . $category->name . '</a></li>';
								if( $category_i == count($categories) ) $tab_category_html .= '</ul>';
								$tab_div_category .= '<div data-category-div="' . $category->term_id . '"><div class="row ts-cat-row"></div></div>';
							}
							$category_i++;
						}
						$tab_category_html .= $tab_div_category . '</div>';
					}

					$args['tax_query'] = array(
						array(
							'taxonomy' => 'category',
							'field'    => 'slug',
							'terms'    => $categories
							)
						);
				} else {
					$args['category__in'] = array(0);
				}

				$args = self::order_by($options['order-by'], $args, $featured);

				$query = new WP_Query( $args );
			} else {
				$query = &$original_query;
			}

			$elements = array();

			if ( $query->have_posts() ) {
				ob_start();
				ob_clean();
				global $article_options;
				$article_options = $options;
				$article_options['i'] = 1;
				$article_options['j'] = $query->post_count;
				while ( $query->have_posts() ) {
					$query->the_post();
					get_template_part('includes/layout-builder/templates/thumbs-view');
				}
				$elements = ob_get_clean();

				if ( isset($options['pagination']) && $options['pagination'] == 'y' ) {
					ob_start();
					ob_clean();
					global $vdf_list_query;
					$vdf_list_query = $query;
					get_template_part('template-pagination');
					$pagination_content = ob_get_clean();
				}

				wp_reset_postdata();

			} else {
				return $thumbnails_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . esc_html__( 'Nothing Found', 'videofly' ) . '</div>' . $thumbnails_view_end;
			}

			if ( $original_query === null ) {
				$next_prev_links = '';
				/* Restore original Post Data */
				wp_reset_postdata();
			} else {
				$next_prev_links = self::archive_navigation();
			}

			if( ! isset( $options['behavior'] ) ){
				@$options['behavior'] = 'none';
			}

			$args['options'] = $options;

			$load_more = (($pagination === 'load-more' || $pagination == 'infinite') && $ajax_load_more === false) ? '<div ' . $display_load_more . ' class="ts-pagination-more' . $infinite_scroll . '" data-loop="1" data-args="' . ts_enc_string(serialize($args)) . '"><i class="icon-restart"></i><span>' .esc_html__('Load More', 'videofly') . '</span>' . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

			if (@$options['behavior'] === 'carousel') {
				return $thumbnails_view_start . $carousel_wrapper_start . $carousel_navigation . $carousel_container_start . $elements . $carousel_container_end. $carousel_wrapper_end . $thumbnails_view_end;
			} else if( @$options['behavior'] === 'scroll' ) {
				return $thumbnails_view_start . '<div class="scroll-view"><div class="row">' . $elements .'</div></div>' . $thumbnails_view_end . $next_prev_links . $pagination_content;
			}else if( $ajax_load_more === true ){
				return $elements;
			}else{
				return $thumbnails_view_start . balanceTags($tab_category_html, true) . $elements . $thumbnails_view_end . $load_more . $next_prev_links . $pagination_content;
			}

		} else if ($options['display-mode'] === 'big-post') {

			$big_post_view_start = '<section class="ts-big-posts ' . $display_effect . ' ">';
			$big_post_view_end   = '</section>';

			$carousel = (isset($options['carousel']) && ($options['carousel'] == 'y' || $options['carousel'] == 'n')) ? $options['carousel'] : 'n';
			$carousel_wrapper_start = ($carousel == 'y') ? '<div class="carousel-wrapper">' : '';
			$carousel_wrapper_end = ($carousel == 'y') ? '</div>' : '';

			$carousel_container_start = '<div class="carousel-overview"><div class="carousel-container">';
			$carousel_container_end = '</div></div>';

			$carousel_navigation = ($carousel == 'y') ? '<ul class="carousel-nav">
			<li class="carousel-nav-left icon-left">
				<span class="hidden_btn">'.esc_html__('prev','videofly').'</span>
			</li>
			<li class="carousel-nav-right icon-right">
				<span class="hidden_btn">'.esc_html__('next','videofly').'</span>
			</li>
		</ul>' : '';

		if ( $original_query === null ) {

			$order    = self::order_direction($options['order-direction']);

			if ( get_query_var('paged') ) {
				$current = get_query_var('paged');
			} elseif ( get_query_var('page') ) {
				$current = get_query_var('page');
			} else {
				$current = 1;
			}

			$args = array(
				'post_type' => 'post',
				'order' => $order,
				'post__not_in' => $exclude_id,
				'posts_per_page' => (int)$options['posts-limit']
				);

			if( $pagination === 'n' || $pagination === 'load-more' || $pagination == 'infinite' ){
				$args['offset'] = $exclude_first;
			}

			if ( is_array($categories) && !empty($categories) ) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'category',
						'field'    => 'slug',
						'terms'    => $categories
						)
					);
			} else {
				$args['category__in'] = array(0);
			}

			$args = self::order_by($options['order-by'], $args, $featured);

			$query = new WP_Query( $args );

		} else {
			$query = &$original_query;
		}

		$elements = array();

		if ( $query->have_posts() ) {
			ob_start();
			ob_clean();
			global $article_options;
			$article_options = $options;
			$article_options['i'] = 1;
			while ( $query->have_posts() ) {
				$query->the_post();
				get_template_part('includes/layout-builder/templates/big-posts');
			}
			$elements = ob_get_clean();

			if ( isset($options['pagination']) && $options['pagination'] == 'y' ) {
				ob_start();
				ob_clean();
				global $vdf_list_query;
				$vdf_list_query = $query;
				get_template_part('template-pagination');
				$pagination_content = ob_get_clean();
			}

			wp_reset_postdata();

		} else {
			return $big_post_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . esc_html__( 'Nothing Found', 'videofly' ) . '</div>' . $big_post_view_end;
		}

		if ( $original_query === null ) {
			$next_prev_links = '';
		} else {
			$next_prev_links = self::archive_navigation();
		}

		$args['options'] = $options;

		$load_more = (($pagination === 'load-more' || $pagination == 'infinite') && $ajax_load_more === false) ? '<div ' . $display_load_more . ' class="ts-pagination-more' . $infinite_scroll . '" data-loop="1" data-args="' . ts_enc_string(serialize($args)) . '"><i class="icon-restart"></i><span>' .esc_html__('Load More', 'videofly') . '</span>' . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

		if( $ajax_load_more === true ){
			return $elements;
		}elseif( $carousel == 'y' ){
			return $big_post_view_start . $carousel_wrapper_start . $carousel_navigation . $carousel_container_start . $elements . $carousel_container_end. $carousel_wrapper_end . $big_post_view_end;
		}else{
			return $big_post_view_start . $elements . $big_post_view_end . $load_more . $next_prev_links . $pagination_content;
		}

	} else if ($options['display-mode'] == 'super-post') {

		$super_post_view_start = '<section class="ts-super-posts ' . $display_effect . ' ">';
		$super_post_view_end = '</section>';

		$valid_columns = array(1, 2, 3);

		if ( ! in_array($options['elements-per-row'], $valid_columns) ) {
			$options['elements-per-row'] = 1;
		}

		$elements = array();

		if ( $original_query === null ) {

			$order    = self::order_direction($options['order-direction']);

			if ( get_query_var('paged') ) {
				$current = get_query_var('paged');
			} elseif ( get_query_var('page') ) {
				$current = get_query_var('page');
			} else {
				$current = 1;
			}

			$args = array(
				'post_type' => 'post',
				'order' => $order,
				'paged' => $current,
				'post__not_in' => $exclude_id,
				'posts_per_page' => (int)$options['posts-limit']
				);

			if( $pagination === 'n' || $pagination === 'load-more' || $pagination == 'infinite' ){
				$args['offset'] = $exclude_first;
			}

			if ( is_array($categories) && !empty($categories) ) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'category',
						'field'    => 'slug',
						'terms'    => $categories
						)
					);
			} else {
				$args['category__in'] = array(0);
			}

			$args = self::order_by($options['order-by'], $args, $featured);

			$query = new WP_Query( $args );

		} else {
			$query = &$original_query;
		}

		$elements = array();

		if ( $query->have_posts() ) {
			ob_start();
			ob_clean();
			global $article_options;
			$article_options = $options;
			while ( $query->have_posts() ) {
				$query->the_post();
				get_template_part('includes/layout-builder/templates/super-posts');
			}
			$elements = ob_get_clean();

			if ( isset($options['pagination']) && $options['pagination'] == 'y' ) {
				ob_start();
				ob_clean();
				global $vdf_list_query;
				$vdf_list_query = $query;
				get_template_part('template-pagination');
				$pagination_content = ob_get_clean();
			}

			wp_reset_postdata();

		} else {
			return $super_post_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . esc_html__( 'Nothing Found', 'videofly' ) . '</div>' . $super_post_view_end;
		}

		if ( $original_query === null ) {
			$next_prev_links = '';
		} else {
			$next_prev_links = self::archive_navigation();
		}

		$args['options'] = $options;
		$load_more = (($pagination === 'load-more' || $pagination == 'infinite') && $ajax_load_more === false) ? '<div ' . $display_load_more . ' class="ts-pagination-more' . $infinite_scroll . '" data-loop="1" data-args="' . ts_enc_string(serialize($args)) . '"><i class="icon-restart"></i><span>' .esc_html__('Load More', 'videofly') . '</span>' . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

		if( $ajax_load_more === true ){
			return $elements;
		}else{
			return $super_post_view_start . $elements . $super_post_view_end . $load_more . $next_prev_links . $pagination_content;
		}

	} else if ($options['display-mode'] === 'timeline') {

		if ( $original_query === null ) {

			$order    = self::order_direction($options['order-direction']);

			if ( get_query_var('paged') ) {
				$current = get_query_var('paged');
			} elseif ( get_query_var('page') ) {
				$current = get_query_var('page');
			} else {
				$current = 1;
			}

			$args = array(
				'post_type' => 'post',
				'order' => $order,
				'paged' => $current,
				'post__not_in' => $exclude_id,
				'posts_per_page' => (int)$options['posts-limit']
				);

			if( $pagination === 'n' || $pagination === 'load-more' || $pagination == 'infinite' ){
				$args['offset'] = $exclude_first;
			}

			if ( is_array($categories) && !empty($categories) ) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'category',
						'field'    => 'slug',
						'terms'    => $categories
						)
					);
			} else {
				$args['category__in'] = array(0);
			}

			$args = self::order_by($options['order-by'], $args, $featured);

			$query = new WP_Query( $args );

		} else {
			$query = &$original_query;
		}

		$articles = array();
		if ( $query->have_posts() ) {
			ob_start();
			ob_clean();
			global $article_options;
			$article_options = $options;
			while ( $query->have_posts() ) {
				$query->the_post();
				get_template_part('includes/layout-builder/templates/timeline-view');
			}
			$elements = ob_get_clean();

			if ( $pagination == 'y' ) {
				ob_start();
				ob_clean();
				global $vdf_list_query;
				$vdf_list_query = $query;
				get_template_part('template-pagination');
				$pagination_content = ob_get_clean();
			}

			wp_reset_postdata();

		} else {
			return '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . esc_html__( 'Nothing Found', 'videofly' ) . '</div>';
		}

		if ( $original_query === null ) {
			$next_prev_links = '';
		} else {
			$next_prev_links = self::archive_navigation();
		}

		$args['options'] = $options;
		$load_more = (($pagination === 'load-more' || $pagination == 'infinite') && $ajax_load_more === false) ? '<div ' . $display_load_more . ' class="ts-pagination-more' . $infinite_scroll . '" data-loop="1" data-args="' . ts_enc_string(serialize($args)) . '"><i class="icon-restart"></i><span>' .esc_html__('Load More', 'videofly') . '</span>' . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

		if( $ajax_load_more === true ){
			return $elements;
		}else{
			return '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><section class="ts-timeline-view" data-alignment="left">' . $elements . '</section>' . $load_more . '</div>' . $next_prev_links . $pagination_content;
		}

	} else if ($options['display-mode'] === 'mosaic') {

		$effect_scroll = (isset($options['effects-scroll']) && $options['effects-scroll'] !== '' && $options['effects-scroll'] == 'default') ? '' : ' fade-effect';

		$gutter_class = (isset($options['gutter']) && $options['gutter'] == 'y') ? ' mosaic-with-gutter ' : ' mosaic-no-gutter';

		$scroll = (isset($options['scroll']) && $options['scroll'] == 'y') ?
		'<div data-scroll="true" class="mosaic-view'. $effect_scroll . $gutter_class . ' mosaic-' . $options['layout'] . '">'
		:
		'<div data-scroll="false" class="mosaic-view'. $gutter_class .' mosaic-' . $options['layout'] . '">';

		$img_rows = (isset($options['rows']) && $options['rows'] !== '' && (int)$options['rows'] !== 0) ? (int)$options['rows'] : '3';
		$layout_mosaic = (isset($options['layout']) && ($options['layout'] == 'rectangles' || $options['layout'] == 'square')) ? $options['layout'] : 'square';

		if ( $original_query === null ) {

			$order    = self::order_direction($options['order-direction']);

			if ( get_query_var('paged') ) {
				$current = get_query_var('paged');
			} elseif ( get_query_var('page') ) {
				$current = get_query_var('page');
			} else {
				$current = 1;
			}

			$args = array(
				'post_type' => 'post',
				'posts_per_page' => (int)$options['posts-limit'],
				'paged' => $current,
				'order' => $order,
				'post__not_in' => $exclude_id

				);

			if( isset($options['scroll']) && $options['scroll'] == 'n' && ($pagination === 'n' || $pagination === 'load-more' || $pagination == 'infinite') ){
				$args['offset'] = $exclude_first;
			}

			if ( is_array($categories) && !empty($categories) ) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'category',
						'field'    => 'slug',
						'terms'    => $categories
						)
					);
			} else {
				$args['category__in'] = array(0);
			}

			$args = self::order_by($options['order-by'], $args, $featured);

			$query = new WP_Query( $args );

		} else {
			$query = &$original_query;
		}

		$articles = array();
		if ( $query->have_posts() ) {
			ob_start();
			ob_clean();
			global $article_options;
			$article_options = $options;

			$article_options['i'] = (isset($options['loop'])) ? (int)$options['loop'] + 1 : 1;
			$article_options['j'] = $query->post_count;
			$article_options['k'] = 1;

			while ( $query->have_posts() ) {
				$query->the_post();
				get_template_part('includes/layout-builder/templates/mosaic-view');
				$article_options['k']++;
				$article_options['i']++;
				if( $article_options['k'] === 7 && $layout_mosaic == 'rectangles' && $img_rows == 2  ){
					$article_options['k'] = 1;
				}
				if( $article_options['k'] === 10 && $layout_mosaic == 'rectangles' && $img_rows == 3  ){
					$article_options['k'] = 1;
				}
				if( $article_options['k'] === 6 && $layout_mosaic == 'square' ){
					$article_options['k'] = 1;
				}
			}

			$elements = ob_get_clean();

			$pagination_content = '';
			if ( isset($options['scroll']) && $options['scroll'] == 'n' && $pagination == 'y' ) {
				ob_start();
				ob_clean();
				global $vdf_list_query;
				$vdf_list_query = $query;
				get_template_part('template-pagination');
				$pagination_content = ob_get_clean();
			}

			wp_reset_postdata();

		} else {
			return '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . esc_html__( 'Nothing Found', 'videofly' ) . '</div>';
		}

		if( isset($options['args']) ){
			$args = $options['args'];
			unset($options['args']);
		}

		$args['options'] = $options;
		$load_more = (isset($options['scroll']) && $options['scroll'] == 'n' && ($pagination === 'load-more' || $pagination == 'infinite') && $ajax_load_more === false) ? '<div ' . $display_load_more . ' class="ts-pagination-more' . $infinite_scroll . '" data-loop="1" data-args="' . ts_enc_string(serialize($args)) . '"><i class="icon-restart"></i><span>' .esc_html__('Load More', 'videofly') . '</span>' . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

		if ( $original_query === null ) {
			$next_prev_links = '';
		} else {
			$next_prev_links = self::archive_navigation();
		}

		if( $ajax_load_more ){
			return $elements;
		}else{
			return $scroll . $elements . $next_prev_links . '</div>' . $load_more . $pagination_content;
		}

	}
}

public static function callaction_element($options = array())
{
	ob_start();
	ob_clean();
	global $article_options;
	$article_options = $options;
	get_template_part('includes/layout-builder/templates/callaction');
	$element = ob_get_clean();
	wp_reset_postdata();
	return $element;
}

public static function advertising_element($options = array())
{
	return 	'<div class="col-lg-12">
	<div class="ad-container">'
		. $options['advertising'] .
		'</div>
	</div>';
}

public static function empty_element($options = array())
{
	return '&nbsp;';
}

public static function text_element($options = array()) {

	$effect = isset($options['effect']) && $options['effect'] !== 'none' ? $options['effect'] : 'none';
	$classDelay = isset($options['delay']) && $options['delay'] !== 'none' ? ' '. $options['delay'] : '';

	return
	'<div class="col-lg-12 col-md-12 col-sm-12 ts-text-element '. ($effect !== 'none' ? ' animatedParent animateOnce' : '') .'">'.
		($effect !== 'none' ? '<div class="animated '. $effect . $classDelay .'">' : '') .'
		' . do_shortcode( str_replace( '--quote--', '"', $options['text'] ) ) .
		($effect !== 'none' ? '</div>' : '') .'
	</div>';
}

public static function video_element($options = array())
{
	$showLightBox = (isset($options['lightbox']) && ($options['lightbox'] == 'y' || $options['lightbox'] == 'n')) ? $options['lightbox'] : 'n';
	$title = (isset($options['title']) && $showLightBox == 'y') ? esc_attr($options['title']) : '';

	$fancyBox = '';
	$videoEmbeddStart = '<div class="col-lg-12">';
	$videoEmbeddEnd = '</div>';
	$idEmbedFancyBox = 'ts-video-' . rand(1, 10000);
	$displayNone = ($showLightBox == 'y') ? 'style="display: none;"' : '';

	if( $showLightBox == 'y' ){

		$fancyBox = '<a href="#' . $idEmbedFancyBox . '" class="ts-video-fancybox" rel="fancybox"> <span class="icon-play"></span><span>'.esc_html__('Play','videofly').'</span></a>
		<h3 class="ts-video-title">' . $title . '</h3>';
	}

	if ( strpos( $options['embed'], 'iframe' ) == false ) {
		$video = wp_oembed_get( $options['embed'] );
	} else {
		$video = wp_unslash( $options['embed'] );
	}

	return
		$videoEmbeddStart .
			'<div ' . $displayNone . ' class="embedded_videos" id="' . $idEmbedFancyBox . '">' .
				$video . '
			</div>
			' . $fancyBox .
		$videoEmbeddEnd;
}

public static function image_element($options = array())
{

	$vdf_image_element = '';
	$retina = (isset($options['retina']) && ($options['retina'] === 'y' || $options['retina'] === 'n')) ? $options['retina'] : 'n';
	$image_url = (isset($options['image-url'])) ? esc_url($options['image-url']) : '';
	$forward_url = (isset($options['forward-url']) && !empty($options['forward-url'])) ? esc_url($options['forward-url']) : NULL;
	$align = (isset($options['align']) && ($options['align'] === 'left' || $options['align'] === 'center' || $options['align'] === 'right')) ? $options['align'] : 'center';

	if( !empty($image_url) ){
		$image_details = getimagesize($image_url);
		if( isset($image_details[0], $image_details[1]) && is_numeric($image_details[0]) && is_numeric($image_details[1]) ){
			$width = $image_details[0];
			$height = $image_details[1];
		}
	}

	$styleRetina = ($retina === 'y' && isset($width)) ? 'style="width: '. $width / 2 .'px"' : '';

	$effect = isset($options['effect']) && $options['effect'] !== 'none' ? $options['effect'] : 'none';
	$classDelay = isset($options['delay']) && $options['delay'] !== 'none' ? ' '. $options['delay'] : '';

	if( isset($forward_url) ){

		$vdf_image_element =
		'<div style="text-align:' . $align . '" class="col-lg-12'. ($effect !== 'none' ? ' animatedParent animateOnce' : '') .'">
		<a'. ($effect !== 'none' ? ' class="animated '. $effect . $classDelay .'"' : '') .' target="' . $options['image-target'] . '" href="' . $forward_url . '">
		<img '. $styleRetina .' alt="" src="' . $image_url . '" />
	</a>
</div>';

}else{

	$vdf_image_element =
	'<div style="text-align:' . $align . '" class="col-lg-12'. ($effect !== 'none' ? ' animatedParent animateOnce' : '') .'">
	<img'. ($effect !== 'none' ? ' class="animated '. $effect . $classDelay .'"' : '') .' '. $styleRetina .' alt="" src="' . $image_url . '" />
</div>';
}

return $vdf_image_element;
}

public static function image_carousel_element($options = array())
{
	ob_start();
	ob_clean();
	global $article_options;
	$article_options = $options;
	get_template_part('includes/layout-builder/templates/image-carousel');
	$element = ob_get_clean();
	wp_reset_postdata();
	return $element;
}

public static function filters_element($options = array())
{
	$options['categories'] = ( ! empty( $options['categories'] ) ) ? explode( ",", trim($options['categories']) ) : array();

	$elements = array();

	if ( $options['categories'] ) {

		$categories_start = '<ul class="ts-filters">';
		$categories_end   = '</ul>';
		$category_list    = '<li><a href="#" data-filter="*">'.esc_html__('Show all', 'videofly').'</a></li>';

			// Check if an effect is selected
		$display_effect = 'no-effect';

		if( isset($options['special-effects'] ) ){
			if( $options['special-effects'] == 'opacited' ){
				$display_effect = 'animated opacited';
			} elseif( $options['special-effects'] == 'rotate-in' ){
				$display_effect = 'animated rotate-in';
			} elseif( $options['special-effects'] == '3dflip' ){
				$display_effect = 'animated flip';
			} elseif( $options['special-effects'] == 'scaler' ){
				$display_effect = 'animated scaler';
			}
		}
			// Check if gutter is enabled/disabled
		$use_gutter = '';
		if( isset( $options['gutter'] ) ){
			if( $options['gutter'] == 'y' ){
				$use_gutter = ' no-gutter';
			}
		}

		switch ($options['post-type']) {
			case 'post':
			$post_type = 'post';
			$taxonomy = 'category';
			break;

			case 'portfolio':
			$post_type = 'portfolio';
			$taxonomy = 'portfolio_register_post_type';
			break;

			case 'ts-gallery':
			$post_type = 'ts-gallery';
			$taxonomy = 'gallery_categories';
			break;

			case 'video':
			$post_type = 'video';
			$taxonomy = 'videos_categories';
			break;

			default:
			$post_type = 'post';
			$taxonomy = 'category';
			break;
		}

		foreach ($options['categories'] as $category) {

			$category = get_term_by( 'slug', $category, $taxonomy );

			if ($category) {
				$category_name = esc_attr($category->name);
				$category_list .= '<li><a href="#" data-filter=".ts-category-' . $category->term_id . '">'.$category_name.'</a></li>';
			}
		}

		$order    = self::order_direction($options['direction']);

		ob_start();
		ob_clean();

		$args = array(
			'post_type' => $post_type,
			'tax_query' => array(
				array(
					'taxonomy' => $taxonomy,
					'field' => 'slug',
					'terms' => $options['categories']
					)
				),
			'posts_per_page' => (int)$options['posts-limit'],
			'order' => $order
			);

		$args = self::order_by($options['order-by'], $args);

		$query = new WP_Query( $args );
		$options['display-title'] = 'over-image';

		if ( $query->have_posts() ) {
			global $article_options, $filter_class, $taxonomy_name;
			$article_options = $options;
			$filter_class = 'yes';
			$taxonomy_name = $taxonomy;
			while ( $query->have_posts() ) {
				$query->the_post();
				get_template_part('includes/layout-builder/templates/thumbs-view');
			}
		}

		$elements = ob_get_clean();

		wp_reset_postdata();

		if ( empty( $elements ) ) {
			return esc_html__('No posts found', 'videofly');
		} else {
			return '<div class="col-md-12">'.$categories_start . $category_list . $categories_end . '</div><section class="ts-thumbnail-view ts-filters-container ' . $display_effect . $use_gutter . '">' . $elements . '</section>';
		}

	} else {
		return esc_html__('Please select categories for filters element in the page builder', 'videofly');
	}
}

public static function facebook_block_element($options = array()){

	$cover = isset($options['cover']) ? $options['cover'] : 'false';

	if ( isset($options['facebook-url']) && $options['facebook-url'] != '' ) {

		return '
		<div class="col-lg-12">
			<div class="fb-page" data-href="'. esc_url($options['facebook-url']) .'" data-width="'. (wp_is_mobile() ? '300' : '500') .'" data-height="350" data-small-header="false" data-adapt-container-width="true" data-hide-cover="'. $cover .'" data-show-facepile="true" data-show-posts="true">
				<div class="fb-xfbml-parse-ignore">
					<blockquote cite="'. esc_url($options['facebook-url']) .'">
						<a href="'. esc_url($options['facebook-url']) .'">Facebook</a>
					</blockquote>
				</div>
			</div>
			<div id="fb-root"></div>
		</div>
		<script>
			(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, "script", "facebook-jssdk"));
</script>';
}

}

public static function clients_element($options = array())
{

	if ( $options['clients'] != '[]' ) {

		$vdf_options = json_decode(stripslashes($options['clients']));
		$columns_class = LayoutCompilator::get_column_class($options['elements-per-row']);
		$img = '';
		$data_tooltip = '';

		if( $options['enable-carousel'] === 'y' ){
			$carousel_wrapper_start = '<div class="carousel-wrapper">';
			$carousel_wrapper_end = '</div>';

			$carousel_container_start = '<div class="carousel-overview"><div class="carousel-container">';
			$carousel_container_end = '</div></div>';

			$carousel_navigation = '<ul class="carousel-nav">
			<li class="carousel-nav-left icon-left">
				<span class="hidden_btn">'.esc_html__('prev','videofly').'</span>
			</li>
			<li class="carousel-nav-right icon-right">
				<span class="hidden_btn">'.esc_html__('next','videofly').'</span>
			</li>
		</ul>';
	} else{
		$carousel_wrapper_start = '';
		$carousel_wrapper_end = '';
		$carousel_container_start = '';
		$carousel_container_end = '';
		$carousel_navigation = '';
	}

	foreach($vdf_options as $vdf_option){

		$data_tooltip = ( isset($vdf_option->title) && $vdf_option->title != '' ) ? 'class="has-tooltip" data-tooltip="'.str_replace('--quote--', '"', $vdf_option->title).'"' : '';

		if( isset($vdf_option->url) && isset($vdf_option->image) && $vdf_option->url !== '' && $vdf_option->image !== '' ){

			$image_url = esc_url(str_replace('--quote--', '"', $vdf_option->image));
			$img .= '<div class="item ' . $columns_class . '">
			<div '. $data_tooltip .'><a target="_blank" href="'. esc_url(str_replace('--quote--', '"', $vdf_option->url)) .'"><img src="'. $image_url .'"></a></div>
		</div>' ;
	}else if( !isset($vdf_option->url) || $vdf_option->url == '' && (isset($vdf_option->image) && $vdf_option->image !== '') ){
		$image_url = esc_url(str_replace('--quote--', '"', $vdf_option->image));
		$img .= '<div class="item ' . $columns_class . '">
		<div '. $data_tooltip .'><img '. $data_tooltip .' src="'. $image_url .'"></div>
	</div>' ;
}else if( !isset($vdf_option->image) || $vdf_option->image == '' && (isset($vdf_option->url) && $vdf_option->url !== '') ){
	$image_url = get_template_directory_uri().'/images/noimage.jpg';
	$img .= '<div class="item ' . $columns_class . '">
	<div '. $data_tooltip .'><a target="_blank" href="'. esc_url(str_replace('--quote--', '"', $vdf_option->url)) .'"><img src="'. $image_url .'"></a></div>
</div>' ;
}else{
	$image_url = get_template_directory_uri().'/images/noimage.jpg';
	$img .= '<div class="item ' . $columns_class . '">
	<div '. $data_tooltip .'><img '. $data_tooltip .' src="'. $image_url .'"></div>
</div>' ;
}

}

return '<section data-show="' . $options['elements-per-row'] . '" class="ts-clients-view cols-by-' . $options['elements-per-row'] . '"><div class="col-lg-12">'. $carousel_wrapper_start . $carousel_navigation . $carousel_container_start . $img . $carousel_container_end . $carousel_wrapper_end .'</div></section>';

} else {
	return esc_html__('No clients found','videofly');
}

}

public static function feature_blocks_element($options = array())
{

	$elements = array();
	$style = '';
	$gutter = (isset($options['gutter']) && ($options['gutter'] == 'gutter' || $options['gutter'] == 'no-gutter')) ? $options['gutter'] : 'gutter';

	ob_start();
	ob_clean();
	global $article_options;
	$article_options = $options;
	get_template_part('includes/layout-builder/templates/feature');
	$elements = ob_get_clean();

	if ( $options['style'] == 'style1' ) {
		$style = 'ts-iconbox-bordered';
	} else {
		$style = 'ts-iconbox-background';
	}
	$style .= ' cols-by-' . $options['elements-per-row'] . ' ';

	if ( $elements ) {
		return '<section class="' . $style . $gutter . '">' . $elements . '</section>';
	} else {
		return esc_html__('No posts found','videofly');
	}

}

public static function listed_feature_element($options = array())
{
	if( isset($options) && $options != '' ){

		$elements = array();
		$style = 'ts-listed-features';
		ob_start();
		ob_clean();
		global $article_options;

		$article_options = $options;
		get_template_part('includes/layout-builder/templates/listed-feature');
		$elements = ob_get_clean();

		if ( $elements ) {
			return '<section class="' . $style . '">' . $elements . '</section>';
		} else {
			return esc_html__('No posts found','videofly');
		}
	}

}

public static function spacer_element($options = array())
{
	return '<div style="height: '.esc_attr($options['height']).'px;"></div>';
}

public static function icon_element($options = array())
{
	$icon_name = (isset($options['icon'])) ? $options['icon'] : '';
	$icon_align = (isset($options['icon-align'])) ? $options['icon-align'] : '';
	$icon_color = (isset($options['icon-color'])) ? $options['icon-color'] : '';
	$icon_size = (isset($options['icon-size'])) ? $options['icon-size'] : '';
	$display_shortcode = (isset($options['display']) && $options['display'] == true) ? true : NULL;

	$icon_styles = 'style="font-size: ' . $icon_size . 'px; color: ' . $icon_color . ';"';

	if( isset($display_shortcode) ){
		return '<i class="' . $icon_name . '" ' . $icon_styles . '></i>';
	}else{
		return '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" data-element="icon-element" style="text-align: '.esc_attr($icon_align).';">' . '<i class="' . $icon_name . '" ' . $icon_styles . '></i>' . '</div>';
	}

}

public static function counter_element($options = array()){

	$effect = isset($options['effect']) && $options['effect'] !== 'none' ? $options['effect'] : 'none';
	$classDelay = isset($options['delay']) && $options['delay'] !== 'none' ? ' '. $options['delay'] : '';

	$track_bar = (isset($options['track-bar']) && ($options['track-bar'] == 'with-track-bar' || $options['track-bar'] == 'without-track-bar')) ? $options['track-bar'] : 'with-track-bar';
	$track_bar_color = (isset($options['track-bar-color']) && $track_bar == 'with-track-bar') ? esc_attr($options['track-bar-color']) : 'transparent';
	$track_bar_icon = (isset($options['track-bar-icon']) && $track_bar == 'without-track-bar') ? $options['track-bar-icon'] : '';

	$counter_icon = (isset($track_bar_icon)) ? '<div class="counter-icon"><i class="'.$track_bar_icon.'"></i></div>' : '';
	$counter_text_color = (isset($options['counters-text-color']) && $track_bar == 'with-track-bar') ? ' style="color: '.$options['counters-text-color'].'"' : '';
	$counter_text_color1 = (isset($options['counters-text-color']) && $track_bar == 'without-track-bar') ? 'style="color: '.$options['counters-text-color'].'"' : '';

	$counter = '<div data-bar-color="' . $track_bar_color . '" data-counter-type="' . $track_bar . '"
	class="ts-counters'. ($effect !== 'none' ? ' animatedParent animateOnce' : '') .'"'. $counter_text_color .'>
	<article'. ($effect !== 'none' ? ' class="animated '. $effect . $classDelay .'"' : '') .'>
	'. $counter_icon .'
	<div class="entry-box" '. $counter_text_color1 .'>
		<div class="chart" data-percent="'. $options['counters-precents'] .'">
			<span class="percent">0</span>
			<div class="entry-title"><span class="the-title">'. $options['counters-text'] .'</span></div>
		</div>
	</div>
</article>
</div>';

return $counter;

}

public static function map_element($options = array()){

	$map_address = '';
	$map_width = '';
	$map_height = '';
	$map_lat = '';
	$map_lng = '';
	$map_type = '';
	$map_style = '';
	$map_zoom = '';
	$map_type_control = '';
	$map_zoom_control = '';
	$map_scale_control = '';
	$map_scroll_wheel = '';
	$map_draggable_dir = '';
	$map_marker = '';

		// Check map address
	if( isset($options['map-address']) ){
		$map_address = $options['map-address'];
	}
		// Check iframe map width
	if( isset($options['map-width']) ){
		$map_width = $options['map-width'];
	}
		// Check iframe map height
	if( isset($options['map-height']) ){
		$map_height = $options['map-height'];
	}
		// Check map latitude
	if( isset($options['map-latitude']) ){
		$map_lat = $options['map-latitude'];
	}
		// Check map longitude
	if( isset($options['map-longitude']) ){
		$map_lng = $options['map-longitude'];
	}
		// Check map type (roadmap, satellite, hybrid, terrain)
	if( isset($options['map-type']) ){
		$map_type = $options['map-type'];
	}
		// Check map style(Essence, Subtle grayscale, Shades of grey, Purple, Best ski pros or your custom style)
	if( isset($options['map-style']) ){
		$map_style = $options['map-style'];
	}
		// Check map zoom
	if( isset($options['map-zoom']) ){
		$map_zoom = (int)$options['map-zoom'];
	}
		// Check map type-control
	if( isset($options['map-type-control']) ){
		$map_type_control = $options['map-type-control'];
	}
		// Check map zoom-control
	if( isset($options['map-zoom-control']) ){
		$map_zoom_control = $options['map-zoom-control'];
	}
		// Check map scale-control
	if( isset($options['map-scale-control']) ){
		$map_scale_control = $options['map-scale-control'];
	}
		// Check map scroll-wheel
	if( isset($options['map-scroll-wheel']) ){
		$map_scroll_wheel = $options['map-scroll-wheel'];
	}
		// Check map draggable-direction
	if( isset($options['map-draggable-direction']) ){
		$map_draggable_dir = $options['map-draggable-direction'];
	}
		// Check map pin/marker image
	if( isset($options['map-marker-img']) ){
		$map_marker = $options['map-marker-img'];
	}
	$randId = rand(10, 100000);

	return '<div class="col-lg-12 col-md-12 col-sm-12">
	<div class="ts-map-create" id="ts-map-canvas-'. $randId .'" style="width: '.$map_width.'%; height: '.$map_height.'px;"
	data-address="'.$map_address.'"
	data-lat="'.$map_lat.'"
	data-lng="'.$map_lng.'"
	data-type="'.$map_type.'"
	data-style="'.$map_style.'"
	data-zoom="'.$map_zoom.'"
	data-type-ctrl="'.$map_type_control.'"
	data-zoom-ctrl="'.$map_zoom_control.'"
	data-scale-ctrl="'.$map_scale_control.'"
	data-scroll="'.$map_scroll_wheel.'"
	data-draggable="'.$map_draggable_dir.'"
	data-marker="'.$map_marker.'"></div>
</div>';
}

public static function post_element($options = array())
{
	return 'Post element';
}

public static function page_element($options = array())
{
	return 'Page element';
}

public static function sidebar_element($options = array())
{
	$stickySidebarClass = '';
	ob_start();
	dynamic_sidebar( @(string)$options['sidebar-id'] );
	if ($options['sidebar-sticky'] == 'y') {
		$stickySidebarClass = 'sidebar-is-sticky';
	}
	$sidebar = ob_get_contents();
	ob_end_clean();
	return '<div class="col-lg-12 col-md-12 col-sm-12"><div class="ts-sidebar-element ' . $stickySidebarClass . '">' . $sidebar . '</div></div>';
}

public static function contact_form_element($options = array())
{
	ob_start();
	ob_clean();
	global $article_options;
	$article_options = $options;
	get_template_part('includes/layout-builder/templates/contact-form');
	$element = ob_get_clean();
	wp_reset_postdata();
	return $element;
}

public static function featured_area_element($options = array())
{

	$categories = isset($options['category']) && !empty($options['category']) && is_string($options['category']) ? explode(',', $options['category']) : '';
	$exclude_first = isset($options['exclude-first']) && (int)$options['exclude-first'] !== 0 ? (int)$options['exclude-first'] : NULL;
	$custom_post = isset($options['custom-post']) && ($options['custom-post'] == 'ts-gallery' || $options['custom-post'] == 'post' || $options['custom-post'] == 'video') ? $options['custom-post'] : 'post';
	if ( empty($categories) ) {
		$categories = array(0);
	}

	$args = array(
		'posts_per_page' => (isset($options['posts-per-page']) && !empty($options['posts-per-page']) ? intval($options['posts-per-page']) : 8),
		'orderby'        => 'date',
		'order'          => (isset($options['order-direction']) ? $options['order-direction'] : 'DESC')
	);


	if( isset($exclude_first) ){
		$args['offset'] = $exclude_first;
	}

	if( $custom_post == 'post' ){
		$args['post_type'] = 'post';
		$args['category__in'] = $categories;
	}
	if( $custom_post == 'video' ){
		$args['post_type'] = 'video';
		$args['tax_query'] = array(
			'relation' => 'OR',
			array(
				'taxonomy' => 'videos_categories',
				'field'    => 'id',
				'terms'    => $categories,
				'operator' => 'IN',
				)
			);
	}

	self::order_by($options['order-by'], $args);

	if( $custom_post == 'ts-gallery' ){
		$args['post_type'] = 'ts-gallery';
		$args['tax_query'] = array(
			'relation' => 'OR',
			array(
				'taxonomy' => 'gallery_categories',
				'field'    => 'id',
				'terms'    => $categories,
				'operator' => 'IN',
				)
			);
	}

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
		ob_start();
		ob_clean();
		global $element, $featQuery;
		$element = $options;
		$featQuery = $query;
		get_template_part('includes/layout-builder/templates/featured-area');
		$element = ob_get_clean();

	} else {
		return esc_html__('No post found', 'videofly');
	}

	/* Restore original Post Data */
	wp_reset_postdata();


	return
		'<div class="ts-slider-cyncing">
			<div class="container">
				<div class="row">'.
					$element .
				'</div>
			</div>
		</div>';
}

public static function buttons_element($options = array())
{

	switch ($options['size']) {
		case 'big':
		$button_class = 'big';
		break;

		case 'medium':
		$button_class = 'medium';
		break;

		case 'small':
		$button_class = 'small';
		break;

		case 'xsmall':
		$button_class = 'xsmall';
		break;

		default:
		$button_class = 'medium';
		break;
	}

	$button_align = (isset($options['button-align'])) ? strip_tags($options['button-align']) : '';
	$class_mode_display = (isset($options['mode-display']) && ($options['mode-display'] === 'background-button' || $options['mode-display'] === 'border-button')) ? $options['mode-display'] : 'background-button';
	$border_color = (isset($options['border-color']) && !empty($options['border-color']) && is_string($options['border-color'])) ? esc_attr($options['border-color']) : 'inherit';
	$background_color = (isset($options['bg-color']) && !empty($options['bg-color']) && is_string($options['bg-color'])) ? esc_attr($options['bg-color']) : 'inherit';
	$text_color = (isset($options['text-color']) && is_string($options['text-color'])) ? esc_attr($options['text-color']) : '';
	$iconAlign = (isset($options['icon-align']) && $options['icon-align'] == 'above-text') ? 'ts-icon-above-text' : 'ts-icon-left-of-text';

	$effect = isset($options['effect']) && $options['effect'] !== 'none' ? $options['effect'] : 'none';
	$classDelay = isset($options['delay']) && $options['delay'] !== 'none' ? ' '. $options['delay'] : '';

	if ( isset( $options['button-icon'] ) && $options['button-icon'] !== 'icon-noicon' ) {
		$button_class .= ' button-has-icon ' . $options['button-icon'];
	}

	$options['url'] = esc_url($options['url']);
	$textColorHover = isset($options['text-hover-color']) ? $options['text-hover-color'] : '#fff';

	$colors = '';

	if ( $options['mode-display'] == 'background-button' ) {

		$bgHoverColor = isset($options['bg-hover-color']) ? $options['bg-hover-color'] : '#fff';

		$colors = 'style="background-color: '. $background_color .'; color: '. $text_color .';"';
		$button_class .= ' bg-button ';

	}else{
			//$borderColor
		$colors = 'style="border-color: '. $border_color .'; color: '. $text_color .';"';
		$button_class .= ' outline-button ';
		$borderHoverColor = isset($options['border-hover-color']) ? $options['border-hover-color'] : '#fff';
	}

	$randClass = uniqid('ts-');

	if ( ! isset($options['target']) ) {
		$options['target'] = '_blank';
	}

	$mouseOver = $options['mode-display'] == 'background-button' ? 'backgroundColor=\''. $bgHoverColor .'\';this.style.color=\''. $textColorHover .'\'"' : 'borderColor=\''. $borderHoverColor .'\';this.style.color=\''. $textColorHover .'\'"';
	$mouseOut = $options['mode-display'] == 'background-button' ? 'backgroundColor=\''. $background_color .'\';this.style.color=\''. $text_color .'\'"' : 'borderColor=\''. $border_color .'\';this.style.color=\''. $text_color .'\'"';

	if ( isset( $options['short'] ) ) {

       	$start = '';

       	$end = '';

   	} else {

   		$start = '<div class="col-lg-12 col-md-12 col-sm-12 '. $button_align . ($effect !== 'none' ? ' animatedParent animateOnce' : '') .'">';

   		$end = '</div>';

   	}

	return 	$start .
				'<a onMouseOver="this.style.'. $mouseOver .' onMouseOut="this.style.'. $mouseOut .' href="' . esc_url($options['url']) . '" target="' . esc_attr($options['target']) .'" class="ts-button ' . $button_class . $iconAlign . ($effect !== 'none' ? ' animated '. $effect . $classDelay : '') . ' '. $randClass .'" ' . $colors . '>'.
					stripslashes($options['text']) .
				'</a>' .
			$end;
}

public static function shortcodes_element($options = array()) {
	$paddings = (isset($options['paddings']) && ($options['paddings'] == 'y' || $options['paddings'] == 'n')) ? $options['paddings'] : 'n';
	$div_paddings_start = $paddings == 'n' ? '<div class="col-lg-12 col-md-12 col-sm-12">' : '';
	$div_paddings_end = $paddings == 'n' ? '</div>' : '';
	return $div_paddings_start . '<div class="ts-shortcode-element">
	' . apply_filters('ts_the_content', stripslashes(@$options['shortcodes'])) . '
</div>' . $div_paddings_end;
}

public static function banner_element($options = array()) {

	if( isset($options) && $options != '' ){

		$banner_img = ( isset($options['banner-image']) ) ? esc_url($options['banner-image']) : '';

		$banner_title = ( isset($options['banner-title']) ) ? strip_tags($options['banner-title']) : '';

		$banner_subtitle = ( isset($options['banner-subtitle']) ) ? strip_tags($options['banner-subtitle']) : '';

		$banner_button_title = ( isset($options['banner-button-title']) ) ? strip_tags($options['banner-button-title']) : '';

		$banner_button_url = ( isset($options['banner-button-url']) ) ? esc_url($options['banner-button-url']) : '';

		$banner_button_background = ( isset($options['banner-button-background']) ) ? strip_tags($options['banner-button-background']) : '';

		$banner_font_color = ( isset($options['banner-font-color']) ) ? strip_tags($options['banner-font-color']) : '';

		$banner_text_align = ( isset($options['banner-text-align']) ) ? strip_tags($options['banner-text-align']) : '';

		$banner_img_height = ( isset($options['banner-height']) ) ? strip_tags((int)$options['banner-height']) : '';

		$bannerButtonTextColor = (isset($options['button-text-color'])) ? esc_attr($options['button-text-color']) : 'inherit';

		$banner_box = '	<div class="col-lg-12 col-md-12">
		<div data-alignment="middle" class="ts-banner-box ts-kenburns ' . $banner_text_align . '" style="color: ' . $banner_font_color . ';height: ' . $banner_img_height . 'px; ">
			<div class="ts-kenburns-inner" style="background: url('.$banner_img.') no-repeat center top; background-size: cover;"></div>
			<article class="container">
				<div class="inner-banner">
					<h1 class="title">' . $banner_title . '</h1>
					<div class="subtitle">' . $banner_subtitle . '</div>
					<a class="banner-btn" href="' . $banner_button_url . '" style="background-color:' . $banner_button_background . '; color: ' . $bannerButtonTextColor . '">' . $banner_button_title . '<i class="icon-right"></i></a>
				</div>
			</article>
		</div>
	</div>
	';

	return $banner_box;

}
}

public static function toggle_element($options = array()) {

	if( isset($options) && $options != '' ){

		$toggle_title = ( isset($options['toggle-title']) ) ? strip_tags($options['toggle-title']) : '';
		$toggle_description = ( isset($options['toggle-description']) ) ? apply_filters('ts_the_content', $options['toggle-description']) : '';
		$toggle_state = ( isset($options['toggle-state']) ) ? strip_tags($options['toggle-state']) : '';
		$toggle_collapse = $options['toggle-state'] == 'open' ? 'in':'';
		$id_toggle = $randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 7);
		$div_large_start = !isset($options['shortcode-div-large']) ? '<div class="col-lg-12 col-md-12">' : '';
		$div_large_end = !isset($options['shortcode-div-large']) ? '</div>' : '';

		$toggle_box =
			$div_large_start .
				'<div class="ts-toggle-box ' . $toggle_state . ' panel-group" id="toggle-id-'. $id_toggle .'">
					<div class="panel panel-default">
						<div class="panel-heading toggle-title">
							<a data-toggle="collapse" data-parent="#toggle-id-'. $id_toggle .'" href="#'. $id_toggle .'"><i class="icon-right"></i>' . $toggle_title . '</a>
						</div>
						<div id="'. $id_toggle .'" class="panel-collapse collapse ' . $toggle_collapse . '">
							<div class="panel-body">
								' . $toggle_description . '
							</div>
						</div>
					</div>
				</div>'.
			$div_large_end;

	return $toggle_box;
}
}

public static function tab_element( $options = array() )
{
    $tabs = isset( $options['tab'] ) && ! empty( $options['tab'] ) && $options['tab'] !== '[]' ? json_decode( stripslashes( $options['tab'] ) ) : NULL;

    if( ! is_array( $tabs ) || empty( $tabs ) ) return;

    $mode = isset( $options['mode'] ) ? $options['mode'] : 'horizontal';

    $i = 0;
		$content = '';
		$li = '';

    foreach ( $tabs as $tab ) {

    	$id = md5( rand() );
        $active = $i == 0 ? ' active' : '';

        $li .= '<li class="ts-item-tab' . $active . '">
					<a href="#' . $id . '">' .
						esc_html( str_replace( '--quote--', '"', $tab->title ) ) .
					'</a>
				</li>';

        $desc = isset( $options['short'] ) ? str_replace( '--quote--', '"', $tab->text ) : nl2br( str_replace( '--quote--', '"', $tab->text ) );

        $content .= '<div class="tab-pane' . $active . '" id="' . $id . '">' . do_shortcode( $desc ) . '</div>';

        $i++;
    }

    if ( isset( $options['short'] ) ) {

    	$start = '';

    	$end = '';

    } else {

    	$start = '<div class="col-lg-12 col-md-12">';

    	$end = '</div>';

    }

    return 	$start .
    			'<div class="ts-tab-container" data-display="' . $mode . '">
					<ul class="nav nav-tabs">' .
						$li .
					'</ul>
					<div class="tab-content">' .
						$content .
					'</div>
				</div>' .
			$end;
}

public static function list_videos_element($options = array(), &$original_query = null)
{
	$exclude_posts = ( isset($options['id-exclude']) && $options['id-exclude'] != '' ) ? explode(',',@$options['id-exclude']) : NULL;
	$exclude_first = ( isset($options['exclude-first']) ) ? (int)$options['exclude-first'] : '';
	$exclude_id    = array();
	$featured      = (isset($options['featured']) && $options['featured'] !== '' && ($options['featured'] == 'y' || $options['featured'] == 'n')) ? $options['featured'] : 'n';
	$scroll        = (isset($options['behavior']) && $options['behavior'] === 'scroll') ? ' scroll-view' : '';
	$layout_mosaic = (isset($options['layout']) && ($options['layout'] == 'rectangles' || $options['layout'] == 'square')) ? $options['layout'] : 'square';
	$categories    = (isset($options['category']) && !empty($options['category']) && is_string($options['category'])) ? explode(',', $options['category']) : '';
	$img_rows      = (isset($options['rows']) && $options['rows'] !== '' && (int)$options['rows'] !== 0) ? (int)$options['rows'] : '3';
	$pagination    = (isset($options['pagination']) && ($options['pagination'] === 'n' || $options['pagination'] === 'y' || $options['pagination'] ==='load-more' || $options['pagination'] == 'infinite')) ? $options['pagination'] : 'n';
	$ajax_load_more = (isset($options['ajax-load-more']) && $options['ajax-load-more'] === true) ? true : false;
	$display_load_more = ($pagination == 'infinite') ? 'style="display: none;"' : '';
	$infinite_scroll = ($pagination == 'infinite') ? ' ts-infinite-scroll' : '';

	if( isset($exclude_posts) ){
		foreach($exclude_posts as $transform_to_integer){
			if( $transform_to_integer != 0 && $transform_to_integer != '' ) $exclude_id[] = (int)$transform_to_integer;
		}
	}

	$pagination_content = '';

	$display_effect = 'no-effect';
	if( isset( $options['special-effects'] ) ){

		if( $options['special-effects'] === 'opacited' ){
			$display_effect = 'animated opacited';
		} elseif( $options['special-effects'] === 'rotate-in' ){
			$display_effect = 'animated rotate-in';
		} elseif( $options['special-effects'] === '3dflip' ){
			$display_effect = 'animated flip';
		} elseif( $options['special-effects'] === 'scaler' ){
			$display_effect = 'animated scaler';
		}
	}

	if (isset($options['taxonomy'])) {
		$taxonomy = $options['taxonomy'];
	} else {
		$taxonomy = 'category';
	}

		// Display elements for grid mode
	if ($options['display-mode'] === 'grid') {

		$vdf_masonry_class = '';
		if ( @$options['behavior'] == 'masonry' ) {
			$vdf_masonry_class = ' ts-filters-container ';
		}

		$grid_view_start = '<section class="ts-grid-view ' . $display_effect . $vdf_masonry_class . $scroll . ' ' . self::get_clear_class($options['elements-per-row']) . ' ">';
		$grid_view_end = '</section>';

		$carousel_wrapper_start = '<div class="carousel-wrapper">';
		$carousel_wrapper_end = '</div>';

		$carousel_container_start = '<div class="carousel-overview"><div class="carousel-container">';
		$carousel_container_end = '</div></div>';

		$carousel_navigation = '<ul class="carousel-nav">
		<li class="carousel-nav-left icon-left">
			<span class="hidden_btn">'.esc_html__('prev','videofly').'</span>
		</li>
		<li class="carousel-nav-right icon-right">
			<span class="hidden_btn">'.esc_html__('next','videofly').'</span>
		</li>
	</ul>';

	$tab_category_html = '';

	if ( $original_query === null ) {

		$order    = self::order_direction($options['order-direction']);

		if ( get_query_var('paged') ) {
			$current = get_query_var('paged');
		} elseif ( get_query_var('page') ) {
			$current = get_query_var('page');
		} else {
			$current = 1;
		}

		$args = array(
			'post_type' => 'video',
			'order' => $order,
			'post__not_in' => $exclude_id,
			'paged' => $current,
			'posts_per_page' => (int)$options['posts-limit']
			);

		if( $pagination === 'load-more' || $pagination === 'n' || $pagination == 'infinite' ){
			$args['offset'] = $exclude_first;
		}

		$select_by_category = (isset($options['behavior']) && $options['behavior'] == 'tabbed') ? 'tabbed' : '';

		if ( is_array($categories) && count($categories) > 0 ){

			if( $select_by_category == 'tabbed' ){
				$tab_category_html = '<div class="col-lg-12">
				<ul class="ts-select-by-category">';
					$category_i = 1;
					$tab_div_category = '';

					foreach($categories as $key=>$slug_category){
						$active_item = $key == 0 ? ' class="active"' : '';
						$category = get_term_by('slug', $slug_category, 'videos_categories');
						if( is_object($category) ){
							$tab_category_html .= '<li' . $active_item . ' data-category-li="' . $category->term_id . '"><a href="#">' . $category->name . '</a></li>';
							if( $category_i == count($categories) ) $tab_category_html .= '</ul>';
							$tab_div_category .= '<div data-category-div="' . $category->term_id . '"><div class="row ts-cat-row"></div></div>';
						}
						$category_i++;
					}
					$tab_category_html .= $tab_div_category . '</div>';
				}

				$args['tax_query'] = array(
					array(
						'taxonomy' => 'videos_categories',
						'field'    => 'slug',
						'terms'    => $categories
						)
					);
			} else {
				$args['category__in'] = array(0);
			}

			$args = self::order_by($options['order-by'], $args, $featured);

			$query = new WP_Query( $args );

		} else {
			$query = &$original_query;
		}

		$row = array();

		if ( $query->have_posts() ) {
			ob_start();
			ob_clean();
			global $article_options;
			$article_options = $options;
			$article_options['j'] = $query->post_count;
			$article_options['i'] = 1;
			$article_options['k'] = 1;

			while ( $query->have_posts() ) {

				$query->the_post();
				get_template_part('includes/layout-builder/templates/grid-view');
			}
			$elements = ob_get_clean();

			if ( isset($options['pagination']) && $options['pagination'] == 'y' ) {
				ob_start();
				ob_clean();
				global $vdf_list_query;
				$vdf_list_query = $query;
				get_template_part('template-pagination');
				$pagination_content = ob_get_clean();
			}
			wp_reset_postdata();

		} else {
			return $grid_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . esc_html__( 'Nothing Found', 'videofly' ) . '</div>' . $grid_view_end;
		}

		if ( $original_query === null ) {
			$next_prev_links = '';
		} else {
			$next_prev_links = self::archive_navigation();
		}

		$args['options'] = $options;
		$load_more = (($pagination === 'load-more' || $pagination == 'infinite') && $ajax_load_more === false) ? '<div ' . $display_load_more . ' class="ts-pagination-more' . $infinite_scroll . '" data-loop="1" data-args="' . ts_enc_string(serialize($args)) . '"><i class="icon-restart"></i><span>' .esc_html__('Load More', 'videofly') . '</span>' . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

		if (@$options['behavior'] === 'carousel') {
			return $grid_view_start . $carousel_wrapper_start . $carousel_navigation . $carousel_container_start . $elements . $carousel_container_end . $carousel_wrapper_end . $grid_view_end;
		}else if( isset($options['behavior']) && $options['behavior'] === 'scroll' ) {
			return  $grid_view_start . '<div class="row">' . $elements .'</div>'. $grid_view_end . $next_prev_links . $pagination_content;
		}else if( $ajax_load_more === true ){
			return  $elements;
		}else{
			return  $grid_view_start . $tab_category_html . $elements . $grid_view_end . $load_more . $next_prev_links . $pagination_content;
		}

	} else if ($options['display-mode'] === 'list') {

		$list_view_start = '<section class="ts-list-view ' . $display_effect . ' ">';
		$list_view_end = '</section>';

		if ( $original_query === null ) {

			$order    = self::order_direction($options['order-direction']);

			if ( get_query_var('paged') ) {
				$current = get_query_var('paged');
			} elseif ( get_query_var('page') ) {
				$current = get_query_var('page');
			} else {
				$current = 1;
			}

			$args = array(
				'post_type' => 'video',
				'order' => $order,
				'paged' => $current,
				'post__not_in' => $exclude_id,
				'posts_per_page' => (int)$options['posts-limit']

				);

			if( $pagination === 'load-more' || $pagination === 'n' || $pagination == 'infinite' ){
				$args['offset'] = $exclude_first;
			}

			if ( is_array($categories) && count($categories) > 0 ) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'videos_categories',
						'field' => 'slug',
						'terms' => $categories
						)
					);
			} else {
				$args['category__in'] = array(0);
			}

			$args = self::order_by($options['order-by'], $args, $featured);

			$query = new WP_Query( $args );

		} else {
			$query = &$original_query;
		}

		$articles = array();
		if ( $query->have_posts() ) {
			ob_start();
			ob_clean();
			global $article_options;
			$article_options = $options;
			while ( $query->have_posts() ) {
				$query->the_post();
				get_template_part('includes/layout-builder/templates/list-view');
			}
			$elements = ob_get_clean();

			if ( isset($options['pagination']) && $options['pagination'] == 'y' ) {
				ob_start();
				ob_clean();
				global $vdf_list_query;
				$vdf_list_query = $query;
				get_template_part('template-pagination');
				$pagination_content = ob_get_clean();
			}

			wp_reset_postdata();

		} else {
			return $list_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . esc_html__( 'Nothing Found', 'videofly' ) . '</div>' . $list_view_end;
		}

		if ( $original_query === null ) {
			$next_prev_links = '';
		} else {
			$next_prev_links = self::archive_navigation();
		}

		$args['options'] = $options;
		$load_more = (($pagination === 'load-more' || $pagination == 'infinite') && $ajax_load_more === false) ? '<div ' . $display_load_more . ' class="ts-pagination-more' . $infinite_scroll . '" data-loop="1" data-args="' . ts_enc_string(serialize($args)) . '"><i class="icon-restart"></i><span>' .esc_html__('Load More', 'videofly') . '</span>' . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

		if( $ajax_load_more === true ){
			return $elements;
		}else{
			return $list_view_start . $elements . $list_view_end . $load_more . $next_prev_links . $pagination_content;
		}


	} else if ($options['display-mode'] === 'thumbnails') {

		$use_gutter = '';

		if( isset($options['gutter']) ){
			if( $options['gutter'] === 'y' ){
				$use_gutter = ' no-gutter';
			}
		}

		$vdf_masonry_class = '';
		if ( isset($options['behavior']) && @$options['behavior'] == 'masonry' ) {
			$vdf_masonry_class = ' ts-filters-container ';
		}

		$thumbnails_view_start = '<section class="ts-thumbnail-view ' . $display_effect . $scroll . $use_gutter . $vdf_masonry_class . ' ' . self::get_clear_class($options['elements-per-row']) . ' ">';
		$thumbnails_view_end = '</section>';

		$carousel_navigation = '<ul class="carousel-nav">
		<li class="carousel-nav-left icon-left">
			<span class="hidden_btn">'.esc_html__('prev','videofly').'</span>
		</li>
		<li class="carousel-nav-right icon-right">
			<span class="hidden_btn">'.esc_html__('next','videofly').'</span>
		</li>
	</ul>';

	$carousel_wrapper_start = '<div class="carousel-wrapper">';
	$carousel_wrapper_end = '</div>';

	$carousel_container_start = '<div class="carousel-overview"><div class="carousel-container">';
	$carousel_container_end = '</div></div>';

	$valid_columns = array(1, 2, 3, 4, 6);

	if ( ! in_array($options['elements-per-row'], $valid_columns)) {
		$options['elements-per-row'] = 3;
	}

	$tab_category_html = '';

	if ( $original_query === null ) {

		$order    = self::order_direction($options['order-direction']);

		if ( get_query_var('paged') ) {
			$current = get_query_var('paged');
		} elseif ( get_query_var('page') ) {
			$current = get_query_var('page');
		} else {
			$current = 1;
		}

		$args = array(
			'post_type' => 'video',
			'order' => $order,
			'paged' => $current,
			'post__not_in' => $exclude_id,
			'posts_per_page' => (int)$options['posts-limit']
			);

		if( $pagination === 'load-more' || $pagination === 'n' || $pagination == 'infinite' ){
			$args['offset'] = $exclude_first;
		}

		$select_by_category = (isset($options['behavior']) && $options['behavior'] == 'tabbed') ? 'tabbed' : '';

		if ( is_array($categories) && count($categories) > 0 ) {

			if( $select_by_category == 'tabbed' ){
				$tab_category_html = '<div class="col-lg-12">
				<ul class="ts-select-by-category">';
					$category_i = 1;
					$tab_div_category = '';

					foreach($categories as $key=>$slug_category){
						$active_item = $key == 0 ? ' class="active"' : '';
						$category = get_term_by('slug', $slug_category, 'videos_categories');
						if( is_object($category) ){
							$tab_category_html .= '<li' . $active_item . ' data-category-li="' . $category->term_id . '"><a href="#">' . $category->name . '</a></li>';
							if( $category_i == count($categories) ) $tab_category_html .= '</ul>';
							$tab_div_category .= '<div data-category-div="' . $category->term_id . '"><div class="row ts-cat-row"></div></div>';
						}
						$category_i++;
					}
					$tab_category_html .= $tab_div_category . '</div>';
				}

				$args['tax_query'] = array(
					array(
						'taxonomy' => 'videos_categories',
						'field' => 'slug',
						'terms' => $categories
						)
					);
			} else {
				$args['category__in'] = array(0);
			}

			$args = self::order_by($options['order-by'], $args, $featured);

			$query = new WP_Query( $args );
		} else {
			$query = &$original_query;
		}

		$elements = array();

		if ( $query->have_posts() ) {
			ob_start();
			ob_clean();
			global $article_options;
			$article_options = $options;
			$article_options['i'] = 1;
			$article_options['j'] = $query->post_count;

			while ( $query->have_posts() ) {
				$query->the_post();
				get_template_part('includes/layout-builder/templates/thumbs-view');
			}
			$elements = ob_get_clean();

			if ( isset($options['pagination']) && $options['pagination'] == 'y' ) {
				ob_start();
				ob_clean();
				global $vdf_list_query;
				$vdf_list_query = $query;
				get_template_part('template-pagination');
				$pagination_content = ob_get_clean();
			}

			wp_reset_postdata();

		} else {
			return $thumbnails_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . esc_html__( 'Nothing Found', 'videofly' ) . '</div>' . $thumbnails_view_end;
		}

		if ( $original_query === null ) {
			$next_prev_links = '';
			/* Restore original Post Data */
			wp_reset_postdata();
		} else {
			$next_prev_links = self::archive_navigation();
		}

		if( ! isset( $options['behavior'] ) ){
			@$options['behavior'] = 'none';
		}


		$args['options'] = $options;
		$load_more = (($pagination === 'load-more' || $pagination == 'infinite') && $ajax_load_more === false) ? '<div ' . $display_load_more . ' class="ts-pagination-more' . $infinite_scroll . '" data-loop="1" data-args="' . ts_enc_string(serialize($args)) . '"><i class="icon-restart"></i><span>' .esc_html__('Load More', 'videofly') . '</span>' . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

		if (@$options['behavior'] === 'carousel') {
			return $thumbnails_view_start . $carousel_wrapper_start . $carousel_navigation . $carousel_container_start . $elements . $carousel_container_end. $carousel_wrapper_end . $thumbnails_view_end;
		} else if( @$options['behavior'] === 'scroll' ) {
			return $thumbnails_view_start . '<div class="row">' . $elements .'</div>' . $thumbnails_view_end . $next_prev_links . $pagination_content;
		} else if( $ajax_load_more === true ) {
			return $elements;
		} else {
			return $thumbnails_view_start . $tab_category_html . $elements . $thumbnails_view_end . $load_more . $next_prev_links . $pagination_content;
		}

	} else if ($options['display-mode'] === 'big-post') {

		$big_post_view_start = '<section class="ts-big-posts ' . $display_effect . ' ">';
		$big_post_view_end = '</section>';

		$carousel = (isset($options['carousel']) && ($options['carousel'] == 'y' || $options['carousel'] == 'n')) ? $options['carousel'] : 'n';
		$carousel_wrapper_start = ($carousel == 'y') ? '<div class="carousel-wrapper">' : '';
		$carousel_wrapper_end = ($carousel == 'y') ? '</div>' : '';

		$carousel_container_start = '<div class="carousel-overview"><div class="carousel-container">';
		$carousel_container_end = '</div></div>';

		$carousel_navigation = ($carousel == 'y') ? '<ul class="carousel-nav">
		<li class="carousel-nav-left icon-left">
			<span class="hidden_btn">'.esc_html__('prev','videofly').'</span>
		</li>
		<li class="carousel-nav-right icon-right">
			<span class="hidden_btn">'.esc_html__('next','videofly').'</span>
		</li>
	</ul>' : '';

	if ( $original_query === null ) {

		$order    = self::order_direction($options['order-direction']);

		if ( get_query_var('paged') ) {
			$current = get_query_var('paged');
		} elseif ( get_query_var('page') ) {
			$current = get_query_var('page');
		} else {
			$current = 1;
		}

		$args = array(
			'post_type' => 'video',
			'order' => $order,
			'post__not_in' => $exclude_id,
			'posts_per_page' => (int)$options['posts-limit'],
			'paged' => $current
			);

		if( $pagination === 'load-more' || $pagination === 'n' || $pagination == 'infinite' ){
			$args['offset'] = $exclude_first;
		}

		if ( is_array($categories) && count($categories) > 0 ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'videos_categories',
					'field' => 'slug',
					'terms' => $categories
					)
				);
		} else {
			$args['category__in'] = array(0);
		}

		$args = self::order_by($options['order-by'], $args, $featured);

		$query = new WP_Query( $args );

	} else {
		$query = &$original_query;
	}

	$elements = array();

	if ( $query->have_posts() ) {
		ob_start();
		ob_clean();
		global $article_options;
		$article_options = $options;
		$article_options['i'] = 1;

		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part('includes/layout-builder/templates/big-posts');
		}
		$elements = ob_get_clean();

		if ( isset($options['pagination']) && $options['pagination'] == 'y' ) {
			ob_start();
			ob_clean();
			global $vdf_list_query;
			$vdf_list_query = $query;
			get_template_part('template-pagination');
			$pagination_content = ob_get_clean();
		}

		wp_reset_postdata();

	} else {
		return $big_post_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . esc_html__( 'Nothing Found', 'videofly' ) . '</div>' . $big_post_view_end;
	}

	if ( $original_query === null ) {
		$next_prev_links = '';
	} else {
		$next_prev_links = self::archive_navigation();
	}

	$args['options'] = $options;
	$load_more = (($pagination === 'load-more' || $pagination == 'infinite') && $ajax_load_more === false) ? '<div ' . $display_load_more . ' class="ts-pagination-more' . $infinite_scroll . '" data-loop="1" data-args="' . ts_enc_string(serialize($args)) . '"><i class="icon-restart"></i><span>' .esc_html__('Load More', 'videofly') . '</span>' . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

	if( $ajax_load_more === true ){
		return $elements;
	}elseif( $pagination == 'y' ){
		return $big_post_view_start . $carousel_wrapper_start . $carousel_navigation . $carousel_container_start . $elements . $carousel_container_end. $carousel_wrapper_end . $big_post_view_end;
	}else{
		return $big_post_view_start . $elements . $big_post_view_end . $load_more . $next_prev_links . $pagination_content;
	}

} else if ($options['display-mode'] == 'super-post') {

	$super_post_view_start = '<section class="ts-super-posts ' . $display_effect . ' ">';
	$super_post_view_end = '</section>';

	$valid_columns = array(1, 2, 3);

	if ( ! in_array($options['elements-per-row'], $valid_columns) ) {
		$options['elements-per-row'] = 1;
	}

	$elements = array();

	if ( $original_query === null ) {

		$order    = self::order_direction($options['order-direction']);

		if ( get_query_var('paged') ) {
			$current = get_query_var('paged');
		} elseif ( get_query_var('page') ) {
			$current = get_query_var('page');
		} else {
			$current = 1;
		}

		$args = array(
			'post_type' => 'video',
			'order' => $order,
			'paged' => $current,
			'post__not_in' => $exclude_id,
			'posts_per_page' => (int)$options['posts-limit']
			);

		if( $pagination === 'load-more' || $pagination === 'n' || $pagination == 'infinite' ){
			$args['offset'] = $exclude_first;
		}

		if ( is_array($categories) && count($categories) > 0 ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'videos_categories',
					'field'    => 'slug',
					'terms'    => $categories
					)
				);
		} else {
			$args['category__in'] = array(0);
		}

		$args = self::order_by($options['order-by'], $args, $featured);

		$query = new WP_Query( $args );

	} else {
		$query = &$original_query;
	}

	$elements = array();

	if ( $query->have_posts() ) {
		ob_start();
		ob_clean();
		global $article_options;
		$article_options = $options;
		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part('includes/layout-builder/templates/super-posts');
		}
		$elements = ob_get_clean();

		if ( isset($options['pagination']) && $options['pagination'] == 'y' ) {
			ob_start();
			ob_clean();
			global $vdf_list_query;
			$vdf_list_query = $query;
			get_template_part('template-pagination');
			$pagination_content = ob_get_clean();
		}

		wp_reset_postdata();

	} else {
		return $super_post_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . esc_html__( 'Nothing Found', 'videofly' ) . '</div>' . $super_post_view_end;
	}

	if ( $original_query === null ) {
		$next_prev_links = '';
	} else {
		$next_prev_links = self::archive_navigation();
	}

	$args['options'] = $options;
	$load_more = (($pagination === 'load-more' || $pagination == 'infinite') && $ajax_load_more === false) ? '<div ' . $display_load_more . ' class="ts-pagination-more' . $infinite_scroll . '" data-loop="1" data-args="' . ts_enc_string(serialize($args)) . '"><i class="icon-restart"></i><span>' .esc_html__('Load More', 'videofly') . '</span>' . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

	if( $ajax_load_more ){
		return $elements;
	}else{
		return $super_post_view_start . $elements . $super_post_view_end . $load_more . $next_prev_links . $pagination_content;
	}

} else if ($options['display-mode'] === 'timeline') {

	if ( $original_query === null ) {

		$order    = self::order_direction($options['order-direction']);

		if ( get_query_var('paged') ) {
			$current = get_query_var('paged');
		} elseif ( get_query_var('page') ) {
			$current = get_query_var('page');
		} else {
			$current = 1;
		}

		$args = array(
			'post_type' => 'video',
			'order' => $order,
			'paged' => $current,
			'post__not_in' => $exclude_id,
			'posts_per_page' => (int)$options['posts-limit']
			);

		if( $pagination === 'load-more' || $pagination === 'n' || $pagination == 'infinite' ){
			$args['offset'] = $exclude_first;
		}

		if ( is_array($categories) && count($categories) > 0 ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'videos_categories',
					'field'    => 'slug',
					'terms'    => $categories
					)
				);
		} else {
			$args['category__in'] = array(0);
		}

		$args = self::order_by($options['order-by'], $args, $featured);

		$query = new WP_Query( $args );

	} else {
		$query = &$original_query;
	}

	$articles = array();
	if ( $query->have_posts() ) {

		ob_start();
		ob_clean();
		global $article_options;
		$article_options = $options;
		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part('includes/layout-builder/templates/timeline-view');
		}
		$elements = ob_get_clean();

		if ( isset($options['pagination']) && $options['pagination'] == 'y' ) {
			ob_start();
			ob_clean();
			global $vdf_list_query;
			$vdf_list_query = $query;
			get_template_part('template-pagination');
			$pagination_content = ob_get_clean();
		}

		wp_reset_postdata();

	} else {
		return '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . esc_html__( 'Nothing Found', 'videofly' ) . '</div>';
	}

	if ( $original_query === null ) {
		$next_prev_links = '';
	} else {
		$next_prev_links = self::archive_navigation();
	}

	$args['options'] = $options;
	$load_more = (($pagination === 'load-more' || $pagination == 'infinite') && $ajax_load_more === false) ? '<div ' . $display_load_more . ' class="ts-pagination-more' . $infinite_scroll . '" data-loop="1" data-args="' . ts_enc_string(serialize($args)) . '"><i class="icon-restart"></i><span>' .esc_html__('Load More', 'videofly') . '</span>' . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

	if ( $ajax_load_more === true ) {
		return $elements;
	}else{
		return '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><section class="ts-timeline-view" data-alignment="left">' . $elements . '</section>' . $load_more . '</div>' . $next_prev_links . $pagination_content;
	}

} else if ($options['display-mode'] === 'mosaic') {

	$effect_scroll = (isset($options['effects-scroll']) && $options['effects-scroll'] !== '' && $options['effects-scroll'] == 'default') ? '' : ' fade-effect';

	$gutter_class = (isset($options['gutter']) && $options['gutter'] !== '' && $options['gutter'] == 'y') ? ' mosaic-with-gutter ' : ' mosaic-no-gutter ';

	$scroll = (isset($options['scroll']) && !empty($options['scroll']) && $options['scroll'] == 'y') ?
	'<div data-scroll="true" class="mosaic-view '. $effect_scroll . $gutter_class . ' mosaic-' . $options['layout'] . '">'
	:
	'<div data-scroll="false" class="mosaic-view '. $gutter_class .' mosaic-' . $options['layout'] . '">';

	$img_rows = (isset($options['rows']) && $options['rows'] !== '' && (int)$options['rows'] !== 0) ? (int)$options['rows'] : '3';
	$layout_mosaic = (isset($options['layout']) && ($options['layout'] == 'rectangles' || $options['layout'] == 'square')) ? $options['layout'] : 'square';

	if ( $original_query === null ) {

		$order    = self::order_direction($options['order-direction']);

		if ( get_query_var('paged') ) {
			$current = get_query_var('paged');
		} elseif ( get_query_var('page') ) {
			$current = get_query_var('page');
		} else {
			$current = 1;
		}

		$args = array(
			'post_type' => 'video',
			'posts_per_page' => (int)$options['posts-limit'],
			'paged' => $current,
			'order' => $order,
			'post__not_in' => $exclude_id

			);

		if( $options['scroll'] == 'y' || ($scroll == 'n' && ($pagination === 'n' || $pagination === 'load-more' || $pagination == 'infinite')) ){
			$args['offset'] = $exclude_first;
		}

		if ( is_array($categories) && count($categories) > 0 ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'videos_categories',
					'field'    => 'slug',
					'terms'    => $categories
					)
				);
		} else {
			$args['category__in'] = array(0);
		}

		$args = self::order_by($options['order-by'], $args, $featured);

		$query = new WP_Query( $args );

	} else {
		$query = &$original_query;
	}

	$articles = array();
	if ( $query->have_posts() ) {
		ob_start();
		ob_clean();
		global $article_options;
		$article_options = $options;
		$article_options['i'] = (isset($options['loop'])) ? (int)$options['loop'] + 1 : 1;
		$article_options['j'] = $query->post_count;
		$article_options['k'] = 1;

		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part('includes/layout-builder/templates/mosaic-view');
			$article_options['i']++;
			$article_options['k']++;
			if( $article_options['k'] === 7 && $layout_mosaic == 'rectangles' && $img_rows == 2  ){
				$article_options['k'] = 1;
			}
			if( $article_options['k'] === 10 && $layout_mosaic == 'rectangles' && $img_rows == 3  ){
				$article_options['k'] = 1;
			}
			if( $article_options['k'] === 6 && $layout_mosaic == 'square' ){
				$article_options['k'] = 1;
			}
		}

		$elements = ob_get_clean();

		$pagination_content = '';
		if ( $options['scroll'] == 'n' && $pagination == 'y' ) {
			ob_start();
			ob_clean();
			global $vdf_list_query;
			$vdf_list_query = $query;
			get_template_part('template-pagination');
			$pagination_content = ob_get_clean();
		}

		wp_reset_postdata();

	} else {
		return '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . esc_html__( 'Nothing Found', 'videofly' ) . '</div>';
	}

	$args['options'] = $options;
	$load_more = ($options['scroll'] == 'n' && ($pagination == 'load-more' || $pagination == 'infinite') && $ajax_load_more == false) ? '<div ' . $display_load_more . ' class="ts-pagination-more' . $infinite_scroll . '" data-loop="1" data-args="' . ts_enc_string(serialize($args)) . '"><i class="icon-restart"></i><span>' .esc_html__('Load More', 'videofly') . '</span>' . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

	if ( $original_query === null ) {
		$next_prev_links = '';
	} else {
		$next_prev_links = self::archive_navigation();
	}

	if( $ajax_load_more ){
		return $elements;
	}else{
		return $scroll . $elements . $next_prev_links . '</div>' . $load_more . $pagination_content;
	}

}

}

public static function breadcrumbs_element($options = array()){
	if( isset($options['type']) && $options['type']  === 'breadcrumbs' ){
		return vdf_breadcrumbs();
	}
}

public static function latest_custom_posts_element($options = array(), &$original_query = null)
{
	$exclude_posts  = ( isset($options['id-exclude']) && $options['id-exclude'] != '' ) ? explode(',',@$options['id-exclude']) : NULL;
	$exclude_first  = ( isset($options['exclude-first']) ) ? (int)$options['exclude-first'] : '';
	$exclude_id     = array();
	$featured       = (isset($options['featured']) && $options['featured'] !== '' && ($options['featured'] == 'y' || $options['featured'] == 'n')) ? $options['featured'] : 'n';
	$post_types     = (isset($options['post-type']) && !empty($options['post-type']) && is_string($options['post-type'])) ? explode(',', $options['post-type']) : '';
	$pagination     = (isset($options['pagination']) && ($options['pagination'] === 'n' || $options['pagination'] === 'y' || $options['pagination'] ==='load-more' || $options['pagination'] ==='infinite')) ? $options['pagination'] : 'n';
	$ajax_load_more = (isset($options['ajax-load-more']) && $options['ajax-load-more'] === true) ? true : false;
	$pagination_content = '';
	$categories = (isset($options['category']) && !empty($options['category']) && is_string($options['category'])) ? explode(',', $options['category']) : NULL;
	$display_load_more = ($pagination == 'infinite') ? 'style="display: none;"' : '';
	$infinite_scroll = ($pagination == 'infinite') ? ' ts-infinite-scroll' : '';

	if( isset($exclude_posts) ){
		foreach($exclude_posts as $transform_to_integer){
			if( $transform_to_integer != 0 && $transform_to_integer != '' ) $exclude_id[] = (int)$transform_to_integer;
		}
	}

	$display_effect = 'no-effect';
	if( isset($options['special-effects']) ){

		if( $options['special-effects'] === 'opacited' ){
			$display_effect = 'animated opacited';
		} elseif( $options['special-effects'] === 'rotate-in' ){
			$display_effect = 'animated rotate-in';
		} elseif( $options['special-effects'] === '3dflip' ){
			$display_effect = 'animated flip';
		} elseif( $options['special-effects'] === 'scaler' ){
			$display_effect = 'animated scaler';
		}
	}

	if ( get_query_var('paged') ) {
		$current = get_query_var('paged');
	} elseif ( get_query_var('page') ) {
		$current = get_query_var('page');
	} else {
		$current = 1;
	}

		// Display elements for grid mode
	if ($options['display-mode'] === 'grid') {

		$vdf_masonry_class = (isset($options['behavior']) && $options['behavior'] == 'masonry') ? ' ts-filters-container ' : '';

		$grid_view_start = '<section class="ts-grid-view ' . $display_effect . $vdf_masonry_class . ' ' . self::get_clear_class($options['elements-per-row']) . ' ">';

		$grid_view_end = '</section>';

		$carousel_wrapper_start = '<div class="carousel-wrapper">';
		$carousel_wrapper_end = '</div>';

		$carousel_container_start = '<div class="carousel-overview"><div class="carousel-container">';
		$carousel_container_end = '</div></div>';

		$carousel_navigation = '<ul class="carousel-nav">
		<li class="carousel-nav-left icon-left">
			<span class="hidden_btn">'.esc_html__('prev','videofly').'</span>
		</li>
		<li class="carousel-nav-right icon-right">
			<span class="hidden_btn">'.esc_html__('next','videofly').'</span>
		</li>
	</ul>';

	if ( $original_query === null ) {

		$order    = self::order_direction($options['order-direction']);

		$args = array(
			'post_type' => $post_types,
			'order' => $order,
			'post__not_in' => $exclude_id,
			'paged' => $current,
			'posts_per_page' => (int)$options['posts-limit'],
			'tax_query' => array('relation' => 'OR')
			);

		if( is_array($post_types) && count($post_types) > 0 && is_array($categories) && count($categories) > 0 ){
			$exclude_taxonomies = array('post_tag', 'post_format');

			foreach($post_types as $type){
				$taxonomies = get_object_taxonomies($type);
				foreach($taxonomies as $taxonomy){
					if( isset($taxonomy) && !in_array($taxonomy, $exclude_taxonomies) ){
						$tax_query = array(
							'taxonomy' => $taxonomy,
							'field'    => 'slug',
							'terms'    => $categories
							);
						array_push($args['tax_query'], $tax_query);
					}
				}
			}

		}

		if( $pagination === 'n' || $pagination === 'load-more' || $pagination == 'infinite' ){
			$args['offset'] = $exclude_first;
		}

		$args = self::order_by($options['order-by'], $args, $featured);

		$query = new WP_Query( $args );

	} else {
		$query = &$original_query;
	}

	$row = array();

	if ( $query->have_posts() ) {
		ob_start();
		ob_clean();
		global $article_options;
		$article_options = $options;
		$article_options['j'] = $query->post_count;
		$article_options['i'] = 1;
		$article_options['k'] = 1;

		while ( $query->have_posts() ) {

			$query->the_post();
			get_template_part('includes/layout-builder/templates/grid-view');
		}
		$elements = ob_get_clean();

		if ( $pagination == 'y' ) {
			ob_start();
			ob_clean();
			global $vdf_list_query;
			$vdf_list_query = $query;
			get_template_part('template-pagination');
			$pagination_content = ob_get_clean();
		}
		wp_reset_postdata();

	} else {
		return $grid_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . esc_html__( 'Nothing Found', 'videofly' ) . '</div>' . $grid_view_end;
	}
	$args['options'] = $options;
	$load_more = (($pagination === 'load-more' || $pagination == 'infinite') && $ajax_load_more === false) ? '<div ' . $display_load_more . ' class="ts-pagination-more' . $infinite_scroll . '" data-loop="1" data-args="' . ts_enc_string(serialize($args)) . '"><i class="icon-restart"></i><span>' .esc_html__('Load More', 'videofly') . '</span>' . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

	if ( $original_query === null ) {
		$next_prev_links = '';
	} else {
		$next_prev_links = self::archive_navigation();
	}

	if (isset($options['behavior']) && $options['behavior'] === 'carousel') {
		return $grid_view_start . $carousel_wrapper_start . $carousel_navigation . $carousel_container_start . $elements . $carousel_container_end . $carousel_wrapper_end . $grid_view_end;
	}else if( isset($options['behavior']) && $options['behavior'] === 'scroll' ) {
		return  $grid_view_start . '<div class="scroll-view"><div class="row">' . $elements .'</div></div>'. $grid_view_end . $next_prev_links . $pagination_content;
	}else if($ajax_load_more === true){
		return  $elements;
	}else{
		return  $grid_view_start . $elements . $grid_view_end . $load_more . $next_prev_links . $pagination_content;
	}

} else if ($options['display-mode'] === 'list') {

	$list_view_start = '<section class="ts-list-view ' . $display_effect . ' ">';
	$list_view_end = '</section>';

	if ( $original_query === null ) {

		$order    = self::order_direction($options['order-direction']);

		$args = array(
			'post_type'      => $post_types,
			'order'          => $order,
			'paged'          => $current,
			'post__not_in'   => $exclude_id,
			'posts_per_page' => (int)$options['posts-limit'],
			'tax_query'      => array('relation' => 'OR')
			);

		if( $pagination === 'n' || $pagination === 'load-more' || $pagination == 'infinite' ){
			$args['offset'] = $exclude_first;
		}

		if( is_array($post_types) && count($post_types) > 0 && is_array($categories) && count($categories) > 0 ){
			$exclude_taxonomies = array('post_tag', 'post_format');
			foreach($post_types as $type){
				$taxonomies = get_object_taxonomies($type);
				foreach($taxonomies as $taxonomy){
					if( isset($taxonomy) && !in_array($taxonomy, $exclude_taxonomies) ){
						$tax_query = array(
							'taxonomy' => $taxonomy,
							'field'    => 'slug',
							'terms'    => $categories
							);
						array_push($args['tax_query'], $tax_query);
					}
				}
			}
		}else{
			$args['category__in'] = array(0);
		}

		$args = self::order_by($options['order-by'], $args, $featured);

		$query = new WP_Query( $args );

	} else {
		$query = &$original_query;
	}

	$articles = array();
	if ( $query->have_posts() ) {
		ob_start();
		ob_clean();
		global $article_options;
		$article_options = $options;
		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part('includes/layout-builder/templates/list-view');
		}
		$elements = ob_get_clean();

		if ( isset($options['pagination']) && $options['pagination'] == 'y' ) {
			ob_start();
			ob_clean();
			global $vdf_list_query;
			$vdf_list_query = $query;
			get_template_part('template-pagination');
			$pagination_content = ob_get_clean();
		}

		wp_reset_postdata();

	} else {
		return $list_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . esc_html__( 'Nothing Found', 'videofly' ) . '</div>' . $list_view_end;
	}

	$args['options'] = $options;
	$load_more = (($pagination === 'load-more' || $pagination == 'infinite') && $ajax_load_more === false) ? '<div ' . $display_load_more . ' class="ts-pagination-more' . $infinite_scroll . '" data-loop="1" data-args="' . ts_enc_string(serialize($args)) . '"><i class="icon-restart"></i><span>' .esc_html__('Load More', 'videofly') . '</span>' . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

	if ( $original_query === null ) {
		$next_prev_links = '';
	} else {
		$next_prev_links = self::archive_navigation();
	}
	if( $ajax_load_more === true ){
		return $elements;
	}else{
		return $list_view_start . $elements . $list_view_end . $load_more . $next_prev_links . $pagination_content;
	}

} else if ($options['display-mode'] === 'thumbnails') {

	$use_gutter = '';
	$author = (isset($options['author'])) ? $options['author'] : '';

	if( isset($options['gutter']) ){
		if( $options['gutter'] === 'y' ){
			$use_gutter = ' no-gutter';
		}
	}

	$vdf_masonry_class = '';
	if ( isset($options['behavior']) && @$options['behavior'] == 'masonry' ) {
		$vdf_masonry_class = ' ts-filters-container ';
	}

	$thumbnails_view_start = '<section class="ts-thumbnail-view ' . $display_effect . $use_gutter . $vdf_masonry_class . ' ' . self::get_clear_class($options['elements-per-row']) . ' ">';
	$thumbnails_view_end = '</section>';

	$carousel_navigation = '<ul class="carousel-nav">
	<li class="carousel-nav-left icon-left">
		<span class="hidden_btn">'.esc_html__('prev','videofly').'</span>
	</li>
	<li class="carousel-nav-right icon-right">
		<span class="hidden_btn">'.esc_html__('next','videofly').'</span>
	</li>
</ul>';

$carousel_wrapper_start = '<div class="carousel-wrapper">';
$carousel_wrapper_end = '</div>';

$carousel_container_start = '<div class="carousel-overview"><div class="carousel-container">';
$carousel_container_end = '</div></div>';

$valid_columns = array(1, 2, 3, 4, 6);

if ( ! in_array($options['elements-per-row'], $valid_columns)) {
	$options['elements-per-row'] = 3;
}

if ( $original_query === null ) {

	$order    = self::order_direction($options['order-direction']);

	$args = array(
		'post_type' => $post_types,
		'order' => $order,
		'paged' => $current,
		'post__not_in' => $exclude_id,
		'posts_per_page' => (int)$options['posts-limit'],
		'tax_query'      => array('relation' => 'OR')
		);

	if( $author !== '' ){
		$args['author'] = $author;
	}

	if( is_array($post_types) && count($post_types) > 0 && is_array($categories) && count($categories) > 0 ){
		$exclude_taxonomies = array('post_tag', 'post_format');

		foreach($post_types as $type){
			$taxonomies = get_object_taxonomies($type);
			foreach($taxonomies as $taxonomy){
				if( isset($taxonomy) && !in_array($taxonomy, $exclude_taxonomies) ){
					$tax_query = array(
						'taxonomy' => $taxonomy,
						'field'    => 'slug',
						'terms'    => $categories
						);
					array_push($args['tax_query'], $tax_query);
				}
			}
		}

	}else{
		$args['category__in'] = array(0);
	}

	if( $pagination === 'n' || $pagination === 'load-more' || $pagination == 'infinite' ){
		$args['offset'] = $exclude_first;
	}

	$args = self::order_by($options['order-by'], $args, $featured);

	$query = new WP_Query( $args );
} else {
	$query = &$original_query;
}

$elements = array();

if ( $query->have_posts() ) {
	ob_start();
	ob_clean();
	global $article_options;
	$article_options = $options;
	$article_options['i'] = 1;
	$article_options['j'] = $query->post_count;
	while ( $query->have_posts() ) {
		$query->the_post();
		get_template_part('includes/layout-builder/templates/thumbs-view');
	}
	$elements = ob_get_clean();

	if ( isset($options['pagination']) && $options['pagination'] == 'y' ) {
		ob_start();
		ob_clean();
		global $vdf_list_query;
		$vdf_list_query = $query;
		get_template_part('template-pagination');
		$pagination_content = ob_get_clean();
	}

	wp_reset_postdata();

} else {
	return $thumbnails_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . esc_html__( 'Nothing Found', 'videofly' ) . '</div>' . $thumbnails_view_end;
}

if ( $original_query === null ) {
	$next_prev_links = '';
	/* Restore original Post Data */
	wp_reset_postdata();
} else {
	$next_prev_links = self::archive_navigation();
}

if( ! isset( $options['behavior'] ) ){
	@$options['behavior'] = 'none';
}

$args['options'] = $options;

$load_more = (($pagination === 'load-more' || $pagination == 'infinite') && $ajax_load_more === false) ? '<div ' . $display_load_more . ' class="ts-pagination-more' . $infinite_scroll . '" data-loop="1" data-args="' . ts_enc_string(serialize($args)) . '"><i class="icon-restart"></i><span>' .esc_html__('Load More', 'videofly') . '</span>' . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

if (@$options['behavior'] === 'carousel') {
	return $thumbnails_view_start . $carousel_wrapper_start . $carousel_navigation . $carousel_container_start . $elements . $carousel_container_end. $carousel_wrapper_end . $thumbnails_view_end;
} else if( @$options['behavior'] === 'scroll' ) {
	return $thumbnails_view_start . '<div class="scroll-view"><div class="row">' . $elements .'</div></div>' . $thumbnails_view_end . $next_prev_links . $pagination_content;
}else if( $ajax_load_more === true ){
	return $elements;
}else{
	return $thumbnails_view_start . $elements . $thumbnails_view_end . $load_more . $next_prev_links . $pagination_content;
}

} else if ($options['display-mode'] === 'big-post') {

	$big_post_view_start = '<section class="ts-big-posts ' . $display_effect . ' ">';
	$big_post_view_end   = '</section>';

	$carousel = (isset($options['carousel']) && ($options['carousel'] == 'y' || $options['carousel'] == 'n')) ? $options['carousel'] : 'n';
	$carousel_wrapper_start = ($carousel == 'y') ? '<div class="carousel-wrapper">' : '';
	$carousel_wrapper_end = ($carousel == 'y') ? '</div>' : '';

	$carousel_container_start = '<div class="carousel-overview"><div class="carousel-container">';
	$carousel_container_end = '</div></div>';

	$carousel_navigation = ($carousel == 'y') ? '<ul class="carousel-nav">
	<li class="carousel-nav-left icon-left">
		<span class="hidden_btn">'.esc_html__('prev','videofly').'</span>
	</li>
	<li class="carousel-nav-right icon-right">
		<span class="hidden_btn">'.esc_html__('next','videofly').'</span>
	</li>
</ul>' : '';

if ( $original_query === null ) {

	$order    = self::order_direction($options['order-direction']);

	$args = array(
		'post_type' => $post_types,
		'order' => $order,
		'post__not_in' => $exclude_id,
		'posts_per_page' => (int)$options['posts-limit'],
		'tax_query'      => array('relation' => 'OR')
		);

	if( $pagination === 'n' || $pagination === 'load-more' || $pagination == 'infinite' ){
		$args['offset'] = $exclude_first;
	}

	if( is_array($post_types) && count($post_types) > 0 && is_array($categories) && count($categories) > 0 ){
		$exclude_taxonomies = array('post_tag', 'post_format');
		foreach($post_types as $type){
			$taxonomies = get_object_taxonomies($type);
			foreach($taxonomies as $taxonomy){
				if( isset($taxonomy) && !in_array($taxonomy, $exclude_taxonomies) ){
					$tax_query = array(
						'taxonomy' => $taxonomy,
						'field'    => 'slug',
						'terms'    => $categories
						);
					array_push($args['tax_query'], $tax_query);
				}
			}
		}
	}else{
		$args['category__in'] = array(0);
	}

	$args = self::order_by($options['order-by'], $args, $featured);

	$query = new WP_Query( $args );

} else {
	$query = &$original_query;
}

$elements = array();

if ( $query->have_posts() ) {
	ob_start();
	ob_clean();
	global $article_options;
	$article_options = $options;
	$article_options['i'] = 1;

	while ( $query->have_posts() ) {
		$query->the_post();
		get_template_part('includes/layout-builder/templates/big-posts');
	}
	$elements = ob_get_clean();

	if ( isset($options['pagination']) && $options['pagination'] == 'y' ) {
		ob_start();
		ob_clean();
		global $vdf_list_query;
		$vdf_list_query = $query;
		get_template_part('template-pagination');
		$pagination_content = ob_get_clean();
	}

	wp_reset_postdata();

} else {
	return $big_post_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . esc_html__( 'Nothing Found', 'videofly' ) . '</div>' . $big_post_view_end;
}

if ( $original_query === null ) {
	$next_prev_links = '';
} else {
	$next_prev_links = self::archive_navigation();
}

$args['options'] = $options;
$load_more = (($pagination === 'load-more' || $pagination == 'infinite') && $ajax_load_more === false) ? '<div ' . $display_load_more . ' class="ts-pagination-more' . $infinite_scroll . '" data-loop="1" data-args="' . ts_enc_string(serialize($args)) . '"><i class="icon-restart"></i><span>' .esc_html__('Load More', 'videofly') . '</span>' . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

if( $ajax_load_more === true ){
	return $elements;
}elseif( $carousel == 'y' ){
	return $big_post_view_start . $carousel_wrapper_start . $carousel_navigation . $carousel_container_start . $elements . $carousel_container_end. $carousel_wrapper_end;
}else{
	return $big_post_view_start . $elements . $big_post_view_end . $load_more . $next_prev_links . $pagination_content;
}

} else if ($options['display-mode'] == 'super-post') {

	$super_post_view_start = '<section class="ts-super-posts ' . $display_effect . ' ">';
	$super_post_view_end = '</section>';

	$valid_columns = array(1, 2, 3);

	if ( ! in_array($options['elements-per-row'], $valid_columns) ) {
		$options['elements-per-row'] = 1;
	}

	$elements = array();

	if ( $original_query === null ) {

		$order = self::order_direction($options['order-direction']);

		$args = array(
			'post_type'      => $post_types,
			'order'          => $order,
			'paged'          => $current,
			'post__not_in'   => $exclude_id,
			'posts_per_page' => (int)$options['posts-limit'],
			'tax_query'      => array('relation' => 'OR')
			);

		if( $pagination === 'n' || $pagination === 'load-more' || $pagination == 'infinite' ){
			$args['offset'] = $exclude_first;
		}

		if( is_array($post_types) && count($post_types) > 0 && is_array($categories) && count($categories) > 0 ){
			$exclude_taxonomies = array('post_tag', 'post_format');
			foreach($post_types as $type){
				$taxonomies = get_object_taxonomies($type);
				foreach($taxonomies as $taxonomy){
					if( isset($taxonomy) && !in_array($taxonomy, $exclude_taxonomies) ){
						$tax_query = array(
							'taxonomy' => $taxonomy,
							'field'    => 'slug',
							'terms'    => $categories
							);
						array_push($args['tax_query'], $tax_query);
					}
				}
			}
		}else{
			$args['category__in'] = array(0);
		}

		$args = self::order_by($options['order-by'], $args, $featured);

		$query = new WP_Query( $args );

	} else {
		$query = &$original_query;
	}

	$elements = array();

	if ( $query->have_posts() ) {
		ob_start();
		ob_clean();
		global $article_options;
		$article_options = $options;
		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part('includes/layout-builder/templates/super-posts');
		}
		$elements = ob_get_clean();

		if ( isset($options['pagination']) && $options['pagination'] == 'y' ) {
			ob_start();
			ob_clean();
			global $vdf_list_query;
			$vdf_list_query = $query;
			get_template_part('template-pagination');
			$pagination_content = ob_get_clean();
		}

		wp_reset_postdata();

	} else {
		return $super_post_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . esc_html__( 'Nothing Found', 'videofly' ) . '</div>' . $super_post_view_end;
	}

	if ( $original_query === null ) {
		$next_prev_links = '';
	} else {
		$next_prev_links = self::archive_navigation();
	}

	$args['options'] = $options;
	$load_more = (($pagination === 'load-more' || $pagination == 'infinite') && $ajax_load_more === false) ? '<div ' . $display_load_more . ' class="ts-pagination-more' . $infinite_scroll . '" data-loop="1" data-args="' . ts_enc_string(serialize($args)) . '"><i class="icon-restart"></i><span>' .esc_html__('Load More', 'videofly') . '</span>' . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

	if( $ajax_load_more === true ){
		return $elements;
	}else{
		return $super_post_view_start . $elements . $super_post_view_end . $load_more . $next_prev_links . $pagination_content;
	}

} else if ($options['display-mode'] === 'timeline') {

	if ( $original_query === null ) {

		$order    = self::order_direction($options['order-direction']);

		$args = array(
			'post_type'      => $post_types,
			'order'          => $order,
			'paged'          => $current,
			'post__not_in'   => $exclude_id,
			'posts_per_page' => (int)$options['posts-limit'],
			'tax_query'      => array('relation' => 'OR')
			);

		if( $pagination === 'n' || $pagination === 'load-more' || $pagination == 'infinite' ){
			$args['offset'] = $exclude_first;
		}

		if( is_array($post_types) && count($post_types) > 0 && is_array($categories) && count($categories) > 0 ){
			$exclude_taxonomies = array('post_tag', 'post_format');
			foreach($post_types as $type){
				$taxonomies = get_object_taxonomies($type);
				foreach($taxonomies as $taxonomy){
					if( isset($taxonomy) && !in_array($taxonomy, $exclude_taxonomies) ){
						$tax_query = array(
							'taxonomy' => $taxonomy,
							'field'    => 'slug',
							'terms'    => $categories
							);
						array_push($args['tax_query'], $tax_query);
					}
				}
			}
		}else{
			$args['category__in'] = array(0);
		}

		$args = self::order_by($options['order-by'], $args, $featured);

		$query = new WP_Query( $args );

	} else {
		$query = &$original_query;
	}

	$articles = array();
	if ( $query->have_posts() ) {
		ob_start();
		ob_clean();
		global $article_options;
		$article_options = $options;
		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part('includes/layout-builder/templates/timeline-view');
		}
		$elements = ob_get_clean();

		if ( $pagination == 'y' ) {
			ob_start();
			ob_clean();
			global $vdf_list_query;
			$vdf_list_query = $query;
			get_template_part('template-pagination');
			$pagination_content = ob_get_clean();
		}

		wp_reset_postdata();

	} else {
		return $timeline_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . esc_html__( 'Nothing Found', 'videofly' ) . '</div>' . $list_view_end;
	}

	if ( $original_query === null ) {
		$next_prev_links = '';
	} else {
		$next_prev_links = self::archive_navigation();
	}

	$args['options'] = $options;
	$load_more = (($pagination === 'load-more' || $pagination == 'infinite') && $ajax_load_more === false) ? '<div ' . $display_load_more . ' class="ts-pagination-more' . $infinite_scroll . '" data-loop="1" data-args="' . ts_enc_string(serialize($args)) . '"><i class="icon-restart"></i><span>' .esc_html__('Load More', 'videofly') . '</span>' . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

	if( $ajax_load_more === true ){
		return $elements;
	}else{
		return '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><section class="ts-timeline-view" data-alignment="left">' . $elements . '</section>' . $load_more . '</div>' . $next_prev_links . $pagination_content;
	}

} else if ($options['display-mode'] === 'mosaic') {

	$effect_scroll = (isset($options['effects-scroll']) && $options['effects-scroll'] !== '' && $options['effects-scroll'] == 'default') ? '' : ' fade-effect';

	$gutter_class = (isset($options['gutter']) && $options['gutter'] !== '' && $options['gutter'] == 'y') ? ' mosaic-with-gutter ' : ' mosaic-no-gutter';

	$scroll = (isset($options['scroll']) && !empty($options['scroll']) && $options['scroll'] == 'y') ? '<div data-scroll="true" class="mosaic-view'. $effect_scroll . $gutter_class . ' mosaic-' . $options['layout'] . '">' : '<div data-scroll="false" class="mosaic-view'. $gutter_class .' mosaic-' . $options['layout'] . '">';
	$img_rows = (isset($options['rows']) && $options['rows'] !== '' && (int)$options['rows'] !== 0) ? (int)$options['rows'] : '3';
	$layout_mosaic = (isset($options['layout']) && ($options['layout'] == 'rectangles' || $options['layout'] == 'square')) ? $options['layout'] : 'square';

	if ( $original_query === null ) {

		$order    = self::order_direction($options['order-direction']);

		if ( get_query_var('paged') ) {
			$current = get_query_var('paged');
		} elseif ( get_query_var('page') ) {
			$current = get_query_var('page');
		} else {
			$current = 1;
		}

		$args = array(
			'post_type'      => $post_types,
			'posts_per_page' => (int)$options['posts-limit'],
			'order'          => $order,
			'post__not_in'   => $exclude_id,
			'paged'          => $current,
			'tax_query'      => array('relation' => 'OR')

			);

		if( isset($options['scroll']) && $options['scroll'] == 'y' || ($options['scroll'] == 'n' && ($pagination === 'n' || $pagination === 'load-more' || $pagination == 'infinite')) ){
			$args['offset'] = $exclude_first;
		}

		if( is_array($post_types) && count($post_types) > 0 && is_array($categories) && count($categories) > 0 ){
			$exclude_taxonomies = array('post_tag', 'post_format');
			foreach($post_types as $type){
				$taxonomies = get_object_taxonomies($type);
				foreach($taxonomies as $taxonomy){
					if( isset($taxonomy) && !in_array($taxonomy, $exclude_taxonomies) ){
						$tax_query = array(
							'taxonomy' => $taxonomy,
							'field'    => 'slug',
							'terms'    => $categories
							);
						array_push($args['tax_query'], $tax_query);
					}
				}
			}
		}else{
			$args['category__in'] = array(0);
		}

		$args = self::order_by($options['order-by'], $args, $featured);

		$query = new WP_Query( $args );

	} else {
		$query = &$original_query;
	}

	$articles = array();
	if ( $query->have_posts() ) {
		ob_start();
		ob_clean();
		global $article_options;
		$article_options = $options;

		$article_options['i'] = (isset($options['loop'])) ? (int)$options['loop'] + 1 : 1;
		$article_options['j'] = $query->post_count;
		$article_options['k'] = 1;

		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part('includes/layout-builder/templates/mosaic-view');
			$article_options['k']++;
			$article_options['i']++;
			if( $article_options['k'] === 7 && $layout_mosaic == 'rectangles' && $img_rows == 2  ){
				$article_options['k'] = 1;
			}
			if( $article_options['k'] === 10 && $layout_mosaic == 'rectangles' && $img_rows == 3  ){
				$article_options['k'] = 1;
			}
			if( $article_options['k'] === 6 && $layout_mosaic == 'square' ){
				$article_options['k'] = 1;
			}
		}

		$elements = ob_get_clean();

		$pagination_content = '';
		if ( isset($options['scroll']) && $options['scroll'] == 'n' && $pagination == 'y' ) {
			ob_start();
			ob_clean();
			global $vdf_list_query;
			$vdf_list_query = $query;
			get_template_part('template-pagination');
			$pagination_content = ob_get_clean();
		}

		wp_reset_postdata();

	} else {
		return '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . esc_html__( 'Nothing Found', 'videofly' ) . '</div>';
	}

	$args['options'] = $options;
	$load_more = (isset($options['scroll']) && $options['scroll'] == 'n' && ($pagination === 'load-more' || $pagination == 'infinite') && $ajax_load_more === false) ? '<div ' . $display_load_more . ' class="ts-pagination-more' . $infinite_scroll . '" data-loop="1" data-args="' . ts_enc_string(serialize($args)) . '"><i class="icon-restart"></i><span>' .esc_html__('Load More', 'videofly') . '</span>' . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

	if ( $original_query === null ) {
		$next_prev_links = '';
	} else {
		$next_prev_links = self::archive_navigation();
	}

	if( $ajax_load_more ){
		return $elements;
	}else{
		return $scroll . $elements . $next_prev_links . '</div>' . $load_more . $pagination_content;
	}
}

}

public static function timeline_element($options = array())
{
	if( isset($options['type']) && $options['type'] === 'timeline' ){
		ob_start();
		ob_clean();
		global $article_options;
		$article_options = $options;
		get_template_part('includes/layout-builder/templates/timeline-features');
		$elements = ob_get_clean();
		return $elements;
	}
}

public static function ribbon_element($options = array())
{
	if( isset($options['type']) && $options['type'] === 'ribbon' ){
		ob_start();
		ob_clean();
		global $article_options;
		$article_options = $options;
		get_template_part('includes/layout-builder/templates/ribbon');
		$elements = ob_get_clean();
		return $elements;
	}
}

public static function video_carousel_element($options = array()) {

	$video_carousel = ( isset($options['video-carousel']) && !empty($options['video-carousel']) && $options['video-carousel'] !== '[]' ) ? json_decode(stripslashes($options['video-carousel'])) : NULL;
	$source = (isset($options['source']) && ($options['source'] == 'latest-posts' || $options['source'] == 'latest-galleries' || $options['source'] == 'latest-videos' || $options['source'] == 'latest-featured-posts' || $options['source'] == 'latest-featured-galleries' || $options['source'] == 'latest-featured-videos' || $options['source'] == 'custom-slides')) ? $options['source'] : 'custom-slides';

	$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 7);

	$vdf_video_carousel = '<div id="'. $randomString .'" class="ts-video-carousel"><div class="ts-video-carousel-wrap"><ul class="slides">';


	if( $source == 'custom-slides' ){
		if( isset($video_carousel) && is_array($video_carousel) && !empty($video_carousel) ){

			foreach($video_carousel as $carousel){
				$title = (isset($carousel->title)) ? esc_attr($carousel->title) : '';
				$description = (isset($carousel->text)) ? apply_filters('ts_the_content', $carousel->text) : '';
				$url = (isset($carousel->url)) ? esc_url($carousel->url) : '';
				$embed = (isset($carousel->embed)) ? esc_url($carousel->embed) : '';

				$vdf_video_carousel .= 	'<li>
				<div class="thumb"><div class="carousel-video-wrapper"><div class="embedded_videos">'. apply_filters('ts_the_content', $embed) .'</div></div></div>
				<div class="carousel-content">
					<h3 class="carousel-item-title"><a href="'. $url. '">'. $title . '</a></h3>
				</div>
			</li>';
		}

	}else{
		return;
	}
}else{

	$post_type = ($source == 'latest-posts' ? 'post' :
		($source == 'latest-videos' ? 'video' :
			($source == 'latest-galleries' ? 'ts-gallery' :
				($source == 'latest-featured-posts' ? 'post' :
					($source == 'latest-featured-videos' ? 'video' :
						($source == 'latest-featured-galleries' ? 'ts-gallery' : 'post'))))));

	$args = array(
		'post_type' => $post_type
		);

	if( $source == 'latest-featured-posts' || $source == 'latest-featured-videos' || $source == 'latest-featured-galleries' ){
		$args['meta_query'] = array(
			array(
				'key' => 'featured',
				'value' => 'yes',
				'compare' => '=',
				),
			);
	}

	$query = new WP_Query($args);

	if( $query->have_posts() ){
		while($query->have_posts()){ $query->the_post();
			if( $post_type == 'video'){
				$videos = get_post_meta(get_the_ID(), 'video', TRUE);
				if( isset($videos['extern_url']) && !empty($videos['extern_url']) ){
					$embed_image = apply_filters('ts_the_content', $videos['extern_url']);
				}elseif( isset($videos['your_url']) && !empty($videos['your_url']) ){
					$src = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
					$poster_url = vdf_resize('single', $src);

					$atts = array(
						'src'      => esc_url($videos['your_url']),
						'poster'   => $poster_url,
						'loop'     => '',
						'autoplay' => '',
						'preload'  => 'metadata',
						'height'   => 580,
						'width'    => 1340,
						);
					$embed_image = wp_video_shortcode($atts);
				}else{
					if ( has_post_thumbnail(get_the_ID()) ) $embed_image = aq_resize(wp_get_attachment_url( get_post_thumbnail_id(get_the_ID())), 650, 375, true);
					else $embed_image = get_template_directory_uri() . '/images/noimage.jpg';
				}

			}else{
				if ( has_post_thumbnail(get_the_ID()) ) $embed_image = aq_resize(wp_get_attachment_url( get_post_thumbnail_id(get_the_ID())), 650, 375, true);
				else $embed_image = get_template_directory_uri() . '/images/noimage.jpg';
			}

			$description = '';
			if (!empty($post->post_excerpt)) {
				if (strlen(strip_tags(strip_shortcodes(get_the_excerpt()))) > intval(intval($ln))) {
					$description = mb_substr(strip_tags(strip_shortcodes(get_the_excerpt())), 0, 250) . '...';
				} else {
					$description = strip_tags(strip_shortcodes(get_the_excerpt()));
				}
			} else {
				if (strlen(strip_tags(strip_shortcodes(get_the_content()))) > 250) {
					$description = mb_substr(strip_tags(strip_shortcodes(get_the_content())), 0, 250) . '...';
				} else {
					$description = strip_tags(strip_shortcodes(get_the_content()));
				}
			}

			$article_date = get_the_date();

			$vdf_video_carousel .= 	'<li>
			<div class="thumb">
				<div class="carousel-video-wrapper">
					<div class="embedded_videos">
						<img src="'. $embed_image .'">
					</div>
				</div>
			</div>
			<div class="carousel-content">
				<h3 class="carousel-item-title"><a href="'. get_permalink() . '">'. get_the_title() . '</a></h3>
				<ul class="entry-meta">
					<li class="entry-meta-author">
						<a class="author-image avatar" href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '"> ' . get_avatar(get_the_author_meta( 'ID' ), 120).'</a>
						<a class="author-name" href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '">' . get_the_author() . '</a>
					</li>
					<li class="ts-date">' . $article_date . '</li>
					'. touchsize_likes(get_the_ID(), '<li class="entry-meta-likes">', '</li>', false) .'
				</ul>
			</li>';

		}
		wp_reset_postdata();
	}else{
		return;
	}
}

$vdf_video_carousel .= 	'</ul></div></div>
<script>
	jQuery(document).ready(function(){
		jQuery("#'. $randomString. '").ts_video_carousel();
	});
</script>';

return $vdf_video_carousel;
}

public static function count_down_element($options = array())
{
	if( isset($options['type']) && $options['type'] === 'count-down' ){
		ob_start();
		ob_clean();
		global $article_options;
		$article_options = $options;
		get_template_part('includes/layout-builder/templates/count-down');
		$elements = ob_get_clean();
		return $elements;
	}
}

public static function powerlink_element($options = array())
{
	if( isset($options['type']) && $options['type'] === 'powerlink' ){
		$src = (isset($options['image']) && !empty($options['image'])) ? esc_url($options['image']) : NULL;

		$image_url = vdf_resize('bigpost', $src);
		$title = (isset($options['title'])) ? esc_attr($options['title']) : '';

		$button_text = (isset($options['button-text'])) ? esc_attr($options['button-text']) : '';
		$button_url = (isset($options['button-url']) && !empty($options['button-url'])) ? esc_url($options['button-url']) : NULL;

		$element = '<div class="col-md-12 col-lg-12 ts-powerlink">
		<article>
			<header>
				<div class="image-holder">
					<img src="'.$image_url.'" alt="'.$title.'">
				</div>
				<div class="content">
					<div class="title"><a href="'.$button_url.'"><h3>'.$title.'</h3></a></div>
					<a class="button" href="'.$button_url.'">'.$button_text.'</a>
				</div>
			</header>
		</article>
	</div>';

	return $element;

}
}

public static function calendar_element($options = array())
{
	if( isset($options['type']) && $options['type'] === 'calendar' ){
		$size = (isset($options['size']) && $options['size'] == 'small') ? 'ts-small-calendar' : 'ts-big-calendar';
		$nonce = wp_create_nonce( 'security' );

		return '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . vdf_draw_calendar_callback(date('m'), date('Y'), $size, $nonce) . '</div>';
	}
}

public static function events_element($options = array(), $query = NULL)
{
	$exclude_id = array();
	$exclude_posts  = ( isset($options['id-exclude']) && $options['id-exclude'] != '' ) ? explode(',',@$options['id-exclude']) : NULL;
	$pagination     = (isset($options['pagination']) && ($options['pagination'] === 'n' || $options['pagination'] === 'y' || $options['pagination'] ==='load-more')) ? $options['pagination'] : 'n';
	$exclude_first  = ( isset($options['exclude-first']) ) ? (int)$options['exclude-first'] : '';
	$ajax_load_more = (isset($options['ajax-load-more']) && $options['ajax-load-more'] === true) ? true : false;
	$categories    = (isset($options['category']) && !empty($options['category']) && is_string($options['category'])) ? explode(',', $options['category']) : '';

	if( isset($exclude_posts) && is_array($exclude_posts) ){
		foreach($exclude_posts as $transform_to_integer){
			if( $transform_to_integer != 0 && $transform_to_integer != '' ) $exclude_id[] = (int)$transform_to_integer;
		}
	}

	$display_effect = 'no-effect';
	if( isset( $options['special-effects'] ) ){

		if( $options['special-effects'] === 'opacited' ){
			$display_effect = 'animated opacited';
		} elseif( $options['special-effects'] === 'rotate-in' ){
			$display_effect = 'animated rotate-in';
		} elseif( $options['special-effects'] === '3dflip' ){
			$display_effect = 'animated flip';
		} elseif( $options['special-effects'] === 'scaler' ){
			$display_effect = 'animated scaler';
		}
	}

	$order = self::order_direction($options['order-direction']);

	if ( get_query_var('paged') ) {
		$current = get_query_var('paged');
	} elseif ( get_query_var('page') ) {
		$current = get_query_var('page');
	} else {
		$current = 1;
	}

	$args = array(
		'post_type' => 'event',
		'order' => $order,
		'post__not_in' => $exclude_id,
		'paged' => $current,
		'posts_per_page' => (int)$options['posts-limit']
		);

	if ( is_array($categories) && count($categories) > 0 ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'event_categories',
				'field' => 'slug',
				'terms' => $categories
				)
			);
	} else {
		$args['category__in'] = array(0);
	}

	if( $pagination === 'load-more' || $pagination === 'n' ){
		$args['offset'] = $exclude_first;
	}

	$args = self::order_by($options['order-by'], $args);

	if( !isset($query) ){
		$query = new WP_Query( $args );
	}

	if( $query->have_posts() ){
		ob_start();
		ob_clean();
		while ( $query->have_posts() ) { $query->the_post();

			global $article_options;
			$article_options = $options;
			get_template_part('includes/layout-builder/templates/events');
		}
		$elements = ob_get_clean();

		$pagination_content = '';

		if ( isset($options['pagination']) && $options['pagination'] == 'y' ) {
			ob_start();
			ob_clean();
			global $vdf_list_query;
			$vdf_list_query = $query;
			get_template_part('template-pagination');
			$pagination_content = ob_get_clean();
		}

		wp_reset_postdata();
		$args['options'] = $options;
		$load_more = ($pagination === 'load-more') ? '<div class="ts-pagination-more" data-loop="1" data-args="' . ts_enc_string(serialize($args)) . '"><i class="icon-restart"></i><span>' .esc_html__('Load More', 'videofly') . '</span>' . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';
		if( $ajax_load_more == true ){
			return $elements;
		}else{
			return '<section class="ts-events-list ' . $display_effect . '"><div class="col-md-12 col-lg-12">' . $elements . '</div></section>' . $load_more . $pagination_content;
		}
	}
}

public static function alert_element($options = array())
{
	$icon = (isset($options['icon'])) ? esc_attr($options['icon']) : 'icon-noicon';
	$title = (isset($options['title'])) ? esc_attr($options['title']) : '';
	$text = (isset($options['text'])) ? apply_filters('ts_the_content', $options['text']) : '';
	$background_color = (isset($options['background-color'])) ? esc_attr($options['background-color']) : '';
	$text_color = (isset($options['text-color'])) ? esc_attr($options['text-color']) : '';
	$div_large_start = (!isset($options['shortcode-div-large'])) ? '<div class="col-md-12 col-sm-12">' : '';
	$div_large_end = (!isset($options['shortcode-div-large'])) ? '</div>' : '';

	$element = 	$div_large_start .
	'<div class="ts-alert" style="color: '.$text_color.'; background-color: '.$background_color.';">
	<span class="alert-icon"><i class="'.$icon.'"></i></span>
	<div class="right-side">
		<span class="alert-title"><h3 class="title">'.$title.'</h3></span>
		<span class="alert-text">'.$text.'</span>
	</div>
</div>' .
$div_large_end;

return $element;

}

public static function skills_element($options = array())
{

	$skills = (isset($options['skills']) && !empty($options['skills']) && $options['skills'] !== '[]') ? json_decode(stripslashes($options['skills'])) : NULL;
	$display_mode = (isset($options['display-mode']) && ($options['display-mode'] == 'horizontal' || $options['display-mode'] == 'vertical')) ? $options['display-mode'] : 'horizontal';

	if( isset($skills) ){

		$element = '<div class="col-md-12 col-lg-12">
		<ul class="ts-'.$display_mode.'-skills countTo">';
			foreach($skills as $skill){
				$color = (isset($skill->color)) ? $skill->color : '';
				$title = (isset($skill->title)) ? str_replace('--quote--', '"', $skill->title) : '';
				$percentage = (isset($skill->percentage) && (int)$skill->percentage !== 0) ? $skill->percentage : '';

				$element .= '<li class="countTo-item">
				<span class="skill-title" style="color: '.$color.'">'.$title.'</span>
				<span class="skill-level" data-percent="'.$percentage.'" style="background-color: '.$color.'">
					<em class="skill-percentage">'.$percentage.'%</em>
				</span>
				<span class="skill-bar"></span>
			</li>';
		}
		$element .=		'</ul>
	</div>';

	return $element;
}
}

public static function accordion_element($options = array(), $query = NULL)
{
	$nr_of_posts = (isset($options['nr-of-posts']) && (int)$options['nr-of-posts'] !== 0) ? (int)$options['nr-of-posts'] : '';
	$posts_limit = (isset($options['posts-limit']) && (int)$options['posts-limit']) ? (int)$options['posts-limit'] : '';
	$categories = (isset($options['category']) && !empty($options['category'])) ? explode(',', $options['category']) : '';
	$direction = (isset($options['order-direction']) && ($options['order-direction'] == 'ASC' || $options['order-direction'] == 'DESC')) ? $options['order-direction'] : 'DESC';

	$post_type = (isset($options['posts-type']) && !empty($options['posts-type'])) ? $options['posts-type'] : 'post';
	$featured = (isset($options['featured']) && ($options['featured'] == 'y' || $options['featured'] == 'n')) ? $options['featured'] : 'n';

	$args = array(
		'post_type' => $post_type,
		'order' => $direction,
		'posts_per_page' => $nr_of_posts
		);

	$args['tax_query'] = array('relation' => 'OR');

	if( is_array($categories) && count($categories) > 0 ){
		$exclude_taxonomies = array('post_tag', 'post_format');

		$taxonomies = get_object_taxonomies($post_type);
		foreach($taxonomies as $taxonomy){
			if( !in_array($taxonomy, $exclude_taxonomies) ){
				$tax_query = array(
					'taxonomy' => $taxonomy,
					'field'    => 'slug',
					'terms'    => $categories
					);
				array_push($args['tax_query'], $tax_query);
			}
		}

	}

	$args = self::order_by($options['order-by'], $args, $featured);

	if( !isset($query) ){
		$query = new WP_Query($args);
	}

	if ( $query->have_posts() ) {
		ob_start();
		ob_clean();
		global $article_options;
		$article_options = $options;
		$article_options['i'] = 1;
		$random_id = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);
		$article_options['accordion_id'] = $random_id;
		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part('includes/layout-builder/templates/accordion');
			$article_options['i']++;
		}
		$elements = ob_get_clean();

		wp_reset_postdata();


		return '<section class="ts-article-accordion col-sm-12 col-md-12">
		<div class="panel-group" id="'.$random_id.'" role="tablist" aria-multiselectable="true">
			' . $elements . '
		</div>
	</section>';

} else {
	return esc_html__('No Results', 'videofly');
}
}

public static function chart_element($options = array(), $query = NULL)
{
	$mode = (isset($options['mode']) && ($options['mode'] == 'pie' || $options['mode'] == 'line')) ? $options['mode'] : 'line';

	if( $mode == 'pie' && $options['chart_pie'] !== '[]' && is_array(json_decode($options['chart_pie'], true)) ){

		$segmentShowStroke = (isset($options['segmentShowStroke']) && ($options['segmentShowStroke'] == 'true' || $options['segmentShowStroke'] == 'false')) ? $options['segmentShowStroke'] : 'true';
		$segmentStrokeColor = (isset($options['segmentStrokeColor']) && !empty($options['segmentStrokeColor'])) ? esc_attr($options['segmentStrokeColor']) : '#777';
		$segmentStrokeWidth = (isset($options['segmentStrokeWidth']) && (int)$options['segmentStrokeWidth'] !== 0) ? $options['segmentStrokeWidth'] : '2';
		$percentageInnerCutout = (isset($options['percentageInnerCutout'])) ? (int)$options['percentageInnerCutout'] : '50';
		$animationSteps = (isset($options['animationSteps']) && (int)$options['animationSteps'] !== 0) ? $options['animationSteps'] : '100';
		$animateRotate = (isset($options['animateRotate']) && ($options['animateRotate'] == 'true' || $options['animateRotate'] == 'false')) ? $options['animateRotate'] : 'true';
		$animateScale = (isset($options['animateScale']) && ($options['animateScale'] == 'true' || $options['animateScale'] == 'false')) ? $options['animateScale'] : 'false';

		$option_pie = '{
			segmentShowStroke : ' . $segmentShowStroke . ',
			segmentStrokeColor : "' . $segmentStrokeColor . '",
			segmentStrokeWidth : ' . $segmentStrokeWidth . ',
			percentageInnerCutout : ' . $percentageInnerCutout . ',
			animationSteps : ' . $animationSteps . ',
			animationEasing : "easeOutBounce",
			animateRotate : ' . $animateRotate . ',
			animateScale : ' . $animateScale . ',
			animationEasing : "easeOutBounce"
		}';

		$array_sections = json_decode($options['chart_pie'], true);

		$chart_pie = '[';
		$i = 1;

		foreach($array_sections as $value){

			if( $i == count($array_sections) ) $comma = '';
			else $comma = ',';

			$chart_pie .= '{
				value: ' . (int)$value['value'] . ',
				color: "' . esc_attr($value['color']) . '",
				highlight: "' . esc_attr($value['highlight']) . '",
				label: "' . esc_attr($value['title']) . '",
			}' . $comma;

			$i++;
		}

		$chart_pie .= ']';
		$rand = rand(1, 10000);
		$rand_id = 'ts-' . $rand;

		return 	'<div class="col-lg-12 col-md-12">
		<canvas id="' . $rand_id . '" width="600" height="400"></canvas>
	</div>
	<script>
		jQuery(document).ready(function(){

			var ctx = document.getElementById("' . $rand_id . '").getContext("2d");
			var startChart'. $rand .' = "y";

			if( jQuery("#'. $rand_id .'").isOnScreen() && startChart'. $rand .' == "y" ){
				new Chart(ctx).Pie(' . $chart_pie . ', ' . $option_pie . ');
				startChart'. $rand .' = "n";
			}

			jQuery(window).on("scroll",function(){
				if( jQuery("#'. $rand_id .'").isOnScreen() && startChart'. $rand .' == "y" ){
					new Chart(ctx).Pie(' . $chart_pie . ', ' . $option_pie . ');
					startChart'. $rand .' = "n";
				}
			});

});
</script>';

}elseif( $mode == 'line' && $options['chart_line'] !== '[]' && is_array(json_decode($options['chart_line'], true)) ){
	$array_labels = (isset($options['label']) && !empty($options['label'])) ? explode(',', $options['label']) : '';
	$scaleShowGridLines = (isset($options['scaleShowGridLines']) && ($options['scaleShowGridLines'] == 'true' || $options['scaleShowGridLines'] == 'false')) ? $options['scaleShowGridLines'] : 'true';
	$scaleGridLineColor = (isset($options['scaleGridLineColor']) && !empty($options['scaleGridLineColor']) ) ? esc_attr($options['scaleGridLineColor']) : 'rgba(0,0,0,.05)';
	$scaleGridLineWidth = (isset($options['scaleGridLineWidth']) && (int)$options['scaleGridLineWidth'] !== 0) ? $options['scaleGridLineWidth'] : 1;
	$scaleShowHorizontalLines = (isset($options['scaleShowHorizontalLines']) && ($options['scaleShowHorizontalLines'] == 'true' || $options['scaleShowHorizontalLines'] == 'false')) ? $options['scaleShowHorizontalLines'] : 'true';
	$scaleShowVerticalLines = (isset($options['scaleShowVerticalLines']) && ($options['scaleShowVerticalLines'] == 'true' || $options['scaleShowVerticalLines'] == 'false')) ? $options['scaleShowVerticalLines'] : 'true';
	$bezierCurve = (isset($options['bezierCurve']) && ($options['bezierCurve'] == 'true' || $options['bezierCurve'] == 'false')) ? $options['bezierCurve'] : 'true';
	$bezierCurveTension = (isset($options['bezierCurveTension']) && !empty($options['bezierCurveTension']) ) ? esc_attr($options['bezierCurveTension']) : '0.4';
	$pointDot = (isset($options['pointDot']) && ($options['pointDot'] == 'true' || $options['pointDot'] == 'false')) ? $options['pointDot'] : 'true';
	$pointDotRadius = (isset($options['pointDotRadius']) && (int)$options['pointDotRadius'] !== 0) ? $options['pointDotRadius'] : 4;
	$pointDotStrokeWidth = (isset($options['pointDotStrokeWidth']) && (int)$options['pointDotStrokeWidth'] !== 0) ? $options['pointDotStrokeWidth'] : 1;
	$pointHitDetectionRadius = (isset($options['pointHitDetectionRadius']) && (int)$options['pointHitDetectionRadius'] !== 0) ? $options['pointHitDetectionRadius'] : 20;
	$datasetStroke = (isset($options['datasetStroke']) && ($options['datasetStroke'] == 'true' || $options['datasetStroke'] == 'false')) ? $options['datasetStroke'] : 'true';
	$datasetStrokeWidth = (isset($options['datasetStrokeWidth']) && (int)$options['datasetStrokeWidth'] !== 0) ? $options['datasetStrokeWidth'] : 2;
	$datasetFill = (isset($options['datasetFill']) && ($options['datasetFill'] == 'true' || $options['datasetFill'] == 'false')) ? $options['datasetFill'] : 'true';


	$option_line = '{
		scaleShowGridLines : ' . $scaleShowGridLines . ',
		scaleGridLineColor : "' . $scaleGridLineColor . '",
		scaleGridLineWidth : ' . $scaleGridLineWidth . ',
		scaleShowHorizontalLines : ' . $scaleShowHorizontalLines . ',
		scaleShowVerticalLines : ' . $scaleShowVerticalLines . ',
		bezierCurve : ' . $bezierCurve . ',
		bezierCurveTension : ' . $bezierCurveTension . ',
		pointDot : ' . $pointDot . ',
		pointDotRadius : ' . $pointDotRadius . ',
		pointDotStrokeWidth : ' . $pointDotStrokeWidth . ',
		pointHitDetectionRadius : ' . $pointHitDetectionRadius . ',
		datasetStroke : ' . $datasetStroke . ',
		datasetStrokeWidth : ' . $datasetStrokeWidth . ',
		datasetFill : ' . $datasetFill . '
	}';
	$labels = '[';

	if( is_array($array_labels) && !empty($array_labels) ){
		$i = 1;
		foreach($array_labels as $label){
			if( $i == count($array_labels) ) $labels .= '"' . esc_attr(trim($label)) . '"';
			else $labels .= '"' . esc_attr(trim($label)) . '",';
			$i++;
		}
		$labels .= ']';
	}

	$array_sections = json_decode($options['chart_line'], true);

	if( is_array($array_sections) && count($array_sections) > 0 ){
		$datasets = '[';
		$i = 1;

		foreach($array_sections as $value){

			if( $i == count($array_sections) ) $comma = '';
			else $comma = ',';

			$datasets .= '{
				label: "' . esc_attr($value['title']) . '",
				fillColor: "' . esc_attr($value['fillColor']) . '",
				strokeColor: "' . esc_attr($value['strokeColor']) . '",
				pointColor: "' . esc_attr($value['pointColor']) . '",
				pointStrokeColor: "' . esc_attr($value['pointStrokeColor']) . '",
				pointHighlightFill: "' . esc_attr($value['pointHighlightFill']) . '",
				pointHighlightStroke: "' . esc_attr($value['pointHighlightStroke']) . '",
				data: [' . esc_attr($value['data']) . ']
			}' . $comma;
		}

		$datasets .= ']';
	}

	if( $labels !== '[' && isset($datasets) ){
		$chart_line = '{
			labels: ' . $labels . ',
			datasets: ' . $datasets . '
		}';

		$rand = rand(1, 10000);
		$rand_id = 'ts-' . rand(1, 10000);

		return 	'<div class="col-lg-12 col-md-12">
		<canvas id="' . $rand_id . '" width="600" height="400"></canvas>
	</div>
	<script>
		jQuery(document).ready(function(){
			var ctx = document.getElementById("' . $rand_id . '").getContext("2d");
			var startChart'. $rand .' = "y";

			if( jQuery("#'. $rand_id .'").isOnScreen() && startChart'. $rand .' == "y" ){
				new Chart(ctx).Line(' . $chart_line . ', ' . $option_line . ');
				startChart'. $rand .' = "n";
			}

			jQuery(window).on("scroll",function(){
				if( jQuery("#'. $rand_id .'").isOnScreen() && startChart'. $rand .' == "y" ){
					new Chart(ctx).Line(' . $chart_line . ', ' . $option_line . ');
					startChart'. $rand .' = "n";
				}
			});
});
</script>';
}
}
}

public static function list_galleries_element($options = array(), &$original_query = null)
{
	$exclude_posts = ( isset($options['id-exclude']) && $options['id-exclude'] != '' ) ? explode(',',@$options['id-exclude']) : NULL;
	$exclude_first = ( isset($options['exclude-first']) ) ? (int)$options['exclude-first'] : '';
	$exclude_id    = array();
	$featured      = (isset($options['featured']) && $options['featured'] !== '' && ($options['featured'] == 'y' || $options['featured'] == 'n')) ? $options['featured'] : 'n';
	$scroll        = (isset($options['behavior']) && $options['behavior'] === 'scroll') ? ' scroll-view' : '';
	$layout_mosaic = (isset($options['layout']) && ($options['layout'] == 'rectangles' || $options['layout'] == 'square')) ? $options['layout'] : 'square';
	$img_rows      = (isset($options['rows']) && $options['rows'] !== '' && (int)$options['rows'] !== 0) ? (int)$options['rows'] : '3';
	$categories    = (isset($options['category']) && !empty($options['category']) && is_string($options['category'])) ? explode(',', $options['category']) : '';
	$pagination    = (isset($options['pagination']) && ($options['pagination'] === 'n' || $options['pagination'] === 'y' || $options['pagination'] ==='load-more' || $options['pagination'] == 'infinite')) ? $options['pagination'] : 'n';
	$ajax_load_more = (isset($options['ajax-load-more']) && $options['ajax-load-more'] === true) ? true : false;
	$display_load_more = ($pagination == 'infinite') ? 'style="display: none;"' : '';
	$infinite_scroll = ($pagination == 'infinite') ? ' ts-infinite-scroll' : '';

	if( isset($exclude_posts) ){
		foreach($exclude_posts as $transform_to_integer){
			if( $transform_to_integer != 0 && $transform_to_integer != '' ) $exclude_id[] = (int)$transform_to_integer;
		}
	}

	$pagination_content = '';

	$display_effect = 'no-effect';
	if( isset($options['special-effects']) ){

		if( $options['special-effects'] === 'opacited' ){
			$display_effect = 'animated opacited';
		} elseif( $options['special-effects'] === 'rotate-in' ){
			$display_effect = 'animated rotate-in';
		} elseif( $options['special-effects'] === '3dflip' ){
			$display_effect = 'animated flip';
		} elseif( $options['special-effects'] === 'scaler' ){
			$display_effect = 'animated scaler';
		}
	}

	if (isset($options['taxonomy'])) {
		$taxonomy = $options['taxonomy'];
	} else {
		$taxonomy = 'category';
	}

		// Display elements for grid mode
	if ($options['display-mode'] === 'grid') {

		$vdf_masonry_class = '';
		if ( @$options['behavior'] == 'masonry' ) {
			$vdf_masonry_class = ' ts-filters-container ';
		}

		$grid_view_start = '<section class="ts-grid-view ' . $display_effect . $vdf_masonry_class . $scroll . ' ' . self::get_clear_class($options['elements-per-row']) . ' ">';
		$grid_view_end = '</section>';

		$carousel_wrapper_start = '<div class="carousel-wrapper">';
		$carousel_wrapper_end = '</div>';

		$carousel_container_start = '<div class="carousel-overview"><div class="carousel-container">';
		$carousel_container_end = '</div></div>';

		$carousel_navigation = '<ul class="carousel-nav">
		<li class="carousel-nav-left icon-left">
			<span class="hidden_btn">'.esc_html__('prev','videofly').'</span>
		</li>
		<li class="carousel-nav-right icon-right">
			<span class="hidden_btn">'.esc_html__('next','videofly').'</span>
		</li>
	</ul>';

	$tab_category_html = '';

	if ( $original_query === null ) {

		$order    = self::order_direction($options['order-direction']);

		if ( get_query_var('paged') ) {
			$current = get_query_var('paged');
		} elseif ( get_query_var('page') ) {
			$current = get_query_var('page');
		} else {
			$current = 1;
		}

		$args = array(
			'post_type' => 'ts-gallery',
			'order' => $order,
			'post__not_in' => $exclude_id,
			'paged' => $current,
			'posts_per_page' => (int)$options['posts-limit']
			);

		if( $pagination === 'load-more' || $pagination === 'n' || $pagination == 'infinite' ){
			$args['offset'] = $exclude_first;
		}

		$select_by_category = (isset($options['behavior']) && $options['behavior'] == 'tabbed') ? 'tabbed' : '';

		if ( is_array($categories) && count($categories) > 0 ) {

			if( $select_by_category == 'tabbed' ){
				$tab_category_html = '<div class="col-lg-12">
				<ul class="ts-select-by-category">';
					$category_i = 1;
					$tab_div_category = '';

					foreach($categories as $key=>$slug_category){
						$active_item = $key == 0 ? ' class="active"' : '';
						$category = get_term_by('slug', $slug_category, 'gallery_categories');
						if( is_object($category) ){
							$tab_category_html .= '<li' . $active_item . ' data-category-li="' . $category->term_id . '"><a href="#">' . $category->name . '</a></li>';
							if( $category_i == count($categories) ) $tab_category_html .= '</ul>';
							$tab_div_category .= '<div data-category-div="' . $category->term_id . '"><div class="row ts-cat-row"></div></div>';
						}
						$category_i++;
					}
					$tab_category_html .= $tab_div_category . '</div>';
				}

				$args['tax_query'] = array(
					array(
						'taxonomy' => 'gallery_categories',
						'field'    => 'slug',
						'terms'    => $categories
						)
					);
			} else {
				$args['category__in'] = array(0);
			}

			$args = self::order_by($options['order-by'], $args, $featured);

			$query = new WP_Query( $args );

		} else {
			$query = &$original_query;
		}

		$row = array();

		if ( $query->have_posts() ) {
			ob_start();
			ob_clean();
			global $article_options;
			$article_options = $options;
			$article_options['j'] = $query->post_count;
			$article_options['i'] = 1;
			$article_options['k'] = 1;

			while ( $query->have_posts() ) {

				$query->the_post();
				get_template_part('includes/layout-builder/templates/grid-view');
			}
			$elements = ob_get_clean();

			if ( isset($options['pagination']) && $options['pagination'] == 'y' ) {
				ob_start();
				ob_clean();
				global $vdf_list_query;
				$vdf_list_query = $query;
				get_template_part('template-pagination');
				$pagination_content = ob_get_clean();
			}
			wp_reset_postdata();

		} else {
			return $grid_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . esc_html__( 'Nothing Found', 'videofly' ) . '</div>' . $grid_view_end;
		}

		if ( $original_query === null ) {
			$next_prev_links = '';
		} else {
			$next_prev_links = self::archive_navigation();
		}

		$args['options'] = $options;
		$load_more = (($pagination === 'load-more' || $pagination == 'infinite') && $ajax_load_more === false) ? '<div ' . $display_load_more . ' class="ts-pagination-more' . $infinite_scroll . '" data-loop="1" data-args="' . ts_enc_string(serialize($args)) . '"><i class="icon-restart"></i><span>' .esc_html__('Load More', 'videofly') . '</span>' . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

		if (@$options['behavior'] === 'carousel') {
			return $grid_view_start . $carousel_wrapper_start . $carousel_navigation . $carousel_container_start . $elements . $carousel_container_end . $carousel_wrapper_end . $grid_view_end;
		}else if( isset($options['behavior']) && $options['behavior'] === 'scroll' ) {
			return  $grid_view_start . '<div class="row">' . $elements .'</div>'. $grid_view_end . $next_prev_links . $pagination_content;
		}else if( $ajax_load_more === true ){
			return  $elements;
		}else{
			return  $grid_view_start . balanceTags($tab_category_html, true) . $elements . $grid_view_end . $load_more . $next_prev_links . $pagination_content;
		}

	} else if ($options['display-mode'] === 'list') {

		$list_view_start = '<section class="ts-list-view ' . $display_effect . ' ">';
		$list_view_end = '</section>';

		if ( $original_query === null ) {

			$order    = self::order_direction($options['order-direction']);

			if ( get_query_var('paged') ) {
				$current = get_query_var('paged');
			} elseif ( get_query_var('page') ) {
				$current = get_query_var('page');
			} else {
				$current = 1;
			}

			$args = array(
				'post_type' => 'ts-gallery',
				'order' => $order,
				'paged' => $current,
				'post__not_in' => $exclude_id,
				'posts_per_page' => (int)$options['posts-limit']

				);

			if( $pagination === 'load-more' || $pagination === 'n' || $pagination == 'infinite' ){
				$args['offset'] = $exclude_first;
			}

			if ( is_array($categories) && count($categories) > 0 ) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'gallery_categories',
						'field' => 'slug',
						'terms' => $categories
						)
					);
			} else {
				$args['category__in'] = array(0);
			}

			$args = self::order_by($options['order-by'], $args, $featured);

			$query = new WP_Query( $args );

		} else {
			$query = &$original_query;
		}

		$articles = array();
		if ( $query->have_posts() ) {
			ob_start();
			ob_clean();
			global $article_options;
			$article_options = $options;
			while ( $query->have_posts() ) {
				$query->the_post();
				get_template_part('includes/layout-builder/templates/list-view');
			}
			$elements = ob_get_clean();

			if ( isset($options['pagination']) && $options['pagination'] == 'y' ) {
				ob_start();
				ob_clean();
				global $vdf_list_query;
				$vdf_list_query = $query;
				get_template_part('template-pagination');
				$pagination_content = ob_get_clean();
			}

			wp_reset_postdata();

		} else {
			return $list_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . esc_html__( 'Nothing Found', 'videofly' ) . '</div>' . $list_view_end;
		}

		if ( $original_query === null ) {
			$next_prev_links = '';
		} else {
			$next_prev_links = self::archive_navigation();
		}

		$args['options'] = $options;
		$load_more = (($pagination === 'load-more' || $pagination == 'infinite') && $ajax_load_more === false) ? '<div ' . $display_load_more . ' class="ts-pagination-more' . $infinite_scroll . '" data-loop="1" data-args="' . ts_enc_string(serialize($args)) . '"><i class="icon-restart"></i><span>' .esc_html__('Load More', 'videofly') . '</span>' . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

		if( $ajax_load_more ){
			return $elements;
		}else{
			return $list_view_start . $elements . $list_view_end . $load_more . $next_prev_links . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . $pagination_content . '</div>';
		}


	} else if ($options['display-mode'] === 'thumbnails') {

		$use_gutter = '';

		if( isset($options['gutter']) ){
			if( $options['gutter'] === 'y' ){
				$use_gutter = ' no-gutter';
			}
		}

		$vdf_masonry_class = '';
		if ( isset($options['behavior']) && $options['behavior'] == 'masonry' ) {
			$vdf_masonry_class = ' ts-filters-container ';
		}

		$thumbnails_view_start = '<section class="ts-thumbnail-view ' . $display_effect . $scroll . $use_gutter . $vdf_masonry_class . ' ' . self::get_clear_class($options['elements-per-row']) . ' ">';
		$thumbnails_view_end = '</section>';

		$carousel_navigation = '<ul class="carousel-nav">
		<li class="carousel-nav-left icon-left">
			<span class="hidden_btn">'.esc_html__('prev','videofly').'</span>
		</li>
		<li class="carousel-nav-right icon-right">
			<span class="hidden_btn">'.esc_html__('next','videofly').'</span>
		</li>
	</ul>';

	$carousel_wrapper_start = '<div class="carousel-wrapper">';
	$carousel_wrapper_end = '</div>';

	$carousel_container_start = '<div class="carousel-overview"><div class="carousel-container">';
	$carousel_container_end = '</div></div>';

	$valid_columns = array(1, 2, 3, 4, 6);

	if ( ! in_array($options['elements-per-row'], $valid_columns)) {
		$options['elements-per-row'] = 3;
	}

	$tab_category_html = '';

	if ( $original_query === null ) {

		$order    = self::order_direction($options['order-direction']);

		if ( get_query_var('paged') ) {
			$current = get_query_var('paged');
		} elseif ( get_query_var('page') ) {
			$current = get_query_var('page');
		} else {
			$current = 1;
		}

		$args = array(
			'post_type' => 'ts-gallery',
			'order' => $order,
			'paged' => $current,
			'post__not_in' => $exclude_id,
			'posts_per_page' => (int)$options['posts-limit']
			);

		if( $pagination === 'load-more' || $pagination === 'n' || $pagination == 'infinite' ){
			$args['offset'] = $exclude_first;
		}

		$select_by_category = (isset($options['behavior']) && $options['behavior'] == 'tabbed') ? 'tabbed' : '';

		if ( is_array($categories) && count($categories) > 0 ) {

			if( $select_by_category == 'tabbed' ){
				$tab_category_html = '<div class="col-lg-12">
				<ul class="ts-select-by-category">';
					$category_i = 1;
					$tab_div_category = '';

					foreach($categories as $key=>$slug_category){
						$active_item = $key == 0 ? ' class="active"' : '';
						$category = get_term_by('slug', $slug_category, 'gallery_categories');
						if( is_object($category) ){
							$tab_category_html .= '<li' . $active_item . ' data-category-li="' . $category->term_id . '"><a href="#">' . $category->name . '</a></li>';
							if( $category_i == count($categories) ) $tab_category_html .= '</ul>';
							$tab_div_category .= '<div data-category-div="' . $category->term_id . '"><div class="row ts-cat-row"></div></div>';
						}
						$category_i++;
					}
					$tab_category_html .= $tab_div_category . '</div>';
				}

				$args['tax_query'] = array(
					array(
						'taxonomy' => 'gallery_categories',
						'field' => 'slug',
						'terms' => $categories
						)
					);
			} else {
				$args['category__in'] = array(0);
			}

			$args = self::order_by($options['order-by'], $args, $featured);

			$query = new WP_Query( $args );
		} else {
			$query = &$original_query;
		}

		$elements = array();

		if ( $query->have_posts() ) {
			ob_start();
			ob_clean();
			global $article_options;
			$article_options = $options;
			$article_options['i'] = 1;
			$article_options['j'] = $query->post_count;
			while ( $query->have_posts() ) {
				$query->the_post();
				get_template_part('includes/layout-builder/templates/thumbs-view');
			}
			$elements = ob_get_clean();

			if ( isset($options['pagination']) && $options['pagination'] == 'y' ) {
				ob_start();
				ob_clean();
				global $vdf_list_query;
				$vdf_list_query = $query;
				get_template_part('template-pagination');
				$pagination_content = ob_get_clean();
			}

			wp_reset_postdata();

		} else {
			return $thumbnails_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . esc_html__( 'Nothing Found', 'videofly' ) . '</div>' . $thumbnails_view_end;
		}

		if ( $original_query === null ) {
			$next_prev_links = '';
			/* Restore original Post Data */
			wp_reset_postdata();
		} else {
			$next_prev_links = self::archive_navigation();
		}

		if( ! isset( $options['behavior'] ) ){
			@$options['behavior'] = 'none';
		}


		$args['options'] = $options;
		$load_more = (($pagination === 'load-more' || $pagination == 'infinite') && $ajax_load_more === false) ? '<div ' . $display_load_more . ' class="ts-pagination-more' . $infinite_scroll . '" data-loop="1" data-args="' . ts_enc_string(serialize($args)) . '"><i class="icon-restart"></i><span>' .esc_html__('Load More', 'videofly') . '</span>' . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

		if (@$options['behavior'] === 'carousel') {
			return $thumbnails_view_start . $carousel_wrapper_start . $carousel_navigation . $carousel_container_start . $elements . $carousel_container_end. $carousel_wrapper_end . $thumbnails_view_end;
		} else if( @$options['behavior'] === 'scroll' ) {
			return $thumbnails_view_start . '<div class="row">' . $elements .'</div>' . $thumbnails_view_end . $next_prev_links . $pagination_content;
		} else if( $ajax_load_more === true ) {
			return $elements;
		} else {
			return $thumbnails_view_start . balanceTags($tab_category_html, true) . $elements . $thumbnails_view_end . $load_more . $next_prev_links . $pagination_content;
		}

	} else if ($options['display-mode'] === 'big-post') {

		$big_post_view_start = '<section class="ts-big-posts ' . $display_effect . ' ">';
		$big_post_view_end = '</section>';

		$carousel = (isset($options['carousel']) && ($options['carousel'] == 'y' || $options['carousel'] == 'n')) ? $options['carousel'] : 'n';
		$carousel_wrapper_start = ($carousel == 'y') ? '<div class="carousel-wrapper">' : '';
		$carousel_wrapper_end = ($carousel == 'y') ? '</div>' : '';

		$carousel_container_start = '<div class="carousel-overview"><div class="carousel-container">';
		$carousel_container_end = '</div></div>';

		$carousel_navigation = ($carousel == 'y') ? '<ul class="carousel-nav">
		<li class="carousel-nav-left icon-left">
			<span class="hidden_btn">'.esc_html__('prev','videofly').'</span>
		</li>
		<li class="carousel-nav-right icon-right">
			<span class="hidden_btn">'.esc_html__('next','videofly').'</span>
		</li>
	</ul>' : '';

	if ( $original_query === null ) {

		$order    = self::order_direction($options['order-direction']);

		if ( get_query_var('paged') ) {
			$current = get_query_var('paged');
		} elseif ( get_query_var('page') ) {
			$current = get_query_var('page');
		} else {
			$current = 1;
		}

		$args = array(
			'post_type' => 'ts-gallery',
			'order' => $order,
			'post__not_in' => $exclude_id,
			'posts_per_page' => (int)$options['posts-limit'],
			'paged' => $current
			);

		if( $pagination === 'load-more' || $pagination === 'n' || $pagination == 'infinite' ){
			$args['offset'] = $exclude_first;
		}

		if ( is_array($categories) && count($categories) > 0 ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'gallery_categories',
					'field' => 'slug',
					'terms' => $categories
					)
				);
		} else {
			$args['category__in'] = array(0);
		}

		$args = self::order_by($options['order-by'], $args, $featured);

		$query = new WP_Query( $args );

	} else {
		$query = &$original_query;
	}

	$elements = array();

	if ( $query->have_posts() ) {
		ob_start();
		ob_clean();
		global $article_options;
		$article_options = $options;
		$article_options['i'] = 1;

		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part('includes/layout-builder/templates/big-posts');
		}
		$elements = ob_get_clean();

		if ( isset($options['pagination']) && $options['pagination'] == 'y' ) {
			ob_start();
			ob_clean();
			global $vdf_list_query;
			$vdf_list_query = $query;
			get_template_part('template-pagination');
			$pagination_content = ob_get_clean();
		}

		wp_reset_postdata();

	} else {
		return $big_post_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . esc_html__( 'Nothing Found', 'videofly' ) . '</div>' . $big_post_view_end;
	}

	if ( $original_query === null ) {
		$next_prev_links = '';
	} else {
		$next_prev_links = self::archive_navigation();
	}

	$args['options'] = $options;
	$load_more = (($pagination === 'load-more' || $pagination == 'infinite') && $ajax_load_more === false) ? '<div ' . $display_load_more . ' class="ts-pagination-more' . $infinite_scroll . '" data-loop="1" data-args="' . ts_enc_string(serialize($args)) . '"><i class="icon-restart"></i><span>' .esc_html__('Load More', 'videofly') . '</span>' . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

	if( $ajax_load_more === true ){
		return $elements;
	}elseif( $carousel == 'y' ){
		return $big_post_view_start . $carousel_wrapper_start . $carousel_navigation . $carousel_container_start . $elements . $carousel_container_end. $carousel_wrapper_end . $big_post_view_end;
	}else{
		return $big_post_view_start . $elements . $big_post_view_end . $load_more . $next_prev_links . $pagination_content;
	}

} else if ($options['display-mode'] == 'super-post') {

	$super_post_view_start = '<section class="ts-super-posts ' . $display_effect . ' ">';
	$super_post_view_end = '</section>';

	$valid_columns = array(1, 2, 3);

	if ( ! in_array($options['elements-per-row'], $valid_columns) ) {
		$options['elements-per-row'] = 1;
	}

	$elements = array();

	if ( $original_query === null ) {

		$order    = self::order_direction($options['order-direction']);

		if ( get_query_var('paged') ) {
			$current = get_query_var('paged');
		} elseif ( get_query_var('page') ) {
			$current = get_query_var('page');
		} else {
			$current = 1;
		}

		$args = array(
			'post_type' => 'ts-gallery',
			'order' => $order,
			'paged' => $current,
			'post__not_in' => $exclude_id,
			'posts_per_page' => (int)$options['posts-limit']
			);

		if( $pagination === 'load-more' || $pagination === 'n' || $pagination == 'infinite' ){
			$args['offset'] = $exclude_first;
		}

		if ( is_array($categories) && count($categories) > 0 ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'gallery_categories',
					'field' => 'slug',
					'terms' => $categories
					)
				);
		} else {
			$args['category__in'] = array(0);
		}

		$args = self::order_by($options['order-by'], $args, $featured);

		$query = new WP_Query( $args );

	} else {
		$query = &$original_query;
	}

	$elements = array();

	if ( $query->have_posts() ) {
		ob_start();
		ob_clean();
		global $article_options;
		$article_options = $options;
		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part('includes/layout-builder/templates/super-posts');
		}
		$elements = ob_get_clean();

		if ( isset($options['pagination']) && $options['pagination'] == 'y' ) {
			ob_start();
			ob_clean();
			global $vdf_list_query;
			$vdf_list_query = $query;
			get_template_part('template-pagination');
			$pagination_content = ob_get_clean();
		}

		wp_reset_postdata();

	} else {
		return $super_post_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . esc_html__( 'Nothing Found', 'videofly' ) . '</div>' . $super_post_view_end;
	}

	if ( $original_query === null ) {
		$next_prev_links = '';
	} else {
		$next_prev_links = self::archive_navigation();
	}

	$args['options'] = $options;
	$load_more = (($pagination === 'load-more' || $pagination == 'infinite') && $ajax_load_more === false) ? '<div ' . $display_load_more . ' class="ts-pagination-more' . $infinite_scroll . '" data-loop="1" data-args="' . ts_enc_string(serialize($args)) . '"><i class="icon-restart"></i><span>' .esc_html__('Load More', 'videofly') . '</span>' . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

	if( $ajax_load_more ){
		return $elements;
	}else{
		return $super_post_view_start . $elements . $super_post_view_end . $next_prev_links . $pagination_content . $load_more;
	}

} else if ($options['display-mode'] === 'timeline') {

	if ( $original_query === null ) {

		$order    = self::order_direction($options['order-direction']);

		if ( get_query_var('paged') ) {
			$current = get_query_var('paged');
		} elseif ( get_query_var('page') ) {
			$current = get_query_var('page');
		} else {
			$current = 1;
		}

		$args = array(
			'post_type' => 'ts-gallery',
			'order' => $order,
			'paged' => $current,
			'post__not_in' => $exclude_id,
			'posts_per_page' => (int)$options['posts-limit']
			);

		if( $pagination === 'load-more' || $pagination === 'n' || $pagination == 'infinite' ){
			$args['offset'] = $exclude_first;
		}

		if ( is_array($categories) && count($categories) > 0 ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'gallery_categories',
					'field' => 'slug',
					'terms' => $categories
					)
				);
		} else {
			$args['category__in'] = array(0);
		}

		$args = self::order_by($options['order-by'], $args, $featured);

		$query = new WP_Query( $args );

	} else {
		$query = &$original_query;
	}

	$articles = array();
	if ( $query->have_posts() ) {

		ob_start();
		ob_clean();
		global $article_options;
		$article_options = $options;
		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part('includes/layout-builder/templates/timeline-view');
		}
		$elements = ob_get_clean();

		if ( isset($options['pagination']) && $options['pagination'] == 'y' ) {
			ob_start();
			ob_clean();
			global $vdf_list_query;
			$vdf_list_query = $query;
			get_template_part('template-pagination');
			$pagination_content = ob_get_clean();
		}

		wp_reset_postdata();

	} else {
		return '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . esc_html__( 'Nothing Found', 'videofly' ) . '</div>';
	}

	if ( $original_query === null ) {
		$next_prev_links = '';
	} else {
		$next_prev_links = self::archive_navigation();
	}

	$args['options'] = $options;
	$load_more = (($pagination === 'load-more' || $pagination == 'infinite') && $ajax_load_more === false) ? '<div ' . $display_load_more . ' class="ts-pagination-more' . $infinite_scroll . '" data-loop="1" data-args="' . ts_enc_string(serialize($args)) . '"><i class="icon-restart"></i><span>' .esc_html__('Load More', 'videofly') . '</span>' . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

	if ( $ajax_load_more === true ) {
		return $elements;
	}else{
		return '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><section class="ts-timeline-view" data-alignment="left">' . $elements . '</section>' . $load_more . '</div>' . $next_prev_links . $pagination_content;
	}

} else if ($options['display-mode'] === 'mosaic') {

	$effect_scroll = (isset($options['effects-scroll']) && $options['effects-scroll'] !== '' && $options['effects-scroll'] == 'default') ? '' : ' fade-effect';

	$gutter_class = (isset($options['gutter']) && $options['gutter'] !== '' && $options['gutter'] == 'y') ? ' mosaic-with-gutter ' : ' mosaic-no-gutter ';

	$scroll = (isset($options['scroll']) && !empty($options['scroll']) && $options['scroll'] == 'y') ?
	'<div data-scroll="true" class="mosaic-view '. $effect_scroll . $gutter_class . ' mosaic-' . $options['layout'] . '">'
	:
	'<div data-scroll="false" class="mosaic-view '. $gutter_class .' mosaic-' . $options['layout'] . '">';

	$img_rows = (isset($options['rows']) && $options['rows'] !== '' && (int)$options['rows'] !== 0) ? (int)$options['rows'] : '3';
	$layout_mosaic = (isset($options['layout']) && ($options['layout'] == 'rectangles' || $options['layout'] == 'square')) ? $options['layout'] : 'square';

	if ( $original_query === null ) {

		$order    = self::order_direction($options['order-direction']);

		if ( get_query_var('paged') ) {
			$current = get_query_var('paged');
		} elseif ( get_query_var('page') ) {
			$current = get_query_var('page');
		} else {
			$current = 1;
		}

		$args = array(
			'post_type' => 'ts-gallery',
			'posts_per_page' => (int)$options['posts-limit'],
			'paged' => $current,
			'order' => $order,
			'post__not_in' => $exclude_id

			);

		if( isset($options['scroll']) && $options['scroll'] == 'y' || ($options['scroll'] == 'n' && ($pagination === 'n' || $pagination === 'load-more' || $pagination == 'infinite')) ){
			$args['offset'] = $exclude_first;
		}

		if ( is_array($categories) && count($categories) > 0 ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'gallery_categories',
					'field' => 'slug',
					'terms' => $categories
					)
				);
		} else {
			$args['category__in'] = array(0);
		}

		$args = self::order_by($options['order-by'], $args, $featured);

		$query = new WP_Query( $args );

	} else {
		$query = &$original_query;
	}

	$articles = array();
	if ( $query->have_posts() ) {
		ob_start();
		ob_clean();
		global $article_options;
		$article_options = $options;
		$article_options['i'] = (isset($options['loop'])) ? (int)$options['loop'] + 1 : 1;
		$article_options['j'] = $query->post_count;
		$article_options['k'] = 1;

		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part('includes/layout-builder/templates/mosaic-view');
			$article_options['i']++;
			$article_options['k']++;
			if( $article_options['k'] === 7 && $layout_mosaic == 'rectangles' && $img_rows == 2  ){
				$article_options['k'] = 1;
			}
			if( $article_options['k'] === 10 && $layout_mosaic == 'rectangles' && $img_rows == 3  ){
				$article_options['k'] = 1;
			}
			if( $article_options['k'] === 6 && $layout_mosaic == 'square' ){
				$article_options['k'] = 1;
			}
		}

		$elements = ob_get_clean();

		$pagination_content = '';
		if ( isset($options['scroll']) && $options['scroll'] == 'n' && $pagination == 'y' ) {
			ob_start();
			ob_clean();
			global $vdf_list_query;
			$vdf_list_query = $query;
			get_template_part('template-pagination');
			$pagination_content = ob_get_clean();
		}

		wp_reset_postdata();

	} else {
		return '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . esc_html__( 'Nothing Found', 'videofly' ) . '</div>';
	}
	$args['options'] = $options;
	$load_more = (isset($options['scroll']) && $options['scroll'] == 'n' && ($pagination === 'load-more' || $pagination == 'infinite') && $ajax_load_more === false) ? '<div ' . $display_load_more . ' class="ts-pagination-more' . $infinite_scroll . '" data-loop="1" data-args="' . ts_enc_string(serialize($args)) . '"><i class="icon-restart"></i><span>' .esc_html__('Load More', 'videofly') . '</span>' . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

	if ( $original_query === null ) {
		$next_prev_links = '';
	} else {
		$next_prev_links = self::archive_navigation();
	}

	if( $ajax_load_more ){
		return $elements;
	}else{
		return $scroll . $elements . $next_prev_links . '</div>' . $load_more . $pagination_content;
	}

}

}

	public static function gallery_element($options = array())
	{
		ob_start();
		ob_clean();
		global $article_options;
		$article_options = $options;
		get_template_part('includes/layout-builder/templates/gallery');
		$element = ob_get_clean();

		return $element;
	}

	public static function featured_article_element($options = array())
	{
		$postId = (isset($options['post-id']) && is_numeric($options['post-id'])) ? $options['post-id'] : '';
		if( $postId == '' ) return;

		$featuredArticleQuery = get_post($postId);

		if( !is_null($featuredArticleQuery) ){
			ob_start();
				ob_clean();
				global $article_options;
				$article_options = $options;
				$article_options['article'] = $featuredArticleQuery;
				get_template_part('includes/layout-builder/templates/featured-article');
			$postHtml = ob_get_clean();

			return $postHtml;
		}
	}

	public static function user_element($options = array())
	{
		ob_start();
			ob_clean();
			get_template_part('includes/layout-builder/templates/user');
		$html = ob_get_clean();

		return '<div class="ts-login '. $options['align'] .'">'. $html .'</div>';
	}

	public static function tsGetPostImg( $postId, $title, $size, $masonry = false, $src = '' )
	{
		$templateUri = get_template_directory_uri();
		$genderalOptions = get_option('videofly_general');

		$src = empty( $src ) && has_post_thumbnail( $postId ) ? wp_get_attachment_url( get_post_thumbnail_id( $postId ) ) : ( ! empty( $src ) ? $src : $templateUri . '/images/noimage.jpg');

		$imgUrl = vdf_resize( $size, $src, $masonry );

		$bool = isset( $genderalOptions['enable_imagesloaded'] ) && $genderalOptions['enable_imagesloaded'] == 'Y' ? true : false;

		return '<img ' . vdf_imagesloaded( $bool, $imgUrl ) . ' alt="' . esc_attr( $title ) . '"/>';
	}

	public static function boca_nona_element($options = array())
	{
		$categories = isset($options['category']) && !empty($options['category']) && is_string($options['category']) ? explode(',', $options['category']) : '';

		$args = array(
			'post_type'      => $options['custom-post'],
			'order'          => $options['order'],
			'posts_per_page' => (int)$options['posts_per_page']
		);

		if ( !empty($categories) ) {

			$args['tax_query'] = array(
				array(
					'taxonomy' => ($options['custom-post'] == 'post' ? 'category' : ($options['custom-post'] == 'video' ? 'videos_categories' : 'gallery_categories')),
					'field'    => 'slug',
					'terms'    => $categories
					)
				);

		} else {
			$args['category__in'] = array(0);
		}

		$args = self::order_by($options['orderby'], $args, $options['featured']);

		$query = new WP_Query($args);

		$elements = '';

		if ( $query->have_posts() ) {

			global $article_options;
			$article_options = $options;

			if( $options['type'] == 'nona' ) {
				$article_options['nona-info'] = '';
				$article_options['nona-nav'] = '';
			}

			ob_start();
			ob_clean();

				while ( $query->have_posts() ) {$query->the_post();

					get_template_part('includes/layout-builder/templates/'. $options['type']);

				}

			$elements = ob_get_clean();

			wp_reset_postdata();

		} else {
			return '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . esc_html__('Nothing Found', 'videofly') . '</div>';
		}


		if ( $options['type'] == 'boca' ) {
			$html = '<div class="post-slides">'.
						$elements .
					'</div>';
		} else {

			$html = '<div class="ts-slide-type-six">'.
						$article_options['nona-info'] .
					'</div>';

			$html .= '<div class="ts-slide-nav">'.
						$article_options['nona-nav'] .
					 '</div>';

		}

		return 	'<div class="col-lg-12 col-sm-12 col-xs-12"><section class="'. ($options['type'] == 'boca' ? 'ts-post-boca' : 'ts-post-nona') .'">'.
					$html .
				'</section></div>';

	}

	public static function instance_element( $options = array() )
	{
		global $article_options;
		$article_options = $options;

		ob_start(); ob_clean();
			get_template_part( 'includes/layout-builder/templates/instance' );
		$html = ob_get_clean();

		return $html;
	}
}

?>
