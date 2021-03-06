<?php 
/**
* Element
*/
class Element
{
	static public $element_types = array(
		'logo',
		'user',
		'social-buttons',
		'searchbox',
		'menu',
		'sidebar',
		'slider',
		'list-portfolios',
		'testimonials',
		'last-posts',
		'list-galleries',
		'callaction',
		'teams',
		'advertising',
		'empty',
		'delimiter',
		'image',
		'video',
		'filters',
		'listed-features',
		'clients',
		'features-block',
		'facebook-block',
		'image-carousel',
		'spacer',
		'counters',
		'page',
		'post',
		'buttons',
		'pricing-tables',
		'icon',
		'list-products',
		'contact-form',
		'featured-area',
		'shortcodes',
		'textarea',
		'map',
		'banner',
		'toggle',
		'tab',
		'list-videos',
		'latest-custom-posts',
		'breadcrumbs',
		'ribbon',
		'timeline',
		'cart',
		'video-carousel',
		'count-down',
		'powerlink',
		'calendar',
		'events',
		'alert',
		'skills',
		'accordion',
		'chart',
		'featured-article',
		'gallery',
		'nona',
		'boca',
		'instance'
	);

	/**
	 * Prepare an element for JS Layout Juilder 
	 * @param  array $element
	 * @return string
	 */
	public static function html ( $element )
	{
		$attributes = array();
		
		array_push( $attributes, 'data-element-type="' . $element['type'] . '"' );
		$admin_label = '';

		if (is_array($element)) {
			foreach ($element as $attr => $attr_value) {
				if ($attr !== 'columns' && $attr !== 'type') {
					array_push( $attributes, 'data-' . $attr . '="' . stripslashes(esc_attr($attr_value)) . '"' );
				}
				if( $attr  == 'admin-label' ){
					$admin_label = $attr_value;
				}
			}
		}

		$attributes = implode( ' ', $attributes );
		$option = (isset($element['option'])) ? $element['options'] : '';	
		return '<li ' . $attributes . '>
					<i class="element-icon ' . self::get_element_icons($element['type']) . '"></i>
					<span class="element-name">' . self::descriptions($element['type'], $admin_label). $option .'</span>
				 	<span class="edit icon-edit" data-tooltip="Edit this element">'. esc_html__('Edit', 'videofly') .'</span>
					<span class="delete icon-delete" data-tooltip="Remove this element"></span>
					<span class="clone icon-beaker" data-tooltip="'.esc_html__('Clone this element', 'videofly').'"></span>
				</li>';
	}
	/**
	 * Validate element type an size
	 * @param  array $element
	 * @return boolean
	 */
	public static function base_validation( $element )
	{
		if ( is_array( $element ) && ! empty( $element ) ) {
			
			$type = array_key_exists('type', $element) ? $element['type'] : false;
			$size = array_key_exists('columns', $element) ? (int)$element['columns'] : false;

			if (!array_key_exists($type, self::$element_types)) {
				return false;
			}

			if ($type && ( $size <=12 || $size >=1 )) {
				return true;
			} else {
				return false;
			}

		} else {
			return false;
		}
		die();
	}

