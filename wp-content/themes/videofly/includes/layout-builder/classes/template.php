<?php

class Template
{

	/**
	 * Template structure validation
	 * @param  array  $structure
	 * @return boolean
	 */
	public static function validate_template_structure( $post = array() )
	{

		if ( isset( $post['content'] )) {
			if (is_array($post['content'])) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public static function get_column_size( $size = 2 )
	{
		switch( $size ) {
			case 2:
				$size = '1/6';
				break;
			case 3:
				$size = '1/4';
				break;
			case 4:
				$size = '1/3';
				break;
			case 5:
				$size = '5/12';
				break;
			case 6:
				$size = '1/2';
				break;
			case 7:
				$size = '7/12';
				break;
			case 8:
				$size = '2/3';
				break;
			case 9:
				$size = '3/4';
				break;
			case 10:
				$size = '5/6';
				break;
			case 11:
				$size = '11/12';
				break;
			case 12:
				$size = '12/12';
				break;
			default:
				$size = '';
		}

		return $size;
	}
	/**
	 * Validate row settings
	 * @param  array  $settings
	 * @return array
	 */
	public static function validate_row_settings($settings)
	{
		$filtred_settings = array(
			'rowName' => '',
			'bgColor' => '',
			'textColor' => '',
			'rowMaskColor' => '',
			'rowMask' => '',
			'rowShadow' => '',
			'bgImage' => '',
			'bgVideoMp' => '',
			'bgVideoWebm' => '',
			'bgPositionX' => '',
			'bgPositionY' => '',
			'bgAttachement' => '',
			'bgRepeat' => '',
			'bgSize' => '',
			'rowMarginTop' => '',
			'rowMarginBottom' => '',
			'rowPaddingTop' => '',
			'rowPaddingBottom' => '',
			'expandRow' => '',
			'specialEffects' => '',
			'rowTextAlign' => '',
			'fullscreenRow' => '',
			'rowVerticalAlign' => '',
			'customCss' => '',
			'rowParallax' => '',
			'scrollDownButton' => '',
			'borderTop' => '',
			'borderBottom' => '',
			'borderTopColor' => '',
			'borderBottomColor' => '',
			'borderTopWidth' => '',
			'borderBottomWidth' => '',
			'rowCarousel' => '',
			'gradientMaskMode' => '',
			'rowMaskGradient' => '',
			'transparent' => ''
		);

		$positions     = array('left', 'center', 'right', 'bottom');
		$attachements  = array('fixed', 'scroll');
		$bgSizes  = array('auto', 'cover');
		$repetitions   = array('repeat', 'no-repeat', 'repeat-x', 'repeat-y');

		$valid_effects = array(
			'none',
			'slideup',
			'perspective-x',
			'perspective-y',
			'opacited',
			'slideright',
			'slideleft'
		);
		$valid_text_align = array(
			'auto',
			'left',
			'center',
			'right'
		);
		$valid_fullscreen_row = array(
			'no',
			'yes'
		);

		$valid_row_mask = array(
			'no',
			'yes',
			'gradient'
		);

		$valid_row_shadow = array(
			'no',
			'yes'
		);

		$valid_row_vertical_align = array(
			'top',
			'middle',
			'bottom'
		);

		if (is_array($settings))
		{
			$filtred_settings['rowName'] = esc_attr($settings['rowName']);
			$filtred_settings['bgColor'] = preg_match('/^#[a-f0-9]{6}$/i', $settings['bgColor']) ? $settings['bgColor'] : 'transparent';
			$filtred_settings['textColor'] = preg_match('/^#[a-f0-9]{6}$/i', $settings['textColor']) ? $settings['textColor'] : 'inherit';
			$filtred_settings['rowMaskColor'] = preg_match('/^#[a-f0-9]{6}$/i', $settings['rowMaskColor']) ? $settings['rowMaskColor'] : 'inherit';
			$filtred_settings['rowOpacity'] = (isset($settings['rowOpacity'])) ? (int)$settings['rowOpacity'] : '';
			$filtred_settings['bgImage'] = isset($settings['bgImage']) ? esc_attr($settings['bgImage']) : '';
			$filtred_settings['bgVideoMp'] = isset($settings['bgVideoMp']) ? esc_attr($settings['bgVideoMp']) : '';
			$filtred_settings['bgVideoWebm'] = isset($settings['bgVideoWebm']) ? esc_attr($settings['bgVideoWebm']) : '';

			$filtred_settings['bgPositionX'] = isset($settings['bgPositionX']) ? $settings['bgPositionX'] : 'center';

			$filtred_settings['bgPositionY'] = isset($settings['bgPositionY']) ? $settings['bgPositionY'] : 'center';

			$filtred_settings['bgAttachement'] = isset($settings['bgAttachement']) ?
											esc_attr($settings['bgAttachement']) : '';

			$filtred_settings['bgAttachement'] = in_array($filtred_settings['bgAttachement'], $attachements) ?
										$filtred_settings['bgAttachement'] : '';

			$filtred_settings['bgRepeat'] = isset($settings['bgRepeat']) ? esc_attr($settings['bgRepeat']) : '';

			$filtred_settings['bgRepeat'] = in_array($settings['bgRepeat'], $repetitions ) ?
										$settings['bgRepeat'] : 'no-repeat';

			$filtred_settings['bgSize'] = isset($settings['bgSize']) ? esc_attr($settings['bgSize']) : '';

			$filtred_settings['bgSize'] = in_array($settings['bgSize'], $bgSizes ) ?
										$settings['bgSize'] : 'auto';

			$filtred_settings['rowMarginTop'] = isset($settings['rowMarginTop']) ?
										esc_attr((int)$settings['rowMarginTop']) : '0';

			$filtred_settings['rowMarginBottom'] = isset($settings['rowMarginBottom']) ?
										esc_attr((int)$settings['rowMarginBottom']) : '0';

			$filtred_settings['rowPaddingTop'] = isset($settings['rowPaddingTop']) ?
										esc_attr((int)$settings['rowPaddingTop']) : '0';

			$filtred_settings['rowPaddingBottom'] = isset($settings['rowPaddingBottom']) ?
										esc_attr((int)$settings['rowPaddingBottom']) : '0';

			$filtred_settings['expandRow'] = isset($settings['expandRow']) ?
										$settings['expandRow'] : 'no';

			$filtred_settings['expandRow'] = in_array($filtred_settings['expandRow'], array('yes', 'no')) ?
										$filtred_settings['expandRow'] : 'no';

			$filtred_settings['specialEffects'] = isset($settings['specialEffects']) ?
										esc_attr($settings['specialEffects']) : 'none';

			$filtred_settings['specialEffects'] = in_array($filtred_settings['specialEffects'], $valid_effects) ?
										$filtred_settings['specialEffects'] : 'none';

			$filtred_settings['rowTextAlign'] = isset($settings['rowTextAlign']) ?
										esc_attr($settings['rowTextAlign']) : 'auto';

			$filtred_settings['rowTextAlign'] = in_array($filtred_settings['rowTextAlign'], $valid_text_align) ?
										$filtred_settings['rowTextAlign'] : 'auto';

			$filtred_settings['fullscreenRow'] = isset($settings['fullscreenRow']) ?
										esc_attr($settings['fullscreenRow']) : 'no';

			$filtred_settings['fullscreenRow'] = in_array($filtred_settings['fullscreenRow'], $valid_fullscreen_row) ?
										$filtred_settings['fullscreenRow'] : 'no';

			$filtred_settings['rowMask'] = isset($settings['rowMask']) ?
										esc_attr($settings['rowMask']) : 'no';

			$filtred_settings['rowMask'] = in_array($filtred_settings['rowMask'], $valid_row_mask) ?
										$filtred_settings['rowMask'] : 'no';

			$filtred_settings['rowShadow'] = isset($settings['rowShadow']) ?
										esc_attr($settings['rowShadow']) : 'no';

			$filtred_settings['rowShadow'] = in_array($filtred_settings['rowShadow'], $valid_row_mask) ?
										$filtred_settings['rowShadow'] : 'no';

			$filtred_settings['rowVerticalAlign'] = isset($settings['rowVerticalAlign']) ?
										esc_attr($settings['rowVerticalAlign']) : 'top';

			$filtred_settings['rowParallax'] = (isset($settings['rowParallax']) && ($settings['rowParallax'] == 'yes' || $settings['rowParallax'] == 'no')) ? $settings['rowParallax'] : 'no';

			$filtred_settings['scrollDownButton'] = (isset($settings['scrollDownButton']) && ($settings['scrollDownButton'] == 'yes' || $settings['scrollDownButton'] == 'no')) ? $settings['scrollDownButton'] : 'no';

			$filtred_settings['rowVerticalAlign'] = in_array($filtred_settings['rowVerticalAlign'], $valid_row_vertical_align) ?
										$filtred_settings['rowVerticalAlign'] : 'top';
			$filtred_settings['customCss'] = isset($settings['customCss']) ? esc_attr($settings['customCss']) : '';

			/*row border options*/
			$filtred_settings['borderTop'] = (isset($settings['borderTop']) && ($settings['borderTop'] == 'y' || $settings['borderTop'] == 'n')) ? $settings['borderTop'] : 'n';
			$filtred_settings['borderBottom'] = (isset($settings['borderBottom']) && ($settings['borderBottom'] == 'y' || $settings['borderBottom'] == 'n')) ? $settings['borderBottom'] : 'n';
			$filtred_settings['borderTopColor'] = (isset($settings['borderTopColor']) && !empty($settings['borderTopColor'])) ? $settings['borderTopColor'] : '#fff';
			$filtred_settings['borderBottomColor'] = (isset($settings['borderBottomColor']) && !empty($settings['borderBottomColor'])) ? $settings['borderBottomColor'] : '#fff';
			$filtred_settings['borderTopWidth'] = (isset($settings['borderTopWidth']) && (int)$settings['borderTopWidth'] <= 15 && (int)$settings['borderTopWidth'] > 0 ) ? $settings['borderTopWidth'] : '3';
			$filtred_settings['borderBottomWidth'] = (isset($settings['borderBottomWidth']) && (int)$settings['borderBottomWidth'] <= 15 && (int)$settings['borderBottomWidth'] > 0 ) ? $settings['borderBottomWidth'] : '3';
			/*end border options*/

			$filtred_settings['rowCarousel'] = (isset($settings['rowCarousel']) && ($settings['rowCarousel'] == 'yes' || $settings['rowCarousel'] == 'no')) ? $settings['rowCarousel'] : 'no';
			$filtred_settings['sliderBackground'] = (isset($settings['sliderBackground']) && !empty($settings['sliderBackground'])) ? (int)$settings['sliderBackground'] : 'no';

			$filtred_settings['rowMaskGradient'] = (isset($settings['rowMaskGradient']) && !empty($settings['rowMaskGradient'])) ? $settings['rowMaskGradient'] : '#fff';;

			$filtred_settings['gradientMaskMode'] = (isset($settings['gradientMaskMode']) && ($settings['gradientMaskMode'] == 'radial' || $settings['gradientMaskMode'] == 'left-to-right' || $settings['gradientMaskMode'] == 'corner-top' || $settings['gradientMaskMode'] == 'corner-bottom')) ? $settings['gradientMaskMode'] : 'radial';

			$filtred_settings['transparent'] = (isset($settings['transparent']) && ($settings['transparent'] == 'y' || $settings['transparent'] == 'n')) ? $settings['transparent'] : 'y';
		}

		return $filtred_settings;
	}

	/**
	 * Generate data-* attributes for row settings
	 * @param  array  $attr
	 * @return string
	 */
	public static function row_attr( $attr = array() )
	{

		$attributes = array();

		if (is_array($attr) && !empty($attr)) {
			array_push( $attributes, 'data-name-id="' . @$attr['rowName'] . '"' );
			array_push( $attributes, 'data-bg-color="' . @$attr['bgColor'] . '"' );
			array_push( $attributes, 'data-text-color="' . @$attr['textColor'] . '"' );
			array_push( $attributes, 'data-mask-color="' . @$attr['rowMaskColor'] . '"' );
			array_push( $attributes, 'data-opacity="' . @$attr['rowOpacity'] . '"' );
			array_push( $attributes, 'data-bg-image="' . @$attr['bgImage'] . '"' );
			array_push( $attributes, 'data-bg-video-mp="' . @$attr['bgVideoMp'] . '"' );
			array_push( $attributes, 'data-bg-video-webm="' . @$attr['bgVideoWebm'] . '"' );
			array_push( $attributes, 'data-bg-position-x="' . @$attr['bgPositionX'] . '"' );
			array_push( $attributes, 'data-bg-position-y="' . @$attr['bgPositionY'] . '"' );
			array_push( $attributes, 'data-bg-attachment="' . @$attr['bgAttachement'] . '"' );
			array_push( $attributes, 'data-bg-repeat="' . @$attr['bgRepeat'] . '"' );
			array_push( $attributes, 'data-bg-size="' . @$attr['bgSize'] . '"' );

			array_push( $attributes, 'data-margin-top="' . @$attr['rowMarginTop'] . '"' );
			array_push( $attributes, 'data-margin-bottom="' . @$attr['rowMarginBottom'] . '"' );
			array_push( $attributes, 'data-padding-top="' . @$attr['rowPaddingTop'] . '"' );
			array_push( $attributes, 'data-padding-bottom="' . @$attr['rowPaddingBottom'] . '"' );
			array_push( $attributes, 'data-expand-row="' . @$attr['expandRow'] . '"' );
			array_push( $attributes, 'data-special-effects="' . @$attr['specialEffects'] . '"' );
			array_push( $attributes, 'data-text-align="' . @$attr['rowTextAlign'] . '"' );
			array_push( $attributes, 'data-fullscreen-row="' . @$attr['fullscreenRow'] . '"' );
			array_push( $attributes, 'data-mask="' . @$attr['rowMask'] . '"' );
			array_push( $attributes, 'data-shadow="' . @$attr['rowShadow'] . '"' );
			array_push( $attributes, 'data-vertical-align="' . @$attr['rowVerticalAlign'] . '"' );
			array_push( $attributes, 'data-custom-css="' . @$attr['customCss'] . '"' );
			array_push( $attributes, 'data-parallax="' . @$attr['rowParallax'] . '"' );
			array_push( $attributes, 'data-scroll-down-button="' . @$attr['scrollDownButton'] . '"' );
			array_push( $attributes, 'data-border-top="' . @$attr['borderTop'] . '"' );
			array_push( $attributes, 'data-border-bottom="' . @$attr['borderBottom'] . '"' );
			array_push( $attributes, 'data-border-top-color="' . @$attr['borderTopColor'] . '"' );
			array_push( $attributes, 'data-border-bottom-color="' . @$attr['borderBottomColor'] . '"' );
			array_push( $attributes, 'data-border-top-width="' . @$attr['borderTopWidth'] . '"' );
			array_push( $attributes, 'data-border-bottom-width="' . @$attr['borderBottomWidth'] . '"' );
			array_push( $attributes, 'data-carousel="' . @$attr['rowCarousel'] . '"' );
			array_push( $attributes, 'data-slider-background="' . @$attr['sliderBackground'] . '"' );
			array_push( $attributes, 'data-mask-gradient="' . @$attr['rowMaskGradient'] . '"' );
			array_push( $attributes, 'data-mask-gradient-mode="' . @$attr['gradientMaskMode'] . '"' );
			array_push( $attributes, 'data-transparent="' . @$attr['transparent'] . '"' );
		}

		return implode( ' ', $attributes );
	}

	/**
	 * Validate column settings
	 * @param  array  $settings
	 * @return array
	 */
	public static function validate_column_settings($settings)
	{
		$filtred_settings = array(
			'columnName' => '',
			'bgColor' => '',
			'textColor' => '',
			'columnMaskColor' => '',
			'columnMask' => '',
			'columnOpacity' => '',
			'bgImage' => '',
			'bgVideoMp' => '',
			'bgVideoWebm' => '',
			'bgPosition' => '',
			'bgAttachement' => '',
			'bgRepeat' => '',
			'bgSize' => '',
			'columnPaddingTop' => '',
			'columnPaddingRight' => '',
			'columnPaddingLeft' => '',
			'columnPaddingBottom' => '',
			'gutterRight' => '',
			'gutterLeft' => '',
			'columnTextAlign' => '',
			'gradientMaskMode' => '',
			'maskGradient' => '',
			'transparent' => ''
		);

		$positions     = array('left', 'center', 'right');
		$attachements  = array('fixed', 'scroll');
		$bgSizes  = array('auto', 'cover');
		$repetitions   = array('repeat', 'no-repeat', 'repeat-x', 'repeat-y');

		$valid_text_align = array(
			'auto',
			'left',
			'center',
			'right'
		);

		$valid_column_mask = array(
			'no',
			'yes',
			'gradient'
		);

		$valid_column_vertical_align = array(
			'top',
			'middle',
			'bottom'
		);

		if (is_array($settings))
		{
			$filtred_settings['columnName'] = esc_attr($settings['columnName']);
			$filtred_settings['bgColor'] = preg_match('/^#[a-f0-9]{6}$/i', $settings['bgColor']) ? $settings['bgColor'] : 'transparent';
			$filtred_settings['textColor'] = preg_match('/^#[a-f0-9]{6}$/i', $settings['textColor']) ? $settings['textColor'] : 'inherit';
			$filtred_settings['columnMaskColor'] = preg_match('/^#[a-f0-9]{6}$/i', $settings['columnMaskColor']) ? $settings['columnMaskColor'] : 'inherit';
			$filtred_settings['columnOpacity'] = (isset($settings['columnOpacity'])) ? (int)$settings['columnOpacity'] : '';
			$filtred_settings['bgImage'] = isset($settings['bgImage']) ? esc_attr($settings['bgImage']) : '';
			$filtred_settings['bgVideoMp'] = isset($settings['bgVideoMp']) ? esc_attr($settings['bgVideoMp']) : '';
			$filtred_settings['bgVideoWebm'] = isset($settings['bgVideoWebm']) ? esc_attr($settings['bgVideoWebm']) : '';

			$filtred_settings['bgPosition'] = (isset($settings['bgPosition']) && in_array($settings['bgPosition'], $positions)) ? esc_attr($settings['bgPosition']) : 'auto';

			$filtred_settings['bgAttachement'] = (isset($settings['bgAttachement']) && in_array($settings['bgAttachement'], $attachements)) ? esc_attr($settings['bgAttachement']) : '';

			$filtred_settings['bgRepeat'] = (isset($settings['bgRepeat']) && in_array($settings['bgRepeat'], $repetitions )) ? esc_attr($settings['bgRepeat']) : '';

			$filtred_settings['bgSize'] = (isset($settings['bgSize']) && in_array($settings['bgSize'], $bgSizes )) ? esc_attr($settings['bgSize']) : 'auto';

			$filtred_settings['columnPaddingTop'] = isset($settings['columnPaddingTop']) ?
										esc_attr((int)$settings['columnPaddingTop']) : '0';

			$filtred_settings['columnPaddingBottom'] = isset($settings['columnPaddingBottom']) ?
										esc_attr((int)$settings['columnPaddingBottom']) : '0';

			$filtred_settings['columnPaddingLeft'] = isset($settings['columnPaddingLeft']) ?
										esc_attr((int)$settings['columnPaddingLeft']) : '0';

			$filtred_settings['columnPaddingRight'] = isset($settings['columnPaddingRight']) ?
										esc_attr((int)$settings['columnPaddingRight']) : '0';

			$filtred_settings['columnTextAlign'] = (isset($settings['columnTextAlign']) && in_array($settings['columnTextAlign'], $valid_text_align)) ? esc_attr($settings['columnTextAlign']) : 'auto';

			$filtred_settings['columnMask'] = (isset($settings['columnMask']) && in_array($settings['columnMask'], $valid_column_mask)) ? esc_attr($settings['columnMask']) : 'no';
			$filtred_settings['gutterLeft'] = (isset($settings['gutterLeft']) &&  is_numeric($settings['gutterLeft'])) ? $settings['gutterLeft'] : '20';
			$filtred_settings['gutterRight'] = (isset($settings['gutterRight']) && is_numeric($settings['gutterRight'])) ? $settings['gutterRight'] : '20';

			$filtred_settings['maskGradient'] = (isset($settings['maskGradient']) && !empty($settings['maskGradient'])) ? $settings['maskGradient'] : '#ffffff';

			$filtred_settings['gradientMaskMode'] = (isset($settings['gradientMaskMode']) && ($settings['gradientMaskMode'] == 'radial' || $settings['gradientMaskMode'] == 'left-to-right' || $settings['gradientMaskMode'] == 'corner-top' || $settings['gradientMaskMode'] == 'corner-bottom')) ? $settings['gradientMaskMode'] : 'radial';

			$filtred_settings['transparent'] = (isset($settings['transparent']) && ($settings['transparent'] == 'y' || $settings['transparent'] == 'n')) ? $settings['transparent'] : 'y';
		}

		return $filtred_settings;
	}

	/**
	 * Generate data-* attributes for column settings
	 * @param  array  $attr
	 * @return string
	 */
	public static function column_attr( $attr = array() )
	{

		$attributes = array();

		if (is_array($attr) && !empty($attr)) {
			array_push( $attributes, 'data-name-id="' . @$attr['columnName'] . '"' );
			array_push( $attributes, 'data-bg-color="' . @$attr['bgColor'] . '"' );
			array_push( $attributes, 'data-text-color="' . @$attr['textColor'] . '"' );
			array_push( $attributes, 'data-mask-color="' . @$attr['columnMaskColor'] . '"' );
			array_push( $attributes, 'data-opacity="' . @$attr['columnOpacity'] . '"' );
			array_push( $attributes, 'data-bg-image="' . @$attr['bgImage'] . '"' );
			array_push( $attributes, 'data-bg-video-mp="' . @$attr['bgVideoMp'] . '"' );
			array_push( $attributes, 'data-bg-video-webm="' . @$attr['bgVideoWebm'] . '"' );
			array_push( $attributes, 'data-bg-position="' . @$attr['bgPosition'] . '"' );
			array_push( $attributes, 'data-bg-attachment="' . @$attr['bgAttachement'] . '"' );
			array_push( $attributes, 'data-bg-repeat="' . @$attr['bgRepeat'] . '"' );
			array_push( $attributes, 'data-bg-size="' . @$attr['bgSize'] . '"' );

			array_push( $attributes, 'data-padding-top="' . @$attr['columnPaddingTop'] . '"' );
			array_push( $attributes, 'data-padding-bottom="' . @$attr['columnPaddingBottom'] . '"' );
			array_push( $attributes, 'data-padding-left="' . @$attr['columnPaddingLeft'] . '"' );
			array_push( $attributes, 'data-padding-right="' . @$attr['columnPaddingRight'] . '"' );
			array_push( $attributes, 'data-text-align="' . @$attr['columnTextAlign'] . '"' );
			array_push( $attributes, 'data-mask="' . @$attr['columnMask'] . '"' );
			array_push( $attributes, 'data-gutter-left="' . @$attr['gutterLeft'] . '"' );
			array_push( $attributes, 'data-gutter-right="' . @$attr['gutterRight'] . '"' );
			array_push( $attributes, 'data-mask-gradient="' . @$attr['maskGradient'] . '"' );
			array_push( $attributes, 'data-mask-gradient-mode="' . @$attr['gradientMaskMode'] . '"' );
			array_push( $attributes, 'data-transparent="' . @$attr['transparent'] . '"' );
		}

		return implode( ' ', $attributes );
	}

	/**
	 * Return all templates
	 * @param  string $location header/footer/page
	 * @return array
	 */
	public static function get_all_templates( $location = 'header' ) {

		$valid_locations = array('header', 'footer', 'page');

		if ( in_array($location, $valid_locations) ) {
			$templates = get_option('videofly_' . $location . '_templates', array());
			return $templates;
		} else {
			return array();
		}
	}

	public static function load_template( $location = 'header', $template_id = 'default') {

		$data = array(
			'name' => '',
			'elements' => ''
		);

		$valid_locations = array('header', 'footer', 'page');

		if ( in_array($location, $valid_locations) ) {

			$templates = get_option('videofly_' . $location . '_templates', array());

			if ( array_key_exists($template_id, $templates) ) {

				return array(
					'template_id' => $template_id,
					'name' => $templates[$template_id]['name'],
					'elements' => self::visual_editor($templates[$template_id]['elements'])
				);

			} else {
				return $data;
			}
		} else {
			return $data;
		}
	}

	public static function save( $action = 'blank_template', $location = 'header' )
	{
		$valid_actions   = array( 'blank_template', 'save_as', 'update' );
		$valid_locations = array( 'header', 'footer', 'page' );
		$template_id     = 'ts-template-' . time();

		$template_name = isset( $_POST['template_name'] ) ? trim($_POST['template_name']) : '';
		$template_name = $template_name == '' ? esc_html__( 'New template ', 'videofly' ) . date( 'd-m-Y' ) : $template_name;

		if ( in_array( $action, $valid_actions ) && in_array( $location, $valid_locations ) ) {

			if ( $action === 'blank_template' ) {

				$templates = get_option( 'videofly_' . $location . '_templates', array() );

				if ( is_array( $templates ) ) {

					$templates[ $template_id ] = array(
						'name'     => $template_name,
						'elements' => array()
					);

				} else {
					$templates = array();
					$templates[ $template_id ] = array(
						'name'     => $template_name,
						'elements' => array()
					);
				}

				$updated = update_option( 'videofly_' . $location . '_templates', $templates );


			} else if ( $action === 'update' ) {

				$data = isset( $_POST['data'] ) ? json_decode( wp_unslash( $_POST['data'] ), true ) : array();

				$content = isset( $data['content'] ) ? $data['content'] : array();

				$validated_content = self::validate_content( $content );

				if ( isset( $data['post_id'] ) ) {

					update_post_meta((int)$data['post_id'], 'ts_template', $validated_content);

				} else {

					$lang = defined( 'ICL_LANGUAGE_CODE' ) ? '_' . ICL_LANGUAGE_CODE : '';

					$updated = update_option( 'videofly_' . $location . $lang,  $validated_content );

				}

			} else {

				$data = isset( $_POST['data'] ) && ( $data = json_decode( wp_unslash( $_POST['data'] ), true ) ) ? $data : array();

				$validated_content = self::validate_content( $data );

				$template_id = isset( $_POST['template_id'] ) ? $_POST['template_id'] : $template_id;

				$templates = get_option( 'videofly_' . $location . '_templates', array() );

				$templates[ $template_id ] = array(
					'name'     => $template_name,
					'elements' => $validated_content,
				);

				$updated = update_option( 'videofly_' . $location . '_templates', $templates );
			}

			return true;

		} else {

			return false;

		}
	}

	/**
	 * Edit template
	 * @param  string $template_id
	 * @return string
	 */
	public static function edit( $location = 'header' )
	{
		if ( $location === 'header' || $location === 'footer' ) {

			$lang = defined( 'ICL_LANGUAGE_CODE' ) ? '_' . ICL_LANGUAGE_CODE : '';

			$template = get_option( 'videofly_' . $location . $lang );

			if ( empty( $template ) ) {

				if ( ! empty( $lang ) ) {

					$template = get_option( 'videofly_' . $location );

				} else {

					$template_id = get_option( 'videofly_' . $location . '_template_id', 'default' );
					$templates   = get_option( 'videofly_' . $location . '_templates', array() );

					if ( isset( $templates[ $template_id ]['elements'] ) ) {
						$template = $templates[ $template_id ]['elements'];
					}

				}
			}

		} else {

			$template = ( $template = get_post_meta( $location, 'ts_template', true ) ) && is_array( $template ) ? $template : array();

		}

		return self::visual_editor( $template );
	}


	/**
	 * Get tempalte name
	 * @param  string $location Header/Footer/Page
	 * @return string
	 */
	public static function get_template_info($location = 'header', $type = 'id') {

		$template_id = get_option( 'videofly_' . $location . '_template_id', 'default', true );
		$templates = get_option( 'videofly_' . $location . '_templates', array(), true );

		if ($type === 'id') {
			return $template_id;
		} else {
			if (isset($templates[$template_id]['name'])) {
				return $templates[$template_id]['name'];
			} else {
				return esc_html__('Template', 'videofly');
			}
		}
	}

	public static function visual_editor($template = array()) {

		$new_structure = '';

		if ( is_array( $template ) && ! empty( $template ) ) {

			$parsed_rows = array();

			$settingsDefaultColumn = array(
				'columnName' => '',
				'bgColor' => 'transparent',
				'textColor' => 'inherit',
				'columnMaskColor' => '',
				'columnMask' => 'no',
				'bgImage' => '',
				'columnOpacity' => '0',
				'bgVideoMp' => '',
				'bgVideoWebm' => '',
				'bgPosition' => 'left',
				'bgAttachement' => 'fixed',
				'bgRepeat' => 'repeat',
				'bgSize' => 'auto',
				'columnPaddingTop' => '0',
				'columnPaddingRight' => '0',
				'columnPaddingLeft' => '0',
				'columnPaddingBottom' => '0',
				'columnTextAlign' => 'auto',
				'gutterLeft' => '20',
				'gutterRight' => '20'
			);

			// travers tempalte rows
			foreach ($template as $row_id => $row) {

				// checK if we have rows in this section
				if ( is_array(@$row['columns']) && ! empty($row['columns']) ) {

					$row_start = '<ul class="layout_builder_row" '.self::row_attr(@$row['settings']).'>
						<li class="row-editor" >
							<ul class="row-editor-options">
								<li>
									<a href="#" class="add-column">+</a>
									<a href="#" class="predefined-columns"><img src="'.get_stylesheet_directory_uri().'/images/options/columns_layout.png" alt=""></a>
									<ul class="add-column-settings">
									   <li>
		                                   <a href="#" data-add-columns="#dragable-column-tpl"><img src="'.get_stylesheet_directory_uri().'/images/options/columns_layout_column.png" alt=""></a>
		                               </li>
		                               <li>
		                                   <a href="#" data-add-columns="#dragable-column-tpl-half"><img src="'.get_stylesheet_directory_uri().'/images/options/columns_layout_halfs.png" alt=""></a>
		                               </li>
		                               <li>
		                                   <a href="#" data-add-columns="#dragable-column-tpl-thirds"><img src="'.get_stylesheet_directory_uri().'/images/options/columns_layout_thirds.png" alt=""></a>
		                               </li>
		                               <li>
		                                   <a href="#" data-add-columns="#dragable-column-tpl-four-halfs"><img src="'.get_stylesheet_directory_uri().'/images/options/columns_layout_one_four.png" alt=""></a>
		                               </li>
		                               <li>
		                                   <a href="#" data-add-columns="#dragable-column-tpl-one_three"><img src="'.get_stylesheet_directory_uri().'/images/options/columns_layout_one_three.png" alt=""></a>
		                               </li>
		                               <li>
		                                   <a href="#" data-add-columns="#dragable-column-tpl-four-half-four"><img src="'.get_stylesheet_directory_uri().'/images/options/columns_layout_four_half_four.png" alt=""></a>
		                               </li>
									</ul>
								</li>
								<li><a href="#" class="remove-row">'.esc_html__( 'delete', 'videofly' ).'</a></li>
								<li><a href="#" class="move">'.esc_html__( 'move', 'videofly' ).'</a></li>
							</ul>
						</li>
						<li class="edit-row-settings" >
							<a href="#" class="edit-row">'.esc_html__( 'Edit', 'videofly' ).'</a>
						</li>';
					$row_end   = '</ul>';

					$parsed_columns = array();

					// travers each row and parse columns
					foreach ($row['columns'] as $column_index => $column) {

						$columnOptions = (isset($column['settingsColumn'])) ? $column['settingsColumn'] : $settingsDefaultColumn;

						$column_start = '<li data-columns="' . $column['size'] . '" data-type="column" class="columns'.$column['size'].'" ' . self::column_attr($columnOptions) . '>
							<div class="column-header">
								<span class="minus icon-left" data-tooltip="Reduce column size"></span>
								<span class="column-size" data-tooltip="The size of the column within container">'.self::get_column_size($column['size']).'</span>
								<span class="plus icon-right" data-tooltip="' . esc_html__('Add column size', 'videofly') . '"></span>
								<span class="delete-column icon-delete" data-tooltip="' . esc_html__('Remove this column', 'videofly') . '"></span>
								<span class="edit-column icon-edit" data-tooltip="'.esc_html__('Edit this column', 'videofly').'"></span>
								<span class="drag-column icon-drag" data-tooltip="' . esc_html__('Drag this column', 'videofly') . '"></span>
								<span class="clone icon-beaker ts-clone-column" data-tooltip="'.esc_html__('Clone this column', 'videofly').'"></span>
							</div>
							<ul class="elements">';

							$column_end = '</ul><span class="add-element">'.esc_html__('Add element', 'videofly').'</span>
						</li>';
						$elements = '';

						// check if row is not empty
						if (is_array($column['elements']) && !empty($column['elements']) ) {
							foreach ($column['elements'] as $element_index => $element) {
								$elements .= "\n" . Element::html( $element, 'edit', 'delete', 'template' );
							}
						}

						$parsed_columns[] = $column_start . "\n" . $elements . "\n" . $column_end . "\n";
					}

					$parsed_rows[] = $row_start . implode("\n", $parsed_columns) . $row_end;
				}
			}

			$new_structure = implode("\n", $parsed_rows);
		}

		return $new_structure;
	}

	/**
	 * Get template structure
	 * @param  string $post_id
	 * @return array
	 */
	public static function get_structure( $post_id = 0 )
	{
		$template = get_post_meta( $post_id, 'ts_template', true );
		return ( $template ) ? $template : array();
	}


	public static function validate_content($content = array()) {

		$validated_content = array();

		if ($content) {
			// traversing rows

			foreach ($content as $row_id => $row) {

				// if row is not empty
				if ( is_array( $row ) && ! empty( $row ) ) {

					$filtered_row = array(
						'settings' => array(),
						'columns' => array()
					);

					// validate row settings
					$settings = (@is_array($row['settings'])) ? $row['settings'] : array();
					$filtered_row['settings'] = self::validate_row_settings( $settings );

					$filtered_columns = array();

					if (isset($row['columns']) && is_array($row['columns'])) {

						// traversing columns
						foreach ( $row['columns'] as $column_id => $column ) {

							$filtered_elements = array();
							if ( is_array( $column ) && ! empty( $column ) ) {

								if (@$column['elements']) {
									foreach ( $column['elements'] as $element_index => $element ) {
										$e = Element::validate( $element );

										// if element is valid then push it to the $filtered_elements
										if ( $e ) {
											$filtered_elements[$element_index] = $e;
										}
									}
								}
							}

							$filtered_columns[$column_id]['size'] = (int)$column['size'];
							$filtered_columns[$column_id]['elements'] = $filtered_elements;
							$column_settings = (isset($column['settingsColumn']) && is_array($column['settingsColumn'])) ? $column['settingsColumn'] : array();

							$filtered_columns[$column_id]['settingsColumn'] = self::validate_column_settings($column_settings);
						}

						$filtered_row['columns'] = $filtered_columns;

						array_push( $validated_content, $filtered_row );
					}
				}
			}
		}

		return $validated_content;
	}

	/**
	 * Delete a template
	 */
	public static function delete( $location = 'header', $template_id = 'default')
	{
		if ( $template_id === 'default' ) {
			return false;
		}

		if ( in_array( $location, array('header', 'footer', 'page') ) ) {

			$templates = get_option( 'videofly_' . $location . '_templates', array(), true );

			if ( array_key_exists($template_id, $templates) && $template_id !== 'default' ) {

				unset($templates[$template_id]);
				update_option('videofly_' . $location . '_templates', $templates);

				return true;

			} else {
				return false;
			}

		} else {
			return false;
		}
	}
}
?>
