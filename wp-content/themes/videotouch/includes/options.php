<?php
/**
 * Export/Import Theme options
 */
function videotouch_impots_options()
{
	//if not administrator, kill WordPress execution and provide a message
	if ( ! current_user_can( 'manage_options' ) ) {
		return false;
	}

	parse_str($_SERVER['QUERY_STRING'], $params);

	if (preg_match("/^.*\/wp-admin\/admin.php$/i", $_SERVER['SCRIPT_NAME'], $matches)) {
		if (array_key_exists('page', $params) && array_key_exists('tab', $params) ) {
			if ($params['page'] === 'videotouch' && $params['tab'] === 'save_options') {
				if (isset($_POST['encoded_options'])) {

					$import = videotouch_impots_decoded_options($_POST['encoded_options']);

					if ($import) {
						$status = '&updated=true';
					} else {
						$status = '&updated=false';
					}

					wp_redirect( admin_url('admin.php?page=videotouch&tab=impots_options' . $status) );
				}
			}
		}
	}
}

add_action('admin_init', 'videotouch_impots_options');

function videotouch_load_patterns()
{
	//if not administrator, kill WordPress execution and provide a message
	if ( ! current_user_can( 'manage_options' ) ) {
		return false;
	}

	parse_str($_SERVER['QUERY_STRING'], $params);

	if (preg_match("/^.*\/wp-admin\/admin.php$/i", $_SERVER['SCRIPT_NAME'], $matches)) {
		if (array_key_exists('page', $params) && array_key_exists('tab', $params) ) {
			if ($params['page'] === 'videotouch' && $params['tab'] === 'load_patterns') {
				require_once(get_template_directory() .'/includes/patterns.php');
				die();
			}
		}
	}
}

add_action('admin_init', 'videotouch_load_patterns');

function videotouch_impots_decoded_options($encoded) {

	$options = ts_base_64($encoded, 'decode');
	$options = json_decode($options, true);

	if ($options === null) {
		return false;
	} else {
		if ($options) {
			foreach ($options as $option_name => $params) {
				delete_option($option_name);
				add_option($option_name, $params);
			}
		}

		return true;
	}
}

function videotouch_exports_options() {

	$export = array();

	$expots_options = array(
		'videotouch_general',
		'videotouch_image_sizes',
		'videotouch_layout',
		'videotouch_colors',
		'videotouch_styles',
		'videotouch_typography',
		'videotouch_single_post',
		'videotouch_page',
		'videotouch_social',
		'videotouch_css',
		'videotouch_sidebars',
		'videotouch_header',
		'videotouch_header_templates',
		'videotouch_header_template_id',
		'videotouch_footer',
		'videotouch_footer_templates',
		'videotouch_footer_template_id',
		'videotouch_footer_template_id',
		'videotouch_page_template_id',
		'videotouch_theme_advertising',
		'videotouch_theme_update'
	);

	foreach ($expots_options as $option) {
		$export[$option] = get_option($option, array());
	}

	$export = json_encode($export);

	return ts_base_64($export, 'encode');
}

function register_my_menu() {
 	register_nav_menu('primary', __( 'Primary Menu', 'touchsize' ));
}

add_action( 'init', 'register_my_menu' );

/**
 * Generate menu for VideoTouch Theme
 */
function videotouch_create_menu()
{
	add_menu_page(
		'VideoTouch Options',
		'VideoTouch',
		'administrator',
		'videotouch',
		'videotouch_display_menu_page',
		get_template_directory_uri() . '/includes/images/touchicon.png'
	);

	add_submenu_page('videotouch', __('Header', 'touchsize'), __('Header', 'touchsize'), 'administrator', 'videotouch_header', 'videotouch_header');
	add_submenu_page('videotouch', __('Footer', 'touchsize'), __('Footer', 'touchsize'), 'administrator', 'videotouch_footer', 'videotouch_footer');
	add_submenu_page('videotouch', '--------------------------', '--------------------------', 'administrator', 'videotouch&tab=general', 'videotouch&tab=general');
	add_submenu_page('videotouch', __('General', 'touchsize'), __('General', 'touchsize'), 'administrator', 'videotouch&tab=general', 'videotouch&tab=general');
	add_submenu_page('videotouch', __('Styles', 'touchsize'), __('Styles', 'touchsize'), 'administrator', 'videotouch&tab=styles', 'videotouch&tab=styles');
	add_submenu_page('videotouch', __('Colors', 'touchsize'), __('Colors', 'touchsize'), 'administrator', 'videotouch&tab=colors', 'videotouch&tab=colors');
	add_submenu_page('videotouch', __('Image sizes', 'touchsize'), __('Image sizes', 'touchsize'), 'administrator', 'videotouch&tab=image_sizes', 'videotouch&tab=image_sizes');
	add_submenu_page('videotouch', __('Layout', 'touchsize'), __('Layout', 'touchsize'), 'administrator', 'videotouch&tab=layout', 'videotouch&tab=layout');
	add_submenu_page('videotouch', __('Typography', 'touchsize'), __('Typography', 'touchsize'), 'administrator', 'videotouch&tab=typography', 'videotouch&tab=typography');
	add_submenu_page('videotouch', __('Single post', 'touchsize'), __('Single post', 'touchsize'), 'administrator', 'videotouch&tab=single', 'videotouch&tab=single');
	add_submenu_page('videotouch', __('Page settings', 'touchsize'), __('Page settings', 'touchsize'), 'administrator', 'videotouch&tab=page_settings', 'videotouch&tab=page_settings');
	add_submenu_page('videotouch', __('Social', 'touchsize'), __('Social', 'touchsize'), 'administrator', 'videotouch&tab=social', 'videotouch&tab=social');
	add_submenu_page('videotouch', __('Custom CSS', 'touchsize'), __('Custom CSS', 'touchsize'), 'administrator', 'videotouch&tab=custom_css', 'videotouch&tab=custom_css');
	add_submenu_page('videotouch', __('Sidebars', 'touchsize'), __('Sidebars', 'touchsize'), 'administrator', 'videotouch&tab=sidebars', 'videotouch&tab=sidebars');
	add_submenu_page('videotouch', __('Import options', 'touchsize'), __('Import options', 'touchsize'), 'administrator', 'videotouch&tab=impots_options', 'videotouch&tab=impots_options');
	add_submenu_page('videotouch', __('Advertising', 'touchsize'), __('Advertising', 'touchsize'), 'administrator', 'videotouch&tab=theme_advertising', 'videotouch&tab=theme_advertising');
	add_submenu_page('videotouch', __('Red Area', 'touchsize'), __('Red Area', 'touchsize'), 'administrator', 'videotouch&tab=red_area', 'videotouch&tab=red_area');
	add_submenu_page('videotouch', __('Theme update', 'touchsize'), __('Theme update', 'touchsize'), 'administrator', 'videotouch&tab=theme_update', 'videotouch&tab=theme_update');
}

add_action( 'admin_menu', 'videotouch_create_menu' );

function videotouch_template_modals($location = 'header', $template_id = 'default', $template_name = 'Default') {
	ob_start();
    ob_clean();
    	wp_editor('', 'ts_editor_id', array('textarea_name' => 'ts_name_textarea', 'wpautop' => true));
    $editor_code = ob_get_clean();
	return '<table>
		<tr>
			<td style="width: 500px">
				<p>
					<input id="ts-blank-template" data-location="'.esc_attr($location).'" data-toggle="modal" data-target="#ts-confirmation" type="button" name="submit" class="button-primary" value="'.__('Blank template', 'touchsize') . '" />

					<input id="ts-save-as-template" data-location="'.esc_attr($location).'" data-toggle="modal" data-target="ts-save-template-modal" type="button" name="submit" class="button-primary" value="' . __('Save as...', 'touchsize') . '" />

					<input id="ts-load-template-button" data-location="'.esc_attr($location).'" type="button" name="submit" class="button-primary" value="'. __('Load template', 'touchsize') . '" />
				</p>
				<!-- Blank template modal -->
				<div class="modal ts-modal fade" id="ts-blank-template-modal" tabindex="-1" role="dialog" aria-labelledby="blank-template-modal" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title" id="blank-template-modal">' . __('Blank template', 'touchsize') . '</h4>
							</div>
							<div class="modal-body">
								<h5>' . __('Template name', 'touchsize') . '</h5>
								<input type="text" name="template_name" value="" id="ts-blank-template-name"/>
							</div>
					      	<div class="modal-footer">
					        	<button type="button" class="button-primary" data-dismiss="modal">' . __('Close', 'touchsize') . ' </button>
								<button type="button" class="button-primary" data-location="' . esc_attr($location) . '" id="ts-save-blank-template-action">' . __('Save', 'touchsize') .'</button>
							</div>
						</div>
					</div>
				</div>

				<!-- Blank template modal confirmation -->
				<div class="modal ts-modal fade" id="ts-confirmation" tabindex="-1" role="dialog" aria-labelledby="blank-modal-confirmation-label" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title" id="blank-modal-confirmation-label">' . __('Are you sure?', 'touchsize') . '</h4>
							</div>
							<div class="modal-body">
								<p>' . __('Are you sure? If you did not save this template it will be erased and you will not be able to load it again. Proceed with caution.', 'touchsize') . '</p>
							</div>
							<div class="modal-footer">
								<button type="button" class="button-primary ts-modal-confirm">' . __('Yes', 'touchsize') . '</button>
								<button type="button" class="button-primary ts-modal-decline" data-dismiss="modal">' . __('No', 'touchsize') .' </button>
							</div>
						</div>
					</div>
				</div>

				<!-- Save as... template modal -->
				<div class="modal ts-modal fade" id="ts-save-template-modal" tabindex="-1" role="dialog" aria-labelledby="save-template-modal-label" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title" id="save-template-modal-label">' . __('Save template', 'touchsize') . '</h4>
							</div>
							<div class="modal-body">
								<h5>' . __('Template name', 'touchsize') . ':</h5>
								<input type="text" name="template_name" value="" id="ts-save-template-name"/>
							</div>
							<div class="modal-footer">
								<button type="button" class="button-primary" data-dismiss="modal">' . __('Close', 'touchsize') . '</button>
								<button type="button" class="button-primary" data-location="'.esc_attr($location).'" id="ts-save-as-template-action">' . __('Save', 'touchsize') . '</button>
							</div>
						</div>
					</div>
				</div>

				<!-- Load template modal -->
				<div class="modal fade ts-modal" id="ts-load-template" tabindex="-1" role="dialog" aria-labelledby="load-template-modal-label" aria-hidden="true">
					<div class="modal-dialog">
					    <div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title" id="load-template-modal-label">' . __('Load template', 'touchsize') . '</h4>
							</div>
							<div class="modal-body">
								<h5>' . __('Select template', 'touchsize') . ':</h5>
								<table id="ts-layout-list">

								</table>
							</div>
							<div class="modal-footer">
								<button type="button" class="button-primary" data-dismiss="modal">'.__('Cancel', 'touchsize') . '</button>
								<button type="button" class="button-primary" data-location="'.esc_attr($location).'" id="ts-load-template-action">' . __('Load', 'touchsize') . '</button>
							</div>
						</div>
					</div>
				</div>
				<!-- Open builder options modal -->
				<div id="ts-builder-elements-modal-preloader"></div>
				<div class="modal ts-modal fade" id="ts-builder-elements-modal" tabindex="-1" role="dialog" aria-labelledby="ts-builder-elements-modal-label" aria-hidden="true">
					<div class="modal-dialog">
					    <div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title" id="ts-builder-elements-modal-label">' . __('Builder elements', 'touchsize') . '</h4>
							</div>
							<div class="modal-body">

							</div>
						</div>
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<div id="ts-builder-elements-editor-preloader"></div>
				<div class="modal fade ts-modal" id="ts-builder-elements-modal-editor" tabindex="-1" role="dialog" aria-labelledby="ts-builder-elements-modal-editor-label" aria-hidden="true">
					<div class="modal-dialog">
					    <div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title" id="ts-builder-elements-editor-modal-label">' . __('Text element', 'touchsize') . '</h4>
							</div>
							<div class="modal-body">
								<table width="100%" cellpadding="10">
								    <tr>
								        <td>
								            <label for="text-admin-label">' . __('Admin label','touchsize') . ':</label>
								        </td>
								        <td>
								           <input type="text" id="text-admin-label" name="text-admin-label" />
								        </td>
								    </tr>
								    <tr>
								        <td>' . __('Add your text here','touchsize') . ':</td><td>'
									. $editor_code .
									'</td>
									</tr>
									<tr>
										<td></td>
										<td>
											<div class="ts-save-editor save-element">' . __('Save', 'touchsize') . '</div>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<h3>
					<strong> ' . __('Template name', 'touchsize') . ' :</strong> <span id="ts-template-name">'.wp_kses($template_name, array()).'</span>
				</h3>
				<input type="hidden" name="template_id" id="ts-template-id" value="'.esc_attr($template_id).'"/>
				<input type="hidden" name="template_location" value="' . esc_attr($location) . '" />
			</td>
		</tr>
	</table>';
}

/**
 * Edit header
 */
function videotouch_header()
{
?>
	<div class="wrap">
		<div class="wrap-red-templates">
			<p><h2><?php _e('Header', 'touchsize') ?></h2></p>
			<?php
				$template_id = Template::get_template_info('header', 'id');
				$template_name = Template::get_template_info('header', 'name');
			 	echo videotouch_template_modals( 'header', $template_id, $template_name );
			?>
			<br/><br/>
			<?php ts_layout_wrapper(Template::edit('header')); ?>
			<input id="save-header-footer" data-location="header" type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes', 'touchsize') ?>"/>
		</div>
		<div id="ts_editor_default" style="display:none"><?php wp_editor('','ts_editor_footer',array('textarea_name' => 'ts_texatarea_name_footer'));  ?></div>
	</div>
<?php
}

/**
 * Edit footer
 */
function videotouch_footer()
{
?>
	<div class="wrap">
		<div class="wrap-red-templates">
			<p><h2><?php _e('Footer', 'touchsize') ?></h2></p>
			<?php
				$template_id = Template::get_template_info('footer', 'id');
				$template_name = Template::get_template_info('footer', 'name');
				echo videotouch_template_modals( 'footer', $template_id, $template_name );
			?>
			<br/><br/>

			<?php ts_layout_wrapper(Template::edit('footer')); ?>
			<input id="save-header-footer" data-location="footer" type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes', 'touchsize') ?>"/>
		</div>
		<div id="ts_editor_default" style="display:none"><?php wp_editor('','ts_editor_footer',array('textarea_name' => 'ts_texatarea_name_footer'));  ?></div>
	</div>
<?php
}

function videotouch_display_menu_page( $active_tab = '')
{
?>

<div class="wrap">
		<div class="wrap-red">
		<?php
			settings_errors();

			if ( isset( $_GET['tab'] ) ) {

				$active_tab = $_GET['tab'];

			} else if ( $active_tab === 'general' ) {

				$active_tab = 'general';

			} else if ( $active_tab === 'styles' ) {

				$active_tab = 'styles';

			} else if ( $active_tab === 'image_sizes' ) {

				$active_tab = 'image_sizes';

			} else if ( $active_tab === 'layout' ) {

				$active_tab = 'layout';

			} else if ( $active_tab === 'typography' ) {

				$active_tab = 'typography';

			} else if ( $active_tab === 'single' ) {

				$active_tab = 'single';

			} else if ( $active_tab === 'page_settings' ) {

				$active_tab = 'page_settings';

			} else if ( $active_tab === 'social' ) {

				$active_tab = 'social';

			} else if ( $active_tab === 'custom_css' ) {

				$active_tab = 'custom_css';

			} else if ( $active_tab === 'sidebars' ) {

				$active_tab = 'sidebars';

			} else if ( $active_tab === 'impots_options' ) {

				$active_tab = 'impots_options';

			} else if ( $active_tab === 'red_area' ) {

				$active_tab = 'red_area';

			} else if ( $active_tab === 'theme_advertising' ) {

				$active_tab = 'theme_advertising';

			} else {

				$active_tab = 'general';
			}
		?>
		<div id="red-wprapper">
			<div id="red-menu">
				<ul id="theme-setting">
					<li>
						<a href="?page=videotouch&tab=general" class="<?php echo $active_tab == 'general' ? 'selected-tab' : ''; ?>">
							<i class="icon-settings"></i>
							<span><?php _e( 'General', 'touchsize' ); ?></span>
						</a>
					</li>
					<li>
						<a href="?page=videotouch&tab=styles" class="<?php echo $active_tab == 'styles' ? 'selected-tab' : ''; ?>">
							<i class="icon-code"></i>
							<span><?php _e( 'Styles', 'touchsize' ); ?></span>
						</a>
					</li>
					<li>
						<a href="?page=videotouch&tab=colors" class="<?php echo $active_tab == 'colors' ? 'selected-tab' : ''; ?>">
							<i class="icon-palette"></i>
							<span><?php _e( 'Colors', 'touchsize' ); ?></span>
						</a>
					</li>
					<li>
						<a href="?page=videotouch&tab=image_sizes" class="<?php echo $active_tab == 'image_sizes' ? 'selected-tab' : ''; ?>">
							<i class="icon-image-size"></i>
							<span><?php _e( 'Image sizes', 'touchsize' ); ?></span>
						</a>
					</li>
					<li>
						<a href="?page=videotouch&tab=layout" class="<?php echo $active_tab == 'layout' ? 'selected-tab' : ''; ?>">
							<i class="icon-layout"></i>
							<span><?php _e( 'Layout', 'touchsize' ); ?></span>
						</a>
					</li>
					<li>
						<a href="?page=videotouch&tab=typography" class="<?php echo $active_tab == 'typography' ? 'selected-tab' : ''; ?>">
							<i class="icon-edit"></i>
							<span><?php _e( 'Typography', 'touchsize' ); ?></span>
						</a>
					</li>
					<li>
						<a href="?page=videotouch&tab=single" class="<?php echo $active_tab == 'single' ? 'selected-tab' : ''; ?>">
							<i class="icon-post"></i>
							<span><?php _e( 'Single post', 'touchsize' ); ?></span>
						</a>
					</li>
					<li>
						<a href="?page=videotouch&tab=page_settings" class="<?php echo $active_tab == 'page_settings' ? 'selected-tab' : ''; ?>">
							<i class="icon-page"></i>
							<span><?php _e( 'Page settings', 'touchsize' ); ?></span>
						</a>
					</li>
					<li>
						<a href="?page=videotouch&tab=social" class="<?php echo $active_tab == 'social' ? 'selected-tab' : ''; ?>">
							<i class="icon-social"></i>
							<span><?php _e( 'Social', 'touchsize' ); ?></span>
						</a>
					</li>
					<li>
						<a href="?page=videotouch&tab=custom_css" class="<?php echo $active_tab == 'custom_css' ? 'selected-tab' : ''; ?>">
							<i class="icon-code"></i>
							<span><?php _e( 'Custom CSS', 'touchsize' ); ?></span>
						</a>
					</li>
					<li>
						<a href="?page=videotouch&tab=sidebars" class="<?php echo $active_tab == 'sidebars' ? 'selected-tab' : ''; ?>">
							<i class="icon-sidebar"></i>
							<span><?php _e( 'Sidebars', 'touchsize' ); ?></span>
						</a>
					</li>
					<li>
						<a href="?page=videotouch&tab=impots_options" class="<?php echo $active_tab == 'impots_options' ? 'selected-tab' : ''; ?>">
							<i class="icon-import"></i>
							<span><?php _e( 'Import options', 'touchsize' ); ?></span>
						</a>
					</li>
					<li>
						<a href="?page=videotouch&tab=theme_advertising" class="<?php echo $active_tab == 'theme_advertising' ? 'selected-tab' : ''; ?>">
							<i class="icon-dollar"></i>
							<span><?php _e( 'Advertising', 'touchsize' ); ?></span>
						</a>
					</li>
					<li>
						<a href="?page=videotouch&tab=theme_update" class="<?php echo $active_tab == 'theme_update' ? 'selected-tab' : ''; ?>">
							<i class="icon-download"></i>
							<span><?php _e( 'Theme update', 'touchsize' ); ?></span>
						</a>
					</li>
					<li>
						<a href="?page=videotouch&tab=red_area" class="<?php echo $active_tab == 'red_area' ? 'selected-tab' : ''; ?>">
							<i class="icon-attention"></i>
							<span><?php _e( 'Red Area', 'touchsize' ); ?></span>
						</a>
					</li>
				</ul>
			</div>
			<div id="red-options">
				<div class="theme-name">
					<h3>VideoTouch</h3>
					<h3>TouchSize</h3>
				</div>
				<?php if ($active_tab !== 'impots_options' ): ?>
				<form method="post" data-table="<?php echo $active_tab; ?>" action="options.php">
				<?php endif ?>
					<?php
						if ( $active_tab === 'general' ) {

							settings_fields( 'videotouch_general' );
							do_settings_sections( 'videotouch_general' );

						} else if ( $active_tab === 'styles' ) {

							settings_fields( 'videotouch_styles' );
							do_settings_sections( 'videotouch_styles' );

						} else if ( $active_tab === 'colors' ) {

							settings_fields( 'videotouch_colors' );
							do_settings_sections( 'videotouch_colors' );

						} else if ( $active_tab === 'image_sizes' ) {

							settings_fields( 'videotouch_image_sizes' );
							do_settings_sections( 'videotouch_image_sizes' );

						} else if ( $active_tab === 'layout' ) {

							settings_fields( 'videotouch_layout' );
							do_settings_sections( 'videotouch_layout' );

						} else if ( $active_tab === 'typography' ) {

							settings_fields( 'videotouch_typography' );
							do_settings_sections( 'videotouch_typography' );

						} else if ( $active_tab === 'single' ) {

							settings_fields( 'videotouch_single_post' );
							do_settings_sections( 'videotouch_single_post' );

						} else if ( $active_tab === 'page_settings' ) {

							settings_fields( 'videotouch_page' );
							do_settings_sections( 'videotouch_page' );

						} else if ( $active_tab === 'social' ) {

							settings_fields( 'videotouch_social' );
							do_settings_sections( 'videotouch_social' );

						} else if ( $active_tab === 'custom_css' ) {

							settings_fields( 'videotouch_css' );
							do_settings_sections( 'videotouch_css' );

						} else if ( $active_tab === 'sidebars' ) {

							settings_fields( 'videotouch_sidebars' );
							do_settings_sections( 'videotouch_sidebars' );

						} else if ( $active_tab === 'theme_advertising' ) {

							settings_fields( 'videotouch_theme_advertising' );
							do_settings_sections( 'videotouch_theme_advertising' );

						} else if ( $active_tab === 'theme_update' ) {

							settings_fields( 'videotouch_theme_update' );
							do_settings_sections( 'videotouch_theme_update' );

						}  else if ( $active_tab === 'impots_options' ) {

							settings_fields( 'videotouch_impots_options' );
							do_settings_sections( 'videotouch_impots_options' );

						} else if ( $active_tab === 'red_area' ) {

							settings_fields( 'videotouch_red_area' );
							do_settings_sections( 'videotouch_red_area' );

						}

					if ( $active_tab != 'sidebars' && $active_tab != 'red_area' && $active_tab != 'impots_options' ) {
						submit_button(__('Save changes','touchsize'), 'button', 'ts_submit_button');
					}
				?>

				<?php if ($active_tab !== 'impots_options' ): ?>
				</form>
				<?php endif ?>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>

<?php
}

/**
 * Iniaitalize the theme options page by registering the Fields, Sections, Settings
 */