	public static function descriptions($element = '', $admin_label = '')
	{
		if( $admin_label != '' ) return $admin_label;
		switch ($element) {
			case 'logo':
				return esc_html__('Logo', 'videofly');
				break;

			case 'user':
				return esc_html__('User', 'videofly');
				break;

			case 'social-buttons':
				return esc_html__('Social buttons', 'videofly');
				break;

			case 'searchbox':
				return esc_html__('Search', 'videofly');
				break;

			case 'menu':
				return esc_html__('Menu', 'videofly');
				break;

			case 'sidebar':
				return esc_html__('Sidebar', 'videofly');
				break;

			case 'slider':
				return esc_html__('Slider', 'videofly');
				break;

			case 'list-portfolios':
				return esc_html__('List Portfolios', 'videofly');
				break;

			case 'listed-features':
				return esc_html__('Listed Features', 'videofly');
				break;

			case 'features-block':
				return esc_html__('Icon box', 'videofly');
				break;

			case 'facebook-block':
				return esc_html__('Facebook Like Box', 'videofly');
				break;

			case 'testimonials':
				return esc_html__('Testimonials', 'videofly');
				break;

			case 'last-posts':
				return esc_html__('List posts', 'videofly');
				break;

			case 'list-galleries':
				return esc_html__('List Galleries', 'videofly');
				break;

			case 'latest-custom-posts':
				return esc_html__('Latest custom posts', 'videofly');
				break;

			case 'latest-custom-posts':
				return esc_html__('Latest custom posts', 'videofly');
				break;

			case 'callaction':
				return esc_html__('Call to action', 'videofly');
				break;

			case 'image-coverflow':
				return esc_html__('Image carousel', 'videofly');
				break;

			case 'list-products':
				return esc_html__('List products', 'videofly');
				break;

			case 'teams':
				return esc_html__('Teams', 'videofly');
				break;

			case 'pricing-tables':
				return esc_html__('Pricing tables', 'videofly');
				break;

			case 'advertising':
				return esc_html__('Advertising', 'videofly');
				break;

			case 'empty':
				return esc_html__('Empty', 'videofly');
				break;

			case 'delimiter':
				return esc_html__('Delimiter', 'videofly');
				break;

			case 'title':
				return esc_html__('Title', 'videofly');
				break;

			case 'image':
				return esc_html__('Image', 'videofly');
				break;

			case 'video':
				return esc_html__('Video', 'videofly');
				break;
			
			case 'filters':
				return esc_html__('Filters', 'videofly');
				break;

			case 'page':
				return esc_html__('Page', 'videofly');
				break;

			case 'spacer':
				return esc_html__('Spacer', 'videofly');
				break;

			case 'counters':
				return esc_html__('Counters', 'videofly');
				break;

			case 'clients':
				return esc_html__('Clients block', 'videofly');
				break;	

			case 'contact-form':
				return esc_html__('Contact form', 'videofly');
				break;

			case 'buttons':
				return esc_html__('Button', 'videofly');
				break;
			case 'icon':
				return esc_html__('Icon', 'videofly');
				break;

			case 'post':
				return esc_html__('Post', 'videofly');
				break;

			case 'buttons':
				return esc_html__('Button', 'videofly');
				break;

			case 'contact-form':
				return esc_html__('Contact form', 'videofly');
				break;

			case 'featured-area':
				return esc_html__('Featured area', 'videofly');
				break;

			case 'shortcodes':
				return esc_html__('Shortcodes', 'videofly');
				break;

			case 'text':
				return esc_html__('Text', 'videofly');
				break;

			case 'map':
				return esc_html__('Map', 'videofly');
				break;

			case 'banner':
				return esc_html__('Banner', 'videofly');
				break;

			case 'toggle':
				return esc_html__('Toggle', 'videofly');
				break;

			case 'image-carousel':
				return esc_html__('Image carousel', 'videofly');
				break;

			case 'tab':
				return esc_html__('Tabs', 'videofly');
				break;

			case 'list-videos':
				return esc_html__('List Videos', 'videofly');
				break;		

			case 'cart':
				return esc_html__('Shopping cart', 'videofly');
				break;

			case 'breadcrumbs':
				return esc_html__('Breadcrumbs', 'videofly');
				break;

			case 'ribbon':
				return esc_html__('Ribbon banner', 'videofly');
				break;

			case 'timeline':
				return esc_html__('Timeline features', 'videofly');
				break;

			case 'video-carousel':
				return esc_html__('Video carousel', 'videofly');
				break;

			case 'count-down':
				return esc_html__('Counter down', 'videofly');
				break;

			case 'powerlink':
				return esc_html__('Powerlink', 'videofly');
				break;

			case 'calendar':
				return esc_html__('Calendar', 'videofly');
				break;

			case 'events':
				return esc_html__('Events', 'videofly');
				break;

			case 'alert':
				return esc_html__('Alert', 'videofly');
				break;

			case 'skills':
				return esc_html__('Horizontal skills', 'videofly');
				break;

			case 'accordion':
				return esc_html__('Article accordion', 'videofly');
				break;

			case 'chart':
				return esc_html__('Chart', 'videofly');
				break;

			case 'gallery':
				return esc_html__('Gallery', 'videofly');
				break;

			case 'featured-article':
				return esc_html__('Featured article', 'videofly');
				break;

			case 'boca':
				return esc_html__('Post slider boca', 'giselle');
				break;

			case 'instance':
				return esc_html__('Instance', 'giselle');
				break;

			case 'nona':
				return esc_html__('Post nona slider', 'giselle');
				break;

			default:
				return '';
				break;
		}
	}

