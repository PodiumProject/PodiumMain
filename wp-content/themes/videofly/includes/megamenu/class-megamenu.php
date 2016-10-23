<?php
if( !class_exists( 'ts_is_megamenu' ) )
{
	class ts_is_megamenu
	{

		function ts_is_megamenu()
		{
			//adds stylesheet and javascript to the menu page
			add_action('admin_menu', array(&$this,'ts_menu_header'));

			//exchange arguments and tell menu to use the ts walker for front end rendering
			add_filter('wp_nav_menu_args', array(&$this,'modify_arguments'), 100);

			//exchange argument for backend menu walker
			add_filter( 'wp_edit_nav_menu_walker', array(&$this,'modify_backend_walker') , 100);

			//save ts options:
			add_action( 'wp_update_nav_menu_item', array(&$this,'update_menu'), 100, 3);

		}

		/**
		 * If we are on the nav menu page add javascript and css for the page
		 */
		function ts_menu_header()
		{
			if(wp_basename( $_SERVER['PHP_SELF']) == "nav-menus.php" )
			{
				//wp_enqueue_style(  'ts_admin', get_template_directory_uri() . '/admin/css/ts_admin.css');
				//wp_enqueue_script( 'ts_is_mega_menu' , get_template_directory_uri() .'/admin/js/ts_is_mega_menu.js',array('jquery', 'jquery-ui-sortable'), false, true );
			}
		}

		/**
		 * Replaces the default arguments for the front end menu creation with new ones
		 */
		function modify_arguments($arguments){

			$walker = apply_filters("ts_is_mega_menu_walker", "ts_walker");

			if($walker)
			{
				$arguments['walker'] 				= new $walker();
				$arguments['container_class'] 		= $arguments['container_class'] .= ' megaWrapper';
				$arguments['menu_class']			= 'main-menu';
			}

			return $arguments;
		}


		/**
		 * Tells wordpress to use our backend walker instead of the default one
		 */
		function modify_backend_walker($name)
		{
			return 'ts_backend_walker';
		}



		/*
		 * Save and Update the Custom Navigation Menu Item Properties by checking all $_POST vars with the name of $check
		 * @param int $menu_id
		 * @param int $menu_item_db
		 */
		function update_menu($menu_id, $menu_item_db)
		{
			$check = apply_filters('avf_mega_menu_post_meta_fields',array('megamenu','division','textarea'), $menu_id, $menu_item_db);

			if( isset($_POST['menu-item-category-posts'][$menu_item_db]) ){
				$value = $_POST['menu-item-category-posts'][$menu_item_db];
				update_post_meta($menu_item_db, '_menu-item-category-posts', $value);
			}else{
				$_POST['menu-item-category-posts'][$menu_item_db] = '';
				update_post_meta($menu_item_db, '_menu-item-category-posts', $_POST['menu-item-category-posts'][$menu_item_db]);
			}

			if( isset($_POST['menu-item-taxonomy'][$menu_item_db]) ){
				$value = $_POST['menu-item-taxonomy'][$menu_item_db];
				update_post_meta($menu_item_db, '_menu-item-taxonomy', $value);
			}else{
				$_POST['menu-item-taxonomy'][$menu_item_db] = '';
				update_post_meta($menu_item_db, '_menu-item-taxonomy', $_POST['menu-item-taxonomy'][$menu_item_db]);
			}

			if( isset($_POST['menu-item-nr-of-columns'][$menu_item_db]) ){
				$value = $_POST['menu-item-nr-of-columns'][$menu_item_db];
				update_post_meta($menu_item_db, '_menu-item-nr-of-columns', $value);
			}else{
				$_POST['menu-item-nr-of-columns'][$menu_item_db] = '';
				update_post_meta($menu_item_db, '_menu-item-nr-of-columns', $_POST['menu-item-nr-of-columns'][$menu_item_db]);
			}

			if(isset($_POST['menu-item-icons'][$menu_item_db])){
				$value = $_POST['menu-item-icons'][$menu_item_db];
				update_post_meta( $menu_item_db, '_menu-item-icons', $value );
			}else{
				$_POST['menu-item-icons'][$menu_item_db] = '';
				$value = $_POST['menu-item-icons'][$menu_item_db];
				update_post_meta( $menu_item_db, '_menu-item-icons', $value );
			}

			foreach ( $check as $key ){
				if(!isset($_POST['menu-item-ts-'.$key][$menu_item_db]))
				{
					$_POST['menu-item-ts-'.$key][$menu_item_db] = "";
				}

				$value = $_POST['menu-item-ts-'.$key][$menu_item_db];
				update_post_meta( $menu_item_db, '_menu-item-ts-'.$key, $value );
			}
		}
	}
}