function videotouch_initialize_general_options()
{
	$default_update = get_option('videotouch_general');
	if( !isset($default_update['login_register_by_facebook']) || !isset($default_update['insert_post_user']) || !isset($default_update['slug_video_taxonomy']) || !isset($default_update['slug_video']) || !isset($default_update['slug_portfolio']) || !isset($default_update['slug_portfolio_taxonomy']) || !isset($default_update['ts_seo']) ){

		$default_update['login_register_by_facebook'] = 'n';
		$default_update['insert_post_user'] = 'post-video';
		$default_update['slug_video_taxonomy'] = 'videos_categories';
		$default_update['slug_video'] = 'video';
		$default_update['slug_portfolio_taxonomy'] = 'portfolio-categories';
		$default_update['slug_portfolio'] = 'portfolio';
		$default_update['ts_seo'] = 'n';
		update_option('videotouch_general', $default_update);
	}
	//delete_option('videotouch_general');
	if ( false === get_option( 'videotouch_general' ) ) {

		add_option( 'videotouch_general', array(
			'featured_image_in_post'     => 'Y',
			'enable_lightbox'            => 'Y',
			'enable_imagesloaded'        => 'N',
			'human_type_date_format'     => 'Y',
			'comment_system'             => 'default',
			'show_wp_admin_bar'          => 'Y',
			'enable_sticky_menu'         => 'Y',
			'enable_mega_menu'     	     => 'N',
			'sticky_menu_bg_color'	     => '#FFFFFF',
			'sticky_menu_text_color'     => '#444444',
			'tracking_code'			     => '',
			'enable_preloader'		     => 'N',
			'onepage_website'		     => 'N',
			'facebook_id'			     => '',
			'grid_excerpt'			     => 260,
			'list_excerpt'			     => 600,
			'bigpost_excerpt'	         => 260,
			'timeline_excerpt'	         => 260,
			'enable_facebook_box'        => 'N',
			'facebook_name' 		     => '',
			'like'                       => 'y',
			'like_icons'                 => 'heart',
			'mode_display_menu'          => 'ts-orizontal-menu',
			'post_publish_user'          => 'pending',
			'login_register_by_facebook' => 'n',
			'ts_seo'                     => 'n',
			'facebook_app_id'            => '',
			'facebook_app_secret'        => '',
			'insert_post_user'           => 'post-video',
			'slug_video_taxonomy'        => 'videos_categories',
			'slug_video'                 => 'video',
			'slug_portfolio_taxonomy'    => 'portfolio-categories',
			'slug_portfolio'             => 'portfolio'
		));

	} // end if

	// Register a section
	add_settings_section(
		'general_settings_section',
		__( 'General Options', 'touchsize' ),
		'videotouch_general_options_callback',
		'videotouch_general'
	);

	add_settings_field(
		'enable_preloader',
		__( 'Enable preloader for website?', 'touchsize' ),
		'toggle_enable_preloader_callback',
		'videotouch_general',
		'general_settings_section',
		array(
			__( "If you want to add a preloader to your website, you can activate it only for your frontpage, for the whole website or disable it.", 'touchsize' )
		)
	);

	add_settings_field(
		'onepage_website',
		__( 'Enable the onepage layout', 'touchsize' ),
		'toggle_onepage_website_callback',
		'videotouch_general',
		'general_settings_section',
		array(
			__( "If you enable this, you'll activate the smooth scrolling for the menus in onepage layout. Do not forget to create links with the row names and set them in your menu. Your menu items WILL NOT LINK TO ANY EXTERNAL PAGES, so be sure you need to use this option. For more info check the documentation.", 'touchsize' )
		)
	);

	add_settings_field(
		'featured_image_in_post',
		__( 'Display featured image in post?', 'touchsize' ),
		'toggle_featured_image_in_post_callback',
		'videotouch_general',
		'general_settings_section',
		array(
			__( "Use this to hide or show the featured image in posts.", 'touchsize' )
		)
	);

	add_settings_field(
		'enable_lightbox',
		__( 'Enable lightbox?', 'touchsize' ),
		'toggle_enable_lightbox_callback',
		'videotouch_general',
		'general_settings_section',
		array(
			__( "Enable this if you want your featured images on single pages to have lightbox available", 'touchsize')
		)
	);

	add_settings_field(
		'enable_imagesloaded',
		__( 'Want to use imagesloaded?', 'touchsize' ),
		'toggle_enable_imagesloaded_callback',
		'videotouch_general',
		'general_settings_section',
		array(
			__( "Help loading site with a relatively higher speed.", 'touchsize')
		)
	);

	add_settings_field(
		'human_type_date_format',
		__( 'Enable human type date?', 'touchsize' ),
		'toggle_human_type_date_format_callback',
		'videotouch_general',
		'general_settings_section',
		array(
			__( 'Human types changes the way the date is shown on archive/single pages. Use this to change default wordpress settings.', 'touchsize' )
		)
	);

	add_settings_field(
		'comment_system',
		__( 'Which comment system you want to use?', 'touchsize' ),
		'toggle_comment_system_callback',
		'videotouch_general',
		'general_settings_section',
		array(
			__( 'Select which type of comments do you want to use.', 'touchsize' )
		)
	);

	add_settings_field(
		'enable_facebook_box',
		__( 'Facebook modal box page', 'touchsize' ),
		'facebook_page_modal_callback',
		'videotouch_general',
		'general_settings_section',
		array(
			__( 'Add the page modal box in the site.', 'touchsize' )
		)
	);

	add_settings_field(
		'show_wp_admin_bar',
		__( 'Show wordpress admin bar?', 'touchsize' ),
		'toggle_show_wp_admin_bar_callback',
		'videotouch_general',
		'general_settings_section',
		array(
			__( 'This options disables the wordpress admin bar when logged.', 'touchsize' )
		)
	);

	add_settings_field(
		'enable_sticky_menu',
		__( 'Enable sticky menu', 'touchsize' ),
		'toggle_enable_sticky_menu_callback',
		'videotouch_general',
		'general_settings_section',
		array(
			__( 'Enable sticky menu', 'touchsize' )
		)
	);

	add_settings_field(
		'enable_mega_menu',
		__( 'Enable mega menu', 'touchsize' ),
		'toggle_enable_mega_menu_callback',
		'videotouch_general',
		'general_settings_section',
		array(
			__( "If you want to add a mega menu to your website, you can activate it.", 'touchsize' )
		)
	);

	add_settings_field(
		'like',
		__( 'Enable likes', 'touchsize' ),
		'toggle_like_callback',
		'videotouch_general',
		'general_settings_section',
		array(
			__( "If you want to add a likes to your website, you can activate it.", 'touchsize' )
		)
	);

	add_settings_field(
		'like_icons',
		__( 'Select your icon for like', 'touchsize' ),
		'toggle_like_icons_callback',
		'videotouch_general',
		'general_settings_section',
		array(
			__( "You can select your icon for likes.", 'touchsize' )
		)
	);

	add_settings_field(
		'generate_likes',
		__( 'You can add the likes to posts', 'touchsize' ),
		'toggle_generate_likes_callback',
		'videotouch_general',
		'general_settings_section',
		array(
			__( "You can generate likes in your posts.", 'touchsize' )
		)
	);

	add_settings_field(
		'tracking_code',
		__( 'Tracking code', 'touchsize' ),
		'toggle_tracking_code_callback',
		'videotouch_general',
		'general_settings_section',
		array(
			__( 'Google analytics or any other scripts you have', 'touchsize' )
		)
	);


	add_settings_field(
		'grid_excerpt',
		__( 'Grid view excerpt size', 'touchsize' ),
		'toggle_grid_excerpt_callback',
		'videotouch_general',
		'general_settings_section',
		array(
			__( 'If you want to shorten or use more characters for your grid articles change the number here', 'touchsize' )
		)
	);

	add_settings_field(
		'list_excerpt',
		__( 'List view excerpt size', 'touchsize' ),
		'toggle_list_excerpt_callback',
		'videotouch_general',
		'general_settings_section',
		array(
			__( 'If you want to shorten or use more characters for your list articles change the number here', 'touchsize' )
		)
	);

	add_settings_field(
		'bigpost_excerpt',
		__( 'Big posts view excerpt size', 'touchsize' ),
		'toggle_bigpost_excerpt_callback',
		'videotouch_general',
		'general_settings_section',
		array(
			__( 'If you want to shorten or use more characters for your big post articles change the number here', 'touchsize' )
		)
	);

	add_settings_field(
		'timeline_excerpt',
		__( 'Timeline view excerpt size', 'touchsize' ),
		'toggle_timeline_excerpt_callback',
		'videotouch_general',
		'general_settings_section',
		array(
			__( 'If you want to shorten or use more characters for your list articles change the number here', 'touchsize' )
		)
	);

	add_settings_field(
		'post_publish_user',
		__( "Set status for users's posts", 'touchsize' ),
		'toggle_post_publish_user_callback',
		'videotouch_general',
		'general_settings_section',
		array(
			__( 'Default frontend submitted posts status', 'touchsize' )
		)
	);

	add_settings_field(
		'login_register_by_facebook',
		__( "Login/register with facebook", 'touchsize' ),
		'toggle_login_register_by_facebook_callback',
		'videotouch_general',
		'general_settings_section',
		array(
			__( 'Add register or login by facebook account', 'touchsize' )
		)
	);

	add_settings_field(
		'facebook_app_id',
		__( "Insert your facebook application id", 'touchsize' ),
		'toggle_facebook_app_id_callback',
		'videotouch_general',
		'general_settings_section',
		array(
			__( '', 'touchsize' )
		)
	);

	add_settings_field(
		'facebook_app_secret',
		__( "Insert your facebook application secret key", 'touchsize' ),
		'toggle_facebook_app_secret_callback',
		'videotouch_general',
		'general_settings_section',
		array(
			__( '', 'touchsize' )
		)
	);

	add_settings_field(
		'insert_post_user',
		__( 'Choose front-end upload post types', 'touchsize' ),
		'toggle_insert_post_user_callback',
		'videotouch_general',
		'general_settings_section',
		array(
			__( '', 'touchsize' )
		)
	);

	add_settings_field(
		'ts_seo',
		__( 'Enable theme seo', 'touchsize' ),
		'toggle_ts_seo_callback',
		'videotouch_general',
		'general_settings_section',
		array(
			__( '', 'touchsize' )
		)
	);

	add_settings_field(
		'slug_video',
		__( 'Change custom post video slug', 'touchsize' ),
		'toggle_slug_video_callback',
		'videotouch_general',
		'general_settings_section',
		array(
			__( '', 'touchsize' )
		)
	);

	add_settings_field(
		'slug_video_taxonomy',
		__( 'Change archive video slug', 'touchsize' ),
		'toggle_slug_video_taxonomy_callback',
		'videotouch_general',
		'general_settings_section',
		array(
			__( '', 'touchsize' )
		)
	);

	add_settings_field(
		'slug_portfolio',
		__( 'Change custom post portfolio slug', 'touchsize' ),
		'toggle_slug_portfolio_callback',
		'videotouch_general',
		'general_settings_section',
		array(
			__( '', 'touchsize' )
		)
	);

	add_settings_field(
		'slug_portfolio_taxonomy',
		__( 'Change archive portfolio slug', 'touchsize' ),
		'toggle_slug_portfolio_taxonomy_callback',
		'videotouch_general',
		'general_settings_section',
		array(
			__( '', 'touchsize' )
		)
	);

	register_setting( 'videotouch_general', 'videotouch_general');

} // END videotouch_initialize_general_options

add_action( 'admin_init', 'videotouch_initialize_general_options' );

/**************************************************
 * Section Callbacks
 *************************************************/

function videotouch_general_options_callback()
{
	echo '<p>'.__( 'Below are the general options that this theme offers. You can enable/disable options and sections of your website.', 'touchsize' ).'</p>';
} // END videotouch_general_options_callback

function toggle_enable_preloader_callback($args)
{
	$options = get_option('videotouch_general');

	$html = '<select name="videotouch_general[enable_preloader]">
		<option value="Y" '. selected( @$options["enable_preloader"], 'Y', false ). '>' . __('Yes', 'touchsize') . '</option>
		<option value="N" '. selected( @$options["enable_preloader"], 'N', false ). '>' . __('No', 'touchsize') . '</option>
		<option value="FP" '. selected( @$options["enable_preloader"], 'FP', false ). '>' . __('Only on first page', 'touchsize') . '</option>
	</select>';
	$html .= '<p class="description">' .$args[0]. '</p>';

	echo $html;
}
function toggle_onepage_website_callback($args)
{
	$options = get_option('videotouch_general');

	$html = '<select name="videotouch_general[onepage_website]">
		<option value="Y" '. selected( @$options["onepage_website"], 'Y', false ). '>' . __('Yes', 'touchsize') . '</option>
		<option value="N" '. selected( @$options["onepage_website"], 'N', false ). '>' . __('No', 'touchsize') . '</option>
	</select>';
	$html .= '<p class="description">' .$args[0]. '</p>';

	echo $html;
}

function toggle_featured_image_in_post_callback($args)
{
	$options = get_option('videotouch_general');

	$html = '<select name="videotouch_general[featured_image_in_post]">
					<option value="Y" '. selected( @$options["featured_image_in_post"], 'Y', false ). '>'.__( 'Yes', 'touchsize' ).'</option>
					<option value="N" '. selected( @$options["featured_image_in_post"], 'N', false ).'>'.__( 'No', 'touchsize' ).'</option>
				</select>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo $html;
}

function toggle_enable_imagesloaded_callback($args){

	$options = get_option('videotouch_general');

	$html = '<select name="videotouch_general[enable_imagesloaded]">
					<option value="Y" '. selected( @$options["enable_imagesloaded"], 'Y', false ). '>'.__( 'Yes', 'touchsize' ).'</option>
					<option value="N" '. selected( @$options["enable_imagesloaded"], 'N', false ).'>'.__( 'No', 'touchsize' ).'</option>
				</select>';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo $html;
}

function toggle_enable_lightbox_callback($args)
{
	$options = get_option('videotouch_general');

	$html = '<select name="videotouch_general[enable_lightbox]">
					<option value="Y" '. selected( @$options["enable_lightbox"], 'Y', false ). '>'.__( 'Yes', 'touchsize' ).'</option>
					<option value="N" '. selected( @$options["enable_lightbox"], 'N', false ).'>'.__( 'No', 'touchsize' ).'</option>
				</select>';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo $html;
}

function toggle_human_type_date_format_callback($args)
{
	$options = get_option('videotouch_general');

	$html = '<select name="videotouch_general[human_type_date_format]">
					<option value="Y" '. selected( @$options["human_type_date_format"], 'Y', false ). '>'.__( 'Yes', 'touchsize' ).'</option>
					<option value="N" '. selected( @$options["human_type_date_format"], 'N', false ).'>'.__( 'No', 'touchsize' ).'</option>
				</select>';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo $html;
}

function toggle_comment_system_callback($args)
{
	$options = get_option('videotouch_general');

	$is_hidden = ( @$options["comment_system"] === 'default' ) ? 'hidden' : '';
	$facebook_id = @$options["facebook_id"];

	$html = '<select name="videotouch_general[comment_system]" id="ts_comment_system">
				<option value="default" '. selected( @$options["comment_system"], 'default', false ).'>'.__( 'Default', 'touchsize' ).'</option>
				<option value="facebook" '. selected( @$options["comment_system"], 'facebook', false ).'>Facebook</option>
			</select>

			<p class="description">' .$args[0]. '</p>

			<div id="facebook_app_id" class="' . $is_hidden . '">
				<p>' . __('Get a Facebook App ID', 'touchsize') . '</p>
				<input type="text" name="videotouch_general[facebook_id]" value="' . esc_attr($facebook_id) . '" />
			</div>

			<script>
				jQuery( document ).ready(function( $ ) {
					var facebook_id = $("#facebook_app_id");

					$("#ts_comment_system").change(function(){
						if ($(this).val() === "default") {
							facebook_id.addClass("hidden");
						} else {
							facebook_id.removeClass("hidden");
						}
					});
				});
			</script>';

	echo $html;
}

function facebook_page_modal_callback($args)
{
	$options = get_option('videotouch_general');

	$html = '<select name="videotouch_general[enable_facebook_box]" id="enable_facebook_box">
				<option value="Y" '. selected( @$options["enable_facebook_box"], 'Y', false ).'>'.__( 'Yes', 'touchsize' ).'</option>
				<option value="N" '. selected( @$options["enable_facebook_box"], 'N', false ). '>'.__( 'No', 'touchsize' ).'</option>
			</select>';
	$html .= '<p class="description">' .$args[0]. '</p>';

	$enable_facebook_box_options = ($options["enable_facebook_box"] === 'Y') ? '' : 'hidden';

	$html .= '<div id="facebook_page" class="'.$enable_facebook_box_options .'">
				<p>' . __('Page name', 'touchsize') . ':</p>
				<input type="text" id="facebook_box" name="videotouch_general[facebook_name]" value="' . @$options['facebook_name'] . '" />
			 </div>';

	echo $html;
}

function toggle_show_wp_admin_bar_callback($args)
{
	$options = get_option('videotouch_general');

	$html = '<select name="videotouch_general[show_wp_admin_bar]">
					<option value="Y" '. selected( @$options["show_wp_admin_bar"], 'Y', false ).'>'.__( 'Yes', 'touchsize' ).'</option>
					<option value="N" '. selected( @$options["show_wp_admin_bar"], 'N', false ). '>'.__( 'No', 'touchsize' ).'</option>
				</select>';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo $html;
}

function toggle_enable_sticky_menu_callback($args)
{
	$options = get_option('videotouch_general');

	$html = '<select name="videotouch_general[enable_sticky_menu]" id="enable_sticky_menu">
				<option value="Y" '. selected( @$options["enable_sticky_menu"], 'Y', false ).'>'.__( 'Yes', 'touchsize' ).'</option>
				<option value="N" '. selected( @$options["enable_sticky_menu"], 'N', false ). '>'.__( 'No', 'touchsize' ).'</option>
			</select>';
	$html .= '<p class="description">' .$args[0]. '</p>';

	$enable_sticky_menu_options = ($options["enable_sticky_menu"] === 'Y') ? '' : 'hidden';

	$html .= '<div id="sticky_menu_options" class="'.$enable_sticky_menu_options .'">
			<p>' . __('Background color', 'touchsize') . ':</p>
			<input type="text" id="sticky_menu_bg_color" name="videotouch_general[sticky_menu_bg_color]" value="' . @$options['sticky_menu_bg_color'] . '" />
			<div id="sticky_menu_bg_color_picker"></div>

			<p>' . __('Text color', 'touchsize') . ':</p>
			<input type="text" id="sticky_menu_text_color" name="videotouch_general[sticky_menu_text_color]" value="' . @$options['sticky_menu_text_color'] . '" />
			<div id="sticky_menu_text_color_picker"></div>
			</div>';

	echo $html;
}

function toggle_enable_mega_menu_callback($args)
{
	$options = get_option('videotouch_general');

	$html = '<select name="videotouch_general[enable_mega_menu]" id="enable_mega_menu">
				<option value="Y" '. selected( @$options["enable_mega_menu"], 'Y', false ).'>'.__( 'Yes', 'touchsize' ).'</option>
				<option value="N" '. selected( @$options["enable_mega_menu"], 'N', false ). '>'.__( 'No', 'touchsize' ).'</option>
			</select>';
	$html .= '<p class="description">' .$args[0]. '</p>';

	echo $html;
}

function toggle_like_callback($args)
{
	$options = get_option('videotouch_general');

	$html = '<select name="videotouch_general[like]" class="enable-likes">
				<option value="y" '. selected( @$options["like"], 'y', false ).'>'.__( 'Yes', 'touchsize' ).'</option>
				<option value="n" '. selected( @$options["like"], 'n', false ). '>'.__( 'No', 'touchsize' ).'</option>
			</select>';
	$html .= '<p class="description">' .$args[0]. '</p>';

	echo $html;
}

function toggle_like_icons_callback($args)
{
	$options = get_option('videotouch_general');

	$html = '<div class="icons-likes">
				<ul class="imageRadioMetaUl perRow-3 builder-icon-list ts-custom-selector" data-selector="#likes-icons">
	               <li><i class="icon-heart clickable-element" data-option="heart"></i></li>
	               <li><i class="icon-thumb clickable-element" data-option="thumb"></i></li>
	               <li><i class="icon-star clickable-element" data-option="star"></i></li>
	            </ul>
	            <select class="hidden" name="videotouch_general[like_icons]" id="likes-icons">
	                <option value="heart" '. selected( @$options["like_icons"], 'heart', false ). '>' . __( 'Heart', 'touchsize' ) . '</option>
	                <option value="thumb" '. selected( @$options["like_icons"], 'thumb', false ). '>' . __( 'Thumb', 'touchsize' ) . '</option>
	                <option value="star" '. selected( @$options["like_icons"], 'star', false ). '>' . __( 'Star', 'touchsize' ) . '</option>
	            </select>
	         </div>';
	$html .= '<p class="description">' .$args[0]. '</p>';
	echo $html;
}

function toggle_post_publish_user_callback($args)
{
	$options = get_option('videotouch_general');

	$html = '<div>
	            <select name="videotouch_general[post_publish_user]">
	                <option value="publish" '. selected( @$options["post_publish_user"], 'publish', false ). '>' . __( 'Publish', 'touchsize' ) . '</option>
	                <option value="pending" '. selected( @$options["post_publish_user"], 'pending', false ). '>' . __( 'Pending', 'touchsize' ) . '</option>
	            </select>
	         </div>';
	$html .= '<p class="description">' .$args[0]. '</p>';
	echo $html;
}

function toggle_login_register_by_facebook_callback($args)
{
	$options = get_option('videotouch_general');

	$html = '<div>
	            <select name="videotouch_general[login_register_by_facebook]">
	                <option value="y" '. selected( @$options["login_register_by_facebook"], 'y', false ). '>' . __( 'Yes', 'touchsize' ) . '</option>
	                <option value="n" '. selected( @$options["login_register_by_facebook"], 'n', false ). '>' . __( 'No', 'touchsize' ) . '</option>
	            </select>
	         </div>';
	$html .= '<p class="description">' .$args[0]. '</p>'; ?>

	<script>
		jQuery(document).ready(function(){
			jQuery('select[name="videotouch_general[login_register_by_facebook]"]').change(function(){
				if( jQuery(this).val() == 'y' ){
					jQuery('input[name="videotouch_general[facebook_app_id]"]').closest('tr').css('display', '');
					jQuery('input[name="videotouch_general[facebook_app_secret]"]').closest('tr').css('display', '');
				}else{
					jQuery('input[name="videotouch_general[facebook_app_id]"]').closest('tr').css('display', 'none');
					jQuery('input[name="videotouch_general[facebook_app_secret]"]').closest('tr').css('display', 'none');
				}
			});

			if( jQuery('select[name="videotouch_general[login_register_by_facebook]"]').val() == 'y' ){
				jQuery('input[name="videotouch_general[facebook_app_id]"]').closest('tr').css('display', '');
				jQuery('input[name="videotouch_general[facebook_app_secret]"]').closest('tr').css('display', '');
			}else{
				jQuery('input[name="videotouch_general[facebook_app_id]"]').closest('tr').css('display', 'none');
				jQuery('input[name="videotouch_general[facebook_app_secret]"]').closest('tr').css('display', 'none');
			}
		});
	</script>

	<?php
	echo $html;
}

function toggle_generate_likes_callback($args)
{
	$html = '<div class="generate-likes">
				<input type="button" id="generate-likes" value="' . __( "Generate likes", "touchsize" ) . '" />
				<div style="display:none;" class="ts-wait">' . __('Please wait...', 'touchsize') . '</div>
				<div style="display:none;" class="ts-succes-like">' . __('Done!', 'touchsize') . '</div>
				<div style="display:none;" class="ts-error-like">' . __('Error!', 'touchsize') . '</div>
			</div>';
	$html .= '<p class="description">' .$args[0]. '</p>';

	echo $html;
}

function toggle_tracking_code_callback($args)
{
	$options = get_option('videotouch_general');

	$html = '<textarea name="videotouch_general[tracking_code]">'.esc_attr(@$options["tracking_code"]).'</textarea>';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo $html;
}

function toggle_grid_excerpt_callback($args)
{
	$options = get_option('videotouch_general');

	$html = '<input type="text" name="videotouch_general[grid_excerpt]" value="'.esc_attr(@$options["grid_excerpt"]).'" />';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo $html;
}

function toggle_list_excerpt_callback($args)
{
	$options = get_option('videotouch_general');

	$html = '<input type="text" name="videotouch_general[list_excerpt]" value="'.esc_attr(@$options["list_excerpt"]).'" />';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo $html;
}

function toggle_bigpost_excerpt_callback($args)
{
	$options = get_option('videotouch_general');

	$html = '<input type="text" name="videotouch_general[bigpost_excerpt]" value="'.esc_attr(@$options["bigpost_excerpt"]).'" />';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo $html;
}

function toggle_timeline_excerpt_callback($args)
{
	$options = get_option('videotouch_general');

	$html = '<input type="text" name="videotouch_general[timeline_excerpt]" value="'.esc_attr(@$options["timeline_excerpt"]).'" />';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo $html;
}

function toggle_facebook_app_id_callback($args)
{
	$options = get_option('videotouch_general');

	$html = '<input type="text" name="videotouch_general[facebook_app_id]" value="'.esc_attr(@$options["facebook_app_id"]).'" />';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo $html;
}

function toggle_facebook_app_secret_callback($args)
{
	$options = get_option('videotouch_general');

	$html = '<input type="text" name="videotouch_general[facebook_app_secret]" value="'.esc_attr(@$options["facebook_app_secret"]).'" />';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo $html;
}

function toggle_insert_post_user_callback($args)
{
	$options = get_option('videotouch_general');

    $html = '<select name="videotouch_general[insert_post_user]">
                <option value="post" '. selected($options["insert_post_user"], 'post', false). '>' . __('Post', 'touchsize') . '</option>
                <option value="video" '. selected($options["insert_post_user"], 'video', false). '>' . __('Video', 'touchsize') . '</option>
                <option value="post-video" '. selected($options["insert_post_user"], 'post-video', false). '>' . __('Post & Video', 'touchsize') . '</option>
            </select>
	        ';
	$html .= '<p class="description">' .$args[0]. '</p>';
	echo $html;
}

function toggle_ts_seo_callback($args)
{
	$options = get_option('videotouch_general');

    $html = '<select name="videotouch_general[ts_seo]">
                <option value="y" '. selected($options["ts_seo"], 'y', false) . '>' . __('Yes', 'touchsize') . '</option>
                <option value="n" ' . selected($options["ts_seo"], 'n', false) . '>' . __('No', 'touchsize') . '</option>
            </select>
	        ';
	$html .= '<p class="description">' .$args[0]. '</p>';
	echo $html;
}

function toggle_slug_video_callback($args)
{
	$options = get_option('videotouch_general');

    $html = '<input type="text" name="videotouch_general[slug_video]" value="' . $options['slug_video'] . '"/>';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo $html;
}

function toggle_slug_video_taxonomy_callback($args)
{
	$options = get_option('videotouch_general');

    $html = '<input type="text" name="videotouch_general[slug_video_taxonomy]" value="' . $options['slug_video_taxonomy'] . '"/>';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo $html;
}

function toggle_slug_portfolio_callback($args)
{
	$options = get_option('videotouch_general');

    $html = '<input type="text" name="videotouch_general[slug_portfolio]" value="' . $options['slug_portfolio'] . '"/>';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo $html;
}

