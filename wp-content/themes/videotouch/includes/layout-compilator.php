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

	public static function order_direction($order_direction = 'asc')
	{
		return ($order_direction === 'asc') ? 'ASC' : 'DESC';
	}

	public static function order_by( $order_by = 'date', $args = array(), $featured = 'n' )
	{
		$order_variants = array('date', 'comments', 'views', 'likes');

		$order_by = (in_array($order_by, $order_variants)) ? $order_by : 'date' ;

		if( $order_by === 'comments' ){
			$args['orderby'] = 'comment_count';
		}
		if( $order_by === 'views' ){
			$args['meta_key'] = 'ts_article_views';
			$args['orderby']  = 'meta_value_num';
		}

		if( $featured === 'y' ){

			$args['meta_query'] = array(
									array(
										'key' => 'featured',
										'value' => 'yes',
										'compare' => '=',
									),
								);

		}
		if( $order_by === 'views' ){
			$args['meta_key'] = 'ts_article_views';
			$args['orderby']  = 'meta_value_num';
		}
		if( $order_by === 'likes' ){
			$args['meta_key'] = '_touchsize_likes';
			$args['orderby']  = 'meta_value_num';
		}

		if( $order_by === 'date' ){
			$args['orderby'] = 'date';
		}
		return $args;
	}

	public static function get_related_posts($post_id = 0, $tags = array())
	{
		$single_options = get_option('videotouch_single_post');
		$criteria = $single_options['related_posts_selection_criteria'];

		if ( $criteria === 'by_tags' ) {

			$post_type = get_post_type($post_id);

			$args['posts_per_page'] = (int)$single_options['number_of_related_posts'];
			$args['post_type'] = $post_type;

			if ( $tags ) {
				$tag_id = array();
				foreach($tags as $tag) {
				    $tag_id[] = $tag->term_id;
				}

				$args = array(
					'tag__in' => $tag_id,
					'post__not_in' => array( $post_id ),
					'post_type' => 'post',
					'posts_per_page' => 3,
				);

				$query = new WP_Query( $args );

				$related = '<footer>
									<div class="related">
										<p class="title">' . __('Related articles', 'touchsize') . '</p>
										<ul class="related-list">
											{{articles}}
										</ul>
									</div>
							</footer>';

				$related_posts = array();

				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {$query->the_post();

						if (ts_human_type_date_format()) {
							$article_date = human_time_diff(strtotime(get_the_time('Y-m-d H:i:s'))).' '.__('ago', 'touchsize');
						} else {
							$article_date =  get_the_date();
						}

						$related_posts[] = '<li>
												<div class="related-thumb"><a href="'.get_permalink(get_the_ID()).'">'. get_the_post_thumbnail( get_the_ID(),  'thumb' ) .'</a></div>
												<div class="related-content">
													<a href="'.get_permalink(get_the_ID()).'">'.esc_attr(get_the_title()).'</a>
													<div class="ts-view-entry-meta-date">
														<ul>
															<li>'.$article_date.'</li>
														</ul>
													</div>
												</div>
											</li>';
					}

					return str_replace('{{articles}}', implode("\n", $related_posts), $related);

				} else {
					// wp_reset_postdata();
					return '';
				}

				wp_reset_postdata();

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

					$args['tax_query'] = array('relation' => 'AND',
											array(
												'taxonomy' => 'videos_categories',
												'field' => 'id',
												'terms' => $term_list,
												'operator' => 'IN'
											)
										 );
					$args['post_type'] = 'video';
					$args['posts_per_page'] = (int)$single_options['number_of_related_posts'];

					$query = new WP_Query( $args );

					$related =  '<footer>
									<div class="related">
										<p class="title">' . __('Related articles', 'touchsize') . '</p>
										<ul class="related-list">
											{{articles}}
										</ul>
									</div>
							    </footer>';
					$related_posts = array();

					if ( $query->have_posts() ) {
						while ( $query->have_posts() ) { $query->the_post();

							if (ts_human_type_date_format()) {
								$article_date = human_time_diff(strtotime(get_the_time('Y-m-d H:i:s'))).' '.__('ago', 'touchsize');
							} else {
								$article_date =  get_the_date();
							}

							$related_posts[] = '<li>
													<div class="related-thumb"><a href="'.get_permalink(get_the_ID()).'">'. get_the_post_thumbnail( get_the_ID(),  'thumb' ) .'</a></div>
													<div class="related-content">
														<a href="'.get_permalink(get_the_ID()).'">'.esc_attr(get_the_title()).'</a>
														<div class="ts-view-entry-meta-date">
															<ul>
																<li>'.$article_date.'</li>
															</ul>
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

				$args['category__in'] = $categories;
				$args['posts_per_page'] = (int)$single_options['number_of_related_posts'];
				$args['post_type'] = $post_type;

				$query = new WP_Query( $args );

				$related =  '<footer>
								<div class="related">
									<p class="title">' . __('Related articles', 'touchsize') . '</p>
									<ul class="related-list">
										{{articles}}
									</ul>
								</div>
						    </footer>';
				$related_posts = array();

				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) { $query->the_post();

						if (ts_human_type_date_format()) {
							$article_date = human_time_diff(strtotime(get_the_time('Y-m-d H:i:s'))).' '.__('ago', 'touchsize');
						} else {
							$article_date =  get_the_date();
						}

						$related_posts[] = '<li>
												<div class="related-thumb"><a href="'.get_permalink(get_the_ID()).'">'. get_the_post_thumbnail( get_the_ID(),  'thumb' ) .'</a></div>
												<div class="related-content">
													<a href="'.get_permalink(get_the_ID()).'">'.esc_attr(get_the_title()).'</a>
													<div class="ts-view-entry-meta-date">
														<ul>
															<li>'.$article_date.'</li>
														</ul>
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

	public static function get_single_related_posts($post_id = 0, $single_video = false) {

		$single_options = get_option('videotouch_single_post');
		$single_sidebar = get_option('videotouch_single_post', array('video_sidebar' => 'n'));
		$post_type = get_post_type($post_id);

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
			$options['display-title'] = 'title-above-excerpt';
			$options['show-meta'] = 'y';
			$options['enable-carousel'] = 'n';
		}
		if ( $options['display-mode'] === 'thumbnails' ) {
			$options['meta-thumbnail'] = 'y';
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
				if( $single_video == false ){
					return self::last_posts_element($options, $query);
				}else{
					return $query;
				}
			} else {
				return '';
			}

		} else if ( $criteria === 'by_categs' ) {

			$category_id = array();
			$categories = wp_get_post_categories( $post_id );

			if( isset($post_type) && $post_type == 'video' ){
				$term_list = wp_get_post_terms($post_id, 'videos_categories', array("fields" => "ids"));
				if ( isset($term_list) && is_array($term_list) && !empty($term_list) ) {

					$args['tax_query'] = array('relation' => 'AND',
											array(
												'taxonomy' => 'videos_categories',
												'field' => 'id',
												'terms' => $term_list,
												'operator' => 'IN'
											)
										);
					$args['post_type'] = 'video';
					$args['posts_per_page'] = (int)$single_options['number_of_related_posts'];

					$query = new WP_Query( $args );
					if ( $query->have_posts() ) {
						if( $single_video == false ){
							return self::last_posts_element($options, $query);
						}else{
							return $query;
						}
					} else {
						return '';
					}
				}
			}

			if ( $categories ) {

				$args['category__in'] = $categories;
				$args['posts_per_page'] = (int)$single_options['number_of_related_posts'];
				$args['post_type'] = $post_type;

				$query = new WP_Query( $args );

				if ( $query->have_posts() ) {
					if( $single_video == false ){
						return self::last_posts_element($options, $query);
					}else{
						return $query;
					}
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

		$ts_masonry_class = '';
		if( @$options['behavior'] === 'masonry' ){
			$ts_masonry_class = ' ts-filters-container ';
		}

		// If carousel is enabled

		if( $options['behavior'] === 'carousel' ){
		$carousel_wrapper_start = '<div class="carousel-wrapper">';
		$carousel_wrapper_end = '</div>';

		$carousel_container_start = '<div class="carousel-container">';
		$carousel_container_end = '</div>';

		$carousel_navigation = '<ul class="carousel-nav">
			                        <li class="carousel-nav-left icon-left"></li>
			                        <li class="carousel-nav-right icon-right"></li>
				                </ul>';
		} else{
			$carousel_wrapper_start = '';
			$carousel_wrapper_end = '';
			$carousel_container_start = '';
			$carousel_container_end = '';
			$carousel_navigation = '';
		}

		$elements = (isset($elements)) ? $elements : '';

		return '<div class="woocommerce"><section class="product-view cols-by-' . $options['elements-per-row'] . ' ' . $display_effect . $ts_masonry_class . '">'. $carousel_wrapper_start . $carousel_navigation . $carousel_container_start . $elements . $carousel_container_end . $carousel_wrapper_end .'</section></div>';
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
			echo get_the_content();
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
		if( @$sidebar_options['position'] == 'left' && !LayoutCompilator::builder_is_enabled() || @$sidebar_options['position'] == 'right' && !LayoutCompilator::builder_is_enabled()){
			$theme_styles = get_option('videotouch_styles');
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


	public static function defaults( $file = 'search' )
	{
		// global $wp_query;

		// $options = get_option('videotouch_layout');
		// $title = '';

		// switch ($file) {
		// 	case 'search':
		// 		$title = __('Search results: ','touchsize') . get_search_query();
		// 		$sidebar_options = $options['search_layout']['sidebar'];
		// 		$view_type = $options['search_layout']['display-mode'];
		// 		$view_options = $options['search_layout'][$view_type];
		// 		$view_options['display-mode'] = $view_type;
		// 		break;

		// 	case 'archive':

		// 		if ( is_day() ) :
		// 			$title = __( 'Daily Archives: ', 'touchsize' ) . get_the_date();
		// 		elseif ( is_month() ) :
		// 			$title = __( 'Monthly Archives: ', 'touchsize' ) . get_the_date( _x( 'F Y', 'monthly archives date format', 'touchsize' ) );
		// 		elseif ( is_year() ) :
		// 			$title = __( 'Yearly Archives: ', 'touchsize' ) . get_the_date( _x( 'Y', 'yearly archives date format', 'touchsize' ) );
		// 		else :
		// 			$title = __( 'Archives', 'touchsize' );
		// 		endif;

		// 		$sidebar_options = $options['archive_layout']['sidebar'];
		// 		$view_type = $options['archive_layout']['display-mode'];
		// 		$view_options = $options['archive_layout'][$view_type];
		// 		$view_options['display-mode'] = $view_type;
		// 		break;

		// 	case 'category':

		// 		$title = __('Category archives: ', 'touchsize') . single_cat_title( '', false );

		// 		$sidebar_options = $options['category_layout']['sidebar'];
		// 		$view_type = $options['category_layout']['display-mode'];
		// 		$view_options = $options['category_layout'][$view_type];
		// 		$view_options['display-mode'] = $view_type;
		// 		break;

		// 	case 'blog':
		// 		$sidebar_options = $options['blog_layout']['sidebar'];
		// 		$view_type = $options['blog_layout']['display-mode'];
		// 		$view_options = $options['blog_layout'][$view_type];
		// 		$view_options['display-mode'] = $view_type;
		// 		break;

		// 	case 'author':

		// 		if ( $wp_query->have_posts() ) {

		// 			$wp_query->the_post();

		// 			$title = __( 'All posts by ', 'touchsize' ) . '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a>';
		// 		}

		// 		$sidebar_options = $options['author_layout']['sidebar'];
		// 		$view_type = $options['author_layout']['display-mode'];
		// 		$view_options = $options['author_layout'][$view_type];
		// 		$view_options['display-mode'] = $view_type;
		// 		break;

		// 	case 'tags':

		// 		$title = __( 'Tag Archives: ', 'touchsize' ) . single_tag_title( '', false );

		// 		$sidebar_options = $options['tags_layout']['sidebar'];
		// 		$view_type = $options['tags_layout']['display-mode'];
		// 		$view_options = $options['tags_layout'][$view_type];
		// 		$view_options['display-mode'] = $view_type;
		// 		break;

		// 	default:
		// 		return '';
		// 		break;
		// }

		// $header_elements = get_option('videotouch_header', array());
		// $footer_elements = get_option('videotouch_footer', array());

		// $header = self::build_header($header_elements);
		// $footer = self::build_footer($footer_elements);

		// extract(self::build_sidebar( $sidebar_options ));

		// $content = self::last_posts_element($view_options, $wp_query);

		// if ( $file == 'search' && $wp_query->posts == array() ) {
		// 	$content = '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		// 					<h1 class="title-404"><i class="icon-attention"></i>' . __('Ooops!', 'touchsize') . '</h1>
		// 					<div class="nothing-message"> ' . __('We didn\'t find anything. Try searching!', 'touchsize') . '</div>
		// 					<div class="search-404">
		// 					' . self::searchbox_element('searchbox') . '
		// 					</div>
		// 				</div>';
		// }

		// if ( ! in_array($file, array('blog'))) {
		// 	$title = '<div class="col-lg-12 col-md-12"><h2 class="page-title">' . $title . '</div></h2>';
		// }

		// $content = '<div id="primary" class="'.$content_class.'"><div id="content" role="main"><div class="row">' . $title . $content . '</div></div></div>';

		// if ( @$sidebar_options['position'] === 'left' ) {
		// 	$content = $sidebar_content . $content;
		// } else if ( @$sidebar_options['position'] === 'right' ) {
		// 	$content = $content . $sidebar_content;
		// }

		// echo $header . '<section id="main" class="row"><div class="container"><div class="row">' . $content . '</div></div></section>' . $footer;
		// die();
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
		ob_start();
		dynamic_sidebar( (string)$options['id']);
		$sidebar = ob_get_contents();
		ob_end_clean();
		if ( $options['position'] !== 'none' ) {

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

			$sidebar_content = '<div class="secondary '.$sidebar_class.'">' . $sidebar . '</div>';

		} else {
			$sidebar_content = '';
			$sidebar_class = '';
			$content_class = self::$columns[12];
		}
		if ( self::builder_is_enabled()) {
			$content_class = 'ts-page-with-layout-builder col-lg-12 col-md-12 col-sm-12 col-xs-12';
		}

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
		}elseif( is_singular() && !is_page() ){
			$page_type = 'single';
		}elseif( is_singular() && !is_page() && get_post_type($post->ID) == 'product' ){
			$page_type = 'product';
		} else{
			$page_type = 'archive';
		}

		$sidebar_option = fields::get_options_value('videotouch_layout', $page_type. '_layout');
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
			$sidebar = fields::get_options_value('videotouch_layout', $page_type. '_layout');
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

		$sidebar_option = fields::get_options_value('videotouch_layout', $page_type. '_layout');

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

		$sidebar_option = fields::get_options_value('videotouch_layout', $page_type. '_layout');

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
			$elementsWithScripts = array('image-carousel', 'featured-area', 'user', 'counters', 'toggle');
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

				$div_mask = '';


				if( isset($row['settings']['rowMask']) && $row['settings']['rowMask'] == 'yes' ){
					if ( $opacity !== '' ) {
						$additional_row_class .= ' has-row-mask ';
						$row_mask_color = (isset($row['settings']['rowMaskColor'])) ? $row['settings']['rowMaskColor'] : '';
						$div_mask = "<div class='row-mask' style='background-color:". $row_mask_color .";opacity:". $opacity ."'></div>";
					}
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

				$row_wrapper_start = '<div data-alignment="'. $vertical_align . '" ' . $row_name_id . ' class="site-section ' . $additional_row_class . '" '.self::row_settings($row['settings']).'>'.$div_mask;
				$row_wrapper_end = '</div>';

				$row_start = '<div class="row">';
				$row_end   = '</div>';

				if ( is_array( $row['columns'] ) && ! empty( $row['columns'] ) ) {
					$columns = array();

					foreach ( $row['columns'] as $column_index => $column ) {
						$elements = '';

						if ( is_array( $column['elements'] ) && ! empty( $column['elements'] ) ) {
							foreach ( $column['elements'] as $element_id => $element ) {
								$elements .= self::compile_element($element, $row['settings']['specialEffects']);

								/***  Include scripts and css file  ***/

								if( isset($element['pagination']) && $element['pagination'] == 'load-more' ){
									if( !in_array('isotope', $tsScripts) ){
										$tsScripts[] = 'isotope';
									}
								}

								if( (isset($element['behavior']) && $element['behavior'] == 'masonry' && !in_array('isotope', $tsScripts)) || $element['type'] == 'filters' ){
									$tsScripts[] = 'isotope';
								}

								if( isset($element['behavior']) && $element['behavior'] == 'scroll' && !in_array('mCustomScrollbar', $tsScripts) ){
									$tsScripts[] = 'mCustomScrollbar';
								}

								if( isset($element['display-mode']) && $element['display-mode'] == 'mosaic' && $element['scroll'] == 'y' && !in_array('mCustomScrollbar', $tsScripts)  ){
									$tsScripts[] = 'mCustomScrollbar';
								}

								if( $element['type'] == 'featured-area' && ! in_array( 'bootstrap', $tsScripts ) ){
									$tsScripts[] = 'bootstrap';
								}

								if( ! in_array( $element['type'], $tsScripts ) && in_array($element['type'], $elementsWithScripts) ){
									$tsScripts[] = $element['type'];
								}

								/***  END Include scripts and css file  ***/
							}
						}
						$columns[] = '<div class="'. self::$columns[$column['size']].'">' . $elements . '</div>';
					}
				}

				$compiled_rows[] = $row_wrapper_start . $row_video_bg . $row_container_start . $row_start . implode("\n", $columns) . $row_end . $row_container_end . $row_wrapper_end;
			}

			tsIncludeScripts($tsScripts);

			return implode("\n", $compiled_rows);
		}
	}

	public static function compile_element($element = array(), $effect = 'none')
	{

		switch ($element['type']) {
			case 'logo':
				$e = self::logo_element($element);
				break;

			case 'user':
				$e = self::user_element($element);
				break;

			case 'cart':
				$e = self::cart_element($element);
				break;

			case 'breadcrumbs':
				$e = self::breadcrumbs_element($element);
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

			case 'quote':
				$e = self::quote_element($element);
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

		$header = get_option( 'videotouch_header' . $lang, array() );

		$header = defined( 'ICL_LANGUAGE_CODE' ) && empty( $header ) ? get_option( 'videotouch_header', array() ) : $header;

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

		$footer = get_option( 'videotouch_footer' . $lang, array() );

		$footer = defined( 'ICL_LANGUAGE_CODE' ) && empty( $footer ) ? get_option( 'videotouch_footer', array() ) : $footer;

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
		$css .= ' background-color: ' . $settings['bgColor'] . '; ';
		$css .= ' color: ' . $settings['textColor'] . '; ';

		$gradient = (isset($settings['gradient']) && ($settings['gradient'] === 'n' || $settings['gradient'] === 'y')) ? $settings['gradient'] : 'n';
		$gradient_color = (isset($settings['gradientColor']) && is_string($settings['gradientColor'])) ? $settings['gradientColor'] : '';
		$gradient_mode = (isset($settings['gradientMode']) && is_string($settings['gradientMode'])) ? $settings['gradientMode'] : '';

		if( $gradient == 'y' ) {
			if( $gradient_mode == 'radial' ) {
				$css .= '
					background: '.$settings['bgColor'].';
					background: -moz-radial-gradient(center, ellipse cover,  '.$settings['bgColor'].' 0%,  '.$gradient_color.' 0%,  '.$settings['bgColor'].' 100%, '.$settings['bgColor'].' 100%);
					background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%,'.$settings['bgColor'].'), color-stop(0%, '.$gradient_color.'), color-stop(100%, '.$settings['bgColor'].'), color-stop(100%,'.$settings['bgColor'].'));
					background: -webkit-radial-gradient(center, ellipse cover,  '.$settings['bgColor'].' 0%, '.$gradient_color.' 0%, '.$settings['bgColor'].' 100%,'.$settings['bgColor'].' 100%);
					background: -o-radial-gradient(center, ellipse cover,  '.$settings['bgColor'].' 0%, '.$gradient_color.' 0%, '.$settings['bgColor'].' 100%,'.$settings['bgColor'].' 100%);
					background: -ms-radial-gradient(center, ellipse cover,  '.$settings['bgColor'].' 0%, '.$gradient_color.' 0%, '.$settings['bgColor'].' 100%,'.$settings['bgColor'].' 100%);
					background: radial-gradient(ellipse at center,  '.$settings['bgColor'].' 0%, '.$gradient_color.' 0%, '.$settings['bgColor'].' 100%,'.$settings['bgColor'].' 100%);
				';
			}elseif ( $gradient_mode == 'left-to-right' ) {
				$css .= '
					background: '.$settings['bgColor'].';
					background: -moz-linear-gradient(left, '.$settings['bgColor'].' 0%,  '.$gradient_color.' 0%, '.$settings['bgColor'].' 100%, '.$gradient_color.' 100%);
					background: -webkit-gradient(linear, left top, right top, color-stop(0%,'.$settings['bgColor'].'), color-stop(0%, '.$gradient_color.'), color-stop(100%,'.$settings['bgColor'].'), color-stop(100%,'.$gradient_color.'));
					background: -webkit-linear-gradient(left, '.$settings['bgColor'].' 0%, '.$gradient_color.' 0%,'.$settings['bgColor'].' 100%,'.$gradient_color.' 100%);
					background: -o-linear-gradient(left, '.$settings['bgColor'].' 0%, '.$gradient_color.' 0%,'.$settings['bgColor'].' 100%,'.$gradient_color.' 100%);
					background: -ms-linear-gradient(left, '.$settings['bgColor'].' 0%, '.$gradient_color.' 0%,'.$settings['bgColor'].' 100%,'.$gradient_color.' 100%);
					background: linear-gradient(to right, '.$settings['bgColor'].' 0%, '.$gradient_color.' 0%,'.$settings['bgColor'].' 100%,'.$gradient_color.' 100%);
				';
			}elseif ( $gradient_mode == 'corner-top' ) {
				$css .= '
					background: '.$settings['bgColor'].';
					background: -moz-linear-gradient(-45deg, '.$settings['bgColor'].' 0%,  '.hex2rgb($settings['bgColor'], 0.8).' 47%,  '.$gradient_color.' 100%);
					background: -webkit-gradient(linear, left top, right bottom, color-stop(0%,'.$settings['bgColor'].'), color-stop(47%, '.hex2rgb($settings['bgColor'], 0.8).'), color-stop(100%, '.$gradient_color.'));
					background: -webkit-linear-gradient(-45deg, '.$settings['bgColor'].' 0%, '.hex2rgb($settings['bgColor'], 0.8).' 47%, '.$gradient_color.' 100%);
					background: -o-linear-gradient(-45deg, '.$settings['bgColor'].' 0%, '.hex2rgb($settings['bgColor'], 0.8).' 47%, '.$gradient_color.' 100%);
					background: -ms-linear-gradient(-45deg, '.$settings['bgColor'].' 0%, '.hex2rgb($settings['bgColor'], 0.8).' 47%, '.$gradient_color.' 100%);
					background: linear-gradient(135deg, '.$settings['bgColor'].' 0%, '.hex2rgb($settings['bgColor'], 0.8).' 47%, '.$gradient_color.' 100%);
				';
			}elseif ( $gradient_mode == 'corner-bottom' ) {
				$css .= '
					background: '.$settings['bgColor'].';
					background: -moz-linear-gradient(45deg, '.$settings['bgColor'].' 0%,  '.hex2rgb($settings['bgColor'], 0.8).' 47%,  '.$gradient_color.' 100%);
					background: -webkit-gradient(linear, left bottom, right top, color-stop(0%,'.$settings['bgColor'].'), color-stop(47%, '.hex2rgb($settings['bgColor'], 0.8).'), color-stop(100%, '.$gradient_color.'));
					background: -webkit-linear-gradient(45deg, '.$settings['bgColor'].' 0%, '.hex2rgb($settings['bgColor'], 0.8).' 47%, '.$gradient_color.' 100%);
					background: -o-linear-gradient(45deg, '.$settings['bgColor'].' 0%, '.hex2rgb($settings['bgColor'], 0.8).' 47%, '.$gradient_color.' 100%);
					background: -ms-linear-gradient(45deg, '.$settings['bgColor'].' 0%, '.hex2rgb($settings['bgColor'], 0.8).' 47%, '.$gradient_color.' 100%);
					background: linear-gradient(45deg, '.$settings['bgColor'].' 0%, '.hex2rgb($settings['bgColor'], 0.8).' 47%, '.$gradient_color.' 100%);
				';
			}
		}

		if ($settings['bgImage'] !== '') {

			$css .= " background-image:url('" . $settings['bgImage'] . "') " .  '; ';

			if ($settings['bgPosition'] !== '') {
				$css .= " background-position: ".$settings['bgPosition']." center; ";
			}

			if ($settings['bgAttachement'] !== '') {
				$css .= " background-attachment: ".$settings['bgAttachement']."; ";
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

		$css = (trim($css) === '') ? '' : $open_attr . $css . $close_attr;

		return $css;
	}

	public static function logo_element($options = array())
	{
		$align_logo = ( isset($options['logo-align']) ) ? strip_tags($options['logo-align']) : '';
		return '<div class="col-lg-12 '. $align_logo .'"><a href="'.home_url().'" class="logo">
					' . ts_get_logo() . '
				</a></div>';
	}

	public static function user_element($options = array())
	{
		$div_start = isset( $options['widget'] ) ? '' : '<div class="col-lg-12">';
		$div_end = isset( $options['widget'] ) ? '' : '</div>';

		if( is_user_logged_in() ){

			global $wp_roles;
			$user = wp_get_current_user();

			$single = get_option( 'videotouch_single_post' );

			$link_to_profile  = (isset($single, $single['user_profile']) && !empty($single['user_profile'])) ? esc_url($single['user_profile']) : home_url();
			$link_to_settings = (isset($single, $single['user_settings']) && !empty($single['user_settings'])) ? esc_url($single['user_settings']) : home_url();
			$link_to_add_post = (isset($single, $single['user_add_post']) && !empty($single['user_add_post'])) ? esc_url($single['user_add_post']) : home_url();

    		if( !current_user_can('manage_options') ) {
    			$role = $wp_roles->roles[$user->roles[0]]['name'];
		    }else{
		    	$role = get_role('administrator');
		    	$role = (isset($role->name)) ? $role->name : 'Admin';
		    }

			return 	$div_start .
						'<div class="ts-user-element">
							 <div class="ts-user-profile-dw align-' . ( isset( $options['align'] ) ? $options['align'] : 'left' ) . '">
								<div class="user-mini-avatar">
									<a href="' . $link_to_profile . '">' . ts_display_gravatar(50) . '</a>
								</div>
								<div class="user-info">
									<a data-toggle="dropdown" class="user-name" href="#">'. get_the_author_meta('display_name', get_current_user_id()) .' <i class="icon-down"></i></a>
									<span class="user-role">' . $role . '</span>
									<div class="dropdown">
										<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
											<li><a href="' . $link_to_profile . '">' . __('My profile', 'touchsize') . '</a></li>
											<li><a href="' . $link_to_add_post . '">' . __('New post', 'touchsize') . '</a></li>
											<li><a href="' . $link_to_settings . '">' . __('Settings', 'touchsize') . '</a></li>
											<li><a href="' . wp_logout_url(home_url()) . '">' . __('Logout', 'touchsize') . '</a></li>
										</ul>
									</div>
								</div>
							</div>
						</div>' .
					$div_end;
		}else{
			return	$div_start .
						ts_get_login_form() .
			    	$div_end;
		}

	}

	public static function cart_element($options = array())
	{
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		$align_cart = (isset($options['cart-align']) && ($options['cart-align'] == 'right' || $options['cart-align'] == 'center' || $options['cart-align'] == 'left')) ? ' ' . $options['cart-align'] : '';

		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			global $woocommerce;
			$cart_code = '<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="woocommerce gbtr_dynamic_shopping_bag' . $align_cart . '">
					<div class="gbtr_little_shopping_bag_wrapper">
						<div class="gbtr_little_shopping_bag">
							<div class="overview">
								<span class="minicart_items ';
									if($woocommerce->cart->cart_contents_count == 0){ $cart_code .= 'no-items'; };
								$cart_code .= '"><i class="icon-basket"></i>';
								$cart_code .= sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count).'</span>
								<span class="minicart_total">'. $woocommerce->cart->get_cart_total() .'</span>
							</div>
						</div>
						<div class="gbtr_minicart_wrapper">
							<h4>'. __('My shopping basket', 'redcodn') . '</h4>
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
														$cart_code .= apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="remove" title="%s">&times;</a>', esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ), __('Remove this item', 'woocommerce') ), $cart_item_key ) . '<a href="'.get_permalink($cart_item['product_id']). '" class="cart-item-title">' . apply_filters('woocommerce_cart_widget_product_title', $gbtr_short_product_title, $_product) . '</a><span class="cart_list_product_quantity"> ('.$cart_item['quantity'].')</span>' . '<span class="cart_list_product_price">'.woocommerce_price($_product->get_price()).'</span>
													</div>
													<div class="clr"></div>
												</li>';
											endif;
										endforeach;
										$cart_code .= ' <li class="minicart_total_checkout">
											<div>'. __('Cart subtotal:', 'redcodn') .' </div>
											<span>'. $woocommerce->cart->get_cart_total() .'</span>
										</li>
										<li class="clr">
											<div class="row">
												<div class="col-lg-6 col-md-6 col-sm-6">
													<a href="'. esc_url( $woocommerce->cart->get_cart_url() ) .'" class="button gbtr_minicart_cart_but">'. __('View Cart', 'redcodn') .'</a>
												</div>
												<div class="col-lg-6 col-md-6 col-sm-6">
													<a href="'. esc_url( $woocommerce->cart->get_checkout_url() ) .'" class="button gbtr_minicart_checkout_but">'. __('Checkout', 'redcodn') .'</a>
												</div>
											</div>
										</li>';
									else:
										$cart_code .= '<li class="empty">' .__('No products in the cart.','redcodn').'</li>';
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

		$mega_menu = get_option('videotouch_general');
		$enable_mega_menu = (isset($mega_menu['enable_mega_menu']) && $mega_menu['enable_mega_menu'] == 'Y') ? new ts_responsive_mega_menu : '';
		$uppercase = (isset($options['uppercase']) && !empty($options['uppercase']) && ($options['uppercase'] === 'menu-uppercase' || $options['uppercase'] === 'menu-no-uppercase') && $options['uppercase'] === 'menu-uppercase') ? 'text-uppercase' : '';
		$menu_by_id = (isset($options['name']) && (int)$options['name'] !== 0) ? $options['name'] : NULL;

		$menu_styles = array(
			'style1' => 'ts-standard-menu',
			'style2' => 'ts-vertical-menu',
		);

		$element_style = array_key_exists($options['element-style'], $menu_styles) ? $menu_styles[$options['element-style']] : 'sf-simplemenu';
		$menu_style = '';

		$menu_id = 'menu-element-'.rand(321, 32132213);
		$mobile_menu_id = 'mobile-menu-element-'.rand(11, 392132213);
		$menu_text_align = '';
	 	$menu_text_align = (isset($options['menu-text-align'])) ? @$options['menu-text-align'] : '';
        $menu_with_logo = ($options['element-style'] == 'style3') ? 'menu-with-logo' : '';

        if( @$options['menu-custom'] == 'yes' ){
        	$custom_menu_class = 'ts-custom-menu-colors';
        } else{
        	$custom_menu_class = '';
        }

	 	if(fields::get_options_value('videotouch_general', 'enable_mega_menu') == 'Y'){
	 		$menu_style .= 'ts-mega-menu';
	 	}elseif (isset($options['element-style']) && !empty($options['element-style'])) {
	 		if($options['element-style'] == 'style1') $menu_style .= 'ts-standard-menu';
	 			elseif($options['element-style'] == 'style2') $menu_style .= 'ts-vertical-menu';
	 				else $menu_style .= 'ts-standard-menu';
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
				'fallback_cb'     => 'menuCallback',
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
				'menu_id'         => 'menu-main-header',
				'echo'            => true,
				'fallback_cb'     => 'menuCallback',
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

		}else {

			ob_start();
			menuCallback();

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
		$menu_styles = '
			<style>
				.ts-custom-menu-colors.'.$menu_id.' .main-menu > .menu-item-has-children ul li > a:before{
					background-color: '. @$options["submenu-bg-color-hover"].';
				}
				.ts-custom-menu-colors.'.$menu_id.'{
					background-color: '. @$options["menu-bg-color"] .';
				}
				.ts-custom-menu-colors.'.$menu_id.' .main-menu > li > a{
					color: '. @$options["menu-text-color"] .';
				}
				.ts-custom-menu-colors.'.$menu_id.' .main-menu > li > a:hover{
					background-color: '. @$options["menu-bg-color-hover"] .';
					color: '.@$options["menu-text-color-hover"].';
				}
				.ts-custom-menu-colors.'.$menu_id.' li li a{
					color: '. @$options["submenu-text-color"] .';
				}
				.ts-custom-menu-colors.'.$menu_id.' li ul li a:not(.view-more):hover{
					color: '. @$options["submenu-text-color-hover"] .';
				}
				.ts-custom-menu-colors.'.$menu_id.' .sub-menu{
	                background-color: '. @$options["submenu-bg-color"].';
	            }
	            .ts-custom-menu-colors .title,
	            .ts-custom-menu-colors .main-menu .ts_is_mega_div .title{
	            	color: '. @$options['menu-text-color'] .';
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
	            }
	            .ts-mobile-menu .title,
	            .ts-mobile-menu .main-menu .ts_is_mega_div .title{
	            	color: '. @$options['menu-text-color'] .';
	            }
			</style>
		';
		if( @$options['menu-custom'] == 'no' ){
			$menu_styles = '';
		}


		return '<div class="col-lg-12 col-md-12 col-sm-12">' .
				$menu
				.'<div id="ts-mobile-menu" class="ts-mobile-menu '.$mobile_menu_id.' '. $custom_menu_class.' ">
					<div class="mobile_header nav-header">
						<a href="#" data-toggle="mobile_menu" class="trigger">
							<i class="icon-menu"></i>
						</a>
					</div>'.
					$mobile_menu .
				'</div>
			   </div>'.$menu_styles;
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
			'iconed icon-close'
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
		} else{
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
			'simplecenter'
		);
		$options['style'] = (in_array($options['style'], $styles))
					? $options['style'] : 'simpleleft';

		$sizes = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );

		$options['size'] = (in_array($options['size'], $sizes))
					? $options['size'] : 'h1';

		if( $options['title'] !== '' && $options['style'] !== 'lineariconcenter' ){
			$title = '<'.$options['size'].' class="the-title" style="color: '.$options['title-color'].'"><i class="' . @$options['title-icon'] . '"></i>'.stripslashes($options['title']).'</'.$options['size'].'>';
		}else{
			$title = '';
		}
		$description = '';
		if( $options['subtitle'] !== '' && $options['style'] !== 'lineariconcenter' ){
			$description = '<span class="block-title-description" style="color: '.$options['subtitle-color'].'">'.stripslashes($options['subtitle']).'</span>';
		}elseif( $options['subtitle'] !== '' && $options['style'] == 'lineariconcenter' ){
			$description = '<span class="block-title-description" style="color: '.$options['subtitle-color'].'">'.stripslashes($options['subtitle']).'</span>';
		}else{
			$description = '';
		}

		if( $options['title'] !== '' && $options['style'] == 'lineariconcenter' ){
			$additional = '<'.$options['size'].' class="the-title" style="color: '.$options['title-color'].'">' . stripslashes($options['title']) . '</'.$options['size'].'>' . $description . '<i class="' . @$options['title-icon'] . '"></i>';
		}else{
			$additional = '';
		}

		return
			'<div class="col-lg-12"><div class="block-title block-title-'.$options['style'].'">
				<div class="block-title-container">
					' . $title . $description . $additional . '
				</div>
			</div></div>';
	}

	public static function social_buttons_element($options = array())
	{
		$elements = array();
		$social_options = get_option( 'videotouch_social' );
		$social_align_text = ( isset($options['social-align']) ) ? $options['social-align'] : '';

		if( isset($social_options['social_new']) && is_array($social_options['social_new']) && !empty($social_options['social_new']) ){
			foreach ($social_options['social_new'] as $key=>$setting_social) {

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
				$elements[] = '<li><a href="'. esc_url($social_options['instagram']) .'" class="icon-instagram" target="_blank"></a></li>';
			}
		}

		$elements = trim(implode("\n", $elements));

		if ($elements) {
			return '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="social-icons">
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
			'prev_text'    => __( '&larr; Previous', 'videotouch' ),
			'next_text'    => __( 'Next &rarr;', 'videotouch' ),
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
		$category = (isset($options['category']) && !empty($options['category']) && is_string($options['category'])) ? explode(',', $options['category']) : NULL;

		if( !isset($category) ) return false;

		if ( get_query_var('paged') ) {
		    $current = get_query_var('paged');
		} elseif ( get_query_var('page') ) {
		    $current = get_query_var('page');
		} else {
		    $current = 1;
		}

		$args = array(
			'post_type' => 'portfolio',
			'paged' => $current,
			'tax_query' => array(
		        array(
		            'taxonomy' => 'portfolio_register_post_type',
		            'field' => 'id',
		            'terms' => $category
		        )
		    ),
			'posts_per_page' => (int)$options['posts-limit'],
			'orderby' => $options['order-direction'],
			'order' => (int)$options['order-by']
		);

		if( $options['order-by'] === 'comments' ){
			$args['orderby'] = 'comment_count';
		}

		if( $options['order-by'] === 'views' ){
			$args['meta_key'] = 'ts_article_views';
			$args['orderby']  = 'meta_value_num';
		}

		$options['args'] = $args;

		$query = new WP_Query($args);

		return self::last_posts_element($options, $query);
	}

	public static function searchbox_element($options = array())
	{
		return '<div class="col-lg-12 col-md-12 col-sm-12">
					<div id="searchbox">
						<form role="search" method="get" class="search-form" action="' . home_url( '/' ) . '">
							<fieldset>
								<input class="input" name="s" type="text" id="keywords" value="'.__( 'some text...', 'touchsize' ).'" onfocus="if (this.value == \''.__( 'some text...', 'touchsize' ).'\') {this.value = \'\';}" onblur="if (this.value == \'\') {this.value = \''.__( 'some text...', 'touchsize' ).'\';}" />
								<input type="submit" class="searchbutton" name="search" value="'.__( 'Search', 'touchsize' ).'" />
								<i class="icon-search"></i>
							</fieldset>
						</form>
					</div>
				</div>';
	}

	public static function teams_element($options = array())
	{

		$teams = array();
		$elements_per_row = (isset($options['elements-per-row'])) ? (int)$options['elements-per-row'] : '';
		$posts_limit = (isset($options['posts-limit'])) ? (int)$options['posts-limit'] : '';
		$remove_gutter = (isset($options['remove-gutter'])) ? strip_tags($options['remove-gutter']) : '';
		$category = (isset($options['category'])) ? explode(',', $options['category']) : '';

		$args = array(
			'post_type' => 'ts_teams',
			'posts_per_page' => $posts_limit,
			'orderby' => 'DATE',
			'order' => 'desc'
		);

		if( isset($category) ){
			$args['tax_query'] = array(
		        array(
		            'taxonomy' => 'teams',
		            'field' => 'id',
		            'terms' => $category
		        )
		    );
		}

		$query = new WP_Query( $args );

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
			return __('No Results', 'touchsize');
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

			$carousel_container_start = '<div class="carousel-container">';
			$carousel_container_end = '</div>';

			$carousel_navigation = '<ul class="carousel-nav">
				                        <li class="carousel-nav-left icon-left"></li>
				                        <li class="carousel-nav-right icon-right"></li>
					                </ul>';
		} else{
			$carousel_wrapper_start = '';
			$carousel_wrapper_end = '';
			$carousel_container_start = '';
			$carousel_container_end = '';
			$carousel_navigation = '';
		}
		return '<section class="teams ' . $gutter_class . ' cols-by-' . $elements_per_row . '">'. $carousel_wrapper_start . $carousel_navigation . $carousel_container_start . $elements . $carousel_container_end . $carousel_wrapper_end .'</section>';
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
		            'field'    => 'id',
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
			return __('No Results', 'touchsize');
		}

		/* Restore original Post Data */
		wp_reset_postdata();
		if( $remove_gutter == 'yes' ){
			$gutter_class = ' no-gutter ';
		} else{
			$gutter_class = '';
		}
		return '<section class="ts-pricing-view ' . $gutter_class . ' cols-by-' . $elements_per_row . '">'. $elements . '</section>';
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
					$img_url = esc_url(str_replace('--quote--', '"', ts_resize('features', $option_element->image)));
					$author_img = '<img class="author-img" src="' . $img_url . '" alt="' . esc_attr(str_replace('--quote--', '"', $option_element->name)) . '" />';
				} else {
					$url_image = get_template_directory_uri() . '/images/noimage.jpg';
					$img_url = esc_url(ts_resize('features', $url_image));
					$author_img = '<img class="author-img" src="' . $img_url . '" alt="' . esc_attr(str_replace('--quote--', '"', $option_element->name)) . '" />';
				}

				$testimonials[] = 	'<div class="testimonial-item '. $elements_per_row .'">
										<article>
											<header>
												<i class="icon-quote"></i>
												<div class="author-text">'. nl2br(str_replace('--quote--', '"', $option_element->text)) .'</div>
											</header>
											<section>
												<div class="testimonial-image">
													'. $author_img .'
												</div>
												<div class="author-name"><a target="_blank" href="'. $option_element->url .'">'. str_replace('--quote--', '"', $option_element->name) .'</a></div>
												<div class="author-position">'. str_replace('--quote--', '"', $option_element->company). '</div>
											</section>
										</article>
									</div>';
			}
		}

		if( $enable_carousel == 'Y' ){

			$carousel_wrapper_start   = '<div class="carousel-wrapper">';
			$carousel_wrapper_end     = '</div>';

			$carousel_container_start = '<div class="carousel-container">';
			$carousel_container_end   = '</div>';

			$carousel_navigation      = '<ul class="carousel-nav">
					                        <li class="carousel-nav-left icon-left"></li>
					                        <li class="carousel-nav-right icon-right"></li>
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

	public static function slider_element($options = array())
	{
		$args = array(
			'post_type' => 'ts_slider',
			'posts_per_page' => 1,
			'p' => $options['slider-id']
		);

		$query = new WP_Query( $args );
		$slider_content = '';
		$slider_id = 'header-carousel-id-' . rand(321,32321321);

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {

				$query->the_post();
				$meta = get_post_meta( get_the_ID(), 'ts_slides', true);
				$slider_type = get_post_meta( get_the_ID(), 'slider_type', true);

				tsIncludeScripts(array('modernizr.custom2', $slider_type));

				if ( $slider_type === 'flexslider' ) {

					$slider_content = '
					<div class="red-slider flexslider" data-animation="slide">
					<ul class="slides">';
					$slides_container = '';

					foreach ($meta as $slide) {

						$img_url = ts_resize('slider', $slide['slide_url']);
						$slides_container .= '<li>';
						$slides_container .= '<img src="'. esc_url($img_url) .'" alt="' . esc_attr($slide['slide_title']) . '" />';

						if ( $slide['slide_title'] !== '' && $slide['slide_description'] !== '' ) {
							$slides_container .= '<div class="slider-caption ' . $slide['slide_position'] . '">';
							$slides_container .= '<h3><a href="' . esc_url($slide['redirect_to_url']) . '">' . $slide['slide_title'] . '</a></h3>';
							$slides_container .= nl2br($slide['slide_description']);
							$slides_container .= '</div>';
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

						$img_url = ts_resize('slider', $slide['slide_url']);
						$slides_container .= '<li>';
						$slides_container .= '<img src="'. esc_url($img_url) .'" alt="' . esc_attr($slide['slide_title']) . '" />';

						if ( $slide['slide_title'] !== '' && $slide['slide_description'] !== '' ) {

							$slides_container .= '<div class="slider-caption ' . $slide['slide_position'] . '">';
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
				}
			}
		}

		/* Restore original Post Data */
		wp_reset_postdata();

		return '<div class="col-lg-12 col-md-12 col-sm-12">' . $slider_content . '</div>';
	}

	public static function last_posts_element($options = array(), &$original_query = null)
	{
		$exclude_posts  = ( isset($options['id-exclude']) && $options['id-exclude'] != '' ) ? explode(',',@$options['id-exclude']) : NULL;
		$exclude_first  = ( isset($options['exclude-first']) ) ? (int)$options['exclude-first'] : '';
		$exclude_id     = array();
		$featured       = (isset($options['featured']) && $options['featured'] !== '' && ($options['featured'] == 'y' || $options['featured'] == 'n')) ? $options['featured'] : 'n';
		$categories     = (isset($options['category']) && !empty($options['category']) && is_string($options['category'])) ? explode(',', $options['category']) : '';
		$pagination     = (isset($options['pagination']) && ($options['pagination'] === 'n' || $options['pagination'] === 'y' || $options['pagination'] ==='load-more')) ? $options['pagination'] : 'n';
		$ajax_load_more = (isset($options['ajax-load-more']) && $options['ajax-load-more'] === true) ? true : false;
		$pagination_content = '';

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

			$ts_masonry_class = (isset($options['behavior']) && $options['behavior'] == 'masonry') ? ' ts-filters-container ' : '';

			$grid_view_start = '<section class="ts-grid-view ' . $display_effect . $ts_masonry_class . ' ' . self::get_clear_class($options['elements-per-row']) . ' ">';

			$grid_view_end = '</section>';

			$carousel_wrapper_start = '<div class="carousel-wrapper">';
			$carousel_wrapper_end = '</div>';

			$carousel_container_start = '<div class="carousel-container">';
			$carousel_container_end = '</div>';

			$carousel_navigation = '<ul class="carousel-nav">
				                        <li class="carousel-nav-left icon-left"></li>
				                        <li class="carousel-nav-right icon-right"></li>
					                </ul>';

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
					'posts_per_page' => (int)$options['posts-limit']
				);

				if( $pagination === 'n' || $pagination === 'load-more' ){
					$args['offset'] = $exclude_first;
				}

				if ( !empty($categories) && is_array($categories) ) {

					$options['category'] = explode(',', $options['category']);

					if ($options['category']) {
						$args['category__in'] = $options['category'];
					} else {
						$args['category__in'] = array(0);
					}

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
					global $ts_list_query;
					$ts_list_query = $query;
					get_template_part('template-pagination');
					$pagination_content = ob_get_clean();
				}
				wp_reset_postdata();

			} else {
				return $grid_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . __( 'Nothing Found', 'touchsize' ) . '</div>' . $grid_view_end;
			}
			$args['options'] = $options;
			$load_more = ($pagination === 'load-more' && $ajax_load_more === false) ? '<div class="ts-pagination-more" data-loop="1" data-args="' . ts_base_64($args, 'encode') . '">' . __('Load More', 'touchsize') . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

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
				return  $grid_view_start . $elements . $grid_view_end . $next_prev_links . $pagination_content . $load_more;
			}

		} elseif ($options['display-mode'] === 'list') {

			$list_view_start = '<section class="ts-list-view ' . $display_effect . ' "><div class="col-lg-12">';
			$list_view_end = '</div></section>';

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

				if( $pagination === 'n' || $pagination === 'load-more' ){
					$args['offset'] = $exclude_first;
				}

				if ( isset($options['category']) && (int)$options['category'] !== 0 ) {

					$options['category'] = explode(',', $options['category']);

					if ($options['category']) {

						$args['category__in'] = $options['category'];

					} else {
						$args['category__in'] = array(0);
					}

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
					global $ts_list_query;
					$ts_list_query = $query;
					get_template_part('template-pagination');
					$pagination_content = ob_get_clean();
				}

				wp_reset_postdata();

			} else {
				return $list_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . __( 'Nothing Found', 'touchsize' ) . '</div>' . $list_view_end;
			}

			$args['options'] = $options;
			$load_more = ($pagination === 'load-more' && $ajax_load_more === false) ? '<div class="ts-pagination-more" data-loop="1" data-args="' . ts_base_64($args, 'encode') . '">' . __('Load More', 'touchsize') . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

			if ( $original_query === null ) {
				$next_prev_links = '';
			} else {
				$next_prev_links = self::archive_navigation();
			}
			if( $ajax_load_more === true ){
				return $elements;
			}else{
				return $list_view_start . $elements . $list_view_end . $next_prev_links . $pagination_content . $load_more;
			}

		} elseif ($options['display-mode'] === 'thumbnails') {

			$use_gutter = '';
			$author = (isset($options['author'])) ? $options['author'] : '';

			if( isset($options['gutter']) ){
				if( $options['gutter'] === 'y' ){
					$use_gutter = ' no-gutter';
				}
			}

			$ts_masonry_class = '';
			if ( isset($options['behavior']) && @$options['behavior'] == 'masonry' ) {
				$ts_masonry_class = ' ts-filters-container ';
			}

			$thumbnails_view_start = '<section class="ts-thumbnail-view ' . $display_effect . $use_gutter . $ts_masonry_class . ' ' . self::get_clear_class($options['elements-per-row']) . ' ">';
			$thumbnails_view_end = '</section>';

			$carousel_navigation = '<ul class="carousel-nav">
				                        <li class="carousel-nav-left icon-left"></li>
				                        <li class="carousel-nav-right icon-right"></li>
					                </ul>';

			$carousel_wrapper_start = '<div class="carousel-wrapper">';
			$carousel_wrapper_end = '</div>';

			$carousel_container_start = '<div class="carousel-container">';
			$carousel_container_end = '</div>';

			$valid_columns = array(1, 2, 3, 4, 6);

			if ( ! in_array($options['elements-per-row'], $valid_columns)) {
				$options['elements-per-row'] = 3;
			}

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

				if( $pagination === 'n' || $pagination === 'load-more' ){
					$args['offset'] = $exclude_first;
				}

				if ( isset($options['category']) && (int)$options['category'] !== 0 ) {

					$options['category'] = explode(',', $options['category']);

					if ($options['category']) {

						$args['category__in'] = $options['category'];

					} else {
						$args['category__in'] = array(0);
					}

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
					global $ts_list_query;
					$ts_list_query = $query;
					get_template_part('template-pagination');
					$pagination_content = ob_get_clean();
				}

				wp_reset_postdata();

			} else {
				return $thumbnails_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . __( 'Nothing Found', 'touchsize' ) . '</div>' . $thumbnails_view_end;
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
			$load_more = ($pagination === 'load-more' && $ajax_load_more === false) ? '<div class="ts-pagination-more" data-loop="1" data-args="' . ts_base_64($args, 'encode') . '">' . __('Load More', 'touchsize') . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

			if (@$options['behavior'] === 'carousel') {
				return $thumbnails_view_start . $carousel_wrapper_start . $carousel_navigation . $carousel_container_start . $elements . $carousel_container_end. $carousel_wrapper_end . $thumbnails_view_end;
			} else if( @$options['behavior'] === 'scroll' ) {
				return $thumbnails_view_start . '<div class="scroll-view"><div class="row">' . $elements .'</div></div>' . $thumbnails_view_end . $next_prev_links . $pagination_content;
			}else if( $ajax_load_more === true ){
				return $elements;
			}else{
				return $thumbnails_view_start . $elements . $thumbnails_view_end . $next_prev_links . $pagination_content . $load_more;
			}

		} elseif ($options['display-mode'] === 'big-post') {

			$big_post_view_start = '<section class="ts-big-posts ' . $display_effect . ' ">';
			$big_post_view_end   = '</section>';

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
					'posts_per_page' => (int)$options['posts-limit'],
					'paged' => $current
				);

				if( $pagination === 'n' || $pagination === 'load-more' ){
					$args['offset'] = $exclude_first;
				}

				if ( isset($options['category']) && (int)$options['category'] !== 0 ) {

					$options['category'] = explode(',', $options['category']);

					if ($options['category']) {

						$args['category__in'] = $options['category'];

					} else {
						$args['category__in'] = array(0);
					}

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
					global $ts_list_query;
					$ts_list_query = $query;
					get_template_part('template-pagination');
					$pagination_content = ob_get_clean();
				}

				wp_reset_postdata();

			} else {
				return $big_post_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . __( 'Nothing Found', 'touchsize' ) . '</div>' . $big_post_view_end;
			}

			if ( $original_query === null ) {
				$next_prev_links = '';
			} else {
				$next_prev_links = self::archive_navigation();
			}

			$args['options'] = $options;
			$load_more = ($pagination === 'load-more' && $ajax_load_more === false) ? '<div class="ts-pagination-more" data-loop="1" data-args="' . ts_base_64($args, 'encode') . '">' . __('Load More', 'touchsize') . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

			if( $ajax_load_more === true ){
				return $elements;
			}else{
				return $big_post_view_start . $elements .  $next_prev_links . $big_post_view_end. $pagination_content . $load_more;
			}

		} elseif ($options['display-mode'] == 'super-post') {

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

				if( $pagination === 'n' || $pagination === 'load-more' ){
					$args['offset'] = $exclude_first;
				}

				if ( isset($options['category']) && (int)$options['category'] !== 0 ) {

					$options['category'] = explode(',', $options['category']);

					if ($options['category']) {

						$args['category__in'] = $options['category'];

					} else {
						$args['category__in'] = array(0);
					}

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
					global $ts_list_query;
					$ts_list_query = $query;
					get_template_part('template-pagination');
					$pagination_content = ob_get_clean();
				}

				wp_reset_postdata();

			} else {
				return $super_post_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . __( 'Nothing Found', 'touchsize' ) . '</div>' . $super_post_view_end;
			}

			if ( $original_query === null ) {
				$next_prev_links = '';
			} else {
				$next_prev_links = self::archive_navigation();
			}

			$args['options'] = $options;
			$load_more = ($pagination === 'load-more' && $ajax_load_more === false) ? '<div class="ts-pagination-more" data-loop="1" data-args="' . ts_base_64($args, 'encode') . '">' . __('Load More', 'touchsize') . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

			if( $ajax_load_more === true ){
				return $elements;
			}else{
				return $super_post_view_start . $elements . $super_post_view_end . $next_prev_links . $pagination_content . $load_more;
			}

		} elseif ($options['display-mode'] === 'timeline') {

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

				if( $pagination === 'n' || $pagination === 'load-more' ){
					$args['offset'] = $exclude_first;
				}

				if ( isset($options['category']) && (int)$options['category'] !== 0 ) {

					$options['category'] = explode(',', $options['category']);

					if ($options['category']) {

						$args['category__in'] = $options['category'];

					} else {
						$args['category__in'] = array(0);
					}

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
					global $ts_list_query;
					$ts_list_query = $query;
					get_template_part('template-pagination');
					$pagination_content = ob_get_clean();
				}

				wp_reset_postdata();

			} else {
				return $timeline_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . __( 'Nothing Found', 'touchsize' ) . '</div>' . $list_view_end;
			}

			if ( $original_query === null ) {
				$next_prev_links = '';
			} else {
				$next_prev_links = self::archive_navigation();
			}

			if( !isset($args) && isset($options['args']) ){
				$args = $options['args'];
				unset($options['args']);
			}
			$args['options'] = $options;
			$load_more = ($pagination === 'load-more' && $ajax_load_more === false) ? '<div class="ts-pagination-more" data-loop="1" data-args="' . ts_base_64($args, 'encode') . '">' . __('Load More', 'touchsize') . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

			if( $ajax_load_more === true ){
				return $elements;
			}else{
				return '<div class="row"><section class="ts-timeline-view" data-alignment="left">' . $elements . '</section>' . $load_more . '</div>' . $next_prev_links . $pagination_content;
			}

		} elseif ($options['display-mode'] === 'mosaic') {

			$effect_scroll = (isset($options['effects-scroll']) && $options['effects-scroll'] !== '' && $options['effects-scroll'] == 'default') ? '' : ' fade-effect';

			$gutter_class = (isset($options['gutter']) && $options['gutter'] !== '' && $options['gutter'] == 'y') ? ' mosaic-with-gutter ' : ' mosaic-no-gutter';

			$scroll = (isset($options['scroll']) && !empty($options['scroll']) && $options['scroll'] == 'y') ? '<div data-scroll="true" class="mosaic-view'. $effect_scroll . $gutter_class . ' mosaic-' . $options['layout'] . '">' : '<div data-scroll="false" class="mosaic-view'. $gutter_class .' mosaic-' . $options['layout'] . '">';
			$img_rows = (isset($options['rows']) && $options['rows'] !== '' && (int)$options['rows'] !== 0) ? (int)$options['rows'] : '3';
			$layout_mosaic = (isset($options['layout']) && ($options['layout'] == 'rectangles' || $options['layout'] == 'square')) ? $options['layout'] : 'square';

			if ( $original_query === null ) {

				$order    = self::order_direction($options['order-direction']);

				$args = array(
					'post_type' => 'post',
					'posts_per_page' => (int)$options['posts-limit'],
					'order' => $order,
					'post__not_in' => $exclude_id,
					'offset' => $exclude_first

				);

				if ( isset($options['category']) && (int)$options['category'] !== 0 ) {

					$options['category'] = explode(',', $options['category']);

					if ($options['category']) {

						$args['category__in'] = $options['category'];

					} else {
						$args['category__in'] = array(0);
					}

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

				$article_options['i'] = 1;
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

				wp_reset_postdata();

			} else {
				return '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . __( 'Nothing Found', 'touchsize' ) . '</div>';
			}

			if ( $original_query === null ) {
				$next_prev_links = '';
			} else {
				$next_prev_links = self::archive_navigation();
			}

			return $scroll . $elements . $next_prev_links . '</div>';

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
		return '<div class="col-lg-12"><div class="ad-container">' . $options['advertising'] . '</div></div>';
	}

	public static function empty_element($options = array())
	{
		return '&nbsp;';
	}

	public static function video_element($options = array())
	{
		return '<div class="col-lg-12"><div class="embedded_videos">' . $options['embed'] . '</div></div>';
	}

	public static function image_element($options = array())
	{

		$ts_image_element = '';
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

        $styleRetina = ($retina === 'y' && isset($width)) ? 'style="width: '. $width / 2 .'px;"' : '';

		if( isset($forward_url) ){
			$ts_image_element = '<div style="text-align:' . $align . '" class="col-lg-12"><a target="' . $options['image-target'] . '" href="' . $forward_url . '"><img '. $styleRetina .' alt="" src="' . $image_url . '" /></a></div>';

		}else $ts_image_element = '<div style="text-align:' . $align . '" class="col-lg-12"><img '. $styleRetina .' alt="" src="' . $image_url . '" /></a></div>';

		return $ts_image_element;
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

		$options['categories'] = trim($options['categories']);
		$options['categories'] = (!empty( $options['categories'])) ? explode( ',', $options['categories'] ) : array();

		$elements = array();

		if ( $options['categories'] ) {

			$categories_start = '<ul class="ts-filters">';
			$categories_end   = '</ul>';
			$category_list    = '<li><a href="#" data-filter="*">'.__('Show all', 'touchsize').'</a></li>';

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
				case 'posts':
					$post_type = 'post';
					$taxonomy = 'category';
					break;

				case 'portfolio':
					$post_type = 'portfolio';
					$taxonomy = 'portfolio_register_post_type';
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

			foreach ($options['categories'] as $id => $category) {

				$category = get_term_by('id', $category, $taxonomy);
				if ( $category ) {
					$category_name = esc_attr($category->name);
					$category_list .= '<li><a href="#" data-filter=".ts-category-' . $category->term_id . '">' . $category_name . '</a></li>';
				}
			}

			$order = self::order_direction($options['direction']);

			ob_start();
			ob_clean();

			$args = array(
				'post_type' => $post_type,
				'tax_query' => array(
			        array(
			            'taxonomy' => $taxonomy,
			            'field'    => 'id',
			            'terms'    => $options['categories']
			        )
			    ),
				'posts_per_page' => (int)$options['posts-limit'],
				'order' => $order
			);

			$args = self::order_by($options['order-by'], $args);

			$query = new WP_Query( $args );

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
				return __('No posts found', 'touchsize');
			} else {
				return $categories_start . $category_list . $categories_end . '<section class="ts-thumbnail-view ts-filters-container ' . $display_effect . $use_gutter . '">' . $elements . '</section>';
			}

		} else {
			return __('Please select categories for filters element in the page builder', 'touchsize');
		}
	}

	public static function facebook_block_element($options = array()){

		$facebook_background = ( isset($options['facebook-background']) && $options['facebook-background'] != '' ) ? $options['facebook-background'] : '';

		$facebook_class = ( $facebook_background == 'dark' ) ? 'dark_facebook' : '';

		if ( isset($options['facebook-url']) && $options['facebook-url'] != '' ) {

			return 	'<div class="col-lg-12 col-md-12">
						<div class="'. $facebook_class .'">
							<div class="fb-like-box" data-href="' . strip_tags($options['facebook-url']) . '" data-colorscheme="'. $facebook_background .'" data-show-faces="true" data-header="true" data-stream="false" data-show-border="true">
							</div>
						</div>
					</div>';
		}

	}

	public static function clients_element($options = array())
	{

		if ( $options['clients'] != '[]' ) {

			$ts_options = json_decode(stripslashes($options['clients']));
			$columns_class = LayoutCompilator::get_column_class($options['elements-per-row']);
			$img = '';
			$data_tooltip = '';

			if( $options['enable-carousel'] === 'y' ){
	            $carousel_wrapper_start = '<div class="carousel-wrapper">';
	            $carousel_wrapper_end = '</div>';

	            $carousel_container_start = '<div class="carousel-container">';
	            $carousel_container_end = '</div>';

	            $carousel_navigation = '<ul class="carousel-nav">
	                                        <li class="carousel-nav-left icon-left"></li>
	                                        <li class="carousel-nav-right icon-right"></li>
	                                    </ul>';
            } else{
                $carousel_wrapper_start = '';
                $carousel_wrapper_end = '';
                $carousel_container_start = '';
                $carousel_container_end = '';
                $carousel_navigation = '';
            }

			foreach($ts_options as $ts_option){

				$data_tooltip = ( isset($ts_option->title) && $ts_option->title != '' ) ? 'class="has-tooltip" data-tooltip="'.str_replace('--quote--', '"', $ts_option->title).'"' : '';

				if( isset($ts_option->url) && isset($ts_option->image) && $ts_option->url !== '' && $ts_option->image !== '' ){

					$image_url = esc_url(str_replace('--quote--', '"', $ts_option->image));
					$img .= '<div class="item ' . $columns_class . '">
								<div '. $data_tooltip .'><a target="_blank" href="'. esc_url(str_replace('--quote--', '"', $ts_option->url)) .'"><img src="'. $image_url .'"></a></div>
							</div>' ;
				}else if( !isset($ts_option->url) || $ts_option->url == '' && (isset($ts_option->image) && $ts_option->image !== '') ){
					$image_url = esc_url(str_replace('--quote--', '"', $ts_option->image));
					$img .= '<div class="item ' . $columns_class . '">
								<div '. $data_tooltip .'><img '. $data_tooltip .' src="'. $image_url .'"></div>
							</div>' ;
				}else if( !isset($ts_option->image) || $ts_option->image == '' && (isset($ts_option->url) && $ts_option->url !== '') ){
					$image_url = get_template_directory_uri().'/images/noimage.jpg';
					$img .= '<div class="item ' . $columns_class . '">
								<div '. $data_tooltip .'><a target="_blank" href="'. esc_url(str_replace('--quote--', '"', $ts_option->url)) .'"><img src="'. $image_url .'"></a></div>
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
			return __("No clients found", "touchsize");
		}

	}

	public static function feature_blocks_element($options = array())
	{

		$elements = array();
		$style = '';
		$gutter = (isset($options['gutter']) && $options['gutter'] !== '' && ($options['gutter'] == 'gutter' || $options['gutter'] == 'no-gutter')) ? $options['gutter'] : 'gutter';

		ob_start();
		ob_clean();
			global $article_options;
			$article_options = $options;
			get_template_part('includes/layout-builder/templates/feature');
		$elements = ob_get_clean();

		if ( $options['style'] == 'style1' ) {
			$style = 'ts-features-default';
		} else {
			$style = 'ts-features-fullbg';
		}
		$style .= ' cols-by-' . $options['elements-per-row'] . ' ';

		if ( $elements ) {
			return '<section class="' . $style . $gutter . '">' . $elements . '</section>';
		} else {
			return __("No posts found", "touchsize");
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
				return __("No posts found", "touchsize");
			}
		}

	}

	public static function spacer_element($options = array())
	{
		$height = (isset($options['height']) && (int)$options['height'] !== 0) ? (int)$options['height'] : '20';
		$mobile = (isset($options['mobile']) && ($options['mobile'] === 'y' || $options['mobile'] === 'n')) ? $options['mobile'] : 'n';

		return '<div data-element="spacer" data-show-mobile="'.$mobile.'" style="height: ' . $height . 'px;"></div>';
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
			return '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: '.esc_attr($icon_align).';">' . '<i class="' . $icon_name . '" ' . $icon_styles . '></i>' . '</div>';
		}

	}

	public static function counter_element($options = array()){

		$counter = '<div class="ts-counters" style="color: '. $options['counters-text-color'] .'">
						<article>
							<div class="entry-box">
								<div class="chart" data-percent="'. $options['counters-precents'] .'">
									<span class="percent"></span>
								</div>
							</div>
							<div class="entry-title"><h5 class="the-title">'. $options['counters-text'] .'</h5></div>
						</article>
					</div>';

		return $counter;

	}

	public static function map_element($options = array()){

		$code_google = '';

		if( isset($options['map-code']) ){
			$code_google = $options['map-code'];
		}

		return '<div class="col-lg-12 col-md-12 col-sm-12">' . $code_google . '</div>';
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
		ob_start();
		dynamic_sidebar( @(string)$options['sidebar-id'] );
		$sidebar = ob_get_contents();
		ob_end_clean();
		return '<div class="col-lg-12 col-md-12 col-sm-12">' . $sidebar . '</div>';
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

		$categories = explode(',', $options['selected-categories']);
		$exclude_first = ( isset($options['exclude-first']) && (int)$options['exclude-first'] !== 0 ) ? (int)$options['exclude-first'] : NULL;
		$posts_per_page = (isset($options['number-posts']) && (int)$options['number-posts'] !== 0 && $options['number-posts'] !== '') ? (int)$options['number-posts'] : '4';
		$custom_post = (isset($options['custom-post']) && $options['custom-post'] !== '') ? $options['custom-post'] : 'post';
		if ( empty($categories) ) {
			$categories = array(0);
		}

		$args = array(
			'posts_per_page' => $posts_per_page,
			'orderby' => 'date',
			'order' => 'DESC'
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
						'field' => 'id',
						'terms' => $categories,
						'operator' => 'IN',
					)
				);
		}

		if( $custom_post == 'video_post' ){
			$args['post_type'] = array('post','video');
		}

		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			ob_start();
			ob_clean();
			global $article_options, $posts_query;
			$article_options = $options;
			$posts_query = $query;
			get_template_part('includes/layout-builder/templates/featured-area');
			$element = ob_get_clean();
			wp_reset_postdata();

		} else {
			wp_reset_postdata();
			return '';
		}

		/* Restore original Post Data */
		wp_reset_postdata();


		return '<div class="col-lg-12 col-md-12 col-sm-12">
					<section class="post-slider">
						<div class="row">'
								.$element.
							'</div>
					</section>
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

		if ( isset( $options['button-icon'] ) && $options['button-icon'] !== 'icon-noicon' ) {
			$button_class .= ' button-has-icon ' . @$options['button-icon'];
		}

		$options['url'] = esc_url($options['url']);

		$colors = '';

		if ( $options['mode-display'] == 'background-button' ) {
			$colors = 'style="background-color: ' . $background_color . '; color: '.$text_color.';"';
			$button_class .= ' bg-button ';
		}else{
			$colors = 'style="border-color: ' . $border_color . '; color: ' . $text_color . ';"';
			$button_class .= ' outline-button ';
		}

		if ( ! isset($options['target']) ) {
			$options['target'] = '_blank';
		}

		if ( isset( $options['short'] ) ) {

	       	$start = '';

	       	$end = '';

       	} else {

       		$start = '<div class="col-lg-12 col-md-12 col-sm-12 ' . $button_align . '">';

       		$end = '</div>';

       	}

		return 	$start .
					'<a href="' . esc_url($options['url']) . '" target="' . esc_attr($options['target']) . '" class="ts-button ' . $button_class . '" ' . $colors . '>' .
						stripslashes($options['text']) .
					'</a>' .
				$end;
	}

	public static function shortcodes_element($options = array()) {
		return '<div class="col-lg-12 col-md-12 col-sm-12"><div class="ts-shortcode-element">
			' . do_shortcode($options['shortcodes']) . '
		</div></div>';
	}

	public static function text_element($options = array()) {

		return 	'<div class="col-lg-12 col-md-12 col-sm-12">' .
					do_shortcode( wp_unslash( str_replace( '--quote--', '"', $options['text'] ) ) ) . '
				</div>';
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

			$banner_box = '	<div class="col-lg-12 col-md-12">
								<div data-alignment="middle" class="ts-banner-box ' . $banner_text_align . '" style="background: url('.$banner_img.') no-repeat center top;color: ' . $banner_font_color . ';background-size: cover;height: ' . $banner_img_height . 'px; ">
									<article class="container">
										<h1 class="title">' . $banner_title . '</h1>
										<div class="subtitle">' . $banner_subtitle . '</div>
										<a class="banner-btn" href="' . $banner_button_url . '" style="background-color:' . $banner_button_background . ';">' . $banner_button_title . '</a>
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
			$toggle_description = ( isset($options['toggle-description']) ) ? nl2br($options['toggle-description']) : '';
			$toggle_state = ( isset($options['toggle-state']) ) ? strip_tags($options['toggle-state']) : '';
			$toggle_collapse = $options['toggle-state'] == 'open' ? 'in':'';
			$id_toggle = $randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 7);

			$toggle_box = '	<div class="col-lg-12 col-md-12 col-sm-12">
								<div class="ts-toggle-box ' . $toggle_state . ' panel-group" id="toggle-id-'. $id_toggle .'">
									<div class="panel panel-default">
										<div class="panel-heading toggle-title">
											<a data-toggle="collapse" data-parent="#toggle-id-'. $id_toggle .'" href="#'. $id_toggle .'"><i class="icon-right"></i>' . $toggle_title . '</a>
										</div>
										<div id="'. $id_toggle .'" class="panel-collapse collapse ' . $toggle_collapse . '">
											<div class="panel-body">
											' . do_shortcode( $toggle_description ) . '
											</div>
										</div>
									</div>
								</div>
							</div>
			';

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
		$modal         = (isset($options['modal']) && ($options['modal'] == 'y' || $options['modal'] == 'n')) ? $options['modal'] : '';
		$scroll        = (isset($options['behavior']) && $options['behavior'] === 'scroll') ? ' scroll-view' : '';
		$layout_mosaic = (isset($options['layout']) && ($options['layout'] == 'rectangles' || $options['layout'] == 'square')) ? $options['layout'] : 'square';
		$img_rows      = (isset($options['rows']) && $options['rows'] !== '' && (int)$options['rows'] !== 0) ? (int)$options['rows'] : '3';
		$pagination    = (isset($options['pagination']) && ($options['pagination'] === 'n' || $options['pagination'] === 'y' || $options['pagination'] ==='load-more')) ? $options['pagination'] : 'n';
		$ajax_load_more = (isset($options['ajax-load-more']) && $options['ajax-load-more'] === true) ? true : false;

		if( isset($exclude_posts) ){
			foreach($exclude_posts as $transform_to_integer){
				if( $transform_to_integer != 0 && $transform_to_integer != '' ) $exclude_id[] = (int)$transform_to_integer;
			}
		}

		$pagination_content = '';
		$ts_modal_content = '';

		if( $modal === 'y' ){

			$ts_modal_content = '
				<div class="modal fade ts-videopost-modal" id="modal_video" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">' . __('Close', 'touchsize') . '</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel"></h4>
                            </div>
                        	<div class="modal-body">
                        	</div>
                        	<div class="modal-footer">
                                <a href="#" class="btn medium active" data-dismiss="modal">' . __('Close', 'touchsize') . '</a>
                                <a href="" class="ts-add-link btn medium">' . __('View post', 'touchsize') . '</a>
                        	</div>
                        </div>
                    </div>
                </div>';
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

		if (isset($options['taxonomy'])) {
			$taxonomy = $options['taxonomy'];
		} else {
			$taxonomy = 'category';
		}

		// Display elements for grid mode
		if ($options['display-mode'] === 'grid') {

			$ts_masonry_class = '';
			if ( @$options['behavior'] == 'masonry' ) {
				$ts_masonry_class = ' ts-filters-container ';
			}

			$grid_view_start = '<section class="ts-grid-view ' . $display_effect . $ts_masonry_class . $scroll . ' ' . self::get_clear_class($options['elements-per-row']) . ' ">';
			$grid_view_end = '</section>';

			$carousel_wrapper_start = '<div class="carousel-wrapper">';
			$carousel_wrapper_end = '</div>';

			$carousel_container_start = '<div class="carousel-container">';
			$carousel_container_end = '</div>';

			$carousel_navigation = '<ul class="carousel-nav">
				                        <li class="carousel-nav-left icon-left"></li>
				                        <li class="carousel-nav-right icon-right"></li>
					                </ul>';

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

				if( $pagination === 'load-more' || $pagination === 'n' ){
					$args['offset'] = $exclude_first;
				}

				if ( isset($options['category']) && (int)$options['category'] !== 0 ) {

					$options['category'] = explode(',', $options['category']);

					if ($options['category']) {
						$args['tax_query'] = array(
							        array(
							            'taxonomy' => 'videos_categories',
							            'field' => 'id',
							            'terms' => $options['category']
							        )
							    );
					} else {
						$args['category__in'] = array(0);
					}

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
					global $ts_list_query;
					$ts_list_query = $query;
					get_template_part('template-pagination');
					$pagination_content = ob_get_clean();
				}
				wp_reset_postdata();

			} else {
				return $grid_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . __( 'Nothing Found', 'touchsize' ) . '</div>' . $grid_view_end;
			}

			if ( $original_query === null ) {
				$next_prev_links = '';
			} else {
				$next_prev_links = self::archive_navigation();
			}

			$args['options'] = $options;
			$load_more = ($pagination === 'load-more' && $ajax_load_more === false) ? '<div class="ts-pagination-more" data-loop="1" data-args="' . ts_base_64($args, 'encode') . '">' . __('Load More', 'touchsize') . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

			if (@$options['behavior'] === 'carousel') {
				return $grid_view_start . $carousel_wrapper_start . $carousel_navigation . $carousel_container_start . $elements . $carousel_container_end . $carousel_wrapper_end . $grid_view_end;
			}else if( isset($options['behavior']) && $options['behavior'] === 'scroll' ) {
				return  $grid_view_start . '<div class="row">' . $elements .'</div>'. $grid_view_end . $next_prev_links . $pagination_content;
			}else if( $ajax_load_more === true ){
				return  $elements;
			}else{
				return  $grid_view_start . $elements . $grid_view_end . $load_more . $ts_modal_content . $next_prev_links . $pagination_content;
			}

		} else if ($options['display-mode'] === 'list') {

			$list_view_start = '<section class="ts-list-view ' . $display_effect . ' "><div class="col-lg-12">';
			$list_view_end = '</div></section>';

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

				if( $pagination === 'load-more' || $pagination === 'n' ){
					$args['offset'] = $exclude_first;
				}

				if ( isset($options['category']) && (int)$options['category'] !== 0 ) {

					$options['category'] = explode(',', $options['category']);

					if ($options['category']) {

						$args['tax_query'] = array(
							        array(
							            'taxonomy' => 'videos_categories',
							            'field' => 'id',
							            'terms' => $options['category']
							        )
							    );

					} else {
						$args['category__in'] = array(0);
					}

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
					global $ts_list_query;
					$ts_list_query = $query;
					get_template_part('template-pagination');
					$pagination_content = ob_get_clean();
				}

				wp_reset_postdata();

			} else {
				return $list_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . __( 'Nothing Found', 'touchsize' ) . '</div>' . $list_view_end;
			}

			if ( $original_query === null ) {
				$next_prev_links = '';
			} else {
				$next_prev_links = self::archive_navigation();
			}

			$args['options'] = $options;
			$load_more = ($pagination === 'load-more' && $ajax_load_more === false) ? '<div class="ts-pagination-more" data-loop="1" data-args="' . ts_base_64($args, 'encode') . '">' . __('Load More', 'touchsize') . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

			if( $ajax_load_more === true ){
				return $elements;
			}else{
				return $list_view_start . $elements . $list_view_end . $load_more . $ts_modal_content . $next_prev_links . $pagination_content;
			}


		} else if ($options['display-mode'] === 'thumbnails') {

			$use_gutter = '';

			if( isset($options['gutter']) ){
				if( $options['gutter'] === 'y' ){
					$use_gutter = ' no-gutter';
				}
			}

			$ts_masonry_class = '';
			if ( isset($options['behavior']) && @$options['behavior'] == 'masonry' ) {
				$ts_masonry_class = ' ts-filters-container ';
			}

			$thumbnails_view_start = '<section class="ts-thumbnail-view ' . $display_effect . $scroll . $use_gutter . $ts_masonry_class . ' ' . self::get_clear_class($options['elements-per-row']) . ' ">';
			$thumbnails_view_end = '</section>';

			$carousel_navigation = '<ul class="carousel-nav">
				                        <li class="carousel-nav-left icon-left"></li>
				                        <li class="carousel-nav-right icon-right"></li>
					                </ul>';

			$carousel_wrapper_start = '<div class="carousel-wrapper">';
			$carousel_wrapper_end = '</div>';

			$carousel_container_start = '<div class="carousel-container">';
			$carousel_container_end = '</div>';

			$valid_columns = array(1, 2, 3, 4, 6);

			if ( ! in_array($options['elements-per-row'], $valid_columns)) {
				$options['elements-per-row'] = 3;
			}

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

				if( $pagination === 'load-more' || $pagination === 'n' ){
					$args['offset'] = $exclude_first;
				}

				if ( isset($options['category']) && (int)$options['category'] !== 0 ) {

					$options['category'] = explode(',', $options['category']);

					if ($options['category']) {

						$args['tax_query'] = array(
							        array(
							            'taxonomy' => 'videos_categories',
							            'field' => 'id',
							            'terms' => $options['category']
							        )
							    );

					} else {
						$args['category__in'] = array(0);
					}

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
					global $ts_list_query;
					$ts_list_query = $query;
					get_template_part('template-pagination');
					$pagination_content = ob_get_clean();
				}

				wp_reset_postdata();

			} else {
				return $thumbnails_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . __( 'Nothing Found', 'touchsize' ) . '</div>' . $thumbnails_view_end;
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
			$load_more = ($pagination === 'load-more' && $ajax_load_more === false) ? '<div class="ts-pagination-more" data-loop="1" data-args="' . ts_base_64($args, 'encode') . '">' . __('Load More', 'touchsize') . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

			if (@$options['behavior'] === 'carousel') {
				return $thumbnails_view_start . $carousel_wrapper_start . $carousel_navigation . $carousel_container_start . $elements . $carousel_container_end. $carousel_wrapper_end . $thumbnails_view_end;
			} else if( @$options['behavior'] === 'scroll' ) {
				return $thumbnails_view_start . '<div class="row">' . $elements .'</div>' . $thumbnails_view_end . $next_prev_links . $pagination_content;
			} else if( $ajax_load_more === true ) {
				return $elements;
			} else {
				return $thumbnails_view_start . $elements . $thumbnails_view_end . $load_more . $next_prev_links . $pagination_content;
			}

		} else if ($options['display-mode'] === 'big-post') {

			$big_post_view_start = '<section class="ts-big-posts ' . $display_effect . ' ">';
			$big_post_view_end = '</section>';

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

				if( $pagination === 'load-more' || $pagination === 'n' ){
					$args['offset'] = $exclude_first;
				}

				if ( isset($options['category']) && (int)$options['category'] !== 0 ) {

					$options['category'] = explode(',', $options['category']);

					if ($options['category']) {

						$args['tax_query'] = array(
							        array(
							            'taxonomy' => 'videos_categories',
							            'field' => 'id',
							            'terms' => $options['category']
							        )
							    );

					} else {
						$args['category__in'] = array(0);
					}

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
					global $ts_list_query;
					$ts_list_query = $query;
					get_template_part('template-pagination');
					$pagination_content = ob_get_clean();
				}

				wp_reset_postdata();

			} else {
				return $big_post_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . __( 'Nothing Found', 'touchsize' ) . '</div>' . $big_post_view_end;
			}

			if ( $original_query === null ) {
				$next_prev_links = '';
			} else {
				$next_prev_links = self::archive_navigation();
			}

			$args['options'] = $options;
			$load_more = ($pagination === 'load-more' && $ajax_load_more === false) ? '<div class="ts-pagination-more" data-loop="1" data-args="' . ts_base_64($args, 'encode') . '">' . __('Load More', 'touchsize') . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';
			if( $ajax_load_more === true ){
				return $elements;
			}else{
				return $big_post_view_start . $elements . $big_post_view_end . $load_more . $ts_modal_content . $next_prev_links . $pagination_content;
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

				if( $pagination === 'load-more' || $pagination === 'n' ){
					$args['offset'] = $exclude_first;
				}

				if ( isset($options['category']) && (int)$options['category'] !== 0 ) {

					$options['category'] = explode(',', $options['category']);

					if ($options['category']) {

						$args['tax_query'] = array(
							        array(
							            'taxonomy' => 'videos_categories',
							            'field' => 'id',
							            'terms' => $options['category']
							        )
							    );

					} else {
						$args['category__in'] = array(0);
					}

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
					global $ts_list_query;
					$ts_list_query = $query;
					get_template_part('template-pagination');
					$pagination_content = ob_get_clean();
				}

				wp_reset_postdata();

			} else {
				return $super_post_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . __( 'Nothing Found', 'touchsize' ) . '</div>' . $super_post_view_end;
			}

			if ( $original_query === null ) {
				$next_prev_links = '';
			} else {
				$next_prev_links = self::archive_navigation();
			}

			$args['options'] = $options;
			$load_more = ($pagination === 'load-more' && $ajax_load_more === false) ? '<div class="ts-pagination-more" data-loop="1" data-args="' . ts_base_64($args, 'encode') . '">' . __('Load More', 'touchsize') . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

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
					'post_type' => 'video',
					'order' => $order,
					'paged' => $current,
					'post__not_in' => $exclude_id,
					'posts_per_page' => (int)$options['posts-limit']
				);

				if( $pagination === 'load-more' || $pagination === 'n' ){
					$args['offset'] = $exclude_first;
				}

				if ( isset($options['category']) && (int)$options['category'] !== 0 ) {

					$options['category'] = explode(',', $options['category']);

					if ($options['category']) {

						$args['tax_query'] = array(
					        array(
					            'taxonomy' => 'videos_categories',
					            'field' => 'id',
					            'terms' => $options['category']
					        )
					    );

					} else {
						$args['category__in'] = array(0);
					}

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
					global $ts_list_query;
					$ts_list_query = $query;
					get_template_part('template-pagination');
					$pagination_content = ob_get_clean();
				}

				wp_reset_postdata();

			} else {
				return '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . __( 'Nothing Found', 'touchsize' ) . '</div>';
			}

			if ( $original_query === null ) {
				$next_prev_links = '';
			} else {
				$next_prev_links = self::archive_navigation();
			}

			$args['options'] = $options;
			$load_more = ($pagination === 'load-more' && $ajax_load_more === false) ? '<div class="ts-pagination-more" data-loop="1" data-args="' . ts_base_64($args, 'encode') . '">' . __('Load More', 'touchsize') . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';
			if ( $ajax_load_more === true ) {
				return $elements;
			}else{
				return '<div class="row"><section class="ts-timeline-view" data-alignment="left">' . $elements . '</section>' . $load_more . '</div>' .  $next_prev_links . $pagination_content;
			}

		} else if ($options['display-mode'] === 'mosaic') {

			$effect_scroll = (isset($options['effects-scroll']) && $options['effects-scroll'] !== '' && $options['effects-scroll'] == 'default') ? '' : ' fade-effect';

			$gutter_class = (isset($options['gutter']) && $options['gutter'] !== '' && $options['gutter'] == 'y') ? ' mosaic-with-gutter ' : ' mosaic-no-gutter ';

			$scroll = (isset($options['scroll']) && !empty($options['scroll']) && $options['scroll'] == 'y') ? '<div data-scroll="true" class="mosaic-view '. $effect_scroll . $gutter_class . ' mosaic-' . $options['layout'] . '">' : '<div data-scroll="false" class="mosaic-view '. $gutter_class .' mosaic-' . $options['layout'] . '">';
			$img_rows = (isset($options['rows']) && $options['rows'] !== '' && (int)$options['rows'] !== 0) ? (int)$options['rows'] : '3';
			$layout_mosaic = (isset($options['layout']) && ($options['layout'] == 'rectangles' || $options['layout'] == 'square')) ? $options['layout'] : 'square';

			if ( $original_query === null ) {

				$order    = self::order_direction($options['order-direction']);

				$args = array(
					'post_type' => 'video',
					'posts_per_page' => (int)$options['posts-limit'],
					'order' => $order,
					'post__not_in' => $exclude_id,
					'offset' => $exclude_first

				);

				if ( isset($options['category']) && (int)$options['category'] !== 0 ) {

					$options['category'] = explode(',', $options['category']);

					if ($options['category']) {

						$args['tax_query'] = array(
							        array(
							            'taxonomy' => 'videos_categories',
							            'field' => 'id',
							            'terms' => $options['category']
							        )
							    );

					} else {
						$args['category__in'] = array(0);
					}

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
				$article_options['i'] = 1;
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

				wp_reset_postdata();

			} else {
				return '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . __( 'Nothing Found', 'touchsize' ) . '</div>';
			}

			if ( $original_query === null ) {
				$next_prev_links = '';
			} else {
				$next_prev_links = self::archive_navigation();
			}

			return $scroll . $elements . $next_prev_links . '</div>';

		}

	}

	public static function breadcrumbs_element($options = array()){
		if( isset($options['type']) && $options['type']  === 'breadcrumbs' ){
			return ts_breadcrumbs();
		}
	}

	public static function latest_custom_posts_element($options = array(), &$original_query = null)
		{
		$exclude_posts  = ( isset($options['id-exclude']) && $options['id-exclude'] != '' ) ? explode(',',@$options['id-exclude']) : NULL;
		$exclude_first  = ( isset($options['exclude-first']) ) ? (int)$options['exclude-first'] : '';
		$exclude_id     = array();
		$featured       = (isset($options['featured']) && $options['featured'] !== '' && ($options['featured'] == 'y' || $options['featured'] == 'n')) ? $options['featured'] : 'n';
		$post_types     = (isset($options['post-type']) && !empty($options['post-type']) && is_string($options['post-type'])) ? explode(',', $options['post-type']) : '';
		$pagination     = (isset($options['pagination']) && ($options['pagination'] === 'n' || $options['pagination'] === 'y' || $options['pagination'] ==='load-more')) ? $options['pagination'] : 'n';
		$ajax_load_more = (isset($options['ajax-load-more']) && $options['ajax-load-more'] === true) ? true : false;
		$pagination_content = '';
		$category = (isset($options['category']) && !empty($options['category'])) ? explode(',', $options['category']) : NULL;

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

			$ts_masonry_class = (isset($options['behavior']) && $options['behavior'] == 'masonry') ? ' ts-filters-container ' : '';

			$grid_view_start = '<section class="ts-grid-view ' . $display_effect . $ts_masonry_class . ' ' . self::get_clear_class($options['elements-per-row']) . ' ">';

			$grid_view_end = '</section>';

			$carousel_wrapper_start = '<div class="carousel-wrapper">';
			$carousel_wrapper_end = '</div>';

			$carousel_container_start = '<div class="carousel-container">';
			$carousel_container_end = '</div>';

			$carousel_navigation = '<ul class="carousel-nav">
				                        <li class="carousel-nav-left icon-left"></li>
				                        <li class="carousel-nav-right icon-right"></li>
					                </ul>';

			if ( $original_query === null ) {

				$order    = self::order_direction($options['order-direction']);

				$args = array(
					'post_type' => $post_types,
					'order' => $order,
					'post__not_in' => $exclude_id,
					'paged' => $current,
					'posts_per_page' => (int)$options['posts-limit']
				);

				if( isset($category) && is_array($category) && count($category) > 0 ){
					$args['category__and '] = $category;
				}

				if( $pagination === 'n' || $pagination === 'load-more' ){
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
					global $ts_list_query;
					$ts_list_query = $query;
					get_template_part('template-pagination');
					$pagination_content = ob_get_clean();
				}
				wp_reset_postdata();

			} else {
				return $grid_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . __( 'Nothing Found', 'touchsize' ) . '</div>' . $grid_view_end;
			}
			$args['options'] = $options;
			$load_more = ($pagination === 'load-more' && $ajax_load_more === false) ? '<div class="ts-pagination-more" data-loop="1" data-args="' . ts_base_64($args, 'encode') . '">' . ts_decorative_icon() . '<span>' .__('Load More', 'touchsize') . '</span>'. ts_decorative_icon() . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

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
				return  $grid_view_start . $elements . $grid_view_end . $next_prev_links . $pagination_content . $load_more;
			}

		} else if ($options['display-mode'] === 'list') {

			$list_view_start = '<section class="ts-list-view ' . $display_effect . ' "><div class="col-lg-12">';
			$list_view_end = '</div></section>';

			if ( $original_query === null ) {

				$order    = self::order_direction($options['order-direction']);

				$args = array(
					'post_type'      => $post_types,
					'order'          => $order,
					'paged'          => $current,
					'post__not_in'   => $exclude_id,
					'posts_per_page' => (int)$options['posts-limit']
				);

				if( $pagination === 'n' || $pagination === 'load-more' ){
					$args['offset'] = $exclude_first;
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
					global $ts_list_query;
					$ts_list_query = $query;
					get_template_part('template-pagination');
					$pagination_content = ob_get_clean();
				}

				wp_reset_postdata();

			} else {
				return $list_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . __( 'Nothing Found', 'touchsize' ) . '</div>' . $list_view_end;
			}

			$args['options'] = $options;
			$load_more = ($pagination === 'load-more' && $ajax_load_more === false) ? '<div class="ts-pagination-more" data-loop="1" data-args="' . ts_base_64($args, 'encode') . '">' . ts_decorative_icon() . '<span>' .__('Load More', 'touchsize') . '</span>'. ts_decorative_icon() . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

			if ( $original_query === null ) {
				$next_prev_links = '';
			} else {
				$next_prev_links = self::archive_navigation();
			}
			if( $ajax_load_more === true ){
				return $elements;
			}else{
				return $list_view_start . $elements . $list_view_end . $next_prev_links . $pagination_content . $load_more;
			}

		} else if ($options['display-mode'] === 'thumbnails') {

			$use_gutter = '';
			$author = (isset($options['author'])) ? $options['author'] : '';

			if( isset($options['gutter']) ){
				if( $options['gutter'] === 'y' ){
					$use_gutter = ' no-gutter';
				}
			}

			$ts_masonry_class = '';
			if ( isset($options['behavior']) && @$options['behavior'] == 'masonry' ) {
				$ts_masonry_class = ' ts-filters-container ';
			}

			$thumbnails_view_start = '<section class="ts-thumbnail-view ' . $display_effect . $use_gutter . $ts_masonry_class . ' ' . self::get_clear_class($options['elements-per-row']) . ' ">';
			$thumbnails_view_end = '</section>';

			$carousel_navigation = '<ul class="carousel-nav">
				                        <li class="carousel-nav-left icon-left"></li>
				                        <li class="carousel-nav-right icon-right"></li>
					                </ul>';

			$carousel_wrapper_start = '<div class="carousel-wrapper">';
			$carousel_wrapper_end = '</div>';

			$carousel_container_start = '<div class="carousel-container">';
			$carousel_container_end = '</div>';

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
					'posts_per_page' => (int)$options['posts-limit']
				);

				if( $author !== '' ){
					$args['author'] = $author;
				}

				if( $pagination === 'n' || $pagination === 'load-more' ){
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
					global $ts_list_query;
					$ts_list_query = $query;
					get_template_part('template-pagination');
					$pagination_content = ob_get_clean();
				}

				wp_reset_postdata();

			} else {
				return $thumbnails_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . __( 'Nothing Found', 'touchsize' ) . '</div>' . $thumbnails_view_end;
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
			$load_more = ($pagination === 'load-more' && $ajax_load_more === false) ? '<div class="ts-pagination-more" data-loop="1" data-args="' . ts_base_64($args, 'encode') . '">' . ts_decorative_icon() . '<span>' .__('Load More', 'touchsize') . '</span>'. ts_decorative_icon() . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

			if (@$options['behavior'] === 'carousel') {
				return $thumbnails_view_start . $carousel_wrapper_start . $carousel_navigation . $carousel_container_start . $elements . $carousel_container_end. $carousel_wrapper_end . $thumbnails_view_end;
			} else if( @$options['behavior'] === 'scroll' ) {
				return $thumbnails_view_start . '<div class="scroll-view"><div class="row">' . $elements .'</div></div>' . $thumbnails_view_end . $next_prev_links . $pagination_content;
			}else if( $ajax_load_more === true ){
				return $elements;
			}else{
				return $thumbnails_view_start . $elements . $thumbnails_view_end . $next_prev_links . $pagination_content . $load_more;
			}

		} else if ($options['display-mode'] === 'big-post') {

			$big_post_view_start = '<section class="ts-big-posts ' . $display_effect . ' ">';
			$big_post_view_end   = '</section>';

			if ( $original_query === null ) {

				$order    = self::order_direction($options['order-direction']);

				$args = array(
					'post_type' => $post_types,
					'order' => $order,
					'post__not_in' => $exclude_id,
					'posts_per_page' => (int)$options['posts-limit'],
					'paged' => $current
				);

				if( $pagination === 'n' || $pagination === 'load-more' ){
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

				while ( $query->have_posts() ) {
					$query->the_post();
					get_template_part('includes/layout-builder/templates/big-posts');
				}
				$elements = ob_get_clean();

				if ( isset($options['pagination']) && $options['pagination'] == 'y' ) {
					ob_start();
					ob_clean();
					global $ts_list_query;
					$ts_list_query = $query;
					get_template_part('template-pagination');
					$pagination_content = ob_get_clean();
				}

				wp_reset_postdata();

			} else {
				return $big_post_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . __( 'Nothing Found', 'touchsize' ) . '</div>' . $big_post_view_end;
			}

			if ( $original_query === null ) {
				$next_prev_links = '';
			} else {
				$next_prev_links = self::archive_navigation();
			}

			$args['options'] = $options;
			$load_more = ($pagination === 'load-more' && $ajax_load_more === false) ? '<div class="ts-pagination-more" data-loop="1" data-args="' . ts_base_64($args, 'encode') . '">' . ts_decorative_icon() . '<span>' .__('Load More', 'touchsize') . '</span>'. ts_decorative_icon() . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

			if( $ajax_load_more === true ){
				return $elements;
			}else{
				return $big_post_view_start . $elements . $big_post_view_end. $next_prev_links . $pagination_content . $load_more;
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
					'post_type' => $post_types,
					'order' => $order,
					'paged' => $current,
					'post__not_in' => $exclude_id,
					'posts_per_page' => (int)$options['posts-limit']
				);

				if( $pagination === 'n' || $pagination === 'load-more' ){
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
				while ( $query->have_posts() ) {
					$query->the_post();
					get_template_part('includes/layout-builder/templates/super-posts');
				}
				$elements = ob_get_clean();

				if ( isset($options['pagination']) && $options['pagination'] == 'y' ) {
					ob_start();
					ob_clean();
					global $ts_list_query;
					$ts_list_query = $query;
					get_template_part('template-pagination');
					$pagination_content = ob_get_clean();
				}

				wp_reset_postdata();

			} else {
				return $super_post_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . __( 'Nothing Found', 'touchsize' ) . '</div>' . $super_post_view_end;
			}

			if ( $original_query === null ) {
				$next_prev_links = '';
			} else {
				$next_prev_links = self::archive_navigation();
			}

			$args['options'] = $options;
			$load_more = ($pagination === 'load-more' && $ajax_load_more === false) ? '<div class="ts-pagination-more" data-loop="1" data-args="' . ts_base_64($args, 'encode') . '">' . ts_decorative_icon() . '<span>' .__('Load More', 'touchsize') . '</span>'. ts_decorative_icon() . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

			if( $ajax_load_more === true ){
				return $elements;
			}else{
				return $super_post_view_start . $elements . $super_post_view_end . $next_prev_links . $pagination_content . $load_more;
			}

		} else if ($options['display-mode'] === 'timeline') {

			if ( $original_query === null ) {

				$order    = self::order_direction($options['order-direction']);

				$args = array(
					'post_type' => $post_types,
					'order' => $order,
					'paged' => $current,
					'post__not_in' => $exclude_id,
					'posts_per_page' => (int)$options['posts-limit']
				);

				if( $pagination === 'n' || $pagination === 'load-more' ){
					$args['offset'] = $exclude_first;
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
					global $ts_list_query;
					$ts_list_query = $query;
					get_template_part('template-pagination');
					$pagination_content = ob_get_clean();
				}

				wp_reset_postdata();

			} else {
				return $timeline_view_start . '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . __( 'Nothing Found', 'touchsize' ) . '</div>' . $list_view_end;
			}

			if ( $original_query === null ) {
				$next_prev_links = '';
			} else {
				$next_prev_links = self::archive_navigation();
			}

			$args['options'] = $options;
			$load_more = ($pagination === 'load-more' && $ajax_load_more === false) ? '<div class="ts-pagination-more" data-loop="1" data-args="' . ts_base_64($args, 'encode') . '">' . ts_decorative_icon() . '<span>' .__('Load More', 'touchsize') . '</span>'. ts_decorative_icon() . wp_nonce_field('pagination-read-more', 'pagination') . '</div>' : '';

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

				$args = array(
					'post_type' => $post_types,
					'posts_per_page' => (int)$options['posts-limit'],
					'order' => $order,
					'post__not_in' => $exclude_id,
					'offset' => $exclude_first

				);

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

				$article_options['i'] = 1;
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

				wp_reset_postdata();

			} else {
				return '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' . __( 'Nothing Found', 'touchsize' ) . '</div>';
			}

			if ( $original_query === null ) {
				$next_prev_links = '';
			} else {
				$next_prev_links = self::archive_navigation();
			}

			return $scroll . $elements . $next_prev_links . '</div>';

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

	/*public static function video_carousel_element($options = array()) {

	    $video_carousel = ( isset($options['video-carousel']) && !empty($options['video-carousel']) && $options['video-carousel'] !== '[]' ) ? json_decode(stripslashes($options['video-carousel'])) : NULL;
	    if( isset($video_carousel) && is_array($video_carousel) && !empty($video_carousel) ){

	    	$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 7);

	    	$ts_video_carousel = '<div id="'. $randomString .'" class="ts-video-carousel"><div class="ts-video-carousel-wrap"><ul class="slides">';

	    	foreach($video_carousel as $carousel){
		    	$title = (isset($carousel->title)) ? esc_attr($carousel->title) : '';
	    		$description = (isset($carousel->text)) ? nl2br($carousel->text) : '';
	    		$url = (isset($carousel->url)) ? esc_url($carousel->url) : '';
	    		$embed = (isset($carousel->embed)) ? esc_url($carousel->embed) : '';

	    		$ts_video_carousel .= 	'<li>
							    			<div class="thumb"><div class="carousel-video-wrapper"><div class="embedded_videos">'.  wp_oembed_get($embed) .'</div></div></div>
							    			<div class="carousel-content">
							    				<h3 class="carousel-item-title"><a href="'. $url. '">'. $title . '</a></h3>
							    				<div class="carousel-description">' . $description .'</div>
							    			</div>
							    		</li>';
	    	}

	    	$ts_video_carousel .= 	'</ul></div></div>
									<script>
										jQuery(document).ready(function(){
											jQuery("#'. $randomString. '").ts_video_carousel();
										});
									</script>';
	    	return $ts_video_carousel;
	    }
	}*/

	public static function video_carousel_element($options = array()) {

	    $video_carousel = ( isset($options['video-carousel']) && !empty($options['video-carousel']) && $options['video-carousel'] !== '[]' ) ? json_decode(stripslashes($options['video-carousel'])) : NULL;
	    $source = (isset($options['source']) && ($options['source'] == 'latest-posts' || $options['source'] == 'latest-galleries' || $options['source'] == 'latest-videos' || $options['source'] == 'latest-featured-posts' || $options['source'] == 'latest-featured-galleries' || $options['source'] == 'latest-featured-videos' || $options['source'] == 'custom-slides')) ? $options['source'] : 'custom-slides';

	    $randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 7);

	    $ts_video_carousel = '<div id="'. $randomString .'" class="ts-video-carousel"><div class="ts-video-carousel-wrap"><ul class="slides">';

	    if( $source == 'custom-slides' ){
		    if( isset($video_carousel) && is_array($video_carousel) && !empty($video_carousel) ){

		    	foreach($video_carousel as $carousel){
			    	$title = (isset($carousel->title)) ? esc_attr($carousel->title) : '';
		    		$description = (isset($carousel->text)) ? apply_filters('the_content', $carousel->text) : '';
		    		$url = (isset($carousel->url)) ? esc_url($carousel->url) : '';
		    		$embed = (isset($carousel->embed)) ? esc_url($carousel->embed) : '';

		    		$ts_video_carousel .= 	'<li>
								    			<div class="thumb">
								    				<div class="carousel-video-wrapper">
								    					<div class="embedded_videos">'. wp_oembed_get($embed) .'</div>
								    				</div>
								    			</div>
								    			<div class="carousel-content">
								    				<h3 class="carousel-item-title"><a href="'. $url. '">'. $title . '</a></h3>
								    				<div class="carousel-description">' . $description .'</div>
								    			</div>
								    		</li>';
		    	}

		    }else{
		    	return;
		    }
		} else {

			$post_type = ($source == 'latest-posts' ? 'post' :
							($source == 'latest-videos' ? 'video' :
								($source == 'latest-galleries' ? 'ts-gallery' :
									($source == 'latest-featured-posts' ? 'post' :
										($source == 'latest-featured-videos' ? 'video' :
											($source == 'latest-featured-galleries' ? 'ts-gallery' : 'post'))))));

			$postsPerPage = (isset($options['nr-of-posts']) && is_numeric($options['nr-of-posts'])) ? $options['nr-of-posts'] : 5;

			$category = isset( $options['category'] ) && ! empty( $options['category'] ) ? explode( ',', $options['category'] ) : array();
			$tax = $post_type == 'video' ? 'videos_categories' : 'category';

			$args = array(
				'post_type'      => $post_type,
				'posts_per_page' => $postsPerPage,
				'tax_query' => array(
			        array(
			            'taxonomy' => $tax,
			            'field'    => 'id',
			            'terms'     => $category
			        )
			    )
			);

			if( $source == 'latest-featured-posts' || $source == 'latest-featured-videos' ){
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

							$embed_image = wp_oembed_get( $videos['extern_url'] );

						}elseif( isset($videos['your_url']) && !empty($videos['your_url']) ){

							$src = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );

							$poster_url = ts_resize('single', $src);

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
							if ( has_post_thumbnail(get_the_ID()) ) $embed_image = '<img src="'. aq_resize(wp_get_attachment_url( get_post_thumbnail_id(get_the_ID())), 800, 650, true) .'">';
							else $embed_image = '<img src="'. get_template_directory_uri() . '/images/noimage.jpg">';
						}

					}else{
						if ( has_post_thumbnail(get_the_ID()) ) $embed_image = '<img src="'. aq_resize(wp_get_attachment_url( get_post_thumbnail_id(get_the_ID())), 800, 650, true) .'">';
						else $embed_image = '<img src="'. get_template_directory_uri() . '/images/noimage.jpg">';
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

				    $article_date = (ts_human_type_date_format()) ? human_time_diff(strtotime(get_the_time('Y-m-d H:i:s'))).' '.__('ago', 'slimvideo') : get_the_date();

		    		$ts_video_carousel .= 	'<li>
								    			<div class="thumb">
								    				<div class="carousel-video-wrapper">
									    				<div class="embedded_videos">
									    					'. $embed_image .'
									    				</div>
								    				</div>
								    			</div>
								    			<div class="carousel-content">
								    				<h3 class="carousel-item-title"><a href="'. get_permalink() . '">'. get_the_title() . '</a></h3>
								    				<div class="carousel-description">' . $description .'</div>
								    			</div>
								    		</li>';
				}
			}else{
				return;
			}
		}

    	$ts_video_carousel .= 	'</ul></div></div>
								<script>
									jQuery(document).ready(function(){
										jQuery("#'. $randomString .'").ts_video_carousel();
									});
								</script>';

		return $ts_video_carousel;
	}

	public static function quote_element($options = array())
	{
		if( isset($options['type']) && $options['type'] === 'quote' ){
			$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 7);

			$text   = (isset($options['text']) && is_string($options['text'])) ? nl2br($options['text']) : '';
			$author = (isset($options['author']) && is_string($options['author'])) ? esc_attr($options['author']) : '';
			$icon   = (isset($options['icon']) && is_string($options['icon'])) ? esc_attr($options['icon']) : 'no-icon';

			$ts_blockquote .= ' <div class="col-md-12 col-lg-12">
									<div id="'. $randomString .'" class="ts-blockquote">
										<blockquote>
											<div class="quote-icon"><i class="'.$icon.'"></i></div>
											<div class="quote-text">
												<p>'.$text.'</p>
												<span class="quote-author">&mdash; '.$author.'</span>
											</div>
										</blockquote>
									</div>
								</div>
			';
			return $ts_blockquote;
		}
	}

}

?>