	public static function get_element_icons($element = '')
	{

		switch ($element) {

			case 'logo':
				return 'icon-logo';
				break;

			case 'user':
				return 'icon-login';
				break;

			case 'social-buttons':
				return 'icon-social';
				break;

			case 'searchbox':
				return 'icon-search';
				break;

			case 'menu':
				return 'icon-menu';
				break;

			case 'sidebar':
				return 'icon-sidebar';
				break;

			case 'slider':
				return 'icon-desktop';
				break;

			case 'list-portfolios':
				return 'icon-briefcase';
				break;

			case 'icon':
				return 'icon-flag';
				break;

			case 'clients':
				return 'icon-clients';
				break;

			case 'testimonials':
				return 'icon-comments';
				break;

			case 'last-posts':
				return 'icon-view-mode';
				break;

			case 'list-galleries':
				return 'icon-gallery';
				break;

			case 'featured-article':
				return 'icon-featured-article';
				break;

			case 'latest-custom-posts':
				return 'icon-window';
				break;

			case 'callaction':
				return 'icon-direction';
				break;

			case 'facebook-block':
				return 'icon-facebook';
				break;

			case 'list-products':
				return 'icon-basket';
				break;

			case 'teams':
				return 'icon-team';
				break;

			case 'advertising':
				return 'icon-money';
				break;

			case 'empty':
				return 'icon-empty';
				break;

			case 'delimiter':
				return 'icon-delimiter';
				break;

			case 'listed-features':
				return 'icon-list';
				break;

			case 'features-block':
				return 'icon-tick';
				break;

			case 'pricing-tables':
				return 'icon-dollar';
				break;

			case 'title':
				return 'icon-font';
				break;

			case 'image':
				return 'icon-image';
				break;

			case 'video':
				return 'icon-movie';
				break;

			case 'filters':
				return 'icon-filter';
				break;

			case 'page':
				return 'icon-post';
				break;

			case 'spacer':
				return 'icon-resize-vertical';
				break;

			case 'counters':
				return 'icon-time';
				break;

			case 'contact-form':
				return 'icon-mail';
				break;

			case 'buttons':
				return 'icon-button';
				break;

			case 'post':
				return 'icon-post';
				break;

			case 'contact-form':
				return 'icon-mail';
				break;

			case 'featured-area':
				return 'icon-featured-area';
				break;

			case 'shortcodes':
				return 'icon-code';
				break;

			case 'text':
				return 'icon-text';
				break;
			case 'image-carousel':
				return 'icon-coverflow';
				break;

			case 'map':
				return 'icon-pin';
				break;

			case 'banner':
				return 'icon-link-ext';
				break;

			case 'toggle':
				return 'icon-resize-full';
				break;

			case 'tab':
				return 'icon-tabs';
				break;

			case 'list-videos':
				return 'icon-movie';
				break;		

			case 'cart':
				return 'icon-basket';
				break;

			case 'breadcrumbs':
				return 'icon-code';
				break;

			case 'ribbon':
				return 'icon-ribbon';
				break;

			case 'timeline':
				return 'icon-parallel';
				break;

			case 'video-carousel':
				return 'icon-coverflow';
				break;
			
			case 'count-down':
				return 'icon-megaphone';
				break;

			case 'powerlink':
				return 'icon-ticket';
				break;

			case 'calendar':
				return 'icon-calendar';
				break;
			
			case 'events':
				return 'icon-text';
				break;

			case 'alert':
				return 'icon-attention';
				break;

			case 'skills':
				return 'icon-pencil-alt';
				break;

			case 'accordion':
				return 'icon-clipboard';
				break;

			case 'chart':
				return 'icon-chart';
				break;

			case 'gallery':
				return 'icon-gallery';
				break;

			case 'boca':
				return 'icon-empty';
				break;

			case 'instance':
				return 'icon-analysis';
				break;

			case 'nona':
				return 'icon-empty';
				break;

			default:
				return 'icon-empty';
				break;
		}
	}

	/**
	 * Element validation
	 * @param  array  $element
	 * @return mixed
	 */
	public static function validate( $element )
	{

		switch ($element['type']) {
			case 'logo':
				return self::parse_logo( $element );
				break;

			case 'user':
				return self::parse_user( $element );
				break;

			case 'social-buttons':
				return self::parse_social_buttons( $element );
				break;

			case 'searchbox':
				return self::parse_searchbox( $element );
				break;

			case 'menu':
				return self::parse_menu( $element );
				break;

			case 'sidebar':
				return self::parse_sidebar( $element );
				break;

			case 'image-carousel':
				return self::parse_image_carousel( $element );
				break;		

			case 'slider':
				return self::parse_slider( $element );
				break;		

			case 'list-portfolios':
				return self::parse_list_portfolios( $element );
				break;

			case 'testimonials':
				return self::parse_testimonials( $element );
				break;

			case 'last-posts':
				return self::parse_last_posts( $element );
				break;

			case 'list-galleries':
				return self::parse_list_galleries( $element );
				break;

			case 'featured-article':
				return self::parse_featured_article( $element );
				break;

			case 'latest-custom-posts':
				return self::parse_latest_custom_posts( $element );
				break;

			case 'list-products':
				return self::parse_list_products( $element );
				break;

			case 'listed-features':
				return self::parse_listed_features( $element );
				break;

			case 'clients':
				return self::parse_clients( $element );
				break;

			case 'features-block':
				return self::parse_features_block( $element );
				break;

			case 'facebook-block':
				return self::parse_facebook_block( $element );
				break;	

			case 'callaction':
				return self::parse_callaction( $element );
				break;

			case 'teams':
				return self::parse_teams( $element );
				break;

			case 'pricing-tables':
				return self::parse_pricing_tables( $element );
				break;

			case 'advertising':
				return self::parse_advertising( $element );
				break;

			case 'empty':
				return self::parse_empty( $element );
				break;

			case 'delimiter':
				return self::parse_delimiter( $element );
				break;

			case 'title':
				return self::parse_title( $element );
				break;

			case 'image':
				return self::parse_image( $element );
				break;

			case 'video':
				return self::parse_video( $element );
				break;

			case 'filters':
				return self::parse_filters( $element );
				break;

			case 'page':
				return self::parse_page( $element );
				break;

			case 'spacer':
				return self::parse_spacer( $element );
				break;

			case 'counters':
				return self::parse_counters( $element );
				break;

			case 'icon':
				return self::parse_icon( $element );
				break;

			case 'post':
				return self::parse_post( $element );
				break;

			case 'buttons':
				return self::parse_buttons( $element );
				break;

			case 'contact-form':
				return self::parse_contact_form( $element );
				break;

			case 'featured-area':
				return self::parse_featured_area( $element );
				break;

			case 'shortcodes':
				return self::parse_shortcodes( $element );
				break;

			case 'text':
				return self::parse_text( $element );
				break;

			case 'map':
				return self::parse_map( $element );
				break;

			case 'banner':
				return self::parse_banner( $element );
				break;

			case 'toggle':
				return self::parse_toggle( $element );
				break;

			case 'tab':
				return self::parse_tab( $element );
				break;

			case 'list-videos':
				return self::parse_list_videos( $element );
				break;			

			case 'cart':
				return self::parse_cart( $element );
				break;

			case 'breadcrumbs':
				return self::parse_breadcrumbs( $element );
				break;

			case 'ribbon':
				return self::parse_ribbon( $element );
				break;

			case 'timeline':
				return self::parse_timeline( $element );
				break;

			case 'video-carousel':
				return self::parse_video_carousel( $element );
				break;

			case 'count-down':
				return self::parse_count_down( $element );
				break;

			case 'powerlink':
				return self::parse_powerlink( $element );
				break;

			case 'calendar':
				return self::parse_calendar( $element );
				break;

			case 'events':
				return self::parse_events( $element );
				break;

			case 'alert':
				return self::parse_alert( $element );
				break;

			case 'skills':
				return self::parse_skills( $element );
				break;

			case 'accordion':
				return self::parse_accordion( $element );
				break;

			case 'chart':
				return self::parse_chart( $element );
				break;

			case 'gallery':
				return self::parse_gallery( $element );
				break;

			case 'boca':
			case 'nona':
				return self::parse_boca_nona( $element );
				break;

			case 'instance':
				return self::parse_instance( $element );
				break;

			default:
				return false;
				break;
		}
	}