function toggle_slug_portfolio_taxonomy_callback($args)
{
	$options = get_option('videotouch_general');

    $html = '<input type="text" name="videotouch_general[slug_portfolio_taxonomy]" value="' . $options['slug_portfolio_taxonomy'] . '"/>';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo $html;
}


function videotouch_initialize_image_sizes_options($args) {

	if( false === get_option( 'videotouch_image_sizes' ) ) {

		$defaults = array(
			'grid' => array(
				'width'  => '450',
				'height' => '350',
				'mode'   => 'crop'
			),
			'thumbnails' => array(
				'width'  => '450',
				'height' => '370',
				'mode'   => 'crop'
			),
			'bigpost' => array(
				'width'  => '720',
				'height' => '400',
				'mode'   => 'crop'
			),
			'superpost' => array(
				'width'  => '700',
				'height' => '350',
				'mode'   => 'crop'
			),
			'single' => array(
				'width'  => '1140',
				'height' => '9999',
				'mode'   => 'resize'
			),
			'portfolio' => array(
				'width'  => '1140',
				'height' => '9999',
				'mode'   => 'resize'
			),
			'featarea' => array(
				'width'  => '920',
				'height' => '440',
				'mode'   => 'crop'
			),
			'slider' => array(
				'width'  => '1920',
				'height' => '650',
				'mode'   => 'crop'
			),
			'carousel' => array(
				'width'  => '9999',
				'height' => '400',
				'mode'   => 'resize'
			),
			'timeline' => array(
				'width'  => '700',
				'height' => '280',
				'mode'   => 'resize'
			),
		);

		add_option( 'videotouch_image_sizes', $defaults );
	}

	// Register  section
	add_settings_section(
		'image_sizes_section',
		__( 'Image sizes', 'touchsize' ),
		'videotouch_image_sizes_callback',
		'videotouch_image_sizes'
	);

	add_settings_field(
		'grid',
		__( 'Grid view', 'touchsize' ),
		'toggle_image_sizes_grid_callback',
		'videotouch_image_sizes',
		'image_sizes_section'
	);

	add_settings_field(
		'thumbnails',
		__( 'Thumbnails view', 'touchsize' ),
		'toggle_image_sizes_thumbnails_callback',
		'videotouch_image_sizes',
		'image_sizes_section'
	);

	add_settings_field(
		'bigpost',
		__( 'Big post view', 'touchsize' ),
		'toggle_image_sizes_bigpost_callback',
		'videotouch_image_sizes',
		'image_sizes_section'
	);

	add_settings_field(
		'superpost',
		__( 'Super post view', 'touchsize' ),
		'toggle_image_sizes_superpost_callback',
		'videotouch_image_sizes',
		'image_sizes_section'
	);

	add_settings_field(
		'single',
		__( 'Single view', 'touchsize' ),
		'toggle_image_sizes_single_callback',
		'videotouch_image_sizes',
		'image_sizes_section'
	);

	add_settings_field(
		'portfolio',
		__( 'Portfolio view', 'touchsize' ),
		'toggle_image_sizes_portfolio_callback',
		'videotouch_image_sizes',
		'image_sizes_section'
	);

	add_settings_field(
		'portfolio',
		__( 'Featured area view', 'touchsize' ),
		'toggle_image_sizes_featarea_callback',
		'videotouch_image_sizes',
		'image_sizes_section'
	);

	add_settings_field(
		'slider',
		__( 'Slider image size', 'touchsize' ),
		'toggle_image_sizes_slider_callback',
		'videotouch_image_sizes',
		'image_sizes_section'
	);

	add_settings_field(
		'carousel',
		__( 'Carousel image size', 'touchsize' ),
		'toggle_image_sizes_carousel_callback',
		'videotouch_image_sizes',
		'image_sizes_section'
	);

	add_settings_field(
		'timeline',
		__( 'Timeline post view', 'touchsize' ),
		'toggle_image_sizes_timeline_callback',
		'videotouch_image_sizes',
		'image_sizes_section'
	);

	register_setting( 'videotouch_image_sizes', 'videotouch_image_sizes');
}

add_action( 'admin_init', 'videotouch_initialize_image_sizes_options' );


function videotouch_image_sizes_callback() {
	echo '<p>'.__( 'In this tab you can choose the dimensions for the images that are used on your website. Caution - these are not the sizes that will be shown on the website as the website is adaptive, but it is the size of the images that will be used. We strongly recommend to use given settings and not to fiddle with any as long as you are not sure what you are doing.', 'touchsize' ).'</p>';
}

function toggle_image_sizes_grid_callback($args) {

	$options = get_option( 'videotouch_image_sizes' );
	$options = @$options['grid'];

	$html = '<p>'.__('Width', 'touchsize').' (px)</p>
			<input type="text" name="videotouch_image_sizes[grid][width]" value="'.esc_attr(@$options['width']).'">
			<p>'.__('Height', 'touchsize').' (px)</p>
			<input type="text" name="videotouch_image_sizes[grid][height]" value="'.esc_attr(@$options['height']).'"><br/><br/>
			<p>
				<input type="radio" name="videotouch_image_sizes[grid][mode]" id="img-sizes-grid-crop" '. checked( @$options['mode'], 'crop', false).' value="crop" />
				<label for="img-sizes-grid-crop">'.__('Crop', 'touchsize').' </label><br/>
				<input type="radio" name="videotouch_image_sizes[grid][mode]" id="img-sizes-grid-resize" '. checked( @$options['mode'], 'resize', false ).' value="resize" />
				<label for="img-sizes-grid-resize">'.__('Resize', 'touchsize').'</label>
			</p>';

	echo $html;
}

function toggle_image_sizes_thumbnails_callback($args) {

	$options = get_option( 'videotouch_image_sizes' );
	$options = @$options['thumbnails'];


	$html = '<p>'.__('Width', 'touchsize').' (px)</p>
			<input type="text" name="videotouch_image_sizes[thumbnails][width]" value="'.esc_attr(@$options['width']).'">
			<p>'.__('Height', 'touchsize').' (px)</p>
			<input type="text" name="videotouch_image_sizes[thumbnails][height]" value="'.esc_attr(@$options['height']).'"><br/><br/>
			<input type="hidden" name="videotouch_image_sizes[thumbnails][mode]" value="crop"><br/><br/>
			<em>'.__('Images for thumbnails view are cropped', 'touchsize').'</em>';

	echo $html;
}

function toggle_image_sizes_bigpost_callback($args) {

	$options = get_option( 'videotouch_image_sizes' );
	$options = @$options['bigpost'];

	$html = '<p>'.__('Width', 'touchsize').' (px)</p>
			<input type="text" name="videotouch_image_sizes[bigpost][width]" value="'.esc_attr(@$options['width']).'">
			<p>'.__('Height', 'touchsize').' (px)</p>
			<input type="text" name="videotouch_image_sizes[bigpost][height]" value="'.esc_attr(@$options['height']).'"><br/><br/>
			<p>
				<input type="radio" name="videotouch_image_sizes[bigpost][mode]" id="img-sizes-bigpost-crop" '. checked( @$options['mode'], 'crop', false).' value="crop" />
				<label for="img-sizes-bigpost-crop">'.__('Crop', 'touchsize').' </label><br/>
				<input type="radio" name="videotouch_image_sizes[bigpost][mode]" id="img-sizes-bigpost-resize" '. checked( @$options['mode'], 'resize', false ).' value="resize" />
				<label for="img-sizes-bigpost-resize">'.__('Resize', 'touchsize').'</label>
			</p>';

	echo $html;
}

function toggle_image_sizes_superpost_callback($args) {

	$options = get_option( 'videotouch_image_sizes' );
	$options = @$options['superpost'];

	$html = '<p>'.__('Width', 'touchsize').' (px)</p>
			<input type="text" name="videotouch_image_sizes[superpost][width]" value="'.esc_attr(@$options['width']).'">
			<p>'.__('Height', 'touchsize').' (px)</p>
			<input type="text" name="videotouch_image_sizes[superpost][height]" value="'.esc_attr(@$options['height']).'"><br/><br/>
			<p>
				<input type="radio" name="videotouch_image_sizes[superpost][mode]" id="img-sizes-superpost-crop" '. checked( @$options['mode'], 'crop', false).' value="crop" />
				<label for="img-sizes-superpost-crop">'.__('Crop', 'touchsize').' </label><br/>
				<input type="radio" name="videotouch_image_sizes[superpost][mode]" id="img-sizes-superpost-resize" '. checked( @$options['mode'], 'resize', false ).' value="resize" />
				<label for="img-sizes-superpost-resize">'.__('Resize', 'touchsize').'</label>
			</p>';

	echo $html;
}

function toggle_image_sizes_single_callback($args) {

	$options = get_option( 'videotouch_image_sizes' );
	$options = @$options['single'];

	$html = '<p>'.__('Width', 'touchsize').' (px)</p>
			<input type="text" name="videotouch_image_sizes[single][width]" value="'.esc_attr(@$options['width']).'">
			<p>'.__('Height', 'touchsize').' (px)</p>
			<input type="text" name="videotouch_image_sizes[single][height]" value="'.esc_attr(@$options['height']).'"><br/><br/>
			<p>
				<input type="radio" name="videotouch_image_sizes[single][mode]" id="img-sizes-single-crop" '. checked( @$options['mode'], 'crop', false).' value="crop" />
				<label for="img-sizes-single-crop">'.__('Crop', 'touchsize').' </label><br/>
				<input type="radio" name="videotouch_image_sizes[single][mode]" id="img-sizes-single-resize" '. checked( @$options['mode'], 'resize', false ).' value="resize" />
				<label for="img-sizes-single-resize">'.__('Resize', 'touchsize').'</label>
			</p>';

	echo $html;
}

function toggle_image_sizes_timeline_callback($args) {

	$options = get_option( 'videotouch_image_sizes' );
	$options = @$options['timeline'];

	$html = '<p>'.__('Width', 'touchsize').' (px)</p>
			<input type="text" name="videotouch_image_sizes[timeline][width]" value="'.esc_attr(@$options['width']).'">
			<p>'.__('Height', 'touchsize').' (px)</p>
			<input type="text" name="videotouch_image_sizes[timeline][height]" value="'.esc_attr(@$options['height']).'"><br/><br/>
			<p>
				<input type="radio" name="videotouch_image_sizes[timeline][mode]" id="img-sizes-timeline-crop" '. checked( @$options['mode'], 'crop', false).' value="crop" />
				<label for="img-sizes-timeline-crop">'.__('Crop', 'touchsize').' </label><br/>
				<input type="radio" name="videotouch_image_sizes[timeline][mode]" id="img-sizes-timeline-resize" '. checked( @$options['mode'], 'resize', false ).' value="resize" />
				<label for="img-sizes-timeline-resize">'.__('Resize', 'touchsize').'</label>
			</p>';

	echo $html;
}

function toggle_image_sizes_portfolio_callback($args) {

	$options = get_option( 'videotouch_image_sizes' );
	$options = @$options['portfolio'];

	$html = '<p>'.__('Width', 'touchsize').' (px)</p>
			<input type="text" name="videotouch_image_sizes[portfolio][width]" value="'.esc_attr(@$options['width']).'">
			<p>'.__('Height', 'touchsize').' (px)</p>
			<input type="text" name="videotouch_image_sizes[portfolio][height]" value="'.esc_attr(@$options['height']).'"><br/><br/>
			<p>
				<input type="radio" name="videotouch_image_sizes[portfolio][mode]" id="img-sizes-portfolio-crop" '. checked( @$options['mode'], 'crop', false).' value="crop" />
				<label for="img-sizes-portfolio-crop">'.__('Crop', 'touchsize').' </label><br/>
				<input type="radio" name="videotouch_image_sizes[portfolio][mode]" id="img-sizes-portfolio-resize" '. checked( @$options['mode'], 'resize', false ).' value="resize" />
				<label for="img-sizes-portfolio-resize">'.__('Resize', 'touchsize').'</label>
			</p>';

	echo $html;
}

function toggle_image_sizes_featarea_callback($args) {

	$options = get_option( 'videotouch_image_sizes' );
	$options = @$options['featarea'];

	$html = '<p>'.__('Width', 'touchsize').' (px)</p>
			<input type="text" name="videotouch_image_sizes[featarea][width]" value="'.esc_attr(@$options['width']).'">
			<p>'.__('Height', 'touchsize').' (px)</p>
			<input type="text" name="videotouch_image_sizes[featarea][height]" value="'.esc_attr(@$options['height']).'"><br/><br/>
			<p>
				<input type="radio" name="videotouch_image_sizes[featarea][mode]" id="img-sizes-featarea-crop" '. checked( @$options['mode'], 'crop', false).' value="crop" />
				<label for="img-sizes-featarea-crop">'.__('Crop', 'touchsize').' </label><br/>
				<input type="radio" name="videotouch_image_sizes[featarea][mode]" id="img-sizes-featarea-resize" '. checked( @$options['mode'], 'resize', false ).' value="resize" />
				<label for="img-sizes-featarea-resize">'.__('Resize', 'touchsize').'</label>
			</p>';

	echo $html;
}

function toggle_image_sizes_slider_callback($args) {

	$options = get_option( 'videotouch_image_sizes' );
	$options = @$options['slider'];

	$html = '<p>'.__('Width', 'touchsize').' (px)</p>
			<input type="text" name="videotouch_image_sizes[slider][width]" value="'.esc_attr(@$options['width']).'">
			<p>'.__('Height', 'touchsize').' (px)</p>
			<input type="text" name="videotouch_image_sizes[slider][height]" value="'.esc_attr(@$options['height']).'"><br/><br/>
			<p>
				<input type="radio" name="videotouch_image_sizes[slider][mode]" id="img-sizes-slider-crop" '. checked( @$options['mode'], 'crop', false).' value="crop" />
				<label for="img-sizes-slider-crop">'.__('Crop', 'touchsize').' </label><br/>
				<input type="radio" name="videotouch_image_sizes[slider][mode]" id="img-sizes-slider-resize" '. checked( @$options['mode'], 'resize', false ).' value="resize" />
				<label for="img-sizes-slider-resize">'.__('Resize', 'touchsize').'</label>
			</p>';

	echo $html;
}

function toggle_image_sizes_carousel_callback($args) {

	$options = get_option( 'videotouch_image_sizes' );
	$options = @$options['carousel'];

	$html = '<p>'.__('Width', 'touchsize').' (px)</p>
			<input type="text" name="videotouch_image_sizes[carousel][width]" value="'.esc_attr(@$options['width']).'">
			<p>'.__('Height', 'touchsize').' (px)</p>
			<input type="text" name="videotouch_image_sizes[carousel][height]" value="'.esc_attr(@$options['height']).'"><br/><br/>
			<p>
				<input type="radio" name="videotouch_image_sizes[carousel][mode]" id="img-sizes-carousel-crop" '. checked( @$options['mode'], 'crop', false).' value="crop" />
				<label for="img-sizes-carousel-crop">'.__('Crop', 'touchsize').' </label><br/>
				<input type="radio" name="videotouch_image_sizes[carousel][mode]" id="img-sizes-carousel-resize" '. checked( @$options['mode'], 'resize', false ).' value="resize" />
				<label for="img-sizes-carousel-resize">'.__('Resize', 'touchsize').'</label>
			</p>';

	echo $html;
}

function videotouch_initialize_layout_options() {

	if( false === get_option( 'videotouch_layout' ) ) {

		add_option( 'videotouch_layout', array() );

		$data = array();

		$layouts = array(
			'single_layout',
			'page_layout',
			'blog_layout',
			'category_layout',
			'author_layout',
			'search_layout',
			'archive_layout',
			'tags_layout',
			'product_layout',
			'show_layout'
		);

		$default_style = array(
			'sidebar' => array(
				'position' => 'none',
				'size'     => '1-3',
				'id'       => '0'
			),
			'display-mode'    => 'big-post',
			'grid' => array(
				'enable-carousel' => 'n',
				'display-title'   => 'title-above-excerpt',
				'show-meta'       => 'y',
				'elements-per-row'=> '3',
				'special-effects' => 'none'
			),
			'list' => array(
				'display-title'   => 'title-above-excerpt',
				'show-meta'       => 'y',
				'image-split'     => '1-2',
				'special-effects' => 'none'
			),
			'thumbnails' => array(
				'enable-carousel' => 'n',
				'elements-per-row'=> '3',
				'special-effects' => 'none',
				'gutter'		  => 'n'
			),
			'big-post' => array(
				'display-title'   => 'title-above-excerpt',
				'show-meta'       => 'y',
				'image-split'     => '1-2',
				'related-posts'   => 'n',
				'special-effects' => 'none'
			),
			'super-post' => array(
				'elements-per-row'=> '3',
				'special-effects' => 'none'
			)
		);

		foreach ($layouts as $layout_id => $layout) {
			if ($layout_id === 'single_layout' || $layout_id === 'page_layout') {
				$data[$layout] = $default_style['sidebar'];
			}

			$data[$layout] = $default_style;
		}

		update_option( 'videotouch_layout', $data );

	} // end if

	// Register  section
	add_settings_section(
		'layout_settings_section',
		__( 'Default layout settings', 'touchsize' ),
		'videotouch_layout_category_callback',
		'videotouch_layout'
	);

	add_settings_field(
		'single_layout',
		__( 'Single', 'touchsize' ),
		'toggle_single_layout_callback',
		'videotouch_layout',
		'layout_settings_section'
	);

	add_settings_field(
		'page_layout',
		__( 'Page', 'touchsize' ),
		'toggle_page_layout_callback',
		'videotouch_layout',
		'layout_settings_section'
	);

	add_settings_field(
		'blog_layout',
		__( 'Blog page', 'touchsize' ),
		'toggle_blog_layout_callback',
		'videotouch_layout',
		'layout_settings_section'
	);

	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if( is_plugin_active( 'woocommerce/woocommerce.php' ) ){

	      	add_settings_field(
				'product_layout',
				__( 'Product', 'touchsize' ),
				'toggle_product_layout_callback',
				'videotouch_layout',
				'layout_settings_section'
			);

	      	add_settings_field(
				'shop_layout',
				__( 'Shop page', 'touchsize' ),
				'toggle_shop_layout_callback',
				'videotouch_layout',
				'layout_settings_section'
			);
		}

	add_settings_field(
		'category_layout',
		__( 'Category', 'touchsize' ),
		'toggle_category_layout_callback',
		'videotouch_layout',
		'layout_settings_section'
	);
	add_settings_field(
		'author_layout',
		__( 'Author', 'touchsize' ),
		'toggle_author_layout_callback',
		'videotouch_layout',
		'layout_settings_section'
	);

	add_settings_field(
		'search_layout',
		__( 'Search', 'touchsize' ),
		'toggle_search_layout_callback',
		'videotouch_layout',
		'layout_settings_section'
	);

	add_settings_field(
		'archive_layout',
		__( 'Archive', 'touchsize' ),
		'toggle_archive_layout_callback',
		'videotouch_layout',
		'layout_settings_section'
	);

	add_settings_field(
		'tags_layout',
		__( 'Tags', 'touchsize' ),
		'toggle_tags_layout_callback',
		'videotouch_layout',
		'layout_settings_section'
	);

	register_setting( 'videotouch_layout', 'videotouch_layout');
}

add_action( 'admin_init', 'videotouch_initialize_layout_options' );

function videotouch_layout_category_callback() {
	echo '<p>'.__( 'This is the default layouts settings area. Here you can set the defaults for your website. Default sidebar settings and the way articles are going to be shown on archive pages.', 'touchsize' ).'</p>';
}

function toggle_single_layout_callback($args)
{
	$options = get_option('videotouch_layout');

	$html = '<table><tr><td><strong>' . __( 'Sidebar position', 'touchsize' ) . '</strong>';
	$html.= videotouch_sidebar_settings_generator('single_layout', $options);
	$html.= '</td></tr></table>';

	echo $html;
}

function toggle_product_layout_callback($args)
{
	$options = get_option('videotouch_layout');

	$html = '<table><tr><td><strong>' . __( 'Sidebar position', 'touchsize' ) . '</strong>';
	$html.= videotouch_sidebar_settings_generator('product_layout', $options);
	$html.= '</td></tr></table>';

	echo $html;
}

function toggle_shop_layout_callback($args)
{
	$options = get_option('videotouch_layout');

	$html = '<table><tr><td><strong>' . __( 'Sidebar position', 'touchsize' ) . '</strong>';
	$html.= videotouch_sidebar_settings_generator('shop_layout', $options);
	$html.= '</td></tr></table>';

	echo $html;
}

function toggle_page_layout_callback($args)
{
	$options = get_option('videotouch_layout');

	$html = '<table><tr><td><strong>' . __( 'Sidebar position', 'touchsize' ) . '</strong>';
	$html.= videotouch_sidebar_settings_generator('page_layout', $options);
	$html.= '</td></tr></table>';

	echo $html;
}

function toggle_category_layout_callback($args)
{
	$options = get_option('videotouch_layout');

	$html = '<table><tr><td><strong>' . __( 'Sidebar position', 'touchsize' ) . '</strong>';
	$html.= videotouch_sidebar_settings_generator('category_layout', $options);
	$html.= '</td></tr><tr><td>'.videotouch_layout_style_generator('category_layout', $options).'</td></tr></table>';

	echo $html;
}

function toggle_blog_layout_callback($args)
{
	$options = get_option('videotouch_layout');

	$html = '<table><tr><td><strong>' . __( 'Sidebar position', 'touchsize' ) . '</strong>';
	$html.= videotouch_sidebar_settings_generator('blog_layout', $options);
	$html.= '</td></tr><tr><td>'.videotouch_layout_style_generator('blog_layout', $options).'</td></tr></table>';

	echo $html;
}

function toggle_author_layout_callback($args)
{
	$options = get_option('videotouch_layout');

	$html = '<table><tr><td><strong>' . __( 'Sidebar position', 'touchsize' ) . '</strong>';
	$html.= videotouch_sidebar_settings_generator('author_layout', $options);
	$html.= '</td></tr><tr><td>'.videotouch_layout_style_generator('author_layout', $options).'</td></tr></table>';

	echo $html;
}

function toggle_search_layout_callback($args)
{
	$options = get_option('videotouch_layout');

	$html = '<table><tr><td><strong>' . __( 'Sidebar position', 'touchsize' ) . '</strong>';
	$html.= videotouch_sidebar_settings_generator('search_layout', $options);
	$html.= '</td></tr><tr><td>'.videotouch_layout_style_generator('search_layout', $options).'</td></tr></table>';

	echo $html;
}

function toggle_archive_layout_callback($args)
{
	$options = get_option('videotouch_layout');

	$html = '<table><tr><td><strong>' . __( 'Sidebar position', 'touchsize' ) . '</strong>';
	$html.= videotouch_sidebar_settings_generator('archive_layout', $options);
	$html.= '</td></tr><tr><td>'.videotouch_layout_style_generator('archive_layout', $options).'</td></tr></table>';

	echo $html;
}

function toggle_tags_layout_callback($args)
{
	$options = get_option('videotouch_layout');

	$html = '<table><tr><td><strong>' . __( 'Sidebar position', 'touchsize' ) . '</strong>';
	$html.= videotouch_sidebar_settings_generator('tags_layout', $options);
	$html.= '</td></tr><tr><td>'.videotouch_layout_style_generator('tags_layout', $options).'</td></tr></table>';

	echo $html;
}

function videotouch_sidebar_settings_generator($section = 'category_layout', $options = array()) {

	$html = '<select name="videotouch_layout['.$section.'][sidebar][position]">
				<option value="none" '. selected( @$options[$section]['sidebar']['position'], 'none', 0 ).'>None</option>
				<option value="left" '. selected( @$options[$section]['sidebar']['position'], 'left', 0 ).'>Left</option>
				<option value="right" '. selected( @$options[$section]['sidebar']['position'], 'right', 0 ).'>Right</option>
			</select>';

	$html .= '<strong>' . __( 'Sidebar size', 'touchsize' ).'</strong>';
	$html .= '<select name="videotouch_layout['.$section.'][sidebar][size]">
				<option value="1-3" '. selected( @$options[$section]['sidebar']['size'], '1-3', 0 ).'>1/3</option>
				<option value="1-4" '. selected( @$options[$section]['sidebar']['size'], '1-4', 0 ).'>1/4</option>
			</select>';

	$html .= '<strong>' . __( 'Sidebar name', 'touchsize' ).'</strong>';
	$html .= ts_sidebars_drop_down(@$options[$section]['sidebar']['id'], $section.'_sidebar', 'videotouch_layout['.$section.'][sidebar][id]');

	return $html;
}

