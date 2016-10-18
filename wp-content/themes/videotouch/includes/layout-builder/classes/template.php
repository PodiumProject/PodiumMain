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
			'textOpacity' => '',
			'bgImage' => '',
			'bgVideoMp' => '',
			'bgVideoWebm' => '',
			'bgPosition' => '',
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
			'gradient' => '',
			'gradientColor' => '',
			'gradientMode' => ''
		);
		
		$positions     = array('left', 'center', 'right');
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
			'yes'
		);

		$valid_row_shadow = array(
			'no',
			'yes'
		);

		$valid_row_gradient = array(
			'n',
			'y'
		);

		$valid_row_gradient_mode = array(
			'radial',
			'left-to-right',
			'corner-top',
			'corner-bottom'
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

			$filtred_settings['bgPosition'] = isset($settings['bgPosition']) ?
											esc_attr($settings['bgPosition']) : '';

			$filtred_settings['bgPosition'] = in_array($filtred_settings['bgPosition'], $positions) ?
										$filtred_settings['bgPosition'] : 'auto';

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

			$filtred_settings['rowVerticalAlign'] = in_array($filtred_settings['rowVerticalAlign'], $valid_row_vertical_align) ?
										$filtred_settings['rowVerticalAlign'] : 'top';

			$filtred_settings['gradient'] = isset($settings['gradient']) ?
										esc_attr($settings['gradient']) : 'n';

			$filtred_settings['gradient'] = in_array($filtred_settings['gradient'], $valid_row_gradient) ?
										$filtred_settings['gradient'] : 'n';

			$filtred_settings['gradientMode'] = isset($settings['gradientMode']) ?
										esc_attr($settings['gradientMode']) : 'radial';

			$filtred_settings['gradientMode'] = in_array($filtred_settings['gradientMode'], $valid_row_gradient_mode) ?
										$filtred_settings['gradientMode'] : 'radial';

			$filtred_settings['gradientColor'] = isset($settings['gradientColor']) ?
										esc_attr($settings['gradientColor']) : 'transparent';
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
			array_push( $attributes, 'data-bg-position="' . @$attr['bgPosition'] . '"' );
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
			array_push( $attributes, 'data-gradient="' . @$attr['gradient'] . '"' );
			array_push( $attributes, 'data-gradient-mode="' . @$attr['gradientMode'] . '"' );
			array_push( $attributes, 'data-gradient-color="' . @$attr['gradientColor'] . '"' );
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
			$templates = get_option('videotouch_' . $location . '_templates', array());
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
			
			$templates = get_option('videotouch_' . $location . '_templates', array());

			if (array_key_exists($template_id, $templates)) {

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

	public static function save($action = 'blank_template', $location = 'header') {

		$valid_actions   = array('blank_template', 'save_as', 'update');
		$valid_locations = array('header', 'footer', 'page');
		$template_id     = 'ts-template-'.time();

		$template_name = isset($_POST['template_name']) ? trim($_POST['template_name']) : ''; 
		$template_name = ($template_name === '') ? __('New template ' . date('d-m-Y'), 'touchsize') : $template_name;

		if ( in_array($action, $valid_actions) && in_array($location, $valid_locations) ) {
			
			if ($action === 'blank_template') {

				$templates = get_option( 'videotouch_' . $location . '_templates', array() );

				if ( is_array($templates) ) {

					$templates[$template_id] = array(
						'name' => $template_name,
						'elements' => array()
					);

				} else {
					$templates = array();
					$templates[$template_id] = array(
						'name' => $template_name,
						'elements' => array()
					);
				}

				$updated = update_option('videotouch_' . $location . '_templates', $templates);
				

			} else if ( $action === 'update') {

				$content = (isset($_POST['content']) && is_array($_POST['content'])) ? $_POST['content'] : array();
				$validated_content = self::validate_content($content);
				
				if (isset($_POST['post_id'])) {
					update_post_meta((int)$_POST['post_id'], 'ts_template', $validated_content);
				} else {
					$updated = update_option( 'videotouch_' . $location,  $validated_content );
				}
				
				if (isset($_POST['template_id'])) {
					$template_id = $_POST['template_id'];
				} else {
					$template_id = 'default';
				}

				update_option( 'videotouch_' . $location . '_template_id', $template_id );

				$templates = get_option( 'videotouch_' . $location . '_templates', array(), true );
				$templates[$template_id]['name'] = $template_name;
				$templates[$template_id]['elements'] = $validated_content;

				update_option( 'videotouch_' . $location . '_templates', $templates );

			} else {

				$content = (isset($_POST['content']) && is_array($_POST['content'])) ? $_POST['content'] : array();
				$validated_content = self::validate_content($content);

				$templates = get_option( 'videotouch_' . $location . '_templates', array() );
				$templates = ( ! is_array($templates) ) ? array() : $templates;

				$templates[$template_id] = array(
					'name' => $template_name,
					'elements' => $validated_content,
				);

				$updated = update_option('videotouch_' . $location . '_templates', $templates);
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
		if ( $location === 'header' || $location === 'footer') {
			
			$template    = get_option( 'videotouch_' . $location, array());
			$template_id = get_option( 'videotouch_' . $location . '_template_id', 'default');
			$templates   = get_option( 'videotouch_' . $location . '_templates', array());

			if ( isset($templates[$template_id]['elements']) ) {
				$template = $templates[$template_id]['elements'];
			}

		} else {
			$template = self::get_structure( $location );
		}

		return self::visual_editor($template);
	}


	/**
	 * Get tempalte name
	 * @param  string $location Header/Footer/Page
	 * @return string
	 */
	public static function get_template_info($location = 'header', $type = 'id') {

		$template_id = get_option( 'videotouch_' . $location . '_template_id', 'default', true );
		$templates = get_option( 'videotouch_' . $location . '_templates', array(), true );

		if ($type === 'id') {
			return $template_id;
		} else {
			if (isset($templates[$template_id]['name'])) {
				return $templates[$template_id]['name'];
			} else {
				return __('Template', 'touchsize');
			}
		}
	}

	public static function visual_editor($template = array()) {

		$new_structure = '';

		if ( is_array( $template ) && ! empty( $template ) ) {

			$parsed_rows = array();

			// travers tempalte rows
			foreach ($template as $row_id => $row) {

				// checK if we have rows in this section
				if ( is_array(@$row['columns']) && ! empty($row['columns']) ) {

					$row_start = '<ul class="layout_builder_row" '.self::row_attr(@$row['settings']).'>
						<li class="row-editor" >
							<ul class="row-editor-options">
								<li>
									<a href="#" class="add-column">'.__( '+', 'touchsize' ).'</a>
									<a href="#" class="predefined-columns"><img src="'.get_template_directory_uri().'/images/options/columns_layout.png" alt=""></a>
									<ul class="add-column-settings">
									   <li>
		                                   <a href="#" data-add-columns="#dragable-column-tpl"><img src="'.get_template_directory_uri().'/images/options/columns_layout_column.png" alt=""></a>
		                               </li>
		                               <li>
		                                   <a href="#" data-add-columns="#dragable-column-tpl-half"><img src="'.get_template_directory_uri().'/images/options/columns_layout_halfs.png" alt=""></a>
		                               </li>
		                               <li>
		                                   <a href="#" data-add-columns="#dragable-column-tpl-thirds"><img src="'.get_template_directory_uri().'/images/options/columns_layout_thirds.png" alt=""></a>
		                               </li>
		                               <li>
		                                   <a href="#" data-add-columns="#dragable-column-tpl-four-halfs"><img src="'.get_template_directory_uri().'/images/options/columns_layout_one_four.png" alt=""></a>
		                               </li>
		                               <li>
		                                   <a href="#" data-add-columns="#dragable-column-tpl-one_three"><img src="'.get_template_directory_uri().'/images/options/columns_layout_one_three.png" alt=""></a>
		                               </li>
		                               <li>
		                                   <a href="#" data-add-columns="#dragable-column-tpl-four-half-four"><img src="'.get_template_directory_uri().'/images/options/columns_layout_four_half_four.png" alt=""></a>
		                               </li>
									</ul>
								</li>
								<li><a href="#" class="remove-row">'.__( 'delete', 'touchsize' ).'</a></li>
								<li><a href="#" class="move">'.__( 'move', 'touchsize' ).'</a></li>
							</ul>
						</li>
						<li class="edit-row-settings" >
							<a href="#" class="edit-row">'.__( 'Edit', 'touchsize' ).'</a>
						</li>';
					$row_end   = '</ul>';

					$parsed_columns = array();

					// travers each row and parse columns
					foreach ($row['columns'] as $column_index => $column) {

						$column_start = '<li data-columns="'.$column['size'].'" data-type="column" class="columns'.$column['size'].'"> 
							<div class="column-header">
								<span class="minus icon-left" data-tooltip="Reduce column size"></span>
								<span class="column-size" data-tooltip="The size of the column within container">'.self::get_column_size($column['size']).'</span>
								<span class="plus icon-right" data-tooltip="Add column size"></span>
								<span class="delete-column icon-delete" data-tooltip="Remove this column"></span>
								<span class="drag-column icon-drag" data-tooltip="Drag this column"></span>
							</div>
							<ul class="elements">';
						
							$column_end = '</ul><span class="add-element">'.__('Add element', 'touchsize').'</span>
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

			$templates = get_option( 'videotouch_' . $location . '_templates', array(), true );

			if ( array_key_exists($template_id, $templates) && $template_id !== 'default' ) {

				unset($templates[$template_id]);
				update_option('videotouch_' . $location . '_templates', $templates);

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