	public static function parse_logo( $element )
	{
		$whitelist = array(
			'type',
			'logo-align'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );
		return $filtered;
	}

	public static function parse_user( $element )
	{
		$whitelist = array(
			'type',
			'align'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );
		return $filtered;
	}

	public static function parse_social_buttons( $element )
	{
		$whitelist = array(
			'type',
			'admin-label',
			'social-settings',
			'social-align',
			'social-style'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );
		return $filtered;
	}

	public static function parse_searchbox( $element )
	{
		$whitelist = array(
			'type',
			'align',
			'design'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );
		return $filtered;
	}
	public static function parse_listed_features( $element )
	{
		$whitelist = array(
			'type',
			'features',
			'features-align',
			'color-style',
			'admin-label'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );
		return $filtered;
	}

	public static function parse_clients( $element ){
		$whitelist = array(
			'type',
			'clients',
			'elements-per-row',
			'enable-carousel',
			'admin-label'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );
		return $filtered;
	}

	public static function parse_facebook_block( $element ){
		$whitelist = array(
			'type',
			'facebook-url',
			'cover'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );
		return $filtered;
	}

	public static function parse_features_block( $element )
	{
		$whitelist = array(
			'type',
			'features-block',
			'elements-per-row',
			'style',
			'admin-label',
			'gutter'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );
		return $filtered;
	}