function videotouch_layout_style_generator($section = 'category_layout', $options = array()) {

	$show_grid = (@$options[$section]['display-mode'] === 'grid') ? '' : 'hidden';
	$show_list = (@$options[$section]['display-mode'] === 'list') ? '' : 'hidden';
	$show_thumbnails = (@$options[$section]['display-mode'] === 'thumbnails') ? '' : 'hidden';
	$show_big_post = (@$options[$section]['display-mode'] === 'big-post') ? '' : 'hidden';
	$show_super_post = (@$options[$section]['display-mode'] === 'super-post') ? '' : 'hidden';

	switch ($section) {
		case 'category_layout':
			$prefix = 'category';
			break;

		case 'blog_layout':
			$prefix = 'blog';
			break;

		case 'author_layout':
			$prefix = 'author';
			break;

		case 'search_layout':
			$prefix = 'search';
			break;

		case 'archive_layout':
			$prefix = 'archive';
			break;

		case 'tags_layout':
			$prefix = 'tags';
			break;

		default:
			$prefix = '';
			break;
	}
	return '<span class="icon-resize-vertical display-layout-options">Show view options <em>(click to toggle)</em></span><div class="builder-elements layout-settings-fields hidden">
                <!-- Display mode -->
                <table cellpadding="10">
                    <tr>
                        <td>
                            <label for="'.$prefix.'-last-posts-display-mode">'.__( 'How to display', 'touchsize' ) . ':</label>
                        </td>
                        <td>
                            <select name="videotouch_layout['.$section.'][display-mode]" class="'.$prefix.'-last-posts-display-mode">
                                <option value="grid" '. selected( @$options[$section]['display-mode'], 'grid', 0 ).'>'.__( 'Grid', 'touchsize' ) .'</option>
                                <option value="list" ' . selected( @$options[$section]['display-mode'], 'list', 0 ).'>'.__( 'List', 'touchsize' ) .'</option>
                                <option value="thumbnails" ' . selected( @$options[$section]['display-mode'], 'thumbnails', 0 ).'>'.__( 'Thumbnails', 'touchsize' ) .'</option>
                                <option value="big-post" ' . selected( @$options[$section]['display-mode'], 'big-post', 0 ) . '>'.__( 'Big post', 'touchsize' ) .'</option>
                                <option value="super-post" ' . selected( @$options[$section]['display-mode'], 'super-post', 0 ) . '>'.__( 'Super Post', 'touchsize' ) .'</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <div class="'.$prefix.'-last-posts-display-mode-options">
                    <!-- Grid options -->
                    <div class="last-posts-grid '.$show_grid.'">
                        <table cellpadding="10">
                            <tr>
                                <td>
                                    <label for="'.$section.'-last-posts-grid-title">'.__( 'Title', 'touchsize' ).':</label>
                                </td>
                                <td>
                                    <select name="videotouch_layout['.$section.'][grid][display-title]" id="'.$section.'-last-posts-grid-title">
                                        <option value="title-above-image" ' . selected( @$options[$section]['grid']['display-title'], 'title-above-image', 0 ) . '>'.__( 'Above image', 'touchsize' ).'</option>
                                        <option value="title-above-excerpt" ' . selected( @$options[$section]['grid']['display-title'], 'title-above-excerpt', 0 ) . '>'.__( 'Above excerpt', 'touchsize' ).'</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="'.$section.'-last-posts-grid-show-meta">'.__( 'Show meta', 'touchsize' ).':</label>
                                </td>
                                <td>
                                    <input type="radio" name="videotouch_layout['.$section.'][grid][show-meta]" id="'.$section.'-last-posts-grid-show-meta-y"  value="y" '.checked( @$options[$section]['grid']['show-meta'], 'y', 0 ).' />
                                    <label for="'.$section.'-last-posts-grid-show-meta-y">'.__( 'Yes', 'touchsize' ).'</label>
                                    <input type="radio" name="videotouch_layout['.$section.'][grid][show-meta]" id="'.$section.'-last-posts-grid-show-meta-n" value="n" '.checked( @$options[$section]['grid']['show-meta'], 'n', 0 ).'/>
                                    <label for="'.$section.'-last-posts-grid-show-meta-n">'.__( 'No', 'touchsize' ).'</label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="'.$section.'-last-posts-grid-el-per-row">'.__( 'Elements per row', 'touchsize' ).'</label>
                                </td>
                                <td>
                                    <select name="videotouch_layout['.$section.'][grid][elements-per-row]" id="'.$section.'-last-posts-grid-el-per-row">
                                        <option value="1" ' . selected( @$options[$section]['grid']['elements-per-row'], '1', 0 ) . '>1</option>
                                        <option value="2" ' . selected( @$options[$section]['grid']['elements-per-row'], '2', 0 ) . '>2</option>
                                        <option value="3" ' . selected( @$options[$section]['grid']['elements-per-row'], '3', 0 ) . '>3</option>
                                        <option value="4" ' . selected( @$options[$section]['grid']['elements-per-row'], '4', 0 ) . '>4</option>
                                        <option value="6" ' . selected( @$options[$section]['grid']['elements-per-row'], '6', 0 ) . '>6</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- List options -->
                    <div class="last-posts-list '.$show_list.'">

                        <table cellpadding="10">
                            <tr>
                                <td>
                                    <label for="'.$section.'-last-posts-list-title">'.__( 'Title:', 'touchsize' ).':</label>
                                </td>
                                <td>
                                    <select name="videotouch_layout['.$section.'][list][display-title]" id="'.$section.'-last-posts-list-title">
                                        <option value="title-above-image" '. selected( @$options[$section]['list']['display-title'], 'title-above-image', 0 ) .'>'.__( 'Above image', 'touchsize' ).'</option>
                                        <option value="title-above-excerpt" '. selected( @$options[$section]['list']['display-title'], 'title-above-excerpt', 0 ) .'>'.__( 'Above excerpt', 'touchsize' ).'</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="'.$section.'-last-posts-list-show-meta">'.__( 'Show meta', 'touchsize' ).':</label>
                                </td>
                                <td>
                                    <input type="radio" name="videotouch_layout['.$section.'][list][show-meta]" id="'.$section.'-last-posts-list-show-meta-y"  value="y" '.checked( @$options[$section]['list']['show-meta'], 'y', 0 ).'  />
                                    <label for="'.$section.'-last-posts-list-show-meta-y">'.__( 'Yes', 'touchsize' ).'</label>
                                    <input type="radio" name="videotouch_layout['.$section.'][list][show-meta]" id="'.$section.'-last-posts-list-show-meta-n" value="n" '.checked( @$options[$section]['list']['show-meta'], 'n', 0 ).'/>
                                    <label for="'.$section.'-last-posts-list-show-meta-n">'.__( 'No', 'touchsize' ).'</label>
                                </td>
                            </tr>
                            <tr>
                                <td>'.__( 'Content split', 'touchsize' ).'</td>
                                <td>
                                    <select name="videotouch_layout['.$section.'][list][image-split]" id="'.$section.'-last-posts-list-image-split">
                                        <option value="1-3" '. selected( @$options[$section]['list']['image-split'], '1-3', 0 ) .'>1/3</option>
                                        <option value="1-2" '. selected( @$options[$section]['list']['image-split'], '1-2', 0 ) .'>1/2</option>
                                        <option value="3-4" '. selected( @$options[$section]['list']['image-split'], '3-4', 0 ) .'>3/4</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Thumbnail options -->
                    <div class="last-posts-thumbnails '.$show_thumbnails.'">
                        <table cellpadding="10">
                            <tr>
                                <td>
                                    <label for="'.$section.'-last-posts-thumbnail-posts-per-row">'.__( 'Number of posts per row', 'touchsize' ).':</label>
                                </td>
                                <td>
                                    <select name="videotouch_layout['.$section.'][thumbnails][elements-per-row]" id="'.$section.'-last-posts-thumbnail-posts-per-row">
                                        <option value="1" ' . selected( @$options[$section]['thumbnails']['elements-per-row'], '1', 0 ) .'>1</option>
                                        <option value="2" ' . selected( @$options[$section]['thumbnails']['elements-per-row'], '2', 0 ) .'>2</option>
                                        <option value="3" ' . selected( @$options[$section]['thumbnails']['elements-per-row'], '3', 0 ) .'>3</option>
                                        <option value="4" ' . selected( @$options[$section]['thumbnails']['elements-per-row'], '4', 0 ) .'>4</option>
                                        <option value="6" ' . selected( @$options[$section]['thumbnails']['elements-per-row'], '6', 0 ) .'>6</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="last-posts-big-post '.$show_big_post.'">
                        <table cellpadding="10">
                            <tr>
                                <td>
                                    <label for="'.$section.'-last-posts-big-post-title">'.__( 'Title', 'touchsize' ).':</label>
                                </td>
                                <td>
                                    <select name="videotouch_layout['.$section.'][big-post][display-title]" id="'.$section.'-last-posts-big-post-title">
                                        <option value="title-above-image" ' . selected( @$options[$section]['big-post']['display-title'], 'title-above-image', 0 ) .'>'.__( 'Above image', 'touchsize' ).'</option>
                                        <option value="title-above-excerpt" ' . selected( @$options[$section]['big-post']['display-title'], 'title-above-excerpt', 0 ) .'>'.__( 'Above excerpt', 'touchsize' ).'</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="'.$section.'-last-posts-big-post-show-meta">'.__( 'Show meta', 'touchsize' ).':</label>
                                </td>
                                <td>
                                    <input type="radio" name="videotouch_layout['.$section.'][big-post][show-meta]" id="'.$section.'-last-posts-big-post-show-meta-y"  value="y" '.checked( @$options[$section]['big-post']['show-meta'], 'y', 0 ).'   />
                                    <label for="'.$section.'-last-posts-big-post-show-meta-y">'.__( 'Yes', 'touchsize' ).'</label>

                                    <input type="radio" name="videotouch_layout['.$section.'][big-post][show-meta]" id="'.$section.'-last-posts-big-post-show-meta-n" value="n" '.checked( @$options[$section]['big-post']['show-meta'], 'n', 0 ).' />
                                    <label for="'.$section.'-last-posts-big-post-show-meta-n">'.__( 'No', 'touchsize' ).'</label>
                                </td>
                            </tr>
                            <tr>
                                <td>'.__( 'Content split', 'touchsize' ).'</td>
                                <td>
                                    <select name="videotouch_layout['.$section.'][big-post][image-split]" id="'.$section.'-last-posts-big-post-image-split">
                                        <option value="1-3" ' . selected( @$options[$section]['big-post']['image-split'], '1-3', 0 ) .'>1/3</option>
                                        <option value="1-2" ' . selected( @$options[$section]['big-post']['image-split'], '1-2', 0 ) .'>1/2</option>
                                        <option value="3-4" ' . selected( @$options[$section]['big-post']['image-split'], '3-4', 0 ) .'>3/4</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="last-posts-super-post '.$show_super_post.'">
                        <table cellpadding="10">
                            <tr>
                                <td>
                                    <label for="'.$section.'-last-posts-super-post-posts-per-row">'.__( 'Number of posts per row', 'touchsize' ).':</label>
                                </td>
                                <td>
                                    <select name="videotouch_layout['.$section.'][super-post][elements-per-row]" id="'.$section.'-last-posts-super-post-posts-per-row">
                                        <option value="1" ' . selected( @$options[$section]['super-post']['elements-per-row'], '1', 0 ) .'>1</option>
                                        <option value="2" ' . selected( @$options[$section]['super-post']['elements-per-row'], '2', 0 ) .'>2</option>
                                        <option value="3" ' . selected( @$options[$section]['super-post']['elements-per-row'], '3', 0 ) .'>3</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
        </div>';
}

/**
 * Iniaitalize the theme options page by registering the Fields, Sections, Settings
 */
function videotouch_initialize_styles_options()
{
	if( false === get_option( 'videotouch_styles' ) ) {
		$defaultStyles = array(
			'boxed_layout' => 'N',
			'image_hover_effect' => 'Y',
			'theme_custom_bg' => 'N',
			'theme_bg_pattern' => '',
			'theme_bg_color' => '#FFFFFF',
			'bg_image' => '',
			'overlay_effect_for_images' => 'N',
			'overlay_effect_type' => 'dots',
			'sharing_overlay' => 'Y',
			'logo_type' => 'image',
			'logo_url' => '',
			'logo_font_name' => '0',
			'logo_font_subsets' => 'latin',
			'logo_font_size' => '32',
			'logo_font_weight' => 'normal',
			'logo_font_style' => 'normal',
			'logo_text' => 'VideoTouch',
			'retina_logo' => 'Y',
			'retina_width' => '532',
			'retina_height' => '81',
			'facebook_image' => ''
		);

		if( !function_exists( 'has_site_icon' ) || !has_site_icon() ){
			$defaultStyles['favicon'] = '';
		}

		add_option( 'videotouch_styles', $defaultStyles);


	} // end if

	// Register styles section
	add_settings_section(
		'style_settings_section',
		__( 'Styles Options', 'touchsize' ),
		'videotouch_styles_callback',
		'videotouch_styles'
	);

	add_settings_field(
		'boxed_layout',
		__( 'Boxed Layout', 'touchsize' ),
		'toggle_boxed_layout_callback',
		'videotouch_styles',
		'style_settings_section',
		array(
			__( 'If enabled it will add white background to content and limit it to the site that is set in general settings.', 'touchsize' )
		)
	);

	add_settings_field(
		'theme_custom_bg',
		__( 'Theme background customization', 'touchsize' ),
		'toggle_theme_custom_bg_callback',
		'videotouch_styles',
		'style_settings_section',
		array(
			__( 'Background options for your website. You can set image background, background color or pattern.', 'touchsize' )
		)
	);

	if( !function_exists( 'has_site_icon' ) || !has_site_icon() ){
		add_settings_field(
			'favicon',
			__( 'Custom favicon', 'touchsize' ),
			'toggle_favicon_callback',
			'videotouch_styles',
			'style_settings_section',
			array(
				'Upload your own favicon for your website.'
			)
		);
	}

	add_settings_field(
		'facebook_image',
		__( 'Facebook image', 'touchsize' ),
		'toggle_facebook_image_callback',
		'videotouch_styles',
		'style_settings_section',
		array(
			'Upload your own facebook image for your website.'
		)
	);

	add_settings_field(
		'overlay_effect_for_images',
		__( 'Enable overlay stripes/dots effect for images', 'touchsize' ),
		'toggle_overlay_effect_for_images_callback',
		'videotouch_styles',
		'style_settings_section',
		array(
			__( 'If enabled, it will add subtle effect over images in archive pages and single featured images.', 'touchsize' )
		)
	);

	add_settings_field(
		'sharing_overlay',
		__( 'Enable sharing overlay buttons in views', 'touchsize' ),
		'toggle_sharing_overlay_callback',
		'videotouch_styles',
		'style_settings_section',
		array(
			__( 'If enabled, it will show sharing buttons on mouse over in post views.', 'touchsize' )
		)
	);

	add_settings_field(
		'logo_type',
		__( 'Logo type', 'touchsize' ),
		'toggle_logo_type_callback',
		'videotouch_styles',
		'style_settings_section',
		array(
			__( 'Choose which type of logo do you want to use. If text, select the font and the styles you need. If you want to use custom image logo use the uploader provided.', 'touchsize' )
		)
	);

	register_setting( 'videotouch_styles', 'videotouch_styles');

} // end videotouch_initialize_theme_options


add_action( 'admin_init', 'videotouch_initialize_styles_options' );

/**************************************************
 * Styles Section Callbacks
 *************************************************/

function videotouch_styles_callback()
{
	echo '<p>'.__( 'Settings for your website styling. Here you can change colors, effects, logo type, custom favicon, background.', 'touchsize' ).'</p>';
?>

<?php
} // end videotouch_styles_callback

function toggle_boxed_layout_callback($args)
{
	$options = get_option('videotouch_styles');

	$html = '<select name="videotouch_styles[boxed_layout]">
					<option value="Y" '. selected( @$options["boxed_layout"], 'Y', false ).'>'.__( 'Yes', 'touchsize' ).'</option>
					<option value="N" '. selected( @$options["boxed_layout"], 'N', false ). '>'.__( 'No', 'touchsize' ).'</option>
				</select>';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo $html;
}

function toggle_image_hover_effect_callback($args)
{
	$options = get_option('videotouch_styles');

	$html = '<select name="videotouch_styles[image_hover_effect]">
					<option value="Y" '. selected( @$options["image_hover_effect"], 'Y', false ).'>'.__( 'Yes', 'touchsize' ).'</option>
					<option value="N" '. selected( @$options["image_hover_effect"], 'N', false ). '>'.__( 'No', 'touchsize' ).'</option>
				</select>';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo $html;
}


function toggle_sharing_overlay_callback($args)
{
	$options = get_option('videotouch_styles');

	$html = '<select name="videotouch_styles[sharing_overlay]">
					<option value="Y" '. selected( @$options["sharing_overlay"], 'Y', false ).'>'.__( 'Yes', 'touchsize' ).'</option>
					<option value="N" '. selected( @$options["sharing_overlay"], 'N', false ). '>'.__( 'No', 'touchsize' ).'</option>
				</select>';

	$html .= '<p class="description">' .$args[0]. '</p>';
	echo $html;
}


function toggle_theme_custom_bg_callback($args)
{
	$options = get_option('videotouch_styles');

	$html = '<select name="videotouch_styles[theme_custom_bg]" id="custom-bg-options">
					<option value="pattern" '. selected( @$options["theme_custom_bg"], 'pattern', false ).'>'.__( 'Pattern', 'touchsize' ).'</option>
					<option value="color" '. selected( @$options["theme_custom_bg"], 'color', false ).'>'.__( 'Color', 'touchsize' ).'</option>
					<option value="image" '. selected( @$options["theme_custom_bg"], 'image', false ).'>'.__( 'Image', 'touchsize' ).'</option>
					<option value="N" '. selected( @$options["theme_custom_bg"], 'N', false ). '>'.__( 'No', 'touchsize' ).'</option>
				</select>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	if ( trim(@$options["theme_bg_pattern"]) !== "" ) {
		$bg_pattern_url = 'background: url(' . get_template_directory_uri(). '/images/patterns/' . $options["theme_bg_pattern"] . ')';
	} else {
		$bg_pattern_url = '';
	}

	$html .= '<div id="ts-patterns-option" class="ts-custom-bg">
				<p>'.__( 'Please select pattern by clicking on image', 'touchsize' ).'</p>

				<a class="thickbox" title="'.__( 'Click on pattern, then click OK button', 'touchsize' ).'" href="'.admin_url('admin.php?page=videotouch&tab=load_patterns&height=480&width=960') . '">
					<div style="width:100px; height:100px; ' . $bg_pattern_url . '" id="pattern-demo">&nbsp;</div>
				</a>

				<input type="hidden" name="videotouch_styles[theme_bg_pattern]" value="' . esc_attr(@$options["theme_bg_pattern"]).'" id="videotouch-bg-pattern"/>
	</div>';

	$html .= '<div id="ts-bg-color" class="ts-custom-bg"><p>Background color:</p>';

	$color = isset($options["theme_bg_color"]) ? $options["theme_bg_color"] : '#FFFFFF';

	$html .= '<input type="text" id="theme-bg-color" class="popup-colorpicker" name="videotouch_styles[theme_bg_color]" value="' . $color . '"/><div id="ts-theme-bg-picker"></div>
	</div>
	';

	$html .= '<div id="ts-bg-image" class="ts-custom-bg">';
	$html .= '<p>'.__( 'Upload background image:', 'touchsize' ).'</p>';
	$html .= '<input type="text" name="videotouch_styles[bg_image]" class="image_url" value="'.esc_url(@$options['bg_image']).'"/>
			<input type="hidden" value="" class="image_media_id"/>
			<input id="ts-upload-bg-image" type="button" name="ts-upload-fb-image" class="button-primary videotouch_select_image" value="'.__('Upload', 'touchsize').'">';
	$html .= '</div>';

	echo $html;
}

function toggle_favicon_callback($args)
{
	$options = get_option('videotouch_styles');

	$favicon = esc_url(@$options["favicon"]);

	$html = '<div>
					<input type="text" name="videotouch_styles[favicon]" class="image_url" value="'.$favicon.'">
					<input id="ts-upload-favicon" type="button" name="ts-upload-favicon" class="button-primary videotouch_select_image" value="'.__('Upload', 'touchsize').'">
					<input type="hidden" value="" class="image_media_id" />';
	$html.= '<p class="description">' .$args[0]. '</p>
				</div>';

	echo $html;
}

function toggle_facebook_image_callback($args)
{
	$options = get_option('videotouch_styles');

	$facebook_image = esc_url(@$options["facebook_image"]);

	$html = '<div>
					<input type="text" name="videotouch_styles[facebook_image]" class="image_url" value="'.$facebook_image.'">
					<input id="ts-upload-facebook-image" type="button" name="ts-upload-facebook_image" class="button-primary videotouch_select_image" value="'.__('Upload', 'touchsize').'">
					<input type="hidden" value="" class="image_media_id" />';
	$html.= '<p class="description">' .$args[0]. '</p>
				</div>';

	echo $html;
}

function toggle_overlay_effect_for_images_callback($args)
{
	$options = get_option('videotouch_styles');

	$html = '<select name="videotouch_styles[overlay_effect_for_images]" id="overlay-effect-for-images">
				<option value="Y" '. selected( @$options["overlay_effect_for_images"], 'Y', false ).'>'.__( 'Yes', 'touchsize' ).'</option>
				<option value="N" '. selected( @$options["overlay_effect_for_images"], 'N', false ). '>'.__( 'No', 'touchsize' ).'</option>
			</select>';

	$html .= '<p class="description">' .$args[0]. '</p>
				<div id="overlay-effects">
				<label for="stripes_effect">
				<input type="radio" id="stripes_effect" name="videotouch_styles[overlay_effect_type]" value="stripes"' . checked( @$options["overlay_effect_type"], 'stripes', false ) .'/>'.__( 'stripes', 'touchsize' ) . '</label>
				<label for="dotts_effect">
				<input type="radio" id="dotts_effect" name="videotouch_styles[overlay_effect_type]" value="dots" ' . checked( @$options["overlay_effect_type"], 'dots', false ) . ' />'.__( 'dots', 'touchsize' ) .'</label>
				</div>';

	echo $html;
}

function toggle_logo_type_callback($args)
{
	$options      = get_option('videotouch_styles');
	$font_size    = @$options['logo_font_size'];
	$font_size    = ($font_size <= 0 || $font_size > 72) ? 24 : $font_size;
	$font_url     = @$options['logo_url'];
	$font_name    = @$options['logo_font_name'];
	$font_subsets = @$options['logo_font_subsets'];

	$new_font_subsets = array();

	if (is_array($font_subsets) && ! empty($font_subsets)) {
		foreach ($font_subsets as $subset_index => $subset) {
			$new_font_subsets[] = "'". esc_attr($subset)."'";
		}
	}

	$font_subsets = implode(", ", $new_font_subsets);

	$font_weight  = @$options['logo_font_weight'];
	$font_style   = @$options['logo_font_style'];
	$logo_text    = @$options['logo_text'];

	$font_sizes = array();

	for ( $i=1; $i < 73; $i++ ) {
		if ((int)$font_size === $i) {
			$selected = 'selected="selected"';
		} else {
			$selected = '';
		}

		$font_sizes[] = '<option value="'.$i.'" ' . $selected . '>'.$i.'px</option>';
	}

	$html = '<select name="videotouch_styles[logo_type]" class="ts-logo-type">
				<option value="image" '. selected( @$options["logo_type"], 'image', false ).'>'.__( 'Image', 'touchsize' ).'</option>
				<option value="text" '. selected( @$options["logo_type"], 'text', false ). '>'.__( 'Text', 'touchsize' ).'</option>
			</select>';

	$html .= '<p class="description">' . @$args[0] . '</p>
			<div id="ts-logo-image">
				<p>' . __( 'Please select your logo ', 'touchsize' ) . '</p>
				<input type="text" name="videotouch_styles[logo_url]" id="logo-url" class="image_url" value="' . @ $options['logo_url'] . '"/>
				<input type="button" name="logo_url" id="upload-logo" class="button-primary videotouch_select_image" value="' . esc_attr(__( 'Upload', 'touchsize' )) . '" />
				<input type="hidden" value="" class="image_media_id" />
				<p>'.__('Enable retina logo','touchsize').'</p>
				<select name="videotouch_styles[retina_logo]">
					<option value="Y" '. selected( @$options["retina_logo"], 'Y', false ).'>'.__('Yes', 'touchsize').'</option>
					<option value="N" '. selected( @$options["retina_logo"], 'N', false ).'>'.__('No', 'touchsize').'</option>
				</select>
				<input type="hidden" id="videotouch_logo_retina_width" name="videotouch_styles[retina_width]" value="'.esc_attr(@$options['retina_width'] ).'"/>
				<input type="hidden" id="videotouch_logo_retina_height" name="videotouch_styles[retina_height]" value="'.esc_attr(@$options['retina_height'] ).'"/>';
?>
				<script>
					jQuery(document).ready(function($) {
						$("#logo-url").keyup(function() {
							var logo_url = $(this).val();
							var newImg = new Image();
							newImg.src = logo_url;

							$(newImg).load(function(){
								$('#videotouch_logo_retina_width').val(newImg.width);
								$('#videotouch_logo_retina_height').val(newImg.height);
							});
						});
					});
				</script>
	<?php
			$html .= '</div>';
	?>
		<script>
			jQuery(document).ready(function($) {
				ts_google_fonts(jQuery, {
					font_name: '<?php echo esc_attr($font_name)?>',
					selected_subsets: [<?php echo $font_subsets; ?>],
					allfonts: $("#fontchanger-logo"),
					prefix: 'logo',
					subsetsTypes: $('.logo-subset-types')
				});
			});
		</script>
	<?php

		$html .= '<div id="ts-logo-fonts">'.__( 'Select font', 'touchsize' ).'
					<select name="videotouch_styles[logo_font_name]" id="fontchanger-logo">
						<option value="0">'.__( 'No font selected', 'touchsize' ).'</option>
					</select>

					<p>' . __( 'Available subsets:', 'touchsize' ) . '</p>
					<div class="logo-subset-types"></div><br />

					<p>' . __('Font weight', 'touchsize') . ':</p>

					<select name="videotouch_styles[logo_font_weight]" id="logo-font-weight">
						<option value="normal" ' . selected( @$options["logo_font_weight"], 'normal', false ) . '>regular</option>
						<option value="700" '. selected( @$options["logo_font_weight"], '700', false ) . '>bold</option>
					</select><br/><br/>

					<p>' . __('Font-style', 'touchsize') . ':</p>
					<select name="videotouch_styles[logo_font_style]" id="logo-font-style">
						<option value="normal" '. selected( @$options["logo_font_style"], 'normal', false ) .'>regular</option>
						<option value="italic" '. selected( @$options["logo_font_style"], 'italic', false ) .'>italic</option>
					</select><br/><br/>

					<p>' . __( 'Font-size', 'touchsize' ). '</p>
					<select name="videotouch_styles[logo_font_size]" id="logo-font-size">'.implode("\n", $font_sizes).'</select><br/><br/>
					<p class="logo-text-preview">' . __( 'Logo text', 'touchsize' ) . '</p>
					<textarea type="text" name="videotouch_styles[logo_text]" id="logo-demo">' . esc_attr($logo_text) . '</textarea><br>
					<input type="button" name="ts-logo-preview" id="logo-preview" class="button-primary" value="Preview"/><br /><br />
					<div class="logo-output"></div>
			</div>';

	echo $html;
}