if( !class_exists( 'ts_walker' ) )
{

	/**
	 * The ts walker is the frontend walker and necessary to display the menu, this is a advanced version of the wordpress menu walker
	 * @package WordPress
	 * @since 1.0.0
	 * @uses Walker
	 */
	class ts_walker extends Walker {
		/**
		 * @see Walker::$tree_type
		 * @var string
		 */
		var $tree_type = array( 'post_type', 'taxonomy', 'custom' );

		/**
		 * @see Walker::$db_fields
		 * @todo Decouple this.
		 * @var array
		 */
		var $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );

		/**
		 * @var int $columns
		 */
		var $columns = 0;

		/**
		 * @var int $max_columns maximum number of columns within one mega menu
		 */
		var $max_columns = 0;

		/**
		 * @var int $rows holds the number of rows within the mega menu
		 */
		var $rows = 1;

		/**
		 * @var array $rowsCounter holds the number of columns for each row within a multidimensional array
		 */
		var $rowsCounter = array();

		/**
		 * @var string $mega_active hold information whetever we are currently rendering a mega menu or not
		 */
		var $mega_active = 0;



		/**
		 * @see Walker::start_lvl()
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param int $depth Depth of page. Used for padding.
		 */
		function start_lvl(&$output, $depth = 0, $args = array()) {
			$indent = str_repeat("\t", $depth);
			if($depth === 0) $output .= "\n{replace_one}\n";
			$output .= "\n$indent<ul class=\"sub-menu\">\n";
		}

		/**
		 * @see Walker::end_lvl()
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param int $depth Depth of page. Used for padding.
		 */
		function end_lvl(&$output, $depth = 0, $args = array()) {
			$indent = str_repeat("\t", $depth);
			$output .= "$indent</ul>\n";

			if($depth === 0)
			{
				if($this->mega_active)
				{

					$output .= "\n</div>\n";
					$output = str_replace("{replace_one}", "<div class='ts_is_mega_div ts_is_mega".$this->max_columns."'>", $output);

					foreach($this->rowsCounter as $row => $columns)
					{
						$output = str_replace("{current_row_".$row."}", "ts_is_mega_menu_columns_".$columns, $output);
					}

					$this->columns = 0;
					$this->max_columns = 0;
					$this->rowsCounter = array();

				}
				else
				{
					$output = str_replace("{replace_one}", "", $output);
				}
			}
		}

		/**
		 * @see Walker::start_el()
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param object $item Menu item data object.
		 * @param int $depth Depth of menu item. Used for padding.
		 * @param int $current_page Menu item ID.
		 * @param object $args
		 */
		function start_el(&$output, $item, $depth = 0, $args = array(), $current_object_id = 0 ) {
			global $wp_query;

			//set maxcolumns
			if( is_object($args) && !isset($args->max_columns) ) $args->max_columns = 5;

			$item_output = $li_text_block_class = $column_class = "";

			if($depth === 0){
				$this->mega_active = get_post_meta( $item->ID, '_menu-item-ts-megamenu', true);
			}

			$item->icons = get_post_meta($item->ID, '_menu-item-icons', true);
			$item->category_posts = get_post_meta($item->ID, '_menu-item-category-posts', true);
			$item->nr_of_columns = get_post_meta($item->ID, '_menu-item-nr-of-columns', true);
			$get_posts = '';
			$taxonomy = get_post_meta($item->ID, '_menu-item-taxonomy', true);

			if( isset($item->category_posts) && $item->category_posts === 'y' ){

				switch ( $taxonomy ) {
					case 'videos_categories':
						$post_type = 'video';
						break;
					case 'gallery_categories':
						$post_type = 'ts-gallery';
						break;

					case 'portfolio_register_post_type':
						$post_type = 'portfolio';
						break;

					case 'gallery_categories':
						$post_type = 'ts-gallery';
						break;

					case 'teams':
						$post_type = 'ts_teams';
						break;

					default:
						$post_type = '';
						break;
				}

				$query = get_posts(array(
						'post_type' => $post_type,
						'tax_query' => array(
							array(
								'taxonomy' => $taxonomy,
								'field' => 'id',
								'terms' => (array)$item->object_id
							)
						),
						'posts_per_page' => $item->nr_of_columns,
						'orderby' => 'date',
						'order' => 'DESC'
					)
				);

				if($depth === 0){
					if( isset($query) && !empty($query)){
						$get_posts = '<div class="ts_is_mega_div ts_is_mega'.$item->nr_of_columns.'">
										<ul class="sub-menu ts_is_mega_submenu ts_is_mega">';
						foreach($query as $wp_post){

							$title_post     = $wp_post->post_title;
							$permalink_post = get_permalink($wp_post->ID);

							$article_date =  get_the_date('', $wp_post->ID);

							$src = wp_get_attachment_url(get_post_thumbnail_id($wp_post->ID));
							$img_url = vdf_resize('thumbnails', $src, false);
							$noimg_url = get_template_directory_uri() . '/images/noimage.jpg';
							$bool = fields::get_options_value('videofly_general', 'enable_imagesloaded');

							if ( $src ) {
								$featimage = '<img '. vdf_imagesloaded($bool, $img_url) .' alt="'. esc_attr($title_post) .'" />';
							} else {
								$featimage = '<img '.  vdf_imagesloaded($bool, $noimg_url) .' alt="'. esc_attr($title_post) .'" />';
							}
							$get_posts .= '<li class="ts_is_mega_menu_columns_'.$item->nr_of_columns.'">';
							$get_posts .= 	'<div class="ts_taxonomy_views">
												<article class="title-below-image">
													<div class="header">
														<div class="image-holder">
															<a href="'.$permalink_post.'">' . $featimage . '</a>
														</div>
													</div>
													<div class="section">
														<div class="entry-title">
															<h3 class="title">
																<a href="' . $permalink_post . '">' . $title_post . '</a>
															</h3>
														</div>
														<div class="ts-date">
															'. $article_date .'
														</div>
													</div>
												</article>
											</div>';
							$get_posts .= '</li>';
						}
						$get_posts .= '</ul></div>';
					}
				}

			}

			if( $depth === 1 && $this->mega_active ){
				$this->columns ++;

				//check if we have more than $args['max_columns'] columns or if the user wants to start a new row
				if( $this->columns > $args->max_columns || (get_post_meta( $item->ID, '_menu-item-ts-division', true) && $this->columns != 1) ){
					$this->columns = 1;
					$this->rows ++;
					// $output .= "\n<li class='ts_is_mega_hr'></li>\n";
				}

				$this->rowsCounter[$this->rows] = $this->columns;

				if( $this->max_columns < $this->columns ) $this->max_columns = $this->columns;


				$title = apply_filters( 'the_title', $item->title, $item->ID );

				if($title != "-" && $title != '"-"'){ //fallback for people who copy the description o_O
					$item_output .= "<h4 class='title'>".$title."</h4>";
				}
				$item_output .= $get_posts;
				$column_class  = ' {current_row_'.$this->rows.'}';

				if($this->columns == 1){
					$column_class  .= " ts_is_mega_menu_columns_fist";
				}

			}else if( $depth >= 2 && $this->mega_active && get_post_meta($item->ID, '_menu-item-ts-textarea', true) ){

				$li_text_block_class = 'ts_is_mega_text_block ';
				$item_output .= do_shortcode($item->post_content);
				$item_output .= $get_posts;

			}else{
				$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) . '"' : '';
				$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) . '"' : '';
				$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) . '"' : '';
				$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) . '"' : '';

				$item_output .= (is_object($args)) ? $args->before : '';
				$item_output .= '<a'. $attributes .'>';

				if( $item->icons != '' && $item->icons != 'icon-noicon' ){
					$item_output .= '<i class="' . $item->icons . '"></i>';
				}
				$link_before = is_object($args) ? $args->link_before : '';
				$link_after = is_object($args) ? $args->link_after : '';
				$item_output .= $link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $link_after;
				if ( trim($item->post_content) !== '' ) {
					$item_output .= '<span class="mega-menu-item-description">' . do_shortcode($item->post_content) . '</span>';
				}
				$item_output .= '</a>';
				$item_output .= $get_posts;
				$item_output .= is_object($args) ? $args->after : '';
			}

			$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
			$class_names = $value = '';

			$classes = empty( $item->classes ) ? array() : (array) $item->classes;

			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
			if ( trim($item->post_content) !== '' ) {
				$class_names .= " menu-item-has-description ";
			}
			$class_names = ' class="'.$li_text_block_class. esc_attr( $class_names ) . $column_class.'"';

			$output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}

		/**
		 * @see Walker::end_el()
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param object $item Page data object. Not used.
		 * @param int $depth Depth of page. Not Used.
		 */
		function end_el(&$output, $item, $depth = 0, $args = array()) {
			$output .= "</li>\n";
		}
	}
}