	public static function parse_menu( $element )
	{
		$whitelist = array(
			'type',
			'element-style',
			'admin-label',
			'menu-custom',
			'menu-bg-color',
			'menu-text-color',
			'menu-bg-color-hover',
			'menu-text-color-hover',
			'submenu-bg-color',
			'submenu-text-color',
			'submenu-bg-color-hover',
			'submenu-text-color-hover',
			'menu-text-align',
			'uppercase',
			'name',
			'icons',
			'description',
			'font-type',
			'font-name',
			'font-subsets',
			'font-weight',
			'font-style',
			'font-size',
			'font-demo'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		if ( ! in_array( $filtered['element-style'], array('style1', 'style2', 'style3', 'style4', 'style5', 'style6')) ) {
			$filtered['element-style'] = 'style6';
		}
		$filtered['menu-custom'] = $element['menu-custom'];
		$filtered['menu-bg-color'] = $element['menu-bg-color'];
		$filtered['menu-text-color'] = $element['menu-text-color'];
		$filtered['menu-bg-color-hover'] = $element['menu-bg-color-hover'];
		$filtered['menu-text-color-hover'] = $element['menu-text-color-hover'];
		$filtered['submenu-bg-color'] = $element['submenu-bg-color'];
		$filtered['submenu-text-color'] = $element['submenu-text-color'];
		$filtered['submenu-bg-color-hover'] = $element['submenu-bg-color-hover'];
		$filtered['submenu-text-color-hover'] = $element['submenu-text-color-hover'];

		return $filtered;
	}

	public static function parse_sidebar( $element )
	{
		$whitelist = array(
			'type',
			'sidebar-id',
			'sidebar-sticky',
			'admin-label'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );
		$valid_sidebars = ts_get_sidebars();
		$valid_sidebars['main'] = 'Main Sidebar';

		if ( ! array_key_exists( $filtered['sidebar-id'], $valid_sidebars) ) {
			$filtered['sidebar-id'] = 0;
		}

		return $filtered;
	}

	public static function parse_slider( $element )
	{
		$whitelist = array(
			'type',
			'slider-id',
			'admin-label'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );
		$filtered['slider-id'] = (int)$filtered['slider-id'];

		return $filtered;
	}
	public static function parse_image_carousel( $element )
	{
		$whitelist = array(
			'type',
			'carousel-height',
			'images',
			'admin-label'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );
		$filtered['carousel-height'] = $filtered['carousel-height'];
		$filtered['images'] = $filtered['images'];

		return $filtered;
	}

	public static function parse_list_portfolios( $element )
	{
		$whitelist = array(
			'type',
			'category',
			'display-mode',
			'behavior',
			'display-title',
			'show-meta',
			'elements-per-row',
			'posts-limit',
			'elements-per-row',
			'posts-limit',
			'order-by',
			'order-direction',
			'image-split',
			'content-split',
			'related-posts',
			'show-label',
			'special-effects',
			'gutter',
			'admin-label',
			'layout',
			'rows',
			'scroll',
			'pagination',
			'effects-scroll'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );
		return $filtered;
	}

	public static function parse_testimonials( $element )
	{
		$whitelist = array(
			'type',
			'testimonials',
			'elements-per-row',
			'enable-carousel',
			'admin-label'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		return $filtered;
	}

	public static function parse_list_products( $element )
	{
		$whitelist = array(
			'type',
			'category',
			'behavior',
			'elements-per-row',
			'posts-limit',
			'order-by',
			'order-direction',
			'special-effects',
			'gutter',
			'admin-label'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );
		return $filtered;
	}

	public static function parse_last_posts( $element )
	{
		$whitelist = array(
			'type',
			'category',
			'display-mode',
			'behavior',
			'display-title',
			'show-meta',
			'elements-per-row',
			'posts-limit',
			'order-by',
			'order-direction',
			'image-split',
			'content-split',
			'related-posts',
			'show-label',
			'special-effects',
			'gutter',
			'id-exclude',
			'exclude-first',
			'meta-thumbnail',
			'pagination',
			'admin-label',
			'image',
			'rows',
			'scroll',
			'effects-scroll',
			'layout',
			'image-position',
			'featured',
			'excerpt',
			'carousel',
			'show-image'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		if ( !isset( $filtered['order-direction'] ) ) {
			$filtered['order-direction'] = 'desc';
		}
		if ( !isset( $filtered['pagination'] ) ) {
			$filtered['pagination'] = 'n';
		}

		return $filtered;
	}

	public static function parse_list_galleries( $element )
	{
		$whitelist = array(
			'type',
			'category',
			'display-mode',
			'behavior',
			'display-title',
			'show-meta',
			'elements-per-row',
			'posts-limit',
			'order-by',
			'order-direction',
			'image-split',
			'content-split',
			'related-posts',
			'show-label',
			'special-effects',
			'gutter',
			'id-exclude',
			'exclude-first',
			'meta-thumbnail',
			'pagination',
			'admin-label',
			'image',
			'rows',
			'scroll',
			'effects-scroll',
			'layout',
			'image-position',
			'featured',
			'excerpt',
			'carousel',
			'show-image'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		if ( !isset( $filtered['order-direction'] ) ) {
			$filtered['order-direction'] = 'desc';
		}
		if ( !isset( $filtered['pagination'] ) ) {
			$filtered['pagination'] = 'n';
		}

		return $filtered;
	}

	public static function parse_featured_article( $element )
	{
		$whitelist = array(
			'type',
			'post-id',
			'post-title',
			'showImage',
			'showMeta'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );
		return $filtered;
	}

	public static function parse_latest_custom_posts( $element )
	{
		$whitelist = array(
			'type',
			'category',
			'post-type',
			'display-mode',
			'behavior',
			'display-title',
			'show-meta',
			'elements-per-row',
			'posts-limit',
			'order-by',
			'order-direction',
			'image-split',
			'content-split',
			'related-posts',
			'show-label',
			'special-effects',
			'gutter',
			'id-exclude',
			'exclude-first',
			'meta-thumbnail',
			'pagination',
			'admin-label',
			'image',
			'rows',
			'scroll',
			'effects-scroll',
			'layout',
			'image-position',
			'featured',
			'excerpt',
			'carousel',
			'show-image'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		if ( !isset( $filtered['order-direction'] ) ) {
			$filtered['order-direction'] = 'desc';
		}
		if ( !isset( $filtered['pagination'] ) ) {
			$filtered['pagination'] = 'n';
		}

		return $filtered;
	}

	public static function parse_callaction( $element )
	{
		$whitelist = array(
			'type',
			'callaction-text',
			'callaction-link',
			'callaction-button-text',
			'admin-label'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );
		return $filtered;
	}

	public static function parse_teams( $element )
	{
		$whitelist = array(
			'type',
			'category',
			'elements-per-row',
			'posts-limit',
			'remove-gutter',
			'enable-carousel',
			'admin-label',
			'effect',
			'delay'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );
		
		$valid_elements_per_row = array(1, 2, 3, 4, 6);
		$filtered['elements-per-row'] = (int)$filtered['elements-per-row'];
		$filtered['elements-per-row'] = in_array($filtered['elements-per-row'] , $valid_elements_per_row) ?
										$filtered['elements-per-row'] : 3;

		$filtered['posts-limit'] = (int)$filtered['posts-limit'];

		return $filtered;
	}

	public static function parse_pricing_tables( $element )
	{
		$whitelist = array(
			'type',
			'category',
			'elements-per-row',
			'posts-limit',
			'remove-gutter',
			'admin-label',
			'effect',
			'delay'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );
		
		$valid_elements_per_row = array(1, 2, 3, 4, 6);
		$filtered['elements-per-row'] = (int)$filtered['elements-per-row'];
		$filtered['elements-per-row'] = in_array($filtered['elements-per-row'] , $valid_elements_per_row) ?
										$filtered['elements-per-row'] : 3;

		$filtered['posts-limit'] = (int)$filtered['posts-limit'];

		return $filtered;
	}

	public static function parse_advertising( $element )
	{
		$whitelist = array(
			'type',
			'advertising',
			'admin-label'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );
		return $filtered;
	}

	public static function parse_empty( $element )
	{
		$whitelist = array(
			'type'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );
		return $filtered;
	}

	public static function parse_delimiter( $element )
	{
		$whitelist = array(
			'type',
			'delimiter-type',
			'delimiter-color',
			'admin-label'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		$delimiter_types = array(
			'dotsslash',
			'doubleline',
			'lines',
			'squares',
			'gradient',
			'line',
			'iconed icon-close',
			'small-line'
		);
		
		if (!in_array($filtered['delimiter-type'], $delimiter_types)) {
			$filtered['delimiter-type'] = 'line';
		}

		return $filtered;
	}

	public static function parse_title( $element )
	{
		$whitelist = array(
			'title-icon',
			'type',
			'title',
			'title-color',
			'subtitle',
			'subtitle-color',
			'style',
			'size',
			'admin-label',
			'link',
			'target',
			'effect',
			'delay',
			'letter-spacer'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		if ( !isset( $filtered['title-icon'] ) ) {
			$filtered['title-icon'] = '';
		}
		if ( ! isset($filtered['title-color'])) {
			$filtered['title-color'] = '#777';
		}
		if ( ! isset($filtered['subtitle-color'])) {
			$filtered['subtitle-color'] = '#777';
		}

		$styles = array(
			'2lines',
			'simpleleft',
			'lineafter',
			'linerect',
			'leftrect',
			'simplecenter',
			'lineariconcenter',
			'with-subtitle-above',
			'align-right',
            'bottom-decoration',
            'brackets',
            'with-subtitle-over',
            'with-small-line-below'
		);
		
		if ( !in_array( @$filtered['style'], $styles ) ) {
			$filtered['style'] = '2lines';
		}

		$sizes = array(
			'h1',
			'h2',
			'h3',
			'h4',
			'h5',
			'h6'
		);
		
		if ( !in_array( @$filtered['size'], $sizes ) ) {
			$filtered['size'] = 'h2';
		}

		return $filtered;
	}

	public static function parse_image( $element )
	{
		$whitelist = array(
			'type',
			'image-url',
			'align',
			'forward-url',
			'image-target',
			'admin-label',
			'retina',
			'effect',
			'delay'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		return $filtered;
	}

	public static function parse_powerlink( $element )
	{
		$whitelist = array(
			'type',
			'admin-label',
			'image',
			'title',
			'button-text',
			'button-url'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		return $filtered;
	}

	public static function parse_video( $element )
	{
		$whitelist = array(
			'type',
			'embed',
			'lightbox',
			'title',
			'admin-label'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		return $filtered;
	}

	public static function parse_filters( $element )
	{

		$whitelist = array(
			'type',
			'post-type',
			'categories',
			'elements-per-row',
			'posts-limit',
			'order-by',
			'direction',
			'special-effects',
			'gutter',
			'admin-label',
			'meta-thumbnail'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		$post_types = array('post', 'portfolio', 'ts-gallery', 'video');
		$filtered['post-type'] = (in_array($filtered['post-type'], $post_types)) ? $filtered['post-type'] : 'post';

		$valid_columns = array(1, 2, 3, 4, 6);
		if ( ! in_array(@$element['elements-per-row'], $valid_columns)) {
			$options['elements-per-row'] = 3;
		}

		if ( ! in_array( $filtered['order-by'], array('date', 'comments')) ) {
			$filtered['order-by'] = 'date';
		}

		if ( ! in_array( $filtered['direction'], array('asc', 'desc')) ) {
			$filtered['direction'] = 'asc';
		}
		if ( ! in_array( $filtered['special-effects'], array('opacited', 'rotate-in', '3dflip', 'scaler')) ) {
			$filtered['special-effects'] = 'none';
		}
		if ( ! in_array( $filtered['gutter'], array('n', 'y')) ) {
			$filtered['gutter'] = 'n';
		}

		return $filtered;
	}

	public static function parse_spacer( $element ) {

		$whitelist = array(
			'type',
			'height',
			'admin-label'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		if ( ! isset($filtered['height'])) {
			$filtered['height'] = 20;
		} else {
			$filtered['height'] = (int)$filtered['height'];
			if ($filtered['height'] < 0) {
				$filtered['height'] = 20;
			}
		}

		return $filtered;
	}

	public static function parse_counters( $element ) {

		$whitelist = array(
			'type',
			'admin-label',
			'counters-text',
			'counters-precents',
			'counters-text-color',
			'track-bar',
			'track-bar-color',
			'track-bar-icon',
			'effect',
			'delay'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		if ( ! isset($filtered['counters-text'])) {
			$filtered['counters-text'] = '';
		}
		if ( ! isset($filtered['counters-precents'])) {
			$filtered['counters-precents'] = '';
		}
		if ( ! isset($filtered['counters-text-color'])) {
			$filtered['counters-text-color'] = '';
		}

		return $filtered;
	}

	public static function parse_alert( $element ) {

		$whitelist = array(
			'type',
			'admin-label',
			'icon',
			'title',
			'text',
			'background-color',
			'text-color'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		return $filtered;
	}

	public static function parse_map( $element ) {

		$whitelist = array(
			'type',
			'map-address',
			'map-width',
			'map-height',
			'map-latitude',
			'map-longitude',
			'map-type',
			'map-style',
			'map-zoom',
			'map-type-control',
			'map-zoom-control',
			'map-scale-control',
			'map-scroll-wheel',
			'map-draggable-direction',
			'map-marker-icon',
			'map-marker-img',
			'admin-label'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );
		return $filtered;
	}

	public static function parse_icon( $element ) {

		$whitelist = array(
			'type',
			'icon',
			'icon-color',
			'icon-align',
			'icon-size',
			'admin-label'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		if ( ! isset($filtered['icon'])) {
			$filtered['icon'] = 'icon-wp';
		}
		if ( ! isset($filtered['icon-size'])) {
			$filtered['icon-size'] = '24';
		}
		if ( ! isset($filtered['icon-align'])) {
			$filtered['icon-align'] = 'left';
		}
		if ( ! isset($filtered['icon-color'])) {
			$filtered['icon-color'] = '#000';
		}

		return $filtered;
	}

	public static function parse_page( $element )
	{
		$whitelist = array(
			'type',
			'post-id',
			'search',
			'criteria',
			'order-by',
			'direction'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		$filtered['post-id'] = (int)$filtered['post-id'];
		$filtered['post-id'] = $filtered['post-id'] > 0 ? $filtered['post-id'] : 0;

		if ( ! in_array( $filtered['criteria'], array('title', 'title-content')) ) {
			$filtered['criteria'] = 'title';
		}

		if ( ! in_array( $filtered['order-by'], array('id', 'date')) ) {
			$filtered['order-by'] = 'id';
		}

		if ( ! in_array( $filtered['direction'], array('asc', 'desc')) ) {
			$filtered['direction'] = 'asc';
		}

		return $filtered;
	}

	public static function parse_post( $element )
	{
		$whitelist = array(
			'type',
			'post-id',
			'search',
			'criteria',
			'order-by',
			'direction'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		$filtered['post-id'] = (int)$filtered['post-id'];
		$filtered['post-id'] = $filtered['post-id'] > 0 ? $filtered['post-id'] : 0;

		if ( ! in_array( $filtered['criteria'], array('title', 'title-content')) ) {
			$filtered['criteria'] = 'title';
		}

		if ( ! in_array( $filtered['order-by'], array('id', 'date')) ) {
			$filtered['order-by'] = 'id';
		}

		if ( ! in_array( $filtered['direction'], array('asc', 'desc')) ) {
			$filtered['direction'] = 'asc';
		}
		
		return $filtered;
	}

	public static function parse_buttons( $element )
	{
		$whitelist = array(
			'button-icon',
			'button-align',
			'icon-align',
			'type',
			'text',
			'target',
			'size',
			'text-color',
			'bg-color',
			'url',
			'admin-label',
			'mode-display',
			'border-color',
			'effect',
			'delay',
			'text-hover-color',
			'border-hover-color',
			'bg-hover-color'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		$valid_sizes = array( 'big', 'medium', 'small', 'xsmall');

		if ( ! isset($filtered['button-icon'])) {
			$filtered['button-icon'] = '';
		}

		if ( ! in_array($filtered['size'], $valid_sizes)) {
			$filtered['size'] = 'medium';
		}

		$valid_targets = array( '_blank', '_self' );

		if ( ! in_array($filtered['target'], $valid_targets)) {
			$filtered['target'] = '_self';
		}

		if (trim($filtered['text-color']) === '') {
			$filtered['text-color'] = '#FFF';
		}
		
		if (trim($filtered['bg-color']) === '') {
			$filtered['bg-color'] = '#EB593C';
		}

		$filtered['text-color'] = esc_attr($filtered['text-color']);
		$filtered['bg-color'] = esc_attr($filtered['bg-color']);
		$filtered['url'] = esc_url($filtered['url']);

		return $filtered;
	}

	public static function parse_contact_form( $element )
	{
		$whitelist = array(
			'type',
			'hide-icon',
			'hide-subject',
			'admin-label',
			'contact-form'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		if ( ! in_array( $filtered['hide-icon'], array('0', '1')) ) {
			$filtered['hide-icon'] = '0';
		}

		if ( ! in_array( $filtered['hide-subject'], array('0', '1')) ) {
			$filtered['hide-subject'] = '0';
		}
		
		return $filtered;
	}

	public static function parse_featured_area( $element )
	{
		$whitelist = array(
			'type',
			'category',
			'admin-label',
			'order-by',
			'order-direction',
			'style',
			'custom-post',
			'exclude-first',
			'play',
			'posts-per-page'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );
		
		return $filtered;
	}

	public static function parse_shortcodes( $element ) {

		$whitelist = array(
			'type',
			'admin-label',
			'paddings',
			'shortcodes'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		return $filtered;
	}

	public static function parse_text( $element ) {

		$whitelist = array(
			'type',
			'text',
			'admin-label',
			'effect',
			'delay'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );
		$filtered['text'] = (isset($filtered['text'])) ? $filtered['text'] : ''; 
		return $filtered;
	}

	public static function parse_banner( $element ) {

		$whitelist = array(
			'type',
			'banner-image',
			'banner-title',
			'banner-subtitle',
			'banner-button-title',
			'banner-button-url',
			'banner-button-background',
			'banner-font-color',
			'banner-text-align',
			'banner-height',
			'admin-label',
			'button-text-color'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		return $filtered;
	}

	public static function parse_toggle( $element ) {

		$whitelist = array(
			'type',
			'toggle-title',
			'toggle-description',
			'toggle-state',
			'admin-label'
		
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		return $filtered;
	}

	public static function parse_tab( $element ) {

		$whitelist = array(
			'type',
			'tab',
			'admin-label',
			'mode'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		return $filtered;
	}

	public static function parse_video_carousel( $element ) {

		$whitelist = array(
			'type',
			'source',
			'video-carousel',
			'admin-label'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		return $filtered;
	}

	public static function parse_count_down( $element ) {

		$whitelist = array(
			'type',
			'admin-label',
			'title',
			'date',
			'hours',
			'style'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		return $filtered;
	}

	public static function parse_list_videos( $element )
	{
		$whitelist = array(
			'type',
			'category',
			'display-mode',
			'behavior',
			'display-title',
			'show-meta',
			'elements-per-row',
			'posts-limit',
			'order-by',
			'order-direction',
			'image-split',
			'content-split',
			'related-posts',
			'show-label',
			'special-effects',
			'gutter',
			'id-exclude',
			'exclude-first',
			'meta-thumbnail',
			'pagination',
			'admin-label',
			'image',
			'rows',
			'scroll',
			'effects-scroll',
			'layout',
			'featured',
			'image-position',
			'excerpt',
			'carousel',
			'show-image',
			'play'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		if ( !isset( $filtered['order-direction'] ) ) {
			$filtered['order-direction'] = 'desc';
		}
		if ( !isset( $filtered['pagination'] ) ) {
			$filtered['pagination'] = 'n';
		}

		return $filtered;
	}

	public static function parse_events( $element )
	{
		$whitelist = array(
			'type',
			'admin-label',
			'category',
			'posts-limit',
			'order-by',
			'order-direction',
			'special-effects',
			'id-exclude',
			'exclude-first',
			'pagination'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		return $filtered;
	}

	public static function parse_cart( $element )
	{
		$whitelist = array(
			'type',
			'cart-align'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );
		return $filtered;
	}

	public static function parse_breadcrumbs( $element )
	{
		$whitelist = array(
			'type'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );
		return $filtered;
	}

	public static function parse_ribbon( $element )
	{
		$whitelist = array(
			'type',
			'admin-label',
			'title',
			'text',
			'text-color',
			'background',
			'align',
			'button-icon',
			'button-align',
			'button-type',
			'button-size',
			'button-text',
			'button-target',
			'button-background-color',
			'button-url',
			'button-mode-display',
			'button-border-color',
			'image',
			'button-text-color'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );
		return $filtered;
	}

	public static function parse_timeline( $element ) {

		$whitelist = array(
			'type',
			'admin-label',
			'timeline'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		return $filtered;
	}
	
	public static function parse_calendar( $element ) {

		$whitelist = array(
			'type',
			'admin-label',
			'size'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		return $filtered;
	}

	public static function parse_skills( $element ) {

		$whitelist = array(
			'type',
			'admin-label',
			'display-mode',
			'skills'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		return $filtered;
	}

	public static function parse_accordion( $element ) {

		$whitelist = array(
			'type',
			'admin-label',
			'posts-type',
			'category',
			'featured',
			'nr-of-posts',
			'order-by',
			'order-direction'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		return $filtered;
	}

	public static function parse_chart( $element ) {

		$whitelist = array(
			'type',
			'admin-label',
			'mode',
			'label',
			'title',
			'scaleShowGridLines',
			'scaleGridLineColor',
			'scaleGridLineWidth',
			'scaleShowHorizontalLines',
			'scaleShowVerticalLines',
			'bezierCurve',
			'bezierCurveTension',
			'pointDot',
			'pointDotRadius',
			'pointDotStrokeWidth',
			'pointHitDetectionRadius',
			'datasetStroke',
			'datasetStrokeWidth',
			'datasetFill',
			'chart_line',
			'segmentShowStroke',
			'segmentStrokeColor',
			'segmentStrokeWidth',
			'percentageInnerCutout',
			'animationSteps',
			'animateRotate',
			'animateScale',
			'chart_pie'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		return $filtered;
	}

	public static function parse_gallery( $element ) {

		$whitelist = array(
			'type',
			'admin-label',
			'images'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		return $filtered;
	}

	public static function parse_boca_nona( $element ) {

		$whitelist = array(
			'type',
			'admin-label',
			'custom-post',
			'category',
			'orderby',
			'order',
			'featured',
			'posts_per_page'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		return $filtered;
	}

	public static function parse_instance( $element ) {

		$whitelist = array(
			'type',
			'admin-label',
			'title',
			'image',
			'align',
			'button-url',
			'button-text',
			'button-target',
			'content'
		);

		$filtered = array_intersect_key( $element, array_flip( $whitelist ) );

		return $filtered;
	}
}
?>