/**
 * Iniaitalize the theme options colors section by registering the Fields, Sections, Settings
 */
function videotouch_initialize_colors_options()
{

	if( false === get_option( 'videotouch_colors' ) ) {

		add_option( 'videotouch_colors', array(
			'general_text_color' => '#3f4549',
			'link_color' => '#e10d0d',
			'link_color_hover' => '#BA2121',
			'view_link_color' => '#434A54',
			'view_link_color_hover' => '#656D78',
			'meta_color' => '#d6cbcb',
			'primary_color' => '#e10d0d',
			'primary_color_hover' => '#BA2121',
			'secondary_color' => '#ECF0F1',
			'secondary_color_hover' => '#eff2f4',
			'primary_text_color' => '#FFFFFF',
			'primary_text_color_hover' => '#f5f6f7',
			'secondary_text_color' => '#a0a8ab',
			'secondary_text_color_hover' => '#b5c0c4',
			'submenu_bg_color' => '#FDFDFD',
			'submenu_text_color' => '#616669',
			'submenu_bg_color_hover' => '#FFFFFF',
			'submenu_text_color_hover' => '#43484a',
		));

	} // end if

	// Register styles section
	add_settings_section(
		'color_settings_section',
		__( 'Theme color options', 'touchsize' ),
		'videotouch_colors_callback',
		'videotouch_colors'
	);



	add_settings_field(
		'general_text_color',
		__( 'General color for the text on the website', 'touchsize' ),
		'toggle_videotouch_general_text_color_callback',
		'videotouch_colors',
		'color_settings_section',
		array(
			__( 'Change this to any color you want and that fits the background of the website.', 'touchsize' )
		)
	);

	add_settings_field(
		'general_text_color',
		__( 'General color for the text on the website', 'touchsize' ),
		'toggle_videotouch_general_text_color_callback',
		'videotouch_colors',
		'color_settings_section',
		array(
			__( 'Change this to any color you want and that fits the background of the website.', 'touchsize' )
		)
	);

	add_settings_field(
		'link_color',
		__( 'Link color', 'touchsize' ),
		'toggle_videotouch_link_color_callback',
		'videotouch_colors',
		'color_settings_section',
		array(
			__( 'Change this color if you want the links on your website to have a different color.', 'touchsize' )
		)
	);
	add_settings_field(
		'link_color_hover',
		__( 'Link color on hover', 'touchsize' ),
		'toggle_videotouch_link_color_hover_callback',
		'videotouch_colors',
		'color_settings_section',
		array(
			__( 'Change this color if you want the links on hover to have a different color.', 'touchsize' )
		)
	);
	add_settings_field(
		'views_link_color',
		__( 'Link colors in views (grid/list/bigpost)', 'touchsize' ),
		'toggle_videotouch_view_link_color_callback',
		'videotouch_colors',
		'color_settings_section',
		array(
			__( 'You have different types to showcase your articles. This option will change the color of the links of the articles.', 'touchsize' )
		)
	);
	add_settings_field(
		'views_link_color_hover',
		__( 'Title colors on hover in view', 'touchsize' ),
		'toggle_videotouch_view_link_color_hover_callback',
		'videotouch_colors',
		'color_settings_section',
		array(
			__( 'You have different types to showcase your articles. This option will change the color on hover of the titles of the articles.', 'touchsize' )
		)
	);
	add_settings_field(
		'meta_color',
		__( 'Meta text color', 'touchsize' ),
		'toggle_videotouch_meta_color_callback',
		'videotouch_colors',
		'color_settings_section',
		array(
			__( 'Change the color of the text for your meta.', 'touchsize' )
		)
	);
	add_settings_field(
		'primary_color',
		__( 'Primary color', 'touchsize' ),
		'toggle_videotouch_primary_color_callback',
		'videotouch_colors',
		'color_settings_section',
		array(
			__( 'Main color of the website. It is used for backgrounds, borders of elements, etc. This defines your main brand/website color.', 'touchsize' )
		)
	);
	add_settings_field(
		'primary_color_hover',
		__( 'Primary color on hover', 'touchsize' ),
		'toggle_videotouch_primary_color_hover_callback',
		'videotouch_colors',
		'color_settings_section',
		array(
			__( 'Main color of the website. It is used for backgrounds, borders of elements, etc. This defines your main brand/website color on hover.', 'touchsize' )
		)
	);
	add_settings_field(
		'secondary_color',
		__( 'Secondary color', 'touchsize' ),
		'toggle_videotouch_secondary_color_callback',
		'videotouch_colors',
		'color_settings_section',
		array(
			__( 'Secondary color of the website. It is used for backgrounds, borders of elements, etc. This defines your secondary or contrast brand/website color.', 'touchsize' )
		)
	);
	add_settings_field(
		'secondary_color_hover',
		__( 'Secondary color on hover', 'touchsize' ),
		'toggle_videotouch_secondary_color_hover_callback',
		'videotouch_colors',
		'color_settings_section',
		array(
			__( 'Secondary color of the website. It is used for backgrounds, borders of elements, etc. This defines your secondary or contrast brand/website color on hover.', 'touchsize' )
		)
	);
	add_settings_field(
		'primary_text_color',
		__( 'Primary text color', 'touchsize' ),
		'toggle_videotouch_primary_text_color_callback',
		'videotouch_colors',
		'color_settings_section',
		array(
			__( 'The color of the text that has a primary color background. Primary color reffers to the color setting above.', 'touchsize' )
		)
	);
	add_settings_field(
		'primary_text_color_hover',
		__( 'Primary text color on hover', 'touchsize' ),
		'toggle_videotouch_primary_text_color_hover_callback',
		'videotouch_colors',
		'color_settings_section',
		array(
			__( 'The color of the text that has a primary color background on hover. Primary color reffers to the color setting above.', 'touchsize' )
		)
	);
	add_settings_field(
		'secondary_text_color',
		__( 'Secondary text color', 'touchsize' ),
		'toggle_videotouch_secondary_text_color_callback',
		'videotouch_colors',
		'color_settings_section',
		array(
			__( 'The color of the text that has a secondary color background.', 'touchsize' )
		)
	);
	add_settings_field(
		'secondary_text_color_hover',
		__( 'Secondary text color on hover', 'touchsize' ),
		'toggle_videotouch_secondary_text_color_hover_callback',
		'videotouch_colors',
		'color_settings_section',
		array(
			__( 'The color of the text that has a secondary color background on hover. Primary color reffers to the color setting above.', 'touchsize' )
		)
	);
	add_settings_field(
		'menu_bg_color',
		__( 'Submenu background color', 'touchsize' ),
		'toggle_videotouch_submenu_bg_color_callback',
		'videotouch_colors',
		'color_settings_section',
		array(
			__( 'This is used for menus that have background colors. Not all menu styles can have backgrounds. Even so, this option will apply for submenu backgrounds as well, even for those that do not have a background by default', 'touchsize' )
		)
	);
	add_settings_field(
		'menu_bg_color_hover',
		__( 'Submenu background color on hover', 'touchsize' ),
		'toggle_videotouch_submenu_bg_color_hover_callback',
		'videotouch_colors',
		'color_settings_section',
		array(
			__( 'Same thing as the option above, only for the hover state.', 'touchsize' )
		)
	);
	add_settings_field(
		'menu_text_color',
		__( 'Menu text color', 'touchsize' ),
		'toggle_videotouch_submenu_text_color_callback',
		'videotouch_colors',
		'color_settings_section',
		array(
			__( 'The colors of the text in the menus and submenus.', 'touchsize' )
		)
	);
	add_settings_field(
		'menu_text_color_hover',
		__( 'Menu text color on hover', 'touchsize' ),
		'toggle_videotouch_submenu_text_color_hover_callback',
		'videotouch_colors',
		'color_settings_section',
		array(
			__( 'The colors of the text in the menus and submenus on hover.', 'touchsize' )
		)
	);

	register_setting( 'videotouch_colors', 'videotouch_colors');

} // end videotouch_initialize_theme_options


add_action( 'admin_init', 'videotouch_initialize_colors_options' );

/**************************************************
 * Colors Section Callbacks
 *************************************************/

function videotouch_colors_callback()
{
	echo '<p>'.__( 'Settings for your website color settings. Here you can change colors that are shown on your website.', 'touchsize' ).'</p>';
?>

<?php
} // end videotouch_styles_callback

function toggle_videotouch_general_text_color_callback($args)
{
	$options = get_option('videotouch_colors');

	$html = '<input type="text" id="ts-general-text-color" class="colors-section-picker" name="videotouch_colors[general_text_color]" value="'.esc_attr(@$options["general_text_color"]).'" /><div class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo $html;
}