if( !class_exists( 'ts_backend_walker' ) )
{
/**
 * Create HTML list of nav menu input items.
 * This walker is a clone of the wordpress edit menu walker with some options appended, so the user can choose to create mega menus
 *
 * @package tsFramework
 * @since 1.0
 * @uses Walker_Nav_Menu
 */
	class ts_backend_walker extends Walker_Nav_Menu
	{
		/**
		 * @see Walker_Nav_Menu::start_lvl()
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference.
		 * @param int $depth Depth of page.
		 */
		function start_lvl(&$output, $depth = 0, $args = array() ) {}

		/**
		 * @see Walker_Nav_Menu::end_lvl()
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference.
		 * @param int $depth Depth of page.
		 */
		function end_lvl(&$output, $depth = 0, $args = array()) {
		}

		/**
		 * @see Walker::start_el()
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param object $item Menu item data object.
		 * @param int $depth Depth of menu item. Used for padding.
		 * @param int $current_page Menu item ID.
		 * @param object $args
		 */
		function start_el(&$output, $item, $depth = 0, $args = array(), $current_object_id = 0 ) {
			global $_wp_nav_menu_max_depth;

			$_wp_nav_menu_max_depth = ($depth > $_wp_nav_menu_max_depth) ? $depth : $_wp_nav_menu_max_depth;

			$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

			ob_start();
			$item_id = esc_attr( $item->ID );
			$removed_args = array(
				'action',
				'customlink-tab',
				'edit-menu-item',
				'menu-item',
				'page-tab',
				'_wpnonce',
			);

			$original_title = '';
			if ( 'taxonomy' == $item->type ) {
				$original_title = get_term_field('name', $item->object_id, $item->object, 'raw');
			} elseif ( 'post_type' == $item->type ) {
				$original_object = get_post( $item->object_id );
				$original_title = $original_object->post_title;
			}

			$classes = array(
				'menu-item menu-item-depth-' . $depth,
				'menu-item-' . esc_attr( $item->object ),
				'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
			);

			$title = $item->title;

			if ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
				$classes[] = 'pending';
				/* translators: %s: title of menu item in draft status */
				$title = sprintf( esc_html__('%s (Pending)', 'videofly'), $item->title );
			}

			$title = empty($item->label) ? $title : $item->label;

			$itemValue = "";
			if($depth == 0)
			{
				$itemValue = get_post_meta( $item->ID, '_menu-item-ts-megamenu', true);
				if($itemValue != "") $itemValue = 'ts_is_mega_active ';
			}
			$item->post_category = '';

			if( $item->object === 'category' || $item->object === 'videos_categories' || $item->object === 'portfolio_register_post_type' || $item->object === 'teams' || $item->object === 'gallery_categories' ){
				$item->category_posts = get_post_meta($item->ID, '_menu-item-category-posts', true);
				$item->nr_of_columns  = get_post_meta($item->ID, '_menu-item-nr-of-columns', true);
			}

			$item->icons = get_post_meta($item->ID, '_menu-item-icons', true);
			$icons_array = get_option('videofly_typography',array());

			$icons_li = '';
			$icons_options = '';
			$class_icon = '';
			$icons_array['icons'] = explode(',',$icons_array['icons']);
			foreach ($icons_array['icons'] as $value) {

			    if( $item->icons === $value ){
			    	$icons_li .= '<li class="selected"><i class="'. $value .' clickable-element" data-option="'. $value .'"></i></li>';
			    	$icons_options .= '<option selected="selected" value="'. $value .'"></option>';
			    	$class_icon = $value;
			    }else{
			    	$icons_options .= '<option value="'. $value .'"></option>';
			    	$icons_li .= '<li><i class="'. $value .' clickable-element" data-option="'. $value .'"></i></li>';
			    }

			}
			?>

			<li id="menu-item-<?php echo vdf_var_sanitize($item_id); ?>" class="<?php echo esc_attr($itemValue); echo implode(' ', $classes ); ?>">
				<dl class="menu-item-bar">
					<dt class="menu-item-handle">
						<span class="item-title"><?php echo esc_html( $title ); ?></span>
						<span class="item-controls">
							<span class="item-type item-type-default"><?php echo esc_html( $item->type_label ); ?></span>
							<span class="item-type item-type-ts"><?php esc_html_e('-- Is Column -- ','videofly'); ?></span>
							<span class="item-type item-type-megafied"><?php esc_html_e('-- Is Mega Menu --','videofly'); ?></span>
							<span class="item-order">
								<a href="<?php
									echo wp_nonce_url(
										add_query_arg(
											array(
												'action' => 'move-up-menu-item',
												'menu-item' => $item_id,
											),
											remove_query_arg($removed_args, esc_url(admin_url( 'nav-menus.php' )) )
										),
										'move-menu_item'
									);
								?>" class="item-move-up"><abbr title="<?php esc_attr('Move up','videofly'); ?>">&#8593;</abbr></a>
								|
								<a href="<?php
									echo wp_nonce_url(
										add_query_arg(
											array(
												'action' => 'move-down-menu-item',
												'menu-item' => $item_id,
											),
											remove_query_arg($removed_args, esc_url(admin_url( 'nav-menus.php' )) )
										),
										'move-menu_item'
									);
								?>" class="item-move-down"><abbr title="<?php esc_attr('Move down','videofly'); ?>">&#8595;</abbr></a>
							</span>
							<a class="item-edit" id="edit-<?php echo vdf_var_sanitize($item_id); ?>" title="<?php esc_html_e('Edit Menu Item','videofly'); ?>" href="<?php
								echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? esc_url( admin_url( 'nav-menus.php' ) ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, esc_url(admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) )) );
							?>"><?php esc_html_e( 'Edit Menu Item','videofly' ); ?></a>
						</span>
					</dt>
				</dl>

				<div class="menu-item-settings" id="menu-item-settings-<?php echo vdf_var_sanitize($item_id); ?>">
					<?php if( 'custom' == $item->type ) : ?>
						<p class="field-url description description-wide">
							<label for="edit-menu-item-url-<?php echo vdf_var_sanitize($item_id); ?>">
								<?php esc_html_e( 'URL','videofly' ); ?><br />
								<input type="text" id="edit-menu-item-url-<?php echo vdf_var_sanitize($item_id); ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo vdf_var_sanitize($item_id); ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
							</label>
						</p>
					<?php endif; ?>
					<p class="description description-thin description-label ts_label_desc_on_active">
						<label for="edit-menu-item-title-<?php echo vdf_var_sanitize($item_id); ?>">
						<span class='ts_default_label'><?php esc_html_e( 'Navigation Label','videofly' ); ?></span>
						<span class='ts_is_mega_label'><?php esc_html_e( 'Mega Menu Column Title (if you dont want to display a title just enter a single dash: "-" )','videofly' ); ?></span>

							<br />
							<input type="text" id="edit-menu-item-title-<?php echo vdf_var_sanitize($item_id); ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo vdf_var_sanitize($item_id); ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
						</label>
					</p>
					<p class="description description-thin description-title">
						<label for="edit-menu-item-attr-title-<?php echo vdf_var_sanitize($item_id); ?>">
							<?php esc_html_e( 'Title Attribute','videofly' ); ?><br />
							<input type="text" id="edit-menu-item-attr-title-<?php echo vdf_var_sanitize($item_id); ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo vdf_var_sanitize($item_id); ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
						</label>
					</p>
					<p class="field-link-target description description-thin">
						<label for="edit-menu-item-target-<?php echo vdf_var_sanitize($item_id); ?>">
							<?php esc_html_e( 'Menu Link Target','videofly' ); ?><br />
							<select id="edit-menu-item-target-<?php echo vdf_var_sanitize($item_id); ?>" class="widefat edit-menu-item-target" name="menu-item-target[<?php echo vdf_var_sanitize($item_id); ?>]">
								<option value="" <?php selected( $item->target, ''); ?>><?php esc_html_e('Open in same window','videofly'); ?></option>
								<option value="_blank" <?php selected( $item->target, '_blank'); ?>><?php esc_html_e('Open in new window','videofly'); ?></option>
							</select>
						</label>
					</p>
					<p class="field-css-classes description description-thin">
						<label for="edit-menu-item-classes-<?php echo vdf_var_sanitize($item_id); ?>">
							<?php esc_html_e( 'Custom CSS Classes','videofly' ); ?><br />
							<input type="text" id="edit-menu-item-classes-<?php echo vdf_var_sanitize($item_id); ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo vdf_var_sanitize($item_id); ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
						</label>
					</p>
					<p class="field-icons description description-icons">
						<label for="edit-menu-item-icon-<?php echo vdf_var_sanitize($item_id); ?>">
							<?php esc_html_e( 'Add icon' ,'videofly'); ?><br />
						</label>
						<div class="builder-element-icon-toggle">
						    <a href="#" class="red-ui-button builder-element-icon-trigger-btn" data-toggle="#menu-item-icon-selector-[<?php echo vdf_var_sanitize($item_id); ?>]">Show icons<i class="<?php echo vdf_var_sanitize($class_icon); ?>"></i></a>
						</div>
						<ul style="display: none;" id="menu-item-icon-selector-[<?php echo vdf_var_sanitize($item_id); ?>" data-selector="#edit-menu-item-icons-<?php echo vdf_var_sanitize($item_id); ?>" class="builder-icon-list ts-custom-selector">
						    <?php echo vdf_var_sanitize($icons_li); ?>
						</ul>
						<select name="menu-item-icons[<?php echo vdf_var_sanitize($item_id); ?>]" id="edit-menu-item-icons-<?php echo vdf_var_sanitize($item_id); ?>" class="hidden">
						    <?php echo vdf_var_sanitize($icons_options); ?>
						</select>
					</p>
					<p class="field-description description description-wide">
						<label for="edit-menu-item-description-<?php echo vdf_var_sanitize($item_id); ?>">
							<?php esc_html_e( 'Description' ,'videofly'); ?><br />
							<textarea id="edit-menu-item-description-<?php echo vdf_var_sanitize($item_id); ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo vdf_var_sanitize($item_id); ?>]"><?php echo esc_html( $item->post_content ); ?></textarea>
						</label>
					</p>

					<?php if( $item->object === 'category' && $item->menu_item_parent == 0 || $item->object === 'videos_categories' && $item->menu_item_parent == 0 || $item->object === 'portfolio_register_post_type' && $item->menu_item_parent == 0 || $item->object === 'teams' && $item->menu_item_parent == 0  || $item->object === 'gallery_categories' && $item->menu_item_parent == 0 ) : ?>
						<p class="field-category-posts category-posts description-category-posts">
							<label for="edit-menu-item-category-posts-<?php echo vdf_var_sanitize($item_id); ?>">
								<?php esc_html_e( 'Show post:' ,'videofly'); ?><br />
							</label>
							<select class="ts-menu-category-posts" name="menu-item-category-posts[<?php echo vdf_var_sanitize($item_id); ?>]" id="edit-menu-item-category-posts-<?php echo vdf_var_sanitize($item_id); ?>">
								<option value="y" <?php selected($item->category_posts, 'y'); ?>><?php esc_html_e('Yes', 'videofly'); ?></option>
								<option value="n" <?php selected($item->category_posts, 'n'); ?>><?php esc_html_e('No', 'videofly'); ?></option>
							</select>
							<input type="hidden" name="menu-item-taxonomy[<?php echo vdf_var_sanitize($item_id); ?>]" value="<?php echo vdf_var_sanitize($item->object); ?>">
						</p>
						<p class="field-nr-of-columns nr-of-columns description-nr-of-columns">
							<label for="edit-menu-item-nr-of-columns-<?php echo vdf_var_sanitize($item_id); ?>">
								<?php esc_html_e( 'Number of columns:' ,'videofly'); ?><br />
							</label>
							<select name="menu-item-nr-of-columns[<?php echo vdf_var_sanitize($item_id); ?>]" id="edit-menu-item-nr-of-columns-<?php echo vdf_var_sanitize($item_id); ?>">
								<option value="3" <?php selected($item->nr_of_columns, '3'); ?>>3</option>
								<option value="4" <?php selected($item->nr_of_columns, '4'); ?>>4</option>
								<option value="5" <?php selected($item->nr_of_columns, '5'); ?>>5</option>
							</select>
						</p>
					<?php endif; ?>

					<div class='ts_is_mega_menu_options'>
					<!-- ################# ts custom code here ################# -->
						<?php
						$key = "menu-item-ts-megamenu";
						$value = get_post_meta( $item->ID, '_'.$key, true);

						if($value != "") $value = "checked='checked'";
						?>

						<p class="description description-wide ts_checkbox ts_is_mega_menu ts_is_mega_menu_d0">
							<label for="edit-<?php echo vdf_var_sanitize($key.'-'.$item_id); ?>">
								<input type="checkbox" value="active" id="edit-<?php echo vdf_var_sanitize($key.'-'.$item_id); ?>" class="ts-megamenu-category-posts <?php echo vdf_var_sanitize($key); ?>" name="<?php echo vdf_var_sanitize($key . "[". $item_id ."]");?>" <?php echo vdf_var_sanitize($value); ?> /><?php esc_html_e( 'Set as Mega Menu','videofly' ); ?>
							</label>
						</p>
						<!-- ***************  end item *************** -->

						<?php
						$key = "menu-item-ts-division";
						$value = get_post_meta( $item->ID, '_'.$key, true);

						if($value != "") $value = "checked='checked'";
						?>

						<p class="description description-wide ts_checkbox ts_is_mega_menu ts_is_mega_menu_d1">
							<label for="edit-<?php echo vdf_var_sanitize($key.'-'.$item_id); ?>">
								<input type="checkbox" value="active" id="edit-<?php echo vdf_var_sanitize($key.'-'.$item_id); ?>" class=" <?php echo vdf_var_sanitize($key); ?>" name="<?php echo vdf_var_sanitize($key . "[". $item_id ."]");?>" <?php echo vdf_var_sanitize($value); ?> /><?php esc_html_e('Column starts new row','videofly'); ?>
							</label>
						</p>
						<!-- ***************  end item *************** -->



						<?php
						$title = esc_html__('Check to create text content. This will not display the item as a link. (Please dont remove the label text, WordPress will automatically remove the item.)','videofly');
						$key = "menu-item-ts-textarea";
						$value = get_post_meta( $item->ID, '_'.$key, true);

						if($value != "") $value = "checked='checked'";
						?>

						<p class="description description-wide ts_checkbox ts_is_mega_menu ts_is_mega_menu_d2">
							<label for="edit-<?php echo vdf_var_sanitize($key.'-'.$item_id); ?>">
								<input type="checkbox" value="active" id="edit-<?php echo vdf_var_sanitize($key.'-'.$item_id); ?>" class=" <?php echo vdf_var_sanitize($key); ?>" name="<?php echo vdf_var_sanitize($key . "[". $item_id ."]");?>" <?php echo vdf_var_sanitize($value); ?> /><span class='ts_long_desc'><?php  echo vdf_var_sanitize($title); ?></span>
							</label>
						</p>
						<!-- ***************  end item *************** -->




					</div>

					<!-- ################# end ts custom code here ################# -->

					<div class="menu-item-actions description-wide submitbox">
						<?php if( 'custom' != $item->type ) : ?>
							<p class="link-to-original">
								<?php printf( esc_html__('Original: %s', 'videofly'), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
							</p>
						<?php endif; ?>
						<a class="item-delete submitdelete deletion" id="delete-<?php echo vdf_var_sanitize($item_id); ?>" href="<?php
						echo wp_nonce_url(
							add_query_arg(
								array(
									'action' => 'delete-menu-item',
									'menu-item' => $item_id,
								),
								remove_query_arg($removed_args, esc_url(admin_url( 'nav-menus.php' )) )
							),
							'delete-menu_item_' . $item_id
						); ?>"><?php esc_html_e('Remove','videofly'); ?></a> <span class="meta-sep"> | </span> <a class="item-cancel submitcancel" id="cancel-<?php echo vdf_var_sanitize($item_id); ?>" href="<?php	echo add_query_arg( array('edit-menu-item' => $item_id, 'cancel' => time()), remove_query_arg( $removed_args, esc_url(admin_url( 'nav-menus.php' ) )) );
							?>#menu-item-settings-<?php echo vdf_var_sanitize($item_id); ?>">Cancel</a>
					</div>

					<input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo vdf_var_sanitize($item_id); ?>]" value="<?php echo vdf_var_sanitize($item_id); ?>" />
					<input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo vdf_var_sanitize($item_id); ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
					<input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo vdf_var_sanitize($item_id); ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
					<input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo vdf_var_sanitize($item_id); ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
					<input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo vdf_var_sanitize($item_id); ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
					<input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo vdf_var_sanitize($item_id); ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
				</div><!-- .menu-item-settings-->
				<ul class="menu-item-transport"></ul>
			<?php
			$output .= ob_get_clean();
		}
	}


}




if( !function_exists( 'ts_fallback_menu' ) )
{
	/**
	 * Create a navigation out of pages if the user didnt create a menu in the backend
	 *
	 */
	function ts_fallback_menu()
	{
		$current = "";
		$exclude = ts_get_option('frontpage');
		if (is_front_page()){$current = "class='current-menu-item'";}
		if ($exclude) $exclude ="&exclude=".$exclude;

		echo "<div class='fallback_menu av-main-nav-wrap'>";
		echo "<ul class='ts_is_mega menu av-main-nav'>";
		echo "<li $current><a href='". esc_url( home_url('/') ) . "'>".esc_html__('Home','videofly')."</a></li>";
		wp_list_pages('title_li=&sort_column=menu_order'.$exclude);
		echo apply_filters('avf_fallback_menu_items', "", 'fallback_menu');
		echo "</ul></div>";
	}
}