function toggle_videotouch_link_color_callback($args)
{
	$options = get_option('videotouch_colors');

	$html = '<input type="text" id="ts-link-color" class="colors-section-picker" name="videotouch_colors[link_color]" value="'.esc_attr(@$options["link_color"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo $html;
}
function toggle_videotouch_link_color_hover_callback($args)
{
	$options = get_option('videotouch_colors');

	$html = '<input type="text" id="ts-link-hover-color" class="colors-section-picker" name="videotouch_colors[link_color_hover]" value="'.esc_attr(@$options["link_color_hover"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo $html;
}

function toggle_videotouch_view_link_color_callback($args)
{
	$options = get_option('videotouch_colors');

	$html = '<input type="text" id="ts-view-link-color" class="colors-section-picker" name="videotouch_colors[view_link_color]" value="'.esc_attr(@$options["view_link_color"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo $html;
}
function toggle_videotouch_view_link_color_hover_callback($args)
{
	$options = get_option('videotouch_colors');

	$html = '<input type="text" id="ts-view-link-hover-color" class="colors-section-picker" name="videotouch_colors[view_link_color_hover]" value="'.esc_attr(@$options["view_link_color_hover"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo $html;
}
function toggle_videotouch_meta_color_callback($args)
{
	$options = get_option('videotouch_colors');

	$html = '<input type="text" id="ts-meta-text-color" class="colors-section-picker" name="videotouch_colors[meta_color]" value="'.esc_attr(@$options["meta_color"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo $html;
}
function toggle_videotouch_primary_color_callback($args)
{
	$options = get_option('videotouch_colors');

	$html = '<input type="text" id="ts-primary-color" class="colors-section-picker" name="videotouch_colors[primary_color]" value="'.esc_attr(@$options["primary_color"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo $html;
}
function toggle_videotouch_primary_color_hover_callback($args)
{
	$options = get_option('videotouch_colors');

	$html = '<input type="text" id="ts-primary-color-hover" class="colors-section-picker" name="videotouch_colors[primary_color_hover]" value="'.esc_attr(@$options["primary_color_hover"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo $html;
}
function toggle_videotouch_secondary_color_callback($args)
{
	$options = get_option('videotouch_colors');

	$html = '<input type="text" id="ts-secondary-color" class="colors-section-picker" name="videotouch_colors[secondary_color]" value="'.esc_attr(@$options["secondary_color"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo $html;
}
function toggle_videotouch_secondary_color_hover_callback($args)
{
	$options = get_option('videotouch_colors');

	$html = '<input type="text" id="ts-secondary-color-hover" class="colors-section-picker" name="videotouch_colors[secondary_color_hover]" value="'.esc_attr(@$options["secondary_color_hover"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo $html;
}

function toggle_videotouch_primary_text_color_callback($args)
{
	$options = get_option('videotouch_colors');

	$html = '<input type="text" id="ts-primary-text-color" class="colors-section-picker" name="videotouch_colors[primary_text_color]" value="'.esc_attr(@$options["primary_text_color"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo $html;
}
function toggle_videotouch_primary_text_color_hover_callback($args)
{
	$options = get_option('videotouch_colors');

	$html = '<input type="text" id="ts-primary-text-color-hover" class="colors-section-picker" name="videotouch_colors[primary_text_color_hover]" value="'.esc_attr(@$options["primary_text_color_hover"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo $html;
}
function toggle_videotouch_secondary_text_color_hover_callback($args)
{
	$options = get_option('videotouch_colors');

	$html = '<input type="text" id="ts-secondary-text-color-hover" class="colors-section-picker" name="videotouch_colors[secondary_text_color_hover]" value="'.esc_attr(@$options["secondary_text_color_hover"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo $html;
}
function toggle_videotouch_secondary_text_color_callback($args)
{
	$options = get_option('videotouch_colors');

	$html = '<input type="text" id="ts-secondary-text-color" class="colors-section-picker" name="videotouch_colors[secondary_text_color]" value="'.esc_attr(@$options["secondary_text_color"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo $html;
}
function toggle_videotouch_submenu_bg_color_callback($args)
{
	$options = get_option('videotouch_colors');

	$html = '<input type="text" id="ts-menu-bg-color" class="colors-section-picker" name="videotouch_colors[submenu_bg_color]" value="'.esc_attr(@$options["submenu_bg_color"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo $html;
}
function toggle_videotouch_submenu_bg_color_hover_callback($args)
{
	$options = get_option('videotouch_colors');

	$html = '<input type="text" id="ts-menu-bg-hover-color" class="colors-section-picker" name="videotouch_colors[submenu_bg_color_hover]" value="'.esc_attr(@$options["submenu_bg_color_hover"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo $html;
}
function toggle_videotouch_submenu_text_color_callback($args)
{
	$options = get_option('videotouch_colors');

	$html = '<input type="text" id="ts-menu-text-color" class="colors-section-picker" name="videotouch_colors[submenu_text_color]" value="'.esc_attr(@$options["submenu_text_color"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo $html;
}
function toggle_videotouch_submenu_text_color_hover_callback($args)
{
	$options = get_option('videotouch_colors');

	$html = '<input type="text" id="ts-menu-text-hover-color" class="colors-section-picker" name="videotouch_colors[submenu_text_color_hover]" value="'.esc_attr(@$options["submenu_text_color_hover"]).'" /><div  class="colors-section-picker-div"></div>';

	$html .= '<p class="description">' .$args[0]. '</p>';

	echo $html;
}

// Typography tab
function videotouch_initialize_typography_options()
{

	if( false === get_option( 'videotouch_typography' ) ) {
		add_option( 'videotouch_typography', array(
			'google_fonts_key' => 'AIzaSyBHh7VPOKMPw1oy6wsEs8FNtR5E8zjb-7A',
			'headings' => array(
				'type' => 'std',
				'font_name' => '0',
				'font_subsets' => array('latin'),
				'font_weight' => 'regular',
				'font_style' => 'regular',
				'h1_size' => '52',
				'h2_size' => '44',
				'h3_size' => '38',
				'h4_size' => '32',
				'h5_size' => '26',
				'text' => 'VideoTouch',
				'font_eot' => '',
				'font_svg' => '',
				'font_ttf' => '',
				'font_woff' => '',
				'font_family' => ''
			),
			'primary_text' => array(
				'type' => 'std',
				'font_name' => '0',
				'font_subsets' => array('latin'),
				'font_weight' => 'regular',
				'font_style' => 'regular',
				'text' => 'VideoTouch is a really nice WordPress Theme by TouchSize.',
				'font_eot' => '',
				'font_svg' => '',
				'font_ttf' => '',
				'font_woff' => '',
				'font_family' => ''
			),
			'secondary_text' => array(
				'type' => 'std',
				'font_name' => '0',
				'font_subsets' => array('latin'),
				'font_weight' => 'regular',
				'font_style' => 'regular',
				'text' => 'VideoTouch',
				'font_eot' => '',
				'font_svg' => '',
				'font_ttf' => '',
				'font_woff' => '',
				'font_family' => ''
			),
			'icons' => 'icon-noicon,icon-image,icon-comments,icon-delete,icon-rss,icon-drag,icon-down,icon-up,icon-layout,icon-import,icon-play,icon-desktop,icon-social,icon-empty,icon-filter,icon-money,icon-flickr,icon-pinterest,icon-user,icon-video,icon-close,icon-link,icon-views,icon-quote,icon-pencil,icon-page,icon-post,icon-category,icon-time,icon-left,icon-right,icon-palette,icon-code,icon-sidebar,icon-vimeo,icon-lastfm,icon-logo,icon-heart,icon-list,icon-attention,icon-menu,icon-delimiter,icon-image-size,icon-settings,icon-share,icon-resize-vertical,icon-text,icon-movie,icon-dribbble,icon-yahoo,icon-facebook,icon-twitter,icon-tumblr,icon-gplus,icon-skype,icon-linkedin,icon-tick,icon-edit,icon-font,icon-home,icon-button,icon-wordpress,icon-music,icon-mail,icon-lock,icon-search,icon-github,icon-basket,icon-star,icon-link-ext,icon-award,icon-signal,icon-target,icon-attach,icon-download,icon-upload,icon-mic,icon-calendar,icon-phone,icon-headphones,icon-flag,icon-credit-card,icon-save,icon-megaphone,icon-key,icon-euro,icon-pound,icon-dollar,icon-rupee,icon-yen,icon-rouble,icon-try,icon-won,icon-bitcoin,icon-anchor,icon-support,icon-blocks,icon-block,icon-graduate,icon-shield,icon-window,icon-coverflow,icon-flight,icon-brush,icon-resize-full,icon-news,icon-pin,icon-params,icon-beaker,icon-delivery,icon-bell,icon-help,icon-laptop,icon-tablet,icon-mobile,icon-thumb,icon-briefcase,icon-direction,icon-ticket,icon-chart,icon-book,icon-print,icon-on,icon-off,icon-featured-area, icon-team, icon-login, icon-clients, icon-tabs, icon-tags, icon-gauge, icon-bag, icon-key, icon-glasses, icon-ok-full, icon-restart, icon-recursive, icon-shuffle, icon-ribbon, icon-lamp, icon-flash, icon-leaf, icon-chart-pie-outline, icon-puzzle, icon-fullscreen, icon-downscreen, icon-zoom-in, icon-zoom-out, icon-pencil-alt, icon-down-dir, icon-left-dir, icon-right-dir, icon-up-dir',

		));
	} // end if

	// Register a section
	add_settings_section(
		'typography_settings_section',
		__( 'Typography Options', 'touchsize' ),
		'videotouch_typography_callback',
		'videotouch_typography'
	);

	add_settings_field(
		'google_fonts_key',
		__( 'Google fonts API key', 'touchsize' ),
		'toggle_google_api_key_callback',
		'videotouch_typography',
		'typography_settings_section',
		array(
			__( sprintf('Get your key <a href="%s" target="_blank">%s</a>', 'https://developers.google.com/fonts/docs/developer_api', __('here', 'touchsize') ), 'touchsize' )
		)
	);

	add_settings_field(
		'headings',
		__( 'Headings styles', 'touchsize' ),
		'toggle_headings_typography_callback',
		'videotouch_typography',
		'typography_settings_section',
		array(
			__( 'Choose the font you want to use for headings on your website. If standard is selected it will use the default ones set by the developers.', 'touchsize' )
		)
	);

	add_settings_field(
		'primary_text',
		__( 'General body text styles', 'touchsize' ),
		'toggle_primary_text_callback',
		'videotouch_typography',
		'typography_settings_section',
		array(
			__( 'This is general body settings. This will change the font for the entire website.', 'touchsize' )
		)
	);

	add_settings_field(
		'secondary_text',
		__( 'Menu text styles', 'touchsize' ),
		'toggle_secondary_text_callback',
		'videotouch_typography',
		'typography_settings_section',
		array(
			__( 'This is used for styling the menu element.', 'touchsize' )
		)
	);


	register_setting( 'videotouch_typography', 'videotouch_typography');

} // END videotouch_initialize_typography_options

add_action( 'admin_init', 'videotouch_initialize_typography_options' );

/**************************************************
 * Typography Section Callbacks
 *************************************************/

function videotouch_typography_callback()
{
	echo '<p>'.__( 'Use settings below to change typography for your website.', 'touchsize' ).'</p>';
} // END videotouch_typography_callback()

function toggle_google_api_key_callback($args)
{
	$options = get_option('videotouch_typography');

	$key = @$options['google_fonts_key'];

	echo '<input type="text" name="videotouch_typography[google_fonts_key]" id="videotouch_google_fonts_key" value="'.esc_attr($key).'"/><p class="description">' .@$args[0]. '</p>';
}

function toggle_headings_typography_callback($args)
{
	$options = get_option('videotouch_typography');

	$font_name    = @$options['headings']['font_name'];
	$font_subsets = @$options['headings']['font_subsets'];

	$new_font_subsets = array();

	if (is_array($font_subsets) && ! empty($font_subsets)) {
		foreach ($font_subsets as $subset_index => $subset) {
			$new_font_subsets[] = "'". esc_attr($subset)."'";
		}
	}

	$font_subsets = implode(", ", $new_font_subsets);

	$font_weight  = @$options['headings']['font_weight'];
	$font_style   = @$options['headings']['font_style'];
	$headings_text= @$options['headings']['text'];

	$html = '<select name="videotouch_typography[headings][type]" class="ts-typo-headings">
					<option value="std" '. selected( @$options["headings"]["type"], 'std', false ).'>'.__( 'Standart fonts', 'touchsize' ).'</option>
					<option value="google" '. selected( @$options["headings"]["type"], 'google', false ). '>'.__( 'Google fonts', 'touchsize' ).'</option>
					<option value="custom_font" '. selected( @$options["headings"]["type"], 'custom_font', false ). '>'.__( 'Custom font', 'touchsize' ).'</option>
			</select>';

	$html .= '<p class="description">' .@$args[0]. '</p>';
	$html .= '<div id="ts-typo-headings-gfonts">';
		_e( 'Select font', 'touchsize' );
	?>
<script>
jQuery(document).ready(function($) {
	ts_google_fonts(jQuery, {
		font_name: '<?php echo esc_attr($font_name)?>',
		selected_subsets: [<?php echo $font_subsets; ?>],
		allfonts: $("#fontchanger-headings"),
		prefix: 'headings',
		subsetsTypes: $('.headings-subset-types'),
		section: 'videotouch_typography'
	});
});
</script>
	<?php
		$html .= '
				<select name="videotouch_typography[headings][font_name]" id="fontchanger-headings">
					<option value="0">'.__( 'No font selected', 'touchsize' ).'</option>
				</select>
			<br>
			<div>' . __( 'Available subsets:', 'touchsize' ) . '
				<div class="headings-subset-types">

				</div><br />
				<p>' . __('Font weight', 'touchsize') . ':</p>

				<select name="videotouch_typography[headings][font_weight]" id="headings-font-weight">
					<option value="400" ' . selected( @$options["headings"]["font_weight"], '400', false ) . '>regular</option>
					<option value="700" '. selected( @$options["headings"]["font_weight"], '700', false ) . '>bold</option>
				</select>

				<p>' . __('Font-style', 'touchsize') . ':</p>
				<select name="videotouch_typography[headings][font_style]" id="headings-font-style">
					<option value="400" '. selected( @$options["headings"]["font_style"], '400', false ) .'>regular</option>
					<option value="italic" '. selected( @$options["headings"]["font_style"], 'italic', false ) .'>italic</option>
				</select>

				<p>' . __('H1 font size', 'touchsize') . ':</p>
				<input type="text" name="videotouch_typography[headings][h1_size]" value="' . @$options["headings"]["h1_size"] . '" />
				<div class="ts-option-description">This will affect the H1 tag. Write your number (!integer) in PIXELS.</div>
				<p>' . __('H2 font size', 'touchsize') . ':</p>
				<input type="text" name="videotouch_typography[headings][h2_size]" value="' . @$options["headings"]["h2_size"] . '" />
				<div class="ts-option-description">This will affect the H2 tag. Write your number (!integer) in PIXELS.</div>
				<p>' . __('H3 font size', 'touchsize') . ':</p>
				<input type="text" name="videotouch_typography[headings][h3_size]" value="' . @$options["headings"]["h3_size"] . '" />
				<div class="ts-option-description">This will affect the H3 tag. Write your number (!integer) in PIXELS.</div>
				<p>' . __('H4 font size', 'touchsize') . ':</p>
				<input type="text" name="videotouch_typography[headings][h4_size]" value="' . @$options["headings"]["h4_size"] . '" />
				<div class="ts-option-description">This will affect the H4 tag. Write your number (!integer) in PIXELS.</div>
				<p>' . __('H5 font size', 'touchsize') . ':</p>
				<input type="text" name="videotouch_typography[headings][h5_size]" value="' . @$options["headings"]["h5_size"] . '" />
				<div class="ts-option-description">This will affect the H5 tag. Write your number (!integer) in PIXELS.</div>

				<p class="logo-text-preview">' . __( 'Logo text', 'touchsize' ) . '</p>
				<textarea type="text" name="videotouch_typography[headings][text]" id="headings-demo">' . esc_attr($headings_text) . '</textarea>
				<input type="button" name="ts-headings-preview" id="headings-preview" class="button-primary" value="Preview"/>
				<div class="headings-output"></div>';
	$html .= '</div></div>';

	$html .= '<div id="custom-font">';

	$html .= '<p>'.__( 'Upload file "eot":', 'touchsize' ).'</p>';
	$html .= '<input type="text" name="videotouch_typography[headings][font_eot]" value="'. esc_attr($options['headings']['font_eot']) .'" id="atachment-eot"/>
			  <input type="hidden" value="" id="file_eot"/>
			  <input class="button-primary" id="upload_eot" type="button" value="'.__('Upload', 'touchsize').'">';

	$html .= '<p>'.__( 'Upload file "svg":', 'touchsize' ).'</p>';
	$html .= '<input type="text" name="videotouch_typography[headings][font_svg]" value="'. esc_attr($options['headings']['font_svg']) .'" id="atachment-svg"/>
			  <input type="hidden" value="" id="file_svg"/>
			  <input type="button" class="button-primary" id="upload_svg" value="'.__('Upload', 'touchsize').'">';

	$html .= '<p>'.__( 'Upload file "ttf":', 'touchsize' ).'</p>';
	$html .= '<input type="text" name="videotouch_typography[headings][font_ttf]" value="'. esc_attr($options['headings']['font_ttf']) .'" id="atachment-ttf"/>
			  <input type="hidden" value="" id="file_ttf"/>
			  <input type="button" class="button-primary" id="upload_ttf" value="'.__('Upload', 'touchsize').'">';

	$html .= '<p>'.__( 'Upload file "woff":', 'touchsize' ).'</p>';
	$html .= '<input type="text" name="videotouch_typography[headings][font_woff]" value="'. esc_attr($options['headings']['font_woff']) .'" id="atachment-woff"/>
			  <input type="hidden" value="" id="file_woff"/>
			  <input type="button" class="button-primary" id="upload_woff" value="'.__('Upload', 'touchsize').'">';

	$html .= '<p>'.__( 'Enter font-family(stylesheet.css):', 'touchsize' ).'</p>';
	$html .= '<input type="text" name="videotouch_typography[headings][font_family]" value="'. @$options['headings']['font_family'] .'" />';

	$html .= '</div>';

	echo $html;
} // END toggle_headings_typography_callback()

function toggle_primary_text_callback($args)
{
	$options = get_option('videotouch_typography');

	$font_name    = @$options['primary_text']['font_name'];
	$font_subsets = @$options['primary_text']['font_subsets'];

	$new_font_subsets = array();

	if (is_array($font_subsets) && ! empty($font_subsets)) {
		foreach ($font_subsets as $subset_index => $subset) {
			$new_font_subsets[] = "'". esc_attr($subset)."'";
		}
	}

	$font_subsets = implode(", ", $new_font_subsets);

	$font_weight  = @$options['primary_text']['font_weight'];
	$font_style   = @$options['primary_text']['font_style'];
	$headings_text= @$options['primary_text']['text'];

	$html = '<select name="videotouch_typography[primary_text][type]" class="ts-typo-primary_text">
					<option value="std" '. selected( @$options["primary_text"]["type"], 'std', false ).'>'.__( 'Standart fonts', 'touchsize' ).'</option>
					<option value="google" '. selected( @$options["primary_text"]["type"], 'google', false ). '>'.__( 'Google fonts', 'touchsize' ).'</option>
					<option value="custom_font" '. selected( @$options["primary_text"]["type"], 'custom_font', false ). '>'.__( 'Custom font', 'touchsize' ).'</option>
			</select>';

	$html .= '<p class="description">' .@$args[0]. '</p>';
	$html .= '<div id="ts-typo-primary_text-gfonts">';
		_e( 'Select font', 'touchsize' );
	?>
		<script>
			jQuery(document).ready(function($) {
				ts_google_fonts(jQuery, {
					font_name: '<?php echo esc_attr($font_name)?>',
					selected_subsets: [<?php echo $font_subsets; ?>],
					allfonts: $("#fontchanger-primary_text"),
					prefix: 'primary_text',
					subsetsTypes: $('.primary_text-subset-types'),
					section: 'videotouch_typography'
				});
			});
		</script>
	<?php
		$html .= '
				<select name="videotouch_typography[primary_text][font_name]" id="fontchanger-primary_text">
					<option value="0">'.__( 'No font selected', 'touchsize' ).'</option>
				</select>
			<div>' . __( 'Available subsets:', 'touchsize' ) . '
				<div class="primary_text-subset-types">

				</div>
				<p>' . __('Font weight', 'touchsize') . ':</p>

				<select name="videotouch_typography[primary_text][font_weight]" id="primary_text-font-weight">
					<option value="400" ' . selected( @$options["primary_text"]["font_weight"], '400', false ) . '>regular</option>
					<option value="700" '. selected( @$options["primary_text"]["font_weight"], '700', false ) . '>bold</option>
				</select>

				<p>' . __('Font-style', 'touchsize') . ':</p>
				<select name="videotouch_typography[primary_text][font_style]" id="primary_text-font-style">
					<option value="400" '. selected( @$options["primary_text"]["font_style"], '400', false ) .'>regular</option>
					<option value="italic" '. selected( @$options["primary_text"]["font_style"], 'italic', false ) .'>italic</option>
				</select>
				<p>' . __('Primary font size', 'touchsize') . ':</p>
				<input type="text" name="videotouch_typography[primary_text][font_size]" value="' . @$options["primary_text"]["font_size"] . '" />
				<div class="ts-option-description">This will affect the most of the website. Write your number (!integer) in PIXELS.</div>

				<p class="primary-preview">' . __( 'Logo text', 'touchsize' ) . '</p>
				<textarea type="text" name="videotouch_typography[primary_text][text]" id="primary_text-demo">' . esc_attr($headings_text) . '</textarea>
				<input type="button" name="ts-primary_text-preview" id="primary_text-preview" class="button-primary" value="Preview"/><br />
				<div class="primary_text-output"></div>';
	$html .= '</div></div>';

	$html .= '<div id="custom-primary-font">';

	$html .= '<p>'.__( 'Upload file "eot":', 'touchsize' ).'</p>';
	$html .= '<input type="text" name="videotouch_typography[primary_text][font_eot]" value="'. esc_attr($options['primary_text']['font_eot']) .'" id="atachment-primary-eot"/>
			  <input type="hidden" value="" id="file_primary_eot"/>
			  <input class="button-primary" id="upload_primary_eot" type="button" value="'.__('Upload', 'touchsize').'">';

	$html .= '<p>'.__( 'Upload file "svg":', 'touchsize' ).'</p>';
	$html .= '<input type="text" name="videotouch_typography[primary_text][font_svg]" value="'. esc_attr($options['primary_text']['font_svg']) .'" id="atachment-primary-svg"/>
			  <input type="hidden" value="" id="file_primary_svg"/>
			  <input type="button" class="button-primary" id="upload_primary_svg" value="'.__('Upload', 'touchsize').'">';

	$html .= '<p>'.__( 'Upload file "ttf":', 'touchsize' ).'</p>';
	$html .= '<input type="text" name="videotouch_typography[primary_text][font_ttf]" value="'. esc_attr($options['primary_text']['font_ttf']) .'" id="atachment-primary-ttf"/>
			  <input type="hidden" value="" id="file_primary_ttf"/>
			  <input type="button" class="button-primary" id="upload_primary_ttf" value="'.__('Upload', 'touchsize').'">';

	$html .= '<p>'.__( 'Upload file "woff":', 'touchsize' ).'</p>';
	$html .= '<input type="text" name="videotouch_typography[primary_text][font_woff]" value="'. esc_attr($options['primary_text']['font_woff']) .'" id="atachment-primary-woff"/>
			  <input type="hidden" value="" id="file_primary_woff"/>
			  <input type="button" class="button-primary" id="upload_primary_woff" value="'.__('Upload', 'touchsize').'">';

	$html .= '<p>'.__( 'Enter font-family(stylesheet.css):', 'touchsize' ).'</p>';
	$html .= '<input type="text" name="videotouch_typography[primary_text][font_family]" value="'. @$options['primary_text']['font_family'] .'" />';

	$html .= '</div>';

	echo $html;
} // END toggle_primary_text_callback()

function toggle_secondary_text_callback($args)
{
	$options = get_option('videotouch_typography');

	$font_name    = @$options['secondary_text']['font_name'];
	$font_subsets = @$options['secondary_text']['font_subsets'];

	$new_font_subsets = array();

	if (is_array($font_subsets) && ! empty($font_subsets)) {
		foreach ($font_subsets as $subset_index => $subset) {
			$new_font_subsets[] = "'". esc_attr($subset)."'";
		}
	}

	$ts_icons = 'icon-noicon,icon-image,icon-comments,icon-delete,icon-rss,icon-drag,icon-down,icon-up,icon-layout,icon-import,icon-play,icon-desktop,icon-social,icon-empty,icon-filter,icon-money,icon-flickr,icon-pinterest,icon-user,icon-video,icon-close,icon-link,icon-views,icon-quote,icon-pencil,icon-page,icon-post,icon-category,icon-time,icon-left,icon-right,icon-palette,icon-code,icon-sidebar,icon-vimeo,icon-lastfm,icon-logo,icon-heart,icon-list,icon-attention,icon-menu,icon-delimiter,icon-image-size,icon-settings,icon-share,icon-resize-vertical,icon-text,icon-movie,icon-dribbble,icon-yahoo,icon-facebook,icon-twitter,icon-tumblr,icon-gplus,icon-skype,icon-linkedin,icon-tick,icon-edit,icon-font,icon-home,icon-button,icon-wordpress,icon-music,icon-mail,icon-lock,icon-search,icon-github,icon-basket,icon-star,icon-link-ext,icon-award,icon-signal,icon-target,icon-attach,icon-download,icon-upload,icon-mic,icon-calendar,icon-phone,icon-headphones,icon-flag,icon-credit-card,icon-save,icon-megaphone,icon-key,icon-euro,icon-pound,icon-dollar,icon-rupee,icon-yen,icon-rouble,icon-try,icon-won,icon-bitcoin,icon-anchor,icon-support,icon-blocks,icon-block,icon-graduate,icon-shield,icon-window,icon-coverflow,icon-flight,icon-brush,icon-resize-full,icon-news,icon-pin,icon-params,icon-beaker,icon-delivery,icon-bell,icon-help,icon-laptop,icon-tablet,icon-mobile,icon-thumb,icon-briefcase,icon-direction,icon-ticket,icon-chart,icon-book,icon-print,icon-on,icon-off,icon-featured-area, icon-team, icon-login, icon-clients, icon-tabs, icon-tags, icon-gauge, icon-bag, icon-key, icon-glasses, icon-ok-full, icon-restart, icon-recursive, icon-shuffle, icon-ribbon, icon-lamp, icon-flash, icon-leaf, icon-chart-pie-outline, icon-puzzle, icon-fullscreen, icon-downscreen, icon-zoom-in, icon-zoom-out, icon-pencil-alt, icon-down-dir, icon-left-dir, icon-right-dir, icon-up-dir';
	$font_subsets = implode(", ", $new_font_subsets);

	$font_weight  = @$options['secondary_text']['font_weight'];
	$font_style   = @$options['secondary_text']['font_style'];
	$headings_text= @$options['secondary_text']['text'];

	$html = '<select name="videotouch_typography[secondary_text][type]" class="ts-typo-secondary_text">
					<option value="std" '. selected( @$options["secondary_text"]["type"], 'std', false ).'>'.__( 'Standart fonts', 'touchsize' ).'</option>
					<option value="google" '. selected( @$options["secondary_text"]["type"], 'google', false ). '>'.__( 'Google fonts', 'touchsize' ).'</option>
					<option value="custom_font" '. selected( @$options["secondary_text"]["type"], 'custom_font', false ). '>'.__( 'Custom font', 'touchsize' ).'</option>
			</select>';

	$html .= '<p class="description">' .@$args[0]. '</p>';
	$html .= '<div id="ts-typo-secondary_text-gfonts">';
		_e( 'Select font', 'touchsize' );
	?>
		<script>
			jQuery(document).ready(function($) {
				ts_google_fonts(jQuery, {
					font_name: '<?php echo esc_attr($font_name)?>',
					selected_subsets: [<?php echo $font_subsets; ?>],
					allfonts: $("#fontchanger-secondary_text"),
					prefix: 'secondary_text',
					subsetsTypes: $('.secondary_text-subset-types'),
					section: 'videotouch_typography'
				});
			});
		</script>
	<?php
		$html .= '
				<select name="videotouch_typography[secondary_text][font_name]" id="fontchanger-secondary_text">
					<option value="0">'.__( 'No font selected', 'touchsize' ).'</option>
				</select>
			<div>' . __( 'Available subsets:', 'touchsize' ) . '
				<div class="secondary_text-subset-types">

				</div>
				<p>' . __('Font weight', 'touchsize') . ':</p>

				<select name="videotouch_typography[secondary_text][font_weight]" id="secondary_text-font-weight">
					<option value="400" ' . selected( @$options["secondary_text"]["font_weight"], '400', false ) . '>regular</option>
					<option value="700" '. selected( @$options["secondary_text"]["font_weight"], '700', false ) . '>bold</option>
				</select><br/><br/>

				<p>' . __('Font-style', 'touchsize') . ':</p>
				<select name="videotouch_typography[secondary_text][font_style]" id="secondary_text-font-style">
					<option value="400" '. selected( @$options["secondary_text"]["font_style"], '400', false ) .'>regular</option>
					<option value="italic" '. selected( @$options["secondary_text"]["font_style"], 'italic', false ) .'>italic</option>
				</select>

				<p>' . __('Menu font size', 'touchsize') . ':</p>
				<input type="text" name="videotouch_typography[secondary_text][font_size]" value="' . @$options["secondary_text"]["font_size"] . '" />
				<div class="ts-option-description">This will affect the menus of the website. Write your number (!integer) in PIXELS.</div>

				<p class="logo-secundary-preview">' . __( 'Logo text', 'touchsize' ) . '</p>
				<textarea type="text" name="videotouch_typography[secondary_text][text]" id="secondary_text-demo">' . esc_attr($headings_text) . '</textarea>
				<input type="button" name="ts-secondary_text-preview" id="secondary_text-preview" class="button-primary" value="Preview"/>
				<div class="secondary_text-output"></div>';
	$html .= '</div></div>';

	$html .= '<div id="custom-secondary-font">';

	$html .= '<p>'.__( 'Upload file "eot":', 'touchsize' ).'</p>';
	$html .= '<input type="text" name="videotouch_typography[secondary_text][font_eot]" value="'. esc_attr($options['secondary_text']['font_eot']) .'" id="atachment-secondary-eot"/>
			  <input type="hidden" value="" id="file_secondary_eot"/>
			  <input class="button-primary" id="upload_secondary_eot" type="button" value="'.__('Upload', 'touchsize').'">';

	$html .= '<p>'.__( 'Upload file "svg":', 'touchsize' ).'</p>';
	$html .= '<input type="text" name="videotouch_typography[secondary_text][font_svg]" value="'. esc_attr($options['secondary_text']['font_svg']) .'" id="atachment-secondary-svg"/>
			  <input type="hidden" value="" id="file_secondary_svg"/>
			  <input type="button" class="button-primary" id="upload_secondary_svg" value="'.__('Upload', 'touchsize').'">';

	$html .= '<p>'.__( 'Upload file "ttf":', 'touchsize' ).'</p>';
	$html .= '<input type="text" name="videotouch_typography[secondary_text][font_ttf]" value="'. esc_attr($options['secondary_text']['font_ttf']) .'" id="atachment-secondary-ttf"/>
			  <input type="hidden" value="" id="file_secondary_ttf"/>
			  <input type="button" class="button-primary" id="upload_secondary_ttf" value="'.__('Upload', 'touchsize').'">';

	$html .= '<p>'.__( 'Upload file "woff":', 'touchsize' ).'</p>';
	$html .= '<input type="text" name="videotouch_typography[secondary_text][font_woff]" value="'. esc_attr($options['secondary_text']['font_woff']) .'" id="atachment-secondary-woff"/>
			  <input type="hidden" value="" id="file_secondary_woff"/>
			  <input type="button" class="button-primary" id="upload_secondary_woff" value="'.__('Upload', 'touchsize').'">';

	$html .= '<p>'.__( 'Enter font-family(stylesheet.css):', 'touchsize' ).'</p>';
	$html .= '<input type="text" name="videotouch_typography[secondary_text][font_family]" value="'. @$options['secondary_text']['font_family'] .'" />';

	$html .= '</div>';
	$html .= '<div class="hidden"><input name="videotouch_typography[icons]" type="hidden" value="'.@$ts_icons.'"></div>';

	echo $html;
} // END toggle_secondary_text_callback()

function videotouch_single_post_options()
{
	$default_update = get_option('videotouch_single_post');
	if( !isset($default_update['video_sidebar']) || !isset($default_update['default_videoplayer']) ){
		$default_update['video_sidebar'] = 'n';
		$default_update['default_videoplayer'] = 'n';
		update_option('videotouch_single_post', $default_update);
	}
	//delete_option('videotouch_single_post');
	if( false === get_option( 'videotouch_single_post' ) ) {
		add_option( 'videotouch_single_post', array() );

		$data = array(
			'related_posts' => 'Y',
			'number_of_related_posts' => 4,
			'related_posts_nr_of_columns' => 2,
			'related_posts_type' => 'thumbnails',
			'related_posts_selection_criteria' => 'by_tags',
			'social_sharing' => 'Y',
			'post_tags' => 'Y',
			'post_meta' => 'Y',
			'post_pagination' => 'Y',
			'resize_video' => 'big',
			'show_more' => 'y',
			'user_settings' => '',
			'user_add_post' => '',
			'user_edit_post' => '',
			'user_profile' => '',
			'display_author_box' => 'n',
			'breadcrumbs' => 'y',
			'button_play' => 'y',
			'text-user' => '',
			'video_sidebar' => 'n',
			'default_videoplayer' => 'n',
			'log_video' => 'Y'
		);

		update_option('videotouch_single_post', $data);
	}

	add_settings_section(
		'single_post_settings_section',
		__( 'Single post Options', 'touchsize' ),
		'videotouch_single_post_callback',
		'videotouch_single_post'
	);

	add_settings_field(
		'related_posts',
		__( 'Enable related posts', 'touchsize' ),
		'toggle_related_posts_callback',
		'videotouch_single_post',
		'single_post_settings_section',
		array(
			__( 'Settings for related posts on single posts', 'touchsize' )
		)
	);

	add_settings_field(
		'default_videoplayer',
		__( 'Set JW Player as default', 'touchsize' ),
		'toggle_default_videoplayer_callback',
		'videotouch_single_post',
		'single_post_settings_section',
		array(
			__( 'Make JW Player as default video player for video posts on single posts', 'touchsize' )
		)
	);

	add_settings_field(
		'social_sharing',
		__( 'Social sharing', 'touchsize' ),
		'toggle_social_sharing_callback',
		'videotouch_single_post',
		'single_post_settings_section',
		array(
			__( 'Enable social sharing on single posts.', 'touchsize' )
		)
	);

	add_settings_field(
		'log_video',
		__( 'Show video users not loggeded', 'videotouch' ),
		'toggle_log_video_callback',
		'videotouch_single_post',
		'single_post_settings_section',
		array('')
	);

	add_settings_field(
		'button_play',
		__( 'Display button play', 'touchsize' ),
		'toggle_button_play_callback',
		'videotouch_single_post',
		'single_post_settings_section',
		array(
			__( 'Enable button play on single posts.', 'touchsize' )
		)
	);

	add_settings_field(
		'post_meta',
		__( 'Display post meta', 'touchsize' ),
		'toggle_post_meta_callback',
		'videotouch_single_post',
		'single_post_settings_section',
		array(
			__( 'Use this option to show or hide meta in posts.', 'touchsize' )
		)
	);

	add_settings_field(
		'post_tags',
		__( 'Display post tags', 'touchsize' ),
		'toggle_post_tags_callback',
		'videotouch_single_post',
		'single_post_settings_section',
		array(
			__( 'Show or hide tags in single posts.', 'touchsize' )
		)
	);

	add_settings_field(
		'post_pagination',
		__( 'Display pagination in single post', 'touchsize' ),
		'toggle_post_pagination_callback',
		'videotouch_single_post',
		'single_post_settings_section',
		array(
			__( 'Show or hide pagination in single posts.', 'touchsize' )
		)
	);

	add_settings_field(
		'resize_video',
		__( 'Default video size:', 'touchsize' ),
		'toggle_resize_video_callback',
		'videotouch_single_post',
		'single_post_settings_section',
		array(
			__( 'Show big or small video in single post', 'touchsize' )
		)
	);

	add_settings_field(
		'video_sidebar',
		__( 'Display video 3/4 width right sidebar', 'touchsize' ),
		'toggle_video_sidebar_callback',
		'videotouch_single_post',
		'single_post_settings_section',
		array(
			__( '', 'touchsize' )
		)
	);

	add_settings_field(
		'display_author_box',
		__( 'Hide author box', 'touchsize' ),
		'toggle_display_author_box_callback',
		'videotouch_single_post',
		'single_post_settings_section',
		array(
			__( 'You can globally author box on your single posts.', 'touchsize' )
		)
	);

	add_settings_field(
		'show_more',
		__( 'Enable show more for video content:', 'touchsize' ),
		'toggle_show_more_callback',
		'videotouch_single_post',
		'single_post_settings_section',
		array(
			__( 'Show or hide the button details in single video post', 'touchsize' )
		)
	);

	add_settings_field(
		'user_settings',
		__( 'Choose the page with user settings template:', 'touchsize' ),
		'toggle_user_settings_callback',
		'videotouch_single_post',
		'single_post_settings_section',
		array(
			__( 'First of all, you will need to create a blank page with the "Frontend - User settings" page template. After you create it, set it here.', 'touchsize' )
		)
	);

	add_settings_field(
		'user_add_post',
		__( 'Choose the page with frontend add post template:', 'touchsize' ),
		'toggle_user_add_post_callback',
		'videotouch_single_post',
		'single_post_settings_section',
		array(
			__( 'Create a blank page with the "Frontend - Add post" page template. After you create it, set it here.', 'touchsize' )
		)
	);

	add_settings_field(
		'user_edit_post',
		__( 'Choose the page with frontend edit post template:', 'touchsize' ),
		'toggle_user_edit_post_callback',
		'videotouch_single_post',
		'single_post_settings_section',
		array(
			__( 'Create a blank page with the "Frontend - Edit post" page template. After you create it, set it here.', 'touchsize' )
		)
	);

	add_settings_field(
		'user_profile',
		__( 'Choose the page with frontend profile template:', 'touchsize' ),
		'toggle_user_profile_callback',
		'videotouch_single_post',
		'single_post_settings_section',
		array(
			__( 'Create a blank page with the "Frontend - My profile" page template. After you create it, set it here.', 'touchsize' )
		)
	);

	add_settings_field(
		'breadcrumbs',
		__( 'Breadcrumbs:', 'touchsize' ),
		'toggle_breadcrumbs_callback',
		'videotouch_single_post',
		'single_post_settings_section',
		array(
			__( 'Activate or disable breadcrumbs on your website.', 'touchsize' )
		)
	);

	add_settings_field(
		'text-user',
		__( 'Frontend text user add post', 'touchsize' ),
		'toggle_text_user_callback',
		'videotouch_single_post',
		'single_post_settings_section',
		array(
			__( '', 'touchsize' )
		)
	);

	register_setting( 'videotouch_single_post', 'videotouch_single_post');

} // end videotouch_single_post_options()

add_action( 'admin_init', 'videotouch_single_post_options' );

/**************************************************
 * Single post Section Callbacks
 *************************************************/

function videotouch_single_post_callback()
{
	echo '<p>'.__( 'Single posts settings options. In this section you can enable/disable related posts, social sharing, tags.', 'touchsize' ).'</p>';
} // end videotouch_single_post_callback()

function toggle_related_posts_callback($args)
{
	$options = get_option('videotouch_single_post');

	$html = '<select name="videotouch_single_post[related_posts]" class="ts-related-posts">
				<option value="Y" '. selected( @$options["related_posts"], 'Y', false ).'>'.__( 'Yes', 'touchsize' ).'</option>
				<option value="N" '. selected( @$options["related_posts"], 'N', false ). '>'.__( 'No', 'touchsize' ).'</option>
			</select>';

	$html .= '<p class="description">' .@$args[0]. '</p>';

	$number_of_related_posts = (int)@$options['number_of_related_posts'];
	$number_of_related_posts = ($number_of_related_posts < 1) ? 3 : $number_of_related_posts;

	$related_posts_nr_of_columns = (int)@$options['related_posts_nr_of_columns'];

	$html .= '<div id="ts-related-posts-options">';

	$html .= '<p>'.__( 'Number of related posts', 'touchsize' ).'</p>';

	$html .= '<select name="videotouch_single_post[number_of_related_posts]">
		<option value="2" '.selected( $number_of_related_posts, '2', false ). '>2</option>
		<option value="3" '.selected( $number_of_related_posts, '3', false ). '>3</option>
		<option value="4" '.selected( $number_of_related_posts, '4', false ). '>4</option>
		<option value="6" '.selected( $number_of_related_posts, '6', false ). '>6</option>
		<option value="9" '.selected( $number_of_related_posts, '9', false ). '>9</option>
	</select>';

	$html .= '<p>' . __( 'Number of columns', 'touchsize' ) . '</p>';

	$html .= '<select name="videotouch_single_post[related_posts_nr_of_columns]">
				<option value="2" '.selected( $related_posts_nr_of_columns, '2', false ). '>2</option>
				<option value="3" '.selected( $related_posts_nr_of_columns, '3', false ). '>3</option>
				<option value="4" '.selected( $related_posts_nr_of_columns, '4', false ). '>4</option>
			</select>';

	$html .= '<p>' . __( 'Post type', 'touchsize' ) . '</p>';

	$html .= '<select name="videotouch_single_post[related_posts_type]">
				<option value="grid" '. selected( @$options["related_posts_type"], 'grid', false ).'>'.__( 'Grid', 'touchsize' ).'</option>
				<option value="thumbnails" '. selected( @$options["related_posts_type"], 'thumbnails', false ). '>'.__( 'Thumbnail', 'touchsize' ).'</option>
			</select>';

	$html .= '<p>'.__( 'Selection criteria', 'touchsize' ).'</p>';

	$html .= '<select name="videotouch_single_post[related_posts_selection_criteria]">
				<option value="by_tags" '. selected( @$options["related_posts_selection_criteria"], 'by_tags', false ).'>'.__( 'by Tags', 'touchsize' ).'</option>
				<option value="by_categs" '. selected( @$options["related_posts_selection_criteria"], 'by_categs', false ). '>'.__( 'by Categories', 'touchsize' ).'</option>
			</select>';
	$html .= '</div>';

	echo $html;

} // END toggle_related_posts_callback()

function toggle_default_videoplayer_callback($args)
{

	$options = get_option('videotouch_single_post');

	$html = '<select name="videotouch_single_post[default_videoplayer]">
				<option value="n" '. selected( @$options["default_videoplayer"], 'n', false ). '>'.__( 'No', 'touchsize' ).'</option>
				<option value="y" '. selected( @$options["default_videoplayer"], 'y', false ).'>'.__( 'Yes', 'touchsize' ).'</option>
			</select>';
	$html .= '<p class="description">' .@$args[0]. '</p>';

	echo $html;

} // END toggle_default_videoplayer_callback()

function toggle_social_sharing_callback($args)
{

	$options = get_option('videotouch_single_post');

	$html = '<select name="videotouch_single_post[social_sharing]">
				<option value="Y" '. selected( @$options["social_sharing"], 'Y', false ).'>'.__( 'Yes', 'touchsize' ).'</option>
				<option value="N" '. selected( @$options["social_sharing"], 'N', false ). '>'.__( 'No', 'touchsize' ).'</option>
			</select>';
	$html .= '<p class="description">' .@$args[0]. '</p>';

	echo $html;

} // END toggle_social_sharing_callback()

function toggle_log_video_callback($args)
{
	$options = get_option( 'videotouch_single_post' );
	$log_video = isset( $options['log_video'] ) ? $options['log_video'] : 'Y';

	$html = '<div>
	            <select name="videotouch_single_post[log_video]">
	                <option value="Y" ' . selected( $log_video, 'Y', false ) . '>' . esc_html__( 'Yes', 'videotouch' ) . '</option>
	                <option value="N" ' . selected( $log_video, 'N', false ) . '>' . esc_html__( 'No', 'videotouch' ) . '</option>
	            </select>
	         </div>';
	$html .= '<p class="description">' . $args[0] . '</p>';

	echo $html;
}

function toggle_button_play_callback($args)
{

	$options = get_option('videotouch_single_post');

	$html = '<select name="videotouch_single_post[button_play]">
				<option value="y" '. selected( @$options["button_play"], 'y', false ).'>'.__( 'Yes', 'touchsize' ).'</option>
				<option value="n" '. selected( @$options["button_play"], 'n', false ). '>'.__( 'No', 'touchsize' ).'</option>
			</select>';
	$html .= '<p class="description">' .@$args[0]. '</p>';

	echo $html;

}

function toggle_post_meta_callback($args)
{
	$options = get_option('videotouch_single_post');

	$html = '<select name="videotouch_single_post[post_meta]">
				<option value="Y" '. selected( @$options["post_meta"], 'Y', false ).'>'.__( 'Yes', 'touchsize' ).'</option>
				<option value="N" '. selected( @$options["post_meta"], 'N', false ). '>'.__( 'No', 'touchsize' ).'</option>
			</select>';
	$html .= '<p class="description">' .@$args[0]. '</p>';

	echo $html;

} // END toggle_post_meta_callback()

function toggle_post_tags_callback($args)
{
	$options = get_option('videotouch_single_post');

	$html = '<select name="videotouch_single_post[post_tags]">
				<option value="Y" '. selected( @$options["post_tags"], 'Y', false ).'>'.__( 'Yes', 'touchsize' ).'</option>
				<option value="N" '. selected( @$options["post_tags"], 'N', false ). '>'.__( 'No', 'touchsize' ).'</option>
			</select>';
	$html .= '<p class="description">' .@$args[0]. '</p>';

	echo $html;

} // toggle_post_tags_callback()

function toggle_resize_video_callback($args)
{
	$options = get_option('videotouch_single_post');

	$html = '<select name="videotouch_single_post[resize_video]">
				<option value="small" '. selected( @$options["resize_video"], 'small', false ).'>'.__( 'Small', 'touchsize' ).'</option>
				<option value="big" '. selected( @$options["resize_video"], 'big', false ). '>'.__( 'Big', 'touchsize' ).'</option>
			</select>';
	$html .= '<p class="description">' .@$args[0]. '</p>';

	echo $html;

}

function toggle_video_sidebar_callback($args)
{
	$options = get_option('videotouch_single_post');

	$html = '<select name="videotouch_single_post[video_sidebar]">
				<option value="y" '. selected( @$options["video_sidebar"], 'y', false ).'>'.__( 'Yes', 'touchsize' ).'</option>
				<option value="n" '. selected( @$options["video_sidebar"], 'n', false ). '>'.__( 'No', 'touchsize' ).'</option>
			</select>';
	$html .= '<p class="description">' .@$args[0]. '</p>';

	echo $html;

}

function toggle_show_more_callback($args)
{
	$options = get_option('videotouch_single_post');

	$html = '<select name="videotouch_single_post[show_more]">
				<option value="y" '. selected( @$options["show_more"], 'y', false ).'>'.__( 'Yes', 'touchsize' ).'</option>
				<option value="n" '. selected( @$options["show_more"], 'n', false ). '>'.__( 'No', 'touchsize' ).'</option>
			</select>';
	$html .= '<p class="description">' .@$args[0]. '</p>';

	echo $html;

}

function toggle_user_settings_callback($args)
{
	$options = get_option('videotouch_single_post');
	$pages = get_all_page_ids();
	$select_page = $options['user_settings'];

	$html  = '<select name="videotouch_single_post[user_settings]">';
			if( isset($pages) && !empty($pages) && is_array($pages) ){
				foreach($pages as $page_id){
					$title = get_the_title($page_id);
					$link  = get_permalink($page_id);
					$selected = (isset($select_page) && $link === $select_page) ? 'selected="selected"' : '';
					$html .= '<option ' . $selected . ' value="' . $link . '">'. $title .'</option>';
			 	}
			}
	$html .= '</select>';
	$html .= '<p class="description">' .@$args[0]. '</p>';

	echo $html;

}

function toggle_user_add_post_callback($args)
{
	$options = get_option('videotouch_single_post');
	$pages = get_all_page_ids();
	$select_page = $options['user_add_post'];

	$html  = '<select name="videotouch_single_post[user_add_post]">';
			if( isset($pages) && !empty($pages) && is_array($pages) ){
				foreach($pages as $page_id){
					$title = get_the_title($page_id);
					$link  = get_permalink($page_id);
					$selected = (isset($select_page) && $link === $select_page) ? 'selected="selected"' : '';
					$html .= '<option ' . $selected . ' value="' . $link . '">'. $title .'</option>';
			 	}
			}
	$html .= '</select>';
	$html .= '<p class="description">' .@$args[0]. '</p>';

	echo $html;

}

function toggle_user_edit_post_callback($args)
{
	$options = get_option('videotouch_single_post');
	$pages = get_all_page_ids();
	$select_page = $options['user_edit_post'];

	$html  = '<select name="videotouch_single_post[user_edit_post]">';
			if( isset($pages) && !empty($pages) && is_array($pages) ){
				foreach($pages as $page_id){
					$title = get_the_title($page_id);
					$link  = get_permalink($page_id);
					$selected = (isset($select_page) && $link === $select_page) ? 'selected="selected"' : '';
					$html .= '<option ' . $selected . ' value="' . $link . '">'. $title .'</option>';
			 	}
			}
	$html .= '</select>';
	$html .= '<p class="description">' .@$args[0]. '</p>';

	echo $html;

}

function toggle_user_profile_callback($args)
{
	$options = get_option('videotouch_single_post');
	$pages = get_all_page_ids();
	$select_page = $options['user_profile'];

	$html  = '<select name="videotouch_single_post[user_profile]">';
			if( isset($pages) && !empty($pages) && is_array($pages) ){
				foreach($pages as $page_id){
					$title = get_the_title($page_id);
					$link  = get_permalink($page_id);
					$selected = (isset($select_page) && $link === $select_page) ? 'selected="selected"' : '';
					$html .= '<option ' . $selected . ' value="' . $link . '">'. $title .'</option>';
			 	}
			}
	$html .= '</select>';
	$html .= '<p class="description">' .@$args[0]. '</p>';

	echo $html;

}

function toggle_display_author_box_callback($args)
{
	$options = get_option('videotouch_single_post');

	$html = '<select name="videotouch_single_post[display_author_box]">
				<option value="y" ' . selected(@$options["display_author_box"], 'y', false) .'>'.__( 'Yes', 'touchsize' ) . '</option>
				<option value="n" ' . selected(@$options["display_author_box"], 'n', false) . '>'.__( 'No', 'touchsize' ) . '</option>
			</select>';
	$html .= '<p class="description">' . @$args[0] . '</p>';

	echo $html;

}

function toggle_breadcrumbs_callback($args)
{
	$options = get_option('videotouch_single_post');

	$html = '<select name="videotouch_single_post[breadcrumbs]">
				<option value="y" ' . selected(@$options["breadcrumbs"], 'y', false) .'>'.__( 'Yes', 'touchsize' ) . '</option>
				<option value="n" ' . selected(@$options["breadcrumbs"], 'n', false) . '>'.__( 'No', 'touchsize' ) . '</option>
			</select>';
	$html .= '<p class="description">' . @$args[0] . '</p>';

	echo $html;

}

function toggle_text_user_callback($args)
{
	$options = get_option('videotouch_single_post');
	$text = (isset($options['text-user'])) ? $options['text-user'] : '';

	$html = wp_editor($text, 'text-user-frontend', array('textarea_name' => 'videotouch_single_post[text-user]'));

	echo $html;

}

function toggle_post_pagination_callback($args)
{
	$options = get_option('videotouch_single_post');

	$html = '<select name="videotouch_single_post[post_pagination]">
				<option value="Y" '. selected( @$options["post_pagination"], 'Y', false ).'>'.__( 'Yes', 'touchsize' ).'</option>
				<option value="N" '. selected( @$options["post_pagination"], 'N', false ). '>'.__( 'No', 'touchsize' ).'</option>
			</select>';
	$html .= '<p class="description">' .@$args[0]. '</p>';

	echo $html;

} // toggle_post_pagination_callback()

function videotouch_page_options()
{
	//delete_option('videotouch_page');
	if( false === get_option( 'videotouch_page' ) ) {
		add_option( 'videotouch_page' );

		$data = array(
			'social_sharing' => 'Y',
			'post_meta' => 'Y',
			'breadcrumbs' => 'y'
		);

		update_option('videotouch_page', $data);
	}

	// Register a section
	add_settings_section(
		'page_settings_section',
		__( 'Page options', 'touchsize' ),
		'videotouch_page_callback',
		'videotouch_page'
	);

	add_settings_field(
		'social_sharing',
		__( 'Social sharing', 'touchsize' ),
		'toggle_page_social_sharing_callback',
		'videotouch_page',
		'page_settings_section',
		array(
			__( 'This will enable/disable social sharing buttons on pages.', 'touchsize' )
		)
	);

	add_settings_field(
		'post_meta',
		__( 'Display page meta', 'touchsize' ),
		'toggle_page_post_meta_callback',
		'videotouch_page',
		'page_settings_section',
		array(
			__( 'Show/hide page meta', 'touchsize' )
		)
	);

	add_settings_field(
		'breadcrumbs',
		__( 'Breadcrumbs', 'touchsize' ),
		'toggle_page_breadcrumbs_callback',
		'videotouch_page',
		'page_settings_section',
		array(
			__( 'Show/hide page meta', 'touchsize' )
		)
	);
	register_setting( 'videotouch_page', 'videotouch_page');

} // end videotouch_page_options

add_action( 'admin_init', 'videotouch_page_options' );

/**************************************************
 * Single post Section Callbacks
 *************************************************/

function videotouch_page_callback()
{

	echo '<p>'.__( 'In this section you can change settings for pages, to enable/disable page meta and social sharing.', 'touchsize' ).'</p>';
} // END videotouch_page_callback

function toggle_page_social_sharing_callback($args)
{
	$options = get_option('videotouch_page');

	$html = '<select name="videotouch_page[social_sharing]">
				<option value="Y" '. selected( @$options["social_sharing"], 'Y', false ).'>'.__( 'Yes', 'touchsize' ).'</option>
				<option value="N" '. selected( @$options["social_sharing"], 'N', false ). '>'.__( 'No', 'touchsize' ).'</option>
			</select>';
	$html .= '<p class="description">' .@$args[0]. '</p>';
	echo $html;
}

function toggle_page_post_meta_callback($args)
{
	$options = get_option('videotouch_page');

	$html = '<select name="videotouch_page[post_meta]">
				<option value="Y" '. selected( @$options["post_meta"], 'Y', false ).'>'.__( 'Yes', 'touchsize' ).'</option>
				<option value="N" '. selected( @$options["post_meta"], 'N', false ). '>'.__( 'No', 'touchsize' ).'</option>
			</select>';
	$html .= '<p class="description">' .@$args[0]. '</p>';

	echo $html;
}

function toggle_page_breadcrumbs_callback($args)
{
	$options = get_option('videotouch_page');

	$html = '<select name="videotouch_page[breadcrumbs]">
				<option value="y" ' . selected( @$options["breadcrumbs"], 'y', false ) .'>' . __( 'Yes', 'touchsize' ) . '</option>
				<option value="n" ' . selected( @$options["breadcrumbs"], 'n', false ) . '>' . __( 'No', 'touchsize' ) . '</option>
			</select>';
	$html .= '<p class="description">' .@$args[0]. '</p>';

	echo $html;
}

function videotouch_social_options()
{		$x = get_option('videotouch_social');

	$social = array();
	if( isset($_POST) && isset($_POST['videotouch_social']['social-new']) && !empty($_POST['videotouch_social']['social-new']) && is_array($_POST['videotouch_social']['social-new']) ){
		foreach ($_POST['videotouch_social']['social-new'] as $key => $value) {
			$url = (isset($value['url'])) ? esc_attr($url) : '';
			$image = (isset($value['image'])) ? esc_url($image) : '';
			$color = (isset($value['color'])) ? esc_attr($color) : '';
			$social[]['url'] = $url;
			$social[]['image'] = $image;
			$social[]['color'] = $color;
		}
	}
	if( false === get_option( 'videotouch_social' ) ) {
		add_option( 'videotouch_social' );

		$data = array(
			'email'		 => '',
			'skype'      => '',
			'github'     => '',
			'gplus'      => '',
			'dribble'    => '',
			'lastfm'     => '',
			'linkedin'   => '',
			'tumblr'     => '',
			'twitter'    => '',
			'vimeo'      => '',
			'wordpress'  => '',
			'yahoo'      => '',
			'youtube'    => '',
			'facebook'   => '',
			'flickr'     => '',
			'pinterest'  => '',
			'instagram'  => '',
			'social-new' => $social
		);

		update_option('videotouch_social', $data);
	}

	add_settings_section(
		'social_section',
		__( 'Social icons options', 'touchsize' ),
		'videotouch_social_callback',
		'videotouch_social'
	);

	add_settings_field(
		'email',
		__( 'Email', 'touchsize' ),
		'toggle_email_callback',
		'videotouch_social',
		'social_section',
		array(
			__( 'This email is used to receive emails from contact form', 'touchsize' )
		)
	);

	add_settings_field(
		'skype',
		__( 'Skype', 'touchsize' ),
		'toggle_skype_social_callback',
		'videotouch_social',
		'social_section',
		array(
			__( 'Insert your Skype here', 'touchsize' )
		)
	);

	add_settings_field(
		'github',
		__( 'Github', 'touchsize' ),
		'toggle_github_social_callback',
		'videotouch_social',
		'social_section',
		array(
			__( 'Insert your github page here', 'touchsize' )
		)
	);

	add_settings_field(
		'gplus',
		__( 'Google+', 'touchsize' ),
		'toggle_google_social_callback',
		'videotouch_social',
		'social_section',
		array(
			__( 'Insert your Google+ page here.', 'touchsize' )
		)
	);

	add_settings_field(
		'dribble',
		__( 'Dribble', 'touchsize' ),
		'toggle_dribble_social_callback',
		'videotouch_social',
		'social_section',
		array(
			__( 'Insert your Dribbble page here.', 'touchsize' )
		)
	);

	add_settings_field(
		'lastfm',
		__( 'last.fm', 'touchsize' ),
		'toggle_lastfm_social_callback',
		'videotouch_social',
		'social_section',
		array(
			__( 'Insert your last.fm page here.', 'touchsize' )
		)
	);

	add_settings_field(
		'linkedin',
		__( 'LinkedIn', 'touchsize' ),
		'toggle_linkedin_social_callback',
		'videotouch_social',
		'social_section',
		array(
			__( 'Insert your LinkedIn here.', 'touchsize' )
		)
	);

	add_settings_field(
		'tumblr',
		__( 'Tumblr', 'touchsize' ),
		'toggle_tumblr_social_callback',
		'videotouch_social',
		'social_section',
		array(
			__( 'Insert your Tumblr page here.', 'touchsize' )
		)
	);

	add_settings_field(
		'twitter',
		__( 'Twitter', 'touchsize' ),
		'toggle_twitter_social_callback',
		'videotouch_social',
		'social_section',
		array(
			__( 'Insert your Twitter page here.', 'touchsize' )
		)
	);

	add_settings_field(
		'vimeo',
		__( 'Vimeo', 'touchsize' ),
		'toggle_vimeo_social_callback',
		'videotouch_social',
		'social_section',
		array(
			__( 'Insert your Vimeo page here.', 'touchsize' )
		)
	);

	add_settings_field(
		'wordpress',
		__( 'WordPress', 'touchsize' ),
		'toggle_wordpress_social_callback',
		'videotouch_social',
		'social_section',
		array(
			__( 'Insert your WordPress page here.', 'touchsize' )
		)
	);

	add_settings_field(
		'yahoo',
		__( 'Yahoo', 'touchsize' ),
		'toggle_yahoo_social_callback',
		'videotouch_social',
		'social_section',
		array(
			__( 'Insert your Yahoo ID here.', 'touchsize' )
		)
	);

	add_settings_field(
		'youtube',
		__( 'Youtube', 'touchsize' ),
		'toggle_youtube_social_callback',
		'videotouch_social',
		'social_section',
		array(
			__( 'Insert your YouTube page here.', 'touchsize' )
		)
	);

	add_settings_field(
		'facebook',
		__( 'Facebook', 'touchsize' ),
		'toggle_facebook_social_callback',
		'videotouch_social',
		'social_section',
		array(
			__( 'Insert your Facebook page here.', 'touchsize' )
		)
	);

	add_settings_field(
		'flickr',
		__( 'Flickr', 'touchsize' ),
		'toggle_flickr_social_callback',
		'videotouch_social',
		'social_section',
		array(
			__( 'Insert your Flickr page here.', 'touchsize' )
		)
	);

	add_settings_field(
		'pinterest',
		__( 'Pinterest', 'touchsize' ),
		'toggle_pinterest_social_callback',
		'videotouch_social',
		'social_section',
		array(
			__( 'Insert your Pinterest page here.', 'touchsize' )
		)
	);

	add_settings_field(
		'instagram',
		__( 'Instagram', 'touchsize' ),
		'toggle_instagram_social_callback',
		'videotouch_social',
		'social_section',
		array(
			__( 'Insert your Instagram page here.', 'touchsize' )
		)
	);

	add_settings_field(
		'social-new',
		__( 'Add new', 'touchsize' ),
		'toggle_social_new_callback',
		'videotouch_social',
		'social_section',
		array(
			__( 'Insert your new social page here.', 'touchsize' )
		)
	);

	register_setting( 'videotouch_social', 'videotouch_social');

} // END videotouch_social_options

add_action( 'admin_init', 'videotouch_social_options' );

function videotouch_theme_update_options()
{
	//delete_option('videotouch_theme_update');
	if( false === get_option( 'videotouch_theme_update' ) ) {
		add_option( 'videotouch_theme_update' );

		$data = array(
			'update_options'=> array(
				'user_name' => '',
				'key_api'   => ''
			)
		);

		update_option('videotouch_theme_update', $data);
	}

	add_settings_section(
		'theme_update_section',
		__( 'Update your Theme from the WordPress Dashboard', 'touchsize' ),
		'videotouch_theme_update_callback',
		'videotouch_theme_update'
	);

	register_setting( 'videotouch_theme_update', 'videotouch_theme_update');

}

add_action( 'admin_init', 'videotouch_theme_update_options' );

function videotouch_theme_advertising_options()
{
	//delete_option('videotouch_theme_advertising');
	if( false === get_option( 'videotouch_theme_advertising' ) ) {
		add_option( 'videotouch_theme_advertising' );

		$data = array(
				'ad_area_1'   => '',
				'ad_area_2'   => '',
				'pre_roll'    => array()
		);

		update_option('videotouch_theme_advertising', $data);
	}

	add_settings_section(
		'theme_advertising_section',
		__( 'Advertising code', 'touchsize' ),
		'videotouch_theme_advertising_callback',
		'videotouch_theme_advertising'
	);

	add_settings_field(
		'ad_area_1',
		__( 'Area 1', 'touchsize' ),
		'videotouch_add_area_1_callback',
		'videotouch_theme_advertising',
		'theme_advertising_section',
		array(
			__( 'This advertising will be shown <b>above the video</b> on the video single post. Used only for custom video posts.', 'touchsize' )
		)
	);

	add_settings_field(
		'ad_area_2',
		__( 'Area 2', 'touchsize' ),
		'videotouch_add_area_2_callback',
		'videotouch_theme_advertising',
		'theme_advertising_section',
		array(
			__( 'This advertising will be shown <b>above the comments</b> on the single post. Used only for any theme posts types.', 'touchsize' )
		)
	);

	add_settings_field(
		'pre_roll',
		__( 'Add pre roll', 'slimvideo' ),
		'toggle_pre_roll_callback',
		'videotouch_theme_advertising',
		'theme_advertising_section',
		array(
			__( '', 'slimvideo' )
		)
	);

	register_setting( 'videotouch_theme_advertising', 'videotouch_theme_advertising');

}
add_action( 'admin_init', 'videotouch_theme_advertising_options' );

/**************************************************
 * Advertising Section Callbacks
 *************************************************/

function videotouch_theme_advertising_callback()
{

	$html   = '';
	echo $html;
}

function videotouch_add_area_1_callback($args)
{
	$options = get_option('videotouch_theme_advertising');
	$html    = '<textarea name="videotouch_theme_advertising[ad_area_1]" cols="80" rows="10">' . @$options['ad_area_1'] . '</textarea>';
	$html   .= '<p class="description">' . @$args[0] . '</p>';
	echo $html;
}

function videotouch_add_area_2_callback($args)
{
	$options = get_option('videotouch_theme_advertising');
	$html    = '<textarea name="videotouch_theme_advertising[ad_area_2]" cols="80" rows="10">' . @$options['ad_area_2'] . '</textarea>';
	$html   .= '<p class="description">' . @$args[0] . '</p>';
	echo $html;
}

function toggle_pre_roll_callback($args)
{
		$options = get_option('videotouch_theme_advertising');
		$html = '';
		$i = 1;
		$arrayPreRoll = (isset($options['pre_roll']) && is_array($options['pre_roll']) && !empty($options['pre_roll'])) ? $options['pre_roll'] : '';
		$refreshJsForUpload = '<script> jQuery(document).ready(function(){';

		if( !empty($arrayPreRoll) ){
			$html = '<ul>';
			foreach($arrayPreRoll as $key => $option){

				$video = (isset($option['video'])) ? $option['video'] : '';
				$url = (isset($option['url'])) ? $option['url'] : '';
				$views = (isset($option['views']) && (int)$option['views'] > 0) ? $option['views'] : 0;
				$clicks = (isset($option['clicks']) && (int)$option['clicks'] > 0) ? $option['clicks'] : 0;
				$timePreRoll = (isset($option['time']) && (int)$option['time'] > 0) ? $option['time'] : '';
				$limitViews = (isset($option['limit']) && (int)$option['limit'] > 0) ? $option['limit'] : '';
				$active = (isset($option['active']) && ($option['active'] == 'y' || $option['active'] == 'n')) ? $option['active'] : 'y';

				$html .= '<li>
							<div class="sortable-meta-element">
			            		<span class="tab-arrow icon-down"></span> <span class="social-item-tab ts-multiple-item-tab">' . __('Video pre roll:', 'videotouch') . ' ' . $i .'</span>
			             	</div>
			    			<div class="hidden">
						        <table>
						            <tr>
						                <td>'. __('Video', 'videotouch') .'</td>
						                <td>
						                    <input id="ts-video-' . $key . '" type="text" data-role="media-url" name="videotouch_theme_advertising[pre_roll][' . $key . '][video]" value="'. $video .'"/>
						                    <input id="ts_upload-' . $key . '" type="button" class="button" value="'. __( 'Upload video mp4', 'videotouch' ) .'" />
						                </td>
						            </tr>
						            <tr>
						                <td>
						                    '. __('Time video pre-roll (sec)integer', 'videotouch') .'
						                </td>
						                <td>
						                    <input value ="'.  $timePreRoll .'" type="text" name="videotouch_theme_advertising[pre_roll]['. $key .'][time]" />
						                </td>
						            </tr>
						            <tr>
						                <td>
						                    '. __('Link', 'videotouch') .'
						                </td>
						                <td>
						                    <input value ="'.  $url .'" type="text" name="videotouch_theme_advertising[pre_roll]['. $key .'][url]" />
						                </td>
						            </tr>
						            <tr>
						                <td>
						                    '. __('Views', 'videotouch') .'
						                </td>
						                <td>
						                	'. $views .'
						                	<input type="hidden" name="videotouch_theme_advertising[pre_roll]['. $key .'][views]" value="'. $views .'" />
						                </td>
						            </tr>
						            <tr>
						                <td>
						                    '. __('Clicks', 'videotouch') .'
						                </td>
						                <td>
						                	'. $clicks .'
						                	<input type="hidden" name="videotouch_theme_advertising[pre_roll]['. $key .'][clicks]" value="'. $clicks .'" />
						                </td>
						            </tr>
						            <tr>
						                <td>
						                    '. __('Limit view', 'videotouch') .'
						                </td>
						                <td>
						                	<input value ="'.  $limitViews .'" type="text" name="videotouch_theme_advertising[pre_roll]['. $key .'][limit]" />
						                </td>
						            </tr>
						            <tr>
						                <td>
						                    '. __('Active', 'videotouch') .'
						                </td>
						                <td>
						                	<select name="videotouch_theme_advertising[pre_roll]['. $key .'][active]">
						                		<option value="y" '. selected($active, 'y', false) .'>'
						                			. __('Yes', 'videotouch') .'
						                		</option>
						                		<option value="n" '. selected($active, 'n', false) .'>'
						                			. __('No', 'videotouch') .'
						                		</option>
						                	</select>
						                </td>
						            </tr>
						        </table>
				        		<input type="button" class="button button-primary remove-item" value="' . __('Remove', 'videotouch') . '" /></div>
				        	</div>
				       	</li>';

			    $i++;
			}
			$html .= '</ul>';
			$refreshJsForUpload .= "ts_upload_files('#ts_upload-". $key ."', '#ts-hidden-". $key ."', '#ts-video-". $key ."', 'Upload video', '', 'webm');";
		}

		$refreshJsForUpload .= '});</script>';
		$html .= $refreshJsForUpload;
		$html .= '<ul id="preroll_items">
	 			</ul>
		 		<input type="hidden" id="preroll_content" value="" />
	 			<input type="button" class="button ts-multiple-add-button" data-element-name="preroll" id="preroll_add_item" value="'. __('Add new video pre-roll', 'videotouch') .'" />';
	 	$html .= '<script id="preroll_items_template" type="text/template">
		     		<li id="list-item-id-{{item-id}}" class="ts-multiple-add-list-element">
			            <div class="sortable-meta-element">
			            	<span class="tab-arrow icon-down"></span> <span class="ts-multiple-item-tab">'. __('Video pre roll:', 'videotouch') .' {{slide-number}}</span>
			            </div>
			            <div class="hidden">

				       	<table>
				            <tr>
				                <td>'. __('Video', 'videotouch') .'</td>
				                <td>
				                    <input id="ts-video-{{item-id}}" type="text" data-role="media-url" name="videotouch_theme_advertising[pre_roll][{{item-id}}][video]" value=""/>
				                    <input type="hidden" value="" id="ts-hidden-{{item-id}}"/>
				                    <input id="ts_upload-{{item-id}}" type="button" class="button ts-upload-social-image ts-multiple-item-upload" value="' . __( 'Upload video mp4', 'videotouch' ) . '" />
				                </td>
				            </tr>
				            <tr>
				                <td>
				                    '. __('Time video pre-roll (sec)', 'touchsize') .'
				                </td>
				                <td>
				                    <input value ="" type="text" name="videotouch_theme_advertising[pre_roll][{{item-id}}][time]" />
				                </td>
				            </tr>
				            <tr>
				                <td>
				                    '. __('Link', 'touchsize') .'
				                </td>
				                <td>
				                    <input value ="" type="text" name="videotouch_theme_advertising[pre_roll][{{item-id}}][url]" />
				                </td>
				            </tr>
				            <tr>
				                <td>
				                    '. __('Views', 'touchsize') .'
				                    <input type="hidden" name="videotouch_theme_advertising[pre_roll][{{item-id}}][views]" value="0" />
				                </td>
				                <td>
				                	0
				                </td>
				            </tr>
				            <tr>
				                <td>
				                    '. __('Clicks', 'touchsize') .'
				                    <input type="hidden" name="videotouch_theme_advertising[pre_roll][{{item-id}}][clicks]" value="0" />
				                </td>
				                <td>
				                	0
				                </td>
				            </tr>
				            <tr>
				                <td>
				                    '. __('Limit view', 'touchsize') .'
				                </td>
				                <td>
				                	<input value ="" type="text" name="videotouch_theme_advertising[pre_roll][{{item-id}}][limit]" />
				                </td>
				            </tr>
				            <tr>
				                <td>
				                    '. __('Active', 'touchsize') .'
				                </td>
				                <td>
				                	<select name="videotouch_theme_advertising[pre_roll][{{item-id}}][active]">
				                		<option value="y">'
				                			. __('Yes', 'touchsize') .'
				                		</option>
				                		<option value="n">'
				                			. __('No', 'touchsize') .'
				                		</option>
				                	</select>
				                </td>
				            </tr>
				        </table>
			        	<input type="button" class="button button-primary remove-item" value="' . __('Remove', 'videotouch') . '" /></div>
		     		</li>
	     		</script>';
	    $html .= '<p class="description">'. @$args[0] .'</p>';
		echo $html;
}

/**************************************************
 * Single post Section Callbacks
 *************************************************/
function videotouch_social_callback()
{
	echo '<p>'.__( 'Insert your link to the social pages below. These are used for social icons. The email set here is going to be used for contact forms.', 'touchsize' ).'</p>';
} // END videotouch_social_callback

function toggle_email_callback($args)
{
	$options = get_option('videotouch_social');
	$email = is_email(@$options['email']) ? @$options['email'] : '';

	$html = '<input type="text" name="videotouch_social[email]" value="'. $email . '">';
	$html .= '<p class="description">' .@$args[0]. '</p>';
	echo $html;
}

function toggle_skype_social_callback($args)
{
	$options = get_option('videotouch_social');

	$html = '<input type="text" name="videotouch_social[skype]" value="'. @esc_url($options['skype']) . '">';
	$html .= '<p class="description">' .@$args[0]. '</p>';
	echo $html;
}

function toggle_github_social_callback($args)
{
	$options = get_option('videotouch_social');

	$html = '<input type="text" name="videotouch_social[github]" value="'. @esc_url($options['github']) . '">';
	$html .= '<p class="description">' .@$args[0]. '</p>';
	echo $html;
}

function toggle_google_social_callback($args)
{
	$options = get_option('videotouch_social');

	$html = '<input type="text" name="videotouch_social[gplus]" value="'. @esc_url($options['gplus']) . '">';
	$html .= '<p class="description">' .@$args[0]. '</p>';
	echo $html;
}

function toggle_dribble_social_callback($args)
{
	$options = get_option('videotouch_social');

	$html = '<input type="text" name="videotouch_social[dribble]" value="'. @esc_url($options['dribble']) . '">';
	$html .= '<p class="description">' .@$args[0]. '</p>';
	echo $html;
}

function toggle_lastfm_social_callback($args)
{
	$options = get_option('videotouch_social');

	$html = '<input type="text" name="videotouch_social[lastfm]" value="'. @esc_url($options['lastfm']) . '">';
	$html .= '<p class="description">' .@$args[0]. '</p>';
	echo $html;
}


function toggle_linkedin_social_callback($args)
{
	$options = get_option('videotouch_social');

	$html = '<input type="text" name="videotouch_social[linkedin]" value="'. @esc_url($options['linkedin']) . '">';
	$html .= '<p class="description">' .@$args[0]. '</p>';
	echo $html;
}

function toggle_tumblr_social_callback($args)
{
	$options = get_option('videotouch_social');

	$html = '<input type="text" name="videotouch_social[tumblr]" value="'. @esc_url($options['tumblr']) . '">';
	$html .= '<p class="description">' .@$args[0]. '</p>';
	echo $html;
}

function toggle_twitter_social_callback($args)
{
	$options = get_option('videotouch_social');

	$html = '<input type="text" name="videotouch_social[twitter]" value="'. @esc_url($options['twitter']) . '">';
	$html .= '<p class="description">' .@$args[0]. '</p>';

	echo $html;
}

function toggle_vimeo_social_callback($args)
{
	$options = get_option('videotouch_social');

	$html = '<input type="text" name="videotouch_social[vimeo]" value="'. @esc_url($options['vimeo']) . '">';
	$html .= '<p class="description">' .@$args[0]. '</p>';
	echo $html;
}

function toggle_wordpress_social_callback($args)
{
	$options = get_option('videotouch_social');

	$html = '<input type="text" name="videotouch_social[wordpress]" value="'. @esc_url($options['wordpress']) . '">';
	$html .= '<p class="description">' .@$args[0]. '</p>';
	echo $html;
}

function toggle_yahoo_social_callback($args)
{
	$options = get_option('videotouch_social');

	$html = '<input type="text" name="videotouch_social[yahoo]" value="'. @esc_url($options['yahoo']) . '">';
	$html .= '<p class="description">' .@$args[0]. '</p>';
	echo $html;
}

function toggle_youtube_social_callback($args)
{
	$options = get_option('videotouch_social');

	$html = '<input type="text" name="videotouch_social[youtube]" value="'. @esc_url($options['youtube']) . '">';
	$html .= '<p class="description">' .@$args[0]. '</p>';
	echo $html;
}

function toggle_facebook_social_callback($args)
{
	$options = get_option('videotouch_social');

	$html = '<input type="text" name="videotouch_social[facebook]" value="'. @esc_url($options['facebook']) . '">';
	$html .= '<p class="description">' .$args[0]. '</p>';

	echo $html;
}

function toggle_flickr_social_callback($args)
{
	$options = get_option('videotouch_social');

	$html = '<input type="text" name="videotouch_social[flickr]" value="'. @esc_url($options['flickr']) . '">';
	$html .= '<p class="description">' .@$args[0]. '</p>';
	echo $html;
}

function toggle_pinterest_social_callback($args)
{
	$options = get_option('videotouch_social');

	$html = '<input type="text" name="videotouch_social[pinterest]" value="'. @esc_url($options['pinterest']) . '">';
	$html .= '<p class="description">' .@$args[0]. '</p>';
	echo $html;
}

function toggle_instagram_social_callback($args)
{
	$options = get_option('videotouch_social');
	$instagram = (isset($options['instagram']) && !empty($options['instagram'])) ? esc_url($options['instagram']) : '';

	$html = '<input type="text" name="videotouch_social[instagram]" value="'. $instagram .'">';
	$html .= '<p class="description">'. @$args[0] .'</p>';
	echo $html;
}

function toggle_social_new_callback($args)
{
	$options = get_option('videotouch_social');
	$html = '';
	$i = 1;

	if( isset($options) && isset($options['social_new']) && is_array($options['social_new']) && !empty($options['social_new']) ){
		$html = '<ul>';
		foreach($options['social_new'] as $key=>$option){
			$image_url = (isset($option['image'])) ? $option['image'] : '';
			$url_social = (isset($option['url'])) ? $option['url'] : '';
			$color = (isset($option['color'])) ? $option['color'] : '';
			$key_clean = (isset($key) && (int)$key !== 0) ? (int)$key : '';

			$html .= '<li>
						<div class="sortable-meta-element">
		            		<span class="tab-arrow icon-down"></span> <span class="social-item-tab ts-multiple-item-tab">' . __('Social item:', 'touchsize') . ' ' . $i .'</span>
		             	</div>
		    			<div class="hidden">
					        <table>
					             <tr>
					               <td>' . __( "Social icon", "touchsize" ) . '</td>
					               <td>
					                     <input id="" type="text" data-role="media-url" name="videotouch_social[social_new][' . $i . '][image]" value="' . $image_url . '"/>
					                     <input id="ts_upload-' . $i . '" type="button" class="button ts-upload-social-image ts-multiple-item-upload" value="' . __( "Upload", "touchsize" ) . '" />
					                 </td>
					             </tr>
					             <tr>
					                 <td>
					                     <label for="social-url">' . __('Enter url social here', 'touchsize') . '</label>
					                 </td>
					                 <td>
					                    <input value ="' .  $url_social . '" type="text" name="videotouch_social[social_new][' .  $i . '][url]" />
					                 </td>
					             </tr>
					             <tr>
					                 <td>
					                     <label for="social-color">' . __('Color hover:', 'touchsize') . '</label>
					                 </td>
					                 <td>
					                     <input type="text" value="' . $color . '" class="colors-section-picker" name="videotouch_social[social_new][' . $i . '][color]" />
					                     <div class="colors-section-picker-div"></div>
					                 </td>
					             </tr>
					        </table>
			        		<input type="button" class="button button-primary remove-item" value="' . __('Remove', 'touchsize') . '" /></div>
			        	</div>
			       	</li>';

		    $i++;
		}
		$html .= '</ul>';
	}

	$html .= '<ul id="social_items">
 			</ul>
	 		<input type="hidden" id="social_content" value="" />
 			<input type="button" class="button ts-multiple-add-button" data-element-name="social" id="social_add_item" value="' . __('Add New Icon', 'touchsize') . '" />';
 	$html .= '<script id="social_items_template" type="text/template">
	     		<li id="list-item-id-{{item-id}}" class="social-item ts-multiple-add-list-element">
		            <div class="sortable-meta-element">
		            	<span class="tab-arrow icon-down"></span> <span class="social-item-tab ts-multiple-item-tab">Item: {{slide-number}}</span>
		            </div>
		            <div class="hidden">

			        <table>
			             <tr>
			               <td>'.__( "Social icon", "touchsize" ).'</td>
			               <td>
			                    <input type="text" data-role="media-url" name="videotouch_social[social_new][{{item-id}}][image]" id="social-{{item-id}}-image" value=""/>
			                    <input type="button" id="uploader_{{item-id}}"  class="button ts-upload-social-image ts-multiple-item-upload" value="' . __( "Upload", "touchsize" ) . '" />
			                 </td>
			             </tr>
			             <tr>
			                 <td>
			                     <label for="social-{{item-id}}-url">' . __('Enter url social here:', 'touchsize') . '</label>
			                 </td>
			                 <td>
			                    <input type="text" name="videotouch_social[social_new][{{item-id}}][url]" />
			                 </td>
			             </tr>
			             <tr>
			                 <td>
			                     <label for="social-color">' . __('Color hover:', 'touchsize') . '</label>
			                 </td>
			                 <td>
			                     <input type="text" value="#777" class="colors-section-picker" name="videotouch_social[social_new][{{item-id}}][color]" />
			                     <div class="colors-section-picker-div" id="social-{{item-id}}-color-picker"></div>
			                 </td>
			             </tr>
			        </table>
		        	<input type="button" class="button button-primary remove-item" value="' . __('Remove', 'touchsize') . '" /></div>
	     		</li>
     		</script>';
    $html .= '<p class="description">' .@$args[0]. '</p>';
	echo $html;
}

function videotouch_css_options()
{
	if( false === get_option( 'videotouch_css' ) ) {
		add_option( 'videotouch_css' );
		$data = array(
			'css' => ''
		);

		update_option('videotouch_css', $data);
	}

	// Register a section
	add_settings_section(
		'css_section',
		__( 'Custom css', 'touchsize' ),
		'videotouch_css_callback',
		'videotouch_css'
	);

	register_setting( 'videotouch_css', 'videotouch_css');

} // END videotouch_css_options()

add_action( 'admin_init', 'videotouch_css_options' );

/**************************************************
 * Single post Section Callbacks
 *************************************************/

function videotouch_css_callback()
{
	echo '<p>'.__( 'Insert here your custom CSS', 'touchsize' ).'</p>';

	$options = get_option('videotouch_css');

	$html = '<textarea name="videotouch_css[css]" cols="80" rows="30">' . @$options['css']. '</textarea>';
	echo $html;

} // END videotouch_css_callback()

function videotouch_sidebars_options()
{
	if( false === get_option( 'videotouch_sidebars' ) ) {
		add_option( 'videotouch_sidebars' );
		update_option( 'videotouch_sidebars', array() );
	}

	// Register a section
	add_settings_section(
		'sidebars_section',
		__( 'Sidebars', 'touchsize' ),
		'videotouch_sidebars_callback',
		'videotouch_sidebars'
	);

	register_setting( 'videotouch_sidebars', 'videotouch_sidebars');

} // END videotouch_sidebars_options()

add_action( 'admin_init', 'videotouch_sidebars_options' );

/**************************************************
 * Sidebars Section Callbacks
 *************************************************/

function videotouch_sidebars_callback()
{
	echo '<p>'.__( 'Manage your theme sidebars from here', 'touchsize' ).'</p>';

	$sidebars = get_option('videotouch_sidebars');
	$html = '';

	if (isset($sidebars)) {
		$html .= '<table cellpadding="10" id="ts-sidebars">';

		foreach ($sidebars as $id => $sidebar) {
			$html .= '
			<tr>
				<td class="dynamic-sidebar">'.$sidebar. '</td>
				<td><a href="#" id="'.$id.'" class="ts-remove-sidebar">'.__( 'Delete', 'touchsize' ).'</a></td>
			</tr>';
		}
		$html .= '</table>';
	}

	$html .= '
		<input type="text" name="sidebar_name" id="ts_sidebar_name" />
		<input type="submit" name="add_sidebar" id="ts_add_sidebar" class="button-primary" value="'.__( 'Add sidebar', 'touchsize' ).'" />
		<br/><br/><br/>';
	echo $html;

} // END videotouch_sidebars_callback()

function videotouch_init_impots_options()
{
	if( false === get_option( 'videotouch_impots_options' ) ) {
		add_option( 'videotouch_impots_options', array() );
	}

	// Register a section
	add_settings_section(
		'videotouch_impots_options_section',
		__( 'Import Options', 'touchsize' ),
		'videotouch_impots_options_callback',
		'videotouch_impots_options'
	);

	add_settings_field(
		'import_demo',
		__( 'Import demo', 'touchsize' ),
		'videotouch_import_demo_callback',
		'videotouch_impots_options',
		'videotouch_impots_options_section',
		array(
			__( 'Import demo settings', 'touchsize' )
		)
	);

	add_settings_field(
		'reset_settings',
		__( 'Reset settings', 'touchsize' ),
		'videotouch_reset_settings_callback',
		'videotouch_impots_options',
		'videotouch_impots_options_section',
		array(
			__( 'Reset your settings to default.', 'touchsize' )
		)
	);

	register_setting( 'videotouch_impots_options', 'videotouch_impots_options');

} // END videotouch_css_options()

add_action( 'admin_init', 'videotouch_init_impots_options' );

function videotouch_impots_options_callback($args)
{
	$file_data = '';

	$file_headers = @get_headers(get_template_directory_uri() . '/import-data/import.txt');
	if($file_headers[0] !== 'HTTP/1.1 404 Not Found') {
		$file_data = wp_remote_fopen(get_template_directory_uri() . '/import-data/import.txt');
	}

	echo '<p>' . __( 'Proceed with caution. Warning! You <b style="color: #E75750">WILL lose all your current settings FOREVER</b> if you paste the import data and click "Save changes". Double check everything!', 'touchsize' ) . '</p>';

	if (isset($_GET['updated'])) {
		if ($_GET['updated'] === 'true') {
			echo '<div class="sucess">' . __('Options are successfully imported', 'touchsize').'</div>';
		} else {
			echo '<div class="error">' . __("Options can't be imported. Inserted data can't be decoded properly", 'touchsize').'</div>';
		}
	}
?><br>
	<form action="<?php echo admin_url('admin.php?page=videotouch&tab=save_options') ?>" method="POST">
		<textarea data-import-demo="<?php echo $file_data; ?>" name="encoded_options" id="ts_encoded_options" cols="30" rows="10"><?php echo esc_attr(videotouch_exports_options()); ?></textarea>
		<br><br>
		<input type="submit" name="ts_submit_button" class="button" value="Save changes">

		<script>
			jQuery(document).ready(function($) {

				$(document).on('click', '#ts_encoded_options', function(event) {
					event.preventDefault();
					$('#ts_encoded_options').select();
				});
			});
		</script>
	</form>
<?php
}

function videotouch_import_demo_callback(){
	$html = '<div class="import-demo">
				<button id="import-demo" class="button">'
					 . __( "Import demo settings", "touchsize" ) .
				'</button>
				<div style="display:none;" class="ts-import-demo">' . __('Please wait ...', 'touchsize') . '</div>
			</div>';
	$html .= '<p class="description">' . @$args[0] . '</p>';
	echo $html; ?>
	<script>
		jQuery("button#import-demo").click(function(){

			//import_true = confirm('Are you sure to import dummy content ? It will overwrite the existing data');
			//if(import_true == false) return;

			var jsonData = jQuery("#ts_encoded_options").attr("data-import-demo");
			jQuery("#ts_encoded_options").val(jsonData);
			jQuery('[name="ts_submit_button"]').trigger('click');

		    /*var data = {
		        'action': 'ts_import'
		    };

			jQuery('.ts-import-demo').css('display', '');
		    jQuery.post(ajaxurl, data, function(response) {

		    	if(response){
		    		jQuery('.ts-import-demo').css('display', 'none');
		    		//jQuery('[name="ts_submit_button"]').trigger('click');
		    	}
		    });*/
		});
	</script>
<?php
}

function videotouch_reset_settings_callback(){
	if( isset($_POST['reset-settings']) ){
		$expots_options = array(
			'videotouch_general',
			'videotouch_image_sizes',
			'videotouch_layout',
			'videotouch_colors',
			'videotouch_styles',
			'videotouch_typography',
			'videotouch_single_post',
			'videotouch_page',
			'videotouch_social',
			'videotouch_css',
			'videotouch_sidebars',
			'videotouch_header',
			'videotouch_header_templates',
			'videotouch_header_template_id',
			'videotouch_footer',
			'videotouch_footer_templates',
			'videotouch_footer_template_id',
			'videotouch_footer_template_id',
			'videotouch_page_template_id',
			'videotouch_theme_advertising',
			'videotouch_theme_update'
		);

		foreach ($expots_options as $option) {
			delete_option($option);
		}
	}
?>
	<form action="<?php echo admin_url('admin.php?page=videotouch&tab=impots_options') ?>" method="POST">
		<input type="submit" name="reset-settings" class="button" value="<?php _e('Reset settings', 'touchsize'); ?>">
	</form>
<?php
}

// ========================================================================================
// TouchSize news and alerts ==============================================================
// ========================================================================================

function videotouch_red_area()
{
	if( false === get_option( 'videotouch_red_area' ) ) {
		$data = array(
			'news' => '',
			'alert' => array(
				'id' => 0,
				'message' => ''
			),
			'hidden_alerts' => array(),
			'time' => time()
		);

		add_option( 'videotouch_red_area', $data );
	}

	// Register a section
	add_settings_section(
		'videotouch_red_area',
		__( 'Red Area', 'touchsize' ),
		'videotouch_red_area_callback',
		'videotouch_red_area'
	);

	register_setting( 'videotouch_red_area', 'videotouch_red_area');

} // END videotouch_css_options()

add_action( 'admin_init', 'videotouch_red_area' );

function videotouch_red_area_callback() {

	echo '<div class="red-last-news">';
	echo '<h4>'.__( 'Latest news', 'touchsize' ).'</h4>';

	$options = get_option('videotouch_red_area', array());

	if (isset($options['news'])) {
		echo $options['news'];
	}
	echo '</div>';
}

function videotouch_theme_update_callback(){

	echo '<p>'.__( 'Update your Theme from the WordPress Dashboard', 'touchsize' ).'</p>';

	$theme_update_options = get_option('videotouch_theme_update');

	$theme_update = (isset($theme_update_options['update_options'])) ? $theme_update_options['update_options'] : '';

	$html = '';
	$html .= '<p>Your Themeforest User Name:</p>
	          <input type="text" name="videotouch_theme_update[update_options][user_name]" value="'.  trim(esc_attr($theme_update['user_name'])) .'" />';

	$html .= '<p>Your Themeforest API Key:</p>
	          <input type="text" name="videotouch_theme_update[update_options][key_api]" value="'.  trim(esc_attr($theme_update['key_api'])) .'" />';

	if($update = check_for_theme_update()){
		$html .= '<p>You have update for your theme</p>';
	}

	echo $html;
}

function check_for_theme_update(){
	$updates = get_site_transient('update_themes');

	if(!empty($updates) && !empty($updates->response))
	{
		$theme = wp_get_theme();

		if($key = array_key_exists($theme->get_template(), $updates->response))
		{
			return $updates->response[$theme->get_template()];
		}
	}

	return false;

}
?>
